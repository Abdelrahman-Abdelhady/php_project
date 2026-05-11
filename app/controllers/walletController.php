<?php
// Ensure these paths are correct based on your sidebar
require_once "../app/models/WalletModel.php";

class Wallet extends Controller {
    private $walletModel;

    public function __construct() {
        $this->walletModel = new WalletModel();
    }

    public function index() {
        // Use a hardcoded ID like 1 just to test first if the session isn't set
        $uid = $_SESSION['user_id'] ?? 1; 
        
        $wallet = $this->walletModel->getWalletByUserId($uid);

        $data = [
            'balance' => $wallet['balance'] ?? 0.00,
            'currency' => $wallet['currency'] ?? 'EGP'
        ];

        $this->view("users/Driver/wallet", $data);
    }
}