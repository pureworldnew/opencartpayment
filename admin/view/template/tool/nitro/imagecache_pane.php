<div class="row-fluid">
  <div class="span8">
    <div class="box-heading">
      <h1>Image Cache</h1>
    </div>
    <table class="form browserCache">
      <tr>
        <td>Override JPEG Quality<span class="help">This will override the default JPEG quality of OpenCart with the value below.</span></td>
        <td>
        <select name="Nitro[ImageCache][OverrideCompression]" class="NitroImageCacheOverrideCompression">
            <option value="yes" <?php echo( (!empty($data['Nitro']['ImageCache']['OverrideCompression']) && $data['Nitro']['ImageCache']['OverrideCompression'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
            <option value="no" <?php echo (empty($data['Nitro']['ImageCache']['OverrideCompression']) || $data['Nitro']['ImageCache']['OverrideCompression'] != 'yes') ? 'selected=selected' : ''?>>No</option>
        </select>
        </td>
      </tr>
      <tr>
        <td>JPEG Quality<span class="help">Your JPEG cache images will be created with this quality / compression.</span></td>
        <td>
            <input type="number" name="Nitro[ImageCache][JPEGCompression]" max="100" min="0" value="<?php echo !empty($data['Nitro']['ImageCache']['JPEGCompression']) ? $data['Nitro']['ImageCache']['JPEGCompression'] : '90'?>" />
        </td>
      </tr>
      <tr>
        <td>Image Cache Directory<span class="help">The native OpenCart image cache directory, where it stores the files.</span></td>
        <td>
            <span class="cacheImageDirSpan cacheDirLink" ca="<?php echo DIR_IMAGE.'cache/'; ?>">********** (click to show)</span>
        </td>
      </tr>
      <tr>
        <td>Delete cache<span class="help">Use this button to delete the OpenCart Image Cache.</span></td>
        <td>
            <a href="javascript:void(0)" onclick="document.location='index.php?route=tool/nitro/clearimagecache&token=<?php echo $_GET['token']; ?>'" class="btn"><i class="icon-trash"></i> Clear OpenCart Image Cache</a>
        </td>
      </tr>
    </table>            
    <script type="text/javascript">
    $('.cacheImageDirSpan').click(function() {
        $(this).html($(this).attr('ca')).removeClass('cacheDirLink');
    });
    </script>
  </div>
  <div class="span4">
    <div class="box-heading">
      <h1><i class="icon-info-sign"></i>Image Cache?</h1>
    </div>
    <div class="box-content" style="min-height:100px; line-height:20px;">
     	This is your OpenCart image cache functionality. Now you have the chance to control the quality of the images and clear the cache from the admin panel. The images are stored in the image cache directory and are created on-the-fly when an image request is sent. This means that if you clear your cache now, it will be auto-populated later while the users are browsing your site.
    </div>
  </div>
</div>