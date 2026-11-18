<?php
$uid = $_SESSION['uid'] ?? '';

if ($uid){
    $sql = "SELECT * FROM users WHERE id = $uid";
    $USER = $connect->query($sql)->fetch();
}

if(isset($_GET['exit'])){
    unset($_SESSION['uid']);
    session_destroy();
    header('Location: /');
}
?>