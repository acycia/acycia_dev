<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
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

$conexion = new ApptivaDB();

$currentPage = $_SERVER["PHP_SELF"];


$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
/*
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);*/

/*$maxRows_insumos = 20;
$pageNum_insumos = 0;
if (isset($_GET['pageNum_insumos'])) {
  $pageNum_insumos = $_GET['pageNum_insumos'];
}
$startRow_insumos = $pageNum_insumos * $maxRows_insumos;

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM insumo WHERE estado_insumo='0' ORDER BY descripcion_insumo ASC";
$query_limit_insumos = sprintf("%s LIMIT %d, %d", $query_insumos, $startRow_insumos, $maxRows_insumos);
$insumos = mysql_query($query_limit_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);

if (isset($_GET['totalRows_insumos'])) {
  $totalRows_insumos = $_GET['totalRows_insumos'];
} else {
  $all_insumos = mysql_query($query_insumos);
  $totalRows_insumos = mysql_num_rows($all_insumos);
}
$totalPages_insumos = ceil($totalRows_insumos/$maxRows_insumos)-1;*/


$maxRows_registros = 20;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;


$colname_busqueda= "-1";

$registros=$conexion->buscarListar("insumo","*","ORDER BY descripcion_insumo ASC ","",$maxRows_registros,$pageNum_registros,"WHERE estado_insumo='0'" );
 

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('tbl_orden_compra'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;

$row_codigo = $conexion->llenaListas('insumo',"WHERE estado_insumo='0'",'ORDER BY codigo_insumo ASC',"id_insumo,codigo_insumo"); 

$row_lista = $conexion->llenaListas('insumo',"WHERE estado_insumo='0'",'ORDER BY descripcion_insumo ASC',"id_insumo,descripcion_insumo"); 

$row_tipo = $conexion->llenaListas('tipo','','ORDER BY nombre_tipo ASC',"id_tipo,nombre_tipo"); 

$row_clase = $conexion->llenaListas('clase','','ORDER BY nombre_clase ASC',"id_clase,nombre_clase"); 

/*mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT * FROM insumo WHERE estado_insumo='0' ORDER BY descripcion_insumo ASC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conexion1, $conexion1);
$query_tipo = "SELECT * FROM tipo ORDER BY nombre_tipo ASC";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo = mysql_num_rows($tipo);

mysql_select_db($database_conexion1, $conexion1);
$query_codigo = "SELECT * FROM insumo WHERE estado_insumo='0' ORDER BY codigo_insumo ASC";
$codigo = mysql_query($query_codigo, $conexion1) or die(mysql_error());
$row_codigo = mysql_fetch_assoc($codigo);
$totalRows_codigo = mysql_num_rows($codigo);*/

/*$queryString_insumos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_insumos") == false && 
        stristr($param, "totalRows_insumos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_insumos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_insumos = sprintf("&totalRows_insumos=%d%s", $totalRows_insumos, $queryString_insumos);*/
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<link rel="stylesheet" type="text/css" href="css/general.css"/>
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
<tr><td id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></td>
  <td id="cabezamenu">
<ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="insumos_busqueda1.php" method="get" name="consulta">
<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">FILTRO DE INSUMOS </td>
  <td id="dato3"><a href="insumo_add.php" target="_top"><img src="images/mas.gif" alt="ADD INSUMO" border="0" style="cursor:hand;"/></a><a href="insumos_busqueda.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="insumos.php" target="_top"><img src="images/cat.gif" alt="INSUMOS" border="0" style="cursor:hand;"/></a></td>
</tr>
<tr>
  <td colspan="2" id="fuente2"><select name="codigo_insumo" id="codigo_insumo" class="selectsMini">
    <option value="0">CODIGO</option>
    <?php  foreach($row_codigo as $row_codigo ) { ?>
      <option value="<?php echo $row_codigo['id_insumo']?>"><?php echo $row_codigo['codigo_insumo']?></option>
      <?php }  ?>
    </select>
    <select name="id_insumo" id="id_insumo" class="selectsMini">
      <option value="0">INSUMO</option>
      <?php  foreach($row_lista as $row_lista ) { ?>
        <option value="<?php echo $row_lista['id_insumo']?>"><?php echo $row_lista['descripcion_insumo']?></option>
        <?php }  ?>
    </select>
    <select name="tipo_insumo" id="tipo_insumo" class="selectsMini">
      <option value="0">TIPO</option>
      <?php  foreach($row_tipo as $row_tipo ) { ?>
        <option value="<?php echo $row_tipo['id_tipo']?>"><?php echo $row_tipo['nombre_tipo']?></option>
        <?php } ?>
    </select> 
    <select name="clase" id="clase" class="selectsMini">
      <option value="0">CLASE</option>
      <?php  foreach($row_clase as $row_clase ) { ?>
        <option value="<?php echo $row_clase['id_clase']?>"><?php echo $row_clase['nombre_clase']?></option>
        <?php }  ?>
    </select>

    <input type="submit" class="botonGMini" name="Submit" value="FILTRO" onClick="if(consulta.codigo_insumo.value=='0' && consulta.id_insumo.value=='0' && consulta.tipo_insumo.value=='0' && consulta.clase.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="10" />
      <input name="Input" type="submit" value="X"/></td>
    <td colspan="6" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }
  if($id == '') { ?>
      <div id="dato1"> <?php echo "Si elimina un INSUMO, sera definitivamente."; ?> </div>      <?php }
  ?></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">CODIGO</td>
    <td id="titulo4">DESCRIPCION</td>
    <td id="titulo4">CLASE</td>
    <td id="titulo4">MEDIDA</td>
    <td id="titulo4">TIPO</td>
    <td id="titulo4">VALOR </td>
    </tr>
  <?php foreach($registros as $row_insumos) {  ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_insumos['id_insumo']; ?>" /></td>
      <td id="dato1"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['codigo_insumo']; ?></a></td>
      <td id="dato1"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['descripcion_insumo']; ?></a></td>
      <td id="dato1"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php  
      $clase_insumo=$row_insumos['clase_insumo'];
      $clase_insumo = $conexion->llenarCampos('clase',"WHERE id_clase = '$clase_insumo'",'',"nombre_clase"); 
      echo $clase_insumo['nombre_clase'];
	    ?></a></td>
      <td id="dato1">
        <a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
        $medida_insumo=$row_insumos['medida_insumo'];
        $medida_insumo = $conexion->llenarCampos('medida',"WHERE id_medida = '$medida_insumo'",'',"nombre_medida"); 
	      echo $medida_insumo['nombre_medida']; 
      ?></a></td>
      <td id="dato1"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
        $tipo_insumo=$row_insumos['tipo_insumo'];
        $tipo_insumo = $conexion->llenarCampos('tipo',"WHERE id_tipo = '$tipo_insumo'",'',"nombre_tipo"); 
	      echo $tipo_insumo['nombre_tipo'];
      ?></a></td>
      <td id="dato3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['valor_unitario_insumo']; ?></a></td>
    </tr>
    <?php }  ?>
</table>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
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
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($insumos);

mysql_free_result($lista);

mysql_free_result($tipo);

mysql_free_result($codigo);
?>