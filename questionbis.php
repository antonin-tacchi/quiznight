<?php
class Questionbis {
    private $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->db = $db;
    }

    // Méthode pour ajouter une question
    public function addQuestion($quiz_id, $text, $question_type) {
        $query = "INSERT INTO questions (quiz_id, text, question_type) 
                  VALUES (:quiz_id, :text, :question_type)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':question_type', $question_type);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Méthode pour récupérer toutes les questions d'un quiz
    public function getQuestionsByQuiz($quiz_id) {
        $query = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour supprimer une question
    public function deleteQuestion($id) {
        $query = "DELETE FROM questions WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>
