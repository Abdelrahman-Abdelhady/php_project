<?php
require_once "../app/helpers/Validator.php";
require_once "../app/core/Auth.php";
require_once "../app/models/UserModel.php";

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Show register page
    public function register()
    {
        $this->view("auth/register");
    }

    // Handle register form
    public function storeRegister()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'driver';
        $phone_num = $_POST['phone_num'] ?? '';
        
        // Encrypt password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Database connection
        $db = Database::getInstance()->getConnection();
        
        // Insert user into database
        $sql = "INSERT INTO users (name, email, password, role, phone_num) 
                VALUES ('$name', '$email', '$hashedPassword', '$role', '$phone_num')";
        
        // Check if query executed successfully
        if ($db->query($sql)) {

            echo "Registered Successfully!";

        } else {

            echo "Database Error: " . $db->error;
        }

        exit;
    }

    // Show login page
    public function login()
    {
        $this->view("auth/login");
    }

    // Handle login process
    public function doLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Find user by email
        $user = $this->userModel->findByEmail($email);
        
        // Verify password
        if ($user && password_verify($password, $user['password'])) {

            // Save user session
            Auth::login($user);

            // Redirect to marketplace
            header("Location: Driver/marketplace");

        } else {

            // Redirect with error
            header("Location: Auth/login?error=invalid");
        }

        exit;
    }

    // Logout user
    public function logout()
    {
        Auth::logout();

        // Redirect to login page
        header("Location: Auth/login");

        exit;
    }
}