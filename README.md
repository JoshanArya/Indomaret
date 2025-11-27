# 🏪 Indomaret - Simple POS System

> Aplikasi Point-of-Sale sederhana untuk pembelajaran & latihan. Dibangun dengan **PHP**, **MySQL**, dan **Vanilla JavaScript**.

![Status](https://img.shields.io/badge/status-development-yellow)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![License](https://img.shields.io/badge/license-MIT-green)

---

## 📋 Daftar Isi

- [Fitur](#-fitur)
- [Teknologi](#-teknologi)
- [Setup & Instalasi](#-setup--instalasi)
- [Struktur Project](#-struktur-project)
- [Konfigurasi](#-konfigurasi)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Tips Developer](#-tips-developer)
- [Troubleshooting](#-troubleshooting)

---

## ✨ Fitur

| Fitur | Deskripsi |
|-------|-----------|
| 🛍️ **Manajemen Produk** | Tambah, edit, hapus produk dengan harga & stok |
| 👥 **Manajemen Kasir** | Kelola data kasir yang melayani transaksi |
| 🎟️ **Sistem Voucher** | Terapkan diskon dengan max limit per produk |
| 💰 **Transaksi Multi-Produk** | Buat transaksi dengan multiple items & hitung otomatis |
| 📝 **Detail Transaksi** | Lihat rincian transaksi & cetak struk |
| 🧮 **Kalkulasi Otomatis** | Total, kembalian, diskon dihitung real-time |
| 🚫 **Smart Duplicate Guard** | Cegah/izinkan produk duplikat per baris transaksi |

---

## 🛠️ Teknologi

- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0 / MariaDB
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Server**: Apache (Laragon)
- **Query Builder**: MySQLi (procedural)

---

## 🚀 Setup & Instalasi

### Prasyarat

✅ Laragon terinstall  
✅ PHP 7.4+ dan MySQL running  
✅ Akses PowerShell / Terminal  

### Langkah-Langkah

#### 1️⃣ Clone / Taruh Project

Taruh folder proyek di folder `www` Laragon:

```
C:\laragon\www\Indomaret\
```

#### 2️⃣ Start Laragon

Buka Laragon dan klik **Start All** (jalankan Apache + MySQL).

#### 3️⃣ Import Database

**Opsi A: PowerShell (Rekomendasi)**

```powershell
# Navigasi ke folder proyek
cd C:\laragon\www\Indomaret

# Import database
mysql -u root < database\db_indomaret.sql
```

**Opsi B: phpMyAdmin**

1. Buka `http://localhost/phpmyadmin`
2. Pilih tab **Import**
3. Upload file `database/db_indomaret.sql`
4. Klik **Go**

#### 4️⃣ Verifikasi Konfigurasi

Buka `config/config.php` dan pastikan:

```php
$server = "localhost";      // Host MySQL
$user = "root";             // User MySQL
$password = "";             // Password (default kosong di Laragon)
$db = "db_indomaret";       // Nama database
```

#### 5️⃣ Buka Browser

Akses aplikasi:

```
http://localhost/Indomaret
```

✅ Berhasil! Anda akan melihat dashboard dengan menu Produk, Kasir, Transaksi.

---

## 📁 Struktur Project

```
Indomaret/
├── index.php                          # 🏠 Halaman utama
├── config/
│   └── config.php                     # ⚙️ Konfigurasi DB & ROOTPATH
├── database/
│   └── db_indomaret.sql              # 💾 Dump database (schema + data)
├── includes/
│   ├── header.php                     # 📌 Header template
│   └── footer.php                     # 📄 Footer template
├── pages/
│   ├── dashboard.php                  # 📊 Dashboard
│   ├── products/
│   │   ├── list.php                   # 📋 Daftar produk
│   │   ├── add.php                    # ➕ Tambah produk
│   │   └── edit.php                   # ✏️ Edit produk
│   ├── cashiers/
│   │   ├── list.php                   # 👥 Daftar kasir
│   │   ├── add.php                    # ➕ Tambah kasir
│   │   └── edit.php                   # ✏️ Edit kasir
│   └── transactions/
│       ├── list.php                   # 💳 Daftar transaksi
│       ├── add.php                    # ➕ Buat transaksi
│       ├── edit.php                   # ✏️ Edit transaksi
│       └── transaction_details.php    # 📄 Detail transaksi
├── process/
│   ├── products_process.php           # 🔄 Handler produk
│   ├── cashiers_process.php           # 🔄 Handler kasir
│   └── transactions_process.php       # 🔄 Handler transaksi
├── assets/
│   ├── css/
│   │   └── style.css                  # 🎨 Stylesheet utama
│   ├── img/                           # 🖼️ Gambar
│   └── js/
│       └── script.js                  # ⚙️ JavaScript global
└── README.md                          # 📖 File ini
```

---

## ⚙️ Konfigurasi

### 🔑 ROOTPATH

Beberapa file menggunakan:

```php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
```

**Jika Anda menggunakan nama folder berbeda**, ubah nilai path-nya:

```php
// Contoh: jika folder adalah www/MyPOS
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/MyPOS');
```

### 🔄 Perilaku Duplikat Produk pada Transaksi

Edit file `pages/transactions/add.php` dan `pages/transactions/edit.php`, cari baris:

```javascript
const allowDuplicateProducts = false; // ⚙️ Ubah sesuai kebutuhan
```

| Setting | Perilaku | Gunakan Saat |
|---------|----------|--------------|
| `true` | ✅ Produk bisa dipilih berkali-kali di baris berbeda | Ingin split qty |
| `false` | ❌ Produk disabled di dropdown setelah dipilih | Cegah duplikat |

**Contoh `false`:**
- Pilih "INDOMIE" di baris 1 → INDOMIE otomatis tidak muncul/disabled di baris 2
- Hapus baris 1 → INDOMIE bisa dipilih lagi

---

## 📖 Panduan Penggunaan

### 💳 Membuat Transaksi

1. Klik **Transaksi** → **Tambah Transaksi**
2. Pilih **Kasir** dari dropdown
3. Pilih **Produk** & masukkan **Jumlah**
4. Klik **Tambah Produk** jika ingin tambah item lain
5. Sistem otomatis hitung **Total**
6. Masukkan **Jumlah Bayar**
7. Lihat **Kembalian** (real-time)
8. Klik **Simpan Transaksi**

✨ **Fitur Smart:**
- Harga produk ter-update otomatis saat dipilih
- Total & kembalian dihitung real-time
- Kode transaksi auto-generate
- Validasi produk duplikat (sesuai setting)

### 📝 Edit Transaksi

1. Klik **Transaksi** → pilih transaksi
2. Klik tombol **Edit**
3. Ubah data (kasir, produk, qty, pembayaran)
4. Klik **Simpan Perubahan**

### 📄 Lihat Detail & Cetak

1. Klik **Transaksi** → pilih transaksi
2. Klik **Detail** untuk melihat rincian
3. Klik **Print Receipt** untuk cetak struk

---

## 💡 Tips Developer

### 📌 Catatan Teknis

#### 1. **Keamanan Harga**
Harga yang tersimpan di `tb_transaction_details.related_price` **diambil dari database** saat submit (bukan dari client). Ini mencegah manipulasi harga.

```php
// Di transactions_process.php
$price_query = "SELECT price FROM tb_products WHERE id = $product_id";
$price_result = mysqli_query($conn, $price_query);
$related_price = $price_row['price']; // ✅ Ambil dari DB
```

#### 2. **Database Relationships**

```
tb_products
  ├─ foreign key: voucher_id → tb_vouchers.id

tb_transactions
  ├─ foreign key: cashier_id → tb_cashiers.id

tb_transaction_details
  ├─ foreign key: transaction_id → tb_transactions.id
  ├─ foreign key: product_id → tb_products.id
```

#### 3. **Status Transaksi**
Field `status` di `tb_transactions` adalah enum:
- `paid` — pembayaran >= total
- `pending` — pembayaran < total
- `voided` — transaksi dibatalkan (opsional)

### 🚀 Pengembangan Lebih Lanjut

- [ ] **Prepared Statements** — Ganti query langsung dengan MySQLi prepared untuk keamanan SQL Injection
- [ ] **User Authentication** — Tambah login untuk kasir
- [ ] **Stock Validation** — Cek stok sebelum insert, rollback jika kurang
- [ ] **Laporan Harian** — Dashboard dengan grafik penjualan
- [ ] **PDO Migration** — Migrasi dari MySQLi ke PDO untuk portabilitas
- [ ] **REST API** — Tambahkan endpoint JSON untuk mobile app

---

## 🔍 Database Schema

### 📦 tb_products
```sql
id (PK)           INT
product_name      VARCHAR(100)
price             INT
stock             INT
voucher_id (FK)   CHAR(6) → tb_vouchers.id
```

### 🎟️ tb_vouchers
```sql
id (PK)           CHAR(6)
voucher_name      VARCHAR(100)
discount          DECIMAL(5,2)       -- Persentase diskon
max_discount      INT                -- Max nominal diskon
expired_date      DATE
status            ENUM('active','inactive')
```

### 👥 tb_cashiers
```sql
id (PK)           INT
cashier_name      VARCHAR(50)
```

### 💳 tb_transactions
```sql
id (PK)           SMALLINT
created_at        TIMESTAMP
code              VARCHAR(10)
cashier_id (FK)   INT → tb_cashiers.id
total             INT
spare_change      INT
status            ENUM('paid','pending','voided')
pay               INT
```

### 🛒 tb_transaction_details
```sql
transaction_id (FK)  SMALLINT → tb_transactions.id
product_id (FK)      SMALLINT → tb_products.id
quantity             INT
sub_total            INT
related_price        SMALLINT          -- Harga saat transaksi
discount             DOUBLE (optional)
```

---

## ❌ Troubleshooting

### ❓ Database Import Gagal
**Solusi:**
- Pastikan MySQL sudah running (`Laragon → Start All`)
- Gunakan path absolut: `mysql -u root < "C:\laragon\www\Indomaret\database\db_indomaret.sql"`
- Cek file encoding (UTF-8)

### ❓ Halaman Blank / Error Path
**Solusi:**
- Periksa `ROOTPATH` di `config/config.php` sesuai dengan folder Anda
- Cek include path: `include ROOTPATH . "/../config/config.php";` vs `include '../../config/config.php';`

### ❓ Transaksi Tersimpan tapi Detail Kosong
**Solusi:**
- Pastikan form mengirim array: `name="product_id[]"` dan `name="quantity[]"`
- Periksa `process/transactions_process.php` — ambil `$_POST['product_id']` dan `$_POST['quantity']`
- Pastikan minimal 1 produk dipilih

### ❓ Produk Tidak Tampil di Dropdown
**Solusi:**
- Pastikan stok produk > 0 (`WHERE stock > 0`)
- Cek `allowDuplicateProducts` setting — jika `false`, produk duplikat akan disabled

### ❓ Total Tidak Ter-update
**Solusi:**
- Buka browser console (`F12 → Console`)
- Periksa error JavaScript
- Pastikan produk sudah dipilih sebelum ubah qty

---

## 📞 Support & Kontribusi

Jika Anda menemukan bug atau punya saran improvement:

1. Periksa issue di bagian **Troubleshooting** di atas
2. Buka issue dengan deskripsi jelas & screenshot
3. Submit PR dengan deskripsi perubahan

---

## 📄 Lisensi

Project ini bebas digunakan untuk keperluan pembelajaran & pengembangan personal.

---

## 🎓 Sumber Belajar

- **MySQL**: https://dev.mysql.com/doc/
- **PHP MySQLi**: https://www.php.net/manual/en/book.mysqli.php
- **JavaScript DOM**: https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model

---

**Made with ❤️ untuk pembelajaran & praktik coding.**

Terakhir update: **27 November 2025**
