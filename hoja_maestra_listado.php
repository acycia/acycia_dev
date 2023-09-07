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
$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$maxRows_orden_produccion = 20;
$pageNum_orden_produccion = 0;
if (isset($_GET['pageNum_orden_produccion'])) {
  $pageNum_orden_produccion = $_GET['pageNum_orden_produccion'];
}
$startRow_orden_produccion = $pageNum_orden_produccion * $maxRows_orden_produccion;

$row_orden_produccion = $conexion->buscarListar("Tbl_referencia, Tbl_caract_proceso"," DISTINCT Tbl_caract_proceso.id_ref_cp, Tbl_caract_proceso.id_pm_cp, Tbl_referencia.id_ref,Tbl_referencia.version_ref,Tbl_referencia.cod_ref,Tbl_referencia.id_ref,Tbl_referencia.registro1_ref ","ORDER BY Tbl_caract_proceso.id_cod_ref_cp DESC","WHERE Tbl_referencia.id_ref=Tbl_caract_proceso.id_ref_cp AND Tbl_referencia.estado_ref='1' AND Tbl_caract_proceso.id_proceso='1'",$maxRows_orden_produccion,$pageNum_orden_produccion,"" );


if (isset($_GET['totalRows_orden_produccion'])) {
  $totalRows_orden_produccion = $_GET['totalRows_orden_produccion'];
} else {
  $totalRows_orden_produccion = $conexion->conteo('Tbl_caract_proceso'); 
} 
$totalPages_orden_produccion = floor($totalRows_orden_produccion/$maxRows_orden_produccion)-1;

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
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>

    <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table style="width: 80%"><!-- class="table table-bordered table-sm" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                    <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div>
                    <div class="panel-heading"><h3>HOJA MAESTRA</h3></div>
                </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                  <div id="cabezamenu">
                   <ul id="menuhorizontal">
                     <li><?php echo $row_usuario['nombre_usuario']; ?></li>
                     <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                     <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                     </ul>
                   </ul>
                </div> 
               <div class="panel-body">
                 <br> 
                 <div ><!-- contenedor -->
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div> 
 
                  <form action="produccion_ordenes_produccion_listado.php" method="get" name="form1" onSubmit="if(form1.retorno_mensaje.value=='1'){return false;}else if(form1.retorno_mensaje.value=='0'){return true;}">
                    <table class="table table-bordered table-sm">
                      <tr>
                        <td id="titulo2">LISTADO DE HOJAS MAESTRAS</td>
                      </tr>

                    </table>

                    <table class="table table-bordered table-sm">
                      <tr>
                        <td id="dato1">&nbsp;</td>
                        <td id="dato1">&nbsp;</td>
                        <td id="dato3">&nbsp;<?php if ($row_usuario['tipo_usuario']=='1') {?><a href="referencias.php"><img src="images/mas.gif" alt="REFERENCIAS" title="REFERENCIAS" border="0" style="cursor:hand;"/></a><?php } ?><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>  
                      <tr id="tr1">
                        <td nowrap="nowrap"id="titulo4">REF.</td>
                        <td nowrap="nowrap"id="titulo4">VER.</td>
                        <td nowrap="nowrap"id="titulo4">RESPONSABLE</td>
                      </tr>
                      <?php foreach($row_orden_produccion as $row_orden_produccion) {  ?>
                        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                          <td nowrap="nowrap"id="dato2"><a href="hoja_maestra_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref'];?>&id_pm=<?php echo $row_orden_produccion['id_pm_cp'];?>&cod_ref=<?php echo $row_orden_produccion['cod_ref']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_orden_produccion['cod_ref']; ?></strong></a></td>
                          <td id="dato2"><a href="hoja_maestra_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref'];?>&id_pm=<?php echo $row_orden_produccion['id_pm_cp'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['version_ref']; ?></a></td>
                          <td nowrap="nowrap"id="dato2"><a href="hoja_maestra_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref'];?>&id_pm=<?php echo $row_orden_produccion['id_pm_cp'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['registro1_ref']; ?></a></td>
                        </tr>
                      <?php } ?>
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
          </table></td>
  </tr> 
  </div>
  </div><!-- contenedor -->
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_produccion);
?>