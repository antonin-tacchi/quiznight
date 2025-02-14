<?php
session_start();
include 'db.php';
include 'user.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour voir vos quiz.";
    exit;
}

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté
$user = new User($pdo, $user_id);

// Récupérer les quiz créés par l'utilisateur
$quizzes = $user->getCreatedQuizzes();

?>
<link rel="stylesheet" href="style.css">
<div class="container">
    <h1>Choisissez un Quiz</h1>
    
    <?php
    // Afficher les quiz créés par l'utilisateur
    foreach ($quizzes as $quizData) {
        // Créer un objet Quiz pour chaque quiz
        $quiz = new Quiz($pdo, $quizData['id'], $quizData['title'], $quizData['description'], $quizData['created_by'], $quizData['url_image']);
        // Afficher le quiz
        $quiz->displayQuiz();
    }
    ?>
</div>
