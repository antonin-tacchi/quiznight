<?php
session_start();

// Connexion à la base de données et initialisation des classes
require_once 'Database.php';
require_once 'Quizbis.php';
require_once 'Questionbis.php';

// Vérifie si l'utilisateur est connecté et récupère son ID
if (!isset($_SESSION['user'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user']; // Récupère l'ID de l'utilisateur connecté

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser les classes pour récupérer les quiz et les questions
$quizbis = new Quizbis($db);
$questionbis = new Questionbis($db);

// Si un quiz est sélectionné pour modification
if (isset($_GET['quiz_id']) && !empty($_GET['quiz_id'])) {
    $quizId = $_GET['quiz_id'];
    $quiz = $quizbis->getQuiz($quizId, $userId); // Récupérer les détails du quiz sélectionné

    // Si le quiz n'appartient pas à l'utilisateur, rediriger vers la page principale
    if (!$quiz) {
        header("Location: modification.php");
        exit();
    }

    $questions = $questionbis->getQuestionsByQuiz($quizId); // Récupérer les questions du quiz

    // Si un formulaire de modification de quiz est soumis
    if (isset($_POST['modify_quiz'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $urlImage = $_POST['url_image'];

        // Modifier le quiz dans la base de données
        $quizbis->modifyQuiz($quizId, $title, $description, $urlImage);

        // Redirection pour éviter de soumettre à nouveau le formulaire après actualisation de la page
        header("Location: modification.php?quiz_id=$quizId");
        exit();
    }

    // Si un formulaire de suppression de question est soumis
    if (isset($_POST['delete_question'])) {
        $questionId = $_POST['question_id'];
        $questionbis->deleteQuestion($questionId);
        header("Location: modification.php?quiz_id=$quizId"); // Redirection pour éviter de soumettre à nouveau le formulaire
        exit();
    }

    // Si un formulaire pour ajouter une question est soumis
    if (isset($_POST['add_question'])) {
        $questionText = $_POST['question_text'];
        $correctAnswer = $_POST['correct_answer']; // Réponse correcte

        // Ajouter la question
        $questionbis->addQuestion($quizId, $questionText, $correctAnswer == 'Vrai' ? 'true' : 'false'); // Type 'true' ou 'false' pour la question Vrai/Faux

        header("Location: modification.php?quiz_id=$quizId"); // Redirection pour éviter de soumettre à nouveau le formulaire
        exit();
    }
}

// Si aucun quiz n'est sélectionné, afficher la liste des quiz existants
if (!isset($quizId)) {
    $quizzes = $quizbis->getQuizzesByUser($userId); // Récupère les quiz de l'utilisateur
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gestion des Quiz</title>
</head>
<header class="header-index2">
<a href="logout.php"><p class="index2-header">Déconnection</p></a>
<a href="index2.php"><p class="index2-header">Acceuil</p></a>
<a href="affichage.php"><p class="index2-header">Mes créations</p></a>
<a href="creation.php"><p class="index2-header">Créer</p></a>
</header>
<body>

<h1>Gestion des Quiz</h1>

<?php if (!isset($quizId)): ?>
    <div class="selection-modif-quiz">
        <!-- Formulaire pour sélectionner un quiz -->
        <h2 class="h2-modif">Sélectionner un quiz à modifier :</h2>
        <form action="modification.php" method="GET" class="form-modif">
            <select name="quiz_id" required>
                <option value="">Sélectionnez un quiz</option>
                <?php foreach ($quizzes as $quiz): ?>
                    <option value="<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['title']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bouton-modif">Modifier</button>
        </form>
    </div>
<?php else: ?>
<div class="container-mofi">
    <div class="modif-quiz">
    <!-- Formulaire pour modifier un quiz -->
    <h2 class="h2-modif-quiz">Modifier le Quiz : <?= htmlspecialchars($quiz['title']) ?></h2>
    <form method="POST" class="form-modif">
        <label for="title" class="label-form-modif">Titre :</label>
        <input type="text" name="title" value="<?= htmlspecialchars($quiz['title']) ?>" required><br>

        <label for="description" class="label-form-modif">Description :</label>
        <textarea name="description" required><?= htmlspecialchars($quiz['description']) ?></textarea><br>

        <label for="url_image" class="label-form-modif">URL de l'image :</label>
        <input type="text" name="url_image" value="<?= htmlspecialchars($quiz['url_image']) ?>"><br>

        <button type="submit" name="modify_quiz" class="bouton-modif-quiz-2">Modifier le quiz</button>
    </form>
    </div>
    <div class="modif-question">
    <h2>Liste des questions</h2>
    <ul>
        <?php foreach ($questions as $q): ?>
            <li>
                <div class="text-supr-modif"><?= htmlspecialchars($q['text']) ?> (Type: <?= htmlspecialchars($q['question_type']) ?>)</div>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="question_id" value="<?= $q['id'] ?>" class="input-quiz-modif">
                    <button type="submit" name="delete_question">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>
    <div class="add-question">
    <h2 class="h2-question">Ajouter une question</h2>
    <form method="POST" class="form-question">
        <label for="question_text">Texte de la question :</label>
        <input type="text" name="question_text" autocomplete="off" required class="input-add-question-modif"><br>

        <label for="correct_answer" class="label-question">Réponse correcte :</label>
        <select name="correct_answer" required>
            <option value="Vrai">Vrai</option>
            <option value="Faux">Faux</option>
        </select><br>

        <button type="submit" name="add_question" class="bouton-question-modif">Ajouter la question</button>
    </form>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
