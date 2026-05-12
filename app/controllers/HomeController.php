<?php
require_once "../app/helpers/Auth.php";
require_once "../app/models/Spot.php";

class HomeController extends Controller
{
    private $spotModel;

    public function __construct()
    {
        $this->spotModel = new Spot();
    }

    public function index()
    {
        $user = Auth::user();

        // Get real available spots from database instead of static listings
        $listings = $this->spotModel->getActiveSpots();

        $this->view("home/index", [
            'user' => $user,
            'listings' => $listings
        ]);
    }

    public function contactUs()
    {
        $this->view("home/contactUs");
    }
}