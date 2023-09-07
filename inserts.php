<?php require_once('Connections/conexion1.php'); ?>
<?php
 //------------------------------------------------------------------
//--------------------------INVENTARIOS-----------------------------
 $insert=$_POST['insert'];//PUEDE SER POST O GET
 //VARIABLES
/* $saldoV=($_REQUEST[SaldoViejo]);
 $saldoV= ($_POST[Descripcion]);*/
 switch($insert) {
	case 0: 
    $sqlinv="INSERT INTO TblInventarioHistory (Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, Salida, Final, CostoUnd, Acep, Tipo, Responsable, Modifico,FechaModif)
	SELECT Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, Salida, Final,  CostoUnd, Acep, Tipo, Responsable, Modifico, FechaModif FROM TblInventarioListado ORDER BY idInv ASC";
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());
  $id=0;
  header("location:inventario.php?id=$id");  	 
	  break;
 	  } 
 ?>