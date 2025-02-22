<?php 
include('dbconnect.php');

if (!isset($_GET['pname']) || empty($_GET['pname'])) {
    echo json_encode(["res" => "error", "msg" => "Product name is required"]);
    exit();
}

$query = "INSERT INTO tablescript (Product_name) VALUES (:pname)";
$statement = $connection->prepare($query);
$res = $statement->execute([':pname' => $_GET['pname']]);

if ($res) {
    echo json_encode(["res" => "success"]);
} else {
    echo json_encode(["res" => "error", "msg" => "Database insertion failed"]);
}
?>
