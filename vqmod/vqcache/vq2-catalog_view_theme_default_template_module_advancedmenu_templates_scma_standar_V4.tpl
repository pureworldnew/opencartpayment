<?php  if ($menu) { ?>

        <?php if($loader){ ?>
          <div id="results_loader"><div id="menu-overlay" style=""></div><div id="menu-loading"><img src="<?php echo $loader_image; ?>"></div></div>
          <?php } ?>
      
<div id="advancedm">
  <?php $i=1; ?>
  <div class="box">
    <div class="filter_box">
      <?php if (!empty($values_selected)) {?>
      <?php foreach ($values_selected as $value_sel) {?>
      <?php  $i==1 ? $liclass="first upper" : $liclass="upper";?>
      <dl id="filter_p<?php echo $i; ?>" class="filters opened selected" >
        <dt class="<?php echo $liclass; ?>"><span><em>&nbsp;</em><?php echo $value_sel['dnd']; ?></span><?php echo html_entity_decode($value_sel['tip_code'], ENT_QUOTES, 'UTF-8'); ?></dt>
        <dd class="page_preload"><?php echo $value_sel['html']; ?></dd>
      </dl>
      <?php $i++; } ?>
      <?php } ?>
      <?php if (!empty($values_no_selected)) { 
      ksort($values_no_selected); ?>
      <?php foreach ($values_no_selected as $value_no_select) { ?>
      <?php foreach ($value_no_select as $value_no_sel) { ?>
      <?php  $i==1 ? $liclass="first upper" : $liclass="upper";?>
      <dl id="filter_p<?php echo $i; ?>" class="filters <?php echo $value_no_sel['initval']; ?>">
        <dt class="<?php echo $liclass; ?>"><span><em>&nbsp;</em><?php echo $value_no_sel['name']; ?></span><?php echo html_entity_decode($value_no_sel['tip_code'], ENT_QUOTES, 'UTF-8'); ?></dt>
       <dd class="page_preload"><?php echo $value_no_sel['html']; ?></dd>
      </dl>
      <?php $i++; } ?>
      <?php } ?>
      <?php } ?>
      <dl class="filters">
        <dt class="last"><span>&nbsp;</span></dt>
      </dl>
    </div>
  </div>
</div>
<style type="text/css">
  .button-style{
    position: absolute;
    right: 0px;
    z-index: 999999;
  }
</style>

        <script type="text/javascript">
          function multiselectable(url,id){
           if($("#li"+id).hasClass('active')){
            $("#li"+id).removeClass('active');
           }else{
            $("#li"+id).addClass('active');
           }
              
            var full_url="";
            $(".smenuss").each(function(i){
            if($(this).parent().hasClass('active')){
              $("#advancedm").attr('data-urls','');
            var temp_url=   $(this).attr('data-url');
            var temp_type = temp_url.split('?');
            var temp_sep = '';
            if(temp_type.length > 1)
                temp_sep = temp_type[1];
            full_url += "&"+ temp_sep.replace("filter_att[", "filter_att[");
            }
            });

            //var already_exist=$("#advancedm").attr('data-urls');
            //$("#advancedm").attr('data-urls','');
            $("#advancedm").attr('data-urls',full_url);
          }
          $("#filter_b").click(function(){
            var urls=$("#advancedm").attr('data-urls');

            location.href=window.location.href+urls;
          });
           //Script
          </script>

        <script type="text/javascript">
          // Read a page's GET URL variables and return them as an associative array.
			function getUrlVars()
			{
			    var vars = [], hash;
			    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			    for(var i = 0; i < hashes.length; i++)
			    {
			        hash = hashes[i].split('=');
			        vars.push(hash[0]);
			        vars[hash[0]] = hash[1];
			    }
			    return vars;
			}	
          var ajaxManager = $.manageAjax.create('cacheQueue', { queue: true, cacheResponse: true });	
          function Ajaxmenu(filter){    
          <?php if ($loader){ ?>
          $('#results_loader').show();
          <?php } ?>
          ajaxManager.add({ 
          success:showResponseMenu,  // post-submit callback 
          url: 'index.php?route=module/supercategorymenuadvanced&a=1',
          data: filter,
          type: "GET",
          cache: true
          });
          ajaxManager.add({ 
          success:showResponsedatos,  // post-submit callback 
          url: 'index.php?route=product/asearch&a=1',
          data: filter,
          type: "GET",
          cache: true
          });
          };
          var ajaxManager2 = $.manageAjax.create('cacheQueue', { queue: true, cacheResponse: true });	
          function Ajaxmenup(filter){        
          ajaxManager2.add({ 
          success:showResponsedatos,  // post-submit callback 
          url: 'index.php?route=product/asearch&a=1', 
          data: filter,
          type: "GET",
          cache: true
          });
          };
          function showResponseMenu(responseText, statusText, xhr)  { 
          $('#advancedm').fadeOut('slow', function(){
          $('#advancedm').fadeOut('slow');
          $("#advancedm").replaceWith(responseText).fadeIn("fast");
          });
          }
          function showResponsedatos(responseText, statusText, xhr)  { 
          $('#content').fadeOut('slow', function(){
          $('#content').fadeOut('slow');
          $("#content").replaceWith(responseText).fadeIn("fast", function() {
          <?php if ($scrollto){ ?>
		  $('body,html').animate({scrollTop: 150}, 800); 
          <?php } ?>
           });});
          <?php if ($loader){ ?>
          $('#results_loader').remove();
          <?php } ?>
          }
          $(document).delegate("select.smenu", "change", function(){
          var jThis = $("option:selected", this), dnd, gapush, ajax_url;
          dnd=jThis.metadata().dnd;
          gapush=jThis.metadata().gapush;
          ajax_url=jThis.metadata().ajaxurl;
          if (gapush!="no"){
          var gas_val=gapush.split('@@@@@')	
          _gaq.push(['_trackEvent','SCM', gas_val[0],gas_val[1]])
          }
          <?php if ($is_ajax){ ?> 
          if (history.pushState) {
          History.pushState(null,ajax_url, dnd);
          }else{
          Ajaxmenu(ajax_url);
          }
          <?php }else{ ?>
          window.location.href = dnd;
          <?php } ?>
          return false;
          });
          $(document).delegate("a.smenu", "click", function(){          	
          var jThis = $(this), dnd, gapush, ajax_url;
          dnd=jThis.metadata().dnd; 
          gapush=jThis.metadata().gapush;
          ajax_url=jThis.metadata().ajaxurl;
          var data_id = jThis.attr("data-id");
          if(jThis.hasClass("link_filter_del")){
          	jThis.removeClass("custom_"+data_id);
	        var all_text = $(".custom_"+data_id).map(function(){
	          			return $(this).attr("data-name");
	          }).get();
	          if(all_text.length > 0){
	         	var concate ='&filter_att['+data_id+']=';
		        for(var i =0; i<all_text.length; i++){
					if(i==0){
					 concate +=all_text[i];
					}else{
		             concate +="|"+all_text[i];
		           }
		        }
		       dnd =dnd+concate;
           	   ajax_url = ajax_url+concate; 	 	
	          }
          }else{
          	
          var all_text = $(".custom_"+data_id).map(function(){
          			return $(this).attr("data-name");
          }).get();
          
          	var concate ='';
           for(var i =0; i<all_text.length; i++){
           		concate +="|"+all_text[i];
           }
           dnd =dnd+concate;
           ajax_url = ajax_url+concate;
          }
          if (gapush!="no"){
          var gas_val=gapush.split('@@@@@')	
          _gaq.push(['_trackEvent','SCM', gas_val[0],gas_val[1]])
          }
          <?php if ($is_ajax){ ?> 
	          if (history.pushState) {
	          	History.pushState(null,ajax_url, dnd);
	          }else{
	          	Ajaxmenu(ajax_url);
	          }
          <?php }else{ ?>
          window.location.href = dnd;
          <?php } ?>
          return false; 
          });
          <?php if ($see_more_trigger){ ?>
          $('a.all_filters').trigger('click');
          <?php } ?>
          <?php if ($option_tip){ ?>
          $('img.picker').tipsy({gravity: 's', fade: true}); // Added for Displaying Title of Adv. Layered Menu Imagesmerlin
          <?php } ?>
		<?php if ($scolumn_left){?>
		$(function () {
		if($('#advancedm').length){
			var $sidebar = $("#column-left"),
            $window = $(window),
            offset = $sidebar.offset(),
            topPadding = 10;
			$window.scroll(function () {
            if ($window.scrollTop() > offset.top) {
                if ($window.scrollTop() < ($("footer").offset().top  - $sidebar.height() - topPadding )) {
                    $sidebar.stop().animate({
                        top: $window.scrollTop() - offset.top + topPadding
                    }, 1000);
                } 
            } else {
                $sidebar.stop().animate({
                    top: 0
                });
            }
			});
		}    
		}); 
		<?php } ?> 
		<?php if ($scolumn_right){ ?>
		$(function () {
		if($('#advancedm').length){
			var $sidebar = $("#column-right"),
            $window = $(window),
            offset = $sidebar.offset(),
            topPadding = 10;
			$window.scroll(function () {
            if ($window.scrollTop() > offset.top) {
                if ($window.scrollTop() < ($("footer").offset().top  - $sidebar.height() - topPadding )) {
                    $sidebar.stop().animate({
                        top: $window.scrollTop() - offset.top + topPadding
                    }, 1000);
                } 
            } else {
                $sidebar.stop().animate({
                    top: 0
                });
            }
			});
		}     
		});
		<?php } ?>
		 $(function(){
			$('[rel="popover"]').popover({
			container: 'body',
			html : true, 
			placement : 'right',
			content: function() {
				$(this).attr('id');
				return $($(this).attr('data-popover-content')).html();
				},
			trigger:'manual'
			}).on("mouseenter", function () {
				var _this = this;
				$(this).popover("show");
			$(".popover").on("mouseleave", function () {
				$(_this).popover('hide');
			});
			}).on("mouseleave", function () {
				var _this = this;
				setTimeout(function () {
				if (!$(".popover:hover").length) {
					$(_this).popover("hide");
				}
				}, 300);
			});
		});
		$('[rel="popover"]').on('shown.bs.popover', function () {
		  $('.popover').css('top',parseInt($('.popover').css('top')) + 18 + 'px')
		})
		<?php if ($isresponsive){ ?>
		$(document).ready(function(){ 
		 if ($( window ).width()<= 768){
		   jQuery('#advancedm').respNav({
				label: '<?php echo $menu_name; ?>',
				prependTo:'<?php echo $appendto; ?>',
				duplicate: false,
				init:function(){
				$('dl').not( ".selected").addClass('closed');
				$('a.all_filters').trigger('click');
				}
		 });
		  }
		});
		<?php } ?> 
          </script>
		  
      
<?php  } ?>