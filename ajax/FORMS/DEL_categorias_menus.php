<?php
include("../../Conexxx.php");
$tab="clases_favoritos";
$ID=r("ID");
if($rolLv==$Adminlvl && !empty($ID)){






try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$DEL="DELETE FROM $tab WHERE id='$ID'";

$linkPDO->exec($DEL);


$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){echo "1";}
else{echo "200";}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}

?>