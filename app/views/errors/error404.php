<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/home.css">

    <style>
        .error-page {
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .error-page h1 {
            font-size: 96px;
            margin: 0;
            font-weight: 800;
            color: #1f2937;
        }

        .error-page h2 {
            font-size: 32px;
            margin: 10px 0;
            color: #111827;
        }

        .error-page p {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .error-page .back-home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            background: #111827;
            color: #fff;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .error-page .back-home-btn:hover {
            background: #374151;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

<?php require_once "../app/views/layout/header.php"; ?>

<main class="error-page">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>The page you are looking for does not exist.</p>
    <a href="<?= BASE_URL ?>" class="back-home-btn">Back to Home</a>
</main>
<footer>
<?php require_once "../app/views/layout/footer.php"; ?>
</footer>
</body>
</html>