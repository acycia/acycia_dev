<?php

$hostname_conexion1 = "localhost";
$database_conexion1 = "acycia_intranet"; 
$username_conexion1 = "acycia_root";
$password_conexion1 = "ac2006";
$conexion1 = mysql_pconnect($hostname_conexion1, $username_conexion1, $password_conexion1) or trigger_error(mysql_error(),E_USER_ERROR); 

 
?>