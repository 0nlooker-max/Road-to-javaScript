<?php
include("dbconnect.php");

$query = "update tablescript set Product_name='".$_GET['pname']."' where product_id='".$_GET['pid']."'";
$statement = $connection->prepare($query);
$res = $statement->execute();

if($res){
    echo json_encode(["res" => "success"]);
}else{
    echo json_encode(["res" => "error", "msg" => "Product not added"]);
}
?>