<div class="row-fluid">
  <div class="span8">
    <div class="box-heading premiumServicesHeading">
      <h1><i class="icon-briefcase"></i> Premium Services</h1>
      <ul class="nav nav-tabs absoluteTabs">
      	<li class="active"><a href="#modulespecific" class="moduleSpecificServicesMenuItem" data-toggle="tab">Module-specific Premium Services</a></li>
      	<li><a href="#allservices" class="allServicesMenuItem" data-toggle="tab">All Premium Services</a></li>
      </ul>
    </div>
    <div class="box-content premium-widget">
    	<div class="tab-content">
        	<div id="modulespecific" class="tab-pane active">
    		<iframe style="border:none; width:100%;" onload="$(this).height($(this).parent().parent().parent().height());"></iframe>            
            </div>
        	<div id="allservices" class="tab-pane">
    		<iframe style="border:none; width:100%;" onload="$(this).height($(this).parent().parent().parent().height())"></iframe>            
            </div>
    	</div>
    </div>
  </div>
  <div class="span4">
    <div class="box-heading">
      <h1><i class="icon-info-sign"></i> Quality and Service</h1>
    </div>
    <div class="box-content">
    	<p>
        The compiled list of premium services are common often-requested solutions for problems we perform for our customers. When you choose a service, you will be securely redirected to PayPal to complete the payment.
    	</p>
        
        <p>If you have not had your developer specifically hired to optimize your site speed, the chances are that your theme and server are not optimized. Although this module does a great job, there are many theme-specific and server-specific optimizations that could improve site loading speed, minimize your server bandwidth, memory and disk space usage.</p>
        <p>
        If you have questions related to these premium services or to other development services, you can <a href="mailto:sales@isenselabs.com?subject=Premium Services Question" target="_blank" class="btn btn-small">contact sales department</a> or email us at sales@isenselabs.com
        </p>
    </div>
  </div>
</div>

<script>
$('.premiumServicesMenuItem,.moduleSpecificServicesMenuItem').click(function() {
	$('#modulespecific iframe').attr('src','//isenselabs.com/external/premiumservices/?pid=12658&info=true');
});
$('.allServicesMenuItem,.premiumServicesMenuItem').click(function() {
	$('#allservices iframe').attr('src','//isenselabs.com/external/premiumservices/?pid=12658&info=true&othersfirst=true');
});
</script>

<style>
.premiumServicesHeading {
	position:relative;	
}

.absoluteTabs {
	position: absolute;
	right: 15px;
	top: 6px;
	font-size:11px;
}

.absoluteTabs li > a {
	font-size: 11px;
	color:#bbb;	
}

.absoluteTabs li > a:hover {
	color:#000;
}
</style>