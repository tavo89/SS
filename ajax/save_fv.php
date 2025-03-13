<?php
include_once("../Conexxx.php");

try {  
ini_set('memory_limit', '1024M'); 
$linkPDO->beginTransaction();
$all_query_ok=true;

$num_fac=r("num_fac_venta");
$PRE=r("pre");
$iRow=r('i');
$i=r('i');
$hash=r("hash");

$MesaID="";
if($MODULES["mesas_pedidos"]==1){
$MesaID=r("id_mesa");
	
}

$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND hash='$hash' AND nit='$codSuc' FOR UPDATE";
$estado="CERRADA";

$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {
	$estado2=$row['anulado'];
	$karDex=$row['kardex'];	
}

	$cliente=rm('cli');
	$snombr=rm('snombr');
	$apelli=rm('apelli');
	$ced=r('ced');
	$cuidad=rm('city');
	$dir=rm('dir');
	$tel=r('tel');
	
	$nota_fac=rm("nota_fac");
	$nota_domicilio=rm("nota_domicilio");
	
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
	$cajero=s('tipo_usu');
	$fecha=r('fecha');
	//$vlr_let=$_REQUEST['vlr_let'];
	$vlr_let=r('vlr_let');
	
	$pagare=r("num_pagare");
	$sub=quitacom($_REQUEST['sub']);
	$iva=quitacom($_REQUEST['iva']);
	$dcto=quitacom($_REQUEST['dcto2']);
	$tot=quitacom($_REQUEST['tot']);
	$tot_bsf=quitacom($_REQUEST['totB']);
	$IMP_CONSUMO=quitacom($_REQUEST['IMP_CONSUMO']);
	
	$entrega=quitacom($_REQUEST['entrega']);
	$entrega2=quitacom($_REQUEST['entrega2']);
	$entrega3=quitacom($_REQUEST['entrega3']);
	$cambio=quitacom($_REQUEST['cambio']);
	$nit=s('cod_su');
	$mail=r('mail');
	
	
	$idCta=r("id_cuenta");
	if($form_p=="Contado")$idCta="1";
	
	if(($tot_bsf*1)<0 || $entrega2<=0)$tot_bsf=0;
	
	$r_fte=quitacom(r("R_FTE"));
	$r_ica=quitacom(r("R_ICA"));
	$r_iva=quitacom(r("R_IVA"));
	
	$r_fte_per=quitacom(r("R_FTE_PER"));
	$r_ica_per=quitacom(r("R_ICA_PER"));
	$r_iva_per=quitacom(r("R_IVA_PER"));
	
	$abon_anti=quitacom(r("anticipo"));
	$num_exp=r("num_exp");
	
	$confirm_bono=r('confirm_bono');
	if(empty($confirm_bono))$confirm_bono="NO";
	
	
	$placa=r("placa");
	$km=r("km");
	$marcaMoto=rm("marca_moto");
	
	$codComision=r("cod_comision");
	$tipoComi=r("tipo_comi");
	
	$DESCUENTO_IVA=quitacom(r("DESCUENTO_IVA"));
	
	$NUM_BOLSAS=r("bolsas");
 
	$preCal=($NUM_BOLSAS*$valor_impuesto_bolsas)*1;
	$IMP_BOLSAS=quitacom($preCal);

	
	if($form_p=="Credito"){
		$estado="PENDIENTE";
		//$num_fac=serial_credito($conex);
		//$PRE=$codCreditoSuc;
		
	}
	else
	{
		
	}

	$fe_naci="";//$_REQUEST['fe_naci'];
	$tipoImp="";//$_REQUEST['tipo_imp'];
	

	
    $columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,cod_comision,tipo_comi,id_vendedor,DESCUENTO_IVA";
	






$sql="UPDATE fac_venta SET id_cli='$ced',
						   nom_cli='$cliente',
						   dir='$dir',
						   tel_cli='$tel',
						   ciudad='$cuidad',
						   tipo_venta='$form_p',
						   tipo_cli='$tipo_cli',
						   vendedor='$ven',
						   mecanico='$meca',
						   cajero='$cajero',
						   fecha='$fecha',
						   val_letras='$vlr_let',
						   sub_tot=$sub,
						   iva=$iva,
						   descuento=$dcto,
                           tot=$tot,
						   entrega=$entrega,
						   cambio=$cambio,
						   modificable='si',
						   estado='$estado',
						   mail='$mail',
						   fe_naci='$fe_naci',
						   usu='$nomUsu',
						   id_usu='$id_Usu',
                           num_pagare='$pagare',
						   tot_bsf='$tot_bsf',
						   entrega_bsf='$entrega2',
						   r_fte='$r_fte',
						   r_iva='$r_iva',
						   r_ica='$r_ica',
						   per_fte='$r_fte_per',
						   per_iva='$r_iva_per',
                           per_ica='$r_ica_per',
						   num_exp='$num_exp',
						   abono_anti='$abon_anti',
						   anticipo_bono='$confirm_bono',
						   nota='$nota_fac',
						   NORECE='$nota_domicilio',
						   placa='$placa',
						   km='$km',
						   tot_tarjeta='$entrega3',
                           tec2='$meca2',
						   tec3='$meca3',
						   tec4='$meca4', 
						   id_cuenta='$idCta',
						   cod_comision='$codComision', 
						   tipo_comi='$tipoComi', 
						   id_vendedor='$idVen', 
						   marca_moto='$marcaMoto',
                           imp_consumo='$IMP_CONSUMO', 
						   DESCUENTO_IVA='$DESCUENTO_IVA', 
						   snombr='$snombr', 
						   apelli='$apelli',
						   num_bolsas='$NUM_BOLSAS',
						   impuesto_bolsas='$IMP_BOLSAS'
    WHERE num_fac_ven=$num_fac AND prefijo='$PRE' AND hash='$hash' AND nit=$codSuc ";

//echo "$sql";
$linkPDO->exec($sql);

$page="";
if(isset($_REQUEST['html']) && !	empty($_REQUEST['html'])){
	$page=$_REQUEST['html'];
logDB($sqlA,"Factura Venta",$OPERACIONES[2],"NO APLICA",$page,$num_fac);
}

/*$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,dir,tel,cuidad,cod_su,cliente,mail_cli,fe_naci) VALUES('$ced','$cliente','$dir','$tel','$cuidad',$codSuc,1,'$mail','$fe_naci')";
$linkPDO->exec($sql);*/

$linkPDO->exec("SAVEPOINT A");
if($num_exp!=0)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql="UPDATE exp_anticipo SET num_fac=$num_fac, prefijo='$PRE', estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
$linkPDO->exec($sql);

$sql="UPDATE comp_anti SET estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
$linkPDO->exec($sql);



}



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
		
		if(empty($feVenci)){$feVenci="0000-00-00";}
		$factor=$uni/$frac;
		$iva=quitacom($_REQUEST['iva_'.$i]);
		$dcto=quitacom($_REQUEST['dcto_'.$i]);
		if(empty($dcto))$dcto=0;
		if(empty($iva))$iva=0;
		$precio=quitacom($_REQUEST['val_uni'.$i]);
		$sub_tot=quitacom($_REQUEST['val_tot'.$i]);
		$SN=r("SN_".$i);
		$ordenIN=r("orden_in".$i);
		$garantia=r("COD_GARANTIA".$i);
		$num_motor=r("num_motor".$i);
		//$imprimirComanda=r('imprimirComanda'.$i);
		$imprimirComanda = !empty(r('imprimirComanda'.$i)) ? r('imprimirComanda'.$i) : 0;
		
		$sql="UPDATE art_fac_ven SET cant=$cant,unidades_fraccion='$uni', 
		      precio=$precio, dcto=$dcto, sub_tot=$sub_tot, serial='$SN',
			  cod_garantia='$garantia', num_motor='$num_motor', imprimir = '$imprimirComanda' 
			  WHERE num_fac_ven=$num_fac AND prefijo='$PRE' 
			  AND hash='$hash' AND nit=$codSuc 
			  AND (ref='$ref' AND cod_barras='$cod_bar' AND fecha_vencimiento='$feVenci')";
		$linkPDO->exec($sql);
		
		if($karDex==1 && $estado2=="CERRADA")
		{
		$update2="UPDATE inv_inter SET exist=(exist-(select cant from art_fac_ven where num_fac_ven=$num_fac AND prefijo='$PRE' AND hash='$hash' AND nit=$codSuc AND (ref='$ref' AND cod_barras='$cod_bar' AND fecha_vencimiento='$feVenci'))),unidades_frac=(unidades_frac-$uni)  WHERE id_pro='$ref' AND id_inter='$cod_bar' AND fecha_vencimiento='$feVenci' AND nit_scs=$codSuc";	

		}
		 	



