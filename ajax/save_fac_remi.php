<?php
require_once("../Conexxx.php");
try {  

ini_set('memory_limit', '1024M'); 
$linkPDO->beginTransaction();
$all_query_ok=true;
$echo_all=0;

$TALLER=r("OT");
$COTIZACION=r("co");
$URL_LIST="remisiones.php";
$ENCABEZADO_FAC="REMISION";

$tipoFAC=r("tipoFAC");

$cotiza_a_fac=r("co_remi");
$nf=r("nf");
$pre=r("prefijo");
$idCliente=r("idCliente");
$FechaI=r("FechaI");
$FechaF=r("FechaF");
$filtroA="";$filtroB="";$filtroFecha="";$filtroFacturaSeleccionada="";
$filtro_remi_coti=" AND tipo_fac='cotizacion' ";

if(!empty($nf) && !empty($pre))$filtroA=" AND (num_fac_ven='$nf' AND prefijo='$pre')";
if(!empty($nf) && !empty($pre))$filtroFacturaSeleccionada=" AND (a.num_fac_ven='$nf' AND a.prefijo='$pre')";
if($cotiza_a_fac==1)$filtro_remi_coti=" AND tipo_fac='cotizacion' $filtroFacturaSeleccionada";
if(!empty($idCliente)){$filtroB=" AND (id_cli='$idCliente' OR nom_cli='$idCliente' ) AND anulado!='ANULADO' AND anulado!='FACTURADA' ";/*$FLUJO_INV=-1;*/}
if(!empty($FechaI) && !empty($FechaF))$filtroFecha=" AND (date(fecha)>='$FechaI' AND date(fecha)<='$FechaF')";
if($COTIZACION==1){
	
	$ENCABEZADO_FAC="COTIZACI&Oacute;N";
	$URL_LIST="COTIZACIONES.php";
	$FLUJO_INV=-1;
	//$_SESSION['FLUJO_INVENTARIO']=1;
	}
$cod_origen=r("origen");

 


$n_r=0;
$n_s=0;
$hh=0;
$cod_su=0;
$TC=tasa_cambio();
if(isset($_SESSION['cod_su']))$cod_su=$_SESSION['cod_su'];
if(isset($_REQUEST['exi_ref']))$n_r=$_REQUEST['exi_ref'];
if(isset($_REQUEST['exi_serv']))$n_s=$_REQUEST['exi_serv'];
$num_fac=0;
$boton=r("boton");





















	$cliente=rm('cli');
	$ced=r('ced');
	$cuidad=rm('city');
	$dir=rm('dir');
	$tel=r('tel');
	$sedeDest=r("sucDestino");
	
	
	$nota_fac=rm("nota_fac");
	
	
	$form_p=r('form_pago');
	$tipo_cli=r('tipo_cli');
	if(!empty($sedeDest)){$tipo_cli="Traslado";}
	$ven=r('vendedor');
	$meca=r('mecanico');
	$meca2=r('mecanico2');
	$meca3=r('mecanico3');
	$meca4=r('mecanico4');
	$cajero=s('tipo_usu');
	$fecha=r('fecha');
	if($FLUJO_INV!=1)$fecha.=" $hora";
	//$vlr_let=$_REQUEST['vlr_let'];
	$vlr_let=r('vlr_let');
	
	$sub=quitacom($_REQUEST['sub']);
	$iva=quitacom($_REQUEST['iva']);
	$dcto=quitacom($_REQUEST['dcto2']);
	$tot=quitacom($_REQUEST['tot']);
	$totBsF=quitacom($_REQUEST['totB']);
	$pagoBsf=r("bsF_flag");
	if($pagoBsf!=1)$totBsF=0;
	
	$entrega=r('entrega');
	$entrega=quitacom($entrega);
	$cambio=quitacom($_REQUEST['cambio']);
	if($entrega>($tot*10000))
	{
		$entrega=$tot;
		$cambio=0;
		}
	$nit=s('cod_su');
	$mail=r('mail');
	

	
	$NUM_PAGARE=r("num_pagare");
	
	$anticipo=quitacom($_REQUEST['anticipo']);
	$num_exp=limpiarcampo($_REQUEST['num_exp']);
	$confirm_bono=r('confirm_bono');
if(empty($confirm_bono))$confirm_bono="NO";


	//$num_fac=serial_fac("factura venta","POS");
	$estado="";
	$tipoResol=r("tipo_resol");
	
	
	$codComision=r("cod_comision");
	
	
	
	
	

	$flagNumRepetido=0;
	$MaxLoops=10;
	$currentLoop=0;
	$RESOLUCION_DATOS=asigna_resol_remi($tipoResol);
	
	$num_fac=$RESOLUCION_DATOS["num_fac"];
	$RESOL=$RESOLUCION_DATOS["RESOL"];
	$PRE=$RESOLUCION_DATOS["PRE"];
	$fechaRESOL=$RESOLUCION_DATOS["fechaRESOL"];
	$RANGO_RESOL=$RESOLUCION_DATOS["RANGO_RESOL"];

	
	
	
	
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$PRE;
	$fe_naci="";//$_REQUEST['fe_naci'];
	$tipoImp="";//$_REQUEST['tipo_imp'];
	
	$PLACA=rm("placa");
	
	$KM=limpianum(r("km"));
	
	
	
    $columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,sede_destino,placa,km,nota,tec2,tec3,tec4,tipo_fac,cod_comision";
	
$tipo_fac="remision";
if($COTIZACION==1){$tipo_fac="cotizacion";}
if($tipoFAC=="OT"){$tipo_fac="remision";}
if($tipoFAC=="remi"){$tipo_fac="remision2";}


$sql="INSERT INTO fac_remi($columnas) VALUES($num_fac,'$ced','$cliente','$dir','$tel','$cuidad','$form_p','$tipo_cli','$ven','$meca','$cajero','$fecha','$vlr_let',$sub,$iva,$dcto,$tot,$entrega,$cambio,'si','$nit','$estado','$mail','$fe_naci','$PRE','$nomUsu','$id_Usu','$RESOL','$fechaRESOL','$RANGO_RESOL','$num_exp','$anticipo','$confirm_bono','$codCaja','$totBsF','$FLUJO_INV','$NUM_PAGARE','$sedeDest','$PLACA','$KM','$nota_fac','$meca2','$meca3','$meca4','$tipo_fac','$codComision')";
$linkPDO->exec($sql);

if($echo_all==1){echo "<li>$sql</li>";}

logDB('',"Factura Venta",$OPERACIONES[1],"NO APLICA",'',$num_fac);

if(!empty($ced) && !empty($cliente)){
$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,dir,tel,cuidad,cod_su) VALUES('$ced','$cliente','$dir','$tel','$cuidad',$codSuc)";
$linkPDO->exec($sql);


}






