<?php ob_start(); ?>
<div class="col-lg-12 post-reply" id="reply-box-<?= $this->input->post('comment_id'); ?>">
    <div class="col-lg-1 post-comm-sec">
      <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
    </div>
    <div class="col-lg-11 post-comm-sec">
      <textarea rows="1" placeholder="Write a reply..." class="textarea-reply" id="textarea-reply-<?= $this->input->post('comment_id'); ?>"></textarea>
    </div>
</div>
<?php echo ob_get_clean(); ?>