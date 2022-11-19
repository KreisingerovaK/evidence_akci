<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Přehled akcí");

  $database = new Db();
  $database->connect();

  $types = $database->selectAll("types");
  $filter = new Form();
  $filter->headerForm("form-inline", "overview.php");
    $filter->formFieldFilter("Název", "text", "name");
    $filter->formSelectFilter("Typ", $types, "type");
    $filter->formFieldFilter("Od", "date", "from");
    $filter->formFieldFilter("Do", "date", "to");
  $filter->endForm("Filtrovat", "btn btn-secondary btn-sm");

  echo '<br>';

  $table = '<table class="table table-hover">';
    $table .= '<thead>';
      $table .= '<tr>';
        $table .= '<th scope="col" width="250">Název akce</th>';
        $table .= '<th scope="col" align="center" width="50">Typ</th>';
        $table .= '<th scope="col" align="center">Akce začala dne</th>';
        $table .= '<th scope="col" align="center" width="50">V</th>';
        $table .= '<th scope="col" align="center">Akce skončila dne</th>';
        $table .= '<th scope="col" align="center" width="50">V</th>';
        $table .= '<th scope="col" align="right" width="150">Počet účastníků</th>';
      $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody>';

      if(isset($_POST["submit"]))
      {
        $filterArray = array();
        if(!empty($_POST["name"])){
          $filterArray[] = 'eventName="'.$_POST["name"].'"';
        }
        if($_POST["type"] != 0){ 
          $filterArray[] = 'typeID="'.$_POST["type"].'"';
        }
        if(!empty($_POST["from"])){ 
          $filterArray[] = 'DATE(eventFrom) = "'.$_POST["from"].'"';
        }
        if(!empty($_POST["to"])){
          $filterArray[] = 'DATE(eventTo) = "'.$_POST["to"].'"';
        }

        $events = $database->selectWhere("events", implode(' AND ', $filterArray));
        while($row = $events->fetch_assoc()) 
        {
          $dateTime = $database->sql('
            SELECT 
              DATE_FORMAT(eventFrom, "%d.%m.%Y") AS fromDate, 
              DATE_FORMAT(eventFrom, "%H:%i") AS fromTime,
              DATE_FORMAT(eventTo, "%d.%m.%Y") AS toDate,
              DATE_FORMAT(eventTo, "%H:%i") AS toTime
            FROM 
              events 
            WHERE 
              eventId = '.$row["eventId"]
          );
          $rowDateTime = $dateTime->fetch_assoc();
          

          $table .= '<tr>';
            $table .= '<th scope="row" width="250">'.$row["eventName"].'</th>';
            $table .= '<td width="50" align="center" >'.$row["typeId"].'</td>';
            $table .= '<td align="center">'.$rowDateTime["fromDate"].'</td>';
            $table .= '<td align="left" width="50">'.$rowDateTime["fromTime"].'</td>';
            $table .= '<td align="center">'.$rowDateTime["toDate"].'</td>';
            $table .= '<td align="left" width="50">'.$rowDateTime["toTime"].'</td>';
            $table .= '<td align="right" width="150">'.$row["numberParticipant"].'</td>';
          $table .= '</tr>';
        }
      }
      else
      {
        $events = $database->selectAll("events");
        while($row = $events->fetch_assoc()) 
        {
          $dateTime = $database->sql('
            SELECT 
              DATE_FORMAT(eventFrom, "%d.%m.%Y"), 
              DATE_FORMAT(eventFrom, "%H:%i"),
              DATE_FORMAT(eventTo, "%d.%m.%Y"),
              DATE_FORMAT(eventTo, "%H:%i")
            FROM 
              events 
            WHERE 
              eventId = '.$row["eventId"]
          );
          $rowDateTime = $dateTime->fetch_assoc();

          $table .= '<tr>';
            $table .= '<th scope="row" width="250">'.$row["eventName"].'</th>';
            $table .= '<td width="50" align="center" >'.$row["typeId"].'</td>';
            $table .= '<td align="center">'.$rowDateTime["DATE_FORMAT(eventFrom, \"%d.%m.%Y\")"].'</td>';
            $table .= '<td align="left" width="50">'.$rowDateTime["DATE_FORMAT(eventFrom, \"%H:%i\")"].'</td>';
            $table .= '<td align="center">'.$rowDateTime["DATE_FORMAT(eventTo, \"%d.%m.%Y\")"].'</td>';
            $table .= '<td align="left" width="50">'.$rowDateTime["DATE_FORMAT(eventTo, \"%H:%i\")"].'</td>';
            $table .= '<td align="right" width="150">'.$row["numberParticipant"].'</td>';
          $table .= '</tr>';
        }
      }
    $table .= '</tbody>';
  $table .= '</table>';

  echo $table;

$html->endHtml();