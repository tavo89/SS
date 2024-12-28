<?php
require_once("../Conexxx.php");


$num_fac=r( 'nf' );
$pre=r("pre");

$idServ=r('idServ');



// 316 471 0852

$del="DELETE FROM serv_fac_remi WHERE id_serv='$idServ'  AND num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su=$codSuc";
//echo $sql;

t1($del);
//t2($update,$del);
//echo "$del";

?>