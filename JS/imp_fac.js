var cont=1;
var num_c=1;
var num_rows=0;
var nr=0;
var fd=0;
var maxDay=new Array();
var per_emple=2;
var per_inde=3;
var per_fanalca=0;
maxDay[0]=31;maxDay[1]=28;maxDay[2]=31;maxDay[3]=30;maxDay[4]=31;
maxDay[5]=30;maxDay[6]=31;maxDay[7]=31;maxDay[8]=30;maxDay[9]=31;
maxDay[10]=30;maxDay[11]=31;
function open_pop2(msg1,msg2,msg_wide,wide)
{
	//alert(msg1);
	$('#modal').remove();
	var html='';
	if(wide)
	{
	 html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1>'+msg1+'</h1><h3>'+msg2+'</h3><div class="uk-overflow-container">'+msg_wide+'</div></div></div>';
	}
	else{
	html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1>'+msg1+'</h1><h3>'+msg2+'</h3></div>';
	}
	var $msg=$(html);
	$msg.appendTo('body');
	/*
	$('#popupDialog').popup();
	$('#popupDialog').popup("open");*/
	
	var modal = $.UIkit.modal("#modal");

	if ( modal.isActive() ) {
    modal.hide();
	//remove_pop($('#modal'));
	} else {
    modal.show();
	}	
	};
function open_popNormal(msg1,msg2,msg3)
{
	//alert(msg1);
	$('#modal').remove();
	var html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1>'+msg1+'</h1><h3>'+msg2+'</h3><div class="uk-overflow-container">'+msg3+'</div></div></div>';
	var $msg=$(html);
	$msg.appendTo('body');
	var modal = $.UIkit.modal("#modal");
    if ( modal.isActive() ) {
    modal.hide();
	//remove_pop($('#modal'));
	} else {
    modal.show();
	}	
	};
function creditos($box,$tipo,$num_cu)
{
	var tc=$tipo.val();
	var nc=$num_cu.val()*1;
	
	if(tc=='Empleados')
	{
		$box.prop('value',nc*per_emple);
		$box.change();
	}
	else if(tc=='Independientes')
	{
		$box.prop('value',nc*per_inde);
		$box.change();
	}
	else 
	{
		$box.prop('value',nc*per_fanalca);
		$box.change();
		}
	
	
};
function fe_fanalca(obj)
{
	var f_ant=$('#fe_entrega').val().split('-');
	var a=f_ant[0]*1,m=f_ant[1]*1,d=f_ant[2]*1;
	var mm=m;
	var i=0;
	if(obj.value=='FANALCA'){
		$('#add_fe_btn').hide();
		$('#fechas_plazo').remove();
		if($('#fe_entrega'+i).length!=0){
			
		if(a%4==0){maxDay[1]=29;}
		else {maxDay[1]=28;}
		
		mm++;
		if(mm>12){a++;mm=1;}
		if(d>maxDay[mm-1]){d=1;}
		if(mm<10&&d<10) $('#fe_entrega'+i).val(a+'-0'+mm+'-0'+d);
		else if(mm<10&&d>=10) $('#fe_entrega'+i).val(a+'-0'+mm+'-'+d);
		else if(mm>=10&&d<10) $('#fe_entrega'+i).val(a+'-'+mm+'-0'+d);
		else  $('#fe_entrega'+i).val(a+'-'+mm+'-'+d);
		
		
		
		}
	}
	else {$('#fe_entrega'+i).prop('value',$('#fe_entrega').val());$('#add_fe_btn').show();}
		//alert(fe);
	
	
};


function nanC($obj)
{
 //alert('nanC:'+$obj.val());
 if(quitap($obj.val())<0){alert('Ingrese un Numero Mayor a Cero!');$obj.focus();}
 if(isNaN(quitap($obj.val()) ) ){alert('Ingrese un Numero!');$obj.focus();}

};

