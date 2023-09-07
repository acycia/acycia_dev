<?php
     require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
     require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
 require_once("db/db.php"); 
 require_once("Controller/Csellado.php");

  session_start();
?>

<?php foreach($this->row_control_paquete as $row_control_paquete) { $row_control_paquete; } ?>
<?php //foreach($this->row_op as $row_op) { $row_op; } ?>
<?php foreach($this->row_codigo_empleado as $row_codigo_empleado) { $row_codigo_empleado; } ?>
<?php foreach($this->row_revisor as $row_revisor) { $row_revisor; } ?>
<?php foreach($this->row_tiquete_num as $row_tiquete_num) { $row_tiquete_num; } ?>
<?php foreach($this->existe_caja as $existe_caja) { $existe_caja; } ?> 
<?php foreach($this->select_tiquete_num as $select_tiquete_num) { $select_tiquete_num; } ?>

<?php 
 
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
 
 
//INSERT DE FALTANTES
 
//$conexion = new ApptivaDB();
 
 
//REDIRECCIONA
/*if($row_control_paquete['int_cod_ref_op']=='096'){
  $id_op=$row_control_paquete['id_op'];
  $int_caja_t=($row_tiquete_num['int_caja_n']);
  $paqxca=($row_tiquete_num['int_undxcaja_n']/$row_tiquete_num['int_undxpaq_n']);

  header ("Location: sellado_control_numeracion_edit_paqxcaja.php?id_op=$id_op&int_caja_tn=$int_caja_t&NumeroPaqxCaja=$paqxca&contador=1");
}*/
?>


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
 
  <!--VALIDAR EL ENVIO DE FORMULARIO PARA CAMBIO DE CAJA-->
  <script type="text/javascript">
/*function funcion(){
if(form1.cajaCompleto.value=='1'){
  swal('¡Se ha finalizado el numero de Cajas para esta O.P!'); 
return false;
}
return true;
}
function funcion2(){
if(document.form1.paqCompleto.value=='1'){
var id_tn=document.form1.id_tn.value;
var id_op=document.form1.int_op_tn.value;
var caj=document.form1.int_caja_tn.value;
var caja=parseInt(caj) + parseInt(1);
 
alert('¡Se ha completado el numero de paquetes para esta Caja, continue!');
window.location ='sellado_control_numeracion_edit.php?id_op='+id_op+'&id_tn='+id_tn+'&int_caja_tn='+caja;
  return false;
  }
   return true;
 }*/
