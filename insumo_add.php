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
  $insertSQL = sprintf("INSERT INTO insumo (id_insumo, codigo_insumo, descripcion_insumo, clase_insumo, medida_insumo, tipo_insumo, valor_unitario_insumo, stok_insumo,quimicos) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_insumo'], "int"),
                       GetSQLValueString($_POST['codigo_insumo'], "text"),
                       GetSQLValueString($_POST['descripcion_insumo'], "text"),
                       GetSQLValueString($_POST['clase_insumo'], "int"),
                       GetSQLValueString($_POST['medida_insumo'], "text"),
                       GetSQLValueString($_POST['tipo_insumo'], "text"),
                       GetSQLValueString($_POST['valor_unitario_insumo'], "double"),
					             GetSQLValueString($_POST['stok_insumo'], "int"),
                       GetSQLValueString($_POST['quimicos'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertSQL2 = sprintf("INSERT INTO TblProveedorInsumo (id_p,id_in) VALUES (%s, %s)",
                       GetSQLValueString($_POST['id_p'],"int"),
                       GetSQLValueString($_POST['id_insumo'], "int"));
   mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());

	//SI EXISTE EL INSUMO O MATERIA PRIMA SE ACTUALIZA CANTIDAD DE ENTRANDA
	      $fecha=$_POST['Fecha'] ;  
		  $codigo=$_POST['id_insumo']; 
		  $costoUnd=$_POST['valor_unitario_insumo'];
		  $acep=$_POST['acep'];
		  $tipo='2';
		  $responsable=$_POST['responsable'];
		$sqling="SELECT Codigo FROM TblInventarioListado WHERE Codigo = '$codigo'";
		$resulting= mysql_query($sqling);
		$numing= mysql_num_rows($resulting);
		if($numing >='1') {
		$sqlinv="UPDATE TblInventarioListado SET Fecha='$fecha', Cod_ref='$codigo', Codigo='$codigo', SaldoInicial='0', CostoUnd='$costoUnd', Acep='0', Tipo='$tipo', Modifico='$responsable' WHERE Codigo = '$codigo'";
		  mysql_select_db($database_conexion1, $conexion1);
		  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());   
		  }else{
		  $sqlinv="INSERT INTO TblInventarioListado (Fecha, Cod_ref, Codigo, SaldoInicial,  CostoUnd, Acep, Tipo, Responsable) VALUES ( '$fecha', '$codigo', '$codigo', '0', '$costoUnd', '0', '$tipo', '$responsable')";
		  mysql_select_db($database_conexion1, $conexion1);
		  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());  
		  }
    
  $insertGoTo = "insumos.php";
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
$query_tipos = "SELECT * FROM tipo ORDER BY nombre_tipo ASC";
$tipos = mysql_query($query_tipos, $conexion1) or die(mysql_error());
$row_tipos = mysql_fetch_assoc($tipos);
$totalRows_tipos = mysql_num_rows($tipos);

mysql_select_db($database_conexion1, $conexion1);
$query_clases = "SELECT * FROM clase ORDER BY nombre_clase ASC";
$clases = mysql_query($query_clases, $conexion1) or die(mysql_error());
$row_clases = mysql_fetch_assoc($clases);
$totalRows_clases = mysql_num_rows($clases);

mysql_select_db($database_conexion1, $conexion1);
$query_medidas = "SELECT * FROM medida ORDER BY nombre_medida ASC";
$medidas = mysql_query($query_medidas, $conexion1) or die(mysql_error());
$row_medidas = mysql_fetch_assoc($medidas);
$totalRows_medidas = mysql_num_rows($medidas);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT codigo_insumo FROM insumo ORDER BY codigo_insumo DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo2 = "SELECT id_insumo FROM insumo ORDER BY id_insumo DESC";
$ultimo2 = mysql_query($query_ultimo2, $conexion1) or die(mysql_error());
$row_ultimo2 = mysql_fetch_assoc($ultimo2);
$totalRows_ultimo2 = mysql_num_rows($ultimo2);


