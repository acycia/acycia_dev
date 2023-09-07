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

mysql_select_db($database_conexion1, $conexion1);
$query_n_cliente = "SELECT * FROM cliente ORDER BY id_c DESC";
$n_cliente = mysql_query($query_n_cliente, $conexion1) or die(mysql_error());
$row_n_cliente = mysql_fetch_assoc($n_cliente);
$totalRows_n_cliente = mysql_num_rows($n_cliente);

/*mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);*/

mysql_select_db($database_conexion1, $conexion1);
$query_n_pais = "select * FROM Tbl_paises ";
$n_pais = mysql_query($query_n_pais, $conexion1) or die(mysql_error());
$row_n_pais = mysql_fetch_assoc($n_pais);
$totalRows_n_pais = mysql_num_rows($n_pais);
$row2 = mysql_fetch_array($n_pais);

mysql_select_db($database_conexion1, $conexion1);
$query_n_ciudad = "select * FROM Tbl_ciudades_col ";
$n_ciudad = mysql_query($query_n_ciudad, $conexion1) or die(mysql_error());
$row_n_ciudad = mysql_fetch_assoc($n_ciudad);
$totalRows_n_ciudad = mysql_num_rows($n_ciudad);
$row2 = mysql_fetch_array($n_ciudad);

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
$tipo=$_POST['tipo_usuario'];
$id_usuario=$_POST['id_usuario'];
if($tipo == '10')
{
$id=$_POST['id_c'];
$sql3="UPDATE usuario SET codigo_usuario='$id' WHERE id_usuario='$id_usuario'";
$result3=mysql_query($sql3);
}
/*setlocale(LC_CTYPE, 'es');
            
 
$variablesMayus= strtoupper ($_POST["MM_insert"]);*/



  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "perfil_cliente_vista.php?id_c=" . $_POST["id_c"] . "&tipo_usuario=" . $_POST["tipo_usuario"] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/agregueCampos.js"></script>
<script language=""="JavaScript">
    function conMayusculas(field) {
            field.value = field.value.toUpperCase()
}
</script>
</head>
<body oncontextmenu="return false">
  <table width="92%" id="tabla_formato">
    <tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
      <li><?php echo $row_usuario['nombre_usuario']; ?></li>       
       <li><a href="comercial.php">COMERCIAL</a></li>
       <li><a href="menu.php">MENU</a></li>
       <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>  
      </ul></div></div>
