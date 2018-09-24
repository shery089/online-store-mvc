<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?= $tabs; ?>
            <h1 class="page-header"><?= $layout_title ?></h1>
            <div class="row">
                <div class="col-lg-3 form-item-height">
                    <div class="form-group">
                        <?php

                        $data = array(

                            'class'         => 'form-control selectpicker',
                            'id'            => 'date',
                            'name'          => 'date',
                            'title'         => 'Get results for',
                            'data-actions-box' => 'true'
                        );

                        foreach ($dates_ranges as $key => $dates_range)
                        {
                            $options[$key] = $dates_range;
                        }

                        $selected = array();

                        ?>
                        <?= form_dropdown('date', $options, $selected, $data); ?>

                        <div id="date_error"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="filter_top_five_best_selling_products" type="button">Filter</button>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-lg-12">
                <div id="top_five_best_selling_products"></div>
                <div class="text-left">
                    <h4 id="message">Please select a date range to see Top 5 best selling items</h4>
                </div>
            </div>
    </div>
</div>