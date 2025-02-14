<?php
class Quizbis {
    private $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->db = $db;
    }

    // Méthode pour ajouter un quiz
    public function addQuiz($title, $description, $createdBy, $urlImage) {
        $query = "INSERT INTO quizz (title, description, created_by, url_image) 
                  VALUES (:title, :description, :createdBy, :urlImage)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':createdBy', $createdBy);
        $stmt->bindParam(':urlImage', $urlImage);

        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // Retourne l'ID du quiz créé
        }
        return false; 
    }

    // Méthode pour récupérer les informations d'un quiz
    public function getQuiz($quizId, $userId) {
        $query = "SELECT * FROM quizz WHERE id = :quizId AND created_by = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quizId', $quizId);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer tous les quiz d'un utilisateur
    public function getQuizzesByUser($userId) {
        $query = "SELECT * FROM quizz WHERE created_by = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour modifier un quiz
    public function modifyQuiz($quizId, $title, $description, $urlImage) {
        $query = "UPDATE quizz SET title = :title, description = :description, url_image = :urlImage WHERE id = :quizId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':urlImage', $urlImage);
        $stmt->bindParam(':quizId', $quizId);

        return $stmt->execute();
    }

    // Méthode pour supprimer un quiz
    public function deleteQuiz($quizId) {
        $query = "DELETE FROM quizz WHERE id = :quizId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quizId', $quizId);

        return $stmt->execute();
    }
}
?>
