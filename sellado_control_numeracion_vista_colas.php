<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
 
  session_start();
 

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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
      case "long":
      case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
      case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$conexion = new ApptivaDB();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario);

//IMPRIME COLAS DE TIQUETES
$registros = $conexion->llenaListas('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' "." AND int_caja_tn='".$_GET['int_caja_tn']."' ",'','*' );
 
?>
<html>
<head>

  <title>SISADGE AC & CIA</title>
  <!-- <link href="css/vista.css" rel="stylesheet" type="text/css" /> -->
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/vista.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <!--IMPRIME AL CARGAR POPUP-->
  <SCRIPT language="javascript"> 
/*function imprimir()
{ if ((navigator.appName == "Netscape")) { window.print() ;
} 
else
{ var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, -1); WebBrowser1.outerHTML = "";
}
}*/
</SCRIPT>
<style type="text/css">

 #oculto {
  display:none;

}

.check {
width:30px;
height:30px;
}

</style>
<script>
  function cerrar(num) {

    window.close()
  }
/*function cerrar() {
    setTimeout(function() {
    window.close();
    }, 100);
    }
    window.onload = cerrar();*/
  </script>
</head>
<body onLoad="self.print();"><!--self.close(); onLoad="imprimir();"--> 
  <?php foreach($registros as $row_colas_tikets) {  ?>
    <div align="center" id="seleccion" onClick="cerrar('seleccion');return false">
   
      <table align="center" id="tabla_borde" width="100%" height="100%"> <!-- border="1"  -->
         
       <!--  <tr>
          <td colspan="4" nowrap="nowrap" align="center" class="fuentev2"><strong>CONTROL DE NUMERACION</strong> </td>
        </tr> -->
        <tr>
          <td nowrap="nowrap" class="fuentev1"><b> PAQUETE</b></td>
          <td nowrap="nowrap" id="fuentev1"><?php echo $paq_gen=$row_colas_tikets['int_paquete_tn']; ?></td>
          <td nowrap class="fuentev1"><b> CAJA</b></td>
          <td nowrap id="fuentev1"><?php echo $caja_gen=$_GET['int_caja_tn']; ?></td>
        </tr>    
        <tr>
          <td colspan="2" nowrap="nowrap" class="fuentev1"><b> FECHA</b></td>
          <td colspan="2" nowrap class="fuentev1"><?php echo $row_colas_tikets['fecha_ingreso_tn']; ?> HORA <?php echo Hora();?></td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap"class="fuentev1"><b> ORDEN P.</b></td>
          <td colspan="2" class="fuentev1"><?php echo $op_gen=$row_colas_tikets['int_op_tn']; ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="fuentev1"><b> UNIDADES  X PAQ.</b></td>
          <td class="fuentev1"><?php echo $row_colas_tikets['int_undxpaq_tn']; ?></td>
          <td nowrap="nowrap" class="fuentev1"><b>CODIGO EMP.</b></td>
          <td class="fuentev1"><?php echo $row_colas_tikets['int_cod_empleado_tn']; ?></td>
        </tr>
        <tr>
         <td nowrap="nowrap"class="fuentev1"><b> UNIDADES X CAJA</b></td>
         <td class="fuentev1"><?php echo $row_colas_tikets['int_undxcaja_tn'];?></td>
         <td nowrap="nowrap" class="fuentev1"><b>CODIGO REV.</b></td>
         <td class="fuentev1"><?php echo $row_colas_tikets['int_cod_rev_tn']; ?></td>
       </tr>
       <tr>
        <td colspan="2" nowrap="nowrap" class="fuentev1"><b> DESDE</b></td>
        <td colspan="2" id="fuentev1"><?php echo $row_colas_tikets['int_desde_tn']; ?></td>
      </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" class="fuentev1"><b> HASTA</b></td>
        <td colspan="2" id="fuentev1"><?php echo $row_colas_tikets['int_hasta_tn']; ?></td>
      </tr> 
      <tr>
        <td colspan="4" nowrap="nowrap" class="fuentev1">&nbsp;&nbsp;</td> 
      </tr>
       
       <?php 
   //faltante por paquete
       $consulta_mysql="select Tbl_faltantes.int_inicial_f AS inicial, Tbl_faltantes.int_final_f AS final
       from Tbl_faltantes  WHERE Tbl_faltantes.id_op_f='$op_gen' and Tbl_faltantes.int_paquete_f = '$paq_gen' and Tbl_faltantes.int_caja_f= '$caja_gen'";
       $resultado_consulta_mysql=mysql_query($consulta_mysql);
  //  Navegamos cada fila que devuelve la consulta mysql y la imprimimos en pantalla
       while($fila=mysql_fetch_array($resultado_consulta_mysql)){
        $inicio=$fila['inicial']; $final=$fila['final'];

        if($inicio!=''){
          ?>  
          <tr>
            <td colspan="4" nowrap="nowrap" align="center" id="stikers_titu"><b>FALTANTES</b></td>
          </tr>      
          <tr>
            <td colspan="4" id="fuentev1"><?php echo $inicio; ?> - <?php echo $final; ?></td>
          </tr>
          <?php 
        }
      }?> 
    </table>
  
   
  </div>
  <div id="oculto">
    <table width="100%" height="100%" border="0" align="center">
      <tr>
        <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');return false" ></td>
      </tr>
    </table>

  </div>
<?php } ?>
</body>
</html>
<?php

 
 


?>
