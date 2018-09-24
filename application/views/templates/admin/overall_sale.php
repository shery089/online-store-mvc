<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?= $tabs; ?>
            <h1 class="page-header"><?= $layout_title ?></h1>
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <input class="form-control" readonly type="text" placeholder="Start Date" id="start_date" name="start_date">
                        <div id="start_date_error"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <input class="form-control" readonly disabled type="text" placeholder="End Date" id="end_date" name="end_date">
                        <div id="end_date_error"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="filter_total_sales" type="button">Filter</button>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-lg-12">
                <div id="total_sales"></div>
                <div class="text-left">
                    <h4 id="message">Please select a date range to see overall sale</h4>
                </div>
            </div>
    </div>
</div>