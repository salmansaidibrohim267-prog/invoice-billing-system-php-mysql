<?php
require_once 'auth.php';
require_once 'config.php';

// Hanya admin yang boleh mengakses
if (!isAdmin()) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $qty  = (int) $_POST['quantity'];
    $price = (float) $_POST['price'];

    $errors = [];
    if (empty($name)) $errors[] = "Nama produk wajib diisi.";
    if ($qty < 0) $errors[] = "Stok tidak boleh negatif.";
    if ($price < 0) $errors[] = "Harga tidak boleh negatif.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssid', $name, $desc, $qty, $price);
        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit;
        } else {
            $errors[] = "Gagal menyimpan data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Inventory Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> body { padding-top: 70px; } </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">📦 Inventory Premium</a>
        <div class="navbar-nav ms-auto">
            <a href="dashboard.php" class="nav-link">🏠 Dashboard</a>
            <a href="index.php" class="nav-link">📋 Produk</a>
            <span class="navbar-text me-3">👤 <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Tambah Produk Baru</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err) echo "<div>$err</div>"; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="quantity" class="form-control" value="0" min="0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="price" class="form-control" value="0" step="0.01" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>