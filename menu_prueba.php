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
    //include('Exploradores/explorador.php'); 
    // explorador();
	
?>
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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
//CONSULTA USUARIO
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_usuario_admon = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
//CONSULTA USUARIO ADMINISTRADOR
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_admon = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_admon);
$usuario_admon = mysql_query($query_usuario_admon, $conexion1) or die(mysql_error());
$row_usuario_admon = mysql_fetch_assoc($usuario_admon);
$totalRows_usuario_admon = mysql_num_rows($usuario_admon);
//CONSULTA MENU GENERAL
mysql_select_db($database_conexion1, $conexion1);
$query_ver_menu = "SELECT * FROM menu";
$ver_menu = mysql_query($query_ver_menu, $conexion1) or die(mysql_error());
$row_ver_menu = mysql_fetch_assoc($ver_menu);
$totalRows_ver_menu = mysql_num_rows($ver_menu);
//CONSULTA SUBMENU GENERAL
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu = "SELECT * FROM submenu";
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);
//CONSULTA SUBMENU COMERCIAL
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_c = "SELECT * FROM submenu WHERE id_menu_submenu = '1'";
$ver_sub_menu_c = mysql_query($query_ver_sub_menu_c, $conexion1) or die(mysql_error());
$row_ver_sub_menu_c = mysql_fetch_assoc($ver_sub_menu_c);
$totalRows_ver_sub_menu_c = mysql_num_rows($ver_sub_menu_c);
//CONSULTA SUBMENU DESARROLLO
mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario['tipo_usuario'];
$query_ver_sub_menu_d = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='2' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
$ver_sub_menu_d = mysql_query($query_ver_sub_menu_d, $conexion1) or die(mysql_error());
$row_ver_sub_menu_d = mysql_fetch_assoc($ver_sub_menu_d);
$totalRows_ver_sub_menu_d = mysql_num_rows($ver_sub_menu_d);
//CONSULTA SUBMENU COMPRAS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_e = "SELECT * FROM submenu WHERE id_menu_submenu = '3'";
$ver_sub_menu_e = mysql_query($query_ver_sub_menu_e, $conexion1) or die(mysql_error());
$row_ver_sub_menu_e = mysql_fetch_assoc($ver_sub_menu_e);
$totalRows_ver_sub_menu_e = mysql_num_rows($ver_sub_menu_e);
//CONSULTA SUBMENU COMERCIAL
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_f = "SELECT * FROM submenu WHERE id_menu_submenu = '4'";
$ver_sub_menu_f = mysql_query($query_ver_sub_menu_f, $conexion1) or die(mysql_error());
$row_ver_sub_menu_f = mysql_fetch_assoc($ver_sub_menu_f);
$totalRows_ver_sub_menu_f = mysql_num_rows($ver_sub_menu_f);
//CONSULTA SUBMENU PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_g = "SELECT * FROM submenu WHERE id_menu_submenu = '9' ORDER BY id_submenu ASC";
$ver_sub_menu_g = mysql_query($query_ver_sub_menu_g, $conexion1) or die(mysql_error());
$row_ver_sub_menu_g = mysql_fetch_assoc($ver_sub_menu_g);
$totalRows_ver_sub_menu_g = mysql_num_rows($ver_sub_menu_g);
//CONSULTA SUBMENU ADMINISTRADOR
mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario['tipo_usuario'];
$query_ver_sub_menu_h = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='10' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
$ver_sub_menu_h = mysql_query($query_ver_sub_menu_h, $conexion1) or die(mysql_error());
$row_ver_sub_menu_h = mysql_fetch_assoc($ver_sub_menu_h);
$totalRows_ver_sub_menu_h = mysql_num_rows($ver_sub_menu_h);
//FIN SUBMENU 
//INICIA SUBMENU DE SUBMENU CREAR UNA CONSULTA PARA CADA LINC SUB SUBMENU
//CONSULTA PERFIL CLIENTE
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_c = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '1'";
$ver_sub_submenu_c = mysql_query($query_ver_sub_submenu_c , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_c  = mysql_fetch_assoc($ver_sub_submenu_c );
$totalRows_ver_sub_submenu_c  = mysql_num_rows($ver_sub_submenu_c );
//CONSULTA COTIZACIONES
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_d = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '19'";
$ver_sub_submenu_d = mysql_query($query_ver_sub_submenu_d , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_d  = mysql_fetch_assoc($ver_sub_submenu_d );
$totalRows_ver_sub_submenu_d  = mysql_num_rows($ver_sub_submenu_d );
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css">
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
</head>
<body oncontextmenu="return false">
<table id="tablacentro"><tr><td id="tdcentro">
<div align="center"><table id="tabla1"><tr><td>
  <div id="cabecera1"><div class="menu2"><ul>
  <li><?php echo $row_usuario['nombre_usuario']; ?></li>
  <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
  </ul></div></div> 
  <div id="columna2"> 
  <div id="justificacion">  
  <div id="columna3"><div>
<!--INICIA MENU-->	
    <ul id="MenuBar1" class="MenuBarVertical">   
	  <?php $i=0; do { //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
       $ver_url=$row_ver_menu['ver_url']; if ($ver_url==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?>   
	  <li><?php 
	    $tipo=$row_usuario['tipo_usuario'];
		$id_menu=$row_ver_menu['id_menu'];
		$sql="select * from permisos where menu='$id_menu' and usuario='$tipo'";//MENU		
		$result=mysql_query($sql); $num=mysql_num_rows($result);		
		if ($num >= '1') { $menu=mysql_result($result,0,'menu'); }		
		if (($id_menu==$menu) && ($id_menu==1)){ $url=$row_ver_menu['url'];	//$id_menu==1 es la que trae los submenu correspondientes a ese menu 1
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
         
        <!--INICIA MENU COMERCIAL-->
        <ul ><?php $i=0; do { ?>
        <?php $ver_url_sub=$row_ver_sub_menu_c['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?>  
        <li><?php
		$tipo=$row_usuario['tipo_usuario'];
		$id_submenu_c=$row_ver_sub_menu_c['id_submenu'];
		$sql2="select * from permisos where submenu='$id_submenu_c' and usuario='$tipo'";
		$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
		if ($num2 >= '1') { $submenu_c=mysql_result($result2,0,'submenu'); }
		if ($id_submenu_c==$submenu_c) {	$url2=$row_ver_sub_menu_c['url'];
		echo "<a href=$url2>".$row_ver_sub_menu_c['nombre_submenu']."</a>";}
		?>
		<?php
		//INICIA EL SUB SUBMENU PERFIL CLIENTE
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==1 ){//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		  ?>
           <ul> <? $i=0; do { ?>
              <?php $ver_url_sub_sub=$row_ver_sub_submenu_c['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
               <li><?php
					$tipo=$row_usuario['tipo_usuario'];
					$id_submenuc=$row_ver_sub_submenu_c['id_submenu'];
					$sqlc="select * from permisos where submenu='$id_submenuc' and usuario='$tipo'";
					$resultc=mysql_query($sqlc); $numc=mysql_num_rows($resultc);
					if ($numc >= '1') { $submenuc=mysql_result($resultc,0,'submenu'); }
					if ($id_submenuc==$submenuc) {	$urlc=$row_ver_sub_submenu_c['url'];
					echo "<a href=$urlc>".$row_ver_sub_submenu_c['nombre_submenu']."</a>";}?>  
                <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
              </li><?php  } while ($row_ver_sub_submenu_c = mysql_fetch_assoc($ver_sub_submenu_c));?>
            </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU PERFIL CLIENTE?> 
                
		<?php
		//INICIA EL SUB SUBMENU COTIZACIONES
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==19){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		  ?>
           <ul> <? $i=0; do { ?>
              <?php $ver_url_sub_sub=$row_ver_sub_submenu_d['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
               <li><?php
					$tipo=$row_usuario['tipo_usuario'];
					$id_submenud=$row_ver_sub_submenu_d['id_submenu'];
					$sqld="select * from permisos where submenu='$id_submenud' and usuario='$tipo'";
					$resultd=mysql_query($sqld); $numd=mysql_num_rows($resultd);
					if ($numd >= '1') { $submenud=mysql_result($resultd,0,'submenu'); }
					if ($id_submenud==$submenud) {	$urld=$row_ver_sub_submenu_d['url'];
					echo "<a href=$urld>".$row_ver_sub_submenu_d['nombre_submenu']."</a>";}?>  
                <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
              </li><?php  } while ($row_ver_sub_submenu_d = mysql_fetch_assoc($ver_sub_submenu_d));?>
            </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU COTIZACIONES?>          
		<?php		
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_c['nombre_submenu']; } ?>
        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU?>
        </li><?php } while ($row_ver_sub_menu_c = mysql_fetch_assoc($ver_sub_menu_c)); ?>
        </ul>       
		<?php	
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
		 //TERMINA EL MENU COMERCIAL

		 
		//INICIA EL MENU DESRROLLO 
		else if ($id_menu==$menu && $id_menu==2 ){ $url3=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { ?>
        <?php $ver_url_sub=$row_ver_submenu['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo=$row_usuario['tipo_usuario'];
		$id_submenu_d=$row_ver_sub_menu['id_submenu'];
		$sql3="select * from permisos where submenu='$id_submenu_d' and usuario='$tipo' ORDER BY id_registro ASC ";
		$result3=mysql_query($sql3); $num3=mysql_num_rows($result3); 
		if ($num3 >= '1') { $submenu_d=mysql_result($result3,0,'submenu'); }
		if ($id_submenu_d==$submenu_d){ $url3=$row_ver_sub_menu_d['url'];
		echo "<a href=$url3>".$row_ver_sub_menu_d['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_d['nombre_submenu']; }	?>
        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
        </li><?php } while ($row_ver_sub_menu_d = mysql_fetch_assoc($ver_sub_menu_d)); ?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU DESARROLLO
		//FIN EL MENU DESRROLLO
		
		//INICIA EL MENU COMPRAS
		//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMPRAS
		else if ($id_menu==$menu && $id_menu==3 ){ $url4=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { ?>
        <?php $ver_url_sub=$row_ver_sub_menu_e['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo=$row_usuario['tipo_usuario'];
		$id_submenu_e=$row_ver_sub_menu_e['id_submenu'];
		$sql4="select * from permisos where submenu='$id_submenu_e' and usuario='$tipo'";
		$result4=mysql_query($sql4); $num4=mysql_num_rows($result4); 
		if ($num4 >= '1') { $submenu_e=mysql_result($result4,0,'submenu'); }
		if ($id_submenu_e==$submenu_e){ $url4=$row_ver_sub_menu_e['url'];
		echo "<a href=$url4>".$row_ver_sub_menu_e['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_e['nombre_submenu']; }	?>
        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
        </li><?php } while ($row_ver_sub_menu_e = mysql_fetch_assoc($ver_sub_menu_e)); ?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMPRAS
		//FIN EL MENU COMPRAS
				 
		//INICIA EL MENU PRODUCCION	
		else if ($id_menu==$menu && $id_menu==4 ){ $url5=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { ?>
        <?php $ver_url_sub=$row_ver_sub_menu_f['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo=$row_usuario['tipo_usuario'];
		$id_submenu_f=$row_ver_sub_menu_f['id_submenu'];
		$sql5="select * from permisos where submenu='$id_submenu_f' and usuario='$tipo'";
		$result5=mysql_query($sql5);$num5=mysql_num_rows($result5);
		if ($num5 >= '1'){ $submenu_f=mysql_result($result5,0,'submenu'); }
		if ($id_submenu_f==$submenu_f) { $url5=$row_ver_sub_menu_f['url'];
		echo "<a href=$url5>".$row_ver_sub_menu_f['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else{ echo $row_ver_sub_menu_f['nombre_submenu']; } ?>
		<?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
		</li><?php } while ($row_ver_sub_menu_f = mysql_fetch_assoc($ver_sub_menu_f));?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMPRAS
		//FIN EL MENU PRODUCCION		 
		 
        //INICIA EL MENU ADMINISTRADOR
		else if ($id_menu==$menu && $id_menu==9 ){ $url6=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { ?>
        <?php $ver_url_sub=$row_ver_sub_menu_g['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php        
		$tipo_g=$row_usuario_admon['tipo_usuario'];
		$id_submenu_g=$row_ver_sub_menu_g['id_submenu'];
		$sql6="select * from permisos where submenu='$id_submenu_g' and usuario='$tipo_g'";
		$result6=mysql_query($sql6);$num6=mysql_num_rows($result6);
		if ($num6 >= '1')
		{ $submenu_g=mysql_result($result6,0,'submenu'); }
		if ($id_submenu_g==$submenu_g) { $url6=$row_ver_sub_menu_g['url'];
		echo "<a href=$url6>".$row_ver_sub_menu_g['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_g['nombre_submenu']; } ?>
		<?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
		</li><?php } while ($row_ver_sub_menu_g = mysql_fetch_assoc($ver_sub_menu_g));?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU ADMINISTRADOR
		 //FINEL MENU ADMINISTRADOR	 
		
		//INICIA EL MENU ORDEN DE PEDIDO
		else if ($id_menu==$menu && $id_menu==10 ){ $url7=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { ?>
        <?php $ver_url_sub=$row_ver_submenu['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php        
		$tipo_h=$row_usuario['tipo_usuario'];
		$id_submenu_h=$row_ver_sub_menu_h['id_submenu'];
		$sql7="select * from permisos where submenu='$id_submenu_h' and usuario='$tipo_h' ORDER BY id_registro ASC ";
		$result7=mysql_query($sql7);$num7=mysql_num_rows($result7);
		if ($num7 >= '1')
		{ $submenu_h=mysql_result($result7,0,'submenu'); }
		if ($id_submenu_h==$submenu_h) { $url7=$row_ver_sub_menu_h['url'];
		echo "<a href=$url7>".$row_ver_sub_menu_h['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_h['nombre_submenu']; } ?>
		<?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
		</li><?php } while ($row_ver_sub_menu_h = mysql_fetch_assoc($ver_sub_menu_h));?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU PEDIDO
		 //FINEL MENU PEDIDO	
		  
		//else { echo $row_ver_menu['nombre_menu']; } ?>		      
      <?php } //AQUI TERMINA LOS DE VALOR 1 DE TODO EL MENU?>
	 </li>
	  <?php } while ($row_ver_menu = mysql_fetch_assoc($ver_menu)); //ESTE CODIGO CONTIENE EL DO DE TODO EL MENU PRINCIPAL?>  
   </ul>
<!--FINALIZA MENU-->	  
  </div></div>
<strong>MENU PRINCIPAL</strong><br><br>
  El sistema administrador de gestiones (SISADGE) de ALBERTO CADAVID R & CÍA S.A. es un desarrollo genérico que especifica el Sistema de Gestión de Calidad en nuestra organización. 
<br>El proposito fundamental de este desarrollo es seguir paso a paso la metodologia del sistema de Gestión de Calidad para la linea comercial, de diseño, producción y comercialización de bolsas de seguridad para el empaque y transporte de valores.<br><br>
<strong>POLITICA DE CALIDAD</strong><br><br>Se busca la completa satisfaccion de los clientes a través del mejoramiento continuo y con un grupo humano comprometido, verificando que durante todo el proceso se este cumpliendo con sus requisitos, necesidades y expectativas garantizando un producto y servicio de excelente calidad en el menor tiempo y a un precio justo, generando en ellos lealtad y confianza.
</div><!--<MARQUEE>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>ALBERTO CADAVID R. & CÍA S.A.</p>
</MARQUEE>--></div></td></tr></table></div></td></tr></table>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($ver_menu);
mysql_free_result($usuario_admon);
mysql_free_result($ver_submenu);
mysql_free_result($ver_sub_menu_c);
mysql_free_result($ver_sub_menu_d);
mysql_free_result($ver_sub_menu_e);
mysql_free_result($ver_sub_menu_f);
mysql_free_result($ver_sub_menu_g);
mysql_free_result($ver_sub_menu_h);
mysql_free_result($ver_sub_submenu_c);
mysql_free_result($ver_sub_submenu_d);
?>
