<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require(ROOT_BBDD);
?>
<?php  //require_once('Connections/conexion1.php'); 
?>
<?php
/*********************************IMPORTANTE******************************************/
//PARA PODER VISUALIZAR EL SUBMENU SE DEBE HACER LO SIGUIENTE:
//1-selecciono el menu que quiero agregarle un submenu (en tipos_usuario)
//2-agrego nombre y la url
//3-importante verificar logandome con uno de los usuarios que estan asociados al menu correspondiente
//4-agregarlo tambien al menu de Todos que es el tipo_usuario 1
//
//header('Location:manteni.php');
//initialize the session

//session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
  $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
  //to fully log out a visitor we need to clear the session varialbles
  //session_unregister('MM_Username');
  //session_unregister('MM_UserGroup');

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

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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
if (!function_exists("get_magic_quotes_gpc")) {
  function get_magic_quotes_gpc()
  {
    return 0;
  }
}
$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
    $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
  exit;
}
?>
<?php
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
//CONSULTA USUARIO
$conexion = new ApptivaDB();


$row_usuario = $conexion->buscar('usuario', 'usuario', $colname_usuario);


//mysql_select_db($database_conexion1, $conexion1);

//accesos
$permitidos = $row_usuario['id_usuario'];


$row_nombretipo = $conexion->llenarCampos('usuario,tipo_user', "WHERE usuario.tipo_usuario = tipo_user.id_tipo and usuario.id_usuario ='$permitidos'", "", "nombre_tipo,tipo_usuario");

$_SESSION['tipo_usuario'] = $row_nombretipo['tipo_usuario'];

if ($permitidos)
  acceso_usuarios($permitidos);

function acceso_usuarios($permitidos = '')
{
  $conexion = new ApptivaDB();
  if ($permitidos) {

    $resultdato = $conexion->llenarCampos('accesos', "WHERE usuario_id = '$permitidos'", '', 'acceso,superacceso,no_edita');

    $acceso = $resultdato['acceso'];
    $superacceso = $resultdato['superacceso'];
    $no_edita = $resultdato['no_edita'];


    $_SESSION['superacceso'] = 0;
    if ($acceso == 1 && $superacceso == 0) {
      $_SESSION['acceso'] = 1;
    } else {
      $_SESSION['acceso'] = 0;
    }
    if (($acceso == 1 || $acceso == 0) && $superacceso == 1) {
      $_SESSION['superacceso'] = 1;
      $_SESSION['acceso'] = 1;
    }


    //$_SESSION['no_edita']=1;
    if ($no_edita == 0) {
      $_SESSION['no_edita'] = 0;
      if (($acceso == 1 || $acceso == 0) && $superacceso == 1) {
        $_SESSION['no_edita'] = 1;
      }
      if (($acceso == 1 && $superacceso == 0)) {
        $_SESSION['no_edita'] = 1;
      }
      if (($acceso == 1 && $superacceso == 1)) {
        $_SESSION['no_edita'] = 1;
      }
      if ($acceso == 0 && $superacceso == 0) {
        $_SESSION['no_edita'] = 0; //1
      }
    } else 
   if ($no_edita == 1) {
      if (($acceso == 1 || $acceso == 0) && $superacceso == 1) {
        $_SESSION['no_edita'] = 1;
      }
      if (($acceso == 1 || $acceso == 0) && $superacceso == 0) {
        $_SESSION['no_edita'] = 1;
      }
      if (($acceso == 0 && $superacceso == 0)) {
        $_SESSION['no_edita'] = 1;
      }
      if (($acceso == 1 && $superacceso == 1)) {
        $_SESSION['no_edita'] = 1;
      }
    }
  }
}

//usuarios con algunas restrinciones 
$_SESSION['id_usuario'] = $row_usuario['id_usuario']; //para usarse en alguna pantalla
$restriUsuarios = array(23, 48); //aqui se agregan los id_usuario que no deben tener ciertos privilegios ubicados en la tabla usuario 
$_SESSION['restriUsuarios'] = 1;
for ($i = 1; $i < count($restriUsuarios); $i++) {
  if ($restriUsuarios[$i] == $row_usuario['id_usuario']) {
    $_SESSION['restriUsuarios'] = 0;
  }
}

// Nombre Usuarios  no mostrar algunas Div o tablas
$arrayopciones1 = array("Nombre Usuario Cualquiera"); //para usarse en alguna pantalla o div
$v1 = $_SESSION['Usuario']; //nombre del usuario

if (in_array($v1, $arrayopciones1)) {
  $_SESSION['NomUsNoMostrar'] = 1; ///ejecutas lo que quieres
} else {
  $_SESSION['NomUsNoMostrar'] = 0;
}


$colname_usuario_admon = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
//CONSULTA USUARIO ADMINISTRADOR

$row_usuario_admon = $conexion->buscar('usuario', 'usuario', $colname_usuario_admon);


//CONSULTA MENU GENERAL

$row_ver_menu1 = $conexion->llenaListas('menu', '', "ORDER BY id_menu ASC", '*');

//CONSULTA SUBMENU GENERAL

$row_ver_submenu = $conexion->buscarTres('submenu', 'ver_url', '', '');



//CONSULTA SUBMENU COMERCIAL

$row_ver_sub_menu_c = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '1'", "", '*');

//CONSULTA SUBMENU DESARROLLO

$row_ver_sub_menu_d = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '2'", "", '*');

//CONSULTA SUBMENU COMPRAS

$row_ver_sub_menu_e = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '3'", "", '*');

$row_ver_sub_submenu_jj = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu='104' ", "", '*');

//CONSULTA SUBMENU PRODUCCION

$row_ver_sub_menu_f = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '4'", "", '*');

//CONSULTA SUBMENU DESPACHOS

