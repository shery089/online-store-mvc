<!-- 
    Turn on output buffering. No output is sent from the script (other 
    than headers), instead the output is stored in an internal buffer. 
-->
<?php ob_start(); ?>

<!--=====================================
=            Modal comment              =
======================================-->

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeModel()" aria-hidden="true">×</button>
                <h4 class="text-center"><?= ($action == 'intro') ? "Introduction" : "Election History"; ?></h4>
            </div>
              <?php
                $action = $action == 'intro' ? 'introduction' : 'election_history';   
                $action = $entity[$action] ; 
                // $introduction = preg_replace('/\r\n|\r|\n+/', "\n", $introduction);
                $action = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $action)))));
                $action = preg_replace('/\[removed\]/', '', $action);
                // $introduction = implode('<br>', array_map('ucfirst', explode('<br>', preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', '<br>', $introduction))));
                // $introduction = preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', PHP_EOL, $introduction);
                // $introduction = html_entity_decode(stripslashes($introduction), ENT_QUOTES);
                $action = preg_replace('/[<br>]{1, }/', '<br>', $action);
                $action = preg_replace('/[\t]+/', '    ', $action);
                $action = preg_replace('/[\s]+/', ' ', $action);
            ?>
            <ul class="details-section text-justify modal-pad">
              <?php
                $action = explode('<br>', $action);
                    for ($i = 0, $count = count($action); $i < $count; $i++) 
                    { 
                        if(!empty($action[$i]))
                        {
                            echo '<li class="details-section-li">' . $action[$i] . '</li>';
                        }
                    }
                ?>
            </ul>
            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-default" onclick="closeModel()">Close</button>
                </div>
            </div>  <!-- end modal-footer -->
            <?php //endforeach ?>
        </div>
</div>  

<!--====  End of Modal comment  ====-->

<script>
    /**
     * [closeModel: This function closes/remove the modal and also removes
     * modal-backdrop after 500ms i.e 0.5s]
     * @return {[type]} [description]
     */
    function closeModel() 
    {
        jQuery('#modal').modal('hide');
        setTimeout(function(){
            jQuery('#modal').remove();
            jQuery('.modal-backdrop').remove();
        },500);
    }
</script>

<!-- Get current buffer contents and delete current output buffer -->
<?= ob_get_clean(); ?>