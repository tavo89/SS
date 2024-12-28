<script language="javascript1.5" type="text/javascript" src="JS/balupton-jquery-scrollto-a30313e/lib/jquery-scrollto.js" ></script>	
<script type="text/javascript" language="javascript1.5">
var index=0;
$(document).keydown(function(e) {
	//alert(e.keyCode);
    if (e.keyCode === 40) {
        index = (index + 1 >= $('tr.can_be_selected').length) ? $('tr.can_be_selected').length - 1 : index + 1;
        $('tr.can_be_selected').removeClass('background_color_grey');
        $('tr.can_be_selected:eq(' + index + ')').addClass('background_color_grey');
		$('tr.can_be_selected:eq(' + index + ')').ScrollTo({
    onlyIfOutside: true
});
		//$('tr.can_be_selected:eq(' + index + ')').click();
        return false;
    }
    if (e.keyCode === 38) {
        index = (index == 0) ? 0 : index - 1;
        $('tr.can_be_selected').removeClass('background_color_grey');
        $('tr.can_be_selected:eq(' + index + ')').addClass('background_color_grey');
		$('tr.can_be_selected:eq(' + index + ')').ScrollTo({
    onlyIfOutside: true
});
		//$('tr.can_be_selected:eq(' + index + ')').click();
        return false;
    }
	if (e.keyCode === 13) {
   		//alert('Enter!');
		$('tr.can_be_selected:eq(' + index + ')').click();
        return false;
    }
	if (e.keyCode === 113) {
   	//alert('click');
		$('#addplus').click();
        return false;
    }
	if (e.keyCode === 115) {
   
		//$('#entrega').focus();
        return false;
    }
});
</script>