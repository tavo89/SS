function blockForm($form){
$form
.ajaxStart(function() { $(this).prop("readonly","readonly"); })
.ajaxStop(function() { $(this).prop("readonly",""); });
}
function print_pop(url,nom_pag,ancho,alto)
{
window.open(url,nom_pag,'width='+ancho+',height='+alto+',scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
};
function save_any($formSelc,URL,success_function)
{
	var datos=$formSelc.serialize();
	//alert(datos);
	ajax_b(URL,datos,success_function);
};
function ajax_a(URL,Data,success_msg)
{
	//alert(URL+'?'+Data);

	$.ajax({
     type: 'POST',
     url: URL,
     data: Data,
     success: function(resp){
		 if(!esVacio(resp))open_pop(resp,'','');
		
    },
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
 });
	
};
function ajax_b(URL,Data,success_function)
{
	//alert(URL+'?'+Data);

	$.ajax({
     type: 'POST',
     url: URL,
     data: Data,
     success: function(resp){
		 //if(!esVacio(resp))open_pop('','',resp);
		 success_function(resp);
		
    },
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
 });
	
};
function getJson(url, data) {
    return JSON.parse($.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        global: false,
        async: false,
        data: data,
        success: function (json) {
            return json;
        },
        error: function (error) {
            return error;
        }
    }).responseText);
};
function quitap(T) {
	T=''+T+'';
	//alert(T);
   var n = T.split(","), 
   i = 0, h = ""; 
   for(i = 0; i < n.length; i++) {
      h = h + n[i]; 
      }
	 // alert(h);
   return h; 
   }; 


function puntoa($ve) {
   var T = $ve.val().toString(); 
   //alert('puntoa::T:'+T);
   T = quitap(T);
  // alert('puntoa::quitap(T):'+T); 
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = '',ff=0; 
   while(i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 if(!isNaN(T[i]) || T[i]=='.'){
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + ',' + h; 
         C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         h = T[i] + h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if(T[i]=='.'){
	   C=-1;
	   h=quitap(h);
	   }
	   C++;
	 }
	    
	   i--;
	 
      }
   $ve.prop('value', h); 
   
   }; 
