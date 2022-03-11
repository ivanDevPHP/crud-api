<?php
    require __DIR__ . '/vendor/autoload.php';
    
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Token {
        private $conn;
        private $db_table = "token";
        public $id;
        public $token;
        public $valid;
        public function __construct($db){
            $this->conn = $db;
        }
        function generateToken($name){
            $secretKey  = '123';
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+1 hour')->getTimestamp();  //tpken wil expire in a hour
            $serverName = "localhost";
            $username   = $name;                                           
    
            $data = [
                'iat'  => $issuedAt->getTimestamp(),         
                'iss'  => $serverName,                       
                'nbf'  => $issuedAt->getTimestamp(),         
                'exp'  => $expire,                           
                'userName' => $username,                     
            ];
    
            $jwt = JWT::encode(
                $data,
                $secretKey,
                'HS512'
            );
    
            return $jwt;
        }

        function validation(){
            $jwt = $this->getToken();
            if($jwt == null){
                return json_encode(["error" => "error", "msg" => "no token"]);
            }

            $decode = $this->getTokenDecode($jwt);
            $decode = json_decode($decode);
            if(isset($decode->error)){
                return json_encode(["error" => "error", "msg" => $decode->msg]);
            }

            //check if the token is valid or not
            $blacklist = $this->blacklist($jwt);

            if($blacklist){
                return json_encode(["error" => "error", "msg" => "token  invalid"]);
            }
            
            return json_encode(["success" => "success"]);
            
        }

        function destroy(){
            $jwt = $this->getToken();
            if($jwt == null){
                return json_encode(["error" => "error", "msg" => "no token"]);
            }

            $jwt = explode("Bearer ", $jwt);
            $newToken= $jwt[1];

            $sqlQuery = "UPDATE public.token SET valid=0 WHERE token='$newToken'";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();

            return json_encode(["success" => "success"]);
        }

        //check if the token is valid or not
        function blacklist($token){
            $token = explode("Bearer ", $token);
            $newToken= $token[1];

            $sqlQuery = "SELECT * FROM public.token WHERE token='$newToken';";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($dataRow['id'])){

                if($dataRow['valid']){
                    return false;
                }else{
                    return true;
                }

                
            }else{
                return false;
            }
        }
        
        function getToken(){
            $headers = null;
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
            return $headers;
        }

        function saveToken($token){
            $sqlQuery = "INSERT INTO public.token (token, valid) VALUES ('$token', 1);";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
        }
        
        function getTokenDecode($headers){
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $key  = '123';
                try{
                    $token = JWT::decode($matches[1], new Key($key, 'HS512'));
                }catch(Exception $e){
                    if($e->getMessage() == "Expired token"){
                        return json_encode(["error" => "error", "msg" => "Expired token"]);
                    }
                    return json_encode(["error" => "error", "msg" => "error"]);
                }
                
                return json_encode(["success" => "success"]);
            }
        }
    }  
?>