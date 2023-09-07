<?php
     require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
     require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once("db/db.php"); 
require_once("Controller/CmezclasIm.php");

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

//$conexion = new ApptivaDB();


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


?>
 
<?php foreach($this->row_materia_prima as $row_materia_prima) { $row_materia_prima; } ?>
<?php foreach($this->row_totalTintas as $row_totalTintas) { $row_totalTintas; } ?>

<?php 
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
		//DEVUELVO LAS TINTAS PARA ACTUALIZAR CON LAS NUEVAS
		//DEBE ESTAR EN ESTA POSICION
		$id_opInv=$_POST['id_op']; 

    //$resultre = $conexion->llenaListas('tbl_reg_kilo_producido', "WHERE op_rp = $id_opInv AND id_proceso_rkp='2'" , " ","id_rpp_rp, valor_prod_rp" );

	  $sqlre="SELECT id_rpp_rp, valor_prod_rp FROM Tbl_reg_kilo_producido WHERE op_rp = $id_opInv and id_proceso_rkp='2'";
		$resultre= mysql_query($sqlre); 
		$numere= mysql_num_rows($resultre);
    
		for($i=0; $i<$numere; $i++)
		{
    
			$id_insumo=mysql_result($resultre,$i,'id_rpp_rp');
			$cantidad=mysql_result($resultre,$i,'valor_prod_rp'); 
		$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - $cantidad   WHERE Codigo = $id_insumo";
		$resultinv=mysql_query($sqlinv, $conexion1); 
 		}	
		 
 		
	$id=($_POST['id_i']);
    foreach($id as $key=>$v)
    $a[]= $v;
	
	$cant=($_POST['cant']);
    foreach($cant as $key=>$v)
    $b[]= $v;
	
	$id_m=($_POST['id_m']);
    foreach($id_m as $key=>$ins)
    $c[]= $ins;
	
	/*$desp=($_POST['desp']);
    foreach($desp as $key=>$v)
    $d[]= $v;*/
	 	
	for($x=0; $x<count($a); $x++){
		if($a[$x]!=''&&$b[$x]!=''&&$c[$x]!=''){	
     	  $sqlcostoMP="SELECT valor_unitario_insumo AS valorkilo FROM insumo WHERE id_insumo = $c[$x]"; 
	      $resultcostoMP=mysql_query($sqlcostoMP); 
	      $numcostoMP=mysql_num_rows($resultcostoMP); 
	      $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	      $contValor=0;
        $valorMP = $row_valoresMP['valorkilo']; 
 
 $actualiza = $conexion->actualizar("Tbl_reg_kilo_producido", " id_rpp_rp=".$c[$x].", valor_prod_rp='".$b[$x]."', op_rp='".$_POST['id_op']."', int_rollo_rkp='".$_POST['rollo_rp']."', id_proceso_rkp='".$_POST['id_proceso_rkp']."', fecha_rkp='".$_POST['fecha_ini_rp']."', costo_mp='".$valorMP."' "," id_rkp=".$a[$x]."" );

/*  $insertSQLkp = "UPDATE Tbl_reg_kilo_producido SET id_rpp_rp=".$c[$x].", valor_prod_rp='".$b[$x]."', op_rp='".$_POST['id_op']."', int_rollo_rkp='".$_POST['rollo_rp']."', id_proceso_rkp='".$_POST['id_proceso_rkp']."', fecha_rkp='".$_POST['fecha_ini_rp']."', costo_mp='".$valorMP."'  WHERE id_rkp=".$a[$x]." ";
  
  mysql_select_db($database_conexion1, $conexion1);
  $Resultkp = mysql_query($insertSQLkp, $conexion1) or die(mysql_error());*/

  //ACTUALIZO LOS NUEVOS VALORES
  $sqlinv2="UPDATE TblInventarioListado SET Salida = Salida + $b[$x] WHERE Codigo = $c[$x]";
  $resultinv2=mysql_query($sqlinv2, $conexion1); 
		 	  	  
	 }

	} 
	
