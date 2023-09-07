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


$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $cantidad=$_POST['cantidad_det'];
  $insertSQL = sprintf("INSERT INTO orden_compra_detalle (id_det, n_oc_det, id_insumo_det, cantidad_det, saldo_det, valor_unitario_det, descuento_det, moneda_det, total_det, verificacion_det, subtotal_det,valor_iva,concepto1,concepto2,centro_costos) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['id_det'], "int"),
   GetSQLValueString($_POST['n_oc_det'], "int"),
   GetSQLValueString($_POST['id_insumo_det'], "int"),
   GetSQLValueString($_POST['cantidad_det'], "double"),
   GetSQLValueString($_POST['cantidad_det'], "double"),
   GetSQLValueString($_POST['valor_unitario_det'], "double"),
   GetSQLValueString($_POST['descuento_det'], "text"), 
   GetSQLValueString($_POST['moneda_det'], "text"),                     
   GetSQLValueString($_POST['total_det'], "double"),
   GetSQLValueString($cantidad, "double"),
   GetSQLValueString($_POST['subtotal_det'], "text"), 
   GetSQLValueString($_POST['valor_iva'], "text"),  
   GetSQLValueString($_POST['concepto1'], "text"),
   GetSQLValueString($_POST['concepto2'], "text"),
   GetSQLValueString($_POST['centro_costos'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
//ACTUALIZA EL VALOR UNITARIO EN INSUMOS ESTO FUE AGREGADO RECIENTEMENTE
//valor_unitario_det este se encuentra en un ajax
  $updateSQL = sprintf("UPDATE insumo SET valor_unitario_insumo=%s WHERE id_insumo=%s",
   GetSQLValueString($_POST['valor_unitario_det'], "double"),
   GetSQLValueString($_POST['id_insumo_det'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
  echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
  echo "<script type=\"text/javascript\">window.close();</script>";  
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

$colname_orden_compra = "-1";
if (isset($_GET['n_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra WHERE n_oc = %s", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM insumo ORDER BY descripcion_insumo ASC";
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
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
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('id_insumo_det','','R','valor_unitario_det','','R','cantidad_det','','R','descuento_det','','R','total_det','','R');return document.MM_returnValue" >
                    <table class="table">
                      <tr>
                        <td colspan="6" id="subtitulo">ADD ITEM </td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente1">ORDEN DE COMPRA N&deg; <strong><?php echo $row_orden_compra['n_oc']; ?></strong><input name="n_oc_det" type="hidden" value="<?php echo $row_orden_compra['n_oc']; ?>"></td>
                        <td colspan="3" id="fuente3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>

                      <tr>
                        <td colspan="5" id="fuente1">INSUMO</td>
                        <td id="fuente1">IVA</td>
                      </tr>
                      <tr>
                        <td colspan="5" id="dato1">
                          <select name="id_insumo_det" id="id_insumo_det" class="selectsGrande" onChange="DatosGestiones('3','id_insumo',form1.id_insumo_det.value);">
                          <option value="">SELECCIONE</option>
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_insumos['id_insumo']?>"><?php echo $row_insumos['descripcion_insumo']?></option>
                            <?php
                          } while ($row_insumos = mysql_fetch_assoc($insumos));
                          $rows = mysql_num_rows($insumos);
                          if($rows > 0) {
                            mysql_data_seek($insumos, 0);
                            $row_insumos = mysql_fetch_assoc($insumos);
                          }
                          ?>
                        </select>
                      </td>
                      <td id="dato1"><input name="constante_iva" type="number" id="constante_iva"  onBlur="detalle()" style="width:60px" min="0" value="19"></td>
                    </tr>

                    <tr>
                      <td colspan="6" id="dato2"><div id="resultado"></div></td>
                    </tr>
                    <tr>
                      <td id="fuente1">CANTIDAD</td>
                      <td id="fuente1">DESCUENTO ($) </td>
                      <td id="fuente1">MONEDA</td>
                      <td id="fuente1">VALOR DEL IVA</td>
                      <td id="fuente1">SUB TOTAL</td>
                      <td id="fuente1">TOTAL</td>
                    </tr>
                    <tr>
                      <td id="dato1"><input type="number" placeholder="0,00" style="width:100px" min="0.00" step="0.01" name="cantidad_det" value="" onChange="detalle()"></td>
                      <td id="dato1"><input type="number" placeholder="0,00" style="width:100px" min="0.00" step="0.01" name="descuento_det" value="0" onChange="detalle()"></td>
                      <td id="dato1"><select name="moneda_det" id="moneda_det">
                        <option value="COL$">COL$</option>
                        <option value="USD$">USD$</option>              
                        <option value="EUR€">EUR€</option>
                        <option value="GBP£">GBP£</option>
                      </select>
                      </td>
                      <td id="dato1"><input type="number" name="valor_iva" id="valor_iva" value="" style="width:100px" min="0" step="0.0001" onChange="detalle()"></td>
                      <td id="dato1"><input type="number" placeholder="0,00" style="width:120px" min="0.00" step="0.0001" name="subtotal_det" value="" size="20" onBlur="detalle()"></td>
                      <td id="dato1"><input type="number" placeholder="0,00" style="width:120px" min="0.00" step="0.0001" name="total_det" value="" onBlur="detalle()"></td>
                    </tr>

                    <tr>
                      <td colspan="2" id="fuente1">CAUSANTE </td>
                      <td colspan="2" id="fuente1">CENTRO COSTOS </td> 
                      <td colspan="3" id="fuente1">CONCEPTO </td> 
                    </tr>
                    <tr>
                      <td colspan="2" id="fuente1"><input type="text" placeholder="Concepto 1" style="width:250px" name="concepto1" value="" > </td>
                      <td colspan="2" id="fuente1"><input type="text" placeholder="Centro Costos" style="width:250px" name="centro_costos" value="" > </td>
                      <td colspan="3" id="fuente1"><input type="text" placeholder="Concepto 2" style="width:250px" name="concepto2" value="" > </td> 
                    </tr>


                    <tr>
                      <td colspan="6" id="dato2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="6" id="dato2"><input class="botonGeneral" type="submit" value="ADD A O.C."></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_insert" value="form1">
                </form>
                <?php echo $conexion->header('footer'); ?>
      </body>
      </html>
      <script>
    
       $('#id_insumo_det').select2({ 
              ajax: {
                  url: "select3/proceso.php",
                  type: "post",
                  dataType: 'json',
                  delay: 250,
                  data: function (params) {
                      return {
                          palabraClave: params.term, // search term
                          var1:"id_insumo",//campo normal para usar
                          var2:"insumo",//tabla
                          var3:"",//where
                          var4:"ORDER BY descripcion_insumo ASC",
                          var5:"id_insumo",//clave
                          var6:"descripcion_insumo"//columna a buscar
                      };
                  },
                  processResults: function (response) {
                      return {
                          results: response
                      };
                  },
                  cache: true
              }
          }); 

       
      </script>
      <?php
      mysql_free_result($usuario);mysql_close($conexion1);

      mysql_free_result($orden_compra);

      mysql_free_result($insumos);
      ?>