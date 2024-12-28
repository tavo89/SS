<!--<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />

<script language="javascript1.5" type="text/javascript" src="JS/jquery-2.1.1.js<?php  echo "?$LAST_VER"; ?>"></script>

<script src="JS/jquery-2.1.1.js"></script>
-->


<script src="JS/jquery-2.1.1.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script language="javascript" type="text/javascript">
var key;
$(document).keydown(function(e) { 
  c=e.keyCode;       
    if (c == 27) {
        window.close();
    }
	else if(c == 13)imprimir();
});
function imprimir(){
$('#imp').hide();
window.print();
$('#imp').show();
};



</script>
