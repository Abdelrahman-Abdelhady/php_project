<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Server Status</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 5px solid #4F5D95; /* PHP Brand Color */
        }
        h1 {
            color: #4F5D95;
            margin-bottom: 0.5rem;
        }
        p {
            color: #666;
            font-size: 1.1rem;
        }
        .time-box {
            margin-top: 20px;
            padding: 10px;
            background: #eef2f7;
            border-radius: 8px;
            font-family: monospace;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="card">
        <?php
            // Confirmation Message
            echo "<h1>PHP is workinggggggg!</h1>";
            
            echo "<p>Your server is correctly processing PHP scripts.</p>";

            // Display formatted server time
            echo "<div class='time-box'>";
            echo "Server Time: " . date("Y-m-d H:i:s");
            echo "</div>";
        ?>
    </div>

</body>
</html>