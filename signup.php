<?php
    include("connection.php");

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO prilimtable (name, email, password) VALUES (?, ?, ?)";
    $statement = $connection->prepare($query);
    $res = $statement->execute([$name, $email, $password]);

    if($res){
        echo json_encode(["res" => "success"]);
    }else{
        echo json_encode(["res" => "error", "msg" => "Student not added"]);
    }




?>