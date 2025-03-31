<?php
class DBVezerlo {
    private $conn;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "carad";

    function __construct() {
        $this->conn = new mysqli($this->host,
        $this->user, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Kapcsolódási hiba: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    // SELECT lekérdezésekhez
    function executeSelectQuery($query, $params = [], $types = "") {
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $resultset = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultset;
    }

    // INSERT, UPDATE, DELETE lekérdezésekhez
    function executeQuery($query, $params = [], $types = "") {
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $result = $stmt->execute(); // Végrehajtja a lekérdezést
        $stmt->close();
        return $result;
    }

    //utolsó beszúrt id
    public function getLastInsertId() {
        return $this->conn->insert_id; // Ha a conn a mysqli kapcsolat
    }

    public function getInsertId() {
        return $this->conn->insert_id;
    }

    function closeDB() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>