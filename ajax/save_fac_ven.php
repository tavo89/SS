<?php
include_once("../Conexxx.php");
$hashFac="$hoy-$IP_CLIENTE";
//$hashFac=r("hashFac");

try {  
ini_set('memory_limit', '1024M'); 




$linkPDO->beginTransaction();
$all_query_ok=true;
$echo_all=0;
//if(($hostName=="test2.nanimosoft.com"|| $hostName=="127.0.0.12")){$echo_all=1;}

/*
$firmaOp="";
if($usar_firmas_cajas==1){
$sql="select firma_op FROM usuarios WHERE id_usu='$id_Usu'";
$stmt = $linkPDO->query($sql);
$row = $stmt->fetch();
$firmaOp=$row["firma_op"];
}*/

$sql="SELECT hash FROM fac_venta WHERE hash='$hashFac' AND nit='$codSuc' FOR UPDATE";
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {
    echo "REGISTRO DUPLICADO, $hashFac";
}
else{


$MesaID="";
if($MODULES["mesas_pedidos"]==1){
$MesaID=r("id_mesa");
	
}

if($codSuc!=1 || (empty($MesaID) && $MODULES["mesas_pedidos"]==1) ){$usar_posFac=0;}

// datos de REMISION/ORDNECOMPRA/COTIZACION
$cotiza_a_fac=r("co");
$nf=r("nf");
$pre=r("prefijo");
$idCliente=r("idCliente");
$reFacturarPOS=r("reFacturarPOS");
$FechaI=r("FechaI");
$FechaF=r("FechaF");
//-----------------------------------------
$filtroA="";$filtroB="";$filtroFecha="";$filtroFacturaSeleccionada="";

$filtro_remi_coti=" AND tipo_fac='remision' ";
if(!empty($nf) && !empty($pre))$filtroA=" AND (num_fac_ven='$nf' AND prefijo='$pre')";
if(!empty($nf) && !empty($pre))$filtroFacturaSeleccionada=" AND (a.num_fac_ven='$nf' AND a.prefijo='$pre')";
if($cotiza_a_fac==1)$filtro_remi_coti=" AND tipo_fac='cotizacion' $filtroFacturaSeleccionada";
if(!empty($idCliente)){$filtroB=" AND (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' AND anulado!='FACTURADA' ";/*$FLUJO_INV=-1;*/}

if(!empty($FechaI) && !empty($FechaF))$filtroFecha=" AND (date(fecha)>='$FechaI' AND date(fecha)<='$FechaF')";

if($cotiza_a_fac==1 ){$FLUJO_INV=1;}
if($reFacturarPOS){$FLUJO_INV=0;}


$n_r=0;
$n_s=0;
$hh=0;
$cod_su=0;
$TC=tasa_cambio();
if(isset($_SESSION['cod_su']))$cod_su=$_SESSION['cod_su'];

if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];

if(isset($_REQUEST['exi_serv']))$n_s=$_REQUEST['exi_serv'];
$num_fac="";
 
 

 
	$cliente=rm('cli');
	$ced=r('ced');
	//$ced = str_replace(array('.', ','), '' , $ced);
	$cuidad=rm('city');
	$dir=rm('dir');
	$tel=r('tel');
	$form_p=r('form_pago');
	$tipoResol=r("tipo_resol");
    $validaDiaSinIVA = ($tipoResol=='PAPEL');
	$nota_fac=rm("nota_fac");
	$nota_domicilio=rm("nota_domicilio");
	if($dia_sin_iva==1 && $validaDiaSinIVA){$nota_fac.='<br>D&iacute;a sin IVA decreto 1314 del 20 de octubre de 2021';}
	

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
	$tipoComi=r("tipo_comi");
	
	if($FLUJO_INV!=1)$fecha.=" $hora";
	//$vlr_let=$_REQUEST['vlr_let'];
	$vlr_let=r('vlr_let');
	
	$fecha=$hoy;
	

	$sub=quitacom(r('sub'));
	$iva=quitacom(r('iva'));
	$dcto=quitacom(r('dcto'));
	$tot=quitacom(r('tot'));
	
	$IMP_CONSUMO=(int)quitacom(r('IMP_CONSUMO'));
	$NUM_BOLSAS=r("bolsas");
	
	$tot=$tot+$IMP_CONSUMO;
	$bolsas=(!empty($_REQUEST['bolsas'])?$_REQUEST['bolsas']:0) ;
	$preCal=($bolsas*$valor_impuesto_bolsas)*1;
	$IMP_BOLSAS=quitacom($preCal);
	$totBsF=quitacom(r('totB'));
	//eco_alert("$totBsF");
	
	
	
	$entrega=quitacom(r('entrega'));
	//echo "<li>entrega 1 [$entrega]</li>";
	$entrega2=quitacom(r('entrega2'));
	$entrega3=quitacom(r('entrega3'));
	$cambio=quitacom(r('cambio'));
	if(($entrega+$entrega2) > ($tot*10000))
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
	
	$anticipo=quitacom(r('anticipo'));
	$num_exp=r('num_exp');
	$confirm_bono=r('confirm_bono');
if(empty($confirm_bono))$confirm_bono="NO";
	
	$estado="";
	
	//$PRE=$codContadoSuc;
	if($form_p=="Credito"){
		$estado="PENDIENTE";

	}

	$RESOLUCION_DATOS=asigna_resol_facVen($tipoResol);
	
	$num_fac=$RESOLUCION_DATOS["num_fac"];
	$PRE=$RESOLUCION_DATOS["PRE"];
	$RESOL=$RESOLUCION_DATOS["RESOL"];
	$fechaRESOL=$RESOLUCION_DATOS["fechaRESOL"];
	$RANGO_RESOL=$RESOLUCION_DATOS["RANGO_RESOL"];
	
	/*$flagNumRepetido=0;
	$MaxLoops=10;
	$currentLoop=0;
	do {
	
	$RESOLUCION_DATOS=asigna_resol_facVen($tipoResol);
	
	$num_fac=$RESOLUCION_DATOS["num_fac"];
	$PRE=$RESOLUCION_DATOS["PRE"];
	$RESOL=$RESOLUCION_DATOS["RESOL"];
	$fechaRESOL=$RESOLUCION_DATOS["fechaRESOL"];
	$RANGO_RESOL=$RESOLUCION_DATOS["RANGO_RESOL"];
	
	
	
	$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit='$codSuc'";
	$stmt = $linkPDO->query($sql);
	if ($row = $stmt->fetch()) {
	$all_query_ok=false;
	$flagNumRepetido=1;
	}
	else{ $flagNumRepetido=0;  }	
		
	$currentLoop++;
	}while($flagNumRepetido==1 && ($currentLoop<$MaxLoops));// FIN {DO WHILE}
	
	
	*/
	
	$r_fte=quitacom(r("R_FTE"));
	$r_ica=quitacom(r("R_ICA"));
	$r_iva=quitacom(r("R_IVA"));
	
	$r_fte_per=quitacom(r("R_FTE_PER"));
	$r_ica_per=quitacom(r("R_ICA_PER"));
	$r_iva_per=quitacom(r("R_IVA_PER"));
	
	$DESCUENTO_IVA=quitacom(r("DESCUENTO_IVA"));
	
	$tot=$tot-$r_fte-$r_ica-$r_iva;
	
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$PRE;
	$fe_naci="";//$_REQUEST['fe_naci'];
	$tipoImp="";//$_REQUEST['tipo_imp'];;
	
	$PLACA=rm("placa");
	
	$KM=limpianum(r("km"));
	$aliasCli=rm("aliasCli");
	
	$marcaMoto=rm("marca_moto");
	
	$TIPDOC=r("TIPDOC");
	if($tipoResol == 'PAPEL'){
		$TIPDOC=7;
	}
	$FECVEN=r("FECVEN");
	$MONEDA=r("MONEDA");
	$regFiscal=r("regFiscal");
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
	
	$nomcon=r("nomcon");
	$regtri=r("regtri");
	$razsoc=r("razsoc");
	$snombr=r("snombr");
	$apelli=r("apelli");
	
	


$idCta=r("id_cuenta");
//if($form_p=="Contado")$idCta="1";
if(empty($idCta) || $form_p=="Contado"){$idCta=val_caja_gral($form_p,"Ingresos","+");}


$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,
descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,num_exp,
abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,entrega_bsf,r_fte,r_iva,r_ica,per_fte,per_iva,per_ica,tasa_cam,placa,
km,nota,tot_tarjeta,tec2,tec3,tec4,id_cuenta,cod_comision,tipo_comi,imp_consumo,num_bolsas,impuesto_bolsas,id_vendedor,hash,
marca_moto,num_mesa, TIPDOC, FECVEN, MONEDA, regFiscal, CODSUC, NUMREF, FORIMP, CLADET, ORDENC, NUREMI, NORECE, EANTIE, COMODE, 
COMOHA, FACCAL, FETACA, FECREF, OBSREF, TEXDOC, MODEDI, NDIAN, SOCIED, MOTDEV, claper, coddoc, paicli, depcli, nomcon, regtri, 
razsoc, snombr, apelli,DESCUENTO_IVA,tipo_resol,anulado";
	
	

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$linkPDO->exec("SAVEPOINT creaFact");

$sqlA="INSERT INTO fac_venta($columnas) VALUES($num_fac,'$ced','$cliente','$dir','$tel','$cuidad','$form_p','$tipo_cli',
       '$ven','$meca','$cajero','$fecha','$vlr_let',$sub,$iva,$dcto,$tot,$entrega,$cambio,'si','$nit','$estado','$mail',
	   '$fe_naci','$PRE','$nomUsu','$id_Usu','$RESOL','$fechaRESOL','$RANGO_RESOL','$num_exp','$anticipo','$confirm_bono',
	   '$codCaja','$totBsF','$FLUJO_INV','$NUM_PAGARE','$entrega2','$r_fte','$r_iva','$r_ica','$r_fte_per','$r_iva_per',
	   '$r_ica_per','$TC','$PLACA','$KM','$nota_fac','$entrega3','$meca2','$meca3','$meca4','$idCta','$codComision',
	   '$tipoComi','$IMP_CONSUMO','$NUM_BOLSAS','$IMP_BOLSAS','$idVen','$hashFac','$marcaMoto','$MesaID', '$TIPDOC', 
	   '$FECVEN', '$MONEDA', '$regFiscal', '$CODSUC', '$NUMREF', '$FORIMP', '$CLADET', '$ORDENC', '$NUREMI', '$nota_domicilio', 
	   '$EANTIE', '$COMODE', '$COMOHA', '$FACCAL', '$FETACA', '$FECREF', '$OBSREF', '$TEXDOC', '$MODEDI', '$NDIAN', '$SOCIED', 
	   '$MOTDEV', '$claper', '$coddoc', '$paicli', '$depcli', '$nomcon', '$regtri', '$razsoc', '$snombr', '$apelli','$DESCUENTO_IVA','$tipoResol','')";
