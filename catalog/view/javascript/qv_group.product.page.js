var GroupProduct={
    obj:{},
    run:1,
    selectedOption:function(){
        var that=this;
		$('#cboxContent #cart-button-display').hide();
		$('#cboxContent #loading-display').show();
		
        var selOpt=[];var name,value;
        var optionObj=$('.options>.option');
        var i=0;
        optionObj.each(function(){
            if(typeof(that.obj)!='undefined'){
                if(that.obj.length>0){
                    if($(this).find('select').attr('name')==that.obj.attr('name')){
                        return false;
                    }
                }
            }
            name=$.trim($(this).find('b').text());
            value=$.trim($(this).find('select>option:selected').html());
			if(jQuery.inArray( that.clean(name)+'~'+value, selOpt ) == -1){
					selOpt[i]=that.clean(name)+'~'+value;
					i++;
			}
           // selOpt[i]=that.clean(name)+'~'+value;
            
        });
        if(selOpt.length>0){
            return selOpt;
        }
    },
    clean:function(v){
        return $.trim(v.replace(':',''));
    },
    /* Change produt options */ 
    changeOption:function(thisObj){
        if(this.run==2){
            this.run=0;
            return false;
        }else{
            this.run+=1;
        }
		$('#cboxContent #cart-button-display').hide();
		$('#cboxContent #loading-display').show();
        var selOpt=this.selectedOption();
        var groupbyvalue=$('.qv_grouped_product_select').val();
		var gpsku=$('.gpsku').val();
        var groupbyname=$('.qv_grouped_product_select>option[value="'+groupbyvalue+'"]').text();
		
        var that=this;
        $.ajax({
            url: 'index.php?route=product/product_grouped/getGroupOptions',
           
            type: 'post',
            dataType: 'json',
            data: {
                'selChoice':selOpt,
                'group_indicator': $("#cboxContent #group_indicator").data('group_indicator'),
                'groupbyname':groupbyname,
                'product_id':groupbyvalue
            },
			 beforeSend: function() {
                $(".gp-loader").show();
            },
            complete: function() {
                $(".gp-loader").hide();
            },
            success: function(resp) {
            	var popupselect = "#cboxContent ";
                $(popupselect+".options").html(resp.options);
                if(typeof(that.obj)!=='undefined'){
                    if(that.obj.length>0){
                        that.obj.length=0;
                    }
                }
                $(popupselect+'.options select').fancySelect();
                that.updateProduct(thisObj);
            }
        });
    },
    /* Update product details on change of product options*/
    updateProduct:function(thisObj){
        var that=this;
        var quantity = $("#quantity_span").html();
        var unit_conversion = '';
        var unit_conversion_text = '';
        if ($('.qv_ig_Units').children().length > 0) {
            unit_conversion = $("#qv_get-unit-data:visible").find('option:selected').val();
            unit_conversion_text = $("#qv_get-unit-data:visible").find('option:selected').html().trim();
        } 
		//alert(unit_conversion_text);
        var groupbyvalue=$('.qv_grouped_product_select').val();
        groupbyvalue=$('.qv_grouped_product_select>option[value="'+groupbyvalue+'"]').text();
        var selOpt=that.selectedOption();
        $.ajax({
            url: 'index.php?route=product/product_grouped/getCombinationData',
            beforeSend: function() {
                $(".gp-loader").show();
            },
            complete: function() {
                $(".gp-loader").hide();
            },
            type: 'post',
            dataType: 'json',
            data: {
                "quantity": quantity,
                "unit_conversion": unit_conversion,
                'unit_conversion_text': unit_conversion_text,
                'group_indicator': $("#cboxContent #group_indicator").data('group_indicator'),
                'selChoice':selOpt,
                'groupbyvalue':groupbyvalue,
				'is_quick_order':1
            },
            success: function(resp) {
                if(typeof(resp.error)!=='undefined'){
                    GroupProduct.obj=thisObj;
                    if(GroupProduct.run == 2){
                        that.errorMail();
                    }
                    that.changeOption();
                }else{
                    if(typeof(that.obj)!='undefined'){
                        that.obj.length=0;
                    }
                    that.replaceOptionIds(resp.op_ids);
                    
                    var popupselect = "#cboxContent ";
                    
                    $(popupselect+".options").html(resp.option_data);
                    //manish
                    $(popupselect+'.options select').fancySelect();
                    //manish//
                    $(popupselect+'#review').html('');
                    $(popupselect+'#review').load('index.php?route=product/product/review&product_id=' + resp.product_id);
                    $(popupselect+'#qa').html('');
                    $(popupselect+'#qa').load('index.php?route=product/product/question&product_id=' + resp.product_id);
                    $(popupselect+"#product_name").text(resp.name);
                    $(popupselect+".model_quick").text(resp.model);
                    $(popupselect+"#show_stock").html(resp.stock_status);
                    $(popupselect+"#refrence_number").text(resp.model);
                   $(popupselect+".iframe-rwd").html(resp.description);
                    if(resp.image === null){
                        $(popupselect+"#image").attr('src', "catalog/view/theme/default/image/no_product.jpg");
                    }else{
                        $(popupselect+"#image").attr('src', resp.image);
                    }
					$(popupselect+"#additionalimages").html(resp.additional_images);
                    $(popupselect+"#image").attr('title', resp.name);
                    $(popupselect+".cloud-zoom > img").attr('src', resp.image);
                    $(popupselect+".cloud-zoom").attr('title', resp.name);
                    $(popupselect+".cloud-zoom").attr('href', resp.large_image);
                    $(popupselect+"#tab-attribute").html(resp.attribute_html);
                    $(popupselect+"#review_status").html("<span class='flr'><img src='catalog/view/theme/default/image/stars-" + resp.rating + ".png' alt=" + resp.reviews + " /><a id='tabs2' onclick=$(a[href=\'#tab-review\']).trigger(\'click\');'>(" + resp.reviews + ")</a></span>");
                    $(popupselect+"a[href='#tab-review']").text(resp.tab_review);
                    $(popupselect+"a[href='#tab-qa']").text(resp.text_tab_qa);
//                    $(".clearfix.img-box2").html(resp.add_image_data);
                    //$("#price-update").text(resp.price);
                    $(popupselect+"#unit_dis").text(resp.unit);
                    $(popupselect+"#product_id_change").val(resp.product_id);
                    $(popupselect+"ul.update_discount_price_group").html(resp.get_product_discount);
                    $(popupselect+".qv_ig_Units").html(resp.product_unit_data_ajax);
                    $(popupselect+"#base_price_input").val(resp.base_price);
					$(popupselect+"#additionalimages").html(resp.additional_images);
                    that.updatePrice();
                    that.addUnit();
                    $(popupselect+".gp-loader").hide();
                    /*$('.cloud-zoom').CloudZoom();
                    $('.colorbox').colorbox({
                        overlayClose: true,
                        opacity: 0.5,
                        rel: "colorbox"
                    });*/
                }
            }
        });
    },
    /* Update Price : Quantity change, Unit change */
    updatePrice:function(){
        var that=this;
        var p_id = $('input[name="product_id"]').val();
        var base_price = $("#base_price_input").val();
        var quantity = $("#qv_qty").val();
        var unit_type = $("#qv_get-unit-data:visible").find('option:selected').attr('data-value');
        var simplePrice = $(".top-gap").next().find(".price-new").text();
        var unit_fullName = $("#qv_get-unit-data:visible").find('option:selected').attr('name');
        var plural_unit = $("#plural_unit").val();
        var conversion_price = $("#qv_get-unit-data:visible").find('option:selected').val();
		var default_conversion_value_name = $("#default_conversion_value_name").val();
        $.ajax({
            url: 'index.php?route=product/product/calcPrice2',
            type: 'post',
            dataType: 'json',
            data: {
                "p_id": p_id,
                "simplePrice": simplePrice,
                "base_price": base_price,
                "quantity": quantity,
                "unit_type": unit_type,
                "conversion_price": conversion_price,
                "unit_fullName": unit_fullName,
                "plural_unit": plural_unit,
				"default_conversion_value_name": default_conversion_value_name
            },
            success: function(resp) {
//                console.log(resp);
                $('.unit_conversion_values').val(unit_type);
                $(".product-info").find(".price-new").html(resp.calc_price);
                if(resp.discount_quantity){
                	$(".product-info").find("#quantity_span").html(resp.discount_quantity);
				}else{
					$(".product-info").find("#quantity_span").html(resp.quantity);
				}
				//$(".product-info").find("#quantity_span").html(resp.discount_quantity);
                $(".product-info").find("#unit_dis").html(resp.unit_fullName);
                var quan = $(".quantity").val();
                var prodOption = $(".qv_ig_MetalType").find("#qv_get-unit-data:visible").find('option:selected').text();
//                console.log(prodOption);
                if(resp.unit_bulk_pricing){
                   that.updateDiscountBox(resp.unit_bulk_pricing);
                }
                var helpText = that.getHelpText(quan, prodOption);
                $('.option_tooltip:visible').attr('data-original-title', helpText);
				$('#converstion_string_display').html(resp.converstion_string);
				default_conversion_value_name = $.trim($("#default_conversion_value_name").val());
				unit_fullName = $.trim($("#qv_get-unit-data:visible").find('option:selected').attr('name'));
				if(default_conversion_value_name != unit_fullName){
					$('#converstion_string_display').show();
				}else{
					$('#converstion_string_display').hide();
				}
				
                refrshTooltip();
				$('#cboxContent #loading-display').hide();
				$('#cboxContent #cart-button-display').show();
				if(resp.unit_convertion_enable == 0){
					//$('#qv_get-unit-data').remove();
				}
              
            }
        });
    },//update discount 
    updateDiscountBox:function(discountObject){
        $.each(discountObject, function(index, data){
                $(".update_discount_price_group").children().eq(index).find(".scale-quantity").html(data.quantity);
                $(".update_discount_price_group").children().eq(index).find(".scale-price").html(data.price);
            });
    },
    /* Update units : units change, Product change */
    addUnit:function(){
        var coversion_id = $("#qv_get-unit-data:visible").find('option:selected').attr('data-value');
        $('.unit_conversion_values').val(coversion_id);
		
		
    },
    getHelpText:function(quan, prodOption) {
        var default_option_price = $('#qv_get-unit-data option:eq(0)').val();
        var default_option_text = $('#qv_get-unit-data option:eq(0)').text();
        var requested_unit_price = $('#qv_get-unit-data > option:contains(' + prodOption + ')').val();
        var resUnits = quan * (requested_unit_price / default_option_price).toFixed(2);
        if (quan == resUnits) {
            return 'Use this menu to calculate prices in different units';
        } else {
            return quan + " " + prodOption + " = " + resUnits + " " + default_option_text;
        }
    },
    replaceOptionIds:function(op_ids){
        var i=0;
        var that=this;
        $.each(op_ids, function(key, data){
            $.each(data, function(index, opt_data){
                var opt_name = index.replace(' ','');
                var opt_product_id = opt_data.product_option_id;
                var opt_value_name = opt_data.option_value.name;
                var opt_value_product_id = opt_data.option_value.product_option_value_id;

                $('.ig_'+opt_name+' > select').attr('name','option['+opt_product_id+']');
                $('.ig_'+opt_name+' option:selected').attr('value',opt_value_product_id);
            });
        });
        $('.options select').fancySelect();
    },
    errorMail:function(){
        var pathname = window.location.pathname;
        $.ajax({
            url: 'index.php?route=product/product_grouped/sendErrorMail',
            type: 'post',
            dataType: 'json',
            data: {
                "current_path": pathname
            }
        });
    }
}
function refrshTooltip() {
            $(".option_tooltip").tooltip({
                show: {
                    effect: "slideDown",
                    delay: 250
                }
            });
            $('.container-fluid').not('.option_tooltip:visible').click(function(event) {
                $('.option_tooltip:visible').tooltip('hide');
            });
        }
