<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];

$base_iva16=0;
$iva16=0;
$base_iva5=0;
$iva5=0;
$excentas=0;
$base_iva16B=0;
$iva16B=0;
$base_iva5B=0;
$iva5B=0;
$excentasB=0;
$ult_fac=0;
$pri_fac=0;
$DCTO=0;
$tot_comprobantes=0;

$tot_fac_taller=0;
$tot_fac_otros_talleres=0;
$tot_fac_mostrador=0;

$tot_fac_contado=0;
$tot_fac_credito=0;
$tot_fac_tarjeta_credito=0;
$tot_fac_cheque=0;
$InteresesCreditos=0;

$TOTAL=0;

//***********************************************************************************************************************************************************************
//***********************************************************************************************************************************************************************
//***********************************************************************************************************************************************************************

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$TOTAL=$row['TOT'];
	
}


$sql="SELECT SUM(descuento) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$DCTO=$row['TOT'];
	
}



$sql="SELECT art_fac_ven.nit as nit_art,SUM(precio*cant) as base_iva, SUM((iva/100)*(precio*cant)) as iva, COUNT(fac_ven.num_fac_ven) as tot_fac,MAX(fac_ven.num_fac_ven) as ultima,MIN(fac_ven.num_fac_ven) as primera FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,prefijo FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven  WHERE fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=16";
$sql2=$sql;
$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$base_iva16=$row['base_iva'];
	$iva16=$row['iva'];
}

$sql="SELECT art_fac_ven.nit as nit_art,SUM(precio*cant) as base_iva, SUM((iva/100)*(precio*cant)) as iva, COUNT(fac_ven.num_fac_ven) as tot_fac,MAX(fac_ven.num_fac_ven) as ultima,MIN(fac_ven.num_fac_ven) as primera FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=5";
$sql3=$sql;

//echo "<div style=\"z-index:999999\">$sql</div>";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$base_iva5=$row['base_iva'];
	$iva5=$row['iva'];
	if($base_iva5==""){$base_iva5=0;$iva5=0;}
}

$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot-(sub_tot*(dcto/100))) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=0";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Mostrador'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_mostrador=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Taller Honda'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_taller=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Otros Talleres'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_otros_talleres=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_contado=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Cheque'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_cheque=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito'";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' ";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	$ult_fac=$row['ultima'];
	$pri_fac=$row['primera'];
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Otros Talleres'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_OtrosTalleres=$row['TOT'];
	
}

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Mostrador'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_mostrador=$row['TOT'];
	
}

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_contado=$row['TOT'];
	
}
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Cheque'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_cheque=$row['TOT'];
	
}

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_tarjetaCre=$row['TOT'];
	
}

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF') AND tipo_cli='Taller Honda'  AND nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito'";

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

//***********************************************************************************************************************************************************************
//***********************************************************************************************************************************************************************
//***********************************************************************************************************************************************************************


$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta!='Credito' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	$ult_fac=$row['ultima'];
	$pri_fac=$row['primera'];
}
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_facCre=$row['tot_fac'];
	$ult_facCre=$row['ultima'];
	$pri_facCre=$row['primera'];
}
?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
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
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:32px"><B>Ventas por Producto </B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr style="font-size:24px; font-weight:bold;">
<td>
Desde: <?PHP echo fecha($_SESSION['fechaI']) ?>
</td>
<td> Hasta: <?php echo fecha($_SESSION['fechaF']) ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
<?php
$cajera="";
$sql="SELECT * FROM cajasb WHERE DATE(inicio)>='$fechaI' AND DATE(inicio)<='$fechaF' AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$cajera=$row['usu'];
}

?>
<tr>
<td><b>Cajero </b></td><td width="50px"></td><td colspan=""><b><?php echo $cajera ?></b></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="4"><b>Facturas POS </b>IMPRESORA: <?PHP ECHO  "BIXOLON MODELO: SRP-350 S/N: IMPCHKA10110096" ?><br>
ResolDian No. <?php echo $ResolContado ?> del <?php echo $FechaResolContado ?> <?php echo $RangoContado ?>


