import pandas as pd
import pymysql
import mysql.connector
from mysql.connector import Error
import matplotlib.pyplot as plt
import seaborn as sns
import sys
import json
from sklearn.ensemble import RandomForestRegressor
from sqlalchemy import create_engine

# DB connection config
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'pos_barcode_db'
}
engine = create_engine("mysql+pymysql://root:@localhost/pos_barcode_db")

# 🔧 Convert NumPy types to native Python types
def convert_numpy_types(obj):
    if isinstance(obj, list):
        return [convert_numpy_types(item) for item in obj]
    elif isinstance(obj, dict):
        return {k: convert_numpy_types(v) for k, v in obj.items()}
    elif hasattr(obj, 'item'):
        return obj.item()
    else:
        return obj

def get_data(from_date, to_date):
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        if conn.is_connected():
            query = f"""
            SELECT 
                i.order_date AS sale_date,
                d.product_id AS product_id,
                p.product AS product_name,
                d.qty AS quantity,
                d.saleprice AS price
            FROM tbl_invoice_details d
            JOIN tbl_invoice i ON d.invoice_id = i.invoice_id
            JOIN tbl_product p ON d.product_id = p.pid
            WHERE i.order_date BETWEEN '{from_date}' AND '{to_date}';
            """
            df = pd.read_sql(query, engine)
            return df
        else:
            return pd.DataFrame()
    except Error as e:
        sys.stdout.write(json.dumps({'error': f'Database connection failed: {str(e)}'}))
        sys.exit(1)
    finally:
        if 'conn' in locals() and conn.is_connected():
            conn.close()

def prepare_data(df):
    df['sale_date'] = pd.to_datetime(df['sale_date'])
    df['month'] = df['sale_date'].dt.to_period('M')
    df['sales_price'] = df['quantity'] * df['price']
    return df

def product_level_forecasting(df, growth_factor=None):
    results = []
    monthly_all = []

    for (product_id, product_name), group in df.groupby(['product_id', 'product_name']):
        temp = group.copy()
        temp['sales_price'] = temp['quantity'] * temp['price']

        monthly = temp.groupby('month').agg({
            'quantity': 'sum',
            'sales_price': 'sum'
        }).reset_index()

        monthly['month'] = monthly['month'].astype(str)
        monthly['month_num'] = range(len(monthly))
        monthly_all.append(monthly.assign(product_id=product_id, product_name=product_name))

        if len(monthly) > 1:
            monthly['quantity_growth'] = monthly['quantity'].pct_change() * 100
            avg_growth_rate = monthly['quantity_growth'].mean()
        else:
            avg_growth_rate = 0

        growth_target = group['quantity'].sum() * (1 + (avg_growth_rate if growth_factor is None else growth_factor) / 100)

        if len(monthly) > 0:
            model = RandomForestRegressor(n_estimators=100, random_state=42)
            model.fit(monthly[['month_num']], monthly['quantity'])
            next_month = pd.DataFrame([[monthly['month_num'].max() + 1]], columns=['month_num'])
            predicted_raw = model.predict(next_month)[0]
            predicted_quantity = max(predicted_raw, growth_target)
        else:
            predicted_quantity = growth_target

        total_sold_quantity = group['quantity'].sum()
        total_sales_price = group['sales_price'].sum()

        results.append({
            'product_id': int(product_id),
            'product_name': product_name,
            'total_sold_quantity': int(total_sold_quantity),
            'total_sales_price': round(float(total_sales_price), 2),
            'predicted_next_month': round(predicted_quantity)
        })

    monthly_all_df = pd.concat(monthly_all)
    return results, monthly_all_df

