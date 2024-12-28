var fe = new Date(); 
var dia = fe.getDate(); 
var mes = fe.getMonth() + 1; 
var year = fe.getFullYear(); 
$(document).ready(function fecha() {
	$('#loader_ajax').hide();
   /*var F = document.getElementById("fecha"); 
   var FECHA = year + '-' + mes + '-' + dia; 
   F.setAttribute("value", FECHA);*/ 
   }); 
var l = null; 
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
var rta=0;
var tipo_cli;
function quitap(T) {
   var n = T.split("."), 
   i = 0, h = ""; 
   for(i = 0; i < n.length; i++) {
      h = h + n[i]; 
      }
   return h; 
   }; 
function V(xhr){
	var n=xhr.split("\n"),i=0,h='',rt='';
	for(i=1;i<n.length;i++)
	{
		if(n[i]!='\n'&&n[i]!='')rt=rt+n[i];
		//alert("...n["+i+"]:"+n[i]+"...");
	}
	//alert("...rt:"+rt);
	return rt;};
	
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


function puntob(ve) {
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
function ids(idd, idvru, vcam) {
   iddet = idd; 
   idvu = idvru; 
   valcampo = vcam; 
   }; 
   
function subtot() {
   var des = quitap($('#DESCUENTO').val()); 

	
   cantidad = cont; 
   $('#num_ref').prop('value',cont);
  // alert('CANT:'+cantidad);
   var i = 0; 
   SUB = 0;
   TOTAL = 0;
   IVA = 0; 
   var vt,vtF,SUBF=0,IVAF=0,cantFlag=0;
   for(i = 0; i < cantidad; i++) {
	   if($('#val_t' + i).length!=0){vt = $('#val_t' + i).val();vtF = $('#val_t' + i+'H').val();cantFlag++;}
	   else {vt="0";vtF="0";}
        
	 // alert("vt:"+vt+" i:"+i);
      vt = quitap(vt); 
      SUB = SUB + parseInt(vt) || 0;
	  SUBF = SUBF + parseFloat(vtF) || 0; 
	  
      }
	  
   if(cantFlag==0){$('#num_ref').prop('value','0');}
  // alert($('#num_ref').val());
   IVA=parseInt(SUB*0.16);
   IVAF=SUBF*0.16;
   //alert('iva:'+IVA+'');
   TOTAL=SUBF+IVAF- (parseFloat(des) || 0);
   SUB = parseInt(SUBF).toString(); 
   SUB = puntob(SUB); 
//alert('SUB:'+SUB);
   IVA = parseInt(IVAF).toString(); 
   IVA = puntob(IVA); 

   TOTAL = parseInt(TOTAL).toString(); 
   TOTAL = puntob(TOTAL); 
  // alert('total:'+TOTAL);
   $('#SUB').prop('value', SUB); 
   $('#SUBH').prop('value', quitap(SUB));
   $('#IVA').prop('value', IVA); 
   $('#IVAH').prop('value', quitap(IVA)); 
   $('#TOTAL').prop('value', TOTAL); 
   $('#TOTALH').prop('value', quitap(TOTAL));
   }; 
function valor_t(id) {
   var i = parseInt(id); 
   //alert('ID:'+id+', num:'+i);
   var idt = 'val_t' + i;
   var vu = $('#val_u' + i+'H').val();
   $('#val_u' + i+'HH').prop('value',vu);
   
   var vuH = $('#val_u' + i+'H').val(), cnt = $('#' + i + 'cant_').val(); 
   //vu = vu.split(".")[0]; 
   
   var vtot = cnt * vu || 0,vtotH = cnt * vuH || 0;
   //alert('cant:'+cnt+' TOT:'+vtot+' vuH:'+vu); 
   vtot = vtot.toString().split(".")[0]; 
   $('#' + idt+'HH').prop('value', vtot);
   vtot = puntob(vtot); 
   $('#val_u' + i+'H').prop('value',vu);
   $('#' + idt).prop('value', vtot);
   $('#' + idt+'H').prop('value', vtotH); 
   subtot(); 
   }; 
function TOT() {
   var sal, tot = $("#TOTAL").val(), des = $('#DESCUENTO').val(), abo = $("#ABO").val(); 
   tot = quitap(tot); 
   des = quitap(des); 
   abo = quitap(abo); 
   SALDO = tot - des - abo; 
   SALDO = SALDO.toString(); 
   SALDO = puntob(SALDO); 
   $('#SALDO').prop('value', SALDO); 
   $('#SALDOH').prop('value', quitap(SALDO)); 
   }; 
function J() {
   if(window.XMLHttpRequest) {
      return new XMLHttpRequest(); 
      }
   else if(window.ActiveXObject) {
      return new ActiveXObject("Microsoft.XMLHTTP"); 
      }
   }; 
function H() {
   var campo = valcampo; 
   return "campo=" + encodeURIComponent(campo) + "&nocache=" + Math.random(); 
   }; 
function L() {
   var campo = valcampo; 
   return "campo=" + encodeURIComponent(campo) +"&tipo=" + encodeURIComponent(tipo_cli) + "&nocache=" + Math.random(); 
   }; 
function j() {
	var $cod=$('#cod0');
	var $loader=$('#loader_ajax');
	//alert($par);
	if(l.readyState==1){$cod.blur();$loader.show();$cod.bind('focus',function(){this.blur()});
	}
   if(l.readyState == 4) {
      if(l.status == 200) {
		  $loader.hide();
		  //$cod.prop("value","");
		  $cod.unbind('focus');
		  $cod.focus();
		 // alert('idvu:'+idvu);
         var G = document.getElementById(idvu); 
         var money = l.responseText; 
		// alert('money respTxt:'+money);
         money = V(money); 
		 
		 
		// alert('money v():'+money);
         if(isNaN(money)) {//alert('isNan')
            $('#' + idvu).prop('value', '0'); 
            }
         else {
			 money=money/1.16;
			 moneyF=money;
			 money=parseInt(money);
			 money=money.toString();
			 $('#'+idvu+'HH').prop('value',money);
			// alert(money);
            money = puntob(money); 
			//alert('pb'+money);
			$('#'+idvu).prop('value',money);
			$('#'+idvu+'H').prop('value',quitap(money)); 
			//alert(G.id+' val:'+G.value)
            }
			//alert("ivu:"+ivu);
         valor_t(ivu); 
          
         }
      }
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
		 subtot();
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };
function eli_mod(c)
 {
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $det;
	 var n_nota=$('#num_nota').val();
	 var ref=$('#'+c).val();
	 //alert('num_nota:'+n_nota+' ref:'+ref);
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		
		$.ajax({
			url:'../valida_datos/del_art.jsp',
			data:{num_nota:n_nota,ref:ref} ,
			type:'POST',
			dataType:'text',
			success:function(resp){
				//$('#delOut').html('<p style="color:white">'+resp+'</p>');
				},
			error:function(xhr,status){alert('Error, xhr:'+xhr+'||||| Status: '+status);}
			
			}); 
		 
		 $eliRow.remove();
		 subtot();
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };


function eli2(c)
 {
	 //alert(c)
	 var $eliRow=$(".art"+c);
	 var $nf=$('#num_facH').val();
	 var $ref=$('#ref'+c+'H').val();
	 var $cant=$('#cant'+c).val();
	 //alert("delete from art_compra where ref='"+$ref+"' and num_fac="+$nf+";");
	 
	// alert($('#del').val());
	 var $det;
	 if($("#det_"+c+"").length==0)$det='la Fila?';
	 else $det=$("#det_"+c+"").val();
	 $eliRow.css('backgroundColor','red');
	 //alert($('#form-fac').serializeArray());
	 if(confirm("Desea borrar '"+$det+"'?"))
	 {
		 $('#del').prop('value',$('#del').val()+"delete from art_compra where ref='"+$ref+"' and num_fac="+$nf+";update inventario set existencias=existencias-"+$cant+" where referencia='"+$ref+"';");
		 $('#del_ck').prop('value','1');
		 $eliRow.remove();
		 subtot();
		 
	 }
	 else {$eliRow.css('backgroundColor','#fff');}
	 
	 
 };


function k() {
	var $cod=$('#cod0');
	var $loader=$('#loader_ajax');
	//alert($par);
	if(l.readyState==1){
		$cod.blur();$loader.show();$cod.bind('focus',function(){this.blur();});
	}

   if(l.readyState == 4) {
      if(l.status == 200) {
		  //alert(l.responseText);
		  var resp=parseInt(V(l.responseText));
		  //alert(resp);
		  if(resp!=0 && resp!=1)
		  {
			  
               var det="det_",val_u="val_u";
               var $ar=$('<tr id="tr_'+cont+'" class="art'+cont+'"><td class="art'+cont+'" align="center"><input class="art'+cont+'" id="'+cont+'cant_" type="text" name="cant_'+cont+'" size="5" maxlength="5" value="1" onkeyup="javascript:valor_t(this.id);" style=" width:50px"/></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" readonly="" value=\"'+valcampo+'\" type="text" id="'+cont+'" name="ref_'+cont+'"/></td><td><input type="text" name="serial'+cont+'" id="serial'+cont+'" class="art'+cont+'"><td class="art'+cont+'"><textarea class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" value=""></textarea></td><td class="art'+cont+'" ><input class="art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoiva(this);valor_t('+cont+');" value="0" /><input class="art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden" readonly="" value="0" /><input class="art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /></td><td class="art'+cont+'"><input class="art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" /><input class="art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td><td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="../Imagenes/iconos/delete.png" width="31px" heigth="31px" ></td></tr>');
               $ar.appendTo('#articulos');
               cont++;
			   $loader.hide();
		       $cod.prop("value","");
		       $cod.unbind('focus');
		       $cod.focus();
			   //alert(iddet);
              
			   $('#'+iddet).prop('value',V(l.responseText));
		      // alert('....'+l.responseText+"...");
               valida(); 
		    
         }
		 else if(resp==0) {
			 $loader.hide();
		     $cod.prop("value","");
		     $cod.unbind('focus');
		     $cod.focus();
			 rta="nr";
			 alert(".:Articulo AGOTADO:.");
			 }
		else if(resp==1&&valcampo!='Apartar Mercancia') {
			 $loader.hide();
		     $cod.prop("value","");
		     $cod.unbind('focus');
		     $cod.focus();
			 rta="nr";
			 alert(".:Articulo no Encontrado:."+" valcamp:"+valcampo);
			 }
			 
		else if(resp==1&&valcampo=='Apartar Mercancia') {
			
			var det="det_",val_u="val_u";
            var $ar=$('<tr id="tr_'+cont+'" class="art'+cont+'"><td class="art'+cont+'" align="center"><input class="art'+cont+'" id="'+cont+'cant_" type="text" name="cant_'+cont+'" size="5" maxlength="5" value="1" onkeyup="javascript:valor_t(this.id);" style=" width:50px"/></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" value=\"'+(valcampo+apartar+'_'+cont)+'\" type="text" id="'+cont+'" name="ref_'+cont+'"/></td><td><input type="text" name="serial'+cont+'" id="serial'+cont+'" class="art'+cont+'"><td class="art'+cont+'"><textarea class="art'+cont+'" name="det_'+cont+'" id="det_'+cont+'" value=""></textarea></td><td class="art'+cont+'" ><input class="art'+cont+'" id="val_u'+cont+'" name="val_uni'+cont+'" type="text" onkeyup="puntoiva(this);valor_t('+cont+');" value="0" /><input class="art'+cont+'" id="val_u'+cont+'HH" name="val_u'+cont+'" type="hidden" readonly="" value="0" /><input class="art'+cont+'" id="val_u'+cont+'H" name="val_u'+cont+'H" type="hidden" readonly="" value="0" /></td><td class="art'+cont+'"><input class="art'+cont+'" id="val_t'+cont+'" name="val_tot'+cont+'" type="text" readonly="" value="0" /><input class="art'+cont+'" id="val_t'+cont+'HH" name="val_t'+cont+'" type="hidden" readonly="" value="0" /><input class="art'+cont+'" id="val_t'+cont+'H" name="val_t'+cont+'H" type="hidden" readonly="" value="0" /></td><td class="art'+cont+'"><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="../Imagenes/iconos/delete.png" width="31px" heigth="31px" ></td></tr>');
               $ar.appendTo('#articulos');
               cont++;
			   apartar++;
			   $loader.hide();
		       $cod.prop("value","");
		       $cod.unbind('focus');
		       $cod.focus();
			 //rta="nr";
			 //alert(".:Apartando Mercancia:.");
			 }
		 
	  }//200
	  
      }//4
   }; 
function K() {
   if(l.readyState == 4) {
      if(l.status == 200) {//alert('respTxt:'+l.responseText+'END');
	     if(l.responseText!=0){//alert('if..');
         var R = V(l.responseText).split("_");
		 if(R[0]=="cliente"){  
		 //alert(R[0]+'-'+R[1]+'-'+R[2]+'-'+R[3]);
         $('#nombres1').prop("value", R[1]); 
         $('#direccion1').prop("value", R[2]); 
         $('#tel1').prop("value", R[3]); 
      
		 }
		 else {
		 $('#nombres2').prop("value", R[1]); 
         $('#direccion2').prop("value", R[2]); 
         $('#tel2').prop("value", R[3]); 
      
			 
			 }
		 }		 
         }
      }
   }; 
function valida() {
   l = J(); 
   if(l) {
      l.onreadystatechange = j; 
      l.open("POST", '../valida_datos/valor_u_inv.jsp', true); 
      l.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
      var O = H(); 
      l.send(O); 
      }
   }; 
function valida_det(id) {
   ivu = id; 
   l = J(); 
   if(l) {
      l.onreadystatechange = k; 
      l.open("POST", '../valida_datos/detalles_inv.jsp', true); 
      l.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
      var O = H(); 
      l.send(O); 
      }
   }; 
 
function valida_doc(cli,cam) {
	tipo_cli=cli;
	valcampo=cam;
   l = J(); 
   if(l) {
      l.onreadystatechange = K; 
      l.open("POST", '../valida_datos/val_client.jsp', true); 
      l.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
      var O = L(); 
      l.send(O); 
      }
   };
   
   
   var key;
function cod(n){
	//alert(n.value)
	if(window.XMLHttpRequest){
    var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=arguments[0];
	key=parseInt(evento.keyCode);
	if(key==13){addart(n);}
	
	}
	}
	else {
	var entra=document.getElementById(n.id);
    entra.onkeyup=function(){
	var evento=window.event;
	 key=parseInt(evento.keyCode);
	 //alert('IE, cod:'+key);
	 if(key==13){addart(n);}    
		}
	}
	
	};
	

