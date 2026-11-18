<?php
$host = 'localhost';
$dbname = 'db425';
$username = 'root';
$password = '';

try{
    $connect = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;",$username,$password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}catch(PDOException $error){
    die('Проблема с подключением БД: ' . $error->getMessage());
}
?>