<?php
include_once("../Conexxx.php");
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$linkPDO->exec("SAVEPOINT inicio");

$num_fac=r("num_fac_venta");
$pre=r("pre");
$sql="SELECT *, (tot-r_fte-r_ica-r_iva) as total_fac FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' $fecha_lim_anulaVenta FOR UPDATE";
$rs=$linkPDO->query($sql);
//echo "$sql";
//$estado="CERRADA";
if($row=$rs->fetch())
{
	$estado=$row['anulado'];
	$MesaID=$row['num_mesa'];
	$karDex=$row['kardex'];	
	$idCta=$row["id_cuenta"];
	$form_p=$row["tipo_venta"];
	$tot=$row["total_fac"]-$row["abono_anti"];
	
	$numRemi=$row["num_remi"];
	$preRemi=$row["pre_remi"];
}
//echo "EST:$estado<BR>";
if($estado!="CERRADA")
{


 

if($FLUJO_INV==1 && $karDex==1){
$sql="SELECT * FROM art_fac_ven WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc FOR UPDATE";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch()){
		$linkPDO->exec("SAVEPOINT loop".$i);

		$ref=$row["ref"];
		$cod_bar=$row["cod_barras"];



		$cant=$row["cant"];
		$uni=$row["unidades_fraccion"];
		$frac=$row["fraccion"];
		if($frac==0)$frac=1;

$feVenci=$row["fecha_vencimiento"];
if(empty($fechaVenci)){$fechaVenci="0000-00-00";}
//if(empty($numRemi) && empty($preRemi)){
$sql="UPDATE `inv_inter`  SET exist=(exist-$cant), unidades_frac=(unidades_frac-$uni) WHERE nit_scs='$codSuc' AND fecha_vencimiento='$feVenci' AND id_pro='$ref' AND id_inter='$cod_bar'";
$linkPDO->exec($sql);




//}

}
}
else
{

}

$sql="UPDATE fac_venta SET anulado='CERRADA', fecha_anula='0000-00-00' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc";
$linkPDO->exec($sql);
up_cta($form_p,$idCta,$tot,"+","Venta $pre $num_fac","Factura Venta",$hoy);

$linkPDO->exec("SAVEPOINT mesas");

$sqlM="UPDATE mesas SET estado='Disponible',valor='',num_fac_ven='0',prefijo='',hash='' 
       WHERE id_mesa='$MesaID' AND (num_fac_ven='$num_fac' AND prefijo='$pre')";
//echo "$sqlM";
$linkPDO->exec($sqlM);

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){

}
else{eco_alert("ERROR! Intente nuevamente");}



}//fin estado!=CERRADA
else echo "0";
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

?>