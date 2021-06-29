<?php 
    class Product{
        private $conn;
        public $table_name = 'product';
        
        public $id;
        public $name;
        public $price;

        function __construct($db){
            $this->conn = $db;          //open connection to database
        }

        function read(){
            $sql = 'SELECT * FROM '. $this->table_name . ';';         //get products from database
            $pQuery = $this->conn->prepare($sql);
            
            $pQuery->execute();
            return $pQuery->fetchAll();
        }

        function readById($id){
            $sql = 'SELECT * FROM '. $this->table_name .' WHERE id = :id;';
            $pQuery = $this->conn->prepare($sql);
            $pQuery->bindParam(':id', $id);
            $pQuery->execute();

            return $pQuery->fetchAll()[0];
        }

        function create($product){
            $sql = 'INSERT INTO '. $this->table_name .' (name, price) VALUES (:name, :price);';
            $pQuery = $this->conn->prepare($sql);
            $pQuery->bindParam(':name', $product->name);
            $pQuery->bindParam('price', $product->price);
            $pQuery->execute();

            return $this->conn->lastInsertId();
        }
    }
?>