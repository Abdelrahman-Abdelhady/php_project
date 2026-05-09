<?php 
/**
 * Payout View Page
 * Handles the user interface for withdrawal requests.
 */ 

$available_balance = 0.00; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Payout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; }

        .side-drawer {
            height: 100vh; width: 0; position: fixed; z-index: 2000; top: 0; left: 0;
            background-color: #000; overflow-x: hidden; transition: 0.5s; padding-top: 3rem;
        }

        .side-drawer a {
            padding: 1rem 2rem; text-decoration: none; font-size: 1rem;
            color: #fff; display: flex; align-items: center;
            border-bottom: 1px solid #111;
        }

        .side-drawer a:hover { background: #fff; color: #000; }

        .page-wrapper {
            background-color: white;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .navbar {
            padding: 1rem 3%;
            display: flex;
            justify-content: space-between;
        }

        .site-title { font-weight: bold; color: #4F5D95; }

        .main-content { padding: 2rem 5vw; }

        .card-box {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .method-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .method-card:hover { background: #f8f9fa; }

        .method-card.active {
            border-color: #4F5D95;
            background: #eef1f9;
            color: #4F5D95;
            font-weight: bold;
        }

        .btn-custom {
            background: #4F5D95;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 30px;
            width: 100%;
            font-weight: 600;
        }

        .btn-custom:hover { background: #3b4675; }

        .form-label { color: #4F5D95; font-weight: bold; }
    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sideDrawer" class="side-drawer">
    <a href="javascript:void(0)" onclick="toggleSidebar()">Close ×</a>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="spots.php"><i class="fas fa-parking"></i> My Spots</a>
    <a href="earnings.php"><i class="fas fa-wallet"></i> Earnings</a>
    <a href="reviews.php"><i class="fas fa-star"></i> Reviews</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
</div>

<!-- Navbar -->
<div class="page-wrapper">
    <nav class="navbar">
        <div onclick="toggleSidebar()" style="cursor:pointer;">☰</div>
        <div class="site-title">CitySlot 🚘</div>
    </nav>
</div>

<!-- Page -->
<div class="container mt-5" style="max-width:700px">

    <h3 class="mb-4 fw-bold">Withdraw Money</h3>

    <!-- 🚨 ERROR MESSAGE -->
    <?php if(isset($_GET['error']) && $_GET['error'] == 'balance'): ?>
        <div class="alert text-center shadow-sm"
             style="border-radius:15px;background:linear-gradient(135deg,#ffdddd,#fff5f5);
             border-left:6px solid #e74c3c;color:#c0392b;font-weight:600;">
            <i class="fas fa-wallet"></i>
            Sorry! Insufficient balance in your wallet.
        </div>
    <?php endif; ?>

    <!-- ✅ SUCCESS MESSAGE -->
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert text-center shadow-sm"
             style="border-radius:15px;background:linear-gradient(135deg,#ddffdf,#f0fff2);
             border-left:6px solid #28a745;color:#1e7e34;font-weight:600;">
            <i class="fas fa-check-circle"></i>
            Withdrawal request submitted successfully!
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card-box">

        <form action="payout_handler.php" method="POST">

            <label class="form-label">Withdrawal Amount ($)</label>
            <input type="number" name="amount" class="form-control mb-4" step="0.01" required>

            <input type="hidden" name="method" id="methodInput" required>

            <label class="form-label">Select Payment Method</label>

            <div class="row mb-4">
                <div class="col-6">
                    <div class="method-card text-center" onclick="selectMethod(this)">
                        <i class="fas fa-university"></i><br>Bank Transfer
                    </div>
                </div>

                <div class="col-6">
                    <div class="method-card text-center" onclick="selectMethod(this)">
                        <i class="fas fa-mobile-alt"></i><br>Vodafone Cash
                    </div>
                </div>
            </div>

            <label class="form-label">Account Info</label>
            <input type="text" name="account_info" class="form-control mb-4" required>

            <button class="btn-custom">Confirm Withdrawal</button>

        </form>

    </div>
</div>

<script>
function toggleSidebar(){
    const d = document.getElementById("sideDrawer");
    d.style.width = (d.style.width === "260px") ? "0" : "260px";
}

function selectMethod(el){
    document.querySelectorAll('.method-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById("methodInput").value = el.innerText.trim();
}
</script>

</body>
</html>