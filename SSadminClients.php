<?php
include("Conexxx.php");
if($rolLv!=$Adminlvl ){header("location: centro.php");}
$usar_fecha_vencimiento=0;
$fechaKardex="2012-01-01";

//include("offline_LIB.php");


function getPromedioFacturas($codSuc) {
    global $linkPDO;
    
    $totVentas = 0;
    $query = "SELECT COUNT(*) AS totFacs
              FROM fac_venta
              WHERE DATE(fecha) >= now()-interval 6 month
              AND nit=$codSuc";
              //echo "<li>$query</li>";
    $result = $linkPDO->query($query);
    if($row=$result->fetch()) {
        $totVentas = $row['totFacs'];
    }

    return $totVentas;

}


function getPromedioFacturasMensuales($codSuc) {
    global $linkPDO;
    
    $totVentas = 0;
    $query = "SELECT COUNT(*) AS totFacs
              FROM fac_venta
              WHERE DATE(fecha) >= now()-interval 6 month
              AND nit=$codSuc";
              //echo "<li>$query</li>";
    $result = $linkPDO->query($query);
    if($row=$result->fetch()) {
        $totVentas = $row['totFacs'];
    }

    return $totVentas/6;

}

?>
<!DOCTYPE html PUBLIC>
<html>
<head>
<?php require_once("HEADER.php"); ?>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->


<div class="uk-width-9-10 uk-container-center">

<div class=" posicion_form uk-grid uk-contrast">
<form class="uk-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">



    
<?php
$i=0;
$contadorPendientes=0;
$contadorPagos=0;
$contadorInhabilitados=0;
$contadorBloqueados=0;
$montoCobrar=0;
$montoCobrarPagos=0;
$montoCobrarInhabilitados=0;
$montoCobrarBloqueados=0;
$totalVentasClientes = 0;
$totalFacturasMensuales=0;
$totalUsuariosFE = 0;
foreach($SUB_DOMINIOS as $index => $val)
{
	
	//&& ($index>=1 && $index<=28)
if($SUB_DOMINIOS[$index]!="127.0.0.1/SS/" ){
echo "<h1>[$index] => $val |".$CREDENCIALES[$val]["db"]."</h1>";

$BaseDatos=$CREDENCIALES[$val]["db"];
$USU=$CREDENCIALES[$val]["usu"];
$CLA=$CREDENCIALES[$val]["cla"];
$HOST="127.0.0.1";
 

$conex = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($conex->connect_errno) {
    echo "<br> Falló la conexión a MySQL[".$CREDENCIALES[$val]["db"]."]: (" . $conex->connect_errno . ") " . $conex->connect_error;
}
/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = new PDO("mysql:host=$HOST;dbname=".$CREDENCIALES[$val]["db"]."", $USU, $CLA, 
      array(PDO::ATTR_PERSISTENT => 0));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected\n";
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}



$sql="SELECT * FROM x_config WHERE 1=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$_SESSION[$row["des_config"]]=$row["val"];		
	
}


$usar_fecha_vencimiento=0;
$fechaKardex="2000-05-07";
if($_SESSION["TIPO_CHUZO"]=="DRO"){$usar_fecha_vencimiento=1;}


