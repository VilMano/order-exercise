<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Content-Type: application/json;");
    include_once '../../DAL/database.php';
    include_once '../../MODEL/payment_method.php';

    $database = new Database();
    $conn = $database->getConnection();
    $payments = new Payment($conn);

    $param = file_get_contents('php://input');
    $objPayment = json_decode($param);

    echo $payments->create($objPayment->name, $objPayment->discount);
?>