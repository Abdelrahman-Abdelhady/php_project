<?php
// Ensure the class name matches the filename capital 'C'
class requestController extends Controller {
    public function __construct() {
        $this->requestModel = $this->model('requestModel');
    }

    public function index() {
        $requests = $this->requestModel->getAllRequests();
        $data = [
            'requests' => $requests
        ];
        $this->view("users/Driver/requests", $data);
    }
}