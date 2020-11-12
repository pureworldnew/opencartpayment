$(".length_content label").click(function(){
$(this).parent().find("label.selected").removeClass("selected");
$(this).addClass("selected");
});
//Radio button values change
var from;
$(".options .ig_Weight select").change(function(){
    from = $(".ig_Weight select option:selected").text();
    
    $(".options .ig_Selectweight .option_container label").each(function (index,value) {
        var me = $(this);
        var sel_val = $(this).text();
        var value = parseFloat(sel_val);
        var to = sel_val.replace(/[\d.]/g, '');
        $.ajax({
            type: "POST",
            url: "index.php?route=product/product/weightId",
            data: {'value':value, 'from':from, 'to':to,},
            dataType: 'json',
            success:function(resp) {
                me.html(resp);
            }
            })
    });
});

$(".options .ig_Units select").change(function(){
    from = $(".ig_Units select option:selected").text();
    
    $(".options .ig_Selectwidth .option_container label, .options .ig_SelectOveralllength .option_container label").each(function (index,value) {
        var me = $(this);
        var sel_val = $(this).data("val");
        var value = parseFloat(sel_val);
        var to = sel_val.replace(/[\d.]/g, '');
        $.ajax({
            type: "POST",
            url: "index.php?route=product/product/lengthId",
            data: {'value':value, 'from':from, 'to':to,},
            dataType: 'json',
            success:function(resp) {
                me.html(resp);
            }
            })
    });
});