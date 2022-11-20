<?php 
require_once('autoloader.php');
$html = new Html();
$html->header("Přehled akcí", "");

  $database = new Db();
  $database->connect();

  $types = $database->selectAll("types","typeId");
  $filter = new Form();
  $filter->headerForm("form-inline", "overview.php");
    $filter->formFieldFilter("Název", "text", "name");
    $filter->formSelectFilter("Hlavní typ", $types, "type", "typeId", "typeName");
    $filter->formFieldFilter("Od", "date", "from");
    $filter->formFieldFilter("Do", "date", "to");
  $filter->endForm("Filtrovat", "btn btn-secondary btn-sm");

  echo '<br>';

  $month = array ("leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec");
  $year = array ();
  $x = 0;
  for ($i = date("Y"); $i >= 2015; $i--) {
    $year[$x] = $i;
    $x++;
  }
  $filter->headerForm("form-inline", "overview.php");
    $filter->formSelect("Počet akcí v", $month, "month", "měsíc", "");
    $filter->formSelect("/", $year, "year", "rok", "");
    echo '&nbsp;';
  $filter->endForm("Zobrazit", "btn btn-secondary btn-sm");

  if(isset($_POST["action"]) && $_POST["action"] == "Zobrazit")
  {
    $numberArray = array();
    
    if($_POST["month"] != 0){
      $sqlMonth = 'MONTH(eventFrom) = '.$_POST["month"];
      $numberArray[] = $sqlMonth;
    }
    if($_POST["year"] != 0){
      $x = 1;
      foreach ($year as $value) {
        if($_POST["year"] == $x){
          $yearValue = $value;
        }
        $x++;
      }
      $sqlYear = 'YEAR(eventFrom) = '.$yearValue;
      $numberArray[] = $sqlYear;
    }

    $numberevents = $database->selectWhere("events", implode(' AND ', $numberArray));
    $result = $numberevents->num_rows;

    echo '<p>Počet akcí: <strong>'.$result.'</strong></p>';
  }

  echo '<br>';

  $table = '<table class="table table-hover">';
    $table .= '<thead>';
      $table .= '<tr>';
        $table .= '<td scope="col" align="left" width="250"><strong>Název akce</strong></td>';
        $table .= '<td scope="col" align="center" width="100"><strong>Hlavní typ</strong></td>';
        $table .= '<td scope="col" align="center"><strong>Akce začala dne</strong></td>';
        $table .= '<td scope="col" align="center" width="50"><strong>V</strong></td>';
        $table .= '<td scope="col" align="center"><strong>Akce skončila dne</strong></td>';
        $table .= '<td scope="col" align="center" width="50"><strong>V</strong></td>';
        $table .= '<td scope="col" align="right" width="150"><strong>Počet účastníků</strong></td>';
      $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody>';

      $numberParticipant = 0;
      $numberRow         = 0;

      if(isset($_POST["action"]) && $_POST["action"] == "Filtrovat")
      {
        $filterArray = array();
        if(!empty($_POST["name"])){
          $filterArray[] = 'eventName="'.$_POST["name"].'"';
        }
        if($_POST["type"] != 0){ 
          $filterArray[] = 'typeId="'.$_POST["type"].'"';
        }
        if(!empty($_POST["from"])){ 
          $filterArray[] = 'DATE(eventFrom) = "'.$_POST["from"].'"';
        }
        if(!empty($_POST["to"])){
          $filterArray[] = 'DATE(eventTo) = "'.$_POST["to"].'"';
        }

        if(empty($filterArray))
        {
          $events = $database->selectAll("events", "eventId DESC");
        }
        else
        {
          $events = $database->selectWhere("events", implode(' AND ', $filterArray));
        }
        $number = $events->num_rows;

        if($number == 0)
        {
          $table .= '<tr>';
            $table .= '<td scope="row" align="center" colspan="7">Nebyl nalezen žádný záznam.</td>';
          $table .= '</tr>';
        }
        else
        {
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

            $nameType = $database->selectWhere("types", "typeId = ".$row["typeId"]);
            $nameType = $nameType->fetch_assoc();           

            $table .= '<tr>';
              $table .= '<td scope="row" width="250"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$row["eventName"].'</a></td>';
              $table .= '<td width="50" align="center" ><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$nameType["typeName"].'</a></td>';
              $table .= '<td align="center"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["fromDate"].'</a></td>';
              $table .= '<td align="left" width="50"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["fromTime"].'</a></td>';
              $table .= '<td align="center"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["toDate"].'</a></td>';
              $table .= '<td align="left" width="50"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["toTime"].'</a></td>';
              $table .= '<td align="right" width="150"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$row["numberParticipant"].'</a></td>';
            $table .= '</tr>';
            $numberParticipant += $row["numberParticipant"];
            $numberRow++;
          }
            
          $table .= '<tfoot>';
            $table .= '<td scope="row" align="left">Počet záznamů: '.$numberRow.'</td>';
            $table .= '<td align="right" colspan="6">Počet účastníků: '.$numberParticipant.'</td>';
          $table .= '</tfoot>';
        }
      }
      else
      {
        $events = $database->selectAll("events", "eventId DESC");
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
          
          $nameType = $database->selectWhere("types", "typeId = ".$row["typeId"]);
          $nameType = $nameType->fetch_assoc();
          
          if(empty($nameType["typeName"]))
          {
            $nameType["typeName"] = '';
          }

          $table .= '<tr>';
            $table .= '<td scope="row" width="250"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$row["eventName"].'</a></td>';
            $table .= '<td width="50" align="center" ><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$nameType["typeName"].'</a></td>';
            $table .= '<td align="center"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["DATE_FORMAT(eventFrom, \"%d.%m.%Y\")"].'</a></td>';
            $table .= '<td align="left" width="50"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["DATE_FORMAT(eventFrom, \"%H:%i\")"].'</a></td>';
            $table .= '<td align="center"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["DATE_FORMAT(eventTo, \"%d.%m.%Y\")"].'</a></td>';
            $table .= '<td align="left" width="50"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$rowDateTime["DATE_FORMAT(eventTo, \"%H:%i\")"].'</a></td>';
            $table .= '<td align="right" width="150"><a class="link-dark" style="text-decoration: none;" href="detail.php?id='.$row["eventId"].'">'.$row["numberParticipant"].'</a></td>';
          $table .= '</tr>';
          $numberParticipant += $row["numberParticipant"];
          $numberRow++;
        }

        $table .= '<tfoot>';
          $table .= '<td scope="row" align="left">Počet záznamů: '.$numberRow.'</td>';
          $table .= '<td scope="row" align="right" colspan="6">Počet účastníků: '.$numberParticipant.'</td>';
        $table .= '</tfoot>';
      }
    $table .= '</tbody>';
  $table .= '</table>';

  echo $table;

$html->endHtml();