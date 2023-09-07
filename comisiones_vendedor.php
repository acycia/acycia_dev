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
session_start();
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

if($_GET['orden']==''){
	$orden="fecha_despacho_io DESC";
	}else{
$orden=$_GET['orden'];
	}

  $maxRows_cotizacion = 20;
  $pageNum_cotizacion = 0;
  if (isset($_GET['pageNum_cotizacion'])) {
    $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
  }
  $startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;

mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_items_ordenc WHERE b_estado_io >= '4' ORDER BY $orden "; 
$query_limit_cotizacion = sprintf("%s LIMIT %d, %d", $query_cotizacion, $startRow_cotizacion, $maxRows_cotizacion);
$cotizacion = mysql_query($query_limit_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);
 
$row_numero = $conexion->llenaSelect('Tbl_referencia',"WHERE estado_ref='1'","order by CONVERT(cod_ref, SIGNED INTEGER)  desc");  
 
//IMRPIME EL NOMBRE DEL VENDEDOR
$row_vendedores = $conexion->llenaSelect('vendedor',"","order by nombre_vendedor ASC");  
 
 
if (isset($_GET['totalRows_cotizacion'])) {
  $totalRows_cotizacion = $_GET['totalRows_cotizacion'];
} else {
  $all_cotizacion = mysql_query($query_cotizacion);
  $totalRows_cotizacion = mysql_num_rows($all_cotizacion);
}
$totalPages_cotizacion = ceil($totalRows_cotizacion/$maxRows_cotizacion)-1;


$queryString_cotizacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_cotizacion") == false && 
        stristr($param, "totalRows_cotizacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_cotizacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_cotizacion = sprintf("&totalRows_cotizacion=%d%s", $totalRows_cotizacion, $queryString_cotizacion);

session_start();
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
 

<script type="text/javascript" src="js/formato.js"></script> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--Fireworks 8 Dreamweaver 8 target.  Created Tue Jun 06 20:42:04 GMT-0500 2006-->
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

 
</head>
<body bgcolor="#ffffff">
<?php echo $conexion->header('listas'); ?>
                    <table width="750" height="10" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">

                      <tr>
                        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                          <tr>
                            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo1 Estilo9"><?php echo $row_usuario['nombre_usuario']; ?></div></td>
                            <td width="128" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo2">Cerrar Sesión</a></div></td>
                          </tr>

                          <tr>
                            <td width="170" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo6"><strong>Codigo: R1-F03</strong></span></div></td>
                            <td width="500" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo76">SEGUIMIENTO A COMISIONES</span></div></td>
                            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Versión: 0</div></td>
                          </tr>

                          <tr>
                             
                            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><?php $id=$_GET['id'];  
                            if($id >= '1') { ?> <div id="acceso1"> <?php echo "Se cambio de estado a rechazada"; ?> </div><?php }
                            if($id == '0') { ?><div id="numero1"> <?php echo "No se pudo eliminar o actualizar"; ?> </div> <?php }?></td>
                            
                          </tr>
                          <tr>
                            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF" nowrap="nowrap"><form name="form1" id="form1" method="get" action="comisiones_vendedor2.php" >
                              <span class="Estilo5">Vendedor 
                                <select name="vendedor" id="vendedor" class="busqueda selectsMini">
                                  <option value="0" >Vendedor</option>
                                  <?php  foreach($row_vendedores as $row_vendedores ) { ?>
                                    <option value="<?php echo $row_vendedores['id_vendedor']?>"><?php echo $row_vendedores['nombre_vendedor']?></option>
                                  <?php } ?>
                                </select> 

                                Ref

                                <select name="cod_ref" id="cod_ref" class="busqueda selectsMini">
                                  <option value="0">Referencia</option>
                                  <?php  foreach($row_numero as $row_numero ) { ?>
                                    <option value="<?php echo $row_numero['cod_ref']?>"><?php echo $row_numero['cod_ref']?></option>
                                  <?php } ?>
                                </select>

                              </span>
       
<input class="botonGMini" name="Submit" type="submit" class="Estilo67" value="Buscar" />
<input class="botonDel" type="button" value="Descarga Excel" onclick="window.location = 'comisiones_vendedor_excel.php?vendedor=<?php echo 0; ?>&cod_ref=<?php echo 0; ?>'" />
</form></td>
<td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo3">
  <div align="center"><a href="cotizacion_general_menu.php" class="Estilo4">*Add Cotización </a></div>
</div></td>
</tr>
</table> 

 
   
      <table width="776" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="104" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="comisiones_vendedor.php?orden=<?php echo "int_vendedor_io ASC";?>">Vendedor</a></div></td>
          <td width="128" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6"><a href="comisiones_vendedor.php?orden=<?php echo "int_cod_ref_io DESC";?>">Ref</a></div></td>
          <td width="250" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Orden C</div></td>
          <td width="100" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Cantidad</div></td>
          <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Precio </div></td>
          <td width="51" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo6">Comision </div></td> 
          <td width="80" bordercolor="#FFFFFF" bgcolor="#ECF5FF" ><div align="center" class="Estilo6"><a href="comisiones_vendedor.php?orden=<?php echo "fecha_despacho_io DESC";?>">Fecha Despacho</a></div></td>
        </tr>
        <?php 
        $i=0;  ?>
        <?php do {  ?>

         <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } 
         $i++; ?>>
         <td width="104"><div align="left" class="Estilo8"><?php 
         $nombre_com = $row_cotizacion['int_vendedor_io'];
         $sqldato="SELECT nombre_vendedor FROM vendedor WHERE id_vendedor='$nombre_com'";
         $resultdato=mysql_query($sqldato);
         echo $nombre_vendedor=mysql_result($resultdato,0,'nombre_vendedor');

         ?></div></td>
         <td width="128"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['int_cod_ref_io']; ?></span></div></td>
         <td width="250"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['str_numero_io']; ?></span></div></td>
         <td width="100"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['int_cantidad_io']; ?></span></div></td>
         <td nowrap="nowrap"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['int_precio_io']; ?></span></div></td>
         <td width="51"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['int_comision_io']; ?></span></div></td>

         <td width="54"><div align="center"><span class="Estilo8"><?php echo $row_cotizacion['fecha_despacho_io']; ?></span></div></td>
       </tr>
     <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
   </table>
           <!-- tabla para paginacion opcional -->
           <table border="0" width="50%" align="center">
             <tr>
               <td width="23%" id="dato2"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
                 <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
               <?php } // Show if not first page ?>
             </td>
             <td width="31%" id="dato2"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
               <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
             <?php } // Show if not first page ?>
           </td>
           <td width="23%" id="dato2"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
             <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
           <?php } // Show if not last page ?>
         </td>
         <td width="23%" id="dato2"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
           <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, $totalPages_cotizacion, $queryString_cotizacion); ?>">&Uacute;ltimo</a>
         <?php } // Show if not last page ?>
       </td>
     </tr>
   </table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($row_usuario);

mysql_free_result($cotizacion);
?>
