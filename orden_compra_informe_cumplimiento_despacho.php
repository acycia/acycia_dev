<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_numeracion = 40;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
$fecha1=first_year_month(); 
$fecha2=last_year_month();
//Filtra todos vacios
if($id_op== '')
{
$query_numeracion = "SELECT * FROM Tbl_orden_compra WHERE b_borrado_oc='0' AND fecha_ingreso_oc BETWEEN '$fecha1' AND '$fecha2' and id_pedido='0' ORDER BY fecha_entrega_oc DESC";
}
//SELECT INTERVAL 1 DAY + `fecha_despacho_io` FROM `Tbl_items_ordenc` WHERE `id_pedido_io`  = 2043 ORDER BY `fecha_despacho_io` DESC LIMIT 1
//$query_limit_numeracion = sprintf("%s LIMIT %d, %d", $query_numeracion, $startRow_numeracion, $maxRows_numeracion);
$numeracion = mysql_query($query_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);

if (isset($_GET['totalRows_numeracion'])) {
  $totalRows_numeracion = $_GET['totalRows_numeracion'];
} else {
  $all_numeracion = mysql_query($query_numeracion);
  $totalRows_numeracion = mysql_num_rows($all_numeracion);
}
$totalPages_numeracion = ceil($totalRows_numeracion/$maxRows_numeracion)-1;

