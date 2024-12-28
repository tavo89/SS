<?php
require_once("Conexxx.php");

$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$clase=$_SESSION["clases"];
$fab=$_SESSION["fabs"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
 <link href="JS/print.css" rel="stylesheet" type="text/css" media="print"> 
<title>COMPROBANTE DE INFORME DIARIO DE VENTAS</title>
 
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body  >

<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>Totales de ventas por T&eacute;cnico </B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr style="font-size:24px; font-weight:bold;">
<td>
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
</table>
</td>
</tr>

</table>
<?php



$total_tec=tot_nomina_tecnicos($fechaI,$fechaF,$codSuc);



?>
<BR /><BR /><BR />
<p align="left" style="font-size:16px;">


</p>

<table frame="box" rules="all" cellpadding="5" cellspacing="3" border="1" style="max-width:21.5cm;">
<thead>
</thead>
<tbody>
<?php
$totVendedores=0;
$idTecnicos[]="";
$NomTecnicos[]="";
$i=0;
$rs=$linkPDO->query("SELECT a.nombre,a.id_usu FROM usuarios a INNER JOIN tipo_usu b ON b.id_usu=a.id_usu WHERE (des='Tecnico' OR des='Mecanico' ) AND cod_su=$codSuc ORDER BY nombre");
while($row=$rs->fetch()){

$nomTec=ucwords(strtolower(htmlentities($row["nombre"], ENT_QUOTES,"$CHAR_SET")));
$idTec=$row["id_usu"];
$idTecnicos[$i]=$idTec;
$NomTecnicos[$i]=$nomTec;
$i++;

}
 
for($i=0;$i<count($idTecnicos);$i++)
{
	
	$sql="SELECT a.id_tec,SUM(a.pvp) as T FROM serv_fac_ven a 
    INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven 
    WHERE a.prefijo=b.prefijo AND DATE(fecha)>='$fechaI' 
    AND DATE(fecha)<='$fechaF' AND b.anulado!='ANULADO' 
    AND id_tec='$idTecnicos[$i]'";
	$rs=$linkPDO->query($sql);
	 ?>
     
     <tr>
     
     <td colspan="6" style="font-size:16px; font-weight:bold;"><?php echo "$NomTecnicos[$i]"; ?></td>
     
     </tr>
     <tr style="font-size:14px; font-weight:bold;"><td>Servicio</td><td>Nota</td><td>Total Facturado</td><td>Veh&iacute;culo</td><td>FECHA</td>
	</tr>
     <?php
	$sql="SELECT a.id_tec,a.pvp,a.serv,a.nota,a.iva,b.placa,b.fecha 
    FROM serv_fac_ven a 
    INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven 
    WHERE a.prefijo=b.prefijo AND DATE(fecha)>='$fechaI' 
    AND DATE(fecha)<='$fechaF' AND b.anulado!='ANULADO' 
    AND id_tec='$idTecnicos[$i]' ORDER BY fecha";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch())
	{
		$carro=vehi($row["placa"]);
		
			?>
            
            
            <tr class="uk-text-small">
            
            <td><?php echo "$row[serv]"; ?></td>
            <td><?php echo "$row[nota]"; ?></td>
            <td align="right"><?php echo money("$row[pvp]"); ?></td>
            <td><?php echo "$carro[modelo] $row[placa] "; ?></td>
             <td><?php echo "$row[fecha]"; ?></td>
            </tr>
            
            <?php 
	}
	
	
	?>
    
    <tr>
     
     <td colspan="6" style="font-size:16px; font-weight:bold;">TOTAL FACTURADO <?php echo "$NomTecnicos[$i] : ".money($total_tec[$idTecnicos[$i]]); ?></td>
     
     </tr>
    <?php
	
}
?>
</tbody>
<tfoot>
<tr style="font-size:20px; font-weight:bold;">
<th>Total Ventas</th><th><?php echo money3(array_sum($total_tec)) ?></th>
</tr>
</tfoot>
</table>

<BR /><BR /><BR />
<hr align="center" width="100%" />
<table width="100%" cellpadding="4" style="font-size:18px;">
<tr>
<td>C./Coordinador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Jefe venta POS:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Contador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
</tr>
</table>

<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 

 </div>
<?php require_once("FOOTER.php"); ?>
</body>
</html>