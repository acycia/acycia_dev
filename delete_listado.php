<?php require_once('Connections/conexion1.php'); ?>
<?php mysql_select_db($database_conexion1, $conexion1);
/*EGP DE LA BOLSA*/
if($_GET['borrado'] == '1')
{ if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) { 
$sql = "DELETE FROM egp WHERE n_egp = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:egp_bolsa.php?id=$id"); } }
else { $id=0; header("location:egp_bolsa.php?id=$id"); }}
/*COTIZACION BOLSA*/
if($_GET['borrado']=='2') { 
if(count($_GET['cotiz'])) {
foreach ($_GET['cotiz'] as $v) {
$sqlcn="SELECT * FROM egp, cotizacion_nueva WHERE cotizacion_nueva.n_cotiz_cn = '$v' AND cotizacion_nueva.n_egp_cn = egp.n_egp";
$resultcn=mysql_query($sqlcn);
$numcn=mysql_num_rows($resultcn);
for($i=0; $i<$numcn; $i++)
{
$n_egp=mysql_result($resultcn,$i,'n_egp');
$sqlegps="UPDATE egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegps=mysql_query($sqlegps);
}
$sqlcns="DELETE FROM cotizacion_nueva WHERE n_cotiz_cn='$v'";
$resultcns=mysql_query($sqlcns);
$sqlexiste="DELETE FROM cotizacion_existente WHERE n_cotiz_ce='$v'";
$resultexiste=mysql_query($sqlexiste);
$sql="DELETE FROM cotizacion WHERE n_cotiz = $v";
$resultado= mysql_query($sql,$conexion1);
$id=1;
header("location:cotizacion_bolsa.php?id=$id"); }}
else {
$id=0; header("location:cotizacion_bolsa.php?id=$id"); }}
/*REVISION BOLSA*/
if($_GET['borrado'] == '3'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql = "DELETE FROM revision WHERE id_rev = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:revision.php?id=$id"); }}
else { $id=0; header("location:revision.php?id=$id"); }}
/*BUSQUEDA DE REFERENCIA*/
if($_GET['borrado'] == '4'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlref="SELECT * FROM referencia WHERE id_ref = $v";
$resultref= mysql_query($sqlref);
$numref= mysql_num_rows($resultref);
if($numref >='1'){
$n_egp=mysql_result($resultref,0,'n_egp_ref');
$sqlegps="UPDATE egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegps=mysql_query($sqlegps);}
$sqlrefcliente="DELETE FROM ref_cliente WHERE id_ref = $v";
$resultrefcliente=mysql_query($sqlrefcliente);
$sqlrevision="DELETE FROM revision WHERE id_ref_rev = $v";
$resultrevision=mysql_query($sqlrevision);
/*eliminacion de artes*/
$sqlarte="SELECT * FROM verificacion WHERE id_ref_verif='$v'";
$resultarte= mysql_query($sqlarte);
$arte = mysql_result($resultarte, 0, 'userfile');
if($arte!=''){ if(file_exists("archivo/".$arte)) { unlink("archivo/".$arte); }}
/*--------------*/
$sqlverif="DELETE FROM verificacion WHERE id_ref_verif = $v";
$resultverif=mysql_query($sqlverif);
$sqlcm="DELETE FROM control_modificaciones WHERE id_ref_cm = $v";
$resultcm=mysql_query($sqlcm);
$sqlval="DELETE FROM validacion WHERE id_ref_val = $v";
$resultval=mysql_query($sqlval);
$sqlft="DELETE FROM ficha_tecnica WHERE id_ref_ft = $v";
$resultft=mysql_query($sqlft);
$sql = "DELETE FROM referencia WHERE id_ref = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:referencia_busqueda.php?id=$id"); }}
else{
$id=0; header("location:referencia_busqueda.php?id=$id"); }}
/*ELIMINAR VERIFICACIONES*/
if($_GET['borrado'] == '5'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
/*eliminar artes*/
$sqlarte="SELECT * FROM verificacion WHERE id_verif = $v";
$resultarte= mysql_query($sqlarte);
$numarte= mysql_num_rows($resultarte);
if($numarte >='1'){ 
   $arte = mysql_result($resultarte, 0, 'userfile'); 
   if($arte!=''){   
      if(file_exists("archivo/".$arte)){ unlink("archivo/".$arte); }
   }
}
$sqlcm="DELETE FROM control_modificaciones WHERE id_verif_cm = $v";
$resultcm=mysql_query($sqlcm);
$sqlverif="DELETE FROM verificacion WHERE id_verif = $v";
$resultverif=mysql_query($sqlverif);
$id=1;
header("location:verificacion.php?id=$id"); }}
else{
$id=0;
header("location:verificacion.php?id=$id"); }}
/*ELIMINAR MODIFICACIONES*/
if($_GET['borrado'] == '6'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlcm="DELETE FROM control_modificaciones WHERE id_cm = $v";
$resultcm=mysql_query($sqlcm);
$id=1;
header("location:control_modificaciones.php?id=$id");
}
}
else
{
$id=0;
header("location:control_modificaciones.php?id=$id");
}
}
/*ELIMINAR VALIDACIONES*/
if($_GET['borrado'] == '7')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlval="DELETE FROM validacion WHERE id_val = $v";
$resultval=mysql_query($sqlval);
$id=1;
header("location:validacion.php?id=$id");
}
}
else
{
$id=0;
header("location:validacion.php?id=$id");
}
}
/*ELIMINAR FICHAS TECNICAS*/
if($_GET['borrado'] == '8')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlft="DELETE FROM ficha_tecnica WHERE n_ft = $v";
$resultft=mysql_query($sqlft);
$id=1;
header("location:ficha_tecnica_busqueda.php?id=$id");
}
}
else
{
$id=0;
header("location:ficha_tecnica_busqueda.php?id=$id");
}
}
/*ELIMINAR PROVEEDOR*/
if($_GET['borrado'] == '9')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {

$sqlsel="DELETE FROM proveedor_seleccion WHERE id_p_seleccion='$v'";
$resultsel=mysql_query($sqlsel);
$sqlpm="DELETE FROM proveedor_mejora WHERE id_p_pm='$v'";
$resultpm=mysql_query($sqlpm);
$sqlp="DELETE FROM proveedor WHERE id_p = $v";
$resultp=mysql_query($sqlp);
$id=1;
header("location:proveedor_busqueda.php?id=$id");
}
}
else
{
$id=0;
header("location:proveedor_busqueda.php?id=$id");
}
}
/*ELIMINAR INSUMOS*/
if($_GET['borrado'] == '10')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlinsumo="UPDATE insumo SET estado_insumo='1' WHERE id_insumo='$v'";
$resultinsumo=mysql_query($sqlinsumo);
$id=1;
header("location:insumos_busqueda.php?id=$id");
}
}
else
{
$id=0;
header("location:insumos_busqueda.php?id=$id");
}
}

