<?php
    include_once 'database.php';
    include_once 'user.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new User($db);
    $data = json_decode(file_get_contents("php://input"));
    $item->name = $data->name;
    $item->age = $data->age;
    
    if($item->create()){
        echo json_encode('Created');
    } else{
        echo json_encode('Error');
    }
?>