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


$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_ordenes_compra = 20;
$pageNum_ordenes_compra = 0;
if (isset($_GET['pageNum_ordenes_compra'])) {
  $pageNum_ordenes_compra = $_GET['pageNum_ordenes_compra'];
}
$startRow_ordenes_compra = $pageNum_ordenes_compra * $maxRows_ordenes_compra;

mysql_select_db($database_conexion1, $conexion1);
$query_ordenes_compra = "SELECT * FROM Tbl_items_ordenc ORDER BY id_items DESC";
$query_limit_ordenes_compra = sprintf("%s LIMIT %d, %d", $query_ordenes_compra, $startRow_ordenes_compra, $maxRows_ordenes_compra);
$ordenes_compra = mysql_query($query_limit_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);

if (isset($_GET['totalRows_ordenes_compra'])) {
  $totalRows_ordenes_compra = $_GET['totalRows_ordenes_compra'];
} else {
  $all_ordenes_compra = mysql_query($query_ordenes_compra);
  $totalRows_ordenes_compra = mysql_num_rows($all_ordenes_compra);
}
$totalPages_ordenes_compra = ceil($totalRows_ordenes_compra/$maxRows_ordenes_compra)-1;

$queryString_ordenes_compra = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ordenes_compra") == false && 
        stristr($param, "totalRows_ordenes_compra") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ordenes_compra = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ordenes_compra = sprintf("&totalRows_ordenes_compra=%d%s", $totalRows_ordenes_compra, $queryString_ordenes_compra);

session_start();
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body onload = "JavaScript: AutoRefresh (30000);">
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
  <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<table id="tabla1">
<tr>
<td colspan="5" id="titulo2">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" id="titulo2">ORDENES DE COMPRA PROGRAMADAS</td>
  </tr>
<tr>
  <td colspan="5" id="fuente2">&nbsp;</td>
</tr>
</table>

<table>
  <tr>
    <td colspan="4" id="dato1">Nota: <img src="images/p.gif" alt="O.C.PROGRAMADA"title="O.C. PROGRAMADA" border="0" style="cursor:hand;"/>si aparece la P es porque esta programada.</td>
    <td colspan="2" id="dato3"><a href="produccion_op_interna.php"><img src="images/mas_r.gif" alt="ADD O.P INTERNA" title="ADD O.P INTERNA" border="0" style="cursor:hand;"/></a><a href="produccion_ordenes_produccion_listado.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO O.P" title="LISTADO O.P" border="0" /></a>
 <a href="produccion_op_ordenconsultar.php"><img src="images/accept.png" style="cursor:hand;" alt="O.P FINALIZADAS" title="O.P FINALIZADAS" border="0" /></a>   
<a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    </tr>  
  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.C </td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">FECHA ENTREGA</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">RESPONSABLE</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td nowrap="nowrap"id="dato1"><strong><?php echo $row_ordenes_compra['str_numero_io']; ?></strong></td>
      <td id="dato2"><?php if($row_ordenes_compra['int_cod_ref_io']=='') {echo $row_ordenes_compra['id_mp_vta_io'];}else{echo $row_ordenes_compra['int_cod_ref_io'];} ?></td>
      <td id="dato2"><?php echo $row_ordenes_compra['fecha_entrega_io']; ?></td>
      <td nowrap="nowrap" id="dato2">
    <?php
	$codOC=$row_ordenes_compra['str_numero_io']; 
	$sqln="SELECT cliente.nombre_c, Tbl_orden_compra.id_c_oc,Tbl_orden_compra.str_responsable_oc FROM cliente,Tbl_orden_compra WHERE Tbl_orden_compra.str_numero_oc='$codOC' AND Tbl_orden_compra.id_c_oc=cliente.id_c "; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c');
	  $respond=mysql_result($resultn,0,'str_responsable_oc');  
	echo $nit_cliente_c; }
	else { echo "";	} ?>
      </td>
      <td nowrap="nowrap"id="dato1"><?php echo $respond; ?></td>
      <td id="dato2">      
	  <?php
	    $codOC=$row_ordenes_compra['str_numero_io']; 
		$codRef=$row_ordenes_compra['int_cod_ref_io'];
		$sqlpro="SELECT id_op FROM Tbl_orden_produccion WHERE str_numero_oc_op='$codOC' AND int_cod_ref_op='$codRef'";
		$resultpro=mysql_query($sqlpro); 
		$numpro=mysql_num_rows($resultpro);
		if($numpro >0 || ($row_ordenes_compra['b_estado_io']!='3' && $row_ordenes_compra['b_estado_io']!='0')){
/*			$ordenP=mysql_result($resultpro,0,'id_op');  
		?><a href="produccion_op_vista.php?id_op=<?php echo $ordenP;?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/p.gif" alt="O.C.PROGRAMADA"title="O.C. PROGRAMADA" border="0" style="cursor:hand;"/></a><?php	
	    }else{*/
        ?><img src="images/p.gif" alt="PROGRAMADA"title="PROGRAMADA" border="0" style="cursor:hand;"/><?php 
		}else {?><a href="produccion_op_add.php?str_numero_oc_op=<?php echo $row_ordenes_compra['str_numero_io']; ?>&int_cod_ref_op=<?php echo $row_ordenes_compra['int_cod_ref_io'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/mas.gif" alt="ADD O.P PROGRAMAR"title="ADD O.P PROGRAMAR" border="0" style="cursor:hand;"/></a><?php }?>
       </td>
    </tr>
    <?php } while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra)); ?>
</table>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, 0, $queryString_ordenes_compra); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, max(0, $pageNum_ordenes_compra - 1), $queryString_ordenes_compra); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, min($totalPages_ordenes_compra, $pageNum_ordenes_compra + 1), $queryString_ordenes_compra); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, $totalPages_ordenes_compra, $queryString_ordenes_compra); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td>
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

mysql_free_result($ordenes_compra);
?>