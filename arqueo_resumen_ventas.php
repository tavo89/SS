<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$cod_su=$_SESSION['cod_su'];

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

$TOT_COMP_EGRESOS=tot_comp_egreso($fechaI,$fechaF,$codSuc,"","contado");


$TOT_ANTI_BONO_CONTADO=tot_anticipos($fechaI,$fechaF,$codSuc,"contado","p");
$TOT_ANTI_BONO_TDEBITO=tot_anticipos($fechaI,$fechaF,$codSuc,"tDebito","p");
$TOT_ANTI_BONO_TCREDITO=tot_anticipos($fechaI,$fechaF,$codSuc,"tCredito","p");
$TOTAL_ANTI_BONO=tot_anticipos($fechaI,$fechaF,$codSuc,"p","p");
$TOTAL_ANTI_BONO_COBRADOS=tot_anticipos($fechaI,$fechaF,$codSuc,"p","p","si");

$tot_Credito=tot_credito($fechaI,$fechaF,$codSuc);

$TOTAL=0;

/////////////////////////////////////////////////////////// TOTALES FAC ANULADAS /////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTALa=$row['TOT'];
	$BASEa=$row['BASE'];
	$DESCUENTOa=$row['D'];
	$IVAa=$row['IVA'];
	
	
}
///////////////////////////////////// TOT. DEVOLUCIONES COMP. ANTICIPOS////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  cod_su=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF'";
$rs=$linkPDO->query($sql);
$tot_devolucion_comp_anti=0;
if($row=$rs->fetch())
{
	$tot_devolucion_comp_anti=$row['TOT'];
	
}

///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CONTADO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND tipo_venta!='Anticipo' AND tipo_venta!='Credito'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minus=$row['TOT'];
	$BASE_minus=$row['BASE'];
	$DESCUENTO_minus=$row['D'];
	$IVA_minus=$row['IVA'];
		
}
// ---------------------------------- DEVOLUCIONES EXENTAS CONTADO-------------------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.tipo_venta!='Anticipo' AND fac_ven.tipo_venta!='Credito' AND anulado='ANULADO' and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}


///////////////////////////////////////////////// TOTAL DEVOCLUCIONES CREDITO////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND tipo_venta!='Anticipo' AND tipo_venta='Credito'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusC=$row['TOT'];
	$BASE_minusC=$row['BASE'];
	$DESCUENTO_minusC=$row['D'];
	$IVA_minusC=$row['IVA'];
		
}
// ---------------------------------- DEVOLUCIONES EXENTAS CREDITO-------------------------------------------------------------------------
$sql="SELECT fac_ven.fecha_anula,fac_ven.prefijo,art_fac_ven.prefijo,art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT prefijo,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta,fecha_anula FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND fac_ven.prefijo=art_fac_ven.prefijo AND fac_ven.tipo_venta!='Anticipo' AND fac_ven.tipo_venta='Credito' AND anulado='ANULADO' and fac_ven.nit=$codSuc AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasBcre=$row['excentas'];
	
	if($excentasBcre==""){$excentasBcre=0;}
}

// --------------------------- DEVOLUCIONES FAC. ANTICIPOS--------------------------------------------------------

$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc AND tipo_venta='Anticipo'  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula)";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL_minusAnti=$row['TOT'];
	$BASE_minusAnti=$row['BASE'];
	$DESCUENTO_minusAnti=$row['D'];
	$IVA_minusAnti=$row['IVA'];
		
}


//////////////////////////////////////////////////////// TOTALES VENTAS //////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT, SUM(iva) IVA, SUM(descuento) D, SUM(sub_tot) BASE FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'  AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$TOTAL=$row['TOT'];
	$BASE=$row['BASE'];
	$DESCUENTO=$row['D'];
	$IVA=$row['IVA'];	
}


///////////////////////////////////////////////// TOTAL RECUPERACION CREDITOS /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc AND num_fac_ven NOT IN(SELECT num_fac FROM comprobante_ingreso) AND estado='PAGADO'  AND DATE(fecha_pago)>='$fechaI' AND DATE(fecha_pago)<='$fechaF' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}


$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes_ingreso=$row['TOT'];
	
}

/*
///////////////////////////////////////////////// TOTAL COMPROBANTES INGRESO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comprobante_ingreso  WHERE  cod_su=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_comprobantes=$row['TOT'];
	
}
*/
///////////////////////////////////////////////// TOTAL COMPROBANTES ANTICIPO /////////////////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) as TOT FROM comp_anti  WHERE  cod_su=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'";
$rs=$linkPDO->query($sql);
$tot_comprobantes_anti=0;
if($row=$rs->fetch())
{
	$tot_comprobantes_anti=$row['TOT'];
	
}


