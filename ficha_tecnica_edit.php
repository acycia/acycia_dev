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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE TblFichaTecnica SET id_ref_ft=%s, n_egp_ft=%s, id_rev_ft=%s, cod_ft=%s, fecha_ft=%s, adicionado_ft=%s, cliente_ft=%s, PtensionTd_ft=%s, PtensionMd_ft=%s, elongTd_ft=%s, elongMd_ft=%s, FrompTd_ft=%s, FrompMd_ft=%s, CDinamCaraMax_ft=%s, CDinamCaraMin_ft=%s, CDinamDorsoMax_ft=%s, CDinamDorsoMin_ft=%s, CEstCaraMax_ft=%s, CEstCaraMin_ft=%s, CEstDorsoMax_ft=%s, CEstDorsoMin_ft=%s, ImpacDardo_ft=%s, TsuperfMax_ft=%s, TsuperfMin_ft=%s, TselleMax_ft=%s, TselleMin_ft=%s, SelloTamano1_ft=%s, SelloTamano2_ft=%s, SegAnchoLiner_ft=%s, HotAnchoLiner_ft=%s, perforacion_ft=%s, SolapMax_ft=%s, SolapMin_ft=%s, CintaMax_ft=%s, CintaMin_ft=%s, PrincMax_ft=%s, PrincMin_ft=%s, InferMax_ft=%s, InferMin_ft=%s, LinerMax_ft=%s, LinerMin_ft=%s, BolsMax_ft=%s, BolsMin_ft=%s, nom_ft=%s, OtroMax_ft=%s, OtroMin_ft=%s, aditivo_ft=%s, cantAditivo_ft=%s, estado_ft=%s, fechaModif_ft=%s, addCambio_ft=%s, aprobo_ft=%s WHERE n_ft=%s",
                       GetSQLValueString($_POST['id_ref_ft'], "int"),
                       GetSQLValueString($_POST['n_egp_ft'], "text"),
                       GetSQLValueString($_POST['id_rev_ft'], "int"),
                       GetSQLValueString($_POST['cod_ft'], "text"),
                       GetSQLValueString($_POST['fecha_ft'], "date"),
                       GetSQLValueString($_POST['adicionado_ft'], "text"),
                       GetSQLValueString($_POST['cliente_ft'], "int"),
                       GetSQLValueString($_POST['PtensionTd_ft'], "double"),
                       GetSQLValueString($_POST['PtensionMd_ft'], "double"),
                       GetSQLValueString($_POST['elongTd_ft'], "double"),
                       GetSQLValueString($_POST['elongMd_ft'], "double"),
                       GetSQLValueString($_POST['FrompTd_ft'], "double"),
                       GetSQLValueString($_POST['FrompMd_ft'], "double"),
                       GetSQLValueString($_POST['CDinamCaraMax_ft'], "double"),
                       GetSQLValueString($_POST['CDinamCaraMin_ft'], "double"),
                       GetSQLValueString($_POST['CDinamDorsoMax_ft'], "double"),
                       GetSQLValueString($_POST['CDinamDorsoMin_ft'], "double"),
                       GetSQLValueString($_POST['CEstCaraMax_ft'], "double"),
                       GetSQLValueString($_POST['CEstCaraMin_ft'], "double"),
                       GetSQLValueString($_POST['CEstDorsoMax_ft'], "double"),
                       GetSQLValueString($_POST['CEstDorsoMin_ft'], "double"),
                       GetSQLValueString($_POST['ImpacDardo_ft'], "int"),
                       GetSQLValueString($_POST['TsuperfMax_ft'], "int"),
                       GetSQLValueString($_POST['TsuperfMin_ft'], "int"),
                       GetSQLValueString($_POST['TselleMax_ft'], "int"),
                       GetSQLValueString($_POST['TselleMin_ft'], "int"),					   
                       GetSQLValueString($_POST['SelloTamano1_ft'], "int"),
                       GetSQLValueString($_POST['SelloTamano2_ft'], "int"),
                       GetSQLValueString($_POST['SegAnchoLiner_ft'], "int"),
                       GetSQLValueString($_POST['HotAnchoLiner_ft'], "int"),
                       GetSQLValueString($_POST['perforacion_ft'], "int"),
                       GetSQLValueString($_POST['SolapMax_ft'], "double"),
                       GetSQLValueString($_POST['SolapMin_ft'], "double"),
                       GetSQLValueString($_POST['CintaMax_ft'], "double"),
                       GetSQLValueString($_POST['CintaMin_ft'], "double"),
                       GetSQLValueString($_POST['PrincMax_ft'], "double"),
                       GetSQLValueString($_POST['PrincMin_ft'], "double"),
                       GetSQLValueString($_POST['InferMax_ft'], "double"),
                       GetSQLValueString($_POST['InferMin_ft'], "double"),
                       GetSQLValueString($_POST['LinerMax_ft'], "double"),
                       GetSQLValueString($_POST['LinerMin_ft'], "double"),
					   GetSQLValueString($_POST['BolsMax_ft'], "double"),
					   GetSQLValueString($_POST['BolsMin_ft'], "double"),
					   GetSQLValueString($_POST['nom_ft'], "text"),
					   GetSQLValueString($_POST['OtroMax_ft'], "double"),
					   GetSQLValueString($_POST['OtroMin_ft'], "double"),					   
					   GetSQLValueString($_POST['aditivo_ft'], "int"),
					   GetSQLValueString($_POST['cantAditivo_ft'], "int"),					   
                       GetSQLValueString($_POST['estado_ft'], "text"),
                       GetSQLValueString($_POST['fechaModif_ft'], "date"),
                       GetSQLValueString($_POST['addCambio_ft'], "text"),
					   GetSQLValueString($_POST['aprobo_ft'], "text"),
                       GetSQLValueString($_POST['n_ft'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "ficha_tecnica_vista.php?n_ft=" . $_POST['n_ft'] . "";
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

$colname_referencia = "-1";
if (isset($_GET['n_ft'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM TblFichaTecnica, Tbl_referencia WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft = Tbl_referencia.id_ref", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_revision = "-1";
if (isset($_GET['n_ft'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM TblFichaTecnica, revision WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft = revision.id_ref_rev", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_verificacion = "-1";
if (isset($_GET['n_ft'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM TblFichaTecnica, verificacion WHERE TblFichaTecnica.n_ft = %s AND TblFichaTecnica.id_ref_ft = verificacion.id_ref_verif AND verificacion.estado_arte_verif = '2'", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_validacion = "-1";
if (isset($_GET['n_ft'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM TblFichaTecnica, validacion WHERE  TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_egp = "-1";
if (isset($_GET['n_ft'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM TblFichaTecnica, Tbl_egp WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.n_egp_ft = Tbl_egp.n_egp", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

$colname_ficha_tecnica = "-1";
if (isset($_GET['n_ft'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblFichaTecnica WHERE n_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_clientes = "-1";
if (isset($_GET['n_ft'])) {
  $colname_clientes = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_clientes = sprintf("SELECT DISTINCT Tbl_referencia.cod_ref,Tbl_cliente_referencia.N_referencia,Tbl_cliente_referencia.Str_nit,cliente.id_c,cliente.nit_c,cliente.nombre_c,TblFichaTecnica.cliente_ft,TblFichaTecnica.id_ref_ft FROM TblFichaTecnica,Tbl_referencia,Tbl_cliente_referencia,cliente 
WHERE TblFichaTecnica.n_ft='%s' and TblFichaTecnica.id_ref_ft=Tbl_referencia.id_ref 
and Tbl_referencia.cod_ref=Tbl_cliente_referencia.N_referencia and Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY cliente.nombre_c ASC", $colname_clientes);
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT id_insumo, codigo_insumo, descripcion_insumo, clase_insumo FROM insumo WHERE clase_insumo='8' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//REF CLIENTE
$colname_refcliente = "-1";
if (isset($_GET['n_ft'])) {
  $colname_refcliente = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refcliente = sprintf("SELECT TblFichaTecnica.id_ref_ft,Tbl_referencia.cod_ref,Tbl_refcliente.id_refcliente,Tbl_refcliente.int_ref_ac_rc,Tbl_refcliente.str_ref_cl_rc,Tbl_refcliente.str_descripcion_rc 
FROM TblFichaTecnica,Tbl_referencia,Tbl_refcliente WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft=Tbl_referencia.id_ref and Tbl_referencia.cod_ref=Tbl_refcliente.int_ref_ac_rc", $colname_refcliente);
$refcliente = mysql_query($query_refcliente, $conexion1) or die(mysql_error());
$row_refcliente = mysql_fetch_assoc($refcliente);
$totalRows_refcliente = mysql_num_rows($refcliente);

$colname_certificacion_ref = "-1";
if (isset($_GET['n_ft'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM TblFichaTecnica,TblCertificacion WHERE TblFichaTecnica.n_ft = %s AND TblFichaTecnica.id_ref_ft = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);    

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
</head>
<body onLoad="javascript: mostrarBols(this)">
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
<table id="tabla1">
  <tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>	
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form method="POST" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="return validacion_select_ft();">
        <table id="tabla2">
          <tr id="tr1">
            <td colspan="2" id="codigo">CODIGO: FT-02</td>
            <td colspan="5" id="titulo2">EDITAR FICHA TECNICA 
              <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "LAMINAS";}else if($row_referencia['tipo_bolsa_ref']=='PACKING LIST'){echo "PACKING LIST";}else{echo "BOLSAS";}?></td>
            <td colspan="3" id="titulo2">VERSION: 1</td>
            </tr>
          <tr>
            <td colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
            <td colspan="5" id="subtitulo"><input name="id_ref_ft" type="hidden" value="<?php echo $row_ficha_tecnica['id_ref_ft']; ?>">
              <input type="text" name="cod_ft" value="<?php echo $row_ficha_tecnica['cod_ft']; ?>" size="5"></td>
            <td colspan="3" id="subtitulo"><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/hoja.gif" alt="VISTA FICHAS TECNICAS" title="VISTA FICHAS TECNICAS" border="0" style="cursor:hand;"/></a>
              <?php if($row_certificacion['idcc']=='') { ?>
              <a href="certificacion_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_referencia['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
              </a><?php } ?><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="ficha_tecnica_busqueda.php"><img src="images/opciones.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" title="RESTAURAR" onClick="window.history.go()"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">REFERENCIA </td>
            <td colspan="4" id="fuente1"><input name="n_egp_ft" type="hidden" value="<?php echo $row_ficha_tecnica['n_egp_ft']; ?>" size="3"></td>
            <td colspan="2" id="fuente1">REVISION N&deg;
              <input name="id_rev_ft" type="text" value="<?php $rev = $row_ficha_tecnica['id_rev_ft']; if($rev == '' || $rev == '0') { echo $row_revision['id_rev']; } else { echo $rev; } ?>" size="3"></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><strong><a href="referencia_bolsa_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&cod_ref= <?php echo $row_referencia['cod_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_referencia['version_ref']; ?></a></strong></td>
            <td colspan="4" id="fuente1">VERIFICACION N&deg; <a href="verificacion_vista.php?id_verif=<?php echo $row_verificacion['id_verif']; ?>"><?php echo $row_verificacion['id_verif']; ?></a></td>
            <td colspan="2" id="fuente1">VALIDACION N&deg; <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><?php echo $row_validacion['id_val']; ?></a></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">FECHA  ELABORACION</td>
            <td colspan="6" id="fuente1">ELABORADA POR </td>
            </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="date" name="fecha_ft" value="<?php echo $row_ficha_tecnica['fecha_ft'];?>" size="10"></td>
            <td colspan="6" id="dato1"><input type="text" name="adicionado_ft" value="<?php echo $row_ficha_tecnica['adicionado_ft'];?>" readonly size="30"></td>
            </tr>
          <tr>
            <td colspan="8" id="dato1">&nbsp;</td>
            </tr>
          <tr id="tr1">
            <td colspan="10" id="titulo2">CARACTERISTICAS GENERALES</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Cliente:</td>
            <td colspan="8" id="fuente1"><select  name="cliente_ft" id="cliente_ft" style="width:200px">
            <option value=""<?php if (!(strcmp("", $row_ficha_tecnica['cliente_ft']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
do {  
?>
              <option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $row_ficha_tecnica['cliente_ft']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c'];?>
                </option>
              <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
  $rows = mysql_num_rows($clientes);
  if($rows > 0) {
      mysql_data_seek($clientes, 0);
	  $row_clientes = mysql_fetch_assoc($clientes);
  }
?>
            </select></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Referencia: </td>
            <td id="fuente1"><?php echo $row_referencia['cod_ref'].'-'.$row_referencia['version_ref'] ?></td>
            <td id="fuente1">ref. Cliente</td>
            <td id="fuente1"><?php echo $row_refcliente['str_ref_cl_rc'];?></td>
            <td colspan="3" id="fuente1">Fecha: </td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['fecha_registro1_ref']; ?></td>
          </tr>
          <tr>
            <td id="fuente1">Calibre &mu;m:</td>
            <td id="fuente1"><?php $calibrem = ($row_referencia['calibre_ref']*25.4); echo $calibrem; ?></td>
            <td colspan="2" id="fuente1">Calibre m&aacute;ximo &mu;m:</td>
            <td id="fuente1"><?php $calibremi = ($calibrem+($calibrem*10)/100); echo $calibremi;?></td>
            <td colspan="3" id="fuente1">Calibre m&iacute;nimo &mu;m:</td>
            <td colspan="2" id="fuente1"><?php $calibrem = ($calibrem-($calibrem*10)/100); echo $calibrem;?></td>
          </tr>
          <tr>
            <td id="fuente1">Calibre mils:</td>
            <td id="fuente1"><?php echo $row_referencia['calibre_ref']; ?></td>
            <td colspan="2" id="fuente1">Calibre m&aacute;ximo mils:</td>
            <td id="fuente1"><?php echo ($row_referencia['calibre_ref']+($row_referencia['calibre_ref']*10)/100); ?></td>
            <td colspan="3" id="fuente1">Calibre m&iacute;nimo mils:</td>
            <td colspan="2" id="fuente1"><?php echo ($row_referencia['calibre_ref']-($row_referencia['calibre_ref']*10)/100); ?></td>
          </tr>
          <tr>
            <td id="fuente1">Ancho mm:</td>
            <td id="fuente1"><?php $ancho = ($row_referencia['ancho_ref']*10);echo $ancho; ?></td>
            <td colspan="2" id="fuente1">Ancho M&aacute;ximo mm:</td>
            <td id="fuente1"><?php echo $ancho+10; ?></td>
            <td colspan="3" id="fuente1">Ancho m&iacute;nimo mm:</td>
            <td colspan="2" id="fuente1"><?php echo $ancho-10; ?></td>
          </tr>
          <tr>
            <td id="fuente1"><?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){ $largo = $row_referencia['N_repeticion_l']; echo 'REPETICION';}else{ $largo = ($row_referencia['largo_ref']*10);echo 'LARGO';}?> mm:</td>
            <td id="fuente1"><?php echo $largo; ?></td>
            <td colspan="2" id="fuente1">Largo M&aacute;ximo mm:</td>
            <td id="fuente1"><?php echo $largo+10; ?></td>
            <td colspan="3" id="fuente1">Largo m&iacute;nimo mm:</td>
            <td colspan="2" id="fuente1"><?php echo $largo-10; ?></td>
          </tr>
            <?php if($row_referencia['tipo_bolsa_ref']!='LAMINA'){ ?><tr>
            <td id="fuente1">Solapa (± 10mm) <?php if (!(strcmp($row_referencia['b_solapa_caract_ref'],2))){echo "sencilla";} if (!(strcmp($row_referencia['b_solapa_caract_ref'],1))){echo "Doble";}?>:</td>
            <td id="fuente1"><?php echo $row_referencia['solapa_ref']*10; ?></td>
            <td colspan="2" id="fuente1">Fuelle (&plusmn; 10mm):</td>
            <td id="fuente1"><?php echo $row_referencia['N_fuelle']*10; ?></td>
            <td colspan="3" id="fuente1"><strong>Ancho &Uacute;til (&plusmn; 10 mm)</strong></td>
            <td colspan="2" id="fuente1"><?php echo ($ancho-15); ?></td>
            </tr><?php }?>
          <tr>
            <td colspan="2" id="fuente1">Pigmento Cara Externa:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['pigm_ext_egp']; ?></td>
            <td id="fuente1">&nbsp;</td>
            <td colspan="3" id="fuente1">Tipo Extrusi&oacute;n:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['tipo_ext_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Pigmento Cara Interna:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['pigm_int_epg']; ?></td>
            <td id="fuente1">&nbsp;</td>
            <td colspan="3" id="fuente1">Aplicaci&oacute;n:</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Peso Millar Bolsa:</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['peso_millar_ref']; ?></td>
            <td id="fuente1">&nbsp;</td>
            <td colspan="3" id="fuente1">Peso Millar Bolsillo:</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['peso_millar_bols']; ?></td>
          </tr>
            <tr id="tr1"> 
              <td colspan="10" id="detalle2">IMPRESIONES</td> 
            </tr>
            <tr> 
              <td colspan="5" id="detalle1">Cantidad de Colores que intervienen</td>
              <td colspan="5" id="detalle1">Segun Arte Aprobado</td> 
            </tr>
          <!-- <tr id="tr1">
            <td colspan="2" id="detalle2">UNIDAD</td>
            <td colspan="2" id="detalle2">COLOR</td>
            <td colspan="4" id="detalle2">UNIDAD</td>
            <td colspan="2" id="detalle2">COLOR</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">1</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color1_egp']; ?></td>
            <td colspan="4" id="detalle1">5</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color5_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">2</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color2_egp']; ?></td>
            <td colspan="4" id="detalle1">6</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color6_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">3</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color3_egp']; ?></td>
            <td colspan="4" id="detalle1">7</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color7_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">4</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color4_egp']; ?></td>
            <td colspan="4" id="detalle1">8</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color8_egp']; ?></td>
          </tr> -->
          <tr>
            <td colspan="10" id="dato1"><p><strong>IMPORTANTE</strong>: El m&eacute;todo de revisi&oacute;n y aseguramiento de la calidad de impresi&oacute;n es visual por medio de la tabla de pantones, la tolerancia en la medida puede variar 10 mm en la altura esta combinada con el fuelle y la solapa si lleva, 10 mm en el ancho y 10% en el calibre, la altura &uacute;til de la bolsa no est&aacute; determinada en la altura total, para obtener este dato debe restarle la solapa si la tiene y el &aacute;rea del selle lateral.</p></td>
            </tr>
          <tr id="tr1">
            <td colspan="10" id="titulo2"><strong>PROPIEDADES MECANICAS</strong></td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="detalle2">ANALISIS</td>
            <td colspan="2" id="detalle2">MAXIMO</td>
            <td colspan="2" id="detalle2">MINIMO</td>
            <td colspan="2" id="detalle2">UNIDAD</td>
            <td colspan="2" id="detalle2">NORMAL</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Propiedades de Tensi&oacute;n TD</td>
            <td colspan="4" id="detalle2">&ge;
              <input name="PtensionTd_ft" type="number" id="PtensionTd_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['PtensionTd_ft']; ?>"></td>
            <td colspan="2" id="detalle2">Newton</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Propiedades de Tensi&oacute;n MD</td>
            <td colspan="4" id="detalle2">&ge;
              <input name="PtensionMd_ft" type="number" id="PtensionMd_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['PtensionMd_ft'];?>"></td>
            <td colspan="2" id="detalle2">Newton</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Elongaci&oacute;n TD </td>
            <td colspan="4" id="detalle2">&ge;
              <input name="elongTd_ft" type="number" id="elongTd_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['elongTd_ft'];?>"></td>
            <td colspan="2" id="detalle2">%</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Elongaci&oacute;n MD </td>
            <td colspan="4" id="detalle2">&ge;
              <input name="elongMd_ft" type="number" id="elongMd_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['elongMd_ft'];?>"></td>
            <td colspan="2" id="detalle2">%</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Factor de Rompimiento TD</td>
            <td colspan="4" id="detalle2">&ge;
              <input name="FrompTd_ft" type="number" id="FrompTd_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['FrompTd_ft'];?>"></td>
            <td colspan="2" id="detalle2">Mpa</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Factor de Rompimiento MD</td>
            <td colspan="4" id="detalle2">&ge;
              <input name="FrompMd_ft" type="number" id="FrompMd_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['FrompMd_ft'];?>"></td>
            <td colspan="2" id="detalle2">Mpa</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Coeficiente Din&aacute;mico Cara/Cara</td>
            <td colspan="2" id="detalle2"><input name="CDinamCaraMax_ft" type="number" id="CDinamCaraMax_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CDinamCaraMax_ft'];?>"></td>
            <td colspan="2" id="detalle2"><input name="CDinamCaraMin_ft" type="number" id="CDinamCaraMin_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CDinamCaraMin_ft'];?>"></td>
            <td colspan="2" id="detalle2">N.A.</td>
            <td colspan="2" id="detalle2">ASTM 1894</td>
          </tr>
           <tr>
             <td colspan="2" id="detalle1">Coeficiente Din&aacute;mico Dorso/Dorso</td>
             <td colspan="2" id="detalle2"><input name="CDinamDorsoMax_ft" type="number" id="CDinamDorsoMax_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CDinamDorsoMax_ft'];?>"></td>
             <td colspan="2" id="detalle2"><input name="CDinamDorsoMin_ft" type="number" id="CDinamDorsoMin_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CDinamDorsoMin_ft'];?>"></td>
             <td colspan="2" id="detalle2">N.A.</td>
             <td colspan="2" id="detalle2">ASTM 1894</td>
           </tr>
          <tr>
            <td colspan="2" id="detalle1">Coeficiente Est&aacute;tico Cara/Cara</td>
            <td colspan="2" id="detalle2"><input name="CEstCaraMax_ft" type="number" id="CEstCaraMax_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CEstCaraMax_ft'];?>"></td>
            <td colspan="2" id="detalle2"><input name="CEstCaraMin_ft" type="number" id="CEstCaraMin_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CEstCaraMin_ft'];?>"></td>
            <td colspan="2" id="detalle2">N.A.</td>
            <td colspan="2" id="detalle2">ASTM 1894</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Coeficiente Est&aacute;tico Dorso/Dorso</td>
            <td colspan="2" id="detalle2"><input name="CEstDorsoMax_ft" type="number" id="CEstDorsoMax_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CEstDorsoMax_ft'];?>"></td>
            <td colspan="2" id="detalle2"><input name="CEstDorsoMin_ft" type="number" id="CEstDorsoMin_ft" placeholder="0,00" style="width:50px" min="0.00" step="0.01"  value="<?php echo $row_ficha_tecnica['CEstDorsoMin_ft'];?>"></td>
            <td colspan="2" id="detalle2">N.A.</td>
            <td colspan="2" id="detalle2">ASTM 1894</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Impacto al Dardo</td>
            <td colspan="4" id="detalle2">&ge;
              <input name="ImpacDardo_ft" type="number" id="ImpacDardo_ft" placeholder="0" style="width:50px" min="0" step="1"  value="<?php echo $row_ficha_tecnica['ImpacDardo_ft'];?>"></td>
            <td colspan="2" id="detalle2">Gramos</td>
            <td colspan="2" id="detalle2">ASTM D-1709</td>
          </tr>
            <tr>
            <td colspan="2" id="detalle1">Tensi&oacute;n Superficial</td>
            <td colspan="2" id="detalle2"><input name="TsuperfMax_ft" type="number" id="TsuperfMax_ft" placeholder="0" style="width:50px" min="0" step="1"  value="<?php echo $row_ficha_tecnica['TsuperfMax_ft'];?>"></td>
            <td colspan="2" id="detalle2"><input name="TsuperfMin_ft" type="number" id="TsuperfMin_ft" placeholder="0" style="width:50px" min="0" step="1"  value="<?php echo $row_ficha_tecnica['TsuperfMin_ft'];?>"></td>
            <td colspan="2" id="detalle2">Dinas</td>
            <td colspan="2" id="detalle2">ASTM D-2578-09</td>
          </tr>
           <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){ ?>
            <!-- <tr>
            <td colspan="2" id="detalle1">Temperatura de Selle</td>
            <td colspan="2" id="detalle2"><input name="TselleMax_ft" type="number" id="TselleMax_ft" placeholder="0" style="width:50px" min="0" step="1"  value="<?php echo $row_ficha_tecnica['TselleMax_ft'];?>"></td>
            <td colspan="2" id="detalle2"><input name="TselleMin_ft" type="number" id="TselleMin_ft" placeholder="0" style="width:50px" min="0" step="1"  value="<?php echo $row_ficha_tecnica['TselleMin_ft'];?>"></td>
            <td colspan="2" id="detalle2">&deg;C</td>
            <td colspan="2" id="detalle2">ASTM F 88</td>
          </tr> --><?php }?>            
          <tr>
            <td colspan="10" id="dato1"><strong>NOTAS</strong>: Estos son valores estad&iacute;sticos obtenidos en nuestro laboratorio y se anexan en este informe solo como orientaci&oacute;n. No comprometen a AC&amp;CIA S.A. como datos absolutos y pueden ser modificados seg&uacute;n el criterio aportado por el laboratorio, los valores m&aacute;ximo y m&iacute;nimo para cada propiedad est&aacute;n especificados en el certificado de calidad.</td>
            </tr>
          <tr id="tr1">
            <td colspan="10" id="titulo2">CONDICIONES DE FABRICACION EN SELLADO</td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">Tipo de Sello:</td>
            <td colspan="2" id="fuente1"><select name="tipo_sello_egp" id="tipo_sello_egp" style="width:130px" onChange="tipoSello(this)">
              <option value="N/A"<?php if (!(strcmp("N/A", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N/A</option>
              <option value="HILO"<?php if (!(strcmp("HILO", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO</option>
              <option value="PLANO"<?php if (!(strcmp("PLANO", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
              <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
            </select></td>
            <td colspan="2" id="fuente1">Tama&ntilde;o del selle mm:</td>
            <td colspan="2" id="fuente1"><input name="SelloTamano1_ft" id="SelloTamano1_ft" type="number" style="width:50px" min="0" step="1" placeholder="0" value="<?php echo $row_ficha_tecnica['SelloTamano1_ft']; ?>"/>
              &le;</td>
            <td colspan="2" id="fuente1"><input name="SelloTamano2_ft" id="SelloTamano2_ft" type="number" style="width:50px" min="0" step="1" placeholder="0" value="<?php echo $row_ficha_tecnica['SelloTamano2_ft']; ?>"/>&ge;</td>
            </tr> 
          <tr>
            <td colspan="2" rowspan="2" id="fuente1">Tipo de Cierre:</td>
            <td colspan="2" rowspan="2" id="fuente1"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref" style="width:130px">
              <option value="N.A." <?php if (!(strcmp("N.A", $row_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A</option>
              <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
              <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
              <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
              <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
            </select></td>
            <td id="fuente1">Seguridad:</td>
            <td id="fuente1"><select name="cintaseg_ft" id="cintaseg_ft" style="width:50px">
          <option value="N.A." <?php if (!(strcmp("N.A", $row_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>            
          </select></td>
            <td colspan="2" id="fuente1">Ancho Liner mm:</td>
            <td colspan="2" id="fuente1"><input name="SegAnchoLiner_ft" id="SegAnchoLiner_ft" type="number" style="width:50px" min="0" step="1" placeholder="0" value="<?php echo $row_ficha_tecnica['SegAnchoLiner_ft'];?>"/></td>
          </tr>
          <tr>
            <td id="fuente1">Hot melt:</td>
            <td id="fuente1"><select name="hotmelt_ft" id="hotmelt_ft" style="width:50px">
          <option value="N.A." <?php if (!(strcmp("N.A", $row_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
            </select></td>
            <td colspan="2" id="fuente1">Ancho Liner mm:</td>
            <td colspan="2" id="fuente1"><input name="HotAnchoLiner_ft" id="HotAnchoLiner_ft" type="number" style="width:50px" min="0" step="1" placeholder="0" value="<?php echo $row_ficha_tecnica['HotAnchoLiner_ft'];?>"/></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">&nbsp;</td>
            <td colspan="2" id="fuente1">Troqueles:</td>
            <td colspan="2" id="fuente1"><select name="troquel_ft" id="troquel_ft" style="width:50px">
              <option value=""<?php if (!(strcmp("", $row_referencia['B_troquel']))) {echo "selected=\"selected\"";} ?>>N.A</option>
              <option value="1"<?php if (!(strcmp("1", $row_referencia['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
              <option value="0"<?php if (!(strcmp("0",$row_referencia['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
            </select></td>
            <td id="fuente1">Perforaciones:</td>
            <td id="fuente1"><select name="perforacion_ft" id="perforacion_ft" style="width:50px">
              <option value="0">NO</option>
              <option value="1">SI</option>
            </select></td>
            <td id="fuente1">Precorte:</td>
            <td id="fuente1"><input name="precorte_ft" id="precorte_ft" type="number" style="width:50px" min="0" max="7" step="1" value="<?php echo $row_referencia['B_precorte']; ?>"/></td>
          </tr>
          <tr id="tr1">
            <td colspan="2" rowspan="2" id="fuente1">Bolsillo Portaguia: 
              <input name="bolsillo_guia_ref" id="bolsillo_guia_ref" type="hidden" size="3" value="<?php echo $row_referencia['bolsillo_guia_ref']; ?>"/>
              <?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
            <td colspan="3" id="fuente1">&nbsp;</td>
            <td id="fuente1">Traslape:</td>
            <td id="fuente1"><select name="traslape_ft" id="str_bols_fo_ref" style="width:80px" onBlur="traslape(this)">
              <option value="">N.A.</option>
              <option value="TRANSLAPE"<?php if (!(strcmp('TRANSLAPE', $row_referencia['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Translape</option>
              <option value="RESELLABLE"<?php if (!(strcmp('RESELLABLE', $row_referencia['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Resellable</option>
            </select></td>
            <td id="fuente1"><input name="cantforma_ft" id="B_cantforma" type="number" disabled style="width:50px" min="0.00" step="0.01" placeholder="traslape" value="<?php echo $row_referencia['B_cantforma']; ?>"/></td>
            <td id="fuente1">Ubicacion:</td>
            <td id="fuente1"><select name="ubicabol_ft" id="str_bols_ub_ref" style="width:50px">
              <option value="">N.A.</option>
              <option value="ANVERSO"<?php if (!(strcmp('ANVERSO', $row_referencia['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Anverso</option>
              <option value="REVERSO"<?php if (!(strcmp('REVERSO', $row_referencia['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Reverso</option>
            </select></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Lamina 1</td>
            <td colspan="2" id="fuente1"><input name="lamina1_ft" id="bol_lamina_1_ref" style="width:50px" min="0"step="0.01" placeholder="0,00" type="number" size="5" value="<?php echo $row_referencia['bol_lamina_1_ref'] ?>" /></td>
            <td colspan="2" id="fuente1">Lamina 2</td>
            <td colspan="2" id="fuente1"><input name="lamina2_ft" id="bol_lamina_2_ref" style="width:50px" min="0"step="0.01" placeholder="0,00" type="number" size="5" value="<?php echo $row_referencia['bol_lamina_2_ref'] ?>" /></td>
          </tr>
          <tr>
            <td colspan="5" id="fuente1">Reacion de adherencia de la CINTA DE SEGURIDAD  &ge; a 300 s</td>
            <td colspan="5" id="fuente1">Reacion de adherencia del HOT MELT &ge; a 20 s</td>
            </tr>
          <tr>
            <td colspan="10" id="fuente3">&nbsp;</td>
            </tr>           
          <tr id="tr1">
            <td colspan="10" id="titulo1">NUMERACION</td>
          </tr>
          <tr id="tr1">
            <td colspan="2" id="detalle2">POSICION</td>
            <td colspan="2" id="detalle2">ESTILO</td>
            <td colspan="2" id="detalle2">m&aacute;ximo mm</td>
            <td colspan="2" id="detalle2">m&iacute;nimo mm</td>
            <td colspan="2" id="detalle2">FORMATO CB </td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">SOLAPA TR </td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_solapatr_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="SolapMax_ft" id="SolapMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['SolapMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="SolapMin_ft" id="SolapMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['SolapMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_solapatr_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">CINTA</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_cinta_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="CintaMax_ft" id="CintaMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['CintaMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="CintaMin_ft" id="CintaMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['CintaMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_cinta_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">PRINCIPAL</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_principal_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="PrincMax_ft" id="PrincMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['PrincMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="PrincMin_ft" id="PrincMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['PrincMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_principal_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">INFERIOR</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_inferior_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="InferMax_ft" id="InferMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['InferMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="InferMin_ft" id="InferMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['InferMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_inferior_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">LINER</td>
            <td colspan="2" id="detalle1">- <?php echo $row_egp['tipo_liner_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="LinerMax_ft" id="LinerMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['LinerMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="LinerMin_ft" id="LinerMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['LinerMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_liner_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">BOLSILLO</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_bols_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="BolsMax_ft" id="BolsMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['BolsMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="BolsMin_ft" id="BolsMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['BolsMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_bols_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Otros: <?php echo $row_egp['tipo_nom_egp']; ?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_otro_egp']; ?></td>
            <td colspan="2" id="detalle1"><input name="OtroMax_ft" id="OtroMax_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['OtroMax_ft'];?>"/></td>
            <td colspan="2" id="detalle1"><input name="OtroMin_ft" id="OtroMin_ft" type="number" style="width:50px" min="0.00" step="0.01" placeholder="0,00" value="<?php echo $row_ficha_tecnica['OtroMin_ft'];?>"/></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_otro_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="10" id="dato1"><strong>NOTAS</strong>: En la numeraci&oacute;n las dimensiones var&iacute;an de acuerdo al tama&ntilde;o de la bolsa, y pueden ser modificadas seg&uacute;n la necesidad del proceso, estilo esta dado por sus caracter&iacute;sticas de tama&ntilde;o y forma as&iacute;: Normal: contador alfanum&eacute;rico, Doble: contador alfanum&eacute;rico impreso a doble repetici&oacute;n en una misma bolsa y CCTV n&uacute;mero de gran car&aacute;cter.(el estilo normal y el doble pueden llevar o no c&oacute;digo de barras.)</td>
            </tr>
          <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){?><tr id="tr1">
            <td colspan="10" id="titulo2">CONDICIONES DE EMPAQUE</td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Peso Max Rollo (kg):</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['N_peso_max_l'] ?></td>
            <td colspan="2" id="fuente1">Diametro (mm):</td>
            <td id="fuente1"><?php echo $row_referencia['N_diametro_max_l'] ?></td>
            <td colspan="2" id="fuente1">Bobinado No:</td>
            <td id="fuente1"><?php echo $row_referencia['N_embobinado_l'] ?></td>
          </tr>
          <?php }?>            
        <tr  id="tr1">	
        <td colspan="3" rowspan="2" id="fuente1">MARGENES</td>
        <td id="fuente1">Izquierda mm</td>
        <td id="fuente1"><?php echo $row_referencia['margen_izq_imp_egp'];?></td>
        <td id="fuente1">Rep. en Ancho</td>
        <td id="fuente1"><?php echo $row_referencia['margen_anc_imp_egp']?></td>
        <td id="fuente2">de</td>
        <td id="fuente1"><?php echo $row_referencia['margen_anc_mm_imp_egp']?></td>
        <td id="fuente1">mm</td>
      </tr>
      <tr>
        <td id="fuente1">Derecha mm</td>
        <td id="fuente1"><?php echo $row_referencia['margen_der_imp_egp']?></td>
        <td id="fuente1">Rep. Perimetro</td>
        <td id="fuente1"><?php echo $row_referencia['margen_peri_imp_egp']?></td>
        <td id="fuente2">de</td>
        <td id="fuente1"><?php echo $row_referencia['margen_per_mm_imp_egp']?></td>
        <td id="fuente1">mm</td>
      </tr>

      <tr  id="tr1">
        <td colspan="3" id="fuente1">&nbsp;</td>
        <td id="fuente1"><strong>Z</strong></td>
        <td colspan="6" id="fuente1"><?php echo $row_referencia['margen_z_imp_egp']?></td>
        </tr>
       <tr>
         <td colspan="10" id="fuente1">TIPO DE ADITIVO</td>
         </tr>
       <tr>
        <td colspan="5" id="fuente1"><select name="aditivo_ft" id="aditivo_ft" style="width:300px" onChange="fichatecnicaref(form.aditivo_ft.value);">
          <option value=""<?php if (!(strcmp("", $row_ficha_tecnica['aditivo_ft']))) {echo "selected=\"selected\"";} ?>>Insumo</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_ficha_tecnica['aditivo_ft']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select>
        	<?php		
			$aditivo=$row_ficha_tecnica['aditivo_ft'];
			$sqln="SELECT id_insumo, codigo_insumo, descripcion_insumo FROM insumo WHERE id_insumo='$aditivo'"; 
			$resultn=mysql_query($sqln); 
			$numn=mysql_num_rows($resultn);  
			if($numn >= '1') 
			{
			$codigo=mysql_result($resultn,0,'codigo_insumo'); }?>
            </td>
        <td colspan="5" id="fuente1"><div id="definicionficha"><?php echo " <strong>REF: </strong> ".$codigo;?><strong> CANT: </strong> <input name="cantAditivo_ft" type="number" style="width:50px" min="0" step="1" value="<?php echo $row_ficha_tecnica['cantAditivo_ft']; ?>" /> %</div></td>
        </tr>
          <tr id="tr1">
            <td colspan="10" id="titulo2">CONDICIONES DE USO Y ALMACENAMIENTO <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "Y TRANSPORTE"; }?></td>
          </tr>
          <tr>
            <td colspan="10" id="detalle1"><p>1. Se deben de guardar en cajas o en paquetes protegiendo del polvo y humedad.</p>
              <p>2. No exponer a los rayos directos del sol ni al agua.</p>
              <p>3. Evitar el contacto con solventes o vapores que afecten o contaminen el adhesivo (bolsas seguridad, courrier).</p>
              <p>4. Siempre dar rotaci&oacute;n a los lotes antiguos para evitar caducidad.</p>
              <p>5. Evitar en el transporte el roce entre paquetes de bolsas.</p>
              <p>6. Conservar el control de numeraci&oacute;n y/o control de empaque por paquete para la trazabilidad.</p></td>
          </tr>
        <tr id="tr1">
            <td colspan="10" id="titulo2">OBSERVACIONES</td>
          </tr>
          <tr>
            <td colspan="10" id="detalle1"><p>Vida &Uacute;til: 12 a 18 meses m&aacute;ximo despu&eacute;s de fecha de producci&oacute;n.</p></td>
            </tr>            
          <tr>
            <td colspan="10" id="fuente4">
            <table border="0" id="tabla1" >
              <tr>
                <td id="fuente1">ESTADO FT</td>
                <td id="fuente1">FECHA MODIF. </td>
                <td id="fuente1">MODIFICADO POR </td>
                <td id="fuente1">APROBO</td>
              </tr>
              <tr>
                <td><select name="estado_ft">
                  <option value="Activa" <?php if (!(strcmp("Activa", $row_ficha['estado_ft']))) {echo "selected=\"selected\"";} ?>>Activa</option>
                  <option value="Inactiva" <?php if (!(strcmp("Inactiva", $row_ficha['estado_ft']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
                </select></td>
                <td><input name="fechaModif_ft" type="date" id="fechaModif_ft" value="<?php echo date("Y-m-d"); ?>" readonly></td>
                <td><input name="addCambio_ft" type="text" id="addCambio_ft" value="<?php echo $row_ficha_tecnica['addCambio_ft'];?>" size="30" readonly></td>
                <td><input name="aprobo_ft" type="text" id="aprobo_ft" value="<?php echo $row_ficha_tecnica['aprobo_ft'];?>" size="30"></td>
              </tr>
            </table></td>
            </tr>
          <tr id="tr1">
            <td colspan="10" id="dato2"><input type="submit" value="EDIT FICHA TECNICA"></td>
          </tr>
        </table>
        <input type="hidden" name="n_ft" value="<?php echo $row_ficha_tecnica['n_ft']; ?>">
        <input type="hidden" name="MM_update" value="form1">
      </form></td>
  </tr></table>
  </div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($referencia);

mysql_free_result($revision);

mysql_free_result($verificacion);

mysql_free_result($validacion);

mysql_free_result($egp);

?>