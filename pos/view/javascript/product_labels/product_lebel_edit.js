function get_uri() {
    return uri = "&pagew=" + $('input[name="pw"]').val(), uri += "&pageh=" + $('input[name="ph"]').val(), uri += "&labelw=" + $('input[name="lw"]').val(), uri += "&labelh=" + $('input[name="lh"]').val(), uri += "&numw=" + $('input[name="nw"]').val(), uri += "&numh=" + $('input[name="nh"]').val(), uri += "&hspacing=" + $('input[name="hspace"]').val(), uri += "&vspacing=" + $('input[name="vspace"]').val(), isNaN($('input[name="margint"]').val()) || "" == $('input[name="margint"]').val() ? (uri += "&margint=auto", $("input[name='margint']").val("auto")) : uri += "&margint=" + $('input[name="margint"]').val(), isNaN($('input[name="marginl"]').val()) || "" == $('input[name="marginl"]').val() ? (uri += "&marginl=auto", $("input[name='marginl']").val("auto")) : uri += "&marginl=" + $('input[name="marginl"]').val(), uri += "&saveas_template=" + $('input[name="saveas_template"]').val(), $('input[name="rounded"]').prop("checked") ? uri += "&rounded=1" : uri += "&rounded=0", uri
}

function preview_template() {
    $.ajax({
        url: "index.php?route=module/pos/previewtemplate&token=" + token + get_uri(),
        success: function(a) {
            $("#preview_template_div").html(a)
        }
    })
}

function pl_saveas_template() {
    return "" == $("input[name='saveas_template']").val() ? (alert(error_saveas_template), !1) : ($("input[name='templateid']").val(""), void save_template())
}

function delete_template() {
    return id = $("input[name='templateid']").val(), id == settings.product_labels_default_templ ? (alert(error_delete_template), !1) : void $.ajax({
        url: "index.php?route=module/pos/deletetemplate&token=" + token + "&id=" + id,
        beforeSend: function() {
            $("#deletebutton_template").attr("style", "visibility:hidden"), $("#savebutton_template").attr("style", "visibility:hidden"), $("#printpreviewbutton_template").attr("style", "visibility:hidden;")
        },
        complete: function() {
            $(".loading").remove()
        },
        success: function(a) {
            $(".select_template option[value='" + id + "']").remove(), $("input[name='pw']").val(settings.product_labels_default_pagew), $("input[name='ph']").val(settings.product_labels_default_pageh), $("input[name='lw']").val(""), $("input[name='lh']").val(""), $("input[name='nw']").val(""), $("input[name='nh']").val(""), $("input[name='hspace']").val(""), $("input[name='vspace']").val(""), $("input[name='margint']").val("auto"), $("input[name='marginl']").val("auto"), $("input[name='saveas_template']").val(""), $("input[name='rounded']").removeAttr("checked"), $("input[name='templateid']").val(""), preview_template()
        }
    })
}

function save_template() {
    $.ajax({
        url: "index.php?route=module/pos/savetemplate&token=" + token + get_uri() + "&id=" + $("input[name='templateid']").val(),
        beforeSend: function() {},
        complete: function() {
            $(".loading").remove()
        },
        success: function(a) {
            $("#savebutton_template").attr("style", "visibility:hidden"), $("#printpreviewbutton_template").attr("style", "visibility:visible;"), "" != $("input[name='saveas_template']").val() && ($(".select_template").append($("<option/>").attr("value", a).text($("input[name='saveas_template']").val())), $("input[name='saveas_template']").val(""), $("input[name='templateid']").val(a), $("select[name='template']").val(a)), preview_template()
        }
    })
}

function preview_label() {
    if (validate_form()) {
        $.ajax({
            url: "index.php?route=module/pos/savelabel&token=" + token + "&action=savelabel&id=1",
            type: "post",
            data: get_label_serialized()
        });
        var a = "index.php?route=" + $("input[name='route']").val() + "&token=" + token + "&sample=1&edit=1&templateid=" + $("select[name='templateid']").val() + "&orientation=" + $("select[name='orientation']").val() + "&labelid=1",
            b = new PDFObject({
                url: a,
                id: "PDFPreview",
                pdfOpenParams: {
                    navpanes: 0,
                    toolbar: 0,
                    statusbar: 0,
                    view: "Fit"
                }
            });
        if (b) {
            var c = b.get("pluginTypeFound");
            if (!c) return $(".warningpdf").html(error_nopdf), $(".warningpdf").css("display", "block"), !1
        }
        var d = b.embed("preview_pdf_label");
        d || ($(".warningpdf").html(error_pdf), $(".warningpdf").css("display", "block"))
    }
}

