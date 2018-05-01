  <!--center-->
  <div class="col-md-6 col-lg-6 dark-border">
    <!-- <div class="row"> -->
      <div class="col-lg-12 light-border">
        <?php if (!empty($politicians)): ?>
          <p class="marg-0">Showing results for <strong> <?= $this->input->post('search_term'); ?> </strong></p>
      </div>

      <div class="col-lg-12 light-border" id="search_results">
      <?php 
          $count = count($politicians); 
          $last_id = array_column($politicians, 'id');
          $last_id = $last_id[$count-1];
      ?>
      <?php foreach ($politicians as $politician): ?>
        <?php
          $political_party = $politician['political_party'];
          $political_party_len = strlen($political_party);
          // echo strrpos($political_party, '-');
          
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
            $political_party = $political_party[0] . '(' . strtoupper($political_party[1]) . ')';
          }
 
          if (strpos($political_party, '(') !== false) 
          {
              $political_party = explode('(', rtrim($political_party, ')'));
              $political_party = $political_party[0] . '(' . strtoupper($political_party[1]) . ')';
          }
          
          if (strpos($political_party, '-') !== false) 
          {
            if(substr_count($political_party, '-') > 1)
            {
              $pos1 = strpos($political_party, '-');
              $pos2 = strrpos($political_party, '-');
              $length = $pos2 - $pos1 + 1;
              $political_party = substr_replace($political_party, ' ', $pos1, $length);
            }
            else
            {
              // $political_party = explode('(', rtrim($political_party, ')'));
              // $political_party = $political_party[0] . '(' . strtoupper($political_party[1]) . ')';
            }
          }
        ?>

        <div class="col-lg-2 header-image">
          <a href="<?= site_url('front_end/politician/get_politician_by_id/' . $politician['id'] ) ?>">
            <img class="img img-responsive img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . $politician['profile_image'] ?>" alt="">
          </a>
        </div>
        <div class="col-lg-10">
          <strong> <a href="<?= site_url('front_end/politician/get_politician_by_id/' . $politician['id'] ) ?>"><p class="entity-name"><?= ucwords(entity_decode($politician['name'])); ?></p></a></strong>
          <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $politician['party_id'] ) ?>"><span class="entity-second-name"><?= ucwords(entity_decode($political_party)); ?></span></a>
        </div>
       <div class="clearfix"></div>
      <?php if ($last_id !== $politician['id']): ?>
        <hr>
      <?php endif ?>
      <?php endforeach ?>
      </div>
      
      <?php else: ?>
       <img id="no_result_img" class="pull-left" src="<?= ADMIN_IMAGES_PATH . 'no_result.jpg' ?>" alt="">
       <p class="marg-0">We couldn't find anything for <strong> <?= $this->input->post('search_term'); ?></strong></p>
       <span>Looking for political parties? Try entering politician fullname or some other name</span>
       <!-- <br> -->
       <div class="clearfix"></div>
      <?php endif ?>
<!-- </div>  <--></-->
<!--/col-lg-6-->
  </div><!--/row-->    
<!--/center-->