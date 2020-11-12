/*generate image for videos javascript
copyright by M.Bilal Tajammal */

var imageThumb={
	videoPath:"",
	container:"",
	elementClass:"",
	appendTo:"",
	imageData:"",
	
	setVideo:function(video){
		videoPath = video;
	},
	
	setContainer:function(cont){
		container = cont;
	},
	
	getImage:async function(imageData){
		//await this.generateThumb(false);
		var img = document.getElementById('gen_thumb');
		img = img ? img : new Image();
		return img.src;
	},
	
	setImage:function(img){
		this.imageData = img;
		return img;
	},
	
	getIt:function(){
		return imageThumb.imageData;
	},
	
	generateThumb:async function(existing = true,appendTo = "",elementClass = ""){
		var video   = document.createElement('video');
		var canvas  = document.createElement('canvas');
		var context = canvas.getContext('2d');
		var w,h,ratio;
		var imgData;
		
		video.src = videoPath;
		video.load();
		video.addEventListener('loadedmetadata', function() {
			ratio = video.videoWidth/video.videoHeight;
			this.currentTime = this.duration / 4;
			w = video.videoWidth-100;
			h = parseInt(w/ratio,10);
			canvas.width = w;
			canvas.height = h;
		},false);
		
		video.addEventListener('loadeddata',function() {
			setTimeout(function(){
				context.fillRect(0,0,w,h);
				context.drawImage(video,0,0,w,h);
				imgData = canvas.toDataURL("image/jpg");
				if (existing === false){
					var image = new Image();
					image.src = imgData;
					image.id  = "gen_thumb";
					image.setAttribute('class',elementClass);
					image.setAttribute('data',videoPath);
					if (appendTo == "")
						document.body.appendChild(image);
					else
						document.getElementById(appendTo).appendChild(image);
				}else{
					//console.log("a");
					document.getElementById(container).src = imgData;
				}
			},900);
		});
		//document.body.appendChild(video);
		//document.body.appendChild(canvas);
	}
}

function loadVideoThumb(){
	var av = document.querySelector('video');
	av.addEventListener('loadedmetadata', function() {
			ratio = av.videoWidth/av.videoHeight;
			this.currentTime = this.duration / 4;
	},false);
}
