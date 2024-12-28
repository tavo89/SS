<?php
    require_once("Conexxx.php");
    
    /** función para agregar comas en las listas de columnas o valores para consultas dinamicas **/
    function insertComma($str){
        if($str !== ""){
            return ",";
        }else{
            return "";
        }
    }
    
    /** funcion para agregar barras en campos de multiples valores(para evitar agregar 5 columnas) **/
    function insertStraightBar($str){
        if($str !== ""){
            return "|";
        }else{
            return "";
        }
    }
    
    if( isset( $_POST['send-form'] ) ){
        $queryCols = "";
        $queryValues = "";
        
        if( isset($_POST['id_cli']) ) {
            if( $_POST['id_cli'] != "" )
            {
                $id_cli = limpiarcampo($_POST['id_cli']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "id_cli";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$id_cli."'";
            }
        }
        if( isset($_POST['nombres']) ) {
            if( $_POST['nombres'] != "" )
            {
                $nombres = strtoupper(limpiarcampo($_POST['nombres']));
                $queryCols .= insertComma($queryCols);
                $queryCols .= "nom_cli";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$nombres."'";
            }
        }
        if( isset($_POST['direccion']) ) {
            if( $_POST['direccion'] != "" )
            {
                $direccion = strtoupper(limpiarcampo($_POST['direccion']));
                $queryCols .= insertComma($queryCols);
                $queryCols .= "dir";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$direccion."'";
            }
        }
        if( isset($_POST['tel_cli']) ) {
            if( $_POST['tel_cli'] != "" )
            {
                $tel_cli = limpiarcampo($_POST['tel_cli']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "tel_cli";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$tel_cli."'";
            }
        }
        if( isset($_POST['ciudad_cli']) ) {
            if( $_POST['ciudad_cli'] != "" )
            {
                $ciudad = strtoupper(limpiarcampo($_POST['ciudad_cli']));
                $queryCols .= insertComma($queryCols);
                $queryCols .= "ciudad";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$ciudad."'";
            }
        }
        if( isset($_POST['email_cli']) ) {
            if( $_POST['email_cli'] != "" )
            {
                $email = limpiarcampo($_POST['email_cli']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "email";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$email."'";
            }
        }
        if( isset($_POST['inicio_tratamiento']) ) {
            if( $_POST['inicio_tratamiento'] != "" )
            {
                $inicio_tratamiento = limpiarcampo($_POST['inicio_tratamiento']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "fecha_tratamiento";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$inicio_tratamiento."'";
            }
        }
        
        
        
        
        if( isset($_POST['primera_visita_izq_menique']) ) {
            if( $_POST['primera_visita_izq_menique'] != "" )
            {
                $primera_visita_izq_menique = limpiarcampo($_POST['primera_visita_izq_menique']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_izq_menique";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_izq_menique)."'";
            }
        }
        if( isset($_POST['primera_visita_izq_anular']) ) {
            if( $_POST['primera_visita_izq_anular'] != "" )
            {
                $primera_visita_izq_anular = limpiarcampo($_POST['primera_visita_izq_anular']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_izq_anular";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_izq_anular)."'";
            }
        }
        if( isset($_POST['primera_visita_izq_medio']) ) {
            if( $_POST['primera_visita_izq_medio'] != "" )
            {
                $primera_visita_izq_medio = limpiarcampo($_POST['primera_visita_izq_medio']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_izq_medio";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_izq_medio)."'";
            }
        }
        if( isset($_POST['primera_visita_izq_indice']) ) {
            if( $_POST['primera_visita_izq_indice'] != "" )
            {
                $primera_visita_izq_indice = limpiarcampo($_POST['primera_visita_izq_indice']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_izq_indice";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_izq_indice)."'";
            }
        }
        if( isset($_POST['primera_visita_izq_pulgar']) ) {
            if( $_POST['primera_visita_izq_pulgar'] != "" )
            {
                $primera_visita_izq_pulgar = limpiarcampo($_POST['primera_visita_izq_pulgar']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_izq_pulgar";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_izq_pulgar)."'";
            }
        }
        if( isset($_POST['primera_visita_der_menique']) ) {
            if( $_POST['primera_visita_der_menique'] != "" )
            {
                $primera_visita_der_menique = limpiarcampo($_POST['primera_visita_der_menique']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_der_menique";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_der_menique)."'";
            }
        }
        if( isset($_POST['primera_visita_der_anular']) ) {
            if( $_POST['primera_visita_der_anular'] != "" )
            {
                $primera_visita_der_anular = limpiarcampo($_POST['primera_visita_der_anular']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_der_anular";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_der_anular)."'";
            }
        }
        if( isset($_POST['primera_visita_der_medio']) ) {
            if( $_POST['primera_visita_der_medio'] != "" )
            {
                $primera_visita_der_medio = limpiarcampo($_POST['primera_visita_der_medio']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_der_medio";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_der_medio)."'";
            }
        }
        if( isset($_POST['primera_visita_der_indice']) ) {
            if( $_POST['primera_visita_der_indice'] != "" )
            {
                $primera_visita_der_indice = limpiarcampo($_POST['primera_visita_der_indice']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_der_indice";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_der_indice)."'";
            }
        }
        if( isset($_POST['primera_visita_der_pulgar']) ) {
            if( $_POST['primera_visita_der_pulgar'] != "" )
            {
                $primera_visita_der_pulgar = limpiarcampo($_POST['primera_visita_der_pulgar']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "primera_visita_der_pulgar";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".strtoupper($primera_visita_der_pulgar)."'";
            }
        }
        if( isset($_POST['tratamiento_realizar']) ) {
            if( $_POST['tratamiento_realizar'] != "" )
            {
                $tratamiento_realizar = limpiarcampo($_POST['tratamiento_realizar']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "tratamiento_realizar";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".ucfirst($tratamiento_realizar)."'";
            }
        }
        if( isset($_POST['cuidados_recomendados']) ) {
            if( $_POST['cuidados_recomendados'] != "" )
            {
                $cuidados_recomendados = limpiarcampo($_POST['cuidados_recomendados']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "cuidados_recomendados";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".ucfirst($cuidados_recomendados)."'";
            }
        }
        if( isset($_POST['alergias']) ) {
            if( $_POST['alergias'] != "" )
            {
                $alergias = limpiarcampo($_POST['alergias']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "alergias";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".ucfirst($alergias)."'";
            }
        }
        if( isset($_POST['id_factura']) ) {
            if( $_POST['id_factura'] != "" )
            {
                $id_factura = limpiarcampo($_POST['id_factura']);
                $queryCols .= insertComma($queryCols);
                $queryCols .= "num_fac_ven";
                $queryValues .= insertComma($queryValues);
                $queryValues .= "'".$id_factura."'";
            }
        }
        
        
        
        /** Se realiza el insert si se reciben datos del formulario **/
        if( $queryCols != "" && $queryValues != ""){
            
                $nextFichaQ = "SELECT MAX(num_ficha) as next FROM ficha_tecnica_manicura";
                $nextRS = $linkPDO->query($nextFichaQ);
                $nextFA = $nextRS->fetch();

                if($nextFA['next'] === null){
                    $idFichaTecnica = 1;
                }else{
                    $idFichaTecnica = ($nextFA['next']+1);
                }
            
            $queryCols .= insertComma($queryCols);
            $queryCols .= "num_ficha";
            $queryValues .= insertComma($queryValues);
            $queryValues .= "'".$idFichaTecnica."'";
            
            $queryCols .= insertComma($queryCols);
            $queryCols .= "fecha";
            $queryValues .= insertComma($queryValues);
            $queryValues .= "'".$hoy."'";
            
            $queryCols .= insertComma($queryCols);
            $queryCols .= "nit";
            $queryValues .= insertComma($queryValues);
            $queryValues .= $codSuc;
            
 
$all_query_ok=true;
            
            $insertQuery = "INSERT INTO ficha_tecnica_manicura($queryCols) VALUES($queryValues)";
            if( !$result = $linkPDO->exec($insertQuery) ){
                /** Insert failure **/
                echo '<script>console.log("error al insertar");</script>';
                $all_query_ok=false;
            }else{
                /** Insert success **/
                echo '<script>console.log("Éxito al insertar");</script>';
                
                savepoint("citas");
                
                $citasCount = $_POST['citas_count'];
                $citasArray[] = ""; /** array to store the results of the queries to check for success **/
                $citasSuccess = 0; /** counter to check if all inserts were succesful **/
                
                for($a = 0;$a < $citasCount; $a++){
                    $fechaCita = limpiarcampo($_POST['fecha_cita_'.$a]);
                    $motivoCita = limpiarcampo($_POST['motivo_cita_'.$a]);
                    
                    $citasInsert = "INSERT INTO art_ficha_manicura_citas(num_ficha,fecha,motivo,nit) VALUES($idFichaTecnica,'$fechaCita','$motivoCita',$codSuc)";
                    if($citasArray[$a] = $linkPDO->exec($citasInsert)){
                       $citasSuccess++;
                    }
                }
                
                if($citasSuccess == $citasCount){
           

if($all_query_ok){
    header("Location: ficha_manicura.php?state=success");
}
else{eco_alert("ERROR! Intente nuevamente");}
                
                }else{
                    /** One or more insert failed, Generate retry button **/
                    //rollback_to_sp("insumos");
                    /** cancel the whole operation **/

                    echo '<script>console.log("Error al insertar, se cancela todo");</script>';
                }
                
            }
            
        }else{
            /** No se registraron datos del formulario, se cancela el insert **/
        }
        
    }
?>
<!DOCTYPE html>
<html>
	<head>
	<?php require_once("HEADER.php"); ?>
		<?php require_once("js_global_vars.php"); ?>	
	<link href="css/multi-select.css" rel="stylesheet" type="text/css" />
        <link href="css/manicura.css" rel="stylesheet" type="text/css" />
        <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

	</head>
	<body>
            <div class="container ">
            <!-- Push Wrapper -->
            <div class="mp-pusher" id="mp-pusher">
                                    <?php require_once("menu_izq.php"); ?>
                                    <?php require_once("menu_top.php"); ?>
                                    <?php require_once("boton_menu.php"); ?>	


            <div class="uk-width-9-10 uk-container-center">

                <!-- <h1 align="center">Colorimetría y Texturización</h1> -->
                
                
                <form class="uk-form" method="post" action="">
                
                <div style="text-align: center; margin: 10px;">
                    <div id="num_ficha_container" >
                        <label for="" style="display: inline-block; color: #FFF; font-size: 24px;">FICHA TECNICA DE MANICURA No. </label>
                        <span id="num_ficha_large" style="display: inline-block;"></span>
                    </div>
                </div>
                
                <ul class="uk-tab" data-uk-switcher="{connect:'#manicura-switchers',swiping:false}">
                    <li class="uk-active"><a href="#">Ficha del Cliente</a></li>
                    <li><a href="#">Registro de Citas</a></li>
                </ul>

                    
                    <ul id="manicura-switchers" class="uk-switcher contenedor-manicura">
                        <li class="uk-active datos-cliente">
                            <div id="block_datos_cliente">
                                <p>Datos del Cliente</p>
                                <div>
                                    <div class="input-container">
                                        <label for="">Documento de Identidad</label>
                                        <input name="id_cli" id="id_cli" type="text" required>
                                    </div>
                                    <div class="input-container">
                                        <label for="">Nombres y Apellidos</label>
                                        <input name="nombres" id="nombres" type="text" onBlur="load_cli_sala(this);" required>
                                    </div>
                                    <div class="input-container">
                                        <label for="">Dirección</label >
                                        <input name="direccion" id="direccion" type="text" required>
                                    </div>
                                </div>
                                <div>
                                    <div class="input-container">
                                        <label for="">Teléfono</label>
                                        <input name="tel_cli" id="tel_cli" type="text" required>
                                    </div>
                                    <div class="input-container">
                                        <label for="">Ciudad</label>
                                        <input name="ciudad_cli" id="ciudad_cli" type="text" value="SARAVENA" required>
                                    </div>
                                    <div class="input-container">
                                        <label for="">e-mail</label>
                                        <input name="email_cli" id="email_cli" type="text">
                                    </div>
                                </div>
                            </div>
                            <div id="block_estado_primera_visita">
                                <span>Estado de las Uñas en la Primera Visita</span>
                                <div>
                                    <span>Mano Izquierda</span>
                                    <div class="input-container">
                                        <label for="">Meñique</label>
                                        <input name="primera_visita_izq_menique" id="primera_visita_izq_menique" type="text"> 
                                    </div>
                                    <div class="input-container">
                                        <label for="">Anular</label>
                                        <input name="primera_visita_izq_anular" id="primera_visita_izq_anular" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Medio</label>
                                        <input name="primera_visita_izq_medio" id="primera_visita_izq_medio" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Indice</label>
                                        <input name="primera_visita_izq_indice" id="primera_visita_izq_indice" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Pulgar</label>
                                        <input name="primera_visita_izq_pulgar" id="primera_visita_izq_pulgar" type="text">
                                    </div>
                                </div>
                                <div>
                                    <span>Mano Derecha</span>
                                    <div class="input-container">
                                        <label for="">Meñique</label>
                                        <input name="primera_visita_der_menique" id="primera_visita_der_menique" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Anular</label>
                                        <input name="primera_visita_der_anular" id="primera_visita_der_anular" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Medio</label>
                                        <input name="primera_visita_der_medio" id="primera_visita_der_medio" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Indice</label>
                                        <input name="primera_visita_der_indice" id="primera_visita_der_indice" type="text">
                                    </div>
                                    <div class="input-container">
                                        <label for="">Pulgar</label>
                                        <input name="primera_visita_der_pulgar" id="primera_visita_der_pulgar" type="text">
                                    </div>
                                </div>
                                <span>*R-Rotas *H-Micosis/Hongos *L-Levantamientos *M-Mordidas *I-Infectadas *A-Amarilleadas *S-Sanas </span>
                            </div>
                            <div id="block_tratamiento">
                                <div class="input-container">
                                    <label for="">Tratamiento que se Realiza</label>
                                    <textarea name="tratamiento_realizar" id="tratamiento_realizar"></textarea>
                                </div>
                            </div>
                            <div id="block_cuidados">
                                <div class="input-container">
                                    <label for="">Cuidados Recomendados Fuera del Centro</label>
                                    <textarea name="cuidados_recomendados" id="cuidados_recomendados"></textarea>
                                </div>
                                <div class="input-container">
                                    <label for="">Alergias</label>
                                    <textarea name="alergias" id="alergias"></textarea>
                                </div>
                            </div>
                        </li>
                        
                        <li id="citas-tab">
                            <div id="citas-container">
                                <p style="display: inline-block;">Registro de Citas</p>
                                <div id="add_cita_btn" class="uk-button uk-button-primary" type="button"><i class="uk-icon-plus-circle uk-icon-large"></i></div>
                                <input type="hidden" name="citas_count" id="citas_count" value="0">
                            </div>
                        </li>
                    </ul>
                    <a class="uk-button uk-button-danger" href="lista_fichas_manicura.php" style="position: absolute; top: 40px;right: 70px;">Volver</a>
                    
                    
                    <div id="numero_factura_container">
                        <div class="input-container">
                            <label for="">Número de Factura de Venta</label>
                            <input name="id_factura" id="id_factura" type="text" pattern="[0-9]*" title="Solo se permiten valores numéricos.">
                        </div>
                        <div class="input-container">
                            <label for="">Fecha de Inicio del Proceso</label>
                            <input name="inicio_tratamiento" id="inicio_tratamiento" type="datetime-local" step="1" value="<?php echo $FECHA_HORA; ?>">
                        </div>
                        <button class="uk-button uk-button-success" type="submit" name="send-form" id="send-form" value="Guardar">Guardar</button>
                    </div>
                    
                    
                    
                </form>
            </div>
	</body>
        
    <?php require_once("FOOTER.php"); ?>	
    <?php require_once("autoCompletePack.php"); ?>	
    <script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
    <script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js"></script>
    <script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
    <script language="javascript1.5" type="text/javascript" src="JS/fac_ven.js?<?php  echo "$LAST_VER"; ?>" ></script>
    
    <script type="text/javascript" language="javascript1.5" src="js/plugins/toastr/toastr.min.js"></script>
        
    <script type="text/javascript" language="javascript1.5">

    /*$(document).bind("mobileinit", function(){ 
            $('#page').on('pageinit', function() { 
                    simplePopUp('Welcome'); 
            }); 
    });
    */
    //$(document).bind("pagebeforeshow", function() {
    $(document).ready( function() {
        call_autocomplete('ID',$('#nombres'),'ajax/busq_cli.php');
    });

    /** Metodo para crear dinamicamente un item en la lista de insumos **/
    function addCita(){
        $("#citas-container").append(
            '<div id="citas_row_'+citasC+'" class="citas_row_'+citasC+'">'+
                '<div class="citas-input-container fecha_cita_container">'+
                    '<label for="">Fecha de la Cita</label>'+
                    '<input name="fecha_cita_'+citasC+'" id="fecha_cita_'+citasC+'" type="datetime-local" step="0" required class="fecha_cita">'+
                '</div>'+
                '<div class="citas-input-container motivo_cita_container">'+
                    '<label for="">Motivo de la Cita</label>'+
                    '<textarea name="motivo_cita_'+citasC+'" id="motivo_cita_'+citasC+'" class="motivo_cita"></textarea>'+
                '</div>'+
                '<div class="remove-cita">'+
                    '<img id="remove_cita_'+citasC+'" class="remove_cita_click" src="imagenes/delete.png">'+
                '</div>'+
            '</div>'
        );

        citasC++;
        $("#citas_count").val(citasC); /** se declara el numero de articulos en un input hidden **/
    }

    /** Metodo para eliminar un item determinado de la lista de insumos **/
    function removeCita(){
        var rowi = $(this).attr('id');
        /** remover 'remove_insumo_' para obtener la posicion del contador **/
        rowi = rowi.replace("remove_cita_","");
        rowi = parseInt(rowi);
        /** eliminar el row correspondiente **/
        $("#citas_row_"+rowi).remove();
        /** ajustar los consecutivos de las filas superiores para que no se salten los consecutivos en los inputs **/
        if( rowi < citasC){
            for(var a = rowi+1; a <= citasC; a++){
                $("#citas_row_"+a).attr("id","citas_row_"+(a-1));
                $("#citas_row_"+a).attr("class","citas_row_"+(a-1));
                
                $("#fecha_cita_"+a).attr("id","fecha_cita_"+(a-1));
                $("#fecha_cita_"+a).attr("class","fecha_cita_"+(a-1));
                
                $("#motivo_cita_"+a).attr("id","motivo_cita_"+(a-1));
                $("#motivo_cita_"+a).attr("class","motivo_cita_"+(a-1));
                
                $("#remove_cita_"+a).attr("id","remove_cita_"+(a-1));
            }
        }
        citasC--;
        $("#citas_count").val(citasC); /** se declara el numero de articulos en un input hidden **/
    }
   
    /** Ajax query para verificar la existencia de una factura para ser relacionada con la ficha texnica **/
    function verifyFacId(){
        $.ajax({
        url:"ajax/confirmar_fac_ficha_color.php",
        type: "GET",
        dataType: "json",
        success: function(resp){
            
        },
        error: function(xhr){
            toastr.error("Algo salio mal con la solicitud al servidor: " + xhr.status);
        }
    });
    }
   
/** Procedimientos globales **/
    
    /** Opciones del toastr **/
    
    toastr.options.progressBar = true;
    toastr.options.preventDuplicates = true;
    toastr.options.positionClass = "toastr-show-on-left";
    
    /** Se genera el aviso de éxito de la creacion de la ficha **/
    <?php 
    if( isset($_GET['state'])){
        if( limpiarcampo($_GET['state']) == "success" ){
            echo "toastr.success('Se ha creado la ficha con éxito','Registro de Nueva Ficha');";
        }
    }
    ?>
    
    /** Se inicializa el contador de los insumos **/
    var citasC = 0;

    /** Se carga el siguiente consecutivo de la ficha técnica de cabello **/
    <?php
        $nextFichaQ = "SELECT MAX(num_ficha) as next FROM ficha_tecnica_manicura";
        $nextRS = $linkPDO->query($nextFichaQ);
        $nextFA = $nextRS->fetch();
        
        if($nextFA['next'] === null){
            echo "$('#num_ficha_large').html('1');";
        }else{
            echo "$('#num_ficha_large').html('".($nextFA['next']+1)."');";
        }
    ?>
        
    /** Se ajusta la altura de los contenedores dinamicos en base a la altura de la pantalla con el evento resize **/
    $(window).resize(function() {
        var switcherContainer =  ($(window).height() - 226).toString()+"px"; /** altura dinamica de la ventana principal **/
        var fillerTextarea =  ($(window).height() - 506).toString()+"px"; /** altura dinamica para rellenar el primer tab en pantallas muy altas **/
        var insumosContainer = ($(window).height() - 342).toString()+"px"; /** altura dinamica para el contenedor de insumos **/
        
        $(".contenedor-manicura").css("height",switcherContainer);
        $(".double_txtarea textarea").css("height",fillerTextarea);
        $("#citas-container").css("height",insumosContainer);
    });
    /** Se dispara resize para ajustar el tamaño al cargar la página por primera vez **/
    $(window).trigger('resize');
    
    
    /** Listeners **/
     
    $("#citas-container").on("click","#add_cita_btn",addCita); /** Agregar item a la lista de insumos **/
    $("#citas-container").on("click",".remove_cita_click",removeCita); /** Eliminar item de la lista de insumos **/
    
    $("#manicura-switchers").on("click",".id_factura",verifyFacId); /** Verificar que la factura a relcionar exista **/
    
    $("form").on("click","#send-form",function(){
        for(var a = 0; a < citasC; a++){
            var fechaTemp = $("#fecha_cita_"+a).val();
            
            if(fechaTemp === ""){
                
                $(".uk-tab > li:eq(1)").trigger("click");
                
                //$("#fecha_cita_"+a).focus();
                toastr.info("Debe ingresar al menos la fecha de la cita, si no desea cargar más citas elimine el registro.","Campo Vacío");
                break;
            }
        }
    });
    
    $("form").on("click","#send-form",function(){
       
            var idTemp = $("#id_cli").val();
            var nomTemp = $("#nombres").val();
            var dirTemp = $("#direccion").val();
            var telTemp = $("#tel_cli").val();
            var ciudadTemp = $("#ciudad_cli").val();
            
            if(idTemp === "" || nomTemp === "" || dirTemp === "" || telTemp === "" || ciudadTemp === ""){
                
                $(".uk-tab > li:eq(0)").trigger("click");
                
                //$("#fecha_cita_"+a).focus();
                toastr.info("Debe ingresar los datos del cliente.","Campo Vacío");
                
            }
        
    });
    
    $("form").on("keydown","#id_factura",function(e){
        //alert(e.keyCode);
        // Allow: backspace, delete, tab, escape, enter
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
             // Allow: Ctrl+A,
            ( e.keyCode === 65 && (e.ctrlKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
            toastr.error("Solo números");
        }
        
    }); /** Controlar los caracteres que se pueden ingresar por teclado en determinados inputs, sólo numéricos en este caso **/
    
    
    $("form").on("keydown",".cantidad_insumo",function(e){
        //alert(e.keyCode);
        // Allow: backspace, delete, tab, escape, enter, dot
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190, 110]) !== -1 ||
             // Allow: Ctrl+A,
            ( e.keyCode === 65 && (e.ctrlKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
            toastr.error("Solo números o puntos");
        }
        
    }); /** Controlar los caracteres que se pueden ingresar por teclado en determinados inputs, sólo numéricos en este caso **/
    
    </script>
</html>