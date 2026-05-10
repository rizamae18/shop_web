<h2 class="orders-title">My Orders</h2>

<?php
$user_id = $_SESSION['user_id'] ?? 0;
$orders = $_SESSION['orders'][$user_id] ?? [];
$orders = array_reverse($orders);
?>

<?php if (isset($_GET['ordered'])): ?>
    <div class="orders-notice">Order placed successfully.</div>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div class="orders-empty">
        <h3>No orders yet</h3>
        <p>Your ordered products will appear here.</p>
        <a href="Shop.php" class="orders-shop-btn">Shop Now</a>
    </div>
<?php else: ?>
    <div class="orders-wrapper">
        <?php foreach ($orders as $order): ?>
            <?php
            $items = $order['items'] ?? [];
            $total_qty = 0;
            foreach ($items as $item) {
                $total_qty += (int)($item['quantity'] ?? 1);
            }
            ?>

            <div class="orders-box">
                <div class="orders-head">
                    <div>
                        <p>Order No.</p>
                        <h3><?php echo htmlspecialchars($order['order_no'] ?? 'N/A'); ?></h3>
                    </div>
                    <span><?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?></span>
                </div>

                <div class="orders-summary">
                    <p><b>Date:</b> <?php echo htmlspecialchars($order['date'] ?? 'N/A'); ?></p>
                    <p><b>Items:</b> <?php echo $total_qty; ?></p>
                    <p><b>Total:</b> ₱<?php echo number_format((float)($order['total'] ?? 0), 2); ?></p>
                </div>

                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="orders-product">
                                        <img src="<?php echo htmlspecialchars($item['image'] ?? ''); ?>" alt="Product">
                                        <span><?php echo htmlspecialchars($item['name'] ?? 'Product'); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($item['category'] ?? 'Uncategorized'); ?></td>
                                <td><?php echo (int)($item['quantity'] ?? 1); ?></td>
                                <td>₱<?php echo number_format((float)($item['price'] ?? 0), 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
