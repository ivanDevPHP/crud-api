<?php
    require __DIR__ . '/vendor/autoload.php';
    
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Token {
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
            
            return json_encode(["success" => "success"]);
            
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