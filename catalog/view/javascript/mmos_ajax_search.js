$(document).ready(function () {
    $('#search input[name=\'search\']').autocomplete_ajax({
        'source': function (request, response) {
            $.ajax({
                url: String(document.location.protocol)+'//'+String(document.location.hostname)+'/index.php?route=module/mmos_ajax_search&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'] + " (" + item['model'] + ")",
                            value: item['product_id'],
                            href: item['href'],
                            image: item['image'],
                            price: item['price'],
                            special: item['special']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
          	var Url_product = $("<div/>").html(item['href']).text();
            location = Url_product;
        }
    });
});


// Autocomplete */
(function ($) {
    $.fn.autocomplete_ajax = function (option) {
        return this.each(function () {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Focus
//            $(this).on('focus', function () {
//                this.request();
//            });

            // Blur
            $(this).on('blur', function () {
                setTimeout(function (object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('keydown', function (event) {
                switch (event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function (event) {
                event.preventDefault();

                value = $(event.target).closest('tr').attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            }

            // Show
            this.show = function () {
                var pos = $(this).position();

                $(this).siblings('table.dropdown-menu').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });

                $(this).siblings('table.dropdown-menu').show();
            }

            // Hide
            this.hide = function () {
                $(this).siblings('table.dropdown-menu').hide();
            }

            // Request
            this.request = function () {
                clearTimeout(this.timer);

                this.timer = setTimeout(function (object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            }

            // Response
            this.response = function (json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['value']] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i]['category']) {
                            html += '<tr data-value="' + json[i]['value'] + '" style="cursor: pointer">';
							if(json[i]['image']){
                            html += '<td><img src="' + json[i]['image'] + '"></td>';
							}
                            html += '<td style="vertical-align: middle;">' + json[i]['label'] + '</td>';
                            if (json[i]['special'] === false) {
                                html += '<td class="text-right" style="vertical-align: middle;">' + json[i]['price'] + '</td>';
                            } else {
                                html += '<td class="text-right" style="vertical-align: middle;"><del>' + json[i]['price'] + '</del> - <span class="text-info">' + json[i]['special'] + '</span></td>';
                            }
                            html += '</tr>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('table.dropdown-menu').html(html);
            }
            
            $(this).after('<table class="dropdown-menu table"></table>');
            $(this).siblings('table.dropdown-menu').delegate('tr', 'click', $.proxy(this.click, this));
        });
    }
})(window.jQuery);