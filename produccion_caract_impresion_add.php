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

	$und=($_POST['und']);
  foreach($und as $key=>$v)
    $a[]= $v;

  $id=($_POST['id']);
  foreach($id as $key=>$v)
    $b[]= $v;

  $valor=($_POST['valor']);
  foreach($valor as $key=>$v)
    $c[]= $v;

  $id_m=($_POST['id_m']);
  foreach($id_m as $key=>$v)
    $f[]= $v;

  for($x=0; $x<count($b); $x++) 
  {
		//if($a[$x]!=''&&$b[$x]!=''&&$c[$x]!=''&&$f[$x]!=''){			 
    $insertSQL = sprintf("INSERT INTO Tbl_produccion_mezclas_impresion (id_proceso, fecha_registro_pmi, str_registro_pmi, id_ref_pmi, int_cod_ref_pmi, version_ref_pmi, und, id_m, id_i_pmi, str_valor_pmi, observ_pmi, b_borrado_pmi) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",

     GetSQLValueString($_POST['id_proceso'], "int"),
     GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
     GetSQLValueString($_POST['str_registro_pmi'], "text"),
     GetSQLValueString($_POST['id_ref_pmi'], "int"),
     GetSQLValueString($_POST['int_cod_ref_pmi'], "text"),
     GetSQLValueString($_POST['version_ref_pmi'], "int"),
     GetSQLValueString($a[$x], "int"),
     GetSQLValueString($f[$x], "int"),
     GetSQLValueString($b[$x], "int"),
     GetSQLValueString($c[$x], "text"),
     GetSQLValueString($_POST['observ_pmi'], "text"),
     GetSQLValueString($_POST['b_borrado_pmi'], "int"));

    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
	}//llave de for    
	//}		
 $insertGoTo = "produccion_caract_impresion_vista.php";
 if (isset($_SERVER['QUERY_STRING'])) {
  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
  $insertGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $insertGoTo));

	//aray de id_c_cv osea names
$id_cv=($_POST['id_cv']);
foreach($id_cv as $key=>$v)
  $d[]= $v;

$valor_cv=($_POST['valor_cv']);
foreach($valor_cv as $key=>$v)
  $e[]= $v;
	//for para guardar refistro por campo
for($cv=0; $cv<count($d); $cv++){
		//if($d[$cv]!=''&&$e[$cv]!=''){	 	
  $insertSQL2 = sprintf("INSERT INTO Tbl_caracteristicas_valor ( id_proceso_cv, id_c_cv, id_ref_cv, cod_ref_cv, version_ref_cv, str_valor_cv, id_pm_cv, fecha_registro_cv, str_registro_cv, b_borrado_cv) VALUES (  %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
    GetSQLValueString($_POST['id_proceso_cv'], "int"),
    GetSQLValueString($d[$cv], "int"),
    GetSQLValueString($_POST['id_ref_cv'], "int"),
    GetSQLValueString($_POST['cod_ref_cv'], "int"),
    GetSQLValueString($_POST['version_ref_cv'], "text"),
    GetSQLValueString($e[$cv], "text"),
    GetSQLValueString($_POST['id_pm_cv'], "int"),
    GetSQLValueString($_POST['fecha_registro_cv'], "date"),
    GetSQLValueString($_POST['str_registro_cv'], "text"),
    GetSQLValueString($_POST['b_borrado_cv'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());	
  
  $insertSQL3 = sprintf("INSERT INTO Tbl_caract_proceso(id_pm_cp, id_ref_cp, id_cod_ref_cp, id_proceso, id_caract ) VALUES ( %s, %s, %s, %s, %s )",
    GetSQLValueString($_POST['id_pm_cv'], "int"),
    GetSQLValueString($_POST['id_ref_cv'], "int"),
    GetSQLValueString($_POST['cod_ref_cv'], "int"), 
    GetSQLValueString($_POST['id_proceso_cv'], "int"),
    GetSQLValueString($d[$cv], "int"));  

  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error()); 	  
	}//llave de for 	   
	//}
    //MAESTRA TBL_MAESTRA_MP
  $insertSQL4 = sprintf("INSERT INTO Tbl_maestra_mezcla_caract ( int_id_ref_mm, int_cod_ref_mm, id_proceso_mm ) VALUES ( %s, %s, %s )",
    GetSQLValueString($_POST['id_ref_cv'], "int"),
    GetSQLValueString($_POST['cod_ref_cv'], "int"), 
    GetSQLValueString($_POST['id_proceso_cv'], "int"));  

  mysql_select_db($database_conexion1, $conexion1);
  $Result4 = mysql_query($insertSQL4, $conexion1) or die(mysql_error());

  $insertGoTo = "produccion_caract_impresion_vista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//CONSULTA COLOR EGP
$colname_ref = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref=%s AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='8' AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_anilox = "SELECT * FROM anilox ORDER BY descripcion_insumo ASC";
$anilox = mysql_query($query_anilox, $conexion1) or die(mysql_error());
$row_anilox = mysql_fetch_assoc($anilox);
$totalRows_anilox = mysql_num_rows($anilox);

//MEZCLA IMPRESION
$colname_mezcla = "-1";
if (isset($_GET['id_pmi'])) {
  $colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_pmi'] : addslashes($_GET['id_pmi']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = sprintf("SELECT * FROM Tbl_produccion_mezclas_impresion WHERE id_pmi = %s AND b_borrado_pmi='0'", $colname_mezcla);
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);
//LLAMA LAS UNIDADES DE IMPRESION
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = "SELECT * FROM Tbl_caracteristicas WHERE proceso_c='2' ORDER BY id_c ASC";
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);


mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT pantone1_egp, pantone2_egp, pantone3_egp, 
   pantone4_egp, pantone5_egp, pantone6_egp, pantone7_egp, pantone8_egp FROM tbl_referencia,tbl_egp WHERE tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref=".$_GET['cod_ref'];
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_row_referencia = mysql_num_rows($row_referencia);
 


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
  <script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>   
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript">
    function cambiarDisplay1() {
      div1 = document.getElementById("m1_1");
      div2 = document.getElementById("m2_1");
      div3 = document.getElementById("m3_1");
      div4 = document.getElementById("m4_1");
      div1.style.display = "";
      div2.style.display = "";
      div3.style.display = "";
      div4.style.display = "";
    }
  </script>
  <script type="text/javascript">
    <!--



    function cambiarDisplay (id1,id2,id3,id4){
//if (!document.getElementById) return false;
fil1 =  document.getElementById(id1);
fil2 =  document.getElementById(id2);
fil3 =  document.getElementById(id3);
fil4 =  document.getElementById(id4);
var todos = [fil1, fil2, fil3,fil4];
/*for (x=0;x<todos.length;x++){
	fil =  todos[x];
  alert(fil);*/
/* fila =  document.getElementsById('row1[]'), fil = 0, x;
    for(x = fila.length; x--;){
     fil =  fila[x];*/
     if (fil1.style.display != "none") {
fil1.style.display = "none"; //ocultar fila 
fil2.style.display = "none"; //ocultar fila 
fil3.style.display = "none"; //ocultar fila 
fil4.style.display = "none"; //ocultar fila 
} else {
fil1.style.display = ""; //mostrar fila 
fil2.style.display = ""; //mostrar fila 
fil3.style.display = ""; //mostrar fila 
fil4.style.display = ""; //mostrar fila 
}
//}//for
}
-->
</script>
</head>
<body>
  <div id="mydiv">
    
  
  <div align="center">
    <table align="center" id="tabla"><tr align="center"><td>
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
              <tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
                <td id="cabezamenu"><ul id="menuhorizontal">
                 <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
               </ul>
             </td>
           </tr>  
           <tr>
            <td colspan="2" align="center">   
              <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1"  >
                <table id="tabla2">
                  <tr>
                    <td><table id="tablainterna">
                      <tr></tr>
                    </table>
                    <table id="tabla3">
                      <tr id="tr1">
                        <td colspan="8" id="titulo2">MEZCLAS DE  IMPRESION</td>
                      </tr>
                      <tr>
                        <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
                        <td colspan="6" id="dato3"><a href="produccion_mezclas_impresion.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a> <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                      </tr>
                      <tr id="tr1">
                        <td width="182" colspan="3" nowrap="nowrap" id="fuente1">Fecha Ingreso:
                          <input name="fecha_registro_pmi" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus /></td>
                          <td colspan="3" id="fuente1"> Ingresado por:
                            <?php $numero=$row_ultimo['id_pmi']+1;  $numero; ?>
                            <input type="hidden" name="id_pmi" id="id_pmi" value="<?php echo $numero; ?>"/>
                            <input name="str_registro_pmi" type="text" id="str_registro_pmi" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
                          </tr>
                          <tr id="tr3">
                            <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
                            <td colspan="3" nowrap="nowrap" id="fuente1">&nbsp;</td>
                          </tr>
                          <tr id="tr3">
                            <td colspan="2" nowrap="nowrap" id="fuente2">Referencia</td>
                            <td colspan="2" id="fuente2">Version</td>
                            <td colspan="2" id="dato1"><!--<input type="button" name="GENERAR COPIA" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/>--></td>
                          </tr>
                          <tr>
                            <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_pmi" id="id_ref_pmi" value="<?php echo $row_ref['id_ref'] ?>"/>
                              <input type="hidden" name="int_cod_ref_pmi" id="int_cod_ref_pmi" value="<?php echo $row_ref['cod_ref']; ?>" />
                              <?php echo $row_ref['cod_ref']; ?></td>
                              <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_pmi" id="version_ref_pmi" value="<?php echo $row_ref['version_ref']; ?>" />
                                <?php echo $row_ref['version_ref']; ?></td>
            <td colspan="2" id="fuente2"><!--<select name="ref" id="select_sh2" onchange="if(form1.ref.value){ consulta_ref_impresion_add(); } else{ alert('Debe Seleccionar una REFERENCIA'); }"  style="visibility:hidden">
          <option value=""<?php if (!(strcmp("", $_GET['ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
          <?php
do {  
?>
          <option value="<?php echo $row_referencia_copia['id_ref_pmi']?>"<?php if (!(strcmp($row_referencia_copia['int_cod_ref_pmi'], $_GET['ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia_copia['int_cod_ref_pmi']?></option>
          <?php
} while ($row_referencia_copia = mysql_fetch_assoc($referencia_copia));
  $rows = mysql_num_rows($referencia_copia);
  if($rows > 0) {
      mysql_data_seek($referencia_copia, 0);
	  $row_referencia_copia = mysql_fetch_assoc($referencia_copia);
  }
?>
</select>--></td>
</tr>
<tr>
  <td colspan="3" id="fuente3"><!--Editar  los colores de las Unidades en el Egp:--></td>
  <td colspan="3" id="dato1"><!--<a href="javascript:verFoto('produccion_caract_impresion_edit_colores.php?id_ref=<?php echo $row_ref['id_ref']; ?>&amp;n_egp=<?php echo $row_ref['cod_ref']; ?>','830','801')"><img src="images/engranaje-12.gif" alt="EDITAR COLORES" width="64" height="47" style="cursor:hand;" title="EDITAR COLORES" border="0" /></a>-->
    <a href="referencia_bolsa_edit.php?id_ref=<?php echo $row_ref['id_ref']; ?>&amp;n_egp=<?php echo $row_ref['cod_ref']; ?>" title="REF-EGP" target="new"><em>REF-EGP</em></a></td>
  </tr>
  <tr id="tr1">
    <td colspan="8" id="titulo4">UNIDADES DE IMPRESION
      <?php $id_ref=$_GET['ref'];?></td>
    </tr>
    <tr>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 1</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 2</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 3</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 4</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 5</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 6</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 7</strong></td>
      <td nowrap="nowrap"id="fuente2"><strong>UNIDAD 8</strong></td>
    </tr>
    <tr>
      <td id="fuente1"><table id="tablainterna2">
        <tr>
          <td nowrap="nowrap"id="fuente6"></td>
          <td colspan="2" nowrap="nowrap"id="fuente6"><?php echo $row_ref['color1_egp'] ?>  <!--<a onClick="cambiarDisplay('row1','row2','row3','row4')" href="#">MEZCLAS</a>--></td>
        </tr>
        <tr>
          <td nowrap="nowrap"id="fuente1">COLORES</td>
          <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
            <input name="und[]" type="hidden" id="und[]" value="1" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">COLOR</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone1_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr ><!--id="row1" style="display:none"-->
            <td nowrap="nowrap"id="fuente1">MEZCLAS</td>
            <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
              <input name="und[]" type="hidden" id="und[]" value="1"/>
              <select name="id[]" id="m1_1" style="width:80px">
                <option value="0">MEZCLA 1</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td nowrap="nowrap" id="fuente1"> 
                <input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="vm1_1"  size="3" value="" placeholder="%" /> </td>
              </tr>
              <tr >
                <td nowrap="nowrap"id="fuente1">&nbsp;</td>
                <td id="fuente4"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
                  <input name="und[]" type="hidden" id="und[]" value="1" />
                  <select name="id[]" id="m1_2" style="width:80px">
                    <option value="0">MEZCLA 2</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="vm1_2"  size="3" value="" placeholder="%"/></td>
                </tr>
                <tr>
                  <td nowrap="nowrap"id="fuente1">&nbsp;</td>
                  <td id="fuente4"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
                    <input name="und[]" type="hidden" id="und[]" value="1" />
                    <select name="id[]" id="id[]" style="width:80px">
                      <option value="0">MEZCLA 3</option>
                      <?php
                      do {  
                        ?>
                        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                        <?php
                      } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                      $rows = mysql_num_rows($materia_prima);
                      if($rows > 0) {
                        mysql_data_seek($materia_prima, 0);
                        $row_materia_prima = mysql_fetch_assoc($materia_prima);
                      }
                      ?>
                    </select></td>
                    <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                  </tr>
                  <tr  >
                    <td nowrap="nowrap"id="fuente1">&nbsp;</td>
                    <td id="fuente4"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
                      <input name="und[]" type="hidden" id="und[]" value="1" />
                      <select name="id[]" id="id[]" style="width:80px">
                        <option value="0">MEZCLA 4</option>
                        <?php
                        do {  
                          ?>
                          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                          <?php
                        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                        $rows = mysql_num_rows($materia_prima);
                        if($rows > 0) {
                          mysql_data_seek($materia_prima, 0);
                          $row_materia_prima = mysql_fetch_assoc($materia_prima);
                        }
                        ?>
                      </select></td>
                      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                    </tr>
                    <tr>
                      <td nowrap="nowrap"id="fuente1">ALCOHOL</td>
                      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                        <input name="und[]" type="hidden" id="und[]" value="1" />
                        <select name="id[]" id="id[]" style="width:80px">
                          <option value="0">ALCOHOL</option>
                          <?php
                          do {  
                            ?>
                            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                            <?php
                          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                          $rows = mysql_num_rows($materia_prima);
                          if($rows > 0) {
                            mysql_data_seek($materia_prima, 0);
                            $row_materia_prima = mysql_fetch_assoc($materia_prima);
                          }
                          ?>
                        </select></td>
                        <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                      </tr>
                      <tr>
                        <td nowrap="nowrap"id="fuente1">ACETATO NPA</td>
                        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                          <input name="und[]" type="hidden" id="und[]" value="1" />
                          <select name="id[]" id="id[]" style="width:80px">
                            <option value="0">ACETATO</option>
                            <?php
                            do {  
                              ?>
                              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                              <?php
                            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                            $rows = mysql_num_rows($materia_prima);
                            if($rows > 0) {
                              mysql_data_seek($materia_prima, 0);
                              $row_materia_prima = mysql_fetch_assoc($materia_prima);
                            }
                            ?>
                          </select></td>
                          <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                        </tr>
              <!--<tr>
    <td nowrap="nowrap"id="fuente1">BARNIZ</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="8" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td nowrap="nowrap"id="fuente1">METOXIPROPANOL</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap"id="fuente1">VISCOSIDAD</td>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="1" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr>
              <!--<tr>
    <td nowrap="nowrap"id="fuente1">PANTONE</td>
    <td id="fuente1"><input name="pantone"  type="text"  placeholder="Pant" value="<?php echo $row_ref['pantone1_egp'] ?>" size="3"/></td>
    <td id="fuente1">&nbsp;</td>
  </tr>-->
              <!--<tr>
    <td nowrap="nowrap"id="fuente1">SOLVENTE</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="12" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td nowrap="nowrap"id="fuente2">&nbsp;</td>
    <td colspan="2" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 1</td>
  </tr>
  <tr>
    <td nowrap="nowrap"id="fuente1">ANILOX</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value=""/></td>
    </tr>
    <tr>
      <td nowrap="nowrap"id="fuente5">BCM</td>
      <td id="fuente5"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="1" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente5"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value=""/></td>
      </tr>
              <!--<tr>
    <td nowrap="nowrap"id="fuente1">BASE</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="14" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td nowrap="nowrap"id="fuente1">FLEJES</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="15" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>
  <tr>
    <td nowrap="nowrap"id="fuente1">OPTURADOR</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="16" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td nowrap="nowrap"id="fuente1">REF STICK</td>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="17" />
      <input name="und[]" type="hidden" id="und[]" value="1" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref.Stick</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color2_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?> 

            <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone2_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?>
          </option>

          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr >
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="2" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="2" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0" selected="selected">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="2" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="2" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="2" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="2" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr>
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="8" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="2" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr>
              <!--<tr>
    <td id="fuente1"><input name="pantone"  type="text"  placeholder="Pant" value="<?php echo $row_ref['pantone2_egp'] ?>" size="3"/></td>
    <td id="fuente1">&nbsp;</td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="12" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 2</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value=""/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="2" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente7"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value=""/></td>
      </tr>
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="14" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="15" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="16" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="17" />
      <input name="und[]" type="hidden" id="und[]" value="2" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref.Stick</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color3_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone3_egp']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="3" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="3" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="3" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="3" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="3" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="3" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr>
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="8" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="3" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr>
              <!--<tr>
    <td id="fuente1"><input name="pantone"  type="text"  placeholder="Pant" value="<?php echo $row_ref['pantone3_egp'] ?>" size="3"/></td>
    <td id="fuente1">&nbsp;</td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="12" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 3</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value="" /></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="3" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente8"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" /></td>
      </tr>
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="14" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="15" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="16" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="17" />
      <input name="und[]" type="hidden" id="und[]" value="3" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref.Stick</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color4_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone4_egp']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="4" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="4" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="4" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="4" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="4" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="4" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr>
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="8" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="4" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr>
              <!--<tr>
    <td id="fuente1"><input name="pantone"  type="text"  placeholder="Pant" value="<?php echo $row_ref['pantone4_egp'] ?>" size="3"/></td>
    <td id="fuente1">&nbsp;</td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="12" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 4</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value="" /></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="4" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente9"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" /></td>
      </tr>
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="14" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="15" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="16" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
              <!--<tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="17" />
      <input name="und[]" type="hidden" id="und[]" value="4" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">Ref.Stick</option>
        <?php
do {  
?>
        <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
        <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
    <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
  </tr>-->
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color5_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="5" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone5_egp']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="5" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="5" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="5" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="5" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="5" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="5" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr>
              
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="5" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="5" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr> 
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 5</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="5" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value="" /></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="5" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente10"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" /></td>
      </tr> 
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color6_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="6" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone6_egp']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="6" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="6" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="6" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="6" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="6" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="6" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr> 
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="6" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="6" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr> 
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 6</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="6" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value="" /></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="6" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" /></td>
      </tr> 
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color7_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="7" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone7_egp']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]2"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]2"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="7" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="7" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="7" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="7" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="7" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="7" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr> 
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="7" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="7" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr> 
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 7</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="7" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value="" /></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="7" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente12"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" /></td>
      </tr> 
</table></td>
<td><table id="tablainterna2">
  <tr>
    <td colspan="2" nowrap="nowrap" id="fuente1"><?php echo $row_ref['color8_egp'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="1" />
      <input name="und[]" type="hidden" id="und[]" value="8" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">COLOR</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_referencia['pantone8_egp']))) {echo "selected=\"selected\"";} ?>><?php echo   $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td nowrap="nowrap" id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="2" />
        <input name="und[]" type="hidden" id="und[]" value="8" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">MEZCLA 1</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
            <?php
          } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
          $rows = mysql_num_rows($materia_prima);
          if($rows > 0) {
            mysql_data_seek($materia_prima, 0);
            $row_materia_prima = mysql_fetch_assoc($materia_prima);
          }
          ?>
        </select></td>
        <td nowrap="nowrap" id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="3" />
          <input name="und[]" type="hidden" id="und[]" value="8" />
          <select name="id[]" id="id[]" style="width:80px">
            <option value="0">MEZCLA 2</option>
            <?php
            do {  
              ?>
              <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
              <?php
            } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
            $rows = mysql_num_rows($materia_prima);
            if($rows > 0) {
              mysql_data_seek($materia_prima, 0);
              $row_materia_prima = mysql_fetch_assoc($materia_prima);
            }
            ?>
          </select></td>
          <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
        </tr>
        <tr>
          <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="4" />
            <input name="und[]" type="hidden" id="und[]" value="8" />
            <select name="id[]" id="id[]" style="width:80px">
              <option value="0">MEZCLA 3</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                <?php
              } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
              $rows = mysql_num_rows($materia_prima);
              if($rows > 0) {
                mysql_data_seek($materia_prima, 0);
                $row_materia_prima = mysql_fetch_assoc($materia_prima);
              }
              ?>
            </select></td>
            <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="5" />
              <input name="und[]" type="hidden" id="und[]" value="8" />
              <select name="id[]" id="id[]" style="width:80px">
                <option value="0">MEZCLA 4</option>
                <?php
                do {  
                  ?>
                  <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                  <?php
                } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                $rows = mysql_num_rows($materia_prima);
                if($rows > 0) {
                  mysql_data_seek($materia_prima, 0);
                  $row_materia_prima = mysql_fetch_assoc($materia_prima);
                }
                ?>
              </select></td>
              <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
            </tr>
            <tr>
              <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="6" />
                <input name="und[]" type="hidden" id="und[]" value="8" />
                <select name="id[]" id="id[]" style="width:80px">
                  <option value="0">ALCOHOL</option>
                  <?php
                  do {  
                    ?>
                    <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                    <?php
                  } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                  $rows = mysql_num_rows($materia_prima);
                  if($rows > 0) {
                    mysql_data_seek($materia_prima, 0);
                    $row_materia_prima = mysql_fetch_assoc($materia_prima);
                  }
                  ?>
                </select></td>
                <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
              </tr>
              <tr>
                <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="7" />
                  <input name="und[]" type="hidden" id="und[]" value="8" />
                  <select name="id[]" id="id[]" style="width:80px">
                    <option value="0">ACETATO</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
                      <?php
                    } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
                    $rows = mysql_num_rows($materia_prima);
                    if($rows > 0) {
                      mysql_data_seek($materia_prima, 0);
                      $row_materia_prima = mysql_fetch_assoc($materia_prima);
                    }
                    ?>
                  </select></td>
                  <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
                </tr> 
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="9" />
      <input name="und[]" type="hidden" id="und[]" value="8" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0"></option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"><?php echo  $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?></option>
          <?php
        } while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
        $rows = mysql_num_rows($materia_prima);
        if($rows > 0) {
          mysql_data_seek($materia_prima, 0);
          $row_materia_prima = mysql_fetch_assoc($materia_prima);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="%"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="10" />
        <input name="und[]" type="hidden" id="und[]" value="8" />
        <input name="id[]" type="hidden" id="id[]" value="1" /></td>
        <td id="fuente4"><input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]"  size="3" value="" placeholder="segundos"/></td>
      </tr> 
  <tr>
    <td colspan="3" nowrap="nowrap"id="fuente2">ANILOX UNIDAD 8</td>
  </tr>
  <tr>
    <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="13" />
      <input name="und[]" type="hidden" id="und[]" value="8" />
      <select name="id[]" id="id[]" style="width:80px">
        <option value="0">ANIX/REF</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
          <?php
        } while ($row_anilox = mysql_fetch_assoc($anilox));
        $rows = mysql_num_rows($anilox);
        if($rows > 0) {
          mysql_data_seek($anilox, 0);
          $row_anilox = mysql_fetch_assoc($anilox);
        }
        ?>
      </select></td>
      <td id="fuente1"><input name="valor[]"  style="width:60px" min="0" step="1" type="number"  id="valor[]"  size="3" value="" /></td>
    </tr>
    <tr>
      <td id="fuente1"><input name="id_m[]" type="hidden" id="id_m[]" value="18" />
        <input name="und[]" type="hidden" id="und[]" value="8" />
        <select name="id[]" id="id[]" style="width:80px">
          <option value="0">ANIX/BCM</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_anilox['id_insumo']?>"><?php echo $row_anilox['descripcion_insumo']." (CODIGO) ".$row_anilox['codigo_insumo']?></option>
            <?php
          } while ($row_anilox = mysql_fetch_assoc($anilox));
          $rows = mysql_num_rows($anilox);
          if($rows > 0) {
            mysql_data_seek($anilox, 0);
            $row_anilox = mysql_fetch_assoc($anilox);
          }
          ?>
        </select></td>
        <td id="fuente13"><input name="valor[]"  style="width:60px" min="0" step="0.01" type="number"  id="valor[]"  size="3" value="" /></td>
      </tr> 
</table></td>
</tr>
<tr>
  <td colspan="2" id="fuente1"></td>
  <td colspan="2" id="fuente1">&nbsp;</td>
  <td id="fuente1">&nbsp;</td>
  <td id="fuente1">&nbsp;</td>
  <td id="fuente1">&nbsp;</td>
  <td id="fuente1">&nbsp;</td>
</tr>
<tr>
  <td width="137" colspan="8" id="fuente2"><textarea name="observ_pmi" id="observ_pmi" cols="100%" rows="2" placeholder="OBSERVACIONES"onblur="conMayusculas(this)"></textarea></td>
</tr>
<tr id="tr1">
  <td colspan="8" id="fuente2"><input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
    <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
    <input type="hidden" name="id_proceso" id="id_proceso" value="2"/>
    <input type="hidden" name="b_borrado_pmi" id="b_borrado_pmi" value="0"/></td>
  </tr>
  <tr id="tr1">
    <td colspan="100%" id="titulo4">CARACTERISTICAS DE IMPRESION</td>
  </tr>
  <tr>
    <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>
    <td id="fuente1"><?php $id_c=mysql_result($caract_valor,$x,id_c); $var=mysql_result($caract_valor,$x,str_nombre_caract_c); echo $var; ?>
      <input name="id_cv[]" type="hidden" value="<?php echo $id_c; ?>" />
      <input name="valor_cv[]" required="required" type="number" style="width:37px" min="0"step="1"  placeholder="Cant/Und" value=""/></td>
      <?php  } ?>
    </tr>
    <tr>
      <td colspan="8" id="fuente2"><input type="submit" name="Guardar" id="Guardar" value="Guardar " /></td>
    </tr>
  </table>
  <table>
  </table></td>
</tr>
</table>
<input type="hidden" name="id_pm_cv" id="id_pm_cv" value="0"/>
<input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_ref['version_ref']; ?>" />
<input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="2"/>
<input type="hidden" name="id_ref_cv" id="id_ref_cv" value="<?php echo $row_ref['id_ref']; ?>"/>
<input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_ref['cod_ref']; ?>"/>
<input type="hidden" name="fecha_registro_cv" id="fecha_registro_cv"  value="<?php echo date("Y-m-d"); ?>"/>
<input type="hidden" name="str_registro_cv" id="str_registro_cv" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
<input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="0"/>
<input type="hidden" name="MM_insert" value="form1">
</form>  
</td></tr>
</table>
</div>
<b class="spiffy"> 
  <b class="spiffy5"></b>
  <b class="spiffy4"></b>
  <b class="spiffy3"></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>


</div>
</body>
</html>

<?php 
    $ref=$row_ref['id_ref'];
    $bandera = 0;
    $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$ref' and id_proceso='2'";
    $resultci= mysql_query($sqlci);
    $row_ci = mysql_fetch_assoc($resultci);
    $numci= mysql_num_rows($resultci);
  
    if($numci > 0)
    {  
        $bandera = $numci;
    }
     ?>
<script type="text/javascript">
 /*$('#mydiv textarea').attr('readonly', 'readonly');*/
  var bandera = '<?php echo $bandera;?>';
  if(bandera  > 0){
     $('#mydiv').find('input, textarea, button, select').attr('disabled','disabled');
      swal("La referencia ya tiene Caracteristicas y Mezclas de Impresion!"); 
  }


</script>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia_copia);

mysql_free_result($color);

mysql_free_result($ref);

mysql_free_result($materia_prima);

mysql_close($conexion1);

?>
