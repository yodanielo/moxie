

// ==============================================================
// HANDLES SCROLLER/S
// Modified from Aaron Boodman http://webapp.youngpup.net/?request=/components/ypSimpleScroll.xml
// mixed ypSimpleScroll with dom-drag script and allowed multiple scrolelrs through array instances
// (c)2004 Sergi Meseguer (http://zigotica.com/), 04/2004:
// ==============================================================
var theHandle = []; var theRoot = []; var theThumb = []; var theScroll = []; var thumbTravel = []; var ratio = [];

function instantiateScroller(count, id, left, top, width, height, speed){
	if(document.getElementById) {
		theScroll[count] = new ypSimpleScroll(id, left, top, width, height, speed);
	}
}

function createDragger(count, handler, root, thumb, minX, maxX, minY, maxY){
		var buttons = '<div class="up" id="up'+count+'"></div><div class="dn"  id="dn'+count+'""></div><div class="thumb" id="'+thumb+'" style="left: 135px; top: 15px;"></div>';
		//alert(document.getElementById("scroll" + count + "Content").offsetHeight + "\n" + maxY);
        if(document.getElementById("scroll" + count + "Content").offsetHeight>195) {
	    document.getElementById(root).innerHTML = buttons + document.getElementById(root).innerHTML; 
		
		theRoot[count]   = document.getElementById(root);
		theThumb[count]  = document.getElementById(thumb);
		var thisup = document.getElementById("up"+count);
		var thisdn = document.getElementById("dn"+count);
		theThumb[count].style.left = parseInt(minX+10) + "px";
		thisup.style.left = parseInt(minX+10) + "px";
		thisdn.style.left = parseInt(minX+10) + "px";
		theThumb[count].style.border =0;
		theThumb[count].style.top = parseInt(minY) + "px";
		thisup.style.top = 0 + "px";
		thisdn.style.top = parseInt(minY+maxY) + "px";
		//thisdn.style.top = 15 + "px";

		theScroll[count].load();

		//Drag.init(theHandle[count], theRoot[count]); //not draggable on screen
		Drag.init(theThumb[count], null, minX+10, maxX+10, minY, maxY-75);
		
		// the number of pixels the thumb can travel vertically (max - min)
		thumbTravel[count] = theThumb[count].maxY - theThumb[count].minY;

		// the ratio between scroller movement and thumbMovement
		ratio[count] = theScroll[count].scrollH / thumbTravel[count];
		
		theThumb[count].onDrag = function(x, y) {
			theScroll[count].jumpTo(null, Math.round((y - theThumb[count].minY) * ratio[count]));
		}
		}
}	

// INITIALIZER:
// ==============================================================
// ala Simon Willison http://simon.incutio.com/archive/2004/05/26/addLoadEvent
function addLoadEvent(fn) {
      var old = window.onload;
      if (typeof window.onload != 'function') {
         window.onload = fn;
      }
      else {
         window.onload = function() {
         old();
         fn();
         }
      }
   }
addLoadEvent(function(){
		if(theScroll.length>0) {
		for(var i=0;i<theScroll.length;i++){
			createDragger(i, "handle"+i, "root"+i, "thumb"+i, theScroll[i].clipW, theScroll[i].clipW, 0, theScroll[i].clipH-45);
		}
	}
}) 