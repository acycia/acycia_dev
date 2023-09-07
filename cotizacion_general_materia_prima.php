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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $updateGoTo = "cotizacion_general_materia_prima_ref.php?Str_referencia_m=" . $_POST['Str_referencia_m'] . "&N_cotizacion=" . $_POST['N_cotizacion'] ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo)); 
}
?>
<?php
$conexion = new ApptivaDB();

/*include('rud_cotizaciones/rud_cotizacion_bolsa.php');*///SISTEMA RUW PARA LA BASE DE DATOS
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
$query_verlinc = "SELECT * FROM Tbl_mp_vta ORDER BY id_mp_vta";
$verlinc = mysql_query($query_verlinc, $conexion1) or die(mysql_error());
$row_verlinc = mysql_fetch_assoc($verlinc);
$totalRows_verlinc = mysql_num_rows($verlinc);

mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

 //TRAE EL NIT DEL CLIENTE
$colname_cliente = "-1";
if (isset($_GET['Str_nit'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script type="text/javascript">
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
</script>
<script type="text/javascript">
function confirActivo() {
 
swal({
  title: "Cliente Inactivo",
  text: "Quiere activar el cliente",
  type: "info",
  showCancelButton: true,
  closeOnConfirm: false,
  showLoaderOnConfirm: true,
},
 function(){
 	setTimeout(function(){
        var url="cambio_estado_cliente.php?"; 
		var campo1=<?php echo $_GET['N_cotizacion']; ?>; 
		var campo2="<?php echo $_GET['Str_nit']; ?>"; 
 		var campo3="5";	
		var dato1="N_cotizacion";
		var dato2="Str_nit";
		var dato3='id';	
		
	window.location.href=url+dato1+"="+campo1+"&"+dato2+"="+campo2+"&"+dato3+"="+campo3;
  
      swal("Proceso finalizado!");
  }, 2000);
    
 });
}
</script>
</head>
<body>
  <div class="spiffy_content">  
    <div align="center">
      <table id="tabla1"><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                  </ul>
                </div> 
                <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div>
                  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_referencia_m','','R','inactivo','','R');return document.MM_returnValue;
                  confirActivo()
                  "><table id="tabla1">
                    <tr id="tr1">
                      <td width="179" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
                      <td colspan="2" nowrap="nowrap" id="titulo2">Cotizacion Materia Prima</td>
                      <td width="282" nowrap="nowrap" id="codigo">VERSION: 2 </td>
                    </tr>
                    <tr>
                      <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
                      <td colspan="2" id="numero2"><strong>NIT N&deg; 
                        <input name="Str_nit" type="text" id="Str_nit" readonly="readonly" value="<?php echo $_GET['Str_nit']; ?>" />
                      </strong></td>
                      <td id="fuente2"><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
                      <td id="numero1"><strong>
                        <input name="N_cotizacion" type="hidden" value="<?php $num=$row_cotizacion['N_cotizacion']+1; echo $num; ?>" />
                        <?php echo $num; ?></strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Fecha  Ingreso</td>
                        <td id="fuente1">Hora Ingreso</td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1"><input name="fecha_m" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" required="required"/></td>
                        <td id="fuente1"><input name="hora_m" type="text" id="hora_m" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Ingresado por</td>
                        <td id="fuente1">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1"><input name="Str_usuario" type="text" id="Str_usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="true" onclick="confirActivo();"/></td>
                        <td id="dato4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente2">Nombre del Cliente</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente1"><select name="clientes" id="clientes" onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit4(); 
                        } else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px">
                        <option value="">*</option>
                        <?php
                        do {  
                          ?>
                          <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c']?></option>
                          <?php
                        } while ($row_clientes = mysql_fetch_assoc($clientes));
                        $rows = mysql_num_rows($clientes);
                        if($rows > 0) {
                          mysql_data_seek($clientes, 0);
                          $row_clientes = mysql_fetch_assoc($clientes);
                        }
                        ?>
                        </select><?php  
                        $activo=$row_cliente['estado_c'];
                        if($activo!='' && $activo!='ACTIVO'){
                          echo "Este cliente esta inactivo, debe activarlo en clientes"; 
                          echo"<input name='inactivo' id='inactivo' type='hidden' value='$activo' />";
                        }
                        ?></td>
                      </tr>
                      <tr>
                        <td id="cabezamenu"><ul id="menuhorizontal">
                          <li><a href="cotizacion_general_menu.php">Menu Cotizaciones</a></li>
                        </ul>
                      </td>
                      <td id="cabezamenu"><ul id="menuhorizontal">
                        <li><a href="perfil_cliente_add.php" target="_self">Crear Cliente</a></li>
                      </ul></td><td colspan="2" id="fuente1">&nbsp;</td>
                    </tr>      
                    <tr id="tr1">
                      <td colspan="4" id="titulo2">MATERIA PRIMA</td>
                    </tr>
                    <tr>
                      <td id="fuente1">&nbsp;</td>
                      <td colspan="2" id="fuente1">&nbsp;</td>
                      <td id="fuente1">&nbsp;</td>
                    </tr>
                    <tr>
                      <td id="fuente1">Seleccione Opcion</td>
                      <td colspan="2" id="fuente1">&nbsp;</td>
                      <td id="fuente1"></td>
                    </tr>
                    <tr>
                      <td colspan="4" id="fuente1"><select name="Str_referencia_m" id="Str_referencia_m">
                        <option value="">*</option>
                        <?php
                        do {  
                          ?>
                          <option value="<?php echo $row_verlinc['id_mp_vta']?>"<?php if (!(strcmp($row_verlinc['id_mp_vta'], $_GET['Str_referencia_m']))) {echo "selected=\"selected\"";} ?>><?php echo $row_verlinc['Str_nombre']?></option>
                          <?php
                        } while ($row_verlinc = mysql_fetch_assoc($verlinc));
                        $rows = mysql_num_rows($verlinc);
                        if($rows > 0) {
                          mysql_data_seek($verlinc, 0);
                          $row_verlinc = mysql_fetch_assoc($verlinc);
                        }
                        ?>
                      </select>
                      <a href="cotizacion_general_materia_prima_ref_nueva.php" target="_top">Crear Materia Prima</a></td>
                    </tr>
                    <tr>
                      <td id="fuente4">&nbsp;</td>
                      <td colspan="2" id="fuente4">&nbsp;</td>
                      <td id="fuente4">      
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4" id="dato1">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="4" id="fuente2"><input name="submit" class="botonGeneral" type="submit"value="CONSULTAR" <?php if($activo!='' && $activo!='ACTIVO'){ ?> onclick="return confirActivo();" <?php } ?>/></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_insert" value="form1">
                </form></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</td>
</tr>
</table>
</div>
</div> 
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($clientes);
mysql_free_result($cotizacion);
mysql_free_result($verlinc);
?>
