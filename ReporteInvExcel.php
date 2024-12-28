<?php
require_once("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
excel("Inventario  $hoy");

require_once("ReporteInv_vars.php");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>



<table cellpadding=0 cellspacing=0 border=1 style="width:21.5cm; position:relative; font-size:10px;">
<thead>
 <tr>
 <th colspan="9">


 <?php echo "Inventario $NOM_NEGOCIO ".$_SESSION["municipio"]." ".$hoy ;
 
 
 $trStyle='bgcolor="#4B8EFA" style="color:#FFF" class="ui-btn-active"';
 ?> 
 </th>
 
 </tr>
<tr >      
<th width="110" <?php echo $trStyle?>>Ref</th>
<th width="110" <?php echo $trStyle?>>Cod.</th>
<th width="210" <?php echo $trStyle?>>Descripci&oacute;n</th>
<th width="100" <?php echo $trStyle?>>Presenta</th>
<th width="100" <?php echo $trStyle?>>Clase</th>
<!-- <th width="100" <?php echo $trStyle?>>Marca/Fab</th>-->
<?php if($usar_talla==1){?><th width="30" <?php echo $trStyle?>>Talla</th><?php }?>
<?php if($usar_color==1){?><th width="30" <?php echo $trStyle?>>Color</th><?php }?>
<?php if($usar_ubica==1){?><th width="30" <?php echo $trStyle?>>Ubica</th><?php }?>
<?php if($usar_fecha_vencimiento==1){?><th width="60" <?php echo $trStyle?>>Fecha Venci.</th><?php }?>




<th width="30" <?php echo $trStyle?>>Costo+IVA</th>
<!--
<th width="30">Util%</th>-->
<th width="30" <?php echo $trStyle?>>PvP</th>
<!--
<th width="30">Tot. Costo</th>
-->
<th width="30" <?php echo $trStyle?>>Cant</th>
<!--<th width="60">Ajuste</th>-->
</tr>
 
     </thead>   
     <tbody>              
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
			
			$unidades=$row['unidades_frac'];
			$feVenci=$row['fecha_vencimiento'];
			$talla=$row['talla'];
			$color=$row['color'];
			$iva=$row["iva"];
			$costo=$row['costo']*(1+($iva/100));
			$cant=$row["exist"]*1;
			
			$factor=($unidades/$frac)+$cant;
			$ubica=$row["ubicacion"];
				$fab=$row["fab"];
				$presentacion=$row["presentacion"];
			 
			
?>
 
<tr  bgcolor="#FFF" >
<td width="80" align="left" height="20">'<?php echo $id_inter; ?>'</td>
<td width="80" align="left" height="20">'<?php echo $id; ?>'</td>
<td width=""><?php echo $des; ?></td>
<td width=""><?php echo $presentacion; ?></td>
<td width=""><?php echo $clase; ?></td>
<!--<td width=""><?php echo $fab; ?></td> -->


<?php if($usar_ubica==1){?><td width="40" align="left"><?php echo $ubica; ?></td><?php }?>


<?php if($usar_talla==1){?><td width="" align="right"><?php echo $talla; ?></td><?php }?>

<?php if($usar_color==1){?><td width="" align="right"><?php echo $color; ?></td><?php }?>

<?php if($usar_fecha_vencimiento==1){?><td width="60" align="right"><?php echo $feVenci; ?></td> <?php }?>

<td width="80" align="right"><?php echo money3($costo); ?></td>
<!--
<td width="30" align="right"><?php echo util($pvp,$costo,$iva,"per"); ?></td>-->
<td width="80" align="right"><?php echo money3($pvp); ?></td>


<!--
<td width="80"><?php //echo money3($costo*$factor); ?></td>
<td width="80"><?php //echo money3($costo*$row['exist']); ?></td>--> 
<td width="20" align="right"><?php 
$showUni=";$unidades";
if($unidades==0)$showUni="";
echo "$row[exist] $showUni"; ?></td> 

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
   </tbody>
</table>
</body>
</html>