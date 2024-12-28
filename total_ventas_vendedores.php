<?php
include_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$clase=$_SESSION["clases"];
$fab=$_SESSION["fabs"];
 
$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (".VALIDACION_VENTA_VALIDA." $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";

?>

<!DOCTYPE html  >
<html  >
<head>
<?php include_once("HEADER_UK.php"); ?>
 
 

</head>

<body >
<div style=" top:0cm; width:21.5cm; height:27.9cm; " class="uk-width-7-10 uk-container-center">
<table align="center" width="100%">
<thead>
<tr>
<td>
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>Nomina Ventas Productos </B></span>
</p>
</td>

</tr>
</thead>
</table>
Fecha: <?PHP echo $hoy ?>

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



$total_vendedores=tot_nomina_cajas($fechaI,$fechaF,$codSuc,"","","",$clase,$fab,"A");



?>

<p align="left" style="font-size:16px;">
<b>Filtros</b><br />
<?php 

if(isset($_SESSION['clases'])&&!empty($_SESSION['clases']))
{
	$class=$_SESSION['clases'];
	$GSX650="<b>Clase(s) Productos:</b> |";
	foreach($class as $key=>$resultado)
	{
			$GSX650.="$resultado |";
	}
	echo $GSX650."<br>";
	}
	
if(isset($_SESSION['fabs'])&&!empty($_SESSION['fabs']))
{
	$fabricantes=$_SESSION['fabs'];
	$GSX650="<b>$global_text_fabricante(s):</b> |";
	foreach($fabricantes as $key=>$resultado)
	{
			$GSX650.="$resultado |";
	}
	echo $GSX650."<br>";
	}
 ?>
</p>
<table align="center"  frame="box" rules="cols" cellspacing="0" cellpadding="0"  class="uk-table uk-table-striped" >
<thead>
<tr class="uk-text-large uk-text-bold uk-text-center uk-block-secondary uk-contrast">
<td>Vendedor</td><td>Total</td>
</tr>
</thead>
<tbody>
<?php
$sql="SELECT vendedor from fac_venta WHERE nit=$codSuc AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' ) $filtroNOanuladas GROUP BY vendedor" ;
$rs=$linkPDO->query($sql);
$totVendedores=0;
//echo "$sql";

while($row=$rs->fetch())
{
$nomVende=nomTrim($row["vendedor"]);
if(array_key_exists($nomVende, $total_vendedores)){
if($total_vendedores[$nomVende]>0 ){

    echo "<tr><td>$nomVende</td><td><b>".money3($total_vendedores[$nomVende])."</b></td></tr>";
$totVendedores+=$total_vendedores[$nomVende];

}

}else{
    
    if($total_vendedores["VENTAS MOSTRADOR"]>0 ){

    echo "<tr><td>VENTAS MOSTRADOR</td><td><b>".money3($total_vendedores["VENTAS MOSTRADOR"])."</b></td></tr>";
$totVendedores+=$total_vendedores["VENTAS MOSTRADOR"];


    
    
}
}
	
}

?>
</tbody>
<tfoot>
<tr style="font-size:20px; font-weight:bold;" class="uk-block-secondary uk-contrast">
<th>Total Ventas sin IVA</th><th><?php echo money3($totVendedores) ?></th>
</tr>
</tfoot>
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


</div>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER"; ?>"></script> 
<?php include_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</body>
</html>