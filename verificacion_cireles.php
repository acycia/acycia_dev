<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php 
 require_once('Connections/conexion1.php');
 require_once("db/db.php");
 require_once("Controller/Cgeneral.php");
 require_once ('Models/Mgeneral.php');
 
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



$colname_ref_egp = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_ref_egp = $_GET['cod_ref'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.cod_ref = '%s' AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", GetSQLValueString($colname_ref_egp, "int"));
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);


//CUANDO NO TIENE UN REPORTE DE PLANCHAS SE INSERTA LA VERIFICACION AUTOMATICA
  $conexion = new CgeneralController();


  $ref=$row_ref_egp['id_ref'];
  $resultExiste=$conexion->llenarCampos('verificacion', "WHERE id_ref_verif='$ref'", "ORDER BY id_verif DESC","id_verif,id_ref_verif" ); 

    $_POST['id_verif'] = $resultExiste['id_verif'];
    $_GET['id_verif'] = $resultExiste['id_verif'];


if((isset($_GET["id_verif2"])) && ($_GET["id_verif2"] != "")){

/*  $ids = $conexion->consultarIdes("verificacion","id_verif");//se optiene el nuevo id verificacion
  $ids = $ids+1;//nueva verif
  $fecha =date("Y-m-d");
 
  
  $myVerif = new CgeneralController();
  $nuevovalorVerif = new omGeneral();
  
  //consulto si tiene verificacion
   $resultplancha = $conexion->llenarCampos('verificacion', "WHERE id_ref_verif=".$_GET['id_ref'], "ORDER BY id_verif DESC","id_verif,id_ref_verif" );   
   $id_verif = $resultplancha['id_verif'];
           
   if($id_verif=='') {
  //INGRESO VERIFICAION
        $nuevovalorVerif = [ "id_verif"=>$ids,"id_ref_verif"=>$_GET["id_ref"],"version_ref_verif"=>"00","fecha_verif"=>$fecha,"responsable_verif"=>$_SESSION['Usuario']
    ];    

        $columnasVerif = ["id_verif"=>"id_verif","id_ref_verif"=>"id_ref_verif","version_ref_verif"=>"version_ref_verif","fecha_verif"=>"fecha_verif","responsable_verif"=>"responsable_verif"
    ];


 
   }*/
         
            //CONSULTO CLIENTE
            $resultVERIF=$conexion->llenarCampos('tbl_cotiza_bolsa cb, cliente c', "WHERE cb.Str_nit=c.nit_c AND cb.N_referencia_c=".$_GET['cod_ref'], "ORDER BY c.id_c,cb.N_cotizacion DESC LIMIT 1"," c.nombre_c" ); 
         $resultVERIF['nombre_c'];
             $_GET['id_verif'] = $_GET['id_verif2'];//PARA CARGAR INFO DE VERIF
            //$_POST['id_verif']= $id_verif =='' ? $ids : $id_verif;
            $vista = "Location: verificacion_cireles.php?id_verif=".$_GET['id_verif2']."";   

            //$myVerif->insertarGen("verificacion", $columnasVerif, $nuevovalorVerif,$vista);
          
    
   }
 


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") ) {
 
     
    if( $resultExiste['id_verif']!='')
     {


          $updateSQL = sprintf("UPDATE verificacion SET `1color_cirel`=%s, observacion_1color=%s, `2color_cirel`=%s, observacion_2color=%s, `3color_cirel`=%s, observacion_3color=%s, `4color_cirel`=%s, observacion_4color=%s, `5color_cirel`=%s, observacion_5color=%s, `6color_cirel`=%s, observacion_6color=%s, `7color_cirel`=%s, observacion_7color=%s, `8color_cirel`=%s, observacion_8color=%s, repeticion_cirel=%s, observacion_repeticion=%s, rodillo_cirel=%s, observacion_rodillo=%s, distancia_logos_cirel=%s, observacion_distancia_logos=%s, concuerda_texto_cirel=%s, observacion_concuerda_texto=%s, fecha_entrega_cirel=%s, registro_cirel=%s, fecha_registro_cirel=%s, modificacion_cirel=%s, fecha_modificacion_cirel=%s,pistas_cirel=%s,observacion_pistas=%s,insumo1=%s,insumo2=%s,insumo3=%s,insumo4=%s,insumo5=%s,insumo6=%s,insumo7=%s,insumo8=%s WHERE id_verif=%s",
                               GetSQLValueString(isset($_POST['1color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_1color'], "text"),
                               GetSQLValueString(isset($_POST['2color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_2color'], "text"),
                               GetSQLValueString(isset($_POST['3color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_3color'], "text"),
                               GetSQLValueString(isset($_POST['4color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_4color'], "text"),
                               GetSQLValueString(isset($_POST['5color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_5color'], "text"),
                               GetSQLValueString(isset($_POST['6color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_6color'], "text"),
                               GetSQLValueString(isset($_POST['7color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_7color'], "text"),
                               GetSQLValueString(isset($_POST['8color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_8color'], "text"),					   					   
                               GetSQLValueString($_POST['repeticion_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_repeticion'], "text"),
                               GetSQLValueString($_POST['rodillo_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_rodillo'], "text"),
                               GetSQLValueString($_POST['distancia_logos_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_distancia_logos'], "text"),
                               GetSQLValueString(isset($_POST['concuerda_texto_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_concuerda_texto'], "text"),
                               GetSQLValueString($_POST['fecha_entrega_cirel'], "date"),
                               GetSQLValueString($_POST['registro_cirel'], "text"),
                               GetSQLValueString($_POST['fecha_registro_cirel'], "date"),
                               GetSQLValueString($_POST['modificacion_cirel'], "text"),
                               GetSQLValueString($_POST['fecha_modificacion_cirel'], "date"),
                               GetSQLValueString($_POST['pistas_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_pistas'], "text"),
                               GetSQLValueString($_POST['insumo1'], "text"),
                               GetSQLValueString($_POST['insumo2'], "text"),
                               GetSQLValueString($_POST['insumo3'], "text"),
                               GetSQLValueString($_POST['insumo4'], "text"),
                               GetSQLValueString($_POST['insumo5'], "text"),
                               GetSQLValueString($_POST['insumo6'], "text"),
                               GetSQLValueString($_POST['insumo7'], "text"),
                               GetSQLValueString($_POST['insumo8'], "text"),
                               GetSQLValueString($_POST['id_verif'], "int")); 
          mysql_select_db($database_conexion1, $conexion1);
          $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
     }else{
          $updateSQL = sprintf("INSERT INTO verificacion (id_ref_verif,`1color_cirel`, observacion_1color, `2color_cirel`, observacion_2color, `3color_cirel`, observacion_3color, `4color_cirel`, observacion_4color, `5color_cirel`, observacion_5color, `6color_cirel`, observacion_6color, `7color_cirel`, observacion_7color, `8color_cirel`, observacion_8color, repeticion_cirel, observacion_repeticion, rodillo_cirel, observacion_rodillo, distancia_logos_cirel, observacion_distancia_logos, concuerda_texto_cirel, observacion_concuerda_texto, fecha_entrega_cirel, registro_cirel, fecha_registro_cirel, modificacion_cirel, fecha_modificacion_cirel,pistas_cirel,observacion_pistas,insumo1,insumo2,insumo3,insumo4,insumo5,insumo6,insumo7,insumo8) VALUES(%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s)",
                               GetSQLValueString($_POST['id_ref_verif'], "text"),
                               GetSQLValueString(isset($_POST['1color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_1color'], "text"),
                               GetSQLValueString(isset($_POST['2color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_2color'], "text"),
                               GetSQLValueString(isset($_POST['3color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_3color'], "text"),
                               GetSQLValueString(isset($_POST['4color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_4color'], "text"),
                               GetSQLValueString(isset($_POST['5color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_5color'], "text"),
                               GetSQLValueString(isset($_POST['6color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_6color'], "text"),
                               GetSQLValueString(isset($_POST['7color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_7color'], "text"),
                               GetSQLValueString(isset($_POST['8color_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_8color'], "text"),                        
                               GetSQLValueString($_POST['repeticion_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_repeticion'], "text"),
                               GetSQLValueString($_POST['rodillo_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_rodillo'], "text"),
                               GetSQLValueString($_POST['distancia_logos_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_distancia_logos'], "text"),
                               GetSQLValueString(isset($_POST['concuerda_texto_cirel']) ? "true" : "", "defined","1","0"),
                               GetSQLValueString($_POST['observacion_concuerda_texto'], "text"),
                               GetSQLValueString($_POST['fecha_entrega_cirel'], "date"),
                               GetSQLValueString($_POST['registro_cirel'], "text"),
                               GetSQLValueString($_POST['fecha_registro_cirel'], "date"),
                               GetSQLValueString($_POST['modificacion_cirel'], "text"),
                               GetSQLValueString($_POST['fecha_modificacion_cirel'], "date"),
                               GetSQLValueString($_POST['pistas_cirel'], "text"),
                               GetSQLValueString($_POST['observacion_pistas'], "text"),
                               GetSQLValueString($_POST['insumo1'], "text"),
                               GetSQLValueString($_POST['insumo2'], "text"),
                               GetSQLValueString($_POST['insumo3'], "text"),
                               GetSQLValueString($_POST['insumo4'], "text"),
                               GetSQLValueString($_POST['insumo5'], "text"),
                               GetSQLValueString($_POST['insumo6'], "text"),
                               GetSQLValueString($_POST['insumo7'], "text"),
                               GetSQLValueString($_POST['insumo8'], "text")); 
          mysql_select_db($database_conexion1, $conexion1);
          $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());



     }


   $updateSQL2 = sprintf("UPDATE Tbl_egp SET pantone1_egp=%s, pantone2_egp=%s, pantone3_egp=%s, 
   pantone4_egp=%s, pantone5_egp=%s, pantone6_egp=%s, pantone7_egp=%s, pantone8_egp=%s WHERE CONVERT(n_egp, SIGNED INTEGER) = ".$_GET['cod_ref']."  ",
                        GetSQLValueString($_POST['insumo1'], "text"),
                        GetSQLValueString($_POST['insumo2'], "text"),
                        GetSQLValueString($_POST['insumo3'], "text"),
                        GetSQLValueString($_POST['insumo4'], "text"),
                        GetSQLValueString($_POST['insumo5'], "text"),
                        GetSQLValueString($_POST['insumo6'], "text"),
                        GetSQLValueString($_POST['insumo7'], "text"),
                        GetSQLValueString($_POST['insumo8'], "text") );
 
   mysql_select_db($database_conexion1, $conexion1);
   $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());

   ///actualizo tabla de mezclas en impresion
   
   

   $updateSQL3 = sprintf("UPDATE tbl_caracteristicas_prod SET campo_1=%s,campo_3=%s,campo_5=%s,campo_7=%s,campo_9=%s,campo_11=%s WHERE CONVERT(cod_ref, SIGNED INTEGER) = ".$_GET['cod_ref']." AND proceso='2' ", 
                        GetSQLValueString($_POST['insumo3'], "text"),
                        GetSQLValueString($_POST['insumo4'], "text"),
                        GetSQLValueString($_POST['insumo5'], "text"),
                        GetSQLValueString($_POST['insumo6'], "text"),
                        GetSQLValueString($_POST['insumo7'], "text"),
                        GetSQLValueString($_POST['insumo8'], "text") ); 
 
   mysql_select_db($database_conexion1, $conexion1);
   $Result3 = mysql_query($updateSQL3, $conexion1) or die(mysql_error());


   $updateSQL4 = sprintf("UPDATE tbl_produccion_mezclas SET int_ref1_tol1_pm=%s,int_ref3_tol3_pm=%s WHERE CONVERT(int_cod_ref_pm, SIGNED INTEGER) = ".$_GET['cod_ref']." AND id_proceso='2' ",
                        GetSQLValueString($_POST['insumo1'], "text"),
                        GetSQLValueString($_POST['insumo2'], "text") ); 
   
   mysql_select_db($database_conexion1, $conexion1);
   $Result4 = mysql_query($updateSQL4, $conexion1) or die(mysql_error());




   $updateGoTo = "verificacion_cireles.php?id_verif=" . $_POST['id_verif'] . "";
 
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_verificar_cirel = "-1";
if (isset($_GET['id_verif'])) {
  $colname_verificar_cirel = $_GET['id_verif'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificar_cirel = sprintf("SELECT * FROM verificacion WHERE id_verif = %s", GetSQLValueString($colname_verificar_cirel, "int"));
$verificar_cirel = mysql_query($query_verificar_cirel, $conexion1) or die(mysql_error());
$row_verificar_cirel = mysql_fetch_assoc($verificar_cirel);
$totalRows_verificar_cirel = mysql_num_rows($verificar_cirel);
 

$colname_verif_ref_egp = "-1";
if (isset($_GET['id_verif'])) {
  $colname_verif_ref_egp = $_GET['id_verif'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_verif_ref_egp = sprintf("SELECT * FROM verificacion, Tbl_referencia, Tbl_egp WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", GetSQLValueString($colname_verif_ref_egp, "int"));
$verif_ref_egp = mysql_query($query_verif_ref_egp, $conexion1) or die(mysql_error());
$row_verif_ref_egp = mysql_fetch_assoc($verif_ref_egp);
$totalRows_verif_ref_egp = mysql_num_rows($verif_ref_egp);


$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
date_default_timezone_set("America/Bogota"); 

//SELECTS COMBOS
 $materiasss=$conexion->llenarSelects('insumo',"WHERE clase_insumo='8' AND estado_insumo='0' ", "ORDER BY descripcion_insumo ASC","id_insumo, descripcion_insumo " );

mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = ("SELECT id_insumo, descripcion_insumo FROM insumo WHERE clase_insumo='8' AND estado_insumo='0' ORDER BY descripcion_insumo ASC " );
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
?>
<html>
<head>
  <title>VERIFICAR CIRELES</title>
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/delete.js"></script> 
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


</head>
<body oncontextmenu="return false">
  <script>
    $(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table style="width: 100%">
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                 <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                 <div class="span12"><h3>&nbsp;&nbsp;&nbsp;&nbsp;VERIFICACION DE CIRELES &nbsp;&nbsp;&nbsp; </h3></div>
               </div>
               <div class="panel-heading" align="left" ></div><!--color azul-->

               <div class="panel-body">
                 <br> 
                 <div ><!--  SI QUITO  class="container" SE ALINEA A LA IZQUIERDA TODO EL CONTENIDO DE ESTE Y SE REDUCE -->
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2"> 
                       <tr>
                        <td id="subtitulo">
                         CIRELES <img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0">
                       </td>
                     </tr> 
                   </table> 
                 </div>
               </div>
               <br> 
               <br>
               <!-- grid --> 

               <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('repeticion_cirel','','R','rodillo_cirel','','R','distancia_logos_cirel','','R');return document.MM_returnValue">
                <table id="tabla_formato">
                  <tr id="fondo_3">
                    <td id="subtitulo_2">PANTONE</td>
                    <td id="subtitulo_2">PANTONE DE LA REFERENCIA</td>
                    <td id="subtitulo_2">CUMPLE</td>
                    <td id="subtitulo_2">OBSERVACION</td>
                  </tr>
                  <tr >
                    <td id="subtitulo1" colspan="2" >1.  
                     <select name="insumo1" id="insumo1" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone1_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
                        <?php foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone1_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone1_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                    </td>
                    <td id="subtitulo2"><input name="1color_cirel" type="checkbox" id="1color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['1color_cirel'],1))) {echo "checked=\"checked\"";} ?> <?php if (!(strcmp($row_verificar_cirel['1color_cirel'],1))) {echo "checked=\"checked\""; } ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_1color" value="<?php echo htmlentities($row_verificar_cirel['observacion_1color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>

                  <tr>
                    <td id="subtitulo1" colspan="2">2. 
                        <select name="insumo2" id="insumo2" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone2_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                        <?php foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone2_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone2_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                    </td>
                    <td id="subtitulo2"><input name="2color_cirel" type="checkbox" id="2color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['2color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_2color" value="<?php echo htmlentities($row_verificar_cirel['observacion_2color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>


                  <tr>
                    <td id="subtitulo1" colspan="2">3. 
                         <select name="insumo3" id="insumo3" class="busqueda selectsGrande" >
                            <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone3_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                         <?php  foreach($materiasss as $row_materia_prima ) { ?>
                             <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone3_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                         <?php } ?> 
                       </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone3_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                    </td>
                    <td id="subtitulo2"><input name="3color_cirel" type="checkbox" id="3color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['3color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_3color" value="<?php echo htmlentities($row_verificar_cirel['observacion_3color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>


                  <tr>
                    <td id="subtitulo1" colspan="2">4. 
                        <select name="insumo4" id="insumo4" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone4_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                        <?php  foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone4_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone4_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                     </td>
                    <td id="subtitulo2"><input name="4color_cirel" type="checkbox" id="4color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['4color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_4color" value="<?php echo htmlentities($row_verificar_cirel['observacion_4color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>
                  <tr>
                    <td id="subtitulo1" colspan="2">5. 
                        <select name="insumo5" id="insumo5" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone5_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                        <?php  foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone5_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone5_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                     </td>
                    <td id="subtitulo2"><input name="5color_cirel" type="checkbox" id="5color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['5color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_5color" value="<?php echo htmlentities($row_verificar_cirel['observacion_5color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>
                  <tr>
                    <td id="subtitulo1" colspan="2">6. 
                        <select name="insumo6" id="insumo6" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone6_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                        <?php  foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone6_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone6_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                      </td>
                    <td id="subtitulo2"><input name="6color_cirel" type="checkbox" id="6color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['6color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_6color" value="<?php echo htmlentities($row_verificar_cirel['observacion_6color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>
                  <tr>
                    <td id="subtitulo1" colspan="2">7. 
                        <select name="insumo7" id="insumo7" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone7_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                        <?php  foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone7_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone7_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                      </td>
                    <td id="subtitulo2"><input name="7color_cirel" type="checkbox" id="7color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['7color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_7color" value="<?php echo htmlentities($row_verificar_cirel['observacion_7color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>
                  <tr>
                    <td id="subtitulo1" colspan="2">8. 
                        <select name="insumo8" id="insumo8" class="busqueda selectsGrande" >
                        <option value=""<?php if (!(strcmp("", $row_ref_egp['pantone8_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option>
                        <?php  foreach($materiasss as $row_materia_prima ) { ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_ref_egp['pantone8_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
                          </option>
                        <?php } ?> 
                      </select>
                      <?php $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_ref_egp['pantone8_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; ?>
                      </td>
                    <td id="subtitulo2"><input name="8color_cirel" type="checkbox" id="8color_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['8color_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
                    <td id="subtitulo2"><input type="text" name="observacion_8color" value="<?php echo htmlentities($row_verificar_cirel['observacion_8color'], ENT_COMPAT, ''); ?>" size="50"></td>
                  </tr>
                  <tr>
                    <td colspan="4">&nbsp;</td>
                  </tr>
                  <tr>
                    <td id="fuente_1">MANGA (cm)</td>
                    <td id="fuente_2" colspan="2">
                     <select name="rodillo_cirel" id="rodillo_cirel">
                      <option value="0">SELECCIONE...</option>
                      <option value="40"<?php if (!(strcmp(40, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>40</option>
                      <option value="43"<?php if (!(strcmp(43, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>43</option>
                      <option value="46"<?php if (!(strcmp(46, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>46</option>
                      <option value="48"<?php if (!(strcmp(48, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>48</option>
                      <option value="50"<?php if (!(strcmp(50, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>50</option>
                      <option value="52"<?php if (!(strcmp(52, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>52</option>
                      <option value="54"<?php if (!(strcmp(54, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>54</option>
                      <option value="56"<?php if (!(strcmp(56, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>56</option>
                      <option value="58"<?php if (!(strcmp(58, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>58</option>
                      <option value="60"<?php if (!(strcmp(60, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>60</option>
                      <option value="64"<?php if (!(strcmp(64, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>64</option>
                      <option value="68"<?php if (!(strcmp(68, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>68</option>
                      <option value="72"<?php if (!(strcmp(72, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>72</option>
                      <option value="76"<?php if (!(strcmp(76, $row_verificar_cirel['rodillo_cirel']))) {echo "selected=\"selected\"";} ?>>76</option>        
                    </select> 
                  </td>
                  <td id="fuente_2"><input type="text" name="observacion_rodillo" value="<?php echo htmlentities($row_verificar_cirel['observacion_rodillo'], ENT_COMPAT, ''); ?>" size="50"></td>
                </tr>
                <tr>
                  <td id="fuente_1">PISTAS (cm)</td>
                  <td id="fuente_2" colspan="2">
                   <select name="pistas_cirel" id="pistas_cirel">
                    <option value="0">SELECCIONE...</option>
                    <option value="1"<?php if (!(strcmp(1, $row_verificar_cirel['pistas_cirel']))) {echo "selected=\"selected\"";} ?>>1</option>
                    <option value="2"<?php if (!(strcmp(2, $row_verificar_cirel['pistas_cirel']))) {echo "selected=\"selected\"";} ?>>2</option>
                    <option value="3"<?php if (!(strcmp(3, $row_verificar_cirel['pistas_cirel']))) {echo "selected=\"selected\"";} ?>>3</option>
                    <option value="4"<?php if (!(strcmp(4, $row_verificar_cirel['pistas_cirel']))) {echo "selected=\"selected\"";} ?>>4</option>
                    <option value="5"<?php if (!(strcmp(5, $row_verificar_cirel['pistas_cirel']))) {echo "selected=\"selected\"";} ?>>5</option>
                    <option value="6"<?php if (!(strcmp(6, $row_verificar_cirel['pistas_cirel']))) {echo "selected=\"selected\"";} ?>>6</option>        
                  </select>  
                </td>
                <td id="fuente_2"><input type="text" name="observacion_pistas" value="<?php echo htmlentities($row_verificar_cirel['observacion_pistas'], ENT_COMPAT, ''); ?>" size="50"></td>
              </tr>
              <tr>
                <td id="fuente_1">Repeticion (cm)     </td>
                <td id="fuente_2" colspan="2">
                 <select name="repeticion_cirel" id="repeticion_cirel">
                  <option value="0">SELECCIONE...</option>
                  <option value="1"<?php if (!(strcmp(1, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>1</option>
                  <option value="2"<?php if (!(strcmp(2, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>2</option>
                  <option value="3"<?php if (!(strcmp(3, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>3</option>
                  <option value="4"<?php if (!(strcmp(4, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>4</option>
                  <option value="5"<?php if (!(strcmp(5, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>5</option>
                  <option value="6"<?php if (!(strcmp(6, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>6</option>
                  <option value="7"<?php if (!(strcmp(7, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>7</option>
                  <option value="8"<?php if (!(strcmp(8, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>8</option>
                  <option value="9"<?php if (!(strcmp(9, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>9</option>
                  <option value="10"<?php if (!(strcmp(10, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>10</option>
                  <option value="11"<?php if (!(strcmp(11, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>11</option>
                  <option value="12"<?php if (!(strcmp(12, $row_verificar_cirel['repeticion_cirel']))) {echo "selected=\"selected\"";} ?>>12</option>       
                </select> 
              </td>
              <td id="fuente_2"><input type="text" name="observacion_repeticion" value="<?php echo htmlentities($row_verificar_cirel['observacion_repeticion'], ENT_COMPAT, ''); ?>" size="50"></td>
            </tr>
            <tr>
              <td id="fuente_1">Distancia entre Guias (mm)</td>
              <td id="fuente_2" colspan="2"><input name="distancia_logos_cirel" type="text" id="distancia_logos_cirel" value="<?php echo htmlentities($row_verificar_cirel['distancia_logos_cirel']); ?>" size="8" ></td>
              <td id="fuente_2"><input type="text" name="observacion_distancia_logos" value="<?php echo htmlentities($row_verificar_cirel['observacion_distancia_logos'], ENT_COMPAT, ''); ?>" size="50"></td>
            </tr>
  <!--   <tr>
      <td id="fuente_1">Concordancia de textos</td>
      <td id="fuente_2"><input type="checkbox" name="concuerda_texto_cirel" value="1" <?php if (!(strcmp($row_verificar_cirel['concuerda_texto_cirel'],1))) {echo "checked=\"checked\"";} ?>></td>
      <td id="fuente_2"><input type="text" name="observacion_concuerda_texto" value="<?php echo htmlentities($row_verificar_cirel['observacion_concuerda_texto'], ENT_COMPAT, ''); ?>" size="50"></td>
    </tr> -->
    <tr>
      <td colspan="4" id="fuente_1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><table id="tabla_formato">
        <tr>
          <td id="dato_2">FECHA DE ENTREGA</td>
          <td id="dato_2">REGISTRO INICIAL</td>
          <td id="dato_2">MODIFICACION</td>
        </tr>
        <tr>
          <td id="dato_2"><input name="fecha_entrega_cirel" type="date" id="fecha_entrega_cirel" value="<?php echo htmlentities($row_verificar_cirel['fecha_entrega_cirel'], ENT_COMPAT, ''); ?>" size="10" maxlength="10"></td>
          <td id="dato_2"><?php $registro=$row_verificar_cirel['registro_cirel'];
          if($registro == '') { ?>
            <input name="registro_cirel" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" maxlength="50" readonly>
            <input name="fecha_registro_cirel" type="text" value="<?php echo date("Y-m-d"); ?>" size="10" readonly>
          <?php } else { 
           echo $row_verificar_cirel['registro_cirel']." ".$row_verificar_cirel['fecha_registro_cirel']; ?>
           <input name="registro_cirel" type="hidden" value="<?php echo $row_verificar_cirel['registro_cirel']; ?>">
           <input name="fecha_registro_cirel" type="hidden" value="<?php echo $row_verificar_cirel['fecha_registro_cirel']; ?>">
           <?php } ?></td>
           <td id="dato_2">
            <?php if($registro != '') { ?>
              <input name="modificacion_cirel" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>">
              <input name="fecha_modificacion_cirel" type="hidden" value="<?php echo date("Y-m-d"); ?>">
              <?php echo $row_verificar_cirel['modificacion_cirel']." ".$row_verificar_cirel['fecha_modificacion_cirel']; ?>
            <?php } else { echo "- -" ;} ?>  
          </td>
        </tr>
        <tr>
          <td colspan="3" id="dato_2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" id="dato_2">&nbsp;</td>
        </tr> 
    </td>
    </tr>
    <tr>
      <td colspan="3" id="dato_2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" id="dato_1"><input type="submit" class="botonGeneral" value="Guardar"></td>
    </tr> 
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id_verif" value="<?php echo $row_verificar_cirel['id_verif']; ?>">
  <input type="hidden" name="id_ref_verif" value="<?php echo $row_ref_egp['id_ref']; ?>">
</form>


<div class="container-fluid"> 
<form id="formPlanchas" name="formPlanchas" action="guardar.php" method="post">
   <hr>
   <h2 id="dato2"><strong>REPORTE DE PLANCHAS MALAS</strong></h2>
     <input name="ref" id="ref" value="<?php echo $row_ref_egp['cod_ref'];?>" type="text" placeholder="ref" style="width: 100px;">&nbsp;&nbsp;
     <input name="cliente" id="cliente" value="" type="text" placeholder="cliente" style="width: 100px;">&nbsp;&nbsp;
     <input name="color1" id="color1" value="" type="text" placeholder="color1" style="width: 100px;">&nbsp;&nbsp;
     <select name="motivo" id="motivo" class="selectsMini" style="width: 90px;">
       <option value="">MOTIVO</option> 
       <option value="USO">USO</option> 
       <option value="DAÑO">DAÑO</option>
     </select>&nbsp;&nbsp;
     <input name="color2" id="color2" value="" type="text" placeholder="color2" style="width: 100px;">&nbsp;&nbsp;
     <select name="motivo2" id="motivo2" class="selectsMini" style="width: 90px;">
       <option value="">MOTIVO</option> 
       <option value="USO">USO</option> 
       <option value="DAÑO">DAÑO</option>
     </select>&nbsp;&nbsp;
     <input name="color3" id="color3" value="" type="text" placeholder="color3" style="width: 100px;">&nbsp;&nbsp;
     <select name="motivo3" id="motivo3" class="selectsMini" style="width: 90px;"> 
       <option value="">MOTIVO</option>
       <option value="USO">USO</option> 
       <option value="DAÑO">DAÑO</option>
     </select>&nbsp;&nbsp;
     <input name="color4" id="color4" value="" type="text" placeholder="color4" style="width: 100px;">&nbsp;&nbsp; 
     <select name="motivo4" id="motivo4" class="selectsMini" style="width: 90px;"> 
      <option value="">MOTIVO</option>
       <option value="USO">USO</option> 
       <option value="DAÑO">DAÑO</option>
     </select>FECHA REPORTE
     <input name="fecha_reporte" id="fecha_reporte" value="<?php echo date('Y-m-d'); ?>" type="date" placeholder="fecha_reporte" style="width: 120px;">&nbsp;&nbsp;
     <input name="se_hizo_repo" id="se_hizo_repo" value="" type="text" placeholder="se hizo reposicion" style="width: 100px;">&nbsp;&nbsp;
     <input name="fecha_repo" id="fecha_repo" value="" type="date" placeholder="fecha_repo" style="width: 120px;">&nbsp;&nbsp;
     <input name="responsable" id="responsable" value="<?php echo $_SESSION['Usuario']; ?>" type="text" placeholder="responsable" style="width: 120px;">&nbsp;&nbsp;  
     <!-- <button id='botondeenvio' type="submit" onclick="validarTodos(); return false;"><img type="image" style="width: 20px; height: 20px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR"></button> -->
     <button id="btnEnviarItems" name="btnEnviarItems" type="button" autofocus="" ><img type="image" style="width: 20px; height: 20px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR"></button>
     <input name="id_verif_p" id="id_verif_p" value="<?php echo $row_verificar_cirel['id_verif']; ?>" type="hidden" > 
     <input name="verificacion" id="verificacion" value="verificacion" type="hidden" >
   </form>
     <p></p>
     <em style="display: none;  align-items: center; justify-content: center;color: red; " id="busqueda" ></em> 
     <em style="display: none;  align-items: center; justify-content: center;color: red; " id="AlertItem" ></em> 
     <table id="example" class="display" style="width:100%" border="1">
            <thead>
              <tr> 
                <th id="fuente_1" style="width: 100px;"><strong> CLIENTE</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> REFERENCIA</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> COLOR1</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> MOTIVO</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> COLOR2</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> MOTIVO</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> COLOR3</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> MOTIVO</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> COLOR4</strong> </th>
                <th id="fuente_1" style="width: 100px;"><strong> MOTIVO</strong> </th>
                <th id="fuente_1" style="width: 150px;"><strong> FECHA REPORTE</strong> </th>
                <th id="fuente_1" style="width: 200px;"><strong> SE HIZO REPOSICION</strong> </th>
                <th id="fuente_1" style="width: 150px;"><strong> FECHA REPOSICION</strong> </th>
                <th id="fuente_1" style="width: 150px;"><strong> RESPONSABLE</strong> </th>
                <th id="fuente_1" style="width: 150px;"><strong> DELETE</strong> </th>
                <th id="fuente_1" style="width: 150px;"><strong> VISTA</strong> </th>

              </tr>
            </thead>
           
            <tbody id="DataResultcirel"> 
              
            </tbody>
         
          </table>  
 
</div>

</table>

</div> <!-- contenedor -->

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
<script>
     $(document).ready(function(){
      var cod_ref = "<?php echo $row_ref_egp['cod_ref']==''? $row_verif_ref_egp['cod_ref']:$row_verif_ref_egp['cod_ref']; ?>"
      consultasPlanchas(cod_ref);//despliega los items

      var cliente = "<?php echo $resultVERIF['nombre_c'];?>";
      $("#cliente").val(cliente);
    }); 
    $( "#btnEnviarItems" ).on( "click", function() {
     if($("#color1").val()=='' ){
       swal("Alerta"," Ingrese Color 1","warning")
          return false;
     }
     if($("#color1").val()!='' && $("#motivo").val()==''){
       swal("Alerta"," Ingrese Motivo 1","warning")
          return false;
     }
     if($("#color2").val()!='' && $("#motivo2").val()==''){
       swal("Alerta"," Ingrese Motivo 2","warning")
          return false;
     }
     if($("#color3").val()!='' && $("#motivo3").val()==''){
       swal("Alerta"," Ingrese Motivo 3","warning")
          return false;
     }
     if($("#color4").val()!='' && $("#motivo4").val()==''){
       swal("Alerta"," Ingrese Motivo 4","warning")
          return false;
     }else{ 
      guardarItemsCireles();
    }

   

  });


    $(document).ready(function(){
      var editar =  "<?php echo $_SESSION['no_edita'];?>";//es una excepcion
      var usuario_especifico =  "<?php echo $_SESSION['id_usuario'];?>";//es una excepcion$_SESSION['Usuario']
      //excepcion para el de planchas
      if(editar==0  || (usuario_especifico!='75' && usuario_especifico!='23' ) )//es una excepcion 23 sistemas, 75 planchas
      {
 
         
        $("input").attr('disabled','disabled');
        $("textarea").attr('disabled','disabled');
        $("select").attr('disabled','disabled');
        $("button").attr('disabled','disabled');

        $('a').each(function() { 
          $(this).attr('href', '#');
        }); 
                 swal("No Autorizado", "Sin permisos para editar :)", "error"); 
      }
    });


     //FILTROS
    

    $(document).ready(function(){  
          $('.insumos').select2({ 
              ajax: {
                  url: "select3/proceso.php",
                  type: "post",
                  dataType: 'json',
                  delay: 250,
                  data: function (params) {
                      return {
                          palabraClave: params.term, // search term
                          var1:"id_insumo,descripcion_insumo",
                          var2:"insumo",
                          var3:"",
                          var4:"ORDER BY descripcion_insumo ASC",
                          var5:"id_insumo",
                          var6:"descripcion_insumo"
                      };
                  },
                  processResults: function (response) {
                      return {
                          results: response
                      };
                  },
                  cache: true
              }
          });
     
     });

</script> 
<?php
mysql_free_result($verificar_cirel);

mysql_free_result($verif_ref_egp);

mysql_free_result($usuario);
?>
