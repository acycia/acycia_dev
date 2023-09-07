<?php foreach($this->row_referencia_copia as $row_referencia_copia) { $row_referencia_copia; } ?>
<?php foreach($this->row_referencia as $row_referencia) { $row_referencia; } ?>
<?php foreach($this->row_caract as $row_caract) { $row_caract; } ?>
<?php foreach($this->row_editar_m as $row_editar_m) { $row_editar_m; } ?>
<?php foreach($this->row_mezcla as $row_mezcla) { $row_mezcla; } ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body onload="extrusoraNumero()" >
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="8" id="principal">PROCESO EXTRUSION MEZCLAS</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="8" id="dato3">
      <a href="view_index.php?c=cmezclas&a=Carat&cod_ref=<?php echo $_GET['cod_ref'];?>"><img src="images/menos.gif" alt="EDITAR"title="EDITAR" border="0" /></a> 
    <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU" border="0"/></a>
    <img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /></a></td>
    </tr>
  <tr>
    <td colspan="5" id="subppal2">FECHA DE INGRESO </td>
    <td colspan="3" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td colspan="5" id="fuente2"><?php echo $row_editar_m['fecha_registro_pm'] ?></td>
    <td colspan="3" nowrap id="fuente2"><?php echo $row_editar_m['str_registro_pm']; ?></td>
    </tr>
  <tr>
    <td colspan="5" id="subppal2">Referencia</td>
    <td colspan="3" id="subppal2">Version</td>
    </tr>
  <tr>
    <td colspan="5" nowrap id="fuente2"><?php echo $row_editar_m['int_cod_ref_pm']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_editar_m['version_ref_pm']; ?></td>
    </tr>
  <tr>
    <td colspan="8" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="8" id="subppal2">ESPECIFICACIONES DE MEZCLA</td>
    </tr>
  <tr>
    <td colspan="2" rowspan="2" id="subppal2">EXT-1 </td>
    <td colspan="2" id="subppal2">TORNILLO A</td>
    <td colspan="2" id="subppal2">TORNILLO B</td>
    <td colspan="2" id="subppal2">TORNILLO C</td>
    </tr>
  <tr>
    <td colspan="2" id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">Tolva A</td>
    <td id="fuente2"><?php 
	    $idinsumo=$row_editar_m['int_ref1_tol1_pm']; 
		$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol1_porc1_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo=$row_editar_m['int_ref2_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol1_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo= $row_editar_m['int_ref3_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}		
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol1_porc3_pm']; ?></td>
  </tr> 
<tr>
    <td colspan="2" id="fuente2">Tolva B</td>
    <td id="fuente2"><?php $idinsumo = $row_editar_m['int_ref1_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol2_porc1_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref2_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol2_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref3_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol2_porc3_pm'] ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">Tolva C</td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref1_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol3_porc1_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref2_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol3_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref3_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol3_porc3_pm'] ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">Tolva D</td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref1_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol4_porc1_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref2_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol4_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref3_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol4_porc3_pm'] ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">RPM - %</td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_rpm_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol5_porc1_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_rpm_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol5_porc2_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_rpm_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol5_porc3_pm'] ?></td>
  </tr> 
<tr>
  <td colspan="8" id="fuente3">Observaciones de Mezclas: <?php echo $row_editar_m['observ_pm'] ?></td>
