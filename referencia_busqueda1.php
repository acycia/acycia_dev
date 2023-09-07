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
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM referencia ORDER BY cod_ref ASC";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_egps = "SELECT * FROM egp ORDER BY n_egp DESC";
$egps = mysql_query($query_egps, $conexion1) or die(mysql_error());
$row_egps = mysql_fetch_assoc($egps);
$totalRows_egps = mysql_num_rows($egps);

mysql_select_db($database_conexion1, $conexion1);
$query_cotizaciones = "SELECT * FROM cotizacion ORDER BY n_cotiz DESC";
$cotizaciones = mysql_query($query_cotizaciones, $conexion1) or die(mysql_error());
$row_cotizaciones = mysql_fetch_assoc($cotizaciones);
$totalRows_cotizaciones = mysql_num_rows($cotizaciones);

mysql_select_db($database_conexion1, $conexion1);
$id_ref = $_GET['id_ref'];
$id_c = $_GET['id_c'];
$n_egp = $_GET['egp'];
$n_cotiz = $_GET['cotizacion'];
//Filtra todos vacios
if($id_ref == '0' && $id_c == '0' && $n_egp == '0' && $n_cotiz == '0')
{
$query_referencia = "SELECT * FROM referencia ORDER BY cod_ref ASC";
}
//Filtra Referencia
if($id_ref != '0' && $id_c == '0' && $n_egp == '0' && $n_cotiz == '0')
{
$query_referencia = "SELECT * FROM referencia WHERE id_ref='$id_ref'";
}
//Filtra EGP
if($id_ref == '0' && $id_c == '0' && $n_egp != '0' && $n_cotiz == '0')
{
$query_referencia = "SELECT * FROM referencia WHERE n_egp_ref='$n_egp'";
}
//Filtra COTIZACION
if($id_ref == '0' && $id_c == '0' && $n_egp == '0' && $n_cotiz != '0')
{
$query_referencia = "SELECT * FROM referencia WHERE n_cotiz_ref='$n_cotiz'";
}
//REFERENCIAS X CLIENTE
if($id_ref == '0' && $id_c != '0' && $n_egp == '0' && $n_cotiz == '0')
{
$query_referencia = "SELECT * FROM referencia, ref_cliente WHERE ref_cliente.id_c='$id_c' AND referencia.id_ref = ref_cliente.id_ref ORDER BY referencia.cod_ref";
}
//CLIENTES X REFERENCIA
if($id_ref != '0' && $id_c == '' && $n_egp == '0' && $n_cotiz == '0')
{
$query_referencia = "SELECT * FROM ref_cliente, cliente WHERE ref_cliente.id_ref='$id_ref' AND cliente.id_c = ref_cliente.id_c";
}
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
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
  <td id="cabezamenu">
<ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="disenoydesarrollo.php">DISEÑOYDESARROLLO</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="referencia_busqueda1.php" method="get" name="consulta">
<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">FILTRO DE REFERENCIAS</td>
  <td id="dato3"><a href="referencia_busqueda.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php" target="_top"><img src="images/r.gif" alt="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="control_modificaciones.php" target="_top"><img src="images/m.gif" alt="MODIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion.php" target="_top"><img src="images/v.gif" alt="VALIDACIONES" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica.php" target="_top"><img src="images/f.gif" alt="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
</tr>
<tr>
  <td colspan="2" id="fuente2"><select name="id_ref" id="id_ref">
    <option value="0" <?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
    <?php