</td></tr></table>
     <div align="center">        
     <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_ingreso_c','','R','fecha_solicitud_c','','R','nombre_c','','R','nit_c','','R','rep_legal_c','','R','telefono_c','','R','direccion_c','','R','pais_c','','R','ciudad_c','','R','registrado_c','','R');return document.MM_returnValue">
       <table width="63%" id="tabla_formato2">
         <tr>
           <td width="25%" id="codigo_formato_2">CODIGO: R1-F07</td>
           <td colspan="1" id="titulo_formato_2">PERFIL DE CLIENTES</td>
           <td colspan="2" id="codigo_formato_2">VERSION: 1</td>
         </tr>
         <tr>
           <td rowspan="5" id="logo_2"><img src="images/logoacyc.jpg"></td>
           <td colspan="1" id="dato_1">Fecha de Ingreso</td>
           <td width="20%" id="dato_1">Fecha de Solicitud</td>
           <td width="20%" id="dato_1"><a href="listado_clientes.php"><img src="images/cat.gif" alt="LISTADO CLIENTES" border="0" style="cursor:hand;"><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onClick="window.history.go()" border="0"></a></td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1"><input name="fecha_ingreso_c" type="text" value="<?php echo date("Y/m/d"); ?>" size="10" readonly></td>
           <td id="dato_1"><input type="text" name="fecha_solicitud_c" value="<?php echo date("Y/m/d"); ?>" size="10"></td>
           <td id="dato_1">Cliente N&deg;
             <input name="id_c" type="hidden" value="<?php $num=$row_n_cliente['id_c']+1;  echo $num; ?>">
           <?php $num=$row_n_cliente['id_c']+1; echo $num; ?></td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1">NIT  000.000.000-0</td>
           <td colspan="1" id="dato_2">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1"><input type="text" name="nit_c" value="" size="30" onBlur="if (form1.nit_c.value) { DatosGestiones('1','nit_c',form1.nit_c.value); } else { alert('Debe digitar el NIT para validar su existencia en la BD'); }" ></td>
           <td colspan="1"><div id="resultado"></div></td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1"> Raz&oacute;n Social</td>
           <td id="dato_1">Tipo de Cliente </td>
           <td id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td id="dato_1">&nbsp;</td>
           <td id="dato_1"><input name="nombre_c" type="text"onChange="conMayusculas(this)" value="" size="30" maxlength="100"></td>
           <td width="20%" id="dato_1"><select name="tipo_c">
             <option value="NACIONAL">Nacional</option>
             <option value="EXTRANJERO">Extranjero</option>
           </select></td>
           <td width="20%" id="dato_1"></td>
         <tr>
           <td><input name="bolsa_plastica_c" type="hidden" id="bolsa_plastica_c" value="0">
               <input name="lamina_c" type="hidden" id="lamina_c" value="0"></td>
           <td colspan="2"><input name="cinta_c" type="hidden" id="cinta_c" value="0">
           <input name="packing_list_c" type="hidden" id="packing_list_c" value="0"></td>
           <td colspan="2">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" id="subtitulo2">INFORMACION GENERAL DEL CLIENTE</td>
         </tr>
         <tr>
           <td  id="dato_1">Representante Legal </td>
           <td width="15%" id="dato_1">Telefono(s)</td>
           <td width="20%" id="dato_1"> Pais</td>
           <td colspan="2" id="dato_1">Email Comercial</td>
         </tr>
         <tr>
           <td id="detalle_1"><input name="rep_legal_c" type="text"onChange="conMayusculas(this)" value="" size="30" maxlength="100"></td>
           <td id="detalle_1"><input type="text" name="telefono_c" value="" size="30"></td>
           <td id="detalle_1"><?php
	 //CONSULTA PAIS      	
     // $query_n_pais="select * from paises ";
     if(!$result2=mysql_query($query_n_pais)) error($query_n_pais);
     //if(mysql_num_rows($result2 > 0)) {
     $row2 = mysql_fetch_array($result2);
     $apuntador2=$row2['id_pais'];	 
     //}
     echo "<select name='pais_c' id_pais='id_pais'>";
	  if ($row2[0]==$row2[1]){ 
     echo "<option selected value='$row2[nombre_pais]'>$row2[1]"; 
	 }
	  else{ 
       echo "<option value='$row2[nombre_pais]'>$row2[1]"; 
     }
     while ($row2=mysql_fetch_array($result2)) {
     echo '<option value='.$row2["nombre_pais"]; //id_pais
     echo ' >';
     echo $row2["nombre_pais"];      
     }
      echo '</select>';
    ?></td>
           <td colspan="2"  id="detalle_1"><input type="text" name="email_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
         </tr>
         <tr>
           <td id="dato_1">Direcci&oacute;n Comercial</td>
           <td id="dato_1">Fax</td>
           <td id="dato_1">Ciudad</td>
           <td colspan="2" id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td id="detalle_1"><input name="direccion_c" type="text"onChange="conMayusculas(this)" value="" size="30" maxlength="100">           
           </td>
           <td id="detalle_1"><input type="text" name="fax_c" value="<?php echo $campo;?> " size="30">
                      <?php //prueba para indicativo ciudad
