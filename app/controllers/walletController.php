<?php

require_once "../app/models/walletModel.php";
require_once "../app/helpers/Auth.php";

class WalletController extends Controller
{
    // rest stays the same...
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

        $this->view("users/Driver/wallet", [
            'wallet' => $wallet,
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

        header("Location: " . BASE_URL . "Wallet/index");
        exit;
    }
}