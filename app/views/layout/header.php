<div id="sideDrawer" class="side-drawer">
    <a href="javascript:void(0)" onclick="toggleSidebar()" style="color:red">Close ×</a>
    <a href="#">My Profile</a>
    <a href="#">Settings</a>
    <a href="<?= BASE_URL ?>history">My Rentals</a>
    <a href="<?= BASE_URL ?>home/contactUs">Contact Us</a>
</div>

<div class="page-wrapper">
    <nav class="navbar">

        <div class="nav-section nav-left">
            <div class="logo">🚘</div>
            <span class="site-title">CitySlot</span>
        </div>

        <div class="nav-section nav-center">
            <input type="text" class="search-bar" placeholder="Search listings...">
        </div>

        <div class="nav-section nav-right">
            <div class="Dropdown">
                <button>Account</button>
                <div class="Content">
                    <?php if (class_exists('Auth') && Auth::check()): ?>
                        <a href="<?= BASE_URL ?>Auth/logout">Logout</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>Auth/login">Login</a>
                        <a href="<?= BASE_URL ?>Auth/register">Signup</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="menu-icon">🔔</div>
            <div class="menu-icon">💵</div>
            <div class="menu-icon" onclick="toggleSidebar()">☰</div>
        </div>

    </nav>
</div>

<<<<<<< HEAD
<script>
function toggleSidebar() {
    var drawer = document.getElementById("sideDrawer");

    if (drawer.style.width === "250px") {
        drawer.style.width = "0";
    } else {
        drawer.style.width = "250px";
    }
}
=======
                <div class="menu-icon" onclick="">🔔</div>
                <div class="menu-icon" onclick="">💵</div>
                <div class="menu-icon" onclick="toggleSidebar()">☰</div>
            </div>
        </nav>
    </div>
    <script>
    function toggleSidebar() {
        const drawer = document.getElementById("sideDrawer");

        if (drawer) {
            drawer.style.width = (drawer.style.width === "250px") ? "0" : "250px";
        }
    }
<<<<<<< Updated upstream
=======
>>>>>>> ee535bd092910d80735e1f0531e0fde6e893388f
>>>>>>> Stashed changes
</script>