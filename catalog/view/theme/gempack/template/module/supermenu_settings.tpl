<link rel="stylesheet" type="text/css" href="catalog/view/supermenu/supermenu.css?v=2" />
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/supermenu/supermenuie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/supermenu/supermenuie6.css" />
<![endif]-->
<?php if ($supermenuisresponsive) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/supermenu/supermenu-responsive.css" />
<script type="text/javascript" src="catalog/view/supermenu/supermenu-responsive.js"></script>
<?php } else { ?>
<script type="text/javascript" src="catalog/view/supermenu/supermenu.js?v=2"></script>
<?php } ?>
<?php if ($usehoverintent) { ?>
<script type="text/javascript" src="catalog/view/supermenu/jquery.hoverIntent.minified.js"></script>
<?php } ?>
<?php if ($supermenu_settings_status) { ?>
<style type="text/css"> 
<?php if ($supermenu_settings['fontf']) { ?>
 #supermenu, #supermenu-mobile { font-family: <?php echo $supermenu_settings['fontf']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['topfont']) { ?>
 #supermenu ul li a.tll, #supermenu-mobile ul li div .withchild a.theparent { font-size: <?php echo $supermenu_settings['topfont']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['dropfont']) { ?>
 #supermenu ul li div a, #supermenu-mobile ul li div .withchild > ul li a { font-size: <?php echo $supermenu_settings['dropfont']; ?> !important; }
<?php } ?>
<?php if ($supermenu_settings['bg'] && $supermenu_settings['bg2']) { ?>
 #supermenu { 
    background-color:<?php echo $supermenu_settings['bg']; ?>;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $supermenu_settings['bg']; ?>), to(<?php echo $supermenu_settings['bg2']; ?>));
    background-image: -webkit-linear-gradient(top, <?php echo $supermenu_settings['bg']; ?>, <?php echo $supermenu_settings['bg2']; ?>); 
    background-image: -moz-linear-gradient(top, <?php echo $supermenu_settings['bg']; ?>, <?php echo $supermenu_settings['bg2']; ?>);
    background-image: -ms-linear-gradient(top, <?php echo $supermenu_settings['bg']; ?>, <?php echo $supermenu_settings['bg2']; ?>);
    background-image: -o-linear-gradient(top, <?php echo $supermenu_settings['bg']; ?>, <?php echo $supermenu_settings['bg2']; ?>);
 }
<?php } elseif ($supermenu_settings['bg'] && !$supermenu_settings['bg2']) { ?>
 #supermenu, #supermenu-mobile { background-color:<?php echo $supermenu_settings['bg']; ?>; }
<?php } elseif (!$supermenu_settings['bg'] && $supermenu_settings['bg2']) { ?>
 #supermenu, #supermenu-mobile { background-color:<?php echo $supermenu_settings['bg2']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['tmborderpx'] && $supermenu_settings['tmborders'] && $supermenu_settings['tmbordero'] && $supermenu_settings['tmborderc']) { ?>
	<?php if ($supermenu_settings['tmborderpx'] != 'default') { ?>
#supermenu, #supermenu-mobile { 
		<?php if ($supermenu_settings['tmbordero'] == 'all-around') { ?>
border: <?php echo $supermenu_settings['tmborderpx']; ?> <?php echo $supermenu_settings['tmborders']; ?> <?php echo $supermenu_settings['tmborderc']; ?>; 
		<?php } else {?>
border-<?php echo $supermenu_settings['tmbordero']; ?>: <?php echo $supermenu_settings['tmborderpx']; ?> <?php echo $supermenu_settings['tmborders']; ?> <?php echo $supermenu_settings['tmborderc']; ?>; 		
		<?php } ?>
	}
	<?php } ?>
