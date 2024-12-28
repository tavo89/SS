function selc(i,id)
{
	$('#num_fac').prop('value',id);
	confirm_cre();
};
function saveComp(btn,val,form)
{
	getPage($('#HTML_Pag'),$('#comp_ingreso'));
	//alert('PROMEDIO  VAL:'+form.confirma.value);
	//alert();
	if(esVacio(form.id_cli.value)){alert('Ingrese el numero de C.C/NIT');form.id_cli.focus();}
	else if(form.verify.value!='ok'){alert('El numero de Factura  NO es  Valido!, Presione ENTER en la casilla para CONFIRMAR Factura');form.num_fac.focus();}
	else if(esVacio(form.fecha_pago.value) || form.fecha_pago.value=='0000-00-00'){alert('Ingrese la fecha');form.fecha_pago.focus();}
	else if(esVacio(form.valor.value)){alert('Ingrese el Valor de la Cuota');form.valor.focus();}
	else if(esVacio(form.concepto.value)){alert('Ingrese el Concepto del abono');form.concepto.focus();}
	else{
	btn.prop('value',val);
	form.submit();}
};
function saveComp_mass(btn,val,form)
{
	getPage($('#HTML_Pag'),$('#comp_ingreso'));
	//alert('PROMEDIO  VAL:'+form.confirma.value);
	//alert();
	if(esVacio(form.id_cli.value)){alert('Ingrese el numero de C.C/NIT');form.id_cli.focus();}
	else if(form.verify.value!='ok'){alert('El numero de Factura  NO es  Valido!, Presione ENTER en la casilla para CONFIRMAR Factura');form.num_fac.focus();}
	else if(esVacio(form.fecha_pago.value) || form.fecha_pago.value=='0000-00-00'){alert('Ingrese la fecha');form.fecha_pago.focus();}
	else if(esVacio(form.valor.value)){alert('Ingrese el Valor de la Cuota');form.valor.focus();}
	else if(esVacio(form.concepto.value)){alert('Ingrese el Concepto del abono');form.concepto.focus();}
	else{
	btn.prop('value',val);
	form.submit();}
};
function saveCompVar(btn,val,form)
{
	getPage($('#HTML_Pag'),$('#comp_ingreso'));
	//alert('PROMEDIO  VAL:'+form.confirma.value);
	//alert();
	if(esVacio(form.fecha_pago.value) || form.fecha_pago.value=='0000-00-00'){alert('Ingrese la fecha');form.fecha_pago.focus();}
	else if(esVacio(form.valor.value)){alert('Ingrese el Valor de la Cuota');form.valor.focus();}
	else if(esVacio(form.concepto.value)){alert('Ingrese el Concepto del abono');form.concepto.focus();}
	else{
	btn.prop('value',val);
	form.submit();}
};
function confirm_cre()
{
	//alert('busq!');
if($('#id_cli').lenght!=0  &&!esVacio($('#id_cli').val()))
	{
		
	$.ajax({
		url:'ajax/cartera/confirm_cre.php',
		data:{id_cli:$('#id_cli').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		
		//alert(text);
			
		///$('#response').prop('value',text); 
		var r=text.split('|'); 
		r[0]=r[0]*1;
		//alert(text+', r['+r[0]);
		    if(r[0]==-3){
			//alert('Cliente CONFIRMADO');
			$('#verify').prop('value','ok');
			$('#saldo').html('SALDO TOTAL:<br> $'+puntob(r[1]));
			
			}
			else if(r[0]==-1){alert('Este credito ya esta PAGADO');$('#verify').prop('value','ko');}
			else if(r[0]==0){
			alert('Facura NO ENCONTRADA');
			$('#verify').prop('value','ko');
			}
			else {alert('Esta Factura NO es un CREDITO');$('#verify').prop('value','ko');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
	}
};
function busq_cu()
{//alert('busq!');
	$.ajax({
		url:'ajax/fechas_pago.php',
		data:{num_fac:$('#num_fac').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);  
		///$('#response').prop('value',text);  
		    if(text==-3){
			$('#sel_fecha').append('<input type="text" name="fecha_cuota" value="'+$('#fecha').val()+'" >');
			$('#verify').prop('value','ok');
			
			}
			else if(text==-4){alert('Este credito ya esta PAGADO');$('#verify').prop('value','ko');}
			else if(text!=-1){
			$('#sel_fecha').html(text);
			$('#verify').prop('value','ok');
			}
			else {alert('No se encontraron sugerencias..');$('#verify').prop('value','ko');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
};

function busq_fac(n)
{//alert('busq:'+n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_fac.php',
		data:{busq:n.val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			$('#Qresp').append(text);
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
	}
};
function confirm_cre_rango()
{
	//alert('busq!');
	
if( ($('#fechaI').lenght!=0 && $('#fechaF').lenght!=0 )  && ( !esVacio($('#fechaI').val()) && !esVacio($('#fechaF').val()) ) )
	{
		
	$.ajax({
		url:'ajax/cartera/saldo_cre_rango.php',
		data:{feI:$('#fechaI').val(),feF:$('#fechaF').val()},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		
		//alert(text);
			
		///$('#response').prop('value',text); 
		var r=text.split('|'); 
		r[0]=r[0]*1;
		//alert(text+', r['+r[0]);
		    if(r[0]==-3){
			//alert('Cliente CONFIRMADO');
			$('#verify').prop('value','ok');
			$('#saldo').html('SALDO TOTAL:<br> $'+puntob(r[1]));
			
			}
			else if(r[0]==-1){alert('Esta Semana ya esta PAGADO');$('#verify').prop('value','ko');}
			else if(r[0]==0){
			alert('Facuras NO ENCONTRADAS');
			$('#verify').prop('value','ko');
			}
			else {alert('NO HAY  CREDITOS EN ESTE RANGO');$('#verify').prop('value','ko');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
	}
};
