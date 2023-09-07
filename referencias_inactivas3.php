<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
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

$row_referencias = $conexion->llenaListas('Tbl_referencia',"WHERE estado_ref = '0' ","ORDER BY n_egp_ref DESC",'*'); 
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
	<div align="center">
	<div id="linea1">
	<table id="tabla3">
	  <tr id="tr1">
	<td id="numero2"><strong>LISTADO DE REFERENCIAS INACTIVAS</strong></td>
	<td id="dato3">
		<a href="referencias.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS"title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a>
		<a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS"title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a>
		<a href="revision.php" target="_top"><img src="images/r.gif" alt="REVISIONES" border="0"title="REVISIONES" style="cursor:hand;" /></a>
		<a href="verificacion.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES"title="VERIFICACIONES" border="0" style="cursor:hand;" /></a>
		<a href="control_modificaciones.php" target="_top"><img src="images/m.gif" alt="MODIFICACIONES" title="MODIFICACIONES" border="0" style="cursor:hand;" /></a>
		<a href="validacion.php" target="_top"><img src="images/v.gif" alt="VALIDACIONES"title="VALIDACIONES" border="0" style="cursor:hand;" /></a>
		<a href="ficha_tecnica.php" target="_top"><img src="images/f.gif" alt="FICHAS TECNICAS"title="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
	  </tr>
	</table>
	<table id="tabla3">
	    <tr>
	      <td class="centrado5">REF</td>
	      <td class="centrado5">VERSION</td>
	      <td class="centrado5">COTIZ</td>
	      <td class="Estilo2">TIPO BOLSA </td>
	      <td class="Estilo1">MATERIAL</td>
	      <td class="Estilo5">ARTE</td>
	      <td class="Estilo2">FECHA ARTE</td>
	      <td class="Estilo5">CLIENTES</td>
	      <td class="Estilo5">REV</td>
	      <td class="Estilo5">VER</td>
	      <td class="Estilo5">C.M.</td>
	      <td class="Estilo5">VAL</td>
	      <td class="Estilo5">FT</td>
	    </tr>
	  </table>
	</div>
</div>
<br>
<div align="center">
<table id="tabla3">
	<?php foreach($row_referencias as $row_referencias) {  ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">   
      <td class="centrado6"><strong><a href="control_tablas.php?cod_ref= <?php echo $row_referencias['cod_ref']; ?>&n_cotiz= <?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref= <?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "3"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['cod_ref']; ?></a></strong></td>
      <td class="centrado6"><a href="control_tablas.php?cod_ref= <?php echo $row_referencias['cod_ref']; ?>&n_cotiz= <?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref= <?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "3"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['version_ref']; ?></a></td>
      <td class="derecha1"><a href="control_tablas.php?n_cotiz= <?php echo $row_referencias['n_cotiz_ref']; ?>&cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref= <?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "4"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['n_cotiz_ref']; ?></a></td>
      <td class="Estilo4"><a href="control_tablas.php?cod_ref= <?php echo $row_referencias['cod_ref']; ?>&n_cotiz= <?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref= <?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "3"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['tipo_bolsa_ref']; ?></a></td>
      <td class="Estilo3"><a href="control_tablas.php?cod_ref= <?php echo $row_referencias['cod_ref']; ?>&n_cotiz= <?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref= <?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "3"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['material_ref']; ?></a></td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $cod_ref=$row_referencias['cod_ref'];
	  $sqlverif="SELECT * FROM verificacion WHERE id_ref_verif='$ref' AND estado_arte_verif='2' ORDER BY id_verif DESC";
	  $resultverif= mysql_query($sqlverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { 
	  $muestra = mysql_result($resultverif, 0, 'userfile'); 
	  $fecha = mysql_result($resultverif, 0, 'fecha_aprob_arte_verif'); 
	  if($muestra != '') 
	  { ?><a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"><img src="images/arte.gif"title="ARTE" alt="<?php echo $muestra;?>" border="0" style="cursor:hand;"  /></a><?php
	  } 
	  if($muestra == '')
	  { 
	  echo "- -"; 
	  } 
	  } 
	  if($numverif < '1')
	  { echo "- -"; } ?></td>
      <td class="centrado4"><?php if($fecha != '') { echo $fecha; } if($fecha == '') { echo "- -"; } ?></td>
      <td class="centrado6"><a href="referencia_cliente.php?id_ref=<?php echo $row_referencias['id_ref']; ?>&cod_ref=<?php echo $row_referencias['cod_ref']; ?>" target="_blank"><?php $cod_ref=$row_referencias['cod_ref'];
	  $sqlrefcliente="SELECT * FROM Tbl_cliente_referencia WHERE N_referencia='$cod_ref'";
	  $resultrefcliente= mysql_query($sqlrefcliente);
	  $numref= mysql_num_rows($resultrefcliente);
	  if($numref >='1')
	  { ?><img src="images/cliente.gif" alt="CLIENTES"title="CLIENTES" border="0" style="cursor:hand;"><?php } else { ?> - - <?php } ?> </a> </td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlrevision="SELECT * FROM revision WHERE id_ref_rev='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?><a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_blank" ><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a><?php } ?>  </td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlverif="SELECT * FROM verificacion WHERE id_ref_verif='$ref'";
	  $resultverif= mysql_query($sqlverif);
	  $row_verif = mysql_fetch_assoc($resultverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { ?> <a href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?></td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlcm="SELECT * FROM control_modificaciones WHERE id_ref_cm='$ref'";
	  $resultcm= mysql_query($sqlcm);
	  $numcm= mysql_num_rows($resultcm);
	  if($numcm >='1')
	  { ?> <a href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/m.gif" alt="VERIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?></td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlval="SELECT * FROM validacion WHERE id_ref_val='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  if($numval >='1')
	  { ?>
        <a href="validacion_vista.php?id_val=<?php echo $row_val['id_val']; ?>" target="_blank"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a>
        <?php } else{ ?>
        <a href="validacion_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a>
        <?php } ?></td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlft="SELECT * FROM TblFichaTecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?> <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>" target="_blank"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?></td>
    </tr>
    <?php }  ?>
</table>
</div> 
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencias);
?>