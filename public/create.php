<?php
session_start();

require_once '../src/app/Db.php';
require_once '../src/app/Flash.php';

$title = 'Nuevo Post';
$titulo = $body = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST["title"];
    $body = $_POST["body"];
    $error = null;

    if (empty($titulo)) {
        $error = "Title is required.";
    }

    if (empty($body)) {
        $error = 'El body es requerido.';
    }

    if ($error != null) {
        Flash::addFlash($error, 'danger');
    } else {
        Db::query('INSERT INTO post (title, body, author_id) VALUES (?, ?, ?)', $titulo, $body, $_SESSION['user_id']);
        Flash::addFlash('Post guardado correctamente.');
        header('Location: /index.php');
        exit;
    }
}

$template = '../views/blog/create.html';

$flashes = null;
if (Flash::hasFlashes()) {
    $flashes = Flash::getFlashes();
}

require_once '../views/base.html';
