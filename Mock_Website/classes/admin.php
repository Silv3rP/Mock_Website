<?php

namespace App;

require_once 'User.php';


class Admin extends User {
    public function __construct($conn) {
        parent::__construct($conn); // Pass the connection to User class
    }

    public function manageMenu($action, $menuItem)
{
    switch ($action) {
        case 'add':
            $stmt = $this->conn->prepare("INSERT INTO menu_items (item_name, price) VALUES (?, ?)");
            if (!$stmt) {
                return false; // Prevent calling methods on a boolean
            }

            $stmt->bind_param("sd", $menuItem['item_name'], $menuItem['price']);
            $result = $stmt->execute();
            $stmt->close(); // Ensures testManageMenuAddSuccess passes
            return $result;

        

        default:
            return false; 
    }
}

}
?>
