<?php 
    class Database{
        private $host = '127.0.0.1';
        private $db_name = 'orders';
        private $username = 'user';
        private $password = 'password';
        public $conn;

        public function getConnection(){
            $this->conn = null;

            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }

            return $this->conn;
        }
    }
?>