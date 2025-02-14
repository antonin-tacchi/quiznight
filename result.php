<?php
session_start();

// Vérifier que les résultats sont disponibles
if (!isset($_SESSION['score']) || !isset($_SESSION['total_questions'])) {
    die("Aucun résultat disponible.");
}

$score = $_SESSION['score'];
$total = $_SESSION['total_questions'];

// Réinitialiser la session pour la prochaine partie
unset($_SESSION['score'], $_SESSION['total_questions'], $_SESSION['current_question']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Résultats</title>
</head>
<header class="header-index2">
<a href="logout.php"><p class="index2-header">Déconnection</p></a>
<a href="index2.php"><p class="index2-header">Acceuil</p></a>
<a href="mes_creation.php"><p class="index2-header">Mes créations</p></a>
<a href="creation.php"><p class="index2-header">Créer</p></a>
</header>
<body>
    <div class="container-result">
        <h1 class="h1-result">Résultats du quiz</h1>
        <p class="p-result">Votre score : <?= $score ?> / <?= $total ?></p>
        <a href="index2.php" class="a-result">Retour à l'accueil</a>
    </div>
</body>
</html>
