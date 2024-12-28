<?php
include_once("Conexxx.php");
$CodCajero=s("cod_caja_arq");
$filtroCaja="";
if(!empty($CodCajero))$filtroCaja=" AND cod_caja=$CodCajero";
$ARQ_TITULO="";

$multisede=r("opc_multi");
$filtroSEDE_nit="AND fac_venta.nit=$codSuc $filtroCaja";
$filtroSEDE_Bnit="AND b.nit=$codSuc $filtroCaja";
$filtroSEDE_fac_ven_nit="AND fac_ven.nit=$codSuc $filtroCaja";
$filtroSEDE_art_ven_nit=" AND art_fac_ven.nit=$codSuc $filtroCaja";
$filtroSEDE_cod_su="AND cod_su=$codSuc $filtroCaja";
$filtroSEDE_Bcod_su="AND b.cod_su=$codSuc $filtroCaja";
if($multisede==1)
{
$filtroSEDE_nit="";
$filtroSEDE_Bnit="";
$filtroSEDE_fac_ven_nit="";
$filtroSEDE_art_ven_nit="";
$filtroSEDE_cod_su="";
$filtroSEDE_Bcod_su="";	
}
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
//$cod_su=$_SESSION['cod_su'];
$horaI=s("horaI");
$horaF=s("horaF");
$num_serial_arq=s("numSerialArq");
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";

$filtroCerradas=" AND anulado='CERRADA'";
//$filtroCerradas=" ";


$filtroNOanuladas="AND ( (anulado!='ANULADO' $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";


$CONSECUTIVO_INF=serial_arqueos($fechaI,$fechaF,$num_serial_arq);

$titulo_pag="";
if($fechaI==$fechaF)$titulo_pag="COMPROBANTE INFORME DIARIO";
else $titulo_pag="COMPROBANTE INFORME DIARIO";

$IVA=0;
$DESCUENTO=0;
$BASE=0;
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
$tot_fac=0;
$DCTO=0;
$tot_comprobantes=0;

$tot_fac_taller=0;
$tot_fac_inspecciones=0;
$tot_fac_otros_talleres=0;
$tot_fac_mostrador=0;
$tot_trabajos3ros=0;
$tot_fac_contado=0;
$tot_fac_credito=0;
$tot_fac_tarjeta_credito=0;
$tot_fac_cheque=0;
$InteresesCreditos=0;



$RETENCIONES=retenciones($fechaI,$fechaF,$filtroHora,$filtroNOanuladas,$filtroSEDE_nit);
$TOT_RETE=$RETENCIONES["fte"]+$RETENCIONES["ica"]+$RETENCIONES["iva"];

$total_vendedores[][]="";
 
$rs=$linkPDO->query("SELECT vendedor from fac_venta  GROUP BY vendedor");
while($row=$rs->fetch())
{
	$n=nomTrim($row["vendedor"]);
	$total_vendedores[$n][1]=0;
	$total_vendedores[$n][2]=0; // Contado
	$total_vendedores[$n][22]=0;// Bolivares fuertes
	$total_vendedores[$n][21]=0;
	$total_vendedores[$n][3]=0;
	$total_vendedores[$n][4]=0;
	$total_vendedores[$n][5]=0;// Gastos
	$total_vendedores[$n]["tarjetas"]=0;
	$total_vendedores[$n]["cheques"]=0;
	$total_vendedores[$n]["transferencias"]=0;

	
	//echo "<li>n:$n</li>";
	
}

//////////////////////////////////////////////////// TOT TARJETAS X VENDEDOR //////////////////////////////////////////////

$sql="SELECT tot as TOT,vendedor,tipo_venta FROM fac_venta  WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'  $filtroSEDE_nit $filtroNOanuladas";
//echo "$sql";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$nomVende=nomTrim($row['vendedor']);
	$formaPago = $row['tipo_venta'];
	
	if($formaPago=='Tarjeta Credito' || $formaPago=='Tarjeta Debito') {
		$total_vendedores[$nomVende]["tarjetas"]+=$row['TOT'];
	}
	if($formaPago=='Cheque') {
		$total_vendedores[$nomVende]["cheques"]+=$row['TOT'];
	}
	if($formaPago=='Transferencia Bancaria') {
		$total_vendedores[$nomVende]["transferencias"]+=$row['TOT'];
	}
	
}

//////////////////////////////////////////////////// TOT BsF X VENDEDOR //////////////////////////////////////////////

$sql="SELECT SUM(tot_bsf) as TOT,SUM(tot) as TOT2,vendedor FROM fac_venta  WHERE  tot_bsf!=0 AND tot_bsf>0 $filtroSEDE_nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta!='Credito'  $filtroNOanuladas GROUP BY vendedor";
$totBsF=0;
$totBsF2=0;
//echo "$sql";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$nomVende=nomTrim($row['vendedor']);
	$totBsF+=$row['TOT'];
	
	$total_vendedores[$nomVende][22]+=$row['TOT'];
	$total_vendedores[$nomVende][21]+=$row['TOT2'];
	
}

