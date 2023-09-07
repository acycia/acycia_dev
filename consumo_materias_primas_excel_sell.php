<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="consumo materias primas.xls"');
?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//SELECT REFERENCIAS
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_referencia  WHERE estado_ref='1' order by id_ref desc";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);

//FECHAS DE IMPRESION
$fecha1 = $_GET['fechaI'];
$fecha2 = $_GET['fechaF'];
$ref = $_GET['ref'];
$mp = $_GET['mp'];
$proceso='4';

mysql_select_db($database_conexion1, $conexion1);
//Filtra Todos vacios
if($fecha1 != '' && $fecha2 != '' && $ref!='' &&  $mp!='' )
{
$query_consumo = "SELECT * FROM Tbl_orden_produccion WHERE  Tbl_orden_produccion.b_estado_op >= 4 AND DATE(f_sellada) BETWEEN '$fecha1' AND '$fecha2' ORDER BY Tbl_orden_produccion.id_op DESC";
}
//Filtra fechas
if($fecha1 != '' && $fecha2 != '' && $ref=='' &&  $mp=='' )
{
$query_consumo = "SELECT * FROM Tbl_orden_produccion WHERE  Tbl_orden_produccion.b_estado_op >= 4 AND DATE(f_sellada) BETWEEN '$fecha1' AND '$fecha2' ORDER BY Tbl_orden_produccion.id_op DESC";
}
//Filtra fechas y referencia 
if($fecha1 != '' && $fecha2 != '' && $ref!='' &&  $mp=='' )
{
$query_consumo = "SELECT * FROM Tbl_orden_produccion WHERE  Tbl_orden_produccion.b_estado_op >= 4 AND Tbl_orden_produccion.id_ref_op='$ref' AND DATE(f_sellada) BETWEEN '$fecha1' AND '$fecha2' ORDER BY Tbl_orden_produccion.id_op DESC";
}
//Filtra fechas ref y materia prima
if($fecha1 != '' && $fecha2 != '' && $ref=='' &&  $mp!='' )
{
mysql_select_db($database_conexion1, $conexion1);
$query_consumo = "SELECT * FROM Tbl_orden_produccion,Tbl_referencia WHERE  Tbl_orden_produccion.b_estado_op >= 4 AND DATE(Tbl_orden_produccion.f_sellada) BETWEEN '$fecha1' AND '$fecha2' AND Tbl_orden_produccion.int_cod_ref_op=Tbl_referencia.cod_ref AND Tbl_referencia.tipoCinta_ref='$mp' ORDER BY Tbl_orden_produccion.int_cod_ref_op DESC";
}
$consumo = mysql_query($query_consumo, $conexion1) or die(mysql_error());
$row_consumo = mysql_fetch_assoc($consumo);
$totalRows_consumo = mysql_num_rows($consumo);

 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1"> 
