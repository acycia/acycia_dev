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

 $maxRows_registros = 50;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
 

$row_registros = $conexion->buscarListar("Tbl_referencia","*","ORDER BY n_egp_ref DESC","",$maxRows_registros,$pageNum_registros,"WHERE estado_ref = '1' AND tipo_bolsa_ref='PACKING LIST' " );

//BOLSA
$row_referencianueva=$conexion->llenarCampos("tbl_cotiza_bolsa","WHERE Tbl_cotiza_bolsa.B_estado = '1' AND Tbl_cotiza_bolsa.B_generica = '0' AND  Tbl_cotiza_bolsa.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)","","*");

//PACKING
$row_referencianueva2=$conexion->llenarCampos("tbl_cotiza_packing","WHERE Tbl_cotiza_packing.B_estado = '1' AND Tbl_cotiza_packing.B_generica = '0' AND Tbl_cotiza_packing.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)","","*");

//LAMINAS
$row_referencianueva3=$conexion->llenarCampos("tbl_cotiza_laminas","WHERE Tbl_cotiza_laminas.B_estado = '1' AND Tbl_cotiza_laminas.B_generica = '0' AND Tbl_cotiza_laminas.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)","","*");
 

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('proveedor'); 
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
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
	<div align="center">
	  <div id="linea1">
	    <table id="tabla3">
	      <tr id="tr1">
	        <td id="acceso2"><strong>LISTADO DE REFERENCIAS ACTIVAS</strong><?php if($row_referencianueva2['N_referencia_c'] <> '') { ?> <a href="referencia_nueva1.php" target="_top"><img src="images/falta.gif" alt="REFERENCIAS NUEVAS" title="REFERENCIAS NUEVAS" border="0" style="cursor:hand;"></a><?php } ?>
	        <?php $id=$_GET['id']; if($id=='1') { echo "REFERENCIA ELIMINADA"; } ?></td>
	        <td id="dato3"><a href="referencias_p.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS"border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS"title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_p.php" target="_top"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES"border="0" style="cursor:hand;" /></a><a href="verificacion_p.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES"title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="control_modificaciones_p.php" target="_top"><img src="images/m.gif" alt="MODIFICACIONES"title="MODIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion_p.php" target="_top"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES"border="0" style="cursor:hand;" /></a><a href="ficha_tecnica_p.php" target="_top"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS"border="0" style="cursor:hand;" /></a></td>
	      </tr>
	    </table>
	  </div>
	  <table id="tabla3">
	    <tr>
	      <td class="centrado5">REF</td>
	      <td class="centrado5">VERSION</td>
	      <td class="centrado5">COTIZ</td>
	      <td class="Estilo2">TIPO </td>
	      <td class="Estilo2">IMPUESTO</td>
	      <td class="centrado5">ARTE</td>
	      <td class="Estilo2">FECHA ARTE</td>
	      <td class="centrado5">CIRELES</td>
	      <td class="Estilo5">CLIENTES</td>
	      <td class="Estilo5">REV</td>
	      <td class="Estilo5">VER</td>
	      <td class="Estilo5">C.M.</td>
	      <td class="Estilo5">VAL</td>
	      <td class="Estilo5">FT</td>
	      <td class="Estilo5">CERT</td>  
	      <td class="centrado5">OBS</td>     
	    </tr>
	  </table>
	</div>
	<br>
