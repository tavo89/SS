<?php
include("../Conexxx.php");
header("application/json");
$columns="num_fac_ven,nom_cli,id_cli,tipo_cli,tipo_venta,vendedor,tot,anulado,cod_caja,fecha";
$sql = "SELECT  $columns FROM fac_venta  WHERE  nit=$codSuc  ORDER BY fecha ";
//echo "$sql<br>" ;
$rs=$linkPDO->query($sql);

//print_r ($row);
$resp=array();
$i=0;
while($row=$rs->fetch()){
	$resp[]=$row;
	

}
echo (json_encode($resp));

?>