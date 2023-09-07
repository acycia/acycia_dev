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
include('rud_cotizaciones/rud_cotizacion_packing.php');//SISTEMA RUW PRA LA BASE DE DATOS
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
$query_cotizacion = sprintf("SELECT * FROM Tbl_cliente_referencia WHERE N_cotizacion='%s'",$colname_ver_cot);
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);
//SELECT PARA REALIZAR UPDATE EN LA BASE DE DATOS
$colname_ver_cliente = "1";   //ESTE CODIGO SE UTILIZA PARA LIMPIAR EL GET DE PUNTOS
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_cliente = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
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
$colname_ver_packing = "-1";
if (isset($_GET['cod_ref'])) 
{
  $colname_ver_packing = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
$colname_ver_packing2 = "-1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_packing2 = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_packing = sprintf("SELECT * FROM Tbl_cotiza_packing WHERE N_cotizacion='%s'  and N_referencia_c='%s'",$colname_ver_packing2,$colname_ver_packing);
$packing = mysql_query($query_packing, $conexion1) or die(mysql_error());
$row_packing = mysql_fetch_assoc($packing);
$totalRows_packing = mysql_num_rows($packing);
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
$query_obs = sprintf("SELECT * FROM Tbl_cotiza_packing_obs WHERE N_cotizacion='%s' and N_referencia_c='%s'",$colname_obs,$colname_ver_packing );
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
$query_refer = sprintf("SELECT valor_impuesto,peso_millar_ref,peso_millar_bols FROM Tbl_referencia WHERE CONVERT(Tbl_referencia.cod_ref, SIGNED INTEGER) ='%s'  ",$colname_refer);
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
                  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nit','','R','N_ancho_p','','RisNum','N_alto_p','','RisNum','N_calibre_p','','RisNum', 'N_cantidad_p','','RisNum','N_comision','','RisNum','vendedor','','RisNum');return document.MM_returnValue">
                    <table id="tabla1">
                      <tr id="tr1">
                        <td width="176" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                        <td colspan="2" nowrap="nowrap" id="titulo2">Cotizacion Packing List</td>
                        <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
                      </tr>
                      <tr>
                        <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
                        <td colspan="2" id="numero2"><strong>NIT N&deg;
                          <input name="Str_nit" type="text"id="Str_nit"  value="<?php echo $row_packing['Str_nit']?>" readonly="readonly" />
                        </strong></td>
                        <td colspan="2" id="fuente2"><?php $tipo=$row_usuario['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?><a href="javascript:eliminar_p('delete_pl',<?php echo $row_packing['N_cotizacion'];?>,'&delete_pl_ref',<?php echo $row_packing['N_referencia_c']; ?>,'&id_refcliente',<?php echo $row_id_refcliente['id_refcliente']; ?>,'&tipo',<?php echo $row_usuario['tipo_usuario']; ?>,'cotizacion_general_packingList_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COTIZACION"
                          title="ELIMINAR COTIZACION" border="0"><?php } ?></a><a href="referencias_p.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" />
                        </td>



                      </tr>    
                      <tr>
                        <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
                        <td colspan="2" id="numero1"><strong>
                          <input name="N_cotizacion" type="hidden" value="<?php $num=$row_packing['N_cotizacion']; echo $num; ?>" />
                          <?php echo $num; ?></strong></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Fecha  Ingreso</td>
                          <td colspan="2" id="fuente1">Hora Ingreso</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1"><input name="fecha_p" type="date" id="fecha_p" value="<?php echo $row_packing['fecha_creacion']; ?>" size="10" /></td>
                          <td colspan="2" id="fuente1"><input name="hora_p" type="text" id="hora_p" value="<?php echo date("g:i a") ?>" size="10" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">Estado de la Cotizaci&oacute;n</td>
                          <td colspan="2" id="fuente1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1"><select name="B_estado" id="B_estado">
                            <option value="0"<?php if (!(strcmp("0", $row_packing['B_estado']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                            <option value="1"<?php if (!(strcmp("1", $row_packing['B_estado']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
                            <option value="2"<?php if (!(strcmp("2", $row_packing['B_estado']))) {echo "selected=\"selected\"";} ?>>Rechazada</option>
                            <option value="3"<?php if (!(strcmp("3", $row_packing['B_estado']))) {echo "selected=\"selected\"";} ?>>Obsoleta</option>
                          </select></td>
                          <td colspan="2" id="dato4">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="4" id="fuente2">Nombre del Cliente</td>
                        </tr>
                        <tr>
                          <td colspan="4" id="fuente1">
                            <select name="clientes" id="clientes"onchange="Javascript:document.form1.Str_nit.value=this.value">
                            <option value=""><?php echo $row_cliente['nombre_c']?></option>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="124" id="cabezamenu"><ul id="menuhorizontal">
                            <li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
                          </ul>
                        </td>
                        <td width="124" id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
                        </ul></td><td colspan="2" id="fuente1">&nbsp;</td>
                      </tr>       
                      <tr id="tr1">
                        <td colspan="7" id="titulo2">PACKING LIST</td>
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
                        <td colspan="2" id="fuente1">Alto  (cms)</td>
                        <td colspan="2" id="fuente1">Calibre (mills)</td>
                      </tr>
                      <tr>
                        <td id="dato1"><input name="N_ancho_p" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_ancho_p" value="<?php echo $row_packing['N_ancho']?>" /></td>
                        <td colspan="2" id="dato1"><input name="N_alto_p" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_alto_p" value="<?php echo $row_packing['N_alto']?>" /></td>
                        <td colspan="2" id="dato1"><input name="N_calibre_p" required="required" type="number" style=" width:70px" min="0" step="0.01" id="N_calibre_p" value="<?php echo $row_packing['N_calibre']?>"/></td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                      </tr>
                      <td colspan="7" id="titulo1">MATERIAL COEXTRUSION</td>
                    </tr>      <tr>
                      <td id="fuente1">Boca de Entrada:</td>
                      <td colspan="2" id="fuente9">&nbsp;</td>
                      <td colspan="2" id="fuente10">&nbsp;</td>
                    </tr>
                    <tr>
                      <td id="fuente6"><select name="Str_boca_entr" id="Str_boca_entr">
                        <option>*</option>
                        <option value="HORIZONTAL"<?php if (!(strcmp("HORIZONTAL", $row_packing['Str_boca_entrada']))) {echo "selected=\"selected\"";} ?>>HORIZONTAL</option>
                        <option value="VERTICAL"<?php if (!(strcmp("VERTICAL", $row_packing['Str_boca_entrada']))) {echo "selected=\"selected\"";} ?>>VERTICAL</option>
                      </select></td>
                      <td colspan="2" id="fuente7">&nbsp;</td>
                      <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>
                    <tr>
                      <td id="fuente1">Ubicacion de la Entrada:</td>
                      <td colspan="2" id="fuente3">&nbsp;</td>
                      <td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>
                    <tr>
                      <td id="fuente1"><select name="Str_entrada_p" id="Str_entrada_p">
                        <option>*</option>
                        <option value="ANVERSO"<?php if (!(strcmp("ANVERSO", $row_packing['Str_ubica_entrada']))) {echo "selected=\"selected\"";} ?>>ANVERSO</option>
                        <option value="REVERSO"<?php if (!(strcmp("REVERSO", $row_packing['Str_ubica_entrada']))) {echo "selected=\"selected\"";} ?>>REVERSO</option>
                      </select></td>
                      <td colspan="2" id="fuente3">Lamina 1 (Adhesivo)</td>
                      <td colspan="2" id="fuente1"><select name="Str_lamina1_p" id="Str_lamina1_p">
                        <option>*</option>
                        <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_packing['Str_lam1']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
                        <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_packing['Str_lam1']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td id="fuente3">&nbsp;</td>
                      <td colspan="2" id="fuente3">Lamina 2</td>
                      <td colspan="2" id="fuente4"><select name="Str_lamina2_p" id="Str_lamina2_p">
                        <option>*</option>
                        <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_packing['Str_lam1']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
                        <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_packing['Str_lam2']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td colspan="7" id="fuente1">&nbsp;</td>
                    </tr>        
                    <td colspan="7" id="titulo1">IMPRESION</td>
                  </tr>
                  <tr>
                    <td colspan="2" id="fuente1">Impresion:          
                      <select name="B_impresion" id="B_impresion" onBlur="mostrarColor(this)">
                        <option value="0"<?php if (!(strcmp("0",$row_packing['B_impresion']))) {echo "selected=\"selected\"";} ?>>NO</option>
                        <option value="1"<?php if (!(strcmp("1", $row_packing['B_impresion']))) {echo "selected=\"selected\"";} ?>>SI</option>
                      </select>
                      <select name="N_colores_impresion" id="N_colores_impresion">
                        <option value="0"<?php if (!(strcmp("0", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>0</option> 
                        <option value="1"<?php if (!(strcmp("1", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>1 color</option>
                        <option value="2"<?php if (!(strcmp("2", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>2 color</option>
                        <option value="3"<?php if (!(strcmp("3", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>3 color</option>
                        <option value="4"<?php if (!(strcmp("4", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>4 color</option>
                        <option value="5"<?php if (!(strcmp("5", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>5 color</option>
                        <option value="6"<?php if (!(strcmp("6", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>6 color</option>
                        <option value="7"<?php if (!(strcmp("7", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>7 color</option>
                        <option value="8"<?php if (!(strcmp("8", $row_packing['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>8 color</option>
                      </select></td>
                      <td id="fuente3">Se Facturan Artes y Planchas ?</td>
                      <td colspan="2" id="fuente1"><select name="B_cyreles" id="B_cyreles">
                        <option value=""<?php if (!(strcmp("", $row_packing['B_cyreles']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                        <option value="1"<?php if (!(strcmp("1", $row_packing['B_cyreles']))) {echo "selected=\"selected\"";} ?>>SI</option>
                        <option value="0"<?php if (!(strcmp("0",$row_packing['B_cyreles']))) {echo "selected=\"selected\"";} ?>>NO</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td colspan="7" id="fuente1">&nbsp;</td>
                    </tr>
                    <tr id="tr1">
                      <td colspan="7" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
                    </tr>
                    <tr>
                      <td colspan="7">
                        <br>
                        <span style="color: red;" >IMPUESTO PLASTICO</span> <input type="checkbox" name="impuesto" id="impuesto"   value="1"<?php if (!(strcmp($row_packing['impuesto'],1))) {echo "checked=\"checked\"";} ?>> <label for="impuesto"> &nbsp;&nbsp;<!-- Adjunto PDF: <input name="pdf_impuesto" type="file" size="20" maxlength="60"class="botones_file"> -->
                        <?php if($row_cliente['pdf_impuesto']): ?>
                          Adjunto PDF:   
                                        <a href="javascript:verFoto('archivosc/impuesto/<?php echo $row_cliente['pdf_impuesto']; ?>','610','490')"> 
                                          <?php if($row_cliente['pdf_impuesto']!='') echo "Impuesto"; ?><em> (Este pdf se adjunta en Cliente) </em>
                                        </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                   <tr>
                    <td id="fuente1">Moneda</td>
                    <td id="fuente1">Precio Anterior</td>
                    <td id="fuente1">Impuesto</td>
                    <td id="fuente1">Precio Impuesto</td>
                      <td colspan="2" id="fuente1">Plazo de pago</td>
                      <td width="223" id="fuente1">Cantidad Solicitada </td>
                    </tr>
                    <tr>
                      <td id="fuente1"><select name="Str_moneda_p" id="Str_moneda_p">
                        <option value="COL$"<?php if (!(strcmp("COL$", $row_packing['Str_moneda']))) {echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="USD$"<?php if (!(strcmp("USD$", $row_packing['Str_moneda']))) {echo "selected=\"selected\"";} ?>>USD$</option>
                        <option value="EUR&euro;"<?php if (!(strcmp("EUR&euro;", $row_packing['Str_moneda']))) {echo "selected=\"selected\"";} ?>>EUR&euro;</option>
                      </select>

                    </td>
                    <td id="fuente1">
                      <input name="N_precio_p" type="text" style="width: 100px" min="0" step="0.01" id="N_precio_p" value="<?php echo $row_packing['N_precio_vnta']?>"/>
                      
                    </td>
                    <td id="fuente5">
                         <input name="valor_impuesto" type="text" style="width:80px" min="0" step="0.01" id="valor_impuesto" value="<?php echo $row_packing['valor_impuesto']=='0'?$row_refer['valor_impuesto'] : $row_packing['valor_impuesto'] ;?>"/> 
                       </td>
                    <td id="fuente1">
                      <input name="N_precio_old" type="text" style="width:100px" min="0" step="0.01" id="N_precio_old" value="<?php echo $row_packing['N_precio_old']; ?>"/>
                    </td>
                      <td colspan="2" id="dato1"><select name="Str_plazo" id="Str_plazo">
                        <option>*</option>
                        <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                        <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                        <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias</option>
                        <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias</option>
                        <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias</option>
                        <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias</option>
                        <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_packing['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias</option>
                      </select></td>
                      <td id="fuente1"><input name="N_cantidad_p" type="number" style=" width:100px" min="0" step="0.01" id="N_cantidad_p" value="<?php echo $row_packing['N_cantidad']?>" /><!-- onKeyUp="puntos(this,this.value.charAt(this.value.length-1))"--></td>
                    </tr>
                    <tr>
                      <td id="fuente1">Incoterms: </td>
                      <td colspan="2" id="fuente1">Vendedor</td>
                      <td colspan="2" id="fuente1">Comision</td>
                    </tr>
                    <tr>
                      <td id="fuente1"><select name="Str_incoterms_p" id="Str_incoterms_p">
                        <option >*</option>
                        <option value="EXW"<?php if (!(strcmp("EXW", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP", $row_packing['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a></td>
                      <td colspan="2" id="fuente1"><select name="vendedor" id="vendedor" required>
                        <option value="" <?php if (!(strcmp("", $row_packing['Str_usuario']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                        <?php
                        do {  
                          ?>
                          <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_packing['Str_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
                          <?php
                        } while ($row_vendedores = mysql_fetch_assoc($vendedores));
                        $rows = mysql_num_rows($vendedores);
                        if($rows > 0) {
                          mysql_data_seek($vendedores, 0);
                          $row_vendedores = mysql_fetch_assoc($vendedores);
                        }
                        ?>
                      </select></td>
                      <td colspan="2" id="fuente1"><input name="N_comision" type="number" style="width:60px" step="0.1" id="N_comision" maxlength="1" value="<?php echo $row_packing['N_comision']?>"/>
                        <strong>%</strong></td>
                      </tr>
                      <tr>
                        <td colspan="7" id="fuente1">&nbsp;</td>
                        <tr>
                          <td colspan="7" id="fuente1"> Observaciones:</td>
                        </tr>
                        <tr>
                          <td colspan="7" id="dato1"><textarea name="nota_p" cols="78" rows="2" id="nota_p"onKeyUp="conMayusculas(this)"><?php echo $row_obs['texto'] ?></textarea></td>
                        </tr>
                        <tr>
                          <td colspan="7" id="dato1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="7" id="fuente2">
                            <input name="peso_millar_ref" type="hidden" id="peso_millar_ref" value="<?php echo $row_refer['peso_millar_ref']=='' ? 0 : $row_refer['peso_millar_ref'];?>"/>
                            <input name="peso_millar_bols" type="hidden" id="peso_millar_bols" value="<?php echo $row_refer['peso_millar_bols']=='' ? 0 : $row_refer['peso_millar_bols'];?>"/>
                            <input type="hidden" name="Str_tipo" id="Str_tipo" value="PACKING LIST" />
                            <input name="responsable_modificacion" type="hidden" value="" />
                            <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d");?>" />
                            <input name="hora_modificacion" type="hidden" value="" />
                            <input name="N_referencia" type="hidden" value="<?php echo $_GET['cod_ref']?>" />
                            <input name="B_generica" type="hidden" value="<?php echo $row_packing['B_generica']?>" />
                            <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
                            <input name="valor" type="hidden" value="2" />
                            <input name="submit" class="botonGeneral" type="submit"value="EDITAR COTIZACION PACKING LIST" /></td>
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
           sumaImpuestoPacking($("#N_precio_p").val(),$("#valor_impuesto").val());
    
    });

   $('#valor_impuesto').on('change', function() { 
          sumaImpuestoPacking($("#N_precio_p").val(),$("#valor_impuesto").val());
 
   });


   $('#N_precio_p').on('change', function() { 
          sumaImpuestoPacking($("#N_precio_p").val(),$("#valor_impuesto").val());
   
   });
</script>
<?php
mysql_free_result($usuario);
mysql_free_result($cliente);
mysql_free_result($vendedores);
mysql_free_result($cotizacion);
?>
