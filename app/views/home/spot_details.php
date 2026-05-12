<?php

$conn = mysqli_connect("localhost","root","","parking_system");

$id = intval($_GET['id']);
$query = "SELECT * FROM spot WHERE spotID = $id";
$result = mysqli_query($conn, $query);
$spot = mysqli_fetch_assoc($result);
require_once __DIR__ . '/../../../app/models/Review.php';
$reviewObj = new Review($conn);
$allReviews = $reviewObj->getSpotReviews($spot['spotID']);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CitySlot — Spot Details</title>
  <meta name="description" content="Spot details, services, reviews">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>


:root {--navy: #0F2854;--navy-light: #1e4080;--sky: #BDE8F5;--sky-soft: #E8F6FB;--accent: #F39C3D;--success: #2ECC71;--danger: #E74C3C;--bg: #F4F8FC;--card: #FFFFFF;--text: #1a2a44;--muted: #6b7a90;--border: #DCE6F0;--gradient-primary: linear-gradient(135deg, #0F2854, #2B5BA8);--gradient-hero: linear-gradient(135deg, #0F2854 0%, #1e4080 100%);--shadow-sm: 0 2px 8px rgba(15, 40, 84, 0.08);--shadow-md: 0 10px 30px rgba(15, 40, 84, 0.15);--shadow-lg: 0 25px 50px rgba(15, 40, 84, 0.2);--shadow-glow: 0 0 40px rgba(43, 91, 168, 0.35);--radius: 1rem;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Poppins',sans-serif;background:var(--sky-soft);color:var(--text);}
a{text-decoration:none;color:inherit;}
img{width:100%;display:block;}
.header{background:var(--gradient-hero);padding:1.2rem 1.5rem;color:#fff;position:sticky;top:0;z-index:100;}
.header-inner{max-width:1200px;margin:auto;display:flex;justify-content:space-between;align-items:center;}
.logo{display:flex;align-items:center;gap:.7rem;font-size:1.3rem;font-weight:800;}
.logo-icon{width:42px;height:42px;background:var(--sky);color:var(--navy);border-radius:12px;display:grid;place-items:center;}
.btn-ghost{background:rgba(255,255,255,.15);color:#fff;padding:.6rem 1rem;border:none;border-radius:999px;cursor:pointer;font-weight:600;}
.hero{position:relative;height:360px;overflow:hidden;}
.hero img{height:100%;object-fit:cover;}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,var(--bg),transparent);}
.back-btn{position:absolute;top:20px;left:20px;background:#fff;padding:.6rem 1rem;border-radius:999px;font-weight:600;box-shadow:var(--shadow-sm);}
.detail-container{max-width:900px;margin:-80px auto 0;padding:0 1.5rem 8rem;position:relative;z-index:2;}
.card{background:#fff;border-radius:1.5rem;padding:1.7rem;margin-bottom:1.2rem;box-shadow:var(--shadow-md);}
.detail-head{display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
.detail-head h1{font-size:1.8rem;color:var(--navy);margin-bottom:.5rem;}
.spot-address{color:var(--muted);font-size:.95rem;}
.spot-zone{color:var(--muted);font-size:.95rem;}
.spot-meta{display:flex;gap:.6rem;flex-wrap:wrap;}
.meta-pill{background:var(--sky-soft);padding:.35rem .8rem;border-radius:999px;font-size:.8rem;font-weight:600;}
.detail-price{font-size:2rem;font-weight:800;color:var(--navy);}
.detail-price small{font-size:.8rem;color:var(--muted);}
.section-title{font-size:1.2rem;margin-bottom:1rem;color:var(--navy);}
.services-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.8rem;}
.service{background:var(--sky-soft);border-radius:14px;padding:1rem;display:flex;align-items:center;gap:.7rem;}
.service-icon{width:40px;height:40px;border-radius:10px;background:var(--gradient-primary);color:#fff;display:grid;place-items:center;}
.review{background:var(--sky-soft);border-radius:14px;padding:1rem;margin-bottom:.8rem;}
.review-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;}
.review-user{display:flex;align-items:center;gap:.6rem;}
.avatar{width:38px;height:38px;border-radius:50%;background:var(--gradient-primary);color:#fff;display:grid;place-items:center;font-weight:700;}
.review-rating{background:#fff;padding:.3rem .7rem;border-radius:999px;font-size:.8rem;font-weight:700;}
.review p{color:var(--muted);font-size:.9rem;}
.booking-section {margin-top: 2rem;text-align: center;padding: 1.5rem;background: #fff;border-radius: 1.5rem;box-shadow: var(--shadow-md);}
.booking-section .price {font-size: 1.8rem;margin-bottom: 1rem;display: block;}
.booking-section .btn {display: inline-block;width: 100%;max-width: 400px;text-align: center;}
.price{font-size:1.4rem;font-weight:800;color:var(--navy);}
.price small{font-size:.75rem;color:var(--muted);}
.btn{padding:.9rem 1.5rem;border-radius:999px;font-weight:700;}
.btn-primary{background:var(--gradient-primary);color:#fff;}
@media(max-width:600px){.hero{height:250px;}.detail-head h1{font-size:1.4rem;}.detail-price{font-size:1.5rem;}.sticky-inner{flex-direction:column;gap:.7rem;}.sticky-inner .btn{width:100%;text-align:center;}}

</style>
</head>
<body>
  <section class="hero">
    <img src="images/<?php echo $spot['image']; ?>">
    <div class="hero-overlay"></div>
    <a href="browse.php" class="back-btn">←
      Back
    </a>
  </section>

  <main class="detail-container">
    <div class="card">
      <div class="detail-head">
        <div>
          <h1>
            <?php echo $spot['title']; ?>
          </h1>
          <p class="spot-zone">📍
            <?php echo $spot['zone']; ?>
          </p>
          <p class="spot-address">📍
            <?php echo $spot['location']; ?>
          </p>
          <div class="spot-meta" style="margin-top:1rem">
            <span class="meta-pill">⭐ 4.9</span>
            <span class="meta-pill">🚗 26/80
              spots
            </span>
            <span class="meta-pill">🕐
              Open 24/7
            </span>
          </div>
        </div>
        <div class="detail-price">
          <?php echo $spot['price_per_hour']; ?><small>EGP / hr</small>
        </div>
      </div>
    </div>


<div class="card">
    <h2 class="section-title">
        Spot Specifications & Services
    </h2>
    <div class="services-grid">
        <div class="service">
            <div class="service-icon">📏</div>
            Max Height: <?= $spot['height_limit'] ?>m
        </div>
        <div class="service">
            <div class="service-icon">↔️</div>
            Max Width: <?= $spot['width_limit'] ?>m
        </div>

        <?php if (isset($spot['ev_charging']) && $spot['ev_charging'] == 1): ?>
            <div class="service">
                <div class="service-icon">⚡</div>
                EV Charging
            </div>

        <?php endif; ?>

        <?php if (isset($spot['cctv']) && $spot['cctv'] == 1): ?>
            <div class="service">
                <div class="service-icon">📹</div>
                CCTV Security
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="card">
    <h2 class="section-title">Latest Reviews</h2>

    <?php if ($allReviews->num_rows > 0): ?>
        <?php while($row = $allReviews->fetch_assoc()): ?>
            <div class="review">
                <div class="review-head">

                    <span class="review-rating">⭐ <?= number_format($row['rating'], 1) ?></span>
                </div>
                <p><?= htmlspecialchars($row['comment']) ?></p>
                <small style="color: #999; font-size: 0.7rem;">
                    <?= date('M d, Y', strtotime($row['date'])) ?>
                </small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</div>

    <div class="booking-section">
        <div class="price">
            <?php echo $spot['price_per_hour']; ?> <small>EGP/hr</small>
        </div>
            <a href="reserve.php?spotID=<?= $spot['spotID'] ?>" class="btn btn-primary">
                Reserve Now
            </a>
    </div>

  </main>
</body>
</html>