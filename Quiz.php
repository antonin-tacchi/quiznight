<?php

class Quiz {
    private $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db) {
        $this->db = $db;
    }

    // Méthode pour ajouter un quiz
    public function addQuiz($title, $description, $createdBy, $urlImage) {
        // Préparer la requête SQL pour insérer un quiz
        $query = "INSERT INTO quizz (title, description, created_by, url_image) 
                  VALUES (:title, :description, :createdBy, :urlImage)";
        
        // Préparer la requête avec la connexion PDO
        $stmt = $this->db->prepare($query);
        
        // Lier les paramètres
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':createdBy', $createdBy);
        $stmt->bindParam(':urlImage', $urlImage);

        // Exécuter la requête et retourner true si l'insertion a réussi
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // Retourne l'ID du quiz créé
        }
        return false; // Retourne false si l'insertion échoue
    }
}

?>
