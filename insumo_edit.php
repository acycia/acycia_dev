<?php require_once('Connections/conexion1.php'); ?>
<?php

 require_once('Connections/conexion1.php'); 
/* require_once("db/db.php");
require_once 'Models/Mgeneral.php'; */
 require_once("db/db.php"); 
 require_once("Controller/Cgeneral.php");

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



$conexion = new CgeneralController();



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE insumo SET codigo_insumo=%s, descripcion_insumo=%s, clase_insumo=%s, medida_insumo=%s, tipo_insumo=%s, valor_unitario_insumo=%s, stok_insumo=%s, estado_insumo=%s,quimicos=%s WHERE id_insumo=%s",
                       GetSQLValueString($_POST['codigo_insumo'], "text"),
                       GetSQLValueString($_POST['descripcion_insumo'], "text"),
                       GetSQLValueString($_POST['clase_insumo'], "int"),
                       GetSQLValueString($_POST['medida_insumo'], "text"),
                       GetSQLValueString($_POST['tipo_insumo'], "text"),
                       GetSQLValueString($_POST['valor_unitario_insumo'], "double"),
					             GetSQLValueString($_POST['stok_insumo'], "int"),
					             GetSQLValueString($_POST['estado_insumo'], "int"),
                       GetSQLValueString($_POST['quimicos'], "text"),
                       GetSQLValueString($_POST['id_insumo'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

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
		$sqlinv="UPDATE TblInventarioListado SET Cod_ref='$codigo', Codigo='$codigo', SaldoInicial='0', CostoUnd='$costoUnd', Acep='0', Tipo='$tipo', Modifico='$responsable' WHERE Codigo = '$codigo'";
		  mysql_select_db($database_conexion1, $conexion1);
		  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());   
		  }else{
		  $sqlinv="INSERT INTO TblInventarioListado (Fecha, Cod_ref, Codigo, SaldoInicial,  CostoUnd, Acep, Tipo, Responsable) VALUES ( '$fecha', '$codigo', '$codigo', '0', '$costoUnd', '0', '$tipo', '$responsable')";
		  mysql_select_db($database_conexion1, $conexion1);
		  $Result1 = mysql_query($sqlinv, $conexion1) or die(mysql_error());  
		  }


  $updateGoTo = "insumos.php";
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

