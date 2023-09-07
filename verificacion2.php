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

$row_verificacion = $conexion->llenaSelect('verificacion',"","ORDER BY id_verif DESC");

$row_referencia = $conexion->llenaSelect('Tbl_referencia',"","ORDER BY cod_ref ASC");

$row_artes = $conexion->llenaSelect('verificacion',"WHERE userfile <> ''","ORDER BY userfile ASC"); 


$maxRows_verificaciones = 20;
$pageNum_verificaciones = 0;
if (isset($_GET['pageNum_verificaciones'])) {
  $pageNum_verificaciones = $_GET['pageNum_verificaciones'];
}
$startRow_verificaciones = $pageNum_verificaciones * $maxRows_verificaciones;

mysql_select_db($database_conexion1, $conexion1);

$id_verif = $_GET['id_verif'];
$arte = $_GET['arte'];
$id_ref = $_GET['id_ref'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($id_verif == '0' && $arte == '0' && $id_ref == '0' && $fecha == '0')
{
  $query_verificaciones = "SELECT * FROM verificacion ORDER BY id_verif DESC";
}
//Filtra verificacion lleno
if($id_verif != '0' && $arte == '0' && $id_ref == '0' && $fecha == '0')
{
  $query_verificaciones = "SELECT * FROM verificacion WHERE id_verif='$id_verif' ORDER BY id_verif DESC";
}
//Filtra arte lleno
if($id_verif == '0' && $arte != '0' && $id_ref == '0' && $fecha == '0')
{
  $query_verificaciones = "SELECT * FROM verificacion WHERE userfile='$arte' ORDER BY id_verif DESC";
}
//Filtra referencia lleno
if($id_verif == '0' && $arte == '0' && $id_ref != '0' && $fecha == '0')
{
  $query_verificaciones = "SELECT * FROM verificacion WHERE id_ref_verif='$id_ref' ORDER BY id_verif DESC";
}
//Filtra fecha lleno
if($id_verif == '0' && $arte == '0' && $id_ref == '0' && $fecha != '0')
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = $fecha + 1;
  $fecha2 = "$fecha2-01-01";
  $query_verificaciones = "SELECT * FROM verificacion WHERE fecha_verif >= '$fecha1' and fecha_verif < '$fecha2' ORDER BY id_verif DESC";
}

$query_limit_verificaciones = sprintf("%s LIMIT %d, %d", $query_verificaciones, $startRow_verificaciones, $maxRows_verificaciones);
$verificaciones = mysql_query($query_limit_verificaciones, $conexion1) or die(mysql_error());
$row_verificaciones = mysql_fetch_assoc($verificaciones);

if (isset($_GET['totalRows_verificaciones'])) {
  $totalRows_verificaciones = $_GET['totalRows_verificaciones'];
} else {
  $all_verificaciones = mysql_query($query_verificaciones);
  $totalRows_verificaciones = mysql_num_rows($all_verificaciones);
}
$totalPages_verificaciones = ceil($totalRows_verificaciones/$maxRows_verificaciones)-1;

  
$queryString_verificaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_verificaciones") == false && 
      stristr($param, "totalRows_verificaciones") == false) {
      array_push($newParams, $param);
  }
}
if (count($newParams) != 0) {
  $queryString_verificaciones = "&" . htmlentities(implode("&", $newParams));
}
}
$queryString_verificaciones = sprintf("&totalRows_verificaciones=%d%s", $totalRows_verificaciones, $queryString_verificaciones);

