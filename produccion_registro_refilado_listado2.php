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

$maxRows_orden_produccion = 20;
$pageNum_orden_produccion = 0;
if (isset($_GET['pageNum_orden_produccion'])) {
  $pageNum_orden_produccion = $_GET['pageNum_orden_produccion'];
}
$startRow_orden_produccion = $pageNum_orden_produccion * $maxRows_orden_produccion;

mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['op'];
$id_ref = $_GET['id_ref'];
//FILTRA OP LLENO
if($id_op!= '0' && $id_ref=='' )
{
$query_orden_produccion = "SELECT * FROM Tbl_orden_produccion WHERE id_op='$id_op' ORDER BY id_op DESC";
}
//FILTRA REF LLENO
if($id_ref != '0' && $id_op=='')
{
$query_orden_produccion = "SELECT * FROM Tbl_orden_produccion WHERE id_ref_op='$id_ref' ORDER BY id_op DESC";
}
$query_limit_orden_produccion = sprintf("%s LIMIT %d, %d", $query_orden_produccion, $startRow_orden_produccion, $maxRows_orden_produccion);
$orden_produccion = mysql_query($query_limit_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);

if (isset($_GET['totalRows_orden_produccion'])) {
  $totalRows_orden_produccion = $_GET['totalRows_orden_produccion'];
} else {
  $all_orden_produccion = mysql_query($query_orden_produccion);
  $totalRows_orden_produccion = mysql_num_rows($all_orden_produccion);
}
$totalPages_orden_produccion = ceil($totalRows_orden_produccion/$maxRows_orden_produccion)-1;

$queryString_orden_produccion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_orden_produccion") == false && 
        stristr($param, "totalRows_orden_produccion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_orden_produccion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_orden_produccion = sprintf("&totalRows_orden_produccion=%d%s", $totalRows_orden_produccion, $queryString_orden_produccion);
mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT id_op FROM Tbl_orden_produccion WHERE  b_estado_op > 0 AND b_borrado_op='0' ORDER BY id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);

mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = "SELECT id_ref, cod_ref FROM Tbl_referencia order by id_ref desc";
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);
session_start();
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
<li><a href="produccion_registro_refilado_listado.php">REFILADO</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="produccion_registro_refilado_add.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="12" id="titulo2">REGISTRO DE ORDENES DE PRODUCCION PARA REFILADO</td>
</tr>
<!--<tr>
  <td colspan="12" id="titulo2">O.P
    <input type="text" name="id_op" required onBlur="if (form1.id_op.value) { DatosGestiones('21','id_op',form1.id_op.value); } else { alert('Debe digitar el O.P para validar su existencia en la BD'); };"><div id="resultado"><input name="retorno_mensaje" type="hidden" ></div></td>
  </tr>-->
  <tr>
  <td  colspan="6" id="fuente3"><select name="op" id="op" onChange="ListadoProduccion('produccion_registro_refilado_listado2.php',this.name,this.value)">
    <option value="0">O.P.</option>
    <?php
do {  
?>
    <option value="<?php echo $row_lista_op['id_op']?>"><?php echo $row_lista_op['id_op']?></option>
    <?php
} while ($row_lista_op = mysql_fetch_assoc($lista_op));
  $rows = mysql_num_rows($lista_op);
  if($rows > 0) {
      mysql_data_seek($lista_op, 0);
	  $row_lista_op = mysql_fetch_assoc($lista_op);
  }
?>
  </select></td>
  <td  colspan="6" id="fuente1"><select name="id_ref" id="id_ref" onChange="ListadoProduccion('produccion_registro_refilado_listado2.php',this.name,this.value)">
    <option value="0">REF</option>
    <?php
do {  
?>
    <option value="<?php echo $row_ref_op['cod_ref']?>"><?php  echo $row_ref_op['cod_ref']?>
      </option>
    <?php
} while ($row_ref_op = mysql_fetch_assoc($ref_op));
  $rows = mysql_num_rows($ref_op);
  if($rows > 0) {
      mysql_data_seek($ref_op, 0);
	  $row_ref_op = mysql_fetch_assoc($ref_op);
  }
?>
  </select></td>
  </tr>
 <tr>
  <td colspan="6" id="fuente3">&nbsp;</td>
  <td colspan="6" id="fuente1">&nbsp;</td>
</tr>

<tr>
    <td colspan="6" id="dato1"><p>Nota: si en el la columna '<strong>Proceso</strong>', aparecen las siguientes notificaciones tenga en cuenta:</p>
      </td>
    <td colspan="6" id="dato1">Nota: si en el la columna '<strong>Mezcla</strong>', aparecen las siguientes notificaciones tenga en cuenta:</td>
    </tr>
  <tr>
    <td colspan="6" id="dato1"><p><img src="images/extruir.gif" width="20" height="17" alt="O.P EXTRUIDA"title="O.P EXTRUIDA" border="0" style="cursor:hand;"/> significa que la o.p esta en proceso de Refilado</p>
      <p><img src="images/refilado.gif" alt="ROLLOS COMPLETOS" width="20" height="17" title="ROLLOS COMPLETOS" border="0" style="cursor:hand;"/> significa que ya tiene todos los rollos agregados</p></td>
    <td colspan="6" id="dato1"><p><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /> significa que la ref. si tiene las mezclas de impresion</p>
      <p><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /> significa que la ref. no tiene las mezclas de impresion</p></td>
  </tr>
