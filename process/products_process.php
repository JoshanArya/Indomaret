<?php
define ('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];
    $product_name = $_POST['product_name'];
    $voucher = (isset($_POST['voucher_id']) && $_POST['voucher_id'] !== '') ? $_POST['voucher_id'] : null;
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);

    if ($action == 'add') {
        $voucher_sql = is_null($voucher) ? 'NULL' : "'" . mysqli_real_escape_string($conn, $voucher) . "'";
        $query = "INSERT INTO tb_products VALUES (NULL, '$product_name', $price, $stock, $voucher_sql)";
        mysqli_query($conn, $query);
    } elseif ($action == 'edit') {
        $voucher_sql = is_null($voucher) ? 'NULL' : "'" . mysqli_real_escape_string($conn, $voucher) . "'";
        $query = "UPDATE tb_products SET product_name='$product_name', price=$price, stock=$stock, voucher_id=$voucher_sql WHERE id='$id'";
        mysqli_query($conn, $query);

    } elseif ($action == 'delete') {
        $query = "DELETE FROM tb_products WHERE id='$id'";
        mysqli_query($conn, $query);
    }

    // Redirect to the list page after any action
    header("Location: ../pages/products/list.php");
    exit;
}
