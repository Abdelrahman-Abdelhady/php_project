<?php
/**
 * P2P Messaging Controller
 * Handles secure messaging between Drivers and Space Owners
 * Phone numbers are never exposed in the chat system
 */

class P2pController extends Controller
{
    private $p2pModel;
    
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: /php_project/simple_login.php");
            exit;
        }
        
        $this->p2pModel = new P2pModel();
    }
    
    /**
     * Display messages page with all conversations
     */
    public function index()
    {
        $user_id = $_SESSION['user_id'];
        $user_role = $_SESSION['role'];
        
        // Get all conversations for this user
        $conversations = $this->p2pModel->getUserConversations($user_id, $user_role);
        
        // Get selected conversation
        $selected_conversation_id = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;
        if ($selected_conversation_id == 0 && !empty($conversations)) {
            $selected_conversation_id = $conversations[0]['conversation_id'];
        }
        
        $messages = [];
        $other_user = null;
        
        if ($selected_conversation_id > 0) {
            $result = $this->p2pModel->getConversationMessages($selected_conversation_id, $user_id);
            $messages = $result['messages'];
            $other_user = $result['other_user'];
        }
        
        // Get available users to start a new chat (no phone numbers exposed)
        $available_users = $this->p2pModel->getAvailableUsers($user_id, $user_role);
        
        // Get unread count for badge
        $unread_count = $this->p2pModel->getUnreadCount($user_id);
        
        $this->view('p2p/index', [
            'conversations' => $conversations,
            'selected_conversation_id' => $selected_conversation_id,
            'messages' => $messages,
            'other_user' => $other_user,
            'available_users' => $available_users,
            'unread_count' => $unread_count
        ]);
    }
    
    /**
     * Send a message
     */
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /php_project/P2p/index");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $conversation_id = $_POST['conversation_id'] ?? 0;
        $receiver_id = $_POST['receiver_id'] ?? 0;
        $message = trim($_POST['message'] ?? '');
        
        if ($conversation_id && $receiver_id && $message) {
            $this->p2pModel->sendMessage($conversation_id, $user_id, $receiver_id, $message);
        }
        
        header("Location: /php_project/P2p/index?conversation_id=" . $conversation_id);
        exit;
    }
    
    /**
     * Start a new conversation
     */
    public function newConversation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /php_project/P2p/index");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $user_role = $_SESSION['role'];
        $other_user_id = $_POST['other_user_id'] ?? 0;
        
        if ($other_user_id) {
            if ($user_role == 'driver') {
                $driver_id = $user_id;
                $owner_id = $other_user_id;
            } else {
                $driver_id = $other_user_id;
                $owner_id = $user_id;
            }
            
            $conversation_id = $this->p2pModel->createConversation($driver_id, $owner_id);
            header("Location: /php_project/P2p/index?conversation_id=" . $conversation_id);
            exit;
        }
        
        header("Location: /php_project/P2p/index");
        exit;
    }
    
    /**
     * Delete a message
     */
    public function deleteMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /php_project/P2p/index");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $message_id = $_POST['message_id'] ?? 0;
        
        if ($message_id) {
            $this->p2pModel->deleteMessage($message_id, $user_id);
        }
        
        header("Location: /php_project/P2p/index");
        exit;
    }
    
    /**
     * API: Get unread messages count for AJAX
     */
    public function getUnreadCount()
    {
        header('Content-Type: application/json');
        $user_id = $_SESSION['user_id'];
        $count = $this->p2pModel->getUnreadCount($user_id);
        echo json_encode(['unread_count' => $count]);
        exit;
    }
}
?>