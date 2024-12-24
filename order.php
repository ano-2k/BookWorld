<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM user_orders WHERE user_id = $user_id ORDER BY order_date DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="Images/icon.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
    body {
    font-family: Arial, sans-serif;
    background: radial-gradient(#fff, #ffd6d6);
    margin: 0;
    padding: 0;
}

.container2 {
    width: 80%;
    margin: 30px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    color: #333;
    
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border: 2px solid #ff523b;
}

th, td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
    color: #333;
}

tr:hover {
    background-color: #f5f5f5;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

img {
    max-width: 100px;
    height: auto;
    border-radius: 5px;
}

.no-orders {
    text-align: center;
    font-size: 18px;
    color: #666;
    padding: 20px 0;
}
tbody, th {
  
    font-family: 'Arial', sans-serif; 
    font-size: 16px;
    color: #333; 
}
th{
    font-weight: bold; 
}


</style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.html"><img src="Images/logo.png" width="125px"></a>
            </div>
            <nav>
                <ul id="menu-items">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="product.php">Products</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="privacypolicy.html">Privacy Policy</a></li>
                    <li><a href="account.php">Account</a></li>
                </ul>
            </nav>
            <a href="cart.php"><img src="Images/cart.png" width="30px" height="30px"></a>
            <img src="Images/menu.jpg" class="menu-icon" onclick="menuToggle()">
        </div>
    </div>


    <div class="container2">
        <h2>My Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='" . $row['image_path'] . "' alt='Product Image'></td>";
                        echo "<td>" . $row['product_name'] . "</td>";
                        echo "<td>" . $row['quantity'] . "</td>";
                        echo "<td>$" . number_format($row['subtotal'], 2) . "</td>";
                        echo "<td>" . $row['order_date'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-orders'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3>Download Our App</h3>
                    <p>Download the app for Android and iOS mobile phones.</p>
                    <div class="app-logo">
                        <img src="Images/ANDROID-LO.png">
                        <img src="Images/Apple.png">
                    </div>
                </div>
                <div class="footer-col-2">
                    <img src="Images/lgo.png">
                    <p>Empowering minds one page at a time, our mission is to spread the joy of reading to all.</p>
                </div>
                <div class="footer-col-3">
                    <h3>Useful Links</h3>
                    <ul>
                        <li>Coupons</li>
                        <li>Blog Post</li>
                        <li>Return Policy</li>
                        <li>Join Affiliate</li>
                    </ul>
                </div>
                <div class="footer-col-4">
                    <h3>Follow Us</h3>
                    <ul>
                        <li>Facebook</li>
                        <li>X</li>
                        <li>Instagram</li>
                        <li>Youtube</li>
                    </ul>
                </div>
            </div>
            <hr>
            <p class="Copyright">Copyright 2024 - BookWorld</p>
        </div>
    </div>

    <script>
        function menuToggle() {
            var MenuItems = document.getElementById("menu-items");
            MenuItems.style.maxHeight = MenuItems.style.maxHeight === "0px" ? "200px" : "0px";
        }
    </script>


</body>
</html>

<?php
$conn->close();
?>
