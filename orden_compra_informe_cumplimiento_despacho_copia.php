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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

$maxRows_numeracion = 40;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
//Filtra todos vacios
if($id_op== '')
{
$query_numeracion = "SELECT * FROM Tbl_items_ordenc ORDER BY fecha_entrega_io DESC";
}

//$query_limit_numeracion = sprintf("%s LIMIT %d, %d", $query_numeracion, $startRow_numeracion, $maxRows_numeracion);
$numeracion = mysql_query($query_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);

if (isset($_GET['totalRows_numeracion'])) {
  $totalRows_numeracion = $_GET['totalRows_numeracion'];
} else {
  $all_numeracion = mysql_query($query_numeracion);
  $totalRows_numeracion = mysql_num_rows($all_numeracion);
}
$totalPages_numeracion = ceil($totalRows_numeracion/$maxRows_numeracion)-1;

$queryString_numeracion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_numeracion") == false && 
        stristr($param, "totalRows_numeracion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_numeracion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_numeracion = sprintf("&totalRows_numeracion=%d%s", $totalRows_numeracion, $queryString_numeracion);
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body><div align="center"> 
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
<td colspan="2" id="titulo2">CUMPLIMIENTO DE DESPACHO POR ORDENES DE COMPRA </td>
</tr>
<tr>
  <td id="titulo2">&nbsp;</td>
  <td id="dato3"><input type="button" value="Exporta a Excel" onClick="window.location = 'orden_compra_informe_cumplimiento_despacho_excel.php'" /></td>
  </tr>
<tr>
  <td id="fuente1"><strong>NOTA:</strong></td>
  <td id="fuente1"><p>En la columna 'Cumple' la informacion:</p>
    <p>S.D = Sin Despachar</p>
    <p>N.R = No hay Registro</p>
    <p>SI = Si Cumple</p>
    <p>NO = No Cumple</p></td>
  </tr>
</table>
<div style="height:400px;width:790px;overflow:scroll;">
<table id="Exportar_a_Excel">   
  <tr id="tr1">
    <td id="titulo4"></td>
    <td nowrap="nowrap"id="titulo4">N&deg; O.C</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">FECHA ENTREGA O.C</td>     
    <td nowrap="nowrap"id="titulo4">FECHA DESPACHO</td>
    <td nowrap="nowrap"id="titulo4">CUMPLE</td>
    <td nowrap="nowrap"id="titulo1">&nbsp;</td>                
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
  <td nowrap="nowrap" id="dato2">&nbsp;</td>
      <td nowrap="nowrap" id="dato1"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_io'];?>&id_oc=<?php echo $row_numeracion['int_codd_ref_io'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['str_numero_io']; ?></a></td>
      <td nowrap="nowrap" id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_io'];?>&id_oc=<?php echo $row_numeracion['int_codd_ref_io'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
	$id_c=$row_numeracion['id_c_oc'];
	$sqln="SELECT nombre_c FROM cliente WHERE cliente.id_c='$id_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
	else { echo "";	} ?>
    </a></td>      
      <td nowrap="nowrap" id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
	  <?php 
/*	  $id_fe=$row_numeracion['id_pedido'];
      $sqlfe="SELECT fecha_entrega_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_fe' ORDER BY `fecha_entrega_io` DESC LIMIT 1"; 
	  $resultfe=mysql_query($sqlfe); 
	  $numfe=mysql_num_rows($resultfe); 
	  if($numfe >= '1') 
	  { $fechafe=mysql_result($resultfe,0,'fecha_entrega_io'); echo $fechafe;
	  }else{echo "N.R";}*/
	  ?>
      <?php echo $row_numeracion['fecha_entrega_io']; ?>
      </a></td>
      <td nowrap="nowrap" id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
	  <?php 
/*	  $id_io=$row_numeracion['id_pedido'];
      $sqlio="SELECT fecha_despacho_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_io' ORDER BY `fecha_despacho_io` DESC LIMIT 1"; 
	  $resultio=mysql_query($sqlio); 
	  $numio=mysql_num_rows($resultio); 
	  if($numio >= '1') 
	  { $fechaio=mysql_result($resultio,0,'fecha_despacho_io'); echo $fechaio;
	  }else{echo "S.D";}*/
	  ?>
      <?php if( $row_numeracion['fecha_despacho_io']==''){echo "S.D";}else{echo $row_numeracion['fecha_despacho_io'];} ?>
      </a></td> 
      <td nowrap="nowrap" id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">	  
        <?php 
/*	  $id_oc=$row_numeracion['id_pedido'];
	  $estado=$row_numeracion['b_estado_oc'];
      $sqloc="SELECT fecha_entrega_io,fecha_despacho_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_oc' AND fecha_entrega_io >= fecha_despacho_io  ORDER BY `fecha_despacho_io` DESC LIMIT 1"; 
	  $resultoc=mysql_query($sqloc); 
	  $numoc=mysql_num_rows($resultoc); */
	  if( $row_numeracion['fecha_despacho_io']!=''){
	  if($row_numeracion['fecha_despacho_io']<$row_numeracion['fecha_entrega_io']|| $row_numeracion['fecha_despacho_io']==$row_numeracion['fecha_entrega_io']) {
		  
	  
	  
       echo "SI";
	  }else{
	  echo "NO";   
	  }
	  }
      ?></a>
      </td>     
      <td nowrap="nowrap" id="titulo1">&nbsp;</td> 
    </tr>
    <?php } while ($row_numeracion = mysql_fetch_assoc($numeracion)); ?>
</table>
</div>
</td>
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

mysql_free_result($numeracion);

?>