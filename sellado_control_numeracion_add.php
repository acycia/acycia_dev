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

  /*&&&&&&&&&&&&&&&&&&&&&&&&&nuevo codigo&&&&&&&&&&&&&&&&&&&&&&&&6*/
//CAMBIO DE NUMERACION POR REFERENCIA EN O.P
/*$cod_ref_op = $_POST['cod_ref_n'];
 $sqlvi="SELECT id_op FROM Tbl_orden_produccion WHERE int_cod_ref_op='$cod_ref_op' ORDER BY id_op DESC LIMIT 1";
$resultvi = mysql_query($sqlvi);
$numvi= mysql_num_rows($resultvi);
if($numvi >='1')
  { 
   $numeracion=$_POST['int_hasta_tn'];
   $id_op_n = mysql_result($resultvi, 0, 'id_op');
   $updateINV = "UPDATE Tbl_orden_produccion SET numInicio_op='$numeracion' WHERE id_op='$id_op_n'";
   $resultINV=mysql_query($updateINV);
 }*/


//se debe actualizar por ref en op
 $cod_ref_op = $_POST['cod_ref_n'];
 $numeracion=$_POST['int_hasta_tn'];
 $updateINV = "UPDATE Tbl_orden_produccion SET numInicio_op='$numeracion' WHERE int_cod_ref_op='$cod_ref_op'";
 $resultINV=mysql_query($updateINV);
 /*&&&&&&&&&&&&&&&&&&&&&&&&&nuevo codigo&&&&&&&&&&&&&&&&&&&&&&&&6*/

 $insertSQL = sprintf("INSERT INTO Tbl_numeracion (id_numeracion, fecha_ingreso_n, int_op_n, cod_ref_n, int_bolsas_n, int_undxpaq_n, int_undxcaja_n,  int_desde_n, int_hasta_n, int_cod_empleado_n, int_cod_rev_n, int_paquete_n, int_caja_n, b_borrado_n, existeTiq_n) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['id_numeracion'], "int"),
   GetSQLValueString($_POST['fecha_ingreso_tn'], "date"),
   GetSQLValueString($_POST['int_op_tn'], "int"),
   GetSQLValueString($_POST['cod_ref_n'], "text"),
   GetSQLValueString($_POST['int_bolsas_tn'], "int"),
   GetSQLValueString($_POST['int_undxpaq_tn'], "int"),
   GetSQLValueString($_POST['int_undxcaja_tn'], "int"),
   GetSQLValueString($_POST['int_desde_tn'], "text"),
   GetSQLValueString($_POST['int_hasta_tn'], "text"),
   GetSQLValueString($_POST['int_cod_empleado_tn'], "int"),
   GetSQLValueString($_POST['int_cod_rev_tn'], "int"),
   GetSQLValueString($_POST['int_paquete_tn'], "int"), 
   GetSQLValueString($_POST['int_caja_tn'], "int"),
   GetSQLValueString($_POST['b_borrado_n'], "int"),
   GetSQLValueString($_POST['existeTiq_n'], "int"));             
 mysql_select_db($database_conexion1, $conexion1);
 $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

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
        $insertSQL2 = sprintf("INSERT INTO tbl_faltantes (id_op_f, int_paquete_f, int_caja_f, int_inicial_f, int_final_f, int_total_f) VALUES (%s, %s, %s, %s, %s, %s)",
                       //GetSQLValueString($_POST['id_tn'], "int"),
         GetSQLValueString($_POST['int_op_tn'], "int"),
         GetSQLValueString($_POST['int_paquete_tn'], "int"),
         GetSQLValueString($_POST['int_caja_tn'], "int"),
         GetSQLValueString($a[$i], "text"),
         GetSQLValueString($b[$i], "text"),
         GetSQLValueString($totalfalt, "int"));

        mysql_select_db($database_conexion1, $conexion1);
        $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error()); 
      }else {echo "<script type=\"text/javascript\">alert(\"Los faltantes desde: $des No debe ser mayor al faltante hasta: $hasta \");return false;history.go(-1)</script>";}//FIN PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO 
     }else {echo "<script type=\"text/javascript\">alert(\"Los faltantes hasta: $hasta No debe ser menor al faltante desde: $des \");return false;history.go(-1)</script>";}//FIN EL RANGO HASTA NO DEBE SER MANOR AL DESDE
    }else {echo "<script type=\"text/javascript\">alert(\"Los faltantes: $des Y $hasta No estan dentro del rango\");return false;history.go(-1)</script>";}//FIN EL RANGO HASTA NO DEBE SER MANOR AL DESDE
   }//FIN FOR
 }//FALTATES ENVIADOS Y LLENOS
