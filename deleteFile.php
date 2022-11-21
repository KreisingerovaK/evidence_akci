<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Smazání přílohy", "");

  $idEvent = $_GET["idEvent"];
  $idFile = $_GET["idFile"];

  $database = new Db();
  $database->connect();

  $sql ='DELETE FROM file WHERE fileId = '.$idFile.';';

  if($database->sql($sql))
  {
    header('Location: edit.php?id='.$idEvent);
  }

$html->endHtml();