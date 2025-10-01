<?php
namespace App;

class Menu {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get all menu items
    public function getAllItems() {
        $query = "SELECT * FROM fast_food";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Get item by ID
    public function getItemById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM fast_food WHERE item_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Search menu items by name
    public function searchItems($query) {
        $stmt = $this->conn->prepare("SELECT * FROM fast_food WHERE item_name LIKE ?");
        $like = "%{$query}%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
