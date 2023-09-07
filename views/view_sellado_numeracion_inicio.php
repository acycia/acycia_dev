<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session

//initialize the session
 require_once("db/db.php"); 
 require_once("Controller/Csellado.php");


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




 $conexion = new ApptivaDB();
 


?>




<!-- <?php foreach($this->row_op_carga as $row_op_carga) { $row_op_carga; } ?>
<?php foreach($this->row_codigo_empleado as $row_codigo_empleado) { $row_codigo_empleado; } ?>
<?php foreach($this->row_revisor as $row_revisor) { $row_revisor; } ?> -->
<?php $insumo = new oMsellado();
         $this->insumo=$insumo->get_Insumo();
         $this->insumo=$insumo->llenaSelect("tbl_reg_tipo_desperdicio","WHERE id_proceso_rtd='4' AND codigo_rtp='3' AND estado_rtp='0'","ORDER BY nombre_rtp ASC"); ?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
 <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <!--<link href="css/camposGrandes.css" rel="stylesheet" type="text/css" />--> 
  <script type="text/javascript" src="js/validacion_numerico.js"></script>

  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <!-- <script type="text/javascript" src="js/consulta.js"></script> -->

 <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script> 
  <script type="text/javascript" src="AjaxControllers/js/funcionesSellado.js"></script> 
  
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
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
   <style type="text/css">
 .loader {
     position: fixed;
     left: 0px;
     top: 0px;
     width: 100%;
     height: 100%;
     z-index: 3200;
     background: url('images/loadingcircle4.gif') 50% 50% no-repeat rgb(250,250,250);
     background-size: 5% 10%;/*tamaño del gif*/
     -moz-opacity:65;
     opacity:0.65;

 }
 </style>
