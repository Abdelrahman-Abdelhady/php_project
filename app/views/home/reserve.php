<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/ReservationModel.php';

// Require login or use test user 1 when testing
$isTestUser = false;
$isTestSpot = false;
$errors = [];

if (!isset($_SESSION['userID'])) {
    $db_temp = Database::getInstance()->getConnection();
    $test_user = $db_temp->query("SELECT userID FROM users WHERE userID = 1 LIMIT 1");
    if ($test_user && $test_user->num_rows > 0) {
        $_SESSION['userID'] = $test_user->fetch_assoc()['userID'];
        $isTestUser = true;
    } else {
        $test_user = $db_temp->query("SELECT userID FROM users LIMIT 1");
        if ($test_user && $test_user->num_rows > 0) {
            $_SESSION['userID'] = $test_user->fetch_assoc()['userID'];
            $isTestUser = true;
            $errors[] = "Test user 1 not found, using first available user from database.";
        } else {
            $errors[] = "No users found in the database. لا يوجد مستخدم في قاعدة البيانات.";
        }
    }
}

$userID = $_SESSION['userID'] ?? null;
$db = Database::getInstance()->getConnection();

// Get spotID from POST/GET or use first available available spot for testing
$spotID = isset($_POST['spotID']) ? (int)$_POST['spotID'] : (isset($_GET['spotID']) ? (int)$_GET['spotID'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0));
if ($spotID <= 0) {
    $spotRow = $db->query("SELECT spotID FROM spot WHERE status = 'available' LIMIT 1");
    if ($spotRow && $spotRow->num_rows > 0) {
        $spotID = (int)$spotRow->fetch_assoc()['spotID'];
        $isTestSpot = true;
    }
}

$spot = null;
if ($spotID <= 0) {
    $errors[] = "Invalid or missing spot. لا يوجد مكان متاح حاليًا.";
} else {
    $stmt = $db->prepare("SELECT * FROM spot WHERE spotID = ?");
    $stmt->bind_param("i", $spotID);
    $stmt->execute();
    $spot = $stmt->get_result()->fetch_assoc();
    if (!$spot) {
        $errors[] = "Spot not found. المكان غير موجود.";
    }
}

$pricePerHour = isset($spot['price_per_hour']) ? (float)$spot['price_per_hour'] : 20.0;
$success = false;
$reservationID = null;

