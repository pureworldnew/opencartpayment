function initAutocomplete(e, t, n) {
    var r = t.text_price ? t.text_price : "Price: ";
    var i = t.text_viewall ? t.text_viewall : "View all ";
    var s = 0;
    ec_reload = n.reload ? n.reload : false;
    baseUrl = n.base_url ? n.base_url : "";
    quickview_title = n.quickview_title ? n.quickview_title : "Quick View";
    popup_width = n.popup_width ? n.popup_width : "50%";
    popup_height = n.popup_height ? n.popup_height : "550px";
    current_selector = e;
    $(e).find("input[name='search']").autocomplete({
        delay: 500,
        source: function(t, r) {
            var i = $(e + ' select[name="category_id"]').first().val();
            if (typeof i == "undefined") i = 0;
            var o = $(e + ' select[name="manufacturer_id"]').first().val();
            if (typeof o == "undefined") o = 0;
            var u = 5;
            var a = n.search_sub_category ? "&sub_category=true" : "";
            var f = n.search_description ? "&description=true" : "";
            $(e).find("input[name='search']").addClass("search-loading");
            $.ajax({
                url: "index.php?route=module/ecquickbuy/autocomplete&filter_category_id=" + i + "&filter_manufacturer_id=" + o + "&limit=" + u + a + f + "&filter_name=" + encodeURIComponent(t.term),
                dataType: "json",
                success: function(t) {
                    $(e).find("input[name='search']").removeClass("search-loading");
                    r($.map(t, function(e) {
                        s = 0;
                        if (e.total) {
                            s = e.total
                        }
                        return {
                            price: e.price,
                            rprice:e.rprice,
                            label: e.name,
                            image: e.image,
                            link: e.link,
                            value: e.product_id,
                            unit : e.unit
                        }
                    }))
                }
            })
        },
        select: function(e, t) {
            return false
        },
        focus: function(e, t) {
            return false
        }
    });
    $(e).find("input[name='search']").data("autocomplete")._renderMenu = function(t, r) {
        var o = this;
        $.each(r, function(e, n) {
            o._renderItem(t, n)
        });
        var u = i.replace(/%s/gi, s);
        if (n.show_viewmore) {
            var a = $(e + ' select[name="category_id"]').first().val();
            a = parseInt(a);
            var f = $(e + ' select[name="manufacturer_id"]').first().val();
            f = parseInt(f);
            var l = n.link_more ? n.link_more : "";
            l = l.replace("{search}", o.term);
            if (a > 0) l = l.replace("{category_id}", "&category_id=" + a);
            else l = l.replace("{category_id}", "");
            if (f > 0) l = l.replace("{manufacturer_id}", "&manufacturer_id=" + f);
            else l = l.replace("{manufacturer_id}", "");
            return $(t).append('<li><a href="' + l + '" onclick="window.location=this.href">' + u + "</a></li>")
        }
        return u
    };
    $(e).find("input[name='search']").data("autocomplete")._renderItem = function(t, i) {
        var s = "<ol>";
        if (n.show_image) {
            s += '<img style="float:left; margin:3px 8px 0 0;" src="' + i.image + '">'
        }
        s += '<strong><div class="std">' + i.label + "</div></strong>";
        if (n.show_price) {
            s += '<p style="font-size:0.9em;line-height:1.4em;margin:2px 0 0;"></p><div class="std">' + r + i.price
        }
        s += '</div><br class="clear clr"/></ol>';
        var o = $("<li></li>").data("item.autocomplete", i).append(s).appendTo(t);
        $(o).click(function(n) {
            //console.log(i);
            $(e + " input[name='search']").val(i.label);
            $("#p_id").val(i.value);
            $("#p_p").val(i.rprice);
            $("#p_link").text(i.label);
            $("#p_link").attr('href',i.link);
            $("#p_img_link").attr('href',i.link);

            if (i.image){
                $("#p_img").attr("src",i.image);
            }

            $("#p_qty").text("1");
            $("#p_price").text(i.rprice + " / " + i.unit.unit_singular);
            $("#p_total").text(i.rprice);
            $("#p_subtotal").text(i.rprice);
            if (screen.width <= 1024) {
                $(e + " .quickview").attr("href", i.link)
            } else {
                //$(e + " .quickview").attr("href", "index.php?route=product/product&product_id=" + i.value + "&view")
                $(e + " .quickview").attr("href", i.link + "&view");
            }
            ec_product_id = i.value;
            $(t).hide()
        });
        return o
    }
}