/*ELIMINAR O.C.*/
if($_GET['borrado'] == '11')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqldet="DELETE FROM orden_compra_detalle WHERE n_oc_det='$v'";
$resultdet=mysql_query($sqldet);
$sqloc="DELETE FROM orden_compra WHERE n_oc='$v'";
$resultoc=mysql_query($sqloc);
$id=1;
header("location:orden_compra.php?id=$id");
}
}
else
{
$id=0;
header("location:orden_compra.php?id=$id");
}
}
/*ELIMINAR VERIFICACIONES*/
if($_GET['borrado'] == '12')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlv="SELECT * FROM verificacion_insumos WHERE n_vi = $v";
$resultv= mysql_query($sqlv);
$numv= mysql_num_rows($resultv);
if($numv >='1')
{
$id_det_vi=mysql_result($resultv,0,'id_det_vi');
$cantidad_recibida_vi = mysql_result($resultv, 0, 'cantidad_recibida_vi');
$faltantes_vi = mysql_result($resultv, 0, 'faltantes_vi');
$saldo=$faltantes_vi+$cantidad_recibida_vi;
$sqldetalle="UPDATE orden_compra_detalle SET verificacion_det='$saldo' WHERE id_det='$id_det_vi'";
$resultdetalle=mysql_query($sqldetalle);
}
$sqlvi="DELETE FROM verificacion_insumos WHERE n_vi='$v'";
$resultvi=mysql_query($sqlvi);
$id=1;
header("location:verificaciones_criticos.php?id=$id");
}
}
else
{
$id=0;
header("location:verificaciones_criticos.php?id=$id");
}
}
/*ELIMINAR ROLLOS*/
if($_GET['borrado'] == '13')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlrollo="DELETE FROM materia_prima_rollos WHERE id_rollo='$v'";
$resultrollo=mysql_query($sqlrollo);
$id=1;
header("location:rollos_busqueda.php?id=$id");
}
}
else
{
$id=0;
header("location:rollos_busqueda.php?id=$id");
}
}
/*ELIMINAR O.C. ROLLOS*/
if($_GET['borrado'] == '14')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlocr="DELETE FROM orden_compra_rollos WHERE n_ocr='$v'";
$resultocr=mysql_query($sqlocr);
$id=1;
header("location:rollos_oc.php?id=$id");
}
}
else
{
$id=0;
header("location:rollos_oc.php?id=$id");
}
}
/*ELIMINAR VERIFICACION ROLLOS*/
if($_GET['borrado'] == '15')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql1="SELECT * FROM verificacion_rollos WHERE n_vr='$v'";
$result1= mysql_query($sql1);
$num1= mysql_num_rows($result1);
if($num1 >='1')
{
$n_ocr=mysql_result($result1,0,'n_ocr_vr');
$cantidad_encontrada=mysql_result($result1,0,'cantidad_encontrada_vr');
$sql2="SELECT * FROM orden_compra_rollos WHERE n_ocr='$n_ocr'";
$result2= mysql_query($sql2);
$num2= mysql_num_rows($result2);
if($num2>='1')
{
$saldo_verificacion=mysql_result($result2,0,'saldo_verificacion_ocr');
}
$falta=$saldo_verificacion+$cantidad_encontrada;
$sql3="UPDATE orden_compra_rollos SET saldo_verificacion_ocr='$falta' WHERE n_ocr='$n_ocr'";
$result3=mysql_query($sql3);
}
$sqlvr="DELETE FROM verificacion_rollos WHERE n_vr='$v'";
$resultvr=mysql_query($sqlvr);
$id=1;
header("location:rollos_verificacion.php?id=$id");
}
}
else
{
$id=0;
header("location:rollos_verificacion.php?id=$id");
}
}
/*ELIMINAR BOLSAS*/
if($_GET['borrado'] == '16')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlbolsa="DELETE FROM material_terminado_bolsas WHERE id_bolsa='$v'";
$resultbolsa=mysql_query($sqlbolsa);
$id=1;
header("location:bolsas_busqueda.php?id=$id");
}
}
else
{
$id=0;
header("location:bolsas_busqueda.php?id=$id");
}
}
/*ELIMINAR O.C. BOLSAS*/
if($_GET['borrado'] == '17')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlocb="DELETE FROM orden_compra_bolsas WHERE n_ocb='$v'";
$resultocb=mysql_query($sqlocb);
$id=1;
header("location:bolsas_oc.php?id=$id");
}
}
else
{
$id=0;
header("location:bolsas_oc.php?id=$id");
}
}
/*ELIMINAR O.C. BOLSAS*/
if($_GET['borrado'] == '18')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlvb="DELETE FROM verificacion_bolsas WHERE n_vb='$v'";
$resultvb=mysql_query($sqlvb);
$id=1;
header("location:bolsas_verificacion.php?id=$id");
}
}
else
{
$id=0;
header("location:bolsas_verificacion.php?id=$id");
}
}
/*ELIMINAR PEDIDO DEL CLIENTE*/
if($_GET['borrado'] == '19')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqldet="DELETE FROM pedido_detalle WHERE id_pedido='$v'";
$resultdet=mysql_query($sqldet);
$sqlpedido="DELETE FROM pedido WHERE id_pedido='$v'";
$resultpedido=mysql_query($sqlpedido);
$id=1;
header("location:pedido_bolsa.php?id=$id"); } }
else { $id=0; header("location:pedido_bolsa.php?id=$id"); } 
}
/*ELIMINAR LISTADO DE EMPLEADOS*/
if($_GET['borrado'] == '20')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql1="SELECT * FROM empleado WHERE id_empleado='$v'";
$result1= mysql_query($sql1);
$num1= mysql_num_rows($result1);
if($num1 >='1')
{	
$codigo_emp=mysql_result($result1,0,'codigo_empleado');	
$sqlemp="DELETE FROM empleado WHERE id_empleado='$v'";
$resultemp=mysql_query($sqlemp);
//ELIMINA APORTES
$sqlaporte="DELETE FROM TblAportes WHERE codigo_empl='$codigo_emp'";
$resultaporte=mysql_query($sqlaporte);
/*ELIMINAR LISTADO DE EMPLEADOS PROCESO*/
$sqlemp="DELETE FROM TblProcesoEmpleado WHERE id_pem='$codigo_emp'";
$resultemp=mysql_query($sqlemp);

$id=1;header("location:empleados.php?id=$id"); 
    }else { $id=0; header("location:empleados.php?id=$id"); } 
  }
 }
}
/*---------------------------------------------------*/
/*-------EGL DE LAMINA-------*/
if($_GET['borrado'] == '21')
{ 
   if(count($_GET['borrar'])) {
	   foreach ($_GET['borrar'] as $v) { 
	     /*COLORES*/
	     $sqlcolores="DELETE FROM egl_colores WHERE n_egl = $v";
		 $resultcolores=mysql_query($sqlcolores);		 
		 /*ARCHIVOS*/
		 $sqldato1="SELECT * FROM egl_archivos WHERE n_egl=$v";
		 $seleccion = mysql_query($sqldato1);            
         while($row=mysql_fetch_array($seleccion, MYSQL_ASSOC))
         { unlink("egplamina/".$row['archivo']); }
		 $sqldato="DELETE FROM egl_archivos WHERE n_egl = $v";
		 $resultdato=mysql_query($sqldato);
		 /*EGL*/
		 $sql = "DELETE FROM egl WHERE n_egl = $v";
		 $resultado = mysql_query($sql);
		 $id=1; header("location:egp_lamina.php?id=$id"); } }
   else { $id=0; header("location:egp_lamina.php?id=$id"); }
}
/*ELIMINAR O.C.*/
if($_GET['borrado'] == '22')  {
if(count($_GET['borrar'])) {
    foreach ($_GET['borrar'] as $v) {
	$sql1="SELECT * FROM Tbl_orden_compra WHERE id_pedido='$v'";
	$result1= mysql_query($sql1);
	$num1= mysql_num_rows($result1);
	if($num1 >='1')
	{	
	$ordenCompra=mysql_result($result1,0,'str_numero_oc');				  
    $sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op  
FROM Tbl_orden_produccion WHERE str_numero_oc_op='$ordenCompra' AND b_borrado_op='0'";
   $resultmp= mysql_query($sqlmp);
   $nump = mysql_num_rows($resultmp);
 		
   $sql2="SELECT * FROM Tbl_remisiones WHERE str_numero_oc_r='$ordenCompra'";
	$result2= mysql_query($sql2);
	$num2= mysql_num_rows($result2);
	
    if($num2 >='1' || $nump >='1')
	    {
		$id=2;
		header("location:orden_compra_cl.php?id=$id");	
	    }else{
		//FOREACH2
 		foreach ($_GET['borrar'] as $v) { 
		$sqloc="DELETE FROM Tbl_orden_compra WHERE id_pedido='$v'";//se eliminan tambien los items en cascada
 		$resultoc=mysql_query($sqloc);
		$id=1;
		header("location:orden_compra_cl.php?id=$id");
		}//fin foreach 2
		}//fin if
	    }
		else {
		$id=0;
		header("location:orden_compra_cl.php?id=$id");
		}	                                
 		}//fin foreach 1
    }
 }
