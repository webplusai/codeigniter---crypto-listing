(function (lib, img, cjs, ss) {

var p; // shortcut to reference prototypes
lib.webFontTxtInst = {}; 
var loadedTypekitCount = 0;
var loadedGoogleCount = 0;
var gFontsUpdateCacheList = [];
var tFontsUpdateCacheList = [];

// library properties:
lib.properties = {
	width: 728,
	height: 90,
	fps: 30,
	color: "#666666",
	opacity: 1.00,
	webfonts: {},
	manifest: [
		{src:"images/728x90_mobilego_001_atlas_.png", id:"728x90_mobilego_001_atlas_"}
	]
};



lib.ssMetadata = [
		{name:"728x90_mobilego_001_atlas_", frames: [[208,832,80,61],[0,416,102,102],[0,520,102,102],[208,624,102,102],[208,520,102,102],[208,416,102,102],[208,0,102,102],[104,832,102,102],[208,312,102,102],[208,208,102,102],[208,728,102,102],[208,104,102,102],[0,624,102,102],[0,728,102,102],[0,832,102,102],[104,0,102,102],[0,0,102,102],[0,104,102,102],[0,208,102,102],[0,312,102,102],[104,104,102,102],[104,208,102,102],[104,312,102,102],[104,416,102,102],[104,520,102,102],[104,624,102,102],[104,728,102,102]]}
];



lib.updateListCache = function (cacheList) {		
	for(var i = 0; i < cacheList.length; i++) {		
		if(cacheList[i].cacheCanvas)		
			cacheList[i].updateCache();		
	}		
};		

lib.addElementsToCache = function (textInst, cacheList) {		
	var cur = textInst;		
	while(cur != exportRoot) {		
		if(cacheList.indexOf(cur) != -1)		
			break;		
		cur = cur.parent;		
	}		
	if(cur != exportRoot) {	//we have found an element in the list		
		var cur2 = textInst;		
		var index = cacheList.indexOf(cur);		
		while(cur2 != cur) { //insert all it's children just before it		
			cacheList.splice(index, 0, cur2);		
			cur2 = cur2.parent;		
			index++;		
		}		
	}		
	else {	//append element and it's parents in the array		
		cur = textInst;		
		while(cur != exportRoot) {		
			cacheList.push(cur);		
			cur = cur.parent;		
		}		
	}		
};		

lib.gfontAvailable = function(family, totalGoogleCount) {		
	lib.properties.webfonts[family] = true;		
	var txtInst = lib.webFontTxtInst && lib.webFontTxtInst[family] || [];		
	for(var f = 0; f < txtInst.length; ++f)		
		lib.addElementsToCache(txtInst[f], gFontsUpdateCacheList);		

	loadedGoogleCount++;		
	if(loadedGoogleCount == totalGoogleCount) {		
		lib.updateListCache(gFontsUpdateCacheList);		
	}		
};		

lib.tfontAvailable = function(family, totalTypekitCount) {		
	lib.properties.webfonts[family] = true;		
	var txtInst = lib.webFontTxtInst && lib.webFontTxtInst[family] || [];		
	for(var f = 0; f < txtInst.length; ++f)		
		lib.addElementsToCache(txtInst[f], tFontsUpdateCacheList);		

	loadedTypekitCount++;		
	if(loadedTypekitCount == totalTypekitCount) {		
		lib.updateListCache(tFontsUpdateCacheList);		
	}		
};
// symbols:



(lib.cursor = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(0);
}).prototype = p = new cjs.Sprite();



(lib.ic1 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(1);
}).prototype = p = new cjs.Sprite();



(lib.ic10 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(2);
}).prototype = p = new cjs.Sprite();



(lib.ic11 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(3);
}).prototype = p = new cjs.Sprite();



(lib.ic12 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(4);
}).prototype = p = new cjs.Sprite();



(lib.ic13 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(5);
}).prototype = p = new cjs.Sprite();



(lib.ic14 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(6);
}).prototype = p = new cjs.Sprite();



(lib.ic15 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(7);
}).prototype = p = new cjs.Sprite();



(lib.ic16 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(8);
}).prototype = p = new cjs.Sprite();



(lib.ic17 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(9);
}).prototype = p = new cjs.Sprite();



(lib.ic18 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(10);
}).prototype = p = new cjs.Sprite();



(lib.ic19 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(11);
}).prototype = p = new cjs.Sprite();



(lib.ic2 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(12);
}).prototype = p = new cjs.Sprite();



(lib.ic20 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(13);
}).prototype = p = new cjs.Sprite();



(lib.ic21 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(14);
}).prototype = p = new cjs.Sprite();



(lib.ic22 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(15);
}).prototype = p = new cjs.Sprite();



(lib.ic23 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(16);
}).prototype = p = new cjs.Sprite();



(lib.ic24 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(17);
}).prototype = p = new cjs.Sprite();



(lib.ic25 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(18);
}).prototype = p = new cjs.Sprite();



(lib.ic26 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(19);
}).prototype = p = new cjs.Sprite();



(lib.ic3 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(20);
}).prototype = p = new cjs.Sprite();



(lib.ic4 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(21);
}).prototype = p = new cjs.Sprite();



(lib.ic5 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(22);
}).prototype = p = new cjs.Sprite();



(lib.ic6 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(23);
}).prototype = p = new cjs.Sprite();



(lib.ic7 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(24);
}).prototype = p = new cjs.Sprite();



(lib.ic8 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(25);
}).prototype = p = new cjs.Sprite();



(lib.ic9 = function() {
	this.spriteSheet = ss["728x90_mobilego_001_atlas_"];
	this.gotoAndStop(26);
}).prototype = p = new cjs.Sprite();