/*		   mysql_select_db($database_conexion1, $conexion1);		  
		   $consulta= mysql_query("SELECT*FROM ciudades_col WHERE nombre_ciudad='$_GET[ciudad_c]'");
		   $total_row_consulta = mysql_num_rows($consulta);
           $row = mysql_fetch_array($consulta);
           while ($row = mysql_fetch_array($consulta))
			{ 
			echo "El Nombre es: <b>".$row['ind_ciudad']."</b><br>n"; 
			 
		   $campo= stripslashes($campos['ind_ciudad']);
		   echo"la variable es: ";
		   echo $row_consulta;
			}*/
		   ?>           
           </td>
           <td id="detalle_1"><?php
	 //CONSULTA CIUDADES     	
     // $query_n_ciudad="select * from ciudades ";
     if(!$result3=mysql_query($query_n_ciudad)) error($query_n_ciudad);
     //if(mysql_num_rows($result3 > 0)) {
     $row3 = mysql_fetch_array($result3);
     $apuntador3=$row3['id_ciudad'];	 
     //}
     echo "<select name='ciudad_c' id_ciudad='id_ciudad'>";
	  if ($row3[0]==$row3[1]){ 
     echo "<option selected value='$row3[nombre_ciudad]'>$row3[1]"; 
	 }
	  else{ 
       echo "<option value='$row3[nombre_ciudad]'>$row3[1]"; 
     }
     while ($row3=mysql_fetch_array($result3)) {
     echo '<option value='.$row3["nombre_ciudad"]; //id_pais
     echo ' >';
	 $indica=$row3["ind_ciudad"];
	 echo $row3["nombre_ciudad"]; 
   
     }
      echo '</select>';
    ?>
    </td>
           <td colspan="2" id="dato_1"><input type="hidden" name="provincia_c" value="" size="20"></td>
         </tr>
         <tr>
           <td id="dato_1">&nbsp;</td>
           <td colspan="1" id="dato_1">&nbsp;</td>
           <td colspan="1" id="dato_1">&nbsp;</td>
         </tr>
           <tr>
           <td colspan="4" id="subtitulo2">INFORMACION DEL CONTACTO GENERAL</td>
         </tr>
         <tr>
           <td colspan="1" id="dato_1">Nombre del Contacto  Comercial </td>
           <td id="dato_1">Cargo  Cont. Com.</td>
           <td id="dato_1">Email Contacto Comercial</td>
           <td colspan="2" id="dato_1">Celular Cont. Com. </td>
         </tr>
         <tr>
           <td colspan="1" id="detalle_1"><input name="contacto_c" type="text"onChange="conMayusculas(this)" value="" size="30" maxlength="100"></td>
           <td id="detalle_1"><input type="text" name="cargo_contacto_c" value="" size="30"onChange="conMayusculas(this)"></td>
           <td id="detalle_1">
		   <?php     	
    /* // $query_n_pais="select * from paises ";
     if(!$result2=mysql_query($query_n_pais)) error($query_n_pais);
     //if(mysql_num_rows($result2 > 0)) {
     $row2 = mysql_fetch_array($result2);
     $apuntador2=$row2['id_pais'];	 
     //}
     echo "<select name='pais_bodega_c' id_pais='id_pais'>";
	  if ($row2[0]==$row2[1]){ 
     echo "<option selected value='$row2[nombre_pais]'>$row2[1]"; 
	 }
	  else{ 
       echo "<option value='$row2[nombre_pais]'>$row2[1]"; 
     }
     while ($row2=mysql_fetch_array($result2)) {
     echo '<option value='.$row2["nombre_pais"]; //id_pais
     echo ' >';
     echo $row2["nombre_pais"];      
     }
      echo '</select>';*/
    ?>
		   <input type="text" name="email_comercial_c2" value="" size="20"onChange="conMayusculas(this)"></td>
           <td colspan="2" id="detalle_1"><input type="text" name="celular_contacto_c" value="" size="20"onkeyUp="return ValNumero(this)">
               <input type="hidden" name="telefono_contacto_c" value="" size="20">
               <input type="hidden" name="contacto_bodega_c" value="" size="50">
               <input type="hidden" name="cargo_contacto_bodega_c" value="" size="20"></td>
         </tr>
         <tr>
           <td id="dato_1">Direcci&oacute;n Entrega de Mercancia </td>
           <td id="dato_1">Telefono Bodega</td>
           <td id="dato_1">Ciudad Bodega </td>
           <td colspan="2" id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td id="detalle_1"><input name="direccion_entrega_c" type="text"onChange="conMayusculas(this)" value="" size="30" maxlength="100"></td>
           <td id="detalle_1"><input type="text" name="telefono_bodega_c" value="" size="30"></td>
           <td id="detalle_1"><?php
	 //CONSULTA CIUDADES BODEGA     	
     // $query_n_ciudad="select * from ciudades ";
     if(!$result3=mysql_query($query_n_ciudad)) error($query_n_ciudad);
     //if(mysql_num_rows($result3 > 0)) {
     $row3 = mysql_fetch_array($result3);
     $apuntador3=$row3['id_ciudad'];	 
     //}
     echo "<select name='ciudad_bodega_c' id_ciudad='id_ciudad'>";
	  if ($row3[0]==$row3[1]){ 
     echo "<option selected value='$row3[nombre_ciudad]'>$row3[1]"; 
	 }
	  else{ 
       echo "<option value='$row3[nombre_ciudad]'>$row3[1]"; 
     }
     while ($row3=mysql_fetch_array($result3)) {
     echo '<option value='.$row3["nombre_ciudad"]; //id_pais
     echo ' >';
     echo $row3["nombre_ciudad"];      
     }
      echo '</select>';
    ?>
           <input type="hidden" name="provincia_bodega_c" value="" size="20"></td>
           <td colspan="2" id="dato_1"><input type="hidden" name="email_contacto_bodega_c" value="" size="20"onChange="conMayusculas(this)"></td>
         </tr>
         <tr>
           <td id="dato_1">Direcci&oacute;n Envio de Factura </td>
           <td id="dato_1">Telefono Envio Factura </td>
           <td id="dato_1">Fax Envio Factura </td>
           <td colspan="2" id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td id="detalle_1"><input name="direccion_envio_factura_c" type="text"onChange="conMayusculas(this)" value="" size="30" maxlength="100"></td>
           <td id="detalle_1"><input type="text" name="telefono_envio_factura_c" value="" size="30"></td>
           <td id="detalle_1"><input type="text" name="fax_envio_factura_c" value="" size="20"onKeyUp="return ValNumero(this)">
           <input type="hidden" name="fax_bodega_c" value="" size="30"onKeyUp="return ValNumero(this)"></td>
           <td colspan="2"  id="dato_1">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" id="dato_1">Observaciones de Informaci&oacute;n General del Cliente </td>
         </tr>
         <tr>
           <td colspan="4" id="dato_4"><textarea name="observ_inf_c" cols="100" rows="2"onChange="conMayusculas(this)"></textarea></td>
         </tr>
         <tr>
           <td colspan="4" id="dato_5">
