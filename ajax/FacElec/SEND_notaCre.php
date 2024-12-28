 <?php
require_once("../../Conexxx.php");
$num_fac=r('num_fac');
$prefijo=r('prefijo');

$codSuc=r('codSuc');

SendNotaCreDIAN($num_fac,$prefijo,$codSuc);


   ?>