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
        $queryUpdate = "";
        
        if( isset($_POST['id_cli']) ) {
            if( $_POST['id_cli'] != "" )
            {
                $id_cli = limpiarcampo($_POST['id_cli']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "id_cli='".$id_cli."'";
            }
        }
        if( isset($_POST['nombres']) ) {
            if( $_POST['nombres'] != "" )
            {
                $nombres = strtoupper(limpiarcampo($_POST['nombres']));
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "nom_cli='".$nombres."'";
            }
        }
        if( isset($_POST['direccion']) ) {
            if( $_POST['direccion'] != "" )
            {
                $direccion = strtoupper(limpiarcampo($_POST['direccion']));
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "dir='".$direccion."'";
            }
        }
        if( isset($_POST['tel_cli']) ) {
            if( $_POST['tel_cli'] != "" )
            {
                $tel_cli = limpiarcampo($_POST['tel_cli']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "tel_cli='".$tel_cli."'";
            }
        }
        if( isset($_POST['ciudad_cli']) ) {
            if( $_POST['ciudad_cli'] != "" )
            {
                $ciudad = strtoupper(limpiarcampo($_POST['ciudad_cli']));
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "ciudad='".$ciudad."'";
            }
        }
        if( isset($_POST['email_cli']) ) {
            
                $email = limpiarcampo($_POST['email_cli']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "email='".$email."'";
            
        }
        if( isset($_POST['inicio_tratamiento']) ) {
            if( $_POST['inicio_tratamiento'] != "" )
            {
                $inicio_tratamiento = limpiarcampo($_POST['inicio_tratamiento']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "fecha_tratamiento='".$inicio_tratamiento."'";
            }
        }
        
        
        
        
        if( isset($_POST['primera_visita_izq_menique']) ) {
            
                $primera_visita_izq_menique = limpiarcampo($_POST['primera_visita_izq_menique']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_izq_menique='".strtoupper($primera_visita_izq_menique)."'";
            
        }
        if( isset($_POST['primera_visita_izq_anular']) ) {
            
                $primera_visita_izq_anular = limpiarcampo($_POST['primera_visita_izq_anular']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_izq_anular='".strtoupper($primera_visita_izq_anular)."'";
            
        }
        if( isset($_POST['primera_visita_izq_medio']) ) {
            
                $primera_visita_izq_medio = limpiarcampo($_POST['primera_visita_izq_medio']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_izq_medio='".strtoupper($primera_visita_izq_medio)."'";
            
        }
        if( isset($_POST['primera_visita_izq_indice']) ) {
            
                $primera_visita_izq_indice = limpiarcampo($_POST['primera_visita_izq_indice']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_izq_indice='".strtoupper($primera_visita_izq_indice)."'";
            
        }
        if( isset($_POST['primera_visita_izq_pulgar']) ) {
            
                $primera_visita_izq_pulgar = limpiarcampo($_POST['primera_visita_izq_pulgar']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_izq_pulgar='".strtoupper($primera_visita_izq_pulgar)."'";
            
        }
        if( isset($_POST['primera_visita_der_menique']) ) {
            
                $primera_visita_der_menique = limpiarcampo($_POST['primera_visita_der_menique']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_der_menique='".strtoupper($primera_visita_der_menique)."'";
            
        }
        if( isset($_POST['primera_visita_der_anular']) ) {
            
                $primera_visita_der_anular = limpiarcampo($_POST['primera_visita_der_anular']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_der_anular='".strtoupper($primera_visita_der_anular)."'";
            
        }
        if( isset($_POST['primera_visita_der_medio']) ) {
            
                $primera_visita_der_medio = limpiarcampo($_POST['primera_visita_der_medio']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_der_medio='".strtoupper($primera_visita_der_medio)."'";
            
        }
        if( isset($_POST['primera_visita_der_indice']) ) {
            
                $primera_visita_der_indice = limpiarcampo($_POST['primera_visita_der_indice']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_der_indice='".strtoupper($primera_visita_der_indice)."'";
            
        }
        if( isset($_POST['primera_visita_der_pulgar']) ) {
            
                $primera_visita_der_pulgar = limpiarcampo($_POST['primera_visita_der_pulgar']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "primera_visita_der_pulgar='".strtoupper($primera_visita_der_pulgar)."'";
            
        }
        if( isset($_POST['tratamiento_realizar']) ) {
            
                $tratamiento_realizar = limpiarcampo($_POST['tratamiento_realizar']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "tratamiento_realizar='".ucfirst($tratamiento_realizar)."'";
            
        }
        if( isset($_POST['cuidados_recomendados']) ) {
            
                $cuidados_recomendados = limpiarcampo($_POST['cuidados_recomendados']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "cuidados_recomendados='".ucfirst($cuidados_recomendados)."'";
            
        }
        if( isset($_POST['alergias']) ) {
            
                $alergias = limpiarcampo($_POST['alergias']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "alergias='".ucfirst($alergias)."'";
            
        }
        if( isset($_POST['id_factura']) ) {
            
                $id_factura = limpiarcampo($_POST['id_factura']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "num_fac_ven='".$id_factura."'";
            
        }
        
        
        
        /** Se realiza el insert si se reciben datos del formulario **/
        if( $queryUpdate != "" ){
            
                    $idFichaTecnica = limpiarcampo($_POST['ficha']);
                    $pag = limpiarcampo($_POST['pagina']);
                
            /*$queryUpdate .= insertComma($queryUpdate);
            $queryUpdate .= "num_ficha='".$idFichaTecnica."'";*/
            
            $queryUpdate .= insertComma($queryUpdate);
            $queryUpdate .= "fecha_edicion='".$hoy."'";
            
 
            
            $insertQuery = "UPDATE ficha_tecnica_manicura SET $queryUpdate WHERE num_ficha=$idFichaTecnica AND nit=$codSuc";
            if( !$result = $linkPDO->exec($insertQuery) ){
                /** Insert failure **/
                echo '<script>console.log("error al insertar");</script>';
                $all_query_ok=false;
            }else{
                /** Insert success **/
                echo '<script>console.log("Éxito al insertar");</script>';
                

                
                $citasCount = $_POST['citas_count'];
               
			    $citasArray[] = 0; /** array to store the results of the queries to check for success **/
                $citasSuccess = 0; /** counter to check if all inserts were succesful **/
                
                for($a = 0;$a < $citasCount; $a++){
                    $fechaCita = limpiarcampo($_POST['fecha_cita_'.$a]);
                    $motivoCita = limpiarcampo($_POST['motivo_cita_'.$a]);
                    $stateCita = limpiarcampo($_POST['update_cita_state_'.$a]);
                    
                    /** Interpretación de  update_insumo_state_:
                     * new = El insumo es un nuevo registro que debe insertarse
                     * deleted = El insumo fue marcado para su eliminacion y debe borrarse de la tabla
                     * "" o vacio = El insumo puede o no haber sido actualizado y se actualizaran los valores de la tabla con los actuales (que pueden ser los mismos y no ver cambios)
                     * **/
                    if($stateCita == "new")
                    {
                        $citaInsert = "INSERT INTO art_ficha_manicura_citas(num_ficha,fecha,motivo,nit) VALUES($idFichaTecnica,'$fechaCita','$motivoCita',$codSuc)";
                        if($citasArray[$a] = $linkPDO->exec($citaInsert)){
                           $citasSuccess++;
                        }
                    }else if($stateCita == "deleted")
                    {
                        $toDeleteCita = limpiarcampo($_POST['cita_id_'.$a]);
                        $deleteCitaQuery = "DELETE FROM art_ficha_manicura_citas WHERE num_ficha=$idFichaTecnica AND id=$toDeleteCita AND nit=$codSuc";
                        
                        if($citasArray[$a] = $linkPDO->exec($deleteCitaQuery)){
                           $citasSuccess++;
                        }
                    }else if($stateCita == "")
                    {
                        $toUpdateCita = limpiarcampo($_POST['cita_id_'.$a]);
                        $updateInsQuery = "UPDATE art_ficha_manicura_citas SET fecha='$fechaCita',motivo='$motivoCita' WHERE num_ficha=$idFichaTecnica AND id=$toUpdateCita AND nit=$codSuc";
                        
                        if($citasArray[$a] = $linkPDO->exec($updateInsQuery)){
                           $citasSuccess++;
                        }
                    }
                }
                
                if($citasSuccess == $citasCount){
                  

if(1){
                    header("Location: editar_ficha_manicura.php?ficha=$idFichaTecnica&pag=$pag&state=success");
}
else{eco_alert("ERROR! Intente nuevamente");}

                }else{
                    /** One or more insert failed, Generate retry button **/
                    //rollback_to_sp("insumos");
                    /** cancel the whole operation **/
                    rollback_to_sp("ficha");
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
                        <label for="" style="display: inline-block; color: #FFF; font-size: 24px;">EDITAR FICHA TECNICA DE MANICURA No. </label>
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
                                        <input name="nombres" id="nombres" type="text" required>
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
                                        <input name="ciudad_cli" id="ciudad_cli" type="text" required>
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
                        <input type="hidden" name="ficha" id="ficha">
                        <input type="hidden" name="pagina" id="pagina">
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
    
    <script type="text/javascript" language="javascript1.5" src="js/plugins/toastr/toastr.min.js"></script>
        
    <script type="text/javascript" language="javascript1.5">

    /*$(document).bind("mobileinit", function(){ 
            $('#page').on('pageinit', function() { 
                    alert('Welcome'); 
            }); 
    });
    */
    //$(document).bind("pagebeforeshow", function() {
    $(document).ready( function() {
        call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');
    });
    
    /** Metodo para tomar parametros GET con JS **/
    
    /**
    * @param {String} options - url and misc settings
    * @return {object}
    */
    function urlObject(options) {
        "use strict";
        /*global window, document*/

        var url_search_arr,
            option_key,
            i,
            urlObj,
            get_param,
            key,
            val,
            url_query,
            url_get_params = {},
            a = document.createElement('a'),
            default_options = {
                'url': window.location.href,
                'unescape': true,
                'convert_num': true
            };

        if (typeof options !== "object") {
            options = default_options;
        } else {
            for (option_key in default_options) {
                if (default_options.hasOwnProperty(option_key)) {
                    if (options[option_key] === undefined) {
                        options[option_key] = default_options[option_key];
                    }
                }
            }
        }

        a.href = options.url;
        url_query = a.search.substring(1);
        url_search_arr = url_query.split('&');

        if (url_search_arr[0].length > 1) {
            for (i = 0; i < url_search_arr.length; i += 1) {
                get_param = url_search_arr[i].split("=");

                if (options.unescape) {
                    key = decodeURI(get_param[0]);
                    val = decodeURI(get_param[1]);
                } else {
                    key = get_param[0];
                    val = get_param[1];
                }

                if (options.convert_num) {
                    if (val.match(/^\d+$/)) {
                        val = parseInt(val, 10);
                    } else if (val.match(/^\d+\.\d+$/)) {
                        val = parseFloat(val);
                    }
                }

                if (url_get_params[key] === undefined) {
                    url_get_params[key] = val;
                } else if (typeof url_get_params[key] === "string") {
                    url_get_params[key] = [url_get_params[key], val];
                } else {
                    url_get_params[key].push(val);
                }

                get_param = [];
            }
        }

        urlObj = {
            protocol: a.protocol,
            hostname: a.hostname,
            host: a.host,
            port: a.port,
            hash: a.hash.substr(1),
            pathname: a.pathname,
            search: a.search,
            parameters: url_get_params
        };

        return urlObj;
    }

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
                    '<textarea name="motivo_cita_'+citasC+'" id="motivo_cita_'+citasC+'" class="motivo_cita" required></textarea>'+
                '</div>'+
                '<div class="remove-cita">'+
                    '<img id="remove_cita_'+citasC+'" class="remove_cita_click" src="imagenes/delete.png">'+
                '</div>'+
                '<input value="new" type="hidden" name="update_cita_state_'+citasC+'" id="update_cita_state_'+citasC+'">'+
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
    
    /**
    * @param {String} id - id del div row que se va a marcar para su eliminación, no incluye '#'
    */
    function markForRemoval(id){
        if($("#"+id).hasClass("mark_for_removal")){
            $("#"+id).removeClass("mark_for_removal");
            $("#"+id+" img").attr("src","imagenes/delete.png");
            $("#"+id+" input[id^='update_cita_state_']").val("");
        }else{
            $("#"+id).addClass("mark_for_removal");
            $("#"+id+" img").attr("src","imagenes/revert.png");
            $("#"+id+" input[id^='update_cita_state_']").val("deleted");
            
            toastr.info("La cita será eliminada cuando guarde los cambios a la ficha, para cancelar la eliminación haga click sobre el botón azul a la derecha de la cita.");
        }
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
   
   function loadFicha(ficha){
        $.ajax({
            url:"ajax/cargar_ficha_tecnica_manicura.php",
            type: "GET",
            data: {ficha:ficha},
            dataType: "json",
            success: function(resp){
                $("#num_ficha_large").html(resp[0].num_ficha);
                $("#ficha").val(resp[0].num_ficha);
                if(resp[0].num_fac_ven !== "0"){ 
                    $("#id_factura").val(resp[0].num_fac_ven);  /** num factura **/
                }
                $("#inicio_tratamiento").val(resp[0].fecha_tratamiento.replace(" ","T"));

                $("#id_cli").val(resp[0].id_cli);
                $("#nombres").val(resp[0].nom_cli);
                $("#direccion").val(resp[0].dir);
                $("#tel_cli").val(resp[0].tel_cli);
                $("#ciudad_cli").val(resp[0].ciudad);
                $("#email_cli").val(resp[0].email);
                $("#primera_visita_izq_menique").val(resp[0].primera_visita_izq_menique);
                $("#primera_visita_izq_anular").val(resp[0].primera_visita_izq_anular);
                $("#primera_visita_izq_medio").val(resp[0].primera_visita_izq_medio);
                $("#primera_visita_izq_indice").val(resp[0].primera_visita_izq_indice);
                $("#primera_visita_izq_pulgar").val(resp[0].primera_visita_izq_pulgar);
                $("#primera_visita_der_menique").val(resp[0].primera_visita_der_menique);
                $("#primera_visita_der_anular").val(resp[0].primera_visita_der_anular);
                $("#primera_visita_der_medio").val(resp[0].primera_visita_der_medio);
                $("#primera_visita_der_indice").val(resp[0].primera_visita_der_indice);
                $("#primera_visita_der_pulgar").val(resp[0].primera_visita_der_pulgar);
                $("#tratamiento_realizar").val(resp[0].tratamiento_realizar);
                $("#cuidados_recomendados").val(resp[0].cuidados_recomendados);
                $("#alergias").val(resp[0].alergias);

                if(resp[1].length > 0){
                    for(var a = 0; a < resp[1].length; a++){
                        $("#citas-container").append(
                            '<div id="citas_row_'+citasC+'" class="citas_row_'+citasC+'">'+
                                '<div class="citas-input-container fecha_cita_container">'+
                                    '<label for="">Fecha de la Cita</label>'+
                                    '<input name="fecha_cita_'+citasC+'" id="fecha_cita_'+citasC+'" type="datetime-local" step="0" required class="fecha_cita">'+
                                '</div>'+
                                '<div class="citas-input-container motivo_cita_container">'+
                                    '<label for="">Motivo de la Cita</label>'+
                                    '<textarea name="motivo_cita_'+citasC+'" id="motivo_cita_'+citasC+'" class="motivo_cita" required>'+resp[1][a].motivo+'</textarea>'+
                                '</div>'+
                                '<div class="remove-cita">'+
                                    '<img id="mark_remove_cita_'+citasC+'" onclick="markForRemoval(\'citas_row_'+a+'\')" src="imagenes/delete.png">'+
                                '</div>'+
                            '<input type="hidden" name="update_cita_state_'+citasC+'" id="update_cita_state_'+citasC+'">'+
                            '<input type="hidden" name="cita_id_'+citasC+'" id="cita_id_'+citasC+'" value="'+resp[1][a].id+'">'+
                            '</div>'
                        );
                
                        $("#fecha_cita_"+citasC).val(resp[1][a].fecha.replace(" ","T")); /** Date inputs do not receive direct text value declarations **/

                        citasC++;
                        $("#citas_count").val(citasC); /** se declara el numero de articulos en un input hidden **/
                    }
                }

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
            echo "toastr.success('Se ha editado la ficha con éxito','Edición de Ficha');";
        }
    }
    ?>
    
    /** Se inicializa el contador de los insumos **/
    var citasC = 0;
    
        /** Se toman los parametros GET en un objeto de JS **/
        var urlParameters = urlObject({'url':document.URL});

        /** Se cargan los datos de la ficha a editar **/
        loadFicha(urlParameters.parameters.ficha);
        $("#pagina").val(urlParameters.parameters.pag); /** Se pasa el numero de la pagina al formulario **/

    /** Se carga el siguiente consecutivo de la ficha técnica de cabello **/
    
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