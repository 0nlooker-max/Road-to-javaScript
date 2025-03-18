<?php 
    include("connection.php");
    
    try{
        $query = "SELECT * FROM prilimtable LIMIT 10";
        $statement = $connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }catch(PDOException $th){
        echo json_encode(['error' => $th->getMessage()]);

    }
?>