function addart(n)
{
//alert('id:'+n.id);

$sel=$("input[value$='"+n.value+"']");
	//alert('id:'+n.id);
	//alert($sel.val());
if($sel.length!=0&&n.id!='botApa'){
	//alert('id:'+$sel.prop('id')+'repetido!!');
	var $cant=$("#"+$sel.prop('id')+"cant_");
	var $cod=$('#cod0');
	var can=($cant.prop("value"))*1;
	//alert(can++);
	$cant.prop("value",++can);
	$cod.prop("value","");
	valor_t($sel.prop('id'));
	
	}

else{	
if(n.value!=""&&n.value!=null)
{
ids('det_'+cont,'val_u'+cont,n.value);

valida_det(cont);

}
}

};


function submitt(val,id,frm)
{
    if(esVacio(frm.num_nota.value)){alert('Ingrese el NUMERO de la Nota de Entrega!');frm.num_nota.focus();}
    else if(frm.verify.value!='ok'){alert('Valor incorrecto, CAMBIELO!');}
	else if(esVacio(frm.ced1.value)){alert('Ingrese la Cedula del Cliente');frm.ced1.focus();}
	else if(esVacio(frm.nom_ven.value)){alert('Ingrese un vendedor');frm.cod_ven.focus();}
	else if(esVacio(frm.num_ref.value)&&parseInt(frm.num_ref)!=0){alert('No se han cargado articulos a la Factura....');}
	else if(esVacio(frm.letras.value)){alert('Ingrese el valor Total en LETRAS!');frm.letras.focus();}
	
	else {
	$('#'+id).prop("value",val);

	frm.submit();}
	
};

