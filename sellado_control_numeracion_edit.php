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

$conexion = new ApptivaDB();

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
 
//se debe actualizar por ref en op
/*   $cod_ref_op = $_POST['cod_ref_n'];
   $numeracion=$_POST['int_hasta_tn'];
   $updateINV = "UPDATE tbl_orden_produccion SET numInicio_op='$numeracion' WHERE int_cod_ref_op='$cod_ref_op'";
   $resultINV=mysql_query($updateINV);//12-08-2021*/

/*&&&&&&&&&&&&&&&&&&&&&&&&&nuevo codigo&&&&&&&&&&&&&&&&&&&&&&&&6*/
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
 
 
if( $desdef!='d'){ //PARA EVALUAR QUE LOS FALTANTES ESTEN DENTRO DEL RANFO
if($hastamenos!='i'){ //EL RANGO HASTA NO DEBE SER MANOR AL DESDE 
if($desdemenos!='e'){//EL RANGO DESDE NO DEBE SER MAYOR AL HASTA  


 

/*$row_control_paquete = $conexion->buscarTres('tbl_tiquete_numeracion',"id_tn","  WHERE int_op_tn= '".$_POST['int_op_tn']."'  AND int_caja_tn='".$_POST['int_caja_tn']."' AND  int_paquete_tn= '".$_POST['int_paquete_tn']."'", ""); 
  
  if($row_control_paquete['id_tn']==''){

  


    $resulrt = $conexion->insertar("tbl_tiquete_numeracion","int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, contador_tn, int_paquete_tn, int_caja_tn, pesot, ref_tn, imprime "," '".$_POST['int_op_tn']."','".$_POST['fecha_ingreso_tn']."','".$_POST['hora_tn']."','".$_POST['int_bolsas_tn']."','".$_POST['int_undxpaq_tn']."','".$_POST['int_undxcaja_tn']."','".$_POST['int_desde_tn']."','".$_POST['int_hasta_tn']."','".$_POST['int_cod_empleado_tn']."','".$_POST['int_cod_rev_tn']."','".$_POST['contador_tn']."','".$_POST['int_paquete_tn']."','".$_POST['int_caja_tn']."','".$_POST['pesot']."','".$_POST['cod_ref_n']."','1' ");
  } 

  $resulUP = $conexion->actualizar("tbl_numeracion", "int_hasta_n='".$_POST['int_hasta_tn']."',int_paquete_n='".$_POST['int_paquete_tn']."',int_caja_n='".$_POST['int_caja_tn']."' ", " int_op_n=".$_POST['int_op_tn']." " );*/
  
  //finaliza el if de si existe el registro

 

 
   
 $insertSQL = sprintf('INSERT INTO tbl_tiquete_numeracion ( int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, contador_tn, int_paquete_tn, int_caja_tn, pesot, ref_tn) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', 
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
 

/*$resulUP = $conexion->actualizar("tbl_numeracion", " cod_ref_n = '". $_POST['cod_ref_n'] ."', int_bolsas_n = '". $_POST['int_bolsas_tn'] ."',int_undxpaq_n = '". $_POST['int_undxpaq_tn'] ."',int_undxcaja_n = '". $_POST['int_undxcaja_tn'] ."',int_desde_n = '". $_POST['int_desde_tn'] ."',int_hasta_n = '". $_POST['int_hasta_tn'] ."',int_cod_empleado_n = '". $_POST['int_cod_empleado_tn'] ."',int_cod_rev_n = '". $_POST['int_cod_rev_tn'] ."',int_paquete_n = '". $_POST['int_paquete_tn'] ."',int_caja_n = '". $_POST['int_caja_tn'] ."' int_op_n = '". $_POST['int_op_tn'] ."' " );*/

  $updateSQL = sprintf("UPDATE tbl_numeracion SET int_hasta_n=%s,int_paquete_n=%s,int_caja_n=%s WHERE int_op_n=%s",
   GetSQLValueString($_POST['int_hasta_tn'], "text"),
   GetSQLValueString($_POST['int_paquete_tn'], "int"),
   GetSQLValueString($_POST['int_caja_tn'], "text"),
   GetSQLValueString($_POST['int_op_tn'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL, $conexion1) or die(mysql_error()); 
   

  $imprimirt = $_POST['imprimirt'] =='' ? '0' :$_POST['imprimirt'];
  $insertGoTo = "sellado_control_numeracion_edit.php?id_op=". $_POST['int_op_tn'] . "&int_caja_tn=" . $_POST['int_caja_tn'] . "&imprimirt=" . $imprimirt . "";
  header(sprintf("Location: %s", $insertGoTo));


     }//alert
    }//alert
  }//alert
}//fin isset

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}

//$row_usuario = $conexion->llenarCampos('usuario',"WHERE usuario='$colname_usuario'", '','*'); ;

//CUANDO ARRANCA DESDE CERO EL INGRESO DE TIQUETES O CAMBIO DE CAJA
//$row_paquete = $conexion->buscar('Tbl_tiquete_numeracion','id_tn',$_GET['cambioPaq']);

$row_control_paquete = $conexion->buscar('tbl_orden_produccion','id_op',$_GET['id_op']); 

$row_op = $conexion->llenarCampos('tbl_tiquete_numeracion','WHERE int_op_tn='.$_GET['id_op'], 'ORDER BY id_tn DESC LIMIT 1','int_cod_empleado_tn,int_cod_rev_tn'); 
 
$row_codigo_empleado = $conexion->llenaSelect('empleado','WHERE tipo_empleado IN(7,9)','ORDER BY nombre_empleado ASC'); 
$row_revisor = $conexion->llenaSelect('empleado','WHERE tipo_empleado IN(7,9)','ORDER BY nombre_empleado ASC'); 
 
//LLENA DIV TIQUETES Y CAJAS
$cajamenos1 = $_GET['int_caja_tn'];

if(isset($_GET['cambioPaq']) && $_GET['cambioPaq']=='1'){
  $cajamenos1 = $cajamenos1-1;//para traer informacion ultimo paquete cuando es cambio de caja
  $cambioPaquete = 0; 
  $contador =  0;

  $row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$cajamenos1."'", "ORDER BY fecha_ingreso_tn DESC, hora_tn DESC, int_caja_tn DESC, int_paquete_tn DESC LIMIT 1", "*");
 $cajamenos1 = $cajamenos1+1;//+1importante ya que si es caja sin faltantes y se abre desde el listado debe tomar la caja que sigue

  /*if( $row_control_paquete['imprimiop']==1){    
        $cajamenos1 = $cajamenos1+1;//+1importante ya que si es caja sin faltantes y se abre desde el listado debe tomar la caja que sigue
   }*/

  echo 'Test1-';
}else{ 
 
  echo 'Test2-';


  //consulto si existe la caja
   $existe_caja = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$cajamenos1."'", "ORDER BY fecha_ingreso_tn DESC, hora_tn DESC, int_caja_tn DESC, int_paquete_tn DESC LIMIT 1", " * ");

      if($existe_caja['id_tn'] && $row_control_paquete['imprimiop']==1){    

           $cajamenos1 = $cajamenos1+1;//+1 importante ya que si es caja sin faltantes y se abre desde el listado debe tomar la caja que sigue

           $row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE id_tn=".$existe_caja['id_tn']." ", "ORDER BY fecha_ingreso_tn DESC, hora_tn DESC, int_caja_tn DESC, int_paquete_tn DESC ", "*");
  echo 'Test21-' ;

      }else{
  echo 'Test22';

      //Test- entra si es caja sin faltantes y trae la info del anterior caja
       $cajamenos1 = $cajamenos1;    

       $row_tiquete_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$cajamenos1."'", "ORDER BY fecha_ingreso_tn DESC, hora_tn DESC, int_caja_tn DESC, int_paquete_tn DESC LIMIT 1", "*");
      }
    
       $cambioPaquete = $row_tiquete_num['int_paquete_tn']; 
       $contador = $row_tiquete_num['contador_tn'];
}
 
