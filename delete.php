<?php require_once('Connections/conexion1.php'); ?>
<?php
if (!isset($_SESSION)) {
	session_start();
}

mysql_select_db($database_conexion1, $conexion1);
/*----------VARIABLES------------*/

/*----------ADMINISTRADOR--------*/
$menu=$_GET['menu'];
$id_submenu=$_GET['id_submenu'];
$id_menu=$_GET['id_menu'];//se utiliza para devolver la variable y que se abran de nuevo por get en el header
$id_sub_menu=$_GET['id_sub_menu'];//se utiliza para devolver la variable y que se abran de nuevo por get en el header
$id_sub_submenu=$_GET['id_sub_submenu'];
$id_registro=$_GET['id_registro'];
$tipo=$_GET['tipo'];
$id_usuario=$_GET['id_usuario'];
$id_clase=$_GET['id_clase'];
$id_medida=$_GET['id_medida'];
$id_tipo=$_GET['id_tipo'];
$id_tipo_proceso=$_GET['id_tipo_proceso'];
//gestion de tipos desperdicios
$id_rtp=$_GET['id_rtp'];
$id_rtp_in=$_GET['id_rtp_in'];
$id_rtp_ac=$_GET['id_rtp_ac'];
/*-------GESTION COMERCIAL---------*/
$id_c=$_GET['id_c'];
$n_egp=$_GET['n_egp'];
$egparchivo=$_GET['egparchivo'];
$archivo=$_GET['archivo'];
$id_anual=$_GET['id_anual'];
$id_vendedor=$_GET['id_vendedor'];
$n_cn=$_GET['n_cn'];
$n_cotiz=$_GET['n_cotiz'];
$n_ce=$_GET['n_ce'];
$id_detalle=$_GET['id_detalle'];
/*------LISTADO COTIZACIONES------*/
$id_cotiz=$_GET['id_cotiz'];
$id_cotiz_up=$_GET['id_cotiz_up'];
/*egl*/
$egl=$_GET['egl'];
$n_egl=$_GET['n_egl'];
$id_color=$_GET['id_color'];
$id_archivo=$_GET['id_archivo'];
/*----GESTION DISEÑO Y DESARROLLO--BOLSAS--*/
$id_refcliente=$_GET['id_refcliente'];
$id_ref=$_GET['id_ref'];
$id_rev=$_GET['id_rev'];
$id_verif=$_GET['id_verif'];
//$id_ref_verif=$_GET['id_ref_verif'];
$id_cm=$_GET['id_cm'];
$id_ref_cm=$_GET['id_ref_cm'];
$id_val=$_GET['id_val'];
$n_ft=$_GET['n_ft'];
/*----GESTION DISEÑO Y DESARROLLO LAMINAS-*/
$id_rev_l=$_GET['id_rev_l'];
$id_verif_l=$_GET['id_verif_l'];
$id_val_l=$_GET['id_val_l'];
$id_val_p=$_GET['id_val_p'];
$id_rev_p=$_GET['id_rev_p'];
$id_verif_p=$_GET['id_verif_p'];
/*-------GESTION DE COMPRAS---------*/
$id_p=$_GET['id_p'];
$id_seleccion=$_GET['id_seleccion'];
$id_pm=$_GET['id_pm'];
$id_insumo=$_GET['id_insumo'];
$id_anilox=$_GET['id_anilox'];
$n_oc=$_GET['n_oc'];
$id_det=$_GET['id_det'];
$n_vi=$_GET['n_vi'];
$id_rollo=$_GET['id_rollo'];
$n_ocr=$_GET['n_ocr'];
$n_vr=$_GET['n_vr'];
$id_bolsa=$_GET['id_bolsa'];
$n_ocb=$_GET['n_ocb'];
$n_vb=$_GET['n_vb'];
$id_ev=$_GET['id_ev'];
$id_eva=$_GET['id_eva'];
/*-------COSTOS EXPORTACION---------*/
$id_det_ce=$_GET['id_det_ce'];
$id_n_ce=$_GET['id_n_ce'];
/*-------ROLLOS PRODUCCION---------*/
$id_re=$_GET['id_re'];
$id_rel=$_GET['id_rel'];
$id_ri=$_GET['id_ri'];
$id_rliq=$_GET['id_rliq'];//impresion
$id_rr=$_GET['id_rr'];
$id_op_bo=$_GET['id_op'];//desde edit o.p
$id_rliqs=$_GET['id_rliqs'];//sellado
$id_rolloparcial=$_GET['id_rolloparcial'];//sellado parcial
/*----------IMPRESION-------------*/
 //DESDE ROLLO
$id_reR=$_GET['id_r']; 
$id_rtei=$_GET['id_rtei'];
$id_rpei=$_GET['id_rpei'];
$id_rdei=$_GET['id_rdei'];

/*----------SELLADO-------------*/
$id_reRS=$_GET['id_rs']; //VARIABLE DE ROLLO PARA EDIT
$id_rts=$_GET['id_rts'];//ELIMINAR EN DELETE2
$id_rps=$_GET['id_rps'];//ELIMINAR EN DELETE2
$id_rds=$_GET['id_rds'];//ELIMINAR EN DELETE2 
$id_regpro=$_GET['id_regpro'];

/*----------SELLADO PARCIAL-------*/
$id_reRSp=$_GET['id_rsp']; //VARIABLE DE ROLLO PARA EDIT
$id_rtsp=$_GET['id_rtsp'];//ELIMINAR EN DELETE2
$id_rpsp=$_GET['id_rpsp'];//ELIMINAR EN DELETE2
$id_rdsp=$_GET['id_rdsp'];//ELIMINAR EN DELETE2 
/*-------GESTION DE ORDEN DE COMPRA O PEDIDO---------*/
$id_pedido_oc=$_GET['id_pedido'];
$id_items=$_GET['id_items'];
$id_itemsadd=$_GET['id_items'];
/*-------GESTION DE REMISIONES---------*/
$id_rd=$_GET['id_rd'];
$int_remision=$_GET['int_remision'];
/*---------GESTION DE PRODUCCION----------*/
$id_empleado_tipo=$_GET['id_empleado_tipo'];
$id_empleado=$_GET['id_empleado'];
$id_empleado_turno=$_GET['id_empleado_turno'];
$id_maquina=$_GET['id_maquina'];
$id_consumo_material=$_GET['id_consumo_material'];
$rollback=$_GET['rollback'];

/*---------GESTION DE COSTOS----------*/
$id_genera=$_GET['id_genera'];
$id_genera_gv=$_GET['id_genera_gv'];
$id_pem=$_GET['id_pem'];
$id_nov=$_GET['id_nov'];
/*---------GESTION DE GGA Y CIF----------*/
$id_gga_fecha=$_GET['id_gga_fecha'];
$estado_gga=$_GET['estado_gga'];
/*---------GESTION REF AC REF CLIENTE------------*/
$id_refac_refcliente=$_GET['id_refac_refcliente'];
/*---------EMPLEADOS ------------*/
$id_aporte=$_GET['id_aporte'];
/*---------PROVEEDORES ------------*/
$id_pi=$_GET['id_pi'];


/*----------------------------------------*/
/*--------------ACCIONES------------------*/
/*----------------------------------------*/
/*------------ADMINISTRADOR---------------*/
/*DELETE MENU*/
if($menu!='') { 
	$sqlsubmenues="DELETE FROM submenu WHERE id_menu_submenu='$menu'";
	$resultsubmenues=mysql_query($sqlsubmenues);
	$sqlsubsubmenues="DELETE FROM Tbl_submenu_submenu WHERE id_menu_submenu='$menu'";
	$resultsubsubmenues=mysql_query($sqlsubsubmenues);
	$sqlmenu="DELETE FROM menu WHERE id_menu='$menu'";
	$resultmenu=mysql_query($sqlmenu);
	header("location:menu1.php"); }
	/*DELETE SUBMENU*/
	if($id_submenu!='') {
		$sqlsubmenu="DELETE FROM submenu WHERE id_submenu='$id_submenu'";
		$resultsubmenu=mysql_query($sqlsubmenu);
		$sqlsub_submenu="DELETE FROM Tbl_submenu_submenu WHERE id_sub_menu='$id_submenu'";
		$resultsub_submenu=mysql_query($sqlsub_submenu);
		header("location:menu_nuevo2.php?id_menu=$id_menu"); }
		/*DELETE SUB-SUBMENU*/
		if($id_sub_submenu!='') {
			$id_sub_submenu= explode(".",$id_sub_submenu);
$id_sub_submenu['0'];//divide las variables de menu_nuevo3 en dos para delete y devolver id_submenu
$id_sub_submenu['1'];	
$sqlsubmenu_sub="DELETE FROM Tbl_submenu_submenu WHERE id_submenu='$id_sub_submenu[0]'";
$resultsubmenu_sub=mysql_query($sqlsubmenu_sub);
header("location:menu_nuevo3.php?id_menu=$id_menu&id_submenu=$id_sub_submenu[1]"); }
/*DELETE TIPO USER*/
if($id_tipo!='') {
	$sqltipo="DELETE FROM tipo_user WHERE id_tipo='$id_tipo'";
	$resultipo=mysql_query($sqltipo);
	$sqlpermiso="DELETE FROM permisos WHERE usuario='$id_tipo'";
	$resultpermiso=mysql_query($sqlpermiso);
	header('location:tipos_usuario.php'); }
	/*DELETE PERMISO DE ACCESO*/
	if($id_registro!='') {
		$sqldato="SELECT * FROM permisos WHERE id_registro='$id_registro'";
		$resultdato=mysql_query($sqldato);
		$usuario=mysql_result($resultdato,0,'usuario');
		$menu=mysql_result($resultdato,0,'menu');
		$sqlpermiso="DELETE FROM permisos WHERE id_registro='$id_registro'";
		$resultpermiso=mysql_query($sqlpermiso);
		header("location:tipo_permisos.php?id_tipo=$usuario&id_menu=$menu");}
		/*DELETE USUARIO*/
		if($id_usuario!='') {
			$sqluser="DELETE FROM usuario WHERE id_usuario='$id_usuario'";
			$resultuser=mysql_query($sqluser);

			$sqlacc="DELETE FROM accesos WHERE usuario_id='$id_usuario'";
			$resultacc=mysql_query($sqlacc);

			header('location:usuarios.php'); }
			/*DELETE DATOS ANUALES*/
			if($id_anual!='') {
				$sqlanual="DELETE FROM anual WHERE id_anual='$id_anual'";
				$resultanual=mysql_query($sqlanual);
				header('location:ano.php'); }
				/*DELETE VENDEDOR*/
				if($id_vendedor!='') {
					$sqlvendedor="DELETE FROM vendedor WHERE id_vendedor='$id_vendedor'";
					$resultvendedor=mysql_query($sqlvendedor);
					header('location:vendedores.php'); }
					/*DELETE CLASE*/
					if($id_clase!='')
					{
						$sqlclase="DELETE FROM clase WHERE id_clase='$id_clase'";
						$resultclase=mysql_query($sqlclase);
						header("location:clase.php");
					}
					/*DELETE MEDIDA*/
					if($id_medida!='')
					{
						$sqlmedida="DELETE FROM medida WHERE id_medida='$id_medida'";
						$resultmedida=mysql_query($sqlmedida);
						header("location:medida.php");
					}
					/*DELETE TIPO*/
					if($id_tipo!='')
					{
						$sqltipo="DELETE FROM tipo WHERE id_tipo='$id_tipo'";
						$resulttipo=mysql_query($sqltipo);
						header("location:tipo.php");
					}
					/*DELETE TIPO*/
					if($id_tipo_proceso!='')
					{
						$sqlproceso="DELETE FROM tipo_procesos WHERE id_tipo_proceso='$id_tipo_proceso'";
						$resultproceso=mysql_query($sqlproceso);
						header("location:tipos_procesos.php");
					}

					if($id_rtp!='')
					{
						$sqldato="SELECT id_proceso_rtd,codigo_rtp FROM tbl_reg_tipo_desperdicio WHERE id_rtp = '$id_rtp'";
						$resultdato=mysql_query($sqldato);
						$numce1= mysql_num_rows($resultdato);
						if($numce1!='') { 
							$proceso=mysql_result($resultdato,0,'id_proceso_rtd');
							$tipo=mysql_result($resultdato,0,'codigo_rtp');
						}

						$sqlproceso="DELETE FROM tbl_reg_tipo_desperdicio WHERE id_rtp = '$id_rtp'";
						$resultproceso=mysql_query($sqlproceso);
						header("location:tipos_desperdicio_tiempos.php?tipo=$tipo&proceso=$proceso");
					}
