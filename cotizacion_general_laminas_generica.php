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
include('rud_cotizaciones/rud_cotizacion_lamina.php');//SISTEMA RUW PRA LA BASE DE DATOS 

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
//PARA IMPRIMIR NUMERO DE COTIZACION
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);
//TRAE EL NIT DEL CLIENTE
/*$colname_cliente = "-1";
if (isset($_GET['Str_nit'])) //$_GET['clientes']
 {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT estado_c FROM cliente WHERE nit_c = '%s'", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);*/

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

//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//IMPRIME CAMPOS
$colname_ver_lamina2 = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ver_lamina2 = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_lamina = sprintf("SELECT * FROM Tbl_cotiza_laminas,Tbl_referencia,Tbl_egp WHERE  Tbl_referencia.id_ref='%s' AND Tbl_referencia.n_cotiz_ref= Tbl_cotiza_laminas.N_cotizacion and Tbl_referencia.cod_ref=Tbl_cotiza_laminas.N_referencia_c and Tbl_referencia.tipo_bolsa_ref='LAMINA' and Tbl_referencia.n_egp_ref=Tbl_egp.n_egp",$colname_ver_lamina2);
$lamina = mysql_query($query_lamina, $conexion1) or die(mysql_error());
$row_lamina = mysql_fetch_assoc($lamina);
$totalRows_lamina = mysql_num_rows($lamina);
//OBSERVACIONES
$colname_obs = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_obs= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_obs = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_lamina_obs WHERE Tbl_referencia.id_ref='%s' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_lamina_obs.N_cotizacion ",$colname_obs);
$obs = mysql_query($query_obs, $conexion1) or die(mysql_error());
$row_obs = mysql_fetch_assoc($obs);
$totalRows_obs = mysql_num_rows($obs);
//EVALUAR LAS REFERENCIAS GENERICA
$colname_nit = "-1";
if (isset($_GET['Str_nit'])) {
  $colname_nit = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
$colname_cotiz = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_cotiz = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
/*mysql_select_db($database_conexion1, $conexion1);
$query_referencias = sprintf("SELECT DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref,Tbl_referencia.n_cotiz_ref,Tbl_cotiza_laminas.N_cotizacion FROM  Tbl_cotiza_laminas,Tbl_referencia  WHERE Tbl_referencia.B_generica='1' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_laminas.N_cotizacion ORDER BY Tbl_referencia.cod_ref ASC ",$colname_nit,$colname_cotiz);
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);*/
$row_referencias = $conexion->llenaListas('Tbl_cotiza_laminas,Tbl_referencia','',"WHERE Tbl_referencia.B_generica='1' and Tbl_referencia.n_cotiz_ref=Tbl_cotiza_laminas.N_cotizacion ORDER BY CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) DESC",'DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref,Tbl_referencia.n_cotiz_ref,Tbl_cotiza_laminas.N_cotizacion'); 
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
$query_referencias2 = sprintf("SELECT * FROM Tbl_cliente_referencia, Tbl_referencia WHERE Tbl_cliente_referencia.Str_nit ='%s' AND Tbl_cliente_referencia.N_referencia = Tbl_referencia.cod_ref AND Tbl_referencia.tipo_bolsa_ref='LAMINA' AND Tbl_cliente_referencia.N_referencia NOT IN(SELECT Tbl_cotiza_laminas.N_referencia_c FROM Tbl_cotiza_laminas WHERE Tbl_cotiza_laminas.N_cotizacion = '%s' and Tbl_cotiza_laminas.B_generica='0' )",$colname_nit2,$colname_cotiz2);
$referencias2 = mysql_query($query_referencias2, $conexion1) or die(mysql_error());
$row_referencias2 = mysql_fetch_assoc($referencias2);
$totalRows_referencias2 = mysql_num_rows($referencias2);*/

$row_referencias2 = $conexion->llenaListas("Tbl_cliente_referencia, Tbl_referencia","","WHERE Tbl_cliente_referencia.Str_nit ='$colname_nit2' AND Tbl_cliente_referencia.N_referencia = Tbl_referencia.cod_ref AND Tbl_referencia.tipo_bolsa_ref='LAMINA' AND Tbl_cliente_referencia.N_referencia NOT IN(SELECT Tbl_cotiza_laminas.N_referencia_c FROM Tbl_cotiza_laminas WHERE Tbl_cotiza_laminas.N_cotizacion = '$colname_cotiz2' and Tbl_cotiza_laminas.B_generica='0') ORDER BY CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) DESC","DISTINCT Tbl_referencia.id_ref,Tbl_referencia.cod_ref");

//TRAE EL NUMRO DE REFERENCIA +1 PARA GUARDARLO SI NO ESCOGE GENERICA
mysql_select_db($database_conexion1, $conexion1);
$query_ref= "SELECT N_referencia FROM Tbl_cliente_referencia ORDER BY N_referencia DESC";
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
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
 		var campo3="3";	
		var dato1="N_cotizacion";
		var dato2="Str_nit";
		var dato3='id';	
		
	window.location.href=url+dato1+"="+campo1+"&"+dato2+"="+campo2+"&"+dato3+"="+campo3;
  
      swal("Proceso finalizado!");
  }, 2000);
    
 });
}
</script>
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

                  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nit','','R','N_ancho_l','','RisNum','N_repeticion_l','','RisNum','N_calibre_l','','RisNum','N_diametro_max_l','','RisNum','N_peso_max','','RisNum','N_cantidad_metros_r_l','','RisNum','N_precio_k','','RisNum','N_cantidad_l','','RisNum','N_comision','','RisNum','vendedor','','RisNum','inactivo','','R');return document.MM_returnValue;return confirActivo()">
                    <table id="tabla1">
                    <tr id="tr1">
                      <td width="113" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                      <td colspan="2" nowrap="nowrap" id="titulo2">Cotizacion Laminas</td>
                      <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
                    </tr>
                    <tr>
                      <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
                      <td colspan="2" id="numero2"><strong>NIT N&deg;
                        <input name="Str_nit" type="text"id="Str_nit"  value="<?php echo $_GET['Str_nit']; ?>" readonly="readonly" />
                      </strong></td>
                      <td colspan="2" id="fuente2"><?php $tipo=$row_usuario['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?> <a href="javascript:eliminar_l('delete_lamina',<?php echo $row_lamina['N_cotizacion'];?>,'&delete_lamina_ref',<?php echo $row_lamina['N_referencia_c']; ?>,'cotizacion_general_laminas_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COTIZACION"
                        title="ELIMINAR COTIZACION" border="0"><?php } ?></a><a href="referencias_l.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" />
                      </td>
                    </tr>

                    <tr>
                      <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
                      <td colspan="2" id="numero1"><strong>
                        <input name="N_cotizacion" type="hidden" value="<?php if($_GET['N_cotizacion']==''){ $num=$row_cotizacion['N_cotizacion']+1; echo $num; }else{  $num=$_GET['N_cotizacion']; echo $num;} ?>" />
                          <?php echo $num; ?>
                        </strong>
                      <!-- echo $num= !(isset($_GET['N_cotizacion'])&&$_GET['N_cotizacion']=='') ? $row_cotizacion['N_cotizacion']+1 : $_GET['N_cotizacion']; -->
                      </td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Fecha  Ingreso</td>
                        <td colspan="2" id="fuente1">Hora Ingreso</td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1"><input name="fecha_l" type="date" id="fecha_l" value="<?php echo date("Y-m-d");  ?>" size="10" /></td>
                        <td colspan="2" id="fuente1"><input name="hora_l" type="text" id="hora_l" value="<?php echo date("g:i a") ?>" size="10" readonly="readonly" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Estado de la Cotizaci&oacute;n</td>
                        <td id="fuente1">Generica </td>
                        <td id="fuente1"> Existente</td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1"><select name="B_estado" id="B_estado">
                          <option value="0"<?php if (!(strcmp("0", $row_lamina['B_estado']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                          <option value="1"<?php if (!(strcmp("1", $row_lamina['B_estado']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
                          <option value="2"<?php if (!(strcmp("2", $row_lamina['B_estado']))) {echo "selected=\"selected\"";} ?>>Rechazada</option>
                          <option value="3"<?php if (!(strcmp("3", $row_lamina['B_estado']))) {echo "selected=\"selected\"";} ?>>Obsoleta</option>
                        </select></td>
                        <td id="dato4">
                          <select name="ref" id="ref" onchange="if(form1.ref.value) { consultagenerica4(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                              <option value="0">-REF-</option>
                                 <?php  foreach($row_referencias as $row_referencias ) { ?>
                              <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
                          <?php } ?>
                          </select> 

                          <!-- <select name="ref" id="ref" onchange="if(form1.ref.value) { consultagenerica4(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                          <option value="" <?php if (!(strcmp("", $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>Select</option>
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
                            <?php
                          } while ($row_referencias = mysql_fetch_assoc($referencias));
                          $rows = mysql_num_rows($referencias);
                          if($rows > 0) {
                            mysql_data_seek($referencias, 0);
                            $row_referencias = mysql_fetch_assoc($referencias);
                          }
                          ?>
                        </select> --></td>
                        <td id="dato4">

                          <select name="ref2" id="ref2" onchange="if(form1.ref2.value) { consultaexistente4(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                               <option value="0">-REF-</option>
                                  <?php  foreach($row_referencias2 as $row_referencias2 ) { ?>
                               <option value="<?php echo $row_referencias2['id_ref']?>"<?php if (!(strcmp($row_referencias2['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias2['cod_ref']?></option>
                           <?php } ?>
                           </select> 

                           <!-- <select name="ref2" id="ref2" onchange="if(form1.ref2.value) { consultaexistente4(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" class="busqueda selectsMini">
                          <option value="" <?php if (!(strcmp("", $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>Select</option>
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_referencias2['id_ref']?>"<?php if (!(strcmp($row_referencias2['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias2['cod_ref']?></option>
                            <?php
                          } while ($row_referencias2 = mysql_fetch_assoc($referencias2));
                          $rows = mysql_num_rows($referencias2);
                          if($rows > 0) {
                            mysql_data_seek($referencias2, 0);
                            $row_referencias2 = mysql_fetch_assoc($referencias2);
                          }
                          ?>
                        </select> -->
                      </td>
                      </tr>
                      <tr>
                        <td colspan="4" id="fuente2">Nombre del Cliente</td>
                      </tr>
                      <tr>
                        <td colspan="4" id="fuente1">
                          <!-- debo dejar este filtro porq al cargar por get carga mas rapido -->
                          <select  name="clientes" id="clientes"onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit2();} else{ alert('Debe Seleccionar un CLIENTE'); }"style="width:250px">
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>><?php $cad=($row_clientes['nombre_c']); echo $cad;?></option>
                            <?php
                          } while ($row_clientes = mysql_fetch_assoc($clientes));
                          $rows = mysql_num_rows($clientes);
                          if($rows > 0) {
                            mysql_data_seek($clientes, 0);
                            $row_clientes = mysql_fetch_assoc($clientes);
                          }
                          ?>
                          </select><?php  
                          $activo=$row_cliente['estado_c'];
                          if($activo!='' && $activo!='ACTIVO'){
                            echo "Este cliente esta inactivo, debe activarlo en clientes";        
                            echo"<input name='inactivo' id='inactivo' type='hidden' value='$activo' />";
                          }
                          ?></td>
                        </tr>
                        <tr>
                          <td id="cabezamenu"><ul id="menuhorizontal">
                            <li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
                          </ul>
                        </td>
                        <td id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
                        </ul></td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                      </tr>       
                      <tr id="tr1">
                        <td colspan="5" id="titulo2">LAMINAS</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="5" id="titulo1">CARACTERISTICAS PRINCIPALES</td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente8">&nbsp;</td>
                        <td colspan="2" id="fuente8">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="fuente1">Ancho (cms)</td>
                        <td colspan="2" id="fuente1">Repeticion (cms)</td>
                        <td colspan="2" id="fuente1">Calibre   &nbsp;(mills) </td>
                      </tr>
                      <tr>
                        <td id="dato1"><input name="N_ancho_l" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_ancho_l" value="<?php echo $row_lamina['ancho_ref']?>" /></td>
                        <td colspan="2" id="dato1"><input name="N_repeticion_l" required="required" type="number" style=" width:70px" min="0" step="1" id="N_repeticion_l" value="<?php echo $row_lamina['N_repeticion_l']?>"/></td>
                        <td colspan="2" id="dato1"><input name="N_calibre_l" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_calibre_l" value="<?php echo $row_lamina['calibre_ref'] ?>" /></td>
                      </tr>
                      <tr>
                        <td id="fuente5">&nbsp;</td>
                        <td colspan="2" id="fuente5">&nbsp;</td>
                        <td colspan="2" id="fuente5">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="5" id="titulo1">MATERIAL COEXTRUSION</td>
                      </tr>      <tr>
                        <td id="fuente1">Material</td>
                        <td colspan="2" id="fuente7">&nbsp;</td>
                        <td colspan="2" id="fuente1">Color:</td>
                      </tr>
                      <tr>
                        <td id="fuente1"><select name="Str_tipo_coextrusion" id="Str_tipo_coextrusion" onChange="mostrarCapa(this)">
                          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_lamina['material_ref']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
                          <option value="PIGMENTADO B/N"<?php if (!(strcmp("PIGMENTADO B/N", $row_lamina['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/N</option>
                          <option value="PIGMENTADO B/B"<?php if (!(strcmp("PIGMENTADO B/B", $row_lamina['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/B</option>

                        </select></td>
                        <td colspan="2" id="fuente3">Capa Externa:</td>
                        <td colspan="2" id="fuente1"><select name="Str_capa_ext_coext" id="Str_capa_ext_coext">
                          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_lamina['pigm_ext_egp']))) {echo "selected=\"selected\"";} ?>>TRANSP</option>
                          <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_lamina['pigm_ext_egp']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
                          <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_lamina['pigm_ext_egp']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente3">Capa Interna:</td>
                        <td colspan="2" id="fuente1"><select name="Str_capa_inter_coext" id="Str_capa_inter_coext">
                          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_lamina['pigm_int_epg']))) {echo "selected=\"selected\"";} ?>>TRANSP</option>
                          <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_lamina['pigm_int_epg']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
                          <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_lamina['pigm_int_epg']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td colspan="5" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="5" id="titulo1">IMPRESION</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente1">Impresion:

                          <select name="B_impresion" id="B_impresion" onchange="mostrarColor(this)">
                            <option value="0"<?php if (!(strcmp("0",$row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            <option value="1"<?php if (strcmp("1", $row_lamina['impresion_ref'])) {echo "selected=\"selected\"";} ?>>SI</option>

                          </select>
                          <select name="N_colores_impresion" id="N_colores_impresion"> 
                            <option value="1"<?php if (!(strcmp("1", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>1 color</option>
                            <option value="2"<?php if (!(strcmp("2", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>2 color</option>
                            <option value="3"<?php if (!(strcmp("3", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>3 color</option>
                            <option value="4"<?php if (!(strcmp("4", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>4 color</option>
                            <option value="5"<?php if (!(strcmp("5", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>5 color</option>
                            <option value="6"<?php if (!(strcmp("6", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>6 color</option>
                            <option value="7"<?php if (!(strcmp("7", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>7 color</option>
                            <option value="8"<?php if (!(strcmp("8", $row_lamina['impresion_ref']))) {echo "selected=\"selected\"";} ?>>8 color</option>
                          </select></td>
                          <td colspan="2" id="fuente1"> Se Facturan Artes y Planchas ?          
                            <select name="B_cyreles" id="B_cyreles">
                              <option value=""<?php if (!(strcmp("", $row_lamina['B_cyreles']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                              <option value="1"<?php if (!(strcmp("1", $row_lamina['B_cyreles']))) {echo "selected=\"selected\"";} ?>>SI</option>
                              <option value="0"<?php if (!(strcmp("0",$row_lamina['B_cyreles']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            </select></td>
                          </tr>
                          <tr>
                            <td colspan="5" id="fuente1">&nbsp;</td>
                          </tr>
                          <td colspan="5" id="titulo1">PRESENTACION</td>
                          <tr>
                            <td colspan="2" id="fuente1">Diametro Maximo x Rollo  (cms)</td>
                            <td id="fuente1">Peso Maximo x Rollo(kgr)</td>
                            <td width="133" id="fuente1">Cantidad Metros x Rollo</td>
                            <td id="fuente1">Tipo de Embobinado</td>
                          </tr>
                          <tr>
                            <td colspan="2" id="fuente1"><input name="N_diametro_max_l" type="number" style=" width:70px" min="0" step="1" id="N_diametro_max_l" value="<?php echo $row_lamina['N_diametro_max_l']?>"/></td>
                            <td id="dato1"><input name="N_peso_max"  type="number" style=" width:70px" min="0" step="1" id="N_peso_max" value="<?php echo $row_lamina['N_peso_max_l'] ?>"/></td>
                            <td id="fuente1"><input name="N_cantidad_metros_r_l" type="number" style=" width:70px" min="0" step="1" id="N_cantidad_metros_r_l" size="15" value="<?php echo $row_lamina['N_cantidad_metros_r_l']?>"/></td>
                            <td id="fuente1"><input name="N_embobinado" type="number" style=" width:70px" min="0" step="1" id="N_embobinado" value="<?php echo $row_lamina['N_embobinado'] ?>" onKeyUp="conMayusculas(this)" />
                              <a href="javascript:verFoto('embobinado_lamina.php','575','510')" >Ver Cuadro</a></td>
                            </tr>
                            <tr>
                              <td colspan="5" id="fuente1">&nbsp;</td>      
                              <tr id="tr1">
                                <td colspan="5" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
                                <tr>
                                  <td colspan="2" id="fuente1">Moneda / Precio x Kilo</td>
                                  <td colspan="2" id="fuente1">Plazo de pago</td>
                                  <td width="219" id="fuente1">Cantidad Solicitada (Kilos)</td>
                                </tr>
                                <tr>
                                  <td colspan="2" id="fuente1"><select name="Str_moneda_l" id="Str_moneda_l">
                                    <option value="COL$"<?php if (!(strcmp("COL$", $row_lamina['Str_moneda']))) {echo "selected=\"selected\"";} ?>>COL$</option>
                                    <option value="USD$"<?php if (!(strcmp("USD$", $row_lamina['Str_moneda']))) {echo "selected=\"selected\"";} ?>>USD$</option>
                                    <option value="EUR&euro;"<?php if (!(strcmp("EUR&euro;", $row_lamina['Str_moneda']))) {echo "selected=\"selected\"";} ?>>EUR&euro;</option>
                                  </select>
                                  <input name="N_precio_k" type="number" style="width: 100px" min="0" step="0.01" id="N_precio_k" value="<?php echo $row_lamina['N_precio_k']?>" /></td>
                                  <td colspan="2" id="dato1"><select name="Str_plazo" id="Str_plazo">
                                    <option>*</option>
                                    <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                                    <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                                    <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias</option>
                                    <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias</option>
                                    <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias</option>
                                    <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias</option>
                                    <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_lamina['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias</option>
                                  </select></td>
                                  <td id="fuente1"><input name="N_cantidad_l" type="number" style=" width:100px" min="0" id="N_cantidad_l" step="0.01" value="<?php echo $row_lamina['N_cantidad'] ?>" /><!--onKeyUp="puntos(this,this.value.charAt(this.value.length-1))"--></td>
                                </tr>
                                <tr>
                                  <td id="fuente1">Incoterms: </td>
                                  <td id="fuente1">&nbsp;</td>
                                  <td colspan="2" id="fuente1">Vendedor</td>
                                  <td id="fuente1">Comision</td>
                                </tr>
                                <tr>
                                  <td colspan="2" id="fuente1"><select name="Str_incoterms_l" id="Str_incoterms_l">
                                    <option >*</option>
                                    <option value="EXW"<?php if (!(strcmp("EXW", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>EXW</option>
                                    <option value="FCA"<?php if (!(strcmp("FCA", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FCA</option>
                                    <option value="FAS"<?php if (!(strcmp("FAS", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FAS</option>
                                    <option value="FOB"<?php if (!(strcmp("FOB", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FOB</option>
                                    <option value="CFR"<?php if (!(strcmp("CFR", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CFR</option>
                                    <option value="CIF"<?php if (!(strcmp("CIF", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIF</option>
                                    <option value="CPT"<?php if (!(strcmp("CPT", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CPT</option>
                                    <option value="CIP"<?php if (!(strcmp("CIP", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIP</option>
                                    <option value="DAF"<?php if (!(strcmp("DAF", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DAF</option>
                                    <option value="DES"<?php if (!(strcmp("DES", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DES</option>
                                    <option value="DEQ"<?php if (!(strcmp("DEQ", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DEQ</option>
                                    <option value="DDU"<?php if (!(strcmp("DDU", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDU</option>
                                    <option value="DDP"<?php if (!(strcmp("DDP", $row_lamina['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDP</option>
                                  </select>
                                  <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a></td>
                                  <td colspan="2" id="fuente1">
                                    <?php
                                    $nom_vend = $row_usuario['nombre_usuario'];
                                    $sqldato="SELECT nombre_vendedor FROM vendedor WHERE nombre_vendedor LIKE '%$nom_vend%'";
                                    $resultdato=mysql_query($sqldato);
                                    $numce1= mysql_num_rows($resultdato);
                                    if($numce1!='') { 
                                      $nombre_vendedor=mysql_result($resultdato,0,'nombre_vendedor');
                                    }
                                    ?><select name="vendedor" id="vendedor" required>
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
                                    <td id="fuente1"><input name="N_comision" type="number" style=" width:60px" step="0.1" id="N_comision" maxlength="1" value="" min="1" required="required"/>
                                      <strong>%</strong></td>
                                    </tr>
                                    <tr>
                                      <td colspan="5" id="fuente1"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="5" id="fuente1"> Observaciones:</td>
                                    </tr>
                                    <tr>
                                      <td colspan="5" id="dato1"><textarea name="nota_l" cols="78" rows="2" id="nota_l"onKeyUp="conMayusculas(this)"></textarea></td>
                                    </tr>
                                    <tr>
                                      <td colspan="5" id="dato1">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="5" id="fuente2">
                                        <input type="hidden" name="Str_tipo" id="Str_tipo" value="LAMINA" />
                                        <input type="hidden" name="Str_unidad_vta" id="Str_unidad_vta" value="KILO" />
                                        <input name="responsable_modificacion" type="hidden" value="" />
                                        <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d");?>" />
                                        <?php if( $_GET['id_ref']!=''){echo "<input name='B_generica' type='hidden' value='1'/>";}else{echo "<input name='B_generica' type='hidden' value='0'/>";} ?>

                                        <input name="N_referencia" type="hidden" value="<?php if( $row_lamina['cod_ref']!=''){echo $row_lamina['cod_ref'];} else{echo $row_ref['N_referencia']+1;}?>" />
                                        <input name="hora_modificacion" type="hidden" value="" />
                                        <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
                                        <input name="valor" type="hidden" value="1" />
                                        <input name="submit" class="botonGeneral" type="submit"value="GUARDAR COTIZACION LAMINA" 
                                        <?php if($activo!='' && $activo!='ACTIVO'){ ?> onclick="return confirActivo();" <?php } ?>/></td>
                                      </tr>
                                    </table>
                                    <input type="hidden" name="MM_insert" value="form1">
                                  </form></td>
                                </tr> 
                              </tr>
                            </table>
                          </form>
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
    </body>
</html>
<script>
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
