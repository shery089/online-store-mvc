<?php ob_start(); ?>            

    <div class="col-lg-11 col-md-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= $result['full_name'] ?> </strong>&nbsp;
        <span class="comment-content"><?= ucwords($result['reply']) ?></span>
        <br>
        <ul class="comm-actions pull-left"> 
            <li class="reply-actions-li">
                <a href="javascript:void(0)" id="comm-like-<?= $result['reply_id']; ?>"><?= $result['has_liked'] > 0 ? 'Unlike' : 'Like'; ?> </a>
            </li>
            <li class="reply-actions-li">
                <time class="timeago" datetime="<?= $result['date'] . ' ' .  $result['time']; ?>"></time>
            </li>
            <li class="reply-actions-li">
                <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                <span class="comment-like-count" id="reply-like-count-<?= $result['reply_id']; ?>"><?= $result['likes'] ?></span>
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
        <?php endif; ?>
        </ul>
    </div>
        <input type="hidden" name="last_row" id="last_row" value="<?= $result['last_row']; ?>">
        <input type="hidden" id="last_comment_id" value="<?= $result['comment_id']; ?>">
    <script>
        $("time.timeago").timeago();
    </script>
<?php echo ob_get_clean(); ?>