function redondeo(numero)
{
var original=parseFloat(numero);
var result=Math.round(original*0.1)/0.1 ;
//var result=Math.round(original) ;
//alert(result);
return result;
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
   $('#' + n.id + "H").prop('value',h/1.16); 
   };  
function V(xhr) {
   var n = xhr.split("\n"), 
   i = 0, h = '', rt; 
   for(i = 0; i < n.length; i++) {
      if(n[i] != '\n' && n[i] != '')rt = n[i]; 
      }
   return rt; 
   }; 
function puntob2(ve) {
   var T = ve; 
   T = quitap(T); 
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = ''; 
   while(i >= 0) {
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + '.' + h; 
         C = 0; 
         }
      else {
         h = T[i] + h; 
         }
      C++; 
      i--; 
      }
   return h; 
   }; 

/*   
function quitap(F) {
   var n = F.split("."), 
   i = 0, T = ""; 
   for(i = 0; i < n.length; i++) {
      T = T + n[i]; 
      }
   return T; 
   }; 
   */
   
 
function addFe(n)
{
	//alert(cont);

    n=parseInt(n) || 0;
	
	creditos($('#intereses'),$('#tipo_credito'),$('#cantC'));
	//creditos($('#intereses'),this,$('#cantC'));
    cont=1;num_c=1;num_rows=0;nr=0;$('#fechas_plazo').remove();
	var $feRow;
	if($('#plazos').val()!="Diarias"){
	$('#fe_diarias').remove();
	fd=0;
	$('<table id="fechas_plazo" border="0"  width="100%" cellspacing="0" cellpadding="0"></table>').appendTo('#fechas-plazos');
	for(i=0;i<n;i++){
	$feRow=$('<tr class="plazo'+cont+'"><td class="plazo'+cont+'">Fecha Cuota <span class="num'+cont+'">'+num_c+':</span></td><td class="plazo'+cont+'"><input class="plazo'+cont+'" readonly type="text" name="fe_entrega'+cont+'" value="" id="fe_entrega'+cont+'" onClick="//popUpCalendar(this, form1.fe_entrega'+cont+', \'dd-mm-yyyy\');"/></td><td class="plazo'+cont+'">Valor Cuota <span class="num'+cont+'">'+num_c+':</span></td><td valign="middle" class="plazo'+cont+'"><input class="plazo'+cont+'" type="text" id="val_cu'+cont+'" /><input class="plazo'+cont+'" type="hidden" name="val_cuota'+cont+'" id="val_cu'+cont+'H" /></td></tr>');
	$feRow.appendTo("#fechas_plazo");
	cont++;
	num_c++;
	num_rows++;
	nr++;
	
	
	}
	
	val_cuotas();fechas();
	
	}
	
	else 
	{
		cont=1;num_c=1;num_rows=0;nr=0;$('#fechas_plazo').remove();
		if(fd==0){
		$('<table id="fe_diarias" border="0"  width="100%" cellspacing="0" cellpadding="0"></table>').appendTo('#fechas-plazos');
        $('<tr ><td >Fecha Cuota FINAL:</td><td><input readonly type="text" name="fe_final" value="" id="fe_Final" onClick="//popUpCalendar(this, form1.fe_final, \'yyyy-mm-dd\');"/></td><td>Valor Cuotas Diarias</td><td><input readonly type="text" id="val_cuD" /><input readonly type="hidden" name="val_cuotaD" id="val_cuDH" /></td></tr>').appendTo('#fe_diarias');fd++;}
		
		var valD=parseFloat((1*$('#val_creditoH').val()-1*$('#val_cu0H').val())/n)||0;
		var f_ant=$('#fe_entrega').val().split('-');
	    var a=f_ant[0]*1,m=f_ant[1]*1,d=f_ant[2]*1;
		var dd=d;
		
		
		
	    for(i=0;i<n;i++)
		{
		  if(a%4==0){maxDay[1]=29;}else {maxDay[1]=28;}
		  dd++;
		  if(dd>maxDay[m-1]){dd=dd-maxDay[m-1];m++;}
		  if(m>12){a++;m=1;}
		 
			
			}
		//alert(a+'-'+m+'-'+dd);
	
		if(m<10&&dd<10) $('#fe_Final').prop('value',a+'-0'+m+'-0'+dd);
		else if(m<10&&dd>=10) $('#fe_Final').prop('value',a+'-0'+m+'-'+dd);
		else if(m>=10&&dd<10) $('#fe_Final').prop('value',a+'-'+m+'-0'+dd);
		else  {$('#fe_Final').prop('value',a+'-'+m+'-'+dd);}
		$('#val_cuD').val(puntob(valD.toString()));
		$('#val_cuDH').val(valD);
		
		}
		$('#nr').prop('value',nr);
	//alert($('#nr').val()+' nr:'+nr);
		
	};
	

