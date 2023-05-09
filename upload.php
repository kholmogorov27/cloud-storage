<?php
  $error = "Неизвестная ошибка";

  switch ($_FILES["file"]["error"]) {
    case UPLOAD_ERR_OK:
      // Получаем имя файла
      $name = $_FILES["file"]["name"];
      // Перемещаем файл в директорию uploads с оригинальным именем
      move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/".$name);
      // Перенаправляем пользователя на главную страницу
      header("Location: index.php");
      exit();

    case 4:
      $error = "Файл не выбран";
  }
    
  // Если произошла ошибка, выводим сообщение об ошибке
  echo "Ошибка загрузки файла: ".$error;
?>