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
        <div class="letter_border">
            <div class="publicity">
                <div>
                    <img src="<?php echo $url_LOGO_B ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
                </div>
                <div>
                    <?php echo $PUBLICIDAD; ?>
                </div>
                <div>
                    <B><span style="font-size:24px; ">Ficha de Colorimetría<br>y Texturización</span></B><br>
                    <span style="color:#F00; font-size: 30px;"># <?php echo $ficha ?></span>
                </div>
            </div>
            <div class="dates_container">
                <div>
                    <b>Fecha de Registro de la Ficha:</b><br>
                    <?php echo dateFormatCol($arrayFicha['fecha']); ?>
                </div>
                <div>
                    <b>Fecha de Inicio del Proceso:</b><br>
                    <?php echo dateFormatCol($arrayFicha['fecha_tratamiento']); ?>
                </div>
            </div>
            <div class="client_container">
                <div>
                    <div>
                        <b>Cliente:</b>
                        <?php echo $arrayFicha['nom_cli']; ?>
                    </div>
                    <div>
                        <b>Identificaci&oacute;n:</b>
                        <?php echo $arrayFicha['id_cli']; ?>
                    </div>
                    <div>
                        <b>Tel.:</b>
                        <?php echo $arrayFicha['tel_cli']; ?>
                    </div>
                </div>
                <div>
                    <div>
                        <b>Direcci&oacute;n:</b>
                        <?php echo $arrayFicha['dir']; ?>
                    </div>
                    <div>
                        <b>Ciudad:</b>
                        <?php echo $arrayFicha['ciudad']; ?>
                    </div>
                    <div>
                        <b>e-mail:</b>
                        <?php echo $arrayFicha['email']; ?>
                    </div>
                </div>
            </div>
            <div class="apariencia_inicial_container">
                <b>Descripción Apariencia Inicial y Estado General:</b>
                <?php echo $arrayFicha['apariencia_inicial']; ?>
            </div>
            <div class="deseo_cliente_container">
                <b>Análisis del Deseo del Cliente:</b>
                <?php echo $arrayFicha['deseo_cliente']; ?>
            </div>
            <div class="procesos_ant_container">
                <b>Procesos Anteriores:</b>
                <?php echo str_replace("|"," | ",$arrayFicha['procesos_anteriores']); ?>
                <?php if($arrayFicha['otros_procesos_ant'] != "")
                    { ?>
                |
                    <?php echo $arrayFicha['otros_procesos_ant'];
                }?>
            </div>
            <div class="tipo_cabello_estado_fibra_container">
                <b>Descripción del Tipo de Cabello y Estado de la Fibra Capilar:</b>
                <?php echo $arrayFicha['desc_tipo_cabello']; ?>
            </div>
            <div class="datos_cortos_container">
                <div>
                    <b>Tono Natural del Cliente:</b>
                    <?php echo $arrayFicha['tono_natural']; ?><br/>
                    <b>Porcentaje de Canas:</b>
                    <?php echo $arrayFicha['porcentaje_canas']; ?>%<br/>
                    <b>Forma del Cabello:</b>
                    <?php echo $arrayFicha['forma_cabello']; ?><br/>
                </div>
                <div>
                    <b>Textura del Cabello:</b>
                    <?php echo $arrayFicha['textura_cabello']; ?><br/>
                    <b>Porosidad:</b>
                    <?php echo $arrayFicha['porosidad_cabello']; ?><br/>
                    <b>Coloración Requerida:</b>
                    <?php echo $arrayFicha['coloracion_requerida']; ?><br/>
                </div>
            </div>
            <div class="signos_alteraciones_container">
                <b>Se Observan Signos de Alteraciones o Daños en el Cuero Cabelludo o Fibra Capilar:</b>
                <?php echo $arrayFicha['alteraciones_daños_fibra']; ?>
            </div>
            <div class="estado_salud_container">
                <b>Estado de Salud del Cliente:</b>
                <?php echo str_replace("|"," | ",$arrayFicha['estado_salud']); ?>
                <?php if($arrayFicha['otros_procesos_ant'] != "")
                    { ?>
                    |
                    <?php echo $arrayFicha['estado_salud_otros'];
                }?>
            </div>
            <div class="proceso_a_realizar_container">
                <b>Proceso a Realizar:</b>
                <?php echo $arrayFicha['proceso_realizar']; ?>
            </div>
            <div class="procedimiento_container">
                <b>Procedimiento:</b>
                <?php echo $arrayFicha['procedimientos']; ?>
            </div>
            <div class="articulos_container">
                <span>Insumos Aplicados:</span>
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
    </div>
    <div class="firmas">
            <div>
                Firma Cliente
            </div>
            <div>
                Firma Manicurista
            </div>
        </div>
    


    <div id="imp"  align="center">
        <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" class="addbtn"/>
        <!-- <input type="button" value="Volver" onMouseDown="javascript:location.assign('compras.php');" class="addbtn" /> -->
    </div>
