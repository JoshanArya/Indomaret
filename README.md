# Indomaret (Simple POS)

Sebuah aplikasi point-of-sale sederhana untuk latihan (PHP, MySQL). Dibangun untuk digunakan pada lingkungan pengembangan lokal (mis. Laragon).

**Tujuan**
- Menyediakan contoh sistem transaksi, produk, kasir, dan voucher.

**Fitur utama**
- Manajemen produk, kasir, voucher
- Pembuatan transaksi dengan multi-produk
- Detail transaksi dan cetak struk sederhana

**Struktur Project (penting)**
- `index.php` — beranda
- `config/` — file konfigurasi (`config.php`)
- `database/db_indomaret.sql` — dump database (schema + sample data)
- `includes/` — `header.php`, `footer.php`
- `pages/` — halaman aplikasi (transactions, products, cashiers, ...)
- `process/` — script yang memproses form (insert/update/delete)

**Persyaratan**
- PHP 7.4+ (tergantung Laragon yang terpasang)
- MySQL / MariaDB
- Web server (Apache / Nginx)

**Setup singkat (Laragon / Windows)**
1. Taruh folder proyek di `C:\laragon\www\Indomaret` (atau path sesuai Laragon Anda). Pastikan `ROOTPATH` di beberapa file menunjuk ke `/indomaret` jika Anda menggunakan folder ini.
2. Start Laragon (Apache + MySQL).
3. Import database:

PowerShell (jalankan di folder project atau berikan path penuh):

```powershell
# Buat database dan import (ganti user/password jika perlu)
mysql -u root -p < database\db_indomaret.sql
# Jika MySQL tidak ada di PATH, gunakan path ke mysql.exe, contoh:
# "C:\laragon\bin\mysql\mysql-8.0.XX\bin\mysql.exe" -u root -p < database\db_indomaret.sql
```

Atau gunakan phpMyAdmin: import file `database/db_indomaret.sql`.

**File konfigurasi penting**
- `config/config.php` — variabel koneksi MySQL (`$conn`) dan lainnya.
- `process/transactions_process.php` — memproses penambahan/ubah/hapus transaksi.
- `pages/transactions/add.php` dan `pages/transactions/edit.php` — form transaksi (JS handling).

**Catatan teknis & penyesuaian**
- ROOTPATH: beberapa file menggunakan:
  ```php
  define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
  ```
  Jika Anda meletakkan project di folder lain (mis. `www/Indomaret_RPL3`), ubah `'/indomaret'` sesuai folder Anda.

- Perilaku pemilihan produk pada transaksi:
  - Jika Anda ingin mencegah produk yang sama dipilih di lebih dari satu baris, buka `pages/transactions/add.php` dan `pages/transactions/edit.php` dan ubah baris:
    ```javascript
    const allowDuplicateProducts = false; // false = cegah duplikat, true = izinkan
    ```
    - `false`: option produk yang sudah dipilih akan otomatis disabled pada dropdown lain.
    - `true` : produk bisa dipilih berkali-kali.

- Validasi harga dan stok:
  - Harga yang disimpan ke `tb_transaction_details.related_price` diambil dari DB saat proses submit. Ini mencegah manipulasi harga dari client-side.

- Database schema utama (ringkas):
  - `tb_products` (id, product_name, price, stock, voucher_id)
  - `tb_vouchers` (id, voucher_name, discount, max_discount, expired_date, status)
  - `tb_cashiers` (id, cashier_name)
  - `tb_transactions` (id, created_at, code, cashier_id, total, spare_change, status, pay)
  - `tb_transaction_details` (transaction_id, product_id, quantity, sub_total, related_price, discount)

**Troubleshooting umum**
- Jika transaksi tersimpan tapi `tb_transaction_details` kosong:
  - Pastikan form mengirim `product_id[]` dan `quantity[]` (lihat `name` attribute di `add.php`).
  - Periksa `process/transactions_process.php` — skrip harus mengambil `$_POST['product_id']` dan `$_POST['quantity']`.

- Jika halaman include gagal (error path): periksa `ROOTPATH` atau gunakan relative include seperti `include '../../config/config.php';` tergantung lokasi file.

**Pengembangan lebih lanjut (opsional)**
- Tambahkan prepared statements untuk keamanan SQL (mysqli_prepare / PDO).
- Validasi sisi server lebih ketat (cek stock sebelum insert detail, rollback jika stock tidak cukup).
- Tambahkan fitur user authentication untuk manajemen kasir.
