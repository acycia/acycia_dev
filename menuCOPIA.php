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
$query_ver_menu = "SELECT * FROM menu ORDER BY id_menu ASC";
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
$query_ver_sub_menu_d = "SELECT * FROM submenu WHERE id_menu_submenu = '2'";
$ver_sub_menu_d = mysql_query($query_ver_sub_menu_d, $conexion1) or die(mysql_error());
$row_ver_sub_menu_d = mysql_fetch_assoc($ver_sub_menu_d);
$totalRows_ver_sub_menu_d = mysql_num_rows($ver_sub_menu_d);
//CONSULTA SUBMENU COMPRAS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_e = "SELECT * FROM submenu WHERE id_menu_submenu = '3' ORDER BY id_submenu ASC";
$ver_sub_menu_e = mysql_query($query_ver_sub_menu_e, $conexion1) or die(mysql_error());
$row_ver_sub_menu_e = mysql_fetch_assoc($ver_sub_menu_e);
$totalRows_ver_sub_menu_e = mysql_num_rows($ver_sub_menu_e);
//CONSULTA SUBMENU PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_f = "SELECT * FROM submenu WHERE id_menu_submenu = '4'";
$ver_sub_menu_f = mysql_query($query_ver_sub_menu_f, $conexion1) or die(mysql_error());
$row_ver_sub_menu_f = mysql_fetch_assoc($ver_sub_menu_f);
$totalRows_ver_sub_menu_f = mysql_num_rows($ver_sub_menu_f);
//CONSULTA SUBMENU DESPACHOS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_i = "SELECT * FROM submenu WHERE id_menu_submenu = '6'";
$ver_sub_menu_i = mysql_query($query_ver_sub_menu_i, $conexion1) or die(mysql_error());
$row_ver_sub_menu_i = mysql_fetch_assoc($ver_sub_menu_i);
$totalRows_ver_sub_menu_i = mysql_num_rows($ver_sub_menu_i);
//CONSULTA SUBMENU ADMINISTRADOR
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_g = "SELECT * FROM submenu WHERE id_menu_submenu = '9'";
$ver_sub_menu_g = mysql_query($query_ver_sub_menu_g, $conexion1) or die(mysql_error());
$row_ver_sub_menu_g = mysql_fetch_assoc($ver_sub_menu_g);
$totalRows_ver_sub_menu_g = mysql_num_rows($ver_sub_menu_g);
//CONSULTA SUBMENU ORDEN DE PEDIDO
mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario['tipo_usuario'];
$query_ver_sub_menu_h = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='10' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
$ver_sub_menu_h = mysql_query($query_ver_sub_menu_h, $conexion1) or die(mysql_error());
$row_ver_sub_menu_h = mysql_fetch_assoc($ver_sub_menu_h);
$totalRows_ver_sub_menu_h = mysql_num_rows($ver_sub_menu_h);
//CONSULTA SUBMENU COSTOS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_menu_j = "SELECT * FROM submenu WHERE id_menu_submenu = '5' ORDER BY  id_submenu ASC";
$ver_sub_menu_j = mysql_query($query_ver_sub_menu_j, $conexion1) or die(mysql_error());
$row_ver_sub_menu_j = mysql_fetch_assoc($ver_sub_menu_j);
$totalRows_ver_sub_menu_j = mysql_num_rows($ver_sub_menu_j);
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
//CONSULTA REFERENCIAS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_e = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '9'";
$ver_sub_submenu_e = mysql_query($query_ver_sub_submenu_e , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_e  = mysql_fetch_assoc($ver_sub_submenu_e );
$totalRows_ver_sub_submenu_e  = mysql_num_rows($ver_sub_submenu_e );
//CONSULTA VENTAS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_f = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '5'";
$ver_sub_submenu_f = mysql_query($query_ver_sub_submenu_f , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_f  = mysql_fetch_assoc($ver_sub_submenu_f );
$totalRows_ver_sub_submenu_f  = mysql_num_rows($ver_sub_submenu_f );
//CONSULTA DESPACHOS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_g = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '6'";
$ver_sub_submenu_g = mysql_query($query_ver_sub_submenu_g , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_g  = mysql_fetch_assoc($ver_sub_submenu_g );
$totalRows_ver_sub_submenu_g  = mysql_num_rows($ver_sub_submenu_g );
//CONSULTA SUB MENU DISEÑO Y DESARROLLO
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_j = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '11'";
$ver_sub_submenu_j = mysql_query($query_ver_sub_submenu_j , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_j = mysql_fetch_assoc($ver_sub_submenu_j );
$totalRows_ver_sub_submenu_j = mysql_num_rows($ver_sub_submenu_j );
//CONSULTA SUB MENU GESTION DE PRODUCCION REGISTRO PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_k = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '42'";
$ver_sub_submenu_k = mysql_query($query_ver_sub_submenu_k , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_k = mysql_fetch_assoc($ver_sub_submenu_k );
$totalRows_ver_sub_submenu_k = mysql_num_rows($ver_sub_submenu_k );
//INICIA SUBMENU2 DE SUBMENU2 CREAR UNA CONSULTA PARA CADA LINC SUB SUBMENU
//REGISTRO DE PRODUCCION SELLADO LISTADO
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_n = "SELECT * FROM Tbl_submenu_submenu2 WHERE id_submenu = '26'";
$ver_sub_submenu_n = mysql_query($query_ver_sub_submenu_n , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_n  = mysql_fetch_assoc($ver_sub_submenu_n );
$totalRows_ver_sub_submenu_n  = mysql_num_rows($ver_sub_submenu_n );
//CONSULTA SUB MENU GESTION DE PRODUCCION REFERENCIAS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_m = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '41'";
$ver_sub_submenu_m = mysql_query($query_ver_sub_submenu_m , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_m = mysql_fetch_assoc($ver_sub_submenu_m );
$totalRows_ver_sub_submenu_m = mysql_num_rows($ver_sub_submenu_m );
//INICIA SUBMENU2 DE SUBMENU2 CREAR UNA CONSULTA PARA CADA LINC SUB SUBMENU
//CONSULTA COPIA COTIZACIONES
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_l = "SELECT * FROM Tbl_submenu_submenu2 WHERE id_submenu = '4'";
$ver_sub_submenu_l = mysql_query($query_ver_sub_submenu_l , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_l  = mysql_fetch_assoc($ver_sub_submenu_l );
$totalRows_ver_sub_submenu_l  = mysql_num_rows($ver_sub_submenu_l );
//CONSULTA SUB SUB MENU COSTOS
mysql_select_db($database_conexion1, $conexion1);
$query_ver_sub_submenu_o = "SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = '90'";
$ver_sub_submenu_o = mysql_query($query_ver_sub_submenu_o , $conexion1) or die(mysql_error());
$row_ver_sub_submenu_o  = mysql_fetch_assoc($ver_sub_submenu_o );
$totalRows_ver_sub_submenu_o  = mysql_num_rows($ver_sub_submenu_o );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script type="text/javascript">
//Mobile Detection and Redirecting
var device = navigator.userAgent

	if (device.match(/Iphone/i)|| device.match(/Ipod/i)|| device.match(/Android/i)|| device.match(/J2ME/i)|| device.match(/HTC/i)) {
	//window.location = "AQUI DIRECCION WEB MOVIL";
	window.location="http://www.acycia.com/app/webroot/intranet/menu_dispositivos.php";
	}
	else if (device.match(/Ipad/i))
	{
	//window.location = "IPAD/TABLET";
	window.location="http://www.acycia.com/app/webroot/intranet/menu_dispositivos.php";
	} else {
	//window.location = "pc";
	window.location="http://www.acycia.com/app/webroot/intranet/menu.php";
	}
	//
</script>
</head>
<body>
<div class="container-fluid" id="divconten"> 
        <div class="row">
         <div class="col-md-8" ><img src="images/cabecera.jpg" style="width: 100%;margin:10px 0px 10px;">
          </div>
          <div class="col-md-4"> 
            <div class="menu2"><ul>
              <li><?php echo $row_usuario['nombre_usuario']; ?></li>
              <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
              </ul>
            </div> 
          </div>
        </div>
         <div class="row">
           <div class="col-md-4">
       <!--INICIA MENU-->
       <ul id="MenuBar1" class="MenuBarVertical">   
	   <?php $i=0; do { //AQUI COMIENZA LOS DE VALOR 1 DE SUBMENU COMERCIAL
       $ver_url=$row_ver_menu['ver_url']; if ($ver_url==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?>   
	   <li><?php 
	    $tipo=$row_usuario['tipo_usuario'];//tipo de usuario en usuarios
		$id_menu=$row_ver_menu['id_menu'];//id_menu 
		$sql="select * from permisos where menu='$id_menu' and usuario='$tipo'";//MENU		
		$result=mysql_query($sql); $num=mysql_num_rows($result);		
		if ($num >= '1') { $menu=mysql_result($result,0,'menu'); }		
		if (($id_menu==$menu) && ($id_menu==1)){ $url=$row_ver_menu['url'];//$id_menu==1 es la que trae los submenu correspondientes ese menu1
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; 
		//else { echo $row_ver_sub_menu_c['nombre_submenu']; } ?>	

        <!--INICIA MENU COMERCIAL-->
        <?php if ($id_menu==$menu && $id_menu==1 ){//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
	    ?>
        <ul ><?php $i=0; do { 
         $ver_url_sub=$row_ver_sub_menu_c['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?>  
        <li><?php
		$tipo_c=$row_usuario['tipo_usuario'];
		$id_submenu_c=$row_ver_sub_menu_c['id_submenu'];
		$sql2="select * from permisos where submenu='$id_submenu_c' and usuario='$tipo_c'";
		$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
		if ($num2 >= '1') { $submenu_c=mysql_result($result2,0,'submenu'); }
		if ($id_submenu_c==$submenu_c) {	$url2=$row_ver_sub_menu_c['url'];
		echo "<a href=$url2>".$row_ver_sub_menu_c['nombre_submenu']."</a>";}
		//else { echo $row_ver_sub_menu_c['nombre_submenu']; } ?>	
		<?php
		//INICIA EL SUB SUBMENU PERFIL CLIENTE
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==1 ){//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
		  ?>
           <ul> <? $i=0; do { 
            $ver_url_sub_sub=$row_ver_sub_submenu_c['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
            <li><?php
			$tipo_c=$row_usuario['tipo_usuario'];
		    $id_submenuc=$row_ver_sub_submenu_c['id_submenu'];
			$sqlc="select * from permisos where submenu='$id_submenuc' and usuario='$tipo_c'";
		    $resultc=mysql_query($sqlc); $numc=mysql_num_rows($resultc);
			if ($numc >= '1') { $submenuc=mysql_result($resultc,0,'submenu'); }
			if ($id_submenuc==$submenuc) {	$urlc=$row_ver_sub_submenu_c['url'];
			echo "<a href=$urlc>".$row_ver_sub_submenu_c['nombre_submenu']."</a>";}
			//else { echo $row_ver_sub_submenu_c['nombre_submenu']; } ?>	 
            <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
           </li><?php  } while ($row_ver_sub_submenu_c = mysql_fetch_assoc($ver_sub_submenu_c));?>
          </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU PERFIL CLIENTE?> 
                
		<?php
		//INICIA EL SUB SUBMENU COTIZACIONES
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==19){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
		  ?>
           <ul> <? $i=0; do { 
              $ver_url_sub_sub=$row_ver_sub_submenu_d['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
               <li><?php
					$tipo_d=$row_usuario['tipo_usuario'];
					$id_submenud=$row_ver_sub_submenu_d['id_submenu'];//llevan este id cuando tienen hijos
					$sqld="select * from permisos where submenu='$id_submenud' and usuario='$tipo_d'";
					$resultd=mysql_query($sqld); $numd=mysql_num_rows($resultd);
					if ($numd >= '1') { $submenud=mysql_result($resultd,0,'submenu'); }
					if ($id_submenud==$submenud) {	$urld=$row_ver_sub_submenu_d['url'];
					echo "<a href=$urld>".$row_ver_sub_submenu_d['nombre_submenu']."</a>";}
					//else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>
                    
						   <?php              
                                //INICIA EL SUB SUBMENU2 COPIA COTIZACIONES
                                if ($id_submenud==$submenud && $id_submenud==4){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC 
                                  ?>
                                   <ul> <? $i=0; do { 
                                      $ver_url_sub_sub2=$row_ver_sub_submenu_d['ver_url']; if ($ver_url_sub_sub2==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
                                       <li><?php
                                            $tipo_d=$row_usuario['tipo_usuario'];
                                            $id_submenul=$row_ver_sub_submenu_l['id_sub_menu'];//llevan este id los hijos finales
                                            $sqll="select * from permisos where submenu='$id_submenul' and usuario='$tipo_d'";
                                            $resultl=mysql_query($sqll); $numl=mysql_num_rows($resultl);
                                            if ($numl >= '1') { $submenul=mysql_result($resultl,0,'submenu'); }
                                            if ($id_submenul==$submenul) {	$urll=$row_ver_sub_submenu_l['url'];
                                            echo "<a href=$urll>".$row_ver_sub_submenu_l['nombre_submenu']."</a>";}
                                            //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>	   
                                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
                                      </li><?php  } while ($row_ver_sub_submenu_l= mysql_fetch_assoc($ver_sub_submenu_l));?>
                                    </ul>
                                 <?php } //FIN DO Y FIN CODIGO SUB SUBMENU2 COPIA COTIZACIONES?>  	   
                <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
              </li><?php  } while ($row_ver_sub_submenu_d = mysql_fetch_assoc($ver_sub_submenu_d));?>
            </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU COTIZACIONES?>  
         
		<?php
		//INICIA EL SUB SUBMENU REFERENCIAS
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==9){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
		  ?>
           <ul> <? $i=0; do { 
              $ver_url_sub_sub=$row_ver_sub_submenu_e['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
               <li><?php
					$tipo_c=$row_usuario['tipo_usuario'];
					$id_submenue=$row_ver_sub_submenu_e['id_sub_menu'];
					$sqle="select * from permisos where submenu='$id_submenue' and usuario='$tipo_c'";
					$resulte=mysql_query($sqle); $nume=mysql_num_rows($resulte);
					if ($nume >= '1') { $submenue=mysql_result($resulte,0,'submenu'); }
					if ($id_submenue==$submenue) {	$urle=$row_ver_sub_submenu_e['url'];
					echo "<a href=$urle>".$row_ver_sub_submenu_e['nombre_submenu']."</a>";}
					//else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>  
                <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
              </li><?php  } while ($row_ver_sub_submenu_e = mysql_fetch_assoc($ver_sub_submenu_e));?>
            </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REFERENCIAS?> 
               
         
         <?php
		//INICIA EL SUB SUBMENU VENTAS
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==5){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
		  ?>
           <ul> <? $i=0; do { 
              $ver_url_sub_sub=$row_ver_sub_submenu_f['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
               <li><?php
					$tipo_c=$row_usuario['tipo_usuario'];
					$id_submenuf=$row_ver_sub_submenu_f['id_sub_menu'];
					$sqlf="select * from permisos where submenu='$id_submenuf' and usuario='$tipo_c'";
					$resultf=mysql_query($sqlf); $numf=mysql_num_rows($resultf);
					if ($numf >= '1') { $submenuf=mysql_result($resultf,0,'submenu'); }
					if ($id_submenuf==$submenuf) {	$urlf=$row_ver_sub_submenu_f['url'];
					echo "<a href=$urlf>".$row_ver_sub_submenu_f['nombre_submenu']."</a>";}
					//else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>  
                <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
              </li><?php  } while ($row_ver_sub_submenu_f = mysql_fetch_assoc($ver_sub_submenu_f));?>
            </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU VENTAS?>
            
                   
         <?php
		//INICIA EL SUB SUBMENU REF AC REF CL
	    if ($id_submenu_c==$submenu_c && $id_submenu_c==6){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
		  ?>
           <ul> <? $i=0; do { 
              $ver_url_sub_sub=$row_ver_sub_submenu_g['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
               <li><?php
					$tipo_c=$row_usuario['tipo_usuario'];
					$id_submenug=$row_ver_sub_submenu_g['id_sub_menu'];
					$sqlg="select * from permisos where submenu='$id_submenug' and usuario='$tipo_c'";
					$resultg=mysql_query($sqlg); $numg=mysql_num_rows($resultg);
					if ($numg >= '1') { $submenug=mysql_result($resultg,0,'submenu'); }
					if ($id_submenug==$submenug) {	$urlg=$row_ver_sub_submenu_g['url'];
					echo "<a href=$urlg>".$row_ver_sub_submenu_g['nombre_submenu']."</a>";}
					//else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>  
                <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
              </li><?php  } while ($row_ver_sub_submenu_g = mysql_fetch_assoc($ver_sub_submenu_g));?>
            </ul>
         <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REF AC REF CL?>        
         
                 
		<?php		
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_c['nombre_submenu']; } ?>
        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU?>
        </li><?php } while ($row_ver_sub_menu_c = mysql_fetch_assoc($ver_sub_menu_c)); ?>
        </ul>       
		<?php	
		 }
		}//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
		 //TERMINA EL MENU COMERCIAL

		 
		//INICIA EL MENU DESARROLLO 		
	    if ($id_menu==$menu && $id_menu==2 ){//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		 //else if ($id_menu==$menu && $id_menu==2 ){ 
	    $url3=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { 
         $ver_url_sub=$row_ver_submenu['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo_d=$row_usuario['tipo_usuario'];
		$id_submenu_d=$row_ver_sub_menu_d['id_submenu'];//ojo el d debe ser consecuente con el codigo de arriba
		$sql3="select * from permisos where submenu='$id_submenu_d' and usuario='$tipo_d' ORDER BY id_registro ASC ";
		$result3=mysql_query($sql3); $num3=mysql_num_rows($result3); 
		if ($num3 >= '1') { $submenu_d=mysql_result($result3,0,'submenu'); }
		if ($id_submenu_d==$submenu_d){ $url3=$row_ver_sub_menu_d['url'];
		echo "<a href=$url3>".$row_ver_sub_menu_d['nombre_submenu']."</a>"; }?>
	
                 					 <?php
                    //INICIA EL SUB SUBMENU DESARROLLO 
                    if ($id_submenu_d==$submenu_d && $id_submenu_d==11){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                      ?>
                       <ul> <? $i=0; do { 
                          $ver_url_sub_sub=$row_ver_sub_submenu_j['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
                           <li><?php
                                $tipo_d=$row_usuario['tipo_usuario'];
                                $id_submenuj=$row_ver_sub_submenu_j['id_sub_menu'];
                                $sqlj="select * from permisos where submenu='$id_submenuj' and usuario='$tipo_d'";
                                $resultj=mysql_query($sqlj); $numj=mysql_num_rows($resultj);
                                if ($numj >= '1') { $submenuj=mysql_result($resultj,0,'submenu'); }
                                if ($id_submenuj==$submenuj) {	$urlj=$row_ver_sub_submenu_j['url'];
                                echo "<a href=$urlj>".$row_ver_sub_submenu_j['nombre_submenu']."</a>";}
                                //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>  
                            <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
                          </li><?php  } while ($row_ver_sub_submenu_j = mysql_fetch_assoc($ver_sub_submenu_j));?>
                        </ul>
                     <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REF AC REF CL?>
         				

                     <?php 
                    //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                    //else { echo $row_ver_sub_menu_d['nombre_submenu']; }	?>
                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
                    </li><?php } while ($row_ver_sub_menu_d = mysql_fetch_assoc($ver_sub_menu_d)); ?>
                    </ul>        
                    <?php 
                     }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU DESARROLLO
                    //FIN EL MENU DESARROLLO
		
		//INICIA EL MENU COMPRAS
	    if ($id_menu==$menu && $id_menu==3 )//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		{ $url4=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { 
         $ver_url_sub=$row_ver_sub_menu_e['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo_c=$row_usuario['tipo_usuario'];
		$id_submenu_e=$row_ver_sub_menu_e['id_submenu'];
		$sql4="select * from permisos where submenu='$id_submenu_e' and usuario='$tipo_c'";
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


		//INICIA EL MENU COSTOS
	    if ($id_menu==$menu && $id_menu==5 )//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		{ $url9=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { 
         $ver_url_sub=$row_ver_sub_menu_j['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo_c=$row_usuario['tipo_usuario'];
		$id_submenu_j=$row_ver_sub_menu_j['id_submenu'];
		$sql9="select * from permisos where submenu='$id_submenu_j' and usuario='$tipo_c'";
		$result9=mysql_query($sql9); $num9=mysql_num_rows($result9); 
		if ($num9 >= '1') { $submenu_j=mysql_result($result9,0,'submenu'); }
		if ($id_submenu_j==$submenu_j){ $url9=$row_ver_sub_menu_j['url'];
		echo "<a href=$url9>".$row_ver_sub_menu_j['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else { echo $row_ver_sub_menu_i['nombre_submenu']; }	?>
                  	<?php
                    //INICIA EL SUB SUBMENU COSTOS
                    if ($id_submenu_j==$submenu_j && $id_submenu_j==90){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                      ?>
                       <ul> <? $i=0; do { 
                          $ver_url_sub_sub=$row_ver_sub_submenu_o['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
                           <li><?php
                                $tipo_o=$row_usuario['tipo_usuario'];
                                $id_submenuo=$row_ver_sub_submenu_o['id_sub_menu'];
                                $sqlo="select * from permisos where submenu='$id_submenuo' and usuario='$tipo_o'";
                                $resulto=mysql_query($sqlo); $numo=mysql_num_rows($resulto);
                                if ($numo >= '1') { $submenuo=mysql_result($resulto,0,'submenu'); }
                                if ($id_submenuo==$submenuo) {	$urlo=$row_ver_sub_submenu_o['url'];
                                echo "<a href=$urlo>".$row_ver_sub_submenu_o['nombre_submenu']."</a>";}
                                //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>  
                            <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
                          </li><?php  } while ($row_ver_sub_submenu_o = mysql_fetch_assoc($ver_sub_submenu_o));?>
                        </ul>
                     <?php } ?>       
        
        
        
        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COSTOS?>
        </li><?php } while ($row_ver_sub_menu_j = mysql_fetch_assoc($ver_sub_menu_j)); ?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COSTOS
		//FIN EL MENU COSTOS


				 
		//INICIA EL MENU PRODUCCION	
		if ($id_menu==$menu && $id_menu==4 )//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		{ $url5=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul><?php $i=0; do { 
         $ver_url_sub=$row_ver_sub_menu_f['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo_5=$row_usuario['tipo_usuario'];
		$id_submenu_f=$row_ver_sub_menu_f['id_submenu'];
		$sql5="select * from permisos where submenu='$id_submenu_f' and usuario='$tipo_5'";
		$result5=mysql_query($sql5);$num5=mysql_num_rows($result5);
		if ($num5 >= '1'){ $submenu_f=mysql_result($result5,0,'submenu'); }
		if ($id_submenu_f==$submenu_f) { $url5=$row_ver_sub_menu_f['url'];
		echo "<a href=$url5>".$row_ver_sub_menu_f['nombre_submenu']."</a>"; } ?>
        
		             <?php
                    //INICIA EL SUB SUBMENU REGISTRO PRODUCCION - REFERENCIAS
                    if ($id_submenu_f==$submenu_f && $id_submenu_f==41){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                      ?>
                       <ul> <? $i=0; do { 
                          $ver_url_sub_sub=$row_ver_sub_submenu_m['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
                           <li><?php
                                $tipo_5=$row_usuario['tipo_usuario'];// tipo 5 produccion
                                $id_submenum=$row_ver_sub_submenu_m['id_submenu'];// trae todos los id de la tabla pertenecientes a produccion que hay en permisos
                                $sqlm="select * from permisos where submenu='$id_submenum' and usuario='$tipo_5'";
                                $resultm=mysql_query($sqlm); $numm=mysql_num_rows($resultm);
                                if ($numm >= '1') { $submenum=mysql_result($resultm,0,'submenu'); }
                                if ($id_submenum==$submenum) {	$urlm=$row_ver_sub_submenu_m['url'];
                                echo "<a href=$urlm>".$row_ver_sub_submenu_m['nombre_submenu']."</a>";}
                                //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>  
                            <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
                          </li><?php  } while ($row_ver_sub_submenu_m = mysql_fetch_assoc($ver_sub_submenu_m));?>
                        </ul>
                     <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REGISTRO PRODUCCION  ?>        
                
		             <?php
                    //INICIA EL TBL_SUBMENU_SUBMENU REGISTRO PRODUCCION - REGISTRO PRODUCCION
                    if ($id_submenu_f==$submenu_f && $id_submenu_f==42){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                      ?>
                       <ul> <? $i=0; do { 
                          $ver_url_sub_sub=$row_ver_sub_submenu_k['ver_url']; if ($ver_url_sub_sub==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
                           <li><?php
                                $tipo_5=$row_usuario['tipo_usuario'];// tipo 5 produccion
                                $id_submenuk=$row_ver_sub_submenu_k['id_submenu'];// trae todos los id de la tabla pertenecientes a produccion que hay en permisos
                                $sqlk="select * from permisos where submenu='$id_submenuk' and usuario='$tipo_5'";
                                $resultk=mysql_query($sqlk); $numk=mysql_num_rows($resultk);
                                if ($numk >= '1') { $submenuk=mysql_result($resultk,0,'submenu'); }
                                if ($id_submenuk==$submenuk) {	$urlk=$row_ver_sub_submenu_k['url'];
                                echo "<a href=$urlk>".$row_ver_sub_submenu_k['nombre_submenu']."</a>";}
                                //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>

                               
									   <?php              
                                            //INICIA EL SUB SUBMENU2 SELLADO
                                            if ($id_submenuk==$submenuk && $id_submenuk==26){ //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC                                             	
											  ?>
                                               <ul> <? $i=0; do { 
                                                  $ver_url_sub_sub2=$row_ver_sub_submenu_n['ver_url'];
												   if ($ver_url_sub_sub2==1){ //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 ?>
                                                   <li><?php
                                                        $tipo_5=$row_usuario['tipo_usuario'];
                                                        $id_submenun=$row_ver_sub_submenu_n['id_sub_menu'];//5 y 6
                                                        $sqln="select * from permisos where submenu='$id_submenun' and usuario='$tipo_5'";
                                                        $resultn=mysql_query($sqln); $numn=mysql_num_rows($resultn);
                                                        if ($numn >= '1') { $submenun=mysql_result($resultn,0,'submenu'); }
                                                        if ($id_submenun==$submenun) {	
														$urln=$row_ver_sub_submenu_n['url'];
                                                        echo "<a href=$urln>".$row_ver_sub_submenu_n['nombre_submenu']."</a>";}
                                                        //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } ?>	   
                                                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
                                                  </li><?php  } while ($row_ver_sub_submenu_n= mysql_fetch_assoc($ver_sub_submenu_n));?>
                                                </ul>
                                             <?php } //FIN DO Y FIN CODIGO SUB SUBMENU2 COPIA COTIZACIONES?>                                  

                            <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU ?>                       
                          </li><?php  } while ($row_ver_sub_submenu_k = mysql_fetch_assoc($ver_sub_submenu_k));?>
                        </ul>
                     <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REGISTRO PRODUCCION
		
		
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else{ echo $row_ver_sub_menu_f['nombre_submenu']; } ?>
		<?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
		</li><?php } while ($row_ver_sub_menu_f = mysql_fetch_assoc($ver_sub_menu_f));?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU PROEDUCCION
		//FIN EL MENU PRODUCCION				 
		 
        //INICIA EL MENU ADMINISTRADOR
		if ($id_menu==$menu && $id_menu==9 )//IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
		{ $url6=$row_ver_menu['url'];	
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; ?>
        <ul ><?php $i=0; do { 
         $ver_url_sub=$row_ver_sub_menu_g['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php        
		$tipo_c=$row_usuario_admon['tipo_usuario'];
		$id_submenu_g=$row_ver_sub_menu_g['id_submenu'];
		$sql6="select * from permisos where submenu='$id_submenu_g' and usuario='$tipo_c'";
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
			

    	//INICIA EL MENU GETION DESPACHOS
		if ($id_menu==$menu && $id_menu==6 )//$menu es para ver si existe en permisos, $id_menu es si existe en menu
		{ $url8=$row_ver_menu['url'];//si esta 1 activo o 0 inactivo
		echo "<a href>".$row_ver_menu['nombre_menu']."</a>"; //me imprime el nombre del menu?>
        <ul ><?php $x=0; do { 
         $ver_url_sub=$row_ver_sub_menu_i['ver_url']; if ($ver_url_sub==1){ //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1?> 
        <li><?php
		$tipo_c=$row_usuario['tipo_usuario'];//el tipo de permiso al usuario en tabla usuarios
		$id_submenu_i=$row_ver_sub_menu_i['id_submenu'];// el id de tabla submenu 49
		$sql8="select * from permisos where submenu='$id_submenu_i' and usuario='$tipo_c'";
		$result8=mysql_query($sql8);
		$num8=mysql_num_rows($result8);
		if ($num8 >= '1')
		{ $submenu_i=mysql_result($result8,0,'submenu'); }//me trae el submenu de permisos osea 49
		if ($id_submenu_i==$submenu_i) { //49 es igual a 49
		$url8=$row_ver_sub_menu_i['url'];
		echo "<a href='$url8'>".$row_ver_sub_menu_i['nombre_submenu']."</a>"; }
		//ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
		//else{ echo $row_ver_sub_menu_f['nombre_submenu']; } ?>
		<?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL?>
		</li><?php } while ($row_ver_sub_menu_i = mysql_fetch_assoc($ver_sub_menu_i));?>
		</ul>        
		<?php 
		 }//AQUI TERMINA LOS DE VALOR 1 DE SUBMENU GESTION DESPACHOS	
		//FIN EL MENU GESTION DESPACHOS	

		  		  
		//else { echo $row_ver_menu['nombre_menu']; } ?>		      
      <?php } //AQUI TERMINA LOS DE VALOR 1 DE TODO EL MENU?>
        </li>
	  <?php } while ($row_ver_menu = mysql_fetch_assoc($ver_menu)); //ESTE CODIGO CONTIENE EL DO DE TODO EL MENU PRINCIPAL?>  
   </ul>
<!--NOTA IMPORATANTE REFERENTE AL MENU: cuando no aparece el en el menu es porque toca ingresarlo en permisos y si no aparece el submenu es porque se esta saltando un menu q debe estar oculto lo mejor es habilitar en anterior menu q esta en blanco para ese usuario-->
<!--FINALIZA MENU-->	  
           </div>          
            <div class="col-md-4">
                <strong>MENU PRINCIPAL</strong><br><br>
 				 El sistema administrador de gestiones (SISADGE) de ALBERTO CADAVID R & C&Iacute;A S.A. es un desarrollo gen&eacute;rico que especifica el Sistema de Gesti&oacute;n de Calidad en nuestra organizaci&oacute;n. 
				<br>El proposito fundamental de este desarrollo es seguir paso a paso la metodologia del sistema de Gesti&oacute;n de Calidad para la linea comercial, de dise&ntilde;o, producci&oacute;n y comercializaci&oacute;n de bolsas de seguridad para el empaque y transporte de valores.<br><br>
            </div>
            <div class="col-md-4">
				<strong>POLITICA DE CALIDAD</strong><br><br>Se busca la completa satisfaccion de los clientes a trav&eacute;s del mejoramiento continuo y con un grupo humano comprometido, verificando que durante todo el proceso se este cumpliendo con sus requisitos, necesidades y expectativas garantizando un producto y servicio de excelente calidad en el menor tiempo y a un precio justo, generando en ellos lealtad y confianza.
            </div>            
          </div>

  </div>            
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
