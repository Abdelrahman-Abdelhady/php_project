<?php
class Vehicle {
    private $db;

    public function __construct() {
        // بننادي كلاس الداتابيز بتاع المشروع
        $this->db = Database::getInstance()->getConnection();
    }

    // ميثود لجلب عربيات مستخدم معين
    public function getUserVehicles($userID) {
        $sql = "SELECT * FROM vehicle WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ميثود لإضافة عربية بكل التفاصيل اللي طلبتيها
    public function addVehicle($userID, $plate, $type, $model, $color, $height, $width, $is_default) {
        // لو العربية دي هي الأساسية، نصفر الـ default القديم للمستخدم ده
        if ($is_default == 1) {
            $this->db->query("UPDATE vehicle SET is_default = 0 WHERE userID = $userID");
        }

        $sql = "INSERT INTO vehicle (userID, licensePlate, type, model, color, height, width, is_default) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isssssdi", $userID, $plate, $type, $model, $color, $height, $width, $is_default);
        return $stmt->execute();
    }
    
}