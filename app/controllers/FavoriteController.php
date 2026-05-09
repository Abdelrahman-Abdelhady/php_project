<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../../core/Database.php';
    require_once __DIR__ . '/../models/Favorite.php';

    class FavoriteController {

        private $db;

        public function __construct() {
            $database = Database::getInstance();
            $this->db = $database->getConnection();
        }

        public function index(){
            $userId = $_SESSION['user_id'];

            $favorite = new Favorite($this->db);

            $favorites = $favorite->getUserFavorites($userId);

            return $favorites;
        }

        public function add(){

            $userId = $_SESSION['user_id'];
            $spotId = $_POST['spot_id'];

            $favorite = new Favorite($this->db);
            $favorite->addFavorite($userId, $spotId);

            echo "added to favorites";
        }

        public function remove(){
            $userId = $_SESSION['user_id'];
            $spotId = $_POST['spot_id'];

            $favorite = new Favorite($this->db);
            $favorite->removeFavorite($userId, $spotId);

            echo "removed from favorites";
        }


    }