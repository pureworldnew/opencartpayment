String.prototype.trim = function()
{
    return this.replace(/(^[\s]*)|([\s]*$)/g, "");
}

function html_encode(html) {
	var div=document.createElement("div");
	var txt=document.createTextNode(html);
	div.appendChild(txt);
	return div.innerHTML;
}
if (typeof comm100_script_id == 'undefined')
	comm100_script_id = 0;

function comm100_script_request(params, success, error) {
	function request() {
		var _id = 'comm100_script_' + comm100_script_id++;
		var _success;
		var _timer_timeout;

		function _append_script(id, src) {
			var scr = document.createElement('script');
			scr.src = src;
			scr.id = '_' + _id;
			scr.type = 'text/javascript';
			document.getElementsByTagName('head')[0].appendChild(scr);
		}
		this.send = function _send (url, success, error) {
			_success = success || function() {};	
			_append_script(_id, url + '&callback=' + _id + '.onresponse');
			_timer_timeout = setTimeout(function() {
				if (error) error('Operation timeout.');
			}, 60 * 1000);
	
		}
		this.onresponse = function _onresponse(response) {
			//alert(response.toString())；
			window[_id] = null;
			var scr = document.getElementById('_' + _id);
			document.getElementsByTagName('head')[0].removeChild(scr);

			clearTimeout(_timer_timeout);

			_success(response);
		}
		window[_id] = this;
	}

	var req = new request();

	if(typeof comm100livechat_session == null) {
        setTimeout(function() {
	        req.send('http://hosted.comm100.com/adminpluginservice/emailmarketingplugin.ashx' + params + "&token=fjasedrlGkj[o5jghYwlthj0w934jhw_kljgAelkg459jghwlkj", success, error);
        }, 1000);
	} else {
	    req.send('http://hosted.comm100.com/adminpluginservice/emailmarketingplugin.ashx' + params + "&token=fjasedrlGkj[o5jghYwlthj0w934jhw_kljgAelkg459jghwlkj", success, error);
	}
}

var comm100_plugin = (function() {
    function _onexception(msg) {
        document.getElementById('login_submit_img').style.display = 'none';
        document.getElementById('login_submit').disabled = false;
        alert(msg);
    }

    function _login() {
        document.getElementById('login_submit_img').style.display = '';
        document.getElementById('login_submit').disabled = true;

        var site_id = encodeURIComponent(document.getElementById('site_id').value.trim());
        var email = encodeURIComponent(document.getElementById('login_email').value);
        var password = encodeURIComponent(document.getElementById('login_password').value);
        var timezone = encodeURIComponent(_get_timezone());

        comm100_script_request('?action=login&siteId=' + site_id + '&email=' + email + '&password=' + password
			, function(response) {
			    if(response.success) {
			        document.getElementById('site_id').value = site_id;
			        document.getElementById('email').value = email;
			        document.forms['site_id_form'].submit();
			    }
			    else {
			        document.getElementById('login_error_').style.display = '';
			        document.getElementById('login_error_text').innerHTML = response.error;
			    }
			    document.getElementById('login_submit_img').style.display = 'none';
			    document.getElementById('login_submit').disabled = false;
			}, function(message) {
			    document.getElementById('login_submit_img').style.display = 'none';
			    document.getElementById('login_submit').disabled = false;

			    document.getElementById('login_error_').style.display = '';
			    document.getElementById('login_error_text').innerHTML = response.error;
			});
    }
    function _get_mailling_lists(site_id, success, error) {
        comm100_script_request('?action=maillinglist&siteId=' + site_id, function(response) {
            if(response.error) {     
                if (typeof error != 'undefined')
    				alert(response.error);
            } else {
                success(response.response);
            }
        });
    }
    function _get_fields(site_id, success, error) {
        comm100_script_request('?action=fields&siteId=' + site_id, function(response) {
            if(response.error) {
    			alert(response.error);
            } else {
                success(response);
            }
        });
    }
    function _get_code(site_id, options, success) {
    	comm100_script_request('?action=code' + options + '&siteId=' + site_id, function(response) {
    		if (response.success) {
    			if (success) {
    				success(response.response);
    			}
    		} else {
    			alert(response.error);
    		}
    	})
    }
    function _get_timezone() {
        return ((new Date()).getTimezoneOffset() / -60.0).toString();
    }
    function _show_sites(sites) {
    	var html = ''
    	for (var i = 0, len = sites.length; i < len; i++) {
    		var s = sites[i];

    		html += '<div style="padding: 0 0 15px 0"><input name="comm100site" type="radio" id="site'+s.id+'"';
    		html += ' onclick="document.getElementById(\'site_id\').value='+s.id+';"';
    		if (i == 0) html += 'checked ';
    		html += '/> <label for="site'+s.id+'">Site Id: <span style="color: #000;font-weight: bold;font-size: larger;">';
    		html += s.id;
    		if (s.inactive) html+= '<span style="color: red; font-size: x-small;padding: 0 0 3px 3px;">(Inactive)</span>';
    		html += '</span><span style="padding: 0 0 0 7px;">Last Login: ';
    		html += s.last_login_time;
    		html += '</span><span style="padding: 0 0 0 7px;">Account Created: '
    		html += s.register_time + '</span></label></div>';
    	}

    	document.getElementById('login_sites').innerHTML = html;

    	hide_element('comm100EM_login');
    	show_element('comm100EM_choose_site');

    	document.getElementById('num_sites').innerHTML = sites.length;
    }
    function _choose_site() {
        show_element('choose_site_submit_img');
        document.getElementById('choose_site_submit').disabled = true;

        _login(function () {
	        hide_element('choose_site_submit_img');
	        document.getElementById('choose_site_submit').disabled = false;
        }, function (error) {
	        hide_element('choose_site_submit_img');
	        document.getElementById('choose_site_submit').disabled = false;

		    document.getElementById('choose_site_error_').style.display = '';
		    document.getElementById('choose_site_error_text').innerHTML = error;        	
        })
    }

    function _sites () {
        show_element('login_submit_img');
        document.getElementById('login_submit').disabled = true;

    	var email = encodeURIComponent(document.getElementById('login_email').value);
        var password = encodeURIComponent(document.getElementById('login_password').value);
        
    	comm100_script_request('?action=sites&email='+email+'&password='+password+'&timezoneoffset='+(new Date()).getTimezoneOffset(), 
    	function (response) {
    		if (response.success) {
    			var sites = response.response;
    			if (sites.length == 0) {
    				return;
    			}

    			document.getElementById('site_id').value = sites[0].id;
    			if (sites.length > 1) {
    				_show_sites(response.response);
    			} else {
			        _login(function () {
				        hide_element('login_submit_img');
				        document.getElementById('login_submit').disabled = false;
			        }, function (error) {
				        hide_element('login_submit_img');
				        document.getElementById('login_submit').disabled = false;

					    document.getElementById('login_error_').style.display = '';
					    document.getElementById('login_error_text').innerHTML = error;        	
			        })
    			}
    		} else {
		        show_element('login_error_');
		        document.getElementById('login_error_text').innerHTML = response.error;
		        
			    hide_element('login_submit_img');
			    document.getElementById('login_submit').disabled = false;
			}
    	});
    }
    return {
        login: _login,
        get_code: _get_code,
        get_mailling_lists: _get_mailling_lists,
        get_fields: _get_fields,
        sites: _sites,
        choose_site: _choose_site,
    };
})();


