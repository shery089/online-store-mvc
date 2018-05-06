/*
 * Pak Democrates Main JS file
 * Author: Sheryar Ahmed
 * Email: sheryarahmed@gmail.com
 * Last-Modified: 09/01/2016 mm-dd-yyyy
 */

/*====================================================
=            document.ready comment block            =
====================================================*/

$(function() {


    /**
     * [description: closes the success and delete bootstrap alert with fade 
     * and slideUp effect after 5 seconds
     */
    $(".alert-dismissable").fadeTo(5000, 500).slideUp(500, function(){
        $(".alert-dismissable").alert('close');
    });
        
    var url = window.location;
    // http://localhost/ims/admin/user/add_user_lookup
    
    var host = window.location.hostname;
    // localhost
    
    var pathname = window.location.pathname;
    // /ims/admin/user/add_user_lookup
    
    var origin = window.location.origin;
    // http://localhost
    
    var path_parts = pathname.split('/');    
    var url_parts = path_parts[1] + '/' + path_parts[2] + '/' + path_parts[3]
    // ims/admin/user
    
    var active_half_url = origin + '/' + url_parts;
    // http://localhost/ims/admin/user
    
    // adds active class to selected page in bootstrap pagination
    $('ul.pagination').find('b').closest('li').addClass('active');

    var base_url = origin + '/ims';
    // "http://localhost/ims"

    var admin_assets = base_url + '/admin/';
    
    var user_image_path = admin_assets + 'images/users/';

    // adds active class to selected side bar li element    
    $('#side-menu a[href="'+ active_half_url +'"]').parent().addClass('active');

    /**
     * [description: Triggers when '#add_user_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     * @return {[type]}      [description]
     */
    $('#add_user_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('add_user_form', '/ims/admin/user/');
    });

    /**
     * [description: Triggers when '#add_user_form' is submitted. Prevents normal form submission
     * and calls createOrUpdateByAjax(); for ajax form submission]
     * @return {[type]}      [description]
     */
    $('#edit_user_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_user_form', '/ims/admin/user/');
    });

    /*=============================================
    =  createOrUpdateByAjax comment block         =
    =============================================*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/ims/admin/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function createOrUpdateByAjax(formId, redirectPath)
    {
        var formId = $('#' + formId);
        // alert($(formId).attr('action'));
        //grab all form data  
        var formData = new FormData(formId[0]);
        // ajax call
        $.ajax({
            url: formId.attr('action'), // form action url
            type: formId.attr('method'), // form method e.g POST
            data: formData, // user un-encoded data
            contentType: false, //it forces jQuery not to add a Content-Type header, otherwise, the boundary string will be missing from it
            processData: false, // to send non-processed data
            success: function (data) {

                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);

                if(data.success !== undefined) 
                {
                    window.location.href = origin + redirectPath;
                }
                else //show errors
                {
                    $.each(data, function(index, error) {
                        index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                    });
                }
            }
        });

        return false;
    }

/*=====  End of createOrUpdateByAjax comment block  ======*/

    /**
     * [description: Triggers when an element id starts with delete_ and calls 
     * the getModal function by id or number after delete_ pattern]
     */
    $('a[id^="delete_"]').on('click', function(){
        var id = $(this).attr('id');
            id = id.split('_');
            action = id[0];
            id = id[1];
        getModal(base_url + '/admin/user/get_modal/' + id, action)
    });

    /**
     * [description: Triggers when an element id starts with view_ and calls 
     * the getModal function by id or number after view_ pattern]
     */
    $('a[id^="view_"]').on('click', function(){
        var id = $(this).attr('id');
            id = id.split('_');
            action = id[0];
            id = id[1];
        getModal(base_url + '/admin/user/get_modal/' + id, action)
    });

    /*=============================================
    =            getModal comment block           =
    =============================================*/

    function getModal(url, action)
    {
        var data = {'action': action};

        // calling ajax
        jQuery.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function(data)
            {
                jQuery('body').append(data);
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
    =      imageIsLoaded comment block            =
    =============================================*/

    /**
     * [imageIsLoaded: Previews an image and change input image color to success color]
     * @param  {[type]} e [event handler]
     * @return {[type]}   [nothing]
     */
    function imageIsLoaded(e) {
     	$("#image").css("background-color","#8BF1B0");
    	$("#image").css("color","#FFFFF");
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

    /*=======================================================
    =            Image Ajax Loader comment block            =
    =======================================================*/
    
    /**
     * [description: Triggers when image is loaded. Validates the image. If validation 
     * passed then shows a green background color in image id section and displays image
     * in the browser. If validation fails shows a red background color in image id section.]
     */
    $("#image").on('change', function() {

        var file = this.files[0];

        var imagefile = file.type;

        var match= ["image/jpeg","image/png","image/jpg"];

        var max_size = 10000000;

        var max_size_mb = formatBytes(max_size);

        if(!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
        {            
            $("#message").html("<p class='image_error'>Please Select A valid Image File</p>"+"<h4 class='image_error'>Note</h4>"+"<span class='image_error'>Only jpeg, jpg and png Images types are allowed!</span>");
            
            $("#image").css("background-color","#F2003C");
            
            $("#image").css("color","#FFFFF");
            
            $("#image").val(''); 
            
            return false;
        }
        else if(file['size'] > max_size)
        {            
            $("#message").html("<p class='image_error'>Please Select A valid Image File</p>"+"<h4 class='image_error'>Note</h4>"+"<span class='image_error'>Image size should be less than 10 " + max_size_mb + "!</span>");                
            
            $("#image").css("background-color","#F2003C");
            
            $("#image").css("color","#FFFFF");
            
            $("#image").val('');
        
        }
        else
        {
            var image = $('#image').val();
            
            $("#message").empty();
            
            var reader = new FileReader();
            
            $('#loading').show();
            
            reader.onload = imageIsLoaded;
            
            $('#loading').hide();

            //loads the image
            reader.readAsDataURL(this.files[0]);
        }
    });

    /*=====  End of Image Ajax Loader comment block  ======*/
});
        
/*=====  End of document.ready comment block  ======*/