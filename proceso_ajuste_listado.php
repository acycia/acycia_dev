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
$query_ajuste = "SELECT * FROM TblProcesoAjuste ORDER BY fechaInicial_pa DESC";
$ajuste = mysql_query($query_ajuste, $conexion1) or die(mysql_error());
$row_ajuste = mysql_fetch_assoc($ajuste);
$totalRows_ajuste = mysql_num_rows($ajuste);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla">
<tr align="center"><td>
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
  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<form action="delete_listado.php" method="get" name="seleccion">
	<table id="tabla1">
	<tr>
	  <td colspan="7" id="titulo2">LISTADO DE AJUSTES DE PROCESOS</td>
  </tr>
<tr>
  <td colspan="2" id="fuente1"><input name="" type="submit" value="Delete"/>
    <input name="borrado" type="hidden" id="borrado" value="43" /></td>
  <td colspan="2" id="fuente1"><?php $id=$_GET['id']; if($id >= '1') { ?> 
    <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
    <?php } 
  if($id == '0') { ?> 
    <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>    <?php } ?></td>
  <td width="25%" id="fuente3"><a href="proceso_ajuste_add.php"><img src="images/mas.gif" alt="ADD AJUSTE" title="ADD AJUSTE" border="0" style="cursor:hand;"></a><a href="tipos_procesos.php"><img src="images/p.gif" title="TIPOS DE PROCESOS" alt="TIPOS DE PROCESOS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="CARGAR LISTADO" title="CARGAR LISTADO" border="0" style="cursor:hand;"/></a><a href="turnos.php"><img src="images/t.gif" style="cursor:hand;" alt="TURNOS" title="TURNOS" border="0"/></a></td></tr>
<tr id="tr1">
  <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
  <td id="titulo4">PROCESO</td>
  <td id="titulo4">AJUSTE</td>
  <td id="titulo4">FECHA INICIAL</td>
  <td id="titulo4">FECHA FINAL</td>
  </tr>
<?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_ajuste['id_pa']; ?>" /></td>
    <td id="dato2"><a href="proceso_ajuste_edit.php?id_pa=<?php echo $row_ajuste['id_pa']; ?>" target="_top" style="text-decoration:none; color:#000000">
      <?php $proceso=$row_ajuste['id_proceso_pa']; 	
	$sql2="SELECT * FROM tipo_procesos WHERE id_tipo_proceso='$proceso'";
	$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
	if ($num2>='1') { $proceso_ajuste=mysql_result($result2,0,'nombre_proceso'); 
	echo $proceso_ajuste; }?>
    </a></td>   
    <td id="dato2"><a href="proceso_ajuste_edit.php?id_pa=<?php echo $row_ajuste['id_pa']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ajuste['valor_pa'];?></a></td>
    <td id="dato2"><a href="proceso_ajuste_edit.php?id_pa=<?php echo $row_ajuste['id_pa']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ajuste['fechaInicial_pa']; ?></a></td>
    <td id="dato2"><a href="proceso_ajuste_edit.php?id_pa=<?php echo $row_ajuste['id_pa']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ajuste['fechaFinal_pa']; ?></a></td>
    </tr>
  <?php } while ($row_ajuste = mysql_fetch_assoc($ajuste)); ?>
</table>
</form>
  </td>
  </tr>
</table></div>
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

mysql_free_result($ajuste);

?>