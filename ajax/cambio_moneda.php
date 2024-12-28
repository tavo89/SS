<?php
require_once("../Conexxx.php");

$tasa=limpiarcampo($_REQUEST['tasa']);


$sql="UPDATE sucursal SET precio_bsf='$tasa' WHERE cod_su=$codSuc";
t1($sql);
echo "";

?>