$linkPDO->exec($sqlA);
if($echo_all==1){echo "<li>$sqlA</li>";}



if($MesaID) {
	$linkPDO->exec("SAVEPOINT updateMesa");
	$sqlM="UPDATE mesas SET estado='Ocupada', num_fac_ven='$num_fac', prefijo='$PRE', hash='$hashFac' WHERE id_mesa='$MesaID'";
	$linkPDO->exec($sqlM);

	if($echo_all==1){echo "<li>$sqlM</li>";}
}

$linkPDO->exec("SAVEPOINT antesLoop1");


$page="";
if(isset($_REQUEST['html']))$page=$_REQUEST['html'];
logDB($sqlA,"Factura Venta",$OPERACIONES[1],"NO APLICA",$page,"$num_fac");

if(!empty($ced) && $ced!=$NIT_PUBLICO_GENERAL && $cliente!=$PUBLICO_GENERAL 
   && $cliente!="PUBLICO GENERAL" && !empty($snombr) && !empty($apelli) && !empty($cliente)){

	$DATOS_CLI["id_usu"]=$ced;
	$DATOS_CLI["nombre"]=$cliente;
	$DATOS_CLI["snombr"]=$snombr;
	$DATOS_CLI["apelli"]=$apelli;
	$DATOS_CLI["dir"]=$dir;
	$DATOS_CLI["tel"]=$tel;
	$DATOS_CLI["cuidad"]=$cuidad;
	$DATOS_CLI["cliente"]=1;
	$DATOS_CLI["mail_cli"]=$mail;
	$DATOS_CLI["fe_naci"]=$fe_naci;
	$DATOS_CLI["alias"]=$aliasCli;
	$DATOS_CLI["cod_su"]=$codSuc;
//reg_cli_fac($DATOS_CLI);



}

