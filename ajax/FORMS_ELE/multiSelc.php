<?php

include("../../Conexxx.php");

$selectedEle=r("selectedEle");
$nameEle=r("nameEle");
$idEle=r("idEle");
$claseEle=r("claseEle");
$table=r("table");
$where=limpWhere($_REQUEST["where"]);
$idCol=r("idCol");
$desCol=limpWhere($_REQUEST["desCol"]);

echo multiSelc("$selectedEle","$nameEle","$idEle","$claseEle","$table","$where","$idCol","$desCol");

?>