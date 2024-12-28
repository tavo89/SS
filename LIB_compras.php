<?php
// CERRAR FAC. COMPRA
function cerrar_fc($num_fac,$nit_pro,$codSuc)
{

	global $munSuc,$usar_costo_dcto,$OPC_COMPRAS_REPLACEREF,$promediar_costos,$FLUJO_INV,$conex,$linkPDO;
	$estado="";
	$confirm="";

try {
$linkPDO->beginTransaction();
	$all_query_ok=true;
	$sql_promCosto="";
	$INSERTS="";
	$UPDATES="";
	$SQL="SELECT * FROM fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc' FOR UPDATE";
	$stmt = $linkPDO->query($SQL);
	if ($row = $stmt->fetch()) {
		$FechaFactura=$row["fecha"];
		$estado=$row['estado'];
		$dcto2=$row['dcto2'];
		$per_flete=($row["perflete"]/100)+1;
		$tipoFac=$row["tipo_fac"];

	}
	//echo "EST:$estado, CONFIRM:$confirm<BR>";
	if($tipoFac=="Ajuste Seccion" || $tipoFac=="Corte Inventario" || $tipoFac=="Inventario Inicial"){
		$promediar_costos=0;
	}
	if($estado!="CERRADA")
	{
	$linkPDO->exec("SAVEPOINT sp3");
	$SQL="SELECT * FROM art_fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc FOR UPDATE";
	//echo "$SQL---->";
	$stmt = $linkPDO->query($SQL);
	$color="";
	$talla="";
	if($tipoFac!="Corte Inventario" && $tipoFac!="Ajuste Seccion"){
	while($row = $stmt->fetch())
	{
			set_time_limit(90);
			$cant=$row['cant'];
			$ref=limpiarcampo($row['ref']);
			$cod_bar=limpiarcampo($row['cod_barras']);
			$presentacion=limpiarcampo($row['presentacion']);
			$fab=limpiarcampo($row['fabricante']);
			$des=limpiarcampo($row['des']);
			$des_full=limpiarcampo($row['des_full']);
			$color=limpiarcampo($row['color']);
			$talla=limpiarcampo($row['talla']);
			$clase=limpiarcampo($row['clase']);
			$fabricante=limpiarcampo($row['fabricante']);
			$costo=limpiarcampo($row['costo']);
			$dcto=$row['dcto'];
			$iva=$row['iva'];
			$impSaludable=$row["impuesto_saludable"];
			$uti=$row['uti'];
			//$uti=util($pvp,$costoDesc,$iva,"per");
			$fechaVenci=$row['fecha_vencimiento'];
			if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
			$pvp=$row['pvp'];
			$s_tot=$row['tot'];
			$frac=$row['fraccion'];
			if($frac==0)$frac=1;
			$uni=$row['unidades_fraccion'];
			$ubica=$row["ubicacion"];

			$aplica_vehi=limpiarcampo($row["aplica_vehi"]);
			$campo_add_01=limpiarcampo($row["campo_add_01"]);
			$campo_add_02=limpiarcampo($row["campo_add_02"]);

			$cod_color=limpiarcampo($row["cod_color"]);
			$vigencia_inicial=limpiarcampo($row["vigencia_inicial"]);
			$grupo_destino=limpiarcampo($row["grupo_destino"]);

			$impConsumo=$row["impuesto_consumo"];
			$perImpConsumo=$impConsumo/100 + 1;

			if($usar_costo_dcto==1)$costoDesc=$costo*$per_flete - $costo*($dcto/100);
			else $costoDesc=$costo*$per_flete*$perImpConsumo;
			$factor=($uni+($cant*$frac))/$frac;


			if($pvp==0&&$uti!=0)$pvp=calc_pvp($costoDesc,$uti,$iva);

			if($uti==0)$uti=util($pvp,$costoDesc,$iva,"per");

			$pvpCre=$row["pvp_cre"];
			$pvpMay=$row["pvp_may"];

			if(!empty($des) && !empty($ref) && !empty($cod_bar)){

			if($OPC_COMPRAS_REPLACEREF==0){
			$INSERTS.="INSERT IGNORE INTO ".tabProductos." (id_pro,detalle,id_clase,frac,fab,presentacion,nit_proveedor) 
					VALUES ('$ref','$des','$clase','$frac','$fabricante','$presentacion','$nit_pro');";

			$UPDATES.="UPDATE ".tabProductos." SET detalle='$des', 
					id_clase='$clase',
					fab='$fabricante',
					presentacion='$presentacion',
					nit_proveedor='$nit_pro' 
					WHERE id_pro='$ref';";

			}else{

				$SQL_aux="SELECT id_pro FROM inv_inter WHERE id_inter='$cod_bar'  AND nit_scs='$codSuc'";
				$rsREF=$linkPDO->query($SQL_aux);
				if($rowREF=$rsREF->fetch()){

				$refRS=$rowREF["id_pro"];

				if(empty($des_full)){
					$UPDATES.="UPDATE IGNORE ".tabProductos." SET id_pro='$ref',
																detalle='$des', 
																id_clase='$clase',
																fab='$fabricante',
																presentacion='$presentacion',
																nit_proveedor='$nit_pro' 
								WHERE id_pro='$refRS';";}

				else{$UPDATES.="UPDATE IGNORE ".tabProductos." SET id_pro='$ref',
																detalle='$des',
																id_clase='$clase',
																fab='$fabricante',
																presentacion='$presentacion',
																nit_proveedor='$nit_pro',
																des_full='$des_full' 
								WHERE id_pro='$refRS';";}



			$UPDATES.="UPDATE IGNORE inv_inter SET id_pro='$ref' WHERE id_pro='$refRS';";


				}else{

			if( !empty($ref) && !empty($cod_bar) && !empty($des)){$INSERTS.="INSERT IGNORE INTO ".tabProductos." (id_pro,detalle,id_clase,frac,fab,presentacion,nit_proveedor) VALUES ('$ref','$des','$clase','$frac','$fabricante','$presentacion','$nit_pro');";}
				}
			}



			if(!empty($aplica_vehi)){$UPDATES.="UPDATE inv_inter SET aplica_vehi='$aplica_vehi' WHERE id_pro='$ref' AND id_inter='$cod_bar' AND nit_scs='$codSuc';";
			}




			if(!empty($color)){$INSERTS.="INSERT IGNORE INTO colores(color) VALUES('$color');";}
			if(!empty($talla)){$INSERTS.="INSERT IGNORE INTO tallas(talla) VALUES('$talla');";}
			if(!empty($clase)){$INSERTS.="INSERT IGNORE INTO clases(des_clas) VALUES('$clase');";}
			if(!empty($fab)){$INSERTS.="INSERT IGNORE INTO fabricantes(fabricante) VALUES('$fab');";}
			if(!empty($presentacion)){$INSERTS.="INSERT IGNORE INTO presentacion(presentacion) VALUES('$presentacion');";}


			if($promediar_costos==1 && $estado!="ANULADA" && $FLUJO_INV==1){

				$sql_promCosto=prom_cost($costoDesc,$uti,$iva,$factor,$cod_bar,$pvp);
				$linkPDO->exec($sql_promCosto);
				}







	if( !empty($ref) && !empty($cod_bar) && !empty($des)){

	$INSERTS.="INSERT IGNORE INTO  `inv_inter` (`id_pro` ,
												`nit_scs` ,
												`id_inter` ,
												`exist` ,
												`max` ,
												`min` ,
												`costo` ,
												`precio_v`,
												`fraccion`,
												`gana`,
												`iva`,
												`tipo_dcto`,
												`color`,
												`talla`,
												`presentacion`,
												`fecha_vencimiento`,
												`unidades_frac`,
												`pvp_may`,
												`pvp_credito`,
												aplica_vehi,
												cod_color,
												vigencia_inicial,
												grupo_destino,
												impuesto_consumo,
												impuesto_saludable) 
									VALUES     ('$ref',  
												$codSuc,  
												'$cod_bar',  
												'0',  
												'0',  
												'0',  
												'$costo',  
												'$pvp',  
												'$frac',
												'$uti',
												'$iva',
												'',
												'$color',
												'$talla',
												'$presentacion',
												'$fechaVenci',
												'0',
												'$pvpMay',
												'$pvpCre',
												'$aplica_vehi',
												'$cod_color',
												'$vigencia_inicial',
												'$grupo_destino',
												'$impConsumo',
												'$impSaludable');";
		}


	////////////////////////////////// exe queries /////////////////////////////////
	$S= explode(';', trim($INSERTS));
	foreach($S as $key => $val){
		//echo "<li>1 >>$val</li>";
		if(!empty($val)){$linkPDO->exec("SAVEPOINT LoopInserts".$key);$linkPDO->exec($val);}
	}
	$INSERTS="";

	$S= explode(';', trim($UPDATES));
	foreach($S as $key => $val){
		//echo "<li>1 >>$val</li>";
		if(!empty($val)){$linkPDO->exec("SAVEPOINT LoopUpdates".$key);$linkPDO->exec($val);}
	}
	$UPDATES="";
	// -------------------------------------------------------------------------------

	$costoUpdate= $costo*$per_flete*$perImpConsumo - $costo*($dcto/100);
	if($FLUJO_INV==1 && $tipoFac!="Corte Inventario" && $tipoFac!="Ajuste Seccion" ){
		$SetCosto=" ";
	if($promediar_costos==0){$SetCosto="costo='$costoUpdate' ,";}


	$datetime1 = date_create($FechaFactura);
	$FechaHoy = date_create(gmdate("Y-m-d",hora_local(-5)));
	$interval = date_diff($datetime1, $FechaHoy);
	$diffDate=$interval->format('%a');
	//if($diffDate>1)echo $interval->format('%a')."<br>";

	$linkPDO->exec("SAVEPOINT Final");


	$mainFields = "     color='$color',
						talla='$talla',
						ubicacion='$ubica', 
						cod_color='$cod_color', 
						vigencia_inicial='$vigencia_inicial', 
						grupo_destino='$grupo_destino',
						exist=(exist+$cant),
						unidades_frac=(unidades_frac+$uni)";

	if($diffDate<45){
	//echo "<li>Cambia Costos!</li>";
	// actualiza precios por REF
	$sqlPrecios="UPDATE `inv_inter`  SET precio_v='$pvp', 
	                              dcto2='$dcto2',
								  gana='$uti',
								  fraccion='$frac', 
								  iva='$iva',
								  pvp_credito='$pvpCre',
								  pvp_may='$pvpMay', 
								  impuesto_consumo='$impConsumo'  
								  WHERE nit_scs='$codSuc' AND id_pro='$ref'";
	$linkPDO->exec($sqlPrecios);

	$sql="UPDATE `inv_inter`  SET $SetCosto 
								precio_v='$pvp', 
								campo_add_01='$campo_add_01', 
								campo_add_02='$campo_add_02', 
								dcto2='$dcto2',
								gana='$uti', 
								iva='$iva',
								pvp_credito='$pvpCre',
								pvp_may='$pvpMay', 
								impuesto_consumo='$impConsumo',
								impuesto_saludable='$impSaludable',
								$mainFields
								WHERE nit_scs='$codSuc' AND fecha_vencimiento='$fechaVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";

	}
	else {
	$sql="UPDATE `inv_inter`  SET $mainFields
								WHERE nit_scs='$codSuc' AND fecha_vencimiento='$fechaVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
	}
	$linkPDO->exec($sql);



	}


			}// FIN CAMPOS VACIOS
	} //////////////// FIN WHILE






	}// FIN IF CORTE INVENTARIO

	else{
		$APPLY="all";
		if($tipoFac=="Ajuste Seccion"){$APPLY="few";}


	$all_query_ok=ajustar_inv_corte($nit_pro,$num_fac,$codSuc,$APPLY,$all_query_ok,$linkPDO);

	//$all_query_ok=multi_consulta($AJUSTES_CORTE_INV[0],$all_query_ok);
	//$all_query_ok=multi_consulta($AJUSTES_CORTE_INV[1],$all_query_ok);

	set_time_limit(90);

	$linkPDO->exec("SAVEPOINT final2");
	$sql="UPDATE `inv_inter` i 
		INNER JOIN (select sum(cant) cant,
									iva,
									ref,
									cod_su,
									uti,
									costo,
									cod_barras,
									talla,
									color,
									fecha_vencimiento,
									unidades_fraccion,
									pvp,
									ubicacion,
									pvp_cre,
									pvp_may,
									aplica_vehi,
									impuesto_saludable
					FROM art_fac_com ar 
					WHERE ar.cod_su=$codSuc AND ar.num_fac_com='$num_fac' AND nit_pro='$nit_pro' 
					group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
					ON i.id_inter=a.cod_barras SET i.precio_v=a.pvp,
												i.color=a.color,
												i.talla=a.talla,
												i.dcto2='$dcto2',
												i.gana=a.uti,
												i.ubicacion=a.ubicacion,
												i.iva=a.iva,
												i.impuesto_saludable=a.impuesto_saludable,
												i.costo=a.costo,
												i.pvp_credito=a.pvp_cre,
												i.pvp_may=a.pvp_may 
					WHERE i.nit_scs=a.cod_su AND i.nit_scs=$codSuc AND i.id_pro=a.ref";
	$linkPDO->exec($sql);

	$sql="UPDATE `".tabProductos."` i 
		INNER JOIN (select sum(cant) cant,
							iva,
							ref,
							cod_su,
							uti,
							costo,
							des,
							clase,
							presentacion,
							fabricante,
							cod_barras,
							talla,
							color,
							fecha_vencimiento,
							unidades_fraccion,
							fraccion,
							pvp,
							ubicacion,
							pvp_cre,
							pvp_may 
					FROM art_fac_com ar 
					WHERE ar.cod_su=$codSuc AND ar.num_fac_com='$num_fac' AND nit_pro='$nit_pro' 
					group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
					ON i.id_pro=a.ref SET i.detalle=a.des,
										i.id_clase=a.clase,
										i.fab=a.fabricante,
										i.presentacion=a.presentacion ";
	$linkPDO->exec($sql);
	}





	$sql="UPDATE fac_com SET estado='CERRADA' WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc";
	$linkPDO->exec($sql);

	if($tipoFac=="Importar BD"){
		$sql="DELETE FROM art_fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc";
		$linkPDO->exec($sql);

		$sql="DELETE FROM fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc";
		$linkPDO->exec($sql);

		}

	$sql="DELETE FROM ".tabProductos." WHERE id_pro NOT IN(SELECT id_pro FROM inv_inter)";
	//$linkPDO->exec($sql);

	$linkPDO->commit();
	$rs=null;
	$stmt=null;
	$linkPDO= null;
	echo "1";
	}//fin estado!=CERRADA


	}catch (Exception $e) {
	$linkPDO->rollBack();
	echo "Failed: " . $e->getMessage();
	}

};
function ajustar_inv_corte($nit_pro,$num_fac,$codSuc,$apply,$all_query_ok,$linkPDO)
{
	global $nomUsu,$id_Usu,$hoy,$conex;
	global $linkPDO;
	$SQL_AJUSTE="";
	$SQL_UPDATES="";
	$fecha=$hoy;
	$nom=limpiarcampo($nomUsu);
	$cc=limpiarcampo($id_Usu);

	$num_ajus=serial_ajustes($conex);

	$SQL_AJUSTE="INSERT INTO ajustes(num_ajuste,fecha,cod_su,nom_usu,id_usu) VALUES($num_ajus,'$fecha',$codSuc,'$nom','$cc');";
	$linkPDO->exec($SQL_AJUSTE);



	//$sqlLog="<ul><li>$Insert1</li>";
	//echo "$sqlLog";



	$SQL="SELECT * FROM art_fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc FOR UPDATE";
//echo "$SQL---->";

	$SQL_AJUSTE="";
	$SQL_UPDATES="";
$stmt = $linkPDO->query($SQL);

$linkPDO->exec("SAVEPOINT ajusteInv1");
$i=0;
while($row = $stmt->fetch()) {



		set_time_limit(90);
	    $cant=$row['cant'];
		$ref=limpiarcampo($row['ref']);
		$cod_bar=limpiarcampo($row['cod_barras']);
		$presentacion=limpiarcampo($row['presentacion']);
		$des=limpiarcampo($row['des']);

		$color=limpiarcampo($row['color']);
		$talla=limpiarcampo($row['talla']);
		$clase=limpiarcampo($row['clase']);
		$fabricante=limpiarcampo($row['fabricante']);

		$ubica=limpiarcampo($row["ubicacion"]);

		$costo=limpiarcampo($row['costo']);
		$dcto=$row['dcto'];
		$iva=$row['iva'];
		$util=$row['uti'];
		$fechaVenci=$row['fecha_vencimiento'];
		if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
		$pvp=$row['pvp'];
		$frac=$row['fraccion'];
		$uni=$row['unidades_fraccion'];

		$SQL_AJUSTE.="INSERT IGNORE INTO ".tabProductos." (id_pro,detalle,id_clase,frac,fab,presentacion,nit_proveedor) 
		              VALUES ('$ref','$des','$clase','$frac','$fabricante','$presentacion','$nit_pro');";

		$SQL_AJUSTE.="UPDATE ".tabProductos." SET detalle='$des', 
		                                          id_clase='$clase',
												  fab='$fabricante',
												  presentacion='$presentacion',
												  nit_proveedor='$nit_pro' 
					  WHERE id_pro='$ref';";


		if(!empty($color)){$SQL_UPDATES.="INSERT IGNORE INTO colores(color) VALUES('$color');";}
		if(!empty($talla)){$SQL_UPDATES.="INSERT IGNORE INTO tallas(talla) VALUES('$talla');";}
		if(!empty($clase)){$SQL_UPDATES.="INSERT IGNORE INTO clases(des_clas) VALUES('$clase');"; }
		if(!empty($fab)){$SQL_UPDATES.="INSERT IGNORE INTO fabricantes(fabricante) VALUES('$fab');"; }
		if(!empty($presentacion)){$SQL_UPDATES.="INSERT IGNORE INTO presentacion(presentacion) VALUES('$presentacion');";}

		$SQL_UPDATES.="INSERT IGNORE INTO  `inv_inter` (`id_pro` ,
		                                                `nit_scs` ,
														`id_inter` ,
														`exist` ,
														`max` ,
														`min` ,
														`costo` ,
														`precio_v` ,
														`fraccion`,
														`gana`,
														`iva`,
														`tipo_dcto`,
														`color`,
														`talla`,
														`presentacion`,
														`fecha_vencimiento`,
														`unidades_frac`) 
											   VALUES ('$ref',  
											            $codSuc,  
													   '$cod_bar',  
													   '0',  
													   '0',  
													   '0',  
													   '$costo',  
													   '$pvp',  
													   '$frac',
													   '$util',
													   '$iva',
													   '',
													   '$color',
													   '$talla',
													   '$presentacion',
													   '$fechaVenci',
													   '0');";




		$sql="SELECT exist,
		             unidades_frac,
					 nit_scs,
					 id_pro,
					 id_inter 
			  FROM inv_inter 
			  WHERE id_inter='$cod_bar' AND id_pro='$ref' AND fecha_vencimiento='$fechaVenci' AND nit_scs=$codSuc";
		$rs1=$linkPDO->query($sql);
		$row1=$rs1->fetch();
		$exist=$row1["exist"];
		$uniExist=$row1["unidades_frac"];

		$ajusteCant=($cant==$exist)?0:$cant-$exist;
		$ajusteUni=($uni==$uniExist)?0:$uni-$uniExist;

		$cant_saldo=$cant;
		$uniSaldo=$uni;

		$motivo="Corte Inventario # $num_fac $hoy";

$SQL_AJUSTE.="INSERT INTO art_ajuste(num_ajuste,
                                     ref,
									 des,
									 cant,
									 costo,
									 precio,
									 util,
									 iva,
									 cod_su,
									 motivo,
									 cant_saldo,
									 cod_barras,
									 presentacion,
									 fraccion,
									 unidades_fraccion,
									 unidades_saldo,
									 fecha_vencimiento)
            VALUES($num_ajus,
			       '$ref',
				   '$des',
				    $ajusteCant,
				    $costo,
				    $pvp,
				    $util,
				    $iva,
				    $codSuc,
				   '$motivo',
				   '$cant_saldo',
				   '$cod_bar',
				   '$presentacion',
				   '$frac',
				   '$ajusteUni',
				   '$uniSaldo',
				   '$fechaVenci');";



////////////////////////////////// exe queries /////////////////////////////////
$S= explode(';', trim($SQL_AJUSTE));
$i=0;
foreach($S as $key => $val){
	//echo "<li>1 >>$val</li>";
		$i++;
		$linkPDO->exec("SAVEPOINT ajusteInv1LoopA".$i);
	if(!empty($val)){$linkPDO->exec($val);}
}
$SQL_AJUSTE="";

$S= explode(';', trim($SQL_UPDATES));
$i=0;
foreach($S as $key => $val){
	//echo "<li>1 >>$val</li>";
	$i++;
		$linkPDO->exec("SAVEPOINT ajusteInv1LoopB".$i);
	if(!empty($val)){$linkPDO->exec($val);}
}
$SQL_UPDATES="";
// -------------------------------------------------------------------------------
}


if($apply=="all"){
//////////////////////// SET INV TO CERO///////////
$sq="SELECT nit_scs,
            fraccion,
			gana,
			presentacion,
			exist,
			id_pro,
			id_inter,
			a.fecha_vencimiento,
			unidades_frac,
			costo,
			precio_v,
			detalle,iva
     FROM (SELECT fraccion,
	              nit_scs,
				  gana,
				  a.presentacion,
				  exist,
				  a.id_pro,
				  id_inter,
				  fecha_vencimiento,
				  unidades_frac,
				  costo,precio_v,
				  detalle,
				  iva 
	       FROM `inv_inter` a INNER JOIN ".tabProductos." b 
		   ON a.id_pro=b.id_pro) a
LEFT JOIN
(SELECT ref,cod_barras,fecha_vencimiento,cod_su FROM art_fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc) b

ON (a.id_pro=b.ref) AND (a.id_inter=b.cod_barras) AND (a.fecha_vencimiento=b.fecha_vencimiento) AND (a.nit_scs=b.cod_su)
WHERE b.ref IS NULL AND b.cod_barras IS NULL AND b.fecha_vencimiento IS NULL AND a.nit_scs=$codSuc FOR UPDATE";
//echo "<br>$sq";
$rs=$linkPDO->query($sq);
$i=0;
while($row=$rs->fetch())
{
		$i++;
		$linkPDO->exec("SAVEPOINT ajusteInv1LoopC".$i);

		$cant=$row['exist'];
		$ref=limpiarcampo($row['id_pro']);
		$cod_bar=limpiarcampo($row['id_inter']);
		$presentacion=limpiarcampo($row['presentacion']);
		$des=limpiarcampo($row['detalle']);
		$costo=limpiarcampo($row['costo']);
		$iva=$row['iva'];
		$util=$row['gana'];
		$fechaVenci=$row['fecha_vencimiento'];
		if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
		$pvp=$row['precio_v'];
		$frac=$row['fraccion'];
		$uni=$row['unidades_frac'];
		$exist=$row["exist"];
		$uniExist=$row["unidades_frac"];

		$ajusteCant=(-1)*($exist);
		$ajusteUni=(-1)*($uniExist);

		$cant_saldo=0;
		$uniSaldo=0;

		$motivo="Corte Inventario # $num_fac, Ajuste a Cero (0)  $hoy";

$SQL_AJUSTE="INSERT INTO art_ajuste(num_ajuste,ref,des,cant,costo,precio,util,iva,cod_su,motivo,cant_saldo,cod_barras,presentacion,fraccion,unidades_fraccion,unidades_saldo,fecha_vencimiento)
VALUES($num_ajus,'$ref','$des',$ajusteCant,'$costo','$pvp','$util','$iva',$codSuc,'$motivo','$cant_saldo','$cod_bar','$presentacion','$frac','$ajusteUni','$uniSaldo','$fechaVenci');";


$linkPDO->exec($SQL_AJUSTE);
$SQL_AJUSTE="";
$SQL_UPDATES="";
}/////////// FIN WHILE SET TO CERO

} /////// FIN IF APPLY
$SQL_AJUSTE="
UPDATE `inv_inter` i
INNER JOIN
( select ar.cod_su nitAr,sum(cant) cant,ref,cod_barras,unidades_fraccion,fecha_vencimiento 
  from art_ajuste ar 
  inner join (select * from ajustes f WHERE num_ajuste=$num_ajus and cod_su='$codSuc' ) fv 
  ON fv.num_ajuste=ar.num_ajuste 
  WHERE fv.cod_su=ar.cod_su and fv.cod_su=$codSuc group by ar.ref,ar.fecha_vencimiento,ar.cod_barras) a
ON i.id_inter=a.cod_barras
SET i.exist=(i.exist+a.cant),i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref;";

$linkPDO->exec($SQL_AJUSTE);



$RESP[0]=$SQL_AJUSTE;
$RESP[1]=$SQL_UPDATES;

return $all_query_ok;
};
function ajustar_precios_compras($fechaI,$fechaF){
	global $codSuc;
	global $linkPDO;
$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";

if(!empty($A)){
$columns="feVen,num_fac_com,nit_pro,nom_pro,fecha,tel,dir,ciudad,flete,cod_su,(tot-(r_fte+r_ica+r_iva+dcto2)) as tot,DATE(fecha_crea) as fecha_crea,fecha_mod,tipo_fac,serial_fac_com,estado,pago,fecha_pago,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(feVen),CURRENT_DATE() ) AS mora2, DATEDIFF(CURRENT_DATE(),DATE(fecha)) AS limAnula";

$sql = "SELECT  $columns FROM fac_com  WHERE  cod_su=$codSuc AND tipo_fac!='Traslado' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion'  $A ORDER BY serial_fac_com ASC ";
//echo "$sql";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$num_fac = $row["num_fac_com"] ;
	$nit_pro =$row["nit_pro"];



	anula_compra($num_fac,$nit_pro,$codSuc);
	echo "<li>Fac. $num_fac,$nit_pro ANULADA</li>";

	cerrar_fc($num_fac,$nit_pro,$codSuc);
	echo "<li>Fac. $num_fac,$nit_pro CERRADA</li>";
}

}// fin !empty
};


