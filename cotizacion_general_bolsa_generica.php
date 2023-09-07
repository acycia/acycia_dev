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

include('rud_cotizaciones/rud_cotizacion_bolsa.php');//SISTEMA RUW PARA LA BASE DE DATOS 

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
 
//aqui traigo el precio del la cotiz antes de todos los cambios del impuesto al plastico
if (isset($_GET['id_ref'])&& $_GET['id_ref']!='' && isset($_GET['Str_nit'])&& $_GET['Str_nit']!=''){  

$precio_old = $conexion->llenarCampos('Tbl_referencia ref ',"LEFT JOIN Tbl_cotiza_bolsa cb ON ref.cod_ref=cb.N_referencia_c LEFT JOIN Tbl_egp te ON ref.n_egp_ref = te.n_egp WHERE ref.tipo_bolsa_ref<>'LAMINA' and ref.tipo_bolsa_ref<>'PACKING LIST' AND cb.fecha_creacion < '2023-03-15' AND ref.id_ref= ".$_GET['id_ref']." AND Str_nit=".$_GET['Str_nit']." ORDER BY cb.fecha_creacion DESC LIMIT 1  ",""," cb.N_precio as N_precio_old ");
}
 
//PARA IMPRIMIR NUMERO DE COTIZACION
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);
//TRAE EL NIT DEL CLIENTE
/*$colname_cliente = "-1";
if (isset($_GET['Str_nit'])) 
{
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);*/


 $colname_cliente = "-1";
if (isset($_GET['Str_nit'])) 
{
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_clientes = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_cliente);
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);
 
 

 /*if(isset($_GET['Str_nit']) && $_GET['Str_nit']!='' ){

  $row_clientes = $conexion->llenaListas("cliente",""," WHERE nit_c = '".$_GET['Str_nit']."' ORDER BY nombre_c ASC"," nit_c,nombre_c ");

}else{
  $row_clientes = $conexion->llenaListas("cliente",""," ORDER BY nombre_c ASC  LIMIT 10"," nit_c,nombre_c"); 
} */

 
//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//IMPRIME CAMPOS
$colname_ver_bolsa2 = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ver_bolsa2 = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
 

$nit_c=$_GET['Str_nit'];
//and  Tbl_cotiza_bolsa.Str_nit='$nit_c' 
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_bolsa,Tbl_egp WHERE Tbl_referencia.id_ref='%s'  AND  Tbl_referencia.cod_ref=Tbl_cotiza_bolsa.N_referencia_c and Tbl_referencia.tipo_bolsa_ref<>'LAMINA' and Tbl_referencia.tipo_bolsa_ref<>'PACKING LIST' and Tbl_referencia.n_egp_ref=Tbl_egp.n_egp AND Tbl_cotiza_bolsa.B_estado in(1,3) ORDER BY Tbl_cotiza_bolsa.N_cotizacion DESC LIMIT 1",$colname_ver_bolsa2);//N_cotizacion importante tre el ultimo cuando es generica
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa = mysql_num_rows($bolsa);

 

//$query_cotiz="SELECT valor_impuesto ,N_referencia_c,Str_nit,N_cant_impresion AS cantidad,N_precio AS N_precio,N_precio_old, Str_unidad_vta, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_bolsa WHERE Str_nit='$nit_c' and N_referencia_c='$codref'  AND B_estado=1 ORDER BY fecha_creacion DESC";

/*
$colname_ver_bolsa2 = "-1";
if (isset($_GET['id_ref']) && isset($_GET['Str_nit'])) 
{
  $colname_ver_bolsa2 = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
 
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_bolsa,Tbl_egp WHERE Tbl_referencia.id_ref='%s'  and  Tbl_referencia.cod_ref=Tbl_cotiza_bolsa.N_referencia_c and Tbl_referencia.tipo_bolsa_ref<>'LAMINA' and Tbl_referencia.tipo_bolsa_ref<>'PACKING LIST' and Tbl_referencia.n_egp_ref=Tbl_egp.n_egp ORDER BY Tbl_cotiza_bolsa.fecha_creacion  DESC LIMIT 1",$colname_ver_bolsa2);
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa = mysql_num_rows($bolsa);
}
*/

//OBSERVACIONES
$colname_obs = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_obs= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_obs = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_bolsa_obs WHERE Tbl_referencia.id_ref='%s' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_bolsa_obs.N_cotizacion ",$colname_obs);
$obs = mysql_query($query_obs, $conexion1) or die(mysql_error());
$row_obs = mysql_fetch_assoc($obs);
$totalRows_obs = mysql_num_rows($obs);
//EVALUAR LAS REFERENCIAS GENERICA
/*mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref,Tbl_referencia.n_cotiz_ref,Tbl_cotiza_bolsa.N_cotizacion FROM Tbl_cotiza_bolsa,Tbl_referencia  WHERE Tbl_referencia.B_generica='1' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_bolsa.N_cotizacion ORDER BY CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) DESC ";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);*/

//GENERICAS
$row_referencias = $conexion->llenaListas('Tbl_cotiza_bolsa,Tbl_referencia','',"WHERE Tbl_referencia.B_generica='1' and Tbl_referencia.tipo_bolsa_ref <> 'LAMINA' and Tbl_referencia.tipo_bolsa_ref <> 'PACKING LIST' AND Tbl_referencia.n_cotiz_ref=Tbl_cotiza_bolsa.N_cotizacion ORDER BY CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) DESC",'DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref,Tbl_referencia.n_cotiz_ref,Tbl_cotiza_bolsa.N_cotizacion'); 