<tr>
  <td colspan="8" nowrap="nowrap" align="center">CONSUMO MATERIAS PRIMAS SELLADO DESDE: <?php echo $_GET['fechaI'] ?> HASTA: <?php echo $_GET['fechaF'] ?></td>
  </tr>     
    <tr id="tr1">
      <td nowrap="nowrap" id="titulo4">ORDEN.P</td>
      <td nowrap="nowrap" id="titulo4">REF.</td>
      <td nowrap="nowrap" id="titulo4">CONSUMO/kg</td>
      <td nowrap="nowrap" id="titulo4">BOLSILLO / MTS</td>
      <td nowrap="nowrap" id="titulo4">CINTA</td>
      <td nowrap="nowrap" id="titulo4">TERMICA</td>
      <td nowrap="nowrap" id="titulo4">METROS</td>
      <td nowrap="nowrap" id="titulo4">PEGA / KG</td>
      <td nowrap="nowrap" id="titulo4">DESPERDICIO/kg</td>
      <td nowrap="nowrap" id="titulo4">CAJAS</td> 
      <td nowrap="nowrap" id="titulo4">FECHA</td>
      </tr>
    <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato1" nowrap="nowrap"><?php 
	  $id_ref=$row_consumo['id_ref_op'];
	  $sqlcod="SELECT  Tbl_referencia.bolsillo_guia_ref,Tbl_referencia.adhesivo_ref,Tbl_referencia.tipoLamina_ref,Tbl_referencia.tipoCinta_ref,Tbl_egp.unids_caja_egp FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref = '$id_ref' AND Tbl_referencia.cod_ref=Tbl_egp.n_egp"; 
	  $resultcod=mysql_query($sqlcod); 
	  $numcod=mysql_num_rows($resultcod); 
	  if($numcod >= '1') 
	  {  
	  $bolsillo=mysql_result($resultcod,0,'bolsillo_guia_ref');
	  $adhesivo=mysql_result($resultcod,0,'adhesivo_ref');
	  $idLamina=mysql_result($resultcod,0,'tipoLamina_ref');
	  $idCinta=mysql_result($resultcod,0,'tipoCinta_ref');
	  $undcaja=mysql_result($resultcod,0,'unids_caja_egp');
	  }
	  echo $row_consumo['id_op'];
	  ?></td>
      <td id="dato1" nowrap="nowrap"><?php echo $row_consumo['int_cod_ref_op'];?></td>
      <td id="dato1" nowrap="nowrap"><?php
	  $id_op=$row_consumo['id_op'];
  	  $sqlRollo="SELECT SUM(int_kilos_prod_rp) AS kilos,SUM(int_metro_lineal_rp) AS metros, SUM(bolsa_rp) AS bolsas, fecha_ini_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='$proceso'"; 
	  $resultRollo=mysql_query($sqlRollo); 
	  $numRollo=mysql_num_rows($resultRollo); 
	  if($numRollo >= '1') 
	  {$kilos=mysql_result($resultRollo,0,'kilos');
	  $metros=mysql_result($resultRollo,0,'metros');
	  $fechaIni=mysql_result($resultRollo,0,'fecha_ini_rp');
	  $bolsa_rp=mysql_result($resultRollo,0,'bolsas');
	  echo $kilos;
	  }
	  ?></td>
      <td id="dato1" nowrap="nowrap"><?php 
		 if($bolsillo > '0'){
			 $bolsmetros=$metros;
			echo redondear_entero_puntos($bolsmetros);}else{echo "N.A";}
	  ?></td>
      <td nowrap="nowrap" id="dato1"><?php 
 	 $sqlcinta="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$idCinta'"; 
	  $resultcinta=mysql_query($sqlcinta); 
	  $numcinta=mysql_num_rows($resultcinta); 
	  if($numcinta >= '1') 
	  { $cinta=mysql_result($resultcinta,0,'descripcion_insumo'); 
	  echo $cinta;}else{echo "N.A";} 
	  ?> </td>
      <td nowrap="nowrap" id="dato1"><?php 
	  $idTermica=$row_consumo['id_termica_op'];
 	  $sqltermica="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$idTermica'"; 
	  $resulttermica=mysql_query($sqltermica); 
	  $numtermica=mysql_num_rows($resulttermica); 
	  if($numtermica >= '1') 
	  { $termica=mysql_result($resulttermica,0,'descripcion_insumo'); 
	  echo $termica;}else{echo "N.A";} 
	  ?></td>
      <td nowrap="nowrap" id="dato3"><?php
 		  echo redondear_entero_puntos($metros); 
	 ?></td>
      <td nowrap="nowrap" id="dato3"><?php   		  
	      $tipo = $adhesivo; 
    	  if($tipo=='HOT MELT')//EVALUO QUE SEA HOT PORQ ES KILO
          {
		  echo $pega=adhesivos($tipo,$metros);//en kilos de pega 
 		  } 
		  else 
		  {
		  echo $pega='N.A';
		  }?></td>
      <td nowrap="nowrap" id="dato3"><?php 
	    $id_op=$row_consumo['id_op']; 
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='$proceso'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0";}
	  ?></td>
      <td id="dato3" nowrap="nowrap"><?php echo redondear_entero($bolsa_rp/$undcaja); ?></td> 
      <td id="dato3" nowrap="nowrap"> <?php echo quitarHora($fechaIni); ?></td>
      </tr>
     
    <?php } while ($row_consumo = mysql_fetch_assoc($consumo)); ?>   
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>