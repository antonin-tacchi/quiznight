<?php
session_start();
include 'db.php';

// Vérifier que la réponse a été envoyée
if (!isset($_POST['reponse'])) {
    die("Aucune réponse sélectionnée.");
}

// Récupérer la réponse
$reponse_id = (int) $_POST['reponse'];

// Vérifier si la réponse est correcte
$stmt = $pdo->prepare("SELECT is_correct FROM reponses WHERE id = ?");
$stmt->execute([$reponse_id]);
$is_correct = $stmt->fetchColumn();

if ($is_correct) {
    $_SESSION['score']++; // Ajouter au score si la réponse est correcte
}

// Passer à la question suivante
$_SESSION['current_question']++;

// Récupérer le nombre total de questions
$stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE quiz_id = ?");
$stmt->execute([$_SESSION['quiz_id']]);
$total_questions = $stmt->fetchColumn();

// Si toutes les questions ont été répondues, afficher le score final
if ($_SESSION['current_question'] >= $total_questions) {
    header("Location: result.php");
    exit();
} else {
    header("Location: quiz.php?id=" . $_SESSION['quiz_id']);
    exit();
}
?>
    