<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Úprava akce", "edit.js");

  $id = $_GET["id"];

  $database = new Db();
  $database->connect();

  // Pokud jsou odeslana data, tak se ulozi
  if(isset($_POST["action"]))
  {
    $name      = $_POST["name"];
    $type      = $_POST["type"];
    $from      = $_POST["from"];
    $to        = $_POST["to"];
    $note      = $_POST["note"];
    $numberPar = $_POST["numberParticipant"];

    $name = htmlspecialchars($name);
    $note = htmlspecialchars($note);

    $sql = 'UPDATE 
              events 
            SET
              eventName = "'.$name.'",
              typeId = "'.$type.'",
              eventFrom = "'.$from.'",
              eventTo = "'.$to.'",
              numberParticipant = "'.$numberPar.'",
              note = "'.$note.'"
            WHERE
              eventId = "'.$id.'"
            ;';
    if($database->sql($sql))
    {
      // Ukladani souboru
      $i = 0;
      while(isset($_FILES['fileInput'.$i]))
      {
        $dir = "upload/";
        if(!file_exists($dir))
        {
          mkdir('upload');
        }
        $fileName = $_FILES["fileInput".$i]["name"];
        $targetFile = $dir.$fileName;
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $file = $_FILES["fileInput".$i]["tmp_name"];
        $fileSize = $_FILES["fileInput".$i]["size"];

        if($fileSize > 500000)
        {

        }

        if(move_uploaded_file($file, $targetFile))
        {
          $sql = "INSERT INTO 
                    file 
                  VALUES (
                    '',
                   '".$id."',
                    '".$fileName."'
                  );";
          $database->sql($sql);
        }
        $i++;
      } 

      // Ukladani dalsich typu
      $typeN = $database->selectAll("types", "typeId");
      $numTypes = $typeN->num_rows; 
      for ($x = 0; $x <= $numTypes; $x++) {
        if(!empty($_POST[$x."type"]))
        {
          // Kontrola, zda uz tato akce neni s timto typem spojena
          $sql = " SELECT 
                    *
                  FROM 
                    typesevents
                  WHERE
                    typesId = '".$_POST[$x."type"]."'
                    AND
                    eventId = '".$id."'
                  ";
          $numberRow = $database->sql($sql);
          $numberRow = $numberRow->num_rows;
          // Pokud ne, vytvori se novy zaznam
          if($numberRow <= 0)
          {
            $sql = "INSERT INTO 
                      typesevents 
                    VALUES (
                      '".$_POST[$x."type"]."',
                      '".$id."'
                    );";
    
            $database->sql($sql);
          }
        }
        else
        {
          // Kdyz je policko prazdne, smaze se spojeni mezi typem a touto akci (pokud to existuje)
          $sql = " SELECT 
                    *
                  FROM 
                    typesevents
                  WHERE
                    typesId = '".$x."'
                    AND
                    eventId = '".$id."'
                  ";
          $numberRow = $database->sql($sql);
          $numberRow = $numberRow->num_rows;
          if($numberRow > 0)
          {
            $sql = "DELETE FROM 
                      typesevents 
                    WHERE 
                      typesId = '".$x."'
                      AND
                      eventId = ".$id."
                    ;";
    
            $database->sql($sql);
          }
        }
      }
    }
    header('Location: detail.php?id='.$id);
  }

  // Nactou se informace o akci a jejich typech
  $sql = 'SELECT 
            e.*,
            GROUP_CONCAT(DISTINCT t.typesId ORDER BY t.typesId ASC SEPARATOR " " ) AS types,
            GROUP_CONCAT(tn.typeName ORDER BY tn.typeId ASC SEPARATOR ", ") AS typeName    
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
  $event = $database->sql($sql);
  $row = $event->fetch_assoc();

  // Nactou se informace ojejich souborech
  $sql = 'SELECT 
            e.eventId,
            GROUP_CONCAT(DISTINCT f.fileId ORDER BY fileId ASC SEPARATOR ", ") AS filesId,
            GROUP_CONCAT(f.fileName ORDER BY fileId ASC SEPARATOR ", " ) AS filesName   
          FROM 
            events e
          RIGHT JOIN 
            file f
          ON
            e.eventId = f.eventId
          WHERE 
            e.eventId = '.$id.'          
          ;';
  $file = $database->sql($sql);
  $file = $file->fetch_assoc();

  $nameType = $database->selectWhere("types", "typeId = ".$row["typeId"]);
  $nameType = $nameType->fetch_assoc();
  
  if(empty($nameType["typeName"]))
  {
    $nameType["typeName"] = '';
  }
  if($row["eventFrom"] == "0000-00-00 00:00:00")
  {
    $row["eventFrom"] = '';
  }
  if($row["eventTo"] == "0000-00-00 00:00:00")
  {
    $row["eventTo"] = '';
  }
 
  $files = explode(", ",$file["filesName"]);
  $filesId = explode(", ",$file["filesId"]);
  $typesEvent = explode(" ",$row["types"]);
  
  $typeN = $database->selectAll("types", "typeId");
  $numTypes = $typeN->num_rows; 
  
  // Vytvoreni obsahu do body na strance
  $form = new Form();
  $form->headerForm("form-horizontal", "edit.php?id=".$id, "return validation(".$numTypes.")");
    $form->formFieldRequired("Název akce","text","name","control-label col-sm-5","",$row["eventName"]);
    $types = $database->selectAll("types", "typeId");
    $form->formSelectDatabase("Hlavní typ akce", $types, "type", "", "typeId", "typeName", $nameType["typeId"]);
    $form->formField("Od","datetime-local","from","","", $row["eventFrom"]);
    $form->formField("Do","datetime-local","to","","", $row["eventTo"]);
    $types = $database->selectAll("types", "typeId");
    $form->formCheckbox($types, $typesEvent);
    $form->formTextarea("Poznámka","note","control-label col-sm-7",$row["note"]);
      if(!empty($files[0]))
      {
        $table = '<table class="table table-borderless">';
          $i = 0;
          foreach ($files as $file) 
          {
            $table .= '<tr>';
              $table .= '<td scope="row" align="left" width="25%" class="p-0">Příloha (klikněte pro stáhnutí)</td>';
              $table .= '<td align="left"><strong><a href="upload/'.$file.'" class="link-secondary" download>'.$file.'</a></strong></td>';
              $table .= '<td><a href="deleteFile.php?idFile='.$filesId[$i].'&&idEvent='.$id.'" class="btn btn-secondary">Smazat</a></td>';
            $table .= '</tr>';
            $i++;
          }
        $table .= '</table>';
        echo $table;
      }
    echo '<div id="files">';
      echo '<div class="form-group pb-2">';
        echo '<label class="control-label col-sm-3">Přidat přílohu</label>';
        echo '<input type="file" name="fileInput0" onChange="newFile(0)">';
      echo '</div>';
      echo '<div class="form-group pb-2" id="file0">';
      echo '</div>';
    echo '</div>';
    $form->formField("Počet účastníků","number","numberParticipant","control-label col-sm-1","",$row["numberParticipant"]);
  $form->endForm("Uložit", "btn btn-secondary", "margin-left: 25%;");
  echo '<a href="delete.php?id='.$id.'" class="btn btn-secondary">Smazat akci</a>';

$html->endHtml();
