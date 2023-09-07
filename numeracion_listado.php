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

mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT int_op_n FROM Tbl_numeracion ORDER BY int_op_n DESC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

$maxRows_numeracion = 20;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;

mysql_select_db($database_conexion1, $conexion1);
$query_numeracion = "SELECT Tbl_numeracion.int_op_n, Tbl_numeracion.int_undxpaq_n, Tbl_numeracion.int_undxcaja_n, Tbl_numeracion.fecha_ingreso_n, Tbl_numeracion.existeTiq_n FROM Tbl_numeracion WHERE  Tbl_numeracion.existeTiq_n='0' ORDER BY Tbl_numeracion.int_op_n DESC";
$query_limit_numeracion = sprintf("%s LIMIT %d, %d", $query_numeracion, $startRow_numeracion, $maxRows_numeracion);
$numeracion = mysql_query($query_limit_numeracion, $conexion1) or die(mysql_error());
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



?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
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
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="sellado_control_numeracion_add.php">O.P CON TIQUETES</a></li>
 </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<table id="tabla1">
	  <tr>
	  <td id="subtitulo">LISTADO DE O.P SIN TIQUETES</td>
	  </tr>
	  <tr>
	  <td id="fuente2">&nbsp;</td>
  </tr>
</table>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1"><input name="usuario" type="hidden" id="usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
      <input name="borrado" type="hidden" id="borrado" value="38"/>
      <input name="Input" type="submit" value="Eliminar"/></td>
    <td colspan="4"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "SE ELIMINO CORRECTAMENTE"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
    <td colspan="5" id="dato3"><a href="sellado_control_numeracion_add.php"><img src="images/mas.gif" alt="ADD O.P" title="ADD O.P" border="0" style="cursor:hand;"/></a><a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" title="LISTADO O.P" border="0" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
 </tr>
  <tr>
    <td colspan="2" id="dato4">&nbsp;</td>
    <td colspan="4">&nbsp;</td>
    <td colspan="5" id="dato5">&nbsp;</td>
  </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td> 
<!--    <td nowrap="nowrap"id="titulo4">Paquete</td>-->     
    <td nowrap="nowrap"id="titulo4">Caja</td>              
    <td nowrap="nowrap"id="titulo4">Und x Paq. </td>   
    <td nowrap="nowrap"id="titulo4">Und x Caja </td> 
    <td nowrap="nowrap"id="titulo4">FECHA</td>                      
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>                
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_numeracion['int_op_n']; ?>" /></td>     
      <td id="dato2"><strong><!--<a href="sellado_control_numeracion_add.php?id_op=<?php echo $row_numeracion['int_op_n']; ?>" target="_parent" style="text-decoration:none; color:#000000">--><?php echo $row_numeracion['int_op_n']; ?><!--</a>--></strong></td>
      <td id="dato2"><?php echo  "1" ?></a></td>           
      <td id="dato2"><?php echo $row_numeracion['int_undxpaq_n'];  ?></td>
      <td id="dato2"><?php echo $row_numeracion['int_undxcaja_n']; ?></td>
      <td id="dato2"><?php echo $row_numeracion['fecha_ingreso_n'];?></td> 
      <td nowrap="nowrap" id="dato2">
        <?php 
	$id_op=$row_numeracion['int_op_n'];
	$sqln="SELECT * FROM Tbl_orden_produccion,cliente WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.str_nit_op=cliente.nit_c"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = htmlentities ($nombre_cliente_c); echo $ca; }
	else { echo "";	} ?>
    </a></td>    
    </tr>
    <?php } while ($row_numeracion = mysql_fetch_assoc($numeracion)); ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, 0, $queryString_numeracion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, max(0, $pageNum_numeracion - 1), $queryString_numeracion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, min($totalPages_numeracion, $pageNum_numeracion + 1), $queryString_numeracion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, $totalPages_numeracion, $queryString_numeracion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td>
  </tr></table>
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

mysql_free_result($lista);

mysql_free_result($numeracion);

?>
