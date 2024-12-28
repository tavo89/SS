<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$clase="";
$fab="";

$opt=r("opt");

$filtroSede="";
if($opt=="A"){$filtroSede=" AND a.nit='$codSuc'";}

$filtroSede=" AND a.nit='$codSuc'";

$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (".VALIDACION_VENTA_VALIDA." $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";



?>

<!DOCTYPE html  >
<html  >
<head>
<?php require_once("HEADER_UK.php"); ?>
 <style type="text/css" media="print">
/* ISO Paper Size */
@page {
  size: A4 landscape;
   
}

/* Size in mm */    
@page {
  size: 100mm 200mm landscape;
}

/* Size in inches */    
@page {
  size: 4in 6in landscape;
}

@media print{    
    div{
        background-color: #FFFFFF;
		font-size:xx-small;
    }
}
</style>
 

</head>

<body >
<div style=" top:0cm; width:28.5cm;    " class="uk-width-7-10 uk-container-center">
<table align="center" width="100%">
<thead>
<tr>
<td>
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:14px;">
<span align="center" style="font-size:24px;"><B>Ventas por Nota Entrega</B></span>
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



//$total_vendedores=tot_nomina_motos($fechaI,$fechaF,$codSuc,"","","",$clase,$fab);



?>

<p align="left" style="font-size:16px;">
<table align="center"  frame="box" rules="cols" cellspacing="0" cellpadding="0"  class="uk-table uk-table-striped" >
<thead>
 
<tr>
<td>Nota Entrega</td>
<td>Cliente</td>
<td>Abono</td>
<td>Tot.</td>

<td>Moto</td>
<td>Marca</td>
<td>Fecha</td>
</tr>
</thead>
<tbody>
<?php
$cols="vendedor,id_vendedor,marca_moto,a.tot,entrega,a.nit,b.des,num_pagare,nom_cli,fecha";
$rs=$linkPDO->query("SELECT $cols FROM fac_venta a INNER JOIN art_fac_ven b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit  WHERE   (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' ) $filtroNOanuladas $filtroSede  " );
$totVendedores=0;
$totNominaUni=0;
$TOT_PAGO_NOMINA=0;
while($row=$rs->fetch())
{

$IDvendedor=$row["id_vendedor"];
$MARCA=$row["marca_moto"];
$nomVende=ucwords(strtolower(htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET")));
$totNominaUni=0;
 


$numNota=$row["num_pagare"];

$tot=$row["tot"];
$pagoParcial=$row["entrega"];

$TOT_PAGO_NOMINA+=$tot; 
$desMoto=$row["des"];
$cliente=$row["nom_cli"];
$fecha=$row["fecha"];
?>
<tr>

<td><?php echo "$numNota";?></td>
<td><?php echo "$cliente";?></td>
<td align="right"><?php echo money2("$pagoParcial");?></td>
<td align="right"><?php echo money2("$tot");?></td>

<td><?php echo "$desMoto";?></td>
<td><?php echo "$MARCA";?></td>

<td><?php echo "$fecha";?></td>


</tr>
<?php

 
	
}

?>
</tbody>
<tfoot>
<tr style="font-size:20px; font-weight:bold;" class="uk-block-secondary uk-contrast">
<th colspan="4">Total   </th><th colspan="4"><?php echo money3($TOT_PAGO_NOMINA) ?></th>
</tr>
</tfoot>
</table>
 

<hr align="center" width="100%" />
<!--
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
-->
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 


</div>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER"; ?>"></script> 
<?php require_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</body>
</html>