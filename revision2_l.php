<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> <?php require_once('Connections/conexion1.php'); ?>
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
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$maxRows_revisiones = 20;
$pageNum_revisiones = 0;
if (isset($_GET['pageNum_revisiones'])) {
  $pageNum_revisiones = $_GET['pageNum_revisiones'];
}
$startRow_revisiones = $pageNum_revisiones * $maxRows_revisiones;
mysql_select_db($database_conexion1, $conexion1);
$id_rev = $_GET['id_rev_l'];
$id_ref = $_GET['id_ref'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($id_rev== '0' && $id_ref == '0' && $fecha == '0')
{
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina ORDER BY id_rev_l DESC";
}
//Filtra revision lleno
if($id_rev != '0' && $id_ref == '0' && $fecha == '0')
{
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE id_rev_l='$id_rev' ORDER BY id_rev_l DESC";
}
//Filtra referencia lleno
if($id_ref != '0' && $id_rev == '0' && $fecha == '0')
{
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE id_ref_rev_l='$id_ref' ORDER BY id_rev_l DESC";
}
//Filtra fecha lleno
if($fecha != '0' && $id_ref == '0' && $id_rev == '0'  )
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = $fecha + 1;
  $fecha2 = "$fecha2-01-01";
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE fecha_rev_l >= '$fecha1' and fecha_rev_l < '$fecha2' ORDER BY id_rev_l DESC";
}
//Filtra fecha y referencia lleno
if($fecha != '0' && $id_ref != '0' && $id_rev == '0'  )
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = $fecha + 1;
  $fecha2 = "$fecha2-01-01";
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE id_ref_rev_l='$id_ref' and fecha_rev_l >= '$fecha1' and fecha_rev_l < '$fecha2' ORDER BY id_rev_l DESC";
}
//Filtra revision y fecha lleno
if($id_rev != '0' && $fecha != '0' && $id_ref == '0')
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = $fecha + 1;
  $fecha2 = "$fecha2-01-01";
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE id_rev_l='$id_rev' and fecha_rev_l >= '$fecha1' and fecha_rev_l < '$fecha2' ORDER BY id_rev_l DESC";
}
//Filtra revision y referencia lleno
if($id_rev != '0' && $id_ref != '0' && $fecha == '0')
{
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE id_rev_l='$id_rev' and id_ref_rev_l='$id_ref' ORDER BY id_rev_l DESC";
}
//Filtra Todos llenos
if($id_rev != '0' && $id_ref != '0' && $fecha != '0')
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = $fecha + 1;
  $fecha2 = "$fecha2-01-01";
  $query_revisiones = "SELECT * FROM Tbl_revision_lamina WHERE id_rev_l='$id_rev' and id_ref_rev_l='$id_ref' and fecha_rev_l >= '$fecha1' and fecha_rev_l < '$fecha2' ORDER BY id_rev_l DESC";
}

$query_limit_revisiones = sprintf("%s LIMIT %d, %d", $query_revisiones, $startRow_revisiones, $maxRows_revisiones);
$revisiones = mysql_query($query_limit_revisiones, $conexion1) or die(mysql_error());
$row_revisiones = mysql_fetch_assoc($revisiones);

if (isset($_GET['totalRows_revisiones'])) {
  $totalRows_revisiones = $_GET['totalRows_revisiones'];
} else {
  $all_revisiones = mysql_query($query_revisiones);
  $totalRows_revisiones = mysql_num_rows($all_revisiones);
}
$totalPages_revisiones = ceil($totalRows_revisiones/$maxRows_revisiones)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_revision = "SELECT * FROM Tbl_revision_lamina ORDER BY id_rev_l DESC";
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_referencia WHERE tipo_bolsa_ref='LAMINA' ORDER BY cod_ref ASC";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$queryString_revisiones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_revisiones") == false && 
      stristr($param, "totalRows_revisiones") == false) {
      array_push($newParams, $param);
  }
}
if (count($newParams) != 0) {
  $queryString_revisiones = "&" . htmlentities(implode("&", $newParams));
}
}
$queryString_revisiones = sprintf("&totalRows_revisiones=%d%s", $totalRows_revisiones, $queryString_revisiones);