$sql="SELECT id_usu FROM usuarios WHERE id_usu='$ced' FOR UPDATE";
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {
	
$sql="UPDATE IGNORE usuarios SET nombre='$cliente',tel='$tel',dir='$dir',
cuidad='$cuidad',mail_cli='$mail',fe_naci='$fe_naci',snombr='$snombr', 
apelli='$apelli', claper='$claper',coddoc='$coddoc',paicli='$paicli',
depcli='$depcli',regFiscal='$regFiscal', nomcon='$nomcon',
regtri='$regtri',razsoc='$razsoc' 
WHERE id_usu='$ced'";
$linkPDO->exec($sql);
if($echo_all==1){echo "<li>$sql</li>";}
	
}

//echo "numExpAnticipo $num_exp <br>";
if($num_exp!=0 && !empty($num_exp))
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$sql="UPDATE exp_anticipo SET num_fac=$num_fac, prefijo='$PRE', estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
	$linkPDO->exec($sql);
	
	if($echo_all==1){echo "<li>$sql</li>";}


	$sql="UPDATE comp_anti SET estado='COBRADO' WHERE num_exp=$num_exp AND cod_su=$codSuc";
	$linkPDO->exec($sql);
	if($echo_all==1){echo "<li>$sql</li>";}

}




$id_fac=0;
$num_art=r('num_ref');
$num_serv=r('num_serv');

