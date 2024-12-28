<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$horaI=s("horaI");
$horaF=s("horaF");
$filtroHora="";
$filtroHoraAnula="";

if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";
$cod_su=$_SESSION['cod_su'];
$CodCajero=$_SESSION['cod_caja_arq'];

$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (anulado!='ANULADO' $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";

$titulo_pag="";
if($fechaI==$fechaF)$titulo_pag="CAJA POR D&Iacute;A";
else $titulo_pag="CAJAS POR D&Iacute;AS";

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

//$TOT_COMP_EGRESOS=tot_comp_egreso($fechaI,$fechaF,$codSuc,"","contado");
$TOT_COMP_EGRESOS=0;//;tot_comp_egreso($fechaI,$fechaF,$codSuc,"","contado");
$sql="SELECT SUM(valor) as t,cajero FROM comp_egreso WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND cod_caja='$CodCajero' AND tipo_pago='Contado' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) GROUP BY cajero";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch())
	{
		$nomVende=nomTrim($row['cajero']);
		$TOT_COMP_EGRESOS+=$row['t'];
		//$total_vendedores[$nomVende][5]+=$row['t'];
	}

//////////////////////////////////////////////////// TOT BsF X VENDEDOR //////////////////////////////////////////////

$sql="SELECT SUM(tot_bsf) as TOT,SUM(tot) as TOT2,vendedor FROM fac_venta  WHERE  nit=$codSuc AND tot_bsf!=0 AND tot_bsf>0  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta!='Credito' AND cod_caja='$CodCajero'  $filtroNOanuladas GROUP BY vendedor";
$totBsF=0;
$totBsF2=0;
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$nomVende=nomTrim($row['vendedor']);
	$totBsF+=$row['TOT'];
	$totBsF2+=$row['TOT2'];
	
	
}


$TOT_ANTI_BONO_CONTADO=tot_anticipos($fechaI,$fechaF,$codSuc,"contado","p");
$TOT_ANTI_BONO_TDEBITO=tot_anticipos($fechaI,$fechaF,$codSuc,"tDebito","p");
$TOT_ANTI_BONO_TCREDITO=tot_anticipos($fechaI,$fechaF,$codSuc,"tCredito","p");
$TOTAL_ANTI_BONO=tot_anticipos($fechaI,$fechaF,$codSuc,"p","p");
$TOTAL_ANTI_BONO_COBRADOS=tot_anticipos($fechaI,$fechaF,$codSuc,"p","p","si");

$tot_Credito=tot_credito($fechaI,$fechaF,$codSuc,$CodCajero);

$TOTAL=0;

/////////////////////////////////////////////////////////// TOTALES FAC ANULADAS /////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTALa=$row['TOT'];
	$BASEa=$row['BASE'];
	$DESCUENTOa=$row['D'];
	$IVAa=$row['IVA'];
	
	
}
///////////////////////////////////// TOT. DEVOLUCIONES COMP. ANTICIPOS////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula)";
$rs=$linkPDO->query($sql );
$tot_devolucion_comp_anti=0;
if($row=$rs->fetch())
{
	$tot_devolucion_comp_anti=$row['TOT'];
	
}

///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CONTADO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero' AND tipo_venta!='Anticipo' AND tipo_venta!='Credito'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
		
}
// ---------------------------------- DEVOLUCIONES EXENTAS CONTADO-------------------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula,cod_caja FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND cod_caja='$CodCajero' AND fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.tipo_venta!='Anticipo' AND fac_ven.tipo_venta!='Credito' AND anulado='ANULADO' and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}


///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CREDITO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero' AND tipo_venta!='Anticipo' AND tipo_venta='Credito'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusC=$row['TOT'];
	$BASE_minusC=$row['BASE'];
	$DESCUENTO_minusC=$row['D'];
	$IVA_minusC=$row['IVA'];
		
}
// ---------------------------------- DEVOLUCIONES EXENTAS CREDITO-------------------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula,cod_caja FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.tipo_venta!='Anticipo' AND fac_ven.tipo_venta='Credito' AND anulado='ANULADO' and fac_ven.nit=$codSuc AND cod_caja='$CodCajero' AND art_fac_ven.nit=fac_ven.nit AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc      AND cod_caja='$CodCajero' AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasBcre=$row['excentas'];
	
	if($excentasBcre==""){$excentasBcre=0;}
}

