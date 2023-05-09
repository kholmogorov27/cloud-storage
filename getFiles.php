<?php

define('UPLOADS_FOLDER', 'uploads');

require_once 'functions/scanFiles.php';

header('Content-Type: application/json; charset=utf-8');

$data = [];

// Если папка существует
if (file_exists(UPLOADS_FOLDER)) {
  $files = scanFiles(UPLOADS_FOLDER);
  foreach ($files as $file) {
    $file_path = UPLOADS_FOLDER . '/' . $file;
    array_push($data, [
      'name' => pathinfo($file_path, PATHINFO_FILENAME),
      'extension' => pathinfo($file_path, PATHINFO_EXTENSION),
      'date' => filemtime($file_path),
      'path' => str_replace(UPLOADS_FOLDER, '', $file_path)
    ]);
  }
}

print json_encode($data);
?>