<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Graficacion-de-Reportes</title>
<link href="css/graficas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
 
 function cambiar() {
 
 
  document.getElementById('matrix').src = "librerias/jpgraph-4.0.0/linear_plot.php";
	 
 }
  
</script>
</head>
 <body>
 <div class="container">
 <div class="cabezote"><img src="images/cabecera.jpg"></div>
  <div class="sidebar1">
    <ul class="nav">
      <li><a href="#" onClick="cambiar();">Tiempos y desp</a></li>
      <li><a href="#" onClick="cambiar();">Otros1</a></li>
      <li><a href="#" onClick="cambiar();">Otros2</a></li>
      <li><a href="#" onClick="cambiar();">Otros3</a></li>
    </ul> 
    <!-- end .sidebar1 --></div>
   <div class="content">
    <h1 class="detalle1">Graficas</h1>
    <p><img src="#" alt="grafica" border="0" id="matrix"></p>
    <h2>&nbsp; </h2>
    <p>&nbsp; </p>
    <!-- end .content --></div>
  <!-- end .container --></div>
</body>
</html>