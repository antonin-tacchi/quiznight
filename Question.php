<?php

class Question {
    private $db;
    private $quiz_id;
    private $text;
    private $question_type;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->db = $db;
    }

    // Méthodes pour définir les propriétés privées
    public function setQuizId($quiz_id) {
        $this->quiz_id = $quiz_id;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setQuestionType($question_type) {
        $this->question_type = $question_type;
    }

    // Méthode pour ajouter une question
    public function addQuestion($quiz_id, $text, $question_type) {
        // Préparer la requête SQL pour insérer la question
        $query = "INSERT INTO questions (quiz_id, text, question_type) 
                  VALUES (:quiz_id, :text, :question_type)";

        // Préparer la requête avec la connexion PDO
        $stmt = $this->db->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':question_type', $question_type);

        // Exécuter la requête et retourner true si l'insertion a réussi
        if ($stmt->execute()) {
            return true;
        }
        return false; // Retourne false si l'insertion échoue
    }
}

?>
