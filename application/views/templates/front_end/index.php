  <!--center-->
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="row">
      <div class="col-lg-12" style="padding:0">
        <img class="img img-responsive" src="<?= FRONT_END_IMAGES_PATH ?>/banner3.jpg" alt="">
        <br>
      </div>
    </div>
    
      <div class="row">
        <div class="col-lg-4 pol-panel" id="politician">
          <div class="panel panel-primary">
            <div class="panel-heading text-center"><h6 class="grey"><i class="fa fa-users" aria-hidden="true"></i> Politicians</h6></div>
            <div class="panel-body panel-pad" style="min-height: 208px !important;">
              <?php foreach ($politicians as $politician): ?>
              <div class="col-lg-12 pad-top-bot">
                <?php 
                  $name = $politician['name'];
                  $political_party_name = $politician['political_party'];
                  if (strpos($political_party_name, '(') !== false) 
                  {
                      $political_party_name = explode('(', rtrim($political_party_name, ')'));
                      $political_party_name = $political_party_name[0] . '(' . strtoupper($political_party_name[1]) . ')';
                  }
                  
                  if (strpos($political_party_name, '-') !== false) 
                  {
                    if(substr_count($political_party_name, '-') > 1)
                    {
                      $pos1 = strpos($political_party_name, '-');
                      $pos2 = strrpos($political_party_name, '-');
                      $length = $pos2 - $pos1 + 1;
                      $political_party_name = substr_replace($political_party_name, ' ', $pos1, $length);
                    }
                    else
                    {
                      // $political_party_name = explode('(', rtrim($political_party_name, ')'));
                      // $political_party_name = $political_party_name[0] . '(' . strtoupper($political_party_name[1]) . ')';
                    }
                  }
                  $keywords = preg_split("/[\s]+/", $political_party_name);
                  $acronym = '';
                  if(strchr($political_party_name, '('))
                  {
                      $count = count($keywords) - 1;
                  }
                  else
                  {
                      $count = count($keywords);
                  }

                  for ($i = 0; $i < $count; $i++) 
                  { 
                      $acronym .= $keywords[$i][0];
                  }

                  if(strchr($political_party_name, '('))
                  {
                      $acronym .= end($keywords);
                  }
                ?>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-left">
                  <img class="img" src="<?= POLITICIAN_IMAGE . $politician['thumbnail']; ?>" alt="<?= $name ?>">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pull-left">
                  <?php 
                    $name = (strlen($name) > 17) ? substr($name,0,9) . ' ...' : $name;  
                    $acronym = $acronym == 'i' ? "independant" : $acronym
                  ?>
                  <h6><a class="entity-name" href="<?= base_url('front_end/politician/get_politician_by_id') . '/' . $politician['id']; ?>"><?= ucwords($name) ?></a></h6>

                  <h6 style="padding-top: 5px;"><a class="entity-second-name" href="<?= base_url('front_end/political_party/get_political_party_by_id') . '/' . $politician['political_party_id']; ?>"><?= strtoupper($acronym) ?></a></h6>
                </div>
              </div>
              <div class="clearfix"></div>
              <?php endforeach ?>
              <br>  
            </div>
          </div>
        </div>
        <div class="col-lg-4 party-panel"  id="political_party">
          <div class="panel panel-primary">
            <div class="panel-heading text-center"><h6 class="grey"><i class="fa fa-users" aria-hidden="true"></i> Political Parties</h6></div>
            <div class="panel-body panel-pad"  style="min-height: 208px !important;">
              <?php foreach ($political_parties as $political_party): ?>
              <div class="col-lg-12 pad-top-bot">
                <?php 
                  $name = $political_party['name'];
                  if (strpos($name, '(') !== false) 
                  {
                      $name = explode('(', rtrim($name, ')'));
                      $name = $name[0] . '(' . strtoupper($name[1]) . ')';
                  }
                  
                  if (strpos($name, '-') !== false) 
                  {
                    if(substr_count($name, '-') > 1)
                    {
                      $pos1 = strpos($name, '-');
                      $pos2 = strrpos($name, '-');
                      $length = $pos2 - $pos1 + 1;
                      $name = substr_replace($name, ' ', $pos1, $length);
                    }
                    else
                    {
                      // $name = explode('(', rtrim($name, ')'));
                      // $name = $name[0] . '(' . strtoupper($name[1]) . ')';
                    }
                  }

                    $name = (strlen($name) > 17) ? substr($name,0,9) . ' ...' : $name;  

                ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-left">
                  <img class="img" src="<?= PARTY_IMAGE . $political_party['thumbnail']; ?>" alt="<?= $name ?>">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pull-left">
                  <h6 style="padding-bottom: 10px;"><a class="entity-name" href="<?= base_url('front_end/political_party/get_political_party_by_id') . '/' . $political_party['id']; ?>"><?= ucwords($name) ?></a></h6>
                  <span class="entity-second-name" style="padding-top: 5px;" class="f-date">Founded: <?= $political_party['founded_date']; ?></span>
                </div>
              </div>
              <div class="clearfix"></div>
              <?php endforeach ?>
              <br>
            </div>
          </div>
        </div>
        <div  class="col-lg-4 col-panel" id="columnist">
          <div class="panel panel-primary">
            <div class="panel-heading text-center"><h6 class="grey"><i class="fa fa-files-o fa-fw" aria-hidden="true"></i> Columnists</h6></div>
            <div class="panel-body panel-pad" style="min-height: 208px !important;">
              <div class="col-lg-12 pad-top-bot" style="padding-top: 0">
              <?php foreach ($columnists as $columnist): ?>
                <div class="col-lg-12 pad-top-bot">
                  <?php $name = $columnist['name']; ?>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-left">
                    <img class="img" src="<?= COLUMNIST_IMAGE . $columnist['thumbnail']; ?>" alt="<?= $name ?>">
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pull-left">
                    <?php $name = (strlen($name) > 17) ? substr($name,0,13) . ' ...' : $name;  ?>
                    <h6><a class="entity-name" style="padding-bottom: 10px;"  href="<?= base_url('front_end/columnist/get_columnist_by_id') . '/' . $columnist['id']; ?>"><?= ucwords($name) ?></a></h6>
                    <h6 class="entity-second-name"style="padding-top:5px" ><?= ucwords($columnist['newspaper']) ?></h6>
                  </div>
                </div>
                <div class="clearfix"></div>
                <?php endforeach ?>
                <br>
            </div>
          </div>
        </div>
      </div>
    <hr>      
    <div class="r">
      <?php 
        if(!empty($featured_post))
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
        <input type="hidden" id="featuredPostStoryId" value="<?= $featured_post_id ?>">
        <input type="hidden" id="postId" name="post_id" value="<?= $featured_post_id ?>">
        <div class="col-lg-12 dark-border">
          <div class="col-lg-6 col-md-6 comm-top">
            <ul class="post text-justify">
                <img class="img-custom img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
                <div id="rating" class="pull-left"><?= $rating ?></div>
                
                <br>
                <!-- <i class="fa fa-star-o" aria-hidden="true"></i> -->
              <h5 id="post-user"><?= ucwords($posted_by); ?></h5>
              <em><?= $posted_time . ' ' . $featured_post[0]['posted_date'] ?></em>
            </ul>
          </div>

          <div class="col-lg-6 col-md-6 comm-top post-top-actions-div pull-left">
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
  <!-- here -->
      <a class="btn btn-default pull-right" href="<?= base_url('front_end/home/get_post_by_id') . '/' . $featured_post_id ?>">Comment</a>
        <div class="clearfix"></div>
      </div> <!-- Post Section -->
        <?php endif ?>
    </div>
    <hr>
  </div>
</div><!--/center-->