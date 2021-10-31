<?php
if ($peticionAjax) {
  require_once '../config/SERVER.php';
} else {
  require_once './config/SERVER.php';
}


class MainModel
{
  protected static function connect()
  {
    $conn = new PDO(SGBD, USER, PASSWORD);
    $conn->exec('SET CHARACTER SET utf8');
    return $conn;
  }

  protected static function executeSimpleQuery($query)
  {
    $sql = self::connect()->prepare($query);
    $sql->execute();
    return $sql;
  }

  // Encriptar cadenas de texto
  public static function encryption($string)
  {
    $output = false;
    $key = hash('sha256', SECRET_KEY);
    $indice = substr(hash('sha256', SECRET_ID), 0, 16);
    $output = openssl_encrypt($string, METHOD, $key, 0, $indice);
    $output = base64_encode($output);
    return $output;
  }

  // Desencriptar cadenas de texto
  protected static function decryption($string)
  {
    $output = false;
    $key = hash('sha256', SECRET_KEY);
    $indice = substr(hash('sha256', SECRET_ID), 0, 16);
    $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $indice);
    return $output;
  }

  // limpiar cadenas de inyeccion sql y scripts
  protected static function cleanString($string)
  {
    $string = trim($string);
    $string = stripslashes($string);
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src", "", $string);
    $string = str_ireplace("<script type=", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("DROP DATABASE", "", $string);
    $string = str_ireplace("TRUNCATE TABLE", "", $string);
    $string = str_ireplace("SHOW TABLES", "", $string);
    $string = str_ireplace("SHOW DATABASES", "", $string);
    $string = str_ireplace("<?php", "", $string);
    $string = str_ireplace("?>", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace(">", "", $string);
    $string = str_ireplace("<", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("==", "", $string);
    $string = str_ireplace(";", "", $string);
    $string = str_ireplace("::", "", $string);
    $string = stripslashes($string);
    $string = trim($string);
    return $string;
  }

  // Verificar datos que cumplan con formato
  protected static function checkData($filter, $string)
  {
    return preg_match("/^" . $filter . "$/", $string);
  }

  // Verificar si fecha cumple con formato
  protected static function checkDate($date)
  {
    $values = explode('-', $date);
    if (count($values) == 3 && checkdate($values[1], $values[2], $values[0])) {
      return true;
    } else {
      return false;
    }
  }

  // Funcion generica para generar paginacion de tablas
  protected static function tablePages($page, $nPages, $url, $buttons)
  {
    $table = '<ul class="justify-content-center pagination">';

    if ($page == 1) {
      $table .= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
    } else {
      $table .= '
      <li class="page-item"><a class="page-link" href="' . $url . '1/"><i class="fas fa-angle-double-left"></i></a></li>
      <li class="page-item"><a class="page-link" href="' . $url . ($page - 1) . '/">Anterior</a></li>
      ';
    }

    $count = 0;

    for ($i = 1; $i <= $nPages; $i++) {
      if ($count >= $buttons) {
        break;
      }

      if ($page == $i) {
        $table .= '<li class="page-item active"><a class="page-link active" href="' . $url . $i . '/">' . $i . '</a></li>';
      } else {
        $table .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '/">' . $i . '</a></li>';
      }

      $count++;
    }

    if ($page == $nPages) {
      $table .= '<li class="page-item disabled"><a class="page-link"> <i class="fas fa-angle-double-right"></i></a></li>';
    } else {
      $table .= '
        <li class="page-item"><a class="page-link" href="' . $url . ($page + 1) . '/">Siguiente</a></li>
        <li class="page-item"><a class="page-link" href="' . $url . $nPages . '/"><i class="fas fa-angle-double-right"></i></a></li>
        ';
    }

    $table .= '</ul>';
    return $table;
  }
}