function submitt_mod(val,id,frm)
{
    if(esVacio(frm.num_nota.value)){alert('Ingrese el NUMERO de la Nota de Entrega!');frm.num_nota.focus();}
    else if(frm.verify.value!='ok'){alert('Valor incorrecto, CAMBIELO!');}
	else if(esVacio(frm.ced1.value)){alert('Ingrese la Cedula del Cliente');frm.ced1.focus();}
	else if(esVacio(frm.nom_ven.value)){alert('Ingrese un vendedor');frm.cod_ven.focus();}
	else if(esVacio(frm.letras.value)){alert('Ingrese el valor Total en LETRAS!');frm.letras.focus();}
	
	else {
	$('#'+id).prop("value",val);
	frm.submit();
	}
	
};


function busq(n,con,id)
{//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	$.ajax({
		url:'../valida_datos/busq_item.jsp',
		data:{busq:n.val(),con:con,id:id},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			$('#Qresp'+con).append(text);
			}
			else {alert('No se encontraron sugerencias..');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
	
	}
};

function auto(n,con,id){
	n=parseInt(n);
	var $cod=$('#'+id+''+con);
	var $bus=$('#search'+n);
	//alert($bus.val());
	$cod.prop('value',$bus.val());
	$cod.change();
	
};


function seller(n)
{

$.ajax({
		url:'../valida_datos/val_ven.jsp',
		data:{cod:n},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			$('#nom_ven').prop('value',text);
			}
			else {alert('El codigo no esta en la Base de Datos');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
};

function pro(n)
{

$.ajax({
		url:'../valida_datos/val_pro.jsp',
		data:{nit:n},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			var r=text.split('?');
			$('#provedor').prop('value',r[0]);
			$('#dir').prop('value',r[1]);
			$('#tel').prop('value',r[2]);
			$('#mail').prop('value',r[3]);
			$('#ciudad').prop('value',r[4]);
			$('#fax').prop('value',r[5]);
			}
			else {}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});
};

function addinv(n)
{var $row;
for(i=0;i<n;i++){
$row=$('<tr class="art'+cont+'"><td class="art'+cont+'" align="center" valign="top"><input style="width:30px" class="art'+cont+'" name="cant'+cont+'" type="text" id="cant'+cont+'" value="" onChange="val_tot('+cont+');"></td><td class="art'+cont+'" align="center" valign="top"><input onChange="art_com(this,'+cont+')" class="art'+cont+'" name="ref'+cont+'" type="text" id="ref'+cont+'" value="" style="width:60px;top:10px"><img style="cursor:pointer" title="Buscar" onMouseUp="busq($(\'#ref'+cont+'\'),'+cont+',\'ref\');" src="../Imagenes/iconos/search128x128.png" width="25" height="25" /><br /><div id="Qresp'+cont+'"></div></td><td class="art'+cont+'" align="center" valign="top"><textarea style="" class="art'+cont+'" name="des'+cont+'"  id="des'+cont+'" value=""></textarea></td><td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="cat'+cont+'" type="text" id="cat'+cont+'" value=""></td><!--<td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="mar'+cont+'" type="text" id="mar'+cont+'" value=""></td>--><td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="v_sin_iva'+cont+'" type="text" id="v_sin_iva'+cont+'" value="" onChange="puntoa(this);val_tot('+cont+')" onKeyUp="puntoa(this);val_tot('+cont+')"><input class="art'+cont+'" name="v_sin_ivaH'+cont+'" type="hidden" id="v_sin_iva'+cont+'H" value=""></td><td align="center" valign="top" class="art'+cont+'"><input onKeyUp="iva('+cont+')" class="art'+cont+'" name="iva'+cont+'" type="text" id="iva'+cont+'" value="" style="width:30px;"></td><td align="center" valign="top" class="art'+cont+'"><input class="art'+cont+'" name="v_iva'+cont+'" type="text" id="v_iva'+cont+'" value="" onChange="util('+cont+');val_tot('+cont+');" onKeyUp="util('+cont+');"><input class="art'+cont+'" name="v_ivaH'+cont+'" type="hidden" id="v_iva'+cont+'H" value=""></td><td class="art'+cont+'" align="center" valign="top"><input style="width:30px" class="art'+cont+'" name="uti'+cont+'" type="text" id="uti'+cont+'" value="" onKeyUp="uti('+cont+')"></td><td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="v_venta'+cont+'" type="text" id="v_venta'+cont+'" value="" onChange="" readonly><input class="art'+cont+'" name="v_ventaH'+cont+'" type="hidden" id="v_venta'+cont+'H" value=""></td><td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="util'+cont+'" type="text" id="util'+cont+'" value="" onChange="puntoa(this)" onKeyUp="puntoa(this)"><input class="art'+cont+'" name="utilH'+cont+'" type="hidden" id="util'+cont+'H" value=""></td><td class="art'+cont+'" align="center" valign="top"><input class="art'+cont+'" name="v_tot'+cont+'" type="text" id="v_tot'+cont+'" value="" onChange="puntoa(this)" onKeyUp="puntoa(this)"><input class="art'+cont+'" name="v_totH'+cont+'" type="hidden" id="v_tot'+cont+'H" value=""><img onMouseUp="eli($(this).prop(\'class\'))" class="'+cont+'" src="../Imagenes/iconos/delete.png" width="21px" heigth="21px" ></td></tr>');
$row.appendTo('#articulos');
cont++;

}
$('#num_ref').prop('value',cont);
	
	};


	
function nom_pro(n)
{
	$('#nit').prop('value',n.value);
	pro(n.value);
	//$('#provedor').prop('value',$(n).html());
	
	};

function art_com(ref,con)
{
	//alert(ref.value+" con:"+con);
	
	$.ajax({
		url:'../valida_datos/val_art_compra.jsp',
		data:{ref:ref.value},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			var r=text.split('_');
			$('#des'+con).prop('value',V(r[0]));
			$('#cat'+con).prop('value',r[1]);
			$('#mar'+con).prop('value',r[2]);
			$('#v_sin_iva'+con).prop('value',puntob(r[3]));
			$('#v_sin_iva'+con).change();
			$('#v_sin_iva'+con+'H').prop('value',r[3]);
			$('#v_iva'+con).prop('value',puntob(r[4]));
			$('#v_iva'+con).change();
			//alert(r[4]);
			$('#v_iva'+con+'H').prop('value',r[4]);
			$('#v_venta'+con).prop('value',puntob(r[5]));
			$('#v_venta'+con).change();
			//alert(r[5]);
			$('#v_venta'+con+'H').prop('value',r[5]);
			$('#util'+con).prop('value',puntob(r[6]));
			$('#util'+con).change();
			//alert(r[6]);
			$('#util'+con+'H').prop('value',r[6]);
			val_tot(con);
			}
			else {}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});

	
	};