</script>
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
<body ><!-- onLoad="sumaPaqSelladoEdit();" -->
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla1">


        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                 <div class="panel-heading"><h2>SELLADO NUMERACION</h2></div>
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

                  <form action="view_index.php?c=csellado&a=Guardar" method="POST" name="form1" id="form1" >
                    <table align="center" id="tabla35">
                      <tr>
                        <td colspan="6" id="dato3">          
                          <a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" border="0"title="LISTADO O.P"/> </a> 
                          <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
                   </td>
               </tr>
           <tr>
          <td colspan="6" id="titulo2">REGISTRO DE PAQUETES
            <strong>  
               <input type="hidden" name="id_tn" id="id_tn" value="<?php echo $select_tiquete_num['id_tn'];?>"> <b id="consecutivoPaq" > <?php echo $select_tiquete_num['id_tn'];?></b>
            </strong>
              <input name="contador_tn" id="contador_tn" type="hidden" value="<?php echo $select_tiquete_num['contador_tn'];?>"> 
           
              <input name="ref_tn" type="hidden" id="ref_tn" value="<?php echo $row_control_paquete['int_cod_ref_op']; ?>">
           
            </td>
          </tr>
          <tr>
            <td colspan="3"><strong>PAQUETE N:</strong> </td>
            <td  ><strong>CAJA N:</strong> <strong  id="verAlert" style="display: none;" class="alert alert-danger" role="alert" style="text-align: left;"> </strong>  </td>
            <td colspan="3" id="fuente1" > <span id="checkfaltantes"></span><em> / Peso</em> </td> 
          </tr>
          <tr>
            <td colspan="3">  
              <span class="rojo_inteso">
              <?php  
              $restrincion = $_SESSION['superacceso'];
              ?>
             </span>
             <input class="form-control negro_inteso" name="int_paquete_tn" style="width:100px;" type="number" id="int_paquete_tn" value="<?php echo $select_tiquete_num['int_paquete_tn']; ?>" maxlength="5">  
            </td>

            <td>  
              <input class="form-control negro_inteso"name="int_caja_tn" id="int_caja_tn" style="width:200px;" type="number" value="<?php echo $select_tiquete_num["int_caja_tn"] ; ?>" maxlength="5"> 
            </td>
  
            <td colspan="3" > 
             <input class="check" type="checkbox" name="imprimirt" <?php if($row_control_paquete['imprimiop']=='0'){echo 'disabled="disabled"'; }  ?>  id="imprimirt" value="<?php echo $select_tiquete_num['imprime']=='' ? $row_control_paquete['imprimiop'] :  $select_tiquete_num['imprime'];?>"/>
             <input name="tienefaltantes"  id="tienefaltantes" type="hidden" value="<?php echo $select_tiquete_num['imprime']=='' ? $row_control_paquete['imprimiop'] :  $select_tiquete_num['imprime'];?>" >  
              
             <input name="pesot" required="required" style="width:50px;" step="0.01" type="number" id="pesot" placeholder="Peso Caja" value="1" >  
            </td>
 
      </tr>
    
      <tr>
        <td colspan="3" id="fuente1">FECHA</td>
        <td colspan="3"id="fuente1"><input class="form-control" name="fecha_ingreso_tn" type="date" min="2000-01-02" value="<?php echo fecha();?>" style="width:200"/>
          <input name="hora_tn" type="hidden" id="hora_tn" value="<?php echo restoHoranew(2);?>" size="8" readonly/></td>
        </tr>
        <tr>
          <td colspan="3"id="fuente1">ORDEN P.</td>
          <td id="fuente1"><input class="form-control negro_inteso" type="number" name="int_op_tn" id="int_op_tn" style=" width:400px" value="<?php  echo $ops = $row_tiquete_num['int_op_n'] ==''? $row_control_paquete['id_op'] :  $row_tiquete_num['int_op_n']; ?>" readonly >
          </td>
          <td><span class="negro_inteso">REF:&nbsp;<?php echo $row_control_paquete['int_cod_ref_op'];?></span></td> 
        </tr>
        <tr>
          <td colspan="3"id="fuente1">BOLSAS</td>
          <td colspan="3"><input class="form-control negro_inteso" type="number" name="int_bolsas_tn" id="int_bolsas_tn" min="0" value="<?php echo $row_tiquete_num['int_bolsas_n']=='' ? $row_control_paquete['int_cantidad_op'] : $row_tiquete_num['int_bolsas_n'];?>" readonly></td>
        </tr>

      <tr>
          <td colspan="3"id="fuente1">UNIDADES X CAJA</td>
          <td colspan="3"><input class="form-control negro_inteso" type="number" name="int_undxcaja_tn" id="int_undxcaja_tn" min="0" <?php if ($restrincion!='1') {echo "readonly";}?> value="<?php echo $row_control_paquete['int_undxcaja_op'];?>"></td> 
        </tr>
        <tr>
          <td colspan="3"id="fuente1">UNIDADES X PAQ.</td>
          <td colspan="3"><input class="form-control negro_inteso" type="number" name="int_undxpaq_tn" id="int_undxpaq_tn" <?php if ($restrincion!='1') {echo "readonly";}?> min="0" value="<?php echo $row_control_paquete['int_undxpaq_op'];?>" readonly> <em>Cambiar en o.p</em></td>
        </tr>
        <tr>
          <td colspan="3"id="fuente1"><strong>DESDE</strong></td>
          <td colspan="2">
           <!-- <input type="text" name="totalFaltantes" id="totalFaltantes" value="<?php echo $faltantes['totalf'];?>">  -->
           <input class="form-control negro_inteso int_desde_tn" type="text" <?php if ($restrincion!='1') {echo "readonly";}?> name="int_desde_tn" autofocus id="int_desde_tn" value="<?php echo $select_tiquete_num['int_hasta_tn']=='' ? $row_control_paquete['numInicio_op'] : $select_tiquete_num['int_hasta_n'];?>" min="0" onchange="conMayusculas(this)" required>
          
          </td>
          <td >
          <input class="form-control negro_inteso charfin" style=" width:100px" type="text" name="charfin" autofocus id="charfin" value="<?php echo $row_control_paquete['charfin'];?>" min="0" readonly="readonly">
        </td>
         </tr>
         <tr>
          <td colspan="3"id="fuente1"><strong>HASTA</strong></td>
          <td colspan="2">
            <input class="form-control negro_inteso"type="text" <?php if ($restrincion!='1') {echo "readonly";}?>  name="int_hasta_tn" id="int_hasta_tn"  required value="" min="0" >
          </td>
          <td> 
              <input class="form-control negro_inteso charfin" style=" width:100px" type="text" name="charfin" autofocus id="charfin" value="<?php echo $row_control_paquete['charfin'];?>" min="0" readonly="readonly"> 
          </td>
        </tr>
        <tr>
          <td colspan="3" nowrap id="fuente1">CODIGO DE OPERARIO</td>
          <td colspan="3" id="fuente1"> 
            <select class="form-control" name="int_cod_empleado_tn" id="int_cod_empleado_tn" >
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
            <select class="form-control" name="int_cod_rev_tn" id="int_cod_rev_tn" >
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
              <button id="guardabotonporCajas" class="botonSelladoxCaja" type="button" autofocus >GUARDAR NUMERACION X CAJA</button><br><em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG" ></em>   
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
                  <td colspan="8" >&nbsp;
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
          <div id="paqycajasnormal" style="display: none;">  
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
               <!-- <a href="javascript:popUp('sellado_control_numeracion_vista_colas.php?id_op=<?php //echo $ops;?>&int_caja_tn=<?php //echo $row_tiquete_num['int_caja_n']; ?>','1200','780')" target="_top"><strong class="letraPaquete">IMIPRIMIR PAQUETES POR CAJA</strong></a>  -->
             </div> 
          </div>
 

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
            </div>
          </div>

        <div id="Faltantes" style="display: none;">
          <span class="Estilo1">MUESTRA FALTANTES DEL ULTIMO PAQUETE</span>
          <div class="bordesolido" id="ventanas">
            <table id="example" class="display" style="width:100%" border="1">
              <thead>
              <tr>
                <td colspan="8" >&nbsp;
                  <em style="display: none;  align-items: center; justify-content: center;color: red; " id="Mostrando"></em> 
                </td>
              </tr>
                <tr>
                  <th>PAQUETE</th>
                  <th>DESDE</th>
                  <th>HASTA</th> 
                </tr>
              </thead>
              <tbody id="DataConsultaFaltantes"> 
                
              </tbody>
            </table> <br> 
          </div>
        </div>

           <div id="cajasnormal" style="display: none;">  
                 <div class="bordesolido" id="ventanasCaja"><br><br>
                   <a href="javascript:popUp('sellado_cajas.php?id_op=<?php echo $ops; ?>','1200','780')" target="_top"><strong class="letraPaquete">VER CAJAS</strong></a> 
               </div>
            </div>
        
              </div>  
          </td>
        </tr>
       
      </table>
    </form>
      <br>
      <!-- GRID FALTANTES-->
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
       
    </div> <!-- contenedor -->

