<?php require_once('Connections/conexion1.php'); ?>
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
$query_rollos = "SELECT * FROM materia_prima_rollos ORDER BY nombre_rollo ASC";
$rollos = mysql_query($query_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);
$totalRows_rollos = mysql_num_rows($rollos);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table id="tabla3">
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
        <td class="Estilo4"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['cod_rollo']; ?></a></td>
      <td class="Estilo3"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['nombre_rollo']; ?></a></td>
      <td class="centrado6"><a href="referencia_vista.php?id_ref=<?php echo $row_rollos['ref_prod_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $ref=$row_rollos['ref_prod_rollo'];
	if($ref!='') { 
	 $sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$ref'";
	  $resultref= mysql_query($sqlref);
	  $numref= mysql_num_rows($resultref);
	  if($numref >='1')
	  { 
	  $referencia = mysql_result($resultref, 0, 'cod_ref');
	  echo $referencia;
	  } } ?></a></td>
      <td class="centrado3"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['presentacion_rollo']; ?></a></td>
      <td class="centrado4"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $tipo=$row_rollos['tipo_rollo']; 
	  if($tipo!='') { 
	  $sqltipo="SELECT * FROM tipo WHERE id_tipo='$tipo'";
	  $resultipo= mysql_query($sqltipo);
	  $numtipo= mysql_num_rows($resultipo);
	  if($numtipo >='1')
	  { 
	  $nombre_tipo = mysql_result($resultipo, 0, 'nombre_tipo');
	  echo $nombre_tipo;
	  } } ?></a></td>
      <td class="centrado4"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $medida=$row_rollos['medida_rollo']; 
	  if($medida!='') { 
	  $sqlmedida="SELECT * FROM medida WHERE id_medida='$medida'";
	  $resultmedida= mysql_query($sqlmedida);
	  $numedida= mysql_num_rows($resultmedida);
	  if($numedida >='1')
	  { 
	  $nombre_medida = mysql_result($resultmedida, 0, 'nombre_medida');
	  echo $nombre_medida;
	  } } ?></a></td>
      <td class="derecha1"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['ancho_rollo']; ?></a></td>
      <td class="derecha1"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['calibre_rollo']; ?></a></td>
      <td class="centrado4"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['tratamiento_rollo']; ?></a></td>
    </tr>
    <?php } while ($row_rollos = mysql_fetch_assoc($rollos)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($rollos);
?>
