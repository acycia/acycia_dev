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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_obs_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_obs_ref = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_obs_ref = sprintf("SELECT * FROM tbl_observaciones_ref WHERE ref = %s ORDER BY fecha desc", $colname_obs_ref);
$obs_ref = mysql_query($query_obs_ref, $conexion1) or die(mysql_error());
$row_obs_ref = mysql_fetch_assoc($obs_ref);
$totalRows_obs_ref = mysql_num_rows($obs_ref);
//edit
$colname_obs_ref_edit = "-1";
if (isset($_GET['id'])) {
  $colname_obs_ref_edit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_obs_ref_edit = sprintf("SELECT * FROM tbl_observaciones_ref WHERE id = %s ORDER BY fecha desc", $colname_obs_ref_edit);
$obs_ref_edit = mysql_query($query_obs_ref_edit, $conexion1) or die(mysql_error());
$row_obs_ref_edit = mysql_fetch_assoc($obs_ref_edit);
$totalRows_obs_ref_edit = mysql_num_rows($obs_ref_edit);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table id="tabla"><tr align="center"><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1">
<tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
</ul>
</td></tr>
<tr><td colspan="2" align="center" id="linea1">
<table id="tabla1">
      <tr>
        <td id="codigo">CODIGO: R2-F01</td>
        <td id="titulo2">PLAN DE DISE&Ntilde;O Y DESARROLLO</td>
        <td id="codigo">VERSION: 3</td>
        </tr>
      <tr>
        <td id="fuente2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a></td>
        <td id="subtitulo">I . obs</td>
        <td id="fuente2"><a href="#"  onclick="mostrar()"><img src="images/mas.gif" alt="ADD OBS" border="0" style="cursor:hand;" title="ADD OBS" /></a></td>
      </tr>
</table></td>
</tr>
  <tr>
    <td colspan="3" align="center" id="numero2"><?php if($row_obs_ref['id'] == '') { echo "Primero debe crear una OBSERVACION"; }?></td>
  </tr><?php if($row_obs_ref['id'] != '') {  ?>
  <tr>
    <td colspan="3" align="center" id="numero2"><table id="tabla1">
  <tr id="tr1">
    <td id="titulo4">REF</td>
    <td id="titulo4">FECHA</td>
    <td id="titulo4">RESPONSABLE</td>
    <!-- <td id="titulo4">OBSERVACION</td> -->
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato1"><a href="obs_ref.php?id_ref=<?php echo $_GET['id_ref']; ?>&id=<?php echo $row_obs_ref['id']; ?>" target="_top" style="text-decoration:none; color:#000000" ><?php echo $row_obs_ref['ref'].' - '.$row_obs_ref['version']; ?></a></td>
      <td id="dato3"><a href="obs_ref.php?id_ref=<?php echo $_GET['id_ref']; ?>&id=<?php echo $row_obs_ref['id']; ?>" target="_top" style="text-decoration:none; color:#000000" ><?php echo $row_obs_ref['fecha']; ?></a></td>
      <td nowrap id="dato2"><a href="obs_ref.php?id_ref=<?php echo $_GET['id_ref']; ?>&id=<?php echo $row_obs_ref['id']; ?>" target="_top" style="text-decoration:none; color:#000000" ><?php $cad = htmlentities( $row_obs_ref['usuario']);echo $cad; ?></a></td>
      <!-- <td id="dato2"><a href="obs_vista.php?id= <?php echo $row_obs_ref['id']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_obs_ref['obs'];?></a></td> -->
    </tr>
    <?php } while ($row_obs_ref = mysql_fetch_assoc($obs_ref)); ?>
</table>
</td>
</tr><?php } ?>
 <br><br>



