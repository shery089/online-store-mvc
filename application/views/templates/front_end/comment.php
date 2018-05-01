
  
      <input type="hidden" id="postId" name="post_id" value="<?= $featured_post[0]['id'] ?>">
      <div class="col-lg-12 light-border pull-left">
        <div class="col-lg-6 col-md-6 comm-top">
          <ul class="post text-justify">
              <img class="img-custom img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
              <i class="fa fa-star-o" aria-hidden="true"></i>
              <i class="fa fa-star-o" aria-hidden="true"></i>
              <i class="fa fa-star-o" aria-hidden="true"></i>
              <i class="fa fa-star-o" aria-hidden="true"></i>
              <i class="fa fa-star-o" aria-hidden="true"></i>
            <h5 id="post-user"><?= ucwords($posted_by[0]); ?></h5>
            <em><?= $posted_time . ' ' . $featured_post[0]['posted_date'] ?></em>
          </ul>
        </div>

        <div class="col-lg-6 col-md-6 comm-top post-top-actions-div pull-left">
          <!-- <div class="pull-left"> -->
            <ul class="post-top-actions">
              <li class="post-top-actions-li pull-left"><a id ="post-like" class="post-top-actions-li-item" href="javascript:void(0);">Like</a>: <span id="post-like-count"><?= $post_likes ?></span></li>
              <li class="post-top-actions-li pull-left"><a id ="post-dislike" class="post-top-actions-li-item" href="javascript:void(0);">Dislike</a>: <span id="post-dislike-count"><?= $post_dislikes ?></span></li>
              <li class="post-top-actions-li pull-left"><a id ="post-comment" class="post-top-actions-li-item" href="javascript:void(0);">Comments</a>: <span id="post-comment-count"></span></li>
            </ul>
          <!-- </div> -->
        </div>
        <div class="clearfix"></div>
        
        <div class="col-lg-12 actual-post">
          <div class="text-justify"><?= $post ?></div>
        </div>
        <div class="clearfix"></div>
        
        <div class="col-lg-12 col-md-12 post-comm post-comm-sec-1">
          <div class="col-lg-1 col-md-1 post-comm-sec comm-details">
            <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
          </div>
          <div class="col-lg-11 col-md-11 post-comm-sec text-justify"><strong class="comm-name">Umar Bhatti</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia ullam id perferendis, quidem nam consequatur quasi minus unde officiis temporibus, in blanditiis voluptates incidunt ipsum earum. Excepturi, modi dolorum ratione?
            <br>
            <ul class="comm-actions pull-left">
              <li class="comm-actions-li">
                <a href="javascript:void(0)">Like</a>
              </li>
              <li class="comm-actions-li">
                <a href="javascript:void(0)">Unlike</a>
              </li>
              <li class="comm-actions-li" id="comm-reply">
                <a href="javascript:void(0)">Reply</a>
              </li>
              <li class="comm-actions-li" id="comm-reply">
                <em>Friday at 9:52pm</em>
              </li>
            </ul>
          </div>
        </div>
        <div class="clearfix"></div>
      <div class="col-lg-12 post-comm">
        <div class="col-lg-1 post-comm-sec">
          <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
        </div>
        <div class="col-lg-11 post-comm-sec text-justify"><strong class="comm-name">Sheryar Ahmed</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia ullam id perferendis, quidem nam consequatur quasi minus unde officiis temporibus, in blanditiis voluptates incidunt ipsum earum. Excepturi, modi dolorum ratione?
          <br>
          <ul class="comm-actions pull-left">
            <li class="comm-actions-li">
              <a href="javascript:void(0)">Like</a>
            </li>
            <li class="comm-actions-li">
              <a href="javascript:void(0)">Unlike</a>
            </li>
            <li class="comm-actions-li" id="comm-reply">
              <a href="javascript:void(0)">Reply</a>
            </li>
            <li class="comm-actions-li" id="comm-reply">
              <em>Friday at 9:52pm</em>
            </li>
          </ul>

          <div class="col-lg-12 post-reply">
            <div class="col-lg-1 post-comm-sec">
              <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
            </div>
            <div class="col-lg-11 post-comm-sec text-justify"><strong class="comm-name">Umar Bhatti</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia ullam id perferendis, quidem nam consequatur quasi minus unde officiis temporibus, in blanditiis voluptates incidunt ipsum earum. Excepturi, modi dolorum ratione?
              <br>
              <ul class="comm-actions pull-left">
                <li class="comm-actions-li">
                  <a href="javascript:void(0)">Like</a>
                </li>
                <li class="comm-actions-li">
                  <a href="javascript:void(0)">Unlike</a>
                </li>
                <li class="comm-actions-li" id="comm-reply">
                  <a href="javascript:void(0)">Reply</a>
                </li>
                <li class="comm-actions-li" id="comm-reply">
                  <em>Friday at 9:52pm</em>
                </li>
              </ul>
            <input type="text" class="form-control comm-reply" placeholder="Write a reply...">
            </div>
            </div>
        </div>
      </div>
      
      <div class="col-lg-12 post-comm">
        <div class="col-lg-1 post-comm-sec">
          <img class="img-comment img-responsive img-square img-thumbnail pull-left" src="<?= POLITICIAN_IMAGE . 'no_image_600.png' ?>" alt="">
        </div>
        <div class="col-lg-11 post-comm-sec">
          <input type="text" class="form-control" placeholder="Write a comment...">
        </div>
      </div>