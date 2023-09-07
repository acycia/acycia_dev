<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
  
class Conectar{
    
    public static function conexion(){
        $conexion=new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE)
        or die(mysql_error());
        $conexion->set_charset("utf8");
        return $conexion;
    }

}
 
?>
