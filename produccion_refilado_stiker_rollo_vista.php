<?php require_once('Connections/conexion1.php'); ?><?php
if (!isset($_SESSION)) {
  session_start();
}
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE TblRefiladoRollo SET fechaV_r=%s WHERE id_r=%s",
					   GetSQLValueString($_POST['fechaV_r'], "date"),
                       GetSQLValueString($_POST['id_r'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

echo "<script> window.print();</script> " ; 
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

//ORDENES DE PRODUCCION
$colname_ordenP = "-1";
if (isset($_GET['id_r'])) {
  $colname_ordenP = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ordenP = sprintf("SELECT id_ref_op FROM Tbl_orden_produccion ORDER BY Tbl_orden_produccion.id_op DESC",$colname_ordenP);
$ordenP = mysql_query($query_ordenP, $conexion1) or die(mysql_error());
$row_ordenP = mysql_fetch_assoc($ordenP);
$totalRows_ordenP = mysql_num_rows($ordenP);

$colname_rollo_refilado = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_refilado = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_refilado = sprintf("SELECT * FROM TblRefiladoRollo WHERE TblRefiladoRollo.id_r=%s",$colname_rollo_refilado);
$rollo_refilado = mysql_query($query_rollo_refilado, $conexion1) or die(mysql_error());
$row_rollo_refilado = mysql_fetch_assoc($rollo_refilado);
$totalRows_rollo_refilado = mysql_num_rows($rollo_refilado);

 
session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC & CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>    
<script type="text/javascript" src="js/jquery-barcode-last.min.js"></script> 
<script> 
function envio_form(){ 
   document.form1.submit() 
} 
</script>
<!--IMPRIME CODIGO DE BARRAS-->
<script type="text/javascript">
$(document).ready(function(){
	var codigo="<?php $var=$row_vista_paquete['int_op_tn']."-".$row_vista_paquete['int_caja_tn'];echo $var;?>";
	var codigo2="770-771-1-<?php echo $row_refac['int_cod_ref_op']; ?>-1";
    $("#bcTarget").barcode(codigo2, "code128",{barWidth:1, barHeight:20});
	//$("#bcTarget").barcode("1234567", "int25"); 
});
</script>
<!--IMPRIME AL CARGAR POPUP-->
<SCRIPT language="javascript"> 
function imprimir()
{ if ((navigator.appName == "Netscape")) { window.print() ; 
} 
else
{ var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, -1); WebBrowser1.outerHTML = "";
}
}
</SCRIPT>
<style type="text/css">

 #oculto {
  display:none;
 
}
</style> 
<script>
function cerrar(num) {
    window.close()
}
</script>
</head>
<body>
<div align="center" id="seleccion"  ><!--onClick="cerrar('seleccion');"-->
  <form action="<?php echo $editFormAction; ?>" method="POST" name="form1">
  <table id="tabla6" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan="2" nowrap="nowrap" id="fuentND" style="border-bottom: 3px solid #000000;"><img src="images/logoacyc.jpg" width="106" height="63"/></td>
    <td colspan="5" nowrap="nowrap" id="stikersC_titu_grande" style="border-left: 3px solid #000000;">MATERIALES REFILADO</td>
  </tr>
  <tr>
    <td colspan="3" nowrap="nowrap" id="stikersC_titu_grande" style="border-bottom: 3px solid #000000;border-left: 3px solid #000000;">ROLLO N&deg; <?php echo $row_rollo_refilado['rollo_r']; ?></td>
    <td colspan="2" nowrap="nowrap" id="fuentND" style="border-bottom: 3px solid #000000;"><a href="produccion_refilado_stiker_rollo_edit.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" /></a>
      <?php if($row_usuario['tipo_usuario']==1){?>
      <a href="produccion_refilado_stiker_rollo_colas_vista.php?id_op_r=<?php echo $row_rollo_refilado['id_op_r']; ?>"><img src="images/t.gif" alt="refilado TODOS LOS ROLLOS" title="refilado TODOS LOS ROLLOS" border="0" /></a>
      <?php }?>
     <a href="produccion_refilado_listado_rollos.php?id_op_r=<?php echo $row_rollo_refilado['id_op_r']; ?>&rollo_r=<?php echo $row_rollo_refilado['rollo_r']; ?>&rollo_r=<?php echo $row_rollo_refilado['rollo_r']; ?>"><img src="images/opciones.gif" alt="LISTADO ROLLOS"title="LISTADO ROLLO" border="0" style="cursor:hand;"/></a>
     <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
            <img src="images/impresor.gif" onClick="envio_form();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR"/></td>
  </tr>

  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">ORDEN P:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN">
    <input type="hidden" name="id_r" value="<?php echo $_GET['id_r']; ?>">
    <input type="hidden" name="fechaV_r" value="<?php echo fechaHora(); ?>">
	<?php echo $row_rollo_refilado['id_op_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">REF:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['ref_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">CLIENTE:</td>
    <td colspan="5" id="stikers_fuentN"><?php $id_c=$row_rollo_refilado['id_c_r']; 
            $sqln="SELECT id_c,nombre_c FROM cliente WHERE id_c='$id_c'"; 
            $resultn=mysql_query($sqln); 
            $numn=mysql_num_rows($resultn); 
            if($numn >= '1') 
            {$nombre_c=mysql_result($resultn,0,'nombre_c'); 
			$cadenaN = htmlentities($nombre_c); echo $cadenaN; 
			} ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">TRAT. INT:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['tratInter_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">TRAT. EXT:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['tratExt_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">FECHA INI:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['fechaI_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">PRESENTA:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['presentacion_r']; ?></td>
  </tr>
  <tr>
    <td height="57" nowrap="nowrap" id="stikersC_fuentN">FECHA FIN:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['fechaF_r']; ?></td>
    <td id="stikersC_fuentN">CAL. MILS:</td>
    <td colspan="2" id="stikers_fuentN"><?php echo $row_rollo_refilado['calibre_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">COD. OPE:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['cod_empleado_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">AUXILIAR:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['cod_auxiliar_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">FECHA V:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo fechaHora(); ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">TURNO:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['turno_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;">METROS:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_refilado['metro_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;">PESO:</td>
    <td colspan="2" nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_refilado['kilos_r']; ?></td>
  </tr>
  <!--<tr>
    <td colspan="6" nowrap="nowrap" id="stikersC_titu_grande" style="border-bottom: 3px solid #000000;">DEFECTOS BANDERAS</td>
  </tr>-->
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">EXTRUSION:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['extru_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">REFILADO:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['impre_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">&nbsp;</td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">REFILADO:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['refil_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">&nbsp;</td>
    <td nowrap="nowrap" id="stikers_fuentN">&nbsp;</td>
    <td id="stikersC_fuentN" style="border-left: 3px solid #000000;">&nbsp;</td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">OBSERV:</td>
    <td colspan="5" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_refilado['observ_r']; ?></td>
  </tr>
  </table>
    <input type="hidden" name="MM_update" value="form1">
       </form>
</div>
<!--<div id="oculto">
<table width="200" border="0" align="center">
  <tr>
    <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');return false" ></td>
  </tr>
</table>
</div>-->
</body>
</html>
<?php

mysql_free_result($usuario);

mysql_free_result($rollo_refilado);

mysql_free_result($ordenP);

mysql_free_result($existe_edit);
?>
