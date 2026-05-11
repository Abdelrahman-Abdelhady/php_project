<?php
// Initialize all variables to avoid undefined errors
$conversations = $conversations ?? [];
$selected_conversation_id = $selected_conversation_id ?? 0;
$messages = $messages ?? [];
$other_user = $other_user ?? null;
$available_users = $available_users ?? [];
$unread_count = $unread_count ?? 0;




echo "<!-- Available users count: " . count($available_users) . " -->";
if (empty($available_users)) {
    echo "<!-- No available users found in database -->";}

// Get user info from session
$user_role = $_SESSION['role'] ?? 'driver';
$user_id = $_SESSION['user_id'] ?? 0;
$user_name = $_SESSION['user'] ?? 'User';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Secure Chat | CitySlot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; height: 100vh; display: flex; }
        
        /* Sidebar */
        .sidebar { width: 300px; background: #111827; color: white; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #374151; }
        .sidebar-header h3 { margin: 0; font-size: 1.2rem; }
        .conversations-list { flex: 1; overflow-y: auto; }
        .conversation-item { padding: 15px 20px; border-bottom: 1px solid #374151; cursor: pointer; text-decoration: none; display: block; color: #d1d5db; transition: 0.3s; }
        .conversation-item:hover { background: #1f2937; }
        .conversation-item.active { background: #4F46E5; color: white; }
        .conversation-name { font-weight: bold; margin-bottom: 5px; }
        .conversation-preview { font-size: 12px; opacity: 0.7; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .conversation-time { font-size: 10px; opacity: 0.5; margin-top: 5px; }
        .unread-badge { background: #ef4444; color: white; border-radius: 10px; padding: 2px 6px; font-size: 10px; margin-left: 8px; }
        
        /* Chat Area */
        .chat-area { flex: 1; display: flex; flex-direction: column; background: #f9fafb; }
        .chat-header { background: white; padding: 15px 25px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 15px; }
        .chat-header h4 { margin: 0; color: #111827; }
        .chat-header p { margin: 0; font-size: 12px; color: #6b7280; }
        .chat-header img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }
        
        /* Privacy Notice */
        .privacy-notice { background: #eef2ff; padding: 8px 15px; font-size: 11px; color: #4F46E5; text-align: center; border-bottom: 1px solid #e5e7eb; }
        
        /* Messages */
        .messages-container { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; }
        .message { max-width: 65%; padding: 10px 15px; margin-bottom: 10px; border-radius: 18px; word-wrap: break-word; }
        .message.sent { background: #4F46E5; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
        .message.received { background: white; color: #111827; align-self: flex-start; border-bottom-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .message-time { font-size: 10px; opacity: 0.7; margin-top: 5px; text-align: right; }
        
        /* Input Area */
        .input-area { background: white; padding: 15px 20px; border-top: 1px solid #e5e7eb; display: flex; gap: 10px; }
        .input-area input { flex: 1; padding: 12px 15px; border: 1px solid #e5e7eb; border-radius: 25px; outline: none; }
        .input-area input:focus { border-color: #4F46E5; }
        .input-area button { background: #4F46E5; color: white; border: none; padding: 0 25px; border-radius: 25px; cursor: pointer; }
        
        /* Empty State & Modal */
        .empty-state { text-align: center; padding: 50px; color: #6b7280; }
        .new-chat-btn { background: #4F46E5; color: white; border: none; padding: 8px 15px; border-radius: 20px; cursor: pointer; font-size: 12px; margin-left: 10px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 25px; border-radius: 15px; min-width: 300px; }
        .btn-primary { background: #4F46E5; border: none; }
        .btn-primary:hover { background: #4338ca; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>🔒 Secure Messages</h3>
            <button class="new-chat-btn" onclick="openNewChatModal()">+ New Chat</button>
        </div>
    </div>
    <div class="conversations-list">
        <?php if (!empty($conversations)): ?>
            <?php foreach ($conversations as $conv): ?>
                <a href="<?= BASE_URL ?>P2p/index?conversation_id=<?= $conv['conversation_id'] ?>" 
                   class="conversation-item <?= ($selected_conversation_id == $conv['conversation_id']) ? 'active' : '' ?>">
                    <div class="conversation-name">
                        <?= htmlspecialchars($conv['other_name']) ?>
                        <?php if (($conv['unread_count'] ?? 0) > 0): ?>
                            <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="conversation-preview"><?= htmlspecialchars(substr($conv['last_message'] ?? 'No messages yet', 0, 35)) ?></div>
                    <div class="conversation-time"><?= isset($conv['last_time']) ? date('M d, H:i', strtotime($conv['last_time'])) : '' ?></div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="padding: 20px; text-align: center; color: #6b7280;">
                No conversations yet.<br>
                <button class="new-chat-btn" onclick="openNewChatModal()" style="margin-top: 10px;">Start a chat</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chat Area -->
<div class="chat-area">
    <div class="privacy-notice">
        🔒 Your phone number is hidden. Only your name is visible to other users.
    </div>
    
    <div class="chat-header">
        <?php if (!empty($other_user)): ?>
            <img src="<?= $other_user['profile_pic'] ?? 'https://via.placeholder.com/45?text=User' ?>" alt="Profile">
            <div>
                <h4><?= htmlspecialchars($other_user['name']) ?></h4>
                <p><?= ($_SESSION['role'] ?? 'driver') == 'driver' ? 'Space Owner' : 'Driver' ?></p>
            </div>
        <?php else: ?>
            <div>
                <h4>Select a conversation</h4>
                <p>Choose a chat from the sidebar to start messaging</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Messages Container -->
    <div class="messages-container" id="messagesContainer">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message <?= ($msg['senderID'] == $_SESSION['user_id']) ? 'sent' : 'received' ?>">
                    <?= htmlspecialchars($msg['content']) ?>
                    <div class="message-time"><?= date('h:i A', strtotime($msg['timestamp'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($selected_conversation_id > 0): ?>
            <div class="empty-state">✨ No messages yet. Send a message to start the conversation!</div>
        <?php else: ?>
            <div class="empty-state">💬 Select a conversation or start a new chat to begin messaging</div>
        <?php endif; ?>
    </div>

    <!-- Message Input Form -->
    <?php if ($selected_conversation_id > 0 && !empty($other_user)): ?>
    <form method="POST" action="<?= BASE_URL ?>P2p/send" class="input-area">
        <input type="hidden" name="conversation_id" value="<?= $selected_conversation_id ?>">
        <input type="hidden" name="receiver_id" value="<?= $other_user['userID'] ?>">
        <input type="text" name="message" placeholder="Type a secure message..." autocomplete="off" required>
        <button type="submit">Send</button>
    </form>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="newChatModal" class="modal">
    <div class="modal-content">
        <h4>Start New Secure Conversation</h4>
        <p style="font-size: 12px; color: #6b7280; margin-bottom: 15px;">Your phone number will remain hidden</p>
        <form method="POST" action="<?= BASE_URL ?>P2p/newConversation">
            <div class="mb-3">
                <label class="form-label">Select user to chat with:</label>
                <select name="other_user_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php if (!empty($available_users)): ?>
                        <?php foreach ($available_users as $u): ?>
                            <option value="<?= $u['userID'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= ($u['role'] ?? '') == 'driver' ? 'Driver' : 'Owner' ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Start Chat</button>
            <button type="button" class="btn btn-secondary w-100 mt-2" onclick="closeNewChatModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    function openNewChatModal() {
        document.getElementById('newChatModal').style.display = 'flex';
    }
    
    function closeNewChatModal() {
        document.getElementById('newChatModal').style.display = 'none';
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('newChatModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

</body>
</html>