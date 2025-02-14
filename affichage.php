<?php
session_start(); // Démarre la session
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']) || !isset($_SESSION['user'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']; // Récupérer l'ID de l'utilisateur connecté

// Vérifier si le nom d'utilisateur est défini dans la session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Utilisateur inconnu'; // Valeur par défaut si non définie

// Connexion à la base de données
include 'db.php';

// Requête pour récupérer les quiz créés par l'utilisateur connecté
$sql = "SELECT * FROM quizz WHERE created_by = :user";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user', $userId, PDO::PARAM_INT);
$stmt->execute();

// Récupération des résultats
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Quiz</title>
    <link rel="stylesheet" href="style.css"> <!-- Ajoutez votre fichier CSS pour le style -->
</head>
<header class="header-index2">
<a href="logout.php"><p class="index2-header">Déconnection</p></a>
<a href="index2.php"><p class="index2-header">Acceuil</p></a>
<a href="affichage.php"><p class="index2-header">Mes créations</p></a>
<a href="creation.php"><p class="index2-header">Créer</p></a>
</header>
<body>
    <h1>Voici vos Quiz </h1>
<div class="affichage-container">
    <?php if (count($quizzes) > 0): ?>
    <ul>
        <?php foreach ($quizzes as $quiz): ?>
            <li>
                <div class="affichage-item">
                    <img src="<?php echo htmlspecialchars($quiz['url_image']); ?>" alt="Image du quiz" style="width: 200px;" class="affichage-img">
                    <h2 class="affichage-title"><?php echo htmlspecialchars($quiz['title']); ?></h2>
                    <p class="affichage-description"><?php echo htmlspecialchars($quiz['description']); ?></p>

                    <!-- Formulaire de suppression -->
                    <div class="bouton-supr">
                        <form action="supprimer-quiz.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz et toutes ses questions ?');">
                            <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
                            <button type="submit" class="delete-button">Supprimer</button>
                        </form>
                    </div>
                 </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        <p>Aucun quiz créé par cet utilisateur.</p>
    <?php endif; ?>
</div>

</body>
<footer class="affichage-footer">
    <p>2025 Quizouille - Tous droits réservés.</p>
</footer>
</html>
