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

$row_id_pm = $conexion->llenaSelect('Tbl_produccion_mezclas',"","ORDER BY int_cod_ref_pm DESC");  

$maxRows_mezclas = 20;
$pageNum_mezclas = 0;
if (isset($_GET['pageNum_mezclas'])) {
  $pageNum_mezclas = $_GET['pageNum_mezclas'];
}
$startRow_mezclas = $pageNum_mezclas * $maxRows_mezclas;

mysql_select_db($database_conexion1, $conexion1);
$query_mezclas = "SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_pm ORDER BY int_cod_ref_pm DESC";
$query_limit_mezclas = sprintf("%s LIMIT %d, %d", $query_mezclas, $startRow_mezclas, $maxRows_mezclas);
$mezclas = mysql_query($query_limit_mezclas, $conexion1) or die(mysql_error());
$row_mezclas = mysql_fetch_assoc($mezclas);

if (isset($_GET['totalRows_mezclas'])) {
  $totalRows_mezclas = $_GET['totalRows_mezclas'];
} else {
  $all_mezclas = mysql_query($query_mezclas);
  $totalRows_mezclas = mysql_num_rows($all_mezclas);
}
$totalPages_mezclas = ceil($totalRows_mezclas/$maxRows_mezclas)-1;



 
$queryString_mezclas = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_mezclas") == false && 
      stristr($param, "totalRows_mezclas") == false) {
      array_push($newParams, $param);
  }
}
if (count($newParams) != 0) {
  $queryString_mezclas = "&" . htmlentities(implode("&", $newParams));
}
}
$queryString_mezclas = sprintf("&totalRows_mezclas=%d%s", $totalRows_mezclas, $queryString_mezclas);

session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
  <form action="produccion_mezclas2.php" method="get" name="consulta">
   <table class="table table-bordered table-sm">
    <tr id="tr1">
     <td nowrap="nowrap" id="codigo">CODIGO: R2-F01</td>
     <td nowrap="nowrap" id="titulo2">PRODUCCION</td>
     <td nowrap="nowrap" id="codigo">VERSION: 0</td>
   </tr>
   <tr>
     <td colspan="3" id="subtitulo">LISTADO DE MEZCLAS POR REF EN EXTRUSION</td>
   </tr>
   <tr>
     <td id="fuente2">
        <select class="busqueda selectsMini" name="id_pm" id="id_pm">
         <option value="0">RE.</option>
         <?php  foreach($row_id_pm as $row_id_pm ) { ?>
          <option value="<?php echo $row_id_pm['id_pm']?>"><?php echo $row_id_pm['int_cod_ref_pm']?></option>
        <?php } ?>
      </select>

      <select class="busqueda selectsMini" name="fecha" id="fecha">
         <option value="0">ANUAL</option>
         <?php  foreach($row_ano as $row_ano ) { ?>
          <option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
      <?php } ?>  
      </select> 
    <input type="submit" class="botonGMini" name="Submit" value="FILTRO" onClick="if(consulta.id_pm.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
  </td>
  <td colspan="2" id="fuente2">
    <input class="botonDel" type="button" value="Descarga Mezclas Excel" onclick="window.location = 'produccion_mezclas_excel.php'" /></td>
</tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
  <table class="table table-bordered table-sm">
    <tr>
      <td colspan="4" id="dato1"><strong>Nota</strong>: Si la fila aparece en color rojo, es porque ingreso la mezcla pero no ingreso las caracteristicas</td>
      <td colspan="3" id="dato3"><a href="referencias.php"><img src="images/mas.gif" alt="ADD MEZCLA Y CARACTERISTICAS" title="ADD MEZCLA Y CARACTERISTICAS" border="0" style="cursor:hand;"/></a><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    </tr>
    <tr>
      <td colspan="2" id="dato1"><input name="Input" type="submit" value="Inactivar o Activar"/></td>
      <td colspan="5"><?php $id=$_GET['id'];
      /*	if($id >= '3') { ?> <div id="acceso1"> <?php echo "No se ha seleccionado"; ?> </div> <?php } */
      /*if($id >= '2') { ?> <div id="acceso1"> <?php echo "ACTIVACION CORRECTA"; ?> </div> <?php }*/	
      if($id >= '1') { ?>
        <div id="acceso1"> <?php echo "CAMBIADA A INACTIVA"; ?></div>
      <?php }/*
      if($id == '0') { ?><div id="numero1"> <?php echo "No se ha seleccionado"; ?> </div> <?php }*/?></td>
    </tr>  
    <tr id="tr1">
      <td id="titulo4"><!--<input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/>-->&nbsp;</td>
      <td nowrap="nowrap" id="titulo4">MEZCLA N&deg;</td>
      <td nowrap="nowrap" id="titulo4">REF N&deg;</td>
      <td nowrap="nowrap" id="titulo4">FECHA</td>
      <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
      <td nowrap="nowrap" id="titulo4">ESTADO</td>
    </tr>
    <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><?php if($row_mezclas['b_borrado_pm']=='0'){?><input name="borrado" type="hidden" id="borrado" value="32" /><input name="borrar[]" type="checkbox" value="<?php echo $row_mezclas['id_ref_pm']; ?>" /><?php }?></td>
        <td id="dato2">
          <?php  
          $ref_pm=$row_mezclas['id_ref_pm'];
          $sqlpm="SELECT int_id_ref_mm, id_proceso_mm FROM Tbl_maestra_mezcla_caract WHERE int_id_ref_mm='$ref_pm' AND id_proceso_mm='1'"; 
          $resultpm=mysql_query($sqlpm); 
          $numpm=mysql_num_rows($resultpm); 
          if($numpm >= '1') 
           { ?>
             <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $row_mezclas['int_cod_ref_pm'];?>" target="_top"  style="text-decoration:none; color:#000000"><?php echo $row_mezclas['id_pm']; ?> </a>
           <?php } else { ?>
             <a href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_mezclas['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_mezclas['id_pm']; ?>" target="_top" class="rojo_peq" style="text-decoration:none;"><?php echo $row_mezclas['id_pm']; ?> </a>
             <?php }?></td>
             <td id="dato2">
              <?php  
              $ref_pm=$row_mezclas['id_ref_pm'];
              $sqlpm="SELECT int_id_ref_mm, id_proceso_mm FROM Tbl_maestra_mezcla_caract WHERE int_id_ref_mm='$ref_pm' AND id_proceso_mm='1'"; 
              $resultpm=mysql_query($sqlpm); 
              $numpm=mysql_num_rows($resultpm); 
              if($numpm >= '1') 
               { ?>
                 <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $row_mezclas['int_cod_ref_pm'];?>" target="_top"  style="text-decoration:none; color:#000000"><?php echo $row_mezclas['int_cod_ref_pm']; ?> </a>
               <?php } else { ?>
                 <a href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_mezclas['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_mezclas['id_pm']; ?>" target="_top" class="rojo_peq" style="text-decoration:none;"><?php echo $row_mezclas['int_cod_ref_pm']; ?> </a>
               <?php }?>     
             </td>
             <td id="dato2">
              <?php  
              $ref_pm=$row_mezclas['id_ref_pm'];
              $sqlpm="SELECT int_id_ref_mm, id_proceso_mm FROM Tbl_maestra_mezcla_caract WHERE int_id_ref_mm='$ref_pm' AND id_proceso_mm='1'"; 
              $resultpm=mysql_query($sqlpm); 
              $numpm=mysql_num_rows($resultpm); 
              if($numpm >= '1') 
               { ?>
                 <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $row_mezclas['int_cod_ref_pm'];?>" target="_top"  style="text-decoration:none; color:#000000"><?php echo $row_mezclas['fecha_registro_pm']; ?> </a>
               <?php } else { ?>
                 <a href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_mezclas['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_mezclas['id_pm']; ?>" target="_top" class="rojo_peq" style="text-decoration:none;"><?php echo $row_mezclas['fecha_registro_pm']; ?> </a>
               <?php }?>
             </td>
             <td id="dato2">
              <?php  
              $ref_pm=$row_mezclas['id_ref_pm'];
              $sqlpm="SELECT int_id_ref_mm, id_proceso_mm FROM Tbl_maestra_mezcla_caract WHERE int_id_ref_mm='$ref_pm' AND id_proceso_mm='1'"; 
              $resultpm=mysql_query($sqlpm); 
              $numpm=mysql_num_rows($resultpm); 
              if($numpm >= '1') 
               { ?>
                 <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $row_mezclas['int_cod_ref_pm'];?>" target="_top"  style="text-decoration:none; color:#000000"><?php echo $row_mezclas['str_registro_pm']; ?> </a>
               <?php } else { ?>
                 <a href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_mezclas['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_mezclas['id_pm']; ?>" target="_top" class="rojo_peq" style="text-decoration:none;"><?php echo $row_mezclas['str_registro_pm']; ?> </a>
               <?php }?>
             </td>
             <td id="dato2"><?php if($row_mezclas['b_borrado_pm']=='0'){echo "ACTIVA";}else?><?php if($row_mezclas['b_borrado_pm']=='1'){?><input name="activar2" type="hidden" id="activar2" value="activar2" /><input name="activa[]" type="checkbox" value="<?php echo $row_mezclas['id_ref_pm']; ?>" /> ACTIVAR?<?php }?></td>
           </tr>
         <?php } while ($row_mezclas = mysql_fetch_assoc($mezclas)); ?>
       </table>
       <table id="tabla1">
        <tr>
          <td id="dato2"><?php if ($pageNum_mezclas > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_mezclas=%d%s", $currentPage, 0, $queryString_mezclas); ?>">Primero</a>
          <?php } // Show if not first page ?>
        </td>
        <td id="dato2"><?php if ($pageNum_mezclas > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_mezclas=%d%s", $currentPage, max(0, $pageNum_mezclas - 1), $queryString_mezclas); ?>">Anterior</a>
        <?php } // Show if not first page ?>
      </td>
      <td id="dato2"><?php if ($pageNum_mezclas < $totalPages_mezclas) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_mezclas=%d%s", $currentPage, min($totalPages_mezclas, $pageNum_mezclas + 1), $queryString_mezclas); ?>">Siguiente</a>
      <?php } // Show if not last page ?>
    </td>
    <td id="dato2"><?php if ($pageNum_mezclas < $totalPages_mezclas) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_mezclas=%d%s", $currentPage, $totalPages_mezclas, $queryString_mezclas); ?>">&Uacute;ltimo</a>
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

mysql_free_result($mezclas);

mysql_free_result($id_pm);
?>