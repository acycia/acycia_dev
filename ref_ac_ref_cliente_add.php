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
session_start();
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
//////////////////////////////////////////////////////////////////////////////
$id_c=$_POST['id_c_rc'];
$nit=$_POST['str_nit_rc'];
$refac=$_POST['int_ref_ac_rc'];
$ver=$_POST['version_ref'];
$refcl=$_POST['str_ref_cl_rc'];
$desc=$_POST['str_descripcion_rc'];
$est=$_POST['int_estado_ref_rc'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (!empty ($_POST['id_c_rc'])&&!empty ($_POST['str_nit_rc'])&&!empty ($_POST['int_ref_ac_rc'])&&!empty ($_POST['version_ref'])&&!empty ($_POST['str_ref_cl_rc'])&&!empty ($_POST['str_descripcion_rc'])&&!empty ($_POST['int_estado_ref_rc'])){
    foreach($_POST['id_c_rc'] as $key=>$value)
      $a[]= $value;
    foreach($_POST['str_nit_rc'] as $key=>$value)
      $b[]= $value;
    foreach($_POST['int_ref_ac_rc'] as $key=>$value)
      $c[]= $value;
    foreach($_POST['version_ref'] as $key=>$value)
      $d[]= $value;
    foreach($_POST['str_ref_cl_rc'] as $key=>$value)
      $e[]= $value;
    foreach($_POST['str_descripcion_rc'] as $key=>$value)
      $f[]= $value;
    foreach($_POST['int_estado_ref_rc'] as $key=>$value)
      $g[]= $value;	

    for($i=0; $i<count($a); $i++) {
		  if(!empty($a[$i])&&!empty($b[$i])&&!empty($c[$i])&&!empty($d[$i])&&!empty($e[$i])&& !empty($g[$i])){ //no salga error con campos vacios
       $insertSQL = sprintf("INSERT INTO Tbl_refcliente (id_c_rc, str_nit_rc, int_ref_ac_rc, version_ref, str_ref_cl_rc, str_descripcion_rc, fecha_rc, str_responsable_rc, int_estado_ref_rc) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",

         GetSQLValueString($a[$i], "int"),
         GetSQLValueString($b[$i], "text"),
         GetSQLValueString($c[$i], "text"),
         GetSQLValueString($d[$i], "int"),
         GetSQLValueString($e[$i], "text"),
         GetSQLValueString($f[$i], "text"),
         GetSQLValueString($_POST['fecha_rc'], "date"),
         GetSQLValueString($_POST['str_responsable_rc'], "text"),
         GetSQLValueString($g[$i], "int"));

       mysql_select_db($database_conexion1, $conexion1);
       $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

       $insertGoTo = "ref_ac_ref_cliente_add.php";
       if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
      }
      header(sprintf("Location: %s", $insertGoTo));
    }
  }
}else{echo"<script type=\"text/javascript\">alert(\"No se puede guardar campos vacios.\")</script>";}
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_cliente = $conexion->llenaSelect('cliente','','ORDER BY nombre_c ASC');
$row_cliente2 = $conexion->llenaSelect('cliente','','ORDER BY nombre_c ASC');
$row_cliente3 = $conexion->llenaSelect('cliente','','ORDER BY nombre_c ASC');

$row_referencia = $conexion->llenaSelect('tbl_referencia',"WHERE estado_ref='1' ",'ORDER BY id_ref DESC');
$row_referencia2 = $conexion->llenaSelect('tbl_referencia',"WHERE estado_ref='1' ",'ORDER BY id_ref DESC');
$row_referencia3 = $conexion->llenaSelect('tbl_referencia',"WHERE estado_ref='1' ",'ORDER BY id_ref DESC');

/*
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM Tbl_refcliente ORDER BY id_refcliente DESC";
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);*/


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
 
