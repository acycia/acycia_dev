<?php require_once('Connections/conexion1.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_POST['usuario'])&&isset($_POST['email_usuario'])) {
  $loginUsername=$_POST['usuario'];
  $emailUsername=$_POST['email_usuario'];
  $MM_redirectLoginSuccess = "usuario_olvido_password.php?id_m=1";
  $MM_redirectLoginFailed = "usuario_olvido_password.php?id_m=2";
  mysql_select_db($database_conexion1, $conexion1);
  
  $LoginRS__query=sprintf("SELECT usuario, email_usuario, clave_usuario FROM usuario WHERE usuario='%s' AND email_usuario='%s'",
    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $emailUsername : addslashes($emailUsername)); 
   
  $LoginRS = mysql_query($LoginRS__query, $conexion1) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser !='') {
	$usuario=mysql_result($LoginRS,0,'usuario');
	$clave=mysql_result($LoginRS,0,'clave_usuario');
	$email=mysql_result($LoginRS,0,'email_usuario');
	  
//ENVIO DE E-MAIL AL USUARIO
				   $headers = "MIME-Version: 1.0\r\n"; 
				   $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
				   //dirección del remitente 
				   $headers .= "From: ACYCIA\r\n"; 
				   //dirección de respuesta, si queremos que sea distinta que la del remitente 
				   $headers .= "ACYCIA\r\n"; 			   
				   $to = $email;  // correo del usuario
				   $mensaje = "<p>Usted solicito informacion de la contraseña para ingresar al programa SISADGE:</p></b>";				   
                   $mensaje .= "<p>Usuario: $usuario </p><p>Clave: $clave </p><p>E-mail: $email</p>"; 
				   $mensaje .= "<p><span style=\"color: #FF0000\">No responder este correo.</span> </p></b>";
				  (mail("$to","Olvido su Contraseña",$mensaje,$headers));  
	      
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
	  
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/imageMenu.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="css/style_login.css" />	
</head>
<body oncontextmenu="return false">
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
           <div class="col-md-4">
                <div id="container">
                      <h1>Olvido Contrase&ntilde;a !</h1>
                        <form ACTION="<?php echo $loginFormAction; ?>" method="POST" name="form1">
                            <ul>
                                <li>
                                    <label for="pswd">E-mail:</label>
                                    <span>
                                    <input id="pswd" type="email" name="email_usuario"required />
                                    </span></li>
                                <li>
                                    <label for="pswd">Usuario:</label>
                                    <span><input id="pswd" type="text" name="usuario" required/></span>
                                </li>
                                <li>
                                  <button type="submit">Enviar</button>
                                </li>
                            </ul>	
                        </form>       			
                <?php if ($_GET['id_m']=='1'){
                  echo "<div id='verde'>La Informacion fue enviada a su correo personal !</b>  </div><meta http-equiv='refresh' content='2;URL=http://www.acycia.com/app/webroot/intranet/usuario.php'</b></div>";}?>
                <?php if ($_GET['id_m']=='2'){
                  echo "<div id='rojo3'>La Informacion no es correcta !</b>"; }?>
                    </div>
                    </div>       
                    <div class="row"> 
                    <div class="col-md-12">
                       <div id="pie">ALBERTO CADAVID R. & CIA. S.A.  Nit. 890.915.756-6    - Carrera 45 # 14 - 15 Sector Barrio Colombia   - Correo Postal: 21519<br>
                        PBX: 3112144     - FAX:  3524330 -  E-mail: info@acycia.com  - Medellin - Colombia
                       </div> 
                        </div>
                      </div>                    
          </div>

  </div>
</body>
</html>