</tr>
 
 <tr id="tr1">
       <td colspan="8" id="titulo2">CARACTERISTICAS DE EXTRUSION </td>
     </tr>  
      <tr>
       <td colspan="8" id="fuente2"><?php foreach($this->row_caract as $row_caract) { $row_caract; } ?>
        Estrusora :<strong> <?php echo $row_caract['extrusora_mp']; ?> </strong>
      </td>
     </tr>
     <tr id="tr1">
       <td colspan="3" id="fuente1">Opcion No 1</td>
       <td colspan="2" id="fuente2">Calibre</td>
       <td colspan="3" id="fuente2">Ancho material</td>
       </tr>
     <tr>
       <td colspan="2" id="fuente1">
       Boquilla de Extrusion</td>
       <td id="fuente1"> 
         <?php echo $row_caract['campo_1'];?> </td>
       <td id="fuente1">Calibre</td>
       <td id="fuente1">Micras</td>
       <td colspan="3" id="fuente1">&nbsp;Ancho</td>
       </tr>
     <tr id="tr1">
       <td colspan="2" id="fuente1">Relacion Soplado (RS)</td>
       <td id="fuente1"> 
         <?php echo $row_caract['campo_2'];?></td>
       <td id="fuente1">
          <?php echo $row_caract['campo_3'];?> </td>
       <td id="fuente1"> 
         <label for="micrass"></label>
         <?php echo $row_caract['campo_6'];?></td>
       <td colspan="3" id="fuente1">  
         <?php echo $row_caract['campo_7'];?> 
       </td>
       </tr>
     <tr>
       <td colspan="2" rowspan="2" id="fuente1">Altura Linea    Enfriamiento</td>
       <td colspan="2" rowspan="2" id="fuente1"> 
         <?php echo $row_caract['campo_8'];?></td>
       <td id="fuente1">Presentacion</td>
       <td id="fuente1">&nbsp;</td>
       <td colspan="2" id="fuente1">Peso Millar</td>
       </tr>
     <tr>
       <td id="fuente1">
         <?php echo $row_caract['campo_9'];?></td>
       <td id="fuente1">&nbsp;</td>
       <td colspan="2" id="fuente1">
         <?php echo $row_caract['campo_10'];?></td>
       </tr>
     <tr id="tr1">
       <td rowspan="2" id="fuente1">Velocidad de Halado</td>
       <td colspan="2" id="fuente1">Tratamiento Corona</td>
       <td colspan="4" id="fuente2">Ubicaci&oacute;n Tratamiento</td>
       <td  id="fuente1">Pigmentaci&oacute;n</td>
     </tr>
     <tr>
       <td id="fuente1">Potencia</td>
       <td id="fuente1">
         <?php echo $row_caract['campo_11'];?>
        </td>
       <td id="fuente1">Cara Interior</td>
       <td colspan="2" id="fuente1">
          <?php echo $row_caract['campo_12'];?>
         </td>
       <td id="fuente1">Interior
        </td>
       <td id="fuente1"><?php echo $row_caract['campo_13'];?></td>
     </tr>
     <tr>
       <td id="fuente1"><?php echo $row_caract['campo_14'];?></td>
       <td id="fuente1">Dinas</td>
       <td id="fuente1">
         <?php echo $row_caract['campo_15'];?>
       </td>
       <td id="fuente1">Cara Exterior</td>
       <td colspan="2" id="fuente1">
         <?php echo $row_caract['campo_16'];?>
        </td>
       <td id="fuente1">Exterior
         </td>
       <td id="fuente1">
         <?php echo $row_caract['campo_17'];?>
       </td>
     </tr>
     <tr id="tr1">
       <td rowspan="2" id="fuente1">% Aire Anillo Enfriamiento</td>
       <td colspan="3" id="fuente2">Tension</td>
       <td colspan="4" id="fuente1">&nbsp;</td>
     </tr>
     <tr id="tr1" class="zonaextruder1" style="display: none;">
       <td colspan="2"id="fuente1">Sec Take Off</td>
       <td colspan="2"id="fuente1">Winder A</td>
       <td colspan="2"id="fuente1">Winder B</td>
       <td colspan="6" id="fuente1" nowrap="nowrap">&nbsp;</td>
     </tr>
     <tr id="tr1" class="zonaextruder2" style="display: none;">
       <td colspan="2"id="fuente1">Calandia</td>
       <td colspan="2"id="fuente1">Colapsador</td>
       <td colspan="2"id="fuente1">Embobinador Ext.</td>
       <td colspan="2"id="fuente1" nowrap="nowrap">Embobinador Int.</td>
     </tr> 
     <tr>
       <td  id="fuente1">
          <?php echo $row_caract['campo_18'];?>
       </td>
       <td colspan="2"id="fuente1" class="zonaextruder1" style="display: none;">
         <?php echo $row_caract['campo_19'];?>
       </td>
       <td colspan="2"id="fuente1" class="zonaextruder1" style="display: none;">
         <?php echo $row_caract['campo_20'];?>
       </td>
       <td colspan="2"id="fuente1" class="zonaextruder1" style="display: none;">
         <?php echo $row_caract['campo_21'];?>
        </td>

        <td colspan="2"id="fuente1" class="zonaextruder2" style="display: none;">
          <?php echo $row_caract['campo_54'];?> 
        </td>
        <td colspan="2"id="fuente1" class="zonaextruder2" style="display: none;">
          <?php echo $row_caract['campo_55'];?> 
        </td>
        <td colspan="2"id="fuente1" class="zonaextruder2" style="display: none;">
           <?php echo $row_caract['campo_56'];?> 
        </td>
        <td colspan="2"colspan="2" id="fuente1" class="zonaextruder2" style="display: none;" nowrap="nowrap">
          <?php echo $row_caract['campo_57'];?> 
        </td> 

        <td id="fuente1" class="zonaextruder1" style="display: none;" nowrap="nowrap" >&nbsp;</td>
     </tr>
     <tr>
       <td colspan="10" id="fuente1"><strong> Nota:</strong> Favor entregar al proceso siguiente el material debidamente identificado seg&uacute;n el documento    correspondiente para cada rollo de material.</td>
       
     </tr> 
     <tr id="tr1">
       <td colspan="8" id="titulo2">TEMPERATURAS DE FUNDIDO EN TORNILLOS Y CABEZAL</td>
     </tr>
     <tr id="tr1">
       <td id="fuente1">&nbsp;</td>
       <td colspan="2"id="fuente1">TORNILLO A</td>
       <td colspan="2"id="fuente1">TORNILLO B</td>
       <td id="fuente1">TORNILLO C</td>
       <td id="fuente1">Cabezal (Die Head)</td>
       <td id="fuente1">&deg;C</td>
     </tr>
     <tr>
       <td id="fuente1">Barrel Zone 1</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_22'];?></td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_23'];?>
        </td>
       <td id="fuente1"><?php echo $row_caract['campo_24'];?>
        </td>
       <td colspan="1" id="fuente1">Share Lower</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_25'];?>
       </td>
     </tr>
     <tr id="tr1">
       <td id="fuente1">Barrel Zone 2</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_26'];?>
       </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_27'];?>
       </td>
       <td id="fuente1"><?php echo $row_caract['campo_28'];?>
         </td>
       <td colspan="1" id="fuente1">Share Upper</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_29'];?>
        </td>
     </tr>
     <tr>
       <td id="fuente1">Barrel Zone 3</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_30'];?>
         </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_31'];?>
        </td>
       <td id="fuente1"><?php echo $row_caract['campo_32'];?>
       </td>
       <td colspan="1" id="fuente1">L-Die</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_33'];?>
        </td>
     </tr>
     <tr id="tr1">
       <td id="fuente1">Barrel Zone 4</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_34'];?>
        </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_35'];?>
         </td>
       <td id="fuente1"><?php echo $row_caract['campo_36'];?>
         </td>
       <td colspan="1" id="fuente1">V- Die</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_37'];?>
        </td>
     </tr>
     <tr>
       <td id="fuente1">Filter Front</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_38'];?>
        </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_39'];?>
         </td>
       <td id="fuente1"><?php echo $row_caract['campo_40'];?>
         </td>
       <td colspan="1" id="fuente1">Die Head</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_41'];?>
         </td>
     </tr>
     <tr id="tr1">
       <td id="fuente1">Filter Back</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_42'];?>
         </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_43'];?>
         </td>
       <td id="fuente1"><?php echo $row_caract['campo_44'];?>
         </td>
       <td colspan="1" id="fuente1">Die Lid</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_45'];?>
         </td>
     </tr>
     <tr>
       <td id="fuente1">Sec- Barrel</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_46'];?>
          </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_47'];?>
         </td>
       <td id="fuente1"><?php echo $row_caract['campo_48'];?>
         </td>
       <td colspan="1" id="fuente1">Die Center Lower</td>
       <td colspan="1" id="fuente1"><?php echo $row_caract['campo_49'];?>
         </td>
     </tr>
     <tr id="tr1">
       <td id="fuente1">Melt Temp &deg;C</td>
         <td colspan="2"id="fuente1"> <?php echo $row_caract['campo_50'];?>
         </td>
       <td colspan="2"id="fuente1">
          <?php echo $row_caract['campo_51'];?>
         </td>
       <td id="fuente1">
          <?php echo $row_caract['campo_52'];?>
         </td>
       <td id="fuente1">Die Center Upper</td>
       <td colspan="2" id="fuente1">
          <?php echo $row_caract['campo_53'];?>
      </td>
     </tr>

     <tr class="zonaextruder2" style="display: none;" >
       <td id="fuente1">Zona 5</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_58'];?>
         </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_59'];?>
         </td>
       <td colspan="5" id="fuente1"><?php echo $row_caract['campo_60'];?>
         </td> 
     </tr>
     <tr id="tr1" class="zonaextruder2" style="display: none;" >
       <td id="fuente1">Zona 6</td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_61'];?>
         </td>
       <td colspan="2"id="fuente1"><?php echo $row_caract['campo_62'];?>
         </td>
       <td colspan="5"id="fuente1"><?php echo $row_caract['campo_63'];?>
         </td> 
     </tr>

     <tr>
       <td colspan="10"id="fuente1">Estos son valores de referencia que pueden cambiar de acuerdo    a velocidad, temperatura ambiente, calibre, etc.</td>
     </tr>
  <tr>
    <td colspan="9" id="subppal2">&nbsp;</td>
    </tr>
</table>

</div>
</body>
</html>
<script type="text/javascript">
 
   
    function extrusoraNumero(){ 
      var extrusora_mp =  "<?php echo $row_caract['extrusora_mp']; ?>";
      if(extrusora_mp == "1 Maquina Extrusora") { 
         $('.zonaextruder1').show();
         $('.zonaextruder2').hide(); 

      }else if(extrusora_mp == "2 Maquina Extrusora"){  
 
         $('.zonaextruder1').hide();
         $('.zonaextruder2').show();  
      }
    }

</script>
<?php
mysql_free_result($usuario);
mysql_free_result($editar_m);
mysql_free_result($res);
?>
