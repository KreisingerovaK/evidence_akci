<?php 
require_once('classes/classHtml.php');
$html = new Html();
$html->header("Formulář pro vytvoření akce: ");

  $database = new Db();
  $database->connect();

  if(!empty($_POST["submit"]))
  {
    $name = $_POST["name"];
    $type = $_POST["type"];
    $from = $_POST["from"];
    $to = $_POST["to"];
    $note = $_POST["note"];
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
    
    $database->sql($sql);
    $id = $database->getId();

    if(!empty($_FILES['file']))
    {
      $data = file_get_contents($_FILES['file']['tmp_name']);
      $sql = "INSERT INTO 
                file 
              VALUES (
                '',
                '".$id."',
                '".$_FILES['file']['name']."',
                '".$data."'
              );";
    
        $database->sql($sql);
    }

    $typeN = $database->selectAll("types");
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
  $form = new Form();
  $form->headerForm();
    $form->formField("Název akce","text","name","control-label col-sm-5","","");
    $types = $database->selectAll("types");
    $form->formSelect("Typ akce", $types, "type", "");
    $form->formField("Od","datetime-local","from","","","");
    $form->formField("Do","datetime-local","to","","","");
    $types = $database->selectAll("types");
    $form->formCheckbox($types);
    $form->formField("Poznámka","text","note","control-label col-sm-7","","");
    $form->formField("Příloha","file","file","","","");
    $form->formField("Počet účastníků","number","numberParticipant","control-label col-sm-1","","");
  $form->endForm();

$html->endHtml();
