  <!--center-->
  <div class="col-md-6 col-lg-6 dark-border">
    <!-- <div class="row"> -->
      <div class="col-lg-12 light-border">
        <?php if (!empty($political_parties)): ?>
          <p class="marg-0">Showing results for <strong> <?= $this->input->post('search_term'); ?> </strong></p>
      </div>

      <div class="col-lg-12 light-border">
      <?php 
          $count = count($political_parties); 
          $last_id = array_column($political_parties, 'id');
          $last_id = $last_id[$count-1];
      ?>
      <?php foreach ($political_parties as $political_party): ?>
        <?php
          $name = $political_party['name'];
          $name_len = strlen($name);
          // echo strrpos($name, '-');
          
          if (strpos($name, '-') !== false) 
          {
            if(substr_count($name, '-') > 1)
            {
              $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");

              $pos1 = strpos($name, '-') + 1;
              $pos2 = strpos($name, '-', $pos1 + strlen('-'));
              $length = abs($pos1 - $pos2);

              $between = strtolower(substr($name, $pos1, $length));
              $name = substr_replace($name, $between, $pos1, $length);
            }
            else
            {
              $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");
            }
          }
          if (strpos($name, '(') !== false) 
          {
            $name = explode('(', rtrim($name, ')'));
            $name = $name[0] . '(' . strtoupper($name[1]) . ')';
          }
 
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
          $keywords = preg_split("/[\s]+/", $name);
          $acronym = '';
          if(strchr($name, '('))
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

          if(strchr($name, '('))
          {
              $acronym .= end($keywords);
          }
        ?>

        <div class="col-lg-2 header-image">
        <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $political_party['id'] ) ?>">
          <img class="img img-responsive img-thumbnail pull-left" src="<?= PARTY_IMAGE . $political_party['profile_image'] ?>" alt="">
        </a>
        
        </div>
        <div class="col-lg-10">
          <strong> <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $political_party['id'] ) ?>"><p class="entity-name"><?= ucwords($name) ?></p></a></strong>
          <span class="entity-second-name"><?= strtoupper($acronym) ?></span>
        </div>
       <div class="clearfix"></div>
      <?php if ($last_id !== $political_party['id']): ?>
        <hr>
      <?php endif ?>
      <?php endforeach ?>
      </div>
      
      <?php else: ?>
       <img id="no_result_img" class="pull-left" src="<?= ADMIN_IMAGES_PATH . 'no_result.jpg' ?>" alt="">
       <p class="marg-0">We couldn't find anything for <strong> <?= $this->input->post('search_term'); ?></strong></p>
       <span>Looking for political parties? Try entering political party full name or some other name</span>
       <!-- <br> -->
       <div class="clearfix"></div>
      <?php endif ?>
<!-- </div>  <--></-->
<!--/col-lg-6-->
  </div><!--/row-->    
<!--/center-->