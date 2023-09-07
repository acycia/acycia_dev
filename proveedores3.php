<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
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

$maxRows_registros = 50;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
 
$row_registros = $conexion->buscarListar("proveedor","*","ORDER BY proveedor_p ASC","",$maxRows_registros,$pageNum_registros,"" );
 

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('proveedor'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;


$queryString_registros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_registros") == false && 
        stristr($param, "totalRows_registros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_registros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);

 
?><html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <title>SISADGE AC &amp; CIA</title>
 <link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/vista.js"></script>

<link rel="stylesheet" type="text/css" href="css/general.css"/>
<link rel="stylesheet" type="text/css" href="css/formato.css"/>
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<body>

  <div id="linea1" align="center">
    <table id="tabla3" align="center">
      <tr id="tr1">
        <td id="subtitulo">LISTADO MAESTRO DE PROVEEDORES <a href="proveedor_add.php" target="_top"><img src="images/mas.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="proveedor_insumo.php" target="_top"><img src="images/cliente.gif" alt="ADD INSUMO" title="ADD INSUMO" border="0" style="cursor:hand;"/></a></td>
      </tr>
    </table></div>
    <div align="center">
      <table id="tabla3">
        <tr>
          <td class="Estilo1">PROVEEDOR</td>    
          <td class="Estilo2">NIT</td>
          <td class="Estilo2">TIPO</td>
          <td class="Estilo2">PRODUCTOS/SERVICIOS</td>
          <td class="Estilo1">CONTACTO</td>
          <td class="Estilo2">CIUDAD</td>
          <td class="Estilo2">TELEFONO</td>
          <td class="Estilo2">CELULAR</td>
          <td class="Estilo5">(%)</td>
          <td class="Estilo5">FECHA</td>
          <td class="Estilo5">CAMARA</td>
          <td class="Estilo5">RUT</td>
          <td class="Estilo5">DATOS</td>
        </tr>
      </table>
    </div>

<div align="center">
<table id="tabla3">
 <?php foreach($row_registros as $row_proveedores) {  ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
      <td class="Estilo3"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['proveedor_p']; ?></a></td>
      <td class="Estilo4"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['nit_p']; ?></a></td>
      <td class="Estilo4"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $tipo_p=$row_proveedores['tipo_p']; 
	  $sqltipo="SELECT * FROM tipo WHERE id_tipo='$tipo_p'";
	  $resultipo= mysql_query($sqltipo);
	  $numtipo= mysql_num_rows($resultipo);
	  if($numtipo >='1')
	  { 
	  $tipo = mysql_result($resultipo, 0, 'nombre_tipo'); 	  
	  if($tipo != '')  { echo $tipo; } } ?></a></td>
      <td class="Estilo4"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['tipo_servicio_p']; ?></a></td>
      <td class="Estilo3"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['contacto_p']; ?></a></td>
      <td class="Estilo4"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['ciudad_p']; ?></a></td>
      <td class="Estilo4"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['telefono_p']; ?></a></td>
      <td class="Estilo4"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['celular_c_p']; ?></a></td>
      <td class="centrado6"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $id_p=$row_proveedores['id_p']; 
	  $sqlp="SELECT * FROM proveedor_seleccion WHERE id_p_seleccion='$id_p'";
	  $resultp= mysql_query($sqlp);
	  $nump= mysql_num_rows($resultp);
	  if($nump >='1')
	  { 
	  $calificacion1 = mysql_result($resultp, 0, 'primera_calificacion_p');
	  $fecha1 = mysql_result($resultp, 0, 'fecha_encuesta_p');
	  $calificacion2 = mysql_result($resultp, 0, 'ultima_calificacion_p');
	  $fecha2 = mysql_result($resultp, 0, 'fecha_ultima_calificacion_p'); 	  	  
	  if($calificacion2 != '')  { echo $calificacion2; } else if($calificacion1 != '') { echo $calificacion1; } 
	  } ?></a></td>
      <td  class="centrado6"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($calificacion2 != '') { echo $fecha2; } else if($calificacion1 != '') { echo $fecha1; } ?></a>
      </td>
      <td class="Estilo3"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
              <a href="javascript:verFoto('archivosc/<?php echo $row_proveedores['camara_comercio_p'] ?>','610','490')">
                    <?php if($row_proveedores['camara_comercio_p']!=''){ ?>
                  <img src="images/arte.gif" alt="<?php echo $muestra;?>" title="CAMARA" border="0" style="cursor:hand;"  /></a>
                    <?php }else{  } ?>
         </a></td>

         <td class="Estilo3"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
                 <a href="javascript:verFoto('archivosc/<?php echo $row_proveedores['rut_p'] ?>','610','490')">
                       <?php if($row_proveedores['rut_p']!=''){ ?>
                     <img src="images/arte.gif" alt="<?php echo $muestra;?>" title="RUT" border="0" style="cursor:hand;"  /></a>
                       <?php }else{  } ?>
            </a></td>

         <td class="Estilo3"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
             <a href="javascript:verFoto('archivosc/<?php echo $row_proveedores['datos_proyeccion_p'] ?>','610','490')">
                   <?php if($row_proveedores['datos_proyeccion_p']!=''){ ?>
                 <img src="images/arte.gif" alt="<?php echo $muestra;?>" title="DATOS" border="0" style="cursor:hand;"  /></a>
                   <?php }else{  } ?>
        </a></td> 
    </tr>
    <?php } ?>
</table>
<!-- tabla para paginacion opcional -->
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
    <?php } // Show if not first page ?>
  </td>
  <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
    <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
  <?php } // Show if not first page ?>
</td>
<td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
<?php } // Show if not last page ?>
</td>
<td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
<?php } // Show if not last page ?>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($proveedores);
?>