//inactivar tipo
					if($id_rtp_in!='')
					{
						$sqldato="SELECT id_proceso_rtd,codigo_rtp FROM tbl_reg_tipo_desperdicio WHERE id_rtp = '$id_rtp_in'";
						$resultdato=mysql_query($sqldato);
						$numce1= mysql_num_rows($resultdato);
						if($numce1!='') { 
							$proceso=mysql_result($resultdato,0,'id_proceso_rtd');
							$tipo=mysql_result($resultdato,0,'codigo_rtp');
						}

						$sqlproceso="UPDATE tbl_reg_tipo_desperdicio SET estado_rtp='1' WHERE id_rtp='$id_rtp_in'";
						$resultproceso=mysql_query($sqlproceso);
						header("location:tipos_desperdicio_tiempos.php?tipo=$tipo&proceso=$proceso");
					}
//activar tipo
					if($id_rtp_ac!='')
					{
						$sqldato="SELECT id_proceso_rtd,codigo_rtp FROM tbl_reg_tipo_desperdicio WHERE id_rtp = '$id_rtp_ac'";
						$resultdato=mysql_query($sqldato);
						$numce1= mysql_num_rows($resultdato);
						if($numce1!='') { 
							$proceso=mysql_result($resultdato,0,'id_proceso_rtd');
							$tipo=mysql_result($resultdato,0,'codigo_rtp');
						}

						$sqlproceso="UPDATE tbl_reg_tipo_desperdicio SET estado_rtp='0' WHERE id_rtp='$id_rtp_ac'";
						$resultproceso=mysql_query($sqlproceso);
						header("location:tipos_desperdicio_tiempos.php?tipo=$tipo&proceso=$proceso");
					}
					/*------------------------------------------------------------------*/
					/*--------------------------GESTION COMERCIAL-----------------------*/
					/*DELETE CLIENTE*/
					if($id_c!=''){
$sqladj="SELECT * FROM cliente WHERE id_c='$id_c'";//desde aqui elimino los archivos en el servidor
$resuladj=mysql_query($sqladj);
$nombre1=mysql_result($resuladj,0,'camara_comercio_c');
if($nombre1!='') { unlink("archivosc/".$nombre1);}
$nombre2=mysql_result($resuladj,0,'referencias_bancarias_c');
if($nombre2!='') { unlink("archivosc/".$nombre2);}
$nombre3=mysql_result($resuladj,0,'referencias_comerciales_c');
if($nombre3!='') { unlink("archivosc/".$nombre3);}
$nombre4=mysql_result($resuladj,0,'estado_pyg_c');
if($nombre4!='') { unlink("archivosc/".$nombre4);}
$nombre5=mysql_result($resuladj,0,'balance_general_c');
if($nombre5!='') { unlink("archivosc/".$nombre5);}
$nombre6=mysql_result($resuladj,0,'flujo_caja_proy_c');
if($nombre6!='') { unlink("archivosc/".$nombre6);}
$nombre7=mysql_result($resuladj,0,'fotocopia_declar_iva_c');
if($nombre7!='') { unlink("archivosc/".$nombre7);}
$nombre8=mysql_result($resuladj,0,'otros_doc_c');
if($nombre8!='') { unlink("archivosc/".$nombre8);}//termina la eliminacion de los archivos en el servidor
$estado_c="INACTIVO";	
 $sqlcliente="UPDATE cliente SET estado_c='$estado_c'  WHERE id_c='$id_c'";//25/11/2020
//$sqlcliente="DELETE FROM cliente WHERE id_c = '$id_c'";
 $resultcliente=mysql_query($sqlcliente);
 header('location:listado_clientes.php'); 

} 
/*DELETE BODEGA DESTINATARIOS*/
/*if($id_c!=''){
$sqlclienteD="DELETE FROM Tbl_Destinatarios WHERE id_d='$id_c'";
$resultclienteD=mysql_query($sqlclienteD);*/
//header('location:perfil_cliente_add.php');//}
/*DELETE EGP - BOLSA DE SEGURIDAD*/
if($n_egp!='') {
	$sqldato="SELECT * FROM egp WHERE n_egp='$n_egp'";
	$resultdato=mysql_query($sqldato);
	$nombre1=mysql_result($resultdato,0,'archivo1');
	if($nombre1!='') { unlink("egpbolsa/".$nombre1); }
	$nombre2=mysql_result($resultdato,0,'archivo2');
	if($nombre2!='') { unlink("egpbolsa/".$nombre2); }
	$nombre3=mysql_result($resultdato,0,'archivo3');
	if($nombre3!='') { unlink("egpbolsa/".$nombre3); }
	$sqlegp="DELETE FROM egp WHERE n_egp='$n_egp'";
	$resultegp=mysql_query($sqlegp);
	header('location:egp_bolsa.php'); }
	/*DELETE-AD-ARCHIVO ADJUNTO-BOLSA DE SEGURIDAD*/ 
	if($archivo=='archivo1') {
		$sqldato="SELECT archivo1 FROM egp WHERE n_egp='$egparchivo'";
		$resultdato=mysql_query($sqldato);
		$nombre=mysql_result($resultdato,0,'archivo1');
		$sqlarchivo="UPDATE egp SET archivo1='' WHERE n_egp='$egparchivo'";
		$resultarchivo=mysql_query($sqlarchivo);
		unlink("egpbolsa/".$nombre);
		header("location:egp_bolsa_edit.php?n_egp=$egparchivo"); }
		if($archivo=='archivo2') {
			$sqldato="SELECT archivo2 FROM egp WHERE n_egp='$egparchivo'";
			$resultdato=mysql_query($sqldato);
			$nombre=mysql_result($resultdato,0,'archivo2');
			$sqlarchivo="UPDATE egp SET archivo2='' WHERE n_egp='$egparchivo'";
			$resultarchivo=mysql_query($sqlarchivo);
			unlink("egpbolsa/".$nombre);
			header("location:egp_bolsa_edit.php?n_egp=$egparchivo"); }
			if($archivo=='archivo3') {
				$sqldato="SELECT archivo3 FROM egp WHERE n_egp='$egparchivo'";
				$resultdato=mysql_query($sqldato);
				$nombre=mysql_result($resultdato,0,'archivo3');
				$sqlarchivo="UPDATE egp SET archivo3='' WHERE n_egp='$egparchivo'";
				$resultarchivo=mysql_query($sqlarchivo);
				unlink("egpbolsa/".$nombre);
				header("location:egp_bolsa_edit.php?n_egp=$egparchivo"); }
				/*DELETE COTIZACION NUEVA*/
				if($n_cn!='') {
					$sqlcn1="SELECT * FROM cotizacion, egp, cotizacion_nueva WHERE cotizacion_nueva.n_cn = '$n_cn' AND cotizacion_nueva.n_cotiz_cn = cotizacion.n_cotiz AND cotizacion_nueva.n_egp_cn = egp.n_egp";
					$resultcn1= mysql_query($sqlcn1);
					$numcn1= mysql_num_rows($resultcn1);
					if($numcn1 >='1') {
						$n_cotiz_cn=mysql_result($resultcn1,0,'n_cotiz');
						$id_c_cotiz=mysql_result($resultcn1,0,'id_c_cotiz');
						$n_egp=mysql_result($resultcn1,0,'n_egp'); }
						$sqlcn2="DELETE FROM cotizacion_nueva WHERE n_cn='$n_cn'";
						$resultcn2=mysql_query($sqlcn2);
						$sqlcn3="UPDATE egp SET estado_egp='0' WHERE n_egp='$n_egp'";
						$resultcn3=mysql_query($sqlcn3);
						header("location:cotizacion_bolsa_edit.php?n_cotiz=$n_cotiz_cn&id_c_cotiz=$id_c_cotiz"); }
						/*DELETE COTIZACION EXISTENTE*/
						if($n_ce!='') {
							$sqlce1="SELECT * FROM cotizacion, cotizacion_existente WHERE cotizacion_existente.n_ce = '$n_ce' AND cotizacion_existente.n_cotiz_ce = cotizacion.n_cotiz";
							$resultce1= mysql_query($sqlce1);
							$numce1= mysql_num_rows($resultce1);
							if($numce1 >='1') {
								$n_cotiz_ce=mysql_result($resultce1,0,'n_cotiz');
								$id_c_cotiz=mysql_result($resultce1,0,'id_c_cotiz'); }
								$sqlce2="DELETE FROM cotizacion_existente WHERE n_ce='$n_ce'";
								$resultce2=mysql_query($sqlce2);
								header("location:cotizacion_bolsa_edit.php?n_cotiz=$n_cotiz_ce&id_c_cotiz=$id_c_cotiz"); }
								/*DELETE COTIZACION*/
								if($n_cotiz!='') {
									$sqlcn="SELECT * FROM egp, cotizacion_nueva WHERE cotizacion_nueva.n_cotiz_cn = '$n_cotiz' AND cotizacion_nueva.n_egp_cn = egp.n_egp";
									$resultcn= mysql_query($sqlcn);
									$numcn= mysql_num_rows($resultcn);
									for($i=0; $i<$numcn; $i++) {
										$n_egp=mysql_result($resultcn,$i,'n_egp');
										$sqlegps="UPDATE egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
										$resultegps=mysql_query($sqlegps); }
										$sqlcotiz="DELETE FROM cotizacion WHERE n_cotiz='$n_cotiz'";
										$resultcotiz=mysql_query($sqlcotiz);
										$sqlcns="DELETE FROM cotizacion_nueva WHERE n_cotiz_cn='$n_cotiz'";
										$resultcns=mysql_query($sqlcns);
										$sqlces="DELETE FROM cotizacion_existente WHERE n_cotiz_ce='$n_cotiz'";
										$resultces=mysql_query($sqlces);
										header('location:cotizacion_bolsa.php'); }
										/*DELETE COTIZACION GENERAL*/
										if($id_cotiz!='') {
											$sqlcn="SELECT N_cotizacion FROM Tbl_cotizaciones WHERE id_cotiz = '$id_cotiz'";
											$resultcn= mysql_query($sqlcn);
											$numcn= mysql_num_rows($resultcn);
											if($numcn >='1') {
												$n_cotiz=mysql_result($resultcn,0,'N_cotizacion');
											}
     //ELIMINA LA COTIZ 
											$sqlce="DELETE FROM Tbl_cotizaciones WHERE id_cotiz='$id_cotiz'";
											$resultce=mysql_query($sqlce);
											$sqlce2="DELETE FROM Tbl_cotiza_bolsa WHERE N_cotizacion='$n_cotiz'";
											$resultce2=mysql_query($sqlce2);
											$sqlce3="DELETE FROM Tbl_cotiza_materia_p WHERE N_cotizacion='$n_cotiz'";
											$resultce3=mysql_query($sqlce3);
											$sqlce4="DELETE FROM Tbl_cotiza_packing WHERE N_cotizacion='$n_cotiz'";
											$resultce4=mysql_query($sqlce4);
											$sqlce5="DELETE FROM Tbl_cotiza_laminas WHERE N_cotizacion='$n_cotiz'";
											$resultce5=mysql_query($sqlce5);
											header('location:listado_cotizaciones.php');
										}
										/*UPDATE COTIZACIONES GENERAL*/

										if($id_cotiz_up!='') {
											$id=0; 
											$sqlcn="SELECT N_cotizacion FROM Tbl_cotizaciones WHERE id_cotiz = '$id_cotiz_up'";
											$resultcn= mysql_query($sqlcn);
											$numcn= mysql_num_rows($resultcn);
											if($numcn >='1') {
												$n_cotiz=mysql_result($resultcn,0,'N_cotizacion');
											}
											if($n_cotiz){
												$sqlbolsa="UPDATE Tbl_cotiza_bolsa SET B_estado='2' WHERE N_cotizacion='$n_cotiz'";
												$resultbolsa=mysql_query($sqlbolsa);
												$sqllamina="UPDATE Tbl_cotiza_laminas SET B_estado='2' WHERE N_cotizacion='$n_cotiz'";
												$resullamina=mysql_query($sqllamina);
												$sqlmateria="UPDATE Tbl_cotiza_materia_p SET B_estado='2' WHERE N_cotizacion='$n_cotiz'";
												$resulmateria=mysql_query($sqlmateria);
												$sqlpacking="UPDATE Tbl_cotiza_packing SET B_estado='2' WHERE N_cotizacion='$n_cotiz'";
												$resulpacking=mysql_query($sqlpacking);
												$id=1;	
											}
											header("location:cotizacion_copia.php?id=$id"); 
										} 



										/*DELETE PEDIDO*/

										/*DELETE DETALLE PEDIDO*/
										if($id_detalle!='')
										{
											$sql_select="SELECT * FROM pedido_detalle WHERE id_detalle='$id_detalle'";
											$result_select= mysql_query($sql_select);
											$num_select= mysql_num_rows($result_select);
											if($num_select>='1') { $id_pedido=mysql_result($result_select,0,'id_pedido'); }
											$sql_delete="DELETE FROM pedido_detalle WHERE id_detalle='$id_detalle'";
											$result_delete=mysql_query($sql_delete);
											header("location:pedido_bolsa_detalle.php?id_pedido=$id_pedido");
										}
										/*--------------------------------------*/
										/*DELETE EGL - LAMINA*/
										if($egl!='') {
											/*1.Eliminar los colores*/
											$sqlegl="DELETE FROM egl_colores WHERE n_egl='$egl'";
											$resultegl=mysql_query($sqlegl);
											/*2.Eliminar archivos adjuntos*/
											$sqldato1="SELECT * FROM egl_archivos WHERE n_egl='$egl'";
											$seleccion = mysql_query($sqldato1);
											while($row=mysql_fetch_array($seleccion, MYSQL_ASSOC))
												{ unlink("egplamina/".$row['archivo']); }
											$sqldato="DELETE FROM egl_archivos WHERE n_egl='$egl'";
											$resultdato=mysql_query($sqldato);
											/*3.Eliminar EGL*/
											$sqlegl="DELETE FROM egl WHERE n_egl='$egl'";
											$resultegl=mysql_query($sqlegl);
											header('location:egp_lamina.php'); 
										}
										/*DELETE COLOR EGL*/
										if($id_color != '' && $n_egl != '')
										{
											$sqlcolor="DELETE FROM egl_colores WHERE id_color='$id_color'";
											$resultcolor=mysql_query($sqlcolor);
											header("location:egp_lamina_colores.php?n_egl=$n_egl");
										}
										/*DELETE ARCHIVO EGL*/
										if($id_archivo != '' && $n_egl != '')
										{
											$sqldato="SELECT archivo FROM egl_archivos WHERE id_archivo='$id_archivo'";
											$resultdato=mysql_query($sqldato);
											$nombre=mysql_result($resultdato,0,'archivo');
											unlink("egplamina/".$nombre);
											$sqlarchivo="DELETE FROM egl_archivos WHERE id_archivo='$id_archivo'";
											$resultarchivo=mysql_query($sqlarchivo);
											header("location:egp_lamina_archivos.php?n_egl=$n_egl");
										}
										/*----------------------------------------------------------------------*/
										/*----------------------GESTION DE DISEÑO Y DESARROLLO------------------*/
										/*DELETE REFERENCIA-CLIENTE*/
										if($id_refcliente!='') {
											$sqlref="SELECT * FROM Tbl_cliente_referencia,Tbl_referencia WHERE Tbl_cliente_referencia.id_refcliente='$id_refcliente' and Tbl_cliente_referencia.N_referencia=Tbl_referencia.cod_ref ";
											$resultref= mysql_query($sqlref);
											$row_resultref = mysql_fetch_assoc($resultref);
											$numref= mysql_num_rows($resultref);

											$id_ref2=$row_resultref['id_ref'];
											$cod_ref2=$row_resultref['N_referencia'];
											$sqlrefcliente="DELETE FROM Tbl_cliente_referencia WHERE id_refcliente='$id_refcliente'";
											$resultrefcliente=mysql_query($sqlrefcliente);
											header("location:referencia_cliente.php?id_ref=$id_ref2&cod_ref=$cod_ref2"); }
											/*DELETE REFERENCIA*/
											if($id_ref!='') {
												$sqlref="SELECT * FROM referencia WHERE id_ref='$id_ref'";
												$resultref= mysql_query($sqlref);
												$numref= mysql_num_rows($resultref);
												if($numref >='1') {
													$n_egp=mysql_result($resultref,0,'n_egp_ref');
													$sqlegps="UPDATE egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
													$resultegps=mysql_query($sqlegps); }
													$sqlrefcliente="DELETE FROM ref_cliente WHERE id_ref='$id_ref'";
													$resultrefcliente=mysql_query($sqlrefcliente);
													$sqlrev="DELETE FROM revision WHERE id_ref_rev='$id_ref'";
													$resultrev=mysql_query($sqlrev);
													$sqlverif="SELECT * FROM verificacion WHERE id_ref_verif='$id_ref'";
													$resultverif= mysql_query($sqlverif);
													$numverif= mysql_num_rows($resultverif);
													if($numverif >='1') {
														for($i=0; $i<$numverif; $i++) {
															$sqlverificacion="DELETE FROM verificacion WHERE id_ref_verif='$id_ref'";
															$resultverificacion=mysql_query($sqlverificacion); } }
															$sqlm="SELECT * FROM control_modificaciones WHERE id_ref_cm='$id_ref'";
															$resultm= mysql_query($sqlm);
															$numcm= mysql_num_rows($resultm);
															if($numcm >='1') {
																for($i=0; $i<$numcm; $i++) {
																	$sqlcm="DELETE FROM control_modificaciones WHERE id_ref_cm = $id_ref";
																	$resultcm=mysql_query($sqlcm); } }
																	$sqlval="DELETE FROM validacion WHERE id_ref_val='$id_ref'";
																	$resultval=mysql_query($sqlval);
																	$sqlft="DELETE FROM ficha_tecnica WHERE id_ref_ft='$id_ref'";
																	$resultft=mysql_query($sqlft);
																	$sqlref="DELETE FROM referencia WHERE id_ref='$id_ref'";
																	$resultref=mysql_query($sqlref);
																	header("location:referencias.php?id=1"); }
																	/*DELETE REVISION BOLSA*/
																	if($id_rev!='') {
																		$sqlrev="DELETE FROM revision WHERE id_rev='$id_rev'";
																		$resultrev=mysql_query($sqlrev);
																		header("location:revision.php?id=1"); }
																		/*DELETE REVISION LAMINA*/
																		if($id_rev_l!='') {
																			$sqlrevl="DELETE FROM Tbl_revision_lamina WHERE id_rev_l='$id_rev_l'";
																			$resultrevl=mysql_query($sqlrevl);
																			header("location:referencias.php"); }
																			/*DELETE verificacion LAMINA*/
																			if($id_verif_l!='') {
																				$sqlrevl="DELETE FROM Tbl_verificacion_lamina WHERE id_verif_l='$id_verif_l'";
																				$resultrevl=mysql_query($sqlrevl);
																				header("location:referencias.php"); }
																				/*DELETE VERIFICACION BOLSA*/
																				if($id_verif!='') {
																					$sqlarte="SELECT * FROM verificacion WHERE id_verif='$id_verif'";
																					$resultarte= mysql_query($sqlarte);
																					$row_arte = mysql_fetch_assoc($resultarte);
																					$numarte= mysql_num_rows($resultarte);
																					if($numarte >='1') { 
																						$arte = mysql_result($resultarte, 0, 'userfile');
																						$id_ref_verif = mysql_result($resultarte, 0, 'id_ref_verif');
																						if(file_exists("archivo/".$arte))
																							{ unlink("archivo/".$arte); } }
																						$sqlverif="DELETE FROM verificacion WHERE id_verif='$id_verif'";
																						$resultverif=mysql_query($sqlverif);
																						$sqlcm="DELETE FROM control_modificaciones WHERE id_verif_cm='$id_verif'";
																						$resultcm=mysql_query($sqlcm);
																						header("location:verificacion_referencia.php?id_ref=$id_ref_verif"); }
																						/*DELETE VALIDACION LAMINA*/
																						if($id_val_l!='') {
																							$sqlrevl="DELETE FROM Tbl_validacion_lamina WHERE id_val_l='$id_val_l'";
																							$resultrevl=mysql_query($sqlrevl);
																							header("referencias.php"); }
																							/*DELETE REVISION PACKING*/
																							if($id_rev_p!='') {
																								$sqlrevp="DELETE FROM Tbl_revision_packing WHERE id_rev_p='$id_rev_p'";
																								$resultrevp=mysql_query($sqlrevp);
																								header("referencias.php"); }
																								/*DELETE VERIFICACION PACKING*/
																								if($id_verif_p!='') {
																									$sqlrevp="DELETE FROM Tbl_verificacion_packing WHERE id_val_p='$id_verif_p'";
																									$resultrevp=mysql_query($sqlrevp);
																									header("referencias.php"); }
																									/*DELETE VALIDACION PACKING*/
																									if($id_val_p!='') {
																										$sqlrevp="DELETE FROM Tbl_validacion_packing WHERE id_val_p='$id_val_p'";
																										$resultrevp=mysql_query($sqlrevp);
																										header("referencias.php"); }
																										/*DELETE CONTROL DE MODIFICACION BOLSA*/
																										if($id_cm!='')
																										{
																											$sqlcm="DELETE FROM control_modificaciones WHERE id_cm='$id_cm'";
																											$resultcm=mysql_query($sqlcm);
																											header("location:verificacion_referencia.php?id_ref=$id_ref_cm");
																										}
																										/*DELETE VALIDACION BOLSA*/
																										if($id_val!='')
																										{
																											$sqlval="DELETE FROM validacion WHERE id_val='$id_val'";
																											$resultval=mysql_query($sqlval);
																											header("location:validacion.php?id=1");
																										}
																										/*DELETE FICHA TECNICA BOLSA*/
																										if($n_ft!='')
																										{
																											$sqlft="DELETE FROM ficha_tecnica WHERE n_ft='$n_ft'";
																											$resultft=mysql_query($sqlft);
																											header("location:ficha_tecnica.php");
																										}
																										/*---------------------------------------------------------------*/
																										/*------------------GESTION DE COMPRAS------------------------*/
																										/*DELETE PROVEEDOR*/
																										if($id_p!='')
																										{
																											$sqlp="DELETE FROM proveedor WHERE id_p='$id_p'";
																											$resultp=mysql_query($sqlp);
																											$sqlsel="DELETE FROM proveedor_seleccion WHERE id_p_seleccion='$id_p'";
																											$resultsel=mysql_query($sqlsel);
																											$sqlpm="DELETE FROM proveedor_mejora WHERE id_p_pm='$id_p'";
																											$resultpm=mysql_query($sqlpm);
																											header("location:proveedores.php");
																										}
																										/*DELETE SELECCION-PROVEEDOR*/
																										if($id_seleccion != '')
																										{
																											$sqlsel="SELECT * FROM proveedor_seleccion WHERE id_seleccion='$id_seleccion'";
																											$resultsel= mysql_query($sqlsel);
																											$numsel= mysql_num_rows($resultsel);
																											if($numsel >='1')
																											{
																												$id_p_seleccion = mysql_result($resultsel, 0, 'id_p_seleccion');
																											}
																											$sqlsel="DELETE FROM proveedor_seleccion WHERE id_seleccion='$id_seleccion'";
																											$resultsel=mysql_query($sqlsel);
																											header("location:proveedor_edit.php?id_p=$id_p_seleccion");
																										}
																										/*DELETE PLAN MEJORA*/
																										if($id_pm != '')
																										{
																											$sqlpm="SELECT * FROM proveedor_mejora WHERE id_pm='$id_pm'";
																											$resultpm= mysql_query($sqlpm);
																											$numpm= mysql_num_rows($resultpm);
																											if($numpm >='1')
																											{
																												$id_p_pm = mysql_result($resultpm, 0, 'id_p_pm');
																											}
																											$sqlpm="DELETE FROM proveedor_mejora WHERE id_pm='$id_pm'";
																											$resultpm=mysql_query($sqlpm);
																											header("location:proveedor_mejoras.php?id_p=$id_p_pm");
																										}
																										/*DELETE INSUMO*/
																										if($id_insumo!='')
																										{ 
																											$sqlinsumo="UPDATE insumo SET estado_insumo='1' WHERE id_insumo='$id_insumo'";
																											$resultinsumo=mysql_query($sqlinsumo);
																											header("location:insumos.php");
																										}
																										/*DELETE ANILOX*/
																										if($id_anilox!='')
																										{ 
																											$sqlinsumo="DELETE FROM anilox WHERE id_insumo='$id_anilox'";
																											$resultinsumo=mysql_query($sqlinsumo);
																											header("location:anilox.php");
																										}
																										/*DELETE DETALLE O.C.*/
																										if($id_det!='')
																										{
																											$sqldetalle="SELECT * FROM orden_compra_detalle, orden_compra WHERE orden_compra_detalle.id_det='$id_det' AND orden_compra_detalle.n_oc_det=orden_compra.n_oc";
																											$resultdetalle= mysql_query($sqldetalle);
																											$numdetalle= mysql_num_rows($resultdetalle);
																											if($numdetalle >='1')
																											{
																												$n_oc_det = mysql_result($resultdetalle, 0, 'n_oc_det');
																												$id_p_oc = mysql_result($resultdetalle, 0, 'id_p_oc');
																												$id_insumo_ing = mysql_result($resultdetalle, 0, 'id_insumo_det'); 
																											}
//ELIMINO INGRESOS Y ACTUALIZO INVENTARIO
																											$sqlpm="SELECT SUM(ingreso_ing) AS cantidad FROM TblIngresos WHERE id_det_ing = '$id_det'";
																											$resultpm= mysql_query($sqlpm);
																											$numpm= mysql_num_rows($resultpm);
																											if($numpm >='1')
																											{
																												$cantidad_ing = mysql_result($resultpm, 0, 'cantidad');
																											}
																											$sqlinv="UPDATE TblInventarioListado SET  Entrada = Entrada - '$cantidad_ing' WHERE Codigo = '$id_insumo_ing'";
																											$resultinv=mysql_query($sqlinv);
																											$sqling="DELETE FROM TblIngresos WHERE id_det_ing = '$id_det'";
																											$resulting=mysql_query($sqling);
//ELIMINO EL ITEM
																											$sqldet="DELETE FROM orden_compra_detalle WHERE id_det='$id_det'";
																											$resultdet=mysql_query($sqldet);
																											header("location:orden_compra_edit.php?n_oc=$n_oc_det&id_p_oc=$id_p_oc");
																										}
																										/*DELETE O.C. INSUMOS*/
																										if($n_oc!='')
																										{
																											$sqldetalle="SELECT * FROM orden_compra_detalle WHERE n_oc_det='$n_oc'";
																											$resultdetalle= mysql_query($sqldetalle);
																											$numdetalle= mysql_num_rows($resultdetalle);
																											if($numdetalle >='1')
																											{
																												for($i=0; $i<$numdetalle; $i++)
																												{
																													$sqldet="DELETE FROM orden_compra_detalle WHERE n_oc_det='$n_oc'";
																													$resultdet=mysql_query($sqldet);
																												}
																											}
																											$sqloc="DELETE FROM orden_compra WHERE n_oc='$n_oc'";
																											$resultoc=mysql_query($sqloc);
																											header("location:orden_compra.php");
																										}
																										/*DELETE O.C. DEL VENTAS*/
																										if($id_pedido_oc!='')
																										{
																											$sqlorden="SELECT id_pedido,str_numero_oc,id_c_oc FROM tbl_orden_compra WHERE id_pedido='$id_pedido_oc'";
																											$resultorden= mysql_query($sqlorden);
																											$numorden= mysql_num_rows($resultorden);	
																											$str_numero_oc = mysql_result($resultorden, 0, 'str_numero_oc');
																											$id_c_oc = mysql_result($resultorden, 0, 'id_c_oc');
																											$id_pedido = mysql_result($resultorden, 0, 'id_pedido');
//QUE NO TENGA ITEMS
/*$sqlordenc="SELECT * FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido' ORDER BY id_pedido_io DESC";
$resultordenc = mysql_query($sqlordenc);
$numordenc = mysql_num_rows($resultordenc);*/
//VERIFICO Q NO TENGA REMISIONES
$sql2="SELECT * FROM Tbl_remisiones,tbl_remision_detalle WHERE Tbl_remisiones.str_numero_oc_r='$str_numero_oc' AND Tbl_remisiones.str_numero_oc_r=tbl_remision_detalle.str_numero_oc_rd";
$result2= mysql_query($sql2);
$numRem= mysql_num_rows($result2);
//EVALUA QUE NO TENGA ITEMS NI REMISIONES		
if($numRem <='0')
{
//ELIMINA O.C Y SUS ITEMS EN CASCADA
	$sqloc="DELETE FROM tbl_orden_compra WHERE id_pedido='$id_pedido_oc'";
	$resultoc=mysql_query($sqloc);

	$sqlitems="DELETE FROM tbl_items_ordenc WHERE str_numero_io='$str_numero_oc'";
	$resultitems=mysql_query($sqlitems); 

	$hoy = date("Y-m-d H:i:s");  
	$usuario = $_SESSION['Usuario'];
	$logs = "INSERT INTO tbl_logs (codigo_id, descrip, fecha, modificacion, usuario) VALUES ('". $str_numero_oc ."','OC','$hoy','Eliminado','$usuario')";
		$resultlogs=mysql_query($logs); 
//CAMBIA ESTADO DE REMISION TAMBIEN
/*$sqlborrado="UPDATE Tbl_remisiones SET b_borrado_r='1' WHERE str_numero_oc_r='$str_numero_oc' AND b_borrado_r='0'";
$resultborrado=mysql_query($sqlborrado);*/
$id=1;
header("location:orden_compra_cl2.php?id=$id");
}else{
	$id=2;	
	header("location:orden_compra_cl_edit.php?str_numero_oc=$str_numero_oc&id_oc=$id_c_oc&id=$id");
}
}
/*DELETE ITEMS DE ORDEN DE COMPRA DEL CLIENTE O PEDIDO DEL CLIENTE*/
if($id_items!='')
{
//SI EXISTE O.P
	$sqlexistop="SELECT Tbl_items_ordenc.id_items AS id
	FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.id_items='$id_items' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
	AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_estado_op <= '5' AND Tbl_orden_produccion.b_borrado_op='0'";
	$resultexistop = mysql_query($sqlexistop);
	$numexistop = mysql_num_rows($resultexistop);
//SI EXISTE REMISION
	$sqlrem="SELECT * FROM Tbl_items_ordenc,Tbl_remisiones WHERE Tbl_items_ordenc.id_items='$id_items' AND Tbl_items_ordenc.str_numero_io = Tbl_remisiones.str_numero_oc_r";
	$resultrem = mysql_query($sqlrem);
	$numrem= mysql_num_rows($resultrem);

//NO SE PUEDE ELIMINAR SI ESTA EN PRODUCCION
	if(($numexistop =="" || $numrem==""))
	{
		$sqlitems="SELECT * FROM Tbl_items_ordenc, Tbl_orden_compra,cliente WHERE Tbl_items_ordenc.id_items='$id_items' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.str_nit_oc=cliente.nit_c";
		$resultitems= mysql_query($sqlitems);
		$numitems= mysql_num_rows($resultitems);
		if($numitems >='1')
		{
			$str_numero_io = mysql_result($resultitems, 0, 'str_numero_io');
$id_oc = mysql_result($resultitems, 0, 'id_c');//para retornar al edit
}
$sqlitems2="DELETE FROM Tbl_items_ordenc WHERE id_items='$id_items'";
$resultitems2=mysql_query($sqlitems2);
$id=1;
header("location:orden_compra_cl_edit.php?str_numero_oc=$str_numero_io&id_oc=$id_oc&id=$id");
}else{
	$id=0;
	header("location:orden_compra_cl_edit.php?str_numero_oc=$str_numero_io&id_oc=$id_oc&id=$id");	
}
}


