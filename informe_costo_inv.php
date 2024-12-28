<?php
require_once("Conexxx.php");

$DCTO[]="";
$tipoD[]="";
$fabricante[]="";
$D="";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>COSTO INVENTARIO</title>
<link rel="stylesheet" href="css/jq.css" type="text/css" media="print, projection, screen" />
<link rel="stylesheet" href="css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script language="javascript1.5" type="text/javascript" src="JS/jquery-1.11.1.min.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery.tablesorter.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


		//$("#TabClientes").tablesorter( {sortList: [[4,0]]} );
		$("#TabClientes").tablesorter();
	}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body style="font-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>COSTO INVENTARIO</B></span>
</p>
</td>

</tr>
</table>
<span style="font-size:18px; font-weight:bold">Fecha: <?PHP echo $hoy ?></span>
<br>
<table align="center" width="100%">
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
</table>
</td>
</tr>
</table>

<hr align="center" width="100%">
<table align="center" width="100%" cellpadding="5" cellspacing="5" style="font-size:11px" rules="rows" id="TabClientes" class="">
<tr style="font-size:18px;  font-weight:bold;">
<th width=""></th>
<th width="200" align="center">Costo Sin IVA</th>
<th width="200" align="center">IVA</th>
<th width="200" align="center">Tot. Costo</th>
<th width="200" align="center">Tot. PVP</th>

</tr>
<?php

$TOT_INV=costo_now($codSuc,"con");
$costoC=$TOT_INV[0];
$costoS=$TOT_INV[1];
$inv_pvp=$TOT_INV[2];
$IVA=$costoC-$costoS;
?>
<tr style="font-size:18px;">
<th width="">TOTAL INVENTARIO</th>
<td align="center"><?php echo money(redondeo($costoS)) ?></td>
<td align="center"><?php echo money(redondeo($IVA)) ?></td>
<td align="center"><?php echo money(redondeo($costoC)) ?></td>
<td align="center"><?php echo money(redondeo($inv_pvp)) ?></td>
</tr>
</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>