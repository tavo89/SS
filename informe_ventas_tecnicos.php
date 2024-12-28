<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$clase=$_SESSION["clases"];
$fab=$_SESSION["fabs"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
 <link href="JS/print.css" rel="stylesheet" type="text/css" media="print"> 
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
<script language="javascript1.5" type="text/javascript" src="JS/jQuery1.8.2.min.js">
</script>
<script language="javascript1.5" type="text/javascript">
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
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>Totales de ventas por T&eacute;cnico </B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr style="font-size:24px; font-weight:bold;">
<td>
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
</table>
</td>
</tr>

</table>
<?php



$total_tec=tot_nomina_tecnicos($fechaI,$fechaF,$codSuc);



?>
<BR /><BR /><BR />
<p align="left" style="font-size:16px;">


</p>
<table align="center" width="100%" style="font-size:18px;" >
<TR>
<td>
<table cellpadding="5" cellspacing="5">
<tr style="font-size:20px; font-weight:bold;"><td>T&eacute;cnico</td><td>Total Facturado</td>
</tr>
<?php
$totVendedores=0;
$rs=$linkPDO->query("SELECT a.nombre,a.id_usu FROM usuarios a INNER JOIN tipo_usu b ON b.id_usu=a.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
while($row=$rs->fetch()){

$nomTec=ucwords(strtolower(htmlentities($row["nombre"], ENT_QUOTES,"$CHAR_SET")));
$idTec=$row["id_usu"];

if(array_key_exists($idTec, $total_tec)){echo "<tr><td>$nomTec</td><td align=\"right\"><b>".money2($total_tec[$idTec])."</b></td></tr>";
	$totVendedores+=$total_tec[$idTec];
}
}

?>
<tr style="font-size:20px; font-weight:bold;">
<th>Total Ventas</th><th><?php echo money3($totVendedores) ?></th>
</tr>
</table>

</td>
</table>
<BR /><BR /><BR />
<hr align="center" width="100%" />
<table width="100%" cellpadding="4" style="font-size:18px;">
<tr>
<td>C./Coordinador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Jefe venta POS:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Contador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
</tr>
</table>

<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 

</body>
</html>