$select_tiquete_num = $conexion->llenaSelect('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$cajamenos1."'",'ORDER BY int_paquete_tn DESC');

 
//REDIRECCIONA
if($row_control_paquete['int_cod_ref_op']=='096'){
  $id_op=$row_control_paquete['id_op'];
  $int_caja_t=($row_tiquete_num['int_caja_tn']);
  $paqxca=($row_tiquete_num['int_undxcaja_tn']/$row_tiquete_num['int_undxpaq_tn']);

  header ("Location: sellado_control_numeracion_edit_paqxcaja.php?id_op=$id_op&int_caja_tn=$int_caja_t&NumeroPaqxCaja=$paqxca&contador=1");
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
 
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <!--<link href="css/camposGrandes.css" rel="stylesheet" type="text/css" />-->
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

   <!-- Loading -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.js"></script>

 
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
                  <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" onSubmit="return validacion_select();">
                    <table align="center" id="tabla35">
                      <tr>
                        <td colspan="2" id="dato3">          
                          <a href="sellado_numeracion_listado.php"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO O.P" border="0"title="LISTADO O.P"/> </a> <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="titulo2">REGISTRO DE PAQUETES<strong> 
 
                            <input type="hidden" name="id_tn" id="id_tn" value="<?php echo $row_tiquete_num['id_tn']==''? '0':$row_tiquete_num['id_tn'];?>">  
                            <input name="contador_tn" type="hidden" value="<?php echo $contador+1; ?>"> 
                            <div style="display: none;" ><input name="cod_ref_n" type="text" id="cod_ref_n" value="<?php echo $row_control_paquete['int_cod_ref_op']; ?>"></strong></div>
                          </td>
                          </tr>
                          <tr>
                            <td id="dato1" nowrap><strong>PAQ N:</strong><span class="rojo_inteso">
                              <?php  
                              $restrincion = $_SESSION['superacceso'];
                              $num=$cambioPaquete+1; //echo $num;?>
                            </span>
                            <input name="int_paquete_tn" style="width:50px;" <?php //if ($restrincion!='1') {echo "readonly";}?> type="number" id="int_paquete_tn" value="<?php echo $num; ?>" maxlength="5">
                            <!--CONTROS DE PAQUETES X CAJA-->
                            <span class="rojo_inteso">  </span>


                              <?php
                                   $paqxcaj=($row_tiquete_num['int_undxcaja_tn']/$row_tiquete_num['int_undxpaq_tn']);?>
                                     <?php if($num > $paqxcaj){ $paqueteCompleto=1;   }?>

                              <!--CONTROS DE PAQUETES POR CAJA REFERENCIAS ESPECIFICAS-->
                            </td>
                            <td id="dato1"><strong>CAJA N : <span class="rojo_inteso">
                              <!--if($num==$row_op['int_paquete_n']){$num2=$num2+1;}-->
                              <?php 
                                  if($row_tiquete_num['int_caja_tn']==''){
                                     $num2=1;  
                                  } else {
                                     $num2=$cajamenos1;  
                                  } 
                               ?>
                       
                            <input name="int_caja_tn" id="int_caja_tn" style="width:60px;" type="number" <?php //if ($restrincion!='1') {echo "readonly";}?>  value="<?php echo $num2; //}?>" maxlength="5">
                          </span></strong></span>
                         <em>Imprimir todos los tick x caja</em> 
                         <input class="check" type="checkbox" name="imprimirt" <?php if($row_control_paquete['imprimiop']=='0'){echo 'disabled="disabled"'; }  ?>  id="imprimirt" value="<?php  echo $row_control_paquete['imprimiop']; ?>"  /> 
                         <?php if($num > $paqxcaj){?>
                        &nbsp;&nbsp; <input name="pesot" required="required" style="width:80px;" step="0.01" type="number" id="pesot" placeholder="Peso Caja" value="1" >
                          <?php }?> 
                        </td>

                        <td id="dato1">
                        </td>
                      </tr>
                      <tr>
                        <td id="fuente1">FECHA</td>
                        <td id="fuente1"><input name="fecha_ingreso_tn" type="date" min="2000-01-02" value="<?php echo fecha();?>" style="width:173px"/>
                          <input name="hora_tn" type="hidden" id="hora_tn" value="<?php echo Hora();?>" size="8" readonly/></td>
                        </tr>
                        <tr>
                          <td id="fuente1">ORDEN P.</td>
                          <td id="fuente1"><input type="number" name="int_op_tn" id="pswd" style=" width:80px" value="<?php  echo $ops = $row_tiquete_num['int_op_tn'] ==''? $_GET['id_op']:  $row_tiquete_num['int_op_tn']; ?>" readonly > Ref: <?php echo $row_control_paquete['int_cod_ref_op'];?></td>
                        </tr>
                        <tr>
                          <td id="fuente1">BOLSAS</td>
                          <td ><input type="number" name="int_bolsas_tn" id="pswd" min="0" value="<?php echo $row_tiquete_num['int_bolsas_tn']=='' ? $row_control_paquete['int_cantidad_op']: $row_tiquete_num['int_bolsas_tn'];?>" readonly></td>
                        </tr>

                        <tr>
                          <td id="fuente1">UNIDADES X CAJA</td>
                          <td ><input type="number" name="int_undxcaja_tn" id="int_undxcaja_tn" min="0" <?php if ($restrincion!='1') {echo "readonly";}?> value="<?php echo $row_control_paquete['int_undxcaja_op'];?>"></td> 
                        </tr>
                        <tr>
                          <td id="fuente1">UNIDADES  X PAQ.</td>
                          <td ><input type="number" name="int_undxpaq_tn" id="int_undxpaq_tn" <?php if ($restrincion!='1') {echo "readonly";}?> min="0" value="<?php echo $row_control_paquete['int_undxpaq_op'];?>" readonly> <em>Cambiar en o.p</em></td>
                        </tr>
                        <tr>
                          <td id="fuente1"><strong>DESDE</strong></td>
                          <td >
                           <?php 
                              //CONTROL DE CADENA DIVIDE NUMEROS DE LETRAS PARA INICIAR CON EL ULTIMO DEL ULTIMO PAQUETE SUMAR 1 AL DESDE DEL NUEVO PAQUETE
                              if($row_tiquete_num['int_hasta_tn']=='')
                               {
                                    $desde=$row_control_paquete['int_cantidad_op'];
                               }else{
                                    $desde=$row_tiquete_num['int_hasta_tn'];
                               }  
                                   $cadena = $desde; 
                            ?>    
           <input type="text" <?php if ($restrincion!='1') {echo "readonly";}?> name="int_desde_tn"  autofocus id="pswd" value="<?php echo $cadena; ?>"min="0" onChange="conMayusculas(this),sumaPaqSelladoAdd(this);" required></td>
         </tr>
         <tr>
          <td id="fuente1"><strong>HASTA</strong></td>
          <td ><input type="text" <?php if ($restrincion!='1') {echo "readonly";}?>  name="int_hasta_tn" id="pswd"  required value="" min="0" ></td>
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
          <td id="fuente1">PAQUETES X CAJA</td>
          <?php if($row_tiquete_num['int_undxpaq_tn']=='') 
           {
            $paqxcaja=0;
           } else  {
            $paqxcaja=($row_tiquete_num['int_undxcaja_tn']/$row_tiquete_num['int_undxpaq_tn']);
           }?>
          <td id="fuente1"><strong> <span class="paqdesde" > <?php echo $num ; ?></span><?php echo " de " . $paqxcaja;?></strong></td>
        </tr>
        <tr>
          <td colspan="2">           
          </td>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2" id="dato2">
            <div id="botonSellado" style="display: none;">
              <button type="submit" id="desabilitado1"  class="botonSellado" autofocus>GUARDAR NUMERACION</button>
              </div><!--onClick="return funcion2();"-->
            <div id="botonporCajas" style="display: none;">
              <button id="guardabotonporCajas" type="button" class="botonSelladoxCaja" >GUARDAR NUMERACION X CAJA</button><br><em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG" ></em>   
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2">&nbsp;</td>
        </tr>          
        <tr>
          <td colspan="2">
            <?php if($cambioPaquete!='0'){?>

           <div id="paqycajasnormal" style="display: none;">   
            <span class="Estilo1">PAQUETES</span>
             <div class="bordesolido" id="ventanas">
              <?php foreach($select_tiquete_num as $row_tiquete_num) {  ?>
              <p class="letraPaquete"><a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_tiquete_num['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_tiquete_num['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_tiquete_num['int_caja_tn']; ?>','700','350')" target="_top"><?php echo "PAQUETE N: ".$row_tiquete_num['int_paquete_tn']. " DESDE: ".  $row_tiquete_num['int_desde_tn']. " HASTA: ".$row_tiquete_num['int_hasta_tn'];?></a>--<a href="javascript:eliminar_sp('id_tn',<?php echo $row_tiquete_num['id_tn'];?>,'sellado_control_numeracion_edit.php')">
                <img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR TIQUETE" title="ELIMINAR TIQUETE" border="0"></a>
                
               </p>
                <?php } ?>
              </div><?php }?>
         <?php  //if($row_tiquete_num['int_caja_tn']!=''){?> 
           </div>
           <div id="cajasnormal" style="display: none;">  
              <span class="Estilo1">CAJAS</span>
                 <div class="bordesolido" id="ventanasCaja"><br><br>
                   <a href="javascript:popUp('sellado_cajas.php?id_op=<?php echo $ops; ?>','770','400')" target="_top"><strong class="letraPaquete">VER CAJAS</strong></a> 
               </div>
            </div>
          <?php  //} ?>
          <?php  //if($cambioPaquete=='0'){?> 
            <div id="tiquetxCajas" style="display: none;"  >
              <span class="Estilo1">TIQUETES X CAJAS</span>
               <div class="bordesolido" id="ventanasCaja"><br><br>
                <a href="javascript:popUp('sellado_totaltiqxcaja.php?id_op=<?php echo $ops; ?>','770','400')" target="_top"><strong class="letraPaquete">TIQ X CAJAS SIN FALTANTES</strong></a>
               </div> 
            </div>
            <?php  //} ?>  
              </div>  
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
 </div>
 
 <p>
  <input type="hidden" name="MM_insert" value="form1">
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</form>
<div id="content"></div> <!-- este bloquea pantalla evitando duplicidad -->
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
<script>
 document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
     if(e.keyCode == 13) {
       e.preventDefault();
     }
   }))
 });

 $('#desabilitado1').on('click', function(){
     if($("#pesot").val()!=''){ 
      $('#content').html('<div class="loader"></div>');
       setTimeout(function() { $(".loader").fadeOut("slow");},12000);
     }
    }); 

 $('#guardabotonporCajas').on( "click", function() { 
      $('#content').html('<div class="loader"></div>');
       setTimeout(function() { $(".loader").fadeOut("slow");},12000);
    }); 

 sumaPaqSelladoEdit();
 

 $(".botonSellado").click(function() { 
       
 var paqueteCompleto = "<?php echo $paqueteCompleto; ?>";  
     if(paqueteCompleto==1){
        cambioCaja();
     }
 });
  

