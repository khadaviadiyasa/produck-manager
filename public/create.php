
<?php

require_once "../config/db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stok = filter_input(INPUT_POST, "stok", FILTER_VALIDATE_INT);

    // Validasi input
    if (strlen($name) < 3) {
        $errors[] = "Nama produk minimal 3 karakter.";
    }

    if ($price === false || $price === null || $price <= 0) {
        $errors[] = "Harga harus lebih dari 0.";
    }

    if ($stok === false || $stok === null || $stok < 0) {
        $errors[] = "Stok harus lebih dari atau sama dengan 0.";
    }

    if ($category === "") {
        $category = "umum";
    }

    // Cek nama produk
    if (!$errors) {

        $stmt = $pdo->prepare(
            "SELECT id 
             FROM products 
             WHERE name = :name 
             LIMIT 1"
        );

        $stmt->execute([
            "name" => $name
        ]);

        if ($stmt->fetch()) {
            $errors[] = "Nama produk sudah digunakan.";
        }
    }

    // Simpan produk
    if (!$errors) {

        $stmt = $pdo->prepare(
            "INSERT INTO products (name, category, price, stock)
             VALUES (:name, :category, :price, :stock)"
        );

        $stmt->execute([
            "name" => $name,
            "category" => $category,
            "price" => $price,
            "stock" => $stok
        ]);

        header("Location: index.php?status=created");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<div class="container">

    <div class="topbar">

        <div>
            <h1>Tambah Produk</h1>
            <p class="subtitle">
                Masukkan informasi produk baru.
            </p>
        </div>

        <a class="btn btn-secondary" href="index.php">
            Kembali
        </a>

    </div>


    <?php if ($errors): ?>

        <div class="alert alert-error">

            <?php foreach ($errors as $error): ?>

                <div>
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <form class="form-card" method="POST" action="create.php">

        <label for="name">
            Nama Produk
        </label>

        <input
            id="name"
            name="name"
            value="<?= htmlspecialchars($name ?? "", ENT_QUOTES, "UTF-8") ?>"
            required
            minlength="3"
        >


        <label for="category">
            Kategori
        </label>

        <input
            id="category"
            name="category"
            value="<?= htmlspecialchars($category ?? "", ENT_QUOTES, "UTF-8") ?>"
        >


        <label for="price">
            Harga
        </label>

        <input
            id="price"
            type="number"
            name="price"
            step="0.01"
            min="0.01"
            value="<?= htmlspecialchars($_POST["price"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            required
        >


        <label for="stok">
            Stok
        </label>

        <input
            id="stok"
            type="number"
            name="stok"
            min="0"
            value="<?= htmlspecialchars($_POST["stok"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            required
        >


        <button class="btn btn-primary" type="submit">
            Simpan Produk
        </button>

    </form>

</div>

</body>
</html>

