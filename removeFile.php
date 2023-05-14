<?php

define('UPLOADS_FOLDER', 'uploads');

header('Content-Type: application/json; charset=utf-8');

$path = json_decode(file_get_contents("php://input"), true)['path'];

// Если файл существует
if ($path && file_exists(UPLOADS_FOLDER . $path)) {
  unlink(UPLOADS_FOLDER . $path);

  // Удалить thumbnail если существует
  if (file_exists(UPLOADS_FOLDER . '/thumbnails' . $path)) {
    unlink(UPLOADS_FOLDER . '/thumbnails' . $path);
  }

  print '{"status": "ok"}';
} else {
  print '{"status": "not found"}';
}

?>