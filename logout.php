<?php
include 'user.php';
include_once 'db.php';

$dsn = 'mysql:host=localhost;dbname=quizznight';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

$user = new User($pdo);
$message = $user->logout();
echo $message;
header('Location: index.php');
exit;
?>