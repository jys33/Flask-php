<?php

session_start();

logout();

// redirect user
header('Location: /');
exit;

function logout()
{
    // Unset all of the session variables.
    $_SESSION = [];

    // destroy the session
    session_destroy();
}
