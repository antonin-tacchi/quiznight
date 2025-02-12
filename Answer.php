<?php
class Answer {
    private $conn;
    private $table_name = "reponses";

    public $id;
    public $question_id;
    public $text;
    public $is_correct;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createAnswer() {
        $query = "INSERT INTO " . $this->table_name . " SET question_id=:question_id, text=:text, is_correct=:is_correct";
        $stmt = $this->conn->prepare($query);

        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->is_correct = (bool)$this->is_correct;  // Convertir à booléen

        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":is_correct", $this->is_correct);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
