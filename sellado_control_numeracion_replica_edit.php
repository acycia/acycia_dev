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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
//INSERT DE FALTANTES
if((isset($_POST['int_desde_f'])) && ($_POST['int_desde_f']!='')&&(isset($_POST['int_hasta_f'])) && ($_POST['int_hasta_f']!='')){
    foreach($_POST['int_desde_f'] as $key=>$value)
    $a[]= $value;
    foreach($_POST['int_hasta_f'] as $key=>$value)
    $b[]= $value;
		   //FOR SEPARA NUMEROS DEL IN_HASTA_TN Y INT_DESDE_TN	
		   $h_tn=$_POST['int_hasta_tn'];
           for( $x = 0; $x < strlen($h_tn); $x++ )
           {
           if( is_numeric($h_tn[$x]))
           {
           $hastar .= $h_tn[$x];
             }
           }
		   $d_tn=$_POST['int_desde_tn'];
           for( $j = 0; $j < strlen($d_tn); $j++ )
           {
           if( is_numeric($d_tn[$j]))
           {
           $desder .= $d_tn[$j];
             }
           }
		   //FIN 	
	//FOR PARA RECORRER ARRAY DE FALTANTES INICIO Y HASTA
	for($i=0; $i<count($a); $i++) 
    { 
	        
		   //FOR SEPARA NUMEROS DEL IN_HASTA_F		   
		   $hasta = $b[$i];
		   $numer = ""; 
		   $hastafin="";          
           for( $id = 0; $id < strlen($hasta); $id++ )
           {
           if( is_numeric($hasta[$id]))
           {
           $hastafin .= $hasta[$id];
             }
		   }		     
		   //FIN
		   //FOR SEPARA NUMEROS DEL IN_DESDE_F 
		   $des = $a[$i];
		   $numers = "";
		   $desdefin="";    
           for( $ids = 0; $ids < strlen($des); $ids++ )
           {
           if( is_numeric($des[$ids]))
           {
           $desdefin .= $des[$ids];
             } 
           }		    
		   if($desdefin<$desder){ $desdef='d';}
		   if($desdefin<$desder || $desdefin>$hastafin){ $desdemenos='e';}
		   //if($hastafin>$hastar){ $hastaf='h';}
           if( $hastafin<$desdefin){ $hastamenos='i';}
	       $totalfalt=($hastafin-$desdefin)+1; 		   
	       //FIN 
		   if($desdef!='d'){//PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO 
		   if($hastamenos!='i'){ //EL RANGO HASTA NO DEBE SER MENOR AL DESDE
		   if($desdemenos!='e'){//EL RANGO DESDE NO DEBE SER MAYOR AL HASTA

  $insertSQL2 = sprintf("INSERT INTO Tbl_faltantesCOPIA (id_op_f, int_paquete_f, int_caja_f, int_inicial_f, int_final_f, int_total_f) VALUES (%s, %s, %s, %s, %s, %s)",
                       //GetSQLValueString($_POST['id_tn'], "int"),
					   GetSQLValueString($_POST['int_op_tn'], "int"),
                       GetSQLValueString($_POST['int_paquete_tn'], "int"),
					   GetSQLValueString($_POST['int_caja_tn'], "int"),
                       GetSQLValueString($a[$i], "text"),
                       GetSQLValueString($b[$i], "text"),
                       GetSQLValueString($totalfalt, "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error()); 
       //}//PORQUE POR PRIMERA VEZ NO HAY FALTANTES DE ESA ID_OP 
      }else {echo "<script type=\"text/javascript\">alert(\"Los faltantes desde: $des No debe ser mayor al faltante hasta: $hasta \");return false;history.go(-1)</script>";}//FIN PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO 
     }else {echo "<script type=\"text/javascript\">alert(\"Los faltantes hasta: $hasta No debe ser menor al faltante desde: $des \");return false;history.go(-1)</script>";}//FIN EL RANGO HASTA NO DEBE SER MANOR AL DESDE
    }else {echo "<script type=\"text/javascript\">alert(\"Los faltantes: $des Y $hasta No estan dentro del rango\");return false;history.go(-1)</script>";}//FIN EL RANGO HASTA NO DEBE SER MANOR AL DESDE
 }//FALTATES ENVIADOS Y LLENOS
}//FIN FOR
//CONSULTAR TOTAL FALTANTES DEL PAQUETE SEPARAR NUMERO DE LETRAS Y SUMAR TOTAL FALTANTES
/*if( $desdef!='d'){ //PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO
if($hastamenos!='i'){ //EL RANGO HASTA NO DEBE SER MANOR AL DESDE 
if($desdemenos!='e'){//EL RANGO DESDE NO DEBE SER MAYOR AL HASTA
$idOp_f=$_POST['int_op_tn'];//FALTANTES RE4CIEN GUARDADOS
$idP_f=$_POST['int_paquete_tn'];
$idC_f=$_POST['int_caja_tn'];
mysql_select_db($database_conexion1, $conexion1);
$query_vista_faltantes = "SELECT * FROM Tbl_faltantes WHERE id_op_f='$idOp_f' AND int_paquete_f='$idP_f' AND int_caja_f='$idC_f' ORDER BY Tbl_faltantes.int_total_f ASC";
$vista_faltantes = mysql_query($query_vista_faltantes, $conexion1) or die(mysql_error());
$row_vista_faltantes = mysql_fetch_assoc($vista_faltantes);
if($row_vista_faltantes['int_total_f']!=''){//PORQUE POR PRIMERA VEZ NO HAY FALTANTES DE ESA ID_OP 
	       do{//ACUMULA LOS TOTALES DE FALTANTES POR PAQUETE
		   $acumula+=$row_vista_faltantes['int_total_f'];
		   }while ($row_vista_faltantes = mysql_fetch_assoc($vista_faltantes));
		   //SEPARA NUMERO DE CADENA HASTA
			  $mystring = $_POST['int_hasta_tn'];		   
			  $numero = "";
			  $findme   = array('AA1F','AA1G','AA1H','AA1I','AA1J','AA1K','AA1L','AA1M','AA1N','AA1B','AA1C','AA1D','AA1E');
  
			  foreach ($findme as &$valor) {  //FOREACH PARA COMPARAR EL ARRAY CON CADENA
			  $valor = $valor ;			
			  $pos = strpos($mystring, $valor);
							  
			  if ($pos !== false) {
			  $cade = substr($mystring, 0, 4);			
			  $nu = substr($mystring, 4);
			  $hastaMasFaltantes =$nu+$acumula;
			  $totalCadena = $cade.$hastaMasFaltantes;
			  }	else {//FIN SI NO HAY SUBCADENA
			  $has = $mystring;
			  $n = "";
	  
			 for( $x = 0; $x < strlen($has); $x++ )
			 {
			 if( is_numeric($has[$x]) )
			 {
			 $n .= $has[$x];
			 $hastaMasFaltantes=$n+$acumula;
			 $letras = ereg_replace("[0-9]", "", $has);//solo letras
			 //TOTALIZA	   
		     $totalCadena=$letras.$hastaMasFaltantes;
			                    }
			             }
			       }
			 }	  
  $insertSQL = sprintf("INSERT INTO Tbl_tiquete_numeracion ( int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, int_paquete_tn, int_caja_tn) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       
					   GetSQLValueString($_POST['int_op_tn'], "int"),
                       GetSQLValueString($_POST['fecha_ingreso_tn'], "date"),
					   GetSQLValueString($_POST['hora_tn'], "text"),
                       GetSQLValueString($_POST['int_bolsas_tn'], "int"),
					   GetSQLValueString($_POST['int_undxpaq_tn'], "int"),
					   GetSQLValueString($_POST['int_undxcaja_tn'], "int"), 
                       GetSQLValueString($_POST['int_desde_tn'], "text"),
                       GetSQLValueString($totalCadena, "text"),
                       GetSQLValueString($_POST['int_cod_empleado_tn'], "int"),
					   GetSQLValueString($_POST['int_cod_rev_tn'], "int"),
                       GetSQLValueString($_POST['int_paquete_tn'], "int"),
					   GetSQLValueString($_POST['int_caja_tn'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
   			   
}else{ */ 
if( $desdef!='d'){ //PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO
if($hastamenos!='i'){ //EL RANGO HASTA NO DEBE SER MANOR AL DESDE 
if($desdemenos!='e'){//EL RANGO DESDE NO DEBE SER MAYOR AL HASTA		  
  $insertSQL = sprintf("INSERT INTO Tbl_tiquete_numeracionCOPIA ( int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, int_paquete_tn, int_caja_tn) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       /*GetSQLValueString($_POST['id_tn'], "int"),*/
					   GetSQLValueString($_POST['int_op_tn'], "int"),
                       GetSQLValueString($_POST['fecha_ingreso_tn'], "date"),
					   GetSQLValueString($_POST['hora_tn'], "text"),
                       GetSQLValueString($_POST['int_bolsas_tn'], "int"),
					   GetSQLValueString($_POST['int_undxpaq_tn'], "int"),
					   GetSQLValueString($_POST['int_undxcaja_tn'], "int"), 
                       GetSQLValueString($_POST['int_desde_tn'], "text"),
                       GetSQLValueString($_POST['int_hasta_tn'], "text"),
                       GetSQLValueString($_POST['int_cod_empleado_tn'], "int"),
					   GetSQLValueString($_POST['int_cod_rev_tn'], "int"),
                       GetSQLValueString($_POST['int_paquete_tn'], "int"),
					   GetSQLValueString($_POST['int_caja_tn'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error()); 	
        
  $insertGoTo = "sellado_control_numeracion_replica_edit.php?id_op=". $_POST['int_op_tn'] . "&int_caja_tn=" . $_POST['int_caja_tn'] . "";
  header(sprintf("Location: %s", $insertGoTo));
     }//alert
    }//alert
  }//alert
}//fin isset
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_control_paquete = "-1";
if (isset($_GET['id_op'])) {
  $colname_control_paquete = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_control_paquete = sprintf("SELECT id_op,int_cod_ref_op,int_undxcaja_op,int_undxpaq_op FROM Tbl_orden_produccion WHERE id_op=%s",$colname_control_paquete);
$control_paquete = mysql_query($query_control_paquete, $conexion1) or die(mysql_error());
$row_control_paquete = mysql_fetch_assoc($control_paquete);
$totalRows_control_paquete = mysql_num_rows($control_paquete);

$colname_op = "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op = sprintf("SELECT * FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s ORDER BY id_tn DESC LIMIT 1",$colname_op);
$op = mysql_query($query_op, $conexion1) or die(mysql_error());
$row_op = mysql_fetch_assoc($op);
$totalRows_op = mysql_num_rows($op);

mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado ORDER BY empleado.codigo_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);
//IMPRIME LOS LINCK DE PAQUETES
$colname_id_op_p = "-1";
if (isset($_GET['id_op'])) {
  $colname_id_op_p  = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_caja_p = "-1";
if (isset($_GET['int_caja_tn'])) {
  $colname_caja_p  = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}
//$colname_caja_p=$_GET['int_caja_tn'];
mysql_select_db($database_conexion1, $conexion1);
$query_tiquete_num = sprintf("SELECT * FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s AND int_caja_tn=%s ORDER BY int_paquete_tn DESC",$colname_id_op_p,$colname_caja_p );
$tiquete_num = mysql_query($query_tiquete_num, $conexion1) or die(mysql_error());
$row_tiquete_num = mysql_fetch_assoc($tiquete_num);
$totalRows_tiquete = mysql_num_rows($tiquete_num);
//IMPRIME LOS LINCK DE LAS CAJAS
$colname_id_op_c = "-1";
if (isset($_GET['id_op'])) {
  $colname_id_op_c  = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caja_num = sprintf("SELECT DISTINCT int_caja_tn,int_op_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn=%s ORDER BY int_caja_tn  DESC",$colname_id_op_c);
$caja_num = mysql_query($query_caja_num, $conexion1) or die(mysql_error());
$row_caja_num = mysql_fetch_assoc($caja_num);
$totalRows_caja = mysql_num_rows($caja_num);
//CUANDO ARRANCA DESDE CERO EL INGRESO DE TIQUETES O CAMBIO DE CAJA
$colname_id_tn = "-1";
if (isset($_GET['id_tn'])) {
  $colname_id_tn  = (get_magic_quotes_gpc()) ? $_GET['id_tn'] : addslashes($_GET['id_tn']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_paquete= sprintf("SELECT * FROM Tbl_tiquete_numeracion WHERE id_tn=%s",$colname_id_tn );
$paquete= mysql_query($query_paquete, $conexion1) or die(mysql_error());
$row_paquete = mysql_fetch_assoc($paquete);
$totalRows_paquete = mysql_num_rows($paquete); 	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<!--<link rel="stylesheet" type="text/css" media="all" href="css/style_login.css" />-->
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>

<!--VALIDAR EL ENVIO DE FORMULARIO PARA CAMBIO DE CAJA-->
<script type="text/javascript">
function funcion(){
if(form1.cajaCompleto.value=='1'){
	alert ('¡Se ha finalizado el numero de Cajas para esta O.P!'); 
return false;
}
return true;
}

function funcion2(){
if(document.form1.paqCompleto.value=='1'){
var id_tn=document.form1.id_tn.value;
var id_op=document.form1.int_op_tn.value;
var caj=document.form1.int_caja_tn.value;
var caja=parseInt(caj)+ parseInt(1);
	 
alert ('¡Se ha completado el numero de paquetes para esta Caja, continue!');
window.location ='sellado_control_numeracion_replica_edit.php?id_op='+id_op+'&id_tn='+id_tn+'&int_caja_tn='+caja;
  return false;
  }
  return true;
}

</script>
</head>
<body onLoad="sumaPaqSelladoEdit();"><!--onLoad="desdeHastaSellado();"-->
<div align="center">
<table align="center" id="tabla">
<tr align="center"><td>
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
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return  alertafaltantes()">
        <table align="center" id="tabla35">
          <tr>
            <td colspan="3" id="dato3"><a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" border="0"title="LISTADO O.P"/> </a> <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="3" id="titulo2">REGISTRO DE PAQUETES<strong>
              <?php if($row_tiquete_num['id_tn']=='')$id_tn=$row_paquete['id_tn']; else  $id_tn=$row_tiquete_num['id_tn']; ?>
              <input type="hidden" name="id_tn" id="id_tn" value="<?php echo $id_tn; //}?>">
            </strong></td>
            </tr>
          <tr>
            <td id="dato1"><strong>PAQ N:</strong><span class="rojo_inteso">
			<?php $num=$row_tiquete_num['int_paquete_tn']+1; //echo $num;?></span>
              <input name="int_paquete_tn" style="width:50px;" <?php if ($row_usuario['tipo_usuario']!='1') {echo "readonly";}?> type="number" id="int_paquete_tn" value="<?php echo $num; ?>" maxlength="5">
 			   <!--CONTROS DE PAQUETES X CAJA-->
               <?php if($row_tiquete_num['int_undxpaq_tn']=='') {$paqxcaj=($row_paquete['int_undxcaja_tn']/$row_paquete['int_undxpaq_tn']);} else  {$paqxcaj=($row_tiquete_num['int_undxcaja_tn']/$row_tiquete_num['int_undxpaq_tn']);}?>
			  <?php if($num>$paqxcaj){?>
              <input name="paqCompleto" type="hidden" value="1">
              <?php }?>
              <!--CONTROS DE PAQUETES POR CAJA REFERENCIAS ESPECIFICAS-->
              <?php if($row_control_paquete['int_cod_ref_op']=='096'){?>
              <span class="rojo_inteso"><a href="sellado_control_numeracion_edit_paqxcaja.php?id_op=<?php echo $row_tiquete_num['int_op_tn']; ?>&int_caja_tn=<?php echo $row_tiquete_num['int_caja_tn']; ?>&NumeroPaqxCaja=<?php echo $paqxcaj; ?>" title="Paquetes x Caja">P.X.C</a></span><?php }?></td>
            <td id="dato3"><strong>CAJA N : <span class="rojo_inteso">
					<!--if($num==$row_op['int_paquete_n']){$num2=$num2+1;}-->
                    <?php if($row_tiquete_num['int_caja_tn']=='')$num2=$row_paquete['int_caja_tn']+1; else  $num2=$row_tiquete_num['int_caja_tn']; ?>
                    </span>
                <input name="int_caja_tn" style="width:50px;" type="number" <?php if ($row_usuario['tipo_usuario']!='1') {echo "readonly='readonly'";}?> id="int_caja_tn" value="<?php echo $num2; //}?>"maxlength="5">
                </span></strong></td>
            <td id="dato2">&nbsp;</td>
            </tr>
          <tr>
            <td id="fuente1">FECHA</td>
            <td colspan="2" id="fuente1"><input name="fecha_ingreso_tn" type="date" min="2000-01-02" value="<?php echo fecha();?>" size="10"/>
              <input name="hora_tn" type="hidden" id="hora_tn" value="<?php echo Hora();?>" size="8" readonly /></td>
          </tr>
          <tr>
            <td id="fuente1">ORDEN P.</td>
            <td colspan="2" ><input type="number" name="int_op_tn" id="pswd" value="<?php if($row_tiquete_num['int_op_tn']=='')echo $row_paquete['int_op_tn']; else echo $row_tiquete_num['int_op_tn'];?>" readonly ></td>
            </tr>
          <tr>
            <td id="fuente1">BOLSAS</td>
            <td colspan="2" ><input type="number" name="int_bolsas_tn" id="pswd" min="0" value="<?php if($row_tiquete_num['int_bolsas_tn']=='')echo $row_paquete['int_bolsas_tn']; else echo $row_tiquete_num['int_bolsas_tn'];?>" readonly></td>
          </tr>
      
          <tr>
            <td id="fuente1">UNIDADES X CAJA</td>
            <td colspan="2" ><input type="number" name="int_undxcaja_tn" id="pswd" min="0" <?php if ($row_usuario['tipo_usuario']!='1') {echo "readonly";}?> value="<?php echo $row_control_paquete['int_undxcaja_op'];?>"></td> 
          </tr>
          <tr>
            <td id="fuente1">UNIDADES  X PAQ.</td>
            <td colspan="2" ><input type="number" name="int_undxpaq_tn" id="pswd" readonly min="0" value="<?php echo $row_control_paquete['int_undxpaq_op'];?>"></td>
            </tr>
          <tr>
            <td id="fuente1"><strong>DESDE</strong></td>
            <td colspan="2" >
           <?php 
		    //CONTROL DE CADENA DIVIDE NUMEROS DE LETRAS PARA INICIAR CON EL ULTIMO DEL ULTIMO PAQUETE SUMAR 1 AL DESDE DEL NUEVO PAQUETE
if($row_tiquete_num['int_hasta_tn']==''){$desde=$row_paquete['int_hasta_tn'];}else{$desde=$row_tiquete_num['int_hasta_tn'];} //if($row_paquete['int_hasta_tn']==''){$desde=$row_nuevo_paquete['int_hasta_tn'];}	
			  $cadena = $desde;
/*			  $numero = "";
			  $mystring = $desde;
			  $findme   = array('AA1F','AA1G','AA1H','AA1I','AA1J','AA1K','AA1L','AA1M','AA1N','AA1B','AA1C','AA1D','AA1E');
  
			  foreach ($findme as &$valor) {  //FOREACH PARA COMPARAR EL ARRAY CON CADENA
			  $valor = $valor ;			
			  $pos = strpos($mystring, $valor);
							  
			  if ($pos !== false) {
			  $cade = substr($mystring, 0, 4);			
			  $nu = substr($mystring, 4);
			  $numE =$nu+1;
			  $cadena_letras = $cade.$numE;
			  }	else {//FIN SI NO HAY SUBCADENA
			  $cadena = $mystring;
			  $numero = "";
	  
			 for( $index = 0; $index < strlen($cadena); $index++ )
			 {
			 if( is_numeric($cadena[$index]) )
			 {
			 $numero .= $cadena[$index];
			 $numeroUno=$numero+1;
			 $cadel=ereg_replace("[0-9]", "", $cadena);
			 $cadena_letras=$cadel.$numeroUno;
			                    }
			             }
			       }
			 }*/
	       ?>	          
          <input type="text" name="int_desde_tn" autofocus id="pswd" value="<?php echo $cadena; ?>"min="0" onChange="conMayusculas(this),sumaPaqSelladoAdd(this);" required></td>
          </tr>
          <tr>
            <td id="fuente1"><strong>HASTA</strong></td>
            <td colspan="2" ><input type="text" name="int_hasta_tn" id="pswd" readonly required value="" min="0"></td>
          </tr>
          <tr>
            <td nowrap id="fuente1">CODIGO DE OPERARIO</td>
            <td colspan="2" id="fuente1"><select name="int_cod_empleado_tn" id="operario" onBlur="if(form1.int_cod_empleado_tn.value=='') { alert('Debe Seleccionar un empleado')}" style="width:157px">
              <option value=""<?php if (!(strcmp("", $row_op['int_cod_empleado_tn']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
              <?php
do {  
?>
              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_op['int_cod_empleado_tn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
              <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
            </select></td>
          </tr>
<tr>
            <td id="fuente1">CODIGO DE REVISOR</td>
            <td colspan="2" id="fuente1"><select name="int_cod_rev_tn" id="revisor" onBlur="if(form1.int_cod_rev_tn.value=='') { alert('Debe Seleccionar un revisor')}" style="width:157px">
              <option value=""<?php if (!(strcmp("", $row_op['int_cod_rev_tn']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
              <?php
do {  
?>
              <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_op['int_cod_rev_tn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
              <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
            </select></td>
          </tr>          
          <tr>
            <td id="fuente1">PAQUETES X CAJA</td><?php if($row_tiquete_num['int_undxpaq_tn']=='') {$paqxcaja=($row_paquete['int_undxcaja_tn']/$row_paquete['int_undxpaq_tn']);} else  {$paqxcaja=($row_tiquete_num['int_undxcaja_tn']/$row_tiquete_num['int_undxpaq_tn']);}?>
            <td colspan="2" id="fuente1"><strong><?php echo $num. " de " . $paqxcaja;?></strong></td>
          </tr>
          <tr>
            <td colspan="3">           
             </td>
            </tr>
            <tr>
            <td colspan="3"></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><button type="submit" onClick="return funcion2();" style='width:350px; height:60px' autofocus>GUARDAR NUMERACION</button></td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
          </tr>          
            <tr>
            <td colspan="3"><?php if($row_tiquete_num['int_paquete_tn']!=''){?>
            <span class="Estilo1">PAQUETES</span>
            <div class="bordesolido" id="ventanas">
            <?php  do { ?>
            <p><a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_tiquete_num['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_tiquete_num['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_tiquete_num['int_caja_tn']; ?>','770','300')" target="_top"><?php echo "PAQUETE #: ".$row_tiquete_num['int_paquete_tn']. " DESDE: ".  $row_tiquete_num['int_desde_tn']. " HASTA: ".$row_tiquete_num['int_hasta_tn'];?></a>-----<a href="javascript:eliminar_sp('id_tn',<?php echo $row_tiquete_num['id_tn'];?>,'sellado_control_numeracion_edit.php')">
            <img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR TIQUETE" title="ELIMINAR TIQUETE" border="0"></a></p>
            <?php } while ($row_tiquete_num = mysql_fetch_assoc($tiquete_num)); ?>
            </div><?php }?>
            <?php if($row_caja_num['int_caja_tn']!=''){?>
            <span class="Estilo1">CAJAS</span><div class="bordesolido" id="ventanas">
              <?php  do { ?>         
            <p><a href="javascript:popUp('sellado_control_cajas_vista.php?id_op=<?php echo $row_caja_num['int_op_tn']; ?>&int_caja_tn=<?php echo $row_caja_num['int_caja_tn']; ?>','770','300')" target="_top"><?php echo "CAJA REGISTRO X CAJAS #: ".$row_caja_num['int_caja_tn']?></a></p>
            <?php  } while ($row_caja_num = mysql_fetch_assoc($caja_num)); ?></div><?php }?></td>
            </tr>
            <tr>
            <td colspan="3" id="dato2"><strong>FALTANTES</strong></td>
            </tr>
        </table>
            <!--            TABLA DE FALTANTES-->  
	<div id="contenedor">
		<table id="tablaf">
			<thead>
				<tr>
					<th width="110" id="nivel2">NUM. DESDE</th>
					<th width="110" id="nivel2">NUM. HASTA</th>
					<th width="40" id="nivel2">FALTAN.</th>
					<th width="50" id="nivel2"><button type="button" onClick="AddItem();" style='width:104px; height:35px'> + </button></th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
			<tfoot>
				<tr>
					<td id="nivel2">TOTAL FALT.</td>
					<td colspan="2" id="nivel2"><span id="total">0</span></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
		
	</div>                                
            <p>
              <input type="hidden" name="MM_insert" value="form1">
            </p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
      </form></td>
  </tr>
</table></div>
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

mysql_free_result($codigo_empleado);

mysql_free_result($op);

mysql_free_result($tiquete_num);

mysql_free_result($paquete);

mysql_free_result($control_paquete);

?>