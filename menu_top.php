<?php 
//$FX_MENU_TOP=limpiarcampo($_REQUEST['ACTIVE_FLUX']);
$link_licencia='<a href="#lic_key" data-uk-modal><i class="uk-icon-question uk-icon-medium"></i></a>';
//$link_licencia='<a href="FIX_INV2.php" target="_blank"><i class="uk-icon-question uk-icon-medium"></i></a><a href="FIX_INV3.php" target="_blank">.</a>';
$hostName=$_SERVER['HTTP_HOST'];

$showPartner="";
if($hostName=="testserver.nanimosoft.com"){$showPartner=1;}

if(isset($_REQUEST['suc'])&& !empty($_REQUEST['suc']) && $_REQUEST['suc']!=$codSuc)
{
//eco_alert('Entra, '.$_REQUEST['suc']);
//eco_alert('Entra, '.$_REQUEST['suc'].'-replica');
$_SESSION['cod_su']= limpiarcampo($_REQUEST['suc']);
//$codSuc=$_SESSION['cod_su'];


$qry="SELECT sucursal.*,departamento.departamento,municipio.municipio FROM sucursal,departamento,municipio WHERE sucursal.cod_su=".$_REQUEST['suc']." and  sucursal.id_dep=departamento.id_dep AND sucursal.id_mun=municipio.id_mun";

$qry="SELECT  sucursal.*,
             (SELECT departamento FROM departamento WHERE departamento.id_dep=sucursal.id_dep) AS departamento,
			 (SELECT municipio FROM municipio WHERE municipio.id_mun=sucursal.id_mun)          AS municipio,
			 usuarios.nombre,
			 usuarios.firma_op,usuarios.tel,usuarios.cod_caja as cc,
			 usuarios.fecha_crea 
	  FROM usuarios,sucursal
      WHERE sucursal.cod_su='".$_REQUEST['suc']."' LIMIT 1";

$qry="SELECT  sucursal.*,
(SELECT departamento FROM departamento WHERE departamento.id_dep=sucursal.id_dep) AS departamento,
(SELECT municipio FROM municipio WHERE municipio.id_mun=sucursal.id_mun)          AS municipio,
usuarios.nombre,
usuarios.firma_op,usuarios.tel,usuarios.cod_caja as cc,
usuarios.fecha_crea 
FROM usuarios,sucursal
WHERE usuarios.id_usu='".$_SESSION['id_usu']."' AND sucursal.cod_su='".$_REQUEST['suc']."' LIMIT 1";
	  //echo "<br><br><br><br><br><br><br><br><br><br><br><br><br>$qry";
//eco_alert ("$qry");

$rs_top_panel=$linkPDO->query($qry);
if($row=$rs_top_panel->fetch()){

	include('variablesSistema.php');


}
js_reload();

}


?>



<!-- This is the modal -->
<div id="lic_key" class="uk-modal" aria-hidden="true" style="z-index:3;">
	<div class="uk-modal-dialog">
		<a href="" class="uk-modal-close uk-close"></a>
        <a href="depuraKardex.php" target="_blank" class="uk-button">Ajusta Kardex</a>
        <a href="FIX_INV3.php" target="_blank" class="uk-button">Valida kardex</a>
		<h1 class="uk-text-primary uk-text-bold">Smart Selling Versi&oacute;n <?php echo "4.9.1" ?></h1>
		<p align="center" class="uk-text-large uk-text-bold">Smart Selling Es un Software Registrado en la DIRECCION NACIONAL DE DERECHO DE AUTOR
			<br />
			<b>Libro - Tomo - Partida :13-41-292</b>
			<br />
			<br />
			<span class="uk-text-success">Licencia <?php echo "".licenceKey() ?></span>
            
            <br />
            <span class="uk-text-success">Token Dian: </span> 
			<div class="uk-overflow-container">
            <?php 
			echo "Usuario:$usuarioDian NIT: $nit_reg_dian<BR>";
			echo $tokenDianOperaciones; 
			echo "<br> $PUBLICIDAD <br> Municipo: $munSuc";
			?>
            </div>
			

		</p>
	</div>
</div>

