<title>Smart Selling 4.9.5</title>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1" charset="UTF-8"/>


<link rel="shortcut icon" href="Imagenes/favSmart.png" />
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/tooltip.gradient.min.css">
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/datepicker.min.css" />
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/progress.gradient.min.css">
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/progress.min.css">
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/sticky.min.css">
 
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/uikit.gradient.min.css?<?php  echo "?$LAST_VER"; ?>" />

<link rel="stylesheet" type="text/css" href="css/menu_ui_off.css?<?php  echo "$LAST_VER"; ?>" />

<link rel="stylesheet" type="text/css" href="css/general_ss.css?<?php  echo "$LAST_VER"; ?>" />
<link rel="stylesheet" type="text/css" href="css/pretty-checkbox.css" />



<style>

.tabla-datos{
    font-size: 18px;
}

html{
background: -moz-linear-gradient(left,  rgba(12,135,176,1) 0%, rgba(8,100,130,1) 100%);
background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(12,135,176,1)), color-stop(100%,rgba(8,100,130,1)));
background: -webkit-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: -o-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: -ms-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: linear-gradient(to right,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
}


.footer{
bottom: -1px;
 
    left: 0;
    right: 0;
    width: 100%;
    position: fixed;
    z-index: 1000;
}
body {
    font-family: 'Lato', Calibri, Arial, sans-serif;
   /* background: #ddd url(../images/bg.jpg) repeat top left;*/
    /*font-weight: 300;
    font-size: 15px;*/
    color: #333;
   /* -webkit-font-smoothing: antialiased;*/
background: rgb(12,135,176);
background: -moz-linear-gradient(left,  rgba(12,135,176,1) 0%, rgba(8,100,130,1) 100%);
background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(12,135,176,1)), color-stop(100%,rgba(8,100,130,1)));
background: -webkit-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: -o-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: -ms-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: linear-gradient(to right,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0c87b0', endColorstr='#086482',GradientType=1 );

<?php if($modoPruebas){
	echo "background: rgb(120,135,176);";}
?>
/*background-color:#FFF;*/
}
.top_menu{background-color:#000;
height:30px;
color:#FFF;
}


@-webkit-keyframes outline-animate {
	0% {
		outline-offset: .5rem
	}
	to {
		outline-offset: -.1rem
	}
}

@keyframes outline-animate {
	0% {
		outline-offset: .5rem
	}
	to {
		outline-offset: -.1rem
	}
}

input:focus {
    outline: .2rem solid #51cbee;
    box-shadow: inset 0 0 0.5rem #1b60db;
    animation: outline-animate .2s linear 0s 1 normal;
}
a:focus {
    outline: .1rem solid #51cbee;
    box-shadow: inset 0 0 0.5rem #1b60db;
    animation: outline-animate .2s linear 0s 1 normal;
}



/*******************  Estilos Facturacion ******************/


.valorProducto {
	width:110px;
}

#articulos_head td{
	border:1px #aaa solid;
}

#articulos input{
	text-rendering: auto;
	color: initial;
	letter-spacing: normal;
	word-spacing: normal;
	text-transform: none;
	text-indent: 0px;
	text-shadow: none;
	display: inline-block;
	text-align: start;
	margin: 0em 0em 0em 0em;
	font-size: 20px;
	height: 40px;
}

#articulos textarea{
	width:350px;
	height:60px;
	font-size:20px;
}

@media screen and (max-width:1269px){

#articulos textarea{
	width:350px;
	height:60px;
	font-size:20px;
}

}

@media screen and (min-width:1270px){

#articulos textarea{
	width:350px;
	height:60px;
	font-size:20px;
}

}
</style>