<?php
include 'db.php';

$dsn = 'mysql:host=localhost;dbname=quizznight';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

// Récupérer les quiz de la base de données
$query = "SELECT * FROM quizz";
$stmt = $pdo->query($query);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Quizouille</title>
</head>
<header class="header-index2">
<a href="logout.php"><p class="index2-header">Déconnection</p></a>
<a href="index2.php"><p class="index2-header">Acceuil</p></a>
<a href="mes_creation.php"><p class="index2-header">Mes créations</p></a>
<a href="creation.php"><p class="index2-header">Créer</p></a>
</header>
<body>
<div class="titre">
    <h1>Quizouille</h1>
    <p class="p-titre">Bienvenue sur votre espace personnel de Quizouille !</p>
</div>
    <!-- Affichage des quizs -->
    <div class="container">        
        <h1>Choisissez un Quiz</h1>
            
        <?php foreach ($quizzes as $quiz): ?>
            <div class="quiz-card">
                <img src="<?php echo htmlspecialchars($quiz['url_image']); ?>" alt="Quiz Image" class="quiz-image">
                <h2 class="quiz-title"><?php echo htmlspecialchars($quiz['title']); ?></h2>
                <p class="quiz-description"><?php echo htmlspecialchars($quiz['description']); ?></p>
                <a href="quiz.php?id=<?php echo $quiz['id']; ?>" class="quiz-link">Commencer le Quiz</a>
            </div>
        <?php endforeach; ?>
     </div>
</body>
<footer class="register-footer">
    <p>2025 Quizouille - Tous droits réservés.</p>
</footer>
</html>