(lib.Tween4 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AAlCEIhJicIAACcIg4AAIAAjOIAAg5IA4AAIBJCcIAAicIA4AAIAAEHg");
	this.shape.setTransform(175.5,1.4);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_1.setTransform(154.2,1.4);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFFFFF").s().p("AgyCEIAAg5IAYAAIAAiVIgYAAIAAg5IBlAAIAAA5IgXAAIAACVIAXAAIAAA5g");
	this.shape_2.setTransform(137.6,1.4);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFFFFF").s().p("AgbCEIAAjOIg8AAIAAg5ICvAAIAAA5Ig8AAIAADOg");
	this.shape_3.setTransform(122.3,1.4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAC3Ih3AAIAABQgAgfgCIA/AAIAAhIIg/AAg");
	this.shape_4.setTransform(103,1.4);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_5.setTransform(82.3,1.4);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFFFFF").s().p("AhgCEIAAg5IAbAAIAAiVIgbAAIAAg5IDBAAIAAEHgAgNBLIA1AAIAAiVIg1AAg");
	this.shape_6.setTransform(60.7,1.4);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFFFFF").s().p("AAkCEIgGg/Ig8AAIgFA/Ig5AAIAZkHICGAAIAaEHgAgZANIAzAAIgJhXIghAAg");
	this.shape_7.setTransform(40.3,1.4);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_8.setTransform(11.6,1.4);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFFFFF").s().p("AgbCEIAAjOIg8AAIAAg5ICvAAIAAA5Ig8AAIAADOg");
	this.shape_9.setTransform(-7.8,1.4);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAC3Ih3AAIAABQgAgfgCIA/AAIAAhIIg/AAg");
	this.shape_10.setTransform(-27.1,1.4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFFFFF").s().p("AgbCEIAAhtIgzhhIgeg5IA+AAIAuBjIAvhjIA+AAIhRCaIAABtg");
	this.shape_11.setTransform(-47.3,1.4);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#FFFFFF").s().p("AAfCEIgphXIgbAAIAABXIg5AAIAAkHICwAAIAACuIgkAAIAxBZgAglgKIA+AAIAAhAIg+AAg");
	this.shape_12.setTransform(-67,1.4);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAABTIg4AAIAAgaIg/AAIAACVIA/AAIAAgaIA4AAIAABTg");
	this.shape_13.setTransform(-88,1.4);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.f("#FFFFFF").s().p("AhWCEIAAjOIAAg5IA4AAIAADOIA9AAIAAgVIA4AAIAABOg");
	this.shape_14.setTransform(-116.4,1.4);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f("#FFFFFF").s().p("AAkCEIgFg/Ig9AAIgGA/Ig4AAIAakHICFAAIAaEHgAgZANIAyAAIgIhXIgiAAg");
	this.shape_15.setTransform(-136.1,1.4);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.f("#FFFFFF").s().p("AhVCEIAAkHICrAAIAAA5IhzAAIAAAvIA6AAIAAA3Ig6AAIAAAvIBzAAIAAA5g");
	this.shape_16.setTransform(-155.3,1.4);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f("#FFFFFF").s().p("AAfCEIgphXIgbAAIAABXIg5AAIAAkHICwAAIAACuIgkAAIAxBZgAglgKIA+AAIAAhAIg+AAg");
	this.shape_17.setTransform(-175.3,1.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_17},{t:this.shape_16},{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-188.3,-21.3,376.8,42.7);


(lib.Tween3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AAlCEIhJicIAACcIg4AAIAAjOIAAg5IA4AAIBJCcIAAicIA4AAIAAEHg");
	this.shape.setTransform(175.5,1.4);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_1.setTransform(154.2,1.4);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFFFFF").s().p("AgyCEIAAg5IAYAAIAAiVIgYAAIAAg5IBlAAIAAA5IgXAAIAACVIAXAAIAAA5g");
	this.shape_2.setTransform(137.6,1.4);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFFFFF").s().p("AgbCEIAAjOIg8AAIAAg5ICvAAIAAA5Ig8AAIAADOg");
	this.shape_3.setTransform(122.3,1.4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAC3Ih3AAIAABQgAgfgCIA/AAIAAhIIg/AAg");
	this.shape_4.setTransform(103,1.4);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_5.setTransform(82.3,1.4);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFFFFF").s().p("AhgCEIAAg5IAbAAIAAiVIgbAAIAAg5IDBAAIAAEHgAgNBLIA1AAIAAiVIg1AAg");
	this.shape_6.setTransform(60.7,1.4);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFFFFF").s().p("AAkCEIgGg/Ig8AAIgFA/Ig5AAIAZkHICGAAIAaEHgAgZANIAzAAIgJhXIghAAg");
	this.shape_7.setTransform(40.3,1.4);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_8.setTransform(11.6,1.4);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFFFFF").s().p("AgbCEIAAjOIg8AAIAAg5ICvAAIAAA5Ig8AAIAADOg");
	this.shape_9.setTransform(-7.8,1.4);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAC3Ih3AAIAABQgAgfgCIA/AAIAAhIIg/AAg");
	this.shape_10.setTransform(-27.1,1.4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFFFFF").s().p("AgbCEIAAhtIgzhhIgeg5IA+AAIAuBjIAvhjIA+AAIhRCaIAABtg");
	this.shape_11.setTransform(-47.3,1.4);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#FFFFFF").s().p("AAfCEIgphXIgbAAIAABXIg5AAIAAkHICwAAIAACuIgkAAIAxBZgAglgKIA+AAIAAhAIg+AAg");
	this.shape_12.setTransform(-67,1.4);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAABTIg4AAIAAgaIg/AAIAACVIA/AAIAAgaIA4AAIAABTg");
	this.shape_13.setTransform(-88,1.4);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.f("#FFFFFF").s().p("AhWCEIAAjOIAAg5IA4AAIAADOIA9AAIAAgVIA4AAIAABOg");
	this.shape_14.setTransform(-116.4,1.4);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f("#FFFFFF").s().p("AAkCEIgFg/Ig9AAIgGA/Ig4AAIAakHICFAAIAaEHgAgZANIAyAAIgIhXIgiAAg");
	this.shape_15.setTransform(-136.1,1.4);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.f("#FFFFFF").s().p("AhVCEIAAkHICrAAIAAA5IhzAAIAAAvIA6AAIAAA3Ig6AAIAAAvIBzAAIAAA5g");
	this.shape_16.setTransform(-155.3,1.4);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f("#FFFFFF").s().p("AAfCEIgphXIgbAAIAABXIg5AAIAAkHICwAAIAACuIgkAAIAxBZgAglgKIA+AAIAAhAIg+AAg");
	this.shape_17.setTransform(-175.3,1.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_17},{t:this.shape_16},{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-188.3,-21.3,376.8,42.7);


(lib.Tween2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape.setTransform(95,4);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AAbBiIg1hyIAAByIgzAAIAAiRIAAgyIAzAAIA1ByIAAhyIAzAAIAADDg");
	this.shape_1.setTransform(77.1,4);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFFFFF").s().p("AgYBiIAAiRIAAgyIAxAAIAADDg");
	this.shape_2.setTransform(64,4);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFFFFF").s().p("AAoBiIAAh3IgoBLIgnhLIAAB3IgzAAIAAiRIAAgyIAzAAIAnBNIAohNIAzAAIAADDg");
	this.shape_3.setTransform(49.6,4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFFFFF").s().p("AAaBiIgEgqIgrAAIgEAqIgyAAIAZjDIBlAAIAYDDgAgQAFIAhAAIgFg0IgXAAg");
	this.shape_4.setTransform(31.2,4);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_5.setTransform(14.8,4);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFFFFF").s().p("AAVBiIAAhKIgpAAIAABKIgzAAIAAiRIAAgyIAzAAIAABJIApAAIAAhJIAzAAIAADDg");
	this.shape_6.setTransform(-10.8,4);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_7.setTransform(-28.1,4);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFFFFF").s().p("AhGBiIAAiRIAAgyIAzAAIAACRIAnAAIAAiRIAzAAIAADDg");
	this.shape_8.setTransform(-45.3,4);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFFFFF").s().p("AhHBiIAAjDICPAAIAADDgAgUAwIApAAIAAhfIgpAAg");
	this.shape_9.setTransform(-62.6,4);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFFFFF").s().p("AAVBiIAAhKIgpAAIAABKIgzAAIAAiRIAAgyIAzAAIAABJIApAAIAAhJIAzAAIAADDg");
	this.shape_10.setTransform(-80,4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFFFFF").s().p("AgYBiIAAiRIgvAAIAAgyICPAAIAAAyIgvAAIAACRg");
	this.shape_11.setTransform(-96.1,4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-105.5,-20.3,211.2,40.7);


(lib.Tween1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape.setTransform(95,4);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AAbBiIg1hyIAAByIgzAAIAAiRIAAgyIAzAAIA1ByIAAhyIAzAAIAADDg");
	this.shape_1.setTransform(77.1,4);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFFFFF").s().p("AgYBiIAAiRIAAgyIAxAAIAADDg");
	this.shape_2.setTransform(64,4);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFFFFF").s().p("AAoBiIAAh3IgoBLIgnhLIAAB3IgzAAIAAiRIAAgyIAzAAIAnBNIAohNIAzAAIAADDg");
	this.shape_3.setTransform(49.6,4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFFFFF").s().p("AAaBiIgEgqIgrAAIgEAqIgyAAIAZjDIBlAAIAYDDgAgQAFIAhAAIgFg0IgXAAg");
	this.shape_4.setTransform(31.2,4);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_5.setTransform(14.8,4);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFFFFF").s().p("AAVBiIAAhKIgpAAIAABKIgzAAIAAiRIAAgyIAzAAIAABJIApAAIAAhJIAzAAIAADDg");
	this.shape_6.setTransform(-10.8,4);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_7.setTransform(-28.1,4);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFFFFF").s().p("AhGBiIAAiRIAAgyIAzAAIAACRIAnAAIAAiRIAzAAIAADDg");
	this.shape_8.setTransform(-45.3,4);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFFFFF").s().p("AhHBiIAAjDICPAAIAADDgAgUAwIApAAIAAhfIgpAAg");
	this.shape_9.setTransform(-62.6,4);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFFFFF").s().p("AAVBiIAAhKIgpAAIAABKIgzAAIAAiRIAAgyIAzAAIAABJIApAAIAAhJIAzAAIAADDg");
	this.shape_10.setTransform(-80,4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFFFFF").s().p("AgYBiIAAiRIgvAAIAAgyICPAAIAAAyIgvAAIAACRg");
	this.shape_11.setTransform(-96.1,4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-105.5,-20.3,211.2,40.7);


(lib.Symbol7 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#00CDFF").s().p("AxhDMIAAmXMAjDAAAIAAGXg");
	this.shape.setTransform(103.3,20.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-9,0,224.5,40.9);


(lib.Symbol4 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFD100").s().p("AgmA2IAAgXIAwAAIAAgUIgwAAIAAglIAAgcIBNAAIAAAZIgwAAIAAASIAwAAIAABBg");
	this.shape.setTransform(249.8,14.5);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFD100").s().p("AgkA2IAAhQIAAgcIBJAAIAAAbIgtAAIAAAQIAWAAIAAAYIgWAAIAAAPIAtAAIAAAag");
	this.shape_1.setTransform(240.9,14.5);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFD100").s().p("AAWA2IAAhBIgWAoIgVgoIAABBIgdAAIAAhQIAAgcIAdAAIAVAsIAWgsIAcAAIAABsg");
	this.shape_2.setTransform(230.6,14.5);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFD100").s().p("AAOA2IgCgXIgXAAIgDAXIgbAAIAOhsIA3AAIAOBsgAgIACIARAAIgCgcIgNAAg");
	this.shape_3.setTransform(220.3,14.5);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFD100").s().p("AgmA2IAAhsIBOAAIAAAcIgyAAIAAA2IAVAAIAAgZIAdAAIAAAzg");
	this.shape_4.setTransform(211.2,14.5);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFD100").s().p("AgkA2IAAhQIAAgcIBJAAIAAAbIgtAAIAAAQIAWAAIAAAYIgWAAIAAAPIAtAAIAAAag");
	this.shape_5.setTransform(197.4,14.5);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFD100").s().p("AglA2IAAhQIAAgcIAdAAIAABSIAuAAIAAAag");
	this.shape_6.setTransform(188.8,14.5);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFD100").s().p("AgNA2IAAhQIAAgcIAbAAIAABsg");
	this.shape_7.setTransform(182,14.5);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFD100").s().p("AgnA2IAAhQIAAgcIBPAAIAAAtIgNAJIANAKIAAAsgAgLAfIAXAAIAAgNIgHgIIgQAAgAgLgJIAQAAIAHgJIAAgLIgXAAg");
	this.shape_8.setTransform(175,14.5);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFD100").s().p("AgnA2IAAhsIBPAAIAABsgAgKAaIAVAAIAAg0IgVAAg");
	this.shape_9.setTransform(165.3,14.5);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFD100").s().p("AAWA2IAAhBIgWAoIgVgoIAABBIgcAAIAAhQIAAgcIAcAAIAVAsIAWgsIAcAAIAABsg");
	this.shape_10.setTransform(154.8,14.5);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFD100").s().p("AgjA2IAAhQIAAgcIBHAAIAAAbIgrAAIAAASIAfAAIAAAYIgfAAIAAAng");
	this.shape_11.setTransform(140.1,14.5);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#FFD100").s().p("AgnA2IAAhsIBPAAIAABsgAgKAaIAVAAIAAg0IgVAAg");
	this.shape_12.setTransform(130.8,14.5);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.f("#FFD100").s().p("AgmA2IAAgXIAwAAIAAgUIgwAAIAAglIAAgcIBNAAIAAAZIgwAAIAAASIAwAAIAABBg");
	this.shape_13.setTransform(116.6,14.5);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.f("#FFD100").s().p("AgrA2IAAgcIAMAAIAAg0IgMAAIAAgcIBXAAIAABsgAgDAaIATAAIAAg0IgTAAg");
	this.shape_14.setTransform(106.6,14.5);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f("#FFD100").s().p("AgkA2IAAhQIAAgcIBJAAIAAAbIgtAAIAAAQIAWAAIAAAYIgWAAIAAAPIAtAAIAAAag");
	this.shape_15.setTransform(97.2,14.5);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.f("#FFD100").s().p("AAKA2IgOggIgIAAIAAAgIgdAAIAAhQIAAgcIBOAAIAABJIgPAAIAUAjgAgMgCIAVAAIAAgZIgVAAg");
	this.shape_16.setTransform(88,14.5);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f("#FFD100").s().p("AgrA2IAAgcIAMAAIAAg0IgMAAIAAgcIBXAAIAABsgAgDAaIATAAIAAg0IgTAAg");
	this.shape_17.setTransform(77.6,14.5);

	this.shape_18 = new cjs.Shape();
	this.shape_18.graphics.f("#FFD100").s().p("AAOA2Igcg+IAAA+IgcAAIAAhQIAAgcIAcAAIAcA/IAAg/IAdAAIAABsg");
	this.shape_18.setTransform(67.3,14.5);

	this.shape_19 = new cjs.Shape();
	this.shape_19.graphics.f("#FFD100").s().p("AgnA2IAAhQIAAgcIAdAAIAABQIAVAAIAAhQIAcAAIAABsg");
	this.shape_19.setTransform(57.3,14.5);

	this.shape_20 = new cjs.Shape();
	this.shape_20.graphics.f("#FFD100").s().p("AALA2IAAgpIgVAAIAAApIgdAAIAAhQIAAgcIAdAAIAAApIAVAAIAAgpIAdAAIAABsg");
	this.shape_20.setTransform(47.6,14.5);

	this.shape_21 = new cjs.Shape();
	this.shape_21.graphics.f("#FFD100").s().p("AALA2IAAgpIgVAAIAAApIgdAAIAAhQIAAgcIAdAAIAAApIAVAAIAAgpIAdAAIAABsg");
	this.shape_21.setTransform(33.3,14.5);

	this.shape_22 = new cjs.Shape();
	this.shape_22.graphics.f("#FFD100").s().p("AgNA2IAAhQIgaAAIAAgcIBPAAIAAAcIgaAAIAABQg");
	this.shape_22.setTransform(24.3,14.5);

	this.shape_23 = new cjs.Shape();
	this.shape_23.graphics.f("#FFD100").s().p("AgNA2IAAhQIAAgcIAbAAIAABsg");
	this.shape_23.setTransform(18,14.5);

	this.shape_24 = new cjs.Shape();
	this.shape_24.graphics.f("#FFD100").s().p("Ag7A2IAAhQIAAgcIAcAAIAABQIASAAIAAhQIAaAAIAABQIASAAIAAhQIAdAAIAABsg");
	this.shape_24.setTransform(9,14.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_24},{t:this.shape_23},{t:this.shape_22},{t:this.shape_21},{t:this.shape_20},{t:this.shape_19},{t:this.shape_18},{t:this.shape_17},{t:this.shape_16},{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,256.7,24.6);


(lib.Symbol2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.gotoAndStop(Math.floor(Math.random()*26)+1);
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(26));

	// ic26.png
	this.instance = new lib.ic26();
	this.instance.parent = this;
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(25).to({_off:false},0).wait(1));

	// ic25.png
	this.instance_1 = new lib.ic25();
	this.instance_1.parent = this;
	this.instance_1._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).wait(24).to({_off:false},0).to({_off:true},1).wait(1));

	// ic24.png
	this.instance_2 = new lib.ic24();
	this.instance_2.parent = this;
	this.instance_2._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(23).to({_off:false},0).to({_off:true},1).wait(2));

	// ic23.png
	this.instance_3 = new lib.ic23();
	this.instance_3.parent = this;
	this.instance_3._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_3).wait(22).to({_off:false},0).to({_off:true},1).wait(3));

	// ic22.png
	this.instance_4 = new lib.ic22();
	this.instance_4.parent = this;
	this.instance_4._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_4).wait(21).to({_off:false},0).to({_off:true},1).wait(4));

	// ic21.png
	this.instance_5 = new lib.ic21();
	this.instance_5.parent = this;
	this.instance_5._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_5).wait(20).to({_off:false},0).to({_off:true},1).wait(5));

	// ic20.png
	this.instance_6 = new lib.ic20();
	this.instance_6.parent = this;
	this.instance_6._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_6).wait(19).to({_off:false},0).to({_off:true},1).wait(6));

	// ic19.png
	this.instance_7 = new lib.ic19();
	this.instance_7.parent = this;
	this.instance_7._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_7).wait(18).to({_off:false},0).to({_off:true},1).wait(7));

	// ic18.png
	this.instance_8 = new lib.ic18();
	this.instance_8.parent = this;
	this.instance_8._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_8).wait(17).to({_off:false},0).to({_off:true},1).wait(8));

	// ic17.png
	this.instance_9 = new lib.ic17();
	this.instance_9.parent = this;
	this.instance_9._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_9).wait(16).to({_off:false},0).to({_off:true},1).wait(9));

	// ic16.png
	this.instance_10 = new lib.ic16();
	this.instance_10.parent = this;
	this.instance_10._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_10).wait(15).to({_off:false},0).to({_off:true},1).wait(10));

	// ic15.png
	this.instance_11 = new lib.ic15();
	this.instance_11.parent = this;
	this.instance_11._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_11).wait(14).to({_off:false},0).to({_off:true},1).wait(11));

	// ic14.png
	this.instance_12 = new lib.ic14();
	this.instance_12.parent = this;
	this.instance_12._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_12).wait(13).to({_off:false},0).to({_off:true},1).wait(12));

	// ic13.png
	this.instance_13 = new lib.ic13();
	this.instance_13.parent = this;
	this.instance_13._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_13).wait(12).to({_off:false},0).to({_off:true},1).wait(13));

	// ic12.png
	this.instance_14 = new lib.ic12();
	this.instance_14.parent = this;
	this.instance_14._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_14).wait(11).to({_off:false},0).to({_off:true},1).wait(14));

	// ic11.png
	this.instance_15 = new lib.ic11();
	this.instance_15.parent = this;
	this.instance_15._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_15).wait(10).to({_off:false},0).to({_off:true},1).wait(15));

	// ic10.png
	this.instance_16 = new lib.ic10();
	this.instance_16.parent = this;
	this.instance_16._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_16).wait(9).to({_off:false},0).to({_off:true},1).wait(16));

	// ic9.png
	this.instance_17 = new lib.ic9();
	this.instance_17.parent = this;
	this.instance_17._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_17).wait(8).to({_off:false},0).to({_off:true},1).wait(17));

	// ic8.png
	this.instance_18 = new lib.ic8();
	this.instance_18.parent = this;
	this.instance_18._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_18).wait(7).to({_off:false},0).to({_off:true},1).wait(18));

	// ic7.png
	this.instance_19 = new lib.ic7();
	this.instance_19.parent = this;
	this.instance_19._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_19).wait(6).to({_off:false},0).to({_off:true},1).wait(19));

	// ic6.png
	this.instance_20 = new lib.ic6();
	this.instance_20.parent = this;
	this.instance_20._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_20).wait(5).to({_off:false},0).to({_off:true},1).wait(20));

	// ic5.png
	this.instance_21 = new lib.ic5();
	this.instance_21.parent = this;
	this.instance_21._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_21).wait(4).to({_off:false},0).to({_off:true},1).wait(21));

	// ic4.png
	this.instance_22 = new lib.ic4();
	this.instance_22.parent = this;
	this.instance_22._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_22).wait(3).to({_off:false},0).to({_off:true},1).wait(22));

	// ic3.png
	this.instance_23 = new lib.ic3();
	this.instance_23.parent = this;
	this.instance_23._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_23).wait(2).to({_off:false},0).to({_off:true},1).wait(23));

	// ic2.png
	this.instance_24 = new lib.ic2();
	this.instance_24.parent = this;
	this.instance_24._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_24).wait(1).to({_off:false},0).to({_off:true},1).wait(24));

	// Layer 1
	this.instance_25 = new lib.ic1();
	this.instance_25.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance_25).to({_off:true},1).wait(25));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,102,102);


(lib.Символ13 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.instance = new lib.cursor();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,80,61);


(lib.Символ12 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#F42434").s().p("EgIOAhSQm9AAl7inQl4inkYkjQkWkiicmOQibmNAAnPQAAnUCbmDQCcmCEWkSQEXkPF5iXQF7iVG9AAIQMAAQHOAAGACYQF+CYEWEUQESETCZGAQCZGAAAHPQgBHPiaGNQicGOkXEiQkWEjl9CnQl9CnnIAAgAwh0CQjzBniwC1QivC3hkD7QhkD8AAExQAAEpBkEBQBkECCvC5QCxC4DyBqQDzBpEgAAIQMAAQElAAD1hpQD2hqCzi4QCzi4BjkDQBkkCAAkoQAAkqhkkAQhjkAizi1Qiyi1j3hnQj1hmklgBIwMAAQkgABjzBmg");
	this.shape.setTransform(178.8,10.4,0.048,0.048);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#F42434").s().p("EgD6AhSQm9AAl7ioQl6imkWkjQkXkjibmNQibmPAAnNQAAnUCbmDQCbmCEXkSQEVkPF7iXQF7iVG9AAMAkDAAAIAALpMgkDAAAQkgAAjzBmQjzBniwC1QiwC2hjD8QhkD7AAEyQAAEpBkEBQBjECCwC5QCwC4DzBqQDzBpEgAAIccAAIAAxTI+AAAIAApxMApuAAAMAAAAmtg");
	this.shape_1.setTransform(151.2,10.4,0.048,0.048);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#00ABC7").s().p("EgdvAhSMAAAhCjMA7aAAAIAALoMgv3AAAMAAAArSMAv8AAAIAALpgAprEWIAApxMAk3AAAIAAJxg");
	this.shape_2.setTransform(127,10.4,0.048,0.048);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#00ABC7").s().p("EgcLAhSMAAAhCjILoAAMAAAA26MAsvAAAIAALpg");
	this.shape_3.setTransform(105.3,10.4,0.048,0.048);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#00ABC7").s().p("EgFzAhSMAAAhCjILmAAMAAABCjg");
	this.shape_4.setTransform(89.7,10.4,0.048,0.048);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#00ABC7").s().p("EgjvAhSMAAAhCjMAwrAAAQE3AAD/BGQD+BHC3CJQC1CKBkDRQBkDOAAEPQgBDNguCZQgvCYhSBtQhSBshtBJQhsBJh1AkQEmBdC6D1QC8D1AAF4QAAEjhhDvQhhDuiyCpQizCrj/BaQkABbk1AAgA4HVwMAhrAAAQDFgBCsgdQCqgdB7hDQB7hEBEhpQBDhqAAifQAAkHikiQQikiPk9AAI9jAAIAApxIcvAAQE8AACZh2QCXh0ABkaQgBipg/hnQhChoh4g3Qh2g3ipgSQipgSjKAAMggrAAAg");
	this.shape_5.setTransform(72.2,10.4,0.048,0.048);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#00ABC7").s().p("EgIOAhSQm9AAl6ioQl6imkWkiQkYkjibmOQibmNAAnPQAAnUCbmDQCbmDEYkRQEVkQF7iWQF6iVG9AAIQLAAQHPAAGACYQF/CZEVETQESETCZGAQCYGAAAHPQAAHPibGNQiaGNkXEkQkYEil7CmQl+ConJAAgAwh0CQjzBnivC1QiwC3hkD7QhkD8AAEwQAAEqBkEBQBkECCwC5QCwC4DyBqQDzBpEgAAIQLAAQEmAAD1hpQD3hqCyi4QC0i5BjkCQBjkDAAkoQAAkphjkAQhjj+i0i3Qiyi1j3hnQj1hmkmgBIwLAAQkgABjzBmg");
	this.shape_6.setTransform(44.6,10.4,0.048,0.048);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#00ABC7").s().p("AmBdwMgSygrSMgMuAuxIrdAAMARIg+nQBMkxElABQB2AABmBAQBnBBA0B7MAUfAvBMAUhgvBQBqj8D7AAQEOgBBYExMARCA+nIrjAAMgMuguxMgS4ArSQhBCHhaBJQhaBJiFAAQkIABh1kag");
	this.shape_7.setTransform(15,10.5,0.048,0.048);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,191.3,21);


