<?php
require_once("../Conexxx.php");
$tab=r('t');
$colSet=r('c');
$valCol=r('vc');
$Col_1=r('col1');
$ValCol_1=r('val_col1');
$Col_2=r('col2');
$ValCol_2=r('val_col2');


$qry="UPDATE $tab SET `$colSet`='$valCol' WHERE $Col_1='$ValCol_1' AND $Col_2='$ValCol_2'";

$rs=$linkPDO->exec($qry);
if($rs)echo 1;
else echo 0;

?>