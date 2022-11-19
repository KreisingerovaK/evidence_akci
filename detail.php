<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Detail akce");

  $database = new Db();
  $database->connect();



$html->endHtml();