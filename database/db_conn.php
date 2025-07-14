<?php
    $conn;

    try{
        $conn = new PDO("mysql:host=;dbname=;charset=utf8", "username", "password");
    }catch(PDOException $e){
        echo $e->getMessage();
    }
?>