$id_fac=0;
$num_art=$_REQUEST['num_ref'];
$num_serv=$_REQUEST['num_serv'];

$linkPDO->exec("SAVEPOINT A");

$sql="SELECT num_fac_ven FROM fac_remi WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$codSuc' FOR UPDATE";

$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {


	
////////////////////////////// ARTICULOS ///////////////////////////////////////////////////////////////


	
	$update="";
	$II=0;
	$UPDATE[]="";
	$color="";
	$talla="";
	//echo "<li>$num_art </li>";
	for($i=0;$i<$num_art;$i++)
	{
		$linkPDO->exec("SAVEPOINT LOOP".$i);
		//echo "cod_bar$i:".$_REQUEST['cod_bar'.$i];
		if(isset($_REQUEST['cod_bar'.$i]))
		{
		$ref=r('ref_'.$i);
		$cod_bar=r('cod_bar'.$i);
		if(isset($_REQUEST['color'.$i]))$color=rm('color'.$i);
		if(isset($_REQUEST['talla'.$i]))$talla=rm('talla'.$i);
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
		$garantia=r("COD_GARANTIA".$i);
		$ordenIN=r("orden_in".$i);
		$num_motor=r("num_motor".$i);
$costoRef="";
$sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$cod_bar' and nit_scs=$codSuc FOR UPDATE";
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {
$costoRef="(select costo from inv_inter where id_inter='$cod_bar' AND id_pro='$ref' and nit_scs=$codSuc LIMIT 0,1)";	}
else {$costoRef=($precio/((100/$iva) +1)/(1.2));}


if($vender_sin_inv!=0){$costoRef=0;}
$sql="INSERT INTO art_fac_remi(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in,cod_garantia,num_motor) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',$costoRef,'$PRE','$cod_bar','$color','$talla','$presen','$feVenci','$frac','$uni','$SN','$ordenIN','$garantia','$num_motor')";
$linkPDO->exec($sql);

if($echo_all==1){echo "<li>$sql</li>";}


if($FLUJO_INV==1){
	
$sql="UPDATE `inv_inter`  SET exist=(exist-$cant), unidades_frac=(unidades_frac-$uni) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$feVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
$linkPDO->exec($sql);

if($echo_all==1){echo "<li>$sql</li>";}
 
	}
	
		
        //t2($Insert,$update);

		}
//eco_alert("codServ: ".$_REQUEST["cod_serv".$i]."");
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
	
	$sql="INSERT INTO serv_fac_remi(num_fac_ven,prefijo,id_serv,serv,iva,pvp,cod_serv,cod_su,nota,id_tec) VALUES('$num_fac','$PRE','$idServ','$serv','$ivaServ','$pvpServ','$codServ','$codSuc','$nota','$tec_serv')";
$linkPDO->exec($sql);

	if($echo_all==1){echo "<li>$sql</li>";}
	
}		
}
	}//FIN FOR ARTICULOS




}



if($all_query_ok){
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$PRE;
	
	if($confirmar_tras=="auto"){confirm_tras($codSuc,$sedeDest,$PRE,$num_fac);}
 
	echo 1;
}
else{echo "REPETIDO FAC : [$flagNumRepetido] $num_fac $PRE $RESOL";}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
?>