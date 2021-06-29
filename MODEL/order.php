<?php 
    include_once 'product.php';
    include_once 'payment_method.php';

    class Order{
        private $conn;
        public $table_name = '`order`';
        
        public $id;
        public $total_price;

        function __construct($db){
            $this->conn = $db;          //open connection to database
        }

        function read(){
            $sql = 'SELECT `order`.id as ID, `order`.total_price as TOTAL, order_item.product_id as PRODUCT, order_item.quantity as QUANTITY
             FROM '. $this->table_name .' LEFT JOIN order_item ON `order`.id = order_item.order_id';
            $pQuery = $this->conn->prepare($sql);
            
            $pQuery->execute();
            $ordersArr = $pQuery->fetchAll();
            $orders = array();
            foreach($ordersArr as $order){
                $product = array("product_id" => $order["PRODUCT"], "quantity" => $order["QUANTITY"]);
                if(!array_key_exists($order["ID"], $orders)){
                    $orders[$order["ID"]] = array("total" => $order["TOTAL"]);    
                }


                array_push($orders[$order["ID"]], $product);
            }            

            return $orders;
        }

        function readById($id){
            $sql = 'SELECT * FROM '. $this->table_name .' WHERE id = :id;';
            $pQuery = $this->conn->prepare($sql);
            $pQuery->bindParam(':id', $id);
            $pQuery->execute();

            return $pQuery->fetchAll()[0];
        }



        function create($order){
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try{
                $order_id = $this->generateIdOrder();
                $payment = new Payment($this->conn);
                $payment_method = $payment->readById($order->payment_method);

                $sql = 'INSERT INTO '. $this->table_name .' (id, payment_id, created_date, total_price) VALUES (:order_id, :payment_method, NOW(), :total_price);';

                $total_price = 0;
                foreach($order->products as $product){
                    $prodConn = new Product($this->conn);
                    $prod = $prodConn->readById($product->product_id);
                    $total_price = $total_price + ($prod["price"] * $product->quantity );
                }

                $total_price = $total_price - ($total_price * $payment_method["discount"] / 100);
                $pQuery = $this->conn->prepare($sql);
                $pQuery->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $pQuery->bindParam(':payment_method', $payment_method["id"], PDO::PARAM_INT);
                $pQuery->bindParam(':total_price', $total_price);
                $pQuery->execute();

                $order_inserted_id = $order_id;           //in order to get the id from the order just inserted

                if(!$pQuery){
                    return $this->conn->errorInfo();
                }

                foreach($order->products as $product){
                    $query = 'INSERT INTO order_item (product_id, order_id, quantity) VALUES (:product_id, :order_id, :quantity);';
                    $prepQuery = $this->conn->prepare($query);
                    $prepQuery->bindParam(':product_id', $product->product_id, PDO::PARAM_INT);
                    $prepQuery->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                    $prepQuery->bindParam(':quantity', $product->quantity, PDO::PARAM_INT);
                    $prepQuery->execute();
                }

                $result = $order_inserted_id;

                switch($payment_method["id"]){         //add more options depending on the payment method
                    case(2): 
                        $result = "Email sent";
                        break;
                }

                return $result;


            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }

        function generateIdOrder(){
            $results = $this->conn->prepare('SELECT id FROM '. $this->table_name .' order by id desc LIMIT 1;');
            $results->execute();
            // fetch the ID of the last order
            if(!$results){
                return 1;
            }else{
                return intval($results->fetch()[0])+1;
            }
        }
    }
?>