//////////////////////////////////////////////////// VENTAS EXENTAS ///////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_ven.nit=$cod_su AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$excentas=$row['excentas'];
	
	if($excentas==""){$excentas=0;}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// Num. Facturas por cada tipo /////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Mostrador' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_fac_mostrador=$row['tot_fac'];}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Empleados' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_empleados=$row['tot_fac'];
	
}
/* fanalca
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Garantia FANALCA' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_FANALCA=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND (tipo_cli =  'Taller Honda' OR tipo_cli='Garantia FANALCA') AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_taller=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Otros Talleres' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_otros_talleres=$row['tot_fac'];
	
}
*/
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_contado=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Cheque' AND anticipo_bono='NO'  AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_cheque=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito' AND anticipo_bono='NO' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_credito=$row['tot_fac'];
	
}

$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Debito' AND anticipo_bono='NO' AND fac_venta.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac_tarjeta_debito=$row['tot_fac'];
	
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$ventas_tot=$iva16+$base_iva16+$base_iva5+$iva5+$excentas;
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
/*
$sql="SELECT COUNT(fac_ven.num_fac_ven) AS tot_ins,serv_fac_ven.nit AS codSu_serv, SUM( precio ) AS base_iva, SUM( (precio_iva-precio )) AS iva, SUM( precio_iva-(precio_iva*(dcto/100)) ) AS TOT,serv_fac_ven.prefijo FROM serv_fac_ven INNER JOIN (SELECT prefijo,fecha, anulado, num_fac_ven, nit, tipo_venta, tipo_cli FROM fac_venta ) AS fac_ven ON fac_ven.num_fac_ven = serv_fac_ven.num_fac_ven WHERE  fac_ven.prefijo=serv_fac_ven.prefijo AND fac_ven.nit =$codSuc AND serv_fac_ven.nit= fac_ven.nit AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli =  'Taller Honda' AND man='Inspeccion' AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";
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
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_cli='Garantia FANALCA' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_FANALCA=$row['TOT'];
	
}


$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_contado=$row['TOT'];
	
}
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Cheque' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_cheque=$row['TOT'];
	
}
*/
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$tot_tarjetaCre=$row['TOT'];
	
}


//////////////////////////////////////////////////////////////////// BONOS/ANTICIPOS ///////////////////////////////////////////////////////////////////
//todos
$sql="SELECT SUM(tot) as TOT,COUNT(*) AS nf FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND abono_anti!=0 AND tipo_venta!='Contado y Tarjeta' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
$rs=$linkPDO->query($sql);

$tot_anticipos=0;
$tot_fac_anticipo=0;
if($row=$rs->fetch())
{
	$tot_anticipos=$row['TOT'];
	$tot_fac_anticipo=$row['nf'];
	
}


//contado
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Contado' AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
//echo "$sql";
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
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Credito' AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
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
$sql="SELECT *,COUNT(*) AS nf,SUM(abono_anti) as abono, SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Tarjeta Debito' AND abono_anti!=0 AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
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
Desde: <?PHP echo fecha($_SESSION['fechaI']) ?>
</td>
<td style="font-size:24px; font-weight:bold;"> Hasta: <?php echo fecha($_SESSION['fechaF']) ?>
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
<tr style=" font-size:16px;">
<td><b>Cajero </b></td><td width="20px"></td><td colspan=""><b><?php echo $cajera ?></b></td>

<?php
$estado_caja="";
$sql="SELECT * FROM cajas WHERE fecha='$fechaI' AND cod_su=$codSuc";
	$rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
	{
	 $cod_caja=$row['cod_caja']; 
	 $estado_caja=$row['estado_caja'];
	}



$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF'  AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_fac=$row['tot_fac'];
	$ult_fac=$row['ultima'];
	$pri_fac=$row['primera'];
}
$sql="SELECT  nit,COUNT(num_fac_ven) as tot_fac,MAX(num_fac_ven) as ultima,MIN(num_fac_ven) as primera FROM fac_venta  WHERE nit=$cod_su AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tot_facCre=$row['tot_fac'];
	$ult_facCre=$row['ultima'];
	$pri_facCre=$row['primera'];
}

?>
<td style="font-size:18px;">
<!--
&nbsp;&nbsp;&nbsp; <B><div class="caja_cerrada"><?php echo $estado_caja ?></div></B>
-->
</td>
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

$IVA=redondeo(($BASE-$excentas)*0.16);



