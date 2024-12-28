<?php
require_once("Conexx.php");

$sql="UPDATE `inv_inter` i INNER JOIN (select sum(cant) cant,ref,cod_su,uti,costo,cod_barras,talla,color,fecha_vencimiento,unidades_fraccion,fraccion FROM art_fac_com ar WHERE ar.cod_su=$codSuc AND ar.num_fac_com='$num_fac' AND nit_pro='$nit_pro' group by ar.cod_barras) a ON i.id_inter=a.cod_barras SET i.exist=(i.exist+a.cant),i.color=a.color,i.talla=a.talla,i.dcto2='$dcto2',i.unidades_frac=(i.unidades_frac+a.unidades_fraccion),i.gana=a.uti,i.fraccion=a.fraccion WHERE i.nit_scs=a.cod_su AND i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento";

?>
