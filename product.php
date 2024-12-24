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

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background: radial-gradient(#fff, #ffd6d6);
        }
        
    </style>
</head>
<body>
<!-- Navigation -->
<div class="container">
    <div class="navbar">
        <div class="logo">
            <img src="images/logo.png" width="125px">
        </div>
        <nav>
            <ul id="menu-items">
                <li><a href="index.html">Home</a></li>
                <li><a href="product.php">Products</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="privacypolicy.html">Privacy Policy</a></li>
                <?php
                if (isset($_SESSION['admin'])) {
                    echo '<li><a href="admin.php">Account</a></li>';
                } elseif (isset($_SESSION['user_id'])) {
                    echo '<li><a href="profile.php">Account</a></li>';
                } else {
                    echo '<li><a href="account.php">Account</a></li>';
                }
                ?>
                
            </ul>
        </nav>
        <a href="cart.php"><img src="Images/cart.png" width="30px" height="30px"></a>
        <img src="Images/menu.jpg" class="menu-icon" onclick="menuToggle()">
    </div>
</div>

<div class="small-container">
    <h1>Products</h1>

    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-4">';
                // Determine the appropriate redirect URL based on detail_page value
                $detailPage = (int)$row['detail_page'];
                $redirectUrl = "pro{$detailPage}.php";
                echo '<a href="' . $redirectUrl . '"><img src="' . htmlspecialchars($row['image_path']) . '"></a>';
                echo '<h4>' . htmlspecialchars($row['name']) . '</h4>';
                echo '<p>$' . htmlspecialchars($row['price']) . '</p>';
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
        ?>
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
    var MenuItems = document.getElementById("menu-items");
    MenuItems.style.maxHeight = "0px";

    function menuToggle() {
        if (MenuItems.style.maxHeight == "0px") {
            MenuItems.style.maxHeight = "200px";
        } else {
            MenuItems.style.maxHeight = "0px";
        }
    }
</script>
<style>
    h1 {
        text-align: center;
        animation-name: slideIn;
        animation-duration: 1s;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .button-with-image {
        display: flex;
        align-items: center;
        padding: 5px 10px;
        background-color: #f0f0f0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .button-with-image img.logout-icon {
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }

    .footer {
        background: black;
        color: white;
        font-size: 14px;
        padding: 20px 0;
    }

    .footer .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer img {
        width: 50%;
    }

    .footer ul {
        list-style-type: none;
        padding: 0;
    }

    .footer ul li {
        padding: 5px 0;
    }

    .footer hr {
        margin: 10px 0;
        border: none;
        height: 1px;
        background-color: #777;
    }

    .footer .Copyright {
        text-align: center;
        padding-top: 10px;
        font-size: 12px;
    }

    .menu-icon {
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: none;
    }

    @media screen and (max-width: 768px) {
        .menu-icon {
            display: block;
        }

        ul {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-4 {
        flex: 25%;
        max-width: 25%;
        padding: 0 10px;
        margin-bottom: 20px;
    }

    .col-4 img {
        width: 100%;
        border-radius: 10px;
    }

    .col-4 h4 {
        text-align: center;
        margin-top: 10px;
    }

    .col-4 p {
        text-align: center;
        margin-top: 10px;
        font-weight: bold;
    }

</style>
</body>
</html>
