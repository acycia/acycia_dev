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

$maxRows_fichas_tecnicas = 20;
$pageNum_fichas_tecnicas = 0;
if (isset($_GET['pageNum_fichas_tecnicas'])) {
  $pageNum_fichas_tecnicas = $_GET['pageNum_fichas_tecnicas'];
}
$startRow_fichas_tecnicas = $pageNum_fichas_tecnicas * $maxRows_fichas_tecnicas;

mysql_select_db($database_conexion1, $conexion1);
$query_fichas_tecnicas = "SELECT * FROM TblCostoRef ORDER BY codigo_cref ASC";
$query_limit_fichas_tecnicas = sprintf("%s LIMIT %d, %d", $query_fichas_tecnicas, $startRow_fichas_tecnicas, $maxRows_fichas_tecnicas);
$fichas_tecnicas = mysql_query($query_limit_fichas_tecnicas, $conexion1) or die(mysql_error());
$row_fichas_tecnicas = mysql_fetch_assoc($fichas_tecnicas);

if (isset($_GET['totalRows_fichas_tecnicas'])) {
  $totalRows_fichas_tecnicas = $_GET['totalRows_fichas_tecnicas'];
} else {
  $all_fichas_tecnicas = mysql_query($query_fichas_tecnicas);
  $totalRows_fichas_tecnicas = mysql_num_rows($all_fichas_tecnicas);
}
$totalPages_fichas_tecnicas = ceil($totalRows_fichas_tecnicas/$maxRows_fichas_tecnicas)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

$queryString_fichas_tecnicas = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_fichas_tecnicas") == false && 
        stristr($param, "totalRows_fichas_tecnicas") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_fichas_tecnicas = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_fichas_tecnicas = sprintf("&totalRows_fichas_tecnicas=%d%s", $totalRows_fichas_tecnicas, $queryString_fichas_tecnicas);
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
  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
 </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<form action="costo_referencia_listado2.php" method="get" name="consulta">
	<table id="tabla1">
	  <tr>
	  <td id="subtitulo">LISTADO DE COSTO POR REFERENCIA</td>
	  </tr>
	  <tr>
	  <td id="fuente2"><select name="id_ref" id="id_ref">
	    <option value="0">REF</option>
        <?php
do {  
?>
        <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?></option>
        <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
    </select>
	    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_val.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>      </td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="46" />
      <input name="Input" type="submit" value="Delete"/></td>
    <td colspan="4"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
    <td id="dato3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="costo_referencia_add.php"><img src="images/mas.gif" alt="ADD COSTO REFERENCIA" title="ADD COSTO REFERENCIA" border="0" style="cursor:hand;" /></a></td>
  </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap" id="titulo4">CODIGO</td>
    <td nowrap="nowrap" id="titulo4">DESCRIPCION</td>
    <td nowrap="nowrap" id="titulo4">UNIDAD</td>
    <td nowrap="nowrap" id="titulo4">CLIENTE</td>
    <td nowrap="nowrap" id="titulo4">COSTO</td>
    <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_fichas_tecnicas['id_cref']; ?>" /></td>
      <td id="dato3"><a href="costo_referencia_edit.php?id_cref=<?php echo $row_fichas_tecnicas['id_cref']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_fichas_tecnicas['codigo_cref'];?></a></td>
      <td id="dato2"><a href="costo_referencia_edit.php?id_cref=<?php echo $row_fichas_tecnicas['id_cref']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_fichas_tecnicas['descripcion_cref']; ?></a></td>
      <td id="dato2"><a href="costo_referencia_edit.php?id_cref=<?php echo $row_fichas_tecnicas['id_cref']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_fichas_tecnicas['unidad_cref']; ?></a></td>
      <td id="dato2"><a href="costo_referencia_edit.php?id_cref=<?php echo $row_fichas_tecnicas['id_cref']; ?>" target="_top" style="text-decoration:none; color:#000000">
      <?php $cliente=$row_fichas_tecnicas['cliente_cref'];echo $cliente;
	  /*$sql2="SELECT nombre_c FROM cliente WHERE id_c='$cliente'";
	  $result2=mysql_query($sql2);
	  $num2=mysql_num_rows($result2);
	  if ($num2 >= '1')
	  {	$nombre=mysql_result($result2,0,'nombre_c');echo $nombre;
	  }*/ ?>
      </a></td>
      <td id="dato1"><a href="costo_referencia_edit.php?id_cref=<?php echo $row_fichas_tecnicas['id_cref']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_fichas_tecnicas['costo_und_cref']; ?></a></td>
      <td id="dato1"><a href="costo_referencia_edit.php?id_cref=<?php echo $row_fichas_tecnicas['id_cref']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_fichas_tecnicas['responsable_cref']; ?></a></td>
    </tr>
    <?php } while ($row_fichas_tecnicas = mysql_fetch_assoc($fichas_tecnicas)); ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, 0, $queryString_fichas_tecnicas); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, max(0, $pageNum_fichas_tecnicas - 1), $queryString_fichas_tecnicas); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas < $totalPages_fichas_tecnicas) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, min($totalPages_fichas_tecnicas, $pageNum_fichas_tecnicas + 1), $queryString_fichas_tecnicas); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas < $totalPages_fichas_tecnicas) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, $totalPages_fichas_tecnicas, $queryString_fichas_tecnicas); ?>">&Uacute;ltimo</a>
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

mysql_free_result($fichas_tecnicas);

mysql_free_result($referencias);
?>
