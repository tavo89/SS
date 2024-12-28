function validar_2chk(campo,campo2, tab, col,col2, Div,$check) {//alert(campo+'[1], '+campo2+'[2],'+tab+' '+col+'[1],'+col2+'[2]  DIV:'+Div);
if(!esVacio(campo)&&!esVacio(campo2)){
   $.ajax( {
      url : 'ajax/val_2cOR.php', 
      data : {
         campo : $.trim(campo),campo2:$.trim(campo2), tabla : tab, colum : col,colum2 : col2}
      , 
	  type : 'POST', 
	  dataType : 'text',
	  success : function(text) {
		 // alert(text);
         var l = text*1; 
		 if(l == 0) {
            Div.css('visibility', 'hidden'); 
			$check.attr('value','ok');
			}
         else {
            Div.css('visibility','visible');
			$check.prop('value','ko');
			 }
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }
   ); 
   
}

else {Div.css('visibility', 'hidden'); 
			$('#verify').prop('value','ok');}
   };
 

function lpm($c)
{
	$c.css({'-webkit-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','-moz-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','box-shadow':'box-shadow:rgb(255, 0, 0) 0px 0px 40px -12px inset;' }).focus();
	
};
function lpm2($c)
{
	$c.css({'-webkit-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','-moz-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','box-shadow':'box-shadow:rgb(255, 0, 0) 0px 0px 40px -12px inset;' });
	
};
function validar_c($campo,tab, col,msj) {
	//alert($campo.val()+'[1], '+tab+' '+col+'[1]');
if(!esVacio($campo.val())){
   $.ajax( {
      url : 'ajax/val_c.php', 
      data : {
         campo : $campo.val(),tabla : tab, colum : col},
		 type : 'POST', 
		 dataType : 'text', 
		 success : function(text) {
         var l = text*1; 
		 //alert(text);
		 if(l == 0) {
            $campo.removeAttr('style');
			
			}
         else {
			 alert(msj);
            lpm($campo);
			//$campo.prop('class','campo_error');
			//$campo.focus();
			 }
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }
   ); 
   
}

else {
	  $campo.removeAttr('style');
	}
   };
function save_inv()
{
	$('#boton').prop('value','Guardar');
	var $form=$('#addpro');
	var Datos=$form.serialize();
	//alert(''+Datos);
	var modal = UIkit.modal.blockUI("Guardando informacion...");
	$.ajax({
     type: 'POST',
     url: 'ajax/insert_inv.php',
     data: Datos,
     success: function(resp){
		var r=resp*1;
		setTimeout(function()
		{
			modal.hide();
			if(r==2) {open_pop('ERROR','Producto REPETIDO','');}
			else if(r==1){
				
			open_pop('Guardado','Producto Registrado con &Eacute;xito','');
			
			$("#modal").on({
	
		'show.uk.modal': function(){
		   
		},
	
		'hide.uk.modal': function(){
			location.assign("agregar_producto.php");
			//alert('del pop');
		}
	});
			
			}
		},2500);
		 

		
    }
 });
};
function guardarProducto(btn,val,form)
{
	
	//alert('PROMEDIO  VAL:'+form.id_producto.value);
	//alert('Costo:'+form.cost.value+(form.cost.value<=0)+', Gan:'+form.gana.value+', PVP:'+form.pvp.value+'');
	getPage($('#HTML_Pag'),$('#crea_inv'));
	if(esVacio(form.id_producto.value)){alert('Ingrese el Código del Producto');form.id_producto.focus();}
	//else if(form.verify.value=='ko'){alert('El Código NO es válido!!');form.id_producto.focus();}
	else if(form.verify2.value=='ko'){alert('El Código Interno NO es válido!!');form.id_inter.focus();}
	else if(esVacio(form.id_inter.value)){alert('Ingrese el Código interno');form.id_inter.focus();}
	else if(esVacio(form.des.value)){alert('Ingrese la Descripción del Producto');form.des.focus();}
	//else if(esVacio(form.clase.value)){alert('Ingrese la Clase/Tipo del Producto');form.clas.focus();}
	//else if(esVacio(form.cost.value)||form.cost.value<=0){alert('Ingrese el Costo');form.cost.focus();}
	//else if(esVacio(form.ganancia.value)||form.ganancia.value<=0){alert('Ingrese la ganancia');form.descuento.focus();}
	//else if(esVacio(form.pvp.value)||form.pvp.value<=0){alert('Ingrese El Precio de Venta');form.pvp.focus();}
	//else if(esVacio(form.iva.value)){alert('Seleccione el IVA');form.iva.focus();}
	else{
	
	//form.submit();
	
	save_inv();
	
	
	}
	//resetForm($form);
	
	//alert('!!');
};
function valida_inv($campo,$campo2,tab, col,col2,msj)
{
	//alert('val_inv');
	if(!esVacio($campo.val())){
   $.ajax( {
      url : 'ajax/val_inv.php', 
      data : {
         campo : $campo.val(),campo2 : $campo2.val(),tabla : tab, colum : col, colum2 : col2},
		 type : 'POST', 
		 dataType : 'text', 
		 success : function(text) {
         var l = text*1; 
		 //alert(text);
		 if(l == 0) {
            $campo.removeAttr('style');
			
			}
		else if(l == "10--") {
			if(confirm('Este Codigo Ya existe en otra SEDE, Desea para copiar todos los datos a esta sede??')){save_inv();}
		}
         else {
			 alert(msj);
            lpm($campo);
			//$campo.prop('class','campo_error');
			//$campo.focus();
			 }
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }
   ); 
   
}

else {
	  $campo.removeAttr('style');
	}
	
};   
function validar_c2($campo,$campo2,tab, col,col2,msj) {
	//alert($campo.val()+'[1], '+tab+' '+col+'[1]');
if(!esVacio($campo.val())){
   $.ajax( {
      url : 'ajax/val_c.php', 
      data : {
         campo : $campo.val(),tabla : tab, colum : col},
		 type : 'POST', 
		 dataType : 'text', 
		 success : function(text) {
         var l = text*1; 
		 //alert(text);
		 if(l == 0) {
            $campo.removeAttr('style');
			
			}
         else {
			 alert(msj);
            lpm($campo);
			//$campo.prop('class','campo_error');
			//$campo.focus();
			 }
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }
   ); 
   
}

else {
	  $campo.removeAttr('style');
	}
   }; 
 
function fabricante(name,id,clase,IDbox) {
	//alert(name+', '+id+', '+clase+'');

   $.ajax( {
      url : 'ajax/fab.php', 
      data : {name:name,id:id,clase:clase},
		 type : 'POST', 
		 dataType : 'text', 
		 success : function(text) {
         
		// alert(text);
		
           var $rs=$(text);
			$rs.appendTo('#'+IDbox);
			
			
			
       
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }); 
 
   };
function validar_2c(campo,campo2, tab, col,col2, Div) {//alert(campo+'[1], '+campo2+'[2],'+tab+' '+col+'[1],'+col2+'[2]  DIV:'+Div);
if(!esVacio(campo)&&!esVacio(campo2)){
   $.ajax( {
      url : 'ajax/val_2c.php', 
      data : {
         campo : campo,campo2:campo2, tabla : tab, colum : col,colum2 : col2}
      , type : 'POST', dataType : 'text', success : function(text) {
         var l = text*1; 
		 if(l == 0) {
            Div.css('visibility', 'hidden'); 
			$('#verify').prop('value','ok');
			}
         else {
            Div.css('visibility','visible');
			$('#verify').prop('value','ko');
			 }
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }
   ); 
   
}

else {Div.css('visibility', 'hidden'); 
			$('#verify').prop('value','ok');}
   };
function validar_campo(campo, tab, col, Div) {//alert(campo.value+' '+tab+' '+col+' '+Div);
if(!esVacio(campo.value)){
   $.ajax( {
      url : 'ajax/val_rep.php', 
      data : {
         campo : campo.value, tabla : tab, colum : col}
      , type : 'POST', dataType : 'text', success : function(text) {
         var l = text*1; 
		 if(l == 0) {
            Div.css('visibility', 'hidden'); 
			$('#verify').prop('value','ok');
			}
         else {
            Div.css('visibility','visible');
			$('#verify').prop('value','ko');
			 }
         }
      , error : function(xhr, status) {
         //alert('Ups! Ha ocurrido un error..'); alert('xhr:' + xhr); alert('status:' + status); 
		 }
      }
   ); 
   
}

else {Div.css('visibility', 'hidden'); 
			$('#verify').prop('value','ok');}
   };
function redondeo(numero)
{
var original=parseFloat(numero);
//var result=Math.round(original*0.01)/0.01;
var result=Math.round(original);
//alert('redondeo:'+result);
return result;
};
function redondeo2(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*0.1)/0.1;
//var result=Math.round(original);
//alert('redondeo:'+result);
return result;
};
function redondeo3(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*0.01)/0.01 ;
//alert('redondeo:'+result);
return result;
};
function nan($obj)
{
 //var obj=quitap($obj.val());
 //alert(obj);
 if(!esVacio($obj.val())){
 if(isNaN(quitap($obj.val()))){alert('Ingrese un Numero!');}
 }
};
function nanC($obj)
{
 //alert('nanC:'+$obj.val());
 if(quitap($obj.val())<=0){alert('Ingrese un Numero Mayor a Cero!');}
 if(isNaN(quitap($obj.val()) ) ){alert('Ingrese un Numero!');}

};

function resetCss(ele)
{
  $(ele).removeAttr('style');
};
function click_ele(ele)
{
	$(ele).css('background-color', '#FF0');
};
function save_reg($form)
{
	getPage($('#pagHTML'),$('#registro_cliente'));
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
			open_pop('Guardado','Ha sido Registrado con &Eacute;xito','');
			
		$("#modal").on({

    'show.uk.modal': function(){
       
    },

    'hide.uk.modal': function(){
        location.assign('registro_cli.php');
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
		}
		
		});
};
function save_form($form,ajaxURL,backURL)
{
	
	var externals=$form;
	var ExtString=externals.serialize();
	var Datos=ExtString;
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	$.ajax({
		url:'ajax/'+ajaxURL,
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			var c=text*1;
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(c==1)
			{
			open_pop('Guardado','Ha sido Registrado con &Eacute;xito','');	
			$('#popupDialog').bind({
  			 popupafterclose: function() { location.assign(backURL); }
									});	
			}
			else if(c==0)
			{
				open_pop('ERROR','Ha ingresado datos no v&aacute;lidos','Por Favor Intentelo de nuevo');
				$('#popupDialog').bind({
  			 popupafterclose: function() { $(this).remove(); }
									});
			}
			else if(c==2)
			{
				open_pop('ERROR','Faltan Datos','Por Favor Llene todos los campos');
				$('#popupDialog').bind({
  			 popupafterclose: function() { $(this).remove(); }
									});
			};
			//else alert('Actualizado');
		}
		
		});
};
function mod_cli($form)
{
	getPage($('#HTML_despues'),$('#mod_cli'));
	var externals=$form;
	var ExtString=externals.serialize();
	var Datos=ExtString;
	//alert(Datos);
	//$('.loader');
	//alert('ref H:'+$('#ref'+c).offset()+', ref H:'+$('#ref'+c).offset());
	
	$.ajax({
		url:'ajax/mod_cli.php',
		data:Datos,
		type:'POST',
		dataType:'text',
		success:function(text){
			var c=text*1;
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(c==1)
			{
			open_pop('Guardado','Los datos han sido Actualizados','');	
			
				
			}
			else if(c==0)
			{
				open_pop('ERROR','Ha ingresado datos no v&aacute;lidos','Por Favor Intentelo de nuevo');
				
				
			}
			else if(c==2)
			{
				open_pop('ERROR','Faltan Datos','Por Favor Llene todos los campos');
				
				
			}
			else {
				open_pop('ERROR','Su conexion FALLO',''+text);
				
				}
			//else alert('Actualizado');
		}
		
		});
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
function cant_dcto_com($tipoD,$dcto,$cant)
{
  
   var dcto=$dcto.val()*1;
   var tipo_dcto=$tipoD.val();
   var c=$cant.val()*1;
   var per=1+(dcto/100);
   
   var cantD=(c*per);
   //alert('dctoCli:'+$dcto_cli+',cant:'+c+',%:'+per+'result:'+cantD);
   if(tipo_dcto=='PRODUCTO'){$cant.prop('value',cantD);alert('Las cantidades han sido ajustadas '+c+'-->'+cantD);};
  // tot();
};
function cto($tipoD,$dcto,$costo,$pvp)
{	

	//alert($tipoD.val()+'|'+$dcto.val()+'|'+$costo.val()+'|'+$pvp.val())
	var dcto=0,c=quitap($costo.val())*1,p=quitap($pvp.val())*1;
	var dcto2=0;
	if($('#descuento2').length!=0)dcto2=($('#descuento2').val()/100)||0;
	//var c=0,p=0;
	//alert('p:'+p+', c:'+c);
	//alert($tipoD.val());
	
		if($tipoD.val()=="PRODUCTO")
		{
			dcto=d($dcto.val())/100;
			c=p-p*dcto;
			
						
		}
		else 
		{
			dcto=$dcto.val()/100;
			c=p-p*dcto;
		}
		//alert(c);
		c=c - c*dcto2;
		//alert(c);
	$costo.prop('value',puntob(c));
	
	};
function save_permiso(id_usu,id_secc,per)
{
	//alert('Usu:'+id_usu+', id Secc:'+id_secc+', Permiso: '+per);
	
	$.ajax({
		url:'ajax/mod_per.php',
		data:{id_cli:id_usu,id_secc:id_secc,per:per},
		type:'POST',
		dataType:'text',
		success:function(text){
			//alert(text);
			
		}
		
		});
	
	
	
};
function CalculaPVP(ori,per,iva,dest,redondear_s_n,i)
{
		var redo=redondear_s_n;
        var cost=quitap(ori.val())*1;
        var gan=(100-per.val()*1)/100;
        var pvp=quitap(dest.val())*1;
		var impSaludable = Number($('#impSaludable'+i).val())/100 || 0;
		var IVA= ((iva.val()*1)/100 +1) + impSaludable;
		var dcto=0,desc=0,per_flete=1;
		var impuesto_consumo=$('#impuesto_consumo'+i).val()*1 || 0;
		impuesto_consumo=impuesto_consumo/100;
		if($('#dct'+i).length!=0){
			dcto=$('#dct'+i).val();
			desc=cost*(dcto/100);
		}
		if($('#per_flete').length!=0){
			
			per_flete=$('#per_flete').val()*1 || 0;
			per_flete=(per_flete/100) +1;
			}

		cost=(cost*IVA*per_flete + cost*impuesto_consumo)-desc;
        console.log('cost= cost['+cost+']*IVA['+IVA+']*PER_FLETE['+per_flete+'] + cost*'+impuesto_consumo+' - desc['+desc+']');
		var valorGanancia=0;

		if(tipoUtil=='A')
		{
			gan=(per.val()/100) +1;
			valorGanancia=(cost*gan)|| 0;
			console.log('tipoUtil==A gan:'+gan+', cost:'+cost+', valorGanancia=='+valorGanancia);
			}
		else {
			valorGanancia=(cost/gan)|| 0;
			console.log('');
		}
	
	if(redo=='s')
	{
		
		valorGanancia=redondeo3(valorGanancia);
		console.log('Redondeo s= si '+valorGanancia);
			
	}
	else 
	{
		valorGanancia=redondeo(valorGanancia);
		console.log('Redondeo s!= no '+valorGanancia);
		}
    
	console.log('Valor Ganancia:'+valorGanancia);
	console.log('------------------ FIN -------------------');
	dest.prop('value',valorGanancia);	
};


function calc_gcia(costo,iva,pvp,dest,redondear_s_n,i)
{
		var redo=redondear_s_n;
        var costoProducto=quitap(costo.val())*1;
		var gan=0;
        var precioProducto=quitap(pvp.val())*1;
		var impSaludable = Number($('#impSaludable'+i).val())/100 || 0;
		var IVA= ((iva.val()*1)/100 +1) + impSaludable;

		console.log('COSTO costoProducto['+costoProducto+'], PvP['+precioProducto+'], impSaludable['+impSaludable+'], IVA['+IVA+']');
		var valorArticulo=precioProducto;
		var dcto=0,desc=0,per_flete=1;
		if($('#dct'+i).length!=0){
			dcto=$('#dct'+i).val();
			desc=costoProducto*(dcto/100);
		}
		if($('#per_flete').length!=0){
			
			per_flete=$('#per_flete').val()*1 || 0;
			per_flete=(per_flete/100) +1;
		}
		
		costoProducto=costoProducto*IVA*per_flete-desc;
        console.log('COSTO costoProducto('+costoProducto+')= costoProducto['+costoProducto+']*IVA['+IVA+']*PER_FLETE['+per_flete+']-DESC['+desc+']');

	if(valorArticulo!=0){
		if(tipoUtil=='A'){
			gan=((valorArticulo-costoProducto)/costoProducto)*100 ||0;
			console.log('tipoUtil==A gan('+gan+')= ( (valorArticulo['+valorArticulo+']-costoProducto['+costoProducto+']) /costoProducto['+costoProducto+'])*100');
		}
		else {
			gan=(1-((costoProducto/valorArticulo)))*100 || 0;
			console.log('tipoUtil !=A gan('+gan+')= (1- ((costoProducto['+costoProducto+']/valorArticulo['+valorArticulo+']))) * 100');
		}
				
	if(redo=='s'){gan=gan.toFixed(2);}
	else {}
	
	
	}
	
	dest.prop('value',gan);	
	console.log('------------------ FIN -------------------');
};


function pvp_costo(ori,per,dest,iva,redondear_s_n,i)
{
	    var redo=redondear_s_n;
        var gan=(100-per.val()*1)/100;
        var pvp=quitap(ori.val())*1;
		var impSaludable = Number($('#impSaludable'+i).val())/100 || 0;
		var IVA= ((iva.val()*1)/100 +1) + impSaludable;
		var costo= 0;

		if(tipoUtil=='A')
		{
			gan=(per.val()*1/100);
			costo= (pvp / (1+gan) ) / IVA || 0;
			
		}
		else {
			costo=(pvp*gan)/IVA || 0;
		}
		costo = (redo=='s') ? redondeo(costo): redondeo(costo);

 
	    dest.prop('value',costo);	
		//alert('Dcto:'+gan+', pvp:'+pvp+', Costo:'+costo+'dest:'+dest.val());

};
function pvp_costo_com(ori,per,dest,iva)
{
	   
        var gan=(100-per.val()*1)/100;
        var pvp=quitap(ori.val())*1;
		var IVA=(iva.val()*1)/100 +1;
			
	var costo=(pvp*gan)/IVA|| 0;
	//alert('Dcto:'+gan+', pvp:'+pvp+', Costo:'+costo+'dest:'+dest.val());
	//alert('val :'+va);
	dest.prop('value',redondeo(costo));	
		//alert('Dcto:'+gan+', pvp:'+pvp+', Costo:'+costo+'dest:'+dest.val());

};


function tipo_descuento(opc,cost,pvp,per,iva,redondear_s_n,i)
{
	
	if(opc.val()=='pvp'){CalculaPVP(cost,per,iva,pvp,redondear_s_n,i);}
	else if(opc.val()=='costo') {pvp_costo(pvp,per,cost,iva,redondear_s_n,i);}
		else if(opc.val()=='ganancia') {calc_gcia(cost,iva,pvp,per,redondear_s_n,i);}
};
function ajax_load(size_256_350_512,container)
{
	var size=size_256_350_512;
	
	var html='<div class="loader" style="position:fixed;top:30%;right:50%;left:30%;vertical-align:middle;"> <img id="loader" src="Imagenes/ajax_loader_red_'+size+'.gif" width="'+size+'" height="'+size+'" /> </div>';
	var $add=$(html);
	$add.appendTo(container);
	

	$('#loader').hide();
	$('#loader').ajaxStart(function(){
		$(this).show();
		$('input[type=button]').prop("disabled","disabled").css("color","red");
		})
	.ajaxSuccess(function(){
		$(this).hide();
		$('input[type=button]').removeAttr("disabled").css("color","black");
		});
	
	//$('#loader').ajaxError(function(){$('input[type=button]').prop("disabled","disabled").css("color","red");$(this).hide();});
	
	
};

function cambia_fv(num_fac,pre,resol,resp,opc)
{
	var datos='num_fac='+num_fac+'&pre='+pre+'&resol='+resol+'&resp='+resp+'&opc='+opc;
	ajax_a('ajax/admin_update_fac_ven.php',datos,'');
};


function eleImpuestoSaludable(onChangeFunction,formValue,idIndex) 
{
	var html='<select name="impSaludable'+idIndex+'" id="impSaludable'+idIndex+'" onChange="'+onChangeFunction+'"  class="art'+idIndex+'" style="height:40px;width:80px;">';
	html+='<option selected value="'+formValue+'"> '+formValue+'%</option>';
    html+='<option value="0">0%</option>';
	html+='<option value="15">15%</option>';
	html+='</select>';

	return html;
}
