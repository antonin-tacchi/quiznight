<?php
include 'user.php';
include_once 'db.php';

$dsn = 'mysql:host=localhost;dbname=quizznight';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

$message = '';  // Variable pour afficher le message d'erreur

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($pdo);
    $message = $user->register($username, $password);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<header>
<a href="index.php"><p>Acceuil</p></a>
</header>
<body>
<div class="form-connection">
    <h2>Inscription</h2>
    <form method="POST">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" autocomplete="off" required><br><br>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" autocomplete="off" required><br><br>

        <button type="submit">S'inscrire</button>
        <!-- Affichage du message d'erreur sous le bouton -->
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <p>Tu veux te connecter maintenant ? <a href="login.php">c'est ici !</a></p>
        
        
    </form>
</div>
</body>
<footer class="register-footer">
    <p>2025 Quizouille - Tous droits réservés.</p>
</footer>
</html>
