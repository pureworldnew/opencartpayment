    
    
        var currentView = $.totalStorage('displayview');
        if (currentView == 'grid') {
            mycustomdispay('grid');
            $('.product-list').hide();
        } else {
            mycustomdispay('list');
              $('.product-grid').hide();
        }
        $('.grid_link_filter').click(function() {
            mycustomdispay('grid');
        });
        $('.list_link_filter').click(function() {
            mycustomdispay('list');
        });

    
    function mycustomdispay(view) {
        if (view == "list") {
            $.totalStorage('displayview', 'list');
            $('.product-grid').addClass('product-list').removeClass('product-grid');
             $('.list_link_filter').addClass("bld");
             $('.grid_link_filter').removeClass("bld");
        } else {
            $.totalStorage('displayview', 'grid');
            $('.product-list').addClass('product-grid').removeClass('product-list');
            $('.grid_link_filter').addClass("bld");
             $('.list_link_filter').removeClass("bld");
        }
    }
    /* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


