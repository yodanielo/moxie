function runSWF(archivo, ancho, alto, version, bgcolor, FlashVars, id, menu, quality, allowScriptAccess) { 

if(version!=""){
	var version_data=version;
}else{
	var version_data="8,0,0,0";
}
if(menu!=""){
	menu_data=menu;
}else{
	menu_data=false;
}
if(bgcolor!=""){
	var bgcolor_data=bgcolor;
}else{
	var bgcolor_data="#FFFFFF";
}
if(id!=""){
	id_data=id;
}else{
	id_data="flashMovie";
}
if(quality!=""){
	quality_data=quality;
}else{
	quality_data="high";
}
if(allowScriptAccess!=""){
	allowScriptAccess_data=allowScriptAccess;
}else{
	allowScriptAccess_data="always";
}
var quality="high"; // calidad de visualizaci�n de la peli
 document.write('<object id="myFlash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version='+version_data+'" width='+ancho+' height='+alto+' id='+id_data+'>\n');
 document.write('<param name="movie" value='+archivo+'>\n');
 document.write('<param name="allowScriptAccess" value='+allowScriptAccess_data+'>\n');
 document.write('<param name="quality" value='+quality_data+'>\n');
 document.write('<param name="FlashVars" value='+FlashVars+'>\n');
 document.write('<param name="bgcolor" value='+bgcolor_data+'>\n');
 document.write('<param name="menu" value='+menu_data+' >\n');
 document.write('<embed src='+archivo+' bgcolor='+bgcolor_data+' FlashVars='+FlashVars+' menu='+menu_data+' allowScriptAccess='+allowScriptAccess_data+' quality='+quality_data+' pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width='+ancho+' height='+alto+' swLiveConnect=true name='+id_data+'></embed>');
 document.write('</object>\n');
}


function runSWF2(archivo, ancho, alto, version, wmode, FlashVars, bgcolor, id, menu, quality, allowScriptAccess) { 

if(version!=""){
	var version_data=version;
}else{
	var version_data="8,0,0,0";
}
if(menu!=""){
	menu_data=menu;
}else{
	menu_data=false;
}
if(bgcolor!=""){
	var bgcolor_data=bgcolor;
}else{
	var bgcolor_data="#000000";
}
if(wmode!=""){
	var wmode_data=wmode;
}else{
	var wmode_data="transparent";
}
if(id!=""){
	id_data=id;
}else{
	id_data="flashMovie";
}
if(quality!=""){
	quality_data=quality;
}else{
	quality_data="high";
}
if(allowScriptAccess!=""){
	allowScriptAccess_data=allowScriptAccess;
}else{
	allowScriptAccess_data="always";
}
var quality="high"; // calidad de visualizaci�n de la peli
 document.write('<object id="myFlash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version='+version_data+'" width='+ancho+' height='+alto+' id='+id_data+'>\n');
 document.write('<param name="movie" value='+archivo+'>\n');
 document.write('<param name="allowScriptAccess" value='+allowScriptAccess_data+'>\n');
 document.write('<param name="quality" value='+quality_data+'>\n');
 document.write('<param name="nomuser" value='+FlashVars+'>\n');
 document.write('<param name="bgcolor" value='+bgcolor_data+'>\n');
 document.write('<param name="menu" value='+menu_data+' >\n');
 document.write('<param name="wmode" value='+wmode_data+'>\n');
 document.write('<embed src='+archivo+' bgcolor='+bgcolor_data+' wmode='+wmode_data+' FlashVars='+FlashVars+' menu='+menu_data+' allowScriptAccess='+allowScriptAccess_data+' quality='+quality_data+' pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width='+ancho+' height='+alto+' swLiveConnect=true name='+id_data+'></embed>');
 document.write('</object>\n');
}