/*ACTIVAR O.C. DEL CLIENTE PASA A ESTADO 0 ACTIVA*/
if($_GET['borrado'] == '222'){
if(count($_GET['borrar'])) {
    foreach ($_GET['borrar'] as $v) {
	$sql1="SELECT * FROM  Tbl_orden_compra WHERE id_pedido='$v'";
	$result1= mysql_query($sql1);
	$num1= mysql_num_rows($result1);
	if($num1 >='1')
	{		
	$ordenCompra=mysql_result($result1,0,'str_numero_oc');		
    $sql2="SELECT * FROM Tbl_remisiones WHERE str_numero_oc_r='$ordenCompra'";
	$result2= mysql_query($sql2);
	$num2= mysql_num_rows($result2);
    if($num2 >='1')
	    {
		$id=2;
		header("location:orden_compra_cl3.php?id=$id");	
	    }else{
		//FOREACH2
 		foreach ($_GET['borrar'] as $v) { 
		$sqloc="DELETE FROM Tbl_orden_compra WHERE id_pedido='$v'";//se eliminan tambien los items en cascada
 		$resultoc=mysql_query($sqloc);
		$id=1;
		header("location:orden_compra_cl3.php?id=$id");
		}//fin foreach 2
		}//fin if
	    }
		else {
		$id=0;
		header("location:orden_compra_cl3.php?id=$id");
		}	                                
 		}//fin foreach 1
    }
}
/*ELIMINAR REMISION DE LA O.C. PASA A ESTADO 1 INACTIVA*/
if($_GET['borrado'] == '23')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlre="UPDATE  Tbl_remisiones SET b_borrado_r='1' WHERE int_remision='$v' AND b_borrado_r='0'";
$resultre=mysql_query($sqlre);
$id=1;
header("location:despacho_listado1_oc.php?id=$id");
}
}
else
{
$id=0;
header("location:despacho_listado1_oc.php?id=$id");
}


