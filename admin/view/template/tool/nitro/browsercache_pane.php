<table class="form browserCache">
  <tr>
    <td>Browser Cache Status</td>
    <td>
    <select name="Nitro[BrowserCache][Enabled]" class="NitroBrowserCacheEnabled">
        <option value="yes" <?php echo( (!empty($data['Nitro']['BrowserCache']['Enabled']) && $data['Nitro']['BrowserCache']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        <option value="no" <?php echo (empty($data['Nitro']['BrowserCache']['Enabled']) || $data['Nitro']['BrowserCache']['Enabled'] != 'yes') ? 'selected=selected' : ''?>>Disabled</option>
    </select>
    </td>
  </tr>
  <tr><td colspan="2"><h4>OpenCart Pages</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Activated Headers</td>
    <td>
    	<table class="bcHeaders">
            <tr>
				<td class="checkboxHeader"><input type="checkbox" id="chkPagesCacheControl" name="Nitro[BrowserCache][Headers][Pages][CacheControl]" <?php echo (!empty($data['Nitro']['BrowserCache']['Headers']['Pages']['CacheControl'])) ? 'checked=checked' : ''; ?> /></td>            
				<td class="textHeader">
                <label for="chkPagesCacheControl">Set <strong>Cache-Control</strong> Header</label>
                <a href="javascript:void(0)" class="infoPopover" data-toggle="popover" data-html="true" data-title="Cache-Control: max-age" data-content="This sets <i>Cache-Control: max-age</i> to a high value, which specifies the maximum amount of time that a representation will be considered fresh." data-placement="right"><i class="icon-info-sign"></i></a>
                </td>            
            </tr>
            <tr>
				<td class="checkboxHeader"><input type="checkbox" id="chkPagesExpires" name="Nitro[BrowserCache][Headers][Pages][Expires]" <?php echo (!empty($data['Nitro']['BrowserCache']['Headers']['Pages']['Expires'])) ? 'checked=checked' : ''; ?> /></td>            
				<td class="textHeader">
                <label for="chkPagesExpires">Set <strong>Expires</strong> Header</label>
                <a href="javascript:void(0)" class="infoPopover" data-toggle="popover" data-html="true" data-title="Expires" data-content="This sets an <i>Expires:</i> header in a future date which will be the expiration date or time for files sent by your server. The expiration information is used by also by proxy servers." data-placement="right"><i class="icon-info-sign"></i></a>
                </td>            
            </tr>
            <tr>
				<td class="checkboxHeader"><input type="checkbox" id="chkPagesLastModified" name="Nitro[BrowserCache][Headers][Pages][LastModified]" <?php echo (!empty($data['Nitro']['BrowserCache']['Headers']['Pages']['LastModified'])) ? 'checked=checked' : ''; ?> /></td>            
				<td class="textHeader">
                <label for="chkPagesLastModified">Set <strong>Last-Modified</strong> Header</label>
                <a href="javascript:void(0)" class="infoPopover" data-toggle="popover" data-html="true" data-title="Last-Modified" data-content="This sets a <i>Last-Modified:</i> header which specifies some characteristic about the resource that the browser checks to determine if the files are the same. The value of this header is always a date. Also, <i>Last-Modified:</i> is a 'weak' caching header in that the browser applies a heuristic to determine whether to fetch the item from cache or not. (The heuristics are different among different browsers)" data-placement="right"><i class="icon-info-sign"></i></a>
                </td>            
            </tr>
            <tr>
				<td class="checkboxHeader"><input type="checkbox" id="chkPagesETag" name="Nitro[BrowserCache][Headers][Pages][ETag]" <?php echo (!empty($data['Nitro']['BrowserCache']['Headers']['Pages']['ETag'])) ? 'checked=checked' : ''; ?> /></td>            
				<td class="textHeader">
                <label for="chkPagesETag">Set <strong>ETag</strong> Header</label>
                <a href="javascript:void(0)" class="infoPopover" data-toggle="popover" data-html="true" data-title="ETag" data-content="This sets an <i>ETag:</i> header, which specifies some characteristic about the resource that the browser checks to determine if the files are the same." data-placement="right"><i class="icon-info-sign"></i></a>
                </td>            
            </tr>
    	</table>
    </td>
  </tr>
  <tr><td colspan="2"><h4>CSS, JavaScript, XML and text</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Cache <i>css, js, txt, xml</i> files for a period of: </td>
    <td>
        <select name="Nitro[BrowserCache][CSSJS][Period]" class="NitroBrowserCacheEnabled">
        	<option value="no-cache" <?php echo( !empty($data['Nitro']['BrowserCache']['CSSJS']['Period'])&& $data['Nitro']['BrowserCache']['CSSJS']['Period'] == 'no-cache') ? 'selected=selected' : ''?>>0 - Do not cache it</option>
            <option value="1 week" <?php echo (empty($data['Nitro']['BrowserCache']['CSSJS']['Period']) || $data['Nitro']['BrowserCache']['CSSJS']['Period'] == '1 week') ? 'selected=selected' : ''?>>1 week</option>
            <option value="1 month" <?php echo( !empty($data['Nitro']['BrowserCache']['CSSJS']['Period']) &&  $data['Nitro']['BrowserCache']['CSSJS']['Period'] == '1 month') ? 'selected=selected' : ''?>>1 month</option>
            <option value="6 months" <?php echo (!empty($data['Nitro']['BrowserCache']['CSSJS']['Period']) && $data['Nitro']['BrowserCache']['CSSJS']['Period'] == '6 months') ? 'selected=selected' : ''?>>6 months</option>
            <option value="1 year" <?php echo (!empty($data['Nitro']['BrowserCache']['CSSJS']['Period']) && $data['Nitro']['BrowserCache']['CSSJS']['Period'] == '1 year') ? 'selected=selected' : ''?>>1 year</option>
        </select>
    </td>
  </tr>
  <tr><td colspan="2"><h4>Images</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Cache <i>jpg, jpeg, png, gif, swf</i> files for a period of: </td>
    <td>
        <select name="Nitro[BrowserCache][Images][Period]" class="NitroBrowserCacheEnabled">
        	<option value="no-cache" <?php echo( !empty($data['Nitro']['BrowserCache']['Images']['Period'])&& $data['Nitro']['BrowserCache']['Images']['Period'] == 'no-cache') ? 'selected=selected' : ''?>>0 - Do not cache it</option>
            <option value="1 week" <?php echo (!empty($data['Nitro']['BrowserCache']['Images']['Period']) && $data['Nitro']['BrowserCache']['Images']['Period'] == '1 week') ? 'selected=selected' : ''?>>1 week</option>
            <option value="1 month" <?php echo( empty($data['Nitro']['BrowserCache']['Images']['Period']) ||  $data['Nitro']['BrowserCache']['Images']['Period'] == '1 month') ? 'selected=selected' : ''?>>1 month</option>
            <option value="6 months" <?php echo (!empty($data['Nitro']['BrowserCache']['Images']['Period']) && $data['Nitro']['BrowserCache']['Images']['Period'] == '6 months') ? 'selected=selected' : ''?>>6 months</option>
            <option value="1 year" <?php echo (!empty($data['Nitro']['BrowserCache']['Images']['Period']) && $data['Nitro']['BrowserCache']['Images']['Period'] == '1 year') ? 'selected=selected' : ''?>>1 year</option>
        </select>
    </td>
  </tr>
  <tr><td colspan="2"><h4>Icons, Fonts, PDF and Flash video</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Cache <i>otf,ico,pdf,flv</i> files for a period of: </td>
    <td>
        <select name="Nitro[BrowserCache][Icons][Period]" class="NitroBrowserCacheEnabled">
        	<option value="no-cache" <?php echo( !empty($data['Nitro']['BrowserCache']['Icons']['Period'])&& $data['Nitro']['BrowserCache']['Icons']['Period'] == 'no-cache') ? 'selected=selected' : ''?>>0 - Do not cache it</option>
            <option value="1 week" <?php echo (!empty($data['Nitro']['BrowserCache']['Icons']['Period']) && $data['Nitro']['BrowserCache']['Icons']['Period'] == '1 week') ? 'selected=selected' : ''?>>1 week</option>
            <option value="1 month" <?php echo( empty($data['Nitro']['BrowserCache']['Icons']['Period']) ||  $data['Nitro']['BrowserCache']['Icons']['Period'] == '1 month') ? 'selected=selected' : ''?>>1 month</option>
            <option value="6 months" <?php echo (!empty($data['Nitro']['BrowserCache']['Icons']['Period']) && $data['Nitro']['BrowserCache']['Icons']['Period'] == '6 months') ? 'selected=selected' : ''?>>6 months</option>
            <option value="1 year" <?php echo (!empty($data['Nitro']['BrowserCache']['Icons']['Period']) && $data['Nitro']['BrowserCache']['Icons']['Period'] == '1 year') ? 'selected=selected' : ''?>>1 year</option>
        </select>
    </td>
  </tr>
</table>            
<script type="text/javascript">
$('.infoPopover').popover();
</script>