//EVALUAR LAS REFERENCIAS EXISTENTE
$colname_nit2 = "-1";
if (isset($_GET['Str_nit'])) {
  $colname_nit2 = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
$colname_cotiz2 = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_cotiz2 = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
/*mysql_select_db($database_conexion1, $conexion1);
$query_referencias2 = sprintf("SELECT DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref FROM Tbl_cliente_referencia, Tbl_referencia WHERE Tbl_cliente_referencia.Str_nit ='%s' AND Tbl_cliente_referencia.N_referencia = Tbl_referencia.cod_ref and Tbl_referencia.tipo_bolsa_ref<>'LAMINA' and Tbl_referencia.tipo_bolsa_ref<>'PACKING LIST' AND Tbl_cliente_referencia.N_referencia NOT IN(SELECT Tbl_cotiza_bolsa.N_referencia_c FROM Tbl_cotiza_bolsa WHERE Tbl_cotiza_bolsa.N_cotizacion = '%s' and Tbl_cotiza_bolsa.B_generica='0') ORDER BY CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) DESC",$colname_nit2,$colname_cotiz2);
$referencias2 = mysql_query($query_referencias2, $conexion1) or die(mysql_error());
$row_referencias2 = mysql_fetch_assoc($referencias2);
$totalRows_referencias2 = mysql_num_rows($referencias2);*/

//EXISTENTES
$row_referencias2 = $conexion->llenaListas("Tbl_cliente_referencia, Tbl_referencia","","WHERE Tbl_cliente_referencia.Str_nit ='$colname_nit2' AND Tbl_cliente_referencia.N_referencia = Tbl_referencia.cod_ref AND Tbl_referencia.tipo_bolsa_ref <> 'LAMINA' and Tbl_referencia.tipo_bolsa_ref <> 'PACKING LIST' AND Tbl_cliente_referencia.N_referencia NOT IN(SELECT Tbl_cotiza_bolsa.N_referencia_c FROM Tbl_cotiza_bolsa WHERE Tbl_cotiza_bolsa.N_cotizacion = '$colname_cotiz2' and Tbl_cotiza_bolsa.B_generica='0') ORDER BY CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) DESC","DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref");


//OTROS CLIENTES
$row_referencias3 = $conexion->llenaListas('Tbl_referencia',"","WHERE Tbl_referencia.tipo_bolsa_ref <> 'LAMINA' and Tbl_referencia.tipo_bolsa_ref <> 'PACKING LIST' AND B_generica='2' ORDER BY CONVERT( cod_ref, SIGNED INTEGER) DESC",'DISTINCT  id_ref, cod_ref, n_cotiz_ref '); 


//TRAE EL NUMERO DE REFERENCIA +1 PARA GUARDARLO SI NO ESCOGE GENERICA
mysql_select_db($database_conexion1, $conexion1);
$query_ref= "SELECT N_referencia FROM Tbl_cliente_referencia ORDER BY N_referencia DESC";
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
 

$colname_refer = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_refer = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
} 

mysql_select_db($database_conexion1, $conexion1);
$query_refer = sprintf("SELECT valor_impuesto,peso_millar_ref,peso_millar_bols FROM Tbl_referencia WHERE Tbl_referencia.id_ref ='%s'  ",$colname_refer);
$refer = mysql_query($query_refer, $conexion1) or die(mysql_error());
$row_refer = mysql_fetch_assoc($refer);
$totalRows_refer = mysql_num_rows($refer);

 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="AjaxControllers/js/funcionesmat.js"></script>

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

<script type="text/javascript">
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
</script>
<script type="text/javascript">
function confirActivo() {
 
swal({
  title: "Cliente Inactivo",
  text: "Quiere activar el cliente",
  type: "info",
  showCancelButton: true,
  closeOnConfirm: false,
  showLoaderOnConfirm: true,
},
 function(){
 	setTimeout(function(){
        var url="cambio_estado_cliente.php?"; 
		var campo1=<?php echo $_GET['N_cotizacion']; ?>; 
		var campo2="<?php echo $_GET['Str_nit']; ?>"; 
 		var campo3="2";	
		var dato1="N_cotizacion";
		var dato2="Str_nit";
		var dato3='id';	
 
	window.location.href=url+dato1+"="+campo1+"&"+dato2+"="+campo2+"&"+dato3+"="+campo3;
  
      swal("Proceso finalizado!");
  }, 2000);
    
 });
}

</script>
<style type="text/css">
  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 3200;
    background: url('images/loadingcircle4.gif') 50% 50% no-repeat rgb(250,250,250);
    background-size: 5% 10%;/*tamaño del gif*/
    -moz-opacity:65;
    opacity:0.65;

  }
