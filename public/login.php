<?php
session_start();

require_once '../src/app/Db.php';
require_once '../src/app/Flash.php';

$title = 'Iniciar sesión';

$username = $password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $error = null;

    $rows = Db::query('SELECT * FROM user WHERE username = ?', $username);

    $user = $rows[0] ?? null;

    if ($user == null) {
        $error = "Incorrect username.";
    } elseif (!verifyPassword($password, $user['password'])) {
        $error = "Incorrect password.";
    }

    if ($error == null) {
        # store the user id in a new session and return to the index
        // session . clear();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        header('Location: /index.php');
        exit;
    }

    Flash::addFlash($error, 'danger');
}

$template = '../views/auth/login.html';

$flashes = null;
if (Flash::hasFlashes()) {
    $flashes = Flash::getFlashes();
}

require_once '../views/base.html';


function verifyPassword($password, $passwordHash)
{
    $passwordMatch = (password_verify($password . 'r8UN#uHVX5', $passwordHash) == $passwordHash);
    return $passwordMatch; //True o False
}
