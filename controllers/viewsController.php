<?php
require_once './models/viewsModel.php';

class ViewsController extends ViewsModel
{
  public function getPlantillaController()
  {
    return require_once './views/plantilla.php';
  }

  public function getViewsController()
  {
    if (isset($_GET['views'])) {
      $ruta = explode('/', $_GET['views']);
      $respuesta = ViewsModel::getViewsModel($ruta[0]);
    } else {
      $respuesta = 'login';
    }
    return $respuesta;
  }
}
