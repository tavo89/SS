<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$cod_su=$_SESSION['cod_su'];
$horaI=s("horaI");
$horaF=s("horaF");
$filtroHora="";
$filtroHoraAnula="";
if(!empty($horaI) && !empty($horaF))$filtroHora=" AND (fecha>='$fechaI $horaI' AND fecha<='$fechaF $horaF')";
if(!empty($horaI) && !empty($horaF))$filtroHoraAnula=" AND (fecha_anula>='$fechaI $horaI' AND fecha_anula<='$fechaF $horaF')";

$CodCajero=s("cod_caja_arq");
$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( (anulado!='ANULADO' $filtroCerradas) OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) ";

$filtroCaja="";
if(!empty($CodCajero))$filtroCaja=" AND cod_caja=$CodCajero ";

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

$TOT_COMP_EGRESOS=tot_comp_egreso($fechaI,$fechaF,$codSuc,"","contado");


$TOT_ANTI_BONO_CONTADO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"contado","p");
$TOT_ANTI_BONO_TDEBITO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"tDebito","p");
$TOT_ANTI_BONO_TCREDITO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"tCredito","p");
$TOTAL_ANTI_BONO=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"p","p");
$TOTAL_ANTI_BONO_COBRADOS=tot_anticipos($filtroCaja,$fechaI,$fechaF,$codSuc,"p","p","si");

$tot_Credito=tot_credito($fechaI,$fechaF,$codSuc);

$TOTAL=0;
/////////////////////////////////////////////////////////// TOTALES FAC ANULADAS /////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTALa=$row['TOT'];
	$BASEa=$row['BASE'];
	$DESCUENTOa=$row['D'];
	$IVAa=$row['IVA'];
	
	
}
///////////////////////////////////// TOT. DEVOLUCIONES COMP. ANTICIPOS////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  cod_su=$codSuc $filtroCaja  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula)";
$rs=$linkPDO->query($sql );
$tot_devolucion_comp_anti=0;
if($row=$rs->fetch())
{
	$tot_devolucion_comp_anti=$row['TOT'];
	
}

///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CONTADO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc $filtroCaja AND tipo_venta!='Anticipo' AND tipo_venta!='Credito'  AND DATE(fecha_anula)>='$fechaI' AND (DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula) $filtroHoraAnula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
		
}
// ---------------------------------- DEVOLUCIONES EXENTAS CONTADO-------------------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula,cod_caja FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su $filtroCaja AND fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.tipo_venta!='Anticipo' AND fac_ven.tipo_venta!='Credito' AND anulado='ANULADO' and fac_ven.nit=$codSuc $filtroCaja AND art_fac_ven.nit=fac_ven.nit AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}


///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CREDITO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc $filtroCaja AND tipo_venta!='Anticipo' AND tipo_venta='Credito'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusC=$row['TOT'];
	$BASE_minusC=$row['BASE'];
	$DESCUENTO_minusC=$row['D'];
	$IVA_minusC=$row['IVA'];
		
}
// ---------------------------------- DEVOLUCIONES EXENTAS CREDITO-------------------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula,cod_caja FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su $filtroCaja AND fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.tipo_venta!='Anticipo' AND fac_ven.tipo_venta='Credito' AND anulado='ANULADO' and fac_ven.nit=$codSuc $filtroCaja AND art_fac_ven.nit=fac_ven.nit AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasBcre=$row['excentas'];
	
	if($excentasBcre==""){$excentasBcre=0;}
}

// --------------------------- DEVOLUCIONES FAC. ANTICIPOS--------------------------------------------------------

$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc $filtroCaja AND tipo_venta='Anticipo'  AND (DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' $filtroHoraAnula) AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusAnti=$row['TOT'];
	$BASE_minusAnti=$row['BASE'];
	$DESCUENTO_minusAnti=$row['D'];
	$IVA_minusAnti=$row['IVA'];
		
}


//////////////////////////////////////////////////////// TOTALES VENTAS //////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)  $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL=$row['TOT'];
	$BASE=$row['BASE'];
	$DESCUENTO=$row['D'];
	$IVA=$row['IVA'];	
}


///////////////////////////////////////////////// TOTAL RECUPERACION CREDITOS /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja AND num_fac_ven NOT IN(SELECT num_fac FROM comprobante_ingreso) AND estado='PAGADO'  AND DATE(fecha_pago)>='$fechaI' AND DATE(fecha_pago)<='$fechaF' $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}


$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes_ingreso=$row['TOT'];
	
}