function n_c()
{
	var n=2;
	nr=0;
	num_c=1;
	for(i=0;i<cont;i++){
	if($('.num'+i).length!=0)
	{
		$('.num'+i).html(n);
		n++;
		num_c++;
		nr++;
		}
	}
	
	$('#nr').prop('value',nr);
	
	};
	
function val_cuotas()
{
//alert($('#val_tot').val()+', val cuota:'+$('#val_cu0').val());
//alert(nr);
if(nr==0)nr=1;
var vcs=redondeo(parseFloat((1*($('#val_creditoH').val())- 1*(quitap($('#val_cu0').val()) ) )/nr));
var vdif=parseFloat(1*$('#val_creditoH').val()-1*(quitap($('#val_cu0').val() )) );
//alert(vcs);
for(i=1;i<cont;i++)
{if($('#val_cu'+i).length!=0)
{
	$('#val_cu'+i+'H').prop('value',vcs);	
	$('#val_cu'+i).prop('value',puntob(vcs.toString()));	
		
	}
		
		
}	

$('#v_dif').prop('value',puntob(vdif.toString()));
$('#v_difH').prop('value',vdif.toString());
	
};


function fechas()
{
	
var fe_ent=$('#fe_entrega').val().split('-'),plazo=$('#plazos').val();
//alert(plazo);
//fe_ent[1]=fe_ent[1]*1;
//alert(fe_ent[0]+'-'+fe_ent[1]+'-'+fe_ent[2]);

if(plazo=="Mensuales")
{
	var f_ant=$('#fe_entrega').val().split('-');
	var a=f_ant[0]*1,m=f_ant[1]*1,d=f_ant[2]*1;
	var mm=m;
	for(i=1;i<cont;i++)
	{
		if($('#fe_entrega'+i).length!=0){
			
		if(a%4==0){maxDay[1]=29;}
		else {maxDay[1]=28;}
		
		mm++;
		if(mm>12){a++;mm=1;}
		if(d>maxDay[mm-1]){d=1;}
		if(mm<10&&d<10) $('#fe_entrega'+i).val(a+'-0'+mm+'-0'+d);
		else if(mm<10&&d>=10) $('#fe_entrega'+i).val(a+'-0'+mm+'-'+d);
		else if(mm>=10&&d<10) $('#fe_entrega'+i).val(a+'-'+mm+'-0'+d);
		else  $('#fe_entrega'+i).val(a+'-'+mm+'-'+d);
		
		
		
		}
		//alert(fe);
	}
	
  }
  
else if(plazo=="Quincenales")
{
	var f_ant=$('#fe_entrega').val().split('-');
	var a=f_ant[0]*1,m=f_ant[1]*1,d=f_ant[2]*1;
	var dd=d;
	for(i=1;i<cont;i++)
	{
		if($('#fe_entrega'+i).length!=0){
			
		if(a%4==0){maxDay[1]=29;}
		else {maxDay[1]=28;}
		
	    dd+=15;
		//alert('antes:'+dd);
		if(dd>maxDay[m-1]){dd=dd-maxDay[m-1];m++;}
		if(m>12){a++;m=1;}
		//alert('despues:'+dd);
		if(m<10&&dd<10) $('#fe_entrega'+i).val(a+'-0'+m+'-0'+dd);
		else if(m<10&&dd>=10) $('#fe_entrega'+i).val(a+'-0'+m+'-'+dd);
		else if(m>=10&&dd<10) $('#fe_entrega'+i).val(a+'-'+m+'-0'+dd);
		else  $('#fe_entrega'+i).val(a+'-'+m+'-'+dd);
		
		
		
		
		}
	}
	
  }//fin else if
else if(plazo=="Semanales")
{
	var f_ant=$('#fe_entrega').val().split('-');
	var a=f_ant[0]*1,m=f_ant[1]*1,d=f_ant[2]*1;
	var dd=d;
	for(i=1;i<cont;i++)
	{
		if($('#fe_entrega'+i).length!=0){
			
		if(a%4==0){maxDay[1]=29;}
		else {maxDay[1]=28;}
		
	    dd+=7;
		//alert('antes:'+dd);
		if(dd>maxDay[m-1]){dd=dd-maxDay[m-1];m++;}
		if(m>12){a++;m=1;}
		//alert('despues:'+dd);
		if(m<10&&dd<10) $('#fe_entrega'+i).val(a+'-0'+m+'-0'+dd);
		else if(m<10&&dd>=10) $('#fe_entrega'+i).val(a+'-0'+m+'-'+dd);
		else if(m>=10&&dd<10) $('#fe_entrega'+i).val(a+'-'+m+'-0'+dd);
		else  $('#fe_entrega'+i).val(a+'-'+m+'-'+dd);
		
		
		
		
		}
	}
	
  }//fin else if


};//fin func

