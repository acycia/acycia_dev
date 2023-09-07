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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
if (!empty ($_POST['id_rpt'])&&!empty ($_POST['valor_tiem_rt'])){
    foreach($_POST['id_rpt'] as $key=>$v)
    $a[]= $v;
    foreach($_POST['valor_tiem_rt'] as $key=>$v)
    $b[]= $v;
    $c= $_POST['id_op_rp'];	
	
	for($i=0; $i<count($a); $i++) {
		  if(!empty($a[$i])&&!empty($b[$i])){ //no salga error con campos vacios
 $insertSQLt = sprintf("INSERT INTO Tbl_reg_tiempo (id_rpt_rt,valor_tiem_rt,op_rt,int_rollo_rt,id_proceso_rt,fecha_rt) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($a[$i], "int"),
                       GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultt = mysql_query($insertSQLt, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty ($_POST['id_rtp'])&&!empty ($_POST['valor_prep_rtp'])){
    foreach($_POST['id_rtp'] as $key=>$n)
    $h[]= $n;
    foreach($_POST['valor_prep_rtp'] as $key=>$n)
    $l[]= $n;
    $c= $_POST['id_op_rp'];	
	
	for($x=0; $x<count($h); $x++) {
		  if(!empty($h[$x])&&!empty($l[$x])){ //no salga error con campos vacios
 $insertSQLp = sprintf("INSERT INTO Tbl_reg_tiempo_preparacion (id_rpt_rtp,valor_prep_rtp,op_rtp,int_rollo_rtp,id_proceso_rtp,fecha_rtp) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($h[$x], "int"),
                       GetSQLValueString($l[$x], "int"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultp = mysql_query($insertSQLp, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty ($_POST['id_rpd'])&&!empty ($_POST['valor_desp_rd'])){
    foreach($_POST['id_rpd'] as $key=>$k)
    $f[]= $k;
    foreach($_POST['valor_desp_rd'] as $key=>$k)
    $g[]= $k;
    $c= $_POST['id_op_rp'];	
	
	for($s=0; $s<count($f); $s++) {
		  if(!empty($f[$s])&&!empty($g[$s])){ //no salga error con campos vacios
 $insertSQLd = sprintf("INSERT INTO Tbl_reg_desperdicio (id_rpd_rd,valor_desp_rd,op_rd,int_rollo_rd,id_proceso_rd,fecha_rd) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($f[$s], "int"),
                       GetSQLValueString($g[$s], "double"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultd = mysql_query($insertSQLd, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty ($_POST['id_rpp'])&&!empty ($_POST['valor_prod_rp'])){
    foreach($_POST['id_rpp'] as $key=>$p)
    $m[]= $p;
    foreach($_POST['valor_prod_rp'] as $key=>$p)
    $o[]= $p;
    $c= $_POST['id_op_rp'];	
	
	for($z=0; $z<count($m); $z++) {
		  if(($m[$z]!=0)&& ($o[$z])!=0){ //no salga error con campos vacios
 $insertSQLkp = sprintf("INSERT INTO Tbl_reg_kilo_producido (id_rpp_rp,valor_prod_rp,op_rp,int_rollo_rkp,id_proceso_rkp,fecha_rkp) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($m[$z], "int"),
                       GetSQLValueString($o[$z], "text"),
					   GetSQLValueString($_POST['id_op_rp'], "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultkp = mysql_query($insertSQLkp, $conexion1) or die(mysql_error());
  
	    }
	  }
    } 
/* echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; */
echo "<script type=\"text/javascript\">window.close();</script>";
}
?>
<?php
$id_op=$_GET['idop']; 
$fecha=$_GET['fecha'];
$rollo=$_GET['rollo'];
$bolsas=$_GET['bolsas'];


 mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_muertos = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='1'";
$tiempo_muertos = mysql_query($query_tiempo_muertos, $conexion1) or die(mysql_error());
$row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
$totalRows_tiempo_muertos = mysql_num_rows($tiempo_muertos);

mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_preparacion = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='2'";
$tiempo_preparacion = mysql_query($query_tiempo_preparacion, $conexion1) or die(mysql_error());
$row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
$totalRows_tiempo_preparacion = mysql_num_rows($tiempo_preparacion);

mysql_select_db($database_conexion1, $conexion1);
$query_desperdicios = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='3'";
$desperdicios = mysql_query($query_desperdicios, $conexion1) or die(mysql_error());
$row_desperdicios = mysql_fetch_assoc($desperdicios);
$totalRows_desperdicios = mysql_num_rows($desperdicios);

//INFORMACION OP, CANTIDAD BOLSAS, CLIENTE, REF
$colname_op_carga = "-1";
if (isset($_GET['idop'])) {
  $colname_op_carga = (get_magic_quotes_gpc()) ? $_GET['idop'] : addslashes($_GET['idop']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_carga = sprintf("SELECT int_pesom_op,int_undxcaja_op,  int_cod_ref_op, int_undxpaq_op,id_termica_op,cinta_termica_op FROM Tbl_orden_produccion WHERE id_op='%s' AND b_borrado_op='0'",$colname_op_carga);
$op_carga = mysql_query($query_op_carga, $conexion1) or die(mysql_error());
$row_op_carga = mysql_fetch_assoc($op_carga);
$totalRows_op_carga = mysql_num_rows($op_carga);

$colname_ref = "-1";
if (isset($_GET['idop'])) {
  $colname_ref = (get_magic_quotes_gpc()) ? $_GET['idop'] : addslashes($_GET['idop']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT Tbl_referencia.id_ref, Tbl_referencia.cod_ref, Tbl_referencia.ancho_ref, Tbl_referencia.tipoCinta_ref, Tbl_referencia.calibreBols_ref,Tbl_referencia.bol_lamina_1_ref,Tbl_referencia.bol_lamina_2_ref,Tbl_referencia.adhesivo_ref, Tbl_referencia.tipoLamina_ref, Tbl_egp.marca_cajas_egp FROM Tbl_referencia,Tbl_orden_produccion,Tbl_egp WHERE Tbl_orden_produccion.id_op='%s'
AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref AND Tbl_referencia.cod_ref=Tbl_egp.n_egp",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref= mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN(1,2,5) AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
//INSUMOS TIPO LAMINAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo2 = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN(2,5) ORDER BY descripcion_insumo ASC";
$insumo2 = mysql_query($query_insumo2, $conexion1) or die(mysql_error());
$row_insumo2 = mysql_fetch_assoc($insumo2);
$totalRows_insumo2 = mysql_num_rows($insumo2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>  
<script>
//Metodo II: Deshabilitar el botón Enviar
function checkSubmit() {
    document.getElementById("btsubmit").value = "Enviando...";
    document.getElementById("btsubmit").disabled = true;
    return true;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposM() {
	var i=0;
 	var d = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rpt[]");
	file0.options[i] = new Option('T.Muertos','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_tiempo_muertos['nombre_rtp']?>','<?php echo $row_tiempo_muertos['id_rtp']?>');
	i++;
    <?php
        } while ($row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos));
         $rows = mysql_num_rows($tiempo_muertos);
             if($rows > 0) {
                 mysql_data_seek($tiempo_muertos, 0);
               $row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
        }?> 		
	file0.setAttribute("style", "width:120px" );
	d.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_tiem_rt[]");
	file.setAttribute("min", "0" );
	file.setAttribute("placeholder", "Tiempo minutos" );
	file.setAttribute("style", "width:65px" );
	d.appendChild(file); 
	
	
 	document.getElementById("moreUploads").appendChild(d);
 	upload_number++;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposP() {
	var i=0;
 	var e = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rtp[]");
	file0.options[i] = new Option('T.Preparacion','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_tiempo_preparacion['nombre_rtp']?>','<?php echo $row_tiempo_preparacion['id_rtp']?>');
	i++;
    <?php
        } while ($row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion));
         $rows = mysql_num_rows($tiempo_preparacion);
             if($rows > 0) {
                 mysql_data_seek($tiempo_preparacion, 0);
               $row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
        }?> 
	file0.setAttribute("style", "width:120px" );
	e.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_prep_rtp[]");
	file.setAttribute("min", "0" );
	file.setAttribute("placeholder", "Tiempo minutos" );
	file.setAttribute("style", "width:65px" );
	e.appendChild(file); 
	
 	document.getElementById("moreUploads2").appendChild(e);
 	upload_number++;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposD() {
	var i=0;
 	var f = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rpd[]");
	file0.options[i] = new Option('Desperdicio','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_desperdicios['nombre_rtp']?>','<?php echo $row_desperdicios['id_rtp']?>');
	i++;
    <?php
        } while ($row_desperdicios = mysql_fetch_assoc($desperdicios));
         $rows = mysql_num_rows($desperdicios);
             if($rows > 0) {
                 mysql_data_seek($desperdicios, 0);
               $row_desperdicios = mysql_fetch_assoc($desperdicios);
        }?>
	file0.setAttribute("style", "width:120px" );
	f.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_desp_rd[]" );
	file.setAttribute("min", "0" );
	file.setAttribute("step", "0.01" );
	file.setAttribute("placeholder", "Kilos" );
	file.setAttribute("style", "width:65px" );
	f.appendChild(file); 
	
 	document.getElementById("moreUploads3").appendChild(f);
 	upload_number++;
}
</script>
</head>
<body onload="metrosakilos();">
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
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" onsubmit="return validacion_select_bolsillo();" ><!--checkSubmit()-->
        <table id="tabla2">
          <tr>
            <td  nowrap="nowrap" colspan="4" id="subtitulo">AGREGAR TIEMPOS Y DESPERDICIOS </td>
            </tr>
          <tr>
            <td id="dato1">O.P N&deg;: 
              <input name="id_op_rp" type="number" id="id_op_rp" style="width:80px" readonly="readonly" value="<?php echo $id_op; ?>" />
              Ref: <?php echo $row_op_carga['int_cod_ref_op'] ?></td>
            <td id="dato1">Rollo N&deg;: 
              <input name="rollo_rp" min="1" style="width:40px" type="number" id="rollo_rp" readonly="readonly" required="required" value="<?php echo $rollo; ?>" /></td>
            <td colspan="2" id="dato3">Fecha y Hora:
              <input name="fecha_ini_rp" type="datetime" min="2000-01-02" size="19" required="required" value="<?php echo $fecha; ?>" readonly="readonly"/>            </td>
            </tr>
           <tr>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>
            <td  nowrap="nowrap" id="dato1">&nbsp;</td>
          </tr> 
          <tr id="tr1">
            <td id="dato1">Desperdicios</td>
            <td id="dato1">Tiempos Muertos</td>
            <td id="dato1">Tiempos Preparacion</td>
            <td id="dato1">&nbsp;</td>
          </tr>
          <tr id="tr1">
            <td id="dato1"><input type="button" name="button3" id="button3" value="Crear otra fila" onclick="tiemposD()" style="width:193px"/></td>
            <td id="dato1"><input type="button" name="button" id="button" value="Crear otra fila" onclick="tiemposM()" style="width:193px"/></td>
            <td id="dato1"><input type="button" name="button2" id="button2" value="Crear otra fila" onclick="tiemposP()" style="width:193px"/></td>
            <td id="dato1"><!--<input type="button" name="button4" id="button4" value="Crear otra fila" onclick="insumos()" style="width:193px"/>--></td>
            </tr>
          <tr>
            <td id="dato1"><div id="moreUploads3"></div></td>
            <td id="dato1"><div id="moreUploads" ></div></td>
            <td id="dato1" ><div id="moreUploads2"></div></td>
            <td id="dato1" ><!--<div id="moreUploads4"></div>--></td>
          </tr>
          <tr>
            <td nowrap="nowrap"  id="dato1">&nbsp;</td>
            <td nowrap="nowrap"  id="dato1">&nbsp;</td>
            <td id="dato1" nowrap="nowrap">&nbsp;</td>
            <td id="dato1" nowrap="nowrap">&nbsp;</td>
          </tr>
           <?php  
 	  $id_op=$_GET['idop'];
	  $sqlsell="SELECT id_rpp_rp FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='4' AND id_rpp_rp IN(1393,1407)"; 
	  $resultsell=mysql_query($sqlsell); 
	  $numsell=mysql_num_rows($resultsell); 
	  if($numsell >= '1') 
	  { $id_rpp_rp=mysql_result($resultsell,0,'id_rpp_rp'); }
 
 ?>           
          <tr id="tr1">
            <td colspan="2" nowrap="nowrap"  id="dato3">CONSUMO ESTIMADO DE EMPAQUE</td>
            <td nowrap="nowrap"  id="dato1">
 <!--<input type="checkbox"  checked   name="insumo" id="insumo" />
           <em>Registrar los insumos</em>--></td>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            </tr>
          <tr>
            <td nowrap="nowrap"  id="dato1">Bolsas Selladas: <?php echo $bolsas ?>
              <input name="bolsas" id="bolsas_r" type="hidden" value="<?php  echo $bolsas;?>" /></td>
            <td nowrap="nowrap"  id="dato1">Bolsas de empaque: <?php echo $bolsas/$row_op_carga['int_undxpaq_op'];
                 $bolsEmp=$row_op_carga['int_undxpaq_op'];?>
              <!--<input name="id_rpp[]" type="hidden" id="id_rpp" value="1393" />
              <input name="valor_prod_rp[]" type="hidden" id="valor_prod_rp[]" value="<?php echo ($bolsas/$bolsEmp) ?>" />--><!--bolsas de empaque--></td>
            <td id="dato1" nowrap="nowrap">Consumo en Cajas:
              <?php
			  echo $consCajas=redondear_entero($bolsas / $row_op_carga['int_undxcaja_op']); 
			   ?></td>
            <td id="dato1" nowrap="nowrap"><!--<input type="button" name="button4" id="button4" value="Crear otra fila" onclick="insumos()" style="width:193px"/>--></td>
          </tr>
          <tr>
            <td nowrap="nowrap"  id="dato1">Und x Caja: <?php echo $row_op_carga['int_undxcaja_op'] ?></td>
            <td nowrap="nowrap"  id="dato1"><p>Lamina Uno: 
              <?php   //$totalLam =($bolsas * $ancholam); 
			  echo $row_ref['bol_lamina_1_ref'];?>
            </p>
              <p>
                Lamina Dos: 
                <?php  echo $row_ref['bol_lamina_2_ref'];?>
            </p>
              <p>
                Calibre Bols:  
                <?php  echo $row_ref['calibreBols_ref'];?></td>
            <td id="dato1"><p>Tipo Adhesivo :</p>
              <p> 
  <select name="adhesivo_ref" disabled="disabled" id="adhesivo" style="width:180px">
    <option value="" <?php if (!(strcmp("N.A", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A</option>
    <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
    <option value="CINTA PERMANENTE" <?php if (!(strcmp("CINTA PERMANENTE", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA PERMANENTE</option>
    <option value="CINTA RESELLABLE" <?php if (!(strcmp("CINTA RESELLABLE", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA RESELLABLE</option>
    <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>
  </select>
              </p></td>
            <td id="dato1" nowrap="nowrap">&nbsp;</td>
          </tr>         
          <tr>
            <td nowrap="nowrap"  id="dato1">Unidad x Paquete:
              <?php  echo $bolsEmp;?>
              <?php $metros=($row_ref['ancho_ref'] * $bolsas);//METROS LINEAL
			        $tMetros=redondear_decimal($metros/100) ?></td>
            <td nowrap="nowrap"  id="dato1">kilos laminas Bols: 
              <?php
			 
			 $ancholam=($row_ref['bol_lamina_1_ref'] + $row_ref['bol_lamina_2_ref']);//ANCHO TOTAL DEL BOLSILLO
 			 $calibre=$row_ref['calibreBols_ref']; 
			 $totalkiloslam = pasarMillar($ancholam,$calibre,$metros); 
			 echo $totalkiloslam; ?></td>
            <td id="dato1" nowrap="nowrap"><?php   
			  $tipo=$row_ref['adhesivo_ref'];  
			  $kilosadhesivo = adhesivos($tipo,$tMetros); ?></td>
            <td id="dato1" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr>
            <td nowrap="nowrap"  id="dato1">Metros Aprox:<?php echo $tMetros;?></td>
            <td nowrap="nowrap"  id="dato1"><?php if($row_ref['bol_lamina_2_ref']!='0.00') {?>
              <select name="id_rpp[]" id="tipolam" style="width:100px">
              <option value="0"<?php if (!(strcmp("", $row_ref['tipoLamina_ref']))) {echo "selected=\"selected\"";} ?>>Tipo Lamina</option>
              <?php
do {  
?>
              <option value="<?php echo $row_insumo2['id_insumo']?>"<?php if (!(strcmp($row_insumo2['id_insumo'], $row_ref['tipoLamina_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo2['descripcion_insumo']?></option>
              <?php
} while ($row_insumo2 = mysql_fetch_assoc($insumo2));
  $rows = mysql_num_rows($insumo2);
  if($rows > 0) {
      mysql_data_seek($insumo2, 0);
	  $row_insumo2 = mysql_fetch_assoc($insumo2);
  }
?>
            </select>
              <input name="valor_prod_rp[]" style="width:70px" step="0.01" type="number" id="valorlam" value="<?php echo $totalkiloslam; ?>" /> 
              kg
              <?php }?></td>
            <td id="dato1" nowrap="nowrap"><select name="id_rpp[]" id="tipocinta" style="width:100px">
              <option value=""<?php if (!(strcmp("", $row_ref['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>  selected="selected">TIPO CINTA</option>
                <?php
do {  
?>
                <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_ref['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['codigo_insumo'] ."-".$row_insumo['descripcion_insumo']?></option>
                <?php
} while ($row_insumo = mysql_fetch_assoc($insumo));
  $rows = mysql_num_rows($insumo);
  if($rows > 0) {
      mysql_data_seek($insumo, 0);
	  $row_insumo = mysql_fetch_assoc($insumo);
  }
?>
            </select>
              <input name="valor_prod_rp[]" style="width:60px" step="0.01" type="number" id="valorcinta" value="<?php echo $kilosadhesivo; ?>" /></td>
            <td id="dato4" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            <td nowrap="nowrap"  id="dato2">&nbsp;         
            </td>
            <td nowrap="nowrap"  id="dato1"><?php if($row_op_carga['id_termica_op']<>"") {?>
              <select name="id_rpp[]" id="tipoterm" style="width:100px">
              <option value=""<?php if (!(strcmp("", $row_op_carga['id_termica_op']))) {echo "selected=\"selected\"";} ?>  selected="selected">TIPO TERMICA</option>
              <?php
do {  
?>
              <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_op_carga['id_termica_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['codigo_insumo'] ."-".$row_insumo['descripcion_insumo']?></option>
              <?php
} while ($row_insumo = mysql_fetch_assoc($insumo));
  $rows = mysql_num_rows($insumo);
  if($rows > 0) {
      mysql_data_seek($insumo, 0);
	  $row_insumo = mysql_fetch_assoc($insumo);
  }
?>
            </select>
              <input type="number" name="valor_prod_rp[]" id="valortermica" value="<?php echo ($row_op_carga['cinta_termica_op'] * $tMetros);?>" style="width:60px" min="0" step="0.01" required="required"/><?php echo $row_op_carga['cinta_termica_op'];?> * Metro Lineal
              <?php }?></td>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            </tr>
<tr>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            <td nowrap="nowrap"  id="dato1">&nbsp;</td>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            </tr>
            <tr>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            <td nowrap="nowrap"  id="dato2">&nbsp;         
            </td>
            <td nowrap="nowrap"  id="dato2"><input type="submit" id="btsubmit" value="GUARDAR"/></td>
            <td nowrap="nowrap"  id="dato2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" id="dato1">
            
            </td>
          </tr>
          <tr>
            <td colspan="3" id="dato1"></td>
          </tr>
          <tr>
            <td ></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><!--<img src="images/rf.gif" width="31" height="18" onClick="javascript:submit();window.opener.location.reload();window.close();">--></td>
            </tr>
        </table>
        <input name="id_proceso" type="hidden" id="id_proceso" value="4" />
        <input type="hidden" name="MM_insert" value="form2">
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
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
mysql_free_result($insumo);

mysql_free_result($tiempo_muertos);

mysql_free_result($op_carga);

mysql_free_result($tiempo_preparacion);

mysql_free_result($desperdicios);
?>
