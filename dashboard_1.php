<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl ){header("location: centro.php");}
$boton=r("boton");
$fechaI="";
$fechaF="";


$tipoGastos="";
if(isset($_REQUEST["tipo_gastos"])){$tipoGastos=$_REQUEST["tipo_gastos"];}
$FILTRO_GASTOS="";


//$FILTRO_INVERSIONES=" AND tipo_gasto!='Consignacion Ventas' AND tipo_gasto!='Transferencia Entre Cuentas' AND tipo_gasto!='Inversion Negocio' AND tipo_gasto!='Inversion Personal' ";
//$FILTRO_INVERSIONES=" AND tipo_gasto!='Consignacion Ventas' AND tipo_gasto!='Transferencia Entre Cuentas'";
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

if(isset($_REQUEST["anio"]) && !empty($_REQUEST["anio"])){
	
	$_SESSION["year_graf01"]=$year;

	
	
}
//echo "año: $year";
if(isset($_SESSION["year_graf01"]) && !empty($_SESSION["year_graf01"]))
{
	$year=$_SESSION["year_graf01"];

}
//echo "<li>año session: $year</li>";
if(empty($year)){
	
	$sql="SELECT MAX(YEAR(fecha)) as year FROM fac_venta WHERE nit=$codSuc";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch()){
	$year=$row["year"];
	$sem=1;
	$type="'grouped:gradient' : 'grouped'";
	}
	}
//echo "<li>año empty: $year</li>";
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
$gananciasSemestre=0;
for($i=1;$i<=12;$i++)
{
	
	//if($once==0){if($sem==1){$i=1;}else {$i=7;}$once=1;}
	
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
	$sql="SELECT IFNULL(sum(valor),0) sum FROM comp_egreso WHERE cod_su=$codSuc $FILTRO_INVERSIONES $FILTRO_GASTOS  AND DATE(fecha)>='$year-$mes-01' AND DATE(fecha)<=CONCAT('$year-$mes-',DAY(last_day(fecha))) AND anulado!='ANULADO'";
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
	$utilidad[$ii]=$ventas[$ii]-$egre[$ii];
	if($ventas[$ii]-$egre[$ii]<0){$utilidad[$ii]=0;}
	
	if($utilidad[$ii]>0)$gananciasSemestre+=$utilidad[$ii];
	$ii++;
	//if($ii>6)break;
	
}


//$ingresosSemestre=$ventas[1]+$ventas[2]+$ventas[3]+$ventas[4]+$ventas[5]+$ventas[6];
$ingresosSemestre=array_sum($ventas);
//$egresosSemestre=$egre[1]+$egre[2]+$egre[3]+$egre[4]+$egre[5]+$egre[6];
$egresosSemestre=array_sum($egre);

$promVentas=$ingresosSemestre/($mesActual*1);
if($year!=$yearActual)$promVentas=$ingresosSemestre/(12);

$ingresosSemestre=redondeoF($ingresosSemestre,0);
$egresosSemestre=redondeoF($egresosSemestre,0);
$promVentas=redondeoF($promVentas,0);
$gananciasSemestre=redondeoF($gananciasSemestre,0);


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