function delete_label() {
    return id = $("select[name='select_label']").val(), id == settings.product_labels_default_label ? (alert(error_delete_label), !1) : void $.ajax({
        url: "index.php?route=module/pos/deletelabel&token=" + token + "&id=" + id,
        beforeSend: function() {
            $("#deletebutton_label").attr("style", "visibility:hidden"), $("#exportbutton_label").attr("style", "visibility:hidden;margin-top:10px"), $("#savebutton_label").attr("style", "visibility:hidden")
        },
        complete: function() {
            $(".loading").remove()
        },
        success: function(a) {
            $(".select_label option[value='" + id + "']").remove(), $("select[name='select_label']").val(""), select_label()
        }
    })
}

function pl_saveas_label() {
    return "" == $("input[name='saveas_label_name']").val() ? (alert(error_saveas_label), !1) : ($("input[name='labelid']").val(""), void save_label())
}

function get_label_serialized() {
    var a = $("#edit_label_form").find("input, textarea, select").filter(".serializable").serialize(),
        b = $("#edit_label_form").find("input, textarea, select").filter(".value").serialize(),
        c = $("#edit_label_form").find("input, textarea, select").filter(".colorpicker").serialize();
    return c = c.replace(/\#/g, "").replace(/\%23/g, ""), labeldata = a + "&" + b + "&" + c, labeldata
}

function validate_form() {
    if (labeldata = get_label_serialized(), elements = ["rect", "img", "text", "barcode", "list"], errors = new Array, $(".labelinput").removeClass("error"), $("#warninglabel").attr("style", "display:none"), "&&" == labeldata) return !0;
    fill = new Array, color = new Array, h = new Array, w = new Array, y = new Array, x = new Array, img = new Array, text = new Array, fr = new Array, fs = new Array, ff = new Array, type = new Array, a = labeldata.split("&");
    for (var i = a.length - 1; i >= 0; i--) {
        var val = a[i].replace(/%3D/g, "");
        val = decodeURIComponent(val), val = val.replace(/'/g, ""), val = val.replace(/&/g, ""), val = val.replace(/=/g, "='") + "';", eval(val)
    }
    for (var i = 0; i < type.length; i++)
        if (void 0 !== type[i]) {
            for (valx = $("#x" + i).val(), valy = $("#y" + i).val(), valw = $("#w" + i).val(), valh = $("#h" + i).val(), valw = valw.replace("width", "1"), valh = valh.replace("height", "1"), "text" == type[i] && (valx = valx.replace("textw", "1"), valy = valy.replace("texth", "1")), replacex = ["left", "center", "right", "width"], replacey = ["top", "center", "bottom", "height"], key = 0; key < replacex.length; key++) valx = valx.replace(replacex[key], "1"), valy = valy.replace(replacey[key], "1");
            try {
                isNaN(parseFloat(eval(valx + "-" + valx))) && errors.push("x" + i)
            } catch (e) {
                errors.push("x" + i)
            }
            try {
                isNaN(parseFloat(eval(valy + "-" + valy))) && errors.push("y" + i)
            } catch (e) {
                errors.push("y" + i)
            }
            switch ($.inArray(type[i], elements)) {
                case 0:
                case 3:
                    try {
                        isNaN(parseFloat(eval(valh + "-" + valh))) && errors.push("h" + i)
                    } catch (e) {
                        errors.push("h" + i)
                    }
                    try {
                        isNaN(parseFloat(eval(valw + "-" + valw))) && errors.push("w" + i)
                    } catch (e) {
                        errors.push("w" + i)
                    }
            }
            switch ($.inArray(type[i], elements)) {
                case 1:
                    try {
                        $("#img" + i).val() || errors.push("img" + i)
                    } catch (e) {
                        errors.push("img" + i)
                    }
                    try {
                        isNaN(parseFloat(eval(valw + "-" + valw))) && isNaN(parseFloat(eval(valh + "-" + valh))) && (errors.push("w" + i), errors.push("h" + i))
                    } catch (e) {
                        errors.push("w" + i), errors.push("h" + i)
                    }
                    break;
                case 2:
                case 3:
                    try {
                        $("#text" + i).val() || errors.push("text" + i)
                    } catch (e) {
                        errors.push("text" + i)
                    }
            }
        }
    if (errors.length > 0) {
        for (var i = 0; i < errors.length; i++) $("#" + errors[i]).addClass("error");
        return $("#warninglabel").attr("style", "visibility:visible"), !1
    }
    return !0
}

function save_label() {
    $.ajax({
        url: "index.php?route=module/pos/savelabel&token=" + token + "&saveas_label_name=" + $("input[name='saveas_label_name']").val() + "&id=" + $("select[name='select_label'] option:selected").val(),
        type: "post",
        data: get_label_serialized(),
        beforeSend: function() {},
        complete: function() {
            $(".loading").remove()
        },
        success: function(a) {
            $("#savebutton_label").attr("style", "visibility:hidden"), $("#exportbutton_label").attr("style", "visibility:hidden"), "" != $("input[name='saveas_label_name']").val() && ($(".select_label").append($("<option/>").attr("value", a).text($("input[name='saveas_label_name']").val())), $("input[name='saveas_label_name']").val(""), $("select[name='select_label']").val(a)), preview_label()
        }
    })
}

function select_label() {
 
    $.ajax({
        url: "index.php?route=module/pos/getlabel&token=" + token + "&id=" + $("select[name='select_label'] option:selected").val(),
        type: "post",
        dataType: "json",
        success: function(a) {
            a.data || (a.data = "{}"), elements = jQuery.parseJSON(a.data);
            var b = "";

            for (row = 0, j = 0; j < label_element_type.length; j++) {
                if (b += '<div class="well" style="padding:5px;"> <div class="row">', b += '<div class="col-sm-12">', b += '<legend style="margin-bottom:2px;">' + label_element_name[j] + "</legend>", b += "</div>", b += "</div>", b += '<div class="col-xs-12">', b += '<div class="row" id="element_test">', b += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input-header"><p>' + text_add + "</p></div>", b += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input-header ' + toggle(label_element_type[j], 0) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_font_f + '">' + text_font_f + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header ' + toggle(label_element_type[j], 0) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_font_s + '">' + text_font_s + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header ' + toggle(label_element_type[j], 9) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_font_a + '">' + text_font_a + "</p></div>", b += '<div class="col-xs-3 col-lg-3 oc2-pl-label-input-header ' + toggle(label_element_type[j], 1) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_text + '">' + text_text + "</p></div>", b += '<div class="col-xs-5 col-lg-5 oc2-pl-label-input-header ' + toggle(label_element_type[j], 2) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_img + '">' + text_img + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header "><p data-toggle="tooltip" data-original-title="' + text_tip_xpos + '">' + text_xpos + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header "><p data-toggle="tooltip" data-original-title="' + text_tip_ypos + '">' + text_ypos + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header ' + toggle(label_element_type[j], 5) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_width + '">' + text_width + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header ' + toggle(label_element_type[j], 6) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_height + '">' + text_height + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header ' + toggle(label_element_type[j], 7) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_color + '">' + text_color + "</p></div>", b += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input-header ' + toggle(label_element_type[j], 8) + '"><p data-toggle="tooltip" data-original-title="' + text_tip_fill + '">' + text_fill + "</p></div>", b += "</div>", b += "</div>", default2("elements['numrows']", 0) > 0) {
                    var c = 1;
                    for (i in elements.type) elements.type[i] == label_element_type[j] && (row++, c++, b += add_label_row(row, elements.type[i], i))
                }
                b += '<div class="row" id="tfoot_' + label_element_type[j] + '">', b += '   <div class="col-sm-12" id="tfoot_' + label_element_type[j] + '">', b += '       <button type="button" class="btn btn-default btn-xs" style="margin-bottom:2px;margin-top:10px;" onclick="add_label_element(\'' + label_element_type[j] + "');return false;\">" + text_addnew + " <b>" + label_element_name[j] + "</b></button>", b += " </div>", b += "</div> </div>"
            }

            $("#form_elements").html(b);
             //$(function() {
            $(".labeltext").autocomplete(autocomp_label_elements);
            $(".color").colorPicker(colorpicker_color);
            $(".fill").colorPicker(colorpicker_fill);
            //}),
             $(".labelinput").change(function() {
                "" != $("select[name='select_label']").val() ? $("#savebutton_label").attr("style", "visibility:visible") : $("#savebutton_label").attr("style", "visibility:hidden")
            }), "" != $("select[name='select_label']").val() ? ($("#deletebutton_label").attr("style", "visibility:visible"), $("#exportbutton_label").attr("style", "visibility:inline;margin-top:10px")) : ($("#deletebutton_label").attr("style", "visibility:hidden"), $("#exportbutton_label").attr("style", "visibility:hidden;margin-top:10px")), $("#savebutton_label").attr("style", "visibility:hidden"), preview_label()
        }
    })
}

function add_label_element(a) {

    row++;
    var b = add_label_row(row, a, 0);
    $("#tfoot_" + a).before(b);
    $('select[name="select_label"]').val();
    $("#savebutton_label").attr("style", "visibility:visible");
    $("#text" + row).autocomplete(autocomp_label_elements);
    if(a != "img"){ 
    $("#color"+row).colorPicker(colorpicker_color); 
    $("#fill"+row).colorPicker(colorpicker_fill);
    }

}
function selected_rwaq(a, b) {
    return a == b ? " selected" : "";
}
function add_label_row(a, b, c) {
    return html = '<div class="col-xs-12" >', html += '<div class="row" id="element_' + b + "_" + a + '">', html += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input">', html += '<label class="sr-only" for="type' + a + '">' + text_add + "</label>", html += '<select class="labelinput serializable form-control" name="type[' + a + ']" id="type' + a + '" onchange="$(\'#element_' + b + "_" + a + "').remove();\">", html += '<option value="">' + text_option_delete + "</option>", html += '<option value="' + b + '" selected>' + b + "</option>", html += "</select>", html += "</div>", html += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input ' + toggle(b, 0) + '">', html += '<label class="sr-only" for="ff' + a + '">' + text_font_f + "</label>", html += '<select class="labelinput serializable form-control" name="ff[' + a + ']" id="ff' + a + '">', html += '<option value="helvetica"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "helvetica") + ">Helvetica</option>", html += '<option value="helveticaB"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "helveticaB") + ">Helvetica bold</option>", html += '<option value="helveticaI"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "helveticaI") + ">Helvetica italic</option>", html += '<option value="helveticaBI"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "helveticaBI") + ">Helvetica bold italic</option>", html += '<option value="dejavusans"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "dejavusans") + ">Sans</option>", html += '<option value="cid0cs"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "cid0cs") + ">Arial Unicode</option>", html += '<option value="cid0jp"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "cid0jp") + ">Arial (JP)</option>", html += '<option value="cid0kr"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "cid0kr") + ">Arial (KR)</option>", html += '<option value="freeserif"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "freeserif") + ">Serif Unicode</option>", html += '<option value="times"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "times") + ">Times</option>", html += '<option value="timesB"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "timesB") + ">Times bold</option>", html += '<option value="timesI"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "timesI") + ">Times italic</option>", html += '<option value="timesBI"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "timesBI") + ">Times bold italic</option>", html += '<option value="symbol"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "symbol") + ">Symbol</option>", html += '<option value="ocrb10"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "ocrb10") + ">OCR</option>", html += '<option value="zapfdingbats"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "zapfdingbats") + ">Dingbats</option>", html += '<option value="msungstdlight"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "msungstdlight") + ">MSung Light (Trad.Chinese)</option>", html += '<option value="hysmyeongjostdmedium"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "hysmyeongjostdmedium") + ">MyungJo Medium (Korean)</option>", html += '<option value="kozgopromedium"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "kozgopromedium") + ">Kozuka Gothic(JP Sans)</option>", html += '<option value="kozminproregular"' + selected_rwaq(default2('elements["ff"][i]', "helvetica"), "kozminproregular") + ">Kozuka Mincho(JP Serif)</option>", html += "</select>", html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input ' + toggle(b, 0) + '">', html += '<label class="sr-only" for="fs' + a + '">' + text_font_s + "</label>", html += '<input type="text" class="labelinput serializable form-control" name="fs[' + a + ']" id="fs' + a + '" value="' + default2("elements['fs'][i]", "12") + '">', html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input ' + toggle(b, 9) + '">', html += '<label class="sr-only" for="fr' + a + '">' + text_font_a + "</label>", html += '<input type="text" class="labelinput serializable form-control" name="fr[' + a + ']" id="fr' + a + '" value="' + default2("elements['fr'][i]", "0") + '">', html += "</div>", html += '<div class="col-xs-3 col-lg-3 oc2-pl-label-input ' + toggle(b, 1) + '">', html += '<label class="sr-only" for="text' + a + '">' + text_text + "</label>", html += '<input type="text" class="labelinput serializable labeltext form-control" name="text[' + a + ']" id="text' + a + '" value="' + default2("elements['text'][i]", "") + '" placeholder="' + text_placeholder_text + '">', html += "</div>", html += '<div class="col-xs-5 col-lg-5 ' + toggle(b, 2) + '" style="margin:0px;padding:2px;">', html += '<div class="input-group oc2-pl-label-input" style="margin:0px;padding:0px;"><label class="sr-only" for="img' + a + '">' + text_img + "</label>", html += '<input type="text" class="labelinput serializable form-control" name="img[' + a + ']"  id="img' + a + '" value="' + default2("elements['img'][i]", "") + '" placeholder="' + text_placeholder_img + '">', html += '<span class="input-group-addon oc2-pl-label-input" style="padding-left:4px;padding-right:px;cursor:pointer;" onclick="$(this).blur();select_image(\'img' + a + '\');"><i class="fa fa-folder-open"></i></span>', html += "</div>", html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input">', html += '<label class="sr-only" for="x' + a + '">' + text_xpos + "</label>", html += '<input type="text" class="value labelinput form-control" name="x[' + a + ']" id="x' + a + '" value="' + default2("elements['x'][i]", "", !0) + '" placeholder="' + text_placeholder_xpos + '">', html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input">', html += '<label class="sr-only" for="y' + a + '">' + text_ypos + "</label>", html += '<input type="text" class="value labelinput form-control" name="y[' + a + ']" id="y' + a + '" value="' + default2("elements['y'][i]", "", !0) + '" placeholder="' + text_placeholder_ypos + '">', html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input ' + toggle(b, 5) + '">', html += '<label class="sr-only" for="w' + a + '">' + text_width + "</label>", html += '<input type="text" class="value labelinput form-control" name="w[' + a + ']" id="w' + a + '" value="' + default2("elements['w'][i]", "", !0) + '" placeholder="' + text_placeholder_width + '">', html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input ' + toggle(b, 6) + '">', html += '<label class="sr-only" for="h' + a + '">' + text_height + "</label>", html += '<input type="text" class="value labelinput form-control" name="h[' + a + ']" id="h' + a + '" value="' + default2("elements['h'][i]", "", !0) + '" placeholder="' + text_placeholder_height + '">', html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input ' + toggle(b, 7) + '">', html += '<label class="sr-only" for="color' + a + '">' + text_color + "</label>", html += '<input type="text" class="colorpicker color labelinput form-control" id="color' + a + '" name="color[' + a + ']" value="#' + default2("elements['color'][i]", "000000") + '" />', html += "</div>", html += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input ' + toggle(b, 8) + '">', html += '<label class="sr-only" for="fill' + a + '">' + text_fill + "</label>", html += '<input type="text" class="colorpicker fill labelinput form-control" id="fill' + a + '" name="fill[' + a + ']" value="#' + default2("elements['fill'][i]", "FFFFFF") + '" />', html += "</div>", html += "</div>", html += "</div>", html
}

function select_image(a) {
    $("#modal-image").remove(), $.ajax({
        url: "index.php?route=common/filemanager&token=" + token + "&link=1&target=" + a,
        dataType: "html",
        success: function(a) {
            $("body").append('<div id="modal-image" class="modal">' + a + "</div>"), $("#modal-image").modal("show")
        }
    })
}

function default2(val, def, plussign) {
    try {
        return "undefined" != typeof eval(val) ? (res = eval(val), "undefined" != typeof plussign ? res.replace(/ /g, "+") : res) : def
    } catch (err) {
        return def
    }
}

function toggle(a, b) {
    return toggle.img = ["hide", "hide", "show", "show", "show", "show", "show", "hide", "hide", "hide"], toggle.text = ["show", "show", "hide", "show", "show", "hide", "hide", "show", "hide", "show"], toggle.rect = ["hide", "hide", "hide", "show", "show", "show", "show", "show", "show", "hide"], toggle.barcode = ["hide", "show", "hide", "show", "show", "show", "show", "show", "hide", "hide"], toggle.list = ["show", "hide", "hide", "show", "show", "show", "show", "show", "hide", "hide"], toggle[a][b]
}



function check_update() {
    $.ajax({
        url: "index.php?route=module/pos/checkupdate&token=" + token,
        success: function(a) {
            a && ($(".updateneeded").html(update_needed + " " + this_version + " " + new_version + " " + a + ". " + please_update), $(".updateneeded").css("display", "block"), $("#update_tab").css("display", "inline"), $("#pltabs a:last").tab("show"), get_update_info())
        }
    })
}

function get_update_info() {
    $.ajax({
        url: "index.php?route=module/pos/getupdateinfo&token=" + token,
        success: function(a) {
            a && $("#notes").html(a)
        }
    })
}

function pl_submit_form() {
    for (i = 1; i <= number_h * number_v; i++) 1 != label_active["label" + i] && blanks.push(i);
    return get_printform_serialized(), $("input[name='templateid']").val($("#pl_templateid").val()), $("input[name='labelid']").val($("#pl_labelid").val()), $("input[name='orientation']").val($("#pl_orientation").val()), $("input[name='blanks']").val(blanks), nlabels > 0 ? void $("#product_labels_form").submit() : (alert("Please indicate number of labels to print"), !1)
}

function toggle_label(a) {
    1 == label_active[a] ? ($("#" + a).css("border", "1px solid #eee"), $("#" + a).css("background-color", "#eee"), label_active[a] = 0) : ($("#" + a).css("border", "1px solid #999"), $("#" + a).css("background-color", "#fff"), label_active[a] = 1)
}

function get_template(a) {
    $.ajax({
        url: "index.php?route=module/pos/gettemplate&token=" + token + "&id=" + a,
        dataType: "json",
        success: function(a) {
            if (a) {
                page_width = parseFloat(a.page_w), page_height = parseFloat(a.page_h), label_width = parseFloat(a.width), label_height = parseFloat(a.height), number_h = parseInt(a.number_h), number_v = parseInt(a.number_v), space_h = parseFloat(a.space_h), space_v = parseFloat(a.space_v), margin_t = parseFloat(a.margin_t), margin_l = parseFloat(a.margin_l), rounded = parseInt(a.rounded), template_id = a.id, template_name = a.name, isNaN(margin_t) && (margin_t = (page_height - (number_v * (label_height + space_v) - space_v)) / 2), isNaN(margin_l) && (margin_l = (page_width - (number_h * (label_width + space_h) - space_h)) / 2), html = "";
                for (var b = 1; b <= number_h * number_v; b++) html += "<div onclick='toggle_label(\"label" + b + '");\' id="label' + b + '" class="product_labels_label"><small>' + b + "</small></div>";
                for ($(".product_labels_page-margin").html(html), label_active = new Array, b = 1; b <= number_h * number_v; b++) label_active["label" + b] = 1;
                "L" == orientation ? toggle_orientation() : (change_template(), update_preview())
            }
        }
    })
}

function change_template() {
    marginl = (page_width - (number_h * (label_width + space_h) - space_h)) / 2 * scale, margint = (page_height - (number_v * (label_height + space_v) - space_v)) / 2 * scale, $(".template_viewer").css("height", (template_viewer - (template_viewer - page_height) / 2) * scale + "px"), $(".template_viewer").css("padding-top", (template_viewer - page_height) / 2 * scale + "px"), $(".product_labels_page").css("width", page_width * scale + "px"), $(".product_labels_page").css("height", page_height * scale + "px"), $(".product_labels_page-margin").css("width", page_width * scale + "px"), $(".product_labels_page-margin").css("padding-top", margint * scale + "px"), $(".product_labels_page-margin").css("padding-left", marginl * scale + "px"), $(".product_labels_label").css("width", label_width * scale + "px"), $(".product_labels_label").css("height", label_height * scale + "px"), $(".product_labels_label").css("margin-bottom", space_v * scale + "px"), $(".product_labels_label").css("margin-right", space_h * scale + "px"), $(".product_labels_label").css("-moz-border-radius", 3 * rounded + "px"), $(".product_labels_label").css("border-radius", 3 * rounded * scale + "px")
}

function toggle_orientation() {
    t_page_width = page_width, t_page_height = page_height, t_label_width = label_width, t_label_height = label_height, t_number_h = number_h, t_number_v = number_v, t_space_h = space_h, t_space_v = space_v, t_margin_t = margin_t, t_margin_l = margin_l, page_width = t_page_height, page_height = t_page_width, label_width = t_label_height, label_height = t_label_width, number_h = t_number_v, number_v = t_number_h, space_h = t_space_v, space_v = t_space_h, margin_t = t_margin_l, margin_l = t_margin_t, orientation = $("#pl_orientation").val(), change_template(), update_preview()
}

function get_label(a) {
    $.ajax({
        url: "index.php?route=module/pos/get_label&token=" + token + "&id=" + a,
        dataType: "json",
        success: function(a) {
            return a.result ? a.result : void 0
        }
    })
}

function update_preview() {
    label_id = $("#pl_labelid").val(), template_id = $("#pl_templateid").val();
    var a = "index.php?route=module/pos/labels&token=" + token + "&orderids=1&sample=1&templateid=" + template_id + "&labelid=" + label_id + "&orientation=" + orientation,
        b = new PDFObject({
            url: a,
            id: "PDFPreview",
            pdfOpenParams: {
                navpanes: 0,
                toolbar: 0,
                statusbar: 0,
                view: "Fit"
            }
        });
    if (b) {
        var c = b.get("pluginTypeFound");
        if (!c) return $(".warningpdf").html("No PDF renderer available in this browser. Please install PDF plugin"), $(".warningpdf").css("display", "block"), !1
    }
    var d = b.embed("preview_label");
    d || $("#debug").html("Error loading PDF")
}

function get_printform_serialized() {
    nlabels = 0;
    for (var a = decodeURIComponent($("#product-label-options").find("input,select").filter(".pl_serializable").serialize()), b = a.split("&"), c = -1, d = 0; d < b.length; d++) {
        var e = b[d].split("="),
            f = e.splice(0, 1);
        f.push(e.join("="));
        var g = f[0].search(/\[/),
            h = f[0].search(/\]/),
            i = h - g - 1,
            j = f[0].substr(g + 1, i).split(",");
        if ("num" == f[0].substr(11, 3)) {
            if ("" == f[1]) {
                var c = j[0];
                continue
            }
            nlabels += parseInt(f[1])
        }
        j[0] != c && $("#product_labels_form").append('<input type="hidden" name="' + f[0] + '" value="' + decodeURIComponent(f[1]) + '" />')
    }
}

function add_new_label(a, b, c, d) {
    var e = '<div class="col-xs-12" style="margin-bottom:5px;"><div class="row">';
    e += '<div class="col-xs-11"><div class="row" id="option_' + b + '">', e += '<div class="col-xs-1 col-lg-1 oc2-pl-label-input">', e += '<input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="pl_options_num[' + b + ']" id="pl_options_num_' + b + '" value="" class="templateinput pl_serializable form-control">', e += "</div>", e += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input">', e += add_select_dropdown(b, 0, c), e += "</div>", e += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input" id="div_options_select_' + b + '_0">', e += add_options_dropdown(b, 0, d), e += "</div>", e += '<div class="col-xs-2 col-lg-2 oc2-pl-label-input" style="display:none;" id="div_options_string_' + b + '_0">', e += '<input type="text" name="pl_options_string[' + b + ',0]" id="pl_options_string_' + b + '_0" value="" class="templateinput pl_serializable form-control">', e += "</div></div>", e += '</div><div class="col-xs-1" style="margin-bottom:5px;">', e += '<button style="float:right;" type="button" onclick="add_option(' + b + ');" data-toggle="tooltip" title="Add option" class="btn btn-info btn-sm"><i class="fa fa-plus-circle"></i></button>', e += "</div></div></div>", $("#" + a).append(e), void 0 != c && populate_opt_dropdown("pl_options_" + b + "_0", c, d, b, 0)
}

function add_options_dropdown(a, b) {
    return select_options = '<select name="pl_options_value[' + a + "," + b + ']" id="pl_options_' + a + "_" + b + '" class="templateinput pl_serializable form-control" onchange="toggle_textinput(\'div_options_string_' + a + "_" + b + '\',$(this).val())"><option value="" selected="selected"></option></select>', select_options
}

function add_option(n, opt_selected, val_selected) {
    eval("option_row_" + n + "++;");
    var html = '<div class="col-xs-2 col-lg-2 oc2-pl-label-input">';
    html += add_select_dropdown(n, eval("option_row_" + n), opt_selected), html += '</div><div class="col-xs-2 col-lg-2 oc2-pl-label-input" id="div_options_select_' + n + "_" + eval("option_row_" + n) + '">', html += add_options_dropdown(n, eval("option_row_" + n)), html += '</div><div class="col-xs-2 col-lg-2 oc2-pl-label-input" style="display:none;" id="div_options_string_' + n + "_" + eval("option_row_" + n) + '">', html += '<input type="text" name="pl_options_string[' + n + "," + eval("option_row_" + n) + ']" id="pl_options_string_' + n + "_" + eval("option_row_" + n) + '" class="templateinput pl_serializable form-control">', html += "</div>", $("#option_" + n).append(html), void 0 != opt_selected && row < 50 && populate_opt_dropdown("pl_options_" + n + "_" + eval("option_row_" + n), opt_selected, val_selected, n, eval("option_row_" + n))
}

function populate_opt_dropdown(a, b, c, d, e) {
    if ($("#" + a).empty(), $("#" + a).append('<option value="">...</option><option value="_c">Custom</option>'), "_c" != b)
        for (var f = 0; f < options_list[b].length; f++) option_string = '<option value="' + options_list[b][f] + '"', c == options_list[b][f] && (option_string += ' selected="selected"'), option_string += ">" + options_list[b][f] + "</option>", $("#" + a).append(option_string);
    toggle_selectinput("div_options_select_" + d + "_" + e, b), toggle_textinput("div_options_string_" + d + "_" + e, b)
}

function toggle_textinput(a, b) {
    "_c" == b ? $("#" + a).css("display", "inline") : ($("#" + a).val(""), $("#" + a).css("display", "none"))
}

function toggle_selectinput(a, b) {
    "_c" == b ? $("#" + a).css("display", "none") : ($("#" + a).val(""), $("#" + a).css("display", "inline"))
}

function generate_label_options() {
    $.ajax({
        url: "index.php?route=module/pos/getlabeloptions&token=" + token + "&product_id=" + product_id,
        dataType: "json",
        success: function(json) {
            c = json.combinations, o = json.options;
            for (var i = 0; i < c.length; i++) {
                eval("option_row_" + row + "=0;");
                for (var a_option = c[i].split(":::"), j = 0; j < a_option.length; j++) {
                    var option = a_option[j].split("::");
                    0 == j ? add_new_label("product-label-options", row, option[0], option[1]) : add_option(row, option[0], option[1])
                }
                row++
            }
        }
    })
}
$(window).load(function() {
    $("#add_options").click(function() {
        eval("option_row_" + row + " = 0;"), add_new_label("product-label-options", row), row++
    }), $("#generate_label_options").click(function() {
        generate_label_options()
    }), $("#print_labels").click(function() {
        get_printform_serialized(), $("#product_labels_form").submit()
    }), $("#checkinstall").click(function() {
        var a = "index.php?route=module/pos/checkinstall&token=" + token;
        html = '<div class="modal-header">', html += '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>', html += '<h4 class="modal-title" id="myModalLabel">Checking module files</h4></div><div class="modal-body">', row = 0, $.ajax({
            url: a,
            dataType: "json",
            success: function(a) {
                jQuery.each(a, function(a, b) {
                    html += '<p style="border-bottom:1px solid #EEE">' + b.remote + '<span style="float:right;', b.valid > 0 && (html += 'color:green"><b>ok</b></span></p>'), 0 == b.valid && (html += 'color:red"><b>not valid</b></span></p>'), b.valid < 0 && (html += 'color:blue"><b>missing</b></span></p>')
                }), html += "</div></div>", $("#pl-modal-content").html(html), $("#pl-modal").modal("toggle")
            },
            error: function() {
                console.log("error loading " + a)
            }
        })
    }), $(".templateinput").on("change", function() {
        "" != $("input[name='templateid']").val() && ($("#savebutton_template").attr("style", "visibility:visible"), $("#savebutton_template").attr("style", "visibility:visible"), $("#printpreviewbutton_template").attr("style", "visibility:hidden;"))
    }), $("select[name='template']").on("change", function() {
        $.ajax({
            url: "index.php?route=module/pos/gettemplate&token=" + token + "&id=" + $("select[name='template'] option:selected").val(),
            type: "post",
            dataType: "json",
            success: function(a) {
                $("input[name='pw']").val(a.page_w), $("input[name='ph']").val(a.page_h), $("input[name='lw']").val(a.width), $("input[name='lh']").val(a.height), $("input[name='nw']").val(a.number_h), $("input[name='nh']").val(a.number_v), $("input[name='hspace']").val(a.space_h), $("input[name='vspace']").val(a.space_v), $("input[name='margint']").val(a.margin_t), $("input[name='marginl']").val(a.margin_l), $("input[name='templateid']").val(a.id), "1" == a.rounded ? $("input[name='rounded']").attr("checked", "checked") : $("input[name='rounded']").removeAttr("checked"), $("#savebutton_template").attr("style", "visibility:hidden"), "" != $("select[name='template'] option:selected").val() ? ($("#deletebutton_template").attr("style", "visibility:visible"), $("#printpreviewbutton_template").attr("style", "visibility:visible;")) : ($("input[name='pw']").val(settings.product_labels_default_pagew), $("input[name='ph']").val(settings.product_labels_default_pageh), $("#deletebutton_template").attr("style", "visibility:hidden"), $("#printpreviewbutton_template").attr("style", "visibility:hidden;")), preview_template()
            }
        })
    }), $(".labelinput").on("change", function() {
        "" != $("input[name='select_label'] option:selected").val() && ($("#savebutton_label").attr("style", "visibility:visible"),
            $("#deletebutton_label").attr("style", "visibility:visible"), $("#exportbutton_label").attr("style", "visibility:inline;margin-top:10px"))
    }), $("select[name='select_label']").on("change", function() {
        select_label()
    })
});