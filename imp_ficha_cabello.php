<?php
require_once('Conexxx.php');
valida_session();

function dateFormatCol($date){
    $split = explode(" ",$date);
    $dmy = explode("-",$split[0]);
    
    $formatted = $dmy[2]."-".$dmy[1]."-".$dmy[0]." ".$split[1];
    return $formatted;
}

//----------------------------- vars-----------------------------------
$ficha = $_GET['ficha'];
$sqlFicha="SELECT * FROM ficha_tecnica_cabello WHERE num_ficha=$ficha AND nit=$codSuc";
$rsFicha = $linkPDO->query($sqlFicha);
$arrayFicha = $rsFicha->fetch();

//-----------------------------fin vars ----------------------------------------


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/imp_ficha_cabello.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language='javascript' src="JS/popcalendar.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script> 
<script language='javascript' src="JS/fac_com.js?<?php echo "$LAST_VER" ?>"></script> 
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).keydown(function(e) { 
  c=e.keyCode;       
    if (c == 27) {
        window.close();
    }
	else if(c == 13)imprimir();
});

function imprimir(){
//$('#imp').css('visibility','hidden');
var $bt=$('.addbtn');
$('.addbtn').detach();
window.print();
//$('#imp').css('visibility','visible');
$bt.appendTo('#imp');
};
</script>

<title>Ficha No. <?php echo $ficha; ?></title>
</head>

<body>

    <div class="letter">
        <div class="publicity">
            <div>
                <img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
            </div>
            <div>
                <?php echo $PUBLICIDAD; ?>
            </div>
            <div>
                <B><span style="font-size:24px; ">Ficha de Colorimetría<br>y Texturización</span></B><br>
                <span style="color:#F00"># <?php echo $ficha ?></span>
            </div>
        </div>
        <div class="dates_container">
            <div>
                Fecha de Registro de la Ficha:<br>
                <?php echo dateFormatCol($arrayFicha['fecha']); ?>
            </div>
            <div class="dates_container">
                Fecha de Inicio del Proceso:<br>
                <?php echo dateFormatCol($arrayFicha['fecha_tratamiento']); ?>
            </div>
        </div>
        <div class="client_container">
            <div>
                <div>
                    Cliente:
                    <?php echo $arrayFicha['nom_cli']; ?>
                </div>
                <div>
                    Identificaci&oacute;n:
                    <?php echo $arrayFicha['id_cli']; ?>
                </div>
                <div>
                    Tel.:
                    <?php echo $arrayFicha['tel_cli']; ?>
                </div>
            </div>
            <div>
                <div>
                    Ciudad:
                    <?php echo $arrayFicha['ciudad']; ?>
                </div>
                <div>
                    Direcci&oacute;n:
                    <?php echo $arrayFicha['dir']; ?>
                </div>
                <div>
                    e-mail:
                    <?php echo $arrayFicha['email']; ?>
                </div>
            </div>
        </div>
        <div class="ficha_data_container">
            <div>
                <b>Descripción Apariencia Inicial y Estado General:</b>
                <?php echo $arrayFicha['apariencia_inicial']; ?>
            </div>
            <div>
                <b>Análisis del Deseo del Cliente:</b>
                <?php echo $arrayFicha['deseo_cliente']; ?>
            </div>
            <div>
                <b>Procesos Anteriores:</b>
                <?php echo $arrayFicha['procesos_anteriores']; ?>
                <?php if($arrayFicha['otros_procesos_ant'] != "")
                    { ?>
                    <b>Otros:</b>
                    <?php echo $arrayFicha['otros_procesos_ant'];
                }?>
            </div>
            <div>
                <b>Descripción del Tipo de Cabello y Estado de la Fibra Capilar:</b>
                <?php echo $arrayFicha['desc_tipo_cabello']; ?>
            </div>
            <div>
                <b>Tono Natural del Cliente:</b>
                <?php echo $arrayFicha['tono_natural']; ?>
                <b>Porcentaje de Canas:</b>
                <?php echo $arrayFicha['porcentaje_canas']; ?>%
                <b>Forma del Cabello:</b>
                <?php echo $arrayFicha['forma_cabello']; ?>
                <b>Textura del Cabello:</b>
                <?php echo $arrayFicha['textura_cabello']; ?>
                <b>Porosidad:</b>
                <?php echo $arrayFicha['porosidad_cabello']; ?>
                <b>Tipo de Coloración Requerida por el Cliente:</b>
                <?php echo $arrayFicha['coloracion_requerida']; ?>
            </div>
            <div>
                <b>Se Observan Signos de Alteraciones o Daños en el Cuero Cabelludo o Fibra Capilar:</b>
                <?php echo $arrayFicha['alteraciones_daños_fibra']; ?>
            </div>
            <div>
                <b>Estado de Salud del Cliente:</b>
                <?php echo $arrayFicha['estado_salud']; ?>
                <?php if($arrayFicha['otros_procesos_ant'] != "")
                    { ?>
                    <b>Otros:</b>
                    <?php echo $arrayFicha['estado_salud_otros'];
                }?>
            </div>
            <div>
                <b>Proceso a Realizar</b>
                <?php echo $arrayFicha['proceso_realizar']; ?>
            </div>
            <div>
                <b>Procedimiento:</b>
                <?php echo $arrayFicha['procedimientos']; ?>
            </div>
        </div>
        <div class="articulos_container">
            <span>Insumos:</span>
            <table>
                <tr>
                    <td>Nombre y Tipo</td>
                    <td>Cantidad</td>
                    <td>Casa Cosmética</td>
                    <td>Observaciones</td>
                </tr>
        <?php 
            $sqlArticulos = "SELECT * from art_ficha_cabello WHERE num_ficha=$ficha AND nit=$codSuc";
            $rsArticulos = $linkPDO->query($sqlArticulos);

            while($articuloArray = $rsArticulos->fetch()){
                ?>
                <tr>
                    <td class='art_nombre'><?php echo $articuloArray['nombre']; ?></td>
                    <td class='art_cantidad'><?php echo $articuloArray['cantidad']; ?></td>
                    <td class='art_fabricante'><?php echo $articuloArray['fabricante']; ?></td>
                    <td class='art_observaciones'><?php echo $articuloArray['observaciones']; ?></td>
                </tr>
                <?php
            }
        ?>    
            </table>
        </div>
    </div>
    


    <div id="imp"  align="center">
        <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" class="addbtn"/>
        <input type="button" value="Volver" onMouseDown="javascript:location.assign('compras.php');" class="addbtn" />
    </div>

</body>
</html>