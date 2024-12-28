<?php
include("Conexxx.php");
//add_sede(2);
$totalRegistros  = 0;
$limit           = 100;
$sql = "SELECT COUNT(*) as total FROM inv_inter WHERE nit_scs='$codSuc'";
$rs  = $linkPDO->query($sql);
while ($row = $rs->fetch()) 
{ 
	$totalRegistros = $row['total'];
}

$totalPag = ceil($totalRegistros/$limit);
?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("HEADER.php"); ?>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php //include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<div class="grid-100 posicion_form">
<h1 align="center">Ajustar Kardex</h1>
<h2 align="center">Total Referencias: <?php echo "$totalRegistros"; ?></h2>
<form action="depuraKardex.php" method="post" id="frm" class="uk-form">

<input type="text" name="totalPaginasOffset" id="totalPaginasOffset" class="uk-hidden" value="<?php echo $totalPag; ?>">
<input type="text" name="limiteOffset" id="limiteOffset"  class="uk-hidden" value="<?php echo $limit; ?>">
<input type="text" name="totalRegistros" id="totalRegistros"  class="uk-hidden" value="<?php echo $totalRegistros; ?>">

<div class="uk-grid">
<div class="uk-width-large-1-2">
<input type="button" name="ajustar" id="ajustar" class="uk-button  uk-button-large uk-button-success uk-width-1-1" value="Ajustar Kardex" onClick="ajustarKardex($('#totalPaginasOffset'),';')">
</div>

<div class="uk-width-large-1-2">
<input type="button" name="verificar" id="verificar" class="uk-button  uk-button-large uk-width-1-1" value="Verificar kardex" onClick="verificarKardex($('#totalPaginasOffset'),';')">
</div>

</div>


<div id="mensaje">
</div>


  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="0" id="num_d" />
    <input type="hidden" value="" name="html" id="pagHTML">
</form>
</div>
<?php include_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >

var paginaActual=1;
var GlobalOffSet=0;
var limit= $('#limiteOffset').val();
var totalRegistros= $('#totalRegistros').val();                  
var totalPaginasOffSet = $('#totalPaginasOffset').val();

function cola_exe()
{
	if(1){
		
	    var data='limit='+limit+'&offset='+GlobalOffSet;
	    ajax_x('ajax/MANAGER/ajustaRef.php',data,function(resp){
	 		var html='';
			var $html='';
		
			var r=parseInt(resp);
			
			if(paginaActual<=totalPaginasOffSet){
				paginaActual++;
				GlobalOffSet= (paginaActual-1)*limit;
				//console.log('RS------> GlobalOffSet:'+GlobalOffSet+', totalPaginasOffSet:'+totalPaginasOffSet+', limit:'+limit+', paginaActual:'+paginaActual);
				}
			
			var porcentaje=redondeox((paginaActual/totalPaginasOffSet)*100,0);
			console.log('GlobalOffSet:'+GlobalOffSet+', totalPaginasOffSet:'+totalPaginasOffSet+', limit:'+limit+', paginaActual:'+paginaActual);
			
			if(paginaActual<=totalPaginasOffSet){
				$('#progress_bar').css("width", porcentaje+"%").html(porcentaje+"%");
				cola_exe();
			}
		
		});
		
		
		
		
	}
	
};

function ajustarKardex($data,separador)
{

	var i=0;
	var html='';
	var $html='';

	//for(i=0;i<n;i++){
		
		html= '<div class="uk-progress uk-progress-success"><div id="progress_bar" class="uk-progress-bar" style="width: 0%;">0%</div></div>';
		$html=$(html);
		$html.appendTo('#mensaje');
	
	
	cola_exe();
		
		
//}
};





var cd=0;
var $nd=$('#num_d');

$('input').on("change",function(){
	
	$(this).prop('value',this.value);
});

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});



	
	});


</script>

</body>
</html>