</div>
</div>
</div>
</td>
</tr>
</table>
</div> 
</div>
<div id="content"></div> <!-- este bloquea pantalla evitando duplicidad -->
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
 var ref = "<?php echo $select_tiquete_num['ref_tn'] == '' ? $row_control_paquete['int_cod_ref_op'] : $select_tiquete_num['ref_tn']; ?>";

  $(document).ready(function(){
    var id_tnf = "<?php echo $select_tiquete_num['id_tn'];?>";
    consultaFaltantes(id_tnf);//para cargar si tiene faltantes

    consultaPaquetes($("#int_op_tn").val(),$("#int_caja_tn").val());//carga paquetes de la caja 
    consultaUnSoloPaquetes($("#id_tn").val());
    var numDesde = "<?php echo $select_tiquete_num['int_hasta_tn']=='' ? $row_control_paquete['numInicio_op'] : $select_tiquete_num['int_hasta_tn'];?>";
    var caja = "<?php echo $select_tiquete_num["int_caja_tn"] =='' ? $row_control_paquete['int_undxcaja_op'] : $select_tiquete_num["int_caja_tn"] ; ?>";
    var paquete = "<?php echo $select_tiquete_num['int_paquete_tn'] == '' ? $row_control_paquete['int_undxpaq_op'] : $select_tiquete_num['int_paquete_tn']; ?>";
    
 
    valCheck(numDesde);
    numeracionDesde(numDesde,caja,paquete,ref); //cuadra la numeracion inicial y el hasta
    consultaPaquetesxOp($("#int_op_tn").val());
 }); 
  
