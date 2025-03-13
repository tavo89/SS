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
var dcto_remi=0;
var l = null; 
//var tasaCambio=0;
var IMPUESTO_CONSUMO=0.08;
var apartar=1;
var cantidad = 0; 
var valcampo; 
var idvu; 
var iddet; 
var TOTAL = 0; 
var totalF=0,ivaF=0,subF=0;
var IVA = 0; 
var SUB = 0; 
var ivu; 
var cont=0;
var cont_serv=0;
var ref_exis=0;
var serv_exist=0;
var rta=0;
var tipo_cli;
var dcto_art=23;
var dcto_taller=50;
var ref_d='PUNTO-FACIL-RECARGAS';
var cont_dcto=0;
var timeOutCod=0;
var contTO=0;
var flag_reSearch=0;
var flag_multi_rows=0;
var flag_gfv=0;

function redondeo(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*1)/1;
//var result=Math.round(original);
//var result=original;

if(tipoRedondeo=="decimal"){return result;}
else{return redondeo_centenas(numero);}
};


function redondeoFacVenta(num, decimals) {
	
var resultado=	Number(Math.round(num+'e'+decimals)+'e-'+decimals);
if(usar_decimales_exactos==1){resultado= Math.round( num * 100 + Number.EPSILON ) / 100;}
return resultado;
//return Math.round( num * 100 + Number.EPSILON ) / 100;
  
  
};

function redondeo2(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*0.1)/0.1;
//var result=Math.round(original);
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
function puntoiva(n) {
   var T = n.value; 
   T = quitap(T); 
   var x, i = T.length - 1, a, b, c, C = 0, ii = T.length, h = ''; 
   T = T.split(""); 
   while(i >= 0) {
      if(C == 3 && ii != 3) {
         h = T[i] + '.' + h; 
         C = 0; 
         }
      else {
         h = T[i] + h; 
         }
      C++; 
      i--; 
      }
	
   $(n).prop('value', h);
   h= quitap(h);
   $('#' + n.id + "H").prop('value',h/1.19); 
   }; 
function eli(c)
 {
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 cont_dcto=0;
		 $('#exi_ref').prop('value',ref_exis);
		 //alert(ref_exis);
	 }
	 else {$eliRow.css('backgroundColor','#fff');}	 
 };
