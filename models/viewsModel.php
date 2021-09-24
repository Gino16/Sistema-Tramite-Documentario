<?php
class ViewsModel
{
  protected static function getViewsModel($view)
  {
    $listaBlanca = ['inicio', 'persona-new', 'persona-list', 'usuario-new'];
    if (in_array($view, $listaBlanca)) {
      if (is_file('./views/contents/' . $view . '-view.php')) {
        $content = './views/contents/' . $view . '-view.php';
      } else {
        $content = '404';
      }
    } elseif ($view == 'login' || $view == 'index') {
      $content = 'login';
    } else {
      $content = '404';
    }

    return $content;
  }
}
