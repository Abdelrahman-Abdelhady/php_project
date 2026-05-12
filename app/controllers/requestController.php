<?php
require_once "../app/models/requestModel.php";
require_once "../app/helpers/Auth.php";

class RequestController extends Controller
{
    private $requestModel;

    public function __construct()
    {
        $this->requestModel = new requestModel();
    }

    // Driver/Owner: show form + their requests
    public function index()
    {
        Auth::redirectIfNotLogged();

        $userID   = Auth::user()['id'];
        $requests = $this->requestModel->getRequestsByUserId($userID);
        $role     = Auth::user()['role'];

        $this->view("users/Driver/requests", [
            'requests' => $requests,
            'role'     => $role,
        ]);
    }

    // Driver/Owner: submit new request
    public function create() {
    $this->view("users/Driver/create_request");
}
    public function store()
    {
        Auth::redirectIfNotLogged();

        $userID      = Auth::user()['id'];
        $role        = Auth::user()['role'];
        $type        = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $errors      = [];

        $allowedTypes = ['owner_verification', 'refund', 'fine_appeal', 'maintenance', 'support'];

        if (!in_array($type, $allowedTypes)) {
            $errors['type'] = 'Please select a valid request type.';
        }

        if (empty(trim($description))) {
            $errors['description'] = 'Please provide a description.';
        }

        if (!empty($errors)) {
            $requests = $this->requestModel->getRequestsByUserId($userID);
            $this->view("users/requests/index", [
                'requests' => $requests,
                'errors'   => $errors,
                'old'      => $_POST,
                'role'     => $role,
            ]);
            return;
        }

        $this->requestModel->createRequest($userID, $type, $description);

        header("Location: " . BASE_URL . "Request/index");
        exit;
    }

    // Admin: view all requests
    public function adminIndex()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('admin');

        $requests = $this->requestModel->getAllRequests();

        $this->view("admin/requests", [
            'requests' => $requests,
        ]);
    }

    // Admin: update request status
    public function updateStatus()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('admin');

        $id     = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';

        $allowed = ['Pending', 'Accepted', 'Rejected'];

        if (!in_array($status, $allowed) || empty($id)) {
            header("Location: " . BASE_URL . "Request/adminIndex");
            exit;
        }

        $this->requestModel->updateStatus($id, $status);

        header("Location: " . BASE_URL . "Request/adminIndex");
        exit;
    }
}