function addToCart2(e, t) {
    localStorage.setItem("is_grouped_product", "0");
    t = typeof t != "undefined" ? t : 1;
    $.ajax({
        url: "index.php?route=checkout/cart/add",
        type: "post",
        data: "product_id=" + e + "&quantity=" + t,
        dataType: "json",
        success: function(t) {
            localStorage.setItem("is_grouped_product", t.is_grouped);
            $(".success, .warning, .attention, .information, .error").remove();
            if (t["redirect"]) {
                if (screen.width <= 1024) {
                    window.location.href = "index.php?route=product/product&product_id=" + e
                } else {
                    $(current_selector + " .quickview").colorbox({
                        width: popup_width,
                        height: popup_height,
                        overlayClose: true,
                        opacity: .5,
                        open: true,
                        title: quickview_title,
                        iframe: false,
                        onComplete: function() {
                            if (localStorage.getItem("is_grouped_product") == 1) {
                                QvGroupProduct.changeOption()
                            }
                            $(".fancySelect").fancySelect();
                            $(".qv-options select").fancySelect()
                        },
                        onCleanup: function() {
                            $("#get-unit-data-qv").die();
                            $(".option_qv select").die();
                            $(".quantity_qv").unbind();
                            $(".grouped_product_select_qv").off()
                        }
                    })
                }
            }
            if (t["success"]) {
                location.reload();
                $("#notification").html('<div class="success" style="display: none;">' + t["success"] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                $(".success").fadeIn("slow");
                $("#cart-total").html(t["total"]);
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
                ec_product_id = 0;
                $(current_selector + " input[name='search']").val("");
                if (ec_reload) {
                    location.reload()
                }
            }
        }
    })
}

function addQuickBuy(e) {
    if ($(e) && ec_product_id) {
        current_selector = e;
        var t = 1;
        if ($(e + " input[name='quantity']")) {
            t = $(e + " input[name='quantity']").val()
        }
        t = parseInt(t);
        t = t > 0 ? t : 1;
        addToCart2(ec_product_id, t)
    } else {
        alert("not found product!")
    }
}
var ec_product_id = 0;
var current_selector = null;
var baseUrl = "";
var quickview_title = "Quick View";
var popup_width = "50%";
var popup_height = "550px";
var ec_reload = false;
(function(e) {
    var t = 0;
    e.widget("ui.autocomplete", {
        options: {
            appendTo: "body",
            autoFocus: false,
            delay: 300,
            minLength: 1,
            position: {
                my: "left top",
                at: "left bottom",
                collision: "none"
            },
            source: null
        },
        pending: 0,
        _create: function() {
            var t = this,
                n = this.element[0].ownerDocument,
                r;
            this.element.addClass("ui-autocomplete-input").attr("autocomplete", "off").attr({
                role: "textbox",
                "aria-autocomplete": "list",
                "aria-haspopup": "true"
            }).bind("keydown.autocomplete", function(n) {
                if (!(t.options.disabled || t.element.propAttr("readOnly"))) {
                    r = false;
                    var i = e.ui.keyCode;
                    switch (n.keyCode) {
                        case i.PAGE_UP:
                            t._move("previousPage", n);
                            break;
                        case i.PAGE_DOWN:
                            t._move("nextPage", n);
                            break;
                        case i.UP:
                            t._move("previous", n);
                            n.preventDefault();
                            break;
                        case i.DOWN:
                            t._move("next", n);
                            n.preventDefault();
                            break;
                        case i.ENTER:
                        case i.NUMPAD_ENTER:
                            if (t.menu.active) {
                                r = true;
                                n.preventDefault()
                            };
                        case i.TAB:
                            if (!t.menu.active) return;
                            t.menu.select(n);
                            break;
                        case i.ESCAPE:
                            t.element.val(t.term);
                            t.close(n);
                            break;
                        default:
                            clearTimeout(t.searching);
                            t.searching = setTimeout(function() {
                                if (t.term != t.element.val()) {
                                    t.selectedItem = null;
                                    t.search(null, n)
                                }
                            }, t.options.delay);
                            break
                    }
                }
            }).bind("keypress.autocomplete", function(e) {
                if (r) {
                    r = false;
                    e.preventDefault()
                }
            }).bind("focus.autocomplete", function() {
                if (!t.options.disabled) {
                    t.selectedItem = null;
                    t.previous = t.element.val()
                }
            }).bind("blur.autocomplete", function(e) {
                if (!t.options.disabled) {
                    clearTimeout(t.searching);
                    t.closing = setTimeout(function() {
                        t.close(e);
                        t._change(e)
                    }, 150)
                }
            });
            this._initSource();
            this.response = function() {
                return t._response.apply(t, arguments)
            };
            this.menu = e("<ul></ul>").addClass("ui-autocomplete").appendTo(e(this.options.appendTo || "body", n)[0]).mousedown(function(n) {
                var r = t.menu.element[0];
                e(n.target).closest(".ui-menu-item").length || setTimeout(function() {
                    e(document).one("mousedown", function(n) {
                        n.target !== t.element[0] && n.target !== r && !e.ui.contains(r, n.target) && t.close()
                    })
                }, 1);
                setTimeout(function() {
                    clearTimeout(t.closing)
                }, 13)
            }).menu({
                focus: function(e, n) {
                    n = n.item.data("item.autocomplete");
                    false !== t._trigger("focus", e, {
                        item: n
                    }) && /^key/.test(e.originalEvent.type) && t.element.val(n.value)
                },
                selected: function(e, r) {
                    var i = r.item.data("item.autocomplete"),
                        s = t.previous;
                    if (t.element[0] !== n.activeElement) {
                        t.element.focus();
                        t.previous = s;
                        setTimeout(function() {
                            t.previous = s;
                            t.selectedItem = i
                        }, 1)
                    }
                    false !== t._trigger("select", e, {
                        item: i
                    }) && t.element.val(i.value);
                    t.term = t.element.val();
                    t.close(e);
                    t.selectedItem = i
                },
                blur: function() {
                    t.menu.element.is(":visible") && t.element.val() !== t.term && t.element.val(t.term)
                }
            }).zIndex(this.element.zIndex() + 1).css({
                top: 0,
                left: 0
            }).hide().data("menu");
            e.fn.bgiframe && this.menu.element.bgiframe()
        },
        destroy: function() {
            this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete").removeAttr("role").removeAttr("aria-autocomplete").removeAttr("aria-haspopup");
            this.menu.element.remove();
            e.Widget.prototype.destroy.call(this)
        },
        _setOption: function(t, n) {
            e.Widget.prototype._setOption.apply(this, arguments);
            t === "source" && this._initSource();
            if (t === "appendTo") this.menu.element.appendTo(e(n || "body", this.element[0].ownerDocument)[0]);
            t === "disabled" && n && this.xhr && this.xhr.abort()
        },
        _initSource: function() {
            var n = this,
                r, i;
            if (e.isArray(this.options.source)) {
                r = this.options.source;
                this.source = function(t, n) {
                    n(e.ui.autocomplete.filter(r, t.term))
                }
            } else if (typeof this.options.source === "string") {
                i = this.options.source;
                this.source = function(r, s) {
                    n.xhr && n.xhr.abort();
                    n.xhr = e.ajax({
                        url: i,
                        data: r,
                        dataType: "json",
                        autocompleteRequest: ++t,
                        success: function(e) {
                            this.autocompleteRequest === t && s(e)
                        },
                        error: function() {
                            this.autocompleteRequest === t && s([])
                        }
                    })
                }
            } else this.source = this.options.source
        },
        search: function(e, t) {
            e = e != null ? e : this.element.val();
            this.term = this.element.val();
            if (e.length < this.options.minLength) return this.close(t);
            clearTimeout(this.closing);
            if (this._trigger("search", t) !== false) return this._search(e)
        },
        _search: function(e) {
            this.pending++;
            this.element.addClass("ui-autocomplete-loading");
            this.source({
                term: e
            }, this.response)
        },
        _response: function(e) {
            if (!this.options.disabled && e && e.length) {
                e = this._normalize(e);
                this._suggest(e);
                this._trigger("open")
            } else this.close();
            this.pending--;
            this.pending || this.element.removeClass("ui-autocomplete-loading")
        },
        close: function(e) {
            clearTimeout(this.closing);
            if (this.menu.element.is(":visible")) {
                this.menu.element.hide();
                this.menu.deactivate();
                this._trigger("close", e)
            }
        },
        _change: function(e) {
            this.previous !== this.element.val() && this._trigger("change", e, {
                item: this.selectedItem
            })
        },
        _normalize: function(t) {
            if (t.length && t[0].label && t[0].value) return t;
            return e.map(t, function(t) {
                if (typeof t === "string") return {
                    label: t,
                    value: t
                };
                return e.extend({
                    label: t.label || t.value,
                    value: t.value || t.label
                }, t)
            })
        },
        _suggest: function(t) {
            var n = this.menu.element.empty().zIndex(this.element.zIndex() + 1);
            this._renderMenu(n, t);
            this.menu.deactivate();
            this.menu.refresh();
            n.show();
            this._resizeMenu();
            n.position(e.extend({
                of: this.element
            }, this.options.position));
            this.options.autoFocus && this.menu.next(new e.Event("mouseover"))
        },
        _resizeMenu: function() {
            var e = this.menu.element;
            e.outerWidth(Math.max(e.width("").outerWidth(), this.element.outerWidth()))
        },
        _renderMenu: function(t, n) {
            var r = this;
            e.each(n, function(e, n) {
                r._renderItem(t, n)
            })
        },
        _renderItem: function(t, n) {
            return e("<li></li>").data("item.autocomplete", n).append(e("<a></a>").text(n.label)).appendTo(t)
        },
        _move: function(e, t) {
            if (this.menu.element.is(":visible"))
                if (this.menu.first() && /^previous/.test(e) || this.menu.last() && /^next/.test(e)) {
                    this.element.val(this.term);
                    this.menu.deactivate()
                } else this.menu[e](t);
            else this.search(null, t)
        },
        widget: function() {
            return this.menu.element
        }
    });
    e.extend(e.ui.autocomplete, {
        escapeRegex: function(e) {
            return e.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&")
        },
        filter: function(t, n) {
            var r = new RegExp(e.ui.autocomplete.escapeRegex(n), "i");
            return e.grep(t, function(e) {
                return r.test(e.label || e.value || e)
            })
        }
    })
})(jQuery);
(function(e) {
    e.widget("ui.menu", {
        _create: function() {
            var t = this;
            this.element.addClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-set-css").attr({
                role: "listbox",
                "aria-activedescendant": "ui-active-menuitem"
            }).click(function(n) {
                if (e(n.target).closest(".ui-menu-item a").length) {
                    n.preventDefault();
                    t.select(n)
                }
            });
            this.refresh()
        },
        refresh: function() {
            var t = this;
            this.element.children("li:not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role", "menuitem").children("a").addClass("ui-corner-all").attr("tabindex", -1).mouseenter(function(n) {
                t.activate(n, e(this).parent())
            }).mouseleave(function() {
                t.deactivate()
            })
        },
        activate: function(e, t) {
            this.deactivate();
            if (this.hasScroll()) {
                var n = t.offset().top - this.element.offset().top,
                    r = this.element.scrollTop(),
                    i = this.element.height();
                if (n < 0) this.element.scrollTop(r + n);
                else n >= i && this.element.scrollTop(r + n - i + t.height())
            }
            this.active = t.eq(0).children("a").addClass("ui-state-hover").attr("id", "ui-active-menuitem").end();
            this._trigger("focus", e, {
                item: t
            })
        },
        deactivate: function() {
            if (this.active) {
                this.active.children("a").removeClass("ui-state-hover").removeAttr("id");
                this._trigger("blur");
                this.active = null
            }
        },
        next: function(e) {
            this.move("next", ".ui-menu-item:first", e)
        },
        previous: function(e) {
            this.move("prev", ".ui-menu-item:last", e)
        },
        first: function() {
            return this.active && !this.active.prevAll(".ui-menu-item").length
        },
        last: function() {
            return this.active && !this.active.nextAll(".ui-menu-item").length
        },
        move: function(e, t, n) {
            if (this.active) {
                e = this.active[e + "All"](".ui-menu-item").eq(0);
                e.length ? this.activate(n, e) : this.activate(n, this.element.children(t))
            } else this.activate(n, this.element.children(t))
        },
        nextPage: function(t) {
            if (this.hasScroll())
                if (!this.active || this.last()) this.activate(t, this.element.children(".ui-menu-item:first"));
                else {
                    var n = this.active.offset().top,
                        r = this.element.height(),
                        i = this.element.children(".ui-menu-item").filter(function() {
                            var t = e(this).offset().top - n - r + e(this).height();
                            return t < 10 && t > -10
                        });
                    i.length || (i = this.element.children(".ui-menu-item:last"));
                    this.activate(t, i)
                } else this.activate(t, this.element.children(".ui-menu-item").filter(!this.active || this.last() ? ":first" : ":last"))
        },
        previousPage: function(t) {
            if (this.hasScroll())
                if (!this.active || this.first()) this.activate(t, this.element.children(".ui-menu-item:last"));
                else {
                    var n = this.active.offset().top,
                        r = this.element.height();
                    result = this.element.children(".ui-menu-item").filter(function() {
                        var t = e(this).offset().top - n + r - e(this).height();
                        return t < 10 && t > -10
                    });
                    result.length || (result = this.element.children(".ui-menu-item:first"));
                    this.activate(t, result)
                } else this.activate(t, this.element.children(".ui-menu-item").filter(!this.active || this.first() ? ":last" : ":first"))
        },
        hasScroll: function() {
            return this.element.height() < this.element[e.fn.prop ? "prop" : "attr"]("scrollHeight")
        },
        select: function(e) {
            this._trigger("selected", e, {
                item: this.active
            })
        }
    })
})(jQuery);