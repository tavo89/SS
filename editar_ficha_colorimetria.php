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
                $nombres = limpiarcampo($_POST['nombres']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "nom_cli='".strtoupper($nombres)."'";
            }
        }
        if( isset($_POST['direccion']) ) {
            if( $_POST['direccion'] != "" )
            {
                $direccion = limpiarcampo($_POST['direccion']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "dir='".strtoupper($direccion)."'";
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
                $ciudad = limpiarcampo($_POST['ciudad_cli']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "ciudad='".strtoupper($ciudad)."'";
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
        if( isset($_POST['apariencia_inicial']) ) {
            
                $apariencia_inicial = limpiarcampo($_POST['apariencia_inicial']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "apariencia_inicial='".ucfirst($apariencia_inicial)."'";
            
        }
        if( isset($_POST['deseo_cliente']) ) {
            
                $deseo_cliente = limpiarcampo($_POST['deseo_cliente']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "deseo_cliente='".ucfirst($deseo_cliente)."'";
            
        }
        
        /** Procesos anteriores, multi checkbox guardado en una sola columna **/
        $valorProcesosAnt = "";
            if( isset($_POST['pa_color']) ) {
                if( $_POST['pa_color'] != "" )
                {
                    $pa_color = limpiarcampo($_POST['pa_color']);
                    $valorProcesosAnt .= insertStraightBar($valorProcesosAnt);
                    $valorProcesosAnt .= $pa_color;
                }
            }
            if( isset($_POST['pa_aliser']) ) {
                if( $_POST['pa_aliser'] != "" )
                {
                    $pa_aliser = limpiarcampo($_POST['pa_aliser']);
                    $valorProcesosAnt .= insertStraightBar($valorProcesosAnt);
                    $valorProcesosAnt .= $pa_aliser;
                }
            }
            if( isset($_POST['pa_decoloracion']) ) {
                if( $_POST['pa_decoloracion'] != "" )
                {
                    $pa_decoloracion = limpiarcampo($_POST['pa_decoloracion']);
                    $valorProcesosAnt .= insertStraightBar($valorProcesosAnt);
                    $valorProcesosAnt .= $pa_decoloracion;
                }
            }
            if( isset($_POST['pa_permanente']) ) {
                if( $_POST['pa_permanente'] != "" )
                {
                    $pa_permanente = limpiarcampo($_POST['pa_permanente']);
                    $valorProcesosAnt .= insertStraightBar($valorProcesosAnt);
                    $valorProcesosAnt .= $pa_permanente;
                }
            }
            if( isset($_POST['pa_genna']) ) {
                if( $_POST['pa_genna'] != "" )
                {
                    $pa_genna = limpiarcampo($_POST['pa_genna']);
                    $valorProcesosAnt .= insertStraightBar($valorProcesosAnt);
                    $valorProcesosAnt .= $pa_genna;
                }
            }
            
                if( $valorProcesosAnt != ""){
                    $queryUpdate .= insertComma($queryUpdate);
                    $queryUpdate .= "procesos_anteriores='".$valorProcesosAnt."'";
                }else if($valorProcesosAnt == ""){
                    $queryUpdate .= insertComma($queryUpdate);
                    $queryUpdate .= "procesos_anteriores=''";
                }
    
        if( isset($_POST['pa_otros_long']) ) {
            
                $pa_otros_long = limpiarcampo($_POST['pa_otros_long']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "otros_procesos_ant='".ucfirst($pa_otros_long)."'";
            
        }
        if( isset($_POST['tipo_estado_fibra_capilar']) ) {
            
                $tipo_estado_fibra_capilar = limpiarcampo($_POST['tipo_estado_fibra_capilar']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "desc_tipo_cabello='".ucfirst($tipo_estado_fibra_capilar)."'";
            
        }
        if( isset($_POST['tono_natural']) ) {
            
                $tono_natural = limpiarcampo($_POST['tono_natural']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "tono_natural='".ucfirst($tono_natural)."'";
            
        }
        if( isset($_POST['porcentaje_canas']) ) {
            
                $porcentaje_canas = limpiarcampo($_POST['porcentaje_canas']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "porcentaje_canas='".$porcentaje_canas."'";
            
        }
        if( isset($_POST['forma_cabello']) ) {
            if( $_POST['forma_cabello'] != "-Seleccionar-" )
            {
                $forma_cabello = limpiarcampo($_POST['forma_cabello']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "forma_cabello='".$forma_cabello."'";
            }else if( $_POST['forma_cabello'] == "-Seleccionar-" ){
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "forma_cabello=''";
            }
        }
        if( isset($_POST['proceso_a_realizar']) ) {
            
                $proceso_a_realizar = limpiarcampo($_POST['proceso_a_realizar']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "proceso_realizar='".ucfirst($proceso_a_realizar)."'";
            
        }
        if( isset($_POST['coloracion_requerida']) ) {
            if( $_POST['coloracion_requerida'] != "-Seleccionar-" )
            {
                $coloracion_requerida = limpiarcampo($_POST['coloracion_requerida']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "coloracion_requerida='".$coloracion_requerida."'";
            }else if( $_POST['coloracion_requerida'] == "-Seleccionar-" ){
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "coloracion_requerida=''";
            }
        }
        if( isset($_POST['alteraciones_daños_fibra']) ) {
            if( $_POST['alteraciones_daños_fibra'] != "" )
            {
                $alteraciones_daños_fibra = limpiarcampo($_POST['alteraciones_daños_fibra']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "alteraciones_daños_fibra='".$alteraciones_daños_fibra."'";
            }
        }
        if( isset($_POST['textura_cabello']) ) {
            if( $_POST['textura_cabello'] != "-Seleccionar-" )
            {
                $textura_cabello = limpiarcampo($_POST['textura_cabello']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "textura_cabello='".$textura_cabello."'";
            }else if( $_POST['textura_cabello'] == "-Seleccionar-" ){
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "textura_cabello=''";
            }
        }
        if( isset($_POST['porosidad']) ) {
            if( $_POST['porosidad'] != "-Seleccionar-" )
            {
                $porosidad = limpiarcampo($_POST['porosidad']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "porosidad_cabello='".$porosidad."'";
            }else if( $_POST['porosidad'] == "-Seleccionar-" ){
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "porosidad_cabello=''";
            }
        }
        
        /** multi checkbox del estado de salud almacenado en una sola columna **/
        $valorEstadoSalud = "";
        
            if( isset($_POST['es_alergias']) ) {
                if( $_POST['es_alergias'] != "" )
                {
                    $es_alergias = limpiarcampo($_POST['es_alergias']);
                    $valorEstadoSalud .= insertStraightBar($valorEstadoSalud);
                    $valorEstadoSalud .= $es_alergias;
                }
            }
            if( isset($_POST['es_embarazo']) ) {
                if( $_POST['es_embarazo'] != "" )
                {
                    $es_embarazo = limpiarcampo($_POST['es_embarazo']);
                    $valorEstadoSalud .= insertStraightBar($valorEstadoSalud);
                    $valorEstadoSalud .= $es_embarazo;
                }
            }
            if( isset($_POST['es_diabetes']) ) {
                if( $_POST['es_diabetes'] != "" )
                {
                    $es_diabetes = limpiarcampo($_POST['es_diabetes']);
                    $valorEstadoSalud .= insertStraightBar($valorEstadoSalud);
                    $valorEstadoSalud .= $es_diabetes;
                }
            }
            
                if( $valorEstadoSalud != ""){
                    $queryUpdate .= insertComma($queryUpdate);
                    $queryUpdate .= "estado_salud='".$valorEstadoSalud."'";
                }else if($valorEstadoSalud == ""){
                    $queryUpdate .= insertComma($queryUpdate);
                    $queryUpdate .= "estado_salud=''";
                }
    
        if( isset($_POST['es_otros_long']) ) {
            
                $es_otros_long = limpiarcampo($_POST['es_otros_long']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "estado_salud_otros='".ucfirst($es_otros_long)."'";
            
        }
        if( isset($_POST['procedimiento']) ) {
            
                $procedimiento = limpiarcampo($_POST['procedimiento']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "procedimientos='".ucfirst($procedimiento)."'";
            
        }
        if( isset($_POST['id_factura']) ) {
            
                $factura = limpiarcampo($_POST['id_factura']);
                $queryUpdate .= insertComma($queryUpdate);
                $queryUpdate .= "num_fac_ven='".$factura."'";
            
        }
        
        /** Se realiza el insert si se reciben datos del formulario **/
        if($queryUpdate != ""){
            
            //$idFichaTecnica = limpiarcampo($_POST['id_ficha_cabello']);
            
                $idFichaTecnica = limpiarcampo($_POST['num_ficha']);
                $pag = limpiarcampo($_POST['pagina']);
                
            /*$queryUpdate .= insertComma($queryUpdate);
            $queryUpdate .= "num_ficha='".$idFichaTecnica."'";*/
            
            $queryUpdate .= insertComma($queryUpdate);
            $queryUpdate .= "fecha_edicion='".$hoy."'";
            
            
 


            
            $insertQuery = "UPDATE ficha_tecnica_cabello SET $queryUpdate WHERE num_ficha=$idFichaTecnica AND nit=$codSuc";
            if( !$result = $linkPDO->exec($insertQuery) ){
                /** Update failure **/
                echo '<script>toastr.error("Error al actualizar.");</script>';
                $all_query_ok=false;
            }else{
                /** Update success **/
                echo '<script>toastr.success("Éxito al actualizar.");</script>';
                

                
                $insumosCount = $_POST['insumos_count'];
                $insumosArray[] = 0; /** array to store the results of the queries to check for success **/
                $insumosSuccess = 0; /** counter to check if all inserts were succesful **/
                
                for($a = 0;$a < $insumosCount; $a++){
                    $nombreIns = limpiarcampo($_POST['nombre_insumo_'.$a]);
                    $cantIns = limpiarcampo($_POST['cantidad_insumo_'.$a]);
                    $fabricanteIns = limpiarcampo($_POST['casa_cosm_insumo_'.$a]);
                    $observacionIns = limpiarcampo($_POST['observacion_insumo_'.$a]);
                    $stateIns = limpiarcampo($_POST['update_insumo_state_'.$a]);
                    
                    /** Interpretación de  update_insumo_state_:
                     * new = El insumo es un nuevo registro que debe insertarse
                     * deleted = El insumo fue marcado para su eliminacion y debe borrarse de la tabla
                     * "" o vacio = El insumo puede o no haber sido actualizado y se actualizaran los valores de la tabla con los actuales (que pueden ser los mismos y no ver cambios)
                     * **/
                    if($stateIns == "new")
                    {
                        $insumoInsert = "INSERT INTO art_ficha_cabello(num_ficha,nombre,cantidad,fabricante,observaciones,nit) VALUES($idFichaTecnica,'$nombreIns',$cantIns,'$fabricanteIns','$observacionIns',$codSuc)";
                        if($insumosArray[$a] = $linkPDO->exec($insumoInsert)){
                           $insumosSuccess++;
                        }
                    }else if($stateIns == "deleted")
                    {
                        $toDeleteIns = limpiarcampo($_POST['insumo_id_'.$a]);
                        $deleteInsQuery = "DELETE FROM art_ficha_cabello WHERE num_ficha=$idFichaTecnica AND id=$toDeleteIns AND nit=$codSuc";
                        
                        if($insumosArray[$a] = $linkPDO->exec($deleteInsQuery)){
                           $insumosSuccess++;
                        }
                    }else if($stateIns == "")
                    {
                        $toUpdateIns = limpiarcampo($_POST['insumo_id_'.$a]);
                        $updateInsQuery = "UPDATE art_ficha_cabello SET nombre='$nombreIns',cantidad=$cantIns,fabricante='$fabricanteIns',observaciones='$observacionIns' WHERE num_ficha=$idFichaTecnica AND id=$toUpdateIns AND nit=$codSuc";
                        
                        if($insumosArray[$a] = $linkPDO->exec($updateInsQuery)){
                           $insumosSuccess++;
                        }
                    }
                }
                
                if($insumosSuccess == $insumosCount){
					
 

if(1){header("Location: editar_ficha_colorimetria.php?ficha=$idFichaTecnica&pag=$pag&state=success");}
else{eco_alert("ERROR! Intente nuevamente");}
 

                }else{
                    /** One or more insert failed, Generate retry button **/
                    //rollback_to_sp("insumos");
                    /** cancel the whole operation **/
                    $all_query_ok=false;
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
        <link href="css/colorimetria.css" rel="stylesheet" type="text/css" />
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
                        <label for="" style="display: inline-block; color: #FFF; font-size: 24px;">EDITAR FICHA TECNICA DE COLORIMETRIA Y TEXTURIZACION No. </label>
                        <span id="num_ficha_large" style="display: inline-block;"></span>
                        <!-- <input name="id_ficha_cabello" id="id_ficha_cabello" type="hidden"> -->
                    </div>
                </div>
                
                <ul class="uk-tab" data-uk-switcher="{connect:'#cabello-switchers',swiping:false}">
                    <li class="uk-active"><a href="#">Ficha del Cliente</a></li>
                    <li><a href="#">Planificación del Proceso a Realizar</a></li>
                </ul>

                    
                    <ul id="cabello-switchers" class="uk-switcher contenedor-cabello">
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
                                        <label for="">Dirección</label>
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
                            <div id="block_apariencia_inicial">
                                <div class="input-container double_txtarea">
                                    <label for="">Descripción Apariencia Inicial y Estado General</label>
                                    <textarea name="apariencia_inicial" id="apariencia_inicial"></textarea>
                                </div>
                                <div class="input-container double_txtarea">
                                    <label for="">Análisis del Deseo del Cliente</label>
                                    <textarea name="deseo_cliente" id="deseo_cliente"></textarea>
                                </div>
                            </div>
                            <div id="block_procesos_anteriores">
                                <p>Procesos Anteriores</p>
                                <div class="checkbox-container">
                                    <label>Color</label>
                                    <input type="checkbox" name="pa_color" id="pa_color" value="Color">
                                </div>
                                <div class="checkbox-container">
                                    <label>Aliser</label>
                                    <input type="checkbox" name="pa_aliser" id="pa_aliser" value="Aliser">
                                </div>
                                <div class="checkbox-container">
                                    <label>Decoloración</label>
                                    <input type="checkbox" name="pa_decoloracion" id="pa_decoloracion" value="Decoloración">
                                </div>
                                <div class="checkbox-container">
                                    <label>Permanente</label>
                                    <input type="checkbox" name="pa_permanente" id="pa_permanente" value="Permanente">
                                </div>
                                <div class="checkbox-container">
                                    <label>Genna</label>
                                    <input type="checkbox" name="pa_genna" id="pa_genna" value="Genna">
                                </div>
                                <div class="input-container">
                                    <label for="pa_otros_long">Otros</label>
                                    <input name="pa_otros_long" id="pa_otros_long" type="text">
                                </div>
                            </div>
                            
                            <div id="block_tono_canas_tipo">
                                <div id="tono_natural_container" class="input-container">
                                    <label for="">Tono Natural del Cliente</label>
                                    <input name="tono_natural" id="tono_natural" type="text">
                                </div>
                                <div id="tipo_estado_fibra_container" class="input-container">
                                    <label for="">Descripción del Tipo de Cabello y Estado de la Fibra Capilar</label>
                                    <textarea name="tipo_estado_fibra_capilar" id="tipo_estado_fibra_capilar"></textarea>
                                </div>
                                <div id="porcentaje_canas_container" class="input-container">
                                    <label for="">Porcentaje de Canas</label>
                                    <input name="porcentaje_canas" id="porcentaje_canas" type="text">
                                </div>
                            </div>
                            <div id="block_forma_textura_porosidad">
                                <div class="input-container">
                                    <label for="">Forma del Cabello</label>
                                    <select name="forma_cabello" id="forma_cabello">
                                        <option>-Seleccionar-</option>
                                        <option>Liso</option>
                                        <option>Ondulado</option>
                                        <option>Rizado</option>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <label for="">Textura del Cabello</label>
                                    <select name="textura_cabello" id="textura_cabello">
                                        <option>-Seleccionar-</option>
                                        <option>Fino</option>
                                        <option>Medio</option>
                                        <option>Grueso</option>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <label for="">Porosidad</label>
                                    <select name="porosidad" id="porosidad">
                                        <option>-Seleccionar-</option>
                                        <option>Porosidad Alta</option>
                                        <option>Porosidad Media</option>
                                        <option>Porosidad Baja</option>
                                    </select>
                                </div>
                                <div id="coloracion_requerida_container" class="input-container">
                                    <label for="">Tipo de Coloración Requerida por el Cliente</label>
                                    <select name="coloracion_requerida" id="coloracion_requerida">
                                        <option>-Seleccionar-</option>
                                        <option>Temporal</option>
                                        <option>Semipermanente</option>
                                        <option>Permanente</option>
                                        <option>Decoloración</option>
                                        <option>Decoloración+Coloración</option>
                                    </select>
                                </div>
                            </div>
                            <div id="block_proceso_alteraciones">
                                <div id="proceso_a_realizar_container" class="input-container">
                                    <label for="">Proceso a Realizar</label>
                                    <textarea name="proceso_a_realizar" id="proceso_a_realizar"></textarea>
                                </div>
                                <div id="alteraciones_daños_fibra_container" class="checkbox-container">
                                    <label>Se Observan Signos de Alteraciones o <br>Daños en el Cuero Cabelludo o Fibra Capilar</label><br>
                                    <select name="alteraciones_daños_fibra" id="alteraciones_daños_fibra">
                                        <option>No</option>
                                        <option>Sí</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="block_estado_salud">
                                <p>Estado de Salud del Cliente</p>
                                <div class="checkbox-container">
                                    <label>Alergias</label>
                                    <input type="checkbox" name="es_alergias" id="es_alergias" value="Alergias">
                                </div>
                                <div class="checkbox-container">
                                    <label>Embarazo</label>
                                    <input type="checkbox" name="es_embarazo" id="es_embarazo" value="Embarazo">
                                </div>
                                <div class="checkbox-container">
                                    <label>Diabetes</label>
                                    <input type="checkbox" name="es_diabetes" id="es_diabetes" value="Diabetes">
                                </div>
                                <div class="input-container">
                                    <label for="es_otros_long">Otros</label>
                                    <input name="es_otros_long" id="es_otros_long" type="text">
                                </div>
                            </div>
                            
                        </li>
                        <li id="insumos-tab">
                            <div id="insumos-container">
                                <p style="display: inline-block;">Insumos Aplicados</p>
                                <div id="add_insumo_btn" class="uk-button uk-button-primary" type="button"><i class="uk-icon-plus-circle uk-icon-large"></i></div>
                                <input type="hidden" name="insumos_count" id="insumos_count" value="0">
                            </div>
                            <div style="padding: 10px;">
                                <div id="procedimiento_container" class="input-container">
                                    <label for="">Procedimiento</label>
                                    <textarea name="procedimiento" id="procedimiento"></textarea>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a class="uk-button uk-button-danger" href="lista_fichas_colorimetria.php?pag=<?php echo limpiarcampo($_GET['pag']); ?>" style="position: absolute; top: 40px;right: 70px;">Volver</a>
                    
                    
                    <div id="numero_factura_container">
                        <div class="input-container">
                            <label for="">Número de Factura de Venta</label>
                            <input name="id_factura" id="id_factura" type="text" pattern="[0-9]*" title="Solo se permiten valores numéricos.">
                        </div>
                        <div class="input-container">
                            <label for="">Fecha de Inicio del Proceso</label>
                            <input name="inicio_tratamiento" id="inicio_tratamiento" type="datetime-local" step="1">
                        </div>
                        <input type="hidden" name="num_ficha" id="num_ficha">
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
    function addInsumo(){
        $("#insumos-container").append(
            '<div id="insumos_row_'+insumoC+'" class="insumos_row_'+insumoC+' new_insumo">'+
                '<div class="insumos-input-container nombre_insumo_container">'+
                    '<label for="">Nombre y Tipo</label>'+
                    '<input name="nombre_insumo_'+insumoC+'" id="nombre_insumo_'+insumoC+'" type="text" required>'+
                '</div>'+
                '<div class="insumos-input-container cantidad_insumo_container">'+
                    '<label for="">Cantidad</label>'+
                    '<input name="cantidad_insumo_'+insumoC+'" id="cantidad_insumo_'+insumoC+'" type="text" class="cantidad_insumo" required>'+
                '</div>'+
                '<div class="insumos-input-container casa_cosm_insumo_container">'+
                    '<label for="">Casa Cosmética</label>'+
                    '<input name="casa_cosm_insumo_'+insumoC+'" id="casa_cosm_insumo_'+insumoC+'" type="text" required>'+
                '</div>'+
                '<div class="insumos-input-container observacion_insumo_container">'+
                    '<label for="">Observaciones</label>'+
                    '<textarea name="observacion_insumo_'+insumoC+'" id="observacion_insumo_'+insumoC+'"></textarea>'+
                '</div>'+
                '<div class="remove-insumo">'+
                    '<img id="remove_insumo_'+insumoC+'" class="remove_insumo_click" src="imagenes/delete.png">'+
                '</div>'+
                '<input type="hidden" name="update_insumo_state_'+insumoC+'" id="update_insumo_state_'+insumoC+'" value="new">'+
            '</div>'
        );

        insumoC++;
        $("#insumos_count").val(insumoC); /** se declara el numero de articulos en un input hidden **/
    }

    /** Metodo para eliminar un item determinado de la lista de insumos **/
    function removeInsumo(){
        var rowi = $(this).attr('id');
        /** remover 'remove_insumo_' para obtener la posicion del contador **/
        rowi = rowi.replace("remove_insumo_","");
        rowi = parseInt(rowi);
        /** eliminar el row correspondiente **/
        $("#insumos_row_"+rowi).remove();
        /** ajustar los consecutivos de las filas superiores para que no se salten los consecutivos en los inputs **/
        if( rowi < insumoC){
            for(var a = rowi+1; a <= insumoC; a++){
                $("#insumos_row_"+a).attr("id","insumos_row_"+(a-1));
                $("#insumos_row_"+a).attr("class","insumos_row_"+(a-1));
                
                $("#nombre_insumo_"+a).attr("name","nombre_insumo_"+(a-1));
                $("#nombre_insumo_"+a).attr("id","nombre_insumo_"+(a-1));
                
                $("#cantidad_insumo_"+a).attr("name","cantidad_insumo_"+(a-1));
                $("#cantidad_insumo_"+a).attr("id","cantidad_insumo_"+(a-1));
                
                $("#casa_cosm_insumo_"+a).attr("name","casa_cosm_insumo_"+(a-1));
                $("#casa_cosm_insumo_"+a).attr("id","casa_cosm_insumo_"+(a-1));
                
                $("#observacion_insumo_"+a).attr("name","observacion_insumo_"+(a-1));
                $("#observacion_insumo_"+a).attr("id","observacion_insumo_"+(a-1));
                
                $("#remove_insumo_"+a).attr("id","remove_insumo_"+(a-1));
                
                $("#update_insumo_state_"+a).attr("name","update_insumo_state_"+(a-1));
                $("#update_insumo_state_"+a).attr("id","update_insumo_state_"+(a-1));
            }
        }
        insumoC--;
        $("#insumos_count").val(insumoC); /** se declara el numero de articulos en un input hidden **/
    }
    
    /** Metodo para marcar insumos que se van a eliminar de la ficha, tambien se usa este metodo para cancelar la eliminacion del insumo **/
    
    /**
    * @param {String} id - id del div row que se va a marcar para su eliminación, no incluye '#'
    */
    function markForRemoval(id){
        if($("#"+id).hasClass("mark_for_removal")){
            $("#"+id).removeClass("mark_for_removal");
            $("#"+id+" img").attr("src","imagenes/delete.png");
            $("#"+id+" input[id^='update_insumo_state_']").val("");
        }else{
            $("#"+id).addClass("mark_for_removal");
            $("#"+id+" img").attr("src","imagenes/revert.png");
            $("#"+id+" input[id^='update_insumo_state_']").val("deleted");
            
            toastr.info("El insumo será eliminado cuando guarde los cambios a la ficha, para cancelar la eliminación haga click sobre el botón azul a la derecha del insumo.");
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
        url:"ajax/cargar_ficha_tecnica_colorimetria.php",
        type: "GET",
        data: {ficha:ficha},
        dataType: "json",
        success: function(resp){
            $("#id_cli").val(resp[0].id_cli);
            $("#nombres").val(resp[0].nom_cli);
            $("#direccion").val(resp[0].dir);
            $("#tel_cli").val(resp[0].tel_cli);
            $("#ciudad_cli").val(resp[0].ciudad);
            $("#email_cli").val(resp[0].email);
            $("#apariencia_inicial").val(resp[0].apariencia_inicial);
            $("#deseo_cliente").val(resp[0].deseo_cliente);
            
            /** Se checkean los checkboxes correspondientes de procesos anteriores **/
            if(resp[0].procesos_anteriores !== ""){
                var paTemp = resp[0].procesos_anteriores;
                if(paTemp.includes("Color")){
                    $("#pa_color").attr("checked","checked");
                }
                if(paTemp.includes("Aliser")){
                    $("#pa_aliser").attr("checked","checked");
                }
                if(paTemp.includes("Decoloración")){
                    $("#pa_decoloracion").attr("checked","checked");
                }
                if(paTemp.includes("Permanente")){
                    $("#pa_permanente").attr("checked","checked");
                }
                if(paTemp.includes("Genna")){
                    $("#pa_genna").attr("checked","checked");
                }
            }
            $("#pa_otros_long").val(resp[0].otros_procesos_ant);
            $("#tono_natural").val(resp[0].tono_natural);
            $("#tipo_estado_fibra_capilar").val(resp[0].desc_tipo_cabello);
            $("#porcentaje_canas").val(resp[0].porcentaje_canas);
            if(resp[0].forma_cabello === ""){ 
                $("#forma_cabello").val("-Seleccionar-");
            }else{
                $("#forma_cabello").val(resp[0].forma_cabello);
            }
            if(resp[0].textura_cabello === ""){ 
                $("#textura_cabello").val("-Seleccionar-");
            }else{
                $("#textura_cabello").val(resp[0].textura_cabello);
            }
            if(resp[0].porosidad_cabello === ""){ 
                $("#porosidad").val("-Seleccionar-");
            }else{
                $("#porosidad").val(resp[0].porosidad_cabello);
            }
            if(resp[0].coloracion_requerida === ""){ 
                $("#coloracion_requerida").val("-Seleccionar-");
            }else{
                $("#coloracion_requerida").val(resp[0].coloracion_requerida);
            }
            $("#proceso_a_realizar").val(resp[0].proceso_realizar);
            $("#alteraciones_daños_fibra").val(resp[0].alteraciones_daños_fibra);
            
            /** Se checkean los checkboxes correspondientes de estado de salud **/
            if(resp[0].estado_salud !== ""){
                var esTemp = resp[0].estado_salud;
                if(esTemp.includes("Alergias")){
                    $("#es_alergias").attr("checked","checked");
                }
                if(esTemp.includes("Embarazo")){
                    $("#es_embarazo").attr("checked","checked");
                }
                if(esTemp.includes("Diabetes")){
                    $("#es_diabetes").attr("checked","checked");
                }
            }
            $("#es_otros_long").val(resp[0].estado_salud_otros);
            $("#procedimiento").val(resp[0].procedimientos);
            
            $("#inicio_tratamiento").val( resp[0].fecha_tratamiento.replace(" ","T") ); /** fecha **/
            if(resp[0].num_fac_ven !== "0"){ 
                $("#id_factura").val(resp[0].num_fac_ven);  /** num factura **/
            }
            $("#num_ficha_large").html(resp[0].num_ficha); /** numero rojo de la ficha **/
            $("#num_ficha").val(resp[0].num_ficha); /** se conserva el numero original de la ficha en caso de que alguien cambie la url **/
            
            /** Se carga la lista de insumos si la ficha tiene alguno**/
            if(resp[1].length > 0){
                for(var a = 0; a < resp[1].length; a++){
                    $("#insumos-container").append(
                        '<div id="insumos_row_'+insumoC+'" class="insumos_row_'+insumoC+'">'+
                            '<div class="insumos-input-container nombre_insumo_container">'+
                                '<label for="">Nombre y Tipo</label>'+
                                '<input value="'+resp[1][a].nombre+'" name="nombre_insumo_'+insumoC+'" id="nombre_insumo_'+insumoC+'" type="text" required>'+
                            '</div>'+
                            '<div class="insumos-input-container cantidad_insumo_container">'+
                                '<label for="">Cantidad</label>'+
                                '<input value="'+resp[1][a].cantidad+'" name="cantidad_insumo_'+insumoC+'" id="cantidad_insumo_'+insumoC+'" type="text" class="cantidad_insumo" required>'+
                            '</div>'+
                            '<div class="insumos-input-container casa_cosm_insumo_container">'+
                                '<label for="">Casa Cosmética</label>'+
                                '<input value="'+resp[1][a].fabricante+'" name="casa_cosm_insumo_'+insumoC+'" id="casa_cosm_insumo_'+insumoC+'" type="text" required>'+
                            '</div>'+
                            '<div class="insumos-input-container observacion_insumo_container">'+
                                '<label for="">Observaciones</label>'+
                                '<textarea name="observacion_insumo_'+insumoC+'" id="observacion_insumo_'+insumoC+'">'+resp[1][a].observaciones+'</textarea>'+
                            '</div>'+
                            '<div class="remove-insumo">'+
                                '<img id="mark_remove_insumo_'+insumoC+'" onclick="markForRemoval(\'insumos_row_'+a+'\')"  src="imagenes/delete.png">'+
                            '</div>'+
                            '<input type="hidden" name="update_insumo_state_'+insumoC+'" id="update_insumo_state_'+insumoC+'">'+
                            '<input type="hidden" name="insumo_id_'+insumoC+'" id="insumo_id_'+insumoC+'" value="'+resp[1][a].id+'">'+
                        '</div>'
                    );

                    insumoC++;
                }
                $("#insumos_count").val(insumoC); /** se declara el numero de articulos de insumos en un input hidden **/
            }
        },
        error: function(xhr){
            toastr.error("Algo salio mal con la solicitud al servidor: " + xhr.status);
        }
    });
    }
    
/** Procedimientos globales **/
    
    /** Se inicializa el contador de los insumos **/
    var insumoC = 0;
    
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
    
        /** Se toman los parametros GET en un objeto de JS **/
        var urlParameters = urlObject({'url':document.URL});

        /** Se cargan los datos de la ficha a editar **/
        loadFicha(urlParameters.parameters.ficha);
        $("#pagina").val(urlParameters.parameters.pag); /** Se pasa el numero de la pagina al formulario **/
        
    /** Se ajusta la altura de los contenedores dinamicos en base a la altura de la pantalla con el evento resize **/
    $(window).resize(function() {
        var switcherContainer =  ($(window).height() - 226).toString()+"px"; /** altura dinamica de la ventana principal **/
        var fillerTextarea =  ($(window).height() - 506).toString()+"px"; /** altura dinamica para rellenar el primer tab en pantallas muy altas **/
        var insumosContainer = ($(window).height() - 342).toString()+"px"; /** altura dinamica para el contenedor de insumos **/
        
        $(".contenedor-cabello").css("height",switcherContainer);
        $(".double_txtarea textarea").css("height",fillerTextarea);
        $("#insumos-container").css("height",insumosContainer);
    });
    /** Se dispara resize para ajustar el tamaño al cargar la página por primera vez **/
    $(window).trigger('resize');
    
    
    /** Listeners **/
     
    $("#insumos-container").on("click","#add_insumo_btn",addInsumo); /** Agregar item a la lista de insumos **/
    $("#insumos-container").on("click",".remove_insumo_click",removeInsumo); /** Eliminar item de la lista de insumos **/
    
    $("#cabello-switchers").on("click",".id_factura",verifyFacId); /** Verificar que la factura a relcionar exista **/
    
    $("form").on("click","#send-form",function(){
        for(var a = 0; a < insumoC; a++){
            var nombreTemp = $("#nombre_insumo_"+a).val();
            var cantidadTemp = $("#cantidad_insumo_"+a).val();
            var casaTemp = $("#casa_cosm_insumo_"+a).val();
            
            if(nombreTemp === "" || cantidadTemp === "" || casaTemp === ""){
                
                $(".uk-tab > li:eq(1)").trigger("click");
                
                $("#nombre_insumo_"+a).focus();
                toastr.info("Debe ingresar los datos principales del insumo, si no desea cargar más insumos elimine el articulo.","Campo Vacío");
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