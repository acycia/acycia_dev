<?php require_once('Connections/conexion1.php'); ?>
<? 
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);



?>

<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$tipo=$_POST['tipo_usuario'];
$id_usuario=$_POST['id_usuario'];
if($tipo == '10')
{
$id=$_POST['id_c'];
$sql3="UPDATE usuario SET codigo_usuario='$id' WHERE id_usuario='$id_usuario'";
$result3=mysql_query($sql3);
}
$_POST['nit']="133";
$nombre=$_POST['otra'];
//for ($x=0; $x<count($nombre); $x++) {	
echo  $insertSQL = sprintf("INSERT INTO Tbl_prueba (nit,otra) VALUES (%s, %s)",
                       GetSQLValueString($_POST['nit'], "int"),
					   GetSQLValueString($nombre, "int"));
					   echo '<br>';
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());


$pnt='2';
$nombre=$_POST['responsable_dest'];
$dir=$_POST['direccion_dest'];
$tel=$_POST['telefono_dest'];
$ciu=$_POST['ciudad_dest'];
for($n=0,$d=0,$t=0,$c=0;$n<count($nombre);$n++,$d++,$t++,$c++){

//for ($x=0; $x<count($nombre); $x++) {	
echo  $insertSQL2 = sprintf("INSERT INTO Tbl_Destinatarios (nit,nombre_responsable,direccion,telefono,ciudad) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($pnt, "text"),
                       GetSQLValueString($nombre[$n], "text"),
                       GetSQLValueString($dir[$d], "text"),
                       GetSQLValueString($tel[$t], "text"),
                       GetSQLValueString($ciu[$c], "text"));
					   echo '<br>';
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());					   
//}
}
  $insertGoTo = "perfil_cliente_vista.php?id_c=" . $_POST["id_c"] . "&tipo_usuario=" . $_POST["tipo_usuario"] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


/*$nombre=$_POST['nombre_responsable'];
$dir=$_POST['direccion'];
$tel=$_POST['telefono'];
$ciu=$_POST['ciudad'];
for($n=0,$d=0,$t=0,$c=0;$n<count($nombre);$n++,$d++,$t++,$c++){

echo $insertSQL ="INSERT INTO Tbl_Destinatarios (nit, nombre_responsable, direccion, telefono, ciudad ) VALUES ('$_POST[nit]','$nombre[$n]','$dir[$d]','$tel[$t]','$ciu[$c]')";	
	echo '<br>';
      // echo "<br>Mes: $n $m => $nit[$n]$nombre[$m]";
}*/  
  



/*$people = array(
          array('name' => 'Kalle', 'salt' => 856412),
          array('name' => 'Pierre', 'salt' => 215863)
               );

for($i = 0, $size = sizeof($people); $i < $size; ++$i)
{
    $people[$i]['salt'] = rand(000000, 999999);
	echo "imprime: $i  ".$i;
}*/



/*  $insertGoTo = "perfil_cliente_vista.php?id_c=" . $_POST["id_c"] . "&tipo_usuario=" . $_POST["tipo_usuario"] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/consulta_ciudad.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/agregueCampos.js"></script>
<script language=""="JavaScript">
    function conMayusculas(field) {
            field.value = field.value.toUpperCase()
}

				

</script>
</head>
<body oncontextmenu="return false">
  <table width="92%" id="tabla_formato">
    <tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
      <li><?php echo $row_usuario['nombre_usuario']; ?></li>       
       <li><a href="comercial.php">COMERCIAL</a></li>
       <li><a href="menu.php">MENU</a></li>
       <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>  
      </ul></div></div>
</td></tr></table>


<body>
     <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="MM_validateForm('fecha_ingreso_c','','R','fecha_solicitud_c','','R','nombre_c','','R','nit_c','','R','tipo_c','','R','rep_legal_c','','R','telefono_c','','R','direccion_c','','R','pais_c','','R','ciudad_c','','R','registrado_c','','R');return document.MM_returnValue">
 
<fieldset id="field">
<input name="otra" type="text" />
<table width="100%"id="tablaUsuarios">
<tr>
 <td colspan="8" id="subtitulo2">INFORMACION DE DESPACHO BODEGAS</td>
  <tr>

<!--<td width="175" id="subtitulo3">NIT</td>-->

<td width="350" id="subtitulo3">RESPONSABLE</td>

<td width="100" id="subtitulo3">DIRECCION</td>

<!--<td width="50" id="subtitulo3">INDICATIVO</td>-->
<td width="100" id="subtitulo3">TELEFONO</td>
<!--<td width="50" id="subtitulo3">EXTENSION</td>-->

<td width="100" id="subtitulo3">CIUDAD</td>

<td width="118">   <input type="button" onClick="crear()"
value="Agregar Bodegas" > </td>
<input name="save" type="submit" value="Adicionar Perfil de Cliente"onClick="enviar()"></td>
</tr>
</table>
<input type="hidden" name="MM_insert" value="form1">

</form>
  
<?
  //Recorrer lo elementos del arreglo
/*      foreach ($_POST['text'] as $value) {
   
       
      echo    $sql= "INSERT INTO Tbl_Destinatarios (nit ) VALUES (NULL,'$value')<br>\n";
      }*/

					    
 
     
 ?>
</body>
</html>