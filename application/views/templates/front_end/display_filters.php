<?php ob_start(); ?>   
  <div class="col-lg-12 light-border martop10" id="filter-details">        
    <h3>Politicians</h3>    
    <?= form_open_multipart('', 'class=form id=politician_filter_form novalidate'); ?>
    <div class="col-lg-12 select-spacing">
        <select class="selectpicker form-control" data-live-search="true" id="by_party" title="Search By Politicial Party">    
          <?php foreach ($political_parties as $political_party): ?>
            <?php 
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
            <option value="<?= $political_party['id'] ?>"><?= ucwords(entity_decode($name)) ?></option>  
          <?php endforeach ?>
        </select>
    </div>
    <br>

    <div class="col-lg-5 select-spacing">
        <select class="selectpicker form-control" data-live-search="true" id="halqas" title="Search By Halqa Type">
            <option value="na">NA</option>  
            <option value="pp">PP</option>  
            <option value="ps">PS</option>  
            <option value="pk">PK</option>  
            <option value="pb">PB</option>  
        </select>
    </div>    

    <div class="col-lg-1"></div>

    <div class="col-lg-6 select-spacing">
        <select class="selectpicker form-control" data-live-search="true" id="provincial_assembly" title="Provincial Assemblies">
            <?php for ($i = 0, $count = count($halqa_types); $i < $count - 1; $i++): ?> 
                <option value="<?= $halqa_types[$i]['id'] ?>"><?= ucwords($halqa_types[$i]['name']); ?></option>  
            <?php endfor; ?>    
        </select>
    </div>
    <div class="col-lg-5 select-spacing">
        <div class="form-group">
            <select name="provincial_halqa" class="form-control" id="provincial_halqa" title="Choose Provincial Halqa" data-actions-box="true" data-live-search="true">
            </select>
        </div>
    </div>

<!--     <div class="col-lg-6 select-spacing">
        <select class="selectpicker form-control" data-live-search="true" id="province" title="Province">
            <?php foreach ($provinces as $province): ?>
                <option value="<?= $province['id'] ?>"><?= ucwords($province['name']); ?></option>  
            <?php endforeach ?>    
        </select>
    </div>
     -->    
    <div class="col-lg-1"></div>

    <div class="col-lg-6 select-spacing">
        <select class="selectpicker form-control" id="age" title="Age">
            <option value="lt30">Less than 30 Years</option>  
            <option value="gt30lt45">Between 30 to 45 (Years)</option>  
            <option value="gt45">Greater than 45 Years</option>  
        </select>
    </div>
    <div class="clearfix"></div>    
    <div class="col-lg-5" style="padding: 0">
        <select class="selectpicker form-control" data-live-search="true" id="city" title="City">
            <?php foreach ($cities as $city): ?>
                <option value="<?= $city['id'] ?>"><?= ucwords($city['name']); ?></option>  
            <?php endforeach ?>    
        </select>
    </div>
    <div class="col-lg-1"></div>


    <div class="col-lg-6 select-spacing">
        <label for="m">Male</label>
        <input type="radio" id="m" name="gender">

        <label for="f">Female</label>
        <input type="radio" id="f" name="gender"> 
    </div>
    <div class="col-lg-12 select-spacing">
        <input class="btn btn-default" id="filter_reset" type="reset" value="Reset Filter">
    </div>

    <?= form_close(); ?>
  </div>      
    <script>
     $('.selectpicker').selectpicker({});
     </script>
<?php echo ob_get_clean(); ?>