<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
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
header('Content-Disposition: attachment; filename="compras.xls"');
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
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
 
 
 $anual=$_GET['anual'];
 $mes=$_GET['mes']; 
 $dia=$_GET['dia']; 
 $fecha = $anual.'-'.$mes.'-'. $dia;
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
 </head>
<body>
<?php
 
//Filtra remision, FECHA
mysql_select_db($database_conexion1, $conexion1);
 
if($anual != '0' && $mes == '0' && $dia== '0')
{
$query_ingresos="SELECT * FROM TblIngresos WHERE  YEAR(fecha_ing) = '$anual' order by fecha_ing DESC";
}
if($anual != '0' && $mes != '0' && $dia== '0')
{
$query_ingresos="SELECT * FROM TblIngresos WHERE  YEAR(fecha_ing) = '$anual' AND MONTH(fecha_ing) = '$mes' order by fecha_ing DESC";
}
if($anual != '0' && $mes != '0' && $dia!= '0')
{
$query_ingresos="SELECT * FROM TblIngresos WHERE fecha_ing = '$fecha' order by fecha_ing DESC";
}
/*else{
$query_ingresos="SELECT * FROM TblIngresos WHERE fecha_ing order by fecha_ing DESC";
}*/
$ingresos = mysql_query($query_ingresos, $conexion1) or die(mysql_error());
$row_ingresos = mysql_fetch_assoc($ingresos);
$totalRows_ingresos = mysql_num_rows($ingresos);


$conexion = new ApptivaDB();

?>

