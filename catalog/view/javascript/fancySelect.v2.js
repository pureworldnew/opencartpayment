/**
 * This plugin converts select box options into clickable labels/images 
 * @author : Manish,Manu Mahesh
 */
(function($) {
    $.fn.fancySelect = function(options) {
        var defaults = {
            labelDefaults: {
                background: 'linear-gradient(#7993B5, #456A9E, #406192)',
                color: '#fff',
                padding: '5px 15px',
                'text-align': 'center',
                'font-weight': 'normal',
                cursor: 'pointer',
                margin: '5px',
                display : 'inline-block',
                labelOutline: {
                    border: '2px solid #fff',
                    outline: '2px solid #A7B7CE'
                }
            },
            imageDefaults: {
                background: 'none',
                padding: '0',
                height: '35px',
                width: '35px',
                outline: '1px solid #D6D6D6',
                cursor: 'pointer',
                margin: '5px',
                imageOutline : {
                    border: '2px solid #fff',
                    outline: '2px solid #A7B7CE'
                }
            },
            outOfStock :{
                background : 'linear-gradient(#676767, #9e9e9e, #585858)',
                outline : '2px solid #CCC !important'
            }
        };
        var main_ojb = this;
        // Merge options into defaults, recursively
        var settings = $.extend( true, defaults, options );
        var html_template, sub_obj, selected_class,label_title,label_popovertext,i,label_text,popover_class = '';
        main_ojb.css('display', 'none');
        
        $(main_ojb).each(function() {
            sub_obj = this;
            
            $(sub_obj).next('.fancySelectContainer').remove();
            html_template = '';
            html_template += "<div class='fancySelectContainer'>";
            $(this).find('option').each(function() {  // iterate over all options
                label_title ='';
                label_text = '';
                selected_class = '';
                popover_class = '';
                label_popovertext = '';
                if(typeof $(this).attr('qty') != 'undefined' && parseInt($(this).attr('qty')) <= parseInt(0)) {
                    label_text = $(this).text().split("-");
                    label_title = label_text[0].trim();
                    for(i=1; i < label_text.length ;i++) {
                        if(typeof $(this).attr('qty') != 'undefined') {
                            if(parseInt(label_text.length) == parseInt(i+1) ) {
                                label_popovertext += label_text[i].trim();
                            } else {
                                label_popovertext += label_text[i].trim()+'-';
                            }
                        }
                    }
                    popover_class = 'out-of-stock-tooltip'
                } else {
                    label_title = $(this).text().trim();
                }
                
                if (typeof $(this).data('option_value_image') != 'undefined' && $(this).data('option_value_image') != '') {
                    if($(this).is(':selected')) {
                        selected_class =  'imageOutline';
                    }
                    html_template += '<img data-title="'+label_popovertext+'" id="' + $(this).val() + '" alt="' + label_title + '" class="fancySelectImage '+selected_class+' '+popover_class+'" src="' + $(this).data('option_value_image') + '">';
                } else {
                    if($(this).is(':selected')) {
                        selected_class =  'labelOutline';
                    }
                    html_template += '<label data-title="'+label_popovertext+'" id="' + $(this).val() + '" class="fancySelectLabel '+selected_class +' '+popover_class+'">' + label_title + '</label>';
                }
            });
            html_template += "</div>";
            $(sub_obj).after(html_template);
        });
        $(".out-of-stock-tooltip").tooltip ({
                        placement: "right",
                        show: {
                            delay: 250
                        }
                    });
        $('.fancySelectLabel').css(settings.labelDefaults);
        $('.labelOutline').css(settings.labelDefaults.labelOutline);
        $('.fancySelectImage').css(settings.imageDefaults);
        $('.imageOutline').css(settings.imageDefaults.imageOutline);
        $(".out-of-stock-tooltip").css(settings.outOfStock);
        
        $('.fancySelectLabel, .fancySelectImage').click(function(){
            
            var clicked_element_id = $(this).attr('id');
            if(typeof $(this).parent().prev('select').val() != 'undefined' && clicked_element_id != $(this).parent().prev('select').val()) {
                $(this).parent().prev('select').val(clicked_element_id);
                //$(this).parent().prev('select').trigger("change");
                $("#button-cart-2").attr("disabled", true);
				GroupProduct.run=0;
			    GroupProduct.changeOption($(this));
                $(this).parent().prev('select').fancySelect();
            }
            return;
        });
    };
}(jQuery));