function puntob(ve) {
   var T = ve.toString(); 
   //alert('T:'+T);
   T = quitap(T);
  // alert('quitap(T):'+T); 
   var i = T.length - 1, ii = T.length; 
   T = T.split(""); 
   var x, a, b, c, C = 0, h = '',ff=0; 
   while(i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 
      if(C == 3 && ii != 3 && T[i] != '-') {
         h = T[i] + ',' + h; 
         C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         h = T[i] + h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if(T[i]=='.'){C=-1;h=quitap(h);}
	   C++; 
	   i--;
       
      }
   return h; 
   }; 
function val_ele(id)
{
	var $resp=$(id);
	
	if($resp.length!=0)
	{
		//alert(id+' '+$resp.val());
		return $resp.val();
	}
	else return 0;
};
function update(t,c,vc,cla,useF)
{
	//alert(t+'--'+c+'--'+vc+'--'+cla+'')
	if(!esVacio(t)&&!esVacio(c)&&!esVacio(vc)&&!esVacio(cla))
	{//alert('entro...');
		$.ajax({
			url:'ajax/ue.php',
			data:{t:t,c:c,vc:vc,clau:cla,useF:useF},
			type:'POST',
			dataType:'text',
			success:function(resp)
			{
			//alert(resp);
			if(resp==1)
			{
			//alert();
			}
			else
			{
				alert('Ocurio un error...');
				alert(resp);
			}
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}			
			});
		
	}	
};
///////////////////////////////////////////////////////////////// MOD COLS EN TABLAS //////////////////////////////////////////////////////////////////////////////
function clearTabTD(IDtd,colVal)
{
	var $td=$('#'+IDtd);
	$td.html(colVal);
};
function mod_tab_row(IDtd,tab,col,valCol,where,ii,inputType,selOpt,tipoCol)
{
	//alert('id:'+ii+', dcto:'+dcto+',ref:'+id_pro+', cli:'+id_cli);
	var $td=$('#'+IDtd);
	$td.html('');
	where=where.replace( /'/g, "\\'" );
	
	if(tipoCol=='num'){valCol=quitap(valCol);}
	//alert(where);
	//alert(selOpt);
	var html='';
	if(inputType=="text"){html="<input type=\"text\" name=\"tabCol\" value=\""+valCol+"\" id=\"tabCol"+ii+"\" onBlur=\"update('"+tab+"','"+col+"',this.value,'"+where+"','');clearTabTD('"+IDtd+"',this.value) \" >";}
	
	if(inputType=="date"){html="<input type=\"date\" name=\"tabCol\" value=\""+valCol+"\" id=\"tabCol"+ii+"\" onBlur=\"update('"+tab+"','"+col+"',this.value,'"+where+"','1');clearTabTD('"+IDtd+"',this.value) \" >";}
	
	else if(inputType=="select"){html="<select data-placeholder=\"Escriba..\" class=\"chosen-select uk-form-large\" name=\"tabCol\" id=\"tabCol"+ii+"\" onChange=\"update('"+tab+"','"+col+"',this.value,'"+where+"','');clearTabTD('"+IDtd+"',this.value) \" ><option value='' selected></option>"+selOpt+"</select>";}
	
	else {html="<input type=\"text\" name=\"tabCol\" value=\""+valCol+"\" id=\"tabCol"+ii+"\" onBlur=\"update('"+tab+"','"+col+"',this.value,'"+where+"','');clearTabTD('"+IDtd+"',this.value) \" >";}
	
	var $box=$(html);
	$box.appendTo($td);
	$box.focus();
	//alert($td.html());	
}
function mod_tab_row_x(IDtd,tab,col,valCol,where,ii,inputType,selOpt,tipoCol,successFunction,ID)
{
	//alert('id:'+ii+', dcto:'+dcto+',ref:'+id_pro+', cli:'+id_cli);
	var $td=$('#'+IDtd);
	$td.html('');
	where=where.replace( /'/g, "\\'" );
	
	if(tipoCol=='num'){valCol=quitap(valCol);}
	var html='';
	if(inputType=="text"){html="<input type=\"text\" name=\"tabCol\" value=\""+valCol+"\" id=\"tabCol"+ii+"\" onBlur=\"update('"+tab+"','"+col+"',this.value,'"+where+"','');clearTabTD('"+IDtd+"',this.value);successFunction('"+ID+"',this.value); \" >";}
	
	if(inputType=="date"){html="<input type=\"date\" name=\"tabCol\" value=\""+valCol+"\" id=\"tabCol"+ii+"\" onBlur=\"update('"+tab+"','"+col+"',this.value,'"+where+"','1');clearTabTD('"+IDtd+"',this.value);successFunction('"+ID+"',this.value); \" >";}
	
	else if(inputType=="select"){html="<select data-placeholder=\"Escriba..\" class=\"chosen-select uk-form-large\" name=\"tabCol\" id=\"tabCol"+ii+"\" onChange=\"update('"+tab+"','"+col+"',this.value,'"+where+"','');clearTabTD('"+IDtd+"',this.value);successFunction('"+ID+"',this.value); \" ><option value='' selected></option>"+selOpt+"</select>";}
	
	else {html="<input type=\"text\" name=\"tabCol\" value=\""+valCol+"\" id=\"tabCol"+ii+"\" onBlur=\"update('"+tab+"','"+col+"',this.value,'"+where+"','');clearTabTD('"+IDtd+"',this.value);successFunction('"+ID+"',this.value); \" >";}
	
	var $box=$(html);
	$box.appendTo($td);
	$box.focus();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function waitAndReload() {
	setTimeout(function() {
		location.reload();
	}, 1100);
}
function simplePopUp(msg1)
{
	
	$('#modal').remove();
	var html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1 class="uk-text-bold uk-text-primary">'+msg1+'</h1></div></div>';
	var $msg=$(html);
	$msg.appendTo('body');

	var modal = $.UIkit.modal("#modal");

	if ( modal.isActive() ) {
    modal.hide();
	//remove_pop($('#modal'));
	} else {
    modal.show();
	}
	remove_pop($('#modal'));
	};

function open_pop(msg1,msg2,msg3)
{
	$('#modal').remove();
	var html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1 class="uk-text-bold uk-text-primary">'+msg1+'</h1><h3  class="uk-text-bold uk-text-warning">'+msg2+'</h3><div class="uk-overflow-container">'+msg3+'</div></div></div>';
	var $msg=$(html);
	$msg.appendTo('body');

	var modal = $.UIkit.modal("#modal");
	//$('#modal').focus();

	if ( modal.isActive() ) {
    modal.hide();
	} else {
    modal.show();
	}
	remove_pop($('#modal'));
	
	};
function open_pop3(msg1,msg2,msg_wide,wide)
{
	$('#modal').remove();
	var html='';
	if(wide)
	{
	 html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1 class="uk-text-bold uk-text-primary">'+msg1+'</h1><h3 class="uk-text-bold uk-text-warning">'+msg2+'</h3><div class="uk-overflow-container">'+msg_wide+'</div></div></div>';
	}
	else{
	html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large"><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1 class="uk-text-bold uk-text-primary">'+msg1+'</h1><h3 class="uk-text-bold uk-text-warning">'+msg2+'</h3></div>';
	}
	var $msg=$(html);
	$msg.appendTo('body');
	var modal = $.UIkit.modal("#modal");

	if ( modal.isActive() ) {
    modal.hide();
	} else {
    modal.show();
	}	
	};
function error_pop(msg2)
{
	var msg1='ERROR';
 	// uk-border-circle
	var url='Imagenes/WARNINGS/07.png';
	
	//url='Imagenes/WARNINGS/02.gif';url='Imagenes/WARNINGS/06.jpg';
	var img='<img src="'+url+'" class="uk-border-rounded" width="200px" height="200px">';
	
	
	$('#modal').remove();
	
	var html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large "><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1 class="uk-text-bold uk-text-danger uk-heading-large">'+msg1+'</h1><h3  class="uk-text-bold uk-text-primary">'+msg2+'</h3><div class="uk-overflow-container">'+img+'</div></div></div>';
	var $msg=$(html);
	$msg.appendTo('body');
	var modal = $.UIkit.modal("#modal");

	if ( modal.isActive() ) {
    modal.hide();
	} else {
    modal.show();
	}
	remove_pop($('#modal'));
	
	
	
	
};
function warrn_pop(msg2)
{
	var msg1='CUIDADO';
 	// uk-border-circle
	var url='Imagenes/WARNINGS/08.jpg';
	
	//url='Imagenes/WARNINGS/02.gif';url='Imagenes/WARNINGS/06.jpg';
	var img='<img src="'+url+'" class="uk-border-rounded" width="200px" height="200px">';
	
	
	$('#modal').remove();
	/**/
	img='';
	var html='<div id="modal" class="uk-modal"><div class="uk-modal-dialog uk-modal-dialog-large "><a class="uk-modal-close uk-close" onClick="remove_pop($(\'#modal\' ))"></a><h1 class="uk-text-bold uk-text-warning uk-heading-large"><i class="uk-icon uk-icon-warning"></i>&nbsp;'+msg1+'</h1><h3  class="uk-text-bold uk-text-primary">'+msg2+'</h3><div class="uk-overflow-container">'+img+'</div></div></div>';
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
	remove_pop($('#modal'));
	
	
	
	
	
};
function remove_pop($pop)
{
	$pop.on({

    'show.uk.modal': function(){
       
    },

    'hide.uk.modal': function(){
        $pop.remove();
		//waitAndReload();
		//alert('del pop');
    }
});

};
function del_pop($pop)
{
    remove_pop($pop);
    				
};
function hide_pop(idSel)
{
var modal = $.UIkit.modal(idSel);
//alert(idSel);
if ( modal.isActive() ) {
    modal.hide();
} else {
    modal.show();
}
};

function redondeox(num, decimals) {
return Number(Math.round(num+'e'+decimals)+'e-'+decimals);
//return Math.round( num * 100 + Number.EPSILON ) / 100;
  
  
};
function trunc(num)
{
	//return Math.trunc(num);
	return parseInt(num,10);
};
function Goback()
{
	 window.history.back();
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
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
}
function ajax_x(URL,Data,success_function)
{
	//alert(URL+'?'+Data);

	$.ajax({
     type: 'POST',
     url: URL,
     data: Data,
     success: function(resp){
		 //if(!esVacio(resp))open_pop('','',resp);
		 success_function(resp);
		
    },
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
 });
	
};
function getPage($contenedor,$obj) {

	/*var $PAGE= $obj.html();
	
	console.log('id elemento:'+$obj.attr('id'));

    var html = '<img src="'+$('#imgPage').prop('src')+'" id="imgPage" width="970px">'; */
	//var html = $PAGE; 
		//html.find('script').remove();
		//html="" + $obj.text() + "";
		
  	//$contenedor.prop('value',html)
	
};
function takeSnapshot(idString){ 

	var node = document.getElementById(idString);
	domtoimage.toPng(node)
    .then(function (dataUrl) {
		if(document.getElementById("imgPage")) {
			document.getElementById("imgPage").remove();
		}
        var img = new Image();
        img.src = dataUrl;
		img.id = 'imgPage';
        document.body.appendChild(img);
    });
}
function calc_per_a($per,$val,$resp)
{
	var per=$per.val()*1/100 || 0;
	var val=quitap($val.val())*1 || 0;
	var resp=puntob(redondeox(val*per,2));
	
	$resp.prop('value',resp);
	//call_tot();
	
};
function addMultiSelc(idBox,selectedEle,nameEle,idEle,claseEle,table,where,idCol,desCol)
{
var data='selectedEle='+selectedEle+'&nameEle='+nameEle+'&idEle='+idEle+'&claseEle='+claseEle+'&table='+table+'&where='+where+'&idCol='+idCol+'&desCol='+desCol;
ajax_x('ajax/FORMS_ELE/multiSelc.php',data,function(resp){
	
	var $html=$(resp);
	
	//$html.appendTo('#'+idBox);
	$('#'+idBox).html(resp);
	
	$('#'+idEle).multiSelect();
	
	});
};
function loadResolFac(idBox,tipoResol)
{
	var data='tipoResol='+tipoResol+'';
	ajax_x('ajax/FORMS_ELE/load_resol_fac.php',data,function(resp){
		
	var $html=$(resp);
	
	//$html.appendTo('#'+idBox);
	$('#'+idBox).html(resp);
		
		});
};
function save_any2(form,valFunc,successFunc)
{
	var URL='ajax/FORMS/add_any.php';
	var data=$(form).serialize();
	//alert(data);
	
	if(valFunc(form)){
		ajax_x(URL,data,successFunc);
		
	}
};
function successAny(resp){
	
	if(resp==1){
		simplePopUp('Operacion EXITOSA');
		waitAndReload();
	}
	else {
		simplePopUp('OPERACION FALLIDA, Revise que los campos sean Validos y que su Conexion sea estable');
		waitAndReload();
	}	
};
function focusRed($c)
{
	$c.css({'-webkit-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','-moz-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','box-shadow':'box-shadow:rgb(255, 0, 0) 0px 0px 40px -12px inset;' }).focus();
	
};
function focusRed2($c)
{
	$c.css({'-webkit-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','-moz-box-shadow':'rgb(255, 0, 0) 0px 0px 23px 0px inset','box-shadow':'box-shadow:rgb(255, 0, 0) 0px 0px 40px -12px inset;' });
	
};
function busq_any_inv(n,Function,All)
{
	//alert(n.val());
	var $val=$('#Qtab');
	if($val.length!=0){$val.remove();}
	
	if(!esVacio(n.val())){
		//alert('Si!');
	  $.ajax({
		url:'ajax/busq_art_any.php',
		data:{busq:n.val(),F:Function,All:All},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
			//$('#Qresp').html(text);
			open_pop('Resultado Busqueda','',text);
			
			
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
function playAlert(idSound){
	
	$('#'+idSound)[0].play();
	var audio = new Audio('media/error1.mp3');
	audio.play();
	
};

function subirFormyBoton(URL,formID,BotonName,BotonValue) {
	var formulario = $('#'+formID).serialize();
	var boton = formulario!='' ? '&'+BotonName+'='+BotonValue : BotonName+'='+BotonValue;
	formulario = formulario + boton;

	location.assign(URL+'?'+formulario);
}

// Facturacion electronica
var enviaMail = 1;


function enviaCorreoFe(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente)
{
    
    $.when(XML(num_fac,pre,hash,cod_su,serial_fac), enviaCorreo(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente)).done(function(ajax1Results,ajax2Results){
    //this code is executed when all ajax calls are done
	
   });
	
}

function enviaCorreo(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente)
{
	var modal = UIkit.modal.blockUI("Enviando correo...");
	$.ajax({
		url:'ajax/correos/enviarMail.php',
		data:{n_fac_ven:num_fac,prefijo:pre,hashFacVen:hash,codSuc:cod_su,serial_fac:serial_fac,mailDestino:mailDestino,idCliente:idCliente},
		type:'POST',
		dataType:'TEXT',
		success:function(response){
                    modal.hide();
				    simplePopUp('Respuesta: '+response);
					waitAndReload();

				
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
	
}

function XML(num_fac,pre,hash,cod_su,serial_fac)
{
	
	$.ajax({
		url:'ajax/correos/creaXML.php',
		data:{num_fac:num_fac,prefijo:pre,hash:hash,codSuc:cod_su,serial_fac:serial_fac},
		type:'POST',
		dataType:'TEXT',
		success:function(response){	
			
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
	
}
function verificaFacElec(num_fac,pre,hash,cod_su,serial_fac)
{
	var modal = UIkit.modal.blockUI("Verificando petici&oacute;n");
	$.ajax({
		url:'ajax/FE/consultaTest.php',
		data:{num_fac:num_fac,prefijo:pre,hash:hash,codSuc:cod_su,serial_fac:serial_fac},
		type:'POST',
		dataType:'JSON',
		success:function(response){
                if(response.isValied == true){
					modal.hide();
				    warrn_pop('Respuesta: '+response.StatusMessage);
				} else {
				    modal.hide();
				    warrn_pop('Error: '+response.ErrorMessage);
				}
				
			
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
	
}
function SEND_facElec(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente)
{
	var modal = UIkit.modal.blockUI("Enviando Factura a la DIAN, por favor espere...");
	$.ajax({
		url:'ajax/FE/enviaFacElec.php',
		data:{num_fac:num_fac,prefijo:pre,hash:hash,codSuc:cod_su,serial_fac:serial_fac},
		type:'POST',
		dataType:'JSON',
		success:function(response){
			if(response.success==true && response.message!='Documento con errores en campos mandatorios.'){
				modal.hide();
                simplePopUp(response.message);
				waitAndReload();
				if(enviaMail==1){
					//enviaCorreoFe(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente);
					}
				else {
					waitAndReload();
				}
                
			}else {
				modal.hide();
				warrn_pop('Error: '+response.message+', '+response.Error);
				var $modalPopUp = $.UIkit.modal("#modal");
				$modalPopUp.on({

					'show.uk.modal': function(){
					   
					},
				
					'hide.uk.modal': function(){
						
						waitAndReload();
					}
				});
				
			}
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
	
};
function RESEND_facElec(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente)
{
	var modal = UIkit.modal.blockUI("Verificando petici&oacute;n");
	$.ajax({
		url:'ajax/FE/reEnviaFacElec.php',
		data:{num_fac:num_fac,prefijo:pre,hash:hash,codSuc:cod_su,serial_fac:serial_fac},
		type:'POST',
		dataType:'JSON',
		success:function(response){
			if(response.success==true && response.message!='Documento con errores en campos mandatorios.'){
				modal.hide();
                simplePopUp(response.message);
                waitAndReload();
			}else {
				modal.hide();
				warrn_pop('Error: '+response.message+', '+response.Error);
				
			}
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
	
};
function RESEND_mail(num_fac,pre,hash,cod_su,serial_fac,mailDestino,idCliente)
{
	var modal = UIkit.modal.blockUI("Verificando petici&oacute;n");
	$.ajax({
		url:'ajax/FE/reEnviaCorreo.php',
		data:{num_fac:num_fac,prefijo:pre,hash:hash,codSuc:cod_su,serial_fac:serial_fac},
		type:'POST',
		dataType:'JSON',
		success:function(response){
			if(response.success==true){
				modal.hide();
                simplePopUp(response.message);
                waitAndReload();
			}else {
				modal.hide();
				warrn_pop('Error: '+response.message+', '+response.Error);
				
			}
			
			},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
	
};
function cambia_resol(nf,pre,opt,ID)
{
	var datos='num_fac='+nf+'&pre='+pre+'&resol='+opt+'&id='+ID;
	if(confirm("Si cambia la numeracion, puede generar saltos entre los seriales, desea Proceguir?"))
	{
		exec_Cresol(datos);
	}

		
}

function exec_Cresol(datos){
	
	ajax_x('ajax/cambia_resol_fac.php',datos,function(resp){
		
		var r=resp*1;
		if(r==1){
			
			open_pop3('Operacion Exitosa','Tenga en cuenta que el Numero de Factura Anterior se ha PERDIDO y quedara un faltante en la numeracion','',0);
			waitAndReload();
		}
		});
	
}

function selectAllText(textbox) {
	textbox.focus();
	textbox.select(); 
}