$(function() {

    var $quick_login = $('.quick_login'),
            $login = $quick_login.find('.ql_login'),
			$imgs = $login.find('img'),
            $main_page = $login.find('#main_page'),
            $error_warning = $quick_login.find('#error_warning'),
            $header = $('#header'),
            //$links = $header.find('.links'),
            $welcome = $header.find('#welcome');
	
	 var $quick_login_desktop = $('.quick_login_desktop'),
            $login_desktop = $quick_login_desktop.find('.ql_login_desktop'),
            $imgs = $login_desktop.find('img'),
            $main_page = $login_desktop.find('#main_page'),
            $error_warning = $quick_login_desktop.find('#error_warning');

    function loadingPic_Show() {
        var width_windowLogin = parseInt($login.css('width')),
                height_windowLogin = parseInt($login.css('height')),
                width_loadingPic = 100, height_loadingPic = 100;

        $login.html('<img src="catalog/view/javascript/loading.gif" />');

        $imgs.css({
            'position': 'relative',
            'top': ((height_windowLogin / 2) - (height_loadingPic / 2)) + 'px',
            'left': ((width_windowLogin / 2) - (width_loadingPic / 2)) + 'px'
        });
    }


    function moduleLoad_login() {
        $.ajax({
            url: 'index.php?route=common/quick_login/checklogin',
            type: 'get',
            success: function(response) {
                $login.html(response);
				$login_desktop.html(response);
				initAfterLoad(response);
                 
            }
        });
    }

    function moduleLoad_logout() {
        $.ajax({
            url: 'index.php?route=common/quick_login/logout',
            type: 'get',
            success: function() {
                 location.reload(true);
            },
            error: function() {
                location.reload(true);
            }
        });
    }

    function init() {
        $header.on('click', '#link_login', function(e) {
            e.preventDefault();
            $(this).parents('.quick_login').toggleClass('active');
            var $target = $(e.target).html();
            // to show the account menu (the other condition is in the quick_login_logged.tpl)
            if ($target != $('#text_hidden_login').val() && $target.indexOf('http://') == -1) {
                $('#init').val('0');
            }
        });
		
		 $header.on('click', '.link_login_desktop', function(e) {
            e.preventDefault();
            $(this).parents('.quick_login_desktop').toggleClass('active');
            var $target = $(e.target).html();
            // to show the account menu (the other condition is in the quick_login_logged.tpl)
            if ($target != $('#text_hidden_login').val() && $target.indexOf('http://') == -1) {
                $('#init').val('0');
            }
        });

        $header.find('.link_logout').live('click', function(e) {
            e.preventDefault();
            $(this).parents('.quick_login').toggleClass('logged');
            moduleLoad_logout();
        });
		
		
		$header.find('.link_logout_desktop').live('click', function(e) {
            e.preventDefault();
            $(this).parents('.quick_login_desktop').toggleClass('logged');
            moduleLoad_logout();
        });

        // it hides the $container if you click on else place
        // first this will be executed then the event on
        $(document).mouseup(function(e) {
            //var $container = $login;
            if (!$login.is(e.target) && $login.has(e.target).length === 0) {
                if (typeof $login.parents('.quick_login').attr('class') != 'undefined' &&
                        $login.parents('.quick_login').attr('class').indexOf('active') > -1 &&
                        $(e.target).html() != $login.parents('.quick_login').find('#link_login').html()) {
                    $login.parents('.quick_login').removeClass('active');
                }
            }
        });

        $quick_login.find('.login_form input').keydown(function(e) {
            if (e.keyCode == 13) {
                $quick_login.find('.login_form').submit();
            }
        });
		
		$quick_login_desktop.find('.login_form input').keydown(function(e) {
            if (e.keyCode == 13) {
                $quick_login_desktop.find('.login_form').submit();
            }
        });

    }

    loadingPic_Show();
    moduleLoad_login();
    init();

    function resizeWindowLogin(numberOfErrors, login_error) {
        // it's an optional parameter for displaying the login error (not the validate error)
        login_error = login_error || 0;
        if (!login_error && $error_warning.html() != '')
            $error_warning.html('');
        /*switch (numberOfErrors){
         case 0:
         $login.css('height','187');
         $('#login #main_page').css('height','163');
         break;
         case 1:
         $login.css('height','202');
         $('#login #main_page').css('height','178');
         break;
         case 2:
         $login.css('height','217');
         $('#login #main_page').css('height','193');
         break;
         }*/
    }

    function initLoginForm() {

        $('#email').focus();

        switch ($('#error_warning').html()) {
            case quick_login.messages.error_login:
            case quick_login.messages.error_approved:
                resizeWindowLogin(2, true);
                break;
        }

        $quick_login_desktop.find(".login_form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: "required"
            },
            messages: {
                email: {
                    required: quick_login_desktop.messages.error_email,
                    email: quick_login_desktop.messages.error_email
                },
                password: quick_login_desktop.messages.error_password
            },
            onfocusin: function(element, event) {
                this.lastActive = element;
                // hide error label and remove error class on focus if enabled

                if (this.settings.focusCleanup && !this.blockFocusCleanup) {
                    this.settings.unhighlight && this.settings.unhighlight.call(this, element, this.settings.errorClass, this.settings.validClass);
                    this.addWrapper(this.errorsFor(element)).hide();
                }

                resizeWindowLogin(this.numberOfInvalids());
            },
            onfocusout: function(element, event) {
                if (!this.checkable(element) && (element.name in this.submitted || !this.optional(element))) {
                    this.element(element);
                }
                resizeWindowLogin(this.numberOfInvalids());
            },
            onkeyup: function(element, event) {

                if (element.name in this.submitted || element == this.lastElement) {
                    this.element(element);
                }
                resizeWindowLogin(this.numberOfInvalids());
            },
            onclick: function(element, event) {
                // click on selects, radiobuttons and checkboxes
                if (element.name in this.submitted)
                    this.element(element);
                // or option elements, check parent select in that case
                else if (element.parentNode.name in this.submitted)
                    this.element(element.parentNode);
                resizeWindowLogin(this.numberOfInvalids());
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element).css('padding-left', '18px');
            },
            invalidHandler: function(event, validator) {
                resizeWindowLogin(validator.numberOfInvalids());
            },
            submitHandler: function(form) {
                var action = $quick_login_desktop.find('.login_form').attr('action'),
                        email = $quick_login_desktop.find("#email").val(),
                        password = $quick_login_desktop.find("#password").val();
                $("#main_page").remove();
                loadingPic_Show();
                $.post(action,
                        {
                            email: email,
                            password: password
                        },
                function(data) {
                    $login.html(data);
					$login_desktop.html(data);
                    initAfterLoad(data);
                     var currentURL = window.location.href;
                var value = currentURL.substring(currentURL.lastIndexOf('/') + 1);
                if (value == "logout") {
                    window.location = BASE_URL;
                }
                else {
                    location.reload(true);
                }
                }
                );
            }
        });
		
		$quick_login.find(".login_form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: "required"
            },
            messages: {
                email: {
                    required: quick_login.messages.error_email,
                    email: quick_login.messages.error_email
                },
                password: quick_login.messages.error_password
            },
            onfocusin: function(element, event) {
                this.lastActive = element;
                // hide error label and remove error class on focus if enabled

                if (this.settings.focusCleanup && !this.blockFocusCleanup) {
                    this.settings.unhighlight && this.settings.unhighlight.call(this, element, this.settings.errorClass, this.settings.validClass);
                    this.addWrapper(this.errorsFor(element)).hide();
                }

                resizeWindowLogin(this.numberOfInvalids());
            },
            onfocusout: function(element, event) {
                if (!this.checkable(element) && (element.name in this.submitted || !this.optional(element))) {
                    this.element(element);
                }
                resizeWindowLogin(this.numberOfInvalids());
            },
            onkeyup: function(element, event) {

                if (element.name in this.submitted || element == this.lastElement) {
                    this.element(element);
                }
                resizeWindowLogin(this.numberOfInvalids());
            },
            onclick: function(element, event) {
                // click on selects, radiobuttons and checkboxes
                if (element.name in this.submitted)
                    this.element(element);
                // or option elements, check parent select in that case
                else if (element.parentNode.name in this.submitted)
                    this.element(element.parentNode);
                resizeWindowLogin(this.numberOfInvalids());
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element).css('padding-left', '18px');
            },
            invalidHandler: function(event, validator) {
                resizeWindowLogin(validator.numberOfInvalids());
            },
            submitHandler: function(form) {
                var action = $quick_login.find('.login_form').attr('action'),
                        email = $quick_login.find("#email").val(),
                        password = $quick_login.find("#password").val();
                $("#main_page").remove();
                loadingPic_Show();
                $.post(action,
                        {
                            email: email,
                            password: password
                        },
                function(data) {
                    $login.html(data);
					$login_desktop.html(data);
                    initAfterLoad(data);
                     var currentURL = window.location.href;
                var value = currentURL.substring(currentURL.lastIndexOf('/') + 1);
                if (value == "logout") {
                    window.location = BASE_URL;
                }
                else {
                    location.reload(true);
                }
                }
                );
            }
        });
		
		

    }

    function belepInit() {
        //$welcome.html($quick_login.find('#main_page #text_logged').val());

        $main_page.css('padding', '0');

        $quick_login.find('#link_login').css({
            'font-weight': 'bold',
            'letter-spacing': '2px'
        }).html($quick_login.find('#main_page #name').val());
		
		$quick_login_desktop.find('.link_login_desktop').css({
            'font-weight': 'bold',
            'letter-spacing': '2px'
        }).html($quick_login_desktop.find('#main_page #name').val());

// to hide the account menu at login
        if ($('#init').val() == '1') {
            $login.parents('.quick_login').removeClass('active');
        }
		
		if ($('#init').val() == '1') {
            $login_desktop.parents('.quick_login_desktop').removeClass('active');
        }

    }

    function initAfterLoad(data) {
        if (data.indexOf('link_logout') != -1) {
            belepInit();
        } else {
            initLoginForm();
        }
    }

});

