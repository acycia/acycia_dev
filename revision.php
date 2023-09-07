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
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_ano = $conexion->llenaSelect('anual',"","ORDER BY anual DESC"); 

$row_revision = $conexion->llenaSelect('revision',"","ORDER BY id_rev DESC");

$row_referencia = $conexion->llenaSelect('Tbl_referencia',"","ORDER BY cod_ref ASC");


$maxRows_revisiones = 20;
$pageNum_revisiones = 0;
if (isset($_GET['pageNum_revisiones'])) {
  $pageNum_revisiones = $_GET['pageNum_revisiones'];
}
$startRow_revisiones = $pageNum_revisiones * $maxRows_revisiones;

mysql_select_db($database_conexion1, $conexion1);
$query_revisiones = "SELECT * FROM revision ORDER BY id_rev DESC";
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

                   <form action="revision2.php" method="get" name="consulta">
                    <table class="table table-bordered table-sm">
                      <tr id="tr1">
                       <td nowrap="nowrap" id="codigo">CODIGO: R2-F01</td>
                       <td nowrap="nowrap" id="titulo2">PLAN DE DISEÑO Y DESARROLLO</td>
                       <td nowrap="nowrap" id="codigo">VERSION: 3</td>
                     </tr>
                     <tr>
                       <td colspan="3" id="subtitulo">LISTADO DE REVISIONES</td>
                     </tr>
                     <tr>
                       <td colspan="3" id="fuente2">
                        <select class="busqueda selectsMini" name="id_rev" id="id_rev">
                           <option value="0">REVISION</option>
                           <?php  foreach($row_revision as $row_revision ) { ?>
                            <option value="<?php echo $row_revision['id_rev']?>"><?php echo $row_revision['id_rev']?></option>
                          <?php } ?>
                        </select>
                        
                        <select class="busqueda selectsMini" name="id_ref" id="id_ref">
                           <option value="0">REF</option>
                           <?php  foreach($row_referencia as $row_referencia ) { ?>
                            <option value="<?php echo $row_referencia['id_ref']?>"><?php echo $row_referencia['cod_ref']?></option>
                          <?php } ?>
                        </select>

                        <select class="busqueda selectsMini" name="fecha" id="fecha">
                           <option value="0">ANUAL</option>
                           <?php  foreach($row_ano as $row_ano ) { ?>
                            <option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
                          <?php } ?>
                        </select>
                         
                        <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_rev.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
                      </td>
                    </tr>
                  </table>
                </form>
                <form action="delete_listado.php" method="get" name="seleccion">
                  <table class="table table-bordered table-sm">
                    <tr>
                      <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="3" />
                        <input name="Input" type="submit" value="Delete"/></td>
                        <td colspan="4"><?php $id=$_GET['id']; 
                        if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
                        if($id == '0') { ?><div id="numero1"> <?php echo "No se ha seleccionado"; ?> </div> <?php }?></td>
                        <td id="dato3"><a href="revision.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="verificacion.php"><img src="images/v.gif" alt="VERIFICACIONES" title="VERIFICACION" border="0" style="cursor:hand;" /></a><a href="validacion.php"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACION" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a></td>
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
                          <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_revisiones['id_rev']; ?>" /></td>
                          <td id="dato3"><a href="revision_vista.php?id_rev= <?php echo $row_revisiones['id_rev']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_revisiones['id_rev']; ?></a></td>
                          <td id="dato2"><?php $id_ref=$row_revisiones['id_ref_rev'];
                          $sql2="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
                          $result2=mysql_query($sql2);
                          $num2=mysql_num_rows($result2);
                          if ($num2 >= '1')
                           {	$cod_ref=mysql_result($result2,0,'cod_ref');
                         $version_ref=mysql_result($result2,0,'version_ref');
                       } ?><a href="revision_vista.php?id_rev= <?php echo $row_revisiones['id_rev']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $cod_ref; ?></a></td>
                       <td id="dato2"><a href="revision_vista.php?id_rev= <?php echo $row_revisiones['id_rev']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $version_ref; ?></a></td>
                       <td id="dato2"><a href="revision_vista.php?id_rev= <?php echo $row_revisiones['id_rev']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_revisiones['fecha_rev']; ?></a></td>
                       <td id="dato1"><a href="revision_vista.php?id_rev= <?php echo $row_revisiones['id_rev']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities($row_revisiones['responsable_rev']); echo $cad; ?></a></td>
                       <td id="dato1"><a href="revision_vista.php?id_rev= <?php echo $row_revisiones['id_rev']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad2 = htmlentities ($row_revisiones['actualizado_rev']);echo $cad2; ?></a></td>
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