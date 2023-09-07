<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
$colname_usuario_encuesta = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_encuesta = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_encuesta = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_encuesta);
$usuario_encuesta = mysql_query($query_usuario_encuesta, $conexion1) or die(mysql_error());
$row_usuario_encuesta = mysql_fetch_assoc($usuario_encuesta);
$totalRows_usuario_encuesta = mysql_num_rows($usuario_encuesta);

$colname_datos_cliente = "1";
if (isset($_GET['nit_c'])) {
  $colname_datos_cliente = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_datos_cliente = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_datos_cliente);
$datos_cliente = mysql_query($query_datos_cliente, $conexion1) or die(mysql_error());
$row_datos_cliente = mysql_fetch_assoc($datos_cliente);
$totalRows_datos_cliente = mysql_num_rows($datos_cliente);

$colname_ver_quejas = "1";
if (isset($_GET['nit_c'])) {
  $colname_ver_quejas = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_quejas = sprintf("SELECT * FROM analisis_qr WHERE nit_c_qr = '%s'", $colname_ver_quejas);
$ver_quejas = mysql_query($query_ver_quejas, $conexion1) or die(mysql_error());
$row_ver_quejas = mysql_fetch_assoc($ver_quejas);
$totalRows_ver_quejas = mysql_num_rows($ver_quejas);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo12 {font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"}
.Estilo14 {color: #000066}
.Estilo15 {
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-weight: bold;
	font-size: 12px;
}
.Estilo31 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo38 {color: #000099; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo49 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 18px;
	color: #000066;
}
.Estilo50 {font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; color: #000066; }
.Estilo59 {color: #000000; font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo62 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000066;
}
.Estilo63 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo68 {
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-size: 12px;
}
.Estilo69 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo87 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo89 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo90 {color: #FF0000}
-->
</style>
</head>

<body>
<table width="735" height="374" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="images/cabecera.jpg" alt="" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="450" class="Estilo62"><div align="left"><?php echo $row_usuario_encuesta['nombre_usuario']; ?></div></td>
          <td width="433"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo63">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  
  <tr bgcolor="#FFFFFF">
    <td height="5" bordercolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td colspan="4" bgcolor="#ECF5FF"><div align="center" class="Estilo33 Estilo36 Estilo49 Estilo31">LISTADO DE QUEJAS Y RECLAMOS POR CLIENTE </div></td>
        </tr>
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td width="122" bgcolor="#FFFFFF"><div align="right" class="Estilo15">Raz&oacute;n Social </div></td>
          <td width="224" bgcolor="#FFFFFF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['nombre_c']; ?></div></td>
          <td width="82" bgcolor="#FFFFFF"><div align="right" class="Estilo15">Tipo Cliente </div></td>
          <td width="270" bgcolor="#FFFFFF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['tipo_c']; ?></div></td>
        </tr>
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td height="17" bgcolor="#ECF5FF"><div align="right" class="Estilo15">Nit</div></td>
          <td bgcolor="#ECF5FF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['nit_c']; ?></div></td>
          <td width="82" bgcolor="#ECF5FF"><div align="right" class="Estilo15">Telefono</div></td>
          <td bgcolor="#ECF5FF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['telefono_c']; ?></div></td>
        </tr>
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td height="17" bgcolor="#FFFFFF"><div align="right" class="Estilo15">Direccion</div></td>
          <td bgcolor="#FFFFFF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['direccion_c']; ?></div></td>
          <td bgcolor="#FFFFFF"><div align="right" class="Estilo15">Fax</div></td>
          <td bgcolor="#FFFFFF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['fax_c']; ?></div></td>
        </tr>
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Contacto Comercial </div></td>
          <td bgcolor="#ECF5FF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['contacto_c']; ?></div></td>
          <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Pais</div></td>
          <td bgcolor="#ECF5FF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['cod_pais_c']; ?></div></td>
        </tr>
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td bgcolor="#FFFFFF"><div align="right" class="Estilo15">Celular</div></td>
          <td bgcolor="#FFFFFF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['celular_contacto_c']; ?></div></td>
          <td bgcolor="#FFFFFF"><div align="right" class="Estilo15">Provincia</div></td>
          <td bgcolor="#FFFFFF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['cod_dpto_c']; ?></div></td>
        </tr>
        <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Email</div></td>
          <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['email_comercial_c']; ?></div></td>
          <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Ciudad</div></td>
          <td bgcolor="#ECF5FF"><div align="left" class="Estilo59"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="21" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="139"><div align="center" class="Estilo89"><a href="comercial.php" class="Estilo14">Gesti&oacute;n Comercial</a></div></td>
        <td width="196"><div align="right" class="Estilo89">
          <div align="center"><a href="listado_clientes.php" class="Estilo14">Listado Clientes</a></div>
        </div></td>
        <td width="131"><div align="center" class="Estilo89"><a href="bus_queja.php" class="Estilo14">Busqueda</a></div></td>
        <td width="127"><div align="center" class="Estilo89"><a href="quejas.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo90">*Nueva Queja </a></div></td>
        <td width="114"><div align="right"><span class="Estilo87"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="41" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#CCCCCC">
        <tr bgcolor="#CCCCCC" class="Estilo38">
          <td width="153" bordercolor="#CCCCCC" bgcolor="#ECF5FF"><div align="center" class="Estilo69"><span class="Estilo14"> QUEJA O RECLAMO N&ordm;</span></div></td>
          <td width="194" bordercolor="#CCCCCC" bgcolor="#ECF5FF"><div align="center" class="Estilo69"><span class="Estilo14">FECHA</span></div></td>
          <td width="165" bordercolor="#CCCCCC" bgcolor="#ECF5FF"><div align="center" class="Estilo68">FORMA</div></td>
          <td width="81" bordercolor="#CCCCCC" bgcolor="#ECF5FF" class="Estilo35 Estilo39 Estilo14 Estilo12"><div align="center" class="Estilo15">EDITAR</div></td>
          <td width="70" bordercolor="#CCCCCC" bgcolor="#ECF5FF" class="Estilo35 Estilo39 Estilo14 Estilo12"><div align="center" class="Estilo15">IMPRIMIR</div></td>
          <td width="82" bordercolor="#CCCCCC" bgcolor="#ECF5FF" class="Estilo35 Estilo39 Estilo14 Estilo12"><div align="center" class="Estilo15">ELIMINAR</div></td>
        </tr>
        <?php 
	  $i=0;
	  ?>
      <?php do { ?>
        <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } 
	  $i++;		  ?>>
          <td class="Estilo50"><div align="center" class="Estilo59"><?php echo $row_ver_quejas['n_qr']; ?></div></td>
          <td class="Estilo50"><div align="center" class="Estilo59"><?php echo $row_ver_quejas['fecha_reclamo_qr']; ?></div></td>
          <td class="Estilo50"><div align="center" class="Estilo59"><?php echo $row_ver_quejas['forma_qr']; ?></div></td>
          <td class="Estilo50"><div align="center" class="Estilo69">
              <div align="center"><a href="ver_queja.php?n_qr=<?php echo $row_ver_quejas['n_qr']; ?>&nit_c=<?php echo $row_datos_cliente['nit_c']; ?>"><img src="hoja.gif" width="18" height="18" border="0"></a></div>
          </div></td>
          <td class="Estilo50"><div align="center" class="Estilo69"><a href="imprimir_queja.php?n_qr=<?php echo $row_ver_quejas['n_qr']; ?>&nit_c=<?php echo $row_ver_quejas['nit_c_qr']; ?>" target="new"><img src="impresor.gif" width="18" height="18" border="0"></a></div></td>
          <td><div align="center" class="Estilo69"><a href="borrado_queja.php?n_qr=<?php echo $row_ver_quejas['n_qr']; ?>&nit_c=<?php echo $row_ver_quejas['nit_c_qr']; ?>"><img src="eliminar.gif" width="18" height="18" border="0"></a></div></td>
        </tr>
        <?php } while ($row_ver_quejas = mysql_fetch_assoc($ver_quejas)); ?>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_encuesta);

mysql_free_result($datos_cliente);

mysql_free_result($ver_quejas);
?>