// --------------------------- DEVOLUCIONES FAC. ANTICIPOS--------------------------------------------------------

$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero' AND tipo_venta='Anticipo'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusAnti=$row['TOT'];
	$BASE_minusAnti=$row['BASE'];
	$DESCUENTO_minusAnti=$row['D'];
	$IVA_minusAnti=$row['IVA'];
		
}


//////////////////////////////////////////////////////// TOTALES VENTAS //////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)  AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL=$row['TOT'];
	$BASE=$row['BASE'];
	$DESCUENTO=$row['D'];
	$IVA=$row['IVA'];	
}


///////////////////////////////////////////////// TOTAL RECUPERACION CREDITOS /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero' AND num_fac_ven NOT IN(SELECT num_fac FROM comprobante_ingreso) AND estado='PAGADO'  AND DATE(fecha_pago)>='$fechaI' AND DATE(fecha_pago)<='$fechaF' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}


$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)  AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes_ingreso=$row['TOT'];
	
}

/*
///////////////////////////////////////////////// TOTAL COMPROBANTES INGRESO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}
*/
///////////////////////////////////////////////// TOTAL COMPROBANTES ANTICIPO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)";
$rs=$linkPDO->query($sql);
$tot_comprobantes_anti=0;
if($row=$rs->fetch())
{
	$tot_comprobantes_anti=$row['TOT'];
	
}


//////////////////////////////////////////////////// VENTAS EXENTAS ///////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,cod_caja FROM fac_venta  ) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND art_fac_ven.nit=fac_ven.nit AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  fac_ven.nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// Num. Facturas por cada tipo /////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Mostrador' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_fac_mostrador=$row['tot_fac'];}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Empleados' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_empleados=$row['tot_fac'];
	
}
/* fanalca
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Garantia FANALCA' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_FANALCA=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (tipo_cli =  'Taller Honda' OR tipo_cli='Garantia FANALCA') AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_taller=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Otros Talleres' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_otros_talleres=$row['tot_fac'];
	
}
*/
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_contado=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Cheque' AND anticipo_bono='NO'  AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_cheque=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND anticipo_bono='NO' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Debito' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_debito=$row['tot_fac'];
	
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$ventas_tot=$iva16+$base_iva16+$base_iva5+$iva5+$excentas;
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
$sql="SELECT COUNT(fac_ven.num_fac_ven) AS tot_ins,serv_fac_ven.nit AS codSu_serv, SUM( precio ) AS base_iva, SUM( (precio_iva-precio )) AS iva, SUM( precio_iva-(precio_iva*(dcto/100)) ) AS TOT,serv_fac_ven.prefijo FROM serv_fac_ven INNER JOIN (SELECT prefijo,fecha, anulado, num_fac_ven, nit, tipo_venta, tipo_cli FROM fac_venta ) AS fac_ven ON fac_ven.num_fac_ven = serv_fac_ven.num_fac_ven WHERE  fac_ven.prefijo=serv_fac_ven.prefijo AND fac_ven.nit =$codSuc AND serv_fac_ven.nit= fac_ven.nit AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli =  'Taller Honda' AND man='Inspeccion' AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
$strIns=$sql;
$rs=$linkPDO->query($sql);
$tot_inspecciones="";
$iva_inspecciones="";
$Baseiva_inspecciones="";

if($row=$rs->fetch())
{
	$tot_inspecciones=$row['TOT'];
	$iva_inspecciones=$row['iva'];
	$Baseiva_inspecciones=$row['base_iva'];
	$tot_fac_inspecciones=$row['tot_ins'];	
}


//Garantia FANALCA
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Garantia FANALCA' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_FANALCA=$row['TOT'];
	
}


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_contado=$row['TOT'];
	
}
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Cheque' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_cheque=$row['TOT'];
	
}
*/
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_tarjetaCre=$row['TOT'];
	
}


//////////////////////////////////////////////////////////////////// BONOS/ANTICIPOS ///////////////////////////////////////////////////////////////////
//todos
$sql="SELECT SUM(tot) as TOT,COUNT(*) AS nf FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);

$tot_anticipos=0;
$tot_fac_anticipo=0;
if($row=$rs->fetch())
{
	$tot_anticipos=$row['TOT'];
	$tot_fac_anticipo=$row['nf'];
	
}


