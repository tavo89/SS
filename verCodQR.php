<?php
include_once("DB.php");
include_once('offline_LIB.php');
include_once("initVariablesSistema.php");
include_once('vendor/phpqrcode-master/phpqrcode.php');


$num_fac=s('n_fac_ven');
$pre=s('prefijo');
$hash=s('hashFacVen');
$urlVars="t=1&n_fac_ven=$num_fac&prefijo=$pre&hashFacVen=$hash&codSuc=$codSuc";

$linkQR = "http://$_SERVER[HTTP_HOST]/verFacturaElec.php?$urlVars";

echo getQRcode($actual_link);


?>