/*
///////////////////////////////////////////////// TOTAL COMPROBANTES INGRESO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}
*/
///////////////////////////////////////////////// TOTAL COMPROBANTES ANTICIPO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  cod_su=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora)";
$rs=$linkPDO->query($sql);
$tot_comprobantes_anti=0;
if($row=$rs->fetch())
{
	$tot_comprobantes_anti=$row['TOT'];
	
}


//////////////////////////////////////////////////// VENTAS EXENTAS ///////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas,cod_caja  FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula,cod_caja FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND art_fac_ven.nit=fac_ven.nit AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND iva=0 $filtroNOanuladas";
$rs=$linkPDO->query($sql );
if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// Num. Facturas por cada tipo /////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Mostrador' $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_fac_mostrador=$row['tot_fac'];}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Empleados' $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_empleados=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND anticipo_bono='NO' $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_contado=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' AND anticipo_bono='NO' $filtroNOanuladas";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Cheque' AND anticipo_bono='NO'  $filtroNOanuladas";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_cheque=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND anticipo_bono='NO' AND anticipo_bono='NO' $filtroNOanuladas";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Debito' AND anticipo_bono='NO' $filtroNOanuladas";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_debito=$row['tot_fac'];
	
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$ventas_tot=$iva16+$base_iva16+$base_iva5+$iva5+$excentas;
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
$sql="SELECT COUNT(fac_ven.num_fac_ven) AS tot_ins,serv_fac_ven.nit AS codSu_serv, SUM( precio ) AS base_iva, SUM( (precio_iva-precio )) AS iva, SUM( precio_iva-(precio_iva*(dcto/100)) ) AS TOT,serv_fac_ven.prefijo FROM serv_fac_ven INNER JOIN (SELECT prefijo,fecha, anulado, num_fac_ven, nit, tipo_venta, tipo_cli FROM fac_venta ) AS fac_ven ON fac_ven.num_fac_ven = serv_fac_ven.num_fac_ven WHERE  fac_ven.prefijo=serv_fac_ven.prefijo AND fac_ven.nit =$codSuc $filtroCaja AND serv_fac_ven.nit= fac_ven.nit AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli =  'Taller Honda' AND man='Inspeccion' AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
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
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_cli='Garantia FANALCA' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_FANALCA=$row['TOT'];
	
}


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_contado=$row['TOT'];
	
}
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Cheque' AND (anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_cheque=$row['TOT'];
	
}
*/
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' $filtroNOanuladas";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_tarjetaCre=$row['TOT'];
	
}


///////////////////////////////////////////// BONOS/ANTICIPOS //////////////////////////////////////////
//todos
$sql="SELECT SUM(tot) as TOT,COUNT(*) AS nf FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND abono_anti!=0 $filtroNOanuladas";
$rs=$linkPDO->query($sql);

$tot_anticipos=0;
$tot_fac_anticipo=0;
if($row=$rs->fetch())
{
	$tot_anticipos=$row['TOT'];
	$tot_fac_anticipo=$row['nf'];
	
}


//contado
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' AND abono_anti!=0 $filtroNOanuladas";
//echo "$sq";
$rs=$linkPDO->query($sql );
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
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Credito' AND abono_anti!=0 $filtroNOanuladas";
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
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Tarjeta Debito' AND abono_anti!=0 $filtroNOanuladas";
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

$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas,cod_caja  FROM art_fac_ven INNER JOIN (SELECT fecha_anula,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,cod_caja FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE anulado='ANULADO'  and fac_ven.nit=$cod_su $filtroCaja AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 $filtroNOanuladas";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}




$sql="SELECT COUNT(*) as tot_fac FROM fac_venta  WHERE  nit=$cod_su $filtroCaja AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' ";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	}

$ventas_totB=$iva16B+$base_iva16B+$base_iva5B+$iva5B+$excentasB;

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


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc $filtroCaja  AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Credito' $filtroNOanuladas";

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

$sql="SELECT COUNT(*) nBonos
FROM  `fac_venta` 
WHERE nit =$codSuc $filtroCaja $filtroNOanuladas
AND bono !=0
AND DATE( fecha ) >=  '$fechaI'
AND DATE( fecha ) <=  '$fechaF'";
$rs=$linkPDO->query($sql);
$tot_bonos_casco=0;
if($row=$rs->fetch())
{
	$tot_bonos_casco=$row['nBonos'];
	
}


$TOTAL_CONTADO=$total_contado+$tot_comprobantes_ingreso+$tot_comprobantes+$TOT_ANTI_BONO_CONTADO[0]+$efectivo_anticipos-$TOTAL_minusAnti-$tot_devolucion_comp_anti-$TOTAL_minus-$TOT_COMP_EGRESOS;

?>