<?php
require_once "../app/helpers/Validator.php";
require_once "../app/helpers/Auth.php";
require_once "../app/models/UserModel.php";
require_once "../app/models/WalletModel.php";
require_once "../app/models/AdminModel.php";

class AuthController extends Controller
{
    private $userModel;
    private $walletModel;
    private $adminModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->walletModel = new WalletModel();
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        header("Location: " . BASE_URL . "Auth/login");
        exit;
    }

    public function register()
    {
        $this->view("auth/register");
    }

    public function storeRegister()
    {
        $validator = new Validator();

        $name      = $_POST['name'] ?? '';
        $phone_num = $_POST['phone_num'] ?? '';
        $email     = $_POST['email'] ?? '';
        $password  = $_POST['password'] ?? '';
        $role      = $_POST['role'] ?? '';

        $validator->required('name', $name);
        $validator->minLength('name', $name, 3);
        $validator->maxLength('name', $name, 50);

        $validator->required('phone_num', $phone_num);
        $validator->phone('phone_num', $phone_num);

        $validator->required('email', $email);
        $validator->email('email', $email);

        $validator->required('password', $password);
        $validator->minLength('password', $password, 6);

        $validator->required('role', $role);
        $validator->role('role', $role);

        $photoUrl = null;

        if (!empty($_FILES['profile_pic']['name'])) {
            require_once "../app/helpers/Upload.php";

            try {
                $upload = new Upload(
                    $_FILES['profile_pic'],
                    ['jpg', 'png', 'jpeg'],
                    1024000,
                    'uploads/img/'
                );

                $photoUrl = $upload->save();
            } catch (Exception $e) {
                $validator->errors['profile_pic'] = $e->getMessage();
            }
        } else {
            $photoUrl = "uploads/img/default.png";
        }

        if (!$validator->passes()) {
            $this->view("auth/register", [
                'errors' => $validator->getErrors(),
                'old'    => $_POST
            ]);
            return;
        }

        if ($this->userModel->emailExists($email)) {
            $this->view("auth/register", [
                'errors' => ['email' => 'Email already exists'],
                'old'    => $_POST
            ]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $created = $this->userModel->createUser(
            $name,
            $phone_num,
            $email,
            $hashedPassword,
            $role,
            $photoUrl
        );

        if (!$created) {
            $this->view("auth/register", [
                'errors' => ['register' => 'Something went wrong. Please try again.'],
                'old'    => $_POST
            ]);
            return;
        }

        $newUser = $this->userModel->findByEmail($email);

        if ($newUser && isset($newUser['id'])) {
            $this->walletModel->createWalletForUser($newUser['id']);
        }

        header("Location: " . BASE_URL . "Auth/login");
        exit;
    }

    public function login()
    {
        $this->view("auth/login");
    }

    public function doLogin()
    {
        $validator = new Validator();

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator->required('email', $email);
        $validator->email('email', $email);

        $validator->required('password', $password);
        $validator->minLength('password', $password, 6);

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

        if (isset($_SESSION['redirect_after_login'])) {
            $redirectUrl = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);

            header("Location: " . $redirectUrl);
            exit;
        }

        switch ($user['role']) {
            case 'driver':
                header("Location: " . BASE_URL . "Home/index");
                break;

            case 'space_owner':
                header("Location: " . BASE_URL . "Owner/dashboard");
                break;

            default:
                header("Location: " . BASE_URL . "Home/index");
                break;
        }

        exit;
    }

    public function adminLogin()
    {
        $this->view("auth/adminlogin");
    }

    public function doAdminLogin()
    {
        $validator = new Validator();

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator->required('email', $email);
        $validator->email('email', $email);

        $validator->required('password', $password);

        if (!$validator->passes()) {
            $this->view("auth/adminlogin", [
                'errors' => $validator->getErrors(),
                'old'    => $_POST
            ]);
            return;
        }

        $admin = $this->adminModel->findAdminByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
            $this->view("auth/adminlogin", [
                'errors' => ['login' => 'Invalid admin credentials'],
                'old'    => $_POST
            ]);
            return;
        }

        Auth::login($admin);

        header("Location: " . BASE_URL . "Admin/index");
        exit;
    }

    public function logout()
    {
        Auth::logout();

        header("Location: " . BASE_URL . "Home/index");
        exit;
    }
}