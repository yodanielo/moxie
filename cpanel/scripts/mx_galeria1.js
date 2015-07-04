
function cargar_galeria(campo,tabla,idc) {
    new Ajax.Updater(campo+"_temp","cls_MantixGaleria1.php?tabla="+ tabla+"&campo="+campo+"&idc="+ idc, { method: 'get', parameters: '', onComplete: function() { 
				var cad=$(campo+'_temp').innerHTML;
				var txt=cad.split('///');	
				var timgs=txt[0];
				var timgsid=txt[1];
				if(timgs!="") {
				$('hid_'+campo+'_cont').value=timgsid;
				$('himg_'+campo+'_cont').value=timgs;
				var imgs=timgs.split('/-/');
				var imgsid=timgsid.split(',');
				$(campo+'_conta').value=imgs.length-1;
				var ancho=(105*imgs.length) +"px";
				$(campo+'_cont').setStyle({width:ancho});
				mostrar_galeria(imgs,campo);
				}
				else {
				$(campo+'_conta').value=-1;
				}
				} } );
}

function mostrar_galeria(imgs,campo) {
	$(campo+'_cont').innerHTML="";
	var imagentxt="";
	for(var a=0;a<imgs.length;a++) {
	  imagentxt=imgs[a];
	  if(imagentxt.length>9) imagentxt=imagentxt.substr(0,8) + '...';
	  poner_galeria_i(campo,imgs[a],imagentxt,a,a,imgs.length-1); 
	}	
}

function poner_galeria(campo,imagen) {
var cad=$('hid_'+campo+'_cont').value;
if(cad!="") { var hid=cad.split(','); }
else { var hid=new Array(); }
hid.push("NULL");
$('hid_'+campo+'_cont').value=hid.join(',');

var cad=$('himg_'+campo+'_cont').value;
if(cad!="") {var himg=cad.split('/-/'); }
else { var himg=new Array(); }

himg.push(imagen);
$('himg_'+campo+'_cont').value=himg.join('/-/');

var long=hid.length;
var a=parseInt($(campo+'_conta').value)+1;
$(campo+'_conta').value=a;

var ancho=(105*long) +"px";
$(campo+'_cont').setStyle({width:ancho});
imagentxt=imagen;
if(imagentxt.length>9) imagentxt=imagentxt.substr(0,8) + '...';				  
mostrar_galeria(himg,campo);
}

function poner_galeria_i(campo,imagen,imagentxt,a,long,total){
var cad=$(campo+'_cont').innerHTML+'<div class="galeria1_img" id="'+campo+'_img_'+ a +'">';

cad=cad+'<div style="width:80px; float:left"><a href="images/galeria_'+campo+'/' + imagen + '" rel="lightbox"><img src="images/galeria_'+campo+'/th_'+imagen+'" border="0" width="80" height="80" /></a><br /><div align="center"><a href="javascript:eliminar_galeria(\''+campo+'\','+a+','+ long +')" class="galeria_txt">(X)'+ imagentxt+'</a></div></div>';

cad=cad+'</div>';

$(campo+'_cont').innerHTML=cad;
}


function eliminar_galeria(campo,a,pos) {
	if(confirm('¿ Está seguro que desea ELIMINAR la imagen ?')) {
	 new Effect.Fade(campo+'_img_'+pos,{ duration:0.6});
	 setTimeout("eliminar_galeria_i('"+campo+"'," + a + ","+pos+")",800);
	}
}

function eliminar_galeria_i(campo,a,pos) {
	 var cad=$('hid_'+campo+'_cont').value;
	 var hid=cad.split(',');
	 hid.splice(pos,1);
	 $('hid_'+campo+'_cont').value=hid.join(',');	
	 
	 var cad=$('himg_'+campo+'_cont').value;
	 var himg=cad.split('/-/');
	 himg.splice(pos,1);
	 $('himg_'+campo+'_cont').value=himg.join('/-/');
	 mostrar_galeria(himg,campo);
}

function posicion_galeria(campo,dir, pos) {
if(confirm('¿ Está seguro que desea MOVER la imagen ?')) {
  	 var cad=$('hid_'+campo+'_cont').value;
	 var hid=cad.split(',');
	 var p1=hid[pos+dir];
	 var p2=hid[pos];

 	 var cad=$('himg_'+campo+'_cont').value;
	 var himg=cad.split('/-/');
	 var pimg1=himg[pos+dir];
	 var pimg2=himg[pos];

	if(dir==-1) { 
		  hid.splice(pos-1,2); 
  		  hid.splice(pos-1,0,p2); 
		  hid.splice(pos,0,p1); 
		  himg.splice(pos-1,2); 
		  himg.splice(pos-1,0,pimg2); 
		  himg.splice(pos,0,pimg1); 
	 }
	 else {
		  hid.splice(pos,2);
		  hid.splice(pos,0,p1); 
		  hid.splice(pos+1,0,p2); 
		  himg.splice(pos,2); 
		  himg.splice(pos,0,pimg1); 
		  himg.splice(pos+1,0,pimg2); 
     }
	 $('himg_'+campo+'_cont').value=himg.join('/-/');
	 $('hid_'+campo+'_cont').value=hid.join(',');	
	 mostrar_galeria(himg,campo);
 }
}

function mover_galeria(campo,pos) {
  if($(campo)) {
     $(campo+"stop").value=0;	
	 if(pos==1) { bajando(campo); }
	 else { subiendo(campo);  }
  }
}

function subiendo(campo) {
var p=$(campo).getStyle('marginLeft');
var px=p.split("px");
var posini=parseInt(px[0]);
if((500-posini)<parseInt($(campo).scrollWidth) && $(campo+"stop").value==0) { 
  posini-=2;
  $(campo).setStyle({marginLeft:posini+"px"});
  setTimeout("subiendo('"+ campo +"')",1);
 }
}

function bajando(campo) {
var p=$(campo).getStyle('marginLeft');
var px=p.split("px");
var posini=parseInt(px[0]);
if( posini<0 && $(campo+"stop").value==0) { 
  posini+=2;
  $(campo).setStyle({marginLeft:posini+"px"});
  setTimeout("bajando('"+ campo +"')",1);
 }
}

function detener_galeria(campo){
 $(campo+"stop").value=1;	
}

