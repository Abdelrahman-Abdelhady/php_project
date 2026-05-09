<?php
require_once "../app/helpers/Auth.php";


class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->view("home/index", ['user' => $user]);
    }
    
    public function contactUs()
    {
        $this->view("home/contactUs");
    }
}
