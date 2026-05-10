<?php

require_once __DIR__ . '/../../../controllers/HistoryController.php';

$controller = new HistoryController();
$reservations = $controller->getReservationHistory();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot | Reservations History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f4f7f9;
            color: #333;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .menu-icon {
            font-size: 1.2rem;
            color: #2D4263;
            cursor: pointer;
        }

        .logo {
            font-weight: 800;
            color: #2D4263; 
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 2.2rem;
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .reservation-card {
            background: white;
            border-radius: 24px;
            padding: 25px 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .reservation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        }

        .spot-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 2;
        }

        .icon-box {
            width: 55px;
            height: 55px;
            background: #eef2f7;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2D4263;
            font-size: 1.4rem;
        }

        .text-details h3 {
            font-size: 1.2rem;
            color: #2D4263;
            margin-bottom: 5px;
        }

        .text-details p {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .res-timing {
            flex: 1;
            text-align: center;
        }

        .res-timing p {
            font-weight: 600;
            color: #444;
            font-size: 1rem;
        }

        .res-timing span {
            font-size: 0.85rem;
            color: #888;
        }

        .status-badge {
            padding: 7px 18px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            flex: 0.5;
            text-align: center;
        }

        .status-completed { background: #e6fcf5; color: #0ca678; }
        .status-ongoing { background: #e7f5ff; color: #339af0; }
        .status-cancelled { background: #fff5f5; color: #fa5252; }

        .action-side {
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        .btn-action {
            padding: 12px 25px;
            background-color: #2D4263; 
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-action:hover {
            background-color: #1e2d44;
        }

        @media (max-width: 850px) {
            .reservation-card {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .spot-info { flex-direction: column; }
            .action-side { justify-content: center; width: 100%; }
            .btn-action { width: 100%; }
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="menu-icon"><i class="fas fa-bars"></i></div>
        <div class="logo">CitySlot <span style="font-style: normal;">🚗</span></div>
    </header>

    <main class="container">
        <h1 class="page-title">Reservations History</h1>

        <div class="history-list">
            
            <?php if ($reservations->num_rows == 0) { ?>

                <p>No reservation history yet.</p>

            <?php } ?>

            <?php while ($row = $reservations->fetch_assoc()) { ?>

                <div class="reservation-card">
                    <div class="spot-info">
                        <div class="icon-box">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <div class="text-details">
                            <h3>Reservation #<?php echo $row['reservationID']; ?></h3>
                            <p>Spot ID: <?php echo $row['spotID']; ?></p>
                        </div>
                    </div>

                    <div class="res-timing">
                        <p><?php echo $row['startTime']; ?></p>
                        <span><?php echo $row['endTime']; ?></span>
                    </div>

                    <?php
                    $statusClass = "status-completed";

                    if ($row['status'] == "ongoing") {
                        $statusClass = "status-ongoing";
                    } elseif ($row['status'] == "cancelled") {
                        $statusClass = "status-cancelled";
                    }
                    ?>

                    <div class="status-badge <?php echo $statusClass; ?>">
                        <?php
                        $buttonText = "Details";

                        if ($row['status'] == "ongoing") {
                            $buttonText = "View Pass";
                        } elseif ($row['status'] == "completed") {
                            $buttonText = "Invoice";
                        } elseif ($row['status'] == "cancelled") {
                            $buttonText = "Re-book";
                        }
                        ?>

                        <button class="btn-action"><?php echo $buttonText; ?></button>
                    </div>

                    <?php if ($row['status'] == "completed") { ?>

                        <form method="POST" action="/php_project/public/add_review.php" style="margin-top: 10px;">
                            <input type="hidden" name="spot_id" value="<?php echo $row['spotID']; ?>">

                            <select name="rating" required>
                                <option value="">Rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>

                            <input type="text" name="comment" placeholder="Write review..." required>

                            <button type="submit" class="btn-action">Submit Review</button>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </main> 
</body>
</html>