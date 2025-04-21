<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Path to the batch file
    $batchFilePath = 'c:\\xampp\\htdocs\\posbarcode\\ui\\send_due_notifications.bat';

    // Execute the batch file
    $output = shell_exec("start /B $batchFilePath");

    // Return a success response
    echo json_encode(['success' => true, 'message' => 'Batch file executed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>