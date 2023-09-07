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




if (isset($_FILES['rut_p']) && $_FILES['rut_p']['name'] != "") {
  $directorio1 = "archivosc/";
  $nombre1 = str_replace(' ', '',  $_FILES['rut_p']['name']);
  $archivo_temporal1 = $_FILES['rut_p']['tmp_name'];
  if (!copy($archivo_temporal1,$directorio1.$nombre1)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen1 = "archivosc/".$nombre1; }
}
if (isset($_FILES['camara_comercio_p']) && $_FILES['camara_comercio_p']['name'] != "") {
  $directorio2 = "archivosc/";
  $nombre2 = str_replace(' ', '',  $_FILES['camara_comercio_p']['name']);
  $archivo_temporal2 = $_FILES['camara_comercio_p']['tmp_name'];
  if (!copy($archivo_temporal2,$directorio2.$nombre2)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen2 = "archivosc/".$nombre2; }
}
if (isset($_FILES['datos_proyeccion_p']) && $_FILES['datos_proyeccion_p']['name'] != "") {
  $directorio3 = "archivosc/";
  $nombre3 = str_replace(' ', '',  $_FILES['datos_proyeccion_p']['name']);
  $archivo_temporal3 = $_FILES['datos_proyeccion_p']['tmp_name'];
  if (!copy($archivo_temporal3,$directorio3.$nombre3)) {
    $error = "Error al enviar el Archivo";
  } else { $imagen3 = "archivosc/".$nombre3; }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO proveedor (id_p, nit_p, proveedor_p, tipo_p, tipo_servicio_p, direccion_p, pais_p, dpto_p, ciudad_p, telefono_p, fax_p, contacto_p, celular_c_p, email_c_p, contribuyentes_p, autoretenedores_p, regimen_p, prod_serv_p, registro_p, fecha_registro_p, modificacion_p, fecha_modif_p,estado_p,camara_comercio_p, rut_p, datos_proyeccion_p,tipo_provee) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['id_p'], "int"),
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
   GetSQLValueString($_POST['contribuyentes_p'], "int"),
   GetSQLValueString($_POST['autoretenedores_p'], "int"),
   GetSQLValueString($_POST['regimen_p'], "text"),
   GetSQLValueString($_POST['prod_serv_p'], "text"),
   GetSQLValueString($_POST['registro_p'], "text"),
   GetSQLValueString($_POST['fecha_registro_p'], "date"),
   GetSQLValueString($_POST['modificacion_p'], "text"),
   GetSQLValueString($_POST['fecha_modif_p'], "date"),
   GetSQLValueString($_POST['estado_p'], "text"),
   GetSQLValueString($nombre2, "text"),
   GetSQLValueString($nombre1, "text"),
   GetSQLValueString($nombre3, "text"),
   GetSQLValueString($_POST['tipo_provee'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertSQL2 = sprintf("INSERT INTO TblProveedorInsumo (id_p,id_in) VALUES (%s, %s)",
   GetSQLValueString($_POST['id_p'],"int"),
   GetSQLValueString($_POST['id_in'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
  
  $insertGoTo = "proveedor_seleccion_add.php?id_p=" . $_POST['id_p'] . "&tipo_p=" . $_POST['tipo_p'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_ultimo = $conexion->llenarCampos('proveedor',"","ORDER BY id_p DESC","id_p"); 

$row_tipo = $conexion->llenaSelect('tipo',"","ORDER BY nombre_tipo ASC");  

$row_insumos = $conexion->llenaSelect('insumo',"","ORDER BY id_insumo ASC");  

 
?><html>
<head>
  <title>SISADGE AC &amp; CIA</title>
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
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>"  enctype="multipart/form-data" onSubmit="MM_validateForm('fecha_registro_p','','R','registro_p','','R','proveedor_p','','R','nit_p','','R','direccion_p','','R','contacto_p','','R','telefono_p','','R','fax_p','','R','pais_p','','R','ciudad_p','','R','email_c_p','','NisEmail','id_in','','R' );return document.MM_returnValue;">
    <table id="tabla2">
      <tr id="tr1">
        <td id="codigo">CODIGO : A3-F03 </td>
        <td colspan="2" id="titulo2">ADD PROVEEDOR </td>
        <td id="codigo">VERSION : 0 </td>
      </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="2" id="numero2">N&deg;
         <input name="id_p" type="hidden" value="<?php $num=$row_ultimo['id_p']+1; echo $num; ?>"><?php echo $num;  ?></td>
         <td id="dato2"><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a><a href="proveedor_insumo.php" target="_top"><img src="images/cliente.gif" alt="ADD INSUMO" title="ADD INSUMO" border="0" style="cursor:hand;"/></a></td>
       </tr>
       <tr>
        <td id="fuente1">FECHA DE REGISTRO </td>
        <td colspan="2" id="fuente1">REGISTRADO POR </td>
      </tr>
      <tr>
        <td id="dato1"><input name="fecha_registro_p" type="date" required id="fecha_registro_p" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10"/></td>
        <td colspan="2" id="dato1"><input type="text" name="registro_p" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30"></td>
      </tr>
      <tr>
        <td id="fuente1">FECHA MODIFICACION </td>
        <td colspan="2" id="fuente1">MODIFICADO POR </td>
      </tr>
      <tr>
        <td id="dato1"><input name="fecha_modif_p" type="date" required id="fecha_modif_p" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10"/></td>
        <td colspan="2" id="dato1"><input type="text" name="modificacion_p" onBlur="conMayusculas(this)" value="" size="30"></td>
      </tr>
      <tr>
        <td colspan="3" id="dato2">Si el proveedor es Critico, se procede a evaluar para su calificacion. </td>
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
        <td colspan="2"><input type="text" required name="proveedor_p" value="" size="50" onBlur="conMayusculas(this)" onChange="if (form1.proveedor_p.value) { DatosGestiones('3','proveedor_p',form1.proveedor_p.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); }"></td>
        <td><input type="text" name="nit_p" value="" size="20" required onChange="if (form1.nit_p.value) { DatosGestiones('3','nit_p',form1.nit_p.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); }"></td>
        <td>
          <select name="tipo_p" id="tipo_p"  class="busqueda selectsMedio">
            <option value="0">O.P</option>
            <?php  foreach($row_tipo as $row_tipo ) { ?>
              <option value="<?php echo $row_tipo['id_tipo']?>"><?php echo $row_tipo['nombre_tipo']?></option>
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
      <td colspan="4" id="dato1"><input type="text" name="direccion_p" value="" size="100" onBlur="conMayusculas(this)"></td>
    </tr>
    <tr>
      <td colspan="2" id="fuente1">CONTACTO COMERCIAL </td>
      <td id="fuente1">TELEFONO</td>
      <td id="fuente1">FAX</td>
    </tr>
    <tr>
      <td colspan="2" id="dato1"><input type="text" name="contacto_p" value="" size="50" onBlur="conMayusculas(this)"></td>
      <td id="dato1"><input type="text" name="telefono_p" value="" size="20"></td>
      <td id="dato1"><input type="text" name="fax_p" value="" size="20"></td>
    </tr>
    <tr>
      <td id="fuente1">CELULAR</td>
      <td id="fuente1">PAIS</td>
      <td id="fuente1">PROVINCIA</td>
      <td id="fuente1">CIUDAD</td>
    </tr>
    <tr>
      <td id="dato1"><input type="text" name="celular_c_p" value="" size="20"></td>
      <td id="dato1"><input type="text" name="pais_p" value="" size="20" onBlur="conMayusculas(this)"></td>
      <td id="dato1"><input type="text" name="dpto_p" value="" size="20" onBlur="conMayusculas(this)"></td>
      <td id="dato1"><input type="text" name="ciudad_p" value="" size="20" onBlur="conMayusculas(this)"></td>
    </tr>
    <tr>
      <td id="fuente1">EMAIL</td>
      <td id="fuente1">REGIMEN</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
    </tr>
    <tr>
      <td id="dato1"><input type="text" name="email_c_p" value="" size="20"></td>
      <td id="dato1"><select name="regimen_p">
        <option value="Comun">Comun</option>
        <option value="Simplificado">Simplificado</option>
      </select></td>
      <td id="fuente1"><input name="contribuyentes_p" type="checkbox" value="1">
      CONTRIBUYENTES</td>
      <td id="fuente1"><input name="autoretenedores_p" type="checkbox" value="1">
      AUTORETENEDORES</td>
    </tr>
    <tr>
      <td id="fuente1">PRODUCTO / SERVICIO</td>
      <td id="dato1">INSUMO</td>
      <td id="fuente1">ESTADO</td>
      <td id="fuente1">&nbsp;</td>
    </tr>
    <tr>
      <td id="dato1"><select name="tipo_servicio_p" id="tipo_servicio_p">
        <option value="">N.A.</option>
        <option value="PRODUCTOS">PRODUCTO</option>
        <option value="SERVICIOS">SERVICIOS</option>
        <option value="PRODUCTO-SERVICIOS">PRODUCTO-SERVICIOS</option>
      </select></td>
      <td id="dato1">
          <select name="id_in" id="insumo"  class="busqueda selectsMedio">
            <option value="">INSUMOS</option>
            <?php  foreach($row_insumos as $row_insumos ) { ?>
              <option value="<?php echo $row_insumos['id_insumo']?>"><?php echo $row_insumos['descripcion_insumo']?></option>
            </option>
          <?php } ?>
        </select>
      </td>
      <td id="fuente1">
        <select name="estado_p" size="1" id="estado_p">
          <option value="ACTIVO" selected>ACTIVO</option>
          <option value="INACTIVO">INACTIVO</option>
        </select><br><br></td>
        <td id="fuente1"><input name="tipo_provee" type="checkbox" value="1">PROVEEDOR SICOQ</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente1">DOCUMENTOS ADJUNTOS 
         <p>
          <hr>
          <p><br> 
          </td> 
        </tr>
        <tr>
          <td colspan="2" nowrap id="dato1">Rut<br>
            <input name="rut_p" type="file" size="20" maxlength="60" class="botones_file">
          </td>
          <td colspan="2" nowrap id="dato1">Camara de Comercio (Vigente)<br>
            <input name="camara_comercio_p" type="file" size="20" maxlength="60" class="botones_file">
          </td>
        </tr>
        <tr>
          <td colspan="2" nowrap id="dato1">Proteccion de Datos <br>
           <input name="datos_proyeccion_p" type="file" size="20" maxlength="60" class="botones_file"><br><br>
         </td>
       </tr>
       <tr>
        <td colspan="4" id="titulo1">II. INFORMACION DEL PROCESO - PRODUCTO / SERVICIO</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente1">PRODUCTOS O SERVICIOS QUE SUMINISTRA </td>
      </tr>
      <tr>
        <td colspan="4" id="dato1"><textarea name="prod_serv_p" placeholder="descripcion del producto o servicio" cols="80" rows="2"></textarea></td>
      </tr>
      <tr>
        <td colspan="4" id="dato2"><input class="botonGMini" type="submit" value="ADD PROVEEDOR"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form> 
  <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($tipo);
?>
