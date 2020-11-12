<div class="row-fluid">
  <div class="span8">
    <div class="box-heading">
      <h1>Minification</h1>
    </div>

    <table class="form minificationtoptable">
      <tr>
        <td>Use Minification</td>
        <td>
        <select name="Nitro[Mini][Enabled]" class="NitroMini">
            <option value="no" <?php echo (empty($data['Nitro']['Mini']['Enabled']) || $data['Nitro']['Mini']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['Enabled']) && $data['Nitro']['Mini']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        </td>
      </tr>
    </table>  
    
   <div class="minification-tabbable-parent">
    <div class="tabbable tabs-left"> 
          <ul class="nav nav-tabs">
            <li class="active"><a href="#mini-css" data-toggle="tab">CSS files</a></li>
            <li><a href="#mini-javascript" data-toggle="tab">JavaScript files</a></li>
            <li><a href="#mini-html" data-toggle="tab">HTML files</a></li>
          </ul>
         <div class="tab-content">
         	<div id="mini-css" class="tab-pane active">
                <table class="form minification" style="margin-top:-10px;">
                  <tr>
                    <td>Minify CSS files</td>
                    <td>
                    <select name="Nitro[Mini][CSS]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['CSS']) || $data['Nitro']['Mini']['CSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['CSS']) && $data['Nitro']['Mini']['CSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Combine CSS files<span class="help">This will combine all your CSS files loaded dynamically into 1 file called <i>nitro-combined.css</i></span></td>
                    <td>
                    <select name="Nitro[Mini][CSSCombine]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['CSSCombine']) || $data['Nitro']['Mini']['CSSCombine'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['CSSCombine']) && $data['Nitro']['Mini']['CSSCombine'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Improved CSS detection algorithm<span class="help">*<b>Experimental:</b> This will try to find hardcoded CSS resources from the generated cache files and process them as well</span></td>
                    <td>
                    <select name="Nitro[Mini][CSSExtract]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['CSSExtract']) || $data['Nitro']['Mini']['CSSExtract'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['CSSExtract']) && $data['Nitro']['Mini']['CSSExtract'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Parse import statements<span class="help">*<b>Experimental:</b> This will try to fetch the content of the imported with <b>@import</b> CSS resources and include it in the combined file.</span></td>
                    <td>
                    <select name="Nitro[Mini][CSSFetchImport]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['CSSFetchImport']) || $data['Nitro']['Mini']['CSSFetchImport'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['CSSFetchImport']) && $data['Nitro']['Mini']['CSSFetchImport'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top;">Excluded files<span class="help">Each file on a new line. The files you specify here will be excluded from minification. This applies to files detected with the improved CSS detection algorithm also.</span></td>
                    <td style="vertical-align:top;">
                    <textarea placeholder="e.g. slideshow.css, each file on a new line" style="width:400px; height:180px;" name="Nitro[Mini][CSSExclude]"><?php echo(!empty($data['Nitro']['Mini']['CSSExclude'])) ? $data['Nitro']['Mini']['CSSExclude'] : ''?></textarea>
                    </td>
                  </tr>
                  <tr>
                  <td colspan="2"><a href="javascript:void(0)" onclick="document.location='index.php?route=tool/nitro/clearcsscache&token=<?php echo $_GET['token']; ?>'" class="btn clearJSCSSCache"><i class="icon-trash"></i> Clear minified CSS files cache</a></td>
                  </tr>
                </table> 
            </div>
         	<div id="mini-javascript" class="tab-pane">
                <table class="form minification" style="margin-top:-10px;">
                  <tr>
                    <td>Minify JavaScript files</td>
                    <td>
                    <select name="Nitro[Mini][JS]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['JS']) || $data['Nitro']['Mini']['JS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['JS']) && $data['Nitro']['Mini']['JS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Combine JavaScript files<span class="help">This will combine all your JS files loaded dynamically into 1 file called <i>nitro-combined.js</i></span></td>
                    <td>
                    <select name="Nitro[Mini][JSCombine]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['JSCombine']) || $data['Nitro']['Mini']['JSCombine'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['JSCombine']) && $data['Nitro']['Mini']['JSCombine'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Improved JavaScript detection<span class="help">*<b>Experimental:</b> This will try to find hardcoded JavaScript resources from the generated cache files and process them as well</span></td>
                    <td>
                    <select name="Nitro[Mini][JSExtract]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['JSExtract']) || $data['Nitro']['Mini']['JSExtract'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['JSExtract']) && $data['Nitro']['Mini']['JSExtract'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top;">Excluded files<span class="help">Each file on a new line. The files you specify here will be excluded from minification. This applies to files detected with the improved JavaScript detection algorithm also.</span></td>
                    <td style="vertical-align:top;">
                    <textarea placeholder="e.g. slideshow.js, each file on a new line" style="width:400px; height:180px;" name="Nitro[Mini][JSExclude]"><?php echo(!empty($data['Nitro']['Mini']['JSExclude'])) ? $data['Nitro']['Mini']['JSExclude'] : ''?></textarea>
                    </td>
                  </tr>
                  <tr>
                  <td colspan="2"><a href="javascript:void(0)" onclick="document.location='index.php?route=tool/nitro/clearjscache&token=<?php echo $_GET['token']; ?>'" class="btn clearJSCSSCache"><i class="icon-trash"></i> Clear minified JavaScript files cache</a></td>
                  </tr>
                </table> 
	        
            </div>
         	<div id="mini-html" class="tab-pane">
            <?php if (empty($data['Nitro']['PageCache']['Enabled']) || $data['Nitro']['PageCache']['Enabled'] == 'no'): ?>
            <div class="alert alert-error"><b>Oh snap!</b> This feature requires enabled Page Cache. <a href="javascript:void(0)" onclick="$('a[href=#pagecache]').trigger('click');">Click here</a> to enable it.</div>
            <?php endif; ?>
                <table class="form minification" style="margin-top:-10px;">
                  <tr>
                    <td>Minify HTML files<span class="help">This requires enabled Page Cache. When enabled, the page cache files will be created minified.</span></td>
                    <td>
                    <select name="Nitro[Mini][HTML]">
                        <option value="no" <?php echo (empty($data['Nitro']['Mini']['HTML']) || $data['Nitro']['Mini']['HTML'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['Mini']['HTML']) && $data['Nitro']['Mini']['HTML'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
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
      <h1><i class="icon-info-sign"></i>What is minification?</h1>
    </div>
    <div class="box-content" style="min-height:100px; line-height:20px;">
	Minification is the process of removing all unnecessary characters from source code, without changing its functionality. These unnecessary characters usually include white space characters, new line characters, comments, and sometimes block delimiters, which are used to add readability to the code but are not required for it to execute.
    
    
    </div>
  </div>
</div>