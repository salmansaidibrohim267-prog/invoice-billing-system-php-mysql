<?php
require_once 'auth.php';
require_once 'config.php';

// Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = '';
$params = [];
$types = '';

if (!empty($search)) {
    $search_sql = "WHERE name LIKE ? OR description LIKE ?";
    $search_param = "%$search%";
    $params = [$search_param, $search_param];
    $types = 'ss';
}

$sql = "SELECT * FROM products $search_sql ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk - Inventory Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 70px; }
        .table-actions a { margin-right: 5px; }
    </style>
</head>
<body>
<!-- Navbar Premium -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">📦 Inventory Premium</a>
        <div class="navbar-nav ms-auto">
            <a href="dashboard.php" class="nav-link">🏠 Dashboard</a>
            <a href="index.php" class="nav-link active">📋 Produk</a>
            <?php if (isAdmin()): ?>
                <a href="add_product.php" class="nav-link">➕ Tambah</a>
                <a href="export.php" class="nav-link">📥 Ekspor CSV</a>
            <?php endif; ?>
            <span class="navbar-text me-3">👤 <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Produk</h2>
        <form class="d-flex" method="get">
            <input class="form-control me-2" type="search" name="search" placeholder="Cari nama/deskripsi" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-success" type="submit">Cari</button>
        </form>
    </div>

    <?php if (count($products) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Dibuat</th>
                    <?php if (isAdmin()): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= $product['quantity'] ?></td>
                    <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($product['created_at'])) ?></td>
                    <?php if (isAdmin()): ?>
                    <td class="table-actions">
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info">Tidak ada produk ditemukan.</div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>