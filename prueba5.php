<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script>
function asignarUbi(){

  var ubicacion, indice;
	
	
  indice = document.getElementById('rutaUbi').selectedIndex;
  
  ubicacion = document.getElementById('rutaUbi').options[indice].text;
  alert(ubicacion);
}  
</script>
</head>

<body>
<select id="selectid">
    <option value="1">Primera opción</option>
    <option value="2">Segunda opción</option>
    <option value="3">Tercera opción</option>
</select>
 
<input 
  type="button" 
  value="Ver texto seleccionado" 
  onclick="alert(document.getElementById('selectid').options[document.getElementById('selectid').selectedIndex].text);" 
/> 
</body>
</html>