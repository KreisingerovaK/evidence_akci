<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Detail akce");

  $id = $_GET["id"];

  $database = new Db();
  $database->connect();

  $sql = 'SELECT 
            e.*,
            GROUP_CONCAT(t.typesId SEPARATOR " ") AS types,
            GROUP_CONCAT(DISTINCT tn.typeName SEPARATOR ", ") AS typeName,
            GROUP_CONCAT(DISTINCT f.fileName SEPARATOR ", ") AS files
          FROM 
            events e
          RIGHT JOIN 
            typesevents t
          ON
            e.eventId = t.eventId
          RIGHT JOIN 
            file f
          ON
            e.eventId = f.eventId
          RIGHT JOIN 
            types tn
          ON
            t.typesId = tn.typeId
          WHERE 
            e.eventId = '.$id.'          
          ;';
  $events = $database->sql($sql);
  $event = $events->fetch_assoc();

  $nameType = $database->selectWhere("types", "typeId = ".$event["typeId"]);
  $nameType = $nameType->fetch_assoc();
  
  if(empty($nameType["typeName"]))
  {
    $nameType["typeName"] = '';
  }
 
  $files = explode(", ",$event["files"]);

  $table = '<table class="table table-borderless">';
    $table .= '<tbody>';
    $table .= '<tr class="table-secondary">';
      $table .= '<td scope="row" width="25%" align="left">Jméno akce</td>';
      $table .= '<td align="left"><strong>'.$event["eventName"].'</strong></td>';
    $table .= '</tr>';
    $table .= '<tr>';
      $table .= '<td scope="row" align="left">Hlavní typ akce</td>';
      $table .= '<td align="left"><strong>'.$nameType["typeName"].'</strong></td>';
    $table .= '</tr>';
    $table .= '<tr>';
      $table .= '<td scope="row" align="left">Akce se koná od</td>';
      $table .= '<td align="left"><strong>'.$event["eventFrom"].'</strong></td>';
    $table .= '</tr>';
    $table .= '<tr>';
      $table .= '<td scope="row" align="left">Do</td>';
      $table .= '<td align="left"><strong>'.$event["eventTo"].'</strong></td>';
    $table .= '</tr>';
    $table .= '<tr>';
      $table .= '<td scope="row"  align="left">Počet účastníků</td>';
      $table .= '<td align="left"><strong>'.$event["numberParticipant"].'</strong></td>';
    $table .= '</tr>';
    $table .= '<tr>';
      $table .= '<td scope="row" align="left">Poznámka</td>';
      $table .= '<td align="left"><strong>'.$event["note"].'</strong></td>';
    $table .= '</tr>';
    $table .= '<tr>';
      $table .= '<td scope="row" align="left">Další typy jsou</td>';
      $table .= '<td align="left"><strong>'.$event["typeName"].'</strong></td>';
    $table .= '</tr>';
    foreach ($files as $file) {
      $table .= '<tr>';
        $table .= '<td scope="row" align="left">Příloha (klikněte pro stáhnutí)</td>';
        $table .= '<td align="left"><strong><a href="upload/'.$file.'" class="link-secondary" download>'.$file.'</a></strong></td>';
      $table .= '</tr>';
    }
    $table .= '</tbody>';
  $table .= '</table>';

  echo $table;

$html->endHtml();