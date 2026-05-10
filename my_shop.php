<?php
$user_id = $_SESSION['user_id'];
$shop_result = mysqli_query($conn, "SELECT * FROM shops WHERE user_id='$user_id' LIMIT 1");
$shop = mysqli_fetch_assoc($shop_result);
?>

<div class="my-shop-header">
    <div>
        <h2>My Shop</h2>
        <p>Swipe sideways to view your products like flash cards.</p>
    </div>
    <a href="AddProduct.php" class="shop-add-btn">+ Add Product</a>
</div>

<?php
if ($shop) {
    $shop_id = $shop['id'];
    $products = mysqli_query($conn, "SELECT * FROM products WHERE shop_id='$shop_id' ORDER BY created_at DESC");

    if (mysqli_num_rows($products) > 0) {
        echo '<section class="flash-card-slider">';

        while ($p = mysqli_fetch_assoc($products)) {
            $product_id = $p['id'];
            $image_query = mysqli_query($conn, "SELECT * FROM product_images WHERE product_id='$product_id' ORDER BY sort_order ASC");

            $images = [];
            while ($img_row = mysqli_fetch_assoc($image_query)) {
                $images[] = $img_row['image_url'];
            }

            if (count($images) == 0 && !empty($p['image_url'])) {
                $images[] = $p['image_url'];
            }

            if (count($images) == 0) {
                $images[] = 'https://via.placeholder.com/350x350?text=No+Image';
            }

            echo '<article class="shop-flash-card">';

            echo '<div class="flash-image-slider">';
            foreach ($images as $image) {
                echo '<img src="'.htmlspecialchars($image).'" alt="'.htmlspecialchars($p['name']).'">';
            }
            echo '</div>';

            echo '<div class="flash-card-details">';
            if (!empty($p['badge'])) {
                echo '<span class="flash-badge">'.htmlspecialchars($p['badge']).'</span>';
            }
            echo '<p class="flash-category">'.htmlspecialchars(str_replace("_", " ", $p['category'])).'</p>';
            echo '<h3>'.htmlspecialchars($p['name']).'</h3>';
            echo '<p class="flash-description">'.htmlspecialchars($p['description']).'</p>';
            echo '<p class="flash-price">₱'.number_format($p['price'], 2).'</p>';
            echo '<p class="flash-stock">Stock: '.htmlspecialchars($p['stock']).'</p>';
            echo '<small>Added: '.htmlspecialchars($p['created_at']).'</small>';
            echo '</div>';

            echo '</article>';
        }

        echo '</section>';
    } else {
        echo '<div class="empty-shop-box">';
        echo '<p>No products yet.</p>';
        echo '<a href="AddProduct.php" class="shop-add-btn">Add your first product</a>';
        echo '</div>';
    }
} else {
    echo '<p>No shop found.</p>';
}
?>
