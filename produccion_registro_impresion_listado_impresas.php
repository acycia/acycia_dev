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
$query_orden_produccion = "SELECT * FROM Tbl_reg_produccion WHERE id_proceso_rp = 2 ORDER BY Tbl_reg_produccion.id_op_rp DESC";
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
<li><a href="produccion_registro_impresion_listado.php">IMPRESION</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="produccion_registro_impresion_add.php" method="get" name="form1" onSubmit="if(form1.retorno_mensaje.value=='1'){return false;}else if(form1.retorno_mensaje.value=='0'){return true;}">
<table id="tabla1">
<tr>
<td colspan="4" id="titulo2">REGISTRO DE ORDENES DE PRODUCCION EN IMPRESION</td>
</tr>
<tr>
  <td id="titulo2">O.P
    <input type="text" name="id_op" required onBlur="if (form1.id_op.value) { DatosGestiones('14','id_op',form1.id_op.value); } else { alert('Debe digitar el O.P para validar su existencia en la BD'); };"><div id="resultado"><input name="retorno_mensaje" type="hidden" ></div></td>
  <td colspan="3" id="fuente2">&nbsp;</td>
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
    <td colspan="3" id="dato1"><p><img src="images/extruir.gif" width="20" height="17" alt="O.P EXTRUIDA"title="O.P EXTRUIDA" border="0" style="cursor:hand;"/> significa que la o.p esta en proceso de extrusion</p>
      <p><img src="images/imprimir.gif" width="20" height="20" alt="O.P IMPRESA"title="O.P IMPRESA" border="0" style="cursor:hand;"/> significa que la o.p esta en proceso de impresion</p></td>
    <td id="dato1"><p><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /> significa que la ref. si tiene las mezclas de impresion</p>
      <p><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /> significa que la ref. no tiene las mezclas de impresion</p></td>
  </tr>
</table>
</form>  
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
 
  <tr>
    <td colspan="2"><?php 
	    $var=$row_usuario['tipo_usuario'];
        if ($var==1) {?>
        <input name="borrado" type="hidden" id="borrado" value="eliminar_imp" />
        <input name="Input" type="submit" onClick="return eliminar_impresion();" value="Eliminar"/>
        <?php
		} if ($var!=1) {?>
        <input name="borrado" type="hidden" id="borrado" value="37" />
        <input name="Input" type="submit" value="Cambio Estado"/>
        <?php
        } ?>   
    </td>
    <td colspan="6" id="dato3"><?php $id=$_GET['id']; 
  if($id == '1') { ?> <div id="numero1"> <?php echo "CAMBIO DE ESTADO A INACTIVA COMPLETA"; ?> </div> <?php }
  if($id == '2') { ?><div id="acceso1"> <?php echo "SE ACTIVO CORRECTAMENTE"; ?> </div> <?php }
  if($id == '3') { ?> <div id="numero1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
    <td colspan="5" id="dato3"><?php  if ($row_usuario['tipo_usuario']==1) {?><a href="costos_listado_ref_xproceso.php"><img src="images/r.gif" alt="LISTADO REF KILOS X PROCESO"title="LISTADO REF KILOS X PROCESO" border="0" style="cursor:hand;"></a><?php } ?><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS"title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a>
    <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR"title="REFRESCAR" border="0" style="cursor:hand;"/></a>
    <input type="button" value="Exporta Excel" onClick="window.location = 'produccion_exportar_excel.php?tipoListado=2'" />
    </td>
  </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">DESPER.</td>
    <td nowrap="nowrap"id="titulo4">OPERARIO</td>
    <td nowrap="nowrap"id="titulo4">AUXILIAR</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
    <td nowrap="nowrap"id="titulo4">ROLLOS</td>
    <td nowrap="nowrap"id="titulo4">MEZCLA</td>
    <td nowrap="nowrap"id="titulo4">PROCESO</td>
  </tr>
  <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_orden_produccion['id_rp']; ?>" />   
      </td>
      <td nowrap="nowrap" id="dato2"><strong><?php echo $row_orden_produccion['id_op_rp']; ?></strong></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
      <td nowrap="nowrap" id="dato2">
        <?php 
		$op_c=$row_orden_produccion['id_op_rp'];
		$sqln="SELECT * FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
		$resultn=mysql_query($sqln); 
		$numn=mysql_num_rows($resultn); 
		if($numn >= '1') 
		{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
		else { echo "";	
		}?>
      </td>
      <td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['version_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_prod_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp']; if($row_orden_produccion['int_kilos_desp_rp']==''){echo "0.00";} ?></td>      
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }else{echo "N/A";}?>
      </td>
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }else{echo "N/A";}?>
      </td>
      <td  nowrap="nowrap" id="dato2">
      <?php  
	  $id_rp=$row_orden_produccion['id_rp'];
	  $sqlidrp="SELECT * FROM Tbl_reg_produccion WHERE id_rp='$id_rp' AND b_borrado_rp='1'";
	  $resultidrp= mysql_query($sqlidrp);
	  $numidrp= mysql_num_rows($resultidrp);
	  if($numidrp >='1')
	  { 
	  $idrp = mysql_result($resultidrp, 0, 'id_rp');
	  
	   ?><input name="activar[]" type="checkbox" value="<?php echo $idrp; ?>" /><a href="javascript:document.seleccion.submit()">Activar</a> <?php
	  }else{echo "Activa";
	  }?>       
      </td>
      <td id="dato2"><a href="javascript:verFoto('produccion_impresion_stiker_rollo_add.php?id_op_r=<?php echo $row_orden_produccion['id_op_rp']; ?>','870','710')"><img src="images/mas.gif" alt="ADD ROLLOS"title="ADD ROLLOS" border="0" style="cursor:hand;" /></a> </td>       
      <td id="dato2">
	  <?php 
	  $id_ref_op=$row_orden_produccion['id_ref_rp'];
	  $sqlop="SELECT * FROM Tbl_caract_proceso WHERE id_ref_cp='$id_ref_op' AND id_proceso='2' ORDER BY id_ref_cp DESC LIMIT 1"; 
	  $resultop=mysql_query($sqlop); 
	  $numop=mysql_num_rows($resultop);
	  if($numop >= '1')
	  { ?><a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref_rp'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a><?php 
	  }else { ?><a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_orden_produccion['id_ref_rp'];?>&cod_ref=<?php echo $row_orden_produccion['int_cod_ref_rp'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /></a>
	  <?php }?></td>
     
      <td nowrap="nowrap" id="dato2"><?php 
	  if($row_orden_produccion['id_proceso_rp']=='1'){?><a href="produccion_registro_impresion_add.php?id_op=<?php echo $row_orden_produccion['id_op_rp'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/extruir.gif" width="28" height="18" alt="O.P EXTRUIDA "title="O.P EXTRUIDA" border="0" style="cursor:hand;"/></a><?php 
	  } else if($row_orden_produccion['id_proceso_rp']=='2'){?><a href="produccion_registro_impresion_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref_rp'];?>&amp;id_op=<?php echo $row_orden_produccion['id_op_rp'];?>&fecha_ini_rp=<?php echo $row_orden_produccion['fecha_ini_rp'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/imprimir.gif" width="28" height="20" alt="O.P IMPRESA"title="O.P IMPRESA" border="0" style="cursor:hand;"/></a>
	  <?php }?></td>	    
    </tr>
    <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
</table>
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