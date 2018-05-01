<!--=============================================
=    Section Political Parties block            =
=============================================-->

    <div id="search_results">
    </div>
    <div class="col-lg-6 col-sm-6 col-md-6 dark-border" id="entity">
        <h1 class="text-center entity-name">Columnists</h1>
        <!-- <div class="container"> -->
            <div class="clearfix marbot10 animatedParent animateOnce" data-sequence='250'>
                <div class="row">
                    <?php foreach ($columnists as $columnist): ?>  
                    <div class="col-lg-12 col-md-12 col-sm-12 marbot10 fadeInRight" data-id='<?= entity_decode($columnist['id']); ?>'>
                        <div class="text-center">
                            <div class="grid image-effect2">
                            <?php 
                                $image = $columnist['profile_image']; 
                                $name = $columnist['name'];
                                echo '<br>';
                                $thumbnails = array_column($columnist['newspaper_id'], 'thumbnail');
                                $newspaper_names = array_column($columnist['newspaper_id'], 'name');
                            ?>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3">
                                <a href="<?= site_url('front_end/columnist/get_columnist_by_id/' . $columnist['id']) ?>">
                                    <figure>
                                        <img src="<?= ADMIN_ASSETS . 'images/columnists/' . entity_decode($image); ?>" alt="<?= entity_decode($name) ?>" class="img-responsive img-thumbnail">
                                    </figure>
                                </a>
                            </div>
                            <div class="col-lg-10 col-md-9 col-sm-9">
                                <a href="<?= site_url('front_end/columnist/get_columnist_by_id/' . $columnist['id']) ?>">
                                    <h5 class="fontresize marbot10 color-light entity-name-prnt"><span class="entity-name"><?= ucwords(entity_decode($name)); ?></span></h5>
                                </a>
                                <figure>
                                    <?php foreach ($thumbnails as $thumbnail): ?>
                                        <img style="width: 100px;" src="<?= NEWSPAPER_IMAGE . entity_decode($thumbnail); ?>" alt="" class="img-responsive img-thumbnail">
                                    <?php endforeach ?>
                                </figure>
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