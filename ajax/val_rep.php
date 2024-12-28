<?php
require_once("../Conexxx.php");
$campo = $_REQUEST["campo"];
$col = $_REQUEST["colum"];
$tab= $_REQUEST["tabla"];


$rs=$linkPDO->query("SELECT * FROM $tab WHERE $col='$campo' AND cod_su=$codSuc;");

if($row=$rs->fetch()){
echo "1";
}
else{echo "0";}

?>