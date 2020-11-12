<ul class="media-list q-list">
  <li class="media">
 	<a class="pull-left q-a">
      <div class="q-icon">Q</div>
    </a>
    <div class="media-body">
      <h4 class="media-heading">How do I combine images into CSS sprites?</h4>
    <label class="checkbox iHaveDoneThis">
      <input type="checkbox" name="Nitro[Tips][CreatedSprites]" value="yes" <?php echo (!empty($data['Nitro']['Tips']['CreatedSprites']) && $data['Nitro']['Tips']['CreatedSprites'] == 'yes') ? 'checked=checked' : ''; ?>> I have already combined images into CSS sprites
    </label>
		This is a design technique and you will need a good designer with CSS skills. If this is you, but never done sprites before, you can look at <a href="http://css-tricks.com/css-sprites/" target="_blank">this article</a> which explains it well. If looking to hire someone to do it for you, you can have a look at our <a href="javascript:void(0)" onclick="$('a[href=#support]').trigger('click'); " >Premium Services</a>.

    </div>
  </li>
  <li class="media">
 	<a class="pull-left q-a">
      <div class="q-icon">Q</div>
    </a>
    <div class="media-body">
      <h4 class="media-heading">I am getting Internal Server Error 500, what to do?</h4>
		Most probably your .htaccess file is malformed. Please go to the root directory of your store, rename your .htaccess file to .htaccess-deactivated. Then, find the .htaccess-backup file in the same directory and rename it to .htaccess, then save and try again.
    </div>
  </li>
  <li class="media">
 	<a class="pull-left q-a">
      <div class="q-icon">Q</div>
    </a>
    <div class="media-body">
      <h4 class="media-heading">I am refreshing my Google Page Report but it still shows the old value?</h4>
		Although we try to prevent the Google PageSpeed report from being cached, sometimes Google caches your old report data. Please do not refresh it for 1 hour and then try again.
    </div>
  </li>
  <li class="media">
 	<a class="pull-left q-a">
      <div class="q-icon">Q</div>
    </a>
    <div class="media-body">
      <h4 class="media-heading">Why I can't get maximum points on some of the Google PageSpeed report parameters?</h4>
		Although NitroCache improves all the page speed parameters dramatically, some parameters of your theme may require manual intervention in order to achieve maximum or close to maximum score. Such parameters can be CSS, HTML and JavaScript minifications, image optimization and others, that are referenced in the TPL files of your theme and NitroCache cannot manipulate them programatically. You can either talk to your developer on this or use our premium custom services to do this for you.
    </div>
  </li>

</ul>

<style>
.q-list a.q-a:hover {
	text-decoration:none;
	cursor:default;
}

.q-list .media-body {
	color:#555;
	font-size:12px;
	font-weight:normal;	
	display: table-cell;
	vertical-align: middle;
	height: 64px;
}

.q-list .media-heading {
	color:#333;
	font-weight:normal;	
	font-size:18px;
	margin-top:3px;
}

.q-icon {
	width:64px;
	height:64px; 
	background: #cecece;
	border-radius: 60px;
	color: white;
	line-height: 64px;
	text-align: center;
	font-size: 24px;
}

.iHaveDoneThis {
	font-size:12px;	
}

</style>

<script type="text/javascript">
$('.q-list .q-icon').each(function(index, element) {
    $(this).html('Q'+(index+1));
});
</script>