function serial_fac_com($conex)
{
	global $linkPDO;
	$nit=$_SESSION['cod_su'];
	$inf=0;
	$sup=0;
	$serial=0;
	$secc="factura compra";
	$tabla="fac_com";
	$serial_col="serial_fac_com";
	$codSU_col="cod_su";
	$rs=$linkPDO->query("SELECT * FROM seriales WHERE seccion='$secc' AND nit_sede='$nit' FOR UPDATE");
	//echo "SELECT * FROM seiales WHERE seccion='factura venta' AND nit_sede='$nit'";
	$nf_seri=$rs->rowCount();;
	if($row=$rs->fetch())
	{
		$inf=$row['serial_inf'];
		$sup=$row['serial_sup'];
		//echo $inf;
		$rs2=$linkPDO->query("SELECT $serial_col AS us FROM $tabla WHERE $codSU_col=$nit order by $serial_col desc FOR UPDATE");
		//echo mysqli_num_rows($rs2);
		if($row=$rs2->fetch()){
			//echo "entra query us:";
			if($row['us']<$sup)return ($row['us']+1);
			else if($row['us']=$sup)return "LIMITE DE FACTURAS ALCANZADO";
			}
		else return $inf;

		}
	else return "NO HAY RANGO ESTABLECIDO";

};

