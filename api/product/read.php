<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Content-Type: application/json;");
    include_once '../../DAL/database.php';
    include_once '../../MODEL/product.php';

    $database = new Database();
    $conn = $database->getConnection();
    $products = new Product($conn);
    
    print_r( json_encode($products->read()));
?>