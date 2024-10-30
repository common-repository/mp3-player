

var player={  
	
	audioObject:{},
	
	wpPostId:{},
	
	trackNumber:0,  
		
	mouseDown:0,
	
	isPlaying:0, 	
		
	playAll:1, 		// if automatic go to next track
	
	paginationPage:-1,     
	
	getTrackName: function(){

		var trackname;

		trackname=jQuery('#track_'+this.wpPostId+'_'+this.trackNumber).html();

		return trackname;

	},

	renderTrackName: function(trackname){

		if(jQuery('#playercontroller_'+this.wpPostId+' .displayTrackName')){

			jQuery('#playercontroller_'+this.wpPostId+' .displayTrackName').html(trackname);

		}

	},

	getFileToPlay: function(wpPostId){

		audioFile=playlist[wpPostId][this.trackNumber];//set audiofile

		return audioFile;

	},

	/*used by play stop pause left to the slider*/

	pause: function(){

		this.audioObject.pause();

		this.isPlaying=0; 
		
		
		jQuery('.play').css('display','block' ); 

		jQuery('.pause').css('display','none' );
		
		jQuery('#playerplay'+player.wpPostId).css('display','block' ); 

		jQuery('#playerpause'+player.wpPostId).css('display','none' );

	},

	
	/*used by play stop pause left to the slider */

	stopAndResetPosition: function(){

		this.audioObject.pause(); 
		
		this.audioObject.currentTime=0;

		this.stop();
		
		this.isPlaying=0; 

	},

	/*

	*this function calculates song position when moving the slider. 

	*/

	setPosition: function(pos){

		pos= Math.round(player.audioObject.duration*parseInt(pos)/100);

		position =parseInt(pos); 

		this.audioObject.currentTime= position;
		
		//audioFile=this.getFileToPlay(this.wpPostId);
		
		this.play();
	},

	load: function(audioFile){
			
			this.audioObject.setAttribute('src', audioFile);

			this.audioObject.load();
		
	},
	
	play: function(){ 

		this.renderTrackName(this.getTrackName());

		this.audioObject.play();
		
		this.isPlaying=1;  
		
		jQuery('.play').css('display','block' ); 

		jQuery('.pause').css('display','none' );

		jQuery('#playerplay'+player.wpPostId).css('display','none' ); 

		jQuery('#playerpause'+player.wpPostId).css('display','block' );
	

	},

	stop: function(){

		this.audioObject.pause(); 

		this.isPlaying=0; 
		
		jQuery('.play').css('display','block' ); 

		jQuery('.pause').css('display','none' );

		jQuery('#playerplay'+player.wpPostId).css('display','block' ); 

		jQuery('#playerpause'+player.wpPostId).css('display','none' );

	},

	
	playerMore: function(){

		/*Pagination for default view*/

			
		IdPost=player.wpPostId;
		
		if(player.paginationPage<0){

			jQuery('#mp3PostId_'+IdPost+' .hidePage1').css('display','none');

			jQuery('#mp3PostId_'+IdPost+' .hidePage2').css('display','block');

			

		}else{

			jQuery('#mp3PostId_'+IdPost+' .hidePage1').css('display','block');

			jQuery('#mp3PostId_'+IdPost+' .hidePage2').css('display','None');

		}

		player.paginationPage=player.paginationPage*(-1);
		
	},

	setupClickActions: function(){
		
		
		thisObject=this;
		
		
		jQuery(".playercontroller").mousedown(function(){
		
			thisObject.mouseDown=1;
		
		}); 
		
		jQuery(".playercontroller").mouseup(function(){
		
			thisObject.mouseDown=0;
		
		}); 
		
		
		jQuery('.playBtn').click(function(){
			
			
			data=jQuery(this).attr('id');
			
			rg=data.split('_')

			thisObject.wpPostId = rg[1]; 

			thisObject.trackNumber=rg[2];	//id in array of all audiofiles, has to be set so we know where we are en playlist

			audioFile=thisObject.getFileToPlay(player.wpPostId);
			
			thisObject.load(audioFile);
						
	
			if(jQuery(this).hasClass('playing')){  

				thisObject.stop();

				jQuery(this).removeClass('playing');

				this.isPlaying=0;

			}else{

				jQuery('.playBtn').removeClass('playing');

				this.isPlaying=1;

				thisObject.play();

				jQuery(this).addClass('playing');

			}
			
			
			return false;
			
			
			});

		
		jQuery('.playercontroller .play').click(function() {
			
			data=jQuery(this).attr('id');

			rgMore=data.split('playerplay');
			
			thisObject.wpPostId = rgMore[1]; 
			
			audioFile=thisObject.getFileToPlay(thisObject.wpPostId);
			
			thisObject.load(audioFile);
			
			thisObject.play();
			
			return false;
		
		});


		jQuery('.playercontroller .pause').click(function() {
			
			data=jQuery(this).attr('id');

			rgMore=data.split('playerplay');
			
			thisObject.wpPostId = rgMore[1]; 			
			
			thisObject.pause();
			
			return false;
		
		});
		
		
		jQuery('.playercontroller .stop').click(function() {
			
			data=jQuery(this).attr('id');

			rgMore=data.split('playerplay');
			
			thisObject.wpPostId = rgMore[1]; 			
			
			thisObject.stopAndResetPosition();
			
			return false;
		
		});
		

		jQuery( ".nextPage" ).click(function(){
			
			data=jQuery(this).attr('id')

			rgMore=data.split('_');

			thisObject.wpPostId=rgMore[1];
				
			thisObject.playerMore();
			
			return false;
		});
		

	},

	setupEventlisteners: function(){

		this.audioObject.addEventListener('ended',this.callbackNextSong,false);

		this.audioObject.addEventListener('timeupdate',this.callbackUpdateSlider,false);

	},
	
	callbackUpdateSlider:function(){
	
		if(!player.mouseDown){
		
			var position;
		
			position=player.calculatePosition(player.audioObject.currentTime,player.audioObject.duration);
		
			jQuery('#slider'+player.wpPostId).css('left',position+"px");

			jQuery('#sliderWidth'+player.wpPostId).css('width',position+"px");

		}

		//dont know if this is right. Trying to show percent loaded

		var buffer=parseInt((player.audioObject.buffered.end(0)-player.audioObject.buffered.start(0))*100/player.audioObject.duration);

		jQuery("#info_bytes"+player.wpPostId).html(buffer+ "%");

	},
	
	findSliders: function(){

		sliders=jQuery('body').find('.carpe_slider');

		for (i = 0; i < sliders.length; i++) {

			sliders[i].onmousedown = simsSlider.slide ;

		}

	},
	
	callbackNextSong: function(){       

		allAudio=playlist[player.wpPostId];                            

		if(player.playAll==1){

			player.trackNumber++; 

			audioFile=allAudio[player.trackNumber];	//id in array of all audiofiles, has to be set so we know where we are en playlist

			player.load(audioFile);
			
			player.play();
			
			player.renderTrackName(player.getTrackName());
			

			jQuery('#track_'+player.wpPostId+'_'+player.trackNumber).addClass('playing');

			jQuery('#track_'+player.wpPostId+'_'+(player.trackNumber-1)).removeClass('playing');


		}

	},
	
	calculatePosition:function(position,duration){

		var timelineWidth = 100;
		var sliderWidth = 40;
		var sliderPositionMin = 0;
		var sliderPositionMax = 100;
		var sliderPosition = Math.round(100*( parseInt(position)/parseInt( duration)));

		if (sliderPosition < sliderPositionMin) {

			sliderPosition = sliderPositionMin;

		}

		if (sliderPosition > sliderPositionMax) {

			sliderPosition = sliderPositionMax;

		}
		
		return  sliderPosition;

	},


	init:function(){
			
		this.audioObject = document.getElementById("html5Player")
			
		if(player.audioObject){

			if(player.audioObject.canPlayType("audio/mp3")){

				this.setupEventlisteners();
				this.setupClickActions();
				this.findSliders();

			}

		}

	}

}


