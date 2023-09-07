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
$conexion = new ApptivaDB();

$currentPage = $_SERVER["PHP_SELF"];

$row_ano = $conexion->llenaSelect('anual','','ORDER BY anual DESC'); 

$row_mensual = $conexion->llenaSelect('mensual','','ORDER BY id_mensual DESC');

$row_dia = $conexion->llenaSelect('dias','','ORDER BY dia DESC');

$row_vendedores = $conexion->llenaSelect('vendedor','','ORDER BY nombre_vendedor ASC');
 
//$row_numero = $conexion->llenaSelect('tbl_remisiones',"WHERE b_borrado_r='0' AND fecha_r > '2019-01-01' ",'ORDER BY int_remision DESC'); 

//$row_orden =$conexion->llenaListas('tbl_remisiones',"WHERE fecha_r > '2019-01-01' ",'ORDER BY str_numero_oc_r DESC','DISTINCT str_numero_oc_r');

//$row_ref = $conexion->llenaListas('tbl_referencia',"",'ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC','cod_ref');  

//$row_cliente = $conexion->llenaSelect('cliente',"",'ORDER BY nombre_c ASC'); 


$maxRows_registros = 20;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

 
if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $all_registros = mysql_query($query_registros);
  $totalRows_registros = mysql_num_rows($all_registros);
}
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;

