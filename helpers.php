<?php
function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function money($amount) {
    return '₱' . number_format((float)$amount, 2);
}

function category_name($category) {
    $names = [
        'mens_fashion' => "Men's Fashion",
        'womens_fashion' => "Women's Fashion",
        'electronics' => 'Electronics'
    ];
    return $names[$category] ?? 'All Products';
}

function product_image($row, $fallback = 'https://via.placeholder.com/250x300') {
    if (!empty($row['upload_image'])) return $row['upload_image'];
    if (!empty($row['image_url'])) return $row['image_url'];
    if (!empty($row['image'])) return $row['image'];
    return $fallback;
}

function cart_key($name, $price) {
    return md5($name . $price);
}
?>
