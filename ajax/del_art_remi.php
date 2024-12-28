<?php
require_once("../Conexxx.php");

$TABLA="fac_remi";
$tabla2="art_fac_remi";
$codOrigen=r("cod_origen");

$num_fac=r( 'nf' );
$pre=r("pre");

$ref=r( 'ref');
$codBar=r( 'cod_barras');
$feVen=r("feVen");

if(empty($feVen))$feVen='0000-00-00';
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


// 316 471 0852
$sql="UPDATE inv_inter SET exist=exist+(select cant from $tabla2 where ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen' AND num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc), unidades_frac=unidades_frac+(select unidades_fraccion from $tabla2 where ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen' AND num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc) WHERE id_pro='$ref' AND id_inter='$codBar' AND fecha_vencimiento='$feVen'";
$linkPDO->exec($sql);



$sql="DELETE FROM $tabla2 WHERE ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen' AND num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc";
$linkPDO->exec($sql);

 

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
eco_alert("Guardado con Exito!");
}
else{eco_alert("ERROR! Intente nuevamente");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

?>