<?php

namespace App;

class User { 
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password)
{
    $stmt = $this->conn->prepare("SELECT id, password, role FROM users WHERE email = ?");

    if ($stmt === false) {
        return null; 
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close(); 

    if ($user && password_verify($password, $user['password'])) {
        return [
                "id" => $user["id"],
                "role" => $user["role"]
            ];
    }
    return null;
}

    // Validation methods
    public static function validateEmail($email) {
        // Email validation: no domain restriction
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password) {
        // At least 8 chars, one upper, one lower, one number, one special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^a-zA-Z\\d]).{8,}$/', $password);
    }

    public static function validateRole($role) {
        return in_array($role, ['user', 'admin']);
    }
}
?>
