<div class="row-fluid">
	<div class="span8">
    <div class="box-heading"><h1>Database cache</h1></div>
    <table class="form cdnpanetable">
      <tr>
        <td>Database cache</td>
        <td>
        <select name="Nitro[DBCache][Enabled]" class="DBCache">
            <option value="no" <?php echo (empty($data['Nitro']['DBCache']['Enabled']) || $data['Nitro']['DBCache']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['DBCache']['Enabled']) && $data['Nitro']['DBCache']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        <a href="javascript:void(0)" onclick="document.location='index.php?route=tool/nitro/cleardbcache&token=<?php echo $_GET['token']; ?>'" class="btn clearDbCache"><i class="icon-trash"></i> Clear Database Cache</a>
        </td>
      </tr>
    </table>
    <div class="dbcache-tabbable-parent">
    <div class="tabbable tabs-left"> 
          <ul class="nav nav-tabs">
            <li class="active"><a href="#dbcache-general" data-toggle="tab">General</a></li>
            <li><a href="#dbcache-products" data-toggle="tab">Products</a></li>
            <li><a href="#dbcache-categories" data-toggle="tab">Categories</a></li>
            <li><a href="#dbcache-seourls" data-toggle="tab">SEO URLs</a></li>
            <li><a href="#dbcache-search" data-toggle="tab">Search</a></li>
          </ul>
         <div class="tab-content">
         	<div id="dbcache-general" class="tab-pane active">
                <table class="form" style="margin-top:-10px;">
                  <tr>
                    <td>Cache Storage<span class="help">Choose a storage system. If you wish to choose memory storage, you should have eAccelerator / XCache / Memcache available for data caching on your system. The options in grey color are <i>not available</i> in your system. Note that NitroPack is compatible with eAccelerator versions prior to 0.9.6.</span></td>
                    <td>
                    <?php $xcache_exists = function_exists('xcache_set') || function_exists('memcache_set') || function_exists('eaccelerator_put'); ?>
                    <select name="Nitro[DBCache][CacheDepo]">
                        <option value="hdd" <?php echo (empty($data['Nitro']['DBCache']['CacheDepo']) || $data['Nitro']['DBCache']['CacheDepo'] == 'hdd') ? 'selected=selected' : ''?>>File system (hard-drive)</option>
                        <option value="ram_eaccelerator" <?php echo( (!empty($data['Nitro']['DBCache']['CacheDepo']) && $data['Nitro']['DBCache']['CacheDepo'] == 'ram_eaccelerator')) ? 'selected=selected' : ''?> <?php if (!function_exists('eaccelerator_put')) { echo 'disabled=disabled style="color:#aaa;"'; } ?>>Memory (RAM) - eAccelerator</option>
                        <option value="ram_xcache" <?php echo( (!empty($data['Nitro']['DBCache']['CacheDepo']) && $data['Nitro']['DBCache']['CacheDepo'] == 'ram_xcache')) ? 'selected=selected' : ''?> <?php if (!function_exists('xcache_set')) { echo 'disabled=disabled style="color:#aaa;"'; } ?>>Memory (RAM) - XCache</option>
                        <option value="ram_memcache" <?php echo( (!empty($data['Nitro']['DBCache']['CacheDepo']) && $data['Nitro']['DBCache']['CacheDepo'] == 'ram_memcache')) ? 'selected=selected' : ''?> <?php if (!function_exists('memcache_set')) { echo 'disabled=disabled style="color:#aaa;"'; } ?>>Memory (RAM) - Memcache</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Expire Time (seconds)<span class="help">If the cache files get older than this time, it will be re-cached automatically.</span></td>
                    <td>
                        <input type="text" name="Nitro[DBCache][ExpireTime]" value="<?php echo(!empty($data['Nitro']['DBCache']['ExpireTime'])) ? $data['Nitro']['DBCache']['ExpireTime'] : '86400'?>" />
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="dbcache-products" class="tab-pane">
                <table class="form" style="margin-top:-10px;">
                  <tr>
                    <td>Cache Product Count Queries</span></td>
                    <td>
                    <select name="Nitro[DBCache][ProductCountQueries]">
                        <option value="no" <?php echo (empty($data['Nitro']['DBCache']['ProductCountQueries']) || $data['Nitro']['DBCache']['ProductCountQueries'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['DBCache']['ProductCountQueries']) && $data['Nitro']['DBCache']['ProductCountQueries'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="dbcache-categories" class="tab-pane">
                <table class="form" style="margin-top:-10px;">
                  <tr>
                    <td>Cache Category Queries</span></td>
                    <td>
                    <select name="Nitro[DBCache][CategoryQueries]">
                        <option value="no" <?php echo (empty($data['Nitro']['DBCache']['CategoryQueries']) || $data['Nitro']['DBCache']['CategoryQueries'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['DBCache']['CategoryQueries']) && $data['Nitro']['DBCache']['CategoryQueries'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Cache Category Count Queries</span></td>
                    <td>
                    <select name="Nitro[DBCache][CategoryCountQueries]">
                        <option value="no" <?php echo (empty($data['Nitro']['DBCache']['CategoryCountQueries']) || $data['Nitro']['DBCache']['CategoryCountQueries'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['DBCache']['CategoryCountQueries']) && $data['Nitro']['DBCache']['CategoryCountQueries'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="dbcache-seourls" class="tab-pane">
                <table class="form" style="margin-top:-10px;">
                  <tr>
                    <td>Cache SEO URLs</span></td>
                    <td>
                    <select name="Nitro[DBCache][SeoUrls]">
                        <option value="no" <?php echo (empty($data['Nitro']['DBCache']['SeoUrls']) || $data['Nitro']['DBCache']['SeoUrls'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['DBCache']['SeoUrls']) && $data['Nitro']['DBCache']['SeoUrls'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="dbcache-search" class="tab-pane">
                <table class="form" style="margin-top:-10px;">
                  <tr>
                    <td>Search Keywords Caching</span></td>
                    <td>
                    <select name="Nitro[DBCache][Search]">
                        <option value="no" <?php echo (empty($data['Nitro']['DBCache']['Search']) || $data['Nitro']['DBCache']['Search'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['DBCache']['Search']) && $data['Nitro']['DBCache']['Search'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align:top;">Search Keywords<span class="help">Comma separated. The query results of these keywords will be cached. Most effective when used for very popular search queries on your site.</span></td>
                    <td style="vertical-align:top;">
                    <textarea placeholder="e.g. imac, macbook pro, cheap imac, discounts" style="width:400px; height:180px;" name="Nitro[DBCache][SearchKeywords]"><?php echo(!empty($data['Nitro']['DBCache']['SearchKeywords'])) ? $data['Nitro']['DBCache']['SearchKeywords'] : ''?></textarea>
                    </td>
                  </tr>
                </table> 
            </div>

          </div>
       </div>
    </div>
    </div>
    <div class="span4">
        <div class="box-heading"><h1><i class="icon-info-sign"></i>Database cache</h1></div>
        <div class="box-content" style="min-height:100px; line-height:20px;">
           <p>NitroPack can cache the database queries in OpenCart known for their slow execution time. If Page Cache or Browser Cache are enabled, on some places the Database Cache will be superseded by the other caches.</p>
           <P>Use this cache when you want to optimize some frequent, but expensive database queries. Hard-disk and memory storage options are available.</P>
        </div>
    </div>
</div>

<style>
.cdnstandard {
		
}

</style>
