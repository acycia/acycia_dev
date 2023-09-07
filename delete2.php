<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php


$conexion = new ApptivaDB();

mysql_select_db($database_conexion1, $conexion1);


if(count($_GET['iddir_bodega'])) {
  $i = $_GET['iddir_bodega'];
$sqlegps="DELETE FROM tbl_destinatarios WHERE id = '$i'";
$resultegps=mysql_query($sqlegps);
//echo "Eliminado correctamente: ".$numcn;

}
/*----------VARIABLES------------*/
/*----------EXTRUSION-------------*/
/*$id_rt=$_GET['id_rt'];
$id_rp=$_GET['id_rp'];
$id_rd=$_GET['id_rd'];
$id_ip=$_GET['id_ip'];*/
//edit
$id_rte=$_GET['id_rte'];
$id_rpe=$_GET['id_rpe'];
$id_rde=$_GET['id_rde'];
$id_ipe=$_GET['id_ipe'];
/*----------IMPRESION-------------*/
/*$id_rti=$_GET['id_rti'];
$id_rpi=$_GET['id_rpi'];
$id_rdi=$_GET['id_rdi'];
$id_ipi=$_GET['id_ipi'];*/
//edit
 
 /*$id_ipei=$_GET['id_ipei']; */
/*----------SELLADO-------------*/
$id_rts=$_GET['id_rts'];
$id_rps=$_GET['id_rps'];
$id_rds=$_GET['id_rds'];
$id_ips=$_GET['id_ips'];
$id_ipsp=$_GET['id_ipsp'];
/*-SELLADO-TIQUETES------------*/
$id_tn=$_GET['id_tn'];
$id_tncaja=$_GET['id_tncaja'];
$id_tnpxc=$_GET['id_tnpxc'];
/*----------GESTION COTIZACIONES------------*/
$delete_bolsa=$_GET['delete_bolsa'];
$delete_bolsa_ref=$_GET['delete_bolsa_ref'];
$delete_lamina=$_GET['delete_lamina'];
$delete_lamina_ref=$_GET['delete_lamina_ref'];
$delete_mp=$_GET['delete_mp'];
$delete_mp_ref=$_GET['delete_mp_ref'];
$delete_pl=$_GET['delete_pl'];
$delete_pl_ref=$_GET['delete_pl_ref'];
$delete_ref_mp=$_GET['id_mp_vta'];
$id_refcliente= $_GET['id_refcliente'];
$tipo=$_GET['tipo'];
/*----GESTION DISEÑO Y DESARROLLO---*/
$id_ref_b=$_GET['id_ref_b'];
$id_ref_l=$_GET['id_ref_l'];
$id_ref_m=$_GET['id_ref_m'];
$id_ref_p=$_GET['id_ref_p'];

///elimino todos los paquetes de una caja en sellado
if($_GET['id_opxcaja'] !='' && $_GET['cajatotal'] !='' && $_GET['vistas'] !='' ) {

	    
      	$sqldet="DELETE FROM tbl_faltantes WHERE id_op_f='".$_GET['id_opxcaja']."' AND int_caja_f='".$_GET['cajatotal']."'";
      	$resultdet=mysql_query($sqldet);

     
      	$sqltn="DELETE FROM tbl_tiquete_numeracion WHERE int_op_tn= '".$_GET['id_opxcaja']."' AND int_caja_tn='".$_GET['cajatotal']."' ";
      	$resultn=mysql_query($sqltn);
       

       	//CODIGO PARA EVALUAR EXISTEN MAS REGISTROS DE ESTA O.P
      	/*$sqltnum ="SELECT int_op_tn FROM tbl_tiquete_numeracion WHERE int_op_tn='".$_GET['id_opxcaja']."'";
      	$resultnum = mysql_query($sqltnum);
      	$quedanregistros = mysql_num_rows($resultnum);  
      	$existenregistros = mysql_result($resultnum,0,'int_op_tn')  ; 
          
        
        if( $existenregistros !='') {*/

      	//ULTIMA CAJA
      	$sqltnumC="SELECT id_tn,int_op_tn,int_hasta_tn,int_caja_tn,int_paquete_tn FROM tbl_tiquete_numeracion WHERE int_op_tn='".$_GET['id_opxcaja']."' ORDER BY int_caja_tn DESC,int_paquete_tn DESC LIMIT 1";
      	$resultnumC = mysql_query($sqltnumC);
      	$numertnumC = mysql_num_rows($resultnumC);
      	$cambioid_tn= mysql_result($resultnumC,0,'id_tn');
      	$cambioint_hasta= mysql_result($resultnumC,0,'int_hasta_tn');
      	$cambioint_op_tn= mysql_result($resultnumC,0,'int_op_tn');
      	$cambiodecaja= mysql_result($resultnumC,0,'int_caja_tn');
      	$cambiodePaq= mysql_result($resultnumC,0,'int_paquete_tn');        
 
        //ACTUALIZA CAJA PARA EL LISTADO Y ACTUALIZO LA NUMERACION FINAL 
        $sqlcaja="UPDATE tbl_numeracion SET id_tn_n = '$cambioid_tn', int_hasta_n='$cambioint_hasta', int_paquete_n='$cambiodePaq', int_caja_n = '$cambiodecaja' WHERE int_op_n='$cambioint_op_tn'";
        $resultcaja= mysql_query($sqlcaja);	  


        /*}else{
      
        $sqlexit=mysql_query("DELETE FROM tbl_numeracion WHERE int_op_n = '".$_GET['id_opxcaja']."' ");
      	 
        }*/


      	/*if($quedanregistros <= '0') { 
            header("location:sellado_numeracion_listado.php"); 
	    }else{*/
            $idop=$_GET['id_opxcaja'];
             //sellado_control_numeracion_edit.php?id_op=9074
	         header("location:sellado_cajas.php?id_op=$idop");
	    // }
 
 

}

