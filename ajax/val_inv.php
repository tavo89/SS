<?php
require_once("../Conexxx.php");
$campo = r("campo");
$campo2 = r("campo2");
$col = r("colum");
$col2 = r("colum2");
$tab= r("tabla");

$extraCondition="";
if($tab=="inv_inter"){$extraCondition=" AND nit_scs=$codSuc";}

$r_1=0;
$r_2=0;

$rs=$linkPDO->query("SELECT * FROM $tab WHERE $col='$campo' AND $col2='$campo2' $extraCondition");

if($row=$rs->fetch()){
$r_1=1;
}
else{$r_1=0;}

$rs=$linkPDO->query("SELECT * FROM $tab WHERE $col='$campo' AND $col2!='$campo2' $extraCondition");

if($row=$rs->fetch()){
$r_2=1;
}
else{$r_2=0;}

if($r_1==0 && $r_2==0)echo "0";
else if($r_1==1)echo "1";
else if($r_1==0 && $r_2==1)echo "10";


?>