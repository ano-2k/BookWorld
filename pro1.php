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
if (isset($_POST['add_to_cart'])) {
    $product = [
        "name" => "Elon Musk Audible Audiobook",
        "price" => 27.00,
        "quantity" => (int)$_POST['quantity'],
        "image" => "Images/pro-01.jpg" // Add image URL
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

    // Redirect to cart page
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elon Musk Audible Audiobook</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="Images/icon.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
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
                <img src="Images/pro-01.jpg" width="75%">
            </div>
            <div class="col-2">
                <h2>Elon Musk Audible Audiobook</h2>
                <h4>Walter Isaacson (Author, Narrator), Jeremy Bobb (Narrator)</h4>
                <p>#1 New York Times bestseller
                   <br> <br> From the author of Steve Jobs and other bestselling biographies, this is the astonishingly intimate story of the most fascinating and controversial innovator of our era—a rule-breaking visionary who helped to lead the world into the era of electric vehicles, private space exploration, and artificial intelligence. Oh, and took over Twitter.</p>
                <h3>$27.00</h3>
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
    <!------Products-------->


<div class="small-container">


<div class="row">
    <div class="col-4">
        <a href="pro9.php"> <img src="Images/pro-09.jpg" ></a>
        <a href="pro9.php"> <h4> Quantum Wealth</h4></a>
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
        <a href="pro10.php">  <img src="Images/pro-10.jpg" ></a>
        <a href="pro10.php"> <h4> Dianetics</h4></a>
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
        <a href="pro11.php"> <img src="Images/pro-11.jpg" ></a>
        <a href="pro11.php">  <h4>Onyx Storm</h4></a>
        <div class="fixed-star-rating">
            <label for="star1"></label>
            <label for="star2"></label>
            <label for="star3"></label>
            <label for="star4"></label>
            <label for="star5"></label>
          </div>
        <p>$19.00</p>
    </div>

    <div class="col-4">
        <a href="pro12.php">   <img src="Images/pro-12.jpg" ></a>
        <a href="pro12.php">  <h4>The Power of Being Rich</h4></a>
        <div class="fixed-star-rating">
            <label for="star1"></label>
            <label for="star2"></label>
            <label for="star3"></label>
            <label for="star4"></label>
            <label for="star5"></label>
          </div>
        <p>$13.00</p>
    </div>

</div>

</div>

    <!-- Footer code here -->
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