//AL DAR CLICK A CHECK Y CAMBIAR A SIN FALTANTES O CON FALTANTES
$('#imprimirt').on('change',function(){
       var numDesde = "<?php echo $select_tiquete_num['int_hasta_tn']=='' ? $row_control_paquete['numInicio_op'] : $select_tiquete_num['int_hasta_tn'];?>";
       var caja = "<?php echo $select_tiquete_num["int_caja_tn"] =='' ? $row_control_paquete['int_undxcaja_op'] : $select_tiquete_num["int_caja_tn"] ; ?>";
       var paquete = "<?php echo $select_tiquete_num['int_paquete_tn'] == '' ? $row_control_paquete['int_undxpaq_op'] : $select_tiquete_num['int_paquete_tn']; ?>";
     if($("#imprimirt").prop("checked")){ 
           document.form1.imprimirt.value = 1;

           //location.reload();//para que cargue bien la numeracion al cliquear
         }else{
           document.form1.imprimirt.value = 0; 
              if($("#int_paquete_tn").val() < ($('#int_undxcaja_tn').val() / $('#int_undxpaq_tn').val())){ 
                 //debe ser mayor a 1 porque sino recarga y no deja cambiar a CON FALTANTES 
                 //location.reload();//para que cargue bien la numeracion al cliquear
              }
        }
    
      valCheck(numDesde);
      consultaMaestroAlCargar('int_op_n',$('#int_op_tn').val(),ref);
      
});



  /*$("#int_desde_tn").on('change',function(){
    numeracionDesde(numDesde,caja,paquete); 
  }); */ 

  $("#int_desde_tn").on('change',function(){
    //var ref = "<?php echo $select_tiquete_num['ref_tn'] == '' ? $row_control_paquete['int_cod_ref_op'] : $select_tiquete_num['ref_tn']; ?>";
    numeracionDesde($("#int_desde_tn").val(),$("#int_caja_tn").val(),$("#int_paquete_tn").val() ); 
  });
  
    $("#botonSellado").show();
    $("#paqycajasnormal").show(50);
    $("#paquetexcaja").show(50);
    $("#cajasnormal").show(50); 
    $("#faltantess").show(50);




   $( "#guardaboton" ).on( "click", function() {
       validaCampos()
   });
   $( "#guardabotonporCajas" ).on( "click", function() {
       registroDuplicado();
       validaCampos();
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
         }
         else if($("#validar").val() > 0 && $("#tipodesperdicio_f").val()==''){
 
           swal("Error", "Debe seleccionar un valor al campo tipo desperdicio ! :)", "error"); 
           return false;
         }  
         else if(submit_faltante()==false){
           swal("Error", "Debe llenar algunos de los Faltantes! :)", "error"); 
         }else{ 
     
         $('#content').html('<div class="loader"></div>');
            setTimeout(function() { $(".loader").fadeOut("slow");},500);
           guardarSelladoTiquetes();
           //guardarSelladoFaltantes()
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


</script>