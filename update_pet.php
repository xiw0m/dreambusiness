<?php
// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "pet_grooming_salon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = $_POST['customer_id'];
    $petName = $_POST['pet_name'];
    $petBirthday = $_POST['pet_birthday'];
    $phoneNumber = $_POST['phone_number'];

    // Update customer information
    $sql = "UPDATE customers SET pet_name = ?, pet_birthday = ?, phone_number = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $petName, $petBirthday, $phoneNumber, $customerId);

    if ($stmt->execute()) {
        echo "Customer updated successfully.";
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating customer: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];

    $sql = "SELECT * FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $petName = $row['pet_name'];
        $petBirthday = $row['pet_birthday'];
        $phoneNumber = $row['phone_number'];
    } else {
        echo "Customer not found.";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Pet</title>
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
    <h2>Update Pet</h2>
    <form method="post" action="">
        <input type="hidden" name="customer_id" value="<?php echo $customerId; ?>">
        <label for="pet_name">Pet Name:</label>
        <input type="text" name="pet_name" value="<?php echo $petName; ?>" required><br>
        <label for="pet_birthday">Pet Birthday:</label>
        <input type="date" name="pet_birthday" value="<?php echo $petBirthday; ?>" required><br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo $phoneNumber; ?>" required><br>
        <input type="submit" value="Update Pet">
    </form>
    <a href="http://localhost/TAPNIO/pet_grooming_salon/index.php">Back</a> 
</body>
</html>