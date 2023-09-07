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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
/*    if ((isset($_POST["id_op"])) && ($_POST["id_op"] != "")) {
	$id_op_ex=$_POST["id_op"];
	$sqlop="SELECT * FROM Tbl_orden_produccion WHERE id_op='$id_op_ex'"; 
	$resultop=mysql_query($sqlop); 
	$numop=mysql_num_rows($resultop); 
	if($numop >= '1') 
	{
 $insertGoTo = "produccion_registro_extrusion_add.php?id_op=" . $_POST['id_op'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 

	 }
	else {
		$insertGoTo = "produccion_registro_extrusion_listado.php?id_op=" . $_POST['id_op'] . ""; 
	    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 

	 
}*/

$conexion = new ApptivaDB();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$maxRows_orden_produccion = 20;
$pageNum_orden_produccion = 0;
if (isset($_GET['pageNum_orden_produccion'])) {
  $pageNum_orden_produccion = $_GET['pageNum_orden_produccion'];
}
$startRow_orden_produccion = $pageNum_orden_produccion * $maxRows_orden_produccion;

mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = "SELECT * FROM Tbl_orden_produccion WHERE b_borrado_op='1' ORDER BY id_op DESC";
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
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>
</head>
<body onload = "JavaScript: AutoRefresh (80000);"><div align="center">
 
    <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div align="center">
    <table style="width: 80%"><!-- id="tabla1" -->
      <tr>
       <td align="center">
         <div class="row-fluid">
           <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
             <div class="panel panel-primary">
              <div class="panel-heading" align="left" ></div><!--color azul-->
              <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
              </div>
              <div class="panel-heading" align="left" ></div><!--color azul-->
               <div id="cabezamenu">
                <ul id="menuhorizontal">
                  <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                </ul>
             </div> 
             <div class="panel-body">
               <br> 
               <div class="container">
                <div class="row">
                  <div class="span12"> 
             </div>
           </div>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
<td id="titulo2" colspan="7">LISTADO DE  ORDENES DE PRODUCCION EN EXTRUSION </td>
</tr>
  <tr>
    <td colspan="2" id="dato1"><input name="usuario" type="hidden" id="usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
      <input name="borrado" type="hidden" id="borrado" value="35" />
      <input class="botonUpdate" name="Input" type="submit" value="Activar"/></td>
    <td colspan="3" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "SE REACTIVO CORRECTAMENTE"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
    <td colspan="2" id="dato3"><a href="produccion_op_interna.php"><img src="images/mas_r.gif" alt="ADD O.P INTERNA" title="ADD O.P INTERNA" border="0" style="cursor:hand;"/></a><a href="produccion_op_add.php"><img src="images/mas.gif" alt="ADD O.P" title="ADD O.P" border="0" style="cursor:hand;"/></a><a href="produccion_ordenes_produccion_listado.php" target="_top"><img src="images/a.gif" alt="O.P. ACTIVAS"title="O.P ACTIVAS" border="0" style="cursor:hand;"/></a><a href="produccion_op_estados.php"><img src="images/p.gif" style="cursor:hand;" alt="LISTADO PROGRAMADAS" title="LISTADO PROGRAMADAS" border="0" /></a><a href="produccion_op_ordenconsultar.php"><img src="images/accept.png" style="cursor:hand;" alt="O.P FINALIZADAS" title="O.P FINALIZADAS" border="0" /></a> <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    </tr>
  <tr>
    <td colspan="2" id="dato4">&nbsp;</td>
    <td colspan="3" id="dato4">&nbsp;</td>
    <td colspan="2" id="dato5">&nbsp;</td>
  </tr>  
  <tr id="tr1">
      <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">RESPONSABLE</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_orden_produccion['id_op'];?>" /></td>
      <td nowrap="nowrap"id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_orden_produccion['id_op']; ?></strong></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['fecha_registro_op']; ?></a></td>
      <td nowrap="nowrap" id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
	$nit_c=$row_orden_produccion['int_cliente_op'];
	$sqln="SELECT * FROM cliente WHERE id_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
	else { echo "";	} ?>
      </a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['int_cod_ref_op']; ?></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['version_ref_op']; ?></a></td>
      <td nowrap="nowrap"id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['str_responsable_op']; ?></a></td>
      <td id="dato2">
	<?php 
	$id_op=$row_orden_produccion['id_op'];
	$sqlop="SELECT * FROM Tbl_orden_produccion, Tbl_caract_proceso WHERE Tbl_orden_produccion.id_op='$id_op' AND  Tbl_orden_produccion.int_cod_ref_op=Tbl_caract_proceso.id_cod_ref_cp AND Tbl_caract_proceso.id_proceso='1' AND Tbl_orden_produccion.b_borrado_op='0'"; 
	$resultop=mysql_query($sqlop); 
	$numop=mysql_num_rows($resultop);			
	if($numop >= '1'){		
	$estado = mysql_result($resultop, 0, 'b_estado_op');
	$id_ref_op = mysql_result($resultop, 0, 'id_ref_op'); 
	}else { ?>
      <?php 
	$id_ref_op=$row_orden_produccion['id_ref_op'];
	$sqlm="SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_ref_pm='$id_ref_op'"; 
	$resultm=mysql_query($sqlm); 
	$numm=mysql_num_rows($resultm);			
	if($numm >= '1'){		
	$id_pm = mysql_result($resultm, 0, 'id_pm'); 
	?>    
    
    <a href="produccion_caract_extrusion_add.php?id_pm=<?php echo $id_pm;?>&amp;id_ref=<?php echo $id_ref_op;?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/e.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUSION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUSION" border="0" /></a><?php } }
    if ($numop >= '1' && $estado=='0') 
	{?><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/falta.gif" alt="O.P INGRESADA "title="O.P INGRESADA" border="0" style="cursor:hand;"/></a><?php }
	else if ($numop >= '1' && $estado=='1') 
	{?><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/extruir.gif" width="28" height="18"alt="O.P EXTRUIDA "title="O.P EXTRUIDA" border="0" style="cursor:hand;"/></a><?php }
	else if ($numop >= '1' && $estado=='2') 
	{?><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/imprimir.gif" width="28" height="20" alt="O.P IMPRESA"title="O.P IMPRESA" border="0" style="cursor:hand;"/></a><?php }	 
	else if ($numop >= '1' && $estado=='3') 
	{?><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/refilado.gif" width="28" height="20" alt="O.P REFILADO"title="O.P REFILADO" border="0" style="cursor:hand;"/></a><?php }		
	else if ($numop >= '1' && $estado=='4') 
	{?><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/sellar.gif" width="28" height="20" alt="O.P SELLADO"title="O.P SELLADO" border="0" style="cursor:hand;"/></a><?php }	
	else{echo "";}
	 ?></td>
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
</table>
    </td>
   </tr> 
 </div> <!-- contenedor -->

  </div>
 </div>
 </div>
 </div>
 </td>
 </tr>
 </table> 
 </div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_produccion);

mysql_close($conexion1);
?>