  <!--center-->
  <div id="search_results">
  </div> <!-- search_result -->
    <div class="col-sm-6 col-md-6 col-lg-6" id="entity">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 dark-border entity-main-info">
          <h3 class="margin-top-0 entity-name"><?= ucwords($columnist['name']); ?></h3>
          <div class="col-lg-5 header-image">
            <img class="img img-responsive" src="<?= COLUMNIST_IMAGE . $columnist['image'] ?>" alt="">
          </div>
          
          <div class="col-lg-7 header-section-2">
              
              <ul class="no-of-votes-list">
                <li><strong>Age: </strong><?= ageCalculator(entity_decode($columnist['dob'])) . ' Years'; ?></li>
                <br>
                <li><strong>City: </strong><?= ucwords(entity_decode($columnist['city'])); ?></li>
                <br>
                <li><strong>Newspaper(s): </strong><?= ucwords(implode(', ', array_column($newspaper, 'name'))); ?>
                  <br>
                  <?php $thumbnails =  array_column($newspaper, 'thumbnail'); ?>
                  <?php foreach ($thumbnails as $thumbnail): ?>
                    <img src="<?= NEWSPAPER_IMAGE . $thumbnail ?>" alt="">
                  <?php endforeach ?>
                </li>
                <br>
              </ul>
            </div>
              <strong id="thumbs-up" class="pull-left thumbs">Support <i data-toggle="tooltip" data-placement="auto" title="I Support It!" id="thumbs-up-icon" class="fa fa-thumbs-o-up"></i><span 
              id="thumbs-up-count" class="text-left thumbs-count"><?= ($columnist['likes'] == 0) ? '' : thousandsCurrencyFormat($columnist['likes']); ?></span></strong>
              <strong  id="thumbs-down" class="pull-right thumbs">Oppose <i data-toggle="tooltip" data-placement="auto" title="I Oppose It!" id="thumbs-down-icon" class="fa fa-thumbs-o-down"></i><span 
              id="thumbs-down-count" class="text-left thumbs-count"><?= ($columnist['dislikes'] == 0) ? '' : thousandsCurrencyFormat($columnist['dislikes']); ?></span></strong>
          </div>
        <br>
        
        <?php 
          /**
           * [Introduction triming]
           */
          $introduction = html_entity_decode($columnist['introduction']); 
          // $introduction = preg_replace('/\r\n|\r|\n+/', "\n", $introduction);
          $introduction = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $introduction)))));
          $introduction = preg_replace('/\[removed\]/', '', $introduction);
          // $introduction = implode('<br>', array_map('ucfirst', explode('<br>', preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', '<br>', $introduction))));
          // $introduction = preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', PHP_EOL, $introduction);
          // $introduction = html_entity_decode(stripslashes($introduction), ENT_QUOTES);
          $introduction = preg_replace('/(<br>)+/', '<br>', $introduction);
          $introduction = preg_replace('/[\t]+/', '    ', $introduction);
          $introduction = preg_replace('/[\s]+/', ' ', $introduction);

        ?>
      <div class="col-lg-12 col-md-12 dark-border pull-left" id="entity-details-1">
        <h3 class="margin-top-0">Introduction</h3>
        <ul class="details-section text-justify">
          <?php
            $introduction = explode('<br>', $introduction);

              $intro = array_slice($introduction,0, 4);
              for ($i = 0, $count = count($intro); $i < $count; $i++) 
              { 
                if(strlen($intro[$i]) > 100)
                {
                    $cut_intro = ucwords(html_entity_decode($intro[$i], ENT_QUOTES));
                    $intro[$i] = (strlen($cut_intro) > 214) ? substr($cut_intro,0,209) . ' .....' : $cut_intro;
                }
                echo '<li class="details-section-li">' . $intro[$i] . '</li>';
              }
            ?>
        </ul>
        <div class="text-center"><i id="rm-pol-intro-<?= $columnist['id']; ?>" class="fa fa-2x fa-angle-double-down read-more"></i></div>
      </div>  

        <div class="clearfix"></div>
        <input type="hidden" id="mainEntityId" name="main_entity_id" value="<?= $columnist['id'] ?>">
        <input type="hidden" id="mainEntityName" name="main_entity_name" value="columnist">
        <input type="hidden" id="ColumnId" name="column_id" value="<?= $column['id'] ?>">

      <?php

        $posted_time = $column['posted_time'];
        $column_details = $column['column'];
        $columnist_name = $column['columnist_name'];
        $posted_date = $column['posted_date'];
        $post_comments_count = $post_comments_count > 0 ? $post_comments_count : '';
        $post_likes = thousandsCurrencyFormat($column['likes']);
        
        $post_likes = $post_likes > 0 ? $post_likes : '';
        
        $post_dislikes = thousandsCurrencyFormat($column['dislikes']);
        
        $post_dislikes = $post_dislikes > 0 ? $post_dislikes : '';
      ?>
          
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dark-border pull-left">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 comm-top">
          <ul class="post text-justify">
              <img class="img-custom img-responsive img-square img-thumbnail pull-left" src="<?= COLUMNIST_IMAGE . $columnist['image'] ?>" alt="">
            <h5 id="post-user"><?= ucwords($columnist['name']); ?></h5>
            <em id="post_time"><?= $posted_date . ' ' . $posted_time ?></em>
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
          <div class="text-left"><?= entity_decode($column_details); ?></div>
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
                      <li class="comm-actions-li pull-left">
                          <a href="javascript:void(0)" id="comm-like-<?= $comment['id']; ?>">Like </a>
                      </li>
                      <li class="comm-actions-li pull-left">
                          <a href="javascript:void(0)" id="comm-reply-<?= $comment['id']; ?>">Reply</a>
                      </li>
                      <li class="comm-actions-li pull-left">
                          <time class="timeago" datetime="<?= ($comment['comment_edit_date'] == '0000-00-00' && $comment['comment_edit_time'] == '00:00:00') ? $comment['comment_date'] . ' ' .  $comment['comment_time'] : $comment['comment_edit_date'] . ' ' . $comment['comment_edit_time']; ?>"></time>
                      </li>
                      <li class="comm-actions-li pull-left">
                          <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                          <span class="comment-like-count" id="comment-like-count-<?= $comment['id']; ?>"><?= $comment['comment_likes'] ?></span>
                      </li>
                      <?php if ($comment['is_edited']): ?>
                        <li class="comm-actions-li pull-left">
                          <span>Edited</span>
                        </li>
                      <?php endif ?>
                      <?php $user_cookie = $this->input->cookie('user', TRUE); 
                            $user_cookie = explode(',', $user_cookie);
                      ?>
                      <?php if($comment['comment_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                      <li class="comm-actions-li pull-left">
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
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-reply pull-left">
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
                            <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
                        </div>
                        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= ucwords($reply['full_name']); ?> </strong>&nbsp;<span><?= $reply_text ?></span>
                            <br>
                            <ul class="comm-actions"> 
                                <li class="comm-actions-li pull-left">
                                    <a href="javascript:void(0)" id="reply-like-<?= $reply['reply_id']; ?>">Like </a>
                                </li><!-- 
                                <li class="comm-actions-li" id="reply-<?= $result['reply_id']; ?>">
                                    <a href="javascript:void(0)">Reply</a>
                                </li> -->
                                <li class="comm-actions-li pull-left">
                                    <time class="timeago" datetime="<?= $reply['reply_date'] . ' ' .  $reply['reply_time']; ?>"></time>
                                </li>
                                <li class="comm-actions-li pull-left">
                                    <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                                    <span class="comment-like-count" id="reply-like-count-<?= $reply['reply_id']; ?>"><?= $reply['reply_likes'] ?></span>
                                </li>
                           <!--      <?php if($reply['reply_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                            <li class="comm-actions-li pull-left">
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
                                                        --> </ul>
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
    </div><!--/row-->    
  </div> <!--/col-lg-6-->
<!--/center-->