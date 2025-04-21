<?php

include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "" || $_SESSION['role'] == "Admin") {
    header('location:../index.php');
}

include_once "headeruser.php";
$useremail = $_SESSION['useremail'];
$query_user = "SELECT company_name, phone FROM tbl_user WHERE useremail = :useremail";
$stmt_user = $pdo->prepare($query_user);
$stmt_user->execute(['useremail' => $useremail]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

$company_name = $user['company_name'] ?? 'Your Business Name'; // Fallback if null
$contact_phone = $user['phone'] ?? '987654321'; // Fallback if null

// Remove the infinite loop and execute the script once
    $phone_numbers = [];
    $due_amounts = [];

    // Query to fetch phone numbers, names, due amounts, and invoice IDs
    $query = "SELECT c.phone, c.name, i.due, i.invoice_id 
              FROM tbl_customer c
              INNER JOIN tbl_invoice i ON c.customer_id = i.customer_id
              WHERE i.due > 0";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        // UltraMsg API details
        $instance_id = "instance114870"; // 🔁 Replace with your real instance ID
        $token = "xkfkptyqmdu1qvvh";     // 🔁 Replace with your real token

        foreach ($results as $row) {
            $to = "+91" . $row['phone']; // Add country code
            $name = $row['name'];
            $due_amount = $row['due'];
            $invoice_id = $row['invoice_id'];

            // Personalized message
            $due_date = date('F 5, Y'); // Dynamically set the 5th of the current month as the due date
            $message = "DUE Alert! 

            Hello $name,
            This is a gentle reminder from  $company_name regarding your monthly due.
            Invoice ID : $invoice_id
            Due Amount: ₹ $due_amount
            Due Date: $due_date
            Kindly make the payment at your earliest convenience.
            If you've already paid, please ignore this message.
            Thank you for your continued support!
            For any queries, feel free to contact us.

             $company_name
            📞 [$phone_numbers]";

            $data = array(
                "token" => $token,
                "to" => $to,
                "body" => $message
            );

            $url = "https://api.ultramsg.com/$instance_id/messages/chat";

            $options = array(
                "http" => array(
                    "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
                    "method"  => "POST",
                    "content" => http_build_query($data),
                ),
            );

            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }
    }



?>