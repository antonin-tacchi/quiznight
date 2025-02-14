<?php
include 'db.php';  // Connexion à la base de données

session_start();

// Vérifier si un quiz_id est passé dans l'URL
$quizId = isset($_GET['id']) ? $_GET['id'] : 0;

if ($quizId == 0) {
    die("Quiz non trouvé.");
}

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=quizznight';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

// Récupérer les questions du quiz
$query = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['quiz_id' => $quizId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si la session de score n'existe pas, l'initialiser
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

// Si la session de la question courante n'existe pas, l'initialiser
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
}

// Si la session du nombre total de questions n'existe pas, l'initialiser
if (!isset($_SESSION['total_questions'])) {
    $_SESSION['total_questions'] = count($questions);
}

// Gérer la soumission du formulaire pour chaque question
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = isset($_POST['answer']) ? $_POST['answer'] : '';
    
    // Trouver la bonne réponse de la question actuelle
    $current_question = $_SESSION['current_question'];
    
    // Vérifier si la question actuelle existe dans les données récupérées
    if (isset($questions[$current_question])) {
        $current_question_data = $questions[$current_question];
        
        // La logique de comparaison ici dépendra du type de question (Vrai/Faux)
        if ($user_answer == 'true' && $current_question_data['question_type'] == 'true') {
            $_SESSION['score']++;
        } elseif ($user_answer == 'false' && $current_question_data['question_type'] == 'false') {
            $_SESSION['score']++;
        }
    }

    // Passer à la question suivante seulement si ce n'est pas la dernière
    $_SESSION['current_question']++;

    // Si toutes les questions sont répondues, rediriger vers la page des résultats
    if ($_SESSION['current_question'] >= $_SESSION['total_questions']) {
        header("Location: result.php");
        exit();
    }
}

// Récupérer la question actuelle
$current_question = $_SESSION['current_question'];

// Vérifier si la question existe
if (isset($questions[$current_question])) {
    $current_question_data = $questions[$current_question];
} else {
    // Si la question n'existe pas, rediriger vers la page de résultats
    header("Location: result.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Quiz - Question <?= $current_question + 1 ?></title>
</head>
<header class="header-index2">
<a href="logout.php"><p class="index2-header">Déconnection</p></a>
<a href="index2.php"><p class="index2-header">Acceuil</p></a>
<a href="mes_creation.php"><p class="index2-header">Mes créations</p></a>
<a href="creation.php"><p class="index2-header">Créer</p></a>
</header>
<body>
    <div class="container-jeu">
        <h1 class="h1-jeu">Question <?= $current_question + 1 ?> / <?= $_SESSION['total_questions'] ?></h1>
        <p class="p-jeu"><?= htmlspecialchars($current_question_data['text']) ?></p>

        <!-- Formulaire pour répondre à la question -->
        <form action="quiz-jeu.php?id=<?= $quizId ?>" method="POST">
            <button type="submit" name="answer" value="true" class="bouton-jeu">Vrai</button>
            <button type="submit" name="answer" value="false" class="bouton-jeu">Faux</button>
        </form>
    </div>
</body>
</html>
