<div class="row-fluid visible-desktop">
    <!--<div class="span8">-->
    <div class="click-login">
        <div class="welcome" id="welcome_desktop">
            <?php if (!$logged) { ?>
                

                     <?php $data = explode('{{LINK}}', $text_welcome); ?>
                        <?php echo $data[0]; ?>
                        
                       <div class="links_desktop login-text">
                        <?php if($logged){ ?>
                            <span id="" class="logged quick_login_desktop">
                        <?php }else{ ?>
                            <span class="quick_login_desktop">
                        <?php } ?>
                            &nbsp;  <a href="#" class="link_login_desktop"><?php echo $text_login; ?></a>
                            <span class="whole-sale ql_login_desktop" class="">
                                <div id="main_page">
                                    <form class="login_form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                
                                        <label id="label_email">
                                            <?php echo $entry_email; ?>
                                            <input type="text" name="email" id="email" class="datafields" value="<?php echo $email; ?>" />
                                        </label>
                                        <label id="label_password">
                                            <?php echo $entry_password; ?>
                                            <input type="password" name="password" id="password" class="datafields" value="<?php echo $password; ?>" />
                                        </label>
                                
                                
                                        <?php if (!empty($error_warning)) { ?>
                                            <label for="email" class="login_error" id="error_warning"><?php echo $error_warning; ?></label>
                                        <?php } ?>
                                
                                        <input type="submit" class="button button_login" id="" name="button_login" value="<?php echo $button_login; ?>" />
                                
                                        <div class="login_links">
                                            <a href="<?php echo $register; ?>" id="link_register"><?php echo $text_register; ?></a>
                                            <a href="<?php echo $forgotten; ?>" id="link_forgotten"><?php echo $text_forgotten; ?></a>
                                        </div>
                                
                                    </form>
                                </div>
                            
                            </span>
                        </span>
                 
                </div>
            
                             <?php echo $data[1]; ?>
                            

            <?php } else { ?>
                

                 <?php $data = explode('{{logout}}', $text_logged); ?>
                 <?php echo $data[0]; ?>
                    <div class="links_desktop"></div>

            
                   <div class="links_desktop login-text">
                    <?php if($logged){ ?>
                        <span id="" class="logged quick_login_desktop">
                    <?php }else{ ?>
                        <span class="quick_login_desktop">
                    <?php } ?>
                       &nbsp;  <a href="#" class="link_login_desktop"><?php echo $text_login; ?></a>
                        <span class="whole-sale ql_login_desktop" id="">
                            <div id="main_page">
                                    <form class="login_form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                
                                        <label id="label_email">
                                            <?php echo $entry_email; ?>
                                            <input type="text" name="email" id="email" class="datafields" value="<?php echo $email; ?>" />
                                        </label>
                                        <label id="label_password">
                                            <?php echo $entry_password; ?>
                                            <input type="password" name="password" id="password" class="datafields" value="<?php echo $password; ?>" />
                                        </label>
                                
                                
                                        <?php if (!empty($error_warning)) { ?>
                                            <label for="email" class="login_error" id="error_warning"><?php echo $error_warning; ?></label>
                                        <?php } ?>
                                
                                        <input type="submit" class="button button_login" id="" name="button_login" value="<?php echo $button_login; ?>" />
                                
                                        <div class="login_links">
                                            <a href="<?php echo $register; ?>" id="link_register"><?php echo $text_register; ?></a>
                                            <a href="<?php echo $forgotten; ?>" id="link_forgotten"><?php echo $text_forgotten; ?></a>
                                        </div>
                                
                                    </form>
                                </div>
                        
                        </span>
                    </span>
                </div>
            
        
                 <?php echo $data[1]; ?>


            <?php } ?>
        </div>
    </div>
</div>
<div class="visible-tablet visible-phone">
																								
	
									<div class="click-login">
										<div class="welcome">
											<?php  if (1==1) { ?>
												
                
                     <?php $data = explode('{{LINK}}', $text_welcome); ?>
    <?php echo $data[0]; ?>
    <div class="links"></div>

                
                       <div class="links login-text">
                         <?php if($logged){ ?>
                            <span class="quick_login" id="quick_login" class="logged">
                        <?php }else{ ?>
                            <span class="quick_login" id="quick_login">
                        <?php } ?>
                            <a href="#" class="link_login" id="link_login"><?php echo $text_login; ?></a>
                            <span class="whole-sale ql_login" id="ql_login">
                            	<div id="main_page">
                                    <form class="login_form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                
                                        <label id="label_email">
                                            <?php echo $entry_email; ?>
                                            <input type="text" name="email" id="email" class="datafields" value="<?php echo $email; ?>" />
                                        </label>
                                        <label id="label_password">
                                            <?php echo $entry_password; ?>
                                            <input type="password" name="password" id="password" class="datafields" value="<?php echo $password; ?>" />
                                        </label>
                                
                                
                                        <?php if (!empty($error_warning)) { ?>
                                            <label for="email" class="login_error" id="error_warning"><?php echo $error_warning; ?></label>
                                        <?php } ?>
                                
                                        <input type="submit" class="button button_login" id="" name="button_login" value="<?php echo $button_login; ?>" />
                                
                                        <div class="login_links">
                                            <a href="<?php echo $register; ?>" id="link_register"><?php echo $text_register; ?></a>
                                            <a href="<?php echo $forgotten; ?>" id="link_forgotten"><?php echo $text_forgotten; ?></a>
                                        </div>
                                
                                    </form>
                                </div>
                            </span>
                        </span>
                    </div>
                
            
    <?php echo $data[1]; ?>
                
            
											<?php } else { ?>
												
                
                     <?php $data = explode('{{logout}}', $text_logged); ?>
                     <?php echo $data[0]; ?>
                        <div class="links"></div>

                
                       <div class="links login-text">
                         <?php if($logged){ ?>
                            <span id="quick_login" class="logged">
                        <?php }else{ ?>
                            <span id="quick_login">
                        <?php } ?>
                            <a href="#" id="link_login"><?php echo $text_login; ?></a>
                            <span class="whole-sale" id="ql_login"></span>
                        </span>
                    </div>
                
            
                     <?php echo $data[1]; ?>
                
            
											<?php } ?>
										</div>
									</div>
				

									</div>
<script type="text/javascript">
                        var quick_login = quick_login || {};
						var quick_login_desktop = quick_login_desktop || {};
                        <?php ?>
                        $.extend(quick_login,{
                            messages:<?php echo json_encode(array(
                                'error_email_ql'=>$error_email,
                                'error_password_ql'=>$error_password,
                                'error_login_ql'=>$error_login,
                                'error_approved_ql'=>$error_approved
                            )) ?>
                        });
						
						$.extend(quick_login_desktop,{
                            messages:<?php echo json_encode(array(
                                'error_email_ql'=>$error_email,
                                'error_password_ql'=>$error_password,
                                'error_login_ql'=>$error_login,
                                'error_approved_ql'=>$error_approved
                            )) ?>
                        });
                    </script>
                    
                    <?php if (!empty($error_warning)) { ?>
                    <script type="text/javascript">
                    $(document).ready(function(){
                    	$('.link_login_desktop').click();
                    	$('.link_login').click();
                    	
                    	setTimeout(function(){
                    		$.ajax({
								url: 'index.php?route=common/quick_login/removeerror',
								type: 'post',
								dataType: 'json',
								success: function(json) {
									console.log($json);
								}
							});
                    	},3000)
                    	
                    })
                    </script>
                    <?php } ?>
