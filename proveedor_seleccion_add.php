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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO proveedor_seleccion (id_seleccion, id_p_seleccion, directo_p, punto_dist_p, forma_pago_p, otra_p, sist_calidad_p, norma_p, certificado_p, frecuencia_p, analisis_p, muestra_p, orden_compra_p, mayor_p, tiempo_agil_p, tiempo_p, entrega_p, metodos_p, flete_p, requisito_p, plan_mejora_p, aspecto_p, precios_p, otro_caso_p, asesor_com_p, nombre_asesor_p, limite_min_p, cuanto_p, proceso_p, primera_calificacion_p, encuestador_p, cargo_p, fecha_encuesta_p, ultima_calificacion_p) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_seleccion'], "int"),
                       GetSQLValueString($_POST['id_p_seleccion'], "int"),
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
                       GetSQLValueString($_POST['primera_calificacion_p'], "double"),
                       GetSQLValueString($_POST['encuestador_p'], "text"),
                       GetSQLValueString($_POST['cargo_p'], "text"),
                       GetSQLValueString($_POST['fecha_encuesta_p'], "date"),
                       GetSQLValueString($_POST['ultima_calificacion_p'], "double"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "proveedor_vista.php?id_p=" . $_POST['id_p_seleccion'] . "";
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

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
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
	</ul></td>
</tr>
<tr>
  <td colspan="2" align="center" id="linea1"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('primera_calificacion_p','','R','fecha_encuesta_p','','R','encuestador_p','','R','cargo_p','','R');return document.MM_returnValue">
    <table id="tabla2">
      <tr id="tr2">
        <td colspan="2" id="titulo1"><input name="id_seleccion" type="hidden" value="">
          <input name="id_p_seleccion" type="hidden" value="<?php echo $row_proveedor['id_p']; ?>">
          III. ENCUESTA PARA LA CALIFICACION DEL PROVEEDOR ( ADICIONAR ) </td>
        </tr><?php $tipo_p=$_GET['tipo_p'];
			if($tipo_p == '2')
			{ ?>
			<tr>
			<td colspan="2" id="numero2">NO se registra la encuesta para la calificación por que es un proveedor TIPO B (NO CRITICO)</td>
			</tr>
			<?php
			}
			else
			{		
			?>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>1</strong>. Es una empresa que ofrece directamente sus productos y/o servicios,los subcontrata o tiene distribuidores ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="directo_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Directo</option>
          <option value="3">Distribuidor</option>
          <option value="1">Subcontrata</option>
        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Puntos de distribuci&oacute;n ? </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="punto_dist_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>2</strong>. Ofrece Formas de Pago ? </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="forma_pago_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">30 a 60 dias</option>
          <option value="3">15 a 29 dias</option>
          <option value="1">Contado a 14 dias</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Otra, Cual ? </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="otra_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>3</strong>. Tiene Sistema de Gesti&oacute;n de Calidad certificado ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="sist_calidad_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="3">En proceso</option>
          <option value="1">No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Con cual Norma y que porcentaje de Avance ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="norma_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>4</strong>. Entrega certificado de calidad de sus productos con cada despacho (insumos) u ofrece garantia al servicio ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="certificado_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="3">Algunas veces</option>
          <option value="1">No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Con que frecuencia ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="frecuencia_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>5</strong>. Realiza analisis de control de calidad a cada lote de material ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="analisis_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="3">Por muestreo</option>
          <option value="1">No</option>
        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Si es por muestra, cada cuanto ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="muestra_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>6</strong>. Requiere orden de compra con anterioridad ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="orden_compra_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">1 a 15 d&iacute;as</option>
          <option value="3">16 a 30 d&iacute;as</option>
          <option value="1">Mayor a 30 d&iacute;as</option>
        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Si es mayor a 30 en cuanto tiempo?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="mayor_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>7</strong>. Tiene establecido un tiempo para la agilidad de respuesta ante un reclamo ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="tiempo_agil_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">El mismo dia</option>
          <option value="3">1 semana</option>
          <option value="1">1 mes</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Cuanto tiempo ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="tiempo_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>8</strong>. Realiza entrega del producto o servicio en las instalaciones de la empresa ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="entrega_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="3">Con intermediario</option>
          <option value="1">No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Otros metodos ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="metodos_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>9</strong>. El flete correspondiente a la entrega corre por parte del proveedor ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="flete_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="1">No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">&oacute; cuando se establece ese requisito ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="requisito_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>10</strong>. Tiene establecido un plan de mejora para el producto, servicios y/o sus procesos?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="plan_mejora_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="1">No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">En que aspectos ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="aspecto_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>11</strong>. Maneja listado de precios actualizado ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="precios_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Anual</option>
          <option value="3">Semestral</option>
          <option value="1">Otro (&lt; 6 meses)</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">En caso de otro, explique.</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="otro_caso_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>12</strong>. Asigna asesores comerciales a cada empresa ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="asesor_com_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="3">No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Nombre ? </td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="nombre_asesor_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>13</strong>. Tiene limites minimos de pedido ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="limite_min_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">No</option>
          <option value="1">Si</option>
                                        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Cuanto ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="cuanto_p" cols="70" rows="2"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>14</strong>. Cuentan con un proceso definido para preservar y manejar el material o equipo suministrado por el cliente ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="proceso_p" onBlur="calificacion()">
          <option value="0">N.A.</option>
          <option value="5">Si</option>
          <option value="1">No</option>
                </select></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>Nota</strong>: En algunos casos puede que su empresa no aplique a alguno de los items anteriores. Por ejemplo, si la pregunta hace referencia a un producto (tangible) y su empresa es de servicios, si es el caso por favor se&ntilde;ale la casilla <strong>NO</strong> de la columna <strong> No Aplica</strong></td>
      </tr>
      <tr>
        <td id="fuente1">CALIFICACION (%) </td>
        <td id="fuente1">FECHA ENCUESTA</td>
      </tr>
      <tr>
        <td id="dato1"><input name="primera_calificacion_p" type="text" size="10" readonly="true"></td>
        <td id="dato1"><input type="text" name="fecha_encuesta_p" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
      </tr>
      <tr>
        <td id="fuente1">NOMBRE DEL ENCUESTADOR </td>
        <td id="fuente1">CARGO DEL ENCUESTADOR </td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="encuestador_p" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="32"></td>
        <td id="dato1"><input type="text" name="cargo_p" value="" size="30">
          <input name="ultima_calificacion_p" type="hidden" value=""></td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input type="submit" value="ADD EVALUACION"></td>
        </tr>
		<?php
		}
		?>      
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form></td>
</tr>   
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><a href="proveedor_edit.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/menos.gif" alt="EDITAR PROVEEDOR" border="0" style="cursor:hand;" /></a><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"></a><a href="proveedores.php"><img src="images/cat.gif" border="0" style="cursor:hand;" alt="LISTADO PROVEEDORES" /></a><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a></td>
    </tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
  </div>
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
mysql_free_result($usuario);

mysql_free_result($proveedor);
?>
