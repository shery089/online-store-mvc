// $(function(){
//     var origin = window.location.origin;
//     // http://localhost

//     var pathname = window.location.pathname;
//     // /ims/admin/user/add_user_lookup

//     var path_parts = pathname.split('/');
//     var base_url = origin + '/ims/f/';
//     var base_url_admin = origin + '/ims/admin/';
// 	var isLoggedIn = $.cookie('isLoggedIn');
// 	if(isLoggedIn == 'true')
// 	{
// 	    var userData = $.cookie('user');
// 	    var userLen = $.cookie('user').length;
// 	    var user = userData.split(',');
// 	}

//   	$(".Fr-star.userChoose").Fr_star(function(rating){
//     	$.post(base_url_admin + "star/add_rating_lookup", {'id' : 'index_page', 'rating': rating}, function(){
//       	alert("Rated" + rating + " !!");
//     });
    	
//   });
// });