<?php
    include_once 'database.php';
    include_once 'user.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new User($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id = $data->id;
    
    if($item->getAge()){
        echo json_encode($item->getAge());
    }else{
        echo json_encode("erro");
    }
    
    
?>