<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php  
$conexion = new ApptivaDB();
//************************** Definicion Tipo de Usuario
if(isset($_GET["iduser"]))
 
{
$row_usuario = $conexion->buscar('usuario','id_usuario',$_GET['iduser']); 
 ?>
Codigo Nuevo<input name="codigo_usuario" type="text" id="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario'];?>" size="20">
<?php
} 
?>
