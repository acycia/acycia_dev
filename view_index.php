<?php
//require_once 'models/Occomercial.php';
require_once("db/db.php");

$controller = 'inicio';

// Todo esta lógica hara el papel de un FrontController
if(!isset($_REQUEST['c']))
{
    require_once "Controller/$controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    $controller->Index();    
}
else
{
    // Obtenemos el controlador que queremos cargar
    $controller = strtolower($_REQUEST['c']);
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index'; 
    // Instanciamos el controlador
    require_once "Controller/$controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
 
    // Llama la accion
    call_user_func( array( $controller, $accion ) );
}
?>