<!-- sweetalert -->
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
 <?php echo $conexion->header('listas'); ?>
                  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="return validacion_select_refac_cliente();">
                    <table class="table table-bordered table-sm">    
                      <tr>
                        <td width="162" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
                        <td colspan="3" id="titulo2"><strong>ADICIONAR REF AC Y REF CLIENTE</strong></td>
                        <td id="dato2"><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" title="RESTAURAR" onClick="window.history.go()" ><a href="ref_ac_ref_cl_listado.php"><img src="images/opciones.gif" alt="LISTADO DE REFERENCIAS CLIENTE"title="LISTADO DE REFERENCIAS CLIENTE" border="0" style="cursor:hand;"></a></td>
                      </tr>
                      <tr>
                        <td id="fuente2">Fecha
                          <!--<input name="id_refcliente" type="hidden" value="<?php $num=$row_ver_nuevo['id_refcliente']+1; echo $num; ?>">-->
                        Registro</td>
                        <td colspan="3" id="fuente1">Registrado por </td>
                      </tr>
                      <tr>
                        <td id="dato2"><input name="fecha_rc" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" required/></td>
                        <td colspan="3" id="detalle1"><?php echo $row_usuario['nombre_usuario']; ?>
                        <input name="str_responsable_rc" type="hidden" id="str_responsable_rc" value="<?php echo $row_usuario['nombre_usuario']; ?>"></td>
                      </tr>
                      <tr>
                        <td id="fuente2">&nbsp;</td>
                        <td colspan="2" id="fuente2">&nbsp;</td>
                        <td id="fuente2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="dato2">&nbsp;</td>
                        <td colspan="2" id="dato2">&nbsp;</td>
                        <td id="dato2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="dato2"><div id="existe"></div></td>
                        <td colspan="3" id="dato2">&nbsp;</td>
                      </tr>
                      <tr id="tr1">
                        <tr id="tr1">
                          <td id="titulo4">REF AC</td>
                          <td id="titulo4">VERS.</td>
                          <td id="titulo4">CLIENTE</td>
                          <td id="titulo4">REF CLIENTE</td>
                          <td id="titulo4">DESCRIPCION</td>
                          <td id="titulo4">ESTADO</td>
                        </tr>
                        <tr><td id="dato1">
                          <select class="busqueda selectsMedio" name="int_ref_ac_rc[]" id="int_ref_ac_rc" onChange="if (form1.int_ref_ac_rc.value) { DefinicionRef(form1.int_ref_ac_rc.value); } else { alert('Debe digitar La Ref AC de acceso para validar su existencia en la BD'); }">
                            <option value="">SELECCIONE</option>
                            <?php  foreach($row_referencia as $row_referencia ) { ?>
                              <option value="<?php echo $row_referencia['cod_ref']?>"><?php echo $row_referencia['cod_ref']?></option>
                            <?php } ?>
                          </select> 
                          </td>
                        <td id="dato2"><div id="definicion"></div></td>
                        <td id="dato2">
                          <select class="busqueda selectsMedio" name="id_c_rc[]" id="id_c_rc" onChange="if (form1.id_c_rc.value) { DefinicionCliente(form1.id_c_rc.value); } else { alert('Debe digitar un Cliente'); }">
                            <option value="0">CLIENTE</option>
                            <?php  foreach($row_cliente as $row_cliente ) { ?>
                              <option value="<?php echo $row_cliente['id_c']?>"><?php echo $row_cliente['nombre_c']?></option>
                            <?php } ?>
                          </select> 
                           <div id="nit"></div>
                         </td>
                        <td id="dato2"><input name="str_ref_cl_rc[]" type="text" id="str_ref_cl_rc" value="" size="10"onBlur="conMayusculas(this)"></td>
                        <td id="dato2"><input name="str_descripcion_rc[]" type="text" id="str_descripcion_rc" value="" size="10"onBlur="conMayusculas(this)"></td>
                        <td id="dato2">
                          <select name="int_estado_ref_rc[]" id="int_estado_ref_rc">
                             <option value="1">ACTIVO</option>
                             <option value="0">INACTIVO</option>
                        </select>
                      </td>
                      </tr>
                      <tr id="tr1">
                        <td id="titulo4">REF AC</td>
                        <td id="titulo4">VERS.</td>
                        <td id="titulo4">CLIENTE</td>
                        <td id="titulo4">REF CLIENTE</td>
                        <td id="titulo4">DESCRIPCION</td>
                        <td id="titulo4">ESTADO</td>
                      </tr>
                      <tr>
                        <td id="dato1">
                          <select class="busqueda selectsMedio"  name="int_ref_ac_rc[]" id="int_ref_ac_rc2"onChange="if (form1.int_ref_ac_rc2.value) { DefinicionRef2(form1.int_ref_ac_rc2.value); } else { alert('Debe digitar La Ref AC de acceso para validar su existencia en la BD'); }">
                            <option value="">SELECCIONE</option>
                            <?php  foreach($row_referencia2 as $row_referencia2 ) { ?>
                              <option value="<?php echo $row_referencia2['cod_ref']?>"><?php echo $row_referencia2['cod_ref']?></option>
                            <?php } ?>
                          </select>

                        </td>
                        <td id="dato2"><div id="definicion2"></div></td>
                        <td id="dato2">
                          <select class="busqueda selectsMedio"  name="id_c_rc[]" id="id_c_rc2"style="width:200px" onChange="if (form1.id_c_rc2.value) { DefinicionCliente2(form1.id_c_rc2.value); } else { alert('Debe digitar un Cliente'); }">
                            <option value="0">CLIENTE</option>
                            <?php  foreach($row_cliente2 as $row_cliente2 ) { ?>
                              <option value="<?php echo $row_cliente2['id_c']?>"><?php echo $row_cliente2['nombre_c']?></option>
                            <?php } ?>
                          </select>

                         <div id="nit2"></div>
                        </td>
                        <td id="dato2"><input name="str_ref_cl_rc[]" type="text" id="str_ref_cl_rc2" value="" size="10" onBlur="conMayusculas(this)"></td>
                        <td id="dato2"><input name="str_descripcion_rc[]" type="text" id="str_descripcion_rc2" value="" size="10"onBlur="conMayusculas(this)"></td>
                        <td id="dato2">
                          <select name="int_estado_ref_rc[]" id="int_estado_ref_rc2">
                             <option value="1">ACTIVO</option>
                            <option value="0">INACTIVO</option>
                         </select> 
                       </td>
                      </tr>
                      <tr id="tr1">
                        <td id="titulo4">REF AC</td>
                        <td id="titulo4">VERS.</td>
                        <td id="titulo4">CLIENTE</td>
                        <td id="titulo4">REF CLIENTE</td>
                        <td id="titulo4">DESCRIPCION</td>
                        <td id="titulo4">ESTADO</td>
                      </tr>
                      <tr>
                        <td id="dato1">
                          <select class="busqueda selectsMedio" name="int_ref_ac_rc[]" id="int_ref_ac_rc3" onChange="if (form1.int_ref_ac_rc3.value) { DefinicionRef3(form1.int_ref_ac_rc3.value); } else { alert('Debe digitar La Ref AC de acceso para validar su existencia en la BD'); }">
                            <option value="">SELECCIONE</option>
                            <?php  foreach($row_referencia3 as $row_referencia3 ) { ?>
                              <option value="<?php echo $row_referencia3['cod_ref']?>"><?php echo $row_referencia3['cod_ref']?></option>
                            <?php } ?>
                          </select> 
                        </td>
                        <td id="dato2"><div id="definicion3"></div></td>
                        <td id="dato2">
                          <select class="busqueda selectsMedio" name="id_c_rc[]" id="id_c_rc3"style="width:200px"onChange="if (form1.id_c_rc3.value) { DefinicionCliente3(form1.id_c_rc3.value); } else { alert('Debe digitar un Cliente'); }">
                            <option value="0">CLIENTE</option>
                            <?php  foreach($row_cliente3 as $row_cliente3 ) { ?>
                              <option value="<?php echo $row_cliente3['id_c']?>"><?php echo $row_cliente3['nombre_c']?></option>
                            <?php } ?>
                          </select> 
                        <div id="nit3"></div>
                        </td>
                        <td id="dato2"><input name="str_ref_cl_rc[]" type="text" id="str_ref_cl_rc3" value="" size="10"onBlur="conMayusculas(this)"></td>
                        <td id="dato2"><input name="str_descripcion_rc[]" type="text" id="str_descripcion_rc3" value="" size="10"onBlur="conMayusculas(this)"></td>
                        <td id="dato2">
                          <select name="int_estado_ref_rc[]" id="int_estado_ref_rc3">
                            <option value="1">ACTIVO</option>
                            <option value="0">INACTIVO</option>
                        </select>
                      </td>
                      </tr>
                      <tr>
                        <td colspan="6" id="fuente2">Alguna inquietud o recomendaci&oacute;n favor comunicarse con sistemas@acycia.com        </td>
                      </tr>    
                      <tr>
                        <td colspan="6" id="dato2"><input type="submit" value="Add Referencia"></td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="form1">
                  </form> 
              </table>
            <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia);

mysql_free_result($cliente);
?>
