<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentIt - Marketplace</title>
    <style>
        
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f6;
    margin: 0;
    display: flex;
    flex-direction: column; 
    min-height: 100vh; 
    font-size: 100%; 
}


.page-wrapper {
    background-color: white;
    width: 100%;
    border-bottom: 0.06rem solid #eee; 
    box-shadow: 0 0.125rem 0.3rem rgba(0,0,0,0.05);
}

.navbar {
    width: 100%; 
    padding: 1rem 3%; 
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-sizing: border-box; 
}


.nav-section { display: flex; flex-direction: column; }
.nav-left { align-items: flex-start; }
.nav-center { align-items: center; flex-grow: 1; }
.nav-right { flex-direction: row; align-items: center; gap: 1rem; }

.logo { font-size: 1.5rem; }
.site-title { font-size: 0.8rem; font-weight: bold; color: #666; }

.search-bar {
    width: 90%; 
    max-width: 25rem; 
    padding: 0.5rem 1rem;
    border-radius: 2rem; 
    border: 0.06rem solid #ccc;
    margin-bottom: 0.3rem;
}

.nav-links a {
    margin: 0 0.6rem;
    text-decoration: none;
    font-size: 0.85rem;
    color: #333;
}


.main-content {
    flex-grow: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 5vh 5vw; 
}

.card {
    background: white;
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 1.25rem rgba(0,0,0,0.1);
    text-align: center;
    border-top: 0.3rem solid #4F5D95;
    width: 100%;
    max-width: 25rem;
}


.side-drawer {
    height: 100vh;
    width: 0;
    position: fixed;
    z-index: 2000;
    top: 0;
    right: 0;
    background-color: #111;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 4rem;
}

.side-drawer a {
    padding: 1rem 2rem;
    text-decoration: none;
    font-size: 1.2rem;
    color: white;
    display: block;
}

.menu-icon { font-size: 1.5rem; cursor: pointer; }
    </style>
</head>
<body>

    <div id="sideDrawer" class="side-drawer">
        <a href="javascript:void(0)" onclick="toggleSidebar()" style="color:red">Close ×</a>
        <a href="#">My Profile</a>
        <a href="#">My Rentals</a>
        <a href="#">Settings</a>
    </div>

    <div class="page-wrapper">
        <nav class="navbar">
            <div class="nav-section nav-left">
                <div class="logo">🚘</div>
                <span class="site-title">RentIt</span>
            </div>

            <div class="nav-section nav-center">
                <input type="text" class="search-bar" placeholder="Search listings...">
                <div class="nav-links">
                    <a href="#">Browse</a>
                    <a href="#">Categories</a>
                    <a href="http://localhost/Project/app/views/home/contactUS.html">Contact us</a>
                </div>
            </div>

            <div class="nav-section nav-right">
                <button>Login</button>
                <div class="menu-icon" onclick="toggleSidebar()">☰</div>
            </div>
        </nav>
    </div>

   <main class="main-content">
        <div class="card">
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
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const drawer = document.getElementById("sideDrawer");
            drawer.style.width = (drawer.style.width === "250px") ? "0" : "250px";
        }
    </script>

</body>
</html>