<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?><?php require_once('Connections/conexion1.php'); ?>
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

$nombre1 = $_POST['arc1'];
$nombre2 = $_POST['arc2'];
$nombre3 = $_POST['arc3'];


if (isset($_FILES['camara_comercio_p']) && $_FILES['camara_comercio_p']['name'] != "") {
if($nombre1 != '') {
if (file_exists("archivosc/".$nombre1))
{ unlink("archivosc/".$nombre1);  } 
} 
  $directorio1 = "archivosc/";
  $nombre1 = str_replace(' ', '',  $_FILES['camara_comercio_p']['name']);
  $archivo_temporal1 = $_FILES['camara_comercio_p']['tmp_name'];
  if (!copy($archivo_temporal1,$directorio1.$nombre1)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen1 = "archivosc/".$nombre1; }
}
if (isset($_FILES['rut_p']) && $_FILES['rut_p']['name'] != "") {
if($nombre2 != '') {
if (file_exists("archivosc/".$nombre2))
{ unlink("archivosc/".$nombre2);  } 
}
  $directorio2 = "archivosc/";
  $nombre2 = str_replace(' ', '',  $_FILES['rut_p']['name']);
  $archivo_temporal2 = $_FILES['rut_p']['tmp_name'];
  if (!copy($archivo_temporal2,$directorio2.$nombre2)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen2 = "archivosc/".$nombre2; }
}
if (isset($_FILES['datos_proyeccion_p']) && $_FILES['datos_proyeccion_p']['name'] != "") {
if($nombre3 != '') {
if (file_exists("archivosc/".$nombre3))
{ unlink("archivosc/".$nombre3);  } 
}
  $directorio3 = "archivosc/";
  $nombre3 = str_replace(' ', '',  $_FILES['datos_proyeccion_p']['name']);
  $archivo_temporal3 = $_FILES['datos_proyeccion_p']['tmp_name'];
  if (!copy($archivo_temporal3,$directorio3.$nombre3)) {
  $error = "Error al enviar el Archivo";
  } else { $imagen3 = "archivosc/".$nombre3; }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE proveedor SET nit_p=%s, proveedor_p=%s, tipo_p=%s, tipo_servicio_p=%s, direccion_p=%s, pais_p=%s, dpto_p=%s, ciudad_p=%s, telefono_p=%s, fax_p=%s, contacto_p=%s, celular_c_p=%s, email_c_p=%s, contribuyentes_p=%s, autoretenedores_p=%s, regimen_p=%s, prod_serv_p=%s, registro_p=%s, fecha_registro_p=%s, modificacion_p=%s, fecha_modif_p=%s, estado_p=%s,camara_comercio_p=%s,rut_p=%s,
datos_proyeccion_p=%s, tipo_provee=%s WHERE id_p=%s",
                       GetSQLValueString($_POST['nit_p'], "text"),
                       GetSQLValueString($_POST['proveedor_p'], "text"),
                       GetSQLValueString($_POST['tipo_p'], "int"),
					   GetSQLValueString($_POST['tipo_servicio_p'], "text"),
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
                       GetSQLValueString($_POST['registro_p'], "text"),
                       GetSQLValueString($_POST['fecha_registro_p'], "date"),
                       GetSQLValueString($_POST['modificacion_p'], "text"),
                       GetSQLValueString($_POST['fecha_modif_p'], "date"),
					   GetSQLValueString($_POST['estado_p'], "text"),
             GetSQLValueString($nombre1, "text"),
             GetSQLValueString($nombre2, "text"),
             GetSQLValueString($nombre3, "text"),
             GetSQLValueString($_POST['tipo_provee'], "text"),
                       GetSQLValueString($_POST['id_p'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());


/*  $insertSQL2 = sprintf("UPDATE TblProveedorInsumo SET id_p=%s, id_in=%s  WHERE ",
                       GetSQLValueString($_POST['id_p'],"int"),
                       GetSQLValueString($_POST['id_in'], "int"));
   mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());*/


  $updateGoTo = "proveedor_vista.php?id_p=" . $_POST['id_p'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

$row_proveedor = $conexion->llenarCampos("proveedor", "WHERE id_p='".$colname_proveedor."' ", " ", "*");

$row_tipo = $conexion->llenaSelect('tipo',"","ORDER BY nombre_tipo ASC");  

$colname_proveedor_seleccion = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor_seleccion = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor_seleccion = sprintf("SELECT * FROM proveedor_seleccion WHERE id_p_seleccion = %s", $colname_proveedor_seleccion);
$proveedor_seleccion = mysql_query($query_proveedor_seleccion, $conexion1) or die(mysql_error());
$row_proveedor_seleccion = mysql_fetch_assoc($proveedor_seleccion);
$totalRows_proveedor_seleccion = mysql_num_rows($proveedor_seleccion);

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM insumo WHERE id_insumo IN(SELECT id_in FROM TblProveedorInsumo WHERE id_p=$colname_proveedor_seleccion)  ORDER BY descripcion_insumo ASC";
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);

?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

<!-- desde aqui para listados nuevos -->
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<link rel="stylesheet" type="text/css" href="css/general.css"/>

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

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1"  enctype="multipart/form-data" onSubmit="MM_validateForm('fecha_registro_p','','R','registro_p','','R','fecha_modif_p','','R','modificacion_p','','R','proveedor_p','','R','nit_p','','R','direccion_p','','R','contacto_p','','R','telefono_p','','R','fax_p','','R','pais_p','','R','ciudad_p','','R','email_c_p','','NisEmail','regimen_p','','R','prod_serv_p','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr1">
            <td id="codigo">CODIGO : A3-F03 </td>
            <td colspan="2" id="titulo2">EDITAR  PROVEEDOR </td>
            <td id="codigo">VERSION : 0 </td>
          </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
            <td colspan="2" id="numero2">N&deg; <?php echo $row_proveedor['id_p']; ?></td>
            <td id="dato2"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/hoja.gif" alt="EDITAR" border="0" style="cursor:hand;" /></a><a href="javascript:eliminar1('id_p',<?php echo $row_proveedor['id_p']; ?>,'proveedor_edit.php')"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" /></a><a href="proveedor_mejoras.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/m.gif" alt="PLAN MEJORA" border="0" style="cursor:hand;"></a><a href="proveedores.php"><img src="images/cat.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"></a><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a><a href="proveedor_insumo.php" target="_top"><img src="images/cliente.gif" alt="ADD INSUMO" title="ADD INSUMO" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td id="fuente1">FECHA DE REGISTRO </td>
            <td colspan="2" id="fuente1">REGISTRADO POR </td>
          </tr>
          <tr>
            <td id="dato1"><input name="fecha_registro_p" type="date" required id="fecha_registro_p" min="2000-01-02" value="<?php echo $row_proveedor['fecha_registro_p']; ?>" size="10"/></td>
            <td colspan="2" id="dato1"><input type="text" name="registro_p" value="<?php echo $row_proveedor['registro_p']; ?>" size="30"></td>
            </tr>
          <tr>
           <td id="fuente1">FECHA MODIFICACION </td>
		   <td colspan="2" id="fuente1">MODIFICADO POR </td>
          </tr>
          <tr>
            <td id="dato1">
              <input name="fecha_modif_p" type="date" required id="fecha_modif_p" min="2000-01-02" value="<?php echo $row_proveedor['fecha_modif_p']; ?>" size="10"/></td>
            <td colspan="2" id="dato1"><input type="text" name="modificacion_p" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" onBlur="conMayusculas(this)"></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2">Validacion de proveedores que afectan directamente la calidad del producto. </td>
            </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">I. INFORMACION COMERCIAL </td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">RAZON SOCIAL </td>
            <td id="fuente1">NIT - C.C. - ID </td>
            <td id="fuente1">TIPO</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="text" name="proveedor_p" required value="<?php echo $row_proveedor['proveedor_p']; ?>" size="50" onChange="if (form1.proveedor_p.value) { DatosGestiones('3','proveedor_p',form1.proveedor_p.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); }" onBlur="conMayusculas(this)"></td>
            <td id="dato1"><input type="text" name="nit_p" required value="<?php echo $row_proveedor['nit_p']; ?>" size="20" onChange="if (form1.nit_p.value) { DatosGestiones('3','nit_p',form1.nit_p.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); }"></td>
            <td id="dato1">
                <select name="tipo_p" id="tipo_p" class="busqueda selectsMedio">
                  <option value="0">O.P</option>
                  <?php  foreach($row_tipo as $row_tipo ) { ?>
                    <option value="<?php echo $row_tipo['id_tipo']?>"<?php if (!(strcmp($row_tipo['id_tipo'], $row_proveedor['tipo_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo['nombre_tipo']?></option>
                  </option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="2"><div id="resultado"></div></td>
            </tr>
          <tr>
            <td colspan="4" id="fuente1">DIRECCION COMERCIAL </td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><input type="text" name="direccion_p" value="<?php echo $row_proveedor['direccion_p']; ?>" size="100" onBlur="conMayusculas(this)"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">CONTACTO COMERCIAL </td>
            <td id="fuente1">TELEFONO</td>
            <td id="fuente1">FAX</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="text" name="contacto_p" value="<?php echo $row_proveedor['contacto_p']; ?>" size="50" onBlur="conMayusculas(this)"></td>
            <td id="dato1"><input type="text" name="telefono_p" value="<?php echo $row_proveedor['telefono_p']; ?>" size="20"></td>
            <td id="dato1"><input type="text" name="fax_p" value="<?php echo $row_proveedor['fax_p']; ?>" size="20"></td>
          </tr>
          <tr>
            <td id="fuente1">CELULAR</td>
            <td id="fuente1">PAIS</td>
            <td id="fuente1">PROVINCIA</td>
            <td id="fuente1">CIUDAD</td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="celular_c_p" value="<?php echo $row_proveedor['celular_c_p']; ?>" size="20"></td>
            <td id="dato1"><input type="text" name="pais_p" value="<?php echo $row_proveedor['pais_p']; ?>" size="20" onBlur="conMayusculas(this)"></td>
            <td id="dato1"><input type="text" name="dpto_p" value="<?php echo $row_proveedor['dpto_p']; ?>" size="20" onBlur="conMayusculas(this)"></td>
            <td id="dato1"><input type="text" name="ciudad_p" value="<?php echo $row_proveedor['ciudad_p']; ?>" size="20" onBlur="conMayusculas(this)"></td>
          </tr>
          <tr>
            <td id="fuente1">EMAIL</td>
            <td id="fuente1">REGIMEN</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="email_c_p" value="<?php echo $row_proveedor['email_c_p']; ?>" size="20"></td>
            <td id="dato1"><select name="regimen_p">
              <option value="Comun" <?php if (!(strcmp("Comun", $row_proveedor['regimen_p']))) {echo "selected=\"selected\"";} ?>>Comun</option>
              <option value="Simplificado" <?php if (!(strcmp("Simplificado", $row_proveedor['regimen_p']))) {echo "selected=\"selected\"";} ?>>Simplificado</option>
            </select></td>
            <td id="fuente1"><input type="checkbox" name="contribuyentes_p" value="1" <?php if (!(strcmp($row_proveedor['contribuyentes_p'],1))) {echo "checked=\"checked\"";} ?>>
              CONTRIBUYENTES</td>
            <td id="fuente1"><input type="checkbox" name="autoretenedores_p" value="1" <?php if (!(strcmp($row_proveedor['autoretenedores_p'],1))) {echo "checked=\"checked\"";} ?>>
              AUTORETENEDORES</td>
          </tr>
          <tr>
            <td id="fuente1">PRODUCTO / SERVICIO</td>
            <td id="dato1">Insumos</td>
            <td id="fuente1">ESTADO</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato1"><select name="tipo_servicio_p" id="tipo_servicio_p">
              <option value="">N.A.</option>
              <option value="PRODUCTOS"<?php if (!(strcmp("PRODUCTOS", $row_proveedor['tipo_servicio_p']))) {echo "selected=\"selected\"";} ?>>PRODUCTO</option>
              <option value="SERVICIOS"<?php if (!(strcmp("SERVICIOS", $row_proveedor['tipo_servicio_p']))) {echo "selected=\"selected\"";} ?>>SERVICIOS</option>
              <option value="PRODUCTO-SERVICIOS"<?php if (!(strcmp("PRODUCTO-SERVICIOS", $row_proveedor['tipo_servicio_p']))) {echo "selected=\"selected\"";} ?>>PRODUCTO-SERVICIOS</option>
            </select></td>
            <td id="dato1"><p>
              <!--<select name="id_in" id="insumo" style="width:110px">
              <option value=""<?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>INSUMOS</option>
              <?php
do {  
?>
              <option value="<?php echo $row_insumos['id_insumo']?>"<?php if (!(strcmp($row_insumos['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumos['descripcion_insumo']?></option>
              <?php
} while ($row_insumos = mysql_fetch_assoc($insumos));
  $rows = mysql_num_rows($insumos);
  if($rows > 0) {
      mysql_data_seek($insumos, 0);
	  $row_insumos = mysql_fetch_assoc($insumos);
  }
?>
            </select>-->
            </p>
   </td>
            <td id="fuente1"><select name="estado_p" size="1" id="estado_p">
            <option value="ACTIVO"<?php if (!(strcmp("ACTIVO", $row_proveedor['estado_p']))) {echo "selected=\"selected\"";} ?>>ACTIVO</option>
              <option value="INACTIVO"<?php if (!(strcmp("INACTIVO", $row_proveedor['estado_p']))) {echo "selected=\"selected\"";} ?>>INACTIVO</option>
            </select></td>
            <td id="fuente1"><input type="checkbox" name="tipo_provee" value="1" <?php if (!(strcmp($row_proveedor['tipo_provee'],1))) {echo "checked=\"checked\"";} ?>>
              PROVEEDOR SICOQ</td>
          </tr>
          <td colspan="4" id="fuente1">DOCUMENTOS ADJUNTOS 
             <p>
              <hr>
             <p><br> 
             </td> 
          </tr>
          <tr>
            <td colspan="2" nowrap id="detalle1">
                Camara de Comercio (Vigente)<br>
                <input name="camara_comercio_p" type="file" size="20" maxlength="60" class="botones_file">
                <input type="hidden" name="arc1" value="<?php echo $row_proveedor['camara_comercio_p'] ?>"/>
                <a href="javascript:verFoto('archivosc/<?php echo $row_proveedor['camara_comercio_p'] ?>','610','490')"><?php if($row_proveedor['camara_comercio_p']!='') echo "Camara Comercio"; ?></a>
            </td>
              <td colspan="2" nowrap id="detalle1">RUT<br>
              <input name="rut_p" type="file" size="20" maxlength="60" class="botones_file">
              <input type="hidden" name="arc2" value="<?php echo $row_proveedor['rut_p'] ?>"/>
              <a href="javascript:verFoto('archivosc/<?php echo $row_proveedor['rut_p'] ?>','610','490')"> 
              <?php if($row_proveedor['rut_p']!='') echo "Rut"; ?>
              </a>
            </td>
          </tr>
         <tr>
          <td colspan="2" nowrap id="detalle1">Proteccion de Datos<br>
            <input name="datos_proyeccion_p" type="file" size="20" maxlength="60" class="botones_file">
            <input type="hidden" name="arc3" value="<?php echo $row_proveedor['datos_proyeccion_p'] ?>"/>
            <a href="javascript:verFoto('archivosc/<?php echo $row_proveedor['datos_proyeccion_p'] ?>','610','490')">
            <?php if($row_proveedor['datos_proyeccion_p']!='') echo "Proteccion de Datos"?>
            </a>
          </td>
        </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo1">II. INFORMACION DEL PROCESO - PRODUCTO / SERVICIO</td>
            </tr>
          <tr>
            <td colspan="4" id="fuente1">PRODUCTOS O SERVICIOS QUE SUMINISTRA </td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><textarea name="prod_serv_p" cols="80" rows="2"><?php echo $row_proveedor['prod_serv_p']; ?></textarea></td>
            </tr>
			  <?php
 $item=0;
   do {
	?>
    <tr><td colspan="4" id="fuente1"> 
	<?php    
   $item ++;
  echo $item."-".$row_insumos['descripcion_insumo']."<BR>";
  ?>
  </td>
    </tr>
  <?php 
  } while ($row_insumos = mysql_fetch_assoc($insumos));       
    ?>
	<?php if($row_proveedor['tipo_p'] != '2') { ?>
          <tr id="tr1">
            <td colspan="4" id="titulo1">III. ENCUESTA PARA LA CALIFICACION DEL PROVEEDOR <?php if($row_proveedor_seleccion['id_seleccion']=='') { ?>
              <a href="proveedor_seleccion_add.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/mas.gif" alt="ADD ENCUESTA" border="0" style="cursor:hand;"></a> 
              <?php } else { ?>
              <a href="proveedor_seleccion_edit.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/menos.gif" alt="EDITAR ENCUESTA" border="0" style="cursor:hand;"></a>              <?php } ?></td>
            </tr><?php if($row_proveedor_seleccion['id_seleccion']!='') { ?>
          <tr id="tr1">
            <td id="fuente1">CALIFICACION INICIAL (%) </td>
            <td colspan="2" id="fuente2">ENCUESTADOR</td>
            <td id="fuente2">FECHA</td>
          </tr>
          <tr>
            <td id="dato2"><?php echo $row_proveedor_seleccion['primera_calificacion_p']; ?></td>
            <td colspan="2" id="dato2"><?php echo $row_proveedor_seleccion['encuestador_p']; ?></td>
            <td id="dato2"><?php echo $row_proveedor_seleccion['fecha_encuesta_p']; ?></td>
          </tr>
          <tr id="tr1">
            <td id="fuente2">CALIFICACION FINAL (%) </td>
            <td colspan="2" id="fuente2">REGISTRADO POR </td>
            <td id="fuente2">FECHA FINAL </td>
          </tr>
          <tr>
            <td id="dato2"><?php echo $row_proveedor_seleccion['ultima_calificacion_p']; ?></td>
            <td colspan="2" id="dato2"><?php echo $row_proveedor_seleccion['registro_ultima_calificacion']; ?></td>
            <td id="dato2"><?php echo $row_proveedor_seleccion['fecha_ultima_calificacion_p']; ?></td>
          </tr><?php } ?>
          
          <tr>
            <td>&nbsp;</td>
            <td colspan="2" id="dato2">&nbsp;</td>
            <td id="dato2">&nbsp;</td>
          </tr><?php } ?>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2" id="dato2"><input name="submit" class="botonGMini" type="submit" value="EDITA PROVEEDOR"></td>
            <td id="dato2">&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_p" value="<?php echo $row_proveedor['id_p']; ?>">
      </form>
      <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($proveedor);

mysql_free_result($tipo);

mysql_free_result($proveedor_seleccion);
?>
