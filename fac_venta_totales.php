
<?php
$HIDDEN_SUBS="uk-hidden";
$HIDDEN_SUBS2="uk-hidden";
if($usar_iva==1 ){$HIDDEN_SUBS="";}
if($fac_ven_verSubtotales==1){$HIDDEN_SUBS2="";}

?>
<table align="right" width="100%">
<tr>
<td rowspan="<?php if($MODULES["ANTICIPOS"]==1){echo "15";}else if($fac_ven_verSubtotales==1){echo "20";}else {echo "14";} ?>">



<table align="right" width="100%">
<tr id="desc">        
<td colspan="" rowspan="<?php if($MODULES["ANTICIPOS"]==1){echo "";}else if($fac_ven_verSubtotales==1){echo "";}else {echo "";} ?>" align="center" width="100px"  class=""> 
<div align="left">
    <textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:300px;" class="save_fc uk-hidden"></textarea>

<textarea class="save_fc" name="nota_fac" id="nota_fac"   cols="40" rows="6" placeholder="NOTAS" style="-webkit-border-radius:19px;-moz-border-radius:19px;border-radius:19px;border:6px solid rgb(201, 38, 38);"><?php echo "$nota_fac" ?></textarea>
</div>
</td> 

</tr>
<tr id="" class="<?php  if(!$usarNotaDomicilios) {echo "uk-hidden";}?>">        
<td colspan=""  align="center" width="100px"  class="<?php  if(!$usarNotaDomicilios) {echo "uk-hidden";}?>"> 
<div align="left">
<textarea class="save_fc" name="nota_domicilio" id="nota_domicilio"   cols="40" rows="4" placeholder="DATOS DOMICILIO" style="-webkit-border-radius:19px;-moz-border-radius:19px;border-radius:19px;border:6px solid rgb(201, 38, 38);"><?php echo "" ?></textarea>
</div>
</td> 

</tr>
</table>
</td>
</tr>

<!-- SUBS IVA DETALLADO -->
<tr class="<?php echo $HIDDEN_SUBS2; ?>">
<td  align="right" colspan=""   class="<?php echo $HIDDEN_SUBS2; ?>"></td>
<td align="right" style="font-size:12px;" class="<?php echo $HIDDEN_SUBS2; ?>">NO GRAVADAS: 
<input style="" name="exc" readonly="readonly" id="EXCENTOS" type="text" value="<?php echo money("$IVA") ?>" class="save_fc uk-form-small facVeNumeric"/>
</td>
</tr>

<tr class="<?php echo $HIDDEN_SUBS2; ?>">
<td  align="right" colspan=""   class="<?php echo $HIDDEN_SUBS2; ?>"></td>
<td align="right" style="font-size:12px;" class="<?php echo $HIDDEN_SUBS2; ?>">BASE 5%: 
<input style="" name="base5" readonly="readonly" id="base5" type="text" value="<?php echo money("$IVA") ?>" class="save_fc uk-form-small facVeNumeric"/>
</td>
</tr>
<tr class="<?php echo $HIDDEN_SUBS2; ?>">
<td  align="right" colspan=""   class="<?php echo $HIDDEN_SUBS2; ?>"></td>
<td align="right" style="font-size:12px;" class="<?php echo $HIDDEN_SUBS2; ?>">IVA 5%: 
<input style="" name="iva5" readonly="readonly" id="iva5" type="text" value="<?php echo money("$IVA") ?>" class="save_fc uk-form-small facVeNumeric"/>
</td>
</tr>

<tr class="<?php echo $HIDDEN_SUBS2; ?>">
<td  align="right" colspan=""   class="<?php echo $HIDDEN_SUBS2; ?>"></td>
<td align="right" style="font-size:12px;" class="<?php echo $HIDDEN_SUBS2; ?>">BASE 19%: 
<input style="" name="base19" readonly="readonly" id="base19" type="text" value="<?php echo money("$IVA") ?>" class="save_fc uk-form-small facVeNumeric"/>
</td>
</tr>
<tr class="<?php echo $HIDDEN_SUBS2; ?>">
<td  align="right" colspan=""   class="<?php echo $HIDDEN_SUBS2; ?>"></td>
<td align="right" style="font-size:12px;" class="<?php echo $HIDDEN_SUBS2; ?>">IVA 19%: 
<input style="" name="iva19" readonly="readonly" id="iva19" type="text" value="<?php echo money("$IVA") ?>" class="save_fc uk-form-small facVeNumeric"/>
</td>
</tr>




