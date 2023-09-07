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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$maxRows_proceso_empleado = 50;
$pageNum_proceso_empleado = 0;
if (isset($_GET['pageNum_proceso_empleado'])) {
  $pageNum_proceso_empleado = $_GET['pageNum_proceso_empleado'];
}
$startRow_proceso_empleado = $pageNum_proceso_empleado * $maxRows_proceso_empleado;

mysql_select_db($database_conexion1, $conexion1);
if( (isset($_GET['nombres'])&&$_GET['nombres']!='') && ($_GET['codigos']=='')){

  $rows_empleado=$conexion->buscarListar("empleado","*","ORDER BY nombre_empleado ASC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado,"WHERE nombre_empleado like '%".$_GET['nombres']."%' OR apellido_empleado like '%".$_GET['nombres']."%'" );
 
}else if(($_GET['nombres']=='') && (isset($_GET['codigos'])&&$_GET['codigos']!='')){

  $rows_empleado=$conexion->buscarListar("empleado","*","ORDER BY nombre_empleado ASC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado,"WHERE codigo_empleado = '".$_GET['codigos']."' " );
}else{
 
   $rows_empleado=$conexion->buscarListar("empleado","*","ORDER BY codigo_empleado desc","",$maxRows_proceso_empleado,$pageNum_proceso_empleado,"" );
 
  
}
 
if (isset($_GET['totalRows_proceso_empleado'])) {
  $totalRows_proceso_empleado = $_GET['totalRows_proceso_empleado'];
} else {
  $totalRows_proceso_empleado = $conexion->conteo('empleado'); 
} 
$totalPages_proceso_empleado = ceil($totalRows_proceso_empleado/$maxRows_proceso_empleado)-1;
 
$queryString_proceso_empleado = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_proceso_empleado") == false && 
        stristr($param, "totalRows_proceso_empleado") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_proceso_empleado = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_proceso_empleado = sprintf("&totalRows_proceso_empleado=%d%s", $totalRows_proceso_empleado, $queryString_proceso_empleado); 
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>

<script type="text/javascript" src="AjaxControllers/js/envioListado.js"></script>

<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

</head>
<body>    
<?php echo $conexion->header('listas'); ?>
	<form action="delete_listado.php" method="get" name="seleccion" id="seleccion">
	<table >
	<tr>
	  <td colspan="7" id="titulo2">LISTADO DE EMPLEADOS DE PLANTA</td>
  </tr>
  <tr>
    <td colspan="7" id="titulo4">Codigo<input name="codigos" id="codigos" type="text" value="" size="50" maxlength="200" value="<?php echo $_GET['codigos']; ?>" > Nombres<input name="nombres" id="nombres" type="text" value="" size="50" maxlength="200" value="<?php echo $_GET['nombres']; ?>" ></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">
    <input class="botonUpdate" name="" type="submit" value="Delete"/>
    <input name="borrado" type="hidden" id="borrado" value="20" /></td>
  <td colspan="4" id="fuente1"><?php $id=$_GET['id']; if($id >= '1') { ?> 
    <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
    <?php } 
  if($id == '0') { ?> 
    <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>    <?php } ?></td>
  <td width="25%" id="fuente3"><a href="empleado_add.php"><img src="images/mas.gif" alt="ADD EMPLEADO" border="0" style="cursor:hand;"></a><a href="empleado_tipo.php"><img src="images/opciones.gif" alt="TIPOS DE EMPLEADO" title="TIPO DE EMPLEADOS" border="0" style="cursor:hand;"></a><a href="factor_prestacional_add.php"><img src="images/f.gif" alt="FACTORES" title="FACTORES" border="0" style="cursor:hand;"></a><a href="turnos.php"><img src="images/t.gif" style="cursor:hand;" alt="TURNOS" title="TURNOS" border="0"/></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td></tr>
<tr id="tr1">
  <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
  <td id="titulo4">COD</td>
  <td id="titulo4">CEDULA</td>
  <td id="titulo4">EMPRESA</td>
  <td id="titulo4">NOMBRE</td>
  <td id="titulo4">APELLIDO</td>
  <td id="titulo4">CARGO</td>
  </tr>
<?php foreach($rows_empleado as $row_empleado) {  ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_empleado['id_empleado']; ?>" /></td>
    <td id="dato2"><a href="empleado_edit.php?id_empleado=<?php echo $row_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $codemp=$row_empleado['codigo_empleado']; if($codemp=='1' || $codemp=='2' || $codemp=='3' || $codemp=='4' || $codemp=='5' || $codemp=='6' || $codemp=='7' || $codemp=='8' || $codemp=='9') { echo "0".$codemp; } else { echo $codemp; } ?></a></td>
    <td id="dato2"><a href="empleado_edit.php?id_empleado=<?php echo $row_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo number_format($row_empleado['cedula_empleado'],0,',','.'); ?></a></td>
    <td id="dato1"><a href="empleado_edit.php?id_empleado=<?php echo $row_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_empleado['empresa_empleado']; ?></a></td>
    <td id="dato1"><a href="empleado_edit.php?id_empleado=<?php echo $row_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_empleado['nombre_empleado']; ?></a></td>
    <td id="dato1"><a href="empleado_edit.php?id_empleado=<?php echo $row_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_empleado['apellido_empleado']; ?></a></td>
    <td id="dato1"><a href="empleado_edit.php?id_empleado=<?php echo $row_empleado['id_empleado']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 

    $tipo_empleado=$row_empleado['tipo_empleado']; 
    if($tipo_empleado!='0' || $tipo_empleado!='') {	
     $sql2="SELECT nombre_tipo_empleado FROM empleado_tipo WHERE id_empleado_tipo=$tipo_empleado ";

     $result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
        if ($num2>='1') { 
         echo $tipo_empl=mysql_result($result2,0,'nombre_tipo_empleado'); 
           
       } 
  }
  ?>

</a>
</td>
    </tr>
  <?php } ?>

  <table id="tabla3">
    <tr>
      <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, 0, $queryString_proceso_empleado); ?>">Primero</a>
        <?php } // Show if not first page ?></td>
        <td width="31%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, max(0, $pageNum_proceso_empleado - 1), $queryString_proceso_empleado); ?>">Anterior</a>
          <?php } // Show if not first page ?></td>
          <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado < $totalPages_proceso_empleado) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, min($totalPages_proceso_empleado, $pageNum_proceso_empleado + 1), $queryString_proceso_empleado); ?>">Siguiente</a>
            <?php } // Show if not last page ?></td>
            <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_empleado < $totalPages_proceso_empleado) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_proceso_empleado=%d%s", $currentPage, $totalPages_proceso_empleado, $queryString_proceso_empleado); ?>">&Uacute;ltimo</a>
              <?php } // Show if not last page ?></td>
            </tr>
          </table></td>
        </tr>

</table>
</form>
 <?php echo $conexion->header('footer'); ?>
</body>
</html>
<script type="text/javascript">

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
      if(e.keyCode == 13) {
        e.preventDefault();
      }
    }))
  });

  $( "#nombres" ).on( "change", function() {
    var form = $("#seleccion").serialize();
    var vista = 'empleados.php';

        enviovarListados(form,vista);

  });

  $( "#codigos" ).on( "change", function() {
    var form = $("#seleccion").serialize();
    var vista = 'empleados.php';

        enviovarListados(form,vista);

  });

</script>
<?php
mysql_free_result($usuario);
mysql_free_result($empleado);
?>