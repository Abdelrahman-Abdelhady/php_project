<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$user = $_SESSION['user'] ?? null;

/* fallback image */
$userPic = $user['profile_pic'] ?? "https://via.placeholder.com/130?text=User";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CitySlot - Profile Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    font-family: 'Segoe UI';
    background: #f4f7f6;
    margin: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ===== SIDEBAR (SAME DASHBOARD) ===== */
.side-drawer {
    height: 100vh;
    width: 0;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #000000;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 3rem;
    z-index: 2000;
}

.side-drawer a {
    padding: 1rem 2rem;
    text-decoration: none;
    font-size: 1rem;
    color: #ffffff;
    display: flex;
    align-items: center;
    transition: 0.3s;
    border-bottom: 1px solid #111;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.side-drawer a i {
    margin-right: 15px;
    width: 20px;
    text-align: center;
    color: #888;
}

.side-drawer a:hover {
    background-color: #ffffff;
    color: #000000;
}

.side-drawer a:hover i {
    color: #000000;
}

.close-btn {
    color: #666 !important;
    font-size: 0.8rem !important;
    cursor: pointer;
    padding: 1rem 2rem;
}

/* NAVBAR */
.navbar {
    background: #fff;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
}

.site-title {
    font-weight: bold;
    color: #4F5D95;
}

/* CONTENT */
.main-content {
    padding: 2rem;
}

.profile-header {
    background: #4F5D95;
    color: #fff;
    padding: 3rem 2rem;
    text-align: center;
    border-radius: 1rem 1rem 0 0;
}

.profile-img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
}

.settings-card {
    background: #fff;
    padding: 2rem;
    border-radius: 1rem;
    margin-top: -2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.save-btn {
    background: #4F5D95;
    color: #fff;
    border: none;
    padding: 0.8rem 2.5rem;
    border-radius: 2rem;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div id="sideDrawer" class="side-drawer">

    <a href="javascript:void(0)" class="close-btn" onclick="toggleSidebar()">
        Close Menu ×
    </a>

    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="spots.php"><i class="fas fa-parking"></i> My Spots</a>
    <a href="earnings.php"><i class="fas fa-wallet"></i> Earnings</a>
    <a href="reviews.php"><i class="fas fa-star"></i> Reviews</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>

</div>

<!-- NAVBAR -->
<div class="navbar">
    <div onclick="toggleSidebar()" style="cursor:pointer;">☰</div>
    <div class="site-title">CitySlot 🚘</div>
</div>

<!-- CONTENT -->
<div class="main-content container" style="max-width:850px;">

<!-- SUCCESS MESSAGE -->
<?php if(isset($_GET['msg']) && $_GET['msg']=='success'): ?>
    <div class="alert alert-success text-center">
        Profile updated successfully 🎉
    </div>
<?php endif; ?>

<?php if(isset($_GET['msg']) && $_GET['msg']=='error'): ?>
    <div class="alert alert-danger text-center">
        Something went wrong ❌
    </div>
<?php endif; ?>

<!-- PROFILE HEADER -->
<div class="profile-header">

    <img src="<?php echo $userPic; ?>" class="profile-img mb-2">

    <h4><?php echo $user['name'] ?? 'Owner'; ?></h4>
    <p class="text-white-50">Verified Parking Provider</p>

</div>

<!-- FORM -->
<div class="settings-card">

<form action="/php_project/app/controllers/OwnerController.php?action=updateProfile"
      method="POST"
      enctype="multipart/form-data">

    <div class="mb-3">
        <label>Profile Image</label>
        <input type="file" name="profile_pic" class="form-control">
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control"
                   value="<?php echo $user['name'] ?? ''; ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="<?php echo $user['phone'] ?? ''; ?>">
        </div>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="<?php echo $user['email'] ?? ''; ?>">
    </div>

    <div class="mb-4">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control">
    </div>

    <div class="text-center">
        <button class="save-btn">Save Changes</button>
    </div>

</form>

</div>
</div>

<script>
function toggleSidebar(){
    const d = document.getElementById("sideDrawer");
    d.style.width = (d.style.width === "260px") ? "0" : "260px";
}
</script>

</body>
</html>