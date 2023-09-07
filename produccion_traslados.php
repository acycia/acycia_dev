<?php
require_once('Connections/conexion1.php');
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
  if($_POST["c_kilos"]==$_POST["kilo_origen"])
  {
	//UPDATE O.P EN ROLLO
	$op_destino=$_POST["op_destino"];
	$id_r = $_POST["rollo_origen"];
	
	$insercionop="INSERT INTO Tbl_orden_produccion (id_op, str_numero_oc_op, int_cod_ref_op) VALUES ('$op_destino', 'AA7', '779')";
		mysql_select_db($database_conexion1, $conexion1);
        $resultop = mysql_query($insercionop, $conexion1) or die(mysql_error());
			
	$sqlr="UPDATE TblExtruderRollo SET id_op_r='$op_destino' WHERE id_r='$id_r'";
    mysql_select_db($database_conexion1, $conexion1);
    $resultr = mysql_query($sqlr, $conexion1) or die(mysql_error());


		
  /* $result0 = mysql_query("SELECT * FROM TblExtruderRollo WHERE id_r='$id_r'");
    while ($row0 = mysql_fetch_array($result0)) {
		$ref=$row0['ref_r'];
		$idc=$row0['id_c_r']; 
		
		$insercion="insert into TblExtruderRollo (id_op_r, ref_r, id_c_r ) values ('$op_destino', '$ref', '$idc')";
		mysql_select_db($database_conexion1, $conexion1);
        $resultr = mysql_query($insercion, $conexion1) or die(mysql_error());
		  }	*/		
 }else{
	//INSERT NUEVO ROLLO
	$op_origen =$_POST["op_origen"];
	$op_destino=$_POST["op_destino"];
	$nuevos_kilos = $_POST["c_kilos"];
	$id_r = $_POST["rollo_origen"];

	$insercionop="INSERT INTO Tbl_orden_produccion (id_op, str_numero_oc_op, int_cod_ref_op) VALUES ('$op_destino', 'AA7', '779')";
		mysql_select_db($database_conexion1, $conexion1);
        $resultop = mysql_query($insercionop, $conexion1) or die(mysql_error());
			
	$updaterollo="UPDATE TblExtruderRollo SET kilos_r = kilos_r - '$nuevos_kilos' WHERE id_r='$id_r'";
    mysql_select_db($database_conexion1, $conexion1);
    $resultrollo = mysql_query($updaterollo, $conexion1) or die(mysql_error());	


	$insercionR="INSERT INTO TblExtruderRollo ('$op_destino', '779', '19') SELECT (id_op_r, ref_r, id_c_r) FROM TblExtruderRollo WHERE id_r='$id_r'";
		mysql_select_db($database_conexion1, $conexion1);
        $resultR = mysql_query($insercionR, $conexion1) or die(mysql_error());
		
/*INSERT INTO table2
(column_name(s))
SELECT column_name(s)
FROM table1;*/


/*		$result0 = mysql_query("SELECT * FROM Tbl_orden_produccion WHERE id_op='$op_origen'");
		
	    while ($row0 = mysql_fetch_array($result0)) {
		$oc=$row0['str_numero_oc_op'];
		$ref=$row0['int_cod_ref_op']; 
		
		$insercion="insert into Tbl_orden_produccion (id_op, str_numero_oc_op, int_cod_ref_op ) values ($op_destino, $oc, $ref)";
		  }*/	//fin while
			
	 }
 
 
  $insertGoTo = "produccion_traslados.php?id_op=" . $_POST['id_op'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

		  
/*    $sqlinsertop="INSERT INTO Tbl_orden_produccion SELECT * FROM Tbl_orden_produccion WHERE id_op='$op_origen'";
	$resultinsertop=mysql_query($sqlinsertop);		

    $sqlinsertE="INSERT INTO TblExtruderRollo SELECT * FROM TblExtruderRollo WHERE id_r=$id_r";
	$resultinsertE=mysql_query($sqlinsertE);*/			  
		  
/*$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s",$colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);*/
//LISTADO ORDEN DE PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_orden = "SELECT * FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.id_op IN (SELECT TblExtruderRollo.id_op_r FROM TblExtruderRollo WHERE Tbl_orden_produccion.id_op=TblExtruderRollo.id_op_r) ORDER BY Tbl_orden_produccion.id_op DESC";
$orden = mysql_query($query_orden, $conexion1) or die(mysql_error());
$row_orden = mysql_fetch_assoc($orden);
$totalRows_orden = mysql_num_rows($orden); 
//IMPRIME O.P Y BOLSAS
$colname_op= "-1";
if (isset($_GET['op_origen'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['op_origen'] : addslashes($_GET['op_origen']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op='%s'",$colname_op);
$op = mysql_query($query_op, $conexion1) or die(mysql_error());
$row_op = mysql_fetch_assoc($op);
$totalRows_op = mysql_num_rows($op);
//KILOS DEL ROLLO Y EL ID
$colname_rollo_kilos= "-1";
if (isset($_GET['rollo_origen'])) {
  $colname_rollo_kilos = (get_magic_quotes_gpc()) ? $_GET['rollo_origen'] : addslashes($_GET['rollo_origen']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollos_kilos = sprintf("SELECT id_r,kilos_r FROM TblImpresionRollo WHERE id_r='%s' ORDER BY rollo_r ASC",$colname_rollo_kilos);
$rollos_kilos = mysql_query($query_rollos_kilos, $conexion1) or die(mysql_error());
$row_rollos_kilos = mysql_fetch_assoc($rollos_kilos);
$totalRows_rollos_kilos = mysql_num_rows($rollos_kilos);
 //SI NO TIENE IMPRESION LA O.P SE DIRIGE A EXTRUSION
if($totalRows_rollos_kilos=='0'){
mysql_select_db($database_conexion1, $conexion1);
$query_rollos_kilos =sprintf( "SELECT id_r,kilos_r FROM TblExtruderRollo WHERE id_r='%s' ORDER BY rollo_r ASC",$colname_rollo_kilos);
$rollos_kilos = mysql_query($query_rollos_kilos, $conexion1) or die(mysql_error());
$row_rollos_kilos = mysql_fetch_assoc($rollos_kilos);
$totalRows_rollos_kilos = mysql_num_rows($rollos_kilos);
 }
//SELECT QUE LLENA COMBO DE ROLLOS
$colname_op= "-1";
if (isset($_GET['op_origen'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['op_origen'] : addslashes($_GET['op_origen']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_rollos = sprintf("SELECT id_r,id_op_r,rollo_r,kilos_r FROM TblImpresionRollo WHERE id_op_r='%s' ORDER BY rollo_r ASC",$colname_op);
$rollos = mysql_query($query_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);
$totalRows_rollos = mysql_num_rows($rollos);
 //SI NO TIENE IMPRESION LA O.P SE DIRIGE A EXTRUSION
if($totalRows_rollos=='0'){
mysql_select_db($database_conexion1, $conexion1);
$query_rollos =sprintf( "SELECT id_r,id_op_r,rollo_r,kilos_r FROM TblExtruderRollo WHERE id_op_r='%s' ORDER BY rollo_r ASC",$colname_op);
$rollos = mysql_query($query_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);
$totalRows_rollos = mysql_num_rows($rollos); 
 }
 //CLIENTES
mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT id_c, nit_c, nombre_c FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 
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
<!--<li><a  href="#" onClick="cerrar()">SALIR</a></li>-->
</ul></td>
</tr>  
  <tr>
    <td colspan="2">
 <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1"><fieldset>
        <table id="tabla2">
          <tr>
            <td colspan="3" id="subtitulo1">TRASLADOS DE O.P
            A O.P</td>
            </tr>
          <tr>
            <td id="fuente1">DATOS DE LA O.P <?php echo $row_op['id_op'];?></td>
            <td colspan="2" id="fuente3"><a href="orden_compra_cl.php"> </a><a href="combos/combo1/menu.php"></a></td>
            </tr>
          
          <tr>
            <td colspan="3" id="fuente1"><select  name="cliente_op"  id="cliente_op" style="width:250px">
              <option value=""<?php if (!(strcmp("", $row_op['int_cliente_op']))) {echo "selected=\"selected\"";} ?>>Cliente</option>
              <?php
		do {  
		?>
              <option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $row_op['int_cliente_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c'];?> </option>
              <?php
		} while ($row_clientes = mysql_fetch_assoc($clientes));
		  $rows = mysql_num_rows($clientes);
		  if($rows > 0) {
			  mysql_data_seek($clientes, 0);
			  $row_clientes = mysql_fetch_assoc($clientes);
		  }
		?>
            </select>
              <input type="hidden" name="nit_op" id="nit_op"  value="<?php echo $row_op['str_nit_op']; ?>"/>
              </td>
          </tr>
          <tr>
            <td colspan="3" id="fuente1"><?php $nueva_bolsas = regladetres($row_rollos_kilos['kilos_r'],$row_op['int_cantidad_op'],$row_op['int_kilos_op']) ?>
            <input type="hidden" name="bolsas_op" value="<?php echo round($nueva_bolsas); ?>">
            </td>
            </tr>
         
          <tr id="tr2">
            <td colspan="4" id="dato2"><table id="tabla1">
              <tr>
                <td colspan="3" id="fuente1">O.P ORIGEN</td>
                <td id="fuente1"><span id="sprytextfield1"><span class="textfieldRequiredMsg">O.P DESTINO</span></span></td>
                </tr>
              
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                  <td id="nivel"># O.P</td>
                  <td id="nivel">ROLLO</td>
                  <td id="nivel">KILOS</td>
                  <td id="nivel">NUEVA O.P</td>
                  </tr>
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                  <td id="fuente1"><select name="op_origen" id="op_origen" style="width:100px" onChange="recargaGeneral(this.name,this.value);">
                    <option value="0"<?php if (!(strcmp(0, $row_op['id_op']))) {echo "selected=\"selected\"";} ?>>O.P.</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_orden['id_op']?>"<?php if (!(strcmp($row_orden['id_op'], $row_op['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_orden['id_op'];?></option>
                    <?php
} while ($row_orden = mysql_fetch_assoc($orden));
  $rows = mysql_num_rows($orden);
  if($rows > 0) {
      mysql_data_seek($orden, 0);
	  $row_orden = mysql_fetch_assoc($orden);
  }
?>
                  </select></td>
                  <td id="fuente1"><select name="rollo_origen" id="rollo_origen" style="width:100px" onChange="recargaRollo(this.name,this.value);">
                    <option value=""<?php if (!(strcmp("", $row_rollos_kilos['id_r']))) {echo "selected=\"selected\"";} ?>>Rollo</option>
                    <?php
			do {  
		?>
                    <option value="<?php echo
					 $row_rollos['id_r']?>"<?php if (!(strcmp($row_rollos['id_r'], $row_rollos_kilos['id_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rollos['rollo_r'];?></option>
                    <?php
			} while ($row_rollos = mysql_fetch_assoc($rollos));
			  $rows = mysql_num_rows($rollos);
			  if($rows > 0) {
				  mysql_data_seek($rollos, 0);
				  $row_rollos = mysql_fetch_assoc($rollos);
			  }
			?>
                  </select></td>
                  <td id="fuente1"><input type="text" name="kilo_origen" id="kilo_origen"  style="width:100px" value="<?php echo $row_rollos_kilos['kilos_r'];?>"><input type="number" name="c_kilos" min="0" step="0.01" id="c_kilos" value="<?php echo $row_rollos_kilos['kilos_r'];?>" style="width:100px" onChange="trasladOp()" required></td>
                  <td id="fuente1"> 
                    <select name="op_destino" id="op_destino" style="width:100px" >
                      <option value="<?php echo $row_orden['id_op']+1;?>"selected ><?php echo $row_orden['id_op']+1;?></option>
                      <?php
do {  
?>
                      <option value="<?php echo $row_orden['id_op']?>"><?php echo $row_orden['id_op'];?></option>
                      <?php
} while ($row_orden = mysql_fetch_assoc($orden));
  $rows = mysql_num_rows($orden);
  if($rows > 0) {
      mysql_data_seek($orden, 0);
	  $row_orden = mysql_fetch_assoc($orden);
  }
?>
                    </select>
 
      </td>                    
                  </tr>
                <tr >
                  <td colspan="4" id="fuente1"></td>
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
            <td colspan="3" id="dato1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="dato2"> 
              <input type="hidden" name="MM_update" value="form1">
              <input type="submit" name="ENVIAR" id="ENVIAR" value="TRASLADAR" /></td>
            </tr>
        </table>
   </fieldset>
</form>
 
       </td>
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
 <script>
$("#estado").on("change", buscarMunicipios);
$("#municipio").on("change", buscarLocalidades); 

function buscarMunicipios(){
	$("#localidad").html("<option value='' style='width: 80px'>- Kilos -</option>");
    //$("#localidad").html("<input type='text' value=''>");
   //$("#localidad").html("<input type='text' list='misdatos' value=''>");
   
	$estado = $("#estado").val(); 
	
	if($estado == ""){
			$("#municipio").html("<option value='' style='width: 80px'>- Rollos -</option>");
	}
	else {
		$.ajax({
			dataType: "json",
			data: {"estado": $estado},
			url:   '../traslados/produccion_traslados_buscar.php',
			type:  'post',
			beforeSend: function(){
				//Lo que se hace antes de enviar el formulario
				},
			success: function(respuesta){
				//lo que se si el destino devuelve algo
				$("#municipio").html(respuesta.html);
			},
			error:	function(xhr,err){ 
				alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
			}
		});
	}
}

function buscarLocalidades(){
	$municipio = $("#municipio").val();
	
	$.ajax({
		dataType: "json",
		data: {"municipio": $municipio},
		url:   '../traslados/produccion_traslados_buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#localidad").html(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});	
}

</script>

</body>
</html>