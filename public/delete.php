<?php
session_start();

require_once '../src/app/Db.php';
require_once '../src/app/Flash.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $post = get_post($_POST['id']);

    Db::query('DELETE FROM post WHERE id = ?', $_POST['id']);
    Flash::addFlash('Post eliminado correctamente.');
    header('Location: /index.php');
    exit;
}

// Obtener post por id y su autor
function get_post($id, $check_autor = true)
{
    $rows = Db::query('SELECT p.id, title, body, created, author_id, username
    FROM post p JOIN user u ON p.author_id = u.id WHERE p.id = ?', $id);

    if (count($rows) != 1) {
        // Abortar con un 404
        throw new Exception('Id ' . $id . ' no existe', 1);
    }

    $post = $rows[0];

    if ($check_autor && $post['author_id'] != $_SESSION['user_id']) {
        // Abortar con un 403
        throw new Exception("Error 403", 1);
    }

    return $post;
}
