<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);
/*----------VARIABLES------------*/
/*----------ITEMS O.C. CLIENTE--------*/
$str_numero_io=$_GET['str_numero_io'];
$id_oc_io=$_GET['id_oc'];
$id_pedido=$_GET['id_pedido'];
$ref_item=$_GET['ref_item'];
$fecha_item=$_GET['fecha_item'];

if($ref_item != ''&&$str_numero_io!=''&&$id_oc_io!=''&&$id_pedido!=''&&$fecha_item!=''){
$sqlref="SELECT * FROM Tbl_items_ordenc WHERE str_numero_io='$str_numero_io' AND int_cod_ref_io='$ref_item'";
$resultref= mysql_query($sqlref);
$numref= mysql_num_rows($resultref);
if($numref >='1'){
	$fecha=mysql_result($resultref,0,'fecha_entrega_io');
if($fecha==$fecha_item){
$id=0; header("location:orden_compra_cl_add_detalle.php?str_numero_oc=$str_numero_io&id_oc=$id_oc_io&id_pedido=$id_pedido&id=$id");
	}/*else
	$id=1;$cal=1;
	header("location:orden_compra_cl_add_detalle.php?str_numero_oc=$str_numero_io&id_oc=$id_oc_io&id_pedido=$id_pedido&cal=$cal&id=$id");	
 }
}*/

   }else
 if($numref <='0'){
/*	$sqlref2="SELECT * FROM Tbl_items_ordenc WHERE str_numero_io='$str_numero_io' AND int_cod_ref_io='$ref_item'";
$resultref2= mysql_query($sqlref2);
$numref2= mysql_num_rows($resultref2);
if($numref2 <='0'){*/
$id=1; header("location:orden_compra_cl_add_detalle.php?str_numero_oc=$str_numero_io&id_oc=$id_oc_io&id_pedido=$id_pedido&id=$id");
// }
 }else
 if($numref <=''){
$id=1; header("location:orden_compra_cl_add_detalle.php?str_numero_oc=$str_numero_io&id_oc=$id_oc_io&id_pedido=$id_pedido&id=$id");	 
	 
	 }
 
 }

 
 
?>