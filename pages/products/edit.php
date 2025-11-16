<?php
include '../../config/config.php';
include '../../includes/header.php';

$product_id = $_GET['id'];
$query = "SELECT * FROM tb_products WHERE id = '$product_id'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);
?>

<div class="container">
    <h1>Tambah Produk</h1>
    <form action="../../process/products_process.php" method="POST">
        <input type="hidden" name="action" value="edit" />
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        
        <div class="form-group">
            <label for="product_name">Nama Produk:</label>
            <input type="text" id="product_name" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name'])?>" required />
        </div>
        
        <div class="form-group">
            <label for="price">Harga:</label>
            <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price'])?>" required />
        </div>

        <div class="form-group">
            <label for="stock">Stok:</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock'])?>" required />
        </div>

        <div class="form-group">
            <label for="voucher_id">Voucher:</label>
            <select id="voucher_id" name="voucher_id" class="form-control" >
                <?php
                if (empty($product['voucher_id'])) {
                    echo '<option value="">-- Pilih Voucher (Opsional) --</option>';
                }
                $query = "SELECT * FROM tb_vouchers";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $discount_percent = $row['discount'] * 100;
                    $selected = ($product['voucher_id'] == $row['id']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['id']) . "' $selected>" . htmlspecialchars($row['voucher_name']) . " (" . htmlspecialchars($discount_percent) . "%)</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="list.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>

