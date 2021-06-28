<?php 
    class Payment{
        private $conn;
        private $table_name = 'payment_method';

        public $id;
        public $name;
        public $discount;
        
        function __construct($db)
        {
            $this->conn = $db;
        }

        function read(){
            $sql = 'SELECT * FROM payment_method';
            $pQuery = $this->conn->prepare($sql);
            $pQuery->execute();
            return $pQuery;
        }

        function readById($id){
            $sql = 'SELECT * FROM payment_method WHERE id = :id;';
            $pQuery = $this->conn->prepare($sql);
            $pQuery->bindParam(':id', $id);

            $pQuery->execute();
            return $pQuery->fetchAll()[0];
        }

        function create($name, $discount){
            try{
                $sql = 'INSERT INTO payment_method (name, discount) VALUES (:name, :discount);';
                $pQuery = $this->conn->prepare($sql);
                $pQuery->bindParam(':name', $name);
                $pQuery->bindParam(':discount', $discount);
    
                $pQuery->execute();

                return $this->conn->lastInsertId();         //return the id of the payment method just created
            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }

    }
?>