<?php
include("STAND_ALONE_CONEXXX.php");


$param1 = $argv[1];


$codSuc=$param1;
//$codSuc=1;
t1("UPDATE x_material_query SET last=NOW() WHERE seccion='Ajuste Kardex' AND cod_su=$codSuc;");
ajusta_kardex_all($codSuc);


?>