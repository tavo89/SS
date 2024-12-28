<?php
include("DB.php");
$usar_fecha_vencimiento=0;
$fechaKardex="2012-01-01";
$VER_PROGRAMA="4.9.530102018";
$FECHA_ACTUALIZAR_SW="2018-10-30";
$LAST_VER="2410201821222";
$CHAR_SET="UTF-8";
include("offline_LIB.php");

function UpdateDbSql($sql)
{
	t1($sql);
	
	};



$boton=r("Guardar");
if($boton=="Guardar"){
$SQL=trim($_REQUEST["SQL"]);

$aux=explode(";",$SQL);

$n=count($aux);

for($i=0;$i<$n;$i++){


try{
t1($aux[$i]);
}
catch(Exception $e){} 


}
	
	echo "FINISH";
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
 
<div class="uk-width-9-10 uk-container-center">
<div class="grid-100 posicion_form">
<h1 align="center">DB UPDATE [<?php echo $USU;?>]</h1>
<form action="UPLOAD_DB.php" method="post" id="frm" class="uk-form">
<textarea name="SQL" id="SQL" cols="30" rows="10" style="width:400px;"></textarea>
<input type="button" name="Guardar" value="Guardar OneLiners" class="uk-button uk-button-primary uk-button-large" onClick="sumit($('#SQL'),';');">
<input type="button" name="Guardar" value="Guardar Bloques" class="uk-button uk-button-primary uk-button-large" onClick="sumit($('#SQL'),'¬');">
<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="0" id="num_d" />
    <input type="hidden" value="" name="html" id="pagHTML">
</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >

var cont_i=0;
var Global_txt='';
function cola_exe(n)
{
	if(1){
	var data=('SQL='+Global_txt[cont_i]);
	//alert(data);
	//alert(n);
	//alert(cont_i);
	ajax_x('ajax/MANAGER/ejecon.php',data,function(resp){
		var html='';
		var $html='';
		
		var r=parseInt(resp);
		
		
		
		if(r==1)
		{
			html='Ok '+cont_i+' '+resp;
			
			}else{html='<span style="color:white; font-size:12px;"><b>'+resp+'</b></span>';}
		
			
			$html=$('<li>'+html+'</li>');
			$html.appendTo('#mensaje');
			
			
			cont_i++;
			
			var porcentaje=(cont_i/n)*100;
			porcentaje=redondeox(porcentaje,0);
			$('#progress_bar').css("width", porcentaje+"%").html(porcentaje+"%");
			if(cont_i<n){cola_exe(n);}
		
		});
		
		
		
		
	}
	
};

function sumit($data,separador)
{
	var text=$data.val().trim();
	Global_txt=text.split(separador);
	var n=Global_txt.length;
	var i=0;
	var html='';
	var $html='';
	simplePopUp(n+' Consultas');
	//for(i=0;i<n;i++){
		
		html= '<div class="uk-progress uk-progress-success"><div id="progress_bar" class="uk-progress-bar" style="width: 0%;">0%</div></div>';
		$html=$(html);
		$html.appendTo('#mensaje');
	
	
	cola_exe(n);
		
		
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

function add_dcto()
{
	//var fab=fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Fabricante:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
	var $d=$(html);
	$d.appendTo('#descuentos');
	fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	cd++;
	
	$nd.prop('value',cd);
	
	
};
function d(cant)
{
   var c=100+cant*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   $dcto=$('#dcto');
   $dcto.prop('value',d);
};

</script>

</body>
</html>