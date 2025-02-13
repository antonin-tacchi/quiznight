<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'quizznight';  // Nom de votre base de données
    private $username = 'root';        // Nom d'utilisateur MySQL
    private $password = '';            // Mot de passe MySQL
    private $conn;

    // Méthode pour obtenir la connexion à la base de données
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", 
                                   $this->username, 
                                   $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Affiche les erreurs PDO
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