<table id="tablaUsuarios">

<?php  	  
  $insertSQL = sprintf("INSERT INTO Tbl_Destinatarios (nit, nombre_responsable, direccion, telefono, ciudad ) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_c_cotiz'], "text"),
                       GetSQLValueString($_POST['n_cotiz'], "text"),
                       GetSQLValueString($_POST['responsable_cotiz'], "text"),
                       GetSQLValueString($_POST['fecha_cotiz'], "date"),
                       GetSQLValueString($_POST['hora_cotiz'], "text"));
?>

<tr>
 <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
<tr>

<td width="100" id="subtitulo2">NIT</td>

<td width="100" id="subtitulo2">RESPONSABLE</td>

<td width="100" id="subtitulo2">DIRECCION</td>

<td width="100" id="subtitulo2">TELEFONO</td>

<td width="100" id="subtitulo2">CIUDAD</td>

<td width="100">   <input type="button" onClick="agregarUsuario()"
value="Agregar Contacto" > </td>
<?php  $vector = array($_POST['nit_dest'],$_POST['responsable_dest'],$_POST['direccion_dest'],$_POST['telefono_dest'],$_POST['ciudad_dest']);
      $vector[1];
      $vector[2];
	  $vector[3];
	  $vector[4];
	  $vector[5]; 
	  
      for($i=0;$i<count($vector);$i++)
        
		
		
		echo "<pre>";
		print_r($vector);
		echo "</pre>";
		//echo implode(",",$_POST['nit_dest']);
		
		
