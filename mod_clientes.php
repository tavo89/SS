<?php
/*

relacionados:
reg_cli.php
mod_cli.php
*/
include_once("Conexxx.php");
// 
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_mod")){header("location: centro.php");}

?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("HEADER.php"); ?>

<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">
<div class=" grid-100 posicion_form">
<?php 
$ced=s('id_cli');
$NOM=s('nombre_cli');
$snombr=s('snombr_cli');
$apelli=s('apelli_cli');
//$pag=limpiarcampo(limpianum($_REQUEST['pag']));
if($MODULES["FACTURACION_ELECTRONICA"]==1){$sql="SELECT * FROM usuarios WHERE id_usu='$ced' AND (snombr='$snombr' AND apelli='$apelli' AND nombre='$NOM')";}
else{$sql="SELECT * FROM usuarios WHERE id_usu='$ced' AND nombre='$NOM'";}
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$nombre=$row['nombre'];
	$ced=$row['id_usu'];
	$usuLv=$row['cliente'];
	
 
	$dir=$row['dir'];
	$tel=$row['tel'];
	
	$mail=$row['mail_cli'];
	$sim=$row['sim'];
	
	$topCre=$row["tope_credito"];
	$authCre=$row["auth_credito"];
	
	$tipoUsu=$row["tipo_usu"];
	
	$fechaBan=$row["fecha_ban"];
	$montoBan=$row["monto_ban"];
	
	$fechaBanRemi=$row["fecha_ban_remi"];
	$montoBanRemi=$row["monto_ban_remi"];
	
	$codComision=$row["cod_comision"];
	$aliasCli=$row["alias"];
	
	$cod_caja=$row["cod_caja"];
	
	$cursoMejoramiento=$row["nomina_1"];
	
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	
	$claper=$row["claper"];
	
	$coddoc=$row["coddoc"];
	$paicli=$row["paicli"];
	$depcli=$row["depcli"];
	$regFiscal=$row["regFiscal"];
	$nomcon=$row["nomcon"];
	$regtri=$row["regtri"];
	$razsoc=$row["razsoc"];
	
	$cuidad = $row['cuidad'];
	//echo "CIUDAD : $cuidad";
	
	$fecha_afiliacion=$row["fecha_afiliacion"];
	$fecha_suspension=$row["fecha_suspension"];
	$fecha_terminacion=$row["fecha_terminacion"];
 ?>
<form action="registro_cli.php" method="get" id="frm" class="uk-form">
<div id="mod_cli">
<table align="center" class="uk-contrast dark_bg uk-text-large">
<tr>
<td>
<table align="center">
<thead>
<th colspan="6" style="font-size:24px"><i class="uk-icon-user">&nbsp;</i>Datos Cliente</th>
</thead>
<tr class="<?php if($MODULES["ALIAS_CLI"]!=1){echo "uk-hidden";}?>">
<td>Alias:</td><td><input name="aliasCli" type="text" id="aliasCli" value="<?php echo $aliasCli ?>" /></td>
</tr>
<tr>
<td>Nombre 1:</td>
<td><input name="cli" type="text" id="cli" value="<?php echo $nombre ?>" />
<input name="cliA" type="hidden" id="cliA" value="<?php echo $nombre ?>" /></td>
<td>Nombre 2:</td>
<td><input name="snombr" type="text" id="snombr" value="<?php echo "$snombr"; ?>" onChange=""/>
<input name="snombrA" type="hidden" id="snombrA" value="<?php echo "$snombr"; ?>" onChange=""/></td>
<td>Apellidos:</td>
<td><input name="apelli" type="text" id="apelli" value="<?php echo "$apelli"; ?>" onChange=""/>
<input name="apelliA" type="hidden" id="apelliA" value="<?php echo "$apelli"; ?>" onChange=""/></td>

</tr>

<tr>
<td>Tipo Documento:</td>
<td>
<select name="coddoc" id="coddoc" style="width:200px;">

