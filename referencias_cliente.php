<?php require_once('Connections/conexion1.php'); ?>
<?php
session_start();
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
$colname_usuario_usuarios = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_usuarios = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_usuarios = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_usuarios);
$usuario_usuarios = mysql_query($query_usuario_usuarios, $conexion1) or die(mysql_error());
$row_usuario_usuarios = mysql_fetch_assoc($usuario_usuarios);
$totalRows_usuario_usuarios = mysql_num_rows($usuario_usuarios);

mysql_select_db($database_conexion1, $conexion1);
$query_ver = "SELECT * FROM referencia ORDER BY nit_c_ref ASC";
$ver = mysql_query($query_ver, $conexion1) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo33 {font-size: 14px; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; }
.Estilo34 {
	color: #000066;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Estilo41 {color: #000066}
.Estilo47 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo48 {font-size: 18px}
.Estilo52 {font-size: 12px; font-family: "Courier New", Courier, mono; font-weight: bold; color: #000066; }
.Estilo67 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
.Estilo68 {font-size: 12px}
.Estilo71 {color: #000000; font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo83 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo84 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000066;
}
.Estilo88 {font-family: Arial, Helvetica, sans-serif}
.Estilo89 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; }
.Estilo90 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo91 {color: #000066; font-weight: bold;}
.Estilo77 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo95 {color: #990000; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
<script LANGUAGE="JavaScript">
<!--
function detener(){
return true
}
window.onerror=detener

function verFoto(img, ancho, alto){
  derecha=(screen.width-ancho)/2;
  arriba=(550-alto)/2;
  string="toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width="+ancho+",height="+alto+",left="+derecha+",top="+arriba+"";
  fin=window.open(img,"",string);
}

function popUp(URL, ancho, alto) {
day = new Date();
id = day.getTime();
derecha=(screen.width-ancho)/2;
arriba=(screen.height-alto)/2;
ventana="toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width="+ancho+",height="+alto+",left="+derecha+",top="+arriba+"";
eval("page" + id + " = window.open(URL, '" + id + "', '" + ventana + "');");
}
// -->
</script>
</head>
<body>
<table width="624" height="10" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" colspan="2" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  
  <tr bgcolor="#999999">
    <td width="488" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><span class="Estilo83"><?php echo $row_usuario_usuarios['nombre_usuario']; ?></span></td>
    <td width="237" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo84">Cerrar Sesi&oacute;n</a></div></td>
  </tr>
  <tr bgcolor="#999999">
    <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo41 Estilo47 Estilo48"><strong>LISTADO DE REFERENCIAS </strong></div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#F9F9F9">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo77">
      <div align="left"><a href="menu.php" class="Estilo41"><img src="home.gif" alt="Menu Principal" width="22" height="23"></a></div>
    </div></td>
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo77"><a href="disenoydesarrollo.php" class="Estilo41 Estilo47">Dise&ntilde;o y Desarrollo</a></div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#F9F9F9">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form name="form1" id="form1" method="get" action="referencias1.php">
      <span class="Estilo84">Referencia
      <input type="radio" name="campo" value="cod_ref" />
      Egp N&ordm;
      <input type="radio" name="campo" value="n_egp" />
Cliente</span>
      <input type="radio" name="campo" value="nombre_c" />
<input name="criterio" type="text" class="Estilo68" id="criterio" size="30" />
<input name="Submit" type="submit" class="Estilo67" value="Filtrar" />      
    </form></td>
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo77">
      <div align="right"><a href="referencia.php" class="Estilo95">*Adicionar Referencia</a></div>
    </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="51" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
      <table width="730" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr bgcolor="#CCCCCC">
          <td width="100" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo68 Estilo52 Estilo88"><a href="referencias.php" class="Estilo41">Referencia</a></div></td>
          <td width="80" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo33 Estilo34"> <strong><a href="referencias_egp.php" class="Estilo41">N&ordm; Egp</a></strong></div></td>
          <td width="300" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo89">Cliente</div></td>
          <td width="150" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo89"> Arte Aprobado </div></td>
          <td width="50" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo89">Ver</div></td>
          <td width="50" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo89">Elim</div></td>
          <td width="50" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo89">Rev</div></td>
          <td width="50" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo89">Verif</div></td>
          <td width="50" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo90"><span class="Estilo91">Val</span></div></td>
          <td width="50" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo90"><span class="Estilo91">Ficha</span></div></td>
        </tr>
		<?php 
				$i=0;
				?>
        <?php do { ?>
        <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } 
	  $i++;		  ?>>
            <td width="100"><div align="center" class="Estilo71">
              <div align="left"><strong><?php echo $row_ver['cod_ref']; ?></strong></div>
            </div></td>
            <td width="80"><div align="center"><span class="Estilo71">
              <?php 
			
			$id2=$row_ver['n_egp_ref']; 
			if ($id2=='0')
			{
			echo "0";
			}
			else
			{
			echo $id2;
			}
			?>
            </span></div></td>
            <td width="300">
			<div align="center" class="Estilo71">
              <div align="left">
                <?php
$id=$row_ver['n_egp_ref'];
if($id<>'0')
{
$sql="select * from egp where n_egp='$id'";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num >='1')
{
$nit_c_egp=mysql_result($result,0,'nit_c_egp');
$sql1="select * from cliente where nit_c='$nit_c_egp'";
$result1=mysql_query($sql1);
$num1=mysql_num_rows($result1);
if ($num1 >='1')
{
$cliente=mysql_result($result1,0,'nombre_c');
echo $cliente;
}
}
}
if ($id=='0')
{
echo $row_ver['nit_c_ref'];
}?>
                  </div>
			</div></td>
            <td width="150"><div align="center" class="Estilo71"><small><font color="#808080">[ </font>
                    <?php $archivo= $row_ver['nombre_arte_ref'];?>
                    <a href="javascript:verFoto('archivo/<?php echo $archivo;?>','610','490')"> <?php echo $archivo;?></a><font color="#808080"> ]</font></small></div></td>
            <td width="50"><div align="center" class="Estilo71"><a href="referencia_editar.php?cod_ref=<?php echo $row_ver['cod_ref']; ?>&n_egp_ref=<?php echo $row_ver['n_egp_ref']; ?>"><img src="hoja.gif" width="18" height="18" border="0" alt="Referencia"></a></div></td>
            <td width="50"><div align="center" class="Estilo71"><a href="borrado_referencia.php?cod_ref=<?php echo $row_ver['cod_ref']; ?>"><img src="eliminar.gif" width="18" height="18" border="0" alt="Eliminar Referencia"></a></div></td>
            <td width="50"><div align="center" class="Estilo71"><a href="revision_detalle.php?cod_ref=<?php echo $row_ver['cod_ref']; ?>"><img src="animated_loading.gif" width="16" height="16" border="0" alt="Revisión"></a></div></td>
            <td width="50"><div align="center" class="Estilo71"><a href="verificacion_detalle.php?cod_ref=<?php echo $row_ver['cod_ref']; ?>"><img src="animated_loading.gif" width="16" height="16" border="0" alt="Verificación"></a></div></td>
            <td width="50"><div align="center" class="Estilo71"><a href="validacion_detalle.php?cod_ref=<?php echo $row_ver['cod_ref']; ?>"><img src="animated_loading.gif" width="16" height="16" border="0" alt="Validación"></a></div></td>
            <td width="50"><div align="center" class="Estilo71"><img src="animated_loading.gif" width="16" height="16" alt="Ficha Tecnica"></div></td>
        </tr>
        <?php } while ($row_ver = mysql_fetch_assoc($ver)); ?>
      </table>
    </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="21" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right">
      <div align="right"><img src="firma3.bmp"></div>
    </div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_usuarios);

mysql_free_result($ver);
?>
