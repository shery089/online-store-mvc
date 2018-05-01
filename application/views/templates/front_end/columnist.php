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
  
        <div class="col-lg-12 col-sm-12 col-md-12 dark-border" id="entity">
        <?php if (empty($columns)): ?>
          <h3 class="text-center">No Columns</h3>
        <?php else: ?>
        <h1 class="text-center entity-name">Columns</h1>
          <div class="clearfix marbot10 animatedParent animateOnce" data-sequence='250'>
              <div class="row">
                  <?php foreach ($columns as $column): ?>
                  <div class="col-lg-12 col-md-12 col-sm-12 marbot10 fadeInRight" data-id='<?= entity_decode($columnist['id']); ?>'>
                      <div class="text-center">
                          <div class="grid image-effect2">
                          <?php 
                            $image = $columnist['profile_image']; 
                            $title = $column['title'];
                          ?>
                          </div>
                          <div class="col-lg-2 col-md-3 col-sm-3">
                              <a href="<?= site_url('front_end/column/get_column_by_id/' . $column['id']) ?>">
                                  <figure>
                                      <img src="<?= COLUMNIST_IMAGE . entity_decode($image); ?>" alt="<?= entity_decode($columnist['image'] ) ?>" class="img-responsive img-thumbnail">
                                  </figure>
                              </a>
                          </div>
                          <div class="col-lg-10 col-md-9 col-sm-9">
                              <a href="<?= site_url('front_end/column/get_column_by_id/' . $column['id']) ?>">
                                  <h5 class="fontresize marbot10 color-light entity-name-prnt"><span class="entity-name"><?= ucwords(entity_decode($title)); ?></span></h5>
                              </a>
                          </div>
                      </div>
                  </div>
              <?php endforeach; ?>
              </div>
          </div>
    <?php endif ?>
    </div>
    </div><!--/row-->    
  </div> <!--/col-lg-6-->
<!--/center-->