/*DELETE ITEMS DE ORDEN DE COMPRA DESDE REMISION ADD*/
$id_items_rem = $_GET['id_items_rem'];
if($id_items_rem!='')
{
//SI EXISTE O.P
	$sqlexistop="SELECT Tbl_items_ordenc.id_items AS id
	FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.id_items='$id_items_rem' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
	AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_estado_op <= '5' AND Tbl_orden_produccion.b_borrado_op='0'";
	$resultexistop = mysql_query($sqlexistop);
	$numexistop = mysql_num_rows($resultexistop);
//SI EXISTE REMISION
	$sqlrem="SELECT * FROM Tbl_items_ordenc,Tbl_remisiones WHERE Tbl_items_ordenc.id_items='$id_items_rem' AND Tbl_items_ordenc.str_numero_io = Tbl_remisiones.str_numero_oc_r";
	$resultrem = mysql_query($sqlrem);
	$numrem= mysql_num_rows($resultrem);

//NO SE PUEDE ELIMINAR SI ESTA EN PRODUCCION
	if(($numexistop =="" || $numrem==""))
	{
		$sqlitems="SELECT * FROM Tbl_items_ordenc, Tbl_orden_compra,cliente WHERE Tbl_items_ordenc.id_items='$id_items_rem' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.str_nit_oc=cliente.nit_c";
		$resultitems= mysql_query($sqlitems);
		$numitems= mysql_num_rows($resultitems);
		if($numitems >='1')
		{
			$str_numero_io = mysql_result($resultitems, 0, 'str_numero_io');
    $id_oc = mysql_result($resultitems, 0, 'id_c');//para retornar al edit
    }
    $sqlitems2="DELETE FROM Tbl_items_ordenc WHERE id_items='$id_items_rem'";
    $resultitems2=mysql_query($sqlitems2);
    $id=1;
    header("location:despacho_items_oc.php?str_numero_r=$str_numero_io");
    }else{
    	$id=0;
    	header("location:despacho_items_oc.php?str_numero_r=$str_numero_io");	
    }
}

