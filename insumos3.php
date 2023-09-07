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


/*$maxRows_registros = 40;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;*/

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 


if (isset($_GET['cod_ref'])&& $_GET['cod_ref']!='0'&& $_GET['descrip']=='0' && $_GET['clase']=='0' && $_GET['quimicos']=='0') {
  $cod_ref = $_GET['cod_ref']; 
  $busqueda = " and codigo_insumo = '$cod_ref'"; 
}else if(isset($_GET['descrip'])&& $_GET['descrip']!='0'&& $_GET['cod_ref']=='0' && $_GET['clase']=='0' && $_GET['quimicos']=='0'){
  $descrip = $_GET['descrip']; 
  $busqueda = " and id_insumo = '$descrip'"; 
}else if(isset($_GET['descrip'])&& isset($_GET['cod_ref'])&& $_GET['descrip']!='0'&& $_GET['cod_ref']!='0' && $_GET['clase']=='0' && $_GET['quimicos']=='0'){
  $cod_ref = $_GET['cod_ref'];
  $descrip = $_GET['descrip']; 
  $busqueda = "and codigo_insumo = '$cod_ref' and id_insumo = '$descrip'"; 
}else if(isset($_GET['clase'])&& $_GET['descrip']=='0' && $_GET['cod_ref']=='0' && $_GET['clase']!='0' && $_GET['quimicos']=='0'){
  $clase = $_GET['clase'];
  $busqueda = " and clase_insumo = '$clase' "; 
}else if(isset($_GET['quimicos'])&& $_GET['descrip']=='0' && $_GET['cod_ref']=='0' && $_GET['clase']=='0' && $_GET['quimicos']!='0'){
  $quimicos = $_GET['quimicos'];
  $busqueda = " and quimicos = '$quimicos' "; 
}else{

  $busqueda =""; 
}

//$row_registros = $conexion->buscarListar("insumo","*","ORDER BY CONVERT(descripcion_insumo, SIGNED INTEGER) ASC","",$maxRows_registros,$pageNum_registros,"WHERE estado_insumo ='0' ".$busqueda );

//$row_registros = $conexion->llenaListas("insumo","WHERE estado_insumo ='0'".$busqueda ,"ORDER BY descripcion_insumo ASC","*" ); 

