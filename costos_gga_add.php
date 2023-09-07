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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
if (!empty ($_POST['id'])&&!empty ($_POST['valor'])){
    foreach($_POST['id'] as $key=>$k)
    $a[]= $k;
    foreach($_POST['valor'] as $key=>$k)
    $b[]= $k;
				
	for($x=0; $x<count($b); $x++) {
    foreach($_POST['area'] as $key=>$k)//el area se hubica dentro del for para que se repita para todos los registros
    $c[]= $k;
	foreach($_POST['nivel'] as $key=>$k)
    $d[]= $k;	
  $insertSQL = sprintf("INSERT INTO TblDetalleGGAProd( FechaInicio, FechaFin, ResponsableGGA, TotalGGA, UnidadesProducidas, CostoGGAxUn,TotalGGA_parcial,porc_parcial,UnidadesProducidas_parcial,CostoGGAxUn_parcial, TotalGGA_impresion,porc_impresion,UnidadesProducidas_impresion,CostoGGAxUn_impresion, IDCaracGGA, ValorCaracGGA, AreaCaracGGA, NivelCaracGGA) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($a[$x], "int"),
                       GetSQLValueString($b[$x], "double"),
					   GetSQLValueString($c[$x], "int"),
					   GetSQLValueString($d[$x], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "costos_gga_vista.php?FechaInicio=" . $_POST['FechaInicio'] . "&FechaFin=" . $_POST['FechaFin'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_conexion1, $conexion1);
$query_caracteristica_gga = "SELECT * FROM TblCaracGGA ORDER BY TblCaracGGA.IDCaracGGA ASC";
$caracteristica_gga = mysql_query($query_caracteristica_gga, $conexion1) or die(mysql_error());
$row_caracteristica_gga = mysql_fetch_assoc($caracteristica_gga);
$totalRows_caracteristica_gga = mysql_num_rows($caracteristica_gga);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
</head>
<body onload="consulta_gga_ajuste(),consulta_gga_ajuste_parcial(),totalGGAyCIF(),promedioUnidadParcial(),consulta_gga_ajuste_impresion(),promedioUnidadImpresion()">
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
    <td colspan="2" align="center" id="linea1">
      <table border="0" id="tabla1">      
        <tr>
          <td colspan="2" id="dato1">
            <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
              <fieldset>
                <legend>COSTO GGA Y CIF UNIDAD PROMEDIO PRODUCIDA</legend>
                <table id="tabla2">
                  <tr>
                    <td id="fuente3" colspan="8">&nbsp;</td>
                    </tr>
                      <tr>
                      <td nowrap="nowrap" id="fuente1">Fecha inicial:                        </td>
                      <td><input name="FechaInicio" type="date" required="required" id="FechaInicio" min="2000-01-02" size="10" value="<?php echo $_GET['FechaInicio']; ?>"/></td>
                      <td nowrap="nowrap" colspan="2" id="fuente1">fecha final:                        </td>
                      <td><input name="FechaFin" type="date" id="FechaFin" min="2000-01-02" size="10" required="required" value="<?php echo $_GET['FechaFin']; ?>" onchange="if(form1.FechaInicio.value && form1.FechaFin.value) { consulta_gga_fechas(); }else { alert('Debe Seleccionar las dos fechas')}"/></td>
                      <td nowrap="nowrap" id="fuente1">Responsable:                        </td>
                      <td><input name="ResponsableGGA" type="text" id="ResponsableGGA" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="15" readonly="readonly"/></td>
                      </tr>                    
                  <tr>
                    <td colspan="8">&nbsp;</td>
                  </tr>
                          
                  </table>                
                </fieldset>
 
        
              <fieldset>
           <legend>COSTO GGA Y CIF POR CUARTILES UNIDAD COMPLETA PROCESADA</legend>
  <table id="tabla2">
      <tr id="tr1">
                    <td colspan="3" id="fuente1">Total  GGA Y CIF Procesada Predeterminada</td>
                    <td colspan="2" id="fuente1">Undades Producidas X (MES)</td>
                    <td colspan="2" id="fuente1">Costo GGA Y CIF promedio Und Producida</td>
                    </tr>
                  <tr>
                    <td colspan="2" id="fuente1">      
                      <?php
					  $fecha1=$_GET['FechaInicio'];
					  $fecha2=$_GET['FechaFin'];					   
					  $sqlgga="SELECT SUM(Tbl_generadores_valor.valor_gv) AS gga FROM Tbl_generadores_valor,Tbl_generadores WHERE  DATE(Tbl_generadores_valor.fecha_ini_gv) ='$fecha1' AND DATE(Tbl_generadores_valor.fecha_fin_gv) ='$fecha2' AND Tbl_generadores_valor.id_generadores_gv=Tbl_generadores.id_generadores"; 
					  $resultgga=mysql_query($sqlgga); 
					  $numgga=mysql_num_rows($resultgga); 
					  if($numgga >= '1') 
					  { $gga=mysql_result($resultgga,0,'gga'); 
					  } else { echo "VACIO";}?>
                      <input type="hidden" name="TotalGGA" id="TotalGGA" min="0" step="0.01" value="<?php echo $gga;?>"/>
                      <input type="text" name="gga" id="gga" min="0" step="0.01" style=" width:150px" readonly="readonly" value="<?php echo numeros_format($gga);?>"/></td>
                    <td nowrap="nowrap" id="fuente1">%
                      <input type="number" name="porcen" id="porcen" min="0" step="1" style=" width:40px" value="100" readonly="readonly" onblur="consulta_gga_ajuste()"/></td>
                    <td colspan="2" id="fuente1">
                      <?php 
					  $fecha1=$_GET['FechaInicio'];
					  $fecha2=$_GET['FechaFin'];
					  $sqlbo="SELECT SUM(bolsa_rp) AS bolsa FROM Tbl_reg_produccion WHERE  DATE( fecha_ini_rp ) BETWEEN  '$fecha1' AND '$fecha2' AND DATE( fecha_fin_rp ) BETWEEN '$fecha1' AND '$fecha2'"; 
					  $resultbo=mysql_query($sqlbo); 
					  $numbo=mysql_num_rows($resultbo); 
					  if($numbo >= '1') 
					  { $und_bo=mysql_result($resultbo,0,'bolsa');
					  }else { echo "VACIO";	
					  }?>
                      <input type="hidden" name="UnidadesProducidas" id="UnidadesProducidas" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo $und_bo;?>"/>
                      <input type="text" name="bolsas" id="bolsas" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo redondear_entero($und_bo);?>"/></td>
                    <td colspan="2" id="fuente1">$ 
                      <input type="number" name="CostoGGAxUn" id="CostoGGAxUn" min="0" step="0.01" style=" width:80px" readonly="readonly" value="<?php $pro=$gga/$und_bo;echo redondear_decimal($pro); ?>"/></td>
                  </tr>
                  <tr>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1"><?php do { ?><input type="hidden" name="area[]" id="area[]" value="<?php echo $row_caracteristica_gga['ValorBolsaGGA']?>"/><?php } while ($row_caracteristica_gga = mysql_fetch_assoc($caracteristica_gga)); ?></td>
      <td id="fuente1">GGA  Y CIF Bolsa de M&aacute;ximo 500<?php $dc='250'; ?> cm&sup2;</td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 501 y 1000<?php  $dq='500'; ?> cm&sup2;</td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 1001 y 1500<?php $qm='1000'; ?> cm&sup2;</td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 1501 y 4000<?php $mc='1500'; ?> cm&sup2;</td>
      </tr>
    <tr>
      <td colspan="2"id="fuente1">Costo GGA Y CIF segun produccion promedio</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="1"/>        <input type="number" name="valor[]" id="promedio_250" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_decimal($pro);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="2"/>        <input type="number" name="valor[]" id="promedio_500" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_decimal($pro);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="3"/>        <input type="number" name="valor[]" id="promedio_1000" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_decimal($pro);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="4"/>        <input type="number" name="valor[]" id="promedio_4000" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_decimal($pro);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      </tr>
    <tr>
      <td colspan="2"id="fuente1">Ajustes</td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="5"/>        <input type="number" name="valor[]" id="ajuste_250" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste()"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="6"/>        <input type="number" name="valor[]" id="ajuste_500" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste()"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="7"/>        <input type="number" name="valor[]" id="ajuste_1000" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste()"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="8"/>        <input type="number" name="valor[]" id="ajuste_4000" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste()"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      </tr>
    <tr>
      <td colspan="2"id="fuente1">Costo GGA Y CIF segun promedio real</td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="9"/>         <input type="number" name="valor[]" id="real_250" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$dc);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="10"/>        <input type="number" name="valor[]" id="real_500" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$dq);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="11"/>        <input type="number" name="valor[]" id="real_1000" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$qm);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="12"/>        <input type="number" name="valor[]" id="real_4000" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$mc);?>"/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      </tr>      
    <tr>
      <td colspan="2" id="fuente1">Costo GGA Y CIF segun produccion y ajuste</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="13"/>        <input type="number" name="valor[]" id="gga_250" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="14"/>        <input type="number" name="valor[]" id="gga_500" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="15"/>        <input type="number" name="valor[]" id="gga_1000" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="16"/>        <input type="number" name="valor[]" id="gga_4000" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="1"/></td>
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
      <td colspan="6" id="fuente2"> </td>
      </tr>
       </table>     
                </fieldset>
    <fieldset>
      <legend>COSTO GGA  Y CIF POR CUARTILES UNIDAD PARCIALMENTE PROCESADA</legend>                
