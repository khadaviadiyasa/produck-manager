<?php
session_start();
require_once "../config/db.php";
$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

$stmt = $pdo->query("SELECT id, name, category, price, stock, created_at FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
$status = $_GET["status"] ?? "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Manager</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
<div class="topbar">
<div><h1>Product Manager</h1><p class="subtitle">Manajemen data produk PHP & MySQL</p></div>
<a class="btn btn-primary" href="create.php">+ Tambah Produk</a>
</div>
<?php if ($status === "created"): ?><div class="alert alert-success">Produk berhasil ditambahkan.</div><?php endif; ?>
<?php if ($status === "updated"): ?><div class="alert alert-success">Produk berhasil diperbarui.</div><?php endif; ?>
<?php if ($status === "deleted"): ?><div class="alert alert-success">Produk berhasil dihapus.</div><?php endif; ?>

<div class="products">
<?php foreach ($products as $product): ?>
<article class="card">
<div class="card-header"><span class="product-id">#<?= (int)$product["id"] ?></span><span class="badge"><?= htmlspecialchars($product["category"], ENT_QUOTES, "UTF-8") ?></span></div>
<h2><?= htmlspecialchars($product["name"], ENT_QUOTES, "UTF-8") ?></h2>
<div class="price">Rp <?= number_format($product["price"], 0, ",", ".") ?></div>
<div class="stock <?= $product["stock"] < 3 ? "critical" : "" ?>">Stok: <?= (int)$product["stock"] ?><?php if ($product["stock"] < 3): ?> <strong>· Stok Kritis</strong><?php endif; ?></div>
<div class="actions">
<a class="btn btn-secondary" href="edit.php?id=<?= (int)$product["id"] ?>">Edit</a>
<form method="POST" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
<input type="hidden" name="id" value="<?= (int)$product["id"] ?>">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>">
<button class="btn btn-danger" type="submit">Hapus</button>
</form>
</div>
</article>
<?php endforeach; ?>
</div>
<?php if (!$products): ?><div class="empty">Belum ada produk.</div><?php endif; ?>
</div>
</body>
</html>
