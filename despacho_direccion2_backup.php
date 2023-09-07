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
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_op = $conexion->llenaSelect('Tbl_orden_produccion',"WHERE b_estado_op > '0'","ORDER BY id_op DESC");  

$colname_op_n = "-1";
if (isset($_GET['id_op'])) {
  $colname_op_n = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_op_n = sprintf("SELECT Tbl_numeracion.int_undxcaja_n,Tbl_numeracion.int_undxpaq_n,Tbl_numeracion.int_op_n,Tbl_orden_produccion.int_cod_ref_op,
Tbl_orden_produccion.int_cliente_op,Tbl_orden_produccion.str_numero_oc_op FROM Tbl_orden_produccion, Tbl_numeracion WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.id_op=Tbl_numeracion.int_op_n ",$colname_op_n);
$op_n = mysql_query($query_op_n, $conexion1) or die(mysql_error());
$row_op_n = mysql_fetch_assoc($op_n);
$totalRows_op_n = mysql_num_rows($op_n);

/*$colname_caja_rm = "-1";
if (isset($_GET['id_op'])) {
  $colname_caja_rm  = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caja_num = sprintf("SELECT DISTINCT int_caja_tn,int_op_tn FROM tbl_tiquete_numeracion_backup WHERE int_op_tn=%s ORDER BY int_caja_tn  DESC",$colname_caja_rm);
$caja_num = mysql_query($query_caja_num, $conexion1) or die(mysql_error());
$row_caja_num = mysql_fetch_assoc($caja_num);
$totalRows_caja = mysql_num_rows($caja_num);*/



mysql_select_db($database_conexion1, $conexion1);
$query_rd = "SELECT id_d FROM Tbl_despacho ORDER BY id_d DESC";
$rd = mysql_query($query_rd, $conexion1) or die(mysql_error());
$row_rd = mysql_fetch_assoc($rd);
$totalRows_rd = mysql_num_rows($rd);

$maxRows_numeracion = 20;
$pageNum_numeracion = 0;
if (isset($_GET['pageNum_numeracion'])) {
  $pageNum_numeracion = $_GET['pageNum_numeracion'];
}
$startRow_numeracion = $pageNum_numeracion * $maxRows_numeracion;
mysql_select_db($database_conexion1, $conexion1);
$id_op = $_GET['id_op'];
//Filtra todos vacios
if($id_op== '')
{
$query_numeracion = "SELECT int_op_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn FROM tbl_tiquete_numeracion_backup ORDER BY int_op_tn, int_caja_tn, int_paquete_tn  DESC";
}
//Filtra id_op lleno
if($id_op != '')
{
$query_numeracion = "SELECT int_op_tn,id_tn,fecha_ingreso_tn,int_caja_tn,int_paquete_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,ref_tn,imprime FROM tbl_tiquete_numeracion_backup WHERE int_op_tn='$id_op' AND id_despacho IS NULL ORDER BY int_op_tn, int_caja_tn, int_paquete_tn  DESC";
}
//$query_limit_numeracion = sprintf("%s LIMIT %d, %d", $query_numeracion, $startRow_numeracion, $maxRows_numeracion);
$numeracion = mysql_query($query_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);

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

//session_start();
 ?>
 <?php 
  $idop=$row_op_n['int_op_n']; 
  $sqlc="SELECT * FROM Tbl_orden_produccion,cliente WHERE Tbl_orden_produccion.id_op='$idop' AND Tbl_orden_produccion.str_nit_op=cliente.nit_c"; 
  $resultc=mysql_query($sqlc); 
  $numc=mysql_num_rows($resultc); 
  if($numc >= '1') 
  { 
  $nit_c=mysql_result($resultc,0,'nit_c'); $nit_c;
  $nombre_c=mysql_result($resultc,0,'nombre_c');$nombre_c = htmlentities($nombre_c);
   } ?>

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
function seleccionar(){
      var undxcaja=parseInt(document.seleccion.undxcaja.value);
    var undxpaq=parseInt(document.seleccion.undxpaq.value);
    var cambiar=(document.seleccion.elements);
    //var cambiar=document.getElementById('cambia')
    var cont=0, cajas,caja,suma,paq,div,bolsas;
   for (i=0;i<cambiar.length;i++) 
      if(cambiar[i].type == "checkbox"){ 
         var todos=cambiar[i].checked=1 
        suma=todos+i;
        cont=suma-2;
        div=undxcaja/undxpaq;
        caja=(cont/div);
        cajas=Math.round(caja);
        document.seleccion.cajas_d.value=cajas;
        bolsas=cont*undxpaq;
            document.seleccion.cantidad_d.value=bolsas;
    }             
}
function deseleccionar(){
   var cambiar=(document.seleccion.elements);
   for (i=0;i<cambiar.length;i++) 
      if(cambiar[i].type == "checkbox"){ 
         cambiar[i].checked=0;
            document.seleccion.cajas_d.value=0;
        document.seleccion.cantidad_d.value=bolsas=0; 
    }   
}

function contar() {
//var checkboxes = $('.check');
//var limite=3;
      var undxcaja=parseInt(document.seleccion.undxcaja.value);
    var undxpaq=parseInt(document.seleccion.undxpaq.value);
    var undxpaq=parseInt(document.seleccion.undxpaq.value);
    var cambiar=(document.seleccion.elements);    
        var cont=0,cajas,caja,paq,div,bolsas;
  for (i=0; i<=cambiar.length; i++)
        {
         if (cambiar[i].checked)
       {         
          cont=cont+1;
        div=undxcaja/undxpaq;
        caja=(cont/div);
          cajas=Math.round(caja);
          document.seleccion.cajas_d.value=cajas;
          bolsas=cont*undxpaq;
            document.seleccion.cantidad_d.value=bolsas;
       }
       
    /*   if (cont>limite)
        {
        alert('no se puede mas de: '+limite);
        document.seleccion.cambiar[i].checked=false;
        break;
        }*/
      }   
  } 
</script>
</head>
<body>
  <script>
        $(document).ready(function() { $(".busqueda").select2(); });
    </script> 
  <?php echo $conexion->header('listas'); ?>
<form action="despacho_direccion2.php" method="get" name="form1" >
<table class="table">
<tr>
<td colspan="5" id="titulo2">CREACION DE DESPACHO BACKUP</td>
</tr>
<tr>
  <td colspan="5" id="titulo2">REF/O.C/O.P

    <select class="busqueda selectsGrande" name="id_op" id="id_op" onChange="if(form1.id_op.value) { consulta_sellado_op(); }else { alert('Debe Seleccionar una O.P')}">
       <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione REF / O.C / O.P</option>
       <?php  foreach($row_op as $row_op ) { ?>
        <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['int_cod_ref_op']." / O.C: ".$row_op['str_numero_oc_op']." / O.P: ".$row_op['id_op']?></option>
    <?php } ?>  
    </select>


  <a href="despacho_faltantes.php"><img src="images/o.gif" style="cursor:hand;" alt="DESPACHOS FALTANTES" title="DESPACHOS FALTANTES" border="0" /></a></td>
  </tr> 
<tr>
  <td colspan="5" id="dato1">Nota: Los paquetes en color <strong class="rojo_peq"> rojo</strong>, es que tiene faltantes.</td>
  <td><h6> EL CLIENTE ES: <?php echo $nombre_c;?></h6></td>
</tr>
 
</table>
</form>
<form name="seleccion" action="despacho_insert.php" method="POST"  onSubmit="MM_validateForm('ciudad_rd','','R','cajas_rd','','RisNum','cantidad_rd','','RisNum','direccion_rd','','R','cliente_rd','','R');return document.MM_returnValue" >
<?php if ($row_numeracion['int_op_tn']!=''){ ?>
<div style="height:600px;width:990px;overflow:scroll;">
<table class="table">
  <tr>
    <td colspan="2" id="dato1"><input type="hidden" name="accion" value="1"></td>
    <td colspan="4"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "DESPACHO REALIZADO"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "DEBE SELECCIONAR CAJAS PARA DESPACHAR !"; ?> </div> <?php }?></td>
    <td colspan="5" id="dato3">&nbsp;</td>
  </tr>   
  <tr id="tr1">
    <td id="titulo4">P. <input name="chulo1"  type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar() } else{ deseleccionar() } "/></td>
<!--    <td id="titulo4">C. </td>
--> <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">REF.</td>
    <td nowrap="nowrap"id="titulo4">FECHA</td>
    <td nowrap="nowrap"id="titulo4">N&deg; Caja </td>     
    <td nowrap="nowrap"id="titulo4">N&deg; Paqu. </td>
    <td nowrap="nowrap"id="titulo4">Und x Paq. </td>              
    <td nowrap="nowrap"id="titulo4">Und x Caja </td>               
    <td nowrap="nowrap"id="titulo4">Desde </td> 
    <td nowrap="nowrap"id="titulo4">Hasta </td>           
    <!-- <td nowrap="nowrap"id="titulo4">CLIENTE</td> -->
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
  <td id="dato2"><input name="cambiar[]" onChange="contar()" type="checkbox" value="<?php echo $row_numeracion['id_tn']; ?>" /></td>
  <!--<td id="dato2"><input name="cambiar2[]"  onChange="contar_cajas()" type="radio" value="<?php echo $row_caja_num['id_tn']; ?>" /></td>-->
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_op_tn']; ?></a></td>
      <td nowrap="nowrap" id="dato2">
        <a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
           echo $ref = $row_numeracion['ref_tn']; ?>
       </a>
  </td>      
      <td id="dato2" nowrap><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['fecha_ingreso_tn']; ?></a>
      </td>
      <td id="dato2">
         
        <a href="javascript:popUp('sellado_control_cajas_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','1200','800')" target="_top"><?php echo $row_numeracion['int_caja_tn']; ?></a>
  
      </td>       
     <td bgcolor="#FFFFFF" id="dato2">
  <?php 
 
    $opF=$row_numeracion['int_op_tn'];
    $paqF=$row_numeracion['int_paquete_tn'];
    $cajaF=$row_numeracion['int_caja_tn'];
    $idtnF=$row_numeracion['id_tn'];
   
    //$sqlfal="SELECT * FROM Tbl_faltantes WHERE id_tn=f=$opF AND int_paquete_f=$paqF AND int_caja_f=$cajaF ORDER BY int_inicial_f ASC";
    $sqlfal="SELECT * FROM Tbl_faltantes WHERE id_tn_f=$idtnF  ORDER BY int_inicial_f ASC";  
    $resultfal=mysql_query($sqlfal); 
    $numfal=mysql_num_rows($resultfal); 
    if($numfal >= '1')
    {  
  ?> 
  <!-- si lleva faltantes se ve rojo -->
  <a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_numeracion['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','600','400')" class="rojo_peq" target="_top"><?php echo $row_numeracion['int_paquete_tn']; ?></a>
  <?php } else { ?>

   <?php if($row_numeracion['imprime']==1): ?>
            <a href="javascript:popUp('sellado_totaltiqxcaja_colas.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','1200','800')" target="_top"><?php echo $row_numeracion['int_paquete_tn']; ?></a>
          <?php else: ?>  
           <a href="javascript:popUp('sellado_control_numeracion_vista.php?id_op=<?php echo $row_numeracion['int_op_tn']; ?>&int_paquete_tn=<?php echo $row_numeracion['int_paquete_tn']; ?>&int_caja_tn=<?php echo $row_numeracion['int_caja_tn']; ?>','600','400')" target="_top"><?php echo $row_numeracion['int_paquete_tn']; ?></a> 
   <?php endif; ?>  

      <?php }?>
      </td> 
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_undxpaq_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_undxcaja_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_desde_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_numeracion['int_hasta_tn']; ?></a></td>
      <td nowrap="nowrap" id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_numeracion['int_op_tn'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
/*  $id_op=$row_numeracion['int_op_tn'];
  $sqln="SELECT Tbl_orden_produccion.id_op,Tbl_orden_produccion.str_nit_op,cliente.nit_c,cliente.nombre_c FROM Tbl_orden_produccion,cliente WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.str_nit_op=cliente.nit_c"; 
  $resultn=mysql_query($sqln); 
  $numn=mysql_num_rows($resultn); 
  if($numn >= '1') 
  { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = htmlentities ($nombre_cliente_c); echo $ca; }
  else { echo ""; } */
  ?>
    </a></td>
    </tr>
    <?php } while ($row_numeracion = mysql_fetch_assoc($numeracion)); ?>
</table>
</div>
<?php  }//IMPRIME TABLA SOLO SI HAY CONSULTA ?>
<table class="table table-bordered table-sm">
<tr>
  <td colspan="2" id="fuente3"><input type="hidden" name="id_op" id="id_op" value="<?php echo $_GET['id_op'] ?>">
    <input name="fecha_d" type="hidden" id="fecha_d" value="<?php echo date("Y-m-d");  ?>" size="10" />
    <input name="id_d" type="hidden" id="id_d" value="<?php $id_d=$row_rd['id_d']+1; echo $id_d;?>" size="10" />
    Ciudad</td>
  <td id="fuente2">
