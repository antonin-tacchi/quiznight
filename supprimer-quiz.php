<?php
include 'db.php';

// Vérifiez si l'ID du quiz a été envoyé
if (isset($_POST['quiz_id'])) {
    $quiz_id = $_POST['quiz_id'];

    // Suppression des questions liées au quiz
    $query = "DELETE FROM questions WHERE quiz_id = :quiz_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();

    // Suppression du quiz
    $query = "DELETE FROM quizz WHERE id = :quiz_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();

    // Redirigez l'utilisateur vers la page d'accueil ou une autre page
    header("Location: affichage.php");
    exit();
} else {
    // Si aucun quiz ID n'est fourni, redirigez vers la page d'accueil
    header("Location: affichage.php");
    exit();
}
?>
