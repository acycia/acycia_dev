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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$maxRows_cotizacion = 20;
$pageNum_cotizacion = 0;
if (isset($_GET['pageNum_cotizacion'])) {
  $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
}
$startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;
if($_GET['orden']==''){
	$orden="N_referencia_c";
	}else{
$orden=$_GET['orden'];
	}

//$row_cotizacion = $conexion->buscarListar("Tbl_cotiza_bolsa","*","ORDER BY $orden DESC","WHERE B_estado <> '2'",$maxRows_cotizacion,$pageNum_cotizacion,"" );

mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotiza_bolsa WHERE B_estado <> '2' ORDER BY $orden DESC";
$query_limit_cotizacion = sprintf("%s LIMIT %d, %d", $query_cotizacion, $startRow_cotizacion, $maxRows_cotizacion);
$cotizacion = mysql_query($query_limit_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);

if (isset($_GET['totalRows_cotizacion'])) {
  $totalRows_cotizacion = $_GET['totalRows_cotizacion'];
} else {
  $all_cotizacion = mysql_query($query_cotizacion);
  $totalRows_cotizacion = mysql_num_rows($all_cotizacion);
}
$totalPages_cotizacion = ceil($totalRows_cotizacion/$maxRows_cotizacion)-1;
 

$row_cliente = $conexion->llenaSelect('cliente',"","ORDER BY nombre_c ASC");  

$row_numero = $conexion->llenaSelect('tbl_referencia',"WHERE estado_ref='1'","order by CONVERT(cod_ref, SIGNED INTEGER)  desc"); 

$row_tipobolsa = $conexion->llenaListas('tbl_referencia',"WHERE estado_ref='1'","GROUP BY tipo_bolsa_ref ORDER BY tipo_bolsa_ref DESC"," tipo_bolsa_ref");  

