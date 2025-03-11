<button id="offcanvas_menu_bttn" class="uk-button uk-button-danger" data-uk-offcanvas="{target:'#offcanvas-1'}">
<span class="uk-visible-large">M<br>E<br>N<br>U<br></span>
<i class="uk-icon uk-icon-bars"></i></button>



 <!-- Inicio del Menu -->
								
<div id="offcanvas-1" class="uk-offcanvas">

	<div class="uk-offcanvas-bar">

		<ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>
		
							<!-- Nivel de los Desplegables -->   
							<li><a style="background:#405060; text-align:left; padding-left:15px;" href="centro.php"><img style="margin-right:10px; margin-left:20px;" src="Imagenes/favSmart.png" class="icono_ss">Smart Selling</a></li>

							<li class="uk-parent"> <!--  Operaciones Administrativas -->
							
									
								<a href="#"><i class="uk-icon-gear <?php echo $uikitIconSize_menu ?>"></i>Configuraci&oacute;n</a> 
								 

									<ul class="uk-nav-sub "> <!-- La clase uk-nav-sub es necesaria en el UL de la lista de opciones desplegables -->
											<?php								
											if($rolLv==$Adminlvl){
											?>
                                            <li ><a  href="SISTEMA.php"><i class="uk-icon-warning <?php echo $uikitIconSize_menu ?>"></i>&nbsp;SISTEM</a></li>
											<li ><a  href="mod_sucursal.php"><i class="uk-icon-ticket <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Rango Fac.</a></li>
										<!--<li ><a  href="favoritos_busquedas_fac.php"><i class="uk-icon-star <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Favoritos Busq.</a></li>-->
                                        	
                                            <?php 
											}
											?>
                                            
                                            	<?php 
											if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1 && $rolLv==$Adminlvl){
											if($rolLv==$Adminlvl){
											?>
                        <li ><a  href="servicios_gestion_planes_internetListado.php"><i class="uk-icon-globe <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Planes Mensuales</a></li>
                                            
                                             <?php 
											}
											}
											?>

											<?php 
											if($MODULES["CUENTAS_BANCOS"]==1){
											if($rolLv==$Adminlvl){
											?>
                                            <li ><a  href="cuentas_bancos.php"><i class="uk-icon-calculator <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Cuentas-Bancos</a></li>
                                            <?php 
											}
											}
											?>
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"clientes")) && $codSuc>0){
											?>
													
													
<li ><a  href="clientes.php"><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Clientes</a></li>
														
														
											<?php 
											}
											?>
										
										
										
										
													
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"usuarios")) && $codSuc>0){
											?>
										
										
												<li><a href="usuarios.php" ><i class="uk-icon-users <?php echo $uikitIconSize_menu ?>" ></i>&nbsp;Usuarios</a></li>
										
										
											<?php 
											}
											?>
										
										

											<?php 
											if(($rolLv==$Adminlvl ) && $codSuc>0){
											?>
										
										
												 <li><a href="movimientosDB.php"  ><i class="uk-icon-list-alt <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Bit&aacute;coras/Logs</a></li>
										
										
											<?php 
											}
											?>
										
														
										
									</ul>
								
							</li>   <!--  Final de Operaciones Administrativas -->
						
						
							
                            <?php 
								if($MODULES["ARQUEOS"]==1){
											?>
							<li class="uk-parent"> <!--  Arqueos de Caja -->
						
									
								<a   href="#"><i class="uk-icon-money <?php echo $uikitIconSize_menu ?>"></i>Arqueos de Caja</a> 
								
											
									<ul class="uk-nav-sub">
										
										
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"arqueos_informes")) && $codSuc>0){ 
											?>
										
										
												<li><a href="arqueos.php" ><i ></i>&nbsp;Arqueos Por Dia</a></li>
										
												<li><a href="arqueos_por_rango.php" ><i ></i>&nbsp;Arqueos Por Rango</a></li>
										
										
											<?php 
											}
											?>
										
										
										
									</ul>
											
							</li> <!-- Final de Arqueos de Caja -->
						
							<?php 
											}
											?>
						
							<li class="uk-parent"> <!--  Informes -->
							
									
								<a href="#"><i class="uk-icon-file-text-o <?php echo $uikitIconSize_menu ?>"></i>Informes</a> 
								
											
