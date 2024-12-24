<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cart']) || !isset($input['user_id']) || !isset($input['total_amount'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$user_id = $input['user_id'];
$cart = $input['cart'];
$total_amount = $input['total_amount'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookWorld";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->autocommit(FALSE);

try {
    foreach ($cart as $item) {
        $product_name = $conn->real_escape_string($item['name']);
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];
        $subtotal = $quantity * $price;
        $image_path = $conn->real_escape_string($item['image']);

        $sql = "INSERT INTO user_orders (user_id, product_name, quantity, subtotal, image_path) 
                VALUES ('$user_id', '$product_name', '$quantity', '$subtotal', '$image_path')";

        if (!$conn->query($sql)) {
            throw new Exception("Error inserting order: " . $conn->error);
        }
    }

    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Order placed successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>
