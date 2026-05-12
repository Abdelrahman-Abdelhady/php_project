<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController extends Controller
{
    // تعريف البروبرتي بيخلي الـ IDE (زي VS Code) يفهم إن الكلاس ده موجود
    public $userModel;

    public function __construct()
    {
        // نداء الموديل بالاسم الصحيح
        $this->userModel = new UserModel(); 
    }

    // READ ALL
    public function index()
    {
        $users = $this->userModel->getAllUsers();
        $this->view("users/index", ['users' => $users]);
    }

    // SHOW ONE USER
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("users/show", ['user' => $user]);
    }

    // SHOW CREATE FORM
    public function create()
    {
        $this->view("users/create");
    }

    // STORE NEW USER
    public function store()
    {
        $validator = new Validator();
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone_num = $_POST['phone_num'] ?? '';
        $role = $_POST['role'] ?? '';

        $validator->required('name', $name);
        $validator->required('email', $email);
        $validator->email('email', $email);
        $validator->required('phone_num', $phone_num);
        $validator->required('role', $role);

        if ($validator->passes()) {
            // بعتنا الـ 5 باراميترز الأساسية (الترتيب: name, phone, email, password, role)
            $this->userModel->createUser($name, $phone_num, $email, '', $role);
            header("Location: " . BASE_URL . "User/index");
        } else {
            $this->view("users/create", [
                'errors' => $validator->getErrors(),
                'old' => $_POST
            ]);
        }
    }

    // EDIT FORM
    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("users/edit", ['user' => $user]);
    }

    // UPDATE USER
    public function update($id)
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_num = $_POST['phone_num'];
        $role = $_POST['role'];

        // مطابقة لـ updateUser($id, $name, $phone_num, $email, $role) في الموديل
        $this->userModel->updateUser($id, $name, $phone_num, $email, $role);

        header("Location: " . BASE_URL . "User/index");
    }

    // DELETE USER
    public function delete($id)
    {
        $this->userModel->deleteUser($id);
        header("Location: " . BASE_URL . "User/index");
    }
}