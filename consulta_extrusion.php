<?php require_once('Connections/conexion1.php'); ?>
<?php

$colname_rp= "-1";
if (isset($_GET['id_op'])) {
  $colname_rp = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_sql = "SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='$colname_rp'";
$sql = mysql_query($query_sql, $conexion1) or die(mysql_error());


//consulta todos los registros por o.p

?>
<table style="color:#000099;width:400px;">
	<tr style="background:#9BB;">
		<td>Fecha</td>
		<td>Kilos</td>		
	</tr>
<?php
  while($row = mysql_fetch_array($sql)){
  echo "<tr>";
  	echo "<td>".$row['fecha_ini_rp']."</td>";
    echo "<td>"?><a href="javascript:getClientData('clientID','<?php echo "<input name='clientID' id='clientID' type='text' value='1002' />"; ?>')"><?php echo $row['int_total_kilos_rp']; ?><input name='clientID' id='clientID' type='text' value='' /><!--<input name='clientID2' id='clientID2' type='text' value='' /><input name='clientID3' id='clientID3' type='text' value='' />--></a></td><?php
  	echo "</tr>";
  }
?>
</table>