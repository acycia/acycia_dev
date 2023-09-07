 <?php require_once('Connections/conexion1.php'); 
 
//vaariable general global
$id=$_GET['id']; 
$estado_c="ACTIVO";	
$Str_nit=$_GET['Str_nit'];

//VARIABLES DESDE O.C
$id_oc=$_GET['id_oc'];
$num_oc=$_GET['str_numero_oc'];
//VARIABLES DESDE COTIZ 
$N_cotizacion=$_GET['N_cotizacion'];

if($id!=''){
$sqlcliente="UPDATE cliente SET estado_c='$estado_c' WHERE nit_c='$Str_nit'";
$resultcliente=mysql_query($sqlcliente); 
if($id==1){ 
 header("location:orden_compra_cl_edit.php?str_numero_oc=$num_oc&id_oc=$id_oc");
}
if($id==2){
 header("location:cotizacion_general_bolsa_generica.php?N_cotizacion=$N_cotizacion&Str_nit=$Str_nit");
}
if($id==3){
 header("location:cotizacion_general_laminas_generica.php?N_cotizacion=$N_cotizacion&Str_nit=$Str_nit");
}
if($id==4){
 header("location:cotizacion_general_packingList_generica.php?N_cotizacion=$N_cotizacion&Str_nit=$Str_nit");
}
if($id==5){
 header("location:cotizacion_general_materia_prima_ref.php?N_cotizacion=$N_cotizacion&Str_nit=$Str_nit");
}
 
}
?>