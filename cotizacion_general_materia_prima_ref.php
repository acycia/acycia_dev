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
include('rud_cotizaciones/rud_cotizacion_materia_p.php');//SISTEMA RUW PARA LA BASE DE DATOS 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

/*mysql_select_db($database_conexion1, $conexion1);
$query_egp = "SELECT * FROM egp ORDER BY n_egp DESC";
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);*/
/*$colname_ver_linc = "1";
if (isset($_POST['Str_referencia_m'])) 
{
  $colname_ver_linc = (get_magic_quotes_gpc()) ? $_POST['Str_referencia_m'] : addslashes($_POST['Str_referencia_m']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_verlinc = sprintf("SELECT * FROM Tbl_mp_vta ORDER BY Str_nombre ASC");
$verlinc = mysql_query($query_verlinc, $conexion1) or die(mysql_error());
$row_verlinc = mysql_fetch_assoc($verlinc);
$totalRows_verlinc = mysql_num_rows($verlinc);
 
//TRAE EL NUMERO DE LA ULTIMA COTIZACION Y SE AGREGA +1
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotizaciones ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);
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

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);
//TRAE EL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
//TRAE EL NUMRO DE REFERENCIA +1 PARA GUARDARLO
mysql_select_db($database_conexion1, $conexion1);
$query_ref= "SELECT id_mp,N_referencia FROM tbl_maestra_mp ORDER BY id_mp DESC";
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

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
</ul>
  </td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_nit','','R','N_precio_vnta_m','','RisNum','N_cantidad_m','','RisNum','N_comision','','RisNum','vendedor','','RisNum');return document.MM_returnValue;return confirActivo();">
    <table id="tabla2">
      <tr id="tr1">
        <td width="182" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td width="225" colspan="2" nowrap="nowrap" id="titulo2">Cotizacion Materia Prima</td>
        <td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2 </td>
      </tr>
      <tr>
        <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2" id="numero2"><strong>NIT N&deg;
          <input type="text" name="Str_nit" id="Str_nit" value="<?php echo $_GET['Str_nit']; ?>"readonly="readonly"/>
        </strong></td>
        <td colspan="2" id="fuente2"><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REFERENCIAS ACTIVAS" title="REFERENCIAS ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REFERENCIAS INACTIVAS" title="REFERENCIAS INACTIVAS" border="0" /></a><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
      </tr>
      <tr>
        <td colspan="2" id="titulo2">COTIZACION N&deg;</td>
        <td colspan="2" id="numero1"><strong>
          <input name="N_cotizacion" type="hidden" value="<?php if($_GET['N_cotizacion']==''){ $num=$row_cotizacion['N_cotizacion']+1; echo $num; }else{  $num=$_GET['N_cotizacion']; echo $num;} ?>" />
          <?php echo $num;  ?></strong>
        <!-- if($_GET['N_cotizacion']==''){ $num=$row_cotizacion['N_cotizacion']+1; echo $num; }else{  $num2=$_GET['N_cotizacion']; echo $num2;} --></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Fecha  Ingreso</td>
        <td colspan="2" id="fuente1">Hora Ingreso</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1"><input name="fecha_m" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" required="required"/></td>
        <td colspan="2" id="fuente1"><input name="hora_m" type="text" id="hora_m" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Estado de la Cotizaci&oacute;n</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1"><select name="B_estado" id="B_estado">
          <option value="0">Pendiente</option>
          <option value="1">Aceptada</option>
          <option value="2">Rechazada</option>
          <option value="3">Obsoleta</option>
        </select></td>
        <td colspan="2" id="dato4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente2">Nombre del Cliente</td>
      </tr>
      <tr>
        <td colspan="4" id="fuente1"><select  name="clientes" id="clientes" onblur="Javascript:document.form1.Str_nit.value=this.value;confirActivo();" onchange="if(form1.clientes.value) { consultanit4(); 
 } else{ alert('Debe Seleccionar un CLIENTE'); }" style="width:250px">
          <?php
do {  
?>
          <option value="<?php echo $row_clientes['nit_c']?>"<?php if (!(strcmp($row_clientes['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>><?php $cad=($row_clientes['nombre_c']); echo $cad;?></option>
          <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
  $rows = mysql_num_rows($clientes);
  if($rows > 0) {
      mysql_data_seek($clientes, 0);
	  $row_clientes = mysql_fetch_assoc($clientes);
  }
?>
        </select>
          <?php  
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
</ul></td>
      <td id="cabezamenu"></td>
      </tr>  
      <tr id="tr1">
        <td colspan="5" id="titulo2">MATERIA PRIMA</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">CARACTERISTICAS PRINCIPALES</td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">Referencia:</td>
        <td colspan="2" id="fuente3">Ver Archivo Adjunto</td>
        <td colspan="2" id="fuente1"><a href="cotizacion_general_materia_prima.php" target="_top"></a></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1"><select name="Str_referencia_m" id="Str_referencia_m">
          <option value="" <?php if (!(strcmp("", $_GET['Str_referencia_m']))) {echo "selected=\"selected\"";} ?>></option>
          <?php
do {  
?><option value="<?php echo $row_verlinc['id_mp_vta'];?>"<?php if (!(strcmp($row_verlinc['id_mp_vta'], $_GET['Str_referencia_m']))) {echo "selected=\"selected\"";} ?>><?php echo $row_verlinc['Str_nombre']?></option>
          <?php
} while ($row_verlinc = mysql_fetch_assoc($verlinc));
  $rows = mysql_num_rows($verlinc);
  if($rows > 0) {
      mysql_data_seek($verlinc, 0);
	  $row_verlinc = mysql_fetch_assoc($verlinc);
  }
?>
          </select></td>
        <td colspan="3" id="fuente1"><?php $idmp=$_GET['Str_referencia_m'];
		if($idmp!=''){ 
				$sql_select="SELECT Str_linc_archivo FROM Tbl_mp_vta WHERE id_mp_vta='$idmp'";
		$result_select= mysql_query($sql_select);
		$num_select= mysql_num_rows($result_select);
		if($num_select>='1') { 
		 $nombre_link=mysql_result($result_select,0,'Str_linc_archivo'); }
 		?>
          <a href="javascript:verFoto('archivosc/archivos_pdf_mp/<?php echo $nombre_link ?>','610','490')" target="_blank"><?php echo $nombre_link ?></a>
          <?php }else  {echo "<span class='rojo'>No tiene archivos adjuntos</span></Br>";echo "</Br><a href='cotizacion_general_materia_prima_ref_nueva.php' target='_top'>Crear Materia Prima</a>";}?>
          <input type="hidden" name="Str_linc" id="Str_linc" value="<?php echo $nombre_link; ?>"/></td>
        </tr>
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1"></td>
      </tr>
      <tr>
        <td id="fuente1"><a href="cotizacion_general_materia_prima.php" target="_top">Consultar  Materia Prima</a></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente5"></td>
      </tr>
      <tr>
        <td id="fuente3"></td>
        <td colspan="2" id="fuente3">&nbsp;</td>
        <td colspan="2" id="fuente6"></td>
      </tr>
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente4">      
        </td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="titulo1">PRECIO Y CONDICIONES COMERCIALES</td>
        <tr>
          <td  id="fuente1"> Moneda / Precio</td>
          <td colspan="2" id="fuente1">Unidad</td>
          <td width="134" id="fuente1">Plazo de pago</td>
          <td width="139" id="fuente1">Cantidad Unidades</td>
        </tr>
        <tr>
          <td  id="fuente1"><select name="Str_moneda_m" id="B_moneda_m">
            <option value="COL$">COL$</option>
            <option value="USD$">USD$</option>
            <option value="EUR&euro;">EUR&euro;</option>
          </select>
            <input name="N_precio_vnta_m" type="number" id="N_precio_vnta_m" style="width:100px" min="0" step="0.01" /></td>
          <td colspan="2" id="dato1">
            <select name="Str_unidad_vta" id="Str_unidad_vta">
              <option>*</option>
              <option value="PRECIO UNITARIO">PRECIO UNITARIO</option>
              <option value="PRECIO KILOS">PRECIO KILOS</option>
              <option value="PRECIO METROS">PRECIO METROS</option>
            </select>
          </td>
          <td id="fuente1"><select name="Str_plazo" id="Str_plazo">
            <option>*</option>
            <option value="ANTICIPADO">Anticipado</option>
            <option value="PAGO DE CONTADO">Pago de Contado</option>
            <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
            <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
            <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
            <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
            <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>           
          </select></td>
          <td id="fuente1"><input name="N_cantidad_m" type="number" style=" width:100px" step="0.01" id="N_cantidad_m" value="0" /><!--onKeyUp="puntos(this,this.value.charAt(this.value.length-1))"onClick="this.value = ''"--></td>
        </tr>
        <tr>
          <td id="fuente1">Incoterms: </td>
          <td id="fuente1">&nbsp;</td>
          <td colspan="2" id="fuente1">Vendedor</td>
          <td id="fuente1">Comision</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1"><select name="Str_incoterms_m" id="Str_incoterms_m">
            <option value="">*</option>
            <option value="EXW">EXW</option>
            <option value="FCA">FCA</option>
            <option value="FAS">FAS</option>
            <option value="FOB">FOB</option>
            <option value="CFR">CFR</option>
            <option value="CIF">CIF</option>
            <option value="CPT">CPT</option>
            <option value="CIP">CIP</option>
            <option value="DAF">DAF</option>
            <option value="DES">DES</option>
            <option value="DEQ">DEQ</option>
            <option value="DDU">DDU</option>
            <option value="DDP">DDP</option>
            </select>
            <a href="javascript:verFoto('archivosc/CuadroIncoterms.pdf','610','490')" >Ver Cuadro</a></td>
          <td colspan="2" id="fuente1">
            <select name="vendedor" id="vendedor" required >
            <option value="">Seleccione</option>
            <?php
do {  
?>
            <option value="<?php echo $row_vendedores['id_vendedor']?>"><?php echo $row_vendedores['nombre_vendedor']?></option>
            <?php
} while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
      mysql_data_seek($vendedores, 0);
	  $row_vendedores = mysql_fetch_assoc($vendedores);
  }
?>
          </select></td>
          <td id="fuente1"><input name="N_comision" type="number" style=" width:60px" step="0.1" id="N_comision" min="1" max="9" required="required"/>
            <strong>%</strong></td>
          <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente1"> Observaciones:</td>
      </tr>
      <tr>
        <td colspan="5" id="dato1"><textarea name="nota_m" cols="78" rows="2" id="nota_m"onKeyUp="conMayusculas(this)"></textarea></td>
      </tr>
      <tr>
        <td colspan="5" id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="fuente2">
          <input type="hidden" name="Str_tipo" id="Str_tipo" value="MATERIA PRIMA" />
          <input name='B_generica' type='hidden' value='0'/>
          <input name="responsable_modificacion" type="hidden" value="" />
          <input name="fecha_modificacion" type="hidden" value="" />
          <input name="hora_modificacion" type="hidden" value="" />
          <input name="id_mp" type="hidden" value="<?php echo $row_ref['id_mp']+1;?>" />
          <input name="N_referencia" type="hidden" value="<?php echo $row_ref['N_referencia']+1;?>" />
          <input name="tipo_usuario" type="hidden" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="valor" type="hidden" value="1" />
        <input name="submit" type="submit"value="COTIZAR" onclick="return confirActivo()"/></td>
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
mysql_free_result($cliente);
mysql_free_result($vendedores);
mysql_free_result($cotizacion);
mysql_free_result($ref);
mysql_free_result($verlinc);
mysql_free_result($verlinc2);
?>
