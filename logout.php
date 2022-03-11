<?php
    require __DIR__ . '/vendor/autoload.php';
    
    use \Firebase\JWT\JWT;
    include_once 'database.php';
    include_once 'user.php';
    include_once 'token.php';

    $database = new Database();
    $db = $database->getConnection();
    $item = new User($db);
    $jwt = new Token($db);
    
    $token = $jwt->destroy();     
    $token = json_decode($token);

    if(isset($token->error)){
        echo json_encode("Error: ".$token->msg);
        die();
    }else{
        echo json_encode("You logout!");
        die();
    }
?>