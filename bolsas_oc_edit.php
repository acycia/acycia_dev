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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orden_compra_bolsas SET id_p_ocb=%s, id_bolsa_ocb=%s, id_ref_ocb=%s, fecha_pedido_ocb=%s, fecha_entrega_ocb=%s, condiciones_pago_ocb=%s, cantidad_ocb=%s, valor_unitario_ocb=%s, valor_neto_ocb=%s, valor_iva_ocb=%s, valor_total_ocb=%s, pedido_ocb=%s, muestra_ocb=%s, paquetes_ocb=%s, cajas_ocb=%s, ancho_ocb=%s, largo_ocb=%s, solapa_ocb=%s, fuelle_ocb=%s, calibre_ocb=%s, calibre_micras_ocb=%s, anexa_arte_ocb=%s, anexa_arte_impreso_ocb=%s, rodillo_ocb=%s, negativo_ocb=%s, cirel_ocb=%s, observaciones_ocb=%s, registrado_ocb=%s, aprobado_ocb=%s, saldo_verificacion_ocb=%s WHERE n_ocb=%s",
   GetSQLValueString($_POST['id_p_ocb'], "int"),
   GetSQLValueString($_POST['id_bolsa_ocb'], "int"),
   GetSQLValueString($_POST['id_ref_ocb'], "int"),
   GetSQLValueString($_POST['fecha_pedido_ocb'], "date"),
   GetSQLValueString($_POST['fecha_entrega_ocb'], "date"),
   GetSQLValueString($_POST['condiciones_pago_ocb'], "text"),
   GetSQLValueString($_POST['cantidad_ocb'], "int"),
   GetSQLValueString($_POST['valor_unitario_ocb'], "double"),
   GetSQLValueString($_POST['valor_neto_ocb'], "double"),
   GetSQLValueString($_POST['valor_iva_ocb'], "double"),
   GetSQLValueString($_POST['valor_total_ocb'], "double"),
   GetSQLValueString($_POST['pedido_ocb'], "int"),
   GetSQLValueString($_POST['muestra_ocb'], "int"),
   GetSQLValueString($_POST['paquetes_ocb'], "int"),
   GetSQLValueString($_POST['cajas_ocb'], "int"),
   GetSQLValueString($_POST['ancho_ocb'], "double"),
   GetSQLValueString($_POST['largo_ocb'], "double"),
   GetSQLValueString($_POST['solapa_ocb'], "double"),
   GetSQLValueString($_POST['fuelle_ocb'], "double"),
   GetSQLValueString($_POST['calibre_ocb'], "double"),
   GetSQLValueString($_POST['calibre_micras_ocb'], "double"),
   GetSQLValueString(isset($_POST['anexa_arte_ocb']) ? "true" : "", "defined","1","0"),
   GetSQLValueString(isset($_POST['anexa_arte_impreso_ocb']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['rodillo_ocb'], "double"),
   GetSQLValueString(isset($_POST['negativo_ocb']) ? "true" : "", "defined","1","0"),
   GetSQLValueString(isset($_POST['cirel_ocb']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['observaciones_ocb'], "text"),
   GetSQLValueString($_POST['registrado_ocb'], "text"),
   GetSQLValueString($_POST['aprobado_ocb'], "text"),
   GetSQLValueString($_POST['cantidad_ocb'], "int"),
   GetSQLValueString($_POST['n_ocb'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "bolsas_oc_vista.php?n_ocb=" . $_POST['n_ocb'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_bolsa_oc = "-1";
if (isset($_GET['n_ocb'])) {
  $colname_bolsa_oc = (get_magic_quotes_gpc()) ? $_GET['n_ocb'] : addslashes($_GET['n_ocb']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_oc = sprintf("SELECT * FROM orden_compra_bolsas WHERE n_ocb = %s", $colname_bolsa_oc);
$bolsa_oc = mysql_query($query_bolsa_oc, $conexion1) or die(mysql_error());
$row_bolsa_oc = mysql_fetch_assoc($bolsa_oc);
$totalRows_bolsa_oc = mysql_num_rows($bolsa_oc);
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
  <div align="center">
    <table align="center" id="tabla">
      <tr align="center"><td>
        <div> 
          <b class="spiffy"> 
            <b class="spiffy1"><b></b></b>
            <b class="spiffy2"><b></b></b>
            <b class="spiffy3"></b>
            <b class="spiffy4"></b>
            <b class="spiffy5"></b></b>
            <div class="spiffy_content">
              <table id="tabla1"><tr>
                <td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
                <tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
                  <td id="cabezamenu"><ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="compras.php">GESTION COMPRAS</a></li>
                  </ul>
                </td>
              </tr>  
              <tr>
                <td colspan="2" align="center" id="linea1">
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_pedido_ocb','','R','fecha_entrega_ocb','','R','condiciones_pago_ocb','','R','cantidad_ocb','','R','valor_unitario_ocb','','R','valor_neto_ocb','','R','valor_iva_ocb','','R','valor_total_ocb','','R','ancho_ocb','','R','largo_ocb','','R','solapa_ocb','','R','paquetes_ocb','','R','cajas_ocb','','R','calibre_micras_ocb','','R','calibre_ocb','','R','rodillo_ocb','','R','registrado_ocb','','R','aprobado_ocb','','R');return document.MM_returnValue">
                    <table id="tabla2">
                      <tr id="tr1">            
                        <td id="codigo" width="25%">CODIGO : A3 - F01 </td>
                        <td colspan="2" id="titulo2" width="50%">ORDEN DE COMPRA </td>
                        <td id="codigo" width="25%">VERSION : 1 </td>
                      </tr>
                      <tr>
                        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
                        <td colspan="2" id="subtitulo">PRODUCTO TERMINADO (BOLSA)</td>
                        <td id="dato2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" id="numero2"><strong>N. <?php echo $row_bolsa_oc['n_ocb']; ?></strong></td>
                        <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="bolsas_oc_vista.php?n_ocb=<?php echo $row_bolsa_oc['n_ocb']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('n_ocb',<?php echo $row_bolsa_oc['n_ocb']; ?>,'bolsas_oc_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="bolsas_oc.php"><img src="images/o.gif" alt="O.C. BOLSAS" border="0" style="cursor:hand;"/></a><a href="bolsas.php"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"/></a></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="dato2"><a href="bolsas_oc_edit1.php?n_ocb=<?php echo $row_bolsa_oc['n_ocb']; ?>">Cambiar datos principales</a></td>
                        <td id="dato2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="fuente2">FECHA DE PEDIDO </td>
                        <td id="fuente2">FECHA DE ENTREGA </td>
                        <td id="fuente2">CONDICIONES DE PAGO </td>
                      </tr>
                      <tr>
                        <td id="dato2"><input type="text" name="fecha_pedido_ocb" value="<?php echo $row_bolsa_oc['fecha_pedido_ocb']; ?>" size="10"></td>
                        <td id="dato2"><input type="text" name="fecha_entrega_ocb" value="<?php echo $row_bolsa_oc['fecha_entrega_ocb']; ?>" size="10"></td>
                        <td id="dato2"><input type="text" name="condiciones_pago_ocb" value="<?php echo $row_bolsa_oc['condiciones_pago_ocb']; ?>" size="20"></td>
                      </tr>
                      <tr>
                        <td colspan="4" id="subtitulo2">DATOS DEL PROVEEDOR </td>
                      </tr>
                      <tr id="tr1">
                        <td colspan="2" id="fuente1">PROVEEDOR</td>
                        <td id="fuente1">NIT</td>
                        <td id="fuente1">TIPO DE PROVEEDOR </td>
                      </tr>
                      <tr>
                        <td colspan="2" id="dato1"><input name="id_p_ocb" type="hidden" value="<?php echo $row_bolsa_oc['id_p_ocb']; ?>"><?php $proveedor=$row_bolsa_oc['id_p_ocb'];
                        if($proveedor!='')
                        {
                         $sqlp="SELECT * FROM proveedor WHERE id_p ='$proveedor'";
                         $resultp= mysql_query($sqlp);
                         $nump= mysql_num_rows($resultp);
                         if($nump >='1')
                         { 
                           $nombre = mysql_result($resultp,0,'proveedor_p');
                           $nit_p = mysql_result($resultp,0,'nit_p');
                           $tipo_p = mysql_result($resultp,0,'tipo_p');
                           $direccion_p = mysql_result($resultp,0,'direccion_p');
                           $pais_p = mysql_result($resultp,0,'pais_p');
                           $ciudad_p = mysql_result($resultp,0,'ciudad_p');
                           $telefono_p = mysql_result($resultp,0,'telefono_p');
                           $fax_p = mysql_result($resultp,0,'fax_p');
                           $contacto_p = mysql_result($resultp,0,'contacto_p');
                           echo $nombre;
                         } } ?></td>
                         <td id="dato1"><?php echo $nit_p; ?></td>
                         <td id="dato1"><?php if($tipo_p != '') {
                           $sqltipo="SELECT * FROM tipo WHERE id_tipo ='$tipo_p'";
                           $resultipo= mysql_query($sqltipo);
                           $numtipo= mysql_num_rows($resultipo);
                           if($numtipo >='1') { $nombre = mysql_result($resultipo,0,'nombre_tipo');	
                           echo $nombre; } } ?></td>
                         </tr>
                         <tr id="tr1">
                          <td height="22" colspan="2" id="fuente1">DIRECCION COMERCIAL </td>
                          <td id="fuente1">PAIS</td>
                          <td id="fuente1">CIUDAD</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="dato1"><?php echo $direccion_p; ?></td>
                          <td id="dato1"><?php echo $ciudad_p; ?></td>
                          <td id="dato1"><?php echo $pais_p; ?></td>
                        </tr>
                        <tr id="tr1">
                          <td colspan="2" id="fuente1">CONTACTO COMERCIAL </td>
                          <td id="fuente1">TELEFONO</td>
                          <td id="fuente1">FAX</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="dato1"><?php echo $contacto_p; ?></td>
                          <td id="dato1"><?php echo $telefono_p; ?></td>
                          <td id="dato1"><?php echo $fax_p; ?></td>
                        </tr>

                        <tr>
                          <td colspan="4" id="subtitulo2">PRODUCTO TERMINADO SOLICITADO </td>
                        </tr>
                        <tr id="tr1">
                          <td height="18" colspan="2" id="fuente1">NOMBRE DE LA BOLSA</td>
                          <td id="fuente1">CODIGO DE LA BOLSA </td>
                          <td id="fuente1">UNIDAD DE MEDIDA </td>
                        </tr>
                        <tr>
                          <td colspan="2" id="dato1"><input name="id_bolsa_ocb" type="hidden" value="<?php echo $row_bolsa_oc['id_bolsa_ocb']; ?>"><?php $id_bolsa=$row_bolsa_oc['id_bolsa_ocb'];
                          if($id_bolsa!='') {
                           $sqlbolsa="SELECT * FROM material_terminado_bolsas WHERE id_bolsa ='$id_bolsa'";
                           $resultbolsa= mysql_query($sqlbolsa);
                           $numbolsa= mysql_num_rows($resultbolsa);
                           if($numbolsa >='1')	{ 
                            $nombre = mysql_result($resultbolsa,0,'nombre_bolsa');
                            $codigo = mysql_result($resultbolsa,0,'codigo_bolsa');					
                            $medida = mysql_result($resultbolsa,0,'id_medida_bolsa');					
                            echo $nombre; } } ?></td>
                            <td id="dato1"><?php echo $codigo; ?></td>
                            <td id="dato1"><?php if($medida!='') { 
                              $sqlm="SELECT * FROM medida WHERE id_medida ='$medida'";
                              $resultm= mysql_query($sqlm);
                              $numedida= mysql_num_rows($resultm);
                              if($numedida >='1') { $nombre_medida = mysql_result($resultm,0,'nombre_medida');
                              echo $nombre_medida; } } ?></td>
                            </tr>
                            <tr id="tr1">
                              <td id="fuente1">CANTIDAD</td>
                              <td id="fuente1">VALOR UNITARIO</td>
                              <td id="fuente1">VALOR NETO</td>
                              <td id="fuente1">VALOR IVA</td>
                            </tr>
                            <tr>
                              <td id="dato1"><input type="text" name="cantidad_ocb" value="<?php echo $row_bolsa_oc['cantidad_ocb']; ?>" size="20" onBlur="ocb_total()"></td>
                              <td id="dato1"><input type="text" name="valor_unitario_ocb" value="<?php echo $row_bolsa_oc['valor_unitario_ocb']; ?>" size="20" onBlur="ocb_total()"></td>
                              <td id="dato1"><input type="text" name="valor_neto_ocb" value="<?php echo $row_bolsa_oc['valor_neto_ocb']; ?>" size="20" onBlur="ocb_total()"></td>
                              <td id="dato1"><input type="text" name="valor_iva_ocb" value="<?php echo $row_bolsa_oc['valor_iva_ocb']; ?>" size="20"></td>
                            </tr>
                            <tr>
                              <td colspan="3" rowspan="2" id="dato1">Según el numeral 3.6 de Gestión de Compras. Se tiene establecido realizar orden de compra para compras mayores o iguales a 100.000 pesos. Pero para la compra de bolsas terminadas, no hay restriccion y en todos los casos se debe elaborar la orden de compra.</td>
                              <td id="fuente1"><strong>VALOR TOTAL</strong></td>
                            </tr>
                            <tr>
                              <td id="dato1"><input type="text" name="valor_total_ocb" value="<?php echo $row_bolsa_oc['valor_total_ocb']; ?>" size="20"></td>
                            </tr>
                            <tr>
                              <td colspan="4" id="subtitulo2">ESPECIFICACIONES TECNICAS DEL PRODUCTO TERMINADO </td>
                            </tr>
                            <tr id="tr1">
                              <td id="fuente1">PEDIDO</td>
                              <td id="fuente1">REF. DEL PRODUCTO </td>
                              <td id="fuente1">ANCHO</td>
                              <td id="fuente1">LARGO</td>
                            </tr>
                            <tr>
                              <td id="dato1"><input name="pedido_ocb" type="hidden" value="<?php echo $row_bolsa_oc['pedido_ocb']; ?>"><?php $pedido=$row_bolsa_oc['pedido_ocb']; if($pedido=='0') { echo "Nuevo"; } if($pedido=='1') { echo "Reimpresion"; } ?></td>
                              <td id="dato1"><input name="id_ref_ocb" type="hidden" value="<?php echo $row_bolsa_oc['id_ref_ocb']; ?>"><?php $id_ref=$row_bolsa_oc['id_ref_ocb']; if($id_ref!='') { 
                                $sqlr="SELECT * FROM Tbl_referencia WHERE id_ref ='$id_ref'";
                                $resultr= mysql_query($sqlr);
                                $numr= mysql_num_rows($resultr);
                                if($numr >='1') 
                                { 
                                 $cod_ref = mysql_result($resultr,0,'cod_ref');
                                 $version_ref = mysql_result($resultr,0,'version_ref');	
                                 $calibre_ref = mysql_result($resultr,0,'calibre_ref');
                                 $n_egp_ref = mysql_result($resultr,0,'n_egp_ref');
                                 $ancho_ref = mysql_result($resultr,0,'ancho_ref');
                                 $largo_ref = mysql_result($resultr,0,'largo_ref');
                                 $solapa_ref = mysql_result($resultr,0,'solapa_ref');
                                 echo $cod_ref; ?> - <?php echo $version_ref; } } ?></td>
                                 <td><input type="text" name="ancho_ocb" value="<?php if($row_bolsa_oc['ancho_ocb']=='') { echo $ancho_ref; } else { echo $row_bolsa_oc['ancho_ocb']; } ?>" size="10"></td>
                                 <td><input type="text" name="largo_ocb" value="<?php if($row_bolsa_oc['largo_ocb']=='') { echo $largo_ref; } else { echo $row_bolsa_oc['largo_ocb']; } ?>" size="10"></td>
                               </tr>
                               <tr id="tr1">
                                <td colspan="2" id="fuente1"><input <?php if (!(strcmp($row_bolsa_oc['muestra_ocb'],1))) {echo "checked=\"checked\"";} ?> name="muestra_ocb" type="checkbox" value="1">
                                Se entrega Muestra </td>
                                <td id="fuente1">SOLAPA</td>
                                <td id="fuente1">FUELLE / FONDO </td>
                              </tr>
                              <tr>
                                <td colspan="2" id="subtitulo1">Formas de Empaque</td>
                                <td id="dato1"><input type="text" name="solapa_ocb" value="<?php if($row_bolsa_oc['solapa_ocb']=='') { echo $solapa_ref; } else { echo $row_bolsa_oc['solapa_ocb']; } ?>" size="10"></td>
                                <td id="fuente1"><input type="text" name="fuelle_ocb" value="<?php echo $row_bolsa_oc['fuelle_ocb']; ?>" size="10"></td>
                              </tr>
                              <tr id="tr1">
                                <td id="fuente1">Paquetes</td>
                                <td id="fuente1">Cajas</td>
                                <td id="fuente1">CALIBRE (micras)</td>
                                <td id="fuente1">CALIBRE (milesimas)</td>
                              </tr>
                              <tr>
                                <td id="dato1"><input type="text" name="paquetes_ocb" value="<?php echo $row_bolsa_oc['paquetes_ocb']; ?>" size="10"></td>
                                <td id="dato1"><input type="text" name="cajas_ocb" value="<?php echo $row_bolsa_oc['cajas_ocb']; ?>" size="10"></td>
                                <td id="dato1"><input type="text" name="calibre_micras_ocb" value="<?php if($row_bolsa_oc['calibre_micras_ocb']=='') { echo $calibre_ref; } else { echo $row_bolsa_oc['calibre_micras_ocb']; } ?>" size="10" onBlur="ocb_calibremillas()"></td>
                                <td id="dato1"><input type="text" name="calibre_ocb" value="<?php echo $row_bolsa_oc['calibre_ocb']; ?>" size="10" onBlur="ocb_calibremicras()"></td>
                              </tr><?php if($pedido=='0') { ?>
                              <tr>
                                <td colspan="4" id="subtitulo2">ESPECIFICACIONES TECNICAS DE LA IMPRESION</td>
                              </tr>
                              <tr>
                                <td colspan="4" id="fuente1">COLORES DE IMPRESION
                                  <?php if($n_egp_ref != '') {
                                    $sqlegp="SELECT * FROM Tbl_egp WHERE n_egp ='$n_egp_ref'";
                                    $resultegp= mysql_query($sqlegp);
                                    $numegp= mysql_num_rows($resultegp);
                                    if($numegp >='1') { 
                                     $color1_egp = mysql_result($resultegp,0,'color1_egp');
                                     $pantone1_egp = mysql_result($resultegp,0,'pantone1_egp');
                                     $color2_egp = mysql_result($resultegp,0,'color2_egp');
                                     $pantone2_egp = mysql_result($resultegp,0,'pantone2_egp');
                                     $color3_egp = mysql_result($resultegp,0,'color3_egp');
                                     $pantone3_egp = mysql_result($resultegp,0,'pantone3_egp');
                                     $color4_egp = mysql_result($resultegp,0,'color4_egp');
                                     $pantone4_egp = mysql_result($resultegp,0,'pantone4_egp');
                                     $color5_egp = mysql_result($resultegp,0,'color5_egp');
                                     $pantone5_egp = mysql_result($resultegp,0,'pantone5_egp');
                                     $color6_egp = mysql_result($resultegp,0,'color6_egp');
                                     $pantone6_egp = mysql_result($resultegp,0,'pantone6_egp');
                                     $color7_egp = mysql_result($resultegp,0,'color7_egp');
                                     $pantone7_egp = mysql_result($resultegp,0,'pantone7_egp');
                                     $color8_egp = mysql_result($resultegp,0,'color8_egp');
                                     $pantone8_egp = mysql_result($resultegp,0,'pantone8_egp');	
                                   } } ?></td>
                                 </tr>
                                 <tr>
                                  <td id="detalle1">Color 1 : <?php echo $color1_egp; ?></td>
                                  <td id="detalle1">Pantone 1 : <?php echo $pantone1_egp; ?></td>
                                  <td id="detalle1">Color 4 : <?php echo $color4_egp; ?></td>
                                  <td id="detalle1">Pantone 4 : <?php echo $pantone4_egp; ?></td>
                                </tr>
                                <tr>
                                  <td id="detalle1">Color 2 : <?php echo $color2_egp; ?></td>
                                  <td id="detalle1">Pantone 2 : <?php echo $pantone2_egp; ?></td>
                                  <td id="detalle1">Color 5 : <?php echo $color5_egp; ?></td>
                                  <td id="detalle1">Pantone 5 : <?php echo $pantone5_egp; ?></td>
                                </tr>
                                <tr>
                                  <td id="detalle1">Color 3 : <?php echo $color3_egp; ?></td>
                                  <td id="detalle1">Pantone 3 : <?php echo $pantone2_egp; ?></td>
                                  <td id="detalle1">Color 6 : <?php echo $color6_egp; ?></td>
                                  <td id="detalle1">Pantone 6 : <?php echo $pantone6_egp; ?></td>
                                </tr>          
                                <tr id="tr1">
                                  <td id="fuente1"><input type="checkbox" name="anexa_arte_ocb" value="1" <?php if (!(strcmp($row_bolsa_oc['anexa_arte_ocb'],1))) {echo "checked=\"checked\"";} ?>>
                                  Se anexa Arte </td>
                                  <td colspan="2" id="fuente2">REPETICION / RODILLO</td>
                                  <td id="fuente1"><input type="checkbox" name="negativo_ocb" value="1" <?php if (!(strcmp($row_bolsa_oc['negativo_ocb'],1))) {echo "checked=\"checked\"";} ?>>
                                  Se entrega cirel </td>
                                </tr>
                                <tr>
                                  <td id="fuente1"><input type="checkbox" name="anexa_arte_impreso_ocb" value="1" <?php if (!(strcmp($row_bolsa_oc['anexa_arte_impreso_ocb'],1))) {echo "checked=\"checked\"";} ?>>
                                  Se anexa arte impreso </td>
                                  <td colspan="2" id="dato2"><input type="text" name="rodillo_ocb" value="<?php echo $row_bolsa_oc['rodillo_ocb']; ?>" size="20"></td>
                                  <td id="fuente1"><input type="checkbox" name="cirel_ocb" value="1" <?php if (!(strcmp($row_bolsa_oc['cirel_ocb'],1))) {echo "checked=\"checked\"";} ?>>
                                  Se entrega negativo </td>
                                </tr><?php } ?>
                                <tr>
                                  <td colspan="4" id="subtitulo2">OBSERVACIONES GENERALES </td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="detalle1">1. Favor remitirse a las especificaciones t&eacute;cnicas del material y de impresi&oacute;n durante la producci&oacute;n. <br>
                                    2. Es muy importante que las caracter&iacute;sticas t&eacute;cnicas de la bolsa se respeten. <br>
                                    En caso de alguna duda comuniquela inmediatamente.<br>
                                    3. NO DEBE DE APARECER  EL LOGO DEL IMPRESOR POR NING&Uacute;N MOTIVO. <br>
                                  4. Debe de revisar bien los sellos y la resistencia de estos.</td>
                                </tr>
                                <tr id="tr1">
                                  <td colspan="4" id="fuente1">OTRAS OBSERVACIONES</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1"><textarea name="observaciones_ocb" cols="80" rows="2"><?php echo $row_bolsa_oc['observaciones_ocb']; ?></textarea></td>
                                </tr>
                                <tr id="tr1">
                                  <td colspan="2" id="fuente2">ELABORADO POR </td>
                                  <td colspan="2" id="fuente2">APROBADO POR </td>
                                </tr>
                                <tr>
                                  <td colspan="2" id="dato2"><input type="text" name="registrado_ocb" value="<?php echo $row_bolsa_oc['registrado_ocb']; ?>" size="30"></td>
                                  <td colspan="2" id="dato2"><input type="text" name="aprobado_ocb" value="<?php echo $row_bolsa_oc['aprobado_ocb']; ?>" size="30"></td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato2">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato2"><input type="submit" value="Actualizar O.C."></td>
                                </tr>
                              </table>
                              <input type="hidden" name="MM_update" value="form1">
                              <input type="hidden" name="n_ocb" value="<?php echo $row_bolsa_oc['n_ocb']; ?>">
                            </form></td>
                          </tr>
                        </table></div>
                        <b class="spiffy"> 
                          <b class="spiffy5"></b>
                          <b class="spiffy4"></b>
                          <b class="spiffy3"></b>
                          <b class="spiffy2"><b></b></b>
                          <b class="spiffy1"><b></b></b></b></div>
                        </td></tr></table>
                      </div>
                    </body>
                    </html>
                    <?php
                    mysql_free_result($usuario);mysql_close($conexion1);

                    mysql_free_result($bolsa_oc);
                    ?>