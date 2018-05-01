  <!--center-->
  <div id="search_results">
  </div> <!-- search_result -->
  <div class="col-md-6 col-lg-6 col-sm-6" id="entity">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 dark-border entity-main-info">
        <h3 class="margin-top-0 text-center entity-name">Story</h3>
      </div>
      <?php if (!empty($featured_post)): ?>
        <input type="hidden" id="featuredPostStoryId" name="featured_post_story_id" value="<?= $featured_post[0]['id'] ?>">
      <?php endif ?>

      <!-- Post Section -->


      <?php 
        if (!empty($featured_post))
        {
          $posted_by = $featured_post[0]['user_details']['full_name'];

          $posted_time = $featured_post[0]['posted_time'];
          
          $post_likes = thousandsCurrencyFormat($featured_post[0]['likes']);
          
          $post_likes = $post_likes > 0 ? $post_likes : '';
          
          $post_dislikes = thousandsCurrencyFormat($featured_post[0]['dislikes']);
          
          $post_dislikes = $post_dislikes > 0 ? $post_dislikes : '';

        }
          $featured_post_id = $featured_post[0]['id'];
      ?>

      <?php if (!empty($featured_post)): ?>
          
      <input type="hidden" id="postId" name="post_id" value="<?= $featured_post_id ?>">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dark-border pull-left">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 comm-top">
          <ul class="post text-justify">
              <img class="img-custom img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
              <div id="rating" class="pull-left"><?= $rating ?></div>
              
              <br>
              <!-- <i class="fa fa-star-o" aria-hidden="true"></i> -->
            <h5 id="post-user"><?= ucwords($posted_by); ?></h5>
            <em id="post_time"><?= $posted_time . ' ' . $featured_post[0]['posted_date'] ?></em>
          </ul>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 comm-top post-top-actions-div pull-left">
          <ul class="post-top-actions">
            <li class="post-top-actions-li pull-left"><a id ="post-like" class="post-top-actions-li-item" href="javascript:void(0);">Like</a>: <span id="post-like-count"><?= $post_likes ?></span></li>
            <li class="post-top-actions-li pull-left"><a id ="post-dislike" class="post-top-actions-li-item" href="javascript:void(0);">Dislike</a>: <span id="post-dislike-count"><?= $post_dislikes ?></span></li>
            <li class="post-top-actions-li pull-left"><a id ="post-comment" class="post-top-actions-li-item" href="javascript:void(0);">Comments</a>: <span id="post-comment-count"><?= $post_comments_count > 0 ? $post_comments_count : ''; ?></span></li>
          </ul>
        </div>
        <div class="clearfix"></div>
        
        <div class="col-lg-12 actual-post">
          <div class="text-justify"><?= entity_decode($featured_post[0]['post']); ?></div>
        </div>
        <div class="clearfix"></div>
        <?php if (!empty($comments)): ?>
          <?php foreach ($comments as $comment): ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-comm post-comm-sec-1 comment pull-left" id="<?= 'comment_id_' . $comment['id']; ?>">
              <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
                  <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
              </div>
              <?php $comment_text = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $comment['comment']))))); 
                    $comment_text = preg_replace('/[\s]+/', ' ', $comment_text);
              ?>
              <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= ucwords($comment['full_name']); ?> </strong>&nbsp;<span class="comment-content"><?= $comment_text ?></span>
                  <br>
                  <ul class="comm-actions"> 
                      <li class="comm-actions-li">
                          <a href="javascript:void(0)" id="comm-like-<?= $comment['id']; ?>">Like </a>
                      </li>
                      <li class="comm-actions-li">
                          <a href="javascript:void(0)" id="comm-reply-<?= $comment['id']; ?>">Reply</a>
                      </li>
                      <li class="comm-actions-li">
                          <time class="timeago" datetime="<?= ($comment['comment_edit_date'] == '0000-00-00') ? $comment['comment_date'] . ' ' .  $comment['comment_time'] : $comment['comment_edit_date'] . ' ' . $comment['comment_edit_time']; ?>"></time>
                      </li>
                      <?php if ($comment['comment_edit_date'] != '0000-00-00'): ?>
                        <li class="comm-actions-li">
                          <span>Edited</span>
                        </li>
                      <?php endif ?>
                      <li class="comm-actions-li">
                          <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                          <span class="comment-like-count" id="comment-like-count-<?= $comment['id']; ?>"><?= $comment['comment_likes'] ?></span>
                      </li>
                      <?php $user_cookie = $this->input->cookie('user', TRUE); 
                            $user_cookie = explode(',', $user_cookie);
                      ?>
                      <?php if($comment['comment_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                      <li class="comm-actions-li pull-right">
                        <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle comm-edit-delete" type="button" id="menu1" data-toggle="dropdown">
                        <span class="fa fa-angle-down"></span></button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                          <li role="presentation" id="comm-edit-<?= $comment['id']; ?>"><a role="menuitem" href="javascript:void(0)">Edit</a></li>
                          <li role="presentation" id="comm-delete-<?= $comment['id']; ?>"><a role="menuitem" href="javascript:void(0)">Delete</a></li>
                        </ul>
                        </div>
                      </li>
                    <?php endif; ?>
                  </ul>
    
                  <?php if (!empty($comment['comment_reply'])): ?>
                    <?php foreach ($comment['comment_reply'] as $reply): ?>
                    <?php $reply_text = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $reply['reply']))))); 
                          $reply_text = preg_replace('/[\s]+/', ' ', $reply_text);
                    ?>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-reply pull-left" id="reply-<?= $reply['reply_id']; ?>">
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
                                    <time class="timeago" datetime="<?= ($reply['reply_edit_date'] == '0000-00-00') ? $reply['reply_date'] . ' ' .  $reply['reply_time'] : $reply['reply_edit_date'] . ' ' . $reply['reply_edit_time']; ?>"></time>
                                </li>
                                <?php if ($reply['reply_edit_date'] != '0000-00-00'): ?>
                                  <li class="reply-actions-li">
                                    <span>Edited</span>
                                  </li>
                                <?php endif ?>
                                <li class="reply-actions-li">
                                    <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                                    <span class="comment-like-count" id="reply-like-count-<?= $reply['reply_id']; ?>"><?= $reply['reply_likes'] ?></span>
                                </li>
                                <?php if($reply['reply_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                                <li class="reply-actions-li pull-right">
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-reply pull-left" id="reply-box-<?= $comment['id']; ?>">
                      <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
                        <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
                      </div>
                      <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec comment-right">
                        <textarea rows="1" placeholder="Write a reply..." class="textarea-reply" id="textarea-reply-<?= $comment['id']; ?>"></textarea>
                      </div>
                    </div>
                      <div class="clearfix"></div>
                  <?php endif ?>
                                </div>
          </div>
          <div class="clearfix"></div>
            <?php endforeach ?>
        <?php endif ?>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-comm pull-left" id="comment-box">
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
          <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
        </div>
        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec comment-right">
          <textarea rows="1" placeholder="Write a comment..." class="textarea-post" id="textarea-post"></textarea>
        </div>
      </div>
      <div class="clearfix"></div>
<!-- here -->
    </div> <!-- Post Section -->
          <?php endif ?>

  </div><!--/row-->    
</div> <!--/col-lg-6-->
<!--/center-->