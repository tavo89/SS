<?php
$rsAux=$linkPDO->query("SELECT nombre,cod_comision FROM usuarios WHERE cod_comision!='' ORDER BY cod_comision");
while($rowAux=$rsAux->fetch()){
$codComi= $rowAux["cod_comision"];
$nombreComiVen=$rowAux["nombre"];
?>
<option value="<?php echo $codComi ?>"   ><?php echo "$codComi $nombreComiVen"?></option>
<?php } ?> 