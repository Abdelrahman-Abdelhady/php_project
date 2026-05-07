<?php
require_once "../app/helpers/Validator.php";
require_once "../app/models/User.php";
require_once "../app/helpers/Auth.php";

class AdminController extends Controller
{
    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole("admin");

        $this->userModel = new User(); // model
    }

    // READ ALL
    public function index()
    {
        $user = Auth::user();

        $users = $this->userModel->getAllUsers();
        $this->view("admin/index", ['users' => $users, 'user' => $user]);
    }

    // SHOW ONE USER
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("admin/show", ['user' => $user]);
    }

    // SHOW CREATE FORM
    public function create()
    {
        $this->view("admin/create");
    }

    // STORE NEW USER
    public function store()
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
            header("Location: " . BASE_URL . "Admin/index");
        } else {
            // Return errors to view
            $this->view("admin/create", [
                'errors' => $validator->getErrors(),
                'old'    => $_POST
            ]);
        }
    }

    // EDIT FORM
    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("admin/edit", ['user' => $user]);
    }

    // UPDATE USER
    public function update($id)
    {
        $validator = new Validator();

        $name  = $_POST['name'];
        $email = $_POST['email'];
        $age   = $_POST['age'];
        $role  = $_POST['role'];

        // Validation rules
        $validator->required('name', $name);
        $validator->required('age', $age);
        $validator->required('email', $email);
        $validator->email('email', $email);

        // Get existing user to keep current profile pic
        $existingUser = $this->userModel->getUserById($id);
        $photoUrl = $existingUser['profile_pic']; // Default to existing

        // Handle file upload if new file is provided
        if (!empty($_FILES['profile_pic']['name'])) {
            require_once '../app/models/Upload.php';
            try {
                $upload = new Upload($_FILES['profile_pic'], ['jpg','png'], 1024000, 'uploads/img/');
                $photoUrl = $upload->save();
            } catch (Exception $e) {
                $validator->errors['profile_pic'] = $e->getMessage();
            }
        }

        if ($validator->passes()) {
            $this->userModel->updateUser($id, $name, $age, $email, $role, $photoUrl);
            header("Location: " . BASE_URL . "Admin/index");
        } else {
            // Return errors to view
            $this->view("admin/edit", [
                'user' => array_merge($existingUser, $_POST),
                'errors' => $validator->getErrors()
            ]);
        }
    }

    // DELETE USER
    public function delete($id)
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole("admin");
        
        $this->userModel->deleteUser($id);

        // AJAX request → return JSON
        // Non-AJAX → Redirect back to index
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => true]);
            return;
        }

        header("Location: " . BASE_URL . "Admin/index");
    }
}
