<?php ob_start(); ?>            
    <div class="col-lg-12 post-comm" id="comment-box">
        <div class="col-lg-1 post-comm-sec">
          <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
        </div>
        <div class="col-lg-11 post-comm-sec">
          <textarea rows="1" placeholder="Write a comment..." class="textarea-post" id="textarea-post"></textarea>
        </div>
    </div>
<?php echo ob_get_clean(); ?>