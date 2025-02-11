<?php
include 'db.php';

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
    <title>Quizouille</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
<a href="login.php"><p>Connection / Inscription</p></a>
<a href="index.php"><p>Acceuil</p></a>
</header>
<body>
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
</body>
</html>


