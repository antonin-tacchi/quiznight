<?php
include 'db.php';

// Récupérer tous les quiz disponibles
$stmt = $pdo->query("SELECT * FROM quizz");
$quizzList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si le formulaire est soumis avec un quiz_id sélectionné
if (isset($_POST['quiz_id']) && is_numeric($_POST['quiz_id'])) {
    $quiz_id = $_POST['quiz_id'];

    // Récupérer le quiz sélectionné
    $stmt = $pdo->prepare("SELECT * FROM quizz WHERE id = :quiz_id");
    $stmt->execute(['quiz_id' => $quiz_id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quiz) {
        // Récupérer toutes les questions pour ce quiz
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quiz_id");
        $stmt->execute(['quiz_id' => $quiz_id]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Traitement des réponses si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answers'])) {
    $score = 0;
    foreach ($_POST['answers'] as $question_id => $answer_id) {
        // Vérification de la réponse
        $stmt = $pdo->prepare("SELECT is_correct FROM reponses WHERE id = :answer_id AND question_id = :question_id");
        $stmt->execute(['answer_id' => $answer_id, 'question_id' => $question_id]);
        $answer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($answer && $answer['is_correct'] == 1) {
            $score++;
        }
    }
    // Affichage du score
    echo "Votre score : $score / " . count($questions);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélectionner un Quiz</title>
</head>
<body>
    <h1>Choisissez un quiz</h1>
    <form method="POST">
        <select name="quiz_id">
            <?php foreach ($quizzList as $quizItem): ?>
                <option value="<?= $quizItem['id'] ?>"><?= htmlspecialchars($quizItem['title']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Commencer le quiz</button>
    </form>

    <?php if (isset($quiz)): ?>
        <h2>Quiz: <?= htmlspecialchars($quiz['title']) ?></h2>
        <p><?= htmlspecialchars($quiz['description']) ?></p>

        <form method="POST">
            <?php foreach ($questions as $question): ?>
                <fieldset>
                    <legend><?= htmlspecialchars($question['text']) ?></legend>
                    <?php
                    // Récupérer les réponses pour chaque question
                    $stmt = $pdo->prepare("SELECT * FROM reponses WHERE question_id = :question_id");
                    $stmt->execute(['question_id' => $question['id']]);
                    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($reponses as $reponse): ?>
                        <label>
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= $reponse['id'] ?>" required>
                            <?= htmlspecialchars($reponse['text']) ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>
            <button type="submit">Soumettre</button>
        </form>
    <?php endif; ?>
</body>
</html>
