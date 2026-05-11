<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot | Reservations History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/home.css">

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

        <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <body>

    <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <main class="container">
        <h1 class="page-title">Reservations History</h1>

        <div class="history-list">

            <?php if ($reservations->num_rows == 0) { ?>

                <p>No reservation history yet.</p>

            <?php } ?>

            <?php while ($row = $reservations->fetch_assoc()) { ?>

                <!-- reservation card here -->

            <?php } ?>

        </div>
    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>

</body>
</html>