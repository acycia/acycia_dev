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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

  $insertSQL = sprintf("INSERT INTO Tbl_generadores_valor (id_generadores_gv, valor_gv, fecha_ini_gv, fecha_fin_gv) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_generadores_gv'], "int"),
					   GetSQLValueString($_POST['valor_gv'], "double"),
                       GetSQLValueString($_POST['fecha_ini_gv'], "date"),
                       GetSQLValueString($_POST['fecha_fin_gv'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
   	

  header(sprintf("Location: costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=$_POST[fecha_ini_gv]&fecha_fin_gv=$_POST[fecha_fin_gv]"));
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
//cambio de estado a inactivo del anterior fecha

  $updateSQL = sprintf("UPDATE Tbl_generadores_valor SET estado_gv='1' WHERE fecha_ini_gv=%s", 
                       GetSQLValueString($_POST['fechaPasada'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL, $conexion1) or die(mysql_error());	  

	
	foreach ($_POST['id_gv'] as $key=>$v) {
    $a[]= $v;
}
	
/*	foreach ($_POST['id_m'] as $key=>$v) {
    $b[]= $v;
}*/
	foreach ($_POST['id_v'] as $key=>$v) {
    $c[]= $v;
}	
    $fecha1=$_POST['fecha_ini_gv'];
	$fecha2=$_POST['fecha_fin_gv'];		
	for($x=0; $x<count($a); $x++){
		// if(!empty($a[$x])&&!empty($b[$x]&&!empty($c[$x])){	
		/*echo "<script type=\"text/javascript\">alert(\" $a[$x] \");return false;history.go(-1)</script>";*/
  $insertSQL2 = sprintf("INSERT INTO Tbl_generadores_valor (id_generadores_gv, valor_gv, fecha_ini_gv, fecha_fin_gv) VALUES (%s, %s, %s, %s)",  
                       GetSQLValueString($a[$x], "text"),                 
                       GetSQLValueString($c[$x], "double"),
                       GetSQLValueString($fecha1, "date"),
					   GetSQLValueString($fecha2, "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
   header(sprintf("Location: costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=$fecha1&fecha_fin_gv=$fecha2"));
		 // }
	}
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Tbl_generadores_valor SET id_generadores_gv=%s, valor_gv=%s WHERE id_gv=%s", 
                       GetSQLValueString($_POST['id_generadores_gv'], "int"),					                   
                       GetSQLValueString($_POST['valor_gv'], "double"),
                       GetSQLValueString($_POST['id_gv'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "costos_generadores_asignacion_cif_gga.php?editar=0" . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_generadores = "SELECT * FROM Tbl_generadores WHERE id_generadores NOT IN(SELECT Tbl_generadores_valor.id_generadores_gv FROM Tbl_generadores_valor WHERE Tbl_generadores_valor.estado_gv='0') ORDER BY id_generadores ASC";
$generadores = mysql_query($query_generadores, $conexion1) or die(mysql_error());
$row_generadores = mysql_fetch_assoc($generadores);
$totalRows_generadores = mysql_num_rows($generadores);

//CONSULTA TODO LO QUE SE INGRESA POR FECHA 
$colname_generadores_inicio = "-1";
if (isset($_GET['fecha_ini_gv'])) {
  $colname_generadores_inicio = (get_magic_quotes_gpc()) ? $_GET['fecha_ini_gv'] : addslashes($_GET['fecha_ini_gv']);
}
$colname_generadores_fin = "-1";
if (isset($_GET['fecha_fin_gv'])) {
  $colname_generadores_fin = (get_magic_quotes_gpc()) ? $_GET['fecha_fin_gv'] : addslashes($_GET['fecha_fin_gv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_generadores_edit = sprintf("SELECT * FROM Tbl_generadores,Tbl_generadores_valor WHERE Tbl_generadores.id_generadores = Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.fecha_ini_gv = '%s' AND Tbl_generadores_valor.fecha_fin_gv='%s' ORDER BY Tbl_generadores.categoria_generadores,Tbl_generadores.nombre_generadores ASC", $colname_generadores_inicio, $colname_generadores_fin);
$generadores_edit = mysql_query($query_generadores_edit, $conexion1) or die(mysql_error());
$row_generadores_edit = mysql_fetch_assoc($generadores_edit);
$totalRows_generadores_edit = mysql_num_rows($generadores_edit);

//CARGA EL ID DEL QUE VA A EDITAR
$colname_valor_edit = "-1";
if (isset($_GET['id_gv'])) {
  $colname_valor_edit = (get_magic_quotes_gpc()) ? $_GET['id_gv'] : addslashes($_GET['id_gv']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_valor_edit = sprintf("SELECT * FROM Tbl_generadores_valor WHERE id_gv = %s", $colname_valor_edit);
$valor_edit = mysql_query($query_valor_edit, $conexion1) or die(mysql_error());
$row_valor_edit = mysql_fetch_assoc($valor_edit);
$totalRows_valor_edit = mysql_num_rows($valor_edit);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina ORDER BY nombre_maquina ASC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script language="javascript" type="text/javascript">
function validar() {
    DatosGestiones3('1','id_generadores_gv',form1.id_generadores_gv.value,'&fecha_ini_gv',form1.fecha_ini_gv.value,'&fecha_fin_gv',form1.fecha_fin_gv.value);
}
function validar_edit() {
    DatosGestiones3('1','id_generadores_gv',form2.id_generadores_gv.value,'&fecha_ini_gv',form2.fecha_ini_gv.value,'&fecha_fin_gv',form2.fecha_fin_gv.value);
}
function envioForm(){
	subm = document.form1.envio.value
if(subm=='1'){
	alert("Ya se ingreso un registro con las mismas caracteristicas ! verifique")
	return false;
	}
	return true;
	}
	
	
	function validaPorcentaje(){
		var porNombre=document.getElementsByName("porc")[0].value;
	if(porNombre !='0'){
    var porcen=parseFloat(document.getElementsByName("porc")[0].value);
	var valor=parseFloat(document.getElementsByName("valor_gv")[0].value);
	//document.formulario.ochenta_name.value = eval(document.formulario.numero.value) * 80 / 100;
	var porc=Math.round(valor*porcen)/100;	 
	document.getElementsByName("valor_gv")[0].value = porc;
	 }else{document.getElementsByName("valor_gv")[0].value = document.getElementsByName("valor_gv")[0].value;}
	}
</script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center">
<img src="images/cabecera.jpg"></td>
</tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
           <li><a href="menu.php">MENU PRINCIPAL</a> </li>
		   <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
</ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<table border="0" id="tabla1">
  <tr>
    <td colspan="3" id="fuente1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "ERROR AL ELIMINAR"; ?> </div> <?php }?></td>
    </tr>
  <tr>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="costos_listado_gga.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COSTOS"title="LISTADO COSTOS" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"><form method="POST" name="form1" action="<?php echo $editFormAction; ?>" onsubmit="return envioForm();">
      <table >     
        <tr>
          <td colspan="2" id="fuente1">FECHA INICIO:</td>
          <td colspan="2" id="fuente1">FECHA FIN:</td>
          </tr>
        <tr>
          <td colspan="2" id="fuente1"><input type="date" name="fecha_ini_gv" id="fecha_ini_gv" required="required" value="<?php echo $row_generadores_edit['fecha_ini_gv']?>" /></td>
          <td colspan="2" id="fuente1"><input type="date" name="fecha_fin_gv" id="fecha_fin_gv" required="required" value="<?php echo $row_generadores_edit['fecha_fin_gv']?>" onchange="validar();"/></td>
          </tr>
        <tr>
          <td colspan="4" id="fuente1"><div id="resultado_generador"></div></td>
          </tr>
        <tr>
          <td id="fuente2"><a href="costos_generadores_cif_gga.php" target="_blank" title="ADD GENERADORES CIF Y GGA">NOMBRE GENERADOR</a></td>
          <!--<td id="fuente2"><a href="maquinas.php" target="_blank" title="ADD MAQUINAS">MAQUINA</a></td>-->
          <td id="fuente2">%</td>
          <td id="fuente2">VALOR NETO</td>
          </tr>
          <tr id="tr3">
            <td id="detalle1"><select name="id_generadores_gv" id="id_gv" style="width:250px" onchange="validar();">
              <?php
			do {  
			?>
			<option value="<?php echo $row_generadores['id_generadores']?>"><?php echo $row_generadores['nombre_generadores']?></option>
			<?php
			} while ($row_generadores = mysql_fetch_assoc($generadores));
			  $rows = mysql_num_rows($generadores);
			  if($rows > 0) {
				  mysql_data_seek($generadores, 0);
				  $row_generadores = mysql_fetch_assoc($generadores);
			  }
			?>
            </select></td>
<!--            <td id="detalle2"><select name="maquina_gv" id="maquina_gv" style="width:100px" onchange="validar();">
              <option value="11" selected="selected">Sin Maquina</option>
              <?php
                do {  
                ?>
              <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['nombre_maquina']?></option>
              <?php
                } while ($row_maquinas = mysql_fetch_assoc($maquinas));
                  $rows = mysql_num_rows($maquinas);
                  if($rows > 0) {
                      mysql_data_seek($maquinas, 0);
                      $row_maquinas = mysql_fetch_assoc($maquinas);
                  }
                ?>
              </select>
              </td>-->
            <td id="detalle2">
            <input name="porc" type="number" id="porc" style="width:100px" value="0"  min="0" step="0.01" /></td>
            <td id="detalle2"><input name="valor_gv" id="valor_gv" type="number" min="0" step="0.01" style="width:120px" required="required" value="<?php echo $row_generadores_edit['codigo']; ?>" onchange="validaPorcentaje()"/></td>
            </tr>           
        <tr>
          <td id="dato2">&nbsp;</td>
          <td id="dato2">&nbsp;</td>
          <td colspan="2" id="dato2">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4" id="dato2"><input type="submit" id="boton3" value="ADICIONAR VALORES"></td>
          </tr>
        <tr>
          <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
     </table>   
      <input type="hidden" name="MM_insert" value="form1" >      
      </form></td></tr>
      
     <tr>
     <td colspan="3"  id="dato1" valign="bottom"><?php $editar=$_GET['editar']; $id_gv= $_GET['id_gv']; if($id_gv!='' && $id_gv!='0' && $editar!='0') { ?>
      <form method="POST" name="form2" action="<?php echo $editFormAction; ?>" onsubmit="return envioForm();">
        <fieldset> <legend>Editar Registro</legend> 
        <table>
          <tr>
            <td colspan="2" id="fuente2">GENERADOR</td>
           <!-- <td colspan="2"id="fuente2">MAQUINA</td>-->
           <td colspan="2" id="fuente2">VALOR NETO</td>
            </tr>
          <tr>
            <td colspan="2" id="detalle1"><input name="id_generadores_gv" type="hidden" value="<?php echo $row_valor_edit['id_generadores_gv'];?>"/>
            <?php  
				  $id_nge=$row_valor_edit['id_generadores_gv'];
				  $sqlngen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_nge'";
				  $resultngen= mysql_query($sqlngen);
				  $numngen= mysql_num_rows($resultngen);
				  if($numngen >='1')
				  { 
				  $nombre_gen = mysql_result($resultngen, 0, 'nombre_generadores');echo $nombre_gen; 
				  }?></td>
          <!--  <td colspan="2"  id="fuente1"><select name="maquina_gv" id="maquina_gv" style="width:100px" onchange="validar_edit();">
              <option value=""<?php if (!(strcmp("",$row_valor_edit['maquina_gv']))) {echo "selected=\"selected\"";} ?>>N.A</option>
              <?php
				   do {  
				   ?>
              <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_valor_edit['maquina_gv']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
              <?php
				   } while ($row_maquinas = mysql_fetch_assoc($maquinas));
				   $rows = mysql_num_rows($maquinas);
					 if($rows > 0) {
					  mysql_data_seek($maquinas, 0);
					  $row_maquinas = mysql_fetch_assoc($maquinas);
					  }
				    ?>
            </select></td>-->
            <td  colspan="2"id="fuente2"><input name="valor_gv" type="number" required="required" id="valor_gv" style="width:120px" min="0" step="0.01" value="<?php if($row_valor_edit['valor_gv']=="0.00"){echo " ";}else{echo redondear_entero($row_valor_edit['valor_gv']);} ?>" onchange="validaPorcentaje()"/></td>
            </tr>
          <tr>
            <td colspan="6" id="dato2"><input name="submit" type="submit" value="ACTUALIZAR VALOR" /></td>
            </tr>
          </table>
        <input type="hidden" name="MM_update" value="form2">
        <input type="hidden" name="id_gv" value="<?php echo $row_valor_edit['id_gv']; ?>">
        <input type="hidden" name="fecha_ini_gv" value="<?php echo $row_valor_edit['fecha_ini_gv']; ?>">
        <input type="hidden" name="fecha_fin_gv" value="<?php echo $row_valor_edit['fecha_fin_gv']; ?>">
        </fieldset>
        </form>
		<?php } ?>
        </td>     
        </tr> 
        
         
   <tr>
    <td colspan="2" id="dato1"><form method="POST" name="form3" action="<?php echo $editFormAction; ?>">
     <fieldset>
       <legend>Fechas <?php echo $_GET['fecha_ini_gv']." - " ?><?php echo $_GET['fecha_fin_gv'] ?></legend>         
     <table>   
        <tr>
          <td colspan="6" id="titulo5">
            Gran Total: <?php
					  $fecha1=$_GET['fecha_ini_gv'];
					  $fecha2=$_GET['fecha_fin_gv'];					   
					  $sqlgga="SELECT SUM(Tbl_generadores_valor.valor_gv) AS gga FROM Tbl_generadores_valor WHERE Tbl_generadores_valor.fecha_ini_gv='$fecha1' AND Tbl_generadores_valor.fecha_fin_gv='$fecha2'"; 
					  $resultgga=mysql_query($sqlgga); 
					  $numgga=mysql_num_rows($resultgga); 
					  if($numgga >= '1') 
					  { $gga=mysql_result($resultgga,0,'gga'); echo numeros_format($gga);
					  } else { echo " ";}?></td>
          <td colspan="3"id="dato1">
            <p>Recuerde que al guardar la copia del nuevo gga y cif, todos los anteriores quedan inactivos </p>
            <p>
              <input type="button" id="boton1" value="Copiar GGA y CIF Con Nueva fecha" style="display:block" onclick="mostrarFechas(this)"/>
            </p>
            <p>
              <input type="submit" id="boton2" value="Copiar GGA y CIF Con Nueva fecha" style="display:none"/>
            </p>
          </td>
          </tr>
        <?php  $id_gv= $row_generadores_edit['id_gv']; if($id_gv!='' && $id_gv!='0') { ?>
         <tr>
          <td id="fuente1">ITEMS</td>
          <td id="fuente1">GENERADOR</td>
         <!-- <td id="fuente1">MAQUINA</td>-->
          <td id="fuente1">VALOR</td>
          <td id="fuente1">CATEG.</td>
          <td id="fuente1"><strong>DEL.</strong></td>
          <td id="fuente1">
          </td>
          <td id="fuente1"><input name="fechaPasada" type="hidden" value="<?php echo $row_generadores_edit['fecha_ini_gv']?>" />
          Inicial:<input  name="fecha_ini_gv" id="fecha_ini_nueva" type="date" required="required" value="<?php echo $row_generadores_edit['fecha_ini_gv']?>" style="display:none" /><br />Final: <input  name="fecha_fin_gv" id="fecha_fin_nueva" type="date" required="required" value="<?php echo $row_generadores_edit['fecha_fin_gv']?>" style="display:none"/></td>
         </tr>
        <?php do { ?>
        <tr id="tr3">
          <td id="detalle1"><?php  echo $item+=1;  ?></td>         
          <td id="detalle1"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
            <?php  
				  $id_g=$row_generadores_edit['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre = mysql_result($resultgen, 0, 'nombre_generadores');echo $nombre; }else{echo "N.A";
				  }?>
          </a><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
          <input type="hidden" name="id_gv[]" id="id_gv[]"  value="<?php echo $id_g ?>"/>
          </a></td>
<!--          <td id="detalle1"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
            <?php  
				  $id_m=$row_generadores_edit['maquina_gv'];
				  $sqlm="SELECT * FROM maquina WHERE id_maquina='$id_m'";
				  $resultm= mysql_query($sqlm);
				  $numm= mysql_num_rows($resultm);
				  if($numm >='1')
				  { 
				  $id_m = mysql_result($resultm, 0, 'id_maquina');
				  $nombre = mysql_result($resultm, 0, 'nombre_maquina');echo $nombre; }else{echo "N.A";
				  }?>
          </a><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
          <input type="hidden" name="id_m[]"  value="<?php echo $id_m ?>"/>
          </a></td>-->
          <td id="detalle1"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">$ <?php echo number_format($row_generadores_edit['valor_gv'], 2, ",", "."); ?></a><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
            <input type="hidden" name="id_v[]" id="id_v[]"  value="<?php echo $row_generadores_edit['valor_gv'] ?>"/>
          </a></td>
          <td id="detalle1"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_generadores_edit['fecha_ini_gv']; ?>&amp;fecha_fin_gv=<?php echo $row_generadores_edit['fecha_fin_gv']; ?>&amp;id_gv=<?php echo $row_generadores_edit['id_gv'];?>" target="_top" style="text-decoration:none; color:#000000">
            <?php  
				  $id_g=$row_generadores_edit['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre_cat = mysql_result($resultgen, 0, 'categoria_generadores');echo $nombre_cat; }else{echo "N.A";
				  }?>
          </a></td>
          <td id="detalle1"><a href="javascript:elimina_complejos('id_genera_gv',<?php echo $row_generadores_edit['id_gv']; ?>,'&fecha_ini_gv',<?php echo $row_generadores_edit['fecha_ini_gv']; ?>,'&fecha_fin_gv',<?php echo $row_generadores_edit['fecha_fin_gv']; ?>,'costos_generadores_asignacion_cif_gga.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a></td>       
        </tr>
        <?php } while ($row_generadores_edit = mysql_fetch_assoc($generadores_edit)); ?>
         <?php }?>
        </table>
        </fieldset>
     <input type="hidden" name="MM_insert" value="form3" />
    </form></td>
      </tr>
      

       </table>
       
       </td>
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

mysql_free_result($generadores);

mysql_free_result($generadores_edit);


?>
