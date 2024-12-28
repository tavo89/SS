<?php

include("../../Conexxx.php");

$placa=r("placa");
$id_cli=r("id_cli");
 
$sql="SELECT nombre FROM usuarios WHERE id_usu='$id_cli'";
//echo "$sql";
$rs2=$linkPDO->query($sql);
$row2=$rs2->fetch();
$nomCli=$row2["nombre"];

$sql="SELECT a.id_tec,a.pvp,a.serv,a.nota,a.iva,b.placa,b.fecha,b.num_fac_ven,b.prefijo FROM serv_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo  AND b.".VALIDACION_VENTA_VALIDA." AND placa='$placa'  ORDER BY fecha DESC";
$rs=$linkPDO->query($sql);

?>
<h2 class="uk-text-bold uk-text-primary"><?php echo "Propietario : $nomCli,  C.C/NIT $id_cli";?></h2>

<table class="uk-table">
<thead>
<tr style="font-size:14px; font-weight:bold;" class="uk-block-secondary uk-contrast">
     <td width="90px">Factura</td>
     <td width="190px">Servicio</td><td>Nota</td><td width="100px">Total Facturado</td><td>T&eacute;cnico</td><td width="150px">FECHA</td>
	</tr>
</thead>
<tbody>
<?php
while($row=$rs->fetch()){

$sql="SELECT nombre FROM usuarios WHERE id_usu='$row[id_tec]'";
$rs2=$linkPDO->query($sql);
$row2=$rs2->fetch();
$tec=$row2["nombre"];
?>

<tr class=" ">
            <td><?php echo "$row[prefijo] <b>$row[num_fac_ven]</b>"; ?></td>
            <td><?php echo "$row[serv]"; ?></td>
            <td><?php echo "$row[nota]"; ?></td>
            <td align="right"><?php echo money("$row[pvp]"); ?></td>
            <td><?php echo "$tec "; ?></td>
             <td><?php echo "$row[fecha]"; ?></td>
            </tr>
            
<?php

}

?>
</tbody>
</table>