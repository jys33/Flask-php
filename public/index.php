<?php

session_start();

require_once '../src/app/Db.php';
require_once '../src/app/Flash.php';

$title = 'Posts';

$sql = 'SELECT 
          p.id,
          title,
          body,
          created,
          author_id,
          username 
        FROM
          post p 
          JOIN USER u 
            ON p.author_id = u.id 
        ORDER BY created DESC; ';

$posts = Db::query($sql);

$template = '../views/blog/index.html';

$flashes = null;
if (Flash::hasFlashes()) {
  $flashes = Flash::getFlashes();
}

require_once '../views/base.html';
