<?php 
$ukIconSize="uk-icon-large";
$ukFormSize="uk-form-small";
if($MODULES["CUENTAS_BANCOS"]==1){
//$ukIconSize="uk-icon-small";
//$ukFormSize="uk-form-small";
	}
?>
<table cellspacing="0" cellpadding="0">
<tr>
<?php if($_opt_facVen_dev=="FAC_VEN"){?>
<td id="tipo_factura_td" colspan="" class="uk-text-bold  uk-text-large" align="center">
<h3   class="uk-text-primary uk-text-bold " style=" font-size:25px;" >
<?php if($usar_remision==1)echo "REMISION";
else echo "FACTURA";?>
</h3>
</td>
<TD id="resolucion_td" align="right">

<select class="uk-text-primary" name="tipo_resol" id="tipo_resol" onChange="cambia_resol($(this),$('#pos'),$('#com'),$('#papel'),$('#com_ant'),$('#papel_ant'),$('#cre_ant'))">
<?php if($FLUJO_INV==1 || !empty($filtroB)){ ?>
<option value="POS" <?php if($tipo_fac_default=="POS")echo "selected"; ?>>POS</option>
<option value="COM" <?php if($tipo_fac_default=="COM")echo "selected"; ?>>COM</option>
<option value="PAPEL" <?php if($tipo_fac_default=="PAPEL")echo "selected"; ?>>FAC. ELECTRONICA</option>
<?php } else{?>
                
<option value="COM_ANT">Computador (Anterior)</option>
<option value="PAPEL_ANT">Papel (Anterior)</option>
<option value="CRE_ANT" selected="selected">CARTERA (Anterior)</option>
<?php } ?></select>
</TD>
<td id="consecutivo_td" colspan=""  align="center" class="uk-text-bold uk-text-danger uk-text-large" style="font-size:42px;">               
<span id="pos"  ></span><span id="com" ></span>
<span id="papel"  ></span><span id="com_ant"  > </span>
<span id="papel_ant"  > </span><span id="cre_ant"  > </span>
</td>
<?php }
else{
?>
<td colspan="2" style=" " class="uk-text-bold  " align="center">
                <h1   class="uk-text-primary uk-text-bold">
                DEVOLUCI&Oacute;N VENTA
                 </h1>
                  </td>
                <TD align="right">&nbsp;</TD>
                <td colspan="1"  align="center" class="uk-text-bold uk-text-danger uk-text-large" style="font-size:42px;">
                
<?php 

echo "# ". serial_fac("fac_dev_ven","REM_POS","fac_dev_venta");
?>
</td>



<?php }?>
<td colspan="2" style="font-size:16px;font-weight:bold;" id="fp_container">


<?php if($MODULES["CUENTAS_BANCOS"]==1)echo " ".selcCta("","uk-text-primary  uk-text-bold uk-form-success $ukFormSize"); ?>
</td>
<td id="usu_venta_td" colspan="3" align="left" style="width:200px;">
<i class="uk-icon-user <?php echo $ukIconSize ?>"></i>
<select name="vendedor" id="vendedor"  style="width:100px" class="<?php echo $ukFormSize ?>"  >
<option value="" selected="selected"></option>
<option value="<?php echo "$nomUsu-$id_Usu" ?>" selected><?PHP echo $nomUsu ?></option>
<?php if($rolLv==$Adminlvl || $MODULES["RES_VEN"]==0){
  $sql = "SELECT nombre,a.id_usu 
          FROM usuarios a 
          INNER JOIN ( SELECT a.estado,b.id_usu,b.des FROM usu_login a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu 
                       WHERE (des='Vendedor' OR des='Caja' OR des='Inventarios' OR des='Administrador') 
                       AND a.estado!='SUSPENDIDO') b ON b.id_usu=a.id_usu AND a.cod_su='$codSuc' AND a.id_usu!='' AND a.cliente=0  ORDER BY nombre";
    $rs=$linkPDO->query($sql);
      while($row=$rs->fetch()){
        $vendedor= $row["nombre"];
        $idVendedor=$row["id_usu"];
        ?>
        <option value="<?php echo "$vendedor-$idVendedor" ?>" <?php if($id_Usu==$idVendedor){echo "selected"; }?> ><?php echo $vendedor;?></option>
        <?php 
    }

}
?>
</select> 
</td>
<td colspan=""> 
<select name="tipo_cli" id="tipo_cli" class="uk-form-small" style="width:100px">
<?php echo "$GLOBAL_TIPOS_CLI";?>
</select>
</td>
<td   colspan="2"  >
<div class="uk-form-icon"><i class="uk-icon-calendar uk-icon-small uk-icon-justify"></i>
<?php if($FLUJO_INV==1 || !empty($filtroB)){ ?>
<input  id="fecha" type="datetime-local" value="<?php echo $FECHA_HORA; ?>" name="fecha"  class="<?php echo $ukFormSize ?>" style="width:275px;" <?php if($rolLv!=$Adminlvl){/*echo"readonly=\"readonly\"";*/}?>  />
<?php } else{ ?>
<input  id="fecha" type="date" value="" name="fecha"  class="<?php echo $ukFormSize ?>"/>
<?php   } ?>
</div>
</td>

