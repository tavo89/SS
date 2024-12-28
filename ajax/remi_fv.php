<?php
require_once("../Conexxx.php");

$NF=r("nf");
$PREFIJO=r("pre");
$sedeDest=r("dest");
$sedeOrig=r("orig");

try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


		$sql="SELECT * FROM fac_remi WHERE estado!='Recibido y Facturado' AND num_fac_ven='$NF' AND prefijo='$PREFIJO' AND nit='$sedeOrig' FOR UPDATE";
		
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch()){
		
		$chofer=chofer_sede($sedeDest);
		$idChofer=$chofer[0];
		
		$num_fac=serial_fac("credito","COM");
		$PRE=$codCreditoSuc;
		$RESOL=$ResolCredito;
		$fechaRESOL=$FechaResolCredito;
		$RANGO_RESOL=$RangoCredito;
	
		$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,sede_destino,tot_cre";
		
		$columnas2="'$num_fac',id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,'Mostrador',(select nombre FROM usuarios WHERE id_usu='$idChofer'),mecanico,cajero,now(),'',sub_tot,iva,descuento,tot,entrega,cambio,modificable,'$sedeOrig',estado,mail,fe_naci,'$PRE',usu,id_usu,'$RESOL','$fechaRESOL','$RANGO_RESOL',num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,'1',tot_cre";
		
	$sqlA="INSERT INTO fac_venta($columnas) SELECT $columnas2 FROM fac_remi WHERE num_fac_ven='$NF' AND prefijo='$PREFIJO' AND nit='$sedeOrig'";

$linkPDO->exec($sqlA);
		
		$linkPDO->exec("SAVEPOINT A");

		$sql="SELECT * FROM art_fac_remi WHERE num_fac_ven='$NF' AND prefijo='$PREFIJO' AND nit='$sedeOrig' FOR UPDATE";
		
		$rs=$linkPDO->query($sql);
		$ordenIN=0;
		$i=0;
		while($row=$rs->fetch())
		{
			$i++;
			$linkPDO->exec("SAVEPOINT LOOP".$i);
			
			$ref = $row["ref"]; 
			$cant=$row["cant"]-$row["cant_dev"];
            $det = $row["des"]; 
			$iva=$row["iva"];
			$precio=$row["precio"];
			$clase =$row["clase"];
			$cod_bar = $row["cod_barras"];
			$presentacion=$row['presentacion'];
			$frac = $row["fraccion"];
			$uni = $row["unidades_fraccion"];
			$fab =$row["fabricante"];
			$color=$row['color'];
			$talla=$row['talla']; 
			$fechaVen=$row['fecha_vencimiento'];
			
			$sub_tot=$cant*$precio;
			$dcto=0;
			$nit=$sedeOrig;
			$ordenIN++;
			
			$sql="INSERT INTO art_fac_ven(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',(select costo from inv_inter where id_inter='$cod_bar'  AND id_pro='$ref' and nit_scs=$sedeOrig AND fecha_vencimiento='$fechaVen'),'$PRE','$cod_bar','$color','$talla','$presentacion','$fechaVen','$frac','$uni','','$ordenIN')";
			
$linkPDO->exec($sql);
			//echo "$Insert<br>";
		}



totFacVen($num_fac,$PRE,$sedeOrig);
$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_ven ar inner join (select * from fac_venta f WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$sedeOrig' ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSuc and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist-a.cant), i.unidades_frac=(i.unidades_frac-a.unidades_fraccion) WHERE i.nit_scs=a.nitAr AND i.fecha_vencimiento=a.fecha_vencimiento and i.id_pro=a.ref and i.nit_scs=$sedeOrig"; 

$linkPDO->exec($sql);


$linkPDO->exec("SAVEPOINT C");

$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$NF AND prefijo='$PREFIJO' and nit='$sedeOrig' ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$sedeOrig and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist+a.cant), i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr AND i.fecha_vencimiento=a.fecha_vencimiento and i.id_pro=a.ref and i.nit_scs=$sedeOrig"; 

$linkPDO->exec($sql);

$sql="UPDATE fac_venta SET anulado='CERRADA' WHERE num_fac_ven='$num_fac' AND prefijo='$PRE' AND nit='$sedeOrig'";
$linkPDO->exec($sql);

$sql="UPDATE fac_remi SET estado='Recibido y Facturado',nf='$num_fac',pre='$PRE',anulado='' WHERE num_fac_ven='$NF' AND prefijo='$PREFIJO' AND nit='$sedeOrig'";
$linkPDO->exec($sql);

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){

}
else{ }


//if($confirmar_tras=="auto"){confirm_tras($sedeDest,$sedeOrig,$PRE,$num_fac);}

		}// fin val cantidades sede
		//echo "Operacin Completada";
		}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

		
?>