<ul class="uk-nav-sub">
									
<?php  
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"balances_contables")) && $MODULES["BALANCES_CONTABLES"]==1){
											?>
										

												<li><a href="BALANCES.php"><i  class="uk-icon uk-icon-balance-scale"></i>&nbsp;Balances Contables</a></li>
										
										
											<?php 
											}
											?>    									
<?php 
											if($MODULES["EGRESOS_2"]==1 && $rolLv==$Adminlvl){
											?>
												<!--<li><a href="grafica_ventas01.php" ><i  class="uk-icon uk-icon-bar-chart"></i>&nbsp;Ingresos/Egresos</a></li>-->
                                                <li><a href="dashboard_1.php" ><i  class="uk-icon uk-icon-area-chart"></i>&nbsp;Resumen Ventas</a></li>
											<?php 
											}
											?>
								
<?php 
if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_ventas_cli")) && $codSuc>0){?>
<li><a href="LISTAS.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Listados</a></li>							
<?php 
if($MODULES["INFORMES_VENTAS_2"]==1){?>
<li><a href="INFORMES_2.php" ><i ></i>&nbsp;Ventas</a></li>
                                                <?php 
											}
											?>
										   
												<!--	<li><a href="INGRESOS_NETOS.php" >Ingresos NETOS</a></li>   -->
										
										
											<?php 
											}
											?>

												
												
											<?php 
											if($rolLv==$Adminlvl && $codSuc>0 && $MODULES["COMPRAS"]==1){
											?>
										
										
												<!--<li><a href="informes_administrativos.php" >&nbsp;Compras y Proveedores</a></li>-->
										
												<li><a href="informes_administrativos_rango.php" ><i ></i>&nbsp;Compras y Proveedores</a></li>
										
										
											<?php 
											}
											?>
										


										
											<?php  
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_listados")) && $MODULES["BALANCES_CONTABLES"]==1){
											?>
										

												<!--<li><a href="INFORMES.php"><i ></i>&nbsp;Inventarios</a></li>-->
										
										
											<?php 
											}
											?>  
													
												  
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_kardex")) && $codSuc>0){
											?>
										
										
												<!--<li><a href="panel_kardex.php" ><i ></i>&nbsp;Kardex de Inventario</a></li>-->

										
											<?php 
											}
											?>

										
										
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_kardex_fecha")) && $codSuc>0){
											?>
										
										
												<!--<li><a href="panel_kardex_fecha.php" ><i ></i>&nbsp;Kardex por Fecha</a></li> -->

										
											<?php 
											}
											?>    
                                            
                                                                       
										
										
										<?php 
											if($rolLv==$Adminlvl && $codSuc>0 && $MODULES["VENTA_VEHICULOS"]==1){
											?>
										
										
												<!--<li><a href="informes_administrativos.php" >&nbsp;Compras y Proveedores</a></li>-->
										<li><a href="metas_ventas_config.php" ><i class="uk-icon uk-icon-cog"></i>&nbsp;Config. Comisiones</a></li>
										<li><a href="metas_ventas.php" ><i class="uk-icon uk-icon-cog"></i>&nbsp;Metas Ventas</a></li>
                            
                                        <li><a href="INFORMES_NOMINA.php" ><i class="uk-icon uk-icon-area-chart"></i>&nbsp;Informes N&oacute;mina</a></li>
										
										
											<?php 
											}
											?>
                                            
                                            <?php 
											if( ($rolLv==$Adminlvl || val_secc($id_Usu,"informes_nomina")) && $codSuc>0 && $MODULES["VENTA_VEHICULOS"]==1){
											?>
											
                                        	<li><a href="INFORMES_NOMINA2.php" ><i class="uk-icon uk-icon-file-text-o"></i>&nbsp;Informe N&oacute;mina</a></li>
                                            <?php 
											}
											?>
									
									</ul>           
							</li> <!-- Final de  Informes -->

