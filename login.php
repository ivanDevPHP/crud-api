<?php
    require __DIR__ . '/vendor/autoload.php';
    
    use \Firebase\JWT\JWT;
    include_once 'database.php';
    include_once 'user.php';
    include_once 'token.php';

    $database = new Database();
    $db = $database->getConnection();
    $item = new User($db);
    $token = new Token();
    
    $data = json_decode(file_get_contents("php://input"));
    $item->name = $data->name;
    $item->password = $data->password;

    if($item->login()){
        $token = $token->generateToken($item->name);
        if($token){
            echo json_encode($token);
        }else{
            echo json_encode("ERROR");
        };
    }else{
        echo json_encode("Login Invalid");
    };

    
      
?>