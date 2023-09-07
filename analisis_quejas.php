<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);
//$ano=$_GET['ano'];
$ano=2006;
?>
<style type="text/css">
<!--
.Estilo3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo5 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; }
.Estilo6 {color: #CCCCCC}
.Estilo7 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #000000;
}
-->
</style>
<table width="741" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr>
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo7">ANALISIS DE QUEJAS Y RECLAMOS POR PERIODO</div></td>
  </tr>
</table>

<table width="100%" bordercolor="#FFFFFF" bgcolor="#FFFFFF" >
  <tr bgcolor="#999999" class="Estilo3">
    <td width="27%"><div align="center"><span class="Estilo5">DESCRIPCION DEL RECLAMO </span></div></td>
    <td width="12%" rowspan="2"><div align="center"><span class="Estilo5">FRECUENCIA</span></div></td>
    <td width="9%" rowspan="2"><div align="center"><span class="Estilo5">Ene</span></div></td>
    <td width="4%" rowspan="2"><div align="center"><span class="Estilo5">Feb</span></div></td>
    <td width="4%" rowspan="2"><div align="center"><span class="Estilo5">Mar</span></div></td>
    <td width="6%" rowspan="2"><div align="center"><span class="Estilo5">Abr</span></div></td>
    <td width="6%" rowspan="2"><div align="center"><span class="Estilo5">May</span></div></td>
    <td width="5%" rowspan="2"><div align="center"><span class="Estilo5">Jun</span></div></td>
    <td width="5%" rowspan="2"><div align="center"><span class="Estilo5">Jul</span></div></td>
    <td width="4%" rowspan="2"><div align="center"><span class="Estilo5">Ago</span></div></td>
    <td width="5%" rowspan="2"><div align="center"><span class="Estilo5">Sep</span></div></td>
    <td width="4%" rowspan="2"><div align="center"><span class="Estilo5">Oct</span></div></td>
    <td width="5%" rowspan="2"><div align="center"><span class="Estilo5">Nov</span></div></td>
    <td width="4%" rowspan="2"><div align="center"><span class="Estilo5">Dic</span></div></td>
  </tr>
  <tr bgcolor="#999999" class="Estilo3">
    <td class="Estilo3"><div align="center"><strong>PROCESO</strong></div></td>
  </tr>
<?php
$sql="select * from proceso";
$result=mysql_query($sql);
while ($proceso=mysql_fetch_array($result))
{
       $cod_proceso=$proceso[0];
       $nom_proceso=$proceso[1];
?>
  <tr class="Estilo3">
    <td bgcolor="#0066CC"><?php echo $nom_proceso?></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
    <td bgcolor="#0066CC"><div align="center"></div></td>
	<td bgcolor="#0066CC"><div align="center"></div></td>
  </tr>
<?php
$sql1="select * from fuente where id_proceso_f='$cod_proceso'";
$result1=mysql_query($sql1);
while ($fuente=mysql_fetch_array($result1))
      {
        $cod_fuente=$fuente[0];
        $nom_fuente=$fuente[2]; 
        $sql5="select * from analisis_qr where fecha_reclamo_qr like '$ano%' and fuente_qr='$cod_fuente'";
        $result5=mysql_query($sql5);
        $num_p=mysql_num_rows($result5); 
        $con_p=$con_p+$num_p;
?>
<tr class="Estilo3">
    <td bgcolor="#CCCCCC"><?php echo $nom_fuente ?></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo $num_p ?><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
	<td bgcolor="#CCCCCC"><div align="center"><span class="Estilo6"></span></div></td>
  </tr>
<?php
$sql2="select * from causa where id_fuente_c='$cod_fuente'";
$result2=mysql_query($sql2);
while ($causa=mysql_fetch_array($result2))
        {
         $cod_causa=$causa[0];
         $nom_causa=$causa[2];
         $sql4="select * from analisis_qr where fecha_reclamo_qr like '$ano%' and causa_gn_qr='$cod_causa'";
         $result4=mysql_query($sql4);
         $num_f=mysql_num_rows($result4);

         
?>
<tr bordercolor="#FFFFFF" bgcolor="#EAF4FF" class="Estilo3">
    <td><?php echo $nom_causa ?></td>
    <td><div align="center"><?php echo $num_f ?></div></td>
<?php
for ($i=1;$i<=12;$i++)
    {
     if ($i<='9')
        {
         $x="0".$i;
         }
         else
         {
         $x=$i;
          }
          $fecha=$ano."-".$x;
          $sql3="select * from analisis_qr where fecha_reclamo_qr like '$fecha%' and causa_gn_qr='$cod_causa'";
          $result3=mysql_query($sql3);
          $num_c=mysql_num_rows($result3);
          $vector[$i]=$vector[$i]+$num_c;
          echo "<td><div align='center'>".$num_c;
	}?>
  </div></td> </tr>
<?php
}
}
}
?>
<tr class="Estilo3">
<td bgcolor="#CCCCCC"><div align="center"><strong>TOTAL</strong></div></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $con_p?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[1] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[2] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[3] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[4] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[5] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[6] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[7] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[8] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[9] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[10] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[11] ?></td>
<td bgcolor="#CCCCCC"><div align="center"><?php echo $vector[12] ?></td>

</tr>
</table>