function eli(c)
 {
	 //alert(c)
	 var $eliRow=$(".plazo"+c);
	 //var $det=$("#det_"+c+"").val();
	 //$eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	// if(confirm("Desea borrar '"+$det+"'?"))
	// {
		 $eliRow.remove();
		 num_rows--;
		 n_c();
		 val_cuotas();
		 fechas();
		 
		 
	// }
	// else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };


function int(val)
{
	//alert(val);
	var inter=redondeo((val/100)*(1*$('#val_totH').val()));
	//alert(inter);
	$('#val_creditoH').prop('value',1*$('#val_totH').val()+inter);
	$('#val_credito').prop('value',puntob(parseFloat($('#val_creditoH').val()).toString()));
	//alert($('#val_creditoH').val());
	val_cuotas();fechas();
	
	};
	
function cambio(n){
	
	cont=1;num_c=1;num_rows=0;nr=0;$('#fechas_plazo').remove();
	addFe(n);
};


/*
function busq_exp(n)
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
*/


function gca(btn,id,frm)
{
	//$('#'+btn.id).unbind('click');
	
	var val = btn.value;
	//alert(frm.meca.value+' '+esVacio(frm.tipo_cli.value));
	//alert(tot);
    if(esVacio(frm.nom_cli.value)){alert('Ingrese Nombre');frm.nom_cli.focus();}
	else if(esVacio(frm.cc_cli.value)){alert('Ingrese No Documento');frm.cc_cli.focus();}
	else if(esVacio(frm.tel_cli.value)){alert('Ingrese Telefono');frm.tel_cli.focus();}
	else {
	$('#'+id).prop("value",val);
	//alert($('#'+id).val()+' ID:'+id+' name:'+$('#'+id).prop('name'));
    $(btn).remove();
	frm.submit();
	
	}
	
	
	
};






function calc_per($per,$val,$resp)
{
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeox(val*per,2));
	
	$resp.prop('value',resp);
	//call_tot();
	
};
function calc_per2($per,$val,$resp)
{
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeox(val*per,2));
	
	$resp.prop('value',resp);
	
};