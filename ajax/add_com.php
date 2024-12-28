<?php
require_once("../Conexxx.php");
$num_fac=r('num_fac');
$nit_pro=r('nit');

$ipSrc=get_ip();

$ID=getId_art_com();

$SQL="INSERT IGNORE INTO art_fac_com(id,num_fac_com,nit_pro,cod_su,ip_src) VALUES('$ID','$num_fac','$nit_pro','$codSuc','$ipSrc')";
//query($SQL);
t1($SQL);
echo "$ID";
  ?>