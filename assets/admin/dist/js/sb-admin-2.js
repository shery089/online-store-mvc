/*
 * Inventory Management System Main JS file
 * Author: Sheryar Ahmed
 * Email: sheryarahmed@gmail.com
 * Created On: 04/29/2018 mm-dd-yyyy
 */

/*====================================================
 =            document.ready comment block           =
 ====================================================*/

$(function() {

    var url = window.location;
    // http://localhost/admin/user/add_user_lookup

    var host = window.location.hostname;
    // localhost

    var pathname = window.location.pathname;
    // /admin/user/add_user_lookup

    var origin = window.location.origin;
    // http://localhost

    var path_parts = pathname.split('/');
    var url_parts = path_parts[1] + '/' + path_parts[2] + '/'
    // ims/admin/user

    var active_half_url = origin + '/' + url_parts;
    // adds active class to selected side bar li element
    $('#side-menu a[href="'+ active_half_url +'"]').parent().addClass('active');

    $('#side-menu').metisMenu();

    var loading = $('#loading');

    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }

    var active_half_url = origin + '/' + url_parts;
    // http://localhost/admin/user

    // adds active class to selected page in bootstrap pagination
    $('ul.pagination').find('b').closest('li').addClass('active');

    var base_url = origin;
    // "http://local.ims.com:8081"

    var admin_assets = base_url + '/admin/';

    var user_image_path = admin_assets + 'images/users/';

    // adds active class to selected side bar li element
    $('#side-menu a[href="'+ active_half_url +'"]').parent().addClass('active');

    /**
     * [description: closes the success and delete bootstrap alert with fade
     * and slideUp effect after 5 seconds
     */
    $(".alert-dismissable").fadeTo(5000, 500).slideUp(500, function(){
        $(".alert-dismissable").alert('close');
    });


    /**
     * [todayDate Formats today's date in yyyy-mm-dd format]
     * @return {[string]} [Today's date in yyyy-mm-dd format]
     */
    function todayDate()
    {
        var today_date = new Date();
        var month = today_date.getMonth()+1;
        var day = today_date.getDate();
        var today_date = today_date.getFullYear() + '-' +
            ((''+month).length < 2 ? '0' : '') + month + '-' +
            ((''+day).length < 2 ? '0' : '') + day;
        return today_date;
    }

    /*====================================================
     =            Submit Handler comment block            =
     ====================================================*/


    /**
     * [description: Triggers when '#edit_gallery_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_gallery_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_gallery_form', '/admin/gallery/product_pictures/' + path_parts[path_parts.length-2]);
    });

    /**
     * [description: Triggers when '#configuration_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#configuration_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('configuration_form', '/admin/configuration/index/');
    });

    if($('#show_notification_hidden').val() == 1) {
        $('#show_notification').attr('checked', true);
    }

    /**
     * [description: Triggers when '#add_company_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#add_company_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('add_company_form', '/admin/company/');
    });

    /**
     * [description: Triggers when '#edit_company_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_company_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_company_form', '/admin/company/');
    });

    /**
     * [description: Triggers when '#add_role_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#add_role_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('add_role_form', '/admin/role/');
    });

    /**
     * [description: Triggers when '#edit_role_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_role_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_role_form', '/admin/role/');
    });

    /**
     * [description: Triggers when '#change_password_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#change_password_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('change_password_form', '/admin/user/');
    });

    /**
     * [description: Triggers when '#add_user_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#add_user_form').on('submit', function(e){
        e.preventDefault();
        var add_user = $('#add_user');
        add_user.addClass('disabled').attr('disabled', true);
        createOrUpdateByAjax('add_user_form', '/admin/user/');
        add_user.removeClass('disabled').removeAttr('disabled');
    });

    /**
     * [description: Triggers when '#edit_user_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_user_form').on('submit', function(e){
        e.preventDefault();
        var edit_user = $('#edit_user');
        edit_user.addClass('disabled').attr('disabled', true);
        createOrUpdateByAjax('edit_user_form', '/admin/user/');
        edit_user.removeClass('disabled').removeAttr('disabled');
    });

    /**
     * [description: Triggers when '#add_category_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#add_category_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('add_category_form', '/admin/category/');
    });

    /**
     * [description: Triggers when '#edit_category_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_category_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_category_form', '/admin/category/');
    });

    /**
     * [description: Triggers when '#add_purchase_order_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */

    $('#add_purchase_order_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('add_purchase_order_form', '/admin/purchase_order/');
    });

    /**
     * [description: Triggers when '#edit_purchase_order_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */

    $('#edit_purchase_order_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_purchase_order_form', '/admin/purchase_order/');
    });

    /**
     * [description: Triggers when '#add_product_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */

    $('#add_product_form').on('submit', function(e){

        var last_inserted_id = getLastInsertedId();

        for(var i = 0; i < last_inserted_id; i++) {

            var product_attribute_details = $("#product_attr_details_" + (i + 1)).val(); //(i + 1) to get 0 + 1 => 1
            var product_attribute = $('#product_attribute_' + (i + 1) + ' option:selected').val(); //(i + 1) to get 0 + 1 => 1

            $('<input>').attr({
                type: 'hidden',
                id: 'submitted_product_attr_details_' + (i + 1),
                name: 'submitted_product_attr_details_' + (i + 1),
                value: product_attribute_details
            }).appendTo('#add_product_form');

            $('<input>').attr({
                type: 'hidden',
                id: 'submitted_product_attr_' + (i + 1),
                name: 'submitted_product_attr_' + (i + 1),
                value: product_attribute
            }).appendTo('#add_product_form');
        }

        e.preventDefault();
        createOrUpdateByAjax('add_product_form', '/admin/product/');
    });

    /**
     * [description: Triggers when '#edit_product_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_product_form').on('submit', function(e){

        var last_inserted_id = getLastInsertedId();

        for(var i = 0; i < last_inserted_id; i++) {

            var product_attribute_details = $("#product_attr_details_" + (i + 1)).val(); //(i + 1) to get 0 + 1 => 1
            var product_attribute = $('#product_attribute_' + (i + 1) + ' option:selected').val(); //(i + 1) to get 0 + 1 => 1

            $('<input>').attr({
                type: 'hidden',
                id: 'submitted_product_attr_details_' + (i + 1),
                name: 'submitted_product_attr_details_' + (i + 1),
                value: product_attribute_details
            }).appendTo('#edit_product_form');

            $('<input>').attr({
                type: 'hidden',
                id: 'submitted_product_attr_' + (i + 1),
                name: 'submitted_product_attr_' + (i + 1),
                value: product_attribute
            }).appendTo('#edit_product_form');
        }

        e.preventDefault();
        createOrUpdateByAjax('edit_product_form', '/admin/product/');
    });

    /**
     * [description: Triggers when '#edit_product_desc_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_product_desc_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_product_desc_form', '/admin/product/');
    });

    /**
     * [description: Triggers when '#add_product_attribute_detail_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#add_product_attribute_detail_form').on('submit', function(e){

        var pick_a_color = $(".pick-a-color").val();

        if($('#submitted_pick_a_color').length == 0)
        {
            $('<input>').attr({
                type: 'hidden',
                id: 'submitted_pick_a_color',
                name: 'submitted_pick_a_color',
                value: pick_a_color
            }).appendTo('#add_product_attribute_detail_form');
        }
        else
        {
            (pick_a_color.length == 0) ? $('#submitted_pick_a_color').val('') : $('#submitted_pick_a_color').val(pick_a_color);
        }

        e.preventDefault();
        createOrUpdateByAjax('add_product_attribute_detail_form', '/admin/product_attribute_detail/');
    });

    /**
     * [description: Triggers when '#edit_product_attribute_detail_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#edit_product_attribute_detail_form').on('submit', function(e){

        var pick_a_color = $(".pick-a-color").val();

        if($('#submitted_pick_a_color').length == 0)
        {
            $('<input>').attr({
                type: 'hidden',
                id: 'submitted_pick_a_color',
                name: 'submitted_pick_a_color',
                value: pick_a_color
            }).appendTo('#edit_product_attribute_detail_form');
        }
        else
        {
            (pick_a_color.length == 0) ? $('#submitted_pick_a_color').val('') : $('#submitted_pick_a_color').val(pick_a_color);
        }

        e.preventDefault();
        createOrUpdateByAjax('edit_product_attribute_detail_form', '/admin/product_attribute_detail/');
    });

    /**
     * [description: Triggers when '#login_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     */
    $('#login_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('login_form', '/admin/login/');
    });

    /*=====  End of Submit Handler comment block  ======*/

    var $sfield = $('#search_by_user_full_name, #search_by_product_name, #search_by_company_name').autocomplete({
        select: function( event, ui )
        {
            if(ui.item.value === 'No Results Found')
            {
                ui.item.value = '';
            }
        },
        focus: function( event, ui )
        {
            if(ui.item.value === 'No Results Found')
            {
                ui.item.value = '';
            }
        },
        source: function(request, response)
        {
            var user_search_btn = $('#user_search_btn');
            var loading = $("#spinner");
            if($.inArray('user', path_parts) !== -1) {
                url = base_url + "/admin/user/user_full_name_autocomplete/";
                $.ajaxSetup({
                    beforeSend: function(){
                        user_search_btn.attr('disabled', true);
                        user_search_btn.children().remove();
                        loading.clone().appendTo('#user_search_btn').removeClass('hide').show();
                    },
                    complete: function(){
                        user_search_btn.removeAttr('disabled');
                        user_search_btn.children().remove();
                        user_search_btn.append('<i class="fa fa-search"></i>');
                        loading.hide();
                    }
                });

                $.post(url, {full_name:request.term}, function(full_name){
                    response($.map(full_name, function(user) {
                        return {
                            value: user
                        };
                    }).slice(0, 5));
                }, "json");
            }
            else if($.inArray('product', path_parts) !== -1) {

                url = base_url + "/admin/product/product_name_autocomplete/";

                $.post(url, {product_name:request.term}, function(product_name){
                    response($.map(product_name, function(product) {
                        return {
                            value: product
                        };
                    }).slice(0, 5));

                }, "json");
            }
            else if($.inArray('company', path_parts) !== -1) {

                url = base_url + "/admin/company/company_name_autocomplete/";

                $.post(url, {company_name:request.term}, function(company_name){
                    response($.map(company_name, function(company) {
                        return {
                            value: company
                        };
                    }).slice(0, 5));

                }, "json");
            }
        },

        minLength: 2,
        autofocus: true,

    });

    /*=============================================
     =  createOrUpdateByAjax comment block         =
     =============================================*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host
     * e.g '/admin/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation();
     * To prevent event from propagating (or "bubbling up") the DOM. So
     * parent element event won;t trigger
     * link: https://css-tricks.com/return-false-and-prevent-default/]
     */
    function createOrUpdateByAjax(formId, redirectPath)
    {
        var formId = $('#' + formId);
        //grab all form data
        var formData = new FormData(formId[0]);
        // ajax call
        $.ajax({
            url: formId.attr('action'), // form action url
            type: formId.attr('method'), // form method e.g POST
            data: formData, // user un-encoded data
            contentType: false, //it forces $ not to add a Content-Type header, otherwise, the boundary string will be missing from it
            processData: false, // to send non-processed data
            success: function (data) {

                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = $.parseJSON(data);
                if(data.success !== undefined)
                {
                    window.location.href = origin + redirectPath;
                }
                else if(data.failure !== undefined)
                {
                    $('#password').val('');
                    $('#password_error').html(data.failure)
                }
                else //show errors
                {
                    $.each(data, function(index, error) {
                        if(index == 'password')
                        {
                            $('#password, #confirm_password').val('');
                        }
                        index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                    });
                }
            }
        });

        return false;
    }

    /*=====  End of createOrUpdateByAjax comment block  ======*/

    /*================================================================
     =            Modal View/Delete Triggers comment block            =
     ================================================================*/

    /**
     * [description: Triggers when an element id starts with delete_ and calls
     * the getModal function by id or number after delete_ pattern]
     */
    $('a[id^="delete"]').on('click', function(){
        var id = $(this).attr('id');
        id = id.split('_');
        action = id[0];
        entity = id[2];
        id = id[1];
        var index = path_parts.indexOf('admin') + 1;
        getModal(base_url + '/admin/' + path_parts[index] + '/get_modal/' + id, action)
    });


    /**
     * [description: Triggers when an element id starts with view_ and calls
     * the getModal function by id or number after view_ pattern]
     */
    $('a[id^="details"]').on('click', function(){
        var id = $(this).attr('id');
        id = id.split('_');
        action = id[0];
        entity = id[2];
        id = id[1];
        var index = path_parts.indexOf('admin') + 1;
        getModal(base_url + '/admin/' + path_parts[index] + '/get_modal/' + id, action)
    });

    if($.inArray('add_gallery_pics_lookup', path_parts) !== -1) {
        Dropzone.autoDiscover = false;
        var index = path_parts[path_parts.length-1];
        if($.isNumeric(index)) {

            var add_gallery_form = $("#add_gallery_form");
            add_gallery_form.css({
                'text-align': 'center',
                'padding': '60px',
                'margin-bottom': '25px'
            });
            add_gallery_form.dropzone({
                url: base_url + "/admin/gallery/add_gallery_pics_lookup/" + index,
                maxFileSize: 50,
                maxFiles: 50,
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png",
                init: function() {
                    this.on("queuecomplete", function() {
                        $('.page-header').before('<p class="alert alert-success alert-dismissable fade in text-center top-height">' +
                            'Please wait for images to be uploaded!' + '<button type="button" class="close" data-dismiss="alert"' +
                            'aria-hidden="true">×</button>' + '</p>');
                        setTimeout(function(){ submitImagesNames(); }, 3000);
                    });
                }
            });
        }
    }


    /*=============================================
     =            submitImagesNames comment block           =
     =============================================*/

    function submitImagesNames()
    {
        // calling ajax
        $.ajax({
            url: base_url + '/admin/gallery/submit_images_names_lookup',
            method: 'post',
            success: function(data)
            {
                var index = path_parts[path_parts.length-1];
                window.location.href = base_url + '/admin/gallery/product_pictures/' + index;
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of submitImagesNames comment block  ======*/

    /**
     * [description: Triggers when an element id starts with view_ and calls
     * the getModal function by id or number after view_ pattern]
     */
    $('a[id^="view"]').on('click', function(){
        var id = $(this).attr('id');
        id = id.split('_');
        action = id[0];
        entity = id[2];
        id = id[1];
        var index = path_parts.indexOf('admin') + 1;
        getModal(base_url + '/admin/' + path_parts[index] + '/get_modal/' + id, action)
    });

    /**
     * [description: Triggers when an element id starts with view_ and calls
     * the getModal function by id or number after view_ pattern]
     */
    $('a[id^="feature"]').on('click', function(){
        var id = $(this).attr('id');
        id = id.split('_');
        var action = id[1];
        var entity = id[2];
        var featured = id[3];
        var entity_id = id[4];
        id = id[1];
        var details = action + '_' + entity + '_' + featured + '_' + entity_id;
        if(path_parts[2] == 'post')
        {
            setPostFeature(base_url + '/admin/' + path_parts[2] + '/set_post_feature_lookup', details);
        }
    });

    /*=====  End of Modal View/Delete Triggers comment block  ======*/


    $(document).on('change', '#search_by_user_role', function() {
        userSearch();
    });

    $(document).on('change', '#search_by_product_category', function() {
        productSearch();
    });

    $(document).on('change', '#search_by_product_company', function() {
        if($.inArray('purchase_order', path_parts) !== -1 || $.inArray('inventory', path_parts) !== -1) {
            purchaseOrderSearch();
            getProductsByCompanyId();
        }
        else {
            productSearch();
        }
    });

    $(document).on('change', '#search_by_product_id', function() {
        if($.inArray('purchase_order', path_parts) !== -1 || $.inArray('inventory', path_parts) !== -1) {
            purchaseOrderSearch();
        }
        else {
            productSearch();
        }
    });

    $(document).on('click', '#product_search_btn', function() {
        productSearch();
    });

    $(document).on('keypress', '#search_by_user_full_name', function(e) {
        if(e.which == 13) {
            userSearch();
        }
    });

    $(document).on('click', '#user_search_btn', function(e) {
        userSearch();
    });

    $(document).on('keypress', '#search_by_quantity', function(e) {
        var _this = $(this);
        var value = _this.val();
        if($.isNumeric(value)) {
            _this.addClass('text-right');
        }
        if(value.length == 0) {
            _this.removeClass('text-right');
        }

        if(e.which === 13) {
            purchaseOrderSearch();
        }

    });

    $(document).on('focusout', '#search_by_quantity', function(e) {
        var _this = $(this);
        if(_this.val().length == 0) {
            _this.removeClass('text-right');
        }
    });

    $(document).on('keypress', '#search_by_company_name, #search_by_company_email', function(e) {
        if(e.which == 13) {
            companySearch();
        }
    });

    $(document).on('click', '#company_search_btn', function(e) {
        companySearch();
    });

    $(document).on('keypress', '#search_by_product_name', function(e) {
        if(e.which == 13) {
            productSearch();
        }
    });

    function userSearch() {
        var role_id = $('#search_by_user_role').val();
        var full_name = $.trim($('#search_by_user_full_name').val());
        if(role_id.length > 0 || full_name.length > 0) {
            searchUser(role_id, full_name);
        }
    }

    function companySearch() {
        var company_name = $('#search_by_company_name').val();
        var company_email = $.trim($('#search_by_company_email').val());
        if(company_name.length > 0 || company_email.length > 0) {
            searchCompany(company_name, company_email);
        }
    }

    function purchaseOrderSearch() {
        var product_company = $.trim($('#search_by_product_company').val());
        var product_id = $.trim($('#search_by_product_id').val());
        var product_quantity = $.trim($('#search_by_quantity').val());
        if(product_company.length > 0 || product_id.length > 0 || product_quantity.length > 0) {
            searchPurchaseOrder(product_company, product_id, product_quantity);
        }
    }

    function productSearch() {
        var product_category = $.trim($('#search_by_product_category').val());
        var product_company = $.trim($('#search_by_product_company').val());
        var product_name = $.trim($('#search_by_product_name').val());

        if(product_category.length > 0 || product_name.length > 0 || product_company.length > 0) {
            searchProduct(product_category, product_name, product_company);
        }
    }

    $(document).on('change', '[id^="product_"]', function() {
        if($.inArray('purchase_order', path_parts) !== -1) {
            $('[id^="product_attribute_"]').removeAttr('disabled');
            $('.selectpicker').selectpicker('refresh');
            var _this = $(this);
            var product_id = _this.val();
            var attr_id = _this.attr('id').split('_');
            if($.isNumeric(attr_id[1])) {
                var attr_id = _this.attr('id').split('_').pop();
                getProductSpecificAttributes(product_id, attr_id);
            }
        }
    });

    $(document).on('change', '[id^="product_attribute_"]', function() {
        if($.inArray('product_attribute', path_parts) !== -1) {
            if($('#product_attribute option:selected').text().toLowerCase()  == 'color') {

                $('#product_attribute_detail').removeAttr('name');
                $('.pick-a-color').val('');
                $('#product_attribute_detail_color_error').html('');

                var product_attribute_detail_color = $('#product_attribute_detail_color').attr('name');

                if (typeof product_attribute_detail_color == typeof undefined || product_attribute_detail_color == false) {

                    $('#product_attribute_detail_color').attr('name', 'product_attribute_detail_color');
                }

                $('#product_attribute_detail').val('');
                $( "#product_attribute_detail" ).closest( ".col-lg-12" ).addClass('hide');
                $( "#product_attribute_detail_color" ).closest( ".col-lg-12" ).removeClass('hide');

            } else {
                $('.pick-a-color').val('');
                $('#product_attribute_detail').val('');
                $('#product_attribute_detail_color').removeAttr('name');
                $('#product_attribute_detail_error').html('');
                var product_attribute_detail = $('#product_attribute_detail').attr('name');

                if (typeof product_attribute_detail == typeof undefined || product_attribute_detail_color == false) {
                    $('#product_attribute_detail').attr('name', 'product_attribute_detail');
                }

                $( "#product_attribute_detail_color" ).closest( ".col-lg-12" ).addClass('hide');
                $( "#product_attribute_detail" ).closest( ".col-lg-12" ).removeClass('hide');
            }
        }
        else {
            $('[id^="product_attr_details_"]').removeAttr('disabled');
            $('.selectpicker').selectpicker('refresh');
            var _this = $(this);
            var id = _this.attr('id');
            var selected_text = $( '#' + id + ' option:selected').text().toLowerCase();
            var product_attribute_id = _this.val();
            id = id.split('_');
            id = id.pop();
            if($.inArray('purchase_order', path_parts) !== -1) {
                product_id = $('#product_' + id).val();
                getProductDetailOptionsForPurchase(id, product_attribute_id, selected_text, '', product_id);
            }
            else {
                getProductDetailOptions(id, product_attribute_id, selected_text);
            }
        }
    });

    $('#add_new_product_attribute_section').on('click', function(){
        var last_inserted_id = getLastInsertedId();

        var prod_attr_total = getProductAttributeChildrenCount(last_inserted_id);

        if(last_inserted_id < prod_attr_total) {

            last_inserted_id = parseInt(last_inserted_id) + 1; // +1 to have a unique id

            addNewProductAttributeSection(last_inserted_id);

            $('#delete_new_product_attribute_section').removeClass('hide');
        }
        if(last_inserted_id >= prod_attr_total) { // if last row is inserted then show + icon

            $('#add_new_product_attribute_section').addClass('hide');

            $('#delete_new_product_attribute_section').removeClass('hide');
        }

    });

    $('#add_new_purchase_order_section').on('click', function(){
        var last_inserted_id = getLastInsertedId();

        var prod_attr_total = getProductAttributeChildrenCount(last_inserted_id);

        last_inserted_id = parseInt(last_inserted_id) + 1; // +1 to have a unique id

        addNewPurchaseOrderSection(last_inserted_id);

        if($('[id^="sale_price_"]').length > 1) {
            $('[id^="delete_new_purchase_order_section_"]').removeClass('hide').removeAttr('style');
        }
        else {

        }
    });

    $('#delete_new_product_attribute_section').on('click', function(){

        $('#loader').show();

        $('#add_new_product_attribute_section, #delete_new_product_attribute_section').attr('disabled', true);

        var last_inserted_id = getLastInsertedId();

        if(last_inserted_id > 1) {
            $('#product_attribute_' + last_inserted_id).closest('.col-lg-6').remove();
            $('#product_attr_details_' + last_inserted_id).closest('.col-lg-6').remove();
            $('#add_new_product_attribute_section').removeClass('hide');
        }
        if(last_inserted_id == 2) { // if second last row is deleted then show + icon
            $('#add_new_product_attribute_section').removeClass('hide');
            $('#delete_new_product_attribute_section').addClass('hide');
        }

        $('.selectpicker').selectpicker('refresh');

        $('#add_new_product_attribute_section, #delete_new_product_attribute_section').removeAttr('disabled');

        $('#loader').hide();

    });

    $(document).on('click', '[id^="delete_new_purchase_order_section_"]', function(){

        $('#loader').show();
        var _this = $(this);
        var id = _this.data('id');
        $('#purchase_record_' + id).fadeOut('slow', function () {
            $('#add_new_product_attribute_section').attr('disabled', true);
            $('[id^="delete_new_purchase_order_section_"]').attr('disabled', true);
            $(this).remove();
        });

        if($('[id^="sale_price_"]').length > 1) {
            $('[id^="delete_new_purchase_order_section_"]').hide();
        }

        $('#add_new_product_attribute_section').removeAttr('disabled');
        $('[id^="delete_new_purchase_order_section_"]').removeClass('hide').removeAttr('disabled');
        $('#loader').hide();

    });

    /**
     * This function returns last inserted id of Product Attribute or Product Attribute Details
     * and total number of records in the Product Attribute Drop Down
     * @returns {int}
     */

    function getLastInsertedId() {

        var prod_attr_dtl_elem_len =  $('[id^="product_attr_details_"]').length;
        var last_inserted_id_arr = $('[id^="product_attr_details_"]').eq(prod_attr_dtl_elem_len - 1).attr('id'); // to get the last inserted id

        last_inserted_id_arr = last_inserted_id_arr.split('_');
        last_inserted_id = last_inserted_id_arr.pop();
        if(!$.isNumeric(last_inserted_id)) {
            last_inserted_id = last_inserted_id_arr[last_inserted_id_arr.length-1];
        }
        return last_inserted_id;
    }

    /**
     * This function returns total Product Attribute options Count
     * @returns {int}
     */

    function getProductAttributeChildrenCount(id) {
        var length = $('#product_attribute_' + id).children('option').length;
        return length - 1; // to ignore Choose one or more option in the count
    }

    if($.inArray('edit_purchase_order_lookup', path_parts) !== -1)
    {
        var product_attribute_value_elem = $('#product_attribute_value');
        var product_attribute_value = product_attribute_value_elem.val();
        if(product_attribute_value.indexOf('#') !== -1) {
            product_attribute_value_elem.css('background', product_attribute_value);
        }
    }

    if($.inArray('product_attribute_detail', path_parts) !== -1)
    {
        $(".pick-a-color").pickAColor({
            showSpectrum            : true,
            showSavedColors         : true,
            saveColorsPerElement    : true,
            fadeMenuToggle          : true,
            showAdvanced            : true,
            showBasicColors         : true,
            showHexInput            : true,
            allowBlank              : true,
            inlineDropdown          : true
        });
    }
/*
    if($.inArray('purchase_order', path_parts) !== -1)
    {
        $('[id^="product_attribute_"]').attr('disabled', true);
        $('[id^="product_attr_details_"]').attr('disabled', true);
        $('.selectpicker').selectpicker('refresh');
    }*/

    /*=============================================
     =     getProductSpecificAttributes block     =
     =============================================*/

    function getProductSpecificAttributes(product_id, attr_id)
    {
        var data = {'product_id': product_id};
        // calling ajax
        $.ajax({
            url: base_url + '/admin/product/get_product_specific_attributes',
            method: 'post',
            data: data,
            success: function(data)
            {
                $('#product_attribute_' + attr_id).html(data);
                $('.selectpicker').selectpicker('refresh');
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of getProductSpecificAttributes block  ======*/


    /*=============================================
     =     getProductsByCompanyId block     =
     =============================================*/

    function getProductsByCompanyId()
    {
        var search_by_product_id_elem = $('#search_by_product_id');
        search_by_product_id_elem.attr('disabled', true);
        var data = {'product_company': $('#search_by_product_company').val()};
        // calling ajax
        $.ajax({
            url: base_url + '/admin/product/get_products_by_company_id',
            method: 'post',
            data: data,
            success: function(data)
            {
                search_by_product_id_elem.html(data);
                search_by_product_id_elem.removeAttr('disabled');
                $('.selectpicker').selectpicker('refresh');
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of getProductSpecificAttributes block  ======*/

    /*=============================================
     =              setPostFeature block           =
     =============================================*/

    function setPostFeature(url, details)
    {

        var data = {'details': details};
        // calling ajax
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function(data)
            {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = $.parseJSON(data);

                if(data.success !== undefined)
                {
                    window.location.href = window.location;
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of setPostFeature block  ======*/

    /*=============================================
     =            getModal comment block           =
     =============================================*/

    function getModal(url, action)
    {
        var data = {'action': action};
        // calling ajax
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function(data)
            {
                $('body').append(data);
                $("#modal").modal({backdrop: "static", toggle: true});
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of getModal comment block  ======*/

    /*=============================================
     =            searchUser comment block           =
     =============================================*/

  /*  function searchUser(id)
    {
        var data = {'role_id': id};
        // calling ajax
        $.ajax({
            url: base_url + '/admin/user/search_user_lookup/',
            method: 'post',
            data: data,
            success: function(data)
            {
                $('#searched_results').empty();
                $('#searched_results').append(data);
                //$("#modal").modal({backdrop: "static", toggle: true});
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }*/

    /*=====  End of getModal comment block  ======*/


    /*=============================================
     =            searchUser comment block           =
     =============================================*/

    function searchUser(role_id, full_name)
    {
        var data = {'role_id': role_id, 'full_name': full_name};

        var elemets_to_disable = $('#search_by_user_role, #search_by_user_full_name, #user_search_btn');

        // calling ajax
        $.ajax({
            url: base_url + '/admin/user/search_user_lookup/',
            method: 'post',
            data: data,
            beforeSend: function() {
                $('#searched_results').empty();
                elemets_to_disable.attr('disabled', true);
                loading.show();
            },
            success: function(data)
            {
                loading.hide();
                elemets_to_disable.removeAttr('disabled');
                $('#searched_results').append(data);
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of searchUser comment block  ======*/


    /*=============================================
     =            searchCompany comment block     =
     =============================================*/

    function searchCompany(company_name, company_email)
    {
        var data = {'company_name': company_name, 'company_email': company_email};

        var elemets_to_disable = $('#search_by_company_name, #search_by_company_email, #company_search_btn');

        // calling ajax
        $.ajax({
            url: base_url + '/admin/company/search_company_lookup/',
            method: 'post',
            data: data,
            beforeSend: function() {
                $('#searched_results').empty();
                elemets_to_disable.attr('disabled', true);
                loading.show();
            },
            success: function(data)
            {
                loading.hide();
                elemets_to_disable.removeAttr('disabled');
                $('#searched_results').append(data);
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of searchCompany comment block  ======*/


    /*=============================================
     =      searchProduct comment block           =
     =============================================*/
    function searchProduct(product_category, product_name, product_company)
    {
        var data = {'product_category': product_category, 'product_name': product_name, 'product_company': product_company};
        // calling ajax

        $.ajax({
            url: base_url + '/admin/product/search_product_lookup/',
            method: 'post',
            data: data,
            success: function(data)
            {
                $('#searched_results').empty();
                $('#searched_results').append(data);
                //$("#modal").modal({backdrop: "static", toggle: true});
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=============================================
     =      searchPurchaseOrder comment block     =
     =============================================*/
    function searchPurchaseOrder(product_company, product_id, product_quantity)
    {
        var data = {'product_id': product_id, 'product_company': product_company, 'product_quantity': product_quantity};

        // calling ajax
        $.ajax({
            url: base_url + '/admin/purchase_order/search_purchase_order_lookup/',
            method: 'post',
            data: data,
            success: function(data)
            {
                $('#searched_results').empty();
                $('#searched_results').append(data);
                //$("#modal").modal({backdrop: "static", toggle: true});
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }


    $(document).on('click', ".pagination li a", function(){
        var search_by_user_role = $('#search_by_user_role').val();
        $.ajax({
            type: "POST",
            url: $(this).attr("href"),
            data: {'role_id': search_by_user_role},
            success: function(res){
                $("#searched_results").html(res);
            }
        });
        return false;
    });

    /*=====  End of getModal comment block  ======*/



    /*=============================================
     =            addNewProductAttributeSection comment block           =
     =============================================*/

    function addNewProductAttributeSection(id_prepend)
    {
        var options_arr = getSelectedProductAttributes();

        var url = base_url + '/admin/product/add_new_product_attribute_section';

        var data = {'id_prepend': id_prepend, 'options_arr': options_arr};
        // calling ajax
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            beforeSend:function()
            {
                $('#loader').show();
                $('#add_new_product_attribute_section, #delete_new_product_attribute_section').attr('disabled', true);
            },
            success: function(data)
            {
                $('#add_new_product_attribute_section').parent().before(data);
                $('.selectpicker').selectpicker('refresh');

                for(var i= 0, length = options_arr.length; i < length; i++){
                    $('#product_attribute_' + id_prepend + ' option:contains(' + options_arr[i] + ')').attr('disabled', true);
                }

                $('.selectpicker').selectpicker('refresh');
                $('#loader').hide();
                $('#add_new_product_attribute_section, #delete_new_product_attribute_section').removeAttr('disabled');
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of addNewProductAttributeSection comment block  ======*/


    /*=============================================
     =            addNewProductAttributeSection comment block           =
     =============================================*/

    function addNewPurchaseOrderSection(id_prepend)
    {
        var options_arr = getSelectedPurchaseOrderAttributes();

        var url = base_url + '/admin/purchase_order/add_new_purchase_order_section';

        var data = {'id_prepend': id_prepend, 'options_arr': options_arr};
        // calling ajax
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            beforeSend:function()
            {
                $('#loader').show();
                $('#add_new_purchase_order_section').attr('disabled', true);
                $('[id^="delete_new_purchase_order_section_"]').attr('disabled', true);
            },
            success: function(data)
            {
                $('#add_new_purchase_order_section').parent().before(data);
                $('.selectpicker').selectpicker('refresh');
                $('#loader').hide();
                $('#add_new_purchase_order_section').removeAttr('disabled');
                $('[id^="delete_new_purchase_order_section_"]').removeClass('hide').removeAttr('disabled');
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of addNewPurchaseOrderSection comment block  ======*/


    function getSelectedProductAttributes() {

        var selected_options = $('[id^="product_attribute_"] option:selected');// $(' option:selected');
        var length = selected_options.length;
        var options_arr = [];
        for(var i=0; i < length; i++){
            options_arr.push(selected_options.eq(i).text());
        }

        var index = options_arr.indexOf("Choose one or more...");

        if (index != -1) {
            options_arr.splice(index, 1);
        }

        options_arr = $.grep(options_arr, function(n){ return (n); });

        return options_arr;
    }

    function getSelectedPurchaseOrderAttributes() {

        var selected_options = $('[id^="product_attribute_"] option:selected');// $(' option:selected');
        var length = selected_options.length;
        var options_arr = [];
        for(var i=0; i < length; i++){
            options_arr.push(selected_options.eq(i).text());
        }

        var index = options_arr.indexOf("Choose one or more...");

        if (index != -1) {
            options_arr.splice(index, 1);
        }

        options_arr = $.grep(options_arr, function(n){ return (n); });

        return options_arr;
    }

    function ucWords (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }

    /*=============================================
     =            getProductDetailOptions comment block           =
     =============================================*/

    function getProductDetailOptions(id, product_attribute, selected_text, product_attribute_detail_values)
    {
        var url = base_url + '/admin/product/get_product_details_options';

        var product_attribute_detail_values = product_attribute_detail_values || '';

        var data = {
                'product_attribute': product_attribute, 'selected_text': selected_text,
                'product_attribute_detail_values': product_attribute_detail_values
            };

        // calling ajax
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function(data)
            {
                $('select[id^="product_attribute_"] option:not(:selected)').removeAttr('disabled');
                $('.selectpicker').selectpicker('refresh');
                var options_arr = getSelectedProductAttributes();
                for(var i= 0, length = options_arr.length; i < length; i++){
                    $('#product_attribute_' + id + ' option:contains(' + ucWords(options_arr[i]) + ')').attr('disabled', true);
                }
                $('#product_attribute_' + id + ' .selectpicker').selectpicker('refresh');

                $('#product_attr_details_' + id).empty();
                $('#product_attr_details_' + id).append(data);
                //$('#product_attr_details_' + id + ' .selectpicker').selectpicker('refresh');

                //if($('#product_attr_details_' + id + ' option').length == 1) {
                    //
                //}

                $('.selectpicker').selectpicker('refresh');
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of getProductDetailOptions comment block  ======*/

    /*=============================================
     =  getProductDetailOptions comment block     =
     =============================================*/

    function getProductDetailOptionsForPurchase(id, product_attribute, selected_text, product_attribute_detail_values, product_id)
    {
        var url = base_url + '/admin/product/get_product_details_options_by_product_id';

        var product_attribute_detail_values = product_attribute_detail_values || '';

        var data = {
                'product_attribute': product_attribute, 'selected_text': selected_text,
                'product_attribute_detail_values': product_attribute_detail_values,
                'product_id': product_id
            };

        // calling ajax
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function(data)
            {
                $('select[id^="product_attribute_"] option:not(:selected)').removeAttr('disabled');
                $('.selectpicker').selectpicker('refresh');
                var options_arr = getSelectedProductAttributes();
                for(var i= 0, length = options_arr.length; i < length; i++){
                    $('#product_attribute_' + id + ' option:contains(' + ucWords(options_arr[i]) + ')').attr('disabled', true);
                }
                $('#product_attribute_' + id + ' .selectpicker').selectpicker('refresh');

                $('#product_attr_details_' + id).empty();
                $('#product_attr_details_' + id).append(data);
                //$('#product_attr_details_' + id + ' .selectpicker').selectpicker('refresh');

                //if($('#product_attr_details_' + id + ' option').length == 1) {
                    //
                //}

                $('.selectpicker').selectpicker('refresh');
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    /*=====  End of getProductDetailOptionsForPurchase comment block  ======*/

    /*=============================================
     =      imageIsLoaded comment block            =
     =============================================*/

    /**
     * [imageIsLoaded: Previews an image and change input image color to success color]
     * @param  {[type]} e [event handler]
     * @return {[type]}   [nothing]
     */
    function imageIsLoaded(e) {
        $('#previewing').attr('src', e.target.result);
    };

    /*=====  End of imageIsLoaded comment block  ======*/

    /*=================================================
     =            formatBytes comment block            =
     =================================================*/

    /**
     * [formatBytes: Converts bytes into MB's etc]
     * @param  {[type]} bytes    [description]
     * @param  {[type]} decimals [optional]
     * @return {[type]} [bytes into MB converted data]
     */
    function formatBytes(bytes,decimals) {
        if(bytes == 0) return '0 Byte';
        var k = 1000;
        var dm = decimals + 1 || 3;
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    /*=====  End of formatBytes comment block  ======*/

    // hides the loading .gif image
    $('#loading').hide();
    $('#loader').hide();

    /**
     * [Triggers when image is loaded. Get image/file contents. e.g
     * File {name: "7.jpeg", lastModified: 1457766049401, lastModifiedDate: Sat Mar 12 2016 12:00:49 GMT+0500 (Pakistan Standard Time),
     * webkitRelativePath: "", size: 67210…}
     * then, it calls ajaxImageLoader function and file id and file contents are passed to the function and image is loaded]
     */
    $("#image").on('change', function() {

        var file = this.files[0];

        ajaxImageLoader('image', file)

    });

    /**
     * [Triggers when image/flag is loaded. Get flag/file contents. e.g
     * File {name: "7.jpeg", lastModified: 1457766049401, lastModifiedDate: Sat Mar 12 2016 12:00:49 GMT+0500 (Pakistan Standard Time),
     * webkitRelativePath: "", size: 67210…}
     * then, it calls ajaxImageLoader function and file id and file contents are passed to the function and flag/image is loaded]
     */
    $("#flag").on('change', function() {

        var file = this.files[0];

        ajaxImageLoader('flag', file)

    });

    /*=======================================================
     =            Image Ajax Loader comment block            =
     =======================================================*/

    /**
     * [ajaxImageLoader: Validates the image. If validation
     * passed then shows a green background color in file id section and displays image
     * in the browser. If validation fails shows a red background color in file id section.
     * and shows error message]
     * @param  {[string]} id   [file id attribute value e.g. image]
     * @param  {[]} file [description]
     * @return {[type]}      [description]
     */
    function ajaxImageLoader(id, file)
    {
        var id = '#' + id;

        var imagefile = file.type;

        var match= ["image/jpeg","image/png","image/jpg"];

        var max_size = 10000000;

        var max_size_mb = formatBytes(max_size);

        if(!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
        {
            $("#message").html("<p class='image_error'>Please Select A valid Image File</p>"+"<h4 class='image_error'>Note</h4>"+"<span class='image_error'>Only jpeg, jpg and png Images types are allowed!</span>");

            $(id).css("background-color","#F2003C");

            $(id).css("color","#FFFFF");

            $(id).val('');

            return false;
        }
        else if(file['size'] > max_size)
        {
            $("#message").html("<p class='image_error'>Please Select A valid Image File</p>"+"<h4 class='image_error'>Note</h4>"+"<span class='image_error'>Image size should be less than 10 " + max_size_mb + "!</span>");

            $(id).css("background-color","#F2003C");

            $(id).css("color","#FFFFF");

            $(id).val('');

        }
        else
        {
            var image = $(id).val();

            $("#message").empty();

            var reader = new FileReader();

            $('#loading').show();

            $(id).css("background-color","#8BF1B0");

            $(id).css("color","#FFFFF");

            reader.onload = imageIsLoaded;

            $('#loading').hide();

            //loads the image
            reader.readAsDataURL(file);
        }
    }

    /*=====  End of Image Ajax Loader comment block  ======*/

    $('.pagination a').on('click', function(){
        var _this = $(this);
        window.location = _this.attr('href');
    });

    if($.inArray('edit_product_lookup', path_parts) !== -1) {
        $(".nav-pills li a:contains('Product Details')").parent().addClass('active');
    }

    if($.inArray('edit_product_description_lookup', path_parts) !== -1) {
        $(".nav-pills li a:contains('Product Description')").parent().addClass('active');
    }

    if($.inArray('add_gallery_pics_lookup', path_parts) !== -1) {
        $(".nav-pills li a:contains('Add New Product Images')").parent().addClass('active');
    }

    if($.inArray('product_pictures', path_parts) !== -1) {
        $(".nav-pills li a:contains('Edit Product Gallery Images')").parent().addClass('active');
    }

    if($.inArray('edit_product_lookup', path_parts) !== -1) {
        var count = $('[id^="product_attribute_"] option:selected').length;

        for (var i = 0; i < count; i++) {
            var product_attribute_id = $('[id^="product_attribute_"] option:selected').eq(i).val();
            var selected_text = $('[id^="product_attribute_"] option:selected').eq(i).text().toLowerCase();
            var id = $('[id^="product_attribute_"] option:selected').eq(i).parent().attr('id');
            id = id.split('_');
            id = id.pop();

            var product_attribute_detail_values = $('#product_attribute_detail_value').val();
            getProductDetailOptions(id, product_attribute_id, selected_text, product_attribute_detail_values);
        }
    }
});

/*=====  End of document.ready comment block  ====== */