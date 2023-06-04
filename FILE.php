<?php

require_once "functions/db.php";

if (isset($_GET['image'])) {
    $sql = "SELECT type, data FROM files WHERE name=?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $_GET['image']);
    $statement->execute() or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_connect_error());
    $result = $statement->get_result();

    $row = $result->fetch_assoc();
    header("Content-type: " . $row["type"]);
    echo $row["data"];
}
?>