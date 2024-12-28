<?php
require_once("../Conexxx.php");



$serial=limpiarcampo($_REQUEST['serial']);
 


anula_dev_compra($serial,$codSuc);

   ?>