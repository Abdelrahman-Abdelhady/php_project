<?php
session_start();

// --- 1. التأكد من تسجيل الدخول ---
if (!isset($_SESSION['userID'])) {
    // لو مش مسجل دخول، ابعته لصفحة اللوج ان
    header("Location: /php_project/public/login.php"); 
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/controllers/UserController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/UserModel.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/php_project/');
}

$user_id = $_SESSION['userID']; 

$controller = new UserController();
$user = $controller->userModel->getUserById($user_id);

// لو اليوزر مش موجود في الداتا بيز (حالة نادرة)
if (!$user) {
    session_destroy();
    header("Location: /php_project/public/login.php");
    exit();
}

$vehicles = []; 
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CitySlot — Profile</title>
  <meta name="description" content="Manage your account and vehicles on CitySlot">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>


body{font-family:Arial,sans-serif;background:#f4f8fc;margin:0;padding:0}
.profile-page{max-width:1100px;margin:auto;padding:40px 20px}
.profile-card{background:#fff;padding:25px;border-radius:16px;margin-bottom:25px;box-shadow:0 5px 15px rgba(0,0,0,.08)}
.card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.btn-add{background:#0F2854;color:#fff;border:none;padding:10px 18px;border-radius:30px;cursor:pointer;font-weight:700} 
.vehicles-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}
.vehicle-card{background:#f9fbff;border:1px solid #dce6f0;border-radius:16px;padding:20px;display:flex;gap:15px;transition:.3s}
.vehicle-card:hover{transform:translateY(-4px)}
.vehicle-icon{width:60px;height:60px;border-radius:12px;background:#0F2854;display:flex;justify-content:center;align-items:center;font-size:28px;color:#fff;flex-shrink:0}
.vehicle-body{flex:1}.vehicle-top{display:flex;align-items:center;gap:10px;margin-bottom:5px}.vehicle-top h3{margin:0;color:#0F2854}
.badge-default{background:orange;color:#fff;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700}.vehicle-sub{color:#666;margin-bottom:8px}
.vehicle-plate{display:inline-block;background:#0F2854;color:#fff;padding:6px 12px;border-radius:8px;font-weight:700}
.vehicle-actions{display:flex;flex-direction:column;gap:8px}
.btn-icon{width:35px;height:35px;border:none;border-radius:8px;cursor:pointer;background:#e9eef7;display:grid;place-items:center}
.danger{background:#ffdddd}.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;justify-content:center;align-items:center;z-index:100}
.modal-overlay[hidden]{display:none}.modal{background:#fff;width:100%;max-width:500px;border-radius:20px;padding:25px}
.modal-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}.btn-close{border:none;background:none;font-size:25px;cursor:pointer}
.modal-body{display:flex;flex-direction:column;gap:15px}.form-row{display:flex;flex-direction:column;gap:5px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}input,select{padding:10px;border-radius:10px;border:1px solid #ccc;font-family:inherit}
.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:10px}.btn-primary{background:#0F2854;color:#fff;border:none;padding:10px 18px;border-radius:25px;cursor:pointer}
.btn-ghost-bordered{background:#fff;border:1px solid #ccc;padding:10px 18px;border-radius:25px;cursor:pointer}
</style>

</head>
<body>

  <main class="profile-page">
    <section class="profile-hero">
      <div class="profile-avatar">
          <?php 
            if (!empty($user['profile_pic'])) {
                echo '<img src="'.BASE_URL.'public/uploads/'.$user['profile_pic'].'" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">';
            } else {

                echo strtoupper(substr($user['name'], 0, 2)); 
            }
          ?>
      </div>
      
      <div class="profile-meta">
        <h1 class="profile-name">
          <?= htmlspecialchars($user['name']); ?>
        </h1>
        <p class="profile-sub">
          Member since <?= date('F Y', strtotime($user['created_at'])); ?>
        </p>
        
        <div class="profile-stats">
          <div class="stat">
            <strong><?= htmlspecialchars($user['role']); ?></strong>
            Account Type
          </div>
          <div class="stat">
            <strong>24</strong>
            Reservations
          </div>

        </div>
      </div>

      <a href="<?= BASE_URL ?>User/edit/<?= $user['userID'] ?>" class="btn-primary btn-edit" style="text-decoration: none; text-align: center;">
        Edit Profile
      </a>
    </section>

   <section class="profile-card">
    <div class="card-head">
        <h2>My Vehicles</h2>
        <button class="btn-add" id="add-vehicle-btn">
            <span class="plus">+</span> Add Vehicle
        </button>
    </div>

    <div class="vehicles-grid">
        <?php if (!empty($vehicles)): ?>
            <?php foreach ($vehicles as $v): ?>
                <article class="vehicle-card">
                    <div class="vehicle-icon"><?= ($v['type'] == 'motorcycle') ? '🏍️' : '🚗'; ?></div>
                    <div class="vehicle-body">
                        <div class="vehicle-top">
                            <h3><?= htmlspecialchars($v['model'] . ' ' . $v['model']); ?></h3>
                        </div>
                        <p class="vehicle-sub">
                            <?= htmlspecialchars($v['type']); ?> · <?= htmlspecialchars($v['year']); ?> · <?= htmlspecialchars($v['color']); ?>
                        </p>
                        <div class="vehicle-plate"><?= htmlspecialchars($v['plate_number']); ?></div>
                    </div>
                    <div class="vehicle-actions">
                        <a href="<?= BASE_URL ?>Vehicle/edit/<?= $v['vehicleID'] ?>" class="btn-icon">✏️</a>
                        <a href="<?= BASE_URL ?>Vehicle/delete/<?= $v['vehicleID'] ?>" class="btn-icon danger" onclick="return confirm('Are you sure?')">🗑️</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No vehicles found. Add your first vehicle to start booking!</p>
        <?php endif; ?>
    </div>
</section>

<div class="modal-overlay" id="vehicle-modal" hidden>
    <div class="modal">

        <div class="modal-head">
            <h3>
                <?= isset($vehicle) ? 'Edit Vehicle' : 'Add New Vehicle'; ?>
            </h3>

            <button type="button" class="btn-close" id="close-modal">
                ×
            </button>
        </div>

        <form
            class="modal-body"
            action="<?= BASE_URL ?>Vehicle/store"
            method="POST">

            <!-- لو Edit ابعتي الاي دي -->
            <?php if(isset($vehicle)): ?>

                <input
                    type="hidden"
                    name="vehicle_id"
                    value="<?= $vehicle['VehicleID']; ?>">

            <?php endif; ?>


            <!-- Brand -->
            <div class="form-row">

                <label>Brand</label>

                <input
                    type="text"
                    name="brand"
                    placeholder="Toyota, BMW, ..."
                    value="<?= $vehicle['brand'] ?? ''; ?>"
                    required>

            </div>


            <!-- Model -->
            <div class="form-row">

                <label>Model</label>

                <input
                    type="text"
                    name="model"
                    placeholder="Corolla, X5, ..."
                    value="<?= $vehicle['model'] ?? ''; ?>"
                    required>

            </div>


            <!-- Year + Color -->
            <div class="form-grid">

                <div class="form-row">

                    <label>Year</label>

                    <input
                        type="number"
                        name="year"
                        min="1990"
                        max="2026"
                        placeholder="2022"
                        value="<?= $vehicle['year'] ?? ''; ?>"
                        required>

                </div>

                <div class="form-row">

                    <label>Color</label>

                    <input
                        type="text"
                        name="color"
                        placeholder="White"
                        value="<?= $vehicle['color'] ?? ''; ?>"
                        required>

                </div>

            </div>


            <!-- Plate -->
            <div class="form-row">

                <label>Plate Number</label>

                <input
                    type="text"
                    name="plate_number"
                    placeholder="ABC · 1234"
                    value="<?= $vehicle['plate_number'] ?? ''; ?>"
                    required>

            </div>


            <!-- Type -->
            <div class="form-row">

                <label>Vehicle Type</label>

                <select name="type" required>

                    <option value="sedan"
                        <?= (isset($vehicle) && $vehicle['type'] == 'sedan') ? 'selected' : ''; ?>>
                        Sedan
                    </option>

                    <option value="suv"
                        <?= (isset($vehicle) && $vehicle['type'] == 'suv') ? 'selected' : ''; ?>>
                        SUV
                    </option>

                    <option value="hatchback"
                        <?= (isset($vehicle) && $vehicle['type'] == 'hatchback') ? 'selected' : ''; ?>>
                        Hatchback
                    </option>

                    <option value="motorcycle"
                        <?= (isset($vehicle) && $vehicle['type'] == 'motorcycle') ? 'selected' : ''; ?>>
                        Motorcycle
                    </option>

                </select>

            </div>


            <!-- Buttons -->
            <div class="modal-actions">

                <button
                    type="button"
                    class="btn-ghost-bordered"
                    id="cancel-modal">

                    Cancel

                </button>

                <button type="submit" class="btn-primary">

                    <?= isset($vehicle) ? 'Update Vehicle' : 'Save Vehicle'; ?>

                </button>

            </div>

        </form>

    </div>
</div>

  <script>
const modal = document.getElementById('vehicle-modal');
document.getElementById('add-vehicle-btn').addEventListener('click', () => modal.hidden = false);
document.getElementById('close-modal').addEventListener('click', () => modal.hidden = true);
document.getElementById('cancel-modal').addEventListener('click', () => modal.hidden = true);
  </script>
</body>
</html>