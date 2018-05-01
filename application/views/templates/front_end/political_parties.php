
<!--=============================================
=    Section Political Parties block            =
=============================================-->

    <div id="search_results">
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 dark-border" id="entity">
        <h1 class="text-center entity-name">Political Parties</h1>
        <!-- <div class="container"> -->
            <div class="clearfix marbot10 animatedParent animateOnce" data-sequence='250'>
                <div class="row">
                    <?php foreach ($political_parties as $political_party): ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 marbot10 fadeInRight" data-id='<?= entity_decode($political_party['id']); ?>'>
                        <div class="text-center">
                            <div class="grid image-effect2">
                            <?php 
                                $flag = $political_party['profile_image']; 
                                $name = $political_party['name'];
                                $name_len = strlen($name);
                                
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
                            ?>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3">
                                <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $political_party['id']) ?>">
                                    <figure>
                                        <img src="<?= ADMIN_ASSETS . 'images/political_parties/' . entity_decode($flag); ?>" alt="<?= entity_decode($name) ?>" class="img-responsive img-thumbnail">
                                    </figure>
                                </a>
                            </div>
                            <div class="col-lg-10 col-md-9 col-sm-9">
                                <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $political_party['id']) ?>">
                                    <h5 class="fontresize marbot10 color-light entity-name-prnt"><span class="entity-name"><?= ucwords(entity_decode($name)); ?></span></h5>
                                </a>

                                    <?php  
                                        
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
                                    <p class="fontresize marbot0 color-light"><span class="entity-second-name"><?= strtoupper($acronym); ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="pull-right"><?= $links ?></div>
            </div>
        <!-- </div> -->
    </div>
<!--=====  End of Section Political Parties block  ======-->