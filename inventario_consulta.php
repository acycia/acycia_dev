<?php
 require_once('Connections/conexion1.php');
    mysql_select_db($database_conexion1, $conexion1);
	//$sql_inventario=mysql_query("SELECT * FROM Tbl_inventario ORDER BY id_inv DESC  LIMIT 5");
    $sql_inventario=mysql_query("SELECT * FROM TblInventarioListado ORDER BY `idInv` DESC LIMIT 7"); 
	$total_rows_inventario = mysql_num_rows($sql_inventario); // obtenemos el número de filas
    //echo "<table style='color:#000099;width:400px;'>";
	echo "<tr id='tr1'>";
	echo "<td colspan='2' id='titulo1'>CODIGO</td>";
	echo "<td id='titulo1'>INVENTARIO FINAL</td>";
	echo "<td id='titulo1'>ENTRADAS</td>";
	echo "<td id='titulo1'>VALOR UNIDAD</td>";
	echo "<td id='titulo1'>ACEPTADA</td>";
	echo "<td id='titulo1'>EDITAR</td>";
  	echo "</tr>";
    while($row_inventario = mysql_fetch_array($sql_inventario)){
    echo "<tr>";
    echo "<td  colspan='2' id='dato1'>".$row_inventario['Codigo']."</td>";
  	echo "<td id='dato1'>".$row_inventario['SaldoInicial']."</td>";
	echo "<td id='dato1'>".$row_inventario['Entrada']."</td>";
	echo "<td id='dato1'>".$row_inventario['CostoUnd']."</td>";
	if ($row_inventario['Acep']=='0'){$aceptada='Conforme';}else{$aceptada='No Conforme';}
	echo "<td id='dato1'>".$aceptada."</td>";
	echo "<td id='dato1'>";?><a href="javascript:verFoto('inventario_edit.php?idInv=<?php echo $row_inventario['idInv'] ?>','810','250')" style="text-decoration:none;"><em>EDITAR</em></a><?php echo "</td>";
  	echo "</tr>";
    ?>
  <?php  
	//echo "</table>";
  }
?>
