<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
if (!empty ($_POST['id_rpp'])&&!empty ($_POST['valor_prod_rp'])){
    foreach($_POST['id_rpp'] as $key=>$k)
    $f[]= $k;
    foreach($_POST['valor_prod_rp'] as $key=>$k)
    $g[]= $k;
    $c= $_POST['id_op_rp'];	
	
	for($s=0; $s<count($f); $s++) {
		  if(!empty($f[$s])&&!empty($g[$s])){ //no salga error con campos vacios
 $insertSQLd = sprintf("INSERT INTO Tbl_reg_kilo_producido (id_rpp_rp,valor_prod_rp,op_rp,id_proceso_rkp,fecha_rkp) VALUES (%s, %s, %s, %s, %s)",                      
                       GetSQLValueString($f[$s], "int"),
                       GetSQLValueString($g[$s], "double"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['id_proceso_rkp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultd = mysql_query($insertSQLd, $conexion1) or die(mysql_error());
  
/*    $insertGoTo = "produccion_registro_extrusion_add.php?id_op=" . $_POST['id_op_rp'] . "&fecha_ini_rp=" . $_POST['fecha_ini_rp'] .  "&hora_ini_rp=" . $_POST['hora_ini_rp'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));  */
  
		  }
	}
}
echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
 
echo "<script type=\"text/javascript\">window.close();</script>";
}
?>
<?php 
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
//MEZCLAS
$colname_materiap = "-1";
if (isset($_GET['id_op'])) 
{
  $colname_materiap= (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_materiap = sprintf("SELECT Tbl_produccion_mezclas.int_ref1_tol1_pm,Tbl_produccion_mezclas.int_ref1_tol1_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol1_pm,Tbl_produccion_mezclas.int_ref2_tol1_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol1_pm,Tbl_produccion_mezclas.int_ref3_tol1_porc3_pm,Tbl_produccion_mezclas.int_ref1_tol2_pm,Tbl_produccion_mezclas.int_ref1_tol2_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol2_pm,Tbl_produccion_mezclas.int_ref2_tol2_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol2_pm,Tbl_produccion_mezclas.int_ref3_tol2_porc3_pm,
Tbl_produccion_mezclas.int_ref1_tol3_pm,Tbl_produccion_mezclas.int_ref1_tol3_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol3_pm,Tbl_produccion_mezclas.int_ref2_tol3_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol3_pm,Tbl_produccion_mezclas.int_ref3_tol3_porc3_pm,Tbl_produccion_mezclas.int_ref1_tol4_pm,Tbl_produccion_mezclas.int_ref1_tol4_porc1_pm,Tbl_produccion_mezclas.int_ref2_tol4_pm,Tbl_produccion_mezclas.int_ref2_tol4_porc2_pm,Tbl_produccion_mezclas.int_ref3_tol4_pm,Tbl_produccion_mezclas.int_ref3_tol4_porc3_pm,
Tbl_produccion_mezclas.id_ref_pm,Tbl_orden_produccion.id_ref_op 
FROM Tbl_orden_produccion,Tbl_produccion_mezclas WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_produccion_mezclas.id_ref_pm",$colname_materiap);
$materiap = mysql_query($query_materiap, $conexion1) or die(mysql_error());
$row_materiap = mysql_fetch_assoc($materiap);
$totalRows_materiap = mysql_num_rows($materiap);
//ORDEN DE PRODUCCION
$colname_op= "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op=%s AND b_borrado_op='0' ORDER BY id_op DESC",$colname_op);
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);
//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P
$colname_totalKilos= "-1";
if (isset($_GET['id_op'])) {
  $colname_totalKilos = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalKilos = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='%s' AND id_proceso_rp='1'",$colname_totalKilos);
$totalKilos = mysql_query($query_totalKilos, $conexion1) or die(mysql_error());
$row_totalKilos = mysql_fetch_assoc($totalKilos);
$totalRows_totalKilos = mysql_num_rows($totalKilos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2">
        <table id="tabla2">
          <tr>
            <td  nowrap="nowrap" colspan="13" id="subtitulo">AGREGAR KILOS PRODUCIDOS</td>
            </tr>
          <tr>
            <td colspan="8" id="dato1">Kilos Requeridos en la O.P: <strong><?php echo $row_orden_produccion['int_kilos_op'] ?> </strong>mas el <strong><?php echo $row_orden_produccion['int_desperdicio_op'] ?></strong>% de Tolerancia de la O.P</td>
            <td colspan="5"  nowrap="nowrap" id="dato2">            
            <strong>Fecha y Hora:</strong>
            <input name="fecha_ini_rp" type="datetime" min="2000-01-02" value="<?php echo $_GET['fecha']; ?>" size="19" required="required" readonly="readonly"/>            </tr>
          <tr>
            <td colspan="13" id="dato5">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="13" id="dato2"><strong>MATERIA PRIMA UTILIZADA</strong></td>
            </tr>
  <td rowspan="3" id="fuente1"><strong>EXT-1          
        </strong></td>
        <td colspan="4" id="fuente1"><strong>TORNILLO A</strong></td>
        <td colspan="4" id="fuente1"><strong>TORNILLO B</strong></td>
        <td colspan="4" id="fuente1"><strong>TORNILLO C</strong></td>
        </tr>
      <tr>
        <td colspan="3" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="3" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><strong>Referencia</strong></td>
        <td id="fuente1"><strong>% Mezcla</strong></td>
        <td id="fuente1"><strong>Kilos aprox.</strong></td>
        <td id="fuente1"><strong>KILOS</strong></td>
        <td id="fuente1"><strong>Referencia</strong></td>
        <td id="fuente1"><strong>% Mezcla</strong></td>
        <td id="fuente1"><strong>Kilos aprox.</strong></td>
        <td id="fuente1"><strong>KILOS</strong></td>
        <td id="fuente1"><strong>Referencia</strong></td>
        <td id="fuente1"><strong>% Mezcla</strong></td>
        <td id="fuente1"><strong>Kilos aprox.</strong></td> 
        <td id="fuente1"><strong>KILOS</strong></td>        
  <tr id="tr1">
        <td id="fuente1"><strong>Tolva A</strong></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref1_tol1_pm'] ?>" size="3"/>
        <?php 
	    $idinsumo=$row_materiap['int_ref1_tol1_pm']; 
		$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref1_tol1_porc1_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol1_porc1_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox;?></td>
        <td id="fuente1"><input type="number" name="valor_prod_rp[]" id="valor_prod_rp[]" min="0"step="0.01" style="width:50px" placeholder="Kilos" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref2_tol1_pm'] ?>" size="3"/>          
		<?php $idinsumo=$row_materiap['int_ref2_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref2_tol1_porc2_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol1_porc2_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox;?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref3_tol1_pm'] ?>" size="3"/>          
		<?php $idinsumo= $row_materiap['int_ref3_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}		
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref3_tol1_porc3_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol1_porc3_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><strong>Tolva B</strong></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref1_tol2_pm'] ?>" size="3"/>
          <?php $idinsumo = $row_materiap['int_ref1_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref1_tol2_porc1_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol2_porc1_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref2_tol2_pm'] ?>" size="3"/>          
		<?php $idinsumo =  $row_materiap['int_ref2_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref2_tol2_porc2_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol2_porc2_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref3_tol2_pm'] ?>" size="3"/>  
        <?php $idinsumo =  $row_materiap['int_ref3_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref3_tol2_porc3_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol2_porc3_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><strong>Tolva C</strong></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref1_tol3_pm'] ?>" size="3"/>
          <?php $idinsumo =  $row_materiap['int_ref1_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref1_tol3_porc1_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol3_porc1_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref2_tol3_pm'] ?>" size="3"/> 
        <?php $idinsumo =  $row_materiap['int_ref2_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref2_tol3_porc2_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol3_porc2_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref3_tol3_pm'] ?>" size="3"/>
        <?php $idinsumo =  $row_materiap['int_ref3_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref3_tol3_porc3_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol3_porc3_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><strong>Tolva D</strong></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref1_tol4_pm'] ?>" size="3"/>
          <?php $idinsumo =  $row_materiap['int_ref1_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref1_tol4_porc1_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref1_tol4_porc1_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref2_tol4_pm'] ?>" size="3"/>          
		<?php $idinsumo =  $row_materiap['int_ref2_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref2_tol4_porc2_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref2_tol4_porc2_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
        <td id="fuente1"><input name="id_rpp[]"  type="hidden" id="id_rpp[]" value="<?php echo $row_materiap['int_ref3_tol4_pm'] ?>" size="3"/>  
        <?php $idinsumo =  $row_materiap['int_ref3_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
        <td id="fuente1"><?php echo $row_materiap['int_ref3_tol4_porc3_pm'] ?></td>
        <td id="fuente1"><?php $kilosaprox = sacar_porcentaje($row_orden_produccion['int_kilos_op'],$row_materiap['int_ref3_tol4_porc3_pm'],$row_orden_produccion['int_desperdicio_op']); echo $kilosaprox; ?></td>
        <td id="fuente1"><input name="valor_prod_rp[]" type="number" id="valor_prod_rp[]" placeholder="Kilos" style="width:50px" min="0"step="0.01" onblur="return validacion_kilos_extrusion();" value="<?php echo $kilosaprox; ?>"/></td>
      </tr>
          <tr>
            <td colspan="13" id="dato1">&nbsp;
            
            </td>
          </tr>          
          <tr>
            <td colspan="13" nowrap="nowrap"  id="dato1"><p><strong>Nota:</strong> Los kilo aprox. son los kilos aproximados que se deben utilizar segun  </p>
              <p>la cantidad requerida en la orden de produccion * el % de la mezcla .</p></td>
            </tr>

          <tr>
            <td colspan="13" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="13" id="dato2"><input type="submit" value="ADD A EXTRUSION" /></td>
          </tr>
          <tr>
            <td ></td>
            </tr>
          <tr>
            <td colspan="13" id="dato2">&nbsp;</td>
            </tr>
        </table>
        <input  name="porcentaje" id="porcentaje" type="hidden" value="<?php echo  $row_orden_produccion['int_desperdicio_op']; ?>"/>
        <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $_GET['id_op']; ?>" />
        <input name="id_proceso_rkp" type="hidden" id="id_proceso_rkp" value="1" />
        <input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_orden_produccion['int_kilos_op']; ?>" />
        <?php  for ($x=0;$x<=$totalRows_totalKilos-1;$x++) { ?>
        <input name="kilos_extruido[]" type="hidden" id="kilos_extruido[]" value="<?php $tK=mysql_result($totalKilos,$x,int_total_kilos_rp); echo $tK; ?>" />
        <?php } ?>
       <input type="hidden" name="MM_insert" value="form2">
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>