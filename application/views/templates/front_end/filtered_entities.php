<?php ob_start(); ?>
  <?php if (!empty($entities) && empty($entities['name'])): ?>
    <div class="col-lg-12 light-border" id="filtered_results">
  <?php 
      $count = count($entities); 
      $last_id = array_column($entities, 'id');
      $last_id = $last_id[$count-1];
  ?>
    <div class="text-right total_rows"><?= number_format(count($entities)); ?> Records</div>

  <?php foreach ($entities as $entity): ?>
    <?php

        $pol_name = $entity['name'];
        $party_id = $entity['party_id'];
        if(isset($entity['halqa_name']))
        {
          $halqa_name = $entity['halqa_name'];
        }

        $image_path = POLITICIAN_IMAGE;

        $entity_name = $entity['party_name'];
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

        // acronym
        
        $keywords = preg_split("/[\s]+/", $entity_name);
      /*  $acronym = '';
        if(strchr($entity_name, '('))
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

        if(strchr($entity_name, '('))
        {
            $acronym .= end($keywords);
        }*/
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
      <?php if ($controller == 'political_party'): ?>
        <p class="entity-second-name mar-0"><?= ucwords(entity_decode($entity_name)); ?></p>
        <?php if (isset($entity['halqa_name'])): ?>
          <p class="entity-name mar-0"><?= strtoupper(entity_decode($halqa_name)); ?></p>
        <?php endif ?>
      <?php else: ?>
        <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $party_id ) ?>"><span class="entity-second-name"><?= ucwords(entity_decode($entity_name)); ?></span></a>
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
    <p class="marg-0">We couldn't find anything for <strong> <?= strtoupper($this->input->post('halqa_type')); ?></strong></p>
    <span>Looking for politicians? Try other available filters</span>
    <!-- <br> -->
  </div>
 <div class="clearfix"></div>
  <?php endif ?>
<?php echo ob_get_clean(); ?>