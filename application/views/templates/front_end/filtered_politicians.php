
    <?php if (!empty($entities) && empty($entities['name'])): ?>

    <div class="col-lg-12 light-border" id="filtered_results">
    <?php 
        $count = count($entities); 
        $last_id = array_column($entities, 'id');
        $last_id = $last_id[$count-1];
    ?>
    <?php foreach ($entities as $entity): ?>
      <?php
          $pol_id = $entity['id'];
          $pol_name = $entity['name'];

          $entity_name = $entity['party_name'];
          $party_id = $entity['party_id'];

          $image_path = POLITICIAN_IMAGE;

          $entity_name_len = strlen($entity_name);
          // echo strrpos($entity_name, '-');
          
          if (strpos($entity_name, '-') !== false) 
          {
            if(substr_count($entity_name, '-') > 1)
            {
              $entity_name = mb_convert_case(mb_strtolower($entity_name), MB_CASE_TITLE, "UTF-8");

              $pos1 = strpos($entity_name, '-') + 1;
              $pos2 = strpos($entity_name, '-', $pos1 + strlen('-'));
              $length = abs($pos1 - $pos2);

              $between = strtolower(substr($entity_name, $pos1, $length));
              $entity_name = substr_replace($entity_name, $between, $pos1, $length);
            }
            else
            {
              $entity_name = mb_convert_case(mb_strtolower($entity_name), MB_CASE_TITLE, "UTF-8");
            }
          }
          if (strpos($entity_name, '(') !== false) 
          {
            $entity_name = explode('(', rtrim($entity_name, ')'));
            $entity_name = $entity_name[0] . '(' . strtoupper($entity_name[1]) . ')';
          }

          if (strpos($entity_name, '(') !== false) 
          {
              $entity_name = explode('(', rtrim($entity_name, ')'));
              $entity_name = $entity_name[0] . '(' . strtoupper($entity_name[1]) . ')';
          }
          
          if (strpos($entity_name, '-') !== false) 
          {
            if(substr_count($entity_name, '-') > 1)
            {
              $pos1 = strpos($entity_name, '-');
              $pos2 = strrpos($entity_name, '-');
              $length = $pos2 - $pos1 + 1;
              $entity_name = substr_replace($entity_name, ' ', $pos1, $length);
            }
            else
            {
              // $entity_name = explode('(', rtrim($entity_name, ')'));
              // $entity_name = $entity_name[0] . '(' . strtoupper($entity_name[1]) . ')';
            }
          }
      ?>

      <div class="col-lg-2 header-image">
        <?php 
          $controller = empty($entity['political_party_id']) ? 'political_party' : 'politician'; 
          $anti_controller = $controller == 'political_party' ? 'politician' : 'political_party'; 
        ?>
        <a href="<?= site_url('front_end/politician/get_politician_by_id/' . $entity['id'] ) ?>">
          <img class="img img-responsive img-thumbnail pull-left" src="<?= $image_path . $entity['profile_image'] ?>" alt="">
        </a>
      </div>
      <div class="col-lg-10"> 
        <strong> <a href="<?= site_url('front_end/politician/get_politician_by_id/' . $entity['id'] ) ?>"><p class="entity-name mar-0"><?= ucwords(entity_decode($pol_name)); ?></p></a></strong>
        <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $entity['id'] ) ?>"><p class="entity-second-name mar-0"><?= ucwords(entity_decode($entity_name)); ?></p></a>
        <?php if (!empty($entity['halqa_name'])): ?>
          <?php $halqa_name = str_replace('no halqa', ' ', entity_decode($entity['halqa_name'])) ?>
          <p class="entity-name mar-0"><?= strtoupper($halqa_name); ?></p>
        <?php endif ?>
      </div>
     <div class="clearfix"></div>
    <?php if ($last_id !== $entity['id']): ?>
      <hr>
    <?php endif ?>
    <?php endforeach ?>
    </div>
    
    <?php else: ?>
      <div class="col-lg-12 light-border" id="filtered_results">
       <img id="no_result_img" class="pull-left" src="<?= ADMIN_IMAGES_PATH . 'no_result.jpg' ?>" alt="">
       <p class="marg-0">We couldn't find anything for <strong> <?= $this->input->post('search_term'); ?></strong></p>
       <span>Looking for politicians or political parties? Try entering other names</span>
      </div>
       <!-- <br> -->
     <div class="clearfix"></div>
    <?php endif ?>