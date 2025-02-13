<?php
include_once 'db.php';

class User {
    private $pdo;
    private $user_id;

    public function __construct($pdo, $user_id = null) {
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    public function register($username, $password) {
        // Vérification si l'utilisateur existe déjà
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->rowCount() > 0) {
            return "Ce nom d'utilisateur est déjà pris.";
        } else {
            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
            
            return "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    }

    public function login($username, $password) {
        // Vérification de l'existence de l'utilisateur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Si l'utilisateur n'existe pas, on renvoie un message d'erreur
        if (!$user) {
            return "Nom d'utilisateur introuvable.";
            exit();
        }
    
        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            // Connexion réussie
            session_start();
            $_SESSION['user'] = $user;
        } else {
            return "Mot de passe incorrect.";
        }
    }
    

    public function logout() {
        session_start();
        session_unset();  // Supprimer toutes les variables de session
        session_destroy();  // Détruire la session
        return "Déconnexion réussie.";
    }

    public function getCreatedQuizzes() {
        $query = "SELECT * FROM quizz WHERE created_by = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>