function iva(con)
{
var vci= ( ( ( parseInt($('#iva'+con).val())||0 )*( parseInt($('#v_sin_iva'+con+'H').val())||0 ) ) /(100) )+( parseInt($('#v_sin_iva'+con+'H').val())||0);
	//alert(vci);
	vci=parseInt(vci);
	$('#v_iva'+con).prop('value',puntob(vci.toString()));
	$('#v_iva'+con+'H').prop('value',vci);
	uti(con);
	val_tot(con);
	
	};

function uti(con)
{
var pdv= ( ( ( parseInt($('#uti'+con).val())||0 )*( parseInt($('#v_iva'+con+'H').val())||0 ) ) /(100) )+( parseInt($('#v_iva'+con+'H').val())||0);
	//alert(vci);
	pdv=parseInt(pdv);
	$('#v_venta'+con).prop('value',puntob(pdv.toString()));
	$('#v_venta'+con+'H').prop('value',pdv);
util(con);	
val_tot(con);
	};


function val_tot(con)
{
	/*iva(con);
	uti(con);
	util(con);*/
	
	var valorT=(parseInt($('#cant'+con).val())||0) * (parseInt($('#v_iva'+con+'H').val())||0);
	//alert(valorT);
	$('#v_tot'+con).prop('value',puntob(valorT.toString()));
	$('#v_tot'+con+'H').prop('value',valorT);
	
	var TOTAL=0,SUB=0,IVA=0;
    //alert(cont);
	for(i=0;i<cont;i++)
	{
	  
	  TOTAL=TOTAL+(parseInt(quitap( $('#v_tot'+i).val())  )||0);
	  SUB=SUB+((parseInt(quitap( $('#v_sin_iva'+i).val() ))||0)*(parseInt(quitap( $('#cant'+i).val() ))||0));
	  	}

     IVA=TOTAL-SUB;
	 $('#SUBH').prop('value',SUB);
	 $('#TOTALH').prop('value',TOTAL);
	 $('#IVAH').prop('value',IVA);
	 
	 $('#SUB').prop('value',puntob(SUB.toString()));
	 $('#TOTAL').prop('value',puntob(TOTAL.toString()));
	 $('#IVA').prop('value',puntob(IVA.toString()));
	
	};
	
