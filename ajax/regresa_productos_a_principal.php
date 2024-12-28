<?php
require_once("../Conexxx.php");
$NF=r("nf");
$PREFIJO=r("pre");
$sedeDest=r("dest");
$sedeOrig=r("orig");


		$sql="SELECT * FROM inv_inter WHERE exist>0 OR unidades_frac>0";
		
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch()){

		$num_fac=serial_fac("remision_com","REM_COM","fac_remi");
		$PRE=$codRemiCOM;
		$RESOL=$ResolRemiCOM;
		$fechaRESOL=$FechaRemiCOM;
		$RANGO_RESOL=$RangoRemiCOM;
	
		$columnas="num_fac_ven,id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,fecha,val_letras,sub_tot,iva,descuento,tot,entrega,cambio,modificable,nit,estado,mail,fe_naci,prefijo,usu,id_usu,resolucion,fecha_resol,rango_resol,num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,sede_destino,fecha_recibe";
		
		$columnas2="'$num_fac',id_cli,nom_cli,dir,tel_cli,ciudad,tipo_venta,tipo_cli,vendedor,mecanico,cajero,now(),'',sub_tot,iva,descuento,tot,entrega,cambio,modificable,'$sedeDest',estado,mail,fe_naci,'$PREFIJO',usu,id_usu,'$RESOL','$fechaRESOL','$RANGO_RESOL',num_exp,abono_anti,anticipo_bono,cod_caja,tot_bsf,kardex,num_pagare,'1',now()";
		
	$sqlA="INSERT INTO fac_remi($columnas) SELECT $columnas2 FROM fac_remi WHERE num_fac_ven='$NF' AND prefijo='$PREFIJO'";
		$linkPDO->exec($sqlA);
		
		$columns="".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,talla,color,".tabProductos.".presentacion,fecha_vencimiento,fraccion,unidades_frac,pvp_credito";
		
		$sql="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs=$sedeDest AND (exist>0 OR unidades_frac>0)";
		
		$rs=$linkPDO->query($sql);
		$ordenIN=0;
		while($row=$rs->fetch())
		{
			
			$ref = $row["id_glo"]; 
			$cant=$row["exist"];
            $det = $row["detalle"]; 
			$iva=$row["iva"];
			$precio=$row["precio_v"];
			$clase =$row["id_clase"];
			$cod_bar = $row["id_sede"];
			$frac = $row["fraccion"];
			$uni = $row["unidades_frac"];
			$fab =$row["fab"];
			$color=$row['color'];
			$talla=$row['talla']; 
			$fechaVen=$row['fecha_vencimiento'];
			
			$sub_tot=$cant*$precio;
			$dcto=0;
			$nit=$sedeDest;
			$ordenIN++;
			
			$Insert="INSERT INTO art_fac_remi(num_fac_ven,ref,des,iva,cant,precio,dcto,sub_tot,nit,costo,prefijo,cod_barras,color,talla,presentacion,fecha_vencimiento,fraccion,unidades_fraccion,serial,orden_in) VALUES($num_fac,'$ref','$det',$iva,$cant,$precio,$dcto,$sub_tot,'$nit',(select costo from inv_inter where id_inter='$cod_bar'  AND id_pro='$ref' and nit_scs=$sedeDest AND fecha_vencimiento='$fechaVen'),'$PRE','$cod_bar','$color','$talla',' ','$fechaVen','$frac','$uni',' ','$ordenIN')";
			
			$linkPDO->exec($Insert);
			//echo "$Insert<br>";
		}
	 $sqlB="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$num_fac AND prefijo='$PRE' and nit='$sedeDest' ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSuc and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist-a.cant), i.unidades_frac=(i.unidades_frac-a.unidades_fraccion) WHERE i.nit_scs=a.nitAr AND i.fecha_vencimiento=a.fecha_vencimiento and i.id_pro=a.ref and i.nit_scs=$sedeDest"; 

$linkPDO->exec($sqlB);  

if($confirmar_tras=="auto"){confirm_tras($sedeDest,$sedeOrig,$PRE,$num_fac);}

		}// fin val cantidades sede
		echo "Operaci&oacute;n Completada";
		
?>