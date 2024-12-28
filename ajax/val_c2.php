<?php
require_once("../Conexxx.php");
$campo = $_REQUEST["campo"];
$campo2 = $_REQUEST["campo2"];
$col = $_REQUEST["colum"];
$col2 = $_REQUEST["colum2"];
$tab= $_REQUEST["tabla"];


$rs=$linkPDO->query("SELECT * FROM $tab WHERE $col='$campo'");

if($row=$rs->fetch()){
echo "1";
}
else{echo "0";}

?>