mysql_select_db($database_conexion1, $conexion1);
$query_registros = "SELECT * FROM insumo WHERE estado_insumo='0' $busqueda ORDER BY descripcion_insumo ASC";
$registros = mysql_query($query_registros, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($registros);
$totalRows_registros = mysql_num_rows($registros);



$row_codigo = $conexion->llenaListas('insumo',"WHERE estado_insumo ='0' ","ORDER BY CONVERT(codigo_insumo, SIGNED INTEGER) DESC","codigo_insumo");

$row_descripcion = $conexion->llenaListas('insumo',"WHERE estado_insumo ='0' ","ORDER BY descripcion_insumo ASC","id_insumo,descripcion_insumo"); 

$row_clase = $conexion->llenaListas('clase',"","ORDER BY nombre_clase ASC","id_clase,nombre_clase "); 

//echo $row_registros['codigo_insumo'];die;
/*if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('insumo'); 
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
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);*/
?><html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="StyleSheet" href="css/formato.css" type="text/css">
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/listado.js"></script>

    <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
<body>
  <script>
    $(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div align="center">

    <table >
      <tr>
        <td><form action="insumos3.php" method="get" name="consulta">
          <select name="cod_ref" id="cod_ref"  class="busqueda selectsMedio">
            <option value="0"<?php if (!(strcmp(0, $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>CODIGO</option>
            <?php foreach ($row_codigo as $row_codigo) { ?>
              <option value="<?php echo $row_codigo['codigo_insumo']?>"<?php if (!(strcmp($row_codigo['codigo_insumo'], $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo['codigo_insumo'];?>
            </option>
          <?php } ?>
        </select>
        <select name="descrip" id="descrip"  class="busqueda selectsGrande">
          <option value="0"<?php if (!(strcmp(0, $_GET['descrip']))) {echo "selected=\"selected\"";} ?>>DESCRIPCION</option>
          <?php foreach ($row_descripcion as $row_descripcion) { ?>
            <option value="<?php echo $row_descripcion['id_insumo']?>"<?php if (!(strcmp($row_descripcion['id_insumo'], $_GET['descrip']))) {echo "selected=\"selected\"";} ?>><?php echo $row_descripcion['descripcion_insumo'];?>
          </option>
        <?php } ?>
      </select>  
      <select name="clase" id="clase" class="busqueda selectsMedio"> 
        <option value="0"<?php if (!(strcmp(0, $_GET['clase']))) {echo "selected=\"selected\"";} ?>>CLASE</option>
        <?php foreach ($row_clase as $row_clase) { ?>
          <option value="<?php echo $row_clase['id_clase']?>"<?php if (!(strcmp($row_clase['id_clase'], $_GET['clase']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clase['nombre_clase'];?>
        </option>
      <?php } ?> 
    </select>
    <select name="quimicos" id="quimicos">
     <option value="0">SELECCIONE...</option>
     <option value="SUSTANCIAS QUIMICAS">SUSTANCIAS QUIMICAS</option>
     <option value="NA">NA</option>    
   </select> &nbsp;&nbsp; 

   <input type="submit" class="botonGMini" style='width:90px; height:25px' name="Submit" value="FILTRO" />
 </form>
</td>
</tr>
</table>
<br>
</div>
<div align="center">
 
    <table >
      <tr id="tr1">
        <td id="titulo2"><a href="insumo_add.php" target="_top"><img src="images/mas.gif" alt="ADD INSUMO" border="0" style="cursor:hand;"/></a> <a href="insumos_inactivo.php" target="_top"><img src="images/i.gif" alt="INSUMOS INACTIVOS" title="INSUMOS INACTIVOS" border="0" style="cursor:hand;"/></a> INSUMOS <a href="proveedor_insumo.php" 0
          ="_top"><img src="images/cliente.gif" alt="ADD INSUMO" title="ADD INSUMO" border="0" style="cursor:hand;"/></a>  <em><a href="anilox.php" target="new">ANILOX </a></em><input type="button" class="botonDel" value="Descarga Insumos Excel" onclick="window.location = 'insumos_excel.php'" />
        </td>
      </tr>
    </table>
 
  <br>
  <table>
    <tr>
      <td class="Estilo1">CODIGO</td>
      <td class="textocentrado">DESCRIPCION</td>
      <td class="centrado2">PROVEEDOR</td>
      <td class="centrado1">CLASE</td>
      <td class="centrado5">QUIMICOS</td>
      <td class="centrado5">MEDIDA</td>
      <td class="centrado1">TIPO</td>
      <td class="Estilo5">VALOR </td>
      <td class="Estilo5">STOCK</td>   
    </tr>

  </table>
<!--   <div class="divScrollGigante" id="itemspedido" role="alert" style="text-align: left;">  -->

  <table  >

    <?php do { ?>
      <?php //foreach($row_registros as $row_insumos) {  ?>
       <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">  
        <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['codigo_insumo']; ?></a>
        </td>
        <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000">
         <?php 
         echo $row_insumos['descripcion_insumo']; 
       ?></a>
     </td>
     <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 

     $id_insumo=$row_insumos['id_insumo']=='' ? 0 : $row_insumos['id_insumo'];
     $sqldato="SELECT proveedor.proveedor_p FROM TblProveedorInsumo, proveedor WHERE TblProveedorInsumo.id_in=$id_insumo AND TblProveedorInsumo.id_p=proveedor.id_p ORDER BY proveedor.id_p DESC ";
     $resultdato=mysql_query($sqldato);
     $row_proveedores = mysql_fetch_assoc($resultdato);
     echo $proveedor_p=mysql_result($resultdato,0,'proveedor_p'); 
     ?> 
   </a>
 </td>       
 <td class="centrado6">
  <a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000">
    <?php 
    $clase_insumo=$row_insumos['clase_insumo']=='' ? 0 : $row_insumos['clase_insumo'];
    $numclase = $conexion->llenarCampos("clase", "WHERE id_clase=$clase_insumo ", " ", "*");

    if($numclase >='1')
    { 
      echo $clase = $numclase['nombre_clase'];    

    } 
    ?> 
  </a>
</td>
<td class="centrado6">
 <a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['quimicos']; ?>
</a>
</td> 
<td class="centrado6"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000">
  <?php 
  $medida_insumo=$row_insumos['medida_insumo']=='' ? 0 : $row_insumos['medida_insumo'];
  $numedida = $conexion->llenarCampos("medida", "WHERE id_medida=$medida_insumo ", " ", "*");
  
  if($numedida >='1') { 
    echo $medida_insumo= $numedida['nombre_medida']; 
  }
  ?> 
</a>
</td>
<td class="centrado6">
  <a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000">
   <?php 
   $tipo_insumo=$row_insumos['tipo_insumo']=='' ? 0 : $row_insumos['tipo_insumo'];
   $numtipo = $conexion->llenarCampos("tipo", "WHERE id_tipo=$tipo_insumo ", " ", "*");

   if($numtipo >='1') { 
     echo $tipo_insumo=$numtipo['nombre_tipo']; 
   }
   ?> 
 </a>
</td>
<td class="centrado6"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo number_format($row_insumos['valor_unitario_insumo'], 2,",", "."); ?></a></td>
<td class="derecha1"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['stok_insumo']; ?></a></td>
</tr>
<?php //} ?>
<?php } while ($row_insumos = mysql_fetch_assoc($registros)); ?>
<tr>
  <td></td>
</tr>
</table>

  <!--   <div class="divScrollGigante" id="itemspedido" role="alert" style="text-align: left;"> 
  </div> -->
<!-- </div>  -->
<br>
<?php if(empty($busqueda) ):?>

<!--   <table id="tabla1"  >
    <tr>
      <td width="50%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="25%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
    <?php } // Show if not first page ?>
  </td>
  <td width="25%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
    <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">&nbsp;&nbsp;&nbsp;&nbsp;Siguiente</a>
  <?php } // Show if not last page ?>
</td>
<td width="25%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&nbsp;&nbsp;&nbsp;&nbsp;Ultimo</a>
<?php } // Show if not last page ?>
</td>
</tr>
</table> -->
<?php endif;?>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($insumos);
?>
