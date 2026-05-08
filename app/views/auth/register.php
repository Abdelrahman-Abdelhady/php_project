<?php
// اتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "parking_system";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// لو تم الضغط على Sign Up
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'driver';
    $phone_num = $_POST['phone_num'] ?? '';
    
    // رفع الصورة
    $profile_pic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_pic']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $upload_dir = "uploads/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_filename = uniqid() . "." . $ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                $profile_pic = $upload_path;
            }
        }
    }
    
    // التحقق من عدم وجود البريد مكرر
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    
    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (name, email, password, role, phone_num, profile_pic) 
                VALUES ('$name', '$email', '$hashedPassword', '$role', '$phone_num', '$profile_pic')";
        
        if ($conn->query($sql)) {
            $success = "Registration successful! You can now <a href='simple_login.php'>Login</a>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Sign Up</title>
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
            max-width: 450px;
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
        .role-selection {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .role-selection input {
            display: none;
        }
        .role-selection label {
            flex: 1;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .role-selection input:checked + label {
            background: #4F5D95;
            color: white;
            border-color: #4F5D95;
        }
        .preview-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            border: 2px solid #4F5D95;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">Create your account</p>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone_num" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <label class="form-label">I am a:</label>
        <div class="role-selection">
            <input type="radio" name="role" id="driver" value="driver" checked>
            <label for="driver">🚗 Driver</label>

            <input type="radio" name="role" id="owner" value="space_owner">
            <label for="owner">🏠 Space Owner</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture (Optional)</label>
            <input type="file" name="profile_pic" class="form-control" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="preview-img" style="display: none;">
        </div>

        <button type="submit" class="btn btn-primary shadow">Sign Up</button>
    </form>

    <div class="mt-4 text-center">
        <p class="small text-muted">
            Already have an account?
            <a href="simple_login.php" class="text-decoration-none fw-bold" style="color: #4F5D95;">Login</a>
        </p>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.margin = '10px auto 0';
            }
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>