// TOT BOLIVARES 2
$sql="SELECT SUM(tot_bsf) as TOT,SUM(tot) as TOT2,vendedor FROM fac_venta  WHERE   tot_bsf!=0 $filtroSEDE_nit AND tot_bsf>0 AND abono_anti=0  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta!='Credito'  $filtroNOanuladas GROUP BY vendedor";

//echo "$sql";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{


	$totBsF2+=$row['TOT2'];
	
	
}

$tot_pesos=0;
$tot_bolivares=0;
$sql="SELECT SUM(entrega) as TOTpe,SUM(tot_bsf) as tot_b,vendedor FROM fac_venta  WHERE  (tot_bsf!=0 AND tot_bsf>0 ) $filtroSEDE_nit  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta!='Credito'  $filtroNOanuladas GROUP BY vendedor";
//echo "$sql";
//$totBsF=0;
//$totBsF2=0;
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$nomVende=nomTrim($row['vendedor']);

	$total_vendedores[$nomVende][2]+=$row['TOTpe'];
	$tot_pesos+=$row['TOTpe'];
	$tot_bolivares+=$row['tot_b'];
}









//***********************************************************************************************************************

//AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))
$TOT_COMP_EGRESOS=0;//;tot_comp_egreso($fechaI,$fechaF,$codSuc,"","contado");
$sql="SELECT SUM(valor-r_fte-r_ica) as t,cajero FROM comp_egreso WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora $filtroSEDE_cod_su AND tipo_pago='Contado' AND (anulado!='ANULADO'  ) GROUP BY cajero";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch())
	{
		$nomVende=nomTrim($row['cajero']);
		$TOT_COMP_EGRESOS+=$row['t'];
		$total_vendedores[$nomVende][5]+=$row['t'];
	}
if($multisede==1){
$TOT_ANTI_BONO_CONTADO=tot_anticipos($filtroCaja,$fechaI,$fechaF,"all","contado","p");
$TOT_ANTI_BONO_TDEBITO=tot_anticipos($filtroCaja,$fechaI,$fechaF,"all","tDebito","p");
$TOT_ANTI_BONO_TCREDITO=tot_anticipos($filtroCaja,$fechaI,$fechaF,"all","tCredito","p");
$TOTAL_ANTI_BONO=tot_anticipos($filtroCaja,$fechaI,$fechaF,"all","p","p");
$TOTAL_ANTI_BONO_COBRADOS=tot_anticipos($filtroCaja,$fechaI,$fechaF,"all","p","p","si");

$tot_Credito=tot_credito($fechaI,$fechaF,"all",$CodCajero);
	}
else{
$TOT_ANTI_BONO_CONTADO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"contado","p");
$TOT_ANTI_BONO_TDEBITO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"tDebito","p");
$TOT_ANTI_BONO_TCREDITO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"tCredito","p");
$TOTAL_ANTI_BONO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"p","p");
$TOTAL_ANTI_BONO_COBRADOS=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"p","p","si");

$tot_Credito=tot_credito($fechaI,$fechaF,$codSuc,$CodCajero);
}
$TOTAL=0;

/////////////////////////////////////////////////////////// TOTALES FAC ANULADAS /////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTALa=$row['TOT'];
	$BASEa=$row['BASE'];
	$DESCUENTOa=$row['D'];
	$IVAa=$row['IVA'];
	
	
}
///////////////////////////////////// TOT. DEVOLUCIONES COMP. ANTICIPOS////////////////////////////////////////////////////////////////////////////
//
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE   (DATE(fecha_anula)>='$fechaI' $filtroSEDE_cod_su AND DATE(fecha_anula)<='$fechaF' AND (DATE(fecha)!=DATE(fecha_anula)) $filtroHoraAnula)";
$rs=$linkPDO->query($sql );
$tot_devolucion_comp_anti=0;
if($row=$rs->fetch())
{
	$tot_devolucion_comp_anti=$row['TOT'];
	
}


///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CONTADO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE tipo_venta!='Anticipo' $filtroSEDE_nit AND tipo_venta!='Credito'  AND DATE(fecha_anula)>='$fechaI' AND (DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) $filtroHoraAnula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
		
}

$DEV_VENTAS=dev_ventas($fechaI,$fechaF,$codSuc,$filtroCaja);
$TOTAL_minus+=$DEV_VENTAS["tot"];
// --------------------------- DEVOLUCIONES FAC. ANTICIPOS--------------------------------------------------------