$queryString_numeracion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_numeracion") == false && 
        stristr($param, "totalRows_numeracion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_numeracion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_numeracion = sprintf("&totalRows_numeracion=%d%s", $totalRows_numeracion, $queryString_numeracion);
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/formato.js"></script>

<!-- sweetalert -->
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
 <?php echo $conexion->header('listas'); ?>
                  <form action="orden_compra_informe_cumplimiento_despacho_excel.php" method="get" name="form1">
                    <table class="table table-bordered table-sm">
                      <tr>
                        <td colspan="2" id="titulo2">CUMPLIMIENTO DE DESPACHO POR ORDENES DE COMPRA </td>
                      </tr>
                      <tr>
                        <td colspan="8" id="fuente2">FECHA INICIO:
                          <input name="fecha_ini" type="date" required="required" id="fecha_ini" min="2018-01-02" size="10" value="<?php echo date('Y-01-01');?>"/>FECHA FIN: <input name="fecha_fin" type="date" id="fecha_fin" min="2018-01-02" size="10" required="required" value="<?php echo fecha();?>"/> 


                          <td id="titulo2"> <input type="submit" class="botonGeneral" name="submit" value="FILTRO"/></td>
                          <td id="dato3"><!-- <input type="button" value="Exporta a Excel" onClick="window.location = 'orden_compra_informe_cumplimiento_despacho_excel.php'" /> --></td>
                        </tr>
                        <tr>
                          <td id="fuente1"><strong>NOTA:</strong></td>
                          <td id="fuente1"><p>En la columna 'Cumple' la informacion:</p>
                            <p>S.D = Sin Despachar    </p>
                            <p>SI = Si Cumple</p>
                            <p>NO = No Cumple</p>
                            <p>* La fecha de despacho tiene una tolerancia de 3 dias, esto porque los fines de semana no hay despacho.</p>
                            <p>* Se compara la fecha de entrega de la O.C y la fecha en que se despacho que no debe ser mayor.</p></td>
                          </tr>
                        </table>
                        <div class="table table-bordered table-sm" style=" overflow:scroll;">
                          <table id="Exportar_a_Excel">   
                            <tr id="tr1">
                              <td id="titulo4"></td>
                              <td nowrap="nowrap"id="titulo4">N&deg; O.C</td>
                              <td nowrap="nowrap"id="titulo4">CLIENTE</td>
                              <td nowrap="nowrap"id="titulo4">FECHA DESPACHO</td>
                              <td nowrap="nowrap"id="titulo4">FECHA ENTREGA O.C</td>     
                              <td nowrap="nowrap"id="titulo4">CUMPLE</td>
                              <td nowrap="nowrap"id="titulo1">&nbsp;</td>                
                            </tr>
                            <?php $sis=0;$nos=0;$total=0; ?>
                            <?php do { ?>
                              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                                <td nowrap="nowrap" id="dato2">&nbsp;</td>
                                <td nowrap="nowrap" id="dato1"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['str_numero_oc']; ?></a></td>
                                <td nowrap="nowrap" id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                                  <?php 
                                  $id_c=$row_numeracion['id_c_oc'];
                                  $sqln="SELECT * FROM cliente WHERE cliente.id_c='$id_c'"; 
                                  $resultn=mysql_query($sqln); 
                                  $numn=mysql_num_rows($resultn); 
                                  if($numn >= '1') 
                                   { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
                                 else { echo "";	} ?>
                               </a></td>      
                               
                                 <td nowrap="nowrap" id="dato2">
                                  <a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                                   <?php 
                                   $id_io=$row_numeracion['id_pedido'];
                                   $sqlio="SELECT fecha_despacho_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_io' ORDER BY `fecha_despacho_io` DESC LIMIT 1"; 
                                   $resultio=mysql_query($sqlio); 
                                   $numio=mysql_num_rows($resultio); 
                                   if($numio >= '1') 
                                     {
                                      $fechaio=mysql_result($resultio,0,'fecha_despacho_io'); 
                                   } 
                                   if ($fechaio==''){
                                     echo "S.D SIN DESPACHO";
                                    }else{

                                      echo $fechaio;
                                    }
                                   ?> 
                                   </a>
                                 </td>
                                 <td nowrap="nowrap" id="dato2">
                                  <a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">
                                   <?php 
                                   $id_fe=$row_numeracion['id_pedido'];
                                   /*$sqlfe="SELECT INTERVAL 3 DAY + `fecha_entrega_io` AS fechamas, fecha_entrega_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_fe' ORDER BY `fecha_entrega_io` DESC LIMIT 1"; 
                                   $resultfe=mysql_query($sqlfe); 
                                   $numfe=mysql_num_rows($resultfe); 
                                   if($numfe >= '1') 
                                   { 
                                     $fechafe=mysql_result($resultfe,0,'fecha_entrega_io'); echo $fechafe;
                                     $fechamas=mysql_result($resultfe,0,'fechamas');
                                   }*/
                                   $sqlfe="SELECT fecha_entrega_oc AS fechamas, fecha_entrega_oc FROM tbl_orden_compra WHERE id_pedido='$id_fe' AND str_elaboro_oc <>'PW' ORDER BY fecha_entrega_oc DESC "; 
                                   $resultfe=mysql_query($sqlfe); 
                                   $numfe=mysql_num_rows($resultfe); 
                                   if($numfe >= '1') 
                                   { 
                                     $fechafe=mysql_result($resultfe,0,'fecha_entrega_oc'); 
                                      
                                     $fechamas=mysql_result($resultfe,0,'fechamas');
                                   }
                                   if ($fechafe==''){
                                     echo "SIN FECHA DE ENTREGA";
                                   }else{
                                     echo $fechafe;
                                     
                                   }
                                   ?></a></td>

                                 <td nowrap="nowrap" id="dato2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_numeracion['str_numero_oc'];?>&id_oc=<?php echo $row_numeracion['id_c_oc'];?>" target="_top" style="text-decoration:none; color:#000000">	  
                                  <?php 
                                      if($fechamas !='' && $fechaio!=''){
                                        if($fechaio<=$fechamas) {
                                           $sis++; 
                                           echo "SI";
                                          }else{
                                          echo "NO";   
                                            $nos++; 
                                        }
                                        $total = $sis+$nos;
                                      } 
                                 ?></a>
                               </td>     
                               <td nowrap="nowrap" id="titulo1">&nbsp;</td> 
                             </tr>
                           <?php } while ($row_numeracion = mysql_fetch_assoc($numeracion)); ?>
                           <tr>
                             <td colspan="6">&nbsp;</td>
                           </tr>
                           <tr>
                             <td nowrap="nowrap"  id="titulo1">&nbsp;</td> 
                             <td>
                               Total SI: <?php  echo $sis; ?>
                             </td>
                             <td>
                               Total NO: <?php  echo $nos; ?>
                             </td>
                             <td>
                              Total: <?php echo $total; ?>
                             </td>
                             <td>
                              <strong> % SI: <?php echo round($sis/$total*100); ?></strong>
                             </td>
                             <td>
                              <strong> % NO: <?php echo round($nos/$total*100); ?></strong>
                             </td>
                           </tr>
                         </table>
                       </form>
                      
           <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($numeracion);

?>