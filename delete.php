<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Smazání akce", "");

  $id = $_GET["id"];

  $database = new Db();
  $database->connect();

  $sql ='DELETE FROM events WHERE eventId = '.$id.';';

  if($database->sql($sql))
  {
    header('Location: overview.php');
  }

$html->endHtml();