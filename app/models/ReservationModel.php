<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';

class ReservationModel {
    private $db;
    private $notificationModel;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        // Load NotificationModel for sending notifications
        require_once __DIR__ . "/NotificationModel.php";
        $this->notificationModel = new NotificationModel();
    }
    
    // Get recent reservations (last $limit reservations)
    public function getRecentReservations($limit = 10) {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, s.location as spot_location 
             FROM reservation r 
             LEFT JOIN users u ON r.userID = u.userID 
             LEFT JOIN spot s ON r.spotID = s.spotID 
             ORDER BY r.reservationID DESC LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get reservations that have exceeded the end time by more than 30 minutes (overstay violations)
    public function getOverstayViolations() {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, u.phone_num, s.location, s.area
             FROM reservation r 
             JOIN users u ON r.userID = u.userID 
             JOIN spot s ON r.spotID = s.spotID 
             WHERE r.status = 'active' 
             AND r.endTime < DATE_SUB(NOW(), INTERVAL 30 MINUTE)"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get current active reservations (not yet ended)
    public function getActiveReservations() {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, u.phone_num, s.location, s.area
             FROM reservation r 
             JOIN users u ON r.userID = u.userID 
             JOIN spot s ON r.spotID = s.spotID 
             WHERE r.status = 'active' AND r.endTime > NOW()
             ORDER BY r.startTime ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Emergency override - cancel a reservation and free the spot
    public function overrideReservation($reservationID) {
        // Get reservation details before overriding
        $stmt = $this->db->prepare("SELECT userID, spotID FROM reservation WHERE reservationID = ?");
        $stmt->bind_param("i", $reservationID);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservation = $result->fetch_assoc();
        
        if ($reservation) {
            // Update reservation status
            $stmt = $this->db->prepare("UPDATE reservation SET status = 'overridden' WHERE reservationID = ?");
            $stmt->bind_param("i", $reservationID);
            $stmt->execute();
            
            // Free the spot
            $stmt2 = $this->db->prepare("UPDATE spot SET status = 'available' WHERE spotID = ?");
            $stmt2->bind_param("i", $reservation['spotID']);
            $stmt2->execute();
            
            // Send notification to the user about the override
            $this->notificationModel->create(
                $reservation['userID'],
                'system',
                'Reservation Overridden - Emergency',
                'Your reservation has been overridden due to an emergency. The parking spot has been cleared for emergency services.',
                "/php_project/app/views/driver/reservations.php"
            );
            
            return true;
        }
        return false;
    }
    
    // Get violations that are eligible for fines (ended but still active)
    public function getViolationsForFines() {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, u.email, s.location, s.area,
                    TIMESTAMPDIFF(MINUTE, r.endTime, NOW()) as overstay_minutes
             FROM reservation r 
             JOIN users u ON r.userID = u.userID 
             JOIN spot s ON r.spotID = s.spotID 
             WHERE r.status = 'active' 
             AND r.endTime < NOW()
             ORDER BY r.endTime ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Generate a fine for a violation
    public function generateFine($reservationID, $amount, $reason) {
        // Get reservation details
        $stmt = $this->db->prepare("SELECT r.userID, s.location FROM reservation r JOIN spot s ON r.spotID = s.spotID WHERE r.reservationID = ?");
        $stmt->bind_param("i", $reservationID);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservation = $result->fetch_assoc();
        
        // Insert the fine
        $stmt = $this->db->prepare("INSERT INTO fines (reservationID, amount, reason, status, generated_at) VALUES (?, ?, ?, 'unpaid', NOW())");
        $stmt->bind_param("ids", $reservationID, $amount, $reason);
        $result = $stmt->execute();
        
        if ($result && $reservation) {
            // Send notification to the user
            $this->notificationModel->create(
                $reservation['userID'],
                'fine',
                'Overstay Fine Issued',
                "You have been fined $${amount} for overstaying at {$reservation['location']}. Reason: {$reason}",
                "/php_project/app/views/driver/fines.php"
            );
            
            // Notify all admins
            $this->notificationModel->notifyAllAdmins(
                'fine',
                'New Fine Generated',
                "A fine of $${amount} has been issued for reservation #{$reservationID}",
                "/php_project/app/views/admin/fines.php"
            );
        }
        
        return $result;
    }
    
    // Get all fines with user and spot details
    public function getAllFines() {
        $sql = "SELECT f.*, u.name as user_name, r.startTime, r.endTime, s.location 
                FROM fines f
                JOIN reservation r ON f.reservationID = r.reservationID
                JOIN users u ON r.userID = u.userID
                JOIN spot s ON r.spotID = s.spotID
                ORDER BY f.fineID DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Create a new reservation
    public function createReservation($userID, $spotID, $startTime, $endTime, $totalCost) {
        $sql = "INSERT INTO reservation (userID, spotID, startTime, endTime, total_cost, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'active', NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iissd", $userID, $spotID, $startTime, $endTime, $totalCost);
        
        if ($stmt->execute()) {
            $reservationID = $this->db->insert_id;
            
            // Update spot status to occupied
            $this->db->query("UPDATE spot SET status = 'occupied' WHERE spotID = $spotID");
            
            // Get spot location for notification
            $result = $this->db->query("SELECT location FROM spot WHERE spotID = $spotID");
            $spot = $result->fetch_assoc();
            
            // Send notification to the user
            $this->notificationModel->create(
                $userID,
                'booking',
                'Booking Confirmed',
                "Your booking at {$spot['location']} has been confirmed from {$startTime} to {$endTime}. Total: $${totalCost}",
                "/php_project/app/views/driver/reservations.php"
            );
            
            return $reservationID;
        }
        return false;
    }
    
    // Cancel a reservation
    public function cancelReservation($reservationID, $userID) {
        $sql = "UPDATE reservation SET status = 'cancelled' WHERE reservationID = ? AND userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $reservationID, $userID);
        
        if ($stmt->execute()) {
            // Free the spot
            $this->db->query("UPDATE spot SET status = 'available' WHERE spotID = (SELECT spotID FROM reservation WHERE reservationID = $reservationID)");
            
            // Send notification
            $this->notificationModel->create(
                $userID,
                'booking',
                'Booking Cancelled',
                "Your booking #{$reservationID} has been cancelled.",
                "/php_project/app/views/driver/reservations.php"
            );
            
            return true;
        }
        return false;
    }
    
    // Get reservations for a specific user
    public function getUserReservations($userID) {
        $sql = "SELECT r.*, s.location, s.zone 
                FROM reservation r 
                JOIN spot s ON r.spotID = s.spotID 
                WHERE r.userID = ?
                ORDER BY r.startTime DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Update fine status (mark as paid)
    public function updateFineStatus($fineID, $status) {
        $sql = "UPDATE fines SET status = ? WHERE fineID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $fineID);
        return $stmt->execute();
    }
}
?>