session_start();
?>
<html>
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
                   <form action="verificacion2.php" method="get" name="consulta">
                     <table class="table table-bordered table-sm">
                      <tr id="tr1">
                       <td nowrap="nowrap" id="codigo">CODIGO: R2-F01</td>
                       <td nowrap="nowrap" id="titulo2">PLAN DE DISEÑO Y DESARROLLO</td>
                       <td nowrap="nowrap" id="codigo">VERSION: 3</td>
                     </tr>
                     <tr>
                       <td colspan="3" id="subtitulo">LISTADO DE VERIFICACIONES </td>
                     </tr>
                     <tr>
                       <td colspan="3" id="fuente2">
                        <select class="busqueda selectsMini" name="id_verif" id="id_verif">
                           <option value="0" <?php if (!(strcmp(0, $_GET['id_verif']))) {echo "selected=\"selected\"";} ?>>VERIF</option>
                           <?php  foreach($row_verificacion as $row_verificacion ) { ?>
                            <option value="<?php echo $row_verificacion['id_verif']?>"<?php if (!(strcmp($row_verificacion['id_verif'], $_GET['id_verif']))) {echo "selected=\"selected\"";} ?>><?php echo $row_verificacion['id_verif']?></option>
                          <?php } ?>
                        </select>

                         <select class="busqueda selectsMini" name="arte" id="arte">
                          <option value="0" <?php if (!(strcmp(0, $_GET['arte']))) {echo "selected=\"selected\"";} ?>>ARTE</option>
                            <?php  foreach($row_artes as $row_artes ) { ?>
                             <option value="<?php echo $row_artes['userfile']?>"<?php if (!(strcmp($row_artes['userfile'], $_GET['userfile']))) {echo "selected=\"selected\"";} ?>><?php echo $row_artes['userfile']?></option>
                           <?php } ?>
                         </select>

                         <select class="busqueda selectsMini" name="id_ref" id="id_ref">
                          <option value="0" <?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
                            <?php  foreach($row_referencia as $row_referencia ) { ?>
                             <option value="<?php echo $row_referencia['id_ref']?>"<?php if (!(strcmp($row_referencia['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['cod_ref']?></option>
                           <?php } ?>
                         </select> 
                         
                         <select class="busqueda selectsMini" name="fecha" id="fecha">
                          <option value="0" <?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
                            <?php  foreach($row_ano as $row_ano ) { ?>
                             <option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
                           <?php } ?>
                         </select>


                        
                      <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_verif.value=='0' && consulta.arte.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
                    </td>
                  </tr>
                </table>
              </form>
              <form action="delete_listado.php" method="get" name="seleccion">
                <table class="table table-bordered table-sm">
                  <tr>
                    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="5" />
                      <input name="Input" type="submit" value="Delete"/></td>
                      <td colspan="3"><?php $id=$_GET['id']; 
                      if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
                      if($id == '0') { ?><div id="numero1"> <?php echo "SELECCIONE UN REGISTRO"; ?> </div> <?php }?></td>
                      <td colspan="5" id="dato3"><a href="control_modificaciones.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" border="0" style="cursor:hand;" /></a><a href="validacion.php"><img src="images/v.gif" alt="VALIDACIONES" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
                    </tr>  
                    <tr id="tr1">
                      <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                      <td nowrap="nowrap" id="titulo4">Nro</td>
                      <td nowrap="nowrap" id="titulo4">REF</td>
                      <td nowrap="nowrap" id="titulo4">FECHA</td>
                      <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
                      <td nowrap="nowrap" id="titulo4">ARTE</td>
                      <td nowrap="nowrap" id="titulo4">FECHA ARTE </td>
                      <td nowrap="nowrap" id="titulo4">ESTADO</td>
                      <td nowrap="nowrap" id="titulo4">CIREL</td>
                      <td nowrap="nowrap" id="titulo4">C.M.</td>
                    </tr>
                    <?php do { ?>
                      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_verificaciones['id_verif']; ?>" /></td>
                        <td id="dato3"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificaciones['id_verif']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones['id_verif']; ?></a></td>
                        <td id="dato2"><?php $id_ref=$row_verificaciones['id_ref_verif'];
                        $sql2="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
                        $result2=mysql_query($sql2);
                        $num2=mysql_num_rows($result2);
                        if ($num2 >= '1')
                         {	$cod_ref=mysql_result($result2,0,'cod_ref');
                       $version_ref=mysql_result($result2,0,'version_ref');
                     } ?><a href="referencia_vista.php?id_ref= <?php echo $row_verificaciones['id_ref_verif']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $cod_ref."-".$version_ref; ?></a></td>
                     <td nowrap id="dato2"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificaciones['id_verif']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones['fecha_verif']; ?></a></td>
                     <td id="dato1"><a href="verificacion_vista.php?id_verif= <?php echo $row_verificaciones['id_verif']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities ($row_verificaciones['responsable_verif']);echo $cad; ?></a></td>
                     <td id="dato2"><?php if($row_verificaciones['id_verif']!='') { $muestra = $row_verificaciones['userfile']; ?> <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"><img src="images/arte.gif" alt="<?php echo $muestra;?>" title="ARTE" border="0" style="cursor:hand;"  /></a><?php } ?></td>
                     <td nowrap id="dato2"><?php echo $row_verificaciones['fecha_aprob_arte_verif']; ?></td>
                     <td id="dato2"><?php if($row_verificaciones['estado_arte_verif'] == '0') { echo "Pendiente"; } if($row_verificaciones['estado_arte_verif'] == '1') { echo "Rechazado"; } if($row_verificaciones['estado_arte_verif'] == '2') { echo "Aceptado"; } if($row_verificaciones['estado_arte_verif'] == '3') { echo "Anulado"; } ?>	  
                   </td>
                   <td nowrap id="dato2"><?php if($row_verificaciones['fecha_entrega_cirel'] == '0000-00-00') { echo ""; } else { echo $row_verificaciones['fecha_entrega_cirel']; } ?></td>
                   <td id="dato2"><?php $id_verif=$row_verificaciones['id_verif']; $sqlcm="SELECT * FROM control_modificaciones WHERE id_verif_cm='$id_verif'";
                   $resultcm= mysql_query($sqlcm);
                   $row_cm = mysql_fetch_assoc($resultcm);
                   $numcm= mysql_num_rows($resultcm);
                   if($numcm >='1')
                   { 
                     $cm = mysql_result($resultcm, 0, 'id_cm'); ?><a href="control_modif_edit.php?id_cm=<?php echo $cm; ?>"><img src="images/m.gif" alt="EDIT MODIFICACION" title="EDIT MODIFICACION" border="0" style="cursor:hand;"/></a><?php } ?></td>
                   </tr>
                 <?php } while ($row_verificaciones = mysql_fetch_assoc($verificaciones)); ?>
               </table>
               <table id="tabla1">
                <tr>
                  <td id="dato2"><?php if ($pageNum_verificaciones > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, 0, $queryString_verificaciones); ?>">Primero</a>
                  <?php } // Show if not first page ?>
                </td>
                <td id="dato2"><?php if ($pageNum_verificaciones > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, max(0, $pageNum_verificaciones - 1), $queryString_verificaciones); ?>">Anterior</a>
                <?php } // Show if not first page ?>
              </td>
              <td id="dato2"><?php if ($pageNum_verificaciones < $totalPages_verificaciones) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, min($totalPages_verificaciones, $pageNum_verificaciones + 1), $queryString_verificaciones); ?>">Siguiente</a>
              <?php } // Show if not last page ?>
            </td>
            <td id="dato2"><?php if ($pageNum_verificaciones < $totalPages_verificaciones) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, $totalPages_verificaciones, $queryString_verificaciones); ?>">&Uacute;ltimo</a>
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

mysql_free_result($verificaciones);

mysql_free_result($verificacion);

mysql_free_result($artes);

mysql_free_result($referencia);
?>