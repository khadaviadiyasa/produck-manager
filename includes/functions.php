<?php
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function redirect($url)
{
    header("Location: " . $url);
    exit;
}

function csrfToken()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION["csrf"] ??= bin2hex(random_bytes(32));
    return $_SESSION["csrf"];
}

function verifyCsrf($token)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    return hash_equals($_SESSION["csrf"] ?? "", (string)$token);
}

function validProductInput($name, $category, $price, $stock)
{
    $errors = [];

    if (strlen($name) < 3) {
        $errors[] = "Nama produk minimal 3 karakter.";
    }

    if ($price === false || $price === null || $price <= 0) {
        $errors[] = "Harga harus lebih dari 0.";
    }

    if ($stock === false || $stock === null || $stock < 0) {
        $errors[] = "Stok harus lebih dari atau sama dengan 0.";
    }

    return $errors;
}

function validProductId($id)
{
    return $id !== false && $id !== null && $id > 0;
}
