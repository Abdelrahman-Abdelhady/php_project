<?php
require_once "../app/models/WalletModel.php";
require_once "../app/models/TransactionModel.php";
require_once "../app/helpers/Auth.php";

class WalletController extends Controller
{
    private $walletModel;
    private $transactionModel;

    public function __construct()
    {
        $this->walletModel      = new WalletModel();
        $this->transactionModel = new TransactionModel();
    }

    // Show wallet page
    public function index()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $userID  = Auth::user()['id'];
        $balance = $this->walletModel->getBalance($userID);

        $this->view("users/Driver/wallet", [
            'balance' => $balance,
        ]);
    }

    // Handle Add Funds form submission
    public function addFunds()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $userID = Auth::user()['id'];
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

        if ($amount <= 0) {
            $_SESSION['wallet_error'] = "Please enter a valid amount greater than 0.";
            header("Location: " . BASE_URL . "Wallet/index");
            exit;
        }

        // Add funds to wallet
        $this->walletModel->addFunds($userID, $amount);

        // Log the transaction
        $this->transactionModel->logTransaction(
            $userID,
            'topup',
            $amount,
            'Wallet top-up'
        );

        $_SESSION['wallet_success'] = "EGP " . number_format($amount, 2) . " added successfully!";
        header("Location: " . BASE_URL . "Wallet/index");
        exit;
    }
}
?>