</body>
    <script>
        /** Set up second page for large fichas **/
        /** document height: 1020px **/
        var totalH = 0;
        var h1 = $(".publicity").outerHeight();
        var h2 = $(".dates_container").outerHeight();
        var h3 = $(".client_container").outerHeight();
        var h4 = $(".apariencia_inicial_container").outerHeight();
        var h5 = $(".deseo_cliente_container").outerHeight();
        var h6 = $(".procesos_ant_container").outerHeight();
        var h7 = $(".tipo_cabello_estado_fibra_container").outerHeight();
        var h8 = $(".datos_cortos_container").outerHeight();
        var h9 = $(".signos_alteraciones_container").outerHeight();
        var h10 = $(".estado_salud_container").outerHeight();
        var h11 = $(".proceso_a_realizar_container").outerHeight();
        var h12 = $(".procedimiento_container").outerHeight();
        var h13 = $(".articulos_container").outerHeight();
        
        totalH = h1 + h2 + h3 + h4 + h5 + h6 + h7 + h8 + h9 + h10 + h11 + h12 + h13;
        
        totalH += 60; /** Firmas **/
        
        //alert(totalH);
        
        if(totalH > 960){
            /** se agrega la nueva pagina **/
            $("body").append(
                "<div class='letter'><div class='letter_border page_2'></div></div>"
            );
            
            /** se duplican los encabezados **/
            $(".publicity").clone().appendTo(".page_2");
            $(".dates_container").clone().appendTo(".page_2");
            $(".client_container").clone().appendTo(".page_2");
            
            /** se mueven los bloques que no caben en la primera pagina a la siguiente página **/
            
            if(totalH-h13 > 1065){
                
                if(totalH-h13-h12 > 1065){
                    
                    if(totalH-h13-h12-h11 > 1065){
                        
                        if(totalH-h13-h12-h11-h10 > 1065){
                            
                            if(totalH-h13-h12-h11-h10-h9 > 1065){
                                
                                if(totalH-h13-h12-h11-h10-h9-h8 > 1065){
                                    
                                    if(totalH-h13-h12-h11-h10-h9-h8-h7 > 1065){
                                        
                                        if(totalH-h13-h12-h11-h10-h9-h8-h7-h6 > 1065){
                                            $(".deseo_cliente_container").appendTo(".page_2");
                                        }
                                        $(".procesos_ant_container").appendTo(".page_2");
                                    }
                                    $(".tipo_cabello_estado_fibra_container").appendTo(".page_2");
                                }
                                $(".datos_cortos_container").appendTo(".page_2");
                            }
                            $(".signos_alteraciones_container").appendTo(".page_2");
                        }
                        $(".estado_salud_container").appendTo(".page_2");
                    }
                    $(".proceso_a_realizar_container").appendTo(".page_2");
                }
                $(".procedimiento_container").appendTo(".page_2");
            }
            $(".articulos_container").appendTo(".page_2");
            $(".firmas").appendTo("body");
        }
    </script>
</html>