$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE tipo_venta='Anticipo' $filtroSEDE_nit  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusAnti=$row['TOT'];
	$BASE_minusAnti=$row['BASE'];
	$DESCUENTO_minusAnti=$row['D'];
	$IVA_minusAnti=$row['IVA'];
		
}

/*
//////////////////////////////////////////////////////// TOTALES VENTAS //////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)  $filtroNOanuladas $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL=$row['TOT'];
	$BASE=$row['BASE'];
	$DESCUENTO=$row['D'];
	$IVA=$row['IVA'];	
}

*/
///////////////////////////////////////////////// TOTAL RECUPERACION CREDITOS /////////////////////////////////////////////////////////////////////////////////
$tot_comprobantes_ingreso=0;
$sql="SELECT SUM(valor-r_fte-r_ica) as TOT,cajero,num_fac FROM comprobante_ingreso  WHERE  id_cli!='' AND forma_pago='Contado' $filtroSEDE_cod_su AND  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (anulado!='ANULADO' ) GROUP BY cajero";
//secho "<li>$sql</li>";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$nomVende=nomTrim($row["cajero"]);
	$tot_comprobantes_ingreso+=$row['TOT'];
	$total_vendedores[$nomVende][4]+=$row['TOT'];
	
	
}


////***********************///////////////////////// INGRESOS VARIOS ///////////////////////////////////
$TOT_INGRESOS_VAR=0;
$sql="SELECT SUM(valor) as TOT,cajero,num_fac FROM comprobante_ingreso  WHERE   id_cli='' AND forma_pago='Contado' $filtroSEDE_cod_su AND  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (anulado!='ANULADO' ) GROUP BY cajero";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$TOT_INGRESOS_VAR+=$row['TOT'];
	
}

