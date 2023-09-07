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

mysql_select_db($database_conexion1, $conexion1);
$query_fichas_tecnicas = "SELECT * FROM TblFichaTecnica ORDER BY cod_ft DESC";
$fichas_tecnicas = mysql_query($query_fichas_tecnicas, $conexion1) or die(mysql_error());
$row_fichas_tecnicas = mysql_fetch_assoc($fichas_tecnicas);
$totalRows_fichas_tecnicas = mysql_num_rows($fichas_tecnicas);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
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
	<div align="center">
	 
	  <div id="linea1">
	    <table >
	      <tr id="tr1">
	        <td id="subtitulo"><strong>LISTADO DE FICHAS TECNICAS</strong></td>
	        <td id="dato3"><a href="referencias.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php" target="_top"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="control_modificaciones.php" target="_top"><img src="images/m.gif" alt="MODIFICACIONES" title="MODIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion.php" target="_top"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES" border="0" style="cursor:hand;" /></a></td>
	      </tr>
	    </table>
	  </div>
	  <table >
	    <tr>
	      <td class="centrado5">F. T. </td>
	      <td class="Estilo2">TIPO BOLSA </td>
	      <td class="Estilo2">MATERIAL</td>
	      <td class="Estilo2">ADHESIVO</td>
	      <td class="centrado5">ARTE</td>
	      <td class="Estilo5">FECHA</td>
	      <td class="Estilo5">CLIENTES</td>
	      <td class="Estilo5">PIGM. EXT. </td>
	      <td class="Estilo5">PIGM. INT. </td>
	      <td class="Estilo5">ANCHO</td>
	      <td class="Estilo5">LARGO</td>
	      <td class="Estilo5">SOLAPA</td>
	      <td class="Estilo5">CALIBRE</td>     
	      <td class="Estilo5">PESO M. </td>
	      <td class="Estilo5">COLORES</td>
	      <td class="Estilo5">ESTADO</td>
	    </tr>
	  </table>
  <br>
		<table >
			<?php do { ?>
				<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
					<td class="Estilo6"><strong><a href="ficha_tecnica_vista.php?n_ft= <?php echo $row_fichas_tecnicas['n_ft']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_fichas_tecnicas['cod_ft']; ?></a></strong></td>
					<?php $ref=$row_fichas_tecnicas['id_ref_ft']; 
					$query_referencias = "SELECT * FROM Tbl_referencia WHERE id_ref = '$ref'";
					$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
					$row_referencias = mysql_fetch_assoc($referencias);
					$totalRows_referencias = mysql_num_rows($referencias);		
					if($ref != '') 
					{ 
						$sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$ref'";
						$resultref= mysql_query($sqlref);
						$numref= mysql_num_rows($resultref);
						if($numref >='1')
						{ 
							$tipo_bolsa = mysql_result($resultref, 0, 'tipo_bolsa_ref');
							$material = mysql_result($resultref, 0, 'material_ref');
							$adhesivo = mysql_result($resultref, 0, 'adhesivo_ref');
							$ancho = mysql_result($resultref, 0, 'ancho_ref');
							$largo = mysql_result($resultref, 0, 'largo_ref');
							$solapa = mysql_result($resultref, 0, 'solapa_ref');
							$calibre = mysql_result($resultref, 0, 'calibre_ref');
							$peso_millar = mysql_result($resultref, 0, 'peso_millar_ref');
						}
					}?>
					<td class="Estilo4"><a href="ficha_tecnica_vista.php?n_ft= <?php echo $row_fichas_tecnicas['n_ft']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($tipo_bolsa != '') { echo $tipo_bolsa; } else{ echo "- -"; } ?></a></td>
					<td class="Estilo4"><a href="ficha_tecnica_vista.php?n_ft= <?php echo $row_fichas_tecnicas['n_ft']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($material != '') { echo $material; } else{ echo "- -"; } ?></a></td>
					<td class="Estilo4"><a href="ficha_tecnica_vista.php?n_ft= <?php echo $row_fichas_tecnicas['n_ft']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($adhesivo != '') { echo $adhesivo; } else{ echo "- -"; } ?></a></td>
					<?php if($ref != '') 
					{ 
						$sqlverif="SELECT * FROM verificacion WHERE id_ref_verif='$ref' AND estado_arte_verif='2'";
						$resultverif= mysql_query($sqlverif);
						$numverif= mysql_num_rows($resultverif);
						if($numverif >='1')
						{ 
							$arte = mysql_result($resultverif, 0, 'userfile');
							$fecha = mysql_result($resultverif, 0, 'fecha_aprob_arte_verif');		
						}
					}?>
					<td class="centrado6"><?php if($arte != '') { ?> <a href="javascript:verFoto('archivo/<?php echo $arte;?>','610','490')"><img src="images/arte.gif" alt="<?php echo $arte; ?>" title="ARTE" border="0" style="cursor:hand;"  /></a> <?php } else{ echo "- -"; } ?></td>
					<td class="centrado6"><?php if($fecha != '') { echo $fecha; } else{ echo "- -"; } ?></td>      
					<td class="centrado6"><a href="referencia_cliente.php?id_ref=<?php echo $ref; ?>&cod_ref=<?php echo $row_referencias['cod_ref']; ?>" target="_top"><?php if($cod_ref != '') {
						$cod_ref=$row_referencias['cod_ref'];   
						$sqlrefcliente="SELECT * FROM Tbl_cliente_referencia WHERE N_referencia='$cod_ref'";
						$resultrefcliente= mysql_query($sqlrefcliente);
						$numrefcliente= mysql_num_rows($resultrefcliente);
						if($numrefcliente >='1')
							{ ?><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0" style="cursor:hand;"><?php 
					}
					else { echo "- -"; }
				}?></a></td>
				<?php if($ref != '') 
				{ 
					$sqlegp="SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref='$ref' AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp";
					$resultegp= mysql_query($sqlegp);
					$numegp= mysql_num_rows($resultegp);
					if($numegp >='1')
					{ 
						$exterior = mysql_result($resultegp, 0, 'pigm_ext_egp');
						$interior = mysql_result($resultegp, 0, 'pigm_int_epg');
						$color1 = mysql_result($resultegp, 0, 'color1_egp');
						$color2 = mysql_result($resultegp, 0, 'color2_egp');
						$color3 = mysql_result($resultegp, 0, 'color3_egp');
						$color4 = mysql_result($resultegp, 0, 'color4_egp');
						$color5 = mysql_result($resultegp, 0, 'color5_egp');
						$color6 = mysql_result($resultegp, 0, 'color6_egp');
						$colores = 0;
						if($color1 != '') { $colores=$colores+1; }
						if($color2 != '') { $colores=$colores+1; }	
						if($color3 != '') { $colores=$colores+1; }	
						if($color4 != '') { $colores=$colores+1; }	
						if($color5 != '') { $colores=$colores+1; }	
						if($color6 != '') { $colores=$colores+1; }		
					}
				}?>
				<td class="Estilo6"><?php if($exterior != '') { echo $exterior; } else{ echo "- -"; } ?></td>
				<td class="Estilo6"><?php if($interior != '') { echo $interior; } else{ echo "- -"; } ?></td>
				<td class="derecha1"><?php if($ancho != '') { echo $ancho; } else{ echo "- -"; } ?></td>
				<td class="derecha1"><?php if($largo != '') { echo $largo; } else{ echo "- -"; } ?></td>
				<td class="derecha1"><?php if($solapa != '') { echo $solapa; } else{ echo "- -"; } ?></td>
				<td class="derecha1"><?php if($calibre != '') { echo $calibre; } else{ echo "- -"; } ?></td>
				<td class="derecha1"><?php if($peso_millar != '') { echo $peso_millar; } else{ echo "- -"; } ?></td>
				<td class="centrado6"><?php if($colores != '') { echo $colores; } else { echo "- -";} ?></td>
				<td class="Estilo6"><?php echo $row_fichas_tecnicas['estado_ft']; ?></td>
			</tr>
		<?php } while ($row_fichas_tecnicas = mysql_fetch_assoc($fichas_tecnicas)); ?>
	</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($fichas_tecnicas);
?>