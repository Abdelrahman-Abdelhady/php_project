<?php

require_once "../app/core/Controller.php";
require_once "../app/models/Request.php";

class RequestController extends Controller {

    public function index() {

        $request = new Request();

        $requests = $request->getAllRequests();

        $this->view("requests/index", [
            "requests" => $requests
        ]);
    }
}