<!-- MODULO ENVIO SMS, MENSAJES DE TEXTO A CELULARES COL -->
<?php 
if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1 && $rolLv==$Adminlvl){
?>

<li class="uk-parent">

<a  href="#"><i  class="uk-icon-globe <?php echo $uikitIconSize_menu ?>"></i>Servicios Internet</a> 
								 

<ul class="uk-nav-sub">  


<li><a href="planes_servicios_INFORMES.php" ><i class="uk-icon-line-chart <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Informes</a></li>

  
<li><a href="SERVICIOS_rutas.php" ><i class="uk-icon-motorcycle echo $uikitIconSize_menu ?>"></i>&nbsp;Rutas</a></li>

<li><a href="SERVICIOS_rutas_nodos.php" ><i class="uk-icon-share-alt echo $uikitIconSize_menu ?>"></i>&nbsp;Nodos</a></li>



<?php 
if(($rolLv==$Adminlvl || val_secc($id_Usu,"clientes")) && $codSuc>0){
?>
													
													
<li ><a  href="clientes.php"><i class="uk-icon-users <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Clientes</a></li>
														
														
<?php }?>

<li ><a  href="ventas_mensuales_gestion.php"><i class="uk-icon-reply-all <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Facturar Planes Mes</a></li>


</ul>
</li>


 

<?php }?>


<!-- MODULO ENVIO SMS, MENSAJES DE TEXTO A CELULARES COL -->
<?php 
if($enviar_sms==1){
?>
<li class=" ">

<a  href="PQR.php"><i  class="uk-icon-question-circle <?php echo $uikitIconSize_menu ?>"></i>PQR</a> 
</li>
<li class="uk-parent">

<a  href="#"><i  class="uk-icon-envelope-o <?php echo $uikitIconSize_menu ?>"></i>Enviar SMS</a> 
								 

<ul class="uk-nav-sub">    
<li><a href="envio_sms_basico.php" ><i class="uk-icon-mail-forward <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Env&iacute;o x Grupos</a></li>

 

<li><a href="sms_grupos.php" ><i class="uk-icon-users <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Grupos</a></li>
</ul>
</li>


 

<?php }?>


															
<?php if($MODULES["CARTERA"]==1){include("menu_izq_CARTERA.php");}?>

						<?php if($MODULES["ANTICIPOS"]==1){include("menu_izq_ANTICIPOS.php");}?>

						<?php if($MODULES["CARROS_RUTAS"]==1){include("menu_izq_CARROS_RUTAS.php");}?>

						<?php if($MODULES["CARGAR_CARROS"]==1){include("menu_izq_CARGAR_CARROS.php");} ?>



<?php if($MODULES["mesas_pedidos"]==1){?>
<!--  Ventas -->
<li class="uk-parent">

								 
<a  href="#"><i  class="uk-icon-cutlery <?php echo $uikitIconSize_menu ?>"></i> / &nbsp; <i  class="uk-icon-glass <?php echo $uikitIconSize_menu ?>"></i>Mesas</a> 
<ul class="uk-nav-sub"> 
<li><a href="MESAS.php" ><i class="uk-icon-sticky-note-o <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Pedidos</a></li>
<li><a href="COMIDAS_menu_favoritos.php" ><i class="uk-icon-bookmark-o <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Categor&iacute;as</a></li>
</ul>
</li>
<?php }?>



<!--  Ventas -->
<li class="uk-parent">

								 
<a  href="#"><i  class="uk-icon-dollar <?php echo $uikitIconSize_menu ?>"></i>Ventas</a> 
<ul class="uk-nav-sub">                                          
<?php if($MODULES["formatos_peluqueria"]==1){?>
<li><a href="lista_fichas_colorimetria.php" ><i class="uk-icon-newspaper-o <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Lista Fichas Colorimetría</a></li>
<li><a href="lista_fichas_manicura.php" ><i class="uk-icon-newspaper-o <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Lista Fichas Manicura</a></li>
										
<?php }?>




									
<?php 
if(($rolLv==$Adminlvl || val_secc($id_Usu,"fac_lista")) && $codSuc>0){
?>										
<li><a href="ventas.php" ><i class="uk-icon-money <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Lista Facturas</a></li>
											
											
<?php 
}
?>                                   

