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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_refcliente = 20;
$pageNum_refcliente = 0;
if (isset($_GET['pageNum_refcliente'])) {
  $pageNum_refcliente = $_GET['pageNum_refcliente'];
}
$startRow_refcliente = $pageNum_refcliente * $maxRows_refcliente;

mysql_select_db($database_conexion1, $conexion1);
$query_refcliente = "SELECT * FROM Tbl_refcliente  ORDER BY id_refcliente DESC";
$query_limit_refcliente = sprintf("%s LIMIT %d, %d", $query_refcliente, $startRow_refcliente, $maxRows_refcliente);
$refcliente = mysql_query($query_limit_refcliente, $conexion1) or die(mysql_error());
$row_refcliente = mysql_fetch_assoc($refcliente);

if (isset($_GET['totalRows_refcliente'])) {
  $totalRows_refcliente = $_GET['totalRows_refcliente'];
} else {
  $all_refcliente = mysql_query($query_refcliente);
  $totalRows_refcliente = mysql_num_rows($all_refcliente);
}
$totalPages_refcliente = ceil($totalRows_refcliente/$maxRows_refcliente)-1;

$queryString_refcliente = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_refcliente") == false && 
      stristr($param, "totalRows_refcliente") == false) {
      array_push($newParams, $param);
  }
}
if (count($newParams) != 0) {
  $queryString_refcliente = "&" . htmlentities(implode("&", $newParams));
}
}
$queryString_refcliente = sprintf("&totalRows_refcliente=%d%s", $totalRows_refcliente, $queryString_refcliente);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_numero = "SELECT DISTINCT int_ref_ac_rc FROM Tbl_refcliente ORDER BY int_ref_ac_rc DESC";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
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
  <form action="ref_ac_ref_cl_listado2.php" method="get" name="consulta">
    <table class="table table-bordered table-sm">
      <tr>
        <td nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
        <td nowrap="nowrap" id="titulo2" width="50%">LISTADO DE REF AC Y REF CL</td>
        <td nowrap="nowrap" id="codigo" width="25%">VERSION : 0</td>
      </tr>
      <tr>
        <td colspan="3" id="fuente2"><select class="busqueda selectsMini" name="ref_ac" id="ref_ac">
          <option value="0">Seleccione la Referencia</option>
          <?php
          do {  
            ?><option value="<?php echo $row_numero['int_ref_ac_rc']?>"><?php echo $row_numero['int_ref_ac_rc']?></option>
            <?php
          } while ($row_numero = mysql_fetch_assoc($numero));
          $rows = mysql_num_rows($numero);
          if($rows > 0) {
            mysql_data_seek($numero, 0);
            $row_numero = mysql_fetch_assoc($numero);
          }
          ?>
        </select>
        <select class="busqueda selectsMini" name="id_c" id="id_c"style="width:350px">
          <option value="0">Seleccione el Cliente</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_cliente['id_c']?>"><?php $cad = ($row_cliente['nombre_c']); echo $cad;?></option>
            <?php
          } while ($row_cliente = mysql_fetch_assoc($cliente));
          $rows = mysql_num_rows($cliente);
          if($rows > 0) {
            mysql_data_seek($cliente, 0);
            $row_cliente = mysql_fetch_assoc($cliente);
          }
          ?>
        </select>    
        <input type="submit" name="Submit" value="FILTRO" onclick="if(consulta.ref_ac.value=='0' && consulta.id_c.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
      </tr>
    </table>
  </form>
  <form action="delete_listado.php" method="get" name="seleccion">
    <table class="table table-bordered table-sm">
      <tr>
        <td colspan="2"><input name="borrado" type="hidden" id="borrado" value="40" />
          <input name="Input" type="submit" value="Delete"/></td>
          <td colspan="3"><?php $id=$_GET['id'];
          if($id >= '1') { ?> <div id="acceso1"> <?php echo "SE ELIMINO CORRECTAMENTE"; ?> </div> <?php }
          if($id == '0') { ?><div id="numero1"> <?php echo "ERROR NO SE PUDO ELIMINAR"; ?> </div><?php } 
          ?></td>
          <td colspan="8" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?>
            <a href="ref_ac_ref_cliente_add.php"><img src="images/mas.gif" alt="ADD REF AC REF CLIENTE" title="ADD REF AC REF CLIENTE" border="0" style="cursor:hand;"/></a><?php } ?>
            <a href="menu.php"><img src="images/identico.gif" alt="MENU" title="MENU" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
          </tr>  
          <tr id="tr1">
            <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
            <td nowrap="nowrap" id="titulo4">REF AC</td>
            <td nowrap="nowrap" id="titulo4">VERS</td>
            <td nowrap="nowrap" id="titulo4">REF CLIENTE</td>
            <td nowrap="nowrap" id="titulo4">CLIENTE</td>
            <td nowrap="nowrap" id="titulo4">DESCRIPCION</td>
            <td nowrap="nowrap" id="titulo4">FECHA</td>
            <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
            <td nowrap="nowrap" id="titulo4">ESTADO</td>
          </tr>
          <?php do { ?>
            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
              <td id="dato2"><?php if($row_refcliente['id_refcliente']!='') { ?><input name="refcliente[]" type="checkbox" value="<?php echo $row_refcliente['id_refcliente']; ?>" /><?php } ?></td>
              <td id="dato2"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_refcliente['int_ref_ac_rc']; ?></a></td>    
              <td id="dato2"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_refcliente['version_ref']; ?></a></td> 
              <td id="dato1"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_refcliente['str_ref_cl_rc']; ?></a></td> 
              <td id="dato1"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000">
                <?php 
                $id_c_rc=$row_refcliente['id_c_rc'];
                $sqln="SELECT * FROM cliente WHERE id_c='$id_c_rc'"; 
                $resultn=mysql_query($sqln); 
                $numn=mysql_num_rows($resultn); 
                if($numn >= '1') 
                 { $nombre_c=mysql_result($resultn,0,'nombre_c'); $ca = htmlentities ($nombre_c);echo $ca; }
               else { echo "";	} ?>
             </a></td>  
             <td id="dato1"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_refcliente['str_descripcion_rc']; ?></a></td>  
             <td id="dato2"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_refcliente['fecha_rc']; ?></a></td>

             <td id="dato2"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_refcliente['str_responsable_rc']; ?></a></td>   
             <td id="dato2"><a href="ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($row_refcliente['int_estado_ref_rc']=='1'){echo "ACTIVO";} else{echo"INACTIVO";} ?></a></td>          
           </tr>
         <?php } while ($row_refcliente = mysql_fetch_assoc($refcliente)); ?>
       </table>
       <table id="tabla1">
        <tr>
          <td id="dato1" width="25%"><?php if ($pageNum_refcliente > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_refcliente=%d%s", $currentPage, 0, $queryString_refcliente); ?>">Primero</a>
          <?php } // Show if not first page ?>
        </td>
        <td id="dato1" width="25%"><?php if ($pageNum_refcliente > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_refcliente=%d%s", $currentPage, max(0, $pageNum_refcliente - 1), $queryString_refcliente); ?>">Anterior</a>
        <?php } // Show if not first page ?>
      </td>
      <td id="dato1" width="25%"><?php if ($pageNum_refcliente < $totalPages_refcliente) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_refcliente=%d%s", $currentPage, min($totalPages_refcliente, $pageNum_refcliente + 1), $queryString_refcliente); ?>">Siguiente</a>
      <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_refcliente < $totalPages_refcliente) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_refcliente=%d%s", $currentPage, $totalPages_refcliente, $queryString_refcliente); ?>">&Uacute;ltimo</a>
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

mysql_free_result($refcliente);

mysql_free_result($numero);

?>