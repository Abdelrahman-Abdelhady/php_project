<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" Content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Marketplace</title>
    <style>
        
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f6;
    margin: 0;
    display: flex;
    flex-direction: column; 
    min-height: 100vh; 
    font-size: 100%; 
    background:#BDE8F5;
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
    justify-Content: space-between;
    align-items: center;
    box-sizing: border-box;
    background: #1C4D8D;
}


/* 1. Force left and right sections to be equal width */
.nav-left, .nav-right {
    flex: 1; /* This makes them grow equally */
}

/* 2. Ensure the right icons stay aligned to the right */
.nav-right {
    justify-Content: flex-end;
}

/* 3. Keep center Content centered within its own box */
.nav-center {
    flex-grow: 0; /* Stop it from fighting for space */
    flex: 2;      /* Give the middle more priority if needed */
    display: flex;
    flex-direction: column;
    align-items: center;
}

.nav-section { display: flex; flex-direction: column; }
.nav-left { align-items: flex-start; }
.nav-center { align-items: center; flex-grow: 1; }
.nav-right { flex-direction: row; align-items: center; gap: 1rem; }

.Dropdown button{
    background-color: grey;
    color: white;
    padding 10px 15px;
    border: none;
    cursor: pointer;
}

.Dropdown button{ 
background-color: hsl(0, 0%, 80%);
color: white;
padding: 10px 15px;
border: none;
cursor: pointer;
}
.Dropdown a{
display: block;
color: black;
text-decoration: none;
 padding: 10px 15px;
}
.Dropdown .Content{
display: none;
position: absolute;
background-color: hsl(0, 0%, 95%);
min-width: 100px;
box-shadow: 2px 2px 5px ☐hsla(0, 0%, 0%, 0.8);
z-index: 2;
}

.Dropdown:hover .Content
{
    display: block;
}

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


.main-Content {
    flex-grow: 1;
    display: flex;
    justify-Content: center;
    align-items: center;
    padding: 5vh 5vw; 
}

