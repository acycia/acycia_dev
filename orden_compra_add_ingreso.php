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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$conexion = new ApptivaDB();


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TblIngresos (id_det_ing, oc_ing, id_insumo_ing, ingreso_ing, valor_und_ing, fecha_ing) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_det_ing'], "int"),
                       GetSQLValueString($_POST['oc_ing'], "int"),
                       GetSQLValueString($_POST['id_insumo_ing'], "int"),
                       GetSQLValueString($_POST['ingreso_ing'], "double"),
                       GetSQLValueString($_POST['valor_und_ing'], "double"),
                       GetSQLValueString($_POST['fecha_ing'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

//ACTUALIZA EL VALOR UNITARIO EN INSUMOS ESTO FUE AGREGADO RECIENTEMENTE
  $updateSQL = sprintf("UPDATE insumo SET valor_unitario_insumo=%s WHERE id_insumo=%s",
                       GetSQLValueString($_POST['valor_und_ing'], "double"),
                       GetSQLValueString($_POST['id_insumo_ing'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

//SI EXISTE EL INSUMO O MATERIA PRIMA SE ACTUALIZA CANTIDAD DE ENTRANDA
	$sqling="SELECT Codigo FROM TblInventarioListado WHERE Codigo = '".$_POST['id_insumo_ing']."'";
	$resulting= mysql_query($sqling);
	$numing= mysql_num_rows($resulting);
	if($numing >='1') {
    $updateSQL3 = sprintf("UPDATE TblInventarioListado SET Fecha=%s, Entrada=Entrada + %s, Modifico=%s WHERE Codigo = '".$_POST['id_insumo_ing']."'",
	                   GetSQLValueString($_POST['fecha_ing'], "date"),
					   GetSQLValueString($_POST['ingreso_ing'], "int"),
					   GetSQLValueString($_POST['responsable'], "text")
					   );

  mysql_select_db($database_conexion1, $conexion1);
  $ResultUpdate1 = mysql_query($updateSQL3, $conexion1) or die(mysql_error());
	}else{
  $sqlinv="INSERT INTO TblInventarioListado (Fecha, Cod_ref, Codigo,  Entrada, Salida, CostoUnd, Acep, Tipo, Responsable) VALUES ('".$_POST['fecha_ing']."', '".$_POST['id_insumo_ing']."', '".$_POST['id_insumo_ing']."', '".$_POST['ingreso_ing']."', '0', '".$_POST['valor_und_ing']."', '0','2', '".$_POST['responsable']."')";
  mysql_select_db($database_conexion1, $conexion1);
  $ResultUpdate2 = mysql_query($sqlinv, $conexion1) or die(mysql_error());  
  	}
	echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
/*echo "<script type=\"text/javascript\">window.close();</script>";  */
  $updateGoTo = "orden_compra_add_ingreso.php?id_det=" . $_POST['id_det'] . "";
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

$colname_detalle = "-1";
if (isset($_GET['id_det'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM orden_compra_detalle WHERE id_det = %s", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);

$colname_orden_compra = "-1";
if (isset($_GET['id_det'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra_detalle, orden_compra WHERE orden_compra_detalle.id_det = '%s' AND orden_compra_detalle.n_oc_det = orden_compra.n_oc", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_inventario = "-1";
if (isset($_GET['id_det'])) {
  $colname_inventario = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_inventario = sprintf("SELECT SUM(TblIngresos.ingreso_ing) AS ingreso,SUM(TblIngresos.salida_ing) AS salida FROM orden_compra_detalle, TblIngresos WHERE orden_compra_detalle.id_det = %s AND orden_compra_detalle.id_det=TblIngresos.id_det_ing", $colname_inventario);
$inventario = mysql_query($query_inventario, $conexion1) or die(mysql_error());
$row_inventario = mysql_fetch_assoc($inventario);
$totalRows_inventario = mysql_num_rows($inventario);

//LISTADO DE INVENTARIOS
$colname_inventarioList = "-1";
if (isset($_GET['id_det'])) {
  $colname_inventarioList = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_inventarioList = sprintf("SELECT (TblInventarioListado.SaldoInicial + TblInventarioListado.Entrada - TblInventarioListado.Salida) AS saldo FROM TblInventarioListado,orden_compra_detalle WHERE orden_compra_detalle.id_det = %s AND orden_compra_detalle.id_insumo_det = TblInventarioListado.Codigo ORDER BY orden_compra_detalle.id_insumo_det DESC", $colname_inventarioList);
$inventarioList = mysql_query($query_inventarioList, $conexion1) or die(mysql_error());
$row_inventarioList = mysql_fetch_assoc($inventarioList);
$totalRows_inventarioList = mysql_num_rows($inventarioList);

//CONSULTA INGRESOS POR INSUMO
$colname_ingresos = "-1";
if (isset($_GET['id_det'])) {
  $colname_ingresos = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ingresos = sprintf("SELECT * FROM TblIngresos WHERE id_det_ing = %s", $colname_ingresos);
$ingresos = mysql_query($query_ingresos, $conexion1) or die(mysql_error());
$row_ingresos = mysql_fetch_assoc($ingresos);
$totalRows_ingresos = mysql_num_rows($ingresos);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>

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

<!-- Select3 Nuevo -->
<meta charset="UTF-8">
<!-- jQuery -->
<script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

<!-- select2 css -->
<link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

<!-- select2 script -->
<script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
<!-- Styles -->
<link rel="stylesheet" href="select3/assets/css/style.css">
<!-- Fin Select3 Nuevo -->

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return detalle_ing();">
        <table class="table table-bordered table-sm">
          <tr>
            <td colspan="4" id="subtitulo">ADD ENTRADA</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">ORDEN DE COMPRA N&deg; <strong><?php echo $row_orden_compra['n_oc']; ?></strong><input name="oc_ing" type="hidden" value="<?php echo $row_orden_compra['n_oc']; ?>">
              <input type="hidden" name="fecha_ing" value="<?php echo date("Y-m-d");?>" size="5">
              <input name="id_insumo_ing" type="hidden" id="id_insumo_ing" value="<?php echo $row_detalle['id_insumo_det']; ?>" size="5">
              <input name="responsable" type="hidden" id="responsable" value="<?php echo $row_usuario['nombre_usuario']; ?>"></td>
            <td colspan="2" id="fuente3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
            </tr>
          
          <tr>
            <td colspan="4" id="fuente1"><strong>INSUMO</strong></td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><input name="id_det_ing" type="hidden" id="id_det_ing" value="<?php echo $row_detalle['id_det']; ?>">
			<?php 
			$insumo=$row_detalle['id_insumo_det'];
			$sqlins="SELECT codigo_insumo,descripcion_insumo,medida_insumo FROM insumo WHERE id_insumo ='$insumo'";
			$resultins= mysql_query($sqlins);
			$numins= mysql_num_rows($resultins);
			if($numins >='1')
			{ 
			$insumo_nombre = mysql_result($resultins,0,'descripcion_insumo');
			$insumo_medida = mysql_result($resultins,0,'medida_insumo');
			$codigo_insumo = mysql_result($resultins,0,'codigo_insumo');
			echo $insumo_nombre;
			 } ?> </td>
            </tr>

          <tr>
            <td id="dato1"><strong>CANT. INGRESADAS: </strong></td>
            <td id="dato1"><strong>CANT. SOLICITADA:</strong></td>
            <td id="dato1"><strong>INGRESO:</strong></td>
            <td id="dato1"><strong>VALOR EN 
              <?php $medida=$insumo_medida;
			if($medida!='')
			{
			$sqlmedida="SELECT nombre_medida FROM medida WHERE medida.id_medida ='$medida'";
			$resultmedida= mysql_query($sqlmedida);
			$numedida= mysql_num_rows($resultmedida);
			if($numedida >='1')
			{ 
			$nombre_medida = mysql_result($resultmedida,0,'nombre_medida');
			echo $nombre_medida;
			} }
			
			$id_insumo=$row_detalle['id_insumo_det'];
			$sqlvalorins="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo ='$id_insumo'";
			$resultvalorins = mysql_query($sqlvalorins);
			$numvalorins= mysql_num_rows($resultvalorins);
			if($numvalorins >='1')
			{ 
			$valor_insumo = mysql_result($resultvalorins,0,'valor_unitario_insumo'); 
			} ?></strong></td>
            </tr>
          <tr>
            <td id="dato1"><?php $ingresado=($row_inventario['ingreso']); echo $ingresado;?>
         <input name="inventario" type="hidden" id="inventario" value="<?php   if($ingresado==''){echo '0';}else{echo $ingresado;}?>" size="5">
              </td>
            <td id="dato1"><input name="existente" type="hidden" id="existente" value="<?php if($row_inventario['ingreso']==''){echo '0';}else{echo $row_inventario['ingreso'];}?>" size="5">
            <input name="cantidad" type="hidden" id="cantidad" value="<?php if($row_detalle['cantidad_det']==''){echo '0';}else{echo $row_detalle['cantidad_det'];} ?>" size="5">
              <?php echo $row_detalle['cantidad_det']; ?></td>
            <td id="dato1"><input name="ingreso_ing" type="number" id="ingreso_ing" placeholder="0,00" style="width:100px" min="0.00" step="0.01" value="" required onBlur="detalle_ing();"></td>
            <td id="dato1"><input type="number" name="valor_und_ing" value="<?php echo $valor_insumo; ?>"placeholder="0.0001" style="width:100px" min="0.0000" step="0.0001" required onBlur="detalle_ing();">
            <?php echo $row_detalle['moneda_det']; ?>&nbsp;&nbsp;<span style="color: red;" >Ojo este valor modifica el insumo</span>
              </td>
          </tr>
          <tr>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">TOTALCOMPRA:</td>
            <td id="dato1"><input type="number" name="total_det" value="" placeholder="0,00" style="width:100px" min="0.00" step="0.01" required onBlur="detalle_ing();"></td>
          </tr>
          <tr>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">TOTAL INVENTARIO:.</td>
            <td id="dato1"> <?php $inventario=$row_inventarioList['saldo']; if($inventario==''){echo '0';}else{echo $inventario;}?></td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="ADD ENTRADA"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td>
  </tr>
  <?php if($row_ingresos['id_ing']!=''){?>
  <tr>
   <td colspan="2" align="center">
   <form action="delete_listado.php" method="get" name="seleccion">
   <table id="tabla2">
   <tr id="tr1">
     <td colspan="5" id="titulo4">INGRESOS </td>
     </tr>
   <tr>
    <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="45" />
      <input name="Input" type="submit" value="Delete"/>
      <input name="iddet" type="hidden" id="iddet" value="<?php echo $_GET['id_det'] ?>" />
      </td>
      <td colspan="3"><?php $id=$_GET['id']; 
      if($id == '1') { ?><div id="acceso1"><?php echo "ELIMINACION COMPLETA"; ?></div> <?php }
      if($id == '0') { ?><div id="numero1"><?php echo "NO HA SELECCIONADO"; ?></div> <?php }?></td>
      </tr>   
             <tr id="tr1">
              <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
              <td id="titulo4">CODIGO</td>
              <td id="titulo4">INGRESO</td>
              <td id="titulo4">VALOR</td>
              <td id="titulo4">FECHA</td>
            </tr>
            <?php do { ?>
            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
            <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_ingresos['id_ing']; ?>" /></td>
              <td id="dato2"><?php echo $codigo_insumo;?></td>
              <td id="dato2"><?php echo $row_ingresos['ingreso_ing'];?></td>
              <td id="dato2"><?php echo $row_ingresos['valor_und_ing'];?></td>
              <td id="dato2"><?php echo $row_ingresos['fecha_ing'];?></td>
            </tr>
             <?php } while ($row_ingresos = mysql_fetch_assoc($ingresos)); ?> 
             </table>
             </form>
          
  <?php }?>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_compra);

mysql_free_result($detalle);

mysql_free_result($inventario);

mysql_free_result($ingresos);
?>