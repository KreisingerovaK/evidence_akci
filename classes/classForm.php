<?php 
require_once('classes/classHtml.php');

class Form
{
  // Metoda pro vytvoreni hlavicky formulare
  function headerForm($class, $action, $function)
  {
    $form = '<form onsubmi="'.$function.'" class="'.$class.'" action="'.$action.'" method="post" enctype="multipart/form-data">';
    echo $form;
  }

  // Metoda pro vytvoreni konce formulare
  function endForm($value, $class, $style)
  {
    $form = '<input style="'.$style.'" id="button" name="action" class="'.$class.'" type="submit" value="'.$value.'">';
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

  // Metoda pro vytvoreni pole formulare, které je nutné vyplnit 
  function formFieldRequired($text, $type, $name, $class, $id, $value)
  {
    $form = '<div class="form-group pb-2">';
      $form .= '<label class="control-label col-sm-3">'.$text.'</label>';
      $form .= '<input type="'.$type.'" value="'.$value.'" class="'.$class.'" id="'.$id.'" name="'.$name.'" required>';
      $form .= '<input type="hidden" class="col-sm-auto">';
    $form .= '</div>';
    echo $form;
  }

  // Metoda pro vytvoreni selectu formulare s daty z databáze
  function formSelectDatabase($text, $options, $name, $id, $valueColumn, $optionNameColumn, $selected)
  {
    $form = '<div class="form-group pb-2">';
      $form .= '<label class="control-label col-sm-3">'.$text.'</label>';
      $form .= '<select id="'.$id.'" name="'.$name.'" class="control-label col-sm-2">';
        $form .= '<option value="0">Vyberte možnost...</option>';
        //echo $selected;
        while($row = $options->fetch_assoc()) 
        {
          if($selected == $row[$optionNameColumn])
          {
            $form .= '<option value="'.$row[$valueColumn].'" selected>'.$row[$optionNameColumn].'</option>';
          }
          else
          {
            $form .= '<option value="'.$row[$valueColumn].'">'.$row[$optionNameColumn].'</option>';
          }
        }
      $form .= '</select>';
      $form .= '<input type="hidden" class="col-sm-auto">';
    $form .= '</div>';
    echo $form;
  }

  // Metoda pro vytvoreni selectu formulare
  function formSelect($text, $options, $name, $firstOption, $function)
  {
    $form = '<label>'.$text.'&nbsp;</label>';
    $form .= '<select name="'.$name.'" '.$function.'>';
      $form .= '<option value="0">'.$firstOption.'</option>';
      $i = 1;
      foreach($options as $value) 
      {
        $form .= '<option value="'.$i.'">'.$value.'</option>';
        $i++;
      }
    $form .= '</select>';
    echo $form;
  }

  // Metoda pro vytvoreni checkboxu formulare
  function formCheckbox($optionsCheck, $optionChecked)
  {
    $form = '<div class="form-group">';
      $i = 0;
      while($row = $optionsCheck->fetch_assoc()) 
      {
        $form .= '<div style="margin-left: 25%;" class="form-check">';
          if(isset($optionChecked[$i]))
          {
            if($optionChecked[$i] == $row["typeName"])
            {
              $form .= '<input class="form-check-input col-sm-offset-2 col-sm-5" type="checkbox" id="'.$row["typeId"].'"  name="'.$row["typeId"].'type" value="'.$row["typeId"].'" checked>';
              $i++;
            }
            else
            {
              $form .= '<input class="form-check-input col-sm-offset-2 col-sm-5" type="checkbox" id="'.$row["typeId"].'"  name="'.$row["typeId"].'type" value="'.$row["typeId"].'">';
            }
          }
          else
          {
            $form .= '<input class="form-check-input col-sm-offset-2 col-sm-5" type="checkbox" id="'.$row["typeId"].'"  name="'.$row["typeId"].'type" value="'.$row["typeId"].'">';
          }
          $form .= '<label class="form-check-label col-sm-5" for="'.$row["typeId"].'">'.$row["typeName"].'</label>';
        $form .= '</div>';
      }
    $form .= '</div>';
    echo $form;
  }

  // Metoda pro vytvoreni textarey
  function formTextarea($text, $name, $class, $value)
  {
    $form = '<div class="form-group">';
      $form .= '<label class="control-label col-sm-3" height="150" >'.$text.'</label>';
      $form .= '<textarea class="'.$class.'" name="'.$name.'" >'.$value.'</textarea>';
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
  function formSelectFilter($text, $options, $name, $valueColumn, $optionNameColumn)
  {
    $form = '<label>'.$text.'&nbsp;</label>';
    $form .= '<select name="'.$name.'" style="margin-right: 10px;">';
      $form .= '<option value="0">Vyberte možnost...</option>';
      while($row = $options->fetch_assoc()) 
      {
        $form .= '<option value="'.$row[$valueColumn].'">'.$row[$optionNameColumn].'</option>';
      }
    $form .= '</select>';
    echo $form;
  }
}