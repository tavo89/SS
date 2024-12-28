<?php
include("../../Conexxx.php");

$tableMain="fac_dev_venta";
$tableArt="art_devolucion_venta";
$tipoOp="+";

try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$TC=tasa_cambio();
$cotiza_a_fac=r("co");




	$cliente=rm('cli');
	$ced=r('ced');
	$cuidad=rm('city');
	$dir=rm('dir');
	$tel=r('tel');
	
	$nota_fac=rm("nota_fac");
	
	
	$form_p=r('form_pago');
	$tipo_cli=r('tipo_cli');
	$ven=r('vendedor');
	

	$vendedorArr=explode("-",$ven);
	
	$ven=$vendedorArr[0];
	$idVen=$vendedorArr[1];
	
	$meca=r('mecanico');
	$meca2=r('mecanico2');
	$meca3=r('mecanico3');
	$meca4=r('mecanico4');
	$cajero=s('tipo_usu');
	$fecha=r('fecha');
	
	
	$codComision=r("cod_comision");
	
	if($FLUJO_INV!=1)$fecha.=" $hora";
	//$vlr_let=$_REQUEST['vlr_let'];
	$vlr_let=r('vlr_let');
	

	$sub=quitacom($_REQUEST['sub']);
	$iva=quitacom($_REQUEST['iva']);
	$dcto=quitacom($_REQUEST['dcto']);
	$tot=quitacom($_REQUEST['tot']);
	$totBsF=quitacom($_REQUEST['totB']);
	//eco_alert("$totBsF");
	
	
	
	$entrega=quitacom($_REQUEST['entrega']);
	$entrega2=quitacom($_REQUEST['entrega2']);
	$entrega3=quitacom($_REQUEST['entrega3']);
	$cambio=quitacom($_REQUEST['cambio']);
	if(($entrega+$entrega2)>($tot*10000))
	{
		$entrega=$tot;
		$cambio=0;
		}
	
	
	if(($totBsF*1)<0)$totBsF=0;
	//eco_alert("$totBsF");
	$pagoBsf=r("bsF_flag");
	if($pagoBsf!=1 || $entrega2<=0)$totBsF=0;
	
	$nit=s('cod_su');
	$mail=r('mail');
	
	$RESOL="";
	$PRE="";
	$fechaRESOL="";
	$RANGO_RESOL="";
	
	$NUM_PAGARE=r("num_pagare");
	
	$anticipo=quitacom($_REQUEST['anticipo']);
	$num_exp=limpiarcampo($_REQUEST['num_exp']);
	$confirm_bono=r('confirm_bono');
