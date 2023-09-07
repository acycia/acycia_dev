<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?><?php require_once('Connections/conexion1.php'); ?>
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
$query_validaciones = "SELECT * FROM validacion ORDER BY id_val DESC";
$validaciones = mysql_query($query_validaciones, $conexion1) or die(mysql_error());
$row_validaciones = mysql_fetch_assoc($validaciones);
$totalRows_validaciones = mysql_num_rows($validaciones);

mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_referencia ORDER BY cod_ref ASC";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$maxRows_validacion = 30;
$pageNum_validacion = 0;
if (isset($_GET['pageNum_validacion'])) {
  $pageNum_validacion = $_GET['pageNum_validacion'];
}
$startRow_validacion = $pageNum_validacion * $maxRows_validacion;

mysql_select_db($database_conexion1, $conexion1);
$id_val = $_GET['id_val'];
$id_ref = $_GET['id_ref'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($id_val== '0' && $id_ref == '0' && $fecha == '0')
{
  $query_validacion = "SELECT * FROM validacion ORDER BY id_val DESC";
}
//Filtra revision lleno
if($id_val!= '0' && $id_ref == '0' && $fecha == '0')
{
  $query_validacion = "SELECT * FROM validacion WHERE id_val='$id_val' ORDER BY id_val DESC";
}
//Filtra referencia lleno
if($id_val == '0' && $id_ref != '0' && $fecha == '0')
{
  $query_validacion = "SELECT * FROM validacion WHERE id_ref_val='$id_ref' ORDER BY id_val DESC";
}
//Filtra fecha lleno
if($id_val == '0' && $id_ref == '0' && $fecha != '0')
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = $fecha + 1;
  $fecha2 = "$fecha2-01-01";
  $query_validacion = "SELECT * FROM validacion WHERE fecha_val >= '$fecha1' and fecha_val < '$fecha2' ORDER BY id_val DESC";
}
$query_limit_validacion = sprintf("%s LIMIT %d, %d", $query_validacion, $startRow_validacion, $maxRows_validacion);
$validacion = mysql_query($query_limit_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);

if (isset($_GET['totalRows_validacion'])) {
  $totalRows_validacion = $_GET['totalRows_validacion'];
} else {
  $all_validacion = mysql_query($query_validacion);
  $totalRows_validacion = mysql_num_rows($all_validacion);
}
$totalPages_validacion = ceil($totalRows_validacion/$maxRows_validacion)-1;