$sql="SELECT * FROM sucursal ";
$rs=$linkPDO->query($sql);
$usuarioFE='';
$statusFE = '';
while($row=$rs->fetch()){
	$i++;
    $classPanel = '';
    $datosCliente = "<pre>
                     <b>$row[nom_negocio]</b> [$codSuc]
                     ($row[representante_se])
                     NIT:$row[nit_negocio]
                     $row[dir_su]
                     $row[tel1]
                     </pre>";
    $usuarioFE = $row['dataico_account_id'];
    $statusFE =  !empty($usuarioFE)? '<B>(USUARIO FE)<B/>':'';
    if(!empty($usuarioFE)){
        $totalUsuariosFE++;
    }

    $classPanel = $row['estado_pago']=='BLOQUEADO' ? 'uk-button-danger': '';
    if($row['estado_pago']=='Inhabilitado'){$classPanel = 'uk-block-secondary uk-contrast';}

    echo 
    '<fieldset>
    <div class="uk-form-row">
    <div class="uk-width-small-1-2 uk-width-1-1">      	
    <div class="uk-panel uk-panel-box '.$classPanel.'">';
    $codSuc=$row['cod_su'];
    $nomNegocio=$row['nom_negocio'];

    $idEstadoPago = "estadoPago".$i;
    $idFechaPago = "fechaPago".$i;
    $idFechaCorte = "fechaCorte".$i;
    $idValorAnualidad = "valorAnualidad".$i;
    $Clientes = new Clientes();
    $estadoCuentaCliente = $Clientes->defineMoraCliente($row['fecha_pago'],$row['fecha_corte'],$row['frecuencia_pago']);

    $now = new DateTime(date('Y-m-d H:i:s'));
    $duration = (new DateTime($row['fecha_corte']." 00:00:00"))->diff($now);
    $diasRestantesPago = $duration->format('%a');
    //echo "TIME : ".$duration->format('%a')."<br>";

    $classEstado='';
    $validacionNoActivos = ($row['estado_pago']!='Inhabilitado' && $row['estado_pago']!='Free' && $row['estado_pago']!='BLOQUEADO');
// validacion pendientes pago 30 dias
if(!$estadoCuentaCliente['estadoPago'] && ($validacionNoActivos) ){
	$contadorPendientes++;
	$montoCobrar+=$row['valor_anual'];
	$classEstado='uk-form-danger';
    $row['estado_pago']='Pendiente';
	}

if($estadoCuentaCliente['estadoPago'] && $validacionNoActivos){
	$contadorPagos++;
	$montoCobrarPagos+=$row['valor_anual'];
	}
	
if($row['estado_pago']=='Inhabilitado'){
	$contadorInhabilitados++;
	$montoCobrarInhabilitados+=$row['valor_anual'];
	}

if($row['estado_pago']=='BLOQUEADO'){
    $contadorBloqueados++;
    $montoCobrarBloqueados+=$row['valor_anual'];
    }

$stringEstado = $row['estado_pago'];

if($estadoCuentaCliente['estadoPago'] && $validacionNoActivos){
    $stringEstado = '<div class="uk-badge uk-badge-success uk-text-large">PAGO</div>';
}

if(!$estadoCuentaCliente['estadoPago'] && $validacionNoActivos){
    $stringEstado = '<div class="uk-badge uk-badge-warning uk-text-large">DEBE</div>';
}

$tablaVentas='';

if($validacionNoActivos || $row['estado_pago']=='Free'){
$sql2="SELECT num_fac_ven, prefijo, nom_cli, fecha, tot 
       FROM `fac_venta` 
       WHERE nit=$codSuc ORDER BY serial_fac DESC LIMIT 10 ";
$rs2=$linkPDO->query($sql2);
$tablaVentas='<table class="uk-table uk-table-striped uk-text-small">
			  <thead>
			  <th>Factura</th>
			  <th>Cliente</th>
			  <th>Total venta</th>
			  <th>Fecha</th>
			  </thead><tbody>';
while($row2=$rs2->fetch()){
	
	$tablaVentas.="<tr><td>$row2[prefijo]$row2[num_fac_ven]</td>";
	$tablaVentas.="<td>$row2[nom_cli]</td>";
	$tablaVentas.="<td>".money($row2['tot'])."</td>";
	$tablaVentas.="<td>$row2[fecha]</td></tr>";
}
$tablaVentas.='</tbody></table>';
}

$facturasTotalesRecientes = getPromedioFacturas($codSuc);
$totalVentasClientes+=$facturasTotalesRecientes;
$ventasMensuales = getPromedioFacturasMensuales($codSuc);
$totalFacturasMensuales+=$ventasMensuales;

$StringDiasRestantes = $estadoCuentaCliente['diasRestantes'].' Días';

?>
<table class="uk-table">
<thead>
<tr>
<th colspan="7"><?php echo "$statusFE $datosCliente URL: <a href=\"http://".$SUB_DOMINIOS[$index]." \" class='uk-button uk-button-link uk-button-primary' target=\"_blank\">Link</a>"; ?></th>
</tr>
<tr>
<th><?php echo $stringEstado." $StringDiasRestantes";?></th><th>Fecha Ult. Pago</th><th>Fecha Corte</th><th>Valor Anualidad</th>
</tr>
</thead>
<tbody>
        <tr>
            <td>
            <select name="estadoPago" id="<?php echo $idEstadoPago;?>" class="<?php echo $classEstado;?>">
            
            
            <option value="Pendiente" <?php if($row['estado_pago']=='Pendiente'){echo 'selected';}?>>Pendiente</option>
            <option value="Pago" <?php if($row['estado_pago']=='Pago'){echo 'selected';}?> >Pago</option>
            <option value="Free" <?php if($row['estado_pago']=='Free'){echo 'selected';}?> >Gratis</option>
            <option value="Inhabilitado" <?php if($row['estado_pago']=='Inhabilitado'){echo 'selected';}?>>Inhabilitado</option>
            <option value="BLOQUEADO" <?php if($row['estado_pago']=='BLOQUEADO'){echo 'selected';}?>>BLOQUEADO</option>
            </select></td>
            <td><input type="date" name="fechaPago" id="<?php echo $idFechaPago;?>" class="" value="<?php echo $row['fecha_pago'];?>"></td>
            <td><input type="date" name="fechaCorte" id="<?php echo $idFechaCorte;?>" class="" value="<?php echo $row['fecha_corte'];?>"></td>
            <td><input type="text" name="valorAnualidad" id="<?php echo $idValorAnualidad;?>" class="" value="<?php echo money($row['valor_anual']);?>" onKeyUp="puntoa($(this));"></td>
            
        </tr>
        <tr>
            <td colspan="2">Facturas Ultimos 6 meses</td>
            <td><?php echo $facturasTotalesRecientes; ?></td>
            <td colspan="2">Ventas Mensuales Promedio</td>
            <td><?php echo $ventasMensuales; ?></td>
        </tr>
        <tr>
        <td colspan="6" class="uk-text-small"><?php echo $tablaVentas;?></td>
        </tr>
        <tr>
        <td colspan="6" align="center">
<input type="button" value="Guardar" class="uk-button uk-button-primary" onClick="saveDataExternal($('#<?php echo $idEstadoPago;?>').val(),$('#<?php echo $idFechaPago;?>').val(),$('#<?php echo $idFechaCorte;?>').val(),$('#<?php echo $idValorAnualidad;?>').val(),'<?php echo $val;?>','<?php echo $codSuc;?>')">
</td>
        </tr>
    </tbody>
</table>




<?php
 

echo '</div>
</div>
</div>
</fieldset>';

}






