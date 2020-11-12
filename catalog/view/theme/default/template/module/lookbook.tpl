<div id="tablet-indicator"></div>
<div id="mobile-indicator"></div>	        
<div id="mobile2-indicator"></div>	
<div id="tablet2-indicator"></div>	        
        <link href='catalog/view/css/jquery.gridly.css' rel='stylesheet' type='text/css'>
       
        <script type="text/javascript" src="catalog/view/javascript/jquery_v1.10.2.js"></script> 
        <script>
           var my_jQuery = jQuery.noConflict(true);
        </script>	
        <script type="text/javascript" src="catalog/view/javascript/jquery.gridly.js"></script> 
        
        <?php
        if($route == "product/category")
        {
        ?>
         <link href='catalog/view/css/sample_cat.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="catalog/view/javascript/sample_cat.js"></script> 
        <?php
          }
          else
          {
          ?>
           <link href='catalog/view/css/sample_tab.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="catalog/view/javascript/sample_tab.js"></script> 
          <?php
          }
        ?>
        
        <script type="text/javascript" src="catalog/view/javascript/rainbow.js"></script> 

<div class='content'>
    <section class='example' >
        <div class='gridly'>
         
		 <?php
            foreach ($lookbooks as $lookbook) {
           // $image_tags = unserialize($lookbook['image_tag_info']);
           $image_tags = $lookbook['image_tag_info'];
         ?>
		  <div class='brick small <?php if($lookbook['no_of_tags'] == 1) echo 'single_tag'; ?>'>
          <img src="lookbook/<?php echo '../image/lookbook/'.$lookbook['image_name']?>" style="width:100%;" class="imgbrick"  />
          <?php
		    foreach ($image_tags as $img) {   
              
              if($lookbook['no_of_tags'] == 1) { ?>
               <div class=""  style="left:40%; top:40%;width:40px;height:40px;position:absolute;">
               <?php 
               }
               else
               {
               ?>
               <div class="hotspot"  style="left:<?php echo $img[3];?>%; top:<?php echo $img[4];?>%;width:40px;height:40px;position:absolute;">
               <?php } ?>
         
         
         <img class="tagimg" src="image/price_info.png" style="left:0px;top:0px;<?php if($lookbook['no_of_tags'] == 1) echo 'display:none;'; ?>" id="tag-<?php echo $img[3];?>_<?php echo $img[4];?>"  rel="<?php echo $img[3];?>_<?php echo $img[4];?>" >
         <div class="product_info" style=" left: 20px; top: 20px;">
         <div>
         <div class="sk_image">
         <img src="<?php  echo 'image/'.$img[5];?>" class="thumbimg"></div>         
         <div class="sk_info">
         <p class="sk_title"><?php if(strlen($img[2]) > 0) { ?><a href="<?php  echo $img[2];?>"  onclick="window.open('<?php  echo $img[2];?>', '_blank')"><?php } ?><?php  echo $img[0];?><?php if(strlen($img[2]) > 0) { ?></a><?php } ?></p>
         <p class="price"><?php if(strlen($img[2]) > 0) { ?><a href="<?php  echo $img[2];?>" onclick="window.open('<?php  echo $img[2];?>', '_blank')"><?php  echo $img[1];?></a><?php } else echo ""."$img[1]"; ?></p>
         
         </div>
         </div></div>
        
         </div>
         
         
          <?php } ?>
            <a class='delete' href='#'>&times;</a>      
             <div style="clear:both"></div>
                </div>
             
         <?php
           }
		 
		 ?>
		
        </div>
        <p class='actions' >
          <a class='add button' href='#' style="font-weight:normal;">Add</a>      
           <a class='shuffle button' href='#' style="font-weight:normal;">Shuffle</a>      
            </p>
          </div>  
      </section>
      
      
      
      
     
     
      
    
  
    
    
    
    
    <div style="display:none" id="remtags">
       <?php
		    foreach ($remlookbooks as $lookbook) {
            // $image_tags = unserialize($lookbook['image_tag_info']);
             $image_tags = $lookbook['image_tag_info'];
		 ?>
		  <div class='brick small newtag <?php if($lookbook['no_of_tags'] == 1) echo 'single_tag'; ?>'>
          <img rel="lookbook/<?php echo '../image/lookbook/'.$lookbook['image_name']?>" src='image/price_info.png' style="width:100%;" class="imgbrick"  />
          <?php
		    foreach ($image_tags as $img) {   
               
		  if($lookbook['no_of_tags'] == 1) { ?>
         <div class=""  style="left:40%; top:40%;width:40px;height:40px;position:absolute;">
         <?php 
         }
         else
         {
         ?>
         <div class="hotspot"  style="left:<?php echo $img[3];?>%; top:<?php echo $img[4];?>%;width:40px;height:40px;position:absolute;">
         <?php } ?>
         
         
         <img class="tagimg"  style="left:0px;top:0px;<?php if($lookbook['no_of_tags'] == 1) echo 'display:none;'; ?>" id="tag-<?php echo $img[3];?>_<?php echo $img[4];?>"  src="image/price_info.png" >
         <div class="product_info" style=" left: 20px; top: 20px;">
         <div>
         <div class="sk_image">
         <img rel="<?php  echo 'image/'.$img[5];?>" class="thumbimg" src='image/price_info.png' ></div>         
         <div class="sk_info">
        <p class="sk_title"><?php if(strlen($img[2]) > 0) { ?><a href="<?php  echo $img[2];?>"  onclick="window.open('<?php  echo $img[2];?>', '_blank')"><?php } ?><?php  echo $img[0];?><?php if(strlen($img[2]) > 0) { ?></a><?php } ?></p>
         <p class="price"><?php if(strlen($img[2]) > 0) { ?><a href="<?php  echo $img[2];?>"  onclick="window.open('<?php  echo $img[2];?>', '_blank')"><?php  echo $img[1];?></a><?php } else echo ""."$img[1]"; ?></p>
         </div>
         </div></div>
        
         </div>
         
         
          <?php } ?>
            <a class='delete' href='#'>&times;</a>      
             <div style="clear:both"></div>
                </div>
             
             
         <?php
           }
		 
		 ?>
     <script>
       $(document).ready(function(){
        
        var timesRun = 0;
        var interval = setInterval(function(){
            timesRun += 1;
            if(timesRun === 4){
                clearInterval(interval);
            }
             var lookbook=$(".example").html();
              $(".example").html("");
              $(".example").html(lookbook);
        }, 5000); 
       });
     </script>
		</div>
		
	