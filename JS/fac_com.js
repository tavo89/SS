$(function(){
    /*
     * this swallows backspace keys on any non-input element.
     * stops backspace -> back
     */
    var rx = /INPUT|SELECT|TEXTAREA/i;

    $(document).bind("keydown keypress", function(e){
        if( e.which == 8 ){ // 8 == backspace
            if(!rx.test(e.target.tagName) || e.target.disabled || e.target.readOnly ){
                e.preventDefault();
            }
        }
    });
});
var cont=0;
var ref_exis=0;
var dct_STD=0;
var fullF=0;
var addArtAlert=1;
var CNC=0;

function save_all()
{
	
		for(i=0;i<=cont;i++)
	{
		//alert('cont:'+cont);
		if($('#costo'+i).length!=0)
		{
			save_fc(i);
		}
		
	}
	
};

function inputs()
{
	var allInputs=$(':input');
	//var formChildren=$('form>*');
	//alert('All Inputs:'+allInputs.length);	
};

function cantLim(i)
{
	//alert('CantLim:'+$('#cantLim'+i).val()+', Cant:'+$('#cant'+i).val()+', traslado:'+traslado);
	if(traslado==1&&parseInt($('#cant'+i).val())>parseInt($('#cantLim'+i).val()))
	{
		warrn_pop('Esa Cantidad excede las registradas ('+$('#cantLim'+i).val()+')');
		$('#cant'+i).prop('value','');
		
		}
		//else alert('nada..');
	
	
};
function val_fc(form)
{
	if(esVacio(form.num_fac.value)){warrn_pop('Ingrese el numero de Factura');form.num_fac.focus();return true;}
	else if(form.verify.value=='ko'){warrn_pop('El numero de Factura  NO es  Valido!');form.num_fac.focus();return true;}
	else if(esVacio(form.fecha.value)){warrn_pop('Ingrese la FECHA');form.fecha.focus();return true;}
	else if(esVacio(form.fechaVen.value)){warrn_pop('Ingrese la FECHA de VENCIMIENTO');form.fechaVen.focus();return true;}
	else if(esVacio(form.confirma.value)){warrn_pop('Especifique el PROMEDIO de Costos:'+form.confirma.value);form.confirma.focus();return true;}
	else if(esVacio(form.tipo_fac.value)){warrn_pop('Especifique el Tipo de Factura:');form.tipo_fac.focus();return true;}
	else if(esVacio(form.nit.value)||esVacio(form.provedor.value)){warrn_pop('Ingrese el PROVEEDOR');form.n_pro.focus();return true;}
	else {return false;}
};

function subir(btn,val,form)
{
	//alert('PROMEDIO  VAL:'+form.confirma.value);
	getPage($('#HTML_Pag'),$('#fac_compra'));
	
	//else if(esVacio(form.num_ref.value)){alert('Ingrese los ARTICULOS');form.addplus.focus();}
	//else if(esVacio(form.FLETE.value)){alert('Ingrese el costo del FLETE');form.FLETE.focus();}
	
	if(val_fc(form)){}
	else{
	btn.prop('value',val);
	form.submit();
	$('#btn').remove();
	}
};

function subir_tras(btn,val,form)
{
	//alert('PROMEDIO  VAL:'+form.confirma.value);
	if(esVacio(form.sede_dest.value)){warrn_pop('Seleccione La sede de Destino');form.sede_dest.focus();}
	else if(esVacio(form.fecha.value)){warrn_pop('Ingrese la FECHA');form.fecha.focus();}
	else if(esVacio(form.num_ref.value)){warrn_pop('Ingrese los PRODUCTOS');form.addplus.focus();}
	else{
	btn.prop('value',val);
	form.submit();
	$('#btn').remove();
	}
};
function redondeox(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
};
function redondeo(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*1)/1;
//var result=Math.round(original);
//var result=original;

