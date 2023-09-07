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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Tbl_inventario (id_inv,id_insumo_inv,descripcion_inv,entrada_inv,costo_inv,costo_total_inv,fecha_inv,hora_inv,responsable_inv) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_inv'], "int"),
                       GetSQLValueString($_POST['id_insumo_inv'], "int"),
                       GetSQLValueString($_POST['descripcion_inv'], "text"),
                       GetSQLValueString($_POST['entrada_inv'], "double"),
					   GetSQLValueString($_POST['costo_inv'], "double"),
                       GetSQLValueString($_POST['costo_total_inv'], "double"),
                       GetSQLValueString($_POST['fecha_inv'], "date"),
                       GetSQLValueString($_POST['hora_inv'], "text"),
                       GetSQLValueString($_POST['responsable_inv'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "costos_inventario_entradas.php?id_inv=" . $_POST['id_inv'] . "&id_insumo_inv=" . $_POST['id_insumo_inv'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//MUESTRA INSERT RECIENTE
$colname_insumo_edit = "1";
if (isset($_GET['id_inv'])) {
  $colname_insumo_edit = (get_magic_quotes_gpc()) ? $_GET['id_inv'] : addslashes($_GET['id_inv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_edit = sprintf("SELECT * FROM Tbl_inventario WHERE id_inv=%s ", $colname_insumo_edit);
$insumo_edit = mysql_query($query_insumo_edit, $conexion1) or die(mysql_error());
$row_insumo_edit = mysql_fetch_assoc($insumo_edit);
$totalRows_insumo_edit = mysql_num_rows($insumo_edit);
//MUESTRA INSERT RECIENTE
$colname_insumo_totales = "1";
if (isset($_GET['id_insumo_inv'])) {
  $colname_insumo_totales = (get_magic_quotes_gpc()) ? $_GET['id_insumo_inv'] : addslashes($_GET['id_insumo_inv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_totales = sprintf("SELECT * FROM Tbl_inventario WHERE id_insumo_inv=%s ", $colname_insumo_totales);
$insumo_totales = mysql_query($query_insumo_totales, $conexion1) or die(mysql_error());
$row_insumo_totales = mysql_fetch_assoc($insumo_totales);
$totalRows_insumo_totales = mysql_num_rows($insumo_totales);
//ULTIMO
mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_inventario ORDER BY id_inv DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
?>
<?php

/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  https://gualinx.wordpress.com
*/   

/*function Conectarse() //Función para conectarse a la BD
{
       if (!($link=mysql_connect("localhost","acycia_root","ac2006")))  { //Cambia estos datos
           echo "Error conectando a la base de datos.";
           exit();
       }
        if (!mysql_select_db("acycia_intranet",$link)) {
            echo "Error seleccionando la base de datos.";
           exit();
       }
       return $link;
}

$row = 1;
$handle = fopen("a_importar.csv", "r"); //Coloca el nombre de tu archivo .csv que contiene los datos
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
    $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
    $row++;
    $cadena = "insert into alumnos(id,nocontrol,nombre,grado,grupo,sexo) values("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios
    for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
        if ($c==($num-1))
              $cadena = $cadena."'".$data[$c] . "'";
        else
              $cadena = $cadena."'".$data[$c] . "',";
    }

    $cadena = $cadena.");"; //Termina de armar la cadena para poder ser ejecutada
    echo $cadena."<br>";  //Muestra la cadena para ejecutarse

     $enlace=Conectarse();
     $result=mysql_query($cadena, $enlace); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada
     mysql_close($enlace);
}

fclose($handle);*/

?>
<h2>Se insertaron <?php echo $row ?> Registros en la tabla miTabla</h2><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/ajax_inventario.js"></script> 
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table id="tabla4" align="center">
<tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1" align="center"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario_admon['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
            <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
			<li><a href="menu.php">MENU PRINCIPAL</a></li>

</ul></td>
</tr>
<tr>
	<td align="center" colspan="2" id="linea1">
<form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >	
<table id="tabla2">
    <tr id="tr1">
        <td colspan="3" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td nowrap="nowrap" id="titulo2">CONTEO-INVENTARIO</td>
        <td colspan="2" nowrap="nowrap" id="codigo"> VERSION: 2</td>
        </tr>
	<tr>
	  <td colspan="3" rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
  <td id="numero2">&nbsp;</td>
      <td id="fuente2"><a href="costos_inventario_entrada_listado.php"><img src="images/opciones.gif" alt="ENTRADAS MATERIAS PRIMA" title="ENTRADAS MATERIAS PRIMA" border="0" style="cursor:hand;" /></a><a href="costos_generales.php"><img src="images/c.gif" style="cursor:hand;" alt="COSTOS GENERALES" title="COSTOS GENERALES" border="0" /></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" title="INSUMOS" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
      </tr>
      <tr>
        <td id="titulo2"><input name="id_inv" type="hidden" value="<?php $num=$row_ultimo['id_inv']+1; echo $num; ?>" size="2" readonly="readonly" /></td>
        <td id="numero1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Fecha  Ingreso</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="fecha_inv" type="date" required="required" id="fecha_inv" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" style="width:150px"/></td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Ingresado por</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="responsable_inv" type="text" id="responsable_inv" value="<?php echo $row_usuario['nombre_usuario']; ?>" style="width:150px" readonly="true" /></td>
        <td id="dato4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo4">ARCHIVO</td>
        </tr>
      <tr>
  <td colspan="5" id="dato2"><label for="adjunto"></label>
    <img src="images/clip.gif" alt="ADJUNTAR OTRO" title="ADJUNTAR OTRO" border="0" style="cursor:hand;" />
    <input type="file" name="excel" />

<input type=’submit’ name=’enviar’  value="Importar"  />

<input type="hidden" value="upload" name="action" /></td>
  </tr>
      <tr>
        <td colspan="5" id="dato1">&nbsp;</td>
        </tr>
<?php //if ($row_insumo_edit['id_insumo']!=''){?>
<div id="inventario">
<?php do { ?>
 <?php } while ($row_insumo_edit = mysql_fetch_assoc($insumo_edit)); ?>
 </div>
 <?php do { 
        $tCantidad=$tCantidad+$row_insumo_totales['entrada_inv'];
		$tCosto=$tCosto+$row_insumo_totales['costo_total_inv'];
        } while ($row_insumo_totales = mysql_fetch_assoc($insumo_totales)); ?>

<tr>
  <td colspan="5" id="dato">&nbsp;</td>
</tr>
<?php //}?>
</table>
    <input type="hidden" name="MM_insert" value="form1">
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
mysql_free_result($ver_ref);
mysql_free_result($ver_ref2);

?>