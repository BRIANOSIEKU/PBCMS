<?php
class AuthController {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function login($email, $password){
        // Remove extra spaces
        $email = trim($email);
        $password = trim($password);

        // Prepare SQL to fetch user by email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $user = $result->fetch_assoc();
            $dbPassword = trim($user['password']); // trim DB value to avoid hidden chars

            // Compare plain text password
            if($password === $dbPassword){
                // Start session if not already started
                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }

                // Store user info in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                return true; // login successful
            }
        }

        return false; // login failed
    }
}