/*if($id_itemsadd!='')
{
$sqlitems="SELECT * FROM Tbl_items_ordenc, Tbl_orden_compra,cliente WHERE Tbl_items_ordenc.id_items='$id_itemsadd' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.str_nit_oc=cliente.nit_c";
$resultitems= mysql_query($sqlitems);
$numitems= mysql_num_rows($resultitems);
if($numitems >='1')
{
$str_numero_io = mysql_result($resultitems, 0, 'str_numero_io');
$id_oc = mysql_result($resultitems, 0, 'id_c');
}
$sqlitems2="DELETE FROM Tbl_items_ordenc WHERE id_items='$id_itemsadd'";
$resultitems2=mysql_query($sqlitems2);
header("location:orden_compra_cl_edit.php?str_numero_oc=$str_numero_io&id_oc=$id_oc");
}*/

/*DELETE REMISION CAMBIO ESTADO BORRADO*/
if($int_remision!='') { 
	$sqlborrado="UPDATE Tbl_remisiones SET b_borrado_r='1' WHERE int_remision='$int_remision' AND b_borrado_r='0'";
//$sqlborrado2="DELETE FROM Tbl_remisiones WHERE int_remision='$int_remision'";
	$resultborrado=mysql_query($sqlborrado);
	header("location:despacho_oc.php");
}
/*DELETE REF DE REMISIONES*/
if($id_rd!='') {
	$sqldato="SELECT * FROM Tbl_remision_detalle WHERE id_rd='$id_rd'";
	$resultdato=mysql_query($sqldato);
	$remision=mysql_result($resultdato,0,'int_remision_r_rd');
	$orden=mysql_result($resultdato,0,'str_numero_oc_rd');
	$id_item=mysql_result($resultdato,0,'int_item_io_rd');
	$id_ref=mysql_result($resultdato,0,'int_ref_io_rd');
	$total_can=(float)$cant_rd=mysql_result($resultdato,0,'int_cant_rd');
	$estado_rd=mysql_result($resultdato,0,'estado_rd');
//CODIGO PARA DEVOLVER LA SUMA DE CANTIDAD A LA TABLA ITEMS
	$sqlitem="SELECT b_estado_io FROM Tbl_items_ordenc WHERE id_items='$id_item'";
	$resultitem=mysql_query($sqlitem); 
	$estado_io=mysql_result($resultitem,0,'b_estado_io');
//DEFINE SI DESCUENTA DEL INVENTARIO O JNO
	if($estado_io > '3' || $estado_rd=='0')
	{ 
		$updateINV = "UPDATE TblInventarioListado SET Salida=Salida - '$total_can' WHERE Cod_ref = $id_ref";
		$resultINV=mysql_query($updateINV);
	} 
   //FIN INVENTARIO
	$sqlsuma="UPDATE Tbl_items_ordenc SET int_cantidad_rest_io=int_cantidad_rest_io + '$total_can' WHERE id_items='$id_item'";
	$resultsuma=mysql_query($sqlsuma);

//FIN
	$sqlremi="DELETE FROM Tbl_remision_detalle WHERE id_rd='$id_rd'";
	$resultremision=mysql_query($sqlremi);
	header("location:despacho_items_oc_edit.php?int_remision=$remision&str_numero_r=$orden");
}
/*DELETE VERIFICACION INSUMO*/
if($n_vi!='')
{
	$sqlvi="SELECT * FROM verificacion_insumos WHERE n_vi='$n_vi'";
	$resultvi= mysql_query($sqlvi);
	$numvi= mysql_num_rows($resultvi);
	if($numvi >='1')
	{
		$n_oc_vi = mysql_result($resultvi, 0, 'n_oc_vi');
		$id_insumo_vi = mysql_result($resultvi, 0, 'id_insumo_vi');
		$id_det_vi = mysql_result($resultvi, 0, 'id_det_vi');
		$cantidad_recibida_vi = mysql_result($resultvi, 0, 'cantidad_recibida_vi');
		$faltantes_vi = mysql_result($resultvi, 0, 'faltantes_vi');
		$saldo=$faltantes_vi+$cantidad_recibida_vi;


		$sqldetalle="UPDATE orden_compra_detalle SET verificacion_det='$saldo' WHERE id_det='$id_det_vi'";
		$resultdetalle=mysql_query($sqldetalle);

		$sqlingresosalida="UPDATE tbl_ingresosalida_items SET ingresokilos = ingresokilos - $cantidad_recibida_vi,totalconsumo = totalconsumo - $cantidad_recibida_vi  WHERE nombre = '$id_insumo_vi' and oc ='$n_oc_vi' ";
		$resultingresosalida=mysql_query($sqlingresosalida);

	}
	$sqlvi="DELETE FROM verificacion_insumos WHERE n_vi='$n_vi'";
	$resultvi=mysql_query($sqlvi);
	header("location:verificacion_insumo_oc.php?n_oc=$n_oc_vi");
}


