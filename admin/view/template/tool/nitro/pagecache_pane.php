<table class="form pagecache">
  <tr>
    <td>Page Cache Status</td>
    <td>
    <select name="Nitro[PageCache][Enabled]" class="NitroPageCacheEnabled">
        <option value="yes" <?php echo( (!empty($data['Nitro']['PageCache']['Enabled']) && $data['Nitro']['PageCache']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        <option value="no" <?php echo (empty($data['Nitro']['PageCache']['Enabled']) || $data['Nitro']['PageCache']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Expire Time (seconds)<span class="help">If the cache files get older than this time, it will be re-cached automatically.</span></td>
    <td>
		<input name="Nitro[PageCache][ExpireTime]" type="text" value="<?php echo (!empty($data['Nitro']['PageCache']['ExpireTime'])) ? $data['Nitro']['PageCache']['ExpireTime'] : NITROCACHE_TIME ?>" />
    </td>
  </tr>
  <tr>
    <td>Add width/height attributes to images<span class="help">Adding those attributes help for faster images rendering</span></td>
    <td>
    <select name="Nitro[PageCache][AddWHImageAttributes]">
        <option value="yes" <?php echo( (!empty($data['Nitro']['PageCache']['AddWHImageAttributes']) && $data['Nitro']['PageCache']['AddWHImageAttributes'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        <option value="no" <?php echo (empty($data['Nitro']['PageCache']['AddWHImageAttributes']) || $data['Nitro']['PageCache']['AddWHImageAttributes'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Clear cache on product edit<span class="help">If enabled, your Nitro-generated cache will be cleared each time you edit a product</span></td>
    <td>
    <select name="Nitro[PageCache][ClearCacheOnProductEdit]">
        <option value="yes" <?php echo( (!empty($data['Nitro']['PageCache']['ClearCacheOnProductEdit']) && $data['Nitro']['PageCache']['ClearCacheOnProductEdit'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        <option value="no" <?php echo (empty($data['Nitro']['PageCache']['ClearCacheOnProductEdit']) || $data['Nitro']['PageCache']['ClearCacheOnProductEdit'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
    </select>
    </td>
  </tr>
  <tr>
    <td style="vertical-align:top;">Ignored Routes<span class="help">Routes (route=parameter) to be ignored from page cache. One route per line.</span></td>
    <td>
		<textarea name="Nitro[PageCache][IgnoredRoutes]" style="width:400px; height:180px;" placeholder="One route per line, e.g. information/sitemap"><?php echo (!empty($data['Nitro']['PageCache']['IgnoredRoutes'])) ? $data['Nitro']['PageCache']['IgnoredRoutes'] : '' ?></textarea>
    </td>
  </tr>
  <tr>
    <td>Store Front Widget<span class="help">This is a small stripe in the very bottom of your website showing useful data</span></td>
    <td>
    <select name="Nitro[PageCache][StoreFrontWidget]" class="NitroPageCacheStoreFrontWidget">
        <option value="showOnlyWhenAdminIsLogged" <?php echo( (!empty($data['Nitro']['PageCache']['StoreFrontWidget']) && $data['Nitro']['PageCache']['StoreFrontWidget'] == 'showOnlyWhenAdminIsLogged')) ? 'selected=selected' : ''?>>Show Only When Admin is Logged In</option>
        <option value="showAlways" <?php echo ( (!empty($data['Nitro']['PageCache']['StoreFrontWidget']) && $data['Nitro']['PageCache']['StoreFrontWidget'] == 'showAlways')) ? 'selected=selected' : ''?>>Show Always</option>
        <option value="showNever" <?php echo ( (!empty($data['Nitro']['PageCache']['StoreFrontWidget']) && $data['Nitro']['PageCache']['StoreFrontWidget'] == 'showNever')) ? 'selected=selected' : ''?>>Show Never</option>
    </select>
    </td>
  </tr>
</table>            
