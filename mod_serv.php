<?php
require_once("Conexxx.php");
if ($rolLv != $Adminlvl && !val_secc($id_Usu, "inventario_add", $conex)) {
  header("location: centro.php");
}
$ID = r("ID_SERV");
?>
<!DOCTYPE html>
<html>

<head>
  <?php require_once("HEADER.php"); ?></head>

<body>


  <div class="container ">
    <!-- Push Wrapper -->
    <div class="mp-pusher" id="mp-pusher">
      <?php require_once("menu_izq.php"); ?>
      <?php require_once("menu_top.php"); ?>
      <?php require_once("boton_menu.php"); ?>
      <div class="uk-width-9-10 uk-container-center">
        <div class="grid-100 posicion_form">

          <h1 align="center">MODIFICAR SERVICIO</h1>
          <form action="agregar_producto.php" method="get" name="add" class="uk-form" id="add">
            <div id="crea_inv">
              <?php

              $sql = "SELECT * FROM servicios WHERE id_serv='$ID' AND cod_su='$codSuc'";
              $rs = $linkPDO->query($sql);
              if ($row = $rs->fetch()) {

                $SERV = $row["servicio"];
                $DES = $row["des_serv"];
                $KM_REV = $row["km_revisa"];
                $IVA = $row["iva"] * 1;
                $PVP = $row["pvp"] * 1;
                $COD = $row["cod_serv"];
                $clasificacion = $row['clasificacion'];

              ?>
                <table cellspacing="10" style="font-size:20px; color:aliceblue; width:30%" align="center" class="uk-table uk-block-secondary" aling="center">


                  <tr>
                    <td align="center"><label>CODIGO:</label></td>
                  </tr>
                  <tr>
                    <td align="center"><input id="cod_serv" name="cod_serv" type="text" placeholder="CODIGO SERVICIO" onChange="validar_c($(this),'servicios','cod_serv','Este CODIGO  Ya Existe!!!');" value="<?php echo "$COD"; ?>" />
                      <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este SERVICIO ya existe</div>
                    </td>
                  </tr>

                  <tr>
                  <td align="center"><label>Servicio:</label></td>
                  </tr>
                  <tr>
                    <td align="center"><input style="width: 300px;"  id="serv" name="serv" type="text" placeholder="NOMBRE SERVICIO" onChange="validar_c($(this),'servicios','servicio','Este SERVICIO  Ya Existe!!!');" value="<?php echo "$SERV"; ?>" />


                      <div id="resp" style="visibility:hidden; color: #F00; width:180px;"><img alt="" src="Imagenes/delete.png" width="20" height="20" />Este SERVICIO ya existe</div>
                    </td>
                  </tr>




                  <tr>
                    <td align="center"><label>Descripci&oacute;n:</label></td>
                  </tr>
                  <tr>
                    <td align="center"><textarea name="des_serv" id="des_serv" rows="4" placeholder="Descripci&oacute;n del producto"><?php echo "$DES"; ?></textarea></td>
                  </tr>

                  <tr>
                    <td align="center"><label>Clasificaci&oacute;n:</label></td>
                  </tr>
                  <tr>
                    <td align="center"><input value="<?php echo "$clasificacion"; ?>" name="clasificacion" id="clasificacion" type="text" placeholder="Clase para aplicar filtros" />
                    </td>
                  </tr>
                  <tr>
                    <td align="center"><label>Precio de Venta al P&uacute;blico:</label></td>
                  </tr>
                  <tr>
                    <td align="center"><input name="pvp" value="<?php echo money("$PVP"); ?>" type="text" placeholder="Precio de Venta" id="pvp" onBlur="nan($(this))" onKeyUp="puntoa($(this));" /></td>
                  </tr>
                  <tr>
                    <td align="center"><label>I.V.A:</label></td>
                  </tr>
                  <tr>
                    <td align="center">
                      <select name="iva" id="iva" onChange="">
                        <option selected value=""></option>
                        <option value="0" <?php if ($IVA == 0) echo "selected"; ?>>0%</option>
                        <option value="5" <?php if ($IVA == 5) echo "selected"; ?>>5%</option>
                        <option value="10" <?php if ($IVA == 10) echo "selected"; ?>>10%</option>
                        <option value="19" <?php if ($IVA == 19) echo "selected"; ?>>19%</option>
                      </select>
                    </td>
                  </tr>


                  <tr>
                    <td align="center">
                      <span onClick="subir($('#boton'),'Guardar',document.forms['add']);" class="uk-button uk-button-large"><i class=" uk-icon-floppy-o"></i>Guardar</span>
                    <span class="uk-button uk-button-large" onClick="location.assign('servicios.php');"><i class=" uk-icon-history"></i>Volver</span></td>

                  </tr>
                </table>
              <?php

              } // FIN IF 

              ?>
            </div>
            <input type="hidden" name="boton" id="boton" value="">
            <input type="hidden" name="ID" id="ID" value="<?php echo "$ID"; ?>">
            <input type="hidden" name="verify" id="verify" value="">
            <input type="hidden" name="verify2" id="verify2" value="">
            <input type="hidden" value="" name="htmlPag" id="HTML_Pag">
          </form>
          <?php require_once("js_global_vars.php"); ?>
          <?php require_once("FOOTER.php"); ?>

          <script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
          <script language='javascript' src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
          <script language="javascript1.5" type="text/javascript">
            $('input').on("change", function() {
              $(this).prop('value', this.value);
            });
            $('textarea').on("change", function() {
              $(this).html(this.value);
            });
            $(document).ready(function() {



              $.ajaxSetup({
                'beforeSend': function(xhr) {
                  try {
                    xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');
                  } catch (e) {}
                }
              });





            });

            function subir(btn, val, form) {
              //alert("!");
              var $form = $('#add');
              //alert('PROMEDIO  VAL:'+form.id_producto.value);
              //alert('Costo:'+form.cost.value+(form.cost.value<=0)+', Gan:'+form.gana.value+', PVP:'+form.pvp.value+'');
              getPage($('#HTML_Pag'),$('#crea_inv'));
              if (esVacio(form.serv.value)) {
                alert('Ingrese el SERVICIO');
                form.serv.focus();
              }
              //else if(form.verify.value=='ko'){alert('El Código NO es válido!!');form.id_producto.focus();}
              else if (esVacio(form.pvp.value) && form.pvp.value != '0') {
                alert('Ingrese el Precio de Venta');
                form.pvp.focus();
              } else if (esVacio(form.iva.value)) {
                alert('Escoga el IVA');
                form.iva.focus();
              } else {
                btn.prop('value', val);
                //form.submit();
                //alert("valida frm");
                var Datos = $form.serialize();
                //alert("datos:"+Datos);
                $.ajax({
                  type: 'POST',
                  url: 'ajax/mod_serv.php',
                  data: Datos,
                  success: function(resp) {
                    var r = resp;
                    //alert(resp);
                    if (!esVacio(r)) open_pop('ERROR', 'SALVE LA PANTALLA E INFORME EL ERROR AL ADMIN.', r);
                    else {

                      open_pop('Guardado', 'SERVICIO actualizado', '');
                      $("#modal").on({
                        'uk.modal.hide': function() {

                          //location.assign("mod_serv.php");
                        }
                      });
                    }

                  }
                });


              }
              //resetForm($form);

              //alert('!!');
            };
          </script>
</body>

</html>