function eli_serv(c)
 {
	 //alert(c)
	 var $eliRow=$(".serv"+c);
	 var $det;
	 if($("#Tipo_serv"+c+"").length==0)$det='la Fila?';
	 else $det=$("#Tipo_serv"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 $eliRow.remove();
		 tot();
		 $('#num_serv').prop('value',cont_serv);
		 serv_exist--;
		 $('#exi_serv').prop('value',serv_exist);
		 //alert(ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
};
 function eli_serv_mod(c)
 {
	 //alert(c);
	 var man=$('#Tipo_serv'+c).val();
	 var n_fac=n_fac_taller;
	// alert('n_fac:'+n_fac);
	 var $eliRow=$(".serv"+c);
	 var $det;
	 if($("#Tipo_serv"+c+"").length==0)$det='la Fila?';
	 else $det=$("#Tipo_serv"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 //alert('MAN:'+man+', N_fac:'+n_fac);
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 $.ajax({
			url:'ajax/del_serv_ord.php',
			data:{num_fac:n_fac,man:man} ,
			type:'POST',
			dataType:'text',
			success:function(resp){//alert(resp);
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			});
		 $eliRow.remove();
		 tot();
		 $('#num_serv').prop('value',cont_serv);
		 serv_exist--;
		 $('#exi_serv').prop('value',serv_exist);
		 //alert(ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };
function eli_mod(c)
 {
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_fac=$('#n_facH').val();
	 var ref=$('#'+c).val();
	 //alert('ref:'+ref+', num_fac:'+n_fac);
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		$.ajax({
			url:'ajax/del_art_ven.php',
			data:{num_fac:n_fac,ref:ref} ,
			type:'POST',
			dataType:'text',
			success:function(resp){//alert(resp);
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
};
function eli_mod_taller(c)
 {
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_fac=n_fac_taller;
	 var ref=$('#'+c).val();
	 //alert('ref:'+ref+', num_fac:'+n_fac);
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		
		$.ajax({
			url:'ajax/del_art_taller.php',
			data:{num_fac:n_fac,ref:ref} ,
			type:'POST',
			dataType:'text',
			success:function(resp){//alert(resp);
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };

function devolucion(c)
 {
	 //alert(c)
	 
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_fac=$('#n_facH').val();
	 var ref=$('#'+c).val();
	 //alert('ref:'+ref+', num_fac:'+n_fac);
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','gray');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea Regresar '"+$det+"'?"))
	 {
		
		$.ajax({
			url:'ajax/devolver_art_ven.php',
			data:{num_fac:n_fac,ref:ref} ,
			type:'POST',
			dataType:'text',
			success:function(resp){//alert(resp);
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 //$eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 $('#exi_ref').prop('value',ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };





function ids(idd, idvru, vcam) {
   iddet = idd; 
   idvu = idvru; 
   valcampo = vcam; 
   }; 
  
 
function omga(i)
{
	//alert('omga!!');
	if(isNaN($('#dcto_serv'+i).val()))
  {
	  //alert('Ingrese un Numero!');
	  $('#dcto_'+i).prop('value','').focus();
  }
  
  //document.write(isNaN(cant));
  //alert(isNaN($('#'+i+'cant_').val()));
 
  if($('#dcto_serv'+i).val()>dcto_taller)
  {
	  alert('No se permiten descuentos mayores de '+dcto_taller+'%!');
	  $('#dcto_serv'+i).prop('value','').focus();
  }
	tot();
};

function valMin($val,valMin,pvp,i)
{
	
	var valor=quitap($val.val());
	var valOrig=pvp;
	var cant=val_ele('#'+i+'cant_')*1||0;
	var vsi=(quitap(val_ele('#val_u'+i))*1)||0;
	var uni=val_ele('#unidades'+i)*1||0;
   	var frac=val_ele('#fracc'+i)*1 ||1;
	var factor=(uni/frac)+cant;
	
	var $dcto=$('#DESCUENTO');
	var DCTOi=0;
	DCTOi=DCTOi + factor*( valOrig-(vsi) ||0);
	
	$dcto.prop('value',puntob(DCTOi));
	
	if(valor<valMin)
	{
	  warrn_pop('Este valor('+puntob(valor)+') es inferior al permitido :'+puntob(valMin));	
	  $val.prop('value',puntob(pvp));
	  $('#dcto_'+i).prop("value","");
	  valor_t(i);
	  tot();
	}
	
};
function cambiaFP()
{
	if(PVP_CRE==1){
		//alert("pvpCre");	
			var FormaPago=$('#form_pago').val();
		if(FormaPago=='Credito')
		{
			$('#pagare').prop('style','font-size:19px;font-weight:bold;color:rgb(0, 68, 255);');	
		}
		else{
		$('#pagare').prop('style','');	
		}
		for(i=0;i<=cont;i++){
		//alert('cont art:'+cont);
		var vsi=0,cant=0,uni=0,frac=0,factor=0;
		
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val()+', Orig:'+$('#val_orig'+i).val());
			$vst=$('#val_t'+i);
			if(FormaPago!='Credito'){valOrig=quitap($('#val_orig'+i).val())*1;}
			else valOrig=quitap($('#val_cre'+i+'H').val())*1;

			
            if(FormaPago!='Credito'){$('#val_u'+i).prop('value',puntob(valOrig));}
			else {$('#val_u'+i).prop('value',puntob($('#val_cre'+i+'H').val()));}
		
		    //alert(vi+',iva: '+iva+', vsi: '+vsi+', excentos: '+EXC);
		
			cant=$('#'+i+'cant_').val()*1||0;
			uni=$('#unidades'+i).val()*1||0;
   			frac=$('#fracc'+i).val()*1||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			vsi=(quitap($('#val_u'+i).val())*1)||0;
			
			$vst.prop('value',puntob(redondeo(vsi*factor)));
			 if(($('#remision').lenght!=0 && $('#modFV').lenght!=0) && $('#remision').val()==1  && $('#modFV').val()==1 )save_fv(i);
			 else  if( ($('#remision').lenght!=0 && $('#modREMI').lenght!=0) && $('#remision').val()==1  && $('#modREMI').val()==1 )save_remi(i);
		 
			//alert('vsi:'+vsi);
		
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
}

//alert('Precios Cambiados!');
tot();
	}// ALLOW pvp_credito 
};
function val_exist_cero()
{
	
	
	var $eliRow;
	 var $det;
	  
	
	
	var cant=0,uni=0,frac=0,factor=0,malCont=0;
	
   var cant= 0;
   var uni= 0;
   var frac= 1;
   var LimCant=1;
   factor=(uni/frac)+cant;
	
	for(i=0;i<=cont;i++)
	{
		//alert('cont art:'+cont);
		if($('#val_t'+i).length!=0)
		{
			
			
			$eliRow=$(".art"+i);
	 		$det;
	 		if($("#det_"+i+"").length==0)$det='la Fila?';
	 		else $det=$("#det_"+i+"").val();
	 		
			
			cant=val_ele('#'+i+'cant_')*1||0;
   			uni=val_ele('#unidades'+i)*1||0;
   			frac=val_ele('#fracc'+i)*1||1;
  			LimCant=redondeoFacVenta(val_ele('#'+i+'cant_L'),2);
   			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			factor=redondeoFacVenta(factor,2);
			
			
			if(factor==0)
  			{
				$eliRow.css('backgroundColor','yellow');
				$('#'+i+'cant_').css('backgroundColor','yellow');
				valor_t(i);
	  			//alert('La cantidad ingresada supera la del inventario('+LimCant+'), cambiela.');
	  			malCont++;
	  
  			}
			else{$eliRow.css('backgroundColor','#fff');}
			
			//alert('factor' +factor+', LimCant' +LimCant+' mal'+malCont);
		}
	}
	
	
	/*if(malCont==0)return 0;
	else return 1;*/
	
	return 0;

};
function val_exist()
{
	
	var $eliRow;
	 var $det;
	  
	
	
	var cant=0,uni=0,frac=0,factor=0,malCont=0;
	
   var cant= 0;
   var uni= 0;
   var frac= 1;
   var LimCant=1;
   factor=(uni/frac)+cant;
	
	for(i=0;i<=cont;i++)
	{
		//alert('cont art:'+cont);
		if($('#val_t'+i).length!=0)
		{
			
			
			$eliRow=$(".art"+i);
	 		$det;
	 		if($("#det_"+i+"").length==0)$det='la Fila?';
	 		else $det=$("#det_"+i+"").val();
	 		
			
			cant=val_ele('#'+i+'cant_')*1||0;
   			uni=val_ele('#unidades'+i)*1||0;
   			frac=val_ele('#fracc'+i)*1||1;
  			LimCant=redondeoFacVenta(val_ele('#'+i+'cant_L'),2);
   			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			factor=redondeoFacVenta(factor,2);
			
			
			if(factor>LimCant)
  			{
				$eliRow.css('backgroundColor','red');
				$('#'+i+'cant_').css('backgroundColor','red');
	  			//alert('La cantidad ingresada supera la del inventario('+LimCant+'), cambiela.');
	  			malCont++;
	  
  			}
			else{$eliRow.css('backgroundColor','#fff');}
			
			//alert('factor' +factor+', LimCant' +LimCant+' mal'+malCont);
		}
	}
	
	
	if(malCont==0)return 0;
	else return 1;
};
function tot()
{
	var FormaPago=$('#form_pago');
	var tipoComi='';
	if($('#tipo_comi').lenght!=0){tipoComi=$('#tipo_comi').val();}
	
	var impuestoBolsas=val_ele('#bolsas')*valor_impuesto_bolsas;
	if(impuesto_bolsas!=1){impuestoBolsas=0;}
	//alert(val_ele('#bolsas')+' , '+valor_impuesto_bolsas+' '+impuestoBolsas);
	
	
	var $sub=$('#SUB');
	var $dcto=$('#DESCUENTO');
	var dctoIVA=quitap(val_ele('#DESCUENTO_IVA'))*1;
	var $iva=$('#IVA');
	var $tot=$('#TOTAL');
	var $exc=$('#EXCENTOS');
	var $base5=$('#base5');
	var $iva5=$('#iva5');
	var $base19=$('#base19');
	var $iva19=$('#iva19');
	var cant=0;
    var dcto=0;
    var iva=0;
    var vsi=($('#val_u'+i).val()*1)||0;
	var SUBi=0,DCTOi=0,IVAi=0,TOTi=0,EXC=0,i=0,vi=0,vt=0,valOrig=0,uni=0,frac=0,factor=0,BASE5=0,BASE19=0,IVA5=0,IVA19=0;
	var $vst='';
	var Tcant=0;
	var stot=0;
	
	for(i=0;i<=cont;i++)
	{
		//alert('cont art:'+cont);
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val());
 			$vst=val_ele('#val_t'+i);
			if(FormaPago!='Credito'){valOrig=quitap(val_ele('#val_orig'+i))*1;}
			else valOrig=quitap(val_ele('#val_cre'+i+'H'))*1;
			cant=val_ele('#'+i+'cant_')*1||0;
			Tcant+=cant;
			uni=val_ele('#unidades'+i)*1||0;
   			frac=val_ele('#fracc'+i)*1 ||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
            dcto=(val_ele('#dcto_'+i)/100)||0;
			if(tipoComi=="Venta Directa"){dcto=0;$('#dcto_'+i).prop('readonly','readonly')}
            iva=(val_ele('#iva_'+i)/100)||0;
			vsi=(quitap(val_ele('#val_u'+i))*1)||0;
			vi=(1+iva)*vsi;
			stot=vsi*factor;
           
		   //alert('iva: '+iva);
			if(iva==0){EXC+=stot;}
			if(iva==0.05){BASE5+=stot/(1+iva);IVA5+=(stot/(1+iva))*(iva);}
			if(iva==0.19){BASE19+=stot/(1+iva);IVA19+=(stot/(1+iva))*(iva); }
		
		    vt=quitap(val_ele('#val_t'+i));
			
			SUBi=SUBi + (parseFloat(vt/(iva+1))||0 );
			DCTOi=DCTOi + factor*( valOrig-(vsi) ||0);
			IVAi=IVAi + ((vt/(iva+1))*iva ||0);
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
	}
	var valServ=0;
	
	if($('#TOT_CANT').length!=0){$('#TOT_CANT').prop('value',Tcant);}
	SUBi=SUBi;
	TOTi=SUBi+IVAi+impuestoBolsas-dctoIVA;
	//alert("s "+SUBi+', I'+IVAi+', imBOl'+impuestoBolsas);
	//alert(TOTi+', '+puntob(redondeoFacVenta(TOTi,2)));
	
	if(dcto_remi==28){
	$sub.prop('value',puntob(redondeoFacVenta(SUBi,2)));
	$dcto.prop('value',puntob(DCTOi));
	$iva.prop('value',puntob(redondeoFacVenta(IVAi,2)));
	$tot.prop('value',puntob(redondeoFacVenta(TOTi,2)));
	$exc.prop('value',puntob(EXC));
	
	$base5.prop('value',puntob(redondeoFacVenta(BASE5,2)));
	$base19.prop('value',puntob(redondeoFacVenta(BASE19,2)));
	$iva5.prop('value',puntob(redondeoFacVenta(IVA5,2)));
	$iva19.prop('value',puntob(redondeoFacVenta(IVA19,2)));
	
	//if(TOTi>0)$('#vlr_let').prop('value',covertirNumLetras(""+TOTi+""));
	}
	else{ 
	$sub.prop('value',puntob(redondeoFacVenta(SUBi,2)));
	//alert("redondeado:"+redondeoFacVenta(SUBi,2)+"| antes:"+SUBi);
	$dcto.prop('value',puntob(redondeoFacVenta(DCTOi,2)));
	$iva.prop('value',puntob(redondeoFacVenta(IVAi,2)));
	$tot.prop('value',puntob(redondeoFacVenta(TOTi,2)));
	$exc.prop('value',puntob(EXC));
	
	$base5.prop('value',puntob(redondeoFacVenta(BASE5,2)));
	$base19.prop('value',puntob(redondeoFacVenta(BASE19,2)));
	$iva5.prop('value',puntob(redondeoFacVenta(IVA5,2)));
	$iva19.prop('value',puntob(redondeoFacVenta(IVA19,2)));
	//alert(BASE19);
	/*
	$sub.prop('value',puntob(SUBi));
	$dcto.prop('value',puntob(DCTOi));
	$iva.prop('value',puntob(IVAi));
	$tot.prop('value',puntob(TOTi));
	*/
	//if(TOTi>0)$('#vlr_let').prop('value',covertirNumLetras(""+redondeo(TOTi)+""));
	}
	

	calc_per2($('#R_FTE_PER'),$('#SUB'),$('#R_FTE'));
	calc_per2($('#R_IVA_PER'),$('#IVA'),$('#R_IVA'));
	calc_per2($('#R_ICA_PER'),$('#SUB'),$('#R_ICA'));
	cambio_moneda($tot,$('#TOTAL_BSF'),tasaCambio);
	change($('#entrega'));
	//alert(covertirNumLetras(""+TOTi+""));
	//takeSnapshot('factura_venta');
	
	
};



function change(n)
{
//alert('entrega');
if(!esVacio(n.value)|| n.length!=0){
puntoa(n);
var stot=quitap($('#SUB').val())*1;
var tot=quitap($('#TOTAL').val())*1;
var r_fte=quitap($('#R_FTE').val())*1||0,r_iva=quitap($('#R_IVA').val())*1||0,r_ica=quitap($('#R_ICA').val())*1||0;

var exentos=quitap($('#EXCENTOS').val())*1;
//alert(exentos);
var impoConsumo=0;
if(impuesto_consumo==1)impoConsumo= redondeo(exentos*IMPUESTO_CONSUMO);
$('#IMP_CONSUMO').prop('value',impoConsumo);

//alert($('#TOTAL').val()+', '+impoConsumo+', '+(r_fte+r_iva+r_ica));
//impoConsumo
//tot=tot+impoConsumo-(r_fte+r_iva+r_ica);
tot=tot+impoConsumo-(r_fte+r_iva+r_ica);

if(tot>0)$('#vlr_let').prop('value',covertirNumLetras(""+redondeo(tot)+""));
//$('#TOTAL_PAGAR').prop('value',puntob(tot));
$('#TOTAL_PAGAR').val(puntob(tot));
//$('#SUB').prop('value',stot);	
var totBsf=quitap($('#TOTAL_BSF').val());
if(totBsf<0)totBsf=0;
var $entrega=$('#entrega');
var $entrega2=$('#entrega2');
var $entrega3=$('#entrega3');
var bs=quitap($entrega2.val())*1 ;
var tarj=quitap($entrega3.val())*1 ;
var $anticipo=$('#anticipo');

var $cambio=$('#cambio');
var en=quitap($entrega.val())*1 + tarj || 0,cam=quitap($cambio.val())*1 ||0,camb=0,anti=quitap($anticipo.val())*1 ||0;

cambio_moneda($('#TOTAL'),$('#TOTAL_BSF'),tasaCambio);
if($('#bsf').prop('class')!='uk-hidden bsf' && bs!=0)
{
en=0;
tot=totBsf;
$('#bsF_flag').prop('value','1');	
}
else 
{
	$('#bsF_flag').prop('value','0');
}
//alert(tot);

//alert(anti+',en:'+en+', cam'+cam);

if(isNaN(quitap($('#entrega').val())))
  {
	  //alert('Ingrese un Numero!');
	  $('#entrega').prop('value','').focus();
  }
var subTot=anti+bs+en;
//alert(subTot);
camb=(subTot-tot)*1||0;
camb2=redondeo(camb*tasaCambio);
if(camb2<0)camb2=0;
//alert(camb);
$cambio.prop('value',puntob(camb));

if($('#bsf').prop('class')!='uk-hidden bsf')
{
$('#cambio_pesos').html('Cambio en Pesos: '+puntob(camb2));	
}
else
{
	$('#cambio_pesos').html('');	
}

}
};

function dct_serv(id)
{
	
   var i = parseInt(id); 
   
   
 
   
   var dcto=($('#dctoServ_'+i).val()/100)||0;
   var iva=($('#iva_'+i).val()/100 + 1)||0;
   var vsi=0;
   var valMin=$("#valMin"+i).val()*1;
   
   

   var $val_u=$('#val_u'+i);
   //alert('Val min'+$('#valMin'+i).val()+', id:'+id);
   if($('#val_orig'+i).lenght!=0){vsi=(quitap($('#val_orig'+i).val())*1)||0;}
   else {vsi=(quitap($('#val_u'+i).val())*1)||0;}
   var vsi2=0;
   vsi2=vsi/(iva);
   
   if(valMin==1)valMin=vsi2;
   
   
   //alert('vsi: '+vsi+', Dcto:'+dcto);
   var vuf=redondeo2(vsi - valMin*dcto);
  // alert('vuf: ('+vsi+' - Dcto:'+valMin*dcto);
   if(tipoUtil=="B"){vuf=redondeo2(vsi/(dcto+1));}
   //alert('vuf:'+vuf+' vsi '+vsi+' vsi2 '+vsi2+' dcto:'+dcto);
   //$val_u.prop('value',puntob(redondeo(vuf)));
   if(dcto!=0 ){$val_u.prop('value',puntob(vuf));}
   else {$val_u.prop('value',puntob(vsi));}
   valor_t(id);

	
};

function dct(id)
{
   var i = parseInt(id); 
   
   
   
   if($('#dcto_'+i).val()>lim_dcto){warrn_pop("Descuento superior al permitido ("+lim_dcto+"%)!");
   $('#dcto_'+i).prop('value',0);
   }
   
   var dcto=($('#dcto_'+i).val()/100)||0;
   var iva=($('#iva_'+i).val()/100 + 1)||0;
   var vsi=0;
   var valMin=$("#valMin"+i).val()*1;
   
   

   var $val_u=$('#val_u'+i);
   //alert('Val min'+$('#valMin'+i).val()+', id:'+id);
   if($('#val_orig'+i).lenght!=0){vsi=(quitap($('#val_orig'+i).val())*1)||0;}
   else {vsi=(quitap($('#val_u'+i).val())*1)||0;}
   var vsi2=0;
   vsi2=vsi/(iva);
   
   if(valMin==1)valMin=vsi2;
   
   
   //alert('vsi: '+vsi+', Dcto:'+dcto);
   var vuf=redondeo2(vsi - valMin*dcto);
  // alert('vuf: ('+vsi+' - Dcto:'+valMin*dcto);
   if(tipoUtil=="B"){vuf=redondeo2(vsi/(dcto+1));}
   //alert('vuf:'+vuf+' vsi '+vsi+' vsi2 '+vsi2+' dcto:'+dcto);
   //$val_u.prop('value',puntob(redondeo(vuf)));
   if(dcto!=0 ){$val_u.prop('value',puntob(vuf));}
   else {$val_u.prop('value',puntob(vsi));}
   valor_t(id);
};
function cant_dcto(id)
{
   var i = parseInt(id);
   var $dcto_cli=$('#dcto_cli'+i).val()*1;
   var $tipo_dcto=$('#tipo_dcto'+i).val();
   var $cant=$('#'+i+'cant_');
   var $ref=$('#ref_'+i).val();
   var c=$cant.val()*1;
   var per=1+($dcto_cli/100);
   
   var cantD=(c*per);
   //alert('dctoCli:'+$dcto_cli+',cant:'+c+',%:'+per+'result:'+cantD);
//alert("$ref="+$ref+", ref_d="+ref_d);
   if($tipo_dcto=='PRODUCTO' && $ref==ref_d){$cant.prop('value',cantD);}
   else if($tipo_dcto=='PRODUCTO' && $ref!=ref_d && cont_dcto==0){$cant.prop('value',redondeo2(cantD));cont_dcto=1};
};
function d(dcto)
{
   var c=100+dcto*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   //alert(c+','+d);
   return d;
};
function busq_dcto2(n)
{
	
	
	};


function valor_t(id) {
	
   var FormaPago=val_ele('#form_pago');
   var i = parseInt(id); 
  // alert('ID:'+id+', num:'+i);
   var idt = 'val_t' + i;
   var cant=val_ele('#'+i+'cant_')*1||0;
   var uni=val_ele('#unidades'+i)*1||0;
   var frac=val_ele('#fracc'+i)*1||1;
   var LimCant=redondeoFacVenta(val_ele('#'+i+'cant_L'),2);
   var dcto=(val_ele('#dcto_'+i)/100)||0;
   var iva=(val_ele('#iva_'+i)/100 + 1)||0;
   var factor=(uni/frac)+cant;
   if(factor==0)factor=cant;
   
   var factor2=redondeoFacVenta(factor,2);
   
   var vsi=(quitap($('#val_u'+i).val())*1)||0;
 /*
   if(FormaPago!='Credito')vsi=(quitap($('#val_u'+i).val())*1)||0;
   else {vsi=(quitap($('#val_cre'+i+'H').val())*1)||0;}*/
 
   if(isNaN($('#'+i+'cant_').val()))
  {
	  if($('#'+i+'cant_').val()<=0){
		  
	  //alert('Ingrese un Numero Mayor a Cero!');
	  $('#'+i+'cant_').prop('value','');
	  }
	  else{
	  //alert('Ingrese un Numero!');
	  $('#'+i+'cant_').prop('value','');
	  }
	 // $('#'+i+'cant_').focus();
  }
  if($('#'+i+'cant_').val()<=0){
		  
	  //alert('Ingrese un Numero Mayor a Cero!');
	  //$('#'+i+'cant_').focus();
	  $('#'+i+'cant_').prop('value','');
	  tot();
	  }
  
  if(isNaN($('#dcto_'+i).val()))
  {
	  //alert('Ingrese un Numero!');
	  $('#dcto_'+i).prop('value','');
	  focusRed2($('#dcto_'+i));
  }
  
  //document.write(isNaN(cant));
  //alert(isNaN($('#'+i+'cant_').val()));
 
 
  //alert(factor);
  if(factor2>LimCant&&cant!=1)
  {
	  warrn_pop('La cantidad ingresada supera la del inventario('+LimCant+'), cambiela.');
	  $('#'+i+'cant_').prop('value','');
	  //$('#'+i+'cant_').focus();
	 // $('#'+i+'cant_L').focus();
	  cant=1;
  }
   
   var vt= factor*(vsi);//- (vsi*iva)*dcto);
   //alert(vt);
   
   var $vst=$('#val_t'+i);
   
   //$vst.prop('value',puntob(redondeo(vt)) );
   if(tipoRedondeo=="decimal")$vst.prop('value',puntob(redondeoFacVenta(vt,0)) );
   else $vst.prop('value',puntob(redondeo_centenas(vt)) );
   //$vst.prop('value',puntob(vt) );
   //alert('Entra tot()');
   tot();
   }; 

function val_rep_fv(ref,cod,feVen)
{
	var id='cod_bar',id2='feVen',id3='ref_'; 
		var ele=$('#cod');
		var ele2=$('#feVen');
		var ele3=$('#Ref'); 
		var $rep='',$busq='',$busq2='';
		var repCheck=0;
		var index=0;
		var valid='';
		//ele.blur();
//alert('add com!, ele:'+ele.val());
	//alert('fv:'+ele2.val());
	//alert('ref:'+ref+', cod:'+cod+' feVen:'+feVen);
	for(i=0;i<cont;i++)
	{
	  $busq=$('#'+id+''+i);
	  $busq2=$('#'+id2+''+i);
	  $busq3=$('#'+id3+''+i);
	 // alert('rowVal['+i+']:'+$busq3.val()+', incomeVal:'+ele3.val() );
	  
//valid=( ($busq.lenght!=0&&$busq.prop('id')!=ele.prop('id') && $.trim($busq.val())==$.trim(ele.val())) && ($busq3.lenght!=0&&$busq3.prop('id')!=ele3.prop('id')&&$.trim($busq3.val())==$.trim(ele3.val()) )  || ($busq.val()==cod  &&(!esVacio(cod) && cod!='' )));
//alert('cod:'+cod+', ref:'+ref+' busq:'+$busq.val()+' busq3:'+$busq3.val()+' ele:'+ele.val()+', ele3:'+ele3.val())
valid=($busq.val()==ele.val()  && $busq3.val()==ele3.val() || ($busq.val()==cod && $busq3.val()==ref  &&(!esVacio(cod) && cod!='' )));

//valid=($busq.val()==ele.val()  && $busq3.val()==ele3.val() || ($busq.val()==cod  &&(!esVacio(cod) && cod!='' &&!esVacio(ref) && ref!='' ) )  );


	  
if(usarFechaVenci==1)
{


valid=($busq.val()==ele.val() && $busq2.val()==ele2.val()  && $busq3.val()==ele3.val() || ($busq.val()==cod && $busq3.val()==ref && $busq2.val()==feVen  &&(!esVacio(cod) && cod!='' )));
}
//alert(valid);
if(valid)
	  {
		  $rep=$busq;
		  //alert('Producto repetido :'+$rep.val());
		  ele.focus();
		  tot();
		  repCheck=1;
		  index=i;
		  //alert($busq.length+',attr:'+$busq.prop('id')+', repCheck:'+repCheck);
	  }
	}
	
	if($busq.length!=0&&$busq.prop('id')!='cod'&& repCheck==1)
	{
		 var $cant=$("#"+index+"cant_");
		 var can=$cant.val()*1;
		 $cant.prop('value',++can);
		 $('#cod').prop('value','');
		 valor_t(index);
		 $('#cod').focus();
		 //alert('Se ha agregado una (1) cantidad al Codigo['+$busq.val()+']');
		// hide_pop('#modal');	
		 }
	else return true;
	
};
function add_art_dev_ven(){
	
	   
clearTimeout(timeOutCod);

contTO=0;
	//alert(cont+', Mod:'+modFac);	
	var tipoCli=$('#tipo_cli').val();
	//alert('tc:'+tipoCli);
 if(cont>=40000 && modFac==1){
	 
	 $('#butt_gfv').click();
	 }
 else{
		 if(val_rep_fv('','','')){
			 //alert('entra if ;'+$sel.length);

		 
         $.ajax({
		url:'ajax/add_art_dev_ven.php',
		data:{codBar:$cod=$('#cod').val(),fe_ven:$('#feVen').val(),ref:$('#Ref').val(),tc:tipoCli,idCli:''+$('#ced').val()+''},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=text;
		//alert('text:['+text+'];');
		
		var r=text.split('|');
			//alert(''+r[11]+','+r[18]);
			//alert(r[21]);
			if(val_rep_fv(r[1],r[11],r[18])){
			
		    if(r[0]==0 && r[16]==0) {
			 alert("Articulo AGOTADO, Intente otra opcion");
			 //alert(r[16]);
			 //busq($('#cod'));
			 
			 var $cod=$('#cod');
			 //$('#cod').blur();
		     //$cod.prop("value","");
			
		    
			 
			 
			 }
			 else if(resp==-2 ||resp==0){
				// alert('.:Articulo No Encontrado:.');
				 //$('#cod').blur();
				 //busq($('#cod'));
				 }
			 
			 else if((r[0]!=0 || r[16]!=0)&&resp!=-2){
				 //alert(text);
			   var $cod=$('#cod');
			   var $feVenci=$('#feVen');			  
               var det="det_",val_u="val_u";
			   // r=text.split('|');
			   var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
			   var iva=(r[3]/100)+1;
			   //alert(iva+',r4:'+r[4]);
			   var vsi=r[4];
			   if(dcto_remi==28)
			   {
			     //vsi=(r[5]/(iva+1)) + (r[5]*0.02);
				 vsi=(r[5])*1.02;
			   
			   }
			    //r[3]=parseInt(r[3]);
			   //alert(r[3]);
			  
			   if( $('#tipo_cli').val()=='Empresas' && r[3]!=0)
			   {
				vsi=redondeo2(r[20]*1.05);   
			   }
			   vsi=redondeo(vsi);
			   //alert(vsi+', pvp'+r[4]);
				//alert(flag_reSearch);
				//alert('RESP:'+r[14]);
				 if(r[14]==2&&(usarFechaVenci==0 && flag_reSearch==0))
				   {
					   //alert('if!');
					   busq($('#cod'));
					   flag_reSearch=1; 
				   }
			   else if(r[14]==2&&(usarFechaVenci==1 && flag_reSearch==0))
			   {
	if(confirm('Existen otras Ref. con Fecha de Vencimiento diferente, Desea buscar otras Referencias??'))
				   {
					   busq($('#cod'));
					   flag_reSearch=1;
					   //alert(flag_reSearch);
				   }
				 
				   else
				   {
			   //alert('nrm');
			   //alert('Pvp Cre:'+r[20]);
			   //ref
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   
			   
			
			   // SN - IMEI
		
			   if(usarSerial==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="SN_'+cont+'" placeholder="S/N" style=" width:100px"/></td>';
			   
			   
			   
			   
			   //cod. bar
			   
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
			   
			   //descripcion
			   html+='<td class="art'+cont+'"><textarea style="" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   
			   
			   //ubicacion - presen
			   if(usa_ubica==1){html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[22]+'" type="text" id="ubica'+cont+'" name="ubica'+cont+'" placeholder="" style=" width:70px" /><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="hidden" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   
			   //feVenci
			   if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="text" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   
			   
			   
			   //color
			   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px" readonly/></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px" readonly/></td>';
			   
			   
			   //iva
			   
			   
			   if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" readonly=""/></td>';
			   
			   else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" /></td>';
			   
			   //cant/cantLim
			   var Cant='',Uni=0;
			   if(tipoFAC=='A')Cant=1;
			   if(r[0]==0){Cant=0;Uni=1};
			   //Cant="";
			   Uni="";
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small fc_cant art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="20" value="'+Cant+'" onkeyDown="" onkeyup="calc_uni($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));" onblur="cant_dcto(this.id);valor_t(this.id);" style=" width:50px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   
			   
			    //fracc-unidades
			    if(usarFracc==1)html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+Uni+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder=""   onKeyUp="calc_cant($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t('+cont+');" />';
			   
			    if(usarFracc==1)html+='<input class="uk-form-small art'+cont+'"  value="'+r[15]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[16]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';
			  
	
			   //descuento
			   if(r[19]==0){html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'),'+r[6]+','+vsi+','+cont+');" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}
			   else {html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}

			   //valor unitario/val Min
			   if(r[13]==0){html+='<td class="art'+cont+'" valorProducto><input class="uk-form-small valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');" readonly="" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';
			 }
			 else{html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="change16('+cont+');valMin($(this),'+r[6]+','+vsi+','+cont+');" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';}
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
//move($('#'+cont+'cant_'),$('#'+(cont-1)+'cant_'),$('#'+(cont+1)+'cant_'),$('#'+cont+'cant_'),$('#unidades'+cont));
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
			  // alert('Producto Agregado');
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   hide_pop('#modal');	
			   //$cod.focus(); 
			  // $cod.prop('value',r[21]);  
				
				   
				   
				   }// fin else 
				   
			   } // fin if otra sugerencia
			   else{
				//alert('nrm2');   
			   
			   //ref
			   //alert(r[1]);
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+(r[1]+cont)+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   
			   
			 
			   
			   
			   //cod. bar
			   
			    if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
				else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+(r[11]+cont)+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
				
				  // SN - IMEI
			   //html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="IMEI_'+cont+'" placeholder="IMEI" style=" width:50px"/></td>';
			   if(usarSerial==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="SN_'+cont+'" placeholder="S/N" style=" width:100px"/></td>';
			   
			   //descripcion
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'"><textarea style=" " cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   else html+='<td class="art'+cont+'"><textarea style=" width:250px" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" >'+r[2]+'</textarea></td>';
			   
			 /*  //presentacion
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   else html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" /></td>';
			   */
			   
			   
			     //ubicacion - presen
			   if(usa_ubica==1){html+='<td class="art'+cont+' "><input class="uk-form-small art'+cont+'"  value="'+r[22]+'" type="text" id="ubica'+cont+'" name="ubica'+cont+'" placeholder="" style=" width:70px" /><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="hidden" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   
			   //feVenci
			  if(vendeSin==0 || r[1]!='VENTA-000'){if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="text" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   else{if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="date" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:130px" /></td>';}
			   
			    if(vendeSin==0 || r[1]!='VENTA-000'){
			   //color
			   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px" readonly/></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px" readonly/></td>';
				}
				else
				{
					
				   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px"  /></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px"  /></td>';	
					
					
				}
			   
			   //iva
			   if(vendeSin==0 || r[1]!='VENTA-000'){
				 if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" readonly=""/></td>';
			   
			   else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" /></td>';
				   
				   }
			   else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" /></td>';
			   
			   //cant/cantLim
			   var Cant='',Uni=0;
			   if(tipoFAC=='A')Cant=1;
			   if(r[0]==0){Cant=0;Uni=1};
			    //Cant="";
			   Uni="";
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="20" value="'+Cant+'" onkeyDown="//move($(this),$(\'#'+(cont-1)+'cant_\'),$(\'#'+(cont+1)+'cant_\'),$(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'));" onkeyup="calc_uni($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);" style=" width:50px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   
			   
			    //fracc-unidades
			    if(usarFracc==1){html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+Uni+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder=""  onKeyUp="calc_cant($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t('+cont+');" />';}
			   
			   if(usarFracc==1){html+='<input class="uk-form-small art'+cont+'"  value="'+r[15]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[16]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';}
			  
		
			   
			   //descuento
			   if(r[19]==0){html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'),'+r[6]+','+vsi+','+cont+');" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}
			   else {html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'),'+r[6]+','+vsi+','+cont+');" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}

			   //valor unitario/val Min
			   if(r[13]==0){html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');" readonly="" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';
			 }
			 else {html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="change16('+cont+');valMin($(this),'+r[6]+','+vsi+','+cont+');" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';}
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   //alert('Producto Agregado');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
				
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   hide_pop('#modal');	
			  // $cod.focus();
			    //$cod.prop('value',r[21]); 
				   
			   }
		    
			
			}
			
		 

		
		}//fin if rep
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		 }//fin else

 }// fin else cont>5 modFac=1
   
};
 
   
function add_art_ven() {   
clearTimeout(timeOutCod);
$formaPago=$('#form_pago').val();
contTO=0;
	//alert(cont+', Mod:'+modFac);	
	var tipoCli=$('#tipo_cli').val();
	var cotiza=0;
	var tipoResol = $('#tipo_resol').val();
	if($("#co").lenght!=0){cotiza=$("#co").val();}
	//alert('tc:'+tipoCli);
 if(cont>=40000 && modFac==1){
	 
	 $('#butt_gfv').click();
	 }
 else{
		 if(val_rep_fv('','','')){
			 //alert('entra if ;'+$sel.length);

		 
         $.ajax({
		url:'ajax/add_art_ven.php',
		data:{codBar:$cod=$('#cod').val(),
		      fe_ven:$('#feVen').val(),
			  ref:$('#Ref').val(),
			  tc:tipoCli,idCli:''+$('#ced').val()+'',
			  co:cotiza,
			  form_pago:$formaPago,
			  tipoResol:tipoResol},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=text;
		//alert('text:['+text+'];');
		
		var r=text.split('|');
			//alert(''+r[11]+','+r[18]);
			//alert(r[21]);
			
			if(val_rep_fv(r[1],r[11],r[18])){
			
		    if(r[0]==0 && r[16]==0) {
			 alert("Articulo AGOTADO, Intente otra opcion");
			 //alert(r[16]);
			 //busq($('#cod'));
			 
			 var $cod=$('#cod');
			 //$('#cod').blur();
		     //$cod.prop("value","");
			
		    
			 
			 
			 }
			 else if(resp==-2 ||resp==0){
				// alert('.:Articulo No Encontrado:.');
				 //$('#cod').blur();
				 busq($('#cod'));
				 $('#cod').focus();
				 //playAlert('alertAudio');
				 }
			 
			 else if((r[0]!=0 || r[16]!=0)&&resp!=-2){
				 //alert(text);
			   var $cod=$('#cod');
			   var $feVenci=$('#feVen');			  
               var det="det_",val_u="val_u";
			   // r=text.split('|');
			   var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
			   var iva=(r[3]/100)+1;
			   //alert(iva+',r4:'+r[4]);
			   var vsi=r[4];
			   if(dcto_remi==28)
			   {
			     //vsi=(r[5]/(iva+1)) + (r[5]*0.02);
				 vsi=(r[5])*1.02;
			   
			   }
			    //r[3]=parseInt(r[3]);
			   //alert(r[3]);
			  
			   if( $('#tipo_cli').val()=='Empresas' && r[3]!=0)
			   {
				vsi=redondeo2(r[20]*1.05);   
			   }
			   //vsi=redondeo(vsi);
			   //alert(vsi+', pvp'+r[4]);
				//alert(flag_reSearch);
				//alert('RESP:'+r[14]);
				 if(r[14]==2&&(usarFechaVenci==0 && flag_reSearch==0))
				   {
					   //alert('if!');
					   busq($('#cod'));
					   flag_reSearch=1; 
				   }
			   else if((r[14]==2&&(usarFechaVenci==1 && flag_reSearch==0) ) && vendeSin==0)
			   {
	if(confirm('Existen otras Ref. con Fecha de Vencimiento diferente, Desea buscar otras Referencias??'))
				   {
					   busq($('#cod'));
					   flag_reSearch=1;
					   //alert(flag_reSearch);
				   }
				 
				   else
				   {
			   //alert('nrm');
			   //alert('Pvp Cre:'+r[20]);
			   //ref
			   html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   
			   
			
			   // SN - IMEI
		
			   if(usarSerial==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="SN_'+cont+'" placeholder="S/N" style=" width:100px"/></td>';
			  
			  
			  
			  // COD-GARANTIA
			
			   if(usar_cod_garantia==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="COD_GARANTIA'+cont+'" name="COD_GARANTIA'+cont+'" placeholder="Garantia" style=" width:100px"/></td>';
			  
			   
			   //cod. bar
			   
			   html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
			   
			   //descripcion
			   html+='<td class="art'+cont+'"><textarea style="" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   
			   //ubicacion - presen
			  if(usa_ubica==1){ html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[22]+'" type="text" id="ubica'+cont+'" name="ubica'+cont+'" placeholder="" style=" width:70px" /><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="hidden" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   
			   //feVenci
			   if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="text" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   
			   
			   
			   //color
			   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px" readonly/></td>';
			   
			   if(usar_datos_motos==1){
				   
				// num_motor
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="num_motor'+cont+'" type="text" id="num_motor'+cont+'" value=""  onkeyUp="check_motor(this.value,'+cont+');"></td>';
   
			   }
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px" readonly/></td>';
			   
			   
			   //iva
			   
			   
			   if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" readonly=""/></td>';
			   
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" /></td>';
			   
			   //cant/cantLim
			   var Cant='',Uni=0;
			   if(tipoFAC=='A')Cant=1;
			   if(r[0]==0){Cant=0;Uni=1};
			   //Cant="";
			   Uni="";
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="20" value="'+Cant+'" onkeyDown="//move($(this),$(\'#'+(cont-1)+'cant_\'),$(\'#'+(cont+1)+'cant_\'),$(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'));" onkeyup="calc_uni($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));" onblur="cant_dcto(this.id);valor_t(this.id);" style=" width:50px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   
			   
			    //fracc-unidades
			    if(usarFracc==1)html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+Uni+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder=""  onKeyUp="calc_cant($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t('+cont+');" />';
			   
			    if(usarFracc==1)html+='<input class="uk-form-small art'+cont+'"  value="'+r[15]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[16]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';
			  
	
			   //descuento
			   if(r[19]==0){html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'),'+r[6]+','+vsi+','+cont+');" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}
			   else {html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}

			   //valor unitario/val Min
			   if(r[13]==0){html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+' " id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');" readonly="" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';
			 }
			 else{html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="change16('+cont+');valMin($(this),'+r[6]+','+vsi+','+cont+');" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+parseFloat(r[5])+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';}
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
//move($('#'+cont+'cant_'),$('#'+(cont-1)+'cant_'),$('#'+(cont+1)+'cant_'),$('#'+cont+'cant_'),$('#unidades'+cont));
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont);
				call_autocomplete('NOM',$('#num_motor'+cont),'ajax/busq_num_motor.php'); 
			  // alert('Producto Agregado');
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
			   if($('#marca_moto').lenght!=0){$('#marca_moto').prop('value',r[23]);}
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   
			   if(mesas_pedidos==1 || fac_servicios_mensuales==1){
				
			   }
			   else {
				hide_pop('#modal');
			   }

			   //$cod.focus(); 
			  // $cod.prop('value',r[21]);  
				
				   
				   
				   }// fin else 
				   
			   } // fin if otra sugerencia
			   else{
				//alert('nrm2');   
			   
			   //ref
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+(r[1]+cont)+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   
			   
			 
			   
			   
			   //cod. bar
			   
			    if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
				else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+(r[11]+cont)+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
				
				  // SN - IMEI
			   //html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="IMEI_'+cont+'" placeholder="IMEI" style=" width:50px"/></td>';
			   if(usarSerial==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="SN_'+cont+'" placeholder="S/N" style=" width:100px"/></td>';
			   
			   
			   // COD-GARANTIA
			
			   if(usar_cod_garantia==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="COD_GARANTIA'+cont+'" name="COD_GARANTIA'+cont+'" placeholder="Garantia" style=" width:100px"/></td>';
			 
			   
			   //descripcion
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'"><textarea style="" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   else html+='<td class="art'+cont+'"><textarea style=" width:250px" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" >'+r[2]+'</textarea></td>';
			   
			 /*  //presentacion
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   else html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" /></td>';
			   */
			   
			   
			     //ubicacion - presen
			   if(usa_ubica==1){ html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[22]+'" type="text" id="ubica'+cont+'" name="ubica'+cont+'" placeholder="" style=" width:70px" /><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="hidden" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   
			   //feVenci
			  if(vendeSin==0 || r[1]!='VENTA-000'){if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="text" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   else{if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="date" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:130px" /></td>';}
			   
			    if(vendeSin==0 || r[1]!='VENTA-000'){
			   //color
			   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px" readonly/></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px" readonly/></td>';
				}
				else
				{
					
				   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px"  /></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px"  /></td>';	
					
					
				}
				
				  if(usar_datos_motos==1){
				   
				// num_motor
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="num_motor'+cont+'" type="text" id="num_motor'+cont+'" value=""  onkeyUp="check_motor(this.value,'+cont+');"></td>';
   
			   }
			   
			   //iva
			   if(vendeSin==0 || r[1]!='VENTA-000'){
				 if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" readonly=""/></td>';
			   
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" /></td>';
				   
				   }
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" /></td>';
			   
			   //cant/cantLim
			   var Cant='',Uni=0;
			   if(tipoFAC=='A')Cant=1;
			   if(r[0]==0){Cant=0;Uni=1};
			    //Cant="";
			   Uni="";
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="20" value="'+Cant+'" onkeyDown="//move($(this),$(\'#'+(cont-1)+'cant_\'),$(\'#'+(cont+1)+'cant_\'),$(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'));" onkeyup="calc_uni($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);" style=" width:50px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   
			   
			    //fracc-unidades
			    if(usarFracc==1){html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+Uni+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder=""  onKeyUp="calc_cant($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t('+cont+');" />';}
			   
			   if(usarFracc==1){html+='<input class="uk-form-small art'+cont+'"  value="'+r[15]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[16]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';}
			  
		
			   
			   //descuento
			   if(r[19]==0){html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'),'+r[6]+','+vsi+','+cont+');" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}
			   else {html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'),'+r[6]+','+vsi+','+cont+');" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}

			   //valor unitario/val Min
			   if(r[13]==0){html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');" readonly="" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';
			 }
			 else {html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="change16('+cont+');valMin($(this),'+r[6]+','+vsi+','+cont+');" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';}
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   //alert('Producto Agregado');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
				
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
				call_autocomplete('NOM',$('#num_motor'+cont),'ajax/busq_num_motor.php'); 
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   
			  
			   if($('#marca_moto').lenght!=0){$('#marca_moto').prop('value',r[23]);}
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   if(mesas_pedidos==1 || fac_servicios_mensuales==1){
				
			   }
			   else {
				hide_pop('#modal');
			   }

			  // $cod.focus();
			    //$cod.prop('value',r[21]); 
				   
			   }
		    
			
			}
			
		 

		
		}//fin if rep
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		 }//fin else

 }// fin else cont>5 modFac=1
   };
   
function busq_dcto(n)
{//alert(n.val());
	var ref=$('#ref_'+n).val();
	var id_cli=$('#ced').val();
	var $dcto=$('#dcto_'+n);
	var $dcto_cli=$('#dcto_cli'+n);
	var $tipo_dcto=$('#tipo_dcto'+n);
	//alert(ref+', '+id_cli+'.')

		
	  $.ajax({
		url:'ajax/busq_dcto.php',
		data:{ref:ref,id_cli:id_cli},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
				//alert('Si!');
				$dcto.prop('value',text*1).focus().blur();
				
			
			}
			else {}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	};
function add_art_ajus(){   
         
		 if(val_rep_fv('','',''))
		 {
		 
         $.ajax({
		url:'ajax/add_art_ajus.php',
		data:{codBar:$cod=$('#cod').val(),fe_ven:$('#feVen').val(),ref:$('#Ref').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		 var r=text.split('|');
		//alert('text:['+text+'];');
		
		     if(resp==-2020){alert('.:Articulo No Encontrado:.');$('#cod').blur();}
			  else if(r[13]==2&&(usarFechaVenci==0 && flag_reSearch==0))
				   {
					   //alert('if!');
					   busq_ajus($('#cod'));
					   flag_reSearch=1; 
				   }
			 else if(resp!=-2020){
				// alert(text);
			   var $cod=$('#cod');			  
               var det="det_",val_u="val_u";
			  
			   
			   var iva=(r[3]/100)+1;
			   //alert(iva+',r4:'+r[4]);
			   var vsi=r[4];
			   var html='';
			
			   
			   			   //omg   xD
						   //ref
			   html+='<tr id="tr_'+cont+'" class="art'+cont+'"><td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value="'+r[1]+'" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"></td>';
			   
			   //cod bar
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value="'+r[8]+'" type="text" id="cod_barras'+cont+'" name="cod_barras'+cont+'" style=" width:80px"></td>';
			   
			   //des
			   html+='<td class="art'+cont+'"><textarea cols="1'+cont+'" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="" >'+r[2]+'</textarea><input class="uk-form-small art'+cont+'" readonly="" value="'+r[9]+'" type="hidden" id="presentacion'+cont+'" name="presentacion'+cont+'" style=" width:100px"></td>';
			   
			   //presentacion
			  /* html+='<td class="art'+cont+'" align="center"></td>';*/
			   
			   if(usar_datos_motos==1){
				   
				// num_motor
html+='<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+' fc_seriales" name="num_motor'+cont+'" type="text" id="num_motor'+cont+'" value=""  onkeyUp="check_motor(this.value,'+cont+');"></td>';
   
			   }
			   
			   
			   //cant
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="cant_'+cont+'" type="text" name="cant_'+cont+'" size="10" maxlength="10" value="" style=" width:40px" onKeyUp="calc_cant($(\'#cant_'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));cant_ajus('+cont+');"><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:30px"></td>';
			   
			   //fracc-unidades
			   html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+0+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder="" style="width:40px" onKeyUp="calc_cant($(\'#cant_'+cont+'\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));" />';
			   
			   html+='<input class="uk-form-small art'+cont+'"  value="'+r[10]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';
			   
			   
			   //motivo
			   html+='<td class="art'+cont+'"><textarea cols="1'+cont+'"  class="art'+cont+'" name="motivo_'+cont+'" id="motivo_'+cont+'" ></textarea><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="hidden" name="iva_'+cont+'" size="5" maxlength="5" value="'+r[3]+'" style="width:30px" readonly=""><input class="uk-form-small art'+cont+'" id="util_'+cont+'" type="hidden" name="util_'+cont+'" size="5" maxlength="5" value="'+r[7]+'" style=" width:40px" readonly="readonly" style=" width:60px"></td>';
			   
			   

			   //iva
			   /*html+='<td class="art'+cont+'" align="center"></td>';*/
			   
			   //util
			   //html+='<td class="art'+cont+'" align="center"></td>';
			   
			   //costo
			  // html+='<td class="art'+cont+'"></td>';
			   
			   
			   
			   //pvp
			  // html+='<td class="art'+cont+'"></td>';
			   
			   //cant saldo
			   html+='<td><input class="uk-form-small art'+cont+'" id="cant_saldo'+cont+'" name="cant_saldo'+cont+'" type="text" readonly="" value="'+r[0]+'" style=" width:40px"><input class="uk-form-small art'+cont+'" id="pvp_'+cont+'" name="pvp_'+cont+'" type="hidden" readonly="" value="'+puntob(r[4])+'" style=" width:60px"><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="'+cont+'"><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0"><input class="uk-form-small art'+cont+'" id="costo_'+cont+'" name="costo_'+cont+'" type="hidden" value="'+puntob(r[5])+'" readonly="readonly" style=" width:60px"><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden" value="0"><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0"><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="" style=" width:30px"><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden" value=""></td>';
			   
			   //fracc-unidades saldo
			   html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+r[11]+'" type="text" id="unidades_saldo'+cont+'" name="unidades_saldo'+cont+'" placeholder="" style="width:40px" onKeyUp="" /></td>';
			   		   
			   //fecha ven
			   html+='<td><input class="uk-form-small art'+cont+'" id="fecha_vencimiento'+cont+'" name="fecha_vencimiento'+cont+'" type="date" readonly="" value="'+r[12]+'" style=" width:150px"></td>';
			   
			   // eli button
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			    call_autocomplete('NOM',$('#num_motor'+cont),'ajax/busq_num_motor.php');
				
			    $('#cant_'+cont).focus();
			   cont++;
			   ref_exis++;
			  
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);		   
			    $('#Ref').prop('value','');
		       $cod.prop("value","");
		       $cod.unbind('focus');
			   hide_pop('#modal');	
		      
			   
			   //alert('Producto Agregado');
                
			
			}
			
		 

			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		 }//fin else


   };
function cant_ajus(i)
{
	//alert(i);
	var cant=$('#cant_'+i).val()*1,cantL=$('#'+i+'cant_L').val()*1;
	//alert(cant+', L:'+cantL+', R:'+(cant-cantL));
	//if(cantL+cant<0){alert('Esa cantidad esta por debajo del permitido');$('#cant_'+i).prop('value',0);}
};
function add_art_gas() {   
         var $cod=$('#cod');
	     var ref=$cod=$('#cod').val(); 
		 $sel=$("input").filter(function() {return $(this).text() === ref;});
		 
		 if($sel.length!=0&&$sel.prop('id')!='cod'){
			 //alert('entra if ;'+$sel.length);
		
		 var $cant=$("#"+$sel.prop('id')+"cant_");
		 var can=$cant.val()*1;
		 $cant.prop('value',++can);
		 $('#cod').prop('value','');
		 valor_t($sel.prop('id'));
		 $('#cod').focus();
			 
		 }
		 else
		 {
		 
         $.ajax({
		url:'ajax/add_art_gas.php',
		data:{ref:$cod=$('#cod').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		//alert('text:['+text+']; resp:'+resp);
		
		    if(resp==0) {
			 alert(".:Articulo AGOTADO:.");
			 var $cod=$('#cod');
		     $cod.prop("value","");
			 $('#cod').blur();

		    
			
			 
			 }
			 else if(resp==-2){alert('.:Articulo No Encontrado:.');$('#cod').blur();}
			 
			 else if(resp!=0&&resp!=-2){
				 //alert(text);
			   var $cod=$('#cod');			  
               var det="det_",val_u="val_u";
			   var r=text.split('|');
			   var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
			   var iva=(r[3]/100)+1;
			   //alert(iva+',r4:'+r[4]);
			   var vsi=r[5];
			   if(dcto_remi==28)
			   {
			     //vsi=(r[5]/(iva+1)) + (r[5]*0.02);
				 vsi=(r[5])*1.02;
			   
			   }
			   vsi=redondeo(vsi);
			   
			   //ref
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="'+cont+'" name="ref_'+cont+'"/></td>';
			   
			   //descripcion
			   html+='<td class="art'+cont+'"><textarea cols="10" rows="3" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   //iva
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:30px" readonly=""/></td>';
			   
			   //cant/cantLim
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="5" value="1" onkeyup="valor_t(this.id);" onblur="valor_t(this.id);" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:30px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			  
			   //descuento
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+0+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:30px"/></td>';

			   //valor unitario/val Min
			   html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');"/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /></td>';
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   valor_t(cont);
               cont++;
			   ref_exis++;
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
		       $cod.unbind('focus');
		       $cod.focus();
                
			
			}
			
		 

			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		 }//fin else


   }; 
function mod_art_ven() {   
         var $cod=$('#cod');
	     var ref=$cod=$('#cod').val(); 
		 var tipoResol = $('#tipo_resol').val();
         $.ajax({
		url:'ajax/add_art_ven.php',
		data:{ref:$cod=$('#cod').val(),tipoResol:tipoResol},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		//alert('text:['+text+']; resp:'+resp);
		
		    if(resp==0) {
			 alert(".:Articulo AGOTADO:.");
			 var $cod=$('#cod');
		     $cod.prop("value","");
			 $('#cod').blur();
		    
			
			 
			 }
			 else if(resp==-2){alert('.:Articulo No Encontrado:.');$('#cod').blur();}
			 
			 else if(resp!=0&&resp!=-2){
			   var $cod=$('#cod');			  
               var det="det_",val_u="val_u";
			   var r=text.split('|');
			   var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
			   var iva=r[3]/100;
			   var vsi=r[4]/(iva+1);
			   vsi=redondeo(vsi);
			   
			   
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="'+cont+'" name="ref_'+cont+'"/></td>';
			   
			   
			   html+='<td class="art'+cont+'"><textarea cols="10" rows="3" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:30px" readonly=""/></td>';
			   
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="5" value="1" onkeyup="valor_t(this.id);" onblur="valor_t(this.id);" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:30px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   

			   
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"0\" onkeyup="javascript:valor_t('+cont+');" onblur="valor_t('+cont+');" style=" width:30px"/></td>';

			   
			   html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\" readonly="" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   valor_t(cont);
               cont++;
			   ref_exis++;
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
		       $cod.unbind('focus');
		       $cod.focus();
                
			
			}
			
		 

			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});


   }; 
function get_nom(n,op){
	//alert('cod '+n.value);
	
	if(!esVacio(n.value))
	{
		
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){ event.which = event.charCode || event.keyCode;}
	key=event.which;
	//alert('Chrome, cod:'+key);
	if(key==13&&op=='add'){busq_cli(n);}
	if(key==13&&op=='mod'){busq_cli(n);}
	if(key==120){busq_nom($(n));}
	if(key==119){busq_exp($(n));}
	
	}
	
	};  
 function busq_nom($n)
 {
	// alert($n.val());
	$.ajax({
		url:'ajax/busq_nom.php',
		data:{busq:$n.val()},
		type: 'POST',
		dataType:'text',
		success:function(text){
			$('#Qresp').html(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);
			
			},
		error:function(xhr,status){alert('Su conexion ha fallado...intentelo mas tarde');}
		}); 
 };
function sel_nom(i,nom)
{
	$('#cli').prop('value',nom);
	busq_cli(document.getElementById('cli'));
	
	};
	
var addCre=0;
function busq_cli(n)
{
	var tipoDoc=0;
	
	if($('#remision').lenght!=0){tipoDoc=$('#remision').val();}
	    
        $.ajax({
		url:'ajax/add_usu_ven.php',
		data:{ced:n.value},
	    type: 'POST',
		dataType:'JSON',
		success:function(response){
			//alert('Encontrado!:'+text);
			var resp=response[0].respuesta*1;
			//resp=resp.replace(/\+/g," ");
			if(resp!=0)
			{
			  
			 if($('#nom_lookup').length!=0){$('#nom_lookup').prop('value',response[0].nombreCompleto);}
			 
			 if($('#snombr').lenght!=0 && $('#apelli').length!=0){
			 $('#cli').prop('value',response[0].nombre);}
			 else {$('#cli').prop('value',response[0].nombreCompleto);}	
			 $('#dir').prop('value',response[0].direccion);
			 $('#tel').prop('value',response[0].telefono);
			 
			 $('#mail').prop('value',response[0].email);
			 $('#fe_naci').prop('value',response[0].fecha_nacimiento);
			 $('#ced').prop('value',response[0].cc);
			 
			 $('#aliasCli').prop('value',response[0].alias);
			 
			 $('#tope_credito').prop('value',response[0].tope_credito);
			 $('#total_credito').prop('value',response[0].tot_credito);
			 
			 $('#snombr').prop('value',response[0].snombr);
			 $('#apelli').prop('value',response[0].apelli);
			 $('#depcli').prop('value',response[0].depcli);
			 $('#nomcon').prop('value',response[0].nomcon);
			 $('#razsoc').prop('value',response[0].razsoc);
			 
             $("#city option[value='"+response[0].ciudad+"']").prop('selected','selected');
			 $("#claper option[value='"+response[0].claper+"']").prop('selected','selected');
			 $("#coddoc option[value='"+response[0].coddoc+"']").prop('selected','selected');
			 $("#regtri option[value='"+response[0].regtri+"']").prop('selected','selected');	
			 $("#regFiscal option[value='"+response[0].regFiscal+"']").prop('selected','selected');
			  
			 
			 
			 
			 var htmlTipoUsu='<select name="tipo_cli" id="tipo_cli"><option value=""></option><option value="Mostrador" selected="">Mostrador (Público)</option><option value="Empresas">Empresas (+16%)</option><option value="Otros Talleres">Otros Talleres</option><option value="Mayoristas">Mayoristas</option></select>';
			 
			 if(response[0].tipo_usu=="Otros Talleres"){ $("#tipo_cli option[value='Otros Talleres']").prop('selected','selected');	 }
			 
			 if(response[0].autoriza_cre==0 && tipoDoc!=3){
				 alert('El cliente '+response[0].nombreCompleto+' NO tiene autorizacion para creditos');
			 $("#form_pago option[value='Credito']").remove();
			 addCre=0;
			 if(($('#remision').lenght!=0 && $('#remision').val()==1) && ($('#co').lenght!=0 && $('#co').val()!=1) ){/*location.assign('remisiones.php');*/}
			 }
			 else {
				 
				 
				 
				 }
			 
			 if(parseInt(response[0].tope_credito)<parseInt(response[0].tot_credito) && response[0].tope_credito!=0 && tipoDoc!=3){
			 alert('El cliente '+response[0].nombreCompleto+' supera el limite de credito establecido ('+puntob(response[0].tope_credito)+')');
			 $("#form_pago option[value='Credito']").remove();
			 //alert(campos[8]>campos[9]);
			 //alert(' 8:'+campos[8]+', 9:'+campos[9]);
			 addCre=0;
			 }
			 else {
				 
				 //$('#form_pago').append($('<option>', {value:'Credito', text:'Credito'}));
				 
				 }
				 
				 if(addCre==0 && !(response[0].tope_credito<response[0].tot_credito && response[0].tope_credito!=0) && (response[0].autoriza_cre!=0) && tipoDoc!=3){
					 
					 $('#form_pago').append($('<option>', {value:'Credito', text:'Credito'}));delCre=1;
					 addCre=1;
					 }
			 if($('#remision').lenght!=0 && $('#remision').val()==0){busq_exp($('#ced'));}
			}
			
			warrn_saldo_lim_fac($('#ced'),$('#cli'));
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
};


function busq_dev(n){
	
	//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_dev_ven.php',
		data:{busq:n.val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			//$('#Qresp').html(text);
			open_pop('Resultado Busqueda','',text);
			remove_pop($('#modal'));
			
			if($('#tab_art').lenght!=0){
				n.blur();
				$('#tab_art').focus();
			
				}
			/*$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	}

};

 
function busq(n)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art.php',
		data:{busq:n.val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			//$('#Qresp').html(text);
			open_pop('Resultado Busqueda','',text);
			remove_pop($('#modal'));
			
			if($('#tab_art').lenght!=0){
				n.blur();
				$('#tab_art').focus();
			
				}
			/*$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			}
			else {warrn_pop('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	}
	else {busq_all(n);}
};
function busq2(n)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_cotiza.php',
		data:{busq:n.val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			//$('#Qresp').html(text);
			open_pop('Resultado Busqueda','',text);
			remove_pop($('#modal'));
			
			if($('#tab_art').lenght!=0){
				n.blur();
				$('#tab_art').focus();
			
				}
			/*$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			}
			else {warrn_pop('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	}
	else {busq_all(n);}
};
function busq_ajus(n)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_ajus.php',
		data:{busq:n.val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			/*$('#Qresp').html(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			open_pop('Resultado Busqueda','',text);
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	}
};
function busq_ajus_all(n)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_ajus_all.php',
		data:{busq:n.val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			/*$('#Qresp').html(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			open_pop('Resultado Busqueda','',text);
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	
};

function busq_all(n)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_all.php',
		data:{},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			/*$('#Qresp').html(text);
			$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);*/
			open_pop('Resultado Busqueda','',text);
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
	
};

function cod_ajus(n,op){
	//alert('cod '+n.value);
	
	if(!esVacio(n.value))
	{
		
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode))
	{ event.which = event.charCode || event.keyCode;}
	key=event.which;
	//alert(key);
	if(key==13&&op=='add'){add_art_ajus()}
	if(key==13&&op=='mod'){add_art_ajus();}
	if(key==120){busq_ajus($('#cod'));}
	
	
	}
	
	};
function codx($n,op){
	var timeLimit=800000;
	if(fix_lector_barras==1){timeLimit=800;}
remove_pop($('#modal'));

if(!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode))
{event.which = event.charCode || event.keyCode;}
key=event.which;	
//alert('cod '+n.value);
if(!esVacio($n.val()))
{
// $n.bind('keyup',function(e){

	if(contTO==0)
	{
	timeOutCod=setTimeout(function()
	{
		if(op=='add')add_art_ven();
		else if(op=='mod')add_art_ven_mod();
		
		},timeLimit);
		contTO=1;
	}
	
	//alert('key:'+key+' op :'+op);
	if(key==13&&(op=='add'||op=='dev'||op=='cotiza')){add_art_ven();}
	else if(key==13&&(op=='dev_ven')){add_art_dev_ven();}
	else if(key==13&&op=='mod'){add_art_ven_mod();}
	else if(key==13&& (op=='cotiza_mod')){add_art_remi_mod();}
	//else if(key==13&& (op=='mod_remi' || op=='cotiza_mod')){add_art_remi_mod();}
	
	if(key==120&&op!='dev' && op!='cotiza' && op!='cotiza_mod'){busq($('#cod'));}
	else if(key==120&&op=='dev'){busq($('#cod'));}
	else if(key==120&& (op=='cotiza'|| op=='cotiza_mod')){busq2($('#cod'));}
	else if(key==120&&op=='dev_ven'){busq_dev($('#cod'));}
	//});
	
	}
	
	else {
		
		
		
		//if(key==13&&(op=='add'||op=='dev')){busq_all($n);}
		}
	
	
	///////////////////
	
	};
	

function busq_ref(n,op){
	//alert('cod '+n.value);
	
	if(!esVacio(n.value))
	{
		
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){ event.which = event.charCode || event.keyCode;}
	key=event.which;
	if(key==120){busq($('#cod'));}
	
	
	
	}
	
	};
function cody(n,op){
	//alert('cod '+n.value);
	
	if(!esVacio(n.value))
	{
	if (!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode)){ event.which = event.charCode || event.keyCode;}
	key=event.which;
	if(key==13&&op=='add'){add_art_gas()}
	if(key==13&&op=='mod'){add_art_gas();}
	if(key==120){busq($('#cod'));}
	
	
	
	}
	
	};
	
function selc(i,ref,id,feVen)
{	//alert('selc');
	//hide_pop('#modal');
	$('#cod').prop('value',id);
	$('#feVen').prop('value',feVen);
	$('#Ref').prop('value',ref);
	//alert($('#feVen').val());
	if($('#modFV').lenght!=0&&$('#modFV').val()==1){add_art_ven_mod();}
	else if($('#modREMI').lenght!=0&&$('#modREMI').val()==1){add_art_remi_mod();}
	else {add_art_ven();}
};

function markSelectedRow(idRow){
    $('#'+idRow).addClass('rowSelected');
}

function selc_dev(i,ref,id,feVen)
{	//alert('selc');
	//hide_pop('#modal');
	$('#cod').prop('value',id);
	$('#feVen').prop('value',feVen);
	$('#Ref').prop('value',ref);
	//alert($('#feVen').val());
	add_art_dev_ven();
	
};

function selc_ajus(i,ref,id,feVen)
{	
	$('#cod').prop('value',""+id+"");
	$('#feVen').prop('value',""+feVen+"");
	$('#Ref').prop('value',""+ref+"");
	//alert($('#feVen').val());
	add_art_ajus();
};

function val_fv(frm)
{
	var cambio=quitap($('#cambio').val())*1 ||0;
	
	val_exist();
	
	var fp=frm.form_pago.value;
	//alert(fp);
 
	var ctaIN=0;
	var valRemi=( $('#remision').lenght!=0 ) && $('#remision').val()!=1;


	var codComi='';
	var tipoComi='';
	if($('#cod_comision').lenght!=0){codComi=$('#cod_comision').val();tipoComi=$('#tipo_comi').val();}
	var PagoTar2=0;
	if($('#entrega3').lenght!=0){PagoTar2=quitap($('#entrega3').val());}
	if(cta_bancos==1 && ( valRemi  )){
		
 
	 ctaIN=frm.id_cuenta.value;
	}
	


var ifB1=esVacio(frm.ced.value)|| esVacio(frm.cli.value);	
var ifB2=frm.ced.value.trim()==ID_CLIENTE_MOSTRADOR &&(frm.cli.value.trim()!=CLIENTE_MOSTRADOR);
var ifB3=frm.ced.value.trim()!=ID_CLIENTE_MOSTRADOR &&( frm.cli.value.trim()==CLIENTE_MOSTRADOR);

ifB2=true;
ifB3=true;

var msg="ifB1:"+ifB1+" | ifB2:"+ifB2+" | ifB3"+ifB3;

	 if(esVacio(frm.form_pago.value)){warrn_pop('Especifique Forma de Pago');focusRed($(frm.form_pago));return true;}
	 
	 else if(!esVacio(codComi) && esVacio(tipoComi)){warrn_pop('Seleccione el Tipo de Comision');focusRed($('#tipo_comi'));return true;}
	 
else if(cta_bancos==1 && (fp!="Contado" && fp!="Credito" && fp!="Cheque") &&( esVacio(ctaIN))){warrn_pop('Seleccione la Cuenta que quiere AFECTAR');focusRed($(frm.id_cuenta));return true;}
else if( (fp!="Contado" ) && (!esVacio(PagoTar2) && PagoTar2!=0) ){warrn_pop('Este campo SOLO aplica para pagos de CONTADO y TARJETA, si quiere pagar solo tarjeta SELECCIONE la forma de pago "Tarjeta de Credito/Debito" y digite el monto en la casilla "PAGO"');focusRed($(frm.entrega));return true;}
	 else if((fp=='Contado'||fp=='Contado-Caja General') && (!esVacio(ctaIN)) &&  cta_bancos==1){warrn_pop('Los pagos de CONTADO solo pueden ir a la Cuenta de CAJA GENERAL');focusRed($(frm.form_pago));return true;}
	 
	 
	else if(esVacio(frm.fecha.value) || frm.fecha.value=='0000-00-00'){warrn_pop('Ingrese la fecha');focusRed($(frm.fecha));return true;}
	else if(esVacio(frm.tipo_cli.value)){warrn_pop('Especifique Tipo de Cliente');focusRed($(frm.tipo_cli));return true;}
	else if(esVacio(frm.vendedor.value)){warrn_pop('Especifique el Vendedor');focusRed($(frm.vendedor));return true;}
	else if( ((cambio)<0 && (frm.form_pago.value!='Credito' && frm.form_pago.value!='SisteCredito')) && pos_fac==0){warrn_pop('El valor entregado es inferior al Total, Cambielo');focusRed($('#entrega'));return true;}

else if(1 && ( ifB1  ) )
	{warrn_pop('Registre el Cliente ') ;
	if(esVacio(frm.cli.value))focusRed($('#cli'));
	else if(esVacio(frm.ced.value))focusRed($('#ced'));
	return true;
	}
	else if( (esVacio(frm.exi_ref.value)|| frm.exi_ref.value<=0) && valRemi && mesas_pedidos!=1){warrn_pop('Debe cargar articulos a la Factura!');$('#cod').focus();return true;}
	//else if(1){}
	else return false;
};

function gfv_block($btn,id,frm)
{
	
	var tipoDoc=frm.remision.value;
	var tipoCli=frm.tipo_cli.value;
	var tipoRemi=frm.co.value;
	var totFac=parseInt(quitap(frm.TOTAL.value));
	var topeCre=parseInt(quitap(frm.tope_credito.value));
	var totFacCre=parseInt(quitap(frm.total_credito.value));
	
	if(flag_gfv==0){
	var val = $btn.val();
	
	//alert('guarda ! '+val);
	//alert(frm.meca.value+' '+esVacio(frm.tipo_cli.value));
	
    var tot=quitap($('#TOTAL').val())*1;
	var entrega=quitap($('#entrega').val())*1 || 0;
	var $entrega2=$('#entrega2');
	var bs=quitap($entrega2.val())*1 ;
	var anticipo=quitap($('#anticipo').val())*1 || 0;
	var num_exp=$('#num_exp').val();
	var cambio=quitap($('#cambio').val())*1 ||0;
	var excesoCredito=(totFacCre+totFac)-topeCre;
	
	//alert('cam:'+cambio);
//alert(cambio);
	//alert(anticipo+'  '+num_exp);
 
   if(val_exist()){warrn_pop('Hay cantidades que superan las permitidas!!');}
   else if(val_exist_cero()){warrn_pop('Hay cantidades CERO');}
   else if(topeCre<(totFacCre+totFac) && frm.ced.value!=ID_CLIENTE_MOSTRADOR && topeCre!=0){warrn_pop('Esta factura supera el limite de Credito por el sig. monto:[$'+puntob(excesoCredito)+']');}
   else if( (frm.ced.value==ID_CLIENTE_MOSTRADOR || frm.cli.value==CLIENTE_MOSTRADOR) && frm.form_pago.value=="Credito"){warrn_pop("Cliente general no tiene autorizado creditos");}
   else  if(val_fv(frm)){}

   else {
	//alert(anticipo+', Exp:'+num_exp)
	if(anticipo>0&&num_exp!=0){
		if(confirm('Esta apunto de COBRAR con ANTICIPOS, desea continuar?')){
			//frm.confirm_bono.value!='SI'
	if(0){warrn_pop('Especifique el uso de Bono/Anticipo');focusRed($(frm.confirm_bono));}
					else {
						$('#'+id).prop("value",val);
    					//$btn.off( 'click' );
						
						flag_gfv=1;	
						if(tipoDoc==0){save_fac_ven();}else if(tipoDoc==1){save_fac_remi();}
					}
		}
	}
	else {
		//alert(anticipo+', Exp:'+num_exp)
		if(anticipo==0&&num_exp==0){
		$('#'+id).prop("value",val);
    	//$btn.prop( 'disabled',true );
		//$('#entrega').prop( 'disabled',true );
		//$('#entrega2').prop( 'disabled',true );
		//$('#entrega3').prop( 'disabled',true );
		flag_gfv=1;	
		
		if(tipoDoc==0){save_fac_ven();}else if(tipoDoc==1){save_fac_remi();}
		}
		else {
alert('Este anticipo no tiene saldo!, presione F8 en el nombre del cliente, luego seleccione "Cancelar"');}
		}
	}
	
	}
	
	};

function val_lim_fac(idCli,nomCli,tipo_fac,totFac,$btn,id,frm)
{
	//console.log('val_lim_fac CALL');
	var data='idCli='+idCli+'&tipo_fac='+tipo_fac+'&tot_fac='+totFac+'&nomCli='+nomCli;
	ajax_x('ajax/WARNINGS/lim_fac_remi.php',data,function(resp){
		//alert(resp);
		//console.log('val_lim_fac ajax success');
		if(resp==1){
			
			//console.log('val_lim_fac ajax success->gfv_block call');
			gfv_block($btn,id,frm);
			
			}
		else{error_pop(resp);}
		});
	
};
function warrn_saldo_lim_fac($idCli,$nomCli)
{
	
	var nomCli=$nomCli.val();
	var idCli=$idCli.val();
	
	var tipoDoc=$('#remision').val();
	var tipoCli=$('#tipo_cli').val();
	var tipoRemi=$('#co').val();
 
	
	var tipoFac='';
	
	if(tipoDoc==0){
		tipoFac='fac';
		}else if(tipoDoc==1 && tipoCli!='Traslado' && tipoRemi!=1){
			tipoFac='remi';
			}else if(tipoCli=='Traslado'){
				tipoFac='traslado';
				}
		

if(usar_lim_fac==1){
var data='idCli='+idCli+'&tipo_fac='+tipoFac+'&tot_fac='+0+'&nomCli='+nomCli;
	ajax_x('ajax/WARNINGS/saldo_lim_fac_remi.php',data,function(resp){
		//alert(resp);
		if(resp==1){}
		else{warrn_pop(resp);}
		});
		
		
}
};


function requireDataMHRemi($btn,id,frm){
	if( $("#cli").val() == "" || $("#ced").val() == ""  )
	{
		if( $("#cli").val() == "" ){
			alert("Ingrese el Nombre del Cliente!");
			//$("#cli").focus();
		}else if( $("#ced").val() == "" ){
			alert("Ingrese la Cédula del cliente!");
			//$("#ced").focus();
		}
		/*else if( $("#dir").val() == "" ){
			alert("Ingrese la Dirección del cliente!");
			//$("#dir").focus();
		}*//*else if( $("#tel").val() == "" ){
			alert("Ingrese el Teléfono del cliente!");
			//$("#tel").focus();
		}*/
		
	}else if( $("#articulos tr").length <= 1 && $("#servicios tr").length <= 1 )
	{
		alert("Debe cargar productos o servicios a la factura!");
	}
	else
	{
		gfv($btn,id,frm);
	} /* else datos cliente vacios */
};


function gfv($btn,id,frm)
{
	banDcto();
	
	//$('#'+btn.id).unbind('click');
	var tipoDoc=frm.remision.value;
	var tipoCli=frm.tipo_cli.value;
	var tipoRemi=frm.co.value;
	var totFac=quitap(frm.TOTAL.value);
	
	var tipoFac='';
	
	if(tipoDoc==0){
		tipoFac='fac';
		}else if(tipoDoc==1 && tipoCli!='Traslado' && tipoRemi!=1){
			tipoFac='remi';
			}else if(tipoCli=='Traslado'){
				tipoFac='traslado';
				}
				
				
				
if(usar_lim_fac==1){val_lim_fac(frm.ced.value,frm.cli.value,tipoFac,totFac,$btn,id,frm);
}else {gfv_block($btn,id,frm);}

	
	
	
};
function save_ajus(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	getPage($('#HTML_Pag'),$('#crea_ajus'));
	var val = btn.value;
	var $nf=$('#num_ref');
	//alert($nf);
	//alert(frm.cod.value);
    if($nf.val()==0){alert('Seleccione una REFERENCIA');frm.cod.focus();}
	else {
		$('#'+id).prop("value",val);
    	$(btn).remove();
		frm.submit();
			}
	
	
	
};
function save_dcto(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	var val = btn.value;
	//alert(frm.num_ref.value);
	var nr=frm.num_ref.value*1;
    if(nr==0){alert('Seleccione una REFERENCIA');frm.cod.focus();}
	else {
		$('#'+id).prop("value",val);
    	$(btn).remove();
		frm.submit();
			}
	
	
	
};
function save_dcto2(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	var val = btn.value;
	//alert(frm.num_ref.value);
    if(esVacio(frm.dcto.value)){alert('Ingrese el Descuento!');frm.dcto.focus();}
	else if(esVacio(frm.fabricante.value)){alert('Seleccione Fabricante!');frm.fabricante.focus();}
	else {
		$('#'+id).prop("value",val);
    	$(btn).remove();
		frm.submit();
			}
	
	
	
};
function gfo(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	
	var val = btn.value;
	//alert(frm.meca.value+' '+esVacio(frm.tipo_cli.value));
    var tot=quitap($('#TOTAL').val())*1;
	var entrega=quitap($('#entrega').val())*1 || 0;
	//alert(tot);
    if(esVacio(frm.form_pago.value)){alert('Especifique Forma de Pago');frm.form_pago.focus();}
	else if(esVacio(frm.tipo_cli.value)){alert('Especifique Tipo de Cliente');frm.tipo_cli.focus();}
	else if(esVacio(frm.vendedor.value)){alert('Especifique el Vendedor');frm.vendedor.focus();}
	else if((frm.tipo_cli.value=='Taller Honda' || frm.num_serv.value>0)&&( esVacio(frm.meca.value)) ){alert('Escoga el mecanico');frm.meca.focus();}
	else if(esVacio(frm.ced.value)||esVacio(frm.cli.value))
	{alert('Registre el Cliente');
	if(esVacio(frm.cli.value))$('#cli').focus();
	else if(esVacio(frm.ced.value))$('#ced').focus();
	}
	//else if(esVacio(frm.exi_ref.value)|| frm.exi_ref.value<=0){alert('Debe cargar articulos a la Factura!');$('#cod').focus();}
    //else if((esVacio(frm.exi_ref.value)|| frm.exi_ref.value<=0)&&(frm.exi_serv.value<=0 || esVacio(frm.exi_serv.value))){alert('Debe cargar articulos y/o servicios a la Factura!');$('#cod').focus();}
   else if(frm.num_serv.value>0&&esVacio(frm.placa.value)){alert('Ingrese la Placa de la Moto');frm.placa.focus();}
	else if(entrega<tot&& frm.form_pago.value!='Credito' && frm.form_pago.value!='SisteCredito'){alert('El valor entregado es inferior al Total, Cambielo');$('#entrega').focus();}
	else if(entrega<=0&&frm.form_pago.value!='Credito' && frm.form_pago.value!='SisteCredito'){alert('Ingrese el monto que entrega el cliente');$('#entrega').focus();}
	
	
	
	
	
	else {
	$('#'+id).prop("value",val);
	//alert($('#'+id).val()+' ID:'+id+' name:'+$('#'+id).prop('name'));
    $(btn).remove();
	frm.submit();
	
	}
	
	
	
};
function gfvt(val,id,frm)
{
	//alert(frm.meca.value+' '+esVacio(frm.tipo_cli.value));
//	tot();
    var tot=quitap($('#TOTAL').val())*1;
	//var entrega=quitap($('#entrega').val())*1 || 0;
	//alert(tot);
    if(esVacio(frm.form_pago.value)){alert('Especifique Forma de Pago');frm.form_pago.focus();}
	else if(esVacio(frm.tipo_cli.value)){alert('Especifique Tipo de Cliente');frm.tipo_cli.focus();}
	else if(esVacio(frm.ot.value)){alert('Ingrese Num. Orden Taller');frm.ot.focus();}
	else if(esVacio(frm.vendedor.value)){alert('Especifique el Vendedor');frm.vendedor.focus();}
	else if(esVacio(frm.meca.value) ){alert('Escoga el mecanico');frm.meca.focus();}
	else if(esVacio(frm.ced.value)||esVacio(frm.cli.value))
	{alert('Registre el Cliente');
	if(esVacio(frm.cli.value))$('#cli').focus();
	else if(esVacio(frm.ced.value))$('#ced').focus();
	}
	else if((esVacio(frm.exi_ref.value)|| frm.exi_ref.value<=0)&&(frm.exi_serv.value<=0 || esVacio(frm.exi_serv.value))&&dcto_remi==0){alert('Debe cargar articulos y/o servicios a la Factura!');$('#cod').focus();}
    
   else if(frm.num_serv.value>0&&esVacio(frm.placa.value)){alert('Ingrese la Placa de la Moto');frm.placa.focus();}
	
	
	else {
	$('#'+id).prop("value",val);
	//alert($('#'+id).val()+' ID:'+id+' name:'+$('#'+id).prop('name'));
      
	frm.submit();
	$('#btn').remove();
	}
	
	
	
};
function gfgas(val,id,frm)
{};


function add_serv()
{
  var html='';//'<tr><td colspan="5" align="center">Servicios Taller Honda</td></tr>';
  var tipo_serv=$('#tipo_man').val();
  var ts='';
  var $sel='';
  //alert(tipo_serv);
  if(tipo_serv!='Servicio adicional' && tipo_serv!='Inspeccion'&& tipo_serv!='Trabajo de terceros' && tipo_serv!='FRT')
  {
	  //alert('split!');
	  ts=tipo_serv.split('|');ts=ts[1];}
  else ts=tipo_serv;
  $sel=$("input[value$='"+ts+"']");
  //alert(ts+', '+$sel.prop('id')+', val:'+$sel.val());
  if($sel.length==0)
  {
	  //alert('valido');  
  if(!esVacio(tipo_serv) && (!esVacio($('#cc').val()) )){
  
  if(tipo_serv=='Servicio adicional' || tipo_serv=='19|CAMBIO DE PARTES' || tipo_serv=='Trabajo de terceros'|| tipo_serv=='Garantia Tecnico' || tipo_serv=='Alistamiento' )
	{//alert('Bazinga');
		if(tipo_serv=='19|CAMBIO DE PARTES'){ts=tipo_serv.split('|');tipo_serv=ts[1];}
		//$('#cilindraje').hide();
		$('#revision').hide();
		$('#alas').hide();
		$('#hh').hide();
		$('#val_frt').hide();
		$('#log_serv').show();
		$('#precio_s').prop({'value':''});
		//$('#precio_sA').prop({'value':''});
		$('#precio_serv').hide();
		
		var precio=quitap($('#precio_sA').val())*1;
        var vsi=precio;
        //vsi=vsi/(1.16);
        if(tipo_serv=='Trabajo de terceros')vsi=precio;
		
  html+='<tr class="serv'+cont_serv+'"><td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="tipo_serv'+cont_serv+'" value="'+tipo_serv+'" id="Tipo_serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="id_tipo_serv'+cont_serv+'" id="id_tipo_serv'+cont_serv+'" value="'+tipo_serv+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" colspan="3">Cilindraje: <input readonly="readonly" style="width:60px" class="serv'+cont_serv+'" type="text" name="cc'+cont_serv+'" value="'+$('#cc').val()+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" colspan="">Nota: <textarea cols="10" rows="3" style="width:100px" class="serv'+cont_serv+'" name="nota'+cont_serv+'" cols=\"25\" rows=\"2\"></textarea></td>';
  
html+='<td class="serv'+cont_serv+'"><input class="serv'+cont_serv+'" type="text" name="dcto_serv'+cont_serv+'" id="dcto_serv'+cont_serv+'" value="'+0+'" style="width:50px" onKeyUp="omga('+cont_serv+');" ></td>';
  
  html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="precio_serv'+cont_serv+'" id="precio_serv'+cont_serv+'" value="'+puntob(vsi)+'" ></td>';
  
  if(dcto_remi==28)html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_serv'+cont_serv+'" value="'+puntob((vsi))+'" ></td>';
  
  else html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_serv'+cont_serv+'" value="'+puntob((vsi))+'" ></td>';
  	 
  html+='<td class="serv'+cont_serv+'"><img onMouseUp="eli_serv($(this).prop(\'class\'))" class="'+cont_serv+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';	
  var $serv=$(html);
   $serv.appendTo('#servicios');
   
   cont_serv++;
   serv_exist++;
   $('#num_serv').prop('value',cont_serv);
   $('#exi_serv').prop('value',serv_exist);
   
   tot();
		
		
		}
  
  else if(tipo_serv=='FRT')
	{
		//$('#cilindraje').hide();
		$('#revision').hide();
		$('#alas').hide();
		$('#log_serv').hide();
		$('#hh').show();
		$('#val_frt').show();
		$('#precio_servA').hide();
		
		if( !esVacio( $('#valor_frt').val()))
		{
		var precio=quitap($('#precio_s').val())*1;
        var vsi=precio;///(iva_serv+1);
  
  html+='<tr class="serv'+cont_serv+'"><td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="tipo_serv'+cont_serv+'" value="'+tipo_serv+'" id="Tipo_serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="id_tipo_serv'+cont_serv+'" id="id_tipo_serv'+cont_serv+'" value="'+tipo_serv+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" colspan="">Cilindraje: <input readonly="readonly" style="width:60px" class="serv'+cont_serv+'" type="text" name="cc'+cont_serv+'" value="'+$('#cc').val()+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" colspan="">Nota: <textarea cols="10" rows="3" style="width:100px" class="serv'+cont_serv+'" name="nota'+cont_serv+'" cols=\"25\" rows=\"2\">Valor FRT:'+$('#valor_frt').val()+'</textarea></td>';
  
  html+='<td class="serv'+cont_serv+'" ><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="hh'+cont_serv+'" value="'+HH+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" ><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="frt'+cont_serv+'" value="'+$('#valor_frt').val()+'" ></td>';
  
html+='<td class="serv'+cont_serv+'"><input class="serv'+cont_serv+'" type="text" name="dcto_serv'+cont_serv+'" id="dcto_serv'+cont_serv+'" value="'+0+'" style="width:50px" onKeyUp="omga('+cont_serv+');" ></td>';
  
  html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="precio_serv'+cont_serv+'" id="precio_serv'+cont_serv+'" value="'+puntob(vsi)+'" ></td>';
  
 if(dcto_remi==28)html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_serv'+cont_serv+'" value="'+puntob((vsi))+'" ></td>';
  
  else html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_serv'+cont_serv+'" value="'+puntob((vsi))+'" ></td>';
  	
  html+='<td class="serv'+cont_serv+'"><img onMouseUp="eli_serv($(this).prop(\'class\'))" class="'+cont_serv+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';	
  var $serv=$(html);
   $serv.appendTo('#servicios');
   
   cont_serv++;
   serv_exist++;
   $('#num_serv').prop('value',cont_serv);
   $('#exi_serv').prop('value',serv_exist);
   
   tot();
		}

		
	}
	else if(tipo_serv=='Inspeccion')
	{
	    //$('#cilindraje').hide();
		$('#log_serv').hide();
		$('#revision').show();
		$('#alas').show();
		$('#hh').hide();
		$('#val_frt').hide();
		$('#precio_servA').hide();
		
		var precio=quitap($('#precio_s').val())*1;
        var vsi=precio;///(iva_serv+1);
  
  html+='<tr class="serv'+cont_serv+'"><td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="tipo_serv'+cont_serv+'" value="'+tipo_serv+'" id="Tipo_serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="id_tipo_serv'+cont_serv+'" id="id_tipo_serv'+cont_serv+'" value="'+tipo_serv+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" colspan="">Cilindraje:<input readonly="readonly" style="width:60px" class="serv'+cont_serv+'" type="text" name="cc'+cont_serv+'" value="'+$('#cc').val()+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'" >Revisi&oacute;n:<input readonly="readonly" style="width:50px;" class="serv'+cont_serv+'" type="text" name="revision'+cont_serv+'" value="'+$('#rev').val()+'" >Km</td>';
  
  html+='<td valign="middle" class="serv'+cont_serv+'" colspan="">Nota: <textarea cols="10" rows="3" style="width:60px" class="serv'+cont_serv+'" name="nota'+cont_serv+'" cols=\"35\" rows=\"2\">Revision '+$('#rev').val()+'Km</textarea></td>';
  
  html+='<td class="serv'+cont_serv+'" ><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="alas'+cont_serv+'" value="'+$('#alas_txt').val()+'" ></td>';
  
html+='<td class="serv'+cont_serv+'"><input class="serv'+cont_serv+'" type="text" name="dcto_serv'+cont_serv+'" id="dcto_serv'+cont_serv+'" value="'+0+'" style="width:50px" onKeyUp="omga('+cont_serv+');" ></td>';
  
  html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="precio_serv'+cont_serv+'" id="precio_garantia'+cont_serv+'" value="'+puntob(vsi)+'" ></td>';
  
  html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_garantia'+cont_serv+'" value="'+puntob((vsi))+'" ></td>';
  	
  html+='<td class="serv'+cont_serv+'"><img onMouseUp="eli_serv($(this).prop(\'class\'))" class="'+cont_serv+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';	
  var $serv=$(html);
   $serv.appendTo('#servicios');
   
   cont_serv++;
   serv_exist++;
   $('#num_serv').prop('value',cont_serv);
   $('#exi_serv').prop('value',serv_exist);
   tot();
   
   
		
		
		
		
			
	}
	else
	{
if(!esVacio($('#cc').val())){
  var tipoServ=tipo_serv.split('|');
  var precio=quitap($('#precio_s').val())*1;
  var vsi=precio;
  //vsi=vsi/(1.16);
  //alert(vsi);
  vsi=redondeo(vsi);
  $('#log_serv').hide();
  //tipo serv
  html+='<tr class="serv'+cont_serv+'"><td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="tipo_serv'+cont_serv+'" value="'+tipoServ[1]+'" id="Tipo_serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="hidden" name="id_tipo_serv'+cont_serv+'" id="id_tipo_serv'+cont_serv+'" value="'+tipoServ[0]+'" ></td>';
  
  //cilindraje
  html+='<td class="serv'+cont_serv+'" colspan="3">Cilindraje: <input readonly="readonly" class="serv'+cont_serv+'" style="width:100px" type="text" name="cc'+cont_serv+'" value="'+$('#cc').val()+'" ></td>';
  
  //nota
  html+='<td valign="middle" class="serv'+cont_serv+'" colspan="">Nota: <textarea style="width:100px" class="serv'+cont_serv+'" name="nota'+cont_serv+'" cols=\"35\" rows=\"2\"></textarea></td>';
  
  
//descuento  
html+='<td class="serv'+cont_serv+'"><input readonly="" class="serv'+cont_serv+'" type="text" name="dcto_serv'+cont_serv+'" id="dcto_serv'+cont_serv+'" value="'+0+'" style="width:50px" onKeyUp="omga('+cont_serv+');" ></td>';
  
 
//precio serv
  html+='<td class="serv'+cont_serv+'"><input readonly="" class="serv'+cont_serv+'" type="text" name="precio_serv'+cont_serv+'" id="precio_serv'+cont_serv+'" value="'+puntob(vsi)+'" onKeyUp="puntoa($(this));tot();" ></td>';

//precio tot
   if(dcto_remi==28)html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_serv'+cont_serv+'" value="'+puntob(vsi)+'" ></td>';
  
  else html+='<td class="serv'+cont_serv+'"><input readonly="readonly" class="serv'+cont_serv+'" type="text" name="subTot_serv'+cont_serv+'" id="subTot_serv'+cont_serv+'" value="'+puntob(vsi)+'" ></td>';

//borrar
  html+='<td class="serv'+cont_serv+'"><img onMouseUp="eli_serv($(this).prop(\'class\'))" class="'+cont_serv+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';	
  var $serv=$(html);
   $serv.appendTo('#servicios');
   
   cont_serv++;
   serv_exist++;
   $('#num_serv').prop('value',cont_serv);
   $('#exi_serv').prop('value',serv_exist);
   //alert($('#num_serv').val()+'----'+$('#exi_serv').val());
   tot();
   
}
	}
	
	//alert($('#num_serv').val()+'----'+$('#exi_serv').val());
	//alert(cont_serv+" ---"+serv_exist);
  }//if tipo_serv vacio
  }
  else alert('Servicio duplicado!');
};

function servicios()
{
	//alert($('#tipo_man').css('left'));
	var tipo_serv=$('#tipo_man').val();
	//alert(tipo_serv);
	$('#precio_serv').show();
	
	$('#precio_s').prop({'value':''});
	$('#precio_sA').prop({'value':''});
	
	if(tipo_serv=='Servicio adicional' || tipo_serv=='19|CAMBIO DE PARTES' || tipo_serv=='Trabajo de terceros'|| tipo_serv=='Garantia Tecnico' || tipo_serv=='Alistamiento')
	{
		//$('#cilindraje').hide();
		
		$('#revision').hide();
		$('#alas').hide();
		$('#hh').hide();
		$('#val_frt').hide();
		$('#log_serv').show();
		$('#precio_serv').hide();
		
		
		}
	else if(tipo_serv=='FRT')
	{
		//$('#cilindraje').hide();
		$('#revision').hide();
		$('#alas').hide();
		$('#log_serv').hide();
		$('#precio_servA').hide();
		$('#hh').show();
		$('#val_frt').show();
	}
	else if(tipo_serv=='Inspeccion')
	{
	    //$('#cilindraje').hide();
		$('#revision').show();
		$('#alas').show();
		$('#hh').hide();
		$('#log_serv').hide();
		$('#precio_servA').hide();
		$('#val_frt').hide();
		
	  if(isNaN($('#alas_txt').val()))
      {
	  if($('#alas_txt').val()<=0){alert('Ingrese un Numero Mayor a Cero!');}
	  else{
	  alert('Ingrese un Numero!');}
	  $('#alas_txt').focus();
      }
		
		if(!isNaN($('#alas_txt').val())&&!esVacio($('#alas_txt').val())&&!esVacio($('#rev').val()) && !esVacio(tipo_serv)){
		
		$.ajax({
		url:'ajax/precio_garantia.php',
		data:{revision:$('#rev').val(),alas:$('#alas_txt').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=-1){
			$('#precio_s').prop('value',puntob(text));
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		}
		
		
			
	}
	else
	{
		$('#cilindraje').show();
		$('#hh').hide();
		$('#log_serv').hide();
		$('#val_frt').hide();
		$('#precio_servA').hide();
		$('#alas').hide();
		$('#revision').hide();
		//alert($('#cc').val()+'---'+tipo_serv);
		if(!esVacio($('#cc').val()) && !esVacio(tipo_serv)){
		var tipoServ=tipo_serv.split('|');
		$.ajax({
		url:'ajax/precio_man.php',
		data:{id_man:tipoServ[0],cc:$('#cc').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=-1){
			$('#precio_s').prop('value',puntob(text));
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		}
		
	}
	
	
};

function val_frtx()
{
	var frt=$('#valor_frt').val()*1;
	var val_frt=0;
	val_frt=frt*HH;
	val_frt=val_frt+val_frt*iva_serv;
	$('#precio_s').prop('value',puntob(val_frt));
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
			 $('#precio_servA').show();
			}
			else if(resp==0)
			{
				alert('Usuario No Autorizado');
				$('#precio_servA').hide();
			}
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}			
			});
		
	}
	
};
function cfvt(t,c,vc,cla)
{//alert('cerrar!');
tot();
if(!esVacio($('#rr').val()) &&$('#rr').val()!=0){
if(confirm('Desea Cerrar esta Orden de Taller?'))
{
	update(t,c,vc,cla);
	//location.assign('orden_a_fac.php');
	}	
}
else 
{
	alert('Ingrese el Numero de Requisicion de Repuestos!');
	$('#rr').focus();
};
};
function creco(v,cr,co)
{
	var $cr=$('#'+cr);
	var $co=$('#'+co);
	if(v=='Credito')
	{
		$cr.show();
		$co.hide();
	}
	else
	{
		$cr.hide();
		$co.show();
		}
	
};
var clicksAnticipo = 0;
function busq_exp($n)
 {
	// alert($n.val());
	//alert('modal: '+$('#modal').length);
	
	
	if($('#anticipo').val()=='0' && $('#modal').length==0){

	
	$.ajax({
		url:'ajax/busq_exp.php',
		data:{busq:$n.val()},
		type: 'POST',
		dataType:'text',
		success:function(text){
			if(text!=0){
		    if(confirm('Este Cliente Tiene Anticipos disponibles,CARGAR ANTICIPO?'))
			{
			
				$('#num_exp').prop('value','0');
				$('#anticipo').prop('value','0');
				
				//$('#Qresp').html(text);
				open_pop('Anticipos/Bonos','Seleccione el valor a cobrar',text);
				//$('html, body').animate({scrollTop: $("#Qtab").offset().top-120}, 800);
			}
			else 
			{
			$('#num_exp').prop('value','0');
			$('#anticipo').prop('value','0');	
			}
			
			}
			
			
			},
		error:function(xhr,status){alert('Su conexion ha fallado...intentelo mas tarde');}
		});
	}
 };
function sel_exp(i,nom,valor)
{
	$('#num_exp').prop('value',nom);
	$('#anticipo').prop('value',valor);
	hide_pop('#modal');
	//$('#fp').each(function() { this.selected = (this.text == 'Anticipo'); });
	//$('#confirma').html('CONFIRMADO').css('color','green');
	
	};

function save_ft(c)
{
	tot();
	var externals=$('.save_ft');
	var ExtString=externals.serialize();

	var row=$('input.art'+c+',textarea.art'+c);
	var serRow='';
	if(row.length!=0)serRow=row.serialize();
	
	var Datos=ExtString+'&i='+c+'&'+serRow;
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	$.ajax({
		url:'mod_fac_taller.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(c==-1)alert('Guardado con Exito');
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
};
function mover($input,$sig,$ant,flecha)
{
	//alert('zunga');
	$input
	.keydown(function(e) {
		// track last key pressed
		lastKeyPressCode = e.keyCode;
		switch(e.keyCode) {
			case 38: // up
			e.preventDefault();
				if(flecha!=0)$ant.focus(function(){this.select();});
				
				break;
			case 40: // down
			e.preventDefault();
				if(flecha!=0)$sig.focus(function(){this.select();});
				
				break;
			case 9:  // tab
			e.preventDefault();
			//alert('sig!');
			if(flecha==0)$sig.focus(function(){this.select();});
			break;
			default: break;
		}
	});
};
function move($input,u,d,l,r)
{
	//alert('zunga');
	var $u='',$d='',$l='',$r='';
	//alert('iDdown:'+d);
	$input.on('keydown',function(e) {
		// track last key pressed
		lastKeyPressCode = e.keyCode;
		//alert(e.keyCode);
		switch(e.keyCode) {
			case 38: // up
			e.preventDefault();
				if($u.lenght!=0){
					$u=$('#'+u);
					$u.focus().select();
					}
				
				break;
			case 40: // down
			e.preventDefault();
			//alert('case:iDdown:'+d);
				if($d.lenght!=0){
					$d=$('#'+d);
					$d.focus().select();
					}
				
				break;
			case 37: // left
			e.preventDefault();
				if($l.lenght!=0){
					$l=$('#'+l);
					$l.focus().select();
				}
				
				break;
			case 39: // right
			e.preventDefault();
			//alert('r:'+$r.val());
				if($r.lenght!=0){
					$r=$('#'+r);
					$r.focus().select();
				}
				break;
			case 9:  // tab
			e.preventDefault();
			//alert('sig!');
			if($r.lenght!=0){$r=$('#'+r);$r.focus().select();}
			break;
			default: break;
		}
	});
};



function envia($input)
{
	//alert('zunga');
	
	var TOTpre=0;
	var TOTpost=0;
	$input
	.keydown(function(e) {
		// track last key pressed
		lastKeyPressCode = e.keyCode;
		switch(e.keyCode) {
			case 13: // up
			e.preventDefault();
				//alert('enter!');
				TOTpre=$('#TOTAL_PAGAR').val();
				for(i=0;i<=cont;i++){if($('#val_t'+i).length!=0){valor_t(i);}	}
				//tot();
				TOTpost=$('#TOTAL_PAGAR').val();
				if(TOTpre!=TOTpost){warrn_pop("El TOTAL a pagar cambio, revise la FACTURA "+TOTpre+"==>"+TOTpost);$('#TOTAL_PAGAR').focus();}
				else{gfv($('#butt_gfv'),'genera',document.forms['form_fac']);}
				
				break;
						default: break;
		}
	});
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
		
		
		
		
		
			//resp=c*f;
			//$cant.prop('value',resp);	
		
		
	}
};

function call_tot()
{
	tot();
};
function cambio_moneda($orig,$result,val_moneda)
{
	val_moneda=quitap(val_moneda)*1 ||0;
	var pesos=quitap($orig.val())*1 ||0,rs=0,indi=val_moneda/100;
	var $entrega=$('#entrega');
	var $entrega3=$('#entrega3'),tar=quitap($entrega3.val())*1;
	var $anticipo=$('#anticipo');
	var anti=quitap($anticipo.val())*1 ||0;
	var bs=quitap($entrega.val())*1 ;
	//alert(bs);
	pesos=pesos-anti-tar;
	pesos=pesos-bs;
	if(indi>0){
		//rs=puntob(redondeo(pesos/indi));
		rs=puntob(redondeo(pesos/val_moneda));
		
	}
	else rs=0;
	//alert(rs);
	
	if(rs<0)rs=0;
	$result.prop('value',rs);
	//alert($('#bsf').prop('class'));
	if($('#bsf').prop('class')!='uk-hidden')
	{
		//alert('BsF!');
	}
};
function ajax_a(URL,Data,success_msg)
{
	//alert(URL+'?'+Data);

	$.ajax({
     type: 'POST',
     url: URL,
     data: Data,
     success: function(resp){
		 if(!esVacio(resp))open_pop('','',resp);
		
    },
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
 });
	
};

function save_tc()
{
	var data='tasa='+$('#tasaCam').val();
	ajax_a('ajax/cambio_moneda.php',data,'');
};
var contAlertOneTime=0;
function save_fv(c)
{
	tot();
	getPage($('#HTML_despues'),$('#fac_com'));
	var numFac=$('#num_fac_venta').val();
	var pre=$('#pre').val();
	var externals=$('.save_fc');
	var ExtString=externals.serialize();
	//var ExtString=$('#form_fac').serialize();
	var row=$('input.art'+c+',textarea.art'+c+',select.art'+c);
	var serRow='';
	var RowPK=$('#cod_bar'+c).val();
	var desPK=$('#det_'+c).val();
	var servPK=$('#cod_serv'+c).val();
//	var colorPK=$('#color'+c).val();
	//var tallaPK=$('#talla'+c).val();
	//alert(RowPK);
	if(row.length!=0){serRow=row.serialize();}
	//alert(serRow);
	var Datos=ExtString+'&i='+c+'&'+serRow;

	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	
	//&& !esVacio(colorPK) && !esVacio(tallaPK)
	if((!esVacio(RowPK)  && !esVacio(desPK) ||!esVacio(servPK)) || c==-1){
	$.ajax({
		url:'ajax/save_fv.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			
			if(!esVacio(text) && contAlertOneTime==0){alert('RESP saveFV:'+text);contAlertOneTime++;}
			//$('<p>'+text+'</p>').appendTo('#Qresp');
			//if(c==-1)alert('Guardado con Exito');
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
		
	}// fin no vacio
};
function save_remi(c)
{
	tot();
	getPage($('#HTML_despues'),$('#fac_com'));
	var numFac=$('#num_fac_venta').val();
	var pre=$('#pre').val();
	var externals=$('.save_fc');
	var AllForm=$("#form_fac");
	var ExtString=AllForm.serialize();
	//var ExtString=$('#form_fac').serialize();
	var row=$('input.art'+c+',textarea.art'+c+',select.art'+c);
	var serRow='';
	var RowPK=$('#cod_bar'+c).val();
	var desPK=$('#det_'+c).val();
	var servPK=$('#cod_serv'+c).val();
//	var colorPK=$('#color'+c).val();
	//var tallaPK=$('#talla'+c).val();
	//alert(RowPK);
	if(row.length!=0){serRow=row.serialize();}
	//alert(serRow);
	var Datos=ExtString+'&i='+c+'&'+serRow;

	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	
	//&& !esVacio(colorPK) && !esVacio(tallaPK)
	//alert('servCod:'+servPK);
	if(((!esVacio(RowPK)  && !esVacio(desPK)) || !esVacio(servPK)) || c==-1){
	$.ajax({
		url:'ajax/save_remi.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			//alert(text);
			if(!esVacio(text)){alert(text);}
			//$('<p>'+text+'</p>').appendTo('#Qresp');
			//if(c==-1)alert('Guardado con Exito');
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
		
	}// fin no vacio
};
function close_fv(c)
{
	//banDcto();
	var frm=document.forms['form_fac'];
	var nf=$('#num_fac').val();
	var pre=$('#pre').val();
	
	
	var fp=frm.form_pago.value;
	//alert(fp);
 
	var ctaIN=0;
	
	if(cta_bancos==1){
		
 
	 ctaIN=frm.id_cuenta.value;
	}
	
	tot();
	if(esVacio(frm.form_pago.value)){alert('Especifique Forma de Pago');frm.form_pago.focus();}
	
	else if(cta_bancos==1 && (fp!="Contado" && fp!="Credito" && fp!="Cheque") &&( esVacio(ctaIN))){alert('Seleccione la Cuenta que quiere AFECTAR');focusRed($(frm.id_cuenta));return true;}
	 else if((fp=='Contado'||fp=='Contado-Caja General') && (!esVacio(ctaIN) ) &&  cta_bancos==1){alert('Los pagos de CONTADO solo pueden ir a la Cuenta de CAJA GENERAL');focusRed($(frm.form_pago));return true;}
	
	else if(esVacio(frm.tipo_cli.value)){alert('Especifique Tipo de Cliente');frm.tipo_cli.focus();}
	else if(esVacio(frm.vendedor.value)){alert('Especifique el Vendedor');frm.vendedor.focus();}
	else if((frm.tipo_cli.value=='Taller Honda' || frm.num_serv.value>0)&&( esVacio(frm.meca.value)) ){alert('Escoga el mecanico');frm.meca.focus();}
	else if(esVacio(frm.ced.value)||esVacio(frm.cli.value))
	{alert('Registre el Cliente');
	if(esVacio(frm.cli.value))$('#cli').focus();
	else if(esVacio(frm.ced.value))$('#ced').focus();
	}
	
	
	
	else{
	
	var $prom=0;
	if(1)
	{
	var externals=$('.save_fc');
	var ExtString=externals.serialize();
	var row=$('input.art'+c+',textarea.art'+c);
	var serRow='';
	//var confirma=$('#confirma').val();
	if(row.length!=0)serRow=row.serialize();
	
	var Datos=ExtString+'&i='+c+'&'+serRow;
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	getPage($('#pagHTML'),$('#factura_venta'));
	save_fv(-1);
	if(confirm('Desea Cerrar esta  Factura de Venta?NO SE PERMITIRAN MODIFICACIONES'))
	{
		banDcto();
	$.ajax({
		url:'ajax/cerrar_fv.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(text!=0)
			{
				//alert('FACTURA CERRADA');
				//alert(text);
				
				location.assign('ventas.php?opc=Imprimir&valor='+nf+'&pre='+pre+'&tipo_imp=post&pag=1');
				
				}
				else if(text==0){
					
					//alert('La factura  CERRADA');
					location.assign('ventas.php?opc=Imprimir&valor='+nf+'&pre='+pre+'&tipo_imp=post&pag=1');
					}
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
	}
	}



	else {}
	
	
	}// fin vals
};
function eli_remi_mod(c)
{
	
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 var orig=$('#cod_orig').val();
	 
	 var ref=$('#ref_'+c).val();
	 var codBar=$('#cod_bar'+c).val();
	 var feVen=$('#feVen'+c).val();
	 var nf=$('#num_fac').val();
	 var pre=$('#pre').val();
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 
		 
		 
		 $.ajax({
			url:'ajax/del_art_remi.php',
			data:{ref:ref,cod_barras:codBar,feVen:feVen,nf:nf,pre:pre,cod_origen:orig} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
				//alert(resp);
				save_remi(-1);
			
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 cont_dcto=0;
		 $('#exi_ref').prop('value',ref_exis);
		 //alert(ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 
};

function eli_serv_mod_ven(c)
{
	
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 
	 var id_serv=$('#id_serv'+c).val();


	 var nf=$('#num_fac').val();
	 var pre=$('#pre').val();
	 if($("#serv"+c+"").length==0)$det='la Fila?';
	 else $det=$("#serv"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 
		 
		 
		 $.ajax({
			url:'ajax/del_serv_ven.php',
			data:{idServ:id_serv,nf:nf,pre:pre} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
				//alert(resp);
				save_fv(-1);
			
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 cont_dcto=0;
		 $('#exi_ref').prop('value',ref_exis);
		 //alert(ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 
};

function eli_serv_mod_remi(c)
{
	
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 
	 var id_serv=$('#id_serv'+c).val();


	 var nf=$('#num_fac').val();
	 var pre=$('#pre').val();
	 if($("#serv"+c+"").length==0)$det='la Fila?';
	 else $det=$("#serv"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 
		 
		 
		 $.ajax({
			url:'ajax/del_serv_remi.php',
			data:{idServ:id_serv,nf:nf,pre:pre} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
				//alert(resp);
				save_remi(-1);
			
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 cont_dcto=0;
		 $('#exi_ref').prop('value',ref_exis);
		 //alert(ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 
};
function eli_fv_mod(c)
{
	
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 
	 var ref=$('#ref_'+c).val();
	 var codBar=$('#cod_bar'+c).val();
	 var feVen=$('#feVen'+c).val();
	 var nf=$('#num_fac').val();
	 var pre=$('#pre').val();
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 
		 
		 
		 $.ajax({
			url:'ajax/del_art_ven.php',
			data:{ref:ref,cod_barras:codBar,feVen:feVen,nf:nf,pre:pre} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
				//alert(resp);
				save_fv(-1);
			
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 
		 $eliRow.remove();
		 tot();
		 $('#num_ref').prop('value',cont);
		 ref_exis--;
		 cont_dcto=0;
		 $('#exi_ref').prop('value',ref_exis);
		 //alert(ref_exis);
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 
};
function addRow(r,Fname,EliF)
{
	//alert('addR');
	var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
	var iva=(r[3]/100)+1;
			   //alert(iva+',r4:'+r[4]);
	var vsi=r[4];
	if(dcto_remi==28)
	{
			     //vsi=(r[5]/(iva+1)) + (r[5]*0.02);
		vsi=(r[5])*1.02;
			   
	}
	if(  $('#tipo_cli').val()=='Empresas' && r[3]!=0)
			   {
				vsi=redondeo2(r[20]*1.19);   
			   }
	vsi=redondeo(vsi);
				   //ref
			   html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   
			   
			
			   // SN - IMEI
		
			   if(usarSerial==1) html+='<td class="art'+cont+' " align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="SN_'+cont+'" placeholder="S/N" style=" width:100px" onChange="'+Fname+'('+cont+')"/></td>';
			   
			   
			   
			   // COD-GARANTIA
			
			   if(usar_cod_garantia==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="COD_GARANTIA'+cont+'" name="COD_GARANTIA'+cont+'" placeholder="Garantia" style=" width:100px" onChange="'+Fname+'('+cont+')"/></td>';
			 
			   
			   
			   //cod. bar
			   
			   html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
			   
			   //descripcion
			   html+='<td class="art'+cont+'"><textarea style="" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   
			   //presentacion
			   html+='<td class="art'+cont+' uk-visible-large"><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   
			   //feVenci
			   if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="text" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   
			   
			   
			   //color
			   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px" readonly/></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px" readonly/></td>';
			   
			   
			   //iva
			   
			   
			   if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="valor_t('+cont+');" style=" width:50px" readonly=""/></td>';
			   
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="valor_t('+cont+');" onBlur="'+Fname+'('+cont+');" style=" width:50px" /></td>';
			   
			   //cant/cantLim
			   var Cant='',Uni=0;
			   if(tipoFAC=='A')Cant=1;
			   if(r[0]==0){Cant=0;Uni=1};
			   //Cant="";
			   Uni="";
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="20" maxlength="20" value="'+Cant+'" onkeyDown="//move($(this),$(\'#'+(cont-1)+'cant_\'),$(\'#'+(cont+1)+'cant_\'),$(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'));" onkeyup="calc_uni($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);'+Fname+'('+cont+');" style=" width:50px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   
			   //alert('add 1');
			    //fracc-unidades
			   if(usarFracc==1)html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+Uni+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder=""  onKeyUp="calc_cant($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t('+cont+');" onBlur="'+Fname+'('+cont+');"/>';
			   
			   if(usarFracc==1)html+='<input class="uk-form-small art'+cont+'"  value="'+r[15]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[16]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';
			  
		
		if(carros_rutas==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+Uni+'" type="text" id="'+cont+'cant_dev" name="cant_dev'+cont+'" placeholder=""  onKeyUp="'+Fname+'('+cont+');"/>';
			   
			   //descuento
			   if(r[19]==0){html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}
			   else {html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));'+Fname+'('+cont+');" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}

			   //valor unitario/val Min
			   if(r[13]==0){html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="change16('+cont+');valMin($(this),'+r[6]+','+vsi+','+cont+');'+Fname+'('+cont+');" readonly="" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';
			 }
			 else{html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');'+Fname+'('+cont+');" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';}
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'" bgcolor="white"><img onMouseUp="'+EliF+'($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ><br><div class="pretty p-switch p-fill"><input class="art'+cont+'" type="checkbox" name="imprimirComanda'+cont+'" id="imprimirComanda'+cont+'" value="1" onClick="save_fv('+cont+');" checked/><div class="state p-success"><label></label></div></div></td></tr>';
			   
			   return html;
};
function addRow2(r,Fname,EliF)
{
	
	//alert('addR2');
	var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
	var iva=(r[3]/100)+1;
			   //alert(iva+',r4:'+r[4]);
	var vsi=r[4];
	if(dcto_remi==28)
	{
			     //vsi=(r[5]/(iva+1)) + (r[5]*0.02);
		vsi=(r[5])*1.02;
			   
	}
	if(  $('#tipo_cli').val()=='Empresas' && r[3]!=0)
			   {
				vsi=redondeo2(r[20]*1.19);   
			   }
	vsi=redondeo(vsi);
	
	//ref
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+r[1]+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" readonly="" value=\"'+(r[1]+cont)+'\" type="text" id="ref_'+cont+'" name="ref_'+cont+'" style=" width:80px"/><input class="uk-form-small art'+cont+'" readonly="" value=\"'+cont+'\" type="hidden" id="orden_in'+cont+'" name="orden_in'+cont+'" style=" width:80px"/></td>';
			   
			   			   
			   
			   //cod. bar
			   
			    if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[11]+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
				else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'"  value="'+(r[11]+cont)+'" type="text" id="cod_bar'+cont+'" name="cod_bar'+cont+'" placeholder="" style=" width:130px" readonly/></td>';
				
				  // SN - IMEI
			   //html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="IMEI_'+cont+'" placeholder="IMEI" style=" width:50px"/></td>';
			   if(usarSerial==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="'+cont+'" name="SN_'+cont+'" placeholder="S/N" style=" width:100px" onBlur="'+Fname+'('+cont+');"/></td>';
			   
			   // COD-GARANTIA
			
			   if(usar_cod_garantia==1) html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="" type="text" id="COD_GARANTIA'+cont+'" name="COD_GARANTIA'+cont+'" placeholder="Garantia" style=" width:100px" onBlur="'+Fname+'('+cont+')"/></td>';
			   
			   
			   //descripcion
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+'"><textarea style="" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" readonly="">'+r[2]+'</textarea></td>';
			   else html+='<td class="art'+cont+'"><textarea style=" width:250px" cols="10" rows="1" class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" >'+r[2]+'</textarea></td>';
			   
			   //presentacion
			   if(vendeSin==0 || r[1]!='VENTA-000')html+='<td class="art'+cont+' uk-visible-large"><input class="uk-form-small art'+cont+' uk-visible-large"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" readonly/></td>';
			   else html+='<td class="art'+cont+' uk-visible-large"><input class="uk-form-small art'+cont+'"  value="'+r[17]+'" type="text" id="presentacion'+cont+'" name="presentacion'+cont+'" placeholder="" style=" width:100px" /></td>';
			   
			   //feVenci
			  if(vendeSin==0 || r[1]!='VENTA-000'){if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="text" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:100px" readonly/></td>';}
			   else{if(usarFechaVenci==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+r[18]+'" type="date" id="feVen'+cont+'" name="feVen'+cont+'" placeholder="" style=" width:130px" /></td>';}
			   
			    if(vendeSin==0 || r[1]!='VENTA-000'){
			   //color
			   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px" readonly/></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px" readonly/></td>';
				}
				else
				{
					
				   if(usarColor==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[8]+'" type="text" id="color'+cont+'" name="color'+cont+'" placeholder="" style=" width:50px"  onBlur="'+Fname+'('+cont+');"/></td>';
			   
			   
			   //talla
			  if(usarTalla==1)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'"  value="'+r[9]+'" type="text" id="talla'+cont+'" name="talla'+cont+'" placeholder="" style=" width:40px"  onBlur="'+Fname+'('+cont+');"/></td>';	
					
					
				}
			   
			   //iva
			   if(vendeSin==0 || r[1]!='VENTA-000'){
				 if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="javascript:valor_t('+cont+');" style=" width:50px" readonly=""/></td>';
			   
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="valor_t('+cont+');" style=" width:50px" onBlur="'+Fname+'('+cont+');"/></td>';
				   
				   }
			   else html+='<td class="art'+cont+' uk-visible-large" align="center"><input class="uk-form-small art'+cont+'" id="iva_'+cont+'" type="text" name="iva_'+cont+'" size="5" maxlength="5" value=\"'+r[3]+'\" onkeyup="valor_t('+cont+');" style=" width:50px" onBlur="'+Fname+'('+cont+');"/></td>';
			   
			   
			   
			   //cant/cantLim
			   var Cant='',Uni=0;
			   if(tipoFAC=='A')Cant=1;
			   if(r[0]==0){Cant=0;Uni=1};
			    //Cant="";
			   Uni="";
			   html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' fc_cant" id="'+cont+'cant_" type="number" name="cant_'+cont+'" size="5" maxlength="20" value="'+Cant+'" onkeyDown="//move($(this),$(\'#'+(cont-1)+'cant_\'),$(\'#'+(cont+1)+'cant_\'),$(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'));" onkeyup="calc_uni($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t(this.id);" onblur="cant_dcto(this.id);valor_t(this.id);'+Fname+'('+cont+');" style=" width:50px"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+r[0]+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
			   
			   
			    //fracc-unidades
			   if(usarFracc==1)html+='<td class="art'+cont+'"><input class="uk-form-small fc_frac art'+cont+'"  value="'+Uni+'" type="text" id="unidades'+cont+'" name="unidades'+cont+'" placeholder=""  onKeyUp="calc_cant($(\'#'+cont+'cant_\'),$(\'#fracc'+cont+'\'),$(\'#unidades'+cont+'\'));valor_t('+cont+');" onBlur="'+Fname+'('+cont+');"/>';
			   
			   if(usarFracc==1)html+='<input class="uk-form-small art'+cont+'"  value="'+r[15]+'" type="hidden" id="fracc'+cont+'" name="fracc'+cont+'" placeholder="" style=" width:80px" readonly/><input class="uk-form-small art'+cont+'"  value="'+r[16]+'" type="hidden" id="unidadesH'+cont+'" name="unidadesH'+cont+'" placeholder="" style=" width:80px" readonly/></td>';
			  
		
		if(carros_rutas==1)html+='<td class="art'+cont+'"><input class="uk-form-small art'+cont+'"  value="'+Uni+'" type="text" id="'+cont+'cant_dev" name="cant_dev'+cont+'" placeholder=""  onKeyUp="'+Fname+'('+cont+');"/>';
			  
		
			   
			   //descuento
			   if(r[19]==0){html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}
			   else {html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+r[12]+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));'+Fname+'('+cont+');" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';}

			   //valor unitario/val Min
			   if(r[13]==0){html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="change16('+cont+');valMin($(this),'+r[6]+','+vsi+','+cont+');" readonly="" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';
			 }
			 else {html+='<td class="art'+cont+'" ><input class=" valorProducto art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(parseFloat(vsi))+'\"  onBlur="valMin($(this),'+r[6]+','+vsi+','+cont+');change16('+cont+');'+Fname+'('+cont+');" style=" "/><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[20]+'" /><input class="uk-form-small art'+cont+'" id="costo'+cont+'H" name="costo'+cont+'H" type="hidden" readonly="" value="'+r[5]+'" /></td>';}
			   
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
			   html+='<td class="art'+cont+'" bgcolor="white"><img onMouseUp="'+EliF+'($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ><br><div class="pretty p-switch p-fill"><input class="art'+cont+'" type="checkbox" name="imprimirComanda'+cont+'" id="imprimirComanda'+cont+'" value="1" onClick="save_fv('+cont+');" checked/><div class="state p-success"><label></label></div></div></td></tr>';
	
	return html;
};

function add_art_ven_mod() {
clearTimeout(timeOutCod);
var tipoCli=$('#tipo_cli').val();
var tipoResol = $('#tipo_resol').val();
contTO=0;
	var cotiza=0;
	if($("#co").lenght!=0){cotiza=$("#co").val();}	
 
if(val_rep_fv('','','')){
//alert('entra if ;'+$sel.length);
        $.ajax({
		url:'ajax/add_art_ven.php',
		data:{codBar:$cod=$('#cod').val(),
		fe_ven:$('#feVen').val(),
		ref:$('#Ref').val(),
		idCli:''+$('#ced').val()+'',
		tc:tipoCli,co:cotiza,
		tipoResol:tipoResol},
	    type:'POST',
		dataType:'text',
		success:function(text){
		var resp=text;
		//alert('text:['+text+'];');
		var r=text.split('|');
			//alert(''+r[11]+','+r[18]);
			//alert(r[21]);
			if(val_rep_fv(r[1],r[11],r[18])){
			
		    if(r[0]==0 && r[16]==0) {
			 alert("Articulo AGOTADO, Intente otra opcion");
			 //alert(r[16]);
			 //busq($('#cod'));
			 
			 var $cod=$('#cod');
			 //$('#cod').blur();
		     //$cod.prop("value","");
			
		    
			 
			 
			 }
			 else if(resp==-2 ||resp==0){
				// alert('.:Articulo No Encontrado:.');
				 //$('#cod').blur();
				 }
			 
			 else if((r[0]!=0 || r[16]!=0)&&resp!=-2){
				 //alert(text);
			   var $cod=$('#cod');
			   var $feVenci=$('#feVen');			  
               var det="det_",val_u="val_u";
			   // r=text.split('|');
			   
				//alert(flag_reSearch);
				//alert('RESP:'+r[14]);
				 if(r[14]==2&&(usarFechaVenci==0 && flag_reSearch==0))
				   {
					   //alert('if!');
					   busq($('#cod'));
					   flag_reSearch=1; 
				   }
			   else if((r[14]==2&&(usarFechaVenci==1 && flag_reSearch==0) )&& vendeSin==0)
			   {
	if(confirm('Existen otras Ref. con Fecha de Vencimiento diferente, Desea buscar otras Referencias??'))
				   {
					   busq($('#cod'));
					   flag_reSearch=1;
					   //alert(flag_reSearch);
				   }
				 
				   else
				   {
			   //alert('nrm');
			   //alert('Pvp Cre:'+r[20]);
			 var html=addRow(r,"save_fv","eli_fv_mod");
			   
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
//move($('#'+cont+'cant_'),$('#'+(cont-1)+'cant_'),$('#'+(cont+1)+'cant_'),$('#'+cont+'cant_'),$('#unidades'+cont));
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
			  // alert('Producto Agregado');
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   save_fv(cont);
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
			   if($('#marca_moto').lenght!=0){$('#marca_moto').prop('value',r[23]);}
			   
			   
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   if(mesas_pedidos==1 || fac_servicios_mensuales==1){
				
			   }
			   else {
				hide_pop('#modal');
			   }
			   // 
			  // $cod.prop('value',r[21]);  
				
				   
				   
				   }// fin else 
				   
			   } // fin if otra sugerencia
			   else{
				//alert('nrm2');   
			   
			   var html=addRow2(r,"save_fv","eli_fv_mod");
			   
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   //alert('Producto Agregado');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
				
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   save_fv(cont);
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
			   if($('#marca_moto').lenght!=0){$('#marca_moto').prop('value',r[23]);}
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   if(mesas_pedidos==1 || fac_servicios_mensuales==1){
				
			   }
			   else {
				hide_pop('#modal');
			   }
			   //$cod.focus();
			    //$cod.prop('value',r[21]); 
				   
			   }
		    
			
			}
			
		 

		
		}//fin if rep
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		 }//fin else


   };
function save_remiNULL(i)
{
	
};
function add_art_remi_mod() {   
clearTimeout(timeOutCod);
//alert('add remi mod');
contTO=0;
		var cotiza=0;
	if($("#co").lenght!=0){cotiza=$("#co").val();}	
 
		 if(val_rep_fv('','','')){
			 //alert('entra if ;'+$sel.length);

		 
         $.ajax({
		url:'ajax/add_art_ven.php',
		data:{codBar:$cod=$('#cod').val(),fe_ven:$('#feVen').val(),ref:$('#Ref').val(),tc:$('#tipo_cli').val(),co:cotiza},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=text;
		//alert('text:['+text+'];');
		
		var r=text.split('|');
			//alert(''+r[11]+','+r[18]);
			//alert(r[21]);
			if(val_rep_fv(r[1],r[11],r[18])){
			
		    if(r[0]==0 && r[16]==0) {
			 alert("Articulo AGOTADO, Intente otra opcion");
			 //alert(r[16]);
			 //busq($('#cod'));
			 
			 var $cod=$('#cod');
			 //$('#cod').blur();
		     //$cod.prop("value","");
			
		    
			 
			 
			 }
			 else if(resp==-2 ||resp==0){
				// alert('.:Articulo No Encontrado:.');
				 //$('#cod').blur();
				 }
			 
			 else if((r[0]!=0 || r[16]!=0)&&resp!=-2){
				 //alert(text);
			   var $cod=$('#cod');
			   var $feVenci=$('#feVen');			  
               var det="det_",val_u="val_u";
			   // r=text.split('|');
			   
				//alert(flag_reSearch);
				//alert('RESP:'+r[14]);
				 if(r[14]==2&&(usarFechaVenci==0 && flag_reSearch==0))
				   {
					   //alert('if!');
					   busq($('#cod'));
					   flag_reSearch=1; 
				   }
			   else if(r[14]==2&&(usarFechaVenci==1 && flag_reSearch==0))
			   {
	if(confirm('Existen otras Ref. con Fecha de Vencimiento diferente, Desea buscar otras Referencias??'))
				   {
					   busq($('#cod'));
					   flag_reSearch=1;
					   //alert(flag_reSearch);
				   }
				 
				   else
				   {
			   //alert('nrm');
			   //alert('Pvp Cre:'+r[20]);
			 var html=addRow(r,"","eli_remi_mod");
			   
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
//move($('#'+cont+'cant_'),$('#'+(cont-1)+'cant_'),$('#'+(cont+1)+'cant_'),$('#'+cont+'cant_'),$('#unidades'+cont));
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
			  // alert('Producto Agregado');
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   save_remi(cont);
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   hide_pop('#modal');	
			  // $cod.focus(); 
			  // $cod.prop('value',r[21]);  
				
				   
				   
				   }// fin else 
				   
			   } // fin if otra sugerencia
			   else{
				//alert('nrm2');   
			   
			   var html=addRow2(r,"","eli_remi_mod");
			   
               var $ar=$(html);
               $ar.appendTo('#articulos');
			   //alert('Producto Agregado');
			    if(tipoFAC=='A'){$cod.focus();}
			   else {$('#'+cont+'cant_').focus();}
				
				move($('#'+cont+'cant_'),(cont-1)+'cant_',(cont+1)+'cant_',cont+'cant_','unidades'+cont);
	
				move($('#unidades'+cont),'unidades'+(cont-1),'unidades'+(cont+1),cont+'cant_','dcto_'+cont);
	
				move($('#dcto_'+cont),'dcto_'+(cont-1),'dcto_'+(cont+1),'unidades'+cont,'val_u'+cont);
	
				move($('#val_u'+cont),'val_u'+(cont-1),'val_u'+(cont+1),'dcto_'+cont,'val_u'+cont); 
			  // alert('#'+cont+'cant_');
			   //$('#'+cont+'cant_').focus();
			   busq_dcto2(cont);
			   valor_t(cont);
			   save_remi(cont);
               cont++;
			   flag_reSearch=0;
			   ref_exis++;
			   $('#Ref').prop('value','');
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
			   
		       $cod.prop("value","");
			   $feVenci.prop('value','');
		       $cod.unbind('focus'); 
			   hide_pop('#modal');	
			  // $cod.focus();
			    //$cod.prop('value',r[21]); 
				   
			   }
		    
			
			}
			
		 

		
		}//fin if rep
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
		 }//fin else


   };
function remi_fv()
{
	var nf=$('#num_fac').val();
	var pre=$('#pre').val();
	var orig=$('#cod_orig').val();
	var dest=$('#cod_dest').val();
	
	
	if(confirm('Desea Crear Factura de Venta y Realizar Entrega de Mercancia?')){
	$.ajax({
		url:'ajax/remi_fv.php',
		data:{nf:nf,pre:pre,orig:orig,dest:dest},
		type:'POST',
		dataType:'text',
		success:function(text){
			
			if(!esVacio(text)){alert(text);}
			//$('<p>'+text+'</p>').appendTo('#Qresp');
			//if(c==-1)alert('Guardado con Exito');
			//else alert('Actualizado');
			alert('Operacion Finalizada');
			location.assign('traslados_salen.php');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
	}
};
function calc_per($per,$val,$resp)
{
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeo2(val*per));
	
	if(tipoUtil=="B"){resp=puntob(redondeo2(val-val/(per+1)));}
	
	$resp.prop('value',resp);
	call_tot();
	
};
function calc_per2($per,$val,$resp)
{
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeoFacVenta(val*per,2));
	
	$resp.prop('value',resp);
	
};
var ExListDctoB= new Array();

function val_pvp_dctoB(p)
{
	var D=quitap($('#DESCUENTO2').val());
	
	var vsi=0,vi=0,v2=0,iva=0,valOrig=0,pAnt=0,i=0,cant=0;
	var dd=0;
	var Tcant=0;
	for(i=0;i<=cont;i++)
	{
		//alert('cont art:'+cont);
		if($('#val_t'+i).length!=0)
		{
			valOrig=quitap($('#val_orig'+i).val())*1;
			/*if(FormaPago!='Credito'){valOrig=quitap($('#val_orig'+i).val())*1;}
			else valOrig=quitap($('#val_cre'+i+'H').val())*1;
			*/
			cant=$('#'+i+'cant_').val()*1||0;
			uni=val_ele('#unidades'+i)*1||0;
   			frac=val_ele('#fracc'+i)*1 ||1;
			//alert('frac:'+frac);
			factor=(uni/frac)+cant;
			 if(factor==0)factor=cant;
			cant=factor;
			
			vsi=valOrig;
			iva=($('#iva_'+i).val()/100)||0;
			vi=(1+iva)*vsi;
			//cant=1;
			
			//pAnt=p*(totItems-cant);
			if(i!=(cont-1)){pAnt+=p*(cant);}
			//alert('pAnt:'+pAnt);
			//if(i!=(cont-1)){pAnt+=p*cant;alert('pAnt:'+pAnt);}
			//pAnt=redondeoFacVenta(pAnt,2);
			//alert(D-pAnt);
			//alert('I:'+i+', cont:'+cont);
			if(i==(cont-1)){
				
				p=(D-pAnt)/cant;
				p=redondeoFacVenta(p,4);
				//alert('p:'+p+', pAnt:'+pAnt);
				}
				
			dd+=p;
			
			if(vsi-p>vsi/2){ExListDctoB[i]=1;}
			else{ExListDctoB[i]=0;Tcant+=factor;}
			
		}
	}
	return Tcant;
};
function dctoB()
{
	var resta_low_pvp=0;
	var tipoComi='';
	if($('#tipo_comi').lenght!=0){tipoComi=$('#tipo_comi').val();}
	if(tipoComi=="Venta Directa" ){$('#DESCUENTO').prop('value','0');}
	var FormaPago=$('#form_pago').val();
	var $sub=$('#SUB');
	var $dcto=$('#DESCUENTO');
	var $iva=$('#IVA');
	var $tot=$('#TOTAL');
	var $exc=$('#EXCENTOS');
	
	var dcto_per=$('#DCTO_PER').val();
	var Dcto=(quitap($('#DESCUENTO2').val())*1/quitap($('#SUB').val())*1  )*100;
	if(dcto_per>lim_dcto||Dcto>lim_dcto){
		$('#DCTO_PER').prop('value',0);
		$('#DESCUENTO2').prop('value',0);
		warrn_pop("Descuento superior al permitido ("+lim_dcto+"%)!");

	}
	
	var D=quitap($('#DESCUENTO2').val());
	
	var vsi=0,vi=0,v2=0,iva=0,valOrig=0,pAnt=0,i=0,cant=0;
	var dd=0;
	var Tcant=0;
	
	var p=0;
	var totItems=scanItems();
	if(totItems!=0)p=redondeoFacVenta(D/totItems,2);
	else p=D;
	
	resta_low_pvp=val_pvp_dctoB(p);
	
	totItems=totItems-resta_low_pvp;
	if(totItems!=0)p=redondeoFacVenta(D/totItems,2);
	else p=D;
	//alert('p:'+p+' D:'+D);
	
	
	
	
	for(i=0;i<=cont;i++)
	{
		//alert('cont art:'+cont);
		if($('#val_t'+i).length!=0 &&$('#cod_serv'+i).length==0)
		{
			if(ExListDctoB[i]==1){
			valOrig=quitap($('#val_orig'+i).val())*1;
			/*if(FormaPago!='Credito'){valOrig=quitap($('#val_orig'+i).val())*1;}
			else valOrig=quitap($('#val_cre'+i+'H').val())*1;
			*/
			cant=$('#'+i+'cant_').val()*1||0;
			uni=val_ele('#unidades'+i)*1||0;
   			frac=val_ele('#fracc'+i)*1 ||1;
			//alert('frac:'+frac);
			factor=(uni/frac)+cant;
			 if(factor==0)factor=cant;
			cant=factor;
			Tcant+=factor;
			vsi=valOrig;
			iva=($('#iva_'+i).val()/100)||0;
			vi=(1+iva)*vsi;
			//cant=1;
			
			//pAnt=p*(totItems-cant);
			if(i!=(cont-1)){pAnt+=p*(cant);}
			//alert('pAnt:'+pAnt);
			//if(i!=(cont-1)){pAnt+=p*cant;alert('pAnt:'+pAnt);}
			//pAnt=redondeoFacVenta(pAnt,2);
			//alert(D-pAnt);
			//alert('I:'+i+', cont:'+cont);
			if(i==(cont-1)){
				
				p=(D-pAnt)/cant;
				p=redondeoFacVenta(p,4);
				//alert('p:'+p+', pAnt:'+pAnt);
				}
				
			dd+=p;
			//v2=vsi-p;
			if(vsi-p>vsi/3){v2=vsi-p;}
			else{v2=vsi;}
			//alert(v2);
			//v2=redondeoFacVenta(v2,2);
			//alert('#cod:'+$('#cod_serv'+i).length);
			if($('#cod_serv'+i).length==0){$('#val_u'+i).prop('value',puntob(v2));$('#dcto_'+i).prop('value','');}
			valor_t(i);
			if($('#modFV').lenght!=0&&$('#modFV').val()==1)save_fv(i);
			if($('#modREMI').lenght!=0&&$('#modREMI').val()==1)save_remi(i);
			}
			else{
				valOrig=quitap($('#val_orig'+i).val())*1;
				v2=valOrig;
			if($('#cod_serv'+i).length==0){$('#val_u'+i).prop('value',puntob(v2));$('#dcto_'+i).prop('value','');}
			valor_t(i);
			if($('#modFV').lenght!=0&&$('#modFV').val()==1)save_fv(i);
			if($('#modREMI').lenght!=0&&$('#modREMI').val()==1)save_remi(i);
			}
		}
	}
	dd=redondeo(dd);
	
	//tot();
	//alert(dd);
};
function scanItems()
{
	var cant=0,totItems=0,uni=0,frac=0,factor=0;
	for(i=0;i<=cont;i++)
	{
		//alert('cont art:'+cont);
		if($('#val_t'+i).length!=0)
		{
			cant=$('#'+i+'cant_').val()*1||0;
			uni=val_ele('#unidades'+i)*1||0;
   			frac=val_ele('#fracc'+i)*1 ||1;
			//alert('frac:'+frac);
			factor=(uni/frac)+cant;
			 if(factor==0)factor=cant;
			 //factor=cant;
			if($('#cod_serv'+i).length==0)totItems+=factor;
		}
	}
	return totItems;
};


function save_vehi()
{
	var datos=$('.VEHI').serialize();
	//alert(datos);
	var placa=$('#placa_ve').val();
	//alert(placa);
	ajax_b('ajax/add_vehi.php',datos,function(r){
		
		if(r==2){
			alert('ESTA PLACA YA ESTA REGISTRADA');
			$('#placa').prop('value',placa);
			remove_pop($('#OT_VEHI'));
			}
		else if(r==1){
			alert('REGISTRO EXITOSO');
			$('#placa').prop('value',placa);
			remove_pop($('#OT_VEHI'));
			}
		else if(r==0){alert('COMPLETE TODOS LOS CAMPOS');}
		else {alert(r);}
		});
};
function val_placa($placa)
{
	var modal = $.UIkit.modal("#OT_VEHI");
	var datos='placa='+$placa.val();
	ajax_b('ajax/val_placa.php',datos,function(r){
		var resp=r.split("|");
		if(resp[0]==1){alert('PLACA CONFIRMADA');$('#ced').prop('value',resp[1]).change();}
		else if(resp[0]==0){
		alert('Placa no Registrada...');
		modal.show();
		}
		});
};
function serv($selc,Fname,EliF)
{
	var serv=$selc.val();
	var r=serv.split('|');
	var html='<tr id="tr_'+cont+'" class="art'+cont+'">';
	var vsi=r[4];
	//alert(r[4]);
	
	if( $('#tipo_cli').val()=='Empresas' && r[3]!=0)
			   {
				vsi=redondeo2(quitap(r[4])*1.19);   
			   }
	
	
	// ID SERV
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' col_id_serv"  value="'+r[0]+'" type="text" id="id_serv'+cont+'" name="id_serv'+cont+'" placeholder=""   onChange="'+Fname+'('+cont+')"/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_" type="hidden" name="cant_'+cont+'" size="5"   value="'+1+'" style=" width:50px" readonly=""/><input class="uk-form-small art'+cont+'" id="'+cont+'cant_L" type="hidden" name="cant_L'+cont+'" size="5" maxlength="5" value="'+100+'" style=" width:50px"/><!--<div class="uk-button-group"><a class="uk-button" onClick="restaCant($(\'#'+cont+'cant_\'));"><i class="uk-icon uk-icon-minus-square"  ></i> </a><a class="uk-button" onClick="sumaCant($(\'#'+cont+'cant_\'));"> <i class="uk-icon uk-icon-plus-square"></i> </a></div>--></td>';
	
	// COD SERV
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' col_cod_serv"  value="'+r[1]+'" type="text" id="cod_serv'+cont+'" name="cod_serv'+cont+'" placeholder=""   onChange="'+Fname+'('+cont+')" readonly=""/></td>';
	
		//  SERV
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' col_serv"  value="'+r[2]+'" type="text" id="serv'+cont+'" name="serv'+cont+'" placeholder=""   onChange="" readonly=""/></td>';
	
	if(modulo_planes_internet!=1){
		
		
	//  NOTA
	html+='<td class="art'+cont+'"><textarea style=" width:170px" cols="10" rows="1" class="art'+cont+' col_nota_serv" name="nota'+cont+'" id="nota'+cont+'" onBlur="'+Fname+'('+cont+');"></textarea></td>';
	
	
	
	
		// IVA
	if(kardex==1 &&  mod_ivas_facs==0)html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' col_iva_serv"  value="'+r[3]+'" type="text" id="iva_'+cont+'" name="iva_serv'+cont+'" placeholder=""    onChange="'+Fname+'('+cont+')" readonly=""/></td>';
			   
else html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' col_iva_serv"  value="'+r[3]+'" type="text" id="iva_'+cont+'" name="iva_serv'+cont+'" placeholder="" onkeyup="javascript:valor_t('+cont+');"  onChange="'+Fname+'('+cont+')"/></td>';
				   
				   
	
	
	
	//descuento
//html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+0+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';
	
	//valor unitario/val Min
html+='<td class="art'+cont+'" ><input class="uk-form-small art'+cont+' col_pvp_serv" id="val_u'+cont+'" name="val_serv'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(vsi)+'\"  onBlur="change16('+cont+');valMin($(this),'+r[3]+','+vsi+','+cont+');'+Fname+'('+cont+');"  /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[4]+'" /></td>';
			   // val sub_tot
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+' col_sub_tot_serv" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
// TECNICO_SERV tecOpt()
html+='<td class="art'+cont+'" colspan="2"><select class="art'+cont+' col_tec_serv" id="tec_serv'+cont+'" name="tec_serv'+cont+'"    onChange="'+Fname+'('+cont+')">'+tecOpt+'</select></td>';

	}
	
	else{
		
 
	

	
	// velocidad Plan
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' "  value="" type="text" id="anchobanda'+cont+'" name="anchobanda'+cont+'" placeholder="Ancho de Banda"   onChange="'+Fname+'('+cont+')"  /></td>';
	
	// tipo cliente
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' "  value="" type="text" id="tipo_cliente'+cont+'" name="tipo_cliente'+cont+'" placeholder="Tipo Cliente"   onChange="'+Fname+'('+cont+')"  /></td>';
	
		// estrato plan
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' "  value="" type="text" id="estratoPlan'+cont+'" name="estratoPlan'+cont+'" placeholder="Estrato"   onChange="'+Fname+'('+cont+')"  /></td>';
	
			// pvp Plan
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' "  value="" type="text" id="nota'+cont+'" name="nota'+cont+'" placeholder="Precio Plan"   nBlur="'+Fname+'('+cont+');" onkeyup="puntoa($(this));" /></td>';
	
		// IVA
	html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+' col_iva_serv"  value="'+r[3]+'" type="text" id="iva_'+cont+'" name="iva_serv'+cont+'" placeholder=""   onChange="'+Fname+'('+cont+')" readonly=""/></td>';
	
	
		//descuento
html+='<td class="art'+cont+'" align="center"><input class="uk-form-small art'+cont+'" id="dcto_'+cont+'" type="text" name="dcto_'+cont+'" size="5" maxlength="5" value=\"'+0+'\" onkeyup="valor_t('+cont+');" onblur="dct('+cont+');valMin($(\'#val_u'+cont+'\'));" style=" width:50px" /><input class="uk-form-small art'+cont+'" id="dcto_cli'+cont+'" name="dcto_cli'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="tipo_dcto'+cont+'" name="tipo_dcto'+cont+'" type="hidden"  value="0" /></td>';
	
	//valor unitario/val Min
html+='<td class="art'+cont+'" ><input class="uk-form-small art'+cont+' col_pvp_serv" id="val_u'+cont+'" name="val_serv'+cont+'" type="text" onkeyup="puntoa($(this));keepVal('+cont+');valor_t('+cont+');" value=\"'+puntob(vsi)+'\"  onBlur="change16('+cont+');valMin($(this),'+r[3]+','+vsi+','+cont+');'+Fname+'('+cont+');"  /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden"  value="0" /><input class="uk-form-small art'+cont+'" id="valMin'+cont+'" type="hidden" name="valMin'+cont+'" size="5" maxlength="5" value="'+r[6]+'" style=" width:30px"/><input class="uk-form-small art'+cont+'" id="val_orig'+cont+'" name="val_orig'+cont+'" type="hidden"  value=\"'+puntob(parseFloat(vsi))+'\" /><input class="uk-form-small art'+cont+'" id="val_origb'+cont+'" name="val_origb'+cont+'" type="hidden"  value=\"'+parseFloat(r[4])+'\" /><input class="uk-form-small art'+cont+'" id="val_cre'+cont+'H" name="val_cre'+cont+'H" type="hidden" readonly="" value="'+r[4]+'" /></td>';

			   // val sub_tot
			   html+='<td class="art'+cont+'"><input class="valorProducto art'+cont+' col_sub_tot_serv" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" style=" "/><input class="uk-form-small art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="uk-form-small art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td>';
			   
	}
	
	// ELI
	 html+='<td class="art'+cont+'"><img onMouseUp="'+EliF+'($(this).prop(\'class\'))" class="'+cont+'" src="Imagenes/delete.png" width="31px" heigth="31px" ></td></tr>';
	 
	 var $ar=$(html);
               $ar.appendTo('#servicios');
			   valor_t(cont);
			   //alert($('#modFV').val());
			   if($('#modFV').lenght!=0&&$('#modFV').val()==1)save_fv(cont);
			   if($('#modREMI').lenght!=0&&$('#modREMI').val()==1)save_remi(cont);
			   cont++;
			
			   flag_reSearch=0;
			   ref_exis++;
			   
			  
		       $('#num_ref').prop('value',cont);
		       $('#exi_ref').prop('value',ref_exis);
	
};
function keepVal(i)
{
	var FormaPago=val_ele('#form_pago');
	var $val_u=$('#val_u'+i);
	var $val_orig=$('#val_orig'+i);
	
	var vu=quitap($val_u.val())*1;
	$val_orig.prop('value',vu);
	
	
	
	if(FormaPago=='Credito')$('#val_cre'+i+'H').prop('value',vu);
};
function change16(i)
{
	
	var FormaPago=val_ele('#form_pago');
	var $val_u=$('#val_u'+i);
	var val_u=quitap($val_u.val())*1.19;
	var $val_orig=$('#val_origb'+i);
	var vu=quitap($val_orig.val())*1;
	//alert(vu+' if:'+(vu==0));
	if(vu==0){$val_orig=$('#val_orig'+i);}
	
	vu=quitap($val_orig.val())*1;
	
	
	
	//alert(vu);
	vsi=redondeo2(quitap(vu)*1.19); 
	//alert((val_u<vsi)+' vu:'+val_u+' vsi:'+vsi);
	//alert(val_u<vsi+' vu:'+val_u+' vsi:'+vsi);
	if($('#tipo_cli').val()=='Empresas' && $('#iva_'+i).val()!=0 && val_u<vsi)
			   {
				
				$('#val_orig'+i).prop('value',puntob(vsi));
				$val_u.prop('value',puntob(vsi));
				valor_t(i);
			   }
};
function gfd(frm)
{
	
	if(flag_gfv==0){
	if(esVacio(frm.fecha.value) || frm.fecha.value=='0000-00-00'){warrn_pop('Ingrese la fecha');focusRed($(frm.fecha));}
	
	else if((esVacio(frm.ced.value)||esVacio(frm.cli.value) ))
	{warrn_pop('Registre el Cliente');
	if(esVacio(frm.cli.value))focusRed($('#cli'));
	else if(esVacio(frm.ced.value))focusRed($('#ced'));
	
	}
	else if(esVacio(frm.exi_ref.value)|| frm.exi_ref.value<=0){warrn_pop('Debe cargar articulos !');$('#cod').focus();}
	//else if(1){}
	else {
		flag_gfv=1;
		
		$('#butt_gfv').off( 'click' );
		
		var data=$("#form-fac").serialize();
		ajax_x('ajax/FORMS/insert_fac_dev.php',data,function(resp){
			
			if(resp==1){
				//print_pop('imp_fac_ven_cre.php','DEVOLUCION',900,650);
				alert("Guardado con Exito");
				location.assign("fac_dev_ven.php");
				
				}else{warrn_pop("RespServer:"+resp);}

			
			});
		
	}
	
	
	}
};
function banDcto()
{
	
	if(BAN_DCTO_CRE==1){
	alert("BAN_DCTO_CRE");	
	var FormaPago=$('#form_pago').val();
if(FormaPago=='Credito')
{


for(i=0;i<=cont;i++){
		//alert('cont art:'+cont);
		var vsi=0,cant=0,uni=0,frac=0,factor=0;
		
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val()+', Orig:'+$('#val_orig'+i).val());
			$vst=$('#val_t'+i);
			valOrig=quitap($('#val_origb'+i).val())*1;
			vsi=quitap($('#val_u'+i).val());
			//alert(vsi+' Orig:'+valOrig);
            if(vsi<valOrig){$('#val_u'+i).prop('value',puntob(valOrig));}
			
			$('#dcto_'+i).prop('value','');
			valor_t(i);
			
		//alert(vi+',iva: '+iva+', vsi: '+vsi+', excentos: '+EXC);
		
			cant=$('#'+i+'cant_').val()*1||0;
			uni=$('#unidades'+i).val()*1||0;
   			frac=$('#fracc'+i).val()*1||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			vsi=(quitap($('#val_u'+i).val())*1)||0;
			
			//$vst.prop('value',puntob(redondeo(vsi*factor)));
			 if(($('#remision').lenght!=0 && $('#modFV').lenght!=0) && $('#remision').val()==1  && $('#modFV').val()==1 )save_fv(i);
			 else  if( ($('#remision').lenght!=0 && $('#modREMI').lenght!=0) && $('#remision').val()==1  && $('#modREMI').val()==1 )save_remi(i);
		 
			//alert('vsi:'+vsi);
		
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
}
$('#DCTO_PER').prop('value',0);
$('#DESCUENTO2').prop('value',0);
//dctoB();

	}//  Deny Dcto Credito
	
	}

};
function formulaGananMayoris(costo,porcentaje){
	var Pvpfinal=1;
	var per = (porcentaje/100);
	
	Pvpfinal = costo +(costo*per);
	Pvpfinal = costo/(1-per);
	
	
	return Pvpfinal;
	
}
function set_mayorista2(){
	
	//alert("ganancia_ventas_mayorista:"+ganancia_ventas_mayorista);
	if(ganancia_ventas_mayorista2==0){
	//alert("pvpCre");	
	var FormaPago=$('#form_pago').val();

for(i=0;i<=cont;i++){
		//alert('cont art:'+cont);
		var vsi=0,cant=0,uni=0,frac=0,factor=0;
		
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val()+', Orig:'+$('#val_orig'+i).val());
			$vst=$('#val_t'+i);
			

			valOrig=quitap($('#val_orig'+i).val())/(1+(per_mayo/100));
			valOrig=redondeo2(valOrig);
			$('#val_u'+i).prop('value',puntob(valOrig));
           	
		//alert(vi+',iva: '+iva+', vsi: '+vsi+', excentos: '+EXC);
		
			cant=$('#'+i+'cant_').val()*1||0;
			uni=$('#unidades'+i).val()*1||0;
   			frac=$('#fracc'+i).val()*1||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			vsi=(quitap($('#val_u'+i).val())*1)||0;
			
			$('#dcto_'+i).prop("value",per_mayo).blur();
			
			//$vst.prop('value',puntob(redondeo(vsi*factor)));
			 if(($('#remision').lenght!=0 && $('#modFV').lenght!=0) && $('#remision').val()==1  && $('#modFV').val()==1 )save_fv(i);
			 else  if( ($('#remision').lenght!=0 && $('#modREMI').lenght!=0) && $('#remision').val()==1  && $('#modREMI').val()==1 )save_remi(i);
		 
			//alert('vsi:'+vsi);
		
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
}

//alert('Precios Cambiados!');
tot();
	}// allow decuento mayorista normal
	else{
		

	//alert("mayorista 2");	
	var FormaPago=$('#form_pago').val();

for(i=0;i<=cont;i++){
		//alert('cont art:'+cont);
		var vsi=0,cant=0,uni=0,frac=0,factor=0;
		
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val()+', Orig:'+$('#val_orig'+i).val());
			$vst=$('#val_t'+i);
			

			valOrig=formulaGananMayoris(quitap($('#costo'+i+'H').val()),ganancia_ventas_mayorista2);
			//alert($('#costo'+i+'H').val()+', '+valOrig);
			valOrig=redondeo_centenas(valOrig);
			$('#val_u'+i).prop('value',puntob(valOrig));
			$('#val_orig'+i).prop('value',puntob(valOrig));
           	
		//alert(vi+',iva: '+iva+', vsi: '+vsi+', excentos: '+EXC);
		
			cant=$('#'+i+'cant_').val()*1||0;
			uni=$('#unidades'+i).val()*1||0;
   			frac=$('#fracc'+i).val()*1||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			vsi=(quitap($('#val_u'+i).val())*1)||0;
			
			valor_t(i);
			
			//$('#dcto_'+i).prop("value",per_mayo).blur();
			
			//$vst.prop('value',puntob(redondeo(vsi*factor)));
			 if(($('#remision').lenght!=0 && $('#modFV').lenght!=0) && $('#remision').val()==1  && $('#modFV').val()==1 )save_fv(i);
			 else  if( ($('#remision').lenght!=0 && $('#modREMI').lenght!=0) && $('#remision').val()==1  && $('#modREMI').val()==1 )save_remi(i);
		 
			//alert('vsi:'+vsi);
		
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
}

//alert('Precios Cambiados!');
//tot();
	
		
	}

	
	

};
function set_mayorista(){
	//alert("ganancia_ventas_mayorista:"+ganancia_ventas_mayorista);
	if(ganancia_ventas_mayorista==0){
	//alert("pvpCre");	
	var FormaPago=$('#form_pago').val();

for(i=0;i<=cont;i++){
		//alert('cont art:'+cont);
		var vsi=0,cant=0,uni=0,frac=0,factor=0;
		
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val()+', Orig:'+$('#val_orig'+i).val());
			$vst=$('#val_t'+i);
			

			valOrig=quitap($('#val_orig'+i).val())/(1+(per_mayo/100));
			valOrig=redondeo2(valOrig);
			$('#val_u'+i).prop('value',puntob(valOrig));
           	
		//alert(vi+',iva: '+iva+', vsi: '+vsi+', excentos: '+EXC);
		
			cant=$('#'+i+'cant_').val()*1||0;
			uni=$('#unidades'+i).val()*1||0;
   			frac=$('#fracc'+i).val()*1||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			vsi=(quitap($('#val_u'+i).val())*1)||0;
			
			$('#dcto_'+i).prop("value",per_mayo).blur();
			
			//$vst.prop('value',puntob(redondeo(vsi*factor)));
			 if(($('#remision').lenght!=0 && $('#modFV').lenght!=0) && $('#remision').val()==1  && $('#modFV').val()==1 )save_fv(i);
			 else  if( ($('#remision').lenght!=0 && $('#modREMI').lenght!=0) && $('#remision').val()==1  && $('#modREMI').val()==1 )save_remi(i);
		 
			//alert('vsi:'+vsi);
		
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
}

//alert('Precios Cambiados!');
tot();
	}// allow decuento mayorista normal
	else{
		

	//alert("mayorista 2");	
	var FormaPago=$('#form_pago').val();

for(i=0;i<=cont;i++){
		//alert('cont art:'+cont);
		var vsi=0,cant=0,uni=0,frac=0,factor=0;
		
		if($('#val_t'+i).length!=0)
		{
			//alert(FormaPago+', pvp Cre:'+$('#val_cre'+i+'H').val()+', Orig:'+$('#val_orig'+i).val());
			$vst=$('#val_t'+i);
			

			valOrig=formulaGananMayoris(quitap($('#costo'+i+'H').val()),ganancia_ventas_mayorista);
			//alert($('#costo'+i+'H').val()+', '+valOrig);
			valOrig=redondeo_centenas(valOrig);
			$('#val_u'+i).prop('value',puntob(valOrig));
			$('#val_orig'+i).prop('value',puntob(valOrig));
           	
		//alert(vi+',iva: '+iva+', vsi: '+vsi+', excentos: '+EXC);
		
			cant=$('#'+i+'cant_').val()*1||0;
			uni=$('#unidades'+i).val()*1||0;
   			frac=$('#fracc'+i).val()*1||1;
			factor=(uni/frac)+cant;
			if(factor==0)factor=cant;
			vsi=(quitap($('#val_u'+i).val())*1)||0;
			
			valor_t(i);
			
			//$('#dcto_'+i).prop("value",per_mayo).blur();
			
			//$vst.prop('value',puntob(redondeo(vsi*factor)));
			 if(($('#remision').lenght!=0 && $('#modFV').lenght!=0) && $('#remision').val()==1  && $('#modFV').val()==1 )save_fv(i);
			 else  if( ($('#remision').lenght!=0 && $('#modREMI').lenght!=0) && $('#remision').val()==1  && $('#modREMI').val()==1 )save_remi(i);
		 
			//alert('vsi:'+vsi);
		
			
			//alert('i:'+i+',SUB:'+SUBi);
			//alert('i:'+i+',DCTO:'+DCTOi+'$():'+$('#dcto_'+i).val()+', valOrig:'+valOrig);
		}
}

//alert('Precios Cambiados!');
//tot();
	
		
	}

	
	
};
function save_fac_ven()
{
	
	tot();
	
	getPage($('#pagHTML'),$('#factura_venta'));
	//getPage($('#HTML_despues'),$('#fac_com'));
	var externals=$('#form-fac');
	var ExtString=externals.serialize();
	var Datos=ExtString ;
	var tipoResol = $('#tipo_resol').val();
	 
	if(1){
	$.ajax({
		url:'ajax/save_fac_ven.php',
		data:Datos,
		type:'POST',
		dataType:'JSON',
		success:function(response){

				if(response.successCode==1){
					if(autoSendFE==1) {
						SEND_facElec(response.numFactura,response.prefijoFac,'',response.codSuc,response.serialFac,response.mailTo,response.idCliente);
						//waitAndReload();
					}
					else {
						location.assign("fac_venta.php?resp=imp");
					}
				}
				else if(response.successCode==2){
					location.assign("fac_venta.php?resp=mod");	
				}else{
						warrn_pop('ERROR, intentelo mas tarde. Si el error persiste, contacte a soporte y envie una foto del error:'+text);
						flag_gfv=0;
					}
					
					/*$('#butt_gfv').prop( 'disabled',false );
					$('#entrega').prop( 'disabled',false );
					$('#entrega2').prop( 'disabled',false );
					$('#entrega3').prop( 'disabled',false );*/


		},
		error:function(xhr,status){
			warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');
			/*$('#butt_gfv').prop( 'disabled',false );
					$('#entrega').prop( 'disabled',false );
					$('#entrega2').prop( 'disabled',false );
					$('#entrega3').prop( 'disabled',false );*/
			}
		
		});
		
	}// fin no vacio
};
function save_fac_remi()
{
	tot();
	getPage($('#HTML_despues'),$('#fac_com'));
	var externals=$('#form-fac');
	var ExtString=externals.serialize();
	var Datos=ExtString ;
	 
	if(1){
	$.ajax({
		url:'ajax/save_fac_remi.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			//alert(text);
			if(!esVacio(text)){
				var resp=text*1;
				
				 
				if(resp==1){
				location.assign("fac_remi.php?resp=imp&tipoFAC="+val_ele('#tipoFAC'));
					
				}else if(resp==2){location.assign("fac_remi.php?resp=mod&tipoFAC="+val_ele('#tipoFAC'));
					}else{alert('ERROR, intentelo mas tarde');}
				}

		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
		
	}// fin no vacio
};


function load_cli_sala(n)
	{
		var url='ajax/add_usu_ven.php';
		var data='ced='+n.value;
		$.ajax({
		url:'ajax/add_usu_ven.php',
		data:{ced:n.value},
	    type: 'POST',
		dataType:'JSON',
		success:function(response){
			//alert('Encontrado!:'+text);
			var resp=response[0].respuesta*1;
			//resp=resp.replace(/\+/g," ");
			if(resp!=0)
			{
 
			 if($('#nom_lookup').length!=0){$('#nom_lookup').prop('value',response[0].nombreCompleto);}
			 $('#nombres').prop('value',response[0].nombreCompleto);	
			 $('#direccion').prop('value',response[0].direccion);
			 $('#tel_cli').prop('value',response[0].telefono);
			 $('#ciudad_cli').prop('value',response[0].ciudad);
			 $('#email_cli').prop('value',response[0].email);
			 $('#id_cli').prop('value',response[0].cc);

			
			}
		}
			
			,
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
		
	};
	
function check_motor(val,cont){
	 
if(!event.which && ((event.charCode || event.charCode === 0) ? event.charCode: event.keyCode))
{event.which = event.charCode || event.keyCode;}
key=event.which;	
//alert('cod '+n.value);
if(!esVacio(val))
{
// $n.bind('keyup',function(e){
if(key==13 ){
	
	
	}

	//});
	
	}
	
	else {
		
		
		
		//if(key==13&&(op=='add'||op=='dev')){busq_all($n);}
		}
};
function sumaCant($cant)
{
	var cant=$cant.val()*1;
	$cant.prop('value',cant+1).blur();
	
	
};
function restaCant($cant)
{
	var cant=$cant.val()*1;
	$cant.prop('value',cant-1).blur();
	
};
function save_cli($form)
{
	

	var externals=$form;
	var ExtString=externals.serialize();
	var Datos=ExtString;
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	$.ajax({
		url:'ajax/reg_cli.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			var c=text*1;
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(c==1)
			{
			alert('Ha sido Registrado con Exito');
			
		$("#modal").on({

    'show.uk.modal': function(){
       
    },

    'hide.uk.modal': function(){
        
		//alert('del pop');
    }
});
			
			
			}
			else if(c==0)
			{
				open_pop('ERROR','Ha ingresado datos no v&aacute;lidos','Por Favor Intentelo de nuevo');
				
			}
			else if(c==2)
			{
				open_pop('ERROR','Faltan Datos','Por Favor Llene todos los campos');
				
			};
			//else alert('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});

};