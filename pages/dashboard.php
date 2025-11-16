<?php 
include '../config/config.php';
include '../includes/header.php';

// Ambil jumlah data dari setiap tabel untuk dashboard
$cashiers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_cashiers"))['count'];
$products_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_products"))['count'];
$transactions_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tb_transactions"))['count'];
?>

<div class="container">
    <h1>Selamat Datang di Sistem Kasir Indomaret</h1>
    <!-- <p>Ringkasan data operasional toko saat ini.</p> -->
    
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-icon"><i class="fas fa-user-tie"></i></div>
            <div class="card-title">Jumlah Kasir</div>
            <h2><?php echo $cashiers_count; ?></h2>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-box"></i></div>
            <div class="card-title">Jumlah Produk</div>
            <h2><?php echo $products_count; ?></h2>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-receipt"></i></div>
            <div class="card-title">Jumlah Transaksi</div>
            <h2><?php echo $transactions_count; ?></h2>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>