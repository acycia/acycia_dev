<?php require_once('Connections/conexion1.php'); ?>
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
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE  Tbl_produccion_mezclas SET id_proceso=%s,fecha_registro_pm=%s,str_registro_pm=%s,int_ref1_tol1_pm=%s,
int_ref1_tol1_porc1_pm=%s,int_ref2_tol1_pm=%s,int_ref2_tol1_porc2_pm=%s,int_ref3_tol1_pm=%s,int_ref3_tol1_porc3_pm=%s,int_ref1_tol2_pm=%s,int_ref1_tol2_porc1_pm=%s,int_ref2_tol2_pm=%s,
int_ref2_tol2_porc2_pm=%s,int_ref3_tol2_pm=%s,int_ref3_tol2_porc3_pm=%s,int_ref1_tol3_pm=%s,int_ref1_tol3_porc1_pm=%s,
int_ref2_tol3_pm=%s,int_ref2_tol3_porc2_pm=%s,int_ref3_tol3_pm=%s,int_ref3_tol3_porc3_pm=%s,int_ref1_tol4_pm=%s,int_ref1_tol4_porc1_pm=%s,int_ref2_tol4_pm=%s,int_ref2_tol4_porc2_pm=%s,
int_ref3_tol4_pm=%s,int_ref3_tol4_porc3_pm=%s,int_ref1_rpm_pm=%s,int_ref1_tol5_porc1_pm=%s,int_ref2_rpm_pm=%s,int_ref2_tol5_porc2_pm=%s,int_ref3_rpm_pm=%s,int_ref3_tol5_porc3_pm=%s,
extrusora_mp=%s, observ_pm=%s,b_borrado_pm=%s WHERE id_pm=%s", 
             GetSQLValueString($_POST['id_proceso'], "int"),
             GetSQLValueString($_POST['fecha_registro_pm'], "date"),
					   GetSQLValueString($_POST['str_registro_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol1_pm'], "int"),
					   GetSQLValueString($_POST['int_ref1_tol1_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol1_pm'], "int"),
					   GetSQLValueString($_POST['int_ref2_tol1_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol1_pm'], "int"),
					   GetSQLValueString($_POST['int_ref3_tol1_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol2_pm'], "int"),
					   GetSQLValueString($_POST['int_ref1_tol2_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol2_pm'], "int"),
					   GetSQLValueString($_POST['int_ref2_tol2_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol2_pm'], "int"),
					   GetSQLValueString($_POST['int_ref3_tol2_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol3_pm'], "int"),
					   GetSQLValueString($_POST['int_ref1_tol3_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol3_pm'], "int"),
					   GetSQLValueString($_POST['int_ref2_tol3_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol3_pm'], "int"),
					   GetSQLValueString($_POST['int_ref3_tol3_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol4_pm'], "int"),
					   GetSQLValueString($_POST['int_ref1_tol4_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol4_pm'], "int"),
					   GetSQLValueString($_POST['int_ref2_tol4_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol4_pm'], "int"),
					   GetSQLValueString($_POST['int_ref3_tol4_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_rpm_pm'], "int"),
					   GetSQLValueString($_POST['int_ref1_tol5_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_rpm_pm'], "int"),
					   GetSQLValueString($_POST['int_ref2_tol5_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_rpm_pm'], "int"),
					   GetSQLValueString($_POST['int_ref3_tol5_porc3_pm'], "double"),
             GetSQLValueString($_POST['extrusora_mp'], "text"),
					   GetSQLValueString($_POST['observ_pm'], "text"),
					   GetSQLValueString($_POST['b_borrado_pm'], "int"),
					   GetSQLValueString($_POST['id_pm'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "produccion_mezclas_edit.php?id_pm=" . $_POST['id_pm'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_editar = "-1";
if (isset($_GET['id_pm'])) 
{
  $colname_editar= (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar = sprintf("SELECT * FROM Tbl_produccion_mezclas WHERE id_pm=%s ",$colname_editar);
$editar = mysql_query($query_editar, $conexion1) or die(mysql_error());
$row_editar = mysql_fetch_assoc($editar);
$totalRows_editar = mysql_num_rows($editar);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="produccion_mezclas_add.php">EXTRUSION</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="7" id="titulo2">PROCESO EXTRUSION MEZCLAS</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="6" id="dato3"><a href="vista.php?id_ref=<?php echo $row['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="2" nowrap="nowrap" id="fuente1">Fecha Ingreso 
          <input name="fecha_registro_pm" type="date" min="2000-01-02" value="<?php echo $row_editar['fecha_registro_pm'] ?>" size="10" autofocus />
          </td>
        <td colspan="4" id="fuente1">
Ingresado por
  <input name="str_registro_pm" type="text" value="<?php echo $row_editar['str_registro_pm']; ?>" size="27" readonly="readonly"/>
  <input type="hidden" name="id_pm" id="id_pm" value="<?php echo $row_editar['id_pm']; ?>"/>

  <input type="hidden" name="id_proceso" id="id_proceso" value="<?php echo $row_editar['id_proceso']; ?>"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="235" colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td colspan="2" id="fuente2"><output  onforminput="value=weight.value"></output></td>
        <td colspan="2" id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2"><datalist id="dias"></datalist></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="titulo4">EXTRUSION</td>
        </tr>
        <tr>
         <td  colspan="10" id="titulo4"> 
          Estrusora : <input name="extrusora_mp"  type="text" id="extrusora_mp" placeholder="Extrusora" size="20"value="<?php echo $row_editar['extrusora_mp']; ?>"/>  
        </td>
       </tr>
      <tr id="tr1">
        <td rowspan="3" id="fuente1">EXT-1          
        </td>
        <td colspan="2" id="fuente1">TORNILLO A</td>
        <td colspan="2" id="fuente1">TORNILLO B</td>
        <td colspan="2" id="fuente1">TORNILLO C</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Tolva A</td>
        <td id="fuente1"><select name="int_ref1_tol1_pm" id="int_ref1_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol1_porc1_pm"  type="text" required="required" id="int_ref1_tol1_porc1_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref1_tol1_porc1_pm']; ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol1_pm" id="int_ref2_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol1_porc1_pm"  type="text" required="required" id="int_ref2_tol1_porc1_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref2_tol1_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol1_pm" id="int_ref3_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol1_porc3_pm"  type="text" required="required" id="int_ref3_tol1_porc3_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref3_tol1_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">Tolva B</td>
        <td id="fuente1"><select name="int_ref1_tol2_pm" id="int_ref1_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol2_porc1_pm"  type="text" required="required" id="int_ref1_tol2_porc1_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref1_tol2_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol2_pm" id="int_ref2_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol2_porc2_pm"  type="text" required="required" id="int_ref2_tol2_porc2_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref2_tol2_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol2_pm" id="int_ref3_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol2_porc3_pm"  type="text" required="required" id="int_ref3_tol2_porc3_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref3_tol2_porc3_pm'] ?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Tolva C</td>
        <td id="fuente1"><select name="int_ref1_tol3_pm" id="int_ref1_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol3_porc1_pm"  type="text" required="required" id="int_ref1_tol3_porc1_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref1_tol3_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol3_pm" id="int_ref2_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol3_porc2_pm"  type="text" required="required" id="int_ref2_tol3_porc2_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref2_tol3_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol3_pm" id="int_ref3_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol3_porc3_pm"  type="text" required="required" id="int_ref3_tol3_porc3_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref3_tol3_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">Tolva D</td>
        <td id="fuente1"><select name="int_ref1_tol4_pm" id="int_ref1_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol4_porc1_pm"  type="text" required="required" id="int_ref1_tol4_porc1_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref1_tol4_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol4_pm" id="int_ref2_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol4_porc2_pm"  type="text" required="required" id="int_ref2_tol4_porc2_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref2_tol4_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol4_pm" id="int_ref3_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol4_porc3_pm"  type="text" required="required" id="int_ref3_tol4_porc3_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref3_tol4_porc3_pm'] ?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">RPM - %</td>
        <td id="fuente1"><input name="int_ref1_rpm_pm"  type="text" placeholder="Rpm Torn-A" required="required" size="10"value="<?php echo $row_editar['int_ref1_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref1_tol5_porc1_pm"  type="text" required="required" id="int_ref1_tol5_porc1_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref1_tol5_porc1_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref2_rpm_pm"  type="text" placeholder="Rpm Torn-B" required="required" size="10"value="<?php echo $row_editar['int_ref2_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref2_tol5_porc2_pm"  type="text" required="required" id="int_ref2_tol5_porc2_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref2_tol5_porc2_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref3_rpm_pm"  type="text" placeholder="Rpm Torn-C" required="required" size="10"value="<?php echo $row_editar['int_ref3_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref3_tol5_porc3_pm"  type="text" required="required" id="int_ref3_tol5_porc3_pm" placeholder="%" size="3"value="<?php echo $row_editar['int_ref3_tol5_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2">OBSERVACIONES</td>
        </tr>
      <tr>
        <td colspan="10" id="fuente2"><textarea name="observ_pm" id="observ_pm" cols="45" rows="5"placeholder="OBSERVACIONES"><?php echo $row_editar['observ_pm'] ?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2"> <input type="hidden" name="b_borrado_pm" id="b_borrado_pm" value="<?php echo $row_editar['b_borrado_pm'] ?>"/>
        <input type="hidden" name="id_pm" value="<?php echo $row_editar['id_pm']; ?>">
        <input type="submit" name="SIGUIENTE" id="SIGUIENTE" value="SIGUIENTE" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    </form>
  </td></tr></table>
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
<?php
mysql_free_result($usuario);
mysql_free_result($editar);
?>
