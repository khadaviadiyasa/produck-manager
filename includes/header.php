<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<header class="site-header">
    <div class="nav-inner">
        <a class="brand" href="index.php">Product Manager</a>
        <nav>
            <a href="index.php">Dashboard</a>
            <a class="btn btn-primary" href="create.php">+ Tambah Produk</a>
        </nav>
    </div>
</header>
