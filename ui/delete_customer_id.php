<?php
include_once 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_id = $_POST['invoice_id'];

    try {
        $delete = $pdo->prepare("DELETE FROM customer_ids WHERE invoice_id = :invoice_id");
        $delete->bindParam(':invoice_id', $invoice_id);

        if ($delete->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the record.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>