<option value="1"  <?php if($coddoc=="1" || empty($coddoc)){echo "selected";} ?>>Cedula de ciudadania</option>
<option value="3" <?php if($coddoc=="3"){echo "selected";} ?>>NIT</option>
<option value="4" <?php if($coddoc=="4"){echo "selected";} ?>>NIT otro país</option>
<option value="6" <?php if($coddoc=="6"){echo "selected";} ?>>Registro civil</option>

<option value="7" <?php if($coddoc=="7"){echo "selected";} ?>>Tarjeta de identidad</option>
<option value="8" <?php if($coddoc=="8"){echo "selected";} ?>>Tarjeta de extranjeria</option>

<option value="2" <?php if($coddoc=="2"){echo "selected";} ?>>Cedula de extranjeria</option>
<option value="9" <?php if($coddoc=="9"){echo "selected";} ?>>Pasaporte</option>
<option value="10" <?php if($coddoc=="10"){echo "selected";} ?>>Documento de identificacion extranjero</option>
</select>
</td>
<td>C.C/NIT:</td>
<td><input name="ced" type="hidden" value="<?php echo $ced ?>" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" />
<input name="NEWced" type="text" value="<?php echo $ced ?>" id="ced" onChange="validar_c($(this),'usuarios','id_usu','Este Documento Ya esta registrado!!!');" />
</td>
<td>Clase Persona:</td>
<td>
<select name="claper" id="claper">
<option value="1" <?php if($claper=="1"){echo "selected";} ?>>Juridica</option>
<option value="2" <?php if($claper=="2" || empty($claper)){echo "selected";} ?>>Natural</option>
</select>
</td>

</tr>

<tr>
<td>Regimen Tributario:</td>
<td>
<select name="regtri" id="regtri">
<option value="1" <?php if($regtri=="1"){echo "selected";} ?>>
Responsable de IVA
</option>
<option value="2" <?php if($regtri=="2"){echo "selected";} ?>>No responsable de IVA</option>
</select>
</td>
<td>Razon Social :</td>
<td><input name="razsoc"  type="text" value="<?php echo "$razsoc"; ?>" id="razsoc"  /></td>
<td>Regimen Fiscal:</td>
<td>
<select name="regFiscal" id="regFiscal" class="datos_cli" style="width: 150px;">
<option value="1" <?php if($regFiscal=="1"){echo "selected";} ?>>Gran contribuyente</option>
<option value="2" <?php if($regFiscal=="2"){echo "selected";} ?>>Autorretenedor</option>
<option value="3" <?php if($regFiscal=="3"){echo "selected";} ?>>Agente de retención IVA</option>
<option value="4" <?php if($regFiscal=="4"){echo "selected";} ?>>
Simple</option>
<option value="5" <?php if($regFiscal=="5"){echo "selected";} ?>>Ordinario</option>
 
</select>
</td>
</tr>

<tr>
<th colspan="6" align="center" style="font-size:24px">
<i class="uk-icon-home">&nbsp;</i>
Datos de Contacto
</th>
</tr>
<tr>

<td >Direcci&oacute;n:</td>
<td ><input name="dir" type="text" value="<?php echo "$dir"; ?>" id="dir"  /></td>

 
<td>Tel.:</td>
<td><input name="tel"  type="text" value="<?php echo "$tel"; ?>" id="tel"  /></td>
</tr>
<tr>

<td>Ciudad:</td>
<td>

<?php
include("form_ciudades_fe.php");
?>
</td>
<td>E-mail.:</td>
<td><input name="mail"  type="text" value="<?php echo "$mail"; ?>" id="mail"  /></td>
</tr>

<tr>
<td>Cod. Pa&iacute;s:</td>
<td> 
<select name="paicli" id="paicli">
<option value="CO" <?php if($paicli=="CO"){echo "selected";} ?>>Colombia</option>
<option value="VE" <?php if($paicli=="VE"){echo "selected";} ?>>Venezuela</option>
</select>
</td>