<table id="tabla2">
      <tr id="tr1">
                    <td colspan="3" id="fuente1">Total  GGA Y CIF Parcialmente Procesada </td>
                    <td colspan="2" id="fuente1">Undades Producidas X (MES)</td>
                    <td colspan="2" id="fuente1">Costo GGA promedio Und Producida Parcial</td>
                    </tr>
                  <tr>
                    <td colspan="2" id="fuente1"><input type="hidden" name="TotalGGA_parcial" id="TotalGGA_parcial" min="0" step="0.01" value="<?php echo $gga;?>"/><input type="text" name="gga_parcial" id="gga_parcial" min="0"  style=" width:150px" readonly="readonly" value=""/></td>
                    <td nowrap="nowrap" id="fuente1">%<input type="number" name="porc_parcial" id="porc_parcial" min="0" step="1" style=" width:40px" value="67" required="required" onchange="totalGGAyCIF(),promedioUnidadParcial(),consulta_gga_ajuste_parcial();"/></td>
                    <td colspan="2" id="fuente1"><input type="hidden" name="UnidadesProducidas_parcial" id="UnidadesProducidas_parcial" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo $und_bo;?>"/><input type="text" name="bolsas_parcial" id="bolsas_parcial" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo redondear_entero($und_bo);?>"/></td>
                    <td colspan="2" id="fuente1">$
                      <input type="number" name="CostoGGAxUn_parcial" id="CostoGGAxUn_parcial" min="0" step="0.01" style=" width:80px" readonly="readonly" value=""/></td>
                    </tr>  
                <tr>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">&nbsp;</td>
      <td id="fuente1">GGA  Y CIF Bolsa de M&aacute;ximo 500 cm&sup2;
        <input type="hidden" name="dc" id="dc" value="250"/></td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 501 y 1000 cm&sup2;
        <input type="hidden" name="dq" id="dq" value="500"/></td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 1001 y 1501 cm&sup2;
        <input type="hidden" name="qm" id="qm" value="1000"/></td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 1501 y 4000 cm&sup2;
        <input type="hidden" name="mc" id="mc" value="1500"/></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">Costo GGA Y CIF segun produccion promedio</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="17"/>        <input type="number" name="valor[]" id="promedio_250_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="18"/>        <input type="number" name="valor[]" id="promedio_500_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="19"/>        <input type="number" name="valor[]" id="promedio_1000_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="20"/>        <input type="number" name="valor[]" id="promedio_4000_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">Ajustes</td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="21"/>        <input type="number" name="valor[]" id="ajuste_250_parcial"  step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_parcial()"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="22"/>        <input type="number" name="valor[]" id="ajuste_500_parcial"  step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_parcial()"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="23"/>        <input type="number" name="valor[]" id="ajuste_1000_parcial"  step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_parcial()"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="24"/>        <input type="number" name="valor[]" id="ajuste_4000_parcial"  step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_parcial()"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">Costo GGA Y CIF segun promedio real</td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="25"/>         <input type="number" name="valor[]" id="real_250_parcial" min="0" step="1" style=" width:80px" required="required" readonly="readonly" /><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="26"/>        <input type="number" name="valor[]" id="real_500_parcial" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="27"/>        <input type="number" name="valor[]" id="real_1000_parcial" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="28"/>        <input type="number" name="valor[]" id="real_4000_parcial" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      </tr>      
    <tr>
      <td colspan="2"id="fuente1">Costo GGA Y CIF segun produccion y ajuste</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="29"/>        <input type="number" name="valor[]" id="gga_250_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="30"/>        <input type="number" name="valor[]" id="gga_500_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="31"/>        <input type="number" name="valor[]" id="gga_1000_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="32"/>        <input type="number" name="valor[]" id="gga_4000_parcial" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="2"/></td>
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
      <td colspan="6" id="fuente2"></td>
      </tr>
       </table>
       </fieldset>   
    <fieldset>
      <legend>COSTO GGA  Y CIF POR CUARTILES UNIDAD PARCIALMENTE PROCESADA SIN IMPRESION</legend>                
