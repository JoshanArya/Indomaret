<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Indomaret</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="/pages/dashboard.php">
                <i class="fas fa-store"></i>
                <span>Indomaret Kasir</span>
            </a>
        </div>
        <nav class="nav">
            <a href="/pages/dashboard.php"><i class="fas fa-home"></i> <span class="nav-text">Beranda</span></a>
            <a href="/pages/cashiers/list.php"><i class="fas fa-user-tie"></i> <span class="nav-text">Kasir</span></a>
            <a href="/pages/products/list.php"><i class="fas fa-box"></i> <span class="nav-text">Produk</span></a>
            <a href="/pages/transactions/list.php"><i class="fas fa-receipt"></i> <span class="nav-text">Transaksi</span></a>
        </nav>
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </header>
    <main class="main-content">