if(tipoRedondeo=="decimal"){return result;}
else{return redondeo_centenas(numero);}
};
function redondeo_centenas(numero)
{
var original=parseFloat(numero);
var result=Math.round(numero);

var r2=0,end=0;
r2=result/50;
r2=Math.round(r2);
end=r2*50;

return end;
};
function dcto_antes()
{
	var $sub=$('#SUB'),subtot=quitap($sub.val())*1;
	var $dcto=$('#DESCUENTO'),dcto=quitap($dcto.val())*1;
	var $dcto2=$('#DESCUENTO2');
	var $iva=$('#IVA');
	var $tot=$('#TOTAL');
	
	var per=redondeox((dcto/subtot)*100,4);
	per=(dcto/subtot)*100;
	
	//alert('DCTO:'+dcto+', ST:'+subtot+' per:'+per)
	for(i=0;i<=cont;i++)
	{
		//alert('cont:'+cont);
		if($('#costo'+i).length!=0)
		{
			$('#dct'+i).prop('value',per);
			save_fc(i);
		}
	}
	
};
function tot()
{
	
	var $sub=$('#SUB');
	var $dcto=$('#DESCUENTO');
	var $dcto2=$('#DESCUENTO2');
	var $iva=$('#IVA');
	var $tot=$('#TOTAL');
	var flete=quitap($('#FLETE').val())*1||0;
	var calc_dcto=$('#calc_dcto').val();
	//alert('sub:'+$sub.val()+' dcto:'+$dcto.val()+'  iva:'+$iva.val()+' tot:'+$tot.val()+'');
	var sub_tot=0;
	var cant=0;
    var dcto=0,d=0;
	var dcto2=($dcto2.val()||0)/100;
	var DCTO2=0;
	var tipoD='';
    var iva=0;
    var vsi=0;
	var pvp=0;
	var SUBi=0,DCTOi=0,IVAi=0,TOTi=0,DCTOi=0,i=0;
	var $costo="",cost=0,Ncost=0;
	var $pvp="",pvp=0,frac=0,uni=0,factor=0,val_dcto=0;
	
	if(usarFracc!=1){
	for(i=0;i<=cont;i++)
	{
		//alert('cont:'+cont);
		if($('#costo'+i).length!=0)
		{
			//cantLim(i);
			d=$('#dct'+i).val();
			cant=$('#cant'+i).val()*1||0;
            dcto=(d/100)||0;
			//alert('dcto['+i+']:'+dcto+'...'+('#dct'+i).val() );
            iva=($('#iva'+i).val()/100)||0;
            vsi=(quitap($('#costo'+i).val())*1)||0;
			//alert('vsi['+i+']:'+vsi);
			sub_tot=(vsi)*cant || 0;
			$('#v_tot'+i).prop('value',puntob(sub_tot));
			SUBi=SUBi + (parseFloat(quitap(vsi) )*cant||0 );
			DCTOi=DCTOi + cant*((vsi)*dcto ||0);
			IVAi=IVAi + cant*((vsi-vsi*dcto)*iva);
			
		};
	};
	}
	else
	{
		
		for(i=0;i<=cont;i++)
	{
		//alert('cont:'+cont);
		if($('#costo'+i).length!=0)
		{
			d=$('#dct'+i).val();
			/*
			if(calc_dcto=="per")dcto=(d/100)||0;
			else dcto=d;
			*/
			dcto=(d/100)||0;
			//cantLim(i);
			cant=$('#cant'+i).val()*1||0;
			frac=$('#fracc'+i).val()*1||1;
			uni=$('#unidades'+i).val()*1||0;
			factor= (uni+(cant*frac))/frac
			//(uni/frac)+cant;
			 if(factor==0)factor=cant;
             
			//alert('dcto['+i+']:'+dcto+'...'+('#dct'+i).val() );
            iva=($('#iva'+i).val()/100)||0;
            vsi=(quitap($('#costo'+i).val())*1)||0;
			//alert('vsi['+i+']:'+vsi);
			sub_tot=((vsi)*factor) || 0;
			$('#v_tot'+i).prop('value',puntob(sub_tot));
			
			/*
			if(calc_dcto=="per"){val_dcto=vsi*dcto ||0;}
			else val_dcto=d;
			*/
			
			val_dcto=vsi*dcto ||0;
//alert('art_sub:'+puntob(sub_tot));
			SUBi=SUBi + (parseFloat(quitap(vsi) )*factor||0 );
			DCTOi=DCTOi + factor*(val_dcto);
			
			IVAi=IVAi + factor*( (vsi-val_dcto)*iva);
		};
	};
		
	}
	DCTO2=(SUBi)*dcto2;
	DCTOi=redondeo(DCTOi);
	//IVAi=(SUBi-DCTOi)*0.19;
	IVAi=redondeo(IVAi+(flete*0.19));
	SUBi=redondeo(SUBi);//-DCTOi;
	//TOTi=((SUBi)+IVAi+flete);
	TOTi=((SUBi-DCTOi)+IVAi+flete);

	var nombreModulo = $('#nombreModulo').length ? $('#nombreModulo').val():'';
	if(nombreModulo=='DEVOLUCION_COMPRAS') {
		$sub.prop('value',puntob(SUBi));
		$dcto.prop('value',puntob(DCTOi));
		$iva.prop('value',puntob(IVAi));
		$tot.prop('value',puntob(TOTi));
		//change($('#entrega'));
	   //alert(covertirNumLetras(""+TOTi+""));
	}

	
	if(TOTi>0)$('#vlr_let').prop('value',covertirNumLetras(""+quitap(TOTi)+""));
};
function gcia2(ori,per,$iva,$dcto,dest)
{
	    var iva=$iva;
		var dcto=($dcto.val()*1)/100;
        var cost=quitap(ori.val())*1;
		cost=cost-(cost*dcto);
        var gan=(100-quitap(per.val())*1)/100;
       var pvp=quitap(dest.val())*1;
		var IVA=(iva.val()*1)/100 +1;
		//alert(gan+', '+cost+", ");
	var va=(cost/gan)*IVA|| 0;
	//alert('val :'+va);
	dest.prop('value',puntob(redondeo(quitap(va))));
	tot();	
};
function addinv()
{
	//if(cont>0 && ref_exis>0)
if(0)
{
	$('#botonSave').click();
}
else{
var $row;
var n=parseInt($('#num_art').val());
//alert('$n:'+typeof($('#num_art').val())+' '+$('#num_art').val());
var html='<tr class="art'+cont+'">';
var i=0;
for(i=0;i<n;i++)
{
//alert('i: '+i);

html+='<td class="art'+cont+'" align="center" valign="top">'+(cont+1)+'</td>';



//ref
html+='<td class="art'+cont+'" align="center" valign="top"><input onKeyUp="cod($(this),\'add\','+cont+',0);" class="art'+cont+'" name="ref'+cont+'" type="text" id="ref'+cont+'" value="" style="width:80px;top:10px"></td><td valign="top"><img style="cursor:pointer" title="Buscar" onMouseUp="busq($(\'#ref'+cont+'\'),'+cont+',0);" src="Imagenes/search128x128.png" width="25" height="25" /></td>';


//cod barras
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:100px" style="" class="art'+cont+'" name="cod_bar'+cont+'" type="text" id="cod_bar'+cont+'" value="" onKeyUp="cod($(this),\'add\','+cont+',1);" onBlur="//busq($(this),'+cont+',\'add\');"></td>';

//cantidad
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="cant'+cont+'" type="text" id="cant'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();cantLim('+cont+');" onChange="cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();">';	

//CantLim

html+='<input style="width:30px" class="art'+cont+'" name="cantLim'+cont+'" type="hidden" id="cantLim'+cont+'" value=""></td>';	


//fraccion
if(usarFracc==1){
	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="fracc'+cont+'" type="text" id="fracc'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();" onChange=""></td>';

	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="unidades'+cont+'" type="text" id="unidades'+cont+'" value="" onKeyUp="calc_cant($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();" onChange=""></td>';

}

//des
html+='<td class="art'+cont+'" align="center" valign="top"><textarea style="width:100px" class="art'+cont+'" name="des'+cont+'"  id="des'+cont+'" value="" ></textarea></td>';



//color
if(usarColor==1)html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="color'+cont+'" type="text" id="color'+cont+'" value="" onKeyUp="" onChange=""></td>';

//talla
if(usarTalla==1)html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="talla'+cont+'" type="text" id="talla'+cont+'" value="" onKeyUp="" onChange=""></td>';



//presentacion
//html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="presentacion'+cont+'" type="text" id="presentacion'+cont+'" value="" onKeyUp="" onChange="">';

if(CNC==1 && !esVacio(preOpt)){
	html+='<td class="art'+cont+'" align="center" valign="top"><select style="width:110px" class="art'+cont+'" name="presentacion'+cont+'" id="presentacion'+cont+'" onChange="">'+preOpt+'</select></td>';
}
else{
	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="presentacion'+cont+'" type="text" id="presentacion'+cont+'" value="UNIDAD" onKeyUp="" onChange=""></td>';
	}


//fabricante
//html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="fabricante'+cont+'" type="text" id="fabricante'+cont+'" value="" onKeyUp="" onChange="">';

if(CNC==1 && !esVacio(fabOpt)){
	html+='<td class="art'+cont+'" align="center" valign="top"><select style="width:110px" class="art'+cont+'" name="fabricante'+cont+'" id="fabricante'+cont+'" onChange="">'+fabOpt+'</select></td>';
}
else{
	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="fabricante'+cont+'" type="text" id="fabricante'+cont+'" value="" onKeyUp="" onChange=""></td>';
}


//clase
//html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="clase'+cont+'" type="text" id="clase'+cont+'" value="" onKeyUp="" onChange="">';

if(CNC==1 && !esVacio(clasesOpt)){
html+='<td class="art'+cont+'" align="center" valign="top"><select style="width:110px" class="art'+cont+'" name="clase'+cont+'" id="clase'+cont+'" onChange="">'+clasesOpt+'</select></td>';
}
else{
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="clase'+cont+'" type="text" id="clase'+cont+'" value="" onKeyUp="" onChange=""></td>';
}


if(usarFechaVenci==1){
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:150px" class="art'+cont+'" name="fecha_vencimiento'+cont+'" type="date" id="fecha_vencimiento'+cont+'" value="" onKeyUp="" onChange=""></td>';

}


//tipo_descuento(opc,cost,pvp,per,iva,redondear_s_n);
//costo
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:90px" class="art'+cont+'" name="costo'+cont+'" type="text" id="costo'+cont+'" value="" onKeyUp="tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');puntoa($(this));tot();" onBlur="nanC($(this))" ></td>';


//tipo dcto
//html+='<td width="60" class="art'+cont+'" valign="top"><select style="width:50px;top:10px" class="art'+cont+'" name="tipo_dcto'+cont+'" id="tipo_dcto'+cont+'" onChange="cto($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'));cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td>';

//dcto  

html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tot();" class="art'+cont+'" name="dcto'+cont+'" type="text" id="dct'+cont+'" value="'+dct_STD+'" style="width:60px;" onChange="nan($(this));/*tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');*/tot();"></td>';
//html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="cto($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'));tot();" class="art'+cont+'" name="dcto'+cont+'" type="text" id="dct'+cont+'" value="0" style="width:30px;" onChange="nan($(this));cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();"></td>';


//util  

html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tot();" class="art'+cont+'" name="util'+cont+'" type="text" id="util'+cont+'" value="'+dct_STD+'" style="width:50px;" onChange="nan($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();"></td>';




//iva%
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tot();" class="art'+cont+'" name="iva'+cont+'" type="text" id="iva'+cont+'" value="" style="width:30px;" onBlur="nan($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');"></td>';


//pvp
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:70px"  class="art'+cont+'" name="pvp'+cont+'" type="text" id="pvp'+cont+'" value="" onKeyUp="puntoa($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();" ></td>';

if(PVP_CRE==1){
//pvpCre
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:70px"  class="art'+cont+'" name="pvpCre'+cont+'" type="text" id="pvpCre'+cont+'" value="" onKeyUp="puntoa($(this));" ></td>';
}


if(PVP_MAY==1){
//pvpMay
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:70px"  class="art'+cont+'" name="pvpMay'+cont+'" type="text" id="pvpMay'+cont+'" value="" onKeyUp="puntoa($(this));" ></td>';
}



//tot
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:90px" class="art'+cont+'" name="v_tot'+cont+'" type="text" id="v_tot'+cont+'" value="" readonly></td><td><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="21px" heigth="21px" ></td></tr>';


$row=$(html);
$row.appendTo('#articulos');
$('#ref'+cont).focus();
//alert('for{cont:'+cont);
cont++;
ref_exis++;
}
//alert('cont:'+cont);
$('#num_ref').prop('value',cont);
$('#exi_ref').prop('value',ref_exis);
//$('#addplus').focus();

}//fin if cont>0
	};
function addRowValidate()
{
	var i=0,r='',f=0;
	for(i=0;i<cont;i++)
	{
		r=$('#cod_bar'+i);
		if(r.length!=0){
			if(esVacio(r.val()) ){f++;}
			}
	}
	return f;
};
function addinv_mod()
{
var nrv=addRowValidate();
//alert(nrv);
if(nrv<1){
	
var externals=$('.save_fc');
var ExtString=externals.serialize();
//alert('addinv_mod');
ajax_x("ajax/add_com.php",ExtString,function(resp){
	//alert('entra ajax_x!');
var $row;
var n=1;
var ID=resp;
//alert(resp);
//alert('$n:'+typeof($('#num_art').val())+' '+$('#num_art').val());
var html='<tr class="art'+cont+'">';
var i=0;
//for(i=0;i<n;i++)

//alert(numRef.length);
if(1)
{
//alert('i: '+i);

html+='<td class="art'+cont+'" align="center" valign="top">'+(cont+1)+'</td>';




//cod barras
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_codBarras" name="cod_bar'+cont+'" type="text" id="cod_bar'+cont+'" value="" onKeyUp="cod($(this),\'mod\','+cont+',1);" onBlur="save_fc('+cont+');dup_cam($(\'#cod_bar'+cont+'\'),$(\'#cod_barH'+cont+'\'));" placeholder="Cod. Barras">';

html+='<input  class="art'+cont+'" name="cod_barH'+cont+'" type="hidden" id="cod_barH'+cont+'" value="" onKeyUp="cod($(this),\'mod\','+cont+',1);"></td>';

//ref
html+='<td class="art'+cont+'" align="center" valign="top" colspan=2><input  class="art'+cont+'" name="id'+cont+'" type="hidden" id="id'+cont+'" value="'+ID+'"><input onKeyUp="cod($(this),\'mod\','+cont+',1);" class="art'+cont+' fc_ref" name="ref'+cont+'" type="text" id="ref'+cont+'" value="" onBlur="save_fc('+cont+');dup_cam($(\'#ref'+cont+'\'),$(\'#refH'+cont+'\'));busq($(this),'+cont+',\'mod\');" placeholder="Referencia"></td>';


if(usarSerial==1){html+='<td class="art'+cont+'" align="center" valign="top" colspan=2><input onKeyUp="" class="art'+cont+' fc_ref" name="serial_art'+cont+'" type="text" id="serial_art'+cont+'" value="" onBlur="save_fc('+cont+');"></td>';}
if(usarCertImport==1){html+='<td class="art'+cont+'" align="center" valign="top" colspan=2><input onKeyUp="" class="art'+cont+' fc_ref" name="cert_import'+cont+'" type="text" id="cert_import'+cont+'" value="" onBlur="save_fc('+cont+');"></td>';}

//cantidad
html+='<td class="art'+cont+'" align="center" valign="top"><input  class="art'+cont+' fc_cant" name="cant'+cont+'" type="text" id="cant'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();cantLim('+cont+');save_fc('+cont+');" onBlur="" onChange="cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();" placeholder="Cant."></td>';	

//CantLim
html+='<input class="art'+cont+'" name="cantLim'+cont+'" type="hidden" id="cantLim'+cont+'" value=""></td>';


//fraccion
if(usarFracc==1){

html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_frac" name="fracc'+cont+'" type="text" id="fracc'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();save_fc('+cont+');" onBlur="" placeholder="Fraccion"></td>';
	
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="unidades'+cont+'" type="text" id="unidades'+cont+'" value="" onKeyUp="calc_cant($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();save_fc('+cont+');" onChange="" placeholder="Uni."></td>';
	
}
else{
html+='<td class="art'+cont+' uk-hidden" align="center" valign="top"><input class="art'+cont+' fc_frac" name="fracc'+cont+'" type="text" id="fracc'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();save_fc('+cont+');" onBlur=""></td>';
	
html+='<td class="art'+cont+' uk-hidden" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="unidades'+cont+'" type="text" id="unidades'+cont+'" value="" onKeyUp="calc_cant($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();save_fc('+cont+');" onChange=""></td>';	
	
}

//des
html+='<td class="art'+cont+'" align="center" valign="top"><textarea class="art'+cont+' fc_des" name="des'+cont+'"  id="des'+cont+'" value="" onBlur="save_fc('+cont+');" placeholder="Descripcion Producto"></textarea></td>';


// DES_FULL
if(usar_des_full==1)html+='<td class="art'+cont+'" align="center" valign="top"><textarea class="art'+cont+' fc_des" name="des_full'+cont+'"  id="des_full'+cont+'" value="" onBlur="save_fc('+cont+');" placeholder="Descripcion adicional"></textarea></td>';

//ubicacion
if(usa_ubica==1){
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="ubicacion'+cont+'" type="text" id="ubicacion'+cont+'" value="" onKeyUp="" onBlur="save_fc('+cont+');"" placeholder="Ubicacion"></td>';
}


//color
if(usarColor==1){html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="color'+cont+'" type="text" id="color'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Color"></td>';}

// datos adicionales ropa

if(ropa_campos_extra==1){
	//cod color
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="cod_color'+cont+'" type="text" id="cod_color'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="CodColor"></td>';

// vigencia
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="vigencia_inicial'+cont+'" type="text" id="vigencia_inicial'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Vigencia"></td>';

// grupo destino
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="grupo_destino'+cont+'" type="text" id="grupo_destino'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Vigencia"></td>';
}

if(usar_datos_motos==1){
// linea
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="linea'+cont+'" type="text" id="linea'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Linea Moto"></td>';	
// modelo
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="modelo'+cont+'" type="text" id="modelo'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Modelo"></td>';
// num_motor
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="num_motor'+cont+'" type="text" id="num_motor'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Num Motor"></td>';
// num_chasis
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="num_chasis'+cont+'" type="text" id="num_chasis'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Num Chasis"></td>';
// cilindraje
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_color" name="cilindraje'+cont+'" type="text" id="cilindraje'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Cilindraje"></td>';
// consecutivo_proveedor
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="consecutivo_proveedor'+cont+'" type="text" id="consecutivo_proveedor'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Consecutivo Proveedor"></td>';
}

//talla
if(usarTalla==1)html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_talla" name="talla'+cont+'" type="text" id="talla'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Talla"></td>';



//presentacion
if(CNC==1 && !esVacio(preOpt)){
html+='<td class="art'+cont+'" align="center" valign="top"><select class="art'+cont+' fc_presentacion" name="presentacion'+cont+'" id="presentacion'+cont+'" onChange="save_fc('+cont+');">'+preOpt+'</select></td>';
}
else{
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_presentacion" name="presentacion'+cont+'" type="text" id="presentacion'+cont+'" value="UNIDAD" onKeyUp="" onBlur="save_fc('+cont+');" placeholder="Presentacion"></td>';
}



//fabricante
//
if(CNC==1 && !esVacio(fabOpt)){
html+='<td class="art'+cont+'" align="center" valign="top"><select class="art'+cont+' fc_fab" name="fabricante'+cont+'" id="fabricante'+cont+'" onBlur="save_fc('+cont+');">'+fabOpt+'</select></td>';
}
else{
html+='<td class="art'+cont+'" align="center" valign="top"><input  class="art'+cont+' fc_fab" name="fabricante'+cont+'" type="text" id="fabricante'+cont+'" value="" onKeyUp="" onBlur="save_fc('+cont+');" placeholder="Fabricante"></td>';		
}


//clase
if(CNC==1 && !esVacio(clasesOpt)){
html+='<td class="art'+cont+'" align="center" valign="top"><select  class="art'+cont+' fc_clase" name="clase'+cont+'" id="clase'+cont+'" onChange="save_fc('+cont+');">'+clasesOpt+'</select></td>';
}
else{
html+='<td class="art'+cont+'" align="center" valign="top"><input  class="art'+cont+' fc_clase" name="clase'+cont+'" type="text" id="clase'+cont+'" value="" onKeyUp="" onBlur="save_fc('+cont+');" placeholder="Clase"></td>';	
}

//aplica_vehi
if(usar_aplica_vehi==1)html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_aplica_vehi" name="aplica_vehi'+cont+'" type="text" id="aplica_vehi'+cont+'" value="" onKeyUp="" onChange="save_fc('+cont+');" placeholder="Aplicacion Vehiculos"></td>';

//fecha vencimiento
if(usarFechaVenci==1){
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_fechaVen" name="fecha_vencimiento'+cont+'" type="date" id="fecha_vencimiento'+cont+'" value="" onBlur="save_fc('+cont+');dup_cam($(\'#fecha_vencimiento'+cont+'\'),$(\'#fecha_vencimientoH'+cont+'\'));" onKeyUp="//alert(\'H:\'+$(\'#fecha_vencimientoH'+cont+'\').val());"></td>';

html+='<input  class="art'+cont+'" name="fecha_vencimientoH'+cont+'" type="hidden" id="fecha_vencimientoH'+cont+'" value="0000-00-00" onKeyUp="" onChange=""></td>';
}
// campos adicionaes 01 & 02

if(usar_campos_01_02==1){
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_codBarras" name="campo_add_01'+cont+'" type="text" id="campo_add_01'+cont+'" value="" onKeyUp="" onChange="" onKeyDown="" placeholder="'+label_campo_add_01+'" onBlur="save_fc('+cont+');"></td>';

html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_fab" name="campo_add_02'+cont+'" type="text" id="campo_add_02'+cont+'" value="" onKeyUp="" onChange="" onKeyDown="" placeholder="'+label_campo_add_02+'" onBlur="save_fc('+cont+');"></td>';	
	
	
}
//costo
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_costo" name="costo'+cont+'" type="text" id="costo'+cont+'" value="" onKeyUp="tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');puntoa($(this));tot();" onChange="nanC($(this));" onKeyDown="//add_row_com($(this),'+cont+');" onBlur="save_fc('+cont+');" placeholder="Costo"></td>';


//dcto
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="/*pvp_costo_com($(\'#pvp'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#iva'+cont+'\'));*/tot();" class="art'+cont+' fc_dcto" name="dcto'+cont+'" type="text" id="dct'+cont+'" value="0"  onChange="nan($(this));/*pvp_costo_com($(\'#pvp'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#iva'+cont+'\'));*/tot();save_fc('+cont+');"></td>';

//tipo dcto
//html+='<td width="60" class="art'+cont+'" valign="top"><select style="width:50px;top:10px" class="art'+cont+'" name="tipo_dcto'+cont+'" id="tipo_dcto'+cont+'" onChange="cto($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'));cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();save_fc('+cont+');"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td>';


//util
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();" class="art'+cont+' fc_uti" name="util'+cont+'" type="text" id="util'+cont+'" value="0" style="width:35px;" onChange="nan($(this));tot();" onBlur="save_fc('+cont+');" placeholder="Util"></td>';



//impuesto saludable %
html+='<td align="center" valign="top" class="art'+cont+'">'+eleImpuestoSaludable('tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();save_fc('+cont+');',0,cont)+'</td>';


//iva%
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();" class="art'+cont+' fc_iva" name="iva'+cont+'" type="text" id="iva'+cont+'" value="" style="" onChange="nan($(this)); " onBlur="save_fc('+cont+');" placeholder="IVA"></td>';

//imp_consumo %
var classHide_consumo=' uk-hidden';
if(impuestos_consumo==1){classHide_consumo=' ';}
html+='<td align="center" valign="top" class="art'+cont+' '+classHide_consumo+'"><input onKeyUp="tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();" class="art'+cont+' '+classHide_consumo+' fc_uti" name="impuesto_consumo'+cont+'" type="text" id="impuesto_consumo'+cont+'" value="" style="width:30px;" onChange="nan($(this)); " onBlur="save_fc('+cont+');" placeholder="Consumo"></td>';


//pvp
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_pvp" name="pvp'+cont+'" type="text" id="pvp'+cont+'" value="" onKeyUp="puntoa($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();" onBlur="save_fc('+cont+');" placeholder="PvP"></td>';


if(PVP_CRE==1){
//pvpCre
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_pvp" name="pvpCre'+cont+'" type="text" id="pvpCre'+cont+'" value="" onKeyUp="puntoa($(this));" onBlur="save_fc('+cont+');" placeholder="Pcredito"></td>';
}


if(PVP_MAY==1){
//pvpMay
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_pvp" name="pvpMay'+cont+'" type="text" id="pvpMay'+cont+'" value="" onKeyUp="puntoa($(this));" onBlur="save_fc('+cont+');" placeholder="Pmayorista"></td>';
}


//tot
html+='<td class="art'+cont+'" align="center" valign="top"><input  readonly class="art'+cont+' fc_stot" name="v_tot'+cont+'" type="text" id="v_tot'+cont+'" value="" onKeyUp="add_row_com($(this),'+cont+');"></td>';

// botones
html+='<td><!--<span style="color:red">'+ID+'</span>--><img onMouseUp="eli_fac_com($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="30px" heigth="21px" ><br><a href="#" onClick="copiaRow('+cont+')" class="art'+cont+' uk-icon-hover uk-icon-medium uk-icon-copy"></a></td></tr>';


$row=$(html);
$row.appendTo('#articulos');
$('#cod_bar'+cont).focus();


//alert('for{cont:'+cont);
cont++;
}
//alert('cont:'+cont);
$('#num_ref').prop('value',cont);
//$('#addplus').focus();

	});// CIERRE AJAX_B
}/// cierre IF refNum
	};
function copiaRow(position)
{
	var codBar=$('#cod_bar'+position).val();
	var lastPosition = cont;
	console.log(lastPosition);
	
	if( ( !esVacio(codBar) ) )
	{
		addinv_mod();
		
		setTimeout(function(){
			var oldValueTemp = $('#des'+position).val();
			$('#des'+lastPosition).prop('value', oldValueTemp );
		
			
			oldValueTemp = $('#fabricante'+position).val();
			$('#fabricante'+lastPosition).prop('value', oldValueTemp );
			
			oldValueTemp = $('#clase'+position).val();
			$('#clase'+lastPosition).prop('value',oldValueTemp );
			
			oldValueTemp = $('#costo'+position).val();
			console.log('costoAnt::'+oldValueTemp+', -#costo'+lastPosition);
			$('#costo'+lastPosition).prop('value', oldValueTemp );
			console.log('costoCopy->:'+$('#costo'+lastPosition).val()+', -#costo'+lastPosition);
			
			oldValueTemp = $('#dct'+position).val();
			$('#dct'+lastPosition).prop('value', oldValueTemp );
			
			oldValueTemp = $('#iva'+position).val();
			$('#iva'+lastPosition).prop('value', oldValueTemp );
			
			oldValueTemp = $('#pvp'+position).val();
			$('#pvp'+lastPosition).prop('value', oldValueTemp );
		}, 1500);
	}
}
function eli_fac_com2(cod,feVenH,c)
{
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_fac=$('#num_fac').val();
	 var ID=$('#id'+c).val();
	 var codBar=cod;
	var ref=$('#ref'+c).val();
	 var fv=feVenH;
	 var nit_pro=$('#nit').val();
	 //alert('ref:'+ref+', num_fac:'+n_fac+' NIT:'+nit_pro+', fechaVen:'+fv+' c:'+$('#fecha_vencimiento'+c).prop('id'));
	 if($("#des"+c+"").length==0)$det='la Fila?';
	 else $det=$("#des"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
//confirm("Desea borrar '"+$det+"' "+fv+" ?")
	 if(0)
	 {
		
		$.ajax({
			url:'ajax/del_art_com.php',
			data:{num_fac:n_fac,ref:ref,codBar:codBar,nit_pro:nit_pro,fecha_ven:fv,id:ID} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
//alert(resp);
			
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 save_fc(-1);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	
};
function eli_fac_com(c)
{
	 var $eliRow=$(".art"+c);
	 var $det;
	 var ID=$('#id'+c).val();
	 var n_fac=$('#num_fac').val();
	 var codBar=$('#cod_bar'+c).val();
	var ref=$('#ref'+c).val();
	 var fv=$('#fecha_vencimiento'+c).prop('value');
	 var nit_pro=$('#nit').val();
	 //alert('ref:'+ref+', num_fac:'+n_fac+' NIT:'+nit_pro+', fechaVen:'+fv+' c:'+$('#fecha_vencimiento'+c).prop('id'));
	 if($("#des"+c+"").length==0)$det='la Fila?';
	 else $det=$("#des"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		
		$.ajax({
			url:'ajax/del_art_com.php',
			data:{num_fac:n_fac,ref:ref,codBar:codBar,nit_pro:nit_pro,fecha_ven:fv,id:ID} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
//alert(resp);
			
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 save_fc(-1);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	
};	
function addinv_dev()
{
var $row;
var n=parseInt($('#num_art').val());
//alert('$n:'+typeof($('#num_art').val())+' '+$('#num_art').val());
var html='<tr class="art'+cont+'">';
var i=0;

//alert('cont: '+cont);

html+='<td class="art'+cont+'" align="center" valign="top">'+(cont+1)+'</td>';



//ref
html+='<td class="art'+cont+'" align="center" valign="top"><input onKeyUp="cod($(this),\'dev\','+cont+',0);" class="art'+cont+' fc_ref" name="ref'+cont+'" type="text" id="ref'+cont+'" value="" ></td><td valign="top"><img style="cursor:pointer" title="Buscar" onMouseUp="busq($(\'#ref'+cont+'\'),'+cont+',0);" src="Imagenes/search128x128.png" width="25" height="25" /></td>';


//cod barras
html+='<td class="art'+cont+'" align="center" valign="top"><input   style="" class="art'+cont+' fc_codBarras" name="cod_bar'+cont+'" type="text" id="cod_bar'+cont+'" value="" onKeyUp="cod($(this),\'dev\','+cont+',0);" onChange=""></td>';

//cantidad
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="cant'+cont+'" type="text" id="cant'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();cantLim('+cont+');" onChange="cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();">';	

//CantLim

html+='<input style="width:30px" class="art'+cont+'" name="cantLim'+cont+'" type="hidden" id="cantLim'+cont+'" value=""></td>';	


//fraccion
if(usarFracc==1){
	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="fracc'+cont+'" type="text" id="fracc'+cont+'" value="" onKeyUp="calc_uni($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();" onChange=""></td>';

	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="unidades'+cont+'" type="text" id="unidades'+cont+'" value="" onKeyUp="calc_cant($(\'#cant'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));tot();" onChange=""></td>';

}

//des
html+='<td class="art'+cont+'" align="center" valign="top"><textarea style="width:100px" class="art'+cont+'" name="des'+cont+'"  id="des'+cont+'" value="" ></textarea></td>';



//color
if(usarColor==1)html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="color'+cont+'" type="text" id="color'+cont+'" value="" onKeyUp="" onChange=""></td>';

//talla
if(usarTalla==1)html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:40px" class="art'+cont+'" name="talla'+cont+'" type="text" id="talla'+cont+'" value="" onKeyUp="" onChange=""></td>';



//presentacion
//html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="presentacion'+cont+'" type="text" id="presentacion'+cont+'" value="" onKeyUp="" onChange="">';

if(CNC==1 && !esVacio(preOpt)){
	html+='<td class="art'+cont+'" align="center" valign="top"><select style="width:110px" class="art'+cont+'" name="presentacion'+cont+'" id="presentacion'+cont+'" onChange="">'+preOpt+'</select></td>';
}
else{
	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="presentacion'+cont+'" type="text" id="presentacion'+cont+'" value="UNIDAD" onKeyUp="" onChange=""></td>';
	}


//fabricante
//html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="fabricante'+cont+'" type="text" id="fabricante'+cont+'" value="" onKeyUp="" onChange="">';

if(CNC==1 && !esVacio(fabOpt)){
	html+='<td class="art'+cont+'" align="center" valign="top"><select style="width:110px" class="art'+cont+'" name="fabricante'+cont+'" id="fabricante'+cont+'" onChange="">'+fabOpt+'</select></td>';
}
else{
	html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="fabricante'+cont+'" type="text" id="fabricante'+cont+'" value="" onKeyUp="" onChange=""></td>';
}


//clase
//html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="clase'+cont+'" type="text" id="clase'+cont+'" value="" onKeyUp="" onChange="">';

if(CNC==1 && !esVacio(clasesOpt)){
html+='<td class="art'+cont+'" align="center" valign="top"><select style="width:110px" class="art'+cont+'" name="clase'+cont+'" id="clase'+cont+'" onChange="">'+clasesOpt+'</select></td>';
}
else{
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:80px" class="art'+cont+'" name="clase'+cont+'" type="text" id="clase'+cont+'" value="" onKeyUp="" onChange=""></td>';
}


if(usarFechaVenci==1){
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:150px" class="art'+cont+'" name="fecha_vencimiento'+cont+'" type="date" id="fecha_vencimiento'+cont+'" value="" onKeyUp="" onChange=""></td>';

}


//tipo_descuento(opc,cost,pvp,per,iva,redondear_s_n);
//costo
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:90px" class="art'+cont+'" name="costo'+cont+'" type="text" id="costo'+cont+'" value="" onKeyUp="tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');puntoa($(this));tot();" onBlur="nanC($(this))" ></td>';


//tipo dcto
//html+='<td width="60" class="art'+cont+'" valign="top"><select style="width:50px;top:10px" class="art'+cont+'" name="tipo_dcto'+cont+'" id="tipo_dcto'+cont+'" onChange="cto($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'));cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td>';

//dcto  

html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tot();" class="art'+cont+'" name="dcto'+cont+'" type="text" id="dct'+cont+'" value="'+dct_STD+'" style="width:60px;" onChange="nan($(this));/*tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');*/tot();"></td>';
//html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="cto($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'));tot();" class="art'+cont+'" name="dcto'+cont+'" type="text" id="dct'+cont+'" value="0" style="width:30px;" onChange="nan($(this));cant_dcto_com($(\'#tipo_dcto'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#cant'+cont+'\'));tot();"></td>';


//util  

html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tot();" class="art'+cont+'" name="util'+cont+'" type="text" id="util'+cont+'" value="'+dct_STD+'" style="width:35px;" onChange="nan($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();"></td>';


//iva%
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="tot();" class="art'+cont+'" name="iva'+cont+'" type="text" id="iva'+cont+'" value="" style="width:30px;" onBlur="nan($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');"></td>';


//pvp
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:70px"  class="art'+cont+'" name="pvp'+cont+'" type="text" id="pvp'+cont+'" value="" onKeyUp="puntoa($(this));tipo_descuento($(\'#tipo_op\'),$(\'#costo'+cont+'\'),$(\'#pvp'+cont+'\'),$(\'#util'+cont+'\'),$(\'#iva'+cont+'\'),\''+redon_pvp_costo+'\','+cont+');tot();" ></td>';


//tot
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:90px" class="art'+cont+'" name="v_tot'+cont+'" type="text" id="v_tot'+cont+'" value="" readonly></td><td><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="21px" heigth="21px" ></td></tr>';


$row=$(html);
$row.appendTo('#articulos');
$('#ref'+cont).focus();
//alert('for{cont:'+cont);
cont++;
ref_exis++;

//alert('cont:'+cont);
$('#num_ref').prop('value',cont);
$('#exi_ref').prop('value',ref_exis);

	
	};
function addinv_tras()
{

var $row;
var n=parseInt($('#num_art').val());
//alert('$n:'+typeof($('#num_art').val())+' '+$('#num_art').val());
var html='<tr class="art'+cont+'">';
var i=0;
for(i=0;i<n;i++)
{
//alert('i: '+i);
//cantidad
html+='<td class="art'+cont+'" align="center" valign="top">'+(cont+1)+'</td>';

html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:30px" class="art'+cont+'" name="cant'+cont+'" type="text" id="cant'+cont+'" value="" onKeyUp="tot();cantLim('+cont+');" onBlur="nanC($(this))">';	

//CantLim

html+='<input style="width:30px" class="art'+cont+'" name="cantLim'+cont+'" type="hidden" id="cantLim'+cont+'" value=""></td>';	

//ref
html+='<td class="art'+cont+'" align="center" valign="top"><input onKeyUp="cod_tras(this,\'add\','+cont+');" class="art'+cont+'" name="ref'+cont+'" type="text" id="ref'+cont+'" value="" style="width:120px;top:10px"></td><td valign="top"><img style="cursor:pointer" title="Buscar" onMouseUp="busq($(\'#ref'+cont+'\'),'+cont+');" src="Imagenes/search128x128.png" width="25" height="25" /></td>';

//des
html+='<td class="art'+cont+'" align="center" valign="top"><textarea style="" class="art'+cont+'" name="des'+cont+'"  id="des'+cont+'" value=""></textarea></td>';

//costo
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="costo'+cont+'" type="text" id="costo'+cont+'" value="" onKeyUp="puntoa($(this));tot();CalculaPVP($(\'#costo'+cont+'\'),$(\'#uti'+cont+'\'),$(\'#iva'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#pvp'+cont+'\'));" onBlur="nanC($(this))"></td>';

//dcto
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="CalculaPVP($(\'#costo'+cont+'\'),$(\'#uti'+cont+'\'),$(\'#iva'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#pvp'+cont+'\'));" class="art'+cont+'" name="dcto'+cont+'" type="text" id="dct'+cont+'" value="" style="width:60px;" onBlur="nan($(this))"></td>';

//iva%
html+='<td align="center" valign="top" class="art'+cont+'"><input onKeyUp="CalculaPVP($(\'#costo'+cont+'\'),$(\'#uti'+cont+'\'),$(\'#iva'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#pvp'+cont+'\'));tot()" class="art'+cont+'" name="iva'+cont+'" type="text" id="iva'+cont+'" value="" style="width:30px;" onBlur="nan($(this))"></td>';


//html+='<td align="center" valign="top" class="art'+cont+'"><input class="art'+cont+'" name="v_iva'+cont+'" type="text" id="v_iva'+cont+'" value="" onChange="util('+cont+');val_tot('+cont+');" onKeyUp="util('+cont+');"><input class="art'+cont+'" name="v_ivaH'+cont+'" type="hidden" id="v_iva'+cont+'H" value=""></td>';


//gana%
html+='<td class="art'+cont+'" align="center" valign="top"><input style="width:30px" class="art'+cont+'" name="uti'+cont+'" type="text" id="uti'+cont+'" value="" onKeyDown="//addrow(this,'+cont+',$(\'#cant'+(cont+1)+'\'));" onKeyUp="CalculaPVP($(\'#costo'+cont+'\'),$(\'#uti'+cont+'\'),$(\'#iva'+cont+'\'),$(\'#dct'+cont+'\'),$(\'#pvp'+cont+'\'));" onBlur="nan($(this))" ></td>';


//pvp
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="pvp'+cont+'" type="text" id="pvp'+cont+'" value="" ></td>';

//html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="util'+cont+'" type="text" id="util'+cont+'" value="" onChange="puntoa(this)" onKeyUp="puntoa(this)"><input class="art'+cont+'" name="utilH'+cont+'" type="hidden" id="util'+cont+'H" value=""></td>';


//tot
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="v_tot'+cont+'" type="text" id="v_tot'+cont+'" value="" ></td><td><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="21px" heigth="21px" ></td></tr>';


$row=$(html);
$row.appendTo('#articulos');
$('#cant'+cont).focus();
//alert('for{cont:'+cont);
cont++;
}
//alert('cont:'+cont);
$('#num_ref').prop('value',cont);
//$('#addplus').focus();

	
	};


function eli_tras(c)
{
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_fac=$('#num_fac').val();
	 var ref=$('#ref'+c).val();

	// alert('ref:'+ref+', num_fac:'+n_fac+' NIT:'+nit_pro);
	 if($("#des"+c+"").length==0)$det='la Fila?';
	 else $det=$("#des"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		
		$.ajax({
			url:'ajax/del_art_tras.php',
			data:{num_fac:n_fac,ref:ref,nit_pro:nit_pro} ,
			type:'POST',
			dataType:'text',
			success:function(resp){//alert(resp);
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	
};
function eli(c)
{
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_fac=$('#num_fac').val();
	 var ref=$('#ref'+c).val();
	 var nit_pro=$('#nit').val();
	// alert('ref:'+ref+', num_fac:'+n_fac+' NIT:'+nit_pro);
	 if($("#des"+c+"").length==0)$det='la Fila?';
	 else $det=$("#des"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
				 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 //cont--;
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	
};


   
function val_mod_fc(campo,campo2, tab, col,col2, Div,nitH,facH)
{
	//alert('nitH:'+nitH+' nit:'+campo2+' numFac:'+campo+' numFacH:'+facH);
	if(nitH!=campo2||facH!=campo)
	{
		validar_2c(campo,campo2, tab, col,col2, Div);
	}
	else {
		//alert('sin cambios!');
		}
	
};
	
	
function nom_pro(n)
{
	$('#nit').prop('value',n.value);
	pro(n.value);
	//$('#provedor').prop('value',$(n).html());
	
	};
function pro(n)
{
if(!esVacio(n)){
$.ajax({
		url:'ajax/val_pro.php',
		data:{nit:n},
	    type: 'POST',
		dataType:'JSON',
		success:function(response){//alert(text);
			
			//var r=text.split('?');
			var resp=response[0].respuesta*1;
			if(resp!=0){
			$('#provedor').prop('value',response[0].nombre);
			$('#dir').prop('value',response[0].direccion);
			$('#tel').prop('value',response[0].telefono);
			$('#mail').prop('value',response[0].mail);
			$('#ciudad').prop('value',response[0].ciudad);
			$('#fax').prop('value',response[0].fax);
			if($('#razsoc').length!=0){
			
			 $('#razsoc').prop('value',response[0].nombre);
			 $('#snombr').prop('value',response[0].snombr);
			 $('#apelli').prop('value',response[0].apelli);
			 $('#depcli').prop('value',response[0].depcli);
			 $('#loccli').prop('value',response[0].loccli);
			 $('#nomcon').prop('value',response[0].nomcon);
			 $('#razsoc').prop('value',response[0].razsoc);
			 

			 $("#claper option[value='"+response[0].claper+"']").prop('selected','selected');
			 $("#coddoc option[value='"+response[0].coddoc+"']").prop('selected','selected');
			 $("#regtri option[value='"+response[0].regtri+"']").prop('selected','selected');	
			 $("#paicli option[value='"+response[0].paicli+"']").prop('selected','selected');
				}
			}
			else {}
			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
}
};


function add_art_com(ele,c){ 
//clearTimeout(timeOutCod);

remove_pop($('#modal'));
		// alert('addArtCOm'+ele);
		 
        $.ajax({
		url:'ajax/add_art_dev.php',
		data:{ref:$('#ref'+c).val(),cod_bar:$('#cod_bar'+c).val(),fe:$('#cantLim'+c).val(),nfFac:$('#num_fac').val(),nitPro:$('#nit').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		//alert('text:['+text+']; resp:'+resp);
		
		//alert(c);
			 
			  
			  var $busca=$('#ref'+c);
			
			 if(esVacio($busca.val()))$busca=$('#cod_bar'+c);
			 if(resp==-101010 && resp!=-2)
			 {
				 busq($busca,c,'dev');
			 }
			 else if(resp==-2){
			  //alert('.:Articulo No Encontrado:.');
			busq($busca,c,'dev');
				}
			 else if((resp==0|| resp!=-101010) && resp!=-2 ){
			   var $cod=$(ele);			  
               var det="det_",val_u="val_u";
			   var r=text.split('|');
			   //alert(text);
			   //vsi=redondeo(vsi);
			   if(r[18]==0 && resp!=-2)$('#cant'+c).prop('value',0);
			   else {
			  $('#cant'+c).prop('value',''); 
			  //$('#cant'+c).prop('style','color:red');
				
			   $('#unidades'+c).prop('value',r[16]);}
			   if($('#cantLim'+c).length!=0)$('#cantLim'+c).prop('value',r[0]);
			   $('#des'+c).prop('value',r[2]);
			   $('#costo'+c).prop('value',puntob(r[3]));
			   $('#iva'+c).prop('value',r[4]);
			   $('#util'+c).prop('value',r[5]);
			   $('#DESCUENTO2').prop('value',r[8]);
			   $('#pvp'+c).prop('value',puntob(r[6]));
			   $('#ref'+c).prop('value',r[7]);
			   $('#refH'+c).prop('value',r[7]);
			   //$('#ref'+c).prop('readonly',true);
			   $('#color'+c).prop('value',r[9]);
			   $('#talla'+c).prop('value',r[10]);
			   $('#cod_bar'+c).prop('value',r[11]).prop("onkeyup", null);
			   $('#ref'+c).prop("onkeyup", null);
			   $('#imgBusq'+c).prop("onmouseup", null);
			   $('#cod_barH'+c).prop('value',r[11]);
			   $('#clase'+c).prop('value',r[12]);
			   $('#fabricante'+c).prop('value',r[13]);
			   $('#presentacion'+c).prop('value',r[14]);
			   $('#fracc'+c).prop('value',r[15]);
			  
			   $('#fecha_vencimiento'+c).prop('value',r[17]);
			   $('#fecha_vencimientoH'+c).prop('value',r[17]);
			   $('#cant'+c).focus();
			   addArtModAlert=0;
			   if(addArtAlert==1)/*alert('Producto Agregado')*/;
			   del_pop($('#modal'));
			   tot();
			   //$('#talla'+c).prop('readonly',true);
			  // $('#color'+c).prop('readonly',true);
			   //$('#cod_bar'+c).prop('readonly',true);
sr=1;
			   
			   
			  
                
			
			}
			
		 

			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
		
	

   };
function add_art_tras(ele,c) { 
var id='ref';  
var $rep='',$busq='';
var repCheck=0;


	for(i=0;i<cont;i++)
	{
	  $busq=$('#'+id+''+i);
	  if($busq.lenght!=0&&$busq.prop('id')!=ele.id&&$busq.val()==ele.value)
	  {
		  $rep=$busq;
		  //alert('Producto repetido :'+$rep.val());
		  $rep.focus();
		  tot();
		  repCheck=1;
	  }
	}
	
	if(repCheck==1){
		 if(confirm('Si desea agregar una unidad hagalo manualmente'))
		 {}
		 else
		 {}
			 
		 }
		 else
		 {
		 
        $.ajax({
		url:'ajax/add_art_tras.php',
		data:{ref:ele.value},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		//alert('text:['+text+']; resp:'+resp);
		
		  /*  if(resp==0) {
			 alert(".:Articulo AGOTADO:.");
			 var $cod=$(ele);
		     $cod.prop("value","");
		    
			
			 
			 }
			 
			 */
			 
			  //if(resp==-2 || resp==0){alert('.:Articulo No Encontrado:.');}
			 
			 if(resp==0||resp!=-2){
			   var $cod=$(ele);			  
               var det="det_",val_u="val_u";
			   var r=text.split('|');
			   //alert(text);
			   //vsi=redondeo(vsi);
			   if($('#cantLim'+c).length!=0)$('#cantLim'+c).prop('value',r[0]);
			   if(r[0]!=0){
				   //alert(r[0]);
				   if(r[0]>=$('#cant'+c).val()*1 ){
			   $('#des'+c).prop('value',r[2]);
			   $('#costo'+c).prop('value',puntob(r[3]));
			   $('#iva'+c).prop('value',r[4]);
			   $('#uti'+c).prop('value',r[5]);
			   $('#pvp'+c).prop('value',puntob(r[6]));
			   $('#ref'+c).prop('value',r[7]);
			   $('#ref'+c).prop('readonly',true);
			   $('#btn').show();
			   
			   tot();
				   }
				   else {
					 warrn_pop('La cantidad ingresada supera el Limite('+r[0]+')');
				   $('#ref'+c).blur();
				   $('#btn').hide();
				   }
			   }
			   else {warrn_pop('.:Articulo AGOTADO:.');$('#ref'+c).blur();$('#btn').hide();}
			   
			   var id='ref';  
var $rep='',$busq='';
var repCheck=0;
	for(i=0;i<cont;i++)
	{
	  $busq=$('#'+id+''+i);
	  if($busq.lenght!=0&&$busq.prop('id')!=ele.id&&$busq.val()==ele.value)
	  {
		  $rep=$busq;
		  $rep.focus();
		  //alert('Producto repetido :'+$rep.val());
		  //$rep.focus();
		  tot();
		  repCheck=1;
	  }
	}
	
	if(repCheck==1)
	{
		       $('#des'+c).prop('value',' ');
			   $('#costo'+c).prop('value',' ');
			   $('#iva'+c).prop('value',' ');
			   $('#uti'+c).prop('value',' ');
			   $('#pvp'+c).prop('value',' ');
			   $('#ref'+c).prop('value',' ');
			   $('#v_tot'+c).prop('value',' ');
			   
			   
			   
	}
	if(repCheck==0)
	{
		
		
		
	}
                
			
			}
			
		 

			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
		
		 }//fin else


   };
var addArtModAlert=0,GLOBAL_rep=0;
function add_art_com_mod(ele,c) { 
//alert('add art MOD!');
remove_pop($('#modal'));
		// alert('else');
		 //alert('ref:'+$('#ref'+c).val()+', cod:'+$('#cod_bar'+c).val()+'fe:'+$('#cantLim'+c).val());
        $.ajax({
		url:'ajax/add_art_com.php',
		data:{ref:$('#ref'+c).val(),cod_bar:$('#cod_bar'+c).val(),fe:$('#cantLim'+c).val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){
			var r=text.split('|');
			
		var resp=parseInt(r[19]);
		//alert('text:['+text+']; resp:'+resp);					  
	    var $busca=$('#ref'+c);
			
			 if(esVacio($busca.val())){$busca=$('#cod_bar'+c);}
			 if(resp==-101010 && resp!=-2)
			 {
				 if(QFI==1){
					 warrn_pop('REPETIDO, SE HA SUMADO +1 CANTIDAD');
					  $('#ref'+c).prop('value','');
			   		  $('#refH'+c).prop('value','');
					  $('#cod_bar'+c).prop('value','');
					  $('#cod_barH'+c).prop('value','');
					  if(esVacio($('#ref'+c).val())){$('#cod_bar'+c).focus();}
						else{$('#ref'+c).focus();}
					 //busq($busca,c,'mod');
				 }
				 else {
					 
					 
				var feVen=$('#fecha_vencimiento'+c).val();
				var codBar=$('#cod_bar'+c).val();
				
			   $('#des'+c).prop('value',r[2]);
			   $('#costo'+c).prop('value',puntob(r[3]));
			   $('#iva'+c).prop('value',r[4]);
			   $('#util'+c).prop('value',r[5]);
			   $('#DESCUENTO2').prop('value',r[8]);
			   $('#pvp'+c).prop('value',puntob(r[6]));
			   $('#ref'+c).prop('value',r[7]);
			   $('#refH'+c).prop('value',r[7]);
			   //$('#ref'+c).prop('readonly',true);
			   $('#color'+c).prop('value',r[9]);
			   $('#talla'+c).prop('value',r[10]);
			   $('#cod_bar'+c).prop('value',r[11]).prop("onkeyup", null);
			   $('#ref'+c).prop("onkeyup", null);
			   $('#imgBusq'+c).prop("onmouseup", null);
			   $('#cod_barH'+c).prop('value',r[11]);
			   $('#clase'+c).prop('value',r[12]);
			   $('#fabricante'+c).prop('value',r[13]);
			   $('#presentacion'+c).prop('value',r[14]);
			   $('#fracc'+c).prop('value',r[15]);
			   
			   
				
				$('#fecha_vencimiento'+c).prop('value',r[17]);
			    $('#fecha_vencimientoH'+c).prop('value',r[17]);
			    $('#ubicacion'+c).prop('value',r[20]);
				$('#pvpMay'+c).prop('value',r[22]);
				$('#pvpCre'+c).prop('value',r[21]);
				//$('.id_100 option[value=val2]').attr('selected','selected');
				$('#impSaludable'+c+' option[value='+r[29]+']').attr('selected','selected');
				console.log('addMod impSaludable' + r[29]);
				
				
				if(usar_aplica_vehi==1){$('#aplica_vehi'+c).prop('value',r[23]);}
				
				if(ropa_campos_extra==1){
					$('#cod_color'+c).prop('value',r[26]);
					$('#vigencia_inicial'+c).prop('value',r[27]);
					$('#grupo_destino'+c).prop('value',r[28]);
				}
				
				if(usar_campos_01_02==1)
				{
					$('#campo_add_01'+c).prop('value',r[24]);
					$('#campo_add_02'+c).prop('value',r[25]);
					 
					
					}
				
				//alert('REPETIDO!! ');
				save_fc(c);
				$('#cod_bar'+c).focus();
				//alert('F*ck Duplicate '+feVen+', cod:'+codBar);
				//eli_fac_com2(codBar,feVenH,c);
				//location.assign('mod_fac_com.php?busq='+codBar+'&boton=Buscar&feVenR='+feVen);
				GLOBAL_rep=1;
					 }
			 }
			 else if(resp==-2){
			  //alert('.:Articulo No Encontrado:.');
			if(QFI!=1)busq($busca,c,'mod');
				}
			 else if((resp==0|| resp!=-101010) && resp!=-2 ){
			   var $cod=$(ele);			  
               var det="det_",val_u="val_u";
			   var r=text.split('|');
			   //alert(text);
			   //vsi=redondeo(vsi);
			   
			   
			   
			   /*
			   if(r[18]==0 && resp!=-2)$('#cant'+c).prop('value','');
			   else{$('#cant'+c).prop('value',r[0]);$('#cant'+c).prop('style','color:red');alert('REPETIDO 2');$('#unidades'+c).prop('value',r[16]);}
			   */
			   
			   
			   if(QFI!=1){$('#cant'+c).prop('value',"");}
			   else {$('#cant'+c).prop('value',1);}
			   if($('#cantLim'+c).length!=0)$('#cantLim'+c).prop('value',r[0]);
			   $('#des'+c).prop('value',r[2]);
			   $('#costo'+c).prop('value',puntob(r[3]));
			   $('#iva'+c).prop('value',r[4]);
			   $('#util'+c).prop('value',r[5]);
			   $('#DESCUENTO2').prop('value',r[8]);
			   $('#pvp'+c).prop('value',puntob(r[6]));
			   $('#ref'+c).prop('value',r[7]);
			   $('#refH'+c).prop('value',r[7]);
			   //$('#ref'+c).prop('readonly',true);
			   $('#color'+c).prop('value',r[9]);
			   $('#talla'+c).prop('value',r[10]);
			   $('#cod_bar'+c).prop('value',r[11]).prop("onkeyup", null);
			   $('#ref'+c).prop("onkeyup", null);
			   $('#imgBusq'+c).prop("onmouseup", null);
			   $('#cod_barH'+c).prop('value',r[11]);
			   $('#clase'+c).prop('value',r[12]);
			   $('#fabricante'+c).prop('value',r[13]);
			   $('#presentacion'+c).prop('value',r[14]);
			   $('#fracc'+c).prop('value',r[15]);
			  
			   $('#fecha_vencimiento'+c).prop('value',r[17]);
			   $('#fecha_vencimientoH'+c).prop('value',r[17]);
			   
			   $('#ubicacion'+c).prop('value',r[20]);
				$('#pvpMay'+c).prop('value',r[22]);
				$('#pvpCre'+c).prop('value',r[21]);

				$('#impSaludable'+c+' option[value='+r[29]+']').attr('selected','selected');
				console.log('addMod impSaludable ' + r[29]);
				
				if(usar_aplica_vehi==1){$('#aplica_vehi'+c).prop('value',r[23]);}
				
				if(ropa_campos_extra==1){
					$('#cod_color'+c).prop('value',r[26]);
					$('#vigencia_inicial'+c).prop('value',r[27]);
					$('#grupo_destino'+c).prop('value',r[28]);
				}
				
				if(usar_campos_01_02==1)
				{
					$('#campo_add_01'+c).prop('value',r[24]);
					$('#campo_add_02'+c).prop('value',r[25]);
					 
					
					}
			   save_fc(c);
			   if(QFI!=1){$('#cant'+c).focus();}
			   addArtModAlert=0;
			   if(addArtAlert==1){if(QFI==1){addinv_mod();}}/*alert('Producto Agregado')*/;
			   del_pop($('#modal'));
			   //$('#talla'+c).prop('readonly',true);
			  // $('#color'+c).prop('readonly',true);
			   //$('#cod_bar'+c).prop('readonly',true);
			   
				sr=1;
				
	
				   
			   
			  
                
			
			}
			
		 

			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
		
	


   }; 
   var rr=-1;
function set_rr(resp)
 {
	 rr=resp;
	// alert('Set rr:'+rr+', resp:'+resp);
 };
var sr=0;
function val_new_rowFC(c)
{
	var cod_bar=$('#cod_bar'+c).val();
	var ref=$('#ref'+c).val();
	var feVen=$('#fecha_vencimiento'+c).val();

	 
	if(esVacio($('#des'+c).val()) && !esVacio(cod_bar)){cod_bar='';ref='';}
	else{}
	//alert('val_newCom');
	$.ajax({
		url:'ajax/val_add_art_com.php',
		data:{ref:ref,codBar:cod_bar,fe:feVen},
	        type: 'POST',
		dataType:'text',
		success:function(text){
			//alert(text);
			var r=text.split('|');
			//alert(text+'r18:'+r[18]);
			if(r[18]==1111222333555)
			{
				
				//alert('REPETIDO!');
			 
			   $('#cant'+c).prop('value',r[0]); 
			   $('#unidades'+c).prop('value',r[16]);
			   warrn_pop('Producto REPETIDO, haga las modificaciones MANUALMENTE');
			   
			   
			  // alert('aftermath r3:'+r[3]+', r6:'+r[6]+'r0:'+r[0]);
			   $('#cant'+c).prop('value',r[0]);
				$('#cant'+c).prop('style','color:red');  
			   if($('#cantLim'+c).length!=0)$('#cantLim'+c).prop('value',r[0]);
			   $('#des'+c).prop('value',r[2]);
			   $('#costo'+c).prop('value',puntob(r[3]));
			   $('#iva'+c).prop('value',r[4]);
			   $('#util'+c).prop('value',r[5]);
			   $('#DESCUENTO2').prop('value',r[8]);
			   $('#pvp'+c).prop('value',puntob(r[6]));
			   $('#ref'+c).prop('value',r[7]);
			   $('#refH'+c).prop('value',r[7]);
			   //$('#ref'+c).prop('readonly',true);
			   $('#color'+c).prop('value',r[9]);
			   $('#ubicacion'+c).prop('value',r[19]);
			   $('#talla'+c).prop('value',r[10]);
			   $('#cod_bar'+c).prop('value',r[11]);
			   $('#cod_barH'+c).prop('value',r[11]);
			   $('#clase'+c).prop('value',r[12]);
			   $('#fabricante'+c).prop('value',r[13]);
			   $('#presentacion'+c).prop('value',r[14]);
			   $('#fracc'+c).prop('value',r[15]);
			  
			   $('#fecha_vencimiento'+c).prop('value',r[17]);
			   $('#fecha_vencimientoH'+c).prop('value',r[17]);
			   // alert('end');
			   hide_pop('#modal');
				sr=0;
				
				
				
			}
			else {


//alert('sr:'+sr);
if(sr==096)
{
var $busca=$('#ref'+c);

//alert('096:'+$('#ref'+c).val());

if(esVacio($busca.val())){$busca=$('#cod_bar'+c);}
//busq($busca,c,'mod');
sr=1;	
//add_art_com_mod(GLOBAL_n,c);
}

add_art_com_mod(GLOBAL_n,c);

		 
		}	
			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
		
};
function save_fc(c)
{
	tot();
	var form=document.forms['form_fac'];
	getPage($('#HTML_despues'),$('#fac_com'));
	var externals=$('.save_fc');
	var ExtString=externals.serialize();
	//var ExtString=$('#form_fac').serialize();
	var row=$('input.art'+c+',textarea.art'+c+',select.art'+c);
	var serRow='';
	var RowPK=$('#cod_bar'+c).val();
	var feVenH=$('#fecha_vencimientoH'+c).val();
	var desPK=$('#des'+c).val();
//	var colorPK=$('#color'+c).val();
	//var tallaPK=$('#talla'+c).val();
	//alert(RowPK);
	if(row.length!=0){serRow=row.serialize();}
	//alert(serRow);
	var Datos=ExtString+'&i='+c+'&'+serRow;
	if(fullF==1){
	var fullForm=$('#form_fac').serialize();
		fullForm=fullForm+'&i='+c+'&ff=1';
		Datos=fullForm;
	}
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	
	//&& !esVacio(colorPK) && !esVacio(tallaPK)
	
	
	//var repetido=val_new_rowFC(c);
	//(!esVacio(RowPK)  && !esVacio(desPK) )
	var repetido=0;
	if(repetido!=1){
		
		//&& (!val_fc(form))
	if( (1 || c==-1) ){
	$.ajax({
		url:'ajax/save_fc.php',
		data:Datos,
		type:'POST',
		dataType:'JSON',
		success:function(response){
			var text=response[0].respuesta*1;
			chk_util(c);
			if(text!=1){
				var cod=parseInt(text);
				var buzq='';
				if($('#busq').lenght!=0){buzq=$('#busq').val();}
				if((cod==1062|| GLOBAL_rep==1) && esVacio(buzq))
				{
				GLOBAL_rep=0;
				var feVen=$('#fecha_vencimiento'+c).val();
				var codBar=$('#cod_bar'+c).val();
				warrn_pop('REPETIDO!!');

				location.assign('mod_fac_com.php?busq='+codBar+'&boton=Buscar&feVenR='+feVen);}
					
				
				}
				else {
					$('#SUB').prop('value',puntob(response[0].SUB));
					$('#IVA').prop('value',puntob(response[0].IVA));
					$('#IMPUESTO_SALUDABLE').prop('value',puntob(response[0].IMPUESTO_SALUDABLE));
					$('#impuesto_consumo').prop('value',puntob(response[0].CONSUMO));
					$('#TOTAL').prop('value',puntob(response[0].TOT));
				}
			//$('<p>'+text+'</p>').appendTo('#salida');
			//if(c==-1)alert('Guardado con Exito');
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');}
		
		});
	}
	else {
if(!esVacio(RowPK)  && !esVacio(desPK) )warrn_pop('Repetido, Haga las modificaciones pertinentes');
		}
	}// fin no vacio
};
function close_fc(c)
{
	//tot();
	var form=document.forms['form_fac'];
	var $prom=$('#confirma');
	if(!esVacio($prom.val()))
	{
	var externals=$('.save_fc');
	var ExtString=externals.serialize();
	var row=$('input.art'+c+',textarea.art'+c);
	var serRow='';
	//var confirma=$('#confirma').val();
	if(row.length!=0)serRow=row.serialize();
	
	var Datos=ExtString+'&i='+c+'&'+serRow;
	
	if(val_fc(form)){}
	
	else{
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	blockForm($('#form_fac :input' ));
	if(confirm('Desea Cerrar esta  Factura de Compra?NO SE PERMITIRAN MODIFICACIONES'))
	{
	$.ajax({
		url:'ajax/cerrar_fc.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			
			//alert(text);

			//$('<p>'+text+'</p>').appendTo('#salida');
			if(text==1)
			{
				warrn_pop('FACTURA CERRADA');
				//alert(text);
				location.assign('compras.php');
				
				}
				else if(text==0){warrn_pop('La factura ya  esta CERRADA');}
				else {warrn_pop('ERROR: '+text);}
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
	}
	}
	
	}/////////// close ELSE val_fc
	
	
	
	
	
};
////////////////////
var GLOBAL_n=0;////
var GLOBAL_n2=0;
var GLOBAL_c=0;////

///////////////////
function add_row_com($ele,c)
{
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){
    event.which = event.charCode || event.keyCode;
}
	key=event.which;
	var ref=$('#ref'+c).val();
	var codBar=$('#cod_bar'+c).val();
	//alert(key);
	//alert(ref+', '+codBar)
	if((key==9 || key==39) && (!esVacio(codBar)))addinv_mod();
	
};
function cod($n,op,c,mod){
	//alert($n.val());
	//$('#addplus').mouseup()
	
	if(!esVacio($n.val()))
	{
   
   // $n.bind('keyup',function(e){
if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){
    event.which = event.charCode || event.keyCode;
}
	key=event.which;
	/*
	timeOutCod=setTimeout(function()
	{
		if(op=='add')add_art_com($n,c);
		else if(op=='mod')add_art_com_mod($n,c);
		
		},800);*/
	
	//alert('key:'+key+'op:'+op);
	
	if(key==13&&(op=='add')){val_new_rowFC(c);}
	else if(key==13&op=='dev'){busq_dev($n,c,mod);}
	else if(key==13&&op=='mod'){val_new_rowFC(c);}
	else if(key==13&&op=='tras'){add_art_tras();}
	else if(key==120&&op!='dev'){busq($n,c,mod);}
	else if(key==120&&op=='dev'){busq_dev($n,c,mod);}
	//});
	
	}
	
};
function cod_tras(n,op,c){
	//alert(n.value);
	//$('#addplus').mouseup();
	if(!esVacio(n.value))
	{
		
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){ event.which = event.charCode || event.keyCode;}
	key=event.which;
	if(key==13&&op=='add'){add_art_tras(n,c)}
	if(key==13&&op=='mod'){add_art_tras_mod(n,c)}
	if(key==120){busq_tras($(n),c);}
	
	}
};
function addrow(n,c,$f){
	//alert(n.value);
	//$('#addplus').mouseup();
	if(!esVacio(n.value))
	{
		
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){ event.which = event.charCode || event.keyCode;}
	key=event.which;
	if(key==13){
		$('#addplus').mouseup();
		//$f.focus();
		}
	
	if(key==120){
		//busq($(n),c);
		}
	}
	
};
function busq(n,c,mod)
{
	GLOBAL_n=n;
	GLOBAL_c=c;
	//alert('busq:'+n.val()+', GLOBAL_c:'+c);
	var $val=$('#Qtab');
	//alert(n.val());
	var $n=$(n);
	//alert($n.val());
	if(!esVacio($n.val()) && esVacio($('#des'+c).val())  ){
	if($val.length!=0){$val.remove();}

		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art2.php',
		data:{busq:$n.val(),c:c,mod:mod},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			/*$('#Qresp').append(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			open_pop('Resultado Busqueda','',text);
			remove_pop($('#modal'));
			//$("#n_pro").focus();
			}
			else {
				
				warrn_pop('No se encontraron sugerencias..');
				
				}
			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
		
		
	}
};
function busq_dev(n,c,mod)
{
	GLOBAL_n=n;
	GLOBAL_c=c;
	var nf=$('#num_fac').val();
	var nit=$('#nit').val();
	var $n=$(n);
	
	//alert('nit:'+nit+',nf:'+nf+', busq:'+n.val()+'GLOBAL_c:'+GLOBAL_c+' c:'+c);
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio($n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_dev.php',
		data:{busq:$n.val(),c:c,num_fac:nf,nit:nit,mod:mod},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			/*$('#Qresp').append(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			open_pop('Resultado Busqueda','',text);
			//$("#n_pro").focus();
			}
			else {warrn_pop('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
	}
};
function busq_tras(n,c)
{
	
	//warrn_pop('busq:'+n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art3.php',
		data:{busq:n.val(),c:c},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			/*$('#Qresp').append(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			open_pop('Resultado Busqueda','',text);
			//$("#n_pro").focus();
			}
			else {warrn_pop('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
	}
};
function selc(i,id,cod,c,mod_opt,feVen)
{
	GLOBAL_n2=id;
	$('#ref'+c).prop('value',id);
	$('#cod_bar'+c).prop('value',cod);
	
	/*
	if(esVacio($('#ref'+c).val())){$('#cod_bar'+c).focus();}
	else{$('#ref'+c).focus();}
	*/
	$('#cantLim'+c).prop('value',feVen);
	//alert('i:'+i+', ref:'+id+', cod:'+cod+', c:'+c);
	hide_pop('#modal');
	//alert('Gn '+GLOBAL_n.val()+', Gc '+GLOBAL_c+' Gc2: '+GLOBAL_n2);
	//alert('aaa Mod:'+mod_opt);
	if(mod_opt==1 || mod_opt=='mod'){val_new_rowFC(GLOBAL_c);}
	else add_art_com(GLOBAL_n,c);//add_art_com(GLOBAL_n,GLOBAL_c);
	
};
function val_op(u,p,lv,tipo,scs)
{
	//alert('usu:'+u+'...pass:'+p+' Type:'+lv);
	if(!esVacio(p))
	{
		$.ajax({
			url:'ajax/val_user.php',
			data:{u:u,p:p,lvl:lv,tipo:tipo,scs:scs},
			type:'POST',
			dataType:'text',
			success:function(resp)
			{
			//alert(resp);
			if(resp==1)
			{
			$('#mask , .login-popup').fadeOut(300 , function() {
		    $('#mask').remove();  
	         }); 
			 $('#username').prop('value','');
			 $('#password').prop('value','');
 window.open('agregar_producto.php','Registro Producto','width=800,height=600,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
			}
			else if(resp==0)
			{
				warrn_pop('Usuario No Autorizado');
				
			}
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}			
			});
		
	}
	
};
function busq_orden_serv()
{
	window.open('ordenes_servicio.php','Ordenes Servicio','width=800,height=600,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
};
function useDB($cnc)
{
	
	if($cnc.lenght!=0)CNC=$cnc.val();
	else CNC=0;
};
function dup_cam($orig,$hidden)
{
	if($hidden.lenght!=0)
	{
		$hidden.prop('value',$orig.val());
		//alert('valOri:'+$orig.val()+', valHid:'+$hidden.val());
	}
};
function change_tc($sel,$tgt)
{
	var opc=$sel.prop('value');
	if(opc=='Inventario Inicial' || opc=='Corte Inventario' || opc=='Ajuste Seccion')
	{
		$tgt.prop('value',inv_ini);
		validar_2c($('#num_fac').val(),$('#nit').val(),'fac_com','num_fac_com','nit_pro',$('#resp'));
	}
};
function calc_uni($cant,$fracc,$uni)
{
	/*
	var resp=0;
	var c=0;
	var f=0;
	var u=0;
	if($cant.lenght!=0 && $fracc.lenght!=0 && $uni.lenght!=0)
	{
		c=$cant.prop('value')*1 || 0;
		f=$fracc.prop('value')*1 || 1;
		u=$uni.prop('value')*1 || 0;
	
			resp=c*f;
			$uni.prop('value',resp);	
		
		
	}*/
};
function calc_cant($cant,$fracc,$uni)
{
	var resp=0,r2=0;
	var c=0;
	var f=0;
	var u=0;
	if($cant.lenght!=0 && $fracc.lenght!=0 && $uni.lenght!=0)
	{
		c=$cant.prop('value')*1 || 0;
		f=$fracc.prop('value')*1 || 1;
		u=$uni.prop('value')*1 || 0;
		
		//alert('c:'+c+',u:'+u+', f:'+f);
		r2=u/f;
		resp=trunc(r2+c);
		//alert('resp:'+resp+', r2:'+r2);
		//if(resp==0 && u>0)resp=1;
		while(u>=f){
		$cant.prop('value',resp);
		if(f>1)$uni.prop('value',(u-f));
		else $uni.prop('value',(0));
		c=$cant.prop('value')*1 || 0;
		f=$fracc.prop('value')*1 || 1;
		u=$uni.prop('value')*1 || 0;
		};
	}
};
function calc_per($per,$val,$resp)
{
	
	var per=$per.val()*1/100 || 0;
	if($per.prop("id")=="R_ICA_PER"){per=$per.val()*1/1000 || 0;}
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeox(val*per,2));
	$resp.prop('value',resp);
	tot_paga();
	//call_tot();
};
function calc_ica($tarifa,$sub_tot,$entryBox)
{
	
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeox(val*per,2));
	$resp.prop('value',resp);
	tot_paga();
	//call_tot();
};
function calc_per2($per,$val,$resp)
{
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeox(val*per,2));
	$resp.prop('value',resp);
	tot_paga();
	
};
function tot_paga()
{
	var $totPa=$('#TOTAL_PAGAR');
	var tot=quitap($('#TOTAL').val())*1;
	var r_fte=quitap($('#R_FTE').val())*1||0;
	var r_iva=quitap($('#R_IVA').val())*1||0;
	var r_ica=quitap($('#R_ICA').val())*1||0;
	var DCTO2=quitap($('#DESCUENTO2').val())*1||0;
	var r=puntob(tot-(r_fte+r_iva+r_ica+DCTO2));
	
	$totPa.prop('value',r);
	save_fc(-1);
	//alert("tot_paga");
};
function cambioTFC($sel,$tab)
{
	var v=$sel.val();
	if(v=='Compra'){$tab.prop('class','round_table_gray');$('#serialTras').hide();$('#serialFac').show();}
	else if(v=='Traslado'){$tab.prop('class','tema_traslados');$('#serialTras').show();$('#serialFac').hide();}
	else if(v=='Remision'){$tab.prop('class','tema_remision');$('#serialTras').hide();$('#serialFac').show();}
	else {$tab.prop('class','round_table_gray');$('#serialTras').hide();$('#serialFac').show();}
};
var flag_util=0;
function chk_util(i)
{
	var ref=$('#ref'+i).val();
	var cod=$('#cod_bar'+i).val();	
	
	var Data='ref='+ref+'&cod='+cod;
	ajax_x('ajax/val_util.php',Data,function(resp){
		
		var costo=$('#costo'+i);
		var pvp=$('#pvp'+i);
		var iva=$('#iva'+i);
		var redo='s';
        var C=quitap(costo.val())*1;
		var gan=0;
        //var gan=(100-per.val()*1)/100;
        var P=quitap(pvp.val())*1;
		var IVA=(iva.val()*1)/100 +1;
		//alert(gan+', '+C+", ");
		var va=P;
		C=C*IVA;
		//alert('costo:'+C+',pvp:'+va+',iva:'+IVA+', UTIL:'+tipoUtil);
	if(va!=0){
		if(tipoUtil=='A'){gan=((va-C)/C)*100 ||0;}
		else {gan=(1-((C/va)))*100 || 0;}
				
	if(redo=='s'){gan=redondeo(gan);}
	else {}
	
	}
		//alert(resp);
		if(resp!=-404 && flag_util!=1)
		{
			
			if(gan<resp){
				//alert('La utilidad de este producto ha bajado ('+resp+' -> '+gan+') verifica el PVP');
				//lpm2(pvp);
				}
			else {pvp.removeAttr('style');}
		}
		else if(resp=-404){}
		else warrn_pop(resp);
		});
};
function check_vals_compra(ID, functionCall)
{
 
	//T1,T2,Obj,valor
	var Data='id_compra='+ID;
	
	
	$.ajax({
		url:'ajax/CHECK_compra.php',
		data:Data,
	    type: 'POST',
		dataType:'JSON',
		success:function(response){
			
		var resp=response[0].respuesta*1;
		if(resp==1)
		{
			var subTot=response[0].subtot;
			var tot=response[0].tot;
			var iva=response[0].iva;
			
 			var valores={};
			
			valores['subtot']=response[0].subtot;
			valores['iva']=response[0].iva;
			valores['tot']=response[0].tot;
			//$('#SUB').prop('value',puntob(response[0].subtot));
			//$('#IVA').prop('value',puntob(response[0].iva));
			//$('#TOTAL').prop('value',puntob(response[0].tot));
			
			functionCall;
			//calc_per(Obj,T1,T2);
		}
 
		else warrn_pop(resp);
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
 
	
};