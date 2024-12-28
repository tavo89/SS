<div class="uk-modal facturarClienteSS" id="facturarClienteSS">
  <div class="uk-modal-dialog" style="font-size: 22px;"> <a class="uk-modal-close uk-close"></a>
    <div class="uk-modal-header uk-text-center uk-text-bold uk-text-large uk-text-warning" style="font-size: xx-large;">SU HOSTING ESTÁ APUNTO DE VENCER</div>
    <span class="">Por favor pagar <b><span class=" uk-text-primary" id="valorFactura"></span></b> antes de <span id="fechaCorte"></span> ( <b><span class=" uk-text-danger" id="diasRestantes"></span></b> d&iacute;as restantes)</span> 
    <br>
    <span class=" ">Este sistema ser&aacute; <span class=" uk-text-danger">inaccesible</span> después del vencimiento del HOSTING.</span>
    <br>
     <br>
    Sucursales:
    <br>
    <div id="sucursalesBox"></div>
    <br>
    <span style="font-size: larger;">Medios de pago </span>
    <br>
    <img src="Imagenes/nequiLogo.png" style="height:100px;"><i class="uk-icon-phone"></i> 320 810 9739&nbsp;&nbsp; <img src="Imagenes/QR_NequiGustavoBocanegra.png" style="height:120px;"> <br>
    <img src="Imagenes/daviPlataLogo.png" style="height:100px;"> <i class="uk-icon-phone"></i> 320 810 9739 <br>
    <br>
    <div class="">Escriba a &nbsp; <i class="uk-icon-whatsapp"></i> 320 810 9739 &nbsp; para notificar su pago, gracias.</div>
    <br>
    <div class="uk-modal-footer"> Contacto: Gustavo Bocanegra <br>
      <i class="uk-icon-whatsapp"></i> 320 810 9739 &nbsp;&nbsp;&nbsp; 
      <i class="uk-icon-envelope-o"></i> soporte@nanimosoft.com </div>
  </div> 
</div>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function() {
	var modalFacturaSS = UIkit.modal(".facturarClienteSS");
	
	function consultaSaldo(){
		$.ajax({
			 type: 'POST',
			 url: 'ajax/WARNINGS/alertaClienteSS.php',
			 data: {noData:''},
			 dataType:'JSON',
			 success: function(response){
				 console.log(response);
				 var resp=response.alerta*1;
				 if(resp==1){
				 	$('#valorFactura').text(response.totalFactura);
					$('#sucursalesBox').html(response.datosSucursales);
					$('#fechaCorte').text(response.fechaCorte);
					$('#diasRestantes').text(response.diasRestantes);
					modalFacturaSS.show();
				 }
				 
				
				
			},
				error:function(xhr,status){simplePopUp('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');}
		});
	}
	
	consultaSaldo();
				  
});			
				
</script>