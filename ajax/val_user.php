<?php
require_once("../Conexxx.php");
$usu=$_REQUEST['u'];
$pass=$_REQUEST['p'];
$lvl=$_REQUEST['lvl'];
$tipo=$_REQUEST['tipo'];
$scs=$_REQUEST['scs'];

$qry="SELECT * FROM tipo_usu INNER JOIN usu_login ON usu_login.id_usu=tipo_usu.id_usu WHERE (tipo_usu.des='$tipo' OR rol_lv>=$lvl )AND usu='$usu' AND cla='$pass'";
$rs=$linkPDO->query($qry);
if($row=$rs->fetch())echo 1;
else echo 0;

?>