<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Content-Type: application/json;");
    include_once '../../DAL/database.php';
    include_once '../../MODEL/payment_method.php';

    $database = new Database();
    $conn = $database->getConnection();
    $paymentObj = new Payment($conn);

    $param = file_get_contents('php://input');
    $payment = json_decode($param);
    $id = $payment->id;
    echo json_encode($paymentObj->readById($id));
?>