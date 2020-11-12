<div class="row-fluid">
	<div class="span8">
    <div class="box-heading"><h1>CloudFlare CDN Service</h1></div>
    <table class="form cdnpanetable">
      <tr>
        <td>CloudFlare Service</td>
        <td>
        <select name="Nitro[CDNCloudFlare][Enabled]" class="NitroCDNCloudFlare">
            <option value="no" <?php echo (empty($data['Nitro']['CDNCloudFlare']['Enabled']) || $data['Nitro']['CDNCloudFlare']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNCloudFlare']['Enabled']) && $data['Nitro']['CDNCloudFlare']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        </td>
      </tr>
      <tr>
        <td>Your Account Email</td>
        <td>
         <input type="text" name="Nitro[CDNCloudFlare][Email]" value="<?php echo(!empty($data['Nitro']['CDNCloudFlare']['Email'])) ? $data['Nitro']['CDNCloudFlare']['Email'] : ''?>" />
        </td>
      </tr>
      <tr>
        <td>Your API Key</td>
        <td>
         <input type="text" name="Nitro[CDNCloudFlare][APIKey]" value="<?php echo(!empty($data['Nitro']['CDNCloudFlare']['APIKey'])) ? $data['Nitro']['CDNCloudFlare']['APIKey'] : ''?>" />
        </td>
      </tr>
    </table>

    </div>
    <div class="span4">
        <div class="box-heading"><h1><i class="icon-info-sign"></i>What is CloudFlare?</h1></div>
        <div class="box-content" style="min-height:150px; line-height:20px;">
        <p>CloudFlare is an online platform which protects and accelerates your site. CloudFlare gives you the chance to use its fast CDN network to distribute your content across an intelligent global network. This gives your visitors fast page load times and the top performance.</p><p> 
Another great advantage of using CloudFlare is that their platform will also block threats and limit abusive bots and crawlers from wasting your bandwidth and server resources.</p>
        <button class="btn btn-small" type="button" onclick="window.open('https://www.cloudflare.com/sign-up')">Sign Up</button>
        or 
        <button class="btn btn-small" type="button" onclick="window.open('https://www.cloudflare.com/login')">Login</button> in CloudFlare
        </div>
    </div>
</div>