function comm100_select_class(element, class_name) {
    element = element || document;
    if (element.getElementsByClassName) {
        return element.getElementsByClassName(class_name);
    }
    var children = element.getElementsByTagName('*') || document.all;
    var elements = new Array();
    for (var i = 0, len = children.length; i < len; i++) {
        var child = children[i];
        if (has_class(child, class_name)) {
            elements.push(child);
        }
    }
    return elements;
}
function comm100_select_class1(ele, class_name) {
    var arr = comm100_select_class(ele, class_name);
    if (arr && arr.length > 0) {
        return arr[0];
    }
    return null;
}


function hide_element(id) {
	document.getElementById(id).style.display = 'none';
}
function show_element(id, display) {
	document.getElementById(id).style.display = display || '';
}
function is_empty(str) {
	return (!str || /^\s*$/.test(str));
}
function is_email(str) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(str);
}
function is_input_empty(input_id) {
	return is_empty(document.getElementById(input_id).value);
}
function is_input_email(input_id) {
	return is_email(document.getElementById(input_id).value);
}
function show_login() {
	show_element("comm100emailmarketing_have_account");
	show_element("comm100emailmarketing_login");
	hide_element("comm100emailmarketing_register");
	//hide_element("comm100emailmarketing_settings");
}
function show_registger() {
	show_element("comm100emailmarketing_have_account");
	hide_element("comm100emailmarketing_login");
	show_element("comm100emailmarketing_register");
	//hide_element("comm100emailmarketing_settings");
}
function show_settings() {
	//hide_element("comm100emailmarketing_have_account");
	//hide_element("comm100emailmarketing_login");
	//hide_element("comm100emailmarketing_register");
	show_element("comm100emailmarketing_settings");
}
function validate_register_input(name) {
	if (is_input_empty("register_" + name)) {
		show_element("register_" + name + "_required");
		return false;
	} else {
		hide_element("register_" + name + "_required");
		return true;
	}
}
function validate_register_input_email(name) {
	if (is_input_empty("register_" + name)) {
		show_element("register_" + name + "_required");
		hide_element("register_" + name + "_valid");
		return false;
	} else if (!is_input_email("register_" + name)) {
		hide_element("register_" + name + "_required");
		show_element("register_" + name + "_valid");
		return false;
	} else {
		hide_element("register_" + name + "_required");
		hide_element("register_" + name + "_valid");
		return true;
	}
}
function validate_register_inputs() {
	var fields = ["name", "password", "phone", "verification_code"];
	var pass = true;

	for (var i = 0, len = fields.length; i < len; i++) {
		if (!validate_register_input(fields[i])) {
			pass = false;
		}
	}

	if (!validate_register_input_email("email")) {
		pass = false;
	}

	return pass;
}



function overwrite_widget_save() {
	if (typeof wpWidgets != 'undefined' && wpWidgets.save) {
		var fn_save = wpWidgets.save;
		wpWidgets.save = function(arg0, arg1, arg2, arg3, arg4, arg5) {
			if (!arg1 && arg0.attr('id').indexOf('emailmarketing') >= 0 && jQuery('.comm100-form-code', arg0).size()) {

				comm100_plugin.get_code(comm100_site_id, comm100_form_options(arg0), function(code) {
					jQuery('.comm100-form-code', arg0).val(html_encode(code));
					fn_save(arg0, arg1, arg2, arg3, arg4, arg5);
				});
			} else {
				fn_save(arg0, arg1, arg2, arg3, arg4, arg5);
			}
		};
	} else {
		setTimeout(overwrite_widget_save, 100);
	}
}

overwrite_widget_save();