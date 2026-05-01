<?php
require_once 'auth.php'; // proteksi halaman
require_once 'config.php';

// Ambil statistik
$totalProducts = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$totalQuantity = $conn->query("SELECT SUM(quantity) AS total FROM products")->fetch_assoc()['total'];
$inventoryValue = $conn->query("SELECT SUM(quantity * price) AS total FROM products")->fetch_assoc()['total'];
$lowStockCount = $conn->query("SELECT COUNT(*) AS count FROM products WHERE quantity <= 5")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Inventory Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">📦 Inventory Premium</a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">👤 <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard</h3>
    <div class="row mt-3">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title"><?= $totalProducts ?></h5>
                    <p class="card-text">Total Produk</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><?= number_format($totalQuantity) ?></h5>
                    <p class="card-text">Total Stok</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($inventoryValue, 0, ',', '.') ?></h5>
                    <p class="card-text">Nilai Inventaris</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title"><?= $lowStockCount ?></h5>
                    <p class="card-text">Produk Hampir Habis (≤5)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu navigasi ke produk -->
    <div class="mt-4">
        <h5>Menu Cepat</h5>
        <a href="index.php" class="btn btn-outline-primary me-2">📋 Daftar Produk</a>
        <a href="add_product.php" class="btn btn-outline-success me-2">➕ Tambah Produk</a>
        <?php if (isAdmin()): ?>
            <span class="badge bg-secondary">Admin: bisa hapus/edit user</span>
        <?php endif; ?>
    </div>
</div>
</body>
</html>