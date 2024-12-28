<?php
require_once("../Conexxx.php");


$ID=r( 'ID' );



// 316 471 0852

$del="DELETE FROM servicios WHERE cod_su=$codSuc AND id_serv='$ID'";
//echo $sql;

t1($del);
//t2($update,$del);
//echo "$del";

?>