<?php } ?>
<?php if ($supermenu_settings['tlc']) { ?>
 #supermenu ul li a.tll, #supermenu-mobile ul li a.tll { color: <?php echo $supermenu_settings['tlc']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['tlch']) { ?>
 #supermenu ul li.tlli:hover a.tll { color: <?php echo $supermenu_settings['tlch']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['tlcts']) { ?>
 #supermenu ul li a.tll, #supermenu-mobile ul li a.tll { text-shadow: 0px 1px 1px <?php echo $supermenu_settings['tlcts']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['tlchts']) { ?>
 #supermenu ul li.tlli:hover a.tll { text-shadow: 0px 1px 1px <?php echo $supermenu_settings['tlchts']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['tlb']) { ?>
 #supermenu ul li.tlli:hover a.tll { background: <?php echo $supermenu_settings['tlb']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['dbg']) { ?>
 #supermenu ul li div.bigdiv, #supermenu-mobile ul li div.bigdiv { background: <?php echo $supermenu_settings['dbg']; ?>; }
<?php if (!$supermenu_settings['fybg']) { ?>
 #supermenu ul li div.bigdiv.withflyout > .withchildfo > .flyouttoright { background: <?php echo $supermenu_settings['dbg']; ?>; }
<?php } ?>
<?php } ?>
<?php if ($supermenu_settings['slborderpx'] && $supermenu_settings['slborders'] && $supermenu_settings['slbordero'] && $supermenu_settings['slborderc']) { ?>
	<?php if ($supermenu_settings['slborderpx'] != 'default') { ?>
#supermenu ul li div.bigdiv, #supermenu-mobile ul li div.bigdiv { 
		<?php if ($supermenu_settings['slbordero'] == 'all-around') { ?>
border: <?php echo $supermenu_settings['slborderpx']; ?> <?php echo $supermenu_settings['slborders']; ?> <?php echo $supermenu_settings['slborderc']; ?>; 
		<?php } else {?>
border: none;
border-<?php echo $supermenu_settings['slbordero']; ?>: <?php echo $supermenu_settings['slborderpx']; ?> <?php echo $supermenu_settings['slborders']; ?> <?php echo $supermenu_settings['slborderc']; ?>; 		
		<?php } ?>
	}
	<?php } ?>
<?php } ?>
<?php if ($supermenu_settings['dic']) { ?>
 #supermenu ul li div .withchild a.theparent, #supermenu-mobile ul li div .withchild a.theparent, #supermenu ul li div .withimage .name a, #supermenu ul li div .dropbrands ul li a { color: <?php echo $supermenu_settings['dic']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['dich']) { ?>
 #supermenu ul li div .withchild a.theparent:hover, #supermenu-mobile ul li div .withchild a.theparent:hover, #supermenu ul li div .withimage .name a:hover, #supermenu ul li div .dropbrands ul li a:hover { color: <?php echo $supermenu_settings['dich']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['dib']) { ?>
 #supermenu ul li div .withchild a.theparent, #supermenu-mobile ul li div .withchild a.theparent { background: <?php echo $supermenu_settings['dib']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['dibh']) { ?>
 #supermenu ul li div .withchild a.theparent:hover, #supermenu-mobile ul li div .withchild a.theparent:hover { background: <?php echo $supermenu_settings['dibh']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['diborderpx'] && $supermenu_settings['diborders'] && $supermenu_settings['dibordero'] && $supermenu_settings['diborderc']) { ?>
	<?php if ($supermenu_settings['diborderpx'] != 'default') { ?>
#supermenu ul li div .withchild a.theparent, #supermenu-mobile ul li div .withchild a.theparent, #supermenu-mobile ul li div .withchild > ul li a { 
		<?php if ($supermenu_settings['dibordero'] == 'all-around') { ?>
border: <?php echo $supermenu_settings['diborderpx']; ?> <?php echo $supermenu_settings['diborders']; ?> <?php echo $supermenu_settings['diborderc']; ?>; 
		<?php } else {?>
border-<?php echo $supermenu_settings['dibordero']; ?>: <?php echo $supermenu_settings['diborderpx']; ?> <?php echo $supermenu_settings['diborders']; ?> <?php echo $supermenu_settings['diborderc']; ?>; 		
		<?php } ?>
	}
	<?php } ?>