function cambioCaja(){
  //var id_tn=document.form1.id_tn.value;
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
               /* swal(
                {
                  title: 'Preseleccion!',
                  text: 'Los archivos fueron gurdados correctamente!',
                  type: 'success',
                  timer: 10,
                  showConfirmButton: false
                }, 
                function() {*/
                   window.location ='sellado_control_numeracion_edit.php?id_op='+id_op+'&cambioPaq=1'+'&int_caja_tn='+caja+'&imprimirt=0';
               /*}
               );*/

              } else {
                swal("Cancelado " , " No se cambio de caja :)", "error");
              }
            });
  });
}
</script>

<script type="text/javascript">

   var imprimirt = "<?php echo $row_control_paquete['imprimiop']; ?>"; //$_GET['imprimirt'] 

  if(imprimirt==1) {$("#imprimirt").prop("checked", true);  }else{$("#imprimirt").prop("checked", false);}

   var paqxcaja = "<?php echo $paqxcaja; ?>";
   var int_caja_tn = "<?php echo $cajamenos1; ?>";
   var num = "<?php echo $num; ?>";
   var num2 = "<?php echo $num2; ?>";
   //document.form1.id_tn.value='';
   $("#botonSellado").show();
   $("#paqycajasnormal").show(300);
   $("#cajasnormal").show(300); 
   $("#faltantess").show(300);
  
    if(imprimirt==1){ 
        $("#imprimirt").prop("checked", true);  
           todosTicket(imprimirt);
           sumaPaqSelladoTiqxCaja2();
     }
    $("#imprimirt").click(function() { 
   
             todosTicket();

    });

    var int_caja_tn = int_caja_tn; 
      function todosTicket(algo){   
                 var check = document.getElementById("imprimirt");
                 //var botonporCajas = document.getElementById("botonporCajas");
                 //var element = document.getElementById("tiquetxCajas"); 
                 //var botonSellado = document.getElementById("botonSellado");
                 //var cajasnormal = document.getElementById("cajasnormal");
                 //var paqycajasnormal = document.getElementById("paqycajasnormal");
                 
                 //faltantess.style.display='none';   
                 $('.paqdesde').text(document.form1.int_paquete_tn.value);
                 //document.form1.int_caja_tn.value = (parseInt(int_caja_tn) + parseInt(1));//document.form1.int_caja_tn.value
                 //$("#imprimirt").prop("checked", true);  
                 

                 


               if (check.checked  ) { 
                
 
                  if(num!=1 && imprimirt=='0' ){
                    swal("Prohibido! " , "Debe completar la caja para continuar con caja Nueva y poder imprimir tiquetes completos por caja!", "error");
                    $("#imprimirt").prop("checked", false);
                     return false; 
                    } 
 
                     document.form1.int_paquete_tn.value = paqxcaja;
                     $("#tiquetxCajas").show(300);   
                     $("#botonporCajas").show();
                     //$("#imprimirt").prop("checked", true); 
                     $("#faltantess").hide();  
                     $("#botonSellado").hide(); 
                     $("#element").show(300); 
                     $("#paqycajasnormal").hide(); 
                     $('.paqdesde').text(document.form1.int_paquete_tn.value); 
                     $("#pesot").show();
                     $("#int_paquete_tn").val($("#int_undxcaja_tn").val() / $("#int_undxpaq_tn").val()); 


                     document.form1.imprimirt.value = 1;
                     //document.form1.int_caja_tn.value = (parseInt(int_caja_tn) + parseInt(1)); 
                      sumaPaqSelladoTiqxCaja2();//suma al hasta  
                     //sumaPaqSelladoAdd();//suma al hasta  
                       
                  } else {
                        $("#tiquetxCajas").hide();
                        $("#botonSellado").show(); 
                        //$("#imprimirt").prop("checked", false);
                        $("#faltantess").show(300);  
                        $("#botonporCajas").hide(); 

                        document.form1.int_paquete_tn.value = num;
                        $('.paqdesde').text(num); 
                        $("#element").show(300);
                        $("#paqycajasnormal").show(300);
                        document.form1.int_caja_tn.value = num2;
                        document.form1.imprimirt.value = 0;
                        sumaPaqSelladoAdd();//suma al hasta
                 } 

      }
 
 $("#guardabotonporCajas").on( "click", function() {  
      cambioCaja();
      guardarTiq();
 });

 function guardarTiq(){
   //$( "#guardabotonporCajas" ).on( "click", function() {    
 
      if($("#int_paquete_tn").val()=='' || $("#int_caja_tn").val()=='' || $("#fecha_ingreso_tn").val()=='' || $("#int_op_tn").val()=='' || $("#int_bolsas_tn").val()=='' || $("#int_undxcaja_tn").val()=='' || $("#int_undxpaq_tn").val()=='' || $("#int_desde_tn").val()=='' || $("#int_hasta_tn").val()=='' || $("#operario").val()=='' || $("#revisor").val()=='' ) 
      {
        swal("Error", "Hay campos vacios! :)", "error"); 
        return false;
      }else{ 
         $("#imprimirt").prop("checked", true);
         var id_op=document.form1.int_op_tn.value;
         var imprimirt="<?php echo $row_control_paquete['imprimiop']; ?>";
         var caja = (parseInt(int_caja_tn) + parseInt(1));//document.form1.int_caja_tn.value;  

         guardarGeneral("AjaxControllers/Actions/guardar.php"); 
         
          var id_tn=document.form1.id_tn.value;
           
          window.location ='sellado_control_numeracion_edit.php?id_op='+id_op+'&cambioPaq=1'+'&int_caja_tn='+caja+'&imprimirt='+imprimirt;
          

         document.form1.paqCompleto.value = 1;
         document.form1.imprimirt.value = "<?php echo $row_control_paquete['imprimiop']; ?>"; 
        
            

      } 
     
      //});

 }



 function sumaPaqSelladoTiqxCaja2() { 
  var desde=document.form1.int_desde_tn.value;
  var caja=document.form1.int_undxcaja_tn.value;
  var codigos = divideCadenas(desde); 
  var desde = codigos[0];
  var cadena = codigos[1];    
  var tnum=parseInt(desde)+parseInt(caja)-parseInt(1);
  document.form1.int_hasta_tn.value=cadena+tnum;
}

 

</script>
<?php
mysql_free_result($usuario); 
mysql_free_result($Result2);
mysql_free_result($Result1);
mysql_free_result($Result3);
mysql_free_result($usuario);
mysql_free_result($control_paquete);
mysql_free_result($op);
mysql_free_result($codigo_empleado);
mysql_free_result($tiquete_num);
mysql_free_result($paquet);
mysql_free_result($caja_num);

/*mysql_close($conexion1);


mysql_free_result($usuario); 
mysql_free_result($codigo_empleado);
mysql_free_result($op);
mysql_free_result($tiquete_num);
mysql_free_result($paquete);
mysql_free_result($control_paquete);
*/
 
 

?>