$sql="SELECT art_fac_ven.nit as nit_art,SUM(sub_tot) as excentas  FROM art_fac_ven INNER JOIN (SELECT fecha_anula,fecha,anulado,num_fac_ven,nit,tipo_cli,tipo_venta FROM fac_venta) as fac_ven ON fac_ven.num_fac_ven=art_fac_ven.num_fac_ven WHERE anulado='ANULADO'  and fac_ven.nit=$cod_su AND art_fac_ven.nit=fac_ven.nit AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND iva=0 AND fac_ven.num_fac_ven NOT IN(SELECT num_fac_ven FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF')";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$excentasB=$row['excentas'];
	
	if($excentasB==""){$excentasB=0;}
}




$sql="SELECT COUNT(*) as tot_fac FROM fac_venta  WHERE  nit=$cod_su AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' ";

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

///////////////////////////////////////////////// REPUESTOS //////////////////////////
$sql="SELECT fac_venta.pago_tar,fac_venta.pago_cont,art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND anticipo_bono='NO'   AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";
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

$tot_pago_tar=0;
$tot_pago_cont=0;
$tot_cont_tar=0;
$cant_fac_cont_tar=0;

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
	
	if($tipo_venta=="Contado")
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
$sql="SELECT art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,fac_venta.prefijo,art_fac_ven.prefijo FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit AND art_fac_ven.nit=$codSuc AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF' AND DATE(fecha)!=DATE(fecha_anula)";
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

if(1){
?>

<div class="grid-100">
<h2>RESUMEN DE VENTAS</h2>
<table align="left" cellpadding="4" cellspacing="0" frame="box" rules="all" width="100%">
<thead>
<tr>
<th>Num. Fac</th>
<th>Cliente</th>
<th>Forma Pago</th>
<th>Total Fac.</th>
<th>Anticipo</th>
<th>Contado</th>
<th>Tarjeta</th>
<!-- 
<th>COD.</th>
<th>PRODUCTO</th><th>CANT.</th><th>Total producto</th>
-->

</tr>
</thead>
<tbody>
<?php 
$sql="SELECT b.sub_tot,b.pago_tar,b.pago_cont,b.abono_anti,b.anticipo_bono,b.num_fac_ven,b.prefijo,b.nom_cli,b.tipo_venta,b.tot,a.ref, a.cod_barras,a.talla,a.color, a.des,cant,a.sub_tot as stot FROM `art_fac_ven` a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND (DATE(b.fecha)>='$fechaI' AND DATE(b.fecha)<='$fechaF'  )  AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')) GROUP BY b.num_fac_ven  ORDER BY b.num_fac_ven";

$rs=$linkPDO->query($sql);
$STOT=0;
$add_des="";
$t_pagoC=0;
$t_pagoT=0;
$t_anti=0;
while($row=$rs->fetch())
{
	$anti=$row['anticipo_bono'];
			if($anti=="SI")$add_des="(ANTICIPO)";
			else $add_des="";
	$des=$row['des'];
	$talla=$row['talla'];
	$codB=$row['ref'];
	$color=$row['color'];
	$cant=$row['cant']*1;
	$tipo_venta=$row['tipo_venta'];
	
	
	$abon=$row['abono_anti']*1;
	$t_anti+=$abon;
	$stot=$row['stot']*1;
	$nomCli=$row['nom_cli'];
	$numFac=$row['num_fac_ven'];
	$pre=$row['prefijo'];
	$tot=$row['tot']*1;
	$pago_c=$row['pago_cont']*1;
	
	
	$pago_t=$row['pago_tar']*1;
	
	$tipoPago=$row['tipo_venta'];
	$STOT+=$tot;
	
	if($tipo_venta!="Contado y Tarjeta")$pago_c=0;
	
	if($tipo_venta=="Contado y Tarjeta")
	{
		
		$t_pagoC+=$pago_c;
		$t_pagoT+=$pago_t;
		
		$tot_pago_tar+=$row['pago_tar']*1;
		$tot_pago_cont+=$row['pago_cont']*1;
		$tot_cont_tar+=$tot;
		$cant_fac_cont_tar++;
		
	}
	//else if($tipo_venta=="Contado")$t_pagoC+=$pago_c;
	//else if($tipo_venta=="Tarjeta Credito"||$tipo_venta=="Tarjeta Debito")$t_pagoT+=$pago_t;
	
	?>

<tr>
<td><?php echo "$numFac" ; ?></td>
<td><?php echo "$nomCli" ; ?></td>
<td><?php echo "$tipoPago <br>$add_des" ; ?></td>
<td align="center"><?php echo money("$tot") ; ?></td>
<td align="center"><?php echo money("$abon") ; ?></td>
<td align="center"><?php echo money("$pago_c") ; ?></td>
<td align="center"><?php echo money("$pago_t") ; ?></td>
<!--
<td><?php echo "$codB" ; ?></td>
<td><?php echo "$des T: $talla, C: $color" ; ?></td>
<td><?php echo "$cant" ; ?></td>
<td><?php echo money("$stot") ; ?></td>
-->

</tr>
    <?php
	
}


 ?>
 </tbody>
 <tfoot>
 <th colspan="3">TOTAL VENTAS:</th><th><?php echo money("$STOT") ; ?></th>
 <th><?php echo money("$t_anti") ; ?></th>
 <th><?php echo money("$t_pagoC") ; ?></th>
 <th><?php echo money("$t_pagoT") ; ?></th>
 </tfoot>
</table>
</div>

<?php
}////////////// fin if fecha 1 dia/////////
?>
 
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

