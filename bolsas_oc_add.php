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
  $insertSQL = sprintf("INSERT INTO orden_compra_bolsas (n_ocb, id_p_ocb, id_bolsa_ocb, id_ref_ocb, fecha_pedido_ocb, fecha_entrega_ocb, pedido_ocb, registrado_ocb) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_ocb'], "int"),
                       GetSQLValueString($_POST['id_p_ocb'], "int"),
                       GetSQLValueString($_POST['id_bolsa_ocb'], "int"),
                       GetSQLValueString($_POST['id_ref_ocb'], "int"),
                       GetSQLValueString($_POST['fecha_pedido_ocb'], "date"),
                       GetSQLValueString($_POST['fecha_entrega_ocb'], "date"),
                       GetSQLValueString($_POST['pedido_ocb'], "int"),
                       GetSQLValueString($_POST['registrado_ocb'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "bolsas_oc_edit.php?n_ocb=" . $_POST['n_ocb'] . "";
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
$query_ultimo = "SELECT * FROM orden_compra_bolsas ORDER BY n_ocb DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

mysql_select_db($database_conexion1, $conexion1);
$query_bolsas = "SELECT * FROM material_terminado_bolsas ORDER BY nombre_bolsa ASC";
$bolsas = mysql_query($query_bolsas, $conexion1) or die(mysql_error());
$row_bolsas = mysql_fetch_assoc($bolsas);
$totalRows_bolsas = mysql_num_rows($bolsas);

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
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_pedido_ocb','','R','fecha_entrega_ocb','','R','pedido_ocb','','R','id_p_ocb','','R','id_bolsa_ocb','','R','id_ref_ocb','','R','registrado_ocb','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr1">            
            <td id="codigo" width="25%">CODIGO : A3 - F01 </td>
            <td colspan="2" id="titulo2" width="50%">ORDEN DE COMPRA </td>
            <td id="codigo" width="25%">VERSION : 1 </td>
		</tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td colspan="2" id="subtitulo">PRODUCTO TERMINADO (BOLSA) </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="numero2"><strong>N° <?php $num=$row_ultimo['n_ocb']+1; echo $num; ?> 
              <input name="n_ocb" type="hidden" value="<?php echo $num; ?>">
            </strong></td>
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="bolsas_oc.php"><img src="images/o.gif" alt="O.C. BOLSAS" border="0" style="cursor:hand;"/></a><a href="bolsas.php"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
            </tr>
          <tr>
            <td id="fuente1">FECHA DE PEDIDO </td>
            <td id="fuente1">FECHA DE ENTREGA </td>
            <td id="fuente1">CLASE DE PEDIDO</td>
          </tr>
          <tr>
            <td><input type="text" name="fecha_pedido_ocb" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td><input type="text" name="fecha_entrega_ocb" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td><select name="pedido_ocb">
              <option value="0">Nuevo</option>
              <option value="1">Reimpresion</option>
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
            <td colspan="4" id="dato1"><select name="id_p_ocb">
              <option value="">PROVEEDOR</option>
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
            <td colspan="4" id="fuente1">NOMBRE DE LA BOLSA </td>
          </tr>
          <tr>
            <td colspan="4" id="dato1"><select name="id_bolsa_ocb">
              <option value="">BOLSA</option>
              <?php
do {  
?>
              <option value="<?php echo $row_bolsas['id_bolsa']?>"><?php echo $row_bolsas['nombre_bolsa']?></option>
              <?php
} while ($row_bolsas = mysql_fetch_assoc($bolsas));
  $rows = mysql_num_rows($bolsas);
  if($rows > 0) {
      mysql_data_seek($bolsas, 0);
	  $row_bolsas = mysql_fetch_assoc($bolsas);
  }
?>
                                    </select></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">REFERENCIA DEL PRODUCTO </td>
            <td colspan="2" id="fuente1">RESPONSABLE DE REGISTRO </td>
            </tr>
          <tr>
            <td colspan="2"><select name="id_ref_ocb">
              <option value="">REF</option>
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
            <td colspan="2"><input type="text" name="registrado_ocb" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="32"></td>
            </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="ADD O.C. (BOLSA)"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
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
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($proveedores);

mysql_free_result($bolsas);

mysql_free_result($referencias);
?>