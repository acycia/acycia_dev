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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//ESPACIO PARA EVALUAR EL FILTRO
mysql_select_db($database_conexion1, $conexion1);
$id_c = $_GET['Str_nit'];//VARIABLE PARA CONSULTAR DATOS DEL CLIENTE
//Filtra todos vacios
/*if($id_c == '0')
{
$query_cotizacion = "SELECT * FROM cotizacion ORDER BY n_cotiz DESC";
}*/

//Filtra cliente lleno
/*if($id_c != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_cotiza_bolsa WHERE Str_nit=$id_c ORDER BY Str_nit DESC";
}*/
//CONSULTA POR CLIENTE PARA EL BOTON MENU
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
//CODIGO PARA VISUALIZACION DATOS DEL CLIENTE
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_cliente = ("SELECT * FROM Tbl_cotiza_bolsa, cliente WHERE Tbl_cotiza_bolsa.Str_nit ='$id_c' AND Tbl_cotiza_bolsa.Str_nit = cliente.nit_c");
$cotizacion_cliente = mysql_query($query_cotizacion_cliente, $conexion1) or die(mysql_error());
$row_cotizacion_cliente = mysql_fetch_assoc($cotizacion_cliente);
$totalRows_cotizacion_cliente = mysql_num_rows($cotizacion_cliente);
//CODIGO PARA VISUALIZAR COTIZACION DE BOLSAS SEGUN CLIENTE ESPECIFICO
$colname_ver_bolsa = "-1";   //ESTE CODIGO SE UTILIZA PARA LIMPIAR EL GET 
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_bolsa= (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_bolsa = sprintf("SELECT * FROM Tbl_cotiza_bolsa  WHERE  Tbl_cotiza_bolsa.Str_nit= '%s'",$colname_ver_bolsa);
$ver_bolsa = mysql_query($query_ver_bolsa, $conexion1) or die(mysql_error());
$num2=mysql_num_rows($ver_bolsa);
//CODIGO PARA VISUALIZAR COTIZACION DE LAMINAS SEGUN CLIENTE ESPECIFICO
$colname_ver_lamina = "-1";
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_lamina = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_lamina = sprintf("SELECT * FROM Tbl_cotiza_laminas  WHERE  Tbl_cotiza_laminas.Str_nit='%s'",$colname_ver_lamina);
$ver_lamina = mysql_query($query_ver_lamina, $conexion1) or die(mysql_error());
$num3=mysql_num_rows($ver_lamina);
//CODIGO PARA VISUALIZAR COTIZACION DE MATERIA PRIMA SEGUN CLIENTE ESPECIFICO
$colname_ver_materia = "-1";
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_materia= (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_materia = sprintf("SELECT * FROM Tbl_cotiza_materia_p  WHERE  Tbl_cotiza_materia_p.Str_nit= '%s'",$colname_ver_materia);
$ver_materia = mysql_query($query_ver_materia, $conexion1) or die(mysql_error());
$num4=mysql_num_rows($ver_materia);
//CODIGO PARA VISUALIZAR COTIZACION DE PACKING LIST SEGUN CLIENTE ESPECIFICO
$colname_ver_pl= "-1";
if (isset($_GET['Str_nit'])) 
{
  $colname_ver_pl = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_pl = sprintf("SELECT * FROM Tbl_cotiza_packing  WHERE  Tbl_cotiza_packing.Str_nit= '%s'",$colname_ver_pl);
$ver_pl = mysql_query($query_ver_pl, $conexion1) or die(mysql_error());
$num5=mysql_num_rows($ver_pl);
//CODIGO PARA VISUALIZAR REFERENCIAS

mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref = "SELECT * FROM Tbl_cliente_referencia  ORDER BY N_referencia DESC";
$ref = mysql_query($query_ver_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//CONSULTA POR REFERENCIAS
$colname_ver_ref= "-1";
if (isset($_GET['ref'])) 
{
  $colname_ver_ref= (get_magic_quotes_gpc()) ? $_GET['ref'] : addslashes($_GET['ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref2 = sprintf("SELECT * FROM Tbl_cotiza_bolsa WHERE N_referencia_c='%s'",$colname_ver_ref);
$ver_ref2 = mysql_query($query_ref2, $conexion1) or die(mysql_error());
$num6=mysql_num_rows($ver_ref2);

mysql_select_db($database_conexion1, $conexion1);
$query_ref3 = sprintf("SELECT * FROM Tbl_cotiza_laminas WHERE N_referencia_c='%s'",$colname_ver_ref);
$ver_ref3 = mysql_query($query_ref3, $conexion1) or die(mysql_error());
$num7=mysql_num_rows($ver_ref3);

mysql_select_db($database_conexion1, $conexion1);
$query_ref4 = sprintf("SELECT * FROM Tbl_cotiza_materia_p WHERE N_referencia_c='%s'",$colname_ver_ref);
$ver_ref4 = mysql_query($query_ref4, $conexion1) or die(mysql_error());
$num8=mysql_num_rows($ver_ref4);

mysql_select_db($database_conexion1, $conexion1);
$query_ref5 = sprintf("SELECT * FROM Tbl_cotiza_packing WHERE N_referencia_c='%s'",$colname_ver_ref);
$ver_ref5 = mysql_query($query_ref5, $conexion1) or die(mysql_error());
$num9=mysql_num_rows($ver_ref5);
//FIN DE CONSULTA POR REFERENCIAS


//varias consultas dependientdo
/*mysql_select_db($database_conexion1, $conexion1);
$ref2 = $_GET['ref'];
//Filtra para bolsas
if(isset($ref2) )
{
$query_cotizacion = "SELECT * FROM Tbl_cotiza_bolsa WHERE N_referencia_c='$ref2'";
}
//Filtra para laminas
if($ref2 != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_cotiza_laminas WHERE N_referencia_c='$ref2'";;
}
//Filtra para materia prima
if($ref2 != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_cotiza_materia_p WHERE N_referencia_c='$ref2'";
}
//Filtra para packing list
if($ref2 != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_cotiza_packing WHERE N_referencia_c='$ref2'";
}
$query_limit_cotizacion = sprintf("%s", $query_cotizacion);
$ver_ref2 = mysql_query($query_limit_cotizacion, $conexion1) or die(mysql_error());
$num6== mysql_fetch_assoc($ver_ref2);*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<body>
<div align="center">
<table id="tablaexterna">
<tr>
<td><table id="tablainterna">
  <tr>
    <td><table id="tablainterna">
      <tr>
        <td rowspan="2" id="fondo" width="30%"><img src="images/logoacyc.jpg"></td>
        <td colspan="2"><div id="titulo1">COTIZACIONES</div>
            <div id="fondo">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6</strong><br>
              Carrera 45 No. 14 - 15  Tel: 311-21-44 Fax: 2664123  Medellin-Colombia<br>
              Emal: alvarocadavid@acycia.com</div></td>
      </tr>
      <tr>
        <td id="fondo_2">CODIGO : R1 - F03</td>
        <td id="fondo_2">VERSION : 0</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table id="tablainterna">
      <tr>
        <td id="fuente10">
            <FORM action="cotizaciones_clientes.php"target='_blank' method="GET" name="consulta4"> 
            REF.
            <SELECT  NAME="ref" id="ref">
            <option value="<? echo $row_ref['N_referencia'] ?>">
             <?php  do { ?>            
            <OPTION VALUE="<?php echo $row_ref['N_referencia']; ?>"><?php echo $row_ref['N_referencia']; ?><?php
		} while ($row_ref = mysql_fetch_assoc($ref));
		  $rows = mysql_num_rows($ref);
		  if($rows > 0) {
			  mysql_data_seek($ref, 0);
			  $row_ref = mysql_fetch_assoc($ref);
		  }
		?>                        
            <input type="submit" name="Submit" value="BUSQUEDA" />     
            </SELECT> </FORM>  
</td>
        <td id="fuente10">
        <form name="form1" method="get" action="cotizaciones_clientes.php">
              CLIENTE:
<select name="Str_nit" id="Str_nit">
            <option value="0" <?php if (!(strcmp(0, $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>>Cliente</option>
            <?php
do {  
?>
            <option value="<?php echo $row_cliente['nit_c']?>"<?php if (!(strcmp($row_cliente['nit_c'], $_GET['Str_nit']))) {echo "selected=\"selected\"";} ?>><?php echo $row_cliente['nombre_c']?></option>
            <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
          </select>
          <input type="submit" name="Submit" value="CONSULTA" onclick="if(consulta.Str_nit.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>
        </form>      
        </td>
        </tr>
      <tr>
        <td id="fuente4" width="50%">FECHA : <?php 
		$fecha1=$row_cotizacion_cliente['fecha_creacion'];
        $dia1=substr($fecha1,8,2);
		$mes1=substr($fecha1,5,2);
        $ano1=substr($fecha1,0,4);
		if($mes1=='01')
		{
		  echo "Enero"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='02')
		{
		  echo "Febrero"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='03')
		{
		  echo "Marzo"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='04')
		{
		  echo "Abril"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='05')
		{
		  echo "Mayo"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='06')
		{
		  echo "Junio"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='07')
		{
		  echo "Julio"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='08')
		{
		  echo "Agosto"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='09')
		{
		  echo "Septiembre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='10')
		{
		  echo "Octubre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='11')
		{
		  echo "Noviembre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='12')
		{
		  echo "Diciembre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		?></td>
        <td id="fuente4" width="50%">REGISTRO : <?php echo $row_cotizacion_cliente['Str_usuario']; ?> </td>
      </tr>
      <tr>
        <td id="fuente6">CLIENTE : <?php echo $row_cotizacion_cliente['nombre_c']; ?></td>
        <td id="fuente6">NIT : <?php echo $row_cotizacion_cliente['nit_c']; ?></td>
      </tr>
      <tr>
        <td id="fuente6">PAIS / CIUDAD : <?php echo $row_cotizacion_cliente['pais_c']; ?> / <?php echo $row_cotizacion_cliente['ciudad_c']; ?></td>
        <td id="fuente6">TELEFONO : <?php echo $row_cotizacion_cliente['telefono_c']; ?></td>
      </tr>
      <tr>
        <td id="fuente6">EMAIL : <?php echo $row_cotizacion_cliente['email_comercial_c']; ?></td>
        <td id="fuente6">FAX : <?php echo $row_cotizacion_cliente['fax_c']; ?></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente6">DIRECCION : <?php echo $row_cotizacion_cliente['direccion_c']; ?></td>
        </tr>
      <tr>
        <td id="fuente6">CONTACTO COMERCIAL : <?php echo $row_cotizacion_cliente['contacto_c']; ?></td>
        <td id="fuente6">CARGO : <?php echo $row_cotizacion_cliente['cargo_contacto_c']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center">
          <?php if($num2!='0')
{ ?>
      <table id="tablainterna" >
        <tr>
          <td width="139" colspan="<?php echo $num2+1; ?>" nowrap id="fuente4"><strong>COTIZACION BOLSAS</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td width="549" id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>        
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">ANCHO(cm)  </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_ancho); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ALTO(cm) </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_alto); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">FUELLE(cm)</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_fuelle); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE (micras) </td>
         <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_calibre); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TROQUEL</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_troquel); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">BOLSILLO PORTAGUIA(cm) </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_bolsillo); if($var=='1'){ echo "SI"; }else{echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TAMAÑO BOLSILLO(cm) </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_tamano_bolsillo); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SOLAPA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_solapa); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_bolsa,$i,N_precio); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UNIDAD VENTA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PLAZO DE PAGO</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_plazo); echo $var; ?></td>
          <?php } ?>
        </tr>          
        <tr>
          <td id="fuente7">INCOTERMS</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_incoterms); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIPO/COEXTRUSION </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_tipo_coextrusion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CAPA EXTERNA COEXTRUSION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_capa_ext_coext); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CAPA INTERNA COEXTRUSION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,Str_capa_inter_coext); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD IMPRESION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_cant_impresion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">COLORES IMPRESION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,N_colores_impresion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CYRELES</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_cyreles); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">SELLADO SEGURIDAD</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_seguridad); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO PERMANENTE</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_permanente); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";}  ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO RESELLABLE</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_resellable); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";}  ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO HOTMELT</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_hotm); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">SELLADO LATERAL</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_lateral); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO PLANO</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_plano); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO HILO</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_hilo); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>  
        <tr>
          <td id="fuente7">SELLADO HILO/PLANO</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
         <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_sellado_hilop); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">NUMERACION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_bolsa,$i,B_numeracion); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>                                                     
      </table>
      <?php }?>
      <?php if($num5!='0')
{ ?>
      <table id="tablainterna">
        <tr>
          <td width="177" colspan="<?php echo $num5+1; ?>" nowrap id="fuente4"><strong>COTIZACION PACKING LIST</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>          
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td width="511" id="fuente2"><?php $var=mysql_result($ver_pl,$l,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">ANCHO(cm)</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_pl,$l,N_ancho); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ALTO(cm)</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_pl,$l,N_alto); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD </td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_pl,$l,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_pl,$l,N_calibre); echo $var; ?>		  </td><?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">INCOTERMS</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_pl,$l,Str_incoterms);	echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_pl,$l,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_pl,$l,N_precio_vnta); echo $var; ?></td><?php } ?>
        </tr>
          <td id="fuente7">BOCA ENTRADA</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
		  <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,Str_boca_entrada); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">COLORES IMPRESION</td>
		  <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,N_colores_impresion); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CYRELES</td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,B_cyreles);if($var=='1'){ echo "SI"; }else if($var=='2'){echo "NO";} ?></td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UBIC. ENTRADA</td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,Str_ubica_entrada); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">LAMINA 1</td>
         <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,Str_lam1); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">LAMINA 2</td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,Str_lam2); echo $var; ?>		  </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UNIDAD DE VENTA</td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,Str_unidad_vta); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PLAZO DE PAGO</td>
          <?php  for ($l=0;$l<=$num5-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_pl,$l,Str_plazo); echo $var; ?>		  </td><?php } ?>
        </tr>         
      </table>       
      <?php }?>
      <?php if($num3!='0')
{ ?>
      <table id="tablainterna">
        <tr>
          <td width="144" colspan="<?php echo $num3+1; ?>" nowrap id="fuente4"><strong>COTIZACION LAMINAS</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_lamina,$j,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>          
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_lamina,$j,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">ANCHO(cm) </td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td width="544" id="fuente2"><?php $var=mysql_result($ver_lamina,$j,N_ancho); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">REPETICION</td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_lamina,$j,N_repeticion); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE (micras) </td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_lamina,$j,N_calibre); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD(mts)</td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_lamina,$j,N_cantidad_metros_r); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">INCOTERMS</td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_lamina,$j,Str_incoterms); echo $var; ?>		  </td><?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">COLORES IMPRESION </td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_lamina,$j,N_colores_impresion); echo $var; ?>	</td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CYRELES</td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_lamina,$j,B_cyreles);if($var=='1'){ echo "SI"; }else if($var=='2'){echo "NO";} ?></td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD</td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
		  <td id="fuente2"><?php $var=mysql_result($ver_lamina,$j,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PESO MAX.</td>
		  <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_lamina,$j,N_peso_max); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">DIAMETRO MAX.</td>
          <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_lamina,$j,N_diametro_max); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
          <?php  for ($j=0;$j<=$num3-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_lamina,$j,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_lamina,$j,N_precio_k); echo $var; ?></td><?php } ?>
        </tr>
      </table>     
      <?php }?>
      <?php if($num4!='0')
{ ?>
      <table id="tablainterna">
        <tr>
          <td width="160" colspan="<?php echo $num4+1; ?>" nowrap id="fuente4"><strong>COTIZACION MATERIA P.</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_materia,$k,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>          
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
          <td width="528" id="fuente2"><?php $var=mysql_result($ver_materia,$k,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD(und)</td>
		  <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_materia,$k,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">INCOTERMS</td>
		  <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_materia,$k,Str_incoterms); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
		  <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_materia,$k,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_materia,$k,N_precio_vnta); echo $var; ?></td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">REFERENCIA</td>
		  <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_materia,$k,Str_referencia); echo $var; ?>		  </td><?php } ?>
        </tr>
      <tr>
          <td id="fuente7">UNIDAD DE VENTA</td>
		  <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
          <td id="fuente2">
		   <?php $var=mysql_result($ver_materia,$k,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
      <tr>
          <td id="fuente7">PLAZO DE PAGO</td>
		  <?php  for ($k=0;$k<=$num4-1;$k++) { ?>
          <td id="fuente2">
		   <?php $var=mysql_result($ver_materia,$k,Str_plazo); echo $var; ?></td>
          <?php } ?>
        </tr>         
      </table>       
      <?php }?>
<!--aqui empieza por referencia --> 
  <tr>
    <td align="center">
          <?php if($num6!='0')
{ ?>
      <table id="tablainterna" >
        <tr>
          <td width="139" colspan="<?php echo $num6+1; ?>" nowrap id="fuente4"><strong>REFERENCIA BOLSA</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td width="549" id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>        
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">ANCHO(cm)  </td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_ancho); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ALTO(cm) </td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_alto); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">FUELLE(cm)</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_fuelle); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE (micras) </td>
         <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_calibre); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TROQUEL</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_troquel); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">BOLSILLO PORTAGUIA(cm) </td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_bolsillo); if($var=='1'){ echo "SI"; }else{echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TAMAÑO BOLSILLO(cm) </td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_tamano_bolsillo); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SOLAPA</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_solapa); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_ref2,$i,N_precio); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UNIDAD VENTA</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PLAZO DE PAGO</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_plazo); echo $var; ?></td>
          <?php } ?>
        </tr>          
        <tr>
          <td id="fuente7">INCOTERMS</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_incoterms); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIPO/COEXTRUSION </td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_tipo_coextrusion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CAPA EXTERNA COEXTRUSION</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_capa_ext_coext); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CAPA INTERNA COEXTRUSION</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,Str_capa_inter_coext); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD IMPRESION</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_cant_impresion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">COLORES IMPRESION</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,N_colores_impresion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CYRELES</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_cyreles); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">SELLADO SEGURIDAD</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_seguridad); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO PERMANENTE</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_permanente); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";}  ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO RESELLABLE</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_resellable); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";}  ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO HOTMELT</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_hotm); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">SELLADO LATERAL</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_lateral); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO PLANO</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_plano); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SELLADO HILO</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_hilo); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>  
        <tr>
          <td id="fuente7">SELLADO HILO/PLANO</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
         <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_sellado_hilop); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">NUMERACION</td>
          <?php  for ($i=0;$i<=$num6-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref2,$i,B_numeracion); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>                                                     
      </table>
      <?php }?>
      <?php if($num7!='0')
{ ?>
      <table id="tablainterna">
        <tr>
          <td width="177" colspan="<?php echo $num7+1; ?>" nowrap id="fuente4"><strong>REFERENCIA PACKING LIST</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>          
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td width="511" id="fuente2"><?php $var=mysql_result($ver_ref3,$l,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">ANCHO(cm)</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_ref3,$l,N_ancho); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ALTO(cm)</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_ref3,$l,N_alto); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD </td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref3,$l,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref3,$l,N_calibre); echo $var; ?>		  </td><?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">INCOTERMS</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref3,$l,Str_incoterms);	echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref3,$l,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_ref3,$l,N_precio_vnta); echo $var; ?></td><?php } ?>
        </tr>
          <td id="fuente7">BOCA ENTRADA</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
		  <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,Str_boca_entrada); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">COLORES IMPRESION</td>
		  <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,N_colores_impresion); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CYRELES</td>
          <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,B_cyreles);if($var=='1'){ echo "SI"; }else if($var=='2'){echo "NO";} ?></td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UBIC. ENTRADA</td>
          <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,Str_ubica_entrada); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">LAMINA 1</td>
         <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,Str_lam1); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">LAMINA 2</td>
         <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,Str_lam2); echo $var; ?>		  </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UNIDAD DE VENTA</td>
         <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,Str_unidad_vta); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PLAZO DE PAGO</td>
         <?php  for ($l=0;$l<=$num7-1;$l++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref3,$l,Str_plazo); echo $var; ?>		  </td><?php } ?>
        </tr>         
      </table>       
      <?php }?>
      <?php if($num8!='0')
{ ?>
      <table id="tablainterna">
        <tr>
          <td width="144" colspan="<?php echo $num8+1; ?>" nowrap id="fuente4"><strong>REFERENCIA LAMINAS</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref4,$j,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>          
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref4,$j,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">ANCHO(cm) </td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td width="544" id="fuente2"><?php $var=mysql_result($ver_ref4,$j,N_ancho); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">REPETICION</td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_ref4,$j,N_repeticion); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE (micras) </td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_ref4,$j,N_calibre); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD(mts)</td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref4,$j,N_cantidad_metros_r); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">INCOTERMS</td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref4,$j,Str_incoterms); echo $var; ?>		  </td><?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">COLORES IMPRESION </td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref4,$j,N_colores_impresion); echo $var; ?>	</td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CYRELES</td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref4,$j,B_cyreles);if($var=='1'){ echo "SI"; }else if($var=='2'){echo "NO";} ?></td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD</td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
		  <td id="fuente2"><?php $var=mysql_result($ver_ref4,$j,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PESO MAX.</td>
		  <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref4,$j,N_peso_max); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">DIAMETRO MAX.</td>
          <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref4,$j,N_diametro_max); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
          <?php  for ($j=0;$j<=$num8-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref4,$j,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_ref4,$j,N_precio_k); echo $var; ?></td><?php } ?>
        </tr>
      </table>     
      <?php }?>
      <?php if($num9!='0')
{ ?>
      <table id="tablainterna">
        <tr>
          <td width="160" colspan="<?php echo $num9+1; ?>" nowrap id="fuente4"><strong>REFERENCIA MATERIA P.</strong></td>
        </tr>
        <tr>
          <td id="fuente7">COTIZACION N&deg; </td>
          <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_ref5,$k,N_cotizacion); echo $var; ?>          </td>
          <?php } ?>        
        </tr>          
        <tr>
          <td id="fuente7">REFERENCIA CLIENTE</td>
          <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
          <td width="528" id="fuente2"><?php $var=mysql_result($ver_ref5,$k,N_referencia_c); echo $var; ?>          </td>
          <?php } ?>        
        </tr>
        <tr>
          <td id="fuente7">CANTIDAD(und)</td>
		  <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_ref5,$k,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">INCOTERMS</td>
		  <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_ref5,$k,Str_incoterms); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO($)</td>
		  <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref5,$k,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_ref5,$k,N_precio_vnta); echo $var; ?></td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">REFERENCIA</td>
		  <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_ref5,$k,Str_referencia); echo $var; ?>		  </td><?php } ?>
        </tr>
      <tr>
          <td id="fuente7">UNIDAD DE VENTA</td>
		  <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
          <td id="fuente2">
		   <?php $var=mysql_result($ver_ref5,$k,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
      <tr>
          <td id="fuente7">PLAZO DE PAGO</td>
		  <?php  for ($k=0;$k<=$num9-1;$k++) { ?>
          <td id="fuente2">
		   <?php $var=mysql_result($ver_ref5,$k,Str_plazo); echo $var; ?></td>
          <?php } ?>
        </tr>         
      </table>
      <?php }?></td>
  </tr>  
  <tr>  
  
  <!--aqui termina impresion por referencia-->
    <td id="justificar"><strong>IMPORTANTE</strong>:  Las cantidades entregadas pueden variar en un 10%. Los calibres un 10% y en la  altura de la bolsa como en el ancho la variaci&oacute;n aceptada es de 5 mm. Las condiciones  comerciales para la elaboraci&oacute;n de este pedido son:<br>
      1. Orden de compra  debidamente aprobada incluyendo en ella este numero de cotizaci&oacute;n comos se&ntilde;al  de aprobaci&oacute;n de nuestros t&eacute;rminos y condiciones.<br>2. Arte aprobado y  firmado.<br>3. El costo de los  artes y cyreles se factura solo por una sola vez. Modificaciones al arte no son  posibles hasta terminar con toda la producci&oacute;n acordada. En caso contrario  cualquier modificaci&oacute;n acarrear&iacute;an nuevo cobro de elaboraci&oacute;n de artes y  Cyreles.<br>4. El precio de  venta hay que adicionarle el IVA correspondiente.<br>Quedamos  pendientes de sus comentarios al respecto y recuerde que el tiempo de  entrega se empieza a contar desde la recepci&oacute;n de la orden de compra y del arte  aprobado debidamente diligenciada por parte de ustedes.</td>
  </tr>
  <tr>
    <td id="justificar"><strong><?php echo $row_ver_texto['texto']; ?></strong></td>
  </tr>
  <tr>
    <td id="justificar"><strong>P.D.</strong> Esta oferta es valida por 30 días siempre y cuando no cambien los costos de las materias primas de tal manera que afecten sensiblemente los costos.</td>
  </tr>
</table>
</td>
</tr>
</table>
<table id="tablainterna" align="center">
  <tr>
    <td id="noprint" align="center"><?php if($_GET['tipo']=='1') { ?><a href="cotizacion_general_bolsas_edit.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>&Str_nit=<?php echo $_GET['Str_nit']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><?php } ?><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR" /><?php if($_GET['tipo']=='1') { ?><a href="cotizacion_bolsa_add.php"><img src="images/mas.gif" alt="ADD COTIZACION" border="0" style="cursor:hand;"/></a><?php } ?><a href="comercial.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMERCIAL" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><a href="cotizacion_general_menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onclick="window.close() "/></a></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($cliente);
mysql_free_result($cotizacion_cliente);
mysql_free_result($ver_bolsa);
mysql_free_result($ver_lamina);
mysql_free_result($ver_materia);
mysql_free_result($ver_pl);
mysql_free_result($ver_ref2);
mysql_free_result($ver_ref3);
mysql_free_result($ver_ref4);
mysql_free_result($ver_ref5);

?>