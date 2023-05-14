<?php

define('UPLOADS_FOLDER', 'uploads');

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

// Если файл существует
if (file_exists(UPLOADS_FOLDER . $data['path'])) {
  rename(UPLOADS_FOLDER . $data['path'], UPLOADS_FOLDER . '/' . $data['name'] . '.' . $data['extension']);

  // Переименовать thumbnail если существует
  if (file_exists(UPLOADS_FOLDER . '/thumbnails' . $data['path'])) {
    rename(UPLOADS_FOLDER . '/thumbnails' . $data['path'], UPLOADS_FOLDER . '/thumbnails' . '/' . $data['name'] . '.' . $data['extension']);
  }

  print '{"status": "ok"}';
} else {
  print '{"status": "not found"}';
}

?>