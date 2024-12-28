<?php
require_once("../Conexxx.php");


$num_fac=r( 'nf' );
$pre=r("pre");

$ref=r( 'ref');
$codBar=r( 'cod_barras');
$feVen=r("feVen");

if(empty($feVen))$feVen='0000-00-00';

// 316 471 0852

$del="DELETE FROM art_fac_ven WHERE ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen' AND num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc";
//echo $sql;

t1($del);
//t2($update,$del);
//echo "$del";

?>