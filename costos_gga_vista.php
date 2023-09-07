<?php require_once('Connections/conexion1.php'); ?>
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_fechaini = "-1";
if (isset($_GET['FechaInicio'])) {
  $colname_fechaini = (get_magic_quotes_gpc()) ? $_GET['FechaInicio'] : addslashes($_GET['FechaInicio']);
}
$colname_fechafin = "-1";
if (isset($_GET['FechaFin'])) {
  $colname_fechafin = (get_magic_quotes_gpc()) ? $_GET['FechaFin'] : addslashes($_GET['FechaFin']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costos_gga_lista = sprintf("SELECT * FROM TblDetalleGGAProd WHERE FechaInicio='%s' AND FechaFin ='%s'",$colname_fechaini,$colname_fechafin);
$costos_gga_lista = mysql_query($query_costos_gga_lista, $conexion1) or die(mysql_error());
$row_costos_gga_lista = mysql_fetch_assoc($costos_gga_lista);
$totalRows_costos_gga_lista = mysql_num_rows($costos_gga_lista);

$colname_fechaini = "-1";
if (isset($_GET['FechaInicio'])) {
  $colname_fechaini = (get_magic_quotes_gpc()) ? $_GET['FechaInicio'] : addslashes($_GET['FechaInicio']);
}
$colname_fechafin = "-1";
if (isset($_GET['FechaFin'])) {
  $colname_fechafin = (get_magic_quotes_gpc()) ? $_GET['FechaFin'] : addslashes($_GET['FechaFin']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costos_gga = sprintf("SELECT * FROM TblDetalleGGAProd WHERE FechaInicio='%s' AND FechaFin='%s'",$colname_fechaini,$colname_fechafin);
$costos_gga = mysql_query($query_costos_gga, $conexion1) or die(mysql_error());
$row_costos_gga = mysql_fetch_assoc($costos_gga);
$totalRows_costos_gga = mysql_num_rows($costos_gga);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna">
<tr>    
     <td colspan="5" id="principal">COSTOS GGA Y CIF</td>
  </tr>
  <tr>
    <td rowspan="4" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="5" id="dato3"><a href="costos_gga_edit.php?FechaInicio=<?php echo $row_costos_gga['FechaInicio']; ?>&FechaFin=<?php echo $row_costos_gga['FechaFin']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><a href="costos_listado_ggaycif.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COSTOS PROMEDIO"title="LISTADO COSTOS PROMEDIO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onClick="window.close() "/></a></td>
    </tr>
  <tr>
    <td id="subppal2">Fecha inicial: </td>
    <td colspan="4" id="subppal2">fecha final:</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_costos_gga['FechaInicio']; ?></td>
    <td colspan="4" nowrap id="fuente2"><?php echo $row_costos_gga['FechaFin']; ?></td>
    </tr>
  <tr>
    <td id="fuente2">Responsable</td>
    <td colspan="4" id="fuente2"><?php echo $row_costos_gga['ResponsableGGA']; ?></td>
    </tr>    
  <tr>
    <td colspan="5" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
  </tr>
 
     
</table>    

<table id="tablainterna">
 <tr>
  <td colspan="5" id="subtitulo">COSTO GGA Y CIF POR CUARTILES UNIDAD COMPLETA PROCESADA</td>
  </tr>
  <tr>
    <td id="subppal2">Total  GGA Y CIF Procesada Predeterminada</td>
    <td colspan="2" id="subppal2">Undades Producidas X (MES)</td>
    <td colspan="2" id="subppal2">Costo GGA Y CIF promedio Und Producida</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_costos_gga['TotalGGA']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_costos_gga['UnidadesProducidas']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_costos_gga['CostoGGAxUn']; ?></td>
  </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="4" rowspan="6" id="fuente1" valign="top">
        <table id="justificar" >
          <tr>
            <td id="subppal3">GGA Y CIF Bolsa de M&aacute;ximo 250 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 250 y 500 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 501  y  1000 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 1001 y 4000 cm&sup2;</td>
          </tr>
          <tr>
          <?php  for ($x=0;$x<4;$x++) { ?>
            <td id="fuente1">$  <?php $cp=mysql_result($costos_gga,$x,ValorCaracGGA);echo $cp; ?></td>
           <?php  } ?>
          </tr>
          <tr>
          <?php  for ($x=4;$x<8;$x++) { ?>
            <td id="fuente1">$  <?php $vc=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vc; ?></td>
           <?php  } ?>
          </tr>
          <tr>
          <?php  for ($x=8;$x<12;$x++) { ?>
            <td id="fuente1">$  <?php $vd=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vd; ?></td>
           <?php  } ?>
          </tr> 
          <tr>
          <?php  for ($x=12;$x<16;$x++) { ?>
            <td id="fuente1">$  <?php $vf=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vf; ?></td>
           <?php  } ?>
          </tr>                                
        </table>
      </td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun produccion promedio</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Ajustes</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun promedio real</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun produccion y ajuste</td>
      </tr> 
      
</table>
<table id="tablainterna">
<tr>
  <td colspan="5" id="subtitulo">COSTO GGA  Y CIF POR CUARTILES UNIDAD PARCIALMENTE PROCESADA</td>
  </tr>
<tr>
    <td id="subppal2">Total  GGA Y CIF Parcialmente Procesada </td>
    <td colspan="2" id="subppal2">Undades Producidas X (MES)</td>
    <td colspan="2" id="subppal2">Costo GGA promedio Und Producida Parcial</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_costos_gga['TotalGGA_parcial']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_costos_gga['UnidadesProducidas_parcial']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_costos_gga['CostoGGAxUn_parcial']; ?></td>
  </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="4" rowspan="6" id="fuente1" valign="top">
        <table id="justificar" >
          <tr>
            <td id="subppal3">GGA Y CIF Bolsa de M&aacute;ximo 250 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 250 y 500 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 501  y  1000 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 1001 y 4000 cm&sup2;</td>
          </tr>
          <tr>
          <?php  for ($x=16;$x<20;$x++) { ?>
            <td id="fuente1">$  <?php $cp=mysql_result($costos_gga,$x,ValorCaracGGA);echo $cp; ?></td>
           <?php  } ?>
          </tr>
          <tr>
          <?php  for ($x=20;$x<24;$x++) { ?>
            <td id="fuente1">$  <?php $vc=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vc; ?></td>
           <?php  } ?>
          </tr>
          <tr>
          <?php  for ($x=24;$x<28;$x++) { ?>
            <td id="fuente1">$  <?php $vd=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vd; ?></td>
           <?php  } ?>
          </tr> 
          <tr>
          <?php  for ($x=28;$x<32;$x++) { ?>
            <td id="fuente1">$  <?php $vf=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vf; ?></td>
           <?php  } ?>
          </tr> 
          
                                         
        </table>
        
        
        </td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun produccion promedio</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Ajustes</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun promedio real</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun produccion y ajuste</td>
      </tr>
     
</table>

<table id="tablainterna">
 <tr>
  <td colspan="5" id="subtitulo">COSTO GGA Y CIF POR CUARTILES UNIDAD COMPLETA PROCESADA</td>
  </tr>
  <tr>
    <td id="subppal2">Total  GGA Y CIF Procesada Predeterminada</td>
    <td colspan="2" id="subppal2">Undades Producidas X (MES)</td>
    <td colspan="2" id="subppal2">Costo GGA Y CIF promedio Und Producida</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_costos_gga['TotalGGA_impresion']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_costos_gga['UnidadesProducidas_impresion']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_costos_gga['CostoGGAxUn_impresion']; ?></td>
  </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="4" rowspan="6" id="fuente1" valign="top">
        <table id="justificar" >
          <tr>
            <td id="subppal3">GGA Y CIF Bolsa de M&aacute;ximo 250 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 250 y 500 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 501  y  1000 cm&sup2;</td>
            <td id="subppal3">GGA Y CIF Bolsa entre 1001 y 4000 cm&sup2;</td>
          </tr>
          <tr>
          <?php  for ($x=32;$x<36;$x++) { ?>
            <td id="fuente1">$  <?php $cpI=mysql_result($costos_gga,$x,ValorCaracGGA);echo $cpI; ?></td>
           <?php  } ?>
          </tr>
          <tr>
          <?php  for ($x=36;$x<40;$x++) { ?>
            <td id="fuente1">$  <?php $vcI=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vcI; ?></td>
           <?php  } ?>
          </tr>
          <tr>
          <?php  for ($x=40;$x<44;$x++) { ?>
            <td id="fuente1">$  <?php $vdI=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vdI; ?></td>
           <?php  } ?>
          </tr> 
          <tr>
          <?php  for ($x=44;$x<48;$x++) { ?>
            <td id="fuente1">$  <?php $vfI=mysql_result($costos_gga,$x,ValorCaracGGA);echo $vfI; ?></td>
           <?php  } ?>
          </tr>                                
        </table>
      </td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun produccion promedio</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Ajustes</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun promedio real</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo GGA Y CIF segun produccion y ajuste</td>
      </tr> 
     <tr>
    <td colspan="5" id="fuente1">&nbsp;</td>
  </tr>     
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos_gga_lista);

mysql_free_result($costos_gga);
?>
