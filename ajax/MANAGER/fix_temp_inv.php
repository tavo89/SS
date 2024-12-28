<?php
include("../../Conexxx.php");

$q1="ANALYZE TABLE `inv_inter`";
$q2="CHECK TABLE `inv_inter`";

$linkPDO->query($q1);
$linkPDO->query($q2);



echo 1;
?>