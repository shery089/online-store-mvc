/*
 *
 * Pak Democrates JS file
 * Autor: Sheryar Ahmed
 * Web-author: https://about.me/sheryar.ahmed
 * 
 */
 $(document).ready(function(){
    var origin = window.location.origin;
    // http://localhost

    var pathname = window.location.pathname;
    // /pak_democrates/admin/user/add_user_lookup
    var path_parts = pathname.split('/');
    var base_url = origin + '/pak_democrates/f/';
    var base_url_admin = origin + '/pak_democrates/admin/';
    // "http://localhost/pak_democrates/f"

    // from a jQuery collection
    autosize($('textarea'));

    /**
     * [Checks whether a user is logged in and user data are being set in cookie.
     * Then, removes login and sign up li's from navbar and inserts new li with
     * User profile options]
     * @type {Boolean}
     */
    var win = $(window);
    // Each time the user scrolls
    win.scroll(function() {
        // lock scroll position, but retain settings for later
        var party = $('#by_party').val() == '' ? '' : $('#by_party').val();
        var halqa_type = $('#halqas').val() == '' ? '' : $('#halqas').val();
        var provincial_halqa = $('#provincial_halqa').val() == '' ? '' : $('#provincial_halqa').val();
        var gender = $('input[name=gender]:checked').attr('id');
        var age = $('#age').val();

        if(party == '' && halqa_type == '' && provincial_halqa == '')
        {
            if(gender != '' && age != '')
            {
                if(typeof $.cookie('gender_age') == 'undefined')
                {
                    // End of the document reached?
                    if ($(document).height() - win.height() == win.scrollTop()) {
                        searchPolitciansByKey('scroll');
                    }
                }
            }
            else if(gender != '')
            {
                if(typeof $.cookie('gender') == 'undefined')
                {
                    // End of the document reached?
                    if ($(document).height() - win.height() == win.scrollTop()) {
                        searchPolitciansByKey('scroll');
                    }
                }
            }
            else
            {
                if(age != '')
                {
                    if(typeof $.cookie('age') == 'undefined')
                    {
                        // End of the document reached?
                        if ($(document).height() - win.height() == win.scrollTop()) {
                            searchPolitciansByKey('scroll');
                        }
                    }
                }   
            }
        }
    });    

/*    
     if()
     {
        var large_loading = $('#large_loading');
        var party = $('#by_party').val() == '' ? '' : $('#by_party').val();
        var halqa_type = $('#halqas').val() == '' ? '' : $('#halqas').val();
        var province = $('#province').val() == '' ? '' : $('#province').val();
        var age = $('#age').val() == '' ? '' : $('#age').val();
        var city = $('#city').val() == '' ? '' : $('#city').val();
        var gender = $('input[name=gender]:checked').attr('id');
     }
     */

    // $('.my-panel').on('mouseover', function(){    
    //     var _this = $(this);
    //     var _this_children = _this.children();
    //     _this.addClass('panel-color');
    //     _this.addClass('my-panel-hover');
    //     // _this_children('my-panel');

    // });

    // $('.my-panel a').on('mouseover', function(){
    //     $(this).addClass('my-panel-hover');
    //     $(this).addClass('panel-color');
    // });

    /**
     * Politcian Filter Autocomplete
     */

    $(document).on('click', '#filter-results', function(){
        if($('#filter-details').length == 0) {
            showFilters();
        }
        else
        {
            $('#filter-details').slideUp(500, function() {
                $(this).remove();
            });
        }
    });

    $(document).on('change', '#halqas', function(){
        $('#provincial_halqa option:selected').removeAttr('selected');
        $("select").selectpicker('refresh');        
        searchPolitciansByKey();
    });

    $(document).on('change', '#provincial_halqa', function(){
        $('#halqas option:selected').removeAttr('selected');
        $("select").selectpicker('refresh');
        searchPolitciansByKey();
    });

    $(document).on('click', '#filter_reset', function(){
        $('option:selected').removeAttr('selected');
        $('#provincial_halqa').empty();
        $("select").selectpicker('refresh');
    });


    function substr_replace (str, replace, start, length) {

      if (start < 0) {
        // start position in str
        start = start + str.length
      }
      length = length !== undefined ? length : str.length
      if (length < 0) {
        length = length + str.length - start
      }

      return [
        str.slice(0, start),
        replace.substr(0, length),
        replace.slice(length),
        str.slice(start + length)
      ].join('')
    }

    $(document).on('change', '#by_party', function(){
        searchPolitciansByKey();
    });
    $(document).on('change', '#age', function(){
        searchPolitciansByKey();
    });
   
    if(path_parts[3] == 'statistics')
    {
        showTopTenLikedPoliticians();
    }

    $(document).on('click', 'input[name=statistics_filters]', function(){
        // searchPolitciansByKey();
        var _this = $(this);
        var id = _this.attr('id');
        switch (id) 
        {
            case  'most_liked_politicians':
                showTopTenLikedPoliticians();
                break;
            case 'most_disliked_politicians':
                showTopTenDislikedPoliticians();
                break;
            case 'most_liked_political_party':
                showTopTenLikedPoliticalParties();
                break;
            case 'most_disliked_political_party':
                showTopTenDislikedPoliticalParties();
                break;
        }
    });

    $(document).on('click', 'input[name=gender]', function(){
        searchPolitciansByKey();
    });

    /* End Politcian Filter Autocomplete */

    $('[data-toggle="tooltip"]').tooltip({template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'}); 

    // adds active class to selected navbar li element    
    
    if(path_parts[3] == 'politician')
    {
        $('#navbar-items li[id="politician-nav-item"]').addClass('active');
    }
    if(path_parts[3] == 'political_party')
    {
        $('#navbar-items li[id="political_party-nav-item"]').addClass('active');
    }
    if(path_parts[3] == 'columnist' || path_parts[3] == 'column')
    {
        $('#navbar-items li[id="columnist-nav-item"]').addClass('active');
    }


    $('.my-panel').on('click', function(){
        var url = base_url;
        var text = $(this).children().text();
        if(text == 'Politicians')
        {
            url = url + 'politician';
        }
        else if(text == 'Politicial Parties')
        {
            url = url + 'political_party';
        }
        else if(text == 'Columnists')
        {
            url = url + 'columnist';
        }
        else
        {
            if(text == 'Statistics')
            {
                url = url + 'statistics';
            }
        }
        window.location.href = url;
    });

    $("time.timeago").timeago();

    var isLoggedIn = $.cookie('isLoggedIn');
    var comment = $.cookie('comment');
    if(comment !== undefined)
    {
        $('#textarea-post').val(comment);
    }
    if(isLoggedIn == 'true')
    {
        var userData = $.cookie('user');
        var userLen = $.cookie('user').length;
        var user = userData.split(',');
        var main_entity_id = $('#mainEntityId').val();
        var main_entity_name =  $('#mainEntityName').val();
        if(path_parts[3] == 'columnist')
        {
            var column_id =  $('#ColumnId').val();
        }
        else
        {
            var featured_post_story_id =  $('#featuredPostStoryId').val();
        }
        var entity = main_entity_id + '_' + main_entity_name;

        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'columnist' || path_parts[3] == 'column')
        {
            getLikesDislikes(main_entity_id, user[0]);
        }
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            getPostLikesDislikes(user[0]);
            getCommentLikes(user[0]);
        }
        if(path_parts[3] == 'column')
        {
            getColumnLikesDislikes(user[0]);
            getColumnCommentLikes(user[0]);
        }
        // to get post likes or dislike by a specific user
    }

    if(isLoggedIn == 'true' && userLen != 0)
    {
        var name = user[1];
        
        $('#navbar-items').append('<li class="upper-case dropdown text-center">'+
         '<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">' + 
            name +    '<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>' + 
            '<ul class="dropdown-menu dropdown-user">' + 
                '<li><a href="' + base_url + 'user/setting' + '"><i class="fa fa-user fa-fw"></i> Settings</a></li>' + 
                '<li><a href="' + base_url + 'login/logout_lookup' + '"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>' + 
            '</ul>' +
            '</a>' + 
            '</li>');
        $('#nav-login').remove();
        $('#nav-signup').remove();
    }

    $(".Fr-star.userChoose").Fr_star(function(rating){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            $.post(base_url + "star/add_rating_lookup", {'user_id' : user[0], 'post_id' : featured_post_story_id, 'rating': rating}, function(data){
                $('#rating').html('');
                $('#rating').html(data);
            });
            // alert("Rated" + rating + " !!");
            // submitRating(featured_post_story_id, , rating);
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal();      
        }
    });


    /**
    * [description: Triggers when '#edit_user_form' is submitted. Prevents normal form submission
    * and calls createOrUpdateByAjax(); for ajax form submission]
    */
    $('#edit_user_form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('edit_user_form');
    });

    /**
     * NavBar Autocomplete
     */

    var $search_term = $('#search_term');
    var xhr = null;
    $search_term.autocomplete({
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
   search: function(event, ui) { 
       $('.spinner').show();
   },
   response: function(event, ui) {
       $('.spinner').hide();
   },
    source: function(request, response)
    {
                if( xhr != null ) {
                xhr.abort();
                xhr = null;
        }
        // $('#search_term').removeClass('ui-autocomplete-loading'); 
        var url = base_url_admin + 'autocomplete/get_entity_by_name_lookup/';
          $.post(url, {search_term:request.term}, function(search_term){
            response($.map(search_term, function(entity) {
                return {
                    value:  ucwords(entity.name),
                    icon: entity.thumbnail,
                    selectedId: entity.id,          
                    partyId: entity.political_party_id           
                };
            }).slice(0, 12));

          }, "json");
    },

    minLength: 2,
    autofocus: true,

    });
    
    $search_term.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
    
    var $li = $('<li class="list_item_container">'),
        $img = $('<img class="img">');

    if(item.partyId !== undefined)
    {
        var folder = item.partyId.length == 0 ? 'political_parties' : 'politicians';
        var controller = folder == 'political_parties' ? 'political_party' : 'politician';
    }
    else
    {
        folder = 'political_parties';
    }

    $img.attr({
      src: 'http://localhost/pak_democrates/assets/admin/images/'+ folder +'/' + item.icon,
      alt: item.value,
      style: 'padding: 5px;'
    });

    $li.attr('data-value', item.value);
    $li.append('<a href="' + base_url  + controller + '/' + 'get_' + controller + '_by_id/' + item.selectedId + '" class="autocomplete">');
    $li.find('a').append($img).append(item.value);    

    return $li.appendTo(ul);
  };
   
    /* NavBar Autocomplete nd */


    $('#search_btn').on('click', function() {

        var search_term = $('#search_term').val();
        var search_term_length = search_term.length;

        if(search_term_length !== 0)
        { 
            searchEntity(search_term); 
        }
    });

    /**
     * Politcian Filter Autocomplete
     */

