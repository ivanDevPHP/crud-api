<?php
    include_once 'database.php';
    include_once 'user.php';
    include_once 'token.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $item = new User($db);
    $token = new Token($db);

    $token = $token->validation();
    $token = json_decode($token);

    if(isset($token->error)){
        echo json_encode("Error: ".$token->msg);
        die();
    }
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id = $data->id;
    
    if($item->delete()){
        echo json_encode("Deleted.");
    } else{
        echo json_encode("Error");
    }
?>