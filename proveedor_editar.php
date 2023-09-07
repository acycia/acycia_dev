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
session_start();
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) 
{
  $updateSQL = sprintf("UPDATE proveedor SET proveedor_p=%s, tipo_p=%s, direccion_p=%s, pais_p=%s, dpto_p=%s, ciudad_p=%s, telefono_p=%s, fax_p=%s, contacto_p=%s, celular_c_p=%s, email_c_p=%s, contribuyentes_p=%s, autoretenedores_p=%s, regimen_p=%s, prod_serv_p=%s, directo_p=%s, punto_dist_p=%s, forma_pago_p=%s, otra_p=%s, sist_calidad_p=%s, norma_p=%s, certificado_p=%s, frecuencia_p=%s, analisis_p=%s, muestra_p=%s, orden_compra_p=%s, mayor_p=%s, tiempo_agil_p=%s, tiempo_p=%s, entrega_p=%s, metodos_p=%s, flete_p=%s, requisito_p=%s, plan_mejora_p=%s, aspecto_p=%s, precios_p=%s, otro_caso_p=%s, asesor_com_p=%s, nombre_asesor_p=%s, limite_min_p=%s, cuanto_p=%s, proceso_p=%s, encuestador_p=%s, cargo_enc_p=%s, fecha_diligencia_p=%s, calificacion_p=%s WHERE nit_p=%s",
                       GetSQLValueString($_POST['proveedor_p'], "text"),
                       GetSQLValueString($_POST['tipo_p'], "text"),
                       GetSQLValueString($_POST['direccion_p'], "text"),
                       GetSQLValueString($_POST['pais_p'], "text"),
                       GetSQLValueString($_POST['dpto_p'], "text"),
                       GetSQLValueString($_POST['ciudad_p'], "text"),
                       GetSQLValueString($_POST['telefono_p'], "text"),
                       GetSQLValueString($_POST['fax_p'], "text"),
                       GetSQLValueString($_POST['contacto_p'], "text"),
                       GetSQLValueString($_POST['celular_c_p'], "text"),
                       GetSQLValueString($_POST['email_c_p'], "text"),
                       GetSQLValueString(isset($_POST['contribuyentes_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['autoretenedores_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['regimen_p'], "text"),
                       GetSQLValueString($_POST['prod_serv_p'], "text"),
                       GetSQLValueString($_POST['directo_p'], "int"),
                       GetSQLValueString($_POST['punto_dist_p'], "text"),
                       GetSQLValueString($_POST['forma_pago_p'], "int"),
                       GetSQLValueString($_POST['otra_p'], "text"),
                       GetSQLValueString($_POST['sist_calidad_p'], "int"),
                       GetSQLValueString($_POST['norma_p'], "text"),
                       GetSQLValueString($_POST['certificado_p'], "int"),
                       GetSQLValueString($_POST['frecuencia_p'], "text"),
                       GetSQLValueString($_POST['analisis_p'], "int"),
                       GetSQLValueString($_POST['muestra_p'], "text"),
                       GetSQLValueString($_POST['orden_compra_p'], "int"),
                       GetSQLValueString($_POST['mayor_p'], "text"),
                       GetSQLValueString($_POST['tiempo_agil_p'], "int"),
                       GetSQLValueString($_POST['tiempo_p'], "text"),
                       GetSQLValueString($_POST['entrega_p'], "int"),
                       GetSQLValueString($_POST['metodos_p'], "text"),
                       GetSQLValueString($_POST['flete_p'], "int"),
                       GetSQLValueString($_POST['requisito_p'], "text"),
                       GetSQLValueString($_POST['plan_mejora_p'], "int"),
                       GetSQLValueString($_POST['aspecto_p'], "text"),
                       GetSQLValueString($_POST['precios_p'], "int"),
                       GetSQLValueString($_POST['otro_caso_p'], "text"),
                       GetSQLValueString($_POST['asesor_com_p'], "int"),
                       GetSQLValueString($_POST['nombre_asesor_p'], "text"),
                       GetSQLValueString($_POST['limite_min_p'], "int"),
                       GetSQLValueString($_POST['cuanto_p'], "text"),
                       GetSQLValueString($_POST['proceso_p'], "int"),
                       GetSQLValueString($_POST['encuestador_p'], "text"),
                       GetSQLValueString($_POST['cargo_enc_p'], "text"), 
					   GetSQLValueString($_POST['fecha_diligencia_p'], "date"),	                      
                       GetSQLValueString($_POST['calificacion_p'], "text"),
                       GetSQLValueString($_POST['nit_p'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "listado_proveedor.php";
  if (isset($_SERVER['QUERY_STRING'])) 
  {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_comercial = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

$colname_ver_proveedor = "-1";
if (isset($_GET['nit_p'])) {
  $colname_ver_proveedor = (get_magic_quotes_gpc()) ? $_GET['nit_p'] : addslashes($_GET['nit_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_proveedor = sprintf("SELECT * FROM proveedor WHERE nit_p = '%s'", $colname_ver_proveedor);
$ver_proveedor = mysql_query($query_ver_proveedor, $conexion1) or die(mysql_error());
$row_ver_proveedor = mysql_fetch_assoc($ver_proveedor);
$totalRows_ver_proveedor = mysql_num_rows($ver_proveedor);

mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario_comercial['tipo_usuario'];
$query_ver_sub_menu = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='3' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
$ver_sub_menu = mysql_query($query_ver_sub_menu, $conexion1) or die(mysql_error());
$row_ver_sub_menu = mysql_fetch_assoc($ver_sub_menu);
$totalRows_ver_sub_menu = mysql_num_rows($ver_sub_menu);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/estilos.css" type="text/css">
<script type="text/javascript" src="js/funcionjs.js"></script>
</head>
<body>
<table class="tabloide">
  <tr><td class="cabeza"><div id="logoindex"></div></td></tr>
  <tr>
    <td class="normal">
	    <table class="tabloide">
		  <tr>
		  <td class="normal"><div id="fuente1"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div></td><td class="normal"><div id="fuente2"><a href="<?php echo $logoutAction ?>">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>  
  <tr>
    <td class="normal">
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('proveedor_p','','R','direccion_p','','R','pais_p','','R','telefono_p','','R','ciudad_p','','R','contacto_p','','R','email_c_p','','NisEmail','encuestador_p','','R','cargo_enc_p','','R','fecha_diligencia_p','','R');return document.MM_returnValue">
        <table class="tabloide">
          <tr>
            <td class="normal">
			<table class="tabloide">
              <tr>
                <td colspan="2" class="estilocelda1"><div id="EstiloI">SELECCION DE PROVEEDORES </div></td>
              </tr>
              <tr>
                <td width="356" class="estilocelda2"><div id="EstiloII">CODIGO : A3-F03</div></td>
                <td width="358" class="estilocelda2"><div id="EstiloII">VERSION : 0</div></td>
              </tr>
            </table></td>
          </tr>
          <tr valign="baseline">
            <td class="normal"><div id="fuente4">1. INFORMACION COMERCIAL </div></td>
          </tr>
          <tr>
            <td class="normal"><table class="tabloide">
              <tr>
                <td width="127" class="estilocelda2"><div id="fuente5">*RAZON SOCIAL</div></td>
                <td width="230" class="estilocelda2"><div id="fuente1"><input type="text" name="proveedor_p" value="<?php echo $row_ver_proveedor['proveedor_p']; ?>" size="30"></div></td>
                <td width="128" class="estilocelda2"><div id="fuente5">* NIT - C.C. - ID </div></td>
                <td width="225" class="estilocelda2"><div id="fuente5"><?php echo $row_ver_proveedor['nit_p']; ?></div></td>
              </tr>
              <tr>
                <td class="estilocelda2"><div id="fuente5">* DIRECCION</div></td>
                <td class="estilocelda2"><div id="fuente1"><input name="direccion_p" type="text" value="<?php echo $row_ver_proveedor['direccion_p']; ?>" size="30">
                </div></td>
                <td class="estilocelda2"><div id="fuente5">* TIPO</div></td>
                <td class="estilocelda2"><div id="fuente1">
                    <select name="tipo_p" onBlur="consulta()">
                      <option value="0" <?php if (!(strcmp(0, $_GET['tipo_p']))) {echo "selected=\"selected\"";} ?>>*</option>
                      <option value="B (No Critico)" <?php if (!(strcmp("B (No Critico)", $_GET['tipo_p']))) {echo "selected=\"selected\"";} ?>>B (No Critico)</option>
                      <option value="A (Critico)" <?php if (!(strcmp("A (Critico)", $_GET['tipo_p']))) {echo "selected=\"selected\"";} ?>>A (Critico)</option>
                      <option value="A y B" <?php if (!(strcmp("A y B", $_GET['tipo_p']))) {echo "selected=\"selected\"";} ?>>A y B</option>
                    </select></div></td>
              </tr>
              <tr>
                <td class="estilocelda2"><div id="fuente5">* PAIS</div></td>
                <td class="estilocelda2"><div id="fuente1"><input name="pais_p" type="text" id="pais_p" value="<?php echo $row_ver_proveedor['pais_p']; ?>" size="30">
                </div></td>
                <td class="estilocelda2"><div id="fuente5">* TELEFONO</div></td>
                <td class="estilocelda2"><div id="fuente1"><input type="text" name="telefono_p" value="<?php echo $row_ver_proveedor['telefono_p']; ?>" size="20">
                </div></td>
              </tr>
              <tr>
               <td class="estilocelda2"><div id="fuente5">* PROVINCIA</div></td>
               <td class="estilocelda2"><div id="fuente5"><input name="dpto_p" type="text" id="dpto_p" value="<?php echo $row_ver_proveedor['dpto_p']; ?>" size="30"></div></td>
                <td class="estilocelda2"><div id="fuente5">* FAX</div></td>
				<td class="estilocelda2"><div id="fuente1"><input type="text" name="fax_p" value="<?php echo $row_ver_proveedor['fax_p']; ?>" size="20"></div></td>
              </tr>
              <tr>
                <td class="estilocelda2"><div id="fuente5">* CIUDAD</div></td>
			    <td class="estilocelda2"><div id="fuente5"><input name="ciudad_p" type="text" id="ciudad_p" value="<?php echo $row_ver_proveedor['ciudad_p']; ?>" size="30"></div></td>
			    <td class="estilocelda2"><div id="fuente5">* EMAIL</div></td>
			    <td class="estilocelda2"><div id="fuente1"><input type="text" name="email_c_p" value="<?php echo $row_ver_proveedor['email_c_p']; ?>" size="20"></div></td>
              </tr>
              <tr>
              <td class="estilocelda2"><div id="fuente5">* CONTACTO </div></td>
			  <td class="estilocelda2"><div id="fuente5"><input type="text" name="contacto_p" value="<?php echo $row_ver_proveedor['contacto_p']; ?>" size="30"></div></td>
			  <td class="estilocelda2"><div id="fuente5">* CELULAR</div></td>
			  <td class="estilocelda2"><div id="fuente1"><input type="text" name="celular_c_p" value="<?php echo $row_ver_proveedor['celular_c_p']; ?>" size="20">
			  </div></td>
              </tr>
              <tr>
                <td class="estilocelda2"><div id="fuente6">
                    <input <?php if (!(strcmp($row_ver_proveedor['contribuyentes_p'],1))) {echo "checked=\"checked\"";} ?> name="contribuyentes_p" type="checkbox" id="contribuyentes_p" value="1">
                    CONTRIBUYENTES</div></td>
                <td class="estilocelda2"><div id="fuente6">
                    <input <?php if (!(strcmp($row_ver_proveedor['autoretenedores_p'],1))) {echo "checked=\"checked\"";} ?> name="autoretenedores_p" type="checkbox" id="autoretenedores_p" value="1">
                    AUTORETENEDORES</div></td>
			  <td class="estilocelda2"><div id="fuente5">* REGIMEN</div></td>
			  <td class="estilocelda2"><div id="fuente1">
                  <select name="regimen_p">
                    <option value="" <?php if (!(strcmp("", $row_ver_proveedor['regimen_p']))) {echo "selected=\"selected\"";} ?>>*</option>
                    <option value="Comun" <?php if (!(strcmp("Comun", $row_ver_proveedor['regimen_p']))) {echo "selected=\"selected\"";} ?>>Comun</option>
                    <option value="Simplificado" <?php if (!(strcmp("Simplificado", $row_ver_proveedor['regimen_p']))) {echo "selected=\"selected\"";} ?>>Simplificado</option>
                  </select></div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="normal"><div id="fuente4">2. INFORMACION DEL PROCESO - PRODUCTO / SERVICIO</div></td>
          </tr>
          <tr>
            <td class="normal"><table class="tabloide">
              <tr>
                <td width="129" class="estilocelda2"><div id="fuente5">* Productos o Servicios que Suministra.</div></td>
                <td width="589" class="estilocelda2"><div id="fuente5"><textarea name="prod_serv_p" cols="60" rows="2"><?php echo $row_ver_proveedor['prod_serv_p']; ?></textarea></div></td>
              </tr>
            </table></td>
          </tr>
		  <?php
			$var10=$_GET['tipo_p'];
			if($var10=='B (No Critico)')
			{		
			?>
          <tr>
            <td class="normal"><div id="fuente7">NO se registr&oacute; la encuesta para la calificaci&oacute;n por que es un proveedor TIPO B (NO CRITICO)</div></td>
          </tr>
		  <?php 
		  }
		  else
		  {?>
          <tr>
		  <td class="normal"><div id="fuente4">III. ENCUESTA PARA CALIFICACION DEL PROVEEDOR</div>		  </td>
		  </tr>
          <tr>
		  <td class="normal"><table class="tabloide">
		  <tr>
		  <td class="estilocelda2" colspan="3"><div id="fuente5"><strong>1</strong>. Es una empresa que ofrece directamente sus productos y/o servicios,los subcontrata o tiene distribuidores?</div></td></tr>
		  <tr>
              <td width="162" class="normal"><div id="fuente1">
                  <select name="directo_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['directo_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['directo_p']))) {echo "selected=\"selected\"";} ?>>Directo</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['directo_p']))) {echo "selected=\"selected\"";} ?>>Distribuidor</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['directo_p']))) {echo "selected=\"selected\"";} ?>>Subcontrata</option>
                  </select></div></td>
              <td width="256" class="normal"><div id="fuente2">Puntos de Distribuci&oacute;n ? </div></td>
              <td width="296" class="estilocelda2"><div id="fuente1">
                  <input name="punto_dist_p" type="text" value="<?php echo $row_ver_proveedor['punto_dist_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td class="estilocelda2" colspan="3"><div id="fuente5"><strong>2</strong>. Ofrece Formas de Pago ? </div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="forma_pago_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['forma_pago_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>30 a 60 dias</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>15 a 29 dias</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>Contado a 14 dias</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Otra, Cual ? </div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="otra_p" type="text" value="<?php echo $row_ver_proveedor['otra_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>3</strong>. Tiene Sistema de Gesti&oacute;n de Calidad certificado? </div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="sist_calidad_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>En proceso</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Con cual Norma y que porcentaje de Avance ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="norma_p" type="text" value="<?php echo $row_ver_proveedor['norma_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>4</strong>. Entrega certificado de calidad de sus productos con cada despacho (insumos) u ofrece garantia al servicio?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="certificado_p"onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['certificado_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['certificado_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['certificado_p']))) {echo "selected=\"selected\"";} ?>>Algunas veces</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['certificado_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Con que frecuencia ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="frecuencia_p" type="text" value="<?php echo $row_ver_proveedor['frecuencia_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>5</strong>. Realiza analisis de control de calidad a cada lote de material ?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="analisis_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['analisis_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['analisis_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['analisis_p']))) {echo "selected=\"selected\"";} ?>>Por muestreo</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['analisis_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Si es por muestra, cada cuanto ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="muestra_p" type="text" value="<?php echo $row_ver_proveedor['muestra_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td class="estilocelda2" colspan="3"><div id="fuente5"><strong>6</strong>. Requiere orden de compra con anterioridad ?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="orden_compra_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['orden_compra_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>1 a 15 d&iacute;as</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>16 a 30 d&iacute;as</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>Mayor a 30 d&iacute;as</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Si es mayor a 30 en cuanto tiempo ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="mayor_p" type="text" value="<?php echo $row_ver_proveedor['mayor_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>7</strong>. Tiene establecido un tiempo para la agilidad de respuesta ante un reclamo ?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="tiempo_agil_p"onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>El mismo dia</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>1 semana</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>1 mes</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Cuanto tiempo ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="tiempo_p" type="text" value="<?php echo $row_ver_proveedor['tiempo_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>8</strong>. Realiza entrega del producto o servicio en las instalaciones de la empresa? </div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="entrega_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['entrega_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['entrega_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['entrega_p']))) {echo "selected=\"selected\"";} ?>>Con intermediario</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['entrega_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Otros metodos ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="metodos_p" type="text" value="<?php echo $row_ver_proveedor['metodos_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>9</strong>. El flete correspondiente a la entrega corre por parte del proveedor?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="flete_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['flete_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['flete_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['flete_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">&oacute; cuando se establece ese requisito ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="requisito_p" type="text" value="<?php echo $row_ver_proveedor['requisito_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>10</strong>. Tiene establecido un plan de mejora para el producto, servicios y/o sus procesos? </div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="plan_mejora_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['plan_mejora_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['plan_mejora_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['plan_mejora_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">En que aspectos ?</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="aspecto_p" type="text" value="<?php echo $row_ver_proveedor['aspecto_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>11</strong>. Maneja listado de precios actualizado ?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="precios_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['precios_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['precios_p']))) {echo "selected=\"selected\"";} ?>>Anual</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['precios_p']))) {echo "selected=\"selected\"";} ?>>Semestral</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['precios_p']))) {echo "selected=\"selected\"";} ?>>Otro (&lt; 6 meses)</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">En caso de otro, explique.</div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="otro_caso_p" type="text" value="<?php echo $row_ver_proveedor['otro_caso_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>12</strong>. Asigna asesores comerciales a cada empresa?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="asesor_com_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['asesor_com_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['asesor_com_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="3" <?php if (!(strcmp(3, $row_ver_proveedor['asesor_com_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Nombre ? </div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="nombre_asesor_p" type="text" value="<?php echo $row_ver_proveedor['nombre_asesor_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>13</strong>. Tiene limites minimos de pedido?</div></td>
            </tr>
            <tr>
              <td class="normal"><div id="fuente1">
                  <select name="limite_min_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['limite_min_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['limite_min_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['limite_min_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                  </select>
              </div></td>
              <td class="normal"><div id="fuente2">Cuanto ? </div></td>
              <td class="estilocelda2"><div id="fuente1">
                  <input name="cuanto_p" type="text" value="<?php echo $row_ver_proveedor['cuanto_p']; ?>" size="40">
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda2"><div id="fuente5"><strong>14</strong>. Cuentan con un proceso definido para preservar y manejar el material o equipo suministrado por el cliente ?</div></td>
            </tr>
            <tr>
              <td colspan="3" class="estilocelda1"><div id="fuente1">
                  <select name="proceso_p" onChange="calcular()">
                    <option value="0" <?php if (!(strcmp(0, $row_ver_proveedor['proceso_p']))) {echo "selected=\"selected\"";} ?>></option>
                    <option value="5" <?php if (!(strcmp(5, $row_ver_proveedor['proceso_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                    <option value="1" <?php if (!(strcmp(1, $row_ver_proveedor['proceso_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                  </select>
              </div></td>
            </tr>
            <tr>
              <td colspan="3" class="normal"><div id="fuente5">En algunos casos puede que su empresa no aplique a alguno de los items anteriores. Por ejemplo, si la pregunta hace referencia a un producto (tangible) y su empresa es de servicios, si es el caso por favor se&ntilde;ale la casilla <strong> NO  </strong> de la columna <strong> No Aplica</strong>.</div></td>
            </tr>
          </table></td>
          </tr>
		  <?php
		  }
		  ?> 
          <tr>
            <td class="normal"><table class="tabloide">
              <tr>
                <td width="118" class="estilocelda2"><div id="fuente5">REGISTRADO POR </div></td>
                <td width="236" class="estilocelda2"><div id="fuente1">
                    <input type="text" name="encuestador_p" value="<?php echo $row_ver_proveedor['encuestador_p']; ?>" size="30">
                </div></td>
                <td width="139" class="estilocelda2"><div id="fuente5">CALIFICACION (%) </div></td>
                <td width="217" class="estilocelda2"><div id="fuente1">
                    <input name="calificacion_p" type="text" value="<?php echo $row_ver_proveedor['calificacion_p']; ?>" size="20">
                </div></td>
              </tr>
              <tr>
                <td class="estilocelda2"><div id="fuente5">CARGO</div></td>
                <td class="estilocelda2"><div id="fuente1">
                    <input type="text" name="cargo_enc_p" value="<?php echo $row_ver_proveedor['cargo_enc_p']; ?>" size="30">
                </div></td>
                <td class="estilocelda2"><div id="fuente5">FECHA (aaaa-mm-dd) </div></td>
                <td class="estilocelda2"><div id="fuente1">
                    <input type="text" name="fecha_diligencia_p" id="fecha_diligencia_p" value="<?php echo $row_ver_proveedor['fecha_diligencia_p']; ?>" size="20">
                </div></td>
              </tr>              
              <tr>
                <td colspan="4" class="estilocelda1"><div id="fuente3">
                  <input name="submit" type="submit" value="Actualizar registro">
                </div></td>
                </tr>
            </table></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="nit_p" value="<?php echo $row_ver_proveedor['nit_p']; ?>">
    </form>
	</td>
  </tr>  
  <tr>
    <td class="normal">
	<table class="tabloide">
      <tr>
        <td width="54" height="34" class="normal"><a href="menu.php"></a><img src="images/home.gif" id="menu"></td>
        <td width="230" class="normal"><div id="fuente3"><a href="compras.php">Gesti&oacute;n Compras</a></div></td>
        <td width="234" class="normal"><div id="fuente3"><a href="listado_proveedor.php">Listado Proveedores </a></div></td>
        <td width="89" class="normal"><div id="fuente3"><a href="borrado_proveedor.php?nit_p=<?php echo $row_ver_proveedor['nit_p']; ?>"><img src="images/signos.jpg" alt="Eliminar Proveedor" border="0"></a></div></td>
        <td width="99" class="normal"><div id="fuente2"><img src="images/firma3.bmp" alt="sistemas@acycia.com"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_proveedor);

mysql_free_result($ver_sub_menu);
?>
