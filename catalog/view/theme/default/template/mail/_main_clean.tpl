<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="all" />
	<meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="author" content="Opencart-Templates" />
    
	<title><?php echo $title; ?></title>
</head>
<body text="<?php echo $config['body_font_color']; ?>" link="<?php echo $config['body_link_color']; ?>" alink="<?php echo $config['body_link_color']; ?>" vlink="<?php echo $config['body_link_color']; ?>" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<?php if (!empty($emailtemplate['preview'])) { ?>
	<div style="display:none !important; font-size:0 !important; line-height:0 !important;"><?php echo $emailtemplate['preview']; ?></div>
<?php } ?>
<div id="emailTemplate">
<table class="emailStyle<?php echo ucwords($config['style']); ?>" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?php echo $config['body_bg_color']; ?>">
	<tr>
    	<td class="emailWrapper" bgcolor="<?php echo $config['body_bg_color']; ?>">
    	
			<table id="emailPage" border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="100%" align="<?php echo $config['page_align']; ?>" valign="top">
						<?php if ($config['page_align'] == 'center') { ?><center>
						<!--[if mso]>
							<table align="center" border="0" cellspacing="0" cellpadding="0" width="<?php echo $config['email_full_width']; ?>"><tr><td>
						<![endif]--><?php } ?>
						<div class="mainContainer" align="<?php echo $config['page_align']; ?>">												
						<table class="mainContainer" width="100%" cellpadding="0" cellspacing="0" border="0" align="<?php echo $config['page_align']; ?>">            				
            				<tr> 
                				<td>
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td class="emailPageInner emailPageInnerTop" width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo $config['page_bg_color']; ?>">&nbsp;</td>
										</tr>
                						<tr>
											<td align="<?php echo $config['text_align']; ?>" class="emailMainText" bgcolor="<?php echo $config['page_bg_color']; ?>">
												{CONTENT}
											</td>
										</tr>
										<?php if ($config['page_footer_text']) { ?>
											<tr><td bgcolor="<?php echo $config['page_bg_color']; ?>"><?php echo $config['page_footer_text']; ?></td></tr>
										<?php } ?>
										<tr>
											<td class="emailPageInner emailPageInnerBottom" width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo $config['page_bg_color']; ?>">&nbsp;</td>
										</tr>
									</table>							
								</td>
							</tr>							
						</table>												
						</div>
						<?php if ($config['page_align'] == 'center') { ?><!--[if mso]>
							</td></tr></table>
						<![endif]-->	
						</center><?php } ?>											 
					</td>
				</tr>
			</table>
		
		</td>
	</tr>
</table>
</div>

<style type="text/css">												        				 												        				 
v\:* { behavior: url(#default#VML); display:inline-block} /* background image hack for outlook */

</style>
</body>
</html>