session_start();
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
  <form action="revision2_l.php" method="get" name="consulta"><table id="tabla1">
    <tr id="tr1">
     <td nowrap="nowrap" id="codigo">CODIGO: R2-F01</td>
     <td nowrap="nowrap" id="titulo2">PLAN DE DISEÑO Y DESARROLLO</td>
     <td nowrap="nowrap" id="codigo">VERSION: 3</td>
   </tr>
   <tr>
     <td colspan="3" id="subtitulo">LISTADO DE REVISIONES</td>
   </tr>
   <tr>
     <td colspan="3" id="fuente2"><select name="id_rev_l" id="id_rev_l">
       <option value="0" <?php if (!(strcmp(0, $_GET['id_rev_l']))) {echo "selected=\"selected\"";} ?>>REVISION</option>
       <?php
       do {  
        ?><option value="<?php echo $row_revision['id_rev_l']?>"<?php if (!(strcmp($row_revision['id_rev_l'], $_GET['id_rev_l']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revision['id_rev_l']?></option>
        <?php
      } while ($row_revision = mysql_fetch_assoc($revision));
      $rows = mysql_num_rows($revision);
      if($rows > 0) {
        mysql_data_seek($revision, 0);
        $row_revision = mysql_fetch_assoc($revision);
      }
      ?>
    </select><select name="id_ref" id="id_ref">
      <option value="0" <?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
      <?php
      do {  
        ?>
        <option value="<?php echo $row_referencia['id_ref']?>"<?php if (!(strcmp($row_referencia['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['cod_ref']?></option>
        <?php
      } while ($row_referencia = mysql_fetch_assoc($referencia));
      $rows = mysql_num_rows($referencia);
      if($rows > 0) {
        mysql_data_seek($referencia, 0);
        $row_referencia = mysql_fetch_assoc($referencia);
      }
      ?>
    </select><select name="fecha" id="fecha">
      <option value="0" <?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
      <?php
      do {  
        ?>
        <option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
        <?php
      } while ($row_ano = mysql_fetch_assoc($ano));
      $rows = mysql_num_rows($ano);
      if($rows > 0) {
        mysql_data_seek($ano, 0);
        $row_ano = mysql_fetch_assoc($ano);
      }
      ?>
    </select><input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_rev_l.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
  <table id="tabla1">
    <tr>
      <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="24" />
        <input name="Input" type="submit" value="Delete"/></td>
        <td colspan="4"><?php $id=$_GET['id']; 
        if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
        if($id == '0') { ?><div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div> <?php }?></td>
        <td id="dato3"><a href="revision_l.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="verificacion_l.php"><img src="images/v.gif" alt="VERIFICACIONES" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion_l.php"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica_l.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
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
          <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_revisiones['id_rev_l']; ?>" /></td>
          <td id="dato3"><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revisiones['id_rev_l']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_revisiones['id_rev_l']; ?></a></td>
          <td id="dato2"><?php $id_ref=$row_revisiones['id_ref_rev_l'];
          $sql2="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
          $result2=mysql_query($sql2);
          $num2=mysql_num_rows($result2);
          if ($num2 >= '1')
           {	$cod_ref=mysql_result($result2,0,'cod_ref');
         $version_ref=mysql_result($result2,0,'version_ref');
       } ?>
       <a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revisiones['id_rev_l']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $cod_ref; ?></a></td>
       <td id="dato2"><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revisiones['id_rev_l']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $version_ref; ?></a></td>
       <td id="dato2"><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revisiones['id_rev_l']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_revisiones['fecha_rev_l']; ?></a></td>
       <td id="dato1"><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revisiones['id_rev_l']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities ($row_revisiones['responsable_rev_l']);echo $cad; ?></a></td>
       <td id="dato1"><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revisiones['id_rev_l']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad2 = htmlentities ($row_revisiones['actualizado_rev_l']);echo $cad2; ?></a></td>
     </tr>
   <?php } while ($row_revisiones = mysql_fetch_assoc($revisiones)); ?>
 </table>

 <table id="tabla1">
  <tr>
    <td id="dato2"><?php if ($pageNum_revisiones > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_revisiones=%d%s", $currentPage, 0, $queryString_revisiones); ?>">Primero</a>
    <?php } // Show if not first page ?>
  </td>
  <td id="dato2"><?php if ($pageNum_revisiones > 0) { // Show if not first page ?>
    <a href="<?php printf("%s?pageNum_revisiones=%d%s", $currentPage, max(0, $pageNum_revisiones - 1), $queryString_revisiones); ?>">Anterior</a>
  <?php } // Show if not first page ?>
</td>
<td id="dato2"><?php if ($pageNum_revisiones < $totalPages_revisiones) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_revisiones=%d%s", $currentPage, min($totalPages_revisiones, $pageNum_revisiones + 1), $queryString_revisiones); ?>">Siguiente</a>
<?php } // Show if not last page ?>
</td>
<td id="dato2"><?php if ($pageNum_revisiones < $totalPages_revisiones) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_revisiones=%d%s", $currentPage, $totalPages_revisiones, $queryString_revisiones); ?>">&Uacute;ltimo</a>
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

mysql_free_result($ano);

mysql_free_result($revisiones);

mysql_free_result($revision);

mysql_free_result($referencia);
?>