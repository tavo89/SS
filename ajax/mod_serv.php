<?php
require_once("../Conexxx.php");

$TABLA="servicios";
$COL_ID_TABLA="id_serv";

$UNIC_INDEX=rm("ID");

$serv=rm("serv");
$des=r("des_serv");
$iva=limpianum(r("iva"));
$pvp=quitacom(r("pvp"));
$km_rev=limpianum(r("km_rev"));
$COD=r("cod_serv");
$clasificacion=r("clasificacion");



$sql="UPDATE $TABLA SET cod_serv='$COD',
                        servicio='$serv',
                        des_serv='$des',
                        iva='$iva',
                        pvp='$pvp',
                        km_revisa='$km_rev',
                        clasificacion='$clasificacion'
      WHERE $COL_ID_TABLA='$UNIC_INDEX'";
//echo "SQL: $sql";
t1($sql);




?>