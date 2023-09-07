<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script> /* Abrimos etiqueta de código */



function cambiarTextfields(selec) { 
if (selec.value == 1) { 
document.getElementById('miCampoDeTexto').disabled = true; 

} 
else if (selec.value == 2) { 
document.getElementById('miCampoDeTexto').disabled = false; 
 
} 
}

 
function bloquea() { 
if (document.form1.radio[0].checked) { 
document.form1.text1.disabled = true 
document.form1.text2.disabled = false 
} 

if (document.form1.radio[1].checked) { 
document.form1.text2.disabled = true 
document.form1.text1.disabled = false 
} 
}
</script>
</head>

<body>

<form name="formulario" action="cualquiera.html" method="post">
Acepto las condiciones <input type="check" value="acepto" onclick="document.formulario.enviar.disabled=!document.formulario.enviar.disabled"><br />
<input type="submit" name="enviar" value="Enviar" disabled>
</form>


<!--<input type="text"  id="miCampoDeTexto" /> 
<select  onchange="cambiarTextfields(this);"> 
<option value="1">Mi opción 1</value> 
<option value="2">Mi opción 2</value> 
</select >



<form name="form1" method="post" action="">
<input onkeypress="return handleEnter(this, event)" name="nombre"/> 

<input name="envia" type="submit" value="Enviar"> 
</form>

  <form name="form1" method="post" action=""> 
   <select name="h" onBlur='submit(this)'>
   <option value="1">uno</option>
      <option value="2">dos</option>
  
   </select>
    <input name="j" type="text" value="<?php echo $_POST['h']?>"/>
</form>-->
</html>