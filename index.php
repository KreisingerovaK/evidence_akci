<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Úvodní stránka");

$databaze = new CreateDb();
//$databaze->create();

$html->endHtml();