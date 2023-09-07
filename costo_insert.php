<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
	
	foreach ($_POST['id_gv'] as $key=>$v) {
    $a[]= $v;
     }
	foreach ($_POST['id_m'] as $key=>$v) {
    $b[]= $v;
    }
	foreach ($_POST['id_v'] as $key=>$v) {
    $c[]= $v;
    }	
    $fecha1=$_POST['fecha_ini_gv'];
	$fecha2=$_POST['fecha_fin_gv'];
/*    foreach($_POST['id_gv'] as $key=>$v){
    $a[]= $v;}
    foreach($_POST['id_m'] as $key=>$v){
    $b[]= $v;}
    foreach($_POST['id_v'] as $key=>$v){
    $c[]= $v;}
	$fecha1=$_POST['fecha_ini_gv'];
	$fecha2=$_POST['fecha_fin_gv'];*/
	
	for($x=0; $x<count($a); $x++){
		// if(!empty($a[$x])&&!empty($b[$x]&&!empty($c[$x])){	
		/*echo "<script type=\"text/javascript\">alert(\" $a[$x] \");return false;history.go(-1)</script>";*/
  $insertSQL2 = sprintf("INSERT INTO Tbl_generadores_valor (id_generadores_gv, maquina_gv, valor_gv, fecha_ini_gv, fecha_fin_gv) VALUES (%s, %s, %s, %s, %s)",  
                       GetSQLValueString($a[$x], "text"),
					   GetSQLValueString($b[$x], "text"),                  
                       GetSQLValueString($c[$x], "double"),
                       GetSQLValueString($fecha1, "date"),
					   GetSQLValueString($fecha2, "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
   header(sprintf("Location: costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=$fecha1&fecha_fin_gv=$fecha2"));
		 // }
	}
}
?>