</td>
</tr>
<tr>
<td>
<b>Desde Fac No. <?php echo $pri_fac ?></b>
</td>
<td>
<b>Hasta Fac No. <?php echo $ult_fac ?></b>
</td><td><b>Total Fac. <?php echo $tot_fac ?></b></td>
</tr>
<tr>
<td colspan="4">
<hr align="center" width="100%">
<b>Facturas COMPUTADOR </b><br>
ResolDian No. <?php echo $ResolCredito ?> del <?php echo $FechaResolCredito ?> <?php echo $RangoCredito ?>


</td>
</tr>

<tr>
<td>
<b>Desde Fac No. <?php echo $pri_facCre ?></b>
</td>
<td>
<b>Hasta Fac No. <?php echo $ult_facCre ?></b>
</td><td><b>Total Fac. <?php echo $tot_facCre ?></b></td>
</tr>

</table>

<hr align="center" width="100%">
<table align="center" width="100%" cellpadding="1" cellspacing="1" style="font-size:11px" rules="rows" id="TabClientes" class="tablesorter">
<thead>
<tr style="font-size:12px;  font-weight:bold;">
<th>#</th>
<th>Prefijo</th>
<th width="50">#Fac.</th>
<th width="60" align="center">Tipo Cli</th>
<th width="100">Referencia</th>
<th width="180">Descripci&oacute;n</th>
<th>Cant.</th>
<th align="center" width="50">Valor U.</th>
<th>IVA</th>
<th width="80" align="center">Sub Total</th>
<th width="80">Vendedor(a)</th>
<th width="50">Hora</th>
<th width="100">Fecha</th>
</tr>
</thead>
<tbody>
<?php
$iva_taller=0;
$tot_taller=0;
$iva_otros=0;
$tot_otros=0;

$trabajoTerceros=0;
$total_vendedores[]="";

$rs=$linkPDO->query("SELECT vendedor from fac_venta WHERE nit=$codSuc GROUP BY vendedor");
while($row=$rs->fetch())
{
	$total_vendedores[ucwords(strtolower($row["vendedor"]))]=0;
	
}

$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc    AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);

$total_taller=0;
$total_otros=0;
$total_mostrador=0;
$total_empleados=0;
$total_fanalca=0;

$total_contado=0;
$total_credito=0;
$total_cre_empleados=0;
$total_cre_fanalca=0;
$total_cre_otros=0;

$base16=0;
$iva16=0;
$excentas=0;
$total_repuestos=0;

$totFacAnticipo=0;