<br><br>
<?php
$sql="SELECT SUM(tot) as TOT FROM fac_venta  WHERE  nit=$codSuc  AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND tipo_venta='Credito' AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))";

$rs=$linkPDO->query($sql);

$tot_Credito=0;
if($row=$rs->fetch())
{
	$tot_Credito=$row['TOT'];
	
}

$sql="SELECT COUNT(*) nBonos
FROM  `fac_venta` 
WHERE nit =$codSuc AND (".VALIDACION_VENTA_VALIDA." OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO'))
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
<td width="197">CONTADO:</td><td width="20"><span id="iva5"><?PHP echo money(redondeo($total_contado)) ?></span></td>
<td width="18" align="center"><span><?PHP echo $tot_fac_contado ?></span></td>
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
<td>ANTICIPOS/BONOS FACTURADOS:</td><td><?PHP echo money(redondeo($tot_anticipos)) ?></td>
<td align="center"><span><?PHP echo $tot_fac_anticipo ?></span></td>
</tr>


<tr>
<td width="197">CONTADO Y TARJETA:</td><td width="20"><span id="iva5"><?PHP echo money(redondeo($tot_cont_tar)) ?></span></td>
<td width="18" align="center"><span><?PHP echo $cant_fac_cont_tar ?></span></td>
</tr>



<tr style="font-size:16px; font-weight:bold;">
<td colspan="">
Total:
</td>
<td>
<?php echo money(redondeo($tot_Credito+$total_contado+$tot_tCredito+$tot_tDebito+$tot_anticipos+$tot_cont_tar)) ?>
</td>
</tr>
</table>

<table align="left" cellpadding="4" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:14px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>ANTICIPOS/BONOS</b></td><TD>#</TD></tr>
</table>
</td></tr>

<tr>
<td>CONTADO:</td><td><span id="iva5"><?PHP echo money(redondeo($TOT_ANTI_BONO_CONTADO[0])) ?></span></td>
<td align="center"><span><?PHP echo $TOT_ANTI_BONO_CONTADO[1] ?></span></td>
</tr>
<tr>
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
<?php echo money(redondeo($TOTAL_ANTI_BONO[0])) ?>
</td>
</tr>
</table>



<table align="left" cellpadding="4" cellspacing="0" frame="box">
<tr>
<td colspan="3" align="left" style="font-size:14px;  background-color:#999">
<table cellspacing="0" cellpadding="0" frame="box"  width="100%">
<tr><td  colspan=""><b>CONTADO Y TARJETA</b></td><TD>#</TD></tr>
</table>
</td></tr>

<tr>
<td>CONTADO:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_pago_cont)) ?></span></td>
<td align="center"><span><?PHP echo "" ?></span></td>
</tr>
<tr>
<td>TARJETAS:</td><td><span id="iva5"><?PHP echo money(redondeo($tot_pago_tar)) ?></span></td>
<td align="center"><span><?PHP echo "" ?></span></td>
</tr>
<tr>
<td>ANTICIPOS:</td><td><span id="iva5"><?PHP echo money(redondeo($t_anti)) ?></span></td>
<td align="center"><span><?PHP echo "" ?></span></td>
</tr>
<tr>
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
<?php echo money(redondeo($tot_cont_tar)) ?>
</td>
</tr>
</table>
</div>
<br><br><br><br><br>

<?php

$TOTAL_CONTADO=$total_contado+$tot_pago_cont+$tot_comprobantes_ingreso+$tot_comprobantes+$TOT_ANTI_BONO_CONTADO[0]+$efectivo_anticipos-$TOTAL_minusAnti-$tot_devolucion_comp_anti-$TOTAL_minus-$TOT_COMP_EGRESOS;
?>






<div id="imp"  align="center" style="position:relative" class="grid-100">
    
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
<?php //echo $sql3; ?>

</div>
<?php
if($TOTALa>0){
?>
<br><br><br>
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
$sql="SELECT * FROM fac_venta WHERE  nit=$cod_su AND DATE(fecha_anula)>='$fechaI' AND DATE(fecha_anula)<='$fechaF'";
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