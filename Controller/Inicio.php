<?php
require_once 'Models/Inicio.php';

class InicioController{

    private $model;

    public function __CONSTRUCT(){
        $this->model = new Inicio();
    }
    public function Index(){
        require_once 'index.php';
    }
 
}