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

$conexion = new ApptivaDB();

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $cantidad=$_POST['cantidad_det'];
  $updateSQL = sprintf("UPDATE orden_compra_detalle SET n_oc_det=%s, id_insumo_det=%s, cantidad_det=%s, saldo_det=%s, valor_unitario_det=%s, descuento_det=%s, moneda_det=%s, total_det=%s, verificacion_det=%s, subtotal_det=%s, valor_iva=%s, concepto1=%s, concepto2=%s, centro_costos=%s WHERE id_det=%s",
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
   GetSQLValueString($_POST['centro_costos'], "text"), 
   GetSQLValueString($_POST['id_det'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
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

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM insumo ORDER BY descripcion_insumo ASC";
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);

$colname_insumo = "-1";
if (isset($_GET['id_insumo'])) {
  $colname_insumo = (get_magic_quotes_gpc()) ? $_GET['id_insumo'] : addslashes($_GET['id_insumo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = sprintf("SELECT * FROM insumo WHERE id_insumo = %s", $colname_insumo);
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
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
<body onLoad="detalle();">
 <?php echo $conexion->header('vistas'); ?>
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('id_insumo_det','','R','valor_unitario_det','','R','cantidad_det','','R','descuento_det','','R','total_det','','R');return document.MM_returnValue">
                    <table class="table">
                      <tr>
                        <td colspan="5" id="subtitulo">EDITAR ITEM </td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente1">ORDEN DE COMPRA N&deg; <input name="n_oc_det" type="hidden" value="<?php echo $row_detalle['n_oc_det']; ?>"><strong><?php echo $row_orden_compra['n_oc']; ?></strong></td>
                        <td colspan="2" id="fuente3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>
                      <tr>
                        <td colspan="5" id="fuente1">INSUMO</td>
                        <td id="fuente1">IVA</td>
                      </tr>
                      <tr>
                        <td colspan="5">
                          <select name="id_insumo_det" class="selectsGrande" onChange="detalle_oc()">
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_insumos['id_insumo']?>"<?php if (!(strcmp($row_insumos['id_insumo'], $_GET['id_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumos['descripcion_insumo']?></option>
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
                        <td id="dato1"><strong>VALOR EN INSUMO : </strong> $ <?php echo $row_insumo['valor_unitario_insumo']; ?></td>
                        <td colspan="2" id="dato1"><strong>CODIGO : </strong><?php echo $row_insumo['codigo_insumo']; ?></td>
                        <td id="dato1"><strong>MEDIDA  : </strong>
                          <?php 
                          $id_medida=$row_insumo['medida_insumo']; if($id_medida != '') { $resultmedida = mysql_query("SELECT * FROM medida WHERE id_medida = '$id_medida'");
                          $row_medida = mysql_fetch_assoc($resultmedida);
                          $totalRows_medida = mysql_num_rows($resultmedida);
                          if ($totalRows_medida > 0)
                          { 
                            echo $row_medida['nombre_medida'];
                          } 
                        } 
                        ?>
                      </td>
                      <td id="dato1">&nbsp;</td>
                    </tr>
                    <tr>
                      <td id="fuente1">VALOR  EN O.C. </td>
                      <td id="fuente1">CANTIDAD</td>
                      <td id="fuente1">DESCUENTO ($)</td>
                      <td id="fuente1">MONEDA</td>
                      <td id="fuente1">VALOR DEL IVA</td>
                      <td id="fuente1">SUB TOTAL</td>
                      <td id="fuente1">TOTAL</td>
                    </tr>
                    <tr>
                      <td id="dato1"><strong>
                        <input type="number" placeholder="0.0001" style="width:100px" min="0.0000" step="0.0001" name="valor_unitario_det" value="<?php echo $row_detalle['valor_unitario_det']; ?>" onChange="detalle()">
                      </strong></td>
                      <td id="dato1"><input type="number" placeholder="0,00" style="width:100px" min="0.00" step="0.01" name="cantidad_det" value="<?php echo $row_detalle['cantidad_det']; ?>" size="10" onChange="detalle()"></td>
                      <td id="dato1"><input type="number" placeholder="0,00" style="width:100px" min="0.00" step="0.01" name="descuento_det" value="<?php echo $row_detalle['descuento_det']; ?>" size="5" onChange="detalle()"></td>
                      <td id="dato1"><select name="moneda_det" id="moneda_det">
                        <option value="COL$"<?php if (!(strcmp("COL$", $row_detalle['moneda_det']))) {echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="USD$"<?php if (!(strcmp("USD$", $row_detalle['moneda_det']))) {echo "selected=\"selected\"";} ?>>USD$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€", $row_detalle['moneda_det']))) {echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£", $row_detalle['moneda_det']))) {echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select>
                     </td>
                     <td id="dato1"><input type="number" name="valor_iva" id="valor_iva" value="<?php echo $row_detalle['valor_iva']; ?>" style="width:100px" min="0" step="0.0001" onChange="detalle()"></td>
                     <td id="dato1"><input type="number" placeholder="0,00" style="width:120px" min="0.00" step="0.0001" name="subtotal_det" value="<?php echo $row_detalle['subtotal_det']; ?>" size="20" onBlur="detalle()"></td>
                     <td id="dato1"><input type="number" placeholder="0,00" style="width:120px" min="0.00" step="0.0001" name="total_det" value="<?php echo $row_detalle['total_det']; ?>" size="20" onBlur="detalle()"></td>
                    </tr>

                    <tr>
                      <td colspan="2" id="fuente1">CAUSANTE </td>
                      <td colspan="2" id="fuente1">CENTRO COSTOS </td> 
                      <td colspan="3" id="fuente1">CONCEPTO </td> 
                    </tr>
                    <tr>
                      <td colspan="2" id="fuente1"><input type="text" placeholder="Causante" style="width:250px" name="concepto1" value="<?php echo $row_detalle['concepto1']; ?>" > </td>
                      <td colspan="2" id="fuente1"><input type="text" placeholder="Centro Costos" style="width:250px" name="centro_costos" value="<?php echo $row_detalle['centro_costos']; ?>" > </td>
                      <td colspan="3" id="fuente1"><input type="text" placeholder="Concepto" style="width:250px" name="concepto2" value="<?php echo $row_detalle['concepto2']; ?>" > </td> 
                    </tr>

                    <tr>
                      <td colspan="5" id="dato2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="5" id="dato2"><input class="botonGeneral" type="submit" value="ACTUALIZAR O.C."></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form1">
                  <input type="hidden" name="id_det" value="<?php echo $row_detalle['id_det']; ?>">
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

      mysql_free_result($detalle);

      mysql_free_result($orden_compra);

      mysql_free_result($insumos);

      mysql_free_result($insumo);
      ?>