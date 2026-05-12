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
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check()
    {
        return isset($_SESSION['user']) && is_array($_SESSION['user']);
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

    public static function forbidIfNotRole($role)
    {
        if (!self::check()) {
            header("Location: " . BASE_URL . "Auth/login");
            exit;
        }

        if (!self::role($role)) {
            header("Location: " . BASE_URL . "Error/error403");
            exit;
        }
    }

    public static function forbidIfNotAdmin()
    {
        if (!self::check()) {
            header("Location: " . BASE_URL . "Auth/adminLogin");
            exit;
        }

        if (!self::role('admin')) {
            header("Location: " . BASE_URL . "Error/error403");
            exit;
        }
    }

    public static function forbidIfNotDriver()
    {
        if (!self::check()) {
            header("Location: " . BASE_URL . "Auth/login");
            exit;
        }

        if (!self::role('driver')) {
            header("Location: " . BASE_URL . "Error/error403");
            exit;
        }
    }

    public static function forbidIfNotOwner()
    {
        if (!self::check()) {
            header("Location: " . BASE_URL . "Auth/login");
            exit;
        }

        if (!self::role('space_owner')) {
            header("Location: " . BASE_URL . "Error/error403");
            exit;
        }
    }
}