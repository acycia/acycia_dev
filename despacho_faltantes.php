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

/*mysql_select_db($database_conexion1, $conexion1);
$query_op = "SELECT id_op,int_cod_ref_op,str_numero_oc_op FROM Tbl_orden_produccion WHERE b_estado_op > '0' ORDER BY id_op DESC";
$op = mysql_query($query_op, $conexion1) or die(mysql_error());
$row_op = mysql_fetch_assoc($op);
$totalRows_op = mysql_num_rows($op);*/
$row_op = $conexion->llenaSelect('Tbl_orden_produccion',"WHERE b_estado_op > '0'","ORDER BY id_op DESC");  
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
</head>
<body>
  <?php echo $conexion->header('listas'); ?>
<form action="despacho_faltantes2.php" method="get" name="form1" >
<table class="table table-bordered table-sm">
<tr>
<td id="titulo2">DESPACHO FALTANTES</td>
</tr>
<tr colspan="5" id="titulo2">
  <td colspan="5" id="titulo2"> REF/O.C/O.P
    <?php if (isset($_GET['id_op'])) {$_GET['id_op'];}else{$_GET['id_op']= '';} ?>
    <select class="busqueda selectsGrande" name="id_op" id="id_op"  required="required" >
       <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione REF / O.C / O.P</option>
       <?php  foreach($row_op as $row_op ) { ?>
        <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['int_cod_ref_op']." / O.C:".$row_op['str_numero_oc_op']." / O.P: ".$row_op['id_op']?></option>
    <?php } ?>  
    </select>

    <!-- <select name="id_op" id="id_op"  required="required" >
      <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione REF / O.C / O.P</option>
      <?php
        do {  
        ?>
        <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['int_cod_ref_op']." / O.C:".$row_op['str_numero_oc_op']." / O.P: ".$row_op['id_op']?></option>
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
<input type="number" class="form-control negro_inteso" size="8" placeholder="Caja Inicial" name="cajaini" id="cajaini" max="100000" >
  </td>
   <td id="titulo2" colspan="4">
<input type="number" class="form-control negro_inteso" size="8" placeholder="Caja Final" name="cajafin" id="cajafin" max="100000" >
  </td>
 </tr> 
 <tr>
   <td colspan="5" id="titulo3" >
      <input type="submit" name="Submit" value="FILTRO" id="btenviar" class="botonGMini"/> <input style="display: none;" type="button" id="excel" name="excel" value="Descarga Excel" onclick="myFunction()" class="botonGMini">
   </td>
 </tr> 
 
<tr>
  <td colspan="5" id="titulo4">

     <b> Importante filtrar por cajas para no agotar memoria del php </b>
   </td>
</tr>
<tr>
  <td id="fuente2"><div id="resultado"></div></td>
</tr>
</table>
</form>
 
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<script>
function despacho_faltantes_op(selec)
{
  var id_op = document.getElementById("id_op").value;
  var cajaini = document.getElementById("cajaini").value; 
  var cajafin = document.getElementById("cajafin").value;  
window.location.href ="despacho_faltantes2.php?id_op=?id_op="+id_op+"&cajaini="+cajaini+"&cajafin="+cajafin;
}

function myFunction() { 
    var id_op = document.getElementById("id_op").value; 
    var cajaini = document.getElementById("cajaini").value; 
    var cajafin = document.getElementById("cajafin").value; 
 
 window.location.href = "despacho_faltante_excel.php?id_op="+id_op+"&cajaini="+cajaini+"&cajafin="+cajafin;
}
$("#id_op").on("change",function(){
    if ($("#id_op").val()!=''){
            $("#excel").css("display", "block");
         }else{
            $("#excel").css("display", "none");
         } 
     }); 

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

//mysql_free_result($numeracion);

mysql_free_result($op);
?>