<?php } ?>
<?php if ($supermenu_settings['slc']) { ?>
 #supermenu ul li div .withchild ul.child-level li a, #supermenu-mobile ul li div .withchild > ul li a, #supermenu ul li div .withimage .name ul a { color: <?php echo $supermenu_settings['slc']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['slch']) { ?>
 #supermenu ul li div .withchild ul.child-level li a:hover, #supermenu-mobile ul li div .withchild > ul li a:hover, #supermenu ul li div .withimage .name ul a:hover { color: <?php echo $supermenu_settings['slch']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['slb']) { ?>
 #supermenu ul li div .withchild ul.child-level li a { background: <?php echo $supermenu_settings['slb']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['slbh']) { ?>
 #supermenu ul li div .withchild ul.child-level li a:hover { background: <?php echo $supermenu_settings['slbh']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['fybg']) { ?>
 #supermenu ul li div.bigdiv.withflyout > .withchildfo > .flyouttoright { background: <?php echo $supermenu_settings['fybg']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['flyic']) { ?>
 #supermenu .withchildfo > a.theparent { color: <?php echo $supermenu_settings['flyic']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['flyich']) { ?>
 #supermenu .withchildfo:hover > a.theparent { color: <?php echo $supermenu_settings['flyich']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['flyiborderpx'] && $supermenu_settings['flyiborders'] && $supermenu_settings['flyibordero'] && $supermenu_settings['flyiborderc']) { ?>
	<?php if ($supermenu_settings['flyiborderpx'] != 'default') { ?>
#supermenu .withchildfo { 
		<?php if ($supermenu_settings['flyibordero'] == 'all-around') { ?>
border: <?php echo $supermenu_settings['flyiborderpx']; ?> <?php echo $supermenu_settings['flyiborders']; ?> <?php echo $supermenu_settings['flyiborderc']; ?>; 
		<?php } else {?>
border-<?php echo $supermenu_settings['flyibordero']; ?>: <?php echo $supermenu_settings['flyiborderpx']; ?> <?php echo $supermenu_settings['flyiborders']; ?> <?php echo $supermenu_settings['flyiborderc']; ?>; 		
		<?php } ?>
	}
	<?php } ?>
<?php } ?>
<?php if ($supermenu_settings['flyib']) { ?>
 #supermenu .withchildfo:hover { background: <?php echo $supermenu_settings['flyib']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['drtc']) { ?>
 #supermenu ul li div.bigdiv .headingoftopitem h2 a, #supermenu ul li div.bigdiv .headingoftopitem h2, #supermenu ul li div .dropbrands span { color: <?php echo $supermenu_settings['drtc']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['drtborderpx'] && $supermenu_settings['drtborders'] && $supermenu_settings['drtbordero'] && $supermenu_settings['drtborderc']) { ?>
	<?php if ($supermenu_settings['drtborderpx'] != 'default') { ?>
#supermenu ul li div.bigdiv .headingoftopitem, #supermenu ul li div .dropbrands span { 
		<?php if ($supermenu_settings['drtbordero'] == 'all-around') { ?>
border: <?php echo $supermenu_settings['drtborderpx']; ?> <?php echo $supermenu_settings['drtborders']; ?> <?php echo $supermenu_settings['drtborderc']; ?>; 
		<?php } else {?>
border-<?php echo $supermenu_settings['drtbordero']; ?>: <?php echo $supermenu_settings['drtborderpx']; ?> <?php echo $supermenu_settings['drtborders']; ?> <?php echo $supermenu_settings['drtborderc']; ?>; 		
		<?php } ?>
	}
	<?php } ?>
