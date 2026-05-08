<?php

class Auth
{
    public static function login($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user'] = [
            'id'    => $user['userID'] ?? $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'profile_pic'  => $user['profile_pic'] ?? null,
            'phone_num' => $user['phone_num'] ?? null
        ];
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
    }

    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }

    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    public static function role($requiredRole)
    {
        return self::check() && $_SESSION['user']['role'] === $requiredRole;
    }

    public static function redirectIfNotLogged()
    {
        if (!self::check()) {
            header("Location: Auth/login");
            exit;
        }
    }

    public static function forbidIfNotRole($role)
    {
        if (!self::role($role)) {
            header("Location: Error/error403");
            exit;
        }
    }
}