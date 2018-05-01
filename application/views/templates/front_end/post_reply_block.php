<?php ob_start(); ?>
                
<div class="col-lg-12 col-md-12 post-reply">
    <div class="col-lg-1 col-md-1 post-comm-sec">
        <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
    </div>
    <?php $reply = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>',  $result['reply']))))); ?>
    <div class="col-lg-11 col-md-11 post-comm-sec text-justify"><strong class="comm-name">  <?= $result['full_name'] ?> </strong>
        &nbsp;<span class="comment-content"><?= $reply ?></span>
        <br>
        <ul class="comm-actions pull-left"> 
            <li class="reply-actions-li">
                <a href="javascript:void(0)" id="reply-like-<?= $result['reply_id']; ?>">Like </a>
            </li><!-- 
            <li class="comm-actions-li" id="reply-<?= $result['reply_id']; ?>">
                <a href="javascript:void(0)">Reply</a>
            </li> -->
            <li class="reply-actions-li">
                <time class="timeago" datetime="<?= $result['date'] . ' ' .  $result['time']; ?>"></time>
            </li>
            <li class="reply-actions-li">
                <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                <span class="comment-like-count" id="reply-like-count-<?= $result['reply_id']; ?>"></span>
            </li>
            <?php $user_cookie = $this->input->cookie('user', TRUE); 
                $user_cookie = explode(',', $user_cookie);
            ?>
            <?php if($result['reply_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                <li class="reply-actions-li pull-right">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle comm-edit-delete" type="button" id="menu1" data-toggle="dropdown">
                        <span class="fa fa-angle-down"></span></button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                            <li role="presentation" id="reply-edit-<?= $result['reply_id']; ?>"><a role="menuitem" href="javascript:void(0)">Edit</a></li>
                            <li role="presentation" id="reply-delete-<?= $result['reply_id']; ?>"><a role="menuitem" href="javascript:void(0)">Delete</a></li>
                        </ul>
                    </div>
                </li>
            <?php endif ?>
        </ul>
    </div>
</div>
<!-- <div class="clearfix"></div> -->
<?php echo ob_get_clean(); ?>