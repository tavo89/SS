<script language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript1.5" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
var traslado=0;

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


cambioTFC($('#tipo_fac'),$('#tab_compra'));


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

	
	
	// When clicking on the button close or the mask layer the popup closed
	
});



$('input').on("change",function(){$(this).prop('value',this.value);});
$('textarea').on("change",function(){$(this).html(this.value);});
</script>
