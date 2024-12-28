<?php
include('../../Conexxx.php');
$listaFacturasPendinetes = array();
$sql = "SELECT num_fac_ven,prefijo,serial_fac,nit FROM fac_venta 
        WHERE nit=$codSuc AND (TIPDOC = '7' AND ( estado_factura_elec!=1) ) AND DATE(fecha)>=NOW() - INTERVAL 15 day 
        ORDER BY num_fac_ven ASC";

$result = $linkPDO->query($sql);
while($row = $result->fetch()){
    $listaFacturasPendinetes[] = array('num_fac_ven'=>$row['num_fac_ven'],
                                       'prefijo'=>$row['prefijo'],
                                       'serial_fac'=>$row['serial_fac'],
                                       'codSuc'=>$row['nit']);
}

echo json_encode($listaFacturasPendinetes);