/*if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
			
    $sql1="SELECT int_item_io_rd,int_cant_rd FROM Tbl_remision_detalle WHERE int_remision_r_rd='$v'";
	$result1 = mysql_query($sql1);
	$num1 = mysql_num_rows($result1);
    $items=mysql_result($result1,0,'int_item_io_rd');
	$total_can=(float)$cant_rd=mysql_result($result1,0,'int_cant_rd');	
	if(count($items)) {
        foreach ($items as $up) {
 		 $sqlsuma="UPDATE Tbl_items_ordenc SET int_cantidad_rest_io=int_cantidad_rest_io + '$total_can' WHERE id_items='$up'";
	     $resultsuma=mysql_query($sqlsuma);	
 		 }//fin foreanch 
	}//fin if
$sqlre="UPDATE Tbl_remisiones SET b_borrado_r='0' WHERE int_remision='$v' AND b_borrado_r='0'";
$resultre=mysql_query($sqlre);
$id=1;
header("location:despacho_listado1_oc.php?id=$id");
}
}*/
}

 
if($_GET['update'] == 'activaRem')
{
   if(count($_GET['borrar'])) {
    foreach ($_GET['borrar'] as $v) {
     $sqlre="UPDATE  Tbl_remisiones SET b_borrado_r='0' WHERE int_remision='$v' ";
     $resultre=mysql_query($sqlre);
     $id=1;
     header("location:despacho_listado_oci.php?id=$id");
    }
   }
   else
   {
       $id=0;
      header("location:despacho_listado_oci.php?id=$id");
   }
}


/*REVISION LAMINA*/
if($_GET['borrado'] == '24'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql = "DELETE FROM Tbl_revision_lamina WHERE id_rev_l = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:revision_l.php?id=$id"); }}
else { $id=0; header("location:revision_l.php?id=$id"); }}
/*VERIFICACIONES LAMINA*/
if($_GET['borrado'] == '25'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
/*eliminar artes*/
$sqlarte="SELECT * FROM Tbl_verificacion_lamina WHERE id_verif_l = $v";
$resultarte= mysql_query($sqlarte);
$numarte= mysql_num_rows($resultarte);
if($numarte >='1'){ 
   $arte = mysql_result($resultarte, 0, 'userfile_l'); 
   if($arte!=''){   
      if(file_exists("archivo/".$arte)){ unlink("archivo/".$arte); }
   }
}
$sqlcm="DELETE FROM Tbl_control_modificaciones_l WHERE id_verif_cm = $v";
$resultcm=mysql_query($sqlcm);
$sqlverif="DELETE FROM Tbl_verificacion_lamina WHERE id_verif_l = $v";
$resultverif=mysql_query($sqlverif);
$id=1;
header("location:verificacion_l.php?id=$id"); }}
else{
$id=0;
header("location:verificacion_l.php?id=$id"); }}
/*ELIMINAR MODIFICACIONES LAMINA*/
if($_GET['borrado'] == '26'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlcm="DELETE FROM Tbl_control_modificaciones_l WHERE id_cm = $v";
$resultcm=mysql_query($sqlcm);
$id=1;
header("location:control_modificaciones_l.php?id=$id");
}
}
else
{
$id=0;
header("location:control_modificaciones_l.php?id=$id");
}
}
/*ELIMINAR MODIFICACIONES PACKING*/
if($_GET['borrado'] == '27'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlcm="DELETE FROM Tbl_control_modificaciones_p WHERE id_cm = $v";
$resultcm=mysql_query($sqlcm);
$id=1;
header("location:control_modificaciones_p.php?id=$id");
}
}
else
{
$id=0;
header("location:control_modificaciones_p.php?id=$id");
}
}
/*REVISION PACKING*/
if($_GET['borrado'] == '28'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql = "DELETE FROM Tbl_revision_packing WHERE id_rev_p = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:revision_p.php?id=$id"); }}
else { $id=0; header("location:revision_p.php?id=$id"); }}
/*REVISION PACKING*/
if($_GET['borrado'] == '29'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql = "DELETE FROM Tbl_verificacion_packing WHERE id_verif_p = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:verificacion_p.php?id=$id"); }}
else { $id=0; header("location:verificacion_p.php?id=$id"); }}
/*REVISION PACKING*/
if($_GET['borrado'] == '30'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql = "DELETE FROM Tbl_validacion_packing WHERE id_val_p = $v";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:validacion_p.php?id=$id"); }}
else { $id=0; header("location:validacion_p.php?id=$id"); }}
/*-----------------GESTION DE PRODUCCION-------------*/
//INACTIVAR CARACTERISTICAS
if($_GET['borrado'] == '31')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlc="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv = $v AND b_borrado_cv='0'";
$resultc= mysql_query($sqlc);
$numc= mysql_num_rows($resultc);
/*if($numc >='1')
{
$id_pm_cv=mysql_result($resultc,0,'id_pm_cv');*/

$sqlpm="UPDATE Tbl_produccion_mezclas SET b_borrado_pm='1' WHERE id_ref_pm=$v AND b_borrado_pm='0'";
$resultpm=mysql_query($sqlpm);

