<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Vytvoření akce: ", "create.js");

  $database = new Db();
  $database->connect();

  // Ulozeni akce
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

    $sql = "INSERT INTO 
              events 
            VALUES (
              '',
              '".$name."',
              '".$type."',
              '".$from."',
              '".$to."',
              '".$numberPar."',
              '".$note."'
            );";
    
    // Ulozeni souboru
    if($database->sql($sql))
    {
      $id = $database->getId();

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

        try
        {
          $MB = 1048576;
          if($fileSize > 5*$MB)
          {
            throw new Exception();
          }
        }
        catch (Exception $e)
        {
          die('<strong>Soubor se nepodařilo uložit - je větší než 5MB.</strong><br>');
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

      // Ulozeni dalsich typu
      $typeN = $database->selectAll("types", "typeId");
      $numTypes = $typeN->num_rows; 
      for ($x = 0; $x <= $numTypes; $x++) {
        if(!empty($_POST[$x."type"]))
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
    }
  }
  $typeN = $database->selectAll("types", "typeId");
  $numTypes = $typeN->num_rows; 

  // Vytvoreni obsahu do body na strance
  $form = new Form();
  $form->headerForm("form-horizontal", "create.php", "return validation(".$numTypes.")");
    $form->formFieldRequired("Název akce","text","name","control-label col-sm-5","","");
    $types = $database->selectAll("types", "typeId");
    $form->formSelectDatabase("Hlavní typ akce", $types, "type", "", "typeId", "typeName", "");
    $form->formFieldRequired("Od","datetime-local","from","","","");
    $form->formFieldRequired("Do","datetime-local","to","","","");
    $types = $database->selectAll("types", "typeId");
    $form->formCheckbox($types, "");
    $form->formTextarea("Poznámka","note","control-label col-sm-7","");
    echo '<div id="files">';
      echo '<div class="form-group pb-2">';
        echo '<label class="control-label col-sm-3">Příloha</label>';
        echo '<input type="file" id="file0" name="fileInput0" onChange="newFile(0)">';
      echo '</div>';
      echo '<div class="form-group pb-2" id="file1">';
      echo '</div>';
    echo '</div>';
    $form->formField("Počet účastníků","number","numberParticipant","control-label col-sm-1","","");
  $form->endForm("Uložit", "btn btn-secondary", "margin-left: 25%;");

$html->endHtml();
