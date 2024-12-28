<?php
require_once("Conexxx.php");
$boton=r("boton");
$fechaI="";
$fechaF="";


$tipoGastos="";
if(isset($_REQUEST["tipo_gastos"])){$tipoGastos=$_REQUEST["tipo_gastos"];}
$FILTRO_GASTOS="";
if($boton=="Seleccionar Gastos")
{
	
$FILTRO_GASTOS=multiSelcSql($tipoGastos,"tipo_gasto");
//echo "gastos: $FILTRO_GASTOS";	
}

$VER_INGRESOS=1;

$year=r("anio");
$sem=r("sem");
$type=r("type");
if($type=="B"){$type="'stacked:gradient' : 'stacked'";}
else {$type="'grouped:gradient' : 'grouped'";}
//CONCAT('2015-02-',DAY(last_day(fecha))) AS ...

if($boton=="Actualizar"){
	
	$_SESSION["year_graf01"]=$year;
	$_SESSION["sem_graf01"]=$sem;
	$_SESSION["type_graf01"]=$type;
	
	
}

if(isset($_SESSION["year_graf01"]) && !empty($_SESSION["year_graf01"]))
{
	$year=$_SESSION["year_graf01"];
	$sem=$_SESSION["sem_graf01"];
	$type=$_SESSION["type_graf01"];
}

if(empty($year) || empty($sem) || empty($type)){
	
	$sql="SELECT MAX(YEAR(fecha)) as year FROM fac_venta WHERE nit=$codSuc";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$year=$row["year"];
	$sem=1;
	$type="'grouped:gradient' : 'grouped'";
	}
	}

$tip="";
if($type=="'grouped:gradient' : 'grouped'"){$tip="Agrupadas";}
else {$tip="Apiladas";}

$ventas[]=0;
$compras[]=0;
$egre[]=0;
$utilidad[]=0;

$i=1;$ii=1;$once=0;
$valor=0;
$valorIngresos=0;
$valorVentas=0;
for($i=1;$i<=12;$i++)
{
	
	if($once==0){if($sem==1){$i=1;}else {$i=7;}$once=1;}
	
	if($i<10){$mes="0".$i;}
	else {$mes=$i;}
	
	if($VER_INGRESOS==0){$filtroFac="";}
	else $filtroFac=" AND tipo_venta!='Credito '";
	
	$sql="SELECT IFNULL(sum(tot),0) sum FROM fac_venta WHERE nit=$codSuc $filtroFac AND DATE(fecha)>='$year-$mes-01' AND DATE(fecha)<=CONCAT('$year-$mes-',DAY(last_day(fecha))) AND ".VALIDACION_VENTA_VALIDA."";
	//echo "FAC.<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{$valorVentas=$row["sum"];$ventas[$ii]=$valorVentas;}
	else {$ventas[$ii]=0;$valorVentas=0;}
	
	
	if($VER_INGRESOS==1){
	
	$sql="SELECT IFNULL(sum(valor),0) sum FROM comprobante_ingreso WHERE cod_su=$codSuc AND DATE(fecha)>='$year-$mes-01' AND DATE(fecha)<=CONCAT('$year-$mes-',DAY(last_day(fecha))) AND anulado!='ANULADO'";
	//echo "<li>$sql</li>";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{$valorIngresos=$row["sum"];$ventas[$ii]+=$valorIngresos;}
	else {$valorIngresos=0;}
	
	}
	
	
	
	//AND tipo_gasto!='Facturas Proveedores'
	$sql="SELECT IFNULL(sum(valor),0) sum FROM comp_egreso WHERE cod_su=$codSuc AND tipo_gasto!='Consignacion Ventas' AND tipo_gasto!='Transferencia Entre Cuentas'  $FILTRO_GASTOS  AND DATE(fecha)>='$year-$mes-01' AND DATE(fecha)<=CONCAT('$year-$mes-',DAY(last_day(fecha))) AND anulado!='ANULADO'";
	$rs=$linkPDO->query($sql);
	//echo "<li>$sql</li>";
	if($row=$rs->fetch())
	{$valorEgresos=$row["sum"];$egre[$ii]=$valorEgresos;}
	else {$egre[$ii]=0;$valorEgresos=0;}
	
	
	/*
	$rs=$linkPDO->query("SELECT IFNULL(sum(tot),0) sum FROM fac_com WHERE cod_su=$codSuc AND tipo_fac!='Inventario Inicial' AND DATE(fecha)>='$year-$mes-01' AND DATE(fecha)<=CONCAT('$year-$mes-',DAY(last_day(fecha)))");
	if($row=$rs->fetch())
	{$valor=$row["sum"];$compras[$ii]=$valor;}
	else {$compras[$ii]=0;}
	*/
	$utilidad[$ii]=($valorIngresos+$valorVentas)-$valorEgresos;
	
	
	$ii++;
	if($ii>6)break;
	
}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
<link type="text/css" href="PLUG-INS/Jit/Examples/css/base.css" rel="stylesheet" />
<link type="text/css" href="PLUG-INS/Jit/Examples/css/BarChart.css" rel="stylesheet" /> 

