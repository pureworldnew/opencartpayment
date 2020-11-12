var GroupProduct={
    obj:{},
    run:1,
    selectedOption:function(){
        var that=this;
		$('#cart-button-display').hide();
		$('#loading-display').show();
		
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
            selOpt[i]=that.clean(name)+'~'+value;
            i++;
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
		$('#cart-button-display').hide();
		$('#loading-display').show();
        var selOpt=this.selectedOption();
        var groupbyvalue=$('.grouped_product_select').val();
        var gpsku=$('.gpsku').val();
//        alert(gpsku);
        var groupbyname=$('.grouped_product_select>option[value="'+groupbyvalue+'"]').text();
        var that=this;
        $.ajax({
            url: 'index.php?route=product/product_grouped/getGroupOptions',
            beforeSend: function() {
                $(".gp-loader").show();
            },
            complete: function() {
                $(".gp-loader").hide();
            },
            type: 'post',
            dataType: 'json',
            data: {
                'selChoice':selOpt,
                'group_indicator': $("#group_indicator").data('group_indicator'),
                'groupbyname':groupbyname,
                'product_id':groupbyvalue
            },
            success: function(resp) {
                $(".options").html(resp.options);
                if(typeof(that.obj)!=='undefined'){
                    if(that.obj.length>0){
                        that.obj.length=0;
                    }
                }
                $('.options select').fancySelect();
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
        if ($('.ig_Units').children().length > 0) {
            unit_conversion = $("#get-unit-data:visible").find('option:selected').val();
            unit_conversion_text = $("#get-unit-data:visible").find('option:selected').html().trim();
        } 
        var groupbyvalue=$('.grouped_product_select').val();
        groupbyvalue=$('.grouped_product_select>option[value="'+groupbyvalue+'"]').text();
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
                'group_indicator': $("#group_indicator").data('group_indicator'),
                'selChoice':selOpt,
                'groupbyvalue':groupbyvalue
            },
            success: function(resp) { 
                if(typeof(resp.error)!=='undefined'){
                    GroupProduct.obj=thisObj;
                    if(GroupProduct.run == 2){
                        //that.errorMail();
                    }
                    that.changeOption();
                }else{
                    if(typeof(that.obj)!='undefined'){
                        that.obj.length=0;
                    }

                    // Update Options First
                    var groupbyvalue=$('.grouped_product_select').val();                                                                                                                                                                      
                    var groupbyname=$('.grouped_product_select>option[value="'+groupbyvalue+'"]').text();
                    $.ajax({
                        url: 'index.php?route=product/product_grouped/getGroupOptions',
                        beforeSend: function() {
                            $(".gp-loader").show();
                        },
                        complete: function() {
                            $(".gp-loader").hide();
                        },
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'selChoice':selOpt,
                            'group_indicator': $("#group_indicator").data('group_indicator'),
                            'groupbyname':groupbyname,
                            'product_id':resp.product_id
                        },
                        success: function(resp) {
                            $(".options").html(resp.options);
                            if(typeof(that.obj)!=='undefined'){
                                if(that.obj.length>0){
                                    that.obj.length=0;
                                }
                            }
                            $('.options select').fancySelect();
                        }
                    });
                    // End of Update Options

                    that.replaceOptionIds(resp.op_ids);
                    $(".options").html(resp.option_data);
                    //manish
                    $('.options select').fancySelect();
                    //manish//
                    $('#review').html('');
                    $('#review').load('index.php?route=product/product/review&product_id=' + resp.product_id);
					$('#article').html('');
                    $('#article').load('index.php?route=product/product/article&product_id=' + resp.product_id);
                    that.getRelated(resp.product_id,1,"#wwell","#wwell_prods");
                    that.getRelated(resp.product_id,0,"#related","#related_prods");
                  
                    $('#qa').html('');
                    $('#qa').load('index.php?route=product/product/question&product_id=' + resp.product_id);
                    $("#product_name").text(resp.name);
					$(".quantity").val(resp.minimum);
                    $("#item_number").text(resp.sku);
                    $("#show_stock").html(resp.stock_status);
					$( ".date_available" ).remove();
					$("#show_stock").after(resp.frontend_date_available);
					//$(".date_available").html(resp.date_available);
                    //console.log( $(".stock_quick").html() );
                    $(".stock_quick").html(resp.stock_status);
                    $("#refrence_number").text(resp.model);
                   $(".iframe-rwd").html(resp.description);
                    if(resp.image === null){
                        $("#image").attr('src', "catalog/view/theme/default/image/no_product.jpg");
                    }else{
                        $("#image").attr('src', resp.image);
                    }
					$("#additionalimages").html(resp.additional_images);
                    $("#image").attr('title', resp.name);
                    $(".custom_product").remove();
                    $("#image").after(resp.custom_product);
                    $(".cloud-zoom > img").attr('src', resp.image);
                    $(".cloud-zoom").attr('title', resp.name);
                    $(".cloud-zoom").attr('href', resp.large_image);
                    $("#tab-attribute").html(resp.attribute_html);
                    $("#review_status").html("<span class='flr'><img src='catalog/view/theme/default/image/stars-" + resp.rating + ".png' alt=" + resp.reviews + " /><a id='tabs2' onclick=$(a[href=\'#tab-review\']).trigger(\'click\');'>(" + resp.reviews + ")</a></span>");
                    $("a[href='#tab-review']").text(resp.tab_review);
                    $("a[href='#tab-qa']").text(resp.text_tab_qa);
//                    $(".clearfix.img-box2").html(resp.add_image_data);
                    //$("#price-update").text(resp.price);
                    $("#unit_dis").text(resp.unit);
                    $("#product_id_change").val(resp.product_id);
                    $("ul.update_discount_price_group").html(resp.get_product_discount);
                    $(".ig_Units").html(resp.product_unit_data_ajax);
                    $("#base_price_input").val(resp.base_price);
					$(".thumbnails").html(resp.additional_images);
                    that.updatePrice();
                    that.addUnit();
                    $(".gp-loader").hide();
                    $('.cloud-zoom').CloudZoom();
                    $('.colorbox').colorbox({
                        overlayClose: true,
                        opacity: 0.5,
                        rel: "colorbox"
                    });
                }
            }
        });
    },
    /* Update Price : Quantity change, Unit change */
    updatePrice:function(){
        var that=this;
        var p_id = $('input[name="product_id"]').val();
        var base_price = $("#base_price_input").val();
        var quantity = $(".quantity:visible").val();
        var unit_type = $("#get-unit-data:visible").find('option:selected').attr('data-value');
        var simplePrice = $(".top-gap").next().find(".price-new").text();
        var unit_fullName = $("#get-unit-data:visible").find('option:selected').attr('name');
        var plural_unit = $("#plural_unit").val();
        var conversion_price = $("#get-unit-data:visible").find('option:selected').val();
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
                $("#compare_pro").attr('onclick','addToCompare('+p_id+')');
                $('.unit_conversion_values').val(unit_type);
                $(".product-block").find(".price-new").html(resp.calc_price);
                if(resp.discount_quantity){
                	$(".product-block").find("#quantity_span").html(resp.discount_quantity);
				}else{
					$(".product-block").find("#quantity_span").html(resp.quantity);
				}
				//$(".product-block").find("#quantity_span").html(resp.discount_quantity);
                $(".product-block").find("#unit_dis").html(resp.unit_fullName);
                var quan = $(".quantity").val();
                var prodOption = $(".ig_MetalType").find("#get-unit-data:visible").find('option:selected').text();
//                console.log(prodOption);
                if(resp.unit_bulk_pricing){
                   that.updateDiscountBox(resp.unit_bulk_pricing);
                }
                var helpText = that.getHelpText(quan, prodOption);
                $('.option_tooltip:visible').attr('data-original-title', helpText);
				$('#converstion_string_display').html(resp.converstion_string);
				default_conversion_value_name = $.trim($("#default_conversion_value_name").val());
				unit_fullName = $.trim($("#get-unit-data:visible").find('option:selected').attr('name'));
				if(default_conversion_value_name != unit_fullName){
					$('#converstion_string_display').show();
				}else{
					$('#converstion_string_display').hide();
				}
				
                refrshTooltip();
				$('#loading-display').hide();
				$('#cart-button-display').show();
				if(resp.unit_convertion_enable == 0){
					$('#get-unit-data').remove();
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
        var coversion_id = $("#get-unit-data:visible").find('option:selected').attr('data-value');
        $('.unit_conversion_values').val(coversion_id);
		
		
    },
    getHelpText:function(quan, prodOption) {
        var default_option_price = $('#get-unit-data option:eq(0)').val();
        var default_option_text = $('#get-unit-data option:eq(0)').text();
        var requested_unit_price = $('#get-unit-data > option:contains(' + prodOption + ')').val();
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
    },
    getRelated:function(pid,mode,selector,main_selector='.related-products'){
        $.ajax({
            url: 'index.php?route=product/product_grouped/getRelated&related='+mode+'&product_id=' + pid,
            type: 'get',
            dataType: 'html',
            success: function(resp) {
                if (resp == ""){
                    //console.log(selector + " NO");
                    $(selector).css('display','none');
                    $(main_selector).css('display','none');
                    //$("#related").css('display','none');
                    return;
                }
                //var jcarousel = $('#wwell').jcarousel();
                $(main_selector).css('display','block');
                $(selector).css('display','block');
                
                
                //$("#related").css('display','block');
                var jcarousel = $(selector).jcarousel();
                jcarousel
                .html(resp);

            // Reload carousel
            jcarousel
                .jcarousel('reload');
                //console.log(jcarousel);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
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
    if ($('#get-unit-data').length > 0) {
        GroupProduct.updatePrice();
        GroupProduct.addUnit();
    }
    
	var default_conversion_value_name = $("#get-unit-data:visible").find('option:selected').attr('name');
		$('#default_conversion_value_name').val(default_conversion_value_name);
		
    $('.quantity, .visible-phone > .quantity').live('blur',function() {
        GroupProduct.updatePrice();
    });
    
    $("#get-unit-data").live('change', function() {
        GroupProduct.addUnit();
        GroupProduct.updatePrice();
    });
    
    $(".grouped_product_select").live('change', function() {
        GroupProduct.run=0;
        GroupProduct.changeOption();
    });
    
    $('.option select').live('change',function(){
        GroupProduct.run=0;
        GroupProduct.changeOption($(this));
    });
    
    $('.colorbox').colorbox({
        overlayClose: true,
        opacity: 0.5,
        rel: "colorbox"
    });
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
					
					if (json['error']['minimum']) {
                   // alert(json['error']['minimum']);
				   $('#notification').html('<div class="alert alert-danger alert-dismissible" role="alert" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + json['error']['minimum'] + '</div>');
                }
                }

                if (json['success']) {
                    $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                    $('.success').fadeIn('slow');
					 if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    $('#cart-total').html("");
				}else{
					$('#cart-total').html(json['total']);
				}
					$('#cart-total-desktop').html(json['total_desktop']);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            }
        });
    });
});