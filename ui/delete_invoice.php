<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "" || $_SESSION['role'] == "") {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['invoice_id']) && !empty($_POST['invoice_id'])) {
        $invoice_id = $_POST['invoice_id'];

        try {
            $pdo->beginTransaction();

            // Delete from customer_ids table
            $deleteCustomerIds = $pdo->prepare("DELETE FROM customer_ids WHERE invoice_id = :invoice_id");
            $deleteCustomerIds->bindParam(':invoice_id', $invoice_id);
            $deleteCustomerIds->execute();

            if ($deleteCustomerIds->rowCount() === 0) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Invoice ID not found in customer_ids table.']);
                exit;
            }

            // Update payment_type to 'CASH' in tbl_invoice
            $updatePaymentType = $pdo->prepare("UPDATE tbl_invoice SET payment_type = 'CASH' WHERE invoice_id = :invoice_id");
            $updatePaymentType->bindParam(':invoice_id', $invoice_id);
            $updatePaymentType->execute();

            $updatePaymentType = $pdo->prepare("UPDATE tbl_invoice SET paid = total WHERE invoice_id = :invoice_id");
            $updatePaymentType->bindParam(':invoice_id', $invoice_id);
            $updatePaymentType->execute();

            $updatePaymentType = $pdo->prepare("UPDATE tbl_invoice SET due = 0 WHERE invoice_id = :invoice_id");
            $updatePaymentType->bindParam(':invoice_id', $invoice_id);
            $updatePaymentType->execute();

            if ($updatePaymentType->rowCount() === 0) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to update payment type. Invoice ID may not exist in tbl_invoice.']);
                exit;
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Invoice deleted and payment type updated to CASH.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invoice ID is required.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>

