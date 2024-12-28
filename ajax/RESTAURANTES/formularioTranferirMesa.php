<?php
include("../../Conexxx.php");

$ID_mesa=r("id_mesa");
$listaMesasDisonibles = '<select name="listaMesasDisonibles" id="listaMesasDisonibles" class="">';

try { 

$sql="SELECT * FROM mesas WHERE estado!='Ocupada' ";
$rs=$linkPDO->query($sql);
while ($row = $rs->fetch()) {
  if(is_numeric ($row["num_mesa"]) || buscaString('VENTAS',strtoupper($row["num_mesa"])) ){
      $listaMesasDisonibles.='<option value="'.$row['id_mesa'].'">['.$row['id_mesa'].'] '.$row['num_mesa'].'</option>';
  }
}
$listaMesasDisonibles.="</select>";
?>
<form class="uk-form uk-form-horizontal">
<div class="uk-form-row">
<label class="uk-form-label">Mesas Disponibles</label>
<input type="hidden" name="idMesaOrigen" value="<?php echo $ID_mesa;?>">
<?php echo  $listaMesasDisonibles; ?>
</div>
<input type="button" class="uk-button uk-width-1-1 uk-button-primary" name="botonGuardar" id="botonGuardar" value="Guardar" onClick="ejecutaTransferencia('<?php echo $ID_mesa;?>',$('select[name=listaMesasDisonibles] option').filter(':selected').val())">
<input type="button" class="uk-button uk-width-1-1" name="botonCerrar" id="botonCerrar" value="Cerrar" onClick="$('#modal').remove();remove_pop($('#modal'));">
</form>
<?php


}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

