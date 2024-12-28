<?php
require_once("Conexxx.php");
$fechaI=r('fechaI');
$fechaF=r('fechaF');
?>

<!DOCTYPE html  >
<html  >
<head>
<?php  
require_once("HEADER_UK.php"); 
require_once("IMP_HEADER.php"); 
 
?></head>

<body>
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:relative; font-size:14px; padding:20px; padding-left:50px;" class=" " >
Fecha Arqueo: <?PHP echo fecha($FechaHoy)."     Hora: ".$hora ?>
<br>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="font-size:24px; font-weight:bold;">
Desde: <?PHP echo fecha($_SESSION['fechaI'])  ?>
</td>
<td style="font-size:24px; font-weight:bold;"> Hasta: <?php echo fecha($_SESSION['fechaF']) ?>
</td>
</tr>
</table>
<?php
$sql="SELECT SUM(tot) as tot FROM fac_remi WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND nit=$codSuc AND tipo_fac='remision2' AND tipo_cli!='Traslado' AND tipo_fac!='remision' AND anulado!='ANULADO'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$TOT_REM=$row["tot"];

$sql="SELECT SUM(tot) as tot FROM fac_remi WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND nit=$codSuc AND tipo_fac='remision2' AND tipo_cli!='Traslado' AND tipo_fac!='remision' AND anulado='ANULADO'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$TOT_REM_ANULA=$row["tot"];
?>
<h2 align="left" class="uk-text-primary uk-text-bold"><b>TOTAL REMISIONES  &nbsp;<span class="uk-badge uk-badge-success" style="font-size:18px;"><?php echo money2($TOT_REM);?></span></b></h2>
<h2 align="left" class="uk-text-primary uk-text-bold"><b>REMISIONES ANULADAS &nbsp;<span class="uk-badge uk-badge-warning" style="font-size:18px;"><?php echo money2($TOT_REM_ANULA);?></span></b></h2>

<div class="uk-grid " >

<div class="uk-width-1-1">
<input type="button" value="IMPRIMIR" name="boton" onClick="imprimir()" />
</div>


</div>

</div>
<?php 
 
require_once("FOOTER_UK.php"); 
?>
</body>
</html>