<?php
$reFacturarPOS = r('reFacturar');
$resol = r('resol');
$filtroRefacturarFE = $reFacturarPOS ? " AND b.TIPDOC != '7' AND idFacturaDian=0":"";

//18764066819187
$filtroResol = !empty($resol) ? " AND resolucion='$resol' ":'';

if($fuenteFac=="cotiza" || $reFacturarPOS){
	$FLUJO_INV=0;
}


// columnas para descargar cantidades de invientario
$colSet01="a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,i.pvp_credito,i.exist,i.unidades_frac,i.precio_v";

// sin usar
$colSet011="a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial";

// vender sin cantidades en inventario
$colSet02="a.costo,a.precio,a.sub_tot,a.dcto,a.des,a.cant,a.fraccion,a.unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,precio as pvp_credito,(cant+1000) as exist,fraccion as unidades_frac,precio as precio_v";

// filtro ID cliente activo
$colSet021="a.costo,SUM(a.sub_tot) as precio,SUM(a.sub_tot) as sub_tot,a.dcto,a.des,SUM(a.cant) as cant,a.fraccion,SUM(a.unidades_fraccion) as unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,precio as pvp_credito,(cant+1000) as exist,fraccion as unidades_frac,precio as precio_v";

// filtro reFacturarPOS
$colSetreFacturar="a.costo, precio,a.dcto,a.des,SUM(a.cant) as cant,a.fraccion,SUM(a.unidades_fraccion) as unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,precio as pvp_credito,(cant+1000) as exist,fraccion as unidades_frac,precio as precio_v";

// carga desde remision o cotizacion, manteniendo pvp y cant
$colSetRemiCoti="a.costo,SUM(a.sub_tot) as precio,SUM(a.sub_tot) as sub_tot,a.dcto,a.des,SUM(a.cant) as cant,a.fraccion,SUM(a.unidades_fraccion) as unidades_fraccion,a.color,a.talla,a.ref,a.cod_barras,a.fecha_vencimiento as feven,a.iva as IVA,a.presentacion as presen,a.serial,precio as pvp_credito,(cant+1000) as exist,fraccion as unidades_frac,precio as precio_v";



if($vender_sin_inv==0 && $FLUJO_INV==1 )
{
	if($reFacturarPOS && !empty($filtroFecha)){

		$sql="SELECT $colSetreFacturar FROM art_fac_ven a 
		INNER JOIN  fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit
		WHERE a.nit='$codSuc'  $filtroFecha AND b.".VALIDACION_VENTA_VALIDA." $filtroResol $filtroRefacturarFE
		GROUP BY cod_barras,ref,fecha_vencimiento
		ORDER BY orden_in ASC";
		//echo "$sql <br>";
		
    } else {
        // consulta para cargar productos de factura anulada, validando cantidades en inventario
		$sql="SELECT $colSet01 FROM  art_fac_ven a 
		LEFT JOIN inv_inter i ON i.id_inter=a.cod_barras 
		WHERE nit='$codSuc' AND (num_fac_ven='$nf' AND prefijo='$pre') AND nit_scs='$codSuc' 
		AND a.fecha_vencimiento=i.fecha_vencimiento AND a.ref=i.id_pro  
		ORDER BY orden_in ASC";

	//filtro cliente
	if(!empty($filtroB))
	{
	$sql="SELECT $colSetRemiCoti FROM art_fac_remi a 
		INNER JOIN  fac_remi b ON a.num_fac_ven=b.num_fac_ven 
		WHERE a.prefijo=b.prefijo AND a.nit=b.nit $filtro_remi_coti  $filtroFacturaSeleccionada $filtroB $filtroFecha 
		GROUP BY cod_barras,ref  
		ORDER BY orden_in ASC";
	}
	}
}
else {

	if($reFacturarPOS && !empty($filtroFecha)){

		$sql="SELECT $colSetreFacturar FROM art_fac_ven a 
		INNER JOIN  fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit
		WHERE a.nit='$codSuc'  $filtroFecha AND b.".VALIDACION_VENTA_VALIDA." $filtroResol $filtroRefacturarFE
		GROUP BY cod_barras,ref,fecha_vencimiento
		ORDER BY orden_in ASC";
		//echo "$sql <br>";

	}
	else {
		//cargar productos de factura singular, sin importar cantidades en inventario. Se identifica est escenario si $nf y $pre no son VACÍAS
		$sql="SELECT $colSet02 FROM art_fac_ven a  
			WHERE nit='$codSuc' AND (num_fac_ven='$nf' AND prefijo='$pre') 
			ORDER BY orden_in ASC";


		if(!empty($filtroB))
		{
		$sql="SELECT $colSetRemiCoti FROM art_fac_remi a 
			INNER JOIN  fac_remi b ON a.num_fac_ven=b.num_fac_ven 
			WHERE a.prefijo=b.prefijo AND a.nit=b.nit $filtro_remi_coti $filtroFacturaSeleccionada $filtroB $filtroFecha GROUP BY cod_barras,ref  ORDER BY orden_in ASC";
			}

	}
}
	


