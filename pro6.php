<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to find a product in cart by name
function findProductByName($name, $cart) {
    foreach ($cart as $key => $item) {
        if ($item['name'] == $name) {
            return $key;
        }
    }
    return -1;
}

// Add to cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product = [
        "name" => "Haunting Adeline",
        "price" => 10.00,
        "quantity" => (int)$_POST['quantity'],
        "image" => "Images/pro-06.jpg" // Update image URL if needed
    ];

    $cart = $_SESSION['cart'];

    // Check if product already exists in cart
    $index = findProductByName($product['name'], $cart);

    if ($index !== -1) {
        // Product exists, update quantity
        $cart[$index]['quantity'] += $product['quantity'];
    } else {
        // Product does not exist, add to cart
        $cart[] = $product;
    }

    $_SESSION['cart'] = $cart; // Update session cart

    // Redirect to cart page or stay on the current page
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haunting Adeline - BookWorld</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="Images/icon.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body{
            background: radial-gradient(#fff, #ffd6d6);
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

    <div class="small-container single-product">
        <div class="row">
            <div class="col-2">
                <img src="Images/pro-06.jpg" width="75%">
            </div>
            <div class="col-2">
                <h2>Haunting Adeline</h2>
                <h4>H. D. Carlton (Author, Publisher), Teddy Hamilton (Narrator), Michelle Sparks (Narrator)</h4>
                <p><br>The Manipulator<br>I can manipulate the emotions of anyone who lets me.<br>I will make you hurt, make you cry, make you laugh and sigh.<br>But my words don't affect him. Especially not when I plead for him to leave.</p>
                <h3>$10.00</h3>
                <form method="POST">
                    <input type="number" name="quantity" value="1" min="1">
                    <button type="submit" name="add_to_cart" class="btn">Add To Cart</button>
                </form>
            </div>
        </div>
        <div class="small-container">
            <div class="row row-2">
                <h2>Related Products</h2>
                <a href="product.php"><p>View More</p></a>
            </div>
        </div>
    </div>

    <div class="small-container">
        <div class="row">
            <div class="col-4">
                <a href="pro9.php"><img src="Images/pro-09.jpg"></a>
                <a href="pro9.php"><h4>Quantum Wealth</h4></a>
                <div class="fixed-star-rating">
                    <label for="star1"></label>
                    <label for="star2"></label>
                    <label for="star3"></label>
                    <label for="star4"></label>
                    <label for="star5"></label>
                </div>
                <p>$12.00</p>
            </div>
            <div class="col-4">
                <a href="pro10.php"><img src="Images/pro-10.jpg"></a>
                <a href="pro10.php"><h4>Dianetics</h4></a>
                <div class="fixed-star-rating">
                    <label for="star1"></label>
                    <label for="star2"></label>
                    <label for="star3"></label>
                    <label for="star4"></label>
                    <label for="star5"></label>
                </div>
                <p>$14.00</p>
            </div>
            <div class="col-4">
                <a href="pro3.php"><img src="Images/pro-03.jpg"></a>
                <a href="pro3.php"><h4>Teaching With AI</h4></a>
                <div class="fixed-star-rating">
                    <label for="star1"></label>
                    <label for="star2"></label>
                    <label for="star3"></label>
                    <label for="star4"></label>
                    <label for="star5"></label>
                </div>
                <p>$24.00</p>
            </div>
            <div class="col-4">
                <a href="pro4.php"><img src="Images/pro-04.jpg"></a>
                <a href="pro4.php"><h4>Age of Revolutions</h4></a>
                <div class="fixed-star-rating">
                    <label for="star1"></label>
                    <label for="star2"></label>
                    <label for="star3"></label>
                    <label for="star4"></label>
                    <label for="star5"></label>
                </div>
                <p>$32.00</p>
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
</body>
</html>