if(empty($confirm_bono))$confirm_bono="NO";
	$num_fac=serial_fac("factura venta","POS");
	$estado="";
	$tipoResol=r("tipo_resol");
	//$PRE=$codContadoSuc;
	if($form_p=="Credito"){
		$estado="PENDIENTE";
		//$num_fac=serial_credito($conex);
	
		//$PRE=$codContadoSuc;
	}
	else
	{
		
	}
	
	if($tipoResol=="POS")
	{
		$num_fac=serial_fac("factura venta","POS");
		$PRE=$codContadoSuc;
		$RESOL=$ResolContado;
		$fechaRESOL=$FechaResolContado;
		$RANGO_RESOL=$RangoContado;
		
	}
	else if($tipoResol=="COM"){
		
		$num_fac=serial_fac("credito","COM");
		$PRE=$codCreditoSuc;
		$RESOL=$ResolCredito;
		$fechaRESOL=$FechaResolCredito;
		$RANGO_RESOL=$RangoCredito;
		
	}

	else if($tipoResol=="PAPEL")
	{
		$num_fac=serial_fac("resol_papel","PAPEL");
		$PRE=$codPapelSuc;
		$RESOL=$ResolPapel;
		$fechaRESOL=$FechaResolPapel;
		$RANGO_RESOL=$RangoPapel;
		
	}
	
	else if($tipoResol=="COM_ANT"){
		
		$num_fac=serial_fac("credito_ant","COM");
		$PRE=$codCreditoSuc;
		$RESOL=$ResolCredito;
		$fechaRESOL=$FechaResolCredito;
		$RANGO_RESOL=$RangoCredito;
		
	}

	else if($tipoResol=="PAPEL_ANT")
	{
		$num_fac=serial_fac("resol_papel_ant","PAPEL");
		$PRE=$codPapelSuc;
		$RESOL=$ResolPapel;
		$fechaRESOL=$FechaResolPapel;
		$RANGO_RESOL=$RangoPapel;
		
	}
	
	else if($tipoResol=="CRE_ANT"){
		
		$num_fac=serial_fac("cartera_ant","CRE");
		$PRE=$codCreditoANTSuc;
		$RESOL=$ResolCreditoANT;
		$fechaRESOL=$FechaResolCreditoANT;
		$RANGO_RESOL=$RangoCreditoANT;
		
	}
	
	else{
		
		$num_fac=serial_fac("fac_dev_ven","REM_POS","fac_dev_venta");
		$PRE=$codRemiPOS;
		$RESOL=$ResolRemiPOS;
		$fechaRESOL=$FechaRemiPOS;
		$RANGO_RESOL=$RangoRemiPOS;	
	}
	$r_fte=quitacom(r("R_FTE"));
	$r_ica=quitacom(r("R_ICA"));
	$r_iva=quitacom(r("R_IVA"));
	
	$r_fte_per=quitacom(r("R_FTE_PER"));
	$r_ica_per=quitacom(r("R_ICA_PER"));
	$r_iva_per=quitacom(r("R_IVA_PER"));
	
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$PRE;
	$fe_naci="";//$_REQUEST['fe_naci'];
	$tipoImp="";//$_REQUEST['tipo_imp'];;
	
	$PLACA=rm("placa");
	
	$KM=limpianum(r("km"));
	
	$TIPDOC=r("TIPDOC");
	$FECVEN=r("FECVEN");
	$MONEDA=r("MONEDA");
	$TIPCRU=r("TIPCRU");
	$CODSUC=r("CODSUC");
	$NUMREF=r("NUMREF");
	$FORIMP=r("FORIMP");
	$CLADET=r("CLADET");
	$ORDENC=r("ORDENC");
	$NUREMI=r("NUREMI");
	$NORECE=r("NORECE");
	$EANTIE=r("EANTIE");
	$COMODE=r("COMODE");
	$COMOHA=r("COMOHA");
	$FACCAL=r("FACCAL");
	$FETACA=r("FETACA");
	$FECREF=r("FECREF");
	$OBSREF=r("OBSREF");
	$TEXDOC=r("TEXDOC");
	$MODEDI=r("MODEDI");
	$NDIAN=r("NDIAN");
	$SOCIED=r("SOCIED");
	$MOTDEV=r("MOTDEV");
	$CODVEN=$idVen;
	
	$claper=r("claper");
	$coddoc=r("coddoc");
	$paicli=r("paicli");
	$depcli=r("depcli");
	$loccli=r("loccli");
	$nomcon=r("nomcon");
	$regtri=r("regtri");
	$razsoc=r("razsoc");
	$snombr=r("snombr");
	$apelli=r("apelli");


$idCta=r("id_cuenta");
if($form_p=="Contado")$idCta="1";

	
$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,entrega_bsf,r_fte,r_iva,r_ica,per_fte,per_iva,per_ica,tasa_cam,placa,km,nota,tot_tarjeta,tec2,tec3,tec4,id_cuenta,cod_comision, TIPDOC, FECVEN, MONEDA, TIPCRU, CODSUC, NUMREF, FORIMP, CLADET, ORDENC, NUREMI, NORECE, EANTIE, COMODE, COMOHA, FACCAL, FETACA, FECREF, OBSREF, TEXDOC, MODEDI, NDIAN, SOCIED, MOTDEV, claper, coddoc, paicli, depcli, regFiscal, nomcon, regtri, razsoc, snombr, apelli,id_vendedor";
	
	


