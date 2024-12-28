<?php 
require_once("Conexxx.php");
if($rolLv!=$Adminlvl  ){header("location: centro.php");}
$busq="";
$val="";
$val2="";
$val3="";
$val4="";
$boton="";
$valFilter=r("filter");


if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];

if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2= $_REQUEST['valor2'];
if(isset($_REQUEST['valor3']))$val3= $_REQUEST['valor3'];
if(isset($_REQUEST['valor4']))$val4= $_REQUEST['valor4'];


 


$tabla="cuentas_dinero";
$col_id="id_cuenta";
$columns="*";
$url="cuentas_bancos.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_cta.php";
$url_new="#add_any";
$pag="";
$limit = 20; 
$order="id_cuenta";
 

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) {$pag = 1; } 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM $tabla WHERE cod_su=$codSuc ORDER BY id_cuenta LIMIT $offset, $limit"; 

 
 
$sqlTotal = "SELECT COUNT(*) as total FROM $tabla WHERE cod_su='$codSuc'"; 
$rs = $linkPDO->query($sql ); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){
$sql_busq="SELECT $columns FROM $tabla WHERE (nombre_cta LIKE '%$busq%' OR cod_cta LIKE '%$busq%' )  AND cod_su=$codSuc";
$rs=$linkPDO->query($sql_busq );
}
	

 
?>
<!DOCTYPE html>
<html  >
<head>
 

 
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<?php require_once("HEADER.php"); ?>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">


<!-- Lado Izquierdo del Navbar -->

<nav class="uk-navbar">
<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 


<!-- Centro del Navbar -->

	<ul class="uk-navbar-nav uk-navbar-center" style="width:520px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
	
	
		<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_add")) && $codSuc>0){?>
<li class="ss-navbar-center"><a href="<?php echo $url_new ?>"  data-uk-modal><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Nueva Cta.</a></li>
		<?php } ?>
		<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>

	</ul>

		<!-- Lado derecho del Navbar -->
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="opc" class="uk-button uk-button-primary">
			</form>
		</div>

	
	
</nav>

<div id="add_any" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>
<h1 class="uk-text-primary uk-text-bold">Nueva Cuenta</h1>
<form class="uk-form uk-form-stacked" id="frm_any" name="frm_any">

    <fieldset data-uk-margin>
         
        <div class="uk-form-row">
        <label class="uk-form-label" for="f0">Nombre Cuenta</label>
        <input name="c0" id="f0" type="text" placeholder="Nombre Cta." >
        
        <label class="uk-form-label" for="f1">Clase</label>
        <select name="c1" id="f1">
             
            <option value="Banco" selected>Banco</option>
			<option value="App">App</option>
            <!--
            <option value="Ingresos Ventas">Ingresos Ventas</option>
            -->
        </select>
        
        <label class="uk-form-label" for="f2">Tipo Cuenta</label>
        <select name="c2" id="f2">
            <option value="" selected>Tipo</option>
            <option value="AHORROS">AHORROS</option>
            <option value="CORRIENTE">CORRIENTE</option>
			<option value="APP">APP</option>
        </select>
        
        <label class="uk-form-label" for="f3">Num. Cuenta</label>
         <input name="c3" id="f3" type="text" placeholder="Numero Cta." onChange="validar_c($(this),'<?php echo "$tabla" ?>','cod_cta','Este Numero de Cuenta ya Existe!');">
        </div>
         <div class="uk-form-row">
         <label class="uk-form-label" for="f4">Saldo Cuenta</label>
         <input name="c4" id="f4" type="text" placeholder="SALDO $" onKeyUp="//puntoa($(this));">
         
         <label class="uk-form-label" for="f5">Fecha Inicio Saldo</label>
         <input name="c5" id="f5" type="date" placeholder="">
         <input name="c6" id="f6" type="hidden" placeholder="" value="<?php echo "$codSuc" ?>">
         </div>
        <div class="uk-form-row">
        <span class=" uk-button uk-button-primary uk-button-large"   onClick="save_any2(document.forms['frm_any'],valCta,successAny)" >Guardar</span>
        <input type="hidden" name="numF" id="numF" value="7">
        <input type="hidden" name="Colset" id="Colset" value="nom_cta,tipo_cta,clase_cta,cod_cta,saldo_cta,fechaI_extractos,cod_su">
        <input type="hidden" name="tab" id="tab" value="cuentas_dinero">
        </div>
         
    </fieldset>

</form>
</div>
</div>
<?php

if($boton=="Imprimir")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_tras']=$val;
	$_SESSION['suc_orig']=$codSuc;
	$_SESSION['pag']=$pag;
	popup("imp_tras.php","Traslado No. $val", "900px","650px");
};


?>
<h1 align="center">CUENTAS</h1>
 

<form action="<?php echo $url ?>" method="post" name="form">
<div   style="display:inline-block;" align="center">
	<table   cellpadding="0" cellspacing="1" class="creditos_filter_table" align="center">
	<thead>
	<TR bgcolor="#CCCCCC">
	<TH colspan="5" align="center">Fecha </TH>

	</TR>
	</thead>
	<tbody>
	<tr>
	<td>Ini:
	</td>
	<td>
	<input type="date" name="fechaI" id="fechaI" value=" "  style="width:135px;color:black;"  >
	</td>
	<td>Fin:
	</td>
	<td>
	<input type="date" name="fechaF" id="fechaF" value=" " style="width:135px;color:black;">
	</td>
	 
	</tr>
	</tbody>
	</table>
