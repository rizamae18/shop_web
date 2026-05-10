<?php
require_once __DIR__ . '/helpers.php';

function sample_products() {
    return [
        ['name'=>'Classic Navy Blazer', 'price'=>129.99, 'category'=>'mens_fashion', 'image'=>'https://via.placeholder.com/250x300', 'badge'=>'BEST SELLER', 'rating'=>'★★★★☆', 'reviews'=>312],
        ['name'=>'Men Casual Shirt', 'price'=>39.99, 'category'=>'mens_fashion', 'image'=>'https://via.placeholder.com/250x300', 'badge'=>'NEW', 'rating'=>'★★★★☆', 'reviews'=>154],
        ['name'=>'Elegant Summer Dress', 'price'=>59.99, 'category'=>'womens_fashion', 'image'=>'https://via.placeholder.com/250x300', 'badge'=>'TRENDING', 'rating'=>'★★★★★', 'reviews'=>221],
        ['name'=>'Women Leather Handbag', 'price'=>79.99, 'category'=>'womens_fashion', 'image'=>'https://via.placeholder.com/250x300', 'badge'=>'HOT', 'rating'=>'★★★★☆', 'reviews'=>188],
        ['name'=>'Pro Wireless Headphones', 'price'=>199.99, 'category'=>'electronics', 'image'=>'https://via.placeholder.com/250x250', 'badge'=>'TOP PICK', 'rating'=>'★★★★★', 'reviews'=>621],
        ['name'=>'Smart Watch Series 5', 'price'=>149.99, 'category'=>'electronics', 'image'=>'https://via.placeholder.com/250x250', 'badge'=>'SALE', 'rating'=>'★★★★☆', 'reviews'=>309],
        ['name'=>'Bluetooth Speaker', 'price'=>89.99, 'category'=>'electronics', 'image'=>'https://via.placeholder.com/250x250', 'badge'=>'NEW', 'rating'=>'★★★★☆', 'reviews'=>97],
        ['name'=>'Portable Power Bank', 'price'=>49.99, 'category'=>'electronics', 'image'=>'https://via.placeholder.com/250x250', 'badge'=>'HOT', 'rating'=>'★★★★☆', 'reviews'=>143]
    ];
}

function db_products($conn, $category = '') {
    $products = [];
    if (!$conn) return $products;

    $where = "WHERE is_active=1";
    if ($category === 'clothing') {
        $where .= " AND category IN ('mens_fashion','womens_fashion')";
    } elseif ($category !== '' && $category !== 'all') {
        $category = mysqli_real_escape_string($conn, $category);
        $where .= " AND category='$category'";
    }

    $result = mysqli_query($conn, "SELECT * FROM products $where ORDER BY created_at DESC");
    if (!$result) return $products;

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'category' => $row['category'],
            'image' => product_image($row),
            'badge' => $row['badge'] ?: 'NEW',
            'rating' => '★★★★☆',
            'reviews' => 0
        ];
    }
    return $products;
}

function all_products($conn) {
    return array_merge(sample_products(), db_products($conn));
}

function filter_products($products, $category) {
    if ($category === 'all' || $category === 'all_clothing') return $products;
    return array_values(array_filter($products, function($p) use ($category) { return $p['category'] === $category; }));
}

function product_card($product, $redirect, $buttonClass = 'buy-now', $addClass = 'add-cart') {
    $label = strtoupper(category_name($product['category']));
    ?>
    <div class="product-card">
        <div class="product-img">
            <span class="badge"><?= e($product['badge']) ?></span>
            <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
        </div>
        <div class="product-info">
            <p class="category"><?= e($label) ?></p>
            <h3><?= e($product['name']) ?></h3>
            <div class="rating"><?= e($product['rating']) ?> <span>(<?= e($product['reviews']) ?>)</span></div>
            <p class="price"><?= money($product['price']) ?></p>
            <div class="card-btns">
                <form action="BuyNow.php" method="POST" class="buy-now-form">
                    <input type="hidden" name="name" value="<?= e($product['name']) ?>">
                    <input type="hidden" name="price" value="<?= e($product['price']) ?>">
                    <input type="hidden" name="image" value="<?= e($product['image']) ?>">
                    <input type="hidden" name="category" value="<?= e($label) ?>">
                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                    <button type="submit" class="<?= e($buttonClass) ?>">Buy Now</button>
                </form>

                <form action="add_to_cart.php" method="POST" class="cart-form">
                    <input type="hidden" name="name" value="<?= e($product['name']) ?>">
                    <input type="hidden" name="price" value="<?= e($product['price']) ?>">
                    <input type="hidden" name="image" value="<?= e($product['image']) ?>">
                    <input type="hidden" name="category" value="<?= e($label) ?>">
                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                    <button type="submit" class="<?= e($addClass) ?>"><i class="fa-solid fa-cart-plus"></i> Add</button>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>
