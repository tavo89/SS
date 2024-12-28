<div class="uk-width-3-10  push-1">
<table style="font-size:12px; width:200px;" cellspacing="0">
<tr>
<th colspan="5"><?php echo "$ARQ_TITULO"?></th>
</tr>
<?php if($TOT_VENTAS0516[0][0]!=0){ ?>
<tr>
<td colspan="4"><?php echo $fac_ven_etiqueta_nogravados;?>:</td><td> <span ><?PHP  echo money3(redondeo($TOT_VENTAS0516[0][0])) ?></span></td>
</tr>
<?php } ?>
<?php if($TOT_VENTAS0516[5][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][1]) ?></span></td>
</tr>
<tr>
<td colspan="4">VALOR IVA 5%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[5][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[10][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 10%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[10][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 10%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[10][2]) ?></span></td>
</tr>
<?php } ?>


<?php if($TOT_VENTAS0516[16][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 16%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[16][2]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[19][1]!=0){ ?>
<tr>
<td colspan="4">BASE IVA 19%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[19][1]) ?></span></td>
</tr>
<tr>

<td colspan="4">VALOR IVA 19%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[19][2]) ?></span></td>
</tr>
<?php } ?>


<?php if($TOT_VENTAS0516[8][1]!=0){ ?>
<tr>
<td colspan="4">Imp. Consumo 8%:</td><td><span ><?PHP echo money3($TOT_VENTAS0516[8][1]) ?></span></td>
</tr>
<?php } ?>

<?php if($TOT_VENTAS0516[20][1]!=0){ ?>
<tr>
<td colspan="4">Imp. Bolsas :</td><td><span ><?PHP echo money3($TOT_VENTAS0516[20][1]) ?></span></td>
</tr>
<?php } ?>


<tr>
<td colspan="4">TOTAL:</td><td> <span ><?PHP echo money3(redondeo($TOT_VENTAS0516[1][1])) ?></span></td>
</tr>

</table>
</div>