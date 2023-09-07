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
//CONTROL CANTIDAD

$can1=$_POST['int_cant_rd'];
$can2=$row_items['int_cantidad_io'];
if($can2>$can1){echo "LA CANTIDAD NO PUEDE SER MAYOR";}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
/* if ((isset($_POST['int_caja_rd'])&&($_POST['int_caja_rd']!=''))&& (isset($_POST['int_numd_rd'])&&($_POST['int_numd_rd']!=''))&& (isset($_POST['int_numd_rh'])&&($_POST['int_numh_rh']!=''))&& (isset($_POST['int_cant_rd'])&&($_POST['int_cant_rd']!=''))&& (isset($_POST['int_peso_rd'])&&($_POST['int_peso_rd']!=''))&& (isset($_POST['int_pesoneto_rd'])&&($_POST['int_pesoneto_rd']!=''))){*/
//VARIABLE DE CAMPOS DINAMICOS
//IGUALES PARA TODO REGISTRO	
	$int_remision_r_rd=$_POST['int_remision_r_rd'];
	$str_numero_oc_rd=$_POST['str_numero_oc_rd'];
	$int_item_io_rd=$_POST['int_item_io_rd'];
	$int_ref_io_rd=$_POST['int_ref_io_rd'];
	$int_mp_io_rd=$_POST['int_mp_io_rd'];
	$str_ref_cl_io_rd=$_POST['str_ref_cl_io_rd'];
	$int_total_cajas_rd=$_POST['int_total_cajas_rd'];
	$int_tolerancia_rd=$_POST['int_tolerancia_rd'];
	
	//DINAMICO	
	$int_caja_rd=$_POST['int_caja_rd'];
	$int_numd_rd=$_POST['int_numd_rd'];
	$int_numh_rd=$_POST['int_numh_rd'];
	$int_cant_rd=$_POST['int_cant_rd'];
	$int_peso_rd=$_POST['int_peso_rd'];
	$int_pesoneto_rd=$_POST['int_pesoneto_rd'];

/*if ($_POST['int_caja_rd']!=''&&$_POST['int_numd_rd']!=''&&$_POST['int_numh_rd']!=''&& $_POST['int_cant_rd']!=''&&$_POST['int_peso_rd']!=''&&$_POST['int_pesoneto_rd']){*/
    foreach($int_caja_rd as $key=>$value)
    $a[]= $value;
    foreach($_POST['int_numd_rd'] as $key=>$value)
    $b[]= $value;
    foreach($_POST['int_numh_rd'] as $key=>$value)
    $c[]= $value;
	foreach($_POST['int_cant_rd'] as $key=>$value)
    $d[]= $value;
	foreach($_POST['int_peso_rd'] as $key=>$value)
    $e[]= $value;
	foreach($_POST['int_pesoneto_rd'] as $key=>$value)
    $f[]= $value;