<!-- SUBS IVA RESUMEN -->
<td align="right" colspan=""   class="<?php echo $HIDDEN_SUBS; ?>"></td>
<td align="right" colspan="" class="<?php echo $HIDDEN_SUBS; ?>" style="font-size:32px;">SubTotal:
<input  id="SUB" type="text" readonly="" value="<?php echo money("$SUB") ?>"   name="sub" class="save_fc uk-form-small facVeNumeric" style=""/>
<input type="hidden" name="SUB" value="0" id="SUBH" class="save_fc" />
<input readonly name="dcto" id="DESCUENTO" type="hidden" value="" onKeyUp="" class="save_fc" />
</td>
</tr>

<tr>
<td  align="right" colspan=""   class="<?php echo $HIDDEN_SUBS; ?>"></td>
<td align="right" style="font-size:32px;" class="<?php echo $HIDDEN_SUBS; ?>">I.V.A: <input style="" name="iva" readonly="readonly" id="IVA" type="text" value="<?php echo money("$IVA") ?>" class="save_fc uk-form-small facVeNumeric"/>
<input id="IVAH" type="hidden" name="IVA" value="0" class="save_fc" /></td>
</tr>



<tr  class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?>">
<td  align="center" colspan="" class="tot_fac">TOTAL (Pesos):
<input class="save_fc uk-button uk-button-success <?php if($usar_bsf!=1)echo "uk-hidden"; ?>" data-uk-toggle="{target:'.bsf'}" value="BsF" type="button"   onMouseDown="//change($('#entrega'));"/>
</td>
<td colspan="" align="right"><input id="TOTAL" type="text" readonly="" value="<?php echo money("$TOT") ?>" name="tot" class="save_fc uk-form-small facVeNumeric" style="" />
</td>
</tr>


<tr id="bsf"  class="<?php if($usar_bsf!=1) echo "uk-hidden" ; ?> bsf">
<td align="right" style="font-size:24px;font-family:Georgia,serif;color:rgb(255, 64, 0);font-weight:bold;font-style:italic;">TOTAL (BsF)</td>
<td align="right"><input id="TOTAL_BSF" type="text" readonly="" value="<?php echo money("$TOT_BSF") ?>" name="totB"  class="save_fc uk-form-small facVeNumeric" style=""/>
</td>
</tr>




<?php
if($usar_iva==0){?>
 
<input name="iva" readonly="readonly" id="IVA" type="hidden" value="" class="save_fc" />
<input id="IVAH" type="hidden" name="IVA" value="0" class="save_fc" />
<?php }?>
 

<tr id="anticipo_bono_tr" style="<?php echo $hide;?>">
<th style=" background-color: #000; color:#FFF;font-size:24px; padding:1px;">Dcto: 
<input class="save_fc" placeholder="%" id="DCTO_PER" type="text"  value="" name="DCTO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO2'));" onBlur="dctoB();"/>
<input class="save_fc" name="dcto2" id="DESCUENTO2" type="text" value="<?php echo "$DESCUENTO" ?>" onKeyUp="dctoB();" />
</th>
<?php if($MODULES["ANTICIPOS"]==1){ ?>
 
<td  align="right" colspan="" style="background-color:#000; color:#FFF; font-size:24px;">Anticipo/Bono: <input id="anticipo" type="text"  value="<?php echo money("$abon_anti") ?>" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));" class="save_fc uk-form-small facVeNumeric"  style=""  readonly/>
<input type="hidden" name="num_exp" id="num_exp" value="<?php echo "$num_exp" ?>" class="save_fc"  />
</td>
 
<?php } else{?>
<input id="anticipo" type="hidden"  value="0" name="anticipo" onKeyUp="change($(this));" onBlur="change($(this));"  class="save_fc" readonly/>
<input type="hidden" name="num_exp" id="num_exp" value="0" class="save_fc"  />
<?php } ?>
</tr>


<tr id="anticipo_bono_tr" class="<?php if($rolLv!=$Adminlvl && !val_secc($id_Usu,"dcto_despues_iva")){echo "uk-hidden";}?>">
<td style=" background-color: #000; color:#FFF;font-size:24px; padding:1px;">Dcto Despu&eacute;s IVA: 
<input class="save_fc" placeholder="%" id="DCTO_IVA_PER" type="text"  value="" name="DCTO_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#DESCUENTO_IVA'));" onBlur="tot();"/>
</td>
<td align="right" >

