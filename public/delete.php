
<?php

session_start();
require_once "../config/db.php";

// Pastikan request menggunakan POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Ambil dan validasi ID produk
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Validasi token CSRF
if (!hash_equals(
    $_SESSION["csrf"] ?? "",
    $_POST["csrf"] ?? ""
)) {
    http_response_code(403);
    exit("Token CSRF tidak valid.");
}

// Hapus produk berdasarkan ID
$stmt = $pdo->prepare(
    "DELETE FROM products WHERE id = :id"
);

$stmt->execute([
    "id" => $id
]);

// Kembali ke halaman utama
header("Location: index.php?status=deleted");
exit;
