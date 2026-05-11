<?php
class Availability {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }


    public function checkSpotavailability($spotID, $start, $end) {
        $query = "SELECT * FROM reservations 
                  WHERE spotID = ? 
                  AND NOT (endTime <= ? OR startTime >= ?)";
        

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iss", $spotID, $start, $end);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "available";
        } else {
            return "busy"; 
        }
    }

    // ميثود تانية ممكن تحتاجيها عشان الـ Waitlist
    public function getNextAvailableSlot($spotID) {
        // لوجيك يحسب أول وقت المكان هيفضى فيه
    }
}