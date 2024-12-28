var busqueda='#nom_cli';
function findValue(li)
{
  	if( li == null ) return alert("No match!");

  	// if coming from an AJAX call, let's use the CityId as the value
  	if( !!li.extra ) var sValue = li.extra[0];

  	// otherwise, let's just display the value in the text box
  	else var sValue = li.selectValue;

  	//alert("The value you selected was: " + sValue);
};
function selectItem(li){findValue(li);};
function formatItem(row){return row[0] + " (id: " + row[1] + ")";};
function lookupAjax(){
var oSuggest = $(busqueda)[0].autocompleter;
oSuggest.findValue();
return false;
};
function lookupLocal(){
var oSuggest = $("#CityLocal")[0].autocompleter;
oSuggest.findValue();
return false;
};

$("#cli").autocomplete(
"ajax/busq_cli.php",
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