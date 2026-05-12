<?php
class Auth {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($user) {
        self::init();
        $_SESSION['user'] = [
            'id'    => $user['userID'], 
            'name'  => $user['name'],
            'role'  => $user['role']
        ];
    }

    public static function user() {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    public static function role($role) {
        self::init();
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === $role);
    }

    public static function redirectIfNotLogged() {
        self::init();
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "Auth/login");
            exit;
        }
    }

    public static function forbidIfNotRole($role) {
        if (!self::role($role)) {
            header("Location: " . BASE_URL . "User/index");
            exit;
        }
    }
}