<td>Nombre de Contacto:</td>
<td>
<input name="nomcon"  type="text" value="<?php echo "$nomcon"; ?>" id="nomcon"  />
</td>

</tr>
<tr class="uk-text-large <?php if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1){}else{echo "uk-hidden";}?>">
<th colspan="6" align="center" style="font-size:24px">
PLANES INTERNET / SERVICIOS MENSUALES
</th>
</tr>
<tr class="uk-text-large <?php if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1){}else{echo "uk-hidden";}?>">

<td>Gestionar Planes INTERNET:</td><td><a href="<?php echo "servicios_gestion_planes_internet.php?id_cliente=$ced"; ?>" class="uk-button uk-button-large uk-button-primary uk-width-1-1">PLANES</a></td>
</tr>


<tr class="uk-text-large <?php if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1){}else{echo "uk-hidden";}?>">
<td>Afiliacion:</td>
<td>
<input type="date" name="fecha_afiliacion" id="fecha_afiliacion" value="<?php echo "$fecha_afiliacion";?>">
</td>
<td>Suspensi&oacute;n:</td>
<td>
<input type="date" name="fecha_suspension" id="fecha_suspension" value="<?php echo "$fecha_suspension";?>">
</td>
<td>Terminaci&oacute;n:</td>
<td>
<input type="date" name="fecha_terminacion" id="fecha_terminacion" value="<?php echo "$fecha_terminacion";?>">
</td>
</tr>


<tr class="uk-text-large <?php if($MODULES["COMI_VENTAS"]!=1){echo "uk-hidden";}?>">

<td>Tipo Usuario:</td>
<td><select name="tipo_usu" id="tipo_usu" >
<option value="<?php echo $tipoUsu ?>" selected><?php echo $tipoUsu ?></option>
<option value="Empleado">Empleado</option>

<option value="Particular">Particular</option>
<option value="Otros Talleres">Otros Talleres</option>
</select>
</td>
<td>Cod. Comisi&oacute;n</td>
<td><input type="text" value="<?php echo "$codComision";?>" name="cod_comision" id="cod_comision" onChange="validar_c($(this),'usuarios','cod_comision','Este CODIGO Ya esta registrado!');"></td>
</tr>
<!--
<tr>
<td>MIN:</td><td colspan="3"><input type="text" name="sim" id="sim" placeholder="No SIM CARD" value="<?php echo $sim ?>"></td>
</tr>
-->


<tr class="uk-block-primary uk-hiddens">
<th colspan="3" align="center" style="font-size:24px">
ACTUALIZAR DATOS EN FACTURAS Y REMISIONES
</th>

<td colspan="" align="center">
<select name="ref_id" id="ref_id" data-uk-tooltip="{pos:'top-left'}" title="El campo seleccionado debe ser el correcto y no cambiar&aacute;, los dem&aacute;s datos ser&aacute;n actualizados">
<option value="" ></option>
<option value="cc" selected>C.C/NIT</option>
<option value="nombre" >Nombre</option>
</select>
</td>
</tr>

<tr class="uk-block-primary <?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?>">
<td colspan="4">OPCIONES N&Oacute;MINA</td> 
</tr>

<tr class="<?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?>">
<td colspan="2">CURSO MEJORAMIENTO</td>
<td>
<select name="curso" id="curso">
<option value="1" <?php if($cursoMejoramiento==1){echo "selected";}?>>Si</option>
<option value="no" <?php if($cursoMejoramiento==0){echo "selected";}?>>NO</option>
</select>
</td>
</tr>

<tr class="uk-block-primary <?php if($MODULES["CARTERA"]!=1){echo "uk-hidden";}?>" >
<th colspan="4" align="center" style="font-size:24px">
CARTERA
</th>
</tr>