<div align="center">
<table id="tabla3">
	<?php foreach($row_registros as $row_referencias) {  ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">   
      <td class="centrado6"><strong><a href="referencia_packing_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "1"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['cod_ref']; ?></a></strong></td>
      <td class="centrado6"><a href="referencia_packing_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "1"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['version_ref']; ?></a></td>
      <td class="derecha1"><a href="referencia_packing_vista.php?n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "2"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['n_cotiz_ref']; ?></a></td>
      <td class="Estilo4"><a href="referencia_packing_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "1"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['tipo_bolsa_ref']; ?></a></td>
      <td class="Estilo4"><a href="referencia_packing_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "1"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['valor_impuesto']; ?></a></td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $cod_ref=$row_referencias['cod_ref'];
	  $sqlverif="SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p='$ref' AND estado_arte_verif_p='2' ORDER BY id_verif_p DESC";
	  $resultverif= mysql_query($sqlverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { 
	  $muestra = mysql_result($resultverif, 0, 'userfile_p'); 
	  $fecha = mysql_result($resultverif, 0, 'fecha_aprob_arte_verif_p'); 
	  if($muestra != '') 
	  { ?><a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"><img src="images/arte.gif" alt="<?php echo $muestra;?>"title="ARTE" border="0" style="cursor:hand;"  /></a><?php
	  }
	  if($muestra == '') 
	  { echo "- -"; 
	  } 
	  }
	  if($numverif < '1')
	  {
	  echo "- -"; 
	  } ?></td>
      <td class="centrado4"><?php if($fecha != '')
	   { echo $fecha; } if($fecha == '') { echo "- -"; } ?></td>
	    <td class="centrado6">
	   		 <?php
	   					 $ref= $row_referencias['id_ref'];
	   				   $resultverif = $conexion->llenarCampos('verificacion', "WHERE id_ref_verif='$ref'", "ORDER BY id_verif DESC","id_verif,id_ref_verif" ); 
	   				   $verif=$resultverif['id_verif'];
	   					 $numverif=$resultverif['id_ref_verif'];


	   					 $cod_ref=$row_referencias['cod_ref'];
	   					 $id_ref=$row_referencias['id_ref'];  
	   					 $resultplancha = $conexion->llenarCampos('tblreporteplanchas', "WHERE ref=$cod_ref ", "ORDER BY id DESC","id_verif,ref" ); 
	   					 $id_verif = $resultplancha['id_verif'];
	   					 $refrp = $resultplancha['ref'];
		    ?>
		         <?php if($refrp!=''): ?>
	   	           <a id="planchas" href="verificacion_cireles.php?id_verif=<?php echo $verif; ?>&cod_ref=<?php echo $cod_ref; ?>" title="<?php echo $cod_ref; ?>" target="_blank" style="text-decoration:none; color:#000000"><img src="images/sinfacturado.png" alt="<?php echo $id_verif;?>" title="PLANCHAS OK" border="0" style="cursor:hand;width: 20px;height: 20px; "  /></a>
	   	          <?php else: ?>     
	   	             <a id="planchas" href="verificacion_cireles.php?id_verif2=<?php echo $verif=='' ? $id_verif:$verif; ?>&cod_ref=<?php echo $cod_ref; ?>" title="<?php echo $cod_ref; ?>" target="_blank" style="text-decoration:none; color:#000000"> - - </a>
	   	         <?php endif; ?>
	    </td> 

      <td class="centrado6"><a href="referencia_cliente.php?id_ref=<?php echo $row_referencias['id_ref'];?>&cod_ref=<?php echo $row_referencias['cod_ref']; ?>" target="_blank"><?php $cod_ref=$row_referencias['cod_ref'];
	  $sqlrefcliente="SELECT N_referencia FROM Tbl_cliente_referencia WHERE N_referencia='$cod_ref'";
	  $resultrefcliente = mysql_query($sqlrefcliente);
	  $numref = mysql_num_rows($resultrefcliente);
	  if($numref >='1')
	  { ?><img src="images/cliente.gif" alt="CLIENTES"title="CLIENTES" border="0" style="cursor:hand;"><?php } else { ?> - - <?php } ?> </a> </td>
      
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlrevision="SELECT id_rev_p,id_ref_rev_p FROM Tbl_revision_packing WHERE id_ref_rev_p='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  
	  if($numrev >='1')
	  { ?><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_revision['id_rev_p']; ?>" target="_blank" ><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_packing_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a><?php } ?> </td>
      
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlverif="SELECT id_ref_verif_p FROM Tbl_verificacion_packing WHERE id_ref_verif_p='$ref'";
	  $resultverif= mysql_query($sqlverif);
	  $row_verif = mysql_fetch_assoc($resultverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?>
      </td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlcm="SELECT id_ref_cm FROM Tbl_control_modificaciones_p WHERE id_ref_cm='$ref'";
	  $resultcm= mysql_query($sqlcm);
	  $numcm= mysql_num_rows($resultcm);
	  if($numcm >='1')
	  { ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/m.gif" alt="VERIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?></td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlval="SELECT id_val_p,id_ref_val_p,version_ref_val_p FROM Tbl_validacion_packing WHERE id_ref_val_p='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  $id_ref_val=$row_val['id_ref_val_p'];
	  $version=$row_val['version_ref_val_p'];
	  
	  $sqlverif2="SELECT version_ref_verif_p,estado_arte_verif_p FROM Tbl_verificacion_packing WHERE id_ref_verif_p='$id_ref_val' and version_ref_verif_p='$version' and estado_arte_verif_p != '2'";
	  $resultverif2= mysql_query($sqlverif2);
	  $row_verif2 = mysql_fetch_assoc($resultverif2);
	  $numverif2= mysql_num_rows($resultverif2);	  
	  if($numverif2 >='1')
	  { ?><a href="verificacion_packing_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/v_rojo.gif" alt="VALIDACION" title="HAY CAMBIOS RECIENTES EN LA VERSION O EL ARTE NO ESTA APROBADO" border="0" style="cursor:hand;"></a> <?php }else if($numval >='1')
	  { ?> <a href="validacion_packing_vista.php?id_val_p=<?php echo $row_val['id_val_p']; ?>" target="_blank"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;"></a><?php } else if($numrev < '1'){ ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a>
	  <?php } else { ?> <a href="validacion_packing_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?>
      </td>
      <td class="centrado6"><?php $ref=$row_referencias['id_ref'];
	  $sqlft="SELECT n_ft FROM TblFichaTecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?> <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>" target="_blank"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php } ?></td>
      <td class="centrado6">
	  <?php $refc=$row_referencias['id_ref'];
	  $sqlcert="SELECT idcc,idref FROM TblCertificacion WHERE idref='$refc'";
	  $resultcert= mysql_query($sqlcert);
	  $row_cert = mysql_fetch_assoc($resultcert);
	  $numcert= mysql_num_rows($resultcert);
	  if($numcert >='1')
	  { ?> <a href="certificacion_listado.php?id_ref=<?php echo $row_cert['idref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="certificacion_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="new"> - - </a> <?php } ?></td>
		<?php
		$refc2=$row_referencias['cod_ref'];
		$sqlobs_ref="SELECT * FROM tbl_observaciones_ref WHERE ref = '$refc2'";
		$resultobs_ref= mysql_query($sqlobs_ref);
		$row_obs_ref = mysql_fetch_assoc($resultobs_ref);
		$numobs_ref= mysql_num_rows($resultobs_ref);
	    if($numobs_ref >='1')
		  {
	      $verobs="<strong style='color: red;' >Ver</strong>";
		  }else{
		  	$verobs='No Obs';
		  }
		?>
	  <td class="centrado6"><a href="obs_ref.php?id_ref=<?php echo $row_referencias['cod_ref']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $verobs; ?></a></td>        
    </tr>
    <?php } ?>
</table>
<!-- tabla para paginacion opcional -->
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
    <?php } // Show if not first page ?>
  </td>
  <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
    <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
  <?php } // Show if not first page ?>
</td>
<td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
<?php } // Show if not last page ?>
</td>
<td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
<?php } // Show if not last page ?>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencias);
?>