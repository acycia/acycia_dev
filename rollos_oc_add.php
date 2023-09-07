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
  $insertSQL = sprintf("INSERT INTO orden_compra_rollos (n_ocr, id_p_ocr, id_rollo_ocr, id_ref_ocr, fecha_pedido_ocr, fecha_entrega_ocr, pedido_ocr, elaboro_ocr) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_ocr'], "int"),
                       GetSQLValueString($_POST['id_p_ocr'], "int"),
                       GetSQLValueString($_POST['id_rollo_ocr'], "int"),
                       GetSQLValueString($_POST['id_ref_ocr'], "int"),
                       GetSQLValueString($_POST['fecha_pedido_ocr'], "date"),
                       GetSQLValueString($_POST['fecha_entrega_ocr'], "date"),
                       GetSQLValueString($_POST['pedido_ocr'], "text"),
                       GetSQLValueString($_POST['elaboro_ocr'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "rollos_oc_edit.php?n_ocr=" . $_POST['n_ocr'] . "";
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

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM orden_compra_rollos ORDER BY n_ocr DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

mysql_select_db($database_conexion1, $conexion1);
$query_rollos = "SELECT * FROM materia_prima_rollos ORDER BY nombre_rollo ASC";
$rollos = mysql_query($query_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);
$totalRows_rollos = mysql_num_rows($rollos);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);
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
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_entrega_ocr','','R','fecha_pedido_ocr','','R','pedido_ocr','','R','id_p_ocr','','R','id_rollo_ocr','','R','id_ref_ocr','','R','elaboro_ocr','','R');return document.MM_returnValue">
        <table id="tabla2">
		<tr id="tr1">            
            <td id="codigo" width="25%">CODIGO : A3 - F01 </td>
            <td colspan="2" id="titulo2" width="50%">ORDEN DE COMPRA </td>
            <td id="codigo" width="25%">VERSION : 1 </td>
		</tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td colspan="2" id="subtitulo">MATERIA PRIMA ( ROLLOS )</td>
            <td id="titulo2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="numero2"><strong>N &deg;
                <?php $num=$row_ultimo['n_ocr']+1; echo $num; ?>
            </strong>
              <input name="n_ocr" type="hidden" value="<?php echo $num; ?>"></td>
            <td id="subtitulo"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="rollos_oc.php"><img src="images/o.gif" alt="O.C. ROLLOS" border="0" style="cursor:hand;"/></a><a href="rollos.php"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="3" id="numero2">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente2">FECHA DE PEDIDO </td>
            <td id="fuente2">FECHA DE ENTREGA </td>
            <td id="fuente2">CLASE DE PEDIDO</td>
            </tr>
          <tr>
            <td id="dato2"><input type="text" name="fecha_entrega_ocr" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td id="dato2"><input type="text" name="fecha_pedido_ocr" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td id="dato2"><select name="pedido_ocr">
                <option value="Nuevo">Nuevo</option>
                <option value="Repeticion">Repeticion</option>
            </select></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"> Todos los campos son obligatorios, para completar los datos especificos de la O.C. </td>
            </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="fuente1">PROVEEDOR</td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><select name="id_p_ocr">
              <option value="">SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_proveedores['id_p']?>"><?php echo $row_proveedores['proveedor_p']?></option>
              <?php
} while ($row_proveedores = mysql_fetch_assoc($proveedores));
  $rows = mysql_num_rows($proveedores);
  if($rows > 0) {
      mysql_data_seek($proveedores, 0);
	  $row_proveedores = mysql_fetch_assoc($proveedores);
  }
?>
                        </select></td>
            </tr>
          <tr>
            <td colspan="4" id="fuente1">MATERIAL SOLICITADO ( ROLLO  ) </td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><select name="id_rollo_ocr">
              <option value="" >SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rollos['id_rollo']?>"><?php echo $row_rollos['nombre_rollo']?></option>
              <?php
} while ($row_rollos = mysql_fetch_assoc($rollos));
  $rows = mysql_num_rows($rollos);
  if($rows > 0) {
      mysql_data_seek($rollos, 0);
	  $row_rollos = mysql_fetch_assoc($rollos);
  }
?>
            </select></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">REFERENCIA DEL PRODUCTO </td>
            <td colspan="2" id="fuente1">RESPONSABLE</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="id_ref_ocr">
              <option value="" >SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?></option>
              <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
                        </select></td>
            <td colspan="2" id="dato1"><input type="text" name="elaboro_ocr" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="4" id="fuente2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="fuente2"><input type="submit" value="ADD O.C. ( ROLLO )"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td>
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

mysql_free_result($ultimo);

mysql_free_result($proveedores);

mysql_free_result($rollos);

mysql_free_result($referencias);
?>