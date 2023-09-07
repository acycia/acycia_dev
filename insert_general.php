<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);
 //------------------------------------------------------------------
//--------------------------INVENTARIOS-----------------------------


  if(isset($_POST['add'])) { 
      $ref=$_POST['ref'];
      $version=$_POST['version'];
      $fecha=$_POST['fecha'];
      $usuario=$_POST['usuario'];
      $obs=$_POST['obs'];
  	$sqlinv="INSERT INTO `tbl_observaciones_ref`(`ref`, `version`, `fecha`, `usuario`, `obs`) VALUES ('$ref','$version','$fecha','$usuario','$obs')";
    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());

    header("location:obs_ref.php?id_ref=$ref");
 
 	  }


  if(isset($_POST['edit'])) { 
      $id=$_POST['id'];
      $ref=$_POST['ref'];
      $version=$_POST['version'];
      $fecha=$_POST['fecha'];
      $usuario=$_POST['usuario'];
      $obs=$_POST['obs'];
    $sql="UPDATE `tbl_observaciones_ref` SET `ref`='$ref',`version`='$version',`fecha`='$fecha',`usuario`='$usuario',`obs`='$obs' WHERE id='$id'";
    $result=mysql_query($sql);
    $Result2 = mysql_query($sql, $conexion1) or die(mysql_error());

    header("location:obs_ref.php?id_ref=$ref");
 
  }   
 ?>