function tipoCompraFilter($requestName,$sessionName,$tableColName,$opc)
{
	$var="";
	$E="";
	//if(!isset($sessionVar))$_SESSION[$sessionName]=$var;
	if(isset($_REQUEST[$requestName]) && !empty($_REQUEST[$requestName]))
	{$var=$_REQUEST[$requestName];$_SESSION[$sessionName]=$var;}

	if(isset($_SESSION[$sessionName]) && !empty($_SESSION[$sessionName]))
	{$var=$_SESSION[$sessionName];}

	if(!empty($var))
	{
	if($var==1)$E=" AND tipo_fac='Compra'";
	else if($var==2)$E=" AND tipo_fac='Remision'";
	else if($var==3)$E=" AND tipo_fac='Traslado'";
	else if($var==4)$E=" AND tipo_fac='Inventario Inicial'";
	else if($var==5)$E=" AND tipo_fac='Corte Inventario'";
	else if($var==6)$E=" AND tipo_fac='Ajuste Seccion'";
	}


	if($opc=="quitarFiltros")
	{
	$E="";
	$_SESSION[$sessionName]="";;
	}

	return $E;
};
function tot_compras($fechaI,$fechaF,$codSuc)
{
	global $linkPDO;
$SUM_TOT[]=0;
$SUM_TOTS[]=0;
$resp[]=0;

$TOT_OTROS=0;
$TOT_OTROSS=0;
$IVA_OTROS=0;
$BRUTO_OTROS=0;

$sql="SELECT nom_pro,nit_pro,SUM(iva) IVA,SUM(tot) TOT,SUM(descuento) AS dcto  FROM `fac_com` WHERE cod_su=$codSuc AND fecha>='$fechaI' AND fecha<='$fechaF' GROUP BY nom_pro ORDER BY 1 ASC";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){

$proveedor=$row['nom_pro'];
$nit=$row['nit_pro'];
$bruto=$row['TOT']-$row['IVA'];
$iva=$row['IVA'];
$tot=$row['TOT'];

$SUM_TOT[$nit]=$tot;
$SUM_TOTS[$nit]=$row['dcto']+$tot;


	$TOT_OTROS+=$tot;
	$TOT_OTROSS+=$tot+$row['dcto'];
	$IVA_OTROS+=$iva;
	$BRUTO_OTROS+=$bruto;


}///////////// FIN WHILE TOTALES/////////
$resp[0]=$BRUTO_OTROS;
$resp[1]=$IVA_OTROS;
$resp[2]=$TOT_OTROS;
return $resp;
};
function tot_compras_0516a($fechaI,$fechaF,$codSuc,$iva_per)
{
	global $linkPDO;
$resp[][]=0;
$MainCondition=" AND b.tipo_fac='Compra' ";
$cols=calc_cols_coma("per");
$BASE_R=0;
$DCTO_R=0;
$FLETE_R=0;
$IVA_R=0;
$TOT_R=0;
$sql="SELECT $cols FROM (select * FROM art_fac_com WHERE iva=$iva_per) a INNER JOIN fac_com b ON a.num_fac_com=b.num_fac_com WHERE b.estado='CERRADA' AND a.nit_pro=b.nit_pro AND a.cod_su=b.cod_su AND a.cod_su='$codSuc'   $MainCondition AND (b.fecha>='$fechaI' AND b.fecha<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
$BASE_R+=$row['SUB'];
$DCTO_R+=$row['DCTO'];
$FLETE_R+=$row['FLETE'];
$IVA_R+=$row['IVA'];
$TOT_R+=$row['TOT'];
}