/*DELETE ROLLO*/
if($id_rollo!='')
{
	$sqlrollo="DELETE FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
	$resultrollo=mysql_query($sqlrollo);
	header("location:rollos.php");
}
/*DELETE O.C.ROLLOS*/
if($n_ocr!='')
{
	$sqlocr="DELETE FROM orden_compra_rollos WHERE n_ocr='$n_ocr'";
	$resultocr=mysql_query($sqlocr);
	header("location:rollos_oc.php");
}
/*DELETE VERIFICACION ROLLOS*/
if($n_vr!='')
{
	$sql1="SELECT * FROM verificacion_rollos WHERE n_vr='$n_vr'";
	$result1= mysql_query($sql1);
	$num1= mysql_num_rows($result1);
	if($num1 >='1')
	{
		$n_ocr=mysql_result($result1,0,'n_ocr_vr');
		$cantidad_encontrada=mysql_result($result1,0,'cantidad_encontrada_vr');
	}
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
	$sqlvr="DELETE FROM verificacion_rollos WHERE n_vr='$n_vr'";
	$resultvr=mysql_query($sqlvr);
	header("location:rollos_oc_verificacion.php?n_ocr=$n_ocr");
}
/*DELETE BOLSAS*/
if($id_bolsa!='')
{
	$sqlbolsa="DELETE FROM material_terminado_bolsas WHERE id_bolsa='$id_bolsa'";
	$resultbolsa=mysql_query($sqlbolsa);
	header("location:bolsas.php");
}
/*DELETE O.C.ROLLOS*/
if($n_ocb!='')
{
	$sqlocb="DELETE FROM orden_compra_bolsas WHERE n_ocb='$n_ocb'";
	$resultocb=mysql_query($sqlocb);
	header("location:bolsas_oc.php");
}
/*DELETE VERIFICACION BOLSAS*/
if($n_vb!='')
{
	$sql1="SELECT * FROM verificacion_bolsas WHERE n_vb='$n_vb'";
	$result1= mysql_query($sql1);
	$num1= mysql_num_rows($result1);
	if($num1 >='1')
	{
		$n_ocb=mysql_result($result1,0,'n_ocb_vb');
		$cantidad_encontrada=mysql_result($result1,0,'cantidad_encontrada_vb');
	}
	$sql2="SELECT * FROM orden_compra_bolsas WHERE n_ocb='$n_ocb'";
	$result2= mysql_query($sql2);
	$num2= mysql_num_rows($result2);
	if($num2>='1')
	{
		$saldo_verificacion=mysql_result($result2,0,'saldo_verificacion_ocb');
	}
	$falta=$saldo_verificacion+$cantidad_encontrada;
	$sql3="UPDATE orden_compra_bolsas SET saldo_verificacion_ocb='$falta' WHERE n_ocb='$n_ocb'";
	$result3=mysql_query($sql3);
	$sqlvb="DELETE FROM verificacion_bolsas WHERE n_vb='$n_vb'";
	$resultvb=mysql_query($sqlvb);
	header("location:bolsas_oc_verificacion.php?n_ocb=$n_ocb");
}
/*DELETE EVALUACION*/
if($id_ev!='')
{
	$sqlev="SELECT * FROM evaluacion_proveedor WHERE id_ev='$id_ev'";
	$resultev= mysql_query($sqlev);
	$numev= mysql_num_rows($resultev);
	if($numev >='1')
	{
		$id_p = mysql_result($resultev, 0, 'id_p_ev');
	}
	$sqlev="DELETE FROM evaluacion_proveedor WHERE id_ev='$id_ev'";
	$resultev=mysql_query($sqlev);
	header("location:evaluacion_proveedor.php?id_p=$id_p&evaluacion=1");
}
/*DELETE EVALUACION FINAL*/
if($id_eva!='')
{
	$sqleva="DELETE FROM evaluacion_anual WHERE id_eva='$id_eva'";
	$resulteva=mysql_query($sqleva);
	header("location:evaluacion_anual.php");
}
/*----------------------------------------------------------*/
/*--------------GESTION DE PRODUCCION-----------------------*/
/*DELETE TIPO DE EMPLEADO*/
if($id_empleado_tipo!='')
{
	$sqltipo="DELETE FROM empleado_tipo WHERE id_empleado_tipo='$id_empleado_tipo'";
	$resultipo=mysql_query($sqltipo);
	header("location:empleado_tipo.php");
}
/*DELETE EMPLEADO*/
if($id_empleado!='')
{
	$sql="SELECT * FROM empleado WHERE id_empleado='$id_empleado'";
	$result= mysql_query($sql);
	$num= mysql_num_rows($result);
	if($num >='1') {
		$codigo_empl = mysql_result($result, 0, 'codigo_empleado');
	}
	$sqlemp="DELETE FROM empleado WHERE id_empleado='$id_empleado'";
	$resultemp=mysql_query($sqlemp);
	$sqlaporte="DELETE FROM TblAportes WHERE codigo_empl='$codigo_empl'";
	$resultaporte=mysql_query($sqlaporte);

	header("location:empleados.php");
}
/*DELETE TURNO DEL EMPLEADO*/
if($id_empleado_turno!='')
{
	$sqlturno="DELETE FROM empleado_turno WHERE id_empleado_turno='$id_empleado_turno'";
	$resulturno=mysql_query($sqlturno);
	header("location:turnos.php");
}
//ELIMINAR APORTES de empleado
if($id_aporte!='')
{
	$sqlaporte="DELETE FROM TblAportes WHERE id_aporte='$id_aporte'";
	$resulaporte=mysql_query($sqlaporte);
	header("location:proceso_empleados_listado.php");
}
/*DELETE MAQUINA*/
if($id_maquina!='')
{
	$sqlmaquina="DELETE FROM maquina WHERE id_maquina='$id_maquina'";
	$resultmaquina=mysql_query($sqlmaquina);
	header("location:maquinas.php");
}
/*DELETE CONSUMO MATERIAL*/
if($id_consumo_material!='')
{
	$sql="SELECT * FROM sellado_consumo_material WHERE id_consumo_material='$id_consumo_material'";
	$result= mysql_query($sql);
	$num= mysql_num_rows($result);
	if($num >='1') { $id_encabezado = mysql_result($result, 0, 'id_encabezado'); }
	$sql="DELETE FROM sellado_consumo_material WHERE id_consumo_material='$id_consumo_material'";
	$result=mysql_query($sql);
	header("location:sellado_consumo_material.php?id_encabezado=$id_encabezado");
}
/*DELETE REF AC REF CLIENTE*/
if($id_refac_refcliente!='')
{
	$sql="SELECT * FROM Tbl_refcliente WHERE id_refcliente='$id_refac_refcliente'";
	$result= mysql_query($sql);
	$num= mysql_num_rows($result);
	if($num >='1') { 
		$id_refcli = mysql_result($result, 0, 'id_refcliente'); 

		$sql="DELETE FROM Tbl_refcliente WHERE id_refcliente ='$id_refcli'";
		$result=mysql_query($sql);
		$id=1;
		header("location:ref_ac_ref_cl_listado.php?id=$id"); 
	}else {
		$id=0; 
		header("location:ref_ac_ref_cl_listado.php?id=$id"); 
	}
}
if($rollback!=''){
	$sqlpm="DELETE FROM Tbl_produccion_mezclas WHERE id_pm ='$rollback'";
	$resultpm=mysql_query($sqlpm);
	header("location:referencias.php");
}
/*---------COSTOS----------*
/*DELETE GENERADORES*/
if($id_genera!='')
{
	$sqlgeneradores="DELETE FROM Tbl_generadores WHERE id_generadores='$id_genera'";
	$resultgeneradores=mysql_query($sqlgeneradores);
	$id='1';
	header("location:costos_generadores_cif_gga.php?id=$id");
}
//generador y valor
if($id_genera_gv!='')
{
	$sqlgv="SELECT * FROM Tbl_generadores_valor WHERE id_gv='$id_genera_gv'";
	$resultgv= mysql_query($sqlgv);
	$numgv= mysql_num_rows($resultgv);
	if($numgv >='1')
	{
		$fecha1 = mysql_result($resultgv, 0, 'fecha_ini_gv');
		$fecha2 = mysql_result($resultgv, 0, 'fecha_fin_gv');
	}	
	$sqlgeneradores="DELETE FROM Tbl_generadores_valor WHERE id_gv='$id_genera_gv'";
	$resultgeneradores=mysql_query($sqlgeneradores);
	$id='1';
	header("location:costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=$fecha1&fecha_fin_gv=$fecha2");
}
/*----------- GESTOS COSTOS ----------------*/
//ELIMINA PROCESO EMPLEADO
if($id_pem!='')
{
	$sqlemp="DELETE FROM TblProcesoEmpleado WHERE id_pem='$id_pem'";
	$resultemp=mysql_query($sqlemp);
	header("location:proceso_empleados_listado.php");
}
//ELIMINA MES GGA DE COSTOS
if($_GET['costeo'] == '1'){
	$fecha1=$_GET['FechaInicio'];
	$fecha2=$_GET['FechaFin'];
	if(count($_GET['IDCaracGGA'])) {
		foreach ($_GET['IDCaracGGA'] as $v) {
			$sql = "DELETE FROM TblDetalleGGAProd WHERE FechaInicio='$fecha1' AND FechaFin='$fecha2' AND IDCaracGGA = '$v'";
			$resultado = mysql_query ($sql, $conexion1);
			$id=1;
			header("location:costos_listado_ggaycif.php?id=$id"); }
		}
		else {
			$id=0;
			header("location:costos_listado_ggaycif.php?id=$id"); 
		}

	}
	/*DELETE DETALLE EXPORTACION EDIT*/
	if($id_det_ce!='')
	{
		$sqldetalle="SELECT * FROM TblCostoExportacionDetalle, TblCostoExportacion WHERE TblCostoExportacionDetalle.id_det='$id_det_ce' AND TblCostoExportacionDetalle.n_ce_det=TblCostoExportacion.n_ce";
		$resultdetalle= mysql_query($sqldetalle);
		$numdetalle= mysql_num_rows($resultdetalle);
		if($numdetalle >='1')
		{
			$id_det = mysql_result($resultdetalle, 0, 'id_det');
			$n_ce= mysql_result($resultdetalle, 0, 'n_ce');
			$id_c_ce= mysql_result($resultdetalle, 0, 'id_c_ce');

			$sqldet="DELETE FROM TblCostoExportacionDetalle WHERE id_det='$id_det'";
			$resultdet=mysql_query($sqldet);
			header("location:costo_exportacion_edit.php?n_ce=$n_ce&id_c_ce=$id_c_ce");
		}
	}