</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>

    <div id="alerta" style="display: none;" >
     <form  action="insert_general.php" method="POST" name="form1" >

    <table id="tabla1" >
       <caption  style="text-align: center; background-color: #5F76F8;; " id="titulo2" >ADD OBSERVACION</caption>
     <tr id="tr1">
        <td id="titulo4">REF</td>
        <td id="titulo4">VERS</td>
        <td id="titulo4">FECHA</td>
        <td id="titulo4">RESPONSABLE</td>
      </tr>
      <tr bgcolor="#FFFFFF">
          <td id="dato1">
           <div style="display: none;" > <input type="text" id="add" name="add" value="add" size="7" ></div>
            <input type="text" id="ref" name="ref" value="" size="7" maxlength="7"></td>
          <td id="dato1"><input type="text" id="version" name="version" value="" size="2" maxlength="2"></td>
          <td id="dato1"><input type="date" name="fecha" value="<?php echo date("Y-m-d"); ?>" size="8S" maxlength="12"></td>
          <td id="dato1"><input type="text" name="usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="40" maxlength="40"></td>
      </tr>
      </table>
      <table>
      <tr>
        <td id="titulo4" nowrap="3">OBSERVACION </td>
      </tr>
      <tr>
          <td id="dato1"  nowrap="3"><textarea name="obs" cols="95" rows="10" maxlength="2000" placeholder="OBS max 2000 caracteres"></textarea></td>
          </tr>
          <tr>
                <td id="dato2" nowrap="3"><input name="submit" type="submit" value="ADD OBSERVACION"></td>
          </tr>
     </table>
     <br><br>
     </form>  
    </div>
    <?php if(isset($_GET['id'] ) && $_GET['id'] !=''): ?> 
    <div id="edit" >
     <form  action="insert_general.php" method="POST" name="form2">
      <table id="tabla1" >
       <caption  style="text-align: center;  background-color: #F55134; " id="titulo2" ><a href="#" onclick="ocultar_edit()">EDITAR OBSERVACION</a> </caption>
     <tr id="tr1">
        <td id="titulo4">EDIT REF</td>
        <td id="titulo4">EDIT VERS</td>
        <td id="titulo4">EDIT FECHA</td>
        <td id="titulo4">EDIT RESPONSABLE</td>
      </tr>
      <tr bgcolor="#FFFFFF">
          <td id="dato1">
           <div style="display: none;" > 
            <input type="text" id="edit" name="edit" value="edit" size="7" >
            <input type="text" id="id" name="id" value="<?php echo $row_obs_ref_edit['id']; ?>" size="7" >
          </div>
            <input type="text" id="ref" name="ref" value="<?php echo $row_obs_ref_edit['ref']; ?>" size="7" maxlength="7"></td>
          <td id="dato1"><input type="text" id="version" name="version" value="<?php echo $row_obs_ref_edit['version']; ?>" size="2" maxlength="2"></td>
          <td id="dato1"><input type="date" name="fecha" value="<?php echo $row_obs_ref_edit['fecha']; ?>" size="8S" maxlength="12"></td>
          <td id="dato1"><input type="text" name="usuario" value="<?php echo $row_obs_ref_edit['usuario']; ?>" size="40" maxlength="40"></td>
      </tr>
      </table>
      <table>
      <tr>
        <td id="titulo4" nowrap="3" >EDIT OBSERVACION </td>
      </tr>
      <tr>
          <td id="dato1"  nowrap="3"><textarea name="obs" cols="95" rows="10"  maxlength="2000" placeholder="OBS max 2000 caracteres"><?php echo $row_obs_ref_edit['obs']; ?></textarea></td>
          </tr>
          <tr>
                <td id="dato2" nowrap="3"><input name="submit" type="submit" value="EDITAR OBSERVACION"></td>
          </tr>
     </table>

     </form>
   </div>

 <?php endif; ?>

</div>
</body>
</html>
<script>
function mostrar() {
   var id_ref = <?php echo $_GET['id_ref']; ?>;
    var x = document.getElementById('alerta');
    if (x.style.display === 'none') {
        x.style.display = 'block';
      document.getElementById("ref").value = id_ref;

    } else { 
        x.style.display = 'none';
    }
}
function ocultar_edit(){
    var x = document.getElementById('edit');
    if (x.style.display === 'none') {
        x.style.display = 'block';

    } else { 
        x.style.display = 'none';
    }
}
/*function editar() {
  var id = <?php echo $_GET['id']; ?>;
  var ref = <?php echo $_GET['ref']; ?>;
  var version = <?php echo $_GET['version']; ?>;
  var fecha = <?php echo $_GET['fecha']; ?>;
  var usuario = <?php echo $_GET['usuario']; ?>;
  var obs = <?php echo $_GET['obs']; ?>;
    var x = document.getElementById('edit');
    if (x.style.display === 'none') {
        x.style.display = 'block';
      document.getElementById("id").value = id;
      document.getElementById("ref").value = ref;
      document.getElementById("version").value = version;
      document.getElementById("fecha").value = fecha;
      document.getElementById("usuario").value = usuario;
      document.getElementById("obs").value = obs;

    } else { 
        x.style.display = 'none';
    }
}*/



/*
lunes 2 horas
martes 2.5 horas
miercoles 3.5 h

*/

</script>
<?php
mysql_free_result($usuario);
mysql_free_result($obs_ref);
?>