$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money2($row['sub_tot']*1);
	$IVA=money2($row['iva']*1);
	$des= $row['des'] ;
	$cant=$row['cant']*1;
	$valor=money2($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=ucwords(strtolower( $row["vendedor"] ));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	
	
	$total_vendedores[$vendedor]+=$row['sub_tot']*1;
	
	if($IVA==0)$excentas+=$row['sub_tot']*1;
	
	if($IVA==16)
	{
		$base16+=($row['sub_tot']*1)/1.16;
		$iva16+=$row['sub_tot']*1-($row['sub_tot']*1)/1.16;
		
		}
	$total_repuestos+=$row['sub_tot']*1;
	
	
	///////////////////////// TIPO CLIENTE //////////////////////////
	if($tipoCli=="Taller Honda")
	{ 
	   $total_taller+=$row['sub_tot']*1;
		}
	if($tipoCli=="Mostrador")
	{
		$total_mostrador+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Otros Talleres")
	{
		$total_otros+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Empleados")
	{
		$total_empleados+=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Garantia FANALCA")
	{
		$total_fanalca+=$row['sub_tot']*1;
		
		}
	//////////////////////////////////////////////////////////////
	
	/////// TIPO PAGO /////////////////////////////////////////////////////
	
	if($tipo_venta=="Contado"|| $tipo_venta=="Tarjeta Credito")
	{
		$total_contado+=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Credito")
	{
		$total_credito+=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Anticipo")
	{
		$totFacAnticipo+=$row['sub_tot']*1;
		
		}
	
	
	?>
    <tr>
    <td width="center"><?php echo $i ?></td>
    <td><?php if($row['tipo_venta']=='Credito'){echo $codCreditoSuc; }else {echo $codContadoSuc;} ?></td>
    <td width="center"><?php echo $num_fac ?></td>
    <td align="left"><?php echo $tipoCli ?></td>
    <td><?php echo $ref ?></td>
    <td style="font-size:9px;"><?php echo $des ?></td>
    <td align="center"><?php echo $cant ?></td>
    <td align="center"><?php echo $valor ?></td>
    <td align="center"><?php echo $IVA ?></td>
    <td align="center"><?php echo $subTot ?></td>
    <td><?php echo $vendedor ?></td>
    <td><?php echo $HORA ?></td>
    <td><?php echo $fecha ?></td>
    </tr>
    
    <?php
	
	
}

///////////////////////////////////// LISTA DEVOLUCIONES ///////////////////////////////////////////////
?>
</tbody>
</table>
<table align="center" width="100%" cellpadding="0" cellspacing="0" style="font-size:11px" >
<thead>
<tr style="font-size:16px; font-weight:bold">
<td colspan="12" align="center">DEVOLUCIONES</td>
</tr>
<tr style="font-size:12px;  font-weight:bold;">
<th>#</th>
<th>Prefijo</th>
<th width="50">#Fac.</th>
<th width="60" align="center">Tipo Cli</th>
<th width="100">Referencia</th>
<th width="180">Descripci&oacute;n</th>
<th>Cant.</th>
<th align="center" width="50">Valor</th>
<th>IVA</th>
<th width="80" align="center">Sub Total</th>
<th width="80">Vendedor(a)</th>
<th width="50">Hora</th>
<th width="100">Fecha</th>
</tr>
</thead>

<?php
$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) AND MONTH(fecha)!=MONTH('$fechaI')";
$rs=$linkPDO->query($sql);

$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money($row['sub_tot']*1);
	$IVA=money($row['iva']*1);
	$des= $row['des'] ;
	$cant=$row['cant'];
	$valor=money($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=ucwords(strtolower( $row["vendedor"] ));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	
	
	$total_vendedores[$vendedor]-=$row['sub_tot']*1;
	
	if($IVA==0)$excentas-=$row['sub_tot']*1;
	
	if($IVA==16)
	{
		$base16-=($row['sub_tot']*1)/1.16;
		$iva16-=$row['sub_tot']*1-($row['sub_tot']*1)/1.16;
		
		}
	$total_repuestos-=$row['sub_tot']*1;
	
	
	///////////////////////// TIPO CLIENTE //////////////////////////
	if($tipoCli=="Taller Honda")
	{ 
	   $total_taller-=$row['sub_tot']*1;
		}
	if($tipoCli=="Mostrador")
	{
		$total_mostrador-=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Otros Talleres")
	{
		$total_otros-=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Empleados")
	{
		$total_empleados-=$row['sub_tot']*1;
		
		}
	if($tipoCli=="Garantia FANALCA")
	{
		$total_fanalca-=$row['sub_tot']*1;
		
		}
	//////////////////////////////////////////////////////////////
	
	/////// TIPO PAGO /////////////////////////////////////////////////////
	
	if($tipo_venta=="Contado"|| $tipo_venta=="Tarjeta Credito")
	{
		$total_contado-=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Credito")
	{
		$total_credito-=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Anticipo")
	{
		$totFacAnticipo-=$row['sub_tot']*1;
		
		}
	
	?>
    <tr>
    <td width="center"><?php echo $i ?></td>
    <td><?php if($row['tipo_venta']=='Credito'){echo $codCreditoSuc; }else {echo $codContadoSuc;} ?></td>
    <td width="center"><?php echo $num_fac ?></td>
    <td align="left"><?php echo $tipoCli ?></td>
    <td><?php echo $ref ?></td>
    <td style="font-size:9px;"><?php echo $des ?></td>
    <td align="center"><?php echo $cant ?></td>
    <td align="center"><?php echo $valor ?></td>
    <td align="center"><?php echo $IVA ?></td>
    <td align="center"><?php echo $subTot ?></td>
    <td><?php echo $vendedor ?></td>
    <td><?php echo $HORA ?></td>
    <td><?php echo $fecha ?></td>
    </tr>
    
    <?php
	
	
}

?>

<tr style="font-size:16px; font-weight:bold; background-color:#CCC;">
<td colspan="12" align="center">FIN DEVOLUCIONES</td>
</tr>
<?php


/////////////////////////////////////////////////////////// DEVOLUCIONES /////////////////////////////////////////////////////////////

$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula)";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
	
	
}

$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha_anula,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE anulado='ANULADO'  and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}

?>
</table>
<hr align="center" width="100%" />
<table align="left" width="100%">
<tr valign="top">
<td>
<table style="font-size:11px" cellspacing="0" align="left" >
<TR style="font-size:16px; font-weight:bold;">
<TD colspan="2">Ventas</TD>
</TR>
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money(redondeo(($base16+$excentas))) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money(redondeo($iva16)) ?></span></td>
</tr>

<tr>
<td><?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP echo money(redondeo($excentas)) ?></span></td>
</tr>
<tr style="font-size:16px; font-weight:bold;">
<td>VENTAS:</td><td> <span ><?PHP echo money(redondeo($total_repuestos)) ?></span></td>
</tr>
</table>
</td>
<td>
<table style="font-size:11px" cellspacing="0" align="left" >
<TR style="font-size:16px; font-weight:bold;">
<TD colspan="2">Devoluciones</TD>
</TR>
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money(redondeo($BASE_minus+$excentasB)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money(redondeo($IVA_minus)) ?></span></td>
</tr>

<tr>
<td> <?php echo $fac_ven_etiqueta_nogravados;?>:</td>
<td> <span ><?PHP echo money(redondeo($excentasB)) ?></span></td>
</tr>
<tr style="font-size:16px; font-weight:bold;">
<td>DEVOLUCIONES:</td>
<td> <span ><?PHP echo money(redondeo($TOTAL_minus)) ?></span></td>
</tr>
</table>
</td>


<td>
<table align="center" style="font-size:11px" cellspacing="0"   cellpadding="0">
<tr style="font-size:16px; font-weight:bold;">
<td colspan="2">
Clientes
</td>
</tr>
<tr>
<td>MOSTRADOR:</td><td><span id="base_iva5"><?PHP echo money(redondeo($total_mostrador)) ?></span></td>

</tr>
<tr>
<td>EMPLEADOS:</td><td><span id="base_iva5"><?PHP echo money(redondeo($total_empleados)) ?></span></td>

</tr>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money($total_mostrador+$total_empleados+$total_taller+$total_otros+$total_fanalca) ?>
</td>
</tr>

</table>
</td>

<td>
<table align="center" style="font-size:11px" cellspacing="0"   cellpadding="0">
<tr style="font-size:16px; font-weight:bold;">
<td colspan="2">
Formas de Pago
</td>
</tr>
<tr>
<td>CONTADO Y TARJETA CREDITO:</td><td><span id="iva5"><?PHP echo money(redondeo($total_contado)) ?></span></td>

</tr>
<!--
<tr>
<td>ANTICIPOS:</td><td><span id="iva5"><?PHP echo money(redondeo($totFacAnticipo)) ?></span></td>

</tr>
-->
<tr>
<td>CR&Eacute;DITO:</td><td><span id="iva5"><?PHP echo money(redondeo($total_credito)) ?></span></td>

</tr>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money($total_contado+$total_credito+$totFacAnticipo) ?>
</td>
</tr>

</table>
</td>
</tr>
</table>
<table align="center" width="100%">
<TR>
<td>
<table>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="2">Ventas por Vendedor</td>
</tr>
<tr style="font-size:16px; font-weight:bold;"><td>Vendedor</td><td>Total</td>
</tr>
<?php
$rs=$linkPDO->query("SELECT vendedor from fac_venta WHERE nit=$codSuc AND mecanico!='Fac. Mensual Revisiones' GROUP BY vendedor");
$totVendedores=0;
while($row=$rs->fetch())
{
	echo "<tr><td>".ucwords(strtolower( $row["vendedor"] ))."</td><td><b>".money($total_vendedores[ucwords(strtolower( $row["vendedor"] ))])."</b></td></tr>";
	$totVendedores+=$total_vendedores[ucwords(strtolower( $row["vendedor"] ))];
	
}

?>
<tr>
<th>Total Ventas</th><th><?php echo money($totVendedores) ?></th>
</tr>
</table>

</td>
</table>
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>