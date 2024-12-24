<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookWorld";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize input
$name = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    die("Error: Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL query with sanitized inputs
$sql = "INSERT INTO tbluser (username, email, password) VALUES ('$name', '$email', '$hashed_password')";

// Execute the query
if ($conn->query($sql) === TRUE) {
    header("Location: account.php?message=Registration successful. Please log in.");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
