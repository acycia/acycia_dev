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
  $insertSQL = sprintf("INSERT INTO TblDetalleGGAProd( FechaInicio, FechaFin, TotalGGA, UnidadesProducidas, CostoGGAxUn, IDCaracGGA, ValorCaracGGA) VALUES ( %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FechaInicio'], "date"),
                       GetSQLValueString($_POST['FechaFin'], "date"),
                       GetSQLValueString($_POST['TotalGGA'], "double"),
                       GetSQLValueString($_POST['UnidadesProducidas'], "double"),
                       GetSQLValueString($_POST['CostoGGAxUn'], "double"),
                       GetSQLValueString($a[$x], "int"),
                       GetSQLValueString($b[$x], "double"));

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
</head>
<body onload="consulta_gga_ajuste()">
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
</ul></td>
</tr>
  <tr>
    <td colspan="2" align="center" id="linea1">
      <table border="0" id="tabla1">      
        <tr>
          <td colspan="2" id="dato1">
            <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
              <fieldset>
                <legend>COSTO CIF UNIDAD PROMEDIO PRODUCIDA</legend>
                <table id="tabla2">
                  <tr>
                    <td id="fuente3" colspan="13">&nbsp;</td>
                    </tr>
                      <tr>
                      <td colspan="4">Fecha inicial:
                        <input name="FechaInicio" type="date" required="required" id="FechaInicio" min="2000-01-02" size="10" value="<?php echo $_GET['FechaInicio']; ?>"/></td>
                      <td colspan="4">fecha final:
                        <input name="FechaFin" type="date" id="FechaFin" min="2000-01-02" size="10" required="required" value="<?php echo $_GET['FechaFin']; ?>" onChange="if(form1.FechaInicio.value && form1.FechaFin.value) { consulta_gga_fechas(); }else { alert('Debe Seleccionar las dos fechas')}"/></td>
                      <td colspan="4">&nbsp;</td>
                      </tr>                    
                  <tr>
                    <td colspan="13">&nbsp;</td>
                  </tr>
                  <tr id="tr1">
                    <td id="fuente2">N&Uacute;MERO</td>
                    <td id="fuente2">1</td>
                    <td id="fuente2">1</td>
                    <td id="fuente2">2</td>
                    <td id="fuente2">1</td>
                    <td id="fuente2">2</td>
                    <td id="fuente2">3</td>
                    <td id="fuente2">5</td>
                    <td id="fuente2">6</td>
                    <td id="fuente2">7</td>
                    <td id="fuente2">1</td>
                    <td id="fuente2">TOTALES</td>
                    </tr>
                  <tr>
                    <td id="fuente2">M&Aacute;QUINA </td>
                    <td id="fuente2">Extrusora</td>
                    <td id="fuente2">Impresora 1</td>
                    <td id="fuente2">Impresora 2</td>
                    <td id="fuente2">Refiladora</td>
                    <td id="fuente2">Selladora 2</td>
                    <td id="fuente2">Selladora 3</td>
                    <td id="fuente2">Selladora 5</td>
                    <td id="fuente2">Selladora 6</td>
                    <td id="fuente2">Selladora 7</td>
                    <td id="fuente2">Monta cargas</td>
                    <td id="fuente2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td id="fuente2">AMPERAJE</td>
                    <td id="fuente2"><input type="number" name="extr" id="extr" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr2" id="extr2" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr3" id="extr3" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr4" id="extr4" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr5" id="extr5" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr6" id="extr6" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr7" id="extr7" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr8" id="extr8" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr9" id="extr9" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr10" id="extr10" min="0" step="1" style=" width:40px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                    <td id="fuente2"><input type="number" name="extr11" id="extr11" min="0" step="1" style=" width:60px" required="required" value="<?php echo redondear_entero($pro);?>"/></td>
                  </tr>
                  <tr>
                    <td colspan="4" id="fuente2">&nbsp;</td>
                    <td colspan="4" id="fuente2">&nbsp;</td>
                    <td colspan="4" id="fuente2">&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="4" id="fuente1">      
					  <?php
					  $fecha1=$_GET['FechaInicio'];
					  $fecha2=$_GET['FechaFin'];					   
					  $sqlgga="SELECT SUM(Tbl_generadores_valor.valor_gv) AS gga FROM Tbl_generadores_valor,Tbl_generadores WHERE  Tbl_generadores_valor.fecha_ini_gv='$fecha1' AND Tbl_generadores_valor.fecha_fin_gv='$fecha2' AND Tbl_generadores_valor.id_generadores_gv=Tbl_generadores.id_generadores AND Tbl_generadores.categoria_generadores='CIF'"; 
					  $resultgga=mysql_query($sqlgga); 
					  $numgga=mysql_num_rows($resultgga); 
					  if($numgga >= '1') 
					  { $gga=mysql_result($resultgga,0,'gga'); 
					  } else { echo "VACIO";}?>
                      <input type="hidden" name="TotalGGA" id="TotalGGA" min="0" step="0.01" value="<?php echo $gga;?>"/>
                      <input type="text" name="gga" id="gga" min="0" step="0.01" style=" width:150px" readonly="readonly" value="<?php echo numeros_format($gga);?>"/></td>
                    <td colspan="4" id="fuente1">
                      <?php 
					  $fecha1=$_GET['FechaInicio'];
					  $fecha2=$_GET['FechaFin'];
					  $sqlbo="SELECT SUM(bolsa_rp) AS bolsa FROM Tbl_reg_produccion WHERE id_proceso_rp='3' AND DATE( fecha_ini_rp ) >= '$fecha1' AND DATE( fecha_fin_rp ) <= '$fecha2'"; 
					  $resultbo=mysql_query($sqlbo); 
					  $numbo=mysql_num_rows($resultbo); 
					  if($numbo >= '1') 
					  { $und_bo=mysql_result($resultbo,0,'bolsa');
					  }else { echo "VACIO";	
					  }?>
                      <input type="hidden" name="UnidadesProducidas" id="UnidadesProducidas" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo $und_bo;?>"/>
                      <input type="text" name="bolsas" id="bolsas" min="0" step="1" style=" width:150px" readonly="readonly" value="<?php echo numeros_format($und_bo);?>"/></td>
                    <td colspan="4" id="fuente1">$ 
                      <input type="number" name="CostoGGAxUn" id="CostoGGAxUn" min="0" step="0.01" style=" width:150px" readonly="readonly" value="<?php $pro=$gga/$und_bo;echo redondear_decimal($pro); ?>"/></td>
                    </tr>
                  <tr>
                    <td colspan="4" id="fuente5">&nbsp;</td>
                    <td colspan="4" id="fuente5">&nbsp;</td>
                    <td colspan="4" id="fuente5">&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    <td colspan="4" id="fuente4">&nbsp;</td>
                    </tr>        
                  </table>                
                </fieldset>
 
        
              <fieldset>
           <legend>COSTO GGA POR CUARTILES</legend>
  <table id="tabla2">
      <tr>
      <td colspan="5">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">GGA  Bolsa de M&aacute;ximo 250 cm&sup2;</td>
      <td id="fuente1">GGA  Bolsa entre 250 y 500 cm&sup2;</td>
      <td id="fuente1">GGA  Bolsa entre 501 y 1000 cm&sup2;</td>
      <td id="fuente1">GGA  Bolsa entre 1001 y 4000 cm&sup2;</td>
      </tr>
    <tr>
      <td id="fuente1">Costo GGA segun produccion promedio</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="1"/>        <input type="number" name="valor[]" id="valor[]" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_entero($pro);?>"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="2"/>        <input type="number" name="valor[]" id="valor[]" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_entero($pro);?>"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="3"/>        <input type="number" name="valor[]" id="valor[]" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_entero($pro);?>"/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="4"/>        <input type="number" name="valor[]" id="valor[]" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value="<?php echo redondear_entero($pro);?>"/></td>
      </tr>
    <tr>
      <td id="fuente1">Ajustes</td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="5"/>        <input type="number" name="valor[]" id="ajuste_250" min="0" step="0.01" style=" width:80px" value="0.00" required="required" onblur="consulta_gga_ajuste()"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="6"/>        <input type="number" name="valor[]" id="ajuste_500" min="0" step="0.01" style=" width:80px" value="0.00" required="required" onblur="consulta_gga_ajuste()"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="7"/>        <input type="number" name="valor[]" id="ajuste_1000" min="0" step="0.01" style=" width:80px" value="0.00" required="required" onblur="consulta_gga_ajuste()"/></td>
      <td id="fuente1">$
        <input type="hidden" name="id[]" id="id[]" value="8"/>        <input type="number" name="valor[]" id="ajuste_4000" min="0" step="0.01" style=" width:80px" value="0.00" required="required" onblur="consulta_gga_ajuste()"/></td>
      </tr>
    <tr>
      <td id="fuente1">Costo GGA segun promedio real</td>
      <td id="fuente1"><?php $cm='250'; ?>
      $
        <input type="hidden" name="id[]" id="id[]" value="9"/>         <input type="number" name="valor[]" id="real_250" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$cm);?>"/></td>
      <td id="fuente1"><?php $cm='500';  ?>
      $
        <input type="hidden" name="id[]" id="id[]" value="10"/>        <input type="number" name="valor[]" id="real_500" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$cm);?>"/></td>
      <td id="fuente1"><?php $cm='1000';  ?>
      $
        <input type="hidden" name="id[]" id="id[]" value="11"/>        <input type="number" name="valor[]" id="real_1000" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$cm);?>"/></td>
      <td id="fuente1"><?php $cm='1500';  ?>
      $
        <input type="hidden" name="id[]" id="id[]" value="12"/>        <input type="number" name="valor[]" id="real_4000" min="0" step="1" style=" width:80px" required="required" readonly="readonly" value="<?php echo restar_ajuste($pro,$cm);?>"/></td>
      </tr>      
    <tr>
      <td id="fuente1">Costo GGA segun produccion y ajuste</td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="13"/>        <input type="number" name="valor[]" id="gga_250" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="14"/>        <input type="number" name="valor[]" id="gga_500" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="15"/>        <input type="number" name="valor[]" id="gga_1000" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/></td>
      <td id="dato1">$
        <input type="hidden" name="id[]" id="id[]" value="16"/>        <input type="number" name="valor[]" id="gga_4000" min="0" step="0.01" style=" width:80px" required="required" readonly="readonly" value=""/></td>
      </tr>
    <tr>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="5" id="fuente2"><input type="hidden" name="MM_insert" value="form1" />        <input type="submit" name="button" id="button" value="Guardar" /></td>
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
