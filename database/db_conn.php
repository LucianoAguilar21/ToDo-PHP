<?php 

$sName = "localhost";
$uName = "username";
$password="password";
$dbName = "to-do-list-php";



try{
    $conn = new PDO("mysql:host=$sName; dbname=$dbName",$uName,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

   
}catch(PDOException $e){

    echo "Connection failed: ".$e->getMessage();

}