$sqlcu="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='1' WHERE id_ref_cv=$v AND b_borrado_cv='0'";
$resultcu=mysql_query($sqlcu);
$id=1;
header("location:produccion_caracteristicas.php?id=$id");
//}
}
}
else
{
$id=0;
header("location:produccion_caracteristicas.php?id=$id");
}
}
//ACTIVAR CARACTERISTICAS
if($_GET['activar'] == 'activar')
{
if(count($_GET['activa'])) {
foreach ($_GET['activa'] as $v) {
$sqlc="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv = $v AND b_borrado_cv='1'";
$resultc= mysql_query($sqlc);
$numc= mysql_num_rows($resultc);
/*if($numc >='1')
{
$id_pm_cv=mysql_result($resultc,0,'id_pm_cv');*/

$sqlpm="UPDATE Tbl_produccion_mezclas SET b_borrado_pm='0' WHERE id_ref_pm=$v AND b_borrado_pm='1'";
$resultpm=mysql_query($sqlpm);

$sqlcu="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='0' WHERE id_ref_cv=$v AND b_borrado_cv='1'";
$resultcu=mysql_query($sqlcu);
$id=2;
header("location:produccion_caracteristicas.php?id=$id");
//}
}
}
else
{
$id=3;
header("location:produccion_caracteristicas.php?id=$id");
}
}
//INACTIVAR MEZCLAS EXTRUDER
if($_GET['borrado'] == '32')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlpm="UPDATE Tbl_produccion_mezclas SET b_borrado_pm='1' WHERE id_ref_pm=$v AND b_borrado_pm='0'";
$resultpm=mysql_query($sqlpm);
$sqlc="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='1' WHERE id_ref_cv=$v AND b_borrado_cv='0'";//SE INACTIVA TODOS LOS PROCESOS DE ESTA REF
$resultc=mysql_query($sqlc);
$id=1;
header("location:produccion_mezclas.php?id=$id");
}
}
else
{
$id=0;
header("location:produccion_mezclas.php?id=$id");
}
}
//ACTIVAR MEZCLAS
if($_GET['activar2'] == 'activar2')
{
if(count($_GET['activa'])) {
foreach ($_GET['activa'] as $v) {
$sqlpm="UPDATE Tbl_produccion_mezclas SET b_borrado_pm='0' WHERE id_ref_pm=$v AND b_borrado_pm='1'";
$resultpm=mysql_query($sqlpm);

$sqlc="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='0' WHERE id_ref_cv=$v  AND b_borrado_cv='1'";
$resultc=mysql_query($sqlc);
$id=2;
header("location:produccion_mezclas.php?id=$id");
}
}
else
{
$id=3;
header("location:produccion_mezclas.php?id=$id");
}
}

//INACTIVAR CARACTERISTICAS
if($_GET['borrado'] == '33')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlc="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv = $v AND b_borrado_cv='0'";
$resultc= mysql_query($sqlc);
$numc= mysql_num_rows($resultc);
/*if($numc >='1')
{
$id_pm_cv=mysql_result($resultc,0,'id_pm_cv');*/

$sqlpm="UPDATE Tbl_produccion_mezclas SET b_borrado_pm='1' WHERE id_ref_pm=$v AND b_borrado_pm='0'";
$resultpm=mysql_query($sqlpm);

$sqlcu="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='1' WHERE id_ref_cv=$v AND b_borrado_cv='0'";
$resultcu=mysql_query($sqlcu);
$id=1;
header("location:produccion_referencias.php?id=$id");
//}
}
}
else
{
$id=0;
header("location:produccion_referencias.php?id=$id");
}
}
//ACTIVAR REFERENCIAS
if($_GET['activar3'] == 'activar3')
{
if(count($_GET['activa'])) {
foreach ($_GET['activa'] as $v) {
$sqlc="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv = $v AND b_borrado_cv='1'";
$resultc= mysql_query($sqlc);
$numc= mysql_num_rows($resultc);
/*if($numc >='1')
{
$id_pm_cv=mysql_result($resultc,0,'id_pm_cv');*/

$sqlpm="UPDATE Tbl_produccion_mezclas SET b_borrado_pm='0' WHERE id_ref_pm=$v AND b_borrado_pm='1'";
$resultpm=mysql_query($sqlpm);

$sqlcu="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='0' WHERE id_ref_cv=$v AND b_borrado_cv='1'";
$resultcu=mysql_query($sqlcu);
$id=2;
header("location:produccion_referencias.php?id=$id");
//}
}
}
else
{
$id=3;
header("location:produccion_referencias.php?id=$id");
}
}
//UPDATE AL ESTADO DESACTIVADO DE LAS OP DESDE SELLADO
$usuario=$_GET['usuario'];
if($_GET['borrado'] == '34')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
//$sqln="UPDATE tbl_numeracion SET b_borrado_n='1' WHERE int_op_n=$v AND b_borrado_n='0'";
//$resultn=mysql_query($sqln);
$fecha = date('Y-m-d');
$sqlop="UPDATE tbl_orden_produccion SET str_responsable_op='$usuario', fecha_registro_op = '$fecha' WHERE id_op=$v ";
$resultop=mysql_query($sqlop);

$sqldel="DELETE FROM tbl_numeracion WHERE int_op_n=$v ";
$resultdel=mysql_query($sqldel);

$sqldel2 = "DELETE FROM tbl_tiquete_numeracion WHERE int_op_tn=$v ";
$resultdel2 = mysql_query($sqldel2);