<?php 
if($MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1 && $rolLv==$Adminlvl){
if(1){
?>
<li ><a  href="ventas_mensuales_gestion.php"><i class="uk-icon-globe <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Facturas Planes Mes</a></li>
                                            
                                             <?php 
											}
											}
											?>
										
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"fac_crea")) && $codSuc>0){
											?>
										

												<li><a href="fac_venta.php" ><i class="uk-icon-plus-square <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Factura</a></li>

											
											<?php 
											}
											?>
                                            
                                            
                                            
                                            <?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"dev_ventas")) && $MODULES["DEVOLUCIONES"]==1){
											?>

											
											<li><a href="dev_ventas.php" ><i class="uk-icon-minus-circle <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Devoluciones</a></li>


											<?php 
											}
											?>
                                            
						 

										
											<?php 
											if($MODULES["REMISIONES"]==1 || $usar_remisiones==1){
												
											
													if(($rolLv==$Adminlvl || val_secc($id_Usu,"remi_lista")) && $codSuc>0){
													?>
										
											
	<?php if($usar_remisiones==1){?> <li><a href="remisiones2.php" ><i class="uk-icon-edit <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Remisiones</a></li><?php }?>
    <li><a href="remisiones.php" ><i class="uk-icon-wrench <?php echo $uikitIconSize_menu ?>"></i>&nbsp;<?php echo $LABEL_REMISION; ?></a></li>
											
			 
													<?php 
													}
													?>                                   
										

													<?php 
													if(($rolLv==$Adminlvl || val_secc($id_Usu,"remi_crea")) && $codSuc>0){
													?>

										
							<!--<li><a href="fac_remi.php" ><i class="uk-icon-plus-square <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Remisi&oacute;n</a></li>
										-->	
										
													<?php 
													}

											}// FIN ALLOW REMI
											?>
<?php 
													if($MODULES["COTIZACIONES"]==1){
													?>										
										
<li><a href="COTIZACIONES.php" ><i class="uk-icon-folder-open <?php echo $uikitIconSize_menu ?>"></i>&nbsp;COTIZACIONES</a></li>
	<?php 
													}
													?>											
										
												
									</ul>
												
							</li> <!-- Final de Ventas -->
												
												
						<?php 
						if($MODULES["SERVICIOS"]==1){
						?>                                 
												
							<li class="uk-parent"> <!--  Servicios Taller -->
								 
								<a  href="#"><i class="uk-icon-hand-grab-o <?php echo $uikitIconSize_menu ?>"></i>Servicios</a> 
								

									<ul class="uk-nav-sub">

										


											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"ver_ingre_vehi")) && $MODULES["VEHICULOS"]==1){
											?>
									
								            <li><a href="lista_vehiculos.php" ><i class="uk-icon-automobile <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Ingreso Veh&iacute;culos</a></li>   
									
											
											<?php 
											}
											?>                                   
										
										
										
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"adm_serv")) && $codSuc>0){
											?>
										
										
												<li><a href="servicios.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Servicios</a></li>   
											
											
											<?php 
											}
											?>
						 
						 
										    
									</ul>
											   
							</li> <!-- Final de Servicios Taller --><?php }?>
                            
                            
                            
                            
                            
                            
                            		<?php 
						if($MODULES["COD_GARANTIA"]==1){
						?>                                 
												
							<li class="uk-parent"> <!--  Garantias Productos -->
								 
								<a  href="#"><i class="uk-icon-legal <?php echo $uikitIconSize_menu ?>"></i>Garantias</a> 
								

									<ul class="uk-nav-sub">

										
										<?php if(($rolLv==$Adminlvl || val_secc($id_Usu,"crear_garantias"))){?>
										
												<li><a href="lista_orden_garantia.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Garant&iacute;as Productos</a></li>
												<?php }?>

										                                 
										
											
						 
						 
										    
									</ul>
											   
							</li> <!-- Final de Garantias Productos --><?php }?>


					
						<?php 
						if(($rolLv==$Adminlvl || val_secc($id_Usu,"compras") || val_secc($id_Usu,"envia_tras"))  && $MODULES["COMPRAS"]==1){
						?>
						
							<li class="uk-parent"> <!--  Compras -->
									
 <a href="#"><i class="uk-icon-folder-open-o <?php echo $uikitIconSize_menu ?>"></i>Compras <?php if($MODULES["TRASLADOS"]==1)echo "" ?></a> 
								
											
									<ul class="uk-nav-sub">
									
										
												
											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"compras_add"))){
											?>
											
												<li><a href="compras.php"><i class="uk-icon-truck <?php echo $uikitIconSize_menu ?>" ></i>&nbsp;Compras</a></li>

										
											<?php 
											}
											?>



											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"compras_add"))){
											?>

											
											<li><a href="devoluciones.php" ><i class="uk-icon-minus-circle <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Devoluciones</a></li>


											<?php 
											}
											?>



											<?php 
											if($MODULES["TRASLADOS"]==1){
											
													if(($rolLv==$Adminlvl || val_secc($id_Usu,"recibe_tras"))){
													?>

												
														<li><a href="traslados.php" ><i class="uk-icon-check-square-o <?php echo $uikitIconSize_menu ?>"></i>&nbsp; Traslados Entran</a></li>

												
													<?php 
													}
													?>


													<?php 
													if(($rolLv==$Adminlvl || val_secc($id_Usu,"envia_tras"))){
													?>

												
														<li><a href="traslados_salen.php" ><i class="uk-icon-reply <?php echo $uikitIconSize_menu ?>" ></i>&nbsp; Traslados Salen</a></li>


													<?php 
													}

											}// FIN ALLOW TRAS
											?>

										
									</ul>
												
							</li> <!-- Final de Compras -->
						 
						 
						<?php 
						}///////////////////////////////////////// CIERRA COMPRAS ////////////////////////
						?>



						<?php 
						if($MODULES["GASTOS"]==1){
								//|| val_secc($id_Usu,"crear_comp_egreso")
if(($rolLv==$Adminlvl ) || val_secc($id_Usu,"lista_comp_egreso")){
								?>
			 
									<li class="uk-parent"> <!--  Gastos -->
								
											
<a  href="#"><i class="uk-icon-bank <?php echo $uikitIconSize_menu ?>"></i>Gastos</a> 
										
											
											<ul class="uk-nav-sub">
												
												
												
												
<li><a href="lista_comp_egreso.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Listado Gastos</a></li>
<?php if(($rolLv==$Adminlvl ) || val_secc($id_Usu,"lista_comp_egreso")){ ?>

<li><a href="EGRESOS_gestion_conceptos.php" ><i class="uk-icon-object-group <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Gesti&oacute;n Conceptos</a></li>

<?php }?>
									  
									  
												
											
											</ul>
												
									</li> <!-- Final de Gastos -->
								
								<?php 
								}
					
						} // FIN ALLOW GASTOS
						?>




						<?php 
						if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario")) ){
						?>
						
							<li class="uk-parent"> <!--  Inventario -->
							
									
								<a href="#"><i class="uk-icon-database <?php echo $uikitIconSize_menu ?>"></i>Inventario</a> 
								
											
									<ul class="uk-nav-sub">
									
                                    			
                                                
                                                <?php if($MODULES["IMPORT_CSV"]==1){?>
                                                <li><a href="importar_lista.php" ><i class="uk-icon-upload <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Importar BD </a></li>
                                                <?php }?>
                                                
