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

    var loading = $('#loading');

    var base_url = origin;
    // "http://local.ims.com:8081"

    var message_elem = $('#message');

    /**
     * Top Ten Best Selling Products
     */

    if($.inArray('top_five_best_selling_products', path_parts) !== -1) {

        $(".nav-pills li a:contains('Top 5 Best Selling Products')").parent().addClass('active');

        $('#filter_top_five_best_selling_products').on('click', function () {

            var date = $('#date').val();

            // calling ajax
            $.ajax({
                url: base_url + '/admin/product_analysis/top_five_best_selling_products',
                data: {
                        'date': date
                },
                method: 'post',
                dataType: 'json',
                success: function(data)
                {
                    if(data.date !== undefined) {
                        $('#date_error').html(data.date);
                    }
                    else if(data.length === 0) {
                        $('#date_error').html('');
                        $('#message').html('No Products Found for <strong>' + date + '</strong>').hide().fadeIn();
                    }
                    else {
                        $('#date_error').html('');
                        $('#message').html('');
                        $('#top_five_best_selling_products').empty().hide().fadeIn();
                        Morris.Bar({
                            element: 'top_five_best_selling_products',
                            data: data.results,
                            xkey: ['sales_year'],
                            ykeys: data.product_names_as_keys,
                            labels: data.product_names,
                            hideHover: 'auto',
                            resize: true
                        });
                    }
                }/*,
                error: function()
                {
                    alert('Something went wrong!');
                }*/
            });
        });
    }

    /**
     * Top Ten Least Selling Products
     */

    if($.inArray('top_five_least_selling_products', path_parts) !== -1) {

        $(".nav-pills li a:contains('Top 5 Least Selling Products')").parent().addClass('active');

        $('#filter_top_five_least_selling_products').on('click', function () {

            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            // calling ajax
            $.ajax({
                url: base_url + '/admin/product_analysis/top_five_least_selling_products',
                data: {
                        'start_date': start_date,
                        'end_date': end_date
                },
                method: 'post',
                dataType: 'json',
                success: function(data)
                {
                    if(data.start_date !== undefined && data.end_date !== undefined) {
                        $('#start_date_error').html(data.start_date);
                        $('#end_date_error').html(data.end_date);
                    }
                    else if(data.length === 0) {
                        $('#start_date_error, #end_date_error').html('');
                        $('#message').html('No Products Found between <strong>' + start_date + '</strong> ' +
                            'and <strong>' + end_date + '</strong>').hide().fadeIn();
                    }
                    else {
                        $('#start_date_error, #end_date_error').html('');
                        $('#message').html('');
                        $('#top_five_least_selling_products').empty().hide().fadeIn();
                        Morris.Bar({
                            element: 'top_five_least_selling_products',
                            data: data,
                            xkey: ['product_name'],
                            ykeys: ['total_least_selling'],
                            labels: ['Total Selling'],
                            hideHover: 'auto',
                            resize: true,
                            barColors: function (row, series, type) {
                                return randomColor();
                            }
                        });
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });
        });
    }

    /**
     * Overall Products Sale
     */

    if($.inArray('total_sales', path_parts) !== -1) {

        $(".nav-pills li a:contains('Total Sales')").parent().addClass('active');

        $('#filter_total_sales').on('click', function () {
            var _this = $(this);
            _this.attr('disabled', true);
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            // calling ajax
            $.ajax({
                url: base_url + '/admin/product_analysis/total_sales',
                data: {
                        'start_date': start_date,
                        'end_date': end_date
                },
                method: 'post',
                dataType: 'json',
                success: function(data)
                {
                    if(data.start_date !== undefined && data.end_date !== undefined) {
                        $('#start_date_error').html(data.start_date);
                        $('#end_date_error').html(data.end_date);
                    }
                    else if(data.length === 0) {
                        $('#start_date_error, #end_date_error').html('');
                        $('#message').html('No Products Found between <strong>' + start_date + '</strong> ' +
                            'and <strong>' + end_date + '</strong>').hide().fadeIn();
                    }
                    else {
                        $('#start_date_error, #end_date_error').html('');
                        $('#message').html('');
                        $('#total_sales').empty().hide().fadeIn();
                        Morris.Bar({
                            element: 'total_sales',
                            data: data,
                            xkey: 'sadasd',
                            ykeys: ['total_selling'],
                            labels: ['Total Selling'],
                            hideHover: 'auto',
                            resize: true
                        });
                    }

                    _this.removeAttr('disabled');

                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });
        });
    }
});

function randomColor() {
    var rand = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f' ];
    var color = '#' + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)];
    return color;
}