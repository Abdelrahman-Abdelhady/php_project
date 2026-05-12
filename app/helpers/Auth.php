<?php

class Auth
{
    public static function login($user)
    {
        $_SESSION['user'] = [
            'id'          => $user['id'] ?? null,
            'name'        => $user['name'] ?? '',
            'email'       => $user['email'] ?? '',
            'role'        => $user['role'] ?? '',
            'profile_pic' => $user['profile_pic'] ?? null
        ];
    }

    public static function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['redirect_after_login']);
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
        return self::check() && ($_SESSION['user']['role'] ?? null) === $requiredRole;
    }

    public static function redirectIfNotLogged($loginRoute = "Auth/login")
    {
        if (!self::check()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

            header("Location: " . BASE_URL . $loginRoute);
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