//DELETE ROLLO DE EXTRUSION DESDE EDIT Y VISTA DEL ROLLO
	if($id_re!='')
	{
		$sqlext="SELECT id_r,id_op_r,rollo_r,fechaI_r FROM TblExtruderRollo WHERE id_r='$id_re'";
		$resultext= mysql_query($sqlext);
		$numext= mysql_num_rows($resultext); 
		if($numext >='1')
		{
			$id_rol = mysql_result($resultext,0, 'id_r');
			$id_op = mysql_result($resultext,0, 'id_op_r');
			$rollo_r = mysql_result($resultext,0, 'rollo_r');
			$fechaI_r = mysql_result($resultext,0, 'fechaI_r');

			$sqlrollo="DELETE FROM TblExtruderRollo WHERE id_r='$id_rol'";
			$resultrollo=mysql_query($sqlrollo);
//DEVUELVO AL INVENTARIO
//devuelvo a inventario si este rollo contiene las tintas
			$sqlre="SELECT id_rpp_rp, valor_prod_rp FROM Tbl_reg_kilo_producido WHERE op_rp = $id_op AND fecha_rkp='$fechaI_r' and id_proceso_rkp='1'";
			$resultre= mysql_query($sqlre);
			$numere= mysql_num_rows($resultre);
			for($i=0; $i<$numere; $i++)
			{
				$id_insumo=mysql_result($resultre,$i,'id_rpp_rp');
				$cantidad=mysql_result($resultre,$i,'valor_prod_rp'); 

				$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$cantidad' WHERE Codigo = $id_insumo";
				$resultinv=mysql_query($sqlinv, $conexion1); 
			}
//desperdicios
			$sqldes="DELETE FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd=1 AND fecha_rd='$fechaI_r'";
			$resultdes=mysql_query($sqldes);
			$sqltiempo="DELETE FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt=1 AND fecha_rt='$fechaI_r'";
			$resulttiempo=mysql_query($sqltiempo);
			$sqltiempop="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp=1 AND fecha_rtp='$fechaI_r'";
			$resulttiempop=mysql_query($sqltiempop);
			$sqlkilos="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp=1 AND fecha_rkp='$fechaI_r'";
			$resultkilos=mysql_query($sqlkilos);

//ELIMINO LIQUIDACION SI SE ELIMINAN TODOS LOS ROLLOS
			$sqlop="SELECT id_r FROM TblExtruderRollo WHERE id_op_r='$id_op'";
			$resultop = mysql_query($sqlop);
			$numop = mysql_num_rows($resultop);
			if($numop =='0')
			{
				$updateSQLupop = "DELETE FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1'";
				$resultupop= mysql_query($updateSQLupop);
				$numupop = mysql_num_rows($resultupop);
			}

//liquidado
/*$sqlliquidado="DELETE FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='1' AND fecha_ini_rp='$fechaI_r'";
$resultliquidado=mysql_query($sqlliquidado);*/
header("location:produccion_extrusion_listado_rollos.php?id_op_r=$id_op");
}
}
//DELETE ROLLO DE EXTRUSION DESDE LISTADOS
//SOLAMENTE BORRA EL REGISTRO DE LIQUIDACION
if($id_rel!='')
{
	$sqlext="SELECT id_op_rp,fecha_ini_rp FROM Tbl_reg_produccion WHERE id_rp='$id_rel'";
	$resultext= mysql_query($sqlext);
	$numext= mysql_num_rows($resultext); 
	if($numext >='1')
	{
		$id_op = mysql_result($resultext,0, 'id_op_rp');
		$fecha_ini= mysql_result($resultext,0, 'fecha_ini_rp');
	}
	$sqlliquidado="DELETE FROM Tbl_reg_produccion WHERE id_rp='$id_rel' AND id_proceso_rp='1'";
	$resultliquidado=mysql_query($sqlliquidado);

	$sqldesp="DELETE FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND fecha_rd = '$fecha_ini' AND id_proceso_rd='1'";
	$resultdesp=mysql_query($sqldesp);

	$sqltiem="DELETE FROM Tbl_reg_tiempo WHERE op_rt=$id_op AND fecha_rt = '$fecha_ini' AND id_proceso_rt='1'";
	$resulttiem=mysql_query($sqltiem);

	header("location:produccion_extrusion_listado_rollos.php?id_op_r=$id_op");
}
//DELETE ROLLO DE IMPRESION DESDE EDIT Y VISTA DEL ROLLO
if($id_ri!='')
{
	$sqlimp="SELECT id_r,id_op_r,rollo_r FROM TblImpresionRollo WHERE id_r='$id_ri'";
	$resultimp= mysql_query($sqlimp);
	$numimp= mysql_num_rows($resultimp);
	if($numimp >='1')
	{
		$id_rol = mysql_result($resultimp,0, 'id_r');
		$id_op = mysql_result($resultimp,0, 'id_op_r');
		$rollo_r = mysql_result($resultimp,0, 'rollo_r');
		$sqlrollo="DELETE FROM TblImpresionRollo WHERE id_r='$id_rol'";
		$resultrollo=mysql_query($sqlrollo);

//devuelvo a inventario si este rollo contiene las tintas
		$sqlre="SELECT id_rpp_rp, valor_prod_rp FROM Tbl_reg_kilo_producido WHERE op_rp = $id_op and int_rollo_rkp=$rollo_r and id_proceso_rkp='2'";
		$resultre= mysql_query($sqlre);
		$numere= mysql_num_rows($resultre);
		for($i=0; $i<$numere; $i++)
		{
			$id_insumo=mysql_result($resultre,$i,'id_rpp_rp');
			$cantidad=mysql_result($resultre,$i,'valor_prod_rp'); 

			$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$cantidad' WHERE Codigo = $id_insumo";
			$resultinv=mysql_query($sqlinv, $conexion1); 

		}
//desperdicios
		$sqldes="DELETE FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd=2 AND int_rollo_rd=$rollo_r";
		$resultdes=mysql_query($sqldes);
		$sqltiempo="DELETE FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt=2 AND int_rollo_rt=$rollo_r";
		$resulttiempo=mysql_query($sqltiempo);
		$sqltiempop="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp=2 AND int_rollo_rtp=$rollo_r";
		$resulttiempop=mysql_query($sqltiempop);
		$sqlkilos="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp=2 AND int_rollo_rkp=$rollo_r";
		$resultkilos=mysql_query($sqlkilos);
//liquidado
		$sqlliquidado="DELETE FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2' AND rollo_rp='$rollo_r'";
		$resultliquidado=mysql_query($sqlliquidado);

//CAMBIO DE ESTADO EN O.P SI SE ELIMINAN TODOS LOS ROLLOS
		$sqlop="SELECT id_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2'";
		$resultop = mysql_query($sqlop);
		$numop = mysql_num_rows($resultop);
		if($numop =='0')
		{
			$updateSQLupop = "UPDATE Tbl_orden_produccion SET b_estado_op = '1' WHERE id_op = '$id_op'";
			$resultupop= mysql_query($updateSQLupop);
			$numupop = mysql_num_rows($resultupop);
		}
		header("location:produccion_impresion_listado_rollos.php?id_op_r=$id_op");
	}
}
//ELIMINA DESDE EDIT DESDE IMPRESION
/*if($id_rliq!='')
{
$sqlnov="SELECT id_rp,id_op_rp,rollo_rp FROM Tbl_reg_produccion WHERE id_rp='$id_rliq'";
$resultnov= mysql_query($sqlnov);
$numnov= mysql_num_rows($resultnov); 
if($numnov >='1')
{
	$id_op= mysql_result($resultnov,0, 'id_op_rp');
	$rollo_r= mysql_result($resultnov,0, 'rollo_rp');
    $id_rp= mysql_result($resultnov,0, 'id_rp');
$sqlproinsumo="DELETE FROM Tbl_reg_produccion WHERE id_rp='$id_rp'";
$resultproinsumo=mysql_query($sqlproinsumo);

$sqlrollo="DELETE FROM TblImpresionRollo WHERE id_op_r='$id_op' AND rollo_r='$rollo_r'";
$resultrollo=mysql_query($sqlrollo);
//desperdicios
$sqldes="DELETE FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd=2 AND int_rollo_rd='$rollo_r'";
$resultdes=mysql_query($sqldes);
$sqltiempo="DELETE FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt=2 AND int_rollo_rt=$rollo_r"; 
$resulttiempo=mysql_query($sqltiempo);
$sqltiempop="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp=2 AND int_rollo_rtp=$rollo_r";
$resulttiempop=mysql_query($sqltiempop);
$sqlkilos="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp=2 AND int_rollo_rkp=$rollo_r";
$resultkilos=mysql_query($sqlkilos);
 //CAMBIO DE ESTADO EN O.P SI SE ELIMINAN TODOS LOS ROLLOS
$sqlop="SELECT id_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2'";
$resultop = mysql_query($sqlop);
$numop = mysql_num_rows($resultop);
if($numop =='0')
{
  $updateSQLupop = "UPDATE Tbl_orden_produccion SET b_estado_op = '1' WHERE id_op = '$id_op'";
  $resultupop= mysql_query($updateSQLupop);
  $numupop = mysql_num_rows($resultupop);
}
echo "<script type=\"text/javascript\">self.close();</script>";
}
}*/
 //ELIMINA DESDE EDIT DESDE SELLADO PARCIALES SOLO EL ROLLO