/*----------EJECUCION DEL CODIGO BOLSA------------*/
if($delete_bolsa!=''&& $delete_bolsa_ref!=''&&$id_refcliente!=''){
/*$sqlbolsa="UPDATE Tbl_cotiza_bolsa SET B_estado='0' WHERE N_cotizacion='$delete_bolsa' AND B_estado='1'";
$resultbolsa=mysql_query($sqlbolsa);*/
/*$sqlrefeb="DELETE FROM Tbl_cliente_referencia WHERE id_refcliente='$id_refcliente'";
$resultrefeb=mysql_query($sqlrefeb);*/
/*$sqlcotizm="DELETE FROM Tbl_cotizaciones WHERE N_cotizacion='$delete_bolsa' ";
$resultcotizm=mysql_query($sqlcotizm);*/
$sqlcotiz="UPDATE Tbl_cotiza_bolsa SET B_estado='2' WHERE N_cotizacion='$delete_bolsa' and N_referencia_c='$delete_bolsa_ref' AND B_estado='1'";
$resultcotiz=mysql_query($sqlcotiz);
/*$sqltex="DELETE FROM Tbl_cotiza_bolsa_obs WHERE N_cotizacion='$delete_bolsa' and N_referencia_c='$delete_bolsa_ref' ";
$resulttex=mysql_query($sqltex);*/
header("location:cotizacion_g_bolsa_vista.php?N_cotizacion=$delete_bolsa&tipo=$tipo");} 
/*----------EJECUCION DEL CODIGO LAMINAS------------*/
if($delete_lamina!=''&&$delete_lamina_ref!=''&&$id_refcliente!=''){
	
/*$sqllamina="UPDATE Tbl_cotiza_laminas SET B_estado='0' WHERE N_cotizacion='$delete_lamina' AND B_estado='1'";
$resultlamina=mysql_query($sqllamina);*/
/*$sqllaminarefe="DELETE FROM Tbl_cliente_referencia WHERE id_refcliente='$id_refcliente'";
$resultlaminarefe=mysql_query($sqllaminarefe);*/
/*$sqllaminacotizm="DELETE FROM Tbl_cotizaciones WHERE N_cotizacion='$delete_lamina' ";
$resultlaminacotizm=mysql_query($sqllaminacotizm);*/
$sqllaminacotiz="UPDATE Tbl_cotiza_laminas SET B_estado='2' WHERE N_cotizacion='$delete_lamina' and N_referencia_c='$delete_lamina_ref'";
$resultlaminacotiz=mysql_query($sqllaminacotiz);
/*$sqllaminatex="DELETE FROM Tbl_cotiza_lamina_obs WHERE N_cotizacion='$delete_lamina'and N_referencia_c='$delete_lamina_ref'";
$resultlaminatex=mysql_query($sqllaminatex);*/
header("location:cotizacion_g_lamina_vista.php?N_cotizacion=$delete_lamina&tipo=$tipo");}
/*----------EJECUCION DEL CODIGO MATERIA PRIMA------------*/
if($delete_mp!=''&& $delete_mp_ref!=''&&$id_refcliente!=''){
/*$sqlmp="UPDATE Tbl_cotiza_materia_p SET B_estado='0' WHERE N_cotizacion='$delete_mp' AND B_estado='1'";
$resultmp=mysql_query($sqlmp);*/
/*$sqlmprefe="DELETE FROM Tbl_cliente_referencia WHERE id_refcliente='$id_refcliente'";
$resultmprefe=mysql_query($sqlmprefe);*/
/*$sqlmpcotizm="DELETE FROM Tbl_cotizaciones WHERE N_cotizacion='$delete_mp' ";
$resultmpcotizm=mysql_query($sqlmpcotizm);*/
$sqlmpcotiz="UPDATE Tbl_cotiza_materia_p SET B_estado='2' WHERE N_cotizacion='$delete_mp' and N_referencia_c='$delete_mp_ref'";
$resultmpcotiz=mysql_query($sqlmpcotiz);
/*$sqlmptex="DELETE FROM Tbl_cotiza_materia_p_obs WHERE N_cotizacion='$delete_mp'and N_referencia_c='$delete_mp_ref'";
$resultmptex=mysql_query($sqlmptex);*/
header("location:cotizacion_g_materiap_vista.php?N_cotizacion=$delete_mp&tipo=$tipo");}
/*----------EJECUCION DEL CODIGO PACKING LIST------------*/
if($delete_pl!=''&& $delete_pl_ref!=''&&$id_refcliente!=''){
/*$sqlpl="UPDATE Tbl_cotiza_packing SET B_estado='0' WHERE N_cotizacion='$delete_pl' AND B_estado='1'";
$resultpl=mysql_query($sqlpl);*/
/*$sqlplrefe="DELETE FROM Tbl_cliente_referencia WHERE id_refcliente='$id_refcliente'";
$resulpltrefe=mysql_query($sqlplrefe);*/
$sqlplcotiz="UPDATE Tbl_cotiza_packing SET B_estado='2' WHERE N_cotizacion='$delete_pl' and N_referencia_c='$delete_pl_ref'";
$resulpltcotiz=mysql_query($sqlplcotiz);
/*$sqlpltex="DELETE FROM Tbl_cotiza_packing_obs WHERE N_cotizacion='$delete_pl' and N_referencia_c='$delete_pl_ref' ";
$resultpltex=mysql_query($sqlpltex);*/
header("location:cotizacion_g_packing_vista.php?N_cotizacion=$delete_pl&tipo=$tipo");}
/*DELETE EN LA BD Tbl_mp_vta*/
if($delete_ref_mp!='') {
$sqldato="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$delete_ref_mp'";
$resultdato=mysql_query($sqldato);
$nombre1=mysql_result($resultdato,0,'Str_linc_archivo');
if($nombre1!='') { unlink("archivosc/archivos_pdf_mp/".$nombre1); }
$sqlegp="DELETE FROM Tbl_mp_vta WHERE id_mp_vta='$delete_ref_mp'";
$resultegp=mysql_query($sqlegp);
header('location:cotizacion_general_materia_prima_ref_nueva.php'); }

