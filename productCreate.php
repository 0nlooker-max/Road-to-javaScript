<?php 
include('dbconnect.php');

$query = "insert into tablescript(Product_name) values('".$_GET['pname']."')";
$statement = $connection->prepare($query);
$res = $statement->execute();

if($res){
    echo json_encode(["res" => "success"]);
}else{
    echo json_encode(["res" => "error", "msg" => "Product not added"]);
}
?>