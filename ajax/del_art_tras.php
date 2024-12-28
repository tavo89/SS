<?php
require_once("../Conexxx.php");

$ref=limpiarcampo($_REQUEST['ref']);
$num_fac=limpiarcampo($_REQUEST['num_fac']);



$del="DELETE FROM art_tras WHERE ref='$ref' AND cod_tras='$num_fac' AND cod_su=$codSuc";
//echo $sql;
$update="UPDATE inv_inter SET exist=exist+(select cant from art_tras where ref='$ref' AND cod_tras='$num_fac' AND cod_su=$codSuc) WHERE id_pro='$ref' AND nit_scs=$codSuc";\

 
t2($update,$del);

?>