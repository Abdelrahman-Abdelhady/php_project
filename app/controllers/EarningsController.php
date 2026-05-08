<?php

class EarningsController {

    public function showEarnings() {

        session_start();

        require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/Earnings.php';

        $db = Database::getInstance()->getConnection();

        $model = new Earnings($db);

        $ownerid = $_SESSION['user_id'];

        return $model->getEarnings($ownerid);
    }
}