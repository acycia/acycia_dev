<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar fila de campos DINAMICOS CONTROLADOS</title>

<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script type="text/javascript">


function adicionarFila(){
var cont = document.getElementById("cont");
var filas = document.getElementById("filas");
cont.setAttribute("value", parseInt(cont.value,0)+1);
var tabla = document.getElementById("contenido").tBodies[0];
var fila = document.createElement("TR");
fila.setAttribute("align","left");
    if(tabla.getElementsByTagName("tr").length>11) {
    alert('YA NO ES POSIBLE AGREGAR MAS FILAS')
    return false;
    }

var celda1 = document.createElement("TD");
var sel = document.createElement("SELECT");
sel.setAttribute("size","1");
sel.setAttribute("name","sel" + cont.value);
opcioncur = document.createElement("OPTION");
opcioncur.innerHTML = 'Zapatos';
opcioncur.value = 'Zapato1';
sel.appendChild(opcioncur);



celda1.appendChild(sel);

var celda2 = document.createElement("TD");
var sel = document.createElement("SELECT");
sel.setAttribute("size","1");
sel.setAttribute("name","sel" + cont.value);
opcioncur = document.createElement("OPTION");
opcioncur.innerHTML = 'Verde';
opcioncur.value = 'verde';
sel.appendChild(opcioncur);

opcion1 = document.createElement("OPTION");
opcion1.innerHTML = "Rojo";
opcion1.value = "rojo";
sel.appendChild(opcion1); 

opcion2 = document.createElement("OPTION");
opcion2.innerHTML = "Fucsia";
opcion2.value = "fucsia";
sel.appendChild(opcion2); 

opcion3 = document.createElement("OPTION");
opcion3.innerHTML = "Naranja";
opcion3.value = "naranja";
sel.appendChild(opcion3); 
celda2.appendChild(sel);

opcion4 = document.createElement("OPTION");
opcion4.innerHTML = "Blanco";
opcion4.value = "blanco";
sel.appendChild(opcion4); 
celda2.appendChild(sel);

opcion5 = document.createElement("OPTION");
opcion5.innerHTML = "Negro";
opcion5.value = "negro";
sel.appendChild(opcion5); 
celda2.appendChild(sel);

opcion6 = document.createElement("OPTION");
opcion6.innerHTML = "Amarillo";
opcion6.value = "amarillo";
sel.appendChild(opcion6); 
celda2.appendChild(sel);

var celda3 = document.createElement("TD");
var valorA = document.createElement("INPUT");
valorA.setAttribute("type","text");
valorA.setAttribute("size","2");
valorA.setAttribute("maxlength","2");
valorA.setAttribute("name","valorA" + cont.value);
celda3.appendChild(valorA); 

var celda4 = document.createElement("TD");
celda4.align=("center")
celda4.className=("listo");
celda4.innerHTML = '1'; 

var celda5 = document.createElement("TD");
celda5.align=("center")
celda5.className=("listo");
celda5.innerHTML = '120 Bs'; 

var celda6 = document.createElement('TD');
var boton = document.createElement('INPUT');
celda6.align=("left") 
boton.setAttribute('type','button');
boton.setAttribute('value','borrar');
boton.onclick=function(){borrarFila(this);add(-1);add2(-100)}
boton.className=("boton")
celda6.appendChild(boton);


fila.appendChild(celda1);
fila.appendChild(celda2);
fila.appendChild(celda3);
fila.appendChild(celda4);
fila.appendChild(celda5);
fila.appendChild(celda6);

tabla.appendChild(fila);
}
function borrarFila(button){
var fila = button.parentNode.parentNode;
var tabla = document.getElementById('contenido').getElementsByTagName('tbody')[0];
tabla.removeChild(fila);
}
function add(delta) {

  valor = eval(detalle.casilla.value);
  if(tabla.getElementsByTagName("tr").length>11) return false;
  detalle.casilla.value = eval(valor+delta);  

}
function add2(delta) {

  valor = eval(detalle.total.value);
  if(tabla.getElementsByTagName("tr").length>11) return false;
  detalle.total.value = eval(valor+delta);  

}

</script>
<link href="comprar.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
<Form name="detalle" action="procesar.php" method="post">
<input name="cont" type="hidden" id="cont" value="0" >
<input name="filas" type="hidden" id="filas" value="" >
<table id="contenido" width="100%">
  <tr class="cabezado" bgcolor="#000000" align="left">
    <th width="16%" scope="col">Producto</th>
    <th width="15%" scope="col">Color</th>
    <th width="13%" scope="col">Talla</th>
    <th width="16%" scope="col">Cantidad</th>
    <th width="22%" scope="col">Precio</th>
    <th width="18%" scope="col">Campo</th>
  </tr>
  <tr  align="left">
    <th scope="col">
      <select class="menu" name="producto">
        <option value="baya">Zapatos</option>
      </select></th>
    <th scope="col"><label for="color"></label>
      <select class="menu" name="color" id="color">
        <option value="verde">Verde</option>
        <option value="rojo">Rojo</option>
        <option value="fucsia">Fucsia</option>
        <option value="naranja">Naranja</option>
        <option value="blanco">Blanco</option>
        <option value="negro">Negro</option>
        <option value="amarillo">Amarillo</option>
      </select></th>
    <th scope="col"><label for="talla"></label>
      <span id="sprytextfield1">
      <input name="talla2" type="text" id="talla2" size="2" maxlength="2">
      <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></th>
    <th class="listo" align="center" scope="col">1</th>
    <th class="listo" align="center"scope="col">100 Bs</th>
    <th class="listo" align="center" scope="col">*</th>
  </tr>
</table>

<input name="nueva_fila" type="button" class="boton" value="Agregar Otro" onClick="adicionarFila();add(1);add2(100)">
<input type="submit" name="Submit" value="Proceder" />


<div class="listo">
  <table align="right" width="35%">
      <th width="30%" bgcolor="#000000" class="cabezado" align="right" scope="row">Cantidad</th>
      <td bgcolor="#000000" width="14%">
      <input name="casilla" type="text" value="3" size="6" readonly></td>
      <td bgcolor="#000000" align="right" class="cabezado" width="25%">Total</td>
      <td bgcolor="#000000" width="27%"><input name="total" type="text" value="300" size="10" readonly></td><td width="4%"></td>
    </tr>
      <tr>
        <td>
      Ejemplo de Html 5 los campos de abajo
        <input type="range" … >
<input type="date" name="bday" min="2000-01-02">
<input type="time" … >
<input  type="text"… placeholder="John Doe">
<input type="text" … required>
<input type="text" … pattern="[a-z]{3}[0-9]{3}"></td></tr>
    <tr>
  </table>
  
</div>
</form>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
</body>
</html>