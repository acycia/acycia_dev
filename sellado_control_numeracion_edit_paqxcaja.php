<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session

session_start();


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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  //CAMBIO DE NUMERACION POR REFERENCIA EN O.P
 
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
        $insertSQL2 = sprintf("INSERT INTO Tbl_faltantes (id_op_f, int_paquete_f, int_caja_f, int_inicial_f, int_final_f, int_total_f) VALUES (%s, %s, %s, %s, %s, %s)",
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
   }//FIN FOR
 }//FALTATES ENVIADOS Y LLENOS
//CONSULTAR TOTAL FALTANTES DEL PAQUETE SEPARAR NUMERO DE LETRAS Y SUMAR TOTAL FALTANTES
 
if( $desdef!='d'){ //PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO
if($hastamenos!='i'){ //EL RANGO HASTA NO DEBE SER MANOR AL DESDE 
if($desdemenos!='e'){//EL RANGO DESDE NO DEBE SER MAYOR AL HASTA    
  $insertSQL = sprintf("INSERT INTO tbl_tiquete_numeracion ( int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, contador_tn, int_paquete_tn, int_caja_tn, pesot,ref_tn) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());   

  $updateSQL = sprintf("UPDATE tbl_numeracion SET id_tn_n=%s, fecha_ingreso_n=%s, int_desde_n=%s, int_hasta_n=%s, int_paquete_n=%s, int_caja_n=%s WHERE int_op_n=%s",
   GetSQLValueString($_POST['id_tn'], "text"),
   GetSQLValueString($_POST['fecha_ingreso_tn'], "date"),
   GetSQLValueString($_POST['int_desde_tn'], "text"),
   GetSQLValueString($_POST['int_hasta_tn'], "text"),
   GetSQLValueString($_POST['int_paquete_tn'], "int"),
   GetSQLValueString($_POST['int_caja_tn'], "text"),
   GetSQLValueString($_POST['int_op_tn'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL, $conexion1) or die(mysql_error()); 
  
  $insertGoTo = "sellado_control_numeracion_edit_paqxcaja.php?id_op=". $_POST['int_op_tn'] . "&int_caja_tn=" . $_POST['int_caja_tn'] . "&NumeroPaqxCaja=" . $_POST['NumeroPaqxCaja'] . "";
  header(sprintf("Location: %s", $insertGoTo));
     }//alert
    }//alert
  }//alert
}//fin isset

$conexion = new ApptivaDB();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario);

$row_control_paquete = $conexion->buscar('tbl_orden_produccion','id_op',$_GET['id_op']); 

$row_op = $conexion->llenarCampos('tbl_tiquete_numeracion','WHERE int_op_tn='.$_GET['id_op'], 'ORDER BY id_tn DESC LIMIT 1','int_cod_empleado_tn,int_cod_rev_tn,int_paquete_tn'); 

$row_codigo_empleado = $conexion->llenaSelect('empleado','WHERE tipo_empleado IN(7,9)','ORDER BY nombre_empleado ASC'); 
$row_revisor = $conexion->llenaSelect('empleado','WHERE tipo_empleado IN(7,9)','ORDER BY nombre_empleado ASC'); 

 
$row_paquete = $conexion->llenarCampos("tbl_numeracion"," WHERE int_op_n='".$_GET['id_op']."' ","","*"); 
 
$cajas =  $_GET['int_caja_tn'] ;

$row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$cajas."'", "ORDER BY int_paquete_tn DESC LIMIT 1", "*");
 
$contador =  $row_tiquete_num['contador_tn'] =='' ? 0 : $row_tiquete_num['contador_tn'];

$num=$contador+1;
  

//LLENA DIV TIQUETES Y CAJAS
$select_tiquete_num = $conexion->llenaSelect('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$_GET['int_caja_tn']."'",'ORDER BY int_paquete_tn DESC');
 
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
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
var NumeroPaqxCaja=document.form1.NumeroPaqxCaja.value;
var id_op=document.form1.int_op_tn.value;
var caj=document.form1.int_caja_tn.value;
var caja=parseInt(caj)+ parseInt(1);//incrementa numero de caja
   
swal('¡Se ha completado el numero de paquetes para esta Caja, continue!');
window.location ='sellado_control_numeracion_edit_paqxcaja.php?id_op='+id_op+'&id_tn='+id_tn+'&int_caja_tn='+caja+'&NumeroPaqxCaja='+NumeroPaqxCaja;
  return false;
  }
  return true;
}*/

