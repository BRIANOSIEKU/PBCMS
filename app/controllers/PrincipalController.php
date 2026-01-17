<?php
class PrincipalController {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // Function to get total number of students
    public function getTotalStudents(){
        $sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'student'";
        $result = $this->conn->query($sql);

        if($result){
            $row = $result->fetch_assoc();
            return $row['total'];
        }

        return 0;
    }
}
