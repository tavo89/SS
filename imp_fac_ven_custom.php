<?php require_once("Conexxx.php"); 


$PUBLICIDAD="
<p align=\"center\" style=\"font-size:10px;line-height:12px;\" class=\"imp_pos\">
<B>$NOM_NEGOCIO</B>
<BR />
$showNIT
<br>
E-mail: $email_sucursal
</p>
";
/*
$Resol="18762002416203";
$FechaResol="11-03-2019";
$Rango="(1 - 2000)";
*/




?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php require_once("HEADER_UK.php"); ?>
<style type="text/css">
@media print{@page {size: landscape}}
@media print {
body {-webkit-print-color-adjust: exact;}
 /* .page-break  { display:block; page-break-before:always; }*/
}


.barra_negra{
vertical-align:top;
position:relative;
top:0px;
background-color:rgb(0, 0, 0) !important;
color:#FFF !important;
-webkit-border-radius:29px;	
	
}
.label_titulo{
	background-color:#063A9B !important;;
-webkit-border-radius:2px;
-moz-border-radius:2px;
border-radius:2px;
color:rgb(255, 255, 255) !important;;
	
}
.label_titulo_warning{
background-color:rgb(252, 0, 130) !important;;
color:rgb(255, 255, 255) !important;;

-webkit-border-radius:2px;
-moz-border-radius:2px;
border-radius:2px;
	
}
.sub_table{
border-width:2px;
border-style:solid;
-webkit-border-radius:8px;
-moz-border-radius:8px;
border-radius:8px;	
	
}
body{ font-size:10px;	
}
table{ font-size:11px;}
</style>
</head>

<body>




<?php
$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];
$hash=s('hashFacVen');

if($fac_servicios_mensuales==1){imprimir_fac_custom2($num_fac,$pre,$hash,$codSuc);}
else {imprimir_fac_custom($num_fac,$pre,$hash,$codSuc);}


require_once("FOOTER_UK.php"); 
?>
</body>
</html>