<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
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
  $updateSQL = sprintf("UPDATE TblExtruderRollo SET fechaV_r=%s WHERE id_r=%s",
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

$conexion = new ApptivaDB();

mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_rollo_estrusion = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_estrusion = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_estrusion = sprintf("SELECT * FROM TblExtruderRollo WHERE TblExtruderRollo.id_r=%s",$colname_rollo_estrusion);
$rollo_estrusion = mysql_query($query_rollo_estrusion, $conexion1) or die(mysql_error());
$row_rollo_estrusion = mysql_fetch_assoc($rollo_estrusion);
$totalRows_rollo_estrusion = mysql_num_rows($rollo_estrusion);

/*$colname_rp = "-1";
if (isset($_GET['id_r'])) {
  $colname_rp = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rp = sprintf("SELECT TblExtruderRollo.ref_r,Tbl_referencia.cod_ref,Tbl_referencia.id_ref FROM TblExtruderRollo,Tbl_referencia WHERE TblExtruderRollo.id_r=%s AND TblExtruderRollo.ref_r= Tbl_referencia.cod_ref",$colname_rp);
$rp_edit= mysql_query($query_rp, $conexion1) or die(mysql_error());
$row_rp_edit = mysql_fetch_assoc($rp_edit);
$totalRows_rp_edit = mysql_num_rows($rp_edit);*/
//EXISTE OP
$colname_existe = "-1";
if (isset($_GET['id_r'])) {
  $colname_existe = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_existe = sprintf("SELECT Tbl_reg_produccion.id_rp, Tbl_reg_produccion.rollo_rp, Tbl_reg_produccion.id_proceso_rp FROM TblExtruderRollo,Tbl_reg_produccion WHERE TblExtruderRollo.id_r=%s AND TblExtruderRollo.id_op_r = Tbl_reg_produccion.id_op_rp AND Tbl_reg_produccion.id_proceso_rp='1'",$colname_existe);// AND TblExtruderRollo.rollo_r=Tbl_reg_produccion.rollo_rp 
$existe_edit= mysql_query($query_existe, $conexion1) or die(mysql_error());
$row_existe_edit = mysql_fetch_assoc($existe_edit);
$totalRows_existe_edit = mysql_num_rows($existe_edit);
 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC & CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

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
<!--<div align="center" id="seleccion" >--><!--onClick="cerrar('seleccion');"-->
  <form action="<?php echo $editFormAction; ?>" method="POST" name="form1">
  <table id="tabla6" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan="2" nowrap="nowrap" id="fuentND" style="border-bottom: 3px solid #000000;"><img src="images/logoacyc.jpg" width="80" height="50"/><div class="marca-de-agua"></div></td>
    <td colspan="5" nowrap="nowrap" id="stikersC_titu_grande" style="border-left: 3px solid #000000;">MATERIALES EXTRUSION</td>
  </tr>
  <tr>
    <td colspan="2" nowrap="nowrap" id="stikersC_titu_grande" style="border-bottom: 3px solid #000000;border-left: 3px solid #000000;">ROLLO N&deg; <?php echo $row_rollo_estrusion['rollo_r']; ?></td>
    <td colspan="3" nowrap="nowrap" id="fuentND" style="border-bottom: 3px solid #000000;">
      <a href="produccion_extrusion_stiker_rollo_edit.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" /></a>
      <a href="produccion_extrusion_stiker_rollo_add.php?id_op_r=<?php echo $row_rollo_estrusion['id_op_r']; ?>"><img src="images/mas.gif" alt="ADD ROLLO"title="ADD ROLLO" border="0" style="cursor:hand;"/></a>
      <?php if($row_usuario['tipo_usuario']==1){?>
      <a href="produccion_extrusion_stiker_rollo_colas_vista.php?id_op_r=<?php echo $row_rollo_estrusion['id_op_r']; ?>"><img src="images/t.gif" alt="IMPRIME TODOS LOS ROLLOS" title="IMPRIME TODOS LOS ROLLOS" border="0" /></a>
      <?php } ?>
      <?php if($row_existe_edit['id_rp']=='' ):  ?>
           <a href="javascript:eliminar1('id_re',<?php echo $row_rollo_estrusion['id_r']; ?>,'produccion_extrusion_stiker_rollo_edit.php')"><img src="images/por.gif" alt="ELIMINAR" title="Eliminara todos los tiempos y desperdicios si tienen fecha de este rollo" border="0" style="cursor:hand;"/></a>
      <?php else: ?>
         <img src="images/por.gif" alt="ELIMINAR" title="no se puede Eliminar debido a que ya esta liquidado" border="0" style="cursor:hand;" onclick="liquidado()" /> 
      <?php endif; ?>
             
         <?php  //if($totalRows_existe_edit < '1') :?>
      
           <a href="produccion_registro_extrusion_add.php?id_op=<?php echo $row_rollo_estrusion['id_op_r']; ?>"><img src="images/adelante.gif" alt="LIQUIDAR"title="LIQUIDAR" border="0" style="cursor:hand;"/></a>

         <?php //endif; ?>
         <?php 
  
           $op_c=$row_rollo_estrusion['id_op_r'];
           $rollo_c=$row_rollo_estrusion['rollo_r'];
            


           $parcial2=$conexion->llenarCampos("tbl_reg_produccion cp","WHERE cp.id_op_rp='$op_c' AND cp.id_proceso_rp ='1' ", "ORDER BY parcial DESC","parcial"); 
 

           //$parcial2=$conexion->llenarCampos("tbl_reg_produccion cp","WHERE cp.id_op_rp='$op_c' AND cp.id_proceso_rp ='1' and rollo_rp=$rollo_c ", "ORDER BY parcial DESC","parcial");  

           /*$op_c=$row_rollo_estrusion['id_op_r'];
           $sqlparcial="SELECT parcial FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' ORDER BY parcial DESC"; 
           $resultparcial=mysql_query($sqlparcial); 
           $numparcial=mysql_num_rows($resultparcial); 
           echo'P1 '.$parcial = mysql_result($resultparcial, 0, 'parcial');


           $op_c=$row_rollo_estrusion['id_op_r'];
           $rollo_c=$row_rollo_estrusion['rollo_r'];
           $sqlparcial="SELECT parcial FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' and rollo_rp=$rollo_c ORDER BY parcial DESC"; 
           $resultparcial=mysql_query($sqlparcial); 
           $numparcial=mysql_num_rows($resultparcial); 

           echo'P2 '.$parcial2 = mysql_result($resultparcial, 0, 'parcial');*/
            
           if($parcial2['parcial'] > 1):?>

             <a href="produccion_registro_extrusion_vistap.php?id_op_rp=<?php echo $row_rollo_estrusion['id_op_r']; ?>&parcial=<?php echo $parcial2['parcial']; ?>"><img src="images/hojap.gif" alt="VISTA EXTRUDER PARCIAL" title="VISTA EXTRUDER PARCIAL" border="0" /></a>
           <?php  else:  ?>
             <a href="produccion_registro_extrusion_vista.php?id_op_rp=<?php echo $row_rollo_estrusion['id_op_r']; ?>"><img src="images/hoja.gif" alt="VISTA EXTRUDER" title="VISTA EXTRUDER" border="0" /></a>
           <?php  endif; ?>

            <a href="produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_rollo_estrusion['id_op_r']; ?>"><img src="images/opciones.gif" alt="LISTADO ROLLOS"title="LISTADO ROLLO" border="0" style="cursor:hand;"/></a><img src="images/impresor.gif" onClick="envio_form();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" />
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">ORDEN P:</td>
    <td nowrap="nowrap" id="stikers_fuentN">
	<input type="hidden" name="id_r" value="<?php echo $_GET['id_r']; ?>">
    <input type="hidden" name="fechaV_r" value="<?php echo fechaHora(); ?>">
	<?php echo $row_rollo_estrusion['id_op_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">REF:</td>
    <td colspan="3" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['ref_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">CLIENTE:</td>
    <td colspan="5" id="stikers_fuent2"><?php $id_c=$row_rollo_estrusion['id_c_r']; 
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
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['tratInter_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">TRAT. EXT:</td>
    <td colspan="3" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['tratExt_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">PIGM. INT:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['pigmInt_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">PIGM. EXT:</td>
    <td colspan="3" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['pigmExt_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">PRESENTA:</td>
    <td id="stikers_fuentN"><?php echo $row_rollo_estrusion['presentacion_r']; ?></td>
    <td id="stikersC_fuentN">CAL. MILS:</td>
    <td colspan="3" id="stikers_fuentN"><?php echo $row_rollo_estrusion['calibre_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">FECHA INI:</td>
    <td nowrap="nowrap" id="stikers_fuent2"><?php echo $row_rollo_estrusion['fechaI_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">COD. OPE:</td>
    <td colspan="3" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['cod_empleado_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">PESO:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['kilos_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN">TURNO:</td>
    <td colspan="3" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['turno_r']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;"> METROS: </td>
    <td nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php echo $row_rollo_estrusion['metro_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-bottom: 3px solid #000000;"> ANCH R:      </td>
    <td colspan="3" nowrap="nowrap" id="stikers_fuentN" style="border-bottom: 3px solid #000000;"><?php 
	 $op_id=$row_rollo_estrusion['id_op_r'];
	 $sqlev="SELECT int_ancho_rollo_op FROM Tbl_orden_produccion WHERE id_op='$op_id'";
	 $resultev= mysql_query($sqlev);
	 $numev= mysql_num_rows($resultev);
	 if($numev >='1')
	 {
	 echo $ancho_rollo = mysql_result($resultev, 0, 'int_ancho_rollo_op');
	 }
	 ?></td>
    </tr>
  
    
  <!--<tr>
    <td colspan="6" nowrap="nowrap" id="stikersC_titu_grande" style="border-bottom: 3px solid #000000;">DEFECTOS BANDERAS</td>
  </tr>-->
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">Reventon:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['reven_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">Medida:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['medid_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">Pigmentación:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['calib_r']; ?></td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">Cort/Hue:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['corte_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">Descalibre:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['desca_r']; ?></td>
    <td id="stikersC_fuentN" style="border-left: 3px solid #000000;">Tratamien:</td>
    <td id="stikers_fuentN" ><?php echo $row_rollo_estrusion['trata_r']; ?></td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">Arrugas:</td>
    <td nowrap="nowrap" id="stikers_fuentN" ><?php echo $row_rollo_estrusion['arrug_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN"style="border-left: 3px solid #000000;">Apagón:</td>
    <td nowrap="nowrap" id="stikers_fuentN" ><?php echo $row_rollo_estrusion['apagon_r']; ?></td>
    <td id="stikersC_fuentN" style="border-left: 3px solid #000000;">&nbsp;</td>  
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">Montaje:</td>
    <td nowrap="nowrap" id="stikers_fuentN" ><?php echo $row_rollo_estrusion['montaje_r']; ?></td>
    <td nowrap="nowrap" id="stikersC_fuentN" style="border-left: 3px solid #000000;">TOTAL B:</td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['bandera_r']; ?></td>
    <td id="stikersC_fuentN" style="border-left: 3px solid #000000;">&nbsp;</td>
  </tr>
 <!-- <tr>
    <td nowrap="nowrap" id="stikersC_fuentN">OBSERV:</td>
    <td colspan="5" nowrap="nowrap" id="stikers_fuentN"><?php echo $row_rollo_estrusion['observ_r']; ?></td>
    </tr>-->
  </table>
      <input type="hidden" name="MM_update" value="form1">
       </form>
<!--</div>-->
<!--<div id="oculto">
<table width="200" border="0" align="center">
  <tr>
    <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');return false" ></td>
  </tr>
</table>
</div>-->
</body>
</html>
<script type="text/javascript">
  
  
  function liquidado(){ 
      swal("El Rollo no se puede eliminar porque ya esta liquidado!")
  }

</script>
<?php

mysql_free_result($usuario);

mysql_free_result($rollo_estrusion);


?>
