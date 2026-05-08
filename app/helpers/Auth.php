<?php

class Auth
{
    public static function login($user)
    {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'age'  => $user['age'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'profile_pic'  => $user['profile_pic']
        ];
    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check()
    {
        return isset($_SESSION['user']);
    }

    public static function role($requiredRole)
    {
        return self::check() && $_SESSION['user']['role'] === $requiredRole;
    }

    public static function redirectIfNotLogged()
    {
        if (!self::check()) {
            header("Location: " . BASE_URL . "Auth/login");
            exit;
        }
    }

    public static function forbidIfNotRole($role)
    {
        if (!self::role($role)) {
            header("Location: " . BASE_URL . "Error/error403");
            exit;
        }
    }

}
