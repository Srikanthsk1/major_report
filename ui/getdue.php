<?php
// Database configuration
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database_name';

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the phone number from the AJAX request
$phoneNumber = $_POST['txtphone'];

// Prepare and execute a SQL query to retrieve previous dues for the customer
$sql = "SELECT SUM(due - paid + repay) AS total_due FROM sales WHERE customer_id IN (SELECT id FROM customers WHERE phone = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $phoneNumber);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Output the total due as a JSON response
echo json_encode($row['total_due']);

// Close the database connection
$stmt->close();
$conn->close();
?>