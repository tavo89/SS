function busq_cli_exp(n)
{
	    
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
			 
		 
			 
			  
			  $('#nom_cli').prop('value',response[0].nombre);	
			 $('#dir').prop('value',response[0].direccion);
			 $('#tel_cli').prop('value',response[0].telefono);
			 
			 
			 
			 			 
			}
			
			
			},
		error:function(xhr,status){alert('La conexion Fallo, INTENTELO MAS TARDE');}
		});
};
function get_exp(n,op){
	//alert('cod '+n.value);
	
	if(!esVacio(n.value))
	{
		
	if(window.XMLHttpRequest){
    var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=arguments[0];
	key=parseInt(evento.keyCode);
	if(key==13&&op=='add'){}
	if(key==13&&op=='mod'){}
	if(key==120){busq_exp($(n));}
	
	}
	}
	else {
	var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=window.event;
	 key=parseInt(evento.keyCode);
	 //alert('IE, cod:'+key);
	if(key==13&&op=='add'){}
	if(key==13&&op=='mod'){}
	if(key==120){busq_exp($(n));} 
		}
	}
	
	}
	
	};
 function busq_exp($n)
 {
	// alert($n.val());
	$.ajax({
		url:'ajax/busq_exp.php',
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
function sel_exp(i,nom,valor)
{
	$('#num_exp').prop('value',nom);
	$('#verify').prop('value','ok');
	$('#confirma').html('CONFIRMADO').css('color','green');
	
	};
function gexp(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	
	var val = btn.value;
	var fp=frm.form_pago.value;
	//alert(fp);
 
	var ctaIN=0;
	
	if(usarCtas==1){
		
 
	 ctaIN=frm.id_cuenta.value;
	}
	//alert(frm.meca.value+' '+esVacio(frm.tipo_cli.value));
	
    if(esVacio($('#nom_cli').val())){alert('Ingrese Nombre');$('#nom_cli').focus();}
	else if((fp!='Contado'&&fp!='Contado-Caja General') && (esVacio(ctaIN) ) &&  usarCtas==1){alert('Seleccione una CUENTA');focusRed($(frm.id_cuenta));}
	else if((fp=='Contado'||fp=='Contado-Caja General') && (!esVacio(ctaIN) ) &&  usarCtas==1){alert('Los pagos de CONTADO solo pueden ir a la Cuenta de CAJA GENERAL');focusRed($(frm.form_pago));}
	else if(esVacio($('#cc_cli').val())){alert('Ingrese No Documento');$('#cc_cli').focus();}
	else if(esVacio($('#tel_cli').val())){alert('Ingrese Telefono');$('#tel_cli').focus();}
	else if(esVacio($('#tot').val())){alert('Ingrese VALOR pagado');$('#tot').focus();}
	else {
	$('#'+id).prop("value",val);
	//alert($('#'+id).val()+' ID:'+id+' name:'+$('#'+id).prop('name'));
    $(btn).remove();
	frm.submit();
	
	}
	
	
	
};
function expediente(n,op){
	//alert('cod '+n.value);
	
	if(!esVacio(n.value))
	{
		
	if(window.XMLHttpRequest){
    var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=arguments[0];
	key=parseInt(evento.keyCode);
	if(key==13&&op=='add'){confirm_exp(n.value);}
	//if(key==13&&op=='mod'){add_art_ven();}
	if(key==120){busq_exp($('#num_fac'));}
	
	}
	}
	else {
	var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=window.event;
	 key=parseInt(evento.keyCode);
	 //alert('IE, cod:'+key);
	 if(key==13&&op=='add'){confirm_exp(n.value);}
	//if(key==13&&op=='mod'){alert('mod!');}
	if(key==120){busq_exp($('#num_fac'));}   
		}
	}
	
	}
	
	};	
function confirma_exp($n)
{
	$.ajax({
		url:'ajax/confirm_exp.php',
		data:{num:$n.val()},
		type: 'POST',
		dataType:'text',
		success:function(text){
			//alert(text);
			var resp=text.split('|');
			if(resp[0]=='1')
			{
				$('#verify').prop('value','ok');
				$('#confirma').html('CONFIRMADO').css('color','green');
				$('#saldo').html('Saldo: '+resp[4]).css('color','green');
				$('#concepto').prop('value',resp[1]);
				}
			else
			{
			$('#verify').prop('value','ko');
			$('#confirma').html('SIN CONFIRMAR').css('color','red');	
			}
			
			},
		error:function(xhr,status){alert('Su conexion ha fallado...intentelo mas tarde');}
		});
};
function confirm_exp(val)
{//alert('busq!');
	$.ajax({
		url:'ajax/confirm_exp.php',
		data:{num:val},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);  
		///$('#response').prop('value',text);  
		    if(text==1){
			$('#RESP').append('<span style="color:green;">Confirmado</span>');
			$('#verify').prop('value','ok');
			
			}
			else if(text==2){alert('Este Anticipo ya fue FACTURADO');$('#verify').prop('value','ko');}
			else if(text!=-1){
			$('#sel_fecha').html(text);
			$('#verify').prop('value','ok');
			}
			else {alert('No se encontraron sugerencias..');$('#verify').prop('value','ko');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
};

function save_anti(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	
	var val = btn.value;
	var fp=frm.form_pago.value;
	//alert(fp);
 
	var ctaIN=0;
	
	if(usarCtas==1){
		
 
	 ctaIN=frm.id_cuenta.value;
	}
	//alert(frm.meca.value+' '+esVacio(frm.tipo_cli.value));
	//alert(tot);
	
    if(esVacio(frm.num_exp.value)){alert('Ingrese No. del Expediente');frm.num_exp.focus();}
	else if((fp!='Contado'&&fp!='Contado-Caja General') && (esVacio(ctaIN) ) &&  usarCtas==1){alert('Seleccione una CUENTA');focusRed($(frm.id_cuenta));}
	else if((fp=='Contado'||fp=='Contado-Caja General') && (!esVacio(ctaIN) ) &&  usarCtas==1){alert('Los pagos de CONTADO solo pueden ir a la Cuenta de CAJA GENERAL');focusRed($(frm.form_pago));}
	else if(frm.verify.value!='ok'){alert('No. Expediente NO VALIDO');frm.num_exp.focus();}
	else if(esVacio(frm.valor.value)){alert('Ingrese el Valor del Anticipo');frm.valor.focus();}
	else if(isNaN(quitap(frm.valor.value))){alert('Ingrese un NUMERO');frm.valor.focus();}
	else if(esVacio(frm.concepto.value)){alert('Especifique concepto del Anticipo');frm.concepto.focus();}
	else {
	$('#'+id).prop("value",val);
	//alert($('#'+id).val()+' ID:'+id+' name:'+$('#'+id).prop('name'));
    $(btn).remove();
	frm.submit();
	
	}
	
	
	
};