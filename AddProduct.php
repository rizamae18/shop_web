<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$shop_result = mysqli_query($conn, "SELECT * FROM shops WHERE user_id='$user_id' LIMIT 1");
$shop = mysqli_fetch_assoc($shop_result);

if (!$shop) {
    $shop_id = uniqid();
    mysqli_query($conn, "INSERT INTO shops (id, user_id, name) VALUES ('$shop_id', '$user_id', 'My Shop')");
} else {
    $shop_id = $shop['id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $badge = mysqli_real_escape_string($conn, $_POST['badge']);

    $product_id = uniqid();
    $main_image = "";

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
        for ($i = 0; $i < count($_FILES['product_images']['name']); $i++) {
            if ($_FILES['product_images']['error'][$i] == 0) {
                $original_name = basename($_FILES['product_images']['name'][$i]);
                $file_name = time() . "_" . $i . "_" . preg_replace('/[^A-Za-z0-9._-]/', '', $original_name);
                $target = "uploads/" . $file_name;

                if (move_uploaded_file($_FILES['product_images']['tmp_name'][$i], $target)) {
                    if ($main_image == "") {
                        $main_image = $target;
                    }

                    $image_id = uniqid();
                    $safe_target = mysqli_real_escape_string($conn, $target);
                    mysqli_query($conn, "INSERT INTO product_images (id, product_id, image_url, sort_order) VALUES ('$image_id', '$product_id', '$safe_target', '$i')");
                }
            }
        }
    }

    $safe_main_image = mysqli_real_escape_string($conn, $main_image);

    $sql = "INSERT INTO products 
            (id, shop_id, name, description, price, stock, category, image_url, badge)
            VALUES 
            ('$product_id', '$shop_id', '$name', '$description', '$price', '$stock', '$category', '$safe_main_image', '$badge')";

    if (mysqli_query($conn, $sql)) {
        header('Location: MyAccount.php?tab=shop');
        exit();
    } else {
        $error = 'Product was not saved: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .add-product-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
            padding: 30px;
        }

        .add-product-form {
            width: 100%;
            max-width: 650px;
            display: grid;
            gap: 12px;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        .upload-box {
            width: 100%;
            min-height: 180px;
            border: 2px dashed #c7a17a;
            border-radius: 16px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #faf7f2;
            color: #8b5e34;
            font-weight: bold;
            text-align: center;
            padding: 20px;
        }

        .upload-icon {
            font-size: 45px;
            margin-bottom: 8px;
        }

        .hidden-input {
            display: none;
        }

        .preview-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .preview-list img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #eee;
            background: #f5f5f5;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">Lumine</div>
        <ul class="nav-links">
            <li><a href="Home.php">Home</a></li>
            <li><a href="Shop.php">Shop</a></li>
            <li><a href="MyAccount.php">My Account</a></li>
        </ul>
        <div class="nav-icons">
            <a href="Cart.php" class="cart-icon" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
</header>

<main class="add-product-page">
    <form method="POST" enctype="multipart/form-data" class="add-product-form">
        <h2>Add Product</h2>

        <?php if(isset($error)){ echo "<p style='color:red;'>$error</p>"; } ?>

        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Price</label>
        <input type="number" step="0.01" name="price" required>

        <label>Stock</label>
        <input type="number" name="stock" required>

        <label>Category</label>
        <select name="category" required>
            <option value="mens_fashion">Men's Fashion</option>
            <option value="womens_fashion">Women's Fashion</option>
            <option value="electronics">Electronics</option>
        </select>

        <label>Upload Image / Images</label>
        <label class="upload-box">
            <div class="upload-icon">📷</div>
            <p>Click to upload image</p>
            <small>You can select more than one image.</small>
            <input type="file" name="product_images[]" id="productImages" class="hidden-input" accept="image/*" multiple>
        </label>

        <div id="previewList" class="preview-list"></div>

        <label>Badge</label>
        <input type="text" name="badge" placeholder="BEST SELLER">

        <button type="submit" class="btn-primary">Save Product</button>
    </form>
</main>

<script>
document.getElementById('productImages').addEventListener('change', function () {
    const previewList = document.getElementById('previewList');
    previewList.innerHTML = '';

    for (const file of this.files) {
        const image = document.createElement('img');
        image.src = URL.createObjectURL(file);
        previewList.appendChild(image);
    }
});
</script>

</body>
</html>
