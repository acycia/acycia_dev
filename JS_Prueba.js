//extrusion e impresion
function kilosxHora2_prueba()
{
		 //RESTA FECHAS 
		var metrosL =document.getElementById("metro_r").value;
		var fecha1 = new Date(document.getElementById("fecha_ini_rp").value);
		var fecha2 = new Date(document.getElementById("fecha_fin_rp").value); 
		var kilos_r = (document.getElementById("int_kilos_prod_rp").value);
 		var DespTm = document.getElementById("horasmuertas").value;
		var DespTp = document.getElementById("horasprep").value;  
		var horas = ((fecha2-fecha1)/3600000);<!--hora en terminos de decimal-->
		var TiempOptimo = (horas*60);//hora a minuto termino minutos
 		//todos los tiempos para rodamiento termino de minutos
		var TotalTiempoRod=(parseFloat(DespTm) + parseFloat(DespTp)) ; 
		//los tiempos muertos y standby termino minutos
 		var TotTiemKilxH=(parseFloat(DespTm));	 

  			//PARA CAMPO TOTAL HORAS
			//FORMATEO A TIME 00:00
			var MintTH = (TiempOptimo); //tiempo real en minutos
			var secTH = (MintTH*60);//con descuento tiempo convierto a segundos
  			var hrsTH = Math.floor(secTH/3600);
			var minuTH = Math.floor((secTH%3600)/60); 
 			secTH = secTH % 60;
			if(secTH<10) secTH = "0" + secTH;   
			if(minuTH<10) minuTH = "0" + minuTH; 
			var tiempoOPTH = hrsTH + ":" + minuTH; 
            document.getElementById("total_horas_rp").value = tiempoOPTH;		
			
  			///PARA CAMPO KILOS X HORA
			//FORMATEO A TIME 00:00  
  			var MintKH = (TiempOptimo - TotTiemKilxH); //tiempo real en minutos
  			var secKH = (MintKH*60);//con descuento tiempo convierto a segundos
   			var hrsKH = Math.floor(secKH/3600);
			var minuKH = Math.floor((secKH%3600)/60);
			if(secKH<10) secKH = "0" + secKH;   
			if(minuKH<10) minuKH = "0" + minuKH; 
			var tiempoOPKH = hrsKH + ":" + minuKH;  
 			var minuto=parseFloat(minuKH/60);<!--minutos en terminos de hora-->
 			var KilosxtH=(parseFloat(kilos_r)/parseFloat(hrsKH+minuto)); 
			var KilosxtHora = KilosxtH.toFixed(2); 
			document.getElementById("int_kilosxhora_rp").value = (KilosxtHora);//para extrusion,impresion
 
             //PARA CAMPO RODAMIENTO
			//FORMATEO A TIME 00:00
 			var MinRO = (TiempOptimo - TotalTiempoRod); //resto tiempos preparacion 
			var secRO = (MinRO*60);//con descuento tiempo convierto la hora a segundos
  			var hrsRO = Math.floor(secRO/3600);
			var minuRO = Math.floor((secRO%3600)/60); 
 			secRO = secRO % 60;
			if(secRO<10) secRO = "0" + secRO;   
			if(minuRO<10) minuRO = "0" + minuRO; 
			var tiempoOPRO = hrsRO + ":" + minuRO; 
 			var minutoR=parseFloat(minuRO/60);<!--minutos en terminos de hora-->
 			var tiempoOPROperar = parseFloat(hrsRO+minutoR); 
     		document.getElementById("tiempoOptimo_rp").value=tiempoOPRO;//rodamiento optimo 			
 
           var metros2=parseFloat(document.getElementById("metro_r2").value); //total rango de metros
           var metroxminuto= metros2/(tiempoOPROperar); <!--metros dividido minuto optimos de rodamiento-->
   
          document.getElementById("metroxmin").value = metroxminuto.toFixed(2);
//FIN 
}
 //TIEMPOS EN IMPRESION
 function restakilosT(){
   var totalTM = 0;
   var totalTP = 0;
	
     var ups = document.getElementsByName('valor_tiem_rt[]'), sum = 0, i;
    for(i = ups.length; i--;){
        if(ups[i].value)
            sum += parseFloat(ups[i].value, 10);
            totalTM=sum.toFixed(2);
  	} 
		
    var ups2 = document.getElementsByName('valor_prep_rtp[]'), sum2 = 0, x;
    for(x = ups2.length; x--;){
        if(ups2[x].value)
            sum2 += parseFloat(ups2[x].value, 10);
            totalTP=sum2.toFixed(2); 

 	} 

    document.getElementById("horasmuertas").value=totalTM;//horas muertas
	document.getElementById("horasprep").value=totalTP;//horas preparacion
 }
 function restakilosD(){
	var kilos=parseFloat(document.form1.kilos_r.value); 
	var metros=parseFloat(document.form1.metro_r.value); 
    var ups = document.getElementsByName('valor_desp_rd[]'), sum = 0, i;

	if(ups.length >0){
     for(i = ups.length; i--;)
        if(ups[i].value) 
            sum += parseFloat(ups[i].value, 10);
            var totalDesp=sum.toFixed(2);
			var kilosTotales=parseFloat(kilos)-parseFloat(totalDesp);
			//regla de tres
			var nuevosMetros = Math.round(kilosTotales * metros / kilos);
			document.form1.int_kilos_desp_rp.value=totalDesp;
			document.form1.int_total_kilos_rp.value=kilosTotales;
			document.form1.metro_r2.value = nuevosMetros;
            restakilosT();  
 	} else{
	document.form1.int_total_kilos_rp.value=kilos;
	}
} 