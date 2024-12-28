<?php

include("../../Conexxx.php");

$idCta=r("id");
$saldo=0;
$showSaldo=0;

$sql="SELECT * FROM cuentas_mvtos WHERE id_cuenta='$idCta' ORDER BY  fecha_mov";
$rs=$linkPDO->query($sql);

?>

<table class="uk-table">
<thead>
<th>Operaci&oacute;n</th>
<th>Detalle</th>
<th>Origen</th>
<th>Monto</th>
<th>Saldo</th>
<th>Fecha</th>
</thead>
<tbody>
<?php
while($row=$rs->fetch()){

$monto=money3($row["monto"]);
if($row["tipo_mov"]=="EGRESO"){$styleRED="style=\"color:red;font-weight:bold;\"";$saldo-=$row["monto"];}
else {$styleRED="";	$saldo+=$row["monto"];}

$showSaldo=money3($saldo);
echo "<tr><td $styleRED>$row[tipo_mov]</td><td>$row[concepto_mov]</td><td>$row[clase_mov]</td><td $styleRED>$monto</td><td ><b>$showSaldo</b></td><td>$row[fecha_mov]</td></tr>";
	
	
}

?>
</tbody>
</table>