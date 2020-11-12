<div class="row-fluid">
  <div class="span8">
    <div class="box-heading">
      <h1>GZIP HTTP Compression</h1>
    </div>
    <table class="form compression">
      <tr>
        <td>GZIP Compression Status</td>
        <td>
        <select name="Nitro[Compress][Enabled]" class="NitroCompressEnabled">
            <option value="yes" <?php echo( (!empty($data['Nitro']['Compress']['Enabled']) && $data['Nitro']['Compress']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
            <option value="no" <?php echo (empty($data['Nitro']['Compress']['Enabled']) || $data['Nitro']['Compress']['Enabled'] != 'yes') ? 'selected=selected' : ''?>>Disabled</option>
        </select>
        </td>
      </tr>
    </table>

   <div class="minification-tabbable-parent">
    <div class="tabbable tabs-left"> 
          <ul class="nav nav-tabs">
            <li class="active"><a href="#compress-css" data-toggle="tab">CSS files</a></li>
            <li><a href="#compress-javascript" data-toggle="tab">JavaScript files</a></li>
            <li><a href="#compress-html" data-toggle="tab">HTML files</a></li>
          </ul>
         <div class="tab-content">
         	<div id="compress-css" class="tab-pane active">
                <table class="form compression" style="margin-top:-10px;">
                  <tr>
                    <td>Compress CSS files</td>
                    <td>
                    <select name="Nitro[Compress][CSS]">
                        <option value="no" <?php echo (empty($data['Nitro']['Compress']['CSS']) || $data['Nitro']['Compress']['CSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Compress']['CSS']) && $data['Nitro']['Compress']['CSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Compression Level</td>
                    <td>
                      <select name="Nitro[Compress][CSSLevel]" val="<?php echo (!empty($data['Nitro']['Compress']['CSSLevel'])) ? $data['Nitro']['Compress']['CSSLevel'] : '4';?>" class="compressionLevel" id="compressionLevel1">
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                      </select>
                      <div id="sliderCompression1" class="sliderCompression"></div>
                    </td>
                  </tr>
                  <tr>
                  <td colspan="2"><a href="javascript:void(0)" onclick="document.location='index.php?route=tool/nitro/clearcsscache&token=<?php echo $_GET['token']; ?>'" class="btn clearJSCSSCache"><i class="icon-trash"></i> Clear compressed CSS files cache</a></td>
                  </tr>
                </table> 
            </div>
         	<div id="compress-javascript" class="tab-pane">
                <table class="form compression" style="margin-top:-10px;">
                  <tr>
                    <td>Compress JavaScript files</td>
                    <td>
                    <select name="Nitro[Compress][JS]">
                        <option value="no" <?php echo (empty($data['Nitro']['Compress']['JS']) || $data['Nitro']['Compress']['JS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Compress']['JS']) && $data['Nitro']['Compress']['JS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Compression Level</td>
                    <td>
                      <select name="Nitro[Compress][JSLevel]" val="<?php echo (!empty($data['Nitro']['Compress']['JSLevel'])) ? $data['Nitro']['Compress']['JSLevel'] : '4';?>" class="compressionLevel" id="compressionLevel2">
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                      </select>
                      <div id="sliderCompression2" class="sliderCompression"></div>
                    </td>
                  </tr>
                  <tr>
                  <td colspan="2"><a href="javascript:void(0)" onclick="document.location='index.php?route=tool/nitro/clearjscache&token=<?php echo $_GET['token']; ?>'" class="btn clearJSCSSCache"><i class="icon-trash"></i> Clear compressed JavaScript files cache</a></td>
                  </tr>
                </table> 
            </div>
         	<div id="compress-html" class="tab-pane">
            <?php if (empty($data['Nitro']['PageCache']['Enabled']) || $data['Nitro']['PageCache']['Enabled'] == 'no'): ?>
            <div class="alert alert-error"><b>Oh snap!</b> This feature requires enabled Page Cache. <a href="javascript:void(0)" onclick="$('a[href=#pagecache]').trigger('click');">Click here</a> to enable it.</div>
            <?php endif; ?>
                <table class="form minification" style="margin-top:-10px;">
                  <tr>
                    <td>Compress HTML files<span class="help">This requires enabled Page Cache. When enabled, the page cache files will be created compressed.</span></td>
                    <td>
                    <select name="Nitro[Compress][HTML]">
                        <option value="no" <?php echo (empty($data['Nitro']['Compress']['HTML']) || $data['Nitro']['Compress']['HTML'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Compress']['HTML']) && $data['Nitro']['Compress']['HTML'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Compression Level</td>
                    <td>
                      <select name="Nitro[Compress][HTMLLevel]" val="<?php echo (!empty($data['Nitro']['Compress']['HTMLLevel'])) ? $data['Nitro']['Compress']['HTMLLevel'] : '4';?>" class="compressionLevel" id="compressionLevel3">
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                      </select>
                      <div id="sliderCompression3" class="sliderCompression"></div>
                    </td>
                  </tr>
                </table> 
            </div>
         </div>
       </div>
    </div>  
    
    
            
  </div>
  <div class="span4">
    <div class="box-heading">
      <h1><i class="icon-info-sign"></i>What is a GZIP HTTP compression?</h1>
    </div>
    <div class="box-content" style="min-height:100px; line-height:20px;">
    <p>GZIP HTTP compression is a capability that can be built into web servers and web clients to make better use of available bandwidth, and provide greater transmission speeds between both.</p>
    <p>HTTP data is compressed before it is sent from the server: compliant browsers will announce what methods are supported to the server before downloading the correct format; browsers that do not support compliant compression method will download uncompressed data.</p>
    </div>
  </div>
</div> 
<script>
  $(function() {
    var select1 = $("#compressionLevel1");
    var slider1 = $("#sliderCompression1").insertAfter(select1).slider({
      min: 0,
      max: 9,
      range: "min",
      value: select1[0].selectedIndex,
      slide: function(event, ui) {
        select1[0].selectedIndex = ui.value;
      }
    });
    var select2 = $("#compressionLevel2");
    var slider2 = $("#sliderCompression2").insertAfter(select2).slider({
      min: 0,
      max: 9,
      range: "min",
      value: select2[0].selectedIndex,
      slide: function(event, ui) {
        select2[0].selectedIndex = ui.value;
      }
    });
    $("#compressionLevel2").change(function() {
      slider2.slider("value", this.selectedIndex);
    });
    var select3 = $("#compressionLevel3");
    var slider3 = $("#sliderCompression3").insertAfter(select3).slider({
      min: 0,
      max: 9,
      range: "min",
      value: select3[0].selectedIndex,
      slide: function(event, ui) {
        select3[0].selectedIndex = ui.value;
      }
    });
    $("#compressionLevel3").change(function() {
      slider3.slider("value", this.selectedIndex);
    });
	
	 slider1.slider("value", $('#compressionLevel1').attr('val'));
	 slider2.slider("value", $('#compressionLevel2').attr('val'));
	 slider3.slider("value", $('#compressionLevel3').attr('val'));
	 $('#compressionLevel1 option:eq('+$('#compressionLevel1').attr('val')+')').attr('selected','selected');
	 $('#compressionLevel2 option:eq('+$('#compressionLevel2').attr('val')+')').attr('selected','selected');
	 $('#compressionLevel3 option:eq('+$('#compressionLevel3').attr('val')+')').attr('selected','selected');
  });
  </script>