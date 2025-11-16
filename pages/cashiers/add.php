<?php 
include '../../config/config.php';
include '../../includes/header.php';
?>
<div class="container">
    <h1>Tambah Kasir Baru</h1>
    <form action="../../process/cashiers_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        <div class="form-group">
            
            <input type="hidden" id="id" name="id" class="form-control" required placeholder="Id akan otomatis terisi">
        </div>
        <div class="form-group">
            <label for="cashier_name">Nama Kasir:</label>
            <input type="text" id="cashier_name" name="cashier_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="list.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>