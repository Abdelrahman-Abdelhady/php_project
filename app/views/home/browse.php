<?php
$conn = mysqli_connect("localhost", "root", "", "parking_system");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM spot";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Spots</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root{--primary:#4F5D95;--bg:#f4f7fb;--white:#fff;--text:#1e293b;--muted:#64748b;--success:#22c55e;--danger:#ef4444;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);}
.header{background:var(--primary);padding:18px 5%;color:white;}
.header-inner{display:flex;justify-content:space-between;align-items:center;}
.logo{text-decoration:none;color:white;font-size:24px;font-weight:700;}
.search-section{padding:40px 5%;text-align:center;}
.search-section h1{font-size:38px;margin-bottom:10px;}
.search-section p{color:var(--muted);margin-bottom:25px;}
.search-bar{max-width:600px;margin:auto;}
.search-bar input{width:100%;padding:15px 20px;border:none;border-radius:50px;font-size:16px;outline:none;box-shadow:0 4px 12px rgba(0,0,0,0.08);}
.spots-grid{width:90%;margin:auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:25px;padding-bottom:50px;}
.spot-card{background:white;border-radius:18px;overflow:hidden;box-shadow:0 4px 18px rgba(0,0,0,0.08);transition:.3s;}
.spot-card:hover{transform:translateY(-6px);}
.spot-image{height:220px;overflow:hidden;}
.spot-image img{width:100%;height:100%;object-fit:cover;}
.spot-info{padding:20px;}
.spot-name{font-size:22px;margin-bottom:8px;color:var(--primary);}
.spot-address{color:var(--muted);font-size:14px;margin-bottom:15px;}
.spot-meta{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;}
.meta-pill{background:#eef2ff;color:var(--primary);padding:6px 12px;border-radius:20px;font-size:13px;font-weight:600;}
.spot-footer{display:flex;justify-content:space-between;align-items:center;}
.price{font-size:24px;font-weight:700;color:var(--primary);}
.price small{font-size:13px;color:var(--muted);}
.btn{background:var(--primary);color:white;padding:10px 18px;border-radius:30px;text-decoration:none;font-size:14px;font-weight:600;transition:.3s;}
.btn:hover{opacity:.9;}
.status{position:absolute;top:15px;right:15px;padding:7px 14px;border-radius:30px;color:white;font-size:20px;font-weight:600;}
.available{background:var(--success);}
.full{background:var(--danger);}
.image-wrapper{position:relative;}
</style>
</head>

<body>
    
<section class="search-section">
  <h1>Find Your Parking Spot</h1>
  <p>Browse available parking spots around Cairo</p>

  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search by location...">
  </div>
</section>

<main class="spots-grid" id="spotsContainer">

<?php while($row = mysqli_fetch_assoc($result)): ?>

<div class="spot-card">

  <div class="image-wrapper">

    <div class="spot-image">
     <img src="images/<?php echo $row['image']; ?>">
    </div>

  <div class="spot-info">

    <h3 class="spot-name">
      <?= $row['title'] ?>
    </h3>

    <p class="spot-address">
      📍 <?= $row['location'] ?>
    </p>

    </div>

    <div class="spot-footer">

      <div class="price">
        <?= $row['price_per_hour'] ?>
        <small>EGP/hr</small>
      </div>
      <a href="spot_details.php?id=<?= $row['spotID'] ?>" class="btn">View</a>
      </div>

  </div>

</div>

<?php endwhile; ?>

</main>

<script>

const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("keyup", function(){

    let filter = searchInput.value.toLowerCase();

    let cards = document.querySelectorAll(".spot-card");

    cards.forEach(card => {

        let title = card.querySelector(".spot-name").innerText.toLowerCase();
        let location = card.querySelector(".spot-address").innerText.toLowerCase();

        if(title.includes(filter) || location.includes(filter)){
            card.style.display = "block";
        }else{
            card.style.display = "none";
        }

    });

});

</script>

</body>
</html>