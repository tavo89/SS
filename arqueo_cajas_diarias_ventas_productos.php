<?php

//$sql="SELECT fac_venta.fecha,fac_venta.fecha_anula,fac_venta.anulado,art_fac_ven.nit,anticipo_bono,tot_tarjeta,entrega_bsf,art_fac_ven.num_fac_ven, precio,des,art_fac_ven.sub_tot,art_fac_ven.iva,cant,ref, TIME(fecha) as hora, DATE(fecha) as fe, tipo_venta,tipo_cli,vendedor,art_fac_ven.prefijo,tot_bsf FROM fac_venta INNER JOIN art_fac_ven ON fac_venta.num_fac_ven=art_fac_ven.num_fac_ven WHERE fac_venta.prefijo=art_fac_ven.prefijo AND fac_venta.nit=art_fac_ven.nit $filtroSEDE_art_ven_nit AND abono_anti=0   AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";



//"SELECT * FROM vista_arqueo_pro  WHERE abono_anti=0 $filtroSEDE_nit    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
$sql="	SELECT 	fac_venta.abono_anti,fac_venta.fecha, fac_venta.fecha_anula, fac_venta.anulado, 
        		art_fac_ven.nit, anticipo_bono, tot_tarjeta, entrega_bsf, art_fac_ven.num_fac_ven, 
				precio, des, art_fac_ven.sub_tot, art_fac_ven.iva, cant, ref, TIME( fecha ) AS hora, 
		DATE( fecha ) AS fe, tipo_venta, tipo_cli, vendedor, art_fac_ven.prefijo, tot_bsf,cod_caja
	  	FROM fac_venta
		INNER JOIN art_fac_ven 
		ON fac_venta.num_fac_ven = art_fac_ven.num_fac_ven 
		AND fac_venta.prefijo = art_fac_ven.prefijo AND fac_venta.nit = art_fac_ven.nit
		WHERE abono_anti=0 $filtroSEDE_nit    
		AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";

//echo "$sql";
$rs=$linkPDO->query($sql ) ;

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
$tot_tBanco=0;
$tot_cheque=0;
$base16=0;
$iva16=0;
$excentas=0;
$total_repuestos=0;

$totFacAnticipo=0;


//$totBsF=0;

$factorImpoConsumo=1;

if($impuesto_consumo==1){$factorImpoConsumo=1.08;}

$i=0;
while($row=$rs->fetch())
{
	$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money3($row['sub_tot']*1);
	$IVA=money3($row['iva']*1);
	$des=$row['des'];
	$cant=$row['cant'];
	$valor=money3($row['precio']*1);
	$ref=$row['ref'];
	$vendedor=nomTrim($row["vendedor"]);
	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	$totB=$row['tot_bsf'];
	
	$entregaBsf=$row['entrega_bsf'];
	$entregaTar=$row['tot_tarjeta'];

	
	
	
	$total_vendedores[$vendedor][1]+=$row['sub_tot']*1;
	
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
	
	if($tipo_venta=="Contado" && ($totB<=0 || $entregaBsf<=0 ))
	{
		$total_contado+=round($row['sub_tot']*$factorImpoConsumo);
		$total_vendedores[$vendedor][2]+=$row['sub_tot']*1;
		
		}
	
	if($totB!=0)
	{
	  // $totBsF+=$row['sub_tot']*1;
	   //$total_vendedores[$vendedor][22]+=$row['sub_tot']*1;	
	}
	if($tipo_venta=="Credito")
	{
		$total_credito+=round($row['sub_tot']*$factorImpoConsumo);
		$total_vendedores[$vendedor][3]+=round($row['sub_tot']*$factorImpoConsumo);
		
		}
	if($tipo_venta=="Anticipo")
	{
		$totFacAnticipo+=round($row['sub_tot']*$factorImpoConsumo);
		
		}
	if($tipo_venta=="Tarjeta Credito")
	{
		$tot_tCredito+=round($row['sub_tot']*$factorImpoConsumo);
		
		}
	if($tipo_venta=="Tarjeta Debito")
	{
		$tot_tDebito+=round($row['sub_tot']*$factorImpoConsumo);
		
		}
	 
	 if($tipo_venta=="Transferencia Bancaria")
	{
		$tot_tBanco+=round($row['sub_tot']*$factorImpoConsumo);
		
		}
                
          if($tipo_venta=="Cheque")
	{
		$tot_cheque+=round($row['sub_tot']*$factorImpoConsumo);
		
		}
	
	
}// FIN ARTICULOS

if($MODULES["SERVICIOS"]==1){

$sql="SELECT * FROM fac_venta a INNER JOIN serv_fac_ven b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.cod_su $filtroSEDE_Bcod_su $filtroCaja    AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) $filtroNOanuladas";
$rs=$linkPDO->query($sql );


while($row=$rs->fetch()){
	
$i++;
	$num_fac=$row['num_fac_ven'];
	$subTot=money3($row['pvp']*1);
	$IVA=money3($row['iva']*1);
	$des=$row['serv'];
	$cant=1;
	$valor=money3($row['pvp']*1);
	
	$vendedor=nomTrim($row["vendedor"]);
	
	$tipo_venta=$row['tipo_venta'];
	$tipoCli=$row['tipo_cli'];
	$totB=$row['tot_bsf'];
	
	$entregaBsf=$row['entrega_bsf'];
	
	
	
	$total_vendedores[$vendedor][1]+=$row['pvp']*1;
	
	if($IVA==0)$excentas+=$row['pvp']*1;
	
	if($IVA==16)
	{
		$base16+=($row['pvp']*1)/1.16;
		$iva16+=$row['pvp']*1-($row['pvp']*1)/1.16;
		
		}
	$total_repuestos+=$row['pvp']*1;
	
	
	///////////////////////// TIPO CLIENTE //////////////////////////
	if($tipoCli=="Taller Honda")
	{ 
	   $total_taller+=$row['pvp']*1;
		}
	if($tipoCli=="Mostrador")
	{
		$total_mostrador+=$row['pvp']*1;
		
		}
	if($tipoCli=="Otros Talleres")
	{
		$total_otros+=$row['pvp']*1;
		
		}
	if($tipoCli=="Empleados")
	{
		$total_empleados+=$row['pvp']*1;
		
		}
	if($tipoCli=="Garantia FANALCA")
	{
		$total_fanalca+=$row['pvp']*1;
		
		}
	//////////////////////////////////////////////////////////////
	
	/////// TIPO PAGO /////////////////////////////////////////////////////
	
	if($tipo_venta=="Contado" && ($totB<=0 || $entregaBsf<=0))
	{
		$total_contado+=$row['pvp']*1;
		$total_vendedores[$vendedor][2]+=$row['pvp']*1;
		
		}
	if($totB!=0)
	{
	  // $totBsF+=$row['sub_tot']*1;
	   //$total_vendedores[$vendedor][22]+=$row['sub_tot']*1;	
	}
	if($tipo_venta=="Credito")
	{
		$total_credito+=$row['pvp']*1;
		$total_vendedores[$vendedor][3]+=$row['pvp']*1;
		
		}
	if($tipo_venta=="Anticipo")
	{
		$totFacAnticipo+=$row['pvp']*1;
		
		}
	if($tipo_venta=="Tarjeta Credito")
	{
		$tot_tCredito+=$row['pvp']*1;
		
		}
	if($tipo_venta=="Tarjeta Debito")
	{
		$tot_tDebito+=$row['pvp']*1;
		
		}
	
	
}

}///// FIN VAL SERV

?>