/**

* slider

*/

var simsSlider={

	moveSlider:function(evnt){

		var evnt = (!evnt) ? window.event : evnt; // The mousemove event

		if (mouseover) { // Only if slider is dragged

			x = slider.startOffsetX + evnt.screenX // Horizontal mouse position relative to allowed slider positions

			if (x > slider.xMax) x = slider.xMax // Limit horizontal movement

			if (x < 0) x = 0 // Limit horizontal movement

			jQuery('#'+slider.id).css('left',x+'px'); 

			sliderVal = x  // pixel value of slider regardless of orientation

			sliderPos = (100 / 1) * Math.round( sliderVal / slider.distance)

			v = Math.round((sliderPos + slider.from)) ; // calculate display value

			slider.v= sliderVal ;

			return false

		}

		return

	},

	slide:function (evnt){

		if (!evnt) evnt = window.event; // Get the mouse event causing the slider activation.

		slider = (evnt.target) ? evnt.target : evnt.srcElement; // Get the activated slider element.

		displayId = slider.getAttribute('display') // ID of associated display element.

		slider.from = 0

		slider.xMax = 100
		if(player.isPlaying){
			
			slider.startOffsetX = parseInt(jQuery('#'+slider.id).css('left')) - evnt.screenX // Slider-mouse horizontal offset at start of slide.
		
		}
		
			
		mouseover = true

		document.onmousemove = simsSlider.moveSlider // Start the action if the mouse is dragged.

		document.onmouseup = simsSlider.sliderMouseUp // Stop sliding.

		return false

	},

	sliderMouseUp:function(){

		if (mouseover) {

			v = slider.v ? slider.v: 0 // Find last display value.

			pos = v - slider.from; // Calculate slider position (regardless of orientation).

			pos = (pos > slider.xMax) ? slider.xMax : pos

			pos = (pos < 0) ? 0 : pos

		                             
			jQuery('#'+slider.id).css('left',pos+'px'); //set slider poss 

			//console.log(pos); 

			if (document.removeEventListener) { // Remove event listeners from 'document' (W3C).

				document.removeEventListener('mousemove', simsSlider.moveSlider, false)

				document.removeEventListener('mouseup', simsSlider.sliderMouseUp, false)

			}

			else if (document.detachEvent) { // Remove event listeners from 'document' (IE).

				document.detachEvent('onmousemove', simsSlider.moveSlider)

				document.detachEvent('onmouseup', simsSlider.sliderMouseUp)

			}

		}

		player.setPosition(pos);

		mouseover = false // Stop the sliding.

	}

}



jQuery(document).ready(function(){ 
	
	player.init(); 


});

