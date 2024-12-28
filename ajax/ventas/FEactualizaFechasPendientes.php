<?php
include('../../Conexxx.php');

$sql="UPDATE fac_venta SET fecha = CONCAT(CURRENT_DATE, ' ',TIME(fecha) ) 
      WHERE prefijo='$codPapelSuc' AND nit='$codSuc' AND estado_factura_elec!=1 
      AND DATE(fecha)> CURRENT_DATE() - INTERVAL 15 DAY;";
$linkPDO->exec($sql);
echo "1";