<?php require_once("HEADER.php"); ?>

    <style>
	h1,h2,h3,h4,h5{color:#000;}
	
	</style>
</head>

<body>
    <div id="wrapper">
         <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>
        <div id="page-wrappers" class="gray-bg uk-width-9-10 uk-container-center" >
        <div class="row border-bottom">
       
        </div>
            <div class="wrapper wrapper-content">
            
            
        <div class="row">
                    <div class="col-lg-2">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">Ingresos</span>
                                <h5>Ventas</h5>
                            </div>
                            <div class="ibox-content">
                                <h2 class="no-margins"><?php  echo money($ingresosSemestre)  ?></h2>
                               <!-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>-->
                                <small>Total ingresos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-warning pull-right">Egresos</span>
                                <h5>Egresos</h5>
                            </div>
                            <div class="ibox-content">
                                <h2 class="no-margins"><?php  echo money($egresosSemestre)  ?></h2>
                                
                                <!--<div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>-->
                                <small>Total egresos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Ganancias</span>
                                <h5>Ganancia</h5>
                            </div>
                            <div class="ibox-content">
                                <h2 class="no-margins"><?php  echo money($gananciasSemestre);  ?></h2>
                               <!-- <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div>-->
                                <small>Total ganancias</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">Ventas</span>
                                <h5><?php echo $MESES[$mesActual*1]?></h5>
                            </div>
                            
                            
                            <div class="ibox-content">
                                <h2 class="no-margins"><?php  echo money($ventas[$mesActual*1])  ?></h2>
                               <!-- <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>-->
                                <small>Mes actual</small>
                            </div>
                        </div>
            </div>
       
        
        
        
        
        <div class="col-lg-2">
                        <div class="ibox float-e-margins">
                        
                            <div class="ibox-title"><span class="label label-warning pull-right">Egresos</span><h5><?php echo $MESES[$mesActual*1]?></h5></div>
<div class="ibox-content"><h2 class="no-margins"><?php  echo money($egre[$mesActual*1])  ?></h2><!-- <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>--> <small>Mes actual</small></div>
                            
                        </div>
            </div>
            
            
              <div class="col-lg-2">
                        <div class="ibox float-e-margins">
                        
                            <div class="ibox-title"><span class="label label-primary pull-right">Util. Bruta</span><h5><?php echo $MESES[$mesActual*1]?></h5></div>
<div class="ibox-content"><h2 class="no-margins"><?php  echo money($ventas[$mesActual*1]-$egre[$mesActual*1])  ?></h2><!-- <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>--> <small>Mes actual</small></div>
                            
                        </div>
            </div>
            
            
            
            
        </div><!-- END ROW -->

                            <div class="ibox-content">
                                <div class="row">
                                <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                    <div>
                                        <span class="pull-right text-right">
                                        <small>Valor promedio de ventas mensual en: <strong> <?php echo $_SESSION['nom_negocio']; ?></strong></small>
                                            <br/>
                                            Promedio mensual: <?php echo money3($promVentas); ?>
                                        </span>
                                        <h2 class="m-b-xs"><?php echo money3($ingresosSemestre); ?></h2>
                                        <h3 class="font-bold no-margins">
                                        <form action="" method="get">
                                            Ventas anuales 
                                            
                                            <select name="anio" onChange="submit();">
            <option value="<?php echo $year; ?>" selected><?php echo $year; ?></option>
           <?php
		   
		   $sql="SELECT YEAR(fecha) as year FROM fac_venta WHERE nit=$codSuc AND YEAR(fecha)!='$year' GROUP BY year ORDER BY year DESC";
	$rs=$linkPDO->query($sql);
	while($row=$rs->fetch()){
	$year=$row["year"];
	echo "<option value=\"$year\">$year</option> ";;
	}
		   
		   
		   ?>
        
            </select>
            </form>
                                            
                                        </h3>
                                        <small>Sales marketing.</small>
                                    </div>

                                <div>
                                    <canvas id="lineChart" height="70"></canvas>
                                </div>

                                <div class="m-t-md">
                                <!--
                                    <small class="pull-right">
                                        <i class="fa fa-clock-o"> </i>
                                        Update on 16.07.2015
                                    </small>
                                    -->
                                    <!--
                                   <small>
                                       <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                                   </small>-->
                                </div>

                            </div>
                        </div>
               
                    </div>


                <!--
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>
        </div>
        -->
       


        </div>
    </div>
<?php require_once("FOOTER.php"); ?>
    <!-- Mainly scripts -->

    <script src="JS/bootstrap.min.js"></script>
    <script src="JS/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="JS/plugins/slimscroll/jquery.slimscroll.min.js"></script>



    <!-- Peity -->
    <script src="JS/plugins/peity/jquery.peity.min.js"></script>
    <script src="JS/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="JS/inspinia.js"></script>
    <script src="JS/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="JS/plugins/jquery-ui/jquery-ui.min.js"></script>


 <!-- ChartJS-->
    <script src="JS/plugins/chartJs/Chart.min.js"></script>
    <script src="JS/UNIVERSALES.js"></script>

    <script>
        $(document).ready(function() {
			
			
			

            var lineData = {
                labels: ["<?php echo $MESES[1] ?>", "<?php echo $MESES[2] ?>", "<?php echo $MESES[3] ?>", "<?php echo $MESES[4] ?>", "<?php echo $MESES[5] ?>", "<?php echo $MESES[6] ?>", "<?php echo $MESES[7] ?>", "<?php echo $MESES[8] ?>", "<?php echo $MESES[9] ?>", "<?php echo $MESES[10] ?>", "<?php echo $MESES[11] ?>", "<?php echo $MESES[12] ?>"],
                datasets: [
				 {
                        label: "Ingresos",
                        fillColor: "rgba(26,179,148,0.5)",
                        strokeColor: "rgba(26,179,148,0.7)",
                        pointColor: "rgba(26,179,148,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(26,179,148,1)",
                        data: [<?php echo $ventas[1] ?>, <?php echo $ventas[2] ?>, <?php echo $ventas[3] ?>, <?php echo $ventas[4] ?>, <?php echo $ventas[5] ?>, <?php echo $ventas[6] ?>, <?php echo $ventas[7] ?>, <?php echo $ventas[8] ?>, <?php echo $ventas[9] ?>, <?php echo $ventas[10] ?>, <?php echo $ventas[11] ?>, <?php echo $ventas[12] ?>]
                    },
                    {
                        label: "Egresos",
                        fillColor: "rgba(220,220,220,0.5)",
                        strokeColor: "rgba(220,220,220,1)",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [<?php echo $egre[1] ?>, <?php echo $egre[2] ?>, <?php echo $egre[3] ?>, <?php echo $egre[4] ?>, <?php echo $egre[5] ?>, <?php echo $egre[6] ?>, <?php echo $egre[7] ?>, <?php echo $egre[8] ?>, <?php echo $egre[9] ?>, <?php echo $egre[10] ?>, <?php echo $egre[11] ?>, <?php echo $egre[12] ?>]
                    },
					 {
                        label: "Utilidad",
                        fillColor: "rgba(64,150,2388,0.5)",
                        strokeColor: "rgba(64,150,238,0.7)",
                        pointColor: "rgba(64,150,238,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(176,179,148,1)",
                        data: [<?php echo ($utilidad[1]) ?>, <?php echo $utilidad[2] ?>, <?php echo $utilidad[3] ?>, <?php echo $utilidad[4] ?>, <?php echo $utilidad[5] ?>, <?php echo $utilidad[6] ?>, <?php echo $utilidad[7] ?>, <?php echo $utilidad[8] ?>, <?php echo $utilidad[9] ?>, <?php echo $utilidad[10] ?>, <?php echo $utilidad[11] ?>, <?php echo $utilidad[12] ?>]
                    }
                   
					
					
					
                ]
            };

            var lineOptions = {
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                bezierCurve: true,
                bezierCurveTension: 0.4,
                pointDot: true,
                pointDotRadius: 3,
                pointDotStrokeWidth: 2,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                responsive: true,
				scaleLabel: function (valuePayload) {
                        return puntob(valuePayload.value);
                    }, multiTooltipTemplate: function (monto) {
                        return monto.datasetLabel + ":  " + puntob(monto.value);
                    }, tooltipTemplate: function (monto) {
                         return monto.datasetLabel + ": "  + puntob(monto.value);
						 }
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            var myNewChart = new Chart(ctx).Line(lineData, lineOptions);

        });
    </script>
</body>
</html>
