<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>Untitled Document</title>
<script type="text/javascript" src="/js/jquery.js"></script>
<script>
/*function objetus(file) {
    xmlhttp=false;
/*    this.AjaxFailedAlert = "Su navegador no soporta las funciónalidades de este sitio
		 y podria experimentarlo de forma diferente a la que fue pensada.
		 Por favor habilite javascript en su navegador para verlo normalmente.\n";
    this.requestFile = file;
    this.encodeURIString = true;
    this.execute = false;
    if (window.XMLHttpRequest) { 
        this.xmlhttp = new XMLHttpRequest();
        if (this.xmlhttp.overrideMimeType) {
            this.xmlhttp.overrideMimeType('text/xml');
        }
    } 
    else if (window.ActiveXObject) { // IE
        try {
            this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        }catch (e) {
            try {
                this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                this.xmlhttp = null;
            }
        }
        if (!this.xmlhttp && typeof XMLHttpRequest!='undefined') {
            this.xmlhttp = new XMLHttpRequest();
            if (!this.xmlhttp){
                this.failed = true; 
            }
        } 
    }
    return this.xmlhttp ;
}
function recibeid(_pagina,valorget,valorpost,capa){ 
    ajax=objetus(_pagina);
    if(valorpost!=""){
        ajax.open("POST", _pagina+"?"+valorget+"&tiempo="+new Date().getTime(),true);
    } else {
        ajax.open("GET", _pagina+"?"+valorget+"&tiempo="+new Date().getTime(),true);
    }
    ajax.onreadystatechange=function() {
        if (ajax.readyState==1){
            document.getElementById(capa).innerHTML = 
				"<img src='images/loadingcircle.gif' align='center'> Aguarde por favor...";
        }
        if (ajax.readyState==4) {
            if(ajax.status==200)
            {document.getElementById(capa).innerHTML = ajax.responseText;}
            else if(ajax.status==404)
            {
                capa.innerHTML = "La direccion no existe";
            }
            else
            {
                capa.innerHTML = "Error: ".ajax.status;
            }
        }
    }
    if(valorpost!=""){
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(valorpost);
    } else {
        ajax.send(null);
    }
} 



if(valorpost!=""){
    ajax.open("POST", _pagina+"?"+valorget+"&tiempo="+new Date().getTime(),true);
} else {
    ajax.open("GET", _pagina+"?"+valorget+"&tiempo="+new Date().getTime(),true);
}

if(valorpost!=""){
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(valorpost);
} else {
    ajax.send(null);
}*/
</script>
<script>
//función creación del objeto XMLHttpRequest. 
/*function creaObjetoAjax () { //Mayoría de navegadores
     var obj;
     if (window.XMLHttpRequest) {
        obj=new XMLHttpRequest();
        }
     else { //para IE 5 y IE 6
        obj=new ActiveXObject(Microsoft.XMLHTTP);
        }
     return obj;
     }
function enviar() {
   //Recoger datos del formulario:
   reemail=document.datos.miemail.value; //Email escrito por el usuario
   recontra1=document.datos.micontra1.value; //Contrasena primera
   recontra2=document.datos.micontra2.value; //Contrasena segunda
   //datos para el envio por POST:
   misdatos="envioEmail="+reemail+"&envioContra1="+recontra1+"&envioContra2="+recontra2;
   //Objeto XMLHttpRequest creado por la función.
   objetoAjax=creaObjetoAjax();
   //Preparar el envio  con Open
   objetoAjax.open("POST","produccion_registro_sellado_detalle_add.php",true);
   //Enviar cabeceras para que acepte POST:
   objetoAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   objetoAjax.setRequestHeader("Content-length", misdatos.length);
   objetoAjax.setRequestHeader("Connection", "close");
   objetoAjax.onreadystatechange=recogeDatos;
	 objetoAjax.send(misdatos);
   } 
function recogeDatos() {
    if (objetoAjax.readyState==4 && objetoAjax.status==200) {
        miTexto=objetoAjax.responseText;
        document.getElementById("comp").innerHTML=miTexto;
        }
    }*/
	</script>
<script>

/*function realizaProceso(valorCaja1, valorCaja2){

        var parametros = {

                "valorCaja1" : valorCaja1,

                "valorCaja2" : valorCaja2

        };

        $.ajax({

                data:  parametros,

                url:   'produccion_sellado_ejemplo_ajax_proceso.php',

                type:  'post',

                beforeSend: function () {

                        $("#resultado").html("Procesando, espere por favor...");

                },

                success:  function (response) {

                        $("#resultado").html(response);

                }

        });

}*/

</script>
<script>
function http(){
    if(typeof window.XMLHttpRequest!='undefined'){
        return new XMLHttpRequest();    
    }else{
        try{
            return new ActiveXObject('Microsoft.XMLHTTP');
        }catch(e){
            alert('Su navegador no soporta AJAX');
            return false;
        }    
    }    
}
function request(url,callback,params){
    var H=new http();
    if(!H)return;
    H.open('post',url+'?'+Math.random(),true);
    H.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    H.onreadystatechange=function(){
        if(H.readyState==4){
            callback(H.responseText);
            H.onreadystatechange=function(){}
            H.abort();
            H=null;
        }
    }
    var p='';
    for(var i in params){
        p+='&'+i+'='+escape(params[i]);    
    }
    H.send(p);
}
function abrir(){
    var a=window.open('','','width=500,height=500');
    request('http://www.acycia.com/app/webroot/intranet/produccion_registro_sellado_detalle_add.php',function(r){
            a.location=r;
    },{})
}
</script>
</head>
<body>
<!--<h1>Envio de datos por POST</h1>
<h2>Comprobación del siguiente formulario.</h2>
<form name="datos">
<p>Escribe un email: <input type="text" name="miemail" ></p>
<p>Escribe una contrasena: (entre 6 y 10 caracteres) 
   <input type="password" name="micontra1"/></p>
<p>Repite la contrasena: 
   <input type="password" name="micontra2"/></p>
<p>Enviar formulario: 
   <input type="button" value="Enviar" onclick="enviar()" /></p>
<p>Reiniciar formulario: 
   <input type="reset" value="Reiniciar" /></p>
</form>
<h3>Comprobación</h3>
<div id="comp">
<p>Aquí se comprobarán los datos</p> 
</div>-->
<div style="width:150px; border:1px solid #000; background:orange; text-align:center; line-height:30px; cursor:pointer" onclick="abrir()">abrir</div>
</body>
</html>
