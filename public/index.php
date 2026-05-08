
//require_once '../core/App.php';
//require_once '../core/Controller.php';
//require_once '../core/Database.php';

//define("BASE_URL", '/' . basename(dirname(__DIR__)) . '/public/');

//$app = new App();
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';
require_once 'app/core/Auth.php';
require_once 'app/helpers/Validator.php';
require_once 'app/helpers/Upload.php';
require_once 'app/models/UserModel.php';

$app = new App();