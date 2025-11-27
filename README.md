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
- [Struktur Project](#-struktur-project)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Database Schema](#-database-schema)

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

**Daze Production**

