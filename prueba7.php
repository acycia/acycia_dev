<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script src="../../SpryAssets/SpryEffects.js" type="text/javascript"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript">
/*function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe contener una dirección de correo electrónico.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' debe contener un número.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' debe contener un número entre '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es obligatorio.\n'; }
    } if (errors) alert('El siguiente error (s) ocurrio:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
f
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}*/
</script>
</head>

<body>
//Fecha formato: 12/12/2014 
fecha = /^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/;

//Fecha formato: 2014-11-27 01:30:00 
fecha = ^([0-9]{4}\-[0-9]{2}\-[0-9]{2}\ [0-9]{2}\:[0-9]{2}\:[0-9]{2})$;
//Letras y nuemros 
pattern="[0-9a-zA-Z]{0,20}"
<form name="form1"action="" method="POST">
<!--<input name="campo" id="campo" type="text" pattern="[0-9a-zA-Z]{0,20}" title="Este no parece un Dato válida verifique solo cadena entre letras y numeros sin espacios"/>-->
<input name="numIni_r" id="numIni_r" type="text" onBlur="if (form1.numIni_r.value) { DatosGestiones3('7','valorswitch',1,'valor1',form1.id_op_r.value,'valor2',form1.numIni_r.value,'valor3',form1.numFin_r.value); }" />

<input type="submit" name="guardar" id="guardar" value="Submit" />
<!--<input name="envio"  value="enviar"type="button" onclick="MM_validateForm('campo','','NisEmail');return document.MM_returnValue" />-->
</form>
</body>
</html>