//echo "$sql";
$rs=$linkPDO->query($sql);
$i=0;
$No=0;
$n_cant=0;
$rbg="background-color:rgba(200,200,200,1)";
while($row=$rs->fetch())
{
$feVence = $row["feven"];
$ref =$row["ref"];
$cod_barras =$row["cod_barras"];
$des = $row["des"];
$SN=$row['serial'];
$presentacion = $row["presen"];
$color=$row['color'];
$talla=$row['talla'];
$iva = $row["IVA"];
$dcto=$row['dcto']*1;

$cant = $row["cant"]*1;

$n_cant+=$cant;
$fracc=$row['fraccion'];
if($fracc<=0)$fracc=1;
$uni = $row["unidades_fraccion"]*1;
$factor=($uni/$fracc)+$cant;



$costo=$row['costo'];
if(!empty($filtroB) && $factor!=0){
	$pvp=$row['precio']/$factor;
}
else {
	$pvp=$row['precio'];
}


$PVP=$row['precio_v']/1;




$sub_tot=$pvp*$factor;
$RS_EXIST=$linkPDO->query("SELECT exist,fraccion,unidades_frac,pvp_credito FROM inv_inter WHERE id_pro='$ref' AND id_inter='$cod_barras' AND fecha_vencimiento='$feVence'");
$ROW_EXIST=$RS_EXIST->fetch();
$FRAC=$ROW_EXIST['fraccion'];
if($FRAC==0){
	$FRAC=1;
}
$FACTOR_INV=(($ROW_EXIST['unidades_frac']/$FRAC)+$ROW_EXIST['exist']);
$pvpCredito=$ROW_EXIST['pvp_credito']*1;
if($pvpCredito==0){
	$pvpCredito=$PVP;
}
	
if($cotiza_a_fac!=1 && $src!="remi"){
	$exist=$FACTOR_INV+$factor;
} else{
	 $exist=$FACTOR_INV;
}
if($FLUJO_INV!=1 || $vende_sin_cant!=0)$exist=100000;
$UNI=$row['unidades_frac'];



if($cod_barras=='') {
	echo "";
}
?>
  
<tr id="tr_<?php echo $i ?>" class="art<?php echo $i ?>">

<!-- REF -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" readonly="" value="<?php echo "$ref" ?>" type="text" id="ref_<?php echo $i ?>" name="ref_<?php echo $i ?>" style=" width:80px">
<input class="art<?php echo $i ?> uk-form-small" readonly="" value="0" type="hidden" id="orden_in<?php echo $i ?>" name="orden_in<?php echo $i ?>" style=" width:80px">
</td>


<!-- cod. barras -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$cod_barras" ?>" type="text" id="cod_bar<?php echo $i ?>" name="cod_bar<?php echo $i ?>" placeholder="" style=" width:130px" readonly="">
</td>




<?php if($usar_serial==1){ ?>
<!-- S/N -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$SN" ?>" type="text" id="SN<?php echo $i ?>" name="SN_<?php echo $i ?>" placeholder="S/N" style=" width:100px" onBlur="">
</td>
<?php } ?>



<?php if($MODULES["COD_GARANTIA"]==1){ ?>
<!-- S/N -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "" ?>" type="text" id="COD_GARANTIA<?php echo $i ?>" name="COD_GARANTIA<?php echo $i ?>" placeholder="Garantia" style=" width:100px" onBlur="">
</td>
<?php } ?>

<!-- descripcion -->
<td class="art<?php echo $i ?>">
<textarea style=" width:335px;height: 31px;font-size:12px;" cols="10" rows="2" class="art<?php echo $i ?> uk-form-small" name="det_<?php echo $i ?>" id="det_<?php echo $i ?>" onBlur=""><?php echo "$des" ?></textarea>
</td>


<!-- presentacion -->
<td class="art<?php echo $i ?> uk-hidden">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$presentacion" ?>" type="text" id="presentacion<?php echo $i ?>" name="presentacion<?php echo $i ?>" placeholder="" style=" width:100px" onBlur="">
</td>

<?php if($usar_fecha_vencimiento==1){ ?>
<!-- fecha_venci -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$feVence" ?>" type="text" id="feVen<?php echo $i ?>" name="feVen<?php echo $i ?>" placeholder="" style=" width:100px" readonly="">
</td>
<?php } ?>

<?php if($usar_color==1){ ?>
<!-- color -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$color" ?>" type="text" id="color<?php echo $i ?>" name="color<?php echo $i ?>" placeholder="" style=" width:50px" >
</td>
<?php } ?>


<?php if($usar_talla==1){ ?>
<!-- talla -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$talla" ?>" type="text" id="talla<?php echo $i ?>" name="talla<?php echo $i ?>" placeholder="" style=" width:40px" >
</td>
<?php } ?>


<!-- iva -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" id="iva_<?php echo $i ?>" type="text" name="iva_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$iva" ?>" onkeyup="valor_t(<?php echo $i ?>);" style=" width:50px" onBlur="" readonly="readonly">
</td>

<!-- cant -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" id="<?php echo $i ?>cant_" type="text" name="cant_<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "$cant" ?>"  onkeyup="calc_uni($('#<?php echo $i ?>cant_'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);" style=" width:50px">

<input class="art<?php echo $i ?> uk-form-small" id="<?php echo $i ?>cant_L" type="hidden" name="cant_L<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$exist" ?>" style=" width:50px">

</td>


<!-- fracc. -->
<?php if($usar_fracciones_unidades==1){ ?>
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$uni" ?>" type="text" id="unidades<?php echo $i ?>" name="unidades<?php echo $i ?>" placeholder="" style="width:80px" onkeyup="calc_cant($('#<?php echo $i ?>cant_'),$('#fracc<?php echo $i ?>'),$('#unidades<?php echo $i ?>'));valor_t(<?php echo $i ?>);" onBlur="">

<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$fracc" ?>" type="hidden" id="fracc<?php echo $i ?>" name="fracc<?php echo $i ?>" placeholder="" style=" width:80px" readonly="">

<input class="art<?php echo $i ?> uk-form-small" value="<?php echo "$UNI" ?>" type="hidden" id="unidadesH<?php echo $i ?>" name="unidadesH<?php echo $i ?>" placeholder="" style=" width:80px" readonly="">
</td>
<?php } ?>


<!-- dcto -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?> uk-form-small" id="dcto_<?php echo $i ?>" type="text" name="dcto_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$dcto" ?>" onkeyup="valor_t(<?php echo $i ?>);" onChange="dct(<?php echo $i ?>);valMin($('#val_u<?php echo $i ?>'),<?php echo ("$costo") ?>,<?php echo "$pvp" ?>,<?php echo "$i" ?>);" style=" width:50px">
<input class="art<?php echo $i ?> uk-form-small" id="dcto_cli<?php echo $i ?>" name="dcto_cli<?php echo $i ?>" type="hidden" value="<?php echo "" ?>">
<input class="art<?php echo $i ?> uk-form-small" id="tipo_dcto<?php echo $i ?>" name="tipo_dcto<?php echo $i ?>" type="hidden" value="<?php echo "" ?>">
</td>

<!-- val Uni -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?> uk-form-small" id="val_u<?php echo $i ?>" name="val_uni<?php echo $i ?>" type="text" onkeyup="puntoa($(this));valor_t(<?php echo "$i" ?>);" value="<?php echo money("$pvp") ?>" onblur="valMin($(this),<?php echo ("$costo") ?>,<?php echo "$pvp" ?>,<?php echo "$i" ?>);" style=" width:70px">

<input class="art<?php echo $i ?> uk-form-small" id="val_u<?php echo $i ?>HH" name="val_u<?php echo $i ?>" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art<?php echo $i ?> uk-form-small" id="val_u<?php echo $i ?>H" name="val_u<?php echo $i ?>H" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art0" id="valMin<?php echo $i ?>" type="hidden" name="valMin<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$costo" ?>" style=" width:30px">
<input class="art<?php echo $i ?> uk-form-small" id="val_orig<?php echo $i ?>" name="val_orig<?php echo $i ?>" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art<?php echo $i ?> uk-form-small" id="val_origb<?php echo $i ?>" name="val_orig2<?php echo $i ?>" type="hidden" value="<?php echo "$pvp" ?>">
<input class="art<?php echo $i ?> uk-form-small" id="val_cre<?php echo $i ?>H" name="val_cre<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$pvpCredito" ?>">
</td>

<!-- sub tot -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?> uk-form-small" id="val_t<?php echo $i ?>" name="val_tot<?php echo $i ?>" type="text" readonly="" value="<?php echo money("$sub_tot") ?>" style=" width:150px">
<input class="art<?php echo $i ?> uk-form-small" id="val_t<?php echo $i ?>HH" name="val_t<?php echo $i ?>" type="hidden" readonly="" value="<?php echo "$sub_tot" ?>">
<input class="art<?php echo $i ?> uk-form-small" id="val_t<?php echo $i ?>H" name="val_t<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$sub_tot" ?>">
</td>
<td class="art<?php echo $i ?>">
<img onmouseup="eli($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="31px" heigth="31px">
</td>
</tr>  
<script language="javascript1.5" type="text/javascript">
				//valor_t(cont);
			    move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont);
 			   cont++;
			   ref_exis++;
			  
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
</script>
  
<?php
$i++;
}////// FIN WHILE LOAD ARTS
$n_ref=$i;

?>