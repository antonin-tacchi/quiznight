<?php

// Paramètres de connexion à la base de données
$host = 'localhost';
$db_name = 'quizznight';
$username = 'root';
$password = '';

try {
    // Créer une nouvelle connexion PDO
    $db = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Définir les attributs PDO pour un meilleur contrôle
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
}
?>
