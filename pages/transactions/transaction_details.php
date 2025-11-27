<?php
include '../../config/config.php';
include '../../includes/header.php';

// Get transaction ID from URL
$transaction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($transaction_id === 0) {
    die("<div class='container'><div class='alert alert-danger'>Transaction ID tidak valid</div></div>");
}

// Get transaction data
$transaction_query = "SELECT t.*, c.cashier_name
                     FROM tb_transactions t 
                     LEFT JOIN tb_cashiers c ON t.cashier_id = c.id 
                     WHERE t.id = $transaction_id";
$transaction_result = mysqli_query($conn, $transaction_query);
$transaction = mysqli_fetch_assoc($transaction_result);

if (!$transaction) {
    die("<div class='container'><div class='alert alert-danger'>Transaksi tidak ditemukan</div></div>");
}

// Get transaction details
$detail_query = "SELECT td.*, p.product_name, p.price as normal_price 
                FROM tb_transaction_details td 
                LEFT JOIN tb_products p ON td.product_id = p.id 
                WHERE td.transaction_id = $transaction_id";
$detail_result = mysqli_query($conn, $detail_query);
$details = [];
while ($detail = mysqli_fetch_assoc($detail_result)) {
    $details[] = $detail;
}

// Calculate if we need to show payment info
$show_payment_info = $transaction['status'] === 'paid';
$payment_amount = $transaction['pay'];
$payment_change = $transaction['spare_change'];
$status = $show_payment_info ? 'Paid' : 'Pending';

// Format transaction date
$transaction_date = date('Y-m-d H:i:s', strtotime($transaction['created_at']));
$transaction_code = $transaction['code'];
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-receipt"></i> Detail Transaksi</h1>
        <p>Rincian lengkap transaksi penjualan</p>
    </div>

    <!-- Transaction Info Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header" style="background: var(--accent-color); padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color);">
            <h3 style="margin: 0; display: flex; justify-content: between; align-items: center;">
                <span>Transaction Details</span>
                <span class="status-badge <?php echo $status === 'Paid' ? 'status-active' : 'status-pending'; ?>" style="font-size: 0.9rem;">
                    <?php echo $status === 'Paid' ? '✅ Lunas' : '⏳ Pending'; ?>
                </span>
            </h3>
        </div>
        <div style="padding: 1.5rem;">
            <?php if ($status === 'Paid'): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle"></i> This transaction has been paid.
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                <strong>Tanggal:</strong>
                <span><?php echo $transaction_date; ?></span>
                
                <strong>Kode Transaksi:</strong>
                <span><?php echo htmlspecialchars($transaction_code); ?></span>
                
                <strong>Kasir:</strong>
                <span><?php echo htmlspecialchars($transaction['cashier_name'] ?? '-'); ?></span>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($details) > 0): ?>
                    <?php 
                    $total_amount = 0;
                    foreach ($details as $detail): 
                        $subtotal = $detail['sub_total'];
                        $total_amount += $subtotal;
                    ?>
                    <tr>
                        <td class="product-name"><?php echo htmlspecialchars($detail['product_name']); ?></td>
                        <td><?php echo $detail['quantity']; ?></td>
                        <td class="price">
                            <?php
                            $harga_normal = $detail['normal_price'];
                            $harga_diskon = $harga_normal;
                            $diskon_persen = null;
                            $diskon_maksimal = null;

                            $voucher_query = "SELECT v.discount, v.max_discount FROM tb_products p LEFT JOIN tb_vouchers v ON p.voucher_id = v.id WHERE p.id = " . intval($detail['product_id']);
                            $voucher_result = mysqli_query($conn, $voucher_query);
                            if ($voucher_row = mysqli_fetch_assoc($voucher_result)) {
                                $diskon_persen = $voucher_row['discount'];
                                $diskon_maksimal = $voucher_row['max_discount'];
                            }

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
                        <td class="price">Rp<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>Tidak ada produk</h3>
                            <p>Detail produk tidak tersedia</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Payment Summary -->
    <div class="payment-summary" style="margin-top: 2rem; background: var(--accent-color); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Payment Summary</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; max-width: 300px;">
            <div style="font-weight: 600;">Total:</div>
            <div style="text-align: right; font-weight: 700; font-size: 1.1rem;">
                Rp<?php echo number_format($total_amount, 0, ',', '.'); ?>
            </div>

            <?php if ($show_payment_info): ?>
                <div style="font-weight: 600;">Pay:</div>
                <div style="text-align: right; font-weight: 700; font-size: 1.1rem; color: var(--success-color);">
                    Rp<?php echo number_format($payment_amount, 0, ',', '.'); ?>
                </div>

                <div style="font-weight: 600;">Spare Change:</div>
                <div style="text-align: right; font-weight: 700; font-size: 1.1rem; color: var(--info-color);">
                    Rp<?php echo number_format($payment_change, 0, ',', '.'); ?>
                </div>
            <?php else: ?>
                <div style="font-weight: 600;">Status:</div>
                <div style="text-align: right;">
                    <span class="status-badge status-pending">Belum Bayar</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="actions" style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="list.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Receipt
        </button>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