(lib.Символ11 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#00ABC7").s().p("EgwkgHYIMvswMAkLAkKMAkNgkKIMwMwMgw9Aw5gEAs2gcXIMzs5IY7Y7Is0M/gEhSkgQfIZO5BINMNJI44ZBg");
	this.shape.setTransform(53,63.3,0.1,0.1);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#F42434").s().p("Egw6gAQMAw7gwkIM2MwMgj9AkHIXRXTIXR3RIr/r/IMWtOIZIZHMgwwAw2g");
	this.shape_1.setTransform(53.1,31.4,0.1,0.1);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,106.1,90);


(lib.Символ6 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AkIHvIAAvdIIRAAIAAPdg");
	this.shape.setTransform(26.5,49.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,53,99);


(lib.Символ5 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#2C2C2C").s().p("AhLBNIAAh4IAAggIAgAAIAAB2IAcAAIAAh2IAfAAIAAB2IAbAAIAAh2IAiAAIAACYg");
	this.shape.setTransform(195.1,22.4);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#2C2C2C").s().p("AgyBNIAAiYIBlAAIAACYgAgRArIAjAAIAAhWIgjAAg");
	this.shape_1.setTransform(180.4,22.4);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#2C2C2C").s().p("AAVBNIgphaIAABaIghAAIAAh4IAAggIAhAAIApBaIAAhaIAhAAIAACYg");
	this.shape_2.setTransform(168.1,22.4);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#2C2C2C").s().p("AgxBNIAAiYIBjAAIAAAgIhCAAIAAAcIAhAAIAAAfIghAAIAAAbIBCAAIAAAig");
	this.shape_3.setTransform(151,22.4);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#2C2C2C").s().p("AgxBNIAAh4IAAggIAgAAIAAB2IAiAAIAAgLIAiAAIAAAtg");
	this.shape_4.setTransform(139.7,22.4);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#2C2C2C").s().p("AAVBNIgDglIgiAAIgEAlIghAAIAPiYIBNAAIAPCYgAgOAHIAdAAIgFgyIgTAAg");
	this.shape_5.setTransform(128.2,22.4);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#2C2C2C").s().p("AgwBNIAAgiIBAAAIAAgcIhAAAIAAhaIBhAAIAAAgIhAAAIAAAbIBAAAIAABdg");
	this.shape_6.setTransform(116.8,22.4);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#2C2C2C").s().p("Ag3BNIAAgiIAPAAIAAhWIgPAAIAAggIBvAAIAACYgAgHArIAeAAIAAhWIgeAAg");
	this.shape_7.setTransform(104.6,22.4);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#2C2C2C").s().p("AhLBNIAAh4IAAggIAhAAIAAB2IAbAAIAAh2IAfAAIAAB2IAbAAIAAh2IAhAAIAACYg");
	this.shape_8.setTransform(89.6,22.4);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#2C2C2C").s().p("AgyBNIAAiYIBlAAIAACYgAgRArIAjAAIAAhWIgjAAg");
	this.shape_9.setTransform(74.9,22.4);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#2C2C2C").s().p("AARBNIgXgzIgPAAIAAAzIghAAIAAiYIBmAAIAABkIgWAAIAdA0gAgVgFIAkAAIAAgmIgkAAg");
	this.shape_10.setTransform(63.2,22.4);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#2C2C2C").s().p("AgyBNIAAiYIBlAAIAAAvIghAAIAAgPIgjAAIAABWIAjAAIAAgOIAhAAIAAAwg");
	this.shape_11.setTransform(50.9,22.4);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#2C2C2C").s().p("AgxBNIAAiYIBjAAIAAAgIhCAAIAAAcIAgAAIAAAfIggAAIAAAbIBCAAIAAAig");
	this.shape_12.setTransform(34.2,22.4);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.f("#2C2C2C").s().p("AASBNIAAg+IgjAAIAAA+IghAAIAAh4IAAggIAhAAIAAA7IAjAAIAAg7IAhAAIAACYg");
	this.shape_13.setTransform(22.2,22.4);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.f("#2C2C2C").s().p("AgPBNIAAh4IgjAAIAAggIBlAAIAAAgIgjAAIAAB4g");
	this.shape_14.setTransform(10.8,22.4);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f("#2C2C2C").s().p("AAVBNIgphaIAABaIghAAIAAh4IAAggIAhAAIApBaIAAhaIAhAAIAACYg");
	this.shape_15.setTransform(-5.9,22.4);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.f("#2C2C2C").s().p("AgcBNIAAgiIANAAIAAhWIgNAAIAAggIA5AAIAAAgIgNAAIAABWIANAAIAAAig");
	this.shape_16.setTransform(-15.9,22.4);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f("#2C2C2C").s().p("AgyBNIAAiYIBlAAIAACYgAgRArIAjAAIAAhWIgjAAg");
	this.shape_17.setTransform(-25.5,22.4);

	this.shape_18 = new cjs.Shape();
	this.shape_18.graphics.f("#2C2C2C").s().p("AgyBNIAAgtIAhAAIAAALIAjAAIAAhWIAAggIAhAAIAACYg");
	this.shape_18.setTransform(-37.6,22.4);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_18},{t:this.shape_17},{t:this.shape_16},{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-44.1,9.1,249.1,24.9);


(lib.Символ4 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.shape = new cjs.Shape();
	this.shape.graphics.lf(["#FFD200","#DE5F00"],[0,1],-6.2,-33.3,6.2,33.3).s().p("A0tEGIAAoMMApbAAAIAAIMg");
	this.shape.setTransform(92.2,26.3);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-40.4,0,265.3,52.6);


(lib.Символ1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AhfCTIAAklIC/AAIAAA/IiAAAIAAA1IBAAAIAAA9IhAAAIAAA1ICAAAIAAA/g");
	this.shape.setTransform(365.7,25.2);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AAiCTIguhhIgdAAIAABhIg/AAIAAklIDDAAIAADCIgoAAIA2BjgAgpgLIBFAAIAAhIIhFAAg");
	this.shape_1.setTransform(343.3,25.2);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFFFFF").s().p("AhhCTIAAklIDDAAIAAElgAgiBUIBFAAIAAinIhFAAg");
	this.shape_2.setTransform(319.4,25.2);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFFFFF").s().p("AgeCTIAAjmIhDAAIAAg/IDDAAIAAA/IhDAAIAADmg");
	this.shape_3.setTransform(297.8,25.2);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFFFFF").s().p("AhdCTIAAg/IB8AAIAAg2Ih8AAIAAiwIC7AAIAAA/Ih9AAIAAA0IB9AAIAACyg");
	this.shape_4.setTransform(276.9,25.2);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFFFFF").s().p("AhfCTIAAklIC/AAIAAA/IiAAAIAAA1IBAAAIAAA9IhAAAIAAA1ICAAAIAAA/g");
	this.shape_5.setTransform(245.5,25.2);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFFFFF").s().p("AhgCTIAAjmIAAg/IA/AAIAADmIBDAAIAAgYIA/AAIAABXg");
	this.shape_6.setTransform(224,25.2);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFFFFF").s().p("Ag3CTIAAg/IAZAAIAAinIgZAAIAAg/IBvAAIAAA/IgZAAIAACnIAZAAIAAA/g");
	this.shape_7.setTransform(205.7,25.2);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFFFFF").s().p("AhhCTIAAklIDDAAIAAB2IgkAcIAkAdIAAB2gAgiBUIBFAAIAAgiIgXgVIguAAgAgigcIAuAAIAXgVIAAgiIhFAAg");
	this.shape_8.setTransform(187.2,25.2);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFFFFF").s().p("AhhCTIAAklIDDAAIAAElgAgiBUIBFAAIAAinIhFAAg");
	this.shape_9.setTransform(164,25.2);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFFFFF").s().p("AA7CTIAAi9Ig7BnIg6hnIAAC9Ig/AAIAAjmIAAg/IA/AAIA6BsIA7hsIBAAAIAAElg");
	this.shape_10.setTransform(138.5,25.2);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFFFFF").s().p("AhhCTIAAklIDDAAIAAElgAgiBUIBFAAIAAinIhFAAg");
	this.shape_11.setTransform(103.1,25.2);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#FFFFFF").s().p("AgeCTIAAjmIhDAAIAAg/IDDAAIAAA/IhDAAIAADmg");
	this.shape_12.setTransform(81.5,25.2);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.f("#FFFFFF").s().p("AhhCTIAAklIDDAAIAADMIiEAAIAABZgAgigCIBFAAIAAhRIhFAAg");
	this.shape_13.setTransform(60.1,25.2);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.f("#FFFFFF").s().p("AgeCTIAAh6Ig6hsIggg/IBFAAIAzBtIA0htIBGAAIhbCrIAAB6g");
	this.shape_14.setTransform(37.6,25.2);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f("#FFFFFF").s().p("AAiCTIguhhIgdAAIAABhIg/AAIAAklIDDAAIAADCIgoAAIA2BjgAgpgLIBFAAIAAhIIhFAAg");
	this.shape_15.setTransform(15.6,25.2);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.f("#FFFFFF").s().p("AhhCTIAAklIDDAAIAABcIg/AAIAAgdIhFAAIAACnIBFAAIAAgdIA/AAIAABcg");
	this.shape_16.setTransform(-7.8,25.2);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f("#FFFFFF").s().p("AgeCTIAAjmIhDAAIAAg/IDDAAIAAA/IhDAAIAADmg");
	this.shape_17.setTransform(-39.2,25.2);

	this.shape_18 = new cjs.Shape();
	this.shape_18.graphics.f("#FFFFFF").s().p("AhdCTIAAg/IB8AAIAAg2Ih8AAIAAiwIC7AAIAAA/Ih9AAIAAA0IB9AAIAACyg");
	this.shape_18.setTransform(-60.1,25.2);

	this.shape_19 = new cjs.Shape();
	this.shape_19.graphics.f("#FFFFFF").s().p("AAiCTIguhhIgdAAIAABhIg/AAIAAklIDDAAIAADCIgoAAIA2BjgAgpgLIBFAAIAAhIIhFAAg");
	this.shape_19.setTransform(-82.1,25.2);

	this.shape_20 = new cjs.Shape();
	this.shape_20.graphics.f("#FFFFFF").s().p("Ag3CTIAAg/IAZAAIAAinIgZAAIAAg/IBvAAIAAA/IgZAAIAACnIAZAAIAAA/g");
	this.shape_20.setTransform(-101.3,25.2);

	this.shape_21 = new cjs.Shape();
	this.shape_21.graphics.f("#FFFFFF").s().p("AhaCTIAAklIC1AAIAAA/Ih2AAIAAA/IBZAAIAAA9IhZAAIAABqg");
	this.shape_21.setTransform(-118,25.2);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_21},{t:this.shape_20},{t:this.shape_19},{t:this.shape_18},{t:this.shape_17},{t:this.shape_16},{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-130.9,0,509.3,47.2);


(lib.Symbol1copy2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.gotoAndPlay(Math.floor(Math.random()*350)+1);
	}
	this.frame_352 = function() {
		this.gotoAndPlay(1);
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(352).call(this.frame_352).wait(1));

	// Layer 1
	this.instance = new lib.Symbol2();
	this.instance.parent = this;
	this.instance.setTransform(2510.2,51,1,1,0,0,0,51,51);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({rotation:133.7,x:51.1,y:50.9},214).to({_off:true},1).wait(138));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(2459.2,0,102,102);


(lib.Symbol1copy = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.gotoAndPlay(Math.floor(Math.random()*275)+1);
	}
	this.frame_278 = function() {
		this.gotoAndPlay(1);
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(278).call(this.frame_278).wait(1));

	// Layer 1
	this.instance = new lib.Symbol2();
	this.instance.parent = this;
	this.instance.setTransform(2577.5,51,1,1,0,0,0,51,51);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({rotation:-360,x:51},139).to({_off:true},1).wait(139));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(2526.5,0,102,102);


(lib.Symbol1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.gotoAndPlay(Math.floor(Math.random()*220)+1);
	}
	this.frame_224 = function() {
		this.gotoAndPlay(1);
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(224).call(this.frame_224).wait(1));

	// Layer 1
	this.instance = new lib.Symbol2();
	this.instance.parent = this;
	this.instance.setTransform(2564.8,51,1,1,120,0,0,51,51);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({rotation:0,x:51},122).to({_off:true},1).wait(102));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(2495.1,-18.6,139.3,139.4);


(lib.Символ10 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.instance = new lib.Символ12("synched",0);
	this.instance.parent = this;
	this.instance.setTransform(655.7,43.8,2.151,2.151,0,0,0,95.5,10.5);
	this.instance.alpha = 0;
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(24).to({_off:false},0).to({x:334.5,alpha:1},9,cjs.Ease.get(1)).wait(13));

	// Символ 11
	this.instance_1 = new lib.Символ11("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(516.5,41.9,5.97,5.97,0,0,0,53,44.7);
	this.instance_1.alpha = 0;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).to({regY:45,scaleX:0.77,scaleY:0.77,y:43.7,alpha:1},9,cjs.Ease.get(-1)).to({scaleX:1,scaleY:1},3).wait(7).to({startPosition:0},0).to({x:52},10).wait(17));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(200.1,-224.9,633.2,537.3);


