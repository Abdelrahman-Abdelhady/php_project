<?php
require_once "../app/helpers/Validator.php";
require_once "../app/helpers/Auth.php";
require_once "../app/models/User.php";

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register()
    {
        $this->view("auth/register");
    }

    public function storeRegister()
    {
        $validator = new Validator();

        $name  = $_POST['name'];
        $age   = $_POST['age'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role  = $_POST['role'];

        // Validation rules
        $validator->required('name', $name);
        $validator->required('age', $age);
        $validator->required('email', $email);
        $validator->email('email', $email);
        $validator->required('password', $password);

        $photoUrl = null;

        // Handle file upload
        if (!empty($_FILES['profile_pic']['name'])) {
            require_once '../app/models/Upload.php';
            try {
                $upload = new Upload($_FILES['profile_pic'], ['jpg','png'], 1024000, 'uploads/img/');
                $photoUrl = $upload->save();
            } catch (Exception $e) {
                $validator->errors['profile_pic'] = $e->getMessage();
            }
        }
        else {
            $photoUrl = "uploads/img/default.png";
        }

        if ($validator->passes()) {
            // Hash password (bcrypt)
            $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // Save to DB
            $this->userModel->createUser($name, $age, $email, $hashedPassword, $role, $photoUrl);
            header("Location: " . BASE_URL . "Auth/login");
        } else {
            // Return errors to view
            $this->view("auth/register", [
                'errors' => $validator->getErrors(),
                'old'    => $_POST
            ]);
        }
    }

    public function login()
    {
        $this->view("auth/login");
    }

    public function doLogin()
    {
        $validator = new Validator();

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validation rules
        $validator->required('email', $email);
        $validator->email('email', $email);
        $validator->required('password', $password);
        $validator->minLength('password', $password, 3);

        if (!$validator->passes()) {
            $this->view("auth/login", [
                'errors' => $validator->getErrors(), 
                'old'    => $_POST
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->view("auth/login", [
                "errors" => ["login" => "Invalid credentials"],
                'old'    => $_POST
            ]);
            return;
        }

        Auth::login($user);

        switch ($user['role']) {
        case 'admin':
            header("Location: " . BASE_URL . "Admin/index");
            break;
        case 'Driver':
            header("Location: " . BASE_URL . "Driver/index");
            break;
        case 'Owner':
            header("Location: " . BASE_URL . "Owner/index");
            break;
        default:
            header("Location: " . BASE_URL . "Home/index");
            break;
        }

        exit;
    }

    public function logout()
    {
        Auth::logout();
        header("Location: " . BASE_URL . "Home/index");
        exit;
    }
}
