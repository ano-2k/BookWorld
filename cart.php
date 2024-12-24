<?php
session_start();


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add product to cart
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!empty($_POST['name']) && !empty($_POST['quantity'])) {
        $name = sanitize_input($_POST['name']);
        $quantity = (int)$_POST['quantity'];

        
        $product_in_cart = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $name) {
                $item['quantity'] += $quantity;
                $product_in_cart = true;
                break;
            }
        }

        if (!$product_in_cart) {
            $_SESSION['cart'][] = array('name' => $name, 'price' => 0, 'quantity' => $quantity, 'image' => 'path_to_image'); // Replace 'path_to_image' with actual image path
        }
    }
}

// Remove product from cart
elseif (isset($_POST['action']) && $_POST['action'] === 'remove') {
    if (!empty($_POST['name'])) {
        $nameToRemove = sanitize_input($_POST['name']);
        $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function($item) use ($nameToRemove) {
            return $item['name'] !== $nameToRemove;
        }));
    }
}

// Update product quantity in cart
elseif (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $name => $quantity) {
        $name = sanitize_input($name);
        $quantity = (int)$quantity;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $name) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}

// Calculate total amount
$cart = $_SESSION['cart'];
$totalAmount = array_reduce($cart, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

// Set cookies for cart items
$cookie_name = "cart_items";
$cookie_value = json_encode($cart);
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
// 86400 = 1 day

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookWorld";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for address
$address_name = "N/A";
$phone = "N/A";
$address = "N/A";
$postal_code = "N/A";

// Retrieve user's address from database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM user_address WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $address_name = htmlspecialchars($row['address_name']);
        $phone = htmlspecialchars($row['phone']);
        $address = htmlspecialchars($row['address']);
        $postal_code = htmlspecialchars($row['postal_code']);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - BookWorld</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="Images/icon.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        .cart-info {
            display: flex;
            align-items: center;
        }
        .cart-info img {
            width: 80px;
            height: 80px;
            margin-right: 10px;
        }
        .remove-btn {
            color: red;
            cursor: pointer;
            text-decoration: underline;
            margin-left: 10px;
        }
        .no-product {
            text-align: center;
            margin-top: 20px;
        }
        .cart-page {
            min-height: 50vh;
        }
        .small-container {
            max-width: 1080px;
            margin: auto;
            padding-left: 25px;
            padding-right: 25px;
        }
        .total-price {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .total-price table {
            border-collapse: collapse;
            width: auto;
            border: 2px solid #8594e1;
            border-radius: 5px;
        }
        .total-price table tr {
            border-bottom: 1px solid #ddd;
        }
        .total-price table tr:last-child {
            border-bottom: none;
        }
        .total-price table td {
            padding: 10px 20px;
            text-align: right;
        }
        .btn {
            background-color: #ff523b;
            color: white;
            padding: 15px 30px;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #f44336;
        }
        #payment {
            margin-top: 50px;
            margin-left:300px ;
        }
        .checkout {
            background-color: #ff523b;
            color: white;
            padding: 15px 30px;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .checkout:hover {
            background-color: #f44336;
        }
        .payment-icons {
            margin-top: 20px;
        }
        .payment-icons i {
            margin-right: 10px;
        }
        .address-container, .payment-container {
            margin-top: 20px;
            border: 2px solid #ff523b;
            border-radius: 50px;
            padding: 20px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            width: 750px;
        }
        .address-container h2, .payment-container h2 {
            margin-bottom: 20px;
            padding-bottom: 5px;
        }
        .address-container table {
            width: 100%;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        .payment-container table{
            width: 100%;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .address-container table td, .payment-container table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .address-container table tr:last-child td, .payment-container table tr:last-child td {
            border-bottom: none;
        }
        .containerr{
            width: 50%;
           margin: left 50px; ;
        }
        body{
            background: radial-gradient(#fff, #ffd6d6);
        }
        h2{
            text-align: center;
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

    <div class="small-container cart-page">
        <?php if (empty($cart)): ?>
            <p class="no-product">No products added to the cart.</p>
        <?php else: ?>
            <form id="cart-form" method="POST">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td>
                                <div class="cart-info">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                    <div>
                                        <p><?php echo $item['name']; ?></p>
                                        <small class="price">$<?php echo number_format($item['price'], 2); ?></small>
                                        <a href="#" class="remove-btn" onclick="removeProduct(event, '<?php echo $item['name']; ?>')">Remove</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="number" style="width: 60px" readonly name="quantities[<?php echo htmlspecialchars($item['name']); ?>]" value="<?php echo $item['quantity']; ?>" min="1" onchange="submitForm()">
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <input type="hidden" name="action" value="update">
            </form>
            <hr>
            <div class="total-price">
                <table>
                    <tr>
                        <td>Total</td>
                        <td>$<?php echo number_format($totalAmount, 2); ?></td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <section id="payment">
        <div class="containerr">
            <div class="address-container">
                <h2>Shipping Address</h2>
                <table>
                    <tr>
                        <td>Name</td>
                        <td id="address-name"><?php echo $address_name; ?></td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td id="address-phone"><?php echo $phone; ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td id="address-details"><?php echo $address; ?></td>
                    </tr>
                    <tr>
                        <td>Postal Code</td>
                        <td id="address-postal"><?php echo $postal_code; ?></td>
                    </tr>
                </table>
                <button id="edit-address-btn" class="checkout" onclick="redirectToAccount()">Edit Address</button>
            </div>
            <div class="payment-container">
                <h2>Total Payment</h2>
                <table>
                    <tr>
                        <td>Discount</td>
                        <td id="payment-subtotal">Rs. 0</td>
                    </tr>
                    <tr>
                        <td>Shipping</td>
                        <td>Free</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td>Rs. 0</td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td id="payment-total"><strong>$<?php echo number_format($totalAmount, 2); ?></strong></td>
                    </tr>
                </table>
                <button id="checkoutBtn" class="checkout" onclick="place_order()">Pay now</button>
                <div class="payment-icons">
                    <i class="fab fa-cc-paypal" style="font-size: 80px;"></i>
                    <i class="fab fa-cc-mastercard" style="font-size: 80px;"></i>
                </div>
            </div>
        </div>
    </section>

    <form id="remove-form" method="POST" style="display: none;">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="name" id="remove-name">
    </form>

    <script>
        function removeProduct(event, name) {
            event.preventDefault();
            document.getElementById('remove-name').value = name;
            document.getElementById('remove-form').submit();
        }

        function submitForm() {
            document.getElementById('cart-form').submit();
        }

        function menuToggle() {
            var MenuItems = document.getElementById("menu-items");
            MenuItems.style.maxHeight = MenuItems.style.maxHeight === "0px" ? "200px" : "0px";
        }

        function redirectToAccount() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.logged_in) {
                window.location.href = 'profile.php';
            } else {
                window.location.href = 'account.php';
            }
        } else {
            alert('Error checking login status. Please try again later.');
        }
    };

    xhr.onerror = function() {
        alert('Error checking login status. Please check your internet connection and try again.');
    };

    xhr.send();
}

function place_order() {
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.logged_in) {
                
                if (confirm("Are you sure you want to place the order?")) {
                
                    var xhrOrder = new XMLHttpRequest();
                    xhrOrder.open('POST', 'place_order.php', true);
                    xhrOrder.setRequestHeader('Content-Type', 'application/json');
                    
                    xhrOrder.onload = function() {
                        if (xhrOrder.status === 200) {
                            var responseOrder = JSON.parse(xhrOrder.responseText);
                            if (responseOrder.status === 'success') {
                             
                                alert("Order placed successfully!");
                                window.location.href = 'profile.php';
                            } else {
                               
                                alert('Error placing order: ' + responseOrder.message);
                            }
                        } else {
                         
                            alert('Error placing order. Please try again later.');
                        }
                    };

                    xhrOrder.onerror = function() {
                       
                        alert('Error placing order. Please check your internet connection and try again.');
                    };

                   
                    var data = {
                        user_id: <?php echo json_encode($_SESSION['user_id']); ?>,
                        cart: <?php echo json_encode($_SESSION['cart']); ?>,
                        total_amount: <?php echo json_encode($totalAmount); ?>,
                    };

                  
                    xhrOrder.send(JSON.stringify(data));
                }
            } else {
               
                alert("You need to log in first to place an order.");
                window.location.href = 'account.php';
            }
        } else {
            
            alert('Error checking login status. Please try again later.');
        }
    };

    
    xhr.onerror = function() {
        alert('Error checking login status. Please check your internet connection and try again.');
    };

    xhr.send();
}

    </script>
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
</body>
</html>
