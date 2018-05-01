<?php ob_start(); ?>
                
<div class="col-lg-12 col-md-12 post-comm post-comm-sec-1 comment" id="<?= 'comment_id_' . $result['comment_id']; ?>">
    <div class="col-lg-1 col-md-1 post-comm-sec comm-details">
        <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
    </div>
    <div class="col-lg-11 col-md-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= $result['full_name'] ?> </strong>&nbsp;
        <span class="comment-content"><?= $result['comment'] ?></span>
        <br>
        <ul class="comm-actions pull-left"> 
            <li class="comm-actions-li">
                <a href="javascript:void(0)" id="comm-like-<?= $result['comment_id']; ?>">Like </a>
            </li>
            <li class="comm-actions-li">
                <a href="javascript:void(0)" id="comm-reply-<?= $result['comment_id']; ?>">Reply</a>
            </li>
            <li class="comm-actions-li">
                <time class="timeago" datetime="<?= $result['date'] . ' ' .  $result['time']; ?>"></time>
            </li>
            <li class="comm-actions-li">
                <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                <span class="comment-like-count" id="comment-like-count-<?= $result['comment_id']; ?>"></span>
            </li>
            <li class="comm-actions-li pull-right">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle comm-edit-delete" type="button" id="menu1" data-toggle="dropdown">
                    <span class="fa fa-angle-down"></span></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                      <li role="presentation" id="comm-edit-<?= $result['comment_id']; ?>"><a role="menuitem" href="javascript:void(0)">Edit</a></li>
                      <li role="presentation" id="comm-delete-<?= $result['comment_id']; ?>"><a role="menuitem" href="javascript:void(0)">Delete</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
<input type="hidden" name="post_comments_count_db" id="post_comments_count_db" value="<?= $result['post_comments_count']; ?>">
<!-- <div class="clearfix"></div> -->
<?php echo ob_get_clean(); ?>