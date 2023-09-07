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

$maxRows_registros = 35;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
	$pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;


$colname_busqueda= "-1";
 $_GET['clientes'];
if (isset($_GET['cod_ref'])&& $_GET['cod_ref']!='0'&& $_GET['clientes']=='0') {
	$cod_ref = $_GET['cod_ref'];
	$busqueda = " and ref.cod_ref = '$cod_ref'"; 
}else if(isset($_GET['clientes'])&& $_GET['clientes']!='0'&& $_GET['cod_ref']=='0'){
	$clientes = $_GET['clientes'];
	$busqueda = " and cref.Str_nit = '$clientes'"; 
}else if(isset($_GET['clientes'])&& isset($_GET['cod_ref'])&& $_GET['clientes']!='0'&& $_GET['cod_ref']!='0'){
	$cod_ref = $_GET['cod_ref'];
	$clientes = $_GET['clientes'];
	$busqueda = "and ref.cod_ref = '$cod_ref' and cref.Str_nit = '$clientes'"; 
}else{
	$busqueda =""; 
} 

$row_registros=$conexion->buscarListar("tbl_referencia ref","ref.cod_ref, ref.id_ref, ref.n_cotiz_ref, ref.version_ref, ref.tipo_bolsa_ref, ref.material_ref,ref.valor_impuesto","ORDER BY CONVERT(ref.cod_ref, SIGNED INTEGER) DESC","GROUP BY cref.n_referencia",$maxRows_registros,$pageNum_registros,"JOIN 
	tbl_cliente_referencia cref ON ref.cod_ref=cref.n_referencia WHERE ref.estado_ref = '1' AND ref.tipo_bolsa_ref <> 'LAMINA' AND ref.tipo_bolsa_ref <> 'PACKING LIST' ".$busqueda );


if (isset($_GET['totalRows_registros'])) {
	$totalRows_registros = $_GET['totalRows_registros'];
} else {
	$totalRows_registros = $conexion->conteo('tbl_referencia'); 
} 
$totalPages_registros = floor($totalRows_registros/$maxRows_registros)-1;


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

$row_ref = $conexion->llenaListas('tbl_referencia',"",'ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC','cod_ref'); 

$row_cliente = $conexion->llenaListas('cliente',"",'ORDER BY nombre_c ASC','id_c,nit_c,nombre_c'); 
 

//BOLSA
$row_referencianueva=$conexion->llenarCampos("tbl_cotiza_bolsa","WHERE Tbl_cotiza_bolsa.B_estado = '1' AND (Tbl_cotiza_bolsa.B_generica = '0' OR Tbl_cotiza_bolsa.B_generica = '2') AND  Tbl_cotiza_bolsa.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)","","*");

//PACKING
$row_referencianueva2=$conexion->llenarCampos("tbl_cotiza_packing","WHERE Tbl_cotiza_packing.B_estado = '1' AND Tbl_cotiza_packing.B_generica = '0' AND Tbl_cotiza_packing.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)","","*");

//LAMINAS
$row_referencianueva3=$conexion->llenarCampos("tbl_cotiza_laminas","WHERE Tbl_cotiza_laminas.B_estado = '1' AND Tbl_cotiza_laminas.B_generica = '0' AND Tbl_cotiza_laminas.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)","","*");
 
?>
<html>
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
<!--onLoad=" setTimeout('refrescar()', 15000);"-->
<body>
	<script>
	    $(document).ready(function() { $(".busqueda").select2(); });
	</script>
	<div align="center">
		<table >
			<tr>
				<td><form action="referencias3.php" method="get" name="consulta">
					<select name="cod_ref" id="cod_ref"  class="busqueda selectsMedio ">
						<option value="0"<?php if (!(strcmp(0, $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>REF.</option>
						<?php foreach ($row_ref as $row_ref) { ?>
							<option value="<?php echo $row_ref['cod_ref']?>"<?php if (!(strcmp($row_ref['cod_ref'], $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>
								<?php echo $row_ref['cod_ref'];?>
							</option>
						<?php } ?>
					</select> &nbsp;&nbsp;
					<select name="clientes" id="clientes" class="busqueda selectsGrande">
						<option value="0"<?php if (!(strcmp(0, $_GET['clientes']))) {echo "selected=\"selected\"";} ?>>CLIENTE</option>
						<?php foreach ($row_cliente as $row_cliente) { ?>
							<option value="<?php echo $row_cliente['nit_c']?>"<?php if (!(strcmp($row_cliente['nit_c'], $_GET['clientes']))) {echo "selected=\"selected\"";} ?>>
								<?php echo $row_cliente['nombre_c'];?>
							</option>
						<?php } ?>
					</select> &nbsp;&nbsp; <input type="submit" class="botonGMini" style='width:90px; height:25px' name="Submit" value="FILTRO" />
				</form>
			</td>
		</tr>
	</table>
</div>
<br>
<div align="center"> 
  <table >
    <tr id="tr1">
     <td id="acceso2"><strong>LISTADO DE REFERENCIAS ACTIVAS</strong><?php if($row_referencianueva['N_referencia_c'] <> ''||$row_referencianueva2['N_referencia_c'] <> ''||$row_referencianueva3['N_referencia_c'] <> '') { ?> <a class="editar" href="referencia_nueva1.php" target="_top"><img src="images/falta.gif" alt="REFERENCIAS NUEVAS" title="REFERENCIAS NUEVAS" border="0" style="cursor:hand;"></a><?php } ?>
     <?php $id=$_GET['id']; if($id=='1') { echo "REFERENCIA ELIMINADA"; } ?>
   </td>
   <td id="dato3"><a class="editar" href="referencias.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a><a class="editar" href="referencias.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a class="editar" href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a class="editar" href="revision.php" target="_top"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a class="editar" href="verificacion.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a class="editar" href="control_modificaciones.php" target="_top"><img src="images/m.gif" alt="MODIFICACIONES" title="MODIFICACIONES" border="0" style="cursor:hand;" /></a><a class="editar" href="validacion.php" target="_top"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES" border="0" style="cursor:hand;" /></a><a class="editar" href="ficha_tecnica.php" target="_top"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;" /></a></td>
 </tr>
</table>
<br>
<table >
	<tr>
		<td class="centrado2">REF</td>
		<td class="centrado5">VERSION</td>
		<td class="centrado5">COTIZ</td>
		<td class="Estilo2">TIPO </td>
		<td class="Estilo1">MATERIAL</td>
		<td class="centrado5">IMPUESTO</td>
		<td class="centrado5">HOJA MAESTRA</td>    
		<td class="centrado5">ARTE</td>
		<td class="Estilo2">FECHA ARTE</td>
		<td class="centrado5">CIRELES</td>
		<td class="Estilo5">CLIENTES</td>
		<td class="Estilo5">REV</td>
		<td class="Estilo5">VER</td>
		<td class="Estilo5">C.M.</td>
		<td class="Estilo5">VAL</td>
		<td class="Estilo5">FT</td> 
		<td class="Estilo5">CERT&nbsp;</td>
		<td class="centrado5">OBS</td>      
	</tr>
 
		<?php foreach($row_registros as $row_referencias) {  ?>
		<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">   
			<td class="centrado6"><strong><a href="referencia_bolsa_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['cod_ref']; ?></a>
			</strong></td>
			<td class="centrado6"><a class="editar" href="referencia_bolsa_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['version_ref']; ?></a>
			</td>
			<td class="derecha1"><a class="editar" href="control_tablas.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "2"; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['n_cotiz_ref']; ?></a>
			</td>
			<td class="Estilo4"><a class="editar" href="referencia_bolsa_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['tipo_bolsa_ref']; ?></a>
			</td>
			<td class="Estilo3"><a class="editar" href="referencia_bolsa_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['material_ref']; ?></a>
			</td>
			<td class="centrado6"><a class="editar" href="referencia_bolsa_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $row_referencias['valor_impuesto']; ?></a>
			</td>
			<td class="centrado6"><?php 
			$id_ref=$row_referencias['id_ref'];
			$resultmp = $conexion->llenarCampos('tbl_produccion_mezclas', "WHERE id_ref_pm='$id_ref' and id_proceso='1'", "","id_pm,id_ref_pm,int_cod_ref_pm" ); 
			$id_pm= $resultmp['id_pm'];
			$id_ref_pm= $resultmp['id_ref_pm']; 
			$cod_ref = $resultmp['int_cod_ref_pm']; 

			if($id_ref_pm !='') :
				if($id_ref_pm!= '' && $id_pm!='') : ?>
					<a class="editar" href="hoja_maestra_vista.php?id_ref=<?php echo $id_ref_pm; ?>&id_pm=<?php echo $id_pm; ?>&cod_ref=<?php echo $cod_ref; ?>" target="_blank" ><img src="images/m.gif" alt="HOJA MAESTRA"title="HOJA MAESTRA" border="0" style="cursor:hand;"></a>
					<?php else: ?>
					<?php endif;
				endif;
				if($id_ref_pm == ''):?>
					<a class="editar" href="referencia_bolsa_vista.php?cod_ref=<?php echo $row_referencias['cod_ref']; ?>&n_cotiz=<?php echo $row_referencias['n_cotiz_ref']; ?>&id_ref=<?php echo $row_referencias['id_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_blank" style="text-decoration:none; color:#000000"> - - </a>
				<?php endif; ?>
			</td>
           
			<td class="centrado6"><?php $ref=$row_referencias['id_ref'];
			$cod_ref=$row_referencias['cod_ref'];
			$resultverif = $conexion->llenarCampos('verificacion', "WHERE id_ref_verif='$ref' AND estado_arte_verif='2'", "ORDER BY id_verif DESC","userfile,fecha_aprob_arte_verif" ); 
			$muestra= $resultverif['userfile'];
			$fecha= $resultverif['fecha_aprob_arte_verif'];

		 

				if($muestra != ''):
					  $url ="//s:/archivo/$muestra";
					?> 
					 <!-- <a class="editar" href="#"><img src="S:/archivo/001-00.pdf" alt="texto descriptivo"> </a> -->
					<!-- <a class="editar" href="<?php echo $url;?>"><img src="images/arte.gif" alt="<?php echo $muestra;?>" title="ARTE" border="0" style="cursor:hand;"  /></a> -->
					<a class="editar" href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"><img src="images/arte.gif" alt="<?php echo $muestra;?>" title="ARTE" border="0" style="cursor:hand;"  /></a><?php
				else:
					echo "- -"; 
				endif;
			 ?>
		</td>


		<td class="centrado4">
			<?php if($fecha != ''): echo $fecha; endif; if($fecha == ''): echo "- -";endif; ?>
		</td>

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

		<td class="centrado6"><a class="editar" href="referencia_cliente.php?id_ref=<?php echo $row_referencias['id_ref']; ?>&cod_ref=<?php echo $row_referencias['cod_ref']; ?>" target="_blank">
			<?php 
			$cod_ref=$row_referencias['cod_ref'];
			$resultrefcliente = $conexion->llenarCampos('tbl_cliente_referencia', "WHERE N_referencia='$cod_ref'", "","N_referencia" ); 
			$numref= $resultrefcliente['N_referencia'];

			if($numref !=''): ?>
				<img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0" style="cursor:hand;"><?php  else:  ?> - - <?php endif; ?> </a> 
			</td>




			<td class="centrado6"><?php $ref=$row_referencias['id_ref'];

			$row_revision = $conexion->llenarCampos('revision', "WHERE id_ref_rev='$ref'", "","id_rev,id_ref_rev" ); 
			$numrev=$row_revision['id_rev'];


			if($numrev !=''): ?><a class="editar" href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_blank" ><img src="images/r.gif" alt="REVISION" title="REVISION" border="0" style="cursor:hand;"></a><?php else: ?><a class="editar" href="revision_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a><?php endif; ?> 
		</td>

		<td class="centrado6">
			<?php 

		if($numverif !=''): ?> <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php else: ?> <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php endif; ?>
	</td>
	<td class="centrado6">
		<?php $ref=$row_referencias['id_ref'];

	$resultcm = $conexion->llenarCampos('control_modificaciones', "WHERE id_ref_cm='$ref'", "","id_ref_cm" ); 
	$numcm=$resultcm['id_ref_cm'];

	if($numcm !=''): ?> <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/m.gif" alt="VERIFICACION" title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php else: ?> <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php endif; ?>
</td>
	<td class="centrado6">
	<?php 
	$ref=$row_referencias['id_ref'];
	$row_val = $conexion->llenarCampos('validacion', "WHERE id_ref_val='$ref'", "ORDER BY id_val DESC LIMIT 1","id_val,id_ref_val,version_val" ); 
	$id_ref_val=$row_val['id_ref_val'];
	$version=$row_val['version_val'];
	$id_val = $row_val['id_val'];

	$row_val = $conexion->llenarCampos('verificacion', "WHERE id_ref_verif='$id_ref_val' and version_ref_verif='$version' and estado_arte_verif != '2'", "ORDER BY id_verif DESC LIMIT 1","version_ref_verif,estado_arte_verif" ); //2 es aceptado
	$numverif2=$row_val['version_ref_verif'];
 
	if($numverif2 !=''): ?>
		<a class="editar" href="validacion_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"><img src="images/v_rojo.gif" alt="VALIDACION" title="HAY CAMBIOS RECIENTES EN LA VERSION O EL ARTE NO ESTA APROBADO"border="0" style="cursor:hand;"></a>
	<?php elseif($id_ref_val !='') : ?> 
		<a class="editar" href="validacion_vista.php?id_val=<?php echo $id_val; ?>" target="_blank"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;"></a>
	<?php elseif($id_ref_val == ''): ?> 
		<a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a>
		<?php else: ?> <a class="editar" href="validacion_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> 
	<?php endif; ?>
	</td>


	<td class="centrado6">
		<?php $ref=$row_referencias['id_ref'];
		$row_ft = $conexion->llenarCampos('tblfichatecnica', "WHERE id_ref_ft='$ref'", "","n_ft" ); 
		$numft=$row_ft['n_ft'];

		if($numft !=''): ?> <a class="editar" href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>" target="_blank"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php else: ?> <a class="editar" href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php endif; ?></td>

		<td class="centrado6">
			<?php $refc=$row_referencias['id_ref'];
			$row_cert = $conexion->llenarCampos('tblcertificacion', "WHERE idref='$refc' ", "","idcc,idref" ); 
			$numcert=$row_cert['idref'];

			if($numcert !=''): ?> <a class="editar" href="certificacion_listado.php?id_ref=<?php echo $row_cert['idref']; ?>" target="_blank"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;"></a> <?php else: ?> <a class="editar" href="certificacion_add.php?id_ref=<?php echo $row_referencias['id_ref']; ?>" target="_blank"> - - </a> <?php endif; ?></td>
			<?php
			$refc2=$row_referencias['cod_ref'];
			$row_obs_ref = $conexion->llenarCampos('tbl_observaciones_ref', "WHERE ref = '$refc2' ", "","ref" ); 
			$numobs_ref=$row_obs_ref['ref'];

			if($numobs_ref !=''):
				$verobs="<strong style='color: red;' >Ver</strong>";
			else:
				$verobs='No Obs';
			endif;
			?> 
			<td class="centrado6"><a class="editar" href="obs_ref.php?id_ref=<?php echo $row_referencias['cod_ref']; ?>" target="_blank" style="text-decoration:none; color:#000000"><?php echo $verobs; ?></a></td>   
		</tr>
	<?php } ?>
	<tr>
		<td>
</td>
</tr>

</table>
  
</div>
<br>
<?php if(empty($busqueda) ):?> 
	<table id="tabla1"  >
		<tr>
			<td width="50%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
				<a  href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero&nbsp;&nbsp;&nbsp;&nbsp;</a>
			<?php } // Show if not first page ?>
		</td>
		<td width="25%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
			<a  href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
		<?php } // Show if not first page ?>
	</td>
	<td width="25%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
		<a  href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">&nbsp;&nbsp;&nbsp;&nbsp;Siguiente</a>
	<?php } // Show if not last page ?>
</td>
<td width="25%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
	<a  href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&nbsp;&nbsp;&nbsp;&nbsp;Ultimo</a>
<?php } // Show if not last page ?>
</td>
</tr>
</table>
<?php  endif;?>
</body>
</html>
<script>
	
	$(document).ready(function(){
	  var editar =  "<?php echo $_SESSION['no_edita'];?>";
	
	  if( editar==0 ){
	     
	    $("input").attr('disabled','disabled'); 
	    $("textarea").attr('disabled','disabled');
	    /*$("select").attr('disabled','disabled');*/
	    $("button").attr('disabled','disabled');


	     $('a').each(function() { 
	      $(".editar").attr('href', '#');
	    }); 
	              swal("No Autorizado", "Sin permisos para editar :)", "error"); 
	  } 
	});

</script>
<?php
/*mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($referencias);*/
?>