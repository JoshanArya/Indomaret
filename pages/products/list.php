<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/../config/config.php";
include ROOTPATH . "/../includes/header.php";

// Query dengan LEFT JOIN untuk mengambil data produk dan diskon voucher
$query = "
    SELECT 
        p.id,
        p.product_name,
        p.price,
        p.stock,
        v.id AS voucher_id,
        v.discount,
        v.max_discount
    FROM tb_products p
    LEFT JOIN tb_vouchers v ON p.voucher_id = v.id
";
$result = mysqli_query($conn, $query);

?>

<div class="container">
    <h1>Daftar Produk</h1>
    <div class="actions">
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga </th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        
                        <td>
                            <?php
                            $harga_normal = $row['price'];
                            $harga_diskon = $harga_normal;
                            $diskon_persen = $row['discount'];
                            $diskon_maksimal = $row['max_discount'];

                            if (!is_null($diskon_persen) && $diskon_persen > 0) {
                                $potongan = $harga_normal * $diskon_persen;
                                if (!is_null($diskon_maksimal) && $potongan > $diskon_maksimal) {
                                    $harga_diskon = $harga_normal - $diskon_maksimal;
                                } else {
                                    $harga_diskon = $harga_normal - $potongan;
                                }
                                echo "<del>Rp" . number_format($harga_normal, 0, ',', '.') . "</del><br>";
                                echo "Rp" . number_format($harga_diskon, 0, ',', '.');
                            } else {
                                echo "Rp" . number_format($harga_normal, 0, ',', '.');
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['stock']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                <?php
                                $query_cek = "SELECT 1 FROM tb_transaction_details WHERE product_id = " . intval($row['id']) . " LIMIT 1";
                                $cek_result = mysqli_query($conn, $query_cek);
                                if (mysqli_num_rows($cek_result)) {
                                    echo '<button class="btn btn-danger" disabled><i class="fas fa-trash"></i> Hapus</button>';
                                } else {
                                ?>
                                    <form action="../../process/products_process.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                                <?php
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="empty-state">Belum ada data produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>