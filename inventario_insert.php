<?php require_once('Connections/conexion1.php'); ?>
<?php 
//INSERT INVENTARIO
 
//variables POST DESDE AJAX
  $fecha=$_POST['fecha'] ;  
  $codigo=$_POST['codigo']; 
  $cod_r=explode("-",$codigo);
  $cod_ref=$cod_r[0];//solamente referencia
  $final=$_POST['final'];
  $entrada=$_POST['entrada'];
  $costoUnd=$_POST['costo'];
  $acep=$_POST['acep'];
  $tipo=$_POST['tipo'];
  $responsable=$_POST['responsable'];
   
//SI EXISTE EL INSUMO O MATERIA PRIMA SE ACTUALIZA CANTIDAD DE ENTRANDA
	$sqling="SELECT Codigo FROM TblInventarioListado WHERE Codigo = '$codigo'";
	$resulting= mysql_query($sqling);
	$numing= mysql_num_rows($resulting);
	if($numing >='1') {
    $sqlinv="UPDATE TblInventarioListado SET Fecha='$fecha', Cod_ref='$cod_ref', Codigo='$codigo', SaldoInicial='$final', Entrada = '$entrada', CostoUnd='$costoUnd', Acep='$acep', Tipo='$tipo', Modifico='$responsable' WHERE Codigo = '$codigo'";
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());   
  }else{
  $sqlinv="INSERT INTO TblInventarioListado (Fecha, Cod_ref, Codigo, SaldoInicial, Entrada, CostoUnd, Acep, Tipo, Responsable) VALUES ( '$fecha', '$cod_ref', '$codigo', '$final', '$entrada', '$costoUnd', '$acep', '$tipo', '$responsable')";
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());  
  }

?>