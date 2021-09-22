<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include './views/inc/Link.php' ?>
  <title><?= APPNAME ?></title>
</head>

<body>
  <?php
  require_once './controllers/viewsController.php';
  $viewController = new ViewsController();

  $views = $viewController->getViewsController();

  if ($views == 'login' || $views == '404') {
    require_once  './views/contents/' . $views . '.php';
  } else {
    include $views;
  }

  include './views/inc/Script.php' ?>
</body>

</html>