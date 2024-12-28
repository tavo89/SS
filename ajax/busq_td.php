<td id="<?php echo $i ?>id_int" class="uk-hidden-touch uk-text-large"><?php echo $id; ?></td>
<?php if($MODULES["APLICA_VEHI"]==1){?>
<td class="uk-hidden-touch"><?php echo $aplicaVehi; ?></td>
<?php }?>

<?php if($MODULES["DES_FULL"]==1){?>
<td  class="uk-hidden-touch"><?php echo $DES_FULL; ?></td>
<?php }?>


<td class="uk-hidden-touch uk-text-large" ><?php echo $cod; ?></td>
<td class="uk-text-large uk-text-bold"><?php echo $des; ?></td>
<td class="uk-hidden-touch"><?php echo $presentacion; ?></td>
<td colspan="" class="uk-hidden-touch"><?php echo $fab ; ?></td> 


<?php if($usar_fecha_vencimiento==1){ ?>
<th class="uk-hidden-touch"><?php echo $fe_ven; ?></th> 
<?php }?>

<?php if($usar_color==1){ ?> <td class="uk-hidden-touch uk-text-large"><?php echo $color; ?></td><?php }?>
<?php if($usar_talla==1){ ?><td class="uk-hidden-touch uk-text-large"><?php echo $talla; ?></td> <?php } ?> 
<td style="font-size: 28px;"><?php if($tipoProducto!="Manufacturado"){echo "$cant;$uni"; }?></td>
<td class="uk-hidden-touch"><?php echo $iva; ?></td>
<!--<td class="uk-hidden-touch"><?php echo $impuesto_saludable; ?></td>-->
<td class="uk-text-bold uk-text-success uk-text-large" style="font-size: 28px;"><?php echo money($pvp); ?></td>
<?php if($MODULES['PVP_MAYORISTA']==1){ ?>
<td width="200" class="uk-text-bold uk-text-success uk-text-large" style="font-size: 28px;"><?php echo money($pvpMayorista); ?></td>
  <?php }?>
  <?php if($MODULES['PVP_CREDITO']==1){ ?>
  <td width="200" class="uk-text-bold uk-text-success uk-text-large" style="font-size: 28px;"><?php echo money($pvpCredito); ?></td>
  <?php }?>
<td class="uk-hidden-touch"><?php echo $clase; ?></td> 
