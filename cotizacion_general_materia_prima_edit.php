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

include('rud_cotizaciones/rud_cotizacion_materia_p.php');//SISTEMA RUW PRA LA BASE DE DATOS 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
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
$colname_ver_materia = "-1";
if (isset($_GET['cod_ref'])) 
{
  $colname_ver_materia = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
$colname_ver_materia2 = "-1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_materia2 = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_materia = sprintf("SELECT * FROM Tbl_cotiza_materia_p WHERE N_cotizacion='%s'  and N_referencia_c='%s'",$colname_ver_materia2,$colname_ver_materia);
$materia = mysql_query($query_materia, $conexion1) or die(mysql_error());
$row_materia = mysql_fetch_assoc($materia);
$totalRows_materia = mysql_num_rows($materia);

mysql_select_db($database_conexion1, $conexion1);
$query_verlinc = sprintf("SELECT * FROM Tbl_mp_vta ORDER BY Str_nombre ASC");
$verlinc = mysql_query($query_verlinc, $conexion1) or die(mysql_error());
$row_verlinc = mysql_fetch_assoc($verlinc);
$totalRows_verlinc = mysql_num_rows($verlinc);
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
$query_obs = sprintf("SELECT * FROM Tbl_cotiza_materia_p_obs WHERE N_cotizacion='%s' and N_referencia_c='%s'",$colname_obs,$colname_ver_materia);
$obs = mysql_query($query_obs, $conexion1) or die(mysql_error());
$row_obs = mysql_fetch_assoc($obs);
$totalRows_obs = mysql_num_rows($obs);
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
<body>
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
                  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nit','','R','N_precio_vnta_m','','RisNum','N_cantidad_m','','RisNum','N_comision','','RisNum','vendedor','','RisNum');return document.MM_returnValue"><table id="tabla1">
                    <tr id="tr1">
                      <td width="179" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                      <td width="225" nowrap="nowrap" id="titulo2">Cotizacion Materia Prima</td>
                      <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
                    </tr>
                    <tr>
                      <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
                      <td id="numero2"><strong>NIT N&deg;
                        <input name="Str_nit" type="text"id="Str_nit"  value="<?php echo $row_materia['Str_nit']?>" readonly="readonly" />
                      </strong></td>
                      <td colspan="2" id="fuente2"><?php $tipo=$row_usuario['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?><a href="javascript:eliminar_m('delete_mp',<?php echo $row_materia['N_cotizacion'];?>,'&delete_mp_ref',<?php echo $row_materia['N_referencia_c']; ?>,'&tipo',<?php echo $row_usuario['tipo_usuario']; ?>,'cotizacion_general_materia_prima_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR COTIZACION"
                        title="ELIMINAR COTIZACION" border="0"><?php } ?></a>
                        <a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
                      </tr>      
                      <tr>
                        <td id="titulo2">COTIZACION N&deg;</td>
                        <td colspan="2" id="numero1"><strong>
                          <input name="N_cotizacion" type="hidden" value="<?php $num=$row_materia['N_cotizacion']; echo $num; ?>" />
                          <?php echo $num; ?>
                        </strong></td>
                      </tr>
                      <tr>
                        <td id="fuente1">Fecha  Ingreso</td>
                        <td colspan="2" id="fuente1">Hora Ingreso</td>
                      </tr>
                      <tr>
                        <td id="fuente1"><input name="fecha_m" type="date" min="2000-01-02" value="<?php echo $row_materia['fecha_creacion']; ?>" size="10" required="required"/>
                          <td colspan="2" id="fuente1"><input name="hora_m" type="text" id="hora_m" value="<?php echo date("g:i a") ?>" size="10" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td id="fuente1">Estado de la Cotizaci&oacute;n</td>
                          <td colspan="2" id="fuente1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td id="fuente1"><select name="B_estado" id="B_estado">
                            <option value="0"<?php if (!(strcmp("0", $row_materia['B_estado']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                            <option value="1"<?php if (!(strcmp("1", $row_materia['B_estado']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
                            <option value="2"<?php if (!(strcmp("2", $row_materia['B_estado']))) {echo "selected=\"selected\"";} ?>>Rechazada</option>
                            <option value="3"<?php if (!(strcmp("3", $row_materia['B_estado']))) {echo "selected=\"selected\"";} ?>>Obsoleta</option>
                          </select></td>
                          <td colspan="2" id="dato4">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente2">Nombre del Cliente</td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente1"><select name="clientes" id="clientes"onchange="Javascript:document.form1.Str_nit.value=this.value">
                            <option value=""><?php $cad=htmlentities($row_cliente['nombre_c']); echo $cad;?></option>
                          </select></td>
                        </tr>
                        <tr>
                          <td id="cabezamenu"><ul id="menuhorizontal">
                            <li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
                          </ul>
                        </td>
                        <td id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
                        </ul></td><td colspan="2" id="fuente1">&nbsp;</td>
                      </tr>      
                      <tr id="tr1">
                        <td colspan="4" id="titulo2">MATERIA PRIMA</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="5" id="titulo1">CARACTERISTICAS PRINCIPALES</td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="fuente1">Referencia:</td>
                        <td id="fuente1">&nbsp;</td>
                        <td colspan="2" id="fuente1">Ver Archivo Adjunto</td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1"><select name="Str_referencia_m" id="Str_referencia_m">
                          <option value="" <?php if (!(strcmp("", $row_materia['Str_referencia']))) {echo "selected=\"selected\"";} ?>></option>
                          <?php
                          do {  
                            ?><option value="<?php echo $row_verlinc['id_mp_vta'];?>"<?php if (!(strcmp($row_verlinc['id_mp_vta'], $row_materia['Str_referencia']))) {echo "selected=\"selected\"";} ?>><?php echo $row_verlinc['Str_nombre']?></option>
                            <?php
                          } while ($row_verlinc = mysql_fetch_assoc($verlinc));
                          $rows = mysql_num_rows($verlinc);
                          if($rows > 0) {
                            mysql_data_seek($verlinc, 0);
                            $row_verlinc = mysql_fetch_assoc($verlinc);
                          }
                          ?>
                        </select></td>
                        <td colspan="2" id="fuente1"><?php $idmp=$row_materia['Str_referencia'];
                        if($idmp!=''){ 
                          $sql_select="SELECT Str_linc_archivo FROM Tbl_mp_vta WHERE id_mp_vta='$idmp'";
                          $result_select= mysql_query($sql_select);
                          $num_select= mysql_num_rows($result_select);
                          if($num_select>='1') { 
                           $nombre_link=mysql_result($result_select,0,'Str_linc_archivo'); }
                           ?>
                           <a href="javascript:verFoto('archivosc/archivos_pdf_mp/<?php echo $nombre_link ?>','610','490')" target="_blank"><?php echo $nombre_link ?></a>
                         <?php }else  echo "<span class='rojo'>No tiene archivos adjuntos</span>";  ?>
                         <input type="hidden" name="Str_linc" id="Str_linc" value="<?php echo $nombre_link; ?>"/></td>
                       </tr>
                       <tr>
                        <td id="fuente4">&nbsp;</td>
                        <td id="fuente4">&nbsp;</td>
                        <td colspan="2" id="fuente4">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="5" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
                        <tr>
                          <td  id="fuente1"> Moneda / Precio</td>
                          <td id="fuente1">Unidades</td>
                          <td width="134" id="fuente1">Plazo de pago</td>
                          <td width="142" id="fuente1">Cantidad Unidades</td>
                        </tr>
                        <tr>
                          <td  id="fuente1"><select name="Str_moneda_m" id="Str_moneda_m">
                            <option value="COL$"<?php if (!(strcmp("COL$", $row_materia['Str_moneda']))) {echo "selected=\"selected\"";} ?>>COL$</option>
                            <option value="USD$"<?php if (!(strcmp("USD$", $row_materia['Str_moneda']))) {echo "selected=\"selected\"";} ?>>USD$</option>
                            <option value="EUR&euro;"<?php if (!(strcmp("EUR&euro;", $row_materia['Str_moneda']))) {echo "selected=\"selected\"";} ?>>EUR&euro;</option>
                          </select>
                          <input name="N_precio_vnta_m" type="number" id="N_precio_vnta_m" step="0.01" style="width:60px" value="<?php echo $row_materia['N_precio_vnta']?>"/></td>
                          <td id="dato1"><select name="Str_unidad_vta" id="Str_unidad_vta">
                            <option>*</option>
                            <option value="PRECIO UNITARIO"<?php if (!(strcmp("PRECIO UNITARIO", $row_materia['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO UNITARIO</option>
                            <option value="PRECIO KILOS"<?php if (!(strcmp("PRECIO KILOS", $row_materia['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO KILOS</option>
                            <option value="PRECIO METROS"<?php if (!(strcmp("PRECIO METROS", $row_materia['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>PRECIO METROS</option>
                          </select></td>
                          <td id="fuente1"><select name="Str_plazo" id="Str_plazo">
                            <option>*</option>
                            <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                            <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                            <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias</option>
                            <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias</option>
                            <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias</option>
                            <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias</option>
                            <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_materia['Str_plazo']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias</option>
                          </select></td>
                          <td id="fuente1"><input name="N_cantidad_m" type="number" id="N_cantidad_m" value="<?php echo $row_materia['N_cantidad']?>" style="width:70px" step="0.01" /><!--onKeyUp="puntos(this,this.value.charAt(this.value.length-1))"--></td>
                        </tr>
                        <tr>
                          <td id="fuente1">Incoterms: </td>
                          <td id="fuente3">Vendedor</td>
                          <td id="fuente1">&nbsp;</td>
                          <td id="fuente1">Comision</td>
                        </tr>
                        <tr>
                          <td id="fuente1"> 
                            <select name="Str_incoterms_m" id="Str_incoterms_m">
                              <option >*</option>
                              <option value="EXW"<?php if (!(strcmp("EXW", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>EXW</option>
                              <option value="FCA"<?php if (!(strcmp("FCA", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FCA</option>
                              <option value="FAS"<?php if (!(strcmp("FAS", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FAS</option>
                              <option value="FOB"<?php if (!(strcmp("FOB", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>FOB</option>
                              <option value="CFR"<?php if (!(strcmp("CFR", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CFR</option>
                              <option value="CIF"<?php if (!(strcmp("CIF", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIF</option>
                              <option value="CPT"<?php if (!(strcmp("CPT", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CPT</option>
                              <option value="CIP"<?php if (!(strcmp("CIP", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>CIP</option>
                              <option value="DAF"<?php if (!(strcmp("DAF", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DAF</option>
                              <option value="DES"<?php if (!(strcmp("DES", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DES</option>
                              <option value="DEQ"<?php if (!(strcmp("DEQ", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DEQ</option>
                              <option value="DDU"<?php if (!(strcmp("DDU", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDU</option>
                              <option value="DDP"<?php if (!(strcmp("DDP", $row_materia['Str_incoterms']))) {echo "selected=\"selected\"";} ?>>DDP</option>
                            </select><a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a></td>
                            <td id="fuente3"><select name="vendedor" id="vendedor">
                              <option value="" <?php if (!(strcmp("", $row_materia['Str_usuario']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                              <?php
                              do {  
                                ?>
                                <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_materia['Str_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
                                <?php
                              } while ($row_vendedores = mysql_fetch_assoc($vendedores));
                              $rows = mysql_num_rows($vendedores);
                              if($rows > 0) {
                                mysql_data_seek($vendedores, 0);
                                $row_vendedores = mysql_fetch_assoc($vendedores);
                              }
                              ?>
                            </select></td>
                            <td id="fuente1">&nbsp;</td>
                            <td id="fuente1"><input name="N_comision" type="number" style=" width:60px" step="0.1" id="N_comision" min="0" max="9" value="<?php echo $row_materia['N_comision']?>"/>
                              <strong>%</strong></td>
                              <tr>
                                <td id="fuente1">&nbsp;</td>
                                <td id="fuente1">&nbsp;</td>
                                <td colspan="2" id="fuente1">&nbsp;</td>
                                <tr>
                                  <td colspan="4" id="fuente1"> Observaciones:</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1"><textarea name="nota_m" cols="78" rows="2" id="nota_m"onKeyUp="conMayusculas(this)"><?php echo $row_obs['texto'] ?></textarea></td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="fuente2"><input type="hidden" name="Str_tipo" id="Str_tipo" value="MATERIA PRIMA" />
                                    <input name="responsable_modificacion" type="hidden" value="" />
                                    <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d");?>" />
                                    <input name="B_generica" type="hidden" value="<?php echo $row_materia['B_generica']?>" />
                                    <input name="hora_modificacion" type="hidden" value="" />
                                    <input name="N_referencia" type="hidden" value="<?php echo $_GET['cod_ref']?>" />
                                    <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
                                    <input name="valor" type="hidden" value="2" />
                                    <input name="submit" class="botonGeneral" type="submit"value="EDITAR COTIZACION MATERIA PRIMA" /></td>
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
    <?php
    mysql_free_result($usuario);
    mysql_free_result($cliente);
    mysql_free_result($vendedores);
    mysql_free_result($materia);
    ?>