/*        $(document).on('change', '#gender', function(){
            var id = $(this).val();
            var halqa_type = $('input[name=halqas]:checked').attr('id');
            if(halqa_type !== undefined)
            {
                searchPolitciansByParty(id, halqa_type);
            }
            else
            {
                searchPolitciansByParty(id);
            }

        });*/
   
    /* End Politcian Filter Autocomplete */

    var xhr = null;

    function searchEntity(search_term)
    {
        if( xhr != null ) 
        {
            xhr.abort();
            xhr = null;
        }
        // var func = path_parts[3] == 'political_party' ? 'political_parties' : path_parts[3] + 's';
        var loading = $("#loading");
        var search_btn = $("#search_btn");
            
/*        $(document).ajaxStart(function () {
            search_btn.children().remove();
            loading.clone().appendTo('#search_btn').show();
        });

        $(document).ajaxStop(function () {
            search_btn.children().remove();
            search_btn.append('<i class="fa fa-search"></i>');
            loading.hide();
        });*/

        xhr = $.ajax({
            url: base_url_admin + 'autocomplete/display_entities',
            method: 'post',
            data: {'search_term': search_term},
            beforeSend: function() {
                search_btn.children().remove();
                loading.clone().appendTo('#search_btn').show();
            },
            success: function(data)
            {
                search_btn.children().remove();
                search_btn.append('<i class="fa fa-search"></i>');
                loading.hide();
                $('#entity').remove();
                $('#search_term').val('');
                jQuery('#search_results').html(data);
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    function searchPolitciansByParty(id, halqa_type)
    {
        halqa_type = halqa_type || '';
        $.ajax({

            url: base_url_admin + 'autocomplete/display_politicians_by_party',
            method: 'post',
            data: {'id': id, 'halqa_type': halqa_type},

            success: function(data)
            {/*
                search_btn.children().remove();
                search_btn.append('<i class="fa fa-search"></i>');
                loading.hide();*/
        
                if($('#filtered_results').length > 0)
                {
                    $('#filtered_results').remove();
                }

                jQuery('#first-box').hide().append(data).fadeIn('slow');
                jQuery('#first-box').siblings().remove();
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    var pol_xhr = null;

    function searchPolitciansByKey(scroll)
    {
        scroll = scroll || '';
        $("#politician_filter_form :input").attr("disabled", true);
        $("#search_btn").attr("disabled", true);
        $('#filter-results').attr("disabled", true); 
        var large_loading = $('#large_loading');
        var party = $('#by_party').val() == '' ? '' : $('#by_party').val();
        var halqa_type = $('#halqas').val() == '' ? '' : $('#halqas').val();
        var province = $('#province').val() == '' ? '' : $('#province').val();
        var age = $('#age').val() == '' ? '' : $('#age').val();
        var city = $('#city').val() == '' ? '' : $('#city').val();
        var provincial_halqa = $('#provincial_halqa').val() == '' ? '' : $('#provincial_halqa').val();
        var gender = $('input[name=gender]:checked').attr('id');
        
        if( pol_xhr != null ) {
            pol_xhr.abort();
            pol_xhr = null;
        }

        pol_xhr = $.ajax({

            url: base_url_admin + 'autocomplete/display_politician_results_lookup',
            method: 'post',
            data: {'scroll': scroll, 'provincial_halqa': provincial_halqa, 'halqa_type': halqa_type, 'party': party, 'province': province, 'age': age, 'city': city, 'gender': gender},
            cache: false,
            beforeSend: function() {
                var clone = large_loading.clone();
                if(scroll == '')
                {
                    if($('#filtered_results').length > 0)
                    {
                        $('#filtered_results').remove();
                    }
                    clone.appendTo('#filter-details').show();
                }
                else
                {
                    if(large_loading.length > 0)
                    {
                        clone.appendTo('.clearfix:last').show();
                    }

                    // lock scroll position, but retain settings for later
                    var scrollPosition = [
                    self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
                    self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
                    ];
                    var html = jQuery('html'); // it would make more sense to apply this to body, but IE7 won't have that
                    html.data('scroll-position', scrollPosition);
                    html.data('previous-overflow', html.css('overflow'));
                    html.css('overflow', 'hidden');
                    window.scrollTo(scrollPosition[0], scrollPosition[1]);
                }
                
            }, 
            success: function(data)
            {
                $("#politician_filter_form :input").attr("disabled", false);
                $("#search_btn").attr("disabled", false);
                $('#filter-results').attr("disabled", false);

                if(scroll == '')
                {
                    $('#first-box').siblings().remove();
                    $('#politician_filter_form').next().remove();
                    $('#first-box').hide().append(data).fadeIn();
                }
                else
                {
                    $('#filtered_results').find('#large_loading').first().remove();
                    $('#filtered_results').hide().append(data).fadeIn();
                    
                    // un-lock scroll position
                    var html = jQuery('html');
                    var scrollPosition = html.data('scroll-position');
                    html.css('overflow', html.data('previous-overflow'));
                    window.scrollTo(scrollPosition[0], scrollPosition[1])
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    function showFilters()
    {   
        $.ajax({

            url: base_url_admin + 'autocomplete/display_filters',
            method: 'post',
            success: function(data)
            {
                jQuery('#first-box').hide().append(data).slideDown(500);
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    function showTopTenLikedPoliticians()
    {   
        if($('.canvasjs-chart-container').length !== 'undefined')
        {
            $('.canvasjs-chart-container').remove();
        }

        $('#chartContainer').html('');
        var large_loading = $('#large_loading');
        
        $.ajax({

            url: base_url + 'statistics/get_top_ten_liked_politicians_lookup',
            method: 'post',
            cache: false,
            success: function(data)
            {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                var data = jQuery.parseJSON(data);
                data_result = data.result;
                if(data_result !== undefined)
                {
                    var data_result_length = data_result.length;
                    var options_object = [];
                    var j = 0; // for x axis in graph
                    $.each(data_result, function(index, element) {
                        var acronym = '';
                        var political_party = element.political_party_id.name;
                        var keywords = political_party.split(" ");
                        
                        var count = keywords.length;
                        

                        var k;
                        if (political_party.indexOf('(') > 0) 
                        {
                          political_party = political_party.split(political_party.replace(/\)+$/,''));
                          political_party = political_party[0] + '(' + (political_party[1].toUpperCase()) + ')';
                        }

                        if (political_party.indexOf('-') > 0) 
                        {
                            var substr_count = political_party.split('-').length - 1;
                            if(substr_count > 1)
                            {
                              var pos1 = political_party.indexOf('-');
                              var pos2 = political_party.indexOf('-');
                              var length = pos2 - pos1 + 1;
                              political_party = substr_replace(political_party, ' ', pos1, length);
                            }
                            else
                            {
                              // political_party = explode('(', rtrim(political_party, ')'));
                              // political_party = political_party[0] . '(' . strtoupper(political_party[1]) . ')';
                            }
                        }
                        for (i = 0; i < count; i++) 
                        { 
                            var k = keywords[i];
                            if (k.indexOf('(') > 0) 
                            {
                                acronym += k;
                            }
                            else if (k.indexOf('-') > 1) 
                            {
                                acronym += k.charAt(0);
                                last_index = k.lastIndexOf('-') + 1;
                                acronym += k.charAt(last_index);
                            }
                            else if (k.indexOf('-') == 1) 
                            {
                                acronym += k.charAt(0);
                            }
                            else
                            {
                                acronym += k.charAt(0);
                            }
                        }

                        if(acronym == 'i')
                        {
                            acronym = 'Independent';
                        }
                           options_object.push({
                                x: j,
                                label: ucwords(element.name) + ' ( ' + acronym.toUpperCase() + ')',
                                y: Number(element.likes),
                                name: "http://localhost/pak_democrates/f/politician/get_politician_by_id/" + element.id
                            });
                        j++;
                    });

                    var options = {
                        title: {
                            text: "Top " + data_result_length + " Liked Politcians"
                        },
                        animationEnabled: true,
                            axisY: {
                          title: "Likes"
                        },
                        axisX: {
                          title: "Politcians"
                        },
                        data: [
                        {
                            type: "column", //change it to line, area, bar, pie, etc
                            dataPoints: options_object,
                            toolTipContent: "<a href = {name}> {label}</a><hr/>Likes: {y}",
                        }
                        ]
                    };

                    $("#chartContainer").CanvasJSChart(options);
                }
                else
                {
                    $("#chartContainer").html(ucwords(data.no_result));   
                }
                
                $('input[name=statistics_filters]').attr("disabled",false);
            },
            beforeSend: function() {
                $('input[name=statistics_filters]').attr("disabled",true);
                var clone = large_loading.clone();
                clone.appendTo('#chartContainer').show();
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    function showTopTenDislikedPoliticians()
    {  
        if($('.canvasjs-chart-container').length !== 'undefined')
        {
            $('.canvasjs-chart-container').remove();
        }

        $('#chartContainer').html('');
        
        var large_loading = $('#large_loading');
        
        $.ajax({

            url: base_url + 'statistics/get_top_ten_disliked_politicians_lookup',
            method: 'post',
            cache: false,
            success: function(data)
            {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                var data = jQuery.parseJSON(data);
                data_result = data.result;
                if(data_result !== undefined)
                {
                    var data_result_length = data_result.length;
                    var options_object = [];
                    $.each(data_result, function(index, element) {
                           options_object.push({
                            label: ucwords(element.name) + ' (' + ucwords(element.political_party_id.name) + ')',
                            y: Number(element.dislikes)
                        });
                    });

                    var options = {
                        title: {
                            text: "Top " + data_result_length + " Disiked Politcians"
                        },
                        animationEnabled: true,
                        data: [
                        {
                            type: "column", //change it to line, area, bar, pie, etc
                            dataPoints: options_object
                        }
                        ]
                    };

                    $("#chartContainer").CanvasJSChart(options);
                }
                else
                {
                    $("#chartContainer").html(ucwords(data.no_result));   
                }
                
                $('input[name=statistics_filters]').attr("disabled",false);
            },            
            beforeSend: function() {
                $('input[name=statistics_filters]').attr("disabled",true);
                var clone = large_loading.clone();
                clone.appendTo('#chartContainer').show();
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    function showTopTenLikedPoliticalParties()
    {   
        if($('.canvasjs-chart-container').length !== 'undefined')
        {
            $('.canvasjs-chart-container').remove();
        }

        $('#chartContainer').html('');

        var large_loading = $('#large_loading');
        
        $.ajax({

            url: base_url + 'statistics/get_top_ten_liked_political_parties_lookup',
            method: 'post',
            cache: false,
            success: function(data)
            {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                var data = jQuery.parseJSON(data);
                data_result = data.result;
                if(data_result !== undefined)
                { 
                    var data_result_length = data_result.length;
                    var options_object = [];
                    $.each(data_result, function(index, element) {
                           options_object.push({
                            label: ucwords(element.name),
                            y: Number(element.likes)
                        });
                    });

                    var options = {
                        title: {
                            text: "Top " + data_result_length + " Liked PoliticalParties"
                        },
                        animationEnabled: true,
                        data: [
                        {   
                            type: "column", //change it to line, area, bar, pie, etc
                            dataPoints: options_object
                        }
                        ]   
                    };

                    $("#chartContainer").CanvasJSChart(options);
                }
                else
                {
                    $("#chartContainer").html(ucwords(data.no_result));   
                }
                
                $('input[name=statistics_filters]').attr("disabled",false);
            },
            beforeSend: function() {
                $('input[name=statistics_filters]').attr("disabled",true);
                var clone = large_loading.clone();
                clone.appendTo('#chartContainer').show();
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    function showTopTenDislikedPoliticalParties()
    {   
        if($('.canvasjs-chart-container').length !== 'undefined')
        {
            $('.canvasjs-chart-container').remove();
        }

        $('#chartContainer').html('');

        var large_loading = $('#large_loading');
        
        $.ajax({

            url: base_url + 'statistics/get_top_ten_disliked_political_parties_lookup',
            method: 'post',
            cache: false,
            success: function(data)
            {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                var data = jQuery.parseJSON(data);
                data_result = data.result;
                if(data_result !== undefined)
                { 
                    var data_result_length = data_result.length;
                    var options_object = [];
                    $.each(data_result, function(index, element) {
                           options_object.push({
                            label: ucwords(element.name),
                            y: Number(element.dislikes)
                        });
                    });

                    var options = {
                        title: {
                            text: "Top " + data_result_length + " Disiked PoliticalParties"
                        },
                        animationEnabled: true,
                        data: [
                        {
                            type: "column", //change it to line, area, bar, pie, etc
                            dataPoints: options_object
                        }
                        ]
                    };

                    $("#chartContainer").CanvasJSChart(options);
                }
                else
                {
                    $("#chartContainer").html(ucwords(data.no_result));   
                }

                $('input[name=statistics_filters]').attr("disabled",false);
            },
            beforeSend: function() {
                $('input[name=statistics_filters]').attr("disabled",true);
                var clone = large_loading.clone();
                clone.appendTo('#chartContainer').show();
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }


    function showFilterResults(halqa_type, id)
    {   
        id = id || '';
        $.ajax({

            url: base_url_admin + 'autocomplete/display_filter_results_lookup',
            method: 'post',
            data: {'halqa_type': halqa_type, 'id': id},
            success: function(data)
            {
                if($('#filtered_results').length > 0)
                {
                    $('#filtered_results').remove();
                }

                jQuery('#first-box').hide().append(data).fadeIn('slow');
                jQuery('#first-box').siblings().remove();
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });
    }

    $('#textarea-post').on( "keypress", function( event ) 
    {
        if(event.keyCode == 13 && !event.shiftKey)
        {
            var textAreaPostComment = $.trim($('#textarea-post').val());
            var textAreaPostCommentLength = textAreaPostComment.length;
            if(textAreaPostCommentLength > 0 && user !== undefined)
            {
                if(path_parts[3] == 'column')
                {
                    postAColumnComment(textAreaPostComment, column_id, user[0]);
                }
                else
                {
                    postAComment(textAreaPostComment, featured_post_story_id, user[0]);
                }
            }
            else if(textAreaPostCommentLength == 0)
            {

            }
            else
            {
                $('#login-form')[0].reset();
                openLoginModal();  
            }

            event.preventDefault();
        }
    });   

    $(document).on( "keypress", '.textarea-reply', function( event ) 
    {
        if(event.keyCode == 13 && !event.shiftKey)
        {   
            var _this = $(this);
            var textAreaPostReply = $.trim(_this.val());
            var id = _this.attr('id');
            var textAreaPostReplyLength = textAreaPostReply.length;
            id = id.split('-');
            id = id.pop();
            if(textAreaPostReplyLength > 0 && user !== undefined)
            {
                if(path_parts[3] == 'column')
                {
                    postAColumnReply(textAreaPostReply, id, user[0], _this);
                }
                else
                {
                    postAReply(textAreaPostReply, id, user[0], _this);
                }
            }
            else if(textAreaPostReplyLength == 0)
            {

            }
            else
            {
                $('#login-form')[0].reset();
                openLoginModal();  
            }

            event.preventDefault();
        }
    });

    $('#vote-now').on('click', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var user = userData.split(',');
            var id = user[0];
            hasAlreadyVoted(id, function(output){
                if(output == 'TRUE')
                {   
                    hasAlreadyVotedThisEntity(main_entity_id, id, main_entity_name, function(output){
                        if(output == 'TRUE')
                        {
                            $('.noty_message').remove();
                            noty({text:  'You have already voted this ' + ucfirst(main_entity_name) + '!', 
                                animation: {
                                    open: {height: 'toggle'}, // jQuery animate function property object
                                    close: {height: 'toggle'}, // jQuery animate function property object
                                    easing: 'swing', // easing
                                },
                                type: 'error', timeout: 5000}
                            );
                        }
                        else
                        {
                            voteThisEntity(main_entity_id, id, function(output){
                                if(output == 'TRUE')
                                {
                                    noty({text: 'Your vote has been casted to this ' + ucfirst(main_entity_name) + '!', type: 'success', timeout: 5000});
                                }   
                            });
                        }
                    });

                    // noty({text: 'You have already voted this!' + main_entity_name, type: 'error ', timeout: 5000});
                }
                else
                {
                    getModal(base_url + path_parts[3] + '/get_modal/' + id)
                }
            });             
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal();  
        }
    });    

    $(document).on('click', '#vote-btn', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var formId = $('#vote_now_form').attr('id');
            var action = $('#' + formId).attr('action');
            var user = userData.split(',');
            var id = user[0];
            voteNow(action, id);
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal();  
        }
    });

    $(document).on('change', '#provincial_assembly', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var provincial_assembly = $('#provincial_assembly').val();
            getProvincialHalqas(provincial_assembly);
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal();  
        }
    });

    // And to capitalize first letter

    function ucfirst(str,force){
          str=force ? str.toLowerCase() : str;
          return str.replace(/(\b)([a-zA-Z])/,
                   function(firstLetter){
                      return   firstLetter.toUpperCase();
                   });
     }

    // And to capitalize all words

    function ucwords(str,force){
      str=force ? str.toLowerCase() : str;  
      return str.replace(/(\b)([a-zA-Z])/g,
               function(firstLetter){
                  return   firstLetter.toUpperCase();
               });
    }


    function postAComment(comment, story_id, user_id)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            var data = {'comment': comment, 'entity': main_entity_name, 'entity_id': main_entity_id};
            jQuery.ajax({
                url: base_url + 'comment/post_a_comment_lookup/' + story_id + '/' + user_id,
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#comment-box').before(data);
                    $("time.timeago").timeago();
                    $('#textarea-post').val('');
                    $.removeCookie("comment", { path: '/' });
                    $('#textarea-post').css('height', '34px');
                    var post_comments_count_db = $('#post_comments_count_db').val();
                    $('#post_comments_count_db').remove();
                    $('#post-comment-count').text('');
                    $('#post-comment-count').text(post_comments_count_db);
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }
    
    function postAColumnComment(comment, column_id, user_id)
    {
        if(path_parts[3] == 'column')
        {
            var column_id = $('#ColumnId').val();
            var data = {'comment': comment};
            jQuery.ajax({
                url: base_url + 'comment/post_a_column_comment_lookup/' + column_id + '/' + user_id,
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#comment-box').before(data);
                    $("time.timeago").timeago();
                    $('#textarea-post').val('');
                    $.removeCookie("comment", { path: '/' });
                    $('#textarea-post').css('height', '34px');
                    var post_comments_count_db = $('#post_comments_count_db').val();
                    $('#post_comments_count_db').remove();
                    $('#post-comment-count').text('');
                    $('#post-comment-count').text(post_comments_count_db);
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function getCommentBox(user_id, last_row)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' ||  path_parts[3] == 'column' ||  path_parts[3] == 'home')
        {
            var data = {'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/get_comment_box/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    if($('#comment-box').length >= 1)
                    {
                        $.each(data, function() {
                            $('#comment-box').remove();
                        });
                    }

                    $(last_row).after(data);
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function getReplyBox(user_id, last_row, last_comment_id)
    {
        last_comment_id = last_comment_id || '';
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'column' || path_parts[3] == 'home')
        {
            var data = {'user_id': user_id, 'comment_id': last_comment_id};
            jQuery.ajax({
                url: base_url + 'comment/get_reply_box/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $(last_row).after(data);
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function editAColumnCommentNoChange(comment, comment_id, user_id)
    {
        if(path_parts[3] == 'column')
        {
            var data = {'comment': comment, 'comment_id': comment_id, 'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/no_change_column_comment_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#textarea-edit-post').closest('.comment').removeAttr('style');
                    $('#textarea-edit-post').closest('.comment').addClass('pbot-8');
                    $("time.timeago").timeago();
                    $('#textarea-edit-post').before(data);
                    $('#textarea-edit-post').parent().next().remove();
                    $('#textarea-edit-post').remove();
                    
                    var last_row = $('#last_row').val();
                    last_row = '#comment_id_' + last_row;
                    $('#last_row').remove();      
                    $('#comment-box').remove();
                    var count = $('#comment-box').length;   
                    if(count == 0)
                    {
                        getCommentBox(user_id, last_row);
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function editACommentNoChange(comment, comment_id, user_id)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            var data = {'comment': comment, 'comment_id': comment_id, 'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/no_change_comment_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#textarea-edit-post').closest('.comment').removeAttr('style');
                    $('#textarea-edit-post').closest('.comment').addClass('pbot-8');
                    $("time.timeago").timeago();
                    $('#textarea-edit-post').before(data);
                    $('#textarea-edit-post').parent().next().remove();
                    $('#textarea-edit-post').remove();
                    var last_row = $('#last_row').val();
                    last_row = '#comment_id_' + last_row;
                    $('#last_row').remove();      
                    $('#comment-box').remove();
                    var count = $('#comment-box').length;                   
                    if(count == 0)
                    {
                        getCommentBox(user_id, last_row);
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function editAReplyNoChange(reply, reply_id, user_id)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            var data = {'reply': reply, 'reply_id': reply_id, 'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/no_change_reply_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#textarea-edit-reply').closest('.comment').removeAttr('style');
                    $('#textarea-edit-reply').closest('.comment').addClass('pbot-8');
                    $("time.timeago").timeago();
                    $('#textarea-edit-reply').before(data);
                    $('#textarea-edit-reply').parent().next().remove();
                    $('#textarea-edit-reply').remove();
                    var last_row = $('#last_row').val();
                    var last_comment_id = $('#last_comment_id').val();
                    $('#last_row').remove();
                    $('#last_comment_id').remove();
                    last_row = '#reply-' + last_row;
                    $.removeCookie("reply_content", { path: '/' });
                    getReplyBox(user_id, last_row, last_comment_id);
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function editAComment(comment, comment_id, user_id)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            
            var data = {'comment': comment, 'comment_id': comment_id, 'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/edit_a_comment_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#textarea-edit-post').closest('.comment').removeAttr('style');
                    $('#textarea-edit-post').closest('.comment').addClass('pbot-8');
                    
                    $('#textarea-edit-post').before(data);
                    $('#textarea-edit-post').parent().next().remove();
                    $('#textarea-edit-post').remove();
                    
                    var last_row = '#comment_id_' + $.cookie('last_row');
                    $.removeCookie("last_row", { path: '/' });

                    $('#comment-box').remove();
                    var count = $('#comment-box').length;
                    
                    if(count == 0)
                    {
                        getCommentBox(user_id, last_row);
                    }    
                },

                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function editAReply(reply, reply_id, user_id)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            var data = {'reply': reply, 'reply_id': reply_id, 'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/edit_a_reply_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#textarea-edit-reply').closest('.comment').removeAttr('style');
                    $('#textarea-edit-reply').closest('.comment').addClass('pbot-8');
                    $("time.timeago").timeago();
                    
                    $('#textarea-edit-reply').before(data);
                    $('#textarea-edit-reply').parent().next().remove();
                    $('#textarea-edit-reply').remove();
                    
                    var last_row = $('#last_row').val();
                    last_row = '#reply-' + last_row;
                    $('#last_row').remove();
                    $('#textarea-reply').remove();

                    var count = $('#textarea-reply').length;
                    if(count == 0)
                    {
                        getReplyBox(user_id, last_row);
                    }    
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }


    function editAColumnComment(comment, comment_id, user_id)
    {
        if(path_parts[3] == 'column')
        {
            var data = {'comment': comment, 'comment_id': comment_id, 'user_id': user_id};
            jQuery.ajax({
                url: base_url + 'comment/edit_a_column_comment_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#textarea-edit-post').closest('.comment').removeAttr('style');
                    $('#textarea-edit-post').closest('.comment').addClass('pbot-8');
                    
                    $('#textarea-edit-post').before(data);
                    $('#textarea-edit-post').parent().next().remove();
                    $('#textarea-edit-post').remove();
                    
                    var last_row = '#comment_id_' + $.cookie('last_row');
                    $.removeCookie("last_row", { path: '/' });
                    $('#comment-box').remove();
                    var count = $('#comment-box').length;   

                    if(count == 0)
                    {
                        getCommentBox(user_id, last_row);
                    }                },

                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function updateAComment(comment, comment_id, user_id, _this)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party')
        {
            var data = {'comment': comment, 'comment_id': comment_id};
            jQuery.ajax({
                url: base_url + 'comment/update_a_comment_lookup/' + user_id,
                method: 'post',
                data: data,
                success: function(data)
                {
                    // $('#comment-box').before(data);
                    // $("time.timeago").timeago();
                    // $('#textarea-post').val('');
                    // $.removeCookie("comment", { path: '/' });
                    // $('#textarea-post').css('height', '34px');
                    // var post_comments_count_db = $('#post_comments_count_db').val();
                    // $('#post_comments_count_db').remove();
                    // $('#post-comment-count').text('');
                    // $('#post-comment-count').text(post_comments_count_db);
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function postAReply(reply, comment_id, user_id, _this)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'home')
        {
            var data = {'reply': reply};
            jQuery.ajax({
                url: base_url + 'comment/post_a_reply_lookup/' + comment_id + '/' + user_id + '/' + featured_post_story_id,
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#reply-box-' + comment_id).before(data);
                    $("time.timeago").timeago();
                    $(_this).val('');
                    $(_this).css('height', '34px');
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function postAColumnReply(reply, comment_id, user_id, _this)
    {
        if(path_parts[3] == 'column')
        {
            var data = {'reply': reply};
            jQuery.ajax({
                url: base_url + 'comment/post_a_column_reply_lookup/' + comment_id + '/' + user_id + '/' + comment_id,
                method: 'post',
                data: data,
                success: function(data)
                {
                    $('#reply-box-' + comment_id).before(data);
                    $("time.timeago").timeago();
                    $(_this).val('');
                    $(_this).css('height', '34px');
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function showReplyBox(comment_id, user_id, _this)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'column' || path_parts[3] == 'home')
        {
            var data = {'comment_id': comment_id};
            jQuery.ajax({
                url: base_url + 'comment/insert_reply_box_lookup/',
                method: 'post',
                data: data,
                success: function(data)
                {
                    $(_this).closest('.comm-actions').after(data);
                    // $("time.timeago").timeago();
                    // $('#textarea-post').val('');
                    // $.removeCookie("comment");
                    // $('#textarea-post').css('height', '34px');
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function getProvincialHalqas(provincial_assembly)
    {
        if(path_parts[3] == 'politician' || path_parts[3] == 'political_party' || path_parts[3] == 'column' || path_parts[3] == 'columnist'
            || path_parts[3] == 'statistics' )
        {
           jQuery.ajax({
                url: base_url_admin + 'halqa' + '/get_halqas_by_type_lookup/' + provincial_assembly,
                method: 'post',
                success: function(data)
                {
                    if(path_parts[3] == 'politician')
                    {
                        $('#provincial_halqa').html(data);
                        $('#provincial_halqa').selectpicker('refresh');
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function hasAlreadyVoted(id, handleData)
    {
        if(path_parts[3] == 'politician')
        {
           jQuery.ajax({
                url: base_url + 'user' + '/has_already_voted_lookup/' + id,
                method: 'post',
                success: function(data)
                {
                    // Takes a well-formed JSON string and returns the resulting JavaScript value.
                    data = jQuery.parseJSON(data);

                    if(path_parts[3] == 'politician')
                    {
                        if(data.success !== undefined) 
                        {
                            handleData(data.success);
                        }
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function hasAlreadyVotedThisEntity(id, user_id, main_entity_name, handleData)
    {
        if(path_parts[3] == 'politician')
        {
           jQuery.ajax({
                url: base_url + 'user/has_already_voted_this_entity_lookup/' + id + '/' + user_id,
                method: 'post',
                success: function(data)
                {
                    // Takes a well-formed JSON string and returns the resulting JavaScript value.
                    data = jQuery.parseJSON(data);

                    if(path_parts[3] == 'politician')
                    {
                        if(data.success !== undefined) 
                        {
                            var national_assembly_votes;
                            var prov_assembly_votes;
                            
                            handleData(data.success);
                        }
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function voteThisEntity(id, user_id, handleData)
    {
        var main_entity_name = $('#mainEntityName').val();
        var main_entity_id = $('#mainEntityId').val();
        var data = {'main_entity_name': main_entity_name, 'main_entity_id': main_entity_id};
        if(path_parts[3] == 'politician')
        {
           jQuery.ajax({
                url: base_url + 'user/vote_this_entity_lookup/' + id + '/' + user_id,
                method: 'post',
                data: data,
                success: function(data)
                {
                    // Takes a well-formed JSON string and returns the resulting JavaScript value.
                    data = jQuery.parseJSON(data);
                    if(path_parts[3] == 'politician')
                    {
                        if(data.success !== undefined) 
                        {
                            if(data.na_halqa_plus == true)
                            {
                                var national_assembly_votes = data.national_assembly_votes > 0 ? data.national_assembly_votes : 0;
                                national_assembly_votes += ' (Votes)';
                            }
                            else
                            {
                                var national_assembly_votes = 'Not a NA Candidate';
                            }
                            
                            if(data.prov_halqa_plus == true)
                            {
                                var prov_assembly_votes = data.prov_assembly_votes > 0 ? data.prov_assembly_votes : 0;
                                prov_assembly_votes += ' (Votes)';
                            }
                            else
                            {
                                var prov_assembly_votes = 'Not a Provincial Candidate';
                            }                            

                            $('#national_assembly_votes_count').html(national_assembly_votes);
                            $('#prov_assembly_votes_count').html(prov_assembly_votes);

                            if(data.off_halqa_votes !== undefined)
                            {
                                var off_halqa_votes = data.off_halqa_votes > 0 ? data.off_halqa_votes : 0;
                                $('#off_halqa_votes_count').html(off_halqa_votes + ' (Votes)');
                            }

                            if(data.na_halqa_plus == true)
                            {
                                $('#nationalAssemblyVotes').val(data.politician_na_halqas_plus);
                            }

                            if(data.prov_halqa_plus == true)
                            {
                                $('#provAssemblyVotes').val(data.politician_prov_halqas_plus);
                            }
                            
                            handleData(data.success);
                        }
                    }
                },
                error: function()
                {
                    alert('Something went wrong!');
                }
            });    
        }
    }

    function voteNow(url, id)
    {
        var main_entity_name = $('#mainEntityName').val();
        var main_entity_id = $('#mainEntityId').val();

        var on_halqa = $('#on_halqa').val();
        var provincial_assembly = $('#provincial_assembly').val();
        var provincial_halqa = $('#provincial_halqa').val();
        var data = {'id': id, 'on_halqa': on_halqa, 'provincial_assembly': provincial_assembly, 
                    'provincial_halqa': provincial_halqa, 'main_entity_name': main_entity_name,
                    'main_entity_id': main_entity_id};
        var action_parts = url.split('/');
        // calling ajax
        jQuery.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function(data)
            {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);
                if(data.success !== undefined) 
                {
                    closeModel('modal');
                    $('body').addClass('add-scroll');
                    noty({text: 'Your vote has been successfully casted. Thanks!', type: 'success', timeout: 5000});
                    
                    if(data.na_halqa_plus == true)
                    {
                        var national_assembly_votes = data.national_assembly_votes > 0 ? data.national_assembly_votes : 0;
                        national_assembly_votes += ' (Votes)';
                    }
                    else
                    {
                        var national_assembly_votes = 'Not a NA Candidate';
                    }
                    
                    if(data.prov_halqa_plus == true)
                    {
                        var prov_assembly_votes = data.prov_assembly_votes > 0 ? data.prov_assembly_votes : 0;
                        prov_assembly_votes += ' (Votes)';
                    }
                    else
                    {
                        var prov_assembly_votes = 'Not a Provincial Candidate';
                    }                            

                    $('#national_assembly_votes_count').html(national_assembly_votes);
                    $('#prov_assembly_votes_count').html(prov_assembly_votes);

                    if(data.off_halqa_votes !== undefined)
                    {
                        var off_halqa_votes = data.off_halqa_votes > 0 ? data.off_halqa_votes : 0;
                        $('#off_halqa_votes_count').html(off_halqa_votes + ' (Votes)');
                    }

                    if(data.na_halqa_plus == true)
                    {
                        $('#nationalAssemblyVotes').val(data.politician_na_halqas_plus);
                    }

                    if(data.prov_halqa_plus == true)
                    {
                        $('#provAssemblyVotes').val(data.politician_prov_halqas_plus);
                    }
                }
                else if(data.failure !== undefined)
                {
                    noty({text: 'Something went wrong please try again!', type: 'success', timeout: 5000});
                    window.location.href = window.location;
                }
                else //show errors
                {
                    $('#modal').addClass('shake')
                    setTimeout( function(){ 
                        $('#modal').removeClass('shake'); 
                    }, 1000 ); 
                    $.each(data, function(index, error) {
                        index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                    });
                }
/*                else if(action_parts[5] == 'login')
                {
                    if(data.success !== undefined) 
                    {
                        closeModel('loginModal');

                        // console.log(data);
                        window.isLoggedIn = data.logged_in;
                        window.user = data;
                        // data.id + ',' data.first_name + ,
                        $.cookie('isLoggedIn', data.logged_in); // expires after browser is closed. to set expiry of 1 day {expires: 1}
                        var user = data.id + ', ' + data.first_name + ', ' + data.picture; 
                        $.cookie('user', user); // expires after browser is closed. to set expiry of 1 day {expires: 1}
                        window.location.href = window.location;
                    }
                    if(data.failure !== undefined) 
                    {
                        jQuery('#login-form').prepend('<div id="login-error" class="alert alert-danger text-center text-danger fade in">' +
                            data.failure + '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                    }
                    else //show errors
                    {
                        $('.modal-dialog').addClass('shake')
                        setTimeout( function(){ 
                            $('.modal-dialog').removeClass('shake'); 
                        }, 1000 ); 
                        $.each(data, function(index, error) {
                            index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                            if($('#login_password_error').length != 0)
                            {
                                $('[type=password]').val('');
                            }
                        });
                    }
                }*/
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        });   
    }

    /**
     * [Closes bootstrap alert after 5 seconds with a slideup animation]
     */
    $(".alert-dismissable").fadeTo(5000, 500).slideUp(500, function(){
       $(".alert-dismissable").alert('close');
    });

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-46172202-1', 'auto');
    ga('send', 'pageview');
    
    /**
     * [Triggers when user clicks on close or &times; (x) inside bootstrap modal.
         * Clears all the errors inside bootstrap modal and reset form value]
     */
    $('.modal').on('hidden.bs.modal', function(){
        $(document).find('div[id$="_error"]').html('');
        $('#registeration-form')[0].reset();
        $('#login-form')[0].reset();
    });

    /**
     * [Triggers when user clicks on login button on navbar and opens login modal]
     */
    $('#nav-login').on('click', function(){
        $('#login-form')[0].reset();
        openLoginModal();
    });  

    /**
     * [Triggers when user clicks on sign up button on navbar and opens sign up modal]
     */
    $('#nav-signup').on('click', function(){
        $('#registeration-form')[0].reset();
        openRegisterModal();
    });

    $('#thumbs-up').on('click', function(){

        if(isLoggedIn == 'true' && userLen != 0)
        {
            // $('#thumbs-up-icon').css('color', '#2195DE');
            // $('#thumbs-up-icon').addClass('thumbs-icon-active');
            $('#thumbs-up-icon').css('color', '');
            $('#thumbs-up-icon').removeClass('thumbs-icon-active');
            var user = userData.split(',');
            var userID = user[0];  
            submitLikeDislike(userID, 'like');
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('like');
        }
    }); 

    $('#thumbs-down').on('click', function(){

        if(isLoggedIn == 'true' && userLen != 0)
        {
            // $('#thumbs-up-icon').css('color', '#2195DE');
            // $('#thumbs-up-icon').addClass('thumbs-icon-active');
            $('#thumbs-down-icon').css('color', '');
            $('#thumbs-down-icon').removeClass('thumbs-icon-active');
            var user = userData.split(',');
            var userID = user[0];  
            submitLikeDislike(userID, 'dislike');
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('dislike');
        }
    }); 

    $('#post-like').on('click', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            // var text = $('#post-like').text();
            // text = text == 'Like' ? 'Unlike' : 'Like';
            // $('#post-like').text(text);
            var user = userData.split(',');
            var userID = user[0];
            if(path_parts[3] == 'column')
            {
                submitColumnLikeDislike(userID, 'like');
            }
            else
            {
                submitPostLikeDislike(userID, 'like');
            }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('like');
        }
    }); 
    
    $('#post-dislike').on('click', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            // var text = $('#post-like').text();
            // text = text == 'Like' ? 'Unlike' : 'Like';
            // $('#post-like').text(text);
            var user = userData.split(',');
            var userID = user[0];  
            if(path_parts[3] == 'column')
            {
                submitColumnLikeDislike(userID, 'dislike');
            }
            else
            {
                submitPostLikeDislike(userID, 'dislike');
            }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('dislike');
        }
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'li[id^="comm-delete-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var _this = $(this);
            var user = userData.split(',');
            var userID = user[0];  
            var id = $(this).attr('id');
                    id = id.split('-');
                    id = id.pop();
                if(path_parts[3] == 'column')
                {
                    deleteColumnCommentReply(id, userID, 'comment', _this);
                }
                else
                {
                    deleteCommentReply(id, userID, 'comment', _this);
                }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('delete-comment');
        }
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'li[id^="comm-edit-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var _this = $(this);
            // console.log(_this.closest('.comment-content'));
            $('#comment-box').remove();
            var comment_right = _this.closest('.comment-right');
            var parent = _this.closest('.comment');
            parent.css('padding-bottom', '0');
            var comment_content = $(comment_right).children('.comment-content').text();
            $.cookie('comment_content', comment_content);
            
            var comment_length = comment_content.length;
            $(comment_right).before('<div class="col-lg-11 post-comm-sec">' +
            '<textarea rows="1" placeholder="Write a comment..." class="textarea-post" id="textarea-edit-post">' + comment_content + '</textarea>' +
            '</div>' + '<span class="pull-left" style="font-stretch: semi-expanded font-size: 9px; color: #90949c">Press Esc to Cancel</span>');
            var textarea_post = $('#textarea-edit-post');
            textarea_post.focus();
            textarea_post[0].setSelectionRange(comment_length, comment_length);
            comment_right.remove();
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('delete-comment');
        }
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'li[id^="reply-edit-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var _this = $(this);
            var reply_id = _this.attr('id');
            $('div[id^="reply-box-"]').remove();
            var comment_right = _this.closest('.comment-right');
            var parent = _this.closest('.comment');
            parent.css('padding-bottom', '0');
            var comment_content = $(comment_right).children('.comment-content').text();
            $.cookie('reply_content', comment_content);
            $.cookie('reply_id', reply_id);
            var comment_length = comment_content.length;
            $(comment_right).before('<div class="col-lg-11 post-comm-sec">' +
            '<textarea rows="1" placeholder="Write a Reply..." class="textarea-post" id="textarea-edit-reply">' + comment_content + '</textarea>' +
            '</div>' + '<span class="pull-left" style="font-size: 9px; color: #90949c">Press Esc to Cancel</span>');
            var textarea_post = $('#textarea-edit-reply');
            textarea_post.focus();
            textarea_post[0].setSelectionRange(comment_length, comment_length);
            comment_right.remove();
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('delete-comment');
        }
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('focus', '#textarea-edit-reply', function(){
        
        // from a jQuery collection
        autosize($('textarea'));
        
        $(document).keyup(function(event) {
            var reply_id = $.cookie('reply_id');
            reply_id = reply_id.split('-');
            reply_id = reply_id[2];
            if (event.keyCode == 27) 
            {
                if(path_parts[3] == 'column')
                {
                    editAColumnReplytNoChange($.cookie('reply_content'), reply_id, user[0]);
                }
                else
                {
                    editAReplyNoChange($.cookie('reply_content'), reply_id, user[0]);
                }
            }
            if( (event.keyCode == 10 || event.keyCode == 13) && !event.shiftKey)
            {
                $(this).off('keyup');
                event.preventDefault();
                var textAreaPostComment = $.trim($('#textarea-edit-reply').val());
                var textAreaPostCommentLength = textAreaPostComment.length;
                if(textAreaPostCommentLength > 0)
                {
                    if(path_parts[3] == 'column')
                    {
                        editAColumnReply(textAreaPostComment, reply_id, user[0]);
                    }
                    else
                    {
                        editAReply(textAreaPostComment, reply_id, user[0]);
                    }
                }
            }
        });
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('focus', '#textarea-edit-post', function(){
        
        // from a jQuery collection
        
        autosize($('textarea'));
        
        $(document).keyup(function(event) {
            var comment_id = $('#textarea-edit-post').closest('.comment').attr('id');
            comment_id = comment_id.split('_');
            comment_id = comment_id[2];
            if (event.keyCode == 27) 
            {
                if(path_parts[3] == 'column')
                {
                    editAColumnCommentNoChange($.cookie('comment_content'), comment_id, user[0]);
                }
                else
                {
                    editACommentNoChange($.cookie('comment_content'), comment_id, user[0]);
                }
            }
            if( (event.keyCode == 10 || event.keyCode == 13) && !event.shiftKey)
            {
                $(this).off('keyup');
                event.preventDefault();
                var textAreaPostComment = $.trim($('#textarea-edit-post').val());
                var textAreaPostCommentLength = textAreaPostComment.length;
                if(textAreaPostCommentLength > 0)
                {
                    if(path_parts[3] == 'column')
                    {
                        editAColumnComment(textAreaPostComment, comment_id, user[0]);
                    }
                    else
                    {
                        editAComment(textAreaPostComment, comment_id, user[0]);
                    }
                }
            }
        });
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'li[id^="reply-delete-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var _this = $(this);
            var user = userData.split(',');
            var userID = user[0];  
            var id = $(this).attr('id');
                    id = id.split('-');
                    id = id.pop();
                if(path_parts[3] == 'column')
                {
                    deleteColumnCommentReply(id, userID, 'reply', _this);
                }
                else
                {
                    deleteCommentReply(id, userID, 'reply', _this);
                }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('delete-reply');
        }
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'a[id^="comm-like-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var user = userData.split(',');
            var userID = user[0];  
            var id = $(this).attr('id');
                    id = id.split('-');
                    id = id.pop();
                    if(path_parts[3] == 'column')
                    {
                        submitColumnCommentReplyLike(id, userID, 'comment');
                    }
                    else
                    {
                        submitCommentReplyLike(id, userID, 'comment');
                    }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('like');
        }
    });

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'a[id^="comm-reply-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var user = userData.split(',');
            var userID = user[0];  
            var _this = $(this);
            var id = $(this).attr('id');
                id = id.split('-');
                id = id.pop();
                if($('#textarea-reply-' + id).length <= 0)
                {
                    showReplyBox(id, user[0], _this);
                }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('like');
        }
    });  

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $(document).on('click', 'a[id^="reply-like-"]', function(){
        if(isLoggedIn == 'true' && userLen != 0)
        {
            var user = userData.split(',');
            var userID = user[0];
            var id = $(this).attr('id');
                id = id.split('-');
                id = id.pop();
                if(path_parts[3] == 'column')
                {
                    submitColumnCommentReplyLike(id, userID, 'reply');
                }
                else
                {
                    submitCommentReplyLike(id, userID, 'reply');
                }
        }
        else
        {
            $('#login-form')[0].reset();
            openLoginModal('like');
        }
    });   

    /**
     * [description: Triggers when an element id starts with rm-pol-intro- and calls 
     * the getModal function by id or number after rm-pol-intro- pattern]
     */
    $('i[id^="rm-pol-intro-"]').on('click', function(){
        var id = $(this).attr('id');
            id = id.split('-');
        var action = id[2];
            id = id.pop();
        getModal(base_url + path_parts[3] + '/get_readme_modal/' + id + '/' + action)
    }); 

    /**
     * [description: Triggers when an element id starts with rm-pol-election_history- and calls 
     * the getModal function by id or number after rm-pol-election_history- pattern]
     */
    $('i[id^="rm-pol-election_history-"]').on('click', function(){
        var id = $(this).attr('id');
            id = id.split('-');
        var action = id[2];
            id = id.pop();
        getModal(base_url + path_parts[3] + '/get_readme_modal/' + id + '/' + action)
    });

    $('#na-plus').on('click', function(){
        var $this = $(this);
        if($this.hasClass('fa-plus'))
        {
            var nationalAssemblyVotes = $('#nationalAssemblyVotes').val();
            $this.removeClass('fa-plus');
            $this.addClass('fa-minus');
            $('#no-of-votes').removeClass('remove-scroll');
            $('#no-of-votes').addClass('add-scroll');
            $('#na-plus-details').html(nationalAssemblyVotes);
        }
        else
        {
            $this.removeClass('fa-minus');
            $this.addClass('fa-plus');
            $('#no-of-votes').removeClass('add-scroll');
            $('#no-of-votes').addClass('remove-scroll');
            $('#na-plus-details').html('');
        }
    });

    $('#prov-plus').on('click', function(){
        var $this = $(this);
        if($this.hasClass('fa-plus'))
        {
            var provAssemblyVotes = $('#provAssemblyVotes').val();
            $this.removeClass('fa-plus');
            $this.addClass('fa-minus');
            $('#no-of-votes').removeClass('remove-scroll');
            $('#no-of-votes').addClass('add-scroll');

            $('#prov-plus-details').html(provAssemblyVotes);
        }
        else
        {
            $this.removeClass('fa-minus');
            $this.addClass('fa-plus');
            $('#no-of-votes').removeClass('add-scroll');
            $('#no-of-votes').addClass('remove-scroll');
            $('#prov-plus-details').html('');
        }
    });
/*
    $('#comm-reply').on('click', function(){
        $('#navbar-items').append('<li class="upper-case"><a class="text-center" href="javascript:void(0)"><i class="fa fa-user" aria-hidden="true"></i> '+        
        $('#thumbs-up-icon').css('color', 'skyblue');
    }); */


/*
    $.validator.addMethod("alpha", function(value, element) {
  
        return this.optional(element) || value == value.match(/^[a-z]+$/i);
    }, 'Please enter only characters');
*/

    /*=============================================
    =            closeModel block                 =
    =============================================*/
    
    function closeModel(id) 
    {
        jQuery('#' + id).modal('hide');
        setTimeout(function(){
            jQuery('#' + id).remove();
            jQuery('.modal-backdrop').remove();
        },500);
    }

    /*=====  End of closeModel block  ======*/
/*
    function thousandsCurrencyFormat($num) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = strchr($x_number_format, ',') ? explode(',', $x_number_format) : $x_number_format;

        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;

        $x_display = $x;
        if(is_array($x_array))
        {
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        }
        else
        {
            $x_display = '';
        }
        if($x_count_parts > 0)
        {
            $x_display .= $x_parts[$x_count_parts - 1];
        }
        return $x_display;
    }*/

    /*=============================================
    =  createOrUpdateByAjax comment block         =
    =============================================*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function createOrUpdateByAjax(formId)
    {
        var formId = $('#' + formId);
        var action = formId.attr('action'); // form action url
        var action_parts = action.split('/');
        //grab all form data  
        var formData = new FormData(formId[0]);
        
        // ajax call
        $.ajax({
            url: action,
            type: formId.attr('method'), // form method e.g POST
            data: formData, // user un-encoded data
            contentType: false, //it forces jQuery not to add a Content-Type header, otherwise, the boundary string will be missing from it
            processData: false, // to send non-processed data
            success: function (data) {

                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);
                if(action_parts[5] == 'user')
                {
                    if(action_parts[6] == 'edit_user_lookup')
                    {
                        noty({text: 'User has been successfully updated!', type: 'success', timeout: 5000});
                        setTimeout( function(){ 
                            window.location.href = base_url + 'home';
                        }, 5000 );
                    }
                    if(data.success !== undefined && action_parts[6] !== 'edit_user_lookup') 
                    {
                        jQuery('#login-form').prepend('<div id="user-registered" class="alert alert-success alert-dismissable fade in text-center text-success fade in">' +
                            'Thanks for Signing up!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                                
                        openLoginModal();
                        $('#email').val(data.success);
                    }
                    else //show errors
                    {
                        $('.modal-dialog').addClass('shake')
                        setTimeout( function(){ 
                            $('.modal-dialog').removeClass('shake'); 
                        }, 1000 ); 
                        $.each(data, function(index, error) {
                            index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                            if($('#password_error').length != 0 || $('#password_confirmation_error').length != 0 )
                            {
                                $('[type=password]').val('');
                            }
                        });
                    
                    }
                }
                else if(action_parts[5] == 'login')
                {
                    if(data.success !== undefined) 
                    {
                        closeModel('loginModal');

                        // console.log(data);
                        window.isLoggedIn = data.logged_in;
                        window.user = data;
                        // data.id + ',' data.first_name + ,
                        $.cookie('isLoggedIn', data.logged_in, {path: '/'}); // expires after browser is closed. to set expiry of 1 day {expires: 1}
                        var user = data.id + ', ' + data.first_name + ', ' + data.picture;
                        $.cookie('user', user, {path: '/'}); // expires after browser is closed. to set expiry of 1 day {expires: 1}
                        var textAreaPostComment = $.trim($('#textarea-post').val());
                        if(textAreaPostComment.length > 0)
                        {
                            $.cookie('comment', textAreaPostComment, {path: '/'}); // expires after browser is closed. to set expiry of 1 day {expires: 1}
                        }
                        
                        window.location.href = window.location;
                    }
                    if(data.failure !== undefined) 
                    {
                        $('#login-error').remove();
                        jQuery('#login-form').before('<div id="login-error" class="alert alert-danger alert-dismissable fade in text-center text-danger fade in">' +
                            data.failure + '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                            
                            /**
                             * [Closes bootstrap alert after 5 seconds with a slideup animation]
                             */
                            $(".alert-dismissable").fadeTo(5000, 500).slideUp(500, function(){
                               $(".alert-dismissable").alert('close');
                            });

                            $.each(data, function(index, error) {
                            index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                            if($('#login_password_error').length != 0)
                            {
                                $('[type=password]').val('');
                            }
                        });
                    }
                    else //show errors
                    {
                        $('#login-error').remove();
                        $('.modal-dialog').addClass('shake')
                        setTimeout( function(){ 
                            $('.modal-dialog').removeClass('shake'); 
                        }, 1000 ); 
                        $.each(data, function(index, error) {
                            index.length !== 0 ? $('#' + index + '_error').html(error) : $('#' + index + '_error').html('');
                            if($('#login_password_error').length != 0)
                            {
                                $('[type=password]').val('');
                            }
                        });
                    }
                }
                else
                {
                    alert();
                }
            }
        });

        return false;
    }

/*=====  End of createOrUpdateByAjax comment block  ======*/

    /*=============================================
    =  submitLikeDislike comment block         =
    =============================================*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function submitLikeDislike(userId, action)
    {
        var controller = path_parts[3] == 'column' ? 'columnist' : path_parts[3];
        var mainEntityId = $('#mainEntityId').val();
        var data = {'user_id': userId, 'action': action, 'main_entity_id': mainEntityId};
        // ajax call
        $.ajax({
            url: base_url + controller + '/like_dislike_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);

                if(data.like !== undefined) 
                {
                    var like = data.like;    
                    like = like.split('_');
                    $('#thumbs-up-icon').css('color', '#2195DE');
                    $('#thumbs-up-icon').removeClass('thumbs-icon-active');
                    $('#thumbs-up-icon').addClass('thumbs-icon-active');
                    $('#thumbs-up-count').text('');
                    $('#thumbs-up-count').text(like[1]);    
                }
                else if(data.already_liked !== undefined) 
                {
                    var already_liked = data.already_liked;    
                    already_liked = already_liked.split('_');
                    // $('#thumbs-up-icon').css('color', '#2195D');
                    $('#thumbs-up-icon').removeClass('thumbs-icon-active');
                    $('#thumbs-up-icon').addClass('thumbs-icon-active');
                    $('#thumbs-up-count').  text('');
                    $('#thumbs-up-count').text(already_liked[1]);    
                }
                else if(data.already_disliked !== undefined) 
                {
                    var already_disliked = data.already_disliked;    
                    already_disliked = already_disliked.split('_');
                    // $('#thumbs-down-icon').css('color', '#ff2800');
                    $('#thumbs-down-icon').addClass('thumbs-icon-active');
                    $('#thumbs-down-count').text('');
                    $('#thumbs-down-count').text(already_disliked[1]);    
                }
                else if(data.like_change !== undefined && data.dislike_change !== undefined && data.action_change !== undefined) 
                {
                    var like_change = data.like_change;   
                    var dislike_change = data.dislike_change;   
                    var action_change = data.action_change;   
                    dislike_change = dislike_change.split('_');
                    like_change = like_change.split('_');
                    var action = action_change == 'like' ? 'up' : 'down';
                    var action_opposite = action_change == 'like' ? 'down' : 'up';
                    var color = action_change == 'like' ? '#2195DE' : '#ff2800';
                    $('#thumbs-up-icon').removeClass('thumbs-icon-active');
                    $('#thumbs-down-icon').removeClass('thumbs-icon-active');
                    $('#thumbs-' + action_opposite + '-icon').css('color', '');
                    $('#thumbs-' + action + '-icon').css('color', color);
                    $('#thumbs-' + action + '-icon').addClass('thumbs-icon-active');
                    
                    $('#thumbs-up-count').text('');
                    $('#thumbs-up-count').text(like_change[1]);    
                    $('#thumbs-down-count').text('');
                    $('#thumbs-down-count').text(dislike_change[1]);
                }
                else
                {
                    if(data.dislike !== undefined)
                    {
                        var dislike = data.dislike;    
                        dislike = dislike.split('_');
                        $('#thumbs-down-icon').css('color', '#ff2800');
                        $('#thumbs-down-icon').removeClass('thumbs-icon-active');
                        $('#thumbs-down-icon').addClass('thumbs-icon-active');
                        $('#thumbs-down-count').text('');
                        $('#thumbs-down-count').text(dislike[1]);
                    }
                }
            } // success
        });

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/

    /*=============================================
    =  submitLikeDislike comment block         =
    =============================================*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function submitPostLikeDislike(userId, action)
    {
        var post_id = $('#postId').val();
        var main_entity_name = $('#mainEntityName').val();
        var data = {'user_id': userId, 'action': action, 'post_id': post_id, 'main_entity_name': main_entity_name};
        // ajax call
        $.ajax({
            url: base_url_admin  + 'post/like_dislike_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);

                if(data.like !== undefined) 
                {
                    var like = data.like;    
                    like = like.split('_');
                    $('#post-like').html('Unlike');
                    $('#post-like-count').text('');
                    $('#post-like-count').text(like[1]);    
                }
                else if(data.already_liked !== undefined) 
                {
                    var already_liked = data.already_liked;    
                    already_liked = already_liked.split('_');
                    $('#post-like').html('Like');
                    $('#post-like-count').text('');
                    $('#post-like-count').text(already_liked[1]);    
                }
                else if(data.already_disliked !== undefined) 
                {
                    var already_disliked = data.already_disliked;    
                    already_disliked = already_disliked.split('_');
                    $('#post-dislike').html('Dislike');
                    $('#post-dislike-count').text('');
                    $('#post-dislike-count').text(already_disliked[1]);    
                }
                else if(data.like_change !== undefined && data.dislike_change !== undefined && data.action_change !== undefined) 
                {
                    var like_change = data.like_change;   
                    var dislike_change = data.dislike_change;   
                    var action_change = data.action_change;   
                    dislike_change = dislike_change.split('_');
                    like_change = like_change.split('_');

                    if(action_change == 'like')
                    {
                        $('#post-like').html('Unlike');
                        $('#post-dislike').html('Dislike');
                    }
                    else
                    {
                        $('#post-like').html('Like');        
                        $('#post-dislike').html('Undislike');
                    }
                    
                    $('#post-like-count').text('');
                    $('#post-like-count').text(like_change[1]);    
                    $('#post-dislike-count').text('');
                    $('#post-dislike-count').text(dislike_change[1]);   
                }
                else
                {
                    if(data.dislike !== undefined)
                    {
                        var dislike = data.dislike;    
                        dislike = dislike.split('_');
                        $('#post-dislike').html('Undislike');
                        $('#post-dislike-count').text('');
                        $('#post-dislike-count').text(dislike[1]);    
                    }
                }
            } // success
        });

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/    /*=============================================
    =  submitLikeDislike comment block         =
    =============================================
*/
    /*=============================================
    =  submitLikeDislike comment block         =
    =============================================*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function submitColumnLikeDislike(userId, action)
    {
        var column_id = $('#ColumnId').val();
        var main_entity_name = $('#mainEntityName').val();
        var data = {'user_id': userId, 'action': action, 'column_id': column_id, 'main_entity_name': main_entity_name};
        // ajax call
        $.ajax({
            url: base_url  + 'column/like_dislike_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);

                if(data.like !== undefined) 
                {
                    var like = data.like;    
                    like = like.split('_');
                    $('#post-like').html('Unlike');
                    $('#post-like-count').text('');
                    $('#post-like-count').text(like[1]);    
                }
                else if(data.already_liked !== undefined) 
                {
                    var already_liked = data.already_liked;    
                    already_liked = already_liked.split('_');
                    $('#post-like').html('Like');
                    $('#post-like-count').text('');
                    $('#post-like-count').text(already_liked[1]);    
                }
                else if(data.already_disliked !== undefined) 
                {
                    var already_disliked = data.already_disliked;    
                    already_disliked = already_disliked.split('_');
                    $('#post-dislike').html('Dislike');
                    $('#post-dislike-count').text('');
                    $('#post-dislike-count').text(already_disliked[1]);    
                }
                else if(data.like_change !== undefined && data.dislike_change !== undefined && data.action_change !== undefined) 
                {
                    var like_change = data.like_change;   
                    var dislike_change = data.dislike_change;   
                    var action_change = data.action_change;   
                    dislike_change = dislike_change.split('_');
                    like_change = like_change.split('_');

                    if(action_change == 'like')
                    {
                        $('#post-like').html('Unlike');
                        $('#post-dislike').html('Dislike');
                    }
                    else
                    {
                        $('#post-like').html('Like');        
                        $('#post-dislike').html('Undislike');
                    }
                    
                    $('#post-like-count').text('');
                    $('#post-like-count').text(like_change[1]);    
                    $('#post-dislike-count').text('');
                    $('#post-dislike-count').text(dislike_change[1]);   
                }
                else
                {
                    if(data.dislike !== undefined)
                    {
                        var dislike = data.dislike;    
                        dislike = dislike.split('_');
                        $('#post-dislike').html('Undislike');
                        $('#post-dislike-count').text('');
                        $('#post-dislike-count').text(dislike[1]);    
                    }
                }
            } // success
        });

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/    /*=============================================
    =  submitLikeDislike comment block         =
    =============================================
*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function submitCommentReplyLike(comment_reply_id, userId, entity)
    {
        var data = {'user_id': userId, 'comment_reply_id': comment_reply_id, 'entity': entity};
        // ajax call
        $.ajax({
            url: base_url  + 'comment/insert_comment_reply_like_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);

                if(entity == 'comment')
                {
                    if(data.like !== undefined) 
                    {
                        var like = data.like;    
                        like = like.split('_');
                        $('#comm-like-' + comment_reply_id).html('Unlike');
                        $('#comment-like-count-' + comment_reply_id).text('');
                        $('#comment-like-count-' + comment_reply_id).text(like[1]);    
                    }
                    else 
                    {
                        if(data.already_liked !== undefined) 
                        {
                            var already_liked = data.already_liked;    
                            already_liked = already_liked.split('_');
                            already_liked[2] = already_liked[2] > 0 ? already_liked[2] : ''; 
                            $('#comm-like-' + comment_reply_id).html('Like');
                            $('#comment-like-count-' + comment_reply_id).text('');
                            $('#comment-like-count-' + comment_reply_id).text(already_liked[2]);    
                        }
                    }
                }
                if(entity == 'reply')
                {
                    if(data.like !== undefined) 
                    {
                        var like = data.like;    
                        like = like.split('_');
                        $('#reply-like-' + comment_reply_id).html('Unlike');
                        $('#reply-like-count-' + comment_reply_id).text('');
                        $('#reply-like-count-' + comment_reply_id).text(like[1]);    
                    }
                    else 
                    {
                        if(data.already_liked !== undefined) 
                        {
                            var already_liked = data.already_liked;    
                            already_liked = already_liked.split('_');
                            already_liked[2] = already_liked[2] > 0 ? already_liked[2] : ''; 
                            $('#reply-like-' + comment_reply_id).html('Like');
                            $('#reply-like-count-' + comment_reply_id).text('');
                            $('#reply-like-count-' + comment_reply_id).text(already_liked[2]);    
                        }
                    }
                }
            } // success
        });

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function submitColumnCommentReplyLike(comment_reply_id, userId, entity)
    {
        var data = {'user_id': userId, 'comment_reply_id': comment_reply_id, 'entity': entity};
        // ajax call
        $.ajax({
            url: base_url  + 'comment/insert_column_comment_reply_like_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);

                if(entity == 'comment')
                {
                    if(data.like !== undefined) 
                    {
                        var like = data.like;    
                        like = like.split('_');
                        $('#comm-like-' + comment_reply_id).html('Unlike');
                        $('#comment-like-count-' + comment_reply_id).text('');
                        $('#comment-like-count-' + comment_reply_id).text(like[1]);    
                    }
                    else 
                    {
                        if(data.already_liked !== undefined) 
                        {
                            var already_liked = data.already_liked;    
                            already_liked = already_liked.split('_');
                            already_liked[2] = already_liked[2] > 0 ? already_liked[2] : ''; 
                            $('#comm-like-' + comment_reply_id).html('Like');
                            $('#comment-like-count-' + comment_reply_id).text('');
                            $('#comment-like-count-' + comment_reply_id).text(already_liked[2]);    
                        }
                    }
                }
                if(entity == 'reply')
                {
                    if(data.like !== undefined) 
                    {
                        var like = data.like;    
                        like = like.split('_');
                        $('#reply-like-' + comment_reply_id).html('Unlike');
                        $('#reply-like-count-' + comment_reply_id).text('');
                        $('#reply-like-count-' + comment_reply_id).text(like[1]);    
                    }
                    else 
                    {
                        if(data.already_liked !== undefined) 
                        {
                            var already_liked = data.already_liked;    
                            already_liked = already_liked.split('_');
                            already_liked[2] = already_liked[2] > 0 ? already_liked[2] : ''; 
                            $('#reply-like-' + comment_reply_id).html('Like');
                            $('#reply-like-count-' + comment_reply_id).text('');
                            $('#reply-like-count-' + comment_reply_id).text(already_liked[2]);    
                        }
                    }
                }
            } // success
        });

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function deleteColumnCommentReply(comment_reply_id, userId, entity, _this)
    {
        var column_id = $('#ColumnId').val();
        var data = {'user_id': userId, 'column_id': column_id, 'comment_reply_id': comment_reply_id, 'entity': entity, 
        'main_entity_id': main_entity_id, 'main_entity_name': main_entity_name};
        
        // ajax call
        $.ajax({
            url: base_url  + 'comment/delete_column_comment_reply_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);
                if(entity == 'comment')
                {
                    $('#post-comment-count').html(data.comment_count);
                    _this.closest('.comment').remove();
                }
                if(entity == 'reply')
                {
                    _this.closest('.post-reply').remove();
                }
            } // success
        });  

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/

    /**
     * [makeAjaxCall This function will make an ajax call for inserting 
     * and updating data to the database]
     * @param  {[type]} formId   [form id e.g. 'add_user_form' with out hash]
     * @param  {[type]} redirectPath [redirect path with protocol and host 
     * e.g '/pak_democrates/f/user/']
     * @return {[type]} false [e.preventDefault(); e.stopPropagation(); 
     * To prevent event from propagating (or "bubbling up") the DOM. So 
     * parent element event won;t trigger 
     * link: https://css-tricks.com/return-false-and-prevent-default/]   
     */
    function deleteCommentReply(comment_reply_id, userId, entity, _this)
    {
        var data = {'user_id': userId, 'comment_reply_id': comment_reply_id, 'entity': entity, 
        'main_entity_id': main_entity_id, 'main_entity_name': main_entity_name};
        
        // ajax call
        $.ajax({
            url: base_url  + 'comment/delete_comment_reply_lookup/',
            type: 'POST',
            data: data, // user un-encoded data
            success: function (data) {
                // Takes a well-formed JSON string and returns the resulting JavaScript value.
                data = jQuery.parseJSON(data);
                if(entity == 'comment')
                {
                    $('#post-comment-count').html(data.comment_count);
                    _this.closest('.comment').remove();
                }
                if(entity == 'reply')
                {
                    _this.closest('.post-reply').remove();
                }
            } // success
        });  

        return false;
    }

/*=====  End of submitLikeDislike comment block  ======*/

    /*=============================================
    =            getLikesDislikes block            =
    =============================================*/

    function getLikesDislikes(entityID, userID)
    {
        var url;
        if(path_parts[3] == 'column')
        {
            url = base_url + 'columnist/get_likes_dislikes_lookup/';
        }
        else
        {
            url = base_url + path_parts[3] + '/get_likes_dislikes_lookup/';
        }
        // calling ajax
        jQuery.ajax({
            url: url,
            method: 'post',
            data: {'entity_id': entityID, 'user_id': userID},
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                if(data.like !== undefined) 
                {
                    $('#thumbs-up-icon').css('color', '#2195DE');
                }
                if(data.dislike !== undefined) 
                {
                    $('#thumbs-down-icon').css('color', 'red');
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        }); 
    }
    
    /*=====  End of getLikesDislikes block  ======*/
    
    /*=============================================
    =            getColumnLikesDislikes block       =
    =============================================*/
    
    function getColumnLikesDislikes(userID)
    {
        // calling ajax
        jQuery.ajax({
            url: base_url + 'column/get_likes_dislikes_lookup/',
            method: 'post',
            data: {'column_id': main_entity_id, 'user_id': userID},
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                if(data.like !== undefined) 
                {
                    $('#post-like').html('Unlike');
                }
                if(data.dislike !== undefined) 
                {
                    $('#post-dislike').html('Undislike');
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        }); 
    }
    
    /*=====  End of getColumnLikesDislikes block  ======*/  

    /*=============================================
    =            getPostLikesDislikes block       =
    =============================================*/
    
    function getPostLikesDislikes(userID)
    {
        var post_id = $('#postId').val();
        // calling ajax
        jQuery.ajax({
            url: base_url_admin + 'post/get_likes_dislikes_lookup/',
            method: 'post',
            data: {'post_id': post_id, 'user_id': userID},
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                if(data.like !== undefined) 
                {
                    $('#post-like').html('Unlike');
                }
                if(data.dislike !== undefined) 
                {
                    $('#post-dislike').html('Undislike');
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        }); 
    }
    
    /*=====  End of getPostLikesDislikes block  ======*/  

    /*=============================================
    =            getColumnCommentLikes block       =
    =============================================*/
    
    function getColumnCommentLikes(userID)
    {
        var column_id = $('#ColumnId').val();
        // calling ajax
        jQuery.ajax({
            url: base_url + 'comment/get_column_comment_likes_lookup/',
            method: 'post',
            data: {'column_id': column_id, 'user_id': userID},
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                var comment_likes_db = data.comment_likes_db;
                var reply_likes_db = data.reply_likes_db;
                if(comment_likes_db !== undefined) 
                {
                    $.each(comment_likes_db, function(i){
                        $.each(comment_likes_db[i], function(key, value){
                            $('#comm-like-' + value).html('Unlike');
                        });
                    });
                    if(reply_likes_db != null)
                    {
                        $.each(reply_likes_db, function(i){
                            $.each(reply_likes_db[i], function(key, value){ 
                                $('#reply-like-' + value).html('Unlike');
                            });
                        });
                    }
                    
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        }); 
    }
    
    /*=====  End of getColumnCommentLikes block  ======*/
    
    /*=============================================
    =            getCommentLikesDislikes block       =
    =============================================*/
    
    function getCommentLikes(userID)
    {
        var post_id = $('#postId').val();
        // calling ajax
        jQuery.ajax({
            url: base_url + 'comment/get_comment_likes_lookup/',
            method: 'post',
            data: {'post_id': post_id, 'user_id': userID},
            success: function(data)
            {
                data = jQuery.parseJSON(data);
                var comment_likes_db = data.comment_likes_db;
                var reply_likes_db = data.reply_likes_db;
                if(comment_likes_db !== undefined) 
                {
                    $.each(comment_likes_db, function(i){
                        $.each(comment_likes_db[i], function(key, value){
                            $('#comm-like-' + value).html('Unlike');
                        });
                    });
                    if(reply_likes_db != null)
                    {
                        $.each(reply_likes_db, function(i){
                            $.each(reply_likes_db[i], function(key, value){ 
                                $('#reply-like-' + value).html('Unlike');
                            });
                        });
                    }
                    
                }
            },
            error: function()
            {
                alert('Something went wrong!');
            }
        }); 
    }
    
    /*=====  End of getPostLikesDislikes block  ======*/
    

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
    = Login/Registeration Modal comment block     =
    ===============================================*/
    

    /**
    * [description: Triggers when '#add_halqa_form' is submitted. Prevents normal form submission
    * and calls createOrUpdateByAjax(); for ajax form submission]
    */
    $('#registeration-form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('registeration-form');
    });

    /**
    * [description: Triggers when '#add_halqa_form' is submitted. Prevents normal form submission
    * and calls createOrUpdateByAjax(); for ajax form submission]
    */
    $('#login-form').on('submit', function(e){
        e.preventDefault();
        createOrUpdateByAjax('login-form');
    });
    
    
    /*=====  End of Login/Registeration Modal comment block  ======*/
    

/*        jQuery('#registeration-form').validate(
        {
            rules: {
                first_name: {
                    minlength: 2,
                    maxlength: 50,
                    required: true,
                    alpha: true
            },
                last_name: {
                    minlength: 2,
                    maxlength: 50,
                    required: true,
                    alpha: true
            },
            email: {
                required: true,
                minlength: 15,
                maxlength: 70,
                email: true
            },
            mobile_number: {
                required: true,
                minlength: 11,
                maxlength: 11
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 64
            },
            password_confirmation: {
                required: true,
                minlength: 6,
                maxlength: 64
            }
        },

        messages: {
                first_name:{
                    required: "Please enter your first name",
                    minlength: "Min {0} characters are required",
                    maxlength: "Max {0} characters are allowed"
                },                       
                last_name:{
                    required: "Please enter your last name",
                    minlength: "Min {0} characters are required",
                    maxlength: "Max {0} characters are allowed"                
                },
                email:{
                    required: "Please enter your email address",
                    minlength: "Min {0} characters are required",
                    maxlength: "Max {0} characters are allowed"           
                },
                mobile_number:{
                    required: "Please enter your mobile number",
                    minlength: "Min {0} characters are required",
                    maxlength: "Max {0} characters are allowed"
                },
                password:{
                    required: "Please enter your password",
                    minlength: "Min {0} characters are required",
                    maxlength: "Max {0} characters are allowed"
                },
                password_confirmation:{
                    required: "Please enter your repeat password",
                    minlength: "Min {0} characters are required",
                    maxlength: "Max {0} characters are allowed"
                }
            },
            
        highlight: function(element) {
        jQuery(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function(element) {
        element
        .text('').addClass('valid')
        .closest('.form-group').removeClass('error').addClass('success');
        }
        ,
        submitHandler: function(form) {
            // do other stuff for a valid form
            jQuery.post('user/add_user_lookup', jQuery("#registeration-form").serialize(), function(data) { // action file is here 
                // jQuery('#post_message').html(data);
            });
        }
        });*/

        /*
         *
         * login-register modal
         * Autor: Creative Tim
         * Web-autor: creative.tim
         * Web script: http://creative-tim.com
         * 
         */

        function closeModel(id) 
        {
            var id = '#' + id;
            jQuery(id).modal('hide');
            setTimeout(function(){
                jQuery(id).remove();
                jQuery('.modal-backdrop').remove();
            },100);
        }

        $(document).on('click', '#create_account', function(){
            showRegisterForm();
        });

        $(document).on('click', '#already_account', function(){
            showLoginForm();
        });


        function showRegisterForm(){
            $('#registeration-form')[0].reset();
            $('.loginBox').fadeOut('fast',function(){
                $('.registerBox').fadeIn('fast');
                $('.login-footer').fadeOut('fast',function(){
                    $('.register-footer').fadeIn('fast');
                });
                $('.modal-title').html('Register with');
            }); 
            $('.error').removeClass('alert alert-danger').html('');
               
        }
        function showLoginForm(likeDislike){
            var likeDislike = (typeof(likeDislike) == undefined) ? '' : likeDislike;
            $('#loginModal .registerBox').fadeOut('fast',function(){
                $('.loginBox').fadeIn('fast');
                $('.register-footer').fadeOut('fast',function(){
                    $('.login-footer').fadeIn('fast');    
                    // $('#login-form')[0].reset();
                });
                
                $('.modal-title').html('Login with');
            });       
             $('.error').removeClass('alert alert-danger').html(''); 
        }

        function openLoginModal(likeDislike){
            showLoginForm(likeDislike);
            setTimeout(function(){
                $('#loginModal').modal({'toggle': true, 'backdrop': 'static'});    
            }, 230);
            
        }
        function openRegisterModal(){
            showRegisterForm();
            setTimeout(function(){
                $('#loginModal').modal({'toggle': true, 'backdrop': 'static'});    
            }, 230);
            
        }

        function loginAjax(){
            var email = $('#email').val();
            var password = $('#password').val();
            $.post( base_url + "login/login_lookup/" + email + '/' + password, function( data ) {
                /*if(data == 1){
                    window.location.replace("/home");            
                } else {
                     shakeModal(); 
                }*/
            });
        }

        function shakeModal(){
            $('#loginModal .modal-dialog').addClass('shake');
                     $('.error').addClass('alert alert-danger').html("Invalid email/password combination");
                     $('input[type="password"]').val('');
                     setTimeout( function(){ 
                        $('#loginModal .modal-dialog').removeClass('shake'); 
            }, 1000 ); 
        }
    });

    
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



    /**
     * Removes class entity-details to avoid margin problem
     */

    (function($) {
        var $window = $(window),
            $html = $('html');

        function resize() {
            if ($window.width() > 991 && $window.width() < 1200) {
                // $html.removeClass('entity-details');
/*
                $('#entity-details-1').removeClass('entity-details');
                $('#entity-details-2').removeClass('entity-details');
                $('#entity-details-1').addClass('entity-details-md');
                $('#entity-details-2').addClass('entity-details-md');*/
                
/*                $('.comm-details').addClass('entity-details-md');
                $('.comm-details').removeClass('comm-details');*/

                // $html.add('entity-details');
                // $html.removeClass('entity-details');
                // return $html.addClass('mobile');
            }
        }


        $window
            .resize(resize)
            .trigger('resize');
  })(jQuery);
