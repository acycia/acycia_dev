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
header('Content-Disposition: attachment; filename="Produccion.xls"');
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

$tipoListado = $_GET['tipoListado'];//variable de control del case
$var1 = $_GET['op'];
$var2 = $_GET['id_ref'];
$anyo = $_GET['anyo'];
$var3 = $_GET['mes'];
$maquina = $_GET['maquina'];
$date = date("Y");
  switch ($tipoListado) {
    case "1":
    mysql_select_db($database_conexion1, $conexion1);
    //FILTRA VACIOS
    if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo == '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)='$date' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA OP LLENO
    if($var1 != '0' && $var2 == '0' && $var3 == '0' && $anyo == '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM Tbl_reg_produccion WHERE id_proceso_rp ='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA OP, AÑO LLENO
    if($var1 != '0' && $var2 == '0' && $var3 == '0' && $anyo != '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA OP, MES LLENO
    if($var1 != '0' && $var2 == '0' && $var3 != '0' && $anyo == '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA OP, AÑO, MES LLENO
    if($var1 != '0' && $var2 == '0' && $var3 != '0' && $anyo != '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA REF LLENO
    if($var1 == '0' && $var2 != '0' && $var3 == '0' && $anyo == '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA REF, AÑO LLENO
    if($var1 == '0' && $var2 != '0' && $var3 == '0' && $anyo != '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA REF, MES LLENO
    if($var1 == '0' && $var2 != '0' && $var3 != '0' && $anyo == '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA REF, AÑO, MES LLENO
    if($var1 == '0' && $var2 != '0' && $var3 != '0' && $anyo != '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA AÑO
    if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo != '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA MES AÑO
    if($var1 == '0' && $var2 == '0' && $var3 != '0' && $anyo != '0' && $maquina =='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }

    //FILTRA MAQUINA LLENO
    if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo == '0' && $maquina !='0')
    {
    $query_orden_produccion="SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA MAQUINA, AÑO LLENO
    if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo != '0' && $maquina !='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA MAQUINA, MES LLENO
    if($var1 == '0' && $var2 == '0' && $var3 != '0' && $anyo == '0' && $maquina !='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
    //FILTRA MAQUINA, AÑO, MES LLENO
    if($var1 == '0' && $var2 == '0' && $var3 != '0' && $anyo != '0' && $maquina !='0')
    {
    $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
    }
     
      
    $orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
    $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
    $totalPages_orden_produccion = mysql_num_rows($orden_produccion);
    break;
    case "2":
      mysql_select_db($database_conexion1, $conexion1);
     if($_GET['anyo']!='0' && $_GET['mes']!='0'){
      $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)=".$_GET['anyo']." AND MONTH(fecha_ini_rp)=".$_GET['mes']." AND id_proceso_rp='2' ORDER BY id_op_rp DESC"; 
    }else{
      $query_orden_produccion = "SELECT * FROM Tbl_reg_produccion WHERE id_proceso_rp ='2' ORDER BY Tbl_reg_produccion.id_op_rp DESC";
    }
      $orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
      $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
      $totalPages_orden_produccion = mysql_num_rows($orden_produccion);
    break;	
    case "3":
      mysql_select_db($database_conexion1, $conexion1);
     if($_GET['anyo']!='0' && $_GET['mes']!='0'){
      $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)=".$_GET['anyo']." AND MONTH(fecha_ini_rp)=".$_GET['mes']." AND id_proceso_rp='3' ORDER BY id_op_rp DESC";
    }else{
      $query_orden_produccion = "SELECT * FROM Tbl_reg_produccion WHERE id_proceso_rp ='3' ORDER BY Tbl_reg_produccion.id_op_rp DESC";
    }
      $orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
      $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
      $totalPages_orden_produccion = mysql_num_rows($orden_produccion);
    break;
    case "4":
      mysql_select_db($database_conexion1, $conexion1);
     if($_GET['anyo']!='0' && $_GET['mes']!='0'){
      $query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)=".$_GET['anyo']." AND MONTH(fecha_ini_rp)=".$_GET['mes']." AND id_proceso_rp='4' ORDER BY id_op_rp DESC";
    }else{
}
      $query_orden_produccion = "SELECT * FROM Tbl_reg_produccion WHERE id_proceso_rp ='4' ORDER BY Tbl_reg_produccion.id_op_rp DESC";
      $orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
      $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
      $totalPages_orden_produccion = mysql_num_rows($orden_produccion);
    break;			
  }
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?php   
  switch ($tipoListado) {
    case "1":?>
<!--EXTRUSION-->
<table id="Exportar_a_Excel" border="1">            
    <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">KILOS DESP.</td>
    <td nowrap="nowrap"id="titulo4">TIEMPO TOTAL</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS MUERTOS</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS PREPARACION</td>
    <td nowrap="nowrap"id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap"id="titulo4">FECHA FINAL</td> 
    <td nowrap="nowrap"id="titulo4">MONTAJE</td>
    <td nowrap="nowrap"id="titulo4">LIQUIDA</td>
    <td nowrap="nowrap"id="titulo4">MAQUINA</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['id_op_rp']; ?></td>
      <td id="dato2">
		<?php 
        $op_c=$row_orden_produccion['id_op_rp'];
        $sqln="SELECT * FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
        $resultn=mysql_query($sqln); 
        $numn=mysql_num_rows($resultn); 
        if($numn >= '1') 
        { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
        else { echo "";	
        }?> </td>     
      <td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['version_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_prod_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['total_horas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_muertas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_prep_rp']; ?></td>             
      <td nowrap="nowrap"id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
      <td nowrap="nowrap"id="dato2"><?php echo $row_orden_produccion['fecha_fin_rp']; ?></td>
      <td nowrap="nowrap" id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE empleado.codigo_empleado='$id_emp'";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; }else{echo "N/I";
	  }?></td>
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE empleado.codigo_empleado='$id_emp'";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; }else{echo "N/I";
	  }?></td>
    <td id="dato2">
     <?php 
       $maquinaid = $row_orden_produccion['str_maquina_rp'];
       $nombre_maquinas = $conexion->llenarCampos("maquina", "WHERE id_maquina='".$maquinaid."' ", " ", "nombre_maquina "); 
       echo $nombre_maquinas['nombre_maquina']; 
       ?> 
    </td> 
      <td id="dato2">
      <?php if($row_orden_produccion['b_borrado_rp']=='1'){?>Inactiva</a><?php }else{echo "Activa";}?>
      </td>          
     </tr>
    <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
  </table>
   <?php 
   break;
   case "2":
   ?>
<!--IMPRESION --> 
<table id="Exportar_a_Excel" border="1">   
  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">KILOS DESP.</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS TOTAL</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS MUERTOS</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS PREPARACION</td>
    <td nowrap="nowrap"id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap"id="titulo4">FECHA FINAL</td>     
    <td nowrap="nowrap"id="titulo4">OPERARIO</td>
    <td nowrap="nowrap"id="titulo4">REVISOR</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
  <tr>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['id_op_rp']; ?></td>
      <td nowrap="nowrap" id="dato2">
		<?php 
        $op_c=$row_orden_produccion['id_op_rp'];
        $sqln="SELECT * FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
        $resultn=mysql_query($sqln); 
        $numn=mysql_num_rows($resultn); 
        if($numn >= '1') 
        { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
        else { echo "";	
        }?>
      </td>
      <td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['version_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_prod_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['total_horas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_muertas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_prep_rp']; ?></td>      
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_fin_rp']; ?></td>      
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }?>
      </td>
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }?>
      </td>
      <td  nowrap="nowrap" id="dato2">      <?php  
	  $id_rp=$row_orden_produccion['id_rp'];
	  $sqlidrp="SELECT * FROM Tbl_reg_produccion WHERE id_rp='$id_rp' AND b_borrado_rp='1'";
	  $resultidrp= mysql_query($sqlidrp);
	  $numidrp= mysql_num_rows($resultidrp);
	  if($numidrp >='1')
	  { 
	  $idrp = mysql_result($resultidrp, 0, 'id_rp');
	  
	   ?>Inactiva<?php
	  }else{echo "Activa";
	  }?>       
      </td>         
    </tr>
    <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
</table>
<?php 
   break;
   case "3":
   ?>
<!--REFILADO --> 
<table id="Exportar_a_Excel" border="1">   
  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">KILOS DESP.</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS TOTAL</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS MUERTOS</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS PREPARACION</td>
    <td nowrap="nowrap"id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap"id="titulo4">FECHA FINAL</td>     
    <td nowrap="nowrap"id="titulo4">OPERARIO</td>
    <td nowrap="nowrap"id="titulo4">REVISOR</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
  <tr>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['id_op_rp']; ?></td>
      <td nowrap="nowrap" id="dato2">
		<?php 
        $op_c=$row_orden_produccion['id_op_rp'];
        $sqln="SELECT * FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
        $resultn=mysql_query($sqln); 
        $numn=mysql_num_rows($resultn); 
        if($numn >= '1') 
        { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
        else { echo "";	
        }?>
      </td>
      <td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['version_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_prod_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['total_horas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_muertas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_prep_rp']; ?></td>      
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_fin_rp']; ?></td>      
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }?>
      </td>
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }?>
      </td>
      <td  nowrap="nowrap" id="dato2">      <?php  
	  $id_rp=$row_orden_produccion['id_rp'];
	  $sqlidrp="SELECT * FROM Tbl_reg_produccion WHERE id_rp='$id_rp' AND b_borrado_rp='1'";
	  $resultidrp= mysql_query($sqlidrp);
	  $numidrp= mysql_num_rows($resultidrp);
	  if($numidrp >='1')
	  { 
	  $idrp = mysql_result($resultidrp, 0, 'id_rp');
	  
	   ?>Inactiva<?php
	  }else{echo "Activa";
	  }?>       
      </td>         
    </tr>
    <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
</table>   
   <?php 
   break;
   case "4":
   ?>
   
<!--SELLADO --> 
<table id="Exportar_a_Excel" border="1">  

  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">KILOS DESP.</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS TOTAL</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS MUERTOS</td>
    <td nowrap="nowrap"id="titulo4">TIEMPOS PREPARACION</td>    
    <td nowrap="nowrap"id="titulo4">ROLLO</td>
    <td nowrap="nowrap"id="titulo4">METROS LINEAL</td>
    <td nowrap="nowrap"id="titulo4">PLACA</td>
    <td nowrap="nowrap"id="titulo4">BOLSAS/UND</td>
    <td nowrap="nowrap"id="titulo4">LAMINA 1</td>
    <td nowrap="nowrap"id="titulo4">LAMINA 2</td>
    <td nowrap="nowrap"id="titulo4">TURNO</td>
    <td nowrap="nowrap"id="titulo4">ROLLO</td>
    <td nowrap="nowrap"id="titulo4">NUMERACION INICIAL</td>
    <td nowrap="nowrap"id="titulo4">NUMERACION FINAL</td>
    <td nowrap="nowrap"id="titulo4">FECHA INICIO</td>
    <td nowrap="nowrap"id="titulo4">FECHA FINAL</td>  
    <td nowrap="nowrap"id="titulo4">OPERARIO</td>
    <td nowrap="nowrap"id="titulo4">REVISOR</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['id_op_rp']; ?></td>
      <td nowrap="nowrap" id="dato2">
      <?php 
	  $op_c=$row_orden_produccion['id_op_rp'];
	  $sqln="SELECT * FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
	  $resultn=mysql_query($sqln); 
	  $numn=mysql_num_rows($resultn); 
	  if($numn >= '1') 
	  { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
	  else { echo "";	
	  }?>
      </a></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['version_ref_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_prod_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['total_horas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_muertas_rp']; ?></td> 
      <td id="dato2"><?php echo $row_orden_produccion['horas_prep_rp']; ?></td>  
      <td id="dato2"><?php echo $row_orden_produccion['rollo_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['int_metro_lineal_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['placa_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['bolsa_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['lam1_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['lam2_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['turno_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['rollo_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['n_ini_rp']; ?></td>
      <td id="dato2"><?php echo $row_orden_produccion['n_fin_rp']; ?></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
      <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_fin_rp']; ?></td>  
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }?>
      </td>
      <td nowrap="nowrap"id="dato2">
      <?php  
	  $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
	  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
	  }?>
      </td>
      <td  nowrap="nowrap" id="dato2">
      <?php  
	  $id_rp=$row_orden_produccion['id_rp'];
	  $sqlidrp="SELECT * FROM Tbl_reg_produccion WHERE id_rp='$id_rp' AND b_borrado_rp='1'";
	  $resultidrp= mysql_query($sqlidrp);
	  $numidrp= mysql_num_rows($resultidrp);
	  if($numidrp >='1')
	  { 
	  $idrp = mysql_result($resultidrp, 0, 'id_rp');
	  
	   ?>Inactiva <?php
	  }else{echo "Activa";
	  }?>       
      </td>       	    
    </tr>
    <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
</table>  
  
<?php 
    break;
  }
?>  
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_produccion);

?>