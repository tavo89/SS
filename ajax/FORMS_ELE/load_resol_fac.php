<?php

include("../../Conexxx.php");

$tipoResol=r("tipoResol");
$NumFac="NUM FACTURA";
$pre="PREFIJO";

if($tipoResol=="POS") {echo "<strong>$codContadoSuc</strong>". serial_fac("factura venta","POS") ."<strong></strong>";}
else if($tipoResol=="COM"){echo "<strong>$codCreditoSuc</strong>". serial_fac("credito","COM") ."<strong></strong>";}
else if($tipoResol=="PAPEL"){echo "<strong>$codPapelSuc</strong>". serial_fac("resol_papel","PAPEL") ."<strong></strong>";}

else if($tipoResol=="COM_ANT"){echo "<strong>$codCreditoSuc</strong>". serial_fac("credito_ant","COM") ."<strong></strong>";}
else if($tipoResol=="PAPEL_ANT"){echo "<strong>$codPapelSuc</strong>". serial_fac("resol_papel_ant","PAPEL") ."<strong></strong>";}
else if($tipoResol=="CRE_ANT"){echo "<strong>$codCreditoANTSuc</strong>". serial_fac("cartera_ant","CRE") ."<strong></strong>";}

else echo "<strong>$pre</strong>$NumFac<strong></strong>";

?>