//}
for($i=0; $i<count($a); $i++) 
{
	
	
//INSERT DE ITEMS
$v=5;//Porcentaje
$e[$i];//peso
$f[$i];//peso/n
$pn=$e[$i]-(($e[$i]*$v)/100);// Restamos porcentaje de un numero entero
$tn=(($e[$i]*$v)/100);// Obtenemos porcentaje de un numero entero
//CALCULAR CANTIDAD
$acumulador = 0;
foreach($_POST['int_cant_rd'] as $precio){
   // echo $_POST['int_cant_rd'] . " = " . $precio . "<br />";
    $acumulador += $precio;
}
//FIN
//SUMAR EL 10% A CANTIDAD
//suma 10% a cantidad items
$cantr=$_POST["int_cantidad_io"];//cantidad del item para sumar 10%
$porcr=$_POST["int_tolerancia_rd"];//Porcentaje
$tcpr=$cantr+(($cantr*$porcr)/100);//total sumado
$cnr=(($cantr*$porcr)/100);//el 10% q se suma
//suma 10% a cantidad_rest items
$cant=$_POST["int_cantidad_rest_io"];//cantidad del item para sumar 10%
$porc=$_POST["int_tolerancia_rd"];//Porcentaje
$tcp=$cant+(($cant*$porc)/100);//total sumado
$cn=(($cant*$porc)/100);//el 10% q se suma
//FIN SUMA
//de cantidad_item y $cant de cantidad_rest controlo total % y suma de restante
$total_rest=$cnr+$cant;
if ($total_rest >= $acumulador) {
	if($a[$i]!=''&&$b[$i]!=''&&$c[$i]!=''&&$d[$i]!=''&&$e[$i]!=''){
$insertSQL = sprintf("INSERT INTO Tbl_remision_detalle2 (int_remision_r_rd,str_numero_oc_rd,int_item_io_rd,int_caja_rd,int_mp_io_rd,int_ref_io_rd,str_ref_cl_io_rd,int_numd_rd,int_numh_rd,int_cant_rd,int_peso_rd,int_pesoneto_rd,int_total_cajas_rd,int_tolerancia_rd,str_direccion_desp_rd) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",                     
                       GetSQLValueString($int_remision_r_rd, "int"),
                       GetSQLValueString($str_numero_oc_rd, "text"),
					   GetSQLValueString($int_item_io_rd, "int"),
					   GetSQLValueString($a[$i], "int"),
					   GetSQLValueString($int_mp_io_rd, "int"),
					   GetSQLValueString($int_ref_io_rd, "int"),
					   GetSQLValueString($str_ref_cl_io_rd, "text"),
					   GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($c[$i], "int"),
                       GetSQLValueString($d[$i], "int"),
                       GetSQLValueString($e[$i], "double"),
					   GetSQLValueString($pn, "double"),
					   GetSQLValueString($int_total_cajas_rd, "int"),
					   GetSQLValueString($int_tolerancia_rd, "int"),
					   GetSQLValueString($_POST["dir"], "text")
					   );
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());  


  
	}else {echo"campos vacios";}
  }
 }
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
//ITEMS
$colname_ver_items = "-1";   
if (isset($_GET['id_items'])){
$colname_ver_items= (get_magic_quotes_gpc()) ? $_GET['id_items'] : addslashes($_GET['id_items']);}
mysql_select_db($database_conexion1, $conexion1);
$query_items =sprintf("SELECT * FROM Tbl_items_ordenc  WHERE id_items = '%s' ORDER BY int_consecutivo_io DESC ",$colname_ver_items);
$items = mysql_query($query_items, $conexion1) or die(mysql_error());
$row_items = mysql_fetch_assoc($items);
$totalRows_items = mysql_num_rows($items);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/agregueCampos.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<!--CODIGO DE CONFIRMACION CIERRE DE PAGINA WEB-->
<script language="JavaScript">
var contador=0;
function incrementar() {
if(contador==3)
alert('Maximo permitido alcanzado: 3');
else {
contador++;
alert('El contador ahora vale :' + contador);}
}
function decrementar() {
if(contador==0)
alert('Minimo permitido alcanzado: 0');
else {
contador--;
alert('El contador ahora vale :' + contador);}
}
</script>
<script type="text/javascript">
// J /* Abrimos etiqueta de código Javascript */
var num=0;
num++;
var posicionCampo=1;