//
	}
}
 
?>
<br><br>
<div class="uk-panel">
    <h3 class="uk-panel-title">Estadisticas Clientes</h3>
    Total Facturas Ultimos 6 meses: <?php echo round($totalVentasClientes);?> <br>
    Promedio ventas mensual: <?php echo round($totalFacturasMensuales);?>
</div>
<br><br>
<table class="uk-table uk-panel-box-primary" style="width:50%;">
<thead>
<tr>
<th >&nbsp;</th>
<th >Pendiente</th>
<th >Pago</th>
<th >Inhabilitado</th>
<th >BLOQUEADO</th>
<th >Usuarios FE</th>
</tr>
</thead>
<tbody>
        <tr>
            <td>
            Cantidad
            </td>
            <td><?php echo $contadorPendientes;?></td>
            <td><?php echo $contadorPagos;?></td>
            <td><?php echo $contadorInhabilitados;?></td>
            <td><?php echo $contadorBloqueados;?></td>
            <td><?php echo $totalUsuariosFE;?></td>
        </tr>
        <tr>
            <td>
            Monto
            </td>
            <td><?php echo money($montoCobrar);?></td>
            <td><?php echo money($montoCobrarPagos);?></td>
            <td><?php echo money($montoCobrarInhabilitados);?></td>
            <td><?php echo money($montoCobrarBloqueados);?></td>
            <td><?php echo '';?></td>
        </tr>
                <tr>
            <td>
            Total Activos
            </td>
            <td><?php echo ($contadorPendientes+$contadorPagos);?></td>
            <td><?php echo money($montoCobrarPagos+$montoCobrar);?></td>
            <td> </td>
            <td> </td>
        </tr>
</tbody>
</table>
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript" type="text/javascript">
function saveDataExternal(estadoPago,fechaPago,fechaCorte,valorAnualidad,cliente,codSuc)
{
	//console.log(estadoPago+', '+fechaPago+', '+valorAnualidad+', '+cliente+', '+codSuc);
	var urlAjax = 'ajax/MANAGER/saveExternalClient.php';
	$.ajax({
		url:urlAjax,
		data:{estadoPago:estadoPago,fechaPago:fechaPago,fechaCorte:fechaCorte,valorAnualidad:valorAnualidad,cliente:cliente,codSuc:codSuc},
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
				//alert('Si!');

				
			
			}
			else {}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
}
</script>
</form>

</div>
</div>
</div>
<?php
$linkPDO = null;
?>
</body>
</html>