//contado
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
//echo "$sq";
$rs=$linkPDO->query($sql);
$efectivo_anticipos=0;
$totFac_antiContado=0;
while($row=$rs->fetch())
{
	$tipo_pago=$row['tipo_venta'];
	$abono_anti=$row['abono'];
	$tot=$row['TOT'];
	$efectivo=$tot-$abono_anti;
	if($efectivo<0)$efectivo=0;
	$totFac_antiContado=$row['nf'];
	$efectivo_anticipos+=$efectivo;	
}
//t. credito
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
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
	if($efectivo<0)$efectivo=0;
	$totFac_antiTcredito=$row['nf'];
	$anticipos_tCredito+=$efectivo;	
}

//t. debito
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Debito' AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>INFORME DIARIO DE VENTAS</title>
<link rel="stylesheet" type="text/css" href="css/unsemantic-master/assets/stylesheets/unsemantic-grid-responsive.css" />
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
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
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:relative; font-size:12px;" class="grid-container">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:32px; font-weight:bold;">
<span align="center"><B>

<?php echo $titulo_pag ?>

</B></span>
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
$cajera=cajeros_list();
?>
<tr style=" font-size:24px;">
<td><b>Cajero </b></td><td width="20px"></td><td colspan=""><b><?php echo $cajera[$CodCajero] ?></b></td>

<?php
$estado_caja="";
$sql="SELECT * FROM cajas WHERE fecha='$fechaI' AND cod_su=$codSuc";
	$rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
	{
	 $CodCajero2=$row['cod_caja']; 
	 $estado_caja=$row['estado_caja'];
	}



$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)  AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	$ult_fac=$row['ultima'];
	$pri_fac=$row['primera'];
}
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND cod_caja='$CodCajero' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_facCre=$row['tot_fac'];
	$ult_facCre=$row['ultima'];
	$pri_facCre=$row['primera'];
}

?>
<td style="font-size:18px;">&nbsp;&nbsp;&nbsp; <B><div class="caja_cerrada"><?php echo $estado_caja ?></div></B></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="4"><b>Facturas POS </b>IMPRESORA: <?PHP ECHO  "BIXOLON MODELO: SRP-350 S/N: IMPCHKA10110096" ?><br>
Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $ResolContado ?> del <?php echo $FechaResolContado ?> <?php echo $RangoContado ?>


</td>
</tr>
<tr>
<td>
<b>Desde Fac No. <?php echo $pri_fac ?></b>
</td>
<td>
<b>Hasta Fac No. <?php echo $ult_fac ?></b>
</td><td><b>Total Fac. <?php echo ($tot_fac) ?></b></td>
</tr>
<!--<tr>
<td colspan="4">
<hr align="center" width="100%">
<b>Facturas COMPUTADOR </b><br>
Autorizaci&oacute;n numeraci&oacute;n de Facturaci&oacute;n Dian No. <?php echo $ResolCredito ?> del <?php echo $FechaResolCredito ?> <?php echo $RangoCredito ?>


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
-->
</table>
<?php
$base_tot=redondeo(redondeo((($TOTAL-$excentas)/1.16 /*+ $excentas*/)));
$iva_tot=redondeo(($TOTAL-$excentas)-($TOTAL-$excentas)/1.16);

$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,$codSuc,$horaI,$horaF,$CodCajero);
?>
<div class="grid-100">
<h2 align="left"><b>VENTAS TOTALES</b></h2>
<table style="font-size:12px" cellspacing="0">
<tr>
<td colspan="4">VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money3(redondeo($TOT_VENTAS0516[0][0])) ?></span></td>
</tr>
<tr>
<td colspan="4">BASE IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][2]) ?></span></td>
</tr>
<tr>
<td colspan="4">BASE IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][2]) ?></span></td>
</tr>

<tr>
<td colspan="4">VENTAS TOTALES:</td><td> <span ><?PHP echo money3(redondeo($TOT_VENTAS0516[1][1])) ?></span></td>
</tr>

</table>
</div>
<?php


$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha_anula,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE anulado='ANULADO'  and fac_ven.nit=$cod_su AND art_fac_ven.nit=fac_ven.nit AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}




$sql="SELECT COUNT(*) as tot_fac FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) ";

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
$total_vendedores[]="";
////////////////////////////////////////////////////// LISTA VENDEDORES ////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT vendedor from fac_venta WHERE nit=$codSuc GROUP BY vendedor");
while($row=$rs->fetch())
{
	$total_vendedores[ucwords(strtolower($row["vendedor"]))]=0;
	
}

