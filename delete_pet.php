<?php
// Include the database connection file
include 'dbConfig.php';

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];

    // Delete associated grooming sessions first
    $sql = "DELETE FROM grooming_sessions WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();

    // Then, delete the customer
    $sql = "DELETE FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);

    if ($stmt->execute()) {
        echo "Customer deleted successfully.";
        header("Location: index.php"); // Replace with your desired redirect URL
        exit();
    } else {
        echo "Error deleting customer: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Missing customer ID.";
}

$conn->close();