$linkPDO->exec("SAVEPOINT antesLoop2");
$sql="SELECT num_fac_ven FROM fac_venta WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$codSuc' FOR UPDATE" ;
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {


	
////////////////////////////// ARTICULOS///////////////////////////////////////////////////////////////


	
	$update="";
	$II=0;
	$UPDATE[]="";
	$color="";
	$talla="";
	//eco_alert("$num_art");
	//echo "<li>$num_art </li>";
	for($i=0;$i<$num_art;$i++)
	{
		$linkPDO->exec("SAVEPOINT LoopProductos".$i);
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
		if(empty($feVenci)){$feVenci="0000-00-00";}
		 
		$factor=$uni/$frac + $cant;
		$iva=quitacom(r('iva_'.$i));
		
		
		$dcto=quitacom(r('dcto_'.$i));
		if(empty($dcto))$dcto=0;
		if(empty($iva))$iva=0;
		$precio=quitacom(r('val_uni'.$i));
		$sub_tot=quitacom(r('val_tot'.$i));
		$SN=r("SN_".$i);
		$ordenIN=r("orden_in".$i);
		$garantia=r("COD_GARANTIA".$i);
		$num_motor=r("num_motor".$i);
		
		
$costoRef="";

$sql="SELECT * 
      FROM inv_inter 
	  WHERE id_pro='$ref' AND id_inter='$cod_bar' and nit_scs=$codSuc FOR UPDATE";
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {
	$costoRef="(select IFNULL(costo,0) from inv_inter where id_inter='$cod_bar' AND id_pro='$ref' and nit_scs=$codSuc LIMIT 0,1)";
	$impuestoSaludable = $row['impuesto_saludable'];
}
else {
	$iva = $iva>0 ? $iva:1;
	$costoRef=($precio/((100/$iva) +1)/(1.2));
}	


if($vender_sin_inv!=0){$costoRef=0;}

 if(empty($costoRef)){$costoRef=0;}
		
$sql="INSERT INTO   art_fac_ven(num_fac_ven,
                               ref,
							   des,
							   iva,
							   cant,
							   precio,
							   dcto,
							   sub_tot,
							   nit,
							   costo,
							   prefijo,
							   cod_barras,
							   color,
							   talla,
							   presentacion,
							   fecha_vencimiento,
							   fraccion,
							   unidades_fraccion,
							   serial,
							   orden_in,
							   cod_garantia,
							   hash,
							   num_motor,
							   impuesto_saludable) 
				    VALUES    ($num_fac,
                              '$ref',
							  '$det',
							   $iva,
							   $cant,
							   $precio,
							   $dcto,
							   $sub_tot,
							  '$nit',
							   $costoRef,
							  '$PRE',
							  '$cod_bar',
							  '$color',
							  '$talla',
							  '$presen',
							  '$feVenci',
							  '$frac',
							  '$uni',
							  '$SN',
							  '$ordenIN',
							  '$garantia',
							  '$hashFac',
							  '$num_motor',
							  '$impuestoSaludable')";	
$linkPDO->exec($sql);	

if($echo_all==1){echo "<li>$sql</li>";}

		

//// resta cantidades




/*
Validacion para saber cuando se debe restar cantidades del inventario

NOTAS
Solo los cajeros pueden cerrar facturas
Vendedores solo generan registro de factura, y se descarga de inv si la variable $usar_posFac==1
Los admin siempre descargan inv a menos que $usar_posFac==1
val_secc($id_Usu,"caja_centro") Permiso que indica que el usuario tiene control de la caja diaria

Cuando no se descarga:
- cuando $cotiza_a_fac==1, porque se descarga inv previamente en los formatos de REMISION/ORDEN COMPRA.
- cuando $FLUJO_INV==0
- cuando $usar_posFac==1 y el usuario no es admin o cajero

-$usar_posFac==0 : cuando se usa PostFac primero se crea el registro de la factura de venta, y los descargues de inventario quedan pendientes
-$FLUJO_INV==1   : Opcion que habilita descargar productos teniendo en cuenta su disponibilidad, si el valor es cero, el sistema permite descargar hasta llegar a saldos negativos.
-

*/

$validaPostFacYflujoInv      = $usar_posFac==0 && $FLUJO_INV==1;
$validaRolusuario            = $rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_mod"); // no es admin y no tiene permiso para modificar facturas ( vendedor mas no cajero)
$validaEsRemisionCotizacion  = empty($nf) && empty($pre) && empty($idCliente);

	
if( ($validaPostFacYflujoInv || ($FLUJO_INV==1 &&  $validaRolusuario  && val_secc($id_Usu,"caja_centro") ))  && ( $validaEsRemisionCotizacion || $cotiza_a_fac==1) ){	


$sql="UPDATE `inv_inter`  SET exist=(exist-$cant), unidades_frac=(unidades_frac-$uni) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$feVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
$linkPDO->exec($sql);
if($echo_all==1){echo "<li>$sql</li>";}

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
	
	$anchobanda=r("anchobanda".$i);
	$estratoPlan=r("estratoPlan".$i);
	$tipo_cliente=r("tipo_cliente".$i);
	
	if(!empty($anchobanda)){$nota=quitacom($nota);}
	
	$sql="INSERT INTO serv_fac_ven(num_fac_ven,prefijo,id_serv,serv,iva,pvp,cod_serv,cod_su,nota,id_tec,hash,anchobanda,estrato,tipo_cliente) VALUES('$num_fac','$PRE','$idServ','$serv','$ivaServ','$pvpServ','$codServ','$codSuc','$nota','$tec_serv','$hashFac','$anchobanda','$estratoPlan','$tipo_cliente')";
	//eco_alert("$sqlServ");
$linkPDO->exec($sql);
if($echo_all==1){echo "<li>$sql</li>";}

}

}
	}//FIN FOR ARTICULOS