$id=1;
header("location:sellado_numeracion_listado.php?id=$id");
}
}
else
{
$id=0;
header("location:sellado_numeracion_listado.php?id=$id");
}
}
//ACTIVAR UPDATE AL ESTADO  ACTIVADO DE LAS OP DESDE LISTADO O.P INACTIVO
$usuario=$_GET['usuario'];
if($_GET['borrado'] == '35')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlop="UPDATE Tbl_orden_produccion SET str_responsable_op='$usuario', b_borrado_op='0' WHERE id_op=$v AND b_borrado_op='1'";
$resultop=mysql_query($sqlop);
$sqln="UPDATE Tbl_numeracion SET b_borrado_n='0' WHERE int_op_n=$v AND b_borrado_n='1'";
$resultn=mysql_query($sqln);
$id=1;
header("location:produccion_ordenes_produccion_listado.php?id=$id");
}
}
else
{
$id=0;
header("location:produccion_ordenes_produccion_listado.php?id=$id");
}
}
/*-------------------LISTADOS EXTRUSION---------------------------*/
/*-------------------ELIMINACION COMPLETA REGISTRO EXTRUSION------*/
if($_GET['borrado'] == 'eliminar_ext')
{
if(count($_GET['borrar'])) {
	foreach ($_GET['borrar'] as $v) {
$sqlv="SELECT * FROM Tbl_reg_produccion WHERE id_rp=$v AND id_proceso_rp=1";
$resultv= mysql_query($sqlv);
$numv= mysql_num_rows($resultv);
if($numv >='1')
{
$fecha_ini_rp=mysql_result($resultv,0,'fecha_ini_rp');
$id_op_rp=mysql_result($resultv,0,'id_op_rp');
$id_proceso_rp=mysql_result($resultv,0,'id_proceso_rp');
/*$sqlop="UPDATE Tbl_orden_produccion SET b_estado_op='0' WHERE id_op=$id_op_rp AND b_estado_op='1'";
$resultop=mysql_query($sqlop);	
$sqlkilo="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp=$id_op_rp AND id_proceso_rkp=id_proceso_rp";
$resultkilo=mysql_query($sqlkilo);
$sqldesp="DELETE FROM Tbl_reg_desperdicio WHERE op_rd=$id_op_rp AND id_proceso_rd=id_proceso_rp";
$resultdesp=mysql_query($sqldespe);
$sqltiem="DELETE FROM Tbl_reg_tiempo WHERE op_rt=$id_op_rp AND id_proceso_rt=id_proceso_rp";
$resulttiem=mysql_query($sqltiem);
$sqltpre="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=$id_op_rp AND id_proceso_rtp=id_proceso_rp";
$resultpre=mysql_query($sqlpre);*/
$sqldel="DELETE FROM Tbl_reg_produccion WHERE id_rp=$v";
$resultdel=mysql_query($sqldel);
$id=3;
header("location:produccion_registro_extrusion_listado_add.php?id=$id");
}
}
}
}
/* ACTIVA E INACTIVAR  REGISTROS DE EXTRUSION----------------*/
/*if($_GET['borrado'] == '36')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlrp="DELETE FROM TblExtruderRollo WHERE id_r=$v";
$resultrp=mysql_query($sqlrp);
$id=1;
header("location:produccion_registro_extrusion_listado.php?id=$id");
}
}
}*/
//EXTRUDER LISTADOS BORRA SOLAMENTE LIQUIDACION
if($_GET['borrado'] == '36')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlext="SELECT id_op_rp,fecha_ini_rp FROM Tbl_reg_produccion WHERE id_rp='$v'";
$resultext= mysql_query($sqlext);
$numext= mysql_num_rows($resultext); 
if($numext >='1')
{
$id_op = mysql_result($resultext,0, 'id_op_rp');
$fecha_ini= mysql_result($resultext,0, 'fecha_ini_rp');
}
	
	
$sqlrp="DELETE FROM Tbl_reg_produccion WHERE id_rp=$v AND id_proceso_rp='1'";
$resultrp=mysql_query($sqlrp);

$sqldesp="DELETE FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND fecha_rd = '$fecha_ini' AND id_proceso_rd='1'";
$resultdesp=mysql_query($sqldespe);

$sqltiem="DELETE FROM Tbl_reg_tiempo WHERE op_rt=$id_op AND fecha_rt = '$fecha_ini' AND id_proceso_rt='1'";
$resulttiem=mysql_query($sqltiem);

/*$sqlkilo="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp=$id_op_rp AND id_proceso_rkp='1'";
$resultkilo=mysql_query($sqlkilo);
$sqltpre="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=$id_op_rp AND id_proceso_rtp='1'";
$resultpre=mysql_query($sqlpre);*/
$id=1;
header("location:produccion_registro_extrusion_listado_add.php?id=$id");
}
}
else
{
if(count($_GET['activar'])) {
foreach ($_GET['activar'] as $v) {
$sqlrp="UPDATE Tbl_reg_produccion SET b_borrado_rp='0' WHERE id_rp=$v AND b_borrado_rp='1'";
$resultrp=mysql_query($sqlrp);
$id=2;

header("location:produccion_registro_extrusion_listado_add.php?id=$id");
}
}else{
	
$id=0;
header("location:produccion_registro_extrusion_listado_add.php?id=$id");
}
}
}
/*-------------------LISTADOS IMPRESION---------------------------*/
/*-------------------ELIMINACION COMPLETA REGISTRO IMPRESION------*/
if($_GET['borrado'] == 'eliminar_imp')
{
if(count($_GET['borrar'])) {
	foreach ($_GET['borrar'] as $v) {
$sqlv="SELECT * FROM Tbl_reg_produccion WHERE id_rp=$v AND id_proceso_rp=2";
$resultv= mysql_query($sqlv);
$numv= mysql_num_rows($resultv);
if($numv >='1')
{
$fecha_ini_rp=mysql_result($resultv,0,'fecha_ini_rp');
$id_op_rp=mysql_result($resultv,0,'id_op_rp');
$id_proceso_rp=mysql_result($resultv,0,'id_proceso_rp');	
/*$sqlkilo="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp=$id_op_rp AND id_proceso_rkp=id_proceso_rp";
$resultkilo=mysql_query($sqlkilo);
$sqldesp="DELETE FROM Tbl_reg_desperdicio WHERE op_rd=$id_op_rp AND id_proceso_rd=id_proceso_rp";
$resultdesp=mysql_query($sqldespe);
$sqltiem="DELETE FROM Tbl_reg_tiempo WHERE op_rt=$id_op_rp AND id_proceso_rt=id_proceso_rp";
$resulttiem=mysql_query($sqltiem);
$sqltpre="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=$id_op_rp AND id_proceso_rtp=id_proceso_rp";
$resultpre=mysql_query($sqlpre);*/
$sqldel="DELETE FROM Tbl_reg_produccion WHERE id_rp=$v";
$resultdel=mysql_query($sqldel);
$id=3;
header("location:produccion_registro_impresion_listado.php?id=$id");
}
}
}
}
//IMPRESION LISTADOS ELIMINA LIQUIDACION
if($_GET['borrado'] == '37')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlrp="DELETE FROM Tbl_reg_produccion WHERE id_rp=$v";
$resultrp=mysql_query($sqlrp);
$id=1;
header("location:produccion_registro_impresion_listado.php?id=$id");
}
}
}
//ELIMINA CONSUMO UNIDADES DE IMPRESION Y EXTRUSION
if($_GET['consumo_i'] == '1'){
if(count($_GET['id'])) {
foreach ($_GET['id'] as $v) {

$sqlre="SELECT id_rpp_rp, valor_prod_rp FROM  Tbl_reg_kilo_producido WHERE id_rkp = $v";
$resultre= mysql_query($sqlre);
$numere= mysql_num_rows($resultre);
if($numere >='1') {
	$id_insumo=mysql_result($resultre,0,'id_rpp_rp');
	$cantidad=mysql_result($resultre,0,'valor_prod_rp'); 
}
 
$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$cantidad' WHERE Codigo = $id_insumo";
$resultinv=mysql_query($sqlinv, $conexion1); 
 
$sql = "DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp = $v";
$resultado = mysql_query ($sql, $conexion1);
 
echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
echo "<script type=\"text/javascript\">window.close();</script>"; }}
//else { $id=0; header("location:revision.php?id=$id"); }
}
/*---------ELIMINA EL MAESTRO DE LOS TIQUETES EN SELLADO---------*/
if($_GET['borrado'] == '38'){
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sql = "DELETE FROM Tbl_numeracion WHERE int_op_n = '$v'";
$resultado = mysql_query ($sql, $conexion1);
$id=1;
header("location:numeracion_listado.php?id=$id");
 }
 }
