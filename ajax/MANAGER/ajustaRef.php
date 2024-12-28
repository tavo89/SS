<?php
include_once("../../Conexxx.php");

$limit      = r('limit');
$offset     = r('offset');
$respuesta =0;
try{
    $sql = "SELECT id_pro, id_inter, fecha_vencimiento FROM inv_inter WHERE nit_scs='$codSuc' LIMIT $offset, $limit";
	echo "SQL: $sql <br>";
    $rs  = $linkPDO->query($sql);
    while($row = $rs->fetch()) 
    { 

        ajusta_kardex_ref($codSuc,$row['id_pro'],$row['id_inter'],$row['fecha_vencimiento']);
		echo "<li>$row[id_pro]-$row[id_inter]-$row[fecha_vencimiento]</li>";
		$respuesta =1;
        
    }
	
	//echo $respuesta;
	$linkPDO = null;
	$rs      = null;
    
}
catch(Exception $e){} 

?>