<link href="css/multi-select.css" rel="stylesheet" type="text/css" />

</head>
<body class="ui-content" >
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<nav class="uk-navbar">
<a class="uk-navbar-brand uk-visible-large" href="#"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a>
<div class="uk-navbar-content uk-text-large"><i class="uk-icon-navicon"></i>&nbsp;<a href="#filtros" data-uk-modal>Egresos</a>.</div>
</nav>

<div class="grid-100 posicion_form">
<div id="container">
<div id="left-container">



        <div class="text">
        <h2 style="color:#06F">
Ingresos y Egresos<br> A&ntilde;o <?php echo $year; ?>   Semestre <?php echo $sem; ?>   
        </h2> 

            Las barras  representan el Total por meses de :
            <li>Ventas de Contado y Recaudo de Cartera.</li>
            <li> Compras.</li>
            <li>Egresos.</li>
            <br><br>
            
          
            
                        
        </div>
        <ul id="id-list"></ul>
         <br /><br />
             <form  method="post" name="form_report">
             <table cellspacing="0" cellpadding="1" style="font-size:12px">
             <tr>
             <td>
            A&ntilde;o:
            </td>
            <td>
            <select name="anio">
            <option value="<?php echo $year; ?>" selected><?php echo $year; ?></option>
           <?php
		   
		   $sql="SELECT YEAR(fecha) as year FROM fac_venta WHERE nit=$codSuc GROUP BY year ORDER BY year DESC";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch()){
	$year=$row["year"];
	echo "<option value=\"$year\">$year</option> ";;
	}
		   
		   
		   ?>
        
            </select>
            </td>
         </tr>
         <tr>
         <td>
            Semestre:
            </td>
            <td>
             <select name="sem">
             <option value="<?php echo $sem; ?>" selected><?php echo $sem; ?></option>
            <option value="1">1</option>
            <option value="2">2</option>
            </select>
            </td>
            </tr>
            <tr>
         <td colspan="2">
          Gr&aacute;fica:
            
             <select name="type">
             <option value="<?php echo $type; ?>" selected><?php echo $tip; ?></option>
            <option value="A">Barras Agrupadas</option>
            <option value="B">Barras Apiladas</option>
            </select>
            </td>
            </tr>
            <tr>
            <td colspan="2" align="center"><input type="submit" value="Actualizar" name="boton"></td>
            </tr>
            </table>
           

        <a id="update" href="#" class="theme button white" style="visibility:hidden">Update Data</a>


<div style="text-align:center; visibility:hidden"><a href="example1.js">See the Example Code</a></div>            
</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="right-container">

<div id="inner-details"></div>

</div>

<div id="log"></div>
</div>


<div id="filtros" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>

    <h1 class="uk-text-primary uk-text-bold"><i class="uk-icon-check-circle"></i>&nbsp;Egresos</h1>
    
     <select multiple="multiple" id="tipo_gasto" name="tipo_gastos[]" onChange="" class="">
	 <option value="">TODOS</option>
	 <?php echo tipoEgresoOpt();?></select>
       <input type="submit" value="Seleccionar Gastos" name="boton" class="uk-button uk-button-primary" onClick="//submit();">   
    </div>
</div>

 </form>

<?php require_once("FOOTER.php"); ?>	
<script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	
$('#tipo_gasto').multiSelect();
	
});
  </script>
<!-- JIT Library File -->
<script language="javascript" type="text/javascript" src="PLUG-INS/Jit/jit-yc.js"></script>

<!-- Example File -->
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	
	init();
	
	});
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


var labelType, useGradients, nativeTextSupport, animate;

