<?php if($product_articles) : ?>
    <h2>Related Article/Video</h2>
    <div class="row-fluid">
        <?php foreach ( $product_articles as $product_article ) : ?>
            <div class="span3">
                <img src="<?=$product_article['image']?>" width="150" height="200" alt="<?=$product_article['post_title']?>"/>
                <p><a href="<?=$product_article['guid']?>" style="display:block;"><?=$product_article['post_title']?></a></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>