$resp["BASE"]=$BASE_R;
$resp["DCTO"]=$DCTO_R;
$resp["FLETE"]=$FLETE_R;
$resp["IVA"]=redondeo($BASE_R*($iva_per/100));
$resp["TOT"]=$TOT_R;
return $resp;
};
function calc_cols_com($calc_dcto,$iva_per)
{
if($calc_dcto=="valor"){$descuento="a.dcto";}
else {$descuento="a.costo*(a.dcto/100)";}
$unidades="(a.unidades_fraccion+(a.cant*a.fraccion))/a.fraccion";
$SUB="ROUND(SUM(a.costo*($unidades) -($unidades*$descuento) )) as SUB";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) as DCTO";
$IVA="ROUND(SUM( ($unidades  )*(a.costo - ($descuento))*(a.iva/100))) as IVA";
$stot="(a.costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="b.flete*0.19";
$iva="( ($unidades  )*(a.costo - ($descuento))*(a.iva/100) )";
$impConsumo="( ($unidades  )*(a.costo - ($descuento))*(a.impuesto_consumo/100) ) as consumo";
$TOT="ROUND(SUM(  $stot + $iva - $dcto) ) as TOT";
$TOT_FLETE="SUM(b.flete) as FLETE";
$cols="$SUB,$DCTO,$TOT_FLETE,$IVA,$impConsumo,$TOT";
return $cols;
};
function tot_compras_0516($fechaI,$fechaF,$codSuc,$iva_per)
{
	global $linkPDO;
$resp[][]=0;
$MainCondition=" AND b.tipo_fac='Compra' ";
$cols=calc_cols_com("per",$iva_per);
$BASE_R=0;
$DCTO_R=0;
$FLETE_R=0;
$IVA_R=0;
$TOT_R=0;
$sql="SELECT $cols FROM (select * FROM art_fac_com WHERE iva=$iva_per) a INNER JOIN fac_com b ON a.num_fac_com=b.num_fac_com WHERE b.estado='CERRADA' AND a.nit_pro=b.nit_pro AND a.cod_su=b.cod_su AND a.cod_su='$codSuc'   $MainCondition AND (b.fecha>='$fechaI' AND b.fecha<='$fechaF')";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
$BASE_R+=$row['SUB'];
$DCTO_R+=$row['DCTO'];
$CONSUMO_R=$row["consumo"];
$IVA_R+=$row['IVA'];
$TOT_R+=$row['TOT'];
}
$sql="SELECT SUM(flete) FLETE FROM  fac_com  WHERE estado='CERRADA' AND cod_su='$codSuc'   AND tipo_fac='Compra' AND (fecha>='$fechaI' AND fecha<='$fechaF')";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$FLETE_R=$row['FLETE'];
}
if($iva_per==19){
$baseFlete=$FLETE_R;
$ivaFlete=round($FLETE_R*0.19,0);

$resp["BASE"]=round($BASE_R+$baseFlete,0) ;
//echo "B $BASE_R, F: $baseFlete ";
$resp["DCTO"]=round($DCTO_R||0,0);
$resp["FLETE"]=round($FLETE_R||0,0);
$resp["IVA"]=round($IVA_R+$ivaFlete,0);
$resp["CONSUMO"]=round($CONSUMO_R||0,0);
$resp["TOT"]=round($TOT_R+$FLETE_R+$ivaFlete,0);

	}else{
$resp["BASE"]=round($BASE_R||0,0);
$resp["DCTO"]=round($DCTO_R||0,0);
$resp["FLETE"]=round($FLETE_R||0,0);
$resp["IVA"]=round($IVA_R||0,0);
$resp["CONSUMO"]=round($CONSUMO_R||0,0);
$resp["TOT"]=round($TOT_R||0,0);
	}
