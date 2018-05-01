<!--=============================================
=    Section Political Parties block            =
=============================================-->

    <div id="search_results">
    </div>
    <div class="col-lg-6 col-sm-6 col-md-6 dark-border" id="entity">
        <h1 class="text-center entity-name">Politicians</h1>
        <!-- <div class="container"> -->
            <div class="clearfix marbot10 animatedParent animateOnce" data-sequence='250'>
                <div class="row">
                    <?php foreach ($politicians as $politician): ?>  
                    <div class="col-lg-12 col-md-12 col-sm-12 marbot10 fadeInRight" data-id='<?= entity_decode($politician['id']); ?>'>
                        <div class="text-center">
                            <div class="grid image-effect2">
                            <?php 
                                $image = $politician['profile_image']; 
                                $name = $politician['name'];
                                $party_name = $politician['political_party_id']['name'];
                                $party_id = $politician['political_party_id']['id'];
                            ?>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3">
                                <a href="<?= site_url('front_end/politician/get_politician_by_id/' . $politician['id']) ?>">
                                    <figure>
                                        <img src="<?= ADMIN_ASSETS . 'images/politicians/' . entity_decode($image); ?>" alt="<?= entity_decode($name) ?>" class="img-responsive img-thumbnail">
                                    </figure>
                                </a>
                            </div>
                            <div class="col-lg-10 col-md-9 col-sm-9">
                                <a href="<?= site_url('front_end/politician/get_politician_by_id/' . $politician['id']) ?>">
                                    <h5 class="fontresize marbot10 color-light entity-name-prnt"><span class="entity-name"><?= ucwords(entity_decode($name)); ?></span></h5>
                                </a>
                                <a href="<?= site_url('front_end/political_party/get_political_party_by_id/' . $party_id) ?>">
                                    <p class="fontresize marbot0 color-light"><span class="entity-second-name"><?= ucwords($party_name); ?></span></p>                                  
                                </a>
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