if($desdef!='d'){//PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO 
if($hastamenos!='i'){ //EL RANGO HASTA NO DEBE SER MENOR AL DESDE
if($desdemenos!='e'){//EL RANGO DESDE NO DEBE SER MAYOR AL HASTA      
  $insertSQL3 = sprintf("INSERT INTO tbl_tiquete_numeracion ( int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, contador_tn, int_paquete_tn, int_caja_tn,pesot,ref_tn) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
   GetSQLValueString($_POST['contador_tn'], "int"),
   GetSQLValueString($_POST['int_paquete_tn'], "int"),
   GetSQLValueString($_POST['int_caja_tn'], "int"),
   GetSQLValueString($_POST['pesot'], "text"),
   GetSQLValueString($_POST['cod_ref_n'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error());  

/*  $updateSQL = sprintf("UPDATE Tbl_numeracion SET int_hasta_n=%s,int_paquete_n=%s,int_caja_n=%s WHERE int_op_n=%s",
                       GetSQLValueString($_POST['int_hasta_tn'], "text"),
             GetSQLValueString($_POST['int_paquete_tn'], "int"),
                       GetSQLValueString($_POST['int_caja_tn'], "text"),
                       GetSQLValueString($_POST['int_op_tn'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL, $conexion1) or die(mysql_error());*/
  
  $imprimirt = $_POST['imprimirt'] =='' ? '0' :$_POST['imprimirt'];
  $insertGoTo = "sellado_control_numeracion_edit.php?id_op=". $_POST['int_op_tn'] . "&int_caja_tn=" . $_POST['int_caja_tn'] . "&imprimirt=" . $imprimirt . "";
  header(sprintf("Location: %s", $insertGoTo));

     }//alert
    }//alert
  }//alert
}//fin isset
//USUARIOS

$conexion = new ApptivaDB();


$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
$row_usuario = $conexion->llenarCampos('usuario',"WHERE usuario='$colname_usuario'", '','*'); 

//ID NUMERACION
 
$row_ultimo = $conexion->buscarId('tbl_numeracion','id_numeracion');

//LISTA EMPLEADOS
$row_codigo_empleado = $conexion->llenaSelect('empleado','WHERE tipo_empleado IN(7,9)','ORDER BY nombre_empleado ASC'); 
 
$row_revisor = $conexion->llenaSelect('empleado','WHERE tipo_empleado IN(7,9)','ORDER BY nombre_empleado ASC'); 

//LISTA DE O.P
/*mysql_select_db($database_conexion1, $conexion1);
$query_op = "SELECT tbl_orden_produccion.id_op FROM tbl_orden_produccion WHERE Tbl_orden_produccion.id_op NOT IN (SELECT Tbl_numeracion.int_op_n FROM Tbl_numeracion ) ORDER BY tbl_orden_produccion.id_op DESC";
$op = mysql_query($query_op, $conexion1) or die(mysql_error());
$row_op = mysql_fetch_assoc($op);
$totalRows_op = mysql_num_rows($op);*/
$row_op = $conexion->llenaListas('tbl_orden_produccion','WHERE tbl_orden_produccion.id_op NOT IN(SELECT tbl_numeracion.int_op_n FROM Tbl_numeracion )', 'ORDER BY tbl_orden_produccion.id_op DESC','tbl_orden_produccion.id_op'); 

if(isset($_GET['id_op'])&& $_GET['id_op']!=''){


//CARGA DATOS DE OP

$row_op_carga = $conexion->llenarCampos('tbl_orden_produccion','WHERE id_op='.$_GET['id_op'], '','*'); 

 
//LISTA DE CAJAS

$row_caja_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."'", "ORDER BY int_caja_tn DESC LIMIT 1", "DISTINCT int_caja_tn,int_op_tn ");

$select_caja_num = $conexion->llenaListas('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."'",'ORDER BY int_caja_tn DESC', "DISTINCT int_caja_tn,int_op_tn"); 

//CONSECUTIVO DE PAQUETES

$row_paquete = $conexion->llenarCampos('tbl_orden_produccion pro,tbl_numeracion nume','WHERE pro.int_cod_ref_op=nume.cod_ref_n AND pro.id_op='.$_GET['id_op'],"ORDER BY nume.int_op_n DESC LIMIT 1","nume.int_hasta_n, nume.int_paquete_n, nume.int_caja_n");
}



//AGREGADA PARA Q NO USEN EL VIEJO SISTEMA
header ("Location:view_index.php?c=csellado&a=Inicio");

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <link href="css/general.css" rel="stylesheet" type="text/css" />

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
    <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body onLoad="sumaPaqSelladoAdd();"><!--onLoad="sumaPaqSelladoAdd();"--> 
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
                <tr><td id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></td>
                  <td id="cabezamenu"><ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>  
                  </ul>
                </td>
              </tr>  
              <tr>
                <td colspan="2" align="center" id="linea1">
                  <form action="<?php echo $editFormAction; ?>" method="POST" id="form1" name="form1" onSubmit="return validacion_select();">
                    <table align="center" id="tabla35">
                      <tr>
                        <td colspan="2" id="dato3"><a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" border="0"title="LISTADO O.P"/> </a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="titulo2">REGISTRO DE PAQUETES <strong>
                          <?php /*if($row_paquete['id_tn']==''){$id_tn=$row_paquete['id_tn']; }else{  $id_tn=$row_paquete['id_tn']; }*/
                          $num_ultimo=$row_ultimo['id']+1; ?>
                          <input name="id_numeracion" type="hidden" value="<?php echo $num_ultimo; ?>">
                          <!--<input type="hidden" name="id_tn" id="id_tn" value="<?php echo $id_tn; //}?>">-->
                          <input type="hidden" name="b_borrado_n" id="b_borrado_n" value="0">
                          <input type="hidden" name="existeTiq_n" id="existeTiq_n" value="1">
                          <div style="display: none;" ><input name="cod_ref_n" type="hidden" id="cod_ref_n" value="<?php echo $row_op_carga['int_cod_ref_op']; ?>"></div>
                        </strong></td>
                      </tr>
                      <tr>
                        <td id="dato1"><strong>PAQUETE N&deg;:</strong><span class="rojo_inteso">
                         <?php 
                         $restrincion = $_SESSION['superacceso'];
                         $num_paq=$row_paquete['int_paquete_n']; 
                         if($num_paq==''){$num_paq='1';}


                         if($row_op_carga['int_cod_ref_op'] =='096'){
                           $sumauno='1';//para que siga el consecutivo del ultimo paquete de la 096 
       } 
       ?></span>
       <input name="int_paquete_tn" style="width:60px;" type="number" <?php //if ($restrincion!='1') {echo "readonly";}?> id="int_paquete_tn" value="<?php echo $num_paq+$sumauno; ?>" maxlength="5"></td>
       <td id="dato1"><strong>CAJA N&deg; : <span class="rojo_inteso">
         <?php 
         $num_caj=$row_paquete['int_caja_n']; 
         if($num_caj==''){$num_caj='1';}?></span>
         <input name="int_caja_tn" id="int_caja_tn" style="width:60px;" <?php //if ($restrincion!='1') {echo "readonly";}?> type="number" value="<?php echo $num_caj;?>">
       </span></strong>
     <em>Imprimir todos los tick x caja</em>
     <input class="check" type="checkbox" name="imprimirt" id="imprimirt" <?php if($row_control_paquete['imprimiop']=='0'){echo 'disabled="disabled"'; }  ?> value="<?php echo $row_op_carga['imprimiop'];  ?>" onchange="javascript:todosTicket()" /> </td> 
     </tr>
     <tr>
      <td id="fuente1">FECHA</td>
      <td id="fuente1"><input name="fecha_ingreso_tn" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" style="width:173px"/>
        <input name="hora_tn" type="hidden" id="hora_tn" value="<?php echo Hora();?>" size="8" readonly /></td>
      </tr>
      <tr>
        <td id="fuente1">ORDEN P.</td>
        <td id="fuente1"><select name="int_op_tn" id="int_op_tn" onChange="if(form1.int_op_tn.value) {consulta_m_op()}else { alert('Debe Seleccionar una O.P')}">
          <option value=""<?php if (!(strcmp("", $row_op_carga['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
          <?php  foreach($row_op as $row_op ) { ?>
            <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $row_op_carga['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['id_op']?></option>
            <?php }
          ?>
        </select> Ref: <?php echo $row_op_carga['int_cod_ref_op'];?></td>
      </tr>
      <tr>
        <td id="fuente1">BOLSAS millar/und</td>
        <td ><input type="number" name="int_bolsas_tn" id="pswd" min="0" value="<?php echo $row_op_carga['int_cantidad_op'];?>" readonly></td>
      </tr>
      
      <tr>
        <td id="fuente1">UNIDADES X CAJA</td>
        <td ><input type="number" name="int_undxcaja_tn" <?php if ($restrincion!='1') {echo "readonly";}?> id="int_undxcaja_tn" min="0" value="<?php echo $row_op_carga['int_undxcaja_op'];?>"></td> 
      </tr>
      <tr>
        <td id="fuente1">UNIDADES  X PAQ.</td>
        <td ><p>
          <input type="number" name="int_undxpaq_tn" readonly id="pswd"min="0" value="<?php echo $row_op_carga['int_undxpaq_op'];?>" >
        </p>
        <p><em>Cambiar en o.p</em></p></td>
      </tr>
      <tr>
        <td id="fuente1"><strong>DESDE</strong></td>
        <td >
          <?php
          $colname_numeracion =$row_op_carga['int_cod_ref_op'];
             $sqldato="SELECT int_hasta_tn FROM tbl_tiquete_numeracion WHERE ref_tn=$colname_numeracion ORDER BY id_tn DESC, int_op_tn DESC,int_hasta_tn DESC";
                $resultdato=mysql_query($sqldato);
                   $row_numeracion= mysql_num_rows($resultdato);
                     if($row_numeracion!='') { 
                          $hasta_paq=mysql_result($resultdato,0,'int_hasta_tn'); //int_hasta_n
                     }
                 $desde=$row_op_carga['numInicio_op']; 
        if($desde==''){$desde=$hasta_paq;}else{$desde=$row_op_carga['numInicio_op'];} 
        ?>
<input type="text" <?php if ($restrincion!='1') {echo "readonly";}?> name="int_desde_tn"  id="pswd" <?php if($row_op_carga['numInicio_op'] != $hasta_paq){?> onClick="numeracionDistinta();"<?php } echo $desde;?> value="<?php echo $desde;?>"  min="0" onBlur="conMayusculas(this);" onChange="sumaPaqSelladoAdd(this);"  autofocus required></td> 
</tr>
<tr>
  <td id="fuente1"><strong>HASTA</strong></td>
  <td ><input type="text" name="int_hasta_tn" id="pswd" required value="" min="0" <?php if ($row_usuario['tipo_usuario']!='1' && $row_usuario['tipo_usuario']!='14'){echo "readonly";} echo $desde;?>></td> 
</tr>
<tr>
  <td id="fuente1">CODIGO DE OPERARIO</td>
  <td id="fuente1">
 
    <select name="int_cod_empleado_tn" id="operario" style="width:157px" >
    <option value="">Seleccione</option>
    <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
      <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['nombre_empleado']?></option>
      <?php }  ?>
  </select></td>
</tr>
<tr>
  <td id="fuente1">CODIGO DE REVISOR</td>
  <td id="fuente1"> 
    <select name="int_cod_rev_tn" id="revisor" style="width:157px">
      <option value=""<?php if (!(strcmp("", $row_op['int_cod_rev_tn']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
      <?php  foreach($row_revisor as $row_revisor ) { ?>
        <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_op['int_cod_rev_tn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
      <?php } ?>
    </select></td>
</tr>          
<tr>
  <div id="paqycajasnormal" style="display: none;">  
     <div id="tiquetxCajas" style="display: none;" >
      <td id="fuente1">PAQUETES X CAJA</td>
      <td id="fuente1"><strong><?php 
      if($row_op_carga['int_cod_ref_op'] =='096'){
         $sumauno='1';//para que siga el consecutivo del ultimo paquete de la 096
         $refInic=$row_op_carga['int_cod_ref_op'];
         $num_caj=$row_paquete['int_caja_n']; 
         $resultdato = $conexion->llenarCampos('tbl_numeracion,tbl_tiquete_numeracion',"WHERE tbl_numeracion.cod_ref_n='$refInic' AND tbl_numeracion.int_op_n = tbl_tiquete_numeracion.int_op_tn AND tbl_tiquete_numeracion.int_caja_tn='$num_caj'", 'ORDER BY contador_tn DESC  LIMIT 1','int_caja_tn,contador_tn');  
         /*$sqldato="SELECT int_caja_tn,contador_tn FROM tbl_numeracion,tbl_tiquete_numeracion WHERE tbl_numeracion.cod_ref_n='$refInic' AND tbl_numeracion.int_op_n = tbl_tiquete_numeracion.int_op_tn AND tbl_tiquete_numeracion.int_caja_tn='$num_caj' ORDER BY contador_tn DESC  LIMIT 1";
         $resultdato=mysql_query($sqldato);
         $numce1= mysql_num_rows($resultdato); */
            if($resultdato['contador_tn']!='') { 
              $contador=$resultdato['contador_tn']; 
            } 
            $num_paq=$contador+$sumauno; 
         }
           echo $num_paq. " de " . $paqxcaj=$row_op_carga['int_undxcaja_op']/$row_op_carga['int_undxpaq_op'];?>
                 <input name="contador_tn" type="hidden" value="<?php echo $num_paq;?>">
               </strong>

              </td>
              </div>
          </div> 
             </tr>
             <tr>
              <td colspan="2">            
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <!--CONTROS DE PAQUETES-->
                <?php if($num_paq>$paqxcaj){?>
                  <input name="paqCompleto" type="hidden" value="1">
                <?php }?>
              </td>
              
              <td id="dato1"><?php if($num_paq==$paqxcaj){?>
                <input name="pesot" required="required" style="width:80px;" step="0.01" type="number" id="pesot" placeholder="Peso Caja">
              <?php }?>
            </td>

          </tr>
          <tr>
            <td colspan="2" id="dato2">
              <!-- <button class="botonSellado" type="submit" style="width:350px; height:60px"<?php if($row_op_carga['numInicio_op'] != $hasta_paq){ ?>  onClick="numeracionDistinta();" <?php } ?>>GUARDAR NUMERACION</button> -->

            <div id="botonSellado" style="display: none;"><button type="submit" class="botonSellado" <?php if($row_op_carga['numInicio_op'] != $hasta_paq){ ?>  onClick="numeracionDistinta();" <?php } ?> >GUARDAR NUMERACION</button>
              </div><!--onClick="return funcion2();"-->
            <div id="botonporCajas" style="display: none;">
              <button id="guardabotonporCajas" name="guardabotonporCajas" type="button" class="botonSelladoxCaja" >GUARDAR NUMERACION X CAJA</button><br><em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG" ></em>   
            </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" id="dato2">&nbsp;</td>
          </tr>          
          <tr>
            <td colspan="2"><?php if($row_tiquete_num['int_paquete_n']!=''){?>
              <span class="Estilo1">PAQUETES</span>
              <div class="bordesolido" id="ventanas">
                <?php  do { ?>
                  <p><a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_tiquete_num['int_op_n']; ?>&id_tn=<?php echo $row_tiquete_num['id_tn']; ?>','770','300')" target="_top"><?php echo "PAQUETE #: ".$row_tiquete_num['int_paquete_n']. " DESDE: ".  $row_tiquete_num['int_desde_n']. " HASTA: ".$row_tiquete_num['int_hasta_n']?></a>-----<a href="javascript:eliminar_sp('id_tn',<?php echo $row_tiquete_num['id_tn'];?>,'sellado_control_numeracion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR TIQUETE"
                    title="ELIMINAR TIQUETE" border="0"></a></p>
                  <?php } while ($row_tiquete_num = mysql_fetch_assoc($tiquete_num)); ?>
                  </div><?php }?>
                  <?php if($row_caja_num['int_caja_tn']!=''){?>
                    <span class="Estilo1">CAJAS</span><div class="bordesolido" id="ventanas">
                     <?php foreach($select_caja_num as $row_caja_num) {  ?>

                        <p><a href="javascript:popUp('sellado_control_cajas_vista.php?id_op=<?php echo $row_caja_num['int_op_tn']; ?>&int_caja_tn=<?php echo $row_caja_num['int_caja_tn']; ?>','770','300')" target="_top"><?php echo "CAJA REGISTRO X CAJAS #: ".$row_caja_num['int_caja_tn']?></a></p>
                        <?php  } ?></div>
                        <?php }?>
                      </td>
                      </tr> 
                    </table>
                  <div id="faltantess" style="display: none;" >
                    <!--            TABLA DE FALTANTES-->  
                    <div id="contenedor">
                      <table id="tablaf">
                        <thead>
                          <tr>
                            <h3>FALTANTES</h3>
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
                </div>
                    <p>        
                      <input type="hidden" name="MM_insert" value="form1">
                    </p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                  </form>
                  <script>
                    document.addEventListener('DOMContentLoaded', () => {
                      document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
                        if(e.keyCode == 13) {
                          e.preventDefault();
                        }
                      }))
                    });
                    
                    if(document.form1.paqCompleto.value=='1'){
                      var id_tn=document.form1.id_tn.value;
                      var id_op=document.form1.int_op_tn.value;
                      var caj=document.form1.int_caja_tn.value;
                      var caja=parseInt(caj) + parseInt(1);
                      document.querySelector('#form1').addEventListener('submit', function(e) {
                        var form = this;
                        e.preventDefault();
                        swal({
                          title: "Cambio de caja?",
                          text: "Se gurdaran los archivos y cambiara de caja!",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonColor: '#DD6B55',
                          confirmButtonText: 'Si, estoy seguro!',
                          cancelButtonText: "No, cancelarla!",
                          closeOnConfirm: false,
               // closeOnCancel: false
             },
             function(isConfirm) {
              if (isConfirm) {
                swal({
                  title: 'Preseleccion!',
                  text: 'Los archivos fueron gurdados correctamente!',
                  type: 'success',
                  timer: 10,
                  showConfirmButton: false
                }, function() {
            // form.submit();
            window.location ='sellado_control_numeracion_edit.php?id_op='+id_op+'&id_tn='+id_tn+'&int_caja_tn='+caja
          });

              } else {
                swal("Cancelado " , " No se cambio de caja :)", "error");
              }
            });
                      });
                    }
                  </script>
<script type="text/javascript">
   if($("#imprimirt").val()==1) {$("#imprimirt").prop("checked", true);  }else{$("#imprimirt").prop("checked", false);}  

    botonSellado.style.display='block';
    faltantess.style.display='block';  
   $(document).ready(todosTicket);

    function todosTicket(){
          element = document.getElementById("tiquetxCajas");
          check = document.getElementById("imprimirt");
          botonSellado = document.getElementById("botonSellado");
          botonporCajas = document.getElementById("botonporCajas");
          if (check.checked) {
              document.form1.int_paquete_tn.value = 1;
              faltantess.style.display='none';  
              element.style.display='block';
              botonporCajas.style.display='block';
              paqycajasnormal.style.display='none';
              botonSellado.style.display='none';
              sumaPaqSelladoTiqxCaja();//suma al hasta 
             
          } else {
              document.form1.int_paquete_tn.value = '<?php echo  $num_paq;?>';
              faltantess.style.display='block'; 
              element.style.display='none';
              botonporCajas.style.display='none';
              paqycajasnormal.style.display='block';
              botonSellado.style.display='block';
              sumaPaqSelladoAdd();//suma al hasta
          }
 
  }


$( "#guardabotonporCajas" ).on( "click", function() { 
 
   if($("#int_paquete_tn").val()=='' || $("#int_caja_tn").val()=='' || $("#fecha_ingreso_tn").val()=='' || $("#int_op_tn").val()=='' || $("#int_bolsas_tn").val()=='' || $("#int_undxcaja_tn").val()=='' || $("#int_undxpaq_tn").val()=='' || $("#int_desde_tn").val()=='' || $("#int_hasta_tn").val()=='' || $("#operario").val()=='' || $("#revisor").val()=='' || $("#pesot").val()=='')
   {
     swal("Error", "Hay campos vacios! :)", "error"); 
     return false;
   }else{  
      var caja = (parseInt(document.form1.int_caja_tn.value) );
      var id_op=document.form1.int_op_tn.value;
      var imprimirt=document.form1.imprimirt.value=1;   
      window.location ='sellado_control_numeracion_edit.php?id_op='+id_op+'&imprimirt='+imprimirt+'&int_caja_tn='+caja;
      guardarGeneral("AjaxControllers/Actions/guardar.php");   
   } 
  
   });
</script>
                </td>
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

          mysql_close($conexion1);
          ?>