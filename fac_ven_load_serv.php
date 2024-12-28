<?php
//$i=0;
while($row=$rs->fetch())
{
	
	$serv=$row["serv"];
	$notaServ=$row["nota"];
	$idServ=$row["id_serv"];
	$codServ=$row["cod_serv"];
	$ivaServ=$row["iva"];
	$pvpServ=$row["pvp"];
	$idTec=$row["id_tec"];
	$anchobanda=$row["anchobanda"];
	$estrato=$row["estrato"];
	$tipo_cliente=$row["tipo_cliente"];
	$dctoServ=0;
	
	

?>
<tr>

<!-- ID SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="id_serv<?php echo $i ?>" name="id_serv<?php echo $i ?>" type="text"  value="<?php echo "$idServ" ?>" style=" width:70px" readonly>

<input class="art<?php echo $i ?>" id="<?php echo $i ?>cant_" type="hidden" name="cant_<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "1" ?>"   style=" width:50px">

<input class="art<?php echo $i ?>" id="<?php echo $i ?>cant_L" type="hidden" name="cant_L<?php echo $i ?>" size="5" maxlength="20" value="<?php echo "1" ?>"   style=" width:50px">
</td>



<!-- COD SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="cod_serv<?php echo $i ?>" name="cod_serv<?php echo $i ?>" type="text"  value="<?php echo "$codServ" ?>" style=" width:70px" readonly></td>

<!-- SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="serv<?php echo $i ?>" name="serv<?php echo $i ?>" type="text"  value="<?php echo "$serv" ?>" style=" width:200px" readonly></td>

<?php if($MODULES["modulo_planes_internet"]!=1){?>

<!-- NOTA -->
<td class="art<?php echo $i ?>">
<textarea style=" width:150px" cols="10" rows="2" class="art<?php echo $i ?>" name="nota<?php echo $i ?>" id="nota<?php echo $i ?>" onBlur="<?php echo "$saveFunct"; ?>(<?php echo $i; ?>);"><?php echo "$notaServ" ?></textarea>
</td>

<!-- IVA SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="iva_<?php echo $i ?>" name="iva_serv<?php echo $i ?>" type="text"  value="<?php echo "$ivaServ" ?>" style=" width:70px" readonly></td>




<!-- val Uni -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>" name="val_serv<?php echo $i ?>" type="text" onkeyup="puntoa($(this));valor_t(<?php echo "$i" ?>);" value="<?php echo money("$pvpServ") ?>" onblur="<?php echo "$saveFunct"; ?>(<?php echo $i; ?>);" style=" width:70px">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>HH" name="val_u<?php echo $i ?>" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>H" name="val_u<?php echo $i ?>H" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art0" id="valMin<?php echo $i ?>" type="hidden" name="valMin<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$pvpServ" ?>" style=" width:30px">
<input class="art<?php echo $i ?>" id="val_orig<?php echo $i ?>" name="val_orig<?php echo $i ?>" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art<?php echo $i ?>" id="val_cre<?php echo $i ?>H" name="val_cre<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$pvpServ" ?>">

</td>

<!-- sub tot -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>" name="val_totServ<?php echo $i ?>" type="text" readonly="" value="<?php echo money("$pvpServ") ?>" style=" width:70px" >
</td>

<!-- TECNICO -->
<td class="art<?php echo $i ?>">
<select class="art<?php echo $i ?>" name="tec_serv<?php echo $i ?>" id="tec_serv<?php echo $i ?>" onChange="<?php echo "$saveFunct"; ?>(<?php echo $i; ?>);">
<?php  echo tecOpt($idTec); ?>
</select>
</td>

<?php }
else {
?>

<!-- velocidad Plan -->
<td class="art<?php echo $i ?>">
<input class="uk-form-small art<?php echo $i ?> "  value="<?php echo "$anchobanda" ?>" type="text" id="anchobanda<?php echo $i ?>" name="anchobanda<?php echo $i ?>" placeholder="Ancho de Banda"   onBlur="<?php echo "$saveFunct($i)"; ?>;"  />
</td>


<!--  tipo cliente-->
<td class="art<?php echo $i; ?>" align="center"><input class="uk-form-small art<?php echo $i; ?> "  value="<?php echo "$tipo_cliente" ?>" type="text" id="tipo_cliente<?php echo $i; ?>" name="tipo_cliente<?php echo $i; ?>" placeholder="Tipo Cliente"   onChange="<?php echo "$saveFunct($i)"; ?>;"  /></td>

<!--  estrato plan-->
<td class="art<?php echo $i; ?>'" align="center"><input class="uk-form-small art<?php echo $i; ?> "  value="<?php echo "$estrato" ?>" type="text" id="estratoPlan<?php echo $i; ?>" name="estratoPlan<?php echo $i; ?>" placeholder="Estrato"   onChange="<?php echo "$saveFunct($i)"; ?>;"  /></td>

<!--  nota -->
<td class="art<?php echo $i; ?>'" align="center"><input class="uk-form-small art<?php echo $i; ?> "  value="<?php echo "$notaServ" ?>" type="text" id="nota<?php echo $i; ?>" name="nota<?php echo $i; ?>" placeholder=""   onChange="<?php echo "$saveFunct($i)"; ?>;"  /></td>

<!-- IVA SERV -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="iva_<?php echo $i ?>" name="iva_serv<?php echo $i ?>" type="text"  value="<?php echo "$ivaServ" ?>" style=" width:70px" readonly></td>



<!-- dcto -->
<td class="art<?php echo $i ?>" align="center">
<input class="art<?php echo $i ?>" id="dctoServ_<?php echo $i ?>" type="text" name="dctoServ_<?php echo $i ?>" size="5" maxlength="5" value="<?php echo $dctoServ*1 ?>" onkeyup="valor_t(<?php echo $i ?>);" onblur="" onChange="dct_serv(<?php echo $i ?>);save_fv(<?php echo $i; ?>);" style=" width:50px" >
 
</td>

<!-- val Uni -->
<td class="art<?php echo $i ?>">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>" name="val_serv<?php echo $i ?>" type="text" onkeyup="puntoa($(this));valor_t(<?php echo "$i" ?>);" value="<?php echo money("$pvpServ") ?>" onblur="<?php echo "$saveFunct"; ?>(<?php echo $i; ?>);" style=" width:70px">

<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>HH" name="val_u<?php echo $i ?>" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art<?php echo $i ?>" id="val_u<?php echo $i ?>H" name="val_u<?php echo $i ?>H" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art0" id="valMin<?php echo $i ?>" type="hidden" name="valMin<?php echo $i ?>" size="5" maxlength="5" value="<?php echo "$pvpServ" ?>" style=" width:30px">
<input class="art<?php echo $i ?>" id="val_orig<?php echo $i ?>" name="val_orig<?php echo $i ?>" type="hidden" value="<?php echo "$pvpServ" ?>">
<input class="art<?php echo $i ?>" id="val_cre<?php echo $i ?>H" name="val_cre<?php echo $i ?>H" type="hidden" readonly="" value="<?php echo "$pvpServ" ?>">

</td>

<!-- sub tot -->
<td class="art<?php echo $i ?>">
<input class="art<?php echo $i ?>" id="val_t<?php echo $i ?>" name="val_totServ<?php echo $i ?>" type="text" readonly="" value="<?php echo money("$pvpServ") ?>" style=" width:70px" >
</td>


<?php }?>

<td class="art<?php echo $i ?>">
<img onmouseup="<?php echo $eliFunct ?>($(this).prop('class'))" class="<?php echo $i ?>" src="Imagenes/delete.png" width="31px" heigth="31px">
</td>
</tr>
<script language="javascript1.5" type="text/javascript">
			   
 			   cont++;
			   ref_exis++;
			  
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
</script>
<?php	
$i++;
}



?>