<div class="row-fluid">
  <div class="span8">
    <div class="box-heading">
      <h1>Smush.it</h1>
    </div>
    <div class="box-content">
        <div class="box-heading" style="margin-bottom:15px;">
            <div class="span8">
                <div class="box-minibox">Smushed Images<div class="number" id="smushedNumber"><?php echo !empty($smushit_data['smushed_images_count']) ? $smushit_data['smushed_images_count'] : 0;?></div></div>
                <div class="box-minibox">Already Smushed Images<div class="number" id="alreadySmushedNumber"><?php echo !empty($smushit_data['already_smushed_images_count']) ? $smushit_data['already_smushed_images_count'] : 'N/A';?></div></div> 
                <div class="box-minibox">Total Images<div class="number" id="totalImages"><?php echo !empty($smushit_data['total_images']) ? $smushit_data['total_images'] : 'N/A';?></div></div>        
                <div class="box-minibox">Kilobytes saved<div class="number" id="kbSaved"><?php echo !empty($smushit_data['kb_saved']) ? $smushit_data['kb_saved'] : 0;?> KB</div></div>         
                <div class="box-minibox">Last smushed<div class="number" id="lastSmushTimestamp"><?php echo !empty($smushit_data['last_smush_timestamp']) ? date('D, j M Y H:i:s', $smushit_data['last_smush_timestamp']) : 'N/A';?></div></div>
            </div>
            <div class="span4">
                <div class="progress" style="text-align: center;">
                    <div id="progressBar" class="bar" style="width: 0%;line-height: 20px;color:#000;">0%</div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
         <div class="smushingResult"></div>
        <button type="button" class="btn btn-large btn-primary smushItButton">Start new smush process</button>
        <button type="button" class="btn btn-large resumeSmushButton" style="display: none">Resume previous smush</button>
        <button type="button" class="btn btn-large btn-primary btn-inverse pauseSmushButton" style="display: none">Pause</button>
        <div class="empty-smush-div"></div>
        <div class="smush-log">
            <div class="smush-log-entries">
            </div>
        </div>
    </div>
  </div>
  <div class="span4">
    <div class="box-heading">
      <h1><i class="icon-info-sign"></i>What is Smush.it?</h1>
    </div>
    <div class="box-content" style="min-height:100px; line-height:20px;"> 
    <p>Smush.it is an image optimization service by <a target="_blank" href="http://developer.yahoo.com/yslow/smushit/">Yahoo!</a></p>
    <p>Smush.it uses optimization techniques specific to image format to remove unnecessary bytes from image files. It is a "lossless" tool, which means it optimizes the images without changing their look or visual quality. After Smush.it runs on a web page it reports how many bytes would be saved by optimizing the page's images. </p>
    <p>You can chech the Smush.it <a target="_blank" href="http://developer.yahoo.com/yslow/smushit/faq.html">Frequently Asked Questions</a> or take a look at <a target="_blank" href="http://www.smushit.com/ysmush.it/">their the hosted service</a>.</p>
    </div>
    <div class="box-heading">
      <h1>Options</h1>
    </div>
    <div class="box-content" style="min-height: 150px;">
        <table class="form">
          <tr>
            <td style="vertical-align:top;">Smush On-Demand<span class="help">Requires activated Page Cache. If enabled, your images will be smushed while your page cache is created. Your first-time page load when the cache is being created may be slower, since the images will be compressed on-the-fly by the SmushIt servers.</span></td>
            <td style="vertical-align:top;">
            <select name="Nitro[SmushIt][OnDemand]">
                <option value="yes" <?php echo( (!empty($data['Nitro']['SmushIt']['OnDemand']) && $data['Nitro']['SmushIt']['OnDemand'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
                <option value="no" <?php echo (empty($data['Nitro']['SmushIt']['OnDemand']) || $data['Nitro']['SmushIt']['OnDemand'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            </select>
            </td>
          </tr>
          <tr>
            <td style="vertical-align:top;">Smush directory/file<span class="help">Enter path to a specific directory or a single file to be smushed. The path should be relative to the root of your OpenCart installation. Use "/" for directory separator even if your server is running on Windows.<br /><strong>Warning:</strong> The smushing process will overwrite the original images with the smushed ones, so a backup is recommended!</span></td>
            <td style="vertical-align:top;">
            <input id="smushTargetPath" type="text" name="Nitro[SmushIt][target_path]" value="<?php echo !empty($data['Nitro']['SmushIt']['target_path']) ? $data['Nitro']['SmushIt']['target_path'] : ''?>" placeholder="image/cache/">
            </td>
          </tr>
        </table>
    </div>
  </div>
</div>


<script>
var smushLog = $('.smush-log-entries');
	smushLog.parent().hide();

var formatTimestamp = function (timestamp) {
	if (timestamp == 0) return 'N/A';
	
	var weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var dateObj = new Date(timestamp * 1000);
	return weekDays[dateObj.getDay()] + ', ' + dateObj.getDate() + ' ' + months[dateObj.getMonth()] + ' ' + dateObj.getFullYear() + ' ' + dateObj.getHours() + ':' + dateObj.getMinutes() + ':' + dateObj.getSeconds();
}

var smusher = (function(){
	var images = [];
	var imagesCount = 0;
	var progressIndex = 0;
	var flagPause = false;
	var logArea = null;
	var isLoadingImagesList = false;
	var isSmushWaiting = false;
	var isSmushStartEventFired = false;
	var smushFinishedCallbacks = [];
	var smushPausedCallbacks = [];
	var smushResumedCallbacks = [];
	var smushStartedCallbacks = [];
	var smushRestoredStateCallbacks = [];
	var smushStartedCallbacks = [];
	var smushCountChangeCallbacks = [];
	
	var getImageList = function() {
		progressIndex = 0;
		isLoadingImagesList = true;
		var smushTargetPath = '';
		if ($('#smushTargetPath').val().length > 0) {
			smushTargetPath = '&targetDir=' + $('#smushTargetPath').val().replace(/\//g, '%2F');
		}
		$.ajax({
			url: 'index.php?route=tool/nitro/getsmushimages'+smushTargetPath+'&token=<?php echo $_GET['token'] ?>',
			dataType: 'json',
			cache: false,
			success: function(data) {
				images = data;
				isLoadingImagesList = false;
				if (isSmushWaiting) {
					smush();
				}
			}
		});
	}
	
	var fireCallbacks = function(callbacksList) {
		if (callbacksList.length) {
			var callback = null;
			for (x=0; x<callbacksList.length;x++) {
				callback = callbacksList[x];
				callback();
			}
		}
	}
	
	var smush = function() {
		if (images.length > 0 && !isLoadingImagesList) {
			isSmushWaiting = false;
			if (typeof images[progressIndex] != 'undefined' && !flagPause) {
				if (!isSmushStartEventFired) {
					isSmushStartEventFired = true;
					fireCallbacks(smushStartedCallbacks);
				}
				
				$.ajax({
					url: 'index.php?route=tool/nitro/smushimagelist&&token=<?php echo $_GET['token'] ?>',
					type: 'POST',
					dataType: 'json',
					data: {imageList: [images[progressIndex]]},
					cache: false,
					success: function(data) {
						var extra_html = '';
						for (x in data.smushed_files) {
							extra_html += data.smushed_files[x].filename + ' <b>(' + data.smushed_files[x].percent + '% saved)</b><br><br>';
						}
						logArea.parent().show();
						var entries = logArea.html().split('<br><br>');
						if (entries.length >= 10) {
							entries.splice(0, 1);
							logArea.html(entries.join('<br><br>'));
						}
						logArea.append(extra_html).slideDown();
						$('.smush-log').css({width: $('.empty-smush-div').width() + 'px'}).animate({
							scrollTop: logArea.outerHeight()
						}, 1000);
						$('#smushedNumber').html(data.smushed_images_count);
						$('#alreadySmushedNumber').html(data.already_smushed_images_count);
						$('#kbSaved').html(data.kb_saved);
						$('#totalImages').html(data.total_images);
						$('#lastSmushTimestamp').html(formatTimestamp(data.last_smush_timestamp));
						
						progressIndex++;
						fireCallbacks(smushCountChangeCallbacks);
						smush();
					}
				});
			} else {
				saveState();
				if (typeof images[progressIndex] == 'undefined') {
					fireCallbacks(smushFinishedCallbacks);
				} else if (flagPause) {
					fireCallbacks(smushPausedCallbacks);
				}
			}
		} else if(isLoadingImagesList) {
			isSmushWaiting = true;
		}
	}
	
	var stopSmushing = function() {
		flagPause = true;
	}
	
	var saveState = function() {
		if(typeof(Storage)!=="undefined") {
			localStorage.nitroSmusherImages = JSON.stringify(images);
			localStorage.nitroSmusherProgressIndex = progressIndex;
		} else {
			console.log('Smusher: localStoreage API not available!');
		}
	}
	
	var validateSavedState = function() {
		return (typeof(localStorage.nitroSmusherImages) != 'undefined' && typeof(localStorage.nitroSmusherProgressIndex) != 'undefined');
	}
	
	var restoreState = function() {
		if(typeof(Storage)!=="undefined" && validateSavedState()) {
			images = JSON.parse(localStorage.nitroSmusherImages);
			progressIndex = parseInt(localStorage.nitroSmusherProgressIndex);
			fireCallbacks(smushRestoredStateCallbacks);
			return true;
		} else {
			return false;
		}
	}
	
	var clearSavedState = function() {
		if(typeof(Storage)!=="undefined" && validateSavedState()) {
			localStorage.removeItem('nitroSmusherImages');
			localStorage.removeItem('nitroSmusherProgressIndex');
		} else {
			return true;
		}
	}
	
	return {
		init: function(logElement, getNewImageList) {
			getNewImageList = getNewImageList ? true : false;
			
			logArea = logElement;
			if (!restoreState() && getNewImageList) {
				getImageList();
			}
		},
		reset: function() {
			images = [];
			imagesCount = 0;
			progressIndex = 0;
			flagPause = false;
			isSmushStartEventFired = false;
			clearSavedState();
			logArea.html('');
		},
		begin: function(){
			progressIndex = 0;
			flagPause = false;
			isSmushStartEventFired = false;
			smush();
		},
		restart: function() {
			images = [];
			imagesCount = 0;
			progressIndex = 0;
			flagPause = false;
			isSmushStartEventFired = false;
			logArea.html('');
			getImageList();
			smush();
		},
		resume: function(){
			flagPause = false;
			isSmushStartEventFired = false;
			smush();
		},
		pause: function(){
			stopSmushing();
		},
		getImages: function() { return images; },
		getTotalImagesCount: function() {
			if (imagesCount == 0) {
				imagesCount = images.length;
			}
			
			return imagesCount;
		},
		getProcessedImagesCount: function() {
			return progressIndex;
		},
		addSmushFinishEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushFinishedCallbacks.push(callback);
			}
		},
		addSmushPauseEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushPausedCallbacks.push(callback);
			}
		},
		addSmushResumeEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushResumedCallbacks.push(callback);
			}
		},
		addSmushStartEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushStartedCallbacks.push(callback);
			}
		},
		addSmushRestoredStateEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushRestoredStateCallbacks.push(callback);
			}
		},
		addSmushStartedEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushStartedCallbacks.push(callback);
			}
		},
		addSmushCountChangedEventListener: function(callback) {
			if (typeof callback === 'function') {
				smushCountChangeCallbacks.push(callback);
			}
		}
	};
})();