function addrem(){

nuevaFila = document.getElementById("tablaremision").insertRow(-1);

nuevaFila.id=posicionCampo;

nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='text' value="+num+++" size='2'  name='int_caja_rd["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='text' size='10' name='int_numd_rd["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='text' size='10' name='int_numh_rd["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='text' size='10' name='int_cant_rd["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='text' size='10' name='int_peso_rd["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='hidden' size='10' name='int_pesoneto_rd["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td> <input type='hidden' size='10' name='agregar["+posicionCampo+"]' ></td>";
nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda=nuevaFila.insertCell(-1);

nuevaCelda.innerHTML="<td><input type='button' value='Eliminar' onclick='eliminarremision(this)'></td>";

posicionCampo++;

}

function eliminarremision(obj){

var oTr = obj;

while(oTr.nodeName.toLowerCase()!='tr'){

oTr=oTr.parentNode;

}

var root = oTr.parentNode;

root.removeChild(oTr);

}
</script>
<script type="text/javascript">
/*calcular la cantidad mas %*/
function calcular_cantidad()
{
$var1=document.form1.largo_ref.value;
$var2=document.form1.solapa_ref.value;
$var3=parseInt($var1);
$var4=parseInt($var2);
$var5=document.form1.ancho_ref.value*(($var3+$var4))*document.form1.calibre_ref.value*0.00467;
$var5=parseFloat($var5);
$var5=Math.round($var5*100)/100 ;
document.form1.peso_millar_ref.value=$var5;
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
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1"onsubmit="MM_validateForm('int_total_cajas_rd','','RisNum','int_tolerancia_rd','','RisNum');return document.MM_returnValue">
        <table id="tabla2">
          <tr>
            <td colspan="3" id="subtitulo">AGREGAR REFERENCIA X CAJAS</td>
            </tr>
          <tr>
            <td id="fuente1">REMISION  N&deg; <strong><?php echo $_GET['int_remision']; ?></strong>
            <input name="int_remision_r_rd" type="hidden" value="<?php echo $_GET['int_remision']; ?>">
            <input name="str_numero_oc_rd" type="hidden" value="<?php echo $row_items['str_numero_io']; ?>">
            <input name="int_item_io_rd" type="hidden" value="<?php echo $row_items['id_items']; ?>">
            <input name="int_mp_io_rd" type="hidden" value="<?php echo $row_items['id_mp_vta_io']; ?>">
            <input name="int_ref_io_rd" type="hidden" value="<?php echo $row_items['int_cod_ref_io']; ?>">
            <input name="str_ref_cl_io_rd" type="hidden" value="<?php echo $row_items['int_cod_cliente_io']; ?>">
             </td>
            <td colspan="2" id="fuente2">&nbsp;</td>
            </tr>
          
          <tr>
            <td colspan="3" id="fuente3"><input type="button" onClick="incrementar()" value="aumentar"> 
<input type="button" onClick="decrementar()" value="disminuir"> 
<label>
<input name="textarea" type="text" value=aqui debe ir el valor>
</label></td>
            </tr>
         
          <tr id="tr2">
            <td colspan="4" id="dato2"><table id="tabla1">
              <tr>
                <td id="nivel2">ITEM </td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. MP</td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">CANTIDAD</td>
                <td id="nivel2">CANTIDAD RESTANTE</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">FECHA ENTREGA</td>
                <td id="nivel2">PRECIO/VENTA</td>
                <td id="nivel2">TOTAL ITEM</td>
                <td id="nivel2">MONEDA</td>
                <td id="nivel2">DIRECCION DESPACHO</td>
                </tr>
              
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                  <td id="talla2"><?php echo $row_items['int_consecutivo_io']; ?></td>
                  <td id="talla1"><?php echo $row_items['int_cod_ref_io']; ?>
                    </td>
        <td id="talla1"><?php $mp=$row_items['id_mp_vta_io'];
		if($mp!='')
		{
		$sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
		$resultmp= mysql_query($sqlmp);
		$nump= mysql_num_rows($resultmp);
		if($nump >='1')
		{ 
		$nombre_mp = mysql_result($resultmp,0,'str_nombre');
		echo $nombre_mp;
		} }else {echo "N.A";} ?></td>
                  <td id="talla3"><?php echo $row_items['int_cod_cliente_io']; ?></td>
                  <td id="talla3"><input name="int_cantidad_io" type="hidden" value="<?php echo $row_items['int_cantidad_io']; ?>"><?php echo $row_items['int_cantidad_io']; ?></td>
                  <td id="talla3"> 
                  <input name="int_cantidad_rest_io" type="hidden" value="<?php echo $row_items['int_cantidad_rest_io']; ?>">
                  <?php echo $row_items['int_cantidad_rest_io']; ?></td>
                  <td id="talla1"><?php echo $row_items['str_unidad_io']; ?></td>
                  <td id="talla1"><?php echo $row_items['fecha_entrega_io']; ?></td>
                  <td id="talla1"><?php echo $row_items['int_precio_io']; ?></td>
                  <td id="talla1"><?php echo $row_items['int_total_item_io'];$subtotal=$subtotal+$row_items['int_total_item_io'];?></td>
                  <td id="talla1"><?php echo $row_items['str_moneda_io']; ?></td>
                  <td id="talla1"><input type="hidden" name="dir" id="dir" value="<?php echo $row_items['str_direccion_desp_io']; ?>">
                    <?php echo $row_items['str_direccion_desp_io']; ?></td>
                </tr>
           

            </table></td>
            </tr>

          <tr>
            <td colspan="3" id="dato1">
            
            </td>
          </tr>
          <tr>
            <td colspan="3" id="dato1"></td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato2"><strong>Total Cajas</strong>:
<input name="int_total_cajas_rd" type="text" id="int_total_cajas_rd" onBlur="MM_validateForm('int_total_cajas_rd','','RisNum');return document.MM_returnValue" size="3"></td>
            <td id="dato1"><strong>Tolerancia % </strong>
              <input name="int_tolerancia_rd" type="text" id="int_tolerancia_rd" onBlur="MM_validateForm('int_tolerancia_rd','','RisNum');return document.MM_returnValue" value="10" size="3"></td>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"></td>
          </tr>                    
          <tr>
            <td colspan="3" id="dato2">
            <table width="616" id="tablaremision">            
            <tr> <?php $item= $row_items['int_consecutivo_io']; ?>           
            <td id="nivel2"width="26">RANGO</td>  
            <td id="nivel2"width="76">NUM. DESDE</td> 
            <td id="nivel2"width="76">NUM. HASTA</td>
            <td id="nivel2"width="64">CANTIDAD</td>  
            <td id="nivel2"width="64">PESO</td>              
            <td id="nivel2"width="30"><input type="button" onClick="addrem(this)"value=" + " ></td>       
            </tr>            
            </table>           
              </td>
          </tr>          
          <tr>
            <td colspan="3" id="dato2"></td>
          </tr>          
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="FINALIZAR REMISION">
              <!--<img src="images/rf.gif" width="31" height="18" onClick="javascript:submit();window.opener.location.reload();window.close();">--></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
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
mysql_free_result($usuario);
mysql_free_result($items);

?>