/*   $insertGoTo = "views/view_produccion_registro_tintas_edit.php?id_op=" . $_POST['id_op'] . "&rollo=" . $_POST['rollo_rp'] . "&fecha=" . $_POST['fecha_ini_rp'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));  */
 

 echo "<script type=\"text/javascript\">window.opener.location.href = window.opener.location.href;</script>"; 
 echo "<script type=\"text/javascript\">window.close();</script>";


}





?>


<?php 
 
/*$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);*/

 
//LLENA COMBOS DE MATERIAS PRIMAS
/* mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='8' AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima); */
//$this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='8' "," ORDER BY descripcion_insumo ASC" );

 //foreach($this->row_materia_prima as $row_materia_prima) { $row_materia_prima; } 

//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 
$id_op=$_GET['id_op']; 
/*mysql_select_db($database_conexion1, $conexion1);
$query_kilo_editar = "SELECT * FROM Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.op_rp='$id_op' AND Tbl_reg_kilo_producido.id_proceso_rkp='2' ORDER BY Tbl_reg_kilo_producido.id_rkp ASC";
$kilo_editar = mysql_query($query_kilo_editar, $conexion1) or die(mysql_error());
$row_kilo_editar = mysql_fetch_assoc($kilo_editar);
$totalRows_kilo_editar = mysql_num_rows($kilo_editar);*/


/*$colname_liquidado_tintas = "-1";
if (isset($_GET['id_op'])) {
  $colname_liquidado_tintas = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalTintas = sprintf("SELECT * FROM TblImpresionRollo, Tbl_reg_kilo_producido WHERE TblImpresionRollo.id_op_r=%s AND TblImpresionRollo.id_op_r=Tbl_reg_kilo_producido.op_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='2'",$colname_liquidado_tintas);
$totalTintas = mysql_query($query_totalTintas, $conexion1) or die(mysql_error());
$row_totalTintas = mysql_fetch_assoc($totalTintas);
$totalRows_totalTintas = mysql_num_rows($totalTintas);
*/

  
 $registros = $conexion->llenaListas('Tbl_reg_kilo_producido', "WHERE Tbl_reg_kilo_producido.op_rp='$id_op' AND Tbl_reg_kilo_producido.id_proceso_rkp='2' ",'ORDER BY Tbl_reg_kilo_producido.id_rkp ASC','id_rkp,id_rpp_rp,valor_prod_rp' );

 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
</head>
<script type="text/javascript">
    $(document).ready(function() { $(".busqueda").select2(); });