/*
///////////////////////////////////////////////// TOTAL COMPROBANTES ANTICIPO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroSEDE_cod_su";
$rs=$linkPDO->query($sql);
$tot_comprobantes_anti=0;
if($row=$rs->fetch())
{
	$tot_comprobantes_anti=$row['TOT'];
	
}
*/
/*
//////////////////////////////////////////////////// VENTAS NO GRAVADAS ///////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE  art_fac_ven.nit=fac_ven.nit $filtroSEDE_fac_ven_nit AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND iva=0 $filtroNOanuladas";
$rs=$linkPDO->query($sql );
if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}
*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// Num. Facturas por cada tipo /////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND anticipo_bono='NO' $filtroNOanuladas $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){$tot_fac_contado=$row['tot_fac'];}

$tot_fac_cheque=0;

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Cheque' AND anticipo_bono='NO' $filtroNOanuladas $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){$tot_fac_cheque=$row['tot_fac'];}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' AND anticipo_bono='NO' $filtroNOanuladas $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Cheque' AND anticipo_bono='NO'  $filtroNOanuladas $filtroSEDE_nit";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_cheque=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND anticipo_bono='NO' AND anticipo_bono='NO' $filtroNOanuladas $filtroSEDE_nit";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Debito' AND anticipo_bono='NO' $filtroNOanuladas $filtroSEDE_nit";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_debito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Transferencia Bancaria' AND anticipo_bono='NO' $filtroNOanuladas $filtroSEDE_nit";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tBanco=$row['tot_fac'];
	
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
//$ventas_tot=$iva16+$base_iva16+$base_iva5+$iva5+$excentas;
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------

$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' $filtroNOanuladas $filtroSEDE_nit";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_tarjetaCre=$row['TOT'];
	
}


///////////////////////////////////////////// BONOS/ANTICIPOS //////////////////////////////////////////
//todos
$sql="SELECT SUM(abono_anti) as TOT,COUNT(*) AS nf FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND abono_anti!=0 $filtroNOanuladas $filtroSEDE_nit";
$rs=$linkPDO->query($sql);

$tot_anticipos=0;
$tot_fac_anticipo=0;
if($row=$rs->fetch())
{
	$tot_anticipos=$row['TOT'];
	$tot_fac_anticipo=$row['nf'];
	
}


//contado
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT,ROUND(SUM(tot_bsf*tasa_cam),-1) as Bsf FROM fac_venta  WHERE tot_tarjeta=0 $filtroSEDE_nit  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND abono_anti!=0 $filtroNOanuladas ";
//echo "$sq";
$rs=$linkPDO->query($sql );
$efectivo_anticipos=0;
$totFac_antiContado=0;
while($row=$rs->fetch())
{
	$tipo_pago=$row['tipo_venta'];
	$abono_anti=$row['abono'];
	$abonoBsf=$row['Bsf'];
	$tot=$row['TOT'];
	$efectivo=$tot-$abono_anti-$abonoBsf;
	//echo "<li>$tot-$abono_anti-$abonoBsf</li>";
	if($efectivo<0)$efectivo=0;
	$totFac_antiContado=$row['nf'];
	$efectivo_anticipos+=$efectivo;	
}



$TOT_TARJETA_EXCEDENTE=0;
$contadoExcedenteTarjeta=0;
//Excedente Tarjeta
$sql="SELECT tot,tot_tarjeta,num_fac_ven as TOT FROM fac_venta  WHERE tot_tarjeta!=0 $filtroSEDE_nit  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' $filtroNOanuladas ";
//echo "$sql";
$rs=$linkPDO->query($sql );
while($row=$rs->fetch())
{

	$tot=$row['tot'];
	$contadoExcedenteTarjeta+= $row['tot']-$row['tot_tarjeta'];
    $TOT_TARJETA_EXCEDENTE+= $row['tot_tarjeta'];


}

//t. credito
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND abono_anti!=0 $filtroNOanuladas $filtroSEDE_nit";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
$anticipos_tCredito=0;
$totFac_antiTcredito=0;
while($row=$rs->fetch())
{
	$tipo_pago=$row['tipo_venta'];
	$abono_anti=$row['abono'];
	$tot=$row['TOT'];
	$efectivo=$tot-$abono_anti;
	//echo "<li>Efectivo $efectivo=$tot-$abono_anti;</li>";
	if($efectivo<0)$efectivo=0;
	$totFac_antiTcredito=$row['nf'];
	$anticipos_tCredito+=$efectivo;	
}

//t. debito
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Debito' AND abono_anti!=0 $filtroNOanuladas $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
$anticipos_tDebito=0;
$totFac_antiTdebito=0;
while($row=$rs->fetch())
{
	$tipo_pago=$row['tipo_venta'];
	$abono_anti=$row['abono'];
	$tot=$row['TOT'];
	$efectivo=$tot-$abono_anti;
	if($efectivo<0)$efectivo=0;
	$totFac_antiTdebito=$row['nf'];
	$anticipos_tDebito+=$efectivo;	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>ARQUEO DE CAJA</title>

<?php include_once("HEADER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};</script>
<style type="text/css">
.caja_cerrada{
	-webkit-transform: rotate(340deg);
	-moz-transform: rotate(340deg);
	-o-transform: rotate(340deg);
	writing-mode: lr-tb;
	position:relative;
	top:200px;
	left:300px;
	font-size:42px;
	}

</style>
</head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:relative; font-size:14px; padding:20px; padding-left:50px;" class=" " >
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" >
<span align="center" style="font-size:24px; font-weight:bold;">
<B>

<?php echo $titulo_pag ?>

</B>

</span>

<br>
<span align="center" style="font-size:14px; font-weight:bold;">
CONSECUTIVO INFORME:<span align="center" style="font-size:20px; font-weight:bold;"> # <?php echo "$CONSECUTIVO_INF" ?></span>
</span>
</p>
</td>

</tr>
</table>
Fecha Arqueo: <?PHP echo fecha($FechaHoy)."     Hora: ".$hora ?>
<br>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="font-size:24px; font-weight:bold;">
Desde: <?PHP echo fecha($_SESSION['fechaI'])."&nbsp; $horaI" ?>
</td>
<td style="font-size:24px; font-weight:bold;"> Hasta: <?php echo fecha($_SESSION['fechaF'])."&nbsp; $horaF" ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
<?php
$cajera="";
$sql="SELECT * FROM cajasb WHERE DATE(inicio)>='$fechaI' AND DATE(inicio)<='$fechaF' $filtroSEDE_cod_su";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$cajera=$row['usu'];
}

if(!empty($filtroCaja)){}
?>
<tr style=" font-size:24px;" height="40px">
<td><b>CAJA </b></td><td width="20px"></td><td colspan=""><b><?php echo "$CodCajero-$cajera"; ?></b></td>

<?php
$estado_caja="";
/*
$sql="SELECT * FROM cajas WHERE fecha='$fechaI' $filtroSEDE_cod_su";
	$rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
	{
	 $cod_caja=$row['cod_caja']; 
	 $estado_caja=$row['estado_caja'];
	}*/
	




?>
<td style="font-size:14px;">&nbsp;&nbsp;&nbsp; <B><div class="caja_cerrada"><?php //echo $estado_caja ?></div></B></td>
</tr>
<?php

?>
</table>
</td>
</tr>
<?php

$sql="SELECT MAX(`num_fac_ven`) ultimo,MIN(`num_fac_ven`) primero,COUNT(`num_fac_ven`) total,`prefijo`,`resolucion`,`fecha_resol`,`rango_resol` FROM `fac_venta` WHERE resolucion!='' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND ".VALIDACION_VENTA_VALIDA."  $filtroSEDE_nit GROUP BY `resolucion`,rango_resol,fecha_resol,prefijo
ORDER BY `fac_venta`.`fecha_resol` DESC";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{
	$tot_fac=$row['total'];
	$ult_fac=$row['ultimo'];
	$pri_fac=$row['primero'];