else { $id=0; header("location:numeracion_listado.php?id=$id"); 
}
}
/*-------------------LISTADOS SELLADO---------------------------*/
/* ACTIVA E INACTIVAR  REGISTROS DE SELLADO----------------*/
/*if($_GET['borrado'] == '39')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlrp="UPDATE Tbl_reg_produccion SET b_borrado_rp='1' WHERE id_rp=$v AND b_borrado_rp='0'";
$resultrp=mysql_query($sqlrp);
$id=1;
header("location:produccion_registro_sellado_listado.php?id=$id");
}
}
else
{
if(count($_GET['activar'])) {
foreach ($_GET['activar'] as $v) {
$sqlrp="UPDATE Tbl_reg_produccion SET b_borrado_rp='0' WHERE id_rp=$v AND b_borrado_rp='1'";
$resultrp=mysql_query($sqlrp);
$id=2;

header("location:produccion_registro_sellado_listado.php?id=$id");
}
}else{
	
$id=0;
header("location:produccion_registro_sellado_listado.php?id=$id");
}
}
}*/
/*if($_GET['borrado'] == '39')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlrp="DELETE FROM TblSelladoRollo WHERE id_r=$v";
$resultrp=mysql_query($sqlrp);
$id=1;
header("location:produccion_registro_sellado_listado.php?id=$id");
}
}
}*/
/*-------------------ELIMINAR REF CLIENTE----------------*/
/*BORRADO DE REF AC REF CLIENTE*/
if($_GET['borrado']=='40') { 
if(count($_GET['refcliente'])) {
foreach ($_GET['refcliente'] as $v) {
$sqlv="SELECT * FROM Tbl_refcliente WHERE id_refcliente = $v";
$resultv= mysql_query($sqlv);
$numv= mysql_num_rows($resultv);
if($numv >='1'){	
$estado_rc = mysql_result($resultv, 0, 'int_estado_ref_rc');	
//$sqle="UPDATE Tbl_refcliente SET int_estado_ref_rc='0' WHERE int_estado_ref_rc='$estado_rc' AND id_refcliente='$v'";
$sqle="DELETE FROM Tbl_refcliente WHERE id_refcliente='$v'";
$resultado= mysql_query($sqle,$conexion1);
$id=1;
header("location:ref_ac_ref_cl_listado.php?id=1"); }else {
$id=0; 
header("location:ref_ac_ref_cl_listado.php?id=$id"); 
}
}
}
}

/*ELIMINAR LISTADO DE ENTRADAS INVENTARIO*/
if($_GET['borrado'] == '42')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlemp="DELETE FROM Tbl_inventario WHERE id_inv='$v'";
$resultemp=mysql_query($sqlemp);
$id=1;header("location:costos_inventario_entrada_listado.php?id=$id"); } 
}else { $id=0; header("location:costos_inventario_entrada_listado.php?id=$id"); } 
}
/*ELIMINAR LISTADO DE AJUSTE PROCESOS*/
if($_GET['borrado'] == '43')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlaj="DELETE FROM TblProcesoAjuste WHERE id_pa='$v'";
$resultaj=mysql_query($sqlaj);
$id=1;header("location:proceso_ajuste_listado.php?id=$id"); } }
else { $id=0; header("location:proceso_ajuste_listado.php?id=$id"); } 
}
/*ELIMINAR EXPORTACIONES*/
if($_GET['borrado'] == '44')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
	$sqlv="SELECT n_ce FROM TblCostoExportacion WHERE n_ce='$v'";
