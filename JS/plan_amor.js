var cont=1;
var num_c=1;
var num_rows=0;
var nr=0;
var fd=0;
var maxDay=new Array();
maxDay[0]=31;maxDay[1]=28;maxDay[2]=31;maxDay[3]=30;maxDay[4]=31;
maxDay[5]=30;maxDay[6]=31;maxDay[7]=31;maxDay[8]=30;maxDay[9]=31;
maxDay[10]=30;maxDay[11]=31;

if($('#val_credito').length!=0){

$('#val_credito').prop('value',puntob2($('#val_credito').val()));
$('#val_tot').prop('value',puntob2($('#val_tot').val()));
}


if($('#Nart').val()!='0'){
$('#val_cre').html(puntob($('#val_cre').html()));
$('#val_fac').html(puntob($('#val_fac').html()));
$('#val_dif').html(puntob($('#val_dif').html()));
for(i = 0; i <= $('#Nart').val(); i++) {
   $('#vu' + i).html(puntob($('#vu' + i).html())); 
   //$('#tvu' + i).html(puntob($('#tvu' + i).html())); 
   }
}
else {//alert('else..');
$('#val_cre').html(puntob($('#val_cre').html()));
$('#val_fac').html(puntob($('#val_fac').html()));
$('#val_dif').html(puntob($('#val_dif').html()));
for(i = 0; i <3; i++) {
   $('#vu' + i).html(puntob($('#vu' + i).html())); 
   //$('#tvu' + i).html(puntob($('#tvu' + i).html())); 
   }
	
	
}

function puntob(k) {
   var F = k; 
   F = quitap(F); 
   var i = F.length - 1, ii = F.length; 
   F = F.split(""); 
   var x, a, b, c, H = 0, T = ''; 
   while(i >= 0) {
      if(H == 3 && ii != 3 && F[i] != '-') {
         T = F[i] + '.' + T; 
         H = 0; 
         }
      else {
         T = F[i] + T; 
         }
      H++; 
      i--; 
      }
   return "$"+T; 
   }; 



function esVacio(val) {
   if(val == null) {
      return true; 
      }
   for(var i = 0; i < val.length; i++) {
      if((val.charAt(i) != ' ') && (val.charAt(i) != "\t") && (val.charAt(i) != "\n") && (val.charAt(i) != "\r")) {
         return false; 
         }
      }
   return true; 
   }; 
function quitap(T) {
   var n = T.split("."), 
   i = 0, h = ""; 
   for(i = 0; i < n.length; i++) {
      h = h + n[i]; 
      }
   return h; 
   }; 
function V(xhr) {
   var n = xhr.split("\n"), 
   i = 0, h = '', rt; 
   for(i = 0; i < n.length; i++) {
      if(n[i] != '\n' && n[i] != '')rt = n[i]; 
      }
   return rt; 
   }; 
function puntoa(n) {
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
   $('#' + n.id + "H").prop('value', quitap(h));
   //alert($('#' + n.id + "H").val()); 
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

   
function quitap(F) {
   var n = F.split("."), 
   i = 0, T = ""; 
   for(i = 0; i < n.length; i++) {
      T = T + n[i]; 
      }
   return T; 
   }; 
   
   
 
function addFe(n)
{
	//alert(cont);

    n=parseInt(n) || 0;
    cont=1;num_c=1;num_rows=0;nr=0;$('#fechas_plazo').remove();
	var $feRow;
	if($('#plazos').val()!="Diarias"){
	$('#fe_diarias').remove();
	fd=0;
	$('<table id="fechas_plazo" border="0"  width="100%" cellspacing="0" cellpadding="0"></table>').appendTo('#fechas-plazos');
	for(i=0;i<n;i++){
	$feRow=$('<tr class="plazo'+cont+'"><td class="plazo'+cont+'">Fecha Cuota <span class="num'+cont+'">'+num_c+':</span></td><td class="plazo'+cont+'"><input class="plazo'+cont+'" readonly type="text" name="fe_entrega'+cont+'" value="" id="fe_entrega'+cont+'" onClick="//popUpCalendar(this, form1.fe_entrega'+cont+', \'dd-mm-yyyy\');"/></td><td class="plazo'+cont+'">Valor Cuota <span class="num'+cont+'">'+num_c+':</span></td><td valign="middle" class="plazo'+cont+'"><input class="plazo'+cont+'" type="text" id="val_cu'+cont+'" /><input class="plazo'+cont+'" type="hidden" name="val_cuota'+cont+'" id="val_cu'+cont+'H" /><img style=" top:5px; position:relative;" onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="../Imagenes/iconos/delete.png" width="20px" heigth="20px" ></td></tr>');
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
        $('<tr ><td >Fecha Cuota FINAL:</td><td><input readonly type="text" name="fe_final" value="" id="fe_Final" onClick="//popUpCalendar(this, form1.fe_final, \'yyyy-mm-dd\');"/></td><td>Valor Cuotas Diarias</td><td><input readonly type="text" id="val_cuD" /><input readonly type="hidden" name="val_cuotaD" id="val_cuDH" /><img style=" top:5px; position:relative; visibility: hidden"  class="1" src="../Imagenes/iconos/delete.png" width="20px" heigth="20px"></td></tr>').appendTo('#fe_diarias');fd++;}
		
		var valD=parseInt((1*$('#val_creditoH').val()-1*$('#val_cu0H').val())/n)||0;
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
		$('#val_cuD').val(puntob2(valD.toString()));
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

var vcs=parseInt((1*($('#val_creditoH').val())- 1*($('#val_cu0H').val()))/nr);
var vdif=parseInt(1*$('#val_creditoH').val()-1*$('#val_cu0H').val());
//alert(vcs);
for(i=1;i<cont;i++)
{if($('#val_cu'+i).length!=0)
{
	$('#val_cu'+i+'H').prop('value',vcs);	
	$('#val_cu'+i).prop('value',puntob2(vcs.toString()));	
		
	}
		
		
}	

$('#v_dif').prop('value',puntob2(vdif.toString()));
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
	var inter=(val/100)*(1*$('#val_totH').val());
	//alert(inter);
	$('#val_creditoH').prop('value',1*$('#val_totH').val()+inter);
	$('#val_credito').prop('value',puntob2(parseInt($('#val_creditoH').val()).toString()));
	//alert($('#val_creditoH').val());
	val_cuotas();fechas();
	
	};
	
function cambio(n){
	
	cont=1;num_c=1;num_rows=0;nr=0;$('#fechas_plazo').remove();
	addFe(n);
};