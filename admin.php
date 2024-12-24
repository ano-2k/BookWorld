<?php
session_start();
if (!isset($_SESSION['admin'])) {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = (float)$_POST['price'];
        $price = number_format($price, 2);
        $detail_page = $_POST['detail_page']; 

        $target_dir = "Images/";

        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 500000) {
            echo "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";

        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

                $image_path = $target_file;

                $sql = "INSERT INTO products (name, price, image_path, detail_page) VALUES ('$name', '$price', '$image_path', '$detail_page')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('New product added successfully');</script>";
                } else {
                    echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
    } elseif (isset($_POST['remove_product'])) {
        $detail_page = $_POST['detail_page'];

        $sql = "DELETE FROM products WHERE detail_page='$detail_page'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product removed successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
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
                <li>
                    <button onclick="window.location.href='logout.php'" class="button-with-image">
                        <img src="Images/logout.png" alt="Logout" class="logout-icon">Logout
                    </button>
                </li>
            </ul>
        </nav>
        <a href="cart.php"><img src="Images/cart.png" width="30px" height="30px"></a>
        <img src="Images/menu.jpg" class="menu-icon" onclick="menuToggle()">
    </div>
</div>

<div class="small-container">
    <h1>Admin Panel</h1>
    <br>
    <br>
    <h2>Add New Product</h2>
    <form method="POST" action="admin.php" enctype="multipart/form-data" class="admin-form">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="price">Product Price:</label>
        <input type="text" id="price" name="price" required>
        <br>
        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" required>
        <br>
        <label for="detail_page">Detail Page Filename:</label> 
        <input type="text" id="detail_page" name="detail_page" required>
        <br>
        <input type="submit" name="add_product" value="Add Product">
    </form>

    <h2>Remove Product</h2>
    <form method="POST" action="admin.php" class="admin-form">
        <label for="detail_page_remove">Product No:</label>
        <input type="text" id="detail_page_remove" name="detail_page" required>
        <br>
        <input type="submit" name="remove_product" value="Remove Product">
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
        h2{
            text-align:center;
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

        .admin-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .admin-form label {
            display: block;
            margin-bottom: 10px;
        }

        .admin-form input[type="text"],
        .admin-form input[type="file"],
        .admin-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .admin-form input[type="submit"] {
            background-color: #ff523b;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .admin-form input[type="submit"]:hover {
            background-color: #e2492f;
        }
        .button-with-image {
    display: flex;
    align-items: center;
    padding: 5px 10px;
    background-color: #ff523b; 
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.button-with-image img.logout-icon {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

    </style>
</body>
</html>
