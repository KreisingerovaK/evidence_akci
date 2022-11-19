<?php 
require_once('classes/classHtml.php');

class Form
{
  // Metoda pro vytvoreni hlavicky formulare
  function headerForm($class, $action)
  {
    $form = '<form class="'.$class.'" action="'.$action.'" method="post" enctype="multipart/form-data">';
    echo $form;
  }

  // Metoda pro vytvoreni konce formulare
  function endForm($value, $class)
  {
    $form = '<input id="button" name="submit" class="'.$class.'" type="submit" value="'.$value.'">';
    $form .= '</form>';
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
        $form .= '<option value="0">Vyberte možnost...</option>';
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

  // Metoda pro vytvoreni textarey
  function formTextarea($text, $name, $class)
  {
    $form = '<div class="form-group">';
      $form .= '<label class="control-label col-sm-3">'.$text.'</label>';
      $form .= '<textarea class="'.$class.'" rows="3" name="'.$name.'"></textarea>';
      $form .= '<input type="hidden" class="col-sm-6">';
    $form .= '</div>';
    echo $form;
  }
  
  // Metoda pro vytvoreni pole formulare
  function formFieldFilter($text, $type, $name)
  {
      $form = '<label>'.$text.'&nbsp;</label>';
      $form .= '<input name="'.$name.'" type="'.$type.'" style="margin-right: 15px;">';
    echo $form;
  }

  // Metoda pro vytvoreni selectu formulare
  function formSelectFilter($text, $options, $name)
  {
    $form = '<label>'.$text.'&nbsp;</label>';
    $form .= '<select name="'.$name.'" style="margin-right: 15px;">';
      $form .= '<option value="0">Vyberte možnost...</option>';
      while($row = $options->fetch_assoc()) 
      {
        $form .= '<option value="'.$row["typeId"].'">'.$row["typeName"].'</option>';
      }
    $form .= '</select>';
    echo $form;
  }
}