<?php require_once('Connections/conexion1.php');?>
<?php
mysql_select_db($database_conexion1, $conexion1);
$id_c=$_POST['id_c_cotiz'];
$cotiz=$_POST['n_cotiz'];
$sql2="UPDATE cotizacion_nueva SET cotizacion_nueva.estado_cn='0' WHERE cotizacion_nueva.n_cotiz_cn='$cotiz'";
$result2=mysql_query($sql2);
$sql4="UPDATE egp SET egp.estado_egp='0' WHERE egp.n_egp IN(SELECT cotizacion_nueva.n_egp_cn FROM cotizacion_nueva WHERE cotizacion_nueva.n_cotiz_cn='$cotiz')";
$result4=mysql_query($sql4);
if(!empty($_POST['reg']))
{         $registros = array_keys($_POST['reg']); 
          $registros = implode("','", $registros);
          $registros = "'".$registros."'";
		  $sql3="UPDATE cotizacion_nueva SET cotizacion_nueva.estado_cn='1' WHERE cotizacion_nueva.n_cn IN($registros)";
		  $result3=mysql_query($sql3);
		  $sql5="UPDATE egp SET egp.estado_egp='1' WHERE n_egp IN(SELECT cotizacion_nueva.n_egp_cn FROM cotizacion_nueva WHERE cotizacion_nueva.n_cn IN($registros))";
		  $result5=mysql_query($sql5);  
}
header("Location:cotizacion_bolsa_edit.php?n_cotiz=".$_POST['n_cotiz']."&id_c_cotiz=".$_POST['id_c_cotiz']."");
?>