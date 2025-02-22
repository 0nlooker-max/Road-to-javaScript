<?php

include("dbconnect.php");

$query = "delete from tablescript where product_id='".$_GET['pid']."'";
$statement = $connection->prepare($query);
$res = $statement->execute();

if($res){
    echo json_encode(["res" => "success"]);
}else{
    echo json_encode(["res" => "error", "msg" => "Product not deleted"]);
}