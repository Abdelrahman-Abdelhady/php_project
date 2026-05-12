<?php
// app/controllers/UserController.php

// Use __DIR__ to ensure paths are absolute and stable
require_once __DIR__ . "/../helpers/Validator.php";


require_once __DIR__ . '/../helpers/Auth.php';

require_once __DIR__ . "/../models/UserModel.php";

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        // Start the session if it hasn't been started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // FIX: Using 'new UserModel()' to match the filename
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

        $name      = $_POST['name'] ?? '';
        $email     = $_POST['email'] ?? '';
        $phone_num = $_POST['phone_num'] ?? '';
        $role      = $_POST['role'] ?? '';

        $validator->required('name', $name);
        $validator->required('email', $email);
        $validator->email('email', $email);
        $validator->required('phone_num', $phone_num);
        $validator->required('role', $role);

        if ($validator->passes()) {
            $this->userModel->createUser($name, $phone_num, $email, '', $role);
            header("Location: " . BASE_URL . "User/index");
            exit;
        } else {
            $this->view("users/create", [
                'errors' => $validator->getErrors(),
                'old'    => $_POST
            ]);
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("users/edit", ['user' => $user]);
    }

    public function update($id)
    {
        $name      = $_POST['name'];
        $email     = $_POST['email'];
        $phone_num = $_POST['phone_num'];
        $role      = $_POST['role'];

        $this->userModel->updateUser($id, $name, $phone_num, $email, $role, null);

        header("Location: " . BASE_URL . "User/index");
        exit;
    }

    public function delete($id)
    {
        $this->userModel->deleteUser($id);
        header("Location: " . BASE_URL . "User/index");
        exit;
    }
}