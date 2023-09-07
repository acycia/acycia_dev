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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_numeracion = 20;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;


/*mysql_select_db($database_conexion1, $conexion1);
$query_op = "SELECT id_op,int_cod_ref_op,str_numero_oc_op FROM Tbl_orden_produccion WHERE b_estado_op > '0' ORDER BY id_op DESC";
$op = mysql_query($query_op, $conexion1) or die(mysql_error());
$row_op = mysql_fetch_assoc($op);
$totalRows_op = mysql_num_rows($op);*/

//Filtra codigo, mes, dia llenos
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
$cajaini = trim($_GET['cajaini']);
$cajafin = trim($_GET['cajafin']);
if($id_op!= '' && $cajaini=='' && $cajafin=='')
{
/*$query_numeracion = "SELECT int_op_tn,int_cod_rev_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id_op' AND id_despacho IS NULL group BY int_op_tn, int_caja_tn, int_paquete_tn  ASC";*/

//$registros = $conexion->llenaListas("tbl_tiquete_numeracion", " WHERE int_op_tn='".$id_op."'  AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","id_tn,int_op_tn,ref_tn,int_caja_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,pesot,int_paquete_tn,int_caja_tn,id_tn"  );

$registros = $conexion->llenaListas("tbl_tiquete_numeracion"," WHERE int_op_tn='".$id_op."' AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","id_tn,int_op_tn,ref_tn,int_caja_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,pesot,int_paquete_tn,int_caja_tn,int_undxpaq_tn,imprime" );

}
if($id_op!= '' && $cajaini!='' && $cajafin!='')
{
/*$query_numeracion = "SELECT int_op_tn,int_cod_rev_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id_op' and  int_caja_tn  between  $cajaini  AND $cajafin  AND id_despacho IS NULL group BY int_op_tn, int_caja_tn, int_paquete_tn  ASC";*/


/*$registros = $conexion->llenaListas("tbl_tiquete_numeracion tn"," LEFT JOIN tbl_faltantes tf ON tn.int_op_tn=tf.id_op_f WHERE tn.int_op_tn='$id_op' and  tn.int_caja_tn  BETWEEN  '$cajaini'  AND '$cajafin'  AND tn.id_despacho IS NULL ","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC"," * " );*/

$registros = $conexion->llenaListas("tbl_tiquete_numeracion"," WHERE int_op_tn='".$id_op."' and  int_caja_tn  BETWEEN  '".$cajaini."'  AND '".$cajafin."'  AND id_despacho IS NULL","GROUP BY int_op_tn, int_caja_tn, int_paquete_tn  ASC","id_tn,int_op_tn,ref_tn,int_caja_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,pesot,int_paquete_tn,int_caja_tn,int_undxpaq_tn,imprime" );
} 
/*$numeracion = mysql_query($query_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);*/

if (isset($_GET['totalRows_numeracion'])) {
  $totalRows_numeracion = $_GET['totalRows_numeracion'];
} else {
  $all_numeracion = mysql_query($query_numeracion);
  $totalRows_numeracion = mysql_num_rows($all_numeracion);
}
$totalPages_numeracion = ceil($totalRows_numeracion/$maxRows_numeracion)-1;