if (isset($_GET['success']) && $_GET['success'] === '1') {
    $success = true;
    $reservationID = isset($_GET['reservationID']) ? (int)$_GET['reservationID'] : null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName    = trim($_POST['full_name'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $plateNumber = trim($_POST['plate_number'] ?? '');
    $date        = trim($_POST['date'] ?? '');
    $startTime   = trim($_POST['start_time'] ?? '');
    $duration    = (int)($_POST['duration'] ?? 0);

    // Validation
    if ($fullName === '' || mb_strlen($fullName) > 100) $errors[] = "Invalid full name.";
    if (!preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) $errors[] = "Invalid phone number.";
    if ($plateNumber === '' || mb_strlen($plateNumber) > 20) $errors[] = "Invalid plate number.";
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $errors[] = "Invalid date.";
    if (!preg_match('/^\d{2}:\d{2}$/', $startTime)) $errors[] = "Invalid start time.";
    if (!in_array($duration, [1,2,3,4,6,12,24], true)) $errors[] = "Invalid duration.";

    if (empty($errors)) {
        $startDateTime = $date . ' ' . $startTime . ':00';
        $startTs = strtotime($startDateTime);
        if ($startTs === false) {
            $errors[] = "Invalid start datetime.";
        } else {
            $endDateTime = date('Y-m-d H:i:s', $startTs + $duration * 3600);
            $totalCost = $pricePerHour * $duration;

            // Check spot is available
            if (isset($spot['status']) && $spot['status'] !== 'available') {
                $errors[] = "This spot is not available.";
            } else {
                // Check overlapping reservations
                $check = $db->prepare(
                    "SELECT COUNT(*) AS cnt FROM reservation
                     WHERE spotID = ? AND status = 'active'
                     AND NOT (endTime <= ? OR startTime >= ?)"
                );
                $check->bind_param("iss", $spotID, $startDateTime, $endDateTime);
                $check->execute();
                $cnt = (int)$check->get_result()->fetch_assoc()['cnt'];

                if ($cnt > 0) {
                    $errors[] = "This spot is already reserved for the selected time.";
                } else {
                    // Save plate/phone on user profile if columns exist (optional)
                    @$db->query("UPDATE users SET phone_num='" . $db->real_escape_string($phone) . "' WHERE userID=" . (int)$userID);

                    $model = new ReservationModel();
                    $reservationID = $model->createReservation($userID, $spotID, $startDateTime, $endDateTime, $totalCost);

                    if ($reservationID) {
                        // Optional: store plate/name details in a reservation_details table if exists
                        @$db->query("INSERT INTO reservation_details (reservationID, full_name, plate_number)
                                     VALUES ($reservationID, '" . $db->real_escape_string($fullName) . "',
                                                            '" . $db->real_escape_string($plateNumber) . "')");
                        header("Location: reserve.php?spotID=" . urlencode($spotID) . "&success=1&reservationID=" . urlencode($reservationID));
                        exit;
                    } else {
                        $errors[] = "Failed to create reservation. Please try again.";
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CitySlot — Reserve Spot</title>
<meta name="description" content="Book your spot in simple steps">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  *{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins','Cairo',sans-serif}
  body{background:#f4f6fa;color:#1f2937;min-height:100vh;padding:20px}
  .container{max-width:680px;margin:0 auto}
  .nav{display:flex;justify-content:space-between;align-items:center;background:#fff;padding:14px 20px;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,.05);margin-bottom:20px}
  .brand{font-weight:800;font-size:1.2rem;color:#2563eb}
  .back{display:inline-block;color:#2563eb;text-decoration:none;margin-bottom:16px;font-weight:600}
  .card{background:#fff;border-radius:16px;padding:22px;box-shadow:0 4px 18px rgba(0,0,0,.06);margin-bottom:18px}
  .card h2{font-size:1.2rem;margin-bottom:10px}
  .price{color:#16a34a;font-weight:700;margin-top:6px}
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .form-grid .full{grid-column:1/-1}
  label{display:block;font-weight:600;font-size:.9rem;margin-bottom:6px;color:#374151}
  input,select{width:100%;padding:11px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.95rem;background:#f9fafb;outline:none;transition:.2s}
  input:focus,select:focus{border-color:#2563eb;background:#fff}
  .total{display:flex;justify-content:space-between;align-items:center;margin-top:16px;padding-top:16px;border-top:1px dashed #e5e7eb;font-weight:700;font-size:1.1rem}
  .total span:last-child{color:#16a34a;font-size:1.3rem}
  .btn{width:100%;background:#2563eb;color:#fff;border:none;padding:14px;border-radius:12px;font-weight:700;font-size:1rem;cursor:pointer;margin-top:18px;transition:.2s}
  .btn:hover{background:#1d4ed8}
  .alert{padding:12px 14px;border-radius:10px;margin-bottom:14px;font-size:.9rem}
  .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
  .success{text-align:center;padding:30px}
  .success .icon{font-size:4rem;margin-bottom:10px}
  .success h2{color:#16a34a;margin-bottom:8px}
  @media(max-width:520px){.form-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="brand">🚘 CitySlot</div>
    <a href="?lang=ar" style="text-decoration:none;color:#6b7280">العربية</a>
  </div>
  <?php if (!empty($isTestUser) || !empty($isTestSpot)): ?>
  <div class="card" style="background:#fef3c7;color:#92400e;">
    <p style="margin:0;font-size:.95rem;line-height:1.5;">
      وضع الاختبار مفعل: <?php echo !empty($isTestUser) ? "استخدمنا userID = $userID." : ""; ?>
      <?php echo !empty($isTestSpot) ? "استخدمنا spotID = $spotID." : ""; ?>
    </p>
  </div>
  <?php endif; ?>

  <a class="back" href="javascript:history.back()">← Back to details</a>

  <?php if ($success): ?>
    <div class="card success">
      <div class="icon">✅</div>
      <h2>Reservation Confirmed!</h2>
      <p style="color:#6b7280">Reservation #<?= htmlspecialchars($reservationID) ?> — We'll contact you soon to confirm.</p>
      <a class="btn" style="display:inline-block;text-decoration:none;margin-top:18px;width:auto;padding:12px 24px"
         href="/php_project/app/views/driver/reservations.php">View My Reservations</a>
    </div>
  <?php elseif (!$spot || !$userID): ?>
    <div class="card alert alert-error">
      <h2>لا يمكن عرض صفحة الحجز</h2>
      <?php foreach ($errors as $e): ?>
        <p><?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php else: ?>

    <div class="card">
      <h2><?= htmlspecialchars($spot['location']) ?></h2>
      <div style="color:#6b7280;margin-top:4px">📍 <?= htmlspecialchars($spot['zone'] ?? '') ?></div>
      <div class="price"><?= number_format($pricePerHour, 0) ?> EGP / hour</div>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $e) echo "• " . htmlspecialchars($e) . "<br>"; ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="card">
      <h2 style="margin-bottom:16px">Reservation Details</h2>
      <div class="form-grid">
        <div class="full">
          <label>Full Name</label>
          <input type="text" name="full_name" required maxlength="100" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
        </div>
        <div>
          <label>Phone</label>
          <input type="tel" name="phone" required pattern="[0-9+\-\s]{6,20}" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div>
          <label>Plate Number</label>
          <input type="text" name="plate_number" required maxlength="20" value="<?= htmlspecialchars($_POST['plate_number'] ?? '') ?>">
        </div>
        <div>
          <label>Date</label>
          <input type="date" name="date" id="date" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>">
        </div>
        <div>
          <label>Start Time</label>
          <input type="time" name="start_time" id="start_time" required value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>">
        </div>
        <div class="full">
          <label>Duration (hours)</label>
          <select name="duration" id="duration" required>
            <?php foreach ([1,2,3,4,6,12,24] as $h): ?>
              <option value="<?= $h ?>" <?= (($_POST['duration'] ?? 2) == $h) ? 'selected' : '' ?>><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="total">
        <span>Total</span>
        <span><span id="total"><?= number_format($pricePerHour * (int)($_POST['duration'] ?? 2), 0) ?></span> EGP</span>
      </div>

        <input type="hidden" name="spotID" value="<?= htmlspecialchars($spotID) ?>">
      <button type="submit" class="btn">Confirm Reservation</button>
    </form>

    <script>
      const price = <?= json_encode($pricePerHour) ?>;
      const dur = document.getElementById('duration');
      const total = document.getElementById('total');
      dur.addEventListener('change', () => {
        total.textContent = (price * parseInt(dur.value)).toFixed(0);
      });
    </script>

  <?php endif; ?>
</div>
</body>
</html>
