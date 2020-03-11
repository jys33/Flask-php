<?php
session_start();

require_once '../src/app/Db.php';
require_once '../src/app/Flash.php';

$title = 'Registrarse';

$username = $password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $error = null;

    if (empty($username)) {
        $error = 'Username is required.';
    } elseif (empty($password)) {
        $error = 'Password is required.';
    } elseif (
        count(Db::query('SELECT id FROM user WHERE username = ?', $username)) == 1
    ) {
        $error = 'User ' . $username . ' is already registered.';
    }

    if ($error == null) {
        Db::query('INSERT INTO user (username, password) VALUES (?, ?)', $username, encryptPassword($password));
        header('Location: /login.php');
        exit;
    }

    Flash::addFlash($error, 'danger');
}

$template = '../views/auth/register.html';

$flashes = null;
if (Flash::hasFlashes()) {
    $flashes = Flash::getFlashes();
}

require_once '../views/base.html';


// función para encriptar contraseña
function encryptPassword($password)
{
    $passwordHash = password_hash($password . 'r8UN#uHVX5', PASSWORD_BCRYPT, ['cost' => 12]);
    return $passwordHash;
}