</tr>


<?php if($MODULES["COMI_VENTAS"]==1){?>
<tr>           
<td colspan="2" class="destacar_cont">Cod. Comisi&oacute;n</td>
<td colspan="4"  >

<select name="cod_comision" id="cod_comision"  style="width:200px" data-placeholder="C&oacute;digo Otros Talleres" class="chosen-select ">
<option value="" selected>- - - - - - - - - </option>
<?php include("load_cod_ven.php");?>
 </select>
 </td>
 <td colspan="4">
<select name="tipo_comi" id="tipo_comi" class="uk-button-success" onChange="if(this.value=='Venta Directa'){$('#DESCUENTO2').prop('value','0').prop('disabled','disabled');dctoB();}else{$('#DESCUENTO2').prop('disabled','');}">
<option value="" selected>Seleccione Tipo Comisi&oacute;n</option>
<option value="Venta Directa">Venta Directa(Venta al Mec&aacute;nico)</option>
<option value="Venta Cliente">Venta Cliente</option>
</select>
</td>
</tr>
<?php }?>    
    
    
<tr  id="pagare" class="<?php if($MODULES["NUM_NOTA_ENTREGA"]!=1){echo "uk-hidden";}?>" valign="bottom">
<td>NOTA ENGREGA:</td>

<td><input type="text" value="" name="num_pagare" id="num_pagare" ></td>
<?php if($MODULES["NUM_NOTA_ENTREGA"]==1){?>
<td colspan="2">PLACA: <input type="text" name="placa" value="<?php echo "$PLACA" ; ?>" id="placa" onChange="" class="save_fc"></td>
<?php } ?>

              </tr>
</tr>



<tr class="clientes">
<td  colspan="6">Cliente: 
<input name="cli_lookup" type="text" id="cli_lookup" value="<?php echo "$nomCli"; ?>" onKeyUp="" onBlur="busq_cli(this);" class="<?php echo $ukFormSize ?>"/>
<?php if($MODULES["ALIAS_CLI"]){?>
<input name="aliasCli" type="text" id="aliasCli" value="" placeholder="ALIAS" style="width:60px;">
<?php }?>
 
<input name="nom_lookup" type="text" id="nom_lookup" value="<?php echo ""; ?>" onKeyUp="" onBlur="busq_cli(this);" class="<?php echo $ukFormSize ?>"/>
<a href="#CREAR_CLI" data-uk-modal class="uk-badge uk-badge-success" style="font-size:16px;">REGISTRAR</a>
</td>
<?php if($MODULES["VEHICULOS"]==1){?>
<td colspan="2"  >PLACA:
 
<input  type="text" name="placa" value="<?php  echo "$PLACA"; ?>" id="placa" onChange="val_placa($(this));" class="<?php echo $ukFormSize ?>">

<a href="#OT_VEHI" data-uk-modal class="uk-badge uk-badge-success" style="font-size:16px;">REGISTRAR</a>
</td>
<td colspan="2"> <input name="km"  type="text" value="<?php  echo "$KM"; ?>" id="km" placeholder="Kil&oacute;metros del Veh&iacute;culo"  class="<?php echo $ukFormSize ?>"/></td>
<?php } ?>
</tr>


</table>



<?php include("form_reg_cli.php");?>



<div id="OPC_FAC" class="uk-modal" >
<div class="uk-modal-dialog" style="width:1000px;">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">Opciones Adicionales Factura</h1>
<table cellspacing="0" cellpadding="0" class="uk-table">
<tr  >


<td valign="bottom" colspan=" ">
APLICAR PRECIOS: </td>
<td>
<input type="button" value="CREDITO" onClick="<?php if($MODULES["PVP_CREDITO"]==1) echo "cambiaFP();"; ?>" class="uk-button uk-button-primary"></TD>
<td>
 <input type="button" value="MAYORISTA" onClick="<?php if($MODULES["MAYORISTA_PER"]==1) echo "set_mayorista();"; ?>" class="uk-button uk-button-primary">
 <!--<input type="button" value="MAYORISTA2" onClick="<?php if($MODULES["MAYORISTA_PER"]==1) echo "set_mayorista2();"; ?>" class="uk-button uk-button-primary">-->
</td>
</tr>
<tr class="uk-hidden">