$queryString_validacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_validacion") == false && 
      stristr($param, "totalRows_validacion") == false) {
      array_push($newParams, $param);
  }
}
if (count($newParams) != 0) {
  $queryString_validacion = "&" . htmlentities(implode("&", $newParams));
}
}
$queryString_validacion = sprintf("&totalRows_validacion=%d%s", $totalRows_validacion, $queryString_validacion);
?><html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/listado.js"></script>
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
  
  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <?php echo $conexion->header('listas'); ?>
  <form action="validacion2.php" method="get" name="consulta">
   <table class="table table-bordered table-sm">
    <tr id="tr1">
     <td nowrap="nowrap" id="codigo">CODIGO: R2-F01</td>
     <td nowrap="nowrap" id="titulo2">PLAN DE DISEÑO Y DESARROLLO</td>
     <td nowrap="nowrap" id="codigo">VERSION: 3</td>
   </tr>
   <tr>
     <td colspan="3" id="subtitulo">LISTADO DE VALIDACIONES </td>
   </tr>
   <tr>
     <td colspan="3" id="fuente2">
      <select name="id_val" id="id_val">
        <option value="0" <?php if (!(strcmp(0, $_GET['id_val']))) {echo "selected=\"selected\"";} ?>>VALIDACION</option>
        <?php
        do {  
          ?><option value="<?php echo $row_validaciones['id_val']?>"<?php if (!(strcmp($row_validaciones['id_val'], $_GET['id_val']))) {echo "selected=\"selected\"";} ?>><?php echo $row_validaciones['id_val']?></option>
          <?php
        } while ($row_validaciones = mysql_fetch_assoc($validaciones));
        $rows = mysql_num_rows($validaciones);
        if($rows > 0) {
          mysql_data_seek($validaciones, 0);
          $row_validaciones = mysql_fetch_assoc($validaciones);
        }
        ?>
      </select>
      <select name="id_ref" id="id_ref">
        <option value="0" <?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
        <?php
        do {  
          ?><option value="<?php echo $row_referencia['id_ref']?>"<?php if (!(strcmp($row_referencia['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['cod_ref']?></option>
          <?php
        } while ($row_referencia = mysql_fetch_assoc($referencia));
        $rows = mysql_num_rows($referencia);
        if($rows > 0) {
          mysql_data_seek($referencia, 0);
          $row_referencia = mysql_fetch_assoc($referencia);
        }
        ?>
      </select>
      <select name="fecha" id="fecha">
        <option value="0" <?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
        <?php
        do {  
          ?><option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
          <?php
        } while ($row_ano = mysql_fetch_assoc($ano));
        $rows = mysql_num_rows($ano);
        if($rows > 0) {
          mysql_data_seek($ano, 0);
          $row_ano = mysql_fetch_assoc($ano);
        }
        ?>
      </select>
      <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_val.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
    </td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
  <table class="table table-bordered table-sm">
    <tr>
      <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="7" />
        <input name="Input" type="submit" value="Delete"/></td>
        <td colspan="4"><?php $id=$_GET['id']; 
        if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
        if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
        <td id="dato3"><a href="validacion.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/v.gif" alt="VERIFICACIONES" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
      </tr>  
      <tr id="tr1">
        <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
        <td nowrap="nowrap" id="titulo4">N°</td>
        <td nowrap="nowrap" id="titulo4">REFERENCIA</td>
        <td nowrap="nowrap" id="titulo4">VERSION</td>
        <td nowrap="nowrap" id="titulo4">FECHA</td>
        <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
        <td nowrap="nowrap" id="titulo4">ACTUALIZACION</td>
      </tr>
      <?php do { ?>
        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
          <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_validacion['id_val']; ?>" /></td>
          <td id="dato3"><a href="validacion_vista.php?id_val= <?php echo $row_validacion['id_val']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_validacion['id_val']; ?></a></td>
          <td id="dato2"><?php $id_ref=$row_validacion['id_ref_val'];
          $sql2="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
          $result2=mysql_query($sql2);
          $num2=mysql_num_rows($result2);
          if ($num2 >= '1')
           {	$cod_ref=mysql_result($result2,0,'cod_ref');
         $version_ref=mysql_result($result2,0,'version_ref');
       } ?><a href="validacion_vista.php?id_val= <?php echo $row_validacion['id_val']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $cod_ref; ?></a></td>
       <td id="dato2"><a href="validacion_vista.php?id_val= <?php echo $row_validacion['id_val']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $version_ref; ?></a></td>
       <td id="dato2"><a href="validacion_vista.php?id_val= <?php echo $row_validacion['id_val']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_validacion['fecha_val']; ?></a></td>
       <td id="dato1"><a href="validacion_vista.php?id_val= <?php echo $row_validacion['id_val']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities( $row_validacion['responsable_val']);echo $cad; ?></a></td>
       <td id="dato1"><a href="validacion_vista.php?id_val= <?php echo $row_validacion['id_val']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad2 = htmlentities ($row_validacion['actualizado_val']);echo $cad; ?></a></td>
     </tr>
   <?php } while ($row_validacion = mysql_fetch_assoc($validacion)); ?>
 </table>


 <table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_validacion > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_validacion=%d%s", $currentPage, 0, $queryString_validacion); ?>">Primero</a>
    <?php } // Show if not first page ?>
  </td>
  <td width="31%" id="dato2"><?php if ($pageNum_validacion > 0) { // Show if not first page ?>
    <a href="<?php printf("%s?pageNum_validacion=%d%s", $currentPage, max(0, $pageNum_validacion - 1), $queryString_validacion); ?>">Anterior</a>
  <?php } // Show if not first page ?>
</td>
<td width="23%" id="dato2"><?php if ($pageNum_validacion < $totalPages_validacion) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_validacion=%d%s", $currentPage, min($totalPages_validacion, $pageNum_validacion + 1), $queryString_validacion); ?>">Siguiente</a>
<?php } // Show if not last page ?>
</td>
<td width="23%" id="dato2"><?php if ($pageNum_validacion < $totalPages_validacion) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_validacion=%d%s", $currentPage, $totalPages_validacion, $queryString_validacion); ?>">&Uacute;ltimo</a>
<?php } // Show if not last page ?>
</td>
</tr>
</table>
</form>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($validaciones);

mysql_free_result($referencia);

mysql_free_result($ano);

mysql_free_result($validacion);
?>
