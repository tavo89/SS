<?php
include("../../Conexxx.php");
$NEAR_BAN=$DIAS_BAN_CLI-$DIAS_BAN_CLI*0.25;


$sql="SELECT tel_cli,num_fac_ven,prefijo,nom_cli,id_cli,fecha,tot,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2 FROM fac_venta WHERE  ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND estado!='PAGADO' AND (DATEDIFF(CURRENT_DATE(),DATE(fecha) )>=$NEAR_BAN AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )<$DIAS_BAN_CLI) AND fecha_pago='0000-00-00 00:00:00' GROUP BY num_fac_ven,prefijo";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	if($see_warn_ban_list==1){
?>
<table class="uk-table">
<thead>
<tr>
<th>Nombre</th>
<th>C.C/NIT</th>
<th>Tel.</th>
<th>Factura</th>
<th>TOT.</th>
<th>Fecha</th>
<th>MORA</th>
</tr>
</thead>
<tbody>
<?php
$sql="SELECT tel_cli,num_fac_ven,prefijo,nom_cli,id_cli,fecha,tot,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(fecha_pago),DATE(fecha) ) AS mora2 FROM fac_venta WHERE  ".VALIDACION_VENTA_VALIDA." AND tipo_venta='Credito' AND estado!='PAGADO' AND (DATEDIFF(CURRENT_DATE(),DATE(fecha) )>=$NEAR_BAN AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )<$DIAS_BAN_CLI) AND fecha_pago='0000-00-00 00:00:00' GROUP BY num_fac_ven,prefijo";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){

$nom=$row["nom_cli"];
$idCli=$row["id_cli"];	
$NF=$row["num_fac_ven"];
$PRE=$row["prefijo"];
$TOT_FAC=money2($row["tot"]);
$fechaFAC=$row["fecha"];
$mora=$row["mora"];
$tel=$row["tel_cli"];

echo "<tr><td align=\"left\">$nom</td> <td align=\"left\">$idCli</td> <td align=\"left\">$tel</td> <td align=\"left\">$NF - $PRE</td> <td align=\"left\">$TOT_FAC</td> <td align=\"left\">$fechaFAC</td> <th align=\"left\">$mora</th></tr>";
	
}
?>

</tbody>
</table>
<input id="butt_gfv" type="button" value="DEJAR DE VER ESTO" name="boton" onClick="disable_ban_list_warn();" class=" uk-button uk-button-success uk-button-large" />

<?php
	}
	else echo "0";

}///////////// FIN IF
else echo "0";

?>