?>
<tr>
<td colspan="">
Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $row['resolucion'] ?> del <?php echo $row['fecha_resol'] ?> <?php echo $row['rango_resol'] ?>


</td>

<td>
<b>Desde: </b><?php echo $row['prefijo']."-".$pri_fac ?>
</td>
<td>
<b>Hasta: </b><?php echo $row['prefijo']."-".$ult_fac ?>
</td><td><b>Total: </b><?php echo $tot_fac; ?></td>
</tr>
<?php 
}
?>
</table>
<?php
 
if($multisede==1){$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,"all",$horaI,$horaF,$CodCajero);}
else {$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero);}

?>
<h2 align="left" class="uk-text-primary uk-text-bold"><b>VENTAS TOTALES</b></h2>
<div class="uk-grid " >



<?php

include("arq_ventas_tot.php");
?>

<?php


$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha_anula,fecha,anulado,num_fac_ven,nit,cod_caja,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE anulado='ANULADO'   $filtroSEDE_fac_ven_nit AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 $filtroNOanuladas ";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}




$sql="SELECT COUNT(*) as tot_fac FROM fac_venta  WHERE DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroSEDE_nit";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	}

$ventas_totB=$iva16B+$base_iva16B+$base_iva5B+$iva5B+$excentasB;


?>
<?php
$iva_taller=0;
$tot_taller=0;
$iva_otros=0;
$tot_otros=0;

$trabajoTerceros=0;


include("arqueo_cajas_diarias_ventas_productos.php");


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' $filtroNOanuladas $filtroSEDE_nit";

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

$sql="SELECT COUNT(*) nBonos
FROM  `fac_venta` 
WHERE bono !=0  
AND DATE( fecha ) >=  '$fechaI'
AND DATE( fecha ) <=  '$fechaF' $filtroSEDE_nit $filtroNOanuladas";
$rs=$linkPDO->query($sql);
$tot_bonos_casco=0;
if($row=$rs->fetch())
{
	$tot_bonos_casco=$row['nBonos'];
	
}




// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$TOT_CRE=tot_cre_car($fechaI,$fechaF,$codSuc,$filtroHora,$filtroNOanuladas);

$TOT_CONT=$total_contado+$tot_pesos-$TOT_CRE-$TOT_TARJETA_EXCEDENTE;


//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
<div class="uk-width-3-10">
<table align="left" cellpadding="0" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:10px;  background-color:#999">
<table cellspacing="1" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>FORMAS DE PAGO</b></td><TD>#</TD></tr>
</table>
</td></tr>

<tr>
<td>CONTADO:</td><td><span id="iva5"><?PHP echo money(redondeo($TOT_CONT )) ?></span></td>
<td align="right" style="border:ridge"><?PHP echo $tot_fac_contado ?></td>
</tr>
    
    
<?php if(1){ ?>
   
<tr>
<td>CHEQUE:</td><td><span id=""><?PHP echo money($tot_cheque) ?></span></td>
<td align="right" style="border:ridge" ><span><?PHP echo $tot_fac_cheque ?></span></td>
</tr>
    
<?php }?>
    
<tr >
<?php $tot_tCredito+=$anticipos_tCredito; ?>
<td>T. CREDITO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_tCredito)) ?></span></td>
<td align="right" style="border:ridge"><span><?PHP echo $tot_fac_tarjeta_credito/*+$totFac_antiTcredito*/ ?></span></td>
</tr>
<tr>
<?php $tot_tDebito+=$anticipos_tDebito; ?>
<td>T. DEBITO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_tDebito)) ?></span></td>
<td align="right" style="border:ridge"><span><?PHP echo $tot_fac_tarjeta_debito/*+$totFac_antiTdebito*/ ?></span></td>
</tr>
<?php
if($MODULES["PAGO_EFECTIVO_TARJETA"]==1){
?>
<tr>
<td>TARJETAS (excedentes):</td><td><span id="iva5"><?PHP echo money(redondeo($TOT_TARJETA_EXCEDENTE)) ?></span></td>
<td align="right" style="border:ridge"><span><?PHP echo ""/*+$totFac_antiTdebito*/ ?></span></td>
</tr>
<?php
}

?>

<tr>
<?php  ?>
<td>TRANSFE:</td><td><span  ><?PHP echo money(redondeo($tot_tBanco)) ?></span></td>
<td align="right" style="border:ridge"><b><?PHP echo $tot_fac_tBanco/*+$totFac_antiTcredito*/ ?></b></td>
</tr>

