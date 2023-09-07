<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="Cumplimiento de Despacho.xls"');
?>
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

//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
?>
<?php
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
/*$fecha1=first_year_month(); 
$fecha2=last_year_month();*/

$fecha1 = $_GET['fecha_ini'];
$fecha2 = $_GET['fecha_fin'];
 
//Filtra todos vacios
if($id_op== '')
{
$query_numeracion = "SELECT * FROM Tbl_orden_compra  WHERE b_borrado_oc='0' AND fecha_ingreso_oc BETWEEN '$fecha1' AND '$fecha2' ORDER BY fecha_entrega_oc DESC";
}

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
</head>
<body>
<table id="Exportar_a_Excel">   
  <tr>
    <td id="titulo5"></td>
    <td nowrap="nowrap" id="titulo5">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" id="titulo5">CUMPLIMIENTO DE DESPACHO POR ORDENES DE COMPRA</td>
    <td nowrap="nowrap" id="titulo6">&nbsp;</td>
  </tr>
  <tr>
    <td id="titulo2"></td>
    <td nowrap="nowrap" id="titulo2">Nota:</td>
    <td colspan="4" nowrap="nowrap" id="titulo2"><ul>
      <li>En la columna 'Cumple' la informacion:</li>
      <li>S.D = Sin Despachar </li>
      <li>SI = Si Cumple</li>
      <li>NO = No Cumple</li>
      <li>* La fecha de despacho tiene una tolerancia de 3 dias, esto porque los fines de semana no hay despacho.</li>
      <li>* Se compara la fecha de entrega de la O.C y la fecha en que se despacho que no debe ser mayor.</li>
    </ul>    </td>
    <td nowrap="nowrap" id="titulo3">&nbsp;</td>
  </tr>
  <tr id="tr1">
    <td id="titulo4">&nbsp;</td>
    <td nowrap="nowrap" id="titulo4">N&deg; O.C</td>
    <td nowrap="nowrap" id="titulo4">CLIENTE</td>
    <td nowrap="nowrap" id="titulo4">FECHA DESPACHO</td>
    <td nowrap="nowrap" id="titulo4">FECHA ENTREGA O.C</td>     
    <td nowrap="nowrap" id="titulo4">CUMPLE</td>
    <td nowrap="nowrap" id="titulo1">&nbsp;</td>                
  </tr>
  <?php $sis=0;$nos=0;$total=0; ?>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
  <td nowrap="nowrap"  id="dato2">&nbsp;</td>
  <td nowrap="nowrap"  id="dato1"><?php echo $row_numeracion['str_numero_oc']; ?></td>
  <td nowrap="nowrap"  id="dato2">
  <?php 
  $id_c=$row_numeracion['id_c_oc'];
  $sqln="SELECT * FROM cliente WHERE cliente.id_c='$id_c'"; 
  $resultn=mysql_query($sqln); 
  $numn=mysql_num_rows($resultn); 
  if($numn >= '1') 
  { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo strtoupper(utf8_encode($ca)); }
  else { echo ""; } ?>
   </td>  
    <td nowrap="nowrap"  id="dato2">
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
    </td> 

      <td nowrap="nowrap"  id="dato2">
        <?php 
        $id_fe=$row_numeracion['id_pedido'];
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
        ?>
        </td>
      <td nowrap="nowrap"  id="dato2">  
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
      ?>
      </td>   
      <td nowrap="nowrap"  id="titulo1">&nbsp;</td> 
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
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($numeracion);

?>