(lib.Символ7 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.instance = new lib.Символ6("synched",0);
	this.instance.parent = this;
	this.instance.setTransform(67.5,49.5,1,1,0,0,0,26.5,49.5);

	this.instance_1 = new lib.Символ6("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(14.5,49.5,0.547,1,0,0,0,26.5,49.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance_1},{t:this.instance}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,94,99);


(lib.Символ3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 1
	this.instance = new lib.Symbol4();
	this.instance.parent = this;
	this.instance.setTransform(128.3,-32.8,1,1,0,0,0,128.3,12.3);

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,-45.1,256.7,24.5);


(lib.Symbol5 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Слой 2 (mask)
	var mask = new cjs.Shape();
	mask._off = true;
	var mask_graphics_9 = new cjs.Graphics().p("AzdD3IAAntMAm7AAAIAAHtg");
	var mask_graphics_10 = new cjs.Graphics().p("Az4D8IAAn3MAnwAAAIAAH3g");
	var mask_graphics_11 = new cjs.Graphics().p("A0SEBIAAoBMAolAAAIAAIBg");
	var mask_graphics_12 = new cjs.Graphics().p("A0tEGIAAoLMApbAAAIAAILg");

	this.timeline.addTween(cjs.Tween.get(mask).to({graphics:null,x:0,y:0}).wait(9).to({graphics:mask_graphics_9,x:0.1,y:0}).wait(1).to({graphics:mask_graphics_10,x:0.1,y:0}).wait(1).to({graphics:mask_graphics_11,x:0.1,y:0}).wait(1).to({graphics:mask_graphics_12,x:0.1,y:0}).wait(104));

	// Layer 1
	this.instance = new lib.Символ5("synched",0);
	this.instance.parent = this;
	this.instance.setTransform(254.7,-3.2,1,1,0,0,0,80.4,21.5);
	this.instance._off = true;

	this.instance.mask = mask;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(9).to({_off:false},0).to({x:-4,y:-1.1},8,cjs.Ease.get(1)).to({x:0.1},3).wait(96));

	// Слой 4 (mask)
	var mask_1 = new cjs.Shape();
	mask_1._off = true;
	var mask_1_graphics_28 = new cjs.Graphics().p("A0ZDyIAAnkMAozAAAIAAHkg");

	this.timeline.addTween(cjs.Tween.get(mask_1).to({graphics:null,x:0,y:0}).wait(28).to({graphics:mask_1_graphics_28,x:0.1,y:0}).wait(88));

	// Слой 3
	this.instance_1 = new lib.Символ7("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(-186,-7.4,1,1.057,0,19,0,47,49.5);
	this.instance_1._off = true;

	this.instance_1.mask = mask_1;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).wait(28).to({_off:false},0).wait(1).to({x:-185.1},0).wait(1).to({x:-182.1},0).wait(1).to({x:-176.9},0).wait(1).to({x:-169.1},0).wait(1).to({x:-158.6},0).wait(1).to({x:-144.9},0).wait(1).to({x:-127.9},0).wait(1).to({x:-107.4},0).wait(1).to({x:-83.7},0).wait(1).to({x:-57.2},0).wait(1).to({x:-28.7},0).wait(1).to({x:0.6},0).wait(1).to({x:29.4},0).wait(1).to({x:56.5},0).wait(1).to({x:81.4},0).wait(1).to({x:103.5},0).wait(1).to({x:122.7},0).wait(1).to({x:139.1},0).wait(1).to({x:152.8},0).wait(1).to({x:164},0).wait(1).to({x:173},0).wait(1).to({x:179.9},0).wait(1).to({x:185.1},0).wait(1).to({x:188.5},0).wait(1).to({x:190.5},0).wait(1).to({x:191.2},0).wait(62));

	// Layer 2
	this.instance_2 = new lib.Символ4("synched",0);
	this.instance_2.parent = this;
	this.instance_2.setTransform(-0.1,0.1,2.041,2.041,0,0,0,92,26.3);
	this.instance_2.alpha = 0;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).to({scaleX:0.94,scaleY:0.94,y:0,alpha:1},9,cjs.Ease.get(-1)).to({regX:92.1,scaleX:1,scaleY:1,x:0},3).wait(104));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-270.5,-53.6,541.4,107.4);


