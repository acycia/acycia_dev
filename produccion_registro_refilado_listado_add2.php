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
$query_orden_produccion = "SELECT * FROM TblRefiladoRollo WHERE id_op_r='$id_op' ORDER BY id_op_r DESC";
}
//FILTRA REF LLENO
if($id_ref != '0' && $id_op=='')
{
$query_orden_produccion = "SELECT * FROM TblRefiladoRollo WHERE ref_r='$id_ref' ORDER BY id_op_r DESC";
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
$query_lista_op = "SELECT * FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.b_borrado_op='0' AND Tbl_orden_produccion.id_op IN (SELECT TblRefiladoRollo.id_op_r FROM TblRefiladoRollo WHERE TblRefiladoRollo.id_op_r = Tbl_orden_produccion.id_op) ORDER BY Tbl_orden_produccion.id_op DESC";
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
<form action="produccion_registro_refilado_add.php" method="get" name="form1" onSubmit="if(form1.retorno_mensaje.value=='1'){return false;}else if(form1.retorno_mensaje.value=='0'){return true;}">
<table id="tabla1">
<tr>
<td colspan="4" id="titulo2">REGISTRO DE ORDENES DE PRODUCCION EN REFILADO</td>
</tr>
<tr>
  <td id="fuente3"><select name="op" id="op" onChange="ListadoProduccion('produccion_registro_refilado_listado_add2.php',this.name,this.value)">
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
  <td colspan="3" id="fuente1"><select name="id_ref" id="id_ref" onChange="ListadoProduccion('produccion_registro_refilado_listado_add2.php',this.name,this.value)">
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
  <td id="fuente2"><div id="resultado"></div></td>
  <td colspan="3" id="fuente2">&nbsp;</td>
</tr>
<tr>
    <td colspan="3" id="dato1"><p>Nota: si en el la columna '<strong>Proceso</strong>', aparecen las siguientes notificaciones tenga en cuenta:</p>
      </td>
    <td id="dato1">Nota: si en el la columna '<strong>Mezcla</strong>', aparecen las siguientes notificaciones tenga en cuenta:</td>
    </tr>
  <tr>
    <td colspan="3" id="dato1"><p><img src="images/refilado.gif" width="20" height="17" alt="O.P EXTRUIDA"title="O.P EXTRUIDA" border="0" style="cursor:hand;"/> significa que la o.p esta en proceso de Refilado</p>
      <p><img src="images/ok.gif" alt="TINTAS AGREGADAS"title="TINTAS AGREGADAS" border="0" style="cursor:hand;"/> significa que ya tiene las tintas agregados</p></td>
    <td id="dato1"><p><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /> significa que la ref. si tiene las mezclas de impresion</p>
      <p><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /> significa que la ref. no tiene las mezclas de impresion</p>
      <p><img src="images/completo.gif" alt="ROLLOS COMPLETOS"title="ROLLOS COMPLETOS" border="0" style="cursor:hand;"/> significa que ya tiene todos los rollos</p></td>
  </tr>
</table>
</form>  
<form action="delete_listado.php" method="get" name="seleccion">
<fieldset> <legend id="dato1">ORDENES DE PRODUCCION EXTRUIDAS</legend>
<table id="tabla1"> 
  <tr>
    <td colspan="2"><?php 
	    if ($var==1) {?>
        <input name="borrado" type="hidden" id="borrado" value="47"/>
        <input name="Input" type="submit" onClick="return eliminar_refilado();" value="Eliminar"/>
        <?php } ?>   
    </td>
    <td colspan="6" id="dato3"><?php $id=$_GET['id']; 
  if($id == '1') { ?> <div id="numero1"> <?php echo "CAMBIO DE ESTADO A INACTIVA COMPLETA"; ?> </div> <?php }
  if($id == '2') { ?><div id="acceso1"> <?php echo "SE ACTIVO CORRECTAMENTE"; ?> </div> <?php }
  if($id == '3') { ?> <div id="numero1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
    <td colspan="6" id="dato3"><?php  if ($row_usuario['tipo_usuario']==1) {?><a href="costos_listado_ref_xproceso_tiempos.php"><img src="images/rt.gif" alt="LISTADO REF TIEMPO X PROCESO"title="LISTADO REF TIEMPO X PROCESO" border="0" style="cursor:hand;"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;"></a><?php } ?><a href="produccion_registro_refilado_listado.php"><img src="images/opciones.gif" alt="LISTADO PARA IMPRIMIR"title="LISTADO PARA REFILAR" border="0" style="cursor:hand;"></a><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS"title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR"title="REFRESCAR" border="0" style="cursor:hand;"/></a>
    <input type="button" value="Exporta Excel" onClick="window.location = 'produccion_exportar_excel.php?tipoListado=2'" />
    </td>
  </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">ROLLO N&deg;</td>
    <td nowrap="nowrap"id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">OPERARIO</td>
    <td nowrap="nowrap"id="titulo4">AUXILIAR</td>
    <td nowrap="nowrap"id="titulo4">MEZCLA</td>
    <td nowrap="nowrap"id="titulo4">PROCESO</td>
  </tr>
  <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><?php  
	  $id_r=$row_orden_produccion['id_op_r'];
	  $fechaI=$row_orden_produccion['fechaI_r'];
	  $sqlregpro="SELECT id_rp,id_ref_rp,b_borrado_rp,fecha_ini_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_r' AND fecha_ini_rp='$fechaI' AND id_proceso_rp='2'";
	  $resultregpro= mysql_query($sqlregpro);
	  $numregpro= mysql_num_rows($resultregpro);
	  if($numregpro >='1')
	  { 
	  $id_rp = mysql_result($resultregpro, 0, 'id_rp');
	  $id_ref = mysql_result($resultregpro, 0, 'id_ref_rp');
	  $borrado = mysql_result($resultregpro, 0, 'b_borrado_rp');
	  }?><input name="borrar[]" type="checkbox" value="<?php echo $row_orden_produccion['id_r']; ?>" />   
      </td>
      <td nowrap="nowrap" id="dato2"><strong><?php echo $row_orden_produccion['id_op_r']; ?></strong></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['rollo_r']; ?></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fechaI_r']; ?></td>
      <td nowrap="nowrap" id="dato2">
        <?php 
		$op_c=$row_orden_produccion['id_op_r'];
		$sqln="SELECT nombre_c FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
		$resultn=mysql_query($sqln); 
		$numn=mysql_num_rows($resultn); 
		if($numn >= '1') 
		{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
		else { echo "";}?>
      </td>
      <td id="dato2"><?php echo $row_orden_produccion['ref_r']; ?></td>
      <td id="dato2"><?php 
	    $cod_ref=$row_orden_produccion['ref_r'];
		$query_cod = "SELECT id_ref,version_ref FROM Tbl_referencia WHERE cod_ref='$cod_ref'";
		$resultcod=mysql_query($query_cod); 
		$numcod=mysql_num_rows($resultcod); 
		if($numcod >= '1') 
		{
		$id_ref_op=mysql_result($resultcod,0,'id_ref');
		$version=mysql_result($resultcod,0,'version_ref');echo $version;}?></td>
      <td id="dato2"><?php echo $row_orden_produccion['kilos_r']; ?></td>
      <td nowrap="nowrap"id="dato2">
        <?php  
	  $id_emp=$row_orden_produccion['cod_empleado_r'];
	  $sqlemp="SELECT nombre_empleado FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }else{echo "N/A";}?>
      </td>
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_aux=$row_orden_produccion['cod_auxiliar_r'];
	  $sqlemp="SELECT nombre_empleado FROM empleado WHERE codigo_empleado='$id_aux' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre_aux = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre_aux; 
	  }else{echo "N/A";}?>
      </td>
      <td id="dato2">
	  <?php 
	  $id_ref_op=$row_orden_produccion['ref_r'];
	  $sqlop="SELECT id_ref_cp FROM Tbl_caract_proceso WHERE id_cod_ref_cp='$id_ref_op' AND id_proceso='2' ORDER BY id_ref_cp DESC LIMIT 1"; 
	  $resultop=mysql_query($sqlop); 
	  $numop=mysql_num_rows($resultop);
	  if($numop >= '1')
	  { 
	  $id_pm = mysql_result($resultop, 0, 'id_pm_cp');
	  $id_ref_pm = mysql_result($resultop, 0, 'id_ref_cp');
      $id_cod = mysql_result($resultop, 0, 'id_cod_ref_cp');
	   ?><a href="javascript:popUp('produccion_caract_impresion_vista.php?id_ref=<?php echo $id_ref_pm;?>','870','600')"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a><?php 
	  }else { ?><a href="javascript:popUp('produccion_caract_impresion_add.php?id_ref=<?php echo $id_ref_pm;?>&cod_ref=<?php echo $row_orden_produccion['ref_r'];?>','870','600')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN REFILADO" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN REFILADO" border="0" /></a>
	  <?php }?></td>
      <td nowrap="nowrap" id="dato2">
      <a href="javascript:verFoto('produccion_refilado_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op_r']; ?>','870','710')"><img src="images/refilado.gif" width="23" height="20" alt="ADD ROLLOS"title="ADD ROLLOS" border="0" style="cursor:hand;" /></a>
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
?>