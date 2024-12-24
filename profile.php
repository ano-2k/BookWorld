<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookWorld";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user basic info
$sqlUser = "SELECT * FROM tbluser WHERE id = $user_id";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows > 0) {
    $user = $resultUser->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Fetch user details
$sqlDetails = "SELECT * FROM user_details WHERE user_id = $user_id";
$resultDetails = $conn->query($sqlDetails);
$details = $resultDetails->fetch_assoc();

// Check if details are available and handle null case
$description = isset($details['description']) ? $details['description'] : '';

// Fetch user address
$sqlAddress = "SELECT * FROM user_address WHERE user_id = $user_id";
$resultAddress = $conn->query($sqlAddress);
$address = $resultAddress->fetch_assoc();

// Fetch user orders
$sqlOrders = "SELECT * FROM user_orders WHERE user_id = $user_id";
$resultOrders = $conn->query($sqlOrders);
$orders = $resultOrders->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BookWorld</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="Images/icon.jpg">
    <style>

        html, body {
            height: 100%;
          
           
        }
        body{
          
            flex-direction: column;
           
        }
        .account-page {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .profile-container {
            padding: 20px;
        }
        .profile-section {
            margin-bottom: 20px;
        }
        .profile-section h2 {
            margin-bottom: 10px;
        }
        .welcome-message {
            text-align: center;
            font-size: 24px;
            font-family: 'Poppins', sans-serif;
            animation: fadeIn 2s;
            margin-bottom: 30px;
        }
        .welcome-message img {
            margin-left: 5px;
            height: 20px;
            width: auto; 
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .buttons-container {
            display: flex;
            justify-content: center; 
            gap: 15px; 
            margin-bottom: 20px;
        }
        .buttons-container button {
            background-color: #ff523b;
            color: white;
            padding: 15px 30px;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
        .visible {
            display: block;
        }
        .profile-section form {
            display: flex;
            flex-direction: column;
        }
        .profile-section form input, .profile-section form textarea {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }
        .button-with-image {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
        }
        .button-with-image img {
            margin-left: 5px;
            height: 20px;
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
                    <li><a href="profile.php">Account</a></li>
                </ul>
            </nav>
            <a href="cart.php"><img src="Images/cart.png" width="30px" height="30px"></a>
            <img src="Images/menu.jpg" class="menu-icon" onclick="menuToggle()">
        </div>
    </div>

    <div class="account-page">
        <div class="container">
            <h2 class="welcome-message">  Welcome back to BookWorld  <?php echo htmlspecialchars($user['username']); ?><img src="Images/cool.png" > <img src="Images/book.png" >!</h2>
            <div class="buttons-container">
                <button onclick="window.location.href='order.php'" class="button-with-image" > <img src="Images/shopping-bag.png">My Orders</button>
                <button onclick="window.location.href='updateProfile.php'" class="button-with-image">
    <img src="Images/user.png"> Account Details 
</button>
<button onclick="window.location.href='updateAddress.php'" class="button-with-image">
    <img src="Images/home.png"> Address
</button>
                <button onclick="window.location.href='logout.php'" class="button-with-image" ><img src="Images/logout.png">Logout</button>
            </div>

            <div id="account-details" class="profile-section hidden">
             
                <form method="POST" action="updateProfile.php">
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Name" required>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
                    <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($description); ?></textarea>
                    <input type="password" name="password" placeholder="Enter your password">
                    <input type="password" name="confirm_password" placeholder="Confirm your password">
                    <button type="submit">Save</button>
                </form>
            </div>
            <div id="address" class="profile-section hidden">
               
                <form method="POST" action="updateAddress.php">
                    <input type="text" name="address_name" value="<?php echo htmlspecialchars($address['address_name'] ?? ''); ?>" placeholder="Name">
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($address['phone'] ?? ''); ?>" placeholder="Phone">
                    <textarea name="address" placeholder="Address"><?php echo htmlspecialchars($address['address'] ?? ''); ?></textarea>
                    <input type="text" name="postal_code" value="<?php echo htmlspecialchars($address['postal_code'] ?? ''); ?>" placeholder="Postal Code">
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
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
            var menuItems = document.getElementById("menu-items");
            menuItems.style.maxHeight = menuItems.style.maxHeight === "200px" ? "0px" : "200px";
        }

        function showSection(sectionId) {
        // Hide all sections first
        document.getElementById('account-details').classList.add('hidden');
        document.getElementById('address').classList.add('hidden');
        
        // Show the selected section
        document.getElementById(sectionId).classList.remove('hidden');
    }
    </script>
</body>
</html>
