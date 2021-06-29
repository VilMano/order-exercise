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
            try{
                $sql = 'INSERT INTO '. $this->table_name .' (name, price) VALUES (:name, :price);';
                $pQuery = $this->conn->prepare($sql);
                $pQuery->bindParam(':name', $product->name);
                $pQuery->bindParam('price', $product->price);
                $pQuery->execute();
    
                return $this->conn->lastInsertId();
            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }

        function update($product){
            try{
                $sql = 'UPDATE '. $this->table_name .' SET name = :name, price = :price WHERE id = :id;';
                $pQuery = $this->conn->prepare($sql);
                $pQuery->bindParam(':name', $product->name);
                $pQuery->bindParam(':price', $product->price);
                $pQuery->bindParam(':id', $product->id);

                $pQuery->execute();
                return $product->id;
            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }

        function delete($id){
            try{
                $sql = 'DELETE FROM '. $this->table_name .' WHERE id = :id;';
                $pQuery = $this->conn->prepare($sql);
                $pQuery->bindParam(':id', $id);
    
                $pQuery->execute();
                return $id;

            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
    }
?>