/*if($delete_ref_mp!=''){
$sqldel = "DELETE  FROM Tbl_mp_vta WHERE id_mp_vta='$delete_ref_mp'";
$Resultdel = mysql_query($sqldel);
header('location:cotizacion_general_materia_prima_ref_nueva.php');}*/

/*DELETE REFERENCIA BOLSAS*/
if($id_ref_b!='') {
$sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref_b'";
$resultref= mysql_query($sqlref);
$numref= mysql_num_rows($resultref);
if($numref >='1') {
$n_egp=mysql_result($resultref,0,'n_egp_ref');
$sqlegps="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegps=mysql_query($sqlegps); }
//archivos ref
//if($n_egp!='') {
$sqldato="SELECT * FROM Tbl_egp WHERE n_egp='$n_egp'";
$resultdato=mysql_query($sqldato);
$nombre1=mysql_result($resultdato,0,'archivo1');
if($nombre1!='') { unlink("egpbolsa/".$nombre1); }
$nombre2=mysql_result($resultdato,0,'archivo2');
if($nombre2!='') { unlink("egpbolsa/".$nombre2); }
$nombre3=mysql_result($resultdato,0,'archivo3');
if($nombre3!='') { unlink("egpbolsa/".$nombre3); }
$sqlegp="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegp=mysql_query($sqlegp);
/*$sqlrefcliente="DELETE FROM Tbl_cliente_referencia WHERE id_ref='$id_ref_b'";
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
$resultft=mysql_query($sqlft);*/
$sqlref="UPDATE Tbl_referencia SET estado_ref='0' WHERE id_ref='$id_ref_b'";
$resultref=mysql_query($sqlref);
header("location:cotizacion_general_menu.php");}

//ELIMINAR REFERENCIA LAMINAS
if($id_ref_l!='') {
$sqlrefl="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref_l'";
$resultrefl= mysql_query($sqlrefl);
$numrefl= mysql_num_rows($resultrefl);
if($numrefl >='1') {
$n_egp=mysql_result($resultrefl,0,'n_egp_ref');
$sqlegpsl="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegpsl=mysql_query($sqlegpsl); }
$sqlegpl="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegpl=mysql_query($sqlegpl);
$sqlrefl="UPDATE Tbl_referencia SET estado_ref='0' WHERE id_ref='$id_ref_l'";
$resultrefl=mysql_query($sqlrefl);
header("location:cotizacion_general_menu.php");}
//ELIMINAR REFERENCIA MATERIA PRIMA
if($id_ref_m!='') {
$sqlrefm="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref_m'";
$resultrefm= mysql_query($sqlrefm);
$numrefm= mysql_num_rows($resultrefm);
if($numrefm >='1') {
$n_egp=mysql_result($resultrefm,0,'n_egp_ref');
$sqlegpsm="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegpsm=mysql_query($sqlegpsm); }
$sqlegpm="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegpm=mysql_query($sqlegpm);
$sqlrefm="UPDATE Tbl_referencia SET estado_ref='0' WHERE id_ref='$id_ref_m'";
$resultrefm=mysql_query($sqlrefm);
header("location:cotizacion_general_menu.php");}
//ELIMINAR REFERENCIA PACKING LIST
if($id_ref_p!='') {
$sqlrefp="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref_p'";
$resultrefp= mysql_query($sqlrefp);
$numrefp= mysql_num_rows($resultrefp);
if($numrefp >='1') {
$n_egp=mysql_result($resultrefp,0,'n_egp_ref');
$sqlegpsp="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegpsp=mysql_query($sqlegpsp); }
$sqlegpp="UPDATE Tbl_egp SET estado_egp='0' WHERE n_egp='$n_egp' AND estado_egp='1'";
$resultegpp=mysql_query($sqlegpp);
$sqlrefp="UPDATE Tbl_referencia SET estado_ref='0' WHERE id_ref='$id_ref_p'";
$resultrefp=mysql_query($sqlrefp);
header("location:cotizacion_general_menu.php");}

//EXTRUSION ELIMINA TIEMPOS Y DESPERDICIOS
/*if($id_rt!='') {
$sqlrt="SELECT * FROM  Tbl_reg_tiempo WHERE id_rt='$id_rt'";
$resultrt= mysql_query($sqlrt);
$numert= mysql_num_rows($resultrt);
if($numert >='1') {
	$id_op=mysql_result($resultrt,0,'op_rt');
	$id_rtdel=mysql_result($resultrt,0,'id_rt');
	$fecha=mysql_result($resultrt,0,'fecha_rt');
$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rtdel'";
$resulrt=mysql_query($sqlrt);
header("location:produccion_registro_extrusion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}
if($id_rp!='') {
$sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rp'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
	$id_op=mysql_result($resultrp,0,'op_rtp');
	$id_rpdel=mysql_result($resultrp,0,'id_rt');
	$fecha=mysql_result($resultrp,0,'fecha_rtp');
$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpdel'";
$resulrp=mysql_query($sqlrp);
header("location:produccion_registro_extrusion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}
if($id_rd!='') {
$sqlrd="SELECT * FROM  Tbl_reg_desperdicio WHERE id_rd='$id_rd'";
$resultrd= mysql_query($sqlrd);
$numerd= mysql_num_rows($resultrd);
if($numerd >='1') {
	$id_op=mysql_result($resultrd,0,'op_rd');
	$id_rddel=mysql_result($resultrd,0,'id_rd');
	$fecha=mysql_result($resultrd,0,'fecha_rd');
$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rddel'";
$resulrd=mysql_query($sqlrd);
header("location:produccion_registro_extrusion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}
if($id_ip!='') {
$sqlrp="SELECT * FROM  Tbl_reg_kilo_producido WHERE id_rkp='$id_ip'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
	$id_op=mysql_result($resultrp,0,'op_rp');
	$id_rpdel=mysql_result($resultrp,0,'id_rkp');
	$fecha=mysql_result($resultrp,0,'fecha_rkp');
$sqlupdate="UPDATE  Tbl_reg_produccion SET int_total_kilos_rp='0' WHERE id_op_rp='$id_op' AND fecha_ini_rp='$fecha'";
$resultup= mysql_query($sqlupdate);

$sqlrp="DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_rpdel'";
$resulrp=mysql_query($sqlrp);
header("location:produccion_registro_extrusion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}*/
//EXTRUSION EDITA TIEMPOS Y DESPERDICIOS


//GENERAL
if( $_GET['idtiemprollo'] !='' && $_GET['valoretorno']) {
 
  $column = $_GET['columntabla'];
	$idretorno = $_GET['idretorno'];
	$valoretorno=$_GET['valoretorno'];
	$paginaext=$_GET['paginaext'];	
	$tabla = $_GET['tabla'];
 
	if($_GET['idtiemprollo'] !='') {
		
 
		$sqlrd="DELETE FROM $tabla WHERE $column=".$_GET['valorcolumna'];
		$resulrd=mysql_query($sqlrd);

		header("location:".$paginaext."?".$idretorno."=".$valoretorno);
	}
  
}

if($id_rte!='') {
$sqlrt="SELECT * FROM  Tbl_reg_tiempo WHERE id_rt='$id_rte'";
$resultrt= mysql_query($sqlrt);
$numert= mysql_num_rows($resultrt);
if($numert >='1') {
	$id_op=mysql_result($resultrt,0,'op_rt');
	$id_rtdel=mysql_result($resultrt,0,'id_rt');
	$fecha=mysql_result($resultrt,0,'fecha_rt');
	$cantid=mysql_result($resultrt,0,'valor_tiem_rt');
	//importantisimo ya que devuelve la cantidad al rodamiento  

		$sqlID="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp = '$id_op' AND id_proceso_rp='1' ";
		$resultID= mysql_query($sqlID);
		$numeID= mysql_num_rows($resultID);
		if($numeID >='1') {
	      $id_rp=mysql_result($resultID,0,'id_rp');
		}

      $sqlupresta="UPDATE Tbl_reg_produccion SET horas_muertas_rp = horas_muertas_rp - $cantid, rodamiento_rp = SEC_TO_TIME(TIME_TO_SEC(`rodamiento_rp`) + ($cantid*60)) WHERE id_op_rp = '$id_op' AND id_proceso_rp='1' ORDER BY id_rp DESC";
    $resultupresta=mysql_query($sqlupresta);	
	
$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rtdel'";
$resulrt=mysql_query($sqlrt);
header("location:produccion_registro_extrusion_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}
if($id_rpe!='') {
$sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpe'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
	$id_op=mysql_result($resultrp,0,'op_rtp');
	$id_rpdel=mysql_result($resultrp,0,'id_rt');
	$fecha=mysql_result($resultrp,0,'fecha_rtp');
	$cantid=mysql_result($resultrp,0,'valor_prep_rtp');	

		$sqlID="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp = '$id_op' AND id_proceso_rp='1' ";
		$resultID= mysql_query($sqlID);
		$numeID= mysql_num_rows($resultID);
		if($numeID >='1') {
	      $id_rp=mysql_result($resultID,0,'id_rp');
		}

$sqlresta="UPDATE Tbl_reg_produccion SET horas_prep_rp = horas_prep_rp - $cantid WHERE id_op_rp = '$id_op' AND id_proceso_rp='1' ORDER BY id_rp DESC";
 $resultresta=mysql_query($sqlresta);	
	
$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpdel'";
$resulrp=mysql_query($sqlrp);
header("location:produccion_registro_extrusion_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}

if($id_rde!='') {
$sqlrd="SELECT * FROM  Tbl_reg_desperdicio WHERE id_rd='$id_rde'";
$resultrd= mysql_query($sqlrd);
$numerd= mysql_num_rows($resultrd);
if($numerd >='1') {
	$id_op=mysql_result($resultrd,0,'op_rd');
	$id_rddel=mysql_result($resultrd,0,'id_rd');
	$fecha=mysql_result($resultrd,0,'fecha_rd');	

		$sqlID="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp = '$id_op' AND id_proceso_rp='1' ";
		$resultID= mysql_query($sqlID);
		$numeID= mysql_num_rows($resultID);
		if($numeID >='1') {
	      $id_rp=mysql_result($resultID,0,'id_rp');
		}

$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rddel'";
$resulrd=mysql_query($sqlrd);
header("location:produccion_registro_extrusion_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}

if($id_ipe!='') {
$sqlre="SELECT * FROM  Tbl_reg_kilo_producido WHERE id_rkp='$id_ipe'";
$resultre= mysql_query($sqlre);
$numere= mysql_num_rows($resultre);
if($numere >='1') {
	$id_op=mysql_result($resultre,0,'op_rp');
	$fecha=mysql_result($resultre,0,'fecha_rkp');
	$id_insumo=mysql_result($resultre,0,'id_rpp_rp');
	$cantidad=mysql_result($resultre,0,'valor_prod_rp');
	
	
$sqlupdate="UPDATE Tbl_reg_produccion SET int_kilos_prod_rp = int_kilos_prod_rp-'$cantidad', int_total_kilos_rp = int_total_kilos_rp - '$cantidad' WHERE id_op_rp='$id_op' AND id_proceso_rp='1'";
$resultup= mysql_query($sqlupdate);

$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$cantidad' WHERE Codigo = '$id_insumo'";
$resultinv=mysql_query($sqlinv);

	$sqlID="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp = '$id_op' AND id_proceso_rp='1' ";
	$resultID= mysql_query($sqlID);
	$numeID= mysql_num_rows($resultID);
	if($numeID >='1') {
      $id_rp=mysql_result($resultID,0,'id_rp');
	}
$sqlrip="DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_ipe'";
$resulrip=mysql_query($sqlrip);
header("location:produccion_registro_extrusion_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}

//IMPRESION ELIMINA TIEMPOS Y DESPERDICIOS
/*if($id_rti!='') {
$sqlrt="SELECT * FROM  Tbl_reg_tiempo WHERE id_rt='$id_rti'";
$resultrt= mysql_query($sqlrt);
$numert= mysql_num_rows($resultrt);
if($numert >='1') {
	$id_op=mysql_result($resultrt,0,'op_rt');
	$id_rtdel=mysql_result($resultrt,0,'id_rt');
	$fecha=mysql_result($resultrt,0,'fecha_rt');	
$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rtdel'";
$resulrt=mysql_query($sqlrt);
header("location:produccion_registro_impresion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}*/
/*if($id_rpi!='') {
$sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpi'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
	$id_op=mysql_result($resultrp,0,'op_rtp');
	$id_rpdel=mysql_result($resultrp,0,'id_rt');
	$fecha=mysql_result($resultrp,0,'fecha_rtp');	
$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpdel'";
$resulrp=mysql_query($sqlrp);
header("location:produccion_registro_impresion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}*/
/*if($id_rdi!='') {
$sqlrd="SELECT * FROM  Tbl_reg_desperdicio WHERE id_rd='$id_rdi'";
$resultrd= mysql_query($sqlrd);
$numerd= mysql_num_rows($resultrd);
if($numerd >='1') {
	$id_op=mysql_result($resultrd,0,'op_rd');
	$id_rddel=mysql_result($resultrd,0,'id_rd');
	$fecha=mysql_result($resultrd,0,'fecha_rd');	
	
$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rddel'";
$resulrd=mysql_query($sqlrd);
header("location:produccion_registro_impresion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}*/
/*if($id_ipi!='') {
if(count($id_ipi)) {
foreach ($id_ipi as $v) {
$sqlrp="SELECT * FROM Tbl_reg_kilo_producido WHERE id_rkp='$v'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
	for($i=0; $i<$numerp; $i++)
    {
	$id_op=mysql_result($resultrp,$i,'op_rp');
	$id_rpdel=mysql_result($resultrp,$i,'id_rkp');
	$fecha=mysql_result($resultrp,$i,'fecha_rkp');		
	}
$sqlrp="DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp='$v'";
$resulrp=mysql_query($sqlrp);
}
}
header("location:produccion_registro_impresion_add.php?id_op=$id_op&fecha_ini_rp=$fecha");
  }
}*/

/*if($id_ipei!='') {
$sqlre="SELECT * FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_ipei'";
$resultre= mysql_query($sqlre);
$numere= mysql_num_rows($resultre);
if($numere >='1') {
	$id_op=mysql_result($resultrp,0,'op_rp');
	$id_rpdel=mysql_result($resultrp,0,'id_rkp');
	$fecha=mysql_result($resultrp,0,'fecha_rkp');

	$sqlI="SELECT id_r,fechaI_r FROM TblImpresionRollo WHERE fechaI_r='$fecha'"; 
	$resultI=mysql_query($sqlI); 		
	$id_r=mysql_result($resultI,0,'id_r');
			
$sqlrip="DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_rpidel'";
$resulrip=mysql_query($sqlrip);
header("location:produccion_registro_impresion_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}*/
//SELLADO ELIMINA TIEMPOS Y DESPERDICIOS Y MATERIA PRIMA
if($id_rts!='') {
 $sqlrt="SELECT * FROM  Tbl_reg_tiempo WHERE id_rt='$id_rts'";
$resultrt= mysql_query($sqlrt);
$numert= mysql_num_rows($resultrt);
if($numert >='1') {
	$id_op=mysql_result($resultrt,0,'op_rt');
	$id_rtdel=mysql_result($resultrt,0,'id_rt');
	$fecha=mysql_result($resultrt,0,'fecha_rt');	
	$rollo=mysql_result($resultrt,0,'int_rollo_rt');
	
$sqlpro="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp='$id_op' AND fecha_ini_rp='$fecha'";
$resultpro= mysql_query($sqlpro);
$numepro = mysql_num_rows($resultpro);
if($numepro >='1') {
	$id_rp=mysql_result($resultpro,0,'id_rp');
}	
$sqlrt="DELETE FROM Tbl_reg_tiempo WHERE id_rt='$id_rtdel'";
$resulrt=mysql_query($sqlrt);
header("location:produccion_registro_sellado_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}
if($id_rps!='') {
$sqlrp="SELECT * FROM  Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rps'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
	$id_op=mysql_result($resultrp,0,'op_rtp');
	$id_rpdel=mysql_result($resultrp,0,'id_rt');
	$fecha=mysql_result($resultrp,0,'fecha_rtp');
	$rollo=mysql_result($resultrp,0,'int_rollo_rtp');
	
$sqlpro="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp='$id_op' AND fecha_ini_rp='$fecha'";
$resultpro= mysql_query($sqlpro);
$numepro = mysql_num_rows($resultpro);
if($numepro >='1') {
	$id_rp=mysql_result($resultpro,0,'id_rp');
}		
$sqlrp="DELETE FROM Tbl_reg_tiempo_preparacion WHERE id_rt='$id_rpdel'";
$resulrp=mysql_query($sqlrp);
header("location:produccion_registro_sellado_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}
if($id_rds!='') {
$sqlrd="SELECT * FROM  Tbl_reg_desperdicio WHERE id_rd='$id_rds'";
$resultrd= mysql_query($sqlrd);
$numerd= mysql_num_rows($resultrd);
if($numerd >='1') {
	$id_op=mysql_result($resultrd,0,'op_rd');
	$id_rddel=mysql_result($resultrd,0,'id_rd');
	$fecha=mysql_result($resultrd,0,'fecha_rd');
	$rollo=mysql_result($resultrd,0,'int_rollo_rtp');	
$sqlpro="SELECT id_rp FROM  Tbl_reg_produccion WHERE id_op_rp='$id_op' AND fecha_ini_rp='$fecha'";
$resultpro= mysql_query($sqlpro);
$numepro = mysql_num_rows($resultpro);
if($numepro >='1') {
	$id_rp=mysql_result($resultpro,0,'id_rp');
}		
$sqlrd="DELETE FROM Tbl_reg_desperdicio WHERE id_rd='$id_rddel'";
$resulrd=mysql_query($sqlrd);
header("location:produccion_registro_sellado_edit.php?id_op=$id_op&id_rp=$id_rp");
  }
}
if($id_ips!='') {
 $id_rs = $_GET['id_rs'];	//id  del rollo
$sqlrp="SELECT * FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_ips'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
 $id_insumo=mysql_result($resultrp,0,'id_rpp_rp');
 $cantidad=mysql_result($resultrp,0,'valor_prod_rp');

$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$cantidad' WHERE Codigo = '$id_insumo'";
$resultinv=mysql_query($sqlinv);
 	
$sqlrp="DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_ips'";
$resulrp=mysql_query($sqlrp);

  }
  header("location:produccion_registro_sellado_edit.php?id_r=$id_rs");
}

if($id_ipsp!='') {
$id_rs = $_GET['id_rsp'];	//id  del rollo
$sqlrp="SELECT * FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_ipsp'";
$resultrp= mysql_query($sqlrp);
$numerp= mysql_num_rows($resultrp);
if($numerp >='1') {
 $id_op=mysql_result($resultrp,0,'op_rp');
 $id_insumo=mysql_result($resultrp,0,'id_rpp_rp');
 $cantidad=mysql_result($resultrp,0,'valor_prod_rp');

$sqlinv="UPDATE TblInventarioListado SET Salida = Salida - '$cantidad' WHERE Codigo = '$id_insumo'";
$resultinv=mysql_query($sqlinv);
 	
$sqlrp="DELETE FROM Tbl_reg_kilo_producido WHERE id_rkp='$id_ipsp'";
$resulrp=mysql_query($sqlrp);

  }
 header("location:produccion_registro_sellado_parcial_edit.php?id_op=$id_op&id_r=$id_rs");
}
//SELLADO ELIMINA TIQUETES DE SELLADO
if($id_tn!='') {
$sqltnn="SELECT * FROM Tbl_tiquete_numeracion WHERE id_tn='$id_tn'";
$resultnn= mysql_query($sqltnn);
$numertnn= mysql_num_rows($resultnn);
if($numertnn >='1') {
	$id=mysql_result($resultnn,0,'int_op_tn');
	$paquete=mysql_result($resultnn,0,'int_paquete_tn');
	$caja=mysql_result($resultnn,0,'int_caja_tn');
	$numeraIn=mysql_result($resultnn,0,'int_desde_tn');
$sqlf="DELETE FROM Tbl_faltantes WHERE id_op_f='$id' AND int_paquete_f='$paquete' AND int_caja_f='$caja'";
$resultf=mysql_query($sqlf);
$sqltn="DELETE FROM Tbl_tiquete_numeracion WHERE id_tn='$id_tn'";
$resultn=mysql_query($sqltn);
//ULTIMA CAJA
$sqltnumC="SELECT int_op_tn,int_caja_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id' ORDER BY int_caja_tn DESC";
$resultnumC = mysql_query($sqltnumC);
$numertnumC = mysql_num_rows($resultnumC);
$cambiodecaja= mysql_result($resultnumC,0,'int_caja_tn');

//CODIGO PARA EVALUAR SI QUEDO MAS PAQUETES SI ES EL ULTIMO CAMBIO ESTADO SIN PAQUETES
$sqltnum="SELECT int_op_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id'";
$resultnum = mysql_query($sqltnum);
$numertnum = mysql_num_rows($resultnum);
//ACTUALISA CAJA PARA EL LISTADO Y ACTUALIZO LA NUMERACION FINAL
$sqlcaja="UPDATE Tbl_numeracion SET int_hasta_n=$numeraIn, int_caja_n = $cambiodecaja WHERE int_op_n='$id'";
$resultcaja= mysql_query($sqlcaja);	

if($numertnum <= '0') {
$sqlexit="UPDATE Tbl_numeracion SET existeTiq_n = '0' WHERE int_op_n='$id'";
$resultexit= mysql_query($sqlexit);	

//ACTUALIZO DE NUEVO EL NUMERO INICIAL EN LA O.P 
   /*$updateINV = "UPDATE Tbl_orden_produccion SET numInicio_op='$numeraIn' WHERE id_op='$id'";
   $resultINV=mysql_query($updateINV);*/


header("location:sellado_numeracion_listado.php"); 
}else{
header("location:sellado_control_numeracion_edit.php?id_op=$id&int_caja_tn=$cambiodecaja&imprimirt=0");
}
  }
}
//SELLADO ELIMINA TIQUETES DE SELLADO ESPECIAL
if($id_tnpxc!='') {
 
 $sqltnn="SELECT * FROM Tbl_tiquete_numeracion WHERE id_tn='$id_tnpxc'";
$resultnn= mysql_query($sqltnn);
$numertnn= mysql_num_rows($resultnn);
if($numertnn >='1') {
	$id=mysql_result($resultnn,0,'int_op_tn');
	$paquete=mysql_result($resultnn,0,'int_paquete_tn');
	$caja=mysql_result($resultnn,0,'int_caja_tn');
	$numeraIn=mysql_result($resultnn,0,'int_desde_tn');
	$undxcaja = mysql_result($resultnn,0,'int_undxcaja_tn');
	$undxpaq= mysql_result($resultnn,0,'int_undxpaq_tn'); 



	$paqxcaja=($undxcaja/$undxpaq);
 

$sqlf="DELETE FROM Tbl_faltantes WHERE id_op_f='$id' AND int_paquete_f='$paquete' AND int_caja_f='$caja'";
$resultf=mysql_query($sqlf);
$sqltn="DELETE FROM Tbl_tiquete_numeracion WHERE id_tn='$id_tnpxc'";
$resultn=mysql_query($sqltn);

//ULTIMA CAJA
$sqltnumC="SELECT id_tn,fecha_ingreso_tn,int_desde_tn,int_hasta_tn,int_paquete_tn,int_op_tn,int_caja_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id' ORDER BY id_tn DESC LIMIT 1";
$resultnumC = mysql_query($sqltnumC);
$numertnumC = mysql_num_rows($resultnumC);
$cambiodecaja= mysql_result($resultnumC,0,'int_caja_tn'); 	
  $id_tn=mysql_result($resultnumC,0,'id_tn');
  $fecha_ingreso_tn=mysql_result($resultnumC,0,'fecha_ingreso_tn');
  $int_desde_tn=mysql_result($resultnumC,0,'int_desde_tn');
  $int_hasta_tn=mysql_result($resultnumC,0,'int_hasta_tn');
  $int_paquete_tn=mysql_result($resultnn,0,'int_paquete_tn');

//CODIGO PARA EVALUAR SI QUEDO MAS PAQUETES SI ES EL ULTIMO CAMBIO ESTADO SIN PAQUETES
$sqltnum="SELECT int_op_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$id'";
$resultnum = mysql_query($sqltnum);
$numertnum = mysql_num_rows($resultnum);
//ACTUALISA CAJA PARA EL LISTADO
$sqlcaja="UPDATE Tbl_numeracion SET id_tn_n=$id_tn, fecha_ingreso_n=$fecha_ingreso_tn, int_desde_n=$int_desde_tn,int_hasta_n=$int_hasta_tn, int_paquete_n=$int_paquete_tn, int_caja_n=$cambiodecaja WHERE int_op_n='$id'";
$resultcaja= mysql_query($sqlcaja);	  

if($numertnum == '') {
$sqlexit="UPDATE Tbl_numeracion SET id_tn_n=$id_tn, fecha_ingreso_n=$fecha_ingreso_tn, int_desde_n=$int_desde_tn,int_hasta_n=$int_hasta_tn, int_paquete_n=$int_paquete_tn, int_caja_n=$cambiodecaja, existeTiq_n = '0' WHERE int_op_n='$id'";
$resultexit= mysql_query($sqlexit);	

//ACTUALIZO DE NUEVO EL NUMERO INICIAL EN LA O.P 
   /*$updateINV = "UPDATE Tbl_orden_produccion SET numInicio_op='$numeraIn' WHERE id_op='$id'";
   $resultINV=mysql_query($updateINV);*/

header("location:sellado_numeracion_listado.php");
}else{
header("location:sellado_control_numeracion_edit_paqxcaja.php?id_op=$id&int_caja_tn=$cambiodecaja&NumeroPaqxCaja=$paqxcaja");
    }
  }
}


if($id_tncaja!='') {
$sqltnn="SELECT * FROM Tbl_tiquete_numeracion WHERE id_tn='$id_tncaja'";
$resultnn= mysql_query($sqltnn);
$numertnn= mysql_num_rows($resultnn);
if($numertnn >='1') {
	$idop=mysql_result($resultnn,0,'int_op_tn');  
	$numeraIn=mysql_result($resultnn,0,'int_desde_tn');
 
$sqltn="DELETE FROM Tbl_tiquete_numeracion WHERE id_tn='$id_tncaja'";
$resultn=mysql_query($sqltn);
//ULTIMA CAJA Y SERIA EL ANTERIOR PAQUETE
$sqltnumC="SELECT int_op_tn,int_caja_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$idop' ORDER BY int_caja_tn DESC";
$resultnumC = mysql_query($sqltnumC);
$numertnumC = mysql_num_rows($resultnumC);
$cambiodecaja= mysql_result($resultnumC,0,'int_caja_tn');

//CODIGO PARA EVALUAR SI QUEDO MAS PAQUETES SI ES EL ULTIMO CAMBIO ESTADO SIN PAQUETES
$sqltnum="SELECT int_op_tn FROM Tbl_tiquete_numeracion WHERE int_op_tn='$idop'";
$resultnum = mysql_query($sqltnum);
$numertnum = mysql_num_rows($resultnum);
//ACTUALISA CAJA PARA EL LISTADO Y ACTUALIZO LA NUMERACION FINAL
$sqlcaja="UPDATE Tbl_numeracion SET int_hasta_n=$numeraIn, int_caja_n = $cambiodecaja WHERE int_op_n='$idop'";
$resultcaja= mysql_query($sqlcaja);	

if($numertnum <= '0') {
$sqlexit="UPDATE Tbl_numeracion SET existeTiq_n = '0' WHERE int_op_n='$idop'";
$resultexit= mysql_query($sqlexit);	

//ACTUALIZO DE NUEVO EL NUMERO INICIAL EN LA O.P 
/*   $updateINV = "UPDATE Tbl_orden_produccion SET numInicio_op='$numeraIn' WHERE id_op='$idop'";
   $resultINV=mysql_query($updateINV);*/


header("location:sellado_totaltiqxcaja.php"); 
}else{
header("location:sellado_totaltiqxcaja.php?id_op=$idop");
}
  }
}





?>