.card {
    background: #4988C4;
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
footer {
    background-color: #0F2854;
    color: white;
    padding: 3rem 5% 1rem;
    margin-top: auto; /* Pushes footer to bottom if Content is short */
}

.footer-container {
    display: flex;
    justify-Content: space-between;
    flex-wrap: wrap;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-section {
    flex: 1;
    min-width: 200px;
}

.footer-section h3 {
    font-size: 1.1rem;
    margin-bottom: 1.2rem;
    color: #ecf0f1;
    border-bottom: 2px solid #4F5D95;
    display: inline-block;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-links a {
    color: #bdc3c7;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: white;
}

.footer-bottom {
    
    text-align: center;
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 0.8rem;
    color: #95a5a6;
    display: flex;
    justify-Content: space-between;
    align-items: center;
}

/* System Health Indicator Styling */
.system-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-dot {
    height: 8px;
    width: 8px;
    background-color: #2ecc71; /* Green for online */
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 8px #2ecc71;
}

.social-icons a {
    font-size: 1.2rem;
    margin-right: 1rem;
    color: white;
    text-decoration: none;
}

/* Container for all listings */
.listings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

/* Individual Card Styling */
.listing-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #eee;
    display: flex;
    flex-direction: column;
}

.listing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Image & Price Badge */
.listing-image-wrapper {
    position: relative;
    height: 180px;
    background-color: #ddd;
}

.listing-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.price-tag {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: #4F5D95;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: bold;
    font-size: 0.9rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* Content Area */
.listing-content {
    padding: 1.25rem;
    flex-grow: 1;
}

.listing-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.listing-header h4 {
    margin: 0;
    font-size: 1.1rem;
    color: #2c3e50;
}

.rating-badge {
    background: #fdf2f2;
    color: #e67e22;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
}

.distance-text {
    font-size: 0.85rem;
    color: #7f8c8d;
    margin-bottom: 15px;
    display: block;
}

/* Specs Chips */
.specs-container {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.spec-chip {
    background: #f0f2f5;
    color: #5d6d7e;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #e1e4e8;
}

/* IoT Live Status */
.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: #27ae60;
    font-weight: 600;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #2ecc71;
    border-radius: 50%;
    position: relative;
}

.pulse-dot::after {
    content: "";
    width: 100%;
    height: 100%;
    background-color: #2ecc71;
    border-radius: 50%;
    position: absolute;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(3); opacity: 0; }
}

/* Buttons */
.card-actions {
    padding: 1.25rem;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 10px;
}

.btn-book {
    flex: 1;
    background: #4F5D95;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-book:hover {
    background: #3a4675;
}

.btn-fav {
    background: white;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
}
    </style>
</head>
<body>

    <div id="sideDrawer" class="side-drawer">
        <a href="javascript:void(0)" onclick="toggleSidebar()" style="color:red">Close ×</a>
        <a href="#">My Profile</a>
        <a href="#">Settings</a>
        <a href="#">My Rentals</a>
        <a href="#">Contact Us</a>
    </div>

    <div class="page-wrapper">
        <nav class="navbar">
            <div class="nav-section nav-left">
                <div class="logo">🚘</div>
                <span class="site-title">CitySlot</span>
            </div>

            <div class="nav-section nav-center">
                <input type="text" class="search-bar" placeholder="Search listings...">
                <!-- <div class="nav-links">
                    <a href="#">Browse</a>
                    <a href="#">Categories</a>
                    <a href="http://localhost/Project/app/views/home/contactUS.html">Contact us</a>
                </div> -->
            </div>

            <div class="nav-section nav-right">
                <div class = "Dropdown">
                <button>Account</button>
                    <div class = "Content">
                    <a href = ""> Login</a>
                    <a href = ""> Signup</a>


                    </div>
                </div>
                <div class="menu-icon" onclick="">🔔</div>
                <div class="menu-icon" onclick="">💵</div>
                <div class="menu-icon" onclick="toggleSidebar()">☰</div>
            </div>
        </nav>
    </div>

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
        
        <!-- Repeat this <div> for each listing -->
        <div class="listing-card">
            <div class="listing-image-wrapper">
                <img src="https://via.placeholder.com/400x250" alt="Parking Space">
                <div class="price-tag">$4.50/hr</div>
            </div>

            <div class="listing-content">
                <div class="listing-header">
                    <h4>Heliopolis - Street 12</h4>
                    <span class="rating-badge">★ 4.8</span>
                </div>
                <span class="distance-text">📍 0.6 km from destination</span>

                <div class="specs-container">
                    <span class="spec-chip">SUV Fit</span>
                    <span class="spec-chip">EV Charging</span>
                    <span class="spec-chip">CCTV</span>
                </div>

                <div class="status-indicator">
                    <div class="pulse-dot"></div>
                    Available Now
                </div>
            </div>

            <div class="card-actions">
                <button class="btn-book">Reserve Spot</button>
                <button class="btn-fav">❤️</button>
            </div>
        </div>
        <!-- End Listing Card -->
          <!-- Repeat this <div> for each listing -->
        <div class="listing-card">
            <div class="listing-image-wrapper">
                <img src="https://via.placeholder.com/400x250" alt="Parking Space">
                <div class="price-tag">$4.50/hr</div>
            </div>

            <div class="listing-content">
                <div class="listing-header">
                    <h4>Heliopolis - Street 12</h4>
                    <span class="rating-badge">★ 4.8</span>
                </div>
                <span class="distance-text">📍 0.6 km from destination</span>

                <div class="specs-container">
                    <span class="spec-chip">SUV Fit</span>
                    <span class="spec-chip">EV Charging</span>
                    <span class="spec-chip">CCTV</span>
                </div>

                <div class="status-indicator">
                    <div class="pulse-dot"></div>
                    Available Now
                </div>
            </div>

            <div class="card-actions">
                <button class="btn-book">Reserve Spot</button>
                <button class="btn-fav">❤️</button>
            </div>
        </div>
        <!-- End Listing Card -->
          <!-- Repeat this <div> for each listing -->
        <div class="listing-card">
            <div class="listing-image-wrapper">
                <img src="https://via.placeholder.com/400x250" alt="Parking Space">
                <div class="price-tag">$4.50/hr</div>
            </div>

            <div class="listing-content">
                <div class="listing-header">
                    <h4>Heliopolis - Street 12</h4>
                    <span class="rating-badge">★ 4.8</span>
                </div>
                <span class="distance-text">📍 0.6 km from destination</span>

                <div class="specs-container">
                    <span class="spec-chip">SUV Fit</span>
                    <span class="spec-chip">EV Charging</span>
                    <span class="spec-chip">CCTV</span>
                </div>

                <div class="status-indicator">
                    <div class="pulse-dot"></div>
                    Available Now
                </div>
            </div>

            <div class="card-actions">
                <button class="btn-book">Reserve Spot</button>
                <button class="btn-fav">❤️</button>
            </div>
        </div>
        <!-- End Listing Card -->
          <!-- Repeat this <div> for each listing -->
        <div class="listing-card">
            <div class="listing-image-wrapper">
                <img src="https://via.placeholder.com/400x250" alt="Parking Space">
                <div class="price-tag">$4.50/hr</div>
            </div>

            <div class="listing-content">
                <div class="listing-header">
                    <h4>Heliopolis - Street 12</h4>
                    <span class="rating-badge">★ 4.8</span>
                </div>
                <span class="distance-text">📍 0.6 km from destination</span>

                <div class="specs-container">
                    <span class="spec-chip">SUV Fit</span>
                    <span class="spec-chip">EV Charging</span>
                    <span class="spec-chip">CCTV</span>
                </div>

                <div class="status-indicator">
                    <div class="pulse-dot"></div>
                    Available Now
                </div>
            </div>

            <div class="card-actions">
                <button class="btn-book">Reserve Spot</button>
                <button class="btn-fav">❤️</button>
            </div>
        </div>
        <!-- End Listing Card -->

    </div>
    
</main>
    </main>

    <script>
        function toggleSidebar() {
            const drawer = document.getElementById("sideDrawer");
            drawer.style.width = (drawer.style.width === "250px") ? "0" : "250px";
        }
    </script>
<footer>
    <div class="footer-container">
        <!-- Brand Section -->
        <div class="footer-section">
            <h3>CitySlot</h3>
            <p style="font-size: 0.85rem; line-height: 1.6; color: #bdc3c7;">
                Optimizing urban mobility by bridging the gap between parking demand and underutilized supply.
            </p>
            <div class="social-icons">
                <a href="#">🌐</a>
                <a href="#">🐦</a>
                <a href="#">📘</a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <li><a href="#">Browse Spaces</a></li>
                <li><a href="#">List Your Driveway</a></li>
                <li><a href="#">How it Works</a></li>
                <li><a href="#">Safety & Security</a></li>
            </ul>
        </div>
        
        <!-- Support -->
        <div class="footer-section">
            <h3>Support</h3>
            <ul class="footer-links">
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Report a Fine</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <!-- Language & Settings -->
        <div class="footer-section">
            <h3>Settings</h3>
            <select style="background: #34495e; color: white; border: none; padding: 5px; border-radius: 4px;">
                <option>English (US)</option>
                <option>Arabic (العربية)</option>
            </select>
        </div>
    </div>

    <div class="footer-bottom">
        <div>
            &copy; <?php echo date("Y"); ?> CitySlot Inc. All rights reserved.
        </div>
        
        <!-- Function 32: System Health Monitor -->
        <div class="system-status">
            <span class="status-dot"></span>
            <span>IoT Network: Online</span>
        </div>
    </div>
</footer>
</body>
</html>