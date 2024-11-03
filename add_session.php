<?php
// Include the database connection file
include 'dbConfig.php';

// Check if customer ID is provided in the URL
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Retrieve customer details
    $sql = "SELECT * FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $customer = $result->fetch_assoc();

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate input (optional, but recommended)
            if (empty($_POST['date_of_grooming']) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $_POST['date_of_grooming'])) {
                echo "Invalid date format.";
                exit();
            }

            if ($_POST['total_price'] <= 0) {
                echo "Total price must be positive.";
                exit();
            }

            // Prepare and bind parameters to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO grooming_sessions (customer_id, date_of_grooming, total_price) VALUES (?, ?, ?)");
            $stmt->bind_param("ids", $customer_id, $_POST['date_of_grooming'], $_POST['total_price']);

            if ($stmt->execute()) {
                echo "New grooming session added successfully!";
                // Redirect to a success page or refresh the current page
                header("Location: add_session.php?customer_id=$customer_id");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        // Retrieve grooming sessions for this customer
        $sql = "SELECT * FROM grooming_sessions WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $grooming_sessions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // ... rest of the HTML code ... (display customer details, grooming sessions, and form)
    } else {
        // Redirect to a page indicating invalid customer ID or back to the previous page
        header("Location: index.php"); // Replace with your desired redirect URL
        exit();
    }
    } else {
    // Redirect to a page indicating missing customer ID or back to the previous page
    header("Location: index.php"); // Replace with your desired redirect URL
    exit();
    }
    ?>
<html>
<head>
    <title>Pet Grooming Salon - Add & View Sessions</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
body {
    font-family: 'Poppins', sans-serif;
    background-color: #fffed7;
    margin: 0;
    padding: 20px;
    text-align: center;
}
form {
    background-color: #e6e6fa;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    width: 80%;
    margin: 0 auto;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 18px;
    color: #333;
}
input[type="text"],
input[type="date"],
input[type="tel"],
input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}
input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
}
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    border: 1px solid #ccc;
    border-spacing: 0;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}
th {
    background-color: #f2f2f2;
    font-weight: bold;
}
a {
    text-decoration: none;
    color: blue;
}
h2 {
    color: #333;
    font-weight: bold;
    margin-bottom: 20px;
}
table th {
    background-color: #f0f0f0;
}
table tr:nth-child(even) {
    background-color: #f2f2f2;
}
    </style>
</head>
<body>
    <h2>Pet Details</h2>
    <p><b>Pet Name:</b> <?php echo $customer['pet_name']; ?></p>
    <p><b>Pet Birthday:</b> <?php echo $customer['pet_birthday']; ?></p>
    <p><b>Phone Number:</b> <?php echo $customer['phone_number']; ?></p>

    <h2>Grooming Sessions</h2>
    <?php if (count($grooming_sessions) > 0) : ?>
        <table>
            <tr>
                <th>Date of Grooming</th>
                <th>Total Price</th>
            </tr>
            <?php foreach ($grooming_sessions as $session) : ?>
                <tr>
                    <td><?php echo $session['date_of_grooming']; ?></td>
                    <td><?php echo $session['total_price']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No grooming sessions found for this customer.</p>
    <?php endif; ?>

    <h2>Add New Session</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="show" name="customer_id" value="<?php echo $customer['customer_id']; ?>">
        <label for="date_of_grooming">Date of Grooming:</label>
        <input type="date" name="date_of_grooming" required><br>

        <label for="total_price">Total Price:</label>
        <input type="number" step="0.01" name="total_price" min="0" required><br>

        <input type="submit" value="Add Session">
    </form>

    <a href="http://localhost/TAPNIO/pet_grooming_salon/index.php">Back</a>  </body>
</html>
