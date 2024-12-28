<?php
require_once("../Conexxx.php");

$ref=limpiarcampo($_REQUEST['ref']);
$num_fac=limpiarcampo($_REQUEST['num_fac']);
$nit=$_SESSION['Nit'];

$del="UPDATE art_fac_ven SET devolucion='si' WHERE ref='$ref' AND num_fac_ven='$num_fac' AND nit='$nit'";
//echo $sql;
$update="UPDATE inv_inter SET exist=exist+(select cant from art_fac_ven where ref='$ref' AND num_fac_ven='$num_fac') WHERE id_pro='$ref' AND nit_scs='$nit'";
 
t2($update,$del);
echo "$del<br>$update";

?>