return $resp;
};

function chk_abono_compra($num_comp,$num_fac,$nitPro,$abono,$tot_fac,$query,$fecha)
{
	global $linkPDO;
	$concepto="";
	$T=$tot_fac;$Ab=0;$S=0;
	$resp=$abono;
	//$sql="SELECT SUM(abono) as abono FROM cartera_mult_pago WHERE num_fac='$num_fac' AND pre='$pre' AND id_cli='$idCli' AND estado!='ANULADO'";
	$sql="SELECT IFNULL(SUM(abono),0) AS t FROM compras_mult_pago a INNER JOIN fac_com b ON a.num_fac=b.num_fac_com WHERE   (a.num_fac='$num_fac') AND a.id_cli='$idCli' AND a.estado!='ANULADO' AND b.anulado!='ANULADO' ";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{$Ab=$row["t"];}
	$S=$T-$Ab;
	//echo "<li>S($S)=T($T)-AB($Ab) ABONO: $abono</li>";
		if($S>0){
			//echo "<br>S>0";
			if($abono>$S){
				$resp=$abono-$S;
				//echo "R:$resp = Ab:$abono- S:$S";
			$sql="INSERT INTO cartera_mult_pago(num_comp,num_fac,pre,id_cli,abono) VALUES('$num_comp','$num_fac','$pre','$idCli','$S')";
			$linkPDO->exec($sql);

			$sql="UPDATE fac_venta SET estado='PAGADO',fecha_pago='$fecha' WHERE num_fac_ven=$num_fac AND prefijo='$pre'";
			$linkPDO->exec($sql);


			$concepto.=" ,Factura $pre-$num_fac PAGADA";
			}
			else {
$sql="INSERT INTO cartera_mult_pago(num_comp,num_fac,pre,id_cli,abono) VALUES('$num_comp','$num_fac','$pre','$idCli','$abono')";
$linkPDO->exec($sql);

if($abono==$S){
				$sql="UPDATE fac_venta SET estado='PAGADO',fecha_pago='$fecha' WHERE num_fac_ven=$num_fac AND prefijo='$pre'";
				$linkPDO->exec($sql);

				}
			$concepto.=" ,ABONO ".money3($abono)." Factura $pre-$num_fac";
			$resp=0;
			}
			}
if(!empty($concepto)){
$sql="UPDATE comprobante_ingreso SET concepto=CONCAT(concepto,' $concepto') WHERE num_com='$num_comp'";
$linkPDO->exec($sql);
}
//echo "<li>Abono: $abono R:$resp, $concepto</li>";

return $all_query_ok;

};

