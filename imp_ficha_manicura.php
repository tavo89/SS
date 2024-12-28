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
$sqlFicha="SELECT * FROM ficha_tecnica_manicura WHERE num_ficha=$ficha AND nit=$codSuc";
$rsFicha = $linkPDO->query($sqlFicha);
$arrayFicha = $rsFicha->fetch();

//-----------------------------fin vars ----------------------------------------


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/imp_ficha_manicura.css" rel="stylesheet" type="text/css" />
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
                    <B><span style="font-size:24px; ">Ficha de Manicura</span></B><br>
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

            <div class="estado_unas_container">
                <div class="mano_izquierda">
                    <div style="text-align: center;margin-bottom: 20px;">Izquierda</div>
                    <div>1: <?php echo $arrayFicha['primera_visita_izq_pulgar']; ?></div>
                    <div>2: <?php echo $arrayFicha['primera_visita_izq_indice']; ?></div>
                    <div>3: <?php echo $arrayFicha['primera_visita_izq_medio']; ?></div>
                    <div>4: <?php echo $arrayFicha['primera_visita_izq_anular']; ?></div>
                    <div>5: <?php echo $arrayFicha['primera_visita_izq_menique']; ?></div>
                </div>
                <div class="mano_derecha">
                    <div style="text-align: center;margin-bottom: 20px;">Derecha</div>
                    <div>1: <?php echo $arrayFicha['primera_visita_der_pulgar']; ?></div>
                    <div>2: <?php echo $arrayFicha['primera_visita_der_indice']; ?></div>
                    <div>3: <?php echo $arrayFicha['primera_visita_der_medio']; ?></div>
                    <div>4: <?php echo $arrayFicha['primera_visita_der_anular']; ?></div>
                    <div>5: <?php echo $arrayFicha['primera_visita_der_menique']; ?></div>
                </div>
                <span class="leyenda">*R-Rotas *H-Micosis/Hongos *L-Levantamientos *M-Mordidas *I-Infectadas *A-Amarilleadas *S-Sanas</span>
            </div>

            <div class="tratamiento">
                <b>Tratamiento que se Realiza:</b>
                <?php echo $arrayFicha['tratamiento_realizar']; ?>
            </div>
            <div class="cuidados">
                <b>Cuidados Recomendados Fuera del Centro:</b>
                <?php echo $arrayFicha['cuidados_recomendados']; ?>
            </div>
            <div class="alergias">
                <b>Alergias:</b>
                <?php echo $arrayFicha['alergias']; ?>
            </div>
            <div class="articulos_container">
                <span>Registro de Citas:</span>
                <table>
                    <tr>
                        <td>Motivo de la Cita</td>
                        <td>Fecha</td>
                    </tr>
            <?php 
                $sqlArticulos = "SELECT * FROM art_ficha_manicura_citas WHERE num_ficha=$ficha AND nit=$codSuc";
                $rsArticulos = $linkPDO->query($sqlArticulos);

                while($articuloArray = $rsArticulos->fetch()){
                    ?>
                    <tr>
                        <td class='art_motivo'><?php echo $articuloArray['motivo']; ?></td>
                        <td class='art_fecha'><?php echo $articuloArray['fecha']; ?></td>
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
        var h4 = $(".estado_unas_container").outerHeight();
        var h5 = $(".tratamiento").outerHeight();
        var h6 = $(".cuidados").outerHeight();
        var h7 = $(".alergias").outerHeight();
        var h8 = $(".articulos_container").outerHeight();
        
        totalH = h1 + h2 + h3 + h4 + h5 + h6 + h7 + h8;
        
        totalH += 60; /** Firmas **/
        
        //alert(totalH);
        
        if(totalH > 970){
            /** se agrega la nueva pagina **/
            $("body").append(
                "<div class='letter'><div class='letter_border page_2'></div></div>"
            );
            
            /** se duplican los encabezados **/
            $(".publicity").clone().appendTo(".page_2");
            $(".dates_container").clone().appendTo(".page_2");
            $(".client_container").clone().appendTo(".page_2");
            
            /** se mueven los bloques que no caben en la primera pagina **/
            
            if(totalH-h8 > 1066){
                
                if(totalH-h8-h7 > 1066){
                    
                    if(totalH-h8-h7-h6 > 1066){
                        
                        if(totalH-h8-h7-h6-h5 > 1066){
                            
                            $(".estado_unas_container").appendTo(".page_2");
                        }
                        $(".tratamiento").appendTo(".page_2");
                    }
                    $(".cuidados").appendTo(".page_2");
                }
                $(".alergias").appendTo(".page_2");
            }
            $(".articulos_container").appendTo(".page_2");
            $(".firmas").appendTo("body");
        }
        
    </script>
    
</html>