do {  
?><option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
    <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
    </select><select name="id_c" id="id_c">
      <option value="0" <?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>CLIENTE</option>
      <option value="" <?php if (!(strcmp("", $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>CLIENTES x REF</option>
      <?php
do {  
?><option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c']?></option>
      <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
  $rows = mysql_num_rows($clientes);
  if($rows > 0) {
      mysql_data_seek($clientes, 0);
	  $row_clientes = mysql_fetch_assoc($clientes);
  }
?>
    </select><select name="egp">
      <option value="0" <?php if (!(strcmp(0, $_GET['egp']))) {echo "selected=\"selected\"";} ?>>EGP</option>
      <?php
do {  
?><option value="<?php echo $row_egps['n_egp']?>"<?php if (!(strcmp($row_egps['n_egp'], $_GET['egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_egps['n_egp']?></option>
      <?php
} while ($row_egps = mysql_fetch_assoc($egps));
  $rows = mysql_num_rows($egps);
  if($rows > 0) {
      mysql_data_seek($egps, 0);
	  $row_egps = mysql_fetch_assoc($egps);
  }
?>
    </select><select name="cotizacion" id="cotizacion">
      <option value="0" <?php if (!(strcmp(0, $_GET['cotizacion']))) {echo "selected=\"selected\"";} ?>>COTIZ</option>
      <?php
do {  
?><option value="<?php echo $row_cotizaciones['n_cotiz']?>"<?php if (!(strcmp($row_cotizaciones['n_cotiz'], $_GET['cotizacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_cotizaciones['n_cotiz']?></option>
      <?php
} while ($row_cotizaciones = mysql_fetch_assoc($cotizaciones));
  $rows = mysql_num_rows($cotizaciones);
  if($rows > 0) {
      mysql_data_seek($cotizaciones, 0);
	  $row_cotizaciones = mysql_fetch_assoc($cotizaciones);
  }
?>
    </select><input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_ref.value=='0' && consulta.id_c.value=='0' && consulta.egp.value=='0' && consulta.cotizacion.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
	<?php $id_c=$_GET['id_c']; $id_ref=$_GET['id_ref'];
	 if($id_c == '' && $id_ref != '0')
	 { ?>
	 <table id="tabla1">
  <tr id="tr2">
    <td nowrap="nowrap" id="titulo4">NIT</td>    
    <td nowrap="nowrap" id="titulo4">CLIENTE</td>
    <td nowrap="nowrap" id="titulo4">PAIS</td>
    <td nowrap="nowrap" id="titulo4">CIUDAD</td>
    <td nowrap="nowrap" id="titulo4">TELEFONO</td>
    <td nowrap="nowrap" id="titulo4">FAX</td>    
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_referencia['id_c']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['nit_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_referencia['id_c']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['nombre_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_referencia['id_c']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['pais_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_referencia['id_c']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['ciudad_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_referencia['id_c']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['telefono_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_referencia['id_c']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['fax_c']; ?></a></td>      
    </tr>
	<?php } while ($row_referencia = mysql_fetch_assoc($referencia)); ?>
</table>
	 <?php 
	 } 
	 else
	 { ?>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="4" />
      <input name="Input" type="submit" value="Delete"/></td>
    <td colspan="7"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div> <?php }
  if($id == '') { ?><div id="dato1"> <?php echo "Si elimina una REFERENCIA, tambien eliminara los registros de DISEÑO Y DESARROLLO respectivos"; ?> </div> <?php }
  ?></td>
    </tr>
	<tr id="tr2">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap" id="titulo4">REFERENCIA</td>
    <td nowrap="nowrap" id="titulo4">VERSION</td>
    <td nowrap="nowrap" id="titulo4">EGP</td>
    <td nowrap="nowrap" id="titulo4">COTIZACION </td>
    <td nowrap="nowrap" id="titulo4">TIPO BOLSA </td>
    <td nowrap="nowrap" id="titulo4">ARTE</td>
    <td nowrap="nowrap" id="titulo4">FECHA ARTE </td>
    <td nowrap="nowrap" id="titulo4">ESTADO ARTE </td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_referencia['id_ref']; ?>" /></td>
      <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['cod_ref']; ?></a></td>
      <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['version_ref']; ?></a></td>
      <td id="dato2"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_referencia['n_egp_ref']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['n_egp_ref']; ?></a></td>
      <td id="dato2"><a href="cotizacion_bolsa_vista.php?n_cotiz=<?php echo $row_referencia['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_referencia['n_cotiz_ref']; ?></a></td>
      <td id="dato2"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
      <td id="dato2"><?php $id_ref=$row_referencia['id_ref'];
	  $sql2="SELECT * FROM verificacion WHERE id_ref_verif='$id_ref' ORDER BY id_verif DESC";
	  $result2=mysql_query($sql2);
	  $num2=mysql_num_rows($result2);
	  if ($num2 >= '1')
	  {	$arte=mysql_result($result2,0,'userfile');
	    $fecha_arte=mysql_result($result2,0,'fecha_aprob_arte_verif');
		$estado_arte=mysql_result($result2,0,'estado_arte_verif');
	  } if($arte!='') { ?> <a href="javascript:verFoto('archivo/<?php echo $arte;?>','610','490')"><img src="images/arte.gif" alt="ARTE" border="0" style="cursor:hand;"  /></a><?php } if($arte =='') { echo "- -"; } ?></td>
      <td id="dato2"><?php echo $fecha_arte; ?></td>
      <td id="dato2"><?php if($estado_arte=='0') { echo "Pendiente"; } if($estado_arte=='1') { echo "Rechazado"; } if($estado_arte=='2') { echo "Aceptado"; } if($estado_arte=='3') { echo "Anulado"; } ?></td>
    </tr>
    <?php } while ($row_referencia = mysql_fetch_assoc($referencia)); ?>
</table>
<?php } ?>
</form>
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

mysql_free_result($clientes);

mysql_free_result($referencias);

mysql_free_result($egps);

mysql_free_result($cotizaciones);

mysql_free_result($referencia);
?>