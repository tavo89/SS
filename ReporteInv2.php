<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
excel("Inventario $NOM_NEGOCIO $hoy");

require_once("ReporteInv_vars.php");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>

<h2><?php echo "Inventario $NOM_NEGOCIO ".$_SESSION["municipio"]." ".$hoy ?></h2>

<table cellpadding=0 cellspacing=0 border=1>
<tr style="font-size:14px;">
<tr bgcolor="#4B8EFA" style="color:#FFF" class="ui-btn-active">      
<th width="110">Ref</th>
<th width="110">Cod.</th>
<th width="210">Descripci&oacute;n</th>
<!--<th width="30">Ubica</th>-->
<?php if($usar_talla==1){?><td>Talla</td><?php }?>
<?php if($usar_color==1){?><td>Color</td><?php }?>

<?php if($usar_fecha_vencimiento==1){?><th width="110">Fecha Vencimiento</th><?php }?>

<th width="30">Costo+IVA</th>
<th width="30">Util%</th>
<th width="30">PvP</th>
</tr>
                      
<?php 
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $id_inter = $row["id_glo"]; 
            $des = $row["detalle"]; 
			$clase = $row["id_clase"];
			$id =$row["id_sede"];
			$frac = $row["fraccion"];
			if($frac==0)$frac=1;
			$fab = $row["fab"]; 
			$pvp=$row['precio_v'];
			$costo=$row['costo'];
			$unidades=$row['unidades_frac'];
			$feVenci=$row['fecha_vencimiento'];
			$talla=$row['talla'];
			$color=$row['color'];
			$iva=$row["iva"];
			$cant=$row["exist"]*1;
			
			$ivaPer=($iva/100) +1;
			
			$factor=($unidades/$frac)+$cant;
			//$ubica=$row["ubicacion"];
			 
			
?>
 
<tr  bgcolor="#FFF" style="font-size:10px;">
<td width="80" align="left" height="20">'<?php echo $id_inter; ?>'</td>
<td width="80" align="left" height="20">'<?php echo $id; ?>'</td>
<td width="150"><?php echo $des; ?></td>
<!--<td width="40"><?php echo $ubica; ?></td>-->

<?php if($usar_talla==1){?><td width=""><?php echo $talla; ?></td><?php }?>

<?php if($usar_color==1){?><td width=""><?php echo $color; ?></td><?php }?>

<?php if($usar_fecha_vencimiento==1){?><td width="70"><?php echo $feVenci; ?></td> <?php }?>

<td width="80"><?php echo money3($costo*$ivaPer); ?></td>

<td width="50" align="right"><?php echo util($pvp,$costo,$iva,"per"); ?>%</td>
<td width="80"><?php echo money3($pvp); ?></td>


</tr> 
         
<?php 
         } 
      ?>
 

 
<?php 
     	 
$sql="select sum(exist*costo) tot from inv_inter where nit_scs=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
$TOT=$row['tot'];	
}
      ?>
  <!--        
   <tr>
   <td width="100">Total Costo sin IVA:</td><td colspan=""><?php echo $TOT ?></td>
   </tr>
   -->
</table>
</body>
</html>