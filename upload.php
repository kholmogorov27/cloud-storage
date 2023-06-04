<?php

require_once "functions/createThumbnail.php";
require_once "functions/ensureFolder.php";
require_once "functions/db.php";

$error = "Неизвестная ошибка";

switch ($_FILES["file"]["error"]) {
  case UPLOAD_ERR_OK:

    ensureFolder("uploads");

    // Получаем имя файла
    $name = $_FILES["file"]["name"];

    $data = file_get_contents($_FILES['file']['tmp_name']);
    $type = $_FILES['file']['type'];
    $sql = "INSERT INTO `files` (`name`, `extension`, `type`, `data`) VALUES(?, ?, ?, ?)";
    print($sql);
    $statement = $connection->prepare($sql);
    $statement->bind_param('ssss', $name, $name, $type, $data);
    $current_id = $statement->execute() or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_connect_error());
    
    // Перемещаем файл в директорию uploads с оригинальным именем
    move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/$name");

    ensureFolder("uploads/thumbnails");
    createThumbnail("uploads/$name", "uploads/thumbnails/$name", 200);

    // Перенаправляем пользователя на главную страницу
    //header("Location: index.php");
    exit;

  case 4:
    $error = "Файл не выбран";
}

// Если произошла ошибка, выводим сообщение об ошибке
echo "Ошибка загрузки файла: " . $error;
?>