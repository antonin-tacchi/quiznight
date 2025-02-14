<?php 
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
    header('Location: login.php');
    exit();
}

// Inclure la configuration et les classes
require_once 'Config.php';  // Pour la configuration de la base de données
require_once 'Quiz.php';    // Assurez-vous que ce chemin est correct pour Quiz.php
require_once 'Question.php'; // Assurez-vous que ce chemin est correct pour Question.php

// Initialiser les variables
$max_questions = 10;  // Nombre maximum de questions
$initial_questions = 3;  // Commence avec 3 questions
$current_question = isset($_POST['current_question']) ? (int)$_POST['current_question'] : $initial_questions;
$quiz_id = null;

// Récupérer les informations du formulaire du quiz, même après l'ajout d'une question
$quiz_title = isset($_POST['title']) ? $_POST['title'] : '';
$quiz_description = isset($_POST['description']) ? $_POST['description'] : '';
$quiz_image_url = isset($_POST['url_image']) ? $_POST['url_image'] : '';

// Si le formulaire a été soumis pour ajouter un quiz et le soumettre
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_quiz'])) {
    // Vérification si les informations sont présentes
    if (empty($quiz_title) || empty($quiz_description) || empty($quiz_image_url)) {
        // Afficher un message d'erreur mais continuer si des champs sont vides
        $error_message = "Veuillez remplir tous les champs du quiz.";
    }

    // Création du quiz
    $quiz = new Quiz($db);
    $quiz_id = $quiz->addQuiz($quiz_title, $quiz_description, $_SESSION['user'], $quiz_image_url);

    // Ajouter les questions une fois le quiz soumis
    for ($i = 1; $i <= $current_question; $i++) {
        if (isset($_POST["question_text_$i"]) && !empty($_POST["question_text_$i"])) {
            $question_text = $_POST["question_text_$i"];
            $question_type = $_POST["question_type_$i"];

            // Créer la question dans la base de données
            $question = new Question($db);
            $question->setQuizId($quiz_id); // Définir l'ID du quiz
            $question->setText($question_text);
            $question->setQuestionType($question_type);

            // Ajouter la question à la base de données
            $question->addQuestion($quiz_id, $question_text, $question_type);
        }
    }

    // Rediriger après avoir ajouté le quiz et les questions
    header("Location: creation.php");
    exit();
}

// Ajouter une nouvelle question, mais ne pas encore soumettre le quiz
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    // Juste incrémenter le nombre de questions, sans les soumettre encore
    if ($current_question < $max_questions) {
        $current_question++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<header class="header-index2">
<a href="logout.php"><p class="index2-header">Déconnection</p></a>
<a href="index2.php"><p class="index2-header">Acceuil</p></a>
<a href="affichage.php"><p class="index2-header">Mes créations</p></a>
<a href="creation.php"><p class="index2-header">Créer</p></a>
</header>
<body>
<div class="form-connection">
    <!-- Formulaire HTML -->
    <form method="post">
        <h2>Créer un Quiz</h2>
    
        <!-- Afficher le message d'erreur si nécessaire -->
        <?php if (isset($error_message)) { echo '<p style="color:red;">' . $error_message . '</p>'; } ?>
    
        <!-- Champs pour les informations du quiz -->
        <label for="title">Titre du Quiz :</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($quiz_title); ?>" required class="input-creation">
    
        <label for="description">Description du Quiz :</label>
        <textarea name="description" required class="input-creation"><?php echo htmlspecialchars($quiz_description); ?></textarea>
    
        <label for="url_image">URL de l'image :</label>
        <input type="text" name="url_image" value="<?php echo htmlspecialchars($quiz_image_url); ?>" required class="input-creation">
    
        <h3>Questions :</h3>
    
        <!-- Afficher les questions une par une -->
        <?php
        for ($i = 1; $i <= $current_question; $i++) {
            echo '<div class="question" id="question_'.$i.'">
                    <label for="question_text_'.$i.'">Question '.$i.' :</label>
                    <input type="text" name="question_text_'.$i.'" value="'.(isset($_POST["question_text_$i"]) ? htmlspecialchars($_POST["question_text_$i"]) : '').'" required>
                    <label for="question_type_'.$i.'">Type de question '.$i.' (Vrai/Faux) :</label>
                    <select name="question_type_'.$i.'" required>
                        <option value="true" '.(isset($_POST["question_type_$i"]) && $_POST["question_type_$i"] == 'true' ? 'selected' : '').'>Vrai</option>
                        <option value="false" '.(isset($_POST["question_type_$i"]) && $_POST["question_type_$i"] == 'false' ? 'selected' : '').'>Faux</option>
                    </select>
                </div>';
        }
        ?>
    
        <!-- Champ caché pour indiquer la question suivante -->
        <input type="hidden" name="current_question" value="<?php echo $current_question; ?>">
    
        <!-- Bouton pour ajouter une question sans soumettre le quiz -->
        <?php if ($current_question < $max_questions) { ?>
            <button type="submit" name="add_question">Ajouter une question</button>
        <?php } ?>
    
        <!-- Bouton pour soumettre le quiz -->
        <button type="submit" name="submit_quiz">Soumettre le quiz</button>
    </form>
        </div>
</body>
</html>