if($id_rolloparcial!='')
{
	$sqlnov="SELECT *,(kilos_r) AS KILOPEND, TIMEDIFF(`fechaF_r`, `fechaI_r`) AS TIEMPODIFE 
	FROM TblSelladoRollo WHERE id_r='$id_rolloparcial'";
	$resultnov= mysql_query($sqlnov);
	$numnov= mysql_num_rows($resultnov); 
	if($numnov >='1')
	{
		$id_op = mysql_result($resultnov,0, 'id_op_r');
		$rollo_r = mysql_result($resultnov,0, 'rollo_r');
		$bolsas = mysql_result($resultnov,0, 'bolsas_r');
		$fechaI = mysql_result($resultnov,0, 'fechaI_r');
		$kilosconsumo = mysql_result($resultnov,0, 'kilos_r');
		$reproceso = mysql_result($resultnov,0, 'reproceso_r');
		$metrosentran = mysql_result($resultnov,0, 'metro_r');
		$costo = mysql_result($resultnov,0, 'costo_r');
		$kilopen = mysql_result($resultnov,0, 'KILOPEND');
		$tiempo = mysql_result($resultnov,0, 'TIEMPODIFE');
	//ACTUALIZO INVENTARIO
		$sqlCINV="SELECT Tbl_orden_produccion.id_termica_op,Tbl_referencia.tipoCinta_ref,Tbl_referencia.cod_ref,Tbl_referencia.version_ref FROM Tbl_orden_produccion,Tbl_referencia WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref";
		$resultCINV= mysql_query($sqlCINV);
		$numCINV= mysql_num_rows($resultCINV); 
		if($numCINV >='1')
		{
			$tipoCinta_ref = mysql_result($resultCINV,0, 'tipoCinta_ref');
			$id_termica_op = mysql_result($resultCINV,0, 'id_termica_op');
			$cod_ref = mysql_result($resultCINV,0, 'cod_ref');
			$version_ref = mysql_result($resultCINV,0, 'version_ref');

			$array = array($id_termica_op, $tipoCinta_ref);
		}
//RESTO EL INSUMO A LA SALIDA
		if(count($array)) {
			foreach ($array as $v) {
				$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$metrosentran' WHERE Codigo = $v";
				$resultinv=mysql_query($sqlinv);
			}
		}
	//RESTO LAS BOLSAS INGRESADAS
		$codvers=$cod_ref."-".$version_ref;
		$sqlinv2="UPDATE TblInventarioListado SET Entrada = Entrada - '$bolsas' WHERE Codigo = '$codvers'";
		$resultinv2=mysql_query($sqlinv2);

		$sqlfalt="SELECT SUM(valor_desp_rd) AS TDD FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd=4 AND int_rollo_rd='$rollo_r' AND fecha_rd='$fechaI'";
		$resultfalt=mysql_query($sqlfalt);
		$falt = mysql_result($resultfalt,0, 'TDD');	
 	$kilopend = ($falt + $kilosconsumo + $reproceso);//KILOS PENDIENTES DEVUELTOS + EL DESPERDICIO + REPROCESO SI LO TIENE
 }
    //ACTUALIZO EL ULTIMO ROLLO A PARCIAL PARA QUE PUEDAN SEGUIR INGRESANDO PARCIAL metroIni_r + $metrosentran Y $kilopend ES PARA ACTUALIZAR TODOS LOS KILOS Y METROS INICIALES
	$sqlparcial="UPDATE TblSelladoRollo SET rolloParcial_r='1', metroIni_r = metroIni_r + $metrosentran,kilopendiente_r=kilopendiente_r+$kilopend WHERE id_op_r='$id_op' AND rollo_r='$rollo_r' ORDER BY id_r DESC LIMIT 1";//LIMIT 1 PARA Q ACTUALICE EL METRO  Y KILOS DEL ULTIMO PARCIAL GUARDADO
	$resultparcial=mysql_query($sqlparcial); 

//ACTUALIZO LA LIQUIDACION 
	$sqlresta="UPDATE Tbl_reg_produccion SET bolsa_rp = bolsa_rp-'$bolsas', int_kilos_desp_rp=int_kilos_desp_rp-'$falt', int_total_kilos_rp=int_total_kilos_rp-'$kilopend', int_metro_lineal_rp=int_metro_lineal_rp-'$metrosentran', total_horas_rp=TIMEDIFF(total_horas_rp,'$tiempo'), rodamiento_rp=TIMEDIFF(rodamiento_rp,'$tiempo'), kiloFaltante_rp=kiloFaltante_rp+'$kilopend', costo=costo-'$costo' WHERE id_op_rp = '$id_op' AND rollo_rp = '$rollo_r' AND id_proceso_rp='4'";
	$resultresta=mysql_query($sqlresta);

	//CAMBIO DE ESTADO EN O.P SI SE ELIMINAN TODOS LOS ROLLOS
	$sqlop="SELECT id_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'";
	$resultop = mysql_query($sqlop);
	$numop = mysql_num_rows($resultop);
	if($numop =='0')
	{
		$updateSQLupop = "UPDATE Tbl_orden_produccion SET b_estado_op = '2' WHERE id_op = '$id_op'";
		$resultupop= mysql_query($updateSQLupop);
		$numupop = mysql_num_rows($resultupop);
	}


    //ELIMINACION
	$sqlrollo="DELETE FROM TblSelladoRollo WHERE id_r='$id_rolloparcial'";
	$resultrollo=mysql_query($sqlrollo);
 	// ELIMINA SI ES EL ULTIMO ROLLO EN TblSellado
	$sqlop="SELECT id_r FROM TblSelladoRollo WHERE id_op_r='$id_op' AND rollo_r=$rollo_r";
	$resultop = mysql_query($sqlop);
	$numop = mysql_num_rows($resultop);
	if($numop =='0')//SI SE ACABAN LOS ROLLOS ELIMINA EN LIQUIDACION
	{
		$sqlliq="DELETE FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND rollo_rp='$rollo_r' AND id_proceso_rp='4'";
		$resultliq = mysql_query($sqlliq); 
	}
	//desperdicios
	if($resultop){

		$sqldes="DELETE FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd=4 AND int_rollo_rd='$rollo_r' AND fecha_rd='$fechaI'";
		$resultdes=mysql_query($sqldes);
		$sqltiempo="DELETE FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt=4 AND int_rollo_rt='$rollo_r' AND fecha_rt='$fechaI'"; 
		$resulttiempo=mysql_query($sqltiempo);
		$sqltiempop="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp=4 AND int_rollo_rtp='$rollo_r' AND fecha_rtp='$fechaI'";
		$resulttiempop=mysql_query($sqltiempop);
		$sqlkilos="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp=4 AND int_rollo_rkp=$rollo_r AND fecha_rkp='$fechaI'";
		$resultkilos=mysql_query($sqlkilos);
	}
	header("location:produccion_registro_sellado_total_vista.php?id_op=$id_op");
}






 //ELIMINA DESDE EDIT DESDE SELLADO
if($id_rliqs!='')
{
	$sqlnov="SELECT id_r,id_op_r,rollo_r FROM TblSelladoRollo WHERE id_r='$id_rliqs'";
	$resultnov= mysql_query($sqlnov);
	$numnov= mysql_num_rows($resultnov); 
	if($numnov >='1')
	{
		$id_op = mysql_result($resultnov,0, 'id_op_r');
		$rollo_r= mysql_result($resultnov,0, 'rollo_r');
		$id_rp= mysql_result($resultnov,0, 'id_r');
		$sqlproinsumo="DELETE FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND rollo_rp='$rollo_r' AND id_proceso_rp='4'";
		$resultproinsumo=mysql_query($sqlproinsumo);

		$sqlrollo="DELETE FROM TblSelladoRollo WHERE id_op_r='$id_op' AND rollo_r='$rollo_r'";
		$resultrollo=mysql_query($sqlrollo);
//desperdicios
		$sqldes="DELETE FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd=4 AND int_rollo_rd='$rollo_r'";
		$resultdes=mysql_query($sqldes);
		$sqltiempo="DELETE FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND id_proceso_rt=4 AND int_rollo_rt=$rollo_r"; 
		$resulttiempo=mysql_query($sqltiempo);
		$sqltiempop="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND id_proceso_rtp=4 AND int_rollo_rtp=$rollo_r";
		$resulttiempop=mysql_query($sqltiempop);
		$sqlkilos="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp=4 AND int_rollo_rkp=$rollo_r";
		$resultkilos=mysql_query($sqlkilos);
 //CAMBIO DE ESTADO EN O.P SI SE ELIMINAN TODOS LOS ROLLOS
		$sqlop="SELECT id_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'";
		$resultop = mysql_query($sqlop);
		$numop = mysql_num_rows($resultop);
		if($numop =='0')
		{
			$updateSQLupop = "UPDATE Tbl_orden_produccion SET b_estado_op = '2' WHERE id_op = '$id_op'";
			$resultupop= mysql_query($updateSQLupop);
			$numupop = mysql_num_rows($resultupop);
		}
		/*echo "<script type=\"text/javascript\">self.close();</script>"*/
		header("location:produccion_registro_sellado_total_vista.php?id_op=$id_op");
	}
}
 //ELIMINA OP PRODUCCION
if($id_op_bo!='')
{
	$sqlOP="UPDATE Tbl_orden_produccion SET b_borrado_op='1' WHERE id_op='$id_op_bo'";
	$resultOP=mysql_query($sqlOP);
	$sqln="UPDATE Tbl_numeracion SET b_borrado_n='1' WHERE int_op_n='$id_op_bo' AND b_borrado_n='0'";
	$resultn=mysql_query($sqln);
	header("location:produccion_ordenes_produccion_listado_inactivo.php");
}

if($id_nov!='')
{
	$sqlnov="SELECT id_nov,codigo_empleado FROM TblNovedades WHERE id_nov='$id_nov'";
	$resultnov= mysql_query($sqlnov);
	$numnov= mysql_num_rows($resultnov); 
	if($numnov >='1')
	{
		$id_nov_del= mysql_result($resultnov,0, 'id_nov');
		$cod= mysql_result($resultnov,0, 'codigo_empleado');
		$sqlnovedad="DELETE FROM TblNovedades WHERE id_nov='$id_nov_del'";
		$resultnovedad=mysql_query($sqlnovedad);
		echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
		echo "<script type=\"text/javascript\">window.close();</script>";

	} 
}


if($id_gga_fecha!='')
{
	$estado=$estado_gga;

	$sqlGGA="UPDATE Tbl_generadores_valor SET estado_gv='$estado' WHERE fecha_ini_gv='$id_gga_fecha'";
	$resultGGA=mysql_query($sqlGGA);
	header("location:costos_listado_gga.php");
}
if($id_pi!='')
{
	$sqlnov="SELECT id_p FROM TblProveedorInsumo WHERE id_pi='$id_pi'";
	$resultnov= mysql_query($sqlnov);
	$numnov= mysql_num_rows($resultnov); 
	if($numnov >='1')
	{
		$id_p= mysql_result($resultnov,0, 'id_p');
		$sqlproinsumo="DELETE FROM TblProveedorInsumo WHERE id_pi='$id_pi'";
		$resultproinsumo=mysql_query($sqlproinsumo);
		header("location:proveedor_insumo.php?id_p=$id_p");
	}
}
//IMPRESION LIQUIDACION EDITA TIEMPOS Y DESPERDICIOS
if($id_rtei!='') {
  $id_reR = $id_reR;//id del rollo
  $sqlrt="SELECT * FROM Tbl_reg_tiempo WHERE id_rt='$id_rtei'";
  $resultrt= mysql_query($sqlrt);
  $numert= mysql_num_rows($resultrt);
  if($numert >='1') {
  	$id_op=mysql_result($resultrt,0,'op_rt');
  	$id_rtdel=mysql_result($resultrt,0,'id_rt');
  	$rollo_rt=mysql_result($resultrt,0,'int_rollo_rt');
  	$fecha=mysql_result($resultrt,0,'fecha_rt');
  	$cantid=mysql_result($resultrt,0,'valor_tiem_rt');
	//importantisimo ya que devuelve la cantidad al rodamiento  
  	$sqlresta="SELECT SEC_TO_TIME(TIME_TO_SEC(`rodamiento_rp`) + ($cantid*60)) as trodam 
  	FROM `Tbl_reg_produccion`
  	WHERE `id_op_rp`='$id_op' AND `rollo_rp` = '$rollo_rt' AND id_proceso_rp='2'"; 
  	$resultresta=mysql_query($sqlresta); 
  	$numeresta= mysql_num_rows($resultresta);
  	$trodam=mysql_result($resultresta,0,'trodam');

  	$sqlupresta="UPDATE Tbl_reg_produccion SET horas_muertas_rp = horas_muertas_rp - $cantid, rodamiento_rp = '$trodam' WHERE id_op_rp = '$id_op' AND rollo_rp= '$rollo_rt' AND id_proceso_rp='2' ORDER BY id_rp DESC";
  	$resultupresta=mysql_query($sqlupresta);

  	$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rtdel'";
  	$resulrt=mysql_query($sqlrt);
  	header("location:produccion_impresion_stiker_rollo_edit.php?id_r=$id_reR");
  }
}
if($id_rpei!='') {
	 $id_reR = $id_reR;//id del rollo
	 $sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpei'";
	 $resultrp= mysql_query($sqlrp);
	 $numerp= mysql_num_rows($resultrp);
	 if($numerp >='1') {
	 	$id_op=mysql_result($resultrp,0,'op_rtp');
	 	$id_rpdel=mysql_result($resultrp,0,'id_rt');
	 	$fecha=mysql_result($resultrp,0,'fecha_rtp');
	 	$rollo_rt=mysql_result($resultrp,0,'int_rollo_rtp');
	 	$cantid=mysql_result($resultrp,0,'valor_prep_rtp');	

	 	$sqlresta="UPDATE Tbl_reg_produccion SET horas_prep_rp = horas_prep_rp - $cantid  WHERE id_op_rp = '$id_op' AND rollo_rp = $rollo_rt AND id_proceso_rp='2' ORDER BY id_rp DESC";     $resultresta=mysql_query($sqlresta);

	 	$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpdel'";
	 	$resulrp=mysql_query($sqlrp);
	 	header("location:produccion_impresion_stiker_rollo_edit.php?id_r=$id_reR");
	 }
	}
	if($id_rdei!='') {
	 $id_reR = $id_reR;//id del rollo
	 $sqlrd="SELECT * FROM  Tbl_reg_desperdicio WHERE id_rd='$id_rdei'";
	 $resultrd= mysql_query($sqlrd);
	 $numerd= mysql_num_rows($resultrd);
	 if($numerd >='1') {
	 	$id_rdei;
	 	$id_op=mysql_result($resultrd,0,'op_rd');
	 	$id_rddel=mysql_result($resultrd,0,'id_rd');
	$cantid=(float)mysql_result($resultrd,0,'valor_desp_rd');//desperdicio
	$fecha=mysql_result($resultrd,0,'fecha_rd');
	$rollo_rd=mysql_result($resultrd,0,'int_rollo_rd');
	
	//RECUPERA LOS METROS AL ELIMINAR LOS KILOS DESPERDICIADOS
	$sqlrp="SELECT int_total_kilos_rp, int_metro_lineal_rp FROM Tbl_reg_produccion WHERE id_op_rp=$id_op AND rollo_rp='$rollo_rd' AND id_proceso_rp='2'"; 
	$resultrp=mysql_query($sqlrp); 		
	$kilosentran=mysql_result($resultrp,0,'int_total_kilos_rp');
	$metrosentran=mysql_result($resultrp,0,'int_metro_lineal_rp');
	$kilosTotales= ($kilosentran + $cantid);
	$nuevosMetros = round($kilosTotales * $metrosentran / $kilosentran); 

	$sqlresta="UPDATE Tbl_reg_produccion SET int_kilos_desp_rp = int_kilos_desp_rp - $cantid, int_total_kilos_rp = $kilosTotales, int_metro_lineal_rp=$nuevosMetros WHERE id_op_rp = '$id_op' AND rollo_rp = $rollo_rd AND id_proceso_rp='2' ORDER BY id_rp DESC";
	$resultresta=mysql_query($sqlresta);

	$sqlresta="UPDATE TblImpresionRollo SET metro_r = metro_r + $nuevosMetros, kilos_r = $kilosTotales WHERE id_op_rp = '$id_op' AND rollo_rp= $rollo_rd ORDER BY id_rp DESC";
	$resultresta=mysql_query($sqlresta);

	$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rddel'";
	$resulrd=mysql_query($sqlrd);
	header("location:produccion_impresion_stiker_rollo_edit.php?id_r=$id_reR");
}
}