$row_ver_sub_menu_i = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '6'", "", '*');

//CONSULTA SUBMENU ADMINISTRADOR

$row_ver_sub_menu_g = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '9'", "", '*');

//CONSULTA SUBMENU RRHH

$row_ver_sub_menu_k = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '10'", "", '*');

//CONSULTA SUBMENU BACKUP

$row_ver_sub_menu_l = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '11'", "", '*');

//CONSULTA SUBMENU ARCHIVOS

$row_ver_sub_menu_ar = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '12'", "", '*');

//CONSULTA COMERCIO EXTERIOR

$row_ver_sub_menu_ext = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '13'", "", '*');

//CONSULTA SUBMENU COSTOS

$row_ver_sub_menu_j = $conexion->llenaListas('submenu', "WHERE id_menu_submenu = '5'", "", '*');

//FIN SUBMENU 
//INICIA SUBMENU DE SUBMENU CREAR UNA CONSULTA PARA CADA LINC SUB SUBMENU
//CONSULTA PERFIL CLIENTE

$row_ver_sub_submenu_c = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '1'", "", '*');

//CONSULTA COTIZACIONES

$row_ver_sub_submenu_d = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '19'", "", '*');

//CONSULTA REFERENCIAS

$row_ver_sub_submenu_e = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '9'", "", '*');

//CONSULTA VENTAS

$row_ver_sub_submenu_f = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '5'", "", '*');

//CONSULTA DESPACHOS

$row_ver_sub_submenu_g = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '6'", "", '*');

//CONSULTA SUB MENU DISE�O Y DESARROLLO

$row_ver_sub_submenu_j = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '11'", "", '*');

//CONSULTA SUB MENU GESTION DE PRODUCCION REGISTRO PRODUCCION

$row_ver_sub_submenu_k = $conexion->llenaListas('tbl_submenu_submenu', "WHERE id_sub_menu = '42'", "", '*');

//INICIA SUBMENU2 DE SUBMENU2 CREAR UNA CONSULTA PARA CADA LINC SUB SUBMENU
//REGISTRO DE PRODUCCION SELLADO LISTADO

$row_ver_sub_submenu_n = $conexion->llenaListas('Tbl_submenu_submenu2', "WHERE id_submenu = '26'", "", '*');

//CONSULTA SUB MENU GESTION DE PRODUCCION REFERENCIAS

$row_ver_sub_submenu_m = $conexion->llenaListas('Tbl_submenu_submenu', "WHERE id_sub_menu = '41'", "", '*');

//INICIA SUBMENU2 DE SUBMENU2 CREAR UNA CONSULTA PARA CADA LINC SUB SUBMENU
//CONSULTA COPIA COTIZACIONES

$row_ver_sub_submenu_l = $conexion->llenaListas('Tbl_submenu_submenu2', "WHERE id_submenu = '4'", "", '*');

//CONSULTA SUB SUB MENU COSTOS

$row_ver_sub_submenu_o = $conexion->llenaListas('Tbl_submenu_submenu', "WHERE id_sub_menu = '90'", "", '*');

?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
  <link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
  <link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />

  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript">
    //Mobile Detection and Redirecting
    var device = navigator.userAgent

    if (device.match(/Iphone/i) || device.match(/Ipod/i) || device.match(/Android/i) || device.match(/J2ME/i) || device.match(/BlackBerry/i) || device.match(/iPhone|iPad|iPod/i) || device.match(/Opera Mini/i) || device.match(/IEMobile/i) || device.match(/Mobile/i) || device.match(/Windows Phone/i) || device.match(/windows mobile/i) || device.match(/windows ce/i) || device.match(/webOS/i) || device.match(/palm/i) || device.match(/bada/i) || device.match(/series60/i) || device.match(/nokia/i) || device.match(/symbian/i) || device.match(/HTC/i)) {
      //window.location = "AQUI DIRECCION WEB MOVIL";
      window.location = "/acycia/menu_dispositivos.php";
    } else if (device.match(/Ipad/i)) {
      //window.location = "IPAD/TABLET";
      window.location = "/acycia/menu_dispositivos.php";
    }
    /*else {
  //window.location = "pc";
  window.location="servidor-01/acycia/menu.php";
}*/
    //


    /*(device.match(/Iphone/i)|| device.match(/Ipod/i)|| device.match(/Android/i)|| device.match(/J2ME/i)|| device.match(/HTC/i)*/
  </script>
</head>