<table id="tabla2">
      <tr id="tr1">
                    <td colspan="3" id="fuente1">Total  GGA Y CIF Parcialmente Sin Impresion</td>
                    <td colspan="2" id="fuente1">Undades Producidas X (MES)</td>
                    <td colspan="2" id="fuente1">Costo GGA promedio Und Producida Parcial</td>
                    </tr>
                  <tr>
                    <td colspan="2" id="fuente1"><input type="hidden" name="TotalGGA_impresion" id="TotalGGA_impresion" min="0" step="0.01" value="<?php echo $gga;?>"/><input type="text" name="gga_impresion" id="gga_impresion" min="0"  style=" width:150px" readonly="readonly" value=""/></td>
                    <td nowrap="nowrap" id="fuente1">%<input type="number" name="porc_impresion" id="porc_impresion" min="0" step="1" style=" width:40px" value="67" required="required" onchange="totalGGAyCIF(),promedioUnidadImpresion(),consulta_gga_ajuste_impresion();"/></td>
                    <td colspan="2" id="fuente1"><input type="hidden" name="UnidadesProducidas_impresion" id="UnidadesProducidas_impresion" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo $und_bo;?>"/><input type="text" name="bolsas_impresion" id="bolsas_impresion" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo redondear_entero($und_bo);?>"/></td>
                    <td colspan="2" id="fuente1">$
                      <input type="number" name="CostoGGAxUn_impresion" id="CostoGGAxUn_impresion" min="0" step="0.01" style=" width:80px" readonly="readonly" value=""/></td>
                    </tr>  
                <tr>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">&nbsp;</td>
      <td id="fuente1">GGA  Y CIF Bolsa de M&aacute;ximo 500 cm&sup2;</td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 501 y 1000 cm&sup2;</td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 1001 y 1501 cm&sup2;</td>
      <td id="fuente1">GGA  Y CIF Bolsa entre 1501 y 4000 cm&sup2;</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">Costo GGA Y CIF segun produccion promedio</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="33"/>        <input type="number" name="valor[]" id="promedio_250_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="34"/>        <input type="number" name="valor[]" id="promedio_500_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="35"/>        <input type="number" name="valor[]" id="promedio_1000_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="36"/>        <input type="number" name="valor[]" id="promedio_4000_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">Ajustes</td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="37"/>        <input type="number" name="valor[]" id="ajuste_250_impresion" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_impresion()"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="38"/>        <input type="number" name="valor[]" id="ajuste_500_impresion" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_impresion()"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="39"/>        <input type="number" name="valor[]" id="ajuste_1000_impresion" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_impresion()"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="40"/>        <input type="number" name="valor[]" id="ajuste_4000_impresion" step="0.01" style=" width:80px" value="0.00" required="required" onchange="consulta_gga_ajuste_impresion()"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">Costo GGA Y CIF segun promedio real</td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="41"/>         <input type="number" name="valor[]" id="real_250_impresion" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="42"/>        <input type="number" name="valor[]" id="real_500_impresion" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="43"/>        <input type="number" name="valor[]" id="real_1000_impresion" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="fuente1">
      $
        <input type="hidden" name="id[]" id="id[]" value="44"/>        <input type="number" name="valor[]" id="real_4000_impresion" min="0" step="1" style=" width:80px" required="required" readonly="readonly"/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      </tr>      
    <tr>
      <td colspan="2"id="fuente1">Costo GGA Y CIF segun produccion y ajuste</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="45"/>        <input type="number" name="valor[]" id="gga_250_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="46"/>        <input type="number" name="valor[]" id="gga_500_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="47"/>        <input type="number" name="valor[]" id="gga_1000_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="48"/>        <input type="number" name="valor[]" id="gga_4000_impresion" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/><input type="hidden" name="nivel[]" id="nivel[]" value="3"/></td>
      </tr>
    <tr>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1"></td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="6" id="fuente2"><input type="hidden" name="MM_insert" value="form1" />        <input type="submit" name="button" id="button" value="Guardar" /></td>
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