mysql_select_db($database_conexion1, $conexion1);
//SELECT * FROM proveedor WHERE proveedor.id_p NOT IN(SELECT TblProveedorInsumo.id_p FROM TblProveedorInsumo ) ORDER BY proveedor_p AS
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="MM_validateForm('id_p','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr>
            <td rowspan="7" id="fuente2"><img src="images/logoacyc.jpg"></td>
            <td id="subtitulo">* ADD NUEVO INSUMO</td>
            <td id="dato2"><a href="insumos.php"><img src="images/cat.gif" alt="INSUMOS" border="0" style="cursor:hand;"></a><a href="insumos_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"></a><img src="images/ciclo1.gif" onClick="window.history.go()" style="cursor:hand;" ><a href="proveedor_insumo.php" target="_top"><img src="images/cliente.gif" alt="ADD PROVEEDOR" title="ADD PROVEEDOR" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_insumo" type="hidden" value="<?php echo $row_ultimo2['id_insumo']+1; ?>">
              CODIGO DEL INSUMO </td>
            <td id="fuente1">TIPO DE INSUMO </td>
          </tr>
          <tr>
            <td id="fuente1"><input type="number" min="1" step="1" style="width:100px" name="codigo_insumo" value="<?php echo $row_ultimo['codigo_insumo']+1; ?>" required ></td>
            <td id="fuente1"><select name="tipo_insumo" id="tipo_insumo">
              <?php
do {  
?>
              <option value="<?php echo $row_tipos['id_tipo']?>"><?php echo $row_tipos['nombre_tipo']?></option>
              <?php
} while ($row_tipos = mysql_fetch_assoc($tipos));
  $rows = mysql_num_rows($tipos);
  if($rows > 0) {
      mysql_data_seek($tipos, 0);
	  $row_tipos = mysql_fetch_assoc($tipos);
  }
?>
              </select></td>
          </tr>
          <tr>
            <td id="fuente1">DESCRIPCION</td>
            <td id="fuente1">CLASE DE INSUMO </td>
          </tr>
          <tr>
            <td id="dato1"><input name="descripcion_insumo" type="text" value="" required size="30" onChange="MayusculaSinEspacios(this)"></td>
            <td id="dato1"><select name="clase_insumo" id="clase_insumo">
                <?php
do {  
?>
                <option value="<?php echo $row_clases['id_clase']?>"><?php echo $row_clases['nombre_clase']?></option>
                <?php
} while ($row_clases = mysql_fetch_assoc($clases));
  $rows = mysql_num_rows($clases);
  if($rows > 0) {
      mysql_data_seek($clases, 0);
	  $row_clases = mysql_fetch_assoc($clases);
  }
?>
              </select></td>
          </tr>
          <tr>
            <td id="fuente1">UNIDAD DE MEDIDA</td>
            <td id="fuente1">VALOR UNITARIO </td>
            <td id="fuente1">QUIMICOS</td>
          </tr>          
          <tr>
            <td id="dato1"><select name="medida_insumo" id="medida_insumo">
              <?php
                do {  
                ?>
                <option value="<?php echo $row_medidas['id_medida']?>"><?php echo $row_medidas['nombre_medida']?></option>
                 <?php
                } while ($row_medidas = mysql_fetch_assoc($medidas));
                  $rows = mysql_num_rows($medidas);
                  if($rows > 0) {
                      mysql_data_seek($medidas, 0);
                	  $row_medidas = mysql_fetch_assoc($medidas);
                  }
                ?>
              </select>
            </td>
            <td id="dato1"><input type="text" name="valor_unitario_insumo" value="" required size="10"></td>
            <td id="dato1">
              <select name="quimicos" id="quimicos">
               <option value="0">SELECCIONE...</option>
                <option value="SUSTANCIAS QUIMICAS">SUSTANCIAS QUIMICAS</option>
                <option value="NA">NA</option>    
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1"><strong>PROVEEDOR:</strong></td>
            <td id="dato1">STOCK DEL PRODUCTO / KG</td>
          </tr>
          <tr>
            <td colspan="2" id="fuente4"><select name="id_p" id="id_p" style="width:220px">
      <option value="0">PROVEEDOR</option>
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
            <td id="dato1"><input type="number" name="stok_insumo" id="stok_insumo" min="0"step="any" required value="" style=" width:100px" /></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente3"><input name="Fecha" id="Fecha" type="hidden" required min="2015-01-01" value="<?php echo date ('Y-m-d'); ?>" style="width:150px"/>
              <input name="responsable" id="responsable" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" /></td>
            <td id="dato3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="ADD INSUMO"></td>
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

mysql_free_result($tipos);

mysql_free_result($clases);

mysql_free_result($medidas);
?>
