<?php
    class Database {
        private const DB_host = "localhost";
        private const DB_user = "user";
        private const DB_pass = "auto52";
        private const DB_name = "diploma";

        private $conn;
        private $stmt;
        private $result;

        function __construct($sql_req){
            try{
                $this->conn = new PDO("mysql:host=" .self::DB_host. ";dbname=" .self::DB_name, self::DB_user, self::DB_pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $this->stmt = $this->conn->prepare($sql_req);
            } catch (PDOException $e) {
                $this->conn = null;
                throw new Exception("Database connection error: " . $e->getMessage());
            }
        }

        function bind_all_Param($data) { //Data associative array
            try{
                $keys = array_keys($data);

                foreach($keys as $key){
                    if($data[$key] != "null" && $data[$key] != "")
                        $this->stmt->bindParam($key, $data[$key]);
                    else{
                        throw new Exception("Null value or empty value $key");
                    }
                }

            } catch(PDOException $e) {
                $this->conn = null;
                throw new Exception("Database bind_all_Param error: " . $e->getMessage());
            }
        }

        function bind_one_Param($value, $value_name){
            try{
                $this->stmt->bindParam($value_name, $value);
            } catch(PDOException $e) {
                $this->conn = null;
                throw new Exception("Database bind_one_Param error: " . $e->getMessage());
            }
        }

        function execute() {
            try{
                $this->stmt->execute();
            } catch(PDOException $e){
                $this->conn = null;
                throw new Exception("Database execute error: " . $e->getMessage());
            }
        }

        function set_new_stmt($sql_req) {
            try{
                $this->stmt = $this->conn->prepare($sql_req);
            } catch(PDOException $e){
                $this->conn = null;
                throw new Exception("Database set_new_stmt error: " . $e->getMessage());
            }
        }

        function get_one_result() {
            try{
                return $this->stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                $this->conn = null;
                throw new Exception("Database execute error: " . $e->getMessage());
            }
        }

        function __destruct(){
            $this->conn = null;
            $this->stmt = null;
        }
    }
?>