<tr class="<?php if($MODULES["CARTERA"]!=1){echo "uk-hidden";}?>">
<td>Tope Cr&eacute;dito:</td>
<td><input type="text" name="top_cre" id="tope_cre" value="<?php echo money("$topCre"); ?>" onKeyUp="puntoa($(this))"></td>
<td>Autorizar Cr&eacute;dito:</td>
<td>
<select name="auth_cre" id="auth_cre">
<option value="1" <?php if($authCre==1)echo "selected" ?>>SI</option>
<option value="0"<?php if($authCre==0)echo "selected" ?>>NO</option>
</select>
</td>
</tr>

<?php

if( ($rolLv==$Adminlvl || val_secc($id_Usu,"usu_lim_fac"))   && $MODULES["LIM_FAC_REMI"]==1){
?>

<tr>
<th colspan="4" align="left" style="font-size:24px">
CONTRATACI&Oacute;N 
</th>
</tr>

<tr>
<td>Fecha Inicio:</td>
<td><input type="date" name="fecha_ban" id="fecha_ban" value="<?php echo $fechaBan; ?>" data-uk-tooltip="{pos:'bottom-left'}" title="Apartir de esta fecha se sumar&aacute;n las FACTURAS para calcular el progreso del l&iacute;mite"></td>

<td>Monto L&iacute;mite:</td>
<td><input type="text" value="<?php echo money($montoBan); ?>" name="monto_ban" id="monto_ban" onKeyUp="puntoa($(this))" class="uk-form-danger" data-uk-tooltip="{pos:'bottom-left'}" title="Si una FACTURA pasa este monto (antes de ser guardada) No se permitir&aacute; seguir a menos que se cambie el valor total de la FACTURA"></td>

</tr>


<tr class="uk-hidden">
<th colspan="4" align="left" style="font-size:24px">
<br>
CONTRATACI&Oacute;N REMISI&Oacute;NES
</th>
</tr>

<tr class="uk-hidden">
<td>Fecha Inicio:</td>
<td><input type="date" name="fecha_ban_remi" id="fecha_ban_remi" value="<?php echo $fechaBanRemi; ?>" data-uk-tooltip="{pos:'bottom-left'}" title="Apartir de esta fecha se sumar&aacute;n las REMISIONES para calcular el progreso del l&iacute;mite"></td>

<td>Monto L&iacute;mite:</td>
<td><input type="text" value="<?php echo money($montoBanRemi); ?>" name="monto_ban_remi" id="monto_ban_remi" onKeyUp="puntoa($(this))" class="uk-form-danger" data-uk-tooltip="{pos:'bottom-left'}" title="Si una REMISION pasa este monto (antes de ser guardada) No se permitir&aacute; seguir a menos que se cambie el valor total de la REMISION"></td>

</tr>
<?php
}


?>
</table>
</td>
</tr>



<?php

if($_descuentosFabricante==1){

?>
<tr>
<td>

<table align="left" cellpadding="5" cellspacing="0" class="round_table_white" id="descuentos">
<?php
$num_d=0;
$sql="SELECT * FROM dcto_fab WHERE id_cli='$ced'";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	
	$dcto=$row['dcto']*1;
	$fab=$row['fabricante'];
	$tipoD=$row['tipo_dcto'];

?>
<tr>
<thead style="font-size:24px; font-weight:bold;">
  <th colspan="4" align="center">Descuentos</th>
</tr>
</thead>
<tbody>
<tr style="font-size:24px">
<td>
<input type="text" name="dcto_<?php echo $num_d ?>" id="dcto_<?php echo $num_d ?>" value="<?php echo $dcto ?>"   style="width:200px" placeholder="DCTO %"/>
</td>
<td width="60">
<select name="tipo_dcto<?php echo $num_d ?>">
<option value="NETO" <?php if($tipoD=="NETO")echo "selected" ?> >NETO</option>
<option value="PRODUCTO" <?php if($tipoD=="PRODUCTO")echo "selected" ?>>PRODUCTO</option>
</select>
</td>
<td >
Operador:
</td>
<td>

<?php
	
	echo fab($conex,$fab,"fab".$num_d,"fab".$num_d,"dcto".$num_d);
?>
</td>
</tr>
<?php
$num_d++;
}// fin while dcto

