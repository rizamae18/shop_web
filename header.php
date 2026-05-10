<?php require_once __DIR__ . '/helpers.php'; ?>
<header>
    <nav class="navbar">
        <div class="logo">Lumine</div>
        <ul class="nav-links">
            <li><a href="Home.php" class="<?= ($active_page ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
            <li><a href="Shop.php" class="<?= ($active_page ?? '') === 'shop' ? 'active' : '' ?>">Shop</a></li>
            <li><a href="Clothing.php" class="<?= ($active_page ?? '') === 'clothing' ? 'active' : '' ?>">Clothing</a></li>
            <li><a href="Electronics.php" class="<?= ($active_page ?? '') === 'electronics' ? 'active' : '' ?>">Electronics</a></li>
            <li><a href="MyAccount.php" class="<?= ($active_page ?? '') === 'account' ? 'active' : '' ?>">My Account</a></li>
        </ul>
        <div class="nav-icons">
            <a href="Cart.php" class="cart-icon <?= ($active_page ?? '') === 'cart' ? 'active' : '' ?>" title="View Cart"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
</header>
