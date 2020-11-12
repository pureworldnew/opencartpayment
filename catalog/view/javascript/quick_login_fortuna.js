$(function(){

    var $quick_login = $('#quick_login'),
        $login = $quick_login.find('#ql_login')
        $imgs = $login.find('img'),
        $main_page = $login.find('#main_page'),
        $header = $('#header'),
        $nav_collapse = $header.find('.nav-collapse'),
        $welcome = $header.find('#welcome');

    function loadingPic_Show(){
        var width_windowLogin = parseInt($login.css('width')),
            height_windowLogin = parseInt($login.css('height')),
            width_loadingPic = 100, height_loadingPic = 100;

        $login.html('<img src="catalog/view/javascript/loading.gif" />');

        $imgs.css({
            'position': 'relative',
            'top': ((height_windowLogin/2) - (height_loadingPic/2)) + 'px',
            'left': ((width_windowLogin/2) - (width_loadingPic/2)) + 'px'
        });
    }


    function moduleLoad_login(){
        $.ajax({
            url: 'index.php?route=module/quick_login',
            type:'get',
            success: function(response) {
                $login.html(response);
                initAfterLoad(response);
            }
        });
    }

    function moduleLoad_logout(){
        $.ajax({
            url: 'index.php?route=module/quick_login/logout',
            type:'get',
            success: function(){
                location.reload(true);
            },
            error: function(){
                location.reload(true);
            }
        });
    }

    function init(){
        $nav_collapse.on('click','#link_login',function(e){
            e.preventDefault();
            $(this).parents('#quick_login').toggleClass('active');
            var $target = $(e.target).html();
                // to show the account menu (the other condition is in the quick_login_logged.tpl)
            if ($target != $('#text_hidden_login').val() && $target.indexOf('http://') == -1){
                $('#init').val('0');
            }
        });

        $nav_collapse.find('#link_logout').live('click',function(e){
            e.preventDefault();
            moduleLoad_logout();
            //$(this).parents('#quick_login').toggleClass('logged');
        });

            // it hides the $container if you click on else place
            // first this will be executed then the event on
        $(document).mouseup(function(e) {
            //var $container = $login;
            if (!$login.is(e.target) && $login.has(e.target).length === 0) {
                if (typeof $login.parents('#quick_login').attr('class') != 'undefined' &&
                    $login.parents('#quick_login').attr('class').indexOf('active') > -1 &&
                    $(e.target).html() != $login.parents('#quick_login').find('#link_login').html()){
                        $login.parents('#quick_login').removeClass('active');
                }
            }
        });

        $quick_login.find('#login_form input').keydown(function(e) {
            if (e.keyCode == 13) {
                $quick_login.find('#login_form').submit();
            }
        });

    }

    loadingPic_Show();
    moduleLoad_login();
    init();

    function initLoginForm(){
    
        $("#login_form").validate({
            rules:{
                email_ql:{
                    required: true,
                    email: true
                },
                password_ql: "required"
            },
            messages:{
                email_ql:{
                    required: quick_login.messages.error_email_ql,
                    email: quick_login.messages.error_email_ql
                },
                password_ql: quick_login.messages.error_password_ql
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element).css({
                  'padding-left':'18px',
                  'margin-top': '-5px'
                });
            },
            submitHandler: function(form){
                alert($("#ql_email").val()+', '+$("#ql_password").val());
                var action = $('#login_form').attr('action'),
                    email = $("#ql_email").val(),
                    password = $("#ql_password").val();
                $("#main_page").remove();
                loadingPic_Show();
                $.post(action,
                    {
                        email: email,
                        password: password
                    },
                    function(data){
                        $login.html(data);
                        initAfterLoad(data);
                    }
                );
            }
        });
       
    }

    function belepInit(){
        $quick_login.addClass('logged');
        
        $welcome.html($quick_login.find('#main_page #text_logged').val());

        $main_page.css('padding','0');

        $quick_login.find('#link_login').css({
            'font-weight': 'bold',
            'letter-spacing': '2px',
            'color': '#0076A8'
        }).html($quick_login.find('#main_page #name').val());

            // to hide the account menu at login
        if ($('#init').val()=='1'){
            $login.parents('#quick_login').removeClass('active');
        }

    }

    function initAfterLoad(data){
        if(data.indexOf('link_logout') != -1){
            belepInit();
        }else{
            initLoginForm();
            
        }
    }
});

