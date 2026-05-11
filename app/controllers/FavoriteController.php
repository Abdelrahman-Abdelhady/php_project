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

            if (!isset($_SESSION['user_id'])) {
                header("Location: /php_project/public/login.php");
                exit;
            }

            $userId = $_SESSION['user_id'];

            $favorite = new Favorite($this->db);

            $favorites = $favorite->getUserFavorites($userId);

            require_once __DIR__ . '/../views/users/Driver/Favourites.php';
        }

        public function add(){

            if (!isset($_SESSION['user_id'])) {
                header("Location: /php_project/public/login.php");
                exit;
            }

            $userId = $_SESSION['user_id'];
            $spotId = $_POST['spot_id'];

            $favorite = new Favorite($this->db);
            $favorite->addFavorite($userId, $spotId);

            header("Location: /php_project/public/favourite");
            exit;
        }

        public function remove(){

            if (!isset($_SESSION['user_id'])) {
                header("Location: /php_project/public/login.php");
                exit;
            }

            $userId = $_SESSION['user_id'];
            $spotId = $_POST['spot_id'];

            $favorite = new Favorite($this->db);
            $favorite->removeFavorite($userId, $spotId);

            header("Location: /php_project/public/favourite");
            exit;
        }


    }

