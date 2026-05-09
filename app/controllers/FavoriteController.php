<?php 

require_once __DIR__ . '/../models/Favorite.php';

class FavoriteController extends BaseController {

    public function index(){

    }

    public function add(){

        $userId = $_POST['user_id'];
        $spotId = $_POST['spot_id'];

        $favorite = new Favorite($this->db);
        $favorite->addFavorite($userId, $spotId);

        echo "added to favorites";
    }

    public function remove(){

    }
}