//SELLADO EDIT ELIMINA TIEMPOS Y DESPERDICIOS Y MATERIA PRIMA
if($id_rts!='') {
	$id_reRS=$id_reRS;//variable del rollo para edit
	$sqlrt="SELECT * FROM Tbl_reg_tiempo WHERE id_rt='$id_rts'";
	$resultrt= mysql_query($sqlrt);
	$numert= mysql_num_rows($resultrt);
	if($numert >='1') {
		$id_op=mysql_result($resultrt,0,'op_rt');
		$id_rtdel=mysql_result($resultrt,0,'id_rt');
		$fecha=mysql_result($resultrt,0,'fecha_rt');	
		$rollo=mysql_result($resultrt,0,'int_rollo_rt');
		$cantid=mysql_result($resultrt,0,'valor_tiem_rt');

	//importantisimo ya que devuelve la cantidad al rodamiento  
		$sqlresta="SELECT SEC_TO_TIME(TIME_TO_SEC(`rodamiento_rp`) + ($cantid*60)) as trodam 
		FROM `Tbl_reg_produccion`
		WHERE `id_op_rp`='$id_op' AND `rollo_rp` = '$rollo' AND id_proceso_rp='4'"; 
		$resultresta=mysql_query($sqlresta); 
		$numeresta= mysql_num_rows($resultresta);
	$trodam=mysql_result($resultresta,0,'trodam');//sumada del nuevo rodamiento

	$sqlresta="UPDATE Tbl_reg_produccion SET horas_muertas_rp = horas_muertas_rp - $cantid, rodamiento_rp = '$trodam' WHERE id_op_rp = '$id_op' AND rollo_rp= $rollo AND id_proceso_rp='4' ORDER BY id_rp DESC";
	$resultresta=mysql_query($sqlresta);

	$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rts'";
	$resulrt=mysql_query($sqlrt);

	header("location:produccion_registro_sellado_edit.php?id_r=$id_reRS");
}
}

if($id_rps!='') {
	$id_reRS=$id_reRS;//variable del rollo para edit
	$sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rps'";
	$resultrp= mysql_query($sqlrp);
	$numerp= mysql_num_rows($resultrp);
	if($numerp >='1') {
		$id_op=mysql_result($resultrp,0,'op_rtp');
		$id_rpdel=mysql_result($resultrp,0,'id_rt');
		$fecha=mysql_result($resultrp,0,'fecha_rtp');
		$rollo_rt=mysql_result($resultrp,0,'int_rollo_rtp');
		$cantid=mysql_result($resultrp,0,'valor_prep_rtp');	

	$sqlresta="UPDATE Tbl_reg_produccion SET horas_prep_rp = horas_prep_rp - $cantid WHERE id_op_rp = '$id_op' AND rollo_rp = $rollo_rt AND id_proceso_rp='4' ORDER BY id_rp DESC";//rodamiento_rp=rodamiento_rp-$cantid
	$resultresta=mysql_query($sqlresta);

	$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rps'";
	$resulrp=mysql_query($sqlrp);
	header("location:produccion_registro_sellado_edit.php?id_r=$id_reRS");
}
}
if($id_rds!='') {
	$id_reRS=$id_reRS;//variable del rollo para edit
	$sqlrd="SELECT * FROM  Tbl_reg_desperdicio WHERE id_rd='$id_rds'";
	$resultrd= mysql_query($sqlrd);
	$numerd= mysql_num_rows($resultrd);
	if($numerd >='1') {
		$id_rdei;
		$id_op=mysql_result($resultrd,0,'op_rd');
		$id_rddel=mysql_result($resultrd,0,'id_rd');
	$cantid=(float)mysql_result($resultrd,0,'valor_desp_rd');//desperdicio
	$fecha=mysql_result($resultrd,0,'fecha_rd');
	$rollo_rd=mysql_result($resultrd,0,'int_rollo_rd');
	
	//RECUPERA LOS METROS AL ELIMINAR LOS KILOS DESPERDICIADOS 
	$sqlrp="SELECT int_kilos_prod_rp,int_total_kilos_rp, int_metro_lineal_rp FROM Tbl_reg_produccion WHERE id_op_rp=$id_op AND rollo_rp='$rollo_rd' AND id_proceso_rp='4'"; 
	$resultrp=mysql_query($sqlrp); 	
	$kilosconsumo=mysql_result($resultrp,0,'int_total_kilos_rp');
	$metrosentran=mysql_result($resultrp,0,'int_metro_lineal_rp');
	$kilosTotales= ($kilosconsumo + $cantid);

	//$nuevosMetros = round($kilosTotales * $metrosentran / $kilosconsumo);  

	$sqlresta="UPDATE Tbl_reg_produccion SET int_kilos_desp_rp = int_kilos_desp_rp - $cantid, int_metro_lineal_rp=$metrosentran, kiloFaltante_rp=kiloFaltante_rp+$cantid WHERE id_op_rp = '$id_op' AND rollo_rp= '$rollo_rd'  AND id_proceso_rp='4' ORDER BY id_rp DESC";
	$resultresta=mysql_query($sqlresta);


	$sqlresta="UPDATE TblSelladoRollo SET kilos_r = $kilosconsumo, kilopendiente_r=(kilopendiente_r+$cantid) WHERE id_op_r = '$id_op' AND rollo_r = '$rollo_rd' ORDER BY id_r DESC";//se deja de esta forma id_op_r = '$id_op' AND rollo_r= $rollo_rd para actualizar en cascada los kilos iniciales de todos los rollos parciales
	$resultresta=mysql_query($sqlresta);

	$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rds'";
	$resulrd=mysql_query($sqlrd);
	header("location:produccion_registro_sellado_edit.php?id_r=$id_reRS");
}
}




//SELLADO PARCIAL EDIT ELIMINA TIEMPOS Y DESPERDICIOS Y MATERIA PRIMA
if($id_rtsp!='') {
	$id_reRSp=$id_reRSp;//variable del rollo para edit
	$sqlrt="SELECT * FROM Tbl_reg_tiempo WHERE id_rt='$id_rtsp'";
	$resultrt= mysql_query($sqlrt);
	$numert= mysql_num_rows($resultrt);
	if($numert >='1') {
		$id_op=mysql_result($resultrt,0,'op_rt');
		$id_rtdel=mysql_result($resultrt,0,'id_rt');
		$fecha=mysql_result($resultrt,0,'fecha_rt');	
		$rollo=mysql_result($resultrt,0,'int_rollo_rt');
		$cantid=mysql_result($resultrt,0,'valor_tiem_rt');

	//importantisimo ya que devuelve la cantidad al rodamiento  
		$sqlresta="SELECT SEC_TO_TIME(TIME_TO_SEC(`rodamiento_rp`) + ($cantid*60)) as trodam 
		FROM `Tbl_reg_produccion`
		WHERE `id_op_rp`='$id_op' AND `rollo_rp` = '$rollo' AND id_proceso_rp='4'"; 
		$resultresta=mysql_query($sqlresta); 
		$numeresta= mysql_num_rows($resultresta);
	$trodam=mysql_result($resultresta,0,'trodam');//sumada del nuevo rodamiento

	$sqlresta="UPDATE Tbl_reg_produccion SET horas_muertas_rp = horas_muertas_rp - $cantid, rodamiento_rp = '$trodam' WHERE id_op_rp = '$id_op' AND rollo_rp= $rollo AND id_proceso_rp='4' ORDER BY id_rp DESC";
	$resultresta=mysql_query($sqlresta);

	$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rtsp'";
	$resulrt=mysql_query($sqlrt);

	header("location:produccion_registro_sellado_parcial_edit.php?id_r=$id_reRSp");
}
}
if($id_rpsp!='') {
	$id_reRSp=$id_reRSp;//variable del rollo para edit
	$sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpsp'";
	$resultrp= mysql_query($sqlrp);
	$numerp= mysql_num_rows($resultrp);
	if($numerp >='1') {
		$id_op=mysql_result($resultrp,0,'op_rtp');
		$id_rpdel=mysql_result($resultrp,0,'id_rt');
		$fecha=mysql_result($resultrp,0,'fecha_rtp');
		$rollo_rt=mysql_result($resultrp,0,'int_rollo_rtp');
		$cantid=mysql_result($resultrp,0,'valor_prep_rtp');	

	$sqlresta="UPDATE Tbl_reg_produccion SET horas_prep_rp = horas_prep_rp - $cantid WHERE id_op_rp = '$id_op' AND rollo_rp = $rollo_rt AND id_proceso_rp='4' ORDER BY id_rp DESC";//rodamiento_rp=rodamiento_rp-$cantid
	$resultresta=mysql_query($sqlresta);

	$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpsp'";
	$resulrp=mysql_query($sqlrp);
	header("location:produccion_registro_sellado_parcial_edit.php?id_r=$id_reRSp");
}
}
if($id_rdsp!='') {
	$id_reRSp=$id_reRSp;//variable del rollo para edit
	$sqlrd="SELECT * FROM Tbl_reg_desperdicio WHERE id_rd='$id_rdsp'";
	$resultrd= mysql_query($sqlrd);
	$numerd= mysql_num_rows($resultrd);
	if($numerd >='1') {
		$id_rdei;
		$id_op=mysql_result($resultrd,0,'op_rd');
		$id_rddel=mysql_result($resultrd,0,'id_rd');
	$cantid=(float)mysql_result($resultrd,0,'valor_desp_rd');//desperdicio
	$fecha=mysql_result($resultrd,0,'fecha_rd');
	$rollo_rd=mysql_result($resultrd,0,'int_rollo_rd');
	
	//RECUPERA LOS METROS AL ELIMINAR LOS KILOS DESPERDICIADOS 
	$sqlrp="SELECT int_kilos_prod_rp,int_total_kilos_rp, int_metro_lineal_rp FROM Tbl_reg_produccion WHERE id_op_rp=$id_op AND rollo_rp='$rollo_rd' AND id_proceso_rp='4'"; 
	$resultrp=mysql_query($sqlrp); 	
	$kilosconsumo=mysql_result($resultrp,0,'int_total_kilos_rp');
	$metrosentran=mysql_result($resultrp,0,'int_metro_lineal_rp');
	$kilosTotales= ($kilosconsumo + $cantid);

	$sqlresta="UPDATE Tbl_reg_produccion SET int_kilos_desp_rp = int_kilos_desp_rp - $cantid, int_metro_lineal_rp=$metrosentran, kiloFaltante_rp=kiloFaltante_rp+$cantid WHERE id_op_rp = '$id_op' AND rollo_rp= '$rollo_rd'  AND id_proceso_rp='4' ORDER BY id_rp DESC";
	$resultresta=mysql_query($sqlresta);

	$sqlresta="UPDATE TblSelladoRollo SET  kilopendiente_r=(kilopendiente_r+$cantid) WHERE id_op_r = '$id_op' AND rollo_r= '$rollo_rd' AND fechaI_r='$fecha' ORDER BY id_r DESC";//se deja de esta forma id_op_r = '$id_op' AND rollo_r= $rollo_rd  and fechaI_r='$fecha' para actualizar el rollo
	$resultresta=mysql_query($sqlresta);

	$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rdsp'";
	$resulrd=mysql_query($sqlrd);
	header("location:produccion_registro_sellado_parcial_edit.php?id_r=$id_reRSp");
}
}
?>