$costoRef="";

$sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$cod_bar' FOR UPDATE";
$stmt = $linkPDO->query($sql);

if ($row = $stmt->fetch()) {
$costoRef="(select costo from inv_inter where id_inter='$cod_bar' AND id_pro='$ref' and nit_scs=$codSuc LIMIT 0,1)";	}
else {$costoRef=($precio/(($iva/100) +1)/(1.2));}


		if($vender_sin_inv!=0){$costoRef=0;}
		
		

		
		$Insert="INSERT IGNORE INTO art_fac_ven(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in,cod_garantia,hash,num_motor) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',$costoRef,'$PRE','$cod_bar','$color','$talla','$presen','$feVenci','$frac','$uni','$SN','$ordenIN','$garantia','$hash','$num_motor')";
		
		$sql="SELECT * FROM art_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$PRE' AND hash='$hash' AND nit=$codSuc AND (ref='$ref' AND cod_barras='$cod_bar' AND fecha_vencimiento='$feVenci')";
		$stmtAux = $linkPDO->query($sql);
		if ($rowAux = $stmtAux->fetch()) {}
		else{
			$linkPDO->exec($Insert);
			}
		
		
		
        //t2($Insert,$update);

		}
if($MODULES["SERVICIOS"]==1){
if(!empty($_REQUEST["cod_serv".$i]))
{
	
	$codServ=r("cod_serv".$i);
	$idServ=r("id_serv".$i);
	$serv=r("serv".$i);
	$nota=r("nota".$i);
	$ivaServ=limpianum(r("iva_serv".$i));
	$pvpServ=quitacom(r("val_serv".$i));
	$tec_serv=r("tec_serv".$i);
	
	$anchobanda=r("anchobanda".$i);
	$estratoPlan=r("estratoPlan".$i);
	$tipo_cliente=r("tipo_cliente".$i);
	
	$dctoServ=r("dctoServ_".$i);
	
	if(!empty($anchobanda)){$nota=quitacom($nota);}
	
	$sql="UPDATE serv_fac_ven SET nota='$nota', pvp='$pvpServ',id_tec='$tec_serv', anchobanda ='$anchobanda',estrato='$estratoPlan', tipo_cliente='$tipo_cliente', dcto='$dctoServ' WHERE num_fac_ven=$num_fac AND prefijo='$PRE' AND hash='$hash' AND cod_su='$codSuc' AND id_serv='$idServ'";
	//echo "$sqlServ";
	$linkPDO->exec($sql);
	
$sql="INSERT IGNORE INTO serv_fac_ven(num_fac_ven,prefijo,id_serv,serv,iva,pvp,cod_serv,cod_su,nota,id_tec,hash,anchobanda,estrato,tipo_cliente) VALUES('$num_fac','$PRE','$idServ','$serv','$ivaServ','$pvpServ','$codServ','$codSuc','$nota','$tec_serv','$hash','$anchobanda','$estratoPlan','$tipo_cliente')";
	
$linkPDO->exec($sql);
}
}
totFacVen($num_fac,$PRE,$codSuc);

$linkPDO->exec("SAVEPOINT C");

$sql="UPDATE mesas SET valor='$tot' WHERE id_mesa='$MesaID'";
//echo "$sql";
$linkPDO->exec($sql);


if($all_query_ok){
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

}
else{echo "ERROR";}
}catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}
?>