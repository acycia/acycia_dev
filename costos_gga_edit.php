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
if (!empty ($_POST['id'])&&!empty ($_POST['valor'])){
	foreach($_POST['id'] as $key=>$k)
    $id[]= $k;
    foreach($_POST['valor'] as $key=>$k)
    $b[]= $k;
    foreach($_POST['area'] as $key=>$k)
    $c[]= $k;					
	for($x=0; $x<count($b); $x++) {
  $updateSQL = sprintf("UPDATE TblDetalleGGAProd SET FechaInicio=%s, FechaFin=%s, ResponsableGGA=%s, TotalGGA=%s, UnidadesProducidas=%s, CostoGGAxUn=%s, TotalGGA_parcial=%s, porc_parcial=%s, UnidadesProducidas_parcial=%s, CostoGGAxUn_parcial=%s, TotalGGA_impresion=%s,porc_impresion=%s,UnidadesProducidas_impresion=%s,CostoGGAxUn_impresion=%s, ValorCaracGGA=%s, AreaCaracGGA=%s WHERE FechaInicio=%s AND FechaFin=%s AND IDCaracGGA=%s ",
                       GetSQLValueString($_POST['FechaInicio'], "date"),
                       GetSQLValueString($_POST['FechaFin'], "date"),
					   GetSQLValueString($_POST['ResponsableGGA'], "text"),
                       GetSQLValueString($_POST['TotalGGA'], "double"),
                       GetSQLValueString($_POST['UnidadesProducidas'], "int"),
                       GetSQLValueString($_POST['CostoGGAxUn'], "double"),
                       GetSQLValueString($_POST['gga_parcial'], "double"),
					   GetSQLValueString($_POST['porc_parcial'], "int"),
                       GetSQLValueString($_POST['UnidadesProducidas_parcial'], "int"),
                       GetSQLValueString($_POST['CostoGGAxUn_parcial'], "double"),
                       GetSQLValueString($_POST['gga_impresion'], "double"),
					   GetSQLValueString($_POST['porc_impresion'], "int"),
                       GetSQLValueString($_POST['UnidadesProducidas_impresion'], "int"),
                       GetSQLValueString($_POST['CostoGGAxUn_impresion'], "double"),						   						   
                       GetSQLValueString($b[$x], "double"),
                       GetSQLValueString($_POST['FechaInicio'], "date"),
                       GetSQLValueString($_POST['FechaFin'], "date"),					   				   
					   GetSQLValueString($id[$x], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "costos_gga_vista.php?FechaInicio=" . $_POST['FechaInicio'] . "&FechaFin=" . $_POST['FechaFin'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
	      }
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
$query_costos_gga_edit = sprintf("SELECT * FROM TblDetalleGGAProd WHERE FechaInicio ='%s' AND FechaFin ='%s'",$colname_fechaini,$colname_fechafin);
$costos_gga_edit = mysql_query($query_costos_gga_edit, $conexion1) or die(mysql_error());
$row_costos_gga_edit = mysql_fetch_assoc($costos_gga_edit);
$totalRows_costos_gga_edit = mysql_num_rows($costos_gga_edit);
//DISTINCT
$colname_fechaini = "-1";
if (isset($_GET['FechaInicio'])) {
  $colname_fechaini = (get_magic_quotes_gpc()) ? $_GET['FechaInicio'] : addslashes($_GET['FechaInicio']);
}
$colname_fechafin = "-1";
if (isset($_GET['FechaFin'])) {
  $colname_fechafin = (get_magic_quotes_gpc()) ? $_GET['FechaFin'] : addslashes($_GET['FechaFin']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costos_promedio = sprintf("SELECT DISTINCT FechaInicio, FechaFin, TotalGGA, UnidadesProducidas, CostoGGAxUn, TotalGGA_parcial, porc_parcial, UnidadesProducidas_parcial, CostoGGAxUn_parcial, TotalGGA_impresion, porc_impresion, UnidadesProducidas_impresion, CostoGGAxUn_impresion FROM TblDetalleGGAProd WHERE FechaInicio ='%s' AND FechaFin ='%s'",$colname_fechaini,$colname_fechafin);
$costos_promedio = mysql_query($query_costos_promedio, $conexion1) or die(mysql_error());
$row_costos_promedio = mysql_fetch_assoc($costos_promedio);
$totalRows_costos_promedio = mysql_num_rows($costos_promedio);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center">
<img src="images/cabecera.jpg"></td>
</tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
		   <li><a href="menu.php">MENU PRINCIPAL</a></li>
           <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
           <li><a href="costos_listado_ggaycif.php"> GGA Y CIF PROMEDIO</a></li>
</ul></td>
</tr>
  <tr>
    <td colspan="2" id="linea1">
                     <table align="right">
                     <tr>
                     <td colspan="2" id="titulo2">&nbsp;</td>
                     </tr>
                    <form action="delete.php" method="GET" name="form2">
                  <tr>
                    <td colspan="2" id="titulo2">                       
                        <input name="FechaInicio" type="hidden" min="2000-01-02" value="<?php echo $row_costos_promedio['FechaInicio']; ?>"/>
                        <input name="FechaFin" type="hidden"  min="2000-01-02" value="<?php echo $row_costos_promedio['FechaFin']; ?>" /> 
                        <?php do { ?>
                        <input name="IDCaracGGA[]" id="IDCaracGGA[]" type="hidden" size="3" value="<?php echo $row_costos_gga_edit['IDCaracGGA']; ?>"/> 
						<?php } while ($row_costos_gga_edit = mysql_fetch_assoc($costos_gga_edit)); ?> 
                        </td>
                     <td colspan="2" id="titulo2"> Eliminar Costo:
                          <a href="#"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COSTO" title="ELIMINAR COSTO" border="0" onclick="return eliminar_costeo();"/></a>
                          <input name="costeo" type="hidden" id="costeo" value="1" /> 
                       </td>           
                  </tr>
                  </form>         
                  </table>
      <table border="0" id="tabla1">      
        <tr>
          <td colspan="2" id="dato1">
            <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
              <fieldset>
                <legend>COSTO GGA Y CIF UNIDAD PROMEDIO PRODUCIDA</legend>
                <table id="tabla2">
                  <tr>
                    <td id="fuente3" colspan="8"><?php if($row_usuario['tipo_usuario'] != '4') { ?>
      <a href="costos_gga_add.php"><img src="images/mas.gif" alt="ADD COSTOS" title="ADD COSTOS" border="0" style="cursor:hand;"/></a><?php } ?>
      <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                    </tr>
                      <tr>
                      <td id="fuente1" nowrap="nowrap">Fecha inicial:                        </td>
                      <td id="fuente1"><input name="FechaInicio" type="date" id="FechaInicio" min="2000-01-02" size="10"  value="<?php echo $row_costos_promedio['FechaInicio']; ?>"/></td>
                      <td colspan="2" nowrap="nowrap" id="fuente1">fecha final:                        </td>
                      <td><input name="FechaFin" type="date" id="FechaFin" min="2000-01-02" size="10"  value="<?php echo $row_costos_promedio['FechaFin']; ?>" /></td>
                      <td id="fuente1" nowrap="nowrap">Responsable:                     
                      <td id="fuente1"><input name="ResponsableGGA" type="text" id="ResponsableGGA" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="15" readonly="readonly"/></td>                                           
                      </tr>
                      <tr>
                    <td colspan="7">&nbsp;</td>
                  </tr>
                  
                  <tr>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>
                   
                  <tr>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>         
                  </table>                
                </fieldset>
 
        
              <fieldset>
           <legend>COSTO GGA Y CIF POR CUARTILES UNIDAD COMPLETA PROCESADA</legend>
  <table id="tabla2">
      <tr id="tr1">
                    <td colspan="3" id="fuente1">Total Valores GGA Y CIF</td>
                    <td colspan="2" id="fuente1">Undades Producidas X (MES)</td>
                    <td colspan="2" id="fuente1">Costo GGA promedio Und Producida</td>
                  </tr>
                  <tr>
                    <td colspan="2" id="fuente1"><input type="text" name="TotalGGA" id="TotalGGA" min="0" step="0.01" style=" width:150px"  value="<?php echo $row_costos_promedio['TotalGGA'];?>"/></td>
                    <td nowrap="nowrap" id="fuente1">%
                      <input type="number" name="porcen" id="porcen" min="0" step="1" style=" width:40px" value="100" readonly="readonly"/></td>
                    <td colspan="2" id="fuente1"><input type="text" name="UnidadesProducidas" id="UnidadesProducidas" min="0" step="1" style=" width:150px"  value="<?php echo $row_costos_promedio['UnidadesProducidas'];?>"/></td>
                    <td colspan="2" id="fuente1">$ 
                      <input type="number" name="CostoGGAxUn" id="CostoGGAxUn" min="0" step="0.01" style=" width:150px"  value="<?php echo $row_costos_promedio['CostoGGAxUn'];?>"/></td>
                  </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">&nbsp;</td>
      <td colspan="4" rowspan="5" id="fuente1">
<table id="justificar" >
  <tr>
    <td id="subppal3">Bolsa de M&aacute;ximo 500 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 501 y 1000 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 1001 y 1500 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 1501 y 4000 cm&sup2;</td>
    </tr>
  <tr>
    <?php  for ($x=0;$x<4;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $cp=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $cp; ?>"/></td>
    <?php  } ?>
    </tr>
  <tr>
    <?php  for ($x=4;$x<8;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vc=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vc; ?>"/></td>
    <?php  } ?>
    </tr>
  <tr>
    <?php  for ($x=8;$x<12;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="1" style=" width:80px" required="required" value="<?php $vd=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vd; ?>"/></td>
    <?php  } ?>
    </tr> 
  <tr>
    <?php  for ($x=12;$x<16;$x++) { ?>
    <td id="fuente1">$
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" />  
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vf=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vf; ?>"/></td>
    <?php  } ?>
    </tr>                                
</table>      
 </td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun produccion promedio</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Ajustes</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun promedio real</td>
      </tr>      
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun produccion y ajuste</td>
      </tr>
    <tr>
       <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
       </table> 
        </fieldset>
 
        
              <fieldset>
           <legend>COSTO GGA  Y CIF POR CUARTILES UNIDAD PARCIALMENTE PROCESADA</legend>
<table id="tabla2">
      <tr id="tr1">
                    <td colspan="3" id="fuente1">Total Valores GGA Y CIF</td>
                    <td colspan="2" id="fuente1">Undades Producidas X (MES)</td>
                    <td colspan="2" id="fuente1">Costo GGA promedio Und Producida</td>
                  </tr>
                  <tr>
                    <td colspan="2" id="fuente1"><input type="text" name="gga_parcial" id="gga_parcial" step="0.01" style=" width:150px"  value="<?php echo $row_costos_promedio['TotalGGA_parcial'];?>"/></td>
                    <td nowrap="nowrap" id="fuente1">%
                      <input type="number" name="porc_parcial" id="porc_parcial" min="0" step="1" style=" width:40px" value="<?php echo $row_costos_promedio['porc_parcial'];?>" readonly="readonly"/></td>
                    <td colspan="2" id="fuente1"><input type="text" name="UnidadesProducidas_parcial" id="UnidadesProducidas_parcial" min="0" step="1" style=" width:150px"  value="<?php echo $row_costos_promedio['UnidadesProducidas_parcial'];?>"/></td>
                    <td colspan="2" id="fuente1">$
                      <input type="number" name="CostoGGAxUn_parcial" id="CostoGGAxUn_parcial" step="0.01" style=" width:150px"  value="<?php echo $row_costos_promedio['CostoGGAxUn_parcial'];?>"/></td>
                    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">&nbsp;</td>
      <td colspan="4" rowspan="5" id="fuente1">
<table id="justificar" >
  <tr>
    <td id="subppal3">Bolsa de M&aacute;ximo 500 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 501 y 1000 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 1001 y 1500 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 1501 y 4000 cm&sup2;</td>
    </tr>
  <tr>
    <?php  for ($x=16;$x<20;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $cp=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $cp; ?>"/></td>
    <?php  } ?>
    </tr>
  <tr>
    <?php  for ($x=20;$x<24;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vc=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vc; ?>"/></td>
    <?php  } ?>
    </tr>
  <tr>
    <?php  for ($x=24;$x<28;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vd=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vd; ?>"/></td>
    <?php  } ?>
    </tr> 
  <tr>
    <?php  for ($x=28;$x<32;$x++) { ?>
    <td id="fuente1">$
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" />  
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vf=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vf; ?>"/></td>
    <?php  } ?>
    </tr>                                
</table>      
 </td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun produccion promedio</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Ajustes</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun promedio real</td>
      </tr>      
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun produccion y ajuste</td>
      </tr>
    <tr>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
       </table>     
       
           
                </fieldset>
                
                
              <fieldset>
           <legend>COSTO GGA  Y CIF POR CUARTILES UNIDAD PROCESADA SIN IMPERSION</legend>
<table id="tabla2">
      <tr id="tr1">
                    <td colspan="3" id="fuente1">Total Valores GGA Y CIF</td>
                    <td colspan="2" id="fuente1">Undades Producidas X (MES)</td>
                    <td colspan="2" id="fuente1">Costo GGA promedio Und Producida</td>
                  </tr>
                  <tr>
                    <td colspan="2" id="fuente1"><input type="text" name="gga_impresion" id="gga_impresion" min="0" step="0.01" style=" width:150px"  value="<?php echo $row_costos_promedio['TotalGGA_impresion'];?>"/></td>
                    <td nowrap="nowrap" id="fuente1">%
                      <input type="number" name="porc_impresion" id="porc_impresion" min="0" step="1" style=" width:40px" value="<?php echo $row_costos_promedio['porc_impresion'];?>" readonly="readonly"/></td>
                    <td colspan="2" id="fuente1"><input type="text" name="UnidadesProducidas_impresion" id="UnidadesProducidas_impresion" min="0" step="1" style=" width:150px"  value="<?php echo $row_costos_promedio['UnidadesProducidas_impresion'];?>"/></td>
                    <td colspan="2" id="fuente1">$
                      <input type="number" name="CostoGGAxUn_impresion" id="CostoGGAxUn_impresion" min="0" step="0.01" style=" width:150px"  value="<?php echo $row_costos_promedio['CostoGGAxUn_impresion'];?>"/></td>
                    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">&nbsp;</td>
      <td colspan="4" rowspan="5" id="fuente1">
<table id="justificar" >
  <tr>
    <td id="subppal3">Bolsa de M&aacute;ximo 500 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 501 y 1000 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 1001 y 1500 cm&sup2;</td>
    <td id="subppal3">Bolsa entre 1501 y 4000 cm&sup2;</td>
    </tr>
  <tr>
    <?php  for ($x=32;$x<36;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $cpi=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $cpi; ?>"/></td>
    <?php  } ?>
    </tr>
  <tr>
    <?php  for ($x=36;$x<40;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vci=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vci; ?>"/></td>
    <?php  } ?>
    </tr>
  <tr>
    <?php  for ($x=40;$x<44;$x++) { ?>
    <td id="fuente1">$ 
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" /> 
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vdi=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vdi; ?>"/></td>
    <?php  } ?>
    </tr> 
  <tr>
    <?php  for ($x=44;$x<48;$x++) { ?>
    <td id="fuente1">$
      <input name="id[]" type="hidden" value="<?php $id_gga=mysql_result($costos_gga_edit,$x,IDCaracGGA);echo  $id_gga;?>" />  
      <input type="number" name="valor[]" id="valor[]" step="0.01" style=" width:80px" required="required" value="<?php $vfi=mysql_result($costos_gga_edit,$x,ValorCaracGGA);echo $vfi; ?>"/></td>
    <?php  } ?>
    </tr>                                
</table>      
 </td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun produccion promedio</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Ajustes</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun promedio real</td>
      </tr>      
    <tr>
      <td colspan="2" id="fuente1" nowrap="nowrap">Costo GGA Y CIF segun produccion y ajuste</td>
      </tr>
    <tr>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="6" id="fuente2"><input name="MM_update" type="hidden" value="form1" />        <input type="submit" name="button" id="button" value="EDITAR GGA Y CIF PROMEDIO" /></td>
      </tr>
       </table>     
       
           
                </fieldset>                
              </form>
            </td>     
          </tr>
        </table>
      </td>
  </tr>
</table
></div>
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

mysql_free_result($caracteristica_gga);

mysql_free_result($bolsas_producidas);

?>
