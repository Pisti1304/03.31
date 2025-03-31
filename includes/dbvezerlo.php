<?php

class DBVezerlo{
    private $conn;
    private $host =  "localhost";

    private $user = "root";

    private $password = "";

    private $database = "carad";

    function __construct(){
        $this->conn = new mysqli($this->host, $this->user, $this->password);
        if($this->conn->connect_error){ 
            die("Kapcsolódási hiba". $this->conn->connect_error);
        }
    }

    function executSelectQuery($query, $params =[],$types = ""){
        $stmt= $this->conn->prepare($query);
        if(!empty($params)){
            $stmt->bind_param($types, $params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $resultset = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultset;
    }
    function executeQuery($query, $params = [],$types = ""){
        $stmt= $this->conn->prepare($query);
        if(!empty($params)){
            $stmt->bind_param($types, $params);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getLastInsertId(){
        return $this->conn->insert_id;
    }
    public function getInsertId(){
        return $this->conn->insert_id;
    }
    public function closeDB()
    {
        $this->conn->close();
    }
}
?>

