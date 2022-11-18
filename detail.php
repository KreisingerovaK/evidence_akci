<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Detail akce");

  $database = new Db();
  $database->connect();

  $file = $database->selectAll("file");

  while($row = $file->fetch_assoc()) {
    print_r($row["content"]);
  }

$html->endHtml();