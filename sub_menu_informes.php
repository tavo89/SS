<!-- Lado izquierdo del Nav -->
<nav class="uk-navbar">

<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 



	<!-- Lado derecho del Navbar -->
	<ul class="uk-navbar-nav uk-navbar-flip">
										
		<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center',mode:'click'}">
			
			<a href="#" ><i class="uk-icon-list"></i>&nbsp;Informes</a>

			<div class="uk-dropdown uk-dropdown-navbar">
			
				<ul class="uk-nav uk-nav-navbar">
					 <?php 
					if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_ventas_cli")) && $codSuc>0){
					?>
					<li><a href="INFORMES_2.php" >Ventas</a>
					</li>
					<?php 
					}
					?>                                  
					<?php 
					if($rolLv==$Adminlvl && $codSuc>0){?>
															<!--<li><a href="informes_administrativos.php" >&nbsp;Compras y Proveedores</a></li>-->
						<li><a href="informes_administrativos_rango.php" >&nbsp;Compras y Proveedores</a></li>
					 <?php }?>
															
					<?php  if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_listados")) && $codSuc>0){?>

					<li> <a href="INFORMES.php" >&nbsp;Inventarios</a> </li>
					<?php }?>  
															
														  
					<?php 
					if(($rolLv==$Adminlvl || val_secc($id_Usu,"informes_kardex")) && $codSuc>0){
					?>
					<li><a href="panel_kardex.php" >&nbsp;Kardex de Inventario</a>
					</li>
					<?php 
					}
					?>
				</ul>
					
			</div>

		</li>
		
	</ul>

</nav>
