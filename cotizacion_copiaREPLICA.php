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
$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_cotizacion = 20;
$pageNum_cotizacion = 0;
if (isset($_GET['pageNum_cotizacion'])) {
  $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
}
$startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;
//bolsa
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT *
FROM Tbl_cotizaciones 
LEFT JOIN Tbl_cotiza_bolsa ON Tbl_cotizaciones.Str_nit=Tbl_cotiza_bolsa.str_nit
LEFT JOIN Tbl_cotiza_laminas ON Tbl_cotizaciones.Str_nit=Tbl_cotiza_laminas.str_nit 
LEFT JOIN Tbl_cotiza_packing ON Tbl_cotizaciones.Str_nit=Tbl_cotiza_packing.str_nit
LEFT JOIN Tbl_cotiza_materia_p ON Tbl_cotizaciones.Str_nit=Tbl_cotiza_materia_p.str_nit
WHERE
Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_bolsa.N_cotizacion AND 
Tbl_cotizaciones.Str_nit=Tbl_cotiza_bolsa.str_nit OR
Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_laminas.N_cotizacion AND  Tbl_cotizaciones.Str_nit=Tbl_cotiza_laminas.str_nit OR
Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_packing.N_cotizacion AND 
Tbl_cotizaciones.Str_nit=Tbl_cotiza_packing.str_nit OR
Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_materia_p.N_cotizacion AND  Tbl_cotizaciones.Str_nit=Tbl_cotiza_materia_p.str_nit";
$query_limit_cotizacion = sprintf("%s LIMIT %d, %d", $query_cotizacion, $startRow_cotizacion, $maxRows_cotizacion);
$cotizacion = mysql_query($query_limit_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);

if (isset($_GET['totalRows_cotizacion'])) {
  $totalRows_cotizacion = $_GET['totalRows_cotizacion'];
} else {
  $all_cotizacion = mysql_query($query_cotizacion);
  $totalRows_cotizacion = mysql_num_rows($all_cotizacion);
}
$totalPages_cotizacion = ceil($totalRows_cotizacion/$maxRows_cotizacion)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_numero = "SELECT * FROM Tbl_cotizaciones  ORDER BY N_cotizacion DESC";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);
//clientes
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$queryString_cotizacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_cotizacion") == false && 
        stristr($param, "totalRows_cotizacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_cotizacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_cotizacion = sprintf("&totalRows_cotizacion=%d%s", $totalRows_cotizacion, $queryString_cotizacion);

session_start();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
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
  <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="cotizacion_copia2.php" method="get" name="consulta">
<table id="tabla1">
<tr>
<td nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td nowrap="nowrap" id="titulo2" width="50%">LISTADO DE COTIZACIONES</td>
<td nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
</tr>
<tr>
  <td colspan="3" id="fuente2"><select name="n_cotiz" id="n_cotiz">
    <option value="0">Seleccione la Cotizacion</option>
    <?php
do {  
?><option value="<?php echo $row_numero['N_cotizacion']?>"><?php echo $row_numero['N_cotizacion']?></option>
    <?php
} while ($row_numero = mysql_fetch_assoc($numero));
  $rows = mysql_num_rows($numero);
  if($rows > 0) {
      mysql_data_seek($numero, 0);
	  $row_numero = mysql_fetch_assoc($numero);
  }
?>
    </select>
    <select name="id_c" id="id_c"style="width:350px">
      <option value="0">Seleccione el Cliente</option>
      <?php
do {  
?>
      <option value="<?php echo $row_cliente['nit_c']?>"><?php $cad = $row_cliente['nombre_c'];echo $cad;?></option>
      <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
    </select>    <input type="submit" name="Submit" value="FILTRO" onclick="if(consulta.n_cotiz.value=='0' && consulta.id_c.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</form>
<form action="delete_listado2.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2"><input name="borrado" type="hidden" id="borrado" value="2" />
    <input name="Str_nit" type="hidden" id="Str_nit" value=" <?php echo $row_cotizacion['Str_nit']; ?>" />
      <input name="Input" type="submit" value="Delete"/></td>
    <td colspan="2"><?php $id=$_GET['id']; $mensaje=$_GET['mensaje']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "CAMBIO DE ESTADO A RECHAZADA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div><?php } 
  if($mensaje== '3') { ?><div id="numero1"><?php echo "LA REFERENCIA NO EXISTE"; ?> </div>
<?php } 
  if($mensaje== '4') { ?><div id="numero1"><?php echo "LA COTIZACION NO TIENE REFERENCIAS"; ?> </div>
  
  <?php }?>  </td>
    <td colspan="2" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?><a href="cotizacion_general_menu.php"><img src="images/mas.gif" alt="ADD COTIZACION" title="ADD COTIZACION" border="0" style="cursor:hand;"/></a><?php } ?>      <a href="cotizacion_general_menu.php"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
   
    <td nowrap="nowrap" id="titulo4">COTIZ</td>
    <td nowrap="nowrap" id="titulo4">TIPO</td>
    
    <td nowrap="nowrap" id="titulo4">CLIENTE</td>
    <td nowrap="nowrap" id="titulo4">ESTADO</td>


  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="cotiz[]" type="checkbox" value="<?php echo $row_cotizacion['N_cotizacion']; ?>" /></td>
      
      <td id="dato2"><a href="cotizacion_g_bolsa_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_cotizacion']; ?></a></td>
      <td id="dato2"><a href="cotizacion_g_bolsa_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['Str_tipo']; ?></a></td>
      
      <td id="dato1"><?php 
	$nit_c=$row_cotizacion['str_nit'];
	$sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nit_cliente_c); }
	 ?><a href="cotizacion_g_bolsa_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo  $ca; ?></a></td>
 