</head>
<body > 
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla1">


        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                 <div class="panel-heading"><h2>SELLADO NUMERACION INICIO</h2></div>
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                  </ul>
               </div> 
              <div class="panel-body">
          <br> 
        <div class="container"> 
    <br> 
 
  <form action="view_index.php?c=csellado&a=GuardarAdd" method="POST" id="form1" name="form1" onSubmit="return validacion_select();">
    <table align="center" id="tabla35">
      <tr>
        <td colspan="6" id="dato3">
          <a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" border="0"title="LISTADO O.P"/> </a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
      </tr>
      <tr>
        <td colspan="6" id="titulo2">REGISTRO DE PAQUETES <strong>  
          <input type="hidden" name="b_borrado_n" id="b_borrado_n" value="0">
          <input type="hidden" name="existeTiq_n" id="existeTiq_n" value="1">
          <input class="form-control" name="cod_ref_n" type="hidden" id="cod_ref_n" value=""> 
          <input name="contador_tn" id="contador_tn" type="hidden" value=""> 
          
          <input name="ref_tn" type="hidden" id="ref_tn" value="">
        </strong></td>
      </tr>
      <tr>
        <td colspan="3"><strong>PAQUETE N:</strong> </td>
        <td ><strong>CAJA N:</strong><strong  id="verAlert" style="display: none;" class="alert alert-danger" role="alert" style="text-align: left;"> </strong> </td>
        <td colspan="3" id="fuente1" > <span id="checkfaltantes"></span><em> / Peso</em> </td> 
      </tr>
          <tr>
            <td colspan="3">  
              <span class="rojo_inteso">
              <?php  
              $restrincion = $_SESSION['superacceso'];
                if($num_paq==''){$num_paq='1';}     

                if($row_op_carga['int_cod_ref_op'] =='096'){
                      $sumauno='1';//para que siga el consecutivo del ultimo paquete de la 096 
                 } 
              ?>
             </span>
             <input class="form-control" name="int_paquete_tn" style="width:100px;" type="number" id="int_paquete_tn" value="1" maxlength="5">
             </td>  
            <td>  
              <input class="form-control" name="int_caja_tn" id="int_caja_tn" style="width:200px;" type="number" value="1">
            </td>
      
            <td colspan="3" >
             <input class="check" type="checkbox" name="imprimirt"  id="imprimirt" value="0"/>
             <input name="tienefaltantes"  id="tienefaltantes" type="hidden" value="" >  
              
             <input name="pesot" required="required" style="width:50px;" step="0.01" type="number" id="pesot" placeholder="Peso Caja" value="1" >  
            </td> 
      </tr>
      
     <tr>
      <td colspan="3" id="fuente1">FECHA</td>
      <td  id="fuente1"><input class="form-control" name="fecha_ingreso_tn" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" style="width:200px;" />
        <input class="form-control" name="hora_tn" type="hidden" id="hora_tn" value="<?php echo restoHoranew(2);?>" size="8" readonly />
      </td>
      <td colspan="3" ><em> Consecutivo Orden Actual? </em>
      <select  style="width:100px;" class="form-control"  name="selladonum" id="selladonum" > 
            <option value="NO" selected >NO</option> 
            <option value="SI">SI</option> 
        </select></td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">ORDEN P.</td>
        <td id="fuente1"> 
          <select  class="form-control" name="int_op_tn" id="int_op_tn" required="required" style=" width:400px">
             <option value=""<?php if (!(strcmp("", $row_op_carga['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                <?php  foreach($row_op as $row_op ) { ?>
                <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $row_op_carga['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['id_op']?></option>
               <?php } ?>
            </select>
            <td><span class="negro_inteso">REF:&nbsp;</span><span class="referenci" ></span></td> 
         </td>
       </tr>
      <tr>
        <td colspan="3" id="fuente1">BOLSAS millar/und</td>
        <td colspan="3" ><input class="form-control" type="number" name="int_bolsas_tn" id="int_bolsas_tn" min="0" value="" readonly></td>
      </tr>
      
      <tr>
        <td colspan="3" id="fuente1">UNIDADES X CAJA</td>
        <td colspan="3" ><input class="form-control" type="number" name="int_undxcaja_tn" <?php if ($restrincion!='1') {echo "readonly";}?> id="int_undxcaja_tn" min="0" value=""></td> 
      </tr>
      <tr>
        <td colspan="3" id="fuente1">UNIDADES  X PAQ.</td>
        <td colspan="3" > 
          <input class="form-control" type="number" name="int_undxpaq_tn" <?php if ($restrincion!='1') {echo "readonly";}?> id="int_undxpaq_tn"min="0" value="" >
        </td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">DESDE # O.P
          <abbr title="Este numero se trae de numeracion de la o.p"> <strong style="color: red;" >ver...</strong></abbr> 
        </td>
        <td colspan="2" > 
          <input class="form-control negro_inteso " type="text" <?php if ($restrincion!='1') {echo "readonly";}?> name="int_desde_tn"  id="int_desde_tn"  value=""  min="0" onBlur="conMayusculas(this);" autofocus required>
      </td>
        <td >
        <input class="form-control negro_inteso charfin" style=" width:100px" type="text" name="charfin" autofocus id="charfin" value="" min="0" onchange="conMayusculas(this)" readonly="readonly" >
      </td> 
    </tr>
<tr>
  <td colspan="3" id="fuente1"><strong>HASTA</strong></td>
  <td colspan="2" ><input class="form-control negro_inteso " type="text" name="int_hasta_tn" id="int_hasta_tn" required="required" value="" min="0" <?php if ($restrincion!='1') {echo "readonly";}?>>
  </td>
  <td> 
      <input class="form-control negro_inteso charfin" style=" width:100px" type="text" name="charfin" autofocus id="charfin" value="<?php echo $row_op_carga['charfin'];?> " min="0" onchange="conMayusculas(this)" readonly="readonly"> 
  </td>
</tr>
<tr>
  <td colspan="3" id="fuente1">CODIGO DE OPERARIO</td>
  <td colspan="3" id="fuente1"> 
    <select class="form-control" name="int_cod_empleado_tn" id="int_cod_empleado_tn" required="required">
      <option value=""<?php if (!(strcmp("", $row_tiquete_num['int_cod_empleado_n']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
      <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
        <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_tiquete_num['int_cod_empleado_n']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado'];?></option>
      <?php } ?>
    </select>
  </td>
</tr>
<tr>
  <td colspan="3" id="fuente1">CODIGO DE REVISOR</td>
  <td colspan="3" id="fuente1"> 
    <select class="form-control" name="int_cod_rev_tn" id="int_cod_rev_tn" required="required" >
      <option value=""<?php if (!(strcmp("", $row_tiquete_num['int_cod_rev_n']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
      <?php  foreach($row_revisor as $row_revisor ) { ?>
        <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_tiquete_num['int_cod_rev_n'],$row_revisor['codigo_empleado']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado'];?></option>
      <?php } ?>
    </select>
  </td>
</tr>          
 <tr>
   <td id="fuente1">PAQUETES X CAJA</td> 
   <td id="fuente1" colspan="6">
     <strong>
       <span class="paqdesde"> </span> DE <span class="paqhasta"> <?php echo $row_tiquete_num['totalcajas'];?></span>
     </strong>
   </td>
 </tr>  
 <tr>
   <td colspan="6" id="dato2">
     <div id="botonSellado" style="display: none;">
      <input name="validar_paquete" id="validar_paquete" value="0" type="hidden"> 
       <button id="guardaboton"  class="botonSellado"  type="button" autofocus onClick="submit_faltante(this.formfalta)" >GUARDAR NUMERACION</button>
       <!-- <button type="submit" id="guardaboton"  class="botonSellado" autofocus>GUARDAR NUMERACION</button> -->
       </div><!--onClick="return funcion2();"-->
     <div id="botonporCajas" style="display: none;">
       <button id="guardabotonporCajas" class="botonSelladoxCaja" type="button" autofocus >GUARDAR NUMERACION X CAJA</button><br>
       <em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG" ></em>   
     </div>
   </td>
 </tr>
 <tr>
   <td colspan="6" id="dato2">&nbsp;</td>
 </tr>          
 <tr>
   <td colspan="6">
     <div id="paquetexcaja"  style="display: none;">  
      <div class="bordesolido" id="ventanas">  
       <table id="example" class="display" style="width:100%" border="1">
         <thead>
         <tr>
           <td colspan="8" >PAQUETES DE ANTERIOR O.P
             <em style="display: none;  align-items: center; justify-content: center;color: red; " id="Mostrando"></em> 
           </td>
         </tr>
           <tr>
             <th>PAQUETE</th>
             <th>DESDE</th>
             <th>HASTA</th> 
           </tr>
         </thead>
         <tbody id="DataConsulta"> 
           
         </tbody>
       </table> <br> 
      </div> 
    </div>
   <!-- <div id="paqycajasnormal" style="display: none;">  
      <div class="bordesolido" id="ventanas">  
       <table id="example" class="display" style="width:100%" border="1">
         <thead>
         <tr>
           <td colspan="8" >&nbsp;
             <em style="display: none;  align-items: center; justify-content: center;color: red; " id="AlertItem"></em> 
           </td>
         </tr>
           <tr> 
             <th>ID</th>
             <th>FECHA INGRESO</th> 
             <th>PAQUETE</th>
             <th>CAJA</th>
             <th>DESDE</th>
             <th>HASTA</th>
             <th>DELETE</th> 
           </tr>
         </thead>
         <tbody id="DataResult"> 
           
         </tbody>
       </table> <br>  
      </div> 
   </div> -->

   <div id="tiquetxCajas" style="display: none;">
     <span class="Estilo1">IMPRIME TODOS PAQUETES DE LA CAJA SIN FALTANTES</span>
     <div class="bordesolido" id="ventanas">
       <table id="example" class="display" style="width:100%" border="1">
         <thead>
         <tr>
           <td colspan="8" >&nbsp;
             <em style="display: none;  align-items: center; justify-content: center;color: red; " id="Mostrando"></em> 
           </td>
         </tr>
           <tr>
             <th>CAJA</th>
             <th>DESDE</th>
             <th>HASTA</th> 
           </tr>
         </thead>
         <tbody id="DataConsultaxCaja"> 
           
         </tbody>
       </table> <br>
        
       <!-- <a href="javascript:popUp('sellado_totaltiqxcaja.php?id_op=<?php echo $ops; ?>','1200','780')" target="_top"><strong class="letraPaquete">TIQ X CAJAS SIN FALTANTES</strong></a> -->
       
     </div>
   </div>
   </td>
 </tr>

   <tr>
          <td colspan="3" id="dato2">&nbsp;</td>
        </tr>          
        <tr>
          <td colspan="3"><?php if($row_tiquete_num['int_paquete_n']!=''){?>
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
             
            <p>        
              <input type="hidden" name="MM_insert" value="form1">
            </p> 
          </form><br>
              <form action="view_index.php?c=csellado&a=GuardarFaltante" method="POST" name="formfalta" id="formfalta" >
                <div id="faltantess" style="display: none;" >
                   <!--            TABLA DE FALTANTES-->  
                   <div id="contenedor">
                     <table id="tablaf">
                      <thead>
                       <tr>
                        <h3>FALTANTES</h3>
                        <th width="200" id="nivel2">NUM. DESDE</th>
                        <th width="200" id="nivel2">NUM. HASTA</th>
                        <th width="100" id="nivel2">FALTAN.</th>
                        <th width="120" id="nivel2"><button type="button" class="botonMiniSellado" onClick="AddItem();" > + </button></th>
                      </tr>
                    </thead>
                    <tbody>
                    <td style="text-align:right;" colspan="4" >
                      <select name="tipodesperdicio_f" id="tipodesperdicio_f" class="selectsMini busqueda" required="required" style="display: none;">
                        <option value="">Desperdicio</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['id_rtp']; ?>"><?php echo htmlentities($insumo['nombre_rtp']); ?> </option>
                        <?php } ?>
                      </select>
                    </td>   

                    <tfoot>
                     <tr>
                      <td id="nivel2">TOTAL FALT.</td>
                      <td colspan="6" id="nivel2">
                        <span id="total">0</span>
                      <input name="validar" id="validar" value="" type="hidden"> 
                    </td>
                      <td></td>
                    </tr>
                  </tfoot>
                    </tbody>
                </table>   

              </div>
            </div>
          </form>
         <div id="content"></div> <!-- este bloquea pantalla evitando duplicidad -->
      </div> <!-- contenedor --> 

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
 document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
     if(e.keyCode == 13) {
       e.preventDefault();
     }
   }))
 });
 
  /***************************************AQUI INICIA TODA LA PROGRAMACION NUEVA**************************************************************/
 
  $(document).ready(function(){
 

    $("#int_op_tn").on('change',function(){
      if( $("#int_op_tn").val()!='' && $("#selladonum").val()=='SI' ){ 
          cargaInfoOpAddNew($("#int_op_tn").val()); 
      }else{ 
        cargaInfoOpAdd($("#int_op_tn").val()); 
      }
    });


 }); 


  $("#selladonum").on('change',function(){ 
    if( $("#int_op_tn").val()!='' && $("#selladonum").val()=='SI' ){ 
        cargaInfoOpAddNew($("#int_op_tn").val()); 
    }else{ 
      cargaInfoOpAdd($("#int_op_tn").val()); 
    }
    });


    $("#int_desde_tn").on('change',function(){
      numeracionDesdeAdd($("#int_desde_tn").val(),$("#int_caja_tn").val(),$("#int_paquete_tn").val(),$("#ref_tn").val(),1 ); 
    });

 

  //AL DAR CLICK A CHECK Y CAMBIAR A SIN FALTANTES O CON FALTANTES
   
    $('#imprimirt').on('change',function(){
      if($("#imprimirt").prop("checked")){ 
            document.form1.imprimirt.value = 1;
               }else{
             document.form1.imprimirt.value = 0; 
         
        } 

          if( $("#int_op_tn").val()!='' && $("#selladonum").val()=='SI' ){
              cargaInfoOpAddNew($("#int_op_tn").val()); 
          }else{
            cargaInfoOpAdd($("#int_op_tn").val()); 
          }

      
  });



    $("#botonSellado").show();
    $("#paqycajasnormal").show(50);
    $("#paquetexcaja").show(50);
    $("#cajasnormal").show(50); 
    $("#faltantess").show(50);
   
 /*if($("#imprimirt").val()==1) {$("#imprimirt").prop("checked", true);  }else{$("#imprimirt").prop("checked", false);}
  
      if($("#imprimirt").val()==1){  
         $("#tiquetxCajas").show(50);   
         $("#botonporCajas").show(); 
         $("#faltantess").hide();  
         $("#botonSellado").hide(); 
         $("#element").show(50); 
         $("#paqycajasnormal").hide();
         $("#paquetexcaja").hide(50); 
         $("#pesot").show();
         $("#checkfaltantes").text('SIN FALTANTES');

           
      } else {
            $("#tiquetxCajas").hide();
            $("#botonSellado").show();  
            $("#faltantess").show(50);  
            $("#botonporCajas").hide();  
            $("#element").show(50);
            $("#paqycajasnormal").show(50);
            $("#paquetexcaja").show(50); 
            $("#checkfaltantes").text('CON FALTANTES');

       
  }*/


   $( "#guardaboton" ).on( "click", function() {
       validaCampos()
   });
   $( "#guardabotonporCajas" ).on( "click", function() {
       validaCampos();
       registroDuplicado();
   });

   function validaCampos(){
         
         if($("#validar_paquete").val()=='1'){
          $("#verAlert").show(); 
          $("#verAlert").text("Ya Existe!"); 
          $('#verAlert').fadeIn(); 
          setTimeout(function() { $("#verAlert").fadeOut();},4000); 
         
           swal("Error", "El registro Ya Existe! :)", "error"); 
           return false;
         }
         else if($("#int_paquete_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo paquete! :)", "error"); 
           return false;
         } 
         else if($("#int_caja_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo caja! :)", "error"); 
           return false;
         }
         else if($("#fecha_ingreso_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo fecha_ingreso! :)", "error"); 
           return false;
         }
         else if($("#int_op_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo op! :)", "error"); 
           return false;
         }
         else if($("#int_bolsas_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo bolsas! :)", "error"); 
           return false;
         }
         else if($("#int_undxcaja_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo undxcaja! :)", "error"); 
           return false;
         }
         else if($("#int_undxpaq_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo undxpaq! :)", "error"); 
           return false;
         }
         else if($("#int_desde_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo desde! :)", "error"); 
           return false;
         }
         else if($("#int_hasta_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo hasta! :)", "error"); 
           return false;
         }
         else if($("#int_cod_empleado_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo cod_empleado! :)", "error"); 
           return false;
         }
         else if($("#int_cod_rev_tn").val()==''){
           swal("Error", "Debe agregar un valor al campo cod_rev! :)", "error"); 
           return false;
         }else if($("#validar").val() > 0 && $("#tipodesperdicio_f").val()==''){
 
           swal("Error", "Debe seleccionar un valor al campo tipo desperdicio ! :)", "error"); 
           return false;
         }else if(submit_faltante()==false){
           swal("Error", "Debe llenar algunos de los Faltantes! :)", "error"); 
         }else{ 
          $('#content').html('<div class="loader"></div>');
             setTimeout(function() { $(".loader").fadeOut("slow");},10000);

             guardarSelladoTiquetes(); 
             envioEdit($("#int_op_tn").val(),$("#int_caja_tn").val());
 
           
         }
   }


    $( ".botonMiniSellado" ).on( "click", function() { 
       idanyobusqueda = $( ".botonMiniSellado" ).val();    
        
         $('#tipodesperdicio_f').show(); 
         
   });

 
    $( "#int_paquete_tn" ).on( "change", function() {
      
        registroDuplicado();
        $("#contador_tn").val($("#int_paquete_tn").val()) 
     
    });

    $( "#int_caja_tn" ).on( "change", function() {
        registroDuplicado(); 
    });


  function registroDuplicado(){
 
       var ref_tn = !$("#ref_tn").val() ? 0 :$("#ref_tn").val();
       var caja = !$("#int_caja_tn").val() ? 0 :$("#int_caja_tn").val();
       var paquete = !$("#int_paquete_tn").val() ? 0 :$("#int_paquete_tn").val();
 
       var result = consultaGeneralTodos("tbl_tiquete_numeracion","id_tn as id","ref_tn","int_caja_tn","int_paquete_tn",ref_tn,caja,paquete);
       return result=0;
  
  }

  function envioEdit(int_op_n,int_caja_n){
    compactada = 'a%3A18%3A%7Bs%3A5%3A"id_tn"%3Bs%3A7%3A"2484710"%3Bs%3A9%3A"int_op_tn"%3Bs%3A4%3A"9215"%3Bs%3A11%3A"id_despacho"%3BN%3Bs%3A16%3A"fecha_ingreso_tn"%3Bs%3A10%3A"2021-11-11"%3Bs%3A7%3A"hora_tn"%3Bs%3A8%3A"03%3A42%3A06"%3Bs%3A13%3A"int_bolsas_tn"%3Bs%3A5%3A"40000"%3Bs%3A14%3A"int_undxpaq_tn"%3Bs%3A3%3A"100"%3Bs%3A15%3A"int_undxcaja_tn"%3Bs%3A4%3A"1000"%3Bs%3A12%3A"int_desde_tn"%3Bs%3A7%3A"2030711"%3Bs%3A12%3A"int_hasta_tn"%3Bs%3A7%3A"2030810"%3Bs%3A19%3A"int_cod_empleado_tn"%3Bs%3A2%3A"53"%3Bs%3A14%3A"int_cod_rev_tn"%3Bs%3A3%3A"264"%3Bs%3A11%3A"contador_tn"%3Bs%3A1%3A"1"%3Bs%3A14%3A"int_paquete_tn"%3Bs%3A1%3A"1"%3Bs%3A11%3A"int_caja_tn"%3Bs%3A3%3A"930"%3Bs%3A5%3A"pesot"%3Bs%3A1%3A"1"%3Bs%3A6%3A"ref_tn"%3Bs%3A3%3A"249"%3Bs%3A7%3A"';
    url = "view_index.php?c=csellado&a=Numeracion&mi_var_array="+compactada+"&int_op_tn="+int_op_n+"&int_caja_tn="+int_caja_n+"";
    $(location).attr('href',url); 
  }


</script>
 
<?php
  mysql_free_result($usuario);
  mysql_free_result($codigo_empleado);
  mysql_free_result($op);
  mysql_free_result($tiquete_num);
  mysql_close($conexion1);
?>