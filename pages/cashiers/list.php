<?php 
include '../../config/config.php';
include '../../includes/header.php';

$query = "SELECT * FROM tb_cashiers";
$result = mysqli_query($conn, $query);

?>
<div class="container">
    <h1>Daftar Kasir</h1>
    <div class="actions">
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kasir</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kasir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['cashier_name']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                <?php
                                $query_cek = "SELECT 1 FROM tb_transactions WHERE cashier_id = " . intval($row['id']) . " LIMIT 1";
                                $cek_result = mysqli_query($conn, $query_cek);
                                if (mysqli_num_rows($cek_result)) {
                                    echo '<button class="btn btn-danger" disabled><i class="fas fa-trash"></i> Hapus</button>';
                                } else {
                                ?>
                                <form action="/../../process/cashiers_process.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                    <td colspan="3" class="empty-state">Belum ada data kasir.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
