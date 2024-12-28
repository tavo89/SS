 <?php
require_once("../../Conexxx.php");
$num_fac=r('num_fac');
$prefijo=r('prefijo');
$hash=r('hash');
$codSuc=r('codSuc');

SendFacDIAN($num_fac,$prefijo,$codSuc,$hash);


   ?>