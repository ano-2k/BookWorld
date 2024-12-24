<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="Images/icon.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

    
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

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 500px;
    margin: 0 auto;
}

input, textarea {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    
}

button {
    padding: 15px;
    background-color: #ff523b;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #e64a19;
}

.error {
    color: red;
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

    <div class="container2">
        <h2>Account Details</h2>
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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $description = $_POST['description'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($name && $email) {
                $sqlDetails = "REPLACE INTO user_details (user_id, name, email, description, password) VALUES (?, ?, ?, ?, ?)";
                $stmtDetails = $conn->prepare($sqlDetails);
                if ($stmtDetails === false) {
                    error_log('Error preparing statement: ' . $conn->error);
                }

                $hashed_password = null;
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                }

                $stmtDetails->bind_param('issss', $user_id, $name, $email, $description, $hashed_password);
                $stmtDetails->execute();

                $stmtDetails->close();
                $conn->close();
                header("Location: profile.php");
                exit();
            } else {
                echo "<div class='error'>Name and Email are required fields.</div>";
            }
        } else {
            $sqlUser = "SELECT * FROM tbluser WHERE id = $user_id";
            $resultUser = $conn->query($sqlUser);

            if ($resultUser->num_rows > 0) {
                $user = $resultUser->fetch_assoc();
            } else {
                echo "User not found.";
                exit();
            }

            $sqlDetails = "SELECT * FROM user_details WHERE user_id = $user_id";
            $resultDetails = $conn->query($sqlDetails);
            $details = $resultDetails->fetch_assoc();

            $description = isset($details['description']) ? $details['description'] : '';
        }
        ?>

        <form method="POST" action="">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Name" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
            <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($description); ?></textarea>
            <input type="password" name="password" placeholder="Enter your password">
            <button type="submit">Save</button>
        </form>
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