<tr>
<td>CR&Eacute;DITO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_Credito+$TOT_CRE)) ?></span></td>
<td align="right" style="border: ridge"><span><?PHP echo $tot_fac_credito ?></span></td>
</tr>
<?php
if($MODULES["ANTICIPOS"]==1){
?>
<tr>
<td>ANTICIPOS:</td><td><?PHP echo money(redondeo($tot_anticipos)) ?></td>
<td align="right" style="border: ridge"><span><?PHP echo $tot_fac_anticipo ?></span></td>
</tr>
<?php
}
?>

<?php
if($usar_bsf==1){
?>
<tr>
<td>BsF (en pesos):</td><td><?PHP echo money(redondeo($totBsF2)) ?></td>
<td align="center"><span><?PHP echo ""; ?></span></td>
</tr>
<?php
}
?>
<!--
<tr>
<td>Bol&iacute;vares:</td><td><span id="iva5"><?PHP echo money3(redondeo($totBsF )) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_contado ?></span></td>
</tr>
-->
<tr style="font-size:12px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money(redondeo($TOT_CONT+$tot_tCredito+$tot_tDebito+$tot_Credito+$tot_anticipos+$totBsF2+$tot_tBanco+$TOT_TARJETA_EXCEDENTE)) ?>
</td>
</tr>
</table>
<!--
</div>
<div class="uk-width-1-3">
-->
</div>
<div class="uk-width-2-10">
<table align="left" cellpadding="0" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:10px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>ABONOS CARTERA</b></td><TD>#</TD></tr>


</table>
</td></tr>
<?php
$TOT_INGRESOS=tot_comp_ingreso($fechaI,$fechaF,$filtroHora,$filtroSEDE_cod_su,"valor");
$COUNT_INGRESOS=tot_comp_ingreso($fechaI,$fechaF,$filtroHora,$filtroSEDE_cod_su,"num");
$SUM_INGRESOS=array_sum($TOT_INGRESOS);

foreach($FP_ingresos as $key=> $result)
{
	$val=money($TOT_INGRESOS[$result]);
        
        $tipoPago=$result;
        if($result=="Tranferencia Bancaria"){$tipoPago="Transfe";}
        if($result=="Tarjeta Debito"){$tipoPago="T. Debi";}
        if($result=="Tarjeta Credito"){$tipoPago="T. Cred";}
	if($TOT_INGRESOS[$result]>0){echo "<tr><td>$tipoPago</td><td>$val</td><td align=\"right\" width=\"20px\" style=\"border: ridge\">$COUNT_INGRESOS[$result]</td></tr>";}
	}
?>
 
<tr style="font-size:12px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money($SUM_INGRESOS) ?>
</td>
</tr>
</table>
</div>
<div class="uk-width-2-10">
<?php if($MODULES["ANTICIPOS"]==1){ ?>
<table align="rigt" cellpadding="0" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:10px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>ANTICIPOS/BONOS</b></td><TD>#</TD></tr>


</table>
</td></tr>

<tr>
<td>CONTADO:</td><td><span id="iva5"><?PHP echo money(redondeo($TOT_ANTI_BONO_CONTADO[0])) ?></span></td>
<td align="center" ><span><?PHP echo $TOT_ANTI_BONO_CONTADO[1] ?></span></td>
</tr>

<tr >
<td>TARJETAS CREDITO:</td><td><span id="iva5"><?PHP echo money(redondeo($TOT_ANTI_BONO_TCREDITO[0])) ?></span></td>
<td align="center"><span><?PHP echo $TOT_ANTI_BONO_TCREDITO[1] ?></span></td>
</tr>
<tr>
<td>TARJETAS DEBITO:</td><td><span id="iva5"><?PHP echo money(redondeo($TOT_ANTI_BONO_TDEBITO[0])) ?></span></td>
<td align="center"><span><?PHP echo $TOT_ANTI_BONO_TDEBITO[1] ?></span></td>
</tr>
<tr>
<!--<td>ANTICIPOS/BONOS:</td><td><?PHP echo money(redondeo($TOTAL_ANTI_BONO[0])) ?></td>
<td align="center"><span><?PHP echo $TOTAL_ANTI_BONO[1] ?></span></td>.
-->
<td>&nbsp;</td>
</tr>
<tr>
<!--<td>ANTICIPOS/BONOS:</td><td><?PHP echo money3(redondeo($TOTAL_ANTI_BONO[0])) ?></td>
<td align="center"><span><?PHP echo $TOTAL_ANTI_BONO[1] ?></span></td>.
-->
<td>&nbsp;</td>
</tr>
<tr style="font-size:12px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money(redondeo($TOTAL_ANTI_BONO[0])) ?>
</td>
</tr>
</table>
<?php }?>



</div>

<div class="uk-width-1-1 uk-grid" style="border-style: ridge">
 
<?php 
if($MODULES["ARQ_VEN_RESOL"]==0){include("arqueo_ventas_vendedores.php");}
else{include("arq_ventas_resol.php");}
 ?>
 