(lib.Symbol3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 4
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("ANcBiIAAhBIgdAAIAABBIgzAAIgfhBIASAAIgVgmIgIAAIAAAmIgMAAIAABBIg0AAIAAhBIANAAIAAgmIgNAAIAAg8IgbAAIAAA8IAKAAIAFAmIgPAAIAABBIgzAAIAAhBIgbAAIAABBIgyAAIAAhBIAHAAIAFgmIgMAAIAAgQIgIAQIhBAAIgIgQIAAAQIgcAAIAAAmIAcAAIAABBIgzAAIAAhBIgZAAIAIBBIgOAAIAAgUIglAAIgDgWIgtAAIgCAWIgwAAIAFgtIgYAAIAAAtIiPAAIAAgtIglAAIAAgbIAzAAIAAAbIAcAAIAAgmIgqAAIAAgKIAyAAIAAAKIB6AAIABgKIAvAAIgBAKIggAAIAAAmIBxAAIAAgmIAcAAIAAg8IglAAIAHA8IgvAAIgBgKIAlAAIAAgoIhtAAIABgKIBGAAIAAggICQAAIAAAgIADAAIAXAtIAYgtIgOAAIAEggIBoAAIAEAgIAlAAIAAggIAzAAIARAgIAjAAIAdA8IALAAIAAg8IgcAAIARggIAyAAIAAAgIAMAAIAAA8IAdAAIAAg8IgOAAIAAggIA0AAIAAAgIAbAAIAAggIAzAAIAOAgIAOAAIAAASIhdAAIAAAqIAkAAIAAAmIgkAAIAAATIApAAIAAgTIAWAAIAAgmIAzAAIAAAmIgVAAIAABBgABtA0IAqAAIAAgTIgqAAgANmAhIAbAAIAAgmIgbAAgAMWAhIAcAAIAAgmIgIAAgACYAhIArAAIAAgJIgrAAgAI5gFIAJAAIgCAKIAjAAIgBgKIgOAAIAAg8IgbAAgALWgFIAEAAIgEgLgAAUBiIgCgUIAmAAIAAAUgAhQBiIACgUIgSAAIAAAUIiQAAIAAgUIhwAAIAAAUIgzAAIAAgUIgrAAIAAAUIgyAAIAAgUIgdAAIAAAUIiPAAIAAgUIgeAAIAAAUIiPAAIAAgUIgdAAIAAAUIiRAAIAAgUIANAAIAAgtIAzAAIAAAtIA+AAIAAgtIA0AAIAAAtIAqAAIAAgtIAzAAIAAAtIAeAAIAAgtIAMAAIAAgmIgMAAIAAgKIAyAAIAAAKIANAAIAAAmIgNAAIAAAPIArAAIAAgPIAhAAIAAgmIghAAIAAgKIAzAAIAAAKIAdAAIAAgKIAzAAIAAAKIApAAIAAgKIA0AAIAAAKIAcAAIAAgKIA0AAIAAAKIAvAAIAAAmIAdAAIAAgmIAsAAIAAgKICRAAIAAAKIAiAAIAAAmIgiAAIAAAtIAYAAIgCAUgAiRBOIAqAAIAAgtIgqAAgAjhBOIAcAAIAAgtIgcAAgAmNBOIAcAAIAAgtIgcAAgAo6BOIAdAAIAAgtIgdAAgAk9A0IApAAIAAgTIgpAAgAnqAwIApAAIAAgPIgpAAgAhiAhIAdAAIAAgmIgdAAgAi+AhIApAAIAAgmIgpAAgAlrAhIAqAAIAAgmIgqAAgAm8AhIAeAAIAAgmIgeAAgAoaAhIArAAIAAgJIgrAAgAxHBiIAAgUIAzAAIAAAUgAykBiIAAgUIAzAAIAAAUgA0XBiIAAgUIA0AAIAAAUgAHTAhIgQAAIAAgbIAzAAIAAAbIgMAAIgMAVgATkAhIAAgbIA0AAIAAAbgASIAhIAAgmIAzAAIAAAmgAQaAhIgTgmIAmAAIALAWIAAgWIAzAAIAAAmgAt5gFIAAgKICRAAIAAAKgAvqgFIAAgKIAzAAIAAAKgAA7g3IAAgKICPAAIAAAKgAhng3IAAgqIAzAAIAAAqgAjFg3IAAgqIA0AAIAAAqgAlxg3IAAgqICQAAIAAAqgAnBg3IAAgqIA0AAIAAAqgAodg3IAAgqIAzAAIAAAqgArKg3IAAgqICQAAIAAAqgAsbg3IAAgqIAzAAIAAAqgAt5g3IAAgqIA0AAIAAAqgAwag3IAAgqICSAAIAAAqgARWhBIAAggICPAAIAAAggAQGhBIAAggIAzAAIAAAgg");
	this.shape.setTransform(150.9,55.2);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AN0BiIAAjDICPAAIAAAyIhcAAIAABjIApAAIAAguIAzAAIAABcgAMjBiIg2hyIAAByIgzAAIAAjDIAzAAIA2ByIAAhyIA0AAIAADDgAJsBiIAAjDIAzAAIAADDgAIeBiIAAh3IgoBLIgphLIAAB3IgzAAIAAjDIAzAAIApBNIAohNIAzAAIAADDgAFYBiIgEgqIgtAAIgEAqIgxAAIAXjDIBpAAIAXDDgAEsAFIAjAAIgFg0IgZAAgABSBiIAAjDICQAAIAAAyIhdAAIAABjIApAAIAAguIA0AAIAABcgAhPBiIAAhKIgrAAIAABKIgzAAIAAjDIAzAAIAABJIArAAIAAhJIAyAAIAADDgAlZBiIAAjDICPAAIAAAyIhcAAIAABjIApAAIAAguIAzAAIAABcgAoGBiIAAjDIA0AAIAACRIApAAIAAiRIAzAAIAADDgAqzBiIAAjDICRAAIAADDgAqAAwIAqAAIAAhfIgqAAgAsDBiIAAhKIgrAAIAABKIgzAAIAAjDIAzAAIAABJIArAAIAAhJIAyAAIAADDgAvTBiIAAiRIgvAAIAAgyICSAAIAAAyIgwAAIAACRg");
	this.shape_1.setTransform(118.5,55.2);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#FFFFFF").s().p("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");
	this.shape_2.setTransform(133.4,55.2);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FFFFFF").s().p("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");
	this.shape_3.setTransform(126.4,55.2);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_4.setTransform(214.1,55.2);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#FFFFFF").s().p("AAbBiIg1hyIAAByIgzAAIAAiRIAAgyIAzAAIA1ByIAAhyIAzAAIAADDg");
	this.shape_5.setTransform(196.2,55.2);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.f("#FFFFFF").s().p("AgYBiIAAiRIAAgyIAxAAIAADDg");
	this.shape_6.setTransform(183.1,55.2);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.f("#FFFFFF").s().p("AAoBiIAAh3IgoBLIgnhLIAAB3IgzAAIAAiRIAAgyIAzAAIAnBNIAohNIAzAAIAADDg");
	this.shape_7.setTransform(168.7,55.2);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#FFFFFF").s().p("AAaBiIgEgqIgrAAIgEAqIgxAAIAXjDIBnAAIAXDDgAgQAFIAhAAIgFg0IgXAAg");
	this.shape_8.setTransform(150.3,55.2);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_9.setTransform(133.9,55.2);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#FFFFFF").s().p("AAVBiIAAhKIgpAAIAABKIgzAAIAAiRIAAgyIAzAAIAABJIApAAIAAhJIAzAAIAADDg");
	this.shape_10.setTransform(108.3,55.2);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#FFFFFF").s().p("AhGBiIAAjDICNAAIAAAyIhaAAIAABjIAnAAIAAguIAzAAIAABcg");
	this.shape_11.setTransform(91,55.2);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.f("#FFFFFF").s().p("AhGBiIAAiRIAAgyIAzAAIAACRIAnAAIAAiRIAzAAIAADDg");
	this.shape_12.setTransform(73.8,55.2);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.f("#FFFFFF").s().p("AhHBiIAAjDICPAAIAADDgAgUAwIApAAIAAhfIgpAAg");
	this.shape_13.setTransform(56.5,55.2);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.f("#FFFFFF").s().p("AAVBiIAAhKIgpAAIAABKIgzAAIAAiRIAAgyIAzAAIAABJIApAAIAAhJIAzAAIAADDg");
	this.shape_14.setTransform(39.1,55.2);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f("#FFFFFF").s().p("AgYBiIAAiRIgvAAIAAgyICPAAIAAAyIgvAAIAACRg");
	this.shape_15.setTransform(23,55.2);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.f("#FFFFFF").s().p("AN0BiIAAg8IgJAAIgCgFIALAAIAAhTIgdAAIAABTIgGAAIAAAFIAGAAIAAA8Ig0AAIgcg8IAXAAIAAgFIgZAAIgYgxIAAAxIAWAAIAAAFIgWAAIAAA8IgzAAIAAg8IAWAAIAAgFIgWAAIAAhTIAGAAIAAgeIgGAAIAAgRIAzAAIAIARIgCAAIAAAeIAQAAIAgBDIAAhDIgTAAIAAgeIATAAIAAgRIA0AAIAAARIAdAAIAAgRICPAAIAAARIhkAAIAAAeIBkAAIAAADIhcAAIAABQIAUAAIAAAFIgUAAIAAAOIApAAIAAgOIAHAAIAAgFIgHAAIAAgbIAzAAIAAAbIAIAAIAAAFIgIAAIAAA8gAJsBiIAAg8IAWAAIAAgFIgWAAIAAhTIgWAAIAAgeIAWAAIAAgRIAzAAIAAARIgOAAIAOAeIAABTIAWAAIAAAFIgWAAIAAA8gAIeBiIAAg8IggAAIAAgFIgTAAIgeg2IAAA2IAZAAIABAFIgaAAIAAA8IgzAAIAAg8IgYAAIAHA8IgxAAIgEgqIgtAAIgEAqIgxAAIAHg8IApAAIAAgFIgpAAIALhTIgiAAIAAADIhdAAIAABjIApAAIAAguIA0AAIAAAbIgkAAIAAAFIAkAAIAAA8IiQAAIAAiUIhiAAIAAgeIBiAAIAAgRICQAAIAAARIAlAAIACgRIBpAAIABARIAAAAIAPAeIgLAAIALBTIAYAAIAAhTIAIAAIAQgeIgYAAIAAgRIAzAAIAJARIAXAAIAAAeIgHAAIAQAeIAQgeIACAAIAAgeIANAAIAJgRIAzAAIAAARIgWAAIAAAeIAWAAIAABTIAUAAIgCAFIgRAAIgDgFIgeAAIAAAFIAgAAIAAA8gAFGAmIAXAAIABgFIgYAAgAIBAhIAdAAIAAg2gAEsAFIAjAAIgFg0IgZAAgAETgyIAjAAIAAgeIgmAAgAB+gyIAiAAIAEgeIgmAAgAhPBiIAAhKIgrAAIAABKIgzAAIAAiUIgGAAIAAgeIAGAAIAAgRIAzAAIAAARIgHAAIAAAeIAHAAIAAAaIArAAIAAgaIAyAAIAACUgAlZBiIAAiUIgdAAIAACUIiQAAIAAiUIgHAAIAAgeIAHAAIAAgRIA0AAIAAARIgIAAIAAAeIAIAAIAABiIApAAIAAhiIgUAAIAAgeIAUAAIAAgRIAzAAIAAARIAdAAIAAgRICPAAIAAARIgUAAIAAAeIAUAAIAAADIhcAAIAABjIApAAIAAguIAzAAIAABcgAkugyIAdAAIAAgeIgdAAgAqzBiIAAiUIgeAAIAACUIgyAAIAAhKIgrAAIAABKIgzAAIAAiUIgGAAIAAgeIAGAAIAAgRIAzAAIAAARIgHAAIAAAeIAHAAIAAAaIArAAIAAgaIgUAAIAAgeIAUAAIAAgRIAyAAIAAARIAeAAIAAgRICRAAIAAARIgUAAIAAAeIAUAAIAACUgAqAAwIAqAAIAAhfIgqAAgAqGgyIAdAAIAAgeIgdAAgAvTBiIAAiRIgvAAIAAgDIhkAAIAAgeIBkAAIAAgRICSAAIAAARIgiAAIAAAeIAiAAIAAADIgwAAIAACRgAvUgyIAPAAIAAgeIgPAAgAHuAmIAQAAIgIAQgAQ0AmIAAgFIAzAAIAAAFgAhPhQIAAgRIAyAAIAAARg");
	this.shape_16.setTransform(118.5,55.2);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f("#FFFFFF").s().p("ANCBiIAAg3IgHAAIgHgPIAOAAIAAh9ICPAAIAAAyIhcAAIAABLIAUAAIAAAPIgUAAIAAAJIApAAIAAgJIAHAAIAAgPIgHAAIAAgWIAzAAIAAAWIAIAAIAAAPIgIAAIAAA3gALxBiIgag3IAVAAIAAgPIgbAAIAAAPIgWAAIAAA3IgzAAIAAg3IAWAAIAAgPIgWAAIAAh9IAzAAIA2ByIAAhyIA0AAIAAB9IgGAAIAAAPIAGAAIAAA3gAK7AcIAVAAIgVgsgAI6BiIAAg3IAWAAIAAgPIgWAAIAAh9IAzAAIAAB9IAWAAIAAAPIgWAAIAAA3gAHsBiIAAg3IggAAIAAgPIgWAAIgbgxIAAAxIAYAAIACAPIgaAAIAAA3IgzAAIAAg3IgXAAIAGA3IgxAAIgEgqIgtAAIgEAqIgxAAIAGg3IAaAAIAAgPIgYAAIAPh9IBpAAIAPB9IAZAAIAAh9IAzAAIApBNIAohNIAzAAIAAB9IAWAAIgHAPIgMAAIgIgPIgbAAIAAAPIAgAAIAAA3gAEUArIAWAAIACgPIgYAAgAHRAcIAbAAIAAgxgAD6AFIAjAAIgFg0IgZAAgAAgBiIAAg3IAzAAIAAAJIApAAIAAgJIAIAAIAAgPIgIAAIAAgWIA0AAIAAAWIAHAAIAAAPIgHAAIAAA3gAiBBiIAAg3IAGAAIAAgPIgGAAIAAgEIgrAAIAAAEIAUAAIAAAPIgUAAIAAA3IgzAAIAAg3IAVAAIAAgPIgVAAIAAhnIgdAAIAAAcIhcAAIAABLIAUAAIAAAPIgUAAIAAAJIApAAIAAgJIAIAAIAAgPIgIAAIAAgWIAzAAIAAAWIAIAAIAAAPIgIAAIAAA3IiPAAIAAg3IAUAAIAAgPIgUAAIAAhnIgdAAIAABnIAIAAIAAAPIgIAAIAAA3IiQAAIAAg3IAUAAIAAgPIgUAAIAAhnIgcAAIAABnIAGAAIAAAPIgGAAIAAA3IiRAAIAAg3IAUAAIAAgPIgUAAIAAhnIgeAAIAABnIAHAAIAAAPIgHAAIAAA3IgyAAIAAg3IAGAAIAAgPIgGAAIAAgEIgrAAIAAAEIgzAAIAAhnIgPAAIAAAcIgwAAIAACRIgzAAIAAiRIgvAAIAAgyIBfAAIAAAWIAQAAIAAgWIAzAAIAAAWIAqAAIAAgWIA0AAIAAAWIAdAAIAAgWICQAAIAAAWIAdAAIAAgWIAzAAIAAAWIAqAAIAAgWIAzAAIAAAWIAdAAIAAgWICQAAIAAAWIAcAAIAAgWIAzAAIAAAWIAqAAIAAgWIA0AAIAAAWIAxAAIAABnIAHAAIAAAPIgHAAIAAA3gAoEAwIApAAIAAgFIAHAAIAAgPIgHAAIAAhnIgpAAIAABnIAUAAIAAAPIgUAAgAqyAwIAqAAIAAgFIAHAAIAAgPIgHAAIAAhLIgqAAIAABLIAUAAIAAAPIgUAAgAisgYIArAAIAAgzIgrAAgAtggYIArAAIAAgzIgrAAgAuTBiIAAg3IAzAAIAAA3gAG+ArIAMAAIgGALgAQCArIAAgPIAzAAIAAAPgAgdArIAAgPIAwAAIAAAPgAAgAcIAAhnIgwAAIAAgWICNAAIAAAWIAzAAIAAAcIhdAAIAABLg");
	this.shape_17.setTransform(123.5,55.2);

	this.instance = new lib.Tween1("synched",0);
	this.instance.parent = this;
	this.instance.setTransform(119.1,51.3);
	this.instance._off = true;

	this.instance_1 = new lib.Tween2("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(119.2,41.4,0.381,0.381,0,0,0,0.1,0);
	this.instance_1.alpha = 0;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[]}).to({state:[{t:this.shape}]},74).to({state:[{t:this.shape_1}]},2).to({state:[{t:this.shape_2}]},2).to({state:[{t:this.shape_1}]},2).to({state:[{t:this.shape_3}]},2).to({state:[{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4}]},2).to({state:[{t:this.shape_16}]},32).to({state:[{t:this.shape_17}]},2).to({state:[{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4}]},3).to({state:[{t:this.instance}]},47).to({state:[{t:this.instance_1}]},7).to({state:[]},1).wait(3));
	this.timeline.addTween(cjs.Tween.get(this.instance).wait(168).to({_off:false},0).to({_off:true,regX:0.1,scaleX:0.38,scaleY:0.38,x:119.2,y:41.4,alpha:0},7,cjs.Ease.get(-1)).wait(4));

	// Layer 3
	this.shape_18 = new cjs.Shape();
	this.shape_18.graphics.f("#FFFFFF").s().p("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");
	this.shape_18.setTransform(165.9,19.2);

	this.shape_19 = new cjs.Shape();
	this.shape_19.graphics.f("#FFFFFF").s().p("AcACEIhLicIAACcIg5AAIAAkHIA5AAIBLCcIAAicIA4AAIAAEHgAWtCEIAAkHICxAAIAAEHgAXlBLIBBAAIAAiVIhBAAgAUsCEIAAg5IAXAAIAAiVIgXAAIAAg5IBmAAIAAA5IgWAAIAACVIAWAAIAAA5gASqCEIAAjOIg8AAIAAg5ICxAAIAAA5Ig8AAIAADOgAOtCEIAAkHICxAAIAAC3Ih5AAIAABQgAPlgCIBBAAIAAhIIhBAAgALdCEIAAkHICyAAIAAEHgAMWBLIBAAAIAAiVIhAAAgAH9CEIAAg5IAaAAIAAiVIgaAAIAAg5IDDAAIAAEHgAJQBLIA3AAIAAiVIg3AAgAG3CEIgGg/Ig+AAIgGA/Ig4AAIAZkHICIAAIAaEHgAF4ANIA0AAIgIhXIgkAAgAAaCEIAAkHICyAAIAAEHgABTBLIBAAAIAAiVIhAAAgAhpCEIAAjOIg9AAIAAg5ICwAAIAAA5Ig7AAIAADOgAlnCEIAAkHICyAAIAAC3Ih5AAIAABQgAkugCIBAAAIAAhIIhAAAgAn0CEIAAhtIhRiaIA+AAIAvBjIAvhjIA/AAIhSCaIAABtgAp9CEIgrhXIgbAAIAABXIg5AAIAAkHICyAAIAACuIgkAAIAxBZgArDgKIBAAAIAAhAIhAAAgAvICEIAAkHICyAAIAABTIg5AAIAAgaIhAAAIAACVIBAAAIAAgaIA5AAIAABTgAziCEIAAkHIA4AAIAADOIA/AAIAAgVIA4AAIAABOgA0sCEIgFg/Ig/AAIgFA/Ig5AAIAakHICHAAIAaEHgA1rANIA1AAIgJhXIgjAAgA5nCEIAAkHICuAAIAAA5Ih1AAIAAAvIA8AAIAAA3Ig8AAIAAAvIB1AAIAAA5gA65CEIgrhXIgbAAIAABXIg4AAIAAkHICxAAIAACuIgjAAIAwBZgA7/gKIBBAAIAAhAIhBAAg");
	this.shape_19.setTransform(119.1,21.7);

	this.shape_20 = new cjs.Shape();
	this.shape_20.graphics.f("#FFFFFF").s().p("AaDCdIgLgYIg5AAIAAgPIAxAAIg4h2IAAB2Ig5AAIAAiUIBAAAIAAgPIhAAAIAAg9IA5AAIAdA9IAiAAIAAAPIgbAAIAnBQIAAhQIASAAIAAgPIgSAAIAAg9IA4AAIAAA9IAwAAIAHAPIg3AAIAACUIgWAAIAAAPIAWAAIAAAYgAX/CdIAAgYIA5AAIAAAYgAUwCdIAAgYICNAAIAAgPIiNAAIAAiUIAsAAIAAgPIgsAAIAAg9ICxAAIAAA9IAdAAIAAAPIgdAAIAACUIAVAAIAAAPIgVAAIAAAYgAVoBkIBBAAIAAiCIAdAAIAAgPIgdAAIAAgEIhBAAIAAAEIAtAAIAAAPIgtAAgASvCdIAAgYIARAAIAAgPIgRAAIAAgSIAXAAIAAiCIgDAAIAAgPIADAAIAAgEIgXAAIAAg5IBmAAIAAA5IgWAAIAAAEIgDAAIAAAPIADAAIAACCIAWAAIAAASIgdAAIAAAPIAdAAIAAAYgAQtCdIAAgYIg9AAIAAgPIA9AAIAAiUIASAAIAAgPIgSAAIAAgEIg8AAIAAg5ICxAAIAAA5Ig8AAIAAAEIARAAIAAAPIgRAAIAACUIA8AAIAAAPIg8AAIAAAYgAMwCdIAAgYIgDAAIAAgFIB9AAIAAgKIApAAIAAAPIhrAAIAAAYgAJgCdIAAgYICyAAIAAAYgAGACdIAAgdIgQAAIADAdIg5AAIgDgdIhEAAIgDAdIg4AAIADgdIgaAAIgDgiIg+AAIgDAiIgOAAIAAAdIiwAAIAAgdIhNAAIAAAdIg4AAIAAgdIgRAAIAAg3IARAAIAAhnIARAAIAAgPIgRAAIAAgEIg9AAIAAgpICyAAIAAApIg9AAIAAAEIASAAIAAAPIgSAAIAABnIgQAAIAAAbIBAAAIAAgbIAdAAIAAhnIARAAIAAgPIgRAAIAAgtIBOAAIAAgQIBiAAIAAA9IAdAAIAAAPIgdAAIAABnIBxAAIAKhnIgeAAIAAgPIAgAAIAFg9ICIAAIAGA9Ig4AAIAAgEIgkAAIAAAEIgeAAIAAAPIAdAAIgFArIgOAAIAAAeIB5AAIACAeIgtAAIAAAbIA3AAIAAgbIAQAAIAAA3IAZAAIAAAFICmAAIAAAYgADaCAIAQAAIAAgcIAaAAIAAgbIgvAAgAhFCAIBqAAIAFg3IgUAAIAAhnIAdAAIAAgPIgdAAIAAgEIg+AAIAAAEIAQAAIAAAPIgQAAIAABnIgdAAgAnkCdIAAgdIA5AAIAAAdgApxCdIAAgdIgJAAIAAg3IAJAAIAAgZIgqhOIAxAAIAAgPIg4AAIgYgtIA9AAIAWAtIAkAAIAWgtIA9AAIgXAtIAjAAIAAgtICyAAIAAAtIAdAAIAAAPIgdAAIAABnIgSAAIAAA3Ig4AAIAAg3IhMAAIAAAEIh5AAIAAAzIAIAAIAAAdgAoxgeIAiAAIgqBOIAAAZIBVAAIAAhnIghAAIgHgPIglAAgAmrAVIBAAAIAAgzIAdAAIAAgPIgdAAIAAgEIhAAAIAAAEIA6AAIgIAPIgyAAgApVgHIALgXIgWAAgAnJgeIAVAAIAHgPIgkAAgAr6CdIgOgdIABAAIAAg3IgcAAIgCgDIgbAAIAAADIg5AAIAAhnIgaAAIAAAHIg5AAIAAgHIAdAAIAAgPIgdAAIAAgEIhAAAIAAAEIg5AAIAAg9IAxAAIAAAQICBAAIAAAtIAaAAIAAgtICyAAIAAAtIAdAAIAAAPIgdAAIAABiIgkAAIADAFIAZAAIAAA3IAFAAIAQAdgAt2geIA2AAIAAArIBAAAIAAgrIg2AAIAAgPIA2AAIAAgEIhAAAIAAAEIg2AAgAr9geIAaAAIAAgPIgaAAgAt5CdIAAgdIgaAAIAAAdIiyAAIAAgdIhrAAIAAAdIivAAIAAgdIgTAAIACAdIg5AAIgCgdIhFAAIgCAdIg5AAIADgdIgOAAIAAAdIiuAAIAAgdIghAAIAPAdIhAAAIgOgdIg2AAIAAg3IAbAAIgCgDIgbAAIAAADIg4AAIAAhnIA4AAIAAArIBBAAIAAgrIAdAAIAAgPIgdAAIAAgEIhBAAIAAAEIg4AAIAAgKIhkAAIAAgjIBkAAIAAgQICxAAIAAAQIAfAAIAAgQICuAAIAAAQIgEAAIAAAjIAEAAIAAAGIh1AAIAAAEIg5AAIAAgKIgfAAIAAAKIAdAAIAAAPIgdAAIAABiIgjAAIADAFIgeAAIAAAbIB1AAIAAAcIAOAAIAFg3IgrAAIAAhnIA5AAIAAAcIA8AAIAAA3Ig8AAIAAAUICIAAIAKhnIg1AAIAAgPIA3AAIABgKIBAAAIAAgjIg9AAIACgQICHAAIAGA9Ig3AAIgBgEIgjAAIgBAEIg1AAIAAAPIA0AAIgHBEIA1AAIgHhEIA4AAIAKBnIhFAAIAAAbIA/AAIAAgVIA4AAIAAAxIBrAAIAAg3IA5AAIAAAbIBAAAIAAgaIA5AAIAAA2IAaAAIAAg3Ig2AAIAAhnIA5AAIAABnIA2AAIAAA3IA4AAIgbg3IA7AAIAeA3IAgAAIAAAdgA4ICAIATAAIAAg3IgZAAgA6GCAIBFAAIgDgiIg/AAgA6ag3IAgAAIADgjIgjAAgA9ng3IAfAAIAAgjIgfAAgA+0CdIAAgdIgmAAIgbg3IA8AAIAeA3IAfAAIAAAdgEAgIACFIgHgPIBKAAIAAAPgAePCFIAAgPIA5AAIAAAPgAbACFIAAgPICxAAIAAAPgEghKACAIAAg3IA4AAIAAA3gAMwB2IAAhLICxAAIAAAiIh5AAIAAApgAJgB2IAAhLIA5AAIAAA5IBAAAIAAg5IA5AAIAABLgAIaB2IAAgtIgQAAIAAgeIA5AAIAABLgAGaBJIAAgeIA5AAIAAAegA1fBJIAAhnIgjAAIABgPIAiAAIAAg9IA4AAIAAA9IgjAAIgBAPIAkAAIAABngAOpANIAAgrIAdAAIAAgPIgdAAIAAgEIhBAAIAAAEIg4AAIAAg9ICxAAIAAA9IAdAAIAAAPIgdAAIAAArgAMwANIAAgrIA4AAIAAArgALZANIAAgrIAdAAIAAgPIgdAAIAAgEIhAAAIAAAEIgwAAIAAAPIAwAAIAAArIg5AAIAAgrIgdAAIAAArIg5AAIAAgrIAmAAIAAgPIgmAAIAAgEIg3AAIAAAEIAiAAIABAPIgjAAIAAArIg5AAIAAgrIAlAAIgCgPIgjAAIAAgEIgaAAIAAg5IDDAAIAAA9IAdAAIAAg9ICyAAIAAA9IAdAAIAAAPIgdAAIAAArgAEtANIgFgrIA4AAIACgPIA3AAIgBAPIg4AAIAFArgAcZgeIAAgPIA4AAIAAAPgANvgeIAAgPIA5AAIAAAPgAKggeIAAgPIA5AAIAAAPgAzJgeIAAgPIA4AAIAAAPgA0kgeIgBgPIA3AAIACAPgA6lgeIAAgPIA4AAIAAAPgAj3iMIAAgQIBOAAIAAAQgAm5iMIAAgQICyAAIAAAQgAp6iMIAAgQICyAAIAAAQgArDiMIAHgQIA/AAIgJAQgAtQiMIgIgQIA+AAIAIAQgAwPiMIAAgQICyAAIAAAQgAyqiMIAAgQICBAAIAAAQg");
	this.shape_20.setTransform(131.6,19.2);

	this.shape_21 = new cjs.Shape();
	this.shape_21.graphics.f("#FFFFFF").s().p("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");
	this.shape_21.setTransform(121.6,21.7);

	this.shape_22 = new cjs.Shape();
	this.shape_22.graphics.f("#FFFFFF").s().p("AAlCEIhJicIAACcIg4AAIAAjOIAAg5IA4AAIBJCcIAAicIA5AAIAAEHg");
	this.shape_22.setTransform(294.6,21.7);

	this.shape_23 = new cjs.Shape();
	this.shape_23.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_23.setTransform(273.3,21.7);

	this.shape_24 = new cjs.Shape();
	this.shape_24.graphics.f("#FFFFFF").s().p("AgxCEIAAg5IAWAAIAAiVIgWAAIAAg5IBkAAIAAA5IgXAAIAACVIAXAAIAAA5g");
	this.shape_24.setTransform(256.7,21.7);

	this.shape_25 = new cjs.Shape();
	this.shape_25.graphics.f("#FFFFFF").s().p("AgbCEIAAjOIg8AAIAAg5ICvAAIAAA5Ig8AAIAADOg");
	this.shape_25.setTransform(241.4,21.7);

	this.shape_26 = new cjs.Shape();
	this.shape_26.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAC3Ih3AAIAABQgAgfgCIA/AAIAAhIIg/AAg");
	this.shape_26.setTransform(222.1,21.7);

	this.shape_27 = new cjs.Shape();
	this.shape_27.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_27.setTransform(201.4,21.7);

	this.shape_28 = new cjs.Shape();
	this.shape_28.graphics.f("#FFFFFF").s().p("AhgCEIAAg5IAbAAIAAiVIgbAAIAAg5IDBAAIAAEHgAgNBLIA1AAIAAiVIg1AAg");
	this.shape_28.setTransform(179.8,21.7);

	this.shape_29 = new cjs.Shape();
	this.shape_29.graphics.f("#FFFFFF").s().p("AAkCEIgFg/Ig9AAIgGA/Ig4AAIAakHICFAAIAaEHgAgZANIAyAAIgIhXIgiAAg");
	this.shape_29.setTransform(159.4,21.7);

	this.shape_30 = new cjs.Shape();
	this.shape_30.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAEHgAgfBLIA/AAIAAiVIg/AAg");
	this.shape_30.setTransform(130.7,21.7);

	this.shape_31 = new cjs.Shape();
	this.shape_31.graphics.f("#FFFFFF").s().p("AgbCEIAAjOIg8AAIAAg5ICvAAIAAA5Ig8AAIAADOg");
	this.shape_31.setTransform(111.3,21.7);

	this.shape_32 = new cjs.Shape();
	this.shape_32.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAAC3Ih3AAIAABQgAgfgCIA/AAIAAhIIg/AAg");
	this.shape_32.setTransform(92,21.7);

	this.shape_33 = new cjs.Shape();
	this.shape_33.graphics.f("#FFFFFF").s().p("AgbCEIAAhtIgzhhIgeg5IA+AAIAuBjIAvhjIA+AAIhRCaIAABtg");
	this.shape_33.setTransform(71.8,21.7);

	this.shape_34 = new cjs.Shape();
	this.shape_34.graphics.f("#FFFFFF").s().p("AAfCEIgphXIgbAAIAABXIg5AAIAAkHICwAAIAACuIgkAAIAxBZgAglgKIA+AAIAAhAIg+AAg");
	this.shape_34.setTransform(52.1,21.7);

	this.shape_35 = new cjs.Shape();
	this.shape_35.graphics.f("#FFFFFF").s().p("AhXCEIAAkHICvAAIAABTIg4AAIAAgaIg/AAIAACVIA/AAIAAgaIA4AAIAABTg");
	this.shape_35.setTransform(31.1,21.7);

	this.shape_36 = new cjs.Shape();
	this.shape_36.graphics.f("#FFFFFF").s().p("AhWCEIAAjOIAAg5IA4AAIAADOIA9AAIAAgVIA4AAIAABOg");
	this.shape_36.setTransform(2.7,21.7);

	this.shape_37 = new cjs.Shape();
	this.shape_37.graphics.f("#FFFFFF").s().p("AAkCEIgGg/Ig8AAIgGA/Ig4AAIAZkHICGAAIAaEHgAgZANIAyAAIgIhXIghAAg");
	this.shape_37.setTransform(-17,21.7);

	this.shape_38 = new cjs.Shape();
	this.shape_38.graphics.f("#FFFFFF").s().p("AhWCEIAAkHICsAAIAAA5IhzAAIAAAvIA6AAIAAA3Ig6AAIAAAvIBzAAIAAA5g");
	this.shape_38.setTransform(-36.2,21.7);

	this.shape_39 = new cjs.Shape();
	this.shape_39.graphics.f("#FFFFFF").s().p("AAfCEIgphXIgbAAIAABXIg5AAIAAkHICwAAIAACuIgkAAIAxBZgAglgKIA+AAIAAhAIg+AAg");
	this.shape_39.setTransform(-56.2,21.7);

	this.shape_40 = new cjs.Shape();
	this.shape_40.graphics.f("#FFFFFF").s().p("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");
	this.shape_40.setTransform(124.1,21.7);

	this.shape_41 = new cjs.Shape();
	this.shape_41.graphics.f("#FFFFFF").s().p("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");
	this.shape_41.setTransform(119.1,21.7);

	this.instance_2 = new lib.Tween3("synched",0);
	this.instance_2.parent = this;
	this.instance_2.setTransform(119.1,20.3);
	this.instance_2._off = true;

	this.instance_3 = new lib.Tween4("synched",0);
	this.instance_3.parent = this;
	this.instance_3.setTransform(119.2,29.7,0.381,0.381,0,0,0,0.1,0.1);
	this.instance_3.alpha = 0;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[]}).to({state:[{t:this.shape_18}]},74).to({state:[{t:this.shape_19}]},2).to({state:[{t:this.shape_20}]},2).to({state:[{t:this.shape_19}]},2).to({state:[{t:this.shape_21}]},2).to({state:[{t:this.shape_39},{t:this.shape_38},{t:this.shape_37},{t:this.shape_36},{t:this.shape_35},{t:this.shape_34},{t:this.shape_33},{t:this.shape_32},{t:this.shape_31},{t:this.shape_30},{t:this.shape_29},{t:this.shape_28},{t:this.shape_27},{t:this.shape_26},{t:this.shape_25},{t:this.shape_24},{t:this.shape_23},{t:this.shape_22}]},2).to({state:[{t:this.shape_40}]},32).to({state:[{t:this.shape_41}]},2).to({state:[{t:this.shape_39},{t:this.shape_38},{t:this.shape_37},{t:this.shape_36},{t:this.shape_35},{t:this.shape_34},{t:this.shape_33},{t:this.shape_32},{t:this.shape_31},{t:this.shape_30},{t:this.shape_29},{t:this.shape_28},{t:this.shape_27},{t:this.shape_26},{t:this.shape_25},{t:this.shape_24},{t:this.shape_23},{t:this.shape_22}]},3).to({state:[{t:this.instance_2}]},47).to({state:[{t:this.instance_3}]},7).to({state:[]},1).wait(3));
	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(168).to({_off:false},0).to({_off:true,regX:0.1,regY:0.1,scaleX:0.38,scaleY:0.38,x:119.2,y:29.7,alpha:0},7,cjs.Ease.get(-1)).wait(4));

	// Символ 1
	this.instance_4 = new lib.Символ1("synched",0);
	this.instance_4.parent = this;
	this.instance_4.setTransform(119.2,-46.7,1,1,0,0,0,123.8,23.6);

	this.shape_42 = new cjs.Shape();
	this.shape_42.graphics.f("#FFFFFF").s().p("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");
	this.shape_42.setTransform(114.5,28.3);

	this.shape_43 = new cjs.Shape();
	this.shape_43.graphics.f("#FFFFFF").s().p("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");
	this.shape_43.setTransform(116.3,28.3);

	this.shape_44 = new cjs.Shape();
	this.shape_44.graphics.f("#FFFFFF").s().p("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");
	this.shape_44.setTransform(117,28.3);

	this.shape_45 = new cjs.Shape();
	this.shape_45.graphics.f("#FFFFFF").s().p("EAkPACTIAAklIDAAAIAAA/IiBAAIAAA1IBBAAIAAA9IhBAAIAAA1ICBAAIAAA/gEAiyACTIgwhhIgdAAIAABhIhAAAIAAklIDGAAIAADCIgoAAIA2BjgEAhlgALIBHAAIAAhIIhHAAgAc+CTIAAklIDFAAIAAElgAd9BUIBHAAIAAinIhHAAgAaqCTIAAjmIhEAAIAAg/IDFAAIAAA/IhCAAIAADmgAWZCTIAAg/IB+AAIAAg2Ih+AAIAAiwIC9AAIAAA/Ih/AAIAAA0IB/AAIAACygARdCTIAAklIDBAAIAAA/IiCAAIAAA1IBCAAIAAA9IhCAAIAAA1ICCAAIAAA/gAOFCTIAAklIA/AAIAADmIBFAAIAAgYIA/AAIAABXgAL3CTIAAg/IAYAAIAAinIgYAAIAAg/IBxAAIAAA/IgaAAIAACnIAaAAIAAA/gAITCTIAAklIDGAAIAAB2IglAcIAlAdIAAB2gAJSBUIBIAAIAAgiIgXgVIgxAAgAJSgcIAxAAIAXgVIAAgiIhIAAgAEsCTIAAklIDGAAIAAElgAFrBUIBIAAIAAinIhIAAgADLCTIAAi9Ig8BnIg8hnIAAC9Ig/AAIAAklIA/AAIA8BsIA8hsIA/AAIAAElgAkzCTIAAklIDFAAIAAElgAj0BUIBHAAIAAinIhHAAgAnICTIAAjmIhDAAIAAg/IDFAAIAAA/IhDAAIAADmgAriCTIAAklIDGAAIAADNIiHAAIAABYgAqjgDIBIAAIAAhQIhIAAgAt/CTIAAh6IhbirIBFAAIA1BtIA1htIBFAAIhaCrIAAB6gAwaCTIgvhhIgdAAIAABhIg/AAIAAklIDFAAIAADCIgoAAIA2BjgAxmgLIBHAAIAAhIIhHAAgA2ICTIAAklIDFAAIAABcIg/AAIAAgdIhHAAIAACnIBHAAIAAgdIA/AAIAABcgA5/CTIAAjmIhDAAIAAg/IDFAAIAAA/IhDAAIAADmgA+QCTIAAg/IB+AAIAAg2Ih+AAIAAiwIC9AAIAAA/Ih+AAIAAA0IB+AAIAACygA/qCTIgwhhIgeAAIAABhIg/AAIAAklIDGAAIAADCIgoAAIA2BjgEgg4gALIBIAAIAAhIIhIAAgEgkFACTIAAg/IAZAAIAAinIgZAAIAAg/IBxAAIAAA/IgZAAIAACnIAZAAIAAA/gEgnPACTIAAklIC3AAIAAA/Ih4AAIAAA/IBbAAIAAA9IhbAAIAABqg");
	this.shape_45.setTransform(119.5,28.3);

	this.shape_46 = new cjs.Shape();
	this.shape_46.graphics.f("#FFFFFF").s().p("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");
	this.shape_46.setTransform(113,28.3);

	this.shape_47 = new cjs.Shape();
	this.shape_47.graphics.f("#FFFFFF").s().p("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");
	this.shape_47.setTransform(89.5,25.8);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance_4}]}).to({state:[{t:this.instance_4}]},9).to({state:[{t:this.instance_4}]},3).to({state:[{t:this.shape_42}]},33).to({state:[{t:this.shape_43}]},2).to({state:[{t:this.instance_4}]},2).to({state:[{t:this.shape_44}]},15).to({state:[{t:this.shape_45}]},2).to({state:[{t:this.shape_46}]},2).to({state:[{t:this.shape_45}]},2).to({state:[{t:this.shape_47}]},2).to({state:[]},2).wait(105));
	this.timeline.addTween(cjs.Tween.get(this.instance_4).to({y:29.9},9,cjs.Ease.get(1)).to({y:26.8},3).to({_off:true},33).wait(4).to({_off:false},0).to({_off:true},15).wait(115));

	// Layer 2
	this.instance_5 = new lib.Символ3("synched",0);
	this.instance_5.parent = this;
	this.instance_5.setTransform(120.2,74.8,1,1,0,0,0,128.3,12.3);
	this.instance_5.alpha = 0;
	this.instance_5._off = true;

	this.shape_48 = new cjs.Shape();
	this.shape_48.graphics.f("#FFD100").s().p("ATJA2IAAgYIAzAAIAAgTIgzAAIAAhAIBPAAIAAAXIgyAAIAAATIAyAAIAABBgARxA2IAAhrIBMAAIAAAaIgvAAIAAAPIAXAAIAAAYIgXAAIAAAQIAvAAIAAAagARHA2IAAhBIgXAoIgWgoIAABBIgdAAIAAhrIAdAAIAWArIAXgrIAcAAIAABrgAPYA2IgCgXIgZAAIgDAXIgbAAIANhrIA7AAIANBrgAPAACIATAAIgDgbIgNAAgANGA2IAAhrIBQAAIAAAcIg0AAIAAA1IAYAAIAAgZIAcAAIAAAzgAK/A2IAAhrIBLAAIAAAaIgvAAIAAAPIAYAAIAAAYIgYAAIAAAQIAvAAIAAAagAJoA2IAAhMIAdAAIAAAyIAwAAIAAAagAI9A2IAAhMIAcAAIAABMgAHdA2IAAhMIgFAAIAAgdIAFAAIAAgCIBQAAIAAACIgNAAIAAAdIANAAIAAANIgNAJIANAKIAAAsgAH4AeIAZAAIAAgLIgHgJIgSAAgAH1gWIADAAIAAANIASAAIAHgIIAAgFIgNAAIAAgdIgPAAgAF7A2IAAhMIgCAAIAAgdIACAAIAAgCIBRAAIAAACIgDAAIAAAdIADAAIAABMgAGVgWIADAAIAAAxIAYAAIAAgxIgDAAIAAgIIgYAAgAFQA2IAAhBIgWAoIgXgoIAABBIgcAAIAAhMIgrAAIAPgdIAcAAIAAgCIAcAAIABACIArAAIABgCIAdAAIAAACIgFAAIAAAdIAFAAIAABMgAE0gWIAAAAIAGAMIAGgMIAMAAIAAgDIgYAAgAEJgWIAOAAIAAgdIgOAAgACDA2IAAhMIAdAAIAAAOIAgAAIAAAXIggAAIAAAngAAiA2IAAhMIgDAAIAAgdIADAAIAAgCIBRAAIAAACIgKAAIAAAYIgtAAIAAAFIADAAIAAAxIAXAAIAAgxIAdAAIAABMgAhpA2IAAgYIAyAAIAAgTIgyAAIAAghIAcAAIAAALIAzAAIAABBgAjTA2IAAgbIANAAIAAgxIgHAAIAAgdIgGAAIAAgCIBaAAIAAACIgFAAIAAAVIgzAAIAAAIIAHAAIAAAxIAUAAIAAgxIAdAAIAABMgAkpA2IAAhMIgBAAIAAgDIgNAAIAAgaIAOAAIAAgCIBLAAIAAACIABAAIAAAdIgdAAIAAgDIgUAAIAAADIACAAIAAAKIAWAAIAAAYIgWAAIAAAQIAuAAIAAAagAlVA2IgRghIgIAAIAAAhIgcAAIAAhMIgDAAIAAgdIADAAIAAgCIBQAAIAAACIgIAAIAAAYIguAAIAAAFIACAAIAAATIAYAAIAAgTIAcAAIAAApIgPAAIATAjgAn0A2IAAgbIAMAAIAAgxIgGAAIAAgdIgGAAIAAgCIBZAAIAAACIgDAAIAAAdIADAAIAABMgAnRgWIAGAAIAAAxIAUAAIAAgxIgDAAIAAgFIgXAAgAogA2Igeg+IAAA+IgcAAIAAhMIAOAAIAAgDIgMAAIAAgaIgCAAIAAgCIAcAAIABACIAdAAIAAgCIAeAAIAAACIAEAAIAAAdIgEAAIAABMgAovgWIAAAAIAPAfIAAgfIAFAAIAAgDIgUAAgAq7A2IAAhMIgDAAIAAgdIADAAIAAgCIAdAAIAAACIgCAAIAOAdIgMAAIAAAxIAXAAIAAgxIADAAIAAgdIgDAAIAAgCIAcAAIAAACIAEAAIAAAdIgEAAIAABMgAroA2IAAgpIgXAAIAAApIgdAAIAAhMIgDAAIAAgdIADAAIAAgCIAdAAIAAACIgDAAIAAAdIADAAIAAAJIAXAAIAAgJIgDAAIAAgdIADAAIAAgCIAdAAIAAACIgDAAIAAAdIADAAIAABMgAt3A2IAAgpIgXAAIAAApIgdAAIAAhMIAdAAIAAAJIAXAAIAAgJIgJAAIAAgdIAJAAIAAgCIAcAAIAAACIgIAAIAAAdIAIAAIAABMgAvrA2IAAhMIAQAAIAAgdIgYAAIAAAdIgbAAIAABMIgcAAIAAhMIAbAAIAAgdIgJAAIAAAaIgaAAIAAADIgHAAIAABMIh6AAIAAhMIgHAAIAAgdIAHAAIAAgCIAcAAIAAACIgGAAIAAAdIAGAAIAAAxIASAAIAAgxIgJAAIAAgdIAJAAIAAgCIAdAAIAAACIASAAIAAgCIAdAAIAAACIAPAAIAAgCIAcAAIAAACIAJAAIAAgCIBRAAIAAACIgLAAIAAAdIgPAAIAABMgAxygWIAKAAIAAAxIASAAIAAgxIAHAAIAAgDIgaAAIAAgaIgJAAgACjgWIAAgdIggAAIAAgCIBKAAIAAACIgNAAIAQAdgAgMgWIAAgDIgXAAIAAADIgdAAIAAgdIgpAAIAAgCIBPAAIAAACIApAAIAAAdgAtLgWIAAgdIAcAAIAAAdgAzpgWIAAgdIAdAAIAAAdgA0XgWIAAgdIAcAAIAAAdgAJogzIAAgCIAdAAIAAACgAI9gzIAAgCIAcAAIAAACgAurgzIAAgCIAdAAIAAACg");
	this.shape_48.setTransform(115.1,59.3);

	this.shape_49 = new cjs.Shape();
	this.shape_49.graphics.f("#FFD100").s().p("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");
	this.shape_49.setTransform(127.6,59.3);

	this.shape_50 = new cjs.Shape();
	this.shape_50.graphics.f("#FFD100").s().p("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");
	this.shape_50.setTransform(120.1,59.3);

	this.shape_51 = new cjs.Shape();
	this.shape_51.graphics.f("#FFD100").s().p("ASXA2IAAgYIAzAAIAAgTIgzAAIAAhAIBPAAIAAAXIgyAAIAAATIAyAAIAABBgAQ/A2IAAhrIBMAAIAAAaIgvAAIAAAPIAXAAIAAAYIgXAAIAAAQIAvAAIAAAagAQVA2IAAhBIgXAoIgWgoIAABBIgdAAIAAhrIAdAAIAWArIAXgrIAcAAIAABrgAOmA2IgCgXIgZAAIgDAXIgbAAIANhrIA7AAIANBrgAOOACIATAAIgDgbIgNAAgAMUA2IAAhrIBQAAIAAAcIg0AAIAAA1IAYAAIAAgZIAcAAIAAAzgAKNA2IAAhrIBLAAIAAAaIgvAAIAAAPIAYAAIAAAYIgYAAIAAAQIAvAAIAAAagAI2A2IAAhrIAdAAIAABRIAwAAIAAAagAILA2IAAhrIAcAAIAABrgAGrA2IAAhrIBQAAIAAAsIgNAJIANAKIAAAsgAHGAeIAZAAIAAgLIgHgJIgSAAgAHGgJIASAAIAHgIIAAgNIgZAAgAFJA2IAAhrIBRAAIAABrgAFmAbIAYAAIAAg0IgYAAgAEeA2IAAhBIgWAoIgXgoIAABBIgcAAIAAhrIAcAAIAXArIAWgrIAdAAIAABrgABRA2IAAhrIBKAAIAAAaIgtAAIAAATIAgAAIAAAXIggAAIAAAngAgOA2IAAhrIBPAAIAABrgAANAbIAXAAIAAg0IgXAAgAibA2IAAgYIAyAAIAAgTIgyAAIAAhAIBPAAIAAAXIgzAAIAAATIAzAAIAABBgAkFA2IAAgbIANAAIAAg0IgNAAIAAgcIBaAAIAABrgAjcAbIAUAAIAAg0IgUAAgAlbA2IAAhrIBLAAIAAAaIguAAIAAAPIAWAAIAAAYIgWAAIAAAQIAuAAIAAAagAmHA2IgRghIgIAAIAAAhIgcAAIAAhrIBQAAIAABIIgPAAIATAjgAmggDIAYAAIAAgYIgYAAgAomA2IAAgbIAMAAIAAg0IgMAAIAAgcIBZAAIAABrgAn9AbIAUAAIAAg0IgUAAgApSA2Igeg+IAAA+IgcAAIAAhrIAcAAIAeA+IAAg+IAeAAIAABrgArtA2IAAhrIAdAAIAABQIAXAAIAAhQIAcAAIAABrgAsaA2IAAgpIgXAAIAAApIgdAAIAAhrIAdAAIAAAoIAXAAIAAgoIAdAAIAABrgAupA2IAAgpIgXAAIAAApIgdAAIAAhrIAdAAIAAAoIAXAAIAAgoIAcAAIAABrgAwdA2IAAhPIgaAAIAAgcIBRAAIAAAcIgaAAIAABPgAxcA2IAAhrIAcAAIAABrgAzlA2IAAhrIAcAAIAABQIASAAIAAhQIAdAAIAABQIASAAIAAhQIAdAAIAABrg");
	this.shape_51.setTransform(120.1,59.3);

	this.shape_52 = new cjs.Shape();
	this.shape_52.graphics.f("#FFD100").s().p("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");
	this.shape_52.setTransform(107.6,59.3);

	this.shape_53 = new cjs.Shape();
	this.shape_53.graphics.f("#FFD100").s().p("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");
	this.shape_53.setTransform(92.6,59.3);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[]}).to({state:[{t:this.instance_5}]},15).to({state:[{t:this.instance_5}]},9).to({state:[{t:this.instance_5}]},4).to({state:[{t:this.shape_48}]},17).to({state:[{t:this.shape_49}]},2).to({state:[{t:this.instance_5}]},2).to({state:[{t:this.shape_50}]},15).to({state:[{t:this.shape_51}]},2).to({state:[{t:this.shape_52}]},2).to({state:[{t:this.shape_51}]},2).to({state:[{t:this.shape_53}]},2).to({state:[]},2).wait(105));
	this.timeline.addTween(cjs.Tween.get(this.instance_5).wait(15).to({_off:false},0).to({y:106.1,alpha:1},9,cjs.Ease.get(1)).to({y:102.3},4).to({_off:true},17).wait(4).to({_off:false},0).to({_off:true},15).wait(115));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-135.5,-70.3,509.3,47.2);


