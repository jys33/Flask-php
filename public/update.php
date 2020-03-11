<?php
session_start();

require_once '../src/app/Db.php';
require_once '../src/app/Flash.php';

$title = 'Editar';

$post = get_post($_GET['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $titulo = $_POST['title'];
    $body = $_POST['body'];

    $error = null;

    if (empty($titulo)) {
        $error = 'El titulo es requerido.';
    }

    if (empty($body)) {
        $error = 'El body es requerido.';
    }

    if ($error != null) {
        Flash::addFlash($error, 'danger');
    } else {
        Db::query('UPDATE post SET title = ?, body = ? WHERE id = ?', $titulo, $body, $_GET['id']);
        Flash::addFlash('Post guardado correctamente.');
        header('Location: /index.php');
        exit;
    }
}

$template = '../views/blog/update.html';

$flashes = null;
if (Flash::hasFlashes()) {
    $flashes = Flash::getFlashes();
}

require_once '../views/base.html';


// Obtener post por id y su autor
function get_post($id, $check_autor = true)
{
    $rows = Db::query('SELECT p.id, title, body, created, author_id, username
    FROM post p JOIN user u ON p.author_id = u.id WHERE p.id = ?', $id);

    if (count($rows) != 1) {
        print('Id ' . $id . ' no existe<br><br><a href="/">Click aqui!</a>');
        exit;
        // Abortar con un 404
        throw new Exception('Id ' . $id . ' no existe', 1);
    }

    $post = $rows[0];

    if ($check_autor && $post['author_id'] != $_SESSION['user_id']) {
        print('Permiso denegado<br><br><a href="/">Click aqui!</a>');
        exit;
        // Abortar con un 403
        throw new Exception("Error 403", 1);
    }

    return $post;
}
