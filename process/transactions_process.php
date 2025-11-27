<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/../config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        // Insert ke tb_transactions
        $code = mysqli_real_escape_string($conn, $_POST['code']);
        $cashier_id = intval($_POST['cashier_id']);
        $total = intval($_POST['total']);
        $pay = intval($_POST['pay']);
        $spare_change = intval($_POST['spare_change']);
        $status = ($pay >= $total) ? 'paid' : 'pending';
        
        $query = "INSERT INTO tb_transactions (code, cashier_id, total, spare_change, pay, status, created_at) 
                 VALUES ('$code', $cashier_id, $total, $spare_change, $pay, '$status', NOW())";
        
        if (!mysqli_query($conn, $query)) {
            die("Error inserting transaction: " . mysqli_error($conn));
        }
        
        // Dapat ID transaksi yang baru
        $transaction_id = mysqli_insert_id($conn);
        
        // Insert ke tb_transaction_details
        // Data dikirim sebagai array product_id[] dan quantity[]
        $product_ids = $_POST['product_id'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        
        foreach ($product_ids as $key => $product_id) {
            $product_id = intval($product_id);
            $quantity = intval($quantities[$key] ?? 1);
            
            if ($product_id > 0 && $quantity > 0) {
                // Ambil harga produk dari database
                $price_query = "SELECT price FROM tb_products WHERE id = $product_id";
                $price_result = mysqli_query($conn, $price_query);
                
                if ($price_result && $price_row = mysqli_fetch_assoc($price_result)) {
                    $related_price = $price_row['price'];
                    $sub_total = $quantity * $related_price;
                    
                    $detail_query = "INSERT INTO tb_transaction_details 
                                   (transaction_id, product_id, quantity, sub_total, related_price) 
                                   VALUES ($transaction_id, $product_id, $quantity, $sub_total, $related_price)";
                    
                    if (!mysqli_query($conn, $detail_query)) {
                        die("Error inserting transaction detail: " . mysqli_error($conn));
                    }
                }
            }
        }
        
    } elseif ($action == 'edit') {
        // Update tb_transactions
        $id = intval($_POST['id']);
        $code = mysqli_real_escape_string($conn, $_POST['code']);
        $cashier_id = intval($_POST['cashier_id']);
        $total = intval($_POST['total']);
        $pay = intval($_POST['pay']);
        $spare_change = intval($_POST['spare_change']);
        $status = ($pay >= $total) ? 'paid' : 'pending';
        
        $query = "UPDATE tb_transactions SET code='$code', cashier_id=$cashier_id, total=$total, spare_change=$spare_change, pay=$pay, status='$status' WHERE id=$id";
        
        if (!mysqli_query($conn, $query)) {
            die("Error updating transaction: " . mysqli_error($conn));
        }
        
        // Hapus detail lama
        $delete_details = "DELETE FROM tb_transaction_details WHERE transaction_id=$id";
        if (!mysqli_query($conn, $delete_details)) {
            die("Error deleting transaction details: " . mysqli_error($conn));
        }
        
        // Insert detail baru
        $product_ids = $_POST['product_id'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        
        foreach ($product_ids as $key => $product_id) {
            $product_id = intval($product_id);
            $quantity = intval($quantities[$key] ?? 1);
            
            if ($product_id > 0 && $quantity > 0) {
                $price_query = "SELECT price FROM tb_products WHERE id = $product_id";
                $price_result = mysqli_query($conn, $price_query);
                
                if ($price_result && $price_row = mysqli_fetch_assoc($price_result)) {
                    $related_price = $price_row['price'];
                    $sub_total = $quantity * $related_price;
                    
                    $detail_query = "INSERT INTO tb_transaction_details 
                                   (transaction_id, product_id, quantity, sub_total, related_price) 
                                   VALUES ($id, $product_id, $quantity, $sub_total, $related_price)";
                    
                    if (!mysqli_query($conn, $detail_query)) {
                        die("Error inserting transaction detail: " . mysqli_error($conn));
                    }
                }
            }
        }
        
    } elseif ($action == 'delete') {
        $id = intval($_POST['id']);
        
        // Hapus detail dulu baru transaksi
        $delete_details = "DELETE FROM tb_transaction_details WHERE transaction_id=$id";
        if (!mysqli_query($conn, $delete_details)) {
            die("Error deleting transaction details: " . mysqli_error($conn));
        }
        
        $query = "DELETE FROM tb_transactions WHERE id=$id";
        if (!mysqli_query($conn, $query)) {
            die("Error deleting transaction: " . mysqli_error($conn));
        }
    }

    header("Location: ../pages/transactions/list.php");
    exit;
}
?>
