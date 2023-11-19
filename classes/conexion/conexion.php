<?php

class conexion
{
  private $server;
  private $user;
  private $password;
  private $database;
  private $port;
  private $conexion;

  function __construct()
  {
    //esta variable esta guardando todos los datos de la conexion
    $listadatos = $this->datosConexion();
    foreach ($listadatos as $key => $value) {
      $this->server = $value['server'];
      $this->user = $value['user'];
      $this->password = $value['password'];
      $this->database = $value['database'];
      $this->port = $value['port'];
    }
    $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
    if ($this->conexion->connect_errno) {
      echo 'algo va mal con la conexion';
      die();
    }
  }

  private  function datosConexion()
  {
    $direccion = dirname(__FILE__);
    $jsondata = file_get_contents($direccion . "/" . "config"); //recibe un archivo guarda su contenido y lo devuelve
    return json_decode($jsondata, true); // convierto los datos del json a un array

  }

  //funcion para convertir el array que devuelve la DB en UTF-8 es decir los registros que se consultan
  private function  convertirUTF8($array)
  {
    // esta funcion recibe dos parametros un arreglo un trigger
    //el trigger recibe dos paremetros uno es un parametro por referencia con &                                       
    array_walk_recursive($array, function (&$item, $key) {
      //si no detecta ningún caracter raro              
      if (!mb_detect_encoding($item, 'utf-8', true)) {
        $item = utf8_encode($item);
      }
    });
    return $array;
  }

  //funcion para obetener datos y hacer push de las tuplas en el array
  public function obtenerDatos($sqlStr)
  {

    $results = $this->conexion->query($sqlStr);
    if (!$results) {
      // Manejo de error
      echo "Error en la consulta: " . $this->conexion->error;
      return false;
    }
    $resultArray = array();
    foreach ($results as $key) {
      //crear una nueva tupla en el array
      $resultArray[] = $key;
    }
    return $this->convertirUTF8($resultArray);
  }
  // devuelve el numero de filas afectadas
  public function nonQuery($sqlStr)
  {
    $results = $this->conexion->query($sqlStr);
    return $this->conexion->affected_rows;
  }

  //Insert  
  public function nonQueryId($sqlStr)
  {
    $results = $this->conexion->query($sqlStr);
    $filas = $this->conexion->affected_rows;
    
    if($filas >= 1){
       $algo=$this->conexion->insert_id;
       return $this->conexion->insert_id;
    }else{
        return 0;
    }
  }

  // encriptar
  protected function encriptar($string)
  {
    return md5($string);
  }
}