?>
</tbody>
</table>
</td>
</tr>

<tr>
<td colspan="2" align="center">
<span onClick="add_dcto();" class="uk-button uk-button-large" data-uk-tooltip title="Agrega Un Descuento por Marca de Producto"><i class=" uk-icon-plus-square"></i>Nuevo Dcto</span>
</td>
</tr>
<?php

}

?>


<?php
$USU="";
$CLA="";
$T_USU="";
if($usuLv==0){

$sql="SELECT * FROM usu_login u INNER JOIN tipo_usu t ON u.id_usu=t.id_usu WHERE t.id_usu='$ced'";
$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$USU=$row['usu'];
		$CLA=$row['cla'];
		$T_USU=$row['des'];
			
	}
?>
<tr>
<td>
<table align="left" width="100%">
<thead >
<tr class="uk-block-primary">
<th colspan="4">
Cuenta Usuario
</th>
</tr>
</thead>
<tbody>
<tr>

<td>Usuario:</td><td><input type="text" name="usu" id="usu" value="<?php echo $USU ?>" readonly></td>
</tr>
<tr>
<td>Contrase&ntilde;a:</td><td><input type="text" name="cla" id="cla" value="<?php echo $CLA ?>"></td>
</tr>
<tr>
<td>Tipo Usuario</td>
<td><select name="tipo_usu" >
<option value="<?php echo $T_USU ?>" selected><?php echo $T_USU ?></option>
<option value="Tecnico">Tecnico</option>
<option value="Estandar">Estandar</option>
<option value="Vendedor">Vendedor</option>
<option value="Administrador">Administrador</option>
</select></td>
<td>Cod. Caja:</td>
<td>
<select name="cod_caja">
<option value="<?php echo 0; ?>"><?php echo 0; ?></option>
<option value="<?php echo $cod_caja; ?>" selected><?php echo $cod_caja; ?></option>
<?php
$sql="SELECT MAX(cod_caja) as m FROM usuarios";
$RSx=$linkPDO->query($sql);
$rowX=$RSx->fetch();
$lastCodCaja=$rowX["m"]+1;
echo "<option value=\"$lastCodCaja\">$lastCodCaja</option>";
?>
</select>

</td>
</tbody>
</table>

</td>
</tr>


<?php
}////////////////////////////////////////////////////// fin IF cuenta usu ////////////////////////////////////////////////////////////////////////////
?>
<tr>
<td colspan="2" align="center">
<span  onClick="mod_cli($('#frm'));" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>

<span class="uk-button uk-button-large" onClick="Goback();"><i class=" uk-icon-history"></i>Volver</span>
</td>
</tr>
</table>
</div>




<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
<!--    <input type="hidden" name="num_d" value="<?php //echo $num_d ?>" id="num_d" />

<input type="hidden" value="" name="html_antes" id="HTML_antes">
    <input type="hidden" value="" name="html_despues" id="HTML_despues">
-->
    
</form>

<?php
}

?>
<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=$('#num_d').val();
var $nd=$('#num_d');

$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


getPage($('#HTML_antes'),$('#mod_cli'));
	});
function add_dcto()
{
	//var fab=fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Marca:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
	var $d=$(html);
	$d.appendTo('#descuentos');
	fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	cd++;
	
	$nd.prop('value',cd);
	
	
};
function d(cant)
{
   var c=100+cant*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   //alert(c);
   $dcto=$('#dcto');
   $dcto.prop('value',d);
};

</script>
</body>
</html>