<input class="save_fc save_fc uk-form-small facVeNumeric" name="DESCUENTO_IVA" id="DESCUENTO_IVA" type="text" value="<?php echo "$DESCUENTO" ?>" onKeyUp="dctoB();"  style=""  />
</td>
</tr>


<tr id="total_pagar_tr">
<td  align="right" colspan="2" style="font-size:42px;" ><b>VALOR A PAGAR: </b> <input  id="TOTAL_PAGAR" type="text" value=""  name="TOTAL_PAGAR"  readonly class="save_fc  facVeNumericTots"/></td>
</tr>
        

      
<tr  class="PAGO_PESOS" style="font-size:18px;font-weight:bold;	background-color: #6C6;">
<td  align="right" colspan="" id="pago_pesos_tr">
<div class="uk-grid">
<div class="uk-width-6-10">

<i class="uk-icon-cc-mastercard <?php echo $ukIconSize ?>"></i>
<select id="form_pago" name="form_pago" onChange="creco(this.value,'credito','contado');banDcto();call_tot();"  class="save_fc uk-text-primary  uk-text-bold uk-form-success  uk-form-large" style="width:120px;"><option value=""></option>
<option value="Contado"  selected>Contado</option>
<option value="Cheque"  >Cheque</option>
<?php if($rolLv>=$Adminlvl|| val_secc($id_Usu,"vende_credito")){?>
<option value="Credito" <?php if($ventas_credito==1){echo "selected";}?>>Cr&eacute;dito</option>
<?php }?>
<option value="Tarjeta Credito" >Tarjeta Credito</option>
<option value="Tarjeta Debito" >Tarjeta Debito</option>
<option value="Transferencia Bancaria" >Transferencia Bancaria</option>
</select>
 	
</div>
<div class="uk-width-6-10 <?php if($impuesto_bolsas!=1){echo "uk-hidden";}?>">

<i class="uk-icon-shopping-bag <?php echo $ukIconSize ?>"></i>
<input style="font-size:20px; width:80px; " id="bolsas" type="text"  value="" name="bolsas" onKeyUp="call_tot();"  class="save_fc uk-form-large uk-form-success  "  />
 <br />
</div>

</div>
</td>
<td align="right">
<div class="uk-grid" style="width:400px;">
<div class="uk-width-4-10" style="font-size:20px;">
EFECTIVO/<BR />TARJETA 
</div>
<div  class="uk-width-6-10">

<input PLACEHOLDER="PAGO"  id="entrega" type="text"  value="" name="entrega" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="save_fc  uk-form-success  facVeNumericTots"  />
</div>
</div>
</td>


</tr>


<tr id="pago_tarjetas_pesos_tr" class="PAGO_PESOS2<?php if($MODULES["PAGO_EFECTIVO_TARJETA"]!=1)echo " uk-hidden"; ?>">
<td style="font-size:24px;" align="right">Pago Excedente en Tarjeta</td>
<td  align="right" colspan="" class="PAGO_PESOS2" style="font-size:24px;"> <input id="entrega3" type="text"  value="" name="entrega3" onKeyUp="change($(this));mover($(this),$('#cod'),$(this),0);" onBlur="//change($(this));" data-uk-tooltip title="SOLO APLICA PARA PAGO PARCIAL (La otra parte debe ser a acontado)" class="save_fc uk-form-small facVeNumericTots"  />
</td>
 

</tr>

<tr>

<td  align="right" colspan="2" style="font-size:40px; height:49px;"><b>CAMBIO</b> 
<input   id="cambio" type="text"  value="0" name="cambio" readonly="readonly" class="save_fc  facVeNumericTots uk-text-primary uk-text-bold " />
<div id="cambio_pesos" style="  color:#F00"></div>
</td>

</tr>

<tr class="<?php if($usar_bsf!=1)echo "uk-hidden"; ?> bsf">

<td   ></td>
<td align="right" colspan="" style="font-size:20px;">Pago Efectivo(bsF):
<input id="entrega2" type="text"  value="" name="entrega2" onKeyUp="mover($(this),$('#cod'),$(this),0);change($(this));" onBlur="change($(this));" class="save_fc uk-form-small facVeNumeric"  />
</td>
</tr>
</table>   
 