</div>

<div class="uk-grid">

<table align="left" width="100%">
<tr>
<td width="30%"  valign="top" class="   ">

 
<H2 align="left" class="uk-text-primary uk-text-bold">CIERRE DE CAJA</H2>

<?php

$sql="SELECT base_caja FROM ajuste_cajas WHERE cod_su='$codSuc' AND fecha='$fechaI'";
$rs=$linkPDO->query($sql);
$baseCaja=0;
if($row=$rs->fetch())
{
	$baseCaja=$row["base_caja"];
}
else {
	
	$sql="SELECT base_caja,MAX(fecha) FROM ajuste_cajas WHERE cod_su='$codSuc'";
	$rs=$linkPDO->query($sql);
 
if($row=$rs->fetch())
{
	$baseCaja=$row["base_caja"];
}
else {$baseCaja=0;}
	}


$TOTAL_CONTADO=$TOT_VENTAS0516[20][2]+$baseCaja+$TOT_INGRESOS_VAR+$TOT_CONT+$tot_comprobantes_ingreso+$tot_comprobantes+$TOT_ANTI_BONO_CONTADO[0]+$efectivo_anticipos-$TOTAL_minusAnti-$tot_devolucion_comp_anti-$TOTAL_minus-$TOT_COMP_EGRESOS-$TOT_RETE;


$ii=0;



$linkPDO->exec("INSERT IGNORE INTO ajuste_cajas(fecha,valor_base,cod_su,base_caja) VALUES('$fechaI','$TOTAL_CONTADO','$codSuc','$baseCaja')");
t1("UPDATE ajuste_cajas SET valor_base='$TOTAL_CONTADO' WHERE fecha='$fechaI' AND cod_su='$codSuc'");

$sql="SELECT * FROM ajuste_cajas WHERE fecha='$fechaI' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();

$valorEntrega=$row["valor_entrega"];
$valorDiff=$row["valor_diferencia"];
$valorBase=$row["valor_base"];

if($valorEntrega==0){$valorDiff=-0;}

$functValor="mod_tab_row('tabTD01$ii','ajuste_cajas','valor_entrega','$valorEntrega',' fecha=\'$fechaI\' AND cod_su=\'$codSuc\'','$ii','text','','');";
$functValorBaseCaja="mod_tab_row('tabTD02$ii','ajuste_cajas','base_caja','$baseCaja',' fecha=\'$fechaI\' AND cod_su=\'$codSuc\'','$ii','text','','');";
?>
<table align="left" cellpadding="0" cellspacing="0" width="70%"  style="font-size:10px;">
<thead>
</thead>
<tbody>
<tr>
<td width="600px" ><B>BASE CAJA:</B></td>
<td width="200px" id="tabTD02<?php echo $ii; ?>" onDblClick="<?php echo $functValorBaseCaja; ?>"> <?php echo money3($baseCaja);?></td>
</tr>

<tr>
<tr>
<td width="600px"><B>CONTADO:</B></td><td width="200px"><span id="iva5"><?php 
//echo money3(redondeo($total_contado+$tot_tCredito+$tot_tDebito)) 
echo money3(redondeo($TOT_CONT))
?></span></td>
</tr>
<?php if($MODULES["RETENCIONES"]==1){?>
<tr>
<td width="600px" ><B>RETENCIONES:</B></td>
<td width="200px" id="tabTD02<?php echo $ii; ?>" onDblClick=""> -<?php echo money3($TOT_RETE);?></td>
</tr>
<?php }?>
<tr>
<td >
<b>ABONOS CARTERA:</b></td>

<td> <span id="total_excentas"><?php echo money3(redondeo($tot_comprobantes+$tot_comprobantes_ingreso)) ?></span></td>
</tr>



<tr>
<td >
<b>INGRESOS VARIOS</b></td>

<td> <span ><?php echo money3(redondeo($TOT_INGRESOS_VAR)) ?></span></td>
</tr>

<?php if($MODULES["ANTICIPOS"]==1){ ?>
<tr style="font-size:12px; ">
<td><b>ANTICIPOS:</b></td><td> <span id="total2"><?php echo money3(redondeo($TOT_ANTI_BONO_CONTADO[0])) ?></span></td>
</tr>
<tr style="font-size:12px; ">
<td><b>FAC. ANTICIPO:</b></td><td> <span id="total2"><?php echo money3(redondeo($efectivo_anticipos)) ?></span></td>
</tr>
<?php } ?>

<tr style="font-size:12px; ">
<td><b>DEVOLUCIONES CONTADO</b></td><td valign="bottom"> <span id="total2"><?php echo money3(redondeo($TOTAL_minus)) ?></span></td>
</tr>
<?php if($MODULES["ANTICIPOS"]){ ?>
<tr style="font-size:12px; font-family:'MS Serif', 'New York', serif;">
<td><b>DEVOLUCIONES ANTI.</b></td><td valign="bottom"> <span id="total2">-<?php echo money3(redondeo($TOTAL_minusAnti+$tot_devolucion_comp_anti)) ?></span></td>
</tr>
<?php } ?>

