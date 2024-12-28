 <?php
require_once("../../Conexxx.php");
$num_fac=r('num_fac');
$nit_pro=r('nit_pro');

$codSuc=r('codSuc');

SendNotaDebDIAN($num_fac,$nit_pro,$codSuc);


   ?>