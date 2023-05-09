<?php

function scanFiles($folder)
{
  // Получаем список файлов в директории uploads 
  $files = scandir($folder);

  // Удаляем папки из массива
  foreach ($files as $key => $link) {
    if (is_dir($folder . '/' . $link)) {
      unset($files[$key]);
    }
  }

  return $files;
}

?>