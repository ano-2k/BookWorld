<?php
session_start();

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

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM tbluser WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        if ($email === 'anoak123@gmail.com' && $password === 'anoak') {
            $_SESSION['admin'] = true;
            header("Location: admin.php");
        } else {
            header("Location: profile.php");
        }
    } else {
        echo "Invalid password. Please try again.";
    }
} else {
    echo "No user found with that email address.";
}

$conn->close();
?>