$queryString_cotizacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_cotizacion") == false && 
        stristr($param, "totalRows_cotizacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_cotizacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_cotizacion = sprintf("&totalRows_cotizacion=%d%s", $totalRows_cotizacion, $queryString_cotizacion);
 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
 
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
 <?php echo $conexion->header('listas'); ?>

             <form action="referencia_precio2.php" method="get" name="consulta">
              <table class="table table-bordered table-sm">
                <tr>
                  <td colspan="6" nowrap="nowrap" id="codigo" >CODIGO : R1 - F03</td>
                  <td colspan="4" nowrap="nowrap" id="titulo2">REFERENCIAS COTIZADAS</td>
                  <td colspan="2" nowrap="nowrap" id="codigo">VERSION : 2</td>
                </tr>
                <tr>
                  <td colspan="12" id="dato2">
                    <select class="busqueda selectsMini" name="cod_ref" id="cod_ref">
                     <option value="0">Referencia</option>
                     <?php  foreach($row_numero as $row_numero ) { ?>
                      <option value="<?php echo $row_numero['cod_ref']?>"><?php echo $row_numero['cod_ref']?></option>
                    <?php } ?>
                  </select>

                  <select class="busqueda" style="width:180px" name="bolsa" id="bolsa">
                    <option value="0">Tipo Bolsa</option>
                    <?php  foreach($row_tipobolsa as $row_tipobolsa ) { ?>
                      <option value="<?php echo $row_tipobolsa['tipo_bolsa_ref']?>"><?php echo $row_tipobolsa['tipo_bolsa_ref']?></option>
                    <?php } ?>
                  </select>
                  
                 
                     <select id='id_c' name='id_c' class="selectsMedio">
                       <option value=''>Seleccione el Cliente</option>
                     </select>
                 

                  


                 <select class="busqueda selectsMini" name="tipo_ref" id="tipo_ref">
                  <option value="" selected="selected">Todas ref.</option>
                  <option value="0">Existentes</option>
                  <option value="1">Genericas</option>
                </select>

              </td>
            </tr>
            <tr>
              <td colspan="12" id="dato2">

                Solapa
                <input class="selectsMini"type="number" name="solapa" id="solapa" min="0.00" step="0.01" value=""/> 
                Ancho
                <input class="selectsMini"type="number" name="ancho" id="ancho" min="0.00" step="0.01" value="0"/>
                Largo
                <input class="selectsMini"type="number" name="largo" id="largo" min="0.00" step="0.01" value="0"/>
                Calibre
                <input class="selectsMini"type="number" name="calibre" id="calibre" min="0.00" step="0.01" value="0"/>

              </td> 
            </tr>
            <tr>
              <td colspan="6" id="dato2">
               <input class="botonFinalizar" type="button" value="Excel_RefCliente" onclick="envioListados()" /> <!-- onclick="window.location = 'referencia_precio_excel.php?id_ref=<?php echo '0'; ?>&amp;id_c=<?php echo '0'; ?>&amp;ref_clientes=<?php echo '1'; ?>'" -->
              </td>
              <td colspan="6" id="dato2"> 
                <input class="botonGeneral" type="submit" name="Submit" value="FILTRO"/> 
             </td>
           </tr> 
         </table>
         <table class="table table-bordered table-sm">
          <tr>
            <td colspan="5" id="dato2"><em style="color: red;" >Si filtra por tipo de bolsa este listado tardara bastante ya que contiene muchas cotizaciones</em>
            <td colspan="7" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?>
              <a href="cotizacion_general_menu.php"><img src="images/mas.gif" alt="ADD COTIZACION" title="ADD COTIZACION" border="0" style="cursor:hand;"/></a><a href="cotizaciones_clientes.php"></a><?php } ?>
              <a href="referencias.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS"border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS"title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="cotizacion_general_menu.php"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>

            </tr>  
            <tr id="tr1">
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "N_referencia_c";?>">N&deg; REF</a></td>
              <td nowrap="nowrap" id="titulo4">TIPO REF</td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "N_cotizacion";?>">COTIZ</a></td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "Str_nit";?>">Cliente</a></td>
              <td nowrap="nowrap" id="titulo4">TIPO</td>
              <td nowrap="nowrap" id="titulo4">BOLSA</td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "N_ancho";?>">Ancho</a></td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "N_alto";?>">Largo</a></td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "N_solapa";?>">Solapa</a></td>
              <td nowrap="nowrap" id="titulo4">Bolsillo</td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "N_calibre";?>">Calibre</a></td>
              <td nowrap="nowrap" id="titulo4">Precio sin impuesto $</td>
              <td nowrap="nowrap" id="titulo4">Precio Con impuesto $</td>
              <td nowrap="nowrap" id="titulo4"><a href="referencia_precio.php?orden=<?php echo "fecha_creacion";?>">Fecha Creacion</a></td>
              <td nowrap="nowrap" id="titulo4">ESTADO</td>
            </tr>
            <?php do { ?>
              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "8"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_referencia_c']; ?></a></td>
                <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000">
                 <?php 
/*        $refB=$row_cotizacion['N_referencia_c'];
        $sqlref="SELECT B_generica FROM Tbl_referencia WHERE cod_ref='$refB'"; 
        $resultref=mysql_query($sqlref); 
        $numref=mysql_num_rows($resultref); 
        if($numref >= '1') 
        { $B_generica=mysql_result($resultref,0,'B_generica'); 
	  if($B_generica=='1'){echo "Generica";}else if($B_generica=='0'){echo "Existente";}
  }*/
  if($row_cotizacion['B_generica']=='0'){echo "Existente";}else{echo "Generica";};
  ?></a>
</td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_cotizacion']; ?></a></td>
<td id="talla1"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000">
 <?php 
 $nit_c=$row_cotizacion['Str_nit'];
 $sqln="SELECT nombre_c FROM cliente WHERE nit_c='$nit_c'"; 
 $resultn=mysql_query($sqln); 
 $numn=mysql_num_rows($resultn); 
 if($numn >= '1') 
  { $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo $nit_cliente_c; }
?>
</a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['Str_tipo_coextrusion']; ?></a></td>
<td id="dato2"><?php 
/*$ref=$row_cotizacion['N_referencia_c'];
$sqltipob="SELECT tipo_bolsa_ref FROM Tbl_referencia WHERE cod_ref='$ref'"; 
$resulttipob=mysql_query($sqltipob); 
$numtipob=mysql_num_rows($resulttipob); 
if($numtipob >= '1') 
  { $tipo_bolsa=mysql_result($resulttipob,0,'tipo_bolsa_ref'); echo $tipo_bolsa; }else{echo "no definida";}*/
echo $row_cotizacion['tipo_bolsa'];
?></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_ancho']; ?></a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_alto']; ?></a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_solapa']; ?></a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php if($row_cotizacion['N_tamano_bolsillo']!=''){ echo $row_cotizacion['N_tamano_bolsillo'];}else{echo "0.00";}  ?></a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_calibre']; ?></a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_precio']; ?></a></td>
<td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_precio_old']; ?></a></td>
<td id="dato1"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['fecha_creacion']; ?></a></td>
<td id="dato1"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&amp;Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>&amp;case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php 
if (!(strcmp("0", $row_cotizacion['B_estado']))) {echo "Pendiente";}
if (!(strcmp("1", $row_cotizacion['B_estado']))) {echo "Aceptada";}
if (!(strcmp("2", $row_cotizacion['B_estado']))) {echo "Rechazada";}
if (!(strcmp("3", $row_cotizacion['B_estado']))) {echo "Obsoleta";} ?></a></td>
</tr>
<?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" colspan="3"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
    <?php } // Show if not first page ?>
  </td>
  <td id="dato1" colspan="3"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
    <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
  <?php } // Show if not first page ?>
</td>
<td id="dato1" colspan="3"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
<?php } // Show if not last page ?>
</td>
<td id="dato1" colspan="3"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, $totalPages_cotizacion, $queryString_cotizacion); ?>">&Uacute;ltimo</a>
<?php } // Show if not last page ?>
</td>
</tr>
</table>
</form>
</td>
  </tr>
</table>
 
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<script type="text/javascript">
  
  function envioListados(){
 
     window.location.href = 'referencia_precio_excel.php?id_c='+$("#id_c").val()+'&cod_ref='+$("#cod_ref").val()+'&bolsa='+$("#bolsa").val()+'&id_c='+$("#id_c").val()+'&tipo_ref='+$("#tipo_ref").val()+'&solapa='+$("#solapa").val()+'&ancho='+$("#ancho").val()+'&largo='+$("#largo").val()+'&calibre='+$("#calibre").val();
 
  }



$(document).ready(function(){  
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
                      var5:"nit_c",
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

<!-- $row_cliente = $conexion->llenaSelect('cliente',"","ORDER BY nombre_c ASC");  
 <select class="busqueda " name="id_c" id="id_c" style="width:250px">
   <option value="">Seleccione el Cliente</option>
   <?php  foreach($row_cliente as $row_cliente ) { ?>
    <option value="<?php echo $row_cliente['nit_c']?>"><?php $cad = ($row_cliente['nombre_c']); echo $cad;?></option>
  <?php } ?>
</select> -->
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion);

mysql_free_result($numero);

?>