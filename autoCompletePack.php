<link href="css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript1.5" src="JS/jquery.autocomplete.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5">
var $busqueda=$('#nom_cli');
var selOpt='ID';
function findValue(li)
{
  	if( li == null ) return simplePopUp("No match!");
  	/*if coming from an AJAX call, let's use the CityId as the value*/
  	if( !!li.extra ) var sValue = li.extra[0];

  	/* otherwise, let's just display the value in the text box*/
  	else var sValue = li.selectValue;

};
function selectItem(li){findValue(li);};
function formatItem(row){return row[0] + " (id: " + row[1] + ")";};
//function formatItem(row){return row[1] + " (id: " + row[0] + ")";};
function lookupAjax(){
var oSuggest = $busqueda[0].autocompleter;
oSuggest.findValue();
return false;
};
function lookupLocal(){
var oSuggest = $("#CityLocal")[0].autocompleter;
oSuggest.findValue();
return false;
};
function call_autocomplete(dataOPC,$busq,URL_ajax)
{
selOpt=dataOPC;	
$busqueda=$busq;	
$busq.autocomplete(
URL_ajax,
{
		  	
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem,
  			onFindValue:findValue,
  			formatItem:formatItem,
  			autoFill:true
}
    );
}

</script>