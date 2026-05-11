<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" Content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Marketplace</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/home.css">

</head>
<body>

    <?php require_once "../app/views/layout/header.php"; ?>

   <main class="main-Content">
    
        <!-- <div class="card">
            <?php
                // Target Date: May 12, 2026
                $targetDate = strtotime("2026-05-12 00:00:00");
                $today = time();
                $difference = $targetDate - $today;

                if ($difference < 0) {
                    echo "<h1>Project Launched!</h1>";
                } else {
                    // Calculating the days, hours, minutes, and seconds
                    $days = floor($difference / (60 * 60 * 24));
                    $hours = floor(($difference % (60 * 60 * 24)) / (60 * 60));
                    $minutes = floor(($difference % (60 * 60)) / 60);
                    $seconds = $difference % 60;

                    echo "<h1>PHP is working magda!!</h1>";
                    echo "<p>Time remaining until launch:</p>";
                    echo "<div class='time-box'>";
                    printf("%02dd %02dh %02dm %02ds", $days, $hours, $minutes, $seconds);
                    echo "</div>";
                }
            ?>
            <p style="margin-top: 15px; font-size: 0.8rem;">(Refresh page to update timer)</p>
        </div> -->
        <main class="main-content">
    <div class="listings-grid">
        
    

    
    <!-- display all active listings by  -->
    <?php foreach ($listings as $listing): ?>
        <?php require "../app/views/layout/listingCard.php"; ?>
    <?php endforeach; ?>



    </div>
    <?php if (Auth::check()): ?>
    <h2>Welcome back, <?= htmlspecialchars(Auth::user()['name']) ?>!</h2>
    <p>Your role is: <?= htmlspecialchars(Auth::user()['role']) ?></p>
    <a href="<?= BASE_URL ?>Auth/logout">Logout</a>
<?php else: ?>
    <h2>Welcome, Guest!</h2>
    <p>Please <a href="<?= BASE_URL ?>Auth/login">Login</a> or <a href="<?= BASE_URL ?>Auth/register">Register</a>.</p>
<?php endif; ?>

    
</main>
    </main>

    
<footer>
    
<?php require_once "../app/views/layout/footer.php"; ?>

</footer>
<script>
    // refresh to prevent geting cahed pages
    window.addEventListener("pageshow", function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
</body> 
</html>