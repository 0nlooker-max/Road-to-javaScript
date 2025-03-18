<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "javascript_try";

try{
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $th){
    die(json_encode(['error' => "Database Connection failed".$th->getMessage()] ));
}