<body>
  <div class="container-fluid" id="divconten">
    <div class="row">
      <div class="col-md-8">
        <img class="baderslide" src="images/cabecera.jpg" style="width: 100%;margin:10px 0px 10px;">
      </div>
      <div class="col-md-4">
        <div class="menu2">
          <ul>
            <li><?php echo $row_nombretipo['nombre_tipo']; ?></li>
            <li><?php echo $_SESSION['Usuario']; ?></li>
            <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <!--INICIA MENU-->

        <ul id="MenuBar1" class="MenuBarVertical">

          <?php
          //$i=0; 

          foreach ($row_ver_menu1 as $row_ver_menu) {

            //AQUI COMIENZA LOS DE VALOR 1 DE SUBMENU COMERCIAL

            $ver_url = $row_ver_menu['ver_url'];
            if ($ver_url == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
          ?>
              <li>
                <?php
                $tipo = $row_usuario['tipo_usuario']; //tipo de usuario en usuarios
                $id_menu = $row_ver_menu['id_menu']; //id_menu 
                $sql = "select * from permisos where menu='$id_menu' and usuario='$tipo'"; //MENU  
                $num = $conexion->llenarCampos('permisos', "where menu='$id_menu' and usuario='$tipo'", '', '*');
                if ($num >= '1') {
                  $menu = $num['menu'];
                }
                if (($id_menu == $menu) && ($id_menu == 1)) {
                  $url = $row_ver_menu['url']; //$id_menu==1 es la que trae los submenu correspondientes ese menu1
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>";
                  //else { echo $row_ver_sub_menu_c['nombre_submenu']; } 
                ?>





                  <!--INICIA MENU COMERCIAL-->
                  <?php if ($id_menu == $menu && $id_menu == 1) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                  ?>
                    <ul>
                      <?php $i = 0;
                      foreach ($row_ver_sub_menu_c as $row_ver_sub_menu_c) {
                        $ver_url_sub = $row_ver_sub_menu_c['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                          <li><?php
                              $tipo_c = $row_usuario['tipo_usuario'];
                              $id_submenu_c = $row_ver_sub_menu_c['id_submenu'];
                              $num2 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_c' and usuario='$tipo_c'", '', '*');
                              if ($num2 >= '1') {
                                $submenu_c = $num2['submenu'];
                              }
                              if ($id_submenu_c == $submenu_c) {
                                $url2 = $row_ver_sub_menu_c['url'];
                                echo "<a href=$url2>" . $row_ver_sub_menu_c['nombre_submenu'] . "</a>";
                              }
                              //else { echo $row_ver_sub_menu_c['nombre_submenu']; } 
                              ?>
                            <?php
                            //INICIA EL SUB SUBMENU PERFIL CLIENTE
                            if ($id_submenu_c == $submenu_c && $id_submenu_c == 1) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                            ?>
                              <ul>
                                <?php $i = 0;
                                foreach ($row_ver_sub_submenu_c as $row_ver_sub_submenu_c) {
                                  $ver_url_sub_sub = $row_ver_sub_submenu_c['ver_url'];
                                  if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                ?>
                                    <li><?php
                                        $tipo_c = $row_usuario['tipo_usuario'];
                                        $id_submenuc = $row_ver_sub_submenu_c['id_submenu'];
                                        $numc = $conexion->llenarCampos('permisos', "where submenu='$id_submenuc' and usuario='$tipo_c'", '', '*');
                                        if ($numc >= '1') {
                                          $submenuc = $numc['submenu'];
                                        }
                                        if ($id_submenuc == $submenuc) {
                                          $urlc22 = $row_ver_sub_submenu_c['url'];
                                          echo "<a href=$urlc22>" . $row_ver_sub_submenu_c['nombre_submenu'] . "</a>";
                                        }
                                        //else { echo $row_ver_sub_submenu_c['nombre_submenu']; } 
                                        ?>
                                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                    ?>
                                    </li><?php  } ?>
                              </ul>
                            <?php } ?> <!--FIN DO Y FIN CODIGO SUB SUBMENU PERFIL CLIENTE-->
                            <?php
                            //INICIA EL SUB SUBMENU COTIZACIONES
                            if ($id_submenu_c == $submenu_c && $id_submenu_c == 19) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                            ?>
                              <ul>
                                <?php $i = 0;
                                foreach ($row_ver_sub_submenu_d as $row_ver_sub_submenu_d) {
                                  $ver_url_sub_sub = $row_ver_sub_submenu_d['ver_url'];
                                  if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                ?>
                                    <li><?php
                                        $tipo_d = $row_usuario['tipo_usuario'];
                                        $id_submenud = $row_ver_sub_submenu_d['id_submenu']; //llevan este id cuando tienen hijos 

                                        $numd = $conexion->llenarCampos('permisos', "where submenu='$id_submenud' and usuario='$tipo_d'", '', '*');
                                        if ($numd >= '1') {
                                          $submenud = $numd['submenu'];
                                        }
                                        if ($id_submenud == $submenud) {
                                          $urld = $row_ver_sub_submenu_d['url'];
                                          echo "<a href=$urld>" . $row_ver_sub_submenu_d['nombre_submenu'] . "</a>";
                                        }
                                        //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                        ?>

                                      <?php
                                      //INICIA EL SUB SUBMENU2 COPIA COTIZACIONES
                                      if ($id_submenud == $submenud && $id_submenud == 4) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC 
                                      ?>
                                        <ul> <?php $i = 0;
                                              foreach ($row_ver_sub_submenu_l as $row_ver_sub_submenu_l) {
                                                $ver_url_sub_sub2 = $row_ver_sub_submenu_d['ver_url'];
                                                if ($ver_url_sub_sub2 == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                              ?>
                                              <li><?php
                                                  $tipo_d = $row_usuario['tipo_usuario'];
                                                  $id_submenul = $row_ver_sub_submenu_l['id_sub_menu']; //llevan este id los hijos finales 

                                                  $numl = $conexion->llenarCampos('permisos', "where submenu='$id_submenul' and usuario='$tipo_d'", '', '*');
                                                  if ($numl >= '1') {
                                                    $submenul = $numl['submenu'];
                                                  }
                                                  if ($id_submenul == $submenul) {
                                                    $urll = $row_ver_sub_submenu_l['url'];
                                                    echo "<a href=$urll>" . $row_ver_sub_submenu_l['nombre_submenu'] . "</a>";
                                                  }
                                                  //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                                  ?>
                                              <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                              ?>
                                              </li><?php  } ?>
                                        </ul>
                                      <?php } //FIN DO Y FIN CODIGO SUB SUBMENU2 COPIA COTIZACIONES
                                      ?>
                                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                    ?>
                                    </li><?php  } ?>
                              </ul>
                            <?php } //FIN DO Y FIN CODIGO SUB SUBMENU COTIZACIONES
                            ?>


                            <?php
                            //INICIA EL SUB SUBMENU DESARROLLO
                            if ($id_submenu_c == $submenu_c && $id_submenu_c == 9) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                            ?>
                              <ul> <?php $i = 0;
                                    foreach ($row_ver_sub_submenu_e as $row_ver_sub_submenu_e) {
                                      $ver_url_sub_sub = $row_ver_sub_submenu_e['ver_url'];
                                      if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                    ?>
                                    <li><?php
                                        $tipo_c = $row_usuario['tipo_usuario'];
                                        $id_submenue = $row_ver_sub_submenu_e['id_sub_menu'];
                                        $nume = $conexion->llenarCampos('permisos', "where submenu='$id_submenue' and usuario='$tipo_c'", '', '*');
                                        if ($nume >= '1') {
                                          $submenue = $nume['submenu'];
                                        }
                                        if ($id_submenue == $submenue) {
                                          $urle = $row_ver_sub_submenu_e['url'];
                                          echo "<a href=$urle>" . $row_ver_sub_submenu_e['nombre_submenu'] . "</a>";
                                        }
                                        //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                        ?>
                                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                    ?>
                                    </li><?php  } ?>
                              </ul>
                            <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REFERENCIAS
                            ?>


                            <?php
                            //INICIA EL SUB SUBMENU VENTAS
                            if ($id_submenu_c == $submenu_c && $id_submenu_c == 5) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                            ?>
                              <ul> <?php $i = 0;
                                    foreach ($row_ver_sub_submenu_f as $row_ver_sub_submenu_f) {
                                      $ver_url_sub_sub = $row_ver_sub_submenu_f['ver_url'];
                                      if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                    ?>
                                    <li><?php
                                        $tipo_c = $row_usuario['tipo_usuario'];
                                        $id_submenuf = $row_ver_sub_submenu_f['id_sub_menu'];
                                        $numf = $conexion->llenarCampos('permisos', "where submenu='$id_submenuf' and usuario='$tipo_c'", '', '*');
                                        if ($numf >= '1') {
                                          $submenuf = $numf['submenu'];
                                        }
                                        if ($id_submenuf == $submenuf) {
                                          $urlf = $row_ver_sub_submenu_f['url'];
                                          echo "<a href=$urlf>" . $row_ver_sub_submenu_f['nombre_submenu'] . "</a>";
                                        }
                                        //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                        ?>
                                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                    ?>
                                    </li><?php  } ?>
                              </ul>
                            <?php } //FIN DO Y FIN CODIGO SUB SUBMENU VENTAS
                            ?>


                            <?php
                            //INICIA EL SUB SUBMENU REF AC REF CL
                            if ($id_submenu_c == $submenu_c && $id_submenu_c == 6) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                            ?>
                              <ul> <?php $i = 0;
                                    foreach ($row_ver_sub_submenu_g as $row_ver_sub_submenu_g) {
                                      $ver_url_sub_sub = $row_ver_sub_submenu_g['ver_url'];
                                      if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                    ?>
                                    <li><?php
                                        $tipo_c = $row_usuario['tipo_usuario'];
                                        $id_submenug = $row_ver_sub_submenu_g['id_sub_menu'];
                                        $numg = $conexion->llenarCampos('permisos', "where submenu='$id_submenug' and usuario='$tipo_c'", '', '*');
                                        if ($numg >= '1') {
                                          $submenug = $numg['submenu'];
                                        }
                                        if ($id_submenug == $submenug) {
                                          $urlg = $row_ver_sub_submenu_g['url'];
                                          echo "<a href=$urlg>" . $row_ver_sub_submenu_g['nombre_submenu'] . "</a>";
                                        }
                                        //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                        ?>
                                    <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                    ?>
                                    </li><?php  } ?>
                              </ul>
                            <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REF AC REF CL
                            ?>

                            <?php
                            //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                            //else { echo $row_ver_sub_menu_c['nombre_submenu']; } 
                            ?>
                          <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU
                          ?>
                          </li><?php }  ?>
                    </ul>
                  <?php
                  }
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                //TERMINA EL MENU COMERCIAL






                //INICIA EL MENU DESARROLLO     
                if ($id_menu == $menu && $id_menu == 2) {
                  //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                  //else if ($id_menu==$menu && $id_menu==2 ){ 
                  $url3 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_d as $row_ver_sub_menu_d) {
                        $ver_url_sub = $row_ver_submenu['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_d = $row_usuario['tipo_usuario'];
                            $id_submenu_d = $row_ver_sub_menu_d['id_submenu']; //ojo el d debe ser consecuente con el codigo de arriba  
                            $num3 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_d' and usuario='$tipo_d' ", 'ORDER BY id_registro ASC ', '*');
                            if ($num3 >= '1') {
                              $submenu_d = $num3['submenu'];
                            }
                            if ($id_submenu_d == $submenu_d) {
                              $url3 = $row_ver_sub_menu_d['url'];
                              echo "<a href=$url3>" . $row_ver_sub_menu_d['nombre_submenu'] . "</a>";
                            } ?>

                          <?php
                          //INICIA EL SUB SUBMENU DESARROLLO 
                          if ($id_submenu_d == $submenu_d && $id_submenu_d == 11) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                          ?>
                            <ul> <?php $i = 0;
                                  foreach ($row_ver_sub_submenu_j as $row_ver_sub_submenu_j) {
                                    $ver_url_sub_sub = $row_ver_sub_submenu_j['ver_url'];
                                    if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                  ?>
                                  <li><?php
                                      $tipo_d = $row_usuario['tipo_usuario'];
                                      $id_submenuj = $row_ver_sub_submenu_j['id_sub_menu'];

                                      $numj = $conexion->llenarCampos('permisos', "where submenu='$id_submenuj' and usuario='$tipo_d' ", '', '*');
                                      if ($numj >= '1') {
                                        $submenuj = $numj['submenu'];
                                      }

                                      if ($id_submenuj == $submenuj) {
                                        $urlj = $row_ver_sub_submenu_j['url'];
                                        echo "<a href=$urlj>" . $row_ver_sub_submenu_j['nombre_submenu'] . "</a>";
                                      }
                                      //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                      ?>
                                  <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                  ?>
                                  </li><?php  } ?>
                            </ul>
                          <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REF AC REF CL
                          ?>


                          <?php
                          //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                          //else { echo $row_ver_sub_menu_d['nombre_submenu']; }  
                          ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php } ?>
                  </ul>
                <?php
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU DESARROLLO
                //FIN EL MENU DESARROLLO



                //INICIA EL MENU COMPRAS
                if ($id_menu == $menu && $id_menu == 3) {
                  //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                  $url4 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_e as $row_ver_sub_menu_e) {
                        $ver_url_sub = $row_ver_submenu['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_e = $row_usuario['tipo_usuario'];
                            $id_submenu_e = $row_ver_sub_menu_e['id_submenu'];
                            $num4 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_e' and usuario='$tipo_e'", '', '*');
                            if ($num4 >= '1') {
                              $submenu_e = $num4['submenu'];
                            }
                            if ($id_submenu_e == $submenu_e) {
                              $url4 = $row_ver_sub_menu_e['url'];
                              echo "<a href=$url4>" . $row_ver_sub_menu_e['nombre_submenu'] . "</a>";
                            } ?>
                          <?php
                          //INICIA EL SUB SUBMENU COMPRAS

                          if ($id_submenu_e == $submenu_e && $id_submenu_e == 104) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                          ?>
                            <ul> <?php $i = 0;
                                  foreach ($row_ver_sub_submenu_jj as $row_ver_sub_submenu_jj) {
                                    $ver_url_sub_subj = $row_ver_sub_submenu_jj['ver_url'];
                                    if ($ver_url_sub_subj == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                  ?>
                                  <li><?php
                                      $tipo_e = $row_usuario['tipo_usuario'];
                                      $id_submenujj = $row_ver_sub_submenu_jj['id_sub_menu'];

                                      $numjj = $conexion->llenarCampos('permisos', "where submenu='$id_submenujj' and usuario='$tipo_e' ", '', '*');
                                      if ($numjj >= '1') {
                                        $submenujj = $numjj['submenu'];
                                      }

                                      if ($id_submenujj == $submenujj) {
                                        $urljj = $row_ver_sub_submenu_jj['url'];
                                        echo "<a href=$urljj>" . $row_ver_sub_submenu_jj['nombre_submenu'] . "</a>";
                                      }
                                      //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                      ?>
                                  <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                  ?>
                                  </li><?php  } ?>
                            </ul>
                          <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REF AC REF CL
                          ?>


                          <?php
                          //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                          //else { echo $row_ver_sub_menu_d['nombre_submenu']; }  
                          ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php } ?>
                  </ul>
                <?php
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMPRAS
                //FIN EL MENU COMPRAS




                //INICIA EL MENU PRODUCCION 
                if ($id_menu == $menu && $id_menu == 4) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                {
                  $url5 = $row_ver_menu['url'];
                  echo "<a href='produccion.php' >" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_f as $row_ver_sub_menu_f) {
                        $ver_url_sub = $row_ver_sub_menu_f['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_5 = $row_usuario['tipo_usuario'];
                            $id_submenu_f = $row_ver_sub_menu_f['id_submenu'];

                            $num5 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_f' and usuario='$tipo_5'", '', '*');
                            if ($num5 >= '1') {
                              $submenu_f = $num5['submenu'];
                            }
                            if ($id_submenu_f == $submenu_f) {
                              $url5 = $row_ver_sub_menu_f['url'];
                              echo "<a href=$url5>" . $row_ver_sub_menu_f['nombre_submenu'] . "</a>";
                            } ?>

                          <?php
                          //INICIA EL SUB SUBMENU REGISTRO PRODUCCION - REFERENCIAS
                          if ($id_submenu_f == $submenu_f && $id_submenu_f == 41) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                          ?>
                            <ul> <?php $i = 0;
                                  foreach ($row_ver_sub_submenu_m as $row_ver_sub_submenu_m) {
                                    $ver_url_sub_sub = $row_ver_sub_submenu_m['ver_url'];
                                    if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                  ?>
                                  <li><?php
                                      $tipo_5 = $row_usuario['tipo_usuario']; // tipo 5 produccion
                                      $id_submenum = $row_ver_sub_submenu_m['id_submenu']; // trae todos los id de la tabla pertenecientes a produccion que hay en permisos 
                                      $numm = $conexion->llenarCampos('permisos', "where submenu='$id_submenum' and usuario='$tipo_5'", '', '*');
                                      if ($numm >= '1') {
                                        $submenum = $numm['submenu'];
                                      }

                                      if ($id_submenum == $submenum) {
                                        $urlm = $row_ver_sub_submenu_m['url'];
                                        echo "<a href=$urlm>" . $row_ver_sub_submenu_m['nombre_submenu'] . "</a>";
                                      }
                                      //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                      ?>
                                  <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                  ?>
                                  </li><?php  } ?>
                            </ul>
                          <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REGISTRO PRODUCCION  
                          ?>

                          <?php
                          //INICIA EL TBL_SUBMENU_SUBMENU REGISTRO PRODUCCION - REGISTRO PRODUCCION
                          if ($id_submenu_f == $submenu_f && $id_submenu_f == 42) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                          ?>
                            <ul> <?php $i = 0;
                                  foreach ($row_ver_sub_submenu_k as $row_ver_sub_submenu_k) {
                                    $ver_url_sub_sub = $row_ver_sub_submenu_k['ver_url'];
                                    if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                  ?>
                                  <li><?php
                                      $tipo_5 = $row_usuario['tipo_usuario']; // tipo 5 produccion
                                      $id_submenuk = $row_ver_sub_submenu_k['id_submenu']; // trae todos los id de la tabla pertenecientes a produccion que hay en permisos  
                                      $numk = $conexion->llenarCampos('permisos', "where submenu='$id_submenuk' and usuario='$tipo_5'", '', '*');
                                      if ($numk >= '1') {
                                        $submenuk = $numk['submenu'];
                                      }
                                      if ($id_submenuk == $submenuk) {
                                        $urlk = $row_ver_sub_submenu_k['url'];
                                        echo "<a href=$urlk>" . $row_ver_sub_submenu_k['nombre_submenu'] . "</a>";
                                      }
                                      //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                      ?>


                                    <?php
                                      //INICIA EL SUB SUBMENU2 SELLADO
                                      if ($id_submenuk == $submenuk && $id_submenuk == 26) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC                                              
                                    ?>
                                      <ul> <?php $i = 0;
                                            foreach ($row_ver_sub_submenu_n as $row_ver_sub_submenu_n) {
                                              $ver_url_sub_sub2 = $row_ver_sub_submenu_n['ver_url'];
                                              if ($ver_url_sub_sub2 == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                            ?>
                                            <li><?php
                                                $tipo_5 = $row_usuario['tipo_usuario'];
                                                $id_submenun = $row_ver_sub_submenu_n['id_sub_menu']; //5 y 6 

                                                $numn = $conexion->llenarCampos('permisos', "where submenu='$id_submenun' and usuario='$tipo_5'", '', '*');
                                                if ($numn >= '1') {
                                                  $submenun = $numn['submenu'];
                                                }
                                                if ($id_submenun == $submenun) {
                                                  $urln = $row_ver_sub_submenu_n['url'];
                                                  echo "<a href=$urln>" . $row_ver_sub_submenu_n['nombre_submenu'] . "</a>";
                                                }
                                                //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                                ?>
                                            <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                            ?>
                                            </li><?php  } ?>
                                      </ul>
                                    <?php } //FIN DO Y FIN CODIGO SUB SUBMENU2 COPIA COTIZACIONES
                                    ?>

                                  <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                  ?>
                                  </li><?php  } ?>
                            </ul>
                          <?php } //FIN DO Y FIN CODIGO SUB SUBMENU REGISTRO PRODUCCION


                          //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                          //else{ echo $row_ver_sub_menu_f['nombre_submenu']; } 
                          ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php } ?>
                  </ul>
                <?php
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU PROEDUCCION
                //FIN EL MENU PRODUCCION    


                //INICIA EL MENU COSTOS
                if ($id_menu == $menu && $id_menu == 5) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                {
                  $url9 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_j as $row_ver_sub_menu_j) {
                        $ver_url_sub = $row_ver_sub_menu_j['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_c = $row_usuario['tipo_usuario'];
                            $id_submenu_j = $row_ver_sub_menu_j['id_submenu'];

                            $num9 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_j' and usuario='$tipo_c'", '', '*');
                            if ($num9 >= '1') {
                              $submenu_j = $num9['submenu'];
                            }
                            if ($id_submenu_j == $submenu_j) {
                              $url9 = $row_ver_sub_menu_j['url'];
                              echo "<a href=$url9>" . $row_ver_sub_menu_j['nombre_submenu'] . "</a>";
                            }
                            //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                            //else { echo $row_ver_sub_menu_i['nombre_submenu']; }  
                            ?>
                          <?php
                          //INICIA EL SUB SUBMENU COSTOS
                          if ($id_submenu_j == $submenu_j && $id_submenu_j == 90) { //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE. NO MODIFICAR EL  _C EN SUBMENU
                          ?>
                            <ul> <?php $i = 0;
                                  foreach ($row_ver_sub_submenu_o as $row_ver_sub_submenu_o) {
                                    $ver_url_sub_sub = $row_ver_sub_submenu_o['ver_url'];
                                    if ($ver_url_sub_sub == 1) { //DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1 
                                  ?>
                                  <li><?php
                                      $tipo_o = $row_usuario['tipo_usuario'];
                                      $id_submenuo = $row_ver_sub_submenu_o['id_sub_menu'];

                                      $numo = $conexion->llenarCampos('permisos', "where submenu='$id_submenuo' and usuario='$tipo_o'", '', '*');
                                      if ($numo >= '1') {
                                        $submenuo = $numo['submenu'];
                                      }
                                      if ($id_submenuo == $submenuo) {
                                        $urlo = $row_ver_sub_submenu_o['url'];
                                        echo "<a href=$urlo>" . $row_ver_sub_submenu_o['nombre_submenu'] . "</a>";
                                      }
                                      //else { echo $row_ver_sub_submenu_d['nombre_submenu']; } 
                                      ?>
                                  <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUB SUBMENU 
                                  ?>
                                  </li><?php  } ?>
                            </ul>
                          <?php } ?>


                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COSTOS
                        ?>
                        </li><?php }  ?>
                  </ul>
                  <?php
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COSTOS
                //FIN EL MENU COSTOS


                if ($_SESSION['acceso']) :
                  //INICIA EL MENU ADMINISTRADOR
                  if ($id_menu == $menu && $id_menu == 9) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                  {
                    $url6 = $row_ver_menu['url'];
                    echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                    <ul><?php $i = 0;
                        foreach ($row_ver_sub_menu_g as $row_ver_sub_menu_g) {
                          $ver_url_sub = $row_ver_sub_menu_g['ver_url'];
                          if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                        ?>
                          <li><?php
                              $tipo_c = $row_usuario_admon['tipo_usuario'];
                              $id_submenu_g = $row_ver_sub_menu_g['id_submenu'];


                              $num6 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_g' and usuario='$tipo_c'", '', '*');
                              if ($num6 >= '1') {
                                $submenu_g = $num6['submenu'];
                              }

                              if ($id_submenu_g == $submenu_g) {
                                $url6 = $row_ver_sub_menu_g['url'];
                                echo "<a href=$url6>" . $row_ver_sub_menu_g['nombre_submenu'] . "</a>";
                              }
                              //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                              //else { echo $row_ver_sub_menu_g['nombre_submenu']; } 
                              ?>
                          <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                          ?>
                          </li><?php } ?>
                    </ul>
                  <?php
                  } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU ADMINISTRADOR
                //FINEL MENU ADMINISTRADOR  
                endif;


                //INICIA EL MENU GETION DESPACHOS
                if ($id_menu == $menu && $id_menu == 6) //$menu es para ver si existe en permisos, $id_menu es si existe en menu
                {
                  $url8 = $row_ver_menu['url']; //si esta 1 activo o 0 inactivo
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; //me imprime el nombre del menu
                  ?>
                  <ul><?php $x = 0;
                      foreach ($row_ver_sub_menu_i as $row_ver_sub_menu_i) {
                        $ver_url_sub = $row_ver_sub_menu_i['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_c = $row_usuario['tipo_usuario']; //el tipo de permiso al usuario en tabla usuarios
                            $id_submenu_i = $row_ver_sub_menu_i['id_submenu']; // el id de tabla submenu 49


                            $num8 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_i' and usuario='$tipo_c'", '', '*');
                            if ($num8 >= '1') {
                              $submenu_i = $num8['submenu'];
                            } //me trae el submenu de permisos osea 49
                            if ($id_submenu_i == $submenu_i) { //49 es igual a 49
                              $url8 = $row_ver_sub_menu_i['url'];
                              echo "<a href='$url8'>" . $row_ver_sub_menu_i['nombre_submenu'] . "</a>";
                            }
                            //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                            //else{ echo $row_ver_sub_menu_f['nombre_submenu']; } 
                            ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php } ?>
                  </ul>
                <?php
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU GESTION DESPACHOS  
                //FIN EL MENU GESTION DESPACHOS 

                //INICIA EL MENU RRHH
                if ($id_menu == $menu && $id_menu == 10) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                {
                  $url9 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_k as $row_ver_sub_menu_k) {
                        $ver_url_sub = $row_ver_sub_menu_k['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_c = $row_usuario['tipo_usuario'];
                            $id_submenu_k = $row_ver_sub_menu_k['id_submenu'];

                            $num9 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_k' and usuario='$tipo_c'", '', '*');
                            if ($num9 >= '1') {
                              $submenu_k = $num9['submenu'];
                            }
                            if ($id_submenu_k == $submenu_k) {
                              $url9 = $row_ver_sub_menu_k['url'];
                              echo "<a href=$url9>" . $row_ver_sub_menu_k['nombre_submenu'] . "</a>";
                            } //FIN rrhh
                            //ESTE CODIGO ELSE HACE APARECER LOS NO HABILITADOS PARA EL TIPO DE USUARIO
                            //else { echo $row_ver_sub_menu_k['nombre_submenu']; }  
                            ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php } ?>
                  </ul>
                <?php
                } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU RRHH
                //FIN EL MENU RRHH  

                //INICIA EL MENU BACKUP
                if ($id_menu == $menu && $id_menu == 11) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                {
                  $url10 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_l as $row_ver_sub_menu_l) {
                        $ver_url_sub = $row_ver_sub_menu_l['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_c = $row_usuario['tipo_usuario'];
                            $id_submenu_l = $row_ver_sub_menu_l['id_submenu'];

                            $num10 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_l' and usuario='$tipo_c'", '', '*');
                            if ($num10 >= '1') {
                              $submenu_l = $num10['submenu'];
                            }
                            if ($id_submenu_l == $submenu_l) {
                              $url10 = $row_ver_sub_menu_l['url'];
                              echo "<a href=$url10>" . $row_ver_sub_menu_l['nombre_submenu'] . "</a>";
                            }
                            ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php }   ?>
                  </ul>
                <?php
                }

                //INICIA EL MENU ARCHIVOS
                if ($id_menu == $menu && $id_menu == 12) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                {
                  $url11 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul><?php $i = 0;
                      foreach ($row_ver_sub_menu_ar as $row_ver_sub_menu_ar) {
                        $ver_url_sub = $row_ver_sub_menu_ar['ver_url'];
                        if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                      ?>
                        <li><?php
                            $tipo_c = $row_usuario['tipo_usuario'];
                            $id_submenu_m = $row_ver_sub_menu_ar['id_submenu'];

                            $num11 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_m' and usuario='$tipo_c'", '', '*');
                            if ($num11 >= '1') {
                              $submenu_m = $num11['submenu'];
                            }
                            if ($id_submenu_m == $submenu_m) {
                              $url11 = $row_ver_sub_menu_ar['url'];
                              echo "<a href=$url11>" . $row_ver_sub_menu_ar['nombre_submenu'] . "</a>";
                            }
                            ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php } ?>
                  </ul>
                <?php
                }

                //FIN BACKUP


                //INICIA EL COMERCIO EXTERIOR

                if ($id_menu == $menu && $id_menu == 13) //IMPORTANTE ESTE NUMERO DEFINE LA IMPRESION DE LOS SUB SUBMENU EN EL LINC CORRESPONDIENTE
                {
                  $url12 = $row_ver_menu['url'];
                  echo "<a href>" . $row_ver_menu['nombre_menu'] . "</a>"; ?>
                  <ul>
                    <?php $i = 0;
                    foreach ($row_ver_sub_menu_ext as $row_ver_sub_menu_ext) {
                      $ver_url_sub = $row_ver_sub_menu_ext['ver_url'];
                      if ($ver_url_sub == 1) { //IMPORTANTE, DEFINE LA IMPRESION DE LOS QUE TIENEN VALOR 1
                    ?>
                        <li><?php
                            $tipo_c = $row_usuario['tipo_usuario'];
                            $id_submenu_l = $row_ver_sub_menu_ext['id_submenu'];

                            $num12 = $conexion->llenarCampos('permisos', "where submenu='$id_submenu_l' and usuario='$tipo_c'", '', '*');
                            if ($num12 >= '1') {
                              $submenu_l = $num12['submenu'];
                            }
                            if ($id_submenu_l == $submenu_l) {
                              $url12 = $row_ver_sub_menu_ext['url'];
                              echo "<a href=$url12>" . $row_ver_sub_menu_ext['nombre_submenu'] . "</a>";
                            }
                            ?>
                        <?php } //AQUI TERMINA LOS DE VALOR 1 DE SUBMENU COMERCIAL
                        ?>
                        </li><?php }   ?>
                  </ul>
                <?php
                }
                //FIN COMERCIO EXTERIOR

                //else { echo $row_ver_menu['nombre_menu']; } 
                ?>
              <?php } //AQUI TERMINA LOS DE VALOR 1 DE TODO EL MENU
              ?>
              </li>
            <?php }  //ESTE CODIGO CONTIENE EL DO DE TODO EL MENU PRINCIPAL
            ?>
            <li>
              <!-- <a href="adjuntardropbox.php" class="MenuBarItemSubmenu">Adjuntar Archivos</a> -->
            </li>

            <!--NOTA IMPORATANTE REFERENTE AL MENU: cuando no aparece el en el menu es porque toca ingresarlo en permisos y si no aparece el submenu es porque se esta saltando un menu q debe estar oculto lo mejor es habilitar en anterior menu q esta en blanco para ese usuario-->
            <!--FINALIZA MENU-->
            <!-- /*********************************IMPORTANTE******************************************/
//PARA PODER VISUALIZAR EL SUBMENU SE DEBE HACER LO SIGUIENTE:
//1-selecciono el menu que quiero agregarle un submenu (en tipos_usuario)
//2-agrego nombre y la url
//3-importante verificar logandome con uno de los usuarios que estan asociados al menu correspondiente
//4-agregarlo tambien al menu de Todos que es el tipo_usuario 1
//  -->
      </div>
      <div class="col-md-4">
        <strong>MENU PRINCIPAL</strong><br><br>
        El sistema administrador de gestiones (SISADGE) de ALBERTO CADAVID R & C&Iacute;A S.A. es un desarrollo gen&eacute;rico que especifica el Sistema de Gesti&oacute;n de Calidad en nuestra organizaci&oacute;n.
        <br>El proposito fundamental de este desarrollo es seguir paso a paso la metodologia del sistema de Gesti&oacute;n de Calidad para la linea comercial, de dise&ntilde;o, producci&oacute;n y comercializaci&oacute;n de bolsas de seguridad para el empaque y transporte de valores.<br><br>
      </div>
      <div class="col-md-4">
        <!-- <strong>POLITICA DE CALIDAD</strong><br><br>Se busca la completa satisfaccion de los clientes a trav&eacute;s del mejoramiento continuo y con un grupo humano comprometido, verificando que durante todo el proceso se este cumpliendo con sus requisitos, necesidades y expectativas garantizando un producto y servicio de excelente calidad en el menor tiempo y a un precio justo, generando en ellos lealtad y confianza. -->
        <strong>PROPOSITO ORGANIZACIONAL</strong><br><br>En Alberto Cadavid R.& CIA estamos comprometidos con la generación y suministro de soluciones seguras y confiables de empaques para el transporte de documentos, valores u otros productos que mantenga la satisfacción, confianza y fidelización con el cliente y partes interesadas.
        <br> Gestionamos eficientemente nuestros procesos con una infraestructura adecuada y el desarrollo de las competencias de nuestros colaboradores, garantizando la calidad de nuestros productos, el cumplimiento a los requisitos aplicables y el mejoramiento continuo de nuestros Sistema de Gestión.
        <button id="accordion" class="accordion">Continuar Leyendo....</button>
        <div class="panel">
          <br> Reafirmamos el compromiso con la protección y promoción de la salud de los trabajadores, en beneficio de su integridad física, mediante la gestión de los controles de los riesgos existentes, el cuidado, la intervención de las condiciones de trabajo que puedan causar accidentes y enfermedades laborales. Logrando mecanismos efectivos que proporcionen un control del ausentismo, la preparación ante emergencias y una cultura preventiva.
          <br> Fomentamos el cumplimiento de normas y procedimientos de seguridad en beneficio de la realización de un trabajo seguro y productivo, en los empleados, contratistas y personal temporal, quienes serán responsables de notificar oportunamente todas aquellas condiciones que puedan generar consecuencias y contingencias en la empresa.
          <br> A partir del cumplimiento de nuestro propósito, nuestra empresa mantendrá el reconocimiento y posicionamiento a nivel nacional e internacional, con un liderazgo y un crecimiento que garantice el desarrollo sostenible de nuestra empresa.
        </div>

      </div>
    </div>
  </div>
  <script type="text/javascript">
    var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {
      imgRight: "SpryAssets/SpryMenuBarRightHover.gif"
    });
  </script>
</body>

</html>


<script>
  let ac = document.getElementById('accordion')

  ac.addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
</script>


<style>
  .accordion {
    margin-top: 10px;
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 5px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
    border-radius: 10px;
  }

  .active,
  .accordion:hover {
    background-color: #ccc;
  }

  .accordion:after {
    content: '\002B';
    color: #777;
    font-weight: bold;
    float: right;
    margin-left: 5px;
  }

  .active:after {
    content: "\2212";
  }

  .panel {
    padding: 0 5px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
  }
</style>