</script>
<body>
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
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2">
        <table id="tabla2">
          <tr>
            <td  nowrap="nowrap" colspan="5" id="subtitulo"> TINTA AGREGADAS
              <input name="id_op" type="hidden" id="id_op" value="<?php echo $_GET['id_op']; ?>" />
              <input name="id_ref" type="hidden" id="id_ref" value="<?php echo $_GET['id_ref']; ?>"/>
              <input name="id_proceso_rkp" type="hidden" id="id_proceso_rkp" value="2" />
              <input name="rollo_rp" type="hidden" id="rollo_rp" value="<?php echo $_GET['rollo']; ?>" /></td>
            </tr>
          <tr>
            <td colspan="4" id="dato1">Se guardara la misma fecha inicial y hora inicial con la que va a guarda todo el registro</td>
            <td  nowrap="nowrap" id="dato2">Fecha y Hora:
              <input name="fecha_ini_rp" type="datetime" min="2000-01-02" value="<?php echo $_GET['fecha']; ?>" size="19" required="required" readonly="readonly"/>
              </td>
            </tr>
          <tr>
            <td colspan="5" id="dato2">
            <?php $id_r=$_GET['id_op'];
            $sqlr="SELECT COUNT(rollo_r) AS rolloI FROM TblImpresionRollo WHERE id_op_r=$id_r"; 
            $resultr=mysql_query($sqlr); 
            $numr=mysql_num_rows($resultr); 
            if($numr >= '1') 
            {$max_rolloI=mysql_result($resultr,0,'rolloI');		 
			     } 
           ?> 
           Consumo para un total de rollos: <?php echo $max_rolloI; ?> Este Consumo es del Rollo numero: <?php echo $_GET['rollo']; ?> 
           <?php
		      if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) { 
		      	$cantTin=($_POST['cant']);
            //$a = array($cantTin); 
            $acum=array_sum($cantTin) ; 
			      $TintasAcum=($acum/$max_rolloI);
		     	$sqlsel="UPDATE tbl_reg_produccion SET int_totalKilos_tinta_rp='$TintasAcum' WHERE id_op_rp='$id_r' AND id_proceso_rp='2'";
		    	$resultsel=mysql_query($sqlsel); 
		      }
		      ?>
            
          </td>
            </tr> 
             <tr> 
               <td colspan="6" nowrap id="detalle2"><strong>Gasto Tintas - Solventes</strong></td>
             </tr>
             <tr id="tr1"> 
               <td colspan="2" nowrap="nowrap"id="detalle2">Tintas - Solventes</td>
               <td colspan="2" nowrap="nowrap"id="detalle2">Kilos Ingresados</td>
               <td colspan="2" nowrap="nowrap"id="detalle2">Kilos a Corregir</td>
             </tr>           
               <?php foreach($registros as $row_totalTintas) {  ?> 
                <tr> 
                  <td colspan="2" id="fuente1">
                    <input name="id_i[]" type="hidden" value="<?php echo $row_totalTintas['id_rkp']; ?>" />
                     <select name="id_m[]" id="id_m[]" style="width:350px"> 
                       <?php  foreach($this->row_materia_prima as $row_materia_prima ) { ?>
                           <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_totalTintas['id_rpp_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']." (CODIGO) ".$row_materia_prima['codigo_insumo']?>
                         </option>
                       <?php } ?> 
                     </select>
                    
                    <br> 
                    <?php 
                   /* $nombreMP=$row_totalTintas['id_rpp_rp']; 
                    $sqlins="SELECT id_insumo,descripcion_insumo FROM insumo WHERE id_insumo='$nombreMP'";
                    $resultins= mysql_query($sqlins);
                    $numins= mysql_num_rows($resultins);
                    if($numins >='1')
                    { 
                      $d_insu = mysql_result($resultins, 0, 'descripcion_insumo'); 
                    }*/
                    ?>
                    
                  </td>
                 <td colspan="2" id="fuente1">
                     <?php $Cant=$row_totalTintas['valor_prod_rp']; echo $Cant; $TotalTintas+=$Cant;?>
                 </td>
                 <td colspan="2" id="fuente1">
                   <input name="cant[]" type="number" style="width:80px" placeholder="kilos" min="0"step="0.01" value="<?php echo $row_totalTintas['valor_prod_rp']; ?>"/>
                 </td> 
                 </tr>
                    
                  <?php } ?>
                  
                  <tr >
                    <td id="fuente1">&nbsp;</td>
                    <td colspan="2" nowrap="nowrap"id="detalle3"><b>Total:</b>  </td>
                    <td colspan="2" nowrap="nowrap"id="detalle1"><?php echo $TotalTintas; ?></td>
                  </tr>   
                  <tr >
                    <td colspan="6" id="fuente1">&nbsp;</td> 
                  </tr> 
                   <tr>
                     <td colspan="5" id="dato2">
                      <input type="submit" class="botonGeneral" onclick="return update2();" value="EDITAR LAS UNIDADES" />
                    </td>
                  </tr>    
            <input type="hidden" name="MM_update" value="form2" />
        </table>
        </form>
      <tr>
    <td colspan="2" align="center"> 
        <table>
        <form action="delete_listado.php" method="get" name="form3">
      <tr>
        <td colspan="2" id="titulo2"> 
              <?php foreach($registros as $row_totalTintas) {  ?> 
              <input name="id[]" type="hidden" value="<?php echo $row_totalTintas['id_rkp']; ?>"/> 
              <?php } ?> 
            </td>
         <td colspan="2" id="titulo3"> 
            <input class="botonFinalizar" name="Delete" type="submit" onclick="return eliminar_u_i();" value="Delete"/>
            <input name="consumo_i" type="hidden" id="consumo_i" value="1" /> 
           </td>           
      </tr>
      </form>         
      </table>
      </td>
  </tr>
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
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($kilo_editar);
mysql_free_result($materia_prima);
?>