var anio=<?php echo $year; ?>;
var semestre=<?php echo $sem; ?>;
(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(){
  //init data
if(semestre==1){
  var json = {
      'label': ['Ingresos', 'Utilidad', 'Egresos'],
      'values': [
      {
        'label': '<?php echo $MESES[1] ?>',
        'values': [<?php echo $ventas[1] ?>, <?php echo $utilidad[1] ?>, <?php echo $egre[1] ?>]
      }, 
      {
        'label': '<?php echo $MESES[2] ?>',
        'values': [<?php echo $ventas[2] ?>, <?php echo $utilidad[2] ?>, <?php echo $egre[2] ?>]
      }, 
      {
        'label': '<?php echo $MESES[3] ?>',
        'values': [<?php echo $ventas[3] ?>, <?php echo $utilidad[3] ?>, <?php echo $egre[3] ?>]
      }, 
      {
        'label': '<?php echo $MESES[4] ?>',
        'values': [<?php echo $ventas[4] ?>, <?php echo $utilidad[4] ?>, <?php echo $egre[4] ?>]
      }, 
      {
        'label': '<?php echo $MESES[5] ?>',
        'values': [<?php echo $ventas[5] ?>, <?php echo $utilidad[5] ?>, <?php echo $egre[5] ?>]
      }, 
      {
        'label': '<?php echo $MESES[6] ?>',
        'values': [<?php echo $ventas[6] ?>, <?php echo $utilidad[6] ?>, <?php echo $egre[6] ?>]
      }]
      
  };
  //end
}//fin If Semestre
else{
  var json = {
      'label': ['Ingresos', 'Utilidad', 'Egresos'],
      'values': [
    
      {
        'label': '<?php echo $MESES[7] ?>',
        'values': [<?php echo $ventas[1] ?>, <?php echo $utilidad[1] ?>, <?php echo $egre[1] ?>]
      }, 
      {
        'label': '<?php echo $MESES[8] ?>',
        'values': [<?php echo $ventas[2] ?>, <?php echo $utilidad[2] ?>, <?php echo $egre[2] ?>]
      }, 
      {
        'label': '<?php echo $MESES[9] ?>',
        'values': [<?php echo $ventas[3] ?>, <?php echo $utilidad[3] ?>, <?php echo $egre[3] ?>]
      }, 
      {
        'label': '<?php echo $MESES[10] ?>',
        'values': [<?php echo $ventas[4] ?>, <?php echo $utilidad[4] ?>, <?php echo $egre[4] ?>]
      }, 
      {
        'label': '<?php echo $MESES[11] ?>',
        'values': [<?php echo $ventas[5] ?>, <?php echo $utilidad[5] ?>, <?php echo $egre[5] ?>]
      },
	  
	    {
        'label': '<?php echo $MESES[12] ?>',
        'values': [<?php echo $ventas[6] ?>, <?php echo $utilidad[6] ?>, <?php echo $egre[6] ?>]
   		}]
      
  };
  //end
}//fin else-semestre



  var json2 = {
      'values': [
      {
        'label': 'date A',
        'values': [10, 40, 15, 7]
      }, 
      {
        'label': 'date B',
        'values': [30, 40, 45, 9]
      }, 
      {
        'label': 'date D',
        'values': [55, 30, 34, 26]
      }, 
      {
        'label': 'date C',
        'values': [26, 40, 85, 28]
      }]
      
  };
    //init BarChart
	
    var barChart = new $jit.BarChart({
      //id of the visualization container
      injectInto: 'infovis',
      //whether to add animations
      animate: true,
      //horizontal or vertical barcharts
      orientation: 'vertical',
      //bars separation
      barsOffset: 20,
      //visualization offset
      Margin: {
        top:5,
        left: 5,
        right: 5,
        bottom:5
      },
      //labels offset position
      labelOffset: 5,
      //bars style
      type: useGradients? <?php echo $type ?>,/*'grouped:gradient' : 'grouped'<-->'stacked:gradient' : 'stacked'*/
      //whether to show the aggregation of the values
      showAggregates:false,
      //whether to show the labels for the bars
      showLabels:true,
      //labels style
      Label: {
        type: labelType, //Native or HTML
        size: 16,
        family: 'Arial',
        color: 'white'
      },
      //add tooltips
      Tips: {
        enable: true,
        onShow: function(tip, elem) {
          tip.innerHTML = "<b>" + elem.name + "</b>: $" + puntob(elem.value);
        }
      }
    });
    //load JSON data.
    barChart.loadJSON(json);
    //end
    var list = $jit.id('id-list'),
        button = $jit.id('update'),
        orn = $jit.id('switch-orientation');
    //update json on click 'Update Data'
    $jit.util.addEvent(button, 'click', function() {
      var util = $jit.util;
      if(util.hasClass(button, 'gray')) return;
      util.removeClass(button, 'white');
      util.addClass(button, 'gray');
      barChart.updateJSON(json2);
    });
    //dynamically add legend to list
    var legend = barChart.getLegend(),
        listItems = [];
    for(var name in legend) {
      listItems.push('<div class=\'query-color\' style=\'background-color:'
          + legend[name] +'\'>&nbsp;</div>' + name);
    }
    list.innerHTML = '<li>' + listItems.join('</li><li>') + '</li>';
}
</script>
</body>
</html>