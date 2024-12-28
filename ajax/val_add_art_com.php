<?php
require_once("../Conexxx.php");
$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("codBar");
$fe=r('fe');
$num_facH=0;
$nit_proH="";
if(isset($_SESSION['num_fac'])){$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_proH=$_SESSION['nit_pro'];}
$busqRefCod="AND ref='$ref' AND cod_barras='$codBar'";
if(empty($ref)){$busqRefCod="AND cod_barras='$codBar'";}
if(empty($codBar)){$busqRefCod="AND ref='$ref'";}

if(!empty($num_facH) && !empty($nit_proH))
{
	if(!empty($fe))$sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su=$codSuc AND nit_pro='$nit_proH' $busqRefCod AND fecha_vencimiento='$fe'";
	else $sql="SELECT  * FROM art_fac_com WHERE num_fac_com='$num_facH' AND cod_su=$codSuc AND nit_pro='$nit_proH' $busqRefCod AND fecha_vencimiento='0000-00-00'";
	
	
	//echo "$sql
	//";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
	
	//set_time_limit(30);

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
		$ubi=$row["ubicacion"];
		//       0        1       2      3    4    5    6       7         8       9     10     11      12       13           14        15    16      17      18  19
		echo "$cant|$referencia|$des|$costo|$iva|$gan|$pvp|$referencia|$tipoD|$color|$talla|$cod_bar|$clase|$fabricante|$presentacion|$frac|$uni|$fechaVenci|1|$ubi";
		}
		else {
			
			echo "-69";
			//echo "$sql";
			}

}/////// FIN IF fac_com

?>