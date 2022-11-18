<?php 

class Html
{

  // Metoda pro vytvoreni hlavicky
  function header($name)
  {
    require_once 'autoloader.php'; 

    $html = '<!DOCTYPE html>';
    $html .= '<html>';
      $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">';
        $html .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>';
        $html .= '<title></title>';
      $html .= '</head>';
      $html .= '<body class="bg-light">';
        $html .= '<div class="container bg-white">';
          $html .= '<div class="mt-4 p-5 bg-secondary text-white rounded">';
            $html .= '<h1>'.$name.'</h1>';
          $html .= '</div>';
          $html .= '<div class="row">';
            $html .= '<div class="col-sm-2 p-3">';
              $html .= '<div class="btn-group-vertical">';
                $html .= '<a href="overview.php" class="btn btn-secondary btn-lg">Přehled</a>';
                $html .= '<a href="create.php" class="btn btn-secondary btn-lg">Nová akce</a>';
              $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-sm-10 g-2 p-3 mt-3">';
    echo $html;
  }

  // Metoda pro uzavreni html
  function endHtml()
  {
            $html ='</div>';
          $html .= '</div>';
        $html .= '</div>';
      $html .= '</body>';
    $html .= '</html>';

    echo $html;
  }
}