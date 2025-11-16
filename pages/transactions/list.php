<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/../config/config.php";
include ROOTPATH . "/../includes/header.php";
$result = mysqli_query($conn, "SELECT * FROM tb_transactions");
?>

<div class="container">
    <h2>List Transaksi</h2>
    <div class="actions" style="display:flex;justify-content:space-between;align-items:center;">
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Transaksi</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>ID Kasir</th>
                <th>Total</th>
                <th colspan="3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?= $no++?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= $row['code'] ?></td>
                <td><?= $row['cashier_id']?></td>
                <td><?= $row['total']?></td>
                <td>
                    <a href="transaction_details.php?id=<?= $row['id'] ?>" class="btn btn-success"><i class="fas fa-eye"></i> Detail</a>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                </td>
                <td>
                    <?php
                    $query_cek = mysqli_query($conn, "SELECT tb_transaction_details.product_id FROM tb_transactions JOIN tb_transaction_details ON tb_transactions.id = tb_transaction_details.product_id WHERE tb_transaction_details.product_id = $row[id]");
                    if(mysqli_num_rows($query_cek) > 0){
                            echo "<button class='btn btn-danger' disabled><i class='fas fa-trash'></i> Delete</button>";
                    }else{
                    ?>
                    <form action="/indomaret_RPL3/process/transactions_process.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>