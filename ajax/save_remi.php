<?php
require_once("../Conexxx.php");
try {  

ini_set('memory_limit', '1024M'); 

//lock_tables("inv_inter WRITE, fac_remi WRITE, art_fac_remi WRITE");
$linkPDO->beginTransaction();
$all_query_ok=true;
$echo_all=0;
$TABLA="fac_remi";
$tabla2="art_fac_remi";
$tab2Cols="num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fraccion,unidades_fraccion,serial,orden_in,cod_garantia,num_motor";
$tab2Cols="num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in,cod_garantia,num_motor";

$codSuc=r("cod_orig");

$num_fac=r("num_fac_venta");
$PRE=r("pre");
$iRow=r('i');
//$i=r('i');
$num_art=r('num_ref');
$num_serv=r('num_serv');



$sql="SELECT * FROM $TABLA WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit='$codSuc' FOR UPDATE";
$stm=$linkPDO->query($sql);
$estado="";
if($row=$stm->fetch())
{
	$estado2=$row['anulado'];
	$karDex=$row['kardex'];	
}

	$cliente=rm('cli');
	$ced=r('ced');
	$cuidad=rm('city');
	$dir=rm('dir');
	$tel=r('tel');
	
	$nota_fac=rm("nota_fac");
	
	$form_p=r('form_pago');
	$tipo_cli=r('tipo_cli');
	$ven=r('vendedor');
	$meca=r('mecanico');
	$meca2=r('mecanico2');
	$meca3=r('mecanico3');
	$meca4=r('mecanico4');
	$cajero=s('tipo_usu');
	$fecha=r('fecha');
	//$vlr_let=$_REQUEST['vlr_let'];
	$vlr_let=r('vlr_let');
	
	$pagare=r("num_pagare");
	$sub=quitacom(r('sub'));
	$iva=quitacom(r('iva'));
	$dcto=quitacom(r('dcto2'));
	$tot=quitacom(r('tot'));
	$tot_bsf=quitacom(r('totB'));
	
	$entrega=quitacom(r('entrega'));
	$entrega2=quitacom(r('entrega2'));
	$cambio=quitacom(r('cambio'));
	$nit=s('cod_su');
	$mail=r('mail');
	
	if(($tot_bsf*1)<0 || $entrega2<=0)$tot_bsf=0;
	
	$r_fte=quitacom(r("R_FTE"));
	$r_ica=quitacom(r("R_ICA"));
	$r_iva=quitacom(r("R_IVA"));
	
	$r_fte_per=quitacom(r("R_FTE_PER"));
	$r_ica_per=quitacom(r("R_ICA_PER"));
	$r_iva_per=quitacom(r("R_IVA_PER"));
	
	$tot_cre=quitacom(r("TOT_CRE"));
	$abon_anti=quitacom(r("anticipo"));
	$num_exp=r("num_exp");
	
	$cod_dest=r("sucDestino");
	
	$placa=r("placa");
	$km=r("km");
	
	$codComision=r("cod_comision");
	
	

	
	if($form_p=="Credito"){
		$estado="PENDIENTE";
		//$num_fac=serial_credito($conex);
		//$PRE=$codCreditoSuc;
		
	}
	else
	{
		
	}

	$fe_naci="";//$_REQUEST['fe_naci'];
	$tipoImp="";//$_REQUEST['tipo_imp'];;
	
    $columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol";

$sql="UPDATE $TABLA SET 
id_cli='$ced',nom_cli='$cliente',dir='$dir',tel_cli='$tel',ciudad='$cuidad',tipo_venta='$form_p',tipo_cli='$tipo_cli',vendedor='$ven',mecanico='$meca',cajero='$cajero',fecha='$fecha',val_letras='$vlr_let',sub_tot=$sub,iva=$iva,descuento=$dcto,tot=$tot,entrega=$entrega,cambio=$cambio,modificable='si',mail='$mail',fe_naci='$fe_naci',usu='$nomUsu',id_usu='$id_Usu',num_pagare='$pagare',tot_bsf='$tot_bsf',entrega_bsf='$entrega2',r_fte='$r_fte',r_iva='$r_iva',r_ica='$r_ica',sede_destino='$cod_dest',tot_cre='$tot_cre',nota='$nota_fac',placa='$placa',km='$km',tec2='$meca2',tec3='$meca3',tec4='$meca4',cod_comision='$codComision'

WHERE num_fac_ven=$num_fac AND prefijo='$PRE' AND nit=$codSuc ";
//echo "$sql";
$linkPDO->exec($sql);


//$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,dir,tel,cuidad,cod_su,cliente,mail_cli,fe_naci) VALUES('$ced','$cliente','$dir','$tel','$cuidad',$codSuc,1,'$mail','$fe_naci')";
//$linkPDO->exec($sql);


$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$codSuc' AND anulado!='ANULADO') fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSuc and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist+a.cant), i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr AND i.fecha_vencimiento=a.fecha_vencimiento and i.id_pro=a.ref and i.nit_scs=$codSuc "; 

if($karDex==1)$linkPDO->exec($sql);


//echo "<li>num_art $num_art</li>";
for($i=0;$i<=$num_art;$i++)
	{
	
		$linkPDO->exec("SAVEPOINT LoopProductos".$i);
		if(isset($_REQUEST['cod_bar'.$i]))
		{
		$ref=r('ref_'.$i);
		$cod_bar=r('cod_bar'.$i);
		$color=rm('color'.$i);
		$talla=rm('talla'.$i);
		$det=rm('det_'.$i);
		$cant=limpianum(r('cant_'.$i));
		$uni=limpianum(r('unidades'.$i));
		
		$cantDev=limpianum(r('cant_dev'.$i));
		$uniDev=limpianum(r('unidades_dev'.$i));
		
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
		
		//ajusta_kardex_ref($codSuc,$ref,$cod_bar,$feVenci);
		
		$cantAnt=0;	
		$uniAnt=0;
		
		$sql="SELECT * FROM  $tabla2   WHERE nit='$codSuc' AND fecha_vencimiento='$feVenci' AND ref='$ref' AND cod_barras='$cod_bar' FOR UPDATE";
		$stmt = $linkPDO->query($sql);
		if ($row = $stmt->fetch()) {
			$cantAnt=$row["cant"]*1;	
			$uniAnt=$row["unidades_fraccion"]*1;
		}
		
			
		$sql="UPDATE $tabla2 SET cant=$cant,unidades_fraccion='$uni', precio=$precio,dcto=$dcto,sub_tot='$sub_tot',serial='$SN',cant_dev='$cantDev',uni_dev='$uniDev',cod_garantia='$garantia',num_motor='$num_motor' WHERE num_fac_ven=$num_fac AND prefijo='$PRE' AND nit='$codSuc' AND (ref='$ref' AND cod_barras='$cod_bar' AND fecha_vencimiento='$feVenci')";
		//echo "";
		$linkPDO->exec($sql);
		


$sql="SELECT * FROM inv_inter WHERE id_pro='$ref' AND id_inter='$cod_bar' FOR UPDATE";
$stmt = $linkPDO->query($sql);
if ($row = $stmt->fetch()) {
$costoRef="(select costo from inv_inter where id_inter='$cod_bar' AND id_pro='$ref' and nit_scs=$codSuc LIMIT 0,1)";	}
else {$costoRef=($precio/((100/$iva) +1)/(1.2));}		
	

if($vender_sin_inv!=0){$costoRef=0;}

$sql="INSERT IGNORE INTO $tabla2($tab2Cols) VALUES($num_fac,'$ref','$det',$iva,'$cant','$precio','$dcto','$sub_tot','$nit',$costoRef,'$PRE','$cod_bar','$color','$talla','$presen','$feVenci','$frac','$uni','$SN','$ordenIN','$garantia','$num_motor')";
$linkPDO->exec($sql);



		
		
        //t2($Insert,$update);

		}// fin insert/update referencia
		
		

if($MODULES["SERVICIOS"]==1){
//echo "<li>serv $i</li>";
if(!empty($_REQUEST["cod_serv".$i]))
{
		//echo "serv:".$_REQUEST["cod_serv".$i];
	
	$codServ=r("cod_serv".$i);
	$idServ=r("id_serv".$i);
	$serv=r("serv".$i);
	$nota=r("nota".$i);
	$tec_serv=r("tec_serv".$i);
	$ivaServ=limpianum(r("iva_serv".$i));
	$pvpServ=quitacom(r("val_serv".$i));
	
	$sql="UPDATE serv_fac_remi SET nota='$nota', pvp='$pvpServ',id_tec='$tec_serv' WHERE num_fac_ven=$num_fac AND prefijo='$PRE' AND cod_su='$codSuc' AND id_serv='$idServ'";
	//echo "$sql";
	$linkPDO->exec($sql);
	
	
	
	$sql="INSERT IGNORE INTO serv_fac_remi(num_fac_ven,prefijo,id_serv,serv,iva,pvp,cod_serv,cod_su) VALUES('$num_fac','$PRE','$idServ','$serv','$ivaServ','$pvpServ','$codServ','$codSuc')";
	
	$linkPDO->exec($sql);
	
}

}// fin servicios

//echo "<li>num_art $i</li>";
	}// fin FOR productos
		

			
$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$codSuc' AND anulado!='ANULADO') fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSuc and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist-a.cant), i.unidades_frac=(i.unidades_frac-a.unidades_fraccion) WHERE i.nit_scs=a.nitAr AND i.fecha_vencimiento=a.fecha_vencimiento and i.id_pro=a.ref and i.nit_scs=$codSuc  "; 
if($karDex==1)$linkPDO->exec($sql);

 

if($all_query_ok){
$linkPDO->commit();

//$linkPDO->exec("UNLOCK TABLES;");
$rs=null;
$stmt=null;
$linkPDO= null;

}
else{echo "ERROR";}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
?>