$resultv= mysql_query($sqlv);
$numv= mysql_num_rows($resultv);
if($numv >='1')
{
$n_ce=mysql_result($resultv,0,'n_ce');
$sqlce="DELETE FROM TblCostoExportacion WHERE n_ce='$n_ce'";
$resultce=mysql_query($sqlce);
/*$sqldet="DELETE FROM TblCostoExportacionDetalle WHERE n_ce_det='$n_ce'";
$resultdet=mysql_query($sqldet);*/
$id=1;
header("location:costo_exportacion_listado.php?id=$id");
}
}
}
else
{
$id=0;
header("location:costo_exportacion_listado.php?id=$id");
}
}
/*ELIMINAR INGRESO DE MP Y A INVENTARIO*/
if($_GET['borrado'] == '45')
{
$iddet=$_GET['iddet'];	
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
	if($v >='1'){
$sqlid="SELECT * FROM TblIngresos WHERE id_ing = $v";
$resultid= mysql_query($sqlid);
$numid= mysql_num_rows($resultid);
$id_det_ing = mysql_result($resultid, 0, 'id_det_ing');	
$id_insumo_ing = mysql_result($resultid, 0, 'id_insumo_ing');
$cantidad_ing = mysql_result($resultid, 0, 'ingreso_ing');	

$sqlinv="UPDATE TblInventarioListado SET Entrada = Entrada - '$cantidad_ing' WHERE Codigo = '$id_insumo_ing'";
$resultinv=mysql_query($sqlinv);
$sqling="DELETE FROM TblIngresos WHERE id_ing = $v";
$resulting=mysql_query($sqling);
  $id=1;
header("location:orden_compra_add_ingreso.php?id=$id&id_det=$id_det_ing");
}
}
}else
{
$id=0;
header("location:orden_compra_add_ingreso.php?id=$id&id_det=$iddet");
}
}
//ELIMINA COSTO DE REFERENCIA
if($_GET['borrado'] == '46')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlcosto="DELETE FROM TblCostoRef WHERE id_cref='$v'";
$resultcosto=mysql_query($sqlcosto);
$id=1;
header("location:costo_referencia_listado.php?id=$id");
}
}
else
{
$id=0;
header("location:costo_referencia_listado.php?id=$id");
}
}
/*-------------------ELIMINACION COMPLETA REGISTRO REFILADO------*/
if($_GET['borrado'] == 'eliminar_refil')
{
if(count($_GET['borrar'])) {
	foreach ($_GET['borrar'] as $v) {
$sqlv="SELECT * FROM Tbl_reg_produccion WHERE id_rp=$v AND id_proceso_rp=3";
$resultv= mysql_query($sqlv);
$numv= mysql_num_rows($resultv);
if($numv >='1')
{
$fecha_ini_rp=mysql_result($resultv,0,'fecha_ini_rp');
$id_op_rp=mysql_result($resultv,0,'id_op_rp');
$id_proceso_rp=mysql_result($resultv,0,'id_proceso_rp');	
$sqlkilo="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp=$id_op_rp AND id_proceso_rkp=id_proceso_rp";
$resultkilo=mysql_query($sqlkilo);
$sqldesp="DELETE FROM Tbl_reg_desperdicio WHERE op_rd=$id_op_rp AND id_proceso_rd=id_proceso_rp";
$resultdesp=mysql_query($sqldespe);
$sqltiem="DELETE FROM Tbl_reg_tiempo WHERE op_rt=$id_op_rp AND id_proceso_rt=id_proceso_rp";
$resulttiem=mysql_query($sqltiem);
$sqltpre="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=$id_op_rp AND id_proceso_rtp=id_proceso_rp";
$resultpre=mysql_query($sqlpre);
$sqldel="DELETE FROM Tbl_reg_produccion WHERE id_rp=$v";
$resultdel=mysql_query($sqldel);
$id=3;
header("location:produccion_registro_impresion_listado.php?id=$id");
}
}
}
}
if($_GET['borrado'] == '47')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlrp="DELETE FROM TblRefiladoRollo WHERE id_r=$v";
$resultrp=mysql_query($sqlrp);
$id=1;
header("location:produccion_registro_refilado_listado.php?id=$id");
}
}
}
//INACTIVAR MEZCLAS IMPRESION
if($_GET['borrado'] == '48')
{
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
$sqlpm="UPDATE Tbl_produccion_mezclas_impresion SET b_borrado_pmi='1' WHERE id_ref_pmi=$v AND b_borrado_pmi='0'";
$resultpm=mysql_query($sqlpm);
$sqlc="UPDATE Tbl_caracteristicas_valor SET b_borrado_cv='1' WHERE id_ref_cv=$v AND b_borrado_cv='0'";//SE INACTIVA TODOS LOS PROCESOS DE ESTA REF
$resultc=mysql_query($sqlc);
$id=1;
header("location:produccion_mezclas_impresion.php?id=$id");
}
}
else
{
$id=0;
header("location:produccion_mezclas_impresion.php?id=$id");
}
}
/*ELIMINAR INGRESO INVENTARIO*/
if($_GET['borrado'] == '49')
{
$iddet=$_GET['iddet'];	
if(count($_GET['borrar'])) {
foreach ($_GET['borrar'] as $v) {
	if($v >='1'){
$sqlid="SELECT * FROM TblIngresos WHERE id_ing = $v";
$resultid= mysql_query($sqlid);
$numid= mysql_num_rows($resultid);
	
$id_det_ing = mysql_result($resultid, 0, 'id_det_ing');
$sqling="DELETE FROM TblIngresos WHERE id_ing = $v";
$resulting=mysql_query($sqling);
$id=1;
header("location:orden_compra_ingresos.php?id=$id");
}
}
}else
{
$id=0;
header("location:orden_compra_ingresos.php?id=$id");
}
}

//BORRADO DE REFERENCIAS DESDE ARCHIVO REFERENCIA_COPIA.PHP
if($_GET['borrado']=='50') { 
if(count($_GET['ref'])) {
foreach ($_GET['ref'] as $v) {
$sqlref1="SELECT * FROM Tbl_referencia WHERE id_ref = $v";
$resultref1= mysql_query($sqlref1);
$numref= mysql_num_rows($resultref1);
if($numref >='1'){
$n_egp=mysql_result($resultref1,0,'n_egp_ref');
$sqlegps="DELETE FROM Tbl_egp WHERE n_egp ='$n_egp'";
$resultegps=mysql_query($sqlegps);}
$sqlrevision="DELETE FROM revision WHERE id_ref_rev = '$v'";
$resultrevision=mysql_query($sqlrevision);
 
$sqlarte="SELECT * FROM verificacion WHERE id_verif = $v";
$resultarte= mysql_query($sqlarte);
$numarte= mysql_num_rows($resultarte);
if($numarte >='1'){ 
   $arte = mysql_result($resultarte, 0, 'userfile'); 
   if($arte!=''){   
      if(file_exists("archivo/".$arte)){ unlink("archivo/".$arte); }
   }
}
/*--------------*/
$sqlverif="DELETE FROM verificacion WHERE id_ref_verif = $v";
$resultverif=mysql_query($sqlverif);
$sqlcm="DELETE FROM control_modificaciones WHERE id_ref_cm = $v";
$resultcm=mysql_query($sqlcm);
$sqlval="DELETE FROM validacion WHERE id_ref_val = $v";
$resultval=mysql_query($sqlval);
$sqlft="DELETE FROM ficha_tecnica WHERE id_ref_ft = $v";
$resultft=mysql_query($sqlft);
$sql="DELETE FROM Tbl_referencia WHERE id_ref='$v'";
$resultado = mysql_query ($sql, $conexion1);

$id=1;
header("location:referencia_copia.php?id=1"); }}
else {
$id=0; header("location:referencia_copia.php?id=$id"); }
}
?>