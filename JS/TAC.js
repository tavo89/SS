$(document).ready(function(){
	$('#loader').hide();
	$('#loader').ajaxStart(function(){$(this).show();})
	.ajaxStop(function(){$(this).hide();});
	
	});
	
	
	
function anular_fac_ven(num_fac,pre)
{
	//alert(num_fac);
	if(!esVacio(pre)&&!esVacio(num_fac)){
	if(confirm('Desea ANULAR Factura de Venta No.'+pre+' '+num_fac)){
	 $.ajax({
		url:'ajax/anula_fac.php',
		data:{num_fac:$.trim(num_fac),pre:$.trim(pre)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
	//alert(text);
		//$('#query').html(text);
		
		if(resp==0)alert('Esta Factura YA esta Anulada o no esta CERRADA');
		else if(resp!=-2&&resp!=-1&&resp!=-445)
		{
			//alert('Fac. No.'+num_fac+' ANULADA');
			location.reload();

		}
		else if(resp==-445){error_pop('Esta Factura tiene abonos en CARTERA, anule los abonos antes de anular esta Factura');}
		else if(resp==-1){error_pop('Esta Factura supera el limite de tiempo permitido para modificaciones, accion cancelada.... ');}
		else error_pop('Factura No.'+num_fac+' NO encontrada');
		
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
	 });
	 
	}// if confirma
	
	}// if vacios
else {alert('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};

function anular_dev_venta(num_fac,pre)
{
	//alert(num_fac);
	if(!esVacio(pre)&&!esVacio(num_fac)){
	if(confirm('Desea ANULAR Devolucion de Venta No.'+pre+' '+num_fac)){
	 $.ajax({
		url:'ajax/anula_dev_venta.php',
		data:{num_fac:$.trim(num_fac),pre:$.trim(pre)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
	//alert(text);
		//$('#query').html(text);
		
		if(resp==0)alert('Esta Devolucion YA esta Anulada  ');
		else if(resp!=-2&&resp!=-1&&resp!=-445)
		{
			alert('Devolucion. No.'+num_fac+' ANULADA');
			location.reload();

		}
		else if(resp==-445){error_pop('Esta Factura tiene abonos en CARTERA, anule los abonos antes de anular esta Factura');}
		else if(resp==-1){error_pop('Esta Devolucion supera el limite de tiempo permitido para modificaciones, accion cancelada.... ');}
		else error_pop('Devolucion No.'+num_fac+' NO encontrada');
		
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
	 });
	 
	}// if confirma
	
	}// if vacios
else {alert('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};	
	
function anular_fac_remi(num_fac,pre,orig)
{
	//alert(num_fac);
	if(!esVacio(pre)&&!esVacio(num_fac)){
	if(confirm('Desea ANULAR REMISION No.'+pre+' '+num_fac)){
	 $.ajax({
		url:'ajax/anula_remi.php',
		data:{num_fac:$.trim(num_fac),pre:$.trim(pre),orig:orig},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		//alert(text);
		//$('#query').html(text);
		
		if(resp==0)alert('Esta REMISION YA esta Anulada o no esta CERRADA');
		else if(resp!=-2&&resp!=-1)
		{
			alert('Fac. No.'+num_fac+' ANULADA');
			location.reload();

		}
		else if(resp==-1){alert('Esta Factura supera el limite de tiempo permitido para modificaciones, accion cancelada.... ');}
		else alert('Factura No.'+num_fac+' NO encontrada');
		
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
	 });
	 
	}// if confirma
	
	}// if vacios
else {alert('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};
	
function recover_fac_remi(num_fac,pre)
{
	//alert(num_fac);
	
	if(!esVacio(pre)&&!esVacio(num_fac)){
	if(confirm('Desea RECUPERAR REMISION No.'+pre+' '+num_fac)){
	 $.ajax({
		url:'ajax/recover_remi.php',
		data:{num_fac:$.trim(num_fac),pre:$.trim(pre)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		//alert(text);
		//$('#query').html(text);
		
		if(resp==0)alert('Esta REMISION NO esta Anulada');
		else if(resp!=-2&&resp!=-1)
		{
			alert('Fac. No.'+num_fac+' RECUPERADA');
			location.reload();

		}
		else if(resp==-1){alert('Esta Factura supera el limite de tiempo permitido para modificaciones, accion cancelada.... ');}
		else alert('Factura No.'+num_fac+' NO encontrada');
		
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
	 });
	 
	}// if confirma
	
	}// if vacios
else {alert('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};
	
function anular_fac_remi2(num_fac,pre,codSuc)
{
	//alert(num_fac);
	if(!esVacio(pre)&&!esVacio(num_fac)){
	if(confirm('Desea ANULAR REMISION No.'+pre+' '+num_fac)){
	 $.ajax({
		url:'ajax/anula_remi2.php',
		data:{num_fac:$.trim(num_fac),pre:$.trim(pre),cod_su:codSuc},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		//alert(text);
		//$('#query').html(text);
		
		if(resp==0)alert('Esta REMISION YA esta Anulada o no esta CERRADA');
		else if(resp!=-2&&resp!=-1)
		{
			alert('Fac. No.'+num_fac+' ANULADA');
			location.reload();

		}
		else if(resp==-1){alert('Esta Factura supera el limite de tiempo permitido para modificaciones, accion cancelada.... ');}
		else alert('Factura No.'+num_fac+' NO encontrada');
		
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
	 });
	 
	}// if confirma
	
	}// if vacios
else {alert('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};
