<?php
    $conn;

    try{
        $conn = new PDO("mysql:host=;dbname=", "username", "password");
    }catch(PDOException $e){
        echo $e->getMessage();
    }
?>