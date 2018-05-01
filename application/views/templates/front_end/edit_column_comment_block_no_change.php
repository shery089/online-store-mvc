<?php ob_start(); ?>            
    <div class="col-lg-11 col-md-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= $result['full_name'] ?> </strong>&nbsp;
        <span class="comment-content"><?= ucwords($result['comment']) ?></span>
        <br>
        <ul class="comm-actions pull-left"> 
            <li class="comm-actions-li">
                <a href="javascript:void(0)" id="comm-like-<?= $result['comment_id']; ?>"><?= $result['has_liked'] > 0 ? 'Unlike' : 'Like'; ?> </a>
            </li>
            <li class="comm-actions-li">
                <a href="javascript:void(0)" id="comm-reply-<?= $result['comment_id']; ?>">Reply</a>
            </li>
            <li class="comm-actions-li">
                <time class="timeago" datetime="<?= $result['date'] . ' ' .  $result['time']; ?>"></time>
            </li>
            <li class="comm-actions-li">
                <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                <span class="comment-like-count" id="comment-like-count-<?= $result['comment_id']; ?>"><?= $result['likes'] ?></span>
            </li>
            <?php $user_cookie = $this->input->cookie('user', TRUE); 
              $user_cookie = explode(',', $user_cookie);
        ?>
        <?php if($result['comment_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
        <li class="comm-actions-li pull-right">
          <div class="dropdown">
          <button class="btn btn-default dropdown-toggle comm-edit-delete" type="button" id="menu1" data-toggle="dropdown">
          <span class="fa fa-angle-down"></span></button>
          <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
            <li role="presentation" id="comm-edit-<?= $result['id']; ?>"><a role="menuitem" href="javascript:void(0)">Edit</a></li>
            <li role="presentation" id="comm-delete-<?= $result['id']; ?>"><a role="menuitem" href="javascript:void(0)">Delete</a></li>
          </ul>
          </div>
        </li>
      <?php endif; ?>
        </ul>
          <?php if (!empty($result['comment_reply'])): ?>
            <?php foreach ($result['comment_reply'] as $reply): ?>
            <?php $reply_text = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $reply['reply']))))); 
                  $reply_text = preg_replace('/[\s]+/', ' ', $reply_text);
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-reply pull-left">
              <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
                  <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
              </div>
              <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= ucwords($reply['full_name']); ?> </strong>&nbsp;<span class="comment-content"><?= $reply_text ?></span>
                  <br>
                  <ul class="comm-actions"> 
                      <li class="reply-actions-li">
                          <a href="javascript:void(0)" id="reply-like-<?= $reply['reply_id']; ?>">Like </a>
                      </li><!-- 
                      <li class="comm-actions-li" id="reply-<?= $result['reply_id']; ?>">
                          <a href="javascript:void(0)">Reply</a>
                      </li> -->
                      <li class="reply-actions-li">
                          <time class="timeago" datetime="<?= $reply['reply_date'] . ' ' .  $reply['reply_time']; ?>"></time>
                      </li>
                      <li class="reply-actions-li">
                          <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                          <span class="comment-like-count" id="reply-like-count-<?= $reply['reply_id']; ?>"><?= $reply['reply_likes'] ?></span>
                      </li>
                      <?php if($reply['reply_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                      <li class="reply-actions-li">
                        <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle comm-edit-delete" type="button" id="menu1" data-toggle="dropdown">
                        <span class="fa fa-angle-down"></span></button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                          <li role="presentation" id="reply-edit-<?= $reply['reply_id']; ?>"><a role="menuitem" href="javascript:void(0)">Edit</a></li>
                          <li role="presentation" id="reply-delete-<?= $reply['reply_id']; ?>"><a role="menuitem" href="javascript:void(0)">Delete</a></li>
                        </ul>
                        </div>
                      </li>
                    <?php endif; ?>            
                  </ul>
              </div>
            </div>
            <div class="clearfix"></div>
            <?php endforeach ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-reply pull-left" id="reply-box-<?= $result['id']; ?>">
              <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
                <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
              </div>
              <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec comment-right">
                <textarea rows="1" placeholder="Write a reply..." class="textarea-reply" id="textarea-reply-<?= $result['id']; ?>"></textarea>
              </div>
            </div>
              <div class="clearfix"></div>
          <?php endif ?>
    </div>

            
    <input type="hidden" name="last_row" id="last_row" value="<?= $result['last_row']; ?>">
    <script>
        $("time.timeago").timeago();
    </script>
<?php echo ob_get_clean(); ?>