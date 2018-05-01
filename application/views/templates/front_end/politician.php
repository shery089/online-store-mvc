  <!--center-->
  <div id="search_results">
  </div> <!-- search_result -->
  <div class="col-md-6 col-lg-6 col-sm-6" id="entity">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 dark-border entity-main-info">
        <h3 class="margin-top-0 entity-name"><?= ucwords(entity_decode($politician['name'])); ?></h3>
        <?php 
            $political_party = $politician['political_party_id']['name'];
            $political_party_len = strlen($political_party);
            
            if (strpos($political_party, '-') !== false) 
            {
              if(substr_count($political_party, '-') > 1)
              {
                $political_party = mb_convert_case(mb_strtolower($political_party), MB_CASE_TITLE, "UTF-8");

                $pos1 = strpos($political_party, '-') + 1;
                $pos2 = strpos($political_party, '-', $pos1 + strlen('-'));
                $length = abs($pos1 - $pos2);

                $between = strtolower(substr($political_party, $pos1, $length));
                $political_party = substr_replace($political_party, $between, $pos1, $length);
              }
              else
              {
                $political_party = mb_convert_case(mb_strtolower($political_party), MB_CASE_TITLE, "UTF-8");
              }
            }
            if (strpos($political_party, '(') !== false) 
            {
              $political_party = explode('(', rtrim($political_party, ')'));
              $political_party = $political_party[0] . '(' . ucwords($political_party[1]) . ')';
            } 
        ?>
        <h3 class="margin-top-0 entity-second-name"><?= ucwords($political_party); ?></h3>
        <div class="col-lg-5 header-image">
          <img id="entity-img" class="img img-responsive" src="<?= POLITICIAN_IMAGE . $politician['image'] ?>" alt="">
        </div>
        
        <div class="col-lg-7 header-section-2">
            <a id="vote-now" class="btn btn-primary pull-left text-center header-section-2-btn" href="javascript:void(0)">Vote Now!</a>

            <!-- <a class="btn btn-primary pull-right text-center header-section-2-btn" href="javascript:void(0)">Family Tree</a> -->
          <br>
          <br>
          <div id="no-of-votes">
            <h5 class="text-left no-of-votes-title"><b>Number of Votes</b></h5>
            <?php 
                // politician actual halqass
                $halqa_details = array_column($politician_halqas, 'halqa_details');
                $halqa_details = array_column($halqa_details, 'name');
                if($casted_votes)
                {
                  $halqa_array_keys = array_keys($casted_votes['halqa_keys']);
                  $halqa_array_values = array_values($casted_votes['halqa_keys']);

                  $halqas_not_voted_yet = array_diff($halqa_details, $halqa_array_keys);
                  $halqas_not_voted_yet_keys = array_combine($halqas_not_voted_yet, $halqas_not_voted_yet);
                  foreach ($halqas_not_voted_yet_keys as $key => $value) 
                  {
                    $halqas_not_voted_yet_keys[$key] = 0;
                  }

                  $halqa_details_plus = array_combine($halqa_array_keys, $halqa_array_values);
                  $halqa_details_plus = array_merge($halqa_details_plus, $halqas_not_voted_yet_keys);
                  $halqa_details_plus = array_change_key_case($halqa_details_plus, CASE_UPPER);
                                  
                  $halqa_details_plus = http_build_query($halqa_details_plus, '', ', ');
                  $halqa_details_plus = str_replace('=', ': ', $halqa_details_plus);

                  $politician_prov_halqas_plus = preg_replace("/(na)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);
                  $politician_na_halqas_plus = preg_replace("/(pp|ps|pk|pb|la|gbla)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);

                  // to remove extra white space and extra commas
                  $politician_na_halqas_plus = preg_replace("/(, ,)+/", ",", $politician_na_halqas_plus);
                  $politician_na_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $politician_na_halqas_plus);
                  $politician_na_halqas_plus = trim($politician_na_halqas_plus, ', ');

                  $politician_prov_halqas_plus = preg_replace("/(, ,)+/", ",", $politician_prov_halqas_plus);
                  $politician_prov_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $politician_prov_halqas_plus);
                  $politician_prov_halqas_plus = trim($politician_prov_halqas_plus, ', ');
                  
                  $politician_halqas = array_column($politician_halqas, 'halqa_type');

                  foreach ($politician_halqas as $politician_halqa)
                  {
                    $na_halqa_plus = in_array($politician_halqa, array('national assembly')) ? TRUE : FALSE;
                    
                    $prov_halqa_plus = !in_array($politician_halqa, array('national assembly')) ? TRUE : FALSE;
                  }
                  
                  $votes_array_keys = array_keys($casted_votes['count_vote_types']);

                  foreach ($votes_array_keys as $vote_type)
                  {
                    if(!in_array($vote_type, array('national assembly', 'off_halqa')))
                    {
                      $prov_assembly = $vote_type;
                      $prov_assembly_votes = array_column($casted_votes, $prov_assembly);
                    }
                  }
                  
                  if(in_array('national assembly', $votes_array_keys))
                  {
                    $national_assembly_votes = array_column($casted_votes, 'national assembly');
                  }

                  if(in_array('off_halqa', $votes_array_keys))
                  {
                    $off_halqa_votes = array_column($casted_votes, 'off_halqa');
                  }
                }
                else
                {
                  $halqa_details_plus = http_build_query($halqa_details, '', ': 0, ');
                  $halqa_details_plus = preg_replace("/[0-9]+=/i", "", $halqa_details_plus);
                  $halqa_details_plus .= ': 0';

                  $politician_prov_halqas_plus = preg_replace("/(na)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);
                  $politician_na_halqas_plus = preg_replace("/(pp|ps|pk|pb|la|gbla)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);

                  // to remove extra white space and extra commas
                  $politician_na_halqas_plus = preg_replace("/(, ,)+/", ",", $politician_na_halqas_plus);
                  $politician_na_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $politician_na_halqas_plus);
                  $politician_na_halqas_plus = strtoupper(trim($politician_na_halqas_plus, ', '));

                  $politician_prov_halqas_plus = preg_replace("/(, ,)+/", ",", $politician_prov_halqas_plus);
                  $politician_prov_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $politician_prov_halqas_plus);
                  $politician_prov_halqas_plus = strtoupper(trim($politician_prov_halqas_plus, ', '));
                  
                  $politician_halqas = array_column($politician_halqas, 'halqa_type');
                  foreach ($politician_halqas as $politician_halqa)
                  {
                    if(in_array($politician_halqa, array('national assembly')))
                    {
                      $na_halqa_plus = TRUE;
                    }
                    else
                    {
                      $na_halqa_plus = FALSE;
                    }
                    if(!in_array($politician_halqa, array('national assembly')))
                    {
                      $prov_halqa_plus = TRUE;
                    }
                    else
                    {
                      $prov_halqa_plus = FALSE;
                    }
                  }
                }               
              ?>

            <input type="hidden" id="nationalAssemblyVotes" name="national_assembly_votes" value="<?= $politician_na_halqas_plus; ?>">
            <input type="hidden" id="provAssemblyVotes" name="prov_assembly_votes" value="<?= $politician_prov_halqas_plus; ?>">
            
            <ul class="no-of-votes-list">
              <li>National Assembly: <span id="national_assembly_votes_count"><?= ($na_halqa_plus == 1) ? (!empty($national_assembly_votes) ? $national_assembly_votes[0] : 0) . ' (Votes)' : 'Not a NA Candidate' ?></span> &nbsp;<?php if($na_halqa_plus == 1): ?><i id="na-plus" class="fa fa-plus votes-plus-btn"></i><?php endif; ?>&nbsp;<div class="halqa-plus-details" id="na-plus-details"></div></li>
              <li>Provincial Assembly: <span id="prov_assembly_votes_count"><?= ($prov_halqa_plus == 1) ? (!empty($prov_assembly_votes) ? $prov_assembly_votes[0] : 0) . ' (Votes)' : 'Not a Provincial Candidate' ?></span> &nbsp;<?php if($prov_halqa_plus == 1): ?><i id="prov-plus" class="fa fa-plus votes-plus-btn"></i><?php endif; ?>&nbsp;<div class="halqa-plus-details" id="prov-plus-details"></div></li>
              <li>Off Halqa: <span id="off_halqa_votes_count"><?= !empty($off_halqa_votes) ? $off_halqa_votes[0] : 0; ?> (Votes)</span></li>
            </ul>
          </div>
            <strong id="thumbs-up" class="pull-left thumbs">Support <i id="thumbs-up-icon" class="fa fa-thumbs-o-up"></i><span 
            id="thumbs-up-count" class="text-left thumbs-count"><?= ($politician['likes'] == 0) ? '' : thousandsCurrencyFormat($politician['likes']); ?></span></strong>
            <strong  id="thumbs-down" class="pull-right thumbs">Oppose <i id="thumbs-down-icon" class="fa fa-thumbs-o-down"></i><span 
            id="thumbs-down-count" class="text-left thumbs-count"><?= ($politician['dislikes'] == 0) ? '' : thousandsCurrencyFormat($politician['dislikes']); ?></span></strong>
        </div>
      <br>
      </div>
      <?php 
        /**
         * [Introduction triming]
         */
        $introduction = html_entity_decode($politician['introduction']); 
        // $introduction = preg_replace('/\r\n|\r|\n+/', "\n", $introduction);
        $introduction = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $introduction)))));
        $introduction = preg_replace('/\[removed\]/', '', $introduction);
        // $introduction = implode('<br>', array_map('ucfirst', explode('<br>', preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', '<br>', $introduction))));
        // $introduction = preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', PHP_EOL, $introduction);
        // $introduction = html_entity_decode(stripslashes($introduction), ENT_QUOTES);
        $introduction = preg_replace('/(<br>)+/', '<br>', $introduction);
        $introduction = preg_replace('/[\t]+/', '    ', $introduction);
        $introduction = preg_replace('/[\s]+/', ' ', $introduction);
        /**
         * [Election History triming]
         */
        $election_history = html_entity_decode($politician['election_history']); 
        // $election_history = preg_replace('/\r\n|\r|\n+/', "\n", $election_history);
        $election_history = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $election_history)))));
        $election_history = preg_replace('/\[removed\]/', '', $election_history);
        // $election_history = implode('<br>', array_map('ucfirst', explode('<br>', preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', '<br>', $election_history))));
        // $election_history = preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', PHP_EOL, $election_history);
        // $election_history = html_entity_decode(stripslashes($election_history), ENT_QUOTES);
        $election_history = preg_replace('/(<br>)+/', '<br>', $election_history);
        $election_history = preg_replace('/[\t]+/', '    ', $election_history);
        $election_history = preg_replace('/[\s]+/', ' ', $election_history);
      ?>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 entity-details dark-border pull-left" id="entity-details-1">
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
        <div class="text-center"><i id="rm-pol-intro-<?= $politician['id']; ?>" class="fa fa-2x fa-angle-double-down read-more"></i></div>
      </div>
      
      <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 entity-details dark-border pull-left" id="entity-details-2">
        <h3 class="margin-top-0">Election History</h3>
        <ul class="details-section text-justify">
          <?php
            $election_history = explode('<br>', $election_history);

              $election_history = array_slice($election_history,0, 4);
              for ($i = 0, $count = count($election_history); $i < $count; $i++) 
              { 
                if(strlen($intro[$i]) > 100)
                {
                    $cut_election_history = ucwords(html_entity_decode($election_history[$i], ENT_QUOTES));
                    $election_history[$i] = (strlen($cut_election_history) > 214) ? substr($cut_election_history,0,209) . ' .....' : $cut_election_history;
                }
                echo '<li class="details-section-li">' . $election_history[$i] . '</li>';
              }
            ?>
        </ul>
        <div class="text-center"><i id="rm-pol-election_history-<?= $politician['id']; ?>" class="fa fa-2x fa-angle-double-down read-more"></i></div>
      </div>      

      <div class="clearfix"></div>
      <input type="hidden" id="mainEntityId" name="main_entity_id" value="<?= $politician['id'] ?>">
      <input type="hidden" id="mainEntityName" name="main_entity_name" value="politician">
      <?php if (!empty($featured_post)): ?>
        <input type="hidden" id="featuredPostStoryId" name="featured_post_story_id" value="<?= $featured_post[0]['id'] ?>">
      <?php endif ?>

      <!-- Post Section -->


      <?php 

        if (!empty($featured_post))
        {
          $posted_by = array_column($featured_post[0]['user_details'], 'full_name');
          
          $posted_time = $featured_post[0]['posted_time'];
          
          $post_likes = thousandsCurrencyFormat($featured_post[0]['likes']);
          
          $post_likes = $post_likes > 0 ? $post_likes : '';
          
          $post_dislikes = thousandsCurrencyFormat($featured_post[0]['dislikes']);
          
          $post_dislikes = $post_dislikes > 0 ? $post_dislikes : '';

          /**
           * [Post triming]
           */
          $post = entity_decode($featured_post[0]['post']); 
          // $post = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $post)))));
          // $post = preg_replace('/\[removed\]/', '', $post);
          // $post = preg_replace('/(<br>)+/', '<br>', $post);
          // $post = preg_replace('/[\t]+/', '    ', $post);
          // $post = preg_replace('/[\s]+/', ' ', $post);
        }
      ?>

      <?php if (!empty($featured_post)): ?>
          
      <input type="hidden" id="postId" name="post_id" value="<?= $featured_post[0]['id'] ?>">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dark-border pull-left">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 comm-top">
          <ul class="post text-justify">
              <img class="img-custom img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
              <div id="rating" class="pull-left"><?= $rating ?></div>
              
              <br>
              <!-- <i class="fa fa-star-o" aria-hidden="true"></i> -->
            <h5 id="post-user"><?= ucwords($posted_by[0]); ?></h5>
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
          <div class="text-justify"><?= $post; ?></div>
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
                          <time class="timeago" datetime="<?= ($comment['comment_edit_date'] == '0000-00-00' && $comment['comment_edit_time'] == '00:00:00') ? $comment['comment_date'] . ' ' .  $comment['comment_time'] : $comment['comment_edit_date'] . ' ' . $comment['comment_edit_time']; ?>"></time>
                      </li>
                      <li class="comm-actions-li">
                          <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                          <span class="comment-like-count" id="comment-like-count-<?= $comment['id']; ?>"><?= $comment['comment_likes'] ?></span>
                      </li>
                      <?php if ($comment['is_edited']): ?>
                        <li class="comm-actions-li">
                          <span>Edited</span>
                        </li>
                      <?php endif ?>
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
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 post-reply pull-left">
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 post-comm-sec comm-details">
                            <img class="img-comment img-responsive img-square img-thumbnail" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
                        </div>
                        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 post-comm-sec text-justify comment-right"><strong class="comm-name">  <?= ucwords($reply['full_name']); ?> </strong>&nbsp;<span class="comment-content"><?= $reply_text ?></span>
                            <br>
                            <ul class="comm-actions"> 
                                <li class="comm-actions-li">
                                    <a href="javascript:void(0)" id="reply-like-<?= $reply['reply_id']; ?>">Like </a>
                                </li><!-- 
                                <li class="comm-actions-li" id="reply-<?= $result['reply_id']; ?>">
                                    <a href="javascript:void(0)">Reply</a>
                                </li> -->
                                <li class="comm-actions-li">
                                    <time class="timeago" datetime="<?= $reply['reply_date'] . ' ' .  $reply['reply_time']; ?>"></time>
                                </li>
                                <li class="comm-actions-li">
                                    <i class="fa fa-thumbs-o-up comment_reply_thumbs-o-up"></i>
                                    <span class="comment-like-count" id="reply-like-count-<?= $reply['reply_id']; ?>"><?= $reply['reply_likes'] ?></span>
                                </li>
<!--                                 <?php if($reply['reply_by'] == $user_cookie[0] && $this->input->cookie('isLoggedIn', TRUE) == TRUE): ?>
                                <li class="comm-actions-li">
                                  <div class="dropdown">
                                  <button class="btn btn-default dropdown-toggle comm-edit-delete" type="button" id="menu1" data-toggle="dropdown">
                                  <span class="fa fa-angle-down"></span></button>
                                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                    <li role="presentation" id="reply-edit-<?= $reply['reply_id']; ?>"><a role="menuitem" href="javascript:void(0)">Edit</a></li>
                                    <li role="presentation" id="reply-delete-<?= $reply['reply_id']; ?>"><a role="menuitem" href="javascript:void(0)">Delete</a></li>
                                  </ul>
                                  </div>
                                </li>
                              <?php endif; ?>   -->                           
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