<?php
include("../../DB.php");
$usar_fecha_vencimiento=0;
$fechaKardex="2012-01-01";
$VER_PROGRAMA="4.9.530102018";
$FECHA_ACTUALIZAR_SW="2018-10-30";
$LAST_VER="2410201821222";
$CHAR_SET="UTF-8";
include("../../offline_LIB.php");

$SQL=($_REQUEST["SQL"]);
try{
set_time_limit(180);
t1($SQL);
echo "1 >>$SQL";
}
catch(Exception $e){} 

?>