</script>
</head>
<body onLoad="sumaPaqSelladoEdit();">
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
                        <td colspan="2" id="dato3"><a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" border="0"title="LISTADO O.P"/> </a> <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="titulo2">REGISTRO DE PAQUETES<strong>
                          <?php $id_tn =$row_tiquete_num['id_tn']=='' ?  $row_paquete['id_tn'] : $row_tiquete_num['id_tn']; ?>
                          <input type="text" name="id_tn" id="id_tn" value="<?php echo $id_tn+1; //}?>"><input name="cod_ref_n" type="hidden" id="cod_ref_n" value="<?php echo $row_control_paquete['int_cod_ref_op']; ?>">
                        </strong></td>
                      </tr>
                      <tr>
                        <td id="dato1" nowrap><strong>PAQ N:</strong>
                         <?php $restrincion = $_SESSION['superacceso'];

                         //$contador=$row_tiquete_num['contador_tn']; 
                          ?>
                         <input name="contador_tn" type="hidden" value="<?php echo $num;?>"> 
                         <input name="int_paquete_tn" style="width:60px;" type="number" <?php //if ($restrincion!='1') {echo "readonly='readonly'";}?> id="int_paquete_tn" value="<?php echo $row_op['int_paquete_tn']+1 ; ?>" maxlength="5">
                         <span class="rojo_inteso"> DE </span><input name="NumeroPaqxCaja" min="1" <?php if ($restrincion!='1') {echo "readonly";}?> type="number" style="width:50px" maxlength="5" value="<?php $paqxcaj=($_GET['NumeroPaqxCaja']); echo $paqxcaj;?>">
                         <!--CONTROS DE PAQUETES X CAJA-->
                         <?php $paqxcaj=($_GET['NumeroPaqxCaja']);?>
                         <?php if($num>$paqxcaj){?>
                           <input name="paqCompleto" type="hidden" value="1"> 
                         <?php }?>
                         
                         <!--CONTROS DE TOTAL BOLSAS-->
                         <?php //if($num2>$row_op['int_caja_n']){?>
                           <!--<input name="cajaCompleto" type="hidden" value="1">-->
                           <?php //}?>                
                         </td>
                         <td id="dato1"><strong>CAJA N : <span class="rojo_inteso">
                          <?php $num2 = $row_tiquete_num['int_caja_tn']=='' ? $row_paquete['int_caja_tn']+1 : $row_tiquete_num['int_caja_tn']; ?>
                        </span>
                        <input name="int_caja_tn" style="width:60px;" type="number" <?php //if ($restrincion!='1') {echo "readonly='readonly'";}?> id="int_caja_tn" value="<?php echo $num2; //}?>" maxlength="5">
                      </span></strong>
                      <?php if($num == $paqxcaj){?>
                          &nbsp;&nbsp; <input name="pesot" required="required" style="width:80px;" step="0.01" type="number" id="pesot" placeholder="Peso Caja"  value="1">
                       <?php }?>
                  </td>

                      <td id="dato1">
                        
                      </td>

                    </tr>
                    <tr>
                      <td id="fuente1">FECHA</td>
                      <td id="fuente1"><input name="fecha_ingreso_tn" type="date" min="2000-01-02" value="<?php echo fecha();?>" style="width:173px"/>
                        <input name="hora_tn" type="hidden" id="hora_tn" value="<?php echo Hora();?>" size="8" readonly /></td>
                      </tr>
                      <tr>
                        <td id="fuente1">ORDEN P.</td>
                        <td id="fuente1"><input type="number" name="int_op_tn" id="pswd" style=" width:80px" value="<?php if($row_tiquete_num['int_op_tn']=='')echo $ops = $row_paquete['int_op_tn']; else echo $ops = $row_tiquete_num['int_op_tn'];?>" readonly > Ref: <?php echo $row_control_paquete['int_cod_ref_op'];?></td>
                      </tr>
                      <tr>
                        <td id="fuente1">BOLSAS</td>
                        <td ><input type="number" name="int_bolsas_tn" id="pswd" min="0" value="<?php if($row_tiquete_num['int_bolsas_tn']=='')echo $row_paquete['int_bolsas_tn']; else echo $row_tiquete_num['int_bolsas_tn'];?>" readonly></td>
                      </tr>

                      <tr>
                        <td id="fuente1">UNIDADES X CAJA</td>
                        <td ><input type="number" name="int_undxcaja_tn" <?php if ($restrincion!='1') {echo "readonly='readonly'";}?> id="pswd" min="0" value="<?php echo $row_control_paquete['int_undxcaja_op'];?>"></td> 
                      </tr>
                      <tr>
                        <td id="fuente1">UNIDADES  X PAQ.</td>
                        <td ><input type="number" name="int_undxpaq_tn" id="pswd" min="0" value="<?php echo $row_control_paquete['int_undxpaq_op'];?>" readonly>
                          <em>Cambiar en o.p</em></td>
                        </tr>
                        <tr>
                          <td id="fuente1"><strong>DESDE</strong></td>
                          <td >
              <?php 
                          //CONTROL DE CADENA DIVIDE NUMEROS DE LETRAS PARA INICIAR CON EL ULTIMO DEL ULTIMO PAQUETE SUMAR 1 AL DESDE DEL NUEVO PAQUETE
                      if($row_tiquete_num['int_hasta_tn']==''){$desde=$row_paquete['int_hasta_tn'];}else{$desde=$row_tiquete_num['int_hasta_tn'];} //if($row_paquete['int_hasta_tn']==''){$desde=$row_nuevo_paquete['int_hasta_tn'];} 
                      $cadena = $desde; 
              ?>             
          <input type="text" <?php if ($restrincion!='1') {echo "readonly";}?> name="int_desde_tn" autofocus id="pswd" value="<?php echo $cadena; ?>" min="0" onChange="conMayusculas(this),sumaPaqSelladoAdd(this);" required></td>
        </tr>
        <tr>
          <td id="fuente1"><strong>HASTA</strong></td>
          <td ><input type="text" <?php if ($restrincion!='1') {echo "readonly";}?> name="int_hasta_tn" id="pswd" readonly required value="" min="0"></td>
        </tr>
        <tr>
          <td nowrap id="fuente1">CODIGO DE OPERARIO</td>
          <td id="fuente1"> 
            <select name="int_cod_empleado_tn" id="operario" style="width:157px">
              <option value=""<?php if (!(strcmp("", $row_op['int_cod_empleado_tn']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
              <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
                <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_op['int_cod_empleado_tn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td id="fuente1">CODIGO DE REVISOR</td>
          <td id="fuente1">
            <select name="int_cod_rev_tn" id="revisor" style="width:157px">
              <option value=""<?php if (!(strcmp("", $row_op['int_cod_rev_tn']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
              <?php  foreach($row_revisor as $row_revisor ) { ?>
                <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_op['int_cod_rev_tn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
              <?php } ?>
            </select>
          </td>
        </tr>            
        <tr>
          <td id="fuente1">PAQUETES X CAJA</td><?php if($row_tiquete_num['int_undxpaq_tn']=='') {$paqxcaja=($row_paquete['int_undxcaja_tn']/$row_paquete['int_undxpaq_tn']);} else  {$paqxcaja=($_GET['NumeroPaqxCaja']);}?>
          <td id="fuente1"><strong><?php echo $num. " de " . $paqxcaj;?></strong></td>
        </tr>
        <tr>
          <td colspan="2">           
          </td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><button type="submit" class="botonSellado" autofocus>GUARDAR NUMERACION</button></td>
        </tr>
        <tr>
          <td colspan="2" id="dato2">&nbsp;</td>
        </tr>          
        <tr>
          <td colspan="2"><?php if($row_tiquete_num['int_paquete_tn']!=''){?>
            <span class="Estilo1">PAQUETES</span>
            <div class="bordesolido" id="ventanas">
              <?php foreach($select_tiquete_num as $row_tiquete_num) {  ?>
                <p class="letraPaquete"><a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_tiquete_num['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_tiquete_num['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_tiquete_num['int_caja_tn']; ?>','700','350')" target="_top"><?php echo "PAQUETE #: ".$row_tiquete_num['int_paquete_tn']. " DESDE: ".  $row_tiquete_num['int_desde_tn']. " HASTA: ".$row_tiquete_num['int_hasta_tn'];?></a>-----<a href="javascript:eliminar_sp('id_tnpxc',<?php echo $row_tiquete_num['id_tn'];?>,'sellado_control_numeracion_edit_paqxcaja.php')"> 
                  <img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR TIQUETE" title="ELIMINAR TIQUETE" border="0"></a></p>
                <?php } while ($row_tiquete_num = mysql_fetch_assoc($tiquete_num)); ?>
                </div><?php }?>
                <?php //if($row_caja_num['int_caja_tn']!=''){?>
                  <span class="Estilo1">CAJAS</span>
                    <div class="bordesolido" id="ventanasCaja"><br><br>
                     <a href="javascript:popUp('sellado_cajas.php?id_op=<?php echo $ops; ?>','770','400')" target="_top"><strong class="letraPaquete">VER CAJAS</strong></a> 
                    </div>

                    <?php //foreach($select_caja_num as $row_caja_num) {  ?>        
                     <!--  <p class="letraPaquete"><a href="javascript:popUp('sellado_control_cajas_vista.php?id_op=<?php echo $row_caja_num['int_op_tn']; ?>&int_caja_tn=<?php echo $row_caja_num['int_caja_tn']; ?>','770','400')" target="_top"><?php echo "CAJA REGISTRO X CAJAS #: ".$row_caja_num['int_caja_tn']?></a></p> -->
                    <?php  //}  ?></div>
                    <?php //}?>
                    <span class="Estilo1">TIQUETES X CAJAS</span>
                       <div class="bordesolido" id="ventanasCaja"> 
                        <?php 
                        //faltante por paquete
                        $registros = $conexion->llenaListas('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' ",'ORDER BY int_caja_tn DESC','DISTINCT int_op_tn, int_caja_tn' );
                         
                        //Navegamos cada fila que devuelve la consulta mysql y la imprimimos en pantalla
                        foreach($registros as $fila) { 
                         $op_tipxcaj=$fila['int_op_tn']; $caja_tipxcaj=$fila['int_caja_tn']; 
                         ?>          
                         <p class="letraPaquete"><a href="javascript:popUp('sellado_control_numeracion_vista_colas.php?id_op=<?php echo $op_tipxcaj; ?>&int_caja_tn=<?php echo $caja_tipxcaj; ?>','770','350')" target="_top"><?php echo "TIQUETES X CAJAS #: ".$caja_tipxcaj;?></a></p>
                       <?php }?>
                     </div>          

                 </td>
               </tr>
               <tr>
                <td colspan="2" id="dato2"><strong>FALTANTES</strong></td>
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
                 <th width="50" id="nivel2"><button type="button" class="botonMiniSellado" onClick="AddItem();" > + </button></th>
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
        var NumeroPaqxCaja=document.form1.NumeroPaqxCaja.value;
        var id_op=document.form1.int_op_tn.value;
        var caj=document.form1.int_caja_tn.value;
        var caja=parseInt(caj)+ parseInt(1);//incrementa numero de caja
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
            document.form1.contador_tn.value = 1;  
            document.form1.int_caja_tn.value = caja;  
            document.form1.submit();
           //window.location ='sellado_control_numeracion_edit_paqxcaja.php?id_op='+id_op+'&id_tn='+id_tn+'&int_caja_tn='+caja+'&NumeroPaqxCaja='+NumeroPaqxCaja;
         });
                
              } else {
                swal("Cancelado " , " No se cambio de caja :)", "error");
              }
            });
    });
  }
</script></td>
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