///////////////////////////////////////////////// REPUESTOS //////////////////////////////////////////////////////////////////////
//$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.cod_su=$codSuc AND cod_caja='$CodCajero' AND anticipo_bono='NO'   AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  cod_su=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula))";
/*$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND cod_caja='$CodCajero' AND anticipo_bono='NO'   AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";*/


$sql="SELECT entrega_bsf,art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo,tot_bsf FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND cod_caja='$CodCajero' AND abono_anti=0   AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
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

$tot_tCredito=0;
$tot_tDebito=0;
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
	$subTot=money($row['sub_tot']*1);
	$IVA=money($row['iva']*1);
	$des=$row['des'];
	$cant=$row['cant'];
	$valor=money($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=ucwords(strtolower($row["vendedor"]));
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	
	$totB=$row['tot_bsf'];
	
	$entregaBsf=$row['entrega_bsf'];
	
	
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
	
	if($tipo_venta=="Contado" && ($totB<=0 || $entregaBsf<=0))
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
	if($tipo_venta=="Tarjeta Credito")
	{
		$tot_tCredito+=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Tarjeta Debito")
	{
		$tot_tDebito+=$row['sub_tot']*1;
		
		}
	
	
}// FIN ARTICULOS


/*

///////////////////////////////////// LISTA DEVOLUCIONES ///////////////////////////////////////////////
$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);

$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money($row['sub_tot']*1);
	$IVA=money($row['iva']*1);
	$des=$row['des'];
	$cant=$row['cant'];
	$valor=money($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=ucwords(strtolower($row["vendedor"]));
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
	
	if($tipo_venta=="Contado")
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
	if($tipo_venta=="Tarjeta Credito")
	{
		$tot_tCredito-=$row['sub_tot']*1;
		
		}
	if($tipo_venta=="Tarjeta Debito")
	{
		$tot_tDebito-=$row['sub_tot']*1;
		
		}
	
	
}// FIN DEVOLUCIONES

*/

if($fechaI==$fechaF){
?>
<!--
<div class="grid-100">
<h2>RESUMEN DE VENTAS</h2>
<table align="left" cellpadding="4" cellspacing="0" frame="box" rules="all">
<thead>
<tr>
<th>COD.</th>
<th>PRODUCTO</th><th>CANT.</th><th>TOT. VENTA</th>
</tr>
</thead>
<tbody>
<?php 
$sql="SELECT a.ref, a.cod_barras,a.talla,a.color, a.des,SUM(a.cant) as cant, SUM(a.sub_tot) as stot FROM `art_fac_ven` a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND (DATE(b.fecha)>='$fechaI' AND DATE(b.fecha)<='$fechaF'  )  AND b.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula)) GROUP BY des";

$rs=$linkPDO->query($sql);
$STOT=0;
while($row=$rs->fetch())
{
	$des=$row['des'];
	$talla=$row['talla'];
	$codB=$row['ref'];
	$color=$row['color'];
	$cant=$row['cant']*1;
	$stot=$row['stot']*1;
	$STOT+=$stot;
	?>

<tr>
<td><?php echo "$codB" ; ?></td>
<td><?php echo "$des T: $talla, C: $color" ; ?></td>
<td><?php echo "$cant" ; ?></td>
<td><?php echo money("$stot") ; ?></td>
</tr>
    <?php
	
}


 ?>
 </tbody>
 <tfoot>
 <th colspan="3">TOTAL VENTAS:</th><th><?php echo money("$STOT") ; ?></th>
 </tfoot>
</table>
</div>
-->
<?php
}////////////// fin if fecha 1 dia/////////
?>
<br><br>
<!--
<table align="right" cellpadding="4" cellspacing="0" frame="box" rules="all">
<thead>
<tr>
<tr>
<th colspan="2">EXISTENCIAS CIERRE CAJA</th>
</tr>
<th>PRODUCTO</th><th>CANT.</th>
</tr>
</thead>
<tbody>
<?php

?>
</tbody>
</table>
-->
<?php
?>

<?php
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND cod_caja='$CodCajero'  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

$sql="SELECT COUNT(*) nBonos
FROM  `fac_venta` 
WHERE nit =$codSuc AND cod_caja='$CodCajero' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))
AND bono !=0
AND DATE( fecha ) >=  '$fechaI'
AND DATE( fecha ) <=  '$fechaF'";
$rs=$linkPDO->query($sql);
$tot_bonos_casco=0;
if($row=$rs->fetch())
{
	$tot_bonos_casco=$row['nBonos'];
	
}

