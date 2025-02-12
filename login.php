<?php
include 'user.php';
include_once 'db.php';

$dsn = 'mysql:host=localhost;dbname=quizznight';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($pdo);
    $message = $user->login($username, $password);
    echo $message;
    header('Location: index2.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>
<header>
<a href="index.php"><p class="Acceuil">Acceuil</p></a>
</header>
<body>
    <div class="form-connection">
    <h2>Connexion</h2>
    <form method="POST">
        <label for="username"><p>Nom d'utilisateur</p></label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password"><p>Mot de passe</p></label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit"><p>Se connecter</p></button>
    </form>
    <p>Pas encore de compte ? <a href="register.php">Créers en un !</a></p>
    </div>
</body>
<footer class="login-footer">
    <p>2025 Quizouille - Tous droits réservés.</p>
</footer>   
</html>