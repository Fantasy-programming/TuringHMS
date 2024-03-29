<?php

namespace Classes\Controllers\auth;

class Logout extends \Core\Controller
{
    public function logoutAction(): void
    {

        // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        $params = session_get_cookie_params();

        // Clear the cookie
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

        // Redirect back to the login page
        header("Location: /");
        exit();
    }
}
