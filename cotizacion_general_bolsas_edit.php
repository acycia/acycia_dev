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

/*mysql_select_db($database_conexion1, $conexion1);
$query_egp = "SELECT * FROM egp ORDER BY n_egp DESC";
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);*/
//PARA IMPRIMIR NUMERO DE COTIZACION
$colname_ver_cot = "1";   //ESTE CODIGO SE UTILIZA PARA LIMPIAR EL GET DE PUNTOS
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_cot= (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
//$N_cotizacion=$_GET['N_cotizacion'];
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = sprintf("SELECT * FROM Tbl_cotizaciones WHERE N_cotizacion='%s'",$colname_ver_cot);
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

//SELECT PARA REALIZAR UPDATE EN LA BASE DE DATOS
$colname_ver_cliente = "1";   //ESTE CODIGO SE UTILIZA PARA LIMPIAR EL GET DE PUNTOS
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_cliente= (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
//$Str_nit=$_GET['Str_nit'];
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE nit_c='%s'",$colname_ver_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//SELECT PARA REALIZAR UPDATE EN LA BASE DE DATOS
$colname_ver_bolsa = "-1";
if (isset($_GET['cod_ref'])) 
{
  $colname_ver_bolsa = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
$colname_ver_bolsa2 = "-1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_bolsa2 = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa = sprintf("SELECT * FROM Tbl_cotiza_bolsa WHERE N_cotizacion='%s' and N_referencia_c='%s'",$colname_ver_bolsa2,$colname_ver_bolsa);
$bolsa = mysql_query($query_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);
$totalRows_bolsa = mysql_num_rows($bolsa);
//TRAE EL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//OBSERVACIONES
$colname_obs = "-1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_obs= (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_obs = sprintf("SELECT * FROM Tbl_cotiza_bolsa_obs WHERE N_cotizacion='%s' and N_referencia_c='%s'",$colname_obs,$colname_ver_bolsa);
$obs = mysql_query($query_obs, $conexion1) or die(mysql_error());
$row_obs = mysql_fetch_assoc($obs);
$totalRows_obs = mysql_num_rows($obs);
//UTILIZADO PARA ENVIAR POR GET A JAVASCRIPT EL IDREFCLIENTE Y PODER ELIMINAR LA REFERENCIA EN TBL_CLIENTE_REFERENCIA EN DELETE2
$colname_ver_idrefcliente= "-1";
if (isset($_GET['cod_ref'])) 
{
  $colname_ver_idrefcliente = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
$colname_ver_idrefcliente2 = "-1";
if (isset($_GET['N_cotizacion'])) 
{
$colname_ver_idrefcliente2 = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
$colname_ver_nit = "1";
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_nit= (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_id_refcliente = sprintf("SELECT * FROM Tbl_cliente_referencia WHERE N_cotizacion='%s' and N_referencia='%s' and Str_nit='%s'",$colname_ver_idrefcliente2,$colname_ver_idrefcliente,$colname_ver_nit);
$id_refcliente = mysql_query($query_id_refcliente, $conexion1) or die(mysql_error());
$row_id_refcliente = mysql_fetch_assoc($id_refcliente);
$totalRows_id_refcliente = mysql_num_rows($id_refcliente);
 

$colname_refer = "-1";
if (isset($_GET['cod_ref'])) 
{
  $colname_refer = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
} 

mysql_select_db($database_conexion1, $conexion1);
$query_refer = sprintf("SELECT valor_impuesto,peso_millar_ref,peso_millar_bols,b_solapa_caract_ref FROM Tbl_referencia WHERE CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) ='%s'  ",$colname_refer);
$refer = mysql_query($query_refer, $conexion1) or die(mysql_error());
$row_refer = mysql_fetch_assoc($refer);
$totalRows_refer = mysql_num_rows($refer);
 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
</head>
<body onload="mostrarColor()">
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
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" enctype="multipart/form-data" onsubmit="MM_validateForm('Str_nit','','R','N_ancho','','RisNum','N_alto','','RisNum','N_solapa','','RisNum','B_fuelle','','RisNum','N_calibre','','','RisNum','N_cant_impresion','','RisNum','N_comision','','RisNum','vendedor','','RisNum');return document.MM_returnValue">
                    <table id="tabla1">
                      <tr id="tr1">
                        <td width="113" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                        <td colspan="2" nowrap="nowrap" id="titulo2">COTIZACION BOLSA</td>
                        <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
                      </tr>
                      <tr>
                        <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
                        <td colspan="2" id="numero1"><strong>NIT N&deg; 
                          <input name="Str_nit" type="text"id="Str_nit"  value="<?php echo $row_bolsa['Str_nit']?>" readonly="readonly" />
                        </strong></td>
                        <td colspan="2" id="fuente2"><?php $tipo=$_SESSION['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?><a href="javascript:eliminar_b('delete_bolsa',<?php echo $row_bolsa['N_cotizacion'];?>,'&delete_bolsa_ref',<?php echo $row_bolsa['N_referencia_c'];?>,'&id_refcliente',<?php echo $row_id_refcliente['id_refcliente']; ?>,'&tipo',<?php echo $_SESSION['tipo_usuario']; ?>,'cotizacion_general_bolsas_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COTIZACION"
                          title="ELIMINAR COTIZACION" border="0"><?php } ?></a><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES" title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" />
                        </td>
                      </tr>    
                      <tr>
                        <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
                        <td colspan="2" id="numero1"><strong>
                          <input name="N_cotizacion" type="hidden" value="<?php $num=$row_bolsa['N_cotizacion']; echo $num; ?>" />
                          <?php echo $num; ?></strong></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Fecha  Ingreso</td>
                          <td colspan="2" id="fuente1">Hora Ingreso</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1"><input name="fecha_b" type="date" id="fecha_b" value="<?php echo $row_bolsa['fecha_creacion']; ?>" size="10" /></td>
                          <td colspan="2" id="fuente1"><input name="hora_b" type="text" id="hora_b" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Estado de la Cotizaci&oacute;n</td>
                          <td colspan="2" id="fuente1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1"><select name="B_estado" id="B_estado">
                            <option value="0"<?php if (!(strcmp("0", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                            <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
                            <option value="2"<?php if (!(strcmp("2", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Rechazada</option>
                            <option value="3"<?php if (!(strcmp("3", $row_bolsa['B_estado']))) {echo "selected=\"selected\"";} ?>>Obsoleta</option>
                          </select></td>
                          <td colspan="2" id="dato1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="4" id="fuente2">Nombre del Cliente</td>
                        </tr>
                        <tr>
                          <td colspan="4" id="fuente1">
                            <select name="clientes" id="clientes"onchange="Javascript:document.form1.Str_nit.value=this.value" style="width:200px">
                           <option value=""><?php echo $row_cliente['nombre_c']?></option>

                         </select></td>
                       </tr>
                       <tr>
                        <td  id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
                        </ul></td>
                        <td id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
                        </ul></td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                         <td colspan="7" id="fuente1"><em style="color: red;" >Si elimina una cotizacion; se traerá el precio de la anterior cotizacion! </em> </td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="7" id="titulo2">BOLSAS PLASTICAS (Seguridad/Courrier/Normal)</td>
                      </tr>
                      <tr>
                        <td colspan="7" id="fuente1">&nbsp;</td>     
                        <tr id="tr1">
                          <td colspan="7" id="titulo1">CARACTERISTICAS PRINCIPALES</td> 
                          <tr>
                            <td id="fuente1">Ancho (cms)</td>
                            <td colspan="2" id="fuente1">Alto (cms)</td>
                            <td id="fuente1">Solapa (cms)</td>
                            <td id="fuente1">Sencilla/Doble</td>
                            <td id="fuente1">Fuelle (cms)</td>
                          </tr>
                          <tr>
                            <td id="dato1"><input name="N_ancho" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_ancho" value="<?php echo $row_bolsa['N_ancho']?>"/></td>
                            <td colspan="2" id="dato1"><input name="N_alto" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_alto" value="<?php echo $row_bolsa['N_alto']?>"/></td>
                            <td colspan="2" id="dato1">
                              <input name="N_solapa" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_solapa" value="<?php echo $row_bolsa['N_solapa']?>"/>

                              <select name="tiposolapa" id="tiposolapa" style="width:100px" >
                                <option value="0"<?php if (!(strcmp("0", $row_refer['b_solapa_caract_ref']))) {echo "selected=\"selected\"";} ?> >N/A</option>
                                <option value="2"<?php if (!(strcmp("2", $row_refer['b_solapa_caract_ref']))) {echo "selected=\"selected\"";} ?>  >Sencilla</option>
                                <option value="1"<?php if (!(strcmp("1", $row_refer['b_solapa_caract_ref']))) {echo "selected=\"selected\"";} ?>  >Doble</option>
                              </select>
                            </td>
                            <td id="dato1"><input name="B_fuelle" required="required" type="number" style=" width:70px" min="0" step="0.01" id="B_fuelle" value="<?php echo $row_bolsa['B_fuelle']?>"/></td>
                          </tr>
                          <tr>
                            <td id="fuente1">Calibre (micras)</td>
                            <td colspan="2" id="fuente1">Tipo Bolsa</td>
                            <td colspan="2" id="fuente1"> Bolsillo canguro</td>
                            <td id="fuente1">Tama&ntilde;o</td>
                          </tr>
                          <tr>
                            <td id="dato1"><input name="N_calibre" required="required" type="number" style=" width:70px" min="0" step="0.01"id="N_calibre" value="<?php echo $row_bolsa['N_calibre']?>"/></td>
                            <td colspan="2" id="dato1">
                              <select name="tipo_bolsa" id="tipo_bolsa" style="width:100px" >
                                <option value="" <?php if (!(strcmp("", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option> 
                                <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
                                <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
                                <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
                                <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
                                <option value="COMPOSTABLE" <?php if (!(strcmp("COMPOSTABLE", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>COMPOSTABLE</option>
                                <option value="BOLSA TROQUELADA" <?php if (!(strcmp("BOLSA TROQUELADA", $row_bolsa['tipo_bolsa']))) {echo "selected=\"selected\"";} ?>>BOLSA TROQUELADA</option>
                              </select>
                            </td>
                            <td colspan="2" id="dato1"><select name="B_bolsillo" id="B_bolsillo" onchange="mostrarBolsillo(this)">
                              <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_bolsillo']))) {echo "selected=\"selected\"";} ?>>NO</option>
                              <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_bolsillo']))) {echo "selected=\"selected\"";} ?>>SI</option>
                            </select></td>
                            <td id="dato1"><input name="N_tamano_bolsillo" required="required" type="number" style=" width:70px" min="0" step="0.01" value="<?php echo $row_bolsa['N_tamano_bolsillo']?>" id="N_tamano_bolsillo"/></td>
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
                            <td colspan="2" id="fuente1">Color:</td>
                          </tr>
                          <tr>
                            <td id="fuente1"><select name="Str_tipo_coextrusion" id="Str_tipo_coextrusion" onchange="mostrarCapa(this)">
                              <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_bolsa['Str_tipo_coextrusion']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
                              <option value="PIGMENTADO B/N"<?php if (!(strcmp("PIGMENTADO B/N", $row_bolsa['Str_tipo_coextrusion']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/N</option>
                              <option value="PIGMENTADO B/B"<?php if (!(strcmp("PIGMENTADO B/B", $row_bolsa['Str_tipo_coextrusion']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/B</option>
                            </select></td>
                            <td colspan="2" id="fuente3">Capa Externa:</td>
                            <td colspan="2" id="fuente1"><select name="Str_capa_ext_coext" id="Str_capa_ext_coext">
                              <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_bolsa['Str_capa_ext_coext']))) {echo "selected=\"selected\"";} ?>>TRANSP</option>
                              <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_bolsa['Str_capa_ext_coext']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
                              <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_bolsa['Str_capa_ext_coext']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
                            </select></td>
                          </tr>
                          <tr>
                            <td id="fuente1">&nbsp;</td>
                            <td colspan="2" id="fuente3">Capa Interna:</td>
                            <td colspan="2" id="fuente1"><select name="Str_capa_inter_coext" id="Str_capa_inter_coext">
                              <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_bolsa['Str_capa_inter_coext']))) {echo "selected=\"selected\"";} ?>>TRANSP</option>
                              <option value="BLANCO"<?php if (!(strcmp("BLANCO", $row_bolsa['Str_capa_inter_coext']))) {echo "selected=\"selected\"";} ?>>BLANCO</option>
                              <option value="NEGRO"<?php if (!(strcmp("NEGRO", $row_bolsa['Str_capa_inter_coext']))) {echo "selected=\"selected\"";} ?>>NEGRO</option>
                            </select></td>
                          </tr>
                          <tr>
                            <td id="fuente1">&nbsp;</td>
                            <td colspan="2" id="fuente1">&nbsp;</td>
                            <td colspan="2" id="fuente1">&nbsp;</td>
                          </tr>
                          <tr id="tr1">
                            <td colspan="7" id="titulo1">IMPRESION</td>
                          </tr>
                          <tr>
                            <td colspan="3" id="fuente1">Impresion:
                              <select name="B_impresion" id="B_impresion" onchange="mostrarColor(this)"> 
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_impresion']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_impresion']))) {echo "selected=\"selected\"";} ?>>SI</option>
                              </select>
                              <select name="N_colores_impresion" id="N_colores_impresion">
                                <option value="0"<?php if (!(strcmp("0", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>0</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>1 color</option>
                                <option value="2"<?php if (!(strcmp("2", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>2 color</option>
                                <option value="3"<?php if (!(strcmp("3", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>3 color</option>
                                <option value="4"<?php if (!(strcmp("4", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>4 color</option>
                                <option value="5"<?php if (!(strcmp("5", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>5 color</option>
                                <option value="6"<?php if (!(strcmp("6", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>6 color</option>
                                <option value="7"<?php if (!(strcmp("7", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>7 color</option>
                                <option value="8"<?php if (!(strcmp("8", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>8 color</option>
                              </select></td>
                              <td width="133" id="fuente1">Se Facturan Artes y Planchas ?</td>
                              <td width="219" id="fuente1"><select name="B_cyreles" id="B_cyreles">
                                <option value=""<?php if (!(strcmp("", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>SI</option>
                                <option value="0"<?php if (!(strcmp("0", $row_bolsa['B_cyreles']))) {echo "selected=\"selected\"";} ?>>NO</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td id="fuente1">&nbsp;</td>
                              <td colspan="2" id="fuente1">&nbsp;</td>
                              <td colspan="2" id="fuente1">&nbsp;</td>
                            </tr>
                            <tr id="tr1">
                              <td colspan="7" id="titulo1">SELLADO</td>
                            </tr>
                            <tr >
                              <td colspan="7" id="titulo1">Tipo de Cierre Principal</td>
                            </tr>
                            <tr>
                              <td id="fuente1">Cinta Seguridad</td>
                              <td colspan="2" id="fuente1">
                                <select name="B_sellado_seguridad" id="B_sellado_seguridad">
                                  <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_seguridad']))) {echo "selected=\"selected\"";} ?>>NO</option>          
                                  <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_seguridad']))) {echo "selected=\"selected\"";} ?>>SI</option>
                                </select>
                              </td>
                              <td id="fuente1">Cinta Permanente</td>
                              <td id="fuente1"><select name="B_sellado_permanente" id="B_sellado_permanente">
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_permanente']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_permanente']))) {echo "selected=\"selected\"";} ?>>SI</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td id="fuente1">Cinta Resellable</td>
                              <td colspan="2" id="fuente1">
                                <select name="B_sellado_resellable" id="B_sellado_resellable">
                                  <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_resellable']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                  <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_resellable']))) {echo "selected=\"selected\"";} ?>>SI</option>
                                </select>
                              </td>
                              <td id="fuente1">Hot Melt</td>
                              <td id="fuente1">
                                <select name="B_sellado_hotm" id="B_sellado_hotm">
                                  <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_sellado_hotm']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                  <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_sellado_hotm']))) {echo "selected=\"selected\"";} ?>>SI</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="7" id="titulo1">Tipo de Sello</td>
                            </tr>
                            <tr>
                              <td id="fuente1">Lateral</td>
                              <td colspan="2" id="fuente1"><select name="Str_sellado_lateral" id="Str_sellado_lateral">
                                <option></option>
                                <option value="NO"<?php if (!(strcmp("NO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>N/A</option>
                                <option value="HILO"<?php if (!(strcmp("HILO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>HILO</option>
                                <option value="PLANO"<?php if (!(strcmp("PLANO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
                                <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_bolsa['Str_sellado_lateral']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
                              </select></td>
                              <td id="fuente1">Fondo</td>
                              <td id="fuente1"><select name="B_fondo" id="B_fondo">
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_fondo']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_fondo']))) {echo "selected=\"selected\"";} ?>>SI</option>

                              </select></td>
                            </tr>
                            <tr>
                              <td id="fuente1">Numeracion</td>
                              <td id="fuente1">Troquel</td>
                              <td id="fuente1">Precorte</td>
                              <td id="fuente1">&nbsp;</td>
                              <td id="fuente1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td id="fuente1"><select name="B_numeracion" id="B_numeracion">
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_numeracion']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_numeracion']))) {echo "selected=\"selected\"";} ?>>SI</option>
                              </select></td>
                              <td id="fuente1"><select name="B_troquel" id="B_troquel">
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>

                              </select></td>
                              <td id="fuente1"><select name="B_precorte" id="B_precorte">
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_precorte']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_precorte']))) {echo "selected=\"selected\"";} ?>>SI</option>
                              </select></td>
                              <td id="fuente1">Codigo de Barras</td>
                              <td id="fuente1"><select name="B_codigo_b" id="B_codigo_b">
                                <option value="0"<?php if (!(strcmp("0",$row_bolsa['B_codigo_b']))) {echo "selected=\"selected\"";} ?>>NO</option>
                                <option value="1"<?php if (!(strcmp("1", $row_bolsa['B_codigo_b']))) {echo "selected=\"selected\"";} ?>>SI</option>
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
                                <span style="color: red;" >IMPUESTO PLASTICO</span> <input type="checkbox" name="impuesto" id="impuesto" value=""<?php if (!(strcmp($row_bolsa['impuesto'],1))) {echo "checked=\"checked\"";} ?>> <label for="impuesto"> &nbsp;&nbsp;<!-- Adjunto PDF: <input name="pdf_impuesto" type="file" size="20" maxlength="60"class="botones_file"> -->
                                <?php if($row_cliente['pdf_impuesto']): ?>
                                  Adjunto PDF:   
                                                <a href="javascript:verFoto('archivosc/impuesto/<?php echo $row_cliente['pdf_impuesto']; ?>','610','490')"> 
                                                  <?php if($row_cliente['pdf_impuesto']!='') echo "Impuesto"; ?><em> (Este pdf se adjunta en Cliente) </em>
                                                </a>
                                <?php endif; ?>
                              </td>
                              <td colspan="4">
                                <div name="formular" id="formular" style="display: none;" >
                                    <span style="color: red;" >RECALCULAR IMPUESTO CON FORMULA </span> <input type="checkbox" name="calculaformula" id="calculaformula" title="Se recalcula con el precio que agregue en Precio Anterior" value="1"> <label for="calculaformula">
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
                                <input name="N_precio" type="text" style="width:100px" min="0" step="0.01" id="N_precio" value="<?php echo $row_bolsa['N_precio']?>"/>
                               </td> 
                               <td id="fuente5">
                                 <input name="valor_impuesto" type="text" style="width:80px" min="0" step="0.01" id="valor_impuesto" value="<?php echo $row_bolsa['valor_impuesto']=='0'?$row_refer['valor_impuesto'] : $row_bolsa['valor_impuesto'] ;?>"/> 
                               </td>
                               <td id="fuente5"> 
                                <input name="N_precio_old" type="text" style="width:100px" min="0" step="0.01" id="N_precio_old" value="<?php echo $row_bolsa['N_precio_old']; ?>"/>
                            </td>
                              <td id="dato1">
                                <select name="Str_unidad_vta" id="Str_unidad_vta">
                                <option>*</option>
                                <option value="PRECIO UNITARIO"<?php if (!(strcmp("PRECIO UNITARIO", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO UNITARIO</option>
                                <option value="PRECIO MILLAR"<?php if (!(strcmp("PRECIO MILLAR", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO MILLAR</option>
                                <option value="PRECIO PAQUETE"<?php if (!(strcmp("PRECIO PAQUETE", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO PAQUETE</option>
                                <option value="PRECIO KILOS"<?php if (!(strcmp("PRECIO KILOS", $row_bolsa['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO KILOS</option>
                              </select></td>
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
                              <td id="fuente1">
                                <input name="N_cant_impresion" type="number" style=" width:100px" step="0.01" min="0" id="N_cant_impresion" value="<?php echo $row_bolsa['N_cant_impresion'];?>" />
                              </td>
                            </tr>
                            <tr>
                              <td id="fuente1">Incoterms:</td>
                              <td id="fuente1">&nbsp;</td>
                              <td colspan="2" id="fuente1">Vendedor</td>
                              <td id="fuente1">Comision</td>
                            </tr>
                            <tr>
                              <td colspan="2" id="fuente1">
                                <select name="Str_incoterms" id="Str_incoterms">
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
                                </select> <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a>
                              </td>
                              <td colspan="2" id="fuente1"><select name="vendedor" id="vendedor" required>
                                <option value="" <?php if (!(strcmp("", $row_bolsa['Str_usuario']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                                <?php
                                do {  
                                  ?>
                                  <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_bolsa['Str_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
                                  <?php
                                } while ($row_vendedores = mysql_fetch_assoc($vendedores));
                                $rows = mysql_num_rows($vendedores);
                                if($rows > 0) {
                                  mysql_data_seek($vendedores, 0);
                                  $row_vendedores = mysql_fetch_assoc($vendedores);
                                }
                                ?>
                              </select></td>
                              <td id="fuente1"><input name="N_comision" type="number" style=" width:60px" step="0.1" id="N_comision" min="0" max="9"  value="<?php echo $row_bolsa['N_comision']?>"/>
                                <strong>%</strong></td>
                              </tr>
                              <tr>
                                <td colspan="7" id="fuente1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="7" id="fuente1">Uso de la Bolsa Observaciones:</td>
                              </tr>
                              <tr>
                                <td colspan="7" id="dato1"><textarea name="nota_b" cols="78" rows="2" id="nota_b"onKeyUp="conMayusculas(this)"><?php echo $row_obs['texto'] ?></textarea></td>
                              </tr>
                              <tr>
                                <td colspan="7" id="dato1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="7" id="fuente2">
                                  <input name="peso_millar_ref" type="hidden" id="peso_millar_ref" value="<?php echo $row_refer['peso_millar_ref']=='' ? 0 : $row_refer['peso_millar_ref'];?>"/>
                                  <input name="peso_millar_bols" type="hidden" id="peso_millar_bols" value="<?php echo $row_refer['peso_millar_bols']=='' ? 0 : $row_refer['peso_millar_bols'];?>"/>
                                  <input type="hidden" name="Str_tipo" id="Str_tipo" value="<?php echo $row_bolsa['Str_tipo']?>" />
                                  <input name="responsable_modificacion" type="hidden" value="" />
                                  <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d");?>" />
                                  <input name="B_generica" type="hidden" value="<?php echo $row_bolsa['B_generica']?>" />
                                  <input name="hora_modificacion" type="hidden" value="" />
                                  <input name="N_referencia" type="hidden" value="<?php echo $_GET['cod_ref']?>" />
                                  <input name="tipo_usuario" type="hidden" value="<?php echo $_SESSION['tipo_usuario']; ?>" />
                                  <input name="valor" type="hidden" value="2" />
                                  <input name="submit" class="botonGeneral" type="submit"value="EDITAR COTIZACION BOLSA" /></td>
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
  </body>
</html>
<script>
 
    $('#impuesto').on('change', function() { 
           sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
    
    });

   $('#valor_impuesto').on('change', function() { 
          sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
 
   });


   $('#N_precio').on('change', function() { 
          sumaImpuesto($("#N_precio").val(),$("#valor_impuesto").val());
   
   });
</script>
<script>
 $(document).ready(function(){

   var editar =  "<?php echo $_SESSION['no_edita'];?>";
   if(editar==0){

     $("input").attr('disabled','disabled');
     $("textarea").attr('disabled','disabled');
     $("select").attr('disabled','disabled'); 

     $('a').each(function() { 
       $(this).attr('href', '#');
     });
              //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
   }
 });

   $(document).ready(function(){
     if( $("#valor_impuesto").val() > '0' ) 
        $("#formular").show(200);
   }); 
   
   
   

 
  $('#calculaformula').on('change', function() { 
      if( $("#N_ancho").val()!='' && $("#N_alto").val()!='' && $("#B_fuelle").val()!='' && $("#N_solapa").val()!='' && $("#N_calibre").val()!='' && $("#N_tamano_bolsillo").val()!='' && $("#N_precio_old").val()!='' ) {   
     
      pesoMillarFormulaCotiz($("#tiposolapa").val(),$("#N_ancho").val(),$("#N_alto").val(),$("#B_fuelle").val(),$("#N_solapa").val(),$("#N_calibre").val(),$("#N_tamano_bolsillo").val(),$("#N_precio_old").val() );
    }
 });

</script> 
<?php
mysql_free_result($usuario);
mysql_free_result($cliente);
mysql_free_result($vendedores);
mysql_free_result($cotizacion);
mysql_free_result($obs);
?>