</table>
</form>  
<fieldset> <legend id="dato1">ORDENES DE PRODUCCION EXTRUIDAS</legend>
<table id="tabla1">
  <tr>
    <td colspan="10" id="dato3"><?php  if ($row_usuario['tipo_usuario']==1) {?><a href="costos_listado_ref_xproceso_tiempos.php"><img src="images/rt.gif" alt="LISTADO REF TIEMPO X PROCESO"title="LISTADO REF TIEMPO X PROCESO" border="0" style="cursor:hand;"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;"></a><?php }?><a href="produccion_registro_refilado_listado_add.php"><img src="images/opciones.gif" alt="LISTADO REFILADAS"title="LISTADO REFILADAS" border="0" style="cursor:hand;"></a><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS"title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR"title="REFRESCAR" border="0" style="cursor:hand;"/></a>
    </td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">MEZCLA</td>
    <td nowrap="nowrap"id="titulo4">ROLLOS</td>
  </tr>
  <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td nowrap="nowrap" id="dato2"><strong><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['id_op']; ?></a></strong></td>
      <td nowrap="nowrap" id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['fecha_registro_op']; ?></a><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"></a></td>
      <td nowrap="nowrap" id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000">
        <?php 
		$op_c=$row_orden_produccion['int_cliente_op'];
		$sqln="SELECT nombre_c FROM cliente WHERE id_c='$op_c'"; 
		$resultn=mysql_query($sqln); 
		$numn=mysql_num_rows($resultn); 
		if($numn >= '1') 
		{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); echo $nombre_cliente_c; }
		else { echo "";	
		}?>
      </a><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['int_cod_ref_op']; ?></a><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['version_ref_op']; ?></a><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['int_kilos_op']; ?></a><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="new"  style="text-decoration:none; color:#000000"></a></td>
      <td id="dato2">
      <?php 
	  $id_ref_op=$row_orden_produccion['id_ref_op'];
	  $sqlop="SELECT id_ref_cp FROM Tbl_caract_proceso WHERE id_ref_cp='$id_ref_op' AND id_proceso='2' ORDER BY id_ref_cp DESC LIMIT 1"; 
	  $resultop=mysql_query($sqlop); 
	  $numop=mysql_num_rows($resultop);
	  if($numop >= '1')
	  { ?><a href="javascript:popUp('produccion_caract_impresion_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref_op'];?>','870','600')"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a><?php 
	  }else { ?><a href="javascript:popUp('produccion_caract_impresion_add.php?id_ref=<?php echo $row_orden_produccion['id_ref_op'];?>&cod_ref=<?php echo $row_orden_produccion['int_cod_ref_op'];?>','870','600')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /></a>
       <?php }?></td>   
      <td nowrap="nowrap" id="dato2">
      	 <?php 	
		$op_c=$row_orden_produccion['id_op'];
		$sqlno="SELECT COUNT(rollo_r) AS role FROM TblExtruderRollo WHERE id_op_r='$op_c'"; 
		$resultno=mysql_query($sqlno); 
		$numno=mysql_num_rows($resultno);
		if($numno > '0') 
		{ $rolloE=mysql_result($resultno,0,'role');}else{ echo '0';}		
		$sqlnI="SELECT COUNT(rollo_r) AS roli FROM TblRefiladoRollo WHERE id_op_r='$op_c'"; 
		$resultnI=mysql_query($sqlnI); 
		$numnI=mysql_num_rows($resultnI);
		if($numnI > '0') 
		{ $rolloR=mysql_result($resultnI,0,'roli');}else{ echo '0';} 
		?>      	
	  <?php if($rolloR < $rolloE){?><a href="javascript:verFoto('produccion_refilado_stiker_rollo_add.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','600')"><img src="images/extruir.gif" width="20" height="20" alt="ADD ROLLOS"title="ADD ROLLOS" border="0" style="cursor:hand;" /></a>
	  <?php }else {?><a href="javascript:verFoto('produccion_refilado_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/refilado.gif" width="23" height="20" alt="REFILADO"title="REFILADO" border="0" style="cursor:hand;" /></a><?php }?>
	  </td>	     
    </tr>
    <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?> 
</table>
 </fieldset>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, 0, $queryString_orden_produccion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_orden_produccion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, max(0, $pageNum_orden_produccion - 1), $queryString_orden_produccion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion < $totalPages_orden_produccion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, min($totalPages_orden_produccion, $pageNum_orden_produccion + 1), $queryString_orden_produccion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion < $totalPages_orden_produccion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, $totalPages_orden_produccion, $queryString_orden_produccion); ?>">&Uacute;ltimo</a>
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

mysql_free_result($orden_produccion);

mysql_close($conexion1);

unset($usuario,$conexion1);
unset($orden_produccion,$conexion1);
unset($orden_produccion,$conexion1);
unset($lista_op,$conexion1);
unset($ref_op,$conexion1);
?>