function fix_cuentas_pagar( $idFac,$codSuc,$saldo,$estado,$fechaPago)
{
	global $conex,$conex;
	global $linkPDO;

	try {
$linkPDO->beginTransaction();
$all_query_ok=true;



	if($saldo<=0 && ($estado!='PAGADO' || $fechaPago=="0000-00-00 00:00:00")){
	$s="SELECT MAX(fecha) fp FROM comp_egreso  WHERE serial_fac_com='$idFac' AND cod_su='$codSuc' FOR UPDATE";
	//echo "<li>$s</li>";
	$rs=$linkPDO->query($s);
	$row=$rs->fetch();
	$fechaPago=$row["fp"];
	$sql="UPDATE fac_com SET pago='PAGADO',fecha_pago='$fechaPago' WHERE serial_fac_com='$idFac' AND cod_su='$codSuc'";
	//echo "$sql";
	$linkPDO->exec($sql);


	}
	else if($saldo>0 && $estado=="PAGADO")
	{
	$sql="UPDATE fac_com SET pago='PENDIENTE',fecha_pago='0000-00-00 00:00:00' WHERE serial_fac_com='$idFac' AND cod_su='$codSuc'";
	//echo "$sql";
	$linkPDO->exec($sql);
	}

$linkPDO->commit();
if($all_query_ok){}
else{eco_alert("ERROR! Intente nuevamente");}

	}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function anula_dev_compra($serial,$codSuc)
{
$tableMain="fac_dev";
$tableArt="art_fac_dev";
$tipoOp="+";

global $nomUsu,$id_Usu,$FLUJO_INV,$conex,$SECCIONES,$OPERACIONES,$fecha_lim_anulaVenta;
global $linkPDO;
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";
$sql="SELECT * FROM $tableMain WHERE serial_fac_dev='$serial' AND cod_su='$codSuc' FOR UPDATE";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){

	        $sql="SELECT * FROM $tableMain WHERE serial_fac_dev='$serial' AND anulado!='ANULADO'  AND cod_su=$codSuc FOR UPDATE";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){

			$sql="SELECT * FROM $tableMain WHERE serial_fac_dev='$serial' AND cod_su=$codSuc FOR UPDATE";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){

			if( 1){


			//$NUM_EXP=$row['num_exp'];
			$qryA="UPDATE $tableMain SET anulado='ANULADO', fecha_anula='$hoy' WHERE serial_fac_dev='$serial' AND cod_su=$codSuc";
			//echo $qry."<br>";

if($FLUJO_INV==1 ){
$qryB="UPDATE `inv_inter` i
INNER JOIN
(select ar.cod_su nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from $tableArt ar inner join (select * from $tableMain f WHERE serial_fac_dev='$serial' ) fv ON fv.serial_fac_dev=ar.serial_dev  group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a
ON i.id_inter=a.cod_barras
SET i.exist=(i.exist $tipoOp a.cant), i.unidades_frac=(i.unidades_frac $tipoOp a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
t2($qryA,$qryB);
}
else{t1($qryA);
}

			}
			else {echo "-445";}

			}// fin if cartera
			else {echo "-1";}

			}
			else {echo "0";}

}
else {echo "-2";}


	};