<?php } ?>
<?php if ($supermenu_settings['pricec']) { ?>
#supermenu ul li div .withimage .dropprice { color: <?php echo $supermenu_settings['pricec']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['pricech']) { ?>
#supermenu ul li div .withimage .dropprice span { color: <?php echo $supermenu_settings['pricech']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['valc']) { ?>
#supermenu ul li div.bigdiv .linkoftopitem a { color: <?php echo $supermenu_settings['valc']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['valch']) { ?>
#supermenu ul li div.bigdiv .linkoftopitem a:hover { color: <?php echo $supermenu_settings['valch']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['valb'] && $supermenu_settings['valb2']) { ?>
#supermenu ul li div.bigdiv .linkoftopitem a {
    background-color:<?php echo $supermenu_settings['valb']; ?>;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $supermenu_settings['valb']; ?>), to(<?php echo $supermenu_settings['valb2']; ?>));
    background-image: -webkit-linear-gradient(top, <?php echo $supermenu_settings['valb']; ?>, <?php echo $supermenu_settings['valb2']; ?>); 
    background-image: -moz-linear-gradient(top, <?php echo $supermenu_settings['valb']; ?>, <?php echo $supermenu_settings['valb2']; ?>);
    background-image: -ms-linear-gradient(top, <?php echo $supermenu_settings['valb']; ?>, <?php echo $supermenu_settings['valb2']; ?>);
    background-image: -o-linear-gradient(top, <?php echo $supermenu_settings['valb']; ?>, <?php echo $supermenu_settings['valb2']; ?>);
 }
#supermenu ul li div.bigdiv .linkoftopitem a:hover {
    background-color:<?php echo $supermenu_settings['valb2']; ?>;
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $supermenu_settings['valb2']; ?>), to(<?php echo $supermenu_settings['valb']; ?>));
    background-image: -webkit-linear-gradient(top, <?php echo $supermenu_settings['valb2']; ?>, <?php echo $supermenu_settings['valb']; ?>); 
    background-image: -moz-linear-gradient(top, <?php echo $supermenu_settings['valb2']; ?>, <?php echo $supermenu_settings['valb']; ?>);
    background-image: -ms-linear-gradient(top, <?php echo $supermenu_settings['valb2']; ?>, <?php echo $supermenu_settings['valb']; ?>);
    background-image: -o-linear-gradient(top, <?php echo $supermenu_settings['valb2']; ?>, <?php echo $supermenu_settings['valb']; ?>);
 }
<?php } elseif ($supermenu_settings['valb'] && !$supermenu_settings['valb2']) { ?>
#supermenu ul li div.bigdiv .linkoftopitem a, #supermenu ul li div.bigdiv .linkoftopitem a:hover { background-image: none; background-color:<?php echo $supermenu_settings['valb']; ?>; }
<?php } elseif (!$supermenu_settings['valb'] && $supermenu_settings['valb2']) { ?>
#supermenu ul li div.bigdiv .linkoftopitem a, #supermenu ul li div.bigdiv .linkoftopitem a:hover { background-image: none; background-color:<?php echo $supermenu_settings['valb2']; ?>; }
<?php } ?>
<?php if ($supermenu_settings['valborderpx'] && $supermenu_settings['valborders'] && $supermenu_settings['valbordero'] && $supermenu_settings['valborderc']) { ?>
	<?php if ($supermenu_settings['valborderpx'] != 'default') { ?>
#supermenu ul li div.bigdiv .linkoftopitem a { 
		<?php if ($supermenu_settings['valbordero'] == 'all-around') { ?>
border: <?php echo $supermenu_settings['valborderpx']; ?> <?php echo $supermenu_settings['valborders']; ?> <?php echo $supermenu_settings['valborderc']; ?>; 
		<?php } else {?>
border: 0px;
border-<?php echo $supermenu_settings['valbordero']; ?>: <?php echo $supermenu_settings['valborderpx']; ?> <?php echo $supermenu_settings['valborders']; ?> <?php echo $supermenu_settings['valborderc']; ?>; 		
		<?php } ?>
	}
	<?php } ?>
<?php } ?>
</style>
<?php } ?>