<?php 
if($MODULES["FACTURACION_ELECTRONICA"]==1){
?>

<div id="CREAR_CLI" class="uk-modal     ">
<div class="uk-modal-dialog" style="width:900px;">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">REGISTRO CLIENTE</h1>
<table cellspacing="0" cellpadding="0">
<tr class="clientes">
<td>Nombre Completo:</td>

<td colspan="3">
<!--<input name="cli" type="text" id="cli" value="<?php echo "$nomCli"; ?>" onKeyUp="//get_nom(this,'add');mover($(this),$('#cod'),$(this),0);" onBlur="busq_cli(this);" class="uk-form-small datos_cli"/>-->
<input name="cli" type="text" id="cli" value="<?php echo $nomCli ?>" class="datos_cli" style="width: 400px;" />
<input name="cliA" type="hidden" id="cliA" value="<?php echo $nomCli ?>" class="datos_cli" />
<?php if($MODULES["ALIAS_CLI"]){?>
<input name="aliasCli" type="text" id="aliasCli" value="" placeholder="ALIAS" style="width:60px;" class="datos_cli">
<?php }?>
</td>
<!--<td>Nombre 2:</td>
<td><input name="snombr" type="text" id="snombr" value="<?php echo "$snombr"; ?>" onChange="" class="datos_cli"/>
<input name="snombrA" type="hidden" id="snombrA" value="<?php echo "$snombr"; ?>" onChange="" class="datos_cli"/></td>
<td>Apellidos:</td>
<td><input name="apelli" type="text" id="apelli" value="<?php echo "$apelli"; ?>" onChange="" class="datos_cli"/>
<input name="apelliA" type="hidden" id="apelliA" value="<?php echo "$apelli"; ?>" onChange="" class="datos_cli"/></td>
-->

</tr>


<tr class="clientes">
<td>Tipo Documento:</td>
<td>
<select name="coddoc" id="coddoc" style="width:200px;" class="datos_cli">

<option value="1"  <?php if($coddoc=="1" || empty($coddoc)){echo "selected";} ?>>Cedula de ciudadania</option>
<option value="3" <?php if($coddoc=="3"){echo "selected";} ?>>NIT</option>
<option value="4" <?php if($coddoc=="4"){echo "selected";} ?>>NIT otro pa&iacute;s</option>
<option value="6" <?php if($coddoc=="6"){echo "selected";} ?>>Registro civil</option>
<option value="7" <?php if($coddoc=="7"){echo "selected";} ?>>Tarjeta de identidad</option>

<option value="8" <?php if($coddoc=="8"){echo "selected";} ?>>Tarjeta de extranjeria</option>
<option value="2" <?php if($coddoc=="2"){echo "selected";} ?>>Cedula de extranjeria</option>

<option value="9" <?php if($coddoc=="9"){echo "selected";} ?>>Pasaporte</option>
<option value="10" <?php if($coddoc=="10"){echo "selected";} ?>>Documento de identificacion extranjero</option>
</select>
</td>
<td>C.C/NIT:</td>
<td>
<input name="ced" type="text" value="<?php echo $idCli ?>" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" class="datos_cli" />

</td>
<td>Clase Persona:</td>
<td>
<select name="claper" id="claper" class="datos_cli">
<option value="1" <?php if($claper=="1"){echo "selected";} ?>>Juridica</option>
<option value="2" <?php if($claper=="2" || empty($claper)){echo "selected";} ?>>Natural</option>
</select>
</td>
 
</tr>
<tr>
<td>Regimen Tributario:</td>
<td>
<select name="regtri" id="regtri" class="datos_cli" style="width: 150px;">
<option value="1" <?php if($regtri=="1"){echo "selected";} ?>>
Responsable de IVA
</option>
<option value="2" <?php if($regtri=="2"){echo "selected";} ?>>No responsable de IVA</option>
<!--
<option value="3" <?php if($regtri=="3"){echo "selected";} ?>>R&eacute;gimen Simple</option>
<option value="4" <?php if($regtri=="4"){echo "selected";} ?>>R&eacute;gimen Ordinario</option>
-->
</select>
</td>
<td>Regimen Fiscal:</td>
<td>
<select name="regFiscal" id="regFiscal" class="datos_cli" style="width: 150px;">
<option value="1" <?php if($regFiscal=="1"){echo "selected";} ?>>Gran contribuyente</option>
<option value="2" <?php if($regFiscal=="2"){echo "selected";} ?>>Autorretenedor</option>
<option value="3" <?php if($regFiscal=="3"){echo "selected";} ?>>Agente de retención IVA</option>
<option value="4" <?php if($regFiscal=="4"){echo "selected";} ?>>
Simple</option>
<option value="5" <?php if($regFiscal=="5"){echo "selected";} ?>>Ordinario</option>
<!--
<option value="6" <?php if($regFiscal=="6"){echo "selected";} ?>>
Impuesto sobre las ventas – IVA</option>
<option value="7" <?php if($regFiscal=="7"){echo "selected";} ?>>
Retención en la fuente a título de renta</option>
<option value="8" <?php if($regFiscal=="8"){echo "selected";} ?>>
Régimen simplificado impuesto nacional consumo rest y bares</option>
-->
</select>
</td>
<td>Razon Social :</td>
<td><input name="razsoc"  type="text" value="<?php echo "$razsoc"; ?>" id="razsoc" class="datos_cli"  /></td>
</tr>
<tr>

<td >Direcci&oacute;n:</td>
<td ><input name="dir" type="text" value="<?php echo "$dirCli"; ?>" id="dir" class="datos_cli"  /></td>
<td>Ciudad:</td>
<td> 
<?php
include("form_ciudades_fe.php");
?>
</td>

<td>Tel.:</td>
<td><input name="tel"  type="text" value="<?php echo "$telCli"; ?>" id="tel" class="datos_cli"  /></td>
</tr>
<tr>
<td>E-mail.:</td>
<td><input name="mail"  type="text" value="<?php echo "$mailCli"; ?>" id="mail" class="datos_cli"  /></td>
</tr>
<!--
<tr>
<td>Departamento:</td>
<td><input name="depcli" type="text" id="depcli" value="<?php echo "$depcli"; ?>" class="datos_cli" /></td>
<td>Localidad :</td>
<td><input name="loccli"  type="text" value="<?php echo "$loccli"; ?>" id="loccli" class="datos_cli"  /></td>
<td>E-mail.:</td>
<td><input name="mail"  type="text" value="<?php echo "$mailCli"; ?>" id="mail" class="datos_cli"  /></td>
</tr>

<tr>
<td>Cod. Pa&iacute;s:</td>
<td> 
<select name="paicli" id="paicli" class="datos_cli">
<option value="CO" <?php if($paicli=="CO"){echo "selected";} ?>>Colombia</option>
<option value="VE" <?php if($paicli=="VE"){echo "selected";} ?>>Venezuela</option>
</select>
</td>

<td>Nombre de Contacto:</td>
<td>
<input name="nomcon"  type="text" value="<?php echo "$nomcon"; ?>" id="nomcon"  class="datos_cli" />
</td>

</tr>
-->

<tr>
<td colspan="4" align="center"><input type="button" value="Guardar" name="filtro"  class="uk-button uk-button-success uk-modal-close datos_cli" onClick="save_cli($('.datos_cli')) "></td>
</tr>
</table>
<input type="hidden" value="1" name="auth_cre" id="auth_cre" class="datos_cli"/>
<input type="hidden" value="Particular" name="tipo_usu" id="tipo_usu" class="datos_cli" />
    </div>
</div>

<?php
}else{
?>
<div id="CREAR_CLI" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">REGISTRO CLIENTE</h1>
<table cellspacing="0" cellpadding="0">
<tr class="clientes">
<td >Cliente:</td>
<td ><input name="cli" type="text" id="cli" value="<?php echo "$nomCli"; ?>" onKeyUp="//get_nom(this,'add');mover($(this),$('#cod'),$(this),0);" onBlur="busq_cli(this);" class="uk-form-small datos_cli"/>
<?php if($MODULES["ALIAS_CLI"]){?>
<input name="aliasCli" type="text" id="aliasCli" value="" placeholder="ALIAS" style="width:60px;">
<?php }?>
</td>
<td>C.C./NIT:</td>
<td><input name="ced" type="text" value="<?php echo "$idCli"; ?>" id="ced" onchange="busq_cli(this)" class="uk-form-small datos_cli"/></td>

</tr>


<tr class="clientes">
<td >Direcci&oacute;n:</td>
<td ><input name="dir" type="text" value="<?php echo "$dirCli"; ?>" id="dir" class="uk-form-small datos_cli" /></td>
<td>Ciudad:</td>
<td>
<input name="city" type="text" id="city" value="<?php echo "$ciudadCli"; ?>" onChange="valida_doc('cliente',this.value);" class="uk-form-small datos_cli"/></td>
 
</tr>
<tr>

<td>Tel.:</td>
<td><input name="tel"  type="text" value="<?php echo "$telCli"; ?>" id="tel"  class="uk-form-small datos_cli"/></td>
<td>E-mail.:</td>
<td colspan=""><input name="mail"  type="text" value="<?php echo "$mailCli"; ?>" id="mail"  class="uk-form-small datos_cli"/></td>
 
</tr>
<tr>
<td colspan="4" align="center"><input type="button" value="Guardar" name="filtro"  class="uk-button uk-button-success uk-modal-close " onClick=" save_cli($('.datos_cli'))"></td>
</tr>
</table>
    </div>
</div>




<?php
}
?>