</div>


<div class="uk-overflow-container">
<?php //require_once("PAGINACION.php"); ?>	
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<th width="90px">#</th>
<td></td>
<th width="200">Nombre</th>

<th width="200" class="uk-visible-large">Tipo</th>
<th width="200" class="uk-visible-large">Clase.</th>


<th width="150" class="uk-visible-large">N&uacute;mero</th>
<th width="200">SALDO</th>
<th width="150" class="uk-visible-large">Fecha Corte</th>
 

       </tr>
 </thead>       
 <tbody> 
      
<?php 
$bgAuth="#FFFFFF";
$totCtas=0;
while ($row = $rs->fetch()) 
{ 
$ii++;
		    $ID = $row["id_cuenta"];
            
            $cod_su = $row["cod_su"];
			 
			$nomCta = $row["nom_cta"];

			$numCta = $row["cod_cta"];
			$tipoCta =$row["tipo_cta"];
			$classCta = $row["clase_cta"];
			$saldoCta=$row['saldo_cta'];
			$totCtas+=$saldoCta;
			$fechaI_corte=$row['fechaI_extractos'];
		 
			
			$Funct="ver_mvtos('$ID','$nomCta','$tipoCta','$numCta')";
			
			//$Funct="ver_mvtos('$ID','$nomCta','$tipoCta','$numCta')";
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td >
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="#" class="uk-icon-list uk-icon-button uk-icon-hover uk-icon-small" onClick="<?php echo $Funct ?>">

</a>
</td>
 
</tr>
</table>


</td>             
<td width="200px"><?php echo $nomCta; ?></td>
 
<td class="uk-visible-large"><?php echo $tipoCta; ?></td>
<td class="uk-visible-large"><?php echo $classCta; ?></td> 
 

<td class="uk-visible-large"><?php echo $numCta; ?></td>
<td><?php echo money3($saldoCta); ?></td>
<td class="uk-visible-large"><?php echo $fechaI_corte; ?></td>
 
</tr> 
         
<?php 
         } 
      ?>
      <tr class="uk-text-large -uk-text-bold uk-heading-large" bgcolor="#FFF">
 
<td colspan="8" class=""><?php echo money3($totCtas); ?></td>
</tr> 
 
 </tbody>
</table>
</div>
</form>
<?php require_once("FOOTER_UK.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>

<script type="text/javascript" language="javascript1.5" >

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});

});



function valCta(form){
	
	
	if(esVacio(form.f1.value)){simplePopUp('Ingrese Nombre de la CUENTA');form.f1.focus();return 0;}
	else if(esVacio(form.f2.value)){simplePopUp('Seleccione CLASE de CUENTA');form.f2.focus();return 0;}
	else if(esVacio(form.f3.value)){simplePopUp('Tipo de CUENTA');form.f3.focus();return 0;}
	else if(esVacio(form.f4.value)){simplePopUp('Ingrese Numero de CUENTA');form.f4.focus();return 0;}
	else if(esVacio(form.f5.value)){simplePopUp('SALDO ');form.f5.focus();return 0;}
	else if(esVacio(form.f6.value)){simplePopUp('Ingrese Fecha de CORTE');form.f6.focus();return 0;}
	return 1;
	
	
};

function ver_mvtos(id,nomCta,tipoCta,numCta)
{
	var Fi=$('#fechaI').val();
	var Ff=$('#fechaF').val();
	var data='id='+id+'&fi='+Fi+'&ff='+Ff;
	var URL='ajax/POP_UPS/mvtos_ctas.php';
	ajax_x(URL,data,function(resp){open_pop3('Movimientos cuenta '+nomCta,tipoCta+' / '+numCta,resp,1);addTable();});
	
};

 
</script>
<script src="JS/bootstrap.min.js"></script>
<script src="JS/plugins/dataTables/datatables.min.js"></script>
<script language="javascript1.5" type="text/javascript">
function addTable(){
var rangoFecha=' ';

var Fi=$('#fechaI').val();
var Ff=$('#fechaF').val();

if(!esVacio(Ff) && !esVacio(Fi)){rangoFecha=Fi+' a '+Ff;}

$('.tablaDataTables').DataTable({
			language: {url: 'locales/es.json'},
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                     
                    {extend: 'csv'},
                    {extend: 'excelHtml5', title: '<?php echo "$NOM_NEGOCIO"; ?> Libro Diario '+rangoFecha,footer:true, customize: function ( xlsx ){
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
				//$( 'xf[numFmtId=9]', xlsx.xl['styles.xml'] ).attr( 10 );
 
                // jQuery selector to add a border
                $('row c[r*="10"]', sheet).attr( 's', '25' );
				
				
				// Loop over the cells in column `F`
                $('row c[r^="F"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    if ( $('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000 ) {
                        $(this).attr( 's', '20' );
                    }
                });
            }},
                  

                    {extend: 'print',footer:true,title: 'Libro Diario '+rangoFecha,
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });
	}
	



$(document).ready(function()
	{
		

	}
	);
	
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>

<?php //require("PAGINACION.php"); ?>	

</body>
</html>