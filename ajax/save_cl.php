<?php
require_once("../Conexxx.php");

$id_inter= $_REQUEST['id_producto'];
$clase= $_REQUEST['clase'];
$class= $_REQUEST['class'];





if(isset($clase) &&!empty($clase)){
$linkPDO->exec("INSERT INTO clases (des_clas) VALUES ('$clase')");
echo "1";
}
else echo "0";

?>
