<?php
include("../../Conexxx.php");

$tab=r("tab");
$Cols=r("Colset");
$NumCols=r("numF");

$Insert="INSERT INTO $tab($Cols) VALUES(";

try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


for($i=0;$i<$NumCols;$i++){

$var=r("c".$i);
if($i!=$NumCols-1){$Insert.="'$var',";}
else {$Insert.="'$var')";}
	
}

$linkPDO->exec($Insert);

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
echo "1";
}
else{echo "200";}
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}


?>