function util(con)
{
	//puntoa(dis);
		//alert(con);
	var utilidad=(parseInt($('#v_venta'+con+'H').val())||0)-(parseInt($('#v_iva'+con+'H').val())||0);

	$('#util'+con).prop('value',puntob(utilidad.toString()));
	$('#util'+con+'H').prop('value',utilidad);
	//val_tot(con)
	
};

function guarda_s(id,val,frm)
{
	
	$('#'+id).prop("value",val);

	frm.submit();
	
	};
	
	
function addinv_m(n)
{var $row;
for(i=0;i<n;i++){
$row=$('<tr class="art'+cont+'"><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="cant'+cont+'" type="text" id="cant'+cont+'" value="" onChange="val_tot('+cont+');"></td><td class="art'+cont+'" align="center"><input onChange="art_com(this,'+cont+')" class="art'+cont+'" name="ref'+cont+'" type="text" id="ref'+cont+'" value=""><img style="cursor:pointer" title="Buscar" onMouseUp="busq($(\'#ref'+cont+'\'),'+cont+',\'ref'+cont+'\');" src="../Imagenes/iconos/search128x128.png" width="34" height="31" /><br /><div id="Qresp'+cont+'"></div></td><td><img id="loader_ajax" src="../Imagenes/ajax-loader.gif" width="31" height="31" /></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="des'+cont+'" type="text" id="des'+cont+'" value=""></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="cat'+cont+'" type="text" id="cat'+cont+'" value=""></td><!--<td class="art'+cont+'" align="center"><input class="art'+cont+'" name="mar'+cont+'" type="text" id="mar'+cont+'" value=""></td>--><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="v_sin_iva'+cont+'" type="text" id="v_sin_iva'+cont+'" value="" onChange="puntoa(this)" onKeyUp="puntoa(this)"><input class="art'+cont+'" name="v_sin_ivaH'+cont+'" type="hidden" id="v_sin_iva'+cont+'H" value=""></td><td align="center" class="art'+cont+'"><input class="art'+cont+'" name="v_iva'+cont+'" type="text" id="v_iva'+cont+'" value="" onChange="util('+cont+',this);" onKeyUp="util('+cont+',this);"><input class="art'+cont+'" name="v_ivaH'+cont+'" type="hidden" id="v_iva'+cont+'H" value=""></td><td class="art'+cont+'" align="center"><input style="width:30px" class="art'+cont+'" name="iva'+cont+'" type="text" id="iva'+cont+'" value=""></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="v_venta'+cont+'" type="text" id="v_venta'+cont+'" value="" onChange="util('+cont+',this);" onKeyUp="util('+cont+',this);"><input class="art'+cont+'" name="v_ventaH'+cont+'" type="hidden" id="v_venta'+cont+'H" value=""></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="util'+cont+'" type="text" id="util'+cont+'" value="" onChange="puntoa(this)" onKeyUp="puntoa(this)"><input class="art'+cont+'" name="utilH'+cont+'" type="hidden" id="util'+cont+'H" value=""></td><td class="art'+cont+'" align="center"><input class="art'+cont+'" name="v_tot'+cont+'" type="text" id="v_tot'+cont+'" value="" onChange="puntoa(this)" onKeyUp="puntoa(this)"><input class="art'+cont+'" name="v_totH'+cont+'" type="hidden" id="v_tot'+cont+'H" value=""><img onMouseUp="eli2($(this).prop(\'class\'))" class="'+cont+'" src="../Imagenes/iconos/delete.png" width="21px" heigth="21px" ></td></tr>');
$row.appendTo('#articulos');
cont++;

}
$('#num_ref').prop('value',cont);
	
	};
function guarda_fac_c(val,id,frm)
{
	if(esVacio(frm.nit.value)){alert('Ingrese el NIT del Provedor');nit.focus();}
	else if(esVacio(frm.num_ref.value)&&frm.num_ref!=0&&frm.num_ref!='0'){alert('No se han cargado articulos a la Factura....');}
	else if(esVacio(frm.num_fac.value)){alert('Ingrese un Numero de Factura');frm.num_fac.focus();}
	else if(frm.verify.value!='ok'){alert('Hay datos que no se pueden repetir, CAMBIELOS');frm.num_fac.focus();}
	else {
	$('#'+id).prop("value",val);

	frm.submit();}
	
};

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   