<!--<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" />
<input type="checkbox"class="check" name="cambiar" onChange="contar()" /><input type="button" name="Submit" value="contar" onClick="contar();">-->Direccion</td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" id="fuente3"><input type="text" name="ciudad_d" id="ciudad_d" required autofocus onChange="conMayusculas(this)"></td>
  <td id="fuente2"><textarea name="direccion_d" id="direccion_d" cols="45" rows="1" required onChange="conMayusculas(this)"><?php  
    $idoc=$row_op_n['str_numero_oc_op']; 
  $sqld="SELECT * FROM Tbl_orden_compra WHERE Tbl_orden_compra.str_numero_oc='$idoc'"; 
  $resultd=mysql_query($sqld); 
  $numd=mysql_num_rows($resultd); 
  if($numd >= '1') 
  { $direccion=mysql_result($resultd,0,'str_dir_entrega_oc'); echo $dir = $direccion; }
  else { echo "N.A";  }?>
  </textarea></td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td id="fuente3">Cajas / </td>
  <td id="fuente3">Cantidad/und</td>
  <td id="fuente2">Cliente</td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td id="fuente3">
  <input type="hidden" name="undxcaja" id="undxcaja" value="<?php echo $row_op_n['int_undxcaja_n'];?>" size="2">
  <input type="hidden" name="undxpaq" id="undxpaq" value="<?php echo $row_op_n['int_undxpaq_n'];?>" size="2">
  <input type="number" name="cajas_d" id="cajas_d" value="" style="width:50px"></td>
  <td id="fuente3"><input type="number" name="cantidad_d" required id="cantidad_d" value=""></td>
  <td id="fuente2">
   
  <input type="hidden" name="cliente_d" id="cliente_d" onChange="conMayusculas(this)" value="<?php echo $nit_c;?>">   
    <textarea name="cliente" cols="45" rows="1" readonly id="cliente" onChange="conMayusculas(this)"><?php echo htmlentities ($nombre_c); ?>
    </textarea>
    
    
    </td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" id="fuente3">Numeracion Desde</td>
  <td id="fuente2">Orden de Compra</td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" id="fuente3"><input type="text" name="desde_d" id="desde_d" onChange="conMayusculas(this)"></td>
  <td id="fuente2"><textarea name="oc_d" id="oc_d" cols="45" rows="1" required onChange="conMayusculas(this)">