?>
<div class="grid-100">
<table align="left" cellpadding="4" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:14px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>FORMAS DE PAGO</b></td><TD>#</TD></tr>
</table>
</td></tr>

<tr>
<td>CONTADO:</td><td><span id="iva5"><?PHP echo money(redondeo($total_contado )) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_contado ?></span></td>
</tr>
<tr>
<td>TARJETAS CREDITO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_tCredito/*+$anticipos_tCredito*/)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_tarjeta_credito/*+$totFac_antiTcredito*/ ?></span></td>
</tr>
<tr>
<td>TARJETAS DEBITO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_tDebito/*+$anticipos_tDebito*/)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_tarjeta_debito/*+$totFac_antiTdebito*/ ?></span></td>
</tr>
<tr>
<td>CR&Eacute;DITO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_Credito)) ?></span></td>
<td align="center"><span><?PHP echo $tot_fac_credito ?></span></td>
</tr>
<tr>
<td>FACTURAS POR ANTICIPO:</td><td><?PHP echo money(redondeo($tot_anticipos)) ?></td>
<td align="center"><span><?PHP echo $tot_fac_anticipo ?></span></td>
</tr>

<tr style="font-size:16px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money(redondeo($tot_Credito+$total_contado+$tot_tCredito+$tot_tDebito+$tot_anticipos)) ?>
</td>
</tr>
</table>

<?php if($MODULES["ANTICIPOS"]==1){ ?>
<table align="left" cellpadding="4" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:14px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>BOLIVARES</b></td><TD>#</TD></tr>
</table>
</td></tr>

<tr>
<td>BsF:</td><td><span  ><?PHP echo money($totBsF) ?></span></td>
<td align="center"><span><?PHP echo $TOT_ANTI_BONO_CONTADO[1] ?></span></td>
</tr>
<tr>
<td>Equiv. Pesos:</td><td><span id="iva5"><?PHP echo money($totBsF2) ?></span></td>
<td align="center"><span><?PHP echo $TOT_ANTI_BONO_TCREDITO[1] ?></span></td>
</tr>

<!--<td>ANTICIPOS/BONOS:</td><td><?PHP echo money(redondeo($TOTAL_ANTI_BONO[0])) ?></td>
<td align="center"><span><?PHP echo $TOTAL_ANTI_BONO[1] ?></span></td>.
-->
<td>&nbsp;</td>
</tr>
<tr>
<!--<td>ANTICIPOS/BONOS:</td><td><?PHP echo money(redondeo($TOTAL_ANTI_BONO[0])) ?></td>
<td align="center"><span><?PHP echo $TOTAL_ANTI_BONO[1] ?></span></td>.
-->
<td>&nbsp;</td>
</tr>
<tr style="font-size:16px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money($totBsF) ?> BsF
</td>
</tr>
</table>
<?php }?>
</div>
<br><br><br><br><br>
<div class="grid-40">
<H2 align="left">CUADRE DE CAJA</H2>

<?php

$TOTAL_CONTADO=$total_contado+$tot_comprobantes_ingreso+$tot_comprobantes+$TOT_ANTI_BONO_CONTADO[0]+$efectivo_anticipos-$TOTAL_minusAnti-$tot_devolucion_comp_anti-$TOTAL_minus-$TOT_COMP_EGRESOS;
?>

<table align="left" cellpadding="0" cellspacing="0">
<tr>
<td><B>CONTADO:</B></td><td><span id="iva5"><?PHP 
//echo money(redondeo($total_contado+$tot_tCredito+$tot_tDebito)) 
echo money(redondeo($total_contado))
?></span></td>
</tr>

<tr>
<td >
<b>RECUPERACI&Oacute;N CR&Eacute;DITOS:</b></td>

<td> <span id="total_excentas"><?PHP echo money(redondeo($tot_comprobantes+$tot_comprobantes_ingreso)) ?></span></td>
</tr>
<?php if($MODULES["ANTICIPOS"]==1){ ?>
<tr style="font-size:14px; ">
<td><b>ANTICIPOS/BONOS:</b></td><td> <span id="total2"><?PHP echo money(redondeo($TOT_ANTI_BONO_CONTADO[0])) ?></span></td>
</tr>
<tr style="font-size:14px; ">
<td><b>SALDO FAC. ANTICIPO/BONO:</b></td><td> <span id="total2"><?PHP echo money(redondeo($efectivo_anticipos)) ?></span></td>
</tr>
<?php } ?>