// stage content:



(lib._728x90_mobilego_001 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 5
	this.instance = new lib.Символ10("synched",0,false);
	this.instance.parent = this;
	this.instance.setTransform(120,56.8,0.582,0.582,0,0,0,95.6,61.1);
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(174).to({_off:false},0).wait(105).to({mode:"single",startPosition:45},0).to({alpha:0},9).wait(1));

	// Слой 1
	this.instance_1 = new lib.Символ13("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(663.2,166.7,1.622,1.622,0,0,0,40.1,30.6);
	this.instance_1._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).wait(204).to({_off:false},0).to({regX:40,regY:30.5,scaleX:1,scaleY:1,x:622,y:93.5},10,cjs.Ease.get(1)).wait(12).to({startPosition:0},0).to({regY:30.6,scaleX:0.85,scaleY:0.85,x:614.8,y:85.3},3).to({regY:30.5,scaleX:1,scaleY:1,x:622,y:93.5},3).wait(44).to({startPosition:0},0).to({regX:40.1,regY:30.6,rotation:-30,x:506.7,y:154.3},12,cjs.Ease.get(-1)).wait(1));

	// Layer 4
	this.instance_2 = new lib.Symbol5("synched",0,false);
	this.instance_2.parent = this;
	this.instance_2.setTransform(530.5,46.1,0.863,0.863,0,0,0,0.5,-0.1);
	this.instance_2._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(197).to({_off:false},0).wait(29).to({startPosition:29},0).to({regY:-0.2,scaleX:0.78,scaleY:0.78,y:46,startPosition:32},3).to({regY:-0.1,scaleX:0.86,scaleY:0.86,y:46.1,startPosition:35},3).wait(47).to({startPosition:69},0).to({scaleX:0.52,scaleY:0.52,x:530.4,alpha:0,startPosition:76},9,cjs.Ease.get(-1)).wait(1));

	// Слой 2
	this.instance_3 = new lib.Symbol7();
	this.instance_3.parent = this;
	this.instance_3.setTransform(530.2,46.1,1,1,0,0,0,103.3,20.4);
	this.instance_3.alpha = 0;
	this.instance_3.compositeOperation = "lighter";
	this.instance_3._off = true;
	this.instance_3.filters = [new cjs.BlurFilter(40, 40, 3)];
	this.instance_3.cache(-11,-2,229,45);

	this.timeline.addTween(cjs.Tween.get(this.instance_3).wait(226).to({_off:false},0).to({alpha:1},6).wait(47).to({scaleX:0.51,scaleY:0.51,y:46.2,alpha:0},9).wait(1));

	// Layer 3
	this.instance_4 = new lib.Symbol3("synched",0,false);
	this.instance_4.parent = this;
	this.instance_4.setTransform(364.2,49.4,1,1,0,0,-3,119.3,39.3);

	this.timeline.addTween(cjs.Tween.get(this.instance_4).to({_off:true},177).wait(112));

	// Layer 1
	this.instance_5 = new lib.Symbol1copy();
	this.instance_5.parent = this;
	this.instance_5.setTransform(71.5,224.8,0.146,0.146,-55.8,0,0,49.7,51.8);
	this.instance_5.alpha = 0.309;

	this.instance_6 = new lib.Symbol1copy();
	this.instance_6.parent = this;
	this.instance_6.setTransform(300.5,224.8,0.146,0.146,-55.8,0,0,49.7,51.8);
	this.instance_6.alpha = 0.309;

	this.instance_7 = new lib.Symbol1copy();
	this.instance_7.parent = this;
	this.instance_7.setTransform(348,220.4,0.146,0.146,-55.8,0,0,49.9,52);
	this.instance_7.alpha = 0.309;

	this.instance_8 = new lib.Symbol1copy();
	this.instance_8.parent = this;
	this.instance_8.setTransform(222.9,199.7,0.146,0.146,-55.8,0,0,49.7,51.8);
	this.instance_8.alpha = 0.309;

	this.instance_9 = new lib.Symbol1();
	this.instance_9.parent = this;
	this.instance_9.setTransform(342.3,260.5,0.213,0.213,-55.8,0,0,50.1,51.9);
	this.instance_9.alpha = 0.488;

	this.instance_10 = new lib.Symbol1copy();
	this.instance_10.parent = this;
	this.instance_10.setTransform(326.3,214.1,0.213,0.213,-55.8,0,0,50.4,52);
	this.instance_10.alpha = 0.488;

	this.instance_11 = new lib.Symbol1copy2();
	this.instance_11.parent = this;
	this.instance_11.setTransform(284.5,223.4,0.266,0.266,-55.8,0,0,50.6,51.4);
	this.instance_11.alpha = 0.641;

	this.instance_12 = new lib.Symbol1();
	this.instance_12.parent = this;
	this.instance_12.setTransform(477.9,404.1,0.36,0.36,-55.8,0,0,50.6,51.4);

	this.instance_13 = new lib.Symbol1copy();
	this.instance_13.parent = this;
	this.instance_13.setTransform(370.1,350.2,0.351,0.351,-55.8,0,0,50.5,51.5);

	this.instance_14 = new lib.Symbol1copy();
	this.instance_14.parent = this;
	this.instance_14.setTransform(319.6,491.9,0.412,0.412,-55.8,0,0,50.6,51.6);

	this.instance_15 = new lib.Symbol1();
	this.instance_15.parent = this;
	this.instance_15.setTransform(377.1,374,0.36,0.36,-55.8,0,0,50.6,51.3);

	this.instance_16 = new lib.Symbol1();
	this.instance_16.parent = this;
	this.instance_16.setTransform(403.6,376.4,0.36,0.36,-55.8,0,0,50.6,51.3);

	this.instance_17 = new lib.Symbol1();
	this.instance_17.parent = this;
	this.instance_17.setTransform(-318.3,432.4,0.635,0.635,-55.8,0,0,50.5,51.4);

	this.instance_18 = new lib.Symbol1();
	this.instance_18.parent = this;
	this.instance_18.setTransform(438.1,496.6,0.635,0.635,-55.8,0,0,50.5,51.4);

	this.instance_19 = new lib.Symbol1();
	this.instance_19.parent = this;
	this.instance_19.setTransform(-123.1,186.4,0.635,0.635,-55.8,0,0,50.6,51.4);

	this.instance_20 = new lib.Symbol1();
	this.instance_20.parent = this;
	this.instance_20.setTransform(372.2,487.2,0.635,0.635,-55.8,0,0,50.6,51.5);

	this.instance_21 = new lib.Symbol1();
	this.instance_21.parent = this;
	this.instance_21.setTransform(-285.3,244.1,0.635,0.635,-55.8,0,0,50.6,51.4);

	this.instance_22 = new lib.Symbol1copy();
	this.instance_22.parent = this;
	this.instance_22.setTransform(438.1,328.5,0.295,0.295,-55.8,0,0,50.7,51.8);

	this.instance_23 = new lib.Symbol1copy2();
	this.instance_23.parent = this;
	this.instance_23.setTransform(326.5,279.1,0.351,0.351,-55.8,0,0,50.5,51.5);

	this.instance_24 = new lib.Symbol1copy2();
	this.instance_24.parent = this;
	this.instance_24.setTransform(123.1,167.5,0.351,0.351,-55.8,0,0,50.1,51.5);

	this.instance_25 = new lib.Symbol1copy();
	this.instance_25.parent = this;
	this.instance_25.setTransform(305.9,315.8,0.351,0.351,-55.8,0,0,50.5,51.5);

	this.instance_26 = new lib.Symbol1copy();
	this.instance_26.parent = this;
	this.instance_26.setTransform(356.4,350.5,0.412,0.412,-55.8,0,0,50.6,51.6);

	this.instance_27 = new lib.Symbol1();
	this.instance_27.parent = this;
	this.instance_27.setTransform(27.7,135.9,0.388,0.388,-55.8,0,0,50.4,51.4);

	this.instance_28 = new lib.Symbol1();
	this.instance_28.parent = this;
	this.instance_28.setTransform(514.3,356.5,0.36,0.36,-55.8,0,0,50.5,51.3);

	this.instance_29 = new lib.Symbol1copy();
	this.instance_29.parent = this;
	this.instance_29.setTransform(399.3,341.3,0.412,0.412,-55.8,0,0,50.6,51.6);

	this.instance_30 = new lib.Symbol1();
	this.instance_30.parent = this;
	this.instance_30.setTransform(95.6,159.1,0.36,0.36,-55.8,0,0,50.7,51.1);

	this.instance_31 = new lib.Symbol1copy();
	this.instance_31.parent = this;
	this.instance_31.setTransform(-5.6,100.2,0.412,0.412,-55.8,0,0,50.7,51.3);

	this.instance_32 = new lib.Symbol1copy();
	this.instance_32.parent = this;
	this.instance_32.setTransform(524.5,318.2,0.351,0.351,-55.8,0,0,50.5,51.5);

	this.instance_33 = new lib.Symbol1copy();
	this.instance_33.parent = this;
	this.instance_33.setTransform(79.3,191.8,0.312,0.312,-55.8,0,0,50.3,51.3);
	this.instance_33.alpha = 0.719;

	this.instance_34 = new lib.Symbol1();
	this.instance_34.parent = this;
	this.instance_34.setTransform(474.3,338.9,0.412,0.412,-55.8,0,0,50.9,51.5);

	this.instance_35 = new lib.Symbol1();
	this.instance_35.parent = this;
	this.instance_35.setTransform(112,116.3,0.36,0.36,-55.8,0,0,50.5,51.1);

	this.instance_36 = new lib.Symbol1copy();
	this.instance_36.parent = this;
	this.instance_36.setTransform(8.5,147.1,0.412,0.412,-55.8,0,0,50.7,51.5);

	this.instance_37 = new lib.Symbol1copy();
	this.instance_37.parent = this;
	this.instance_37.setTransform(43.5,107,0.266,0.266,-55.8,0,0,50.5,51.1);
	this.instance_37.alpha = 0.641;

	this.instance_38 = new lib.Symbol1copy2();
	this.instance_38.parent = this;
	this.instance_38.setTransform(205.7,202.6,0.266,0.266,-55.8,0,0,50.6,51.4);
	this.instance_38.alpha = 0.641;

	this.instance_39 = new lib.Symbol1();
	this.instance_39.parent = this;
	this.instance_39.setTransform(302.2,600.2,0.635,0.635,-55.8,0,0,50.6,51.5);

	this.instance_40 = new lib.Symbol1();
	this.instance_40.parent = this;
	this.instance_40.setTransform(-239.3,243.6,0.635,0.635,-55.8,0,0,50.6,51.5);

	this.instance_41 = new lib.Symbol1();
	this.instance_41.parent = this;
	this.instance_41.setTransform(395,336.7,0.635,0.635,-55.8,0,0,50.6,51.4);

	this.instance_42 = new lib.Symbol1();
	this.instance_42.parent = this;
	this.instance_42.setTransform(-228,254.7,0.635,0.635,-55.8,0,0,50.6,51.4);

	this.instance_43 = new lib.Symbol1();
	this.instance_43.parent = this;
	this.instance_43.setTransform(393.6,456.2,0.635,0.635,-55.8,0,0,50.6,51.5);

	this.instance_44 = new lib.Symbol1copy();
	this.instance_44.parent = this;
	this.instance_44.setTransform(72.5,127.8,0.295,0.295,-55.8,0,0,50.6,51.6);

	this.instance_45 = new lib.Symbol1copy2();
	this.instance_45.parent = this;
	this.instance_45.setTransform(95.9,146.2,0.351,0.351,-55.8,0,0,50.3,51.4);

	this.instance_46 = new lib.Symbol1copy2();
	this.instance_46.parent = this;
	this.instance_46.setTransform(427,320.6,0.351,0.351,-55.8,0,0,50.4,51.6);

	this.instance_47 = new lib.Symbol1copy2();
	this.instance_47.parent = this;
	this.instance_47.setTransform(415.3,396.8,0.351,0.351,-55.8,0,0,50.5,51.5);

	this.instance_48 = new lib.Symbol1copy();
	this.instance_48.parent = this;
	this.instance_48.setTransform(407.1,421.2,0.412,0.412,-55.8,0,0,50.8,51.6);

	this.instance_49 = new lib.Symbol1();
	this.instance_49.parent = this;
	this.instance_49.setTransform(452.7,279.5,0.388,0.388,-55.8,0,0,50.6,51.5);

	this.instance_50 = new lib.Symbol1();
	this.instance_50.parent = this;
	this.instance_50.setTransform(46.9,138.8,0.36,0.36,-55.8,0,0,50.4,51.1);

	this.instance_51 = new lib.Symbol1copy();
	this.instance_51.parent = this;
	this.instance_51.setTransform(-29.5,133.9,0.412,0.412,-55.8,0,0,50.5,51.1);

	this.instance_52 = new lib.Symbol1();
	this.instance_52.parent = this;
	this.instance_52.setTransform(432.9,331.7,0.36,0.36,-55.8,0,0,50.6,51.2);

	this.instance_53 = new lib.Symbol1copy();
	this.instance_53.parent = this;
	this.instance_53.setTransform(393.3,321.1,0.412,0.412,-55.8,0,0,50.8,51.4);

	this.instance_54 = new lib.Symbol1copy2();
	this.instance_54.parent = this;
	this.instance_54.setTransform(1.6,96.7,0.351,0.351,-55.8,0,0,50.3,51.4);

	this.instance_55 = new lib.Symbol1();
	this.instance_55.parent = this;
	this.instance_55.setTransform(-31.7,110.9,0.412,0.412,-55.8,0,0,50.8,51.4);

	this.instance_56 = new lib.Symbol1();
	this.instance_56.parent = this;
	this.instance_56.setTransform(420.8,323.7,0.36,0.36,-55.8,0,0,50.6,51.2);

	this.instance_57 = new lib.Symbol1copy();
	this.instance_57.parent = this;
	this.instance_57.setTransform(366.5,283.7,0.412,0.412,-55.8,0,0,50.6,51.4);

	this.instance_58 = new lib.Symbol1copy();
	this.instance_58.parent = this;
	this.instance_58.setTransform(269.9,282.7,0.146,0.146,-55.8,0,0,49.9,52);
	this.instance_58.alpha = 0.309;

	this.instance_59 = new lib.Symbol1();
	this.instance_59.parent = this;
	this.instance_59.setTransform(333.9,338.7,0.213,0.213,-55.8,0,0,50.1,52.1);
	this.instance_59.alpha = 0.488;

	this.instance_60 = new lib.Symbol1copy();
	this.instance_60.parent = this;
	this.instance_60.setTransform(90.9,124.9,0.213,0.213,-55.8,0,0,50.4,52);
	this.instance_60.alpha = 0.488;

	this.instance_61 = new lib.Symbol1();
	this.instance_61.parent = this;
	this.instance_61.setTransform(210.6,219.3,0.213,0.213,-55.8,0,0,50.4,52);
	this.instance_61.alpha = 0.488;

	this.instance_62 = new lib.Symbol1copy();
	this.instance_62.parent = this;
	this.instance_62.setTransform(78.1,170.6,0.312,0.312,-55.8,0,0,50.4,51.4);
	this.instance_62.alpha = 0.719;

	this.instance_63 = new lib.Symbol1copy2();
	this.instance_63.parent = this;
	this.instance_63.setTransform(139.1,164.1,0.146,0.146,-55.8,0,0,50.4,52.1);
	this.instance_63.alpha = 0.172;

	this.instance_64 = new lib.Symbol1();
	this.instance_64.parent = this;
	this.instance_64.setTransform(115.3,173.7,0.146,0.146,-55.8,0,0,50.1,51.9);
	this.instance_64.alpha = 0.309;

	this.instance_65 = new lib.Symbol1copy2();
	this.instance_65.parent = this;
	this.instance_65.setTransform(146.4,192.1,0.146,0.146,-55.8,0,0,50.1,52.3);
	this.instance_65.alpha = 0.16;

	this.instance_66 = new lib.Symbol1copy();
	this.instance_66.parent = this;
	this.instance_66.setTransform(169.5,224.8,0.146,0.146,-55.8,0,0,49.7,51.8);
	this.instance_66.alpha = 0.309;

	this.instance_67 = new lib.Symbol1();
	this.instance_67.parent = this;
	this.instance_67.setTransform(272.2,291.1,0.213,0.213,-55.8,0,0,50.1,51.9);
	this.instance_67.alpha = 0.488;

	this.instance_68 = new lib.Symbol1();
	this.instance_68.parent = this;
	this.instance_68.setTransform(135.7,171,0.266,0.266,-55.8,0,0,50.5,51.1);
	this.instance_68.alpha = 0.641;

	this.instance_69 = new lib.Symbol1copy();
	this.instance_69.parent = this;
	this.instance_69.setTransform(263.8,269.6,0.213,0.213,-55.8,0,0,50.4,52);
	this.instance_69.alpha = 0.488;

	this.instance_70 = new lib.Symbol1copy2();
	this.instance_70.parent = this;
	this.instance_70.setTransform(433.6,304,0.266,0.266,-55.8,0,0,50.6,51.4);
	this.instance_70.alpha = 0.641;

	this.instance_71 = new lib.Symbol1();
	this.instance_71.parent = this;
	this.instance_71.setTransform(118.5,146.7,0.213,0.213,-55.8,0,0,50.4,52);
	this.instance_71.alpha = 0.488;

	this.instance_72 = new lib.Symbol1();
	this.instance_72.parent = this;
	this.instance_72.setTransform(238.9,247.5,0.213,0.213,-55.8,0,0,50.4,52);
	this.instance_72.alpha = 0.488;

	this.instance_73 = new lib.Symbol1copy();
	this.instance_73.parent = this;
	this.instance_73.setTransform(393.2,267.7,0.266,0.266,-55.8,0,0,50.6,51.5);
	this.instance_73.alpha = 0.641;

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance_73},{t:this.instance_72},{t:this.instance_71},{t:this.instance_70},{t:this.instance_69},{t:this.instance_68},{t:this.instance_67},{t:this.instance_66},{t:this.instance_65},{t:this.instance_64},{t:this.instance_63},{t:this.instance_62},{t:this.instance_61},{t:this.instance_60},{t:this.instance_59},{t:this.instance_58},{t:this.instance_57},{t:this.instance_56},{t:this.instance_55},{t:this.instance_54},{t:this.instance_53},{t:this.instance_52},{t:this.instance_51},{t:this.instance_50},{t:this.instance_49},{t:this.instance_48},{t:this.instance_47},{t:this.instance_46},{t:this.instance_45},{t:this.instance_44},{t:this.instance_43},{t:this.instance_42},{t:this.instance_41},{t:this.instance_40},{t:this.instance_39},{t:this.instance_38},{t:this.instance_37},{t:this.instance_36},{t:this.instance_35},{t:this.instance_34},{t:this.instance_33},{t:this.instance_32},{t:this.instance_31},{t:this.instance_30},{t:this.instance_29},{t:this.instance_28},{t:this.instance_27},{t:this.instance_26},{t:this.instance_25},{t:this.instance_24},{t:this.instance_23},{t:this.instance_22},{t:this.instance_21},{t:this.instance_20},{t:this.instance_19},{t:this.instance_18},{t:this.instance_17},{t:this.instance_16},{t:this.instance_15},{t:this.instance_14},{t:this.instance_13},{t:this.instance_12},{t:this.instance_11},{t:this.instance_10},{t:this.instance_9},{t:this.instance_8},{t:this.instance_7},{t:this.instance_6},{t:this.instance_5}]}).wait(289));

	// Layer 2
	this.shape = new cjs.Shape();
	this.shape.graphics.rf(["#356187","#151321"],[0,1],0,0,0,0,0,473.8).s().p("Eg43AN5IAA7xMBxvAAAIAAbxg");
	this.shape.setTransform(364,45);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(289));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(364,-1133.2,1378.7,1312.2);

})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{});
var lib, images, createjs, ss;