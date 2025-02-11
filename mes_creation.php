<?php
session_start();
include 'db.php';
include 'user.php';

$dsn = 'mysql:host=localhost;dbname=quizznight';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

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
    foreach ($quizzes as $quizData) {
        $quiz = new Quiz($pdo, $quizData['id'], $quizData['title'], $quizData['description'], $quizData['created_by']);
        $quiz->displayQuiz();
    }
    ?>
</div>