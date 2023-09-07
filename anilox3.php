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

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM anilox WHERE estado_insumo ='0' ORDER BY descripcion_insumo ASC";
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body><div align="center">
<table id="tabla3">
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
      <td width="4%" class="Estilo3"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['codigo_insumo']; ?></a></td>
      <td width="35%" class="texto"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['descripcion_insumo']; ?></a></td>
<td width="13%" class="Estilo3"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 
    $id_insumo=$row_insumos['id_insumo'];
	$query_proveedores="SELECT proveedor.proveedor_p FROM TblProveedorInsumo, proveedor WHERE TblProveedorInsumo.id_in=$id_insumo AND TblProveedorInsumo.id_p=proveedor.id_p ORDER BY proveedor.proveedor_p ASC";
   $proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
   $row_proveedores = mysql_fetch_assoc($proveedores);
   $totalRows_proveedores = mysql_num_rows($proveedores);
    do { 
    echo $row_proveedores['proveedor_p']. "<BR>";
    } while ($row_proveedores = mysql_fetch_assoc($proveedores));       
    ?></a></td>       
       <td width="13%" class="Estilo3"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $clase_insumo=$row_insumos['clase_insumo'];
	  $sqlclase="SELECT * FROM clase WHERE id_clase='$clase_insumo'";
	  $resultclase= mysql_query($sqlclase);
	  $numclase= mysql_num_rows($resultclase);
	  if($numclase >='1')
	  { 
	  $clase = mysql_result($resultclase, 0, 'nombre_clase'); 	  
	  if($clase != '')  { echo $clase; } } ?></a></td>
      <td width="13%" class="Estilo3"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $medida_insumo=$row_insumos['medida_insumo'];
	$sqlmedida="SELECT * FROM medida WHERE id_medida = $medida_insumo";
	$resultmedida= mysql_query($sqlmedida);
	$numedida= mysql_num_rows($resultmedida);
	if($numedida >='1') { $medida_insumo=mysql_result($resultmedida,0,'nombre_medida'); }
	echo $medida_insumo; ?></a></td>
      <td width="13%" class="Estilo3"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $tipo_insumo=$row_insumos['tipo_insumo'];
	$sqltipo="SELECT * FROM tipo WHERE id_tipo = $tipo_insumo";
	$resultipo= mysql_query($sqltipo);
	$numtipo= mysql_num_rows($resultipo);
	if($numtipo >='1') { $tipo_insumo=mysql_result($resultipo,0,'nombre_tipo'); }
	echo $tipo_insumo;
	 ?></a></td>
      <td width="4%" class="derecha1"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo number_format($row_insumos['valor_unitario_insumo'], 2,",", "."); ?></a></td>
      <td width="5%" class="derecha1"><a href="anilox_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['stok_insumo']; ?></a></td>
    </tr>
    <?php } while ($row_insumos = mysql_fetch_assoc($insumos)); ?>
</table></div>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($insumos);
?>