$sql="INSERT INTO $tableMain($columnas) VALUES($num_fac,'$ced','$cliente','$dir','$tel','$cuidad','$form_p','$tipo_cli','$ven','$meca','$cajero','$fecha','$vlr_let',$sub,$iva,$dcto,$tot,$entrega,$cambio,'si','$nit','$estado','$mail','$fe_naci','$PRE','$nomUsu','$id_Usu','$RESOL','$fechaRESOL','$RANGO_RESOL','$num_exp','$anticipo','$confirm_bono','$codCaja','$totBsF','$FLUJO_INV','$NUM_PAGARE','$entrega2','$r_fte','$r_iva','$r_ica','$r_fte_per','$r_iva_per','$r_ica_per','$TC','$PLACA','$KM','$nota_fac','$entrega3','$meca2','$meca3','$meca4','$idCta','$codComision', '$TIPDOC', '$FECVEN', '$MONEDA', '$TIPCRU', '$CODSUC', '$NUMREF', '$FORIMP', '$CLADET', '$ORDENC', '$NUREMI', '$NORECE', '$EANTIE', '$COMODE', '$COMOHA', '$FACCAL', '$FETACA', '$FECREF', '$OBSREF', '$TEXDOC', '$MODEDI', '$NDIAN', '$SOCIED', '$MOTDEV', '$claper', '$coddoc', '$paicli', '$depcli', '$loccli', '$nomcon', '$regtri', '$razsoc', '$snombr', '$apelli','$idVen')";
$linkPDO->exec($sql);
$page="";
if(isset($_REQUEST['html']))$page=$_REQUEST['html'];
//logDB($sqlA,"Factura Venta",$OPERACIONES[1],"NO APLICA",$page,$num_fac);


 
if($num_exp!=0)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql="UPDATE exp_anticipo SET num_fac=$num_fac, prefijo='$PRE', estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
$linkPDO->exec($sql);

$sql="UPDATE comp_anti SET estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
$linkPDO->exec($sql);




}


$linkPDO->exec("SAVEPOINT A");

$id_fac=0;
$num_art=$_REQUEST['num_ref'];
$num_serv=$_REQUEST['num_serv'];

