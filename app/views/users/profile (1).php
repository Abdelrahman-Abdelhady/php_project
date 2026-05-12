<?php
session_start();

// --- 1. Auth check ---
if (!isset($_SESSION['userID'])) {
    header("Location: /php_project/public/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/controllers/UserController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/UserModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/vehicleModel.php';

if (!defined('BASE_URL')) define('BASE_URL', '/php_project/');

$user_id = $_SESSION['userID'];

$controller = new UserController();
$user = $controller->userModel->getUserById($user_id);

if (!$user) {
    session_destroy();
    header("Location: /php_project/public/login.php");
    exit();
}

// --- Fetch vehicles from DB ---
$vehicleModel = new Vehicle();
$vehicles = $vehicleModel->getUserVehicles($user_id);

// --- Reservation count (optional stat) ---
$db = Database::getInstance()->getConnection();
$resCount = 0;
$stmt = $db->prepare("SELECT COUNT(*) AS c FROM reservation WHERE userID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resCount = (int)$stmt->get_result()->fetch_assoc()['c'];

// --- Member since (optional) ---
$memberSince = '';
$stmt = $db->prepare("SELECT created_at FROM users WHERE userID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if ($row && !empty($row['created_at'])) {
    $memberSince = date('M Y', strtotime($row['created_at']));
}

// --- Delete vehicle handler ---
if (isset($_GET['delete_vehicle'])) {
    $vid = (int)$_GET['delete_vehicle'];
    $stmt = $db->prepare("DELETE FROM vehicle WHERE vehicleID = ? AND userID = ?");
    $stmt->bind_param("ii", $vid, $user_id);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CitySlot — Profile</title>
<meta name="description" content="Manage your account and vehicles on CitySlot">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  *{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins','Cairo',sans-serif}
  body{background:#f4f6fa;color:#1f2937;min-height:100vh;padding:20px}
  .container{max-width:720px;margin:0 auto}
  .nav{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:14px 20px;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,.05);margin-bottom:20px}
  .brand{font-weight:800;font-size:1.2rem;color:#2563eb}
  .card{background:#fff;border-radius:16px;padding:22px;box-shadow:0 4px 18px rgba(0,0,0,.06);margin-bottom:18px}
  .profile-head{display:flex;align-items:center;gap:18px;margin-bottom:18px}
  .avatar{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;overflow:hidden}
  .avatar img{width:100%;height:100%;object-fit:cover}
  .pinfo h2{font-size:1.3rem;margin-bottom:4px}
  .pinfo .email{color:#6b7280;font-size:.9rem}
  .pinfo .since{color:#9ca3af;font-size:.8rem;margin-top:4px}
  .stats{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:18px}
  .stat{background:#f9fafb;padding:14px;border-radius:12px;text-align:center}
  .stat .num{font-size:1.4rem;font-weight:800;color:#2563eb}
  .stat .lbl{font-size:.8rem;color:#6b7280;margin-top:2px}
  .btn{background:#2563eb;color:#fff;border:none;padding:11px 22px;border-radius:10px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block;font-size:.9rem;transition:.2s}
  .btn:hover{background:#1d4ed8}
  .btn-edit{width:100%;margin-top:18px;padding:13px;font-size:1rem}
  .sec-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
  .sec-head h3{font-size:1.1rem}
  .vlist{display:flex;flex-direction:column;gap:12px}
  .vcard{display:flex;justify-content:space-between;align-items:center;padding:14px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:12px}
  .vcard .vinfo .vplate{font-weight:700;font-size:1rem}
  .vcard .vinfo .vmeta{color:#6b7280;font-size:.85rem;margin-top:3px}
  .vcard .actions{display:flex;gap:6px}
  .icon-btn{background:#fff;border:1px solid #e5e7eb;padding:6px 10px;border-radius:8px;cursor:pointer;text-decoration:none;font-size:.95rem}
  .icon-btn.del:hover{background:#fee2e2;border-color:#fca5a5}
  .empty{color:#6b7280;text-align:center;padding:24px;border:2px dashed #e5e7eb;border-radius:12px}
  .badge{display:inline-block;background:#dbeafe;color:#1d4ed8;padding:2px 10px;border-radius:999px;font-size:.7rem;font-weight:600;margin-left:6px}
  /* Modal */
  .overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;padding:20px;z-index:50}
  .overlay.show{display:flex}
  .modal{background:#fff;border-radius:16px;padding:24px;max-width:520px;width:100%;max-height:90vh;overflow-y:auto}
  .mhead{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
  .mhead h3{font-size:1.2rem}
  .close{background:none;border:none;font-size:1.6rem;cursor:pointer;color:#6b7280}
  label{display:block;font-weight:600;font-size:.85rem;margin-bottom:5px;color:#374151}
  input,select{width:100%;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.9rem;background:#f9fafb;outline:none;margin-bottom:12px}
  input:focus,select:focus{border-color:#2563eb;background:#fff}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .check{display:flex;align-items:center;gap:8px;margin-bottom:14px}
  .check input{width:auto;margin:0}
  .mactions{display:flex;gap:10px;margin-top:8px}
  .btn-secondary{background:#f3f4f6;color:#374151}
  .btn-secondary:hover{background:#e5e7eb}
</style>
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="brand">🚘 CitySlot</div>
    <a href="/php_project/public/logout.php" style="color:#ef4444;text-decoration:none;font-weight:600">Logout</a>
  </div>

  <!-- Profile Card -->
  <div class="card">
    <div class="profile-head">
      <div class="avatar">
        <?php if (!empty($user['profile_pic'])): ?>
          <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Avatar">
        <?php else: echo strtoupper(substr($user['name'], 0, 2)); endif; ?>
      </div>
      <div class="pinfo">
        <h2><?= htmlspecialchars($user['name']) ?></h2>
        <div class="email"><?= htmlspecialchars($user['email']) ?></div>
        <?php if ($memberSince): ?>
          <div class="since">Member since <?= htmlspecialchars($memberSince) ?></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="stats">
      <div class="stat">
        <div class="num" style="text-transform:capitalize"><?= htmlspecialchars($user['role']) ?></div>
        <div class="lbl">Account Type</div>
      </div>
      <div class="stat">
        <div class="num"><?= $resCount ?></div>
        <div class="lbl">Reservations</div>
      </div>
    </div>

    <a href="edit_profile.php" class="btn btn-edit" style="text-align:center">Edit Profile</a>
  </div>

  <!-- Vehicles -->
  <div class="card">
    <div class="sec-head">
      <h3>My Vehicles</h3>
      <button class="btn" onclick="openModal()">+ Add Vehicle</button>
    </div>

    <?php if (empty($vehicles)): ?>
      <div class="empty">No vehicles found. Add your first vehicle to start booking!</div>
    <?php else: ?>
      <div class="vlist">
        <?php foreach ($vehicles as $v): ?>
          <div class="vcard">
            <div class="vinfo">
              <div class="vplate">
                <?= htmlspecialchars($v['licensePlate']) ?>
                <?php if (!empty($v['is_default'])): ?><span class="badge">Default</span><?php endif; ?>
              </div>
              <div class="vmeta">
                <?= htmlspecialchars($v['model']) ?> · <?= htmlspecialchars($v['color']) ?> · <?= htmlspecialchars($v['type']) ?>
              </div>
            </div>
            <div class="actions">
              <a class="icon-btn del" href="?delete_vehicle=<?= (int)$v['vehicleID'] ?>"
                 onclick="return confirm('Delete this vehicle?')">🗑️</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add Vehicle Modal -->
<div class="overlay" id="modal">
  <div class="modal">
    <div class="mhead">
      <h3>Add Vehicle</h3>
      <button class="close" onclick="closeModal()">×</button>
    </div>
    <form method="POST" action="/php_project/app/controllers/vehicleController.php">
      <label>Plate Number</label>
      <input type="text" name="licensePlate" required maxlength="20">

      <label>Model</label>
      <input type="text" name="model" required maxlength="50" placeholder="e.g. Toyota Corolla">

      <label>Color</label>
      <input type="text" name="color" required maxlength="20">

      <label>Vehicle Type</label>
      <select name="type" required>
        <option value="Sedan">Sedan</option>
        <option value="SUV">SUV</option>
        <option value="Hatchback">Hatchback</option>
        <option value="Motorcycle">Motorcycle</option>
      </select>

      <div class="row">
        <div>
          <label>Height (m)</label>
          <input type="number" step="0.01" name="height" required min="0.5" max="5">
        </div>
        <div>
          <label>Width (m)</label>
          <input type="number" step="0.01" name="width" required min="0.5" max="5">
        </div>
      </div>

      <div class="check">
        <input type="checkbox" name="is_default" id="is_default" value="1">
        <label for="is_default" style="margin:0">Set as default vehicle</label>
      </div>

      <div class="mactions">
        <button type="button" class="btn btn-secondary" style="flex:1" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn" style="flex:1">Save Vehicle</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal(){ document.getElementById('modal').classList.add('show'); }
  function closeModal(){ document.getElementById('modal').classList.remove('show'); }
  document.getElementById('modal').addEventListener('click', e => {
    if (e.target.id === 'modal') closeModal();
  });
</script>
</body>
</html>