<table id="tabla1" border=1>
              <tr>  
                <td id="nivel2">ORDEN DE COMPRA</td>
                <td id="nivel2">FACTURA</td>
                <td id="nivel2">PROVEEDOR</td>
                <td id="nivel2">CODIGO AyC</td>
                <td id="nivel2">PRODUCTO</td> 
                <td id="nivel2">UNIDAD</td>
                <td id="nivel2">COSTO UNITARIO</td>
                <td id="nivel2">CANTIDAD</td>
                <td id="nivel2">COSTO ANTES DE IVA</td>
                <td id="nivel2">IVA</td>
                <td id="nivel2">RET FTE</td>
                <td id="nivel2">COSTO TOTAL</td> 
                <td id="nivel2">PLAZO EN DIAS</td>
                <td id="nivel2">FECHA INGRESO</td>
                <td id="nivel2">CAUSANTE</td>
                <td id="nivel2">CONCEPTO 2</td>
                <td id="nivel2">CENTRO COSTOS</td>
                <td id="nivel2">FECHA ORDEN</td>
                <td id="nivel2">FECHA FACTURA</td>
                <td id="nivel2">FECHA VENCIMIENTO FACTURA</td>
                <td id="nivel2">FECHA DE RECIBIDO</td>
                </tr>                
            <?php do { ?>
            <tr>
            
            <td id="nivel2"><?php echo $row_ingresos['oc_ing'];?></td> 
            <td id="nivel2"><?php 
                $oc=$row_ingresos['oc_ing'];
                $sqlverif="SELECT factura_vi FROM verificacion_insumos WHERE n_oc_vi='$oc'";
                $resultverif= mysql_query($sqlverif);
                $numverif= mysql_num_rows($resultverif);
                if($numverif >='1')
                { 
                  echo $factura_verif = mysql_result($resultverif,0,'factura_vi');
                }?>
            </td>             
            <td id="nivel2"><?php 
                $oc=$row_ingresos['oc_ing'];
                $sqldet="SELECT * FROM orden_compra as oc, orden_compra_detalle as ocd WHERE oc.n_oc=ocd.n_oc_det and n_oc = '$oc'";
                $resultdet= mysql_query($sqldet);
                $numdet= mysql_num_rows($resultdet);
                if($numdet >='1')
                { 
                $idpoc = mysql_result($resultdet,0,'id_p_oc');
                $total_det = mysql_result($resultdet,0,'valor_bruto_oc');
                $valor_iva_oc = mysql_result($resultdet,0,'valor_iva_oc');
                $total_oc = mysql_result($resultdet,0,'total_oc');
                $fte_oc = mysql_result($resultdet,0,'fte_oc');
                $cond_pago_oc = mysql_result($resultdet,0,'cond_pago_oc');
                $concepto1 = mysql_result($resultdet,0,'concepto1');
                $concepto2 = mysql_result($resultdet,0,'concepto2');
                $centro_costos = mysql_result($resultdet,0,'centro_costos');
                $fecha_entrega_oc  = mysql_result($resultdet,0,'fecha_entrega_oc');
                $fecha_factura = mysql_result($resultdet,0,'fecha_factura');
                $fecha_vence_factura = mysql_result($resultdet,0,'fecha_vence_factura');

                $idpoc;
                 } 
               
                $sqlpro="SELECT proveedor_p FROM proveedor where id_p = '$idpoc'";
                $resultpro= mysql_query($sqlpro);
                $numpro= mysql_num_rows($resultpro);
                if($numpro >='1')
                { 
                echo $pro_nombre = mysql_result($resultpro,0,'proveedor_p');
                 } 
                 
                 ?>
            </td>
            <td id="nivel2"><?php 
                $insumo=$row_ingresos['id_insumo_ing'];
                $sqlins="SELECT codigo_insumo,descripcion_insumo,medida_insumo FROM insumo WHERE id_insumo = '$insumo'";
                $resultins= mysql_query($sqlins);
                $numins= mysql_num_rows($resultins);
                if($numins >='1')
                { 
                $insumo_nombre = mysql_result($resultins,0,'descripcion_insumo');
                $insumo_medida = mysql_result($resultins,0,'medida_insumo');
                $codigo_insumo = mysql_result($resultins,0,'codigo_insumo');
                echo $codigo_insumo;
                 } ?></td>
              <td id="nivel2"><?php echo $insumo_nombre;?></td> 
              <td id="nivel2">
              <?php $medida_insumo=$insumo_medida;
              $sqlmedida="SELECT nombre_medida FROM medida WHERE id_medida = $medida_insumo";
              $resultmedida= mysql_query($sqlmedida);
              $numedida= mysql_num_rows($resultmedida);
              if($numedida >='1') { 
                $medida_ins=mysql_result($resultmedida,0,'nombre_medida'); }
              echo $medida_ins; ?>
              </td>              
              <td id="nivel2"><?php echo $row_ingresos['valor_und_ing'];?></td>
              <td id="nivel2"><?php echo $row_ingresos['ingreso_ing'];?></td>
              <td id="nivel2">
                <?php echo $total_det; ?></td>
              <td nowrap id="nivel2"><?php echo $valor_iva_oc;?></td> 
              <td nowrap id="nivel2"><?php echo $fte_oc;?></td> 
              <td id="nivel2"><?php echo $total_oc;?></td>
              <td id="nivel2"><?php echo $cond_pago_oc;?></td>
              <td nowrap id="nivel2"><?php echo $row_ingresos['fecha_ing'];?></td>
              <td nowrap id="nivel2"><?php echo $concepto1;?></td>
              <td nowrap id="nivel2"><?php echo $concepto2;?></td>
              <td nowrap id="nivel2"><?php echo $centro_costos;?></td>
              <td nowrap id="nivel2"><?php echo $fecha_entrega_oc;?></td>
              <td nowrap id="nivel2"><?php echo $fecha_factura ;?></td>
              <td nowrap id="nivel2"><?php echo $fecha_vence_factura;?></td> 
              <td id="nivel2">
              <?php 
              $oc_ing=$row_ingresos['oc_ing'];
              $row_verif = $conexion->llenarCampos('verificacion_insumos',"WHERE n_oc_vi='$oc_ing'", '','fecha_vi'); 
              if($row_verif['fecha_vi'] >='1') { 
                $fecha_vi=$row_verif['fecha_vi']; 
              }
              echo $fecha_vi; ?>
              </td> 
            </tr>
             <?php } while ($row_ingresos = mysql_fetch_assoc($ingresos)); ?>

            </table>

</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);
mysql_free_result($ingresos);


?>