<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require(ROOT_BBDD);
?>
<?php //require_once('Connections/conexion1.php'); 
?>
<?php
//header('Location:manteni.php');
// *** Validate request to login to this site.

session_start();


$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['usuario'])) {
  $loginUsername = $_POST['usuario'];
  $password = $_POST['clave_usuario'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "menu.php";
  $MM_redirectLoginFailed = "denegado.php";
  $MM_redirecttoReferrer = false;

  $conexion = new ApptivaDB();

  $loginFoundUser = $conexion->buscarDos('usuario', 'usuario', $loginUsername, 'clave_usuario', $password);

  /*  mysql_select_db($database_conexion1, $conexion1);
  $LoginRS__query=sprintf("SELECT id_usuario, usuario, clave_usuario FROM usuario WHERE usuario='%s' AND clave_usuario='%s'",
    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $conexion1) ;
  $loginFoundUser = mysql_num_rows($LoginRS);*/

  if ($loginFoundUser) {
    $loginStrGroup = "";

    //declare two session variables and assign them
    $_SESSION['MM_IdUsername'] = $loginFoundUser['id_usuario'];
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['Usuario'] = $loginFoundUser['nombre_usuario'];
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
    $_SESSION['restriUsuarios'] = 1; // 1  es con permiso a todos los menus     

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }
    header("Location: " . $MM_redirectLoginSuccess);
  } else {
    header("Location: " . $MM_redirectLoginFailed);
  }
}
?>
<html>

<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
  <script type="text/javascript" src="js/formato.js"></script>
</head>

<body oncontextmenu="return false">
  <div class="container-fluid" id="divconten">
    <div class="row">
      <div class="col-md-8">
        <img class="baderslide" src="images/cabecera.jpg" style="width: 100%;margin:10px 0px 10px;">
      </div>
      <div class="col-md-4">
        <div class="menu2">
          <ul>
            <li><?php echo $_SESSION['MM_name']; ?></li>
            <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <!--LOGUIN-->
        <div class="navbar">
          <div class="navbar">
            <ul>
              <li><a href="menu.php">Menu Principal</a></li>
              <li><a href="comercial.php">Pagina Comercial</a></li>
            </ul>
          </div>
          <form ACTION="<?php echo $loginFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('usuario','','R','clave_usuario','','R');return document.MM_returnValue">
            <div id="fuente2">Usuario</div>
            <div id="fuente2"><input name="usuario" type="text" id="usuario" size="20"></div>
            <div id="fuente2">Clave de Acceso</div>
            <div id="fuente2"><input name="clave_usuario" type="password" id="clave_usuario" size="20"></div>
            <div id="fuente2"><input name="enviar" type="submit" id="enviar" value="Iniciar Sesion"><br><br>
              <br>
              <a href="usuario_cambio_password.php">Cambiar clave ?</a>
              <br><a href="usuario_olvido_password.php">Olvido Clave ?</a>
              </p>
            </div>
          </form>
        </div>

      </div>
      <div class="col-md-4">
        <strong>MENU PRINCIPAL</strong><br><br>
        El sistema administrador de gestiones (SISADGE) de ALBERTO CADAVID R & C&Iacute;A S.A. es un desarrollo gen&eacute;rico que especifica el Sistema de Gesti&oacute;n de Calidad en nuestra organizaci&oacute;n.
        <br>El proposito fundamental de este desarrollo es seguir paso a paso la metodologia del sistema de Gesti&oacute;n de Calidad para la linea comercial, de dise&ntilde;o, producci&oacute;n y comercializaci&oacute;n de bolsas de seguridad para el empaque y transporte de valores.<br><br>
      </div>
      <div class="col-md-4">
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
</body>

</html>