<li><a href="inventario_inicial.php" ><i class="uk-icon-list <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Inventario</a></li>
											
												<!--<li><a href="clases.php" class="uk-icon-list">&nbsp;Clasificaci&oacute;n inv.</a></li>-->         

											
											<?php 
											//&& $_SESSION['FLUJO_INVENTARIO']==1 && $vende_sin_cant==0
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario_ajustes")) && $codSuc>0 ){
											?>

										
												<li><a href="ajustes.php"  ><i class="uk-icon-undo <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Ajustes Inventario</a></li>

											
											<?php
											}
											?>



											<?php 
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario")) && $MODULES["ver_inv_sedes"]==1){
											?>

										
<li><a href="inventario_sedes.php" ><i class="uk-icon-share-alt <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Inventario SEDES</a></li>

											
											<?php
											}
											?>

<?php 
													if($rolLv==$Adminlvl  && ($MODULES["ROTACION_INV"]==1)){
													?>											
<li><a href="ROTACION_INV.php" ><i class="uk-icon-bar-chart <?php echo $uikitIconSize_menu ?>"></i>&nbsp;Rotaci&oacute;n Inv.</a></li>
												
<?php 
													}
													?>											
									</ul>
												
							</li> <!-- Final de Inventario -->
						 
						
						<?php 
						}
						?>

			
		</ul>
    </div>
</div>