/* $strquery='insert into Tbl_Destinatarios (nit, nombre_responsable, direccion, telefono, ciudad) values'; 
for($i=0;$i<count($ini);$i++){ 
$strquery.="('".$_POST['responsable_dest'][$i]."','".$_POST['direccion_dest'][$i]."','". $_POST['telefono_dest'][$i]."','". $_POST['ciudad_dest'][$i]."',"; 
} 
$strquery=substr($strquery,0,(strlen($strquery)-1)).';'; 
echo $strquery; 
mysql_query($strquery) or die(mysql_error()); 
echo 'Las necesidades han sido registradas de manera satisfactoria.<br />'; 
exit;  */
?>
</tr>

</table>           
       
           </td>
         </tr>
              
         <tr>
           <td colspan="4" id="subtitulo2">INFORMACION FINANCIERA </td>
         </tr>
         <tr>
           <td id="dato_1">Contacto Dpto Pagos</td>
           <td id="dato_1">Telefono</td>
           <td id="dato_1">Fax</td>
           <td id="dato_1">Email</td>
         </tr>
         <tr>
           <td id="detalle_1"><input type="text" name="contacto_dpto_pagos_c" value="" size="30"onChange="conMayusculas(this)"></td>
           <td id="detalle_1"><input type="text" name="telefono_dpto_pagos_c" value="" size="30"></td>
           <td id="detalle_1"><input type="text" name="fax_dpto_pagos_c" value="" size="30"onKeyUp="return ValNumero(this)"></td>
           <td id="dato_1"><input type="text" name="email_dpto_pagos_c" value="" size="20"onChange="conMayusculas(this)"></td>
         </tr>
         <tr>
           <td id="dato_1">Direcci&oacute;n</td>
           <td id="dato_1">Cupo Solicitado ($) </td>
           <td id="dato_1">Forma de Pago</td>
           <td id="dato_1">Otra Forma de Pago</td>
         </tr>
         <tr>
           <td id="detalle_1"><input type="text" name="direccion_dpto_pagos_c" value="" size="30"onChange="conMayusculas(this)"></td>
           <td id="detalle_1"><input type="text" name="cupo_solicitado_c" value="" size="30"onkeyUp="return ValNumero(this)"></td>
           <td id="detalle_1"><select name="forma_pago_c">
             <option value="CHEQUE">Cheque</option>
             <option value="CONSIGNACION">Consignacion</option>
             <option value="TRANSFERENCIA">Transferencia</option>
             <option value="OTRA">Otra</option>
           </select></td>
           <td id="dato_1"><input type="text" name="otro_pago_c" value="" size="20"></td>
         </tr>
         <tr>
           <td colspan="4"><table width="82%" id="tabla_formato">
               <tr>
                 <td colspan="4" id="subtitulo2">REFERENCIAS COMERCIALES</td>
               </tr>
               <tr>
                 <td width="20%" id="subtitulo3">REFERENCIAS COMERCIALES</td>
                 <td id="subtitulo3">TELEFONOS</td>
                 <td width="12%"  id="subtitulo3">CUPOS</td>
                 <td width="57%"  id="subtitulo3">PLAZOS</td>
               </tr>
               <tr>
                 <td colspan="3"id="dato_1">&nbsp;</td>
               </tr>
               <tr>
                 <td id="detalle_1"><input type="text" name="ref_comercial_c" value="" size="30"onChange="conMayusculas(this)"></td>
                 <td width="11%" id="detalle_1"><input type="text" name="tel_1ref_comercial_c" value="" size="15"></td>
                 <td id="detalle_1"><input type="text" name="cupo_1ref_comercial_c" value="" size="15"></td>
                 <td id="detalle_1"><select name="plazo_1ref_comercial_c">
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                   </select>
                     <input type="hidden" name="nombre_1ref_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
               </tr>
               <tr>
                 <td id="detalle_1"><input type="text" name="ref_comercial_c2" value="" size="30"onChange="conMayusculas(this)"></td>
                 <td id="detalle_1"><input type="text" name="tel_2ref_comercial_c" value="" size="15"></td>
                 <td id="detalle_1"><input type="text" name="cupo_2ref_comercial_c" value="" size="15"></td>
                 <td id="detalle_1"><select name="plazo_2ref_comercial_c">
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                   </select>
                     <input type="hidden" name="nombre_2ref_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
               </tr>
               <tr>
                 <td id="detalle_1"><input type="text" name="ref_comercial_c3" value="" size="30"onChange="conMayusculas(this)"></td>
                 <td id="detalle_1"><input type="text" name="tel_3ref_comercial_c" value="" size="15"></td>
                 <td id="detalle_1"><input type="text" name="cupo_3ref_comercial_c" value="" size="15"></td>
                 <td id="detalle_1"><select name="plazo_3ref_comercial_c">
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                   </select>
                     <input type="hidden" name="nombre_3ref_comercial_c" value="" size="20"onChange="conMayusculas(this)"></td>
               </tr>
           </table></td>
         </tr>
  <td colspan="4" id="subtitulo2">REFERENCIAS BANCARIAS</td>
  <tr>
    <td id="subtitulo3">REFERENCIAS BANCARIAS</td>
    <td id="subtitulo3">TELEFONOS</td>
    <td id="subtitulo3">NOMBRES</td>
    <td colspan="1" id="subtitulo3">&nbsp;</td>
  </tr>
  <tr>
    <td id="dato_1">&nbsp;</td>
    <td id="dato_1">&nbsp;</td>
    <td id="dato_1">&nbsp;</td>
    <td colspan="1"  id="dato_1">&nbsp;</td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="text" name="ref_bancaria_c" value="" size="30"onChange="conMayusculas(this)"></td>
    <td id="detalle_1"><input type="text" name="telefono_1ref_bancaria_c" value="" size="45"></td>
    <td id="detalle_1"><input type="text" name="nombre_1ref_bancaria_c" value="" size="30"onChange="conMayusculas(this)"></td>
    <td colspan="1" id="detalle_1">&nbsp;</td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="text" name="ref_bancaria_c2" value="" size="30"onChange="conMayusculas(this)"></td>
    <td id="detalle_1"><input type="text" name="telefono_2ref_bancaria_c" value="" size="45"></td>
    <td id="detalle_1"><input type="text" name="nombre_2ref_bancaria_c" value="" size="30"onChange="conMayusculas(this)"></td>
    <td colspan="1" id="detalle_1">&nbsp;</td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="text" name="ref_bancaria_c3" value="" size="30"onChange="conMayusculas(this)"></td>
    <td id="detalle_1"><input type="text" name="telefono_3ref_bancaria_c" value="" size="45"></td>
    <td id="detalle_1"><input type="text" name="nombre_3ref_bancaria_c" value="" size="30"onChange="conMayusculas(this)"></td>
    <td colspan="1" id="detalle_1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Observaciones de la Informaci&oacute;n Financiera </td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1"><textarea name="observ_inf_finan_c" cols="100" rows="2"onChange="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">APROBACION FINANCIERA</td>
  </tr>
  <tr>
    <td colspan="3" id="dato_1">Cupo Aprobado Plazo Aprobado</td>
    <td colspan="1" id="dato_1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"id="dato_1"onkeyUp="return ValNumero(this)"><input type="text" name="cupo_aprobado_c" value="" size="30"onkeyUp="return ValNumero(this)">
        <select name="plazo_aprobado_c">
                     <option value="PAGO DE CONTADO">Pago de Contado</option>
                     <option value="PAGO A 15 DIAS">Pago a 15 Dias</option>
                     <option value="PAGO A 30 DIAS">Pago a 30 Dias</option>
                     <option value="PAGO A 45 DIAS">Pago a 45 Dias</option>
                     <option value="PAGO A 60 DIAS">Pago a 60 Dias</option>
                     <option value="PAGO A 90 DIAS">Pago a 90 Dias</option>
                     <option value="PAGO A 999 DIAS">Pago a 999 Dias</option>
                   </select></td>
  </tr>
  <tr>
    <td colspan="4"id="dato_1">Observaciones de la Aprobacion Financiera del Cliente </td>
  </tr>
  <tr>
    <td colspan="4"id="dato_1"><textarea name="observ_aprob_finan_c" cols="100" rows="3"onChange="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">APROBACION COMERCIAL</td>
  </tr>
  <tr>
    <td id="dato_1"> Estado Comercial </td>
    <td colspan="2" id="dato_1"><select name="estado_comercial_c">
      <option value="PENDIENTE">Pendiente</option>
      <option value="ACEPTADO">Aceptado</option>
      <option value="RECHAZADO">Rechazado</option>
    </select></td>
    <td colspan="1" id="dato_1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Observaciones de Aprobaci&oacute;n Comercial </td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1"><textarea name="observ_asesor_com_c" cols="100" rows="2"onChange="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">DOCUMENTOS ADJUNTOS </td>
  </tr>
  <tr>
    <td nowrap id="detalle_1"><input type="checkbox" name="camara_comercio_c" value="" >
      Camara de Comercio (Vigente) </td>
    <td colspan="1" nowrap id="detalle_1"><input type="checkbox" name="referencias_bancarias_c" value="" >
      Referencias Bancarias </td>
    <td colspan="2" nowrap id="detalle_1"><input type="checkbox" name="referencias_comerciales_c" value="" >
      Referencias Comerciales </td>
  </tr>
  <tr>
    <td nowrap id="detalle_1"><input type="checkbox" name="balance_general_c" value="" >
      Balance General </td>
    <td colspan="1" nowrap id="detalle_1"><input type="checkbox" name="flujo_caja_proy_c" value="" >
      Flujo Caja Proyectado </td>
    <td colspan="2" nowrap id="detalle_1"><input type="checkbox" name="fotocopia_declar_iva_c" value="" >
      Fotocopia Declaraci&oacute;n IVA </td>
  </tr>
  <tr>
    <td id="detalle_1"><input type="checkbox" name="estado_pyg_c" value="" >
      Estado P&amp;G </td>
    <td colspan="1" id="detalle_1">&nbsp;</td>
    <td colspan="2" id="detalle_1"><input type="checkbox" name="otros_doc_c" value="" >
      Otros</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1">Observaciones de Documentos Adjuntos </td>
  </tr>
  <tr>
    <td colspan="4" id="dato_1"><textarea name="observ_doc_c" cols="100" rows="2"onChange="conMayusculas(this)"></textarea></td>
  </tr>
  <tr>
    <td colspan="4" id="subtitulo2">INFORMACION FINAL DEL FORMATO PERFIL DE CLIENTES </td>
  </tr>
  <tr>
    <td id="dato_1">* Estado de Cliente
      <select name="estado_c">
          <option value="PENDIENTE">Pendiente</option>
          <option value="ACTIVO">Activo</option>
          <option value="RETIRADO">Retirado</option>
        </select>
        <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>">
        <input name="id_usuario" type="hidden" id="id_usuario" value="<?php echo $row_usuario['id_usuario']; ?>"></td>
    <td id="dato_1">&nbsp;</td>
    <td id="dato_1">Registrado Por
      <input name="registrado_c" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30"readonly></td>
    <td colspan="1" id="dato_1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="dato_2"><input name="submit" type="submit" value="Adicionar Perfil de Cliente"></td>
  </tr>
       </table>
       <input type="hidden" name="MM_insert" value="form1">
</form></div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($n_cliente);

//mysql_free_result($vendedores);
?>