function saldo_compra($serialFac,$codSuc){
global $linkPDO;
$sql="SELECT *,(tot - dcto2) as TOT FROM fac_com WHERE serial_fac_com='$serialFac' AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$nomPro=$row['nom_pro'];
$nitPro=$row['nit_pro'];
$numFacCom=$row['num_fac_com'];
$tot=$row['TOT'];
$ICA=$row["r_ica"];
$FTE=$row["r_fte"];

$sql="SELECT IFNULL(SUM(valor-(r_fte+r_iva+r_ica)), 0) as abon FROM comp_egreso WHERE serial_fac_com=$serialFac AND anulado!='ANULADO' AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$abon=$row['abon'];

$sql="SELECT IFNULL(SUM(tot),0) as dev FROM fac_dev WHERE nit_pro='$nitPro' AND num_fac_com='$numFacCom' AND anulado!='ANULADO' AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$dev=$row['dev'];



$saldo=$tot-$abon-$dev-$FTE-$ICA;

//echo " $saldo=$tot-$abon-$dev-$FTE-$ICA;";
$concept="Pago Factura de Compra No. $numFacCom ";
//					  0       1		  2		3	  4 	  5    6
return $saldo;
}
else {return 0;}


};
function saldo_compra_postPago($serialFac,$codSuc){
global $linkPDO;
$sql="SELECT *,(tot) as TOT FROM fac_com WHERE serial_fac_com='$serialFac' AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$nomPro=$row['nom_pro'];
$nitPro=$row['nit_pro'];
$numFacCom=$row['num_fac_com'];
$tot=$row['TOT'];
$ICA=$row["r_ica"];
$FTE=$row["r_fte"];

$sql="SELECT IFNULL(SUM(valor-(r_fte+r_iva+r_ica)), 0) as abon FROM comp_egreso WHERE serial_fac_com=$serialFac AND anulado!='ANULADO' AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$abon=$row['abon'];

$sql="SELECT IFNULL(SUM(tot),0) as dev FROM fac_dev WHERE nit_pro='$nitPro' AND num_fac_com='$numFacCom' AND anulado!='ANULADO' AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$dev=$row['dev'];



$saldo=$tot-$abon-$dev-$FTE-$ICA;
if($saldo<0){$saldo=0;}
//echo " $saldo=$tot-$abon-$dev-$FTE-$ICA;";
$concept="Pago Factura de Compra No. $numFacCom ";
//					  0       1		  2		3	  4 	  5    6
return $saldo;
}
else {return 0;}


};


