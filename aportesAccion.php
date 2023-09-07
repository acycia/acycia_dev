<?php
 require_once('Connections/conexion1.php');

$conexion1 = mysql_pconnect($hostname_conexion1, $username_conexion1, $password_conexion1) or trigger_error(mysql_error(),E_USER_ERROR); 

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action == 'registrar') {
    $aportes = aportesModel::getAll();
    $isPost = count($_POST) > 0;
    if ($isPost) {
      $data = [
	      'id_aporte' => $_POST['id_aporte'],
          'salarioBasico' => $_POST['salarioBasico'],
		  'auxilioTrans' => $_POST['auxilioTrans'],
		  'cesantias' => $_POST['cesantias'],
		  'interesCesantias' => $_POST['interesCesantias'],
		  'prima' => $_POST['prima'],
		  'salud' => $_POST['salud'],
		  'pension' => $_POST['pension'],
		  'vacaciones' => $_POST['vacaciones'],
		  'cajaCompensacion' => $_POST['cajaCompensacion'],
		  'sena' => $_POST['sena'],
		  'arl' => $_POST['arl']
      ];
      aportesModel::insert($data);
      header("location:aportesAccion.php?action=consultar");
    } else {
      require 'aportes.php';
    }
  } 
  else if ($action == 'consultar') {
    $aportes = aportesModel::getAll();
    require 'aportes_vista.php';
  } else if($action == 'eliminar'){
      $id_aporte = $_GET['id_aporte'];
      aportesModel::delete($id_aporte);
       header("location:aportesAccion.php?action=consultar");
  }else if($action == 'modificar'){
    $isPost = count($_POST)>0;
    if($isPost){
      $data = [
          'salarioBasico' => $_POST['salarioBasico'],
		  'auxilioTrans' => $_POST['auxilioTrans'],
		  'cesantias' => $_POST['cesantias'],
		  'interesCesantias' => $_POST['interesCesantias'],
		  'prima' => $_POST['prima'],
		  'salud' => $_POST['salud'],
		  'pension' => $_POST['pension'],
		  'vacaciones' => $_POST['vacaciones'],
		  'cajaCompensacion' => $_POST['cajaCompensacion'],
		  'sena' => $_POST['sena'],
		  'arl' => $_POST['arl'],
	      'id_aporte' => $_POST['id_aporte']		  
      ];
      aportesModel::update($data);
       header("location:aportesAccion.php?action=consultar");
    }else{
      $id_aporte = $_GET['id_aporte'];
      $aportes = aportesModel::find($id_aporte);
      require 'aportes_edit.php';
    }
  }
  
} else {
  header("location:aportes.php?action=consultar");
}


//require 'file:///C|/xampp/htdocs/parcial2/vistas/include/footer.php';