$registros = $conexion->buscarListar("tbl_remisiones","*","ORDER BY int_remision DESC","",$maxRows_registros,$pageNum_registros,"where b_borrado_r='0'" );
 

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('tbl_remisiones'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;
 
$queryString_registros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_registros") == false && 
        stristr($param, "totalRows_registros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_registros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);




$row_alertas_rojo = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones',"WHERE tbl_orden_compra.str_numero_oc = tbl_remisiones.str_numero_oc_r and (tbl_remisiones.comprobante_file is  null or tbl_remisiones.comprobante_file ='')   and tbl_orden_compra.b_estado_oc in('3','4') and tbl_orden_compra.comprobante_ent='SI'" ,'ORDER BY CONVERT(tbl_orden_compra.str_numero_oc, SIGNED INTEGER) ASC',"DISTINCT tbl_remisiones.int_remision, tbl_remisiones.str_numero_oc_r, tbl_remisiones.comprobante_file ");//and tbl_orden_compra.entrega_fac='NO'

$row_alertas_verde = $conexion->llenaListas('tbl_orden_compra,tbl_remisiones',"WHERE tbl_orden_compra.str_numero_oc = tbl_remisiones.str_numero_oc_r and (tbl_remisiones.comprobante_file is NOT null or tbl_remisiones.comprobante_file ='')  and tbl_orden_compra.b_estado_oc in('3','4') " ,'ORDER BY CONVERT(tbl_orden_compra.str_numero_oc, SIGNED INTEGER) ASC',"DISTINCT tbl_remisiones.int_remision, tbl_remisiones.str_numero_oc_r, tbl_remisiones.comprobante_file ");//and tbl_orden_compra.entrega_fac='NO' 

?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>

  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/updates.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
 
 <!-- desde aqui para listados nuevos -->
 <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
 <link rel="stylesheet" type="text/css" href="css/general.css"/>

 <!-- jquery -->
 <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
 <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
 <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
 <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

 <!-- sweetalert -->
 <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
 <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
 <!-- select2 -->
<!--  <link href="select2/css/select2.min.css" rel="stylesheet"/>
 <script src="select2/js/select2.min.js"></script>
 -->

 <!-- Select3 Nuevo -->
 <meta charset="UTF-8">
 <!-- jQuery -->
 <script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

 <!-- select2 css -->
 <link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

 <!-- select2 script -->
 <script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
 <!-- Styles -->
 <link rel="stylesheet" href="select3/assets/css/style.css">
 <!-- Fin Select3 Nuevo -->

</head>
<body>
  <script>
       /*$(document).ready(function() { $(".combos").select2({ minimumInputLength: 3;}); });*/ 
  </script>

  
  <div align="center">
    <table style="width: 80%"> 
      <tr>
       <td align="center">
         <div class="row-fluid">
           <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
             <div class="panel panel-primary">
              <div class="panel-heading" align="left" ></div><!--color azul-->
              <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
              </div>
              <div class="panel-heading" align="left" ></div><!--color azul-->
               <div id="cabezamenu">
                <ul id="menuhorizontal">
                  <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                  <li><a href="orden_compra_cl2.php">LISTADO O.C</a></li>
                </ul>
             </div> 
             <div class="panel-body">
               <br> 
               <div class="container">
                <div class="row">
                  <div class="span12"> 
             </div>
           </div>

  
              <form action="despacho_listado2_oc.php" method="get" name="consulta">
                <table >
                  <tr>
                    <td id="titulo2">REMISIONES</td>
                  </tr>
                  <tr>
                    <td id="fuente2">
                      <div class="row">

                        <div class="main"> 
                           <select id='int_remision' name='int_remision'  style='width: 200px;'>
                             <option value='0'<?php if (!(strcmp(0, $_GET['int_remision']))) {echo "selected=\"selected\"";} ?>>- Buscar Remision -</option>
                           </select>
                         </div>

                         <div class="main"> 
                           <select id='str_numero' name='str_numero'  style='width: 200px;'>
                             <option value='0'<?php if (!(strcmp(0, $_GET['str_numero']))) {echo "selected=\"selected\"";} ?>>- Buscar O.C. -</option>
                           </select>
                         </div>

                         <div class="main"> 
                           <select id='cod_ref' name='cod_ref'  style='width: 200px;'>
                             <option value='0'<?php if (!(strcmp(0, $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>- Buscar Ref. -</option>
                           </select>
                         </div>

                         <div class="main"> 
                           <select id='id_c' name='id_c'  style='width: 200px;'>
                             <option value='0'<?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>- Buscar Cliente -</option>
                           </select>
                         </div>

                       <select  name="estado_oc" id="estado_oc" style="width:180px">
                        <option value="0">ESTADO O.C</option>
                        <option value="3">REMISIONADA</option>
                        <option value="4">FAC.PARCIAL</option>
                        <option value="5">FAC.TOTAL</option>
                        <option value="6">MUESTRAS REPOSICION</option>
                      </select> 

                      <select  name="estado_rd" id="estado_rd" style="width:120px">
                        <option value="0" selected>Despachado</option>
                        <option value="1">Pendiente</option>
                      </select>

                      <select  name="fecha" id="fecha" style="width:120px">
                        <option value="0">ANUAL</option>
                        <?php foreach ($row_ano as $row_ano) { ?>
                          <option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option> 
                        <?php } ?>
                      </select>

                      <select  name="mensual" id="mensual" style="width:120px">
                        <option value="0">MENSUAL</option>
                        <?php foreach ($row_mensual as $row_mensual) { ?>
                          <option value="<?php echo $row_mensual['id_mensual']?>"><?php echo $row_mensual['mensual']?></option>
                        <?php  } ?>
                      </select> 

                      <!--dias -->
                      <select name="dia" id="dia" style="width:60px">
                        <option value="0">DIA</option>
                        <?php foreach ($row_dia as $row_dia) { ?>
                          <option value="<?php echo $row_dia['dia']?>"><?php echo $row_dia['dia']?></option>
                        <?php } ?>
                      </select>

                      <select  name="vende" id="vende" class="selectsMini" >
                        <option value="0">Vendedor</option>
                        <?php  foreach($row_vendedores as $row_vendedores ) { ?>
                          <option value="<?php echo $row_vendedores['id_vendedor']; ?>"<?php if (!(strcmp($row_vendedores['nombre_vendedor'], $_GET['vende']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_vendedores['nombre_vendedor']); ?> </option>
                        <?php } ?>
                      </select> 


                    </div>
                    <br>    
                    <input type="submit" class="botonGMini" style='width:90px; height:25px' name="Submit" value="FILTRO" /> 
                    <input type="button" class="botonDel" style='width:140px; height:25px' id="excel" name="excel" value="Descarga Excel" onclick="myFunction()">  <br><br>
                  </td> 
                </tr>
              </table>
            </form>
          </div>
        </div>
            <form action="delete_listado.php" method="get" name="seleccion">
              <table id="tabla1">
                <tr>
                  <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="23" />
                    <input name="Input" type="submit" value="Delete"/>  </td>
                    <td colspan="2"><?php if (isset($_GET['id'])) {$id= $_GET['id'];}else{$id= '';} 
                    if($id >= '1') { ?> 
                    <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                    <?php }
                    if($id == '0') { ?>
                    <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>      <?php }?>
                    </td>
                    <td colspan="2" id="dato3">
                      <a href="orden_compra_ingresos.php"><img src="images/c.gif" style="cursor:hand;" alt="COMPRAS" title="COMPRAS" border="0" /></a>
                      <a href="despacho_faltantes.php"><img src="images/f.gif" style="cursor:hand;" alt="DESPACHOS FALTANTES" title="DESPACHOS FALTANTES" border="0" /></a> 
                      <a href="despacho_oc.php"><img src="images/mas.gif" alt="ADD DESPACHO" title="ADD DESPACHO" border="0" style="cursor:hand;"/></a>
                      <a href="despacho_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="INGRESAR DESPACHOS" title="INGRESAR DESPACHOS" border="0" /></a>
                      <a href="despacho_listado_oci.php"><img src="images/i.gif" style="cursor:hand;" alt="INACTIVAS" title="INACTIVAS" border="0" /></a>
                      <a href="despacho_listado1_oc.php"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
                    </td>
                    </tr> 
                    <tr>
                      <td style="text-align: right;"  colspan="12" id="dato1"> 
                        <img src="images/facturado.png" alt="YA TIENE FACTURA" title="YA TIENE FACTURA" border="0" style="cursor:hand;" width="20" height="18" />Ya Tiene Factura/Comprobante <img src="images/falta8.gif" alt="SIN AUTORIZAR" title="SIN AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" />Sin Factura 
                      </td>
                    </tr>  
                    <tr>
                      <td style="text-align: right;"  colspan="5" id="dato1"> 
                        <div class="alert alert-danger divScroll" role="alert" style="text-align: left;">
                          <span>SIN COMPROBANTE:</span><br>
                          <?php  foreach($row_alertas_rojo as $row_rojo ) { ?>
                            <?php echo "* Remision: " .$row_rojo['int_remision'] ."* O.C ".$row_rojo['str_numero_oc_r']. " sin comprobante de entrega" ."<br>" ; ?>
                          <?php } ?>
                        </div>
                      </td>
                      <td style="text-align: right;"  colspan="7" id="dato1"> 
                        <div class="alert alert-success divScroll" role="alert" style="text-align: left;" >
                          <span>CON COMPROBANTE:</span><br>
                          <?php  foreach($row_alertas_verde as $row_verde ) { ?>
                            <?php echo "* Remision: " .$row_verde['int_remision'] . "* ".$row_verde['str_numero_oc_r']. " con comprobante de entrega" ."<br>" ; ?>
                          <?php } ?>
                        </div> 
                      </td>
                    </tr> 
                    <tr id="tr1">
                      <td id="fuente2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                      <td id="titulo4" nowrap="nowrap">N° REMISION</td>
                      <td id="titulo4">N° O.C.</td>
                      <td id="titulo4">CLIENTE</td>
                      <td id="titulo4">CIUDAD</td>
                      <td id="titulo4">TRANSPOR.</td>
                      <td id="titulo4">FECHA</td>
                      <td id="titulo4">GUIA N°</td>
                      <td nowrap="nowrap" id="titulo4">FACTURA N°</td>
                      <td id="titulo4">VENDEDOR</td>
                      <td id="titulo4">COMPROBANTE?</td>
                      <td id="titulo4">ESTADO</td>
                      <?php if($_SESSION['acceso']): ?><td id="titulo4" nowrap>FACTURAR</td> <?php endif; ?>
                    </tr>
                    <?php foreach($registros as $row_remision) {  ?>
                    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                      <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_remision['int_remision']; ?>" /></td>
                      <td nowrap id="dato2"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><strong><?php echo $row_remision['int_remision']; ?></strong></a></td>
                      <td nowrap id="dato2"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><strong><?php echo $row_remision['str_numero_oc_r']; ?></strong></a></td>
                      <td nowrap id="dato1"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000">
                        <?php 
                             $str_numero_oc_r=$row_remision['str_numero_oc_r'];
                             $sqln = $conexion->llenarCampos('cliente,tbl_orden_compra', "WHERE tbl_orden_compra.id_c_oc = cliente.id_c and tbl_orden_compra.str_numero_oc='$str_numero_oc_r' ", '','cliente.nombre_c,cliente.ciudad_c' );
                             
                             $cliente_c=$sqln['nombre_c']; echo  ($cliente_c);
                             $ciudad_c=$sqln['ciudad_c'];  
                        ?>
                        </a>
                      </td>
                      <td nowrap id="dato1"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo str_replace ( "/", "", $row_remision['ciudad_pais']); ?>
                        </a>
                      </td>
                      <td nowrap id="dato1"> 
                         <?php
                            $transport = $row_remision['str_transportador_r'];
                            $transport = trim($transport); 
                           if($transport=="COORDINADORA"){
                                 
                                 ?>
                                 <a href="https://www.coordinadora.com/portafolio-de-servicios/servicios-en-linea/rastrear-guias/" target="_blank" ><?php echo $transport; ?></a>
                                 <?php

                           }else if($transport=="TCC"){
                                 
                                 ?>
                                 <a href="https://www.tcc.com.co/courier/mensajeria-productos-y-servicios/rastrear-envio/" target="_blank" ><?php echo $transport; ?></a>
                                 <?php

                           }else if($transport=="SERVIENTREGA"){
                                ?>
                                <a href="https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/" target="_blank" ><?php echo $transport; ?></a>
                                <?php
                                 
                           
                           }else if($transport==''){ 
                              ?>
                               <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo"Sin Transportadora"; ?></a>
                              <?php
                           }else{ 
                              ?>
                               <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $transport; ?></a>
                              <?php
                           } 
                         ?>
                         </td>
                      <td nowrap id="dato1"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_remision['fecha_r']; ?></a></td>
                      <td id="dato1"> 
                        <?php
                     $str_guia = $row_remision['str_transportador_r'];
                     $str_guia = trim($str_guia); 
                    if($str_guia=="COORDINADORA"){
                          
                          ?>
                          <a href="https://www.coordinadora.com/portafolio-de-servicios/servicios-en-linea/rastrear-guias/" target="_blank" ><?php echo $row_remision['str_guia_r'] ?></a>
                          <?php

                    }else if($str_guia=="TCC"){
                          
                          ?>
                          <a href="https://www.tcc.com.co/courier/mensajeria-productos-y-servicios/rastrear-envio/" target="_blank" ><?php echo $row_remision['str_guia_r'] ?></a>
                          <?php

                    }else if($str_guia=="SERVIENTREGA"){
                         ?>
                         <a href="https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/" target="_blank" ><?php echo $row_remision['str_guia_r'] ?></a>
                         <?php
                          
                    
                    }else if($str_guia==''){ 
                       ?>
                        <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo"Sin Guia"; ?></a>
                       <?php
                    }else{ 
                       ?>
                        <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_remision['str_guia_r'] ?></a>
                       <?php
                    } 
                  ?>

                        </td>

                      <td id="dato1">
                          <?php  
                            if($row_remision['factura_r']!='0' && $row_remision['factura_r']!=''){
                                $datosFE = substr($row_remision['factura_r'], 0, 2);  
                               
                               $variasFacArray = array();
                              if($datosFE=="FE"){ 
                                //$variasFacArray[] = $row_remision['factura_r'];
                                 $variasFacArray=( explode(',', $row_remision['factura_r']) );
                                 foreach ($variasFacArray as $key => $value) {
                                   $conceros = $value ;
                                  ?>  
                                  <a href="javascript:verFoto('PDF_FE/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                                  <?php
                                 }  

                                }if($datosFE!="FE"){
                                
                                   $digito = "FE";
                                   $facturaCompleto = $row_remision['factura_r'];
                                   if($facturaCompleto!='' || $facturaCompleto!= null){ 
                                   $conceros = $digito.(str_pad($facturaCompleto, 7, "0", STR_PAD_LEFT)); 
                                   ?>  
                                   <a href="javascript:verFoto('PDF_FE/<?php echo $conceros ;?>.pdf','610','490')">  <?php echo $conceros.'<br>'; ?> </a> 
                                   <?php  
                                   }else{
                                     $conceros = '';   
                                   } 
                                 }  

                               }

                        ?> 
                      </td>
                      <td nowrap="nowrap" id="dato1">
                        <?php $mp=$row_remision['str_numero_oc_r'];
                        if($mp!='')
                        { 

                          $resultmp = $conexion->llenarCampos('tbl_orden_compra', "WHERE str_numero_oc='$mp' ", '','factura_oc,b_estado_oc,str_elaboro_oc' ); 
                            $b_estado_oc= $resultmp['b_estado_oc'];
                            $factura_oc =  $resultmp['factura_oc']; 
                        } 

                        $idoc = $row_remision['str_numero_oc_r'];
                        $select_direccion = $conexion->llenaListas('vendedor ver',"left join tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io= '$idoc'","","distinct ver.nombre_vendedor");
                         foreach($select_direccion as $row_direccion) { 
                           $vende = $row_direccion['nombre_vendedor']." ";
                         } 
                             echo htmlentities($vende); 
                        ?>
                      </td>
                      <td id="dato2">  
                        <?php if( ($row_remision['comprobante_file']!='' || !is_null($row_remision['comprobante_file'])) ):  ?>
                          <a href="javascript:verFoto('Archivosdesp/<?php echo $row_remision['comprobante_file'];?>','610','490')"> 
                             <img src="images/facturado.png" alt="YA TIENE COMPROBANTE" title="YA TIENE COMPROBANTE" border="0" style="cursor:hand;" width="20" height="18" /> 
                             </a> 
                              <?php else: ?> 
                             <img src="images/18.PNG" alt="SIN COMPROBANTE" title="SIN COMPROBANTE" border="0" style="cursor:hand;" width="20" height="18" />   
                          <?php endif; ?>
                      </td>
                      <td id="dato2">
                        <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>" target="_blank" style="text-decoration:none; color:#000000">

                          <?php if($b_estado_oc=='5'){echo "Facturado Total";}else if($b_estado_oc=='4'){echo "Facturado Parcial";}else if($b_estado_oc=='1'){echo "Ingresado";}else if($b_estado_oc=='2'){echo "Programado";}else if($b_estado_oc=='3'){echo "Remisionado";}else if($b_estado_oc=='6'){echo "Muestras reposicion";}  ?></a>
                      </td>
                      <?php if($_SESSION['acceso']): ?>
                        <td id="dato2">  
                          <a href="javascript:updateList('int_remision',<?php echo $row_remision['int_remision']; ?>,'despacho_listado1_oc.php')" >
                            <?php   //$row_remision['int_remision']
                            if( ($row_remision['factura_r']=='' || $row_remision['factura_r']=='0')  && ($factura_oc=='' || $factura_oc=='0')  ): ?>
                                <img src="images/falta8.gif" alt="ACTUALIZAR" title="ACTUALIZAR" border="0" style="cursor:hand;" width="20" height="18" /></a>
                                  <?php else: ?>
                                <img src="images/facturado.png" alt="YA TIENE FACTURA" title="YA TIENE FACTURA" border="0" style="cursor:hand;" width="20" height="18" />
                              <?php endif; ?>
                              </a>
                        </td> 
                        <div style="display: none;  align-items: center; justify-content: center; " id="resp"> <b style="color: red;" >Actualizando Numero de Factura!</b></div>
                      <?php endif; ?>
                      </tr>


                      <?php } ?>
                    </table>
                  </form>
                  <table border="0" width="50%" align="center">
                    <tr>
                      <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                        <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
                        <?php } // Show if not first page ?>
                      </td>
                      <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                        <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
                        <?php } // Show if not first page ?>
                      </td>
                      <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
                        <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
                        <?php } // Show if not last page ?>
                      </td>
                      <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
                        <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
                        <?php } // Show if not last page ?>
                      </td>
                    </tr>
                  </table>
                  </td>
                </tr>
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
        </body>
        </html>
        <script>
          function myFunction() { 
            var cod_ref = document.getElementById("cod_ref").value;
            var ano = document.getElementById("fecha").value;
            var mes = document.getElementById("mensual").value;
            var dia = document.getElementById("dia").value;
            var vende = document.getElementById("vende").value;
            var id_c = document.getElementById("id_c").value;
            var int_remision= document.getElementById("int_remision").value;
            var str_numero= document.getElementById("str_numero").value;
            var estado_oc= document.getElementById("estado_oc").value;
            var estado_rd= document.getElementById("estado_rd").value;
            window.location.href = "despachos_excel.php?fecha="+ano+'&mensual='+mes+'&dia='+dia+'&cod_ref='+cod_ref+'&vende='+vende+'&id_c='+id_c+'&int_remision='+int_remision+'&str_numero='+str_numero+'&estado_oc='+estado_oc+'&estado_rd='+estado_rd;
          }
        </script>

        <script>
   /*        $(document).ready(function(){

             var editar =  "<?php echo $_SESSION['acceso'];?>";
             if(editar==0){
              $('a').each(function() { 
               $(this).attr('href', '#');
             });
              //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
            }
          });*/


         $(document).ready(function(){  

            $('#int_remision').select2({ 
                ajax: {
                    url: "select3/proceso.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            palabraClave: params.term, // search term
                            var1:"*",
                            var2:"tbl_remisiones",
                            var3:"",
                            var4:"ORDER BY int_remision DESC",
                            var5:"int_remision",
                            var6:"int_remision"
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

            $('#str_numero').select2({ 
                ajax: {
                    url: "select3/proceso.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            palabraClave: params.term, // search term
                            var1:"*",
                            var2:"tbl_remisiones",
                            var3:"",
                            var4:"ORDER BY int_remision DESC",
                            var5:"str_numero_oc_r",
                            var6:"str_numero_oc_r"
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

            $('#cod_ref').select2({ 
                ajax: {
                    url: "select3/proceso.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            palabraClave: params.term, // search term
                            var1:"*",
                            var2:"tbl_referencia",
                            var3:"",
                            var4:"ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC",
                            var5:"cod_ref",
                            var6:"cod_ref"
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

            $('#id_c').select2({ 
                ajax: {
                    url: "select3/proceso.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            palabraClave: params.term, // search term
                            var1:"*",
                            var2:"cliente",
                            var3:"",
                            var4:"ORDER BY nombre_c ASC",
                            var5:"id_c",
                            var6:"nombre_c"
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
        mysql_free_result($usuario);mysql_close($conexion1);

        mysql_free_result($orden);

        mysql_free_result($numero);

        mysql_free_result($cliente);

        mysql_free_result($remision);

        ?>