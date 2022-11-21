<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Vytvoření akce: ", "create.js");

  $database = new Db();
  $database->connect();

  if(isset($_POST["action"]))
  {
    $name      = $_POST["name"];
    $type      = $_POST["type"];
    $from      = $_POST["from"];
    $to        = $_POST["to"];
    $note      = $_POST["note"];
    $numberPar = $_POST["numberParticipant"];

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
    
    if($database->sql($sql))
    {
      $id = $database->getId();

      $i = 0;
      while(isset($_FILES['file'.$i]))
      {
        $dir = "upload/";
        $fileName = $_FILES["file".$i]["name"];
        $targetFile = $dir.$fileName;
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $file = $_FILES["file".$i]["tmp_name"];
        $fileSize = $_FILES["file".$i]["size"];

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
  $form = new Form();
  $form->headerForm("form-horizontal", "create.php", "return handleData()");
    $form->formFieldRequired("Název akce","text","name","control-label col-sm-5","","");
    $types = $database->selectAll("types", "typeId");
    $form->formSelectDatabase("Hlavní typ akce", $types, "type", "", "typeId", "typeName", "");
    $form->formField("Od","datetime-local","from","","","");
    $form->formField("Do","datetime-local","to","","","");
    $types = $database->selectAll("types", "typeId");
    $form->formCheckbox($types, "");
    $form->formTextarea("Poznámka","note","control-label col-sm-7","");
    echo '<div id="files">';
      echo '<div class="form-group pb-2">';
        echo '<label class="control-label col-sm-3">Příloha</label>';
        echo '<input type="file" value="" class="" name="file0" onChange="newFile(0)">';
      echo '</div>';
      echo '<div class="form-group pb-2" id="file0">';
      echo '</div>';
    echo '</div>';
    $form->formField("Počet účastníků","number","numberParticipant","control-label col-sm-1","","");
  $form->endForm("Uložit", "btn btn-secondary", "margin-left: 25%;");

$html->endHtml();
