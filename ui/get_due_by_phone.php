<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include_once 'connectdb.php';
session_start();

// Database connection
$connection = new mysqli("localhost", "root", "", "pos_barcode_db"); // Update with correct credentials

// Check connection
if ($connection->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $connection->connect_error]);
    exit;
}

// Get the phone number from the request
$phone = $_GET['phone'] ?? '';

if (empty($phone)) {
    echo json_encode(["error" => "Phone number is required"]);
    exit;
}

// Use prepared statements to prevent SQL injection
$sql = "SELECT COALESCE(SUM(i.due), 0) AS total_due 
        FROM tbl_invoice i
        INNER JOIN tbl_customer c ON i.customer_id = c.customer_id
        WHERE c.customer_phone = ?"; // Use COALESCE to handle null sums

$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(["total_due" => $row["total_due"] ?? 0]);
} else {
    echo json_encode(["error" => "Failed to fetch data"]);
}

$stmt->close();
$connection->close();
?>
