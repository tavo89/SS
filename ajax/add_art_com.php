<?php
require_once("../Conexxx.php");
//$ref=limpiarcampo($_REQUEST['ref']);
$ref=r("ref");
$codBar=r("cod_bar");
if(empty($codBar)){$codBar=$ref;$ref="";}
$fe=r('fe');
$num_facH=0;
$nit_proH="";
$resp=1;

/////////// PARAMETROS REUTILIZABLES ////////////////////

$tablaListaInv="inv_inter";

$filtroSedeA=" AND nit_scs=$codSuc";
$filtroSedeB=" AND cod_su=$codSuc";
//if($MODULES["MULTISEDES"]==1){$filtroSedeA="";$filtroSedeB="";}

if(isset($_SESSION['num_fac'])){$num_facH=$_SESSION['num_fac'];}
if(isset($_REQUEST['nfFac'])){$num_facH=r("nfFac");}

if(isset($_SESSION['nit_pro'])){$nit_proH=$_SESSION['nit_pro'];}
if(isset($_REQUEST['nitPro'])) {$nit_proH=r("nitPro");}

//if(empty($ref)){$ref=$codBar;}

include("add_art_com_parametrizaCampos.php");



// buscar si el producto esta repetido en la compra actual
$UPDATE_REPEAT_VALIDATE="";
if(!empty($num_facH) && !empty($nit_proH))
{
	if(!empty($ref)){
	if(!empty($fe)){$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' $filtroSedeB AND nit_pro='$nit_proH' AND $addRefValidateA";$UPDATE_REPEAT_VALIDATE=$addRefValidateA;}
	else {$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' $filtroSedeB AND nit_pro='$nit_proH' AND $addRefValidateB";$UPDATE_REPEAT_VALIDATE=$addRefValidateB;}
	
	}
	else
	{
		if(!empty($fe)){$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' $filtroSedeB AND nit_pro='$nit_proH' AND $addRefValidateA";$UPDATE_REPEAT_VALIDATE=$addRefValidateA;}		
		else {$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' $filtroSedeB AND nit_pro='$nit_proH' AND $addRefValidateB";$UPDATE_REPEAT_VALIDATE=$addRefValidateB;}
		
	}
	//echo "$sql";
	$rs=$linkPDO->query($sql);
	$nr=$rs->rowCount();;
	if($row=$rs->fetch())
	{
		//echo "1st IF $sql";
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
		
		$ubica=$row["ubicacion"];
		$aplica_vehi=$row["aplica_vehi"];
		$pvpCre=$row["pvp_cre"];
		$pvpMay=$row["pvp_may"];
		$campo_add_01=$row["campo_add_01"];
		$campo_add_02=$row["campo_add_02"];
		
		$cod_color=$row["cod_color"];
		$vigencia_inicial=$row["vigencia_inicial"];
		$grupo_destino=$row["grupo_destino"];
		$impuesto_saludable=$row["impuesto_saludable"];
		
		$respuesta="$cant|$referencia|$des|$costo|$iva|$gan|$pvp|$referencia|$tipoD|$color|$talla|$cod_bar|$clase|$fabricante|$presentacion|$frac|$uni|$fechaVenci|1|-101010|$ubica|$pvpCre|$pvpMay|$aplica_vehi|$campo_add_01|$campo_add_02|$cod_color|$vigencia_inicial|$grupo_destino|$impuesto_saludable";
		
		if($nr>=1) {
			

	    echo $respuesta;		
			
		if($MODULES["QUICK_FAC_INPUT"]==1){t1("UPDATE art_fac_com SET cant=(cant+1) WHERE $UPDATE_REPEAT_VALIDATE");}
		}
		else {
	//set_time_limit(30);

      
		$respuesta="$cant|$referencia|$des|$costo|$iva|$gan|$pvp|$referencia|$tipoD|$color|$talla|$cod_bar|$clase|$fabricante|$presentacion|$frac|$uni|$fechaVenci|1||$ubica|$pvpCre|$pvpMay|$aplica_vehi|$campo_add_01|$campo_add_02|$cod_color|$vigencia_inicial|$grupo_destino";
		echo $respuesta;
		}
		}// finaliza QUERY REPETIDO
		else
		{
		
		include("add_art_com_buscarEnTablaInv.php");
		
		//echo "$sql ------> $MODULES[QUICK_FAC_INPUT]";
		//--$tablaListaInv.id_pro='$ref' OR--
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch()){
					add_art_compra($row);
		}
		else {
			// sino encuentra en la base principal, buscar en la tabla de respaldo(registros viejos, con poco uso)
			$tablaListaInv="inv_inter_alter";
			include("add_art_com_parametrizaCampos.php");
			include("add_art_com_buscarEnTablaInv.php");
	
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	add_art_compra($row);
	}
	
	else{echo " | | | | | | | | | | | |||||||1|-2|";}}

	
		}

}/////// FIN IF fac_com
else 
{
 	echo "NF: $num_facH, NIT: $nit_proH";	
}

?>