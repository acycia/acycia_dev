<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once('funciones/funciones_php.php');

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
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$id_p=$_POST['id_p_ev'];
$calificacion_final=$_POST['porcentaje_final_ev'];
$responsable_calificacion=$_POST['responsable_registro_ev'];
$fecha_calificacion=$_POST['fecha_registro_ev'];

$sqlp="UPDATE proveedor_seleccion SET ultima_calificacion_p='$calificacion_final', registro_ultima_calificacion='$responsable_calificacion', fecha_ultima_calificacion_p='$fecha_calificacion' WHERE id_p_seleccion='$id_p'";
 
  $insertSQL = sprintf("INSERT INTO evaluacion_proveedor (id_ev, n_ev, id_p_ev, periodo_desde_ev, periodo_hasta_ev, total_oc_ev, total_verificacion_ev, total_oportunos_ev, total_no_oportunos_ev, porcentaje_oportunos_ev, total_cumple_ev, total_no_cumple_ev, porcentaje_cumple_ev, total_conforme_ev, total_no_conforme_ev, porcentaje_conforme_ev, total_atencion_ev, total_no_atencion_ev, porcentaje_atencion_ev, porcentaje_final_ev, calificacion_ev, calificacion_texto_ev, responsable_registro_ev, fecha_registro_ev, evaluacion) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_ev'], "int"),
                       GetSQLValueString($_POST['n_ev'], "int"),
                       GetSQLValueString($_POST['id_p_ev'], "int"),
                       GetSQLValueString($_POST['periodo_desde_ev'], "date"),
                       GetSQLValueString($_POST['periodo_hasta_ev'], "date"),
                       GetSQLValueString($_POST['total_oc_ev'], "int"),
                       GetSQLValueString($_POST['total_verificacion_ev'], "int"),
                       GetSQLValueString($_POST['total_oportunos_ev'], "int"),
                       GetSQLValueString($_POST['total_no_oportunos_ev'], "int"),
                       GetSQLValueString($_POST['porcentaje_oportunos_ev'], "double"),
                       GetSQLValueString($_POST['total_cumple_ev'], "int"),
                       GetSQLValueString($_POST['total_no_cumple_ev'], "int"),
                       GetSQLValueString($_POST['porcentaje_cumple_ev'], "double"),
                       GetSQLValueString($_POST['total_conforme_ev'], "int"),
                       GetSQLValueString($_POST['total_no_conforme_ev'], "int"),
                       GetSQLValueString($_POST['porcentaje_conforme_ev'], "double"),
                       GetSQLValueString($_POST['total_atencion_ev'], "int"),
                       GetSQLValueString($_POST['total_no_atencion_ev'], "int"),
                       GetSQLValueString($_POST['porcentaje_atencion_ev'], "double"),
                       GetSQLValueString($_POST['porcentaje_final_ev'], "double"),
                       GetSQLValueString($_POST['calificacion_ev'], "int"),
                       GetSQLValueString($_POST['calificacion_texto_ev'], "text"),
                       GetSQLValueString($_POST['responsable_registro_ev'], "text"),
                       GetSQLValueString($_POST['fecha_registro_ev'], "date"),
                       GetSQLValueString($_GET['evaluacion'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultp=mysql_query($sqlp);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "evaluacion_proveedor_vista.php?id_ev=" . $_POST['id_ev'] . "&id_p=" . $_POST['id_p'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM evaluacion_proveedor ORDER BY id_ev DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_ultimo_numero = "-1";
if (isset($_GET['id_p'])) {
  $colname_ultimo_numero = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ultimo_numero = sprintf("SELECT * FROM evaluacion_proveedor WHERE id_p_ev = %s ORDER BY n_ev DESC", $colname_ultimo_numero);
$ultimo_numero = mysql_query($query_ultimo_numero, $conexion1) or die(mysql_error());
$row_ultimo_numero = mysql_fetch_assoc($ultimo_numero);
$totalRows_ultimo_numero = mysql_num_rows($ultimo_numero);

$colname_bolsa_verificacion = "-1";
if (isset($_GET['id_p'])) {
  $colname_bolsa_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$desde= $_GET['desde'];
$hasta= $_GET['hasta'];
$query_bolsa_verificacion = sprintf("SELECT * FROM verificacion_bolsas WHERE id_p_vb = %s AND fecha_recibido_vb >= '$desde' AND fecha_recibido_vb <= '$hasta' ORDER BY fecha_recibido_vb ASC", $colname_bolsa_verificacion);
$bolsa_verificacion = mysql_query($query_bolsa_verificacion, $conexion1) or die(mysql_error());
$row_bolsa_verificacion = mysql_fetch_assoc($bolsa_verificacion);
$totalRows_bolsa_verificacion = mysql_num_rows($bolsa_verificacion);

$colname_rollo_verificacion = "-1";
if (isset($_GET['id_p'])) {
  $colname_rollo_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$desde= $_GET['desde'];
$hasta= $_GET['hasta'];
$query_rollo_verificacion = sprintf("SELECT * FROM verificacion_rollos WHERE id_p_vr = %s AND fecha_recibo_vr >= '$desde' AND fecha_recibo_vr <= '$hasta' ORDER BY fecha_recibo_vr ASC", $colname_rollo_verificacion);
$rollo_verificacion = mysql_query($query_rollo_verificacion, $conexion1) or die(mysql_error());
$row_rollo_verificacion = mysql_fetch_assoc($rollo_verificacion);
$totalRows_rollo_verificacion = mysql_num_rows($rollo_verificacion);

$colname_verificaciones_insumos = "-1";
if (isset($_GET['id_p'])) {
  $colname_verificaciones_insumos = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$desde= $_GET['desde'];
$hasta= $_GET['hasta'];
$query_verificaciones_insumos = sprintf("SELECT * FROM verificacion_insumos WHERE id_p_vi = %s AND fecha_vi >= '$desde' AND fecha_vi <= '$hasta' ORDER BY fecha_vi ASC", $colname_verificaciones_insumos);
$verificaciones_insumos = mysql_query($query_verificaciones_insumos, $conexion1) or die(mysql_error());
$row_verificaciones_insumos = mysql_fetch_assoc($verificaciones_insumos);
$totalRows_verificaciones_insumos = mysql_num_rows($verificaciones_insumos);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<link rel="stylesheet" type="text/css" href="css/general.css"/>
</head>
<body>
<table id="tabla3">
<tr><td align="center">
<table id="tabla1">
  <tr>
    <td colspan="2" id="fuente2"><img src="images/cabecera.jpg"></td>
    </tr>
  <tr>
    <td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul></td>
  </tr>
</table>
</tr></td>
<tr>
  <td id="titulo">EVALUACION DE DESEMPENYO DEL PROVEEDOR <?php echo $row_proveedor['tipo_servicio_p'];?></td>
</tr>
<tr>
<td id="dato2">
  <?php if($row_proveedor['tipo_servicio_p']=='PRODUCTOS'): 
       $tiposervicio="evaluacion_proveedor_add.php";
       else:
       $tiposervicio="evaluacion_proveedor_servicio_add.php";
   endif; 
   ?>
  <form name="form2" method="get" action="<?php echo $tiposervicio;?>">
    <table id="tabla3">
	<tr>
	<td id="fuente2">EVALUACION N. <strong></strong></td>
	<td id="fuente2">PROVEEDOR
	  <input name="id_p" type="hidden" id="id_p" value="<?php echo $row_proveedor['id_p']; ?>">

  </td>
	<td id="fuente2">PERIODO DESDE</td>
	<td id="fuente2">HASTA </td>
	<td id="fuente3"><a href="evaluacion_proveedor.php?id_p=<?php echo $row_proveedor['id_p']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>"><img src="images/cat.gif" alt="EVALUACIONES" border="0" style="cursor:hand;" /></a><a href="evaluacion_proveedor.php"><img src="images/e.gif" alt="CAMBIAR PROVEEDOR" border="0" style="cursor:hand;" /></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" /></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0" /></a><a href="orden_compra.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" border="0" /></a><a href="verificaciones_criticos.php"><img src="images/v.gif" style="cursor:hand;" alt="VERIFICACIONES (INSUMOS CRITICOS)" border="0" /></a></td>
	</tr>
	<tr>
	<td id="dato2"><input name="numero" type="text" id="numero" value="<?php if($_GET['numero']!='') { echo $_GET['numero']; } else { $numero=$row_ultimo_numero['n_ev']+1; echo $numero; } ?>" size="2"></td>
	 <td id="dato2"><strong><?php echo $row_proveedor['proveedor_p']; ?></strong></td>
	 <td id="dato2"><input name="desde" type="date" id="desde" value="<?php if($_GET['desde']=='') { echo date("Y-m-d"); } else { echo $_GET['desde']; } ?>" size="10"></td>
	 <td id="dato2"><input name="hasta" type="date" id="hasta" value="<?php if($_GET['hasta']=='') { echo date("Y-m-d"); } else { echo $_GET['hasta']; } ?>" size="10"></td>
	 <td id="dato1"><input name="Submit" type="submit" value="FILTRO"></td>
	</tr>
	 </table>        
	 </form>
    </td>
</tr>
</table>
<?php 
/*PRIORIDAD*/ $desde=$_GET['desde']; $hasta=$_GET['hasta'];
/*TOTALES PRINCIPALES*/ $suma_oc=0; $suma_vr=0;
/*TOTALES(Si)*/ $oportunos=0; $cumple=0; $abuena=0; $serviciosi=0; 
/*TOTALES(No)*/$noportunos=0; $nocumple=0; $amala=0; $serviciono=0;
if($desde != '' && $hasta != '') { 
$vi=$row_verificaciones_insumos['n_vi'];
$vr=$row_rollo_verificacion['n_vr'];
$vb=$row_bolsa_verificacion['n_vb'];
if($vi!='' || $vr!='' || $vb!='') { ?>
<table>
  <tr id="tr2">
    <td rowspan="2" id="arriba2">VERIF.</td>
    <td rowspan="2" id="arriba2">FECHA</td>
    <td rowspan="2" id="arriba2">O.C.</td>
    <td rowspan="2" id="arriba2">MATERIAL</td>
    <td rowspan="2" id="arriba2">SOLICITADO</td>
    <td rowspan="2" id="arriba2">PEDIDO</td>
    <td rowspan="2" id="arriba2">ENTREGA</td>
    <td rowspan="2" id="arriba2">TIEMPO (DIAS)</td>
    <td rowspan="2" id="arriba2">DOCUMENTO</td>
    <td colspan="4" id="arriba2">OPORTUNOS  (&lt;=0 dias) </td>
    <td colspan="3" id="arriba2">CANTIDAD (&gt;=90%) </td>
    <td colspan="3" id="arriba2">CALIDAD (&gt;=95%) </td>
    <td colspan="3" id="arriba2">SERVICIO ( <?php echo utf8_encode('Calificacíon');?> de 1 a 10) </td>
    </tr>
  <tr>
    <td id="arriba2">RECIBE</td>
    <td id="arriba2">FECHA</td>
    <td id="arriba2">ATRASO</td>
    <td id="arriba2">CUMPLE</td>
    <td id="arriba2">ENTREGADA</td>
    <td id="arriba2">%</td>
    <td id="arriba2">CUMPLE</td>
    <td id="arriba2">SUMA</td>
    <td id="arriba2">%</td>
    <td id="arriba2">CUMPLE</td>
    <td id="arriba2">CALIF.</td>
    <td id="arriba2">%</td>
    <td id="arriba2">CUMPLE</td>
  </tr>
  <?php /*INSUMOS CRITICOS*/
  if($row_verificaciones_insumos['n_vi']!='') { ?>
  <tr>
    <td colspan="11" id="fuente1">Insumos Criticos</td>
    <td colspan="11" id="fuente1">+ 7 DIAS</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
      <td id="abajo3">
        <?php echo $row_verificaciones_insumos['n_vi']; 
            $verificacion1=$row_verificaciones_insumos['n_vi']; 
           if($verificacion1!='') { $suma_vr=$suma_vr+1; }//VERIF ?>
        </td>
        <td nowrap id="abajo2">
          <?php echo $row_verificaciones_insumos['fecha_vi'];//FECHA ?>
        </td>
        <td id="abajo3">
          <?php echo $row_verificaciones_insumos['n_oc_vi'];//O.C. ?>
        </td>      
        <td nowrap id="abajo1">
          <?php $id_insumo=$row_verificaciones_insumos['id_insumo_vi'];
            $sql2="SELECT * FROM insumo WHERE id_insumo='$id_insumo'";
            $result2=mysql_query($sql2);
            $num2=mysql_num_rows($result2);
            if ($num2 >='1') { $descripcion_insumo=mysql_result($result2,0,'descripcion_insumo'); } 
            echo $descripcion_insumo;//MATERIAL
           ?>
        </td>
        <td id="abajo3">
          <?php echo $row_verificaciones_insumos['cantidad_solicitada_vi'];//SOLICITADO ?> 
        </td>      
        <td nowrap id="abajo2" title="FECHA PEDIO DE O.C" >
        <?php $n_oc=$row_verificaciones_insumos['n_oc_vi'];
        $sql2="SELECT * FROM orden_compra WHERE n_oc='$n_oc'";
        $result2=mysql_query($sql2);
        $num2=mysql_num_rows($result2); 
        if ($num2>='1') {
         $fecha_pedido=mysql_result($result2,0,'fecha_pedido_oc');
         $fecha_entrega=mysql_result($result2,0,'fecha_entrega_oc'); //$row_verificaciones_insumos['fecha_registro_vi'];
       } 
          echo $fecha_pedido;//PEDIDO?>
       </td>
       <td nowrap id="abajo2" title="FECHA RECIBIDO DE O.C">
        <?php 
          echo $fecha_entrega;//FECHA ENTREGA
          
         ?> 
       </td>
       <td id="abajo2" title="RESTA FECHA RECIBIDO - FECHA PEDIO DE O.C">
          <?php 
             if($fecha_pedido!='' && $fecha_entrega!='') { 
              //defino fecha 1 
              $ano1 = substr($fecha_pedido,0,4);
              $mes1 = substr($fecha_pedido,5,2); 
              $dia1 = substr($fecha_pedido,8,2); 
              //defino fecha 2 
              $ano2 = substr($fecha_entrega,0,4); 
              $mes2 = substr($fecha_entrega,5,2); 
              $dia2 = substr($fecha_entrega,8,2); 
              //calculo timestam de las dos fechas 
              $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
              $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
              //resto a una fecha la otra 
              $segundos_diferencia = $timestamp1 - $timestamp2; 
              //echo $segundos_diferencia; 
              //convierto segundos en días 
              $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
              //obtengo el valor absoulto de los días (quito el posible signo negativo)
              $dias_diferencia = abs($dias_diferencia); 
              //quito los decimales a los días de diferencia 
              $dias_diferencia= floor($dias_diferencia);  
              //echo $dias_diferencia=RestarFechasNew($fecha_pedido, $fecha_entrega);
              echo $dias_diferencia;//TIEMPO (DIAS)
             } 
           ?>
         </td>      
         <td id="abajo2">
             <?php 
             $factura=$row_verificaciones_insumos['factura_vi'];
             $remision=$row_verificaciones_insumos['remision_vi'];
             if($factura!='') { echo $factura; } else{ echo $remision; }//DOCUMENTO ?> 
          </td>

          <td id="abajo2" title="RECIBE TOTAL O PARCIAL">
            <?php echo $row_verificaciones_insumos['entrega_vi'];//OPORTUNOS  RECIBE TOTAL O PARCIAL ?> 
          </td>
          <td nowrap id="abajo2" title="FECHA REGISTRO VERIFICACION INSUMO + 7 DIAS DE GRACIA">
            <?php 
                echo  $fecha_recibe=$row_verificaciones_insumos['fecha_registro_vi'];//OPORTUNOS  FECHA
               $fecha_recibe = RestaDias($fecha_recibe,'7');
            ?> 
           </td>
          <td id="abajo2" title="RESTA REGISTRO VERIFICACION  - FECHA RECIBIDO DE O.C CON 7 DIAS DE GRACIA">
              <?php  

               if($fecha_recibe!='' && $fecha_entrega!='') { 
                 
                 
                 $ano1 = substr($fecha_recibe,0,4);
                 $mes1 = substr($fecha_recibe,5,2); 
                 $dia1 = substr($fecha_recibe,8,2); 
                 //defino fecha 2 
                 $ano2 = substr($fecha_entrega,0,4); 
                 $mes2 = substr($fecha_entrega,5,2); 
                 $dia2 = substr($fecha_entrega,8,2); 
                 //calculo timestam de las dos fechas 
                 $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
                 $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
                 //resto a una fecha la otra 
                 $segundos_diferencia = $timestamp1 - $timestamp2; 
                 //echo $segundos_diferencia; 
                 //convierto segundos en días 
                 $dias_diferencia2 = $segundos_diferencia / (60 * 60 * 24); 
                 $dias_diferencia2= floor($dias_diferencia2);
                 //echo $dias_diferencia2=RestarFechasNew($fecha_recibe, $fecha_entrega);
                 echo $dias_diferencia2; 

                }
              ?>
           </td>  
          <td id="abajo2" title="OPORTUNIDAD (Tiempo de entrega) (<=0 dias)"> 
               <?php $entrega=$row_verificaciones_insumos['entrega_vi'];
              if($entrega=='TOTAL' && $dias_diferencia2=='0')
                { echo "Si"; $oportunos=$oportunos+1; }
              if($entrega=='TOTAL' && $dias_diferencia2<'0')
                { echo "Si"; $oportunos=$oportunos+1; }	

              if($entrega=='TOTAL' && $dias_diferencia2>'0')
                { echo "No"; $noportunos=$noportunos+1;	}
              if($entrega=='PARCIAL' && $dias_diferencia2=='0')
                { echo "Si"; $oportunos=$oportunos+1; }			
              if($entrega=='PARCIAL' && $dias_diferencia2<'0')
                { echo "Si"; $oportunos=$oportunos+1; }
              if($entrega=='PARCIAL' && $dias_diferencia2>'0')
                { echo "No"; $noportunos=$noportunos+1;	}//OPORTUNOS CUMPLE
                 ?> 
           </td> 




           <td id="abajo3" title="CANTIDAD RECIBIDA VERIFICACION DE INSUMOS">
            <?php 
                echo $row_verificaciones_insumos['cantidad_recibida_vi'];//CANTIDAD ENTREGADA
             ?> 
            </td> 
             <td nowrap id="abajo3" title="CANTIDAD SOLICITADA - CANTIDAD RECIBIDA (Total entregado) (>=90%)">
              <?php	

                $cant1=$row_verificaciones_insumos['cantidad_solicitada_vi'];
                $cant2=$row_verificaciones_insumos['cantidad_recibida_vi'];

             if($cant1!='' && $cant2!='') {
               $cant3 = ($cant2/$cant1)*100;
               $cant3=round($cant3*100)/100; 	
               if($cant3>'100') 
                { 
                  $cant3=100; 
                }
                 } else { 
                  $cant3=0; 
                } 

               echo $cant3;//CANTIDAD % ?>
               %
             </td>  
             <td id="abajo2" title="SI LA ENTREGA >= 90%">
                <?php	
                  if($cant3>='90') { echo "Si"; $cumple=$cumple+1; }
                  if($cant3<'90') {	echo "No";	$nocumple=$nocumple+1; }//CANTIDAD CUMPLE 
                ?> 
             </td>  


 
             <td id="abajo3">
                <?php 
                  $apariencia=$row_verificaciones_insumos['apariencia_vi'];
                  if($apariencia=='0') { echo "Mala"; }
                  if($apariencia=='0.5') { echo "Regular"; }
                  if($apariencia=='1') { echo "Buena"; }//CALIDAD SUMA 
                ?>
              </td>
              <td id="abajo3" title="APARIENCIA DEL INSUMO RECIBIDO">
                <?php 
                   if($apariencia=='0') { $porcentaje_conf=0; echo "0%"; }
                   if($apariencia=='0.5') { $porcentaje_conf=50; echo "50%"; }
                   if($apariencia=='1') { $porcentaje_conf=100; echo "100%"; }//CALIDAD % 
                ?> 
              </td>
             <td id="abajo2"><?php if($porcentaje_conf!='') {
                 if($porcentaje_conf>='95') { echo "Si"; $abuena=$abuena+1; }
                 if($porcentaje_conf<'95') { echo "No"; $amala=$amala+1; } }//CALIDADCUMPLE
                  ?>
              </td>




              <td id="abajo3"><?php 
                  echo $cump_servicio=$row_verificaciones_insumos['servicio_vi'];//SERVICIO  CALIF
                   ?>  
              </td>
             <td nowrap id="abajo3">
                <?php 
                   if($cump_servicio!='') { $porc_servicio=($cump_servicio/10)*100;
                      $porc_servicio=round($porc_servicio*100)/100; } else { $porc_servicio=0; } 
                       echo $porc_servicio;//SERVICIO % 
                   ?>
                   %
             </td> 
              <td id="abajo2">
                <?php if($porc_servicio>='75') { echo "Si"; $serviciosi=$serviciosi+1; }
                 if($porc_servicio<'75') { echo "No"; $serviciono=$serviciono+1; }//SERVICIO CUMPLE
                 ?> 
             </td>
    </tr>
  <?php } while ($row_verificaciones_insumos = mysql_fetch_assoc($verificaciones_insumos)); ?><?php } ?>




	<?php /*MATERIA PRIMA ROLLOS*/
	        if($row_rollo_verificacion['n_vr']!='') { ?>
	<tr>	
      <td colspan="22" id="fuente1">Materia Prima Rollos</td>
    </tr><?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
        <td id="abajo3"><?php echo $row_rollo_verificacion['n_vr']; 
		$verificacion1=$row_rollo_verificacion['n_vr']; 
		  if($verificacion1!='') { $suma_vr=$suma_vr+1; } ?></td>
        <td nowrap id="abajo2"><?php echo $row_rollo_verificacion['fecha_recibo_vr']; ?></td>
        <td id="abajo3"><?php echo $row_rollo_verificacion['n_ocr_vr']; ?></td>
        <td nowrap id="abajo1"><?php $id_rollo=$row_rollo_verificacion['id_rollo_vr'];
	$sqlrollo="SELECT * FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
	$resultrollo=mysql_query($sqlrollo);
	$numrollo=mysql_num_rows($resultrollo);
	if ($numrollo >='1') { $nombre_rollo=mysql_result($resultrollo,0,'nombre_rollo');	
	} echo $nombre_rollo; ?></td>
        <td id="abajo3"><?php echo $row_rollo_verificacion['cantidad_solicitada_vr']; ?></td>
        <td nowrap id="abajo2"><?php $n_ocr=$row_rollo_verificacion['n_ocr_vr'];
		$sqlocr="SELECT * FROM orden_compra_rollos WHERE n_ocr='$n_ocr'";
		$resultocr=mysql_query($sqlocr);
		$numocr=mysql_num_rows($resultocr);
		if($numocr>='1') {
			$fecha_pedido=mysql_result($resultocr,0,'fecha_pedido_ocr');
			$fecha_entrega=mysql_result($resultocr,0,'fecha_entrega_ocr');
		} echo $fecha_pedido; ?></td>
        <td nowrap id="abajo2"><?php echo $fecha_entrega; ?></td>
        <td id="abajo2"><?php 
		if($fecha_pedido!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_pedido,0,4);
			  $mes1 = substr($fecha_pedido,5,2); 
			  $dia1 = substr($fecha_pedido,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en días 
			  $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los días (quito el posible signo negativo)
			  $dias_diferencia = abs($dias_diferencia); 
			  //quito los decimales a los días de diferencia 
			  $dias_diferencia= floor($dias_diferencia);			 
			  echo $dias_diferencia; } ?></td>
        <td id="abajo2"><?php 
	  $factura=$row_rollo_verificacion['factura_vr'];
	  $remision=$row_rollo_verificacion['remision_vr'];
	  if($factura != '') { echo $factura; } else{ echo $remision; } ?></td>
        <td id="abajo2"><?php if($row_rollo_verificacion['entrega_vr']=='0') { echo "PARCIAL"; }
		if($row_rollo_verificacion['entrega_vr']=='1') { echo "TOTAL"; } ?></td>
        <td nowrap id="abajo2"><?php echo $row_rollo_verificacion['fecha_recibo_vr']; ?></td>
        <td id="abajo2"><?php $fecha_recibe=$row_rollo_verificacion['fecha_recibo_vr'];

			if($fecha_recibe!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_recibe,0,4);
			  $mes1 = substr($fecha_recibe,5,2); 
			  $dia1 = substr($fecha_recibe,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en días 
			  $dias_diferencia2 = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los días (quito el posible signo negativo)
			  //$dias_diferencia2 = abs($dias_diferencia2);	   
			  //quito los decimales a los días de diferencia 
			  $dias_diferencia2= floor($dias_diferencia2);
			  echo $dias_diferencia2; } ?></td>
        <td id="abajo2"><?php $entrega=$row_rollo_verificacion['entrega_vr'];
		if($entrega=='1' && $dias_diferencia2=='0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='1' && $dias_diferencia2<'0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='1' && $dias_diferencia2>'0')
		{ echo "No"; $noportunos=$noportunos+1; }
		if($entrega=='0' && $dias_diferencia2=='0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='0' && $dias_diferencia2<'0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='0' && $dias_diferencia2>'0')
		{ echo "No"; $noportunos=$noportunos+1; } ?></td>
        <td id="abajo3"><?php echo $row_rollo_verificacion['cantidad_encontrada_vr']; ?></td>
        <td nowrap id="abajo3"><?php //Porcentaje de cumplimiento
		$cant1=$row_rollo_verificacion['cantidad_solicitada_vr'];
		$cant2=$row_rollo_verificacion['cantidad_encontrada_vr'];
		if($cant1!='' && $cant2!='')
		{
		   $cant3=($cant2/$cant1)*100;
		   $cant3=round($cant3*100)/100;
		   if($cant3>'100') { $cant3=100; } 
		} else { $cant3=0; } echo $cant3; ?>%</td>
        <td id="abajo2"><?php if($cant3>='90') { echo "Si";	$cumple=$cumple+1; }
		if($cant3<'90') { echo "No"; $nocumple=$nocumple+1; } ?></td>
        <td id="abajo3"><?php $conformidad=0; $cont=0;		
		 $cantidad=$row_rollo_verificacion['cantidad_cumple_vr'];
		 if($cantidad!='2') { $conformidad=$cantidad+$conformidad; $cont=$cont+1; }
		 $calibre=$row_rollo_verificacion['calibre_cumple_vr'];
		 if($calibre!='2') { $conformidad=$calibre+$conformidad; $cont=$cont+1; }
		 $peso=$row_rollo_verificacion['peso_cumple_vr'];
		 if($peso!='2') { $conformidad=$peso+$conformidad; $cont=$cont+1; }
		 $ancho=$row_rollo_verificacion['ancho_cumple_vr'];
		 if($ancho!='2') { $conformidad=$ancho+$conformidad; $cont=$cont+1; }
		 $repeticion=$row_rollo_verificacion['rodillo_cumple_vr'];
		 if($repeticion!='2') { $conformidad=$repeticion+$conformidad; $cont=$cont+1; }
		 $tto=$row_rollo_verificacion['tratamiento_cump_vr'];
		 if($tto!='2') { $conformidad=$tto+$conformidad; $cont=$cont+1; }
		 $md=$row_rollo_verificacion['md_cumple_vr'];
		 if($md!='2') { $conformidad=$md+$conformidad; $cont=$cont+1; }
		 $td=$row_rollo_verificacion['td_cumple_vr'];
		 if($td!='2') { $conformidad=$td+$conformidad; $cont=$cont+1; }
		 $angulo=$row_rollo_verificacion['angulo_cumple_vr'];
		 if($angulo!='2') { $conformidad=$angulo+$conformidad; $cont=$cont+1; }
		 $fuerza=$row_rollo_verificacion['fuerzaselle_cumple_vr'];
		 if($fuerza!='2') { $conformidad=$fuerza+$conformidad; $cont=$cont+1; }
		 $apariencia=$row_rollo_verificacion['apariencia_cumple_vr'];
		 if($apariencia!='2') { $conformidad=$apariencia+$conformidad; $cont=$cont+1; }
		 $sellos=$row_rollo_verificacion['resistencia_sellos_cumple_vr'];
		 if($sellos!='2') { $conformidad=$sellos+$conformidad; $cont=$cont+1; }
		 $impresion=$row_rollo_verificacion['impresion_cumple_vr'];
		 if($impresion!='2') { $conformidad=$impresion+$conformidad; $cont=$cont+1; }
		 $color=$row_rollo_verificacion['color_cumple_vr'];
		 if($color!='2') { $conformidad=$color+$conformidad; $cont=$cont+1; }
		 $tinta=$row_rollo_verificacion['tinta_cumple_vr'];
		 if($tinta!='2') { $conformidad=$tinta+$conformidad; $cont=$cont+1; }
		 echo $conformidad;?></td>
        <td nowrap id="abajo3"><?php if($conformidad!='') {
			  $porcentaje1=($conformidad/$cont)*100;			  
			  $porcentaje_conf=floor($porcentaje1);
			  } else { $porcentaje_conf=0; } echo $porcentaje_conf; ?>%</td>
        <td id="abajo2"><?php if($porcentaje_conf>='95') { echo "Si"; $abuena=$abuena+1; }
			  if($porcentaje_conf<'95') { echo "No"; $amala=$amala+1; } ?></td>
        <td id="abajo3"><?php echo $row_rollo_verificacion['servicio_vr']; $cump_servicio=$row_rollo_verificacion['servicio_vr']; ?></td>
        <td nowrap id="abajo3"><?php if($cump_servicio!='') {
			  $porc_servicio=($cump_servicio/10)*100;
			  $porc_servicio = floor($porc_servicio); 
			  } else { $porc_servicio=0; } echo $porc_servicio; ?>%</td>
        <td id="abajo2"><?php 
			  if($porc_servicio>='75') { echo "Si"; $serviciosi=$serviciosi+1; }
			  if($porc_servicio<'75') { echo "No"; $serviciono=$serviciono+1;  } ?></td>
      </tr>
      <?php } while ($row_rollo_verificacion = mysql_fetch_assoc($rollo_verificacion));?>
	  <?php } ?>	




	  <?php /*PRODUCTO TERMINADO BOLSAS*/
	  if($row_bolsa_verificacion['n_vb']!='') { ?>
    <tr>
      <td colspan="22" id="fuente1">Producto Terminado Bolsas</td>
    </tr>
    <?php do { ?>
      <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
        <td id="abajo3"><?php echo $row_bolsa_verificacion['n_vb'];
	  $verificacion1=$row_bolsa_verificacion['n_vb'];
	  if($verificacion1!='') { $suma_vr=$suma_vr+1; } ?></td>
        <td nowrap id="abajo2"><?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?></td>
        <td id="abajo3"><?php echo $row_bolsa_verificacion['n_ocb_vb']; ?></td>
        <td nowrap id="abajo1"><?php $id_bolsa=$row_bolsa_verificacion['id_bolsa_vb'];
	  $sqlbolsa="SELECT * FROM material_terminado_bolsas WHERE id_bolsa='$id_bolsa'";
	  $resultbolsa=mysql_query($sqlbolsa);
	  $numbolsa=mysql_num_rows($resultbolsa);
	  if($numbolsa>='1') { $nombre_bolsa=mysql_result($resultbolsa,0,'nombre_bolsa'); }
	  echo $nombre_bolsa; ?></td>
        <td id="abajo3"><?php echo $row_bolsa_verificacion['cantidad_solicitada_vb']; ?></td>
        <td nowrap id="abajo2"><?php $n_ocb=$row_bolsa_verificacion['n_ocb_vb'];
		$sqlocb="SELECT * FROM orden_compra_bolsas WHERE n_ocb='$n_ocb'";
		$resultocb=mysql_query($sqlocb);
		$numocb=mysql_num_rows($resultocb);
		if($numocb>='1') {
			$fecha_pedido=mysql_result($resultocb,0,'fecha_pedido_ocb');
			$fecha_entrega=mysql_result($resultocb,0,'fecha_entrega_ocb');
		} echo $fecha_pedido; ?></td>
        <td nowrap id="abajo2"><?php echo $fecha_entrega; ?></td>
        <td id="abajo2"><?php if($fecha_pedido!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_pedido,0,4);
			  $mes1 = substr($fecha_pedido,5,2); 
			  $dia1 = substr($fecha_pedido,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en días 
			  $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los días (quito el posible signo negativo)
			  $dias_diferencia = abs($dias_diferencia); 
			  //quito los decimales a los días de diferencia 
			  $dias_diferencia= floor($dias_diferencia);			 
			  echo $dias_diferencia; } ?></td>
        <td id="abajo2"><?php 
		$factura=$row_bolsa_verificacion['factura_vb'];
		$remision=$row_bolsa_verificacion['remision_vb'];
	  if($factura!='') { echo $factura; } else{ echo $remision; } ?></td>
        <td id="abajo2"><?php if($row_bolsa_verificacion['entrega_vb']=='0') { echo "PARCIAL"; }
	  if($row_bolsa_verificacion['entrega_vb']=='1') { echo "TOTAL"; } ?></td>
        <td nowrap id="abajo2"><?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?></td>
        <td id="abajo2"><?php $fecha_recibe=$row_bolsa_verificacion['fecha_recibido_vb'];
        $fecha_recibe = RestaDias($fecha_recibe,'7');

			if($fecha_recibe!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_recibe,0,4);
			  $mes1 = substr($fecha_recibe,5,2); 
			  $dia1 = substr($fecha_recibe,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en días 
			  $dias_diferencia2 = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los días (quito el posible signo negativo)
			  //$dias_diferencia2 = abs($dias_diferencia2);	   
			  //quito los decimales a los días de diferencia 
			  $dias_diferencia2= floor($dias_diferencia2);
			  echo $dias_diferencia2; } ?></td>
        <td id="abajo2"><?php $entrega=$row_bolsa_verificacion['entrega_vb'];
		if($entrega=='1' && $dias_diferencia2=='0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='1' && $dias_diferencia2<'0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='1' && $dias_diferencia2>'0')
		{ echo "No"; $noportunos=$noportunos+1;	}
		if($entrega=='0' && $dias_diferencia2=='0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='0' && $dias_diferencia2<'0')
		{ echo "Si"; $oportunos=$oportunos+1; }
		if($entrega=='0' && $dias_diferencia2>'0')
		{ echo "No"; $noportunos=$noportunos+1;	} ?></td>
        <td id="abajo3"><?php echo $row_bolsa_verificacion['cantidad_encontrada_vb']; ?></td>
        <td nowrap id="abajo3"><?php //Porcentaje de cumplimiento
		$cant1=$row_bolsa_verificacion['cantidad_solicitada_vb'];
		$cant2=$row_bolsa_verificacion['cantidad_encontrada_vb'];
		if($cant1!='' && $cant2!='')
		{
		   $cant4=($cant2/$cant1)*100;
		   $cant3=round($cant4*100)/100;
		   if($cant3>'100') { $cant3=100; } } else{ $cant3=0; } echo $cant3; ?>%</td>
        <td id="abajo2"><?php 
			if($cant3>='90') { echo "Si";	$cumple=$cumple+1; }
			if($cant3<'90') { echo "No"; $nocumple=$nocumple+1; } ?></td>
        <td id="abajo3"><?php $conformidad=0; $cont=0;
	  $cantidad=$row_bolsa_verificacion['cantidad_cumple_vb'];
	  if($cantidad!='2') { $conformidad=$cantidad+$conformidad; $cont=$cont+1; }
	  $calibre=$row_bolsa_verificacion['calibre_cumple_vb'];
	  if($calibre!='2') { $conformidad=$calibre+$conformidad; $cont=$cont+1; }
	  $ancho=$row_bolsa_verificacion['ancho_cumple_vb'];
	  if($ancho!='2') { $conformidad=$ancho+$conformidad; $cont=$cont+1; }
	  $largo=$row_bolsa_verificacion['largo_cumple_vb'];
	  if($largo!='2') { $conformidad=$largo+$conformidad; $cont=$cont+1; }
	  $solapa=$row_bolsa_verificacion['solapa_cumple_vb'];
	  if($solapa!='2') { $conformidad=$solapa+$conformidad; $cont=$cont+1; }
	  $fuelle=$row_bolsa_verificacion['fuelle_cumple_vb'];
	  if($fuelle!='2') { $conformidad=$fuelle+$conformidad; $cont=$cont+1; }
	  $empaque=$row_bolsa_verificacion['empaque_cumple_vb'];
	  if($empaque!='2') { $conformidad=$empaque+$conformidad; $cont=$cont+1; }
	  $apariencia=$row_bolsa_verificacion['apariencia_cumple_vb'];
	  if($apariencia!='2') { $conformidad=$apariencia+$conformidad; $cont=$cont+1; }
	  $resistencia=$row_bolsa_verificacion['resistencia_cumple_vb'];
	  if($resistencia!='2') { $conformidad=$resistencia+$conformidad; $cont=$cont+1; }
	  $tratamiento=$row_bolsa_verificacion['tratamiento_cumple_vb'];
	  if($tratamiento!='2') { $conformidad=$tratamiento+$conformidad; $cont=$cont+1; }
	  echo $conformidad; ?></td>
        <td nowrap id="abajo3"><?php if($conformidad!='') {
			  $porcentaje1=($conformidad/$cont)*100;			  
			  $porcentaje_conf=floor($porcentaje1); } else { $porcentaje_conf=0; }
			  echo $porcentaje_conf; ?>%</td>
        <td id="abajo2"><?php 
			  if($porcentaje_conf>='95') { echo "Si"; $abuena=$abuena+1; }
			  if($porcentaje_conf<'95')  { echo "No"; $amala=$amala+1; } ?></td>
        <td id="abajo3"><?php echo $row_bolsa_verificacion['servicio_vb']; $cump_servicio=$row_bolsa_verificacion['servicio_vb']; ?></td>
        <td nowrap id="abajo3"><?php if($cump_servicio!='') {
			  $porc_servicio=($cump_servicio/10)*100;
			  $porc_servicio = floor($porc_servicio);
			  } else { $porc_servicio=0; } echo $porc_servicio; ?>%</td>
        <td id="abajo2"><?php 
			  if($porc_servicio>='75') { echo "Si"; $serviciosi=$serviciosi+1; }
			  if($porc_servicio<'75') { echo "No"; $serviciono=$serviciono+1; } ?></td>
      </tr>
      <?php } while ($row_bolsa_verificacion = mysql_fetch_assoc($bolsa_verificacion)); ?><?php } ?> 





    <tr>
      <td colspan="22" id="dato3">
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('numero','','R','desde','','R','hasta','','R','total_verificacion_ev','','R','total_oc_ev','','R','total_oportunos_ev','','R','total_cumple_ev','','R','total_conforme_ev','','R','total_atencion_ev','','R','total_no_oportunos_ev','','R','total_no_cumple_ev','','R','total_no_conforme_ev','','R','total_no_atencion_ev','','R','porcentaje_oportunos_ev','','R','porcentaje_cumple_ev','','R','porcentaje_conforme_ev','','R','porcentaje_atencion_ev','','R','porcentaje_final_ev','','R','responsable_registro_ev','','R','fecha_registro_ev','','R','calificacion_texto_ev','','R');return document.MM_returnValue">
    <table id="tabla3">      
      <tr>
        <td colspan="2" id="fuente1">Verificaciones
        <input name="total_verificacion_ev" type="text" id="total_verificacion_ev" value="<?php echo $suma_vr; ?>" size="5">
        <input name="id_ev" type="hidden" value="<?php $numero=$row_ultimo['id_ev']+1; echo $numero; ?>">
        <input type="hidden" name="n_ev" value="<?php echo $_GET['numero']; ?>" size="2">
        <input name="id_p_ev" type="hidden" value="<?php echo $row_proveedor['id_p']; ?>">
        <input name="periodo_desde_ev" type="hidden" value="<?php echo $_GET['desde']; ?>">
        <input name="periodo_hasta_ev" type="hidden" value="<?php echo $_GET['hasta']; ?>">
Total O.C.
<?php 
		$id_p=$row_proveedor['id_p'];
		$desde=$_GET['desde'];
		$hasta=$_GET['hasta'];
		if($id_p!='')
		{ $sqlp="SELECT DISTINCT n_oc_vi FROM verificacion_insumos WHERE id_p_vi = '$id_p' AND fecha_vi >= '$desde' AND fecha_vi <= '$hasta' ORDER BY fecha_vi ASC";
		$resultp=mysql_query($sqlp);
		$nump=mysql_num_rows($resultp);
		if ($nump>='1') { for($i=0; $i<$nump; $i++) { $suma_oc=$suma_oc+1; } } 
		$sqlp2="SELECT DISTINCT n_ocr_vr FROM verificacion_rollos WHERE id_p_vr = '$id_p' AND fecha_recibo_vr >= '$desde' AND fecha_recibo_vr <= '$hasta' ORDER BY fecha_recibo_vr ASC";
		$resultp2=mysql_query($sqlp2);
		$nump2=mysql_num_rows($resultp2);
		if ($nump2>='1') { for($i=0; $i<$nump2; $i++) { $suma_oc=$suma_oc+1; } }
		$sqlp3="SELECT DISTINCT n_ocb_vb FROM verificacion_bolsas WHERE id_p_vb = '$id_p' AND fecha_recibido_vb >= '$desde' AND fecha_recibido_vb <= '$hasta' ORDER BY fecha_recibido_vb ASC";
		$resultp3=mysql_query($sqlp3);
		$nump3=mysql_num_rows($resultp3);
		if ($nump3>='1') { for($i=0; $i<$nump3; $i++) { $suma_oc=$suma_oc+1; } }
		} ?>
<input name="total_oc_ev" type="text" id="total_oc_ev" value="<?php echo $suma_oc; ?>" size="5"></td>
        <td id="fuente3">Oportunos <input name="total_oportunos_ev" type="text" id="total_oportunos_ev" value="<?php echo $oportunos; ?>" size="5"></td>        
        <td id="fuente3">Cumple <input name="total_cumple_ev" type="text" id="total_cumple_ev" size="5" value="<?php echo $cumple; ?>"></td>
        <td id="fuente3">Conforme <input name="total_conforme_ev" type="text" id="total_conforme_ev" value="<?php echo $abuena; ?>" size="5"></td>
        <td id="fuente3">Atencion <input name="total_atencion_ev" type="text" id="total_atencion_ev" value="<?php echo $serviciosi; ?>" size="5"></td>
      </tr>
      <tr>
        <td colspan="2" rowspan="2" id="dato1">Se evaluan cuatro clases de evaluacion   con un 25% cada uno, para completar 100% en el porcentaje final.</td>
        <td nowrap id="fuente3">No oportunos
        <input name="total_no_oportunos_ev" type="text" id="total_no_oportunos_ev" value="<?php echo $noportunos; ?>" size="5"></td>
        <td nowrap id="fuente3">No cumple
          <input name="total_no_cumple_ev" type="text" id="total_no_cumple_ev" value="<?php echo $nocumple; ?>" size="5"></td>
        <td nowrap id="fuente3">No conforme 
        <input name="total_no_conforme_ev" type="text" id="total_no_conforme_ev" value="<?php echo $amala; ?>" size="5"></td>
        <td nowrap id="fuente3">No atencion<input name="total_no_atencion_ev" type="text" id="total_no_atencion_ev" value="<?php echo $serviciono; ?>" size="5"></td>
      </tr>
      <tr>
        <td nowrap id="fuente3">% Oportunos <?php $porc=(($oportunos/$suma_vr)*25); $porcentaje=round($porc*100)/100; if($porcentaje=='') { $porcentaje=0; } ?>
        <input name="porcentaje_oportunos_ev" type="text" id="porcentaje_oportunos_ev" value="<?php echo $porcentaje; ?>" size="5"></td>        
        <td nowrap id="fuente3">% Cumple <?php $porc2=($cumple/$suma_vr)*25; $porcentaje2=round($porc2*100)/100; if($porcentaje2=='') { $porcentaje2=0; } ?>
        <input name="porcentaje_cumple_ev" type="text" id="porcentaje_cumple_ev" value="<?php echo $porcentaje2; ?>" size="5"></td>
        <td nowrap id="fuente3">% Conforme <?php $porc_conforme1=($abuena/$suma_vr)*25;	
		$porc_conforme=round($porc_conforme1*100)/100; if($porc_conforme=='') { $porc_conforme=0; }  ?><input name="porcentaje_conforme_ev" type="text" id="porcentaje_conforme_ev" value="<?php echo $porc_conforme; ?>" size="5"></td>
        <td nowrap id="fuente3">% Atencion <?php $porc_atencion1=($serviciosi/$suma_vr)*25;
		$porc_atencion=round($porc_atencion1*100)/100; if($porc_atencion=='') { $porc_atencion=0; } ?><input name="porcentaje_atencion_ev" type="text" id="porcentaje_atencion_ev" value="<?php echo $porc_atencion; ?>" size="5"></td>
      </tr>
      <tr>
        <td colspan="6" id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" id="numero1"><strong>PORCENTAJE FINAL (%)</strong>
		<?php $total=0; $total=$porcentaje+$porcentaje2+$porc_conforme+$porc_atencion; ?> 
            <input name="porcentaje_final_ev" type="text" id="porcentaje_final_ev" size="5" value="<?php echo $total; ?>"><?php  if($total>='0' && $total<='50') { $calif=1;	}
			if($total>='51' && $total<='80') { $calif=2; }
			if($total>='81' && $total<='100') {	$calif=3; } ?>
          <input name="calificacion_ev" type="hidden" id="calificacion_ev" value="<?php echo $calif; ?>"></td>
      </tr>
      <tr id="tr1">
        <td colspan="6" id="fuente1">CALIFICACION</td>
      </tr>
      <tr>
        <td colspan="6" id="fuente1">
           <textarea name="calificacion_texto_ev" cols="80" rows="2" id="calificacion_texto_ev"><?php 
                 if($calif=='1') { echo "Esta entre 0%-50%: El proveedor no debe continuar a menos que sea el único o se defina un plan riguroso."; }
                 if($calif=='2') { echo "Esta entre 51% - 80%: Se debe establecer un plan de acción."; }
                 if($calif=='3') { echo "Esta entre 81% - 100%: Se considera que cumple para la organizacion."; }
             ?>
           </textarea>
        </td>
      </tr>
      <tr id="tr1">
        <td id="fuente2">RESPONSABLE DEL REGISTRO </td>
        <td id="fuente2">FECHA DE REGISTRO </td>
        <td colspan="4" id="fuente2">Finalice solo si los datos estan correctos </td>
        </tr>
      <tr>
        <td id="dato2">
          <input type="text" name="responsable_registro_ev" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30">
        </td>
        <td id="dato2">
          <input name="evaluacion" type="hidden" id="evaluacion" value="<?php echo $_GET['evaluacion']=='' ? $row_proveedores['tipo_servicio_p'] : $_GET['evaluacion']; ?>"> 
          <input type="text" name="fecha_registro_ev" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
        <td colspan="4" id="dato2"><input name="submit" type="submit" class="botonFinalizar" value="GUARDAR"></td>
        </tr>	  
    </table>
    <input type="hidden" name="MM_insert" value="form1">
</form></td>
    </tr>      
</table><?php } else{ ?><br><br><div id="fuente2">No hay datos correspondientes a este periodo, favor verificar la fecha.</div><?php } } ?>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($proveedores);

mysql_free_result($proveedor);

mysql_free_result($ultimo);

mysql_free_result($ultimo_numero);

mysql_free_result($bolsa_verificacion);

mysql_free_result($rollo_verificacion);

mysql_free_result($verificaciones_insumos);
?>
