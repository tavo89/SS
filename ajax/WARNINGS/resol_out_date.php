<?php
include("../../Conexxx.php");
 
$POS_serial=serial_fac("factura venta","POS");
$COM_serial=serial_fac("credito","COM");


$sql="SELECT * FROM seriales WHERE nit_sede=$codSuc";
$rs=$linkPDO->query($sql);
$serialesFAC[]=0;
while($row=$rs->fetch()){
$serialesFAC[$row["seccion"]]=$row["serial_sup"];
}

$toleranciaPOS=($serialesFAC["factura venta"])*0.05;
$toleranciaCOM=($serialesFAC["credito"])*0.05;

$avalNumPOS=$serialesFAC["factura venta"]-$POS_serial;
$avalNumCOM=$serialesFAC["credito"]-$COM_serial;


$sql="SELECT *,DATEDIFF(NOW(),fecha_resol_contado) as diasPOS,DATEDIFF(NOW(),fecha_resol_credito) as diasCOM FROM sucursal WHERE cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$fechaPOS=$row["fecha_resol_contado"];
$fechaCOM=$row["fecha_resol_credito"];

$diasCadPOS=$row["diasPOS"];
$diasCadCOM=$row["diasCOM"];
	
	}

//echo "avalPOS:$avalNumPOS-->T:$toleranciaPOS , alaCOM:$avalNumCOM-->T:$toleranciaCOM";
if(($diasCadCOM>700 || $diasCadPOS>700) ||  ($avalNumPOS<=$toleranciaPOS|| $POS_serial=="LIMITE DE FACTURAS ALCANZADO") || ($avalNumCOM<=$toleranciaCOM || $COM_serial=="LIMITE DE FACTURAS ALCANZADO")){
//if(1){
$sql="SELECT *,DATEDIFF(NOW(),fecha_resol_contado) as diasPOS,DATEDIFF(NOW(),fecha_resol_credito) as diasCOM FROM sucursal WHERE cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	if($see_warn_resol==1){
?>
<table class="uk-table">
<thead>
<tr>
<th>Tipo Resoluci&oacute;n</th>
<th># Resoluci&oacute;n</th>
<th>Prefijo</th>
<th>Rango Numeraci&oacute;n</th>
<th>Numeraci&oacute;n Actual</th>
<th>Fecha</th>
<th>D&iacute;as Vencimiento</th>
 
</tr>
</thead>
<tbody>
<?php
$resolContado=$row["resol_contado"];
$resolCredito=$row["resol_credito"];
$prefijoPOS=$row["cod_contado"];
$prefijoCOM=$row["cod_credito"];
$fechaPOS=$row["fecha_resol_contado"];
$fechaCOM=$row["fecha_resol_credito"];
$rangoPOS=$row["rango_contado"];
$rangoCOM=$row["rango_credito"];
$diasCadPOS=$row["diasPOS"];
$diasCadCOM=$row["diasCOM"];


$warnFeCOM="<div class=\"uk-badge uk-badge-danger\" > !</div>";
if($diasCadCOM<700)$warnFeCOM="";

$warnNumCOM="";
if($avalNumCOM>=$toleranciaCOM  && $COM_serial!="LIMITE DE FACTURAS ALCANZADO")$warnNumCOM="";
else $warnNumCOM="<div class=\"uk-badge uk-badge-danger\" > !</div>";


$warnFePOS="<div class=\"uk-badge uk-badge-danger\" > !</div>";
if($diasCadPOS<700)$warnFePOS="";

$warnNumPOS="";
if($avalNumPOS>=$toleranciaPOS && $POS_serial!="LIMITE DE FACTURAS ALCANZADO")$warnNumPOS="";
else $warnNumPOS="<div class=\"uk-badge uk-badge-danger\" > !</div>";

echo "<tr><td align=\"left\">POS</td> <td align=\"left\">$resolContado</td> <td align=\"left\">$prefijoPOS</td> <td align=\"left\">$rangoPOS</td> <td align=\"left\">$POS_serial $warnNumPOS</td> <td align=\"left\">$fechaPOS</td><td align=\"left\">$diasCadPOS $warnFePOS</td>  </tr>";


echo "<tr><td align=\"left\">COMPUTADOR</td> <td align=\"left\">$resolCredito</td> <td align=\"left\">$prefijoCOM</td> <td align=\"left\">$rangoCOM</td> <td align=\"left\">$COM_serial $warnNumCOM</td> <td align=\"left\">$fechaCOM</td><td align=\"left\">$diasCadCOM $warnFeCOM</td>  </tr>";



?>

</tbody>
</table>
<input id="butt_gfv" type="button" value="DEJAR DE VER ESTO" name="boton" onClick="disable_resol_warn();" class=" uk-button uk-button-success uk-button-large" />

<?php
	}
	else echo "0";
	}
	else echo "0";

}///////////// FIN IF
else echo "0";

?>