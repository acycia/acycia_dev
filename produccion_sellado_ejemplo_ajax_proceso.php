<?php

$compEmail=0; //valor inicial para combrobaci�n email.
$compContra=0;  //valor inicial para comprobacion contrasena
$email=$_POST["envioEmail"]; //recoger datos de email
$contra1=$_POST["envioContra1"]; //recoger datos de contrasena 1a
$contra2=$_POST["envioContra2"]; //recoger datos de contrasena 2a
if (strlen($email)>0) { //si hay algo escrito en contrasena ....
    $num1=substr_count($email,"@"); //n�mero de signos @ escritos en el email;
    if ($num1==1) { //correcto si ha una @ en el email
        $textoEmail="<p>Escritura de e-mail correcta</p>";
        $compEmail=1; //comprobaci�n correcta de Email
        }
    else { //incorrecto si hay m�s o menos de 1 @ en email.
        $textoEmail="<p>El e-mail no se ha escrito correctamente.</p>";
        }
    }
else {  //si no hay nada escrito en email ...
   $textoEmail="<p>No has escrito ningun e-mail.</p>"; 
   }
if (strlen($contra1)<6 or strlen($contra2)<6) {  //contrasen� menos de 6 caracteres: 
   $textoContra="<p>La contrasena o su repetici�n tienen menos de 6 caracteres.</p>";
   }
elseif (strlen($contra1)>10 or strlen($contra2)>10) {  //contrasena m�s de 10 caracteres:
   $textoContra="<p>La contrasena o su repetici�n tienen m�s de 10 caracteres.</p>";
   }
elseif ($contra1 != $contra2) {  //contrasena y repetici�n distintas
   $textoContra="<p>La contrasena y su repetici�n no son iguales</p>";
   }
else {  //la contrasena y su repetici�n son correctas.
   $textoContra="<p>La contrasena es correcta.</p>";
   $compContra=1;  //Contrasena correcta
   }
if ($compEmail==1 and $compContra==1) {  //si todo est� correcto enviar mensaje ...
   echo $textoEmail.$textoContra;
   echo "<p>Los datos son correctos y han sido enviados.</p>";
   }
else{  //Si hay alg�n fallo enviar mensaje ...
   echo $textoEmail.$textoContra;
   echo "<p>Datos incorrectos. Revisa el formulario y env�alo otra vez.</p>";
   }
?>