$colname_insumo_edit = "-1";
if (isset($_GET['id_insumo'])) {
  $colname_insumo_edit = (get_magic_quotes_gpc()) ? $_GET['id_insumo'] : addslashes($_GET['id_insumo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_edit = sprintf("SELECT * FROM insumo WHERE id_insumo = %s", $colname_insumo_edit);
$insumo_edit = mysql_query($query_insumo_edit, $conexion1) or die(mysql_error());
$row_insumo_edit = mysql_fetch_assoc($insumo_edit);
$totalRows_insumo_edit = mysql_num_rows($insumo_edit);

mysql_select_db($database_conexion1, $conexion1);
$query_tipo = "SELECT * FROM tipo ORDER BY nombre_tipo ASC";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo = mysql_num_rows($tipo);

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

$colname_prov_edit = "-1";
if (isset($_GET['id_insumo'])) {
  $colname_prov_edit = (get_magic_quotes_gpc()) ? $_GET['id_insumo'] : addslashes($_GET['id_insumo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = sprintf("SELECT TblProveedorInsumo.id_pi, proveedor.proveedor_p FROM TblProveedorInsumo, proveedor WHERE TblProveedorInsumo.id_in=%s AND TblProveedorInsumo.id_p=proveedor.id_p ORDER BY proveedor.id_p DESC", $colname_prov_edit);
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);
 
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>

<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="AjaxControllers/js/elimina.js"></script> 

  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
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
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
        <table id="tabla2">
          <tr>
            <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td id="subtitulo">EDITAR INSUMO </td>
            <td id="fuente2"><a href="javascript:eliminar1('id_insumo',<?php echo $row_insumo_edit['id_insumo']; ?>,'insumo_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"></a><a href="insumos.php"><img src="images/cat.gif" alt="INSUMOS" border="0" style="cursor:hand;"></a><a href="insumos_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"></a><img src="images/ciclo1.gif" onClick="window.history.go()" style="cursor:hand;" ><a href="proveedor_insumo.php" target="_top"><img src="images/cliente.gif" alt="ADD PROVEEDOR" title="ADD PROVEEDOR" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td id="fuente1">CODIGO DEL INSUMO </td>
            <td id="fuente1">TIPO DE INSUMO </td>
          </tr>
          <tr>
            <td id="fuente1"><input type="number" min="1" step="1" style="width:100px" name="codigo_insumo" value="<?php echo $row_insumo_edit['codigo_insumo']; ?>" required ></td>
            <td id="fuente1"><select name="tipo_insumo">
            <option value=""<?php if (!(strcmp("", $row_insumo_edit['tipo_insumo']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
                  do {  
                  ?>
                    <option value="<?php echo $row_tipo['id_tipo']?>"<?php if (!(strcmp($row_tipo['id_tipo'], $row_insumo_edit['tipo_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo['nombre_tipo']?></option>
                                <?php
                  } while ($row_tipo = mysql_fetch_assoc($tipo));
                    $rows = mysql_num_rows($tipo);
                    if($rows > 0) {
                        mysql_data_seek($tipo, 0);
                  	  $row_tipo = mysql_fetch_assoc($tipo);
                    }
                  ?>
            </select></td>
          </tr>
          <tr>
            <td id="fuente1">DESCRIPCION</td>
            <td id="fuente1">CLASE DE INSUMO </td>
          </tr>
          <tr>
            <td id="fuente1"><input type="text" name="descripcion_insumo" required value="<?php echo $row_insumo_edit['descripcion_insumo']; ?>" size="30" onChange="MayusculaSinEspacios(this)"></td>
            <td id="fuente1"><select name="clase_insumo">
            <option value=""<?php if (!(strcmp("", $row_insumo_edit['clase_insumo']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
               do {  
               ?>
                  <option value="<?php echo $row_clases['id_clase']?>"<?php if (!(strcmp($row_clases['id_clase'], $row_insumo_edit['clase_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clases['nombre_clase']?></option>
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
            <td id="fuente1">UNIDAD DE MEDIDA </td>
            <td id="fuente1">VALOR UNITARIO </td>
            <td id="fuente1">QUIMICOS</td>
          </tr>
          <tr>
            <td id="fuente1">
              <select name="medida_insumo">
            <option value=""<?php if (!(strcmp("", $row_insumo_edit['medida_insumo']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
                do {  
                ?>
                  <option value="<?php echo $row_medidas['id_medida']?>"<?php if (!(strcmp($row_medidas['id_medida'], $row_insumo_edit['medida_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_medidas['nombre_medida']?></option>
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
          <td id="fuente1">
              <input type="text" name="valor_unitario_insumo" required value="<?php echo $row_insumo_edit['valor_unitario_insumo'];?>" size="20">
            </td>
          <td id="dato1">
              <select name="quimicos" id="quimicos">
               <option value="0"<?php if (!(strcmp("0", $row_insumo_edit['quimicos']))) {echo "selected=\"selected\"";} ?>>SELECCIONE...</option>
                <option value="SUSTANCIAS QUIMICAS"<?php if (!(strcmp("SUSTANCIAS QUIMICAS", $row_insumo_edit['quimicos']))) {echo "selected=\"selected\"";} ?>>SUSTANCIAS QUIMICAS</option>
                <option value="NA">NA</option>    
              </select>
            </td>
          </tr>
           <tr>
            <td id="fuente1"><strong>PROVEEDOR:</strong> <span id="resp" style=" color: red; display: none;" >Eliminacion Completa!</span></td>
            <td id="fuente1">ESTADO:
            <label for="estado_insumo"></label>
            <select name="estado_insumo" id="estado_insumo">
              <option value="0"<?php if (!(strcmp("0", $row_insumo_edit['estado_insumo']))) {echo "selected=\"selected\"";} ?>>ACTIVO</option>
              <option value="1"<?php if (!(strcmp("1", $row_insumo_edit['estado_insumo']))) {echo "selected=\"selected\"";} ?>>INACTIVO</option>
            </select>
          </td>
            <td id="fuente1">STOCK DEL PRODUCTO / KG: <input type="number" name="stok_insumo" id="stok_insumo" min="0"step="any" required value="<?php echo $row_insumo_edit['stok_insumo']; ?>" style=" width:100px" /></td>
          </tr>
          <tr>
            <td id="fuente1" nowrap="nowrap" > 
          <?php
               $item=0;
            do { 
           ?> 

           <span onclick="eliminaProv('id_pi','<?php echo $row_proveedores[id_pi];?>' )"  ><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR" title="ELIMINAR" border="0"></span> 
           
            <?php
             $item ++;
                 echo $item."-".$row_proveedores['proveedor_p']."<BR>";
             } while ($row_proveedores = mysql_fetch_assoc($proveedores));       
            ?>        
         </td> 
            <td id="dato4">

            </td>
            <td id="dato1"></td>
          </tr>
          <tr>
            <td id="fuente5">&nbsp;</td>
            <td id="dato8">&nbsp;</td>
            <td id="dato9"><input name="Fecha" id="Fecha" type="hidden" required min="2015-01-01" value="<?php echo date ('Y-m-d'); ?>" style="width:150px"/>
              <input name="responsable" id="responsable" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" /></td>
          </tr>
                    
          <tr>
            <td colspan="3" id="fuente2"><input type="submit" class="botonGeneral" value="Actualizar "></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_insumo" value="<?php echo $row_insumo_edit['id_insumo']; ?>">
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
<script type="text/javascript">
  
  function eliminaProv(vid,valores){

    ids=vid;//coloque la columna del id a actualizar
    valorid = ''+valores; 
    tabla='tblproveedorinsumo';
    url='view_index.php?c=cgeneral&a=Eliminar'; //la envio en campo proceso
    proceso= 'Eliminar';
    eliminar(valorid,ids,proceso,url,tabla);   

  }

</script>
<?php
mysql_free_result($usuario);

mysql_free_result($insumo_edit);

mysql_free_result($tipo);

mysql_free_result($clases);

mysql_free_result($medidas);
?>
