-- --------------------------------------------------------
-- Hapus dan buat ulang database (HATI-HATI! Backup dulu!)
-- --------------------------------------------------------

DROP DATABASE IF EXISTS `db_indomaret`;
CREATE DATABASE `db_indomaret` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `db_indomaret`;

-- --------------------------------------------------------
-- Table structure for table `tb_cashiers`
-- --------------------------------------------------------

CREATE TABLE `tb_cashiers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cashier_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Insert data kasir
INSERT INTO `tb_cashiers` (`id`, `cashier_name`) VALUES
(1, 'Ratih'),
(2, 'Turah'),
(3, 'Arie'),
(4, 'Gio');

-- --------------------------------------------------------
-- Table structure for table `tb_vouchers`
-- --------------------------------------------------------

CREATE TABLE `tb_vouchers` (
  `id` char(6) NOT NULL,
  `voucher_name` varchar(100) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `max_discount` int NOT NULL,
  `expired_date` date NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data vouchers
INSERT INTO `tb_vouchers` (`id`, `voucher_name`, `discount`, `max_discount`, `expired_date`, `status`) VALUES
('vo-001', 'voucher abc orange squash', 0.10, 10000, '2025-12-31', 'active'),
('vo-002', 'voucher indofood wonderland', 0.20, 200000, '2025-12-31', 'active');

-- --------------------------------------------------------
-- Table structure for table `tb_products`
-- --------------------------------------------------------

CREATE TABLE `tb_products` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `voucher_id` char(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produk_voucher` (`voucher_id`),
  CONSTRAINT `fk_produk_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `tb_vouchers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Insert data products
INSERT INTO `tb_products` (`id`, `product_name`, `price`, `stock`, `voucher_id`) VALUES
(1, 'ABC IRABGE 525ML', 18000, 20, 'vo-001'),
(2, 'I/F BISC.WNDRLND 300', 20900, 300, 'vo-002'),
(3, 'LEXUS SANDW COKL 190', 26800, 200, NULL),
(4, 'LUWAK WHT ORGL 20X20', 25400, 240, NULL),
(5, 'KOPIKO 78C 240ML', 19800, 150, NULL),
(6, 'SUSU INDOMILK 1LT', 24900, 80, NULL),
(7, 'INDOMIE ACEH', 3000, 1000, NULL);

-- --------------------------------------------------------
-- Table structure for table `tb_transactions`
-- --------------------------------------------------------

CREATE TABLE `tb_transactions` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `code` varchar(10) NOT NULL DEFAULT '',
  `cashier_id` int DEFAULT NULL,
  `total` int NOT NULL,
  `spare_change` int NOT NULL,
  `status` enum('paid','pending','voided') NOT NULL DEFAULT 'pending',
  `pay` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transaksi_kasir` (`cashier_id`),
  CONSTRAINT `fk_transaksi_kasir` FOREIGN KEY (`cashier_id`) REFERENCES `tb_cashiers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Insert data transactions (sama seperti di atas)
INSERT INTO `tb_transactions` (`id`, `created_at`, `code`, `cashier_id`, `total`, `spare_change`, `status`, `pay`) VALUES
(1, '2025-01-15 08:30:15', 'TRX001', 1, 74000, 25918, 'paid', 100000),
(2, '2025-01-15 09:45:22', 'TRX002', 2, 45000, 5000, 'paid', 50000),
(3, '2025-01-15 11:20:33', 'TRX003', 3, 120000, 30000, 'paid', 150000),
(4, '2025-01-15 14:15:47', 'TRX004', 4, 35000, 15000, 'paid', 50000),
(5, '2025-01-15 16:40:12', 'TRX005', 1, 89000, 11000, 'paid', 100000),
(6, '2025-01-16 10:05:28', 'TRX006', 2, 67000, 33000, 'paid', 100000),
(7, '2025-01-16 13:30:45', 'TRX007', 3, 23000, 27000, 'paid', 50000),
(8, '2025-01-16 17:20:18', 'TRX008', 4, 156000, 44000, 'paid', 200000),
(9, '2025-01-17 08:55:33', 'TRX009', 1, 45000, 5000, 'paid', 50000),
(10, '2025-01-17 12:10:27', 'TRX010', 2, 78000, 22000, 'paid', 100000);

-- --------------------------------------------------------
-- Table structure for table `tb_transaction_details`
-- --------------------------------------------------------

CREATE TABLE `tb_transaction_details` (
  `transaction_id` smallint NOT NULL,
  `product_id` smallint NOT NULL,
  `quantity` int NOT NULL,
  `sub_total` int NOT NULL,
  `related_price` smallint NOT NULL,
  `discount` double DEFAULT NULL,
  PRIMARY KEY (`transaction_id`,`product_id`),
  KEY `fk_detail_produk` (`product_id`),
  CONSTRAINT `fk_details_produk` FOREIGN KEY (`product_id`) REFERENCES `tb_products` (`id`),
  CONSTRAINT `fk_details_transaksi` FOREIGN KEY (`transaction_id`) REFERENCES `tb_transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data transaction_details (sama seperti di atas)
INSERT INTO `tb_transaction_details` (`transaction_id`, `product_id`, `quantity`, `sub_total`, `related_price`, `discount`) VALUES
(1, 2, 2, 52000, 26000, NULL),
(1, 3, 1, 22000, 22000, NULL),
(2, 1, 2, 36000, 18000, NULL),
(2, 7, 3, 9000, 3000, NULL),
(3, 4, 3, 76200, 25400, NULL),
(3, 5, 2, 39600, 19800, NULL),
(3, 6, 1, 24900, 24900, NULL),
(4, 3, 1, 26800, 26800, NULL),
(4, 7, 3, 9000, 3000, NULL),
(5, 1, 3, 54000, 18000, NULL),
(5, 2, 1, 20900, 20900, NULL),
(5, 7, 5, 15000, 3000, NULL),
(6, 4, 2, 50800, 25400, NULL),
(6, 5, 1, 19800, 19800, NULL),
(7, 3, 1, 26800, 26800, NULL),
(8, 1, 4, 72000, 18000, NULL),
(8, 2, 2, 41800, 20900, NULL),
(8, 6, 2, 49800, 24900, NULL),
(9, 5, 2, 39600, 19800, NULL),
(9, 7, 2, 6000, 3000, NULL),
(10, 3, 2, 53600, 26800, NULL),
(10, 4, 1, 25400, 25400, NULL);