<?php

function ensureFolder($folder_name)
{
  if (!file_exists($folder_name)) {
    mkdir($folder_name, 0777, true);
  }
}

?>