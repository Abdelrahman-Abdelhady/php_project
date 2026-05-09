<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /php_project/simple_login.php");
    exit;
}

require_once __DIR__ . "/../models/NotificationModel.php";

$notificationModel = new NotificationModel();
$userID = $_SESSION['user_id'];

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_read'])) {
        $notificationModel->markAsRead($_POST['notification_id'], $userID);
        header("Location: notifications.php");
        exit;
    } elseif (isset($_POST['mark_all_read'])) {
        $notificationModel->markAllAsRead($userID);
        header("Location: notifications.php");
        exit;
    } elseif (isset($_POST['delete'])) {
        $notificationModel->delete($_POST['notification_id'], $userID);
        header("Location: notifications.php");
        exit;
    }
}

$notifications = $notificationModel->getUserNotifications($userID);
$unreadCount = $notificationModel->getUnreadCount($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - CitySlot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f7f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .notification-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header-stats {
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-stats h2 {
            margin: 0;
            color: #1f2937;
        }

        .unread-badge {
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 10px;
        }

        .notification-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 12px;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: 0.3s;
            cursor: pointer;
            position: relative;
        }

        .notification-card.unread {
            background: #eef2ff;
            border-left: 4px solid #3b82f6;
        }

        .notification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .notification-message {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .notification-time {
            font-size: 11px;
            color: #9ca3af;
        }

        .notification-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 8px;
        }

        .badge-message { background: #3b82f6; color: white; }
        .badge-fine { background: #ef4444; color: white; }
        .badge-system { background: #6b7280; color: white; }
        .badge-event { background: #f59e0b; color: white; }
        .badge-booking { background: #10b981; color: white; }

        .btn-mark-all {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-mark-all:hover {
            background: #2563eb;
        }

        .delete-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-btn:hover {
            color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
        }

        .empty-state i {
            font-size: 64px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="notification-container">
    <div class="header-stats">
        <div>
            <h2><i class="fas fa-bell"></i> Notifications</h2>
            <?php if ($unreadCount > 0): ?>
                <span class="unread-badge"><?= $unreadCount ?> unread</span>
            <?php endif; ?>
        </div>
        <?php if ($unreadCount > 0): ?>
            <form method="POST">
                <button type="submit" name="mark_all_read" class="btn-mark-all">
                    <i class="fas fa-check-double"></i> Mark all as read
                </button>
            </form>
        <?php endif; ?>
    </div>

    <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $notif): ?>
            <div class="notification-card <?= $notif['isRead'] ? '' : 'unread' ?>">
                <form method="POST" class="delete-form" style="display: inline;">
                    <input type="hidden" name="notification_id" value="<?= $notif['notificationID'] ?>">
                    <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Delete this notification?')">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                
                <?php
                    $badgeClass = '';
                    $icon = '';
                    switch($notif['type']) {
                        case 'message': 
                            $badgeClass = 'badge-message'; 
                            $icon = '💬'; 
                            break;
                        case 'fine': 
                            $badgeClass = 'badge-fine'; 
                            $icon = '💰'; 
                            break;
                        case 'event_lock': 
                            $badgeClass = 'badge-event'; 
                            $icon = '🔒'; 
                            break;
                        case 'booking': 
                            $badgeClass = 'badge-booking'; 
                            $icon = '📅'; 
                            break;
                        default: 
                            $badgeClass = 'badge-system'; 
                            $icon = '🔔'; 
                            break;
                    }
                ?>
                
                <div class="notification-title">
                    <span class="notification-badge <?= $badgeClass ?>"><?= $icon ?> <?= ucfirst($notif['type']) ?></span>
                    <?= htmlspecialchars($notif['title']) ?>
                </div>
                <div class="notification-message"><?= htmlspecialchars($notif['message']) ?></div>
                <div class="notification-time">
                    <i class="far fa-clock"></i> <?= date('M d, Y h:i A', strtotime($notif['created_at'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="far fa-bell-slash"></i>
            <p>No notifications yet</p>
            <p style="font-size: 14px;">When you receive notifications, they will appear here</p>
        </div>
    <?php endif; ?>
</div>

<script>
    function markAsRead(notificationID) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'mark_read=1&notification_id=' + notificationID
        }).then(() => {
            window.location.reload();
        });
    }
    
    // Mark as read when clicking on notification card
    document.querySelectorAll('.notification-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-form')) {
                return;
            }
            const deleteForm = this.querySelector('.delete-form');
            const notifId = deleteForm.querySelector('input[name="notification_id"]').value;
            markAsRead(notifId);
        });
    });
</script>

</body>
</html>