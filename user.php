<?php
    class User{
        // Connection
        private $conn;
        // Table
        private $db_table = "user";
        // Columns
        public $id;
        public $name;
        public $age;
        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        // CREATE
        public function create(){
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->age=htmlspecialchars(strip_tags($this->age));
            
            $sqlQuery = "INSERT INTO public.user (name,age) VALUES ('$this->name', $this->age);";
            $stmt = $this->conn->prepare($sqlQuery);

            
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        
        // DELETE
        function delete(){
            $this->id=htmlspecialchars(strip_tags($this->id));
            $sqlQuery = "DELETE FROM public.user WHERE id=$this->id;";
            $stmt = $this->conn->prepare($sqlQuery);
            
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        // GET AGE
        function getAge(){
            $this->id=htmlspecialchars(strip_tags($this->id));
            $sqlQuery = "SELECT id, age FROM public.user WHERE id=$this->id;";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            
            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(isset($dataRow['id'])){
                $user = new stdClass;
                $user->id = $dataRow['id'];
                $user->age = $dataRow['age'];
            
                return $user;
            }else{
                return false;
            }
            
        }
    }
?>