$rs=$linkPDO->query("SELECT num_fac_ven FROM $tableMain WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$codSuc' FOR UPDATE" );
//echo "SELECT id_fac_ven,num_fac_ven FROM $tableMain WHERE num_fac_ven=$num_fac";
if($row=$rs->fetch())
{//echo "entra if<br>";


	
////////////////////////////// ARTICULOS Y RECARGAS///////////////////////////////////////////////////////////////


	
	$update="";
	$II=0;
	$UPDATE[]="";
	$color="";
	$talla="";
	//eco_alert("$num_art");
	//echo "<li>$num_art </li>";
	for($i=0;$i<$num_art;$i++)
	{
		$linkPDO->exec("SAVEPOINT LOOP".$i);
		//eco_alert("$num_art");
		//echo "cod_bar$i:".$_REQUEST['cod_bar'.$i];
		if(isset($_REQUEST['cod_bar'.$i]))
		{
		$ref=r('ref_'.$i);
		$cod_bar=r('cod_bar'.$i);
		$color=rm('color'.$i);
		$talla=rm('talla'.$i);
		$det=rm('det_'.$i);
		$cant=limpianum(r('cant_'.$i));
		$uni=limpianum(r('unidades'.$i));
		$frac=limpianum(r('fracc'.$i));
		if($frac==0)$frac=1;
		$presen=r('presentacion'.$i);
		$feVenci=r('feVen'.$i);
		if(empty($feVenci))$feVenci="0000-00-00";
		$factor=$uni/$frac;
		$iva=quitacom($_REQUEST['iva_'.$i]);
		$dcto=quitacom($_REQUEST['dcto_'.$i]);
		if(empty($dcto))$dcto=0;
		if(empty($iva))$iva=0;
		$precio=quitacom($_REQUEST['val_uni'.$i]);
		$sub_tot=quitacom($_REQUEST['val_tot'.$i]);
		$SN=r("SN_".$i);
		$ordenIN=r("orden_in".$i);
		
$costoRef="";
$rsx=$linkPDO->query("SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$cod_bar'");
if($rx=$rsx->fetch()){$costoRef="(select costo from inv_inter where id_inter='$cod_bar' AND id_pro='$ref' and nit_scs=$codSuc LIMIT 0,1)";	}
else {$costoRef=($precio/((100/$iva) +1)/(1.2));}	

if($usar_fecha_vencimiento==0){

if($vender_sin_inv==0){$Insert="INSERT INTO $tableArt(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fraccion,unidades_fraccion,serial,orden_in) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',$costoRef,'$PRE','$cod_bar','$color','$talla','$presen','$frac','$uni','$SN','$ordenIN')";}

else {$Insert="INSERT INTO $tableArt(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fraccion,unidades_fraccion,serial,orden_in) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',0,'$PRE','$cod_bar','$color','$talla','$presen','$frac','$uni','$SN','$ordenIN')";}


}
		
		else {
if($vender_sin_inv==0)$Insert="INSERT INTO $tableArt(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',$costoRef,'$PRE','$cod_bar','$color','$talla','$presen','$feVenci','$frac','$uni','$SN','$ordenIN')";

else $Insert="INSERT INTO $tableArt(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',0,'$PRE','$cod_bar','$color','$talla','$presen','$feVenci','$frac','$uni','$SN','$ordenIN')";
		}
	//echo "<li>$Insert</li>";
		


		$linkPDO->exec($Insert);
		


//// SUMA cantidades
if($FLUJO_INV==1  ){	
$paramsInventario = array(  'tipoOperacion'=>$tipoOp,
							'codSuc'=>$codSuc,
							'tipoDocumento'=>'devolucionVenta',
							'datosProducto'=>array('cant'=>$cant,
												   'fraccion'=>$frac,
												   'unidades_frac'=>$uni,
												   'ref'=>$ref,
												   'cod_bar'=>$cod_bar,
												   'fecha_vencimiento'=>$feVenci,
												   'detalle'=>$det,
												   'iva'=>$iva,
												   'precio_v'=>$precio,
												   'costo'=>$costoRef,
												   'color'=>$color,
												   'talla'=>$talla,
												   'presentacion'=>$presen,
												   'codSuc'=>$codSuc)
										   );

invAlteraCantidades($paramsInventario);
/*$sql="UPDATE `inv_inter`  SET exist=(exist+$cant), unidades_frac=(unidades_frac+$uni) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$feVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
$linkPDO->exec($sql);*/


}




//////////////	


		}
if($MODULES["SERVICIOS"]==1){	
if(!empty($_REQUEST["cod_serv".$i]))
{
	
	$codServ=r("cod_serv".$i);
	$idServ=r("id_serv".$i);
	$serv=r("serv".$i);
	$nota=r("nota".$i);
	$tec_serv=r("tec_serv".$i);
	$ivaServ=limpianum(r("iva_serv".$i));
	$pvpServ=quitacom(r("val_serv".$i));
	
	$sql="INSERT INTO serv_fac_devolucion(num_fac_ven,prefijo,id_serv,serv,iva,pvp,cod_serv,cod_su,nota,id_tec) VALUES('$num_fac','$PRE','$idServ','$serv','$ivaServ','$pvpServ','$codServ','$codSuc','$nota','$tec_serv')";
	//eco_alert("$sqlServ");
$linkPDO->exec($sql);
}

}
	}//FIN FOR ARTICULOS



totFacVen($num_fac,$PRE,$codSuc);
//echo "pre IF inv --";
if(1){

//echo "COTIZAAAAAAAAAAA              A   v1 $cotiza_a_fac  v2 $idCliente  boolean: $boolll";

	up_cta($form_p,$idCta,($tot-$anticipo),"+","Venta $PRE-$num_fac","Factura Venta",$fecha);
	$sql="UPDATE $tableMain SET anulado='CERRADA' WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit=$codSuc";
	$linkPDO->exec($sql);

if($usar_posFac==0 || val_secc($id_Usu,"caja_centro")){}
}

}
if(!empty($idCliente)){
	
 }
 
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$PRE;
	
	echo "1";
	//SendNotaCreDIAN($num_fac,$PRE,$codSuc);
}
else{echo 500;}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}



?>