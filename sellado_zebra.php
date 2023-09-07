<?php require_once('Connections/conexion1.php'); ?>
<?php
/*require('barcode/class/BCGFontFile.php');
require('barcode/class/BCGColor.php');
require('barcode/class/BCGDrawing.php');
require('barcode/class/BCGcode128.barcode.php');//clase que contiene el tipo de codigo code128
 
$font = new BCGFontFile('./barcode/class/font/Arial.ttf', 18);
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);
 
// Barcode Part
$code = new BCGcode128();
$code->setScale(2);
$code->setThickness(30);
$code->setForegroundColor($color_black);
$code->setBackgroundColor($color_white);
$code->setFont($font);
$code->setStart(NULL);
$code->setTilde(true);
$code->parse('a123');
 
// Drawing Part
$drawing = new BCGDrawing('', $color_white);
$drawing->setBarcode($code);
$drawing->draw();
 
header('Content-Type: image/png');
 
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);*/



/*$handle = printer_open("\\\\avseb01pdc01\\HP Color LaserJet 4650 PCL 6 (EB01ORT1)");
printer_start_doc($handle, "Mi Documento");
printer_start_page($handle);
$font = printer_create_font("Arial",55,30,400,false,false, false,0);
printer_select_font($handle, $font);
$mostrar="ESTOY TRATANDO DE HACER FUNCIONAR ESTA COSA...";
$mostrar2= "Sigo intentando, pero en la otra linea";
printer_draw_text($handle,$mostrar,50,400);
printer_draw_text($handle,$mostrar2,50,900);
printer_delete_font($font);
printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);*/


/*$handle = printer_open("ZDesigner ZT230-200dpi ZPL");
printer_write($handle, "Texto a imprimir");
printer_close($handle);*/


/*$_SESSION['PrintBuffer']='';         //printer buffer
  print_sub_1(); 
  print_sub_2();
  print_sub_3();

  $handle=printer_open("ZDesigner ZT230-200dpi ZPL");
  printer_set_option($handle, PRINTER_MODE, "RAW");
  printer_write($handle, $_SESSION['PrintBuffer']);
//print $_SESSION['PrintBuffer'];         //for testing
  printer_close($handle);*/
  
/*	$handle = printer_open("ZDesigner ZT230-200dpi ZPL"); 
	printer_start_doc($handle, "My Document"); 
	printer_start_page($handle); 
	$font = printer_create_font("Arial", 72, 48, 400, false, false, false, 0); 
	printer_select_font($handle, $font); 
	printer_draw_text($handle, "test", 10, 10); 
	printer_delete_font($font); 
	printer_end_page($handle); 
	printer_end_doc($handle); 
	printer_close($handle);  */ 


/*	$printer = "ZDesigner ZT230-200dpi ZPL";  
	
	if($ph = printer_open($printer)) 
	{  
	
	
	printer_start_doc($ph, "testfile.txt"); 
	printer_start_page($ph); 
	
	$font = printer_create_font("Arial",72,48,400,false,false, false,0); 
	printer_select_font($ph, $font); 
	
	$text1= "producto: computadora personal notex12563 cancelado..."; 
	$text2= "observacion: puede ser muy largooooooooooooooooooooooo"; 
	
	//$text2=wordwrap($text1, 8, "\n", true); 
	
	printer_draw_text($ph, $text1, 10, 10); 
	printer_draw_text($ph, $text2, 10, 110); 
	
	printer_delete_font($font); 
	
	printer_end_page($ph); 
	printer_end_doc($ph); 
	printer_close($ph); 
	
	}  
	else "Couldn't connect..."; */ 
	
	$handle = printer_open("ZDesigner ZT230-200dpi ZPL"); 
	printer_start_doc($handle, "My Document"); 
	printer_start_page($handle); 
	
	$font = printer_create_font("Arial",72,48,400,false,false, false,0); 
	printer_select_font($handle, $font); 
	printer_draw_text($handle, "test", 10, 10); 
	printer_delete_font($font);
	printer_set_option($handle, PRINTER_COPIES, 1); 
	
	printer_end_page($handle); 
	printer_end_doc($handle); 
	printer_close($handle); 
	
  
?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Documento sin título</title>
<script language='VBScript'>
Sub Print() //Declaración de subrutina VbScript
       OLECMDID_PRINT = 6
       OLECMDEXECOPT_DONTPROMPTUSER = 2
       OLECMDEXECOPT_PROMPTUSER = 1
       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
End Sub
document.write "<object ID='WB' WIDTH=0 HEIGHT=0 CLASSID='CLSID:8856F961-340A-11D0-A96B-00C04FD705A2'></object>"
</script>
</head>

<body onload="Print();">
<a href="javascript:window.print();">Print</a>
     <form action="form1" method="get"><INPUT TYPE="button" onClick="test_OnClick" NAME="test" VALUE="Pour un test" ></form> 

</body>
</html>-->