$linkPDO->exec("SAVEPOINT sp3");

totFacVen($num_fac,$PRE,$codSuc);
//echo "pre IF inv --";



if( (($usar_posFac==0 && $FLUJO_INV==1) || 
($FLUJO_INV==1 && (($rolLv!=$Adminlvl && !val_secc($id_Usu,"fac_mod")) ) && val_secc($id_Usu,"caja_centro") ))  
&& ((empty($nf) && empty($pre) && empty($idCliente)) || $cotiza_a_fac==1) ){
	up_cta($form_p,$idCta,($tot-$anticipo),"+","Venta $PRE-$num_fac","Factura Venta",$fecha);
	$sql="UPDATE fac_venta SET anulado='CERRADA' WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit=$codSuc";
	$linkPDO->exec($sql);
if($echo_all==1){echo "<li>$sql</li>";}

}
}


if(!empty($idCliente) ){
$sql="UPDATE fac_remi SET anulado='FACTURADA' WHERE (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' $filtroFecha $filtroA ";
$linkPDO->exec($sql);
if($echo_all==1){echo "<li>$sql</li>";}

}

if( $reFacturarPOS ){
	$sql = "SELECT serial_fac FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit=$codSuc";
	$rs=$linkPDO->query($sql);
	if($row = $rs->fetch()){

	$idFacturaPOSelectronica = $row['serial_fac'];
	$sql="UPDATE fac_venta SET idFacturaDian='$idFacturaPOSelectronica'
	      WHERE TIPDOC != '7' AND idFacturaDian=0 $filtroFecha  ";
		  //echo "<li>$sql</li>";
	$linkPDO->exec($sql);

	$sql="UPDATE fac_venta SET anulado='CERRADA', corte_cont='NO', anulado='DIAN' WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit=$codSuc";
	$linkPDO->exec($sql);

	if($echo_all==1){echo "<li>$sql</li>";}

	}
	
	}

if(!empty($idCliente) && $cotiza_a_fac==0){
$sql="UPDATE fac_venta SET num_remi='$nf', pre_remi='$pre' WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit=$codSuc";
$linkPDO->exec($sql);
if($echo_all==1){echo "<li>$sql</li>";}

}
 
if($all_query_ok){


$linkPDO->commit();



$respuesta = array('successCode'=>1,
                   'numFactura'=>$num_fac,
				   'prefijoFac'=>$PRE,
				   'codSuc'=>$codSuc,
				   'serialFac'=>"$PRE $num_fac",
				   'mailTo'=>$mail,
				   'idCliente'=>$ced
				 );

if($MODULES["mesas_pedidos"]!=1 || (empty($MesaID) && $MODULES["mesas_pedidos"]==1)){
	
	echo json_encode($respuesta);
}
else {
	$respuesta['successCode']=2;
	echo json_encode($respuesta);

} 

$_SESSION['n_fac_ven']=$num_fac;
$_SESSION['prefijo']=$PRE;
$_SESSION['TIPDOC']=$TIPDOC;

}else
{
	echo "ERROR!";
}




}// VALIDACION REPETIDO
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}


$rs=null;
$stmt=null;
$linkPDO= null;
?>