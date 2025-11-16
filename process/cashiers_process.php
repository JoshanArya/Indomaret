<?php
define ('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];
    $name = $_POST['cashier_name'];

    if ($action == 'add') {
        $query = "INSERT INTO tb_cashiers (cashier_name) VALUES ('$name')";
        mysqli_query($conn, $query);
    } elseif ($action == 'edit') {
        $query = "UPDATE tb_cashiers SET cashier_name='$name' WHERE id='$id'";
        mysqli_query($conn, $query);
    } elseif ($action == 'delete') {
        $query = "DELETE FROM tb_cashiers WHERE id='$id'";
        mysqli_query($conn, $query);
    }

    // Redirect to the cashiers list page after any action
    header("Location: ../pages/cashiers/list.php");
    exit;
}