<tr style="font-size:14px; ">
<td><b>DEVOLUCIONES CONTADO</b></td><td valign="bottom"> <span id="total2"><?PHP echo money(redondeo($TOTAL_minus)) ?></span></td>
</tr>
<?php if($MODULES["ANTICIPOS"]==1){ ?>
<tr style="font-size:14px; font-family:'MS Serif', 'New York', serif;">
<td><b>DEVOLUCIONES ANTICIPOS(FAC. Y ABONOS)</b></td><td valign="bottom"> <span id="total2">-<?PHP echo money(redondeo($TOTAL_minusAnti+$tot_devolucion_comp_anti)) ?></span></td>
</tr>
<?php } ?>

<?php if($MODULES["GASTOS"]==1){ ?>
<tr style="font-size:14px; font-family:'MS Serif', 'New York', serif;">
<td><b>GASTOS (Efectivo)</b></td><td valign="bottom"> <span id="total2">-<?PHP echo money(redondeo($TOT_COMP_EGRESOS)) ?></span></td>
</tr>
<?php } ?>
<tr style="font-size:24px; ">
<td><b>TOTAL EFECTIVO:</b></td><td> <span id="total2"><?PHP echo money(redondeo($TOTAL_CONTADO)) ?></span></td>
</tr>
</table>
</div>

<div class="grid-40 push-10">
<table style="font-size:10px" cellspacing="0"  cellpadding="0" align="right">
<tr>
<td>TOTAL SISTEMA:</td><TD>&nbsp;&nbsp; <?php echo money(redondeo($TOTAL_CONTADO)) ?>
</TD>
</tr>
<tr>
<td>EFECTIVO:</td><TD>
<p align="center">________________________</p></TD>
</tr>
<tr>
<td>DIFERENCIA:</td><TD>
<p align="center">________________________</p></TD>
</tr>
<tr>
<td>NOVEDADES:</td>
<TD>
<p align="center">________________________</p>
<p align="center">________________________</p>
</TD>
</tr>

</table>
<br>
</div>
<br><br><br>
<div class="grid-100">
<table width="100%" cellpadding="4">
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
</div>
</div>



<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
<?php //echo $sql3; ?>

</div>
<?php
if($TOTALa>0){
?>
<div class="grid-100">
<h2 align="left"><b>ANULADAS</b></h2>
<table style="font-size:12px" cellspacing="0">
<tr>
<td>BASE IVA 16%:</td><td><span ><?PHP echo money(redondeo($BASEa-$DESCUENTOa)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 16%:</td><td><span ><?PHP echo money(redondeo($IVAa)) ?></span></td>
</tr>
<!--
<tr>
<td>BASE IVA 5%:</td><td><span><?PHP //echo money(redondeo($base_iva5B)) ?></span></td>
</tr>
<tr>
<td>VALOR IVA 5%:</td><td><span ><?PHP //echo money(redondeo($iva5B)) ?></span></td>
</tr>
-->
<tr>
<td>VENTAS <?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP echo money(redondeo($excentasB)) ?></span></td>
</tr>
<tr>
<td>VENTAS TOTALES:</td><td> <span ><?PHP echo money(redondeo($TOTALa)) ?></span></td>
</tr>
<tr>
<td>TOTAL ANULADAS:</td><td> <span ><?PHP echo $tot_fac ?></span></td>
</tr>
</table>

<table cellpadding="2" cellspacing="0" style="font-size:12px" align="left">
<tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">
<th>#</th><td>Num. Factura</td><td>Vendedor</td><td>Total</td>

<?php
$sql="SELECT * FROM fac_venta WHERE  nit=$cod_su AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula)";
$rs=$linkPDO->query($sql);
$con=0;
while($row=$rs->fetch()){
$con++;
$NFac=$row['num_fac_ven'];
$vendedor=$row['vendedor'];
$totalFac=money($row['tot']);	

?>
<tr>
<th><?php echo $con ?></th>
<td align="center"><?php echo $NFac ?></td>
<td align="center"><?php echo $vendedor?></td>
<td align="right"><?php echo $totalFac ?></td>
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
</body>
</html>