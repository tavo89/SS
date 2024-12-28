<?php
require_once("../Conexxx.php");
$tab=r('t');
$colSet=r('c');
$valCol=r('vc');
$useFunct=r('useF');
$clau=limpWhere($_REQUEST['clau']);


if($useFunct!="1")$qry="UPDATE $tab SET `$colSet`='$valCol' WHERE $clau";
else {$qry="UPDATE $tab SET `$colSet`=CONCAT('$valCol',' ',TIME(`$colSet`) ) WHERE $clau";}

 
$sqlValidate="SELECT * FROM $tab WHERE $clau";
$rsValidate=$linkPDO->query($sqlValidate);
$nr= $rsValidate->rowCount();;
if($nr==1){
t1($qry);
echo 1;

}
else {echo "666";}

?>