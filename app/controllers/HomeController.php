<?php
require_once "../app/helpers/Auth.php";

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Temporary static listings until SpotModel is ready
        $listings = [
            [
                'spotID' => 1,
                'title' => 'Heliopolis - Street 12',
                'image' => 'https://via.placeholder.com/400x250',
                'price_per_hour' => 4.50,
                'rating' => 4.8,
                'distance' => '0.6 km from destination',
                'status' => 'available',
                'features' => ['SUV Fit', 'EV Charging', 'CCTV']
            ],
            [
                'spotID' => 2,
                'title' => 'Nasr City - Abbas El Akkad',
                'image' => 'https://via.placeholder.com/400x250',
                'price_per_hour' => 3.75,
                'rating' => 4.6,
                'distance' => '1.2 km from destination',
                'status' => 'available',
                'features' => ['Sedan Fit', 'Covered', 'Security']
            ],
            [
                'spotID' => 3,
                'title' => 'Maadi - Road 9',
                'image' => 'https://via.placeholder.com/400x250',
                'price_per_hour' => 5.00,
                'rating' => 4.9,
                'distance' => '0.9 km from destination',
                'status' => 'available',
                'features' => ['CCTV', 'Wide Space', '24/7 Access']
            ],
            [
                'spotID' => 4,
                'title' => 'Zamalek - Nile Street',
                'image' => 'https://via.placeholder.com/400x250',
                'price_per_hour' => 6.25,
                'rating' => 4.7,
                'distance' => '1.5 km from destination',
                'status' => 'available',
                'features' => ['Covered', 'SUV Fit', 'Security']
            ]
        ];

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