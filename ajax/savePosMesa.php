<?php
include("../Conexxx.php");
$left=r("left");
$top=r("top");
$ID_mesa=r("id_mesa");


try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$linkPDO->exec("SAVEPOINT A");
$sql="UPDATE mesas SET p_left='$left', p_top='$top' WHERE id_mesa='$ID_mesa'";
echo "$sql";
$linkPDO->exec($sql);
$linkPDO->commit();
 
 
$linkPDO= null;



}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

?>