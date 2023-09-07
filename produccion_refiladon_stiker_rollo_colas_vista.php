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

$colname_rollo_impresion = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_rollo_impresion = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_impresion = sprintf("SELECT * FROM TblRefiladoRollo WHERE TblRefiladoRollo.id_op_r=%s",$colname_rollo_impresion);
$rollo_impresion = mysql_query($query_rollo_impresion, $conexion1) or die(mysql_error());
$row_rollo_impresion = mysql_fetch_assoc($rollo_impresion);
$totalRows_rollo_impresion = mysql_num_rows($rollo_impresion);
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
<?php do {?>
<div align="center" id="seleccion" ><!--onClick="cerrar('seleccion');"-->
  <table id="tabla6" cellspacing="0" cellpadding="0">

      <tr>
        <td rowspan="2" nowrap="nowrap" id="fuentND" style="border-bottom: 3px solid #000000;"><img src="images/logoacyc.jpg" width="106" height="63"/></td>
        <td colspan="5" nowrap="nowrap" id="stikersC_titu_grande" style="border-left: 3px solid #000000;">MATERIALES IMPRESION</td>
        </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="stikersC_titu_grande" style="border-bottom: 3px solid #000000;border-left: 3px solid #000000;">ROLLO N&deg; <?php echo $row_rollo_impresion['rollo_r']; ?></td>
        <td colspan="2" nowrap="nowrap" id="fuentND" style="border-bottom: 3px solid #000000;"><a href="produccion_impresion_stiker_rollo_edit.php?id_r=<?php echo $row_rollo_impresion['id_r']; ?>&rollo_r=<?php echo $row_rollo_impresion['rollo_r']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" /></a><a href="produccion_impresion_stiker_rollo_add.php?id_op_r=<?php echo $row_rollo_impresion['id_op_r']; ?>&rollo_r=<?php echo $row_rollo_impresion['rollo_r']; ?>"><img src="images/mas.gif" alt="ADD ROLLO"title="ADD ROLLO" border="0" style="cursor:hand;"/></a><a href="produccion_impresion_listado_rollos.php?id_op_r=<?php echo $row_rollo_impresion['id_op_r']; ?>&rollo_r=<?php echo $row_rollo_impresion['rollo_r']; ?>"><img src="images/opciones.gif" alt="LISTADO ROLLOS"title="LISTADO ROLLO" border="0" style="cursor:hand;"/></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /></td>
        </tr>
       <tr>
        <td nowrap="nowrap" id="stikersC_fuentN">ORDEN P:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['id_op_r']; ?></td>
        <td nowrap="nowrap" id="stikersC_fuentN">REF:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['ref_r']; ?></td>
       </tr>
      <tr>
        <td nowrap="nowrap" id="stikersC_fuentN">CLIENTE:</td>
        <td colspan="5" id="stikers_fuentN"><?php $id_c=$row_rollo_impresion['id_c_r']; 
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
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['tratInter_r']; ?></td>
        <td nowrap="nowrap" id="stikersC_fuentN">TRAT. EXT:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['tratExt_r']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="stikersC_fuentN">FECHA INI:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['fechaI_r']; ?></td>
        <td nowrap="nowrap" id="stikersC_fuentN">PRESENTA:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['presentacion_r']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="stikersC_fuentN">FECHA FIN:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['fechaF_r']; ?></td>
        <td id="stikersC_fuentN">CAL. MILS:</td>
        <td colspan="2" id="stikers_fuentN"><?php echo $row_rollo_impresion['calibre_r']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="stikersC_fuentN">COD. OPE:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['cod_empleado_r']; ?></td>
        <td nowrap="nowrap" id="stikersC_fuentN">AUXILIAR:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['cod_auxiliar_r']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="stikersC_fuentN">FECHA V:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['fechaV_r']; ?></td>
        <td nowrap="nowrap" id="stikersC_fuentN">TURNO:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['turno_r']; ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;">METROS:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_impresion['metro_r']; ?></td>
        <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;">PESO:</td>
        <td colspan="2" nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_impresion['kilos_r']; ?></td>
      </tr>
       <!--<tr>
        <td colspan="6" nowrap="nowrap" id="stikersC_titu_grande" style="border-bottom: 3px solid #000000;">DEFECTOS BANDERAS</td>
      </tr>-->      
      <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">Desregistro:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['desf_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">Tanteo:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['tante_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style=" border-left: 3px solid #000000;">Color:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><span style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_impresion['color_r']; ?></span></td>
      </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">Manchas:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['manch_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">Empates:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['empat_r']; ?></td>
    <td id="stikersC_fuentN" style=" border-left: 3px solid #000000;">Medida:</td>
    <td id="stikers_fuentN" ><?php echo $row_rollo_impresion['medid_r']; ?></td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;">Rasquetas:</td>
    <td nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_impresion['rasqueta_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;border-bottom: 3px solid #000000;">&nbsp;</td>
    <td nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;">&nbsp;</td>
    <td id="stikersC_fuentN" style="border-bottom: 3px solid #000000;border-left: 3px solid #000000;">TOTAL:</td>
    <td id="stikers_fuentN" style="border-bottom: 3px solid #000000;" ><?php echo $row_rollo_impresion['bandera_r']; ?></td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">OBSERV:</td>
    <td colspan="5" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_impresion['observ_r']; ?></td>
    </tr>      
    
  </table>
</div>
<!--<div id="oculto">
<table width="200" border="0" align="center">
  <tr>
    <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');return false" ></td>
  </tr>
</table>
</div>-->
 <?php } while ($row_rollo_impresion = mysql_fetch_assoc($rollo_impresion)); ?>
</body>
</html>
<?php

mysql_free_result($usuario);

mysql_free_result($rollo_cola);
?>