function anula_compra($num_fac,$nit_pro,$codSuc){

	global $FLUJO_INV,$karDex,$conex,$fecha_lim_anulaCompra, $linkPDO;
	date_default_timezone_set('America/Bogota');
	$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
	$qry="";

	$RESP="";

	try {
	$linkPDO->beginTransaction();
	$all_query_ok=true;


	$sql="SELECT * FROM fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su='$codSuc' FOR UPDATE";
	//echo $sql."<br>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){

				$estado=$row['estado'];
				if($estado!="ANULADA"&&$estado!="ABIERTA"){

				$sql="SELECT * FROM fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc  $fecha_lim_anulaCompra FOR UPDATE";
				//echo $sql."<br>";
				$rs=$linkPDO->query($sql);
				if($row=$rs->fetch()){
					$karDex=$row['kardex'];
					$tipoFac=$row["tipo_fac"];
					$dcto2=$row['dcto2'];
					$per_flete=($row["perflete"]/100)+1;
				//$NUM_EXP=$row['num_exp'];
				$sql="UPDATE fac_com SET estado='ANULADA' WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc";
				$linkPDO->exec($sql);
				$linkPDO->exec("SAVEPOINT A");

	if($FLUJO_INV==1 && $karDex==1){

	$SQL="SELECT * FROM art_fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc FOR UPDATE";
	//echo "$SQL---->";
	$rs=$linkPDO->query($SQL);
	$color="";
	$talla="";
	$i=0;
	if($tipoFac!="Corte Inventario" && $tipoFac!="Ajuste Seccion"){
	while($row=$rs->fetch())
	{
			$i++;
			$linkPDO->exec("SAVEPOINT loop".$i);
			set_time_limit(90);
			$cant=$row['cant'];
			$ref=limpiarcampo($row['ref']);
			$cod_bar=limpiarcampo($row['cod_barras']);
			$presentacion=limpiarcampo($row['presentacion']);
			$fab=limpiarcampo($row['fabricante']);
			$des=limpiarcampo($row['des']);
			$des_full=limpiarcampo($row['des_full']);
			$color=limpiarcampo($row['color']);
			$talla=limpiarcampo($row['talla']);
			$clase=limpiarcampo($row['clase']);
			$fabricante=limpiarcampo($row['fabricante']);
			$costo=limpiarcampo($row['costo']);
			$dcto=$row['dcto'];
			$iva=$row['iva'];
			$uti=$row['uti'];
			//$uti=util($pvp,$costoDesc,$iva,"per");
			$fechaVenci=$row['fecha_vencimiento'];
			if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
			$pvp=$row['pvp'];
			$s_tot=$row['tot'];
			$frac=$row['fraccion'];
			if($frac==0)$frac=1;
			$uni=$row['unidades_fraccion'];
			$ubica=$row["ubicacion"];

			if(!empty($des) && !empty($ref) && !empty($cod_bar))
			{
				$costoUpdate= $costo*$per_flete - $costo*($dcto/100);
				if($FLUJO_INV==1 && $tipoFac!="Corte Inventario" && $tipoFac!="Ajuste Seccion" ){
					$sql="UPDATE `inv_inter`  SET exist=(exist-$cant), 
					                              unidades_frac=(unidades_frac-$uni)
						  WHERE nit_scs='$codSuc' AND fecha_vencimiento='$fechaVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
					$linkPDO->exec($sql);
				}

			}

	}// fin val vacios

	}



	}
	else
	{

	$linkPDO->exec($sql);


	}


				}
				else {$RESP="-1";}
				}

				else if($estado=="ABIERTA"){$RESP="-2";}
				else if($estado=="ANULADA"){$RESP="0";}

	}
	else {$RESP="-3";}

	$linkPDO->commit();
	$rs=null;
	$stmt=null;
	$linkPDO= null;



	return $RESP;
	}catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
	}

};


function list_comprasOpt()
{
	global $codSuc,$linkPDO;
	$selc="";
	$sql="SELECT * FROM fac_com WHERE tipo_fac='Compra' AND cod_su='$codSuc' AND pago!='PAGADO' ORDER BY fecha DESC";
	$rs=$linkPDO->query($sql);
	$selc.="<option value=\"0\"  >--BORRAR--</option>";
	while($row=$rs->fetch()){
		$VALS=$row["serial_fac_com"];
		$VALS2="$row[num_fac_com] $row[nom_pro]";
		$selc.="<option value=\"$VALS\"  >$VALS2</option>";
	}
	return minify_html($selc);
};
function add_art_compra($row){
	global $ref,$codBar,$fe;
	
			$id_pro=$row['ref'];
	        $cant = $row["exist"]*1;
            $des = $row["detalle"]; 
			$id = $row["ref"];
			$costo=$row['costo']*1;
			$pvp = $row["precio_v"]*1;
			$iva = $row["iva"];
			$gan=$row['gana']*1;
			$dcto2=$row['dcto2']*1;
			$color=$row['color'];
			
			$presentacion=$row['presentacion'];
			$frac=$row['fraccion'];
			$uni=$row['unidades_frac'];
			$fe=$row['fecha_vencimiento'];
			
			$talla=$row['talla'];
			$codBar=$row['id_inter'];
			$clase=$row['id_clase'];
			$fab=$row['fab'];
			$ubica=$row["ubicacion"];
			$pvpCre=$row["pvp_credito"];
			$pvpMay=$row["pvp_may"];
			$aplica_vehi=$row["aplica_vehi"];
			$campo_add_01=$row["campo_add_01"];
			$campo_add_02=$row["campo_add_02"];
			$cod_color=$row["cod_color"];
			$vigencia_inicial=$row["vigencia_inicial"];
			$grupo_destino=$row["grupo_destino"];
			$impuesto_saludable=$row["impuesto_saludable"];
			//if($cant>0)
			//echo htmlentities("$cant|$ref|$des|$costo|$iva|$gan|$pvp|$id_pro|$dcto2|$color|$talla|$codBar|$clase|$fab|$presentacion", ENT_QUOTES,"$CHAR_SET");
		$respuesta="$cant|$ref|$des|$costo|$iva|$gan|$pvp|$id_pro|$dcto2|$color|$talla|$codBar|$clase|$fab|$presentacion|$frac|$uni|$fe|0||$ubica|$pvpCre|$pvpMay|$aplica_vehi|$campo_add_01|$campo_add_02|$cod_color|$vigencia_inicial|$grupo_destino|$impuesto_saludable";
			//        0		1	 2		3	 4	  5	   6     7	  	 8	    9     10  	  11 	 12    13	    14			15   16  17	18   20      21     22 		   23
			echo $respuesta;
			//else echo "0";
	
	
	};
?>
