/**
 * jQuery Lightbox
 * Version 0.2 - 07/31/2007
 * @author Warren Krewenki
 *
 * Based on Lightbox 2 by Lokesh Dhakar (http://www.huddletogether.com/projects/lightbox2/)
 * Originally written to make use of the Prototype framework, and Script.acalo.us, now altered to use jQuery.
 *
 * New in this version:
 * - fully encapsulated in one JSON object
 * - new method of adding the lightbox html
 * - removal of some dead functions
 *
 * To Do:
 * - Refactor setHeight, setWidth etc. to use internal jQuery methods
 * - Add in logic for automatically resizing the image, based on window size
 * - Add support for different content types to be displayed.
 **/

var Lightbox = {
	fileLoadingImage : "images/loading.gif",
	fileBottomNavCloseImage : 'images/closelabel.gif',
	overlayOpacity : 0.8,
	borderSize : 10,
	imageArray : new Array,
	activeImage : null,
	
	initialize: function() {	
		// attach lightbox to any links with rel 'lightbox'
		$("a").each(function(){
			if(this.rel.toLowerCase().match('lightbox')){
				$(this).click(function(){
					Lightbox.start(this);
					return false;
				});
			}
		});

		$("body").append('<div id="overlay"></div><div id="lightbox"><div id="outerImageContainer"><div id="imageContainer"><img id="lightboxImage"><div style="" id="hoverNav"><a href="#" id="prevLink"></a><a href="#" id="nextLink"></a></div><div id="loading"><a href="#" id="loadingLink"><img src="images/loading.gif"></a></div></div></div><div id="imageDataContainer"><div id="imageData"><div id="imageDetails"><span id="caption"></span><span id="numberDisplay"></span></div><div id="bottomNav"><a href="#" id="bottomNavClose"><img src="images/close.gif"></a></div></div></div></div>');
		$("#overlay").click(function(){ Lightbox.end(); }).hide();
		$("#lightbox").click(function(){ Lightbox.end();}).hide();
		$("#loadingLink").click(function(){ Lightbox.end(); return false;});
		$("#bottomNavClose").click(function(){ Lightbox.end(); return false; });
		Lightbox.setWidth('outerImageContainer', 250);
		Lightbox.setHeight('outerImageContainer', 250);
	},
	
	//
	//	start()
	//	Display overlay and lightbox. If image is part of a set, add siblings to Lightbox.imageArray.
	//
	start: function(imageLink) {	
		$("select, embed, object").hide();
		
		// stretch overlay to fill page and fade in
		var arrayPageSize = Lightbox.getPageSize();
		Lightbox.setWidth('overlay', arrayPageSize[0]);
		Lightbox.setHeight('overlay', arrayPageSize[1]);
		$("#overlay").hide().css({opacity : Lightbox.overlayOpacity}).fadeIn();

		Lightbox.imageArray = [];
		imageNum = 0;		

		var anchors = document.getElementsByTagName( imageLink.tagName);
	
		// if image is NOT part of a set..
		if((imageLink.rel == 'lightbox')){
			// add single image to Lightbox.imageArray
			Lightbox.imageArray.push(new Array(imageLink.href, imageLink.title));			
		} else {
		// if image is part of a set..

			// loop through anchors, find other images in set, and add them to Lightbox.imageArray
			for (var i=0; i<anchors.length; i++){
				var anchor = anchors[i];
				if (anchor.href && (anchor.rel == imageLink.rel)){
					Lightbox.imageArray.push(new Array(anchor.href, anchor.title));
				}
			}

			for(i = 0; i < Lightbox.imageArray.length; i++){
		        for(j = Lightbox.imageArray.length-1; j>i; j--){        
		            if(Lightbox.imageArray[i][0] == Lightbox.imageArray[j][0]){
		                Lightbox.imageArray.splice(j,1);
		            }
		        }
		    }
			while(Lightbox.imageArray[imageNum][0] != imageLink.href) { imageNum++;}
		}

		// calculate top and left offset for the lightbox 
		var arrayPageScroll = Lightbox.getPageScroll();
		var lightboxTop = arrayPageScroll[1] + (arrayPageSize[3] / 10);
		var lightboxLeft = arrayPageScroll[0];
		Lightbox.setTop('lightbox',lightboxTop);
		Lightbox.setLeft('lightbox',lightboxLeft);
		
		$("#lightbox").show();
		
		this.changeImage(imageNum);
	},

	//
	//	changeImage()
	//	Hide most elements and preload image in preparation for resizing image container.
	//
	changeImage: function(imageNum) {	
		
		Lightbox.activeImage = imageNum;	// update global var

		// hide elements during transition
		$('#loading').show();
		$('#lightboxImage').hide();
		$('#hoverNav').hide();
		$('#prevLink').hide();
		$('#nextLink').hide();
		$('#imageDataContainer').hide();
		$('#numberDisplay').hide();		
		
		imgPreloader = new Image();
		
		// once image is preloaded, resize image container
		imgPreloader.onload=function(){
			document.getElementById('lightboxImage').src = Lightbox.imageArray[Lightbox.activeImage][0];
			Lightbox.resizeImageContainer(imgPreloader.width, imgPreloader.height);
			
			imgPreloader.onload=function(){};	//	clear onLoad, IE behaves irratically with animated gifs otherwise 
		}
		imgPreloader.src = Lightbox.imageArray[Lightbox.activeImage][0];
	},

	//
	//	resizeImageContainer()
	//
	resizeImageContainer: function( imgWidth, imgHeight) {

		// get curren width and height
		this.widthCurrent = Lightbox.getWidth('outerImageContainer');
		this.heightCurrent = Lightbox.getHeight('outerImageContainer');

		// get new width and height
		var widthNew = (imgWidth  + (Lightbox.borderSize * 2));
		var heightNew = (imgHeight  + (Lightbox.borderSize * 2));

		// scalars based on change from old to new
		this.xScale = ( widthNew / this.widthCurrent) * 100;
		this.yScale = ( heightNew / this.heightCurrent) * 100;

		// calculate size difference between new and old image, and resize if necessary
		wDiff = this.widthCurrent - widthNew;
		hDiff = this.heightCurrent - heightNew;

		if(!(hDiff == 0)){
			$('#outerImageContainer').animate({width: widthNew},400,'linear',function(){
				if(!(wDiff == 0)){
					$("#outerImageContainer").animate({height: heightNew},400,'linear',function(){
						Lightbox.showImage();
					});
				}	
			});
		}


		// if new and old image are same size and no scaling transition is necessary, 
		// do a quick pause to prevent image flicker.
		if((hDiff == 0) && (wDiff == 0)){
			if (navigator.appVersion.indexOf("MSIE")!=-1){ Lightbox.pause(250); } else { Lightbox.pause(100);} 
		}

		Lightbox.setHeight('prevLink', imgHeight);
		Lightbox.setHeight('nextLink', imgHeight);
		Lightbox.setWidth( 'imageDataContainer', widthNew);

		
	},
	
	//
	//	showImage()
	//	Display image and begin preloading neighbors.
	//
	showImage: function(){
		$('#loading').hide();
		$('#lightboxImage').fadeIn("fast");
		Lightbox.updateDetails();
		this.preloadNeighborImages();
	},

	//
	//	updateDetails()
	//	Display caption, image number, and bottom nav.
	//
	updateDetails: function() {
	$("#imageDataContainer").hide();
		// if caption is not null
		if(Lightbox.imageArray[Lightbox.activeImage][1]){
			$('#caption').html(Lightbox.imageArray[Lightbox.activeImage][1]).show();
		}
		
		// if image is part of set display 'Image x of x' 
		if(Lightbox.imageArray.length > 1){
			$('#numberDisplay').html("Image " + eval(Lightbox.activeImage + 1) + " of " + Lightbox.imageArray.length).show();
		}

		$("#imageDataContainer").hide().slideDown("slow");
		var arrayPageSize = Lightbox.getPageSize();
		Lightbox.setHeight('overlay',arrayPageSize[1]);
		Lightbox.updateNav();
	},

	//
	//	updateNav()
	//	Display appropriate previous and next hover navigation.
	//
	updateNav: function() {

		$('#hoverNav').show();				

		// if not first image in set, display prev image button
		if(Lightbox.activeImage != 0){
			$('#prevLink').show().click(function(){
				Lightbox.changeImage(Lightbox.activeImage - 1); return false;
			});
		}

		// if not last image in set, display next image button
		if(Lightbox.activeImage != (Lightbox.imageArray.length - 1)){
			$('#nextLink').show().click(function(){
				Lightbox.changeImage(Lightbox.activeImage +1); return false;
			});
		}
		
		this.enableKeyboardNav();
	},


	enableKeyboardNav: function() {
		document.onkeydown = this.keyboardAction; 
	},

	disableKeyboardNav: function() {
		document.onkeydown = '';
	},

	keyboardAction: function(e) {
		if (e == null) { // ie
			keycode = event.keyCode;
			escapeKey = 27;
		} else { // mozilla
			keycode = e.keyCode;
			escapeKey = e.DOM_VK_ESCAPE;
		}

		key = String.fromCharCode(keycode).toLowerCase();
		
		if((key == 'x') || (key == 'o') || (key == 'c') || (keycode == escapeKey)){	// close lightbox
			Lightbox.end();
		} else if((key == 'p') || (keycode == 37)){	// display previous image
			if(Lightbox.activeImage != 0){
				Lightbox.disableKeyboardNav();
				Lightbox.changeImage(Lightbox.activeImage - 1);
			}
		} else if((key == 'n') || (keycode == 39)){	// display next image
			if(Lightbox.activeImage != (Lightbox.imageArray.length - 1)){
				Lightbox.disableKeyboardNav();
				Lightbox.changeImage(Lightbox.activeImage + 1);
			}
		}

	},

	preloadNeighborImages: function(){

		if((Lightbox.imageArray.length - 1) > Lightbox.activeImage){
			preloadNextImage = new Image();
			preloadNextImage.src = Lightbox.imageArray[Lightbox.activeImage + 1][0];
		}
		if(Lightbox.activeImage > 0){
			preloadPrevImage = new Image();
			preloadPrevImage.src = Lightbox.imageArray[Lightbox.activeImage - 1][0];
		}
	
	},

	end: function() {
		this.disableKeyboardNav();
		$('#lightbox').hide();
		$("#overlay").fadeOut();
		$("select, object, embed").show();
	},
	
	getWidth: function(element) {
	   	element = document.getElementById(element);
	   	return element.offsetWidth; 
	},
	getHeight: function(element){
		element = document.getElementById(element);
		return element.offsetHeight;
	},
	setWidth: function(element,w) {
	   	element = document.getElementById(element);
    	element.style.width = w +"px";
	},
	setHeight: function(element,h) {
   		element = document.getElementById(element);
    	element.style.height = h +"px";
	},
	setTop: function(element,t) {
	   	element = document.getElementById(element);
    	element.style.top = t +"px";
	},
	setLeft: function(element,l) {
	   	element = document.getElementById(element);
    	element.style.left = l +"px";
	},
	setSrc: function(element,src) {
    	element = document.getElementById(element);
    	element.src = src; 
	},
	setHref: function(element,href) {
    	element = document.getElementById(element);
    	element.href = href; 
	},
	setInnerHTML: function(element,content) {
		element = document.getElementById(element);
		element.innerHTML = content;
	},
	getPageSize : function(){
		var xScroll, yScroll;

		if (window.innerHeight && window.scrollMaxY) {	
			xScroll = window.innerWidth + window.scrollMaxX;
			yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}

		var windowWidth, windowHeight;

		if (self.innerHeight) {	// all except Explorer
			if(document.documentElement.clientWidth){
				windowWidth = document.documentElement.clientWidth; 
			} else {
				windowWidth = self.innerWidth;
			}
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}	

		// for small pages with total height less then height of the viewport
		if(yScroll < windowHeight){
			pageHeight = windowHeight;
		} else { 
			pageHeight = yScroll;
		}


		// for small pages with total width less then width of the viewport
		if(xScroll < windowWidth){	
			pageWidth = xScroll;		
		} else {
			pageWidth = windowWidth;
		}

		arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
		return arrayPageSize;
	},
	getPageScroll : function(){
		
		var xScroll, yScroll;

		if (self.pageYOffset) {
			yScroll = self.pageYOffset;
			xScroll = self.pageXOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){	 // Explorer 6 Strict
			yScroll = document.documentElement.scrollTop;
			xScroll = document.documentElement.scrollLeft;
		} else if (document.body) {// all other Explorers
			yScroll = document.body.scrollTop;
			xScroll = document.body.scrollLeft;	
		}

		arrayPageScroll = new Array(xScroll,yScroll) 
		return arrayPageScroll;
	},
	pause : function(ms){
		var date = new Date();
		curDate = null;
		do{var curDate = new Date();}
		while( curDate - date < ms);
	}
};

$(document).ready(function(){
	Lightbox.initialize();
});