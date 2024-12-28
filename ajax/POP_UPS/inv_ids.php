<?php

include("../../Conexxx.php");

$idCta=r("id");
$saldo=0;
$showSaldo=0;

$sql="SELECT * FROM seriales_inv WHERE cod_su='$codSuc' ORDER BY  label";
$rs=$linkPDO->query($sql);

?>

<table class="uk-table">
<thead>
<th>Secci&oacute;n</th>
<th>Rango Min.</th>
<th>Rango Max.</th>
<th>Actual</th>
<th>Opci&oacute;n</th>

</thead>
<tbody>
<?php
while($row=$rs->fetch()){

?>
<tr>
<td><?php echo $row["label"];?></td><td><?php echo $row["min"];?></td><td><?php echo $row["max"];?></td><td><?php echo $row["current"];?></td><td><a href="#" class="uk-icon-check-square uk-icon-button uk-icon-hover uk-icon-small" onClick="<?php echo "set_serial($row[id],$row[current])";?>">

</a></td>
</tr>
<?php

}

?>
</tbody>
</table>