<?php if($MODULES["ANTICIPOS"]==1){ ?>
<td colspan="" class="">
USAR Bono/Anticipo </td>
<td>
<select id="confirm_bono" name="confirm_bono" onChange="" class="uk-form-small">
<option value="SI" >Si</option>
<option value="NO" selected>No</option>
</select>
</td>
<?php }?>
                 

</tr>
       
<tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text"  value="" name="R_FTE_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_FTE'));" class="save_fc uk-form-small"/>
</td>
<td colspan="" align="right"><input id="R_FTE" type="text"  value="" name="R_FTE" class="save_fc uk-form-small" />
</td>
</tr>
          

<tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text"  value="" name="R_IVA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#IVA'),$('#R_IVA'));"  class="uk-form-small"/></td>
<td colspan="" align="right"><input id="R_IVA" type="text"  value="" name="R_IVA"  class="save_fc uk-form-small"/>
</td>
</tr>
          

<tr class="<?php if($MODULES["RETENCIONES"]==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text"  value="" name="R_ICA_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#SUB'),$('#R_ICA'));" class="save_fc uk-form-small"/></td>
<td colspan="" align="right"><input id="R_ICA" type="text"  value="" name="R_ICA" class="save_fc uk-form-small"/>
</td>
</tr>

<tr class="<?php if($impuesto_consumo==1){}else{echo "uk-hidden";}?>">
<td  align="right" colspan="">IMP. Consumo<input placeholder="%" id="CONSUMO_PER" type="hidden"  value="" name="CONSUMO_PER"  style="width:50px" onKeyUp="calc_per($(this),$('#TOT'),$('#IMP_CONSUMO'));" class="save_fc uk-form-small"/></td>
<td colspan="" align="right"><input id="IMP_CONSUMO" type="text"  value="" name="IMP_CONSUMO" class="save_fc uk-form-small"/>
</td>
</tr>



<tr>
<td>
<!-- DEAULTS -->
<input type="hidden" value="<?php echo $TIPDOC; ?>" name="TIPDOC" id="TIPDOC" class=""/>
<input type="hidden" value="<?php echo $TIPCRU;?>" name="TIPCRU" id="TIPCRU" class=""/>
<input type="hidden" value="<?php echo $codSuc; ?>" name="CODSUC" id="CODSUC" class=""/>
<input type="hidden" value="01" name="FORIMP" id="FORIMP" class=""/>
<input type="hidden" value="D" name="CLADET" id="CLADET" class=""/>
<input type="hidden" value="" name="ORDENC" id="ORDENC" class=""/>
<input type="hidden" value="" name="NUREMI" id="NUREMI" class=""/>
<input type="hidden" value="" name="NORECE" id="NORECE" class=""/>
<input type="hidden" value="" name="EANTIE" id="EANTIE" class=""/>
<input type="hidden" value="COP" name="COMODE" id="COMODE" class=""/>
<input type="hidden" value="COP" name="COMOHA" id="COMOHA" class=""/>
<input type="hidden" value="" name="FACCAL" id="FACCAL" class=""/>
<input type="hidden" value="" name="FETACA" id="FETACA" class=""/>
<input type="hidden" value="" name="FECREF" id="FECREF" class=""/>
<input type="hidden" value="" name="OBSREF" id="OBSREF" class=""/>
<input type="hidden" value="" name="FACCAL" id="FACCAL" class=""/>
<input type="hidden" value="" name="FETACA" id="FETACA" class=""/>

<input type="hidden" value="<?php echo "$ResolContado";?>" name="NDIAN" id="NDIAN" class=""/>

Fecha Vencimiento:
</td>
<td><input type="date" name="FECVEN" id="FECVEN" class="" /></td>
<td>Moneda:</td>
<td>
<select name="MONEDA" id="MONEDA" class="">
<option value="COP" selected="selected">COP</option>
<option value="USD">USD</option>
</select>
</td>


<td>Orden de compra:</td>
<td><input type="text" name="ORDENC" id="ORDENC" class="" /></td>

</tr>
<tr>
<td>Fecha Doc. Referenciado</td>
<td><input type="date" name="FECREF" id="FECREF" class="" /></td>

<td>Observaciones Fac. referenciada:</td>
<td><textarea name="OBSREF" id="OBSREF" class=""></textarea></td>
<td>Organización de ventas:</td>
<td><input type="text" name="SOCIED" id="SOCIED" class=""></td>

</tr>
<tr>
<td>Texto documento:</td>
<td><textarea name="TEXDOC" id="TEXDOC" class=""></textarea></td>


<td>Motivo devoluci&oacute;n SISTEMA:</td>
<td><textarea name="MOTDEV" id="MOTDEV" class=""></textarea></td>
</tr>
</table>
</div>
</div>
