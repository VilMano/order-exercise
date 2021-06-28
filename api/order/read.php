<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Content-Type: application/json;");
    include_once '../../DAL/database.php';
    include_once '../../MODEL/order.php';

    $database = new Database();
    $conn = $database->getConnection();
    $orders = new Order($conn);

    echo json_encode($orders->read());
?>