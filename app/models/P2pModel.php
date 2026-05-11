<?php
/**
 * P2P Messaging Model - Secure Messaging System
 * Handles secure communication between Drivers and Space Owners
 * Phone numbers are never exposed - only user names and IDs
 */

require_once __DIR__ . '/../../core/Database.php';

class P2pModel
{
    private $db;
    
    /**
     * Constructor - Initialize database connection
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create a new conversation between driver and owner
     * @param int $driver_id - User ID of the driver
     * @param int $owner_id - User ID of the space owner
     * @return int|false - Conversation ID or false on failure
     */
    public function createConversation($driver_id, $owner_id)
    {
        // Check if conversation already exists
        $sql = "SELECT conversation_id FROM conversations 
                WHERE (driver_id = ? AND owner_id = ?) 
                   OR (driver_id = ? AND owner_id = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $driver_id, $owner_id, $owner_id, $driver_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['conversation_id'];
        }
        
        // Create new conversation
        $sql = "INSERT INTO conversations (driver_id, owner_id, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $driver_id, $owner_id);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    /**
     * Send a message
     * @param int $conversation_id - Conversation ID
     * @param int $sender_id - User ID of sender
     * @param int $receiver_id - User ID of receiver
     * @param string $message - Plain text message
     * @return bool - Success or failure
     */
    public function sendMessage($conversation_id, $sender_id, $receiver_id, $message)
    {
        $sql = "INSERT INTO p2p_messaging (conversation_id, content, isRead, timestamp, senderID, receiverID) 
                VALUES (?, ?, 0, NOW(), ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isii", $conversation_id, $message, $sender_id, $receiver_id);
        
        if ($stmt->execute()) {
            // Update conversation timestamp
            $this->updateConversationTime($conversation_id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Update conversation updated_at timestamp
     * @param int $conversation_id
     */
    private function updateConversationTime($conversation_id)
    {
        $sql = "UPDATE conversations SET updated_at = NOW() WHERE conversation_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $conversation_id);
        $stmt->execute();
    }
    
    /**
     * Get all conversations for a user (driver or owner)
     * Phone numbers are NOT included for privacy
     * @param int $user_id - User ID
     * @param string $user_role - 'driver' or 'space_owner'
     * @return array - List of conversations with other user info
     */
    public function getUserConversations($user_id, $user_role)
    {
        if ($user_role == 'driver') {
            $sql = "SELECT c.conversation_id, c.owner_id as other_id, u.name as other_name, 
                           u.profile_pic as other_avatar,
                           c.updated_at,
                           (SELECT content FROM p2p_messaging 
                            WHERE conversation_id = c.conversation_id 
                            ORDER BY timestamp DESC LIMIT 1) as last_message,
                           (SELECT timestamp FROM p2p_messaging 
                            WHERE conversation_id = c.conversation_id 
                            ORDER BY timestamp DESC LIMIT 1) as last_time,
                           (SELECT COUNT(*) FROM p2p_messaging 
                            WHERE conversation_id = c.conversation_id 
                            AND receiverID = ? AND isRead = 0) as unread_count
                    FROM conversations c
                    JOIN users u ON c.owner_id = u.userID
                    WHERE c.driver_id = ?
                    ORDER BY c.updated_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
        } else {
            $sql = "SELECT c.conversation_id, c.driver_id as other_id, u.name as other_name,
                           u.profile_pic as other_avatar,
                           c.updated_at,
                           (SELECT content FROM p2p_messaging 
                            WHERE conversation_id = c.conversation_id 
                            ORDER BY timestamp DESC LIMIT 1) as last_message,
                           (SELECT timestamp FROM p2p_messaging 
                            WHERE conversation_id = c.conversation_id 
                            ORDER BY timestamp DESC LIMIT 1) as last_time,
                           (SELECT COUNT(*) FROM p2p_messaging 
                            WHERE conversation_id = c.conversation_id 
                            AND receiverID = ? AND isRead = 0) as unread_count
                    FROM conversations c
                    JOIN users u ON c.driver_id = u.userID
                    WHERE c.owner_id = ?
                    ORDER BY c.updated_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $conversations = $result->fetch_all(MYSQLI_ASSOC);
        
        // Truncate long messages for preview
        foreach ($conversations as &$conv) {
            if ($conv['last_message']) {
                if (strlen($conv['last_message']) > 40) {
                    $conv['last_message'] = substr($conv['last_message'], 0, 37) . '...';
                }
            } else {
                $conv['last_message'] = 'No messages yet';
            }
        }
        
        return $conversations;
    }
    
    /**
     * Get all messages for a specific conversation
     * Phone numbers are NOT included for privacy
     * @param int $conversation_id
     * @param int $user_id - Current user ID (for permission check)
     * @return array - Messages list and other user info
     */
    public function getConversationMessages($conversation_id, $user_id)
    {
        // Verify user has access to this conversation
        $sql = "SELECT * FROM conversations WHERE conversation_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $conversation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conversation = $result->fetch_assoc();
        
        if (!$conversation) {
            return ['messages' => [], 'other_user' => null];
        }
        
        // Determine other user (without phone number for privacy)
        if ($conversation['driver_id'] == $user_id) {
            $other_id = $conversation['owner_id'];
        } elseif ($conversation['owner_id'] == $user_id) {
            $other_id = $conversation['driver_id'];
        } else {
            return ['messages' => [], 'other_user' => null];
        }
        
        // Get other user info (no phone number - privacy first)
        $sql = "SELECT userID, name, email, profile_pic, role FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $other_id);
        $stmt->execute();
        $other_user = $stmt->get_result()->fetch_assoc();
        
        // Get all messages in conversation
        $sql = "SELECT m.*, u.name as sender_name 
                FROM p2p_messaging m
                JOIN users u ON m.senderID = u.userID
                WHERE m.conversation_id = ?
                ORDER BY m.timestamp ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $conversation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        
        // Mark unread messages as read
        $sql = "UPDATE p2p_messaging SET isRead = 1 
                WHERE conversation_id = ? AND receiverID = ? AND isRead = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $conversation_id, $user_id);
        $stmt->execute();
        
        return [
            'messages' => $messages,
            'other_user' => $other_user,
            'conversation' => $conversation
        ];
    }
    
    /**
     * Get available users to start a conversation with
     * Phone numbers are NOT included for privacy
     * @param int $user_id - Current user ID
     * @param string $user_role - 'driver' or 'space_owner'
     * @return array - List of users the current user can chat with
     */
    public function getAvailableUsers($user_id, $user_role)
    {
        if ($user_role == 'driver') {
            // Drivers can chat with space owners (no phone numbers exposed)
            $sql = "SELECT userID, name, email, profile_pic, role 
                    FROM users 
                    WHERE role = 'space_owner' AND userID != ?
                    ORDER BY name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
        } else {
            // Space owners can chat with drivers (no phone numbers exposed)
            $sql = "SELECT userID, name, email, profile_pic, role 
                    FROM users 
                    WHERE role = 'driver' AND userID != ?
                    ORDER BY name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get total unread messages count for a user
     * @param int $user_id
     * @return int
     */
    public function getUnreadCount($user_id)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM p2p_messaging m
                JOIN conversations c ON m.conversation_id = c.conversation_id
                WHERE (c.driver_id = ? OR c.owner_id = ?) 
                AND m.receiverID = ? AND m.isRead = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $user_id, $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] ?? 0;
    }
    
    /**
     * Delete a message (only the sender can delete)
     * @param int $message_id
     * @param int $user_id - Current user ID (must be sender)
     * @return bool
     */
    public function deleteMessage($message_id, $user_id)
    {
        $sql = "DELETE FROM p2p_messaging WHERE messageID = ? AND senderID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $message_id, $user_id);
        return $stmt->execute();
    }
    
    /**
     * Get conversation by ID
     * @param int $conversation_id
     * @return array|null
     */
    public function getConversationById($conversation_id)
    {
        $sql = "SELECT * FROM conversations WHERE conversation_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $conversation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>