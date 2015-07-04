var preloads = new Object();
window.onload=precarga;

function precarga()
{
	if (document.getElementById)
		var x = document.getElementById('main_menu1').getElementsByTagName('IMG');
	else if (document.all)
		var x = document.all['main_menu1'].all.tags('IMG');
	else return;
	for (var i=0;i<x.length;i++)
	{
		preloads['n'+x[i].id] = new Image;
		preloads['n'+x[i].id].src = 'images/ico0_'+ x[i].id+'.gif'; 
		preloads['o'+x[i].id] = new Image;
		preloads['o'+x[i].id].src = 'images/ico1_'+ x[i].id+'.gif' ;
	//	x[i].onmouseover = function () {this.src=preloads['o'+this.id].src;}
	//	x[i].onmouseout = function () { this.src=preloads['n'+this.id].src;}
	}
}
function saltarclub(){
	f=document.form_grid;
	g=document.formulario;
	switch(g.titulo.selectedIndex){
		case 0:
			uno="32";
			break;
		case 1:
			uno="33";
			break;
		case 2:
			uno="34";
			break;
		case 3:
			uno="35";
			break;
	}
	grid_modificar(uno)
}
function over_icono(i) {
	document.getElementById(i).src=preloads['o'+i].src;
}
function out_icono(i) {
	document.getElementById(i).src=preloads['n'+i].src;
}