</style>
</head>
<body>
  <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div class="spiffy_content">  
    <div align="center">
      <table id="tabla1"><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                  </ul>
                </div> 
                <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div> 
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" enctype="multipart/form-data" onsubmit="MM_validateForm('Str_nit','','R','N_ancho','','RisNum','N_alto','','RisNum','N_solapa','','RisNum','B_fuelle','','RisNum','N_calibre','', '','RisNum','N_cant_impresion','','RisNum','N_comision','','RisNum','vendedor','','RisNum','inactivo','','R');return document.MM_returnValue;return confirActivo()">

                    <table id="tabla1">
                    <tr></tr>
                    <tr id="tr1">
                      <td width="113" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                      <td colspan="2" nowrap="nowrap" id="titulo2">COTIZACION BOLSA <span id="titulo2" class="tipobolsa"></span> </td>
                      <td colspan="3" nowrap="nowrap" id="codigo">VERSION: 2 </td>
                    </tr>
                    <tr>
                      <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg" /></td>
                      <td colspan="2" id="numero1"><strong>NIT N&deg;
                        <input type="text" name="Str_nit" id="Str_nit" value="<?php echo $_GET['Str_nit']; ?>"readonly="readonly"/>
                      </strong></td>
                      <td colspan="3" id="fuente2"><?php $tipo=$row_usuario['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?>
                        <a href="javascript:eliminar_b('delete_bolsa',<?php echo $row_bolsa['N_cotizacion'];?>,'&delete_bolsa_ref',<?php echo $row_bolsa['N_referencia_c']; ?>,'cotizacion_general_bolsas_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COTIZACION"
                          title="ELIMINAR COTIZACION" border="0" />
                        <?php } ?>
                      </a><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
                      <td colspan="3" id="numero1"><strong> 
                        <input name="N_cotizacion" type="hidden" value="<?php if($_GET['N_cotizacion']==''){ $num=$row_cotizacion['N_cotizacion']+1; echo $num; }else{  $num=$_GET['N_cotizacion']; echo $num;} ?>" /> 
                        <!-- echo $num = !(isset($_GET['N_cotizacion'])&&$_GET['N_cotizacion']=='' ) && $_GET['mismacotiz']==0 ? $row_cotizacion['N_cotizacion']+1 : $_GET['N_cotizacion'];  -->
                          <?php echo $num;  ?></strong></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Fecha  Ingreso</td>
                          <td colspan="3" id="fuente1">Hora Ingreso</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1"><input name="fecha_b" type="date" id="fecha_b" value="<?php echo date("Y-m-d");  ?>" size="10" /></td>
                          <td colspan="3" id="fuente1"><input name="hora_b" type="text" id="hora_b" value="<?php echo date("h:i a") ?>" size="10" readonly="true" /></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Estado de la Cotizaci&oacute;n</td>
                          <td id="fuente1">Generica </td>
                          <td id="fuente1">Existente</td>
                          <td id="fuente1">Otros clientes</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1"><select name="B_estado" id="B_estado">
                            <option value="0"<?php if (!(strcmp("0", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
                            <option value="2"<?php if (!(strcmp("2", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Rechazada</option>
                            <option value="3"<?php if (!(strcmp("3", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Obsoleta</option>
                          </select></td>
                          <td id="dato1"> 
                            <select name="ref" id="ref" onchange="if(form1.ref.value!='') { consultagenerica3(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                                <option value="0">-REF-</option>
                                   <?php  foreach($row_referencias as $row_referencias ) { ?>
                                <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
                            <?php } ?>
                            </select> 
                        </td>
                          <td id="dato1"> 
                             
                           <select name="ref2" id="ref2" onchange="if(form1.ref2.value!='') { consultaexistente3(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                                <option value="0">-REF-</option>
                                   <?php  foreach($row_referencias2 as $row_referencias2 ) { ?>
                                <option value="<?php echo $row_referencias2['id_ref']?>"<?php if (!(strcmp($row_referencias2['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias2['cod_ref']?></option>
                            <?php } ?>
                            </select> 

                        </td>
                          <td id="dato1">

                          <select name="ref3" id="ref3" onchange="if(form1.ref3.value!='') { consultaotros3(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                                <option value="0">-REF-</option>
                                   <?php  foreach($row_referencias3 as $row_referencias3 ) { ?>
                                <option value="<?php echo $row_referencias3['id_ref']?>"<?php if (!(strcmp($row_referencias3['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias3['cod_ref']?></option>
                            <?php } ?>
                            </select>

                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Nombre del Cliente</td>
                        </tr>
                        <tr>
                          <td colspan="5" id="fuente1">
                            <!-- debo dejar este filtro porq al cargar por get carga mas rapido -->
                          <select  name="clientes" id="clientes" onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit();} else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px">
                                   <?php
                           do {  
                           ?>
                                   <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>>
                                     <?php $nombre_cl= ($row_clientes['nombre_c']); echo $nombre_cl;?>
                                     </option>
                                   <?php
                           } while ($row_clientes = mysql_fetch_assoc($clientes));
                             $rows = mysql_num_rows($clientes);
                             if($rows > 0) {
                                 mysql_data_seek($clientes, 0);
                              $row_clientes = mysql_fetch_assoc($clientes);
                             }
                           ?>
                          </select> 
                          <!-- <select  name="clientes" id="clientes"onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit();} else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px" class="selectsGrande" >
                             <?php  foreach($row_clientes as $row_clientes ) { ?>
                                 <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>>
                                 <?php $nombre_cl= ($row_clientes['nombre_c']); echo $nombre_cl;?>
                               </option>
                             <?php } ?>
                           </select>  -->

                           <!-- <select  name="clientes" id="clientes"onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit();} else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px" class="selectsGrande" >
                             <option value=''>Seleccione el Cliente</option>
                           </select> -->

                            <!-- <select  name="clientes" id="clientes"onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit();} else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px" class="busqueda selectsGrande" >
                                   <?php  foreach($row_clientes as $row_clientes ) { ?>
                                <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>>
                                <?php $nombre_cl= ($row_clientes['nombre_c']); echo $nombre_cl;?>
                              </option>
                            <?php } ?>
                            </select> -->

                            <!-- <select  name="clientes" id="clientes"onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit();} else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px" class="busqueda selectsMedio">
                            <?php
                            do {  
                              ?>
                              <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>>
                                <?php $nombre_cl= ($row_clientes['nombre_c']); echo $nombre_cl;?>
                              </option>
                              <?php
                            } while ($row_clientes = mysql_fetch_assoc($clientes));
                            $rows = mysql_num_rows($clientes);
                            if($rows > 0) {
                              mysql_data_seek($clientes, 0);
                              $row_clientes = mysql_fetch_assoc($clientes);
                            }
                            ?>
                          </select> -->

                          <?php  
                          $activo=$row_cliente['estado_c'];
                          if($activo!='' && $activo!='ACTIVO'){
                            echo "Este cliente esta inactivo, debe activarlo en clientes";        echo"<input name='inactivo' id='inactivo' type='hidden' value='$activo' />";
                          }
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
                        </ul></td>
                        <td id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
                        </ul></td>
                        <td colspan="3" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="7" id="titulo2">BOLSAS PLASTICAS (Seguridad/Courrier/Normal)</td>
                      </tr>
                      <tr>
                        <td colspan="7" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                         <td colspan="7" id="fuente1"><em style="color: red;" >Si elimina una cotizacion; se traerá el precio de la anterior cotizacion! </em> </td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="7" id="titulo1">CARACTERISTICAS PRINCIPALES</td>
                      </tr>
                      <tr>
                        <td id="fuente1">Ancho (cms)</td>
                        <td colspan="2" id="fuente1">Alto (cms)</td>
                        <td id="fuente1">Solapa (cms)</td>
                        <td id="fuente1">Sencilla/Doble</td>
                        <td id="fuente1">Fuelle (cms)</td>
                      </tr>
                      <tr>
                        <td id="dato1"><input name="N_ancho" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_ancho" value="<?php echo $row_bolsa['ancho_ref']?>" /></td>
                        <td colspan="2" id="dato1"><input name="N_alto" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_alto" value="<?php echo $row_bolsa['largo_ref']?>"/></td>
                        <td colspan="2" id="dato1"><input name="N_solapa" type="number" style=" width:70px" min="0" step="0.01" id="N_solapa" required="required" value="<?php echo $row_bolsa['solapa_ref']=$row_bolsa['solapa_ref']=='' ? 0 : $row_bolsa['solapa_ref'];?>"/>
                          <select name="tiposolapa" id="tiposolapa" style="width:100px" >
                            <option value="0" >N/A</option>
                            <option value="2" >Sencilla</option>
                            <option value="1" >Doble</option>
                          </select>
                        </td>
                        <td id="dato1"><input name="B_fuelle" type="number" style=" width:70px" min="0" step="0.01" id="B_fuelle" value="<?php echo $row_bolsa['N_fuelle']?>"/></td>
                      </tr>
                      <tr>
                        <td id="fuente1">Calibre (micras)</td>
                        <td colspan="2" id="fuente1">Tipo Bolsa</td>
                        <td colspan="2" id="fuente1"> Bolsillo canguro</td>
                        <td id="fuente1">Tama&ntilde;o</td>
                      </tr>
                      <tr>
                        <td id="dato1"><input name="N_calibre" type="number" style=" width:70px" min="0" step="0.01" required="required" id="N_calibre" value="<?php echo $row_bolsa['calibre_ref']?>" size="3" /></td>
                        <td colspan="2" id="dato1">
                          <select name="tipo_bolsa" id="tipo_bolsa" style="width:100px" required >
                            <option value="">Seleccione...</option> 
                            <option value="SEGURIDAD" >SEGURIDAD</option>
                            <option value="CURRIER" >CURRIER</option>
                            <option value="BOLSA PLASTICA" >BOLSA PLASTICA</option>
                            <option value="BOLSA MONEDA" >BOLSA MONEDA</option>
                            <option value="COMPOSTABLE" >COMPOSTABLE</option>
                            <option value="BOLSA TROQUELADA" >BOLSA TROQUELADA</option>
                          </select>
                        </td>
                        <td colspan="2" id="dato1"><select name="B_bolsillo" id="B_bolsillo" onchange="mostrarBolsillo(this)">
                          <option value="0"  selected="selected"  >NO</option>
                          <option value="1" >SI</option>

                        </select></td>
                        <td id="dato1"><input name="N_tamano_bolsillo"  type="number" style=" width:70px" min="0" step="0.01" value="<?php echo $row_bolsa['bolsillo_guia_ref']=='' ? 0 :$row_bolsa['bolsillo_guia_ref'] ?>" id="N_tamano_bolsillo"/></td>
                      </tr>
                      <tr>
                        <td colspan="7" id="titulo3">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="7" id="titulo1">MATERIAL COEXTRUSION</td>
                      </tr>
                      <tr>
                        <td id="fuente1">Material</td>
                        <td colspan="2" id="fuente7">&nbsp;</td>
                        <td colspan="3" id="fuente1">Color:</td>
                      </tr>
                      <tr>
                        <td id="fuente1"><select name="Str_tipo_coextrusion" id="Str_tipo_coextrusion" onChange="mostrarCapa(this)">
                          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_bolsa['material_ref']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
                          <option value="PIGMENTADO B/N"<?php if (!(strcmp("PIGMENTADO B/N", $row_bolsa['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/N</option>
                          <option value="PIGMENTADO B/B"<?php if (!(strcmp("PIGMENTADO B/B", $row_bolsa['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/B</option>

                        </select></td>
                        <td colspan="2" id="fuente3">Capa Externa:</td>
                        <td colspan="3" id="fuente1"><select name="Str_capa_ext_coext" id="Str_capa_ext_coext">
                          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_bolsa['pigm_ext_egp']))) {echo "selected=\"selected\"";} ?>>TRANSP</option>
                          <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_bolsa['pigm_ext_egp']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
                          <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_bolsa['pigm_ext_egp']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente3">Capa Interna:</td>
                        <td colspan="3" id="fuente1"><select name="Str_capa_inter_coext" id="Str_capa_inter_coext">
                          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_bolsa['pigm_int_egp']))) {echo "selected=\"selected\"";} ?>>TRANSP</option>
                          <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_bolsa['pigm_int_epg']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
                          <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_bolsa['pigm_int_epg']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                        <td colspan="3" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="7" id="titulo1">IMPRESION</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente1">Impresion:
                          <select name="B_impresion" id="B_impresion" onchange="mostrarColor(this)"> 
                            <option value="0"<?php if (!(strcmp("0",$row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (strcmp("1", $row_bolsa['impresion_ref'])) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select>
                          <select name="N_colores_impresion" id="N_colores_impresion"> 
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>1 color</option>
                            <option value="2"<?php if (!(strcmp("2", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>2 color</option>
                            <option value="3"<?php if (!(strcmp("3", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>3 color</option>
                            <option value="4"<?php if (!(strcmp("4", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>4 color</option>
                            <option value="5"<?php if (!(strcmp("5", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>5 color</option>
                            <option value="6"<?php if (!(strcmp("6", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>6 color</option>
                            <option value="7"<?php if (!(strcmp("7", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>7 color</option>
                            <option value="8"<?php if (!(strcmp("8", $row_bolsa['impresion_ref']))) {echo "selected=\"selected\"";} ?>>8 color</option>
                          </select></td>
                          <td width="133" colspan="2" id="fuente1">Se Facturan Artes y Planchas ?</td>
                          <td width="219" id="fuente1"><select name="B_cyreles" id="B_cyreles">
                            <option value=""<?php if (!(strcmp("", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>SI</option>
                            <option value="0"<?php if (!(strcmp("0", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>NO</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td id="fuente1">&nbsp;</td>
                          <td colspan="2" id="fuente1">&nbsp;</td>
                          <td colspan="3" id="fuente1">&nbsp;</td>
                        </tr>
                        <tr id="tr1">
                          <td colspan="7" id="titulo1">SELLADO</td>
                        </tr>
                        <tr >
                          <td colspan="7" id="titulo1">Tipo de Cierre Principal</td>
                        </tr>
                        <tr>
                          <td id="fuente1">Cinta Seguridad</td>
                          <td colspan="2" id="fuente1"><select name="B_sellado_seguridad" id="B_sellado_seguridad">
                            <option value="0"<?php if (!(strcmp("N.A",$row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("CINTA DE SEGURIDAD", $row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                          <td colspan="2" id="fuente1">Cinta Permanente</td>
                          <td id="fuente1"><select name="B_sellado_permanente" id="B_sellado_permanente">
                            <option value="0"<?php if (!(strcmp("N.A",$row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("CINTA PERMANENTE", $row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td id="fuente1">Cinta Resellable</td>
                          <td colspan="2" id="fuente1"><select name="B_sellado_resellable" id="B_sellado_resellable">
                            <option value="0"<?php if (!(strcmp("N.A",$row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("CINTA RESELLABLE", $row_bolsa['adhesivo_refe']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                          <td colspan="2" id="fuente1">Hot Melt</td>
                          <td id="fuente1"><select name="B_sellado_hotm" id="B_sellado_hotm">
                            <option value="0"<?php if (!(strcmp("N.A",$row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("HOT MELT", $row_bolsa['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td colspan="7" id="titulo1">Tipo de Sello</td>
                        </tr>
                        <tr>
                          <td id="fuente1">Lateral</td>
                          <td colspan="2" id="fuente1"><select name="Str_sellado_lateral" id="Str_sellado_lateral">
                            <option></option>
                            <option value="N/A"<?php if (!(strcmp("N/A", $row_bolsa['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N/A</option>
                            <option value="HILO"<?php if (!(strcmp("HILO", $row_bolsa['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO</option>
                            <option value="PLANO"<?php if (!(strcmp("PLANO", $row_bolsa['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
                            <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_bolsa['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
                          </select></td>
                          <td colspan="2" id="fuente1">Fondo</td>
                          <td id="fuente1"><select name="B_fondo" id="B_fondo">
                            <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_fondo']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_fondo']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td id="fuente1">Numeracion</td>
                          <td id="fuente1">Troquel</td>
                          <td id="fuente1">Precorte</td>
                          <td colspan="2" id="fuente1">&nbsp;</td>
                          <td id="fuente1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td id="fuente1"><select name="B_numeracion" id="B_numeracion">
                            <option value="0"<?php if (!(strcmp("0",$row_bolsa['num_pos_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['num_pos_ref']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                          <td id="fuente1"><select name="B_troquel" id="B_troquel">
                            <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                          <td id="fuente1"><select name="B_precorte" id="B_precorte">
                            <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_precorte']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_precorte']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                          <td colspan="2" id="fuente1">Codigo de Barras</td>
                          <td id="fuente1"><select name="B_codigo_b" id="B_codigo_b">
                            <option value="0"<?php if (!(strcmp("0",$row_bolsa['cod_form_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['cod_form_ref']))) {echo "selected=\"selected\"";} ?>>SI</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td colspan="7" id="fuente1">&nbsp;</td>
                        </tr>
                        <tr id="tr1">
                          <td colspan="7" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
                        </tr>
                        <tr>
                          <td colspan="3">
                            <br>
                            <span style="color: red;" >IMPUESTO PLASTICO</span> <input type="checkbox" name="impuesto" id="impuesto" checked value="1"> <label for="impuesto"> &nbsp;&nbsp;<!-- Adjunto PDF: <input name="pdf_impuesto" type="file" size="20" maxlength="60"class="botones_file"> -->
                            <?php if($row_cliente['pdf_impuesto']): ?>
                              Adjunto PDF:   
                                            <a href="javascript:verFoto('archivosc/impuesto/<?php echo $row_cliente['pdf_impuesto']; ?>','610','490')"> 
                                              <?php if($row_cliente['pdf_impuesto']!='') echo "Impuesto"; ?><em> (Este pdf se adjunta en Cliente) </em>
                                            </a>
                            <?php endif; ?>
                          </td>
                          <td colspan="4">
                            <div name="formular" id="formular" style="display: none;" >
                                <span style="color: red;" >CALCULAR IMPUESTO CON FORMULA </span> <input type="checkbox" name="calculaformula" id="calculaformula" title="Solamente para referencias nuevas" value="1"> <label for="calculaformula">
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td id="fuente1">Moneda</td>
                          <td id="fuente1">Precio Anterior</td>
                          <td id="fuente1">Impuesto</td>
                          <td id="fuente1">Precio con Impuesto</td>
                          <td id="fuente1">Unidad</td>
                          <td id="fuente1">Plazo de pago</td>
                          <td id="fuente1">Cantidad Solicitada</td>
                        </tr>
                        <tr>
                          <td id="fuente5">
                            <select name="Str_moneda" id="Str_moneda">
                            <option value="COL$"<?php if (!(strcmp("COL$", $row_bolsa['Str_moneda']))) {echo "selected=\"selected\"";} ?>>COL$</option>
                            <option value="USD$"<?php if (!(strcmp("USD$", $row_bolsa['Str_moneda']))) {echo "selected=\"selected\"";} ?>>USD$</option>
                            <option value="EUR&euro;"<?php if (!(strcmp("EUR&euro;", $row_bolsa['Str_moneda']))) {echo "selected=\"selected\"";} ?>>EUR&euro;</option>
                          </select>
                        </td>
                        <td id="fuente5">
                          <?php 
                          /*if($precio_old['N_precio_old'] && $row_bolsa['N_precio_old']){
                            $precioActual = $row_bolsa['N_precio_old'];
                          }else if($precio_old['N_precio_old'] && !$row_bolsa['N_precio_old']){
                            $precioActual = $precio_old['N_precio_old'];
                          }else if(!$precio_old['N_precio_old'] && $row_bolsa['N_precio_old']){
                            $precioActual = $row_bolsa['N_precio_old'];
                          }else{
                            $precioActual = $precioActual =='' ? $row_bolsa['N_precio'] : $precio_old['N_precio'];
                          }*/
                          ?>
                          <input name="N_precio" type="text" style="width:100px" min="0" step="0.01" id="N_precio" value="<?php echo $row_bolsa['N_precio']==''?0:$row_bolsa['N_precio']; ?>"/>
                          </td>
                          
                         <td id="fuente5">
                           <input name="valor_impuesto" type="text" title="Valor de la ultima cotiz" style="width:80px" min="0" step="0.01" id="valor_impuesto" value="<?php echo $row_bolsa['valor_impuesto']=='0'?$row_refer['valor_impuesto']:$row_bolsa['valor_impuesto']; ?>"/> 
                         </td>
                         <td id="fuente5">
                            <input name="N_precio_old" type="text" style="width:100px" min="0" step="0.01" id="N_precio_old" value="<?php echo $row_bolsa['N_precio_old']?>"/>
                         </td>
                          <td id="dato1">
                            <select name="Str_unidad_vta" id="Str_unidad_vta">
                            <option>*</option>
                            <option value="PRECIO UNITARIO"<?php if (!(strcmp("PRECIO UNITARIO", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO UNITARIO</option>
                            <option value="PRECIO MILLAR"<?php if (!(strcmp("PRECIO MILLAR", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO MILLAR</option>
                            <option value="PRECIO PAQUETE"<?php if (!(strcmp("PRECIO PAQUETE", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO PAQUETE</option>
                            <option value="PRECIO KILOS"<?php if (!(strcmp("PRECIO KILOS", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO KILOS</option>
                            <option value="PRECIO COMBOS"<?php if (!(strcmp("PRECIO COMBOS", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO COMBOS</option>
                          </select>
                        </td>
                          <td id="fuente5"><select name="Str_plazo" id="Str_plazo">
                            <option>*</option>
                            <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                            <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                            <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias</option>
                            <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias</option>
                            <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias</option>
                            <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias</option>
                            <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_bolsa['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias</option>
                          </select></td>
                          <td id="fuente1"><input name="N_cant_impresion" type="number" style=" width:100px" step="0.01" min="0" id="N_cant_impresion" value="<?php echo $row_bolsa['N_cant_impresion'];?>"  />
                          </td>
                        </tr>
                        <tr>
                          <td colspan="7" id="fuente1"><hr> </td>
                          </tr>
                        <tr>
                          <td id="fuente1">Incoterms:</td>
                          <td colspan="2" id="fuente1">&nbsp;</td>
                          <td colspan="3" id="fuente1">Vendedor</td>
                          <td id="fuente1">Comision</td>
                        </tr>
                        <tr>
                          <td id="fuente1"><select name="Str_incoterms" id="Str_incoterms">
                            <option >*</option>
                            <option value="EXW"<?php if (!(strcmp("EXW", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>EXW</option>
                            <option value="FCA"<?php if (!(strcmp("FCA", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FCA</option>
                            <option value="FAS"<?php if (!(strcmp("FAS", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FAS</option>
                            <option value="FOB"<?php if (!(strcmp("FOB", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FOB</option>
                            <option value="CFR"<?php if (!(strcmp("CFR", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CFR</option>
                            <option value="CIF"<?php if (!(strcmp("CIF", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIF</option>
                            <option value="CPT"<?php if (!(strcmp("CPT", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CPT</option>
                            <option value="CIP"<?php if (!(strcmp("CIP", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIP</option>
                            <option value="DAF"<?php if (!(strcmp("DAF", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DAF</option>
                            <option value="DES"<?php if (!(strcmp("DES", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DES</option>
                            <option value="DEQ"<?php if (!(strcmp("DEQ", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DEQ</option>
                            <option value="DDU"<?php if (!(strcmp("DDU", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDU</option>
                            <option value="DDP"<?php if (!(strcmp("DDP", $row_bolsa['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDP</option>
                          </select>
                          <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a>
                         </td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                          <td colspan="3" id="fuente1">
                            <?php
                            $nom_vend = $row_usuario['nombre_usuario'];
                            $sqldato="SELECT nombre_vendedor FROM vendedor WHERE nombre_vendedor LIKE '%$nom_vend%'";
                            $resultdato=mysql_query($sqldato);
                            $numce1= mysql_num_rows($resultdato);
                            if($numce1!='') { 
                              $nombre_vendedor=mysql_result($resultdato,0,'nombre_vendedor');
                            }
                            ?>
                            <select name="vendedor" id="vendedor" required >
                              <option value="">Seleccione</option>
                              <?php
                              do {  
                                ?>
                                <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['nombre_vendedor'], $nombre_vendedor))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
                                <?php
                              } while ($row_vendedores = mysql_fetch_assoc($vendedores));
                              $rows = mysql_num_rows($vendedores);
                              if($rows > 0) {
                                mysql_data_seek($vendedores, 0);
                                $row_vendedores = mysql_fetch_assoc($vendedores);
                              }
                              ?>
                            </select></td>
                            <td id="fuente1"><input name="N_comision" type="number" style=" width:60px" step="0.1" id="N_comision" min="1" max="9"  value="" required="required"/>
                              <strong>%</strong></td>
                            </tr>
                            <tr>
                              <td colspan="7" id="fuente1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="7" id="fuente1">Uso de la Bolsa Observaciones:</td>
                            </tr>
                            <tr>
                              <td colspan="7" id="dato1"><textarea name="nota_b" cols="78" rows="2" id="nota_b"onkeyup="conMayusculas(this)"></textarea></td>
                            </tr>
                            <tr>
                              <td colspan="7" id="dato1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="7" id="fuente2"><input type="hidden" name="Str_tipo" id="Str_tipo" value="BOLSA" />
                                <input name="responsable_modificacion" type="hidden" value="" />
                                <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d");?>" />
                                <?php /*if(isset($_GET['generica']) && $_GET['generica']=='1')
                                {
                                  echo "<input name='B_generica' type='hidden' value='1'/>";
                                }else if(isset($_GET['generica']) && $_GET['generica']=='2'){
                                  echo "<input name='B_generica' type='hidden' value='2'/>";
                                }else{
                                  echo "<input name='B_generica' type='hidden' value='0'/>";
                                }*/
                                ?>
                                <input name="peso_millar_ref" type="hidden" id="peso_millar_ref" value="<?php echo $row_bolsa['peso_millar_ref']==''?0:$row_bolsa['peso_millar_ref']?>"/>
                                <input name="peso_millar_bols" type="hidden" id="peso_millar_bols" value="<?php echo $row_bolsa['peso_millar_bols']=='' ? 0 : $row_bolsa['peso_millar_bols'];?>"/> 
                                <input name='B_generica' id='B_generica' type='hidden' value='0'/>
                                <input name="N_referencia" type="hidden" value="<?php if( $row_bolsa['cod_ref']!=''){echo $row_bolsa['cod_ref'];} else{$vref=$row_ref['N_referencia']+1; echo $vref;}?>" />
                                <input name="hora_modificacion" type="hidden" value="" />
                                <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
                                <input name="valor" type="hidden" value="1" />
                                <input name="submit" class="botonGeneral" type="submit" value="GUARDAR COTIZACION BOLSA" <?php if($activo!='' && $activo!='ACTIVO'){ ?> onclick="return confirActivo();" <?php } ?>/></td>
                              </tr>
                            </table>
                            <input type="hidden" name="MM_insert" value="form1">
                          </form></td>
                        </tr>
                      </table>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <a href="#" id="content" class="button ">
     <i class="fa fa-eye">Ver efecto</i> 
</a>
 
</body>
</html>
<script>
  

    $(document).ready(function(){

            var ref=$("#ref").val();
            var ref2=$("#ref2").val();
            var ref3=$("#ref3").val();

              
             if( ref !='0' && ref2 =='0' && ref3 =='0' ){  
                  $("#B_generica").val("1"); 
                  $(".tipobolsa").text("GENERICA");  
             }else 
             if( ref =='0' && ref2 !='0' && ref3 =='0' ){   
                  $("#B_generica").val("1"); 
                  $(".tipobolsa").text("EXISTENTE");  
             }else 
             if( ref =='0' && ref2 =='0' && ref3 !='0' ){  
                  $("#B_generica").val("2"); 
                  $(".tipobolsa").text("OTROS CLIENTES");  
             }else 
             if( ref !='0' && ref2 !='0' && ref3 =='0' ){   
                  $("#B_generica").val("1"); 
                  $(".tipobolsa").text("GENERICA");    
             }else{
                  $("#B_generica").val("0");
                  $(".tipobolsa").text(""); 
             }
 
     sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
 });

    
    $('#impuesto').on('change', function() { 
           sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
    
    });

   $('#valor_impuesto').on('change', function() { 
          sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
 
   });


   $('#N_precio').on('change', function() { 
          sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
   
   });


/*   if( $('#impuesto').prop('checked') && $("#N_precio_old").val()=='' ) {
       swal("Debe llenar el campo Precio Impuesto");
       return false;
   }*/
   
    
   $(document).ready(function(){
     if( $("#ref").val()=='0' && $("#ref2").val()=='0'  && $("#ref3").val()=='0') 
        $("#formular").show(200);
   }); 
   
   
   

   $('#N_solapa').on('change', function() { 
           if ($("#N_solapa").val() > 0  ){
                $("#tiposolapa").val("2"); //2 es sencilla por defecto
           }
   
   }); 
  $('#calculaformula').on('change', function() { 
      if( $("#N_ancho").val()!='' && $("#N_alto").val()!='' && $("#B_fuelle").val()!='' && $("#N_solapa").val()!='' && $("#N_calibre").val()!='' && $("#N_tamano_bolsillo").val()!='' && $("#N_precio_old").val()!='' ) {   
     
      pesoMillarFormulaCotiz($("#tiposolapa").val(),$("#N_ancho").val(),$("#N_alto").val(),$("#B_fuelle").val(),$("#N_solapa").val(),$("#N_calibre").val(),$("#N_tamano_bolsillo").val(),$("#N_precio_old").val() );
    }
 });

 
       $('.botonGeneral').on('click', function(){
        if($("#Str_nit").val()!='' && $("#B_estado").val()!='' && $("#N_ancho").val()!='' && $("#N_alto").val()!='' && $("#N_calibre").val()!='' && $("#tipo_bolsa").val()!=''  && $("#N_precio").val()!='' && $("#valor_impuesto").val()!='' && $("#N_precio_old").val()!='' && $("#N_cant_impresion").val()!='' && $("#vendedor").val()!='' && $("#N_comision").val()!='' ){

           $('#content').html('<div class="loader"></div>'); setTimeout(function() { $(".loader").fadeOut("slow");},3000); 

        }
    });  






//FILTROS
       $(document).ready(function(){  
             $('#clientes').select2({ 
                 ajax: {
                     url: "select3/proceso.php",
                     type: "post",
                     dataType: 'json',
                     delay: 250,
                     data: function (params) {
                         return {
                             palabraClave: params.term, // search term
                             var1:"nit_c,nombre_c",
                             var2:"cliente",
                             var3:"",
                             var4:"ORDER BY nombre_c ASC",
                             var5:"nit_c",
                             var6:"nombre_c"
                         };
                     },
                     processResults: function (response) {
                         return {
                             results: response
                         };
                     },
                     cache: true
                 }
             });
        
        });

</script>
<?php
mysql_free_result($usuario);
mysql_free_result($cliente);
mysql_free_result($vendedores);
mysql_free_result($cotizacion);
mysql_free_result($obs);
?>
