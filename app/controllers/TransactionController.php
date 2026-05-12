<?php
require_once "../app/models/TransactionModel.php";
require_once "../app/helpers/Auth.php";

class TransactionController extends Controller
{
    private $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $userID       = Auth::user()['id'];
        $transactions = $this->transactionModel->getTransactionsByUserId($userID);

        $this->view("users/Driver/transaction", [
            'transactions' => $transactions,
        ]);
    }
}