smusher.addSmushPauseEventListener(function(){
	$('.smushItButton').show();
	$('.pauseSmushButton').text('Pause').hide();
	$('.resumeSmushButton').show();
	$('.smushingResult div.smushingDiv').remove();
});

smusher.addSmushFinishEventListener(function(){
	$('.smushItButton').show();
	$('.pauseSmushButton').text('Pause').hide();
	$('.resumeSmushButton').hide();
	$('.smushingResult div.smushingDiv').remove();
});

smusher.addSmushRestoredStateEventListener(function(){
	$('.resumeSmushButton').show();
	var progress = parseInt((smusher.getProcessedImagesCount()*100)/smusher.getTotalImagesCount());
	$('#progressBar').css('width', progress + '%').text(progress + '%');
});

smusher.addSmushStartedEventListener(function(){
	$('.pauseSmushButton').show();
	$('.smushItButton').hide();
	$('.resumeSmushButton').hide();
	$('.smushingResult div.smushingDiv').remove();
	$('.smushingResult').html('<div class="smushingDiv"><img src="../catalog/view/theme/default/image/loading.gif" /> Smushing...</div>');
});

smusher.addSmushCountChangedEventListener(function(){
	var progress = parseInt((smusher.getProcessedImagesCount()*100)/smusher.getTotalImagesCount());
	$('#progressBar').css('width', progress + '%').text(progress + '%');
});

$('.smushItButton').click(function() {
	smusher.reset();
	smusher.init(smushLog, true);
	smusher.begin();
});

$('.pauseSmushButton').click(function() {
	smusher.pause();
	$(this).text('Pausing...');
});

$('.resumeSmushButton').click(function() {
	smusher.resume();
});

smusher.init(smushLog);
</script>

<style>
.smushingDiv {
	padding: 10px;
}
</style>