<?php echo $row_op_n['str_numero_oc_op']; ?>
  </textarea></td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" id="fuente3">Numeracion Hasta</td>
  <td id="fuente2">Referencia Cliente</td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" id="fuente3"><input type="text" name="hasta_d" id="hasta_d" onChange="conMayusculas(this)"></td>
  <td id="fuente2">
       <?php $ref=$row_op_n['int_cod_ref_op']; $id_c=$row_op_n['int_cliente_op'];
      $sqlrf="SELECT * FROM Tbl_refcliente WHERE id_c_rc='$id_c' AND int_ref_ac_rc='$ref'"; 
      $resultrf=mysql_query($sqlrf); 
      $numrf=mysql_num_rows($resultrf); 
      if($numrf >= '1') 
      { $desc_rf=mysql_result($resultrf,0,'str_descripcion_rc'); $carf =($desc_rf); }  ?>
      <textarea name="ref_d" id="ref_d" cols="45" rows="1" required onChange="conMayusculas(this)"><?php echo $carf; ?> </textarea></td>
  <td id="fuente3">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" id="fuente">&nbsp;</td>
  <td id="fuente">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" id="fuente2"><input class="botonGeneral" name="submit" type="submit" value="GUARDAR"/></td>
  <td id="fuente2">&nbsp;</td>
</tr>
</table>
<!--<input type="hidden" name="MM_insert" value="seleccion">-->
</form>
<table border="0" width="50%" align="center">
  <!--<tr>
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
  </tr>-->
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
 

<?php
mysql_free_result($usuario);

mysql_free_result($numeracion);

mysql_free_result($op);

mysql_free_result($rd);

mysql_free_result($op_n);

//mysql_free_result($caja_num);
?>