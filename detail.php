<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Detail akce", "");

  $id = $_GET["id"];

  $database = new Db();
  $database->connect();

  // Nactou se informace o akci a jejich typech
  $sql = 'SELECT 
            e.*,
            GROUP_CONCAT(t.typesId SEPARATOR " ") AS types,
            GROUP_CONCAT(tn.typeName SEPARATOR ", ") AS typeName
          FROM 
            events e
          RIGHT JOIN 
            typesevents t
          ON
            e.eventId = t.eventId
          RIGHT JOIN 
            types tn
          ON
            t.typesId = tn.typeId
          WHERE 
            e.eventId = '.$id.'          
          ;';
  $events = $database->sql($sql);
  $event = $events->fetch_assoc();

  // Nactou se soubory
  $sql = 'SELECT 
            e.eventId,
            GROUP_CONCAT(f.fileName ORDER BY fileId SEPARATOR ", ") AS files
          FROM 
            events e
          RIGHT JOIN 
            file f
          ON
            e.eventId = f.eventId
          WHERE 
            e.eventId = '.$id.'          
          ;';
  $files = $database->sql($sql);
  $files = $files->fetch_assoc();     

  $nameType = $database->selectWhere("types", "typeId = ".$event["typeId"]);
  $nameType = $nameType->fetch_assoc();
  
  if(empty($nameType["typeName"]))
  {
    $nameType["typeName"] = '';
  }
 
  $file = explode(", ",$files["files"]);

  // Vypocita se, jak dlouho akce trvala
  $from = new DateTimeImmutable($event["eventFrom"]);
  $to = new DateTimeImmutable($event["eventTo"]);
  $interval = $from->diff($to);
  $days = $interval->format('%a&nbsp;');
  $hours = $interval->format('%H&nbsp;');
  switch($days){
    case 1:
      $days .= "den";
      break;
    case 2:
      $days .= "dny";
      break;
    case 3:
      $days .= "dny";
      break;
    case 4:
      $days .= "dny";
      break;
    default:
      $days .= "dní";
  }
  switch($hours){
    case 01:
      $hours .= "hodinu";
      break;
    case 02:
      $hours .= "hodiny";
      break;
    case 03:
      $hours .= "hodiny";
      break;
    case 04:
      $hours .= "hodiny";
      break;
    default:
      $hours .= "hodin";
  }

  // Vytvori se tabulka s informacemi o akci
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
      $table .= '<td scope="row" align="left">Akce trvala</td>';
      $table .= '<td align="left"><strong>'.$days.'&nbsp;a&nbsp;'.$hours.'</strong></td>';
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
    foreach ($file as $fileName) {
      $table .= '<tr>';
        $table .= '<td scope="row" align="left">Příloha (klikněte pro stáhnutí)</td>';
        $table .= '<td align="left"><strong><a href="upload/'.$fileName.'" class="link-secondary" download>'.$fileName.'</a></strong></td>';
      $table .= '</tr>';
    }
    $table .= '<tr>';
      $table .= '<td scope="row"></td>';
      $table .= '<td scope="row" align="left"><a href="edit.php?id='.$id.'" class="btn btn-secondary">Upravit</a></td>';
    $table .= '</tr>';
    $table .= '</tbody>';
  $table .= '</table>';

  echo $table;

$html->endHtml();