<?php if($MODULES["GASTOS"]==1){ ?>
<tr style="font-size:12px; font-family:'MS Serif', 'New York', serif;">
<td><b>GASTOS (Efectivo)</b></td><td valign="bottom"> <span id="total2">-<?php echo money3(redondeo($TOT_COMP_EGRESOS)) ?></span></td>
</tr>
<?php } ?>
<?php
if($usar_bsf==1){
?>
<tr style="font-size:12px; ">
<td><b>TOTAL Bs.:</b></td><td> <span id="total2"><?php echo money3(redondeo($totBsF)) ?></span></td>
</tr>
<?php
}
?>
<tr style="font-size:12px; ">
<td><b>TOTAL Pesos:</b></td><td> <span id="total2"><?php echo money3(redondeo($TOTAL_CONTADO)) ?></span></td>
</tr>
</tbody>
</table>
 

</td>

<td valign="top" width="40%">

 
<table style="font-size:10px; width:300px;" cellspacing="0"  cellpadding="0" align="right" class="" width="100%">
<thead>
<?php
if($usar_bsf==1){
?>
<tr style="font-size:10px; ">
<td><b>TOTAL Bs.:</b></td><td> <span id="total2"><?PHP echo money3(redondeo($totBsF)) ?></span></td>
</tr>
<?php
}


?>
<tr style="font-size:10px; ">
<td><b>TOTAL Pesos:</b></td><td> <span id="total2"><?PHP echo money3(redondeo($TOTAL_CONTADO)) ?></span></td>
</tr>
</thead>
<tbody>
<tr>
<td >EFECTIVO:</td>
<TD id="tabTD01<?php echo $ii ?>" onDblClick="<?php echo $functValor; ?>"><?php echo money3($valorEntrega); ?></TD>
</tr>
<tr>
<td>DIFERENCIA:</td><TD><?php echo money3($valorDiff*(-1)); ?></TD>
</tr>
<tr>
<td>NOVEDADES:</td>
<TD></TD>
</tr>
</tbody>
</table>
 
</td>
</tr>

<tr>
<td colspan="2">
<br><br>
<div class="uk-width-1-1">
<table width="100%" cellpadding="4" style="font-size:12px;">
<tr>
<td>C./Coordinador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Jefe venta:
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
 
</td>
</tr>

</table>
</div>



<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="imprimir();" />
</div> 
<?php //echo $sql3; ?>

 
<?php
if($TOTALa>0){
?>
<br><br><br>
<br><br><br>
<div class="uk-width-1-1 uk-grid">
<h2 align="left"><b>ANULADAS</b></h2>
<table style="font-size:12px" cellspacing="0">
<!--
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP //echo money3(redondeo($BASEa-$DESCUENTOa)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP //echo money3(redondeo($IVAa)) ?></span></td>
</tr>

<tr>
<td>BASE IVA 5%:</td><td><span><?PHP //echo money3(redondeo($base_iva5B)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 5%:</td><td><span ><?PHP //echo money3(redondeo($iva5B)) ?></span></td>
</tr>

<tr>
<td>VENTAS NO GRAVADAS:</td><td> <span ><?PHP //echo money3(redondeo($excentasB)) ?></span></td>
</tr>
-->
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money3(redondeo($TOTALa)) ?></span></td>
</tr>
<tr>
<td>TOTAL ANULADAS:</td><td> <span ><?PHP echo $tot_fac ?></span></td>
</tr>
</table>

<table cellpadding="2" cellspacing="0" style="font-size:12px" align="left">
<tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">
<th>#</th><td>Num. Factura</td><td>Vendedor</td><td>Total</td><td>Forma pago</td>

<?php
$sql="SELECT * FROM fac_venta WHERE DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroSEDE_nit";
$rs=$linkPDO->query($sql);
$con=0;
while($row=$rs->fetch()){
$con++;
$NFac=$row['num_fac_ven'];
$vendedor=$row['vendedor'];
$totalFac=money3($row['tot']);	
$formaPago=$row["tipo_venta"];
?>
<tr>
<th><?php echo $con ?></th>
<td align="center"><?php echo $NFac ?></td>
<td align="center"><?php echo $vendedor?></td>
<td align="right"><?php echo $totalFac ?></td>
<td align="left"><?php echo $formaPago ?></td>
</tr>
<?php
	
}

?>
</tr>
</table>
</div>
<?php
}
?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER"; ?>"></script> 
<?php include_once("FOOTER_UK.php"); ?>
</div>
</body>
</html>