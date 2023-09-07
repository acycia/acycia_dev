<?php require_once('Connections/conexion1.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}
$email=$_GET['email'];
if (isset($_POST['clave_antigua'])&&isset($_POST['clave_nueva1'])&&isset($_POST['clave_nueva2'])) {
  $emailUsername=$email;
  $password=$_POST['clave_antigua'];
  $MM_redirectLoginReteat = "usuario_cambio_password2.php?id_m=3&email=$emailUsername";
  $MM_redirectLoginSuccess = "usuario_cambio_password2.php?id_m=2";
  $MM_redirectLoginFailed = "usuario_cambio_password2.php?id_m=1&email=$emailUsername";
  mysql_select_db($database_conexion1, $conexion1);
  
  $LoginRS__query=sprintf("SELECT clave_usuario, email_usuario FROM usuario WHERE clave_usuario='%s' AND email_usuario='%s'",
    get_magic_quotes_gpc() ? $password : addslashes($password), get_magic_quotes_gpc() ? $emailUsername : addslashes($emailUsername)); 
   
  $LoginRS = mysql_query($LoginRS__query, $conexion1) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser !='') { 
     //UPDATE A LA CLAVE DE CONTRASEÑA
	 $clave=$_POST['clave_nueva1'];
     $updateSQL = "UPDATE usuario SET clave_usuario='$clave' WHERE  email_usuario='$emailUsername'";
	 
     mysql_select_db($database_conexion1, $conexion1);
     $Result = mysql_query($updateSQL, $conexion1) or die(mysql_error());
 
     header("Location: " . $MM_redirectLoginSuccess );
     } else {
	 header("Location: ". $MM_redirectLoginFailed );
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
<link rel="stylesheet" type="text/css" media="all" href="css/style_login.css" />	
<script src="js/Jquery_login.js"></script>
<script>
$(document).ready(function() {

	$('input[type=password]').keyup(function() {
		// set password variable
		var pswd = $(this).val();
		//validate the length
		if ( pswd.length < 8 ) {
			$('#length').removeClass('valid').addClass('invalid');
		} else {
			$('#length').removeClass('invalid').addClass('valid');
		}

		//validate letter
		if ( pswd.match(/[A-z]/) ) {
			$('#letter').removeClass('invalid').addClass('valid');
		} else {
			$('#letter').removeClass('valid').addClass('invalid');
		}

		//validate capital letter
		if ( pswd.match(/[A-Z]/) ) {
			$('#capital').removeClass('invalid').addClass('valid');
		} else {
			$('#capital').removeClass('valid').addClass('invalid');
		}

		//validate number
		if ( pswd.match(/\d/) ) {
			$('#number').removeClass('invalid').addClass('valid');
		} else {
			$('#number').removeClass('valid').addClass('invalid');
		}

	}).focus(function() {
		$('#pswd_info').show();
	}).blur(function() {
		$('#pswd_info').hide();
});

});
</script>
<script type="text/javascript">
function MM_popupMsg(msg) { //v1.0
  if(document.form1.clave_nueva1.value!=document.form1.clave_nueva2.value){
  alert(msg);}
  
}
</script>
<script type="text/javascript">
function validarForm(form1) {
/*  if(formulario.nombre.value.length==0) { //comprueba que no esté vacío
    formulario.nombre.focus();   
    alert('No has escrito tu nombre'); 
    return false; //devolvemos el foco
  }	*/ 
  var p1 = form1.clave_nueva1.value;
  var p2 = form1.clave_nueva2.value;
  var p3 = form1.clave_antigua.value;
  var tx = form1.clave_nueva1.value;
  
  var espacios = false;
  var cont = 0;

  // Este bucle recorre la cadena para comprobar
  // que no todo son espacios
        while (!espacios && (cont < p1.length)) {
                if (p1.charAt(cont) == " ")
                        espacios = true;
                cont++;
        }
   
  if (espacios) {
   alert ("La contraseña no puede contener espacios en blanco");
   return false;
  }
   
  if (p1.length == 0 || p2.length == 0) {
   alert("Los campos de la password no pueden quedar vacios");
   return false;
  }
   
  if (p1 != p2) {
   alert("Las passwords deben de coincidir");
   return false;
  }
 
   if (p3 == p1||p3 == p2) {
   alert("La nueva clave no debe ser igual a la Antigua");
   return false;
  }/*else {
   alert("Todo esta correcto");
   return true; 
  }*/else{
	var nMay = 0, nMin = 0, nNum = 0
	var t1 = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ"
	var t2 = "abcdefghijklmnñopqrstuvwxyz"
	var t3 = "0123456789"
	for (i=0;i<tx.length;i++) {
		if ( t1.indexOf(tx.charAt(i)) != -1 ) {nMay++}
		if ( t2.indexOf(tx.charAt(i)) != -1 ) {nMin++}
		if ( t3.indexOf(tx.charAt(i)) != -1 ) {nNum++}
	}
	if ( nMay>0 && nMin>0 && nNum>0 ) { return true }
	else {
		alert("La nueva clave  debe contener minimo un numero y letra mayuscula");
		 return false }
  }
}
</script>
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
             <h1>Cambio de Contrase&ntilde;a</h1>
               <form ACTION="<?php echo $loginFormAction; ?>" method="POST" name="form1"  onsubmit="return validarForm(this);">
                <ul>
                    <li><label for="pswd">Clave Anterior:</label>
                    <span><input id="pswd" type="text" name="clave_antigua"required /></span></li>
                    <li> <label for="pswd">Clave Nueva:</label>
                    <span><input id="pswd" type="password" name="clave_nueva1" required/></span></li>
                    <li><label for="pswd">Clave Nueva:</label>
                    <span><input id="pswd" type="password" name="clave_nueva2" required/></span></li>
                    <li> <button type="submit">Cambiar</button> </li>
                  </ul>	
                 </form>
                     <div id="pswd_info">
                        <h4>La contrase&ntilde;a deber&iacute;a cumplir con los siguientes requerimientos:</h4>
                        <ul>
                            <li id="letter" class="invalid">Al menos deber&iacute;a tener <strong>una letra</strong></li>
                            <li id="capital" class="invalid">Al menos deber&iacute;a tener <strong>una letra en may&uacute;sculas</strong></li>
                            <li id="number" class="invalid">Al menos deber&iacute;a tener <strong>un n&uacute;mero</strong></li>
                            <li id="length" class="invalid">Deber&iacute;a tener <strong>8 car&aacute;cteres</strong> como m&iacute;nimo</li>
                        </ul>
                     </div>        			
					<?php if ($_GET['id_m']=='1'){
                      echo "<div id='rojo3'>La clave Antigua  no existe en la Base de Datos !</b>  </div>";}?>
                    <?php if ($_GET['id_m']=='2'){
                      echo "<div id='verde'>La clave fue modificada exitosamente !</b><meta http-equiv='refresh' content='2;URL=usuario.php' </b></div>"; }?>
                    </div>   
                  </div>      
               <div class="col-md-4">
                <strong>MENU PRINCIPAL</strong><br><br>
 				 El sistema administrador de gestiones (SISADGE) de ALBERTO CADAVID R & C&Iacute;A S.A. es un desarrollo gen&eacute;rico que especifica el Sistema de Gesti&oacute;n de Calidad en nuestra organizaci&oacute;n. 
				<br>El proposito fundamental de este desarrollo es seguir paso a paso la metodologia del sistema de Gesti&oacute;n de Calidad para la linea comercial, de dise&ntilde;o, producci&oacute;n y comercializaci&oacute;n de bolsas de seguridad para el empaque y transporte de valores.<br><br>           
               </div>
                <div class="col-md-4">
                <strong>CAMBIO DE CONTRASEÑA</strong><br><br>
 				 El sistema puedes cambiar tu contraseña, siempre y cuando tengas un correo de la compañia.<br><br>
              </div>                     
          </div>

  </div> 
</body>
</html>
