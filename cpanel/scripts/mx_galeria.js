// JavaScript Document
var campo_activo="";
var tabla_activa="";
var idc_activo="";
var img_activa="";
function cargar_galeria(campo,tabla,idc) {
	campo_activo=campo;
	tabla_activa=tabla;
	idc_activo=idc;
	new Effect.Fade(campo,{ duration:0.6});
	setTimeout("new Ajax.Updater('" + campo + "','cls_MantixGaleria1.php?tabla="+ tabla+"&campo="+campo+"&idc="+ idc +"', {method: 'get', parameters: '', onComplete: mostrar });",800);
}
function eliminar_galeria(campo,tabla,idc) {
	if(confirm('¿ Está seguro que desea ELIMINAR la imagen ?')) {
	  campo_activo=campo;
	  new Effect.Fade(campo,{ duration:0.6});
	  setTimeout("new Ajax.Updater('" + campo + "','cls_MantixGaleria2.php?tabla="+ tabla+ "&idc="+idc+"', {method: 'get', parameters: '', onComplete: mostrar_galeria });",800);
	}
}

function posicion_galeria(campo,tabla,id, pos, imagen) {
if(confirm('¿ Está seguro que desea MOVER la imagen ?')) {
		  campo_activo=campo;
	new Effect.Fade(campo,{ duration:0.6});
	setTimeout("new Ajax.Updater('" + campo + "','cls_MantixGaleria3.php?tabla="+ tabla+"&campo="+campo+"&id="+ id +"&pos="+pos+"&imagen="+ imagen+"', {method: 'get', parameters: '', onComplete: mostrar_galeria });",800);
}
 }

function mover_galeria(campo,pos) {
 campo_activo=campo;
 if(pos==1) { bajar_listado(); }
 else { subir_listado();  }
}

function mostrar() {
  new Effect.Appear(campo_activo,{ duration:0.6});
}

function mostrar_galeria() {
  cargar_galeria(campo_activo,tabla_activa, idc_activo);
}

var detener_sube=0;
var detener_baja=0;
var posini=0;

function subiendo() {
if(detener_sube) { 
  posini-=1;
  document.getElementById(campo_activo).style.marginLeft=posini+"px";
  if((500-posini)>=document.getElementById(campo_activo).scrollWidth) detener_sube=0;
  setTimeout("subiendo()",2);
 }
}
function bajando() {
if(detener_baja && posini<0 ) { 
  posini+=1;
  document.getElementById(campo_activo).style.marginLeft=posini+"px";
  setTimeout("bajando()",2);
 }
}
function subir_listado() {
detener_sube=1;
detener_baja=0;
var p=document.getElementById(campo_activo).style.marginLeft;
var px=p.split("px");
posini=(px[0]);
//alert(posini);
subiendo();
}
function bajar_listado() {
detener_sube=0;
detener_baja=1;
var p=document.getElementById(campo_activo).style.marginLeft;
var px=p.split("px");
posini=parseInt(px[0]);

bajando();
}
function detener_subiendo() {
 detener_sube=0;
}
function detener_bajando() {
 detener_baja=0;
}