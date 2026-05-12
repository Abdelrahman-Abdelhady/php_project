<?php

// Using __DIR__ ensures the path is absolute and won't break 
// regardless of where the file is called from.
require_once __DIR__ . "/../models/WalletModel.php";
require_once __DIR__ . "/../helpers/Auth.php";

class WalletController extends Controller
{
    private $walletModel;

    public function __construct()
    {
        $this->walletModel = new WalletModel();
    }

    public function index()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $userID = Auth::user()['id'];
        $wallet = $this->walletModel->getWalletByUserId($userID);

        // Auto-create wallet if one doesn't exist for this driver
        if (!$wallet) {
            $this->walletModel->createWalletForUser($userID);
            $wallet = $this->walletModel->getWalletByUserId($userID);
        }

        $this->view("users/Driver/wallet", [
            'wallet' => $wallet,
            'errors' => [],
            'old'    => []
        ]);
    }

    public function doTopUp()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $amount = $_POST['amount'] ?? '';
        $userID = Auth::user()['id'];
        $errors = [];

        if (!is_numeric($amount) || $amount <= 0) {
            $errors['amount'] = 'Please enter a valid amount.';
        }

        if (!empty($errors)) {
            $wallet = $this->walletModel->getWalletByUserId($userID);
            $this->view("users/Driver/wallet", [
                'wallet' => $wallet,
                'errors' => $errors,
                'old'    => $_POST
            ]);
            return;
        }

        $topped = $this->walletModel->topUp($userID, (float)$amount);

        if (!$topped) {
            $wallet = $this->walletModel->getWalletByUserId($userID);
            $this->view("users/Driver/wallet", [
                'wallet' => $wallet,
                'errors' => ['topup' => 'Something went wrong. Please try again.'],
                'old'    => $_POST
            ]);
            return;
        }

        // After successful top-up, send them back to the index to see the new balance
        header("Location: " . BASE_URL . "Wallet/index");
        exit;
    }
}