<!-- Top Navigation -->
<div class="top_menu  uk-block uk-block-primary uk-padding-remove" <?php if($_SESSION['FLUJO_INVENTARIO']==-1){echo " style=\"background-color:rgb(255, 0, 0);background-position-x:0%;\""; }?>>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="formulario_session" method="post">
		<?php
	if(isset($_SESSION['tipo_usu']))
	{
		if($rolLv<$Adminlvl && $rolUsu!="Jefe Taller" && $rolUsu!="Jefe Inventarios" )
		{
			?>

			
			<!-- small -->
			
			<div class="uk-button-dropdown uk-hidden-large uk-vertical-align" data-uk-dropdown>

							<!-- This is the element toggling the dropdown -->
							<div><i class="uk-icon-server uk-vertical-align-middle" style="font-size:24px;"></i></div>

							<!-- This is the dropdown -->
							<div class="uk-dropdown" style="background:rgba(40,40,40,1);color:white;width:200px;z-index:2;">
															
<div class="session" style="color:#FFF; font-size:14px;">
										<?php echo $nomUsu ?> <br>
										<?php echo $_SESSION['tipo_usu'] ?> <br>
										<a href="mod_cuenta.php" >Cuenta</a> <br>
										<a href="centro.php" >CAJA (<?php echo "$codCaja" ?>)</a> <br>
										<a href="index.php?boton=Cerrar" target="_parent" >Cerrar sesion</a> <br>
										<?php echo $link_licencia; ?>
										
	

								</div>
										
										
							</div>

						</div>
			
		
			
		<!-- large -->
			
			<div class="session" style="color:#FFF; font-size:14px;">
				&nbsp;<?php echo $nomUsu ?>&nbsp;&nbsp;&nbsp;
				|&nbsp;&nbsp;<?php echo $_SESSION['tipo_usu'] ?>&nbsp;&nbsp;
				|&nbsp;<a href="mod_cuenta.php" >Cuenta</a>&nbsp;&nbsp;
				|&nbsp;<a href="centro.php" >CAJA (<?php echo "$codCaja" ?>)</a>&nbsp;&nbsp;
				|&nbsp;&nbsp;<a href="index.php?boton=Cerrar" target="_parent" >Cerrar sesion</a>
                 <?php echo $link_licencia; ?>
				
					<div style="display:inline-block;">
							<?php if(val_secc($id_Usu,"ver_sedes"))
							{?>
								<div class="uk-form-icon">
										<i class="uk-icon-globe"></i>
									<select name="suc" onChange="document.forms['formulario_session'].submit()" style="  <?php if($MODULES["FLUJO_KARDEX"]==1){/*echo "width:12%;";*/}else {echo "font-size:12px; font-weight:bold; background-color:#F80;";} ?>">
										<option value=""></option>
										<?php
										$sql_top_panel="SELECT * FROM sucursal";
										$rs_top_panel=$linkPDO->query($sql_top_panel);
										while($row=$rs_top_panel->fetch())
										{
											$NombreSuc=$row['nombre_su'];
											$codigoSu=$row['cod_su'];
											?>
										<option  value="<?php echo "$codigoSu" ?>" <?php if($codigoSu==$codSuc) echo "selected" ?>><?php echo "$NombreSuc" ?> </option>
											<?php
											
										}
										?>
									</select>
								</div>
								<?php 
							}
							if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario_on_off")) && $codSuc>0 &&$MODULES["FLUJO_KARDEX"]==1)
							{
								?>
									<select name="ACTIVE_FLUX" onChange="document.forms['formulario_session'].submit()" style="width:12%;<?php if($_SESSION['FLUJO_INVENTARIO']==-1)echo " font-weight:bold; font-size:16px;background-color:rgb(255, 0, 0);background-position-x:0%;" ?>">
										<option value="1" <?php if($_SESSION['FLUJO_INVENTARIO']==1)echo "selected"?>>Inventario-ON</option>
										<option value="-1" <?php if($_SESSION['FLUJO_INVENTARIO']==-1)echo "selected"?>>Inventario-OFF</option>
									</select>
								<?php 

							}
							?>
					</div>
			
			</div>
			<?php 
		}
		else if($rolLv>=$Adminlvl  || val_secc($id_Usu,"ver_sedes") )
		{
					?>
				
				
				
				
				
				 
				
				<div class="session" align="center" style="color:#FFF; font-size:14px;">
				<?php if($showPartner==1){?>
				 <img src="Imagenes/logoNani.png" width="120"  class="uk-hidden-touch"/> 
                  <img src="Imagenes/logofinalSGB.png" width="95" class="uk-hidden-touch" /> 
                  <?php }?>
				
				<!-- small ACA -->
						<div class="uk-button-dropdown uk-hidden-large " data-uk-dropdown>

							<!-- This is the element toggling the dropdown -->
							<div><i class="uk-icon-server uk-vertical-align-middle" style="font-size:24px;margin-right:10px;"></i></div>

							<!-- This is the dropdown -->
							<div class="uk-dropdown" style="background:rgba(40,40,40,1);color:white;width:200px;z-index:2;">
										<?php  //echo"Codigo SUcursal: $codSuc, input:".$_SESSION['cod_su'] ?>
									<!--	<?php echo $nomUsu ?> <br>
										<?php echo $_SESSION['tipo_usu'] ?> <br> -->
										<a href="mod_cuenta.php" >Cuenta</a> <br>
										<a href="centro.php" >CAJA (<?php echo "$codCaja" ?>)</a> <br>
										<a href="index.php?boton=Cerrar" target="_parent" >Cerrar sesion</a> <br>
										 
											
											<?php echo $link_licencia; ?>
												
											<?php if($rolLv>=$Adminlvl || val_secc($id_Usu,"ver_sedes"))
											{?>
												<!-- <div class="uk-form-icon">
														<i class="uk-icon-globe"></i>
														
														<select name="suc" onChange="document.forms['formulario_session'].submit()" style="  <?php if($MODULES["FLUJO_KARDEX"]==1){/*echo "width:12%;";*/}else {echo "font-size:18px; font-weight:bold; background-color:#F80;";} ?>">
															<option value=""></option>
															<?php
															$sql_top_panel="SELECT * FROM sucursal";
															$rs_top_panel=$linkPDO->query($sql_top_panel);
															while($row=$rs_top_panel->fetch())
															{
																$NombreSuc=$row['nombre_su'];
																$codigoSu=$row['cod_su'];
																?>
																<option  value="<?php echo "$codigoSu" ?>" <?php if($codigoSu==$codSuc) echo "selected" ?>><?php echo "&nbsp;&nbsp; &nbsp;&nbsp;$NombreSuc" ?> </option>
																<?php
																
															}
															?>
														</select>
												</div> -->
												<?php 
											}
											if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario_on_off")) && $codSuc>0 &&$MODULES["FLUJO_KARDEX"]==1)
											{
												?>
												<!--	<select name="ACTIVE_FLUX" onChange="document.forms['formulario_session'].submit()" style="width:12%;<?php if($_SESSION['FLUJO_INVENTARIO']==-1)echo " font-weight:bold; font-size:16px;background-color:rgb(255, 0, 0);background-position-x:0%;" ?>">
														<option value="1" <?php if($_SESSION['FLUJO_INVENTARIO']==1)echo "selected"?>>Inventario-On</option>
														<option value="-1" <?php if($_SESSION['FLUJO_INVENTARIO']==-1)echo "selected"?>>Inventario-Off</option>
													</select> -->
												<?php 

											}
											?>
											<?php //echo "fi: $FX_MENU_TOP, FI:". $_SESSION['FLUJO_INVENTARIO']; ?>

							</div>

						</div>
				
				<!-- large  PRINCIPAL -->
				<div class="uk-visible-large" style="display:inline-block">
					<?php  //echo"Codigo SUcursal: $codSuc, input:".$_SESSION['cod_su'] ?>
					 <?php echo $nomUsu ?>&nbsp;&nbsp;&nbsp;
					|&nbsp;&nbsp;<?php echo $_SESSION['tipo_usu'] ?>&nbsp;&nbsp;
					|&nbsp;<a href="mod_cuenta.php" >Cuenta</a>&nbsp;&nbsp;
					|&nbsp;<a href="centro.php" >CAJA (<?php echo "$codCaja" ?>)</a>&nbsp;&nbsp;
					|&nbsp;&nbsp;<a href="index.php?boton=Cerrar" target="_parent" >Cerrar sesion</a>|<b> </b>
						
						<?php echo $link_licencia; ?>| 
					
				</div>
				
				<div style="display:inline-block;">
						<?php if($rolLv>=$Adminlvl || val_secc($id_Usu,"ver_sedes"))
						{?>
							<div class="uk-form-icon">
									<i class="uk-icon-globe"></i>
									
									<select name="suc" onChange="document.forms['formulario_session'].submit()" style="  <?php if($MODULES["FLUJO_KARDEX"]==1){/*echo "width:12%;";*/}else {echo "font-size:18px; font-weight:bold; background-color:#F80;";} ?>">
										<option value=""></option>
										<?php
										$sql_top_panel="SELECT * FROM sucursal";
										$rs_top_panel=$linkPDO->query($sql_top_panel);
										while($row=$rs_top_panel->fetch())
										{
											$NombreSuc=$row['nombre_su'];
											$codigoSu=$row['cod_su'];
											?>
											<option  value="<?php echo "$codigoSu" ?>" <?php if($codigoSu==$codSuc) echo "selected" ?>><?php echo "&nbsp;&nbsp; &nbsp;&nbsp;$NombreSuc" ?> </option>
											<?php
											
										}
										?>
									</select>
							</div>
							<?php 
						}
						if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario_on_off")) && $codSuc>0 &&$MODULES["FLUJO_KARDEX"]==1)
						{
							?>
								<select name="ACTIVE_FLUX" onChange="document.forms['formulario_session'].submit()" style="<?php if($_SESSION['FLUJO_INVENTARIO']==-1)echo " font-weight:bold; font-size:16px;background-color:rgb(255, 0, 0);background-position-x:0%;" ?>">
									<option value="1" <?php if($_SESSION['FLUJO_INVENTARIO']==1)echo "selected"?>>Inventario-On</option>
									<option value="-1" <?php if($_SESSION['FLUJO_INVENTARIO']==-1)echo "selected"?>>Inventario-Off</option>
								</select>
							<?php 

						}
						?>
						<?php //echo "fi: $FX_MENU_TOP, FI:". $_SESSION['FLUJO_INVENTARIO']; ?>


				</div>
				
				</div>

					
					<?php
					
		}


	}
	else
	{
		?>
			<div align="center" style="color:#fff; position:fixed; top:0px">Sesi&oacute;n CERRADA <a href="index.php?boton=Cerrar" target="_parent" >Volver</a></div>
		<?php

	}

		?>

	</form>





</div>