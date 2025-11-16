<?php 
include '../../config/config.php';
include '../../includes/header.php';

$cashier_id = $_GET['id'];
$query = "SELECT * FROM tb_cashiers WHERE id = '$cashier_id'";
$result = mysqli_query($conn, $query);
$cashier = mysqli_fetch_assoc($result);

if (!$cashier) {
    die("Data kasir tidak ditemukan.");
}
?>
<div class="container">
    <h1>Edit Data Kasir</h1>
    <form action="../../process/cashiers_process.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($cashier['id']); ?>">
        <div class="form-group">
            <label for="cashier_name">Nama Kasir:</label>
            <input type="text" id="cashier_name" name="cashier_name" class="form-control" value="<?php echo htmlspecialchars($cashier['cashier_name']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="list.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>