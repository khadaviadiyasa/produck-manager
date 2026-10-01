
<?php

require_once "../config/db.php";

// Ambil ID produk dari URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data produk berdasarkan ID
$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit("Produk tidak ditemukan.");
}

$errors = [];


// Proses update ketika form dikirim
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


    // Cek nama produk yang sama
    if (!$errors) {

        $stmt = $pdo->prepare(
            "SELECT id
             FROM products
             WHERE name = :name
             AND id <> :id
             LIMIT 1"
        );

        $stmt->execute([
            "name" => $name,
            "id" => $id
        ]);

        if ($stmt->fetch()) {
            $errors[] = "Nama produk sudah digunakan.";
        }
    }


    // Update data produk
    if (!$errors) {

        $stmt = $pdo->prepare(
            "UPDATE products
             SET name = :name,
                 category = :category,
                 price = :price,
                 stock = :stock
             WHERE id = :id"
        );

        $stmt->execute([
            "name" => $name,
            "category" => $category,
            "price" => $price,
            "stock" => $stok,
            "id" => $id
        ]);

        header("Location: index.php?status=updated");
        exit;
    }


    // Menampilkan kembali input jika validasi gagal
    $product["name"] = $name;
    $product["category"] = $category;
    $product["price"] = $_POST["price"] ?? "";
    $product["stok"] = $_POST["stok"] ?? "";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<div class="container">

    <div class="topbar">

        <div>
            <h1>Edit Produk</h1>
            <p class="subtitle">
                Perbarui data produk.
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


    <form
        class="form-card"
        method="POST"
        action="edit.php?id=<?= (int)$id ?>"
    >

        <label for="name">
            Nama Produk
        </label>

        <input
            id="name"
            name="name"
            value="<?= htmlspecialchars($product["name"], ENT_QUOTES, "UTF-8") ?>"
            required
            minlength="3"
        >


        <label for="category">
            Kategori
        </label>

        <input
            id="category"
            name="category"
            value="<?= htmlspecialchars($product["category"], ENT_QUOTES, "UTF-8") ?>"
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
            value="<?= htmlspecialchars($product["price"], ENT_QUOTES, "UTF-8") ?>"
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
            value="<?= htmlspecialchars($product["stok"], ENT_QUOTES, "UTF-8") ?>"
            required
        >


        <button class="btn btn-primary" type="submit">
            Simpan Perubahan
        </button>

    </form>

</div>

</body>
</html>

