<?php
require_once "../app/helpers/Validator.php";
require_once "../app/helpers/Auth.php";
require_once "../app/models/UserModel.php";
require_once "../app/models/WalletModel.php";

class AuthController extends Controller
{
    private $userModel;
    private $walletModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->walletModel = new WalletModel();
    }

    public function index()
    {
        // CHANGED: Keep index as a clean redirect to login
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

        // CHANGED: Added ?? '' to prevent undefined index warnings
        $name      = $_POST['name'] ?? '';
        $phone_num = $_POST['phone_num'] ?? '';
        $email     = $_POST['email'] ?? '';
        $password  = $_POST['password'] ?? '';
        $role      = $_POST['role'] ?? '';

        // Validation rules
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

        // Handle file upload
        if (!empty($_FILES['profile_pic']['name'])) {
            require_once "../app/helpers/Upload.php";

            try {
                // CHANGED: Added jpeg as an accepted extension
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

        if ($validator->passes()) {

            // CHANGED: Added duplicate email check before inserting user
            if ($this->userModel->emailExists($email)) {
                $this->view("auth/register", [
                    'errors' => ['email' => 'Email already exists'],
                    'old'    => $_POST
                ]);
                return;
            }

          
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Save to DB through model only
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

                // ADDED: Get newly created user using email
                $newUser = $this->userModel->findByEmail($email);

                // ADDED: Create wallet record for the new user
                if ($newUser && isset($newUser['id'])) {
                    $this->walletModel->createWalletForUser($newUser['id']);
                }

                header("Location: " . BASE_URL . "Auth/login");
                exit;
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

      
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation rules
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

        // If user was redirected to login from a protected page,
        // send them back to that original page.
        if (isset($_SESSION['redirect_after_login'])) {
            $redirectUrl = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);

            header("Location: " . $redirectUrl);
            exit;
        }

        
        switch ($user['role']) {
            case 'admin':
                header("Location: " . BASE_URL . "Admin/index");
                break;

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

    public function logout()
    {
        Auth::logout();

        header("Location: " . BASE_URL . "Home/index");
        exit;
    }
}