/** Events starts here */
$(document).ready(function() {

    GroupProduct.changeOption(false);
    if ($('#qv_get-unit-data').length > 0) {
        GroupProduct.updatePrice();
        GroupProduct.addUnit();
    }
    
	var default_conversion_value_name = $("#qv_get-unit-data:visible").find('option:selected').attr('name');
		$('#default_conversion_value_name').val(default_conversion_value_name);
		
    $('.quantity, .visible-phone > .quantity').live('blur',function() {
        GroupProduct.updatePrice();
    });
    
    $("#qv_get-unit-data").live('change', function() {
        GroupProduct.addUnit();
        GroupProduct.updatePrice();
    });
    
    $(".qv_grouped_product_select").live('change', function() {
        GroupProduct.run=0;
        GroupProduct.changeOption();
    });
    
    $('.option select').live('change',function(){
        GroupProduct.run=0;
        GroupProduct.changeOption($(this));
    });
    
   /* $('.colorbox').colorbox({
        overlayClose: true,
        opacity: 0.5,
        rel: "colorbox"
    });*/
    $('select[name="profile_id"], input[class="quantity" name="quantity"]').change(function() {
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name="product_id"], input[class="quantity" name="quantity"], select[name="profile_id"]'),
            dataType: 'json',
            beforeSend: function() {
                $('#profile-description').html('');
            },
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();
                if (json['success']) {
                    $('#profile-description').html(json['success']);
                }
            }
        });
    });
	$('.button-cart').bind('click', function() {
    var quan = $('.quantity').val();
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('.product-info input[type=\'text\']:visible,#get-unit-data:visible.find("option:selected").attr("data-value"), \n\, .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();
                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                        }
                    }

                    if (json['error']['profile']) {
                        $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                    }
                }

                if (json['success']) {
                    $('#notification-quick').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                    $('.success').fadeIn('slow');
                    $('#cart-total').html(json['total']);
					$('#cart-total-desktop').html(json['total_desktop']);
                   
                }
            }
        });
    });
    
});