<?php
require_once("../Conexxx.php");
$id=$_REQUEST['id_cli'];


$qry="SELECT * FROM usuarios WHERE id_cli='$id'";
$rs=$linkPDO->query($qry);
if($row=$rs->fetch())echo 1;
else echo 0;

?>