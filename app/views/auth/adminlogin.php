<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "parking_system";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check for admin only (municipal_admin role)
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'municipal_admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['name'];
            $_SESSION['user_id'] = $row['userID'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone_num'];
            $_SESSION['profile_pic'] = $row['profile_pic'];
            
            // Redirect to admin dashboard
            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Admin account not found";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }
        .btn-primary {
            background: #4F5D95;
            border: none;
            border-radius: 25px;
            padding: 10px;
            width: 100%;
        }
        .btn-primary:hover {
            background: #3b4675;
        }
        .admin-badge {
            background: #4F5D95;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <span class="admin-badge">🔐 Admin Portal</span>
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">Welcome back Admin! Please login</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="admin@cityslot.com" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary shadow">Admin Login</button>
    </form>

    
</div>

</body>
</html>