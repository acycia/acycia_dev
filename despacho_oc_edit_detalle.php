<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once("db/db.php");
require_once 'Models/Mremision.php';

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
//BASES DE DATOS
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

//REMISIONES
$colname_remision_detalle = "-1";
if (isset($_GET['id_rd'])) {
  $colname_remision_detalle = (get_magic_quotes_gpc()) ? $_GET['id_rd'] : addslashes($_GET['id_rd']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision_detalle = sprintf("SELECT * FROM Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remision_detalle.id_rd = %s AND Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC ", $colname_remision_detalle);
$remision_detalle = mysql_query($query_remision_detalle, $conexion1) or die(mysql_error());
$row_remision_detalle = mysql_fetch_assoc($remision_detalle);
$totalRows_remision_detalle = mysql_num_rows($remision_detalle);
//FIN

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	//ACTUALIZA TODOS LOS ITEMS QUE TENGAN ESTADO PENDIENTE DE DESPACHO Y TENGAN LA FECHA DE INGRESO MAYOR A UN MES, SEGUN FECHA ACTUAL
    $updateFecha = sprintf("UPDATE `Tbl_remision_detalle` SET `fecha_rd` = CURDATE( ) WHERE TIMESTAMPDIFF( MONTH , `fecha_rd` , CURDATE( )) <= '1' AND  `estado_rd` = '1'",
					   GetSQLValueString($_POST['fecha_rd'], "text")
					   );
  mysql_select_db($database_conexion1, $conexion1);
  $ResultFecha = mysql_query($updateFecha, $conexion1) or die(mysql_error()); 
  
  $updateSQL = sprintf("UPDATE Tbl_items_ordenc SET b_estado_io=%s WHERE id_items=%s",
					   GetSQLValueString($_POST['b_estado_io'], "int"),
					   GetSQLValueString($_POST['id_items'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error()); 
  
  $updateSQL2 = sprintf("UPDATE Tbl_remision_detalle SET fecha_rd=%s, estado_rd=%s WHERE id_rd=%s",
                       GetSQLValueString($_POST['fecha_rd'], "text"),
					   GetSQLValueString($_POST['estado_rd'], "int"),
					   GetSQLValueString($_POST['id_rd'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error()); 


  //GUARDADO DE HISTORICOS
  $myObject = new oRemision();
  $historico =  new oRemision();

  if(isset($_POST['id_rd'])){ 
    $historico=$myObject->Obtener('tbl_remision_detalle','id_rd', " '".$_POST['id_rd']."'  " );
  }  

  if(isset($_POST['id_rd']) && $historico){
    $myObject->RegistrarItems("tbl_remision_detalle_historico", "int_remision_r_rd,str_numero_oc_rd, fecha_rd, int_item_io_rd,int_caja_rd,int_mp_io_rd,int_ref_io_rd,str_ref_cl_io_rd,int_numd_rd,int_numh_rd,int_cant_rd,int_peso_rd,int_pesoneto_rd,int_total_cajas_rd,int_tolerancia_rd,str_direccion_desp_rd,estado_rd,modifico", $historico);
  }//FIN HISTORICO
  
//SI ES PARCIAL TOTAL O DESPACHADO  b_estado_io  Y estado_rd
$estado_io=$_POST["b_estado_io"];
$estado_rd = $_POST["estado_rd"];
if($estado_io > '3' || $estado_rd=='0')
 {  
   $updateINV6 = sprintf("UPDATE TblInventarioListado SET Salida=Salida + %s WHERE Cod_ref = %s",
					   GetSQLValueString($_POST['int_cantidad_rd'], "text"),
                       GetSQLValueString($_POST['ref_inven'], "text"));				   mysql_select_db($database_conexion1, $conexion1);
  $Result6 = mysql_query($updateINV6, $conexion1) or die(mysql_error()); 
 } 
 //FIN IF DE ESTADOS PARA INVENTARIO 
  
echo "<script type=\"text/javascript\">window.opener.location.reload();self.close();</script>"; 
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table id="tabla2">
          <tr>
            <td colspan="4" id="subtitulo">EDITAR EL ESTADO DEL ITEM </td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">REMISION  N&deg; <strong><?php echo $row_remision_detalle['int_remision_r_rd']; ?></strong>
            <input name="id_items" type="hidden" value="<?php echo $row_remision_detalle['id_items']; ?>">
            <input name="id_rd" type="hidden" value="<?php echo $_GET['id_rd']; ?>">

             <input type="hidden" name="ref_inven" id="ref_inven" value="<?php echo $row_remision_detalle['int_cod_ref_io'];?>"/></td>
            <td colspan="2" id="fuente1"><strong>Fecha:</strong>
              <input name="fecha_rd" type="date" id="fecha_rd" value="<?php echo $row_remision_detalle['fecha_rd']; ?>" size="10" readonly/></td>            
            </tr>
          <tr>
            <td colspan="4" id="dato1">            
            </td>
          </tr>
          <tr>
            <td colspan="4" id="dato1"></td>
          </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato1"><strong>Total Cajas</strong>:
            <input name="int_total_cajas_rd" type="number" id="int_total_cajas_rd" readonly required title="Ingrese cajas"  style="width:60px"value="<?php echo $row_remision_detalle['int_total_cajas_rd']; ?>"></td>
            <td id="dato1"><strong>Tolerancia % </strong>
              <input name="int_tolerancia_rd" type="number" id="int_tolerancia_rd" readonly required title="Ingrese tolerancia" max="50" style="width:40px" value="<?php echo $row_remision_detalle['int_tolerancia_rd']; ?>"></td>
            <td id="dato1"><strong>Facturar:</strong>
              <select name="b_estado_io" id="opciones" >
                <option value="3"<?php if (!(strcmp(3, $row_remision_detalle['b_estado_io']))) {echo "selected=\"selected\"";} ?>>REMISIONADA</option>
                <option value="4"<?php if (!(strcmp(4, $row_remision_detalle['b_estado_io']))) {echo "selected=\"selected\"";} ?>>FACTURADA PARCIAL</option>               
                <option value="5"<?php if (!(strcmp(5, $row_remision_detalle['b_estado_io']))) {echo "selected=\"selected\"";} ?>>FACTURADA TOTAL</option>
                <option value="6"<?php if (!(strcmp(6, $row_remision_detalle['b_estado_io']))) {echo "selected=\"selected\"";} ?>>MUESTRAS REPOSICION</option> 
              </select>
              <strong>ESTADO:</strong>
              <select name="estado_rd" id="estado_rd" >
                <option value="0"<?php if (!(strcmp(0, $row_remision_detalle['estado_rd']))) {echo "selected=\"selected\"";} ?> selected>Despachado</option>
                <option value="1"<?php if (!(strcmp(1, $row_remision_detalle['estado_rd']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
              </select></td>
            <td id="dato1">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="4" id="dato2"></td>
          </tr>                    
          <tr>
            <td colspan="4" id="dato2">&nbsp;</tr>          
<?php if (($row_remision_detalle['id_rd']!='')) { ?>
          <tr id="tr2">
            <td colspan="4" id="dato2"><table id="tabla1">
              <tr>
                <td id="nivel2">ITEM</td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. MP</td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">CANTIDADES</td>
                <td id="nivel2">RANGOS</td>
                <td id="nivel2">NUM. DESDE</td>
                <td id="nivel2">NUM. HASTA</td>
                <td id="nivel2">PESO</td>  
                <td id="nivel2">PESO/NETO</td>
                <td nowrap="nowrap"id="nivel2">FACTURADO</td>                
                </tr>
              <?php do { ?>
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
                  <td id="talla2"><?php echo $row_remision_detalle['int_consecutivo_io']; ?></td>
                  <td id="talla2"><?php echo $row_remision_detalle['int_ref_io_rd']; ?></td>
                  
 <td id="talla1">                 
<?php $mp=$row_remision_detalle['id_mp_vta_io'];
		if($mp!='')
		{
		$sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
		$resultmp= mysql_query($sqlmp);
		$nump= mysql_num_rows($resultmp);
		if($nump >='1')
		{ 
		$nombre_mp = mysql_result($resultmp,0,'str_nombre');
		;
		} } ?><?php echo $nombre_mp ?></td>
        <td id="talla2"><?php echo $row_remision_detalle['int_cod_cliente_io']; ?></td>                  
                  <td id="talla2"><input name="int_cantidad_rd" id="int_cantidad_rd" type="hidden" value="<?php echo $row_remision_detalle['int_cant_rd']; ?>">
                    <?php echo $row_remision_detalle['int_cant_rd']; ?></td>
                  <td id="talla2"><?php echo $row_remision_detalle['int_caja_rd'];$cajas=$cajas+$row_remision_detalle['int_total_cajas_rd']; ?></td>
                  <td id="talla2"><?php echo $row_remision_detalle['int_numd_rd']; ?></td>
                  <td id="talla2"><?php echo $row_remision_detalle['int_numh_rd'];$total=$subtotal+$row_remision_detalle['int_total_item_io'];?></td>
                  <td id="talla2"><?php echo $row_remision_detalle['int_peso_rd']; $peso=$peso+$row_remision_detalle['int_peso_rd']; ?></td>
                  <td id="talla2"><?php echo $row_remision_detalle['int_pesoneto_rd'];$peson= $peson+$row_remision_detalle['int_pesoneto_rd']; ?></td>
                  
                  <td nowrap="nowrap" id="talla2"><?php if($row_remision_detalle['b_estado_io']=='5'){echo "Facturado Total";}else if($row_remision_detalle['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_remision_detalle['b_estado_io']=='1'){echo "Ingresado";}else if($row_remision_detalle['b_estado_io']=='2'){echo "Programado";}else if($row_remision_detalle['b_estado_io']=='3'){echo "Remisionado";}else if($row_remision_detalle['b_estado_io']=='6'){echo "Muestras reposicion";}?></td>
                </tr>
                <?php } while ($row_remision_detalle = mysql_fetch_assoc($remision_detalle)); ?>

            </table></td>
            </tr><?php } ?>         
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="EDITAR ESTADO REF" onChange="if(form1.b_despacho_io.value=='0' && form1.b_estado_io.value=='0') { alert('DEBE SELECCIONAR UNA OPCION');}">
              <!--<img src="images/rf.gif" width="31" height="18" onClick="javascript:submit();window.opener.location.reload();window.close();">--></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
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

mysql_free_result($remision_detalle);
?>