$queryString_numeracion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_numeracion") == false && 
        stristr($param, "totalRows_numeracion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_numeracion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_numeracion = sprintf("&totalRows_numeracion=%d%s", $totalRows_numeracion, $queryString_numeracion);



$row_op = $conexion->llenaSelect('Tbl_orden_produccion',"","ORDER BY id_op DESC"); //WHERE b_estado_op > '0' 


$row_info_op = $conexion->llenarCampos('tbl_orden_produccion', "WHERE id_op='".$id_op."' ", "","charfin" );

/*  $insertGoTo = "despacho_faltantes2.php?id_op=" . $_POST['id_op'] . "cajaini=". $_POST['cajaini'] . "cajafin=" . $_POST['cajafin'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 
 */
//session_start();
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/formato.js"></script>


  <!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

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

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script type="text/javascript">
 
$(document).ready(function(){
 
$("#btenviar").click(function(){
   if($("#id_op").val()==''){
    alert('op no debe estar vacio');
   } 
});
    
});
 
</script>
</head>
<body>
  <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script> 


<?php echo $conexion->header('listas'); ?>
<form action="despacho_faltantes2.php" method="get" name="form1" >
<table class="table table-bordered table-sm">
<tr>
<td colspan="5" id="titulo2">DESPACHO FALTANTES</td>
</tr>
<tr>
  <td colspan="5" id="titulo2">REF/O.C/O.P
    <select class="busqueda selectsGrande" name="id_op" id="id_op"  required="required" >
       <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione REF / O.C / O.P</option>
       <?php  foreach($row_op as $row_op ) { ?>
        <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['int_cod_ref_op']." / O.C: ".$row_op['str_numero_oc_op']." / O.P: ".$row_op['id_op']?></option>
    <?php } ?>  
    </select>

    <!-- <select name="id_op" id="id_op"  required="required"> 
      <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione REF / O.C / O.P</option>
      <?php
do {  
?>
      <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['int_cod_ref_op']." / O.C: ".$row_op['str_numero_oc_op']." / O.P: ".$row_op['id_op']?></option>
      <?php
} while ($row_op = mysql_fetch_assoc($op));
  $rows = mysql_num_rows($op);
  if($rows > 0) {
      mysql_data_seek($op, 0);
	  $row_op = mysql_fetch_assoc($op);
  }
?>
    </select> --><br>

 </td>
</tr>
<tr>
 <td style="text-align:center;" >
      <input type="number" class="form-control negro_inteso" placeholder="CAJA INICIAL" name="cajaini" id="cajaini" max="100000" value="<?php echo $_GET['cajaini'];?>" >  
  </td>
   <td id="titulo2" colspan="4">
      <input type="number" class="form-control negro_inteso" placeholder="CAJA FINAL" name="cajafin"  id="cajafin" max="100000" value="<?php echo $_GET['cajafin'];?>" > 
  
  </td>
 </tr> 
 <tr>
   <td colspan="5" id="titulo3" >
    <input type="submit" name="Submit" value="FILTRO" id="btenviar" class="botonGMini"/> <input type="button" id="excel" name="excel" value="Descarga Excel" onclick="myFunction()" class="botonDel"> 
   </td>
 </tr> 
<tr>
  <td colspan="5" id="titulo4"> 
     <b> Importante filtrar por cajas para no agotar memoria del php </b>
   </td>
</tr>
 <tr> 
    <td colspan="2"><!-- <input type="button" id="excel" name="excel" value="Descarga Excel" onclick="myFunction()"></td>
    <td colspan="2" id="dato3"> --></td>
    </tr> 
</table>
</form>
<form name="seleccion" action="despacho_faltantes2.php" method="POST">
<!--  <div style="height:400px;width:720px;overflow:scroll;"> -->
<div style="height:600px;width:990px;overflow:scroll;">
  <table class="table">
 
     <tr id="tr1">
              <td id="titulo4">N. O.P</td> 
              <td id="titulo4">REF</td> 
              <td id="titulo4">N. CAJA</td> 
              <td id="titulo4">UND X CAJA</td> 
              <td nowrap="nowrap" id="titulo4">DESDE</td> 
              <td nowrap="nowrap" id="titulo4">HASTA</td> 
              <td id="titulo4">PESO</td> 
              <td id="titulo4">N. PAQUETE</td> 
              <td id="titulo4">UND X PAQ</td> 
              <td nowrap="nowrap" id="titulo4">DESDE</td> 
              <td nowrap="nowrap" id="titulo4">HASTA</td> 
              <td id="titulo4">FALTANTES X PAQ</td> 
              <td nowrap="nowrap" id="titulo4">ERROR FALTAN</td> 
      </tr>
          <?php foreach($registros as $row_numeracion) {  ?>
          <?php 
             $opF=$row_numeracion['int_op_tn'];
            $paqF=$row_numeracion['int_paquete_tn'];
            $cajaF=$row_numeracion['int_caja_tn'];
            $idtnF=$row_numeracion['id_tn'];
           
             $query_vista_faltantes="SELECT id_f,int_inicial_f,int_final_f,int_total_f  FROM  Tbl_faltantes WHERE  Tbl_faltantes.id_op_f= '$opF'  AND  Tbl_faltantes.int_paquete_f='$paqF' AND  Tbl_faltantes.int_caja_f='$cajaF' ORDER BY Tbl_faltantes.int_inicial_f ASC"; 
              $vista_faltantes = mysql_query($query_vista_faltantes, $conexion1) or die(mysql_error());
              $row_vista_faltantes = mysql_fetch_assoc($vista_faltantes);
              $totalRows_vista_faltantes = mysql_num_rows($vista_faltantes);  

              
              ?> 
             <?php  do { 
               //foreach($registros_faltantes as $row_vista_faltantes) { 

             ?>
            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">

            <td id="dato2"><?php echo $row_numeracion['int_op_tn'];?></td>
            <td id="dato2"><?php echo $row_numeracion['ref_tn']; ?></td> 
            <td id="dato2"> 
              <a href="javascript:popUp('sellado_control_cajas_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','1200','800')" target="_top"><?php echo $row_numeracion['int_caja_tn']; ?></a> 
            </td> 
            <td id="dato2"><?php echo $row_numeracion['int_undxcaja_tn']; ?></td> 
            <td nowrap="nowrap" id="dato2"><?php echo $row_numeracion['int_desde_tn']; ?><?php echo $row_info_op['charfin'];?></td> 
            <td nowrap="nowrap" id="dato2"><?php echo $row_numeracion['int_hasta_tn']; ?><?php echo $row_info_op['charfin'];?></td> 
            <td id="dato2" nowrap="nowrap"><?php echo $row_numeracion['pesot']; ?></td> 
            <td id="dato2">
            <?php  

/*            $opF=$row_numeracion['int_op_tn'];
            $paqF=$row_numeracion['int_paquete_tn'];
            $cajaF=$row_numeracion['int_caja_tn'];
            $idtnF=$row_numeracion['id_tn'];
 
            $sqlfal="SELECT * FROM Tbl_faltantes WHERE id_tn_f=$idtnF  ORDER BY int_inicial_f ASC";  
            $resultfal=mysql_query($sqlfal); 
            $row_vista_faltantes=mysql_fetch_assoc($resultfal); */

            //if($row_vista_faltantes >= '1') {   
              if($row_vista_faltantes['int_inicial_f']  > '0')  {
              ?> 
              <a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_numeracion['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','400','300')" class="rojo_peq" target="_top"><?php echo $row_numeracion['int_paquete_tn']; ?></a>

              <!-- <a href="javascript:popUp('despacho_paquete_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_numeracion['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','400','300')" class="rojo_peq" target="_top"><?php echo $paqF; ?></a> -->
            <?php } else { ?> 

              <?php if($row_numeracion['imprime']==1): ?>
               <a href="javascript:popUp('sellado_totaltiqxcaja_colas.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','1200','800')" target="_top"><?php echo $row_numeracion['int_paquete_tn']; ?></a>
             <?php else: ?>  
              <a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_numeracion['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','600','400')" target="_top"><?php echo $row_numeracion['int_paquete_tn']; ?></a> 
            <?php endif; ?>  

            <!-- <a href="javascript:popUp('despacho_paquete_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_numeracion['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','400','300')" target="_top"><?php echo $paqF; ?></a>    -->

          <?php }  ?>

        </td> 
            <td id="dato2"><?php echo $row_numeracion['int_undxpaq_tn']; ?></td> 

             
            <td nowrap="nowrap" id="dato2">    
                  <?php echo $row_vista_faltantes['int_inicial_f']; ?><?php if($row_vista_faltantes['int_inicial_f']!=''){echo $row_info_op['charfin']  ;} ?>

              </td> 
              <td nowrap="nowrap" id="dato2">    
                  <?php echo  $row_vista_faltantes['int_final_f']; ?><?php if($row_vista_faltantes['int_final_f']!=''){echo $row_info_op['charfin']  ;} ?>

              </td> 
               <td id="dato2"><?php echo  $row_vista_faltantes['int_total_f'];?></td> 

               <td id="dato2">
                <span style="color: red;" id="Error<?php echo $row_numeracion['int_desde_tn'];?>"></span>

                <?php  
                    $query_error="SELECT sum(int_total_f) as sumat FROM  Tbl_faltantes WHERE id_op_f=$opF AND int_paquete_f=$paqF AND int_caja_f=$cajaF group by int_paquete_f "; 
                    $vista_error = mysql_query($query_error, $conexion1) or die(mysql_error());
                    $row_error = mysql_fetch_assoc($vista_error);
                ?>
                 <script type="text/javascript">
                   numDesde = "<?php echo $row_numeracion['int_desde_tn'];?>"; 
                   numHasta = "<?php echo $row_numeracion['int_hasta_tn'];?>";   
                   undP = "<?php echo $row_numeracion['int_undxpaq_tn'];?>"; 
                   paqF = "<?php echo $row_numeracion['int_paquete_tn'];?>"; 

                   sumat = "<?php echo $row_error['sumat'];?>"; 

                  
                         sumat = ( sumat == "" || sumat == "NaN" ) ? 0 : sumat; 
                  
                    if(numDesde!=''){
                            numeracionDesdeF(numDesde,numHasta,undP,paqF,sumat);
                     }

                    function numeracionDesdeF(numDesde,numHasta,undP,paqF,sumat) {
               
                     var dividida1 = numeracionCharF(numDesde); 
                     var numerosD = dividida1[0];
                     var cadena = dividida1[1];

                     var dividida2 = numeracionCharF(numHasta); 
                     var numerosH = dividida2[0];
                     var cadena2 = dividida2[1];

                     var result=parseInt(numerosH) - parseInt(numerosD);
                     var RangoT =parseInt(result) + parseInt(1); 
                    
                      var TotalundP = parseInt(undP) + parseInt(sumat) ;

                      var conteo = numDesde;
                      
  
                    if(RangoT > TotalundP){
                        $("#Error"+conteo).text('Error: '+paqF);
                           //alert('Paque: '+ paqF+' RangoT: '+RangoT + ' sumat: ' +TotalundP);
 
                        } 
                    }


                 </script>
                  
                </td> 
                 <script type="text/javascript">
                    

                   //FUNCION GENERAL AUXILIARES FALTANTES DE TIQUETES SELLADO  
                   function numeracionCharF(carac){
                     var num="",caden="",l="",b="",c="",d="",e="",g="",h="",desde="", sal="",sal2="",cadena="";
                       var caract =carac.toUpperCase().replace(/\s/g,'');//a mayusculas,reemplaza espacios   
                       var z=(caract.search(/AA1Y|AA1F|AA1G|AA1H|AA1I|AA1J|AA1K|AA1L|AA1M|AA1N|AA1O|AA1P|AA1Q|AA1R|AA1S|AA1T|AA1U|AA1V|AA1W|AA1X|AA1Z|AA1A|AA1B|AA1C|AA1D|AA1E|AA2Y|AA2F|AA2G|AA2H|AA2I|AA2J|AA2K|AA2L|AA2M|AA2N|AA2O|AA2P|AA2Q|AA2R|AA2S|AA2T|AA2U|AA2V|AA2W|AA2X|AA2Z|AA2A|AA2B|AA2C|AA2D|AA2E|AA3Y|AA3F|AA3G|AA3H|AA3I|AA3J|AA3K|AA3L|AA3M|AA3N|AA3O|AA3P|AA3Q|AA3R|AA3S|AA3T|AA3U|AA3V|AA3W|AA3X|AA3Z|AA3A|AA3B|AA3C|AA3D|AA3E|AA4Y|AA4F|AA4G|AA4H|AA4I|AA4J|AA4K|AA4L|AA4M|AA4N|AA4O|AA4P|AA4Q|AA4R|AA4S|AA4T|AA4U|AA4V|AA4W|AA4X|AA4Z|AA4A|AA4B|AA4C|AA4D|AA4E|AA5Y|AA5F|AA5G|AA5H|AA5I|AA5J|AA5K|AA5L|AA5M|AA5N|AA5O|AA5P|AA5Q|AA5R|AA5S|AA5T|AA5U|AA5V|AA5W|AA5X|AA5Z|AA5A|AA5B|AA5C|AA5D|AA5E|AA6Y|AA6F|AA6G|AA6H|AA6I|AA6J|AA6K|AA6L|AA6M|AA6N|AA6O|AA6P|AA6Q|AA6R|AA6S|AA6T|AA6U|AA6V|AA6W|AA6X|AA6Z|AA6A|AA6B|AA6C|AA6D|AA6E|AA7Y|AA7F|AA7G|AA7H|AA7I|AA7J|AA7K|AA7L|AA7M|AA7N|AA7O|AA7P|AA7Q|AA7R|AA7S|AA7T|AA7U|AA7V|AA7W|AA7X|AA7Z|AA7A|AA7B|AA7C|AA7D|AA7E|AA8Y|AA8F|AA8G|AA8H|AA8I|AA8J|AA8K|AA8L|AA8M|AA8N|AA8O|AA8P|AA8Q|AA8R|AA8S|AA8T|AA8U|AA8V|AA8W|AA8X|AA8Z|AA8A|AA8B|AA8C|AA8D|AA8E|AA9Y|AA9F|AA9G|AA9H|AA9I|AA9J|AA9K|AA9L|AA9M|AA9N|AA9O|AA9P|AA9Q|AA9R|AA9S|AA9T|AA9U|AA9V|AA9W|AA9X|AA9Z|AA9A|AA9B|AA9C|AA9D|AA9E/i));
                    
                           codigo1 = buscaDigitos(caract);//busca 5 fecha 
                           var n=(caract.search(/\d+/g));//d solo numeros
                           var l=(caract.search(/\w+/g));//w alfanumericos

                      if(codigo1!= undefined ){
                            var codigo = caract.split("-");//hasta el guion   
                            var data = codigo[0];
                            var num = codigo[1];
                            cadena=data+"-";
                            solonumeros=num;  
                            return [  solonumeros, cadena ];    
                        }else 
                        if(z=='0'){
                         var v = caract; 
                         var data = v.substring(0,4);
                         var num = v.substring(4); 
                         cadena=data;
                         solonumeros=num;  
                         return [  solonumeros, cadena ];    
                       }else
                       //------------------SOLO NUMEROS---------------//   
                       var n=(caract.search(/\d+/g));//d solo numeros
                       if( n=='0'){      
                         var v = caract; 
                         var num = v.substring(0);
                         var vacia="";  
                         solonumeros=num; 
                         return [ solonumeros, vacia ];    
                       }else
                       //------------------LETRAS AL INICIO-----------// 
                       var l=(caract.search(/\w+/g));//w alfanumericos
                       if(l=='0' && z!='0'&& n!='0'){
                       //caract.match(/\d+/g).join('');
                       l=caract.match(/\D+/g); //D acepta diferente de numeros
                       cadena=l;
                       num=caract.match(/\d+/g); //d acepta solo numeros
                       solonumeros=num;
                       return [ solonumeros, cadena ];       
                       }//fin if
                   }
                   
 

    function buscaDigitos(caract){
     
        var codigo = caract.split("-");//hasta el guion
        if(codigo[1]){
 
            return codigo;  
 
        }
    
    }
                 </script>
               
            </tr>
                  <?php  } while ($row_vista_faltantes = mysql_fetch_assoc($vista_faltantes));    ?>
             <?php } ?>
</table>
</div>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, 0, $queryString_numeracion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, max(0, $pageNum_numeracion - 1), $queryString_numeracion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, min($totalPages_numeracion, $pageNum_numeracion + 1), $queryString_numeracion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, $totalPages_numeracion, $queryString_numeracion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<script>
function myFunction() { 
    var id_op = document.getElementById("id_op").value; 
    var cajaini = document.getElementById("cajaini").value; 
    var cajafin = document.getElementById("cajafin").value; 
 
 window.location.href = "despacho_faltante_excel.php?id_op="+id_op+"&cajaini="+cajaini+"&cajafin="+cajafin;
}

function despacho_faltantes_op(selec)
{
  var id_op = document.getElementById("id_op").value; 
  var cajaini = document.getElementById("cajaini").value; 
  var cajafin = document.getElementById("cajafin").value; 
window.location.href ='despacho_faltantes2.php?id_op='+id_op+"&cajaini="+cajaini+"&cajafin="+cajafin;
}
  
</script>
<script>
 
 $('#id_op').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"id_op,int_cod_ref_op,str_numero_oc_op",//campo normal para usar
                    var2:"tbl_orden_produccion",//tabla
                    var3:" b_estado_op > '0' ",//where
                    var4:"ORDER BY id_op DESC",
                    var5:"id_op",//clave
                    var6:"int_cod_ref_op,str_numero_oc_op,id_op"//columna a buscar
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

 
</script>
<?php
mysql_free_result($usuario);

mysql_free_result($numeracion);

mysql_free_result($op); 

//mysql_free_result($caja_num);
?>