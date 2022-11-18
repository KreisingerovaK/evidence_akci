<?php 
require_once('classes/classHtml.php');

class Form
{
  // Metoda pro vytvoreni hlavicky formulare
  function headerForm()
  {
    $form = '<form class="form-horizontal" action="create.php" method="post" enctype="multipart/form-data">';
    echo $form;
  }

  // Metoda pro vytvoreni pole formulare
  function formField($text, $type, $name, $class, $id, $value)
  {
    $form = '<div class="form-group pb-2">';
      $form .= '<label class="control-label col-sm-3">'.$text.'</label>';
      $form .= '<input type="'.$type.'" value="'.$value.'" class="'.$class.'" id="'.$id.'" name="'.$name.'">';
      $form .= '<input type="hidden" class="col-sm-auto">';
    $form .= '</div>';
    echo $form;
  }

  // Metoda pro vytvoreni selectu formulare
  function formSelect($text, $options, $name, $id)
  {
    $form = '<div class="form-group pb-2">';
      $form .= '<label class="control-label col-sm-3">'.$text.'</label>';
      $form .= '<select id="'.$id.'" name="'.$name.'" class="control-label col-sm-2">';
        while($row = $options->fetch_assoc()) 
        {
          $form .= '<option value="'.$row["typeId"].'">'.$row["typeName"].'</option>';
        }
      $form .= '</select>';
      $form .= '<input type="hidden" class="col-sm-auto">';
    $form .= '</div>';
    echo $form;
  }

  // Metoda pro vytvoreni checkboxu formulare
  function formCheckbox($optionscheck)
  {
    $form = '<div class="form-group">';
      $form .= '<div class="form-check">';
        while($row = $optionscheck->fetch_assoc()) {
          $form .= '<input class="form-check-input col-sm-offset-2 col-sm-5" type="checkbox" id="'.$row["typeId"].'"  name="'.$row["typeId"].'type" value="'.$row["typeId"].'">';
          $form .= '<label class="form-check-label col-sm-5" for="'.$row["typeId"].'">'.$row["typeName"].'</label>';
          $form .= '<br>';
        }
      $form .= '</div>';
    $form .= '</div>';
    echo $form;
  }

  // Metoda pro vytvoreni konce formulare
  function endForm()
  {
    $form = '<input id="button" name="submit" class="btn btn-secondary col-sm-2" type="submit" value=" Uložit ">';
    $form .= '</form>';
    echo $form;
  }
}