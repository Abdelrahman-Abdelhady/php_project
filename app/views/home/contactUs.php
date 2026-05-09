<?php

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        // Save to database
$conn = new mysqli("localhost", "root", "", "parking_system");
$stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $message);
$stmt->execute();
$stmt->close();
$conn->close();
        $success = "Thank you for contacting us! We'll get back to you soon.";
        
         //Clear form data (optional)
         $_POST = array();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CitySlot</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #eef4ff, #dbeafe);
            color: #1f2937;
        }

        .container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 20px;
        }

        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #ffffff;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        .contact-info {
            background: #203859;
            color: white;
            padding: 60px 45px;
        }

        .contact-info h1 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .contact-info p {
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 35px;
            opacity: 0.95;
        }

        .info-item {
            margin-bottom: 25px;
            font-size: 18px;
        }

        .info-item strong {
            display: block;
            margin-bottom: 8px;
            font-size: 19px;
        }

        .contact-form {
            padding: 60px 45px;
        }

        .contact-form h2 {
            font-size: 34px;
            margin-bottom: 30px;
            color: #203859;
        }

        .input-group {
            margin-bottom: 22px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1f2937;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 16px;
            transition: 0.3s;
            outline: none;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            border-color: #203859;
            box-shadow: 0 0 0 4px rgba(32, 56, 89, 0.12);
        }

        .input-group textarea {
            min-height: 160px;
            resize: vertical;
        }

        .submit-btn {
            width: 100%;
            padding: 17px;
            border: none;
            border-radius: 12px;
            background: #203859;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            background: #2a4a75;
            box-shadow: 0 10px 25px rgba(32, 56, 89, 0.35);
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        @media (max-width: 900px) {
            .contact-wrapper {
                grid-template-columns: 1fr;
            }

            .contact-info,
            .contact-form {
                padding: 40px 30px;
            }

            .contact-info h1 {
                font-size: 34px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="contact-wrapper">

            <div class="contact-info">
                <h1>Contact Us</h1>
                <p>
                    Have questions, feedback, or need assistance? 
                    Our CitySlot support team is here to help you anytime.
                </p>

                <div class="info-item">
                    <strong>📧 Email</strong>
                    support@cityslot.com
                </div>

                <div class="info-item">
                    <strong>📞 Phone</strong>
                    +20 12345
                </div>

                <div class="info-item">
                    <strong>📍 Address</strong>
                    Cairo, Egypt
                </div>
            </div>

            <div class="contact-form">
                <h2>Contact Our Team</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="input-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" placeholder="Enter your full name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" placeholder="Enter your phone number" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>

                    <div class="input-group">
                        <label>Message *</label>
                        <textarea name="message" placeholder="Write your message here..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        ✉️ Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>