def overall_forecasting(df, growth_factor=None):
    monthly = df.groupby(df['sale_date'].dt.to_period('M')).agg({
        'quantity': 'sum',
        'sales_price': 'sum'
    }).reset_index()

    monthly['month'] = monthly['sale_date'].astype(str)
    monthly['month_num'] = range(len(monthly))

    total_quantity = df['quantity'].sum()
    total_sales = df['sales_price'].sum()
    avg_price_per_unit = total_sales / total_quantity if total_quantity else 0

    if len(monthly) > 1:
        monthly['quantity_growth'] = monthly['quantity'].pct_change() * 100
        avg_growth_rate = monthly['quantity_growth'].mean()
    else:
        avg_growth_rate = 0

    growth_target = total_quantity * (1 + (avg_growth_rate if growth_factor is None else growth_factor) / 100)

    if len(monthly) > 0:
        model = RandomForestRegressor(n_estimators=100, random_state=42)
        model.fit(monthly[['month_num']], monthly['quantity'])
        next_month = pd.DataFrame([[monthly['month_num'].max() + 1]], columns=['month_num'])
        predicted_raw = model.predict(next_month)[0]
        predicted_quantity = max(predicted_raw, growth_target)
    else:
        predicted_quantity = growth_target

    predicted_sales_value = round(predicted_quantity * avg_price_per_unit, 2)

    return {
        'total_sold_quantity': int(total_quantity),
        'total_sales_price': round(float(total_sales), 2),
        'predicted_next_month_quantity': round(predicted_quantity),
        'predicted_next_month_sales': predicted_sales_value
    }, monthly

def visualize(monthly_all_df, overall_monthly, overall_pred):
    plt.figure(figsize=(12, 6))
    sns.lineplot(data=overall_monthly, x='month', y='quantity', marker='o', label='Overall Sales')
    for product_id in monthly_all_df['product_id'].unique():
        sub = monthly_all_df[monthly_all_df['product_id'] == product_id]
        sns.lineplot(x='month', y='quantity', data=sub, label=sub['product_name'].iloc[0], marker='o')

    plt.axhline(overall_pred['predicted_next_month_quantity'], color='red', linestyle='--', label='Predicted Next Month')
    plt.title('Sales Trends by Product and Overall')
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.legend()
    plt.savefig('sales_trend.png')
    plt.close()

def main(from_date, to_date):
    try:
        df = get_data(from_date, to_date)
        if df.empty:
            output = {"error": "No sales data in selected range."}
        else:
            df = prepare_data(df)
            product_forecasts, monthly_all = product_level_forecasting(df)
            overall_result, overall_monthly = overall_forecasting(df)

            # 👉 SCALE product-level predictions to match overall prediction
            total_predicted_product_qty = sum(p['predicted_next_month'] for p in product_forecasts)
            overall_predicted_qty = overall_result['predicted_next_month_quantity']

            if total_predicted_product_qty > 0:
                scale_factor = overall_predicted_qty / total_predicted_product_qty
            else:
                scale_factor = 1

            for product in product_forecasts:
                original_pred_qty = product['predicted_next_month']
                scaled_pred_qty = round(original_pred_qty * scale_factor)
                product['predicted_next_month'] = scaled_pred_qty

            # Recalculate total predicted revenue from scaled product forecasts
            predicted_total_revenue = 0
            for product in product_forecasts:
                avg_price = product['total_sales_price'] / product['total_sold_quantity'] if product['total_sold_quantity'] else 0
                predicted_total_revenue += product['predicted_next_month'] * avg_price

            predicted_total_revenue = round(predicted_total_revenue, 2)
            overall_result['predicted_next_month_sales'] = predicted_total_revenue

            visualize(monthly_all, overall_monthly, overall_result)

            output = {
                "Overall Sales Summary": {
                    "Total Sales in Selected Range": overall_result['total_sold_quantity'],
                    "Total Revenue in Selected Range": overall_result['total_sales_price'],
                    "Predicted Overall Sales for Next Month (Qty)": overall_result['predicted_next_month_quantity'],
                    "Predicted Overall Sales for Next Month (Revenue)": overall_result['predicted_next_month_sales']
                },
                "Product-wise Forecasts": product_forecasts
            }

        sys.stdout.write(json.dumps(convert_numpy_types(output)))

        with open("python_debug_log.txt", "a") as f:
            f.write(f"Received FROM: {from_date}, TO: {to_date}\n")

    except Exception as e:
        sys.stdout.write(json.dumps({"error": f"An error occurred: {str(e)}"}))

if __name__ == "__main__":
    if len(sys.argv) == 3:
        from_date = sys.argv[1]
        to_date = sys.argv[2]
        main(from_date, to_date)
    else:
        sys.stdout.write(json.dumps({"error": "Missing date arguments."}))
