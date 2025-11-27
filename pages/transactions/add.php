<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/../config/config.php";
include ROOTPATH . "/../includes/header.php";

// Get all products for dropdown
$products_query = "SELECT id, product_name, price, stock, voucher_id FROM tb_products WHERE stock > 0 ORDER BY product_name ASC";
$products_result = mysqli_query($conn, $products_query);
$products = [];
while ($product = mysqli_fetch_assoc($products_result)) {
    $products[] = $product;
}

// Get all cashiers for dropdown
$cashiers_query = "SELECT id, cashier_name FROM tb_cashiers ORDER BY cashier_name ASC";
$cashiers_result = mysqli_query($conn, $cashiers_query);
$cashiers = [];
while ($cashier = mysqli_fetch_assoc($cashiers_result)) {
    $cashiers[] = $cashier;
}
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Tambah Transaksi</h1>
        <p>Buat transaksi penjualan baru</p>
    </div>

    <div class="card">
        <div class="card-header" style="background: var(--accent-color); padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color);">
            <h3 style="margin: 0;">Data Transaksi</h3>
        </div>
        <div style="padding: 1.5rem;">
            <form action="../../process/transactions_process.php" method="POST" id="transactionForm">
                <input type="hidden" name="action" value="add" />

                <!-- Cashier Selection -->
                <div class="form-group">
                    <label for="cashier_id"><i class="fas fa-user"></i> Kasir:</label>
                    <select id="cashier_id" name="cashier_id" class="form-control" required>
                        <option value="">-- Pilih Kasir --</option>
                        <?php foreach ($cashiers as $cashier): ?>
                            <option value="<?= $cashier['id']; ?>"><?= htmlspecialchars($cashier['cashier_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Products Section -->
                <div style="margin-top: 2rem; margin-bottom: 2rem; border-top: 2px solid var(--border-color); padding-top: 1.5rem;">
                    <h4 style="margin-bottom: 1rem;"><i class="fas fa-box"></i> Daftar Produk</h4>
                    
                    <div id="productItems">
                        <!-- First product item -->
                        <div class="product-item" style="background: var(--accent-color); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid var(--border-color);">
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="product_id_0">Produk:</label>
                                    <select name="product_id[]" class="form-control product-select" data-index="0" required>
                                        <option value="">-- Pilih Produk --</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['id']; ?>" 
                                                    data-price="<?= $product['price']; ?>" 
                                                    data-stock="<?= $product['stock']; ?>" 
                                                    data-voucher="<?= $product['voucher_id'] ?? ''; ?>">
                                                <?= htmlspecialchars($product['product_name']); ?> (Rp<?= number_format($product['price'], 0, ',', '.'); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="quantity_0">Jumlah:</label>
                                    <input type="number" name="quantity[]" class="form-control quantity-input" data-index="0" min="1" value="1" required />
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Harga:</label>
                                    <input type="text" class="form-control price-display" data-index="0" readonly style="background: #f5f5f5;" />
                                </div>

                                <div style="display: flex; align-items: flex-end; margin-bottom: 0;">
                                    <button type="button" class="btn btn-danger remove-product" style="display: none; width: 100%;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addProductBtn" class="btn btn-outline" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </button>
                </div>

                <!-- Payment Section -->
                <div style="margin-top: 2rem; padding: 1.5rem; background: var(--accent-color); border-radius: 8px; border: 1px solid var(--border-color);">
                    <h4 style="margin-bottom: 1rem;"><i class="fas fa-money-bill"></i> Pembayaran</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; max-width: 500px;">
                        <div class="form-group">
                            <label>Total:</label>
                            <div style="border: 2px solid var(--primary-color); border-radius: 6px; padding: 0.75rem; background: white;">
                                <input type="text" id="totalDisplay" readonly style="border: none; background: transparent; font-weight: 700; font-size: 1.2rem; width: 100%; color: var(--primary-color);" />
                            </div>
                            <input type="hidden" name="total" id="totalInput" />
                        </div>

                        <div class="form-group">
                            <label for="pay">Jumlah Bayar:</label>
                            <input type="number" id="pay" name="pay" class="form-control" min="0" placeholder="0" required 
                                   style="border: 2px solid var(--info-color); padding: 0.75rem; font-size: 1.1rem;" />
                        </div>

                        <div class="form-group">
                            <label>Kembalian:</label>
                            <div style="border: 2px solid var(--success-color); border-radius: 6px; padding: 0.75rem; background: white;">
                                <input type="text" id="spareChangeDisplay" readonly style="border: none; background: transparent; font-weight: 700; font-size: 1.2rem; width: 100%; color: var(--success-color);" />
                            </div>
                            <input type="hidden" name="spare_change" id="spareChangeInput" />
                        </div>

                        <div class="form-group">
                            <label for="code">Kode Transaksi:</label>
                            <div style="border: 2px solid var(--warning-color); border-radius: 6px; padding: 0.75rem; background: white;">
                                <input type="text" id="code" name="code" readonly style="border: none; background: transparent; font-weight: 600; font-size: 1.2rem; width: 100%; color: var(--warning-color);" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="actions" style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="list.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let productCount = 1;
const products = <?php echo json_encode($products); ?>;
// ===== PENGATURAN: Ubah ke false untuk MENCEGAH produk duplikat =====
const allowDuplicateProducts = false; // true = boleh duplikat, false = cegah duplikat

// Function to update price display
function updatePriceDisplay(index) {
    const select = document.querySelector(`select[name="product_id[]"][data-index="${index}"]`);
    const option = select.options[select.selectedIndex];
    const priceDisplay = document.querySelector(`.price-display[data-index="${index}"]`);
    const price = option.dataset.price || 0;
    
    if (price) {
        priceDisplay.value = 'Rp' + parseInt(price).toLocaleString('id-ID');
    } else {
        priceDisplay.value = '';
    }
    
    calculateTotal();
}

// Function to update product options visibility (hide/disable already selected products)
function updateProductOptionsVisibility() {
    if (allowDuplicateProducts) return; // Bypass jika allow duplicate
    
    const allSelects = document.querySelectorAll('select[name="product_id[]"]');
    const selectedProductIds = {};
    
    // Kumpulkan semua product ID yang sudah dipilih
    allSelects.forEach(sel => {
        const value = sel.value;
        const index = sel.getAttribute('data-index');
        if (value !== '') {
            if (!selectedProductIds[value]) {
                selectedProductIds[value] = [];
            }
            selectedProductIds[value].push(index);
        }
    });
    
    // Update setiap select: disable option yang sudah dipilih di baris lain
    allSelects.forEach(select => {
        const currentIndex = select.getAttribute('data-index');
        const currentValue = select.value;
        
        Array.from(select.options).forEach(option => {
            if (option.value === '') return; // Skip placeholder
            
            const isSelectedElsewhere = selectedProductIds[option.value] && 
                                       selectedProductIds[option.value].some(idx => idx !== currentIndex);
            
            option.disabled = isSelectedElsewhere;
            option.style.backgroundColor = isSelectedElsewhere ? '#f5f5f5' : '';
        });
    });
}

// Function to calculate total
function calculateTotal() {
    let total = 0;
    const productSelects = document.querySelectorAll('select[name="product_id[]"]');
    
    productSelects.forEach(select => {
        const index = select.dataset.index;
        const option = select.options[select.selectedIndex];
        const price = parseInt(option.dataset.price) || 0;
        const quantity = parseInt(document.querySelector(`.quantity-input[data-index="${index}"]`).value) || 0;
        
        total += price * quantity;
    });
    
    document.getElementById('totalInput').value = total;
    document.getElementById('totalDisplay').value = 'Rp' + total.toLocaleString('id-ID');
    
    updateSpareChange();
}

// Function to update spare change
function updateSpareChange() {
    const total = parseInt(document.getElementById('totalInput').value) || 0;
    const pay = parseInt(document.getElementById('pay').value) || 0;
    const spareChange = pay - total;
    
    document.getElementById('spareChangeInput').value = spareChange;
    document.getElementById('spareChangeDisplay').value = 'Rp' + spareChange.toLocaleString('id-ID');
    
    // Change color based on spare change
    const display = document.getElementById('spareChangeDisplay');
    const border = display.closest('div');
    if (spareChange < 0) {
        display.style.color = 'var(--danger-color)';
        border.style.borderColor = 'var(--danger-color)';
    } else {
        display.style.color = 'var(--success-color)';
        border.style.borderColor = 'var(--success-color)';
    }
}

// Add product row
document.getElementById('addProductBtn').addEventListener('click', function() {
    const productItems = document.getElementById('productItems');
    const newItem = document.createElement('div');
    newItem.className = 'product-item';
    newItem.style.cssText = 'background: var(--accent-color); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid var(--border-color);';
    newItem.innerHTML = `
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="product_id_${productCount}">Produk:</label>
                <select name="product_id[]" class="form-control product-select" data-index="${productCount}" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id']; ?>" 
                                data-price="<?= $product['price']; ?>" 
                                data-stock="<?= $product['stock']; ?>" 
                                data-voucher="<?= $product['voucher_id'] ?? ''; ?>">
                            <?= htmlspecialchars($product['product_name']); ?> (Rp<?= number_format($product['price'], 0, ',', '.'); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="quantity_${productCount}">Jumlah:</label>
                <input type="number" name="quantity[]" class="form-control quantity-input" data-index="${productCount}" min="1" value="1" required />
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>Harga:</label>
                <input type="text" class="form-control price-display" data-index="${productCount}" readonly style="background: #f5f5f5;" />
            </div>

            <div style="display: flex; align-items: flex-end; margin-bottom: 0;">
                <button type="button" class="btn btn-danger remove-product" style="width: 100%;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    productItems.appendChild(newItem);
    
    // Add event listeners to new product select and quantity
    const newSelect = newItem.querySelector('select');
    const newQuantity = newItem.querySelector('input[type="number"]');
    const removeBtn = newItem.querySelector('.remove-product');
    
    newSelect.addEventListener('change', function() {
        updatePriceDisplay(this.dataset.index);
        updateProductOptionsVisibility();
    });
    
    newQuantity.addEventListener('input', function() {
        calculateTotal();
    });
    
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        newItem.remove();
        calculateTotal();
        updateRemoveButtons();
        updateProductOptionsVisibility();
    });
    
    // Initialize price display for new item
    updatePriceDisplay(productCount);
    
    // Update first item's remove button visibility
    updateRemoveButtons();
    updateProductOptionsVisibility();
    productCount++;
});

// Remove product row
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-product')) {
        e.preventDefault();
        const item = e.target.closest('.product-item');
        item.remove();
        calculateTotal();
        updateRemoveButtons();
        updateProductOptionsVisibility();
    }
});

// Update remove button visibility (show only if more than 1 item)
function updateRemoveButtons() {
    const items = document.querySelectorAll('.product-item');
    items.forEach((item, index) => {
        const btn = item.querySelector('.remove-product');
        btn.style.display = items.length > 1 ? 'block' : 'none';
    });
}

// Event listeners for product select and quantity
document.querySelectorAll('select[name="product_id[]"]').forEach(select => {
    select.addEventListener('change', function() {
        updatePriceDisplay(this.dataset.index);
        updateProductOptionsVisibility();
    });
});

document.querySelectorAll('input[type="number"][name="quantity[]"]').forEach(input => {
    input.addEventListener('input', function() {
        calculateTotal();
    });
});

document.getElementById('pay').addEventListener('input', function() {
    updateSpareChange();
});

// Generate transaction code
function generateTransactionCode() {
    const timestamp = new Date().getTime().toString().slice(-4);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    return 'TRX' + timestamp + random;
}

// Set initial code
document.getElementById('code').value = generateTransactionCode();

// Initialize first product price display
updatePriceDisplay(0);
updateRemoveButtons();
updateProductOptionsVisibility();
</script>

<?php include ROOTPATH . "/../includes/footer.php"; ?>
