<?php

include("../../Conexxx.php");

$idCta=r("id");
$fechaI=r("fi");
$fechaF=r("ff");
$saldo=0;
$showSaldo=0;

$sql="SELECT *,DATE(fecha_mov) fe FROM cuentas_mvtos WHERE id_cuenta='$idCta' ORDER BY  fecha_mov ASC";
$rs=$linkPDO->query($sql);

?>

<table align="center" width="100%" cellpadding="0" cellspacing="0" style="font-size:10px" id="TabClientes" class="display dataTable table table-striped table-bordered table-hover tablaDataTables" rules="all" frame="box">
<thead>
<th>Fecha</th>
<th>Operaci&oacute;n</th>
<th>Detalle</th>
<th>Origen</th>
<th>Monto</th>
<th>Saldo</th>

</thead>
<tbody>
 
<?php
$saldoI=0;
$flagI=0;
$saldoF=0;
$signo=1;
while($row=$rs->fetch()){

$monto=money3($row["monto"]);
if($row["tipo_mov"]=="EGRESO"){$styleRED="style=\"color:red;font-weight:bold;\"";$saldo-=$row["monto"];}
else {$styleRED="";	$saldo+=$row["monto"];}

$showSaldo=money3($saldo);
if( ($row["fe"]>=$fechaI&&$row["fe"]<=$fechaF) || (empty($fechaI) || empty($fechaF)) ){

if($row["tipo_mov"]=="EGRESO"){ $signo=1;}
else { $signo=-1;}
	if($flagI==0){$saldoI=$saldo+($row["monto"]*$signo);$flagI=1;}
	$saldoF=$saldo;
echo "<tr><td>$row[fecha_mov]</td><td $styleRED>$row[tipo_mov]</td><td>$row[concepto_mov]</td><td>$row[clase_mov]</td><td $styleRED>$monto</td><td ><b>$showSaldo</b></td></tr>";
}

}

?>
<tfoot>
<td><?php echo "Saldo Inicio:".money2($saldoI);?></td>
<td> </td>
<td><?php echo "Saldo Fin:".money2($saldoF);?></td>
<td></td>
<td></td>
<td></td>
</tfoot>
</tbody>
</table>
<?php

?>