<td id="dato2"></strong><?php if($row_cotizacion['B_estado'] == '0') { ?> <a href="cotizacion_g_bolsa_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new"><img src="images/falta.gif" alt="REFERENCIAS PENDIENTES" title="REFERENCIAS PENDIENTES" border="0" style="cursor:hand;"></a><?php }else if($row_cotizacion['B_estado'] == '1') {echo "ACEPTADA";}else if($row_cotizacion['B_estado'] == '2') {echo "RECHAZADA";} ?></td>     

    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
<?php if($row_cotizacion['N_cotizacion']!=''){ ?>    
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="cotiz[]" type="checkbox" value="<?php echo $row_cotizacion['N_cotizacion']; ?>" /></td>
      
      <td id="dato2"><a href="cotizacion_g_lamina_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_cotizacion']; ?></a></td>
      <td id="dato2"><a href="cotizacion_g_lamina_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['Str_tipo']; ?></a></td>
      
      <td id="dato1"><?php 
	$nit_c=$row_cotizacion['str_nit'];
	$sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca2 =($nit_cliente_c); }?>
    <a href="cotizacion_g_lamina_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&amp;Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $ca2; ?>
      </a></td>
 
<td id="dato2"></strong><?php if($row_cotizacion['B_estado'] == '0') { ?> <a href="cotizacion_g_lamina_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new"><img src="images/falta.gif" alt="REFERENCIAS PENDIENTES" title="REFERENCIAS PENDIENTES" border="0" style="cursor:hand;"></a><?php }else if($row_cotizacion['B_estado'] == '1') {echo "ACEPTADA";}else if($row_cotizacion['B_estado'] == '2') {echo "RECHAZADA";}  ?></td>    

    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion2)); ?>
    <?php } // aparece o desaparece dependiendo de si hay o no cotiz de lamina ?> 
<?php if($row_cotizacion['N_cotizacion']!=''){ ?>    
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="cotiz[]" type="checkbox" value="<?php echo $row_cotizacion['N_cotizacion']; ?>" /></td>
      
      <td id="dato2"><a href="cotizacion_g_packing_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_cotizacion']; ?></a></td>
      <td id="dato2"><a href="cotizacion_g_packing_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['Str_tipo']; ?></a></td>
      
      <td id="dato1"><?php 
	$nit_c=$row_cotizacion['str_nit'];
	$sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca3 =($nit_cliente_c); } ?>
    <a href="cotizacion_g_packing_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&amp;Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000">
      <?php echo $ca3;?></a></td>
 
<td id="dato2"></strong><?php if($row_cotizacion['B_estado'] == '0') { ?> <a href="cotizacion_g_packing_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new"><img src="images/falta.gif" alt="REFERENCIAS PENDIENTES" title="REFERENCIAS PENDIENTES" border="0" style="cursor:hand;"></a><?php }else if($row_cotizacion['B_estado'] == '1') {echo "ACEPTADA";}else if($row_cotizacion['B_estado'] == '2') {echo "RECHAZADA";}  ?></td>      

    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion3)); ?>
    <?php } // aparece o desaparece dependiendo de si hay o no cotiz de lamina ?>        
<?php if($row_cotizacion['N_cotizacion']!=''){ ?>    
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="cotiz[]" type="checkbox" value="<?php echo $row_cotizacion['N_cotizacion']; ?>" /></td>
      
      <td id="dato2"><a href="cotizacion_g_materiap_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_cotizacion']; ?></a></td>
      <td id="dato2"><a href="cotizacion_g_materiap_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['Str_tipo']; ?></a></td>
      
      <td id="dato1">
        <?php 
	$nit_c=$row_cotizacion['str_nit'];
	$sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca4 =($nit_cliente_c); } ?>
      <a href="cotizacion_g_materiap_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&amp;Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "6"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $ca4;?></a></td>
 
<td id="dato2"></strong><?php if($row_cotizacion['B_estado'] == '0') { ?> <a href="cotizacion_g_materiap_vista.php?N_cotizacion=<?php echo $row_cotizacion['N_cotizacion']; ?>&cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "6"; ?>" target="new"><img src="images/falta.gif" alt="REFERENCIAS PENDIENTES" title="REFERENCIAS PENDIENTES" border="0" style="cursor:hand;"></a><?php }else if($row_cotizacion['B_estado'] == '1') {echo "ACEPTADA";}else if($row_cotizacion['B_estado'] == '2') {echo "RECHAZADA";}  ?></td>     

    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion4)); ?>
    <?php } // aparece o desaparece dependiendo de si hay o no cotiz de materia ?>       
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, $totalPages_cotizacion, $queryString_cotizacion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
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

mysql_free_result($cotizacion);

mysql_free_result($numero);

?>