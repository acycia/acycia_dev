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
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_reg_tipo_desperdicio (id_rtp, id_proceso_rtd, codigo_rtp, nombre_rtp,estado_rtp,responsable) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_rtp'], "int"),
 					   GetSQLValueString($_POST['proceso'], "int"),
					   GetSQLValueString($_POST['tipo'], "int"),
                       GetSQLValueString($_POST['nombre_tipo'], "text"),
					   GetSQLValueString('0', "int"),
					   GetSQLValueString($_POST['responsable'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
 
  $insertGoTo = "tipos_desperdicio_tiempos.php?tipo=" . $_POST['tipo'] . "&proceso=" . $_POST['proceso'] . "";
/*  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  } */ 
  header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tbl_reg_tipo_desperdicio SET id_proceso_rtd=%s, codigo_rtp=%s, nombre_rtp=%s, responsable=%s WHERE id_rtp=%s",
                       GetSQLValueString($_POST['proceso'], "int"),
                       GetSQLValueString($_POST['tipo'], "int"),
                       GetSQLValueString($_POST['nombre_tipo'], "text"),
					   GetSQLValueString($_POST['responsable'], "text"),
					   GetSQLValueString($_POST['id_rtp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "tipos_desperdicio_tiempos.php?tipo=" . $_POST['tipo'] . "&proceso=" . $_POST['proceso'] . "";
 
  header(sprintf("Location: %s", $updateGoTo));
}
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
 
mysql_select_db($database_conexion1, $conexion1);
$query_lista_tipos = "SELECT * FROM tbltipodesp ORDER BY CodigoTipo,nombre_tipo ASC";
$lista_tipos = mysql_query($query_lista_tipos, $conexion1) or die(mysql_error());
$row_lista_tipos = mysql_fetch_assoc($lista_tipos);
$totalRows_lista_tipos = mysql_num_rows($lista_tipos);

mysql_select_db($database_conexion1, $conexion1);
$query_lista_procesos = "SELECT * FROM tipo_procesos ORDER BY id_tipo_proceso ASC";
$lista_procesos = mysql_query($query_lista_procesos, $conexion1) or die(mysql_error());
$row_lista_procesos = mysql_fetch_assoc($lista_procesos);
$totalRows_lista_procesos = mysql_num_rows($lista_procesos);

$colname_editar_proceso = "-1";
if (isset($_GET['id_rtp'])) {
  $colname_editar_proceso = (get_magic_quotes_gpc()) ? $_GET['id_rtp'] : addslashes($_GET['id_rtp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_procesos = sprintf("SELECT * FROM tbl_reg_tipo_desperdicio WHERE id_rtp= %s ORDER BY id_rtp ASC", $colname_editar_proceso,$colname_editar_tipo);
$editar_procesos = mysql_query($query_editar_procesos, $conexion1) or die(mysql_error());
$row_editar_procesos = mysql_fetch_assoc($editar_procesos);
$totalRows_editar_procesos = mysql_num_rows($editar_procesos);

$colname_lista_proceso = "-1";
if (isset($_GET['proceso'])) {
  $colname_lista_proceso = (get_magic_quotes_gpc()) ? $_GET['proceso'] : addslashes($_GET['proceso']);
}
$colname_lista_codigo = "-1";
if (isset($_GET['tipo'])) {
  $colname_lista_codigo = (get_magic_quotes_gpc()) ? $_GET['tipo'] : addslashes($_GET['tipo']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_lista_todo = sprintf("SELECT * FROM tbl_reg_tipo_desperdicio WHERE id_proceso_rtd = %s AND codigo_rtp = %s ORDER BY nombre_rtp ASC",$colname_lista_proceso,$colname_lista_codigo);
$lista_todo = mysql_query($query_lista_todo, $conexion1) or die(mysql_error());
$row_lista_todo = mysql_fetch_assoc($lista_todo);
$totalRows_lista_todo = mysql_num_rows($lista_todo);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT id_rtp FROM tbl_reg_tipo_desperdicio ORDER BY id_rtp DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<?php echo $conexion->header('listas'); ?>
<table id="tabla3">
    <?php $id_tipo_proceso=$row_lista_todo['id_rtp'];
	   if($id_tipo_proceso!='') { ?>
      <tr><td colspan="4" id="subtitulo1">LISTADO DE PROCESOS</td>
        </tr>
      <tr>
        <td colspan="4"  id="subtitulo1">
      <?php   
	  $id_ctipo=$_GET['tipo'];
	  $sqlnp="SELECT nombre_tipo FROM tbltipodesp WHERE CodigoTipo=$id_ctipo"; 
	  $resultnp=mysql_query($sqlnp); 
	  $numnp=mysql_num_rows($resultnp); 
	  if($numnp >= '1') 
	  { $nombre_tipo=mysql_result($resultnp,0,'nombre_tipo'); 
 	  } 
	  $id_cproc=$_GET['proceso'];
	  $sqlproc="SELECT nombre_proceso FROM tipo_procesos WHERE id_tipo_proceso=$id_cproc"; 
	  $resultproc=mysql_query($sqlproc); 
	  $numproc=mysql_num_rows($resultproc); 
	  if($numproc >= '1') 
	  { $nombre_proc=mysql_result($resultproc,0,'nombre_proceso'); 
	  echo "TIPO: ".$nombre_tipo." PROCESO: ".$nombre_proc; 	
	  }
	  
	  ?>
      </td>
      </tr>
  	  <tr>
        <td colspan="4" id="dato1"><table id="tabla35">
		  <?php do { ?>
		    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">            
		      <td id="detalle1"><a href="tipos_desperdicio_tiempos.php?id_rtp=<?php echo $row_lista_todo['id_rtp']; ?>&tipo=<?php echo $row_lista_todo['codigo_rtp']; ?>&proceso=<?php echo $row_lista_todo['id_proceso_rtd']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_lista_todo['nombre_rtp']; ?></a></td>
		      <td id="detalle2"><a href="tipos_desperdicio_tiempos.php?id_rtp=<?php echo $row_lista_todo['id_rtp']; ?>&tipo=<?php echo $row_lista_todo['codigo_rtp']; ?>&proceso=<?php echo $row_lista_todo['id_proceso_rtd']; ?>"><img src="images/menos.gif" alt="EDIT TIPO" title="EDIT TIPO" border="0" style="cursor:hand;"/></a><a href="javascript:activar1('id_rtp_in',<?php echo $row_lista_todo['id_rtp']; ?>,'0','tipos_desperdicio_tiempos.php')">
        <?php if($row_lista_todo['estado_rtp']=='0') {?><img src="images/a.gif" alt="ACTIVO" title="ACTIVO" border="0" style="cursor:hand;"/></a><?php }else{?><a href="javascript:activar1('id_rtp_ac',<?php echo $row_lista_todo['id_rtp']; ?>,'1','tipos_desperdicio_tiempos.php')"><img src="images/i_rojo.gif" alt="INACTIVO" title="INACTIVO" border="0" style="cursor:hand;"/></a><?php } ?>
        <?php if($row_usuario['tipo_usuario']=='1'){?>
              <a href="javascript:eliminar1('id_rtp',<?php echo $row_lista_todo['id_rtp']; ?>,'tipos_desperdicio_tiempos.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a><?php } ?></td>
		    </tr>
		    <?php } while ($row_lista_todo = mysql_fetch_assoc($lista_todo)); ?>
		  </table></td>
        </tr> 
      <?php } 
	  $id_tipo_proceso2=$row_editar_procesos['id_rtp'];
	   if($id_tipo_proceso2==''){?>
      <tr>
        <td>
     <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('tipo','','R');return document.MM_returnValue">
     <table>
              <tr>
                <td id="subtitulo1">TIPO</td>
                <td id="subtitulo1"><a href="tipos_procesos.php" target="_self">PROCESO</a></td>
                <td id="subtitulo1">NOMBRE</td>
                <td id="subtitulo1">&nbsp;</td>
              </tr>
              <tr> 
                <td id="dato1"><select name="tipo" id="tipo" style="width:150px">  
                  <?php
do {  
?>
                  <option value="<?php echo $row_lista_tipos['CodigoTipo']?>"<?php if (!(strcmp($row_lista_tipos['CodigoTipo'], $_GET['tipo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_tipos['nombre_tipo']?></option>
                  <?php
} while ($row_lista_tipos = mysql_fetch_assoc($lista_tipos));
  $rows = mysql_num_rows($lista_tipos);
  if($rows > 0) {
      mysql_data_seek($lista_tipos, 0);
	  $row_lista_tipos = mysql_fetch_assoc($lista_tipos);
  }
?>
    </select></td>
        <td><select name="proceso" id="proceso" style="width:150px">
          <?php
do {  
?>
          <option value="<?php echo $row_lista_procesos['id_tipo_proceso']?>"<?php if (!(strcmp($row_lista_procesos['id_tipo_proceso'], $_GET['proceso']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_procesos['nombre_proceso']?></option>
          <?php
} while ($row_lista_procesos = mysql_fetch_assoc($lista_procesos));
  $rows = mysql_num_rows($lista_procesos);
  if($rows > 0) {
      mysql_data_seek($lista_procesos, 0);
	  $row_lista_procesos = mysql_fetch_assoc($lista_procesos);
  }
?>
        </select></td>
        <td><input name="nombre_tipo" type="text" id="nombre_tipo" value="" size="20" required></td>
        <td><input name="submit" type="submit" value="Adicionar">
          <input name="submit3" type="button" value="Editar" onClick="EnvioBoton('tipos_desperdicio_tiempos.php',tipo.name,tipo.value,proceso.name,proceso.value)">
          <input type="hidden" name="id_rtp" value="<?php echo $row_ultimo['id_rtp']+1; ?>"> 
          <input type="hidden" name="responsable" value="<?php echo $row_usuario['nombre_usuario']; ?>"> 
       
          <input type="hidden" name="MM_insert" value="form1"></td>
        </tr>
       </table>
      <?php }?>
       </form></td>
        </tr>
        <?php  
	    if($id_tipo_proceso2!='') { ?>
        <tr>
        <td id="dato1">
        <form method="post" name="form2" action="<?php echo $editFormAction; ?>" onSubmit="MM_validateForm('tipo','','R');return document.MM_returnValue">
             <table>
             <tr>
                <td id="subtitulo1">TIPO EDITAR</td>
                <td id="subtitulo1">PROCESO EDITAR</td>
                <td id="subtitulo1">NOMBRE EDITAR</td>
                <td id="subtitulo1">&nbsp;</td>
              </tr>
              <tr>
                <td><select name="tipo" id="tipo" style="width:150px">
                  <?php
do {  
?>
                  <option value="<?php echo $row_lista_tipos['CodigoTipo']?>"<?php if (!(strcmp($row_lista_tipos['CodigoTipo'], $row_editar_procesos['codigo_rtp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_tipos['nombre_tipo']?></option>
                  <?php
} while ($row_lista_tipos = mysql_fetch_assoc($lista_tipos));
  $rows = mysql_num_rows($lista_tipos);
  if($rows > 0) {
      mysql_data_seek($lista_tipos, 0);
	  $row_lista_tipos = mysql_fetch_assoc($lista_tipos);
  }
?>
                </select></td>
                <td><select name="proceso" id="proceso" style="width:150px">
                  <?php
do {  
?>
                  <option value="<?php echo $row_lista_procesos['id_tipo_proceso']?>"<?php if (!(strcmp($row_lista_procesos['id_tipo_proceso'], $row_editar_procesos['id_proceso_rtd']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_procesos['nombre_proceso']?></option>
                  <?php
} while ($row_lista_procesos = mysql_fetch_assoc($lista_procesos));
  $rows = mysql_num_rows($lista_procesos);
  if($rows > 0) {
      mysql_data_seek($lista_procesos, 0);
	  $row_lista_procesos = mysql_fetch_assoc($lista_procesos);
  }
?>
                </select></td>
                <td><input name="nombre_tipo" type="text" id="nombre_tipo" value="<?php echo $row_editar_procesos['nombre_rtp']; ?>" size="20" required></td>
              <td><input name="submit2" type="submit" value="Actualizar"> 
             <input type="hidden" name="MM_update" value="form2">
             <input type="hidden" name="responsable" value="<?php echo $row_usuario['nombre_usuario']; ?>">
             <input type="hidden" name="id_rtp" value="<?php echo $row_editar_procesos['id_rtp']; ?>">
          <a href="tipos_desperdicio_tiempos.php?tipo=<?php echo $row_editar_procesos['codigo_rtp']; ?>&proceso=<?php echo $row_editar_procesos['id_proceso_rtd']; ?>">Cancelar</a>
          </td>
        </tr></table>
            </form><?php } ?>
     </table></td>
  </tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($lista_tipos);

mysql_free_result($lista_procesos);
?>