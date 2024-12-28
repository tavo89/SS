<?php 
session_start();
?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Documento sin título</title>
</head>

<body>
<div data-role="page" id="dialog1" data-theme="c">
    <div data-role="header">
        <h1>
            Borrar
        </h1>
    </div><!-- /header -->
    <div data-role="content">
        <p>
           Desea borrar <?php echo $_REQUEST['des'] ?>?
        </p>
  <div class="ui-grid-a">
	<div class="ui-block-a"><a href="inventario_total.php?opc=del_aff&valor=<?php echo $_REQUEST['id'] ?>&pag=<?php echo $_REQUEST['pag'] ?>" data-role="button" data-icon="check">Aceptar</a></div>
	<div class="ui-block-b"><a href="inventario_total.php?opc=del_neg&pag=<?php echo $_REQUEST['pag'] ?>" data-role="button" data-icon="back">Volver</a></div>	   
</div>
       
    </div><!-- /content -->
    
</div>

</body>
</html>