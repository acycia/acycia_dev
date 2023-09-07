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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE TblInventarioListado SET SaldoInicial=%s, Entrada=%s, CostoUnd=%s, Acep=%s, Modifico=%s, FechaModif=%s WHERE idInv=%s",
                       GetSQLValueString($_POST['SaldoFinal'], "double"),
					   GetSQLValueString($_POST['Entrada'], "int"),
                       GetSQLValueString($_POST['CostoUnd'], "double"),
                       GetSQLValueString($_POST['Acep'], "int"),
                       GetSQLValueString($_POST['Modifico'], "text"),
					   GetSQLValueString($_POST['FechaModif'], "date"),
                       GetSQLValueString($_POST['idInv'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
  echo "<script type=\"text/javascript\">window.opener.location.reload();self.close();</script>"; 
}

//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN


$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_editar = "1";
if (isset($_GET['idInv'])) {
  $colname_editar = (get_magic_quotes_gpc()) ? $_GET['idInv'] : addslashes($_GET['idInv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar = sprintf("SELECT * FROM TblInventarioListado WHERE idInv='%s'", $colname_editar);
$editar = mysql_query($query_editar, $conexion1) or die(mysql_error());
$row_editar = mysql_fetch_assoc($editar);
$totalRows_editar = mysql_num_rows($editar);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/inventario_ajax.js"></script> 
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
<td align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
  <td align="center" id="linea1">
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
      <table id="tabla2">
        <tr>
          <td colspan="2" id="fuente1">
            <table id="tabla3">
              <tr id="tr1">
                <td nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                <td colspan="5" nowrap="nowrap" id="titulo2">ADICIONAR AL INVENTARIO </td>
                </tr>
              <tr id="tr1">
                <td colspan="2" id="fuente1">Fecha  Ingreso</td>
                <td colspan="3" id="fuente1">Responsable</td>
                </tr>
              <tr>
                <td colspan="2" id="fuente1"><input name="Fecha" type="date" readonly="readonly" id="Fecha" min="2000-01-02" value="<?php echo $row_editar['Fecha'] ?>" style="width:150px"/><input name="FechaModif" type="hidden"  id="Fecha" min="2000-01-02" value="<?php echo fecha(); ?>" style="width:150px"/></td>
                <td colspan="3" id="fuente1"><input name="Modifico" type="text" id="Modifico" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="readonly" /></td>
                </tr>
              <tr>
                <td colspan="5" id="fuente1">&nbsp;</td>
                </tr>
              <tr>
                <td colspan="6" id="fuente2">&nbsp;</td>
              </tr>
              <tr id="tr1">
                <td id="titulo1">INVENTARIO INICIAL</td>
                <td id="titulo1">ENTRADAS</td>
                <td id="titulo1">VALOR MEDIDA</td>
                <td id="titulo1">ACEPTADAS</td>
                <td id="titulo1">GUARDA</td>
                </tr>
              <tr>
                <td id="dato1"><input name="SaldoFinal" type="number" id="SaldoFinal" min="0" step="0.01" style="width:100px" required="required" value="<?php echo $row_editar['SaldoInicial'] ?>"/></td>
                <td id="dato1"><input name="Entrada" type="number" id="Entrada" min="0" step="0.01" style="width:100px" required="required" value="<?php echo $row_editar['Entrada'] ?>"/></td>
                <td id="dato1"><?php
	  //VALOR UNIDAD
	  if($_GET['tipo']=='1'){
	  
	  $Cod_ref=$row_editar['Cod_ref'];
	  $sqlcosto = "SELECT str_unidad_io,int_precio_io FROM Tbl_items_ordenc WHERE int_cod_ref_io = $Cod_ref ORDER BY id_items DESC LIMIT 1";
	  $resultcosto=mysql_query($sqlcosto); 
	  $numcosto=mysql_num_rows($resultcosto); 
	  if ($numcosto > 0)
	  { 
		$valor_ins=mysql_result($resultcosto,0,'int_precio_io');
		echo $medRefConv = mysql_result($resultcosto,0,'str_unidad_io');
		
		if($medRefConv=='MILLAR')
		{
			$dividemil='1000';
			($valor_insumo=$valor_ins/$dividemil);}
		else
		{$dividemil='1';
		$valor_insumo=$valor_ins;}
 	   }
 	  }//fin if
	  else{ 
		  $valor_insumo=($row_editar['CostoUnd']);
		   
		  }
     ?><div id="resultado_generador"><input name="CostoUnd" type="number" id="CostoUnd" min="0" step="0.001" style="width:100px" required="required" value="<?php echo $valor_insumo; ?>"/></div></td>
                <td id="dato1"><select name="Acep" id="Acep">
                <option value="0"<?php if (!(strcmp("0", $row_editar['Acep']))) {echo "selected=\"selected\"";} ?>>Conforme</option>
                <option value="1"<?php if (!(strcmp("1", $row_editar['Acep']))) {echo "selected=\"selected\"";} ?>>No Conforme</option>
                </select></td>
                <td id="dato1"><input type="submit" name="Submit" value="Editar" /></td>
                </tr>
              <tr>
                <td colspan="6" id="dato4">&nbsp;</td>
                </tr>
              <tr>
                <td colspan="6" id="dato2"><input name="idInv" type="hidden" id="idInv" value="<?php echo $row_editar['idInv'] ?>" />                  
                <input type="hidden" name="MM_update" value="form1" /></td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
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