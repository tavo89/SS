<?php
require_once("../Conexxx.php");
$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("cod_bar");
if(empty($codBar)){$codBar=$ref;$ref="";}
$fe=r('fe');
$num_facH=0;
$nit_proH="";

$resp=1;

if(isset($_SESSION['num_fac'])){$num_facH=$_SESSION['num_fac'];}
if(isset($_REQUEST['nfFac'])){$num_facH=r("nfFac");}

if(isset($_SESSION['nit_pro'])){$nit_proH=$_SESSION['nit_pro'];}
if(isset($_REQUEST['nitPro'])) {$nit_proH=r("nitPro");}


$addRefValidateA="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$fe'";
$addRefValidateB="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='0000-00-00'";

if($MODULES["QUICK_FAC_INPUT"]==1){
$addRefValidateA="cod_barras='$codBar'  AND fecha_vencimiento='$fe'";
$addRefValidateB="cod_barras='$codBar'  AND fecha_vencimiento='0000-00-00'"; }

$UPDATE_REPEAT_VALIDATE="";
if(!empty($num_facH) && !empty($nit_proH))
{
	if(!empty($ref)){
	if(!empty($fe)){$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su=$codSuc AND nit_pro='$nit_proH' AND $addRefValidateA";$UPDATE_REPEAT_VALIDATE=$addRefValidateA;}
	else {$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su=$codSuc AND nit_pro='$nit_proH' AND $addRefValidateB";$UPDATE_REPEAT_VALIDATE=$addRefValidateB;}
	
	}
	else
	{
		if(!empty($fe)){$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su=$codSuc AND nit_pro='$nit_proH' AND $addRefValidateA";$UPDATE_REPEAT_VALIDATE=$addRefValidateA;}		
		else {$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su=$codSuc AND nit_pro='$nit_proH' AND $addRefValidateB";$UPDATE_REPEAT_VALIDATE=$addRefValidateB;}
		
	}
	
	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	$nr=$rs->rowCount();;
	if($row=$rs->fetch())
	{
		if($nr>=1) {
			
		
	
		
        $cant=$row['cant']*1;
		$referencia=$row['ref'];
		$cod_bar=$row['cod_barras'];
		$des=$row['des'];
		$color=$row['color'];
		$talla=$row['talla'];
		$presentacion=$row['presentacion'];
		$fabricante=$row['fabricante'];
		$clase=$row['clase'];
		$costo=$row['costo']*1;
		$iva=$row['iva'];
				//echo "<br><span style=\"color: #fff\"<b>$i costo SIN Dcto : $costo</b></span><br>";
		$dcto=$row['dcto'];
		$Iva=$row['iva'];
		
		$tipoD=$row['tipo_dcto'];
		$pvp=$row['pvp']*1;
		$s_tot=$row['tot']*1;
		$gan=$row['uti'];
		
		$fechaVenci=$row['fecha_vencimiento'];
		$frac=$row['fraccion'];
		$uni=$row['unidades_fraccion'];
		
		echo limpiarcampo("$cant|$referencia|$des|$costo|$iva|$gan|$pvp|$referencia|$tipoD|$color|$talla|$cod_bar|$clase|$fabricante|$presentacion|$frac|$uni|$fechaVenci|1");
			
		
		}
		else {
	//set_time_limit(30);
		echo "-101010";
		
        
		}
		}
		else
		{
		
		
		if(!empty($ref)){
		if(!empty($fe) && $fe!="0000-00-00"){$sql="SELECT exist,dcto2,inv_inter.id_pro ref,detalle,precio_v,costo,iva,gana,color,talla,id_inter,id_clase,fab,".tabProductos.".presentacion,inv_inter.fraccion,inv_inter.unidades_frac,fecha_vencimiento FROM ".tabProductos." INNER JOIN inv_inter ON inv_inter.id_pro=".tabProductos.".id_pro WHERE  ( inv_inter.id_inter='$codBar' AND inv_inter.id_pro='$ref' AND inv_inter.fecha_vencimiento='$fe') AND nit_scs=1";}

else {$sql="SELECT exist,dcto2,inv_inter.id_pro ref,detalle,precio_v,costo,iva,gana,color,talla,id_inter,id_clase,fab,".tabProductos.".presentacion,inv_inter.fraccion,inv_inter.unidades_frac,fecha_vencimiento FROM ".tabProductos." INNER JOIN inv_inter ON inv_inter.id_pro=".tabProductos.".id_pro WHERE  ( inv_inter.id_inter='$codBar' AND inv_inter.id_pro='$ref') AND nit_scs=1";}
		}
		else
		{

$addRefValidateA="inv_inter.id_inter='$codBar' AND inv_inter.id_pro='$ref' AND inv_inter.fecha_vencimiento='$fe'";
$addRefValidateB="inv_inter.id_inter='$codBar' AND inv_inter.id_pro='$ref'";
if($MODULES["QUICK_FAC_INPUT"]==1){
$addRefValidateA="inv_inter.id_inter='$codBar'  AND inv_inter.fecha_vencimiento='$fe'";
$addRefValidateB="inv_inter.id_inter='$codBar' "; }

if(!empty($fe) && $fe!="0000-00-00"){$sql="SELECT exist,dcto2,inv_inter.id_pro ref,detalle,precio_v,costo,iva,gana,color,talla,id_inter,id_clase,fab,".tabProductos.".presentacion,inv_inter.fraccion,inv_inter.unidades_frac,fecha_vencimiento FROM ".tabProductos." INNER JOIN inv_inter ON inv_inter.id_pro=".tabProductos.".id_pro WHERE  ($addRefValidateA) AND nit_scs=1";}

else {$sql="SELECT exist,dcto2,inv_inter.id_pro ref,detalle,precio_v,costo,iva,gana,color,talla,id_inter,id_clase,fab,".tabProductos.".presentacion,inv_inter.fraccion,inv_inter.unidades_frac,fecha_vencimiento FROM ".tabProductos." INNER JOIN inv_inter ON inv_inter.id_pro=".tabProductos.".id_pro WHERE  ($addRefValidateB) AND nit_scs=1";}			
			
		}

//--inv_inter.id_pro='$ref' OR--
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
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
			//if($cant>0)
			//echo htmlentities("$cant|$ref|$des|$costo|$iva|$gan|$pvp|$id_pro|$dcto2|$color|$talla|$codBar|$clase|$fab|$presentacion", ENT_QUOTES,"$CHAR_SET");
			echo limpiarcampo("$cant|$ref|$des|$costo|$iva|$gan|$pvp|$id_pro|$dcto2|$color|$talla|$codBar|$clase|$fab|$presentacion|$frac|$uni|$fe|0");
			//else echo "0";
}
else {echo "-2";}

	
		}

}/////// FIN IF fac_com
else 
{
 	echo "NF: $num_facH, NIT: $nit_proH";	
}
   ?>