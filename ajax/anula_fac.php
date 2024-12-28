<?php
require_once("../Conexxx.php");
$num_fac=limpiarcampo($_REQUEST['num_fac']);
$pre=limpiarcampo($_REQUEST['pre']);


anula_fac_ven($num_fac,$pre,$codSuc);

   ?>