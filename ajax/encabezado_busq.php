<thead class="uk-text-large">
<tr >
  <th width="200" class="uk-hidden-touch">Ref.</th>
  <?php if($MODULES["APLICA_VEHI"]==1){?>
  <th width="200" class="uk-hidden-touch">Aplicaci&oacute;n </th>
  <?php }?>
  
  <?php if($MODULES["DES_FULL"]==1){?>
  <th width="200" class="uk-hidden-touch">Aplicaci&oacute;n </th>
  <?php }?>
  
  <th width="200" class="uk-hidden-touch">Cod.</th>
  <th width="200" class="uk-text-large">Descripci&oacute;n</th>
  <th width="100" class="uk-hidden-touch">Presentaci&oacute;n</th>
  <th width="200" class="uk-hidden-touch"><?php echo $global_text_fabricante?></th>
  <?php if($usar_fecha_vencimiento==1){ ?>
  <th width="200" class="uk-hidden-touch">Fecha Vencimiento</th>
  <?php }?>
  
  <?php if($usar_color==1){ ?>
  <th width="200" class="uk-hidden-touch">Color</th>
  <?php }?>
  <?php if($usar_talla==1){ ?>
  <th width="200" class="uk-hidden-touch">Talla</th>
  <?php }?>
  
  <th width="100">Cant.</th>
  
  <th width="200" class="uk-hidden-touch">IVA</th>
  <!--<th width="200" class="uk-hidden-touch">Imp. Saludable</th>-->
  <th width="200" class="uk-text-large">PVP</th>
  <?php if($MODULES['PVP_MAYORISTA']==1){ ?>
  <th width="200" class="uk-hidden-touch">PVP Mayorista</th>
  <?php }?>
  <?php if($MODULES['PVP_CREDITO']==1){ ?>
  <th width="200" class="uk-hidden-touch">PVP Credito</th>
  <?php }?>
  <th width="200" class="uk-hidden-touch">Clasificaci&oacute;n</th>
  
  
</tr>
</thead>