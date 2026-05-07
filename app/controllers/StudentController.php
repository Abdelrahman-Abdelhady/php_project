<?php
require_once "../app/helpers/Auth.php";


class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->view("student/index", ['user' => $user]);
    }
}
